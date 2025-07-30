<?php

/**
 * Este archivo contiene un script para procesar y manejar solicitudes relacionadas con la integración
 * de la API de casino 'AADVARK'. Incluye operaciones como autenticación, consulta de saldo,
 * colocación de apuestas, resolución de tickets, revocación de transacciones y manejo de datos significativos.
 *
 * @category   Integración
 * @package    API
 * @subpackage Aadvark
 * @author     Karla Ramirez <karla.ramirez@virtualsoft.tech>
 * @version    ninguna
 * @since      2025-02-19
 */

/**
 * Habilita el modo de depuración si se recibe un parámetro específico en la solicitud.
 *
 * @var string $_REQUEST ['DXbDpfykzqwS'] Clave de depuración.
 */
if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

header('Content-type: application/json');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Aadvark;

/**
 * Configuración de variables globales para la conexión y tiempo de espera.
 *
 * @var int    $_ENV ["enabledConnectionGlobal"] Habilita la conexión global.
 * @var string $_ENV ["ENABLEDSETLOCKWAITTIMEOUT"] Configura el tiempo de espera para bloqueos.
 */
$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';

$URI = $_SERVER['REQUEST_URI'];

$x_auth_signature = $_SERVER['HTTP_X_AUTH_SIGNATURE'];

/**
 * Registra la solicitud en un archivo de log.
 *
 * @var string $log Contenido del log que incluye URI, firma de autenticación y datos de la solicitud.
 */
$log = "\r\n" . "-------------Request------------" . "\r\n";
$log = $log . ($URI) . $x_auth_signature . "\r\n";
$log = $log . (http_build_query($_REQUEST));
$log = $log . trim(file_get_contents('php://input'));
// Guarda el log en un archivo.
fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

$body = file_get_contents('php://input');
$body = preg_replace("[\n|\r|\n\r]", "", $body);
$data = json_decode($body);

if (true) {
    /**
     * Maneja la autenticación de usuarios.
     *
     * @var string $token Token de autenticación proporcionado en la solicitud.
     */
    if (strpos($URI, "auth") !== false) {
        $token = $_REQUEST['token'];

        /* Procesamos */
        $Aadvark = new Aadvark($token, "");
        $response = ($Aadvark->Auth());
    }

    /**
     * Consulta el saldo de un usuario.
     *
     * @var string $UserId Identificador del usuario.
     */
    if (strpos($URI, "user") !== false) {
        $UserId = $data->userID;

        /* Procesamos */
        $Aadvark = new Aadvark('', $UserId);
        $response = ($Aadvark->getBalance());
    }

    /**
     * Procesa la colocación de apuestas.
     *
     * @var string $gameId   Identificador del juego.
     * @var string $roundId  Identificador de la ronda.
     * @var string $token    Token del usuario.
     * @var string $walletID Identificador de la billetera.
     * @var array  $bets     Lista de apuestas realizadas.
     */
    if (strpos($URI, "place") !== false) {
        $gameId = $data->provider;
        $roundId = $data->ticketUUID;
        $token = $data->userToken;
        $walletID = $data->walletID;

        $freespin = false;

        $datos = $data;

        foreach ($data->bets as $bets) {
            $transactionId = $bets->uuid;
            $Amount = $bets->stake;

            /* Procesamos */
            $Aadvark = new Aadvark($token, '');
            $response = $Aadvark->Debit($gameId, $Amount, $roundId, $transactionId, json_encode($datos), $freespin);
        }
    }

    /**
     * Resuelve un ticket de apuestas.
     *
     * @var string $roundId  Identificador de la ronda.
     * @var string $revision Revisión del ticket.
     * @var string $state    Estado del ticket.
     * @var string $cid      Identificador del cliente.
     * @var array  $bets     Lista de apuestas realizadas.
     */
    if (strpos($URI, "resolve-ticket") !== false) {
        $roundId = $data->ticketUUID;
        $revision = $data->revision;
        $state = $data->state;
        $cid = $data->cid;
        $datos = $data;

        foreach ($data->bets as $bets) {
            $transactionId = $bets->uuid;
            $WinAmount = $bets->returns;

            /* Procesamos */
            $Aadvark = new Aadvark('', '');
            $response = $Aadvark->Credit('', $WinAmount, $roundId, 'C_' . $transactionId, json_encode($datos), $roundClosed = false);
        }
    }

    /**
     * Revoca una transacción.
     *
     * @var string $roundId     Identificador de la ronda.
     * @var string $transaction Identificador de la transacción.
     * @var string $reason      Razón de la revocación.
     */
    if (strpos($URI, "revoke") !== false) {
        $roundId = $data->ticketUUID;
        $transaction = $data->transactionID;
        $reason = $data->reason;

        /* Procesamos */
        $Aadvark = new Aadvark("", "");
        $response = ($Aadvark->Rollback($roundId, $transaction, '', $data));
    }

    /**
     * Maneja datos significativos relacionados con eventos, categorías y torneos.
     *
     * @var string $gameId     Identificador del proveedor del juego.
     * @var object $event      Datos del evento.
     * @var object $category   Datos de la categoría.
     * @var object $tournament Datos del torneo.
     */
    if (strpos($URI, "significant-data") !== false) {
        $gameId = $data->provider;
        $eventUuid = $data->event->uuid;
        $eventState = $data->event->state;
        $eventArchived = $data->event->archived;
        $eventTitle = $data->event->title;
        $eventSport = $data->event->sport;
        $eventProduct = $data->event->product;
        $eventTournament = $data->event->tournamentUUID;
        $eventCategory = $data->event->categoryUUID;
        $eventStart = $data->event->startTime;
        $eventClose = $data->event->closeTime;
        $eventMod = $data->event->modifiedAt;
        $catId = $data->category->uuid;
        $catTransEn = $data->category->translations->en;
        $catTransLt = $data->category->translations->lt;
        $catMod = $data->category->modifiedAt;
        $TourId = $data->tournament->uuid;
        $tourTransEn = $data->tournament->translations->en;
        $tourTransLt = $data->tournament->translations->lt;
        $tourMod = $data->tournament->modifiedAt;

        /* Procesamos */
        $Aadvark = new Aadvark("", "");
    }
}

/**
 * Registra la respuesta en un archivo de log.
 *
 * @var string $log Contenido del log que incluye la respuesta generada.
 */
$log = $log . "\r\n" . "-------------Response------------" . "\r\n";
$log = $log . ($response);
// Guarda el log en un archivo.
fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

print_r($response);
