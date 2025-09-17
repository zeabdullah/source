# # HasFramePropertiesTrait

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**clips_content** | **bool** | Whether or not this node clip content outside of its bounds |
**background** | [**\OpenAPI\Client\Model\Paint[]**](Paint.md) | Background of the node. This is deprecated, as backgrounds for frames are now in the &#x60;fills&#x60; field. | [optional]
**background_color** | [**\OpenAPI\Client\Model\RGBA**](RGBA.md) | Background color of the node. This is deprecated, as frames now support more than a solid color as a background. Please use the &#x60;fills&#x60; field instead. | [optional]
**layout_grids** | [**\OpenAPI\Client\Model\LayoutGrid[]**](LayoutGrid.md) | An array of layout grids attached to this node (see layout grids section for more details). GROUP nodes do not have this attribute | [optional]
**overflow_direction** | **string** | Whether a node has primary axis scrolling, horizontal or vertical. | [optional] [default to 'NONE']
**layout_mode** | **string** | Whether this layer uses auto-layout to position its children. | [optional] [default to 'NONE']
**primary_axis_sizing_mode** | **string** | Whether the primary axis has a fixed length (determined by the user) or an automatic length (determined by the layout engine). This property is only applicable for auto-layout frames. | [optional] [default to 'AUTO']
**counter_axis_sizing_mode** | **string** | Whether the counter axis has a fixed length (determined by the user) or an automatic length (determined by the layout engine). This property is only applicable for auto-layout frames. | [optional] [default to 'AUTO']
**primary_axis_align_items** | **string** | Determines how the auto-layout frame&#39;s children should be aligned in the primary axis direction. This property is only applicable for auto-layout frames. | [optional] [default to 'MIN']
**counter_axis_align_items** | **string** | Determines how the auto-layout frame&#39;s children should be aligned in the counter axis direction. This property is only applicable for auto-layout frames. | [optional] [default to 'MIN']
**padding_left** | **float** | The padding between the left border of the frame and its children. This property is only applicable for auto-layout frames. | [optional] [default to 0]
**padding_right** | **float** | The padding between the right border of the frame and its children. This property is only applicable for auto-layout frames. | [optional] [default to 0]
**padding_top** | **float** | The padding between the top border of the frame and its children. This property is only applicable for auto-layout frames. | [optional] [default to 0]
**padding_bottom** | **float** | The padding between the bottom border of the frame and its children. This property is only applicable for auto-layout frames. | [optional] [default to 0]
**item_spacing** | **float** | The distance between children of the frame. Can be negative. This property is only applicable for auto-layout frames. | [optional] [default to 0]
**item_reverse_z_index** | **bool** | Determines the canvas stacking order of layers in this frame. When true, the first layer will be draw on top. This property is only applicable for auto-layout frames. | [optional] [default to false]
**strokes_included_in_layout** | **bool** | Determines whether strokes are included in layout calculations. When true, auto-layout frames behave like css \&quot;box-sizing: border-box\&quot;. This property is only applicable for auto-layout frames. | [optional] [default to false]
**layout_wrap** | **string** | Whether this auto-layout frame has wrapping enabled. | [optional]
**counter_axis_spacing** | **float** | The distance between wrapped tracks of an auto-layout frame. This property is only applicable for auto-layout frames with &#x60;layoutWrap: \&quot;WRAP\&quot;&#x60; | [optional]
**counter_axis_align_content** | **string** | Determines how the auto-layout frame’s wrapped tracks should be aligned in the counter axis direction. This property is only applicable for auto-layout frames with &#x60;layoutWrap: \&quot;WRAP\&quot;&#x60;. | [optional] [default to 'AUTO']

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
