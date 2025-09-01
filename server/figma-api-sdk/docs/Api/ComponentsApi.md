# OpenAPI\Client\ComponentsApi

All URIs are relative to https://api.figma.com, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getComponent()**](ComponentsApi.md#getComponent) | **GET** /v1/components/{key} | Get component |
| [**getFileComponents()**](ComponentsApi.md#getFileComponents) | **GET** /v1/files/{file_key}/components | Get file components |
| [**getTeamComponents()**](ComponentsApi.md#getTeamComponents) | **GET** /v1/teams/{team_id}/components | Get team components |


## `getComponent()`

```php
getComponent($key): \OpenAPI\Client\Model\InlineObject14
```

Get component

Get metadata on a component by key.

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


$apiInstance = new OpenAPI\Client\Api\ComponentsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$key = 'key_example'; // string | The unique identifier of the component.

try {
    $result = $apiInstance->getComponent($key);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ComponentsApi->getComponent: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **key** | **string**| The unique identifier of the component. | |

### Return type

[**\OpenAPI\Client\Model\InlineObject14**](../Model/InlineObject14.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getFileComponents()`

```php
getFileComponents($file_key): \OpenAPI\Client\Model\InlineObject13
```

Get file components

Get a list of published components within a file library.

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


$apiInstance = new OpenAPI\Client\Api\ComponentsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$file_key = 'file_key_example'; // string | File to list components from. This must be a main file key, not a branch key, as it is not possible to publish from branches.

try {
    $result = $apiInstance->getFileComponents($file_key);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ComponentsApi->getFileComponents: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **file_key** | **string**| File to list components from. This must be a main file key, not a branch key, as it is not possible to publish from branches. | |

### Return type

[**\OpenAPI\Client\Model\InlineObject13**](../Model/InlineObject13.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getTeamComponents()`

```php
getTeamComponents($team_id, $page_size, $after, $before): \OpenAPI\Client\Model\InlineObject12
```

Get team components

Get a paginated list of published components within a team library.

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


$apiInstance = new OpenAPI\Client\Api\ComponentsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$team_id = 'team_id_example'; // string | Id of the team to list components from.
$page_size = 30; // float | Number of items to return in a paged list of results. Defaults to 30. Maximum of 1000.
$after = 3.4; // float | Cursor indicating which id after which to start retrieving components for. Exclusive with before. The cursor value is an internally tracked integer that doesn't correspond to any Ids.
$before = 3.4; // float | Cursor indicating which id before which to start retrieving components for. Exclusive with after. The cursor value is an internally tracked integer that doesn't correspond to any Ids.

try {
    $result = $apiInstance->getTeamComponents($team_id, $page_size, $after, $before);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ComponentsApi->getTeamComponents: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **team_id** | **string**| Id of the team to list components from. | |
| **page_size** | **float**| Number of items to return in a paged list of results. Defaults to 30. Maximum of 1000. | [optional] [default to 30] |
| **after** | **float**| Cursor indicating which id after which to start retrieving components for. Exclusive with before. The cursor value is an internally tracked integer that doesn&#39;t correspond to any Ids. | [optional] |
| **before** | **float**| Cursor indicating which id before which to start retrieving components for. Exclusive with after. The cursor value is an internally tracked integer that doesn&#39;t correspond to any Ids. | [optional] |

### Return type

[**\OpenAPI\Client\Model\InlineObject12**](../Model/InlineObject12.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
