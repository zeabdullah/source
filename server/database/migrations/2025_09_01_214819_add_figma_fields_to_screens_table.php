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
        Schema::table('screens', function (Blueprint $table) {
            $table->string('figma_node_id')->nullable()->after('data');
            $table->string('figma_url')->nullable()->after('figma_node_id');
            $table->text('description')->nullable()->after('figma_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('screens', function (Blueprint $table) {
            $table->dropColumn(['figma_node_id', 'figma_url', 'description']);
        });
    }
};
