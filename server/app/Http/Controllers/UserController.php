<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUserById(string $userId): JsonResponse
    {
        $user = User::find($userId);
        if (!$user) {
            return $this->notFoundResponse('User not found');
        }

        return $this->responseJson($user);
    }

    public function updateOwnProfile(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $request->user()->id,
            'avatar_url' => 'sometimes|nullable|url',
        ]);

        $user = $request->user();
        $user->update($validated);

        return $this->responseJson($user->fresh());
    }

    public function storeFigmaToken(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'figma_access_token' => 'required|string|min:1',
        ]);

        $user = $request->user();
        $user->figma_access_token = $validated['figma_access_token'];
        $user->save();

        return $this->responseJson(message: 'Figma access token stored successfully');
    }
}
