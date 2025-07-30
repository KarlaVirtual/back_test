# Swagger\Client\EntityApi

All URIs are relative to *http://test-virtual.golden-race.net:8081/api/external/v0.1*

Method | HTTP request | Description
------------- | ------------- | -------------
[**entityAdd**](EntityApi.md#entityAdd) | **POST** /entity/add | 
[**entityApplyConfiguration**](EntityApi.md#entityApplyConfiguration) | **POST** /entity/applyConfiguration/localization | 
[**entityFind**](EntityApi.md#entityFind) | **GET** /entity/find | 
[**entityFindById**](EntityApi.md#entityFindById) | **GET** /entity/findById | 


# **entityAdd**
> \Swagger\Client\Model\AuthResultClient entityAdd($entity_parent_id, $entity_name, $ext_id, $ext_data)



Creation of a new entity, whose name (entityName), its external id (extId),  the external information (extData) and the id of the reference entity on which we want to create the new entity (parentId). - the entity id (**entityParentId**)  - the entity name (**entityName**),  - the external identifier (**extId**) - a JSON object that is used as Information repository on body request (**extData**).

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

$api_instance = new Swagger\Client\Api\EntityApi();
$entity_parent_id = 56; // int | The parent entity id.
$entity_name = "entity_name_example"; // string | The name to be assigned to the entity.
$ext_id = "ext_id_example"; // string | External unique id of account, for 3rd party integrations.
$ext_data = "ext_data_example"; // string | Information in json format so that the client can store any type in information that requires for its use.

try {
    $result = $api_instance->entityAdd($entity_parent_id, $entity_name, $ext_id, $ext_data);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling EntityApi->entityAdd: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **entity_parent_id** | **int**| The parent entity id. |
 **entity_name** | **string**| The name to be assigned to the entity. |
 **ext_id** | **string**| External unique id of account, for 3rd party integrations. | [optional]
 **ext_data** | **string**| Information in json format so that the client can store any type in information that requires for its use. | [optional]

### Return type

[**\Swagger\Client\Model\AuthResultClient**](../Model/AuthResultClient.md)

### Authorization

[apiHash](../../README.md#apiHash), [apiId](../../README.md#apiId)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **entityApplyConfiguration**
> entityApplyConfiguration($entity_id, $currency_code, $language, $timezone)



Applies currency, language and timezone settings to an entity.

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

$api_instance = new Swagger\Client\Api\EntityApi();
$entity_id = 56; // int | The id of the entity.
$currency_code = "currency_code_example"; // string | The currency code to be selected between - EUR  - GBP  - USD  - AUD  - BRL  - NZD  - CAD  - CHF  - CNY  - DKK  - HKD  - INR  - JPY  - KRW  - LKR  - MXN  - MYR  - NOK  - SEK  - SGD  - THB  - TWD  - VEF  - ZAR  - BGN  - CZK  - EEK  - HUF  - LTL  - LVL  - PLN  - RON  - SKK  - ISK  - HRK  - RUB  - TRY  - PHP  - COP  - ARS  - RWF  - BIF  - CRC  - KES  - PEN  - DOP  - BYR  - UAH  - NAD  - GEL  - PRB  - MDL  - KZT  - MUR  - KGS  - IEP  - MKD  - RSD  - AZN  - MGA  - BAM  - TJS  - ALL  - SRD  - NIO  - GHS  - XAF  - GMD  - IQD  - IRR  - NGN  - AMD  - HTG  - GTQ  - ZMW  - GOLD
$language = "language_example"; // string | The language code to be selected between - ww-WW   - en-GB   - nl-NL   - de-DE   - tr-TR   - es-ES   - pt-PT   - pl-PL   - ka-GE   - hi-IN   - it-IT   - ar-AE   - el-GR   - zh-CN   - ru-KIN  - ro-RO   - ru-RU   - sk-SK   - cs-CZ   - sq-AL   - da-DK   - fi-FI   - fr-FR   - nb-NO   - sv-SE   - sl-SI   - hu-HU   - sw-KE   - ne-NE   - fr-bel  - du-bel  - es-DR   - es-CR   - uk-ukr  - ja-jpn  - ko-nko  - lt-lit  - et-est  - sa-afr  - es-COL  - bu-run  - ug-swa  - ta-swa  - na-afr  - bel-bel - ko-sko  - es-mex  - kk-kaz  - es-arg  - en-aus  - vi-vie  - lv-lav  - tl-tgl  - id-ind  - bel-rus - pan-sp  - cyp-gr  - bg-bul  - es-ven  - ru-mos  - ky-rus  - rb-RB   - uk-RU   - az-AZ   - mk-MKD
$timezone = "timezone_example"; // string | The timezone to be selected between - \"Africa/Abidjan\" - \"Africa/Accra\" - \"Africa/Addis_Ababa\" - \"Africa/Algiers\" - \"Africa/Asmara\" - \"Africa/Asmera\" - \"Africa/Bamako\" - \"Africa/Bangui\" - \"Africa/Banjul\" - \"Africa/Bissau\" - \"Africa/Blantyre\" - \"Africa/Brazzaville\" - \"Africa/Bujumbura\" - \"Africa/Cairo\" - \"Africa/Casablanca\" - \"Africa/Ceuta\" - \"Africa/Conakry\" - \"Africa/Dakar\" - \"Africa/Dar_es_Salaam\" - \"Africa/Djibouti\" - \"Africa/Douala\" - \"Africa/El_Aaiun\" - \"Africa/Freetown\" - \"Africa/Gaborone\" - \"Africa/Harare\" - \"Africa/Johannesburg\" - \"Africa/Juba\" - \"Africa/Kampala\" - \"Africa/Khartoum\" - \"Africa/Kigali\" - \"Africa/Kinshasa\" - \"Africa/Lagos\" - \"Africa/Libreville\" - \"Africa/Lome\" - \"Africa/Luanda\" - \"Africa/Lubumbashi\" - \"Africa/Lusaka\" - \"Africa/Malabo\" - \"Africa/Maputo\" - \"Africa/Maseru\" - \"Africa/Mbabane\" - \"Africa/Mogadishu\" - \"Africa/Monrovia\" - \"Africa/Nairobi\" - \"Africa/Ndjamena\" - \"Africa/Niamey\" - \"Africa/Nouakchott\" - \"Africa/Ouagadougou\" - \"Africa/Porto-Novo\" - \"Africa/Sao_Tome\" - \"Africa/Timbuktu\" - \"Africa/Tripoli\" - \"Africa/Tunis\" - \"Africa/Windhoek\" - \"America/Adak\" - \"America/Anchorage\" - \"America/Anguilla\" - \"America/Antigua\" - \"America/Araguaina\" - \"America/Argentina/Buenos_Aires\" - \"America/Argentina/Catamarca\" - \"America/Argentina/ComodRivadavia\" - \"America/Argentina/Cordoba\" - \"America/Argentina/Jujuy\" - \"America/Argentina/La_Rioja\" - \"America/Argentina/Mendoza\" - \"America/Argentina/Rio_Gallegos\" - \"America/Argentina/Salta\" - \"America/Argentina/San_Juan\" - \"America/Argentina/San_Luis\" - \"America/Argentina/Tucuman\" - \"America/Argentina/Ushuaia\" - \"America/Aruba\" - \"America/Asuncion\" - \"America/Atikokan\" - \"America/Atka\" - \"America/Bahia\" - \"America/Bahia_Banderas\" - \"America/Barbados\" - \"America/Belem\" - \"America/Belize\" - \"America/Blanc-Sablon\" - \"America/Boa_Vista\" - \"America/Bogota\" - \"America/Boise\" - \"America/Buenos_Aires\" - \"America/Cambridge_Bay\" - \"America/Campo_Grande\" - \"America/Cancun\" - \"America/Caracas\" - \"America/Catamarca\" - \"America/Cayenne\" - \"America/Cayman\" - \"America/Chicago\" - \"America/Chihuahua\" - \"America/Coral_Harbour\" - \"America/Cordoba\" - \"America/Costa_Rica\" - \"America/Creston\" - \"America/Cuiaba\" - \"America/Curacao\" - \"America/Danmarkshavn\" - \"America/Dawson,\" - \"America/Dawson_Creek\" - \"America/Denver\" - \"America/Detroit\" - \"America/Dominica\" - \"America/Edmonton\" - \"America/Eirunepe\" - \"America/El_Salvador\" - \"America/Ensenada,\" - \"America/Fort_Nelson\" - \"America/Fort_Wayne\" - \"America/Fortaleza\" - \"America/Glace_Bay\" - \"America/Godthab\" - \"America/Goose_Bay\" - \"America/Grand_Turk\" - \"America/Grenada\" - \"America/Guadeloupe\" - \"America/Guatemala\" - \"America/Guayaquil\" - \"America/Guyana\" - \"America/Halifax\" - \"America/Havana\" - \"America/Hermosillo\" - \"America/Indiana/Indianapolis\" - \"America/Indiana/Knox\" - \"America/Indiana/Marengo\" - \"America/Indiana/Petersburg\" - \"America/Indiana/Tell_City\" - \"America/Indiana/Vevay\" - \"America/Indiana/Vincennes\" - \"America/Indiana/Winamac\" - \"America/Indianapolis\" - \"America/Inuvik\" - \"America/Iqaluit\" - \"America/Jamaica\" - \"America/Jujuy\" - \"America/Juneau\" - \"America/Kentucky/Louisville\" - \"America/Kentucky/Monticello\" - \"America/Knox_IN\" - \"America/Kralendijk\" - \"America/La_Paz\" - \"America/Lima\" - \"America/Los_Angeles,\" - \"America/Louisville\" - \"America/Lower_Princes\" - \"America/Maceio\" - \"America/Managua\" - \"America/Manaus\" - \"America/Marigot\" - \"America/Martinique\" - \"America/Matamoros\" - \"America/Mazatlan\" - \"America/Mendoza\" - \"America/Menominee\" - \"America/Merida\" - \"America/Metlakatla\" - \"America/Mexico_City\" - \"America/Miquelon\" - \"America/Moncton\" - \"America/Monterrey\" - \"America/Montevideo\" - \"America/Montreal\" - \"America/Montserrat\" - \"America/Nassau\" - \"America/New_York\" - \"America/Nipigon\" - \"America/Nome\" - \"America/Noronha\" - \"America/North_Dakota/Beulah\" - \"America/North_Dakota/Center\" - \"America/North_Dakota/New_Salem\" - \"America/Ojinaga\" - \"America/Panama\" - \"America/Pangnirtung\" - \"America/Paramaribo\" - \"America/Phoenix\" - \"America/Port-au-Prince\" - \"America/Port_of_Spain\" - \"America/Porto_Acre\" - \"America/Porto_Velho\" - \"America/Puerto_Rico\" - \"America/Rainy_River\" - \"America/Rankin_Inlet\" - \"America/Recife\" - \"America/Regina\" - \"America/Resolute\" - \"America/Rio_Branco\" - \"America/Rosario\" - \"America/Santa_Isabel\" - \"America/Santarem\" - \"America/Santiago\" - \"America/Santo_Domingo\" - \"America/Sao_Paulo\" - \"America/Scoresbysund\" - \"America/Shiprock\" - \"America/Sitka\" - \"America/St_Barthelemy\" - \"America/St_Johns\" - \"America/St_Kitts\" - \"America/St_Lucia\" - \"America/St_Thomas\" - \"America/St_Vincent\" - \"America/Swift_Current\" - \"America/Tegucigalpa\" - \"America/Thule\" - \"America/Thunder_Bay\" - \"America/Tijuana,\" - \"America/Toronto\" - \"America/Tortola\" - \"America/Vancouver,\" - \"America/Virgin\" - \"America/Whitehorse,\" - \"America/Winnipeg\" - \"America/Yakutat\" - \"America/Yellowknife\" - \"Antarctica/Casey\" - \"Antarctica/Davis\" - \"Antarctica/DumontDUrville\" - \"Antarctica/Macquarie\" - \"Antarctica/Mawson\" - \"Antarctica/McMurdo\" - \"Antarctica/Palmer\" - \"Antarctica/Rothera\" - \"Antarctica/South_Pole\" - \"Antarctica/Syowa\" - \"Antarctica/Troll\" - \"Antarctica/Vostok\" - \"Arctic/Longyearbyen\" - \"Asia/Aden\" - \"Asia/Almaty\" - \"Asia/Amman\" - \"Asia/Anadyr\" - \"Asia/Aqtau\" - \"Asia/Aqtobe\" - \"Asia/Ashgabat\" - \"Asia/Ashkhabad\" - \"Asia/Baghdad\" - \"Asia/Bahrain\" - \"Asia/Baku\" - \"Asia/Bangkok\" - \"Asia/Barnaul\" - \"Asia/Beirut\" - \"Asia/Bishkek\" - \"Asia/Brunei\" - \"Asia/Calcutta\" - \"Asia/Chita\" - \"Asia/Choibalsan\" - \"Asia/Chongqing\" - \"Asia/Chungking\" - \"Asia/Colombo\" - \"Asia/Dacca\" - \"Asia/Damascus\" - \"Asia/Dhaka\" - \"Asia/Dili\" - \"Asia/Dubai\" - \"Asia/Dushanbe\" - \"Asia/Gaza\" - \"Asia/Harbin\" - \"Asia/Hebron\" - \"Asia/Ho_Chi_Minh\" - \"Asia/Hong_Kong\" - \"Asia/Hovd\" - \"Asia/Irkutsk\" - \"Asia/Istanbul\" - \"Asia/Jakarta\" - \"Asia/Jayapura\" - \"Asia/Jerusalem\" - \"Asia/Kabul\" - \"Asia/Kamchatka\" - \"Asia/Karachi\" - \"Asia/Kashgar\" - \"Asia/Kathmandu\" - \"Asia/Katmandu\" - \"Asia/Khandyga\" - \"Asia/Kolkata\" - \"Asia/Krasnoyarsk\" - \"Asia/Kuala_Lumpur\" - \"Asia/Kuching\" - \"Asia/Kuwait\" - \"Asia/Macao\" - \"Asia/Macau\" - \"Asia/Magadan\" - \"Asia/Makassar\" - \"Asia/Manila\" - \"Asia/Muscat\" - \"Asia/Nicosia\" - \"Asia/Novokuznetsk\" - \"Asia/Novosibirsk\" - \"Asia/Omsk\" - \"Asia/Oral\" - \"Asia/Phnom_Penh\" - \"Asia/Pontianak\" - \"Asia/Pyongyang\" - \"Asia/Qatar\" - \"Asia/Qyzylorda\" - \"Asia/Rangoon\" - \"Asia/Riyadh\" - \"Asia/Saigon\" - \"Asia/Sakhalin\" - \"Asia/Samarkand\" - \"Asia/Seoul\" - \"Asia/Shanghai\" - \"Asia/Singapore\" - \"Asia/Srednekolymsk\" - \"Asia/Taipei\" - \"Asia/Tashkent\" - \"Asia/Tbilisi\" - \"Asia/Tehran\" - \"Asia/Tel_Aviv\" - \"Asia/Thimbu\" - \"Asia/Thimphu\" - \"Asia/Tokyo\" - \"Asia/Tomsk\" - \"Asia/Ujung_Pandang\" - \"Asia/Ulaanbaatar\" - \"Asia/Ulan_Bator\" - \"Asia/Urumqi\" - \"Asia/Ust-Nera\" - \"Asia/Vientiane\" - \"Asia/Vladivostok\" - \"Asia/Yakutsk\" - \"Asia/Yekaterinburg\" - \"Asia/Yerevan\" - \"Atlantic/Azores\" - \"Atlantic/Bermuda\" - \"Atlantic/Canary\" - \"Atlantic/Cape_Verde\" - \"Atlantic/Faeroe\" - \"Atlantic/Faroe\" - \"Atlantic/Jan_Mayen\" - \"Atlantic/Madeira\" - \"Atlantic/Reykjavik\" - \"Atlantic/South_Georgia\" - \"Atlantic/St_Helena\" - \"Atlantic/Stanley\" - \"Australia/ACT\" - \"Australia/Adelaide\" - \"Australia/Brisbane\" - \"Australia/Broken_Hill\" - \"Australia/Canberra\" - \"Australia/Currie\" - \"Australia/Darwin\" - \"Australia/Eucla\" - \"Australia/Hobart\" - \"Australia/LHI\" - \"Australia/Lindeman\" - \"Australia/Lord_Howe\" - \"Australia/Melbourne\" - \"Australia/NSW\" - \"Australia/North\" - \"Australia/Perth\" - \"Australia/Queensland\" - \"Australia/South\" - \"Australia/Sydney\" - \"Australia/Tasmania\" - \"Australia/Victoria\" - \"Australia/West\" - \"Australia/Yancowinna\" - \"Brazil/Acre\" - \"Brazil/DeNoronha\" - \"Brazil/East\" - \"Brazil/West\" - \"CET\" - \"CST6CDT\" - \"Canada/Atlantic\" - \"Canada/Central\" - \"Canada/East-Saskatchewan\" - \"Canada/Eastern\" - \"Canada/Mountain\" - \"Canada/Newfoundland\" - \"Canada/Pacific,\" - \"Canada/Saskatchewan\" - \"Canada/Yukon,\" - \"Chile/Continental\" - \"Chile/EasterIsland\" - \"Cuba\" - \"EET\" - \"EST5EDT\" - \"Egypt\" - \"Eire\" - \"Etc/GMT\" - \"Etc/GMT+0\" - \"Etc/GMT+1\" - \"Etc/GMT+10\" - \"Etc/GMT+11\" - \"Etc/GMT+12\" - \"Etc/GMT+2\" - \"Etc/GMT+3\" - \"Etc/GMT+4\" - \"Etc/GMT+5\" - \"Etc/GMT+6\" - \"Etc/GMT+7\" - \"Etc/GMT+8,\" - \"Etc/GMT+9\" - \"Etc/GMT-0\" - \"Etc/GMT-1\" - \"Etc/GMT-10\" - \"Etc/GMT-11\" - \"Etc/GMT-12\" - \"Etc/GMT-13\" - \"Etc/GMT-14\" - \"Etc/GMT-2\" - \"Etc/GMT-3\" - \"Etc/GMT-4\" - \"Etc/GMT-5\" - \"Etc/GMT-6\" - \"Etc/GMT-7\" - \"Etc/GMT-8\" - \"Etc/GMT-9\" - \"Etc/GMT0\" - \"Etc/Greenwich\" - \"Etc/UCT\" - \"Etc/UTC\" - \"Etc/Universal\" - \"Etc/Zulu\" - \"Europe/Amsterdam\" - \"Europe/Andorra\" - \"Europe/Astrakhan\" - \"Europe/Athens\" - \"Europe/Belfast\" - \"Europe/Belgrade\" - \"Europe/Berlin\" - \"Europe/Bratislava\" - \"Europe/Brussels\" - \"Europe/Bucharest\" - \"Europe/Budapest\" - \"Europe/Busingen\" - \"Europe/Chisinau\" - \"Europe/Copenhagen\" - \"Europe/Dublin\" - \"Europe/Gibraltar\" - \"Europe/Guernsey\" - \"Europe/Helsinki\" - \"Europe/Isle_of_Man\" - \"Europe/Istanbul\" - \"Europe/Jersey\" - \"Europe/Kaliningrad\" - \"Europe/Kiev\" - \"Europe/Kirov\" - \"Europe/Lisbon\" - \"Europe/Ljubljana\" - \"Europe/London\" - \"Europe/Luxembourg\" - \"Europe/Madrid\" - \"Europe/Malta\" - \"Europe/Mariehamn\" - \"Europe/Minsk\" - \"Europe/Monaco\" - \"Europe/Moscow\" - \"Europe/Nicosia\" - \"Europe/Oslo\" - \"Europe/Paris\" - \"Europe/Podgorica\" - \"Europe/Prague\" - \"Europe/Riga\" - \"Europe/Rome\" - \"Europe/Samara\" - \"Europe/San_Marino\" - \"Europe/Sarajevo\" - \"Europe/Simferopol\" - \"Europe/Skopje\" - \"Europe/Sofia\" - \"Europe/Stockholm\" - \"Europe/Tallinn\" - \"Europe/Tirane\" - \"Europe/Tiraspol\" - \"Europe/Ulyanovsk\" - \"Europe/Uzhgorod\" - \"Europe/Vaduz\" - \"Europe/Vatican\" - \"Europe/Vienna\" - \"Europe/Vilnius\" - \"Europe/Volgograd\" - \"Europe/Warsaw\" - \"Europe/Zagreb\" - \"Europe/Zaporozhye\" - \"Europe/Zurich\" - \"GB\" - \"GB-Eire\" - \"GMT\" - \"GMT0\" - \"Greenwich\" - \"Hongkong\" - \"Iceland\" - \"Indian/Antananarivo\" - \"Indian/Chagos\" - \"Indian/Christmas\" - \"Indian/Cocos\" - \"Indian/Comoro\" - \"Indian/Kerguelen\" - \"Indian/Mahe\" - \"Indian/Maldives\" - \"Indian/Mauritius\" - \"Indian/Mayotte\" - \"Indian/Reunion\" - \"Iran\" - \"Israel\" - \"Jamaica\" - \"Japan\" - \"Kwajalein\" - \"Libya\" - \"MET\" - \"MST7MDT\" - \"Mexico/BajaNorte,\" - \"Mexico/BajaSur\" - \"Mexico/General\" - \"NZ\" - \"NZ-CHAT\" - \"Navajo\" - \"PRC\" - \"PST8PDT,\" - \"Pacific/Apia\" - \"Pacific/Auckland\" - \"Pacific/Bougainville\" - \"Pacific/Chatham\" - \"Pacific/Chuuk\" - \"Pacific/Easter\" - \"Pacific/Efate\" - \"Pacific/Enderbury\" - \"Pacific/Fakaofo\" - \"Pacific/Fiji\" - \"Pacific/Funafuti\" - \"Pacific/Galapagos\" - \"Pacific/Gambier\" - \"Pacific/Guadalcanal\" - \"Pacific/Guam\" - \"Pacific/Honolulu\" - \"Pacific/Johnston\" - \"Pacific/Kiritimati\" - \"Pacific/Kosrae\" - \"Pacific/Kwajalein\" - \"Pacific/Majuro\" - \"Pacific/Marquesas\" - \"Pacific/Midway\" - \"Pacific/Nauru\" - \"Pacific/Niue\" - \"Pacific/Norfolk\" - \"Pacific/Noumea\" - \"Pacific/Pago_Pago\" - \"Pacific/Palau\" - \"Pacific/Pitcairn,\" - \"Pacific/Pohnpei\" - \"Pacific/Ponape\" - \"Pacific/Port_Moresby\" - \"Pacific/Rarotonga\" - \"Pacific/Saipan\" - \"Pacific/Samoa\" - \"Pacific/Tahiti\" - \"Pacific/Tarawa\" - \"Pacific/Tongatapu\" - \"Pacific/Truk\" - \"Pacific/Wake\" - \"Pacific/Wallis\" - \"Pacific/Yap\" - \"Poland\" - \"Portugal\" - \"ROK\" - \"Singapore\" - \"SystemV/AST4\" - \"SystemV/AST4ADT\" - \"SystemV/CST6\" - \"SystemV/CST6CDT\" - \"SystemV/EST5\" - \"SystemV/EST5EDT\" - \"SystemV/HST10\" - \"SystemV/MST7\" - \"SystemV/MST7MDT\" - \"SystemV/PST8,\" - \"SystemV/PST8PDT,\" - \"SystemV/YST9\" - \"SystemV/YST9YDT\" - \"Turkey\" - \"UCT\" - \"US/Alaska\" - \"US/Aleutian\" - \"US/Arizona\" - \"US/Central\" - \"US/East-Indiana\" - \"US/Eastern\" - \"US/Hawaii\" - \"US/Indiana-Starke\" - \"US/Michigan\" - \"US/Mountain\" - \"US/Pacific,\" - \"US/Pacific-New,\" - \"US/Samoa\" - \"UTC\" - \"Universal\" - \"W-SU\" - \"WET\" - \"Zulu\" - \"EST\" - \"HST\" - \"MST\" - \"ACT\" - \"AET\" - \"AGT\" - \"ART\" - \"AST\" - \"BET\" - \"BST\" - \"CAT\" - \"CNT\" - \"CST\" - \"CTT\" - \"EAT\" - \"ECT\" - \"IET\" - \"IST\" - \"JST\" - \"MIT\" - \"NET\" - \"NST\" - \"PLT\" - \"PNT\" - \"PRT\" - \"PST\" - \"SST\" - \"VST\"

try {
    $api_instance->entityApplyConfiguration($entity_id, $currency_code, $language, $timezone);
} catch (Exception $e) {
    echo 'Exception when calling EntityApi->entityApplyConfiguration: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **entity_id** | **int**| The id of the entity. |
 **currency_code** | **string**| The currency code to be selected between - EUR  - GBP  - USD  - AUD  - BRL  - NZD  - CAD  - CHF  - CNY  - DKK  - HKD  - INR  - JPY  - KRW  - LKR  - MXN  - MYR  - NOK  - SEK  - SGD  - THB  - TWD  - VEF  - ZAR  - BGN  - CZK  - EEK  - HUF  - LTL  - LVL  - PLN  - RON  - SKK  - ISK  - HRK  - RUB  - TRY  - PHP  - COP  - ARS  - RWF  - BIF  - CRC  - KES  - PEN  - DOP  - BYR  - UAH  - NAD  - GEL  - PRB  - MDL  - KZT  - MUR  - KGS  - IEP  - MKD  - RSD  - AZN  - MGA  - BAM  - TJS  - ALL  - SRD  - NIO  - GHS  - XAF  - GMD  - IQD  - IRR  - NGN  - AMD  - HTG  - GTQ  - ZMW  - GOLD |
 **language** | **string**| The language code to be selected between - ww-WW   - en-GB   - nl-NL   - de-DE   - tr-TR   - es-ES   - pt-PT   - pl-PL   - ka-GE   - hi-IN   - it-IT   - ar-AE   - el-GR   - zh-CN   - ru-KIN  - ro-RO   - ru-RU   - sk-SK   - cs-CZ   - sq-AL   - da-DK   - fi-FI   - fr-FR   - nb-NO   - sv-SE   - sl-SI   - hu-HU   - sw-KE   - ne-NE   - fr-bel  - du-bel  - es-DR   - es-CR   - uk-ukr  - ja-jpn  - ko-nko  - lt-lit  - et-est  - sa-afr  - es-COL  - bu-run  - ug-swa  - ta-swa  - na-afr  - bel-bel - ko-sko  - es-mex  - kk-kaz  - es-arg  - en-aus  - vi-vie  - lv-lav  - tl-tgl  - id-ind  - bel-rus - pan-sp  - cyp-gr  - bg-bul  - es-ven  - ru-mos  - ky-rus  - rb-RB   - uk-RU   - az-AZ   - mk-MKD |
 **timezone** | **string**| The timezone to be selected between - \&quot;Africa/Abidjan\&quot; - \&quot;Africa/Accra\&quot; - \&quot;Africa/Addis_Ababa\&quot; - \&quot;Africa/Algiers\&quot; - \&quot;Africa/Asmara\&quot; - \&quot;Africa/Asmera\&quot; - \&quot;Africa/Bamako\&quot; - \&quot;Africa/Bangui\&quot; - \&quot;Africa/Banjul\&quot; - \&quot;Africa/Bissau\&quot; - \&quot;Africa/Blantyre\&quot; - \&quot;Africa/Brazzaville\&quot; - \&quot;Africa/Bujumbura\&quot; - \&quot;Africa/Cairo\&quot; - \&quot;Africa/Casablanca\&quot; - \&quot;Africa/Ceuta\&quot; - \&quot;Africa/Conakry\&quot; - \&quot;Africa/Dakar\&quot; - \&quot;Africa/Dar_es_Salaam\&quot; - \&quot;Africa/Djibouti\&quot; - \&quot;Africa/Douala\&quot; - \&quot;Africa/El_Aaiun\&quot; - \&quot;Africa/Freetown\&quot; - \&quot;Africa/Gaborone\&quot; - \&quot;Africa/Harare\&quot; - \&quot;Africa/Johannesburg\&quot; - \&quot;Africa/Juba\&quot; - \&quot;Africa/Kampala\&quot; - \&quot;Africa/Khartoum\&quot; - \&quot;Africa/Kigali\&quot; - \&quot;Africa/Kinshasa\&quot; - \&quot;Africa/Lagos\&quot; - \&quot;Africa/Libreville\&quot; - \&quot;Africa/Lome\&quot; - \&quot;Africa/Luanda\&quot; - \&quot;Africa/Lubumbashi\&quot; - \&quot;Africa/Lusaka\&quot; - \&quot;Africa/Malabo\&quot; - \&quot;Africa/Maputo\&quot; - \&quot;Africa/Maseru\&quot; - \&quot;Africa/Mbabane\&quot; - \&quot;Africa/Mogadishu\&quot; - \&quot;Africa/Monrovia\&quot; - \&quot;Africa/Nairobi\&quot; - \&quot;Africa/Ndjamena\&quot; - \&quot;Africa/Niamey\&quot; - \&quot;Africa/Nouakchott\&quot; - \&quot;Africa/Ouagadougou\&quot; - \&quot;Africa/Porto-Novo\&quot; - \&quot;Africa/Sao_Tome\&quot; - \&quot;Africa/Timbuktu\&quot; - \&quot;Africa/Tripoli\&quot; - \&quot;Africa/Tunis\&quot; - \&quot;Africa/Windhoek\&quot; - \&quot;America/Adak\&quot; - \&quot;America/Anchorage\&quot; - \&quot;America/Anguilla\&quot; - \&quot;America/Antigua\&quot; - \&quot;America/Araguaina\&quot; - \&quot;America/Argentina/Buenos_Aires\&quot; - \&quot;America/Argentina/Catamarca\&quot; - \&quot;America/Argentina/ComodRivadavia\&quot; - \&quot;America/Argentina/Cordoba\&quot; - \&quot;America/Argentina/Jujuy\&quot; - \&quot;America/Argentina/La_Rioja\&quot; - \&quot;America/Argentina/Mendoza\&quot; - \&quot;America/Argentina/Rio_Gallegos\&quot; - \&quot;America/Argentina/Salta\&quot; - \&quot;America/Argentina/San_Juan\&quot; - \&quot;America/Argentina/San_Luis\&quot; - \&quot;America/Argentina/Tucuman\&quot; - \&quot;America/Argentina/Ushuaia\&quot; - \&quot;America/Aruba\&quot; - \&quot;America/Asuncion\&quot; - \&quot;America/Atikokan\&quot; - \&quot;America/Atka\&quot; - \&quot;America/Bahia\&quot; - \&quot;America/Bahia_Banderas\&quot; - \&quot;America/Barbados\&quot; - \&quot;America/Belem\&quot; - \&quot;America/Belize\&quot; - \&quot;America/Blanc-Sablon\&quot; - \&quot;America/Boa_Vista\&quot; - \&quot;America/Bogota\&quot; - \&quot;America/Boise\&quot; - \&quot;America/Buenos_Aires\&quot; - \&quot;America/Cambridge_Bay\&quot; - \&quot;America/Campo_Grande\&quot; - \&quot;America/Cancun\&quot; - \&quot;America/Caracas\&quot; - \&quot;America/Catamarca\&quot; - \&quot;America/Cayenne\&quot; - \&quot;America/Cayman\&quot; - \&quot;America/Chicago\&quot; - \&quot;America/Chihuahua\&quot; - \&quot;America/Coral_Harbour\&quot; - \&quot;America/Cordoba\&quot; - \&quot;America/Costa_Rica\&quot; - \&quot;America/Creston\&quot; - \&quot;America/Cuiaba\&quot; - \&quot;America/Curacao\&quot; - \&quot;America/Danmarkshavn\&quot; - \&quot;America/Dawson,\&quot; - \&quot;America/Dawson_Creek\&quot; - \&quot;America/Denver\&quot; - \&quot;America/Detroit\&quot; - \&quot;America/Dominica\&quot; - \&quot;America/Edmonton\&quot; - \&quot;America/Eirunepe\&quot; - \&quot;America/El_Salvador\&quot; - \&quot;America/Ensenada,\&quot; - \&quot;America/Fort_Nelson\&quot; - \&quot;America/Fort_Wayne\&quot; - \&quot;America/Fortaleza\&quot; - \&quot;America/Glace_Bay\&quot; - \&quot;America/Godthab\&quot; - \&quot;America/Goose_Bay\&quot; - \&quot;America/Grand_Turk\&quot; - \&quot;America/Grenada\&quot; - \&quot;America/Guadeloupe\&quot; - \&quot;America/Guatemala\&quot; - \&quot;America/Guayaquil\&quot; - \&quot;America/Guyana\&quot; - \&quot;America/Halifax\&quot; - \&quot;America/Havana\&quot; - \&quot;America/Hermosillo\&quot; - \&quot;America/Indiana/Indianapolis\&quot; - \&quot;America/Indiana/Knox\&quot; - \&quot;America/Indiana/Marengo\&quot; - \&quot;America/Indiana/Petersburg\&quot; - \&quot;America/Indiana/Tell_City\&quot; - \&quot;America/Indiana/Vevay\&quot; - \&quot;America/Indiana/Vincennes\&quot; - \&quot;America/Indiana/Winamac\&quot; - \&quot;America/Indianapolis\&quot; - \&quot;America/Inuvik\&quot; - \&quot;America/Iqaluit\&quot; - \&quot;America/Jamaica\&quot; - \&quot;America/Jujuy\&quot; - \&quot;America/Juneau\&quot; - \&quot;America/Kentucky/Louisville\&quot; - \&quot;America/Kentucky/Monticello\&quot; - \&quot;America/Knox_IN\&quot; - \&quot;America/Kralendijk\&quot; - \&quot;America/La_Paz\&quot; - \&quot;America/Lima\&quot; - \&quot;America/Los_Angeles,\&quot; - \&quot;America/Louisville\&quot; - \&quot;America/Lower_Princes\&quot; - \&quot;America/Maceio\&quot; - \&quot;America/Managua\&quot; - \&quot;America/Manaus\&quot; - \&quot;America/Marigot\&quot; - \&quot;America/Martinique\&quot; - \&quot;America/Matamoros\&quot; - \&quot;America/Mazatlan\&quot; - \&quot;America/Mendoza\&quot; - \&quot;America/Menominee\&quot; - \&quot;America/Merida\&quot; - \&quot;America/Metlakatla\&quot; - \&quot;America/Mexico_City\&quot; - \&quot;America/Miquelon\&quot; - \&quot;America/Moncton\&quot; - \&quot;America/Monterrey\&quot; - \&quot;America/Montevideo\&quot; - \&quot;America/Montreal\&quot; - \&quot;America/Montserrat\&quot; - \&quot;America/Nassau\&quot; - \&quot;America/New_York\&quot; - \&quot;America/Nipigon\&quot; - \&quot;America/Nome\&quot; - \&quot;America/Noronha\&quot; - \&quot;America/North_Dakota/Beulah\&quot; - \&quot;America/North_Dakota/Center\&quot; - \&quot;America/North_Dakota/New_Salem\&quot; - \&quot;America/Ojinaga\&quot; - \&quot;America/Panama\&quot; - \&quot;America/Pangnirtung\&quot; - \&quot;America/Paramaribo\&quot; - \&quot;America/Phoenix\&quot; - \&quot;America/Port-au-Prince\&quot; - \&quot;America/Port_of_Spain\&quot; - \&quot;America/Porto_Acre\&quot; - \&quot;America/Porto_Velho\&quot; - \&quot;America/Puerto_Rico\&quot; - \&quot;America/Rainy_River\&quot; - \&quot;America/Rankin_Inlet\&quot; - \&quot;America/Recife\&quot; - \&quot;America/Regina\&quot; - \&quot;America/Resolute\&quot; - \&quot;America/Rio_Branco\&quot; - \&quot;America/Rosario\&quot; - \&quot;America/Santa_Isabel\&quot; - \&quot;America/Santarem\&quot; - \&quot;America/Santiago\&quot; - \&quot;America/Santo_Domingo\&quot; - \&quot;America/Sao_Paulo\&quot; - \&quot;America/Scoresbysund\&quot; - \&quot;America/Shiprock\&quot; - \&quot;America/Sitka\&quot; - \&quot;America/St_Barthelemy\&quot; - \&quot;America/St_Johns\&quot; - \&quot;America/St_Kitts\&quot; - \&quot;America/St_Lucia\&quot; - \&quot;America/St_Thomas\&quot; - \&quot;America/St_Vincent\&quot; - \&quot;America/Swift_Current\&quot; - \&quot;America/Tegucigalpa\&quot; - \&quot;America/Thule\&quot; - \&quot;America/Thunder_Bay\&quot; - \&quot;America/Tijuana,\&quot; - \&quot;America/Toronto\&quot; - \&quot;America/Tortola\&quot; - \&quot;America/Vancouver,\&quot; - \&quot;America/Virgin\&quot; - \&quot;America/Whitehorse,\&quot; - \&quot;America/Winnipeg\&quot; - \&quot;America/Yakutat\&quot; - \&quot;America/Yellowknife\&quot; - \&quot;Antarctica/Casey\&quot; - \&quot;Antarctica/Davis\&quot; - \&quot;Antarctica/DumontDUrville\&quot; - \&quot;Antarctica/Macquarie\&quot; - \&quot;Antarctica/Mawson\&quot; - \&quot;Antarctica/McMurdo\&quot; - \&quot;Antarctica/Palmer\&quot; - \&quot;Antarctica/Rothera\&quot; - \&quot;Antarctica/South_Pole\&quot; - \&quot;Antarctica/Syowa\&quot; - \&quot;Antarctica/Troll\&quot; - \&quot;Antarctica/Vostok\&quot; - \&quot;Arctic/Longyearbyen\&quot; - \&quot;Asia/Aden\&quot; - \&quot;Asia/Almaty\&quot; - \&quot;Asia/Amman\&quot; - \&quot;Asia/Anadyr\&quot; - \&quot;Asia/Aqtau\&quot; - \&quot;Asia/Aqtobe\&quot; - \&quot;Asia/Ashgabat\&quot; - \&quot;Asia/Ashkhabad\&quot; - \&quot;Asia/Baghdad\&quot; - \&quot;Asia/Bahrain\&quot; - \&quot;Asia/Baku\&quot; - \&quot;Asia/Bangkok\&quot; - \&quot;Asia/Barnaul\&quot; - \&quot;Asia/Beirut\&quot; - \&quot;Asia/Bishkek\&quot; - \&quot;Asia/Brunei\&quot; - \&quot;Asia/Calcutta\&quot; - \&quot;Asia/Chita\&quot; - \&quot;Asia/Choibalsan\&quot; - \&quot;Asia/Chongqing\&quot; - \&quot;Asia/Chungking\&quot; - \&quot;Asia/Colombo\&quot; - \&quot;Asia/Dacca\&quot; - \&quot;Asia/Damascus\&quot; - \&quot;Asia/Dhaka\&quot; - \&quot;Asia/Dili\&quot; - \&quot;Asia/Dubai\&quot; - \&quot;Asia/Dushanbe\&quot; - \&quot;Asia/Gaza\&quot; - \&quot;Asia/Harbin\&quot; - \&quot;Asia/Hebron\&quot; - \&quot;Asia/Ho_Chi_Minh\&quot; - \&quot;Asia/Hong_Kong\&quot; - \&quot;Asia/Hovd\&quot; - \&quot;Asia/Irkutsk\&quot; - \&quot;Asia/Istanbul\&quot; - \&quot;Asia/Jakarta\&quot; - \&quot;Asia/Jayapura\&quot; - \&quot;Asia/Jerusalem\&quot; - \&quot;Asia/Kabul\&quot; - \&quot;Asia/Kamchatka\&quot; - \&quot;Asia/Karachi\&quot; - \&quot;Asia/Kashgar\&quot; - \&quot;Asia/Kathmandu\&quot; - \&quot;Asia/Katmandu\&quot; - \&quot;Asia/Khandyga\&quot; - \&quot;Asia/Kolkata\&quot; - \&quot;Asia/Krasnoyarsk\&quot; - \&quot;Asia/Kuala_Lumpur\&quot; - \&quot;Asia/Kuching\&quot; - \&quot;Asia/Kuwait\&quot; - \&quot;Asia/Macao\&quot; - \&quot;Asia/Macau\&quot; - \&quot;Asia/Magadan\&quot; - \&quot;Asia/Makassar\&quot; - \&quot;Asia/Manila\&quot; - \&quot;Asia/Muscat\&quot; - \&quot;Asia/Nicosia\&quot; - \&quot;Asia/Novokuznetsk\&quot; - \&quot;Asia/Novosibirsk\&quot; - \&quot;Asia/Omsk\&quot; - \&quot;Asia/Oral\&quot; - \&quot;Asia/Phnom_Penh\&quot; - \&quot;Asia/Pontianak\&quot; - \&quot;Asia/Pyongyang\&quot; - \&quot;Asia/Qatar\&quot; - \&quot;Asia/Qyzylorda\&quot; - \&quot;Asia/Rangoon\&quot; - \&quot;Asia/Riyadh\&quot; - \&quot;Asia/Saigon\&quot; - \&quot;Asia/Sakhalin\&quot; - \&quot;Asia/Samarkand\&quot; - \&quot;Asia/Seoul\&quot; - \&quot;Asia/Shanghai\&quot; - \&quot;Asia/Singapore\&quot; - \&quot;Asia/Srednekolymsk\&quot; - \&quot;Asia/Taipei\&quot; - \&quot;Asia/Tashkent\&quot; - \&quot;Asia/Tbilisi\&quot; - \&quot;Asia/Tehran\&quot; - \&quot;Asia/Tel_Aviv\&quot; - \&quot;Asia/Thimbu\&quot; - \&quot;Asia/Thimphu\&quot; - \&quot;Asia/Tokyo\&quot; - \&quot;Asia/Tomsk\&quot; - \&quot;Asia/Ujung_Pandang\&quot; - \&quot;Asia/Ulaanbaatar\&quot; - \&quot;Asia/Ulan_Bator\&quot; - \&quot;Asia/Urumqi\&quot; - \&quot;Asia/Ust-Nera\&quot; - \&quot;Asia/Vientiane\&quot; - \&quot;Asia/Vladivostok\&quot; - \&quot;Asia/Yakutsk\&quot; - \&quot;Asia/Yekaterinburg\&quot; - \&quot;Asia/Yerevan\&quot; - \&quot;Atlantic/Azores\&quot; - \&quot;Atlantic/Bermuda\&quot; - \&quot;Atlantic/Canary\&quot; - \&quot;Atlantic/Cape_Verde\&quot; - \&quot;Atlantic/Faeroe\&quot; - \&quot;Atlantic/Faroe\&quot; - \&quot;Atlantic/Jan_Mayen\&quot; - \&quot;Atlantic/Madeira\&quot; - \&quot;Atlantic/Reykjavik\&quot; - \&quot;Atlantic/South_Georgia\&quot; - \&quot;Atlantic/St_Helena\&quot; - \&quot;Atlantic/Stanley\&quot; - \&quot;Australia/ACT\&quot; - \&quot;Australia/Adelaide\&quot; - \&quot;Australia/Brisbane\&quot; - \&quot;Australia/Broken_Hill\&quot; - \&quot;Australia/Canberra\&quot; - \&quot;Australia/Currie\&quot; - \&quot;Australia/Darwin\&quot; - \&quot;Australia/Eucla\&quot; - \&quot;Australia/Hobart\&quot; - \&quot;Australia/LHI\&quot; - \&quot;Australia/Lindeman\&quot; - \&quot;Australia/Lord_Howe\&quot; - \&quot;Australia/Melbourne\&quot; - \&quot;Australia/NSW\&quot; - \&quot;Australia/North\&quot; - \&quot;Australia/Perth\&quot; - \&quot;Australia/Queensland\&quot; - \&quot;Australia/South\&quot; - \&quot;Australia/Sydney\&quot; - \&quot;Australia/Tasmania\&quot; - \&quot;Australia/Victoria\&quot; - \&quot;Australia/West\&quot; - \&quot;Australia/Yancowinna\&quot; - \&quot;Brazil/Acre\&quot; - \&quot;Brazil/DeNoronha\&quot; - \&quot;Brazil/East\&quot; - \&quot;Brazil/West\&quot; - \&quot;CET\&quot; - \&quot;CST6CDT\&quot; - \&quot;Canada/Atlantic\&quot; - \&quot;Canada/Central\&quot; - \&quot;Canada/East-Saskatchewan\&quot; - \&quot;Canada/Eastern\&quot; - \&quot;Canada/Mountain\&quot; - \&quot;Canada/Newfoundland\&quot; - \&quot;Canada/Pacific,\&quot; - \&quot;Canada/Saskatchewan\&quot; - \&quot;Canada/Yukon,\&quot; - \&quot;Chile/Continental\&quot; - \&quot;Chile/EasterIsland\&quot; - \&quot;Cuba\&quot; - \&quot;EET\&quot; - \&quot;EST5EDT\&quot; - \&quot;Egypt\&quot; - \&quot;Eire\&quot; - \&quot;Etc/GMT\&quot; - \&quot;Etc/GMT+0\&quot; - \&quot;Etc/GMT+1\&quot; - \&quot;Etc/GMT+10\&quot; - \&quot;Etc/GMT+11\&quot; - \&quot;Etc/GMT+12\&quot; - \&quot;Etc/GMT+2\&quot; - \&quot;Etc/GMT+3\&quot; - \&quot;Etc/GMT+4\&quot; - \&quot;Etc/GMT+5\&quot; - \&quot;Etc/GMT+6\&quot; - \&quot;Etc/GMT+7\&quot; - \&quot;Etc/GMT+8,\&quot; - \&quot;Etc/GMT+9\&quot; - \&quot;Etc/GMT-0\&quot; - \&quot;Etc/GMT-1\&quot; - \&quot;Etc/GMT-10\&quot; - \&quot;Etc/GMT-11\&quot; - \&quot;Etc/GMT-12\&quot; - \&quot;Etc/GMT-13\&quot; - \&quot;Etc/GMT-14\&quot; - \&quot;Etc/GMT-2\&quot; - \&quot;Etc/GMT-3\&quot; - \&quot;Etc/GMT-4\&quot; - \&quot;Etc/GMT-5\&quot; - \&quot;Etc/GMT-6\&quot; - \&quot;Etc/GMT-7\&quot; - \&quot;Etc/GMT-8\&quot; - \&quot;Etc/GMT-9\&quot; - \&quot;Etc/GMT0\&quot; - \&quot;Etc/Greenwich\&quot; - \&quot;Etc/UCT\&quot; - \&quot;Etc/UTC\&quot; - \&quot;Etc/Universal\&quot; - \&quot;Etc/Zulu\&quot; - \&quot;Europe/Amsterdam\&quot; - \&quot;Europe/Andorra\&quot; - \&quot;Europe/Astrakhan\&quot; - \&quot;Europe/Athens\&quot; - \&quot;Europe/Belfast\&quot; - \&quot;Europe/Belgrade\&quot; - \&quot;Europe/Berlin\&quot; - \&quot;Europe/Bratislava\&quot; - \&quot;Europe/Brussels\&quot; - \&quot;Europe/Bucharest\&quot; - \&quot;Europe/Budapest\&quot; - \&quot;Europe/Busingen\&quot; - \&quot;Europe/Chisinau\&quot; - \&quot;Europe/Copenhagen\&quot; - \&quot;Europe/Dublin\&quot; - \&quot;Europe/Gibraltar\&quot; - \&quot;Europe/Guernsey\&quot; - \&quot;Europe/Helsinki\&quot; - \&quot;Europe/Isle_of_Man\&quot; - \&quot;Europe/Istanbul\&quot; - \&quot;Europe/Jersey\&quot; - \&quot;Europe/Kaliningrad\&quot; - \&quot;Europe/Kiev\&quot; - \&quot;Europe/Kirov\&quot; - \&quot;Europe/Lisbon\&quot; - \&quot;Europe/Ljubljana\&quot; - \&quot;Europe/London\&quot; - \&quot;Europe/Luxembourg\&quot; - \&quot;Europe/Madrid\&quot; - \&quot;Europe/Malta\&quot; - \&quot;Europe/Mariehamn\&quot; - \&quot;Europe/Minsk\&quot; - \&quot;Europe/Monaco\&quot; - \&quot;Europe/Moscow\&quot; - \&quot;Europe/Nicosia\&quot; - \&quot;Europe/Oslo\&quot; - \&quot;Europe/Paris\&quot; - \&quot;Europe/Podgorica\&quot; - \&quot;Europe/Prague\&quot; - \&quot;Europe/Riga\&quot; - \&quot;Europe/Rome\&quot; - \&quot;Europe/Samara\&quot; - \&quot;Europe/San_Marino\&quot; - \&quot;Europe/Sarajevo\&quot; - \&quot;Europe/Simferopol\&quot; - \&quot;Europe/Skopje\&quot; - \&quot;Europe/Sofia\&quot; - \&quot;Europe/Stockholm\&quot; - \&quot;Europe/Tallinn\&quot; - \&quot;Europe/Tirane\&quot; - \&quot;Europe/Tiraspol\&quot; - \&quot;Europe/Ulyanovsk\&quot; - \&quot;Europe/Uzhgorod\&quot; - \&quot;Europe/Vaduz\&quot; - \&quot;Europe/Vatican\&quot; - \&quot;Europe/Vienna\&quot; - \&quot;Europe/Vilnius\&quot; - \&quot;Europe/Volgograd\&quot; - \&quot;Europe/Warsaw\&quot; - \&quot;Europe/Zagreb\&quot; - \&quot;Europe/Zaporozhye\&quot; - \&quot;Europe/Zurich\&quot; - \&quot;GB\&quot; - \&quot;GB-Eire\&quot; - \&quot;GMT\&quot; - \&quot;GMT0\&quot; - \&quot;Greenwich\&quot; - \&quot;Hongkong\&quot; - \&quot;Iceland\&quot; - \&quot;Indian/Antananarivo\&quot; - \&quot;Indian/Chagos\&quot; - \&quot;Indian/Christmas\&quot; - \&quot;Indian/Cocos\&quot; - \&quot;Indian/Comoro\&quot; - \&quot;Indian/Kerguelen\&quot; - \&quot;Indian/Mahe\&quot; - \&quot;Indian/Maldives\&quot; - \&quot;Indian/Mauritius\&quot; - \&quot;Indian/Mayotte\&quot; - \&quot;Indian/Reunion\&quot; - \&quot;Iran\&quot; - \&quot;Israel\&quot; - \&quot;Jamaica\&quot; - \&quot;Japan\&quot; - \&quot;Kwajalein\&quot; - \&quot;Libya\&quot; - \&quot;MET\&quot; - \&quot;MST7MDT\&quot; - \&quot;Mexico/BajaNorte,\&quot; - \&quot;Mexico/BajaSur\&quot; - \&quot;Mexico/General\&quot; - \&quot;NZ\&quot; - \&quot;NZ-CHAT\&quot; - \&quot;Navajo\&quot; - \&quot;PRC\&quot; - \&quot;PST8PDT,\&quot; - \&quot;Pacific/Apia\&quot; - \&quot;Pacific/Auckland\&quot; - \&quot;Pacific/Bougainville\&quot; - \&quot;Pacific/Chatham\&quot; - \&quot;Pacific/Chuuk\&quot; - \&quot;Pacific/Easter\&quot; - \&quot;Pacific/Efate\&quot; - \&quot;Pacific/Enderbury\&quot; - \&quot;Pacific/Fakaofo\&quot; - \&quot;Pacific/Fiji\&quot; - \&quot;Pacific/Funafuti\&quot; - \&quot;Pacific/Galapagos\&quot; - \&quot;Pacific/Gambier\&quot; - \&quot;Pacific/Guadalcanal\&quot; - \&quot;Pacific/Guam\&quot; - \&quot;Pacific/Honolulu\&quot; - \&quot;Pacific/Johnston\&quot; - \&quot;Pacific/Kiritimati\&quot; - \&quot;Pacific/Kosrae\&quot; - \&quot;Pacific/Kwajalein\&quot; - \&quot;Pacific/Majuro\&quot; - \&quot;Pacific/Marquesas\&quot; - \&quot;Pacific/Midway\&quot; - \&quot;Pacific/Nauru\&quot; - \&quot;Pacific/Niue\&quot; - \&quot;Pacific/Norfolk\&quot; - \&quot;Pacific/Noumea\&quot; - \&quot;Pacific/Pago_Pago\&quot; - \&quot;Pacific/Palau\&quot; - \&quot;Pacific/Pitcairn,\&quot; - \&quot;Pacific/Pohnpei\&quot; - \&quot;Pacific/Ponape\&quot; - \&quot;Pacific/Port_Moresby\&quot; - \&quot;Pacific/Rarotonga\&quot; - \&quot;Pacific/Saipan\&quot; - \&quot;Pacific/Samoa\&quot; - \&quot;Pacific/Tahiti\&quot; - \&quot;Pacific/Tarawa\&quot; - \&quot;Pacific/Tongatapu\&quot; - \&quot;Pacific/Truk\&quot; - \&quot;Pacific/Wake\&quot; - \&quot;Pacific/Wallis\&quot; - \&quot;Pacific/Yap\&quot; - \&quot;Poland\&quot; - \&quot;Portugal\&quot; - \&quot;ROK\&quot; - \&quot;Singapore\&quot; - \&quot;SystemV/AST4\&quot; - \&quot;SystemV/AST4ADT\&quot; - \&quot;SystemV/CST6\&quot; - \&quot;SystemV/CST6CDT\&quot; - \&quot;SystemV/EST5\&quot; - \&quot;SystemV/EST5EDT\&quot; - \&quot;SystemV/HST10\&quot; - \&quot;SystemV/MST7\&quot; - \&quot;SystemV/MST7MDT\&quot; - \&quot;SystemV/PST8,\&quot; - \&quot;SystemV/PST8PDT,\&quot; - \&quot;SystemV/YST9\&quot; - \&quot;SystemV/YST9YDT\&quot; - \&quot;Turkey\&quot; - \&quot;UCT\&quot; - \&quot;US/Alaska\&quot; - \&quot;US/Aleutian\&quot; - \&quot;US/Arizona\&quot; - \&quot;US/Central\&quot; - \&quot;US/East-Indiana\&quot; - \&quot;US/Eastern\&quot; - \&quot;US/Hawaii\&quot; - \&quot;US/Indiana-Starke\&quot; - \&quot;US/Michigan\&quot; - \&quot;US/Mountain\&quot; - \&quot;US/Pacific,\&quot; - \&quot;US/Pacific-New,\&quot; - \&quot;US/Samoa\&quot; - \&quot;UTC\&quot; - \&quot;Universal\&quot; - \&quot;W-SU\&quot; - \&quot;WET\&quot; - \&quot;Zulu\&quot; - \&quot;EST\&quot; - \&quot;HST\&quot; - \&quot;MST\&quot; - \&quot;ACT\&quot; - \&quot;AET\&quot; - \&quot;AGT\&quot; - \&quot;ART\&quot; - \&quot;AST\&quot; - \&quot;BET\&quot; - \&quot;BST\&quot; - \&quot;CAT\&quot; - \&quot;CNT\&quot; - \&quot;CST\&quot; - \&quot;CTT\&quot; - \&quot;EAT\&quot; - \&quot;ECT\&quot; - \&quot;IET\&quot; - \&quot;IST\&quot; - \&quot;JST\&quot; - \&quot;MIT\&quot; - \&quot;NET\&quot; - \&quot;NST\&quot; - \&quot;PLT\&quot; - \&quot;PNT\&quot; - \&quot;PRT\&quot; - \&quot;PST\&quot; - \&quot;SST\&quot; - \&quot;VST\&quot; |

### Return type

void (empty response body)

### Authorization

[apiHash](../../README.md#apiHash), [apiId](../../README.md#apiId)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **entityFind**
> \Swagger\Client\Model\AuthResultClient[] entityFind($entity_parent_id, $reg_exp, $ext_id)



Find a list of entities under a root entity. Return all entities that match  by regular expresion, or exact match of externalId.

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

$api_instance = new Swagger\Client\Api\EntityApi();
$entity_parent_id = 56; // int | The parent entity id.
$reg_exp = "reg_exp_example"; // string | Regular expression that is used to search for the entity name among all the entities to which we have access.
$ext_id = "ext_id_example"; // string | External unique id of account, for 3rd party integrations.

try {
    $result = $api_instance->entityFind($entity_parent_id, $reg_exp, $ext_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling EntityApi->entityFind: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **entity_parent_id** | **int**| The parent entity id. |
 **reg_exp** | **string**| Regular expression that is used to search for the entity name among all the entities to which we have access. |
 **ext_id** | **string**| External unique id of account, for 3rd party integrations. | [optional]

### Return type

[**\Swagger\Client\Model\AuthResultClient[]**](../Model/AuthResultClient.md)

### Authorization

[apiHash](../../README.md#apiHash), [apiId](../../README.md#apiId)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **entityFindById**
> \Swagger\Client\Model\AuthResultClient entityFindById($entity_parent_id, $entity_id)



Finds an entity by unique global Id, under restriction of being under root entity Id.

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

$api_instance = new Swagger\Client\Api\EntityApi();
$entity_parent_id = 56; // int | The parent entity id.
$entity_id = 56; // int | The id of the entity.

try {
    $result = $api_instance->entityFindById($entity_parent_id, $entity_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling EntityApi->entityFindById: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **entity_parent_id** | **int**| The parent entity id. |
 **entity_id** | **int**| The id of the entity. |

### Return type

[**\Swagger\Client\Model\AuthResultClient**](../Model/AuthResultClient.md)

### Authorization

[apiHash](../../README.md#apiHash), [apiId](../../README.md#apiId)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

