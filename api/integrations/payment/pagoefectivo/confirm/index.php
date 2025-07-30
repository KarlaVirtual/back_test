<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con PagoEfectivo.
 * Procesa las solicitudes HTTP entrantes, registra logs y realiza operaciones relacionadas
 * con la confirmación de pagos y transacciones de productos.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payment\PagoEfectivo
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-02-06
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $log                 Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $_REQUEST            Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $body                Esta variable contiene el contenido del cuerpo de la solicitud HTTP.
 * @var mixed $data                Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $PagoEfectivo        Variable que almacena información de pagos en efectivo.
 * @var mixed $estado              Variable que almacena el estado de un proceso o entidad.
 * @var mixed $comentario          Variable que almacena comentarios.
 * @var mixed $tipo_genera         Variable que define el tipo de generación.
 * @var mixed $OrdenID             Variable que almacena el ID de la orden.
 * @var mixed $t_value             Variable que almacena un valor temporal.
 * @var mixed $TransaccionProducto Variable que almacena información sobre una transacción de producto.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');
exit();

use Backend\integrations\payment\PagoEfectivo;
use Backend\dto\TransaccionProducto;

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . json_encode($_REQUEST);
$log = $log . trim(file_get_contents('php://input'));
fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

$body = json_encode($_REQUEST);

$data = $_REQUEST['data'];

if ($data != "") {
    /* Procesamos */

    $PagoEfectivo = new PagoEfectivo();

    $PagoEfectivo->confirmation($data);
}

if ($_REQUEST["pagada"] != "") {
    // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
    $estado = 'A';

    // Comentario personalizado para el log
    $comentario = 'Aprobada por PagoEfectivo ';
    $tipo_genera = 'A';
    $OrdenID = 14583656;
    $OrdenID = $_REQUEST["orden"];

    $t_value = "{}";

    print_r("ENTRP" . $_REQUEST["pagada"]);

    $TransaccionProducto = new TransaccionProducto($_REQUEST["pagada"]);

    print_r($TransaccionProducto);

    print_r($TransaccionProducto->setAprobada($_REQUEST["pagada"], $tipo_genera, $estado, $comentario, $t_value, $OrdenID));
}