<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Screen;
use App\Services\FigmaService;
use App\Services\ScreenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScreenController extends Controller
{
    /** @deprecated Use `exportScreens` instead */
    public function createScreen(Request $request, string $projectId): JsonResponse
    {
        /** @var \App\Models\Project */
        $project = $request->attributes->get('project');

        $isMember = $project->members()->where('user_id', $request->user()->id)->exists();
        if (!$isMember) {
            return $this->notFoundResponse('Project not found');
        }

        $validated = $request->validate([
            'section_name' => 'nullable|string|max:255',
            'data' => 'nullable|array',
        ]);

        try {
            $screen = Screen::create(array_merge($validated, ['project_id' => $projectId]));
            return $this->responseJson($screen, 'Created successfully', 201);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to save Screen to database: ' . $th->getMessage());
        }
    }

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
    public function getScreenByIdBasic(Request $request, string $screenId): JsonResponse
    {
        return $this->getScreenById($request, '', $screenId);
    }

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

    public function regenerateDescription(Request $request, string $projectId, string $screenId, ScreenService $screenService): JsonResponse
    {
        return $this->notImplementedResponse();
    }
}
