<?php
/**
 * Este archivo contiene un script para procesar y generar un informe de cuotas totales
 * basado en datos de usuarios, transacciones y actividades relacionadas.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    ninguna
 * @since      2025-05-11
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST                 Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $_SERVER                  Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $_GET                     Variable superglobal que contiene datos enviados a través del método GET.
 * @var mixed $log                      Variable utilizada para almacenar y registrar información de depuración.
 * @var mixed $body                     Contenido del cuerpo de la solicitud HTTP recibido.
 * @var mixed $data                     Datos decodificados del cuerpo de la solicitud en formato JSON.
 * @var mixed $URI                      URI de la solicitud actual.
 * @var mixed $headers                  Encabezados HTTP de la solicitud actual.
 * @var mixed $ConfigurationEnvironment Objeto para manejar configuraciones del entorno (desarrollo o producción).
 * @var mixed $secretKey                Clave secreta utilizada para generar y validar firmas HMAC.
 * @var mixed $PESignature              Firma HMAC generada para validar la integridad de los datos.
 * @var mixed $ISoftBet                 Objeto que maneja la integración con el proveedor ISoftBet.
 * @var mixed $response                 Respuesta generada por las operaciones realizadas.
 * @var mixed $sign                     Firma utilizada para autenticar solicitudes.
 * @var mixed $datos                    Datos procesados para operaciones específicas.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\casino\ISoftBet;

header('Content-type: application/json; charset=utf-8');
if ( ! function_exists('getallheaders')) {
    /**
     * Obtiene todos los encabezados de la solicitud HTTP.
     *
     * @return array Un arreglo asociativo con los nombres y valores de los encabezados.
     */
    function getallheaders()
    {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}

ini_set('display_errors', 'OFF');

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . $_SERVER['REQUEST_URI'];
$log = $log . " Body ";

$log = $log . trim(file_get_contents('php://input'));
$body = file_get_contents('php://input');

$body = preg_replace("[\n|\r|\n\r]", "", $body);
$data = json_decode($body);
$URI = $_SERVER['REQUEST_URI'];


if ($_GET["date_from"] == "") {
    exit();
}

if ($_GET["date_to"] == "") {
    exit();
}


