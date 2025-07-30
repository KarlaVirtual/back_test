<?php

/**
 * Este archivo contiene un script para procesar y manejar solicitudes relacionadas con la API de casino 'Spribe'.
 * Incluye autenticación, obtención de información y operaciones como depósitos, retiros y reversión de transacciones.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed  $_REQUEST     Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed  $_ENV         Variable de entorno para habilitar el modo de depuración ["debugFixed"].
 * @var mixed  $_ENV         Variable de entorno para habilitar conexiones globales ["enabledConnectionGlobal"].
 * @var mixed  $_ENV         Variable de entorno para habilitar el tiempo de espera de bloqueo ["ENABLEDSETLOCKWAITTIMEOUT"].
 * @var string $URI          URI de la solicitud actual.
 * @var string $body         Cuerpo de la solicitud en formato JSON.
 * @var object $data         Datos decodificados del cuerpo de la solicitud.
 * @var string $log          Cadena utilizada para almacenar información de registro.
 * @var string $requestOrder Cadena que representa los parámetros de la solicitud ordenados.
 * @var string $hashOriginal Hash generado para validar la solicitud.
 * @var object $Spribe       Objeto de la clase Spribe para manejar operaciones específicas.
 * @var mixed  $response     Respuesta generada por las operaciones de la clase Spribe.
 * @var mixed  $respuesta    Respuesta generada por operaciones como depósito, retiro o reversión.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Spribe;

header('Content-type: application/json');

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = 1;

$URI = $_SERVER['REQUEST_URI'];

$body = json_encode($_REQUEST);
$body = file_get_contents('php://input');
if ($body != "") {
    $data = json_decode($body);
}

if (true) {
    if (strpos($URI, "/auth") !== false) {
        $hash = $data->hash;
        $user_token = $data->user_token;
        $session_token = $data->session_token;
        $platform = $data->platform;
        $currency = $data->currency;

        /* Procesamos */
        $Spribe = new Spribe($user_token, "");
        $response = $Spribe->auth($user_token, $session_token, $platform, $currency); //No son necesarios los argumentos
    } elseif (strpos($URI, "/info") !== false) {

        $user_id = $data->user_id;
        $session_token = $data->session_token;
        $hash = $data->hash;
        $currency = $data->currency;

        /* Procesamos */
        $Spribe = new Spribe($session_token, $user_id);
        $response = $Spribe->info($user_id, $session_token, $currency);
    } else {

        $user_id = $data->user_id;
        $currency = $data->currency;
        $amount = floatval(round($data->amount, 3) / 1000);
        $provider = $data->provider;
        $provider_tx_id = $data->provider_tx_id;
        $game = $data->game;
        $action = $data->action;
        $action_id = $data->action_id;
        $session_token = $data->session_token;
        $platform = $data->platform;
        $hash = $data->hash;
        $datos = $data;

        if (strpos($URI, "/withdraw")) {

            $Spribe = new Spribe($session_token, $user_id);
            $response = $Spribe->Debit($game, $amount, $action_id, $provider_tx_id, $provider, json_encode($datos));
        } elseif (strpos($URI, "/deposit")) {

            if ($action == "freebet" || $action == "rainfreebet" || $action == "promofreebet") {
                $isbonus = true;
            } else {
                $isbonus = false;
            }

            $Spribe = new Spribe($session_token, $user_id);

            if ($action == "freebet" || $action == "rainfreebet" || $action == "promofreebet") {
                $response = $Spribe->Debit($game, 0, $action_id, 'FS' . $provider_tx_id, $provider, $provider_tx_id, json_encode($datos));
            }

            $response = $Spribe->Credit($game, $amount, $action_id, $provider_tx_id, json_encode($datos), $isbonus, $provider);
        } elseif (strpos($URI, "/rollback")) {
            $rollback_provider_tx_id = $data->rollback_provider_tx_id;
            $action_id = $data->provider_tx_id;
            $action_id = $data->provider_tx_id;

            $Spribe = new Spribe($session_token, $user_id);
            $response = $Spribe->Rollback($amount, $action_id, $rollback_provider_tx_id, $user_id, json_encode($datos), $provider);
        }
    }

    print_r($response);
}
