<?php

/**
 * Archivo encargado de procesar confirmaciones de pagos mediante SINPE Móvil.
 *
 * Este script recibe datos de transacciones, los procesa y genera una respuesta
 * en formato JSON. Además, registra logs de las solicitudes y respuestas para
 * facilitar la depuración.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales y superglobales utilizadas en el script:
 *
 * @var mixed $headers                  Variable que almacena los encabezados HTTP de la solicitud.
 * @var mixed $_REQUEST                 Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $_ENV                     Variable superglobal que contiene el entorno de variables del sistema.
 * @var mixed $data                     Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $userServer               Variable que almacena el nombre de usuario para acceder a un servidor.
 * @var mixed $passwordServer           Variable que almacena la contraseña para acceder a un servidor.
 * @var mixed $pwServerEncode           Variable que almacena una contraseña codificada para el servidor.
 * @var mixed $log                      Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $ConfigurationEnvironment Esta variable se utiliza para almacenar y manipular la configuración del entorno.
 * @var mixed $Username                 Variable que almacena el nombre de usuario de un sistema.
 * @var mixed $Password                 Variable que almacena una contraseña o clave de acceso.
 * @var mixed $encodecredentials        Variable que almacena credenciales codificadas.
 * @var mixed $ClearDescriptions        Variable que almacena descripciones claras o legibles de una operación.
 * @var mixed $customerIdNumber         Variable que almacena el número de identificación del cliente.
 * @var mixed $customerTypeIdNumber     Variable que almacena el número de identificación del tipo de cliente.
 * @var mixed $numMov                   Variable que almacena el número de movimiento de una transacción.
 * @var mixed $externoId                Variable que almacena un identificador externo en Internpay.
 * @var mixed $approvalDate             Variable que almacena la fecha de aprobación de una transacción.
 * @var mixed $transactionType          Variable que almacena el tipo de transacción realizada.
 * @var mixed $amount                   Variable que almacena un monto o cantidad.
 * @var mixed $currency                 Variable que almacena la moneda utilizada en una transacción.
 * @var mixed $status                   Esta variable se utiliza para almacenar y manipular el estado.
 * @var mixed $TransactionId            Variable que almacena el identificador de una transacción.
 * @var mixed $id                       Variable que almacena un identificador genérico.
 * @var mixed $name                     Variable que almacena el nombre de un archivo, usuario o entidad.
 * @var mixed $iban                     Variable que almacena el número de cuenta bancaria internacional (IBAN).
 * @var mixed $entityCode               Variable que almacena el código de entidad asociado a la transacción.
 * @var mixed $phone                    Variable que almacena el número de teléfono.
 * @var mixed $sinpeMovil               Variable que almacena información relacionada con transacciones mediante SINPE Móvil.
 * @var mixed $response                 Esta variable almacena la respuesta generada por una operación o petición.
 * @var mixed $result                   Variable que almacena el resultado de una operación o transacción.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\ConfigurationEnvironment;
use \Backend\integrations\payment\Kashio;
use Backend\dto\CuentaCobro;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioHistorial;
use Backend\integrations\payment\SinpeMovil;
use Backend\integrations\payout\PAYBROKERSSERVICES;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;

$headers = getallheaders();

/* Obtenemos Variables que nos llegan */
header('Content-Type: application/json');

$data = (file_get_contents('php://input'));

$userServer = $headers['PHP_AUTH_USER'];
$passwordServer = $headers['PHP_AUTH_PW'];
$pwServerEncode = base64_encode($userServer . ':' . $passwordServer);

$data = json_decode($data);

$ConfigurationEnvironment = new ConfigurationEnvironment();

if ($ConfigurationEnvironment->isDevelopment()) {
    $Username = "VirtSimppXVCJ9";
    $Password = "kwIiwibmFtZSI6IkpvaG4";
} else {
    $Username = "39d3e333f1403e8e7872f282be12c0f2";
    $Password = "7f9c5d9e8d5ef7ec95f61e12e8c89f3c";
}

$encodecredentials = base64_encode($Username . ':' . $Password);

if (true) {
    try {
        $descripcion = trim($data->description);

        // Eliminar cualquier texto o símbolos antes del número
        $descripcion = preg_replace('/^[^\d]+/', '', $descripcion);

        // Si contiene guión, cortar antes
        if (strpos($descripcion, '-') !== false) {
            $descripcion = explode('-', $descripcion)[0];
        }

        // Validar que tenga un número al inicio
        if (preg_match('/^\d+/', $descripcion, $coincidencia)) {
            $ClearDescriptions = $coincidencia[0];
        }
    } catch (Exception $e) {
    }

    //sinpemovil
    $customerIdNumber = $data->customerIdNumber;
    $customerTypeIdNumber = $data->customerTypeIdNumber;
    $numMov = $data->numMov;
    $externoId = $data->sinpeReference;
    $approvalDate = $data->approvalDate;
    $transactionType = $data->transactionType;
    $amount = $data->amount;
    $currency = $data->currency;
    $status = $data->status;
    $TransactionId = $ClearDescriptions;
    $id = $data->id;
    $name = $data->name;
    $iban = $data->iban;
    $entityCode = $data->entityCode;
    $phone = $data->phone;

    /* Procesamos */
    $sinpeMovil = new SinpeMovil($TransactionId, $status, $externoId, $amount);
    $response = $sinpeMovil->confirmation(json_encode($data));

    syslog(LOG_WARNING, "SINPEMOVIL REQUEST: " . json_encode($data) . "SAVE-NEW TRANSACTION_ID" . $TransactionId . "RESPONSE: " . json_encode($response));

    $result = $response;

    $data = [];
    $data["success"] = true;
    $data["status"] = $status;
    $data["code"] = 0;
    $data["error"] = false;
    $data["message"] = "Recibido con exito. $TransactionId ";
    $data["result"] = $result;

    http_response_code(200);
    print_r(json_encode($data));
} else {
    http_response_code(401);
    echo "Error: No coinciden las credenciales ";
}


