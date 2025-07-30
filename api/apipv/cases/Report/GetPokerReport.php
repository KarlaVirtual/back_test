<?php
use Backend\dto\BonoInterno;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\ProductoMySqlDAO;
use Backend\dto\Producto;


/**
 * Report/GetPokerReport
 *
 * Obtiene el reporte de transacciones de poker según los filtros especificados
 *
 * @param array $params {
 *   "dateFrom": string,        // Fecha inicial en formato Y-m-d
 *   "dateTo": string,          // Fecha final en formato Y-m-d
 *   "UserId": int,             // ID del usuario a filtrar
 *   "CommissionType": string,  // Tipo de comisión
 *   "GameType": string,        // Tipo de juego
 *   "Supplier": string,        // Proveedor
 *   "Subsupplier": string,     // Subproveedor
 *   "Country": string,         // País
 *   "count": int,              // Cantidad de registros por página
 *   "start": int,              // Índice inicial para paginación
 *   "Type": int                // Tipo de reporte
 * }
 *
 * @return array {
 *   "HasError": boolean,       // Indica si hubo error
 *   "AlertType": string,       // Tipo de alerta (success, error)
 *   "AlertMessage": string,    // Mensaje de alerta
 *   "ModelErrors": array,      // Errores del modelo
 *   "Data": array {
 *     "Proveedor": string,     // Nombre del proveedor
 *     "Subproveedor": string,  // Nombre del subproveedor
 *     "ID": int,               // ID de la transacción
 *     "identificador": string, // Identificador único
 *     "Juego": string,         // Nombre del juego
 *     "tipo": string,          // Tipo de transacción
 *     "Fecha": string,         // Fecha de la transacción
 *     "Valor_Apuesta": float,  // Monto apostado
 *     "Premio": float,         // Premio ganado
 *     "Rake": float,           // Comisión del juego
 *     "Torneo": int           // ID del torneo
 *   }[],
 *   "total_count": int        // Total de registros
 * }
 */

// Obtiene los parámetros de la solicitud HTTP
$dateFrom = $_REQUEST['dateFrom'];
$dateTo = $_REQUEST['dateTo'];
$UserId = $_REQUEST['UserId'];
$CommissionType = $_REQUEST['CommissionType'];
$GameType = $_REQUEST['GameType'];
$Supplier = $_REQUEST['Supplier'];
$Subsupplier = $_REQUEST['Subsupplier'];
$Country = $_REQUEST['Country'];
$MaxRows = ($_REQUEST["count"] != "") ? $_REQUEST["count"] : 1000;
$SkeepRows = ($_REQUEST["start"] != "") ? $_REQUEST["start"] : 0;
$mandante = $_SESSION['mandante'];
$Type = $_REQUEST["Type"];


/*Sanitización de parámetros (No aplica para reporte tipo 1 al ser antiguo y manejar otra lógica) */
$Country = $Country ?? ($_SESSION['PaisCondS'] ?? $_SESSION['pais_id']);

$Country = is_numeric($Country) ? $Country : null;

$mandante = is_numeric($mandante) ? $mandante : null;

$dateFrom = date("Y-m-d 00:00:00", strtotime($dateFrom));

$dateTo = date("Y-m-d 23:59:59", strtotime($dateTo));

$UserId = is_numeric($UserId) ? $UserId : null;

$GameType = match ((int)$GameType) {
    1 => 'CASHOUT',
    2 => 'TOURNAMENT',
    default => null
};

$Supplier = is_numeric($Supplier) ? $Supplier : null;

$Subsupplier = is_numeric($Subsupplier) ? $Subsupplier : null;

$SkeepRows = is_numeric($SkeepRows) ? $SkeepRows : 0;

$MaxRows = is_numeric($MaxRows) ? $MaxRows : 100;


