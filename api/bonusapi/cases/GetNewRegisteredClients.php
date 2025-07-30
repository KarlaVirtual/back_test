<?php
/**
 * Este script obtiene una lista de clientes recién registrados.
 * 
 * @param object $params Contiene los siguientes campos:
 * @param string $params->MaxCreatedLocal Fecha máxima de creación en formato local.
 * @param string $params->MinCreatedLocal Fecha mínima de creación en formato local.
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
 * - Data (int): Conteo total de clientes registrados.
 * 
 * @throws Exception Si ocurre un error al procesar los datos.
 */

use Backend\dto\Usuario;


/* crea un objeto Usuario y procesa datos JSON de la entrada. */
$Usuario = new Usuario();

$params = file_get_contents('php://input');
$params = json_decode($params);

$MaxCreatedLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->MaxCreatedLocal) . ' +1 day'));

/* Código que asigna parámetros a variables para su posterior uso. */
$MinCreatedLocal = $params->MinCreatedLocal;
$Region = $params->Region;
$Currency = $params->Currency;

$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;

/* asigna valores predeterminados a variables si están vacías. */
$SkeepRows = $params->SkeepRows;

if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* Establece un valor predeterminado y agrega una regla de filtrado de fecha. */
if ($MaxRows == "") {
    $MaxRows = 10;
}

$rules = [];
array_push($rules, array("field" => "usuario.fecha_crea", "data" => "$MinCreatedLocal ", "op" => "ge"));

/* agrega reglas de filtrado basadas en condiciones específicas de usuario. */
array_push($rules, array("field" => "usuario.fecha_crea", "data" => "$MaxCreatedLocal", "op" => "le"));

if ($Region != "") {
    array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
}

if ($Currency != "") {
    array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
}


/* Se crea un filtro JSON para obtener usuarios desde una base de datos. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);


$usuarios = $Usuario->getUsuariosCustom(" COUNT(*) count ", "usuario.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true);

$usuarios = json_decode($usuarios);


/* Inicializa un array de usuarios y configura respuesta sin errores y éxito. */
$usuariosFinal = [];

$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];


/* Asigna el conteo de usuarios al campo "Data" de la respuesta. */
$response["Data"] = $usuarios->data[0]->{".count"};