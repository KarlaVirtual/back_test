<?php

use Backend\dto\UsuarioMandante;
use Backend\dto\CategoriaMandante;

/**
 * Categories/GetCategories
 *
 * Este script devuelve las categorías de productos según los parámetros proporcionados.
 *
 * @param object $params Objeto que contiene los siguientes parámetros:
 * @param int $params ->country Identificador del país.
 * @param string $params ->partnerSelected Identificador del socio seleccionado.
 * @param boolean $params ->global Indica si se deben obtener categorías globales.
 *
 *
 * @return array Respuesta en formato JSON con los siguientes campos:
 *  - HasError (boolean): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta (e.g., 'success', 'danger').
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *  - data (array): Lista de categorías con sus propiedades específicas.
 *
 * @throws Exception Si ocurre un error durante la ejecución.
 */

/* Función que devuelve tipo de categoría según valor numérico o nombre. */
function getCategoriesType($value)
{
    $Types = ['0' => 'CASINO', '1' => 'LIVECASINO', '2' => 'VIRTUAL', '3' => 'MINIGAMES', '4' => 'PAYMENT', '5' => 'BINGO'];
    return is_numeric($value) ? $Types[$value] : array_search($value, $Types);
}

$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);


/* Asignación de valores a variables según condiciones de parámetros y sesión del usuario. */
$CountryId = $params->country;
$PartnerSelected = $params->partnerSelected;
$Global = $params->global ?: false;

$Partner = $PartnerSelected != '' ? $PartnerSelected : $_SESSION['mandante'];

if (empty($CountryId)) {
    $CountryId = $_SESSION['PaisCond'] === 'N' && !empty($_SESSION['PaisCondS']) ? $_SESSION['PaisCondS'] : $UsuarioMandante->paisId;
}


/* inicializa variables si la condición global es verdadera, definiendo reglas vacías. */
if ($Global) {
    $Partner = -1;
    $CountryId = 0;
}

$rules = [];


/* Se crean reglas de filtrado para una consulta, agrupadas en formato JSON. */
array_push($rules, ['field' => 'categoria_mandante.mandante', 'data' => $Partner, 'op' => 'eq']);
array_push($rules, ['field' => 'categoria_mandante.pais_id', 'data' => $CountryId, 'op' => 'eq']);
array_push($rules, ['field' => 'categoria_mandante.tipo', 'data' => 'LEGCASINO', 'op' => 'ne']);
array_push($rules, ['field' => 'categoria_mandante.estado', 'data' => 'A', 'op' => 'eq']);

$filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);


/* Se crea un objeto y se obtienen categorías en un rango específico desde la base de datos. */
$CategoriaMandante = new CategoriaMandante();
$Categories = $CategoriaMandante->getCategoriaMandanteCustom('*', 'categoria_mandante.orden', 'asc', 0, 10000, $filter, true);

$Categories = json_decode($Categories);

$AllCategories = [];


/* Recorre categorías y almacena datos en un arreglo para su posterior uso. */
foreach ($Categories->data as $key => $value) {
    $data = [];
    $data['Id'] = $value->{'categoria_mandante.catmandante_id'};
    $data['Description'] = $value->{'categoria_mandante.descripcion'} . ' (' . $value->{'categoria_mandante.tipo'} . ')';
    $data['Name'] = $value->{'categoria_mandante.descripcion'};
    $data['Image'] = $value->{'categoria_mandante.imagen'};
    $data['Type'] = getCategoriesType($value->{'categoria_mandante.tipo'});
    $data['IsActive'] = $value->{'categoria_mandante.estado'};
    $data['Slug'] = $value->{'categoria_mandante.slug'};

    array_push($AllCategories, $data);
}


/* Código que define una respuesta JSON sin errores y con datos de categorías. */
$response['HasError'] = false;
$response['AlertType'] = 'success';
$response['AlertMessage'] = '';
$response['ModelErrors'] = [];
$response['data'] = $AllCategories;
?>