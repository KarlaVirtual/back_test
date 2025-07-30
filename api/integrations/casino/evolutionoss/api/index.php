<?php

/**
 * Este archivo contiene la implementación de la API para la integración con el casino 'Evolutionoss'.
 * Proporciona diferentes endpoints para manejar operaciones como autenticación, balance, débitos, créditos,
 * cancelaciones y transacciones promocionales relacionadas con usuarios y juegos.
 *
 * @category   API
 * @package    Integrations
 * @subpackage Casino
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-09
 * @access     público
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST  Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $_ENV      Variable superglobal que contiene variables de entorno del servidor.
 * @var mixed $URI       Contiene la URI de la solicitud actual.
 * @var mixed $body      Almacena el contenido del cuerpo de la solicitud en formato JSON.
 * @var mixed $query     Array que contiene los parámetros de consulta extraídos de la URI.
 * @var mixed $authToken Token de autenticación enviado en la solicitud.
 * @var mixed $data      Objeto decodificado del cuerpo de la solicitud, que contiene los datos enviados.
 * @var mixed $response  Variable que almacena la respuesta generada por las operaciones de la API.
 */

header('Content-type: application/json');

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

if (version_compare(phpversion(), '7.1', '>=')) {
    ini_set('precision', 17);
    ini_set('serialize_precision', -1);
}

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Evolutionoss;
$_ENV["enabledConnectionGlobal"] = 1;

$URI = $_SERVER['REQUEST_URI'];
$body = file_get_contents('php://input');

$url = parse_url($URI);
parse_str($url['query'], $query);
$authToken = isset($query['authToken']) ? $query['authToken'] : null;

$data = json_decode($body);

$Evolutionoss = new Evolutionoss('', '', '');
//$token_auth = $Evolutionoss->autchToken($data->userId, $data->sid);

