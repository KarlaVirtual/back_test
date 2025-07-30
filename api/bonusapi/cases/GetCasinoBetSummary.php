<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\TransaccionJuego;

/**
 * Obtiene un resumen de apuestas y premios para un período específico en el casino
 * 
 * @package Backend\BonusApi\Cases
 * @method POST
 * @param object $params Objeto con los parámetros de la petición
 * @param string $params->FromDateLocal Fecha inicial en formato "YYYY-MM-DD HH:mm:ss"
 * @param string $params->ToDateLocal Fecha final en formato "YYYY-MM-DD HH:mm:ss"
 * @param string $params->Region Región del casino
 * @param string $params->Currency Moneda de las transacciones
 * @param int $params->MaxRows Número máximo de filas a retornar (default: 10)
 * @param int $params->OrderedItem Orden de los items (default: 1)
 * @param int $params->SkeepRows Número de filas a saltar (default: 0)
 * 
 * @return array{
 *   HasError: bool,
 *   AlertType: string,
 *   AlertMessage: string,
 *   ModelErrors: array,
 *   Data: array{
 *     BetAmount: float,
 *     WinningAmount: float
 *   }
 * }
 */

// Inicializa el objeto TransaccionJuego para acceder a los datos de transacciones
$TransaccionJuego = new TransaccionJuego();

// Obtiene y decodifica los parámetros de entrada desde la solicitud
$params = file_get_contents('php://input');
$params = json_decode($params);

// Procesa las fechas de inicio y fin, formateándolas correctamente para la consulta
// Ajusta la fecha final para incluir todo el día especificado
$ToDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->ToDateLocal) . ' +1 day'));
$FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->FromDateLocal)));
$Region = $params->Region;
$Currency = $params->Currency;

// Extrae los parámetros de paginación y ordenamiento
$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

// Establece valores predeterminados para los parámetros de paginación
// si no se proporcionan en la solicitud
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 10;
}

// Construye el filtro JSON para la consulta de transacciones
// Filtra por estado 'I' y el rango de fechas especificado
$json = '{"rules" : [{"field" : "transaccion_juego.estado", "data" : "I","op":"eq"},{"field" : "transaccion_juego.fecha_modif", "data": "' . $FromDateLocal . '","op":"ge"},{"field" : "transaccion_juego.fecha_modif", "data": "' . $ToDateLocal . '","op":"le"}] ,"groupOp" : "AND"}';

// Ejecuta la consulta para obtener la suma de apuestas y premios
// Calcula el total apostado y el total de premios ganados
$transacciones = $TransaccionJuego->getTransaccionesCustom(" SUM(transaccion_juego.valor_ticket) apuestas, SUM(CASE WHEN transaccion_juego.premiado = 'S' THEN transaccion_juego.valor_premio ELSE 0 END) premios  ", "transaccion_juego.transjuego_id", "asc", $SkeepRows, $MaxRows, $json, true);

// Decodifica los resultados obtenidos
$transacciones = json_decode($transacciones);

// Prepara la respuesta con indicadores de éxito
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

// Estructura los datos de respuesta con los montos de apuestas y ganancias
$response["Data"] = array(
    "BetAmount" => $transacciones->data[0]->{".apuestas"},
    "WinningAmount" => $transacciones->data[0]->{".premios"},

);
