# Playlist

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**id** | **int** | Playlist Id. | [optional] 
**game_type** | [**\Swagger\Client\Model\ServerTicketGameType**](ServerTicketGameType.md) |  | [optional] 
**mode** | **string** | The mode define if the playlist is normal or a on-demand playlist. | [optional] 
**filter** | [**\Swagger\Client\Model\PlaylistFilter**](PlaylistFilter.md) |  | [optional] 
**capabilities** | [**\Swagger\Client\Model\PlaylistCapabilities[]**](PlaylistCapabilities.md) |  | [optional] 
**market_option_size** | **int** | Gets the size of the market options to be able to compare when we get the size of the array of odds values in the eventData. | [optional] 
**market_templates** | [**\Swagger\Client\Model\Market_[]**](Market_.md) | get info markets for playlist. | [optional] 
**participant_templates** | [**\Swagger\Client\Model\PlaylistParticipantTemplates[]**](PlaylistParticipantTemplates.md) |  | [optional] 
**description** | **string** | Playlist text description | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


