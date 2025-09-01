# # VariableUpdate

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**action** | **string** | The action to perform for the variable. |
**id** | **string** | The id of the variable to update. |
**name** | **string** | The name of this variable. | [optional]
**description** | **string** | The description of this variable. | [optional]
**hidden_from_publishing** | **bool** | Whether this variable is hidden when publishing the current file as a library. | [optional] [default to false]
**scopes** | [**\OpenAPI\Client\Model\VariableScope[]**](VariableScope.md) | An array of scopes in the UI where this variable is shown. Setting this property will show/hide this variable in the variable picker UI for different fields. | [optional]
**code_syntax** | [**\OpenAPI\Client\Model\VariableCodeSyntax**](VariableCodeSyntax.md) |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
