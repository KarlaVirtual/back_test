<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con Coincaex.
 * Procesa los datos recibidos en la solicitud, registra logs y utiliza la clase Coincaex
 * para gestionar la confirmación de transacciones.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payment\Coincaex
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables Globales:
 *
 * @var mixed $URI        Esta variable contiene el URI de la petición actual.
 * @var mixed $_SERVER    Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $data       Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $fp         Variable que almacena información sobre la forma de pago.
 * @var mixed $log        Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $_REQUEST   Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $confirm    Variable que almacena la confirmación de una operación o transacción.
 * @var mixed $id         Variable que almacena un identificador genérico.
 * @var mixed $value      Esta variable se utiliza para almacenar y manipular valores asociados a claves.
 * @var mixed $status     Esta variable se utiliza para almacenar y manipular el estado.
 * @var mixed $Paybrokers Variable que almacena información relacionada con PayBrokers.
 */


require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\payment\Coincaex;

/* Obtenemos Variables que nos llegan */

$URI = $_SERVER['REQUEST_URI'];

$data = (file_get_contents('php://input'));

$fp = fopen('log_' . date("Y-m-d") . '.log', 'a');

$log = " /" . time();

$log = $log . "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
$log = $log . ($URI);
$log = $log . trim($data);

fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

$data = json_decode($data);
if (isset($data)) {
    $confirm = $data;

    $id = $confirm->Invoice->id;
    $value = $confirm->Invoice->price;
    $status = $confirm->Invoice->status;

    if ($status == 'complete' || $status == 'confirmed') {
        $status = 'complete';
    }

    /* Procesamos */
    $Paybrokers = new Coincaex($id, $value, $status);

    $Paybrokers->confirmation($data);
}