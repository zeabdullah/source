# # PublishedComponent

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**key** | **string** | The unique identifier for the component. |
**file_key** | **string** | The unique identifier of the Figma file that contains the component. |
**node_id** | **string** | The unique identifier of the component node within the Figma file. |
**thumbnail_url** | **string** | A URL to a thumbnail image of the component. | [optional]
**name** | **string** | The name of the component. |
**description** | **string** | The description of the component as entered by the publisher. |
**created_at** | **\DateTime** | The UTC ISO 8601 time when the component was created. |
**updated_at** | **\DateTime** | The UTC ISO 8601 time when the component was last updated. |
**user** | [**\OpenAPI\Client\Model\User**](User.md) | The user who last updated the component. |
**containing_frame** | [**\OpenAPI\Client\Model\FrameInfo**](FrameInfo.md) | The containing frame of the component. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
