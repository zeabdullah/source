<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\EmailTemplate;
use App\Models\Screen;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function getScreenComments(Request $request, string $screenId): JsonResponse
    {
        try {
            $comments = Comment::where('commentable_id', $screenId)
                ->where('commentable_type', Screen::class)
                ->get();

            return $this->responseJson($comments);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(
                ['trace' => $th->getTrace()],
                'Failed to get screen comments: ' . $th->getMessage()
            );
        }
    }
    public function createScreenComment(Request $request, string $screenId): JsonResponse
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        try {
            $comment = Comment::create([
                'content' => $validated['content'],
                'user_id' => $request->user()->id,
                'commentable_id' => $screenId,
                'commentable_type' => Screen::class,
            ]);
            return $this->responseJson($comment, 'Created successfully', 201);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(
                ['trace' => $th->getTrace()],
                'Failed to create screen comment: ' . $th->getMessage()
            );
        }
    }
    public function getEmailTemplateComments(Request $request, string $emailTemplateId): JsonResponse
    {
        try {
            $comments = Comment::where('commentable_id', $emailTemplateId)
                ->where('commentable_type', EmailTemplate::class)
                ->get();

            return $this->responseJson($comments);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(
                ['trace' => $th->getTrace()],
                'Failed to get email template comments: ' . $th->getMessage()
            );
        }
    }
    public function createEmailTemplateComment(Request $request, string $emailTemplateId): JsonResponse
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        try {
            $comment = Comment::create([
                'content' => $validated['content'],
                'user_id' => $request->user()->id,
                'commentable_id' => $emailTemplateId,
                'commentable_type' => EmailTemplate::class,
            ]);
            return $this->responseJson($comment, 'Created successfully', 201);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(
                ['trace' => $th->getTrace()],
                'Failed to create email template comment: ' . $th->getMessage()
            );
        }
    }

    public function getCommentById(Request $request, string $commentId): JsonResponse
    {
        try {
            $comment = Comment::find($commentId);
            if (!$comment) {
                return $this->notFoundResponse('Comment not found');
            }

            return $this->responseJson($comment);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(
                ['trace' => $th->getTrace()],
                'Failed to get comment: ' . $th->getMessage()
            );
        }
    }
    public function updateCommentById(Request $request, string $commentId): JsonResponse
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        try {
            $comment = Comment::find($commentId);
            if (!$comment) {
                return $this->notFoundResponse('Comment not found');
            }

            $comment->updateOrFail([
                'content' => $validated['content'],
            ]);

            return $this->responseJson($comment->fresh(), 'Updated successfully');
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(
                ['trace' => $th->getTrace()],
                'Failed to update comment: ' . $th->getMessage()
            );
        }
    }
    public function deleteCommentById(Request $request, string $commentId): JsonResponse
    {
        try {
            $comment = Comment::find($commentId);
            if (!$comment) {
                return $this->notFoundResponse('Comment not found');
            }

            $comment->deleteOrFail();

            return $this->responseJson($comment, 'Comment deleted');
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(
                ['trace' => $th->getTrace()],
                'Failed to delete comment: ' . $th->getMessage()
            );
        }
    }
}
