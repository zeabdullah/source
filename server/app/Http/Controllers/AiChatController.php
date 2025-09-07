<?php

namespace App\Http\Controllers;

use App\Models\AiChat;
use App\Models\Screen;
use Illuminate\Http\Request;

class AiChatController extends Controller
{
    public function createScreenChatMessage(Request $request, string $screenId)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:2500'
        ]);

        try {
            $chatMsg = new AiChat([
                'content' => $validated['content'],
            ]);
            $chatMsg->sender = 'user';
            $chatMsg->user_id = $request->user()->id;
            $chatMsg->commentable_id = $screenId;
            $chatMsg->commentable_type = Screen::class;

            $chatMsg->saveOrFail();

            return $this->responseJson($chatMsg->fresh(), 'Created successfully', 201);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse('Failed to create chat message: ' . $th->getMessage());
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
