<?php

namespace Database\Factories;

use App\Models\Screen;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AiChat>
 */
class AiChatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sender = $this->faker->randomElement(['user', 'ai']);

        return [
            'user_id' => $sender === 'ai' ? null : User::factory(),
            'commentable_id' => Screen::factory(),
            'commentable_type' => Screen::class,
            'sender' => $sender,
            'content' => $this->faker->paragraph(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
