<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\UsuarioRecarga;

/**
 * Este script procesa solicitudes para obtener depósitos y retiros con paginación.
 *
 * @param object $params Objeto JSON decodificado que contiene los siguientes parámetros:
 * @param string $params->ToCreatedDateLocal Fecha de finalización en formato local.
 * @param string $params->FromCreatedDateLocal Fecha de inicio en formato local.
 * @param string $params->PaymentSystemId ID del sistema de pago.
 * @param string $params->CashDeskId ID de la caja.
 * @param string $params->ClientId ID del cliente.
 * @param float $params->AmountFrom Monto mínimo.
 * @param float $params->AmountTo Monto máximo.
 * @param string $params->CurrencyId ID de la moneda.
 * @param string $params->ExternalId ID externo de la transacción.
 * @param string $params->Id ID de la transacción.
 * @param int $params->MaxRows Número máximo de filas a devolver.
 * @param int $params->OrderedItem Elemento de ordenación.
 * @param int $params->SkeepRows Número de filas a omitir.
 *
 * @return array $response Respuesta estructurada con los siguientes datos:
 *  - HasError (bool): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta (e.g., "success").
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *  - Data (array): Contiene:
 *    - Documents (array): Incluye:
 *      - Objects (array): Lista de transacciones procesadas.
 *      - Count (int): Número total de transacciones.
 *    - TotalAmount (float): Suma total de los montos procesados.
 */

/* Se obtiene y decodifica un JSON de la entrada para obtener la fecha local. */
$UsuarioRecarga = new UsuarioRecarga();

$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->ToCreatedDateLocal;

/* asigna parámetros a variables para su posterior uso en procesamiento. */
$FromDateLocal = $params->FromCreatedDateLocal;
$PaymentSystemId = $params->PaymentSystemId;
$CashDeskId = $params->CashDeskId;
$ClientId = $params->ClientId;
$AmountFrom = $params->AmountFrom;
$AmountTo = $params->AmountTo;

/* Se asignan parámetros de entrada a variables para su posterior uso en el código. */
$CurrencyId = $params->CurrencyId;
$ExternalId = $params->ExternalId;
$Id = $params->Id;

$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;

/* Se definen reglas para filtrar datos en un rango de fechas. */
$SkeepRows = $params->SkeepRows;

$rules = [];

array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));


/* Agrega reglas al arreglo según condiciones de PaymentSystemId y CashDeskId. */
if ($PaymentSystemId != "") {
    array_push($rules, array("field" => "producto.producto_id", "data" => "$PaymentSystemId", "op" => "eq"));
}

if ($CashDeskId != "") {
    array_push($rules, array("field" => "punto_venta.puntoventa_id", "data" => "$CashDeskId", "op" => "eq"));
}

/* Condicionalmente agrega reglas a un arreglo basado en ClientId y AmountFrom. */
if ($ClientId != "") {
    array_push($rules, array("field" => "usuario_recarga.usuario_id", "data" => "$ClientId", "op" => "eq"));
}

if ($AmountFrom != "") {
    array_push($rules, array("field" => "usuario_recarga.valor", "data" => "$AmountFrom", "op" => "ge"));
}

/* Agrega reglas de validación según el monto y la moneda proporcionados. */
if ($AmountTo != "") {
    array_push($rules, array("field" => "usuario_recarga.valor", "data" => "$AmountTo", "op" => "le"));
}

if ($CurrencyId != "") {
    array_push($rules, array("field" => "usuario.moneda", "data" => "$CurrencyId", "op" => "eq"));
}

/* Añade reglas a un array si las variables no están vacías. */
if ($ExternalId != "") {
    array_push($rules, array("field" => "transaccion_producto.externo_id", "data" => "$ExternalId", "op" => "eq"));
}
if ($Id != "") {
    array_push($rules, array("field" => "transaccion_producto.externo_id", "data" => "$Id", "op" => "eq"));
}


/* Se define un filtro y se inicializan variables si están vacías. */
$filtro = array("rules" => $rules, "groupOp" => "AND");

if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* Configura el número máximo de filas y obtiene datos de transacciones en formato JSON. */
if ($MaxRows == "") {
    $MaxRows = 10;
}

$json = json_encode($filtro);

$transacciones = $UsuarioRecarga->getUsuarioRecargasCustom(" transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true);


/* convierte datos JSON en un arreglo y inicializa variables para procesamiento. */
$transacciones = json_decode($transacciones);

$final = [];
$totalm = 0;
foreach ($transacciones->data as $key => $value) {

    /* inicializa un arreglo y asigna valores basados en condiciones específicas. */
    $array = [];
    $totalm = $totalm + $value->{"transaccion_producto.valor"};
    if ($value->{"producto.descripcion"} == "") {

        $array["Id"] = $value->{"usuario_recarga.recarga_id"};
        $array["ClientId"] = $value->{"usuario_recarga.usuario_id"};
        $array["CreatedLocal"] = $value->{"usuario_recarga.fecha_crea"};
        $array["ModifiedLocal"] = $value->{"usuario_recarga.fecha_crea"};

        $array["Amount"] = $value->{"usuario_recarga.valor"};
        $array["PaymentSystemName"] = "Efectivo";
        $array["TypeName"] = "Payment";

        $array["CurrencyId"] = $value->{"usuario.moneda"};
        $array["CashDeskId"] = $value->{"usuario_recarga.puntoventa_id"};
        $array["State"] = "A";
        $array["Note"] = "";
        $array["ExternalId"] = "";

    } else {
        /* Se asignan valores de un objeto a un array para almacenar información de transacciones. */


        $array["Id"] = $value->{"usuario_recarga.recarga_id"};
        $array["ClientId"] = $value->{"transaccion_producto.usuario_id"};
        $array["CreatedLocal"] = $value->{"usuario_recarga.fecha_crea"};
        $array["ModifiedLocal"] = $value->{"transaccion_producto.fecha_modif"};

        $array["Amount"] = $value->{"transaccion_producto.valor"};

        $array["PaymentSystemName"] = $value->{"producto.descripcion"};
        $array["TypeName"] = "Payment";

        $array["CurrencyId"] = $value->{"usuario.moneda"};
        $array["CashDeskId"] = $value->{"usuario_recarga.puntoventa_id"};
        $array["State"] = $value->{"transaccion_producto.estado_producto"};
        $array["Note"] = "";
        $array["ExternalId"] = $value->{"transaccion_producto.externo_id"};
    }

    /* Agrega el contenido de $array al final del array $final. */
    array_push($final, $array);
}


/* construye una respuesta estructurada con datos sobre documentos y errores. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = array("Documents" => array("Objects" => $final,
    "Count" => $transacciones->count[0]->{".count"}),
    "TotalAmount" => $totalm,
);