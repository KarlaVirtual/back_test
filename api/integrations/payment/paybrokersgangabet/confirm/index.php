<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con PayBrokers.
 * Procesa datos encriptados, realiza solicitudes a servicios externos y registra información
 * relevante para la confirmación de transacciones.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-02-06
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $data               Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $cryptbody          Variable que almacena el cuerpo del mensaje encriptado.
 * @var mixed $decrypBody         Variable que almacena el cuerpo del mensaje desencriptado.
 * @var mixed $URI                Esta variable contiene el URI de la petición actual.
 * @var mixed $_SERVER            Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $log                Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $_REQUEST           Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $confirm            Variable que almacena la confirmación de una operación o transacción.
 * @var mixed $id                 Variable que almacena un identificador genérico.
 * @var mixed $value              Esta variable se utiliza para almacenar y manipular valores asociados a claves.
 * @var mixed $paid_value         Variable que almacena el valor pagado.
 * @var mixed $paid_at            Variable que almacena la fecha y hora de un pago.
 * @var mixed $status             Esta variable se utiliza para almacenar y manipular el estado.
 * @var mixed $PAYBROKERSSERVICES Variable relacionada con los servicios de PAYBROKERS.
 * @var mixed $header             Variable que almacena un encabezado HTTP individual.
 * @var mixed $Respueta           Variable que almacena la respuesta del sistema.
 * @var mixed $token              Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $respuesta          Esta variable se utiliza para almacenar y manipular la respuesta de una operación.
 * @var mixed $TransactionId      Variable que almacena el identificador de una transacción.
 * @var mixed $txid               Variable que almacena el ID de la transacción (txid).
 * @var mixed $Paybrokers         Variable que almacena información relacionada con PayBrokers.
 */


require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\TransaccionProducto;
use Backend\dto\Usuario;
use Backend\integrations\payment\Paybrokers;

/* Obtenemos Variables que nos llegan */

$data = (file_get_contents('php://input'));

$data = json_decode($data);

$cryptbody = ($data->bodyEncrypted);
$cryptbody = explode(".", $cryptbody);

$decrypBody = base64_decode($cryptbody[1]);
$decrypBody = json_decode(($decrypBody));

$data = $decrypBody;
$URI = $_SERVER['REQUEST_URI'];


if (isset($data)) {
    $confirm = ($data);

    $id = $confirm->id;
    $value = $confirm->value;
    $paid_value = $confirm->paid_value;
    $paid_at = $confirm->paid_at;
    $status = $confirm->status;
    $transaccion_id = $confirm->reference_id;

    $TransaccionProducto = new TransaccionProducto($transaccion_id);
    $Usuario = new Usuario($TransaccionProducto->usuarioId);

    $PAYBROKERSSERVICES = new Backend\integrations\payment\PAYBROKERSSERVICES();
    $header = $PAYBROKERSSERVICES->buildAuthorizationHeader('18', $Usuario);

    $Respueta = $PAYBROKERSSERVICES->ConnectionToken($header, '18', $Usuario);

    $token = $Respueta->token;

    $Credentials = $PAYBROKERSSERVICES->Credentials($Usuario);
    $URL = $Credentials->URL;

    $respuesta = $PAYBROKERSSERVICES->GetPIXPayment($id, $token, '18', $URL);

    syslog(LOG_WARNING, "PAYBROKERS Respuesta PIX PAYMENT" . json_encode($respuesta));

    $TransactionId = $respuesta->reference_id;
    $txid = $respuesta->acquirer_id;
    $value = $respuesta->value;
    $status = $respuesta->status;

    /* Procesamos */
    $Paybrokers = new Paybrokers($id, $TransactionId, $value, $status);

    $Paybrokers->confirmation($data);
}