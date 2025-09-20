<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Screen;
use App\Services\FigmaService;
use App\Services\ScreenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Screens",
 *     description="Screen management endpoints for projects"
 * )
 */
class ScreenController extends Controller
{
    /**
     * @OA\Post(
     *     path="/projects/{projectId}/screens/export",
     *     summary="Export screens from Figma",
     *     description="Export multiple Figma frames as screens for a project",
     *     tags={"Screens"},
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
     *             @OA\Property(property="frame_ids", type="array", @OA\Items(type="string"), example={"1:23", "1:24"}),
     *             @OA\Property(property="figma_access_token", type="string", example="figd_abc123..."),
     *             @OA\Property(property="figma_file_key", type="string", example="abc123def456")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Screens exported successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Screen")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation errors"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function exportScreens(Request $request, string $projectId, FigmaService $figmaService): JsonResponse
    {
        $validated = $request->validate([
            'frame_ids' => 'required|array|min:1',
            'frame_ids.*' => 'string|distinct|unique:screens,figma_node_id',
            'figma_access_token' => 'required|string',
            'figma_file_key' => 'required|string',
        ]);
        /** @var string[] */
        $frameIds = $validated['frame_ids'];
        /** @var string[] */
        $fileKey = $validated['figma_file_key'];

        try {
            $svgUrls = $figmaService->getSvgUrlsForNodes(
                $frameIds,
                $fileKey,
                $validated['figma_access_token'],
            );

            // Map over the frame IDs and create a new screen for each one
            $createdScreens = collect($frameIds)->map(function ($frameId) use ($projectId, $fileKey, $svgUrls, $validated) {
                $data = [
                    'project_id' => $projectId,
                    'figma_node_id' => $frameId,
                    'figma_file_key' => $fileKey,
                ];
                if (isset($svgUrls[$frameId])) {
                    $data['figma_svg_url'] = $svgUrls[$frameId];
                }

                $screen = Screen::create($data);

                // Sync Figma data to populate figma_node_name
                $screen->syncFigmaDataWithToken($validated['figma_access_token']);

                return $screen;
            });
            return $this->responseJson($createdScreens, 'Frames exported as screens successfully', 201);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: "Failed to export screens from Figma: " . $th->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/projects/{projectId}/screens",
     *     summary="Get project screens",
     *     description="Get all screens for a project with optional search",
     *     tags={"Screens"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="projectId",
     *         in="path",
     *         description="Project ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search screens by name or description",
     *         required=false,
     *         @OA\Schema(type="string", example="login")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of project screens",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Screen")
     *         )
     *     ),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function getProjectScreens(Request $request, string $projectId): JsonResponse
    {
        $project = $request->attributes->get('project');

        $screensQuery = $project->screens();
        if ($search = $request->query('search')) {
            $screensQuery = $screensQuery->semanticSearch($search);
        }

        $screens = $screensQuery->get();

        return $this->responseJson($screens);
    }

    /**
     * @OA\Get(
     *     path="/projects/{projectId}/screens/{screenId}",
     *     summary="Get screen by ID",
     *     description="Get a specific screen by its ID",
     *     tags={"Screens"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="projectId",
     *         in="path",
     *         description="Project ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Parameter(
     *         name="screenId",
     *         in="path",
     *         description="Screen ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Screen data",
     *         @OA\JsonContent(ref="#/components/schemas/Screen")
     *     ),
     *     @OA\Response(response=404, description="Screen not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function getScreenById(Request $request, string $projectId, string $screenId): JsonResponse
    {
        try {
            $screen = Screen::find($screenId);
            if (!$screen) {
                return $this->notFoundResponse('Screen not found');
            }

            return $this->responseJson($screen);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to get screen: ' . $th->getMessage());
        }
    }

    /**
     * @OA\Put(
     *     path="/projects/{projectId}/screens/{screenId}",
     *     summary="Update screen",
     *     description="Update a screen by its ID",
     *     tags={"Screens"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="projectId",
     *         in="path",
     *         description="Project ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Parameter(
     *         name="screenId",
     *         in="path",
     *         description="Screen ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="section_name", type="string", nullable=true, example="Updated Screen Name")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Screen updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Screen")
     *     ),
     *     @OA\Response(response=404, description="Screen not found"),
     *     @OA\Response(response=422, description="Validation errors"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function updateScreenById(Request $request, string $projectId, string $screenId): JsonResponse
    {
        $validated = $request->validate([
            'section_name' => 'nullable|string|max:255',
        ]);

        $screen = Screen::find($screenId);

        if (!$screen) {
            return $this->notFoundResponse('Screen not found');
        }

        try {
            $screen->updateOrFail($validated);
            $screen->refresh();
            return $this->responseJson($screen, 'Updated successfully');
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to update Screen: ' . $th->getMessage());
        }

    }

    /**
     * @OA\Delete(
     *     path="/projects/{projectId}/screens/{screenId}",
     *     summary="Delete screen",
     *     description="Delete a screen by its ID",
     *     tags={"Screens"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="projectId",
     *         in="path",
     *         description="Project ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Parameter(
     *         name="screenId",
     *         in="path",
     *         description="Screen ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Screen deleted successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Screen")
     *     ),
     *     @OA\Response(response=404, description="Screen not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function deleteScreenById(Request $request, string $projectId, string $screenId): JsonResponse
    {
        $screen = Screen::find($screenId);

        if (!$screen) {
            return $this->notFoundResponse('Screen not found');
        }

        try {
            $screen->deleteOrFail();
            return $this->responseJson($screen, 'Screen deleted');
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to delete screen: ' . $th->getMessage());
        }

    }
}
