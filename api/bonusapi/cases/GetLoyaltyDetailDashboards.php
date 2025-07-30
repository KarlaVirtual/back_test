<?php

use Backend\dto\UsuarioLealtad;

/**
 * Este script genera un resumen de bonificaciones activas, redimidas y expiradas
 * basado en los datos de lealtad de los usuarios.
 *
 * @param object $params Objeto JSON decodificado que contiene los siguientes parámetros:
 * @param string $params->ResultToDate Fecha de fin de resultados.
 * @param string $params->ResultFromDate Fecha de inicio de resultados.
 * @param array $params->BonusDefinitionIds Identificadores de definiciones de bonificación.
 * @param string $params->PlaterExternalId Identificador externo del jugador.
 * @param int $params->Limit Límite de filas a consultar.
 * @param int $params->Offset Desplazamiento para la consulta.
 * @param int $params->OrderedItem Elemento por el cual ordenar.
 * 
 *
 * @return array $response Respuesta estructurada con los siguientes elementos:
 *  - HasError: Indica si ocurrió un error (false si no hay errores).
 *  - AlertType: Tipo de alerta (success, error, etc.).
 *  - AlertMessage: Mensaje de alerta.
 *  - ModelErrors: Lista de errores de validación.
 *  - Result: Resumen de bonificaciones activas, redimidas y expiradas.
 *  - Data: Resumen de bonificaciones activas, redimidas y expiradas.
 */

/* obtiene y decodifica datos JSON de una solicitud HTTP en PHP. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->ResultToDate;

$FromDateLocal = $params->ResultFromDate;

/* Asignación de parámetros para procesar datos relacionados con bonificaciones y control de filas. */
$BonusDefinitionsIds = $params->BonusDefinitionIds;
$PlaterExternalId = $params->PlaterExternalId;

$MaxRows = $params->Limit;
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($params->Offset) * $MaxRows;


/* Crea una regla para el id de lealtad si no está vacío. */
$Id = $_REQUEST["IdLoyalty"];

$rules = [];

if ($Id != "") {
    array_push($rules, array("field" => "lealtad_interna.lealtad_id", "data" => "$Id", "op" => "eq"));
}


/* Se configura un filtro y se inicializan variables si están vacías. */
$filtro = array("rules" => $rules, "groupOp" => "AND");

if ($SkeepRows == "") {
    $SkeepRows = 0;
}
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

/* Calcula sumas y cantidades de valores de usuarios según su estado de lealtad. */
if ($MaxRows == "") {
    $MaxRows = 1000000000;
}

$json = json_encode($filtro);

$select = "SUM(CASE WHEN usuario_lealtad.estado =  'R' THEN usuario_lealtad.valor ELSE 0 END) valor_redimidos,
        SUM(CASE WHEN usuario_lealtad.estado =  'A' THEN usuario_lealtad.valor ELSE 0 END) valor_activos,
        SUM(CASE WHEN usuario_lealtad.estado =  'E' THEN usuario_lealtad.valor ELSE 0 END) valor_expirados,
        SUM(CASE WHEN usuario_lealtad.estado =  'R' THEN 1 ELSE 0 END) cant_redimidos,
        SUM(CASE WHEN usuario_lealtad.estado =  'A' THEN 1 ELSE 0 END) cant_activos,
        SUM(CASE WHEN usuario_lealtad.estado =  'E' THEN 1 ELSE 0 END) cant_expirados
        ";


/* Se obtiene y decodifica información de lealtad de usuario en formato JSON. */
$UsuarioLealtad = new UsuarioLealtad();
$data = $UsuarioLealtad->getUsuarioLealtadCustom($select, "usuario_lealtad.usulealtad_id", "asc", $SkeepRows, $MaxRows, $json, true, '');


$data = json_decode($data);

$value = $data->data[0];


/* Inicializa un arreglo PHP con datos sobre bonificaciones activas y su formato. */
$final = [];


$final["ActiveBonus"] = [];
$final["ActiveBonus"]["Total"] = $value->{".cant_activos"};
$final["ActiveBonus"]["Amount"] = number_format($value->{".valor_activos"}, 2);

/* Se estructura un arreglo para redenciones y bonificaciones expiradas con formatos numéricos. */
$final["RedimBonus"] = [];
$final["RedimBonus"]["Total"] = $value->{".cant_redimidos"};
$final["RedimBonus"]["Amount"] = number_format($value->{".valor_redimidos"}, 2);
$final["ExpiratedBonus"] = [];
$final["ExpiratedBonus"]["Total"] = $value->{".cant_expirados"};
$final["ExpiratedBonus"]["Amount"] = number_format($value->{".valor_expirados"}, 2);

/* Suma diferentes tipos de bonificaciones y formatea el total a dos decimales. */
$final["AllBonus"] = [];
$final["AllBonus"]["Total"] = $final["ActiveBonus"]["Total"] + $final["RedimBonus"]["Total"] + $final["ExpiratedBonus"]["Total"];
$final["AllBonus"]["Amount"] = $value->{".valor_activos"} + $value->{".valor_redimidos"} + $value->{".valor_expirados"};
$final["AllBonus"]["Amount"] = number_format($final["AllBonus"]["Amount"], 2);

/* asigna valores a un array de respuesta para una operación exitosa. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Result"] = $final;
$response["Data"] = $final;


?>

