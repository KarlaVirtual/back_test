# Swagger\Client\TicketApi

All URIs are relative to *http://test-virtual.golden-race.net:8081/api/external/v0.1*

Method | HTTP request | Description
------------- | ------------- | -------------
[**ticketCancel**](TicketApi.md#ticketCancel) | **GET** /ticket/cancel | 
[**ticketFind**](TicketApi.md#ticketFind) | **GET** /ticket/find | 
[**ticketFindById**](TicketApi.md#ticketFindById) | **GET** /ticket/findById | 
[**ticketPayout**](TicketApi.md#ticketPayout) | **GET** /ticket/payout | 


# **ticketCancel**
> \Swagger\Client\Model\ServerTicket[] ticketCancel($ticket_ids)



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

$api_instance = new Swagger\Client\Api\TicketApi();
$ticket_ids = array(new int[]()); // int[] | Array ids ticket

try {
    $result = $api_instance->ticketCancel($ticket_ids);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TicketApi->ticketCancel: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **ticket_ids** | **int[]**| Array ids ticket |

### Return type

[**\Swagger\Client\Model\ServerTicket[]**](../Model/ServerTicket.md)

### Authorization

[apiHash](../../README.md#apiHash), [apiId](../../README.md#apiId)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **ticketFind**
> \Swagger\Client\Model\ServerTicket[] ticketFind($entity_id, $start_time, $first, $n, $order_by, $ext_ticket_id, $end_time, $status)



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

$api_instance = new Swagger\Client\Api\TicketApi();
$entity_id = 56; // int | The id of the entity.
$start_time = new \DateTime(); // \DateTime | Interval start time from which the search will be performed.
$first = 0; // int | First element of query to be returned, for paging purposes.  If 0, start from first element.
$n = 0; // int | Number of elements to return from query.  If 0, get all elements.
$order_by = "ASC"; // string | Define order ASC or DESC
$ext_ticket_id = "ext_ticket_id_example"; // string | External Ticket id (your own id) to find
$end_time = new \DateTime(); // \DateTime | Interval end time to which the search will be performed. Use n or endTime
$status = array("status_example"); // string[] | 

try {
    $result = $api_instance->ticketFind($entity_id, $start_time, $first, $n, $order_by, $ext_ticket_id, $end_time, $status);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TicketApi->ticketFind: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **entity_id** | **int**| The id of the entity. |
 **start_time** | **\DateTime**| Interval start time from which the search will be performed. |
 **first** | **int**| First element of query to be returned, for paging purposes.  If 0, start from first element. | [default to 0]
 **n** | **int**| Number of elements to return from query.  If 0, get all elements. | [default to 0]
 **order_by** | **string**| Define order ASC or DESC | [default to ASC]
 **ext_ticket_id** | **string**| External Ticket id (your own id) to find | [optional]
 **end_time** | **\DateTime**| Interval end time to which the search will be performed. Use n or endTime | [optional]
 **status** | [**string[]**](../Model/string.md)|  | [optional]

### Return type

[**\Swagger\Client\Model\ServerTicket[]**](../Model/ServerTicket.md)

### Authorization

[apiHash](../../README.md#apiHash), [apiId](../../README.md#apiId)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **ticketFindById**
> \Swagger\Client\Model\ServerTicket ticketFindById($entity_id, $ticket_id)



Find a ticket by unique ticket Id, under a root entity.

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

$api_instance = new Swagger\Client\Api\TicketApi();
$entity_id = 56; // int | The id of the entity.
$ticket_id = 789; // int | Ticket id to find

try {
    $result = $api_instance->ticketFindById($entity_id, $ticket_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TicketApi->ticketFindById: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **entity_id** | **int**| The id of the entity. |
 **ticket_id** | **int**| Ticket id to find |

### Return type

[**\Swagger\Client\Model\ServerTicket**](../Model/ServerTicket.md)

### Authorization

[apiHash](../../README.md#apiHash), [apiId](../../README.md#apiId)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **ticketPayout**
> \Swagger\Client\Model\ServerTicket[] ticketPayout($ticket_ids)



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

$api_instance = new Swagger\Client\Api\TicketApi();
$ticket_ids = array(new int[]()); // int[] | Array ids ticket

try {
    $result = $api_instance->ticketPayout($ticket_ids);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TicketApi->ticketPayout: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **ticket_ids** | **int[]**| Array ids ticket |

### Return type

[**\Swagger\Client\Model\ServerTicket[]**](../Model/ServerTicket.md)

### Authorization

[apiHash](../../README.md#apiHash), [apiId](../../README.md#apiId)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

