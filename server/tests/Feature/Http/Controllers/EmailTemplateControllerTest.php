<?php

use App\Models\EmailTemplate;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

uses(RefreshDatabase::class);

describe('getProjectEmailTemplates', function () {
    it('lists all email templates for a project', function () {
        $user = User::factory()->create();
        $project = Project::factory()->recycle($user)->create();
        EmailTemplate::factory()->recycle($project)->create([
            'section_name' => 'Welcome Email',
            'html_content' => '<html>Welcome content</html>'
        ]);
        EmailTemplate::factory()->recycle($project)->create([
            'section_name' => 'Newsletter',
            'html_content' => '<html>Newsletter content</html>'
        ]);

        $response = actingAs($user)->getJson("/api/projects/{$project->id}/email-templates");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'payload' => [
                    '*' => [
                        'id',
                        'project_id',
                        'section_name',
                        'html_content',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ])
            ->assertJsonCount(2, 'payload');
    });
});

describe('updateEmailTemplateById', function () {
    it('updates an email template successfully', function () {
        $user = User::factory()->create();
        $project = Project::factory()->recycle($user)->create();
        $emailTemplate = EmailTemplate::factory()->recycle($project)->create([
            'section_name' => 'Old Name',
            'html_content' => '<html>Old content</html>'
        ]);

        $response = actingAs($user)
            ->putJson("/api/projects/{$project->id}/email-templates/{$emailTemplate->id}", [
                'section_name' => 'Updated Name',
                'html' => '<html>Updated content</html>'
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'payload' => [
                    'id',
                    'project_id',
                    'section_name',
                    'html_content',
                    'created_at',
                    'updated_at'
                ]
            ])
            ->assertJson([
                'message' => 'Email template updated successfully',
                'payload' => [
                    'section_name' => 'Updated Name'
                ]
            ]);

        assertDatabaseHas('email_templates', [
            'id' => $emailTemplate->id,
            'section_name' => 'Updated Name'
        ]);
    });
});

describe('deleteEmailTemplateById', function () {
    it('deletes an email template successfully', function () {
        $user = User::factory()->create();
        $project = Project::factory()->recycle($user)->create();
        $emailTemplate = EmailTemplate::factory()->recycle($project)->create([
            'section_name' => 'Template to Delete'
        ]);

        $response = actingAs($user)
            ->deleteJson("/api/projects/{$project->id}/email-templates/{$emailTemplate->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'payload' => [
                    'id',
                    'project_id',
                    'section_name',
                    'created_at',
                    'updated_at'
                ]
            ])
            ->assertJson([
                'message' => 'Email template deleted successfully'
            ]);

        assertDatabaseMissing('email_templates', [
            'id' => $emailTemplate->id
        ]);
    });
});
