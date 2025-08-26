<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function createProject(Request $request)
    {
        return $this->responseJson([
            [
                'id' => 2,
                'name' => 'Demo Project',
                'description' => 'A sample project for demonstration.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ], 'Created successfully', 201);
    }

    public function getMyProjects(Request $request)
    {
        return $this->responseJson([
            [
                'id' => 1,
                'name' => 'Demo Project',
                'description' => 'A sample project for demonstration.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    public function getProjectById(Request $request, string $projectId)
    {
        return $this->notImplementedResponse();
    }

    public function deleteProjectById(Request $request, string $projectId)
    {
        return $this->notImplementedResponse();
    }


    public function updateProjectById(Request $request, string $projectId)
    {
        return $this->notImplementedResponse();
    }
}
