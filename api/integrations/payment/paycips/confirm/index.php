<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con PayCIPS.
 * Procesa los datos recibidos en la solicitud HTTP, registra logs y realiza la confirmación
 * de la transacción utilizando la clase PayCIPS.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $log          Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $_REQUEST     Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $body         Esta variable contiene el contenido del cuerpo de la solicitud HTTP.
 * @var mixed $data         Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $session      Variable que almacena datos de sesión del usuario.
 * @var mixed $invoice      Variable que almacena el identificador de una factura.
 * @var mixed $usuario_id   Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $documento_id Variable que almacena el identificador de un documento.
 * @var mixed $valor        Variable que almacena un valor monetario o numérico.
 * @var mixed $Auth         Variable que almacena información de autenticación.
 * @var mixed $text         Variable que almacena un texto genérico.
 * @var mixed $control      Variable que almacena un código de control para una operación.
 * @var mixed $result       Variable que almacena el resultado de una operación o transacción.
 * @var mixed $Paycips      Variable que almacena información relacionada con Paycips.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\payment\PayCIPS;

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . json_encode($_REQUEST);
$log = $log . trim(file_get_contents('php://input'));

fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


/* Obtenemos Variables que nos llegan */

$body = file_get_contents('php://input');

$data = json_decode($body);


$session = $data->SessionId;

$invoice = $data->TxOrderId;

$usuario_id = "";

$documento_id = $data->ResponseData->SystemTrace;

$valor = $data->Amount;
$Auth = $data->ResponseData->Auth;
$text = $data->ResponseData->Text;
$control = "";
$result = strtoupper($data->ResponseData->Code);

/* Procesamos */


$Paycips = new PayCIPS($invoice, $usuario_id, $documento_id, $valor, $control, $result);

$Paycips->confirmation();





