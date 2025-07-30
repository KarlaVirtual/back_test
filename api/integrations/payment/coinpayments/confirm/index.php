<?php

/**
 * Este archivo maneja la confirmación de pagos a través de CoinPayments.
 * Procesa los datos recibidos, los registra en un archivo de log y utiliza
 * la clase CoinPayments para gestionar la confirmación de la transacción.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Documentación de Variables Globales:
 *
 * @var mixed $data         Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $fp           Variable que almacena información sobre la forma de pago.
 * @var mixed $_REQUEST     Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $confirm      Variable que almacena la confirmación de una operación o transacción.
 * @var mixed $invoice      Variable que almacena el identificador de una factura.
 * @var mixed $result       Variable que almacena el resultado de una operación o transacción.
 * @var mixed $documento_id Variable que almacena el identificador de un documento.
 * @var mixed $valor        Variable que almacena un valor monetario o numérico.
 * @var mixed $usuario_id   Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $control      Variable que almacena un código de control para una operación.
 * @var mixed $CoinPayments Variable que almacena información relacionada con CoinPayments.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\payment\CoinPayments;


/* Obtenemos Variables que nos llegan */

$data = (file_get_contents('php://input'));

$fp = fopen('log_' . date("Y-m-d") . '.log', 'a');

fwrite($fp, json_encode($_REQUEST));
fwrite($fp, json_encode($data));
fwrite($fp, file_get_contents('php://input'));

fclose($fp);
$data = str_replace("&", '","', $data);
$data = str_replace("=", '":"', $data);
$data = '{"' . $data . '"}';
$data = json_decode($data);

if (isset($data)) {
    $confirm = ($data);


    $invoice = $confirm->invoice;
    $result = $confirm->status;
    $documento_id = $confirm->txn_id;
    $valor = $confirm->amount1;
    $usuario_id = "";;
    $control = "";

    /* Procesamos */
    $CoinPayments = new CoinPayments($invoice, $usuario_id, $documento_id, $valor, $control, $result);
    $CoinPayments->confirmation(json_encode($data));
}