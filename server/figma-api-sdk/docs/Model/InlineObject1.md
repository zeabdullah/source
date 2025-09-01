# # InlineObject1

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**name** | **string** | The name of the file as it appears in the editor. |
**role** | **string** | The role of the user making the API request in relation to the file. |
**last_modified** | **\DateTime** | The UTC ISO 8601 time at which the file was last modified. |
**editor_type** | **string** | The type of editor associated with this file. |
**thumbnail_url** | **string** | A URL to a thumbnail image of the file. |
**version** | **string** | The version number of the file. This number is incremented when a file is modified and can be used to check if the file has changed between requests. |
**nodes** | [**array<string,\OpenAPI\Client\Model\InlineObject1NodesValue>**](InlineObject1NodesValue.md) | A mapping from node IDs to node metadata. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
