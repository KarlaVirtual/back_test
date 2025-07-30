<?php


use Backend\dto\Clasificador;
use Backend\mysql\ClasificadorMySqlDAO;

/**
 * Report/getTypesCampaign
 * 
 * Obtiene los tipos de campañas y sus descripciones
 *
 * @return array {
 *   "HasError": boolean,           // Indica si hubo error
 *   "AlertType": string,           // Tipo de alerta (success, error)
 *   "AlertMessage": string,        // Mensaje descriptivo
 *   "ModelErrors": array,          // Errores del modelo
 *   "Data": array {
 *     "Objects": array[{           // Lista de tipos de campaña
 *       "Id": int,                 // ID del tipo de campaña
 *       "Description": string      // Descripción del tipo de campaña
 *     }],
 *     "Count": int                 // Total de registros
 *   }
 * }
 */


// Configura los parámetros de paginación y ordenamiento
$MaxRows = 10;
$SkeepRows = 0;
$OrderItem = "Desc";

// Construye el filtro para obtener clasificadores de tipo "TBC" (Tipos de Campaña)
$rules = [];
array_push($rules, array("field" => "clasificador.tipo", "data" => "TBC", "op" => "eq"));

$filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

$Clasificador = new Clasificador();

// Obtiene los clasificadores de tipo TBC con sus datos completos
$datos = $Clasificador->getClasificadoresCustom("clasificador.*", "clasificador.clasificador_id", $OrderItem, $SkeepRows, $MaxRows, $filter, true);

$datos = json_decode($datos);

// Inicializa arreglos para almacenar los resultados
$final3 = [];
$final = [];

// Procesa los clasificadores TBC y construye el arreglo de categorías
foreach ($datos->data as $key => $value) {
    $array = [];
    $array["Id"] = $value->{"clasificador.clasificador_id"};
    $array["Description"] = $value->{"clasificador.descripcion"};

    array_push($final, $array);
}

// Construye un nuevo filtro para obtener clasificadores de tipo "TBD" (Detalles de Campaña)
$rules = [];
array_push($rules, array("field" => "clasificador.tipo", "data" => "TBD", "op" => "eq"));

$filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

// Obtiene los clasificadores de tipo TBD con campos específicos
$Clasificador = new Clasificador();
$datos = $Clasificador->getClasificadoresCustom("clasificador.descripcion,clasificador.clasificador_id", "clasificador.clasificador_id", $OrderItem, $SkeepRows, $MaxRows, $filter, true);

$datos = json_decode($datos);

// Procesa los clasificadores TBD y construye el arreglo de detalles
$final2 = [];
foreach ($datos->data as $key => $value) {
    $array = [];
    $array["Id"] = $value->{"clasificador.clasificador_id"};
    $array["Description"] = $value->{"clasificador.descripcion"};

    array_push($final2, $array);
}

// Construye y retorna la respuesta final con las categorías y detalles de campaña
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response["Data"] = array(
    "Categories" => $final,
    "DetailsCampaign" => $final2
);
