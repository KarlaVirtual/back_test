<?php


use Backend\dto\UsuarioJackpot;


/**
 * Este script procesa los detalles del jackpot para el dashboard.
 * 
 * @param object $params Objeto JSON decodificado que contiene:
 * @param string $params->Id ID del jackpot recibido a través de $_REQUEST.
 * @param string $params->ResultToDate Fecha final del rango de resultados.
 * @param string $params->ResultFromDate Fecha inicial del rango de resultados.
 * @param array $params->BonusDefinitionIds IDs de definiciones de bonos.
 * @param string $params->PlaterExternalId ID externo del jugador.
 * @param int $params->Limit Límite de filas a devolver.
 * @param int $params->OrderedItem Elemento por el cual ordenar.
 * @param int $params->Offset Número de páginas a omitir.
 * 
 * @return array $response Respuesta estructurada que incluye:
 *  - bool $HasError Indica si ocurrió un error.
 *  - string $AlertType Tipo de alerta (e.g., "success").
 *  - string $AlertMessage Mensaje de alerta.
 *  - array $ModelErrors Errores del modelo, si los hay.
 *  - array $Data Datos procesados del jackpot, incluyendo:
 *    - array $RealMoney Información sobre dinero real:
 *      - float $Total Total de dinero.
 *      - float $Amount Monto de dinero.
 *      - float $GGR Ganancia bruta de juego.
 *    - array $Participantes Información sobre participantes:
 *      - int $Total Total de participantes.
 *      - int $Amount Cantidad de participantes.
 *    - array $Progress Progreso del jackpot:
 *      - float $Total Porcentaje total de progreso.
 *      - float $Amount Porcentaje actual de progreso.
 */


/* recibe y decodifica datos JSON desde una entrada HTTP en PHP. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->ResultToDate;

$FromDateLocal = $params->ResultFromDate;

/* asigna valores de parámetros a variables para gestionar paginación y filtrado. */
$BonusDefinitionsIds = $params->BonusDefinitionIds;
$PlaterExternalId = $params->PlaterExternalId;

$MaxRows = $params->Limit;
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($params->Offset) * $MaxRows;


/* recibe un ID y lo añade a un array de reglas si no está vacío. */
$Id = $_REQUEST["Id"];

$rules = [];

if ($Id != "") {
    array_push($rules, array("field" => "jackpot_interno.jackpot_id", "data" => "$Id", "op" => "eq"));
}


/* Define un filtro y ajusta variables relacionadas a filas y orden. */
$filtro = array("rules" => $rules, "groupOp" => "AND");

if ($SkeepRows == "") {
    $SkeepRows = 0;
}
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

/* establece un valor predeterminado y prepara una consulta SQL para datos. */
if ($MaxRows == "") {
    $MaxRows = 1000000000;
}

$json = json_encode($filtro);

$select = "COUNT(*)usuarios,
        SUM(usuario_jackpot.valor) dinero,
        jackpot_interno.fecha_inicio,
        jackpot_interno.fecha_fin
        ";


/* Se crea un objeto y se obtienen datos JSON de un jackpot de usuario. */
$UsuarioJackpot = new UsuarioJackpot();
$data = $UsuarioJackpot->getUsuarioJackpotCustom($select, "usuario_jackpot.usujackpot_id", "asc", $SkeepRows, $MaxRows, $json, true, '');


$data = json_decode($data);

$value = $data->data[0];


/* Calcula la diferencia en segundos entre dos fechas de un jackpot interno. */
$ts1 = strtotime($value->{"jackpot_interno.fecha_inicio"});
$ts2 = strtotime($value->{"jackpot_interno.fecha_fin"});

$second_dif = $ts2 - $ts1; //  obtenemos los segundos entre fecha inicio y fecha fin


$ts1 = strtotime($value->{"jackpot_interno.fecha_fin"});

/* Compara dos marcas de tiempo y asigna 100% si la primera es menor. */
$ts2 = strtotime(date('Y-m-d H:i:s'));


if ($ts1 < $ts2) {
    $porc = 100;

} else {
    /* Calcula el progreso y porcentaje restante entre dos marcas de tiempo. */

    $seconds_diff2 = $ts1 - $ts2; // estamos obteniendo los segundos entre fecha final y fecha actual que serian Los segundos que faltan por terminar
    $progreso = ($second_dif - $seconds_diff2);
    $porc = $progreso * 100 / $second_dif;

}


/* Inicializa un array final con total y monto de dinero redondeado. */
$final = [];


$final["RealMoney"] = [];
$final["RealMoney"]["Total"] = round($value->{".dinero"}, 2);
$final["RealMoney"]["Amount"] = $value->{".dinero"};


/* Calcula el GGR y redondea montos en el arreglo final. */
$final["RealMoney"]["GGR"] = $final["RealMoney"]["Amount"] - $final["RealMoney"]["AmountWin"];


$final["RealMoney"]["Amount"] = round($final["RealMoney"]["Amount"], 2);
$final["RealMoney"]["GGR"] = round($final["RealMoney"]["GGR"], 2);


$final["Participantes"] = [];

/* asigna datos de usuarios y progreso en un arreglo final. */
$final["Participantes"]["Total"] = $value->{".usuarios"};
$final["Participantes"]["Amount"] = $value->{".usuarios"};


$final["Progress"] = [];
$final["Progress"]["Total"] = round($porc, 2);
$final["Progress"]["Amount"] = round($porc, 2);

$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];


/* Asigna el valor de la variable $final al índice "Data" de $response. */
$response["Data"] = $final;


?>

