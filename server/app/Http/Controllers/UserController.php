<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Source API",
 *      description="Source API description",
 * )
 */
class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/users/{userId}",
     *     summary="Get a user by ID",
     *     @OA\Parameter(
     *         description="The ID of the user to get",
     *         in="path",
     *         name="userId",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="int", value="1", summary="An int value."),
     *         @OA\Examples(example="uuid", value="0006faf6-7a61-426c-9034-579f2cfcfa83", summary="An UUID value."),
     *     ),
     *     @OA\Response(response=200, description="Ok"),
     *     @OA\Response(response=404, description="User not found")
     *     )
     * )
     */
    public function getUserById(string $userId): JsonResponse
    {
        $user = User::find($userId);
        if (!$user) {
            return $this->notFoundResponse('User not found');
        }

        return $this->responseJson($user);
    }

    /**
     * @OA\Put(
     *     path="/profile",
     *     summary="Update own profile",
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", maxLength=255),
     *             @OA\Property(property="email", type="string", format="email", maxLength=255),
     *             @OA\Property(property="avatar_url", type="string", format="url", nullable=true),
     *             @OA\Property(property="figma_access_token", type="string", nullable=true),
     *             @OA\Property(property="brevo_api_token", type="string", nullable=true),
     *         )
     *     ),
     *     @OA\Response(response=200, description="Profile updated successfully"),
     *     @OA\Response(response=422, description="Validation errors")
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/profile/figma-token",
     *     summary="Store Figma access token",
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="figma_access_token", type="string", minLength=1),
     *         )
     *     ),
     *     @OA\Response(response=200, description="Figma access token stored successfully"),
     *     @OA\Response(response=422, description="Validation errors")
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/profile/brevo-token",
     *     summary="Store Brevo API token",
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="brevo_api_token", type="string", minLength=1),
     *         )
     *     ),
     *     @OA\Response(response=200, description="Brevo API token stored successfully"),
     *     @OA\Response(response=422, description="Validation errors")
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/profile/figma-token",
     *     summary="Remove Figma access token",
     *     security={{"sanctum": {}}},
     *     @OA\Response(response=200, description="Figma access token removed successfully")
     * )
     */
    public function removeFigmaToken(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->figma_access_token = null;
        $user->save();

        return $this->responseJson(message: 'Figma access token removed successfully');
    }

    /**
     * @OA\Delete(
     *     path="/profile/brevo-token",
     *     summary="Remove Brevo API token",
     *     security={{"sanctum": {}}},
     *     @OA\Response(response=200, description="Brevo API token removed successfully")
     * )
     */
    public function removeBrevoApiToken(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->brevo_api_token = null;
        $user->save();

        return $this->responseJson(message: 'Brevo API token removed successfully');
    }
}
