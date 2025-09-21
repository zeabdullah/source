<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\EmailTemplate;
use App\Models\Screen;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Comments",
 *     description="Comment management endpoints for screens and email templates"
 * )
 */
class CommentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/screens/{screenId}/comments",
     *     summary="Get screen comments",
     *     description="Get all comments for a specific screen",
     *     tags={"Comments"},
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
     *         description="List of screen comments",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Comment")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Screen not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function getScreenComments(Request $request, string $screenId): JsonResponse
    {
        try {
            $screen = Screen::find($screenId);
            if (!$screen) {
                return $this->notFoundResponse('Screen not found');
            }

            $comments = $screen->comments()
                ->with('user')
                ->orderByDesc('created_at')
                ->get();

            $comments->each(function (Comment $comment) {
                $comment->makeHidden('user_id');
                $comment->makeVisible('user');
            });

            return $this->responseJson($comments);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(
                ['trace' => $th->getTrace()],
                'Failed to get screen comments: ' . $th->getMessage()
            );
        }
    }

    /**
     * @OA\Post(
     *     path="/screens/{screenId}/comments",
     *     summary="Create screen comment",
     *     description="Create a new comment for a specific screen",
     *     tags={"Comments"},
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
     *             @OA\Property(property="content", type="string", example="This is a comment about the screen")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Comment created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Comment")
     *     ),
     *     @OA\Response(response=422, description="Validation errors"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
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
            $comment->user = $request->user();
            $comment->makeHidden('user_id');
            $comment->makeVisible('user');

            return $this->responseJson($comment, 'Created successfully', 201);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(
                ['trace' => $th->getTrace()],
                'Failed to create screen comment: ' . $th->getMessage()
            );
        }
    }

    /**
     * @OA\Get(
     *     path="/email-templates/{emailTemplateId}/comments",
     *     summary="Get email template comments",
     *     description="Get all comments for a specific email template",
     *     tags={"Comments"},
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
     *         description="List of email template comments",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Comment")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Email template not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function getEmailTemplateComments(Request $request, string $emailTemplateId): JsonResponse
    {
        try {
            $template = EmailTemplate::find($emailTemplateId);
            if (!$template) {
                return $this->notFoundResponse('Email template not found');
            }

            $comments = $template->comments()
                ->with('user')
                ->orderByDesc('created_at')
                ->get();

            $comments->each(function (Comment $comment) {
                $comment->makeHidden('user_id');
                $comment->makeVisible('user');
            });

            return $this->responseJson($comments);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(
                ['trace' => $th->getTrace()],
                'Failed to get email template comments: ' . $th->getMessage()
            );
        }
    }

    /**
     * @OA\Post(
     *     path="/email-templates/{emailTemplateId}/comments",
     *     summary="Create email template comment",
     *     description="Create a new comment for a specific email template",
     *     tags={"Comments"},
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
     *             @OA\Property(property="content", type="string", example="This is a comment about the email template")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Comment created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Comment")
     *     ),
     *     @OA\Response(response=422, description="Validation errors"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
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
            $comment->user = $request->user();
            $comment->makeHidden('user_id');
            $comment->makeVisible('user');

            return $this->responseJson($comment, 'Created successfully', 201);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(
                ['trace' => $th->getTrace()],
                'Failed to create email template comment: ' . $th->getMessage()
            );
        }
    }

    /**
     * @OA\Get(
     *     path="/comments/{commentId}",
     *     summary="Get comment by ID",
     *     description="Get a specific comment by its ID",
     *     tags={"Comments"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="commentId",
     *         in="path",
     *         description="Comment ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comment data",
     *         @OA\JsonContent(ref="#/components/schemas/Comment")
     *     ),
     *     @OA\Response(response=404, description="Comment not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/comments/{commentId}",
     *     summary="Update comment",
     *     description="Update a comment by its ID",
     *     tags={"Comments"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="commentId",
     *         in="path",
     *         description="Comment ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="content", type="string", example="Updated comment content")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comment updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Comment")
     *     ),
     *     @OA\Response(response=404, description="Comment not found"),
     *     @OA\Response(response=422, description="Validation errors"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/comments/{commentId}",
     *     summary="Delete comment",
     *     description="Delete a comment by its ID",
     *     tags={"Comments"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="commentId",
     *         in="path",
     *         description="Comment ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comment deleted successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Comment")
     *     ),
     *     @OA\Response(response=404, description="Comment not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
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
