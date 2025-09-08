# # TypePropertiesTrait

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**characters** | **string** | The raw characters in the text node. |
**style** | [**\OpenAPI\Client\Model\TypeStyle**](TypeStyle.md) | Style of text including font family and weight. |
**character_style_overrides** | **float[]** | The array corresponds to characters in the text box, where each element references the &#39;styleOverrideTable&#39; to apply specific styles to each character. The array&#39;s length can be less than or equal to the number of characters due to the removal of trailing zeros. Elements with a value of 0 indicate characters that use the default type style. If the array is shorter than the total number of characters, the characters beyond the array&#39;s length also use the default style. |
**layout_version** | **float** | Internal property, preserved for backward compatibility. Avoid using this value. | [optional]
**style_override_table** | [**array<string,\OpenAPI\Client\Model\TypeStyle>**](TypeStyle.md) | Map from ID to TypeStyle for looking up style overrides. |
**line_types** | **string[]** | An array with the same number of elements as lines in the text node, where lines are delimited by newline or paragraph separator characters. Each element in the array corresponds to the list type of a specific line. List types are represented as string enums with one of these possible values:  - &#x60;NONE&#x60;: Not a list item. - &#x60;ORDERED&#x60;: Text is an ordered list (numbered). - &#x60;UNORDERED&#x60;: Text is an unordered list (bulleted). |
**line_indentations** | **float[]** | An array with the same number of elements as lines in the text node, where lines are delimited by newline or paragraph separator characters. Each element in the array corresponds to the indentation level of a specific line. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
