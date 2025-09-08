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
        // TODO: implement properly

        $provider = config('ai.provider');


        // Placeholder simple heuristic until provider is enabled
        $name = $frameNode['name'] ?? 'Untitled';
        $width = $frameNode['absoluteBoundingBox']['width'] ?? null;
        $height = $frameNode['absoluteBoundingBox']['height'] ?? null;
        $size = $width && $height ? " ({$width}x{$height})" : '';

        return "Frame '{$name}'{$size} imported from Figma.";
    }
}


