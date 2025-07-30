<?php

/**
 * Archivo encargado de manejar las integraciones de pago con ClubPago.
 * Este script procesa solicitudes relacionadas con consultas, pagos y cancelaciones de transacciones.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
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
 * @var mixed $string                   Esta variable contiene una cadena de texto, utilizada para representar información textual.
 * @var mixed $token                    Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $Proveedor                Esta variable representa la información del proveedor, utilizada para operaciones comerciales o logísticas.
 * @var mixed $reference                Variable que almacena una referencia para una transacción o proceso.
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
 * @var mixed $monto                    Variable que almacena el monto de una transacción.
 * @var mixed $Registro                 Variable que almacena información sobre un registro.
 * @var mixed $invoice                  Variable que almacena el identificador de una factura.
 * @var mixed $result                   Variable que almacena el resultado de una operación o transacción.
 * @var mixed $documento_id             Variable que almacena el identificador de un documento.
 * @var mixed $valor                    Variable que almacena un valor monetario o numérico.
 * @var mixed $usuario_id               Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $control                  Variable que almacena un código de control para una operación.
 * @var mixed $datos                    Variable que almacena datos genéricos.
 * @var mixed $ClubPago                 Variable que almacena información relacionada con ClubPago.
 * @var mixed $TransaccionProducto2     Variable que almacena información sobre una segunda transacción de producto.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Registro;
use Backend\integrations\payment\ClubPago;

/* Obtenemos Variables que nos llegan */
$URI = $_SERVER['REQUEST_URI'];
$data = (file_get_contents('php://input'));

/**
 * Abre un archivo de log para registrar las solicitudes entrantes.
 */
$fp = fopen('log_' . date("Y-m-d") . '.log', 'a');
header('Content-type: application/json; charset=utf-8');
fwrite($fp, json_encode($_REQUEST));
fwrite($fp, json_encode($data));
fwrite($fp, file_get_contents('php://input'));
fclose($fp);

$data = json_decode($data);
$headers = getallheaders();

/**
 * Configuración del entorno para determinar si se encuentra en desarrollo o producción.
 */
$ConfigurationEnvironment = new ConfigurationEnvironment();

if ($ConfigurationEnvironment->isDevelopment()) {
    $string = "User=VirtualSoft&Password=gH2F0qj^sS9g";
    $token = base64_encode($string);
} else {
    $string = "User=VirtualSoft&Password=gQReMRj6mhp66";
    $token = base64_encode($string);
}

/**
 * Normaliza el encabezado `x-origin` en caso de que no esté definido.
 */
if ($headers["x-origin"] == "") {
    $headers["x-origin"] = $headers["X-Origin"];
}

/**
 * Instancia del proveedor ClubPago.
 */
$Proveedor = new \Backend\dto\Proveedor("", "CLUBPAGO");

/**
 * Verifica si el token de autenticación es válido.
 */
