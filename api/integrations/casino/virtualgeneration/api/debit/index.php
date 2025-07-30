<?php
/**
 * Este archivo contiene un script para procesar solicitudes de débito en la API de casino 'virtualgeneration'.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var string  $URI                Contiene la URI de la solicitud actual.
 * @var string  $body               Contiene el cuerpo de la solicitud HTTP en formato JSON.
 * @var object  $data               Objeto decodificado del cuerpo de la solicitud.
 * @var string  $operatorId         Identificador del operador recibido en la solicitud.
 * @var string  $token              Token de autenticación recibido en la solicitud.
 * @var float   $Amount             Monto de la transacción.
 * @var integer $TransactionTypeId  Identificador del tipo de transacción.
 * @var string  $TransactionMessage Mensaje asociado a la transacción.
 * @var string  $UserId             Identificador del usuario.
 * @var string  $Username           Nombre de usuario.
 * @var string  $Currency           Moneda utilizada en la transacción.
 */

require(__DIR__ . '../../../../../../vendor/autoload.php');

use Backend\integrations\casino\VirtualGeneration;

/* Procesamos */

$URI = $_SERVER['REQUEST_URI'];
$body = file_get_contents('php://input');
if ($body != "") {
    $data = json_decode($body);

    $operatorId = $data->operatorId;
    $token = $data->token;


    $Amount = $data->Amount;
    $TransactionTypeId = $data->TransactionTypeId;
    $TransactionMessage = $data->TransactionMessage;
    $UserId = $data->UserId;
    $Username = $data->Username;
    $Currency = $data->Currency;

    /* Procesamos */

    $VirtualGeneration = new VirtualGeneration($operatorId, $username);

    print_r($Ezugi->Debit($gameId, $uid, $betTypeID, $currency, $debitAmount, $serverId, $roundId, $transactionId, $seatId, $hash));
}



