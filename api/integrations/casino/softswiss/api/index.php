<?php

/**
 * Este archivo contiene un script para procesar y manejar solicitudes relacionadas con la integración
 * de la API de casino 'SOFTSWISS'. Incluye operaciones como autenticación, apuestas, ganancias,
 * reversión de transacciones, manejo de giros gratis y consulta de saldo.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Davison Valencia <davison.valencia@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 * @access     public
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST  Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $URI       Contiene la URI de la solicitud actual.
 * @var mixed $body      Almacena el cuerpo de la solicitud HTTP.
 * @var mixed $data      Contiene los datos decodificados del cuerpo de la solicitud en formato JSON.
 * @var mixed $log       Variable utilizada para almacenar información de registro.
 * @var mixed $Softswiss Objeto que maneja las operaciones relacionadas con la integración de Softswiss.
 * @var mixed $Sign      Almacena la firma de la solicitud enviada en el encabezado HTTP.
 * @var mixed $array     Variable que almacena la respuesta generada por las operaciones realizadas.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Softswiss;

header('Content-type: application/json');
$_ENV["enabledConnectionGlobal"] = 1;

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

$URI = $_SERVER['REQUEST_URI'];

$body = file_get_contents('php://input');

if ($body != "") {
    $data = $body;
}

$data = json_decode($data);

$Softswiss = new Softswiss('', '', '', '');

$Sign = $_SERVER['HTTP_X_REQUEST_SIGN'];

if (true) {
    if (strpos($URI, "play") !== false) {
        $userId = $data->user_id;
        $gameId = $data->game;
        $roundId = $data->game_id;
        $finished = $data->finished ?? false;

        // Autenticación inicial
        $AutchSign = $Sign;
        $Softswiss = new Softswiss('', $userId, $AutchSign, $Sign);

        // Obtener saldo una sola vez
        $saldoData = $Softswiss->Auth($userId);
        $saldo = $saldoData['balance'] / 100;

        // Preparar respuesta base
        $response = [
            'balance' => 0,
            'game_id' => $roundId,
            'transactions' => [],
        ];

        // Verificación de saldo para apuestas
        $totalBet = 0;
        $hasBetAction = false;

        // Pre-calcular el total de apuestas
        foreach ($data->actions as $item) {
            if ($item->action === 'bet') {
                $totalBet += $item->amount / 100;
                $hasBetAction = true;
            }
        }

        // Validación de saldo suficiente
        if ($hasBetAction && $saldo < $totalBet) {
            $response = $Softswiss->convertError(20001, 'No credit');
        } else {
            $lastAction = 'Auth';
            $processError = false;

            // Procesar acciones
            foreach ($data->actions as $item) {
                if ($processError) break;

                $action = trim($item->action);
                $lastAction = $action ?: $lastAction;

                if (empty($action)) {
                    continue; // Saltar acciones vacías
                }

                $currency = $data->currency;
                $amount = $item->amount / 100;
                $transactionId = $item->action_id;

                switch ($action) {
                    case 'bet':
                        $result = $Softswiss->Debit($gameId, $amount, $roundId, $transactionId, json_encode($data), false, $currency);
                        break;

                    case 'win':
                        $result = $Softswiss->Credit($gameId, $amount, $roundId, $transactionId, json_encode($data), false, false, $currency);
                        break;

                    default:
                        continue 2; // Saltar acciones desconocidas
                }

                // Manejo de resultados
                if (!empty($result['code'])) {
                    if ($result['code'] == 5151) {
                        $balanceInfo = $Softswiss->getBalance($transactionId);
                        $response['transactions'][] = $balanceInfo;
                    } else {
                        $response = $result;
                        $processError = true;
                        continue;
                    }
                } else {
                    $response['transactions'][] = $result;
                }

                // Actualizar balance después de cada acción exitosa
                $balanceData = $Softswiss->Auth($userId);
                $response['balance'] = $balanceData['balance'];
            }

            // Si solo hubo acción de autenticación
            if (!$processError && $lastAction === 'Auth') {
                $response = $Softswiss->Auth($userId);
            }
        }

        $array = $response;
    } elseif (strpos($URI, "rollback") !== false) {
        // Inicialización de variables
        $userId = $data->user_id;
        $gameId = $data->game;
        $roundId = $data->game_id;
        $finished = $data->finished ?? false;

        // Autenticación y configuración inicial
        $AutchSign = $Sign;
        $Softswiss = new Softswiss('', $userId, $AutchSign, $Sign);

        // Estructura base de respuesta
        $response = [
            'balance' => 0,
            'game_id' => $roundId,
            'transactions' => [],
        ];

        // Procesar solo acciones de rollback
        foreach ($data->actions as $item) {
            if ($item->action !== 'rollback') {
                continue;
            }

            $currency = $data->currency;
            $transactionId = $item->action_id;
            $OrTId = $item->original_action_id;

            // Ejecutar rollback
            $rollbackResult = $Softswiss->Rollback("", $roundId, $transactionId, json_encode($data), false, $OrTId, $gameId);

            // Manejar respuesta
            if (!empty($rollbackResult['code'])) {
                if ($rollbackResult['code'] == 5050) {
                    $balanceInfo = $Softswiss->getBalance($transactionId);
                    $response['transactions'][] = $balanceInfo;
                } else {
                    $response = $rollbackResult;
                    break; // Salir en caso de error
                }
            } else {
                $response['transactions'][] = $rollbackResult;
            }

            // Actualizar balance
            $authData = $Softswiss->Auth($userId);
            $response['balance'] = max(0, $authData['balance']); // Asegurar que no sea negativo
        }

        $array = $response;
    } elseif (strpos($URI, "freespins") !== false) {

        $UserBonus = $data->issue_id;

        $rand = rand(1, 100);
        $result = explode('_', $UserBonus);

        $userB = $result[0];
        $userId = $result[1];
        $gameId = $result[2];
        $currency = $result[3];
        $amount = $data->total_amount / 100;
        $roundId = $userB . $userId . $rand;
        $transactionId = $userB . $userId . $rand;

        $AutchSign = $Sign;

        $Softswiss = new Softswiss('', $userId, $AutchSign, $Sign);

        $respuesta = $Softswiss->Debit($gameId, 0, $roundId . '_R', $transactionId, json_encode($data), false, $currency, true);

        if ($respuesta['code'] != '') {
            $array = $respuesta;
        } else {
            $respuesta = $Softswiss->Credit($gameId, $amount, $roundId . '_R', $transactionId . 'C', json_encode($data), false, false, $currency, true);
            if ($respuesta['code'] != '') {
                $array = $respuesta;
            } else {
                $respuesta = $Softswiss->Auth($userId, true);
                $array = $respuesta;
            }
        }
    } elseif (strpos($URI, "Balance") !== false) {

        $userId = $data->wallet_id;
        $currency = $data->currency;

        $AutchSign = $Sign;

        $Softswiss = new Softswiss('', $userId, $AutchSign, $Sign);

        $respuesta = $Softswiss->Auth($userId, false, true);
        $array = $respuesta;
    }

    echo json_encode($array);
}
