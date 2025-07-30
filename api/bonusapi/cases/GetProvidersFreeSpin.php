<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/


use Backend\dto\Proveedor;
use Backend\dto\SubproveedorMandantePais;


/**
 * Obtiene una lista de proveedores con sistema de bonificación activado, filtrados por tipo y país.
 *
 * @param string $type Tipo de proveedor solicitado:
 * - 3: Live Casino.
 * - 4: Virtual.
 * - Otro valor: Casino.
 * @param string $Country ID del país para filtrar proveedores.
 *
 * @return array $response Respuesta estructurada con los siguientes campos:
 * - HasError (bool): Indica si hubo un error (false si no hay errores).
 * - AlertType (string): Tipo de alerta (success en caso de éxito).
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo (vacío en caso de éxito).
 * - Data (array): Lista de proveedores con los campos Id, Name y Abbreviated.
 */


/* oculta errores y valida el acceso a proveedores para un partner específico. */
ini_set('display_errors', 'OFF');
$type = $_REQUEST["Type"];
$Country = $_REQUEST["Country"];

$Mandante = $_SESSION["mandante"];

if ($Mandante == "-1") {
    throw new Exception("No es posible obtener los proveedores de este partner");
}



/* Asignación de un tipo basado en el valor de la variable $type. */
$tipo = 'CASINO';

if ($type == 3) {
    $tipo = 'LIVECASINO';
}

if ($type == 4) {
    $tipo = 'VIRTUAL';
}

/* inicializa reglas y crea un objeto Subproveedor, restringiendo filas procesadas. */
$rules = [];

$Subproveedor = new \Backend\dto\Subproveedor();

$SkeepRows = 0;
$MaxRows = 1000000;


/*
 * proposito:  se utiliza rules para solo mostrar los proveedores con bonusSystem activado al momento de crear un freespin
 * ademas de filtrar  solo con el pais
*/


/* Construye un objeto JSON con reglas para filtrar datos según condiciones específicas. */
$json = '{
    "rules": [
        {"field": "subproveedor.tipo", "data": "' . $tipo . '", "op": "eq"},
        {"field": "subproveedor_mandante_pais.bonus_system", "data": "S", "op": "eq"},
        {"field": "subproveedor_mandante_pais.pais_id", "data": "' . $Country . '", "op": "eq"},
        {"field": "subproveedor_mandante_pais.mandante", "data": "' . $Mandante . '", "op": "eq"} 
    ],
    "groupOp": "AND"
}';


/* Se obtiene una lista de subproveedores desde una clase y se decodifica en JSON. */
$SubproveedorMandantePais = new SubproveedorMandantePais();
$proveedores = $SubproveedorMandantePais->getSubproveedoresMandantePaisCustom("subproveedor_mandante_pais.*,subproveedor.*", "subproveedor_mandante_pais.provmandante_id", "desc", $SkeepRows, $MaxRows, $json, true);

$proveedores = json_decode($proveedores);

$final = [];


/* Itera sobre proveedores para crear un arreglo con sus datos específicos. */
foreach ($proveedores->data as $key => $value) {

    $array = [];

    $array["Id"] = $value->{"subproveedor.subproveedor_id"};
    $array["Name"] = $value->{"subproveedor.descripcion"};
    $array["Abbreviated"] = $value->{"subproveedor.abreviado"};

    array_push($final, $array);

}


/* crea una respuesta sin errores y contiene datos finales. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;
