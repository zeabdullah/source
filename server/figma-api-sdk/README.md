# OpenAPIClient-php

This is the OpenAPI specification for the [Figma REST API](https://www.figma.com/developers/api).

Note: we are releasing the OpenAPI specification as a beta given the large surface area and complexity of the REST API. If you notice any inaccuracies with the specification, please [file an issue](https://github.com/figma/rest-api-spec/issues).


## Installation & Usage

### Requirements

PHP 8.1 and later.

### Composer

To install the bindings via [Composer](https://getcomposer.org/), add the following to `composer.json`:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/GIT_USER_ID/GIT_REPO_ID.git"
    }
  ],
  "require": {
    "GIT_USER_ID/GIT_REPO_ID": "*@dev"
  }
}
```

Then run `composer install`

### Manual Installation

Download the files and include `autoload.php`:

```php
<?php
require_once('/path/to/OpenAPIClient-php/vendor/autoload.php');
```

## Getting Started

Please follow the [installation procedure](#installation--usage) and then run the following:

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



// Configure OAuth2 access token for authorization: OrgOAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\ActivityLogsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$events = 'events_example'; // string | Event type(s) to include in the response. Can have multiple values separated by comma. All events are returned by default.
$start_time = 3.4; // float | Unix timestamp of the least recent event to include. This param defaults to one year ago if unspecified.
$end_time = 3.4; // float | Unix timestamp of the most recent event to include. This param defaults to the current timestamp if unspecified.
$limit = 3.4; // float | Maximum number of events to return. This param defaults to 1000 if unspecified.
$order = 'asc'; // string | Event order by timestamp. This param can be either \"asc\" (default) or \"desc\".

try {
    $result = $apiInstance->getActivityLogs($events, $start_time, $end_time, $limit, $order);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ActivityLogsApi->getActivityLogs: ', $e->getMessage(), PHP_EOL;
}

```

## API Endpoints

