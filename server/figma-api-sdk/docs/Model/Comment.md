# # Comment

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**id** | **string** | Unique identifier for comment. |
**client_meta** | [**\OpenAPI\Client\Model\CommentClientMeta**](CommentClientMeta.md) |  |
**file_key** | **string** | The file in which the comment lives |
**parent_id** | **string** | If present, the id of the comment to which this is the reply | [optional]
**user** | [**\OpenAPI\Client\Model\User**](User.md) | The user who left the comment |
**created_at** | **\DateTime** | The UTC ISO 8601 time at which the comment was left |
**resolved_at** | **\DateTime** | If set, the UTC ISO 8601 time the comment was resolved | [optional]
**message** | **string** | The content of the comment |
**order_id** | **string** | Only set for top level comments. The number displayed with the comment in the UI |
**reactions** | [**\OpenAPI\Client\Model\Reaction[]**](Reaction.md) | An array of reactions to the comment |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
