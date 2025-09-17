<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AiChat;
use App\Models\EmailTemplate;
use App\Models\Screen;
use App\Models\User;

class AiChatSeeder extends Seeder
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

            AiChat::factory(3)
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

            AiChat::factory(3)
                ->recycle($randomUser)
                ->recycle($template)
                ->create([
                    'commentable_id' => $template->id,
                    'commentable_type' => EmailTemplate::class,
                ]);
        }
    }
}
