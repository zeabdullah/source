<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\FigmaService;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Projects",
 *     description="Project management endpoints"
 * )
 */
class ProjectController extends Controller
{
    /**
     * @OA\Post(
     *     path="/projects",
     *     summary="Create a new project",
     *     description="Create a new project for the authenticated user",
     *     tags={"Projects"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", maxLength=255, example="My New Project"),
     *             @OA\Property(property="description", type="string", maxLength=1500, nullable=true, example="Project description")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Project created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Project")
     *     ),
     *     @OA\Response(response=422, description="Validation errors"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/projects",
     *     summary="Get user's projects",
     *     description="Get all projects owned by the authenticated user",
     *     tags={"Projects"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search projects by name",
     *         required=false,
     *         @OA\Schema(type="string", example="my project")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of user's projects",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Project")
     *         )
     *     )
     * )
     */
    public function getMyProjects(Request $request)
    {
        $ownedProjectsQuery = $request->user()->ownedProjects();

        if ($search = $request->query('search')) {
            $ownedProjectsQuery = $ownedProjectsQuery->whereLike('name', "%$search%");
        }

        return $this->responseJson($ownedProjectsQuery->get());
    }

    /**
     * @OA\Get(
     *     path="/projects/{projectId}",
     *     summary="Get project by ID",
     *     description="Get a specific project by ID (owner only)",
     *     tags={"Projects"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="projectId",
     *         in="path",
     *         description="Project ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Project data",
     *         @OA\JsonContent(ref="#/components/schemas/Project")
     *     ),
     *     @OA\Response(response=404, description="Project not found")
     * )
     */
    public function getProjectById(Request $request, string $projectId)
    {
        $project = Project::find($projectId);

        return $this->responseJson($project);
    }

    /**
     * @OA\Delete(
     *     path="/projects/{projectId}",
     *     summary="Delete project",
     *     description="Delete a project by ID (owner only)",
     *     tags={"Projects"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="projectId",
     *         in="path",
     *         description="Project ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Project deleted successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Project")
     *     ),
     *     @OA\Response(response=404, description="Project not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/projects/{projectId}",
     *     summary="Update project",
     *     description="Update a project by ID (owner only)",
     *     tags={"Projects"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="projectId",
     *         in="path",
     *         description="Project ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", maxLength=255, example="Updated Project Name"),
     *             @OA\Property(property="description", type="string", maxLength=1500, nullable=true, example="Updated description")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Project updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Project")
     *     ),
     *     @OA\Response(response=404, description="Project not found"),
     *     @OA\Response(response=422, description="Validation errors"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
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
