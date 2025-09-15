<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use App\Services\N8nService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\MailchimpService;
use GuzzleHttp\Exception\RequestException;


class EmailTemplateController extends Controller
{
    public function importEmailTemplate(Request $request, string $projectId, MailchimpService $mailchimp, N8nService $n8n): JsonResponse
    {
        $validated = $request->validate([
            'mailchimp_campaign_id' => 'required|string',
        ]);
        $campaignId = $validated['mailchimp_campaign_id'];

        try {
            $campaignContent = $mailchimp->getCampaignContent($campaignId);
            /**
             * @var \App\Models\Project
             */
            $project = $request->attributes->get('project');

            $emailTemplate = new EmailTemplate();
            $emailTemplate->campaign_id = $campaignId;
            $emailTemplate->project_id = $project->id;

            // send html to n8n workflow, which generates a thumbnail, which is then saved to the email template as a url to the thumbnail
            $n8nResponse = $n8n->generateThumbnailFromHtml($campaignContent->html);
            $emailTemplate->thumbnail_url = $n8nResponse->thumbnail_url;

            $emailTemplate->saveOrFail();

            return $this->responseJson($emailTemplate, 'Imported Campaign and created Email Template successfully', 201);
        } catch (RequestException $e) {
            if ($e->getResponse()?->getStatusCode() === 404) {
                return $this->notFoundResponse(message: 'Campaign not found in Mailchimp');
            }
            return $this->serverErrorResponse(message: 'Failed to import email template: ' . $e->getMessage());
        } catch (\Throwable $th) {
            return $this->serverErrorResponse(message: 'Failed to import email template: ' . $th->getMessage());
        }
    }

    public function getProjectEmailTemplates(Request $request, string $projectId): JsonResponse
    {
        /**
         * @var \App\Models\Project
         */
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

        /**
         * @var \App\Models\Project
         */
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
