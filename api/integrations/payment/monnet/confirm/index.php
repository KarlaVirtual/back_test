<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con la plataforma Monnet.
 * Procesa los datos recibidos en formato JSON, registra información en un archivo de log y utiliza
 * la clase Monnet para confirmar la transacción.
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
 * @var mixed $data                         Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $fp                           Variable que almacena información sobre la forma de pago.
 * @var mixed $log                          Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $_REQUEST                     Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $Username                     Variable que almacena el nombre de usuario de un sistema.
 * @var mixed $_SERVER                      Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $Password                     Variable que almacena una contraseña o clave de acceso.
 * @var mixed $payinStateID                 Variable que almacena el identificador del estado de un pago entrante.
 * @var mixed $payinState                   Variable que almacena el estado de un pago entrante.
 * @var mixed $payinStatusErrorCode         Variable que almacena el código de error de un pago entrante.
 * @var mixed $payinStatusErrorMessage      Variable que almacena el mensaje de error de un pago entrante.
 * @var mixed $payinMerchantID              Variable que almacena el identificador del comerciante en una transacción.
 * @var mixed $payinAmount                  Variable que almacena el monto de un pago entrante.
 * @var mixed $payinCurrency                Variable que almacena la moneda de un pago entrante.
 * @var mixed $payinMerchantOperationNumber Variable que almacena el número de operación del comerciante en un pago.
 * @var mixed $payinMethod                  Variable que almacena el método de pago utilizado en una transacción.
 * @var mixed $payinVerification            Variable que almacena el estado de verificación de un pago.
 * @var mixed $Monnet                       Variable que almacena información sobre la plataforma de pagos Monnet.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use \Backend\integrations\payment\Monnet;

/* Obtenemos Variables que nos llegan */
header('Content-Type: application/json');


$data = (file_get_contents('php://input'));

$data = json_decode($data);

if (isset($data)) {
    $payinStateID = $data->payinStateID;
    $payinState = $data->payinState;
    $payinStatusErrorCode = $data->payinStatusErrorCode;
    $payinStatusErrorMessage = $data->payinStatusErrorMessage;
    $payinMerchantID = $data->payinMerchantID;
    $payinAmount = $data->payinAmount;
    $payinCurrency = $data->payinCurrency;
    $payinMerchantOperationNumber = $data->payinMerchantOperationNumber;
    $payinMethod = $data->payinMethod;
    $payinVerification = $data->payinVerification;

    /* Procesamos */
    $Monnet = new Monnet($payinMerchantOperationNumber, $payinAmount, $payinStateID, $payinMethod);
    $Monnet->confirmation(json_encode($data));
}
