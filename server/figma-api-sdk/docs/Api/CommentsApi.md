# OpenAPI\Client\CommentsApi

All URIs are relative to https://api.figma.com, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**deleteComment()**](CommentsApi.md#deleteComment) | **DELETE** /v1/files/{file_key}/comments/{comment_id} | Delete a comment |
| [**getComments()**](CommentsApi.md#getComments) | **GET** /v1/files/{file_key}/comments | Get comments in a file |
| [**postComment()**](CommentsApi.md#postComment) | **POST** /v1/files/{file_key}/comments | Add a comment to a file |


## `deleteComment()`

```php
deleteComment($file_key, $comment_id): \OpenAPI\Client\Model\InlineObject9
```

Delete a comment

Deletes a specific comment. Only the person who made the comment is allowed to delete it.

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


$apiInstance = new OpenAPI\Client\Api\CommentsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$file_key = 'file_key_example'; // string | File to delete comment from. This can be a file key or branch key. Use `GET /v1/files/:key` with the `branch_data` query param to get the branch key.
$comment_id = 'comment_id_example'; // string | Comment id of comment to delete

try {
    $result = $apiInstance->deleteComment($file_key, $comment_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CommentsApi->deleteComment: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **file_key** | **string**| File to delete comment from. This can be a file key or branch key. Use &#x60;GET /v1/files/:key&#x60; with the &#x60;branch_data&#x60; query param to get the branch key. | |
| **comment_id** | **string**| Comment id of comment to delete | |

### Return type

[**\OpenAPI\Client\Model\InlineObject9**](../Model/InlineObject9.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getComments()`

```php
getComments($file_key, $as_md): \OpenAPI\Client\Model\InlineObject8
```

Get comments in a file

Gets a list of comments left on the file.

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


$apiInstance = new OpenAPI\Client\Api\CommentsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$file_key = 'file_key_example'; // string | File to get comments from. This can be a file key or branch key. Use `GET /v1/files/:key` with the `branch_data` query param to get the branch key.
$as_md = True; // bool | If enabled, will return comments as their markdown equivalents when applicable.

try {
    $result = $apiInstance->getComments($file_key, $as_md);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CommentsApi->getComments: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **file_key** | **string**| File to get comments from. This can be a file key or branch key. Use &#x60;GET /v1/files/:key&#x60; with the &#x60;branch_data&#x60; query param to get the branch key. | |
| **as_md** | **bool**| If enabled, will return comments as their markdown equivalents when applicable. | [optional] |

### Return type

[**\OpenAPI\Client\Model\InlineObject8**](../Model/InlineObject8.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `postComment()`

```php
postComment($file_key, $post_comment_request): \OpenAPI\Client\Model\Comment
```

Add a comment to a file

Posts a new comment on the file.

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


$apiInstance = new OpenAPI\Client\Api\CommentsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$file_key = 'file_key_example'; // string | File to add comments in. This can be a file key or branch key. Use `GET /v1/files/:key` with the `branch_data` query param to get the branch key.
$post_comment_request = new \OpenAPI\Client\Model\PostCommentRequest(); // \OpenAPI\Client\Model\PostCommentRequest | Comment to post.

try {
    $result = $apiInstance->postComment($file_key, $post_comment_request);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CommentsApi->postComment: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **file_key** | **string**| File to add comments in. This can be a file key or branch key. Use &#x60;GET /v1/files/:key&#x60; with the &#x60;branch_data&#x60; query param to get the branch key. | |
| **post_comment_request** | [**\OpenAPI\Client\Model\PostCommentRequest**](../Model/PostCommentRequest.md)| Comment to post. | |

### Return type

[**\OpenAPI\Client\Model\Comment**](../Model/Comment.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
