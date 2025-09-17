<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmailTemplate>
 */
class EmailTemplateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'section_name' => $this->faker->optional()->word(),
            'campaign_id' => $this->faker->uuid(),
            'thumbnail_url' => $this->faker->optional(0.7)->passthrough('https://picsum.photos/400/300?random=' . $this->faker->randomNumber()),
        ];
    }
}
