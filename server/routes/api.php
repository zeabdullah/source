<?php

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
        Route::get('/{projectId}', [ProjectController::class, 'getProjectById'])->middleware('is_owner');
        Route::put('/{projectId}', [ProjectController::class, 'updateProjectById'])->middleware('is_owner');
        Route::delete('/{projectId}', [ProjectController::class, 'deleteProjectById'])->middleware('is_owner');
        Route::post('/{projectId}/figma/connect', [ProjectController::class, 'connectFigmaFile'])->middleware('is_owner');
        Route::post('/{projectId}/figma/disconnect', [ProjectController::class, 'disconnectFigmaFile'])->middleware('is_owner');

        // Releases (per project)
        Route::post('/{projectId}/releases', [ReleaseController::class, 'createRelease']);
        Route::get('/{projectId}/releases', [ReleaseController::class, 'getProjectReleases']);

        // Screens (per project)
        Route::post('/{projectId}/screens', [ScreenController::class, 'createScreen']); // likely going to remove this endpoint (screens are likely going to be added through Figma only through exportScreens  )
        Route::post('/{projectId}/screens/export', [ScreenController::class, 'exportScreens'])->middleware('is_owner');
        Route::get('/{projectId}/screens', [ScreenController::class, 'getProjectScreens'])->middleware('is_owner');
        Route::put('/{projectId}/screens/{screenId}', [ScreenController::class, 'updateScreenById'])->middleware('is_owner');
        Route::delete('/{projectId}/screens/{screenId}', [ScreenController::class, 'deleteScreenById'])->middleware('is_owner');

        // Email Templates (per project)
        Route::post('/{projectId}/email-templates', [EmailTemplateController::class, 'createEmailTemplate'])->middleware('is_owner');
        Route::get('/{projectId}/email-templates', [EmailTemplateController::class, 'getProjectEmailTemplates'])->middleware('is_owner');
    });

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


    // // Chats (per screen)
    // Route::get('/screens/{screenId}/chats', [ChatController::class, 'index']);
    // Route::post('/screens/{screenId}/chats', [ChatController::class, 'store']);
    // Route::get('/chats/{chatId}', [ChatController::class, 'show']);
    // Route::put('/chats/{chatId}', [ChatController::class, 'update']);
    // Route::patch('/chats/{chatId}', [ChatController::class, 'update']);
    // Route::delete('/chats/{chatId}', [ChatController::class, 'destroy']);

    // // Comments (per screen, polymorphic but scoped here)
    // Route::get('/screens/{screenId}/comments', [CommentController::class, 'index']);
    // Route::post('/screens/{screenId}/comments', [CommentController::class, 'store']);
    // Route::get('/comments/{commentId}', [CommentController::class, 'show']);
    // Route::put('/comments/{commentId}', [CommentController::class, 'update']);
    // Route::patch('/comments/{commentId}', [CommentController::class, 'update']);
    // Route::delete('/comments/{commentId}', [CommentController::class, 'destroy']);
});
