# # VariableDataValue

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**r** | **float** | Red channel value, between 0 and 1. |
**g** | **float** | Green channel value, between 0 and 1. |
**b** | **float** | Blue channel value, between 0 and 1. |
**a** | **float** | Alpha channel value, between 0 and 1. |
**type** | **string** |  |
**id** | **string** | The id of the variable that the current variable is aliased to. This variable can be a local or remote variable, and both can be retrieved via the GET /v1/files/:file_key/variables/local endpoint. |
**expression_function** | [**\OpenAPI\Client\Model\ExpressionFunction**](ExpressionFunction.md) |  |
**expression_arguments** | [**\OpenAPI\Client\Model\VariableData[]**](VariableData.md) |  |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
