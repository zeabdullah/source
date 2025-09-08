# # Effect

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**type** | **string** | A string literal representing the effect&#39;s type. Always check the type before reading other properties. |
**show_shadow_behind_node** | **bool** | Whether to show the shadow behind translucent or transparent pixels | [default to false]
**color** | [**\OpenAPI\Client\Model\RGBA**](RGBA.md) | The color of the noise effect |
**blend_mode** | [**\OpenAPI\Client\Model\BlendMode**](BlendMode.md) | Blend mode of the noise effect |
**offset** | [**\OpenAPI\Client\Model\Vector**](Vector.md) | How far the shadow is projected in the x and y directions |
**radius** | **float** | The radius of the texture effect |
**spread** | **float** | The distance by which to expand (or contract) the shadow.  For drop shadows, a positive &#x60;spread&#x60; value creates a shadow larger than the node, whereas a negative value creates a shadow smaller than the node.  For inner shadows, a positive &#x60;spread&#x60; value contracts the shadow. Spread values are only accepted on rectangles and ellipses, or on frames, components, and instances with visible fill paints and &#x60;clipsContent&#x60; enabled. When left unspecified, the default value is 0. | [optional] [default to 0]
**visible** | **bool** | Whether the noise effect is visible. |
**bound_variables** | [**\OpenAPI\Client\Model\BaseBlurEffectBoundVariables**](BaseBlurEffectBoundVariables.md) |  | [optional]
**blur_type** | **string** | The string literal &#39;PROGRESSIVE&#39; representing the blur type. Always check the blurType before reading other properties. |
**start_radius** | **float** | The starting radius of the progressive blur |
**start_offset** | [**\OpenAPI\Client\Model\Vector**](Vector.md) | The starting offset of the progressive blur |
**end_offset** | [**\OpenAPI\Client\Model\Vector**](Vector.md) | The ending offset of the progressive blur |
**noise_size** | **float** | The size of the noise effect |
**clip_to_shape** | **bool** | Whether the texture is clipped to the shape |
**noise_type** | **string** | The string literal &#39;DUOTONE&#39; representing the noise type. |
**density** | **float** | The density of the noise effect |
**opacity** | **float** | The opacity of the noise effect |
**secondary_color** | [**\OpenAPI\Client\Model\RGBA**](RGBA.md) | The secondary color of the noise effect |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