$headers = getallheaders();
if ($body != "") {
    $ConfigurationEnvironment = new ConfigurationEnvironment();

    if ($ConfigurationEnvironment->isDevelopment()) {
        $secretKey = "xz8GAVAWRacPCVsSPK5Ovwjpf10VttmO";
    } else {
        $secretKey = "";
    }


    $PESignature = hash_hmac('sha256', $body, $secretKey);

    if ($PESignature == $_GET["hash"]) {
        if (strpos($data->action->command, "init") !== false) {
            $sesion = $data->sessionid;
            $playerid = $data->playerid;
            $skinid = $data->skinid;
            $state = $data->state;
            $operator = $data->operator;
            $token = $data->action->parameters->token;
            $country = $data->action->parameters->country;
            $game_type = $data->action->parameters->game_type;
            $jppool = $data->action->parameters->jppool;

            $sign = $_REQUEST["sign"];

            /* Procesamos */
            $ISoftBet = new ISoftBet($token, $sign);
            $response = ($ISoftBet->Auth($sesion));


            $log = "";
            $log = $log . "/" . time();

            $log = $log . "\r\n" . "-------------------------" . "\r\n";
            $log = $log . ($response);

            print_r($response);
        }

        if (strpos($data->action->command, "token") !== false) {
            $token = $data->sessionid;;


            $sign = $_REQUEST["sign"];

            /* Procesamos */
            $ISoftBet = new ISoftBet($token, $sign);
            $response = ($ISoftBet->Token($data));


            $log = "";
            $log = $log . "/" . time();

            $log = $log . "\r\n" . "-------------------------" . "\r\n";
            $log = $log . ($response);

            print_r($response);
        }

        if (strpos($data->action->command, "balance") !== false) {
            $token = $data->sessionid;;
            /* Procesamos */
            $ISoftBet = new ISoftBet($token, $sign);
            $response = ($ISoftBet->getBalance());


            $log = "";
            $log = $log . "/" . time();

            $log = $log . "\r\n" . "-------------------------" . "\r\n";
            $log = $log . ($response);
            print_r($response);
        }

        if (strpos($data->action->command, "win") !== false) {
            $token = $data->sessionid;
            $operator = $data->operator;
            $transactionId = $data->action->parameters->transactionid;
            $transactionRefId = $data->action->parameters->transactionRefId;
            $roundid = $data->action->parameters->roundid;
            $closeround = $data->action->parameters->closeround;
            $CreditAmount = floatval(round($data->action->parameters->amount, 2) / 100);
            $jpw = doubleval(round($data->action->parameters->jpw, 2) / 100);
            $jpw_from_jpc = doubleval(round($data->action->parameters->jpw_from_jpc, 2) / 100);
            $froundid = $data->action->parameters->froundid;
            $fround_campaignid = $data->action->parameters->fround_campaignid;
            $fround_coin_value = intval(round($data->action->parameters->fround_coin_value, 2) / 100);
            $fround_lines = $data->action->parameters->fround_lines;
            $game_id = $data->skinid;
            $fround_line_bet = $data->action->parameters->fround_line_bet;
            $timestamp = $data->action->parameters->timestamp;
            $game_type = $data->action->parameters->game_type;
            $additional_game_values = $data->action->parameters->additional_game_values;
            $jackpot = $data->action->parameters->additional_game_values->jackpot;

            $datos = $data;

            /* Procesamos */
            if ($operator != "testOperator") {
                $ISoftBet = new ISoftBet($token, $sign);


                $respuestaCredit = $ISoftBet->Credit($game_id, $CreditAmount, $roundid, $transactionId, json_encode($datos));


                print_r($respuestaCredit);
            } else {
                $respuesta = array(
                    "status" => "error",
                    "code" => "W_07",
                    "message" => "Operator should remain the same during the round.",
                    "action" => "void"

                );
                print_r(json_encode($respuesta));
            }
        }

        if (strpos($data->action->command, "bet") !== false) {
            $token = $data->sessionid;
            $transactionId = $data->action->parameters->transactionid;
            $transactionRefId = $data->action->transactionRefId;
            $roundid = $data->action->parameters->roundid;
            $DebitAmount = floatval(round($data->action->parameters->amount, 2) / 100);
            $jpc = doubleval(round($data->action->parameters->jpc, 2) / 100);
            $froundid = $data->action->parameters->froundid;
            $fround_campaignid = $data->action->parameters->fround_campaignid;
            $fround_coin_value = intval(round($data->action->parameters->fround_coin_value, 2) / 100);
            $fround_lines = $data->action->parameters->fround_lines;
            $game_id = $data->skinid;
            $fround_line_bet = $data->action->parameters->fround_line_bet;
            $timestamp = $data->action->parameters->timestamp;
            $game_type = $data->action->parameters->game_type;
            $additional_game_values = $data->action->parameters->additional_game_values;
            $jackpot = $data->action->parameters->additional_game_values->jackpot;


            $freespin = false;
            if ($froundid > 0) {
                $freespin = true;
            }

            $datos = $data;

            /* Procesamos */

            $ISoftBet = new ISoftBet($token, $sign);


            $respuestaDebit = ($ISoftBet->Debit($game_id, $DebitAmount, $roundid, $transactionId, json_encode($datos), $freespin));

            print_r($respuestaDebit);
        }

        if ($data->state == "multi") {
            if (strpos($data->actions[0]->command, "bet") !== false) {
                $token = $data->sessionid;
                $transactionId = $data->actions[0]->parameters->transactionid;
                $transactionRefId = $data->actions[0]->transactionRefId;
                $roundid = $data->actions[0]->parameters->roundid;
                $DebitAmount = floatval(round($data->actions[0]->parameters->amount, 2) / 100);
                $jpc = doubleval(round($data->actions[0]->parameters->jpc, 2) / 100);
                $froundid = $data->actions[0]->parameters->froundid;
                $fround_campaignid = $data->actions[0]->parameters->fround_campaignid;
                $fround_coin_value = intval(round($data->actions[0]->parameters->fround_coin_value, 2) / 100);
                $fround_lines = $data->actions[0]->parameters->fround_lines;
                $game_id = $data->skinid;
                $fround_line_bet = $data->actions[0]->parameters->fround_line_bet;
                $timestamp = $data->actions[0]->parameters->timestamp;
                $game_type = $data->actions[0]->parameters->game_type;
                $additional_game_values = $data->actions[0]->parameters->additional_game_values;
                $jackpot = $data->actions[0]->parameters->additional_game_values->jackpot;

                $freespin = false;
                if ($froundid > 0) {
                    $freespin = true;
                }
                $datos = $data;

                /* Procesamos */

                $ISoftBet = new ISoftBet($token, $sign);


                $respuestaDebit = ($ISoftBet->Debit($game_id, $DebitAmount, $roundid, $transactionId, json_encode($datos), $freespin));

                // print_r($respuestaDebit);
                if (strpos($data->actions[1]->command, "win") !== false) {
                    $token = $data->sessionid;
                    $operator = $data->operator;
                    $transactionId = $data->actions[1]->parameters->transactionid;
                    $transactionRefId = $data->actions[1]->parameters->transactionRefId;
                    $roundid = $data->actions[1]->parameters->roundid;
                    $closeround = $data->actions[1]->parameters->closeround;
                    $CreditAmount = floatval(round($data->actions[1]->parameters->amount, 2) / 100);
                    $jpw = doubleval(round($data->actions[1]->parameters->jpw, 2) / 100);
                    $jpw_from_jpc = doubleval(round($data->actions[1]->parameters->jpw_from_jpc, 2) / 100);
                    $froundid = $data->actions[1]->parameters->froundid;
                    $fround_campaignid = $data->actions[1]->parameters->fround_campaignid;
                    $fround_coin_value = intval(round($data->actions[1]->parameters->fround_coin_value, 2) / 100);
                    $fround_lines = $data->actions[1]->parameters->fround_lines;
                    $game_id = $data->skinid;
                    $fround_line_bet = $data->actions[1]->parameters->fround_line_bet;
                    $timestamp = $data->actions[1]->parameters->timestamp;
                    $game_type = $data->actions[1]->parameters->game_type;
                    $additional_game_values = $data->actions[1]->parameters->additional_game_values;
                    $jackpot = $data->actions[1]->parameters->additional_game_values->jackpot;

                    $datos = $data;

                    /* Procesamos */
                    if ($operator != "testOperator") {
                        $ISoftBet = new ISoftBet($token, $sign);


                        $respuestaCredit = $ISoftBet->Credit($game_id, $CreditAmount, $roundid, $transactionId, json_encode($datos));
                        print_r($respuestaCredit);
                    } else {
                        $respuesta = array(
                            "status" => "error",
                            "code" => "W_07",
                            "message" => "Operator should remain the same during the round.",
                            "action" => "void"

                        );
                        print_r(json_encode($respuesta));
                    }
                }
            }
        }
        if (strpos($data->action->command, "depositmoney") !== false) {
            $token = $data->sessionid;
            $playerid = $data->playerid;
            $game_id = $data->skinid;
            $roundid = $data->action->parameters->transactionid;
            $transactionId = $data->action->parameters->transactionid;
            $amount = floatval(round($data->action->parameters->amount, 2) / 100);
            $offerid = $data->action->parameters->offerid;
            $type = $data->action->parameters->type;
            $description = $data->action->parameters->description;
            $additional_values = $data->action->parameters->additional_values;


            $datos = $data;
            if ($type == "leaderboard") {
                $game_id = "Bonos";
            }
            //
            /* Procesamos */
            $ISoftBet = new ISoftBet($token, $sign, $playerid);
            $response = ($ISoftBet->Debit($game_id, 0, $roundid, "F.S" . $transactionId, json_encode($datos), true));
            $response = $ISoftBet->Credit($game_id, $amount, $roundid, $transactionId, json_encode($datos));

            $log = "";
            $log = $log . "/" . time();

            $log = $log . "\r\n" . "-------------------------" . "\r\n";
            $log = $log . ($response);
            print_r($response);
        }

        if (strpos($data->action->command, "cancel") !== false) {
            $token = $data->sessionid;
            $playerid = $data->playerid;
            $transactionId = $data->action->parameters->transactionid;
            $transactionRefId = $data->action->parameters->transactionRefId;
            $roundid = $data->action->parameters->roundid;
            $rollbackAmount = floatval(round($data->action->parameters->amount, 2) / 100);
            $jpc = doubleval(round($data->action->parameters->jpc, 2) / 100);
            $froundid = $data->action->parameters->froundid;
            $fround_coin_value = intval(round($data->action->parameters->fround_coin_value, 2) / 100);
            $fround_lines = $data->action->parameters->fround_lines;
            $fround_line_bet = $data->action->parameters->fround_line_bet;
            $timestamp = $data->action->parameters->timestamp;
            $additional_game_values = $data->action->parameters->additional_game_values;


            $datos = $data;

            /* Procesamos */

            $ISoftBet = new ISoftBet($token, $sign);
            $respuesta = ($ISoftBet->Rollback($rollbackAmount, $roundid, $transactionId, $playerid, json_encode($datos)));


            print_r($respuesta);
        }

        if (strpos($data->action->command, "end") !== false) {
            $token = $data->sessionid;
            $sessionstatus = $data->action->parameters->sessionstatus;

            $ISoftBet = new ISoftBet($token, $sign);

            $response = ($ISoftBet->End());
            $log = $log . "/" . time();

            $log = $log . "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
            $log = $log . ($response);

            print_r($response);
        }
        if (strpos($data->action->command, "dialog") !== false) {
            $token = $data->sessionid;
            $action = $data->action;
            $choiceIndex = $data->choiceIndex;


            /* Procesamos */
            $ISoftBet = new ISoftBet($token, $sign);
            $response = ($ISoftBet->getBalance());


            $log = "";
            $log = $log . "/" . time();

            $log = $log . "\r\n" . "-------------------------" . "\r\n";
            $log = $log . ($response);
            print_r($response);
        }
    } else {
        $respuesta = array(
            "status" => "error",
            "code" => "R_03",
            "message" => "Invalid HMAC",
            "action" => "void"

        );
        print_r(json_encode($respuesta));
    }
}


if (strpos($URI, "report") !== false) {
    $token = $data->sessionid;
    $playerid = $_GET["playerid"];
    $operator = $_GET["operator"];
    $dateFrom = $_GET["date_from"];
    $dateFrom = explode(" ", $dateFrom);
    $dateFrom = str_replace("T", ' ', $dateFrom[0]);
    $dateTo = $_GET["date_to"];
    $dateTo = explode(" ", $dateTo);
    $dateTo = str_replace("T", ' ', $dateTo[0]);


    /* Procesamos */
    $ISoftBet = new ISoftBet($token, $sign, $playerid);
    $response = ($ISoftBet->report($dateFrom, $dateTo));


    $log = "";
    $log = $log . "/" . time();

    $log = $log . "\r\n" . "-------------------------" . "\r\n";
    $log = $log . ($response);
    print_r($response);
}


