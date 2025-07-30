<?php

use Backend\dto\CategoriaProducto;
use Backend\dto\Producto;

/**
 * Obtiene productos y categorías asociadas a un socio.
 *
 * Este script procesa una solicitud para recuperar productos y categorías asociadas a un socio, 
 * aplicando filtros y devolviendo los resultados en un formato estructurado.
 *
 * @param array $_REQUEST Parámetros de solicitud HTTP. Contiene:
 * @param int $_REQUEST->Categorie Identificador de la categoría.
 * @param string $_REQUEST->Name Nombre del producto.
 * @param int $_REQUEST->PartnerReference Referencia del socio.
 * @param int $_REQUEST->CountrySelect Identificador del país seleccionado.
 * @param int $_REQUEST->ProviderId Identificador del proveedor.
 * @param int $_REQUEST->SubProviderId Identificador del subproveedor.
 * @param int $_REQUEST->Partner Identificador del socio.
 * @param int $_REQUEST->Type Tipo de categoría.
 * @param string $_REQUEST->IsActivate Estado del producto (A: Activo, I: Inactivo).
 * @param int $_REQUEST->start Índice inicial para la paginación.
 * @param int $_REQUEST->count Número máximo de registros a recuperar.
 * 
 * 
 *
 * @return array $response Respuesta en formato JSON con los siguientes valores:
 *                         - HasError (bool): Indica si ocurrió un error.
 *                         - AlertType (string): Tipo de alerta (success o error).
 *                         - AlertMessage (string): Mensaje de alerta.
 *                         - ModelErrors (array): Lista de errores del modelo.
 *                         - Data (array): Datos estructurados de productos y categorías.
 */


/* recopila datos de una solicitud HTTP y asigna valores a variables. */
$Categorie = $_REQUEST['Categorie'];
$Name = $_REQUEST['Name'];
$PartnerReference = '';
$PartnerReference = $_REQUEST['PartnerReference'];
$CountrySelect = $_REQUEST['CountrySelect'] ?: $_REQUEST['CountrySelect2'];
$ProviderId = is_numeric($_REQUEST['ProviderId']) ? $_REQUEST['ProviderId'] : '';

/* Recopila y valida datos de solicitud, asignando valores predeterminados cuando es necesario. */
$SubProviderId = is_numeric($_REQUEST['SubProviderId']) ? $_REQUEST['SubProviderId'] : '';
$Partner = $_REQUEST['Partner'] ?: $_SESSION['mandante'];
$Type = $_REQUEST['Type'] ?: 0;
$IsActivate = $_REQUEST['IsActivate'] === 'A' || $_REQUEST['IsActivate'] === 'I' ? $_REQUEST['IsActivate'] : '';

$start = $_REQUEST['start'] ?: 0;

/* establece un valor por defecto y define una función para categorizar tipos. */
$count = $_REQUEST['count'] ?: 100000;

/**
 * Obtiene el tipo de categoría basado en el valor proporcionado.
 *
 * @param mixed $value El valor que puede ser numérico o una cadena.
 * @return mixed El tipo de categoría correspondiente al valor.
 */
function getCategoriesType($value)
{
    $Types = ['0' => 'CASINO', '1' => 'LIVECASINO', '2' => 'VIRTUAL', '3' => 'MINIGAMES', '4' => 'PAYMENT', '5' => 'BINGO'];
    return is_numeric($value) ? $Types[$value] : array_search($value, $Types);
}


