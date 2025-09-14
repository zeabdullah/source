<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;
use App\Models\Project;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::all();

        foreach ($projects as $project) {
            EmailTemplate::factory(3)
                ->recycle($project)
                ->create();
        }
    }
}
