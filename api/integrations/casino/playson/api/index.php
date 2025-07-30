<?php

/**
* Este archivo contiene un script para procesar y manejar las solicitudes de la API del casino 'Playson'.
* Incluye operaciones como autenticación, consulta de balance, manejo de apuestas y reembolsos.
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
* @var mixed $_REQUEST Variable superglobal que contiene datos enviados a través del método REQUEST.
* @var mixed $_ENV     Indica si la conexión global está habilitada.
* @var mixed $_ENV     Define si se necesita un nivel de aislamiento en las transacciones.
* @var mixed $_ENV     Habilita el tiempo de espera para bloqueos en la base de datos.
* @var mixed $_ENV     Indica si las integraciones lentas están habilitadas.
* @var mixed $URI      Contiene la URI de la solicitud actual.
* @var mixed $body     Almacena el cuerpo de la solicitud en formato XML.
* @var mixed $method   Método HTTP utilizado en la solicitud.
* @var mixed $data     Objeto SimpleXML que representa los datos de la solicitud.
* @var mixed $log      Variable utilizada para almacenar registros de eventos y operaciones.
* @var mixed $response Almacena la respuesta generada por las operaciones de la API.
*/

ini_set('display_errors', 'OFF');
require(__DIR__ . '../../../../../vendor/autoload.php');
use Backend\integrations\casino\Playson;
if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Access-Control-Allow-Headers, Access-Control-Allow-Credentials,Authentication, Origin, X-Requested-With, Content-Type');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS,PUT');

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["NEEDINSOLATIONLEVEL"] = '1';
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = 1;
$_ENV['enabledSlowIntegrations'] = false;
$URI = $_SERVER['REQUEST_URI'] . " C " . $_SERVER['REQUEST_METHOD'];
$body = trim(file_get_contents('php://input'));
$method = "";

if ($body != "") {
    Header('Content-type: text/xml');
    $data = simplexml_load_string($body);
} else {
    exit();
}

$URI = $data->children()[0]->getName();

if (strpos($URI, 'enter') !== false) {
    $Enter = $data;
    $tokensession = (string) $Enter->attributes()->key;
    $session = (string) $Enter->attributes()->session;
    $proveedorId = (string) intval($Enter->enter->attributes()->id);
    $guid = (string) $Enter->enter->attributes()->guid;
    $key = (string) $Enter->enter->attributes()->key;
    $Playson = new Playson($key, '', '', "enter", '', $session, $proveedorId, $guid);
    $response = $Playson->Auth();
}

if (strpos($URI, "balance") !== false) {
    $Balance = $data;
    $proveedorId = (string) intval($Balance->getbalance->attributes()->id);
    $session = (string) $Balance->attributes()->session;
    $guid = (string) $Balance->getbalance->attributes()->guid;
    $Playson = new Playson($guid, "", '', "balance", '', $session, $proveedorId, $guid);
    $response = $Playson->getBalance();
}

if (strpos($URI, "roundbetwin") !== false) {
    $roundbetwin = $data;
    $proveedorId = (string) intval($roundbetwin->roundbetwin->attributes()->id);
    $guid = (string) $roundbetwin->roundbetwin->attributes()->guid;
    $DebitAmount = floatval((string) $roundbetwin->roundbetwin->attributes()->bet) / 100;
    $CreditAmount = floatval((string) $roundbetwin->roundbetwin->attributes()->win) / 100;
    $gameid = (string) $roundbetwin->roundbetwin->attributes()->gameid;
    $type = (string) $roundbetwin->roundbetwin->attributes()->type;
    $commandtype = (string) $roundbetwin->roundbetwin->attributes()->commandtype;
    $session = (string) $roundbetwin->attributes()->session;
    $RoundId = (string) $roundbetwin->roundbetwin->roundnum->attributes()->id;
    $transactionId = (string) $roundbetwin->roundbetwin->attributes()->id;
    $token = $guid;
    $freespin = false;

    if ($type == "freespin") {
        $DebitAmount = 0;
        $CreditAmount = strval(floatval($roundbetwin->roundbetwin->attributes()->win) / 100);
    }

    if ($type == "giftspin") {
        $DebitAmount = 0;
        $giftwin = strval($roundbetwin->roundbetwin->giftfin->attributes()->giftwin / 100);
        $CreditAmount = $giftwin;
    }

    if ($type == "ft") {
        $DebitAmount = 0;
        $giftwin = strval($roundbetwin->roundbetwin->giftfin->attributes()->giftwin / 100);
        $CreditAmount = $giftwin;
    }

    if ($type == "feature") {
        $featureWin = strval($roundbetwin->roundbetwin->feature->win->attributes()->win / 100);
        $CreditAmount = $featureWin;
    }

    if ($type == "bonus") {
        $DebitAmount = 0;
    }

    $gitspinOffer = strval($roundbetwin->roundbetwin->giftspin->attributes()->offer);

    if ($type == "spin" && $gitspinOffer != '') {
        $freespin = true;
        $DebitAmount = 0;
    }

    $datos = array(
        "id" => $proveedorId,
        "guid" => $guid,
        "bet" => $DebitAmount,
        "win" => $CreditAmount,
        "type" => $type,
        "commandtype" => $commandtype,
        "roundid" => $RoundId,
        "transactionId" => $transactionId
    );

    $Playson = new Playson($token, "", '', "roundbetwin", $gameid, $session, $proveedorId, $guid);
    $response = $Playson->DebitAndCredit("", $RoundId, $DebitAmount, $CreditAmount, $transactionId, $transactionId, json_encode($datos), $freespin);
}

if (strpos($URI, "refund") !== false) {
    $refund = $data;
    $token = (string) $refund->attributes()->key;
    $session = (string) $refund->attributes()->session;
    $proveedorId = (string) $refund->refund->attributes()->id;
    $guid = (string) $refund->refund->attributes()->guid;
    $cash = (string) intval($refund->refund->attributes()->cash);
    $cmd = (string) $refund->refund->storno->attributes()->cmd;
    $idAjuste = (string) $refund->refund->storno->attributes()->id;
    $wlid = (string) $refund->refund->storno->attributes()->wlid;
    $gameId = (string) $refund->refund->storno->attributes()->gameid;
    $guidStorno = (string) $refund->refund->storno->attributes()->guid;
    $roundId = (string) $refund->refund->storno->roundnum->attributes()->id;
    $transactionId = $roundId;
    $Playson = new Playson($token, '', '', "refund", '', $session, $proveedorId, $guid);
    $datos = array(
        "guid" => $guid,
        "cash" => $cash,
        "cmd" => $cmd,
        "idAjuste" => $idAjuste,
        "wlid" => $wlid,
        "gameId" => $gameId,
        "guidStorno" => $guidStorno,
        "roundId" => $roundId,
    );
    $response = $Playson->Rollback($gameId, $roundId, "", $cash, $transactionId, json_encode($datos));
}

if (strpos($URI, "logout") !== false) {
    $logout = $data;
    $proveedorId = (string) $logout->logout->attributes()->id;
    $guid = (string) $logout->logout->attributes()->guid;
    $session = (string) $logout->attributes()->session;
    $token = $guid;
    $Playson = new Playson($token, "", '', "logout", '', $session, $proveedorId, $guid);
    $response = $Playson->logout();
}

print_r($response);