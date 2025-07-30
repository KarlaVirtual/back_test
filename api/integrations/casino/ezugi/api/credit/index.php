<?php

/**
 * Este archivo contiene un script para procesar solicitudes relacionadas con la API de casino 'ezugi' en modo crédito.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-09
 * @access     público
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var string  $URI            URI de la solicitud actual.
 * @var string  $body           Cuerpo de la solicitud HTTP recibido en formato JSON.
 * @var object  $data           Objeto decodificado del cuerpo de la solicitud.
 * @var string  $operatorId     Identificador del operador.
 * @var string  $token          Token de autenticación del operador.
 * @var string  $gameId         Identificador del juego.
 * @var string  $uid            Identificador único del usuario.
 * @var string  $betTypeID      Identificador del tipo de apuesta.
 * @var string  $currency       Moneda utilizada en la transacción.
 * @var float   $creditAmount   Monto de crédito involucrado en la transacción.
 * @var string  $serverId       Identificador del servidor.
 * @var string  $roundId        Identificador de la ronda de juego.
 * @var string  $transactionId  Identificador único de la transacción.
 * @var string  $hash           Hash de seguridad para validar la solicitud.
 * @var string  $gameDataString Cadena de datos del juego.
 * @var boolean $isEndRound     Indica si la ronda ha finalizado.
 * @var integer $creditIndex    Índice del crédito en la transacción.
 */

require(__DIR__ . '../../../../../../vendor/autoload.php');

use Backend\integrations\casino\Ezugi;


/* Procesamos */

$URI = $_SERVER['REQUEST_URI'];
$body = file_get_contents('php://input');

if ($body != "") {
    $data = json_decode($body);

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
}