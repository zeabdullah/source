<?php

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;

use App\Models\User;
use App\Models\Screen;
use App\Models\Project;
use App\Models\EmailTemplate;
use App\Models\AiChat;
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

describe('createAiChatResponseForEmailTemplate', function () {
    it('creates an AI chat message for an email template successfully', function () {
        $user = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $user->id]);
        $emailTemplate = EmailTemplate::factory()->create(['project_id' => $project->id]);

        $payload = [
            'content' => 'This is an AI response for the email template.',
        ];

        $response = postJson("/api/projects/email-templates/{$emailTemplate->id}/chats/ai-response-webhook", $payload, [
            'Authorization' => 'Basic ' . base64_encode(env('BASIC_AUTH_USERNAME', 'test') . ':' . env('BASIC_AUTH_PASSWORD', 'password'))
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'payload' => [
                    'id',
                    'user_id',
                    'commentable_id',
                    'commentable_type',
                    'sender',
                    'content',
                    'created_at',
                    'updated_at'
                ]
            ])
            ->assertJson([
                'message' => 'AI chat message created successfully',
                'payload' => [
                    'user_id' => null,
                    'sender' => 'ai',
                    'content' => 'This is an AI response for the email template.',
                    'commentable_id' => $emailTemplate->id,
                    'commentable_type' => EmailTemplate::class,
                ]
            ]);

        assertDatabaseHas('ai_chats', [
            'sender' => 'ai',
            'content' => 'This is an AI response for the email template.',
            'commentable_id' => $emailTemplate->id,
            'commentable_type' => EmailTemplate::class,
        ]);
    });

    it('returns 404 when email template does not exist', function () {
        $payload = [
            'content' => 'This is an AI response for a non-existent email template.',
        ];

        $response = postJson('/api/projects/email-templates/999/chats/ai-response-webhook', $payload, [
            'Authorization' => 'Basic ' . base64_encode(env('BASIC_AUTH_USERNAME', 'test') . ':' . env('BASIC_AUTH_PASSWORD', 'password'))
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Email template not found'
            ]);
    });

    it('requires content field when creating AI chat response', function () {
        $user = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $user->id]);
        $emailTemplate = EmailTemplate::factory()->create(['project_id' => $project->id]);

        $response = postJson("/api/projects/email-templates/{$emailTemplate->id}/chats/ai-response-webhook", [], [
            'Authorization' => 'Basic ' . base64_encode(env('BASIC_AUTH_USERNAME', 'test') . ':' . env('BASIC_AUTH_PASSWORD', 'password'))
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['content']);
    });

    it('validates content is a string', function () {
        $user = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $user->id]);
        $emailTemplate = EmailTemplate::factory()->create(['project_id' => $project->id]);

        $payload = [
            'content' => 123, // Not a string
        ];

        $response = postJson("/api/projects/email-templates/{$emailTemplate->id}/chats/ai-response-webhook", $payload, [
            'Authorization' => 'Basic ' . base64_encode(env('BASIC_AUTH_USERNAME', 'test') . ':' . env('BASIC_AUTH_PASSWORD', 'password'))
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['content']);
    });
});
