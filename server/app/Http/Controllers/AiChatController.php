<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AiChat;
use App\Models\Screen;
use App\Services\AiAgentService;
use App\Services\FigmaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Gemini\Data\Content;
use Gemini\Enums\Role;

class AiChatController extends Controller
{
    public function sendScreenChatMessage(Request $request, string $screenId, AiAgentService $aiAgentService, FigmaService $figmaService)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:2500',
            'figma_access_token' => 'required|string',
        ]);
        $userMsg = $validated['content'];
        $accessToken = $validated['figma_access_token'];

        $lockKey = "screen:{$screenId}:ai_chat_lock";
        $figmaCacheKey = "figma_frame_data_{$screenId}"; // New cache key for Figma data

        try {
            $screen = Screen::find($screenId);
            if (!$screen) {
                return $this->notFoundResponse('Screen not found');
            }

            // Lock: prevent adding more messages to this screen while processing
            if (cache()->has($lockKey)) {
                return $this->forbiddenResponse('AI is currently replying. Please wait.');
            }
            cache()->put($lockKey, true, ttl: 90);

            $result = DB::transaction(function () use ($request, $screen, $screenId, $userMsg, $accessToken, $figmaCacheKey, $aiAgentService, $figmaService) {
                // create user message
                $chatMsg = new AiChat([
                    'content' => $userMsg,
                ]);
                $chatMsg->sender = 'user';
                $chatMsg->user_id = $request->user()->id;
                $chatMsg->commentable_id = $screenId;
                $chatMsg->commentable_type = Screen::class;

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
                if ($screen->hasFigmaData() && $screen->project->hasFigmaFile()) {
                    $figmaNodes = cache()->remember($figmaCacheKey, now()->addHours(12), function () use ($accessToken, $screen, $figmaService) {
                        return $figmaService->getFigmaFrameForAI(
                            $screen->project->figma_file_key,
                            $screen->figma_node_id,
                            $accessToken,
                        );
                    });

                    if ($figmaNodes) {
                        $figmaContext = "Figma Frame Data: " . json_encode($figmaNodes);
                        $contextMessages[] = Content::parse($figmaContext, Role::USER); // Add as user role for context
                    }
                }

                $aiReplyText = $aiAgentService->generateReplyFromContext($userMsg, history: $contextMessages);

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

            cache()->forget($lockKey);

            return $this->responseJson($result, 'Created successfully', 201);
        } catch (\Throwable $th) {
            cache()->forget($lockKey);
            // return $this->serverErrorResponse('Failed to send chat message: ' . $th->getMessage());
            throw $th;
        }
    }

    public function getScreenChatMessages(Request $request, string $screenId)
    {
        try {
            $screen = Screen::find($screenId);
            if (!$screen) {
                return $this->notFoundResponse('Screen not found');
            }

            $project = $screen->project;
            if (!$project) {
                return $this->notFoundResponse('Project not found');
            }

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
            return $this->serverErrorResponse('Failed to retrieve chat messages: ' . $th->getMessage());
        }
    }


    public function updateChatMessageById(Request $request, string $chatId)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:2500'
        ]);

        $chatMsg = AiChat::find($chatId);
        try {
            if (!$chatMsg) {
                return $this->notFoundResponse('Chat message not found');
            }

            if ($chatMsg->user_id !== $request->user()->id) {
                return $this->forbiddenResponse('You are not authorized to update this message');
            }

            $chatMsg->updateOrFail($validated['content']);

            return $this->responseJson($chatMsg->fresh(), 'Chat message updated');
        } catch (\Throwable $th) {
            return $this->serverErrorResponse('Failed to update chat message: ' . $th->getMessage());
        }
    }

    public function deleteChatMessageById(Request $request, string $chatId)
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
            return $this->serverErrorResponse('Failed to delete chat message: ' . $th->getMessage());
        }
    }
}
