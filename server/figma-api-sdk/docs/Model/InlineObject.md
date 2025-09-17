# # InlineObject

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**name** | **string** | The name of the file as it appears in the editor. |
**role** | **string** | The role of the user making the API request in relation to the file. |
**last_modified** | **\DateTime** | The UTC ISO 8601 time at which the file was last modified. |
**editor_type** | **string** | The type of editor associated with this file. |
**thumbnail_url** | **string** | A URL to a thumbnail image of the file. | [optional]
**version** | **string** | The version number of the file. This number is incremented when a file is modified and can be used to check if the file has changed between requests. |
**document** | [**\OpenAPI\Client\Model\DocumentNode**](DocumentNode.md) |  |
**components** | [**array<string,\OpenAPI\Client\Model\Component>**](Component.md) | A mapping from component IDs to component metadata. |
**component_sets** | [**array<string,\OpenAPI\Client\Model\ComponentSet>**](ComponentSet.md) | A mapping from component set IDs to component set metadata. |
**schema_version** | **float** | The version of the file schema that this file uses. | [default to 0]
**styles** | [**array<string,\OpenAPI\Client\Model\Style>**](Style.md) | A mapping from style IDs to style metadata. |
**link_access** | **string** | The share permission level of the file link. | [optional]
**main_file_key** | **string** | The key of the main file for this file. If present, this file is a component or component set. | [optional]
**branches** | [**\OpenAPI\Client\Model\InlineObjectBranchesInner[]**](InlineObjectBranchesInner.md) | A list of branches for this file. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
