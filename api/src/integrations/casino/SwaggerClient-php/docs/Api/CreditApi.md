# Swagger\Client\CreditApi

All URIs are relative to *http://test-virtual.golden-race.net:8081/api/external/v0.1*

Method | HTTP request | Description
------------- | ------------- | -------------
[**creditAdd**](CreditApi.md#creditAdd) | **POST** /credit/add | 
[**creditClear**](CreditApi.md#creditClear) | **POST** /credit/clear | 
[**creditHistory**](CreditApi.md#creditHistory) | **GET** /credit/history | 
[**creditRemove**](CreditApi.md#creditRemove) | **POST** /credit/remove | 
[**creditSet**](CreditApi.md#creditSet) | **POST** /credit/set | 
[**creditTransfer**](CreditApi.md#creditTransfer) | **POST** /credit/transfer | 


# **creditAdd**
> \Swagger\Client\Model\InlineResponse200 creditAdd($entity_id, $currency_code, $ext_transaction_id, $amount, $transaction_desc, $transaction_data)



Increments credit as a transaction on currrent unit.

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

$api_instance = new Swagger\Client\Api\CreditApi();
$entity_id = 56; // int | The id of the entity.
$currency_code = "currency_code_example"; // string | The currency code to be selected between - EUR  - GBP  - USD  - AUD  - BRL  - NZD  - CAD  - CHF  - CNY  - DKK  - HKD  - INR  - JPY  - KRW  - LKR  - MXN  - MYR  - NOK  - SEK  - SGD  - THB  - TWD  - VEF  - ZAR  - BGN  - CZK  - EEK  - HUF  - LTL  - LVL  - PLN  - RON  - SKK  - ISK  - HRK  - RUB  - TRY  - PHP  - COP  - ARS  - RWF  - BIF  - CRC  - KES  - PEN  - DOP  - BYR  - UAH  - NAD  - GEL  - PRB  - MDL  - KZT  - MUR  - KGS  - IEP  - MKD  - RSD  - AZN  - MGA  - BAM  - TJS  - ALL  - SRD  - NIO  - GHS  - XAF  - GMD  - IQD  - IRR  - NGN  - AMD  - HTG  - GTQ  - ZMW  - GOLD
$ext_transaction_id = "ext_transaction_id_example"; // string | External transaction Id
$amount = 1.2; // double | Positive amount to decrease current credit
$transaction_desc = "transaction_desc_example"; // string | User readable description of current transaction.
$transaction_data = "transaction_data_example"; // string | JSON encoded object with custom data to be associated with transaction

try {
    $result = $api_instance->creditAdd($entity_id, $currency_code, $ext_transaction_id, $amount, $transaction_desc, $transaction_data);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CreditApi->creditAdd: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **entity_id** | **int**| The id of the entity. |
 **currency_code** | **string**| The currency code to be selected between - EUR  - GBP  - USD  - AUD  - BRL  - NZD  - CAD  - CHF  - CNY  - DKK  - HKD  - INR  - JPY  - KRW  - LKR  - MXN  - MYR  - NOK  - SEK  - SGD  - THB  - TWD  - VEF  - ZAR  - BGN  - CZK  - EEK  - HUF  - LTL  - LVL  - PLN  - RON  - SKK  - ISK  - HRK  - RUB  - TRY  - PHP  - COP  - ARS  - RWF  - BIF  - CRC  - KES  - PEN  - DOP  - BYR  - UAH  - NAD  - GEL  - PRB  - MDL  - KZT  - MUR  - KGS  - IEP  - MKD  - RSD  - AZN  - MGA  - BAM  - TJS  - ALL  - SRD  - NIO  - GHS  - XAF  - GMD  - IQD  - IRR  - NGN  - AMD  - HTG  - GTQ  - ZMW  - GOLD |
 **ext_transaction_id** | **string**| External transaction Id |
 **amount** | **double**| Positive amount to decrease current credit | [optional]
 **transaction_desc** | **string**| User readable description of current transaction. | [optional]
 **transaction_data** | **string**| JSON encoded object with custom data to be associated with transaction | [optional]

### Return type

[**\Swagger\Client\Model\InlineResponse200**](../Model/InlineResponse200.md)

### Authorization

[apiHash](../../README.md#apiHash), [apiId](../../README.md#apiId)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **creditClear**
> \Swagger\Client\Model\InlineResponse200 creditClear($entity_id, $currency_code, $ext_transaction_id, $transaction_desc, $transaction_data)



Clears all the credit as a transaction on currrent unit.

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

$api_instance = new Swagger\Client\Api\CreditApi();
$entity_id = 56; // int | The id of the entity.
$currency_code = "currency_code_example"; // string | The currency code to be selected between - EUR  - GBP  - USD  - AUD  - BRL  - NZD  - CAD  - CHF  - CNY  - DKK  - HKD  - INR  - JPY  - KRW  - LKR  - MXN  - MYR  - NOK  - SEK  - SGD  - THB  - TWD  - VEF  - ZAR  - BGN  - CZK  - EEK  - HUF  - LTL  - LVL  - PLN  - RON  - SKK  - ISK  - HRK  - RUB  - TRY  - PHP  - COP  - ARS  - RWF  - BIF  - CRC  - KES  - PEN  - DOP  - BYR  - UAH  - NAD  - GEL  - PRB  - MDL  - KZT  - MUR  - KGS  - IEP  - MKD  - RSD  - AZN  - MGA  - BAM  - TJS  - ALL  - SRD  - NIO  - GHS  - XAF  - GMD  - IQD  - IRR  - NGN  - AMD  - HTG  - GTQ  - ZMW  - GOLD
$ext_transaction_id = "ext_transaction_id_example"; // string | External transaction Id
$transaction_desc = "transaction_desc_example"; // string | User readable description of current transaction.
$transaction_data = "transaction_data_example"; // string | JSON encoded object with custom data to be associated with transaction

try {
    $result = $api_instance->creditClear($entity_id, $currency_code, $ext_transaction_id, $transaction_desc, $transaction_data);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CreditApi->creditClear: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **entity_id** | **int**| The id of the entity. |
 **currency_code** | **string**| The currency code to be selected between - EUR  - GBP  - USD  - AUD  - BRL  - NZD  - CAD  - CHF  - CNY  - DKK  - HKD  - INR  - JPY  - KRW  - LKR  - MXN  - MYR  - NOK  - SEK  - SGD  - THB  - TWD  - VEF  - ZAR  - BGN  - CZK  - EEK  - HUF  - LTL  - LVL  - PLN  - RON  - SKK  - ISK  - HRK  - RUB  - TRY  - PHP  - COP  - ARS  - RWF  - BIF  - CRC  - KES  - PEN  - DOP  - BYR  - UAH  - NAD  - GEL  - PRB  - MDL  - KZT  - MUR  - KGS  - IEP  - MKD  - RSD  - AZN  - MGA  - BAM  - TJS  - ALL  - SRD  - NIO  - GHS  - XAF  - GMD  - IQD  - IRR  - NGN  - AMD  - HTG  - GTQ  - ZMW  - GOLD |
 **ext_transaction_id** | **string**| External transaction Id |
 **transaction_desc** | **string**| User readable description of current transaction. | [optional]
 **transaction_data** | **string**| JSON encoded object with custom data to be associated with transaction | [optional]

### Return type

[**\Swagger\Client\Model\InlineResponse200**](../Model/InlineResponse200.md)

### Authorization

[apiHash](../../README.md#apiHash), [apiId](../../README.md#apiId)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **creditHistory**
> \Swagger\Client\Model\InlineResponse200[] creditHistory($entity_id, $start_time, $n, $first, $order_by, $end_time)



Produces a history of credit transactions

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

$api_instance = new Swagger\Client\Api\CreditApi();
$entity_id = 56; // int | The id of the entity.
$start_time = new \DateTime(); // \DateTime | Interval start time from which the search will be performed.
$n = 0; // int | Number of elements to return from query.  If 0, get all elements.
$first = 0; // int | First element of query to be returned, for paging purposes.  If 0, start from first element.
$order_by = "ASC"; // string | Define order ASC or DESC
$end_time = new \DateTime(); // \DateTime | Interval end time to which the search will be performed. Use n or endTime

try {
    $result = $api_instance->creditHistory($entity_id, $start_time, $n, $first, $order_by, $end_time);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CreditApi->creditHistory: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **entity_id** | **int**| The id of the entity. |
 **start_time** | **\DateTime**| Interval start time from which the search will be performed. |
 **n** | **int**| Number of elements to return from query.  If 0, get all elements. | [default to 0]
 **first** | **int**| First element of query to be returned, for paging purposes.  If 0, start from first element. | [default to 0]
 **order_by** | **string**| Define order ASC or DESC | [default to ASC]
 **end_time** | **\DateTime**| Interval end time to which the search will be performed. Use n or endTime | [optional]

### Return type

[**\Swagger\Client\Model\InlineResponse200[]**](../Model/InlineResponse200.md)

### Authorization

[apiHash](../../README.md#apiHash), [apiId](../../README.md#apiId)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **creditRemove**
> \Swagger\Client\Model\InlineResponse200 creditRemove($entity_id, $currency_code, $ext_transaction_id, $amount, $transaction_desc, $transaction_data)



Decrements credit as a transaction on currrent unit.

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

$api_instance = new Swagger\Client\Api\CreditApi();
$entity_id = 56; // int | The id of the entity.
$currency_code = "currency_code_example"; // string | The currency code to be selected between - EUR  - GBP  - USD  - AUD  - BRL  - NZD  - CAD  - CHF  - CNY  - DKK  - HKD  - INR  - JPY  - KRW  - LKR  - MXN  - MYR  - NOK  - SEK  - SGD  - THB  - TWD  - VEF  - ZAR  - BGN  - CZK  - EEK  - HUF  - LTL  - LVL  - PLN  - RON  - SKK  - ISK  - HRK  - RUB  - TRY  - PHP  - COP  - ARS  - RWF  - BIF  - CRC  - KES  - PEN  - DOP  - BYR  - UAH  - NAD  - GEL  - PRB  - MDL  - KZT  - MUR  - KGS  - IEP  - MKD  - RSD  - AZN  - MGA  - BAM  - TJS  - ALL  - SRD  - NIO  - GHS  - XAF  - GMD  - IQD  - IRR  - NGN  - AMD  - HTG  - GTQ  - ZMW  - GOLD
$ext_transaction_id = "ext_transaction_id_example"; // string | External transaction Id
$amount = 1.2; // double | Positive amount to decrease current credit
$transaction_desc = "transaction_desc_example"; // string | User readable description of current transaction.
$transaction_data = "transaction_data_example"; // string | JSON encoded object with custom data to be associated with transaction

try {
    $result = $api_instance->creditRemove($entity_id, $currency_code, $ext_transaction_id, $amount, $transaction_desc, $transaction_data);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CreditApi->creditRemove: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **entity_id** | **int**| The id of the entity. |
 **currency_code** | **string**| The currency code to be selected between - EUR  - GBP  - USD  - AUD  - BRL  - NZD  - CAD  - CHF  - CNY  - DKK  - HKD  - INR  - JPY  - KRW  - LKR  - MXN  - MYR  - NOK  - SEK  - SGD  - THB  - TWD  - VEF  - ZAR  - BGN  - CZK  - EEK  - HUF  - LTL  - LVL  - PLN  - RON  - SKK  - ISK  - HRK  - RUB  - TRY  - PHP  - COP  - ARS  - RWF  - BIF  - CRC  - KES  - PEN  - DOP  - BYR  - UAH  - NAD  - GEL  - PRB  - MDL  - KZT  - MUR  - KGS  - IEP  - MKD  - RSD  - AZN  - MGA  - BAM  - TJS  - ALL  - SRD  - NIO  - GHS  - XAF  - GMD  - IQD  - IRR  - NGN  - AMD  - HTG  - GTQ  - ZMW  - GOLD |
 **ext_transaction_id** | **string**| External transaction Id |
 **amount** | **double**| Positive amount to decrease current credit | [optional]
 **transaction_desc** | **string**| User readable description of current transaction. | [optional]
 **transaction_data** | **string**| JSON encoded object with custom data to be associated with transaction | [optional]

### Return type

[**\Swagger\Client\Model\InlineResponse200**](../Model/InlineResponse200.md)

### Authorization

[apiHash](../../README.md#apiHash), [apiId](../../README.md#apiId)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **creditSet**
> \Swagger\Client\Model\InlineResponse200 creditSet($entity_id, $currency_code, $ext_transaction_id, $amount, $transaction_desc, $transaction_data)



To set an amount as the new credit value; this will override any limit or check.

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

$api_instance = new Swagger\Client\Api\CreditApi();
$entity_id = 56; // int | The id of the entity.
$currency_code = "currency_code_example"; // string | The currency code to be selected between - EUR  - GBP  - USD  - AUD  - BRL  - NZD  - CAD  - CHF  - CNY  - DKK  - HKD  - INR  - JPY  - KRW  - LKR  - MXN  - MYR  - NOK  - SEK  - SGD  - THB  - TWD  - VEF  - ZAR  - BGN  - CZK  - EEK  - HUF  - LTL  - LVL  - PLN  - RON  - SKK  - ISK  - HRK  - RUB  - TRY  - PHP  - COP  - ARS  - RWF  - BIF  - CRC  - KES  - PEN  - DOP  - BYR  - UAH  - NAD  - GEL  - PRB  - MDL  - KZT  - MUR  - KGS  - IEP  - MKD  - RSD  - AZN  - MGA  - BAM  - TJS  - ALL  - SRD  - NIO  - GHS  - XAF  - GMD  - IQD  - IRR  - NGN  - AMD  - HTG  - GTQ  - ZMW  - GOLD
$ext_transaction_id = "ext_transaction_id_example"; // string | External transaction Id
$amount = 1.2; // double | Positive amount to decrease current credit
$transaction_desc = "transaction_desc_example"; // string | User readable description of current transaction.
$transaction_data = "transaction_data_example"; // string | JSON encoded object with custom data to be associated with transaction

try {
    $result = $api_instance->creditSet($entity_id, $currency_code, $ext_transaction_id, $amount, $transaction_desc, $transaction_data);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CreditApi->creditSet: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **entity_id** | **int**| The id of the entity. |
 **currency_code** | **string**| The currency code to be selected between - EUR  - GBP  - USD  - AUD  - BRL  - NZD  - CAD  - CHF  - CNY  - DKK  - HKD  - INR  - JPY  - KRW  - LKR  - MXN  - MYR  - NOK  - SEK  - SGD  - THB  - TWD  - VEF  - ZAR  - BGN  - CZK  - EEK  - HUF  - LTL  - LVL  - PLN  - RON  - SKK  - ISK  - HRK  - RUB  - TRY  - PHP  - COP  - ARS  - RWF  - BIF  - CRC  - KES  - PEN  - DOP  - BYR  - UAH  - NAD  - GEL  - PRB  - MDL  - KZT  - MUR  - KGS  - IEP  - MKD  - RSD  - AZN  - MGA  - BAM  - TJS  - ALL  - SRD  - NIO  - GHS  - XAF  - GMD  - IQD  - IRR  - NGN  - AMD  - HTG  - GTQ  - ZMW  - GOLD |
 **ext_transaction_id** | **string**| External transaction Id |
 **amount** | **double**| Positive amount to decrease current credit | [optional]
 **transaction_desc** | **string**| User readable description of current transaction. | [optional]
 **transaction_data** | **string**| JSON encoded object with custom data to be associated with transaction | [optional]

### Return type

[**\Swagger\Client\Model\InlineResponse200**](../Model/InlineResponse200.md)

### Authorization

[apiHash](../../README.md#apiHash), [apiId](../../README.md#apiId)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **creditTransfer**
> \Swagger\Client\Model\InlineResponse200[] creditTransfer($entity_id_from, $entity_id_to, $currency_code, $ext_transaction_id, $amount, $transaction_desc, $transaction_data)



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

$api_instance = new Swagger\Client\Api\CreditApi();
$entity_id_from = 56; // int | Entity that it doing the transfer from
$entity_id_to = 56; // int | Entity that it doing the transfer to
$currency_code = "currency_code_example"; // string | The currency code to be selected between - EUR  - GBP  - USD  - AUD  - BRL  - NZD  - CAD  - CHF  - CNY  - DKK  - HKD  - INR  - JPY  - KRW  - LKR  - MXN  - MYR  - NOK  - SEK  - SGD  - THB  - TWD  - VEF  - ZAR  - BGN  - CZK  - EEK  - HUF  - LTL  - LVL  - PLN  - RON  - SKK  - ISK  - HRK  - RUB  - TRY  - PHP  - COP  - ARS  - RWF  - BIF  - CRC  - KES  - PEN  - DOP  - BYR  - UAH  - NAD  - GEL  - PRB  - MDL  - KZT  - MUR  - KGS  - IEP  - MKD  - RSD  - AZN  - MGA  - BAM  - TJS  - ALL  - SRD  - NIO  - GHS  - XAF  - GMD  - IQD  - IRR  - NGN  - AMD  - HTG  - GTQ  - ZMW  - GOLD
$ext_transaction_id = "ext_transaction_id_example"; // string | External transaction Id
$amount = 1.2; // double | Positive amount to decrease current credit
$transaction_desc = "transaction_desc_example"; // string | User readable description of current transaction.
$transaction_data = "transaction_data_example"; // string | JSON encoded object with custom data to be associated with transaction

try {
    $result = $api_instance->creditTransfer($entity_id_from, $entity_id_to, $currency_code, $ext_transaction_id, $amount, $transaction_desc, $transaction_data);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CreditApi->creditTransfer: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **entity_id_from** | **int**| Entity that it doing the transfer from |
 **entity_id_to** | **int**| Entity that it doing the transfer to |
 **currency_code** | **string**| The currency code to be selected between - EUR  - GBP  - USD  - AUD  - BRL  - NZD  - CAD  - CHF  - CNY  - DKK  - HKD  - INR  - JPY  - KRW  - LKR  - MXN  - MYR  - NOK  - SEK  - SGD  - THB  - TWD  - VEF  - ZAR  - BGN  - CZK  - EEK  - HUF  - LTL  - LVL  - PLN  - RON  - SKK  - ISK  - HRK  - RUB  - TRY  - PHP  - COP  - ARS  - RWF  - BIF  - CRC  - KES  - PEN  - DOP  - BYR  - UAH  - NAD  - GEL  - PRB  - MDL  - KZT  - MUR  - KGS  - IEP  - MKD  - RSD  - AZN  - MGA  - BAM  - TJS  - ALL  - SRD  - NIO  - GHS  - XAF  - GMD  - IQD  - IRR  - NGN  - AMD  - HTG  - GTQ  - ZMW  - GOLD |
 **ext_transaction_id** | **string**| External transaction Id |
 **amount** | **double**| Positive amount to decrease current credit | [optional]
 **transaction_desc** | **string**| User readable description of current transaction. | [optional]
 **transaction_data** | **string**| JSON encoded object with custom data to be associated with transaction | [optional]

### Return type

[**\Swagger\Client\Model\InlineResponse200[]**](../Model/InlineResponse200.md)

### Authorization

[apiHash](../../README.md#apiHash), [apiId](../../README.md#apiId)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

