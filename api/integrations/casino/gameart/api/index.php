<?php

/**
 * Este archivo contiene un script para procesar y gestionar las integraciones
 * con el proveedor de juegos GameArt en un entorno de casino.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 * @access     público
 *
 * Variables globales utilizadas en el script:
 *
 * @var mixed   $_REQUEST Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var boolean $_ENV     Indica si el modo de depuración está habilitado['debug'].
 * @var integer $_ENV     Controla la conexión global habilitada ["enabledConnectionGlobal"].
 * @var string  $_ENV     Configuración para el tiempo de espera de bloqueo ["ENABLEDSETLOCKWAITTIMEOUT"].
 * @var mixed   $body     Contiene el cuerpo de la solicitud en formato JSON.
 * @var mixed   $data     Datos decodificados del cuerpo de la solicitud.
 * @var string  $URI      URI de la solicitud actual.
 * @var mixed   $response Respuesta generada por las operaciones realizadas.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\casino\GameArt;

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

$ConfigurationEnvironment = new ConfigurationEnvironment();
$UsuarioMandante = new \Backend\dto\UsuarioMandante($_GET["remote_id"]);

$VarAxu = explode('/?', $URI);
$VarAxu2 = explode('&key', $VarAxu[1]);
$body = $VarAxu2[0];

$sign = $_GET["key"];
$userId = $_GET["remote_id"];

$GameArt = new GameArt('', $sign, $userId);
$PESignature = $GameArt->getSignature($body);

if ($PESignature == $_GET["key"]) {
    if (strpos($_GET["action"], "balance") !== false) {
        $userId = $_GET["remote_id"];
        $session = $_GET["session_id"];

        /* Procesamos */
        $GameArt = new GameArt('', $sign, $userId);
        $response = ($GameArt->getBalance());
    }

    if (strpos($_GET["action"], "credit") !== false) {
        $userId = $_GET["remote_id"];
        $transactionId = $_GET["transaction_id"];
        $session = $_GET["session_id"];
        $roundid = $_GET["round_id"];;
        $CreditAmount = round($_GET["amount"], 2);
        $game_id = $_GET["game_id"];;

        $freespin = false;
        $action_type = $_GET["action_type"];
        if ($action_type == "win_free") {
            $freespin = true;
        }

        $datos = $data;

        /* Procesamos */
        $GameArt = new GameArt('', $sign, $userId);
        $response = $GameArt->Credit($game_id, $CreditAmount, $roundid, $transactionId, json_encode($datos));
    }

    if (strpos($_GET["action"], "debit") !== false) {
        $userId = $_GET["remote_id"];
        $transactionId = $_GET["transaction_id"];
        $session = $_GET["session_id"];
        $roundid = $_GET["round_id"];;
        $DebitAmount = round($_GET["amount"], 2);
        $game_id = $_GET["game_id"];;

        $freespin = false;
        $action_type = $_GET["action_type"];
        if ($action_type == "bet_free") {
            $freespin = true;
        }

        $datos = $data;

        /* Procesamos */
        $GameArt = new GameArt('', $sign, $userId);
        $response = ($GameArt->Debit($game_id, $DebitAmount, $roundid, $transactionId, json_encode($datos), $freespin));
    }

    if (strpos($_GET["action"], "rollback") !== false) {
        $userId = $_GET["remote_id"];
        $transactionId = $_GET["transaction_id"];
        $session = $_GET["session_id"];
        $roundid = $_GET["transaction_id"];
        $rollbackAmount = round($_GET["amount"], 2);

        $datos = $data;

        /* Procesamos */
        $GameArt = new GameArt('', $sign, $userId);
        $response = ($GameArt->Rollback($rollbackAmount, $roundid, $transactionId, $userId, json_encode($datos)));
    }
} else {
    $response = array(
        "status" => "error",
        "code" => "R_03",
        "message" => "Invalid HMAC",
        "action" => "void"

    );
    $response = json_encode($response);
}

print_r($response);
