<?php

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

describe('createScreen', function () {
    it('creates a screen and returns its data', function () {
        $user = User::factory()->create();
        $project = Project::factory()->recycle($user)->create();
        $project->members()->attach($user);

        actingAs($user);

        $screenData = [
            'section_name' => 'Homepage Hero',
            'data' => [
                'title' => 'Welcome to our app',
                'subtitle' => 'The best solution for your needs'
            ]
        ];

        // Act
        $response = postJson("/api/projects/{$project->id}/screens", $screenData);

        // Assert
        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'payload' => [
                    'id',
                    'project_id',
                    'section_name',
                    'data',
                    'created_at',
                    'updated_at'
                ]
            ])
            ->assertJson([
                'payload' => [
                    'project_id' => $project->id,
                    'section_name' => 'Homepage Hero',
                    'data' => [
                        'title' => 'Welcome to our app',
                        'subtitle' => 'The best solution for your needs'
                    ],
                ]
            ]);

        assertDatabaseHas('screens', [
            'project_id' => $project->id,
            'section_name' => 'Homepage Hero',
            'data' => [
                'title' => 'Welcome to our app',
                'subtitle' => 'The best solution for your needs'
            ],
        ]);
    });

    it('validates required fields when creating a screen', function () {
        // Arrange
        $user = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $user->id]);
        $project->members()->attach($user);

        actingAs($user);

        // Act - Missing required 'data' field
        $response = postJson("/api/projects/{$project->id}/screens", [
            'section_name' => 'Test Section'
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['data']);

        // Act - Invalid data type (string instead of array)
        $response = postJson("/api/projects/{$project->id}/screens", [
            'section_name' => 'Test Section',
            'data' => 'invalid data'
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['data']);

        // Act - Section name too long
        $response = postJson("/api/projects/{$project->id}/screens", [
            'section_name' => str_repeat('a', 256),
            'data' => ['key' => 'value']
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['section_name']);
    });

    it('prevents creating screen for non-existent project', function () {
        // Arrange
        $user = User::factory()->create();
        actingAs($user);

        // Act
        $response = postJson("/api/projects/999/screens", [
            'data' => ['key' => 'value']
        ]);

        // Assert
        $response->assertStatus(404)
            ->assertJson(['message' => 'Project not found']);
    });

    it('prevents creating screen for project user does not own or is not member of', function () {
        // Arrange
        $owner = User::factory()->create();
        $nonMember = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);

        actingAs($nonMember);

        // Act
        $response = postJson("/api/projects/{$project->id}/screens", [
            'data' => ['key' => 'value']
        ]);

        // Assert
        $response->assertStatus(404)
            ->assertJson(['message' => 'Project not found']);
    });

    it('creates screen with only required fields', function () {
        // Arrange
        $user = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $user->id]);
        $project->members()->attach($user);

        actingAs($user);

        // Act
        $response = postJson("/api/projects/{$project->id}/screens", [
            'data' => ['content' => 'Simple content']
        ]);

        // Assert
        $response->assertStatus(201);

        assertDatabaseHas('screens', [
            'project_id' => $project->id,
            'section_name' => null,
            'data' => ['content' => 'Simple content']
        ]);
    });
});

describe('getProjectScreens', function () { });

describe('getScreenById', function () {
    it('shows a single Screen by id');
});

describe('updateScreenById', function () {
    it('updates a Screen successfully');

    it('prevents updating a Screen not owned by the user');
});

describe('deleteScreenById', function () {
    it('deletes a Screen successfully');

    it('prevents deleting a Screen not owned by the user');
});
