# # PublishedStyle

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**key** | **string** | The unique identifier for the style |
**file_key** | **string** | The unique identifier of the Figma file that contains the style. |
**node_id** | **string** | ID of the style node within the figma file |
**style_type** | [**\OpenAPI\Client\Model\StyleType**](StyleType.md) |  |
**thumbnail_url** | **string** | A URL to a thumbnail image of the style. | [optional]
**name** | **string** | The name of the style. |
**description** | **string** | The description of the style as entered by the publisher. |
**created_at** | **\DateTime** | The UTC ISO 8601 time when the style was created. |
**updated_at** | **\DateTime** | The UTC ISO 8601 time when the style was last updated. |
**user** | [**\OpenAPI\Client\Model\User**](User.md) | The user who last updated the style. |
**sort_position** | **string** | A user specified order number by which the style can be sorted. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
