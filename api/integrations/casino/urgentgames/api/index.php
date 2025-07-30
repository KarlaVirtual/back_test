<?php

/**
 * Este archivo contiene un script para procesar solicitudes relacionadas con la API de casino 'UrgentGames'.
 * Maneja acciones como balance, crédito, débito y reversión de transacciones.
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
 * @var mixed   $_REQUEST    Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var boolean $_ENV        Indica si el modo de depuración está habilitado ['debug'].
 * @var integer $_ENV        Controla si la conexión global está habilitada ["enabledConnectionGlobal"].
 * @var string  $_ENV        Configuración para el tiempo de espera de bloqueo ["ENABLEDSETLOCKWAITTIMEOUT"].
 * @var string  $log         Almacena información de registro para depuración.
 * @var string  $body        Contiene el cuerpo de la solicitud en formato JSON.
 * @var object  $data        Decodificación del cuerpo de la solicitud en un objeto.
 * @var string  $URI         URI de la solicitud actual.
 * @var string  $sign        Firma de autenticación enviada en la solicitud.
 * @var string  $userId      Identificador único del usuario remoto.
 * @var string  $session     Identificador de sesión del usuario.
 * @var string  $game_id     Identificador del juego solicitado.
 * @var object  $UrgentGames Instancia de la clase UrgentGames para manejar operaciones.
 * @var string  $response    Respuesta generada por las operaciones realizadas.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\UrgentGames;

header('Content-type: application/json; charset=utf-8');

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . $_SERVER['REQUEST_URI'];
$log = $log . " Body ";
$log = $log . trim(file_get_contents('php://input'));

$body = file_get_contents('php://input');

$body = preg_replace("[\n|\r|\n\r]", "", $body);
$data = json_decode($body);
$URI = $_SERVER['REQUEST_URI'];

$VarAxu = explode('/?', $URI);
$VarAxu2 = explode('&key', $VarAxu[1]);
$body = $VarAxu2[0];

$sign = $_GET["key"];
$userId = $_GET["remote_id"];
$session = $_GET["session_id"];
$game_id = $_GET["game_id"];

$UrgentGames = new UrgentGames($session, $sign, $userId);
$PESignature = $UrgentGames->autchSign($body, $game_id);

if ($PESignature == $_GET["key"]) {
    if (strpos($_GET["action"], "balance") !== false) {
        $userId = $_GET["remote_id"];
        $session = $_GET["session_id"];

        /* Procesamos */
        $UrgentGames = new UrgentGames($session, $sign, $userId);
        $response = ($UrgentGames->getBalance());
    }

    if (strpos($_GET["action"], "credit") !== false) {
        $userId = $_GET["remote_id"];
        $transactionId = $_GET["transaction_id"];
        $session = $_GET["session_id"];
        $roundid = $_GET["round_id"];
        $CreditAmount = round($_GET["amount"], 2);
        $game_id = $_GET["game_id"];

        $freespin = false;
        $action_type = $_GET["action_type"];
        if ($action_type == "win_free") {
            $freespin = true;
        }

        $datos = $data;

        /* Procesamos */
        $UrgentGames = new UrgentGames($session, $sign, $userId);
        $response = $UrgentGames->Credit($game_id, $CreditAmount, $roundid, $transactionId, json_encode($datos));
    }

    if (strpos($_GET["action"], "debit") !== false) {
        $userId = $_GET["remote_id"];
        $transactionId = $_GET["transaction_id"];
        $session = $_GET["session_id"];
        $roundid = $_GET["round_id"];
        $DebitAmount = round($_GET["amount"], 2);
        $game_id = $_GET["game_id"];

        $freespin = false;
        $action_type = $_GET["action_type"];
        if ($action_type == "bet_free") {
            $freespin = true;
        }

        $datos = $data;

        /* Procesamos */
        $UrgentGames = new UrgentGames($session, $sign, $userId);
        $response = $UrgentGames->Debit($game_id, $DebitAmount, $roundid, $transactionId, json_encode($datos), $freespin);
    }

    if (strpos($_GET["action"], "rollback") !== false) {
        $userId = $_GET["remote_id"];
        $transactionId = $_GET["transaction_id"];
        $session = $_GET["session_id"];
        $roundid = $_GET["transaction_id"];
        $rollbackAmount = round($_GET["amount"], 2);
        $datos = $data;

        /* Procesamos */
        $UrgentGames = new UrgentGames('', $sign, $userId);
        $response = $UrgentGames->Rollback($rollbackAmount, $roundid, $transactionId, $userId, json_encode($datos));
    }
} else {
    $response = array(
        "status" => "500",
        "msg" => "Service Error",
    );
    $response = json_encode($response);
}

$log = "";
$log = $log . "/" . time();
$log = $log . "\r\n" . "-------------------------" . "\r\n";
$log = $log . ($response);

print_r($response);
