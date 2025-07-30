<?php

/**
 * Este archivo contiene un script para procesar y generar un informe de cuotas totales
 * basado en datos de usuarios, transacciones y actividades relacionadas.
 *
 * @category   Casino
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
 * @var mixed $Country             Esta variable indica el país asociado a la operación.
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

ini_set('display_errors', 'OFF');

if (version_compare(phpversion(), '7.1', '>=')) {
    ini_set('precision', 17);
    ini_set('serialize_precision', -1);
}
require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Ezugin;

$_ENV["enabledConnectionGlobal"] = 1;

$URI = $_SERVER['REQUEST_URI'];
$body = file_get_contents('php://input');

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . "\r\n" . $URI . date("Y-m-d H:i:s") . "\r\n";
$log = $log . trim(file_get_contents('php://input'));

if ($body != "") {
    $data = json_decode($body);
}

$htext = 'HT';

/**
 * Obtiene los encabezados de la solicitud HTTP.
 *
 * Este método recorre las variables del servidor (\$_SERVER) para identificar
 * y extraer los encabezados HTTP enviados en la solicitud. Los nombres de los
 * encabezados se transforman a un formato estándar (con palabras capitalizadas
 * y separadas por guiones).
 *
 * @return array Un arreglo asociativo donde las claves son los nombres de los
 *               encabezados y los valores son sus respectivos contenidos.
 */
function getRequestHeaders()
{
    $headers = array();
    foreach ($_SERVER as $key => $value) {
        if (substr($key, 0, 5) <> 'HTTP_') {
            continue;
        }
        $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
        $headers[$header] = $value;
    }
    return $headers;
}

$headers = getRequestHeaders();
$hash_value = "";
foreach ($headers as $header => $value) {
    if ($header == "Hash") {
        $hash_value = $value;
    }
    $htext = $htext . "$header: $value <br />\n";
}

$hash_value_propio = base64_encode(hash_hmac('sha256', $body, "8856b34f-65bf-4158-87dd-420411d829dc", true));

$log = $log . "\r\n" . "-------------------------" . "\r\n";
$log = $log . "\r\n" . $URI . date("Y-m-d H:i:s") . "\r\n";
$log = $log . "\r\n" . $hash_value . "\r\n";
$log = $log . "\r\n" . $hash_value_propio . "\r\n";
$log = $log . trim(file_get_contents('php://input'));

