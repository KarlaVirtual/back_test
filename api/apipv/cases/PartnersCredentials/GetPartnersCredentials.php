<?php

use Backend\dto\SubproveedorMandantePais;

/**
 * Obtener credenciales de subproveedores.
 *
 * Este script permite obtener las credenciales de subproveedores con filtros personalizados.
 *
 * @param array $_REQUEST Arreglo que contiene los siguientes parámetros:
 * @param int $_REQUEST["count"] Número máximo de filas a obtener.
 * @param int $_REQUEST["start"] Número de filas a omitir.
 * @param string $_REQUEST["Partner"] Identificador del mandante.
 * @param int $_REQUEST["ProviderId"] ID del proveedor.
 * @param int $_REQUEST["SubProviderId"] ID del subproveedor.
 * @param string $_REQUEST["CountrySelect"] País seleccionado.
 * @param string $_REQUEST["Type"] Tipo de subproveedor.
 * @param string $_REQUEST["IsActivate"] Estado de activación ('A' para activo, 'I' para inactivo).
 * 
 * 
 *
 * @return array $response Respuesta con los siguientes índices:
 *  - HasError (bool): Indica si hubo un error.
 *  - AlertType (string): Tipo de alerta ('success', 'error', etc.).
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *  - data (array): Lista de subproveedores obtenidos.
 *
 * @throws Exception Si ocurre un error durante la ejecución.
 */

/* obtiene parámetros de solicitud para paginación y asigna valores predeterminados. */
$MaxRows = $_REQUEST["count"];
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];
$partner = isset($_REQUEST["Partner"]) ? $_REQUEST["Partner"] : '-1';

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* establece un valor predeterminado y obtiene parámetros de la solicitud. */
if ($MaxRows == "") {
    $MaxRows = 10000;
}

$providerId = $_REQUEST['ProviderId'];
$subProviderId = $_REQUEST['SubProviderId'];
$country = $_REQUEST['CountrySelect'];
$type = $_REQUEST['Type'];
$isActivate = $_REQUEST['IsActivate'];

/*Generación filtros de consulta*/
$rules = [];

if ($providerId != '') array_push($rules, ['field' => 'proveedor.proveedor_id', 'data' => $providerId, 'op' => 'eq']);

if ($subProviderId != '') array_push($rules, ['field' => 'subproveedor.subproveedor_id', 'data' => $subProviderId, 'op' => 'eq']);

if ($country != '') array_push($rules, ['field' => 'pais.pais_id', 'data' => $country, 'op' => 'eq']);

if ($type != '') array_push($rules, ['field' => 'subproveedor.tipo', 'data' => $type, 'op' => 'eq']);


/* Agrega reglas a un array basado en condiciones específicas de estado y mandante. */
if ($isActivate != '') {
    array_push($rules, ['field' => 'subproveedor.estado', 'data' => $isActivate, 'op' => 'eq']);
    array_push($rules, ['field' => 'subproveedor_mandante.estado', 'data' => $isActivate, 'op' => 'eq']);
    array_push($rules, ['field' => 'subproveedor_mandante_pais.estado', 'data' => $isActivate, 'op' => 'eq']);
}

array_push($rules, ['field' => 'subproveedor_mandante_pais.mandante', 'data' => $partner, 'op' => 'eq']);


/* Se filtran y obtienen datos de subproveedores con ciertas condiciones y orden. */
$filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

$SubproveedorMandantePais = new SubproveedorMandantePais();

$query = $SubproveedorMandantePais->getSubproveedoresMandantePaisCustomCredentials('proveedor.*, subproveedor.*, subproveedor_mandante_pais.*,  mandante.nombre as mandante_nombre, pais.pais_nom', 'subproveedor_mandante_pais.orden', 'asc', $SkeepRows, $MaxRows, $filter, true);

$query = json_decode($query);


/* procesa datos de proveedores y almacena información específica en un array. */
$providers = [];

foreach ($query->data as $key => $value) {
    $data = [];
    $data['Id'] = $value->{'subproveedor_mandante_pais.provmandante_id'};
    $data['Provider'] = $value->{'proveedor.descripcion'};
    $data['SubProvider'] = $value->{'subproveedor.descripcion'};
    $data['Partner'] = $value->{'mandante.mandante_nombre'};
    $data['State'] = $value->{'subproveedor_mandante_pais.estado'};
    $data['Country'] = $value->{'pais.pais_nom'};
    $data['Credentials'] = empty($value->{'subproveedor_mandante_pais.credentials'})
        ? $data['Credentials'] = $value->{'subproveedor.credentials'}
        : $data['Credentials'] = $value->{'subproveedor_mandante_pais.credentials'};

    array_push($providers, $data);
}

$response['HasError'] = false;
$response['AlertType'] = 'success';
$response['AlertMessage'] = '';
$response['ModelErrors'] = [];
$response['data'] = $providers;
