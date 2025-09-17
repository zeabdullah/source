# # VariableCreate

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**action** | **string** | The action to perform for the variable. |
**id** | **string** | A temporary id for this variable. | [optional]
**name** | **string** | The name of this variable. |
**variable_collection_id** | **string** | The variable collection that will contain the variable. You can use the temporary id of a variable collection. |
**resolved_type** | **string** | The resolved type of the variable. |
**description** | **string** | The description of this variable. | [optional]
**hidden_from_publishing** | **bool** | Whether this variable is hidden when publishing the current file as a library. | [optional] [default to false]
**scopes** | [**\OpenAPI\Client\Model\VariableScope[]**](VariableScope.md) | An array of scopes in the UI where this variable is shown. Setting this property will show/hide this variable in the variable picker UI for different fields. | [optional]
**code_syntax** | [**\OpenAPI\Client\Model\VariableCodeSyntax**](VariableCodeSyntax.md) |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
