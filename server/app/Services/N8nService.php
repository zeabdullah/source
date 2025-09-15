<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class N8nService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function generateBase64ThumbnailFromHtml(string $html): string
    {
        $username = env('N8N_WEBHOOKS_BASIC_USERNAME');
        $password = env('N8N_WEBHOOKS_BASIC_PASSWORD');
        $url = env('N8N_URL') . '/webhook/generate-thumbnail-from-html';

        $response = Http::withBasicAuth($username, $password)
            ->post($url, ['html' => $html])
            ->json();

        return $response['image'];
    }
}
