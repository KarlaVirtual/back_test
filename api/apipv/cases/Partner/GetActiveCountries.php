<?php

use Backend\dto\Pais;
use Backend\dto\PaisMandante;


/**
 * Obtiene la lista de países activos y su moneda asociada.
 *
 * @param string $Partner Nombre del socio para filtrar países asociados (opcional).
 *
 * @return array $response Respuesta con los siguientes atributos:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (success, danger, etc.).
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo.
 * - Data (array): Lista de países con los siguientes atributos:
 *   - id (int): ID del país.
 *   - value (string): Nombre del país.
 *   - currency (string|null): Moneda asociada al país (si aplica).
 *   - iso (string): Código ISO del país en minúsculas.
 *
 * @throws Exception Si ocurre un error al obtener los datos de los países.
 */
/* filtra datos basándose en la condición del estado 'A'. */
$Partner = $_GET['Partner'];

$rules = [];

array_push($rules, ['field' => 'pais.estado', 'data' => 'A', 'op' => 'eq']);

$filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);


/* Se instancia un objeto de país y se obtienen países filtrados y ordenados. */
$Pais = new Pais();
$PaisMandante = new PaisMandante();
$countries = $Pais->getPaisesCustom2('pais.pais_nom', 'ASC', 0, 1000, $filter, true);
$countries = json_decode($countries, true);

$partnerCountries = [];


/* Crea un filtro para recuperar países asociados a un socio específico. */
if ($Partner !== '') {
    $rules = [];

    array_push($rules, ['field' => 'pais_mandante.mandante', 'data' => $Partner, 'op' => 'eq']);

    $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

    $PaisMandante = new PaisMandante();
    $partnerCountries = $PaisMandante->getPaisMandantesCustom2('pais.pais_id, pais_mandante.moneda', 'pais.pais_id', 'ASC', 0, 1000, $filter, true);
    $partnerCountries = json_decode($partnerCountries, true);
}


/* genera un array con información de países y su moneda asociada. */
$data = [];

foreach ($countries['data'] as $value) {
    $index = array_search($value['pais.pais_id'], array_column($partnerCountries['data'], 'pais.pais_id'));

    $array = [];
    $array['id'] = $value['pais.pais_id'];
    $array['value'] = $value['pais.pais_nom'];
    $array['currency'] = $index !== false ? $partnerCountries['data'][$index]['pais_mandante.moneda'] : null;
    $array['iso'] = strtolower($value['pais.iso']);

    array_push($data, $array);
}


/* Código PHP que estructura una respuesta exitosa sin errores ni alertas. */
$response['HasError'] = false;
$response['AlertType'] = 'success';
$response['AlertMessage'] = '';
$response['ModelErrors'] = [];
$response['Data'] = $data;
?>