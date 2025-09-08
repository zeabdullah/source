# # MinimalStrokesTrait

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**strokes** | [**\OpenAPI\Client\Model\Paint[]**](Paint.md) | An array of stroke paints applied to the node. | [optional]
**stroke_weight** | **float** | The weight of strokes on the node. | [optional] [default to 1]
**stroke_align** | **string** | Position of stroke relative to vector outline, as a string enum  - &#x60;INSIDE&#x60;: stroke drawn inside the shape boundary - &#x60;OUTSIDE&#x60;: stroke drawn outside the shape boundary - &#x60;CENTER&#x60;: stroke drawn centered along the shape boundary | [optional]
**stroke_join** | **string** | A string enum with value of \&quot;MITER\&quot;, \&quot;BEVEL\&quot;, or \&quot;ROUND\&quot;, describing how corners in vector paths are rendered. | [optional] [default to 'MITER']
**stroke_dashes** | **float[]** | An array of floating point numbers describing the pattern of dash length and gap lengths that the vector stroke will use when drawn.  For example a value of [1, 2] indicates that the stroke will be drawn with a dash of length 1 followed by a gap of length 2, repeated. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
