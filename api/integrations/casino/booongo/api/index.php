<?php

/**
 * Este archivo contiene un script para procesar y manejar solicitudes relacionadas con la integración
 * de la API del casino Booongo, incluyendo operaciones como login, consulta de saldo, transacciones,
 * reversión de transacciones y logout.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-09
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Booongo;

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . $_SERVER['REQUEST_URI'];
$log = $log . trim(file_get_contents('php://input'));

$body = file_get_contents('php://input');

$URI = $_SERVER['REQUEST_URI'];


if ($body != "") {
    $data = json_decode($body);


    if (strpos($data->name, "login") !== false) {
        $uid = $data->uid;
        $session = $data->session;
        $timestamp = $data->timestamp;
        $token = $data->args->token;
        $game = $data->args->game;
        $sign = $_REQUEST["sign"];

        /* Procesamos */
        $Booongo = new Booongo($token, $sign, $uid);
        $response = ($Booongo->Auth());

        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);

        print_r($response);
    }


    if (strpos($data->name, "getbalance") !== false) {
        $uid = $data->uid;
        $timestamp = $data->timestamp;
        $session = $data->session;
        $token = $data->args->token;
        $game = $data->args->game;
        $player_id = $data->args->player->id;
        $player_currency = $data->args->player->currency;

        /* Procesamos */
        $Booongo = new Booongo($token, $sign, $uid);
        $response = ($Booongo->getBalance());

        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);
        print_r($response);
    }

    if (strpos($data->name, "transaction") !== false) {
        $uid = $data->uid;
        $timestamp = $data->timestamp;
        $session = $data->session;
        $bet = floatval(round($data->args->bet, 2) / 100);
        $win = floatval(round($data->args->win, 2) / 100);
        $rounds = $data->args->rounds;
        $token = $data->args->token;
        $game = $data->args->game;
        $round_started = $data->args->round_started;
        $round_finished = $data->args->round_finished;
        $player = $data->args->player->id;
        $currency = $data->args->player->currency;

        $freebet_id = intval($data->args->freebet_id);
        $freebetDetailstype = intval($data->args->freebet_details->id);
        $freebetDetailstype = $data->args->freebet_details->type;
        $freebetDetailsSource = $data->args->freebet_details->source;
        $freebetDetailsPlace = $data->args->freebet_details->place;
        $freebetDetailsCampaign = $data->args->freebet_details->campaign;
        $freebetDetailsTotal_bet = floatval(round($data->args->freebet_details->total_bet, 2) / 100);
        $freebetDetailsTotal_rounds = floatval($data->args->freebet_details->total_rounds);
        $freebetDetailsRound_bet = floatval(round($data->args->freebet_details->round_bet, 2) / 100);
        $freebetDetailsStart_date = $data->args->freebet_details->start_date;
        $freebetDetailsEnd_date = $data->args->freebet_details->end_date;
        $freebetDetailsStatus = $data->args->freebet_details->status;
        $freebetDetailsPlayed_bet = floatval(round($data->args->freebet_details->played_bet, 2) / 100);
        $freebetDetailPlayed_win = floatval(round($data->args->freebet_details->played_win, 2) / 100);

        $award_id = $data->args->award_id;
        $award_detailsId = $data->args->award_details->id;
        $award_detailsType = $data->args->award_details->type;
        $award_detailsSource = $data->args->award_details->source;
        $award_detailsPlace = $data->args->award_details->place;
        $award_detailsAmount = floatval(round($data->args->award_details->amount, 2) / 100);
        $award_detailsStart_date = $data->args->award_details->start_date;
        $award_detailsEnd_date = $data->args->award_details->end_date;
        $award_detailsStatus = $data->args->award_details->status;


        $datos = $data;

        /* Procesamos */


        $Booongo = new Booongo($token, $sign, $uid);


        $freespin = false;

        if ($data->args->freebet_id != null && $data->args->freebet_id != '') {
            $freespin = true;
        }

        $respuestaDebit = ($Booongo->Debit($game, $bet, $session, $uid, json_encode($datos), $freespin));

        if (json_decode($respuestaDebit)->error == "") {
            $transactionId = "credit" . $uid;

            $datos = $data;

            /* Procesamos */


            $Booongo = new Booongo($token, $sign, $uid);


            $respuestaCredit = $Booongo->Credit($game, $win, $session, $transactionId, json_encode($datos));

            print_r($respuestaCredit);
        } else {
            print_r($respuestaDebit);
        }
    }


    if (strpos($data->name, "rollback") !== false) {
        $sign = $data->sign;

        $uid = $data->uid;
        $timestamp = $data->timestamp;
        $session = $data->session;
        $transactionId = $data->args->transaction_uid;
        $bet = floatval(round($data->args->bet, 2) / 100);
        $win = floatval(round($data->args->win, 2) / 100);
        $rounds = $data->args->rounds;
        $freebet_id = $data->args->freebet_id;
        $token = $data->args->token;
        $award_id = $data->args->award_id;
        $game = $data->args->game;
        $player = $data->args->player->id;
        $currency = $data->args->player->currency;
        $rollbackAmount = 0;
        $date = $data->date;


        $datos = $data;

        /* Procesamos */

        $Booongo = new Booongo($token, $sign, $uid);
        print_r($Booongo->Rollback($rollbackAmount, $session, $transactionId, $player, json_encode($datos)));
    }

    if (strpos($data->name, "logout") !== false) {
        $logout = $data;

        $uid = $data->uid;
        $timestamp = $data->timestamp;
        $session = $data->session;
        $transactionId = $data->args->transaction_uid;
        $reason = $data->args->reason;
        $token = $data->args->token;
        $game = $data->args->game;
        $player = $data->args->player->id;
        $currency = $data->args->player->currency;

        $Booongo = new Booongo($token, $sign, $uid);

        $response = ($Booongo->logout());
        $log = $log . "/" . time();

        $log = $log . "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
        $log = $log . ($response);

        print_r($response);
    }
}



