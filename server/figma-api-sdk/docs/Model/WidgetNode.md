# # WidgetNode

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**id** | **string** | A string uniquely identifying this node within the document. |
**name** | **string** | The name given to the node by the user in the tool. |
**type** | **string** | The type of this node, represented by the string literal \&quot;WIDGET\&quot; |
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
**export_settings** | [**\OpenAPI\Client\Model\ExportSetting[]**](ExportSetting.md) | An array of export settings representing images to export from the node. | [optional]
**children** | [**\OpenAPI\Client\Model\SubcanvasNode[]**](SubcanvasNode.md) | An array of nodes that are direct children of this node |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
