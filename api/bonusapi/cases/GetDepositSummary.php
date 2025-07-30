<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\UsuarioRecarga;

/**
 * Este script calcula un resumen de depósitos por región y moneda.
 *
 * @param object $params Objeto JSON decodificado que contiene los siguientes parámetros:
 * @param string $params->ToDateLocal Fecha de finalización en formato local.
 * @param string $params->FromDateLocal Fecha de inicio en formato local.
 * @param string $params->Region Región del usuario.
 * @param string $params->Currency Moneda utilizada.
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
 *    - DepositTotalCount (int): Número total de depósitos.
 *    - DepositTotalAmount (float): Monto total de depósitos.
 */

/* crea un objeto y formatea una fecha a partir de parámetros JSON. */
$UsuarioRecarga = new UsuarioRecarga();

$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->ToDateLocal) . ' +1 day'));

/* establece fechas y obtiene parámetros de región y moneda. */
$FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->FromDateLocal)));

$Region = $params->Region;
$Currency = $params->Currency;

$MaxRows = $params->MaxRows;

/* Se define una regla para filtrar por fecha de creación en la recarga de usuario. */
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

$rules = [];

array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));

/* Agrega reglas de filtro basadas en fecha, región y moneda a un arreglo. */
array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));

if ($Region != "") {
    array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
}

if ($Currency != "") {
    array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
}


/* Crea un filtro JSON con reglas y gestiona la variable SkeepRows. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* asigna valores predeterminados si $OrderedItem o $MaxRows están vacíos. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 10000000;
}


/* verifica una región y obtiene datos de recargas de usuarios. */
if ($Region != "") {
    $usuarios = $UsuarioRecarga->getUsuarioRecargasCustom("SUM(usuario_recarga.valor) valor", "usuario_recarga.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true);

    $usuarios = json_decode($usuarios);

    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    $response["Data"] = array(
        "DepositTotalCount" => $usuarios->count[0]->{".count"},
        "DepositTotalAmount" => $usuarios->data[0]->{".valor"},

    );

} else {


    /* consulta recargas de usuarios y las convierte a formato JSON. */
    $usuarios = $UsuarioRecarga->getUsuarioRecargasCustom("COUNT(*) count,SUM(usuario_recarga.valor) valor,usuario.moneda", "usuario_recarga.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true, "usuario.moneda");

    $usuarios = json_decode($usuarios);
    setlocale(LC_ALL, 'czech');

    $valor_convertido = 0;

    /* Suma valores convertidos de usuarios en moneda local a euros y cuenta total de usuarios. */
    $total = 0;
    foreach ($usuarios->data as $key => $value) {

        $converted_currency = currencyConverter($value->{"usuario.moneda"}, 'EUR', round($value->{".valor"}, 0));
        $valor_convertido = $valor_convertido + $converted_currency;
        $total = $total + $value->{".count"};

    }


    /* Código genera una respuesta estructurada para un sistema, indicando éxito y datos de depósito. */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    $response["Data"] = array(
        "DepositTotalCount" => $total,
        "DepositTotalAmount" => $valor_convertido,

    );

}