<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    public function createEmailTemplate(Request $request, string $projectId): JsonResponse
    {
        $validated = $request->validate([
            'section_name' => 'required|string|max:255',
            'campaign_id' => 'required|string',
            'html' => 'required|string',
        ]);

        $project = $request->attributes->get('project');

        try {
            $emailTemplate = $project->emailTemplates()->create($validated);
            return $this->responseJson($emailTemplate, 'Email template created successfully', 201);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to create email template: ' . $th->getMessage());
        }
    }

    public function getProjectEmailTemplates(Request $request, string $projectId): JsonResponse
    {
        $project = $request->attributes->get('project');

        try {
            $emailTemplates = $project->emailTemplates;
            return $this->responseJson($emailTemplates);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to fetch email templates: ' . $th->getMessage());
        }
    }

    public function updateEmailTemplateById(Request $request, string $projectId, string $emailTemplateId): JsonResponse
    {
        $validated = $request->validate([
            'section_name' => 'nullable|string|max:255',
            'html' => 'nullable|string',
        ]);

        $project = $request->attributes->get('project');

        try {
            $emailTemplate = $project->emailTemplates()->find($emailTemplateId);
            if (!$emailTemplate) {
                return $this->notFoundResponse(message: 'Email template not found');
            }

            $emailTemplate->updateOrFail($validated);
            return $this->responseJson($emailTemplate->fresh(), 'Email template updated successfully');
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to update email template: ' . $th->getMessage());
        }
    }

    public function deleteEmailTemplateById(Request $request, string $projectId, string $emailTemplateId): JsonResponse
    {
        $project = $request->attributes->get('project');

        try {
            $emailTemplate = $project->emailTemplates()->find($emailTemplateId);
            if (!$emailTemplate) {
                return $this->notFoundResponse(message: 'Email template not found');
            }

            $emailTemplate->deleteOrFail();
            return $this->responseJson($emailTemplate, 'Email template deleted successfully');
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to delete email template: ' . $th->getMessage());
        }
    }
}
