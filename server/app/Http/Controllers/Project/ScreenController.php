<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Screen;
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
            'data' => 'required|array',
        ]);

        try {
            $screen = Screen::create(array_merge($validated, ['project_id' => $projectId]));
            return $this->responseJson($screen, 'Created successfully', 201);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to save Screen to database: ' . $th->getMessage());
        }
    }

    public function exportScreens(Request $request, string $projectId)
    {
        $request->validate([
            'frame_ids' => 'required|array|min:1',
            'frame_ids.*' => 'string|distinct|unique:screens,figma_node_id',
        ]);

        $frameIds = $request->input('frame_ids');

        $createdScreens = collect($frameIds)->map(function ($frameId) use ($projectId) {
            return Screen::create([
                'project_id' => $projectId,
                'figma_node_id' => $frameId,
                'data' => [],
            ]);
        });

        return $this->responseJson($createdScreens, 'Frames exported as screens successfully', 201);
    }

    public function getProjectScreens(Request $request, string $projectId)
    {
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
            'data' => 'nullable|array',
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
        $screen = Screen::find($screenId);
        if (!$screen) {
            return $this->notFoundResponse('Screen not found');
        }

        $frameNode = $screen->data ?? [];
        $screen->description = $screenService->generateDescription($screen, $frameNode);
        $screen->save();

        return $this->responseJson($screen->fresh(), 'Description regenerated');
    }
}
