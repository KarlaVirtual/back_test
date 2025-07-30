# WalletCreditResponse

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**ticket_id** | **int** |  | 
**result** | **string** | Confirm success of operation for specific request. A duplicated, should be considered as a success operation, with no effect. If error, error_id, and error_message should provide additional details about error. | 
**error_id** | **int** | Internal id of confirm error meesage. | [optional] 
**error_message** | **string** | Readable description of confirm error. | [optional] 
**type** | **string** |  | 
**old_credit** | **double** | Amount of credit before transaction is applied. If empty, old_credit is estimated, on last credit cached on client system. | [optional] 
**new_credit** | **double** | Amount of credit after transaction is applied. If empty, new_credit is estimated based on transaction request. | [optional] 
**ext_transaction_id** | **string** |  | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


