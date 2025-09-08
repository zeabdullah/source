# OpenAPI\Client\ActivityLogsApi

All URIs are relative to https://api.figma.com, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getActivityLogs()**](ActivityLogsApi.md#getActivityLogs) | **GET** /v1/activity_logs | Get activity logs |


## `getActivityLogs()`

```php
getActivityLogs($events, $start_time, $end_time, $limit, $order): \OpenAPI\Client\Model\InlineObject24
```

Get activity logs

Returns a list of activity log events

### Example

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

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **events** | **string**| Event type(s) to include in the response. Can have multiple values separated by comma. All events are returned by default. | [optional] |
| **start_time** | **float**| Unix timestamp of the least recent event to include. This param defaults to one year ago if unspecified. | [optional] |
| **end_time** | **float**| Unix timestamp of the most recent event to include. This param defaults to the current timestamp if unspecified. | [optional] |
| **limit** | **float**| Maximum number of events to return. This param defaults to 1000 if unspecified. | [optional] |
| **order** | **string**| Event order by timestamp. This param can be either \&quot;asc\&quot; (default) or \&quot;desc\&quot;. | [optional] [default to &#39;asc&#39;] |

### Return type

[**\OpenAPI\Client\Model\InlineObject24**](../Model/InlineObject24.md)

### Authorization

[OrgOAuth2](../../README.md#OrgOAuth2)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
