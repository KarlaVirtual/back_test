<?php


use Backend\dto\UsuarioSorteo;


/**
 * Procesa los detalles del dashboard de loterías.
 *
 * @param object $params Contiene los parámetros de entrada:
 * @param string $params ->ResultToDate Fecha final del rango de resultados.
 * @param string $params ->ResultFromDate Fecha inicial del rango de resultados.
 * @param array $params ->BonusDefinitionIds IDs de definiciones de bonificaciones.
 * @param string $params ->PlaterExternalId ID externo del jugador.
 * @param int $params ->Limit Número máximo de filas a devolver.
 * @param string $params ->OrderedItem Elemento por el cual ordenar.
 * @param int $params ->Offset Número de filas a omitir.
 *
 *
 * @return array $response Respuesta estructurada con los siguientes valores:
 * - HasError: Indica si ocurrió un error.
 * - AlertType: Tipo de alerta (success o error).
 * - AlertMessage: Mensaje de alerta.
 * - ModelErrors: Errores del modelo.
 * - Data: Datos procesados, incluyendo dinero real, participantes y progreso.
 */


/* obtiene y decodifica parámetros JSON de una solicitud HTTP en PHP. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->ResultToDate;

$FromDateLocal = $params->ResultFromDate;

/* asigna parámetros a variables para manejar paginación y definir bonificaciones. */
$BonusDefinitionsIds = $params->BonusDefinitionIds;
$PlaterExternalId = $params->PlaterExternalId;

$MaxRows = $params->Limit;
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($params->Offset) * $MaxRows;


/* Se construye un filtro con reglas basadas en un ID recibido. */
$Id = $_REQUEST["Id"];

$rules = [];

array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => "$Id", "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");


/* Asigna valores predeterminados a $SkeepRows y $OrderedItem si están vacíos. */
if ($SkeepRows == "") {
    $SkeepRows = 0;
}
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

/* establece un valor predeterminado y genera una consulta para contar usuarios y sumar valores. */
if ($MaxRows == "") {
    $MaxRows = 1000000000;
}

$json = json_encode($filtro);

$select = "COUNT(*)Usuarios,
        SUM(usuario_sorteo.valor_base) dinero,
        sorteo_interno.fecha_inicio,
        sorteo_interno.fecha_fin
        ";


/* Se obtiene y decodifica un JSON de sorteos de usuario. */
$UsuarioSorteo = new UsuarioSorteo();
$data = $UsuarioSorteo->getUsuarioSorteosCustomWithoutPosition($select, "usuario_sorteo.ususorteo_id", "asc", $SkeepRows, $MaxRows, $json, true, '');


$data = json_decode($data);

$value = $data->data[0];


/* Convierte fechas a timestamp y calcula la diferencia en segundos entre ellas. */
$ts1 = strtotime($value->{"sorteo_interno.fecha_inicio"});
$ts2 = strtotime($value->{"sorteo_interno.fecha_fin"});

$second_dif = $ts2 - $ts1;

$ts1 = strtotime($value->{"sorteo_interno.fecha_fin"});

/* Calcula el porcentaje de diferencia de tiempo entre dos marcas temporales. */
$ts2 = strtotime(date('Y-m-d H:i:s'));
$seconds_diff2 = $ts1 - $ts2;

$porc = (($seconds_diff2 * 100) / $seconds_diff);

$porc = 100 - $porc;


/* ajusta un porcentaje y crea un arreglo para dinero real. */
if ($porc > 100) {
    $porc = 100;
}

$final = [];


$final["RealMoney"] = [];

/* calcula y redondea valores monetarios en un arreglo asociativo. */
$final["RealMoney"]["Total"] = round($value->{".dinero"}, 2);
$final["RealMoney"]["Amount"] = $value->{".dinero"};

$final["RealMoney"]["GGR"] = $final["RealMoney"]["Amount"] - $final["RealMoney"]["AmountWin"];


$final["RealMoney"]["Amount"] = round($final["RealMoney"]["Amount"], 2);

/* Se redondea el GGR y se asignan detalles de participantes al arreglo final. */
$final["RealMoney"]["GGR"] = round($final["RealMoney"]["GGR"], 2);


$final["Participantes"] = [];
$final["Participantes"]["Total"] = $value->{".Usuarios"};
$final["Participantes"]["Amount"] = $value->{".Usuarios"};


/* inicializa un arreglo y establece valores de progreso y respuesta sin errores. */
$final["Progress"] = [];
$final["Progress"]["Total"] = round($porc, 2);
$final["Progress"]["Amount"] = round($porc, 2);

$response["HasError"] = false;
$response["AlertType"] = "success";

/* inicializa un array de respuesta con mensaje y errores vacíos. */
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;


?>

