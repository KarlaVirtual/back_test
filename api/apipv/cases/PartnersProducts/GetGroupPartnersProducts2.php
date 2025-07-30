<?php

use Backend\dto\ProductoMandante;

/**
 * Obtiene los productos de un socio agrupados por diferentes criterios.
 *
 * @param array $params Parámetros de entrada obtenidos a través de $_GET:
 * @param int $params['ProductId'] (int|null) ID del producto.
 * @param int $params['ProductPartnerId'] (int|null) ID del producto asociado al socio.
 * @param string $params['Name'] (string|null) Nombre del producto.
 * @param int $params['ProviderId'] (int|null) ID del proveedor.
 * @param int $params['SubProviderId'] (int|null) ID del subproveedor.
 * @param int $params['CountrySelect'] (int|null) ID del país seleccionado.
 * @param string $params['Partner'] (string|null) Identificador del socio.
 * @param string $params['State'] (string|null) Estado del producto.
 * @param int $params['start'] (int|null) Número de filas a omitir para la paginación.
 * @param int $params['count'] (int|null) Número máximo de filas a devolver.
 * 
 *
 * @return array $response Respuesta en formato JSON:
 *  - HasError (bool): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta ('success' o 'error').
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *  - Data (array): Lista de productos obtenidos.
 *  - total_count (int): Número total de productos.
 */

/* obtiene parámetros de entrada a través de la URL utilizando $_GET. */
$ProductId = $_GET['ProductId'];
$ProductPartnerId = $_GET['ProductPartnerId'];
$Name = $_GET['Name'];
$ProviderId = $_GET['ProviderId'];
$SubProviderId = $_GET['SubProviderId'];
$CountrySelect = $_GET['CountrySelect'];
$Partner = $_GET['Partner'];
$State = $_GET['State'];

$SkeepRows = $_GET['start'] ?: 0;
$MaxRows = $_GET['count'] ?: 5;

if (!empty($CountrySelect) && $Partner !== '') {
    /*Definición filtrado para la obtención de los grupos de productos*/
    $ProductoMandante = new ProductoMandante();
    $rules = [];

    array_push($rules, ['field' => 'proveedor.tipo', 'data' => '"CASINO","LIVECASINO","POKER","VIRTUAL"', 'op' => 'in']);
    array_push($rules, ['field' => 'producto_mandante.mandante', 'data' => $Partner, 'op' => 'eq']);
    array_push($rules, ['field' => 'producto_mandante.pais_id', 'data' => $CountrySelect, 'op' => 'eq']);
    array_push($rules, ['field' => 'producto_mandante.habilitacion', 'data' => 'A', 'op' => 'eq']);
    if (!empty($ProductId)) array_push($rules, ['field' => 'producto_mandante.producto_id', 'data' => $ProductId, 'op' => 'eq']);
    if (!empty($ProductPartnerId)) array_push($rules, ['field' => 'producto_mandante.prodmandante_id', 'data' => $ProductPartnerId, 'op' => 'eq']);
    if (!empty($Name)) array_push($rules, ['field' => 'producto.descripcion', 'data' => $Name, 'op' => 'cn']);
    if (!empty($ProviderId)) array_push($rules, ['field' => 'producto.proveedor_id', 'data' => $ProviderId, 'op' => 'eq']);
    if (!empty($SubProviderId)) array_push($rules, ['field' => 'producto.subproveedor_id', 'data' => $SubProviderId, 'op' => 'eq']);
    if (!empty($State)) array_push($rules, ['field' => 'producto_mandante.estado', 'data' => $State, 'op' => 'eq']);

    $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

    /*Obtención de productos*/
    $data = (string)$ProductoMandante->getProductosMandanteCustom('producto.producto_id, producto.descripcion, producto.image_url, proveedor.descripcion, subproveedor.descripcion, producto_mandante.estado, producto_mandante.prodmandante_id, producto_mandante.codigo_minsetur,etiqueta_producto.etiqueta_id,etiqueta_producto.image,etiqueta_producto.text', 'producto.producto_id', 'ASC', $SkeepRows, $MaxRows, $filter, true);
    $data = json_decode($data, true);

    $products = [];

    foreach ($data['data'] as $key => $value) {
        /*Creación de objetos de respuesta*/
        $array = [];
        $array['ProductId'] = $value['producto.producto_id'];
        $array['ProductPartnerId'] = $value['producto_mandante.prodmandante_id'];
        $array['Name'] = $value['producto.descripcion'];
        $array['Provider'] = $value['proveedor.descripcion'];
        $array['Subprovider'] = $value['subproveedor.descripcion'];
        $array['State'] = $value['producto_mandante.estado'];
        $array['Image'] = $value['producto.image_url'];
        $array['Code'] = $value['producto_mandante.codigo_minsetur'];
        $array['TagImage'] = $value['etiqueta_producto.image'] ?? '';
        $array['TagText'] = $value['etiqueta_producto.text'] ?? '';

        array_push($products, $array);
    }

    /*Formato de respuesta*/
    $response['HasError'] = false;
    $response['AlertType'] = 'success';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];
    $response['Data'] = $products;
    $response['total_count'] = $data['count'][0]['.count'];
} else {
    /*Formato de error*/
    $response['HasError'] = true;
    $response['AlertType'] = 'error';
    $response['AlertMessage'] = 'Debe de mandar el partner y el pais como parametro';
    $response['ModelErrors'] = [];
    $response['Data'] = [];
}
?>