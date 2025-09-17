<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $primaryUser = User::find(1);
        $users = User::all()->except([1]);

        $projects = Project::factory(10)
            ->recycle($users)
            ->create();

        // A project for the primary user
        Project::factory()->recycle($primaryUser)->create([
            'name' => 'Acme App',
            'description' => 'This project belongs to Acme Inc., the best company and product in the world.',
        ]);

        // Attach users to projects through many-to-many relationship
        foreach ($projects as $project) {
            // Attach random number of users to each project (excluding the owner)
            $randomUsers = $users->where('id', '!=', $project->owner_id)->random(rand(1, 3));
            $project->members()->attach($randomUsers);
        }
    }
}
