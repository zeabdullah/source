# # HasMaskTrait

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**is_mask** | **bool** | Does this node mask sibling nodes in front of it? | [optional] [default to false]
**mask_type** | **string** | If this layer is a mask, this property describes the operation used to mask the layer&#39;s siblings. The value may be one of the following:  - ALPHA: the mask node&#39;s alpha channel will be used to determine the opacity of each pixel in the masked result. - VECTOR: if the mask node has visible fill paints, every pixel inside the node&#39;s fill regions will be fully visible in the masked result. If the mask has visible stroke paints, every pixel inside the node&#39;s stroke regions will be fully visible in the masked result. - LUMINANCE: the luminance value of each pixel of the mask node will be used to determine the opacity of that pixel in the masked result. | [optional]
**is_mask_outline** | **bool** | True if maskType is VECTOR. This field is deprecated; use maskType instead. | [optional] [default to false]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
