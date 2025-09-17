<?php

namespace Database\Seeders;

use Database\Seeders\Models;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            Models\UserSeeder::class,
            Models\ProjectSeeder::class,
            Models\ScreenSeeder::class,
            Models\EmailTemplateSeeder::class,
            Models\AiChatSeeder::class,
            Models\CommentSeeder::class,
            Models\ReleaseSeeder::class,
        ]);
    }
}
