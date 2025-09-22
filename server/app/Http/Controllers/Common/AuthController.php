<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Tag(
 *     name="Auth",
 *     description="Authentication endpoints for users and plugin"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/plugin/login",
     *     summary="Plugin login",
     *     description="Authenticate plugin with email and password",
     *     tags={"Auth"},
     *     @OA\Parameter(ref="#/components/parameters/AcceptJson"),
     *     @OA\Parameter(ref="#/components/parameters/ContentTypeJson"),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", ref="#/components/schemas/User"),
     *             @OA\Property(property="token", type="string", example="1|abc123...")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Invalid credentials")
     * )
     */
    public function pluginLogin(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!auth()->attempt($credentials)) {
            return $this->unauthorizedResponse('Invalid credentials');
        }

        $user = auth()->user();
        $token = $user->createToken('plugin_auth_token')->plainTextToken;

        return $this->responseJson([
            'user' => $user,
            'token' => $token
        ], 'Login successful');
    }

    /**
     * @OA\Post(
     *     path="/plugin/logout",
     *     summary="Plugin logout",
     *     description="Logout plugin and revoke current access token",
     *     tags={"Auth"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(ref="#/components/parameters/AcceptJson"),
     *     @OA\Parameter(ref="#/components/parameters/ContentTypeJson"),
     *     @OA\Response(response=200, description="Logged out successfully")
     * )
     */
    public function pluginLogout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return $this->responseJson(null, 'Logged out successfully');
    }

    /**
     * @OA\Post(
     *     path="/login",
     *     summary="User login",
     *     description="Authenticate user with email and password",
     *     tags={"Auth"},
     *     @OA\Parameter(ref="#/components/parameters/AcceptJson"),
     *     @OA\Parameter(ref="#/components/parameters/ContentTypeJson"),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", ref="#/components/schemas/User"),
     *             @OA\Property(property="token", type="string", example="1|abc123...", description="Only returned for API requests")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Invalid credentials")
     * )
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!auth()->attempt($credentials)) {
            return $this->unauthorizedResponse('Invalid credentials');
        }

        $payload = [];
        $user = auth()->user();

        if ($request->hasSession()) {
            $request->session()->regenerate();
        } else {
            $payload['token'] = $user->createToken('auth_token')->plainTextToken;
        }
        $payload['user'] = $user;

        return $this->responseJson($payload, 'Login successful');
    }

    /**
     * @OA\Post(
     *     path="/register",
     *     summary="User registration",
     *     description="Register a new user account",
     *     tags={"Auth"},
     *     @OA\Parameter(ref="#/components/parameters/AcceptJson"),
     *     @OA\Parameter(ref="#/components/parameters/ContentTypeJson"),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", maxLength=255, example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", minLength=8, example="password123"),
     *             @OA\Property(property="password_confirmation", type="string", example="password123"),
     *             @OA\Property(property="avatar_url", type="string", format="url", nullable=true, example="https://example.com/avatar.jpg")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Registration successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", ref="#/components/schemas/User"),
     *             @OA\Property(property="token", type="string", example="1|abc123...", description="Only returned for API requests")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation errors")
     * )
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'avatar_url' => 'nullable|url',
        ]);
        $credentials = $request->only(['email', 'password']);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'avatar_url' => $validated['avatar_url'] ?? null,
        ]);

        if (!auth()->attempt($credentials)) {
            return $this->unauthorizedResponse('Invalid credentials');
        }

        $payload = [];

        if ($request->hasSession()) {
            $request->session()->regenerate();
        } else {
            $payload['token'] = $user->createToken('auth_token')->plainTextToken;
        }
        $payload['user'] = $user;

        return $this->responseJson($payload, 'Registration successful', 201);
    }

    /**
     * @OA\Post(
     *     path="/logout",
     *     summary="User logout",
     *     description="Logout user and revoke current access token",
     *     tags={"Auth"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(ref="#/components/parameters/AcceptJson"),
     *     @OA\Parameter(ref="#/components/parameters/ContentTypeJson"),
     *     @OA\Response(response=200, description="Logged out successfully")
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        if ($request->hasSession()) {
            auth()->guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        } else {
            $request->user()->currentAccessToken()?->delete();

        }

        return $this->responseJson(null, 'Logged out successfully');
    }

    /**
     * @OA\Get(
     *     path="/me",
     *     summary="Get current user",
     *     description="Get the currently authenticated user",
     *     tags={"Auth"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(ref="#/components/parameters/AcceptJson"),
     *     @OA\Response(
     *         response=200,
     *         description="Current user data",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function getMe(Request $request): JsonResponse
    {
        return $this->responseJson($request->user());
    }
}
