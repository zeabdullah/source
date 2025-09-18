<?php

namespace App\Services;

use Gemini;
use Gemini\Data\Content;

class AiAgentService
{
    private $client;
    private $baseModel;

    public function __construct()
    {
        $apiKey = config('ai.gemini.api_key');
        $this->client = Gemini::client($apiKey);

        $this->baseModel = $this->client->generativeModel('gemini-2.0-flash');
    }

    private function getFigmaSystemInstruction(): Content
    {
        return Content::parse(
            "You are a Figma AI agent specialized in analyzing design frames and providing actionable improvement suggestions. Your role is to:

**Core Responsibilities:**
- Analyze Figma frames provided as context to understand the current design
- Respond to user prompts about design improvements, issues, or questions
- Provide specific, actionable suggestions for design enhancements
- Consider usability, accessibility, visual hierarchy, and design best practices

**Analysis Framework:**
When examining Figma frames, evaluate:
- Visual hierarchy and information architecture
- Color contrast and accessibility compliance
- Typography choices and readability
- Spacing, alignment, and layout consistency
- Component reusability and design system adherence
- User experience flow and interaction patterns
- Mobile responsiveness and cross-device compatibility

**Response Guidelines:**
- Be specific and actionable in your suggestions
- Reference specific elements, components, or areas in the design, when possible and if available
- Explain the reasoning behind each recommendation
- Prioritize suggestions by impact and implementation effort
- Consider both immediate fixes and long-term design improvements
- Maintain a constructive, professional tone

**Context Awareness:**
- You will receive Figma frame data as context for your analysis
- User prompts may ask about specific aspects (colors, layout, components, etc.)
- Consider (highly) the overall design system and brand consistency
- Account for different user personas and use cases

Always provide clear, implementable recommendations that help improve the design's effectiveness and user experience."
        );
    }

    private function getEmailTemplateSystemInstruction(string $currentHtml, string $prompt): Content
    {
        return Content::parse(
            "You are an expert email template designer and developer. Your task is to update email templates based on user requests while maintaining professional email design standards.

**Your responsibilities:**
- Analyze the current HTML email template
- Understand the user's update request
- Generate improved HTML that maintains email client compatibility
- Provide a clear explanation of the changes made
- Suggest an appropriate section name if the content changes significantly

**Email Template Guidelines:**
- Use inline CSS for maximum email client compatibility
- Maintain responsive design principles
- Ensure proper fallbacks for images and fonts
- Keep HTML structure clean and semantic
- Test for common email client rendering issues

Current HTML template:
```html
{$currentHtml}
```

User request: {$prompt}"
        );
    }

    /**
     * Generate AI response for Figma design analysis
     *
     * @param string|array $userMessages
     * @param \Gemini\Data\Content[] $history
     */
    public function generateFigmaReply(string|array $userMessages, array $history = []): string
    {
        $model = $this->baseModel->withSystemInstruction($this->getFigmaSystemInstruction());
        $result = $model->startChat($history)->sendMessage($userMessages);

        return $result->text();
    }

    /**
     * Generate AI response for email template updates
     *
     * @param string $prompt User's update request
     * @param string $currentHtml Current HTML template
     * @param string $emailTemplateId Template ID for context
     */
    public function generateEmailTemplateUpdate(string $prompt, string $currentHtml, string $emailTemplateId): array
    {
        $systemInstruction = $this->getEmailTemplateSystemInstruction($currentHtml, $prompt);
        $model = $this->baseModel->withSystemInstruction($systemInstruction);

        try {
            $result = $model->startChat([])->sendMessage($prompt);
            $normalAiResponse = $result->text();

            $formattedAiResponse = $model->startChat([])->sendMessage("You are to respond with a JSON object with the following structure:
            {
                \"explanation\": \"AI's reply without the HTML\",
                \"updated_html\": \"The updated HTML content\",
                \"updated_name\": \"An appropriate section name, if the content changes significantly.  If not, omit this field.\"
            }
            Ensure the JSON is valid and parsable. Here is the response to parse to JSON: $normalAiResponse");

            // Parse the JSON response
            $aiResponse = json_decode($formattedAiResponse->text(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON response from AI: ' . json_last_error_msg());
            }

            // Validate required fields
            if (!isset($aiResponse['updated_html'])) {
                throw new \Exception('AI response missing required field: updated_html');
            }

            return $aiResponse;

        } catch (\Throwable $th) {
            // Fallback response if AI fails
            return [
                'updated_html' => $currentHtml,
                'updated_name' => null,
                'explanation' => 'AI template update failed: ' . $th->getMessage() . '. Template remains unchanged.'
            ];
        }
    }

    /**
     * Legacy method for backward compatibility
     * @deprecated Use generateFigmaReply() instead
     *
     * @param string|array $userMessages
     * @param \Gemini\Data\Content[] $history
     */
    public function generateReplyFromContext(string|array $userMessages, array $history): string
    {
        return $this->generateFigmaReply($userMessages, $history);
    }
}
