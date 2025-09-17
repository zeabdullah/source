<?php

use App\Models\Comment;
use App\Models\EmailTemplate;
use App\Models\Screen;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

describe('getScreenComments', function () {
    it('should return a list of comments for a screen', function () {
        $screen = Screen::factory()->create();
        Comment::factory(3)->create([
            'commentable_id' => $screen->id,
            'commentable_type' => Screen::class
        ]);

        $response = $this->getJson("/api/screens/{$screen->id}/comments");

        $response->assertStatus(200)
            ->assertJsonCount(3, 'payload');
    });
});

describe('createScreenComment', function () {
    it('should create a new comment for a screen', function () {
        $screen = Screen::factory()->create();
        $commentData = ['content' => 'This is a test comment'];

        $response = $this->postJson("/api/screens/{$screen->id}/comments", $commentData);

        $response->assertStatus(201)
            ->assertJson(['payload' => ['content' => $commentData['content']]]);

        $this->assertDatabaseHas('comments', [
            'content' => $commentData['content'],
            'commentable_id' => $screen->id,
            'commentable_type' => Screen::class,
            'user_id' => $this->user->id,
        ]);
    });

    it('should return an error if content is missing', function () {
        $screen = Screen::factory()->create();

        $response = $this->postJson("/api/screens/{$screen->id}/comments", []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['content']);
    });
});

describe('getEmailTemplateComments', function () {
    it('should return a list of comments for an email template', function () {
        $emailTemplate = EmailTemplate::factory()->create();
        Comment::factory(3)->create([
            'commentable_id' => $emailTemplate->id,
            'commentable_type' => EmailTemplate::class
        ]);

        $response = $this->getJson("/api/email-templates/{$emailTemplate->id}/comments");

        $response->assertStatus(200)
            ->assertJsonCount(3, 'payload');
    });
});

describe('createEmailTemplateComment', function () {
    it('should create a new comment for an email template', function () {
        $emailTemplate = EmailTemplate::factory()->create();
        $commentData = ['content' => 'This is a test comment for email template'];

        $response = $this->postJson("/api/email-templates/{$emailTemplate->id}/comments", $commentData);

        $response->assertStatus(201)
            ->assertJson(['payload' => ['content' => $commentData['content']]]);

        $this->assertDatabaseHas('comments', [
            'content' => $commentData['content'],
            'commentable_id' => $emailTemplate->id,
            'commentable_type' => EmailTemplate::class,
            'user_id' => $this->user->id,
        ]);
    });

    it('should return an error if content is missing for email template comment', function () {
        $emailTemplate = EmailTemplate::factory()->create();

        $response = $this->postJson("/api/email-templates/{$emailTemplate->id}/comments", []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['content']);
    });
});

describe('getCommentById', function () {
    it('should return a comment by its ID', function () {
        $screen = Screen::factory()->create();
        $comment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'commentable_id' => $screen->id,
            'commentable_type' => Screen::class
        ]);

        $response = $this->getJson("/api/comments/{$comment->id}");

        $response->assertStatus(200)
            ->assertJson(['payload' => ['id' => $comment->id, 'content' => $comment->content]]);
    });

    it('should return a 404 if the comment is not found', function () {
        $response = $this->getJson('/api/comments/999999');

        $response->assertStatus(404);
    });
});

describe('updateCommentById', function () {
    it('should update a comment by its ID', function () {
        $screen = Screen::factory()->create();
        $comment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'commentable_id' => $screen->id,
            'commentable_type' => Screen::class
        ]);
        $updateData = ['content' => 'Updated comment content'];

        $response = $this->putJson("/api/comments/{$comment->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson(['payload' => ['content' => $updateData['content']]]);

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'content' => $updateData['content'],
        ]);
    });

    it('should return a 404 if the comment is not found', function () {
        $updateData = ['content' => 'Updated comment content'];

        $response = $this->putJson('/api/comments/999999', $updateData);

        $response->assertStatus(404);
    });

    it('should return an error if content is missing', function () {
        $screen = Screen::factory()->create();
        $comment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'commentable_id' => $screen->id,
            'commentable_type' => Screen::class
        ]);

        $response = $this->putJson("/api/comments/{$comment->id}", []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['content']);
    });
});

describe('deleteCommentById', function () {
    it('should delete a comment by its ID', function () {
        $screen = Screen::factory()->create();
        $comment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'commentable_id' => $screen->id,
            'commentable_type' => Screen::class
        ]);

        $response = $this->deleteJson("/api/comments/{$comment->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    });

    it('should return a 404 if the comment is not found', function () {
        $response = $this->deleteJson('/api/comments/999999');

        $response->assertStatus(404);
    });
});
