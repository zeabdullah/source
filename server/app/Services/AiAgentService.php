<?php

namespace App\Services;

use Gemini\Data\Content;
use Gemini\GeminiHelper;
use Gemini;

class AiAgentService
{
    protected $client;

    public function __construct()
    {
        $apiKey = config('ai.gemini.api_key');
        $this->client = Gemini::client($apiKey);
    }

    public function getModel()
    {
        return $this->client->generativeModel('gemini-2.0-flash');
    }

    /**
     * @param string|array $userMessages
     * @param \Gemini\Data\Content[] $history
     */
    public function generateReplyFromContext(string|array $userMessages, array $history): string
    {
        $result = $this->getModel()->startChat($history)->sendMessage($userMessages);

        return $result->text();
    }
}
