<?php

use Backend\dto\JackpotInterno;

/**
 * Report/GetJackpotWinnersReport
 *
 * Obtiene el reporte de ganadores de jackpots según los filtros especificados
 *
 * @param array $params {
 *   "JackpotId": int,           // ID del jackpot
 *   "PlayerId": int,            // ID del jugador
 *   "DropDateFrom": string,     // Fecha inicial en formato Y-m-d
 *   "DropDateTo": string,       // Fecha final en formato Y-m-d
 *   "BalanceType": string,      // Tipo de balance
 *   "JackpotName": string,      // Nombre del jackpot
 *   "Vertical": string,         // Vertical del juego
 *   "CountrySelect": int,       // ID del país seleccionado
 *   "count": int,               // Cantidad de registros a retornar
 *   "start": int                // Registro inicial (paginación)
 * }
 *
 * @return array {
 *   "HasError": boolean,         // Indica si hubo error
 *   "AlertType": string,         // Tipo de alerta (success, error)
 *   "AlertMessage": string,      // Mensaje de alerta
 *   "ModelErrors": array,        // Errores del modelo
 *   "data": array {
 *     "JackpotId": int,         // ID del jackpot
 *     "JackpotName": string,    // Nombre del jackpot
 *     "PlayerId": int,          // ID del jugador
 *     "PlayerName": string,     // Nombre del jugador
 *     "Amount": float,          // Monto ganado
 *     "DropDate": string,       // Fecha de caída del premio
 *     "GameName": string,       // Nombre del juego
 *     "Vertical": string,       // Vertical del juego
 *     "Country": string         // País del jugador
 *   }[],
 *   "pos": int,                 // Posición actual
 *   "total_count": int          // Total de registros
 * }
 */


// Obtiene los parámetros de paginación y filtros básicos de la solicitud
$limit = ($_REQUEST["count"] != "") ? $_REQUEST["count"] : 1000;
$start = ($_REQUEST["start"] != "") ? $_REQUEST["start"] : 0;
$jackpotId = $_REQUEST['JackpotId'];
$playerId = $_REQUEST['PlayerId'];
$dateFrom = isset($_REQUEST['DropDateFrom']) ? $_REQUEST['DropDateFrom'] : null;
$dateTo = isset($_REQUEST['DropDateTo']) ? $_REQUEST['DropDateTo'] : null;
$balanceType = $_REQUEST['BalanceType'];
$jackpotName = $_REQUEST['JackpotName'];
$vertical = $_REQUEST['Vertical'];
$countrySelect = $_REQUEST['CountrySelect'];

// Verifica las condiciones de mandante según la sesión del usuario
$mandanteEspecifico='';
if ($_SESSION['Global'] == "N") {
    $mandanteEspecifico=$_SESSION['mandante'];
}else {
    if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
        $mandanteEspecifico = $_SESSION["mandanteLista"];
    }
}

// Inicializa y construye el array de filtros para la consulta
$filters = [];

if(!empty($jackpotId)) array_push($filters, ['field' => 'ji.jackpot_id', 'data' => $jackpotId, 'op' => 'eq']);
if(!empty($playerId)) array_push($filters, ['field' => 'uj.usuario_id', 'data' => $playerId, 'op' => 'eq']);

// Procesa y agrega filtros de fecha si están definidos
if (!empty($dateFrom) && !empty($dateTo)) {
    $dateFrom = date('Y-m-d 00:00:00', strtotime($dateFrom));
    $dateTo = date('Y-m-d 23:59:59', strtotime($dateTo));
    $filters[] = ['field' => 'bl.fecha_crea', 'data' => $dateFrom, 'op' => 'ge'];
    $filters[] = ['field' => 'bl.fecha_crea', 'data' => $dateTo, 'op' => 'le'];
}

// Agrega filtros de mandante y país según configuración
if($mandanteEspecifico != ''){
    $filters[] = ['field' => 'ji.mandante', 'data' => $mandanteEspecifico, 'op' => 'in'];
}

// Si el usuario esta condicionado por País.

if ($_SESSION['PaisCond'] == "S") {
    $filters[] = ['field' => 'u.pais_id', 'data' => $_SESSION['pais_id'], 'op' => 'eq'];
} else {
    if ($_SESSION["PaisCondS"] != '') {
        $filters[] = ['field' => 'u.pais_id', 'data' => $_SESSION['PaisCondS'], 'op' => 'eq'];
    }else{
        if($countrySelect != ''){
            $filters[] = ['field' => 'u.pais_id', 'data' => $countrySelect, 'op' => 'eq'];
        }
    }
}

// Agrega filtros adicionales de balance, nombre de jackpot y vertical
if(!empty($balanceType)) array_push($filters, ['field' => 'jd.valor', 'data' => $balanceType, 'op' => 'eq']);
if(!empty($jackpotName)) array_push($filters, ['field' => 'ji.nombre', 'data' => $jackpotName, 'op' => 'eq']);

// Procesa y agrega filtro de vertical si está definido
if(!empty($vertical) && $vertical != 0) {
    $vertical = match ($vertical) {
        '1' => 'INCOME_SPORTBOOK',
        '2' => 'INCOME_CASINO',
        '3' => 'INCOME_LIVECASINO',
        '4' => 'INCOME_VIRTUAL',
    };
    array_push($filters, ['field' => 'ujg.tipo', 'data' => $vertical, 'op' => 'eq']);
}

// Prepara los filtros finales y ejecuta la consulta
$filters = json_encode(['rules' => $filters, 'groupOp' => 'AND']);
$jackpot = new JackpotInterno();
$jackpotWinners = $jackpot->getJackpotWinners($start,$limit,$filters,$countrySelect);

// Procesa los resultados y construye el array de respuesta
$responseData = [];
foreach ($jackpotWinners['data'] as $jackpot) {
    $data = [];
    $data['JackpotId'] = $jackpot['ji.id_jackpot'];
    $data['JackpotName'] = $jackpot['ji.nombre'];
    $data['StartDate'] = $jackpot['ji.fecha_inicio'];
    $data['DropDate'] = $jackpot['bl.fecha_caida'];
    $data['BetNumber'] = $jackpot['.numero_apuesta'];
    $data['PrizeValue'] = $jackpot['uj.valor_premio'];
    $data['Currency'] = $jackpot['jd.moneda'];
    $data['BalanceType'] = $jackpot['.tipo_saldo'];
    $data['Verticals'] = $jackpot['.verticales'];
    $data['PlayerId'] = $jackpot['uj.usuario_id'];
    array_push($responseData, $data);
}

// Prepara la respuesta final con los datos procesados
$response = [];
$response['HasError'] = false;
$response['AlertType'] = 'success';
$response['AlertMessage'] = '';
$response['ModelErrors'] = [];
$response['total_count'] = $jackpotWinners['count'];
$response['pos'] = $start;
$response['data'] = $responseData;
