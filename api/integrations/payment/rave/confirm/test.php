<?php

/**
 * Este archivo contiene un script para procesar confirmaciones de pago utilizando la integración con rave.
 * Se inicializan variables necesarias para la operación y se realiza la confirmación del pago.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payment\Rave
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $result       Variable que almacena el resultado de una operación o transacción.
 * @var mixed $invoice      Variable que almacena el identificador de una factura.
 * @var mixed $usuario_id   Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $documento_id Variable que almacena el identificador de un documento.
 * @var mixed $valor        Variable que almacena un valor monetario o numérico.
 * @var mixed $control      Variable que almacena un código de control para una operación.
 * @var mixed $_POST        Arreglo global que contiene los datos enviados mediante el método POST.
 * @var mixed $Astropay     Variable que almacena información relacionada con AstroPay.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\payment\Astropay;

/* Obtenemos Variables que nos llegan */

$result = 9;

$invoice = 7217;

$usuario_id = 1058;

$documento_id = 164687145;

$valor = 40;

$control = $_POST['x_control'];

/* Procesamos */

$Astropay = new Astropay($invoice, $usuario_id, $documento_id, $valor, $control, $result);

$Astropay->confirmation();



