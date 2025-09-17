# # WebhookFileDeletePayload

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**passcode** | **string** | The passcode specified when the webhook was created, should match what was initially provided |
**timestamp** | **\DateTime** | UTC ISO 8601 timestamp of when the event was triggered. |
**webhook_id** | **string** | The id of the webhook that caused the callback |
**event_type** | **string** |  |
**file_key** | **string** | The key of the file that was deleted |
**file_name** | **string** | The name of the file that was deleted |
**triggered_by** | [**\OpenAPI\Client\Model\User**](User.md) | The user that deleted the file and triggered this event |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
