<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con Paycash.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payment\Paycash
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $data          Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $fp            Variable que almacena información sobre la forma de pago.
 * @var mixed $log           Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $_REQUEST      Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $confirm       Variable que almacena la confirmación de una operación o transacción.
 * @var mixed $status        Esta variable se utiliza para almacenar y manipular el estado.
 * @var mixed $PublicId      Variable que almacena el ID público.
 * @var mixed $transaccionID Variable que almacena el ID de la transacción.
 * @var mixed $Amount        Variable que almacena un monto o cantidad (posible duplicado de amount).
 * @var mixed $Paycash       Variable relacionada con la plataforma Paycash.
 * @var mixed $Resp          Variable que almacena la respuesta del sistema.
 * @var mixed $response      Esta variable almacena la respuesta generada por una operación o petición.
 */

// Habilitar modo debug con clave de seguridad
if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\payment\Paycash;

/* Obtenemos Variables que nos llegan */

$data = (file_get_contents('php://input'));

$data = json_decode($data);

if (isset($data)) {
    $confirm = ($data);

    if ($confirm->payment != null) {
        $status = 0;
    } else {
        $status = 1;
    }

    $PublicId = $confirm->payment->Referencia;
    $transaccionID = $confirm->payment->Value;
    $Amount = $confirm->payment->Monto;

    /* Procesamos */
    $Paycash = new Paycash($PublicId, $transaccionID, $Amount, $status);
    $Resp = $Paycash->confirmation($data);
    $Resp = json_decode($Resp);

    $response = array();
    if ($Resp->result == 'success') {
        $response["code"] = 200;
        $response["message"] = "payment successfully notified";
    } else {
        $response["code"] = 400;
        $response["message"] = "payment error notified";
    }
    echo json_encode($response);
}