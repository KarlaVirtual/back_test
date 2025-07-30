# Swagger\Client\StatsApi

All URIs are relative to *http://test-virtual.golden-race.net:8081/api/external/v0.1*

Method | HTTP request | Description
------------- | ------------- | -------------
[**statsEarning**](StatsApi.md#statsEarning) | **GET** /stats/getEarning | 
[**statsEarningDetail**](StatsApi.md#statsEarningDetail) | **GET** /stats/getEarningDetail | 


# **statsEarning**
> \Swagger\Client\Model\Stat1[] statsEarning($entity_id, $start_time, $market_code, $end_time)



This method returns accumulated totals for earnings between two time points.

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

$api_instance = new Swagger\Client\Api\StatsApi();
$entity_id = 56; // int | The id of the entity.
$start_time = new \DateTime(); // \DateTime | Interval start time from which the search will be performed. Miliseconds.
$market_code = "market_code_example"; // string | Market code. Try it out to see enum values.
$end_time = new \DateTime(); // \DateTime | Interval end time to which the search will be performed. Use n or endTime. Miliseconds.

try {
    $result = $api_instance->statsEarning($entity_id, $start_time, $market_code, $end_time);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling StatsApi->statsEarning: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **entity_id** | **int**| The id of the entity. |
 **start_time** | **\DateTime**| Interval start time from which the search will be performed. Miliseconds. |
 **market_code** | **string**| Market code. Try it out to see enum values. |
 **end_time** | **\DateTime**| Interval end time to which the search will be performed. Use n or endTime. Miliseconds. | [optional]

### Return type

[**\Swagger\Client\Model\Stat1[]**](../Model/Stat1.md)

### Authorization

[apiHash](../../README.md#apiHash), [apiId](../../README.md#apiId)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **statsEarningDetail**
> \Swagger\Client\Model\StatDetail[] statsEarningDetail($entity_id, $start_time, $market_code, $end_time)



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

$api_instance = new Swagger\Client\Api\StatsApi();
$entity_id = 56; // int | The id of the entity.
$start_time = new \DateTime(); // \DateTime | Interval start time from which the search will be performed. Miliseconds.
$market_code = "market_code_example"; // string | Market code. Try it out to see enum values.
$end_time = new \DateTime(); // \DateTime | Interval end time to which the search will be performed. Use n or endTime. Miliseconds.

try {
    $result = $api_instance->statsEarningDetail($entity_id, $start_time, $market_code, $end_time);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling StatsApi->statsEarningDetail: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **entity_id** | **int**| The id of the entity. |
 **start_time** | **\DateTime**| Interval start time from which the search will be performed. Miliseconds. |
 **market_code** | **string**| Market code. Try it out to see enum values. |
 **end_time** | **\DateTime**| Interval end time to which the search will be performed. Use n or endTime. Miliseconds. | [optional]

### Return type

[**\Swagger\Client\Model\StatDetail[]**](../Model/StatDetail.md)

### Authorization

[apiHash](../../README.md#apiHash), [apiId](../../README.md#apiId)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

