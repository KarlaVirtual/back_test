<?php

/**
 * Archivo principal para la integración con EsaGaming en la API de póker.
 *
 * Este archivo procesa solicitudes HTTP relacionadas con la autenticación de usuarios,
 * obtención de información de usuarios, balance y transacciones en la plataforma EsaGaming.
 * También registra logs de las operaciones realizadas para facilitar la depuración.
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
 * @var mixed $log             Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $_SERVER         Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $body            Esta variable contiene el contenido del cuerpo de la solicitud HTTP.
 * @var mixed $data            Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $URI             Esta variable contiene el URI de la petición actual.
 * @var mixed $username        Variable que almacena el nombre de usuario.
 * @var mixed $password        Variable que almacena la contraseña.
 * @var mixed $partnerId       Variable que almacena el identificador único del socio o partner en una plataforma o transacción.
 * @var mixed $source          Variable que almacena la fuente de una transacción o proceso, como el origen de los datos o acción.
 * @var mixed $EsaGaming       Variable que hace referencia a un proveedor o sistema relacionado con juegos en línea (EsaGaming).
 * @var mixed $request         Variable que representa la solicitud HTTP, conteniendo datos como parámetros y encabezados.
 * @var mixed $response        Esta variable almacena la respuesta generada por una operación o petición.
 * @var mixed $userId          Variable que almacena el identificador único del usuario.
 * @var mixed $Balance         Variable que almacena el balance o saldo disponible en una cuenta o sistema.
 * @var mixed $DebitAmount     Variable que almacena el monto debitado de una cuenta o transacción.
 * @var mixed $CreditAmount    Variable que almacena el monto acreditado a una cuenta o transacción.
 * @var mixed $sessionId       Variable que almacena el identificador único de una sesión de usuario o de un proceso.
 * @var mixed $AAMS_token      Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $gameId          Variable que almacena el identificador de un juego.
 * @var mixed $gameName        Variable que almacena el nombre del juego asociado a una transacción o proceso.
 * @var mixed $gameProvider    Variable que almacena el nombre del proveedor o plataforma del juego.
 * @var mixed $amount          Variable que almacena un monto o cantidad.
 * @var mixed $currency        Variable que almacena la moneda utilizada en una transacción.
 * @var mixed $descripcion     Variable que almacena una descripción detallada de un elemento o proceso.
 * @var mixed $sessionState    Variable que almacena el estado actual de una sesión de usuario o proceso (por ejemplo, activo, inactivo).
 * @var mixed $transactionId   Variable que almacena el identificador único de una transacción.
 * @var mixed $RoundId         Variable que almacena el identificador de una ronda de juego.
 * @var mixed $freespin        Variable que almacena información sobre giros gratis en un juego.
 * @var mixed $datos           Variable que almacena datos genéricos.
 * @var mixed $respuestaDebit  Variable que almacena la respuesta obtenida de una operación de débito realizada en el sistema.
 * @var mixed $respuestaCredit Variable que almacena la respuesta de una operación de crédito.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\Usuario;
use Backend\integrations\poker\EsaGaming;
use Backend\integrations\poker\ESAGAMINGSERVICES;

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . $_SERVER['REQUEST_URI'];
$log = $log . trim(file_get_contents('php://input'));
fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

$body = file_get_contents('php://input');
if ($body != "") {
    $body = str_replace("&", '","', $body);
    $body = str_replace("=", '":"', $body);

    $body = '{"' . $body . '"}';
    $data = json_decode($body);
}

$URI = $_SERVER['REQUEST_URI'];
header('Content-Type: x-www-form-urlencoded');

if ($body != "") {
    $data = json_decode($body);

    $data->username = str_replace("%40", "@", $data->username);

    if (strpos($URI, "UserAuthenticate") !== false) {
        $username = $data->username;
        $password = $data->password;
        $partnerId = $data->partnerId;
        $source = $data->source;

        /* Procesamos */
        $EsaGaming = new EsaGaming("", $username);
        $request = [
            "method" => "UserAuthenticate",
            "username" => $username,
            "password" => $password,
            "partnerId" => $partnerId,
            "source" => $source
        ];

        $response = $EsaGaming->userAuthenticate(json_encode($request));
        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);
        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

        print_r($response);
    }
    if (strpos($URI, "UserGetInfo") !== false) {
        $userId = $data->userId;

        $EsaGaming = new EsaGaming($userId);
        $request = [
            "method" => "UserGetInfo",
            "userId" => $userId,
        ];
        $response = $EsaGaming->userGetInfo();
        $log = $log . "/" . time();

        $log = $log . "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
        $log = $log . ($response);
        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

        print_r($response);
    }
    if (strpos($URI, "UserGetBalance") !== false) {
        $Balance = $data;

        $userId = $data->userId;

        $EsaGaming = new EsaGaming($userId);
        $request = [
            "method" => "UserGetBalance",
            "userId" => $userId,
        ];
        $response = $EsaGaming->userGetBalance();
        $log = $log . "/" . time();

        $log = $log . "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
        $log = $log . ($response);
        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

        print_r($response);
    }


    if (strpos($URI, "UserTransaction") !== false) {
        $DebitAmount = $data->context->total_bet_amount;
        $CreditAmount = $data->context->total_win_amount;
        $sessionId = $data->context->session_id;
        $AAMS_token = $data->context->AAMS_token;
        $gameId = $data->context->game_id;
        $gameName = $data->context->game_name;
        $gameProvider = $data->context->game_provider;
        $userId = $data->userId;
        $amount = $data->amount;
        $currency = $data->currency;
        $descripcion = $data->descripcion;
        $sessionState = $data->sessionState;
        $transactionId = $data->transactionId;
        $RoundId = $data->transactionId;
        $freespin = false;
        $EsaGaming = new EsaGaming($userId);

        $DebitAmount = intval($DebitAmount) / 100;
        $CreditAmount = intval($CreditAmount) / 100;
        $request = [
            "transactionId" => (string)$transactionId,
            "userId " => (string)$userId,
            "amount" => (string)$amount,
            "currency" => (string)$currency,
            "context" => array(
                "session_id" => (string)$sessionId,
                " game_id" => (string)$gameId,
                " AAMS_token" => (string)$AAMS_token,
                " game_name" => (string)$gameName,
                " game_provider" => (string)$gameProvider,
                "total_bet_amount" => (string)$DebitAmount,
                "total_win_amount " => (string)$CreditAmount
            ),
            "description " => (string)$descripcion,
            "sessionState " => (string)$sessionState
        ];
        if ($amount >= 0) {
            $datos = $data;

            /* Procesamos */


            $EsaGaming = new EsaGaming($userId);

            $respuestaDebit = $EsaGaming->Debit("POKER", '0', $RoundId, $transactionId, json_encode($request), $freespin);
            $transactionId = "credit" . $data->transactionId;;


            $datos = $data;
            $EsaGaming = new EsaGaming($userId);
            $respuestaCredit = $EsaGaming->Credit("POKER", $amount, $RoundId, $transactionId, json_encode($request));
            $log = $log . "/" . time();

            $log = $log . "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
            $log = $log . ($respuestaCredit);
            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

            print_r($respuestaCredit);
        }

        if ($amount < 0) {
            $amount = ($amount * -1);
            $respuestaDebit = $EsaGaming->Debit("POKER", $amount, $RoundId, $transactionId, json_encode($request), $freespin);

            $log = $log . "/" . time();

            $log = $log . "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
            $log = $log . ($respuestaDebit);
            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

            print_r($respuestaDebit);
        }
    }
}



