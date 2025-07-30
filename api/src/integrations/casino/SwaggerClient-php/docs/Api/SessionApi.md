# Swagger\Client\SessionApi

All URIs are relative to *http://test-virtual.golden-race.net:8081/api/external/v0.1*

Method | HTTP request | Description
------------- | ------------- | -------------
[**sessionExternalLogin**](SessionApi.md#sessionExternalLogin) | **POST** /session/login | 


# **sessionExternalLogin**
> \Swagger\Client\Model\AuthResult sessionExternalLogin($unit_id, $staff_id, $session_context)



Generates authentication by external API to unit/staff entity client to be able to operate with tickets. - **Unit entity**.  Account used to store products configuration, wallet operations and ticket ownership. - **Staff entity**. Identifies logged user that perform operations over credit and tickets. In the scenario of single user accounts, it is a common scenario to use a single entity as staffId ad unitId, providing dupicated value on both input parammeters.  Allows binding of an external **sessionContext** by providing a JSON object on body request.  This information, will be stored on every ticket created during this session, and could be used as extensible information for external integrations and product customization extensions as well.  **WARNING** Generated session token can be used only once, and would deprecate 300 secs after login.

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

$api_instance = new Swagger\Client\Api\SessionApi();
$unit_id = 56; // int | Id of unit entity, identifes **account**, used to own credit, store wallet transactions and own tickets.
$staff_id = 56; // int | Id of staff entity, identifies **user**, that perform operations during session, to a given unit/acount.
$session_context = "session_context_example"; // string | Extensible json object.  This object stores all information from GoldenRace external systems  for this user session.  Example.  External token session/or ids to be included on screen/ticket.

try {
    $result = $api_instance->sessionExternalLogin($unit_id, $staff_id, $session_context);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling SessionApi->sessionExternalLogin: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **unit_id** | **int**| Id of unit entity, identifes **account**, used to own credit, store wallet transactions and own tickets. |
 **staff_id** | **int**| Id of staff entity, identifies **user**, that perform operations during session, to a given unit/acount. |
 **session_context** | **string**| Extensible json object.  This object stores all information from GoldenRace external systems  for this user session.  Example.  External token session/or ids to be included on screen/ticket. | [optional]

### Return type

[**\Swagger\Client\Model\AuthResult**](../Model/AuthResult.md)

### Authorization

[apiHash](../../README.md#apiHash), [apiId](../../README.md#apiId)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

