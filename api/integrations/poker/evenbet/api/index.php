<?php

/**
 * Este archivo contiene la implementación de una API para integrarse con el sistema EvenBet Poker.
 * Proporciona métodos para manejar solicitudes relacionadas con el balance, transacciones de débito,
 * crédito, devoluciones y rollbacks en el sistema de juegos de apuestas.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $headers                Variable que almacena los encabezados HTTP de la solicitud.
 * @var mixed $_SERVER                Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $name                   Variable que almacena el nombre de un archivo, usuario o entidad.
 * @var mixed $value                  Esta variable se utiliza para almacenar y manipular valores asociados a claves.
 * @var mixed $_REQUEST               Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $_ENV                   Variable superglobal que contiene el entorno de variables del sistema.
 * @var mixed $log                    Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $body                   Esta variable contiene el contenido del cuerpo de la solicitud HTTP.
 * @var mixed $URI                    Esta variable contiene el URI de la petición actual.
 * @var mixed $data                   Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $sign                   Variable que almacena una firma digital o de seguridad.
 * @var mixed $token                  Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $currency               Variable que almacena la moneda utilizada en una transacción.
 * @var mixed $userId                 Variable que almacena el identificador único del usuario.
 * @var mixed $Evenbet                Variable que hace referencia a un proveedor o sistema relacionado con juegos de apuestas (Evenbet).
 * @var mixed $response               Esta variable almacena la respuesta generada por una operación o petición.
 * @var mixed $transactionId          Variable que almacena el identificador único de una transacción.
 * @var mixed $transactionType        Variable que almacena el tipo de transacción realizada.
 * @var mixed $RoundId                Variable que almacena el identificador de una ronda de juego.
 * @var mixed $Game                   Variable que almacena información relacionada con un juego específico.
 * @var mixed $Amount                 Variable que almacena un monto o cantidad (posible duplicado de amount).
 * @var mixed $freespin               Variable que almacena información sobre giros gratis en un juego.
 * @var mixed $datos                  Variable que almacena datos genéricos.
 * @var mixed $respuestaDebit         Variable que almacena la respuesta obtenida de una operación de débito realizada en el sistema.
 * @var mixed $respuestaCredit        Variable que almacena la respuesta de una operación de crédito.
 * @var mixed $referenceTransactionId Variable que almacena el identificador único de una transacción de referencia, utilizada para hacer un seguimiento o referencia cruzada.
 * @var mixed $respuesta              Esta variable se utiliza para almacenar y manipular la respuesta de una operación.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\poker\Evenbet;

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

header('Content-type: application/json; charset=utf-8');
if ( ! function_exists('getallheaders')) {
    /**
     * Obtiene todos los encabezados de la solicitud HTTP.
     *
     * @return array Un array asociativo con los nombres y valores de los encabezados.
     */
    function getallheaders()
    {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}

$body = file_get_contents('php://input');

$URI = $_SERVER['REQUEST_URI'];
$headers = getallheaders();

if ($body != "") {
    $data = json_decode($body);

    if (strpos($data->method, "GetBalance") !== false) {
        $sign = $headers['sign'];
        $token = $data->sessionId;
        $currency = $data->currency;
        $userId = $data->userId;

        /* Procesamos */
        $Evenbet = new Evenbet($token, $sign, $userId);
        $response = ($Evenbet->getBalance());
    }

    if (strpos($data->method, "GetCash") !== false) {
        $sign = $headers['sign'];
        $token = $data->sessionId;
        $currency = $data->currency;
        $userId = $data->userId;
        $transactionId = $data->transactionId;
        $transactionType = $data->transactionType;

        if ($data->tournamentId != 0) {
            $RoundId = $data->tournamentId;
        } else {
            $RoundId = $data->tableSessionId;
        }

        $Game = "EvenBetPoker";
        $Amount = round($data->amount, 2) / 100;
        $freespin = false;
        $datos = $data;

        /* Procesamos */
        $Evenbet = new Evenbet($token, $sign, $userId);
        $response = ($Evenbet->Debit($Game, $Amount, $RoundId, $transactionId, json_encode($datos), $freespin));
    }

    if (strpos($data->method, "ReturnCash") !== false) {
        $sign = $headers['sign'];
        $token = $data->sessionId;
        $currency = $data->currency;
        $userId = $data->userId;
        $transactionId = $data->transactionId;
        $transactionType = $data->transactionType;

        if ($data->tournamentId != 0) {
            $RoundId = $data->tournamentId;
        } else {
            $RoundId = $data->tableSessionId;
        }

        $Game = "EvenBetPoker";
        $Amount = round($data->amount, 2) / 100;
        $datos = $data;

        /* Procesamos */
        $Evenbet = new Evenbet($token, $sign, $userId);
        $response = $Evenbet->Credit($Game, $Amount, $RoundId, $transactionId, json_encode($datos));

        if (json_decode($response) != null && json_decode($response)->error != null && json_decode($response)->error->message == 'Reference transaction does not exist') {
            $response = $Evenbet->Debit($Game, 0, $RoundId, 'D_' . $transactionId, json_encode($datos), false);
            $response = $Evenbet->Credit($Game, $Amount, $RoundId, 'C_' . $transactionId, json_encode($datos));
        }
    }

    if (strpos($data->method, "Rollback") !== false) {
        $sign = $headers['sign'];
        $token = $data->sessionId;
        $currency = $data->currency;
        $userId = $data->userId;
        $transactionId = $data->transactionId;
        $referenceTransactionId = $data->referenceTransactionId;

        if ($data->tournamentId != 0) {
            $RoundId = $data->tournamentId;
        } else {
            $RoundId = $data->tableSessionId;
        }

        $Game = "EvenBetPoker";
        $Amount = round($data->amount, 2) / 100;
        $datos = $data;

        /* Procesamos */
        $Evenbet = new Evenbet($token, $sign, $userId);
        $response = $Evenbet->Rollback($Amount, $RoundId, $transactionId, $userId, json_encode($datos));
    }
}

print_r($response);
