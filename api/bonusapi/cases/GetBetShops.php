<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\Mandante;

/**
 * GetBetShops
 * 
 * Obtiene la lista de puntos de venta registrados en el sistema
 *
 * @return array {
 *   "HasError": boolean,      // Indica si hubo error
 *   "AlertType": string,      // Tipo de alerta (success/danger)
 *   "AlertMessage": string,   // Mensaje descriptivo
 *   "ModelErrors": array,     // Errores del modelo
 *   "Data": array[{          // Lista de puntos de venta
 *     "Id": int,             // ID del punto de venta
 *     "Name": string,        // Nombre/descripción del punto
 *     "Phone": string,       // Teléfono de contacto
 *     "Email": string,       // Correo electrónico
 *     "CityName": string,    // Nombre de la ciudad
 *     "DepartmentName": string, // Nombre del departamento
 *     "RegionName": string,  // Nombre de la región/país
 *     "CurrencyId": string,  // ID de la moneda
 *     "Address": string,     // Dirección física
 *     "CreatedDate": string, // Fecha de creación
 *     "LastLoginDateLabel": string, // Fecha del último acceso
 *     "Type": string,        // Tipo de punto de venta
 *     "MinBet": float,       // Apuesta mínima permitida
 *     "PreMatchPercentage": float,  // Porcentaje comisión pre-partido
 *     "LivePercentage": float,      // Porcentaje comisión en vivo
 *     "RecargasPercentage": float   // Porcentaje comisión recargas
 *   }]
 * }
 *
 * @throws Exception         // Errores de procesamiento
 */

// Obtiene y decodifica los parámetros de entrada desde el request
$params = file_get_contents('php://input');
$params = json_decode($params);

// Inicializa el objeto Mandante para acceder a los datos de puntos de venta
$Mandante = new Mandante();

// Establece valores por defecto para la paginación y ordenamiento
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 100000;
}

// Construye el JSON con los filtros para obtener solo puntos de venta
$json = '{"rules" : [{"field" : "usuario_perfil.perfil_id", "data": "PUNTOVENTA","op":"eq"}] ,"groupOp" : "AND"}';

// Obtiene los datos de puntos de venta y los decodifica
$mandantes = $Mandante->getPuntosVentaTree("mandante.mandante", "asc", $SkeepRows, $MaxRows, $json, true);
$mandantes = json_decode($mandantes);

// Inicializa el array que contendrá la lista final de puntos de venta
$final = [];

// Procesa cada punto de venta obtenido y extrae sus datos
foreach ($mandantes->data as $key => $value) {

    $array = [];

    // Mapea los campos de la base de datos al formato de respuesta requerido
    $array["Id"] = $value->{"punto_venta.puntoventa_id"};
    $array["Name"] = $value->{"punto_venta.descripcion"};
    $array["Phone"] = $value->{"punto_venta.telefono"};
    $array["Email"] = $value->{"usuario.email"};
    $array["CityName"] = $value->{"ciudad.ciudad_nom"};
    $array["DepartmentName"] = $value->{"departamento.depto_nom"};
    $array["RegionName"] = $value->{"pais.pais_nom"};
    $array["CurrencyId"] = $value->{"usuario.moneda"};
    $array["Address"] = $value->{"punto_venta.direccion"};

    // Establece valores vacíos para fechas que no están disponibles
    $array["CreatedDate"]= "";
    $array["LastLoginDateLabel"]="";

    // Mapea los campos relacionados con configuración y comisiones
    $array["Type"] = $value->{"tipo_punto.descripcion"};
    $array["MinBet"] = $value->{"usuario_premiomax.apuesta_min"};
    $array["PreMatchPercentage"] = $value->{"punto_venta.porcen_comision"};
    $array["LivePercentage"] = $value->{"punto_venta.porcen_comision"};
    $array["RecargasPercentage"] = $value->{"punto_venta.porcen_comision2"};

    array_push($final, $array);
}

// Configura la respuesta exitosa con los datos procesados
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;