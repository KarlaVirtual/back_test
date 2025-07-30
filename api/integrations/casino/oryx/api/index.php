<?php
/**
 * Este archivo contiene un script para procesar solicitudes de la API del casino 'oryx',
 * manejando autenticaciones, consultas de saldo, transacciones de juego y otras operaciones relacionadas.
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
 * @var mixed $_SERVER        URI de la solicitud actual enviada al servidor ['REQUEST_URI'].
 * @var mixed $_ENV           Variable de entorno que habilita la conexión global ["enabledConnectionGlobal"].
 * @var mixed $_REQUEST       Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $URI            URI de la solicitud procesada.
 * @var mixed $body           Contenido del cuerpo de la solicitud en formato JSON.
 * @var mixed $data           Objeto decodificado del cuerpo de la solicitud.
 * @var mixed $log            Variable utilizada para almacenar y registrar información de logs.
 * @var mixed $token          Token de autenticación o identificador único del jugador.
 * @var mixed $sign           Firma utilizada para validar la autenticidad de las solicitudes.
 * @var mixed $response       Respuesta generada por las operaciones realizadas.
 * @var mixed $transactionId  Identificador único de una transacción de juego.
 * @var mixed $action         Acción solicitada en una transacción de juego.
 * @var mixed $rollbackAmount Monto a revertir en una operación de rollback.
 * @var mixed $roundId        Identificador único de una ronda de juego.
 * @var mixed $CreditAmount   Monto de crédito en una transacción de premio.
 * @var mixed $DebitAmount    Monto de débito en una transacción de apuesta.
 * @var mixed $GameCode       Código del juego asociado a una transacción.
 * @var mixed $PlayerId       Identificador único del jugador.
 * @var mixed $datos          Datos adicionales enviados en la solicitud.
 * @var mixed $respuesta      Respuesta procesada y enviada al proveedor.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Oryx;

$URI = $_SERVER['REQUEST_URI'];

/* LOG Inicial de los parametros que nos envia el proveedor */

$_ENV["enabledConnectionGlobal"] = 1;

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . ($URI);
$log = $log . trim(file_get_contents('php://input'));
// Obtenemos lo enviado en el body
$body = file_get_contents('php://input');
// Convertimos el json en objeto
$data = json_decode($body);


// Validamos si la solicitud es de autenticación
if (strpos($URI, "authenticate") !== false) {
    //Obtenemos el token
    $token = $data->Token;
    $token = explode("/tokens/", $URI);
    $token = explode("/authenticate", $token[1]);
    $token = $token[0];

    //Obtenemos el sign
    $sign = $_REQUEST["sign"];

    /* Procesamos */
    $Oryx = new Oryx($token, $sign);
    $response = ($Oryx->Auth());

    /*
     * Escribimos el log con la respuesta al proveedor
     */
    $log = "";
    $log = $log . "/" . time();
    $log = $log . "\r\n" . "-------------------------" . "\r\n";
    $log = $log . ($response);
    // Respuesta al proveedor
    print_r($response);
}