/*Reporte detallado por sesión individual del jugador*/
if ($Type == 0){
    /*Construcción objeto de filtros aplicables a la consulta*/
    $filters = [
        "Country" => $Country,
        "Partner" => $mandante,
        "dateFrom" => $dateFrom,
        "dateTo" => $dateTo,
        "UserId" => $UserId,
        "GameType" => $GameType,
        "SupplierId" => $Supplier,
        "SubsupplierId" => $Subsupplier,
        "Start" => $SkeepRows,
        "Count" => $MaxRows
    ];

    try {
        /*Ejecución de la consulta correspondiente a reporte*/
        $totalCount = 0;
        $Producto = new Producto();
        $datos = $Producto->getPokerReportBySessions($totalCount, $filters);

        /*Definición respuesta estándar*/
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];
        $response['total_count'] = $totalCount;
        $response['pos'] = $SkeepRows;
        $response['data'] = $datos;
    } catch (Exception $e) {
        /*Definición respuesta de error*/
        $response["HasError"] = true;
        $response["AlertType"] = "Error";
        $response["AlertMessage"] = "Fallo en la consulta";
        $response["ModelErrors"] = [];
        $response['total_count'] = 0;
        $response['pos'] = 0;
        $response['data'] = [];

        /*Verificación disponibilidad opción de debug*/
        if ($_ENV['debug']) Throw $e;
    }

    /*Finalización proceso de consulta en el recurso*/
    return;
}


/*Reporte agrupado por proveedor y tipo de juego*/
if ($Type == 1){
    /*Construcción objeto de filtros aplicables a la consulta*/
    $filters = [
        "Country" => $Country,
        "Partner" => $mandante,
        "dateFrom" => $dateFrom,
        "dateTo" => $dateTo,
        "SupplierId" => $Supplier,
        "Start" => $SkeepRows,
        "Count" => 1000
    ];

    try {
        $totalCount = 0;
        $Producto = new Producto();
        $datos = $Producto->getPokerReportBySupplierByUserByGameType($totalCount, $filters);

        /*Definición respuesta estándar*/
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];
        $response['total_count'] = $totalCount;
        $response['pos'] = $SkeepRows;
        $response['data'] = $datos;
    } catch (Exception $e) {
        /*Definición respuesta de error*/
        $response["HasError"] = true;
        $response["AlertType"] = "Error";
        $response["AlertMessage"] = "Fallo en la consulta";
        $response["ModelErrors"] = [];
        $response['total_count'] = 0;
        $response['pos'] = 0;
        $response['data'] = [];

        /*Verificación disponibilidad opción de debug*/
        if ($_ENV['debug']) Throw $e;
    }

    /*Finalización proceso de consulta en el recurso*/
    return;
}


/*Reporte agrupado por proveedor y usuario*/
if ($Type == 2){
    $filters = [
        "Country" => $Country,
        "Partner" => $mandante,
        "dateFrom" => $dateFrom,
        "dateTo" => $dateTo,
        "UserId" => $UserId,
        "SupplierId" => $Supplier,
        "Start" => $SkeepRows,
        "Count" => $MaxRows
    ];

    try {
        $totalCount = 0;
        $Producto = new Producto();
        $datos = $Producto->getPokerReportBySupplierByUser($totalCount, $filters);

        /*Definición respuesta estándar*/
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];
        $response['total_count'] = $totalCount;
        $response['pos'] = $SkeepRows;
        $response['data'] = $datos;
    } catch (Exception $e) {
        /*Definición respuesta de error*/
        $response["HasError"] = true;
        $response["AlertType"] = "Error";
        $response["AlertMessage"] = "Fallo en la consulta";
        $response["ModelErrors"] = [];
        $response['total_count'] = 0;
        $response['pos'] = 0;
        $response['data'] = [];

        /*Verificación disponibilidad opción de debug*/
        if ($_ENV['debug']) Throw $e;
    }

    /*Finalización proceso de consulta en el recurso*/
    return;
}




// Prepara la respuesta final con la paginación y los datos
$response["pos"] = $SkeepRows;
$response["total_count"] = $count[0]->{".COUNT(*)"};
$response["data"] = $dataFinal;
