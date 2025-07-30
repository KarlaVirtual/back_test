<?php
/**
 * Este archivo contiene un script para interactuar con la API de casino 'join',
 * permitiendo realizar operaciones como obtener el balance de un usuario,
 * listar juegos, realizar depósitos, entre otros.
 *
 * @category   Integración
 * @package    API
 * @subpackage Casino
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 */

error_reporting(E_ALL);
ini_set('display_errors', 'ON');
require(__DIR__ . '../../../../../vendor/autoload.php');
header('Content-type: application/xml');

use Backend\integrations\casino\JOINSERVICES;

$JOINSERVICES = new JOINSERVICES();

$response = ($JOINSERVICES->getBalance2(1, 5));
$insertXML = new SimpleXMLElement($response);
print_r($response);

if ($insertXML->RESPONSE->RESULT != "KO") {
}
