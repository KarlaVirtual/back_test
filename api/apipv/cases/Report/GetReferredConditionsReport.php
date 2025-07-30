<?php
use Backend\dto\UsuarioMandante;
use Backend\dto\LogroReferido;

/**
 * Report/GetReferredConditionsReport
 * 
 * Obtiene el reporte de condiciones cumplidas por los referidos
 *
 * @param int $ReferentId           ID del usuario referente
 * @param int $ReferredId           ID del usuario referido
 * @param int $AwardId              ID del premio
 * @param int $ConditionType        Tipo de condición (1: Primer depósito, 2: Apuesta mínima, 3: Verificado)
 * @param string $RedemptionDateFrom Fecha inicial de redención (Y-m-d)
 * @param string $RedemptionDateTo   Fecha final de redención (Y-m-d)
 * @param int $start                Posición inicial para paginación
 * @param int $count                Cantidad de registros a retornar
 * 
 * @return array {
 *   "HasError": boolean,           // Indica si hubo error
 *   "AlertType": string,           // Tipo de alerta (success, error)
 *   "AlertMessage": string,        // Mensaje descriptivo
 *   "ModelErrors": array,          // Errores del modelo
 *   "total_count": int,            // Total de registros encontrados
 *   "pos": int,                    // Posición actual en la paginación
 *   "data": array {               // Lista de condiciones cumplidas
 *     "ReferentId": int,          // ID del referente
 *     "ReferentName": string,     // Nombre del referente
 *     "ReferredId": int,          // ID del referido
 *     "ReferredName": string,     // Nombre del referido
 *     "ConditionType": string,    // Tipo de condición cumplida
 *     "AwardId": int,             // ID del premio
 *     "AwardName": string,        // Nombre del premio
 *     "RedemptionDate": string,   // Fecha de redención
 *     "Status": string            // Estado de la condición
 *   }[]
 * }
 */


/** Parámetros recibidos */
$referentId = $_GET['ReferentId'];
$referredId = $_GET['ReferredId'];
$awardId = $_GET['AwardId'];
$conditionType = $_GET['ConditionType'];
$redemptionDateFrom = $_GET['RedemptionDateFrom'];
$redemptionDateTo = $_GET['RedemptionDateTo'];
$start = $_GET['start'] ?? 0;
$count = $_GET['count'] ?? 10;
$position = $start;

/** Configurando intervalos de horas */
if (!empty($redemptionDateFrom)) $redemptionDateFrom = date('Y-m-d 00:00:01', strtotime($redemptionDateFrom));
if (!empty($redemptionDateTo)) $redemptionDateTo = date('Y-m-d 23:59:59', strtotime($redemptionDateTo));

/** Convenciones en parámetros - Define los estados y tipos de condiciones posibles */
$statesConvention = [
    1 => 'P',
    2 => 'C',
    3 => 'R',
    4 => 'F',
    5 => 'CE',
    6 => 'PE'
];

$conditionsConvention = [
    1 => 'CONDMINFIRSTDEPOSITREFERRED',
    2 => 'CONDMINBETREFERRED',
    3 => 'CONDVERIFIEDREFERRED',
];

/** Solicitando información del operario y configurando país */
$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);
$country = $_SESSION['PaisCondS'] ?? $UsuarioMandante->getPaisId();

/** Construyendo reglas de filtrado para la consulta */
$rules = [];

if (!empty($country)) {
    array_push($rules, ['field' => 'usuario_mandante.pais_id', 'data' => $country, 'op' => 'eq']);
}

if (!empty($referentId)) {
    array_push($rules, ['field' => 'logro_referido.usuid_referente', 'data' => $referentId, 'op' => 'eq']);
}

/** Agregando filtros adicionales para referido, premio y tipo de condición */
if (!empty($referredId)) {
    array_push($rules, ['field' => 'logro_referido.usuid_referido', 'data' => $referredId, 'op' => 'eq']);
}

if (!empty($awardId)) {
    array_push($rules, ['field' => 'logro_referido.tipo_premio', 'data' => $awardId, 'op' => 'eq']);
}