if (!empty($Categorie)) {

    /*Definición filtrado de categorías*/
    $rules = [];
    if (!empty($ProviderId)) array_push($rules, ['field' => 'producto.proveedor_id', 'data' => $ProviderId, 'op' => 'eq']);
    if (!empty($Name)) array_push($rules, ['field' => 'producto.descripcion', 'data' => $Name, 'op' => 'cn']);
    if (!empty($SubProviderId)) array_push($rules, ['field' => 'producto.subproveedor_id', 'data' => $SubProviderId, 'op' => 'eq']);
    array_push($rules, ['field' => 'producto_mandante.habilitacion', 'data' => "A", 'op' => 'eq']);
    array_push($rules, ['field' => 'categoria_mandante.catmandante_id', 'data' => $Categorie, 'op' => 'eq']);
    array_push($rules, ['field' => 'categoria_mandante.tipo', 'data' => getCategoriesType($Type), 'op' => 'eq']);
    array_push($rules, ['field' => 'categoria_producto.mandante', 'data' => $Partner, 'op' => 'eq']);
    array_push($rules, ['field' => 'categoria_producto.pais_id', 'data' => $CountrySelect, 'op' => 'eq']);
    array_push($rules, ['field' => 'categoria_producto.estado', 'data' => 'A', 'op' => 'eq']);


    $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

    /*Obtención de categorías*/
    $CategoriaProducto = new CategoriaProducto();
    $products_categories = $CategoriaProducto->getCategoriaProductosMandanteCustom('categoria_producto.*', 'producto.descripcion', 'asc', $start, $count, $filter, true);

    $products_categories = json_decode($products_categories);


    $includeProducts = '';

    foreach ($products_categories->data as $key => $value) {
        $includeProducts .= $value->{'categoria_producto.producto_id'} . ',';
    }


    if ($partnerReference == '' || $partnerReference == '-1') {
        $rules = [];

        if (!empty($Name)) array_push($rules, ['field' => 'producto.descripcion', 'data' => $Name, 'op' => 'cn']);
        if (!empty($ProviderId)) array_push($rules, ['field' => 'producto.proveedor_id', 'data' => $ProviderId, 'op' => 'eq']);
        if (!empty($SubProviderId)) array_push($rules, ['field' => 'subproveedor.subproveedor_id', 'data' => $SubProviderId, 'op' => 'eq']);
        if (!empty($IsActivate)) array_push($rules, ['field' => 'producto.estado', 'data' => $IsActivate, 'op' => 'eq']);
        array_push($rules, ['field' => 'subproveedor.estado', 'data' => 'A', 'op' => 'eq']);
        if (!in_array(getCategoriesType($Type), array('BINGO'))) {
            array_push($rules, ['field' => 'subproveedor.tipo', 'data' => in_array(getCategoriesType($Type), array('MINIGAMES')) ? 'CASINO' : GetCategoriesType($Type), 'op' => 'eq']);
        }

        $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

        /*Obtención de productoMandante*/
        $Producto = new Producto();
        $producst = $Producto->getProductosCustomMandante('producto.*, proveedor.*, subproveedor.descripcion', 'producto.descripcion', 'asc', $start, $count, $filter, true, $Partner);

        $producst = json_decode($producst);

        $exclude_products = [];

        foreach ($producst->data as $key => $value) {
            $data = [];
            $data['id'] = $value->{'producto.producto_id'};
            $data['value'] = $value->{'subproveedor.descripcion'} . ' - ' . $value->{'producto.descripcion'} . ' (' . $value->{'producto.producto_id'} . ')';

            array_push($exclude_products, $data);
        }
    } else {
        $rules = [];

        if (!empty($PartnerReference)) array_push($rules, ['field' => 'categoria_producto.mandante', 'data' => $PartnerReference, 'op' => 'eq']);
        if (!empty($Name)) array_push($rules, ['field' => 'producto.descripcion', 'data' => $Name, 'op' => 'cn']);
        if (!empty($ProviderId)) array_push($rules, ['field' => 'producto.proveedor_id', 'data' => $ProviderId, 'op' => 'eq']);
        if (!empty($SubProviderId)) array_push($rules, ['field' => 'subproveedor.subproveedor_id', 'data' => $SubProviderId, 'op' => 'eq']);
        array_push($rules, ['field' => 'producto_mandante.habilitacion', 'data' => "A", 'op' => 'eq']);
        array_push($rules, ['field' => 'categoria_mandante.tipo', 'data' => getCategoriesType($Type), 'op' => 'eq']);



        $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

        /*Obtención categoría de los productos mandante*/
        $CategoriaProducto = new CategoriaProducto();
        $products_categories = $CategoriaProducto->getCategoriaProductosMandanteCustom('categoria_producto.*', 'producto.descripcion', 'asc', $start, 100000, $filter, true);

        $products_categories = json_decode($products_categories);

        $exclude_products = [];

        foreach ($products_categories->data as $key => $value) {
            $data = [];
            $data['id'] = $value->{'producto.producto_id'};
            $data['value'] = $value->{'subproveedor.descripcion'} . ' - ' . $value->{'producto.descripcion'} . ' (' . $value->{'producto.producto_id'} . ')';

            array_push($exclude_products, $data);
        }

    }
}


/* Genera el formato de respuestas */
$response['HasError'] = false;
$response['AlertType'] = 'success';
$response['AlertMessage'] = '';
$response['ModelErrors'] = [];
$response['Data'] = [
    'ExcludedCategoriesList' => $exclude_products ?: [],
    'IncludedCategoriesList' => trim($includeProducts, ',') ?: ''
];

?>