<?php

/**
 * Este archivo contiene el índice de la API de casino 'inbet', encargado de procesar solicitudes
 * relacionadas con transacciones de juegos, autenticación y operaciones de débito/crédito.
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
 * @var mixed   $_REQUEST      Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var integer $_ENV          Variable que habilita la conexión global ["enabledConnectionGlobal"].
 * @var string  $_ENV          Variable que habilita el tiempo de espera para el bloqueo ["ENABLEDSETLOCKWAITTIMEOUT"].
 * @var string  $log           Variable que almacena los datos de registro de la solicitud.
 * @var string  $body          Contenido del cuerpo de la solicitud HTTP.
 * @var mixed   $data          Datos decodificados del cuerpo de la solicitud en formato JSON.
 * @var integer $minus         Valor que representa una cantidad a debitar.
 * @var integer $plus          Valor que representa una cantidad a acreditar.
 * @var string  $token         Token de sesión enviado en la solicitud.
 * @var string  $gameId        Identificador del juego.
 * @var string  $uid           Identificador único de la partida.
 * @var string  $game          Nombre del juego.
 * @var string  $denomination  Denominación utilizada en la transacción.
 * @var integer $bet           Valor de la apuesta realizada.
 * @var integer $lines         Número de líneas jugadas.
 * @var mixed   $result        Resultado de la partida.
 * @var string  $transaccionId Identificador de la transacción.
 * @var mixed   $response      Respuesta generada por las operaciones realizadas.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Inbet;

$_ENV["enabledConnectionGlobal"] = 1;

$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . json_encode($_REQUEST);
$log = $log . trim(file_get_contents('php://input'));

$body = file_get_contents('php://input');

if ($body != "") {
    $data = json_decode($body);

    $minus = $data->minus;
    $plus = $data->plus;

    $token = $data->session;

    switch ($data->method) {
        case "do":

            $Inbet = new Inbet("", $token);

            if ($Inbet->isFun) {
                $minus = 0;
                $plus = 0;
            }

            $gameId = $data->tag->game_id;
            $uid = $data->tag->game_uuid;
            $game = $data->tag->game;
            $denomination = $data->tag->denomination;
            $bet = $data->tag->bet;
            $lines = $data->tag->lines;
            $result = $data->tag->result;
            $transaccionId = $data->trx_id;

            switch ($minus) {
                case 0:

                    switch ($plus) {
                        case 0 :


                            $response = $Inbet->Auth();

                            break;

                        default:

                            $response = $Inbet->Credit($gameId, $uid, $game, $plus, $denomination, $bet, $lines, $result, $transaccionId, $body);

                            break;
                    }

                    break;

                default:
                    $response = $Inbet->Debit($gameId, $uid, $game, $minus, $denomination, $bet, $lines, $result, $transaccionId, $body);

                    break;
            }
            print_r($response);
            exit;
    }
}



