<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class BrevoService
{
    private Client $client;
    private string $baseUrl = 'https://api.brevo.com/v3';

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => 30,
        ]);
    }

    /**
     * Get all email templates from Brevo
     */
    public function getTemplates(string $apiKey): array
    {
        try {
            $response = $this->client->get('/smtp/templates', [
                'headers' => [
                    'api-key' => $apiKey,
                    'Content-Type' => 'application/json',
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            Log::error('Brevo API Error - Get Templates: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get a specific email template by ID
     */
    public function getTemplate(string $apiKey, string $templateId): array
    {
        try {
            $response = $this->client->get("/smtp/templates/{$templateId}", [
                'headers' => [
                    'api-key' => $apiKey,
                    'Content-Type' => 'application/json',
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            Log::error('Brevo API Error - Get Template: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create a new email template in Brevo
     */
    public function createTemplate(string $apiKey, array $templateData): array
    {
        try {
            $response = $this->client->post('/smtp/templates', [
                'headers' => [
                    'api-key' => $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => $templateData,
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            Log::error('Brevo API Error - Create Template: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update an existing email template in Brevo
     */
    public function updateTemplate(string $apiKey, string $templateId, array $templateData): array
    {
        try {
            $response = $this->client->put("/smtp/templates/{$templateId}", [
                'headers' => [
                    'api-key' => $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => $templateData,
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            Log::error('Brevo API Error - Update Template: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete an email template from Brevo
     */
    public function deleteTemplate(string $apiKey, string $templateId): bool
    {
        try {
            $response = $this->client->delete("/smtp/templates/{$templateId}", [
                'headers' => [
                    'api-key' => $apiKey,
                    'Content-Type' => 'application/json',
                ],
            ]);

            return $response->getStatusCode() === 204;
        } catch (RequestException $e) {
            Log::error('Brevo API Error - Delete Template: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Send a test email using a template
     */
    public function sendTestTemplate(string $apiKey, string $templateId, array $testData): array
    {
        try {
            $response = $this->client->post("/smtp/templates/{$templateId}/sendTest", [
                'headers' => [
                    'api-key' => $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => $testData,
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            Log::error('Brevo API Error - Send Test Template: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Generate a preview of the template
     */
    public function generateTemplatePreview(string $apiKey, string $templateId, array $previewData = []): array
    {
        try {
            $response = $this->client->post("/smtp/templates/{$templateId}/preview", [
                'headers' => [
                    'api-key' => $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => $previewData,
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            Log::error('Brevo API Error - Generate Template Preview: ' . $e->getMessage());
            throw $e;
        }
    }
}
