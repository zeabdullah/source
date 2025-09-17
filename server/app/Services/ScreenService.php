<?php

namespace App\Services;

use App\Models\Screen;

class ScreenService
{
    /**
     * Generates a description of the given screen; used for semantic searching of screens
     * @return string
     */
    public function generateDescription(Screen $screen, array $frameNode): string
    {
        throw new \Exception('generateDescription not implemented');
    }
}


