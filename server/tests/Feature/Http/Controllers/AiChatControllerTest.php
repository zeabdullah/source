<?php

use function Pest\Laravel\postJson;

use App\Models\User;
use App\Models\Screen;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

describe('createScreenChatMessage', function () {
    it('requires figma_access_token when creating a chat message for a screen', function () {
        $user = User::factory()->create();
        $screen = Screen::factory()->recycle($user)->create();
        actingAs($user);

        $payload = [
            'content' => 'Hello, this is a test message.',
        ];

        $response = postJson("/api/screens/{$screen->id}/chats", $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['figma_access_token']);
    });
});
