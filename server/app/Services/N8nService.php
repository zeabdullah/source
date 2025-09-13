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

    public function generateThumbnail(string $html)
    {
        $response = Http::post(
            'http://localhost:5678/webhook/674b0267-0048-4503-89fb-72dc67fced59',
            ['html' => $html]
        )->json();

        return $response;
    }
}
