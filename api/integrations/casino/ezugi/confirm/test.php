<?php

/**
 * Este archivo contiene un script para procesar y confirmar transacciones de casino
 * en la integración con el proveedor 'Ezugi'.
 *
 * @category   Casino
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var string  $body           Contiene el cuerpo de la solicitud HTTP recibida en formato JSON.
 * @var object  $data           Objeto decodificado del cuerpo de la solicitud, que contiene los datos de la transacción.
 * @var string  $operatorId     Identificador del operador que realiza la solicitud.
 * @var string  $token          Token de autenticación proporcionado por el operador.
 * @var string  $gameId         Identificador del juego asociado a la transacción.
 * @var string  $uid            Identificador único del usuario.
 * @var string  $betTypeID      Identificador del tipo de apuesta realizada.
 * @var string  $currency       Moneda utilizada en la transacción.
 * @var float   $creditAmount   Monto de crédito involucrado en la transacción.
 * @var string  $serverId       Identificador del servidor que procesa la transacción.
 * @var string  $roundId        Identificador de la ronda de juego.
 * @var string  $transactionId  Identificador único de la transacción.
 * @var string  $hash           Hash de seguridad para validar la transacción.
 * @var string  $gameDataString Cadena de datos específicos del juego.
 * @var boolean $isEndRound     Indica si la transacción corresponde al final de una ronda.
 * @var integer $creditIndex    Índice del crédito utilizado en la transacción.
 * @var integer $seatId         Identificador del asiento del jugador.
 */

error_reporting(E_ALL);
ini_set('display_errors', 'ON');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Ezugi;

$body = file_get_contents('php://input');
if ($body != "") {
    $data = json_decode($body);
}


/* Procesamos */


$operatorId = $data->operatorId;
$token = $data->token;


$gameId = $data->gameId;
$uid = $data->uid;
$betTypeID = $data->betTypeID;
$currency = $data->currency;
$creditAmount = $data->creditAmount;
$serverId = $data->serverId;
$roundId = $data->roundId;
$transactionId = $data->transactionId;
$hash = $data->hash;
$gameDataString = $data->gameDataString;
$isEndRound = $data->isEndRound;
$creditIndex = $data->creditIndex;
$seatId = $data->seatId;


/* Procesamos */

print_r($betTypeID);
print_r($data);
print_r("TEST");

$Ezugi = new Ezugi($operatorId, $token, $uid);

print_r($Ezugi->Credit($gameId, $uid, $betTypeID, $currency, $creditAmount, $serverId, $roundId, $transactionId, $seatId, $gameDataString, $isEndRound, $creditIndex, $hash));
