# # LayoutGrid

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**pattern** | **string** | Orientation of the grid as a string enum  - &#x60;COLUMNS&#x60;: Vertical grid - &#x60;ROWS&#x60;: Horizontal grid - &#x60;GRID&#x60;: Square grid |
**section_size** | **float** | Width of column grid or height of row grid or square grid spacing. |
**visible** | **bool** | Is the grid currently visible? |
**color** | [**\OpenAPI\Client\Model\RGBA**](RGBA.md) | Color of the grid |
**alignment** | **string** | Positioning of grid as a string enum  - &#x60;MIN&#x60;: Grid starts at the left or top of the frame - &#x60;MAX&#x60;: Grid starts at the right or bottom of the frame - &#x60;STRETCH&#x60;: Grid is stretched to fit the frame - &#x60;CENTER&#x60;: Grid is center aligned |
**gutter_size** | **float** | Spacing in between columns and rows |
**offset** | **float** | Spacing before the first column or row |
**count** | **float** | Number of columns or rows |
**bound_variables** | [**\OpenAPI\Client\Model\LayoutGridBoundVariables**](LayoutGridBoundVariables.md) |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
