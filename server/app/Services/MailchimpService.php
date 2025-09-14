<?php

namespace App\Services;

use MailchimpMarketing\ApiClient as MailchimpApiClient;

class MailchimpService
{
    private MailchimpApiClient $client;

    public function __construct()
    {
        $this->client = new MailchimpApiClient();
        $this->client->setConfig([
            'apiKey' => env('MAILCHIMP_API_KEY'),
            'server' => env('MAILCHIMP_SERVER_PREFIX'),
        ]);
    }

    public function getClient(): MailchimpApiClient
    {
        return $this->client;
    }

    public function getCampaignContent(string $campaignId)
    {
        /**
         * @var \MailchimpMarketing\Api\CampaignsApi
         */
        $campaigns = $this->client->campaigns;

        $response = $campaigns->getContent($campaignId);
        return $response;
    }
}

