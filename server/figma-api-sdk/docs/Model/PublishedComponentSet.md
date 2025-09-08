# # PublishedComponentSet

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**key** | **string** | The unique identifier for the component set. |
**file_key** | **string** | The unique identifier of the Figma file that contains the component set. |
**node_id** | **string** | The unique identifier of the component set node within the Figma file. |
**thumbnail_url** | **string** | A URL to a thumbnail image of the component set. | [optional]
**name** | **string** | The name of the component set. |
**description** | **string** | The description of the component set as entered by the publisher. |
**created_at** | **\DateTime** | The UTC ISO 8601 time when the component set was created. |
**updated_at** | **\DateTime** | The UTC ISO 8601 time when the component set was last updated. |
**user** | [**\OpenAPI\Client\Model\User**](User.md) | The user who last updated the component set. |
**containing_frame** | [**\OpenAPI\Client\Model\FrameInfo**](FrameInfo.md) | The containing frame of the component set. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
