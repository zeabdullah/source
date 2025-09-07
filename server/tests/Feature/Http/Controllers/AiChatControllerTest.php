<?php

use function Pest\Laravel\postJson;

use App\Models\User;
use App\Models\Screen;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

describe('createScreenChatMessage', function () {
    it('creates a chat message for a screen', function () {
        $user = User::factory()->create();
        $screen = Screen::factory()->recycle($user)->create();
        actingAs($user);

        $payload = [
            'content' => 'Hello, this is a test message.',
        ];

        $response = postJson("/api/screens/{$screen->id}/chats", $payload);

        $response->assertStatus(201)
            ->assertJson([
                'payload' => [
                    'content' => 'Hello, this is a test message.',
                    'sender' => 'user',
                    'user_id' => $user->id,
                    'commentable_id' => $screen->id,
                    'commentable_type' => Screen::class,
                ]
            ]);
    });
});
