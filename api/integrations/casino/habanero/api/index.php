<?php

/**
 * Este archivo contiene un script para procesar solicitudes relacionadas con la integración
 * del casino Habanero, incluyendo autenticación, transferencia de fondos, consultas y cierre de sesión.
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
 * @var mixed   $_REQUEST Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var boolean $_ENV     Indica si el modo de depuración está habilitado ['debug'].
 * @var integer $_ENV     Controla si la conexión global está habilitada ["enabledConnectionGlobal"].
 * @var string  $_ENV     Configuración para el tiempo de espera de bloqueo ["ENABLEDSETLOCKWAITTIMEOUT"].
 * @var string  $log      Almacena información de registro para depuración.
 * @var string  $body     Contiene el cuerpo de la solicitud HTTP recibida.
 * @var string  $URI      Almacena la URI de la solicitud actual.
 * @var mixed   $response Almacena la respuesta generada por las operaciones realizadas.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Habanero;

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . $_SERVER['REQUEST_URI'];
$log = $log . trim(file_get_contents('php://input'));

$body = file_get_contents('php://input');

$URI = $_SERVER['REQUEST_URI'];

$body = str_replace("'", "", $body);

if ($body != "") {
    $data = json_decode($body);

    if (strpos($data->type, "playerdetailrequest") !== false) {
        $dtsent = $data->dtsent;
        $brandgameid = $data->basegame->brandgameid;
        $keyname = $data->basegame->keyname;
        $username = $data->auth->username;
        $passkey = $data->auth->passkey;
        $machinename = $data->auth->machinename;
        $locale = $data->auth->locale;
        $brandid = $data->auth->brandid;
        $token = $data->playerdetailrequest->token;
        $gamelaunch = $data->playerdetailrequest->gamelaunch;

        $sign = $_REQUEST["sign"];

        /* Procesamos */
        $Habanero = new Habanero($token, $sign);
        $response = ($Habanero->Auth());

        print_r($response);
    }

    if (strpos($data->type, "fundtransferrequest") !== false) {
        //datos juego
        $dtsent = $data->dtsent;
        $brandgameid = $data->basegame->brandgameid;
        $keyname = $data->basegame->keyname;

        //datos jugador
        $username = $data->auth->username;
        $passkey = $data->auth->passkey;
        $machinename = $data->auth->machinename;
        $locale = $data->auth->locale;
        $brandid = $data->auth->brandid;

        //datos de tranferencia
        $token = $data->fundtransferrequest->token;
        $gameinstanceid = $data->fundtransferrequest->gameinstanceid;
        $customplayertype = $data->fundtransferrequest->customplayertype;
        $friendlygameinstanceid = $data->fundtransferrequest->friendlygameinstanceid;
        $isretry = $data->fundtransferrequest->isretry;
        $isrefund = $data->fundtransferrequest->isrefund;
        $isrecredit = $data->fundtransferrequest->isrecredit;
        $accountid = $data->fundtransferrequest->accountid;

        //datos de proceso de tranferencia
        $debitandcredit = $data->fundtransferrequest->funds->debitandcredit;
        $transferid = $data->fundtransferrequest->funds->fundinfo[0]->transferid;
        $amount = floatval($data->fundtransferrequest->funds->fundinfo[0]->amount);
        $bonusamount = floatval($data->fundtransferrequest->funds->fundinfo[0]->bonusamount);

        $dtevent = $data->fundtransferrequest->funds->fundinfo[0]->dtevent;
        $currencycode = $data->fundtransferrequest->funds->fundinfo[0]->currencycode;
        $gamestatemode = $data->fundtransferrequest->funds->fundinfo[0]->gamestatemode;
        $jpwin = $data->fundtransferrequest->funds->fundinfo[0]->jpwin;
        $jpcont = $data->fundtransferrequest->funds->fundinfo[0]->jpcont;
        $isbonus = $data->fundtransferrequest->funds->fundinfo[0]->isbonus;
        $initialdebittransferid = $data->fundtransferrequest->funds->fundinfo[0]->initialdebittransferid;
        $accounttransactiontype = $data->fundtransferrequest->funds->fundinfo[0]->accounttransactiontype;
        $gameinfeature = $data->fundtransferrequest->funds->fundinfo[0]->gameinfeature;

        //detalles del juego
        $name = $data->fundtransferrequest->gamedetails->name;
        $keynamedetails = $data->fundtransferrequest->gamedetails->keyname;
        $gametypeid = $data->fundtransferrequest->gamedetails->gametypeid;
        $gametypename = $data->fundtransferrequest->gamedetails->gametypename;
        $brandgameid = $data->fundtransferrequest->gamedetails->brandgameid;
        $gamesessionid = $data->fundtransferrequest->gamedetails->gamesessionid;
        $gameinstanceid_gamedetails = $data->fundtransferrequest->gamedetails->gameinstanceid;
        $friendlygameinstanceid = $data->fundtransferrequest->gamedetails->friendlygameinstanceid;
        $channel = $data->fundtransferrequest->gamedetails->channel;
        $device = $data->fundtransferrequest->gamedetails->device;
        $browser = $data->fundtransferrequest->gamedetails->browser;

        $datos = $data;

        /* Procesamos */
        $Habanero = new Habanero($token, $sign, $accountid);

        $freespin = false;

        if ($isbonus != null && $isbonus != '' && $isbonus == true) {
            $freespin = true;
        }

        if ($jpwin == true) {
            $response = ($Habanero->Debit($keyname, 0, $gameinstanceid, "F.S" . $transferid, json_encode($datos), true));
            $response = $Habanero->Credit($keyname, $amount, $gameinstanceid, $transferid, json_encode($datos));
        }

        if ($isbonus == true) {
            $response = ($Habanero->Debit($keyname, 0, $gameinstanceid, "F.S" . $transferid, json_encode($datos), true));
            $response = $Habanero->Credit($keyname, $amount, $gameinstanceid, $transferid, json_encode($datos));
        }

        if ($debitandcredit == true) {
            $transferidDebit = $data->fundtransferrequest->funds->fundinfo[0]->transferid;
            $amountDebit = floatval($data->fundtransferrequest->funds->fundinfo[0]->amount);
            $dtevent = $data->fundtransferrequest->funds->fundinfo[0]->dtevent;
            $currencycode = $data->fundtransferrequest->funds->fundinfo[0]->currencycode;
            $gamestatemode = $data->fundtransferrequest->funds->fundinfo[0]->gamestatemode;
            $jpwin = $data->fundtransferrequest->funds->fundinfo[0]->jpwin;
            $jpcont = $data->fundtransferrequest->funds->fundinfo[0]->jpcont;
            $isbonus = $data->fundtransferrequest->funds->fundinfo[0]->isbonus;
            $initialdebittransferid = $data->fundtransferrequest->funds->fundinfo[0]->initialdebittransferid;
            $accounttransactiontype = $data->fundtransferrequest->funds->fundinfo[0]->accounttransactiontype;
            $gameinfeature = $data->fundtransferrequest->funds->fundinfo[0]->gameinfeature;

            if ($amountDebit < 0) {
                $amountDebit = ($amountDebit * -1);
            }

            $respuestaDebit = ($Habanero->Debit($keyname, $amountDebit, $gameinstanceid, $transferidDebit, json_encode($datos), $freespin));

            $DatosDebit = json_decode($respuestaDebit);

            if (json_decode($DatosDebit->fundtransferresponse->status->success == true)) {
                $datos = $data;

                /* Procesamos */
                $Habanero = new Habanero($token, $sign, $accountid);

                $transferidCredit = $data->fundtransferrequest->funds->fundinfo[1]->transferid;
                $amountCredit = floatval($data->fundtransferrequest->funds->fundinfo[1]->amount);
                $dtevent = $data->fundtransferrequest->funds->fundinfo[1]->dtevent;
                $currencycode = $data->fundtransferrequest->funds->fundinfo[1]->currencycode;
                $gamestatemode = $data->fundtransferrequest->funds->fundinfo[1]->gamestatemode;
                $jpwin = $data->fundtransferrequest->funds->fundinfo[1]->jpwin;
                $jpcont = $data->fundtransferrequest->funds->fundinfo[1]->jpcont;
                $isbonus = $data->fundtransferrequest->funds->fundinfo[1]->isbonus;
                $initialdebittransferid = $data->fundtransferrequest->funds->fundinfo[1]->initialdebittransferid;
                $accounttransactiontype = $data->fundtransferrequest->funds->fundinfo[1]->accounttransactiontype;
                $gameinfeature = $data->fundtransferrequest->funds->fundinfo[1]->gameinfeature;

                $respuestaCredit = $Habanero->Credit($keyname, $amountCredit, $gameinstanceid, $transferidCredit, json_encode($datos));
                $respuestaCredit = json_decode($respuestaCredit);
                $respuestaCredit->fundtransferresponse->status->successdebit = true;
                $respuestaCredit->fundtransferresponse->status->successcredit = $respuestaCredit->fundtransferresponse->status->success;
                $respuestaCredit = json_encode($respuestaCredit);
                print_r($respuestaCredit);
            } else {
                $respuestaDebit = json_decode($respuestaDebit);
                $respuestaDebit->fundtransferresponse->status->successdebit = false;
                $respuestaDebit->fundtransferresponse->status->successcredit = false;

                $respuestaDebit = json_encode($respuestaDebit);
                print_r($respuestaDebit);
            }
        }

        if ($amount >= 0 && $isrefund == false && $debitandcredit == false) {
            $response = $Habanero->Credit($keyname, $amount, $gameinstanceid, $transferid, json_encode($datos));
        }
        if ($amount < 0 && $isrefund == false && $debitandcredit == false) {
            $amount = ($amount * -1);

            $response = ($Habanero->Debit($keyname, $amount, $gameinstanceid, $transferid, json_encode($datos), $freespin));
        }


        if ($isrefund == true) {
            $transferid = $data->fundtransferrequest->funds->refund->transferid;
            $amount = floatval($data->fundtransferrequest->funds->refund->amount);
            $dtevent = $data->fundtransferrequest->funds->refund->dtevent;
            $currencycode = $data->fundtransferrequest->funds->refund->currencycode;
            $gamestatemode = $data->fundtransferrequest->funds->refund->gamestatemode;
            $originaltransferid = $data->fundtransferrequest->funds->refund->originaltransferid;
            $bonusamount = $data->fundtransferrequest->funds->refund->bonusamount;
            $jpwin = $data->fundtransferrequest->funds->refund->jpwin;
            $jpcont = $data->fundtransferrequest->funds->refund->jpcont;
            $isbonus = $data->fundtransferrequest->funds->refund->isbonus;
            $initialdebittransferid = $data->fundtransferrequest->funds->refund->initialdebittransferid;
            $accounttransactiontype = $data->fundtransferrequest->funds->refund->accounttransactiontype;
            $gameinfeature = $data->fundtransferrequest->funds->refund->gameinfeature;

            $response = $Habanero->Rollback($amount, $gameinstanceid, $originaltransferid, $accountid, json_encode($datos));
        }

        if ($isrecredit == true && $isretry == true) {
        }

        print_r($response);
    }

    if (strpos($data->type, "queryrequest") !== false) {
        $dtsent = $data->dtsent;

        $brandgameid = $data->basegame->brandgameid;
        $keyname = $data->basegame->keyname;

        $username = $data->auth->username;
        $passkey = $data->auth->passkey;
        $machinename = $data->auth->machinename;
        $locale = $data->auth->locale;
        $brandid = $data->auth->brandid;

        $transferid = $data->queryrequest->transferid;
        $token = $data->queryrequest->token;
        $partnermeta = $data->queryrequest->partnermeta;
        $gameinstanceid = $data->queryrequest->gameinstanceid;
        $friendlygameinstanceid = $data->queryrequest->friendlygameinstanceid;
        $accountid = $data->queryrequest->accountid;
        $queryamount = $data->queryrequest->queryamount;

        $sign = $_REQUEST["sign"];

        /* Procesamos */
        $Habanero = new Habanero($token, $sign, $accountid);

        if ($queryamount < 0) {
            $response = ($Habanero->DebitQuery($transferid));
        } else {
            $response = ($Habanero->CreditQuery($transferid));
        }

        print_r($response);
    }

    if (strpos($URI, "logout") !== false) {
        $logout = $data;

        $BrandId = $data->BrandId;
        $APIKey = $data->APIKey;
        $Username = $data->Username;
        $password = $data->args->password;
        $currencycode = $data->args->currencycode;
        $token = $data->args->token;

        $Habanero = new Habanero($token, $sign);

        $response = ($Habanero->logout());

        print_r($response);
    }

    $log = "";
    $log = $log . "/" . time();
    $log = $log . "\r\n" . "-------------------------" . "\r\n";
    $log = $log . ($response);
}
