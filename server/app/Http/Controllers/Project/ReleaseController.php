<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
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
     *             @OA\Property(property="name", type="string", example="Version 1.0"),
     *             @OA\Property(property="description", type="string", nullable=true, example="Initial release")
     *         )
     *     ),
     *     @OA\Response(
     *         response=501,
     *         description="Not implemented"
     *     )
     * )
     */
    public function createRelease(Request $request, string $projectId)
    {
        return $this->notImplementedResponse();
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
     *         response=501,
     *         description="Not implemented"
     *     )
     * )
     */
    public function getProjectReleases(Request $request, string $projectId)
    {
        return $this->notImplementedResponse();
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
     *         response=501,
     *         description="Not implemented"
     *     )
     * )
     */
    public function getReleaseById(Request $request, string $releaseId)
    {
        return $this->notImplementedResponse();
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
     *             @OA\Property(property="name", type="string", example="Updated Release Name"),
     *             @OA\Property(property="description", type="string", nullable=true, example="Updated description"),
     *             @OA\Property(property="status", type="string", enum={"draft", "published", "archived"}, example="published")
     *         )
     *     ),
     *     @OA\Response(
     *         response=501,
     *         description="Not implemented"
     *     )
     * )
     */
    public function updateReleaseById(Request $request, string $releaseId)
    {
        return $this->notImplementedResponse();
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
     *         response=501,
     *         description="Not implemented"
     *     )
     * )
     */
    public function deleteReleaseById(Request $request, string $releaseId)
    {
        return $this->notImplementedResponse();
    }
}
