<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\Project;
use App\Models\Screen;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuditController extends Controller
{
    /**
     * Display a listing of audits for a project
     */
    public function index(Request $request, Project $project): JsonResponse
    {

        // user already checked for ownership with is_owner middleware

        $audits = $project->audits()
            ->with([
                'screens' => function ($query) {
                    $query->orderBy('audit_screens.sequence_order');
                }
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $audits
        ]);
    }

    /**
     * Store a newly created audit
     */
    public function store(Request $request, Project $project): JsonResponse
    {
        // user already checked for ownership with is_owner middleware

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'screen_ids' => 'required|array|min:2|max:7',
            'screen_ids.*' => 'exists:screens,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // TODO: Validate that all screens belong to the project

        $audit = Audit::create([
            'project_id' => $project->id,
            'name' => $request->name,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        // Attach screens with sequence order
        foreach ($request->screen_ids as $index => $screenId) {
            $audit->screens()->attach($screenId, [
                'sequence_order' => $index + 1
            ]);
        }

        $audit->load([
            'screens' => function ($query) {
                $query->orderBy('audit_screens.sequence_order');
            }
        ]);

        return response()->json([
            'success' => true,
            'data' => $audit
        ], 201);
    }

    /**
     * Display the specified audit
     */
    public function show(Project $project, Audit $audit): JsonResponse
    {
        // user already checked for ownership with is_owner middleware

        $audit->load([
            'screens' => function ($query) {
                $query->orderBy('audit_screens.sequence_order');
            }
        ]);

        return response()->json([
            'success' => true,
            'data' => $audit
        ]);
    }

    /**
     * Execute the audit (trigger AI analysis)
     */
    public function execute(Project $project, Audit $audit): JsonResponse
    {
        // user already checked for ownership with is_owner middleware

        if ($audit->isProcessing()) {
            return response()->json([
                'success' => false,
                'message' => 'Audit is already being processed'
            ], 409);
        }

        if ($audit->isCompleted()) {
            return response()->json([
                'success' => false,
                'message' => 'Audit has already been completed'
            ], 409);
        }

        // Update status to processing
        $audit->update(['status' => 'processing']);

        // TODO: Dispatch PerformFlowAudit job
        // PerformFlowAudit::dispatch($audit);

        return response()->json([
            'success' => true,
            'message' => 'Audit processing started',
            'data' => $audit
        ]);
    }

    /**
     * Get audit status
     */
    public function status(Project $project, Audit $audit): JsonResponse
    {
        // user already checked for ownership with is_owner middleware

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $audit->id,
                'status' => $audit->status,
                'overall_score' => $audit->overall_score,
                'created_at' => $audit->created_at,
                'updated_at' => $audit->updated_at,
            ]
        ]);
    }

    /**
     * Update the specified audit
     */
    public function update(Request $request, Project $project, Audit $audit): JsonResponse
    {
        // user already checked for ownership with is_owner middleware

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $audit->update($request->only(['name', 'description']));

        return response()->json([
            'success' => true,
            'data' => $audit
        ]);
    }

    /**
     * Remove the specified audit
     */
    public function destroy(Project $project, Audit $audit): JsonResponse
    {
        // user already checked for ownership with is_owner middleware

        $audit->delete();

        return response()->json([
            'success' => true,
            'message' => 'Audit deleted successfully'
        ]);
    }
}
