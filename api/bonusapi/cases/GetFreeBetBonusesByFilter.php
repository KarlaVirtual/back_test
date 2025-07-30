<?php

use Backend\dto\BonoInterno;


/**
 * Filtra y obtiene una lista de bonos personalizados según los parámetros proporcionados.
 *
 * @param object $params Objeto que contiene los parámetros de entrada:
 * @param string $StartTimeLocal Fecha de inicio en formato local.
 * @param string $EndTimeLocal Fecha de fin en formato local.
 * @param string $BonusType Tipo de bono.
 * @param int $MaxRows Número máximo de filas a devolver.
 * @param int $OrderedItem Elemento por el cual ordenar.
 * @param int $SkeepRows Número de filas a omitir.
 * 
 * 
 * @return array $response Respuesta estructurada con los siguientes campos:
 *  - HasError (bool): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta ('success', 'error', etc.).
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *  - Result (array): Lista de bonos filtrados con los campos:
 *    - Id (int): ID del bono.
 *    - Name (string): Nombre del bono.
 *    - Description (string): Descripción del bono.
 *    - BeginDate (string): Fecha de inicio del bono.
 *    - EndDate (string): Fecha de fin del bono.
 *    - ProductTypeId (int): ID del tipo de producto.
 *    - TypeId (int): ID del tipo.
 *    - Type (array): Información del tipo.
 *    - entity (array): Información de la entidad.
 */




/* recibe y decodifica datos JSON mediante PHP. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->StartTimeLocal;
$FromDateLocal = $params->EndTimeLocal;
$BonusType = $params->BonusType;


/* Se inicializan variables a partir de los parámetros y se declara un array vacío. */
$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;


$rules = [];


/* Condicional que agrega reglas de filtro según el tipo de bono especificado. */
if ($BonusType != "") {
    array_push($rules, array("field" => "bono_interno.tipo", "data" => "$BonusType", "op" => "eq"));
}

$filtro = array("rules" => $rules, "groupOp" => "AND");

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* Asigna valores predeterminados a $OrderedItem y $MaxRows si están vacíos. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000000000;
}


/* convierte un filtro a JSON y obtiene bonos personalizados de una clase. */
$json = json_encode($filtro);

$BonoInterno = new BonoInterno();

$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, true);

$bonos = json_decode($bonos);


/* Se inicializa un arreglo vacío llamado "final". */
$final = [];

foreach ($bonos->data as $key => $value) {


    /* crea un array asociativo con datos de un objeto "bono_interno". */
    $array = [];

    $array["Id"] = $value->{"bono_interno.bono_id"};
    $array["Name"] = $value->{"bono_interno.nombre"};
    $array["Description"] = $value->{"bono_interno.descripcion"};
    $array["BeginDate"] = $value->{"bono_interno.fecha_inicio"};
    $array["EndDate"] = $value->{"bono_interno.fecha_fin"};
    $array["ProductTypeId"] = 2;
    $array["TypeId"] = 6;
    $array["Type"] = array(
        "Id" => 2,
        "Name" => "Primer Deposito",
        "TypeId" => 2
    );

    /* crea un array asociativo y lo añade a otro array llamado "final". */
    $array["entity"] = array(
        "Id" => 2,
        "Name" => "Primer Deposito",
        "TypeId" => 2
    );

    array_push($final, $array);
}


/* Código para estructurar una respuesta JSON sin errores, con mensaje de éxito y datos. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Result"] = $final;
