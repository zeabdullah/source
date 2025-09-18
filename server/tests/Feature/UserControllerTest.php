<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user can store brevo api token', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->postJson('/api/profile/brevo-token', [
            'brevo_api_token' => 'test-brevo-api-key-123'
        ]);

    $response->assertStatus(200);
    $response->assertJson(['message' => 'Brevo API token stored successfully']);

    $user->refresh();
    // Since brevo_api_token is encrypted, we need to check that it's not null
    expect($user->brevo_api_token)->not->toBeNull();
    expect($user->brevo_api_token)->not->toBe('');
});

test('user can update brevo api token via profile update', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->putJson('/api/profile', [
            'brevo_api_token' => 'updated-brevo-api-key-456'
        ]);

    $response->assertStatus(200);

    $user->refresh();
    // Since brevo_api_token is encrypted, we need to check that it's not null
    // and that it's different from the original (which would be null)
    expect($user->brevo_api_token)->not->toBeNull();
    expect($user->brevo_api_token)->not->toBe('');
});

test('brevo api token validation works', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->postJson('/api/profile/brevo-token', [
            'brevo_api_token' => ''
        ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['brevo_api_token']);
});

test('user can update figma token via profile update', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->putJson('/api/profile', [
            'figma_access_token' => 'updated-figma-token-789'
        ]);

    $response->assertStatus(200);

    $user->refresh();
    // Since figma_access_token is encrypted, we need to check that it's not null
    // and that it's different from the original (which would be null)
    expect($user->figma_access_token)->not->toBeNull();
    expect($user->figma_access_token)->not->toBe('');
});

test('user can remove figma token', function () {
    $user = User::factory()->create(['figma_access_token' => 'existing-token']);

    $response = $this->actingAs($user)
        ->deleteJson('/api/profile/figma-token');

    $response->assertStatus(200);
    $response->assertJson(['message' => 'Figma access token removed successfully']);

    $user->refresh();
    expect($user->figma_access_token)->toBeNull();
});

test('user can remove brevo api token', function () {
    $user = User::factory()->create(['brevo_api_token' => 'existing-token']);

    $response = $this->actingAs($user)
        ->deleteJson('/api/profile/brevo-token');

    $response->assertStatus(200);
    $response->assertJson(['message' => 'Brevo API token removed successfully']);

    $user->refresh();
    expect($user->brevo_api_token)->toBeNull();
});
