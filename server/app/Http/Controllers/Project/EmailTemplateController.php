<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use App\Services\N8nService;
use App\Services\BrevoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Tag(
 *     name="Email Templates",
 *     description="Email template management and Brevo integration"
 * )
 */
class EmailTemplateController extends Controller
{
    /**
     * @OA\Get(
     *     path="/projects/{projectId}/email-templates",
     *     summary="Get project email templates",
     *     description="Get all email templates for a project",
     *     tags={"Email Templates"},
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
     *         description="List of email templates",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/EmailTemplate")
     *         )
     *     ),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function getProjectEmailTemplates(Request $request, string $projectId): JsonResponse
    {
        /** @var \App\Models\Project */
        $project = $request->attributes->get('project');

        try {
            return $this->responseJson($project->emailTemplates);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to fetch email templates: ' . $th->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/projects/{projectId}/email-templates/{emailTemplateId}",
     *     summary="Get email template by ID",
     *     description="Get a specific email template by its ID",
     *     tags={"Email Templates"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="projectId",
     *         in="path",
     *         description="Project ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Parameter(
     *         name="emailTemplateId",
     *         in="path",
     *         description="Email template ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Email template data",
     *         @OA\JsonContent(ref="#/components/schemas/EmailTemplate")
     *     ),
     *     @OA\Response(response=404, description="Email template not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function getEmailTemplateById(Request $request, string $projectId, string $emailTemplateId): JsonResponse
    {
        try {
            $emailTemplate = EmailTemplate::find($emailTemplateId);
            if (!$emailTemplate) {
                return $this->notFoundResponse(message: 'Email template not found');
            }

            return $this->responseJson($emailTemplate);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to get email template: ' . $th->getMessage());
        }
    }

    /**
     * @OA\Put(
     *     path="/projects/{projectId}/email-templates/{emailTemplateId}",
     *     summary="Update email template",
     *     description="Update an email template by its ID",
     *     tags={"Email Templates"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="projectId",
     *         in="path",
     *         description="Project ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Parameter(
     *         name="emailTemplateId",
     *         in="path",
     *         description="Email template ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="section_name", type="string", nullable=true, example="Updated Template Name"),
     *             @OA\Property(property="html", type="string", nullable=true, example="<html>Updated content</html>")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Email template updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/EmailTemplate")
     *     ),
     *     @OA\Response(response=404, description="Email template not found"),
     *     @OA\Response(response=422, description="Validation errors"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function updateEmailTemplateById(Request $request, string $projectId, string $emailTemplateId): JsonResponse
    {
        $validated = $request->validate([
            'section_name' => 'nullable|string|max:255',
            'html' => 'nullable|string',
        ]);

        /** @var \App\Models\Project */
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

    /**
     * @OA\Delete(
     *     path="/projects/{projectId}/email-templates/{emailTemplateId}",
     *     summary="Delete email template",
     *     description="Delete an email template by its ID",
     *     tags={"Email Templates"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="projectId",
     *         in="path",
     *         description="Project ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Parameter(
     *         name="emailTemplateId",
     *         in="path",
     *         description="Email template ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Email template deleted successfully",
     *         @OA\JsonContent(ref="#/components/schemas/EmailTemplate")
     *     ),
     *     @OA\Response(response=404, description="Email template not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function deleteEmailTemplateById(Request $request, string $projectId, string $emailTemplateId): JsonResponse
    {
        /** @var \App\Models\Project */
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

    /**
     * @OA\Post(
     *     path="/projects/{projectId}/email-templates/import-brevo",
     *     summary="Import Brevo template",
     *     description="Import an email template from Brevo",
     *     tags={"Email Templates"},
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
     *             @OA\Property(property="brevo_template_id", type="integer", example=123)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Template imported successfully",
     *         @OA\JsonContent(ref="#/components/schemas/EmailTemplate")
     *     ),
     *     @OA\Response(response=403, description="Brevo API token not configured"),
     *     @OA\Response(response=404, description="Template not found in Brevo"),
     *     @OA\Response(response=409, description="Template already imported"),
     *     @OA\Response(response=422, description="Validation errors"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function importBrevoTemplate(Request $request, string $projectId, BrevoService $brevo, N8nService $n8n): JsonResponse
    {
        $validated = $request->validate([
            'brevo_template_id' => 'required|integer',
        ]);

        $brevoTemplateId = $validated['brevo_template_id'];
        $user = auth()->user();
        if (!$user->brevo_api_token) {
            return $this->forbiddenResponse('Brevo API token not configured for user');
        }

        try {
            $brevoTemplate = $brevo->getTemplate($user->brevo_api_token, $brevoTemplateId);
            /** @var \App\Models\Project */
            $project = $request->attributes->get('project');

            // Check if template already exists
            $emailTemplate = EmailTemplate::where('brevo_template_id', $brevoTemplateId)
                ->where('project_id', $project->id)
                ->first();

            if ($emailTemplate) {
                return $this->responseJson(message: 'Template already imported', code: 409);
            }

            // Defensive: Ensure required Brevo fields exist
            if (
                !isset($brevoTemplate['htmlContent']) ||
                empty($brevoTemplate['htmlContent'])
            ) {
                return $this->responseJson(
                    message: 'Brevo template does not contain HTML content.',
                    code: 422
                );
            }

            // Generate thumbnail from HTML content
            try {
                $base64Img = $n8n->generateBase64ThumbnailFromHtml($brevoTemplate['htmlContent']);
                if (!$base64Img) {
                    return $this->responseJson(
                        message: 'Failed to generate thumbnail from Brevo template HTML.',
                        code: 500
                    );
                }

                $binaryImg = base64_decode($base64Img);
                if ($binaryImg === false) {
                    return $this->serverErrorResponse('Failed to decode generated thumbnail image.');
                }

                $thumbnailPath = 'email-thumbnails/' . uniqid('et_', true) . '.png';
                Storage::put($thumbnailPath, $binaryImg);
                $thumbnailUrl = Storage::url($thumbnailPath);
            } catch (\Throwable $thumbnailException) {
                return $this->serverErrorResponse(
                    'Error generating thumbnail: ' . $thumbnailException->getMessage()
                );
            }

            // Create email template record
            $emailTemplate = EmailTemplate::create([
                'project_id' => $project->id,
                'section_name' => $brevoTemplate['templateName'] ?? 'Imported Template',
                'brevo_template_id' => $brevoTemplateId,
                'html_content' => $brevoTemplate['htmlContent'],
                'thumbnail_url' => $thumbnailUrl ?? null,
            ]);

            if (!$emailTemplate) {
                return $this->serverErrorResponse(message: 'Failed to create email template record');
            }

            return $this->responseJson($emailTemplate->fresh(), 'Brevo template imported successfully', 201);
        } catch (RequestException $e) {
            $status = $e->getResponse()?->getStatusCode();
            if ($status === 404) {
                return $this->notFoundResponse('Template not found in Brevo');
            }
            return $this->responseJson(
                message: 'Failed to import Brevo template: ' . $e->getMessage(),
                code: $status ?? 500
            );
        } catch (\Throwable $th) {
            // Always return a JSON response with a message
            return $this->responseJson(
                message: 'Failed to import Brevo template: ' . $th->getMessage(),
                code: 500
            );
        }
    }

