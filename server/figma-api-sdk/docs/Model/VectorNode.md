# # VectorNode

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**id** | **string** | A string uniquely identifying this node within the document. |
**name** | **string** | The name given to the node by the user in the tool. |
**type** | **string** | The type of this node, represented by the string literal \&quot;VECTOR\&quot; |
**visible** | **bool** | Whether or not the node is visible on the canvas. | [optional] [default to true]
**locked** | **bool** | If true, layer is locked and cannot be edited | [optional] [default to false]
**is_fixed** | **bool** | Whether the layer is fixed while the parent is scrolling | [optional] [default to false]
**scroll_behavior** | **string** | How layer should be treated when the frame is resized | [default to 'SCROLLS']
**rotation** | **float** | The rotation of the node, if not 0. | [optional] [default to 0]
**component_property_references** | **array<string,string>** | A mapping of a layer&#39;s property to component property name of component properties attached to this node. The component property name can be used to look up more information on the corresponding component&#39;s or component set&#39;s componentPropertyDefinitions. | [optional]
**plugin_data** | **mixed** |  | [optional]
**shared_plugin_data** | **mixed** |  | [optional]
**bound_variables** | [**\OpenAPI\Client\Model\IsLayerTraitBoundVariables**](IsLayerTraitBoundVariables.md) |  | [optional]
**explicit_variable_modes** | **array<string,string>** | A mapping of variable collection ID to mode ID representing the explicitly set modes for this node. | [optional]
**blend_mode** | [**\OpenAPI\Client\Model\BlendMode**](BlendMode.md) | How this node blends with nodes behind it in the scene (see blend mode section for more details) |
**opacity** | **float** | Opacity of the node | [optional] [default to 1]
**absolute_bounding_box** | [**\OpenAPI\Client\Model\Rectangle**](Rectangle.md) |  |
**absolute_render_bounds** | [**\OpenAPI\Client\Model\Rectangle**](Rectangle.md) |  |
**preserve_ratio** | **bool** | Keep height and width constrained to same ratio. | [optional] [default to false]
**constraints** | [**\OpenAPI\Client\Model\LayoutConstraint**](LayoutConstraint.md) | Horizontal and vertical layout constraints for node. | [optional]
**relative_transform** | **float[][]** | A transformation matrix is standard way in computer graphics to represent translation and rotation. These are the top two rows of a 3x3 matrix. The bottom row of the matrix is assumed to be [0, 0, 1]. This is known as an affine transform and is enough to represent translation, rotation, and skew.  The identity transform is [[1, 0, 0], [0, 1, 0]].  A translation matrix will typically look like:  &#x60;&#x60;&#x60; [[1, 0, tx],   [0, 1, ty]] &#x60;&#x60;&#x60;  and a rotation matrix will typically look like:  &#x60;&#x60;&#x60; [[cos(angle), sin(angle), 0],   [-sin(angle), cos(angle), 0]] &#x60;&#x60;&#x60;  Another way to think about this transform is as three vectors:  - The x axis (t[0][0], t[1][0]) - The y axis (t[0][1], t[1][1]) - The translation offset (t[0][2], t[1][2])  The most common usage of the Transform matrix is the &#x60;relativeTransform property&#x60;. This particular usage of the matrix has a few additional restrictions. The translation offset can take on any value but we do enforce that the axis vectors are unit vectors (i.e. have length 1). The axes are not required to be at 90° angles to each other. | [optional]
**size** | [**\OpenAPI\Client\Model\Vector**](Vector.md) | Width and height of element. This is different from the width and height of the bounding box in that the absolute bounding box represents the element after scaling and rotation. Only present if &#x60;geometry&#x3D;paths&#x60; is passed. | [optional]
**layout_align** | **string** | Determines if the layer should stretch along the parent&#39;s counter axis. This property is only provided for direct children of auto-layout frames.  - &#x60;INHERIT&#x60; - &#x60;STRETCH&#x60;  In previous versions of auto layout, determined how the layer is aligned inside an auto-layout frame. This property is only provided for direct children of auto-layout frames.  - &#x60;MIN&#x60; - &#x60;CENTER&#x60; - &#x60;MAX&#x60; - &#x60;STRETCH&#x60;  In horizontal auto-layout frames, \&quot;MIN\&quot; and \&quot;MAX\&quot; correspond to \&quot;TOP\&quot; and \&quot;BOTTOM\&quot;. In vertical auto-layout frames, \&quot;MIN\&quot; and \&quot;MAX\&quot; correspond to \&quot;LEFT\&quot; and \&quot;RIGHT\&quot;. | [optional]
**layout_grow** | **float** | This property is applicable only for direct children of auto-layout frames, ignored otherwise. Determines whether a layer should stretch along the parent&#39;s primary axis. A &#x60;0&#x60; corresponds to a fixed size and &#x60;1&#x60; corresponds to stretch. | [optional] [default to self::LAYOUT_GROW_NUMBER_0]
**layout_positioning** | **string** | Determines whether a layer&#39;s size and position should be determined by auto-layout settings or manually adjustable. | [optional] [default to 'AUTO']
**min_width** | **float** | The minimum width of the frame. This property is only applicable for auto-layout frames or direct children of auto-layout frames. | [optional] [default to 0]
**max_width** | **float** | The maximum width of the frame. This property is only applicable for auto-layout frames or direct children of auto-layout frames. | [optional] [default to 0]
**min_height** | **float** | The minimum height of the frame. This property is only applicable for auto-layout frames or direct children of auto-layout frames. | [optional] [default to 0]
**max_height** | **float** | The maximum height of the frame. This property is only applicable for auto-layout frames or direct children of auto-layout frames. | [optional] [default to 0]
**layout_sizing_horizontal** | **string** | The horizontal sizing setting on this auto-layout frame or frame child. - &#x60;FIXED&#x60; - &#x60;HUG&#x60;: only valid on auto-layout frames and text nodes - &#x60;FILL&#x60;: only valid on auto-layout frame children | [optional]
**layout_sizing_vertical** | **string** | The vertical sizing setting on this auto-layout frame or frame child. - &#x60;FIXED&#x60; - &#x60;HUG&#x60;: only valid on auto-layout frames and text nodes - &#x60;FILL&#x60;: only valid on auto-layout frame children | [optional]
**grid_row_count** | **float** | The number of rows in the grid layout. This property is only applicable for auto-layout frames with &#x60;layoutMode: \&quot;GRID\&quot;&#x60;. | [optional]
**grid_column_count** | **float** | The number of columns in the grid layout. This property is only applicable for auto-layout frames with &#x60;layoutMode: \&quot;GRID\&quot;&#x60;. | [optional]
**grid_row_gap** | **float** | The distance between rows in the grid layout. This property is only applicable for auto-layout frames with &#x60;layoutMode: \&quot;GRID\&quot;&#x60;. | [optional] [default to 0]
**grid_column_gap** | **float** | The distance between columns in the grid layout. This property is only applicable for auto-layout frames with &#x60;layoutMode: \&quot;GRID\&quot;&#x60;. | [optional] [default to 0]
**grid_columns_sizing** | **string** | The string for the CSS grid-template-columns property. This property is only applicable for auto-layout frames with &#x60;layoutMode: \&quot;GRID\&quot;&#x60;. | [optional]
**grid_rows_sizing** | **string** | The string for the CSS grid-template-rows property. This property is only applicable for auto-layout frames with &#x60;layoutMode: \&quot;GRID\&quot;&#x60;. | [optional]
**grid_child_horizontal_align** | **string** | Determines how a GRID frame&#39;s child should be aligned in the horizontal direction within its grid area. This property is only applicable for direct children of frames with &#x60;layoutMode: \&quot;GRID\&quot;&#x60;. | [optional]
**grid_child_vertical_align** | **string** | Determines how a GRID frame&#39;s child should be aligned in the vertical direction within its grid area. This property is only applicable for direct children of frames with &#x60;layoutMode: \&quot;GRID\&quot;&#x60;. | [optional]
**grid_row_span** | **float** | The number of rows that a GRID frame&#39;s child should span. This property is only applicable for direct children of frames with &#x60;layoutMode: \&quot;GRID\&quot;&#x60;. | [optional] [default to 1]
**grid_column_span** | **float** | The number of columns that a GRID frame&#39;s child should span. This property is only applicable for direct children of frames with &#x60;layoutMode: \&quot;GRID\&quot;&#x60;. | [optional] [default to 1]
**grid_row_anchor_index** | **float** | The index of the row that a GRID frame&#39;s child should be anchored to. This property is only applicable for direct children of frames with &#x60;layoutMode: \&quot;GRID\&quot;&#x60;. | [optional] [default to 0]
**grid_column_anchor_index** | **float** | The index of the column that a GRID frame&#39;s child should be anchored to. This property is only applicable for direct children of frames with &#x60;layoutMode: \&quot;GRID\&quot;&#x60;. | [optional] [default to 0]
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
**export_settings** | [**\OpenAPI\Client\Model\ExportSetting[]**](ExportSetting.md) | An array of export settings representing images to export from the node. | [optional]
**effects** | [**\OpenAPI\Client\Model\Effect[]**](Effect.md) | An array of effects attached to this node (see effects section for more details) |
**is_mask** | **bool** | Does this node mask sibling nodes in front of it? | [optional] [default to false]
**mask_type** | **string** | If this layer is a mask, this property describes the operation used to mask the layer&#39;s siblings. The value may be one of the following:  - ALPHA: the mask node&#39;s alpha channel will be used to determine the opacity of each pixel in the masked result. - VECTOR: if the mask node has visible fill paints, every pixel inside the node&#39;s fill regions will be fully visible in the masked result. If the mask has visible stroke paints, every pixel inside the node&#39;s stroke regions will be fully visible in the masked result. - LUMINANCE: the luminance value of each pixel of the mask node will be used to determine the opacity of that pixel in the masked result. | [optional]
**is_mask_outline** | **bool** | True if maskType is VECTOR. This field is deprecated; use maskType instead. | [optional] [default to false]
**transition_node_id** | **string** | Node ID of node to transition to in prototyping | [optional]
**transition_duration** | **float** | The duration of the prototyping transition on this node (in milliseconds). This will override the default transition duration on the prototype, for this node. | [optional]
**transition_easing** | [**\OpenAPI\Client\Model\EasingType**](EasingType.md) | The easing curve used in the prototyping transition on this node. | [optional]
**interactions** | [**\OpenAPI\Client\Model\Interaction[]**](Interaction.md) |  | [optional]
**corner_radius** | **float** | Radius of each corner if a single radius is set for all corners | [optional] [default to 0]
**corner_smoothing** | **float** | A value that lets you control how \&quot;smooth\&quot; the corners are. Ranges from 0 to 1. 0 is the default and means that the corner is perfectly circular. A value of 0.6 means the corner matches the iOS 7 \&quot;squircle\&quot; icon shape. Other values produce various other curves. | [optional]
**rectangle_corner_radii** | **float[]** | Array of length 4 of the radius of each corner of the frame, starting in the top left and proceeding clockwise.  Values are given in the order top-left, top-right, bottom-right, bottom-left. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
