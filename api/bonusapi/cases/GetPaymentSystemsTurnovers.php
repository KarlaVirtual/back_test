<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\TransaccionProducto;

/**
 * Este script obtiene los movimientos de sistemas de pago y los organiza en un formato específico.
 *
 * @param object $params Objeto JSON decodificado con los parámetros de entrada.
 * @param string $params->StartTimeLocal Fecha de inicio del rango de consulta.
 * @param string $params->EndTimeLocal Fecha de fin del rango de consulta.
 * @param string $params->TypeId ID del tipo de producto.
 * @param int $params->SkeepRows Número de filas a omitir en la consulta.
 * @param int $params->OrderedItem Orden de los elementos.
 * @param int $params->MaxRows Número máximo de filas a devolver.
 * 
 *
 * @return array $response Respuesta estructurada con los datos de los movimientos.
 * - HasError: Indica si hubo un error (false si no hay errores).
 * - AlertType: Tipo de alerta (success en caso de éxito).
 * - AlertMessage: Mensaje de alerta (vacío en caso de éxito).
 * - ModelErrors: Lista de errores del modelo (vacío en caso de éxito).
 * - Data: Lista de movimientos con los campos PaymentTypeName, AccountId, Debit, Credit, CurrencyId y ReciboId.
 */

/* Se crea un objeto y se decodifica un JSON de entrada para obtener una fecha. */
$TransaccionProducto = new TransaccionProducto();

$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->StartTimeLocal;

/* Asignación de variables desde un objeto $params utilizando propiedades específicas. */
$FromDateLocal = $params->EndTimeLocal;
$TypeId = $params->TypeId;

$FromDateLocal = $params->EndTimeLocal;
$FromDateLocal = $params->EndTimeLocal;

$MaxRows = $params->MaxRows;

/* Código PHP que define reglas para validar el estado de un producto en transacción. */
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

$rules = [];

array_push($rules, array("field" => "transaccion_producto.estado", "data" => "I", "op" => "eq"));

/* Agrega reglas de filtro a un array según condiciones específicas en PHP. */
array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "transaccion_producto.fecha_modif", "data" => "$FromDateLocal ", "op" => "ge"));
array_push($rules, array("field" => "transaccion_producto.fecha_modif", "data" => "$ToDateLocal", "op" => "le"));

if ($TypeId != "") {
    array_push($rules, array("field" => "producto.producto_id", "data" => "$TypeId", "op" => "eq"));
}


/* Se define un filtro y se inicializan variables si están vacías. */
$filtro = array("rules" => $rules, "groupOp" => "AND");

if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* Configura un máximo de filas y obtiene transacciones personalizadas en formato JSON. */
if ($MaxRows == "") {
    $MaxRows = 1000000000;
}

$json = json_encode($filtro);

$transacciones = $TransaccionProducto->getTransaccionesCustom(" transaccion_producto.*,producto.*,usuario.moneda ", "transaccion_producto.transproducto_id", "asc", $SkeepRows, $MaxRows, $json, true);


/* procesa transacciones, organizando datos en un nuevo arreglo para análisis. */
$transacciones = json_decode($transacciones);

$final = [];

foreach ($transacciones->data as $key => $value) {

    $array = [];

    $array["PaymentTypeName"] = $value->{"producto.descripcion"};
    $array["AccountId"] = $value->{"transaccion_producto.usuario_id"};
    $array["Debit"] = $value->{"transaccion_producto.valor"};
    $array["Credit"] = 0;
    $array["CurrencyId"] = $value->{"usuario.moneda"};
    $array["ReciboId"] = $value->{"transaccion_producto.final_id"};

    array_push($final, $array);
}


/* establece una respuesta JSON sin errores, con mensaje y datos finales. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;