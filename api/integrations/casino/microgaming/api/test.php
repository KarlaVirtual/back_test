<?php
/**
 * Este archivo contiene un script para procesar y generar un informe de cuotas totales
 * basado en datos de usuarios, transacciones y actividades relacionadas.
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
 * @var mixed $_REQUEST       Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $callerId       Identificador del llamador enviado en la solicitud.
 * @var mixed $callerPassword Contraseña del llamador enviada en la solicitud.
 * @var mixed $callerPrefix   Prefijo del llamador enviado en la solicitud.
 * @var mixed $action         Acción solicitada enviada en la solicitud.
 * @var mixed $remote_id      Identificador remoto enviado en la solicitud.
 * @var mixed $username       Nombre de usuario enviado en la solicitud.
 * @var mixed $session_id     Identificador de sesión enviado en la solicitud.
 * @var mixed $provider       Proveedor enviado en la solicitud.
 * @var mixed $game_id        Identificador del juego enviado en la solicitud.
 * @var mixed $gamesession_id Identificador de la sesión de juego enviado en la solicitud.
 * @var mixed $key            Clave enviada en la solicitud.
 * @var mixed $action_type    Tipo de acción enviada en la solicitud.
 * @var mixed $amount         Monto enviado en la solicitud.
 * @var mixed $transaction_id Identificador de la transacción enviado en la solicitud.
 * @var mixed $round_id       Identificador de la ronda enviado en la solicitud.
 * @var mixed $new_parameter  Nuevo parámetro enviado en la solicitud.
 * @var mixed $game_id_hash   Hash del identificador del juego enviado en la solicitud.
 * @var mixed $remote_data    Datos remotos enviados en la solicitud.
 * @var mixed $gameplay_final Indicador de finalización del juego enviado en la solicitud.
 */

error_reporting(E_ALL);
ini_set('display_errors', 'ON');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\IGPSERVICES;


$IGPSERVICES = new IGPSERVICES();

syslog(LOG_WARNING, "LLEGO: IGP" . json_encode($_REQUEST));
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

    syslog(LOG_WARNING, "RESPUESTA: IGP" . json_encode($auth));

    print_r($auth);
}

if (true) {
    $username = "user1";
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


    $action_type = $_REQUEST["action_type"];
    $amount = 0.05;
    $transaction_id = "gs-1868223925-07";
    $round_id = "2010878791";
    $new_parameter = $_REQUEST["new_parameter"];
    $gamesession_id = "gs_db6032-38311280";
    $game_id_hash = $_REQUEST["game_id_hash"];
    $remote_data = $_REQUEST["remote_data"];
    $gameplay_final = $_REQUEST["gameplay_final"];

    $game_id = 3419;

    $IGP = new \Backend\integrations\casino\IGP($username);


    $debit = $IGP->Debit($game_id, $remote_id, $amount, $transaction_id, $gameplay_final, $round_id, $remote_data, $session_id, $key, $gamesession_id, $game_id_hash);

    syslog(LOG_WARNING, "RESPUESTA: IGP" . json_encode($debit));

    print_r($debit);
}

if (false) {
    $username = "user1";
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


    $action_type = $_REQUEST["action_type"];
    $amount = 0.00;
    $transaction_id = "gs-1867990022-88";
    $round_id = "2010826428";
    $new_parameter = $_REQUEST["new_parameter"];
    $gamesession_id = "gs_a08fc0-38310778";
    $game_id_hash = $_REQUEST["game_id_hash"];
    $remote_data = $_REQUEST["remote_data"];
    $gameplay_final = "1";
    $game_id = 3419;
    $remote_id = 658475;

    $IGP = new \Backend\integrations\casino\IGP($username);


    $credit = $IGP->Credit($game_id, $remote_id, $amount, $transaction_id, $gameplay_final, $round_id, $remote_data, $session_id, $key, $gamesession_id, $game_id_hash);

    syslog(LOG_WARNING, "RESPUESTA: IGP" . json_encode($credit));

    print_r($credit);
}


