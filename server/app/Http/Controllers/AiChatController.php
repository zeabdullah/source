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

    public function sendEmailTemplateChatMessage(Request $request, string $emailTemplateId, N8nService $n8n, AiAgentService $ai): JsonResponse
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'update_template' => 'boolean',
        ]);
        $userPrompt = $validated['content'];
        $updateTemplate = $validated['update_template'] ?? false;

        try {
            $emailTemplate = EmailTemplate::find($emailTemplateId);
            if (!$emailTemplate) {
                return $this->notFoundResponse('Email template not found');
            }

            $chatMsg = new AiChat(['content' => $userPrompt]);
            $chatMsg->sender = 'user';
            $chatMsg->user_id = $request->user()->id;
            $chatMsg->commentable_id = $emailTemplateId;
            $chatMsg->commentable_type = EmailTemplate::class;
            $chatMsg->saveOrFail();

            if ($updateTemplate && $emailTemplate->brevo_template_id) {
                // Use Gemini AI to update the template
                try {
                    $aiResponse = $ai->generateEmailTemplateUpdate($userPrompt, $emailTemplate->html_content ?? '', $emailTemplateId);

                    // Update the template with AI-generated content
                    $emailTemplate->update([
                        'html_content' => $aiResponse['updated_html'],
                        'section_name' => $aiResponse['updated_name'] ?? $emailTemplate->section_name,
                    ]);

                    $aiResponseContent = $aiResponse['explanation'];
                } catch (\Throwable $aiTh) {
                    Log::error('Failed to generate AI email template update: ' . $aiTh->getMessage(), [
                        'trace' => $aiTh->getTrace(),
                    ]);
                    $aiResponseContent = 'Failed to generate AI email template update. Please check the logs for more details.';
                }
            } else {
                // Return a non-AI response indicating missing requirements
                $aiResponseContent = 'AI response is unavailable because the required information or configuration is missing.';
            }

            return $this->responseJson([
                'user' => $chatMsg->fresh(),
                'ai' => ['content' => $aiResponseContent],
                'template_updated' => $updateTemplate,
            ], 'Messages created successfully', 201);

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

        $figmaCacheKey = "figma_frame_data_{$screenId}";

        try {
            $screen = Screen::find($screenId);
            if (!$screen) {
                return $this->notFoundResponse('Screen not found');
            }

            $result = DB::transaction(function () use ($request, $screen, $screenId, $userMsg, $accessToken, $figmaCacheKey, $ai, $figma) {
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

                // Fetch and add Figma data to context messages using cache
                if ($screen->hasFigmaData()) {
                    $figmaNodes = cache()->remember(
                        $figmaCacheKey,
                        now()->addHours(12),
                        function () use ($accessToken, $screen, $figma) {
                            return $figma->getFigmaFrameForAI(
                                $screen->figma_file_key,
                                $screen->figma_node_id,
                                $accessToken,
                            );
                        }
                    );

                    $figmaContext = "Figma Frame Data: " . json_encode($figmaNodes);
                    $contextMessages[] = Content::parse($figmaContext, Role::USER); // Add as user role for context
                }

                $aiReplyText = $ai->generateReplyFromContext($userMsg, history: $contextMessages);

                // create ai message
                $aiReply = new AiChat([
                    'content' => $aiReplyText,
                ]);
                $aiReply->user_id = null;
                $aiReply->sender = 'ai';
                $aiReply->commentable_id = $chatMsg->commentable_id;
                $aiReply->commentable_type = $chatMsg->commentable_type;

                $chatMsg->saveOrFail();
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
