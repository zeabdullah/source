<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *      title="Source API",
 *      version="1.0.0",
 *      description="API for Source - Figma Design Audit Platform"
 * )
 * @OA\Server(
 *      url="http://localhost:8000/api",
 *      description="Local development server"
 * )
 * @OA\SecurityScheme(
 *      securityScheme="sanctum",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT"
 * )
 * @OA\Parameter(
 *      parameter="AcceptJson",
 *      name="Accept",
 *      in="header",
 *      description="Accept JSON response",
 *      required=true,
 *      @OA\Schema(type="string", default="application/json")
 * )
 * @OA\Parameter(
 *      parameter="ContentTypeJson",
 *      name="Content-Type",
 *      in="header",
 *      description="Content type for JSON requests",
 *      required=true,
 *      @OA\Schema(type="string", default="application/json")
 * )
 * @OA\Schema(
 *      schema="User",
 *      type="object",
 *      @OA\Property(property="id", type="integer", example=1),
 *      @OA\Property(property="name", type="string", example="John Doe"),
 *      @OA\Property(property="email", type="string", format="email", example="user@example.com"),
 *      @OA\Property(property="avatar_url", type="string", format="url", nullable=true, example="https://example.com/avatar.jpg"),
 *      @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T00:00:00.000000Z"),
 *      @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T00:00:00.000000Z")
 * )
 * @OA\Schema(
 *      schema="Audit",
 *      type="object",
 *      @OA\Property(property="id", type="integer", example=1),
 *      @OA\Property(property="project_id", type="integer", example=1),
 *      @OA\Property(property="name", type="string", example="User Flow Audit"),
 *      @OA\Property(property="description", type="string", nullable=true, example="Audit description"),
 *      @OA\Property(property="status", type="string", enum={"pending", "processing", "completed", "failed"}, example="pending"),
 *      @OA\Property(property="overall_score", type="number", format="float", nullable=true, example=85.5),
 *      @OA\Property(property="results", type="object", nullable=true),
 *      @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T00:00:00.000000Z"),
 *      @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T00:00:00.000000Z"),
 *      @OA\Property(property="screens", type="array", @OA\Items(type="object"))
 * )
 * @OA\Schema(
 *      schema="EmailTemplate",
 *      type="object",
 *      @OA\Property(property="id", type="integer", example=1),
 *      @OA\Property(property="project_id", type="integer", example=1),
 *      @OA\Property(property="section_name", type="string", example="Welcome Email"),
 *      @OA\Property(property="campaign_id", type="integer", nullable=true, example=123),
 *      @OA\Property(property="brevo_template_id", type="integer", nullable=true, example=456),
 *      @OA\Property(property="html_content", type="string", nullable=true, example="<html>...</html>"),
 *      @OA\Property(property="thumbnail_url", type="string", format="url", nullable=true, example="https://example.com/thumb.jpg"),
 *      @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T00:00:00.000000Z"),
 *      @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T00:00:00.000000Z")
 * )
 * @OA\Schema(
 *      schema="BrevoTemplate",
 *      type="object",
 *      @OA\Property(property="id", type="integer", example=1),
 *      @OA\Property(property="name", type="string", example="Welcome Email Template"),
 *      @OA\Property(property="subject", type="string", example="Welcome to our platform"),
 *      @OA\Property(property="htmlContent", type="string", example="<html>...</html>"),
 *      @OA\Property(property="sender", type="object", @OA\Property(property="name", type="string", example="Company Name")),
 *      @OA\Property(property="isActive", type="boolean", example=true),
 *      @OA\Property(property="createdAt", type="string", format="date-time", example="2023-01-01T00:00:00.000000Z"),
 *      @OA\Property(property="modifiedAt", type="string", format="date-time", example="2023-01-01T00:00:00.000000Z")
 * )
 * @OA\Schema(
 *      schema="Project",
 *      type="object",
 *      @OA\Property(property="id", type="integer", example=1),
 *      @OA\Property(property="owner_id", type="integer", example=1),
 *      @OA\Property(property="name", type="string", example="My Project"),
 *      @OA\Property(property="description", type="string", nullable=true, example="Project description"),
 *      @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T00:00:00.000000Z"),
 *      @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T00:00:00.000000Z")
 * )
 * @OA\Schema(
 *      schema="Comment",
 *      type="object",
 *      @OA\Property(property="id", type="integer", example=1),
 *      @OA\Property(property="content", type="string", example="This is a comment"),
 *      @OA\Property(property="user_id", type="integer", example=1),
 *      @OA\Property(property="commentable_id", type="integer", example=1),
 *      @OA\Property(property="commentable_type", type="string", example="App\\Models\\Screen"),
 *      @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T00:00:00.000000Z"),
 *      @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T00:00:00.000000Z"),
 *      @OA\Property(property="user", ref="#/components/schemas/User")
 * )
 * @OA\Schema(
 *      schema="AiChat",
 *      type="object",
 *      @OA\Property(property="id", type="integer", example=1),
 *      @OA\Property(property="content", type="string", example="AI generated response"),
 *      @OA\Property(property="sender", type="string", enum={"user", "ai"}, example="ai"),
 *      @OA\Property(property="user_id", type="integer", nullable=true, example=1),
 *      @OA\Property(property="commentable_id", type="integer", example=1),
 *      @OA\Property(property="commentable_type", type="string", example="App\\Models\\Screen"),
 *      @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T00:00:00.000000Z"),
 *      @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T00:00:00.000000Z")
 * )
 * @OA\Schema(
 *      schema="Screen",
 *      type="object",
 *      @OA\Property(property="id", type="integer", example=1),
 *      @OA\Property(property="project_id", type="integer", example=1),
 *      @OA\Property(property="section_name", type="string", nullable=true, example="Login Screen"),
 *      @OA\Property(property="figma_node_id", type="string", nullable=true, example="1:23"),
 *      @OA\Property(property="figma_file_key", type="string", nullable=true, example="abc123def456"),
 *      @OA\Property(property="figma_svg_url", type="string", format="url", nullable=true, example="https://figma.com/svg/..."),
 *      @OA\Property(property="figma_node_name", type="string", nullable=true, example="Login"),
 *      @OA\Property(property="description", type="string", nullable=true, example="User login screen"),
 *      @OA\Property(property="data", type="object", nullable=true),
 *      @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T00:00:00.000000Z"),
 *      @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T00:00:00.000000Z")
 * )
 * @OA\Schema(
 *      schema="Release",
 *      type="object",
 *      @OA\Property(property="id", type="integer", example=1),
 *      @OA\Property(property="project_id", type="integer", example=1),
 *      @OA\Property(property="name", type="string", example="Version 1.0"),
 *      @OA\Property(property="description", type="string", nullable=true, example="Initial release"),
 *      @OA\Property(property="status", type="string", enum={"draft", "published", "archived"}, example="draft"),
 *      @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T00:00:00.000000Z"),
 *      @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T00:00:00.000000Z")
 * )
