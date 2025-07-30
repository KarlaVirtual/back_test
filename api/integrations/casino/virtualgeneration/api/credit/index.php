<?php
/**
 * Este archivo contiene un script para procesar y manejar solicitudes de crédito
 * en la integración del casino 'virtualgeneration'.
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
 * @var string  $body           Cadena JSON que contiene los datos de la solicitud de crédito.
 * @var object  $data           Objeto decodificado desde la cadena JSON que contiene los datos procesados.
 * @var string  $operatorId     Identificador del operador.
 * @var string  $token          Token de autenticación para la solicitud.
 * @var string  $gameId         Identificador del juego.
 * @var string  $uid            Identificador único del usuario.
 * @var string  $betTypeID      Identificador del tipo de apuesta.
 * @var string  $currency       Moneda utilizada en la transacción.
 * @var float   $creditAmount   Monto de crédito solicitado.
 * @var integer $serverId       Identificador del servidor.
 * @var string  $roundId        Identificador de la ronda de juego.
 * @var string  $transactionId  Identificador de la transacción.
 * @var string  $hash           Hash de seguridad para validar la solicitud.
 * @var string  $gameDataString Cadena de datos adicionales del juego.
 * @var boolean $isEndRound     Indica si la ronda ha finalizado.
 * @var string  $creditIndex    Índice de crédito utilizado en la solicitud.
 */

require(__DIR__ . '../../../../../../vendor/autoload.php');

use Backend\integrations\casino\Ezugi;

/* Procesamos */

$body = '{"uid":"12345","transactionId":"13","operatorId":"10178001","gameId":"1","currency":"USD","gameDataString":"","token":"13346577889273645364","creditAmount":1,"betTypeID":"1","serverId":102,"roundId":"12345","seatId":"","returnReason":0,"creditIndex":"1|1","isEndRound":"false"}';
if ($body != "") {
    $data = json_decode($body);
}


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


/* Procesamos */

$Ezugi = new Ezugi($operatorId, $token);

print_r($Ezugi->Credit($gameId, $uid, $betTypeID, $currency, $creditAmount, $serverId, $roundId, $transactionId, $gameDataString, $isEndRound, $creditIndex, $hash));




