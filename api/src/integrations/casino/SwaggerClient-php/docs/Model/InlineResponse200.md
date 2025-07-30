# InlineResponse200

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**transaction_id** | **int** | Transaction Id | [optional] 
**ext_transaction_id** | **string** | External transaction Id | [optional] 
**entity_id** | **int** | Entity Id , wich credit is modified | [optional] 
**ext_id** | **string** | External EntityId, wich credit is modified | [optional] 
**ticket_id** | **int** | Entity Id , wich credit is modified | [optional] 
**date** | [**\DateTime**](\DateTime.md) | Date of transaction | [optional] 
**previous_credit** | **double** | Previous Credit (Before Transaction) | [optional] 
**new_credit** | **double** | Resulting Credit (After Transaction) | [optional] 
**change_credit** | **double** | Amount of credit change requested on transaction.  In case that a wallet system is linked with a seamless wallet without transactional check,  the amounts on newCredit and previousCredit, are based on an incremental estimation,  and could not match 100% to changeCredit amount on the scenario of concurrent credit modifications. | [optional] 
**currency** | **string** | Currency ISO Code | [optional] 
**description** | **string** | Description of Transaction. | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