/**
 * @OA\Tag(
 *     name="Users",
 *     description="User management and profile endpoints"
 * )
 */
class UserController extends Controller
{
    /**
     * @OA\Put(
     *     path="/profile",
     *     summary="Update own profile",
     *     description="Update the authenticated user's profile information",
     *     tags={"Users"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", maxLength=255, example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", maxLength=255, example="user@example.com"),
     *             @OA\Property(property="avatar_url", type="string", format="url", nullable=true, example="https://example.com/avatar.jpg"),
     *             @OA\Property(property="figma_access_token", type="string", nullable=true),
     *             @OA\Property(property="brevo_api_token", type="string", nullable=true),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Profile updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(response=422, description="Validation errors"),
     *     @OA\Response(response=500, description="Server error")
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
        try {

            $user = $request->user();
            $user->update($validated);

            return $this->responseJson($user->fresh(), 'Profile updated successfully');
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to update profile: ' . $th->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/profile/figma-token",
     *     summary="Store Figma access token",
     *     description="Store Figma access token for the authenticated user",
     *     tags={"Users"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="figma_access_token", type="string", minLength=1, example="figd_abc123..."),
     *         )
     *     ),
     *     @OA\Response(response=200, description="Figma access token stored successfully"),
     *     @OA\Response(response=422, description="Validation errors"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function storeFigmaToken(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'figma_access_token' => 'required|string|min:1',
        ]);
        try {

            $user = $request->user();
            $user->figma_access_token = $validated['figma_access_token'];
            $user->save();

            return $this->responseJson(message: 'Figma access token stored successfully');
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to store Figma token: ' . $th->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/profile/brevo-token",
     *     summary="Store Brevo API token",
     *     description="Store Brevo API token for the authenticated user",
     *     tags={"Users"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="brevo_api_token", type="string", minLength=1, example="xkeys-abc123..."),
     *         )
     *     ),
     *     @OA\Response(response=200, description="Brevo API token stored successfully"),
     *     @OA\Response(response=422, description="Validation errors"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function storeBrevoApiToken(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'brevo_api_token' => 'required|string|min:1',
        ]);
        try {

            $user = $request->user();
            $user->brevo_api_token = $validated['brevo_api_token'];
            $user->saveOrFail();

            return $this->responseJson(message: 'Brevo API token stored successfully');
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to store Brevo token: ' . $th->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *     path="/profile/figma-token",
     *     summary="Remove Figma access token",
     *     description="Remove Figma access token from the authenticated user's profile",
     *     tags={"Users"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(response=200, description="Figma access token removed successfully"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function removeFigmaToken(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $user->figma_access_token = null;
            $user->save();

            return $this->responseJson(message: 'Figma access token removed successfully');
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to remove Figma token: ' . $th->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *     path="/profile/brevo-token",
     *     summary="Remove Brevo API token",
     *     description="Remove Brevo API token from the authenticated user's profile",
     *     tags={"Users"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(response=200, description="Brevo API token removed successfully"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function removeBrevoApiToken(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $user->brevo_api_token = null;
            $user->save();

            return $this->responseJson(message: 'Brevo API token removed successfully');
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to remove Brevo token: ' . $th->getMessage());
        }
    }
}
