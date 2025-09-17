# OpenAPI\Client\VariablesApi

All URIs are relative to https://api.figma.com, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getLocalVariables()**](VariablesApi.md#getLocalVariables) | **GET** /v1/files/{file_key}/variables/local | Get local variables |
| [**getPublishedVariables()**](VariablesApi.md#getPublishedVariables) | **GET** /v1/files/{file_key}/variables/published | Get published variables |
| [**postVariables()**](VariablesApi.md#postVariables) | **POST** /v1/files/{file_key}/variables | Create/modify/delete variables |


## `getLocalVariables()`

```php
getLocalVariables($file_key): \OpenAPI\Client\Model\InlineObject26
```

Get local variables

**This API is available to full members of Enterprise orgs.**  The `GET /v1/files/:file_key/variables/local` endpoint lets you enumerate local variables created in the file and remote variables used in the file. Remote variables are referenced by their `subscribed_id`.  As a part of the Variables related API additions, the `GET /v1/files/:file_key` endpoint now returns a `boundVariables` property, containing the `variableId` of the bound variable. The `GET /v1/files/:file_key/variables/local` endpoint can be used to get the full variable or variable collection object.  Note that `GET /v1/files/:file_key/variables/published` does not return modes. Instead, you will need to use the `GET /v1/files/:file_key/variables/local` endpoint, in the same file, to examine the mode values.

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


$apiInstance = new OpenAPI\Client\Api\VariablesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$file_key = 'file_key_example'; // string | File to get variables from. This can be a file key or branch key. Use `GET /v1/files/:key` with the `branch_data` query param to get the branch key.

try {
    $result = $apiInstance->getLocalVariables($file_key);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling VariablesApi->getLocalVariables: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **file_key** | **string**| File to get variables from. This can be a file key or branch key. Use &#x60;GET /v1/files/:key&#x60; with the &#x60;branch_data&#x60; query param to get the branch key. | |

### Return type

[**\OpenAPI\Client\Model\InlineObject26**](../Model/InlineObject26.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getPublishedVariables()`

```php
getPublishedVariables($file_key): \OpenAPI\Client\Model\InlineObject27
```

Get published variables

**This API is available to full members of Enterprise orgs.**  The `GET /v1/files/:file_key/variables/published` endpoint returns the variables that are published from the given file.  The response for this endpoint contains some key differences compared to the `GET /v1/files/:file_key/variables/local` endpoint:  - Each variable and variable collection contains a `subscribed_id`. - Modes are omitted for published variable collections  Published variables have two ids: an id that is assigned in the file where it is created (`id`), and an id that is used by subscribing files (`subscribed_id`). The `id` and `key` are stable over the lifetime of the variable. The `subscribed_id` changes every time the variable is modified and published. The same is true for variable collections.  The `updatedAt` fields are ISO 8601 timestamps that indicate the last time that a change to a variable was published. For variable collections, this timestamp will change any time a variable in the collection is changed.

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


$apiInstance = new OpenAPI\Client\Api\VariablesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$file_key = 'file_key_example'; // string | File to get variables from. This must be a main file key, not a branch key, as it is not possible to publish from branches.

try {
    $result = $apiInstance->getPublishedVariables($file_key);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling VariablesApi->getPublishedVariables: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **file_key** | **string**| File to get variables from. This must be a main file key, not a branch key, as it is not possible to publish from branches. | |

### Return type

[**\OpenAPI\Client\Model\InlineObject27**](../Model/InlineObject27.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `postVariables()`

```php
postVariables($file_key, $post_variables_request): \OpenAPI\Client\Model\InlineObject28
```

Create/modify/delete variables

**This API is available to full members of Enterprise orgs with Editor seats.**  The `POST /v1/files/:file_key/variables` endpoint lets you bulk create, update, and delete variables and variable collections.  The request body supports the following 4 top-level arrays. Changes from these arrays will be applied in the below order, and within each array, by array order.  - **variableCollections**: For creating, updating, and deleting variable collections - **variableModes**: For creating, updating, and deleting modes within variable collections   - Each collection can have a maximum of 40 modes   - Mode names cannot be longer than 40 characters - **variables**: For creating, updating, and deleting variables   - Each collection can have a maximum of 5000 variables   - Variable names must be unique within a collection and cannot contain certain special characters such as `.{}` - **variableModeValues**: For setting a variable value under a specific mode.   - When setting aliases, a variable cannot be aliased to itself or form an alias cycle  Temporary ids can be used to reference an object later in the same POST request body. They can be used at create time in the `id` property of variable collections, modes, variables, and in the `initialModeId` property of variable collections. They are scoped to a single request body, and must be unique within the body. The mapping of temporary ids to real ids is returned in the response.  This endpoint has the following key behaviors:  - The request body must be 4MB or less. - Must include an `action` property for collections, modes, and variables to tell the API whether to create, update, or delete the object. - When creating a collection, mode, or variable, you can include a temporary `id` that can be referenced in dependent objects in the same request. For example, you can create a new collection with the id `\"my_new_collection\"`. You can then set `variableCollectionId` to `\"my_new_collection\"` in new modes or variables. Temporary ids must be unique in the request body. - New collections always come with one mode. You can reference this mode by setting `initialModeId` to a temporary id in the request body. This is useful if you want to set values for variables in the mode in the `variableModeValues` array.   - The `tempIdToRealId` array returns a mapping of the temporary ids in the request, to the real ids of the newly created objects. - When adding new modes or variables, default variable values will be applied, consistent with what happens in the UI. - Everything to be created, updated, and deleted in the request body is treated as one atomic operation. If there is any validation failure, you will get a 400 status code response, and no changes will be persisted. - You will not be able to update remote variables or variable collections. You can only update variables in the file where they were originally created.

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


$apiInstance = new OpenAPI\Client\Api\VariablesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$file_key = 'file_key_example'; // string | File to modify variables in. This can be a file key or branch key. Use `GET /v1/files/:key` with the `branch_data` query param to get the branch key.
$post_variables_request = new \OpenAPI\Client\Model\PostVariablesRequest(); // \OpenAPI\Client\Model\PostVariablesRequest

try {
    $result = $apiInstance->postVariables($file_key, $post_variables_request);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling VariablesApi->postVariables: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **file_key** | **string**| File to modify variables in. This can be a file key or branch key. Use &#x60;GET /v1/files/:key&#x60; with the &#x60;branch_data&#x60; query param to get the branch key. | |
| **post_variables_request** | [**\OpenAPI\Client\Model\PostVariablesRequest**](../Model/PostVariablesRequest.md)|  | |

### Return type

[**\OpenAPI\Client\Model\InlineObject28**](../Model/InlineObject28.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
