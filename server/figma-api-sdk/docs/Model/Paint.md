# # Paint

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**type** | **string** | The string literal \&quot;SOLID\&quot; representing the paint&#39;s type. Always check the &#x60;type&#x60; before reading other properties. |
**color** | [**\OpenAPI\Client\Model\RGBA**](RGBA.md) | Solid color of the paint |
**bound_variables** | [**\OpenAPI\Client\Model\SolidPaintAllOfBoundVariables**](SolidPaintAllOfBoundVariables.md) |  | [optional]
**visible** | **bool** | Is the paint enabled? | [optional] [default to true]
**opacity** | **float** | Overall opacity of paint (colors within the paint can also have opacity values which would blend with this) | [optional] [default to 1]
**blend_mode** | [**\OpenAPI\Client\Model\BlendMode**](BlendMode.md) | How this node blends with nodes behind it in the scene |
**gradient_handle_positions** | [**\OpenAPI\Client\Model\Vector[]**](Vector.md) | This field contains three vectors, each of which are a position in normalized object space (normalized object space is if the top left corner of the bounding box of the object is (0, 0) and the bottom right is (1,1)). The first position corresponds to the start of the gradient (value 0 for the purposes of calculating gradient stops), the second position is the end of the gradient (value 1), and the third handle position determines the width of the gradient. |
**gradient_stops** | [**\OpenAPI\Client\Model\ColorStop[]**](ColorStop.md) | Positions of key points along the gradient axis with the colors anchored there. Colors along the gradient are interpolated smoothly between neighboring gradient stops. |
**scale_mode** | **string** | Image scaling mode. |
**image_ref** | **string** | A reference to an image embedded in this node. To download the image using this reference, use the &#x60;GET file images&#x60; endpoint to retrieve the mapping from image references to image URLs. |
**image_transform** | **float[][]** | A transformation matrix is standard way in computer graphics to represent translation and rotation. These are the top two rows of a 3x3 matrix. The bottom row of the matrix is assumed to be [0, 0, 1]. This is known as an affine transform and is enough to represent translation, rotation, and skew.  The identity transform is [[1, 0, 0], [0, 1, 0]].  A translation matrix will typically look like:  &#x60;&#x60;&#x60; [[1, 0, tx],   [0, 1, ty]] &#x60;&#x60;&#x60;  and a rotation matrix will typically look like:  &#x60;&#x60;&#x60; [[cos(angle), sin(angle), 0],   [-sin(angle), cos(angle), 0]] &#x60;&#x60;&#x60;  Another way to think about this transform is as three vectors:  - The x axis (t[0][0], t[1][0]) - The y axis (t[0][1], t[1][1]) - The translation offset (t[0][2], t[1][2])  The most common usage of the Transform matrix is the &#x60;relativeTransform property&#x60;. This particular usage of the matrix has a few additional restrictions. The translation offset can take on any value but we do enforce that the axis vectors are unit vectors (i.e. have length 1). The axes are not required to be at 90° angles to each other. | [optional]
**scaling_factor** | **float** | The scaling factor for the pattern |
**filters** | [**\OpenAPI\Client\Model\ImageFilters**](ImageFilters.md) | Defines what image filters have been applied to this paint, if any. If this property is not defined, no filters have been applied. | [optional]
**rotation** | **float** | Image rotation, in degrees. | [optional] [default to 0]
**gif_ref** | **string** | A reference to an animated GIF embedded in this node. To download the image using this reference, use the &#x60;GET file images&#x60; endpoint to retrieve the mapping from image references to image URLs. | [optional]
**source_node_id** | **string** | The node id of the source node for the pattern |
**tile_type** | **string** | The tile type for the pattern |
**spacing** | [**\OpenAPI\Client\Model\Vector**](Vector.md) | The spacing for the pattern |
**horizontal_alignment** | **string** | The horizontal alignment for the pattern |
**vertical_alignment** | **string** | The vertical alignment for the pattern |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
