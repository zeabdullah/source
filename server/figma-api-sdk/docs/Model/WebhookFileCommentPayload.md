# # WebhookFileCommentPayload

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**passcode** | **string** | The passcode specified when the webhook was created, should match what was initially provided |
**timestamp** | **\DateTime** | UTC ISO 8601 timestamp of when the event was triggered. |
**webhook_id** | **string** | The id of the webhook that caused the callback |
**event_type** | **string** |  |
**comment** | [**\OpenAPI\Client\Model\CommentFragment[]**](CommentFragment.md) | Contents of the comment itself |
**comment_id** | **string** | Unique identifier for comment |
**created_at** | **\DateTime** | The UTC ISO 8601 time at which the comment was left |
**file_key** | **string** | The key of the file that was commented on |
**file_name** | **string** | The name of the file that was commented on |
**mentions** | [**\OpenAPI\Client\Model\User[]**](User.md) | Users that were mentioned in the comment | [optional]
**triggered_by** | [**\OpenAPI\Client\Model\User**](User.md) | The user that made the comment and triggered this event |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
