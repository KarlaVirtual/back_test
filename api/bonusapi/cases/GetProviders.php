<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/


use Backend\dto\Proveedor;


/**
 * Obtiene una lista de subproveedores filtrados por tipo y mandante.
 *
 * @param string $type Tipo de proveedor solicitado:
 * - 3: Live Casino.
 * - 4: Virtual.
 * - Otro valor: Casino.
 *
 * @return array $response Respuesta estructurada con los siguientes campos:
 * - HasError (bool): Indica si hubo un error (false si no hay errores).
 * - AlertType (string): Tipo de alerta (success en caso de éxito).
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo (vacío en caso de éxito).
 * - Data (array): Lista de subproveedores con los campos Id y Name.
 */


/* desactiva errores y asigna un tipo basado en la solicitud del usuario. */
ini_set('display_errors', 'OFF');
$type = $_REQUEST["Type"];

$tipo = 'CASINO';

if ($type == 3) {
    $tipo = 'LIVECASINO';
}


/* Asigna 'VIRTUAL' a $tipo si $type es igual a 4 y crea un objeto Proveedor. */
if ($type == 4) {
    $tipo = 'VIRTUAL';
}


$Proveedor = new Proveedor();


/* Inicializa variables y prepara un arreglo para almacenar datos de proveedores. */
$SkeepRows = 0;
$MaxRows = 1000000;

/*$json = '{"rules" : [{"field" : "proveedor.tipo", "data": "' . $tipo . '","op":"eq"}] ,"groupOp" : "AND"}';

$proveedores = $Proveedor->getProveedoresCustom(" proveedor.* ", "proveedor.proveedor_id", "asc", $SkeepRows, $MaxRows, $json, true);
$proveedores = json_decode($proveedores);*/

/*$final = [];
foreach ($proveedores->data as $key => $value) {

    $array = [];

    $array["Id"] = $value->{"proveedor.proveedor_id"};
    $array["Name"] = $value->{"proveedor.descripcion"};

    array_push($final, $array);

}*/

$final = [];


/* Se crean reglas de filtro para una consulta, basadas en condiciones específicas. */
$rules = [];

array_push($rules, array("field" => "subproveedor.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "subproveedor.tipo", "data" => "'$tipo'", "op" => "in"));

if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "subproveedor_mandante.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
} else {
    /* Condiciona la adición de reglas según la sesión "mandanteLista". */


    if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
        array_push($rules, array("field" => "subproveedor_mandante.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
    }

}

/* Se crea un filtro JSON y se obtienen subproveedores desde la base de datos. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$SubproveedorMandante = new \Backend\dto\SubproveedorMandante();
$subprovedores = $SubproveedorMandante->getSubproveedoresMandanteCustom("subproveedor.subproveedor_id, subproveedor.descripcion,proveedor.descripcion", "subproveedor.subproveedor_id", "asc", $SkeepRows, $MaxRows, $json, true);


$subprovedores = json_decode($subprovedores);

/* Convierte datos de subproveedores en un arreglo final con id y nombre формateado. */
$final = [];


foreach ($subprovedores->data as $key => $value) {

    $array = [];

    /*$array["Id"] = $value->{"proveedor.proveedor_id"};
    $array["Name"] = $value->{"proveedor.descripcion"};*/

    $array["Id"] = $value->{"subproveedor.subproveedor_id"};
    $array["Name"] = $value->{"subproveedor.descripcion"} . "(" . $value->{"subproveedor.descripcion"} . ")";

    array_push($final, $array);


}


/* Código asigna valores a un array para gestionar respuestas de una API o aplicación. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;
