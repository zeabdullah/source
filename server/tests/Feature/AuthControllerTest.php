<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\postJson;
use function Pest\Laravel\assertDatabaseHas;

// This is an example case for testing API-based controllers in Laravel

uses(RefreshDatabase::class);

describe('register', function () {
    it('registers a new user and returns token and the new user', function () {
        $requestPayload = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'StrongPass123',
            'password_confirmation' => 'StrongPass123',
            'avatar_url' => 'https://example.com/avatar.jpg',
        ];

        $response = postJson('/api/register', $requestPayload);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'payload' => [
                    'user' => ['id', 'name', 'email', 'avatar_url'],
                    'token',
                ],
            ]);

        assertDatabaseHas('users', [
            'email' => 'jane@example.com',
            'name' => 'Jane Doe',
            'avatar_url' => 'https://example.com/avatar.jpg',
        ]);
    });

    it('fails to register with duplicate email', function () {
        User::factory()->create(['email' => 'taken@example.com']);

        $response = postJson('/api/register', [
            'name' => 'John',
            'email' => 'taken@example.com',
            'password' => 'StrongPass123',
        ]);

        $response->assertStatus(422)
            ->assertInvalid(['email']);
    });
});

describe('login', function () {
    it('logs in with valid credentials and returns token', function () {
        $userPassword = 'Secret123!';
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => $userPassword,
        ]);

        $response = postJson('/api/login', [
            'email' => $user->email,
            'password' => $userPassword,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Login successful')
            ->assertJsonStructure([
                'message',
                'payload' => [
                    'user' => ['id', 'name', 'email', 'avatar_url'],
                    'token',
                ],
            ]);
    });

    it('rejects login with invalid credentials', function () {
        User::factory()->create([
            'email' => 'login-fail@example.com',
            'password' => 'CorrectPass123',
        ]);

        $response = postJson('/api/login', [
            'email' => 'login-fail@example.com',
            'password' => 'WrongPass123',
        ]);

        $response->assertStatus(401)
            ->assertJsonPath('message', 'Invalid credentials');
    });

    it('validates required fields for login', function () {
        $response = postJson('/api/login', []);

        $response->assertStatus(422)
            ->assertInvalid(['email', 'password']);
    });
});


