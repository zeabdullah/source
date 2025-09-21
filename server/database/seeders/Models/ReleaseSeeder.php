<?php

namespace Database\Seeders\Models;

use App\Models\Project;
use App\Models\Release;
use Illuminate\Database\Seeder;

class ReleaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::all();

        foreach ($projects as $project) {
            $releases = Release::factory(rand(1, 3))
                ->recycle($project)
                ->create([
                    'project_id' => $project->id,
                ]);

            // Attach some screens and email templates to releases
            foreach ($releases as $release) {
                $projectScreens = $project->screens;
                $projectEmailTemplates = $project->emailTemplates;

                if ($projectScreens->isNotEmpty()) {
                    $release->screens()->attach(
                        $projectScreens->random(min(rand(1, 3), $projectScreens->count()))
                    );
                }

                if ($projectEmailTemplates->isNotEmpty()) {
                    $release->emailTemplates()->attach(
                        $projectEmailTemplates->random(min(rand(1, 2), $projectEmailTemplates->count()))
                    );
                }
            }
        }
    }
}
