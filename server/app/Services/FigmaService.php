<?php

/**
 * Services like this module will be talking called from a controller,
 * so they won't be returning any HTTP responses.
 */

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FigmaService
{
    public function getFileData(string $fileKey): array
    {
        $response = Http::withToken(config('figma.token'))
            ->baseUrl(config('figma.base_url'))
            ->get("/files/{$fileKey}");

        if ($response->failed()) {
            Log::warning('Figma getFileData failed', [
                'status' => $response->status(),
                'body' => $response->json(),
            ]);
            throw new \Exception('Failed to fetch Figma file data');
        }

        return $response->json() ?? [];
    }

    public function extractFrames(array $fileData): array
    {
        $frames = [];

        if (!isset($fileData['document']['children'])) {
            return $frames;
        }

        $queue = $fileData['document']['children'];
        while (!empty($queue)) {
            $node = array_shift($queue);
            if (($node['type'] ?? null) === 'FRAME') {
                $frames[] = $node;
            }
            if (!empty($node['children'])) {
                foreach ($node['children'] as $child) {
                    $queue[] = $child;
                }
            }
        }

        return $frames;
    }

    public function getFrameImageUrl(string $fileKey, array $frameNode): ?string
    {
        $nodeId = $frameNode['id'] ?? null;
        if (!$nodeId) {
            return null;
        }

        $response = Http::withToken(config('figma.token'))
            ->baseUrl(config('figma.base_url'))
            ->get('/images/' . $fileKey, [
                'ids' => $nodeId,
                'format' => 'png',
                'scale' => 2,
            ]);

        if ($response->failed()) {
            Log::warning('Figma getFrameImageUrl failed', [
                'status' => $response->status(),
                'body' => $response->json(),
            ]);
            return null;
        }

        $images = $response->json()['images'] ?? [];
        return $images[$nodeId] ?? null;
    }

    public function buildFrameUrl(Project $project, string $nodeId): ?string
    {
        if (!$project->figma_file_key) {
            return null;
        }

        return "https://www.figma.com/file/{$project->figma_file_key}/?node-id={$nodeId}";
    }
}

