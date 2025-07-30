<?php

/**
 * Este archivo contiene un script para procesar y manejar las solicitudes de la API de casino 'iesgames'.
 * Proporciona funcionalidades como autenticación, consulta de saldo, débitos, créditos, y más.
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
 * @var mixed $_SERVER                  Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $_ENV                     Variable superglobal que contiene variables de entorno configuradas.
 * @var mixed $_REQUEST                 Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $log                      Variable que almacena información de registro para depuración.
 * @var mixed $body                     Contenido del cuerpo de la solicitud HTTP recibido.
 * @var mixed $data                     Datos decodificados del cuerpo de la solicitud en formato JSON.
 * @var mixed $URI                      URI de la solicitud actual.
 * @var mixed $headers                  Encabezados HTTP de la solicitud actual.
 * @var mixed $ConfigurationEnvironment Objeto que maneja la configuración del entorno (desarrollo o producción).
 * @var mixed $Token                    Token de autenticación utilizado en las solicitudes.
 * @var mixed $UserId                   Identificador del usuario autenticado.
 * @var mixed $User                     Nombre de usuario enviado en la solicitud.
 * @var mixed $Pass                     Contraseña enviada en la solicitud.
 * @var mixed $response                 Respuesta generada por las operaciones realizadas.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\IESGAMES;

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

header('Content-type: application/json; charset=utf-8');

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';

$body = file_get_contents('php://input');
$body = preg_replace("[\n|\r|\n\r]", "", $body);
$data = json_decode($body);
$URI = $_SERVER['REQUEST_URI'];

if ($body != "") {

    if (strpos($URI, "authenticate") !== false) {

        $token = explode(".", "$data->token");
        $token = base64_decode($token[1]);
        $json = json_decode($token);
        $user = explode(":", "$json->auth");
        $UserId = $user[1];
        $Token = "";
        $User = $data->user;
        $Pass = $data->pass;

        $sign = $_REQUEST["sign"];

        /* Procesamos */
        $IESGAMES = new IESGAMES($Token = "", $UserId);
        $response = $IESGAMES->Auth();
    }

    if (strpos($URI, "getbalance") !== false) {
        $token = explode(".", "$data->token");
        $token = base64_decode($token[1]);
        $json = json_decode($token);

        $user = explode(":", "$json->auth");
        $UserId = $user[1];
        $Token = "";
        $User = $data->User;
        $Pass = $data->Pass;
        /* Procesamos */
        $IESGAMES = new IESGAMES($Token = "", $UserId);
        $response = $IESGAMES->getBalance();
    }

    if (strpos($URI, "debitandcreditcode") !== false) {

        $token = explode(".", "$data->token");
        $token = base64_decode($token[1]);
        $json = json_decode($token);
        $user = explode(":", "$json->auth");
        $UserId = $user[1];
        $Token = "";
        $bonuscode = $data->code;
        $GameCode = $data->gameCode;
        $PlayerId = $data->playerId;
        $Currency = $data->currency;
        $IESTransactionId = $data->iesTransactionId;
        $RoundId = $data->roundId;
        $User = $data->user;
        $Pass = $data->pass;

        $freespin = true;
        $datos = $data;
        $Amount = 0;

        /* Procesamos */
        $IESGAMES = new IESGAMES($Token = "", $UserId);
        $response = ($IESGAMES->DebitBono("IESGAMES", $Amount, $GameCode, $IESTransactionId, json_encode($datos), $freespin, $bonuscode));
    }

    if (strpos($URI, "credit") !== false) {

        $token = explode(".", "$data->token");
        $token = base64_decode($token[1]);
        $json = json_decode($token);

        $user = explode(":", "$json->auth");
        $UserId = $user[1];
        $Token = "";
        $WinningPlay = $data->winningPlay;
        $CreditAmount = round($data->creditAmount, 2);
        $GameCode = $data->gameCode;
        $PlayerId = $data->playerId;
        $Currency = $data->currency;
        $IESTransactionId = $data->iesTransactionId;
        $RoundId = $data->roundId;
        $SaleTransactionId = $data->saleTransactionId;
        $User = $data->user;
        $Pass = $data->pass;
        $datos = $data;

        /* Procesamos */
        $IESGAMES = new IESGAMES($Token = "", $UserId);
        $response = $IESGAMES->Credit("IESGAMES", $CreditAmount, $GameCode, $IESTransactionId, json_encode($datos));
    }

    if (strpos($URI, "debit") !== false) {

        $token = explode(".", "$data->token");
        $token = base64_decode($token[1]);
        $json = json_decode($token);

        $user = explode(":", "$json->auth");
        $UserId = $user[1];

        $Token = "";
        $DebitAmount = round($data->debitAmount, 2);
        $GameCode = $data->gameCode;
        $PlayerId = $data->playerId;
        $Currency = $data->currency;
        $IESTransactionId = $data->iesTransactionId;
        $RoundId = $data->roundId;
        $User = $data->user;
        $Pass = $data->pass;

        $freespin = false;
        $datos = $data;

        /* Procesamos */
        $IESGAMES = new IESGAMES($Token = "", $UserId);
        $response = $IESGAMES->Debit("IESGAMES", $DebitAmount, $GameCode, $IESTransactionId, json_encode($datos), $freespin);
    }

    if (strpos($URI, "rollback") !== false) {

        $token = explode(".", "$data->token");
        $token = base64_decode($token[1]);
        $json = json_decode($token);

        $user = explode(":", "$json->auth");
        $RoundId = explode(":", "$json->auth");
        $UserId = $user[1];
        $RoundId = $user[0];

        $IESTransactionId = $data->iesTransactionId;
        $IESRelatedTransactionId = $data->iesRelatedTransactionId;
        $OperatorTransactionId = $data->operatorTransactionId;
        $Token = "";
        $Amount = $data->Amount;
        $PlayerId = $data->PlayerId;
        $User = $data->User;
        $Pass = $data->Pass;

        $datos = $data;

        /* Procesamos */
        $IESGAMES = new IESGAMES($Token = "", $UserId);
        $response = $IESGAMES->Rollback($Amount, $RoundId, $IESRelatedTransactionId, $PlayerId, json_encode($datos));
    }

    if (strpos($URI, "multidebit") !== false) {

        $token = explode(".", "$data->token");
        $token = base64_decode($token[1]);
        $json = json_decode($token);

        $user = explode(":", "$json->auth");
        $UserId = $user[1];

        $Token = "";
        $IESTransactionId = $data->IESTransactionId;
        $IESRelatedTransactionId = $data->IESRelatedTransactionId;
        $OperatorTransactionId = $data->OperatorTransactionId;
        $Amount = $data->Amount;
        $PlayerId = $data->PlayerId;
        $User = $data->User;
        $Pass = $data->Pass;

        $freespin = false;
        $datos = $data;

        /* Procesamos */
        $IESGAMES = new IESGAMES($Token = "", $UserId);
        $response = ($IESGAMES->Debit($GameCode, "", $RoundId, $IESTransactionId, json_encode($datos), $freespin));
    }

    print_r($response);
}
