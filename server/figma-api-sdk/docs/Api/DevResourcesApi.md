# OpenAPI\Client\DevResourcesApi

All URIs are relative to https://api.figma.com, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**deleteDevResource()**](DevResourcesApi.md#deleteDevResource) | **DELETE** /v1/files/{file_key}/dev_resources/{dev_resource_id} | Delete dev resource |
| [**getDevResources()**](DevResourcesApi.md#getDevResources) | **GET** /v1/files/{file_key}/dev_resources | Get dev resources |
| [**postDevResources()**](DevResourcesApi.md#postDevResources) | **POST** /v1/dev_resources | Create dev resources |
| [**putDevResources()**](DevResourcesApi.md#putDevResources) | **PUT** /v1/dev_resources | Update dev resources |


## `deleteDevResource()`

```php
deleteDevResource($file_key, $dev_resource_id)
```

Delete dev resource

Delete a dev resource from a file

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


$apiInstance = new OpenAPI\Client\Api\DevResourcesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$file_key = 'file_key_example'; // string | The file to delete the dev resource from. This must be a main file key, not a branch key.
$dev_resource_id = 'dev_resource_id_example'; // string | The id of the dev resource to delete.

try {
    $apiInstance->deleteDevResource($file_key, $dev_resource_id);
} catch (Exception $e) {
    echo 'Exception when calling DevResourcesApi->deleteDevResource: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **file_key** | **string**| The file to delete the dev resource from. This must be a main file key, not a branch key. | |
| **dev_resource_id** | **string**| The id of the dev resource to delete. | |

### Return type

void (empty response body)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getDevResources()`

```php
getDevResources($file_key, $node_ids): \OpenAPI\Client\Model\InlineObject29
```

Get dev resources

Get dev resources in a file

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


$apiInstance = new OpenAPI\Client\Api\DevResourcesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$file_key = 'file_key_example'; // string | The file to get the dev resources from. This must be a main file key, not a branch key.
$node_ids = 'node_ids_example'; // string | Comma separated list of nodes that you care about in the document. If specified, only dev resources attached to these nodes will be returned. If not specified, all dev resources in the file will be returned.

try {
    $result = $apiInstance->getDevResources($file_key, $node_ids);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DevResourcesApi->getDevResources: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **file_key** | **string**| The file to get the dev resources from. This must be a main file key, not a branch key. | |
| **node_ids** | **string**| Comma separated list of nodes that you care about in the document. If specified, only dev resources attached to these nodes will be returned. If not specified, all dev resources in the file will be returned. | [optional] |

### Return type

[**\OpenAPI\Client\Model\InlineObject29**](../Model/InlineObject29.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `postDevResources()`

```php
postDevResources($post_dev_resources_request): \OpenAPI\Client\Model\InlineObject30
```

Create dev resources

Bulk create dev resources across multiple files. Dev resources that are successfully created will show up in the links_created array in the response.  If there are any dev resources that cannot be created, you may still get a 200 response. These resources will show up in the errors array. Some reasons a dev resource cannot be created include:  - Resource points to a `file_key` that cannot be found. - The node already has the maximum of 10 dev resources. - Another dev resource for the node has the same url.

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


$apiInstance = new OpenAPI\Client\Api\DevResourcesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$post_dev_resources_request = new \OpenAPI\Client\Model\PostDevResourcesRequest(); // \OpenAPI\Client\Model\PostDevResourcesRequest | A list of dev resources that you want to create.

try {
    $result = $apiInstance->postDevResources($post_dev_resources_request);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DevResourcesApi->postDevResources: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **post_dev_resources_request** | [**\OpenAPI\Client\Model\PostDevResourcesRequest**](../Model/PostDevResourcesRequest.md)| A list of dev resources that you want to create. | |

### Return type

[**\OpenAPI\Client\Model\InlineObject30**](../Model/InlineObject30.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `putDevResources()`

```php
putDevResources($put_dev_resources_request): \OpenAPI\Client\Model\InlineObject31
```

Update dev resources

Bulk update dev resources across multiple files.  Ids for dev resources that are successfully updated will show up in the `links_updated` array in the response.  If there are any dev resources that cannot be updated, you may still get a 200 response. These resources will show up in the `errors` array.

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


$apiInstance = new OpenAPI\Client\Api\DevResourcesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$put_dev_resources_request = new \OpenAPI\Client\Model\PutDevResourcesRequest(); // \OpenAPI\Client\Model\PutDevResourcesRequest | A list of dev resources that you want to update.

try {
    $result = $apiInstance->putDevResources($put_dev_resources_request);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DevResourcesApi->putDevResources: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **put_dev_resources_request** | [**\OpenAPI\Client\Model\PutDevResourcesRequest**](../Model/PutDevResourcesRequest.md)| A list of dev resources that you want to update. | |

### Return type

[**\OpenAPI\Client\Model\InlineObject31**](../Model/InlineObject31.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
