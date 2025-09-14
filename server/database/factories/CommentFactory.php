<?php

namespace Database\Factories;

use App\Models\EmailTemplate;
use App\Models\Screen;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $commentable = $this->faker->randomElement([Screen::class, EmailTemplate::class]);
        return [
            'user_id' => User::factory(),
            'content' => $this->faker->paragraph(),
            'commentable_id' => $commentable::factory(),
            'commentable_type' => $commentable,
        ];
    }
}
