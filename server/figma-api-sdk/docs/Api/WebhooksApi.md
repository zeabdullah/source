# OpenAPI\Client\WebhooksApi

All URIs are relative to https://api.figma.com, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**deleteWebhook()**](WebhooksApi.md#deleteWebhook) | **DELETE** /v2/webhooks/{webhook_id} | Delete a webhook |
| [**getTeamWebhooks()**](WebhooksApi.md#getTeamWebhooks) | **GET** /v2/teams/{team_id}/webhooks | [Deprecated] Get team webhooks |
| [**getWebhook()**](WebhooksApi.md#getWebhook) | **GET** /v2/webhooks/{webhook_id} | Get a webhook |
| [**getWebhookRequests()**](WebhooksApi.md#getWebhookRequests) | **GET** /v2/webhooks/{webhook_id}/requests | Get webhook requests |
| [**getWebhooks()**](WebhooksApi.md#getWebhooks) | **GET** /v2/webhooks | Get webhooks by context or plan |
| [**postWebhook()**](WebhooksApi.md#postWebhook) | **POST** /v2/webhooks | Create a webhook |
| [**putWebhook()**](WebhooksApi.md#putWebhook) | **PUT** /v2/webhooks/{webhook_id} | Update a webhook |


## `deleteWebhook()`

```php
deleteWebhook($webhook_id): \OpenAPI\Client\Model\WebhookV2
```

Delete a webhook

Deletes the specified webhook. This operation cannot be reversed.

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


$apiInstance = new OpenAPI\Client\Api\WebhooksApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$webhook_id = 'webhook_id_example'; // string | ID of webhook to delete

try {
    $result = $apiInstance->deleteWebhook($webhook_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling WebhooksApi->deleteWebhook: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **webhook_id** | **string**| ID of webhook to delete | |

### Return type

[**\OpenAPI\Client\Model\WebhookV2**](../Model/WebhookV2.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getTeamWebhooks()`

```php
getTeamWebhooks($team_id): \OpenAPI\Client\Model\InlineObject22
```

[Deprecated] Get team webhooks

Returns all webhooks registered under the specified team.

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


$apiInstance = new OpenAPI\Client\Api\WebhooksApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$team_id = 'team_id_example'; // string | ID of team to get webhooks for

try {
    $result = $apiInstance->getTeamWebhooks($team_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling WebhooksApi->getTeamWebhooks: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **team_id** | **string**| ID of team to get webhooks for | |

### Return type

[**\OpenAPI\Client\Model\InlineObject22**](../Model/InlineObject22.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getWebhook()`

```php
getWebhook($webhook_id): \OpenAPI\Client\Model\WebhookV2
```

Get a webhook

Get a webhook by ID.

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


$apiInstance = new OpenAPI\Client\Api\WebhooksApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$webhook_id = 'webhook_id_example'; // string | ID of webhook to get

try {
    $result = $apiInstance->getWebhook($webhook_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling WebhooksApi->getWebhook: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **webhook_id** | **string**| ID of webhook to get | |

### Return type

[**\OpenAPI\Client\Model\WebhookV2**](../Model/WebhookV2.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getWebhookRequests()`

```php
getWebhookRequests($webhook_id): \OpenAPI\Client\Model\InlineObject23
```

Get webhook requests

Returns all webhook requests sent within the last week. Useful for debugging.

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


$apiInstance = new OpenAPI\Client\Api\WebhooksApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$webhook_id = 'webhook_id_example'; // string | The id of the webhook subscription you want to see events from

try {
    $result = $apiInstance->getWebhookRequests($webhook_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling WebhooksApi->getWebhookRequests: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **webhook_id** | **string**| The id of the webhook subscription you want to see events from | |

### Return type

[**\OpenAPI\Client\Model\InlineObject23**](../Model/InlineObject23.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getWebhooks()`

```php
getWebhooks($context, $context_id, $plan_api_id, $cursor): \OpenAPI\Client\Model\InlineObject21
```

Get webhooks by context or plan

Returns a list of webhooks corresponding to the context or plan provided, if they exist. For plan, the webhooks for all contexts that you have access to will be returned, and theresponse is paginated

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


$apiInstance = new OpenAPI\Client\Api\WebhooksApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$context = 'context_example'; // string | Context to create the resource on. Should be \"team\", \"project\", or \"file\".
$context_id = 'context_id_example'; // string | The id of the context that you want to get attached webhooks for. If you're using context_id, you cannot use plan_api_id.
$plan_api_id = 'plan_api_id_example'; // string | The id of your plan. Use this to get all webhooks for all contexts you have access to. If you're using plan_api_id, you cannot use context or context_id. When you use plan_api_id, the response is paginated.
$cursor = 'cursor_example'; // string | If you're using plan_api_id, this is the cursor to use for pagination. If you're using context or context_id, this parameter is ignored. Provide the next_page or prev_page value from the previous response to get the next or previous page of results.

try {
    $result = $apiInstance->getWebhooks($context, $context_id, $plan_api_id, $cursor);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling WebhooksApi->getWebhooks: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **context** | **string**| Context to create the resource on. Should be \&quot;team\&quot;, \&quot;project\&quot;, or \&quot;file\&quot;. | [optional] |
| **context_id** | **string**| The id of the context that you want to get attached webhooks for. If you&#39;re using context_id, you cannot use plan_api_id. | [optional] |
| **plan_api_id** | **string**| The id of your plan. Use this to get all webhooks for all contexts you have access to. If you&#39;re using plan_api_id, you cannot use context or context_id. When you use plan_api_id, the response is paginated. | [optional] |
| **cursor** | **string**| If you&#39;re using plan_api_id, this is the cursor to use for pagination. If you&#39;re using context or context_id, this parameter is ignored. Provide the next_page or prev_page value from the previous response to get the next or previous page of results. | [optional] |

### Return type

[**\OpenAPI\Client\Model\InlineObject21**](../Model/InlineObject21.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `postWebhook()`

```php
postWebhook($post_webhook_request): \OpenAPI\Client\Model\WebhookV2
```

Create a webhook

Create a new webhook which will call the specified endpoint when the event triggers. By default, this webhook will automatically send a PING event to the endpoint when it is created. If this behavior is not desired, you can create the webhook and set the status to PAUSED and reactivate it later.

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


$apiInstance = new OpenAPI\Client\Api\WebhooksApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$post_webhook_request = new \OpenAPI\Client\Model\PostWebhookRequest(); // \OpenAPI\Client\Model\PostWebhookRequest | The webhook to create.

try {
    $result = $apiInstance->postWebhook($post_webhook_request);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling WebhooksApi->postWebhook: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **post_webhook_request** | [**\OpenAPI\Client\Model\PostWebhookRequest**](../Model/PostWebhookRequest.md)| The webhook to create. | |

### Return type

[**\OpenAPI\Client\Model\WebhookV2**](../Model/WebhookV2.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `putWebhook()`

```php
putWebhook($webhook_id, $put_webhook_request): \OpenAPI\Client\Model\WebhookV2
```

Update a webhook

Update a webhook by ID.

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


$apiInstance = new OpenAPI\Client\Api\WebhooksApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$webhook_id = 'webhook_id_example'; // string | ID of webhook to update
$put_webhook_request = new \OpenAPI\Client\Model\PutWebhookRequest(); // \OpenAPI\Client\Model\PutWebhookRequest | The webhook to update.

try {
    $result = $apiInstance->putWebhook($webhook_id, $put_webhook_request);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling WebhooksApi->putWebhook: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **webhook_id** | **string**| ID of webhook to update | |
| **put_webhook_request** | [**\OpenAPI\Client\Model\PutWebhookRequest**](../Model/PutWebhookRequest.md)| The webhook to update. | |

### Return type

[**\OpenAPI\Client\Model\WebhookV2**](../Model/WebhookV2.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
