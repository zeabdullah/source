<?php

namespace App\Services;

use Gemini;
use Gemini\Data\Content;
use Gemini\Data\GenerationConfig;
use Gemini\Data\Schema;
use Gemini\Enums\DataType;
use Gemini\Enums\ResponseMimeType;

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

    private function getEmailTemplateSystemInstruction(): Content
    {
        return Content::parse(
            "You are an expert email template designer and developer. Your task is to update email templates based on user requests while maintaining professional email design standards.

**Your responsibilities:**
- Analyze the current HTML email template provided in context
- Understand the user's update request from their prompt
- Generate improved HTML that maintains email client compatibility
- Provide a clear explanation of the changes made in your chat response
- Only update the HTML if changes are actually needed

**Email Template Guidelines:**
- Use inline CSS for maximum email client compatibility
- Maintain responsive design principles
- Ensure proper fallbacks for images and fonts
- Keep HTML structure clean and semantic
- Test for common email client rendering issues

**Response Format:**
You must respond with a JSON object containing:
- 'chat_message': A conversational response explaining what you did or why no changes were needed
- 'updated_html': The new HTML content (only if changes were made, otherwise return null)

**Important:** Only modify the HTML if the user's request actually requires changes to the template. If they're just asking questions or the current template is already appropriate, set 'updated_html' to null."
        );
    }

    private function getFlowConsistencySystemInstruction(): Content
    {
        return Content::parse(
            "You are a UX/UI design consistency expert specializing in analyzing user flow designs across multiple screens. Your role is to evaluate design consistency and provide actionable recommendations for improvement.

**Core Responsibilities:**
- Analyze multiple screens within a user flow for design consistency
- Identify inconsistencies in visual design, interaction patterns, and user experience
- Provide specific, actionable recommendations for improvement
- Score overall consistency and highlight both issues and positive findings

**Analysis Framework:**
When examining screen flows, evaluate these key areas:

**Visual Consistency:**
- Color usage and palette consistency across screens
- Typography hierarchy and font choices
- Spacing, padding, and margin consistency
- Icon style and usage patterns
- Visual hierarchy and information architecture

**Interaction Consistency:**
- Button styles, sizes, and placement
- Form field designs and validation patterns
- Navigation patterns and menu structures
- Loading states and feedback mechanisms
- Error handling and messaging

**Content Consistency:**
- Terminology and labeling consistency
- Tone and voice in copy
- Information density and layout patterns
- Call-to-action placement and wording

**User Experience Flow:**
- Logical progression through the flow
- Consistent mental models
- Appropriate feedback and confirmation patterns
- Accessibility considerations

**Scoring Guidelines:**
- Rate overall consistency from 0-10 (10 being perfectly consistent)
- Consider both the severity and frequency of inconsistencies
- Weight critical user-facing elements more heavily
- Account for intentional variations vs. unintentional inconsistencies

**Response Requirements:**
- Provide specific examples of inconsistencies with screen references
- Include actionable suggestions for each issue
- Highlight positive consistency findings
- Prioritize issues by severity and user impact
- Be constructive and solution-oriented in recommendations

**Issue Categories:**
- terminology: Inconsistent text, labels, or messaging
- color_usage: Inconsistent color application or palette usage
- spacing: Inconsistent spacing, padding, or margins
- typography: Inconsistent font choices, sizes, or hierarchy
- interaction: Inconsistent button styles, form patterns, or interactions
- layout: Inconsistent layout patterns or information architecture
- accessibility: Accessibility or usability issues
- navigation: Inconsistent navigation patterns or flow logic

Always provide clear, implementable recommendations that help improve the overall user experience and design consistency."
        );
    }

    /**
     * Generate AI response for Figma design analysis
     *
     * @param string|array $userMessages
     * @param \Gemini\Data\Content[] $chatHistory
     * @param array|null $figmaNodes
     */
    public function generateFigmaReply(string|array $userMessages, array $chatHistory = [], ?array $figmaNodes = null): array
    {
        $systemInstruction = $this->getFigmaSystemInstruction();

        // Add Figma context if provided
        if ($figmaNodes) {
            $systemInstruction = Content::parse(
                $systemInstruction->parts[0]->text . "\n\n**Figma Frame Data:**\n" . json_encode($figmaNodes)
            );
        }

        $model = $this->baseModel
            ->withSystemInstruction($systemInstruction)
            ->withGenerationConfig(
                new GenerationConfig(
                    responseMimeType: ResponseMimeType::APPLICATION_JSON,
                    responseSchema: new Schema(
                        DataType::OBJECT,
                        properties: [
                            'chat_message' => new Schema(
                                DataType::STRING,
                                description: 'Conversational response explaining what was done or why no changes were needed'
                            )
                        ],
                        required: ['chat_message']
                    )
                )
            );

        try {
            $result = $model->startChat($chatHistory)->sendMessage($userMessages);
            $response = $result->json(true);

            return [
                'chat_message' => $response['chat_message'] ?? 'No response generated',
            ];
        } catch (\Throwable $th) {
            return [
                'chat_message' => "AI failed to respond: " . $th->getMessage(),
            ];
        }
    }

    public function generateEmailTemplateReply(
        string|array $userMessages,
        array $chatHistory = [],
        ?string $htmlContext = null
    ): array {
        $systemInstruction = $this->getEmailTemplateSystemInstruction();

        // Add HTML context to system instruction if provided
        if ($htmlContext) {
            $systemInstruction = Content::parse(
                $systemInstruction->parts[0]->text . "\n\n**Current Email Template HTML:**\n" . $htmlContext
            );
        }

        $model = $this->baseModel
            ->withSystemInstruction($systemInstruction)
            ->withGenerationConfig(
                new GenerationConfig(
                    responseMimeType: ResponseMimeType::APPLICATION_JSON,
                    responseSchema: new Schema(
                        DataType::OBJECT,
                        properties: [
                            'chat_message' => new Schema(
                                DataType::STRING,
                                description: 'Conversational response explaining what was done or why no changes were needed'
                            ),
                            'updated_html' => new Schema(
                                DataType::STRING,
                                description: 'New HTML content if changes were made, otherwise null',
                                nullable: true
                            )
                        ],
                        required: ['chat_message', 'updated_html']
                    )
                )
            );

        try {
            $result = $model->startChat($chatHistory)->sendMessage($userMessages);
            $response = $result->json(true);

            return [
                'chat_message' => $response['chat_message'] ?? 'No response generated',
                'updated_html' => $response['updated_html'] ?? null
            ];

        } catch (\Throwable $th) {
            return [
                'chat_message' => "AI failed to respond: " . $th->getMessage(),
                'updated_html' => null
            ];
        }
    }

    /**
     * Analyze flow consistency across multiple screens
     */
    public function analyzeFlowConsistency(array $screens, string $auditName): array
    {
        $systemInstruction = $this->getFlowConsistencySystemInstruction();

        // Add screen data context to system instruction
        $screensContext = "**Flow Name:** {$auditName}\n\n**Screens to Analyze:**\n" . implode("\n\n---\n\n", $screens);
        $systemInstruction = Content::parse(
            $systemInstruction->parts[0]->text . "\n\n" . $screensContext
        );

        $model = $this->baseModel
            ->withSystemInstruction($systemInstruction)
            ->withGenerationConfig(
                new GenerationConfig(
                    responseMimeType: ResponseMimeType::APPLICATION_JSON,
                    responseSchema: new Schema(
                        DataType::OBJECT,
                        properties: [
                            'auditId' => new Schema(
                                DataType::STRING,
                                description: 'Identifier for this audit analysis'
                            ),
                            'flowName' => new Schema(
                                DataType::STRING,
                                description: 'Name of the flow being analyzed'
                            ),
                            'overallConsistencyScore' => new Schema(
                                DataType::NUMBER,
                                description: 'Overall consistency score from 0-10'
                            ),
                            'issues' => new Schema(
                                DataType::ARRAY ,
                                description: 'Array of consistency issues found',
                                items: new Schema(
                                    DataType::OBJECT,
                                    properties: [
                                        'type' => new Schema(
                                            DataType::STRING,
                                            description: 'Type of issue (terminology, color_usage, spacing, typography, etc.)'
                                        ),
                                        'severity' => new Schema(
                                            DataType::STRING,
                                            description: 'Severity level (low, medium, high)'
                                        ),
                                        'description' => new Schema(
                                            DataType::STRING,
                                            description: 'Detailed description of the issue'
                                        ),
                                        'screens' => new Schema(
                                            DataType::ARRAY ,
                                            description: 'Array of screen names affected by this issue',
                                            items: new Schema(DataType::STRING)
                                        ),
                                        'suggestion' => new Schema(
                                            DataType::STRING,
                                            description: 'Actionable suggestion to fix the issue'
                                        )
                                    ],
                                    required: ['type', 'severity', 'description', 'screens', 'suggestion']
                                )
                            ),
                            'positiveFindings' => new Schema(
                                DataType::ARRAY ,
                                description: 'Array of positive consistency findings',
                                items: new Schema(DataType::STRING)
                            )
                        ],
                        required: ['auditId', 'flowName', 'overallConsistencyScore', 'issues', 'positiveFindings']
                    )
                )
            );

        try {
            $result = $model->generateContent("Analyze the flow consistency for: {$auditName}");
            $response = $result->json(true);

            return [
                'auditId' => $response['auditId'] ?? uniqid('audit_'),
                'flowName' => $response['flowName'] ?? $auditName,
                'overallConsistencyScore' => $response['overallConsistencyScore'] ?? 0,
                'issues' => $response['issues'] ?? [],
                'positiveFindings' => $response['positiveFindings'] ?? []
            ];
        } catch (\Throwable $th) {
            return [
                'auditId' => uniqid('audit_'),
                'flowName' => $auditName,
                'overallConsistencyScore' => 0,
                'issues' => [
                    [
                        'type' => 'analysis_error',
                        'severity' => 'high',
                        'description' => 'AI analysis failed: ' . $th->getMessage(),
                        'screens' => ['All'],
                        'suggestion' => 'Please retry the analysis or contact support if the issue persists'
                    ]
                ],
                'positiveFindings' => []
            ];
        }
    }
}
