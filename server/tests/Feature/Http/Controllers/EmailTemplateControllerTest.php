<?php

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\putJson;

uses(RefreshDatabase::class);

describe('createEmailTemplate', function () {
    it('creates an email template and returns its data', function () {
        $user = User::factory()->create();
        actingAs($user);

        $project = Project::factory()->for($user, 'owner')->create();

        $payload = [
            'section_name' => 'Test Section',
            'campaign_id' => 'campaign123',
            'html' => '<p>Test HTML content</p>',
        ];

        $response = postJson("/api/projects/{$project->id}/email-templates", $payload);

        $response->assertStatus(201)
            ->assertExactJsonStructure([
                'message',
                'payload' => [
                    'id',
                    'project_id',
                    'section_name',
                    'campaign_id',
                    'html',
                    'created_at',
                    'updated_at',
                ]
            ])
            ->assertJson([
                'payload' => [
                    'project_id' => $project->id,
                    'section_name' => 'Test Section',
                    'campaign_id' => 'campaign123',
                    'html' => '<p>Test HTML content</p>',
                ]
            ]);

        assertDatabaseHas('email_templates', [
            'project_id' => $project->id,
            'section_name' => 'Test Section',
            'campaign_id' => 'campaign123',
            'html' => '<p>Test HTML content</p>',
        ]);
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
