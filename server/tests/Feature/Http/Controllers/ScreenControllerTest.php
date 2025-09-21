<?php

use App\Http\Middleware\EnsureUserIsProjectOwner;
use App\Models\Project;
use App\Models\Screen;
use App\Models\User;
use App\Services\FigmaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;
use function Pest\Laravel\withMiddleware;

uses(RefreshDatabase::class);

describe('importScreens', function () {
    it('returns an error if the fields are invalid', function () {
        $user = User::factory()->create();
        $project = Project::factory()->recycle($user)->create();
        actingAs($user);

        $response = postJson("/api/projects/{$project->id}/screens/import", [
            'frame_ids' => [1, 1],
            'figma_access_token' => true,
            'figma_file_key' => 'abc123def456',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['figma_access_token', 'frame_ids.0', 'frame_ids.1']);
    });

    it('returns an error if the fields are missing', function () {
        $user = User::factory()->create();
        $project = Project::factory()->recycle($user)->create();
        actingAs($user);

        $response = postJson("/api/projects/{$project->id}/screens/import", [
            'frame_ids' => ['1:23', '1:24'],
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['figma_access_token', 'figma_file_key']);
    });
});

describe('getProjectScreens', function () {
    it('returns all screens for a project', function () {
        $owner = User::factory()->create();
        $project = Project::factory()->recycle($owner)->create();
        $screens = Screen::factory()->count(3)->create(['project_id' => $project->id]);
        actingAs($owner);

        $response = getJson("/api/projects/{$project->id}/screens");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'payload' => [
                    '*' => [
                        'id',
                        'project_id',
                        'section_name',
                        'figma_node_name',
                        'figma_svg_url',
                        'figma_node_id',
                        'figma_file_key',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ])
            ->assertJsonCount(3, 'payload');
    });

    it('filters screens by search term', function () {
        $owner = User::factory()->create();
        $project = Project::factory()->recycle($owner)->create();
        $loginScreen = Screen::factory()->create(['project_id' => $project->id, 'section_name' => 'Login Page']);
        $dashboardScreen = Screen::factory()->create(['project_id' => $project->id, 'section_name' => 'Dashboard']);
        actingAs($owner);

        $response = getJson("/api/projects/{$project->id}/screens?search=Login");

        $response->assertStatus(200)
            ->assertJsonCount(1, 'payload')
            ->assertJsonFragment(['section_name' => 'Login Page'], 'payload');
    });

    it('prevents accessing screens for project not owned by user', function () {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $project = Project::factory()->recycle($owner)->create();
        actingAs($otherUser);

        $response = getJson("/api/projects/{$project->id}/screens");

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Project not found']);
    });
});

describe('getScreenById', function () {
    it('shows a single Screen by id', function () {
        $owner = User::factory()->create();
        $project = Project::factory()->recycle($owner)->create();
        $screen = Screen::factory()->recycle($project)->create();
        actingAs($owner);

        $response = getJson("/api/projects/{$project->id}/screens/{$screen->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $screen->id,
                'project_id' => $project->id,
                'section_name' => $screen->section_name
            ]);
    });

    it('returns 404 for non-existent screen', function () {
        $owner = User::factory()->create();
        $project = Project::factory()->recycle($owner)->create();
        actingAs($owner);

        $response = getJson("/api/projects/{$project->id}/screens/999");

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Screen not found']);
    });

    it('prevents accessing screen for project not owned by user', function () {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $project = Project::factory()->recycle($owner)->create();
        $screen = Screen::factory()->recycle($project)->create();
        actingAs($otherUser);

        $response = getJson("/api/projects/{$project->id}/screens/{$screen->id}");

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Project not found']);
    });
});

describe('updateScreenById', function () {
    it('updates a Screen successfully', function () {
        $owner = User::factory()->create();
        $project = Project::factory()->recycle($owner)->create();
        $screen = Screen::factory()->recycle($project)->create(['section_name' => 'Old Name']);
        actingAs($owner);

        $response = putJson("/api/projects/{$project->id}/screens/{$screen->id}", [
            'section_name' => 'Updated Screen Name'
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $screen->id,
                'section_name' => 'Updated Screen Name'
            ]);

        assertDatabaseHas('screens', [
            'id' => $screen->id,
            'section_name' => 'Updated Screen Name'
        ]);
    });

    it('validates section_name field', function () {
        $owner = User::factory()->create();
        $project = Project::factory()->recycle($owner)->create();
        $screen = Screen::factory()->recycle($project)->create();
        actingAs($owner);

        $response = putJson("/api/projects/{$project->id}/screens/{$screen->id}", [
            'section_name' => str_repeat('a', 256) // Too long
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['section_name']);
    });

    it('prevents updating a Screen not owned by the user', function () {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $project = Project::factory()->recycle($owner)->create();
        $screen = Screen::factory()->recycle($project)->create();
        actingAs($otherUser);

        $response = putJson("/api/projects/{$project->id}/screens/{$screen->id}", [
            'section_name' => 'Updated Name'
        ]);

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Project not found']);
    });
});

describe('deleteScreenById', function () {
    it('deletes a Screen successfully', function () {
        $owner = User::factory()->create();
        $project = Project::factory()->recycle($owner)->create();
        $screen = Screen::factory()->recycle($project)->create();
        actingAs($owner);

        $response = deleteJson("/api/projects/{$project->id}/screens/{$screen->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $screen->id,
                'message' => 'Screen deleted'
            ]);

        assertDatabaseMissing('screens', ['id' => $screen->id]);
    });

    it('returns 404 for non-existent screen', function () {
        $owner = User::factory()->create();
        $project = Project::factory()->recycle($owner)->create();
        actingAs($owner);

        $response = deleteJson("/api/projects/{$project->id}/screens/999");

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Screen not found']);
    });

    it('prevents deleting a Screen not owned by the user', function () {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $project = Project::factory()->recycle($owner)->create();
        $screen = Screen::factory()->recycle($project)->create();
        actingAs($otherUser);

        $response = deleteJson("/api/projects/{$project->id}/screens/{$screen->id}");

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Project not found']);
    });
});
