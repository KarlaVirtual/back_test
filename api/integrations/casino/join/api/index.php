<?php
/**
 * Este archivo contiene la implementación de la API de casino 'join', que permite realizar
 * operaciones relacionadas con la autenticación, balance, depósitos, retiros y otras acciones
 * específicas del sistema de casino.
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
 * @var mixed  $_REQUEST Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var int    $_ENV     Variable que habilita la conexión global en el entorno ["enabledConnectionGlobal"].
 * @var string $_ENV     Variable que habilita el tiempo de espera para el bloqueo ["ENABLEDSETLOCKWAITTIMEOUT"].
 * @var string $log      Variable que almacena los registros de las operaciones realizadas.
 * @var string $body     Contenido del cuerpo de la solicitud HTTP recibida.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\JoinBet;


$_ENV["enabledConnectionGlobal"] = 1;

$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';
$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . json_encode($_REQUEST);
$log = $log . trim(file_get_contents('php://input'));

$body = file_get_contents('php://input');

if ($body != "") {
    $data = json_decode($body)->data;


    $token = $data->token;

    switch ($data->method) {
        case "authenticate":

            $JoinBet = new JoinBet($token);

            $response = $JoinBet->Auth();

            $log = $log . "/" . time();
            $log = $log . "\r\n" . "-------------------------" . "\r\n";
            $log = $log . ($response);
            print_r($response);


            break;

        case "balance":

            $JoinBet = new JoinBet($token);

            $response = $JoinBet->getBalance();

            $log = $log . "/" . time();

            $log = $log . "\r\n" . "-------------------------" . "\r\n";
            $log = $log . ($response);


            print_r($response);


            break;

        case "withdraw":
            $gameId = $data->game_id;
            $uid = $data->round_id;
            $transaccionId = $data->transaction_id;
            $amount = $data->withdraw;


            $JoinBet = new JoinBet($token);

            $response = $JoinBet->Debit($gameId, $uid, $amount, $transaccionId, $body);

            $log = $log . "/" . time();

            $log = $log . "\r\n" . "-------------------------" . "\r\n";
            $log = $log . ($response);

            print_r($response);


            break;

        case "deposit":
            $gameId = $data->game_id;
            $uid = $data->round_id;
            $transaccionId = $data->transaction_id;
            $amount = $data->deposit;


            $JoinBet = new JoinBet($token);

            $response = $JoinBet->Credit($gameId, $uid, $amount, $transaccionId, $body);

            $log = $log . "/" . time();

            $log = $log . "\r\n" . "-------------------------" . "\r\n";
            $log = $log . ($response);

            print_r($response);


            break;
        case "withdrawDeposit":
            $gameId = $data->game_id;
            $uid = $data->round_id;
            $transaccionId = $data->transaction_id;
            $amount = $data->withdraw;


            $JoinBet = new JoinBet($token);

            $response = $JoinBet->Debit($gameId, $uid, $amount, $transaccionId, $body);

            $log = $log . "/" . time();

            $log = $log . "\r\n" . "-------------------------" . "\r\n";
            $log = $log . ($response);

            $amount = $data->deposit;

            $transaccionId = "joinwd" . $transaccionId;


            $response = $JoinBet->Credit($gameId, $uid, $amount, $transaccionId, $body);

            $log = $log . "/" . time();

            $log = $log . "\r\n" . "-------------------------" . "\r\n";
            $log = $log . ($response);


            print_r($response);


            break;
        case "cancel":
            $gameId = $data->game_id;
            $uid = $data->round_id;
            $transaccionId = $data->transaction_id;
            $amount = $data->deposit;


            $JoinBet = new JoinBet($token);

            $response = $JoinBet->Rollback("", "", "", $transaccionId, $body);

            $log = $log . "/" . time();

            $log = $log . "\r\n" . "-------------------------" . "\r\n";
            $log = $log . ($response);

            print_r($response);


            break;

        case "endSession":

            $JoinBet = new JoinBet($token);

            $response = $JoinBet->EndSession();

            $log = $log . "/" . time();

            $log = $log . "\r\n" . "-------------------------" . "\r\n";
            $log = $log . ($response);

            print_r($response);


            break;
    }
}



