<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Screen;
use App\Services\FigmaService;
use App\Services\ScreenService;
use Illuminate\Http\Request;

class FigmaController extends Controller
{
    public function connectFile(Request $request, string $projectId)
    {
        $validated = $request->validate([
            'file_key' => 'required|string',
            'file_name' => 'nullable|string',
        ]);

        $project = Project::find($projectId);
        if (!$project) {
            return $this->notFoundResponse('Project not found');
        }
        if ($project->owner_id !== $request->user()->id) {
            return $this->forbiddenResponse('You do not own this project');
        }

        $project->connectFigmaFile($validated['file_key'], $validated['file_name'] ?? $validated['file_key']);

        return $this->responseJson($project->fresh(), 'Figma file connected');
    }

    public function disconnectFile(Request $request, string $projectId)
    {
        $project = Project::find($projectId);
        if (!$project) {
            return $this->notFoundResponse('Project not found');
        }
        if ($project->owner_id !== $request->user()->id) {
            return $this->forbiddenResponse('You do not own this project');
        }

        $project->disconnectFigmaFile();

        return $this->responseJson($project->fresh(), 'Figma file disconnected');
    }

    public function syncFile(Request $request, string $projectId, FigmaService $figmaService, ScreenService $screenService)
    {
        $project = Project::find($projectId);
        if (!$project) {
            return $this->notFoundResponse('Project not found');
        }
        if ($project->owner_id !== $request->user()->id) {
            return $this->forbiddenResponse('You do not own this project');
        }
        if (!$project->hasFigmaFile()) {
            return $this->badRequestResponse('No Figma file connected to this project');
        }

        $fileData = $figmaService->getFileData($project->figma_file_key);
        $frames = $figmaService->extractFrames($fileData);

        $createdOrUpdated = [];
        $now = now();

        // Process in chunks to limit memory usage and DB roundtrips
        foreach (array_chunk($frames, 500) as $frameChunk) {
            $nodeIds = array_values(array_filter(array_map(fn($f) => $f['id'] ?? null, $frameChunk)));
            if (empty($nodeIds)) {
                continue;
            }

            // Preload existing screens for this project and these node IDs (single query)
            $existingScreens = Screen::query()
                ->where('project_id', $project->id)
                ->whereIn('figma_node_id', $nodeIds)
                ->get(['id', 'figma_node_id', 'description'])
                ->keyBy('figma_node_id');

            $rows = [];
            foreach ($frameChunk as $frame) {
                $nodeId = $frame['id'] ?? null;
                if (!$nodeId) {
                    continue;
                }

                $existing = $existingScreens->get($nodeId);

                $proposedDescription = $existing && !empty($existing->description)
                    ? $existing->description
                    : $screenService->generateDescription(new Screen(['project_id' => $project->id]), $frame);

                $rows[] = [
                    'project_id' => $project->id,
                    'figma_node_id' => $nodeId,
                    'section_name' => $frame['name'] ?? null,
                    'data' => $frame,
                    'figma_url' => $figmaService->buildFrameUrl($project, $nodeId),
                    'description' => $proposedDescription,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            if (!empty($rows)) {
                // Single upsert for the chunk (unique by project_id + figma_node_id)
                Screen::upsert(
                    $rows,
                    ['project_id', 'figma_node_id'],
                    ['section_name', 'data', 'figma_url', 'description', 'updated_at']
                );

                // Re-fetch affected screens in one go to return fresh data
                $affected = Screen::query()
                    ->where('project_id', $project->id)
                    ->whereIn('figma_node_id', $nodeIds)
                    ->get();

                foreach ($affected as $scr) {
                    $createdOrUpdated[] = $scr;
                }
            }
        }

        $project->updateLastSynced();

        return $this->responseJson([
            'count' => count($createdOrUpdated),
            'screens' => $createdOrUpdated,
        ], 'Figma sync completed');
    }
}


