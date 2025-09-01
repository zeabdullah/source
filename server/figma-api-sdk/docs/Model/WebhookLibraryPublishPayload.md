# # WebhookLibraryPublishPayload

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**passcode** | **string** | The passcode specified when the webhook was created, should match what was initially provided |
**timestamp** | **\DateTime** | UTC ISO 8601 timestamp of when the event was triggered. |
**webhook_id** | **string** | The id of the webhook that caused the callback |
**event_type** | **string** |  |
**created_components** | [**\OpenAPI\Client\Model\LibraryItemData[]**](LibraryItemData.md) | Components that were created by the library publish |
**created_styles** | [**\OpenAPI\Client\Model\LibraryItemData[]**](LibraryItemData.md) | Styles that were created by the library publish |
**created_variables** | [**\OpenAPI\Client\Model\LibraryItemData[]**](LibraryItemData.md) | Variables that were created by the library publish |
**modified_components** | [**\OpenAPI\Client\Model\LibraryItemData[]**](LibraryItemData.md) | Components that were modified by the library publish |
**modified_styles** | [**\OpenAPI\Client\Model\LibraryItemData[]**](LibraryItemData.md) | Styles that were modified by the library publish |
**modified_variables** | [**\OpenAPI\Client\Model\LibraryItemData[]**](LibraryItemData.md) | Variables that were modified by the library publish |
**deleted_components** | [**\OpenAPI\Client\Model\LibraryItemData[]**](LibraryItemData.md) | Components that were deleted by the library publish |
**deleted_styles** | [**\OpenAPI\Client\Model\LibraryItemData[]**](LibraryItemData.md) | Styles that were deleted by the library publish |
**deleted_variables** | [**\OpenAPI\Client\Model\LibraryItemData[]**](LibraryItemData.md) | Variables that were deleted by the library publish |
**description** | **string** | Description of the library publish | [optional]
**file_key** | **string** | The key of the file that was published |
**file_name** | **string** | The name of the file that was published |
**library_item** | [**\OpenAPI\Client\Model\LibraryItemData**](LibraryItemData.md) | The library item that was published |
**triggered_by** | [**\OpenAPI\Client\Model\User**](User.md) | The user that published the library and triggered this event |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
