<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/


use Backend\dto\TransaccionJuego;
use Backend\dto\Usuario;

/**
 * Obtiene un reporte de juegos de casino con información detallada de transacciones
 * 
 * @package Backend\api\bonusapi\cases
 * 
 * @param object $params Parámetros de la petición
 * @param int $params->MaxRows Número máximo de filas a retornar (default: 10000)
 * @param int $params->OrderedItem Orden de los items (default: 1)
 * @param int $params->SkeepRows Número de filas a saltar (default: 0)
 * 
 * @return array {
 *   HasError: bool,
 *   AlertType: string,
 *   AlertMessage: string,
 *   ModelErrors: array,
 *   Data: array[] {
 *     Game: string,
 *     ProviderName: string,
 *     Bets: string,
 *     Stakes: float,
 *     Winnings: float,
 *     Profit: float,
 *     BonusCashBack: float
 *   }
 * }
 */

// Inicializa el objeto Usuario para posibles operaciones con usuarios
$Usuario = new Usuario();

// Obtiene y decodifica los parámetros de entrada desde la solicitud
$params = file_get_contents('php://input');
$params = json_decode($params);

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
    $MaxRows = 10000;
}

// Inicializa el objeto TransaccionJuego y obtiene los datos de transacciones
// ordenados por ID de transacción en orden ascendente
$TransaccionJuego = new TransaccionJuego();
$data = $TransaccionJuego->getTransacciones("transaccion_juego.transjuego_id", "asc", $SkeepRows, $MaxRows, "", false);

// Decodifica los resultados obtenidos para su procesamiento
$data = json_decode($data);

// Inicializa un array para almacenar los datos procesados
$final = [];

// Recorre cada transacción y extrae la información relevante
// para el reporte de juegos de casino
foreach ($data->data as $key => $value) {

    $array = [];

    // Asigna los valores de cada campo del reporte
    $array["Game"] = $value->{"producto.descripcion"};
    $array["ProviderName"] = $value->{"proveedor.descripcion"};
    $array["Bets"] = $value->{"producto.descripcion"};
    $array["Stakes"] = $value->{"transaccion_juego.valor_ticket"};
    $array["Winnings"] = $value->{"transaccion_juego.valor_premio"};
    $array["Profit"] = 0;
    $array["BonusCashBack"] = 0;

    // Agrega el registro procesado al array final
    array_push($final, $array);
}

// Prepara la respuesta con indicadores de éxito y los datos procesados
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

// Asigna los datos procesados a la respuesta
$response["Data"] = $final;