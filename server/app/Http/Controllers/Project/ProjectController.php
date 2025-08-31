<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
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
        $me = $request->user();
        $search = $request->query('search');

        $ownedProjects = $me->ownedProjects();
        if ($search) {
            $ownedProjects = $ownedProjects->where('name', 'like', "%$search%");
        }

        return $this->responseJson($ownedProjects->get());
    }

    public function getProjectById(Request $request, string $projectId)
    {
        $project = Project::find($projectId);

        if (!$project) {
            return $this->notFoundResponse('Project not found');
        }
        return $this->responseJson($project);
    }

    public function deleteProjectById(Request $request, string $projectId)
    {
        $project = Project::find($projectId);

        if (!$project) {
            return $this->notFoundResponse('Project not found');
        }
        if ($project->owner_id !== $request->user()->id) {
            return $this->notFoundResponse('Project not found');
        }

        try {
            $project->deleteOrFail();
        } catch (\Throwable $e) {
            return $this->serverErrorResponse(message: 'Failed to delete Project: ' . $e->getMessage());
        }

        return $this->responseJson($project, 'Project deleted');
    }


    public function updateProjectById(Request $request, string $projectId)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1500',
        ]);

        $project = Project::find($projectId);

        if (!$project) {
            return $this->notFoundResponse('Project not found');
        }
        if ($project->owner_id !== $request->user()->id) {
            return $this->notFoundResponse('Project not found');
        }

        try {
            $project->updateOrFail($validated);
        } catch (\Throwable $e) {
            return $this->serverErrorResponse(message: 'Failed to update Project: ' . $e->getMessage());
        }

        $project->refresh();
        return $this->responseJson($project, 'Updated successfully');
    }
}
