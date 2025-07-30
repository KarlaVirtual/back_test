<?php

/**
 * Este archivo contiene la implementación de un API para la integración con el sistema Xpress.
 * Proporciona múltiples endpoints para manejar operaciones como autenticación, balance,
 * débito, crédito, y reversión de transacciones.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST        Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $_ENV            Variable superglobal que contiene el entorno de variables del sistema.
 * @var mixed $params          Variable que contiene los parámetros de una solicitud, generalmente en forma de array.
 * @var mixed $URI             Esta variable contiene el URI de la petición actual.
 * @var mixed $_SERVER         Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $method          Variable que almacena el método de pago o de ejecución de una acción.
 * @var mixed $log             Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $URI__           Variable que almacena una URI genérica.
 * @var mixed $data            Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $token           Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $sign            Variable que almacena una firma digital o de seguridad.
 * @var mixed $requestId       Variable que almacena el identificador de la solicitud.
 * @var mixed $Xpress          Variable que almacena información relacionada con Xpress.
 * @var mixed $response        Esta variable almacena la respuesta generada por una operación o petición.
 * @var mixed $DebitAmount     Variable que almacena el monto debitado de una cuenta o transacción.
 * @var mixed $GameCode        Variable que almacena el código de un juego.
 * @var mixed $PlayerId        Variable que almacena el identificador único de un jugador.
 * @var mixed $transactionId   Variable que almacena el identificador único de una transacción.
 * @var mixed $RoundId         Variable que almacena el identificador de una ronda de juego.
 * @var mixed $type            Esta variable se utiliza para almacenar y manipular el tipo.
 * @var mixed $bets            Variable que almacena una lista de apuestas.
 * @var mixed $descriptions    Variable que almacena descripciones.
 * @var mixed $event           Variable que almacena un evento específico dentro de un sistema o proceso.
 * @var mixed $bet             Variable que almacena la apuesta realizada en un juego o evento.
 * @var mixed $events          Variable que almacena una lista de eventos.
 * @var mixed $winnings        Variable que almacena ganancias.
 * @var mixed $infoTicket      Variable que almacena información detallada del ticket.
 * @var mixed $datos           Variable que almacena datos genéricos.
 * @var mixed $CreditAmount    Variable que almacena el monto acreditado a una cuenta o transacción.
 * @var mixed $gameCycleClosed Variable que indica si el ciclo del juego ha finalizado.
 * @var mixed $ticketPv        Variable que almacena el punto de venta del ticket.
 * @var mixed $currency        Variable que almacena la moneda utilizada en una transacción.
 * @var mixed $rollbackAmount  Variable que almacena el monto de reversión.
 * @var mixed $date            Variable que almacena una fecha genérica.
 * @var mixed $player          Variable que almacena información del jugador.
 * @var mixed $roundId         Variable que almacena el identificador de la ronda.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\virtual\Xpress;

header('Content-type: application/json');

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';

$params = $_REQUEST['t'];
$params = file_get_contents('php://input');

$URI = $_SERVER['REQUEST_URI'];
$method = $URI;

$log = "/";
$log = $log . "\r\n" . "-------------Request------------" . "\r\n";
$log = $log . ($URI) . "\r\n";
$log = $log . $params;
fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

$URI__ = explode('/', $URI);
$URI__ = $URI__[count($URI__) - 1];

$data = json_decode($params);

if ( ! empty($params)) {
    if (strpos($URI, "login") !== false) {
        $token = $data->token;
        $sign = $data->fingerprint;
        $requestId = $data->requestId;

        /* Procesamos */
        $Xpress = new Xpress($token, $sign, $requestId);
        $response = $Xpress->Auth();
    }

    if (strpos($URI, "balance") !== false) {
        $token = $data->sessionId;
        $sign = $data->fingerprint;
        $requestId = $data->requestId;

        /* Procesamos */
        $Xpress = new Xpress($token, $sign, $requestId);
        $response = $Xpress->getBalance();
    }

    if (strpos($URI, "logout") !== false) {
        $token = $data->sessionId;
        $sign = $data->fingerprint;

        /* Procesamos */
        $Xpress = new Xpress($token, $sign);
        $response = $Xpress->logout();
    }

    if (strpos($URI, "debit") !== false) {
        $token = $data->sessionId;
        $DebitAmount = ($data->transactionAmount);
        $GameCode = $data->gameId;

        $PlayerId = $data->playerId;
        $transactionId = $data->transactionId;

        $RoundId = $data->gameCycle;

        if ($PlayerId == '51258') {
            exit();
        }

        // Validar si el parámetro systemBets existe y está vacío
        if (isset($data->extData->ticket->details->systemBets)) {
            if (empty($data->extData->ticket->details->systemBets)) {
                $type = 'Simple';
            } else {
                $type = 'Combinada';
            }
        } else {
            $type = 'Simple';
        }

        $bets = 0;
        // Validar si el parámetro events existe y está vacío
        if (isset($data->extData->ticket->details->events)) {
            if ( ! empty($data->extData->ticket->details->events)) {
                $descriptions = [];
                foreach ($data->extData->ticket->details->events as $event) {
                    $descriptions[] = $event->playlistDescription;
                    foreach ($event->bets as $bet) {
                        $bets += $bet->oddValue;
                    }
                }
                $events = implode(',', $descriptions);
            } else {
                $events = '0';
            }
        } else {
            $events = '0';
        }

        // Validar si el parámetro winningData existe y está vacío
        if (isset($data->extData->ticket->winningData)) {
            if ( ! empty($data->extData->ticket->winningData)) {
                $winnings = $data->extData->ticket->winningData->maxWinning;
            } else {
                $winnings = 0;
            }
        } else {
            $winnings = 0;
        }

        $infoTicket = [
            'type' => $type,
            'events' => $events,
            'bets' => $bets,
            'winnings' => $winnings,
        ];

        unset($data->extData->ticket->unit->extData);
        unset($data->extData->ticket->sellStaff->extData);
        unset($data->extData->ticket->extData);
        $datos = $data;
        $datos->infoTicket = $infoTicket;

        /* Procesamos */
        $Xpress = new Xpress($token, $sign, '', $PlayerId);
        $response = $Xpress->Debit($GameCode, $DebitAmount, $RoundId, $transactionId, json_encode($datos), json_encode($infoTicket));
    }


    if (strpos($URI, "credit") !== false) {
        $token = $data->sessionId;
        $CreditAmount = ($data->transactionAmount);
        $GameCode = $data->gameId;

        $PlayerId = $data->playerId;
        $transactionId = $data->transactionId;

        $RoundId = $data->gameCycle;
        $gameCycleClosed = $data->gameCycleClosed;

        if ($gameCycleClosed == "true" || $gameCycleClosed == 1 || $gameCycleClosed == true) {
            $gameCycleClosed = true;
        } else {
            $gameCycleClosed = false;
        }

        $ticketPv = $data->extData->ticketId;

        $datos = $data;

        /* Procesamos */
        $Xpress = new Xpress($token, $sign, '', $PlayerId);
        $response = $Xpress->Credit($GameCode, $CreditAmount, $RoundId, $transactionId, json_encode($datos), $gameCycleClosed, $ticketPv);
    }

    if (strpos($URI, "RollFIX") !== false) {
        error_reporting(E_ALL);
        ini_set("display_errors", "ON");
        $sign = $data->sign;
        $token = $data->token;

        $currency = $data->currency;
        $rollbackAmount = 0;
        $date = $data->date;
        $player = $data->PlayerId;
        $roundId = $data->RGSRelatedTransactionId;
        $transactionId = $data->RGSRelatedTransactionId;

        $datos = $data;

        /* Procesamos */
        $Xpress = new Xpress($token, $sign);
        $response = $Xpress->Rollback($rollbackAmount, $roundId, $transactionId, $player, json_encode($datos));
    }

    if (strpos($URI, "rollback") !== false) {
        $sign = $data->sign;
        $token = $data->sessionId;

        $currency = $data->currency;
        $rollbackAmount = $data->transactionAmount;
        $date = $data->date;
        $player = $data->gameId;
        $roundId = $data->gameCycle;
        $transactionId = $data->transactionId;

        $PlayerId = $data->playerId;

        $datos = $data;

        /* Procesamos */
        $Xpress = new Xpress($token, $sign, '', $PlayerId);
        $response = $Xpress->Rollback($rollbackAmount, $roundId, $transactionId, $player, json_encode($datos));
    }

    $log = "";
    $log = $log . "\r\n" . "--------------Response-$URI__-----------" . "\r\n";
    $log = $log . $response;
    $log = $log . "\r\n" . "---------------------------------" . "\r\n";
    fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

    echo $response;
}
