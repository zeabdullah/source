<?php

namespace App\Jobs;

use App\Models\Screen;
use App\Models\User;
use App\Services\FigmaService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncScreenFigmaData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Starting Figma screen data sync job');

        // Get all screens with valid figma_node_id and figma_file_key
        $screens = Screen::whereNotNull('figma_node_id')
            ->whereNotNull('figma_file_key')
            ->with(['project.owner'])
            ->get();

        $figmaService = new FigmaService();
        $syncedCount = 0;
        $errorCount = 0;

        foreach ($screens as $screen) {
            try {
                // Get the project owner's Figma access token
                $owner = $screen->project->owner;
                if (!$owner || !$owner->figma_access_token) {
                    Log::warning("No Figma access token for screen {$screen->id}, skipping");
                    continue;
                }

                // Validate node ID and get data
                $nodeData = $figmaService->validateAndGetNodeData(
                    $screen->figma_node_id,
                    $screen->figma_file_key,
                    $owner->figma_access_token
                );

                if ($nodeData) {
                    // Update screen data with Figma node data
                    $screen->update(['data' => $nodeData]);
                    $syncedCount++;
                    Log::debug("Synced Figma data for screen {$screen->id}");
                } else {
                    Log::warning("Invalid Figma node ID for screen {$screen->id}: {$screen->figma_node_id}");
                    $errorCount++;
                }
            } catch (\Exception $e) {
                Log::error("Failed to sync Figma data for screen {$screen->id}: " . $e->getMessage(), [
                    'screen_id' => $screen->id,
                    'figma_node_id' => $screen->figma_node_id,
                    'figma_file_key' => $screen->figma_file_key,
                    'exception' => $e
                ]);
                $errorCount++;
            }
        }

        Log::info("Figma screen data sync completed. Synced: {$syncedCount}, Errors: {$errorCount}");
    }
}
