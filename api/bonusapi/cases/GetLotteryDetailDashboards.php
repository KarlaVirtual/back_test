<?php


use Backend\dto\UsuarioSorteo;


/**
 * Procesa los datos de una solicitud para obtener detalles del dashboard de loterías.
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
$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->ResultToDate;

$FromDateLocal = $params->ResultFromDate;

/* extrae e inicializa parámetros necesarios para procesar una solicitud. */
$BonusDefinitionsIds = $params->BonusDefinitionIds;
$PlaterExternalId = $params->PlaterExternalId;

$MaxRows = $params->Limit;
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($params->Offset) * $MaxRows;


/* extrae un ID de la solicitud y crea reglas de filtrado si está presente. */
$Id = $_REQUEST["Id"];

$rules = [];

if ($Id != "") {
    array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => "$Id", "op" => "eq"));
}


/* configura un filtro y establece valores predeterminados para variables. */
$filtro = array("rules" => $rules, "groupOp" => "AND");

if ($SkeepRows == "") {
    $SkeepRows = 0;
}
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

/* asigna un valor por defecto a `$MaxRows` y prepara una consulta SQL. */
if ($MaxRows == "") {
    $MaxRows = 1000000000;
}

$json = json_encode($filtro);

$select = "COUNT(*)usuarios,
        SUM(usuario_sorteo.valor_base) dinero,
        sorteo_interno.fecha_inicio,
        sorteo_interno.fecha_fin
        ";


/* Crea un objeto UsuarioSorteo, obtiene y decodifica datos del usuario. */
$UsuarioSorteo = new UsuarioSorteo();
$data = $UsuarioSorteo->getUsuarioSorteosCustom($select, "usuario_sorteo.ususorteo_id", "asc", $SkeepRows, $MaxRows, $json, true, '');


$data = json_decode($data);

$value = $data->data[0];


/* Calcula la diferencia en segundos entre dos fechas. */
$ts1 = strtotime($value->{"sorteo_interno.fecha_inicio"});
$ts2 = strtotime($value->{"sorteo_interno.fecha_fin"});

$second_dif = $ts2 - $ts1; //  obtenemos los segundos entre fecha inicio y fecha fin


$ts1 = strtotime($value->{"sorteo_interno.fecha_fin"});

/* compara dos marcas de tiempo y asigna 100 a `$porc` si `$ts1` es menor. */
$ts2 = strtotime(date('Y-m-d H:i:s'));


if ($ts1 < $ts2) {
    $porc = 100;

} else {
    /* Calcula el progreso en porcentaje entre dos marcas de tiempo dadas. */

    $seconds_diff2 = $ts1 - $ts2; // estamos obteniendo los segundos entre fecha final y fecha actual que serian Los segundos que faltan por terminar
    $progreso = ($second_dif - $seconds_diff2);
    $porc = $progreso * 100 / $second_dif;

}


/* Crea una estructura de datos que almacena información monetaria redondeada y total. */
$final = [];


$final["RealMoney"] = [];
$final["RealMoney"]["Total"] = round($value->{".dinero"}, 2);
$final["RealMoney"]["Amount"] = $value->{".dinero"};


/* Calcula y redondea GGR y monto en dinero real, inicializando participantes. */
$final["RealMoney"]["GGR"] = $final["RealMoney"]["Amount"] - $final["RealMoney"]["AmountWin"];


$final["RealMoney"]["Amount"] = round($final["RealMoney"]["Amount"], 2);
$final["RealMoney"]["GGR"] = round($final["RealMoney"]["GGR"], 2);


$final["Participantes"] = [];
$final["Participantes"]["Total"] = $value->{".usuarios"};
$final["Participantes"]["Amount"] = $value->{".usuarios"};


/* Se asigna un valor redondeado a "Amount" y se configura la respuesta. */
$final["Progress"] = [];
$final["Progress"]["Total"] = round($porc, 2);
$final["Progress"]["Amount"] = round($porc, 2);

$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];


/* Asigna el valor de $final a la clave "Data" del arreglo $response. */
$response["Data"] = $final;


?>

