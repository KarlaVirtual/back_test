<?php

use Backend\dto\Moneda;


/* Crea un objeto "Moneda" y define reglas de filtrado en formato JSON. */
$Moneda = new Moneda();

$rules = [];
array_push($rules, ['field' => 'moneda.estado', 'data' => 'A', 'op' => 'eq']);

$filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);


/* obtiene monedas y las organiza en un array de claves y valores. */
$currencies = $Moneda->getMonedasCustom('moneda.moneda, moneda.descripcion', 'ASC', 0, 1000, $filter, true);
$currencies = json_decode($currencies, true);

$data = [];

foreach ($currencies['data'] as $value) {
    $array = [];
    $array['key'] = $value['moneda.moneda'];
    $array['value'] = $value['moneda.descripcion'];

    array_push($data, $array);
}


/* Código establece respuesta sin errores, con éxito y datos incluidos. */
$response['HasError'] = false;
$response['AlertType'] = 'success';
$response['AlertMessage'] = '';
$response['ModelErrors'] = [];
$response['Data'] = $data;
?>