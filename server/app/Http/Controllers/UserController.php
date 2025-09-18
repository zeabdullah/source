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
            'figma_access_token' => 'sometimes|nullable|string|min:1',
            'brevo_api_token' => 'sometimes|nullable|string|min:1',
        ]);

        $user = $request->user();
        $user->update($validated);

        return $this->responseJson($user->fresh(), 'Profile updated successfully');
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

    public function storeBrevoApiToken(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'brevo_api_token' => 'required|string|min:1',
        ]);

        $user = $request->user();
        $user->brevo_api_token = $validated['brevo_api_token'];
        $user->save();

        return $this->responseJson(message: 'Brevo API token stored successfully');
    }

    public function removeFigmaToken(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->figma_access_token = null;
        $user->save();

        return $this->responseJson(message: 'Figma access token removed successfully');
    }

    public function removeBrevoApiToken(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->brevo_api_token = null;
        $user->save();

        return $this->responseJson(message: 'Brevo API token removed successfully');
    }
}
