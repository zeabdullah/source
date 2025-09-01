# # PatternPaint

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**visible** | **bool** | Is the paint enabled? | [optional] [default to true]
**opacity** | **float** | Overall opacity of paint (colors within the paint can also have opacity values which would blend with this) | [optional] [default to 1]
**blend_mode** | [**\OpenAPI\Client\Model\BlendMode**](BlendMode.md) | How this node blends with nodes behind it in the scene |
**type** | **string** | The string literal \&quot;PATTERN\&quot; representing the paint&#39;s type. Always check the &#x60;type&#x60; before reading other properties. |
**source_node_id** | **string** | The node id of the source node for the pattern |
**tile_type** | **string** | The tile type for the pattern |
**scaling_factor** | **float** | The scaling factor for the pattern |
**spacing** | [**\OpenAPI\Client\Model\Vector**](Vector.md) | The spacing for the pattern |
**horizontal_alignment** | **string** | The horizontal alignment for the pattern |
**vertical_alignment** | **string** | The vertical alignment for the pattern |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
