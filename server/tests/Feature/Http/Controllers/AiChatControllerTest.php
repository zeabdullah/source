<?php

use App\Models\User;
use App\Models\Screen;
use App\Models\Project;
use App\Models\EmailTemplate;
use App\Models\AiChat;
use App\Services\AiAgentService;
use App\Services\N8nService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

uses(RefreshDatabase::class);

describe('sendEmailTemplateChatMessage', function () {
    it('sends a chat message to AI for an email template', function () {
        $owner = User::factory()->create();
        $project = Project::factory()->recycle($owner)->create();
        $emailTemplate = EmailTemplate::factory()->recycle($project)->create();
        actingAs($owner);

        // Mock AI response
        $mockAiAgent = Mockery::mock(AiAgentService::class);
        $mockAiAgent->shouldReceive('generateEmailTemplateReply')
            ->once()
            ->andReturn([
                'chat_message' => 'I have analyzed your email template and made improvements.',
                'updated_html' => '<html><body>Updated content</body></html>'
            ]);
        app()->instance(AiAgentService::class, $mockAiAgent);

        // Mock N8n thumbnail generation
        $mockN8n = Mockery::mock(N8nService::class);
        $mockN8n->shouldReceive('generateBase64ThumbnailFromHtml')
            ->once()
            ->andReturn('base64_thumbnail_data');
        app()->instance(N8nService::class, $mockN8n);

        $response = postJson("/api/email-templates/{$emailTemplate->id}/chats", [
            'content' => 'Please improve this email template'
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'payload' => [
                    'user' => [
                        'id',
                        'content',
                        'sender',
                        'user_id',
                        'commentable_id',
                        'commentable_type'
                    ],
                    'ai' => [
                        'id',
                        'content',
                        'sender',
                        'user_id',
                        'commentable_id',
                        'commentable_type'
                    ],
                    'template_updated',
                    'brevo_updated',
                    'thumbnail_updated'
                ]
            ]);

        assertDatabaseHas('ai_chats', [
            'content' => 'Please improve this email template',
            'sender' => 'user',
            'user_id' => $owner->id,
            'commentable_id' => $emailTemplate->id,
            'commentable_type' => EmailTemplate::class
        ]);

        assertDatabaseHas('ai_chats', [
            'content' => 'I have analyzed your email template and made improvements.',
            'sender' => 'ai',
            'user_id' => null,
            'commentable_id' => $emailTemplate->id,
            'commentable_type' => EmailTemplate::class
        ]);
    });

    it('returns 404 if email template is not found', function () {
        $owner = User::factory()->create();
        actingAs($owner);

        $response = postJson('/api/email-templates/999/chats', [
            'content' => 'Please improve this email template'
        ]);

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Email template not found']);
    });

    it('validates content field', function () {
        $owner = User::factory()->create();
        $project = Project::factory()->recycle($owner)->create();
        $emailTemplate = EmailTemplate::factory()->recycle($project)->create();
        actingAs($owner);

        $response = postJson("/api/email-templates/{$emailTemplate->id}/chats", []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['content']);
    });

    it('prevents access to email template not owned by user', function () {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $project = Project::factory()->recycle($owner)->create();
        $emailTemplate = EmailTemplate::factory()->recycle($project)->create();

        // Add the other user as a member to test the authorization logic
        $project->members()->attach($otherUser);
        actingAs($otherUser);

        // Mock AI response for the member user
        $mockAiAgent = Mockery::mock(AiAgentService::class);
        $mockAiAgent->shouldReceive('generateEmailTemplateReply')
            ->once()
            ->andReturn([
                'chat_message' => 'I have analyzed your email template and made improvements.',
                'updated_html' => '<html><body>Updated content</body></html>'
            ]);
        app()->instance(AiAgentService::class, $mockAiAgent);

        // Mock N8n thumbnail generation
        $mockN8n = Mockery::mock(N8nService::class);
        $mockN8n->shouldReceive('generateBase64ThumbnailFromHtml')
            ->once()
            ->andReturn('base64_thumbnail_data');
        app()->instance(N8nService::class, $mockN8n);

        $response = postJson("/api/email-templates/{$emailTemplate->id}/chats", [
            'content' => 'Please improve this email template'
        ]);

        // Since the user is a member, they should have access
        $response->assertStatus(201);
    });
});

