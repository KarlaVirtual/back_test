<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\UsuarioTorneo;

/**
 * Procesa y genera un resumen de detalles del torneo.
 *
 * @param string $params JSON de entrada con los siguientes valores:
 * @param string $params->ResultToDate Fecha de fin del resultado.
 * @param string $params->ResultFromDate Fecha de inicio del resultado.
 * @param array $params->BonusDefinitionIds IDs de definición de bonos.
 * @param string $params->PlayerExternalId ID externo del jugador.
 * @param int $params->Limit Límite de filas.
 * @param string $params->OrderedItem Elemento para ordenar.
 * @param int $params->Offset Desplazamiento de filas.
 * 
 * 
 * @return array $response Respuesta estructurada con los siguientes valores:
 * - HasError (bool): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta ('success', 'error', etc.).
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo.
 * - Result (array): Resumen de créditos, dinero real, jugadores y progreso.
 * - Data (array): Resumen de créditos, dinero real, jugadores y progreso.
 */


/* lee datos JSON de entrada y extrae fechas y bonos específicos. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->ResultToDate;
$FromDateLocal = $params->ResultFromDate;
$BonusDefinitionIds = $params->BonusDefinitionIds;

/* asigna parámetros y establece valores para paginación y selección de datos. */
$PlayerExternalId = $params->PlayerExternalId;

$MaxRows = $params->Limit;
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($params->Offset) * $MaxRows;

$Id = $_REQUEST["Id"];


/* Se crea un filtro con reglas para consultas, ajustando el número de filas a omitir. */
$rules = [];

array_push($rules, array("field" => "torneo_interno.torneo_id", "data" => "$Id", "op" => "eq"));

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


/* Código para contar y sumar datos de usuarios, créditos, dinero y premios en torneos. */
$json = json_encode($filtro);

$select = "COUNT(*) usuarios,
        SUM(usuario_torneo.valor) creditos,
        SUM(usuario_torneo.valor_base) dinero,
        SUM(usuario_torneo.valor_premio) premios,
        torneo_interno.fecha_inicio,
        torneo_interno.fecha_fin
        ";


/* Se obtiene y decodifica información de torneos de usuario en formato JSON. */
$UsuarioTorneo = new UsuarioTorneo();
$data = $UsuarioTorneo->getUsuarioTorneosCustom($select, "usuario_torneo.usutorneo_id", "asc", $SkeepRows, $MaxRows, $json, true, '');


$data = json_decode($data);

$value = $data->data[0];


/* Calcula la diferencia en segundos entre dos fechas de un torneo interno. */
$ts1 = strtotime($value->{"torneo_interno.fecha_inicio"});
$ts2 = strtotime($value->{"torneo_interno.fecha_fin"});

$seconds_diff = $ts2 - $ts1;

$ts1 = strtotime($value->{"torneo_interno.fecha_fin"});

/* Calcula la diferencia en segundos entre dos fechas y asigna un valor predeterminado. */
$ts2 = strtotime(date('Y-m-d H:i:s'));
$seconds_diff2 = $ts1 - $ts2;

if ($seconds_diff == 0) {
    $porc = 0; // o cualquier valor por defecto apropiado
} else {
    /* Calcula el porcentaje basado en la diferencia de segundos entre dos valores. */

    $porc = (($seconds_diff2 * 100) / $seconds_diff);
}


/* ajusta un porcentaje y lo limita a un máximo de 100. */
$porc = 100 - $porc;

if ($porc > 100) {
    $porc = 100;
}
$final = [];

/* almacena y redondea créditos en un array estructurado. */
$final["Credits"] = [];
$final["Credits"]["Total"] = $value->{".creditos"};
$final["Credits"]["Amount"] = $value->{".creditos"};

$final["Credits"]["Amount"] = round($final["Credits"]["Amount"], 2);

$final["RealMoney"] = [];

/* Calcula y redondea total, cantidad, premios y GGR en un arreglo final. */
$final["RealMoney"]["Total"] = round($value->{".dinero"}, 2);
$final["RealMoney"]["Amount"] = $value->{".dinero"};
$final["RealMoney"]["AmountWin"] = $value->{".premios"};
$final["RealMoney"]["GGR"] = $final["RealMoney"]["Amount"] - $final["RealMoney"]["AmountWin"];

$final["RealMoney"]["Amount"] = round($final["RealMoney"]["Amount"], 2);

/* Se redondean valores y se inicializan datos sobre jugadores en el arreglo final. */
$final["RealMoney"]["AmountWin"] = round($final["RealMoney"]["AmountWin"], 2);
$final["RealMoney"]["GGR"] = round($final["RealMoney"]["GGR"], 2);


$final["Players"] = [];
$final["Players"]["Total"] = $value->{".usuarios"};

/* asigna valores a un arreglo en PHP y maneja errores. */
$final["Players"]["Amount"] = $value->{".usuarios"};
$final["Progress"] = [];
$final["Progress"]["Total"] = round($porc, 2);
$final["Progress"]["Amount"] = round($porc, 2);

$response["HasError"] = false;

/* Código que prepara una respuesta con estado de éxito y resultados procesados. */
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Result"] = $final;
$response["Data"] = $final;