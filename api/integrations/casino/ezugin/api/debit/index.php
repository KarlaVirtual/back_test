<?php

/**
 * Este archivo contiene un script para procesar solicitudes de débito en la API del casino 'ezugi'.
 *
 * @category   Casino
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2017-10-18
 * @access     público
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var string $URI            URI de la solicitud actual.
 * @var string $body           Cuerpo de la solicitud HTTP recibido en formato JSON.
 * @var object $data           Objeto decodificado del cuerpo de la solicitud.
 * @var string $operatorId     Identificador del operador.
 * @var string $token          Token de autenticación.
 * @var string $gameId         Identificador del juego.
 * @var string $uid            Identificador único del usuario.
 * @var string $betTypeID      Identificador del tipo de apuesta.
 * @var string $currency       Moneda utilizada en la transacción.
 * @var float  $debitAmount    Monto a debitar.
 * @var string $serverId       Identificador del servidor.
 * @var string $roundId        Identificador de la ronda de juego.
 * @var string $seatId         Identificador del asiento.
 * @var string $transactionId  Identificador de la transacción.
 * @var string $hash           Hash de seguridad para validar la solicitud.
 * @var string $gameDataString Cadena de datos del juego.
 */

require(__DIR__ . '../../../../../../vendor/autoload.php');

use Backend\integrations\casino\Ezugin;


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
    $debitAmount = $data->debitAmount;
    $serverId = $data->serverId;
    $roundId = $data->roundId;
    $seatId = $data->seatId;
    $transactionId = $data->transactionId;
    $hash = $data->hash;
    $gameDataString = $data->gameDataString;

    $Ezugin = new Ezugin($operatorId, $token);

    $gameId = $gameId;
    $uid = $uid;
    $betTypeID = $betTypeID;
    $currency = $currency;
    $debitAmount = $debitAmount;
    $serverId = $serverId;
    $roundId = $roundId;
    $transactionId = $transactionId;
    $hash = $hash;


    print_r($Ezugin->Debit($gameId, $uid, $betTypeID, $currency, $debitAmount, $serverId, $roundId, $transactionId, $hash));
}
