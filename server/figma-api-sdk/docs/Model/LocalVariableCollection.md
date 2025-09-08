# # LocalVariableCollection

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**id** | **string** | The unique identifier of this variable collection. |
**name** | **string** | The name of this variable collection. |
**key** | **string** | The key of this variable collection. |
**modes** | [**\OpenAPI\Client\Model\LocalVariableCollectionModesInner[]**](LocalVariableCollectionModesInner.md) | The modes of this variable collection. |
**default_mode_id** | **string** | The id of the default mode. |
**remote** | **bool** | Whether this variable collection is remote. |
**hidden_from_publishing** | **bool** | Whether this variable collection is hidden when publishing the current file as a library. | [default to false]
**variable_ids** | **string[]** | The ids of the variables in the collection. Note that the order of these variables is roughly the same as what is shown in Figma Design, however it does not account for groups. As a result, the order of these variables may not exactly reflect the exact ordering and grouping shown in the authoring UI. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
