<?php

/**
 * Archivo de configuración de mensajes de error y validación para el módulo de integración de pagos Paysafecard.
 *
 * Este archivo contiene una lista de mensajes predefinidos que se utilizan para manejar errores, validaciones
 * y notificaciones en el contexto del módulo de pagos. Los mensajes están organizados en un arreglo asociativo
 * para facilitar su uso en diferentes partes del sistema.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables de error y validación para el módulo de integración de pagos Paysafecard.
 *
 * @var mixed $_ Esta variable se utiliza para almacenar y manipular el valor de '_' en el contexto actual.
 */

$_['username_empty'] = 'Username is empty!';
$_['username_length'] = 'The username must have more than 3 characters.';
$_['password_empty'] = 'Password is empty!';
$_['passwor_length'] = 'The password must have more than 5 characters.';
$_['invalid_client_id'] = 'The specified Merchant-Client-ID is invalid. The Merchant-Client-ID must be between 1 and 20 characters. Only the following is allowed A-Z, a-z, 0-9 as well as – (hypen) and _ (underline).';
$_['no_auto_correct'] = 'No auto-correct specified.';
$_['no_sys_lang'] = 'No system language specified.';
$_['no_debug'] = 'No debug-status specified.';
$_['shopid_oversize'] = 'ShopID is invalid. ShopID maximum length is 60 characters.';
$_['shopid_undersize'] = 'ShopID is invalid. ShopID must have at least 20 characters.';
$_['shopid_invalid'] = 'ShopID is invalid. Only the following is allowed A-Z, a-z, 0-9 as well as – (hypen) and _ (underline).';
$_['shoplabel_oversize'] = 'Shoplabel is invalid. Shoplabel maximum length is 60 characters.';
$_['shoplabel_undersize'] = 'Shoplabel is invalid. Shoplabel must have at least 1 characters.';
$_['shoplabel_invalid'] = 'Shoplabel is invalid. Only the following is allowed A-Z, a-z, 0-9 as well as – (hypen), _ (underline) and spaces.';
$_['mtid_oversize'] = 'Mtid is invalid. Mtid maximum length is 60 characters.';
$_['mtid_undersize'] = 'Mtid is invalid. Mtid must have at least 1 characters.';
$_['mtid_invalid'] = 'Mtid is invalid. Only the following is allowed A-Z, a-z, 0-9 as well as – (hypen), _ (underline) and spaces.';
$_['error_validation_value'] = 'Validation errors. There was no value is passed to the validation!';
$_['error_validation_type'] = 'Validation errors. It was not specified a valid type for the validation!';
$_['error_validation'] = 'Validation errors.';
$_['min_age_invalide'] = 'Invalid restricted age. The age must be a positive numbervalue.';
$_['min_kyc_level_invalide'] = 'Invalid restricted level. The level must be SIMPLE or FULL.';
$_['restricted_country_invalide'] = 'Invalid restricted country. 2 characters required. The value accepts ISO 3166-1 country codes.';
$_['restricted_country_case'] = 'Invalid restricted country. There are only capital letters allowed. The value accepts ISO 3166-1 country codes.';
$_['invalide_status'] = 'Invalid module status. Status can only be "live" or "test".';
$_['no_status'] = 'It was not specified a status.';
$_['create_disp_is_error'] = 'create disposition was aborted. Please remove all errors.';
$_['execute_debit_is_error'] = 'executeDebit was aborted. Resolve all errors before proceeding.';
$_['execute_debit_error'] = 'executeDebit was not successful.';
$_['wrong_currency'] = 'Invalid currency. The currency must be 3 characters long (ISO 4217).';
$_['wrong_currency_case'] = 'Invalid currency. The currency may only be specified in uppercase.';
$_['dot_amount'] = 'The amount must be separated with a dot.';
$_['invalid_nok_url'] = 'Specified nok-URL is invalid!';
$_['invalid_ok_url'] = 'Specified ok-URL is invalid!';
$_['invalid_pn_url'] = 'Specified pn-URL is invalid!';
$_['auto_correct_set_pn_url'] = 'Specified pn-URL was corrected with AutoCorrect. Please revise entry!';
$_['auto_correct_set_nok_url'] = 'Specified nok-URL was corrected with AutoCorrect. Please revise entry!';
$_['auto_correct_set_ok_url'] = 'Specified ok-URL was corrected with AutoCorrect. Please revise entry!';
$_['auto_correct_res_country'] = 'Country restrictions entry was corrected with AutoCorrect. Please revise entry!';
$_['auto_correct_ammount_warning'] = 'Specified amount was corrected with AutoCorrect. The specified entry does not have the required formatting!';
$_['get_serial_num_is_error'] = 'getSerialNumbers was aborted. Resolve all errors before proceeding.';
$_['error_get_serial_num'] = 'getSerialNumbers was not executed successfully!';
$_['error_create_disposition'] = 'createDisposition was not executed successfully!';
$_['msg_create_disposition'] = 'Transaction could not be initiated due to connection problems. If the problem persists, please contact our support.';
$_['msg_execute_debit'] = 'Payment could not be completed. There is a temporary connection problem. Please press the "reload" button in your browser or click the following link to complete payment. <a href="%s">Reload</a> <br> 
If this issue persists, please contact Support On the paysafecard credit overview (https://customer.cc.at.paysafecard.com/psccustomer/GetWelcomePanelServlet?language=de) find out when the reserved amount is released again.';
$_['payment_invalide'] = 'Failed to complete the payment transaction properly';
$_['payment_cancelled'] = 'Payment transaction was aborted at your request';
$_['payment_expired'] = 'Timeout. Please restart the payment transaction.';
$_['payment_unknown_error'] = 'Unknown error during payment. Please restart the payment transaction. If this issue persists, please contact Support';
$_['payment_done'] = 'Payment transaction was completed successfully.';