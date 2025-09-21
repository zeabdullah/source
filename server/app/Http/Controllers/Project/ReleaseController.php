<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Release;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Releases",
 *     description="Release management endpoints for projects"
 * )
 */
class ReleaseController extends Controller
{
    /**
     * @OA\Post(
     *     path="/projects/{projectId}/releases",
     *     summary="Create release",
     *     description="Create a new release for a project",
     *     tags={"Releases"},
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
     *             @OA\Property(property="version", type="string", maxLength=255, example="1.0.0"),
     *             @OA\Property(property="description", type="string", maxLength=1500, nullable=true, example="Initial release with core features"),
     *             @OA\Property(property="tags", type="string", maxLength=500, nullable=true, example="stable production"),
     *             @OA\Property(property="screen_ids", type="array", nullable=true, @OA\Items(type="integer"), example={1, 2, 3}),
     *             @OA\Property(property="email_template_ids", type="array", nullable=true, @OA\Items(type="integer"), example={1, 2})
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Release created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Release")
     *     ),
     *     @OA\Response(response=404, description="Project not found"),
     *     @OA\Response(response=422, description="Validation errors"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function createRelease(Request $request, string $projectId)
    {
        $validated = $request->validate([
            'version' => 'required|string|max:255',
            'description' => 'nullable|string|max:1500',
            'tags' => 'nullable|string|max:500',
            'screen_ids' => 'nullable|array',
            'screen_ids.*' => 'exists:screens,id',
            'email_template_ids' => 'nullable|array',
            'email_template_ids.*' => 'exists:email_templates,id',
        ]);


        try {
            $project = Project::find($projectId);
            if (!$project) {
                return $this->notFoundResponse('Project not found');
            }

            $release = new Release($validated);
            $release->project_id = $project->id;

            $release->saveOrFail();

            // Attach screens and email templates
            if (!empty($validated['screen_ids'])) {
                $release->screens()->attach($validated['screen_ids']);
            }
            if (!empty($validated['email_template_ids'])) {
                $release->emailTemplates()->attach($validated['email_template_ids']);
            }

            return $this->responseJson(
                $release->fresh(),
                'Created successfully',
                201
            );
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to create Release: ' . $th->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/projects/{projectId}/releases",
     *     summary="Get project releases",
     *     description="Get all releases for a project",
     *     tags={"Releases"},
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
     *         description="List of project releases",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Release")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Project not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function getProjectReleases(Request $request, string $projectId)
    {
        try {
            $project = Project::find($projectId);
            if (!$project) {
                return $this->notFoundResponse('Project not found');
            }

            $releases = $project->releases()
                ->orderBy('created_at', 'desc')
                ->get();

            return $this->responseJson($releases);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to get project releases: ' . $th->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/releases/{releaseId}",
     *     summary="Get release by ID",
     *     description="Get a specific release by its ID",
     *     tags={"Releases"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="releaseId",
     *         in="path",
     *         description="Release ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Release data with relationships",
     *         @OA\JsonContent(ref="#/components/schemas/Release")
     *     ),
     *     @OA\Response(response=404, description="Release not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function getReleaseById(Request $request, string $releaseId)
    {
        try {
            $release = Release::with(['project'])
                ->find($releaseId);
            if (!$release) {
                return $this->notFoundResponse('Release not found');
            }

            return $this->responseJson($release);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to get release: ' . $th->getMessage());
        }
    }

    /**
     * @OA\Put(
     *     path="/releases/{releaseId}",
     *     summary="Update release",
     *     description="Update a release by its ID",
     *     tags={"Releases"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="releaseId",
     *         in="path",
     *         description="Release ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="version", type="string", maxLength=255, example="1.1.0"),
     *             @OA\Property(property="description", type="string", maxLength=2500, nullable=true, example="Updated release with bug fixes"),
     *             @OA\Property(property="tags", type="array", nullable=true, @OA\Items(type="string"), example={"hotfix", "production"}),
     *             @OA\Property(property="screen_ids", type="array", nullable=true, @OA\Items(type="integer"), example={1, 3, 5}),
     *             @OA\Property(property="email_template_ids", type="array", nullable=true, @OA\Items(type="integer"), example={2, 4})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Release updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Release")
     *     ),
     *     @OA\Response(response=404, description="Release not found"),
     *     @OA\Response(response=422, description="Validation errors"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function updateReleaseById(Request $request, string $releaseId)
    {
        $validated = $request->validate([
            'version' => 'required|string|max:255',
            'description' => 'nullable|string|max:2500',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:255',
            'screen_ids' => 'nullable|array',
            'screen_ids.*' => 'exists:screens,id',
            'email_template_ids' => 'nullable|array',
            'email_template_ids.*' => 'exists:email_templates,id',
        ]);

        try {
            $release = Release::find($releaseId);
            if (!$release) {
                return $this->notFoundResponse('Release not found');
            }

            $release->updateOrFail(collect($validated)->except(['screen_ids', 'email_template_ids'])->toArray());

            // Update relationships if provided
            if (isset($validated['screen_ids'])) {
                $release->screens()->sync($validated['screen_ids']);
            }
            if (isset($validated['email_template_ids'])) {
                $release->emailTemplates()->sync($validated['email_template_ids']);
            }

            return $this->responseJson($release->fresh(), 'Updated successfully');
        } catch (\Throwable $e) {
            return $this->serverErrorResponse(message: 'Failed to update Release: ' . $e->getMessage());
        }

    }

    /**
     * @OA\Delete(
     *     path="/releases/{releaseId}",
     *     summary="Delete release",
     *     description="Delete a release by its ID",
     *     tags={"Releases"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="releaseId",
     *         in="path",
     *         description="Release ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Release deleted successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Release")
     *     ),
     *     @OA\Response(response=404, description="Release not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function deleteReleaseById(Request $request, string $releaseId)
    {

        try {
            $release = Release::find($releaseId);
            if (!$release) {
                return $this->notFoundResponse('Release not found');
            }

            $release->deleteOrFail();

            return $this->responseJson($release, 'Release deleted');
        } catch (\Throwable $e) {
            return $this->serverErrorResponse(message: 'Failed to delete Release: ' . $e->getMessage());
        }
    }
}
