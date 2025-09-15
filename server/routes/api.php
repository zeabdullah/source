<?php

use App\Http\Controllers\AiChatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Common\AuthController;
use App\Http\Controllers\Project\ProjectController;
use App\Http\Controllers\Project\ReleaseController;
use App\Http\Controllers\Project\ScreenController;
use App\Http\Controllers\Project\EmailTemplateController;
use Illuminate\Support\Facades\Route;

Route::group([], function () {
    Route::get('/me', [AuthController::class, 'getMe'])->middleware('auth:sanctum');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

Route::prefix('plugin')->group(function () {
    Route::post('/login', [AuthController::class, 'pluginLogin']);
    Route::post('/logout', [AuthController::class, 'pluginLogout'])->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {
    // Users
    Route::get('/users/{userId}', [UserController::class, 'getUserById']);
    Route::get('/profile', [UserController::class, 'getOwnProfile']);
    Route::put('/profile', [UserController::class, 'updateOwnProfile']);

    Route::prefix('projects')->group(function () {

        Route::post('/', [ProjectController::class, 'createProject']);
        Route::get('/', [ProjectController::class, 'getMyProjects']);
        Route::middleware('is_owner')->group(function () {
            Route::get('/{projectId}', [ProjectController::class, 'getProjectById']);
            Route::put('/{projectId}', [ProjectController::class, 'updateProjectById']);
            Route::delete('/{projectId}', [ProjectController::class, 'deleteProjectById']);
        });

        // Releases (per project)
        Route::post('/{projectId}/releases', [ReleaseController::class, 'createRelease']);
        Route::get('/{projectId}/releases', [ReleaseController::class, 'getProjectReleases']);

        // Screens (per project)
        Route::post('/{projectId}/screens', [ScreenController::class, 'createScreen']); // deprecated
        Route::middleware('is_owner')->group(function () {
            Route::post('/{projectId}/screens/export', [ScreenController::class, 'exportScreens']);
            Route::get('/{projectId}/screens', [ScreenController::class, 'getProjectScreens']);
            Route::get('/{projectId}/screens/{screenId}', [ScreenController::class, 'getScreenById']);
            Route::put('/{projectId}/screens/{screenId}', [ScreenController::class, 'updateScreenById']);
            Route::delete('/{projectId}/screens/{screenId}', [ScreenController::class, 'deleteScreenById']);
        });

        // Email Templates (per project)
        Route::middleware('is_owner')->group(function () {
            Route::post('/{projectId}/email-templates/import', [EmailTemplateController::class, 'importEmailTemplate']);
            Route::get('/{projectId}/email-templates', [EmailTemplateController::class, 'getProjectEmailTemplates']);
            Route::get('/{projectId}/email-templates/{emailTemplateId}', [EmailTemplateController::class, 'getEmailTemplateById']);
            Route::put('/{projectId}/email-templates/{emailTemplateId}', [EmailTemplateController::class, 'updateEmailTemplateById']);
            Route::delete('/{projectId}/email-templates/{emailTemplateId}', [EmailTemplateController::class, 'deleteEmailTemplateById']);
        });

    });

    // Chats (general)
    Route::put('/chats/{chatId}', [AiChatController::class, 'updateChatMessageById']);
    Route::delete('/chats/{chatId}', [AiChatController::class, 'deleteChatMessageById']);
    // Chats (per email template)
    Route::post('/email-templates/{emailTemplateId}/chats', [AiChatController::class, 'sendEmailTemplateChatMessage']);
    Route::get('/email-templates/{emailTemplateId}/chats', [AiChatController::class, 'getEmailTemplateChat']);
    // Chats (per screen)
    Route::post('/screens/{screenId}/chats', [AiChatController::class, 'sendScreenChatMessage']);
    Route::get('/screens/{screenId}/chats', [AiChatController::class, 'getScreenChat']);

    // Releases (by id)
    Route::prefix('releases')->group(function () {
        Route::get('/{releaseId}', [ReleaseController::class, 'getReleaseById']);
        Route::put('/{releaseId}', [ReleaseController::class, 'updateReleaseById']);
        Route::delete('/{releaseId}', [ReleaseController::class, 'deleteReleaseById']);
    });

    // Screens (by id)
    Route::prefix('screens')->group(function () {
        Route::post('/{screenId}/regenerate-description', [ScreenController::class, 'regenerateDescription']);
    });


    // // Comments (per screen, polymorphic but scoped here)
    // Route::get('/screens/{screenId}/comments', [CommentController::class, 'index']);
    // Route::post('/screens/{screenId}/comments', [CommentController::class, 'store']);
    // Route::get('/comments/{commentId}', [CommentController::class, 'show']);
    // Route::put('/comments/{commentId}', [CommentController::class, 'update']);
    // Route::patch('/comments/{commentId}', [CommentController::class, 'update']);
    // Route::delete('/comments/{commentId}', [CommentController::class, 'destroy']);
});

// BASIC auth routes
Route::group(['prefix' => 'basic', 'middleware' => 'basic_auth'], function () {
    // Screens
    Route::get('/screens/{screenId}', [ScreenController::class, 'getScreenByIdBasic']);
    Route::post('/screens/{screenId}/chats/ai-response-webhook', [AiChatController::class, 'createAiChatResponseForScreen']);

    // Email Templates
    Route::get('/email-templates/{emailTemplateId}', [EmailTemplateController::class, 'getEmailTemplateByIdBasic']);
    Route::post('/email-templates/{emailTemplateId}/chats/ai-response-webhook', [AiChatController::class, 'createAiChatResponseForEmailTemplate']);
});
