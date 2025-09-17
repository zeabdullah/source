# # PostWebhookRequest

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**event_type** | [**\OpenAPI\Client\Model\WebhookV2Event**](WebhookV2Event.md) |  |
**team_id** | **string** | Team id to receive updates about. This is deprecated, use &#39;context&#39; and &#39;context_id&#39; instead. | [optional]
**context** | **string** | Context to create the webhook for. Must be \&quot;team\&quot;, \&quot;project\&quot;, or \&quot;file\&quot;. |
**context_id** | **string** | The id of the context you want to receive updates about. |
**endpoint** | **string** | The HTTP endpoint that will receive a POST request when the event triggers. Max length 2048 characters. |
**passcode** | **string** | String that will be passed back to your webhook endpoint to verify that it is being called by Figma. Max length 100 characters. |
**status** | [**\OpenAPI\Client\Model\WebhookV2Status**](WebhookV2Status.md) | State of the webhook, including any error state it may be in | [optional]
**description** | **string** | User provided description or name for the webhook. Max length 150 characters. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
