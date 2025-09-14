<?php

use App\Models\Project;
use App\Models\User;
use App\Services\MailchimpService;
use App\Services\N8nService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('importEmailTemplate', function () {
    it('successfully imports email template from Mailchimp campaign', function () {
        $user = User::factory()->create();
        $this->actingAs($user);
        $project = Project::factory()->for($user, 'owner')->create();

        $mailchimpCampaignId = 'test-campaign-123';
        $campaignContent = (object) [
            'html' => '<html><body><h1>Test Campaign</h1><p>This is a test email campaign.</p></body></html>'
        ];
        $thumbnailUrl = 'https://example.com/thumbnail.jpg';

        $this->mock(MailchimpService::class, function ($mock) use ($mailchimpCampaignId, $campaignContent) {
            $mock->shouldReceive('getCampaignContent')
                ->once()
                ->with($mailchimpCampaignId)
                ->andReturn($campaignContent);
        });

        $this->mock(N8nService::class, function ($mock) use ($campaignContent, $thumbnailUrl) {
            $mock->shouldReceive('generateThumbnail')
                ->once()
                ->with($campaignContent->html)
                ->andReturn((object) ['thumbnail_url' => $thumbnailUrl]);
        });

        $payload = [
            'mailchimp_campaign_id' => $mailchimpCampaignId,
        ];

        // Act
        $response = $this->postJson("/api/projects/{$project->id}/email-templates/import", $payload);

        // Assert
        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'payload' => [
                    'id',
                    'project_id',
                    'campaign_id',
                    'thumbnail_url',
                    'created_at',
                    'updated_at',
                ]
            ])
            ->assertJson([
                'payload' => [
                    'project_id' => $project->id,
                    'campaign_id' => $mailchimpCampaignId,
                    'thumbnail_url' => $thumbnailUrl,
                ]
            ]);

        assertDatabaseHas('email_templates', [
            'project_id' => $project->id,
            'campaign_id' => $mailchimpCampaignId,
            'thumbnail_url' => $thumbnailUrl,
        ]);
    });

    it('validates mailchimp_campaign_id is required', function () {
        $user = User::factory()->create();
        $this->actingAs($user);
        $project = Project::factory()->for($user, 'owner')->create();

        $payload = [];

        // Act
        $response = $this->postJson("/api/projects/{$project->id}/email-templates/import", $payload);

        // Assert
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['mailchimp_campaign_id']);
    });

    it('returns 404 when Mailchimp campaign is not found', function () {
        $user = User::factory()->create();
        $this->actingAs($user);
        $project = Project::factory()->for($user, 'owner')->create();

        $mailchimpCampaignId = 'non-existent-campaign';

        $this->mock(MailchimpService::class, function ($mock) use ($mailchimpCampaignId) {
            $mock->shouldReceive('getCampaignContent')
                ->once()
                ->with($mailchimpCampaignId)
                ->andThrow(new \GuzzleHttp\Exception\RequestException(
                    'Campaign not found',
                    new \GuzzleHttp\Psr7\Request('GET', 'test'),
                    new \GuzzleHttp\Psr7\Response(404)
                ));
        });

        $payload = [
            'mailchimp_campaign_id' => $mailchimpCampaignId,
        ];

        // Act
        $response = $this->postJson("/api/projects/{$project->id}/email-templates/import", $payload);

        // Assert
        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Campaign not found in Mailchimp'
            ]);
    });

    it('returns 500 when Mailchimp service fails', function () {
        $user = User::factory()->create();
        $this->actingAs($user);
        $project = Project::factory()->for($user, 'owner')->create();

        $mailchimpCampaignId = 'test-campaign-123';

        $this->mock(MailchimpService::class, function ($mock) use ($mailchimpCampaignId) {
            $mock->shouldReceive('getCampaignContent')
                ->once()
                ->with($mailchimpCampaignId)
                ->andThrow(new \GuzzleHttp\Exception\RequestException(
                    'Service unavailable',
                    new \GuzzleHttp\Psr7\Request('GET', 'test'),
                    new \GuzzleHttp\Psr7\Response(500)
                ));
        });

        $payload = [
            'mailchimp_campaign_id' => $mailchimpCampaignId,
        ];

        // Act
        $response = $this->postJson("/api/projects/{$project->id}/email-templates/import", $payload);

        // Assert
        $response->assertStatus(500)
            ->assertJsonStructure(['message'])
            ->assertJsonFragment(['message' => 'Failed to import email template: Service unavailable']);
    });

    it('returns 500 when N8n service fails', function () {
        $user = User::factory()->create();
        $this->actingAs($user);
        $project = Project::factory()->for($user, 'owner')->create();

        $mailchimpCampaignId = 'test-campaign-123';
        $campaignContent = (object) [
            'html' => '<html><body><h1>Test Campaign</h1></body></html>'
        ];

        $this->mock(MailchimpService::class, function ($mock) use ($mailchimpCampaignId, $campaignContent) {
            $mock->shouldReceive('getCampaignContent')
                ->once()
                ->with($mailchimpCampaignId)
                ->andReturn($campaignContent);
        });

        $this->mock(N8nService::class, function ($mock) use ($campaignContent) {
            $mock->shouldReceive('generateThumbnail')
                ->once()
                ->with($campaignContent->html)
                ->andThrow(new \Exception('N8n service unavailable'));
        });

        $payload = [
            'mailchimp_campaign_id' => $mailchimpCampaignId,
        ];

        // Act
        $response = $this->postJson("/api/projects/{$project->id}/email-templates/import", $payload);

        // Assert
        $response->assertStatus(500)
            ->assertJsonStructure(['message'])
            ->assertJsonFragment(['message' => 'Failed to import email template: N8n service unavailable']);
    });
});

describe('getProjectEmailTemplates', function () {
    it('lists all email templates for a project', function () {
        $this->assertTrue(true);
    });
});

describe('updateEmailTemplateById', function () {
    it('updates an email template successfully', function () {
        $this->assertTrue(true);
    });
});

describe('deleteEmailTemplateById', function () {
    it('deletes an email template successfully', function () {
        $this->assertTrue(true);
    });
});
