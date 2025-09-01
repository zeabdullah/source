# OpenAPI\Client\ComponentSetsApi

All URIs are relative to https://api.figma.com, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getComponentSet()**](ComponentSetsApi.md#getComponentSet) | **GET** /v1/component_sets/{key} | Get component set |
| [**getFileComponentSets()**](ComponentSetsApi.md#getFileComponentSets) | **GET** /v1/files/{file_key}/component_sets | Get file component sets |
| [**getTeamComponentSets()**](ComponentSetsApi.md#getTeamComponentSets) | **GET** /v1/teams/{team_id}/component_sets | Get team component sets |


## `getComponentSet()`

```php
getComponentSet($key): \OpenAPI\Client\Model\InlineObject17
```

Get component set

Get metadata on a published component set by key.

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


$apiInstance = new OpenAPI\Client\Api\ComponentSetsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$key = 'key_example'; // string | The unique identifier of the component set.

try {
    $result = $apiInstance->getComponentSet($key);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ComponentSetsApi->getComponentSet: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **key** | **string**| The unique identifier of the component set. | |

### Return type

[**\OpenAPI\Client\Model\InlineObject17**](../Model/InlineObject17.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getFileComponentSets()`

```php
getFileComponentSets($file_key): \OpenAPI\Client\Model\InlineObject16
```

Get file component sets

Get a list of published component sets within a file library.

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


$apiInstance = new OpenAPI\Client\Api\ComponentSetsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$file_key = 'file_key_example'; // string | File to list component sets from. This must be a main file key, not a branch key, as it is not possible to publish from branches.

try {
    $result = $apiInstance->getFileComponentSets($file_key);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ComponentSetsApi->getFileComponentSets: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **file_key** | **string**| File to list component sets from. This must be a main file key, not a branch key, as it is not possible to publish from branches. | |

### Return type

[**\OpenAPI\Client\Model\InlineObject16**](../Model/InlineObject16.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getTeamComponentSets()`

```php
getTeamComponentSets($team_id, $page_size, $after, $before): \OpenAPI\Client\Model\InlineObject15
```

Get team component sets

Get a paginated list of published component sets within a team library.

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


$apiInstance = new OpenAPI\Client\Api\ComponentSetsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$team_id = 'team_id_example'; // string | Id of the team to list component sets from.
$page_size = 30; // float | Number of items to return in a paged list of results. Defaults to 30.
$after = 3.4; // float | Cursor indicating which id after which to start retrieving component sets for. Exclusive with before. The cursor value is an internally tracked integer that doesn't correspond to any Ids.
$before = 3.4; // float | Cursor indicating which id before which to start retrieving component sets for. Exclusive with after. The cursor value is an internally tracked integer that doesn't correspond to any Ids.

try {
    $result = $apiInstance->getTeamComponentSets($team_id, $page_size, $after, $before);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ComponentSetsApi->getTeamComponentSets: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **team_id** | **string**| Id of the team to list component sets from. | |
| **page_size** | **float**| Number of items to return in a paged list of results. Defaults to 30. | [optional] [default to 30] |
| **after** | **float**| Cursor indicating which id after which to start retrieving component sets for. Exclusive with before. The cursor value is an internally tracked integer that doesn&#39;t correspond to any Ids. | [optional] |
| **before** | **float**| Cursor indicating which id before which to start retrieving component sets for. Exclusive with after. The cursor value is an internally tracked integer that doesn&#39;t correspond to any Ids. | [optional] |

### Return type

[**\OpenAPI\Client\Model\InlineObject15**](../Model/InlineObject15.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
