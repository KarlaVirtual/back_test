<?php

/**
 * Este archivo contiene la implementación de la API para la integración con el casino 'Platipus'.
 * Procesa solicitudes relacionadas con usuarios, balances, apuestas, reembolsos y giros gratis.
 *
 * @category   Casino
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 * @access     public
 *
 * Variables globales utilizadas en el script:
 *
 * @var array  $_REQUEST     Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var string $URI          URI de la solicitud actual.
 * @var string $body         Cuerpo de la solicitud en formato JSON.
 * @var object $data         Datos decodificados del cuerpo de la solicitud.
 * @var string $log          Variable utilizada para almacenar información de registro.
 * @var string $requestOrder Orden de los parámetros de la solicitud, excluyendo el hash.
 * @var string $hashOriginal Hash original enviado en la solicitud.
 * @var mixed  $response     Respuesta generada por las operaciones realizadas.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Platipus;

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

header('Content-type: application/json');

$_ENV["enabledConnectionGlobal"] = 1;

$URI = $_SERVER['REQUEST_URI'];

$body = json_encode($_REQUEST);

if ($body != "") {
    $data = json_decode($body);
}

$requestOrder = "";
ksort($_REQUEST);
$cont = 0;

foreach ($_REQUEST as $key => $val) {
    if ($key != "hash") {
        if ($cont == 0) {
            $requestOrder .= "$key=$val";
        } else {
            $requestOrder .= "&$key=$val";
        }
        $cont++;
    }
}

$hashOriginal = $_REQUEST['md5'];

if (true) {
    if (strpos($URI, "GetUserName") !== false) {
        $providerId = $_REQUEST["providerId"]; //Ok
        $PlayerId = $_REQUEST["userid"]; //Ok
        $hash = $_REQUEST["hash"]; //Ok, pero será igual a $md5
        $token = $_REQUEST["token"];
        $ipAddress = $_REQUEST["ipAddress"];
        $externalPlayerId = $_REQUEST["externalPlayerId"];

        /* Procesamos */
        $Platipus = new Platipus($token, $hash, $externalPlayerId, $hashOriginal);
        $response = ($Platipus->getUserName($providerId, $PlayerId, $hash));
    } elseif (strpos($URI, "GetBalance") !== false) {
        $hash = $_REQUEST["md5"];
        $token = $_REQUEST["token"];
        $providerId = $_REQUEST["providerid"];
        $externalPlayerId = $_REQUEST["userid"];
        $PlayerId = $_REQUEST["userid"];

        /* Procesamos */
        $Platipus = new Platipus($token, $hash, $externalPlayerId, $hashOriginal);
        $response = ($Platipus->getBalance($providerId, $PlayerId, $hash));
    } elseif (strpos($URI, "GetBalance2") !== false) {
        $hash = $_REQUEST["hash"]; //Ok, pero será igual a $md5
        $token = $_REQUEST["token"];
        $providerId = $_REQUEST["providerId"]; //Ok
        $ipAddress = $_REQUEST["ipAddress"];
        $externalPlayerId = $_REQUEST["externalPlayerId"];
        $PlayerId = $_REQUEST["userId"]; //Ok

        /* Procesamos */
        $Platipus = new Platipus($token, $hash, $externalPlayerId, $hashOriginal);
        $response = ($Platipus->getBalance2($providerId, $PlayerId, $hash));
    } else {
        $providerId = $_REQUEST["providerid"];
        $UserId = $_REQUEST["userid"];
        $md5 = $_REQUEST["md5"];
        $amount = floatval($_REQUEST["amount"]);
        $remotetranid = $_REQUEST["remotetranid"];
        $gameid = $_REQUEST["gameid"];
        $gameName = $_REQUEST["gameName"];
        $roundid = $_REQUEST["roundid"];
        $trntype = $_REQUEST["trntype"];
        $finished = $_REQUEST["finished"];
        $secretkey = "12345678";
        $hash = $_REQUEST["md5"];
        $token = $_REQUEST["token"];

        $datos = $data;

        if ($amount < 0) {
            $amount = -$amount;
        }

        if (strpos($URI, "BetWin")) {
            if ($trntype == "BET") {
                $Platipus = new Platipus($token, $hash, $UserId, $hashOriginal);
                $response = $Platipus->Debit($gameid, $gameName, $amount, $roundid, $remotetranid, json_encode($datos));
            } elseif ($trntype == "WIN") {
                $Platipus = new Platipus($token, $hash, $UserId, $hashOriginal);

                if ($finished == 1) {
                    $finished = true;
                } else {
                    $finished = false;
                }

                $isBonus = false;

                $response = $Platipus->Credit($gameid, $gameName, $amount, $roundid, $remotetranid, json_encode($datos), $isBonus, $finished);
            }
        } elseif (strpos($URI, "Refund")) {
            $Platipus = new Platipus($token, $hash, $UserId, $hashOriginal);
            $response = ($Platipus->Rollback($amount, $roundid, $remotetranid, $UserId, json_encode($datos)));
        } elseif (strpos($URI, "Freespin")) {
            $roomid = $_REQUEST["roomid"];
            $freespin_id = $_REQUEST["freespin_id"];

            $Platipus = new Platipus($token, $md5, $UserId, $hashOriginal);
            $response = $Platipus->Debit($gameid, $gameName, 0, $roomid, 'FS' . $roundid . $freespin_id, json_encode($datos));
            $response = $Platipus->Credit($gameid, $gameName, $amount, $roomid, $roundid . $freespin_id, json_encode($datos), true, $finished);
        }
    }

    print_r($response);
}
