<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\Mandante;
use Backend\dto\Usuario;

/**
 * Obtiene la lista de cajas registradoras (puntos de venta) asociadas a un BetShop específico
 * 
 * @package Backend\BonusApi\Cases
 * 
 * @param int $BetShopId ID del BetShop para filtrar los puntos de venta
 * @param int $SkeepRows Número de filas a saltar (paginación)
 * @param int $OrderedItem Columna por la cual ordenar los resultados
 * @param int $MaxRows Número máximo de filas a retornar
 * 
 * @return array{
 *     HasError: bool,
 *     AlertType: string,
 *     AlertMessage: string,
 *     ModelErrors: array,
 *     Data: array<array{
 *         Id: string,
 *         Name: string,
 *         CurrencyId: string,
 *         Type: string,
 *         MinBet: float,
 *         PreMatchPercentage: float,
 *         LivePercentage: float,
 *         RecargasPercentage: float
 *     }>
 * }
 */
// Obtiene y decodifica los parámetros de entrada desde la solicitud
$params = file_get_contents('php://input');
$params = json_decode($params);

// Extrae el ID del BetShop de los parámetros
$id = $params->BetShopId;

// Inicializa el objeto Mandante para operaciones relacionadas
$Mandante = new Mandante();

// Establece valores predeterminados para los parámetros de paginación
// si no se proporcionan en la solicitud
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 100000;
}

// Define un filtro JSON inicial (no utilizado posteriormente)
$json = '{"rules" : [{"field" : "punto_venta.puntoventa_id", "data": ' . $id . ',"op":"eq"},{"field" : "usuario_perfil.perfil_id", "data": "PUNTOVENTA","op":"eq"}] ,"groupOp" : "AND"}';

// Inicializa un array para las reglas de filtrado
$rules = [];
// Agrega una regla para filtrar por perfil de punto de venta
array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));

// Aplica filtros adicionales según los permisos del usuario actual
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
}
if ($_SESSION['PaisCond'] == "S") {
    array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
}

// Construye el objeto de filtro completo y lo convierte a JSON
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

// Inicializa el objeto Usuario y obtiene los datos filtrados
$Usuario = new Usuario();
$mandantes = $Usuario->getUsuariosCustom("  punto_venta.*,usuario.puntoventa_id,usuario.nombre,usuario.usuario_id,usuario.fecha_ult,usuario.moneda,usuario.idioma,usuario.dir_ip,usuario.login,usuario.estado,usuario.estado_esp,usuario.pais_id,usuario.fecha_crea ", "usuario.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true,'usuario.usuario_id');

// Decodifica los resultados obtenidos
$mandantes = json_decode($mandantes);

// Prepara el array para almacenar los resultados formateados
$final = [];

// Procesa cada registro obtenido y formatea los datos para la respuesta
foreach ($mandantes->data as $key => $value) {
    $array = [];
    $array["Id"] = $value->{"usuario.puntoventa_id"};
    $array["Name"] = $array["Id"].' - '.$value->{"punto_venta.descripcion"};
    $array["CurrencyId"] = $value->{"usuario.moneda"};
    $array["Type"] = $value->{"tipo_punto.descripcion"};
    $array["MinBet"] = $value->{"usuario_premiomax.apuesta_min"};
    $array["PreMatchPercentage"] = $value->{"punto_venta.porcen_comision"};
    $array["LivePercentage"] = $value->{"punto_venta.porcen_comision"};
    $array["RecargasPercentage"] = $value->{"punto_venta.porcen_comision2"};
    array_push($final, $array);
}

// Configura la respuesta con los datos procesados
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response["Data"] = $final;
