<?php
/**
 * Este archivo contiene un script para procesar y manejar las solicitudes de la API de casino 'Microgaming'.
 * Proporciona funcionalidades como autenticación, consulta de saldo, manejo de jugadas, y otras operaciones
 * relacionadas con la integración del casino.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 * @access     public
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_ENV   Indica si la conexión global está habilitada ["enabledConnectionGlobal"].
 * @var mixed $_ENV   Configuración para habilitar el tiempo de espera de bloqueo ["ENABLEDSETLOCKWAITTIMEOUT"].
 * @var mixed $log    Variable utilizada para almacenar y registrar información de logs.
 * @var mixed $body   Contiene el cuerpo de la solicitud recibida en formato XML.
 * @var mixed $method Almacena el método de la solicitud extraído del XML.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Microgaming;


$_ENV["enabledConnectionGlobal"] = 1;

$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';
$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . trim(file_get_contents('php://input'));

$body = trim(file_get_contents('php://input'));


if ($body != "") {
    Header('Content-type: text/xml');

    $data = simplexml_load_string($body);

    $log = time();

    $method = $data->methodcall->attributes()->name;
}


//print_r($data);

switch ($method) {
    case "login":

        $Auth = $data->methodcall->auth;

        $call = $data->methodcall->call;
        $seq = $data->methodcall->call->attributes()->seq;
        $Token = $data->methodcall->call->attributes()->token;

        $Microgaming = new Microgaming($seq, $Token, $Auth->attributes()->login, $Auth->attributes()->password);

        $response = ($Microgaming->Auth());
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);
        print_r($response);


        break;

    case "getbalance":

        $Auth = $data->methodcall->auth;

        $call = $data->methodcall->call;
        $seq = $data->methodcall->call->attributes()->seq;
        $Token = $data->methodcall->call->attributes()->token;

        $Microgaming = new Microgaming($seq, $Token, $Auth->attributes()->login, $Auth->attributes()->password);

        $response = ($Microgaming->getBalance());
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);
        print_r($response);

        break;

    case "play":

        $Auth = $data->methodcall->auth;

        $call = $data->methodcall->call;
        $seq = $data->methodcall->call->attributes()->seq;
        $Token = $data->methodcall->call->attributes()->token;
        $playtype = $call->attributes()->playtype;

        switch ($playtype) {
            case "bet":


                $freegame = $call->attributes()->freegame;

                if ($freegame != "") {
                    $gameid = $call->attributes()->gameid;
                    $gamereference = $call->attributes()->gamereference;
                    $actionid = $call->attributes()->actionid;
                    $actiondesc = $call->attributes()->actiondesc;
                    $amount = floatval($call->attributes()->amount) / 100;
                    $start = $call->attributes()->start;
                    $finish = $call->attributes()->finish;
                    $offline = $call->attributes()->offline;
                    $currency = $call->attributes()->currency;
                    $freegame = $call->attributes()->freegame;
                    $freegameofferinstanceid = $call->attributes()->freegameofferinstanceid;
                    $freegamenumgamesplayed = $call->attributes()->freegamenumgamesplayed;
                    $freegamenumgamesremaining = $call->attributes()->freegamenumgamesremaining;
                    $clienttypeid = $call->attributes()->clienttypeid;

                    $Microgaming = new Microgaming($seq, $Token, $Auth->attributes()->login, $Auth->attributes()->password);

                    $datos = array(
                        "seq" => $seq,
                        "playtype" => $playtype,
                        "token" => $Token,
                        "gameid" => $gameid,
                        "gamereference" => $gamereference,
                        "actionid" => $actionid,
                        "actiondesc" => $actiondesc,
                        "amount" => $amount,
                        "start" => $start,
                        "finish" => $finish,
                        "offline" => $offline,
                        "currency" => $currency,
                        "freegame" => $freegame,
                        "freegameofferinstanceid" => $freegameofferinstanceid,
                        "freegamenumgamesplayed" => $freegamenumgamesplayed,
                        "freegamenumgamesremaining" => $freegamenumgamesremaining,
                        "clienttypeid" => $clienttypeid
                    );

                    $response = ($Microgaming->DebitFree($gamereference, $gameid, "", $amount, $actionid, json_encode($datos)));
                    $log = $log . "/" . time();

                    $log = $log . "\r\n" . "-------------------------" . "\r\n";
                    $log = $log . ($response);
                    print_r($response);
                } else {
                    $gameid = $call->attributes()->gameid;
                    $gamereference = $call->attributes()->gamereference;
                    $actionid = $call->attributes()->actionid;
                    $actiondesc = $call->attributes()->actiondesc;
                    $amount = floatval($call->attributes()->amount) / 100;
                    $start = $call->attributes()->start;
                    $finish = $call->attributes()->finish;
                    $offline = $call->attributes()->offline;
                    $currency = $call->attributes()->currency;
                    $freegame = $call->attributes()->freegame;
                    $freegameofferinstanceid = $call->attributes()->freegameofferinstanceid;
                    $freegamenumgamesplayed = $call->attributes()->freegamenumgamesplayed;
                    $freegamenumgamesremaining = $call->attributes()->freegamenumgamesremaining;
                    $clienttypeid = $call->attributes()->clienttypeid;

                    $Microgaming = new Microgaming($seq, $Token, $Auth->attributes()->login, $Auth->attributes()->password);

                    $datos = array(
                        "seq" => $seq,
                        "playtype" => $playtype,
                        "token" => $Token,
                        "gameid" => $gameid,
                        "gamereference" => $gamereference,
                        "actionid" => $actionid,
                        "actiondesc" => $actiondesc,
                        "amount" => $amount,
                        "start" => $start,
                        "finish" => $finish,
                        "offline" => $offline,
                        "currency" => $currency,
                        "freegame" => $freegame,
                        "freegameofferinstanceid" => $freegameofferinstanceid,
                        "freegamenumgamesplayed" => $freegamenumgamesplayed,
                        "freegamenumgamesremaining" => $freegamenumgamesremaining,
                        "clienttypeid" => $clienttypeid
                    );

                    $response = ($Microgaming->Debit($gamereference, $gameid, "", $amount, $actionid, json_encode($datos)));
                    $log = $log . "/" . time();

                    $log = $log . "\r\n" . "-------------------------" . "\r\n";
                    $log = $log . ($response);
                    print_r($response);
                }


                break;

            case "win":

                $gameid = $call->attributes()->gameid;
                $gamereference = $call->attributes()->gamereference;
                $actionid = $call->attributes()->actionid;
                $actiondesc = $call->attributes()->actiondesc;
                $amount = floatval($call->attributes()->amount) / 100;
                $start = $call->attributes()->start;
                $finish = $call->attributes()->finish;
                $offline = $call->attributes()->offline;
                $currency = $call->attributes()->currency;

                $Microgaming = new Microgaming($seq, $Token, $Auth->attributes()->login, $Auth->attributes()->password);

                $datos = array(
                    "seq" => $seq,
                    "playtype" => $playtype,
                    "token" => $Token,
                    "gameid" => $gameid,
                    "gamereference" => $gamereference,
                    "actionid" => $actionid,
                    "actiondesc" => $actiondesc,
                    "amount" => $amount,
                    "start" => $start,
                    "finish" => $finish,
                    "offline" => $offline,
                    "currency" => $currency
                );


                if ($_REQUEST['isDebug'] == '1') {
                    print_r(date('Y-m-d H:i:s'));
                }
                $response = ($Microgaming->Credit($gamereference, $gameid, "", $amount, strval($actionid), true, json_encode($datos)));
                $log = $log . "/" . time();

                $log = $log . "\r\n" . "-------------------------" . "\r\n";
                $log = $log . ($response);
                print_r($response);

                break;
            case "progressivewin":

                $gameid = $call->attributes()->gameid;
                $gamereference = $call->attributes()->gamereference;
                $actionid = $call->attributes()->actionid;
                $actiondesc = $call->attributes()->actiondesc;
                $amount = floatval($call->attributes()->amount) / 100;
                $start = $call->attributes()->start;
                $finish = $call->attributes()->finish;
                $offline = $call->attributes()->offline;

                $Microgaming = new Microgaming($seq, $Token, $Auth->attributes()->login, $Auth->attributes()->password);

                $datos = array(
                    "seq" => $seq,
                    "playtype" => $playtype,
                    "token" => $Token,
                    "gameid" => $gameid,
                    "gamereference" => $gamereference,
                    "actionid" => $actionid,
                    "actiondesc" => $actiondesc,
                    "amount" => $amount,
                    "start" => $start,
                    "finish" => $finish,
                    "offline" => $offline
                );

                $response = ($Microgaming->CreditProgressive($gamereference, $gameid, "", $amount, $actionid, true, json_encode($datos)));
                $log = $log . "/" . time();

                $log = $log . "\r\n" . "-------------------------" . "\r\n";
                $log = $log . ($response);
                print_r($response);

                break;
            case "refund":

                $gameid = $call->attributes()->gameid;
                $gamereference = $call->attributes()->gamereference;
                $actionid = $call->attributes()->actionid;
                $actiondesc = $call->attributes()->actiondesc;
                $amount = floatval($call->attributes()->amount) / 100;
                $start = $call->attributes()->start;
                $finish = $call->attributes()->finish;
                $offline = $call->attributes()->offline;

                $Microgaming = new Microgaming($seq, $Token, $Auth->attributes()->login, $Auth->attributes()->password);

                $datos = array(
                    "seq" => $seq,
                    "playtype" => $playtype,
                    "token" => $Token,
                    "gameid" => $gameid,
                    "gamereference" => $gamereference,
                    "actionid" => $actionid,
                    "actiondesc" => $actiondesc,
                    "amount" => $amount,
                    "start" => $start,
                    "finish" => $finish,
                    "offline" => $offline
                );

                $response = ($Microgaming->Rollback($gamereference, $gameid, "", $amount, $actionid, json_encode($datos)));
                $log = $log . "/" . time();

                $log = $log . "\r\n" . "-------------------------" . "\r\n";
                $log = $log . ($response);
                print_r($response);

                break;
        }


        break;

    case "endgame":
        $Auth = $data->methodcall->auth;

        $call = $data->methodcall->call;
        $seq = $data->methodcall->call->attributes()->seq;
        $Token = $data->methodcall->call->attributes()->token;

        $gameid = $call->attributes()->gameid;
        $gamereference = $call->attributes()->gamereference;
        $offline = $call->attributes()->offline;

        $Microgaming = new Microgaming($seq, $Token, $Auth->attributes()->login, $Auth->attributes()->password);

        $datos = array(
            "seq" => $seq,
            "playtype" => $playtype,
            "token" => $Token,
            "gameid" => $gameid,
            "gamereference" => $gamereference,
            "offline" => $offline

        );

        $response = ($Microgaming->Credit2($gamereference, $gameid, $actionid, json_encode($datos)));
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);
        print_r($response);

        break;


    case "refreshtoken":

        $call = $data->methodcall->call;
        $seq = $data->methodcall->call->attributes()->seq;
        $Token = $data->methodcall->call->attributes()->token;


        $Microgaming = new Microgaming($seq, $Token, $Auth->attributes()->login, $Auth->attributes()->password);

        $response = ($Microgaming->Refreshtoken());
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);
        print_r($response);
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

        $Microgaming = new Microgaming($seq, $Token, $Auth->attributes()->login, $Auth->attributes()->password);

        $datos = array(
            "TransactionID" => (string)$TransactionID,
            "WinReferenceNum" => (string)$WinReferenceNum,
            "WinAmount" => (string)$WinAmount,
            "GameReference" => (string)$GameReference,
            "GameStatus" => (string)$GameStatus
        );

        $response = ($Microgaming->Credit($GameReference, $TransactionID, "", $WinAmount, $WinReferenceNum, $isEnd, json_encode($datos)));
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);
        print_r($response);

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


        $Microgaming = new Microgaming("", "", $Token);

        $datos = array(
            "TransactionID" => (string)$TransactionID,
            "BetReferenceNum" => (string)$BetReferenceNum,
            "RefundAmount" => (string)$RefundAmount,
            "GameReference" => (string)$GameReference
        );

        $response = ($Microgaming->Rollback($GameReference, $TransactionID, "", $RefundAmount, $BetReferenceNum, json_encode($datos)));
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);
        print_r($response);

        break;

    default:

        break;
}

