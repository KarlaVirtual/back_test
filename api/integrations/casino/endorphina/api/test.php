<?php

/**
 * Este archivo contiene un script para procesar y realizar una operación de rollback
 * en un sistema de casino utilizando la integración con Ezugi.
 *
 * @category   Casino
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var string  $test          Cadena JSON que contiene los datos de prueba para la operación.
 * @var object  $data          Objeto decodificado desde la cadena JSON que contiene los datos de prueba.
 * @var string  $operatorId    Identificador del operador.
 * @var string  $token         Token de autenticación del operador.
 * @var string  $gameId        Identificador del juego.
 * @var string  $uid           Identificador único del usuario.
 * @var string  $betTypeID     Identificador del tipo de apuesta.
 * @var string  $currency      Moneda utilizada en la transacción.
 * @var string  $debitAmount   Monto a debitar en la operación de rollback.
 * @var integer $serverId      Identificador del servidor.
 * @var string  $roundId       Identificador de la ronda de juego.
 * @var string  $transactionId Identificador de la transacción.
 * @var string  $hash          Hash de seguridad para validar la operación.
 * @var string  $seatId        Identificador del asiento del jugador.
 */

error_reporting(E_ALL);
ini_set('display_errors', 'ON');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Ezugi;

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

$Ezugi = new Ezugi($operatorId, $token);

print_r($Ezugi->Rollback($gameId, $uid, $betTypeID, $currency, $debitAmount, $serverId, $roundId, $transactionId, $seatId, $hash));