    /**
     * @OA\Post(
     *     path="/projects/{projectId}/email-templates/{emailTemplateId}/sync-brevo",
     *     summary="Sync email template with Brevo",
     *     description="Sync local email template with the latest data from Brevo",
     *     tags={"Email Templates"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="projectId",
     *         in="path",
     *         description="Project ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Parameter(
     *         name="emailTemplateId",
     *         in="path",
     *         description="Email template ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Template synced with Brevo successfully",
     *         @OA\JsonContent(ref="#/components/schemas/EmailTemplate")
     *     ),
     *     @OA\Response(response=400, description="Email template is not linked to Brevo"),
     *     @OA\Response(response=403, description="Brevo API token not configured"),
     *     @OA\Response(response=404, description="Email template not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function syncWithBrevo(Request $request, string $projectId, string $emailTemplateId, BrevoService $brevo): JsonResponse
    {
        $user = auth()->user();

        if (!$user->brevo_api_token) {
            return $this->forbiddenResponse('Brevo API token not configured for user');
        }

        /** @var \App\Models\Project */
        $project = $request->attributes->get('project');

        try {
            $emailTemplate = $project->emailTemplates()->find($emailTemplateId);
            if (!$emailTemplate) {
                return $this->notFoundResponse('Email template not found');
            }

            if (!$emailTemplate->brevo_template_id) {
                return $this->badRequestResponse('Email template is not linked to Brevo');
            }

            // Get latest template data from Brevo
            $brevoTemplate = $brevo->getTemplate($user->brevo_api_token, $emailTemplate->brevo_template_id);

            // Update local template with Brevo data
            $emailTemplate->update([
                'html_content' => $brevoTemplate['htmlContent'],
                'section_name' => $brevoTemplate['templateName'] ?? $emailTemplate->section_name,
            ]);

            return $this->responseJson($emailTemplate->fresh(), 'Template synced with Brevo successfully');
        } catch (RequestException $e) {
            if ($e->getResponse()?->getStatusCode() === 404) {
                return $this->notFoundResponse('Template not found in Brevo');
            }
            return $this->responseJson(message: 'Failed to sync with Brevo: ' . $e->getMessage(), code: $e->getResponse()?->getStatusCode() ?? 500);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to sync with Brevo: ' . $th->getMessage());
        }
    }

    /**
     * @OA\Put(
     *     path="/projects/{projectId}/email-templates/{emailTemplateId}/update-brevo",
     *     summary="Update email template in Brevo",
     *     description="Update the email template in Brevo with local changes",
     *     tags={"Email Templates"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="projectId",
     *         in="path",
     *         description="Project ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Parameter(
     *         name="emailTemplateId",
     *         in="path",
     *         description="Email template ID",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="html_content", type="string", example="<html>Updated content</html>"),
     *             @OA\Property(property="template_name", type="string", nullable=true, example="Updated Template Name")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Template updated in Brevo successfully",
     *         @OA\JsonContent(ref="#/components/schemas/EmailTemplate")
     *     ),
     *     @OA\Response(response=400, description="Email template is not linked to Brevo"),
     *     @OA\Response(response=403, description="Brevo API token not configured"),
     *     @OA\Response(response=404, description="Email template not found"),
     *     @OA\Response(response=422, description="Validation errors"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function updateInBrevo(Request $request, string $projectId, string $emailTemplateId, BrevoService $brevo): JsonResponse
    {
        $validated = $request->validate([
            'html_content' => 'required|string',
            'template_name' => 'nullable|string|max:255',
        ]);

        $user = auth()->user();

        if (!$user->brevo_api_token) {
            return $this->forbiddenResponse('Brevo API token not configured for user');
        }

        /** @var \App\Models\Project */
        $project = $request->attributes->get('project');

        try {
            $emailTemplate = $project->emailTemplates()->find($emailTemplateId);
            if (!$emailTemplate) {
                return $this->notFoundResponse(message: 'Email template not found');
            }

            if (!$emailTemplate->brevo_template_id) {
                return $this->badRequestResponse('Email template is not linked to Brevo');
            }

            // Update template in Brevo
            $brevoTemplateData = [
                'htmlContent' => $validated['html_content'],
            ];

            if (isset($validated['template_name'])) {
                $brevoTemplateData['templateName'] = $validated['template_name'];
            }

            $brevo->updateTemplate($user->brevo_api_token, $emailTemplate->brevo_template_id, $brevoTemplateData);

            // Update local template
            $emailTemplate->update([
                'html_content' => $validated['html_content'],
                'section_name' => $validated['template_name'] ?? $emailTemplate->section_name,
            ]);

            return $this->responseJson($emailTemplate->fresh(), 'Template updated in Brevo successfully');
        } catch (RequestException $e) {
            if ($e->getResponse()?->getStatusCode() === 404) {
                return $this->notFoundResponse('Template not found in Brevo');
            }
            return $this->responseJson(message: 'Failed to update template in Brevo: ' . $e->getMessage(), code: $e->getResponse()?->getStatusCode() ?? 500);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to update template in Brevo: ' . $th->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/brevo-templates",
     *     summary="Get Brevo templates",
     *     description="Get all templates from user's Brevo account",
     *     tags={"Email Templates"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of Brevo templates",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/BrevoTemplate")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Brevo API token not configured"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function getBrevoTemplates(Request $request, BrevoService $brevo): JsonResponse
    {
        $user = auth()->user();

        if (!$user->brevo_api_token) {
            return $this->forbiddenResponse('Brevo API token not configured for user');
        }

        try {
            $templates = $brevo->getTemplates($user->brevo_api_token);

            return $this->responseJson($templates);
        } catch (RequestException $e) {
            return $this->responseJson(message: 'Failed to fetch Brevo templates: ' . $e->getMessage(), code: $e->getResponse()?->getStatusCode() ?? 500);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to fetch Brevo templates: ' . $th->getMessage());
        }
    }
}
