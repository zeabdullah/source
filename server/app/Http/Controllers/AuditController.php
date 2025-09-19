<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuditController extends Controller
{
    /**
     * Display a listing of audits for a project
     */
    public function index(Request $request, Project $project): JsonResponse
    {
        // user already checked for ownership with is_owner middleware

        try {
            $audits = $project->audits()
                ->with([
                    'screens' => function ($query) {
                        $query->orderBy('audit_screens.sequence_order');
                    }
                ])
                ->orderBy('created_at', 'desc')
                ->get();

            return $this->responseJson($audits);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to fetch audits: ' . $th->getMessage());
        }
    }

    /**
     * Store a newly created audit
     */
    public function store(Request $request, Project $project): JsonResponse
    {
        // user already checked for ownership with is_owner middleware

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'screen_ids' => 'required|array|min:2|max:7',
            'screen_ids.*' => 'exists:screens,id',
        ]);

        try {
            // Validate that all screens belong to the project
            $screenCount = $project->screens()
                ->whereIn('id', $validated['screen_ids'])
                ->count();

            if ($screenCount !== count($validated['screen_ids'])) {
                return $this->responseJson(
                    message: 'One or more screens do not belong to the specified project.',
                    code: 422
                );
            }

            $audit = Audit::create([
                'project_id' => $project->id,
                'name' => $validated['name'],
                'description' => $validated['description'],
                'status' => 'pending',
            ]);

            // Attach screens with sequence order
            foreach ($validated['screen_ids'] as $index => $screenId) {
                $audit->screens()->attach($screenId, [
                    'sequence_order' => $index + 1
                ]);
            }

            // TODO: need to ensure it works correctly
            $audit->load([
                'screens' => function ($query) {
                    $query->orderBy('audit_screens.sequence_order');
                }
            ]);

            return $this->responseJson($audit, 'Audit created successfully', 201);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to create audit: ' . $th->getMessage());
        }
    }

    /**
     * Display the specified audit
     */
    public function show(Project $project, Audit $audit): JsonResponse
    {
        // user already checked for ownership with is_owner middleware

        try {
            $audit->load([
                'screens' => function ($query) {
                    $query->orderBy('audit_screens.sequence_order');
                }
            ]);

            return $this->responseJson($audit);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to fetch audit: ' . $th->getMessage());
        }
    }

    /**
     * Execute the audit (trigger AI analysis)
     */
    public function execute(Project $project, Audit $audit): JsonResponse
    {
        // user already checked for ownership with is_owner middleware

        try {
            if ($audit->isProcessing()) {
                return $this->responseJson($audit, 'Audit is already being processed', 202);
            }

            if ($audit->isCompleted()) {
                return $this->responseJson($audit, 'Audit has already been completed', 409);
            }

            // Update status to processing
            $audit->update(['status' => 'processing']);

            // TODO: Dispatch PerformFlowAudit job
            // PerformFlowAudit::dispatch($audit);

            return $this->responseJson($audit, 'Audit processing started');
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to execute audit: ' . $th->getMessage());
        }
    }

    /**
     * Get audit status
     */
    public function status(Project $project, Audit $audit): JsonResponse
    {
        // user already checked for ownership with is_owner middleware

        try {
            $statusData = [
                'id' => $audit->id,
                'status' => $audit->status,
                'overall_score' => $audit->overall_score,
                'created_at' => $audit->created_at,
                'updated_at' => $audit->updated_at,
            ];

            return $this->responseJson($statusData);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to fetch audit status: ' . $th->getMessage());
        }
    }

    /**
     * Update the specified audit
     */
    public function update(Request $request, Project $project, Audit $audit): JsonResponse
    {
        // user already checked for ownership with is_owner middleware

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string|max:2000',
        ]);

        try {
            $audit->update($validated);

            return $this->responseJson($audit->fresh(), 'Audit updated successfully');
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to update audit: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified audit
     */
    public function destroy(Project $project, Audit $audit): JsonResponse
    {
        // user already checked for ownership with is_owner middleware

        try {
            $audit->delete();

            return $this->responseJson(message: 'Audit deleted successfully');
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to delete audit: ' . $th->getMessage());
        }
    }
}
