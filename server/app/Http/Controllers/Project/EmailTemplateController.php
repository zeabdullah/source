<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use App\Models\Project;
use App\Services\N8nService;
use App\Services\BrevoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\MailchimpService;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Storage;


class EmailTemplateController extends Controller
{
    public function importEmailTemplate(Request $request, string $projectId, MailchimpService $mailchimp, N8nService $n8n): JsonResponse
    {
        $validated = $request->validate([
            'mailchimp_campaign_id' => 'required|string',
        ]);
        $campaignId = $validated['mailchimp_campaign_id'];

        try {
            $emailTemplate = EmailTemplate::firstOrNew(['campaign_id' => $campaignId]);

            /** @var \App\Models\Project */
            $project = $request->attributes->get('project');
            $campaignContent = $mailchimp->getCampaignContent($campaignId);

            $base64Img = $n8n->generateBase64ThumbnailFromHtml($campaignContent->html);
            $binaryImg = base64_decode($base64Img);

            $thumbnailPath = 'email-thumbnails/' . uniqid('et_', true) . '.png';

            $emailTemplate->campaign_id ??= $campaignId;
            $emailTemplate->project_id ??= $project->id;
            Storage::put($thumbnailPath, $binaryImg);
            $emailTemplate->thumbnail_url = Storage::url($thumbnailPath);

            $emailTemplate->saveOrFail();

            return $this->responseJson($emailTemplate->fresh(), 'Imported Campaign and created Email Template successfully', 201);
        } catch (RequestException $e) {
            if ($e->getResponse()?->getStatusCode() === 404) {
                return $this->notFoundResponse(message: 'Campaign not found in Mailchimp');
            }
            return $this->responseJson(message: 'Failed to import email template: ' . $e->getMessage(), code: $e->getResponse()?->getStatusCode() ?? 500);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to import email template: ' . $th->getMessage());
        }
    }

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
    public function getEmailTemplateByIdBasic(Request $request, string $emailTemplateId): JsonResponse
    {
        return $this->getEmailTemplateById($request, '', $emailTemplateId);
    }

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

    // ===== BREVO INTEGRATION METHODS =====

    /**
     * Import email template from Brevo
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
            // Get template from Brevo
            $brevoTemplate = $brevo->getTemplate($user->brevo_api_token, $brevoTemplateId);

            /** @var \App\Models\Project */
            $project = $request->attributes->get('project');

            // Check if template already exists
            $emailTemplate = EmailTemplate::where('brevo_template_id', $brevoTemplateId)
                ->where('project_id', $project->id)
                ->first();

            if ($emailTemplate) {
                return $this->responseJson($emailTemplate, 'Template already imported');
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
                    return $this->responseJson(
                        message: 'Failed to decode generated thumbnail image.',
                        code: 500
                    );
                }
                $thumbnailPath = 'email-thumbnails/' . uniqid('et_', true) . '.png';
                Storage::put($thumbnailPath, $binaryImg);
                $thumbnailUrl = Storage::url($thumbnailPath);
            } catch (\Throwable $thumbEx) {
                return $this->responseJson(
                    message: 'Error generating thumbnail: ' . $thumbEx->getMessage(),
                    code: 500
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
                return $this->notFoundResponse(message: 'Template not found in Brevo');
            }
            // Always return a JSON response with a message
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
     * Sync email template with Brevo
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
     * Update email template in Brevo
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
     * Get user's Brevo templates
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
