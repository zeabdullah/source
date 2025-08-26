<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUserById(Request $request, string $userId): JsonResponse
    {
        $user = User::find($userId);

        if (!$user) {
            return $this->notFoundResponse(message: 'User not found');
        }

        return $this->responseJson($user);
    }
}