if (!empty($conditionType)) {
    array_push($rules, ['field' => 'clasificador.abreviado', 'data' => $conditionsConvention[$conditionType], 'op' => 'eq']);
}

/** Agregando filtros de fechas para la consulta */
if (!empty($redemptionDateFrom)) {
    array_push($rules, ['field' => 'logro_referido.fecha_modif', 'data' => $redemptionDateFrom, 'op' => 'ge']);
}

if (!empty($redemptionDateTo)) {
    array_push($rules, ['field' => 'logro_referido.fecha_modif', 'data' => $redemptionDateTo, 'op' => 'le']);
}

/** Configurando y ejecutando la consulta principal */
$filters = ['rules' => $rules, 'groupOp' => 'AND'];
$select = 'logro_referido.logroreferido_id, logro_referido.usuid_referente, logro_referido.usuid_referido, logro_referido.tipo_premio, logro_referido.valor_premio, clasificador.abreviado, mandante_detalle_condicion.valor, logro_referido.valor_condicion, logro_referido.estado, logro_referido.estado_grupal, logro_referido.fecha_modif, logro_referido.fecha_crea, logro_referido.fecha_uso, logro_referido.fecha_expira, logro_referido.fecha_expira_premio';
$sidx = 'logro_referido.usuid_referido DESC, logro_referido.tipo_premio';

$LogroReferido = new LogroReferido();
$returnedConditions = $LogroReferido->getLogroReferidoCustom($select, $sidx, 'DESC', $start, $count, json_encode($filters), true);
$returnedConditionsData = json_decode($returnedConditions)->data;
$returnedConditionsCount = json_decode($returnedConditions)->count;

/** Procesando resultados y construyendo array de condiciones */
$conditions = [];
foreach ($returnedConditionsData as $returnedCondition) {
    $condition = (object) [];
    $condition->ReferentId = $returnedCondition->{'logro_referido.usuid_referente'};
    $condition->ReferredId = $returnedCondition->{'logro_referido.usuid_referido'};
    $condition->AwardId = $returnedCondition->{'logro_referido.tipo_premio'};
    $condition->ChoicedBonus = $returnedCondition->{'logro_referido.valor_premio'} ?? '-';

    /** Mapeando tipo de condición y estados usando las convenciones definidas */
    $condition->ConditionType = array_filter($conditionsConvention, function ($convention) use($returnedCondition) {
        if ($convention == $returnedCondition->{'clasificador.abreviado'}) return $convention;
    });
    $condition->ConditionType = array_keys($condition->ConditionType)[0];
    $condition->TargetValue = $returnedCondition->{'mandante_detalle_condicion.valor'};
    $condition->CurrentValue = $returnedCondition->{'logro_referido.valor_condicion'};
    
    /** Procesando estados individuales y globales */
    $condition->State = array_filter($statesConvention, function ($convention) use($returnedCondition) {
        if ($convention == $returnedCondition->{'logro_referido.estado'}) return $convention;
    });
    $condition->State = array_keys($condition->State)[0];
    $condition->GlobalState = array_filter($statesConvention, function ($convention) use($returnedCondition) {
        if ($convention == $returnedCondition->{'logro_referido.estado_grupal'}) return $convention;
    });
    $condition->GlobalState = array_keys($condition->GlobalState)[0];

    /** Asignando fechas relevantes */
    $condition->RedemptionDate = $returnedCondition->{'logro_referido.fecha_modif'};
    $condition->FulfillmentDate = $returnedCondition->{'logro_referido.fecha_uso'};
    $condition->CondExpirationDate = $returnedCondition->{'logro_referido.fecha_expira'} ?? '-1';
    $condition->AwardExpirationDate = $returnedCondition->{'logro_referido.fecha_expira_premio'} ?? '-1';
    $condition->CreationDate = $returnedCondition->{'logro_referido.fecha_crea'};

    array_push($conditions, $condition);
}

/** Preparando respuesta final */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response['total_count'] = $returnedConditionsCount[0]->{'.count'};
$response['pos'] = $position;
$response['data'] = $conditions;
?>
