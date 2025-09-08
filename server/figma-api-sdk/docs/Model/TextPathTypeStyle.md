# # TextPathTypeStyle

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**font_family** | **string** | Font family of text (standard name). | [optional]
**font_post_script_name** | **string** | PostScript font name. | [optional]
**font_style** | **string** | Describes visual weight or emphasis, such as Bold or Italic. | [optional]
**italic** | **bool** | Whether or not text is italicized. | [optional] [default to false]
**font_weight** | **float** | Numeric font weight. | [optional]
**font_size** | **float** | Font size in px. | [optional]
**text_case** | **string** | Text casing applied to the node, default is the original casing. | [optional]
**text_align_horizontal** | **string** | Horizontal text alignment as string enum. | [optional]
**text_align_vertical** | **string** | Vertical text alignment as string enum. | [optional]
**letter_spacing** | **float** | Space between characters in px. | [optional]
**fills** | [**\OpenAPI\Client\Model\Paint[]**](Paint.md) | An array of fill paints applied to the characters. | [optional]
**hyperlink** | [**\OpenAPI\Client\Model\Hyperlink**](Hyperlink.md) | Link to a URL or frame. | [optional]
**opentype_flags** | **array<string,float>** | A map of OpenType feature flags to 1 or 0, 1 if it is enabled and 0 if it is disabled. Note that some flags aren&#39;t reflected here. For example, SMCP (small caps) is still represented by the &#x60;textCase&#x60; field. | [optional]
**semantic_weight** | **string** | Indicates how the font weight was overridden when there is a text style override. | [optional]
**semantic_italic** | **string** | Indicates how the font style was overridden when there is a text style override. | [optional]
**is_override_over_text_style** | **bool** | Whether or not this style has overrides over a text style. The possible fields to override are semanticWeight, semanticItalic, and hyperlink. If this is true, then those fields are overrides if present. | [optional]
**bound_variables** | [**\OpenAPI\Client\Model\TextPathTypeStyleAllOfBoundVariables**](TextPathTypeStyleAllOfBoundVariables.md) |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
