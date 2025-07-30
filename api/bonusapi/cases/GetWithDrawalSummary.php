<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\CuentaCobro;

/**
 * Este script genera un resumen de retiros basado en filtros específicos.
 *
 * @param object $params Objeto JSON con los siguientes valores:
 * - @param string $ToDateLocal Fecha final del rango en formato "Y-m-d H:i:s".
 * - @param string $FromDateLocal Fecha inicial del rango en formato "Y-m-d H:i:s".
 * - @param string $Region Región del usuario.
 * - @param string $Currency Moneda utilizada.
 * - @param bool $IsNewRegistered Indica si se filtran usuarios recién registrados.
 * - @param int $MaxRows Número máximo de filas a devolver.
 * - @param int $OrderedItem Orden de los resultados.
 * - @param int $SkeepRows Número de filas a omitir.
 * 
 * 
 * @return array $response Respuesta en formato JSON que incluye:
 * - @property bool $HasError Indica si hubo un error.
 * - @property string $AlertType Tipo de alerta (success o danger).
 * - @property string $AlertMessage Mensaje de alerta.
 * - @property array $ModelErrors Lista de errores del modelo.
 * - @property array $Data Resumen de retiros, incluyendo:
 *   - @property int $WithDrawalTotalCount Total de retiros.
 *   - @property float $WithDrawalTotalAmount Monto total de retiros.
 */

/* crea un objeto y procesa fecha desde entrada JSON. */
$CuentaCobro = new CuentaCobro();

$params = file_get_contents('php://input');
$params = json_decode($params);

/* formatea una fecha y extrae parámetros como región y moneda. */
$ToDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->ToDateLocal) . ' +1 day'));
$FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->FromDateLocal)));
$Region = $params->Region;
$Currency = $params->Currency;
$IsNewRegistered = $params->IsNewRegistered;

$MaxRows = $params->MaxRows;

/* Asigna $OrderedItem y $SkeepRows, inicializando este último en 0 si está vacío. */
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* Asigna valores por defecto a variables si están vacías. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 10;
}


/* Código que define reglas de filtrado para cuentas de cobro en un arreglo. */
$rules = [];
array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "I", "op" => "eq"));
array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => "$FromDateLocal ", "op" => "ge"));
array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => "$ToDateLocal", "op" => "le"));

if ($Region != "") {
    array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
}


/* Agrega reglas basadas en moneda y fecha si se cumplen ciertas condiciones. */
if ($Currency != "") {
    array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
}

if ($IsNewRegistered) {
    array_push($rules, array("field" => "usuario.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
    array_push($rules, array("field" => "usuario.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));

}


/* Genera un JSON con datos de cuentas si se especifica una región. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

if ($Region != "") {

    $cuentas = $CuentaCobro->getCuentasCobroCustom("SUM(cuenta_cobro.valor) valor", "cuenta_cobro.cuenta_id", "asc", $SkeepRows, $MaxRows, $json, true);

    $cuentas = json_decode($cuentas);

    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    $response["Data"] = array(
        "WithDrawalTotalCount" => $cuentas->count[0]->{".count"},
        "WithDrawalTotalAmount" => $cuentas->data[0]->{".valor"},

    );
} else {

    /* obtiene y decodifica cuentas de cobro, inicializando variables para cálculos. */
    $cuentas = $CuentaCobro->getCuentasCobroCustom("COUNT(*) count,SUM(cuenta_cobro.valor) valor,usuario.moneda", "cuenta_cobro.cuenta_id", "asc", $SkeepRows, $MaxRows, $json, true, "usuario.moneda");

    $cuentas = json_decode($cuentas);

    $valor_convertido = 0;
    $total = 0;

    /* Convierte monedas a euros y suma totales de valores y cuentas. */
    foreach ($cuentas->data as $key => $value) {

        $converted_currency = currencyConverter($value->{"usuario.moneda"}, 'EUR', round($value->{".valor"}, 0));
        $valor_convertido = $valor_convertido + $converted_currency;
        $total = $total + $value->{".count"};

    }


    /* Código que estructura una respuesta sin errores, incluyendo datos de transacciones. */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    $response["Data"] = array(
        "WithDrawalTotalCount" => $total,
        "WithDrawalTotalAmount" => $valor_convertido,

    );
}