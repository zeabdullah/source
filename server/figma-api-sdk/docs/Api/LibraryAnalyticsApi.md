# OpenAPI\Client\LibraryAnalyticsApi

All URIs are relative to https://api.figma.com, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getLibraryAnalyticsComponentActions()**](LibraryAnalyticsApi.md#getLibraryAnalyticsComponentActions) | **GET** /v1/analytics/libraries/{file_key}/component/actions | Get library analytics component action data. |
| [**getLibraryAnalyticsComponentUsages()**](LibraryAnalyticsApi.md#getLibraryAnalyticsComponentUsages) | **GET** /v1/analytics/libraries/{file_key}/component/usages | Get library analytics component usage data. |
| [**getLibraryAnalyticsStyleActions()**](LibraryAnalyticsApi.md#getLibraryAnalyticsStyleActions) | **GET** /v1/analytics/libraries/{file_key}/style/actions | Get library analytics style action data. |
| [**getLibraryAnalyticsStyleUsages()**](LibraryAnalyticsApi.md#getLibraryAnalyticsStyleUsages) | **GET** /v1/analytics/libraries/{file_key}/style/usages | Get library analytics style usage data. |
| [**getLibraryAnalyticsVariableActions()**](LibraryAnalyticsApi.md#getLibraryAnalyticsVariableActions) | **GET** /v1/analytics/libraries/{file_key}/variable/actions | Get library analytics variable action data. |
| [**getLibraryAnalyticsVariableUsages()**](LibraryAnalyticsApi.md#getLibraryAnalyticsVariableUsages) | **GET** /v1/analytics/libraries/{file_key}/variable/usages | Get library analytics variable usage data. |


## `getLibraryAnalyticsComponentActions()`

```php
getLibraryAnalyticsComponentActions($file_key, $group_by, $cursor, $start_date, $end_date): \OpenAPI\Client\Model\InlineObject32
```

Get library analytics component action data.

Returns a list of library analytics component actions data broken down by the requested dimension.

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


$apiInstance = new OpenAPI\Client\Api\LibraryAnalyticsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$file_key = 'file_key_example'; // string | File key of the library to fetch analytics data for.
$group_by = 'group_by_example'; // string | A dimension to group returned analytics data by.
$cursor = 'cursor_example'; // string | Cursor indicating what page of data to fetch. Obtained from prior API call.
$start_date = 'start_date_example'; // string | ISO 8601 date string (YYYY-MM-DD) of the earliest week to include. Dates are rounded back to the nearest start of a week. Defaults to one year prior.
$end_date = 'end_date_example'; // string | ISO 8601 date string (YYYY-MM-DD) of the latest week to include. Dates are rounded forward to the nearest end of a week. Defaults to the latest computed week.

try {
    $result = $apiInstance->getLibraryAnalyticsComponentActions($file_key, $group_by, $cursor, $start_date, $end_date);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling LibraryAnalyticsApi->getLibraryAnalyticsComponentActions: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **file_key** | **string**| File key of the library to fetch analytics data for. | |
| **group_by** | **string**| A dimension to group returned analytics data by. | |
| **cursor** | **string**| Cursor indicating what page of data to fetch. Obtained from prior API call. | [optional] |
| **start_date** | **string**| ISO 8601 date string (YYYY-MM-DD) of the earliest week to include. Dates are rounded back to the nearest start of a week. Defaults to one year prior. | [optional] |
| **end_date** | **string**| ISO 8601 date string (YYYY-MM-DD) of the latest week to include. Dates are rounded forward to the nearest end of a week. Defaults to the latest computed week. | [optional] |

### Return type

[**\OpenAPI\Client\Model\InlineObject32**](../Model/InlineObject32.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getLibraryAnalyticsComponentUsages()`

```php
getLibraryAnalyticsComponentUsages($file_key, $group_by, $cursor): \OpenAPI\Client\Model\InlineObject33
```

Get library analytics component usage data.

Returns a list of library analytics component usage data broken down by the requested dimension.

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


$apiInstance = new OpenAPI\Client\Api\LibraryAnalyticsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$file_key = 'file_key_example'; // string | File key of the library to fetch analytics data for.
$group_by = 'group_by_example'; // string | A dimension to group returned analytics data by.
$cursor = 'cursor_example'; // string | Cursor indicating what page of data to fetch. Obtained from prior API call.

try {
    $result = $apiInstance->getLibraryAnalyticsComponentUsages($file_key, $group_by, $cursor);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling LibraryAnalyticsApi->getLibraryAnalyticsComponentUsages: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **file_key** | **string**| File key of the library to fetch analytics data for. | |
| **group_by** | **string**| A dimension to group returned analytics data by. | |
| **cursor** | **string**| Cursor indicating what page of data to fetch. Obtained from prior API call. | [optional] |

### Return type

[**\OpenAPI\Client\Model\InlineObject33**](../Model/InlineObject33.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getLibraryAnalyticsStyleActions()`

```php
getLibraryAnalyticsStyleActions($file_key, $group_by, $cursor, $start_date, $end_date): \OpenAPI\Client\Model\InlineObject34
```

Get library analytics style action data.

Returns a list of library analytics style actions data broken down by the requested dimension.

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


$apiInstance = new OpenAPI\Client\Api\LibraryAnalyticsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$file_key = 'file_key_example'; // string | File key of the library to fetch analytics data for.
$group_by = 'group_by_example'; // string | A dimension to group returned analytics data by.
$cursor = 'cursor_example'; // string | Cursor indicating what page of data to fetch. Obtained from prior API call.
$start_date = 'start_date_example'; // string | ISO 8601 date string (YYYY-MM-DD) of the earliest week to include. Dates are rounded back to the nearest start of a week. Defaults to one year prior.
$end_date = 'end_date_example'; // string | ISO 8601 date string (YYYY-MM-DD) of the latest week to include. Dates are rounded forward to the nearest end of a week. Defaults to the latest computed week.

try {
    $result = $apiInstance->getLibraryAnalyticsStyleActions($file_key, $group_by, $cursor, $start_date, $end_date);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling LibraryAnalyticsApi->getLibraryAnalyticsStyleActions: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **file_key** | **string**| File key of the library to fetch analytics data for. | |
| **group_by** | **string**| A dimension to group returned analytics data by. | |
| **cursor** | **string**| Cursor indicating what page of data to fetch. Obtained from prior API call. | [optional] |
| **start_date** | **string**| ISO 8601 date string (YYYY-MM-DD) of the earliest week to include. Dates are rounded back to the nearest start of a week. Defaults to one year prior. | [optional] |
| **end_date** | **string**| ISO 8601 date string (YYYY-MM-DD) of the latest week to include. Dates are rounded forward to the nearest end of a week. Defaults to the latest computed week. | [optional] |

### Return type

[**\OpenAPI\Client\Model\InlineObject34**](../Model/InlineObject34.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getLibraryAnalyticsStyleUsages()`

```php
getLibraryAnalyticsStyleUsages($file_key, $group_by, $cursor): \OpenAPI\Client\Model\InlineObject35
```

Get library analytics style usage data.

Returns a list of library analytics style usage data broken down by the requested dimension.

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


$apiInstance = new OpenAPI\Client\Api\LibraryAnalyticsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$file_key = 'file_key_example'; // string | File key of the library to fetch analytics data for.
$group_by = 'group_by_example'; // string | A dimension to group returned analytics data by.
$cursor = 'cursor_example'; // string | Cursor indicating what page of data to fetch. Obtained from prior API call.

try {
    $result = $apiInstance->getLibraryAnalyticsStyleUsages($file_key, $group_by, $cursor);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling LibraryAnalyticsApi->getLibraryAnalyticsStyleUsages: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **file_key** | **string**| File key of the library to fetch analytics data for. | |
| **group_by** | **string**| A dimension to group returned analytics data by. | |
| **cursor** | **string**| Cursor indicating what page of data to fetch. Obtained from prior API call. | [optional] |

### Return type

[**\OpenAPI\Client\Model\InlineObject35**](../Model/InlineObject35.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getLibraryAnalyticsVariableActions()`

```php
getLibraryAnalyticsVariableActions($file_key, $group_by, $cursor, $start_date, $end_date): \OpenAPI\Client\Model\InlineObject36
```

Get library analytics variable action data.

Returns a list of library analytics variable actions data broken down by the requested dimension.

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


$apiInstance = new OpenAPI\Client\Api\LibraryAnalyticsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$file_key = 'file_key_example'; // string | File key of the library to fetch analytics data for.
$group_by = 'group_by_example'; // string | A dimension to group returned analytics data by.
$cursor = 'cursor_example'; // string | Cursor indicating what page of data to fetch. Obtained from prior API call.
$start_date = 'start_date_example'; // string | ISO 8601 date string (YYYY-MM-DD) of the earliest week to include. Dates are rounded back to the nearest start of a week. Defaults to one year prior.
$end_date = 'end_date_example'; // string | ISO 8601 date string (YYYY-MM-DD) of the latest week to include. Dates are rounded forward to the nearest end of a week. Defaults to the latest computed week.

try {
    $result = $apiInstance->getLibraryAnalyticsVariableActions($file_key, $group_by, $cursor, $start_date, $end_date);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling LibraryAnalyticsApi->getLibraryAnalyticsVariableActions: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **file_key** | **string**| File key of the library to fetch analytics data for. | |
| **group_by** | **string**| A dimension to group returned analytics data by. | |
| **cursor** | **string**| Cursor indicating what page of data to fetch. Obtained from prior API call. | [optional] |
| **start_date** | **string**| ISO 8601 date string (YYYY-MM-DD) of the earliest week to include. Dates are rounded back to the nearest start of a week. Defaults to one year prior. | [optional] |
| **end_date** | **string**| ISO 8601 date string (YYYY-MM-DD) of the latest week to include. Dates are rounded forward to the nearest end of a week. Defaults to the latest computed week. | [optional] |

### Return type

[**\OpenAPI\Client\Model\InlineObject36**](../Model/InlineObject36.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getLibraryAnalyticsVariableUsages()`

```php
getLibraryAnalyticsVariableUsages($file_key, $group_by, $cursor): \OpenAPI\Client\Model\InlineObject37
```

Get library analytics variable usage data.

Returns a list of library analytics variable usage data broken down by the requested dimension.

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


$apiInstance = new OpenAPI\Client\Api\LibraryAnalyticsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$file_key = 'file_key_example'; // string | File key of the library to fetch analytics data for.
$group_by = 'group_by_example'; // string | A dimension to group returned analytics data by.
$cursor = 'cursor_example'; // string | Cursor indicating what page of data to fetch. Obtained from prior API call.

try {
    $result = $apiInstance->getLibraryAnalyticsVariableUsages($file_key, $group_by, $cursor);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling LibraryAnalyticsApi->getLibraryAnalyticsVariableUsages: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **file_key** | **string**| File key of the library to fetch analytics data for. | |
| **group_by** | **string**| A dimension to group returned analytics data by. | |
| **cursor** | **string**| Cursor indicating what page of data to fetch. Obtained from prior API call. | [optional] |

### Return type

[**\OpenAPI\Client\Model\InlineObject37**](../Model/InlineObject37.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
