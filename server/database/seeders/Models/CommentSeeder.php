<?php

namespace Database\Seeders\Models;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;
use App\Models\Comment;
use App\Models\Screen;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        $screens = Screen::all();
        foreach ($screens as $screen) {
            $randomUser = $users->random();

            Comment::factory(2)
                ->recycle($randomUser)
                ->recycle($screen)
                ->create([
                    'commentable_id' => $screen->id,
                    'commentable_type' => Screen::class,
                ]);
        }

        $emailTemplates = EmailTemplate::all();
        foreach ($emailTemplates as $template) {
            $randomUser = $users->random();

            Comment::factory(2)
                ->recycle($randomUser)
                ->recycle($template)
                ->create([
                    'commentable_id' => $template->id,
                    'commentable_type' => EmailTemplate::class,
                ]);
        }
    }
}
