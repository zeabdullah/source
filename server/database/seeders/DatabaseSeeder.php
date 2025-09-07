<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class
        ]);

        $users = User::all()->except([1]);
        $projects = Project::factory(10)
            ->recycle($users)
            ->create();

        // Attach users to projects through many-to-many relationship
        foreach ($projects as $project) {
            // Attach random number of users to each project (excluding the owner)
            $randomUsers = $users->where('id', '!=', $project->owner_id)->random(rand(1, 3));
            $project->members()->attach($randomUsers);
        }
    }
}
