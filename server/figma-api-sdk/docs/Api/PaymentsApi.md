# OpenAPI\Client\PaymentsApi

All URIs are relative to https://api.figma.com, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getPayments()**](PaymentsApi.md#getPayments) | **GET** /v1/payments | Get payments |


## `getPayments()`

```php
getPayments($plugin_payment_token, $user_id, $community_file_id, $plugin_id, $widget_id): \OpenAPI\Client\Model\InlineObject25
```

Get payments

There are two methods to query for a user's payment information on a plugin, widget, or Community file. The first method, using plugin payment tokens, is typically used when making queries from a plugin's or widget's code. The second method, providing a user ID and resource ID, is typically used when making queries from anywhere else.  Note that you can only query for resources that you own. In most cases, this means that you can only query resources that you originally created.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure API key authorization: PersonalAccessToken
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setApiKey('X-Figma-Token', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setApiKeyPrefix('X-Figma-Token', 'Bearer');


$apiInstance = new OpenAPI\Client\Api\PaymentsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$plugin_payment_token = 'plugin_payment_token_example'; // string | Short-lived token returned from \"getPluginPaymentTokenAsync\" in the plugin payments API and used to authenticate to this endpoint. Read more about generating this token through \"Calling the Payments REST API from a plugin or widget\" below.
$user_id = 'user_id_example'; // string | The ID of the user to query payment information about. You can get the user ID by having the user OAuth2 to the Figma REST API.
$community_file_id = 'community_file_id_example'; // string | The ID of the Community file to query a user's payment information on. You can get the Community file ID from the file's Community page (look for the number after \"file/\" in the URL). Provide exactly one of \"community_file_id\", \"plugin_id\", or \"widget_id\".
$plugin_id = 'plugin_id_example'; // string | The ID of the plugin to query a user's payment information on. You can get the plugin ID from the plugin's manifest, or from the plugin's Community page (look for the number after \"plugin/\" in the URL). Provide exactly one of \"community_file_id\", \"plugin_id\", or \"widget_id\".
$widget_id = 'widget_id_example'; // string | The ID of the widget to query a user's payment information on. You can get the widget ID from the widget's manifest, or from the widget's Community page (look for the number after \"widget/\" in the URL). Provide exactly one of \"community_file_id\", \"plugin_id\", or \"widget_id\".

try {
    $result = $apiInstance->getPayments($plugin_payment_token, $user_id, $community_file_id, $plugin_id, $widget_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PaymentsApi->getPayments: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **plugin_payment_token** | **string**| Short-lived token returned from \&quot;getPluginPaymentTokenAsync\&quot; in the plugin payments API and used to authenticate to this endpoint. Read more about generating this token through \&quot;Calling the Payments REST API from a plugin or widget\&quot; below. | [optional] |
| **user_id** | **string**| The ID of the user to query payment information about. You can get the user ID by having the user OAuth2 to the Figma REST API. | [optional] |
| **community_file_id** | **string**| The ID of the Community file to query a user&#39;s payment information on. You can get the Community file ID from the file&#39;s Community page (look for the number after \&quot;file/\&quot; in the URL). Provide exactly one of \&quot;community_file_id\&quot;, \&quot;plugin_id\&quot;, or \&quot;widget_id\&quot;. | [optional] |
| **plugin_id** | **string**| The ID of the plugin to query a user&#39;s payment information on. You can get the plugin ID from the plugin&#39;s manifest, or from the plugin&#39;s Community page (look for the number after \&quot;plugin/\&quot; in the URL). Provide exactly one of \&quot;community_file_id\&quot;, \&quot;plugin_id\&quot;, or \&quot;widget_id\&quot;. | [optional] |
| **widget_id** | **string**| The ID of the widget to query a user&#39;s payment information on. You can get the widget ID from the widget&#39;s manifest, or from the widget&#39;s Community page (look for the number after \&quot;widget/\&quot; in the URL). Provide exactly one of \&quot;community_file_id\&quot;, \&quot;plugin_id\&quot;, or \&quot;widget_id\&quot;. | [optional] |

### Return type

[**\OpenAPI\Client\Model\InlineObject25**](../Model/InlineObject25.md)

### Authorization

[PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
