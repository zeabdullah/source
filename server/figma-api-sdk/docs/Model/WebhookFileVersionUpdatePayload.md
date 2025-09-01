# # WebhookFileVersionUpdatePayload

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**passcode** | **string** | The passcode specified when the webhook was created, should match what was initially provided |
**timestamp** | **\DateTime** | UTC ISO 8601 timestamp of when the event was triggered. |
**webhook_id** | **string** | The id of the webhook that caused the callback |
**event_type** | **string** |  |
**created_at** | **\DateTime** | UTC ISO 8601 timestamp of when the version was created |
**description** | **string** | Description of the version in the version history | [optional]
**file_key** | **string** | The key of the file that was updated |
**file_name** | **string** | The name of the file that was updated |
**triggered_by** | [**\OpenAPI\Client\Model\User**](User.md) | The user that created the named version and triggered this event |
**version_id** | **string** | ID of the published version |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
