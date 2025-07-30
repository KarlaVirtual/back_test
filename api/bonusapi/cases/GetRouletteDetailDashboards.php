<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\UsuarioRuleta;

/**
 * Este script procesa una solicitud HTTP para obtener estadísticas detalladas de ruletas.
 * 
 * @param object $params Objeto JSON decodificado que contiene:
 * @param string $params->ResultToDate Fecha y hora de fin de los resultados.
 * @param string $params->ResultFromDate Fecha y hora de inicio de los resultados.
 * @param array $params->BonusDefinitionIds IDs de definiciones de bonificación.
 * @param string $params->PlayerExternalId ID externo del jugador.
 * @param int $params->Limit Número máximo de filas a obtener.
 * @param string $params->OrderedItem Campo por el cual ordenar los resultados.
 * @param int $params->Offset Número de filas a omitir.
 * 
 * @return array $response Respuesta estructurada que contiene:
 *  - HasError (bool): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta ("success", "error", etc.).
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores de modelo.
 *  - Result (array): Estadísticas de la ruleta, incluyendo:
 *      - Credits (array): Créditos totales y monto.
 *      - RealMoney (array): Dinero real, premios y GGR.
 *      - Players (array): Total de jugadores.
 *      - Progress (array): Progreso en porcentaje.
 *  - Data (array): Datos de las estadísticas.
 */

/* obtiene y decodifica datos JSON de una entrada HTTP en PHP. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->ResultToDate;
$FromDateLocal = $params->ResultFromDate;
$BonusDefinitionIds = $params->BonusDefinitionIds;

/* asigna valores de parámetros y calcula filas para una consulta. */
$PlayerExternalId = $params->PlayerExternalId;

$MaxRows = $params->Limit;
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($params->Offset) * $MaxRows;

$Id = $_REQUEST["Id"];


/* Se configuran reglas para filtrar datos en una consulta SQL. */
$rules = [];

array_push($rules, array("field" => "ruleta_interno.ruleta_id", "data" => "$Id", "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* Inicializa variables $OrderedItem y $MaxRows si están vacías. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000000000;
}


/* genera un JSON y estructura una consulta SQL para estadísticas. */
$json = json_encode($filtro);

$select = "COUNT(*) usuarios,
        SUM(usuario_ruleta.valor) creditos,
        SUM(usuario_ruleta.valor_base) dinero,
        SUM(usuario_ruleta.valor_premio) premios,
        ruleta_interno.fecha_inicio,
        ruleta_interno.fecha_fin
        ";


/* Se obtiene información de usuario en formato JSON y se decodifica. */
$UsuarioRuleta = new UsuarioRuleta();
$data = $UsuarioRuleta->getUsuarioRuletasCustom($select, "usuario_ruleta.usuruleta_id", "asc", $SkeepRows, $MaxRows, $json, true, '');


$data = json_decode($data);

$value = $data->data[0];


/* Calcula la diferencia en segundos entre dos fechas obtenidas de un objeto. */
$ts1 = strtotime($value->{"ruleta_interno.fecha_inicio"});
$ts2 = strtotime($value->{"ruleta_interno.fecha_fin"});

$seconds_diff = $ts2 - $ts1;

$ts1 = strtotime($value->{"ruleta_interno.fecha_fin"});

/* Calcula el porcentaje de diferencia entre dos marcas de tiempo en segundos. */
$ts2 = strtotime(date('Y-m-d H:i:s'));
$seconds_diff2 = $ts1 - $ts2;

if($seconds_diff > 0){
    $porc = (($seconds_diff2 * 100) / $seconds_diff);
    $porc = 100 - $porc;
    
    /* limita el porcentaje a 100 y organiza créditos en un arreglo. */
    if ($porc > 100) {
        $porc = 100;
    }
}else{
    $porc =0;
}

$final = [];
$final["Credits"] = [];
$final["Credits"]["Total"] = $value->{".creditos"};

/* asigna y redondea créditos y dinero a un array final. */
$final["Credits"]["Amount"] = $value->{".creditos"};

$final["Credits"]["Amount"] = round($final["Credits"]["Amount"], 2);

$final["RealMoney"] = [];
$final["RealMoney"]["Total"] = round($value->{".dinero"}, 2);

/* calcula y redondea montos de dinero y premios. */
$final["RealMoney"]["Amount"] = $value->{".dinero"};
$final["RealMoney"]["AmountWin"] = $value->{".premios"};
$final["RealMoney"]["GGR"] = $final["RealMoney"]["Amount"] - $final["RealMoney"]["AmountWin"];

$final["RealMoney"]["Amount"] = round($final["RealMoney"]["Amount"], 2);
$final["RealMoney"]["AmountWin"] = round($final["RealMoney"]["AmountWin"], 2);

/* redondea GGR y asigna valores de usuarios a un array. */
$final["RealMoney"]["GGR"] = round($final["RealMoney"]["GGR"], 2);


$final["Players"] = [];
$final["Players"]["Total"] = $value->{".usuarios"};
$final["Players"]["Amount"] = $value->{".usuarios"};

/* almacena el progreso y estado en un array de respuesta. */
$final["Progress"] = [];
$final["Progress"]["Total"] = round($porc, 2);
$final["Progress"]["Amount"] = round($porc, 2);

$response["HasError"] = false;
$response["AlertType"] = "success";

/* Se configuran respuestas de alerta y errores en un sistema, incluyendo resultados finales. */
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Result"] = $final;
$response["Data"] = $final;