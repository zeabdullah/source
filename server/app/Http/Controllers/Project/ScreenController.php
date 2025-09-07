<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Screen;
use App\Services\FigmaService;
use App\Services\ScreenService;
use Illuminate\Http\Request;

class ScreenController extends Controller
{
    public function createScreen(Request $request, string $projectId)
    {

        $project = Project::find($projectId);
        if (!$project) {
            return $this->notFoundResponse('Project not found');
        }

        $user = $request->user();

        $isOwner = $project?->owner_id === $user->id;
        $isMember = $user->memberProjects()->where('project_id', $projectId)->exists();
        if (!($isOwner || $isMember)) {
            // We'll show the same response to avoid info leakage
            return $this->notFoundResponse('Project not found');
        }

        $validated = $request->validate([
            'section_name' => 'nullable|string|max:255',
        ]);

        try {
            $screen = Screen::create(array_merge($validated, ['project_id' => $projectId]));
            return $this->responseJson($screen, 'Created successfully', 201);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to save Screen to database: ' . $th->getMessage());
        }
    }

    public function exportScreens(Request $request, string $projectId, FigmaService $figmaService)
    {
        $validated = $request->validate([
            'frame_ids' => 'required|array|min:1',
            'frame_ids.*' => 'string|distinct|unique:screens,figma_node_id',
            'figma_access_token' => 'required|string',
        ]);
        /** @var string[] */
        $frameIds = $validated['frame_ids'];

        $project = Project::find($projectId);

        if (!$project->figma_file_key) {
            return $this->forbiddenResponse('You must connect your project to a Figma file first');
        }

        $svgUrls = [];

        try {
            $svgUrls = $figmaService->getSvgUrlsForNodes(
                $frameIds,
                $project->figma_file_key,
                $validated['figma_access_token'],
            );
            $createdScreens = collect($frameIds)->map(function ($frameId) use ($projectId, $svgUrls) {
                $data = [
                    'project_id' => $projectId,
                    'figma_node_id' => $frameId,
                ];
                if (isset($svgUrls[$frameId])) {
                    $data['figma_svg_url'] = $svgUrls[$frameId];
                }
                return Screen::create($data);
            });
            return $this->responseJson($createdScreens, 'Frames exported as screens successfully', 201);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: $th->getMessage());
        }
    }

    public function getProjectScreens(Request $request, string $projectId, FigmaService $figmaService)
    {
        $validated = $request->validate([
            'figma_access_token' => 'required|string'
        ]);

        $project = Project::find($projectId);

        $screensQuery = $project->screens();
        if ($search = $request->query('search')) {
            $screensQuery = $screensQuery->semanticSearch($search);
        }

        $screens = $screensQuery->get();

        return $this->responseJson($screens);
    }

    public function updateScreenById(Request $request, string $projectId, string $screenId)
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

    public function deleteScreenById(Request $request, string $projectId, string $screenId)
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

    public function regenerateDescription(Request $request, string $projectId, string $screenId, ScreenService $screenService)
    {
        $this->notImplementedResponse();
    }
}
