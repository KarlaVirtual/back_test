<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\Mandante;

/**
 * GetBetShopById
 * 
 * Obtiene la información detallada de un punto de venta por su ID
 *
 * @param int $id             // ID del punto de venta a consultar
 *
 * @return array {
 *   "HasError": boolean,     // Indica si hubo error
 *   "AlertType": string,     // Tipo de alerta (success/danger)
 *   "AlertMessage": string,  // Mensaje descriptivo
 *   "ModelErrors": array,    // Errores del modelo
 *   "Data": {               // Datos del punto de venta
 *     "Id": int,            // ID del punto de venta
 *     "Name": string,       // Nombre/descripción del punto de venta
 *     "CurrencyId": string, // ID de la moneda
 *     "Type": string,       // Tipo de punto de venta
 *     "MinBet": float,      // Apuesta mínima permitida
 *     "PreMatchPercentage": float,  // Porcentaje de comisión pre-partido
 *     "LivePercentage": float,      // Porcentaje de comisión en vivo
 *     "RecargasPercentage": float   // Porcentaje de comisión en recargas
 *   }
 * }
 *
 * @throws Exception        // Errores de procesamiento
 */

// Obtiene y decodifica los parámetros de entrada
$params = file_get_contents('php://input');
$params = json_decode($params);

// Obtiene el ID del punto de venta desde la URL
$id = $_REQUEST["id"];

// Inicializa el objeto Mandante para acceder a los datos
$Mandante = new Mandante();

// Establece valores por defecto para la paginación
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 100000;
}

// Construye el JSON con los filtros para la consulta
$json = '{"rules" : [{"field" : "punto_venta.puntoventa_id", "data": ' . $id . ',"op":"eq"},{"field" : "usuario_perfil.perfil_id", "data": "PUNTOVENTA","op":"eq"}] ,"groupOp" : "AND"}';

// Obtiene los datos del punto de venta y los decodifica
$mandantes = $Mandante->getPuntosVentaTree("mandante.mandante", "asc", $SkeepRows, $MaxRows, $json, true);
$mandantes = json_decode($mandantes);

$final = [];

// Procesa los datos obtenidos y los formatea según la estructura requerida
foreach ($mandantes->data as $key => $value) {
    $array = [];

    $array["Id"] = $value->{"punto_venta.puntoventa_id"};
    $array["Name"] = $value->{"punto_venta.descripcion"};
    $array["CurrencyId"] = $value->{"usuario.moneda"};
    $array["Type"] = $value->{"tipo_punto.descripcion"};
    $array["MinBet"] = $value->{"usuario_premiomax.apuesta_min"};
    $array["PreMatchPercentage"] = $value->{"punto_venta.porcen_comision"};
    $array["LivePercentage"] = $value->{"punto_venta.porcen_comision"};
    $array["RecargasPercentage"] = $value->{"punto_venta.porcen_comision2"};
    $final = $array;
}

// Configura la respuesta exitosa
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

// Asigna los datos formateados a la respuesta
$response["Data"] = $final;