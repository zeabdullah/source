# # InnerShadowEffect

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**color** | [**\OpenAPI\Client\Model\RGBA**](RGBA.md) | The color of the shadow |
**blend_mode** | [**\OpenAPI\Client\Model\BlendMode**](BlendMode.md) | Blend mode of the shadow |
**offset** | [**\OpenAPI\Client\Model\Vector**](Vector.md) | How far the shadow is projected in the x and y directions |
**radius** | **float** | Radius of the blur effect (applies to shadows as well) |
**spread** | **float** | The distance by which to expand (or contract) the shadow.  For drop shadows, a positive &#x60;spread&#x60; value creates a shadow larger than the node, whereas a negative value creates a shadow smaller than the node.  For inner shadows, a positive &#x60;spread&#x60; value contracts the shadow. Spread values are only accepted on rectangles and ellipses, or on frames, components, and instances with visible fill paints and &#x60;clipsContent&#x60; enabled. When left unspecified, the default value is 0. | [optional] [default to 0]
**visible** | **bool** | Whether this shadow is visible. |
**bound_variables** | [**\OpenAPI\Client\Model\BaseShadowEffectBoundVariables**](BaseShadowEffectBoundVariables.md) |  | [optional]
**type** | **string** | A string literal representing the effect&#39;s type. Always check the type before reading other properties. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
