<?php

/**
 * Archivo de idioma en alemán para mensajes de error y validación relacionados con pagos.
 *
 * Este archivo contiene una lista de mensajes de error y validación utilizados
 * en el contexto de la integración de pagos con Paysafecard.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payment\Paysafecard
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variable de idioma para mensajes de error y validación.
 *
 * @var mixed $_ Esta variable se utiliza para almacenar y manipular el valor de '_' en el contexto actual.
 */

$_['username_empty'] = 'Benutzername ist leer!';
$_['username_length'] = 'Der Benutzername muss mehr als 3 Zeichen haben.';
$_['password_empty'] = 'Das Passwort ist leer!';
$_['passwor_length'] = 'Das Passwort muss mehr als 5 Zeichen haben.';
$_['invalid_client_id'] = 'Die angegebene Merchant-Client-ID ist ungültig. Die Merchant-Client-ID muss zwischen 1 und 50 Zeichen lang sein. Erlaubte Zeichen: AZ, az, 0-9 sowie - (Bindestrich) und _ (Unterstrich).';
$_['mtid_oversize'] = 'Mtid ist ungültig. Mtid darf nicht mehr als 60 Zeichen haben.';
$_['mtid_undersize'] = 'Mtid ist ungültig. Mtid muss mehr als 20 Zeichen haben.';
$_['mtid_invalid'] = 'Mtid ist ungültig. Erlaubte Zeichen A-Z, a-z, 0-9 und – (Bindestrich) und _ (Unterstrich).';
$_['error_validation_value'] = 'Fehler bei der Validierung. Es wurde kein Wert zur Validierung übergeben!';
$_['error_validation_type'] = 'Fehler bei der Validierung. Es wurde kein gültiger Typ zur Validierung angegeben!';
$_['error_validation'] = 'Fehler bei der Validierung.';
$_['invalide_status'] = 'Ungültiger Status. Der Status kann "test" order "live" sein.';
$_['no_status'] = 'Es wurde keine Status angegeben.';
$_['wrong_currency'] = 'Ungültige Währung. Die Währung muss 3 Zeichen lange sein (ISO 4217).';
$_['wrong_currency_case'] = 'Ungültige Währung. Die Währung darf nur in Großbuchstaben angegeben werden.';
$_['dot_amount'] = 'Der Betrag muss mit einem Punkt getrennt werden.';
$_['wrong_amount'] = 'Der Betrag ist nicht vollständig oder richtig formatiert';
$_['empty_amount'] = 'Es wurde kein Betrag angegeben';
$_['empty_customer_id_type'] = 'CustomerIdType ist leer oder nicht gesetzt';
$_['invalide_customer_id_type'] = 'CustomerIdType ist ungültig';
$_['customer_id_oversize'] = 'CustomerID hat mehr als 90 Zeichen';
$_['customer_id_undersize'] = 'CustomerIdType hat weniger als 5 Zeichen oder ist leer';
$_['create_payout_missing_parameter'] = 'Der Parameter "%s" fehlt oder ist leer';