describe('getEmailTemplateChat', function () {
    it('gets all chat messages for an email template', function () {
        $owner = User::factory()->create();
        $project = Project::factory()->recycle($owner)->create();
        $emailTemplate = EmailTemplate::factory()->recycle($project)->create();

        // Create some chat messages
        $userChat = AiChat::factory()->create([
            'user_id' => $owner->id,
            'sender' => 'user',
            'commentable_id' => $emailTemplate->id,
            'commentable_type' => EmailTemplate::class,
            'content' => 'User message'
        ]);

        $aiChat = AiChat::factory()->create([
            'user_id' => null,
            'sender' => 'ai',
            'commentable_id' => $emailTemplate->id,
            'commentable_type' => EmailTemplate::class,
            'content' => 'AI response'
        ]);

        actingAs($owner);

        $response = getJson("/api/email-templates/{$emailTemplate->id}/chats");

        $response->assertStatus(200)
            ->assertJsonCount(2, 'payload')
            ->assertJsonStructure([
                'message',
                'payload' => [
                    '*' => [
                        'id',
                        'content',
                        'sender',
                        'user_id',
                        'commentable_id',
                        'commentable_type',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]);
    });

    it('returns 404 if email template is not found', function () {
        $owner = User::factory()->create();
        actingAs($owner);

        $response = getJson('/api/email-templates/999/chats');

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Email template not found']);
    });
});

describe('sendScreenChatMessage', function () {
    it('sends a chat message to AI for a screen', function () {
        $owner = User::factory()->create(['figma_access_token' => 'test_token']);
        $project = Project::factory()->recycle($owner)->create();
        $screen = Screen::factory()->recycle($project)->create();
        actingAs($owner);

        // Mock AI response
        $mockAiAgent = Mockery::mock(AiAgentService::class);
        $mockAiAgent->shouldReceive('generateFigmaReply')
            ->once()
            ->andReturn([
                'chat_message' => 'I have analyzed your screen design and provided suggestions.'
            ]);
        app()->instance(AiAgentService::class, $mockAiAgent);

        $response = postJson("/api/screens/{$screen->id}/chats", [
            'content' => 'Please analyze this screen design'
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'payload' => [
                    'user' => [
                        'id',
                        'content',
                        'sender',
                        'user_id',
                        'commentable_id',
                        'commentable_type'
                    ],
                    'ai' => [
                        'id',
                        'content',
                        'sender',
                        'user_id',
                        'commentable_id',
                        'commentable_type'
                    ]
                ]
            ]);

        assertDatabaseHas('ai_chats', [
            'content' => 'Please analyze this screen design',
            'sender' => 'user',
            'user_id' => $owner->id,
            'commentable_id' => $screen->id,
            'commentable_type' => Screen::class
        ]);
    });

    it('returns 403 if user does not have figma access token', function () {
        $owner = User::factory()->create(['figma_access_token' => null]);
        $project = Project::factory()->recycle($owner)->create();
        $screen = Screen::factory()->recycle($project)->create();
        actingAs($owner);

        $response = postJson("/api/screens/{$screen->id}/chats", [
            'content' => 'Please analyze this screen design'
        ]);

        $response->assertStatus(403)
            ->assertJsonFragment(['message' => 'You must set a valid Figma access token to your account to send an AI chat message']);
    });

    it('returns 404 if screen is not found', function () {
        $owner = User::factory()->create(['figma_access_token' => 'test_token']);
        actingAs($owner);

        $response = postJson('/api/screens/999/chats', [
            'content' => 'Please analyze this screen design'
        ]);

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Screen not found']);
    });

    it('validates content field', function () {
        $owner = User::factory()->create(['figma_access_token' => 'test_token']);
        $project = Project::factory()->recycle($owner)->create();
        $screen = Screen::factory()->recycle($project)->create();
        actingAs($owner);

        $response = postJson("/api/screens/{$screen->id}/chats", []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['content']);
    });
});

describe('getScreenChat', function () {
    it('gets all chat messages for a screen', function () {
        $owner = User::factory()->create();
        $project = Project::factory()->recycle($owner)->create();
        $screen = Screen::factory()->recycle($project)->create();

        // Create some chat messages
        $userChat = AiChat::factory()->create([
            'user_id' => $owner->id,
            'sender' => 'user',
            'commentable_id' => $screen->id,
            'commentable_type' => Screen::class,
            'content' => 'User message'
        ]);

        $aiChat = AiChat::factory()->create([
            'user_id' => null,
            'sender' => 'ai',
            'commentable_id' => $screen->id,
            'commentable_type' => Screen::class,
            'content' => 'AI response'
        ]);

        actingAs($owner);

        $response = getJson("/api/screens/{$screen->id}/chats");

        $response->assertStatus(200)
            ->assertJsonCount(2, 'payload')
            ->assertJsonStructure([
                'message',
                'payload' => [
                    '*' => [
                        'id',
                        'content',
                        'sender',
                        'user_id',
                        'commentable_id',
                        'commentable_type',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]);
    });

    it('returns 404 if screen is not found', function () {
        $owner = User::factory()->create();
        actingAs($owner);

        $response = getJson('/api/screens/999/chats');

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Screen not found']);
    });
});

describe('updateChatMessageById', function () {
    it('updates a chat message by its ID', function () {
        $owner = User::factory()->create();
        $project = Project::factory()->recycle($owner)->create();
        $emailTemplate = EmailTemplate::factory()->recycle($project)->create();
        $chatMessage = AiChat::factory()->create([
            'user_id' => $owner->id,
            'sender' => 'user',
            'commentable_id' => $emailTemplate->id,
            'commentable_type' => EmailTemplate::class,
            'content' => 'Original message'
        ]);
        actingAs($owner);

        $response = putJson("/api/chats/{$chatMessage->id}", [
            'content' => 'Updated message content'
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'content' => 'Updated message content'
            ]);

        assertDatabaseHas('ai_chats', [
            'id' => $chatMessage->id,
            'content' => 'Updated message content'
        ]);
    });

    it('returns 403 if user is not authorized to update the message', function () {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $project = Project::factory()->recycle($owner)->create();
        $emailTemplate = EmailTemplate::factory()->recycle($project)->create();
        $chatMessage = AiChat::factory()->create([
            'user_id' => $owner->id,
            'sender' => 'user',
            'commentable_id' => $emailTemplate->id,
            'commentable_type' => EmailTemplate::class,
            'content' => 'Original message'
        ]);
        actingAs($otherUser);

        $response = putJson("/api/chats/{$chatMessage->id}", [
            'content' => 'Updated message content'
        ]);

        $response->assertStatus(403)
            ->assertJsonFragment(['message' => 'You are not authorized to update this message']);
    });

    it('returns 404 if chat message is not found', function () {
        $owner = User::factory()->create();
        actingAs($owner);

        $response = putJson('/api/chats/999', [
            'content' => 'Updated message content'
        ]);

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Chat message not found']);
    });

    it('validates content field', function () {
        $owner = User::factory()->create();
        $project = Project::factory()->recycle($owner)->create();
        $emailTemplate = EmailTemplate::factory()->recycle($project)->create();
        $chatMessage = AiChat::factory()->create([
            'user_id' => $owner->id,
            'sender' => 'user',
            'commentable_id' => $emailTemplate->id,
            'commentable_type' => EmailTemplate::class,
            'content' => 'Original message'
        ]);
        actingAs($owner);

        $response = putJson("/api/chats/{$chatMessage->id}", []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['content']);
    });
});

describe('deleteChatMessageById', function () {
    it('deletes a chat message by its ID', function () {
        $owner = User::factory()->create();
        $project = Project::factory()->recycle($owner)->create();
        $emailTemplate = EmailTemplate::factory()->recycle($project)->create();
        $chatMessage = AiChat::factory()->create([
            'user_id' => $owner->id,
            'sender' => 'user',
            'commentable_id' => $emailTemplate->id,
            'commentable_type' => EmailTemplate::class,
            'content' => 'Message to delete'
        ]);
        actingAs($owner);

        $response = deleteJson("/api/chats/{$chatMessage->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'content' => 'Message to delete'
            ]);

        assertDatabaseMissing('ai_chats', [
            'id' => $chatMessage->id
        ]);
    });

    it('returns 403 if user is not authorized to delete the message', function () {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $project = Project::factory()->recycle($owner)->create();
        $emailTemplate = EmailTemplate::factory()->recycle($project)->create();
        $chatMessage = AiChat::factory()->create([
            'user_id' => $owner->id,
            'sender' => 'user',
            'commentable_id' => $emailTemplate->id,
            'commentable_type' => EmailTemplate::class,
            'content' => 'Message to delete'
        ]);
        actingAs($otherUser);

        $response = deleteJson("/api/chats/{$chatMessage->id}");

        $response->assertStatus(403)
            ->assertJsonFragment(['message' => 'You are not authorized to update this message']);
    });

    it('returns 404 if chat message is not found', function () {
        $owner = User::factory()->create();
        actingAs($owner);

        $response = deleteJson('/api/chats/999');

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Chat message not found']);
    });
});
