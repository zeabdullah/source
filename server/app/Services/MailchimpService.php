<?php

namespace App\Services;

use MailchimpMarketing\ApiClient as MailchimpClient;

class MailchimpService
{
    private MailchimpClient $client;

    public function __construct()
    {
        $this->client = new MailchimpClient();
        $this->client->setConfig([
            'apiKey' => env('MAILCHIMP_API_KEY'),
            'server' => env('MAILCHIMP_SERVER_PREFIX'),
        ]);
    }

    public function getClient(): MailchimpClient
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

