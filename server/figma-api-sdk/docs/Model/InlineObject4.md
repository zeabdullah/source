# # InlineObject4

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**name** | **string** | The name of the file. |
**folder_name** | **string** | The name of the project containing the file. | [optional]
**last_touched_at** | **\DateTime** | The UTC ISO 8601 time at which the file content was last modified. |
**creator** | [**\OpenAPI\Client\Model\User**](User.md) | The user who created the file. |
**last_touched_by** | [**\OpenAPI\Client\Model\User**](User.md) | The user who last modified the file contents. | [optional]
**thumbnail_url** | **string** | A URL to a thumbnail image of the file. | [optional]
**editor_type** | **string** | The type of editor associated with this file. |
**role** | **string** | The role of the user making the API request in relation to the file. | [optional]
**link_access** | **string** | Access policy for users who have the link to the file. | [optional]
**url** | **string** | The URL of the file. | [optional]
**version** | **string** | The version number of the file. This number is incremented when a file is modified and can be used to check if the file has changed between requests. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
