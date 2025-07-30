<?php

/**
 * Este archivo maneja la confirmación de pagos a través del sistema Fri.
 * Procesa los datos recibidos en formato JSON, registra logs y utiliza la clase Fri
 * para realizar la confirmación de la transacción.
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
 * @var mixed $_REQUEST            Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $data                Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $fp                  Variable que almacena información sobre la forma de pago.
 * @var mixed $log                 Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $_SERVER             Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $_POST               Arreglo global que contiene los datos enviados mediante el método POST.
 * @var mixed $Username            Variable que almacena el nombre de usuario de un sistema.
 * @var mixed $Password            Variable que almacena una contraseña o clave de acceso.
 * @var mixed $id                  Variable que almacena un identificador genérico.
 * @var mixed $businessId          Variable que almacena el identificador de un negocio o empresa.
 * @var mixed $businessUserId      Variable que almacena el identificador de un usuario dentro de un negocio.
 * @var mixed $reference           Variable que almacena una referencia para una transacción o proceso.
 * @var mixed $paymentRequestId    Variable que almacena el identificador de una solicitud de pago.
 * @var mixed $transferId          Variable que almacena el identificador de una transferencia.
 * @var mixed $authorizationNumber Variable que almacena un número de autorización.
 * @var mixed $amount              Variable que almacena un monto o cantidad.
 * @var mixed $formattedAmount     Variable que almacena un monto con formato.
 * @var mixed $creationDate        Variable que almacena la fecha de creación de un registro.
 * @var mixed $status              Esta variable se utiliza para almacenar y manipular el estado.
 * @var mixed $friUserId           Variable que almacena el identificador de un usuario en el sistema Fri.
 * @var mixed $username            Variable que almacena el nombre de usuario.
 * @var mixed $name                Variable que almacena el nombre de un archivo, usuario o entidad.
 * @var mixed $lastname            Variable que almacena el apellido de un usuario.
 * @var mixed $avatar              Variable que almacena la URL o referencia de la imagen de perfil de un usuario.
 * @var mixed $countryCode         Esta variable indica la cantidad total de elementos o registros, útil para iteraciones o validaciones.
 * @var mixed $phoneNumber         Variable que almacena el número de teléfono de un usuario.
 * @var mixed $emailAddress        Variable que almacena la dirección de correo electrónico de un usuario.
 * @var mixed $Fri                 Variable que almacena información relacionada con el sistema Fri.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use \Backend\integrations\payment\Fri;

/* Obtenemos Variables que nos llegan */
header('Content-Type: application/json');

if ($_REQUEST["isDebug"] == 1) {
    error_reporting(E_ALL);
    ini_set(
        "display_errors", "ON"
    );
}

$data = (file_get_contents('php://input'));

$fp = fopen('log_' . date("Y-m-d") . '.log', 'a');

$log = " /" . time();

$log = $log . "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
$log = $log . json_encode($_REQUEST);
$log = $log . "******";
$log = $log . json_encode($_SERVER);
$log = $log . "######";
$log = $log . json_encode($_POST);
$log = $log . $data;

fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

$data = json_decode($data);

if (isset($data)) {
    $id = $data->id;
    $businessId = $data->businessId;
    $businessUserId = $data->businessUserId;
    $reference = $data->reference;
    $paymentRequestId = $data->paymentRequestId;
    $transferId = $data->transferId;
    $authorizationNumber = $data->authorizationNumber;
    $amount = $data->amount;


    $formattedAmount = $data->formattedAmount;
    $creationDate = $data->creationDate;
    $status = $data->status;
    $friUserId = $data->friUser->id;
    $username = $data->friUser->username;
    $name = $data->friUser->name;
    $lastname = $data->friUser->lastname;
    $avatar = $data->friUser->avatar;
    $countryCode = $data->friUser->countryCode;
    $phoneNumber = $data->friUser->phoneNumber;
    $emailAddress = $data->friUser->emailAddress;


    /* Procesamos */
    $Fri = new Fri($reference, $formattedAmount, $status, $id);
    $Fri->confirmation(json_encode($data));
}
