<?php

use App\Models\EmailTemplate;
use App\Models\Project;
use App\Models\Release;
use App\Models\Screen;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->projectOwner = User::factory()->create(['email' => 'owner@test.com']);
    $this->otherUser = User::factory()->create(['email' => 'other@test.com']);
    $this->project = Project::factory()->create(['owner_id' => $this->projectOwner->id]);
});

describe('createRelease', function () {
    it('creates a release successfully with basic data', function () {
        $releaseData = [
            'version' => '1.0.0',
            'description' => 'Initial release',
            'tags' => 'stable production',
        ];

        $response = $this->actingAs($this->projectOwner)
            ->postJson("/api/projects/{$this->project->id}/releases", $releaseData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'payload' => [
                    'id',
                    'project_id',
                    'version',
                    'description',
                    'tags',
                    'created_at',
                    'updated_at'
                ]
            ]);

        $this->assertDatabaseHas('releases', [
            'project_id' => $this->project->id,
            'version' => '1.0.0',
            'description' => 'Initial release',
            'tags' => 'stable production',
        ]);
    });

    it('creates a release with screens and email templates attached', function () {
        $screens = Screen::factory()->count(3)->create(['project_id' => $this->project->id]);
        $emailTemplates = EmailTemplate::factory()->count(2)->create(['project_id' => $this->project->id]);

        $releaseData = [
            'version' => '2.0.0',
            'description' => 'Major release with new features',
            'screen_ids' => $screens->pluck('id')->toArray(),
            'email_template_ids' => $emailTemplates->pluck('id')->toArray(),
        ];

        $response = $this->actingAs($this->projectOwner)
            ->postJson("/api/projects/{$this->project->id}/releases", $releaseData);

        $response->assertStatus(201);

        $release = Release::latest()->first();
        expect($release->screens)->toHaveCount(3);
        expect($release->emailTemplates)->toHaveCount(2);

        $this->assertDatabaseHas('releasables', [
            'release_id' => $release->id,
            'releasable_type' => Screen::class,
            'releasable_id' => $screens->first()->id,
        ]);

        $this->assertDatabaseHas('releasables', [
            'release_id' => $release->id,
            'releasable_type' => EmailTemplate::class,
            'releasable_id' => $emailTemplates->first()->id,
        ]);
    });

    it('returns 404 when project does not exist', function () {
        $releaseData = [
            'version' => '1.0.0',
            'description' => 'Test release',
        ];

        $response = $this->actingAs($this->projectOwner)
            ->postJson('/api/projects/999/releases', $releaseData);

        $response->assertStatus(404)
            ->assertJson(['message' => 'Project not found']);
    });

    it('validates required fields', function () {
        $response = $this->actingAs($this->projectOwner)
            ->postJson("/api/projects/{$this->project->id}/releases", []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['version']);
    });

    it('validates screen_ids exist', function () {
        $releaseData = [
            'version' => '1.0.0',
            'screen_ids' => [999, 1000],
        ];

        $response = $this->actingAs($this->projectOwner)
            ->postJson("/api/projects/{$this->project->id}/releases", $releaseData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['screen_ids.0', 'screen_ids.1']);
    });

    it('validates email_template_ids exist', function () {
        $releaseData = [
            'version' => '1.0.0',
            'email_template_ids' => [999, 1000],
        ];

        $response = $this->actingAs($this->projectOwner)
            ->postJson("/api/projects/{$this->project->id}/releases", $releaseData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email_template_ids.0', 'email_template_ids.1']);
    });
});

describe('getProjectReleases', function () {
    it('returns project releases ordered by creation date desc', function () {
        $oldRelease = Release::factory()->create([
            'project_id' => $this->project->id,
            'version' => '1.0.0',
            'created_at' => now()->subDays(2),
        ]);

        $newRelease = Release::factory()->create([
            'project_id' => $this->project->id,
            'version' => '2.0.0',
            'created_at' => now()->subDay(),
        ]);

        $response = $this->actingAs($this->projectOwner)
            ->getJson("/api/projects/{$this->project->id}/releases");

        $response->assertStatus(200)
            ->assertJsonCount(2, 'payload')
            ->assertJsonPath('payload.0.version', '2.0.0')
            ->assertJsonPath('payload.1.version', '1.0.0');
    });

    it('returns releases with relationships loaded', function () {
        $screens = Screen::factory()->count(2)->create(['project_id' => $this->project->id]);
        $emailTemplates = EmailTemplate::factory()->count(1)->create(['project_id' => $this->project->id]);

        $release = Release::factory()->create(['project_id' => $this->project->id]);
        $release->screens()->attach($screens->pluck('id'));
        $release->emailTemplates()->attach($emailTemplates->pluck('id'));

        $response = $this->actingAs($this->projectOwner)
            ->getJson("/api/projects/{$this->project->id}/releases");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'payload' => [
                    '*' => [
                        'id',
                        'version'
                    ]
                ]
            ]);

        $payload = $response->json('payload.0');
        expect($payload['screens'])->toHaveCount(2);
        expect($payload['email_templates'])->toHaveCount(1);
    });

    it('returns 404 when project does not exist', function () {
        $response = $this->actingAs($this->projectOwner)
            ->getJson('/api/projects/999/releases');

        $response->assertStatus(404)
            ->assertJson(['message' => 'Project not found']);
    });

    it('returns empty array when project has no releases', function () {
        $response = $this->actingAs($this->projectOwner)
            ->getJson("/api/projects/{$this->project->id}/releases");

        $response->assertStatus(200)
            ->assertJsonCount(0, 'payload');
    });
});

