<?php

/**
 * Este script maneja la confirmación de pagos a través de la pasarela Greenpay.
 * Procesa los datos recibidos, registra logs y actualiza el estado de las transacciones.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payment\Greenpay
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $data     Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $fp       Variable que almacena información sobre la forma de pago.
 * @var mixed $log      Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $_REQUEST Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $Username Variable que almacena el nombre de usuario de un sistema.
 * @var mixed $_SERVER  Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $Password Variable que almacena una contraseña o clave de acceso.
 * @var mixed $trans    Variable que almacena información sobre una transacción.
 * @var mixed $uid      Variable que almacena el identificador único de un usuario.
 * @var mixed $status   Esta variable se utiliza para almacenar y manipular el estado.
 * @var mixed $Greenpay Variable que almacena información sobre la pasarela de pago Greenpay.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');


use \Backend\integrations\payment\Greenpay;

/* Obtenemos Variables que nos llegan */
header('Content-Type: application/json');

$data = (file_get_contents('php://input'));

$data = json_decode($data);

if (isset($data)) {
    $trans = $data->orden_id;
    $uid = $data->orden_id;
    $status = $data->estado;

    if ($status == 400 || $status == '400') {
        $status = 'PROGRESS';
    } elseif ($status == '500' || $status == 500) {
        $status = 'CANCEL';
    } elseif ($status == '2' || $status == 2) {
        $status = 'SUCCESS';
    }

    /* Procesamos */
    $Greenpay = new Greenpay($trans, $status, $uid);
    $Greenpay->confirmation(json_encode($data));
}
