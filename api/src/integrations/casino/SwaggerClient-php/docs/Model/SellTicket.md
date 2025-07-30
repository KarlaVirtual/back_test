# SellTicket

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**time_send** | [**\DateTime**](\DateTime.md) | Timestamp (on client side) timestamp in RFC3339 / ISO_8601 format. Precission in milliseconds. for current ticket, used as transaction id, as no more than 2 tickets are allowed to be created on same timestamp. | [optional] 
**sell_staff** | [**\Swagger\Client\Model\AuthResultClient**](AuthResultClient.md) |  | [optional] 
**currency** | [**\Swagger\Client\Model\CurrencyConfiguration**](CurrencyConfiguration.md) |  | [optional] 
**odd_settings_id** | **int** |  | [optional] 
**game_type** | [**\Swagger\Client\Model\ServerTicketGameType**](ServerTicketGameType.md) |  | [optional] 
**details** | [**\Swagger\Client\Model\Ticket_**](Ticket_.md) |  | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


