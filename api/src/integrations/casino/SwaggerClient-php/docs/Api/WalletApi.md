# Swagger\Client\WalletApi

All URIs are relative to *http://test-virtual.golden-race.net:8081/api/external/v0.1*

Method | HTTP request | Description
------------- | ------------- | -------------
[**walletRetryConfirm**](WalletApi.md#walletRetryConfirm) | **POST** /wallet/retryConfirm | 
[**walletRetryPayout**](WalletApi.md#walletRetryPayout) | **POST** /wallet/retryPayout | 


# **walletRetryConfirm**
> \Swagger\Client\Model\WalletResponse[] walletRetryConfirm($ticket_ids)



### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure API key authorization: apiHash
Swagger\Client\Configuration::getDefaultConfiguration()->setApiKey('apiHash', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// Swagger\Client\Configuration::getDefaultConfiguration()->setApiKeyPrefix('apiHash', 'Bearer');
// Configure API key authorization: apiId
Swagger\Client\Configuration::getDefaultConfiguration()->setApiKey('apiId', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// Swagger\Client\Configuration::getDefaultConfiguration()->setApiKeyPrefix('apiId', 'Bearer');

$api_instance = new Swagger\Client\Api\WalletApi();
$ticket_ids = array(new int[]()); // int[] | Array ids ticket

try {
    $result = $api_instance->walletRetryConfirm($ticket_ids);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling WalletApi->walletRetryConfirm: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **ticket_ids** | **int[]**| Array ids ticket |

### Return type

[**\Swagger\Client\Model\WalletResponse[]**](../Model/WalletResponse.md)

### Authorization

[apiHash](../../README.md#apiHash), [apiId](../../README.md#apiId)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **walletRetryPayout**
> \Swagger\Client\Model\WalletResponse[] walletRetryPayout($ticket_ids)



### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure API key authorization: apiHash
Swagger\Client\Configuration::getDefaultConfiguration()->setApiKey('apiHash', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// Swagger\Client\Configuration::getDefaultConfiguration()->setApiKeyPrefix('apiHash', 'Bearer');
// Configure API key authorization: apiId
Swagger\Client\Configuration::getDefaultConfiguration()->setApiKey('apiId', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// Swagger\Client\Configuration::getDefaultConfiguration()->setApiKeyPrefix('apiId', 'Bearer');

$api_instance = new Swagger\Client\Api\WalletApi();
$ticket_ids = array(new int[]()); // int[] | Array ids ticket

try {
    $result = $api_instance->walletRetryPayout($ticket_ids);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling WalletApi->walletRetryPayout: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **ticket_ids** | **int[]**| Array ids ticket |

### Return type

[**\Swagger\Client\Model\WalletResponse[]**](../Model/WalletResponse.md)

### Authorization

[apiHash](../../README.md#apiHash), [apiId](../../README.md#apiId)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

