# # WebhookDevModeStatusUpdatePayload

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**passcode** | **string** | The passcode specified when the webhook was created, should match what was initially provided |
**timestamp** | **\DateTime** | UTC ISO 8601 timestamp of when the event was triggered. |
**webhook_id** | **string** | The id of the webhook that caused the callback |
**event_type** | **string** |  |
**file_key** | **string** | The key of the file that was updated |
**file_name** | **string** | The name of the file that was updated |
**node_id** | **string** | The id of the node where the Dev Mode status changed. For example, \&quot;43:2\&quot; |
**related_links** | [**\OpenAPI\Client\Model\DevResource[]**](DevResource.md) | An array of related links that have been applied to the layer in the file |
**status** | **string** | The Dev Mode status. Either \&quot;NONE\&quot;, \&quot;READY_FOR_DEV\&quot;, or \&quot;COMPLETED\&quot; |
**triggered_by** | [**\OpenAPI\Client\Model\User**](User.md) | The user that made the status change and triggered the event |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
