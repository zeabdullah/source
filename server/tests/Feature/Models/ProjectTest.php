<?php

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\assertDatabaseHas;

uses(RefreshDatabase::class);

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
            'payload' => ['id', 'owner_id', 'name', 'description']
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

it('lists all projects for the authenticated user', function () {
    $user = User::factory()->create();
    actingAs($user);


    $response = getJson('/api/projects');
    $response->assertStatus(200)
        ->assertExactJsonStructure([
            'message',
            'payload' => [
                '*' => ['id', 'owner_id', 'name', 'description']
            ],
        ]);
});

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

it('updates a project successfully');
it('prevents updating a project not owned by the user');
it('deletes a project successfully');
it('prevents deleting a project not owned by the user');


