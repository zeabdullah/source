<?php

use App\Http\Controllers\AiChatController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\CommentController;
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
    Route::put('/profile', [UserController::class, 'updateOwnProfile']);
    Route::post('/profile/figma-token', [UserController::class, 'storeFigmaToken']);
    Route::post('/profile/brevo-token', [UserController::class, 'storeBrevoApiToken']);
    Route::delete('/profile/figma-token', [UserController::class, 'removeFigmaToken']);
    Route::delete('/profile/brevo-token', [UserController::class, 'removeBrevoApiToken']);

    // Brevo Templates (general)
    Route::get('/brevo-templates', [EmailTemplateController::class, 'getBrevoTemplates']);

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
        Route::middleware('is_owner')->group(function () {
            Route::post('/{projectId}/screens/export', [ScreenController::class, 'exportScreens']);
            Route::get('/{projectId}/screens', [ScreenController::class, 'getProjectScreens']);
            Route::get('/{projectId}/screens/{screenId}', [ScreenController::class, 'getScreenById']);
            Route::put('/{projectId}/screens/{screenId}', [ScreenController::class, 'updateScreenById']);
            Route::delete('/{projectId}/screens/{screenId}', [ScreenController::class, 'deleteScreenById']);
        });

        // Email Templates (per project)
        Route::middleware('is_owner')->group(function () {

            // Brevo integration routes
            Route::post('/{projectId}/email-templates/import-brevo', [EmailTemplateController::class, 'importBrevoTemplate']);
            Route::post('/{projectId}/email-templates/{emailTemplateId}/sync-brevo', [EmailTemplateController::class, 'syncWithBrevo']);
            Route::put('/{projectId}/email-templates/{emailTemplateId}/update-brevo', [EmailTemplateController::class, 'updateInBrevo']);

            // General email template routes
            Route::get('/{projectId}/email-templates', [EmailTemplateController::class, 'getProjectEmailTemplates']);
            Route::get('/{projectId}/email-templates/{emailTemplateId}', [EmailTemplateController::class, 'getEmailTemplateById']);
            Route::put('/{projectId}/email-templates/{emailTemplateId}', [EmailTemplateController::class, 'updateEmailTemplateById']);
            Route::delete('/{projectId}/email-templates/{emailTemplateId}', [EmailTemplateController::class, 'deleteEmailTemplateById']);

            // Audits (per project)
            Route::get('/{projectId}/audits', [AuditController::class, 'index']);
            Route::post('/{projectId}/audits', [AuditController::class, 'store']);
            Route::get('/{projectId}/audits/{auditId}', [AuditController::class, 'show']);
            Route::put('/{projectId}/audits/{auditId}', [AuditController::class, 'update']);
            Route::delete('/{projectId}/audits/{auditId}', [AuditController::class, 'destroy']);
            Route::post('/{projectId}/audits/{auditId}/execute', [AuditController::class, 'execute']);
            Route::get('/{projectId}/audits/{auditId}/status', [AuditController::class, 'status']);
        });

    });

    // Chats (general)
    Route::put('/chats/{chatId}', [AiChatController::class, 'updateChatMessageById']);
    Route::delete('/chats/{chatId}', [AiChatController::class, 'deleteChatMessageById']);
    // Chats (per email template)
    Route::get('/email-templates/{emailTemplateId}/chats', [AiChatController::class, 'getEmailTemplateChat']);
    Route::post('/email-templates/{emailTemplateId}/chats', [AiChatController::class, 'sendEmailTemplateChatMessage']);
    // Chats (per screen)
    Route::get('/screens/{screenId}/chats', [AiChatController::class, 'getScreenChat']);
    Route::post('/screens/{screenId}/chats', [AiChatController::class, 'sendScreenChatMessage']);

    // Releases (by id)
    Route::prefix('releases')->group(function () {
        Route::get('/{releaseId}', [ReleaseController::class, 'getReleaseById']);
        Route::put('/{releaseId}', [ReleaseController::class, 'updateReleaseById']);
        Route::delete('/{releaseId}', [ReleaseController::class, 'deleteReleaseById']);
    });

    // Comments (per screen, polymorphic but scoped here)
    Route::get('/screens/{screenId}/comments', [CommentController::class, 'getScreenComments']);
    Route::post('/screens/{screenId}/comments', [CommentController::class, 'createScreenComment']);

    // Comments (per email template, polymorphic but scoped here)
    Route::get('/email-templates/{emailTemplateId}/comments', [CommentController::class, 'getEmailTemplateComments']);
    Route::post('/email-templates/{emailTemplateId}/comments', [CommentController::class, 'createEmailTemplateComment']);

    // Comments (by id)
    Route::get('/comments/{commentId}', [CommentController::class, 'getCommentById']);
    Route::put('/comments/{commentId}', [CommentController::class, 'updateCommentById']);
    Route::delete('/comments/{commentId}', [CommentController::class, 'deleteCommentById']);
});
