# OpenAPI\Client\FilesApi

All URIs are relative to https://api.figma.com, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getFile()**](FilesApi.md#getFile) | **GET** /v1/files/{file_key} | Get file JSON |
| [**getFileMeta()**](FilesApi.md#getFileMeta) | **GET** /v1/files/{file_key}/meta | Get file metadata |
| [**getFileNodes()**](FilesApi.md#getFileNodes) | **GET** /v1/files/{file_key}/nodes | Get file JSON for specific nodes |
| [**getFileVersions()**](FilesApi.md#getFileVersions) | **GET** /v1/files/{file_key}/versions | Get versions of a file |
| [**getImageFills()**](FilesApi.md#getImageFills) | **GET** /v1/files/{file_key}/images | Get image fills |
| [**getImages()**](FilesApi.md#getImages) | **GET** /v1/images/{file_key} | Render images of file nodes |


## `getFile()`

```php
getFile($file_key, $version, $ids, $depth, $geometry, $plugin_data, $branch_data): \OpenAPI\Client\Model\InlineObject
```

Get file JSON

Returns the document identified by `file_key` as a JSON object. The file key can be parsed from any Figma file url: `https://www.figma.com/file/{file_key}/{title}`.  The `document` property contains a node of type `DOCUMENT`.  The `components` property contains a mapping from node IDs to component metadata. This is to help you determine which components each instance comes from.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

// Configure API key authorization: PersonalAccessToken
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setApiKey('X-Figma-Token', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setApiKeyPrefix('X-Figma-Token', 'Bearer');


$apiInstance = new OpenAPI\Client\Api\FilesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$file_key = 'file_key_example'; // string | File to export JSON from. This can be a file key or branch key. Use `GET /v1/files/:key` with the `branch_data` query param to get the branch key.
$version = 'version_example'; // string | A specific version ID to get. Omitting this will get the current version of the file.
$ids = 'ids_example'; // string | Comma separated list of nodes that you care about in the document. If specified, only a subset of the document will be returned corresponding to the nodes listed, their children, and everything between the root node and the listed nodes.  Note: There may be other nodes included in the returned JSON that are outside the ancestor chains of the desired nodes. The response may also include dependencies of anything in the nodes' subtrees. For example, if a node subtree contains an instance of a local component that lives elsewhere in that file, that component and its ancestor chain will also be included.  For historical reasons, top-level canvas nodes are always returned, regardless of whether they are listed in the `ids` parameter. This quirk may be removed in a future version of the API.
$depth = 3.4; // float | Positive integer representing how deep into the document tree to traverse. For example, setting this to 1 returns only Pages, setting it to 2 returns Pages and all top level objects on each page. Not setting this parameter returns all nodes.
$geometry = 'geometry_example'; // string | Set to \"paths\" to export vector data.
$plugin_data = 'plugin_data_example'; // string | A comma separated list of plugin IDs and/or the string \"shared\". Any data present in the document written by those plugins will be included in the result in the `pluginData` and `sharedPluginData` properties.
$branch_data = false; // bool | Returns branch metadata for the requested file. If the file is a branch, the main file's key will be included in the returned response. If the file has branches, their metadata will be included in the returned response. Default: false.

try {
    $result = $apiInstance->getFile($file_key, $version, $ids, $depth, $geometry, $plugin_data, $branch_data);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling FilesApi->getFile: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **file_key** | **string**| File to export JSON from. This can be a file key or branch key. Use &#x60;GET /v1/files/:key&#x60; with the &#x60;branch_data&#x60; query param to get the branch key. | |
| **version** | **string**| A specific version ID to get. Omitting this will get the current version of the file. | [optional] |
| **ids** | **string**| Comma separated list of nodes that you care about in the document. If specified, only a subset of the document will be returned corresponding to the nodes listed, their children, and everything between the root node and the listed nodes.  Note: There may be other nodes included in the returned JSON that are outside the ancestor chains of the desired nodes. The response may also include dependencies of anything in the nodes&#39; subtrees. For example, if a node subtree contains an instance of a local component that lives elsewhere in that file, that component and its ancestor chain will also be included.  For historical reasons, top-level canvas nodes are always returned, regardless of whether they are listed in the &#x60;ids&#x60; parameter. This quirk may be removed in a future version of the API. | [optional] |
| **depth** | **float**| Positive integer representing how deep into the document tree to traverse. For example, setting this to 1 returns only Pages, setting it to 2 returns Pages and all top level objects on each page. Not setting this parameter returns all nodes. | [optional] |
| **geometry** | **string**| Set to \&quot;paths\&quot; to export vector data. | [optional] |
| **plugin_data** | **string**| A comma separated list of plugin IDs and/or the string \&quot;shared\&quot;. Any data present in the document written by those plugins will be included in the result in the &#x60;pluginData&#x60; and &#x60;sharedPluginData&#x60; properties. | [optional] |
| **branch_data** | **bool**| Returns branch metadata for the requested file. If the file is a branch, the main file&#39;s key will be included in the returned response. If the file has branches, their metadata will be included in the returned response. Default: false. | [optional] [default to false] |

### Return type

[**\OpenAPI\Client\Model\InlineObject**](../Model/InlineObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getFileMeta()`

```php
getFileMeta($file_key): \OpenAPI\Client\Model\InlineObject4
```

Get file metadata

Get file metadata

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

// Configure API key authorization: PersonalAccessToken
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setApiKey('X-Figma-Token', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setApiKeyPrefix('X-Figma-Token', 'Bearer');


$apiInstance = new OpenAPI\Client\Api\FilesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$file_key = 'file_key_example'; // string | File to get metadata for. This can be a file key or branch key. Use `GET /v1/files/:key` with the `branch_data` query param to get the branch key.

try {
    $result = $apiInstance->getFileMeta($file_key);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling FilesApi->getFileMeta: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **file_key** | **string**| File to get metadata for. This can be a file key or branch key. Use &#x60;GET /v1/files/:key&#x60; with the &#x60;branch_data&#x60; query param to get the branch key. | |

### Return type

[**\OpenAPI\Client\Model\InlineObject4**](../Model/InlineObject4.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getFileNodes()`

```php
getFileNodes($file_key, $ids, $version, $depth, $geometry, $plugin_data): \OpenAPI\Client\Model\InlineObject1
```

Get file JSON for specific nodes

Returns the nodes referenced to by `ids` as a JSON object. The nodes are retrieved from the Figma file referenced to by `file_key`.  The node ID and file key can be parsed from any Figma node url: `https://www.figma.com/file/{file_key}/{title}?node-id={id}`  The `name`, `lastModified`, `thumbnailUrl`, `editorType`, and `version` attributes are all metadata of the specified file.  The `linkAccess` field describes the file link share permission level. There are 5 types of permissions a shared link can have: `\"inherit\"`, `\"view\"`, `\"edit\"`, `\"org_view\"`, and `\"org_edit\"`. `\"inherit\"` is the default permission applied to files created in a team project, and will inherit the project's permissions. `\"org_view\"` and `\"org_edit\"` restrict the link to org users.  The `document` attribute contains a Node of type `DOCUMENT`.  The `components` key contains a mapping from node IDs to component metadata. This is to help you determine which components each instance comes from.  By default, no vector data is returned. To return vector data, pass the geometry=paths parameter to the endpoint. Each node can also inherit properties from applicable styles. The styles key contains a mapping from style IDs to style metadata.  Important: the nodes map may contain values that are `null`. This may be due to the node id not existing within the specified file.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

// Configure API key authorization: PersonalAccessToken
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setApiKey('X-Figma-Token', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setApiKeyPrefix('X-Figma-Token', 'Bearer');


$apiInstance = new OpenAPI\Client\Api\FilesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$file_key = 'file_key_example'; // string | File to export JSON from. This can be a file key or branch key. Use `GET /v1/files/:key` with the `branch_data` query param to get the branch key.
$ids = 'ids_example'; // string | A comma separated list of node IDs to retrieve and convert.
$version = 'version_example'; // string | A specific version ID to get. Omitting this will get the current version of the file.
$depth = 3.4; // float | Positive integer representing how deep into the node tree to traverse. For example, setting this to 1 will return only the children directly underneath the desired nodes. Not setting this parameter returns all nodes.  Note: this parameter behaves differently from the same parameter in the `GET /v1/files/:key` endpoint. In this endpoint, the depth will be counted starting from the desired node rather than the document root node.
$geometry = 'geometry_example'; // string | Set to \"paths\" to export vector data.
$plugin_data = 'plugin_data_example'; // string | A comma separated list of plugin IDs and/or the string \"shared\". Any data present in the document written by those plugins will be included in the result in the `pluginData` and `sharedPluginData` properties.

try {
    $result = $apiInstance->getFileNodes($file_key, $ids, $version, $depth, $geometry, $plugin_data);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling FilesApi->getFileNodes: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **file_key** | **string**| File to export JSON from. This can be a file key or branch key. Use &#x60;GET /v1/files/:key&#x60; with the &#x60;branch_data&#x60; query param to get the branch key. | |
| **ids** | **string**| A comma separated list of node IDs to retrieve and convert. | |
| **version** | **string**| A specific version ID to get. Omitting this will get the current version of the file. | [optional] |
| **depth** | **float**| Positive integer representing how deep into the node tree to traverse. For example, setting this to 1 will return only the children directly underneath the desired nodes. Not setting this parameter returns all nodes.  Note: this parameter behaves differently from the same parameter in the &#x60;GET /v1/files/:key&#x60; endpoint. In this endpoint, the depth will be counted starting from the desired node rather than the document root node. | [optional] |
| **geometry** | **string**| Set to \&quot;paths\&quot; to export vector data. | [optional] |
| **plugin_data** | **string**| A comma separated list of plugin IDs and/or the string \&quot;shared\&quot;. Any data present in the document written by those plugins will be included in the result in the &#x60;pluginData&#x60; and &#x60;sharedPluginData&#x60; properties. | [optional] |

### Return type

[**\OpenAPI\Client\Model\InlineObject1**](../Model/InlineObject1.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getFileVersions()`

```php
getFileVersions($file_key, $page_size, $before, $after): \OpenAPI\Client\Model\InlineObject7
```

Get versions of a file

This endpoint fetches the version history of a file, allowing you to see the progression of a file over time. You can then use this information to render a specific version of the file, via another endpoint.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

// Configure API key authorization: PersonalAccessToken
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setApiKey('X-Figma-Token', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setApiKeyPrefix('X-Figma-Token', 'Bearer');


$apiInstance = new OpenAPI\Client\Api\FilesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$file_key = 'file_key_example'; // string | File to get version history from. This can be a file key or branch key. Use `GET /v1/files/:key` with the `branch_data` query param to get the branch key.
$page_size = 3.4; // float | The number of items returned in a page of the response. If not included, `page_size` is `30`.
$before = 3.4; // float | A version ID for one of the versions in the history. Gets versions before this ID. Used for paginating. If the response is not paginated, this link returns the same data in the current response.
$after = 3.4; // float | A version ID for one of the versions in the history. Gets versions after this ID. Used for paginating. If the response is not paginated, this property is not included.

try {
    $result = $apiInstance->getFileVersions($file_key, $page_size, $before, $after);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling FilesApi->getFileVersions: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **file_key** | **string**| File to get version history from. This can be a file key or branch key. Use &#x60;GET /v1/files/:key&#x60; with the &#x60;branch_data&#x60; query param to get the branch key. | |
| **page_size** | **float**| The number of items returned in a page of the response. If not included, &#x60;page_size&#x60; is &#x60;30&#x60;. | [optional] |
| **before** | **float**| A version ID for one of the versions in the history. Gets versions before this ID. Used for paginating. If the response is not paginated, this link returns the same data in the current response. | [optional] |
| **after** | **float**| A version ID for one of the versions in the history. Gets versions after this ID. Used for paginating. If the response is not paginated, this property is not included. | [optional] |

### Return type

[**\OpenAPI\Client\Model\InlineObject7**](../Model/InlineObject7.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getImageFills()`

```php
getImageFills($file_key): \OpenAPI\Client\Model\InlineObject3
```

Get image fills

Returns download links for all images present in image fills in a document. Image fills are how Figma represents any user supplied images. When you drag an image into Figma, we create a rectangle with a single fill that represents the image, and the user is able to transform the rectangle (and properties on the fill) as they wish.  This endpoint returns a mapping from image references to the URLs at which the images may be download. Image URLs will expire after no more than 14 days. Image references are located in the output of the GET files endpoint under the `imageRef` attribute in a `Paint`.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

// Configure API key authorization: PersonalAccessToken
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setApiKey('X-Figma-Token', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setApiKeyPrefix('X-Figma-Token', 'Bearer');


$apiInstance = new OpenAPI\Client\Api\FilesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$file_key = 'file_key_example'; // string | File to get image URLs from. This can be a file key or branch key. Use `GET /v1/files/:key` with the `branch_data` query param to get the branch key.

try {
    $result = $apiInstance->getImageFills($file_key);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling FilesApi->getImageFills: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **file_key** | **string**| File to get image URLs from. This can be a file key or branch key. Use &#x60;GET /v1/files/:key&#x60; with the &#x60;branch_data&#x60; query param to get the branch key. | |

### Return type

[**\OpenAPI\Client\Model\InlineObject3**](../Model/InlineObject3.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getImages()`

```php
getImages($file_key, $ids, $version, $scale, $format, $svg_outline_text, $svg_include_id, $svg_include_node_id, $svg_simplify_stroke, $contents_only, $use_absolute_bounds): \OpenAPI\Client\Model\InlineObject2
```

Render images of file nodes

Renders images from a file.  If no error occurs, `\"images\"` will be populated with a map from node IDs to URLs of the rendered images, and `\"status\"` will be omitted. The image assets will expire after 30 days. Images up to 32 megapixels can be exported. Any images that are larger will be scaled down.  Important: the image map may contain values that are `null`. This indicates that rendering of that specific node has failed. This may be due to the node id not existing, or other reasons such has the node having no renderable components. It is guaranteed that any node that was requested for rendering will be represented in this map whether or not the render succeeded.  To render multiple images from the same file, use the `ids` query parameter to specify multiple node ids.  ``` GET /v1/images/:key?ids=1:2,1:3,1:4 ```

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

// Configure API key authorization: PersonalAccessToken
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setApiKey('X-Figma-Token', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setApiKeyPrefix('X-Figma-Token', 'Bearer');


$apiInstance = new OpenAPI\Client\Api\FilesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$file_key = 'file_key_example'; // string | File to export images from. This can be a file key or branch key. Use `GET /v1/files/:key` with the `branch_data` query param to get the branch key.
$ids = 'ids_example'; // string | A comma separated list of node IDs to render.
$version = 'version_example'; // string | A specific version ID to get. Omitting this will get the current version of the file.
$scale = 3.4; // float | A number between 0.01 and 4, the image scaling factor.
$format = 'png'; // string | A string enum for the image output format.
$svg_outline_text = true; // bool | Whether text elements are rendered as outlines (vector paths) or as `<text>` elements in SVGs.  Rendering text elements as outlines guarantees that the text looks exactly the same in the SVG as it does in the browser/inside Figma.  Exporting as `<text>` allows text to be selectable inside SVGs and generally makes the SVG easier to read. However, this relies on the browser's rendering engine which can vary between browsers and/or operating systems. As such, visual accuracy is not guaranteed as the result could look different than in Figma.
$svg_include_id = false; // bool | Whether to include id attributes for all SVG elements. Adds the layer name to the `id` attribute of an svg element.
$svg_include_node_id = false; // bool | Whether to include node id attributes for all SVG elements. Adds the node id to a `data-node-id` attribute of an svg element.
$svg_simplify_stroke = true; // bool | Whether to simplify inside/outside strokes and use stroke attribute if possible instead of `<mask>`.
$contents_only = true; // bool | Whether content that overlaps the node should be excluded from rendering. Passing false (i.e., rendering overlaps) may increase processing time, since more of the document must be included in rendering.
$use_absolute_bounds = false; // bool | Use the full dimensions of the node regardless of whether or not it is cropped or the space around it is empty. Use this to export text nodes without cropping.

try {
    $result = $apiInstance->getImages($file_key, $ids, $version, $scale, $format, $svg_outline_text, $svg_include_id, $svg_include_node_id, $svg_simplify_stroke, $contents_only, $use_absolute_bounds);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling FilesApi->getImages: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **file_key** | **string**| File to export images from. This can be a file key or branch key. Use &#x60;GET /v1/files/:key&#x60; with the &#x60;branch_data&#x60; query param to get the branch key. | |
| **ids** | **string**| A comma separated list of node IDs to render. | |
| **version** | **string**| A specific version ID to get. Omitting this will get the current version of the file. | [optional] |
| **scale** | **float**| A number between 0.01 and 4, the image scaling factor. | [optional] |
| **format** | **string**| A string enum for the image output format. | [optional] [default to &#39;png&#39;] |
| **svg_outline_text** | **bool**| Whether text elements are rendered as outlines (vector paths) or as &#x60;&lt;text&gt;&#x60; elements in SVGs.  Rendering text elements as outlines guarantees that the text looks exactly the same in the SVG as it does in the browser/inside Figma.  Exporting as &#x60;&lt;text&gt;&#x60; allows text to be selectable inside SVGs and generally makes the SVG easier to read. However, this relies on the browser&#39;s rendering engine which can vary between browsers and/or operating systems. As such, visual accuracy is not guaranteed as the result could look different than in Figma. | [optional] [default to true] |
| **svg_include_id** | **bool**| Whether to include id attributes for all SVG elements. Adds the layer name to the &#x60;id&#x60; attribute of an svg element. | [optional] [default to false] |
| **svg_include_node_id** | **bool**| Whether to include node id attributes for all SVG elements. Adds the node id to a &#x60;data-node-id&#x60; attribute of an svg element. | [optional] [default to false] |
| **svg_simplify_stroke** | **bool**| Whether to simplify inside/outside strokes and use stroke attribute if possible instead of &#x60;&lt;mask&gt;&#x60;. | [optional] [default to true] |
| **contents_only** | **bool**| Whether content that overlaps the node should be excluded from rendering. Passing false (i.e., rendering overlaps) may increase processing time, since more of the document must be included in rendering. | [optional] [default to true] |
| **use_absolute_bounds** | **bool**| Use the full dimensions of the node regardless of whether or not it is cropped or the space around it is empty. Use this to export text nodes without cropping. | [optional] [default to false] |

### Return type

[**\OpenAPI\Client\Model\InlineObject2**](../Model/InlineObject2.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
