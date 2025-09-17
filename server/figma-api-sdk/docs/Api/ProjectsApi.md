# OpenAPI\Client\ProjectsApi

All URIs are relative to https://api.figma.com, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getProjectFiles()**](ProjectsApi.md#getProjectFiles) | **GET** /v1/projects/{project_id}/files | Get files in a project |
| [**getTeamProjects()**](ProjectsApi.md#getTeamProjects) | **GET** /v1/teams/{team_id}/projects | Get projects in a team |


## `getProjectFiles()`

```php
getProjectFiles($project_id, $branch_data): \OpenAPI\Client\Model\InlineObject6
```

Get files in a project

Get a list of all the Files within the specified project.

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


$apiInstance = new OpenAPI\Client\Api\ProjectsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$project_id = 'project_id_example'; // string | ID of the project to list files from
$branch_data = false; // bool | Returns branch metadata in the response for each main file with a branch inside the project.

try {
    $result = $apiInstance->getProjectFiles($project_id, $branch_data);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ProjectsApi->getProjectFiles: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **project_id** | **string**| ID of the project to list files from | |
| **branch_data** | **bool**| Returns branch metadata in the response for each main file with a branch inside the project. | [optional] [default to false] |

### Return type

[**\OpenAPI\Client\Model\InlineObject6**](../Model/InlineObject6.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getTeamProjects()`

```php
getTeamProjects($team_id): \OpenAPI\Client\Model\InlineObject5
```

Get projects in a team

You can use this endpoint to get a list of all the Projects within the specified team. This will only return projects visible to the authenticated user or owner of the developer token. Note: it is not currently possible to programmatically obtain the team id of a user just from a token. To obtain a team id, navigate to a team page of a team you are a part of. The team id will be present in the URL after the word team and before your team name.

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


$apiInstance = new OpenAPI\Client\Api\ProjectsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$team_id = 'team_id_example'; // string | ID of the team to list projects from

try {
    $result = $apiInstance->getTeamProjects($team_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ProjectsApi->getTeamProjects: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **team_id** | **string**| ID of the team to list projects from | |

### Return type

[**\OpenAPI\Client\Model\InlineObject5**](../Model/InlineObject5.md)

### Authorization

[OAuth2](../../README.md#OAuth2), [PersonalAccessToken](../../README.md#PersonalAccessToken)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