$URI = explode('/', $URI);
$URI = $URI[count($URI) - 1];
$URI = explode('?', $URI);
$URI = $URI[0];
if($authToken ==''){

    if ($data != '' && $data != null) {
        $user = $data->userId;
        $token = $data->sid;
        $currency = $data->currency;
        $uid = $data->uuid;
    }

    $response = array(
        "status" => 'INVALID_TOKEN_ID',
        "balance" => 0,
        "bonus" => 0,
        "uuid" => $uid,
    );

    $response = json_encode($response);
}elseif (true) {
    if ($URI == "check") {
        $user = $data->userId;
        $token = $data->sid;
        $uid = $data->uuid;

        /* Procesamos */
        $Evolutionoss = new Evolutionoss($user, $token, $uid);
        $response = $Evolutionoss->Auth(false);
    } elseif ($URI == "sid") {
        $user = $data->userId;
        $token = $data->sid;
        $uid = $data->uuid;

        /* Procesamos */
        $Evolutionoss = new Evolutionoss($user, $token, $uid);
        $response = $Evolutionoss->Auth(true);
    } elseif ($URI == "balance") {
        $user = $data->userId;
        $token = $data->sid;
        $uid = $data->uuid;
        $gameId = $data->game;
        $currency = $data->currency;

        /* Procesamos */
        $Evolutionoss = new Evolutionoss($user, $token, $uid);
        $response = $Evolutionoss->Balance($gameId, $currency);
    } elseif ($URI == "debit") {
        $user = $data->userId;
        $token = $data->sid;
        $currency = $data->currency;
        $uid = $data->uuid;

        $gameId = $data->game->details->table->id;
        $debitAmount = $data->transaction->amount;
        $roundId = $data->transaction->refId;
        $transactionId = $data->transaction->id;

        /* Procesamos */
        $Evolutionoss = new Evolutionoss($user, $token, $uid);
        $response = $Evolutionoss->Debit($gameId, $debitAmount, $roundId, $transactionId, $data, false, $currency);
    } elseif ($URI == "credit") {
        $user = $data->userId;
        $token = $data->sid;
        $currency = $data->currency;
        $uid = $data->uuid;

        $gameId = $data->game->details->table->id;
        $winAmount = $data->transaction->amount;
        $roundId = $data->transaction->refId;
        $transactionId = $data->transaction->id;

        /* Procesamos */
        $Evolutionoss = new Evolutionoss($user, $token, $uid);
        $response = $Evolutionoss->Credit($gameId, $winAmount, $roundId, $transactionId, json_encode($data), false, true, $currency);
    } elseif ($URI == "cancel") {
        $user = $data->userId;
        $token = $data->sid;
        $currency = $data->currency;
        $uid = $data->uuid;

        $gameId = $data->game->details->table->id;
        $CancelAmount = $data->transaction->amount;
        $roundId = $data->transaction->refId;
        $transactionId = $data->transaction->id;

        /* Procesamos */
        $Evolutionoss = new Evolutionoss($user, $token, $uid);
        $response = $Evolutionoss->Rollback($CancelAmount, $roundId, $transactionId, $user, json_encode($data), false, $gameId);
    } elseif ($URI == "promo_payout") {
        $user = $data->userId;
        $token = $data->sid;
        $currency = $data->currency;
        $uid = $data->uuid;
        $game = $data->game;

        if ($game == null) {
            $gameId = 'DF_EVO';
        } else {
            $gameId = $data->game->details->table->id;
        }
        $winAmount = $data->promoTransaction->amount;
        $roundId = $data->promoTransaction->id;
        $transactionId = $data->promoTransaction->id;

        /* Procesamos */
        $Evolutionoss = new Evolutionoss($user, $token, $uid);
        $response = $Evolutionoss->Debit($gameId, 0, $roundId, 'P_P' . $transactionId, $data, false, $currency, 'promo_payout');

        $val = json_decode($response);
        if ($val->status == 'OK') {
            $response = $Evolutionoss->Credit($gameId, $winAmount, $roundId, $transactionId, json_encode($data), false, true, $currency);
        }
    } elseif ($URI == "promo_debit") {
        $user = $data->userId;
        $token = $data->sid;
        $currency = $data->currency;
        $uid = $data->uuid;
        $game = $data->game;

        if ($game == null) {
            $gameId = 'DF_EVO';
        } else {
            $gameId = $data->game->details->table->id;
        }
        $winAmount = $data->promoTransaction->amount;
        $roundId = $data->promoTransaction->refId;
        $transactionId = $data->promoTransaction->id;

        /* Procesamos */
        $Evolutionoss = new Evolutionoss($user, $token, $uid);
        $response = $Evolutionoss->Debit($gameId, 0, $roundId, 'P_P' . $transactionId, $data, false, $currency, 'promo_debit');
    } elseif ($URI == "promo_credit") {
        $user = $data->userId;
        $token = $data->sid;
        $currency = $data->currency;
        $uid = $data->uuid;
        $game = $data->game;

        if ($game == null) {
            $gameId = 'DF_EVO';
        } else {
            $gameId = $data->game->details->table->id;
        }
        $winAmount = $data->promoTransaction->amount;
        $roundId = $data->promoTransaction->refId;
        $transactionId = $data->promoTransaction->id;

        /* Procesamos */
        $Evolutionoss = new Evolutionoss($user, $token, $uid);
        $response = $Evolutionoss->Credit($gameId, $winAmount, $roundId, $transactionId, json_encode($data), false, true, $currency);
    } elseif ($URI == "promo_cancel") {
        $user = $data->userId;
        $token = $data->sid;
        $currency = $data->currency;
        $uid = $data->uuid;
        $game = $data->game;

        if ($game == null) {
            $gameId = 'DF_EVO';
        } else {
            $gameId = $data->game->details->table->id;
        }
        $CancelAmount = $data->promoTransaction->amount;
        $roundId = $data->promoTransaction->refId;
        $transactionId = $data->promoTransaction->id;

        /* Procesamos */
        $Evolutionoss = new Evolutionoss($user, $token, $uid);
        $response = $Evolutionoss->Rollback($CancelAmount, $roundId, 'P_P' . $transactionId, $user, json_encode($data), false, $gameId);
    } elseif ($URI == "tip_debit") {
        $user = $data->userId;
        $token = $data->sid;
        $currency = $data->currency;
        $uid = $data->uuid;

        $gameId = $data->game->details->table->id;
        $debitAmount = $data->transaction->amount;
        $roundId = $data->transaction->id;
        $transactionId = $data->transaction->id;

        /* Procesamos */
        $Evolutionoss = new Evolutionoss($user, $token, $uid);
        $response = $Evolutionoss->Debit($gameId, $debitAmount, $roundId, 'T_D' . $transactionId, $data, false, $currency, 'tip_debit');
    } elseif ($URI == "tip_close") {
        $user = $data->userId;
        $token = $data->sid;
        $uid = $data->uuid;

        if ($data->currency != null) {
            $currency = $data->currency;
        } else {
            $currency = 'NA';
        }

        $gameId = $data->game->details->table->id;
        $winAmount = 0;
        $roundId = $data->transaction->id;
        $transactionId = $data->transaction->id;

        /* Procesamos */
        $Evolutionoss = new Evolutionoss($user, $token, $uid);
        $response = $Evolutionoss->Credit($gameId, $winAmount, $roundId, 'T_C' . $transactionId, json_encode($data), false, true, $currency);
    } elseif ($URI == "tip_cancel") {
        $user = $data->userId;
        $token = $data->sid;
        $currency = $data->currency;
        $uid = $data->uuid;

        $gameId = $data->game->details->table->id;
        $CancelAmount = $data->transaction->amount;
        $roundId = $data->transaction->refId;
        $transactionId = $data->transaction->id;

        /* Procesamos */
        $Evolutionoss = new Evolutionoss($user, $token, $uid);
        $response = $Evolutionoss->Rollback($CancelAmount, $roundId, 'T_D' . $transactionId, $user, json_encode($data), false, $gameId);
    }
} else {
    if ($data != '' && $data != null) {
        $user = $data->userId;
        $token = $data->sid;
        $currency = $data->currency;
        $uid = $data->uuid;
    }

    $response = array(
        "status" => 'INVALID_TOKEN_ID',
        "balance" => 0,
        "bonus" => 0,
        "uuid" => $uid,
    );

    $response = json_encode($response);
}

echo $response;
