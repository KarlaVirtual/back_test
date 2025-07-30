<?php

/**
 * Este archivo contiene un script para procesar solicitudes relacionadas con la API de casino 'IMOON',
 * incluyendo operaciones como autenticación de jugadores, apuestas, resultados y reversión de transacciones.
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
 * @var mixed   $_REQUEST     Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var string  $_ENV         Variable de entorno que habilita la conexión global ["enabledConnectionGlobal"].
 * @var string  $_ENV         Variable de entorno que habilita el tiempo de espera para bloqueos ["ENABLEDSETLOCKWAITTIMEOUT"].
 * @var string  $URI          URI de la solicitud actual.
 * @var string  $body         Cuerpo de la solicitud recibido en formato JSON.
 * @var mixed   $data         Datos decodificados del cuerpo de la solicitud.
 * @var string  $playerToken  Token del jugador recibido en la solicitud.
 * @var string  $playerId     Identificador del jugador.
 * @var string  $uniqueId     Identificador único de la transacción o sesión.
 * @var array   $array        Arreglo utilizado para almacenar respuestas estructuradas.
 * @var mixed   $respuesta    Respuesta generada por las operaciones de la clase Imoon.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Imoon;

header('Content-type: application/json');

$URI = $_SERVER['REQUEST_URI'];

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';

$body = file_get_contents('php://input');
if ($body != "") {
    $data = $body;
}

$data = json_decode($data);

if (true) {
    if (strpos($URI, "playerinfo") !== false) {
        $playerToken = $data->playerToken;
        $playerId = $data->playerId;
        $uniqueId = $data->uniqueId;

        /* Procesamos */
        $Imoon = new Imoon($playerToken, $playerId, $uniqueId);
        $response = $Imoon->Auth($playerId);
    } elseif (strpos($URI, "bet") !== false) {
        $uniqueId = $data->uniqueId;

        $array = array(
            'resultList' => array(),
            'uniqueId' => $uniqueId
        );

        foreach ($data->betList as $i => $items) {
            $betAmount = $items->betAmount;
            $betId = $items->betId;
            $currency = $items->currency;
            $gameId = $items->gameId;
            $playerId = $items->playerId;
            $playerToken = $items->playerToken;

            $Imoon = new Imoon($playerToken, $playerId, $uniqueId);
            $respuesta = $Imoon->Debit($gameId, $betAmount, $betId . '_R', $betId, json_encode($data), true, $currency);
        }

        array_push($array['resultList'], $respuesta);
        $response = $array;
    } elseif (strpos($URI, "result") !== false) {
        $uniqueId = $data->uniqueId;

        $array = array(
            'resultList' => array(),
            'uniqueId' => $uniqueId
        );

        foreach ($data->betList as $i => $items) {
            $betAmount = $items->betAmount;
            $betId = $items->betId;
            $currency = $items->currency;
            $extraData = $items->extraData;
            $gameId = $items->gameId;
            $playerId = $items->playerId;
            $playerToken = $items->playerToken;
            $status = $items->status;
            $winAmount = $items->winAmount;

            $Imoon = new Imoon($playerToken, $playerId, $uniqueId);
            $respuesta = $Imoon->Credit($gameId, $winAmount, $betId . '_R', $betId . '_C', json_encode($data), false, true, $currency, $betId);
        }

        array_push($array['resultList'], $respuesta);
        $response = $array;
    } elseif (strpos($URI, "rollback") !== false) {
        $uniqueId = $data->uniqueId;

        $array = array(
            'resultList' => array(),
            'uniqueId' => $uniqueId
        );

        foreach ($data->rollbackList as $i => $items) {
            $betId = $items->betId;
            $extraData = $items->extraData;
            $status = $items->status;
            $playerId = $items->playerId;
            $playerToken = $items->playerToken;
            $gameId = $items->gameId;

            $Imoon = new Imoon($playerToken, $playerId, $uniqueId);
            $respuesta = $Imoon->Rollback("", $betId, $betId, $playerId, json_encode($data), true);
        }

        array_push($array['resultList'], $respuesta);
        $response = $array;
    }

    echo json_encode($response);
}
