# # SolidPaint

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**visible** | **bool** | Is the paint enabled? | [optional] [default to true]
**opacity** | **float** | Overall opacity of paint (colors within the paint can also have opacity values which would blend with this) | [optional] [default to 1]
**blend_mode** | [**\OpenAPI\Client\Model\BlendMode**](BlendMode.md) | How this node blends with nodes behind it in the scene |
**type** | **string** | The string literal \&quot;SOLID\&quot; representing the paint&#39;s type. Always check the &#x60;type&#x60; before reading other properties. |
**color** | [**\OpenAPI\Client\Model\RGBA**](RGBA.md) | Solid color of the paint |
**bound_variables** | [**\OpenAPI\Client\Model\SolidPaintAllOfBoundVariables**](SolidPaintAllOfBoundVariables.md) |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