// Validamos si la solicitud es de consulta de saldo o si es de transacciones
if (strpos($URI, "/balance") !== false) {
    //$token = $data->Token;

    $token = explode("players/", $URI);
    $token = explode("/balance", $token[1]);
    $token = $token[0];

    /* Procesamos */
    $Oryx = new Oryx($token, $sign);
    $response = ($Oryx->getBalance($token));

    /*
     * Escribimos el log con la respuesta al proveedor
     */
    $log = "";
    $log = $log . "/" . time();
    $log = $log . "\r\n" . "-------------------------" . "\r\n";
    $log = $log . ($response);
    // Respuesta al proveedor
    print_r($response);
} elseif (strpos($URI, "game-transactions") !== false) {
    $transactionId = explode("game-transactions/", $URI);
    $transactionId = $transactionId[1];

    $action = $data->action;

    // Si la acción es de cancelación o Rollback
    if ($action == "CANCEL" || $data->roundAction == "CANCEL") {
        $token = $data->playerId;
        $rollbackAmount = 0;
        $roundId = $data->roundId;
        $transactionId = $data->transactionId;

        $player = 0;

        $datos = $data;

        /* Procesamos */

        $Oryx = new Oryx($token, $sign);
        $response = ($Oryx->Rollback($rollbackAmount, $roundId, $transactionId, $player, json_encode($datos)));


        /*
         * Escribimos el log con la respuesta al proveedor
         */

        $log = "";
        $log = $log . "/" . time();
        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);
        // Respuesta al proveedor
        print_r($response);
    } elseif ($data->roundAction == "CLOSE") {
        $CreditAmount = $data->win->amount;


        if ($CreditAmount != "" && ($data->win)) {
            $token = $data->playerId;

            $GameCode = $data->gameCode;

            $PlayerId = $data->playerId;

            $RoundId = $data->roundId;

            $transactionId = $data->win->transactionId;
            $CreditAmount = $data->win->amount;

            $Oryx = new Oryx($token, $sign);
            $CreditAmount = $CreditAmount / 100;

            //Procesamos y obtenemos la respuesta
            $respuesta = $Oryx->Credit($GameCode, $CreditAmount, $RoundId, $transactionId, json_encode($datos));
        } else {
            $token = $data->playerId;

            $Oryx = new Oryx($token, $sign);

            //Procesamos y obtenemos la respuesta
            $respuesta = ($Oryx->getBalance($token));
        }


        /*
         * Escribimos el log con la respuesta al proveedor
         */
        $log = "";
        $log = $log . "/" . time();
        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($respuesta);

        // Respuesta al proveedor
        print_r($respuesta);
    } else {
        // Si la acción es de apuesta o ganada

        $token = $data->playerId;

        $GameCode = $data->gameCode;

        $PlayerId = $data->playerId;

        $RoundId = $data->roundId;


        $transactionId = $data->bet->transactionId;
        $DebitAmount = $data->bet->amount;

        if (($DebitAmount != "" || $DebitAmount == 0) && ($data->bet)) {
            $Oryx = new Oryx($token, $sign);

            $DebitAmount = $DebitAmount / 100;

            //Procesamos y obtenemos la respuesta
            $respuesta = ($Oryx->Debit($GameCode, $DebitAmount, $RoundId, $transactionId, json_encode($datos)));
        }

        $transactionId = $data->win->transactionId;
        $CreditAmount = $data->win->amount;


        if ($CreditAmount != "" && ($data->win)) {
            $Oryx = new Oryx($token, $sign);
            $CreditAmount = $CreditAmount / 100;

            //Procesamos y obtenemos la respuesta
            $respuesta = $Oryx->Credit($GameCode, $CreditAmount, $RoundId, $transactionId, json_encode($datos));
        }


        /*
         * Escribimos el log con la respuesta al proveedor
         */

        $log = "";
        $log = $log . "/" . time();
        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($respuesta);

        // Respuesta al proveedor
        print_r($respuesta);
    }
} elseif (strpos($URI, "game-transaction") !== false) {
    $token = $data->playerId;

    $GameCode = $data->gameCode;

    $PlayerId = $data->playerId;

    $RoundId = $data->roundId;

    $roundAction = $data->roundAction;


    $datos = $data;

    /* Procesamos */

    if ($roundAction == "CANCEL") {
        $transactionId = $RoundId;
        $rollbackAmount = 0;
        $player = 0;
        if (($RoundId != "")) {
            $Oryx = new Oryx($token, $sign);

            $respuesta = ($Oryx->RollbackRound($rollbackAmount, $RoundId, $transactionId, $player, json_encode($datos)));
        }
    } else {
        $transactionId = $data->bet->transactionId;
        $DebitAmount = $data->bet->amount;

        if (($DebitAmount != "" || $DebitAmount == 0) && ($data->bet)) {
            $Oryx = new Oryx($token, $sign);

            $DebitAmount = $DebitAmount / 100;

            $respuesta = ($Oryx->Debit($GameCode, $DebitAmount, $RoundId, $transactionId, json_encode($datos)));
        }

        /* Procesamos */

        $transactionId = $data->win->transactionId;
        $CreditAmount = $data->win->amount;

        if ($CreditAmount != "" && ($data->win)) {
            $Oryx = new Oryx($token, $sign);
            $CreditAmount = $CreditAmount / 100;

            $respuesta = $Oryx->Credit($GameCode, $CreditAmount, $RoundId, $transactionId, json_encode($datos));
        }
    }

    /*
     * Escribimos el log con la respuesta al proveedor
     */
    $log = "";
    $log = $log . "/" . time();
    $log = $log . "\r\n" . "-------------------------" . "\r\n";
    $log = $log . ($respuesta);

    // Respuesta al proveedor
    print_r($respuesta);
}