All URIs are relative to *https://api.figma.com*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*ActivityLogsApi* | [**getActivityLogs**](docs/Api/ActivityLogsApi.md#getactivitylogs) | **GET** /v1/activity_logs | Get activity logs
*CommentReactionsApi* | [**deleteCommentReaction**](docs/Api/CommentReactionsApi.md#deletecommentreaction) | **DELETE** /v1/files/{file_key}/comments/{comment_id}/reactions | Delete a reaction
*CommentReactionsApi* | [**getCommentReactions**](docs/Api/CommentReactionsApi.md#getcommentreactions) | **GET** /v1/files/{file_key}/comments/{comment_id}/reactions | Get reactions for a comment
*CommentReactionsApi* | [**postCommentReaction**](docs/Api/CommentReactionsApi.md#postcommentreaction) | **POST** /v1/files/{file_key}/comments/{comment_id}/reactions | Add a reaction to a comment
*CommentsApi* | [**deleteComment**](docs/Api/CommentsApi.md#deletecomment) | **DELETE** /v1/files/{file_key}/comments/{comment_id} | Delete a comment
*CommentsApi* | [**getComments**](docs/Api/CommentsApi.md#getcomments) | **GET** /v1/files/{file_key}/comments | Get comments in a file
*CommentsApi* | [**postComment**](docs/Api/CommentsApi.md#postcomment) | **POST** /v1/files/{file_key}/comments | Add a comment to a file
*ComponentSetsApi* | [**getComponentSet**](docs/Api/ComponentSetsApi.md#getcomponentset) | **GET** /v1/component_sets/{key} | Get component set
*ComponentSetsApi* | [**getFileComponentSets**](docs/Api/ComponentSetsApi.md#getfilecomponentsets) | **GET** /v1/files/{file_key}/component_sets | Get file component sets
*ComponentSetsApi* | [**getTeamComponentSets**](docs/Api/ComponentSetsApi.md#getteamcomponentsets) | **GET** /v1/teams/{team_id}/component_sets | Get team component sets
*ComponentsApi* | [**getComponent**](docs/Api/ComponentsApi.md#getcomponent) | **GET** /v1/components/{key} | Get component
*ComponentsApi* | [**getFileComponents**](docs/Api/ComponentsApi.md#getfilecomponents) | **GET** /v1/files/{file_key}/components | Get file components
*ComponentsApi* | [**getTeamComponents**](docs/Api/ComponentsApi.md#getteamcomponents) | **GET** /v1/teams/{team_id}/components | Get team components
*DevResourcesApi* | [**deleteDevResource**](docs/Api/DevResourcesApi.md#deletedevresource) | **DELETE** /v1/files/{file_key}/dev_resources/{dev_resource_id} | Delete dev resource
*DevResourcesApi* | [**getDevResources**](docs/Api/DevResourcesApi.md#getdevresources) | **GET** /v1/files/{file_key}/dev_resources | Get dev resources
*DevResourcesApi* | [**postDevResources**](docs/Api/DevResourcesApi.md#postdevresources) | **POST** /v1/dev_resources | Create dev resources
*DevResourcesApi* | [**putDevResources**](docs/Api/DevResourcesApi.md#putdevresources) | **PUT** /v1/dev_resources | Update dev resources
*FilesApi* | [**getFile**](docs/Api/FilesApi.md#getfile) | **GET** /v1/files/{file_key} | Get file JSON
*FilesApi* | [**getFileMeta**](docs/Api/FilesApi.md#getfilemeta) | **GET** /v1/files/{file_key}/meta | Get file metadata
*FilesApi* | [**getFileNodes**](docs/Api/FilesApi.md#getfilenodes) | **GET** /v1/files/{file_key}/nodes | Get file JSON for specific nodes
*FilesApi* | [**getFileVersions**](docs/Api/FilesApi.md#getfileversions) | **GET** /v1/files/{file_key}/versions | Get versions of a file
*FilesApi* | [**getImageFills**](docs/Api/FilesApi.md#getimagefills) | **GET** /v1/files/{file_key}/images | Get image fills
*FilesApi* | [**getImages**](docs/Api/FilesApi.md#getimages) | **GET** /v1/images/{file_key} | Render images of file nodes
*LibraryAnalyticsApi* | [**getLibraryAnalyticsComponentActions**](docs/Api/LibraryAnalyticsApi.md#getlibraryanalyticscomponentactions) | **GET** /v1/analytics/libraries/{file_key}/component/actions | Get library analytics component action data.
*LibraryAnalyticsApi* | [**getLibraryAnalyticsComponentUsages**](docs/Api/LibraryAnalyticsApi.md#getlibraryanalyticscomponentusages) | **GET** /v1/analytics/libraries/{file_key}/component/usages | Get library analytics component usage data.
*LibraryAnalyticsApi* | [**getLibraryAnalyticsStyleActions**](docs/Api/LibraryAnalyticsApi.md#getlibraryanalyticsstyleactions) | **GET** /v1/analytics/libraries/{file_key}/style/actions | Get library analytics style action data.
*LibraryAnalyticsApi* | [**getLibraryAnalyticsStyleUsages**](docs/Api/LibraryAnalyticsApi.md#getlibraryanalyticsstyleusages) | **GET** /v1/analytics/libraries/{file_key}/style/usages | Get library analytics style usage data.
*LibraryAnalyticsApi* | [**getLibraryAnalyticsVariableActions**](docs/Api/LibraryAnalyticsApi.md#getlibraryanalyticsvariableactions) | **GET** /v1/analytics/libraries/{file_key}/variable/actions | Get library analytics variable action data.
*LibraryAnalyticsApi* | [**getLibraryAnalyticsVariableUsages**](docs/Api/LibraryAnalyticsApi.md#getlibraryanalyticsvariableusages) | **GET** /v1/analytics/libraries/{file_key}/variable/usages | Get library analytics variable usage data.
*PaymentsApi* | [**getPayments**](docs/Api/PaymentsApi.md#getpayments) | **GET** /v1/payments | Get payments
*ProjectsApi* | [**getProjectFiles**](docs/Api/ProjectsApi.md#getprojectfiles) | **GET** /v1/projects/{project_id}/files | Get files in a project
*ProjectsApi* | [**getTeamProjects**](docs/Api/ProjectsApi.md#getteamprojects) | **GET** /v1/teams/{team_id}/projects | Get projects in a team
*StylesApi* | [**getFileStyles**](docs/Api/StylesApi.md#getfilestyles) | **GET** /v1/files/{file_key}/styles | Get file styles
*StylesApi* | [**getStyle**](docs/Api/StylesApi.md#getstyle) | **GET** /v1/styles/{key} | Get style
*StylesApi* | [**getTeamStyles**](docs/Api/StylesApi.md#getteamstyles) | **GET** /v1/teams/{team_id}/styles | Get team styles
*UsersApi* | [**getMe**](docs/Api/UsersApi.md#getme) | **GET** /v1/me | Get current user
*VariablesApi* | [**getLocalVariables**](docs/Api/VariablesApi.md#getlocalvariables) | **GET** /v1/files/{file_key}/variables/local | Get local variables
*VariablesApi* | [**getPublishedVariables**](docs/Api/VariablesApi.md#getpublishedvariables) | **GET** /v1/files/{file_key}/variables/published | Get published variables
*VariablesApi* | [**postVariables**](docs/Api/VariablesApi.md#postvariables) | **POST** /v1/files/{file_key}/variables | Create/modify/delete variables
*WebhooksApi* | [**deleteWebhook**](docs/Api/WebhooksApi.md#deletewebhook) | **DELETE** /v2/webhooks/{webhook_id} | Delete a webhook
*WebhooksApi* | [**getTeamWebhooks**](docs/Api/WebhooksApi.md#getteamwebhooks) | **GET** /v2/teams/{team_id}/webhooks | [Deprecated] Get team webhooks
*WebhooksApi* | [**getWebhook**](docs/Api/WebhooksApi.md#getwebhook) | **GET** /v2/webhooks/{webhook_id} | Get a webhook
*WebhooksApi* | [**getWebhookRequests**](docs/Api/WebhooksApi.md#getwebhookrequests) | **GET** /v2/webhooks/{webhook_id}/requests | Get webhook requests
*WebhooksApi* | [**getWebhooks**](docs/Api/WebhooksApi.md#getwebhooks) | **GET** /v2/webhooks | Get webhooks by context or plan
*WebhooksApi* | [**postWebhook**](docs/Api/WebhooksApi.md#postwebhook) | **POST** /v2/webhooks | Create a webhook
*WebhooksApi* | [**putWebhook**](docs/Api/WebhooksApi.md#putwebhook) | **PUT** /v2/webhooks/{webhook_id} | Update a webhook

## Models

- [Action](docs/Model/Action.md)
- [ActionOneOf](docs/Model/ActionOneOf.md)
- [ActivityLog](docs/Model/ActivityLog.md)
- [ActivityLogAction](docs/Model/ActivityLogAction.md)
- [ActivityLogActor](docs/Model/ActivityLogActor.md)
- [ActivityLogContext](docs/Model/ActivityLogContext.md)
- [ActivityLogEntity](docs/Model/ActivityLogEntity.md)
- [ActivityLogFileEntity](docs/Model/ActivityLogFileEntity.md)
- [ActivityLogFileRepoEntity](docs/Model/ActivityLogFileRepoEntity.md)
- [ActivityLogOrgEntity](docs/Model/ActivityLogOrgEntity.md)
- [ActivityLogPluginEntity](docs/Model/ActivityLogPluginEntity.md)
- [ActivityLogProjectEntity](docs/Model/ActivityLogProjectEntity.md)
- [ActivityLogTeamEntity](docs/Model/ActivityLogTeamEntity.md)
- [ActivityLogUserEntity](docs/Model/ActivityLogUserEntity.md)
- [ActivityLogWidgetEntity](docs/Model/ActivityLogWidgetEntity.md)
- [ActivityLogWorkspaceEntity](docs/Model/ActivityLogWorkspaceEntity.md)
- [AfterTimeoutTrigger](docs/Model/AfterTimeoutTrigger.md)
- [ArcData](docs/Model/ArcData.md)
- [BaseBlurEffect](docs/Model/BaseBlurEffect.md)
- [BaseBlurEffectBoundVariables](docs/Model/BaseBlurEffectBoundVariables.md)
- [BaseNoiseEffect](docs/Model/BaseNoiseEffect.md)
- [BasePaint](docs/Model/BasePaint.md)
- [BaseShadowEffect](docs/Model/BaseShadowEffect.md)
- [BaseShadowEffectBoundVariables](docs/Model/BaseShadowEffectBoundVariables.md)
- [BaseTypeStyle](docs/Model/BaseTypeStyle.md)
- [BlendMode](docs/Model/BlendMode.md)
- [BlurEffect](docs/Model/BlurEffect.md)
- [BooleanOperationNode](docs/Model/BooleanOperationNode.md)
- [CanvasNode](docs/Model/CanvasNode.md)
- [ColorStop](docs/Model/ColorStop.md)
- [ColorStopBoundVariables](docs/Model/ColorStopBoundVariables.md)
- [Comment](docs/Model/Comment.md)
- [CommentClientMeta](docs/Model/CommentClientMeta.md)
- [CommentFragment](docs/Model/CommentFragment.md)
- [Component](docs/Model/Component.md)
- [ComponentNode](docs/Model/ComponentNode.md)
- [ComponentPropertiesTrait](docs/Model/ComponentPropertiesTrait.md)
- [ComponentProperty](docs/Model/ComponentProperty.md)
- [ComponentPropertyBoundVariables](docs/Model/ComponentPropertyBoundVariables.md)
- [ComponentPropertyDefinition](docs/Model/ComponentPropertyDefinition.md)
- [ComponentPropertyDefinitionDefaultValue](docs/Model/ComponentPropertyDefinitionDefaultValue.md)
- [ComponentPropertyType](docs/Model/ComponentPropertyType.md)
- [ComponentPropertyValue](docs/Model/ComponentPropertyValue.md)
- [ComponentSet](docs/Model/ComponentSet.md)
- [ComponentSetNode](docs/Model/ComponentSetNode.md)
- [ConditionalAction](docs/Model/ConditionalAction.md)
- [ConditionalBlock](docs/Model/ConditionalBlock.md)
- [ConnectorEndpoint](docs/Model/ConnectorEndpoint.md)
- [ConnectorEndpointOneOf](docs/Model/ConnectorEndpointOneOf.md)
- [ConnectorEndpointOneOf1](docs/Model/ConnectorEndpointOneOf1.md)
- [ConnectorLineType](docs/Model/ConnectorLineType.md)
- [ConnectorNode](docs/Model/ConnectorNode.md)
- [ConnectorTextBackground](docs/Model/ConnectorTextBackground.md)
- [Constraint](docs/Model/Constraint.md)
- [CornerRadiusShapeTraits](docs/Model/CornerRadiusShapeTraits.md)
- [CornerTrait](docs/Model/CornerTrait.md)
- [DefaultShapeTraits](docs/Model/DefaultShapeTraits.md)
- [DevResource](docs/Model/DevResource.md)
- [DevStatusTrait](docs/Model/DevStatusTrait.md)
- [DevStatusTraitDevStatus](docs/Model/DevStatusTraitDevStatus.md)
- [DirectionalTransition](docs/Model/DirectionalTransition.md)
- [DocumentNode](docs/Model/DocumentNode.md)
- [DocumentationLink](docs/Model/DocumentationLink.md)
- [DropShadowEffect](docs/Model/DropShadowEffect.md)
- [DuotoneNoiseEffect](docs/Model/DuotoneNoiseEffect.md)
- [Easing](docs/Model/Easing.md)
- [EasingEasingFunctionCubicBezier](docs/Model/EasingEasingFunctionCubicBezier.md)
- [EasingEasingFunctionSpring](docs/Model/EasingEasingFunctionSpring.md)
- [EasingType](docs/Model/EasingType.md)
- [Effect](docs/Model/Effect.md)
- [EllipseNode](docs/Model/EllipseNode.md)
- [EmbedNode](docs/Model/EmbedNode.md)
- [ErrorResponsePayloadWithErrMessage](docs/Model/ErrorResponsePayloadWithErrMessage.md)
- [ErrorResponsePayloadWithErrorBoolean](docs/Model/ErrorResponsePayloadWithErrorBoolean.md)
- [ExportSetting](docs/Model/ExportSetting.md)
- [Expression](docs/Model/Expression.md)
- [ExpressionFunction](docs/Model/ExpressionFunction.md)
- [FlowStartingPoint](docs/Model/FlowStartingPoint.md)
- [FrameInfo](docs/Model/FrameInfo.md)
- [FrameInfoContainingComponentSet](docs/Model/FrameInfoContainingComponentSet.md)
- [FrameInfoContainingStateGroup](docs/Model/FrameInfoContainingStateGroup.md)
- [FrameNode](docs/Model/FrameNode.md)
- [FrameOffset](docs/Model/FrameOffset.md)
- [FrameOffsetRegion](docs/Model/FrameOffsetRegion.md)
- [FrameTraits](docs/Model/FrameTraits.md)
- [GradientPaint](docs/Model/GradientPaint.md)
- [GroupNode](docs/Model/GroupNode.md)
- [HasBlendModeAndOpacityTrait](docs/Model/HasBlendModeAndOpacityTrait.md)
- [HasChildrenTrait](docs/Model/HasChildrenTrait.md)
- [HasEffectsTrait](docs/Model/HasEffectsTrait.md)
- [HasExportSettingsTrait](docs/Model/HasExportSettingsTrait.md)
- [HasFramePropertiesTrait](docs/Model/HasFramePropertiesTrait.md)
- [HasGeometryTrait](docs/Model/HasGeometryTrait.md)
- [HasGeometryTraitAllOfFillOverrideTable](docs/Model/HasGeometryTraitAllOfFillOverrideTable.md)
- [HasLayoutTrait](docs/Model/HasLayoutTrait.md)
- [HasMaskTrait](docs/Model/HasMaskTrait.md)
- [HasTextSublayerTrait](docs/Model/HasTextSublayerTrait.md)
- [Hyperlink](docs/Model/Hyperlink.md)
- [ImageFilters](docs/Model/ImageFilters.md)
- [ImagePaint](docs/Model/ImagePaint.md)
- [IndividualStrokesTrait](docs/Model/IndividualStrokesTrait.md)
- [InlineObject](docs/Model/InlineObject.md)
- [InlineObject1](docs/Model/InlineObject1.md)
- [InlineObject10](docs/Model/InlineObject10.md)
- [InlineObject11](docs/Model/InlineObject11.md)
- [InlineObject12](docs/Model/InlineObject12.md)
- [InlineObject12Meta](docs/Model/InlineObject12Meta.md)
- [InlineObject13](docs/Model/InlineObject13.md)
- [InlineObject13Meta](docs/Model/InlineObject13Meta.md)
- [InlineObject14](docs/Model/InlineObject14.md)
- [InlineObject15](docs/Model/InlineObject15.md)
- [InlineObject15Meta](docs/Model/InlineObject15Meta.md)
- [InlineObject16](docs/Model/InlineObject16.md)
- [InlineObject16Meta](docs/Model/InlineObject16Meta.md)
- [InlineObject17](docs/Model/InlineObject17.md)
- [InlineObject18](docs/Model/InlineObject18.md)
- [InlineObject18Meta](docs/Model/InlineObject18Meta.md)
- [InlineObject19](docs/Model/InlineObject19.md)
- [InlineObject19Meta](docs/Model/InlineObject19Meta.md)
- [InlineObject1NodesValue](docs/Model/InlineObject1NodesValue.md)
- [InlineObject2](docs/Model/InlineObject2.md)
- [InlineObject20](docs/Model/InlineObject20.md)
- [InlineObject21](docs/Model/InlineObject21.md)
- [InlineObject22](docs/Model/InlineObject22.md)
- [InlineObject23](docs/Model/InlineObject23.md)
- [InlineObject24](docs/Model/InlineObject24.md)
- [InlineObject24Meta](docs/Model/InlineObject24Meta.md)
- [InlineObject25](docs/Model/InlineObject25.md)
- [InlineObject26](docs/Model/InlineObject26.md)
- [InlineObject26Meta](docs/Model/InlineObject26Meta.md)
- [InlineObject27](docs/Model/InlineObject27.md)
- [InlineObject27Meta](docs/Model/InlineObject27Meta.md)
- [InlineObject28](docs/Model/InlineObject28.md)
- [InlineObject28Meta](docs/Model/InlineObject28Meta.md)
- [InlineObject29](docs/Model/InlineObject29.md)
- [InlineObject3](docs/Model/InlineObject3.md)
- [InlineObject30](docs/Model/InlineObject30.md)
- [InlineObject30ErrorsInner](docs/Model/InlineObject30ErrorsInner.md)
- [InlineObject31](docs/Model/InlineObject31.md)
- [InlineObject31ErrorsInner](docs/Model/InlineObject31ErrorsInner.md)
- [InlineObject32](docs/Model/InlineObject32.md)
- [InlineObject32Rows](docs/Model/InlineObject32Rows.md)
- [InlineObject33](docs/Model/InlineObject33.md)
- [InlineObject33Rows](docs/Model/InlineObject33Rows.md)
- [InlineObject34](docs/Model/InlineObject34.md)
- [InlineObject34Rows](docs/Model/InlineObject34Rows.md)
- [InlineObject35](docs/Model/InlineObject35.md)
- [InlineObject35Rows](docs/Model/InlineObject35Rows.md)
- [InlineObject36](docs/Model/InlineObject36.md)
- [InlineObject36Rows](docs/Model/InlineObject36Rows.md)
- [InlineObject37](docs/Model/InlineObject37.md)
- [InlineObject37Rows](docs/Model/InlineObject37Rows.md)
- [InlineObject38](docs/Model/InlineObject38.md)
- [InlineObject39](docs/Model/InlineObject39.md)
- [InlineObject3Meta](docs/Model/InlineObject3Meta.md)
- [InlineObject4](docs/Model/InlineObject4.md)
- [InlineObject40](docs/Model/InlineObject40.md)
- [InlineObject41](docs/Model/InlineObject41.md)
- [InlineObject42](docs/Model/InlineObject42.md)
- [InlineObject43](docs/Model/InlineObject43.md)
- [InlineObject44](docs/Model/InlineObject44.md)
- [InlineObject45](docs/Model/InlineObject45.md)
- [InlineObject46](docs/Model/InlineObject46.md)
- [InlineObject47](docs/Model/InlineObject47.md)
- [InlineObject48](docs/Model/InlineObject48.md)
- [InlineObject5](docs/Model/InlineObject5.md)
- [InlineObject6](docs/Model/InlineObject6.md)
- [InlineObject6FilesInner](docs/Model/InlineObject6FilesInner.md)
- [InlineObject7](docs/Model/InlineObject7.md)
- [InlineObject8](docs/Model/InlineObject8.md)
- [InlineObject9](docs/Model/InlineObject9.md)
- [InlineObjectBranchesInner](docs/Model/InlineObjectBranchesInner.md)
- [InnerShadowEffect](docs/Model/InnerShadowEffect.md)
- [InstanceNode](docs/Model/InstanceNode.md)
- [InstanceSwapPreferredValue](docs/Model/InstanceSwapPreferredValue.md)
- [Interaction](docs/Model/Interaction.md)
- [IsLayerTrait](docs/Model/IsLayerTrait.md)
- [IsLayerTraitBoundVariables](docs/Model/IsLayerTraitBoundVariables.md)
- [IsLayerTraitBoundVariablesIndividualStrokeWeights](docs/Model/IsLayerTraitBoundVariablesIndividualStrokeWeights.md)
- [IsLayerTraitBoundVariablesRectangleCornerRadii](docs/Model/IsLayerTraitBoundVariablesRectangleCornerRadii.md)
- [IsLayerTraitBoundVariablesSize](docs/Model/IsLayerTraitBoundVariablesSize.md)
- [LayoutConstraint](docs/Model/LayoutConstraint.md)
- [LayoutGrid](docs/Model/LayoutGrid.md)
- [LayoutGridBoundVariables](docs/Model/LayoutGridBoundVariables.md)
- [LibraryAnalyticsComponentActionsByAsset](docs/Model/LibraryAnalyticsComponentActionsByAsset.md)
- [LibraryAnalyticsComponentActionsByTeam](docs/Model/LibraryAnalyticsComponentActionsByTeam.md)
- [LibraryAnalyticsComponentUsagesByAsset](docs/Model/LibraryAnalyticsComponentUsagesByAsset.md)
- [LibraryAnalyticsComponentUsagesByFile](docs/Model/LibraryAnalyticsComponentUsagesByFile.md)
- [LibraryAnalyticsStyleActionsByAsset](docs/Model/LibraryAnalyticsStyleActionsByAsset.md)
- [LibraryAnalyticsStyleActionsByTeam](docs/Model/LibraryAnalyticsStyleActionsByTeam.md)
- [LibraryAnalyticsStyleUsagesByAsset](docs/Model/LibraryAnalyticsStyleUsagesByAsset.md)
- [LibraryAnalyticsStyleUsagesByFile](docs/Model/LibraryAnalyticsStyleUsagesByFile.md)
- [LibraryAnalyticsVariableActionsByAsset](docs/Model/LibraryAnalyticsVariableActionsByAsset.md)
- [LibraryAnalyticsVariableActionsByTeam](docs/Model/LibraryAnalyticsVariableActionsByTeam.md)
- [LibraryAnalyticsVariableUsagesByAsset](docs/Model/LibraryAnalyticsVariableUsagesByAsset.md)
- [LibraryAnalyticsVariableUsagesByFile](docs/Model/LibraryAnalyticsVariableUsagesByFile.md)
- [LibraryItemData](docs/Model/LibraryItemData.md)
- [LineNode](docs/Model/LineNode.md)
- [LinkUnfurlNode](docs/Model/LinkUnfurlNode.md)
- [LocalVariable](docs/Model/LocalVariable.md)
- [LocalVariableCollection](docs/Model/LocalVariableCollection.md)
- [LocalVariableCollectionModesInner](docs/Model/LocalVariableCollectionModesInner.md)
- [LocalVariableValuesByModeValue](docs/Model/LocalVariableValuesByModeValue.md)
- [Measurement](docs/Model/Measurement.md)
- [MeasurementOffset](docs/Model/MeasurementOffset.md)
- [MeasurementOffsetInner](docs/Model/MeasurementOffsetInner.md)
- [MeasurementOffsetOuter](docs/Model/MeasurementOffsetOuter.md)
- [MeasurementStartEnd](docs/Model/MeasurementStartEnd.md)
- [MinimalFillsTrait](docs/Model/MinimalFillsTrait.md)
- [MinimalStrokesTrait](docs/Model/MinimalStrokesTrait.md)
- [MonotoneNoiseEffect](docs/Model/MonotoneNoiseEffect.md)
- [MultitoneNoiseEffect](docs/Model/MultitoneNoiseEffect.md)
- [Navigation](docs/Model/Navigation.md)
- [Node](docs/Model/Node.md)
- [NodeAction](docs/Model/NodeAction.md)
- [NoiseEffect](docs/Model/NoiseEffect.md)
- [NormalBlurEffect](docs/Model/NormalBlurEffect.md)
- [OnKeyDownTrigger](docs/Model/OnKeyDownTrigger.md)
- [OnMediaHitTrigger](docs/Model/OnMediaHitTrigger.md)
- [OpenURLAction](docs/Model/OpenURLAction.md)
- [Overrides](docs/Model/Overrides.md)
- [Paint](docs/Model/Paint.md)
- [PaintOverride](docs/Model/PaintOverride.md)
- [Path](docs/Model/Path.md)
- [PatternPaint](docs/Model/PatternPaint.md)
- [PaymentInformation](docs/Model/PaymentInformation.md)
- [PaymentStatus](docs/Model/PaymentStatus.md)
- [PostCommentReactionRequest](docs/Model/PostCommentReactionRequest.md)
- [PostCommentRequest](docs/Model/PostCommentRequest.md)
- [PostCommentRequestClientMeta](docs/Model/PostCommentRequestClientMeta.md)
- [PostDevResourcesRequest](docs/Model/PostDevResourcesRequest.md)
- [PostDevResourcesRequestDevResourcesInner](docs/Model/PostDevResourcesRequestDevResourcesInner.md)
- [PostVariablesRequest](docs/Model/PostVariablesRequest.md)
- [PostWebhookRequest](docs/Model/PostWebhookRequest.md)
- [ProgressiveBlurEffect](docs/Model/ProgressiveBlurEffect.md)
- [Project](docs/Model/Project.md)
- [PrototypeDevice](docs/Model/PrototypeDevice.md)
- [PublishedComponent](docs/Model/PublishedComponent.md)
- [PublishedComponentSet](docs/Model/PublishedComponentSet.md)
- [PublishedStyle](docs/Model/PublishedStyle.md)
- [PublishedVariable](docs/Model/PublishedVariable.md)
- [PublishedVariableCollection](docs/Model/PublishedVariableCollection.md)
- [PutDevResourcesRequest](docs/Model/PutDevResourcesRequest.md)
- [PutDevResourcesRequestDevResourcesInner](docs/Model/PutDevResourcesRequestDevResourcesInner.md)
- [PutWebhookRequest](docs/Model/PutWebhookRequest.md)
- [RGB](docs/Model/RGB.md)
- [RGBA](docs/Model/RGBA.md)
- [Reaction](docs/Model/Reaction.md)
- [Rectangle](docs/Model/Rectangle.md)
- [RectangleNode](docs/Model/RectangleNode.md)
- [RectangularShapeTraits](docs/Model/RectangularShapeTraits.md)
- [Region](docs/Model/Region.md)
- [RegularPolygonNode](docs/Model/RegularPolygonNode.md)
- [ResponseCursor](docs/Model/ResponseCursor.md)
- [ResponsePagination](docs/Model/ResponsePagination.md)
- [SectionNode](docs/Model/SectionNode.md)
- [SetVariableAction](docs/Model/SetVariableAction.md)
- [SetVariableModeAction](docs/Model/SetVariableModeAction.md)
- [ShapeType](docs/Model/ShapeType.md)
- [ShapeWithTextNode](docs/Model/ShapeWithTextNode.md)
- [SimpleTransition](docs/Model/SimpleTransition.md)
- [Size](docs/Model/Size.md)
- [SliceNode](docs/Model/SliceNode.md)
- [SolidPaint](docs/Model/SolidPaint.md)
- [SolidPaintAllOfBoundVariables](docs/Model/SolidPaintAllOfBoundVariables.md)
- [StarNode](docs/Model/StarNode.md)
- [StickyNode](docs/Model/StickyNode.md)
- [StrokeWeights](docs/Model/StrokeWeights.md)
- [Style](docs/Model/Style.md)
- [StyleType](docs/Model/StyleType.md)
- [SubcanvasNode](docs/Model/SubcanvasNode.md)
- [TableCellNode](docs/Model/TableCellNode.md)
- [TableNode](docs/Model/TableNode.md)
- [TextNode](docs/Model/TextNode.md)
- [TextPathNode](docs/Model/TextPathNode.md)
- [TextPathPropertiesTrait](docs/Model/TextPathPropertiesTrait.md)
- [TextPathTypeStyle](docs/Model/TextPathTypeStyle.md)
- [TextPathTypeStyleAllOfBoundVariables](docs/Model/TextPathTypeStyleAllOfBoundVariables.md)
- [TextureEffect](docs/Model/TextureEffect.md)
- [TransformGroupNode](docs/Model/TransformGroupNode.md)
- [Transition](docs/Model/Transition.md)
- [TransitionSourceTrait](docs/Model/TransitionSourceTrait.md)
- [Trigger](docs/Model/Trigger.md)
- [TriggerOneOf](docs/Model/TriggerOneOf.md)
- [TriggerOneOf1](docs/Model/TriggerOneOf1.md)
- [TriggerOneOf2](docs/Model/TriggerOneOf2.md)
- [TypePropertiesTrait](docs/Model/TypePropertiesTrait.md)
- [TypeStyle](docs/Model/TypeStyle.md)
- [TypeStyleAllOfBoundVariables](docs/Model/TypeStyleAllOfBoundVariables.md)
- [UpdateMediaRuntimeAction](docs/Model/UpdateMediaRuntimeAction.md)
- [UpdateMediaRuntimeActionOneOf](docs/Model/UpdateMediaRuntimeActionOneOf.md)
- [UpdateMediaRuntimeActionOneOf1](docs/Model/UpdateMediaRuntimeActionOneOf1.md)
- [UpdateMediaRuntimeActionOneOf2](docs/Model/UpdateMediaRuntimeActionOneOf2.md)
- [User](docs/Model/User.md)
- [VariableAlias](docs/Model/VariableAlias.md)
- [VariableChange](docs/Model/VariableChange.md)
- [VariableCodeSyntax](docs/Model/VariableCodeSyntax.md)
- [VariableCollectionChange](docs/Model/VariableCollectionChange.md)
- [VariableCollectionCreate](docs/Model/VariableCollectionCreate.md)
- [VariableCollectionDelete](docs/Model/VariableCollectionDelete.md)
- [VariableCollectionUpdate](docs/Model/VariableCollectionUpdate.md)
- [VariableCreate](docs/Model/VariableCreate.md)
- [VariableData](docs/Model/VariableData.md)
- [VariableDataType](docs/Model/VariableDataType.md)
- [VariableDataValue](docs/Model/VariableDataValue.md)
- [VariableDelete](docs/Model/VariableDelete.md)
- [VariableModeChange](docs/Model/VariableModeChange.md)
- [VariableModeCreate](docs/Model/VariableModeCreate.md)
- [VariableModeDelete](docs/Model/VariableModeDelete.md)
- [VariableModeUpdate](docs/Model/VariableModeUpdate.md)
- [VariableModeValue](docs/Model/VariableModeValue.md)
- [VariableResolvedDataType](docs/Model/VariableResolvedDataType.md)
- [VariableScope](docs/Model/VariableScope.md)
- [VariableUpdate](docs/Model/VariableUpdate.md)
- [VariableValue](docs/Model/VariableValue.md)
- [Vector](docs/Model/Vector.md)
- [VectorNode](docs/Model/VectorNode.md)
- [Version](docs/Model/Version.md)
- [WashiTapeNode](docs/Model/WashiTapeNode.md)
- [WebhookBasePayload](docs/Model/WebhookBasePayload.md)
- [WebhookDevModeStatusUpdatePayload](docs/Model/WebhookDevModeStatusUpdatePayload.md)
- [WebhookFileCommentPayload](docs/Model/WebhookFileCommentPayload.md)
- [WebhookFileDeletePayload](docs/Model/WebhookFileDeletePayload.md)
- [WebhookFileUpdatePayload](docs/Model/WebhookFileUpdatePayload.md)
- [WebhookFileVersionUpdatePayload](docs/Model/WebhookFileVersionUpdatePayload.md)
- [WebhookLibraryPublishPayload](docs/Model/WebhookLibraryPublishPayload.md)
- [WebhookPingPayload](docs/Model/WebhookPingPayload.md)
- [WebhookV2](docs/Model/WebhookV2.md)
- [WebhookV2Event](docs/Model/WebhookV2Event.md)
- [WebhookV2Request](docs/Model/WebhookV2Request.md)
- [WebhookV2RequestInfo](docs/Model/WebhookV2RequestInfo.md)
- [WebhookV2ResponseInfo](docs/Model/WebhookV2ResponseInfo.md)
- [WebhookV2Status](docs/Model/WebhookV2Status.md)
- [WidgetNode](docs/Model/WidgetNode.md)

## Authorization

Authentication schemes defined for the API:
### PersonalAccessToken

- **Type**: API key
- **API key parameter name**: X-Figma-Token
- **Location**: HTTP header


### OAuth2

- **Type**: `OAuth`
- **Flow**: `accessCode`
- **Authorization URL**: `https://www.figma.com/oauth`
- **Scopes**: 
    - **current_user:read**: Read your name, email, and profile image.
    - **file_comments:read**: Read the comments for files.
    - **file_comments:write**: Post and delete comments and comment reactions in files.
    - **file_content:read**: Read the contents of files, such as nodes and the editor type.
    - **file_dev_resources:read**: Read dev resources in files.
    - **file_dev_resources:write**: Write to dev resources in files.
    - **file_metadata:read**: Read metadata of files.
    - **file_variables:read**: Read variables in Figma file. Note: this is only available to members in Enterprise organizations.
    - **file_variables:write**: Write to variables in Figma file. Note: this is only available to members in Enterprise organizations.
    - **file_versions:read**: Read the version history for files you can access.
    - **files:read**: Deprecated. Read files, projects, users, versions, comments, components & styles, and webhooks.
    - **library_analytics:read**: Read library analytics data.
    - **library_assets:read**: Read data of individual published components and styles.
    - **library_content:read**: Read published components and styles of files.
    - **projects:read**: List projects and files in projects.
    - **team_library_content:read**: Read published components and styles of teams.
    - **webhooks:read**: Read metadata of webhooks.
    - **webhooks:write**: Create and manage webhooks.

### OrgOAuth2

- **Type**: `OAuth`
- **Flow**: `accessCode`
- **Authorization URL**: `https://www.figma.com/oauth`
- **Scopes**: 
    - **org:activity_log_read**: Read activity logs in the organization.

## Tests

To run the tests, use:

```bash
composer install
vendor/bin/phpunit
```

## Author

support@figma.com

## About this package

This PHP package is automatically generated by the [OpenAPI Generator](https://openapi-generator.tech) project:

- API version: `0.33.0`
    - Generator version: `7.15.0`
- Build package: `org.openapitools.codegen.languages.PhpClientCodegen`
