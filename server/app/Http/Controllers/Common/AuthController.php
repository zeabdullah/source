<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (!auth()->attempt($credentials)) {
            return $this->unauthorized(message: 'Invalid credentials');
        }

        $user = auth()->user();
        $token = $this->generateAuthToken($user);

        return $this->response([
            'user' => $user,
            'token' => $token,
        ], 'Login successful');
    }

    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'avatar_url' => 'nullable|url',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'avatar_url' => $validated['avatar_url'] ?? null,
        ]);

        $token = $this->generateAuthToken($user);

        return $this->response([
            'user' => $user,
            'token' => $token,
        ], 'Registration successful', 201);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->response(null, 'Logged out successfully');
    }

    private function generateAuthToken(User $user): string
    {
        return $user->createToken('auth_token')->plainTextToken;
    }
}
