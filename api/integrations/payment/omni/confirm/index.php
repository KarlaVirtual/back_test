<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con Omni.
 * Procesa los datos recibidos, realiza validaciones y registra los resultados en un archivo de log.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payment\Omni
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $data         Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $fp           Variable que almacena información sobre la forma de pago.
 * @var mixed $log          Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $_REQUEST     Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $confirm      Variable que almacena la confirmación de una operación o transacción.
 * @var mixed $valor        Variable que almacena un valor monetario o numérico.
 * @var mixed $usuario_id   Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $usuarioId    Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $invoice      Variable que almacena el identificador de una factura.
 * @var mixed $result       Variable que almacena el resultado de una operación o transacción.
 * @var mixed $documento_id Variable que almacena el identificador de un documento.
 * @var mixed $control      Variable que almacena un código de control para una operación.
 * @var mixed $Omni         Variable utilizada para configuraciones de Omni.
 * @var mixed $response     Esta variable almacena la respuesta generada por una operación o petición.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\Producto;
use Backend\dto\proveedor;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransprodLog;
use Backend\dto\TransproductoDetalle;
use Backend\dto\Usuario;
use Backend\integrations\payment\Omni;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;


/* Obtenemos Variables que nos llegan */

$data = (file_get_contents('php://input'));

$fp = fopen('log_' . date("Y-m-d") . '.log', 'a');

$log = " /" . time();

$log = $log . "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
$log = $log . json_encode($_REQUEST);
$log = $log . json_encode($data);
fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


$data = json_decode($data);

if (isset($data)) {
    $confirm = ($data);


    $valor = $confirm->transaction->amount;
    $usuario_id = $confirm->accountBasicData->accountID;

    $usuario_id = explode("Usuario", $usuario_id);
    $usuarioId = $usuario_id[1];

    $invoice = "";
    $result = $confirm->transaction->pspMessage;
    $documento_id = $confirm->transaction->tagTransactionId;


    $control = "";
    if ($result == "OK") {
        /* Procesamos */
        $Omni = new Omni($invoice, $usuarioId, $documento_id, $valor, $control, $result);
        $response = $Omni->confirmation(json_encode($data));
    }
    $log = "";
    $log = $log . "/" . time();
    $log = $log . "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
    $log = $log . ($response);

    fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

    print_r($response);
}