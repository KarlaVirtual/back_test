<?php

/**
 * Este archivo contiene la implementación de un servicio de integración de pagos
 * para ClubPago. Proporciona funcionalidades para consultar referencias, realizar
 * pagos y cancelar pagos, interactuando con transacciones y productos.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables Globales:
 *
 * @var mixed $URI                      Esta variable contiene el URI de la petición actual.
 * @var mixed $_SERVER                  Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $data                     Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $fp                       Variable que almacena información sobre la forma de pago.
 * @var mixed $_REQUEST                 Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $headers                  Variable que almacena los encabezados HTTP de la solicitud.
 * @var mixed $ConfigurationEnvironment Esta variable se utiliza para almacenar y manipular la configuración del entorno.
 * @var mixed $token                    Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $string                   Esta variable contiene una cadena de texto, utilizada para representar información textual.
 * @var mixed $reference                Variable que almacena una referencia para una transacción o proceso.
 * @var mixed $Proveedor                Esta variable representa la información del proveedor, utilizada para operaciones comerciales o logísticas.
 * @var mixed $OrderedItem              Variable que representa un elemento ordenado en una lista.
 * @var mixed $_GET                     Arreglo global que contiene los datos enviados mediante el método GET.
 * @var mixed $MaxRows                  Variable que define el número máximo de registros a retornar en una consulta.
 * @var mixed $SkeepRows                Variable que indica el número de registros a omitir en una consulta.
 * @var mixed $rules                    Esta variable contiene las reglas de validación o negocio, utilizadas para controlar el flujo de operaciones.
 * @var mixed $filtro                   Esta variable contiene criterios de filtrado para la búsqueda o procesamiento de datos.
 * @var mixed $jsonbetshop              Esta variable contiene datos en formato JSON específicos para la integración con Betshop.
 * @var mixed $TransaccionProducto      Variable que almacena información sobre una transacción de producto.
 * @var mixed $transacciones            Variable que almacena un conjunto de transacciones.
 * @var mixed $key                      Esta variable se utiliza para almacenar y manipular claves genéricas.
 * @var mixed $value                    Esta variable se utiliza para almacenar y manipular valores asociados a claves.
 * @var mixed $response                 Esta variable almacena la respuesta generada por una operación o petición.
 * @var mixed $log                      Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $invoice                  Variable que almacena el identificador de una factura.
 * @var mixed $result                   Variable que almacena el resultado de una operación o transacción.
 * @var mixed $documento_id             Variable que almacena el identificador de un documento.
 * @var mixed $valor                    Variable que almacena un valor monetario o numérico.
 * @var mixed $usuario_id               Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $control                  Variable que almacena un código de control para una operación.
 * @var mixed $ClubPago                 Variable que almacena información relacionada con ClubPago.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\payment\CoinPayments;


/* Obtenemos Variables que nos llegan */
$URI = $_SERVER['REQUEST_URI'];
$data = (file_get_contents('php://input'));

$fp = fopen('log_' . date("Y-m-d") . '.log', 'a');
header('Content-type: application/json; charset=utf-8');
fwrite($fp, json_encode($_REQUEST));
fwrite($fp, json_encode($data));
fwrite($fp, file_get_contents('php://input'));

fclose($fp);
$data = str_replace("&", '","', $data);
$data = str_replace("=", '":"', $data);
$data = '{"' . $data . '"}';
$data = json_decode($data);
$headers = getallheaders();

$ConfigurationEnvironment = new ConfigurationEnvironment();

if ($ConfigurationEnvironment->isDevelopment()) {
    $data = "User=VirtualSoft&Password=gH2F0qj^sS9g";
    $token = base64_encode($data);
} else {
    $string = "User=VirtualSoft&Password=gQReMRj6mhp66";
    $token = base64_encode($string);
}

print_r($token);
exit();

