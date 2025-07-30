<?php

/**
 * Este archivo contiene la implementación de la API para la integración con el casino 'Booming'.
 * Proporciona endpoints para manejar callbacks y rollbacks relacionados con transacciones de juego.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST Contiene datos enviados a través del método REQUEST.
 * @var mixed $_ENV     Almacena variables de entorno utilizadas en la configuración.
 * @var mixed $URI      URI de la solicitud actual.
 * @var mixed $body     Cuerpo de la solicitud en formato JSON.
 * @var mixed $data     Datos decodificados del cuerpo de la solicitud.
 * @var mixed $datos    Alias para los datos decodificados.
 * @var mixed $log      Almacena información de registro para depuración.
 * @var mixed $response Respuesta generada por las operaciones de la API.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\casino\Booming;

header('Content-type: application/json');
if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

$URI = $_SERVER['REQUEST_URI'];
$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';


$body = file_get_contents('php://input');

if ($body != "") {
    $data = json_decode($body);
}

$datos = $data;

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . ($URI);
$log = $log . (http_build_query($_REQUEST));
$log = $log . json_encode($data);

if (true) {
    if (strpos($URI, "rollback_callback") !== false) {
        $session_id = $data->session_id;
        $player_id = $data->player_id;
        $round = $data->round;
        $type = $data->type;
        $debit = $data->debit;
        $credit = $data->credit;
        $operator_launch_data = $data->operator_launch_data;
        $round = $session_id . '_' . $round;

        $ends = $data->game_cycle->ends;
        $session_id2 = $data->game_cycle->root_round->session_id;
        $round2 = $data->game_cycle->root_round->round;

        $transactionId = $session_id . '-' . $round;
        /* Procesamos */
        $Booming = new Booming($session_id, $player_id);
        $response = ($Booming->Rollback($debit, $round, $transactionId, $player_id, json_encode($datos)));

        $transactionId = 'W' . $session_id . '-' . $round;
        /* Procesamos */
        $Booming = new Booming($session_id, $player_id);
        $response = ($Booming->Rollback($debit, $round, $transactionId, $player_id, json_encode($datos)));
    } elseif (strpos($URI, "callback") !== false) {
        $session_id = $data->session_id;
        $player_id = $data->player_id;
        $round = $data->round;
        $type = $data->type;
        $debit = $data->debit;
        $credit = $data->credit;
        $operator_launch_data = $data->operator_launch_data;
        $round = $session_id . '_' . $round;
        $ends = $data->game_cycle->ends;
        $session_id2 = $data->game_cycle->root_round->session_id;
        $round2 = $data->game_cycle->root_round->round;

        $isFreeSpin = false;
        if ($data->campaign->campaign_id != "") {
            $debit = 0;
            $isFreeSpin = true;
        }

        /* Procesamos */
        $Booming = new Booming($session_id, $player_id);

        $response = $Booming->Debit($debit, $round, $session_id . '-' . $round, json_encode($datos), $isFreeSpin);

        $transactionId = 'W' . $session_id . '-' . $round;

        if ($credit >= 0) {
            $response = $Booming->Credit($credit, $round, $transactionId, json_encode($datos), $isFreeSpin);
        }
    }


    $log = "";
    $log = $log . "/" . time();

    $log = $log . "\r\n" . "-------------------------" . "\r\n";
    $log = $log . ($response);

    print_r($response);
}