<?php

use Backend\dto\CategoriaMandante;

/**
 * Obtiene una lista de categorías de socios según los filtros proporcionados.
 *
 * Este script procesa una solicitud para recuperar categorías de socios, 
 * aplicando filtros y devolviendo los resultados en un formato estructurado.
 *
 * @param array $_REQUEST Parámetros de solicitud HTTP. Contiene:
 * @param int $_REQUEST->start Índice inicial para la paginación.
 * @param int $_REQUEST->count Número de registros a recuperar.
 * @param string $_REQUEST->Description Descripción de la categoría.
 * @param int $_REQUEST->Type Tipo de categoría (índice numérico o cadena).
 * @param int $_REQUEST->Partner Identificador del socio.
 * @param string $_REQUEST->Slug Identificador único de la categoría.
 * @param int $_REQUEST->State Estado de la categoría (0: Activo, 1: Inactivo).
 * @param int $_REQUEST->Country Identificador del país.
 * 
 *
 * @return array $response Respuesta en formato JSON con los siguientes valores:
 *                         - HasError (bool): Indica si ocurrió un error.
 *                         - AlertType (string): Tipo de alerta (success o error).
 *                         - AlertMessage (string): Mensaje de alerta.
 *                         - ModelErrors (array): Lista de errores del modelo.
 *                         - data (array): Lista de categorías recuperadas.
 */
function getCategoriesType($value)
{
    $Types = ['0' => 'CASINO', '1' => 'LIVECASINO', '2' => 'VIRTUAL', '3' => 'MINIGAMES', '4' => 'PAYMENT', '5' => 'BINGO'];
    return is_numeric($value) ? $Types[$value] : array_search($value, $Types);
}

$Start = $_REQUEST['start'] ?: 0;

/* Asigna valores de parámetros de solicitud, con valor predeterminado para 'count'. */
$Count = $_REQUEST['count'] ?: 100;
$Description = $_REQUEST['Description'];
$Type = $_REQUEST['Type'];
$Partner = $_REQUEST['Partner'];
$Slug = $_REQUEST['Slug'];
$State = $_REQUEST['State'];

/* Asignación de valores a variables según condiciones de solicitud y sesión en PHP. */
$Country = $_REQUEST['Country'];

if ($Partner == '' && $_SESSION['mandante'] == -1) {
    $Partner = -1;
    $Country = 0;
}

$rules = [];

if (!empty($Description)) array_push($rules, ['field' => 'descripcion', 'data' => $Description, 'op' => 'cn']);
if ($Type != '') array_push($rules, ['field' => 'tipo', 'data' => getCategoriesType($Type), 'op' => 'eq']);
if (!empty($Slug)) array_push($rules, ['field' => 'slug', 'data' => $Slug, 'op' => 'eq']);
if ($State != '') array_push($rules, ['field' => 'estado', 'data' => $State == 0 ? 'A' : 'I', 'op' => 'eq']);


/* Se crean reglas de filtrado y se codifican en JSON para un objeto. */
array_push($rules, ['field' => 'tipo', 'data' => 'LEGCASINO', 'op' => 'ne']);
array_push($rules, ['field' => 'pais_id', 'data' => $Country, 'op' => 'eq']);
array_push($rules, ['field' => 'mandante', 'data' => $Partner, 'op' => 'eq']);

$filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

$CategoriaMandante = new CategoriaMandante();

/* obtiene categorías, las procesa y las organiza en un arreglo estructurado. */
$categories = $CategoriaMandante->getCategoriaMandanteCustom('*', 'orden', 'asc', $Start, $Count, $filter, true);

$categories = json_decode($categories);
$parnet_categories = [];

foreach ($categories->data as $key => $value) {
    $data = [];
    $data['Id'] = $value->{'categoria_mandante.catmandante_id'};
    $data['Slug'] = $value->{'categoria_mandante.slug'};
    $data['Description'] = $value->{'categoria_mandante.descripcion'};
    $data['Icon'] = $value->{'categoria_mandante.imagen'};
    $data['Order'] = $value->{'categoria_mandante.orden'};
    $data['State'] = $value->{'categoria_mandante.estado'} === 'A' ? 0 : 1;
    $data['Type'] = getCategoriesType($value->{'categoria_mandante.tipo'});
    $data['Country'] = $value->{'categoria_mandante.pais_id'};

    array_push($parnet_categories, $data);
}


/* Crea una respuesta estructurada con error, mensaje de éxito y datos de categorías. */
$response['HasError'] = false;
$response['AlertType'] = 'success';
$response['AlertMessage'] = '';
$response['ModelErrors'] = [];
$response['data'] = $parnet_categories;
?>