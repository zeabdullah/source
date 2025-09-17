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
        Schema::create('release_screen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('release_id')->constrained('releases')->cascadeOnDelete();
            $table->foreignId('screen_id')->constrained('screens')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('release_screen');
    }
};
