<?php

use Backend\dto\UsuarioSorteo2;


/**
 * Procesa los datos de una solicitud para obtener detalles del dashboard de apuestas de loterías.
 *
 * @param object $params Objeto JSON decodificado que contiene:
 * @param string $params->ResultToDate Fecha de finalización del rango.
 * @param string $params->ResultFromDate Fecha de inicio del rango.
 * @param array $params->BonusDefinitionIds IDs de definiciones de bonos.
 * @param string $params->PlaterExternalId ID externo del jugador.
 * @param int $params->Limit Límite de filas a obtener.
 * @param string $params->OrderedItem Elemento por el cual ordenar.
 * @param int $params->Offset Número de páginas a omitir.
 * 
 *
 * @return array $response Respuesta estructurada que incluye:
 * - HasError (bool): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (e.g., "success").
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo.
 * - Data (array): Datos procesados que incluyen:
 *   - RealMoney (array): Información monetaria.
 *   - Participantes (array): Información de participantes.
 *   - Progress (array): Progreso en porcentaje.
 */

/* obtiene y decodifica datos JSON enviados por un cliente en PHP. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->ResultToDate;

$FromDateLocal = $params->ResultFromDate;

/* Extrae y asigna parámetros para manejar límites y desplazamientos en un conjunto de datos. */
$BonusDefinitionsIds = $params->BonusDefinitionIds;
$PlaterExternalId = $params->PlaterExternalId;

$MaxRows = $params->Limit;
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($params->Offset) * $MaxRows;


/* obtiene un ID y agrega reglas si no está vacío. */
$Id = $_REQUEST["Id"];

$rules = [];


if ($Id != "") {
    array_push($rules, array("field" => "sorteo_interno2.sorteo2_id", "data" => "$Id", "op" => "eq"));
}


/* Se define un filtro con reglas y condiciones, y se inicializa un contador de filas. */
$filtro = array("rules" => $rules, "groupOp" => "AND");


if ($SkeepRows == "") {
    $SkeepRows = 0;
}

/* Establece valores predeterminados para $OrderedItem y $MaxRows si están vacíos. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}
if ($MaxRows == "") {
    $MaxRows = 1000000000;
}


/* Código para codificar un arreglo en JSON y contar usuarios con sumas de valores. */
$json = json_encode($filtro);


$select = "COUNT(*)usuarios,
        SUM(usuario_sorteo2.valor_base) dinero,
        sorteo_interno2.fecha_inicio,
        sorteo_interno2.fecha_fin
        ";


/* obtiene datos de un usuario, los decodifica y selecciona el primer valor. */
$UsuarioSorteo = new UsuarioSorteo2();

$data = $UsuarioSorteo->getUsuarioSorteosCustom($select, "usuario_sorteo2.ususorteo2_id", "asc", $SkeepRows, $MaxRows, $json, true, '');

$data = json_decode($data);


$value = $data->data[0];


/* Calcula la diferencia en segundos entre dos fechas de sorteo. */
$ts1 = strtotime($value->{"sorteo_interno2.fecha_inicio"});
$ts2 = strtotime($value->{"sorteo_interno2.fecha_fin"});

$second_dif = $ts2 - $ts1; //  obtenemos los segundos entre fecha inicio y fecha fin


$ts1 = strtotime($value->{"sorteo_interno.fecha_fin"});

/* Compara dos marcas de tiempo y asigna 100% si la primera es anterior. */
$ts2 = strtotime(date('Y-m-d H:i:s'));


if ($ts1 < $ts2) {
    $porc = 100;

} else {
    /* Calcula el progreso en porcentaje entre dos tiempos dados. */

    $seconds_diff2 = $ts1 - $ts2; // estamos obteniendo los segundos entre fecha final y fecha actual que serian Los segundos que faltan por terminar
    $progreso = ($second_dif - $seconds_diff2);
    $porc = $progreso * 100 / $second_dif;

}


/* crea un arreglo con datos de dinero real y su ganancia neta. */
$final = [];

$final["RealMoney"] = [];
$final["RealMoney"]["Total"] = round($value->{".dinero"}, 2);
$final["RealMoney"]["Amount"] = $value->{".dinero"};

$final["RealMoney"]["GGR"] = $final["RealMoney"]["Amount"] - $final["RealMoney"]["AmountWin"];


/* redondea montos y GGR, y asigna el total de participantes. */
$final["RealMoney"]["Amount"] = round($final["RealMoney"]["Amount"], 2);
$final["RealMoney"]["GGR"] = round($final["RealMoney"]["GGR"], 2);


$final["Participantes"] = [];
$final["Participantes"]["Total"] = $value->{".usuarios"};
$final["Participantes"]["Amount"] = $value->{".usuarios"};


$final["Progress"] = [];
$final["Progress"]["Total"] = round($porc, 2);
$final["Progress"]["Amount"] = round($porc, 2);


/* crea una respuesta estructurada sin errores y con datos finalizados. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;