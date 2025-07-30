<?php
/**
 * Este archivo contiene un script para interactuar con la API de casino 'Microgaming'
 * y realizar diversas operaciones relacionadas con la gestión de juegos y apuestas.
 *
 * @category   Casino
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST            Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $MicrogamingServices Objeto que maneja los servicios de integración con Microgaming.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\MICROGAMINGSERVICES;

$MicrogamingServices = new MICROGAMINGSERVICES();

if ($_REQUEST["request"] == "getQueueRollback") {
    print_r($MicrogamingServices->getQueueRollback());
}

if ($_REQUEST["request"] == "getQueueCommit") {
    print_r($MicrogamingServices->getQueueCommit());
}

if ($_REQUEST["request"] == "getQueueEndGame") {
    print_r($MicrogamingServices->getQueueEndGame());
}

if ($_REQUEST["request"] == "setManuallyValidateBet") {
    print_r($MicrogamingServices->setManuallyValidateBet($_REQUEST["ExternalReference"], $_REQUEST["RowId"], $_REQUEST["UnlockType"], $_REQUEST["UserId"]));
}

if ($_REQUEST["request"] == "setManuallyCompleteGame") {
    print_r($MicrogamingServices->setManuallyCompleteGame($_REQUEST["ExternalReference"], $_REQUEST["RowId"], $_REQUEST["UnlockType"], $_REQUEST["UserId"]));
}

if ($_REQUEST["request"] == "setFreeGames") {
    print_r($MicrogamingServices->setFreeGames());
}