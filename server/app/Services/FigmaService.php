<?php

namespace App\Services;

use App\Models\Project;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Facades\Log;

class FigmaService
{
    /**
     * @return array{string, string}
     */
    public function connectFigmaFile(Project $project, string $fileKey, string $figmaAccessToken): array
    {
        $client = new HttpClient([
            'base_uri' => 'https://api.figma.com/v1/',
            'headers' => [
                'X-Figma-Token' => $figmaAccessToken,
                'Accept' => 'application/json',
            ],
        ]);

        try {
            $response = $client->get("files/{$fileKey}/meta");

            $data = json_decode($response->getBody()->getContents(), associative: true);
            $fileName = $data['file']['name'];

            $project->registerFigmaFile($fileKey, $fileName);

            return [$fileKey, $fileName];
        } catch (\Exception $e) {
            throw new \Exception('Failed to fetch Figma file name: ' . $e->getMessage());
        }
    }

    /**
     * @param string[] $figmaNodeIds
     * @throws \Exception
     * @return array<string, string> array of Frame ID keys, URL values
     */
    public function getSvgUrlsForNodes(array $figmaNodeIds, string $fileKey, string $figmaAccessToken)
    {
        $client = new HttpClient([
            'base_uri' => 'https://api.figma.com/v1/',
            'headers' => [
                'X-Figma-Token' => $figmaAccessToken,
                'Accept' => 'application/json',
            ],
        ]);

        try {
            $response = $client->get("images/{$fileKey}", [
                'query' => [
                    'ids' => implode(',', $figmaNodeIds),
                    'format' => 'svg',
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), associative: true);

            return $data['images'];
        } catch (\Exception $e) {
            throw new \Exception('Failed to fetch Figma file name: ' . $e->getMessage());
        }
    }

    public function getFigmaFrameForAI(string $nodeId, string $fileKey, $figmaAccessToken)
    {
        $client = new HttpClient([
            'base_uri' => 'https://api.figma.com/v1/',
            'headers' => [
                'X-Figma-Token' => $figmaAccessToken,
                'Accept' => 'application/json',
            ],
        ]);

        try {
            $response = $client->get("files/{$fileKey}/nodes", [
                'query' => [
                    'ids' => $nodeId,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), associative: true);

            if (empty($data['nodes'][$nodeId]['document'])) {
                return null;
            }

            return $data['nodes'][$nodeId]['document'];
        } catch (\Exception $e) {
            Log::error('Failed to fetch Figma frame for AI: ' . $e->getMessage(), ['exception' => $e]);
            return null;
        }
    }

    /**
     * Validate if a Figma node ID exists and fetch its data
     * @return array|null Returns node data if valid, null if invalid
     */
    public function validateAndGetNodeData(string $nodeId, string $fileKey, string $figmaAccessToken, int $depth = 1): ?array
    {
        $client = new HttpClient([
            'base_uri' => 'https://api.figma.com/v1/',
            'headers' => [
                'X-Figma-Token' => $figmaAccessToken,
                'Accept' => 'application/json',
            ],
        ]);

        try {
            $response = $client->get("files/{$fileKey}/nodes", [
                'query' => [
                    'ids' => $nodeId,
                    'depth' => $depth,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), associative: true);

            if (empty($data['nodes'][$nodeId]['document'])) {
                return null;
            }

            $document = $data['nodes'][$nodeId]['document'];
            $nodeName = $document['name'];

            return [
                'document' => $document,
                'name' => $nodeName,
            ];
        } catch (\Exception $e) {
            Log::error('Failed to validate Figma node: ' . $e->getMessage(), [
                'node_id' => $nodeId,
                'file_key' => $fileKey,
                'exception' => $e
            ]);
            return null;
        }
    }
}

