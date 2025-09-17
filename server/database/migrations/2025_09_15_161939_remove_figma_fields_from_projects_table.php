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
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['figma_file_key', 'figma_file_name', 'figma_last_synced']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('figma_file_key')->nullable()->after('description');
            $table->string('figma_file_name')->nullable()->after('figma_file_key');
            $table->timestamp('figma_last_synced')->nullable()->after('figma_file_name');
        });
    }
};
