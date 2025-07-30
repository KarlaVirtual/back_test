<?php
/**
 * Este archivo contiene un script para procesar y manejar acciones relacionadas con la API de casino 'ipg',
 * incluyendo operaciones de balance, débito, crédito y reversión de transacciones.
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
 * @var mixed   $_REQUEST       Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var integer $_ENV           Variable que habilita la conexión global ["enabledConnectionGlobal"].
 * @var integer $_ENV           Variable que habilita el tiempo de espera para bloqueos ["ENABLEDSETLOCKWAITTIMEOUT"].
 * @var mixed   $IGPSERVICES    Objeto que maneja servicios relacionados con IGP.
 * @var mixed   $log            Variable utilizada para almacenar registros de log.
 * @var string  $callerId       Identificador del solicitante.
 * @var string  $callerPassword Contraseña del solicitante.
 * @var string  $callerPrefix   Prefijo del solicitante.
 * @var string  $action         Acción solicitada (balance, debit, credit, rollback).
 * @var string  $remote_id      Identificador remoto.
 * @var string  $username       Nombre de usuario.
 * @var string  $session_id     Identificador de sesión.
 * @var string  $provider       Proveedor del servicio.
 * @var string  $game_id        Identificador del juego.
 * @var string  $gamesession_id Identificador de la sesión de juego.
 * @var string  $key            Clave de autenticación.
 * @var string  $action_type    Tipo de acción (usado en debit, credit, rollback).
 * @var float   $amount         Monto de la transacción.
 * @var string  $transaction_id Identificador de la transacción.
 * @var string  $round_id       Identificador de la ronda.
 * @var mixed   $new_parameter  Parámetro adicional.
 * @var string  $game_id_hash   Hash del identificador del juego.
 * @var mixed   $remote_data    Datos remotos asociados.
 * @var integer $gameplay_final Indicador de finalización del juego.
 */

ini_set('display_errors', 'off');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\IGPSERVICES;


$_ENV["enabledConnectionGlobal"] = 1;

$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';
$IGPSERVICES = new IGPSERVICES();

$log = $log . "\r\n" . "-------------------------" . "\r\n";
$log = $log . json_encode($_REQUEST);
$callerId = $_REQUEST["callerId"];
$callerPassword = $_REQUEST["callerPassword"];
$callerPrefix = $_REQUEST["callerPrefix"];
$action = $_REQUEST["action"];
$remote_id = $_REQUEST["remote_id"];
$username = $_REQUEST["username"];
$session_id = $_REQUEST["session_id"];
$provider = $_REQUEST["provider"];
$game_id = $_REQUEST["game_id"];
$gamesession_id = $_REQUEST["gamesession_id"];
$key = $_REQUEST["key"];


if ($action == "balance") {
    $username = explode("user", $username)[1];

    $IGP = new \Backend\integrations\casino\IGP($username);


    $auth = $IGP->Auth();

    $log = "";
    $log = $log . "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "RESPUESTA: IGP" . ($auth);
    print_r($auth);
}

if ($action == "debit") {
    $username = explode("user", $username)[1];

    $action_type = $_REQUEST["action_type"];
    $amount = $_REQUEST["amount"];
    $transaction_id = $_REQUEST["transaction_id"];
    $round_id = $_REQUEST["round_id"];
    $new_parameter = $_REQUEST["new_parameter"];
    $gamesession_id = $_REQUEST["gamesession_id"];
    $game_id_hash = $_REQUEST["game_id_hash"];
    $remote_data = $_REQUEST["remote_data"];
    $gameplay_final = $_REQUEST["gameplay_final"];

    $IGP = new \Backend\integrations\casino\IGP($username);


    $debit = $IGP->Debit($game_id, $remote_id, $amount, $transaction_id, $gameplay_final, $round_id, $remote_data, $session_id, $key, $gamesession_id, $game_id_hash, json_encode($_REQUEST));


    $log = "";
    $log = $log . "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "RESPUESTA: IGP" . ($debit);

    print_r($debit);
}

if ($action == "credit") {
    $username = explode("user", $username)[1];

    $action_type = $_REQUEST["action_type"];
    $amount = $_REQUEST["amount"];
    $transaction_id = $_REQUEST["transaction_id"];
    $round_id = $_REQUEST["round_id"];
    $new_parameter = $_REQUEST["new_parameter"];
    $gamesession_id = $_REQUEST["gamesession_id"];
    $game_id_hash = $_REQUEST["game_id_hash"];
    $remote_data = $_REQUEST["remote_data"];
    $gameplay_final = $_REQUEST["gameplay_final"];

    $IGP = new \Backend\integrations\casino\IGP($username);


    $credit = $IGP->Credit($game_id, $remote_id, $amount, $transaction_id, $gameplay_final, $round_id, $remote_data, $session_id, $key, $gamesession_id, $game_id_hash, json_encode($_REQUEST));


    $log = "";
    $log = $log . "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "RESPUESTA: IGP" . ($credit);

    print_r($credit);
}

if ($action == "rollback") {
    $username = explode("user", $username)[1];

    $action_type = $_REQUEST["action_type"];
    $amount = $_REQUEST["amount"];
    $transaction_id = $_REQUEST["transaction_id"];
    $round_id = $_REQUEST["round_id"];
    $new_parameter = $_REQUEST["new_parameter"];
    $gamesession_id = $_REQUEST["gamesession_id"];
    $game_id_hash = $_REQUEST["game_id_hash"];
    $remote_data = $_REQUEST["remote_data"];
    $gameplay_final = $_REQUEST["gameplay_final"];

    $IGP = new \Backend\integrations\casino\IGP($username);


    $credit = $IGP->Rollback($game_id, $remote_id, $amount, $transaction_id, $gameplay_final, $round_id, $remote_data, $session_id, $key, $gamesession_id, $game_id_hash, json_encode($_REQUEST));


    $log = "";
    $log = $log . "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "RESPUESTA: IGP" . ($credit);

    print_r($credit);
}





