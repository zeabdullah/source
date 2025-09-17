# # HasGeometryTrait

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**fills** | [**\OpenAPI\Client\Model\Paint[]**](Paint.md) | An array of fill paints applied to the node. |
**styles** | **array<string,string>** | A mapping of a StyleType to style ID (see Style) of styles present on this node. The style ID can be used to look up more information about the style in the top-level styles field. | [optional]
**strokes** | [**\OpenAPI\Client\Model\Paint[]**](Paint.md) | An array of stroke paints applied to the node. | [optional]
**stroke_weight** | **float** | The weight of strokes on the node. | [optional] [default to 1]
**stroke_align** | **string** | Position of stroke relative to vector outline, as a string enum  - &#x60;INSIDE&#x60;: stroke drawn inside the shape boundary - &#x60;OUTSIDE&#x60;: stroke drawn outside the shape boundary - &#x60;CENTER&#x60;: stroke drawn centered along the shape boundary | [optional]
**stroke_join** | **string** | A string enum with value of \&quot;MITER\&quot;, \&quot;BEVEL\&quot;, or \&quot;ROUND\&quot;, describing how corners in vector paths are rendered. | [optional] [default to 'MITER']
**stroke_dashes** | **float[]** | An array of floating point numbers describing the pattern of dash length and gap lengths that the vector stroke will use when drawn.  For example a value of [1, 2] indicates that the stroke will be drawn with a dash of length 1 followed by a gap of length 2, repeated. | [optional]
**fill_override_table** | [**array<string,\OpenAPI\Client\Model\HasGeometryTraitAllOfFillOverrideTable>**](HasGeometryTraitAllOfFillOverrideTable.md) | Map from ID to PaintOverride for looking up fill overrides. To see which regions are overriden, you must use the &#x60;geometry&#x3D;paths&#x60; option. Each path returned may have an &#x60;overrideID&#x60; which maps to this table. | [optional]
**fill_geometry** | [**\OpenAPI\Client\Model\Path[]**](Path.md) | Only specified if parameter &#x60;geometry&#x3D;paths&#x60; is used. An array of paths representing the object fill. | [optional]
**stroke_geometry** | [**\OpenAPI\Client\Model\Path[]**](Path.md) | Only specified if parameter &#x60;geometry&#x3D;paths&#x60; is used. An array of paths representing the object stroke. | [optional]
**stroke_cap** | **string** | A string enum describing the end caps of vector paths. | [optional] [default to 'NONE']
**stroke_miter_angle** | **float** | Only valid if &#x60;strokeJoin&#x60; is \&quot;MITER\&quot;. The corner angle, in degrees, below which &#x60;strokeJoin&#x60; will be set to \&quot;BEVEL\&quot; to avoid super sharp corners. By default this is 28.96 degrees. | [optional] [default to 28.96]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
