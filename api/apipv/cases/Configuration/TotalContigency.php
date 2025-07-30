<?php

use Backend\dto\Clasificador;
use Backend\dto\MandanteDetalle;


/**
 * Este script procesa información relacionada con contingencias y clasificadores.
 * Utiliza datos de mandantes y clasificadores para generar un arreglo de contingencias
 * clasificadas como activas ('A') o inactivas ('I').
 *
 * @param array $params No utilizado
 * @return array $response Arreglo que contiene las contingencias procesadas, donde cada clave
 *                         es el nombre de la contingencia y su valor es 'A' (activa) o 'I' (inactiva).
 * @throws Exception Si ocurre un error durante la obtención o procesamiento de datos.
 */

/**
 * Obtiene la contingencia correspondiente a un valor dado.
 *
 * @param string $value El valor para el cual se desea obtener la contingencia.
 * @return string La contingencia correspondiente al valor dado, o una cadena vacía si no se encuentra.
 */
function getContingency($value)
{
    $data = [
        'TOTALCONTINGENCE' => 'IsActivateContingency',
        'TOTALCONTINGENCESPORT' => 'IsActivateContingencyDeportivas',
        'TOTALCONTINGENCEWITHDRAWAL' => 'IsActivateContingencyWithdrawal',
        'TOTALCONTINGENCEDEPOSIT' => 'IsActivateContingencyDeposit',
        'TOTALCONTINGENCECASINO' => 'IsActivateContingencyCasino',
        'TOTALCONTINGENCECASINOLIVE' => 'IsActivateContingencyCasinoVivo',
        'TOTALCONTINGENCEVIRTUAL' => 'IsActivateContingencyVirtuales',
        'TOTALCONTINGENCEPOKER' => 'IsActivateContingencyPoker'
    ];

    /* Se asigna un valor vacío si no se encuentra en el arreglo $data. */
    return $data[$value] ?: '';
}
$abbreviated = '"TOTALCONTINGENCE", "TOTALCONTINGENCESPORT", "TOTALCONTINGENCEWITHDRAWAL", "TOTALCONTINGENCEDEPOSIT", "TOTALCONTINGENCECASINO", "TOTALCONTINGENCECASINOLIVE", "TOTALCONTINGENCEPOKER", "TOTALCONTINGENCEVIRTUAL"';

$rules = [];


/* Se generan reglas de filtros y se codifican en formato JSON. */
array_push($rules, ['field' => 'mandante_detalle.pais_id', 'data' => 0, 'op' => 'eq']);
array_push($rules, ['field' => 'mandante_detalle.mandante', 'data' => -1, 'op' => 'eq']);
array_push($rules, ['field' => 'mandante_detalle.estado', 'data' => 'A', 'op' => 'eq']);
array_push($rules, ['field' => 'clasificador.estado', 'data' => 'A', 'op' => 'eq']);
array_push($rules, ['field' => 'clasificador.abreviado', 'data' => $abbreviated, 'op' => 'in']);

$filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);


/* Se obtiene y procesa información detallada sobre mandantes en formato JSON. */
$MandateDetalle = new MandanteDetalle();

$query = $MandateDetalle->getMandanteDetallesCustom2('clasificador.abreviado, mandante_detalle.valor', 'mandante_detalle.manddetalle_id', 'ASC', 0, count(explode(', ', $abbreviated)), $filters, true);
$query = json_decode($query, true);

$abbreviated = explode(', ', str_replace('"', '', $abbreviated));


/* busca contingencias en datos y las clasifica como 'A' o 'I'. */
$index = array_search('TOTALCONTINGENCESPORT', $abbreviated);

$contingencies = [];

foreach ($query['data'] as $key => $value) {
    $contingency = getContingency($value['clasificador.abreviado']);
    if (!empty($contingency)) {
        $contingencies[$contingency] = $value['mandante_detalle.valor'] == 1 ? 'A' : 'I';
        unset($abbreviated[array_search($value['clasificador.abreviado'], $abbreviated)]);
    }
}


/* Se verifica y procesa información sobre clasificadores usando filtros en un arreglo. */
if (oldCount($abbreviated) > 0) {
    $rules = [];

    array_push($rules, ['field' => 'clasificador.abreviado', 'data' => '"' . implode('", "', $abbreviated) . '"', 'op' => 'in']);
    array_push($rules, ['field' => 'clasificador.estado', 'data' => 'A', 'op' => 'eq']);

    $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

    $Clasificador = new Clasificador();

    $subQuery = $Clasificador->getClasificadoresCustom('clasificador.abreviado', 'clasificador.clasificador_id', 'ASC', 0, oldCount($abbreviated), $filters, true);
    $subQuery = json_decode($subQuery, true);

    foreach ($subQuery['data'] as $key => $value) {
        $contingency = getContingency($value['clasificador.abreviado']);
        if (!empty($contingency)) $contingencies[$contingency] = 'I';
    }
}


/* Se crea un arreglo y se añade el elemento $contingencies a él. */
$response = [];
array_push($response, $contingencies);
?>