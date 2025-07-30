<?php

use Backend\dto\UsuarioSorteo;

/**
     * Obtiene los premios ganados en un sorteo y genera una respuesta JSON.
 *
 * @param object $json Objeto JSON que contiene los parámetros de la solicitud.
 * @param int $json->params->lottery_id Identificador de la lotería.
 * @param array $json->params->prices_ids Identificadores de los premios.
 * @param string $json->rid Identificador de la solicitud.
 * @return array Respuesta estructurada con los premios ganados.
 *  -code:int Código de respuesta.
 *  -msg:string Mensaje de respuesta.
 *  -rid:string Identificador de respuesta.
 *  -data:array Arreglo de premios ganados.
 */

/* La función devuelve el tipo correspondiente según la clave proporcionada. */
/**
 * Devuelve el tipo correspondiente según la clave proporcionada.
 *
 * @param string $type Clave del tipo de premio.
 * @return string Tipo de premio correspondiente.
 */
function getTypeWards($type)
{
    $types = ['RANKAWARDMAT' => 'Fisico', 'BONO' => 'Bono', 'RANKAWARD' => 'Efectivo'];
    return isset($types[$type]) ? $types[$type] : '';
}
$params = $json->params;

/* Asigna valores de parámetros a variables y asegura que prices_ids sea un array. */
$lottery_id = $params->lottery_id;
$prices_ids = $params->prices_ids;

$prices_ids = $prices_ids ?: [];

$allData = [];


/* Genera reglas de filtro y realiza consultas sobre `usuario_sorteo` en un bucle. */
foreach ($prices_ids as $value) {
    $rules = [];

    array_push($rules, ['field' => 'usuario_sorteo.sorteo_id', 'data' => $lottery_id, 'op' => 'eq']);
    array_push($rules, ['field' => 'usuario_sorteo.premio', 'data' => '', 'op' => 'ne']);
    array_push($rules, ['field' => 'usuario_sorteo.premio_id', 'data' => $value, 'op' => 'eq']);
    array_push($rules, ['field' => 'usuario_sorteo.estado', 'data' => 'R', 'op' => 'eq']);

    $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

    $UsuarioSorteo = new UsuarioSorteo();

    $query = (string)$UsuarioSorteo->getUsuarioSorteosCustom('usuario_sorteo.*, usuario_mandante.nombres, usuario_mandante.usuario_mandante', 'usuario_sorteo.ususorteo_id', 'asc', 0, 100, $filter, true);

    $query = json_decode($query, true);

    $allData = array_merge($query->data, $allData);
}


/* procesa datos de sorteos y almacena premios ganados en un arreglo. */
$digits = '0000000';

$winningPrizes = [];

foreach ($query['data'] as $key => $value) {
    $winData = !empty($value['usuario_sorteo.premio']) ? json_decode($value['usuario_sorteo.premio'], true) : '';

    $data = [];
    $data['coupon_id'] = $value['usuario_sorteo.ususorteo_id'];
    $data['code'] = substr($digits . $value['usuario_sorteo.ususorteo_id'], -7);
    $data['win_type'] = getTypeWards($winData['type']);
    $data['image'] = $winData['imagen'];
    $data['position'] = $winData['position'];
    $data['description'] = $winData['description'];
    $data['value'] = $winData['value'];
    $data['user_win'] = $value['usuario_mandante.usuario_mandante'] . '** ' . $value['usuario_mandante.nombres'];

    array_push($winningPrizes, $data);
}


/* Crea una respuesta estructurada dependiendo de la existencia de premios ganadores. */
$response = [];
$response[oldCount($winningPrizes) > 0 ? 'code' : 'error_code'] = oldCount($winningPrizes) > 0 ? 0 : 9000;
$response['msg'] = oldCount($winningPrizes) > 0 ? 'Success' : 'No existen participantes para este sorteo';
$response['rid'] = $json->rid;
$response['data'] = $winningPrizes;
?>