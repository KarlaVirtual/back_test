<?php
/**
 * Este script procesa un resumen de depósitos de clientes recién registrados.
 * 
 * @param object $params Contiene los siguientes campos:
 * @param string $params->ToDateLocal Fecha máxima de creación en formato local.
 * @param string $params->FromDateLocal Fecha mínima de creación en formato local.
 * @param string $params->Region Región del cliente.
 * @param string $params->Currency Moneda utilizada.
 * @param int $params->MaxRows Número máximo de filas a devolver.
 * @param int $params->OrderedItem Elemento por el cual ordenar.
 * @param int $params->SkeepRows Número de filas a omitir.
 * 
 * 
 * @return array $response Contiene los siguientes campos:
 * - HasError (bool): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (por ejemplo, "success").
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo.
 * - Data (array): Información del resumen de depósitos, incluyendo:
 *   - DepositTotalCount (int): Total de depósitos realizados.
 *   - DepositTotalAmount (float): Monto total de los depósitos.
 * 
 * @throws Exception Si ocurre un error al procesar los datos.
 */

use Backend\dto\UsuarioRecarga;


/* crea un objeto y procesa una fecha a partir de entrada JSON. */
$UsuarioRecarga = new UsuarioRecarga();

$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->ToDateLocal) . ' +1 day'));

/* procesa fechas y parámetros relacionados con región, moneda y filas máximas. */
$FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->FromDateLocal)));
$Region = $params->Region;
$Currency = $params->Currency;

$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;

/* crea reglas para filtrar fechas en registros de recargas de usuario. */
$SkeepRows = $params->SkeepRows;

$rules = [];

array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));

/* Se agregan reglas de filtrado para fechas y región en un array. */
array_push($rules, array("field" => "usuario.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
array_push($rules, array("field" => "usuario.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));

if ($Region != "") {
    array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
}


/* Añade una regla de moneda a un filtro si no está vacío. */
if ($Currency != "") {
    array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
}

$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);


/* asigna valores predeterminados si las variables están vacías. */
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* establece un límite de filas y procesa usuarios en una región específica. */
if ($MaxRows == "") {
    $MaxRows = 10000000;
}

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


    /* obtiene y procesa datos de recargas de usuarios, convirtiendo valores moneda. */
    $usuarios = $UsuarioRecarga->getUsuarioRecargasCustom("COUNT(*) count,SUM(usuario_recarga.valor) valor,usuario.moneda", "usuario_recarga.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true, "usuario.moneda");

    $usuarios = json_decode($usuarios);
    setlocale(LC_ALL, 'czech');

    $valor_convertido = 0;

    /* convierte valores en moneda a euros y calcula un total. */
    $total = 0;
    foreach ($usuarios->data as $key => $value) {

        $converted_currency = currencyConverter($value->{"usuario.moneda"}, 'EUR', round($value->{".valor"}, 0));
        $valor_convertido = $valor_convertido + $converted_currency;
        $total = $total + $value->{".count"};

    }


    /* define una respuesta sin errores, incluyendo información sobre depósitos. */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    $response["Data"] = array(
        "DepositTotalCount" => $total,
        "DepositTotalAmount" => $valor_convertido,

    );

}
