<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\N8nService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\AiChat;
use App\Models\EmailTemplate;
use App\Models\Screen;
use App\Services\AiAgentService;
use App\Services\FigmaService;
use Illuminate\Support\Facades\DB;
use Gemini\Data\Content;
use Gemini\Enums\Role;
use Illuminate\Support\Facades\Log;
use App\Services\BrevoService;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Storage;

class AiChatController extends Controller
{
    /**
     * Used for the AI response webhook from N8n.
     */
    public function createAiChatResponseForEmailTemplate(Request $request, string $emailTemplateId): JsonResponse
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);
        $aiMsg = $validated['content'];

        try {
            $emailTemplate = EmailTemplate::find($emailTemplateId);
            if (!$emailTemplate) {
                return $this->notFoundResponse('Email template not found');
            }

            $chatMsg = new AiChat(['content' => $aiMsg]);
            $chatMsg->sender = 'ai';
            $chatMsg->commentable_id = $emailTemplateId;
            $chatMsg->commentable_type = EmailTemplate::class;
            $chatMsg->saveOrFail();

            return $this->responseJson($chatMsg->fresh(), 'AI chat message created successfully', 201);

        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to create AI chat message: ' . $th->getMessage());
        }
    }

    /**
     * Used for the AI response webhook from N8n.
     */
    public function createAiChatResponseForScreen(Request $request, string $screenId): JsonResponse
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);
        $aiMsg = $validated['content'];

        try {
            $screen = Screen::find($screenId);
            if (!$screen) {
                return $this->notFoundResponse('Screen not found');
            }

            $chatMsg = new AiChat(['content' => $aiMsg]);
            $chatMsg->sender = 'ai';
            $chatMsg->commentable_id = $screenId;
            $chatMsg->commentable_type = Screen::class;

            $chatMsg->saveOrFail();

            return $this->responseJson($chatMsg->fresh(), 'AI chat message created successfully', 201);

        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to create AI chat message: ' . $th->getMessage());
        }
    }

    public function sendEmailTemplateChatMessage(Request $request, string $emailTemplateId): JsonResponse
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

            $result = DB::transaction(function () use ($request, $emailTemplate, $emailTemplateId, $userPrompt) {
                $ai = new AiAgentService();
                $brevo = new BrevoService();
                $n8n = new N8nService();

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

    public function getEmailTemplateChatBasic(Request $request, string $emailTemplateId): JsonResponse
    {
        try {
            $template = EmailTemplate::find($emailTemplateId);
            if (!$template) {
                return $this->notFoundResponse('Email template not found');
            }

            $chats = $template->aiChats()
                ->orderBy('created_at', 'asc')
                ->get();

            return $this->responseJson($chats);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to retrieve chat messages: ' . $th->getMessage());
        }
    }

    public function sendScreenChatMessage(Request $request, string $screenId): JsonResponse
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);
        $accessToken = $request->user()->figma_access_token;

        if (!$accessToken) {
            return $this->forbiddenResponse('You must set a valid Figma access token to your account to send an AI chat message');
        }

        $userMsg = $validated['content'];

        $figmaCacheKey = "figma_frame_data_{$screenId}";

        try {
            $screen = Screen::find($screenId);
            if (!$screen) {
                return $this->notFoundResponse('Screen not found');
            }

            $result = DB::transaction(function () use ($request, $screen, $screenId, $userMsg, $accessToken, $figmaCacheKey) {
                $ai = new AiAgentService();
                $figma = new FigmaService();

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

            $chatMsg->updateOrFail($validated['content']);

            return $this->responseJson($chatMsg->fresh(), 'Chat message updated');
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to update chat message: ' . $th->getMessage());
        }
    }

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
