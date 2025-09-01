# # WebhookV2

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**id** | **string** | The ID of the webhook |
**event_type** | [**\OpenAPI\Client\Model\WebhookV2Event**](WebhookV2Event.md) | The event this webhook triggers on |
**team_id** | **string** | The team id you are subscribed to for updates. This is deprecated, use context and context_id instead |
**context** | **string** | The type of context this webhook is attached to. The value will be \&quot;PROJECT\&quot;, \&quot;TEAM\&quot;, or \&quot;FILE\&quot; |
**context_id** | **string** | The ID of the context this webhook is attached to |
**plan_api_id** | **string** | The plan API ID of the team or organization where this webhook was created |
**status** | [**\OpenAPI\Client\Model\WebhookV2Status**](WebhookV2Status.md) | The current status of the webhook |
**client_id** | **string** | The client ID of the OAuth application that registered this webhook, if any |
**passcode** | **string** | The passcode that will be passed back to the webhook endpoint. For security, when using the GET endpoints, the value is an empty string |
**endpoint** | **string** | The endpoint that will be hit when the webhook is triggered |
**description** | **string** | Optional user-provided description or name for the webhook. This is provided to help make maintaining a number of webhooks more convenient. Max length 140 characters. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
