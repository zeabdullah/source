# # PostCommentRequestClientMeta

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**x** | **float** | X coordinate of the position. |
**y** | **float** | Y coordinate of the position. |
**node_id** | **string** | Unique id specifying the frame. |
**node_offset** | [**\OpenAPI\Client\Model\Vector**](Vector.md) | 2D vector offset within the frame from the top-left corner. |
**region_height** | **float** | The height of the comment region. Must be greater than 0. |
**region_width** | **float** | The width of the comment region. Must be greater than 0. |
**comment_pin_corner** | **string** | The corner of the comment region to pin to the node&#39;s corner as a string enum. | [optional] [default to 'bottom-right']

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
