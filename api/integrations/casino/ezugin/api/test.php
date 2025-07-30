<?php

/**
 * Este archivo contiene un script para probar la API del casino 'Ezugi'.
 *
 * @category   Casino
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 * @access     público
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var string  $test          JSON que contiene datos de prueba para la API.
 * @var object  $data          Objeto decodificado del JSON de prueba.
 * @var string  $operatorId    Identificador del operador.
 * @var string  $token         Token de autenticación.
 * @var string  $gameId        Identificador del juego.
 * @var string  $uid           Identificador único del usuario.
 * @var string  $betTypeID     Identificador del tipo de apuesta.
 * @var string  $currency      Moneda utilizada en la transacción.
 * @var string  $debitAmount   Monto a debitar en la operación.
 * @var integer $serverId      Identificador del servidor.
 * @var string  $roundId       Identificador de la ronda.
 * @var string  $transactionId Identificador de la transacción.
 * @var string  $hash          Hash de seguridad para la operación.
 * @var string  $seatId        Identificador del asiento.
 */
error_reporting(E_ALL);
ini_set('display_errors', 'ON');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Ezugin;

$test = '{"uid":"1","rollbackAmount":"10","transactionId":"2551","operatorId":"10178001","token":"13346577889273645363","gameId":"1","serverId":102,"roundId":"66701","currency":"USD","seatId":"1","betTypeID":"1"}';
$data = json_decode($test);

$operatorId = $data->operatorId;
$token = $data->token;


$gameId = $data->gameId;
$uid = $data->uid;
$betTypeID = $data->betTypeID;
$currency = $data->currency;
$debitAmount = $data->rollbackAmount;
$serverId = $data->serverId;
$roundId = $data->roundId;
$transactionId = $data->transactionId;
$hash = $data->hash;
$seatId = $data->seatId;

/* Procesamos */

$Ezugin = new Ezugin($operatorId, $token);

print_r($Ezugin->Rollback($gameId, $uid, $betTypeID, $currency, $debitAmount, $serverId, $roundId, $transactionId, $seatId, $hash));
