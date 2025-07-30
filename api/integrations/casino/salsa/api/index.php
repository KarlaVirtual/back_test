<?php

/**
 * Este archivo contiene un script para procesar y manejar las solicitudes de la API del casino 'Salsa'.
 * Proporciona diferentes métodos para interactuar con el sistema, como obtener detalles de cuentas,
 * consultar saldos, realizar apuestas, otorgar premios y reembolsar apuestas.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 * @access     publico
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_ENV     Variable que habilita o deshabilita la conexión global ["enabledConnectionGlobal"].
 * @var mixed $URI      Contiene la URI de la solicitud actual.
 * @var mixed $body     Almacena el contenido del cuerpo de la solicitud.
 * @var mixed $method   Método de la API solicitado.
 * @var mixed $data     Datos procesados desde el cuerpo de la solicitud en formato XML.
 * @var mixed $log      Variable utilizada para almacenar información de registro.
 * @var mixed $response Respuesta generada por los métodos de la API.
 */

ini_set('display_errors', 'off');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Salsa;

header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Access-Control-Allow-Headers, Access-Control-Allow-Credentials,Authentication, Origin, X-Requested-With, Content-Type');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS,PUT');

$_ENV["enabledConnectionGlobal"] = 1;

$URI = $_SERVER['REQUEST_URI'] . " C " . $_SERVER['REQUEST_METHOD'];
$body = trim(file_get_contents('php://input'));
$method = "";

if ($body != "") {
    Header('Content-type: text/xml');

    $data = simplexml_load_string($body);
    $method = $data->Method->attributes()->Name;
}
if (true) {

    switch ($method) {

        case "GetAccountDetails":

            $Auth = $data->Method->Auth->attributes();

            $Params = $data->Method->Params;
            $Token = (string)$Params->Token->attributes()->Value;

            $Salsa = new Salsa($Token);

            $response = ($Salsa->Auth());

            break;

        case "GetBalance":

            $Auth = $data->Method->Auth->attributes();

            $Params = $data->Method->Params;
            $Token = $Params->Token->attributes()->Value;

            $Salsa = new Salsa($Token);

            $response = ($Salsa->getBalance());

            break;

        case "PlaceBet":


            $Auth = $data->Method->Auth->attributes();

            $Params = $data->Method->Params;
            $Token = $Params->Token->attributes()->Value;
            $TransactionID = intval($Params->TransactionID->attributes()->Value);
            $BetReferenceNum = intval($Params->BetReferenceNum->attributes()->Value);
            $BetAmount = intval($Params->BetAmount->attributes()->Value);
            $BetAmount = $BetAmount / 100;
            $GameReference = $Params->GameReference->attributes()->Value;

            $Salsa = new Salsa($Token);

            $datos = array(
                "TransactionID" => (string)$TransactionID,
                "BetReferenceNum" => (string)$BetReferenceNum,
                "BetAmount" => (string)$BetAmount,
                "GameReference" => (string)$GameReference
            );

            $response = ($Salsa->Debit($GameReference, $TransactionID, "", $BetAmount, $BetReferenceNum, json_encode($datos)));

            break;


        case "AwardWinnings":

            $Auth = $data->Method->Auth->attributes();

            $Params = $data->Method->Params;
            $Token = $Params->Token->attributes()->Value;
            $TransactionID = intval($Params->TransactionID->attributes()->Value);
            $WinReferenceNum = intval($Params->WinReferenceNum->attributes()->Value);
            $WinAmount = intval($Params->WinAmount->attributes()->Value);
            $WinAmount = $WinAmount / 100;
            $GameReference = $Params->GameReference->attributes()->Value;
            $GameStatus = $Params->GameStatus->attributes()->Value;

            $isEnd = false;

            if ($GameStatus == "Complete") {
                $isEnd = true;
            }

            $Salsa = new Salsa($Token);

            $datos = array(
                "TransactionID" => (string)$TransactionID,
                "WinReferenceNum" => (string)$WinReferenceNum,
                "WinAmount" => (string)$WinAmount,
                "GameReference" => (string)$GameReference,
                "GameStatus" => (string)$GameStatus
            );

            $response = ($Salsa->Credit($GameReference, $TransactionID, "", $WinAmount, $WinReferenceNum, $isEnd, json_encode($datos)));

            break;


        case "RefundBet":

            $Auth = $data->Method->Auth->attributes();

            $Params = $data->Method->Params;
            $Token = $Params->Token->attributes()->Value;
            $TransactionID = intval($Params->TransactionID->attributes()->Value);
            $BetReferenceNum = intval($Params->BetReferenceNum->attributes()->Value);
            $RefundAmount = intval($Params->RefundAmount->attributes()->Value);
            $RefundAmount = $RefundAmount / 100;
            $GameReference = $Params->GameReference->attributes()->Value;


            $Salsa = new Salsa($Token);

            $datos = array(
                "TransactionID" => (string)$TransactionID,
                "BetReferenceNum" => (string)$BetReferenceNum,
                "RefundAmount" => (string)$RefundAmount,
                "GameReference" => (string)$GameReference
            );

            $response = ($Salsa->Rollback($GameReference, $TransactionID, "", $RefundAmount, $BetReferenceNum, json_encode($datos)));
            break;

        default:

            break;
    }
    print_r($response);
}
