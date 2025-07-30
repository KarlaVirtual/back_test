<?php
/**
 * Este archivo contiene un script para procesar y generar un informe de cuotas totales
 * basado en datos de usuarios, transacciones y actividades relacionadas.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $body           Contiene el cuerpo de la solicitud HTTP recibida.
 * @var mixed $data           Almacena los datos decodificados del cuerpo de la solicitud.
 * @var mixed $operatorId     Identificador del operador.
 * @var mixed $token          Token de autenticación.
 * @var mixed $gameId         Identificador del juego.
 * @var mixed $uid            Identificador único del usuario.
 * @var mixed $betTypeID      Identificador del tipo de apuesta.
 * @var mixed $currency       Moneda utilizada en la transacción.
 * @var mixed $creditAmount   Monto de crédito involucrado en la transacción.
 * @var mixed $serverId       Identificador del servidor.
 * @var mixed $roundId        Identificador de la ronda de juego.
 * @var mixed $transactionId  Identificador de la transacción.
 * @var mixed $hash           Hash de seguridad para validar la transacción.
 * @var mixed $gameDataString Cadena de datos del juego.
 * @var mixed $isEndRound     Indica si la ronda ha finalizado.
 * @var mixed $creditIndex    Índice del crédito.
 * @var mixed $seatId         Identificador del asiento.
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
