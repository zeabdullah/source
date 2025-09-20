<?php

namespace App\Http\Controllers;

use App\Jobs\PerformFlowAudit;
use App\Models\Audit;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Audits",
 *     description="Audit management and execution endpoints"
 * )
 */
class AuditController extends Controller
{
    /**
     * @OA\Get(
     *     path="/projects/{projectId}/audits",
     *     summary="Get project audits",
     *     description="Get all audits for a specific project",
     *     tags={"Audits"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="projectId",
     *         in="path",
     *         description="Project ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of audits",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Audit")
     *         )
     *     ),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function index(Request $request, string $projectId): JsonResponse
    {
        /** @var Project */
        $project = $request->attributes->get('project');

        try {
            $audits = $project->audits()
                ->orderBy('created_at', 'desc')
                ->get();

            return $this->responseJson($audits);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to fetch audits: ' . $th->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/projects/{projectId}/audits",
     *     summary="Create audit",
     *     description="Create a new audit for a project",
     *     tags={"Audits"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="projectId",
     *         in="path",
     *         description="Project ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", maxLength=255, example="User Flow Audit"),
     *             @OA\Property(property="description", type="string", maxLength=2000, nullable=true, example="Audit description"),
     *             @OA\Property(property="screen_ids", type="array", minItems=2, maxItems=7,
     *                 @OA\Items(type="integer", example=1),
     *                 example={1, 2, 3}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Audit created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Audit")
     *     ),
     *     @OA\Response(response=422, description="Validation errors"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function store(Request $request, string $projectId): JsonResponse
    {
        /** @var Project */
        $project = $request->attributes->get('project');

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

            return $this->responseJson($audit, 'Audit created successfully', 201);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to create audit: ' . $th->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/projects/{projectId}/audits/{auditId}",
     *     summary="Get audit details",
     *     description="Get details of a specific audit",
     *     tags={"Audits"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="projectId",
     *         in="path",
     *         description="Project ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Parameter(
     *         name="auditId",
     *         in="path",
     *         description="Audit ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Audit details",
     *         @OA\JsonContent(ref="#/components/schemas/Audit")
     *     ),
     *     @OA\Response(response=404, description="Audit not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function show(Request $request, string $projectId, string $auditId): JsonResponse
    {
        // user already checked for ownership with is_owner middleware

        try {
            $audit = Audit::find($auditId);
            if (!$audit) {
                return $this->notFoundResponse('Audit not found');
            }

            return $this->responseJson($audit);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to fetch audit: ' . $th->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/projects/{projectId}/audits/{auditId}/execute",
     *     summary="Execute audit",
     *     description="Start AI analysis for an audit",
     *     tags={"Audits"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="projectId",
     *         in="path",
     *         description="Project ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Parameter(
     *         name="auditId",
     *         in="path",
     *         description="Audit ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Audit processing started",
     *         @OA\JsonContent(ref="#/components/schemas/Audit")
     *     ),
     *     @OA\Response(response=202, description="Audit is already being processed"),
     *     @OA\Response(response=404, description="Audit not found"),
     *     @OA\Response(response=409, description="Audit has already been completed"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function execute(Request $request, string $projectId, string $auditId): JsonResponse
    {
        // user already checked for ownership with is_owner middleware

        try {
            $audit = Audit::find($auditId);
            if (!$audit) {
                return $this->notFoundResponse('Audit not found');
            }

            if ($audit->isProcessing()) {
                return $this->responseJson($audit, 'Audit is already being processed', 202);
            }

            if ($audit->isCompleted()) {
                return $this->responseJson($audit, 'Audit has already been completed', 409);
            }

            // Update status to processing
            $audit->update(['status' => 'processing']);

            // Dispatch PerformFlowAudit job
            PerformFlowAudit::dispatch($audit);

            return $this->responseJson($audit, 'Audit processing started');
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to execute audit: ' . $th->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/projects/{projectId}/audits/{auditId}/status",
     *     summary="Get audit status",
     *     description="Get the current status of an audit",
     *     tags={"Audits"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="projectId",
     *         in="path",
     *         description="Project ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Parameter(
     *         name="auditId",
     *         in="path",
     *         description="Audit ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Audit status",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="status", type="string", enum={"pending", "processing", "completed", "failed"}, example="processing"),
     *             @OA\Property(property="overall_score", type="number", format="float", nullable=true, example=85.5),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T00:00:00.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T00:00:00.000000Z")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Audit not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function status(Request $request, string $projectId, string $auditId): JsonResponse
    {
        // user already checked for ownership with is_owner middleware

        try {
            $audit = Audit::find($auditId);
            if (!$audit) {
                return $this->notFoundResponse('Audit not found');
            }

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
     * @OA\Put(
     *     path="/projects/{projectId}/audits/{auditId}",
     *     summary="Update audit",
     *     description="Update an existing audit",
     *     tags={"Audits"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="projectId",
     *         in="path",
     *         description="Project ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Parameter(
     *         name="auditId",
     *         in="path",
     *         description="Audit ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", maxLength=255, example="Updated Audit Name"),
     *             @OA\Property(property="description", type="string", maxLength=2000, nullable=true, example="Updated description")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Audit updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Audit")
     *     ),
     *     @OA\Response(response=422, description="Validation errors"),
     *     @OA\Response(response=500, description="Server error")
     * )
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

            return $this->responseJson($audit, 'Audit updated successfully');
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to update audit: ' . $th->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *     path="/projects/{projectId}/audits/{auditId}",
     *     summary="Delete audit",
     *     description="Delete an audit",
     *     tags={"Audits"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="projectId",
     *         in="path",
     *         description="Project ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Parameter(
     *         name="auditId",
     *         in="path",
     *         description="Audit ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Audit deleted successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Audit")
     *     ),
     *     @OA\Response(response=404, description="Audit not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function destroy(Request $request, string $projectId, string $auditId): JsonResponse
    {
        try {
            $audit = Audit::find($auditId);
            if (!$audit) {
                return $this->notFoundResponse('Audit not found');
            }

            $audit->delete();

            return $this->responseJson($audit, 'Audit deleted successfully');
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to delete audit: ' . $th->getMessage());
        }
    }
}
