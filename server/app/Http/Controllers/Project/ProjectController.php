<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\FigmaService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function createProject(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1500',
        ]);

        $project = new Project($validated);
        $project->owner_id = $request->user()->id;

        $success = $project->save();

        if (!$success) {
            return $this->serverErrorResponse(message: 'Failed to save Project to database');
        }

        return $this->responseJson($project->fresh(), 'Created successfully', 201);
    }

    public function getMyProjects(Request $request)
    {
        $ownedProjectsQuery = $request->user()->ownedProjects();

        if ($search = $request->query('search')) {
            $ownedProjectsQuery = $ownedProjectsQuery->whereLike('name', "%$search%");
        }

        return $this->responseJson($ownedProjectsQuery->get());
    }

    public function getProjectById(Request $request, string $projectId)
    {
        $project = Project::find($projectId);

        return $this->responseJson($project);
    }

    public function deleteProjectById(Request $request, string $projectId)
    {
        $project = Project::find($projectId);
        try {
            $project->deleteOrFail();
            return $this->responseJson($project, 'Project deleted');
        } catch (\Throwable $e) {
            return $this->serverErrorResponse(message: 'Failed to delete Project: ' . $e->getMessage());
        }
    }

    public function updateProjectById(Request $request, string $projectId)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1500',
        ]);

        $project = Project::find($projectId);

        try {
            $project->updateOrFail($validated);
        } catch (\Throwable $e) {
            return $this->serverErrorResponse(message: 'Failed to update Project: ' . $e->getMessage());
        }

        return $this->responseJson($project->fresh(), 'Updated successfully');
    }

}