describe('getReleaseById', function () {
    it('returns release with all relationships', function () {
        $screens = Screen::factory()->count(2)->create(['project_id' => $this->project->id]);
        $emailTemplates = EmailTemplate::factory()->count(1)->create(['project_id' => $this->project->id]);

        $release = Release::factory()->create(['project_id' => $this->project->id]);
        $release->screens()->attach($screens->pluck('id'));
        $release->emailTemplates()->attach($emailTemplates->pluck('id'));

        $response = $this->actingAs($this->projectOwner)
            ->getJson("/api/releases/{$release->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'payload' => [
                    'id',
                    'version'
                ]
            ]);

        $payload = $response->json('payload');
        expect($payload['screens'])->toHaveCount(2);
        expect($payload['email_templates'])->toHaveCount(1);
        expect($payload['project']['id'])->toBe($this->project->id);
    });

    it('returns 404 when release does not exist', function () {
        $response = $this->actingAs($this->projectOwner)
            ->getJson('/api/releases/999');

        $response->assertStatus(404)
            ->assertJson(['message' => 'Release not found']);
    });
});

describe('updateReleaseById', function () {
    it('updates release basic fields successfully', function () {
        $release = Release::factory()->create([
            'project_id' => $this->project->id,
            'version' => '1.0.0',
            'description' => 'Original description',
        ]);

        $updateData = [
            'version' => '1.1.0',
            'description' => 'Updated description',
            'tags' => ['hotfix', 'production'],
        ];

        $response = $this->actingAs($this->projectOwner)
            ->putJson("/api/releases/{$release->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonPath('payload.version', '1.1.0')
            ->assertJsonPath('payload.description', 'Updated description');

        $this->assertDatabaseHas('releases', [
            'id' => $release->id,
            'version' => '1.1.0',
            'description' => 'Updated description',
        ]);
    });

    it('updates release relationships', function () {
        $initialScreens = Screen::factory()->count(2)->create(['project_id' => $this->project->id]);
        $newScreens = Screen::factory()->count(3)->create(['project_id' => $this->project->id]);

        $initialEmailTemplates = EmailTemplate::factory()->count(1)->create(['project_id' => $this->project->id]);
        $newEmailTemplates = EmailTemplate::factory()->count(2)->create(['project_id' => $this->project->id]);

        $release = Release::factory()->create(['project_id' => $this->project->id]);
        $release->screens()->attach($initialScreens->pluck('id'));
        $release->emailTemplates()->attach($initialEmailTemplates->pluck('id'));

        $updateData = [
            'version' => '2.0.0',
            'screen_ids' => $newScreens->pluck('id')->toArray(),
            'email_template_ids' => $newEmailTemplates->pluck('id')->toArray(),
        ];

        $response = $this->actingAs($this->projectOwner)
            ->putJson("/api/releases/{$release->id}", $updateData);

        $response->assertStatus(200);

        $release->refresh();
        expect($release->screens)->toHaveCount(3);
        expect($release->emailTemplates)->toHaveCount(2);

        // Verify old relationships are removed
        $this->assertDatabaseMissing('releasables', [
            'release_id' => $release->id,
            'releasable_type' => Screen::class,
            'releasable_id' => $initialScreens->first()->id,
        ]);

        // Verify new relationships are added
        $this->assertDatabaseHas('releasables', [
            'release_id' => $release->id,
            'releasable_type' => Screen::class,
            'releasable_id' => $newScreens->first()->id,
        ]);
    });

    it('returns 404 when release does not exist', function () {
        $updateData = [
            'version' => '2.0.0',
            'description' => 'Updated description',
        ];

        $response = $this->actingAs($this->projectOwner)
            ->putJson('/api/releases/999', $updateData);

        $response->assertStatus(404)
            ->assertJson(['message' => 'Release not found']);
    });

    it('validates required fields', function () {
        $release = Release::factory()->create(['project_id' => $this->project->id]);

        $response = $this->actingAs($this->projectOwner)
            ->putJson("/api/releases/{$release->id}", []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['version']);
    });
});

describe('deleteReleaseById', function () {
    it('deletes release successfully', function () {
        $screens = Screen::factory()->count(2)->create(['project_id' => $this->project->id]);
        $release = Release::factory()->create(['project_id' => $this->project->id]);
        $release->screens()->attach($screens->pluck('id'));

        $response = $this->actingAs($this->projectOwner)
            ->deleteJson("/api/releases/{$release->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Release deleted']);

        $this->assertDatabaseMissing('releases', ['id' => $release->id]);

        // Verify polymorphic relationships are also deleted
        $this->assertDatabaseMissing('releasables', [
            'release_id' => $release->id,
        ]);
    });

    it('returns 404 when release does not exist', function () {
        $response = $this->actingAs($this->projectOwner)
            ->deleteJson('/api/releases/999');

        $response->assertStatus(404)
            ->assertJson(['message' => 'Release not found']);
    });
});

describe('authentication and authorization', function () {
    it('requires authentication for all endpoints', function () {
        $release = Release::factory()->create(['project_id' => $this->project->id]);

        $this->postJson("/api/projects/{$this->project->id}/releases", [])
            ->assertStatus(401);

        $this->getJson("/api/projects/{$this->project->id}/releases")
            ->assertStatus(401);

        $this->getJson("/api/releases/{$release->id}")
            ->assertStatus(401);

        $this->putJson("/api/releases/{$release->id}", [])
            ->assertStatus(401);

        $this->deleteJson("/api/releases/{$release->id}")
            ->assertStatus(401);
    });
});
