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

    public function generateThumbnailFromHtml(string $html)
    {
        $response = Http::post(
            env('N8N_URL') . '/webhook/ENDPOINT',
            ['html' => $html]
        )->json();

        return $response;
    }
}
