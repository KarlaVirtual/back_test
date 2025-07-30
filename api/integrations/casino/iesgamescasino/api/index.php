<?php

/**
 * Este archivo contiene un script para procesar y manejar diversas operaciones relacionadas
 * con un casino, como autenticación, balance, créditos, débitos, y más.
 *
 * El script utiliza datos enviados a través de solicitudes HTTP, procesa la información
 * y genera respuestas en formato JSON. También interactúa con clases y métodos específicos
 * para realizar operaciones en el sistema del casino.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-11
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST            Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $Mandante            Esta variable se utiliza para almacenar y manipular el mandante asociado.
 * @var mixed $Country             Esta variable indica la cantidad total de elementos o registros, útil para iteraciones o validaciones.
 * @var mixed $AffiliateId         Variable que almacena el identificador de un afiliado.
 * @var mixed $BonoInternoMySqlDAO Objeto que maneja operaciones de base de datos para bonos internos en MySQL.
 * @var mixed $Transaction         Esta variable contiene información de una transacción, utilizada para el seguimiento y procesamiento de operaciones.
 * @var mixed $sql                 Variable que almacena una consulta SQL a ejecutar en la base de datos.
 * @var mixed $BonoInterno         Variable que representa un bono interno en el sistema.
 * @var mixed $Resultado           Variable que almacena el resultado de una operación o consulta.
 * @var mixed $array               Variable que almacena una lista o conjunto de datos.
 * @var mixed $index               Variable que representa un índice en una estructura de datos.
 * @var mixed $value               Esta variable se utiliza para almacenar y manipular valores asociados a claves.
 * @var mixed $item                Variable que almacena un elemento genérico en una lista o estructura de datos.
 * @var mixed $response            Esta variable almacena la respuesta generada por una operación o petición.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\IESGAMESCASINO;

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
        $IESGAMESCASINO = new IESGAMESCASINO($Token = "", $UserId);
        $response = $IESGAMESCASINO->Auth();
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
        $IESGAMESCASINO = new IESGAMESCASINO($Token = "", $UserId);
        $response = $IESGAMESCASINO->getBalance();
    }

    if (strpos($URI, "creditxbonus") !== false) {
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
        $IESGAMESCASINO = new IESGAMESCASINO($Token = "", $UserId);
        $response = $IESGAMESCASINO->Debit($GameCode, 0, $RoundId, "F.S" . $IESTransactionId, json_encode($datos), true);
        $response = $IESGAMESCASINO->Credit($GameCode, $CreditAmount, $RoundId, $IESTransactionId, json_encode($datos));
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
        $IESGAMESCASINO = new IESGAMESCASINO($Token = "", $UserId);
        $response = $IESGAMESCASINO->Credit($GameCode, $CreditAmount, $RoundId, $IESTransactionId, json_encode($datos));
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
        $IESGAMESCASINO = new IESGAMESCASINO($Token = "", $UserId);
        $response = $IESGAMESCASINO->Debit($GameCode, $DebitAmount, $RoundId, $IESTransactionId, json_encode($datos), $freespin);
    }

    if ($URI == "debitandcreditcode") {
        $token = explode(".", "$data->token");
        $token = base64_decode($token[1]);
        $json = json_decode($token);

        $user = explode(":", "$json->auth");
        $UserId = $user[1];

        $Token = "";
        $Code = $data->Code;
        $GameCode = $data->GameCode;
        $PlayerId = $data->PlayerId;
        $Currency = $data->Currency;
        $IESTransactionId = $data->IESTransactionId;
        $RoundId = $data->RoundId;
        $User = $data->User;
        $Pass = $data->ass;

        $freespin = false;
        $datos = $data;

        /* Procesamos */
        $IESGAMES = new IESGAMESCASINO($Token = "", $UserId);
        $response = $IESGAMES->Debit($GameCode, "", $RoundId, $IESTransactionId, json_encode($datos), $freespin);

        $response = json_decode($response);

        if ($response->status->success == true) {
            $datos = $data;

            /* Procesamos */
            $IESGAMESCASINO = new IESGAMESCASINO($Token = "", $UserId);
            $response = $IESGAMESCASINO->Credit($GameCode, "", $RoundId, $IESTransactionId, json_encode($datos));
        } else {
            $response = json_encode($response);
        }
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
        $IESGAMESCASINO = new IESGAMESCASINO($Token = "", $UserId);
        $response = $IESGAMESCASINO->Rollback($Amount, $RoundId, $IESRelatedTransactionId, $PlayerId, json_encode($datos));
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
        $IESGAMESCASINO = new IESGAMESCASINO($Token = "", $UserId);
        $response = ($IESGAMESCASINO->Debit($GameCode, "", $RoundId, $IESTransactionId, json_encode($datos), $freespin));
    }

    print_r($response);
}
