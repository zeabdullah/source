<?php

namespace App\Services;

use App\Models\Project;
use GuzzleHttp\Client as HttpClient;

class FigmaService
{
    /**
     * @return array{string, string}
     */
    public function connectFigmaFile(Project $project, string $fileKey, string $ACCESS_TOKEN_DO_NOT_COMMIT): array
    {
        $client = new HttpClient([
            'base_uri' => 'https://api.figma.com/v1/',
            'headers' => [
                'X-Figma-Token' => $ACCESS_TOKEN_DO_NOT_COMMIT,
                'Accept' => 'application/json',
            ],
        ]);

        try {
            $response = $client->get("files/{$fileKey}/meta");

            $data = json_decode($response->getBody()->getContents(), true);
            $fileName = $data['file']['name'];

            $project->registerFigmaFile($fileKey, $fileName);

            return [$fileKey, $fileName];
        } catch (\Exception $e) {
            throw new \Exception('Failed to fetch Figma file name: ' . $e->getMessage());
        }
    }

    public function disconnectFigmaFile(Project $project)
    {
        return $project->unregisterFigmaFile();
    }
}

