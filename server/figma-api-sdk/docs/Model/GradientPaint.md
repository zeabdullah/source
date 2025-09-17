# # GradientPaint

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**visible** | **bool** | Is the paint enabled? | [optional] [default to true]
**opacity** | **float** | Overall opacity of paint (colors within the paint can also have opacity values which would blend with this) | [optional] [default to 1]
**blend_mode** | [**\OpenAPI\Client\Model\BlendMode**](BlendMode.md) | How this node blends with nodes behind it in the scene |
**type** | **string** | The string literal representing the paint&#39;s type. Always check the &#x60;type&#x60; before reading other properties. |
**gradient_handle_positions** | [**\OpenAPI\Client\Model\Vector[]**](Vector.md) | This field contains three vectors, each of which are a position in normalized object space (normalized object space is if the top left corner of the bounding box of the object is (0, 0) and the bottom right is (1,1)). The first position corresponds to the start of the gradient (value 0 for the purposes of calculating gradient stops), the second position is the end of the gradient (value 1), and the third handle position determines the width of the gradient. |
**gradient_stops** | [**\OpenAPI\Client\Model\ColorStop[]**](ColorStop.md) | Positions of key points along the gradient axis with the colors anchored there. Colors along the gradient are interpolated smoothly between neighboring gradient stops. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
