<?php

/**
 * Este archivo contiene la implementación de un endpoint para la integración con la plataforma Mobadoo.
 * Se procesan diferentes tipos de solicitudes relacionadas con transacciones, balance, y operaciones de juego.
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
 * @var mixed $_ENV            Variable superglobal que contiene el entorno de variables del sistema.
 * @var mixed $params          Variable que contiene los parámetros de una solicitud, generalmente en forma de array.
 * @var mixed $_REQUEST        Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $URI             Esta variable contiene el URI de la petición actual.
 * @var mixed $_SERVER         Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $method          Variable que almacena el método de pago o de ejecución de una acción.
 * @var mixed $log             Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $data            Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $hash            Variable que almacena un valor hash para seguridad o verificación.
 * @var mixed $_GET            Arreglo global que contiene los datos enviados mediante el método GET.
 * @var mixed $token           Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $Mobadoo         Variable que almacena información relacionada con la plataforma Mobadoo.
 * @var mixed $response        Esta variable almacena la respuesta generada por una operación o petición.
 * @var mixed $datos           Variable que almacena datos genéricos.
 * @var mixed $PlayerId        Variable que almacena el identificador único de un jugador.
 * @var mixed $GameCode        Variable que almacena el código de un juego.
 * @var mixed $transactionId   Variable que almacena el identificador único de una transacción.
 * @var mixed $RoundId         Variable que almacena el identificador de una ronda de juego.
 * @var mixed $CreditAmount    Variable que almacena el monto acreditado a una cuenta o transacción.
 * @var mixed $DebitAmount     Variable que almacena el monto debitado de una cuenta o transacción.
 * @var mixed $state           Variable que almacena el estado actual de un elemento o proceso.
 * @var mixed $sign            Variable que almacena una firma digital o de seguridad.
 * @var mixed $requestId       Variable que almacena el identificador de la solicitud.
 * @var mixed $gameCycleClosed Variable que indica si el ciclo del juego ha finalizado.
 * @var mixed $respuestaCredit Variable que almacena la respuesta de una operación de crédito.
 * @var mixed $currency        Variable que almacena la moneda utilizada en una transacción.
 * @var mixed $rollbackAmount  Variable que almacena el monto de reversión.
 * @var mixed $date            Variable que almacena una fecha genérica.
 * @var mixed $player          Variable que almacena información del jugador.
 * @var mixed $roundId         Variable que almacena el identificador de la ronda.
 * @var mixed $rollback        Variable que almacena información de una reversión.
 */

header('Access-Control-Allow-Origin: *');

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\virtual\Mobadoo;

header('Content-type: application/json');

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';

$params = $_REQUEST['t'];
$params = file_get_contents('php://input');

$URI = $_SERVER['REQUEST_URI'];
$method = $URI;

$data = json_decode($params);

$URI = explode('/', $URI);
$URI = $URI[count($URI) - 1];
$URI = explode('?', $URI);
$URI = $URI[0];

$hash = $_GET['hash'];

if (true) {
    if ($URI == "credit") {
        $token = $hash;

        /* Procesamos */
        $Mobadoo = new Mobadoo($token, '00');
        $response = $Mobadoo->Auth();
    }

    if ($URI == "create") {
        $datos = $data;
        if ($data->type == "TICKET_CHANGED") {
            $change = $data->type;
            $token = $data->hash;
            $PlayerId = $data->ticket->uuid;
            $GameCode = $data->ticket->tips[0]->product_type;
            $RoundId = $data->ticket->id . $PlayerId;
            $transactionId = $data->ticket->transaction_id . $PlayerId . $RoundId;
            $CreditAmount = $data->ticket->tips[0]->won_amount;
            $DebitAmount = $data->ticket->tips[0]->bet_amount;
            $state = $data->ticket->state;
        } else {
            $change = 'CREATED';
            $token = $data->hash;
            $PlayerId = $data->uuid;
            $GameCode = $data->tips[0]->product_type;
            $RoundId = $data->id . $PlayerId;
            $transactionId = $data->transaction_id . $PlayerId . $RoundId;
            $DebitAmount = $data->tips[0]->bet_amount;
            $state = $data->state;
        }

        if ($state == 'CANCELED' || $state == 'REJECTED') {
            $state = 'CANCELED';
        }

        /* Procesamos */
        $Mobadoo = new Mobadoo($token, $PlayerId);
        if ($state == 'CREATED' && $change == "CREATED") {
            $response = $Mobadoo->Debit($GameCode, $DebitAmount, $RoundId, $transactionId . "_D", json_encode($datos));
        } elseif ($state == 'RESOLVED') {
            $response = $Mobadoo->Credit($GameCode, $CreditAmount, $RoundId, $transactionId . "_C", json_encode($datos));
        } elseif ($state == 'CANCELED') {
            $response = $Mobadoo->Rollback($GameCode, $RoundId, $transactionId, $PlayerId, json_encode($datos));
        }
    }

    if (strpos($URI, "logout") !== false) {
        $token = $data->sessionId;
        $sign = $data->fingerprint;

        /* Procesamos */
        $Mobadoo = new Mobadoo($token, $sign);
        $response = $Mobadoo->logout();
    }

    echo $response;
}
