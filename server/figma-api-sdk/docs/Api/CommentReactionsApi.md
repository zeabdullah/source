# OpenAPI\Client\CommentReactionsApi

All URIs are relative to https://api.figma.com, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**deleteCommentReaction()**](CommentReactionsApi.md#deleteCommentReaction) | **DELETE** /v1/files/{file_key}/comments/{comment_id}/reactions | Delete a reaction |
| [**getCommentReactions()**](CommentReactionsApi.md#getCommentReactions) | **GET** /v1/files/{file_key}/comments/{comment_id}/reactions | Get reactions for a comment |
| [**postCommentReaction()**](CommentReactionsApi.md#postCommentReaction) | **POST** /v1/files/{file_key}/comments/{comment_id}/reactions | Add a reaction to a comment |


## `deleteCommentReaction()`

```php
deleteCommentReaction($file_key, $comment_id, $emoji): \OpenAPI\Client\Model\InlineObject9
```

Delete a reaction

Deletes a specific comment reaction. Only the person who made the reaction is allowed to delete it.

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


$apiInstance = new OpenAPI\Client\Api\CommentReactionsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$file_key = 'file_key_example'; // string | File to delete comment reaction from. This can be a file key or branch key. Use `GET /v1/files/:key` with the `branch_data` query param to get the branch key.
$comment_id = 'comment_id_example'; // string | ID of comment to delete reaction from.
$emoji = 'emoji_example'; // string

try {
    $result = $apiInstance->deleteCommentReaction($file_key, $comment_id, $emoji);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CommentReactionsApi->deleteCommentReaction: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **file_key** | **string**| File to delete comment reaction from. This can be a file key or branch key. Use &#x60;GET /v1/files/:key&#x60; with the &#x60;branch_data&#x60; query param to get the branch key. | |
| **comment_id** | **string**| ID of comment to delete reaction from. | |
| **emoji** | **string**|  | |

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

## `getCommentReactions()`

```php
getCommentReactions($file_key, $comment_id, $cursor): \OpenAPI\Client\Model\InlineObject10
```

Get reactions for a comment

Gets a paginated list of reactions left on the comment.

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


$apiInstance = new OpenAPI\Client\Api\CommentReactionsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$file_key = 'file_key_example'; // string | File to get comment containing reactions from. This can be a file key or branch key. Use `GET /v1/files/:key` with the `branch_data` query param to get the branch key.
$comment_id = 'comment_id_example'; // string | ID of comment to get reactions from.
$cursor = 'cursor_example'; // string | Cursor for pagination, retrieved from the response of the previous call.

try {
    $result = $apiInstance->getCommentReactions($file_key, $comment_id, $cursor);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CommentReactionsApi->getCommentReactions: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **file_key** | **string**| File to get comment containing reactions from. This can be a file key or branch key. Use &#x60;GET /v1/files/:key&#x60; with the &#x60;branch_data&#x60; query param to get the branch key. | |
| **comment_id** | **string**| ID of comment to get reactions from. | |
| **cursor** | **string**| Cursor for pagination, retrieved from the response of the previous call. | [optional] |

### Return type

[**\OpenAPI\Client\Model\InlineObject10**](../Model/InlineObject10.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `postCommentReaction()`

```php
postCommentReaction($file_key, $comment_id, $post_comment_reaction_request): \OpenAPI\Client\Model\InlineObject9
```

Add a reaction to a comment

Posts a new comment reaction on a file comment.

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


$apiInstance = new OpenAPI\Client\Api\CommentReactionsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$file_key = 'file_key_example'; // string | File to post comment reactions to. This can be a file key or branch key. Use `GET /v1/files/:key` with the `branch_data` query param to get the branch key.
$comment_id = 'comment_id_example'; // string | ID of comment to react to.
$post_comment_reaction_request = new \OpenAPI\Client\Model\PostCommentReactionRequest(); // \OpenAPI\Client\Model\PostCommentReactionRequest | Reaction to post.

try {
    $result = $apiInstance->postCommentReaction($file_key, $comment_id, $post_comment_reaction_request);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CommentReactionsApi->postCommentReaction: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **file_key** | **string**| File to post comment reactions to. This can be a file key or branch key. Use &#x60;GET /v1/files/:key&#x60; with the &#x60;branch_data&#x60; query param to get the branch key. | |
| **comment_id** | **string**| ID of comment to react to. | |
| **post_comment_reaction_request** | [**\OpenAPI\Client\Model\PostCommentReactionRequest**](../Model/PostCommentReactionRequest.md)| Reaction to post. | |

### Return type

[**\OpenAPI\Client\Model\InlineObject9**](../Model/InlineObject9.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
