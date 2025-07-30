<?php

/**
 * Este archivo maneja solicitudes HTTP para realizar operaciones de débito en la integración de generación virtual.
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
 * @var mixed $URI                Esta variable contiene el URI de la petición actual.
 * @var mixed $_SERVER            Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $body               Esta variable contiene el contenido del cuerpo de la solicitud HTTP.
 * @var mixed $data               Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $operatorId         Variable que almacena el identificador del operador.
 * @var mixed $token              Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $Amount             Variable que almacena un monto o cantidad (posible duplicado de amount).
 * @var mixed $TransactionTypeId  Variable que almacena el identificador del tipo de transacción.
 * @var mixed $TransactionMessage Variable que almacena un mensaje de transacción.
 * @var mixed $UserId             Esta variable se utiliza para almacenar y manipular el identificador del usuario.
 * @var mixed $Username           Variable que almacena el nombre de usuario de un sistema.
 * @var mixed $Currency           Variable que almacena la moneda utilizada.
 * @var mixed $VirtualGeneration  Variable que almacena información sobre generación virtual.
 * @var mixed $username           Variable que almacena el nombre de usuario.
 * @var mixed $Ezugi              Variable que almacena información relacionada con la plataforma Ezugi.
 * @var mixed $gameId             Variable que almacena el identificador de un juego.
 * @var mixed $uid                Variable que almacena el identificador único de un usuario.
 * @var mixed $betTypeID          Variable que almacena el identificador del tipo de apuesta.
 * @var mixed $currency           Variable que almacena la moneda utilizada en una transacción.
 * @var mixed $debitAmount        Variable que almacena el monto del débito.
 * @var mixed $serverId           Variable que almacena el identificador del servidor.
 * @var mixed $roundId            Variable que almacena el identificador de la ronda.
 * @var mixed $transactionId      Variable que almacena el identificador único de una transacción.
 * @var mixed $seatId             Variable que almacena el identificador del asiento.
 * @var mixed $hash               Variable que almacena un valor hash para seguridad o verificación.
 */

require(__DIR__ . '../../../../../../vendor/autoload.php');

use Backend\integrations\casino\VirtualGeneration;


/* Procesamos */

$URI = $_SERVER['REQUEST_URI'];
$body = file_get_contents('php://input');
if ($body != "") {
    $data = json_decode($body);

    $operatorId = $data->operatorId;
    $token = $data->token;


    $Amount = $data->Amount;
    $TransactionTypeId = $data->TransactionTypeId;
    $TransactionMessage = $data->TransactionMessage;
    $UserId = $data->UserId;
    $Username = $data->Username;
    $Currency = $data->Currency;

    /* Procesamos */

    $VirtualGeneration = new VirtualGeneration($operatorId, $username);

    print_r($Ezugi->Debit($gameId, $uid, $betTypeID, $currency, $debitAmount, $serverId, $roundId, $transactionId, $seatId, $hash));
}



