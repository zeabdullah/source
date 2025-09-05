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
        $user = $request->user();
        $project = Project::find($projectId);

        $isOwner = $project?->owner_id === $user->id;
        $isMember = $user->memberProjects()->where('project_id', $projectId)->exists();

        if (!$project || !$isOwner || !$isMember) {
            // We'll show the same response to avoid info leakage
            return $this->notFoundResponse('Project not found');
        }

        $validated = $request->validate([
            'section_name' => 'nullable|string|max:255',
            'data' => 'required|array',
        ]);

        $screen = new Screen($validated);
        $screen->project_id = $projectId;

        $success = $screen->save();

        if (!$success) {
            return $this->serverErrorResponse(message: 'Failed to save Screen to database');
        }

        return $this->responseJson($screen->fresh(), 'Created successfully', 201);
    }

    public function exportScreens(Request $request, string $projectId)
    {
        $request->validate([
            'frame_ids' => 'required|array|min:1',
            'frame_ids.*' => 'string|distinct',
        ]);

    }

    public function getProjectScreens(Request $request, string $projectId)
    {
        $project = Project::find($projectId);
        if (!$project) {
            return $this->notFoundResponse('Project not found');
        }

        $screensQuery = $project->screens();
        if ($search = $request->query('search')) {
            $screensQuery = $screensQuery->semanticSearch($search);
        }

        $screens = $screensQuery->get();

        return $this->responseJson($screens);
    }

    public function updateScreenById(Request $request, string $screenId)
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
        } catch (\Throwable $e) {
            return $this->serverErrorResponse(message: 'Failed to update Screen: ' . $e->getMessage());
        }

        $screen->refresh();
        return $this->responseJson($screen, 'Updated successfully');
    }

    public function regenerateDescription(Request $request, string $screenId, ScreenService $screenService)
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

    public function deleteScreenById(Request $request, string $screenId)
    {
        $screen = Screen::find($screenId);

        if (!$screen) {
            return $this->notFoundResponse('Screen not found');
        }

        try {
            $screen->deleteOrFail();
        } catch (\Throwable $e) {
            return $this->serverErrorResponse(message: 'Failed to delete screen: ' . $e->getMessage());
        }

        return $this->responseJson($screen, 'Screen deleted');
    }
}
