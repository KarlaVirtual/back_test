<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con PayRetailers.
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
 * @var mixed $_ENV              Variable superglobal que contiene el entorno de variables del sistema.
 * @var mixed $data              Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $fp                Variable que almacena información sobre la forma de pago.
 * @var mixed $log               Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $_REQUEST          Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $Username          Variable que almacena el nombre de usuario de un sistema.
 * @var mixed $_SERVER           Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $Password          Variable que almacena una contraseña o clave de acceso.
 * @var mixed $uid               Variable que almacena el identificador único de un usuario.
 * @var mixed $type              Esta variable se utiliza para almacenar y manipular el tipo.
 * @var mixed $status            Esta variable se utiliza para almacenar y manipular el estado.
 * @var mixed $message           Variable que almacena un mensaje informativo o de error dentro del sistema.
 * @var mixed $trackingId        Variable que almacena el ID de seguimiento.
 * @var mixed $amount            Variable que almacena un monto o cantidad.
 * @var mixed $currency          Variable que almacena la moneda utilizada en una transacción.
 * @var mixed $amountChanged     Variable que indica si el monto ha cambiado.
 * @var mixed $originalAmount    Variable que almacena el monto original.
 * @var mixed $newCouponAmount   Variable que almacena el nuevo monto con cupón aplicado.
 * @var mixed $newCouponCurrency Variable que define la nueva moneda tras aplicar el cupón.
 * @var mixed $description       Variable que almacena una descripción.
 * @var mixed $cardNumber        Variable que almacena el número de tarjeta.
 * @var mixed $createdAt         Esta variable se utiliza para almacenar y manipular la fecha de creación.
 * @var mixed $updatedAt         Variable que almacena la fecha de la última actualización.
 * @var mixed $firstName         Variable que almacena el primer nombre.
 * @var mixed $lastName          Variable que almacena el apellido.
 * @var mixed $email             Variable que almacena la dirección de correo electrónico de un usuario.
 * @var mixed $country           Esta variable indica la cantidad total de elementos o registros, útil para iteraciones o validaciones.
 * @var mixed $city              Variable que almacena el nombre de la ciudad.
 * @var mixed $zip               Variable que almacena el código postal.
 * @var mixed $address           Variable que almacena una dirección.
 * @var mixed $phone             Variable que almacena el número de teléfono.
 * @var mixed $deviceId          Variable que almacena el ID del dispositivo.
 * @var mixed $ip                Variable que almacena la dirección IP.
 * @var mixed $personalId        Variable que almacena el ID personal.
 * @var mixed $id                Variable que almacena un identificador genérico.
 * @var mixed $name              Variable que almacena el nombre de un archivo, usuario o entidad.
 * @var mixed $PayRetailers      Variable que almacena información relacionada con PayRetailers.
 */


require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\payment\PayRetailers;

/* Obtenemos Variables que nos llegan */
header('Content-Type: application/json');

$data = (file_get_contents('php://input'));

$data = json_decode($data);

if (isset($data)) {
    $trackingId = $data->trackingId;
    $personalId = $data->customer->personalId;
    $amount = $data->billing->amount;
    $status = $data->status;
    $uid = $data->uid;

    /* Procesamos */
    $PayRetailers = new PayRetailers($trackingId, $personalId, $amount, $status, $uid);
    $PayRetailers->confirmation(json_encode($data));
}
