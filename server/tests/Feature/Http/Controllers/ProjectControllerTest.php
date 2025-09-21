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

describe('createProject', function () {
    it('creates a project and returns its data', function () {
        $user = User::factory()->create();
        actingAs($user);

        $payload = [
            'name' => 'Test Project',
            'description' => 'A sample project',
        ];

        $response = postJson('/api/projects', $payload);

        $response->assertStatus(201)
            ->assertExactJsonStructure([
                'message',
                'payload' => [
                    'id',
                    'owner_id',
                    'name',
                    'description',
                ]
            ])
            ->assertJson([
                'payload' => [
                    'owner_id' => $user->id,
                    'name' => 'Test Project',
                    'description' => 'A sample project',
                ]
            ]);

        assertDatabaseHas('projects', [
            'owner_id' => $user->id,
            'name' => 'Test Project',
            'description' => 'A sample project',
        ]);
    });

    it('validates required fields and name length when creating a project', function () {
        $user = User::factory()->create();
        actingAs($user);

        // No fields provided
        $response = postJson('/api/projects', []);
        $response->assertStatus(422)
            ->assertInvalid(['name']);

        // Name empty
        $payload = [
            'name' => '',
            'description' => 'Some description',
        ];
        $response = postJson('/api/projects', $payload);
        $response->assertStatus(422)
            ->assertInvalid(['name']);

        // Name too long (max 255)
        $payload = [
            'name' => str_repeat('a', 256),
            'description' => 'Some description',
        ];
        $response = postJson('/api/projects', $payload);
        $response->assertStatus(422)
            ->assertInvalid(['name']);

        // Name valid length
        $payload = [
            'name' => 'Valid Project Name',
            'description' => 'Some description',
        ];
        $response = postJson('/api/projects', $payload);
        $response->assertStatus(201)
            ->assertJsonPath('payload.name', 'Valid Project Name');
    });
});

describe('getMyProjects', function () {
    it('lists all projects for the authenticated user without search', function () {
        $user = User::factory()->create();
        actingAs($user);

        $project1 = Project::factory()->for($user, 'owner')->create(['name' => 'Alpha Project']);
        $project2 = Project::factory()->for($user, 'owner')->create(['name' => 'Beta Project']);

        $response = getJson('/api/projects');
        $response->assertStatus(200)
            ->assertExactJsonStructure([
                'message',
                'payload' => [
                    '*' => [
                        'id',
                        'owner_id',
                        'name',
                        'description',
                    ]
                ],
            ])
            ->assertJsonFragment(['id' => $project1->id, 'name' => 'Alpha Project'])
            ->assertJsonFragment(['id' => $project2->id, 'name' => 'Beta Project']);
    });

    it('lists only matching projects for the authenticated user with search', function () {
        $user = User::factory()->create();
        actingAs($user);

        $project1 = Project::factory()->for($user, 'owner')->create(['name' => 'Alpha Project']);
        $project2 = Project::factory()->for($user, 'owner')->create(['name' => 'Beta Project']);

        $searchResponse = getJson('/api/projects?search=alpha');
        $searchResponse->assertStatus(200)
            ->assertJsonFragment(['id' => $project1->id, 'name' => 'Alpha Project'])
            ->assertJsonMissing(['id' => $project2->id, 'name' => 'Beta Project']);
    });
});

describe('getProjectById', function () {
    it('shows a single project by id', function () {
        $user = User::factory()->create();
        actingAs($user);

        $project = Project::factory()->for($user, 'owner')->create([
            'name' => 'My Project',
            'description' => 'Project description',
        ]);

        $response = getJson("/api/projects/{$project->id}");

        $response->assertStatus(200)
            ->assertJson([
                'payload' => [
                    'id' => $project->id,
                    'owner_id' => $user->id,
                    'name' => 'My Project',
                    'description' => 'Project description',
                ]
            ]);
    });
});

describe('updateProjectById', function () {
    it('updates a project successfully', function () {
        $user = User::factory()->create();
        actingAs($user);

        $project = Project::factory()->for($user, 'owner')->create([
            'name' => 'Old Name',
            'description' => 'Old description',
        ]);

        $payload = [
            'name' => 'New Name',
            'description' => 'New description',
        ];

        $response = putJson("/api/projects/{$project->id}", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'payload' => [
                    'id' => $project->id,
                    'owner_id' => $user->id,
                    'name' => 'New Name',
                    'description' => 'New description',
                ]
            ]);
        assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => 'New Name',
            'description' => 'New description',
        ]);
    });

    it('prevents updating a project not owned by the user', function () {
        $authedUser = User::factory()->create();
        $ownerUser = User::factory()->create();
        actingAs($authedUser);

        $project = Project::factory()->for($ownerUser, 'owner')->create([
            'name' => 'Other Project',
            'description' => 'Other description',
        ]);

        $disallowedPayload = [
            'name' => 'Hacked Name',
            'description' => 'Hacked description',
        ];

        $response = putJson("/api/projects/{$project->id}", $disallowedPayload);

        $response->assertStatus(404);

        assertDatabaseMissing('projects', $disallowedPayload);
        assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => 'Other Project',
            'description' => 'Other description',
        ]);
    });
});

describe('deleteProjectById', function () {
    it('deletes a project successfully', function () {
        $user = User::factory()->create();
        actingAs($user);

        $project = Project::factory()->for($user, 'owner')->create();

        assertDatabaseHas('projects', [
            'id' => $project->id,
        ]);

        $response = deleteJson("/api/projects/{$project->id}");

        $response->assertStatus(200)
            ->assertJson([
                'payload' => [
                    'id' => $project->id,
                    'owner_id' => $user->id,
                ],
            ]);
        assertDatabaseMissing('projects', [
            'id' => $project->id,
        ]);
    });

    it('prevents deleting a project not owned by the user', function () {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        actingAs($user);

        $project = Project::factory()->for($otherUser, 'owner')->create();

        $response = deleteJson("/api/projects/{$project->id}");

        $response->assertStatus(404);
        assertDatabaseHas('projects', [
            'id' => $project->id,
        ]);
    });
});
