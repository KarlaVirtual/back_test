<?php

use Backend\dto\UsuarioMarketing;

/**
 * Client/GetReportMarketing
 *
 * Este script genera un reporte de marketing basado en los parámetros proporcionados.
 *
 * @param array $params
 * @param int $params->start Índice inicial para la paginación.
 * @param int $params->count Número de registros a obtener.
 * @param int $params->Id Identificador del usuario de marketing.
 * @param string $params->ExternalId Identificador externo.
 * @param string $params->dateFrom Fecha de inicio en formato 'Y-m-d H:i:s'.
 * @param string $params->dateTo Fecha de fin en formato 'Y-m-d H:i:s'.
 * @param string|int $params->Type Tipo de acción de marketing (e.g., "CLICKBANNER", "LINKVISIT", "REGISTRO").
 * 
 * 
 *
 * @return array $response
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (e.g., "success", "danger").
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo.
 * - data (array): Datos del reporte de marketing.
 *
 */


/**
 * Convierte el valor del tipo de acción de marketing.
 *
 * @param string|int $value El valor del tipo de acción de marketing.
 * @return string|int El tipo de acción de marketing convertido.
 */
function typeValue($value)
{
    if (empty($value)) return '';

    /* mapea tipos de eventos a números y gestiona la solicitud de inicio. */
    $types = ['CLICKBANNER', 'LINKVISIT', 'REGISTRO'];

    return is_numeric($value) ? $types[$value - 1] : array_search($value, $types) + 1;
}

/* procesa parámetros de solicitud y establece valores predeterminados. */
$start = $_REQUEST['start'] ?: $_SESSION['?start'] ?: 0;
$count = $_REQUEST['count'] ?: 100;
$Id = $_REQUEST['Id'];
$ExternalId = $_REQUEST['ExternalId'];
$dateFrom = !empty($_REQUEST['dateFrom']) ? date('Y-m-d H:00:00', strtotime($_REQUEST['dateFrom'])) : date('Y-m-d 00:00:00');
$dateTo = !empty($_REQUEST['dateTo']) ? date('Y-m-d 23:59:59', strtotime($_REQUEST['dateTo'])) : date('Y-m-d 23:59:59');
$Type = $_REQUEST['Type'];

$Type = typeValue($Type);
/*Genera filtrado para la solicitud*/
if ($dateFrom > $dateTo) $dateFrom = date('Y-m-d H:00:00', strtotime($dateTo));

$rules = [];

if (!empty($Id)) array_push($rules, ['field' => 'usuario_marketing.usumarketing_id', 'data' => $Id, 'op' => 'eq']);

if (!empty($ExternalId)) array_push($rules, ['field' => 'usuario_marketing.externo_id', 'data' => $ExternalId, 'op' => 'eq']);

if (!empty($Type)) array_push($rules, ['field' => 'usuario_marketing.tipo', 'data' => $Type, 'op' => 'eq']);

array_push($rules, ['field' => 'usuario_marketing.fecha_crea', 'data' => $dateFrom, 'op' => 'ge']);
array_push($rules, ['field' => 'usuario_marketing.fecha_crea', 'data' => $dateTo, 'op' => 'le']);

$filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

$UsuarioMarketing = new UsuarioMarketing();


/* Extrae y transforma datos de usuario marketing a un array estructurado. */
$usuarioMarketing = $UsuarioMarketing->getUsuarioMarketingCustom('usuario_marketing.*', 'usuario_marketing.usumarketing_id', 'asc', $start, $count, $filters, true);

$usuarioMarketing = json_decode($usuarioMarketing);

$marketing_data = [];

foreach ($usuarioMarketing->data as $key => $value) {

    $data = [];
    $data['Id'] = $value->{'usuario_marketing.usumarketing_id'};
    $data['Type'] = typeValue($value->{'usuario_marketing.tipo'});
    $data['ExternalId'] = $value->{'usuario_marketing.externo_id'};
    $data['CreationDate'] = $value->{'usuario_marketing.fecha_crea'};
    $data['Value'] = $value->{'usuario_marketing.valor'};

    array_push($marketing_data, $data);
}


/* Código establece una respuesta exitosa sin errores y con datos de marketing. */
$response['HasError'] = false;
$response['AlertType'] = 'success';
$response['AlertMessage'] = '';
$response['ModelErrors'] = [];
$response['data'] = $marketing_data;
?>