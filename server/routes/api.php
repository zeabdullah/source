<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\Common\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/user', [UserController::class, 'getUser'])->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
