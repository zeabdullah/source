<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\AiChat;
use App\Models\EmailTemplate;
use App\Models\Screen;
use App\Services\AiAgentService;
use App\Services\N8nService;
use App\Services\FigmaService;
use App\Services\BrevoService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Gemini\Data\Content;
use Gemini\Enums\Role;
use GuzzleHttp\Exception\RequestException;

/**
 * @OA\Tag(
 *     name="AI Chat",
 *     description="AI chat management endpoints for screens and email templates"
 * )
 */
class AiChatController extends Controller
{
    /**
     * @OA\Post(
     *     path="/email-templates/{emailTemplateId}/chats",
     *     summary="Send email template chat message",
     *     description="Send a chat message to AI for an email template and get AI response",
     *     tags={"AI Chat"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="emailTemplateId",
     *         in="path",
     *         description="Email template ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="content", type="string", example="Please improve this email template")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Chat message sent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", ref="#/components/schemas/AiChat"),
     *             @OA\Property(property="ai", ref="#/components/schemas/AiChat"),
     *             @OA\Property(property="template_updated", type="boolean", example=true),
     *             @OA\Property(property="brevo_updated", type="boolean", example=false),
     *             @OA\Property(property="thumbnail_updated", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(response=404, description="Email template not found"),
     *     @OA\Response(response=422, description="Validation errors"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function sendEmailTemplateChatMessage(Request $request, string $emailTemplateId, AiAgentService $ai, BrevoService $brevo, N8nService $n8n): JsonResponse
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);
        $userPrompt = $validated['content'];

        try {
            $emailTemplate = EmailTemplate::find($emailTemplateId);
            if (!$emailTemplate) {
                return $this->notFoundResponse('Email template not found');
            }

            $result = DB::transaction(function () use ($request, $emailTemplate, $emailTemplateId, $userPrompt, $ai, $brevo, $n8n) {
                // create user message
                $userAiChat = new AiChat(['content' => $userPrompt]);
                $userAiChat->sender = 'user';
                $userAiChat->user_id = $request->user()->id;
                $userAiChat->commentable_id = $emailTemplateId;
                $userAiChat->commentable_type = EmailTemplate::class;

                // feed history of messages as ai context
                $contextMessages = $emailTemplate->aiChats()
                    ->orderBy('created_at', 'desc')
                    ->limit(15)
                    ->get(['sender', 'content'])
                    ->reverse()
                    ->values()
                    ->map(fn($msg) => Content::parse(
                        $msg->content,
                        $msg->sender === 'user' ? Role::USER : Role::MODEL
                    ))
                    ->toArray();

                // Get AI response with structured output
                $aiResponse = $ai->generateEmailTemplateReply($userPrompt, $contextMessages, $emailTemplate->html_content);
                $aiReplyText = $aiResponse['chat_message'];
                $updatedHtml = $aiResponse['updated_html'];

                $brevoUpdated = false;
                $thumbnailUpdated = false;
                if ($updatedHtml !== null) {
                    $emailTemplate->html_content = $updatedHtml;

                    // Generate new thumbnail from updated HTML
                    try {
                        $base64Img = $n8n->generateBase64ThumbnailFromHtml($updatedHtml);
                        if ($base64Img) {
                            $binaryImg = base64_decode($base64Img);
                            if ($binaryImg !== false) {
                                // Delete old thumbnail if exists
                                if (!empty($emailTemplate->thumbnail_url)) {
                                    $oldPath = str_replace(asset('storage') . '/', '', $emailTemplate->thumbnail_url);
                                    if (Storage::exists($oldPath)) {
                                        Storage::delete($oldPath);
                                    }
                                }
                                $thumbnailPath = 'email-thumbnails/' . uniqid('et_', true) . '.png';
                                Storage::put($thumbnailPath, $binaryImg);
                                $emailTemplate->thumbnail_url = Storage::url($thumbnailPath);
                                $thumbnailUpdated = true;
                            }
                        }
                    } catch (\Throwable $thumbnailException) {
                        Log::warning('Failed to generate thumbnail: ' . $thumbnailException->getMessage(), [
                            'email_template_id' => $emailTemplateId,
                        ]);
                        // Continue execution even if thumbnail generation fails
                    }

                    $emailTemplate->save();

                    // Update Brevo template if linked and user has API token
                    $user = $request->user();
                    if ($user->brevo_api_token && $emailTemplate->brevo_template_id) {
                        try {
                            $brevoTemplateData = [
                                'htmlContent' => $updatedHtml,
                            ];
                            $brevo->updateTemplate($user->brevo_api_token, $emailTemplate->brevo_template_id, $brevoTemplateData);
                            $brevoUpdated = true;
                        } catch (RequestException $e) {
                            Log::warning('Failed to update Brevo template: ' . $e->getMessage(), [
                                'email_template_id' => $emailTemplateId,
                                'brevo_template_id' => $emailTemplate->brevo_template_id,
                                'user_id' => $user->id,
                            ]);
                            // Continue execution even if Brevo update fails
                        }
                    }
                }

                // create ai message
                $aiReply = new AiChat([
                    'content' => $aiReplyText,
                ]);
                $aiReply->user_id = null;
                $aiReply->sender = 'ai';
                $aiReply->commentable_id = $userAiChat->commentable_id;
                $aiReply->commentable_type = $userAiChat->commentable_type;

                $userAiChat->saveOrFail();
                $aiReply->saveOrFail();

                return [
                    'user' => $userAiChat->fresh(),
                    'ai' => $aiReply->fresh(),
                    'template_updated' => $updatedHtml !== null,
                    'brevo_updated' => $brevoUpdated,
                    'thumbnail_updated' => $thumbnailUpdated,
                ];
            }, attempts: 2);

            return $this->responseJson($result, 'Chat message created successfully', 201);
        } catch (\Throwable $th) {
            Log::error('Failed to send chat message: ' . $th->getMessage(), [
                'trace' => $th->getTrace(),
            ]);

            return $this->serverErrorResponse('Failed to send chat message: ' . $th->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/email-templates/{emailTemplateId}/chats",
     *     summary="Get email template chat",
     *     description="Get all chat messages for an email template",
     *     tags={"AI Chat"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="emailTemplateId",
     *         in="path",
     *         description="Email template ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of chat messages",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/AiChat")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Email template not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function getEmailTemplateChat(Request $request, string $emailTemplateId): JsonResponse
    {
        try {
            $template = EmailTemplate::find($emailTemplateId);
            if (!$template) {
                return $this->notFoundResponse('Email template not found');
            }

            $project = $template->project;
            $user = $request->user();

            $isMember = $project->members()->where('users.id', $user->id)->exists();
            $isOwner = $project->owner_id === $user->id;
            if (!($isOwner || $isMember)) {
                return $this->notFoundResponse('Project not found');
            }

            $chats = $template->aiChats()
                ->orderBy('created_at', 'asc')
                ->get();

            return $this->responseJson($chats);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to retrieve chat messages: ' . $th->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/screens/{screenId}/chats",
     *     summary="Send screen chat message",
     *     description="Send a chat message to AI for a screen and get AI response",
     *     tags={"AI Chat"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="screenId",
     *         in="path",
     *         description="Screen ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="content", type="string", example="Please analyze this screen design")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Chat message sent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", ref="#/components/schemas/AiChat"),
     *             @OA\Property(property="ai", ref="#/components/schemas/AiChat")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Figma access token required"),
     *     @OA\Response(response=404, description="Screen not found"),
     *     @OA\Response(response=422, description="Validation errors"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function sendScreenChatMessage(Request $request, string $screenId, AiAgentService $ai, FigmaService $figma): JsonResponse
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);
        $accessToken = $request->user()->figma_access_token;

        if (!$accessToken) {
            return $this->forbiddenResponse('You must set a valid Figma access token to your account to send an AI chat message');
        }

        $userMsg = $validated['content'];


        try {
            $screen = Screen::find($screenId);
            if (!$screen) {
                return $this->notFoundResponse('Screen not found');
            }

            $result = DB::transaction(function () use ($request, $screen, $screenId, $userMsg, $accessToken, $ai, $figma) {
                $figmaCacheKey = "figma_frame_data_{$screenId}";

                // create user message
                $chatMsg = new AiChat([
                    'content' => $userMsg,
                ]);
                $chatMsg->sender = 'user';
                $chatMsg->user_id = $request->user()->id;
                $chatMsg->commentable_id = $screenId;
                $chatMsg->commentable_type = Screen::class;

                // feed history of messages as ai context
                $contextMessages = $screen->aiChats()
                    ->orderBy('created_at', 'desc')
                    ->limit(15)
                    ->get(['sender', 'content'])
                    ->reverse()
                    ->values()
                    ->map(fn($msg) => Content::parse(
                        $msg->content,
                        $msg->sender === 'user' ? Role::USER : Role::MODEL
                    ))
                    ->toArray();

                $figmaNodes = null;
                // Fetch and add Figma data to context messages using cache
                if ($screen->hasFigmaData()) {
                    $figmaNodes = cache()->remember(
                        $figmaCacheKey,
                        now()->addHours(12),
                        function () use ($accessToken, $screen, $figma) {
                            return $figma->getFigmaFrameForAI(
                                $screen->figma_node_id,
                                $screen->figma_file_key,
                                $accessToken,
                            );
                        }
                    );
                } else {
                    // Fetch from API and cache it
                    $figmaNodes = cache()->remember(
                        $figmaCacheKey,
                        now()->addHours(12),
                        function () use ($accessToken, $screen, $figma) {
                            return $figma->getFigmaFrameForAI(
                                $screen->figma_node_id,
                                $screen->figma_file_key,
                                $accessToken,
                            );
                        }
                    );
                }

                $aiReply = $ai->generateFigmaReply($userMsg, $contextMessages, $figmaNodes);

                // create ai message
                $aiReply = new AiChat([
                    'content' => $aiReply['chat_message'],
                ]);
                $aiReply->user_id = null;
                $aiReply->sender = 'ai';
                $aiReply->commentable_id = $chatMsg->commentable_id;
                $aiReply->commentable_type = $chatMsg->commentable_type;

                $chatMsg->saveOrFail();

                // Ensure AI reply has a later timestamp
                // since transactions don't guarantee sequential order of timestamps
                $aiReply->created_at = now()->addSecond();
                $aiReply->saveOrFail();

                return [
                    'user' => $chatMsg->fresh(),
                    'ai' => $aiReply->fresh(),
                ];
            }, attempts: 2);

            return $this->responseJson($result, 'Created successfully', 201);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to send chat message: ' . $th->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/screens/{screenId}/chats",
     *     summary="Get screen chat",
     *     description="Get all chat messages for a screen",
     *     tags={"AI Chat"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="screenId",
     *         in="path",
     *         description="Screen ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of chat messages",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/AiChat")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Screen not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function getScreenChat(Request $request, string $screenId): JsonResponse
    {
        try {
            $screen = Screen::find($screenId);
            if (!$screen) {
                return $this->notFoundResponse('Screen not found');
            }

            $project = $screen->project;
            $user = $request->user();

            $isMember = $project->members()->where('users.id', $user->id)->exists();
            $isOwner = $project->owner_id === $user->id;
            if (!($isOwner || $isMember)) {
                return $this->notFoundResponse('Project not found');
            }

            $chats = $screen->aiChats()
                ->orderBy('created_at', 'asc')
                ->get();

            return $this->responseJson($chats);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to retrieve chat messages: ' . $th->getMessage());
        }
    }


    /**
     * @OA\Put(
     *     path="/chats/{chatId}",
     *     summary="Update chat message",
     *     description="Update a chat message by its ID (user messages only)",
     *     tags={"AI Chat"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="chatId",
     *         in="path",
     *         description="Chat message ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="content", type="string", example="Updated message content")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Chat message updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/AiChat")
     *     ),
     *     @OA\Response(response=403, description="Not authorized to update this message"),
     *     @OA\Response(response=404, description="Chat message not found"),
     *     @OA\Response(response=422, description="Validation errors"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function updateChatMessageById(Request $request, string $chatId): JsonResponse
    {
        $validated = $request->validate([
            'content' => 'required|string'
        ]);

        try {
            $chatMsg = AiChat::find($chatId);

            if (!$chatMsg) {
                return $this->notFoundResponse('Chat message not found');
            }

            $isMsgAuthor = $chatMsg->user_id === $request->user()->id;
            if (!$isMsgAuthor) {
                return $this->forbiddenResponse('You are not authorized to update this message');
            }

            $chatMsg->updateOrFail($validated);

            return $this->responseJson($chatMsg->fresh(), 'Chat message updated');
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to update chat message: ' . $th->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *     path="/chats/{chatId}",
     *     summary="Delete chat message",
     *     description="Delete a chat message by its ID (user messages only)",
     *     tags={"AI Chat"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="chatId",
     *         in="path",
     *         description="Chat message ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Chat message deleted successfully",
     *         @OA\JsonContent(ref="#/components/schemas/AiChat")
     *     ),
     *     @OA\Response(response=403, description="Not authorized to delete this message"),
     *     @OA\Response(response=404, description="Chat message not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function deleteChatMessageById(Request $request, string $chatId): JsonResponse
    {
        try {
            $chatMsg = AiChat::find($chatId);

            if (!$chatMsg) {
                return $this->notFoundResponse('Chat message not found');
            }

            if ($chatMsg->user_id !== $request->user()->id) {
                return $this->forbiddenResponse('You are not authorized to update this message');
            }

            $chatMsg->deleteOrFail();

            return $this->responseJson($chatMsg, 'Chat message deleted');
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to delete chat message: ' . $th->getMessage());
        }
    }
}
