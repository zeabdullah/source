# # LocalVariable

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**id** | **string** | The unique identifier of this variable. |
**name** | **string** | The name of this variable. |
**key** | **string** | The key of this variable. |
**variable_collection_id** | **string** | The id of the variable collection that contains this variable. |
**resolved_type** | **string** | The resolved type of the variable. |
**values_by_mode** | [**array<string,\OpenAPI\Client\Model\LocalVariableValuesByModeValue>**](LocalVariableValuesByModeValue.md) | The values for each mode of this variable. |
**remote** | **bool** | Whether this variable is remote. |
**description** | **string** | The description of this variable. |
**hidden_from_publishing** | **bool** | Whether this variable is hidden when publishing the current file as a library.  If the parent &#x60;VariableCollection&#x60; is marked as &#x60;hiddenFromPublishing&#x60;, then this variable will also be hidden from publishing via the UI. &#x60;hiddenFromPublishing&#x60; is independently toggled for a variable and collection. However, both must be true for a given variable to be publishable. |
**scopes** | [**\OpenAPI\Client\Model\VariableScope[]**](VariableScope.md) | An array of scopes in the UI where this variable is shown. Setting this property will show/hide this variable in the variable picker UI for different fields.  Setting scopes for a variable does not prevent that variable from being bound in other scopes (for example, via the Plugin API). This only limits the variables that are shown in pickers within the Figma UI. |
**code_syntax** | [**\OpenAPI\Client\Model\VariableCodeSyntax**](VariableCodeSyntax.md) |  |
**deleted_but_referenced** | **bool** | Indicates that the variable was deleted in the editor, but the document may still contain references to the variable. References to the variable may exist through bound values or variable aliases. | [optional] [default to false]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
