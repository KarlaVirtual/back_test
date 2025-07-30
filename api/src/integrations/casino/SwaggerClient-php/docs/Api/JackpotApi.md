# Swagger\Client\JackpotApi

All URIs are relative to *http://test-virtual.golden-race.net:8081/api/external/v0.1*

Method | HTTP request | Description
------------- | ------------- | -------------
[**jackpotAddEntity**](JackpotApi.md#jackpotAddEntity) | **POST** /jackpot/addEntity | 
[**jackpotRemoveEntity**](JackpotApi.md#jackpotRemoveEntity) | **POST** /jackpot/removeEntity | 


# **jackpotAddEntity**
> jackpotAddEntity($jackpot_id, $entity_id, $currency_code)



Register entity on target jackpot group.  One entity can be part of any number of jackpot groups.   Any entity, can be a jackpot group, in case it has some other entities associated.  For every jackpot group, a jackpot amount, and jackpot status is updated per currency for every ticket that is solved on units that belongs to jackpot group.

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

$api_instance = new Swagger\Client\Api\JackpotApi();
$jackpot_id = 56; // int | Jackpot Id
$entity_id = 56; // int | The id of the entity.
$currency_code = "currency_code_example"; // string | The currency code to be selected between - EUR  - GBP  - USD  - AUD  - BRL  - NZD  - CAD  - CHF  - CNY  - DKK  - HKD  - INR  - JPY  - KRW  - LKR  - MXN  - MYR  - NOK  - SEK  - SGD  - THB  - TWD  - VEF  - ZAR  - BGN  - CZK  - EEK  - HUF  - LTL  - LVL  - PLN  - RON  - SKK  - ISK  - HRK  - RUB  - TRY  - PHP  - COP  - ARS  - RWF  - BIF  - CRC  - KES  - PEN  - DOP  - BYR  - UAH  - NAD  - GEL  - PRB  - MDL  - KZT  - MUR  - KGS  - IEP  - MKD  - RSD  - AZN  - MGA  - BAM  - TJS  - ALL  - SRD  - NIO  - GHS  - XAF  - GMD  - IQD  - IRR  - NGN  - AMD  - HTG  - GTQ  - ZMW  - GOLD

try {
    $api_instance->jackpotAddEntity($jackpot_id, $entity_id, $currency_code);
} catch (Exception $e) {
    echo 'Exception when calling JackpotApi->jackpotAddEntity: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **jackpot_id** | **int**| Jackpot Id |
 **entity_id** | **int**| The id of the entity. |
 **currency_code** | **string**| The currency code to be selected between - EUR  - GBP  - USD  - AUD  - BRL  - NZD  - CAD  - CHF  - CNY  - DKK  - HKD  - INR  - JPY  - KRW  - LKR  - MXN  - MYR  - NOK  - SEK  - SGD  - THB  - TWD  - VEF  - ZAR  - BGN  - CZK  - EEK  - HUF  - LTL  - LVL  - PLN  - RON  - SKK  - ISK  - HRK  - RUB  - TRY  - PHP  - COP  - ARS  - RWF  - BIF  - CRC  - KES  - PEN  - DOP  - BYR  - UAH  - NAD  - GEL  - PRB  - MDL  - KZT  - MUR  - KGS  - IEP  - MKD  - RSD  - AZN  - MGA  - BAM  - TJS  - ALL  - SRD  - NIO  - GHS  - XAF  - GMD  - IQD  - IRR  - NGN  - AMD  - HTG  - GTQ  - ZMW  - GOLD |

### Return type

void (empty response body)

### Authorization

[apiHash](../../README.md#apiHash), [apiId](../../README.md#apiId)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **jackpotRemoveEntity**
> jackpotRemoveEntity($jackpot_id, $entity_id, $currency_code)



Removes entity from a jackpot group.

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

$api_instance = new Swagger\Client\Api\JackpotApi();
$jackpot_id = 56; // int | Jackpot Id
$entity_id = 56; // int | The id of the entity.
$currency_code = "currency_code_example"; // string | The currency code to be selected between - EUR  - GBP  - USD  - AUD  - BRL  - NZD  - CAD  - CHF  - CNY  - DKK  - HKD  - INR  - JPY  - KRW  - LKR  - MXN  - MYR  - NOK  - SEK  - SGD  - THB  - TWD  - VEF  - ZAR  - BGN  - CZK  - EEK  - HUF  - LTL  - LVL  - PLN  - RON  - SKK  - ISK  - HRK  - RUB  - TRY  - PHP  - COP  - ARS  - RWF  - BIF  - CRC  - KES  - PEN  - DOP  - BYR  - UAH  - NAD  - GEL  - PRB  - MDL  - KZT  - MUR  - KGS  - IEP  - MKD  - RSD  - AZN  - MGA  - BAM  - TJS  - ALL  - SRD  - NIO  - GHS  - XAF  - GMD  - IQD  - IRR  - NGN  - AMD  - HTG  - GTQ  - ZMW  - GOLD

try {
    $api_instance->jackpotRemoveEntity($jackpot_id, $entity_id, $currency_code);
} catch (Exception $e) {
    echo 'Exception when calling JackpotApi->jackpotRemoveEntity: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **jackpot_id** | **int**| Jackpot Id |
 **entity_id** | **int**| The id of the entity. |
 **currency_code** | **string**| The currency code to be selected between - EUR  - GBP  - USD  - AUD  - BRL  - NZD  - CAD  - CHF  - CNY  - DKK  - HKD  - INR  - JPY  - KRW  - LKR  - MXN  - MYR  - NOK  - SEK  - SGD  - THB  - TWD  - VEF  - ZAR  - BGN  - CZK  - EEK  - HUF  - LTL  - LVL  - PLN  - RON  - SKK  - ISK  - HRK  - RUB  - TRY  - PHP  - COP  - ARS  - RWF  - BIF  - CRC  - KES  - PEN  - DOP  - BYR  - UAH  - NAD  - GEL  - PRB  - MDL  - KZT  - MUR  - KGS  - IEP  - MKD  - RSD  - AZN  - MGA  - BAM  - TJS  - ALL  - SRD  - NIO  - GHS  - XAF  - GMD  - IQD  - IRR  - NGN  - AMD  - HTG  - GTQ  - ZMW  - GOLD |

### Return type

void (empty response body)

### Authorization

[apiHash](../../README.md#apiHash), [apiId](../../README.md#apiId)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

