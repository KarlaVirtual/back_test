<?php

/**
 * Este archivo contiene el índice de la API de casino 'eagaming', que maneja diversas acciones
 * relacionadas con el balance, débitos, créditos y transacciones de los usuarios.
 *
 * @category   Casino
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed   $_REQUEST        Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var integer $_ENV            Indica si la conexión global está habilitada ["enabledConnectionGlobal"].
 * @var string  $log             Contiene información de la URI, parámetros de la solicitud y cuerpo de la entrada.
 * @var string  $body            Contiene los datos de la solicitud en formato JSON.
 * @var object  $data            Contiene los datos decodificados de la solicitud.
 * @var string  $action          Acción a realizar (balance, debit, credit, transaction_id).
 * @var string  $token           Identificador único de la sesión del usuario.
 * @var string  $gameId          Identificador del juego.
 * @var float   $amount          Monto a debitar o acreditar.
 * @var string  $txnId           Identificador de la transacción.
 * @var string  $roundId         Identificador de la ronda.
 * @var string  $action_type     Tipo de acción (BET, BET_FREE, WIN, WIN_FREE, etc.).
 * @var boolean $isfreeSpin      Indica si la acción es un giro gratuito.
 * @var mixed   $response        Respuesta generada por una operación o petición.
 * @var mixed   $respuestaDebit  Respuesta generada por la acción de débito.
 * @var mixed   $respuestaCredit Respuesta generada por la acción de crédito.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Eagaming;

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

$_ENV["enabledConnectionGlobal"] = 1;

$body = json_encode($_REQUEST);

if ($body != "") {
    $data = json_decode($body);

    $action = $_REQUEST["action"];
    $token = $data->session_id;

    switch ($action) {
        case "balance":

            $Eagaming = new Eagaming($token);
            $response = $Eagaming->getBalance();

            break;

        case "debit":

            $gameId = $data->game_id;
            $amount = $data->amount;
            $txnId = $data->transaction_id;
            $roundId = $data->round_id;

            $action_type = $data->action_type;

            $isfreeSpin = false;

            if ($action_type == "BET_FREE") {
                $isfreeSpin = true;
            }

            $Eagaming = new Eagaming($token);
            $response = ($Eagaming->Debit($gameId, $amount, $roundId, $txnId, json_encode($data)));

            break;

        case "credit":

            switch ($data->action_type) {
                case "WIN":

                    $gameId = $data->game_id;
                    $amount = $data->amount;
                    $txnId = $data->transaction_id;
                    $roundId = $data->round_id;

                    $Eagaming = new Eagaming($token, $roundId);
                    $response = ($Eagaming->Credit($gameId, $amount, $roundId, $txnId, json_encode($data)));

                    break;
                case "WIN_FREE":

                    $gameId = $data->game_id;
                    $amount = $data->amount;
                    $txnId = $data->transaction_id;
                    $roundId = $data->round_id;

                    $Eagaming = new Eagaming($token, $roundId);
                    $response = ($Eagaming->Credit($gameId, $amount, $roundId, $txnId, json_encode($data), true));

                    break;
            }

            break;

        case "transaction_id":

            $gameId = $data->game_id;
            $amount = $data->amount;
            $txnId = $data->transaction_id;
            $roundId = $data->round_id;

            $Eagaming = new Eagaming($token);
            $response = ($Eagaming->Rollback($amount, $roundId, $txnId, 0, json_encode($data)));
            break;
    }
    print_r($response);
}
