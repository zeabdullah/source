<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('releases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->string('version');
            $table->text('description')->nullable();
            $table->string('tags')->nullable();
            $table->timestamps();
        });

        // This is a 'commentable'
        Schema::create('screens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->string('section_name')->nullable();
            $table->json('data');
            $table->timestamps();
        });

        // This is a 'commentable'
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->string('section_name')->nullable();
            $table->json('data');
            $table->timestamps();
        });

        Schema::create('ai_chats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('commentable_id')->constrained('screens')->onDelete('cascade');
            $table->string('commentable_type');
            $table->text('content')->nullable();
            $table->timestamps();
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('commentable_id')->constrained('screens')->onDelete('cascade');
            $table->string('commentable_type');
            $table->text('content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
        Schema::dropIfExists('ai_chats');
        Schema::dropIfExists('email_templates');
        Schema::dropIfExists('screens');
        Schema::dropIfExists('releases');
        Schema::dropIfExists('projects');
    }
};
