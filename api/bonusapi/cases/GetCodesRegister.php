<?php

use Backend\dto\CodigoPromocional;

/**
 * Este script gestiona la obtención de códigos promocionales personalizados.
 * 
 * @param int $SkeepRows Número de filas a omitir en la consulta (por defecto 0).
 * @param int $OrderedItem Orden de los elementos en la consulta (por defecto 1).
 * @param int $MaxRows Número máximo de filas a devolver (por defecto 1000).
 * @param array $_SESSION Contiene información de la sesión del usuario, como Global y mandante.
 * 
 * @return array $response Respuesta estructurada que incluye:
 * - HasError: Indica si ocurrió un error (boolean).
 * - AlertType: Tipo de alerta (string).
 * - AlertMessage: Mensaje de la operación (string).
 * - ModelErrors: Lista de errores de modelo (array).
 * - Data: Datos de los códigos promocionales obtenidos (array).
 * - Result: Resultado final con los datos obtenidos (array).
 */

/* inicializa variables si están vacías, asignando valores predeterminados. */
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}

/* Establece un número máximo de filas y define reglas para promociones. */
if ($MaxRows == "") {
    $MaxRows = 1000;
}

$rules = [];

array_push($rules, array("field" => "codigo_promocional.funcion", "data" => "1", "op" => "eq"));

// Si el usuario esta condicionado por el mandante y no es de Global

/* agrega reglas a un arreglo según la sesión del usuario. */
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "codigo_promocional.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
} else {

    if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
        array_push($rules, array("field" => "codigo_promocional.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
    }

}// Inactivamos reportes para el país Colombia

/* Se crea un filtro JSON para obtener códigos promocionales de manera personalizada. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$CodigoPromocional = new CodigoPromocional();

$data = $CodigoPromocional->getCodigoPromocionalsCustom("  codigo_promocional.* ", "codigo_promocional.codpromocional_id", "asc", $SkeepRows, $MaxRows, $json, true);

/* Convierte datos JSON en un arreglo estructurado con información promocional específica. */
$data = json_decode($data);
$final = [];

foreach ($data->data as $key => $value) {

    $array = [];

    $array["Id"] = $value->{"codigo_promocional.codpromocional_id"};
    $array["Code"] = $value->{"codigo_promocional.codigo"};
    $array["Name"] = $value->{"codigo_promocional.descripcion"};
    $array["CreatedLocalDate"] = $value->{"codigo_promocional.fecha_crea"};
    $array["State"] = $value->{"codigo_promocional.estado"};
    $array["UserId"] = $value->{"codigo_promocional.usuario_id"};
    $array["Function"] = $value->{"codigo_promocional.funcion"};

    array_push($final, $array);

}

/* configura una respuesta sin errores y con datos finales. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;

/* Asigna el valor de `$final` a la clave "Result" en el array `$response`. */
$response["Result"] = $final;
