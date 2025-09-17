# # NodeAction

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**type** | **string** |  |
**destination_id** | **string** |  |
**navigation** | [**\OpenAPI\Client\Model\Navigation**](Navigation.md) |  |
**transition** | [**\OpenAPI\Client\Model\Transition**](Transition.md) |  |
**preserve_scroll_position** | **bool** | Whether the scroll offsets of any scrollable elements in the current screen or overlay are preserved when navigating to the destination. This is applicable only if the layout of both the current frame and its destination are the same. | [optional]
**overlay_relative_position** | [**\OpenAPI\Client\Model\Vector**](Vector.md) | Applicable only when &#x60;navigation&#x60; is &#x60;\&quot;OVERLAY\&quot;&#x60; and the destination is a frame with &#x60;overlayPosition&#x60; equal to &#x60;\&quot;MANUAL\&quot;&#x60;. This value represents the offset by which the overlay is opened relative to this node. | [optional]
**reset_video_position** | **bool** | When true, all videos within the destination frame will reset their memorized playback position to 00:00 before starting to play. | [optional]
**reset_scroll_position** | **bool** | Whether the scroll offsets of any scrollable elements in the current screen or overlay reset when navigating to the destination. This is applicable only if the layout of both the current frame and its destination are the same. | [optional]
**reset_interactive_components** | **bool** | Whether the state of any interactive components in the current screen or overlay reset when navigating to the destination. This is applicable if there are interactive components in the destination frame. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