if ($hash_value_propio == $hash_value || $_ENV['debug']) {
    if ($_REQUEST['test'] == 'auth') {
        $operatorId = $data->operatorId;
        $token = $data->token;

        /* Procesamos */
        $Ezugin = new Ezugin($operatorId, $token);
        $response = ($Ezugin->Auth());
    }

    if (strpos($URI, "auth") !== false) {
        $operatorId = $data->operatorId;
        $token = $data->token;

        /* Procesamos */
        $Ezugin = new Ezugin($operatorId, $token);
        $response = ($Ezugin->Auth());
    }


    if (strpos($URI, "credit") !== false) {
        if ($data->transactionId == "") {
            exit();
        }

        $operatorId = $data->operatorId;
        $token = $data->token;


        $gameId = $data->gameId;
        $uid = $data->uid;
        $betTypeID = $data->betTypeID;
        $currency = $data->currency;
        $creditAmount = $data->creditAmount;
        $serverId = $data->serverId;
        $roundId = $data->roundId;
        $transactionId = $data->transactionId;
        $hash = $data->hash;
        $gameDataString = $data->gameDataString;
        $isEndRound = $data->isEndRound;
        $creditIndex = $data->creditIndex;
        $seatId = $data->seatId;

        $debitTransactionId = $data->debitTransactionId;

        /* Procesamos */

        $Ezugin = new Ezugin($operatorId, $token, $uid);

        $response = ($Ezugin->Credit($gameId, $uid, $betTypeID, $currency, $creditAmount, $serverId, $roundId, $transactionId, $seatId, $gameDataString, $isEndRound, $creditIndex, $hash, $debitTransactionId));
    }

    if ($_REQUEST['test'] == 'credit') {
        $operatorId = $data->operatorId;
        $token = $data->token;


        $gameId = $data->gameId;
        $uid = $data->uid;
        $betTypeID = $data->betTypeID;
        $currency = $data->currency;
        $creditAmount = $data->creditAmount;
        $serverId = $data->serverId;
        $roundId = $data->roundId;
        $transactionId = $data->transactionId;
        $hash = $data->hash;
        $gameDataString = $data->gameDataString;
        $isEndRound = $data->isEndRound;
        $creditIndex = $data->creditIndex;
        $seatId = $data->seatId;


        /* Procesamos */

        $Ezugin = new Ezugin($operatorId, $token, $uid);

        $response = ($Ezugin->Credit($gameId, $uid, $betTypeID, $currency, $creditAmount, $serverId, $roundId, $transactionId, $seatId, $gameDataString, $isEndRound, $creditIndex, $hash));
    }

    if ($_REQUEST['test'] == 'debit') {
        $operatorId = $data->operatorId;
        $token = $data->token;


        $gameId = $data->gameId;
        $uid = $data->uid;
        $betTypeID = $data->betTypeID;
        $currency = $data->currency;
        $debitAmount = $data->debitAmount;
        $serverId = $data->serverId;
        $roundId = $data->roundId;
        $transactionId = $data->transactionId;
        $hash = $data->hash;
        $seatId = $data->seatId;

        if ($betTypeID == '6') {
            if (strpos($seatId, '-') === false) {
                $seatId = $seatId . '-2';
            }
        }

        /* Procesamos */

        $Ezugin = new Ezugin($operatorId, $token, $uid);

        $response = ($Ezugin->Debit($gameId, $uid, $betTypeID, $currency, $debitAmount, $serverId, $roundId, $transactionId, $seatId, $hash));
    }

    if (strpos($URI, "debit") !== false) {
        $operatorId = $data->operatorId;
        $token = $data->token;


        $gameId = $data->gameId;
        $uid = $data->uid;
        $betTypeID = $data->betTypeID;
        $currency = $data->currency;
        $debitAmount = $data->debitAmount;
        $serverId = $data->serverId;
        $roundId = $data->roundId;
        $transactionId = $data->transactionId;
        $hash = $data->hash;
        $hash = $_GET['hash'];
        $seatId = $data->seatId;

        if ($betTypeID == '6') {
            if (strpos($seatId, '-') === false) {
                $seatId = $seatId . '-2';
            }
        }

        /* Procesamos */


        $Ezugin = new Ezugin($operatorId, $token, $uid);
        $response = ($Ezugin->Debit($gameId, $uid, $betTypeID, $currency, $debitAmount, $serverId, $roundId, $transactionId, $seatId, $hash));
    }

    if (strpos($URI, "rollback") !== false) {
        $operatorId = $data->operatorId;
        $token = $data->token;


        $gameId = $data->gameId;
        $uid = $data->uid;
        $betTypeID = $data->betTypeID;
        $currency = $data->currency;
        $rollbackAmount = $data->rollbackAmount;
        $serverId = $data->serverId;
        $roundId = $data->roundId;
        $transactionId = $data->transactionId;
        $hash = $data->hash;
        $seatId = $data->seatId;

        if ($betTypeID == '6') {
            if (strpos($seatId, '-') === false) {
                $seatId = $seatId . '-2';
            }
        }

        /* Procesamos */

        $Ezugin = new Ezugin($operatorId, $token, $uid);

        $response = ($Ezugin->Rollback($gameId, $uid, $betTypeID, $currency, $rollbackAmount, $serverId, $roundId, $transactionId, $seatId, $hash));
    }


    if (strpos($URI, "roolback") !== false) {
        $operatorId = $data->operatorId;
        $token = $data->token;


        $gameId = $data->gameId;
        $uid = $data->uid;
        $betTypeID = $data->betTypeID;
        $currency = $data->currency;
        $rollbackAmount = $data->rollbackAmount;
        $serverId = $data->serverId;
        $roundId = $data->roundId;
        $transactionId = $data->transactionId;
        $hash = $data->hash;
        $seatId = $data->seatId;

        if ($betTypeID == '6') {
            if (strpos($seatId, '-') === false) {
                $seatId = $seatId . '-2';
            }
        }

        /* Procesamos */

        $Ezugin = new Ezugin($operatorId, $token, $uid);

        $response = ($Ezugin->Rollback($gameId, $uid, $betTypeID, $currency, $rollbackAmount, $serverId, $roundId, $transactionId, $seatId, $hash));
    }

    if ($_REQUEST['test'] == 'rollback') {
        $operatorId = $data->operatorId;
        $token = $data->token;


        $gameId = $data->gameId;
        $uid = $data->uid;
        $betTypeID = $data->betTypeID;
        $currency = $data->currency;
        $rollbackAmount = $data->rollbackAmount;
        $serverId = $data->serverId;
        $roundId = $data->roundId;
        $transactionId = $data->transactionId;
        $hash = $data->hash;
        $seatId = $data->seatId;

        if ($betTypeID == '6') {
            if (strpos($seatId, '-') === false) {
                $seatId = $seatId . '-2';
            }
        }

        /* Procesamos */

        $Ezugin = new Ezugin($operatorId, $token, $uid);

        $response = ($Ezugin->Rollback($gameId, $uid, $betTypeID, $currency, $rollbackAmount, $serverId, $roundId, $transactionId, $seatId, $hash));
    }


    $log = $log . "\r\n" . "-----------Response--------------" . date("Y-m-d H:i:s") . "\r\n";
    $log = $log . "\r\n" . $response . "\r\n";
    $log = $log . trim(file_get_contents('php://input'));

    print_r($response);
} else {
    if ($data != '' && $data != null) {
        $gameId = $data->gameId;
        $uid = $data->uid;
        $betTypeID = $data->betTypeID;
        $currency = $data->currency;
        $creditAmount = $data->creditAmount;
        $serverId = $data->serverId;
        $roundId = $data->roundId;
        $transactionId = $data->transactionId;
        $hash = $data->hash;
        $gameDataString = $data->gameDataString;
        $isEndRound = $data->isEndRound;
        $creditIndex = $data->creditIndex;
        $seatId = $data->seatId;

        $operatorId = $data->operatorId;
        $token = $data->token;
    }


    $return = array(

        "operatorId" => 10178001,
        "uid" => $uid,
        "token" => $token,
        "balance" => 0,
        "currency" => $currency,
        "roundId" => $roundId,

        "errorCode" => 1,
        "errorDescription" => "General Error. (Hash)",
        "timestamp" => (round(microtime(true) * 1000))
    );
    print_r(json_encode($return));
}
