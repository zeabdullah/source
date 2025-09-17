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
        Schema::create('ai_chats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('commentable_id');
            $table->string('commentable_type');
            $table->enum('sender', ['user', 'ai']);
            $table->text('content')->nullable();
            $table->timestamps();

            $table->index(['created_at']); // we'll be frequently sorting messages by creation date.
            $table->index(['commentable_id', 'commentable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove indexes if they exist before dropping the table
        Schema::table('ai_chats', function (Blueprint $table) {
            $table->dropIndex(['commentable_id', 'commentable_type']);
            $table->dropIndex(['created_at']);
        });
        Schema::dropIfExists('ai_chats');
    }
};
