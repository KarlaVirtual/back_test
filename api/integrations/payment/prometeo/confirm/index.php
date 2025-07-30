<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con Prometeo.
 * Procesa los datos recibidos en formato JSON, registra eventos en un archivo de log
 * y utiliza la clase Prometeo para confirmar transacciones.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables utilizadas en el script:
 *
 * @var mixed $data                     Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $fp                       Variable que almacena información sobre la forma de pago.
 * @var mixed $log                      Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $_REQUEST                 Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $verify_token             Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $events                   Variable que almacena una lista de eventos.
 * @var mixed $event_type               Variable que define el tipo de evento.
 * @var mixed $event_id                 Variable que almacena el identificador de un evento.
 * @var mixed $timestamp                Variable que almacena la marca de tiempo.
 * @var mixed $payload                  Variable que almacena los datos del cuerpo de una solicitud, usualmente en JSON.
 * @var mixed $amount                   Variable que almacena un monto o cantidad.
 * @var mixed $concept                  Variable que almacena la descripción o concepto de una transacción.
 * @var mixed $currency                 Variable que almacena la moneda utilizada en una transacción.
 * @var mixed $origin_account           Esta variable indica la cantidad total de elementos o registros, útil para iteraciones o validaciones.
 * @var mixed $destination_account      Esta variable indica la cantidad total de elementos o registros, útil para iteraciones o validaciones.
 * @var mixed $destination_institution  Variable que almacena la institución financiera de destino.
 * @var mixed $branch                   Variable que almacena información sobre una sucursal bancaria.
 * @var mixed $destination_owner_name   Variable que almacena el nombre del titular de la cuenta de destino.
 * @var mixed $destination_account_type Esta variable indica la cantidad total de elementos o registros, útil para iteraciones o validaciones.
 * @var mixed $document_type            Variable que almacena el tipo de documento de identificación.
 * @var mixed $document_number          Variable que almacena el número de documento de identificación.
 * @var mixed $destination_bank_code    Variable que almacena el código del banco de destino.
 * @var mixed $mobile_os                Variable que almacena el sistema operativo de un dispositivo móvil.
 * @var mixed $request_id               Variable que almacena el identificador de una solicitud.
 * @var mixed $intent_id                Variable que almacena el identificador de una intención de transacción.
 * @var mixed $Prometeo                 Variable que almacena información sobre la plataforma de pagos Prometeo.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');


use \Backend\integrations\payment\Prometeo;

/* Obtenemos Variables que nos llegan */
header('Content-Type: application/json');


$data = (file_get_contents('php://input'));

$fp = fopen('log_' . date("Y-m-d") . '.log', 'a');

$log = " /" . time();

$log = $log . "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
$log = $log . json_encode($_REQUEST);
$log = $log . trim($data);
fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

$data = json_decode($data);


if (isset($data)) {
    $verify_token = $data->verify_token;
    $events = $data->events[0];
    $event_type = $events->event_type;
    $event_id = $events->event_id;
    $timestamp = $events->timestamp;
    $payload = $events->payload;
    $amount = $payload->amount;
    $concept = $payload->concept;
    $currency = $payload->currency;
    $origin_account = $payload->origin_account;
    $destination_account = $payload->destination_account;
    $destination_institution = $payload->destination_institution;
    $branch = $payload->branch;
    $destination_owner_name = $payload->destination_owner_name;
    $destination_account_type = $payload->destination_account_type;
    $document_type = $payload->document_type;
    $document_number = $payload->document_number;
    $destination_bank_code = $payload->destination_bank_code;
    $mobile_os = $payload->mobile_os;
    $request_id = $payload->request_id;
    $intent_id = $payload->intent_id;

    /* Procesamos */
    $Prometeo = new Prometeo($concept, $event_type, $request_id);
    $Prometeo->confirmation(json_encode($data));
}
