# ServerTicket

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**ticket_id** | **int** |  | [optional] 
**ext_id** | **string** |  | [optional] 
**ext_data** | **object** |  | [optional] 
**server_hash** | **string** |  | [optional] 
**ip** | **string** |  | [optional] 
**unit** | [**\Swagger\Client\Model\AuthResultClient**](AuthResultClient.md) |  | [optional] 
**pay_staff** | [**\Swagger\Client\Model\AuthResultClient**](AuthResultClient.md) |  | [optional] 
**sell_staff** | [**\Swagger\Client\Model\AuthResultClient**](AuthResultClient.md) |  | [optional] 
**time_send** | [**\DateTime**](\DateTime.md) | Timestamp (on client side) timestamp in RFC3339 / ISO_8601 format. Precission in milliseconds. for current ticket, used as transaction id, as no more than 2 tickets are allowed to be created on same timestamp. | [optional] 
**time_register** | [**\DateTime**](\DateTime.md) |  | [optional] 
**time_resolved** | [**\DateTime**](\DateTime.md) |  | [optional] 
**time_cancelled** | [**\DateTime**](\DateTime.md) |  | [optional] 
**time_paid** | [**\DateTime**](\DateTime.md) |  | [optional] 
**time_closed_market** | [**\DateTime**](\DateTime.md) |  | [optional] 
**status** | **string** |  | [optional] 
**currency** | [**\Swagger\Client\Model\CurrencyConfiguration**](CurrencyConfiguration.md) |  | [optional] 
**stake** | **double** | Amount without taxes. | [optional] 
**stake_taxes** | **double** | Total amount already paid by player on stake, in addition to total stake amount. | [optional] 
**won_data** | [**\Swagger\Client\Model\ServerTicketWonData**](ServerTicketWonData.md) |  | [optional] 
**winning_data** | [**\Swagger\Client\Model\ServerTicketWinningData**](ServerTicketWinningData.md) |  | [optional] 
**odd_settings_id** | **int** |  | [optional] 
**game_type** | [**\Swagger\Client\Model\ServerTicketGameType**](ServerTicketGameType.md) |  | [optional] 
**details** | [**\Swagger\Client\Model\Ticket_**](Ticket_.md) |  | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


