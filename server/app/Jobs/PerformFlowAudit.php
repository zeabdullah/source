<?php

namespace App\Jobs;

use App\Models\Audit;
use App\Services\AiAgentService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class PerformFlowAudit implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $timeout = 300; // 5 minutes

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Audit $audit
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(AiAgentService $aiAgentService): void
    {
        try {
            Log::info("Starting flow audit for audit ID: {$this->audit->id}");

            // Load screens with their data
            $this->audit->load([
                'screens' => function ($query) {
                    $query->orderBy('audit_screens.sequence_order');
                }
            ]);

            if ($this->audit->screens->isEmpty()) {
                throw new \Exception('No screens found for audit');
            }

            // Serialize all screen data for AI analysis
            $serializedScreens = [];
            foreach ($this->audit->screens as $screen) {
                $serializedScreens[] = $screen->serializeForAudit(); // TODO: revise. not needed.
            }

            // TODO: Call AI service for multi-screen consistency analysis
            // $results = $aiAgentService->analyzeFlowConsistency($serializedScreens, $this->audit->name);

            // For now, create a mock response
            $results = $this->createMockResults();

            // Update audit with results
            $this->audit->update([
                'status' => 'completed',
                'results' => $results,
                'overall_score' => $results['overallConsistencyScore'] ?? null,
            ]);

            Log::info("Flow audit completed for audit ID: {$this->audit->id}");

        } catch (\Exception $e) {
            Log::error("Flow audit failed for audit ID: {$this->audit->id}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->audit->update(['status' => 'failed']);

            throw $e;
        }
    }

    /**
     * Create mock results for testing
     * TODO: Remove this when AI service is implemented
     */
    private function createMockResults(): array
    {
        return [
            'auditId' => $this->audit->id,
            'flowName' => $this->audit->name,
            'overallConsistencyScore' => 7.2,
            'issues' => [
                [
                    'type' => 'terminology',
                    'severity' => 'medium',
                    'description' => 'Inconsistent button text across screens',
                    'screens' => ['Screen 1', 'Screen 2'],
                    'suggestion' => 'Standardize button text to "Continue" across all screens'
                ],
                [
                    'type' => 'color_usage',
                    'severity' => 'high',
                    'description' => 'Primary action button color differs between screens',
                    'screens' => ['Screen 1', 'Screen 3'],
                    'suggestion' => 'Use consistent primary color (#007bff) for all primary actions'
                ]
            ],
            'positiveFindings' => [
                'Consistent typography hierarchy across all screens',
                'Good use of spacing and padding consistency',
                'Clear visual progression through the flow'
            ]
        ];
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("Flow audit job failed permanently for audit ID: {$this->audit->id}", [
            'error' => $exception->getMessage()
        ]);

        $this->audit->update(['status' => 'failed']);
    }
}
