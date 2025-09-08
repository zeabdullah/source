# OpenAPI\Client\StylesApi

All URIs are relative to https://api.figma.com, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getFileStyles()**](StylesApi.md#getFileStyles) | **GET** /v1/files/{file_key}/styles | Get file styles |
| [**getStyle()**](StylesApi.md#getStyle) | **GET** /v1/styles/{key} | Get style |
| [**getTeamStyles()**](StylesApi.md#getTeamStyles) | **GET** /v1/teams/{team_id}/styles | Get team styles |


## `getFileStyles()`

```php
getFileStyles($file_key): \OpenAPI\Client\Model\InlineObject19
```

Get file styles

Get a list of published styles within a file library.

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


$apiInstance = new OpenAPI\Client\Api\StylesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$file_key = 'file_key_example'; // string | File to list styles from. This must be a main file key, not a branch key, as it is not possible to publish from branches.

try {
    $result = $apiInstance->getFileStyles($file_key);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling StylesApi->getFileStyles: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **file_key** | **string**| File to list styles from. This must be a main file key, not a branch key, as it is not possible to publish from branches. | |

### Return type

[**\OpenAPI\Client\Model\InlineObject19**](../Model/InlineObject19.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getStyle()`

```php
getStyle($key): \OpenAPI\Client\Model\InlineObject20
```

Get style

Get metadata on a style by key.

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


$apiInstance = new OpenAPI\Client\Api\StylesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$key = 'key_example'; // string | The unique identifier of the style.

try {
    $result = $apiInstance->getStyle($key);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling StylesApi->getStyle: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **key** | **string**| The unique identifier of the style. | |

### Return type

[**\OpenAPI\Client\Model\InlineObject20**](../Model/InlineObject20.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getTeamStyles()`

```php
getTeamStyles($team_id, $page_size, $after, $before): \OpenAPI\Client\Model\InlineObject18
```

Get team styles

Get a paginated list of published styles within a team library.

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


$apiInstance = new OpenAPI\Client\Api\StylesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$team_id = 'team_id_example'; // string | Id of the team to list styles from.
$page_size = 30; // float | Number of items to return in a paged list of results. Defaults to 30.
$after = 3.4; // float | Cursor indicating which id after which to start retrieving styles for. Exclusive with before. The cursor value is an internally tracked integer that doesn't correspond to any Ids.
$before = 3.4; // float | Cursor indicating which id before which to start retrieving styles for. Exclusive with after. The cursor value is an internally tracked integer that doesn't correspond to any Ids.

try {
    $result = $apiInstance->getTeamStyles($team_id, $page_size, $after, $before);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling StylesApi->getTeamStyles: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **team_id** | **string**| Id of the team to list styles from. | |
| **page_size** | **float**| Number of items to return in a paged list of results. Defaults to 30. | [optional] [default to 30] |
| **after** | **float**| Cursor indicating which id after which to start retrieving styles for. Exclusive with before. The cursor value is an internally tracked integer that doesn&#39;t correspond to any Ids. | [optional] |
| **before** | **float**| Cursor indicating which id before which to start retrieving styles for. Exclusive with after. The cursor value is an internally tracked integer that doesn&#39;t correspond to any Ids. | [optional] |

### Return type

[**\OpenAPI\Client\Model\InlineObject18**](../Model/InlineObject18.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