if ($token == $headers["x-origin"]) {
    /**
     * Maneja solicitudes de consulta de referencia.
     */
    if (strpos($URI, "ConsultaReferencia") !== false) {
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
        array_push($rules, array("field" => "proveedor.proveedor_id", "data" => $Proveedor->getProveedorId(), "op" => "eq"));
        array_push($rules, array("field" => "producto.estado", "data" => "A", "op" => "eq"));
        array_push($rules, array("field" => "transaccion_producto.externo_id", "data" => $reference, "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $jsonbetshop = json_encode($filtro);

        $TransaccionProducto = new \Backend\dto\TransaccionProducto();
        $transacciones = $TransaccionProducto->getTransaccionesCustom2("transaccion_producto.*", "transaccion_producto.transproducto_id", "asc", 0, 1, $jsonbetshop, true);
        $transacciones = json_decode($transacciones);

        if ($transacciones->count[0]->{".count"} != 0) {
            foreach ($transacciones->data as $key => $value) {
                $response = array(
                    "codigo" => 0,
                    "mensaje" => "Transacción Exitosa",
                    "monto" => floatval($value->{"transaccion_producto.valor"} * 100),
                    "referencia" => $value->{"transaccion_producto.externo_id"},
                    "transaccion" => intval($value->{"transaccion_producto.transproducto_id"}),
                    "parcial" => 0
                );
            }
        } else {
            $response = array(
                "codigo" => 40,
                "mensaje" => "Referencia Desconocida",
                "monto" => 0,
                "referencia" => $_REQUEST["r"],
                "transaccion" => 0
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

    /**
     * Maneja solicitudes de pago por referencia.
     */
    if (strpos($URI, "PagoReferencia") !== false) {
        $reference = $data->referencia;
        $monto = $data->monto;
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
        array_push($rules, array("field" => "proveedor.proveedor_id", "data" => $Proveedor->getProveedorId(), "op" => "eq"));
        array_push($rules, array("field" => "producto.estado", "data" => "A", "op" => "eq"));
        array_push($rules, array("field" => "transaccion_producto.externo_id", "data" => $reference, "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $jsonbetshop = json_encode($filtro);

        $TransaccionProducto = new \Backend\dto\TransaccionProducto();
        $transacciones = $TransaccionProducto->getTransaccionesCustom2("transaccion_producto.*", "transaccion_producto.transproducto_id", "asc", 0, 1, $jsonbetshop, true);
        $transacciones = json_decode($transacciones);

        foreach ($transacciones->data as $key => $value) {
            if (($value->{"transaccion_producto.valor"} * 100) == $monto) {
                if ($value->{"transaccion_producto.estado"} == "I" && $value->{"transaccion_producto.estado_producto"} == 'A') {
                    $response = array(
                        "codigo" => 13,
                        "mensaje" => "Esta referencia ya fue pagada"
                    );
                } else {
                    $Registro = new Registro("", $value->{"transaccion_producto.usuario_id"});
                    $invoice = $value->{"transaccion_producto.transproducto_id"};
                    $result = "APROBADO";
                    $documento_id = $value->{"transaccion_producto.externo_id"};
                    $valor = $value->{"transaccion_producto.valor"};
                    $usuario_id = $value->{"transaccion_producto.usuario_id"};
                    $control = "";
                    $datos = array(
                        "invoice" => $invoice,
                        "result" => $result,
                        "documento_id" => $documento_id,
                        "valor" => $valor,
                        "usuario_id" => $usuario_id,
                    );

                    $ClubPago = new ClubPago($invoice, $usuario_id, $documento_id, $valor, $control, $result);
                    $ClubPago->confirmation(json_encode($datos));

                    sleep(2);
                    $TransaccionProducto2 = new \Backend\dto\TransaccionProducto($value->{"transaccion_producto.transproducto_id"});
                    $response = array(
                        "codigo" => 0,
                        "autorizacion" => intval($TransaccionProducto2->getFinalId()),
                        "mensaje" => "Transacción Exitosa",
                        "transaccion" => intval($TransaccionProducto2->transproductoId),
                        "fecha" => $value->{"transaccion_producto.fecha_crea"},
                        "notificacion_sms" => $Registro->getCelular(),
                        "mensaje_sms" => "Deposito Realizado"
                    );
                }
            } else {
                $response = array(
                    "codigo" => 30,
                    "mensaje" => "El monto de la transacción es inválido"
                );
            }
        }

        $response = json_encode($response);
        $log = "";
        $log = $log . "/" . time();
        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);
        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);
        print_r($response);
    }

    /**
     * Maneja solicitudes de cancelación de pagos.
     */
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
        array_push($rules, array("field" => "proveedor.proveedor_id", "data" => $Proveedor->getProveedorId(), "op" => "eq"));
        array_push($rules, array("field" => "producto.estado", "data" => "A", "op" => "eq"));
        array_push($rules, array("field" => "transaccion_producto.externo_id", "data" => $reference, "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $jsonbetshop = json_encode($filtro);

        $TransaccionProducto = new \Backend\dto\TransaccionProducto();
        $transacciones = $TransaccionProducto->getTransaccionesCustom2("transaccion_producto.*", "transaccion_producto.transproducto_id", "asc", 0, 1, $jsonbetshop, true);
        $transacciones = json_decode($transacciones);

        foreach ($transacciones->data as $key => $value) {
            if ($value->{"transaccion_producto.estado"} == "A" && $value->{"transaccion_producto.estado_producto"} == 'E') {
                $invoice = $value->{"transaccion_producto.transproducto_id"};
                $result = "RECHAZADO";
                $documento_id = $value->{"transaccion_producto.externo_id"};
                $valor = $value->{"transaccion_producto.valor"};
                $usuario_id = $value->{"transaccion_producto.usuario_id"};
                $control = "";
                $datos = array(
                    "invoice" => $invoice,
                    "result" => $result,
                    "documento_id" => $documento_id,
                    "valor" => $valor,
                    "usuario_id" => $usuario_id,
                );

                $ClubPago = new ClubPago($invoice, $usuario_id, $documento_id, $valor, $control, $result);
                $ClubPago->confirmation(json_encode($datos));

                $response = array(
                    "codigo" => 0,
                    "mensaje" => "Transacción Cancelada"
                );
            } else {
                $response = array(
                    "codigo" => 61,
                    "mensaje" => "Cancelación fallida"
                );
            }
        }

        $response = json_encode($response);
        $log = "";
        $log = $log . "/" . time();
        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);
        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);
        print_r($response);
    }
} else {
    /**
     * Respuesta en caso de token inválido.
     */
    $response = array(
        "codigo" => 1,
        "mensaje" => "Token Invalido"
    );

    $response = json_encode($response);
    $log = "";
    $log = $log . "/" . time();
    $log = $log . "\r\n" . "-------------------------" . "\r\n";
    $log = $log . ($response);
    fwriteCustom('log_' . date("Y-m-d") . '.log', $log);
    print_r($response);
}

