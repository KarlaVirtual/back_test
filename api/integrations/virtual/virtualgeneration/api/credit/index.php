<?php

/**
 * Este archivo maneja la integración con la plataforma Ezugi para realizar operaciones de crédito.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $body           Esta variable contiene el contenido del cuerpo de la solicitud HTTP.
 * @var mixed $data           Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $operatorId     Variable que almacena el identificador del operador.
 * @var mixed $token          Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $gameId         Variable que almacena el identificador de un juego.
 * @var mixed $uid            Variable que almacena el identificador único de un usuario.
 * @var mixed $betTypeID      Variable que almacena el identificador del tipo de apuesta.
 * @var mixed $currency       Variable que almacena la moneda utilizada en una transacción.
 * @var mixed $creditAmount   Variable que almacena el monto del crédito.
 * @var mixed $serverId       Variable que almacena el identificador del servidor.
 * @var mixed $roundId        Variable que almacena el identificador de la ronda.
 * @var mixed $transactionId  Variable que almacena el identificador único de una transacción.
 * @var mixed $hash           Variable que almacena un valor hash para seguridad o verificación.
 * @var mixed $gameDataString Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $isEndRound     Variable que indica si la ronda ha finalizado.
 * @var mixed $creditIndex    Variable que almacena el índice de crédito.
 * @var mixed $Ezugi          Variable que almacena información relacionada con la plataforma Ezugi.
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




