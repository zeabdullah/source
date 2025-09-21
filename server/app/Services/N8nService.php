<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class N8nService
{
    private string $username;
    private string $password;

    public function __construct()
    {
        if (!env('N8N_WEBHOOKS_BASIC_USERNAME') || !env('N8N_WEBHOOKS_BASIC_PASSWORD')) {
            throw new \Exception(
                'N8N_WEBHOOKS_BASIC_USERNAME and N8N_WEBHOOKS_BASIC_PASSWORD are not set. Please set them to use this service.'
            );
        }
        $this->username = env('N8N_WEBHOOKS_BASIC_USERNAME');
        $this->password = env('N8N_WEBHOOKS_BASIC_PASSWORD');
    }

    public function generateBase64ThumbnailFromHtml(string $html): string
    {
        $url = env('N8N_URL') . '/webhook/generate-thumbnail-from-html';

        $response = Http::withBasicAuth($this->username, $this->password)
            ->post($url, ['html' => $html])
            ->json();

        return $response['image'];
    }
}