if ($token == $headers["x-origin"]) {
    if (strpos($URI, "ConsultaReferencia") !== false) {
        $reference = $_REQUEST["r"];

        //$Proveedor = new \Backend\dto\Proveedor("","CLUBPAGO");


        $OrderedItem = $_GET["OrderedItem"];

        $MaxRows = $_REQUEST["Count"];
        $SkeepRows = ($_REQUEST["Start"] == "") ? $_REQUEST["?Start"] : $_REQUEST["Start"];

        if ($SkeepRows == "") {
            $SkeepRows = 0;
        }

        if ($OrderedItem == "") {
            $OrderedItem = 1;
        }

        if ($MaxRows == "") {
            $MaxRows = 1;
        }
        //$Proveedor->proveedorId
        $rules = [];
        array_push($rules, array("field" => "proveedor.tipo", "data" => "PAYMENT", "op" => "eq"));
        array_push($rules, array("field" => "proveedor.estado", "data" => "A", "op" => "eq"));
        array_push($rules, array("field" => "proveedor.proveedor_id", "data" => 125, "op" => "eq"));
        //array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "A", "op" => "eq"));

        array_push($rules, array("field" => "producto.estado", "data" => "A", "op" => "eq"));
        array_push($rules, array("field" => "transaccion_producto.externo_id", "data" => $reference, "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $jsonbetshop = json_encode($filtro);


        $TransaccionProducto = new \Backend\dto\TransaccionProducto();


        $transacciones = $TransaccionProducto->getTransaccionesCustom2("transaccion_producto.*", "transaccion_producto.transproducto_id", "asc", 0, 1, $jsonbetshop, true);

        $transacciones = json_decode($transacciones);


        foreach ($transacciones->data as $key => $value) {
            $response = array(
                "codigo" => 0,
                "mensaje" => "Transacción Exitosa",
                "monto" => floatval($value->{"transaccion_producto.valor"}),
                "referencia" => $value->{"transaccion_producto.externo_id"},
                "transaccion" => intval($value->{"transaccion_producto.transproducto_id"}),
                "parcial" => 0
            );
            // }
        }

        $response = json_encode($response);

        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);

        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

        print_r($response);
    }
}
if (strpos($URI, "PagoReferencia") !== false) {
    $reference = $_REQUEST["r"];

    $OrderedItem = $_GET["OrderedItem"];

    $MaxRows = $_REQUEST["Count"];
    $SkeepRows = ($_REQUEST["Start"] == "") ? $_REQUEST["?Start"] : $_REQUEST["Start"];

    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }

    if ($MaxRows == "") {
        $MaxRows = 1;
    }
    //$Proveedor->proveedorId
    $rules = [];
    array_push($rules, array("field" => "proveedor.tipo", "data" => "PAYMENT", "op" => "eq"));
    array_push($rules, array("field" => "proveedor.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "proveedor.proveedor_id", "data" => 125, "op" => "eq"));

    array_push($rules, array("field" => "producto.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "transaccion_producto.externo_id", "data" => $reference, "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $jsonbetshop = json_encode($filtro);


    $TransaccionProducto = new \Backend\dto\TransaccionProducto();


    $transacciones = $TransaccionProducto->getTransaccionesCustom2("transaccion_producto.*", "transaccion_producto.transproducto_id", "asc", 0, 1, $jsonbetshop, true);

    $transacciones = json_decode($transacciones);


    foreach ($transacciones->data as $key => $value) {
        $response = array(
            "codigo" => 0,
            "autorizacion" => "",
            "mensaje" => "Transacción Exitosa",
            "transaccion" => intval($value->{"transaccion_producto.transproducto_id"}),
            "fecha" => floatval($value->{"transaccion_producto.fecha_crea"}),
            "notificacion_sms" => floatval($value->{"transaccion_producto.fecha_crea"}),
            "mensaje_sms" => floatval($value->{"transaccion_producto.fecha_crea"})
        );
    }
    $invoice = $value->{"transaccion_producto.transproducto_id"};
    $result = "APROBADO";
    $documento_id = $value->{"transaccion_producto.externo_id"};
    $valor = $value->{"transaccion_producto.valor"};
    $usuario_id = $value->{"transaccion_producto.usuario_id"};
    $control = "";

    /* Procesamos */
    $ClubPago = new \Backend\Integrations\payment\ClubPago($invoice, $usuario_id, $documento_id, $valor, $control, $result);
    $ClubPago->confirmation(json_encode($data));

    $response = json_encode($response);

    $log = "";
    $log = $log . "/" . time();

    $log = $log . "\r\n" . "-------------------------" . "\r\n";
    $log = $log . ($response);

    fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

    print_r($response);
}

if (strpos($URI, "CancelaPago") !== false) {
    $reference = $_REQUEST["r"];
    $OrderedItem = $_GET["OrderedItem"];

    $MaxRows = $_REQUEST["Count"];
    $SkeepRows = ($_REQUEST["Start"] == "") ? $_REQUEST["?Start"] : $_REQUEST["Start"];

    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }

    if ($MaxRows == "") {
        $MaxRows = 1;
    }
    $rules = [];
    array_push($rules, array("field" => "proveedor.tipo", "data" => "PAYMENT", "op" => "eq"));
    array_push($rules, array("field" => "proveedor.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "proveedor.proveedor_id", "data" => 125, "op" => "eq"));

    array_push($rules, array("field" => "producto.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "transaccion_producto.externo_id", "data" => $reference, "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $jsonbetshop = json_encode($filtro);


    $TransaccionProducto = new \Backend\dto\TransaccionProducto();


    $transacciones = $TransaccionProducto->getTransaccionesCustom2("transaccion_producto.*", "transaccion_producto.transproducto_id", "asc", 0, 1, $jsonbetshop, true);

    $transacciones = json_decode($transacciones);


    foreach ($transacciones->data as $key => $value) {
        $response = array(
            "codigo" => 0,
            "mensaje" => "Transacción Cancelada"
        );
    }

    $response = json_encode($response);

    $log = "";
    $log = $log . "/" . time();

    $log = $log . "\r\n" . "-------------------------" . "\r\n";
    $log = $log . ($response);

    fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

    print_r($response);
}