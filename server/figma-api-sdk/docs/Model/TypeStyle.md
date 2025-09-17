# # TypeStyle

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
**paragraph_spacing** | **float** | Space between paragraphs in px, 0 if not present. | [optional] [default to 0]
**paragraph_indent** | **float** | Paragraph indentation in px, 0 if not present. | [optional] [default to 0]
**list_spacing** | **float** | Space between list items in px, 0 if not present. | [optional] [default to 0]
**text_decoration** | **string** | Text decoration applied to the node, default is none. | [optional] [default to 'NONE']
**text_auto_resize** | **string** | Dimensions along which text will auto resize, default is that the text does not auto-resize. TRUNCATE means that the text will be shortened and trailing text will be replaced with \&quot;…\&quot; if the text contents is larger than the bounds. &#x60;TRUNCATE&#x60; as a return value is deprecated and will be removed in a future version. Read from &#x60;textTruncation&#x60; instead. | [optional] [default to 'NONE']
**text_truncation** | **string** | Whether this text node will truncate with an ellipsis when the text contents is larger than the text node. | [optional] [default to 'DISABLED']
**max_lines** | **float** | When &#x60;textTruncation: \&quot;ENDING\&quot;&#x60; is set, &#x60;maxLines&#x60; determines how many lines a text node can grow to before it truncates. | [optional]
**line_height_px** | **float** | Line height in px. | [optional]
**line_height_percent** | **float** | Line height as a percentage of normal line height. This is deprecated; in a future version of the API only lineHeightPx and lineHeightPercentFontSize will be returned. | [optional] [default to 100]
**line_height_percent_font_size** | **float** | Line height as a percentage of the font size. Only returned when &#x60;lineHeightPercent&#x60; (deprecated) is not 100. | [optional]
**line_height_unit** | **string** | The unit of the line height value specified by the user. | [optional]
**is_override_over_text_style** | **bool** | Whether or not this style has overrides over a text style. The possible fields to override are semanticWeight, semanticItalic, hyperlink, and textDecoration. If this is true, then those fields are overrides if present. | [optional]
**bound_variables** | [**\OpenAPI\Client\Model\TypeStyleAllOfBoundVariables**](TypeStyleAllOfBoundVariables.md) |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
