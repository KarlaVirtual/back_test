<?php

use Backend\dto\UsuarioMensajecampana;

/**
 * Client/GetClientSendNotificationCampaign
 *
 * Obtiene y formatea las campañas de usuario activas o expiradas.
 *
 * Este método procesa los datos de las campañas de usuario según los parámetros recibidos, incluyendo filtros por fechas, activación, país y otros criterios. Los datos son formateados adecuadamente antes de ser retornados en una respuesta estructurada.
 *
 * @param object $params : Objeto que contiene los parámetros de filtro para las campañas.
 *  - *Id* (int): ID de la campaña.
 *  - *DateFrom* (string): Fecha de inicio de la campaña (en formato 'Y-m-d').
 *  - *DateTo* (string): Fecha final de la campaña (en formato 'Y-m-d').
 *  - *DateExpiration* (string): Fecha de expiración de la campaña (en formato 'Y-m-d').
 *  - *CountrySelect* (int): ID del país de la campaña.
 *  - *IsActivate* (bool): Estado de activación de la campaña.
 *  - *IsGlobal* (bool): Si la campaña es global.
 *  - *start* (int): Paginación: inicio de los registros.
 *  - *length* (int): Paginación: número de registros por página.
 *  - *order* (array): Criterio de orden de los resultados.
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 *  - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 *  - *ModelErrors* (array): Retorna array vacío.
 *  - *Data* (array): Arreglo de campañas formateadas.
 *
 * Objeto en caso de error:
 *
 * "HasError" => true,
 * "AlertType" => "danger",
 * "AlertMessage" => "[Mensaje de error]",
 * "ModelErrors" => [],
 * "Data" => array(),
 *
 * @throws Exception Si ocurre un error al procesar los datos de la campaña.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* asigna valores de parámetros a variables y formatea fechas adecuadamente. */
$Id = $params->Id;
$DateFrom = !empty($params->DateFrom) ? date('Y-m-d 00:00:00', strtotime($params->DateFrom)) : '';
$DateTo = !empty($params->DateTo) ? date('Y-m-d 23:59:59', strtotime($params->DateTo)) : '';
$DateExpiration = $params->DateExpiration;
$CountrySelect = $params->CountrySelect ?: 0;
$IsActivate = $params->IsActivate;

/* establece variables y condiciones iniciales para procesar datos solicitados. */
$IsGlobal = $params->IsGlobal;
$start = $params->start ?: 0;
$length = $params->length ?: 100;
$order = $params->order[0]->dir;

if ($DateFrom > $DateTo) $DateTo = date('Y-m-d 23:59:59', strtotime($DateFrom));

$rules = [];


/* Declaración criterios de filtrado */
if (!empty($Id)) array_push($rules, ['field' => 'usuario_mensajecampana.usumencampana_id', 'data' => $Id, 'op' => 'eq']);
if (!empty($DateFrom)) array_push($rules, ['field' => 'usuario_mensajecampana.fecha_envio', 'data' => $DateFrom, 'op' => 'ge']);
if (!empty($DateTo)) array_push($rules, ['field' => 'usuario_mensajecampana.fecha_envio', 'data' => $DateTo, 'op' => 'le']);
if (!empty($DateExpiration)) {
    array_push($rules, ['field' => 'usuario_mensajecampana.fecha_expiracion', 'data' => date('Y-m-d 00:00:00', strtotime($DateExpiration)), 'op' => 'ge']);
    array_push($rules, ['field' => 'usuario_mensajecampana.fecha_expiracion', 'data' => date('Y-m-d 23:59:59', strtotime($DateExpiration)), 'op' => 'le']);
}
if (!empty($CountrySelect)) array_push($rules, ['field' => 'usuario_mensajecampana.pais_id', 'data' => $CountrySelect, 'op' => 'eq']);

/* Filtrado estado del mensaje */
if (!empty($IsActivate)) array_push($rules, ['field' => 'usuario_mensajecampana.estado', 'data' => $IsActivate, 'op' => 'eq']);

/* Se construyen reglas de filtrado y se codifican en formato JSON para consulta. */
array_push($rules, ['field' => 'usuario_mensajecampana.mandante', 'data' => $_SESSION['mandante'], 'op' => 'eq']);
array_push($rules, ['field' => 'usuario_mensajecampana.tipo', 'data' => '"PUSHNOTIFICACION", "DESKTOPNOTIFICACION"', 'op' => 'in']);

$filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

$UsuarioMensaje = new UsuarioMensajecampana();

/* Obtiene y formatea datos de campañas de usuario en un arreglo JSON. */
$campaigns = $UsuarioMensaje->getUsuarioMensajesCustom2('usuario_mensajecampana.*, pais.pais_nom, pais.pais_id', 'usuario_mensajecampana.usumencampana_id', $order, $start, $length, $filter, true);
$campaigns = json_decode($campaigns, true);

$allCapaigns = [];

foreach ($campaigns['data'] as $key => $value) {
    $data = [];
    $data['Id'] = $value['usuario_mensajecampana.usumencampana_id'];
    $data['Title'] = $value['usuario_mensajecampana.nombre'];
    $data['Description'] = $value['usuario_mensajecampana.descripcion'];
    $data['DateFrom'] = $value['usuario_mensajecampana.fecha_envio'];
    $data['DateExpiration'] = $value['usuario_mensajecampana.fecha_expiracion'];
    $data['CountryId'] = $value['pais.pais_id'];
    $data['CountrySelect'] = $value['pais.pais_nom'];
    $data['IsActivate'] = $value['usuario_mensajecampana.estado'];

    array_push($allCapaigns, $data);
}


/* genera una respuesta estructurada sin errores, incluyendo datos y mensaje de alerta. */
$response = [];
$response['HasError'] = false;
$response['AlertType'] = 'success';
$response['AlertMessage'] = 'success';
$response['ModelErrors'] = [];
$response['data'] = $allCapaigns;

/* Asigna la variable `$allCapaigns` a la clave 'Data' del array `$response`. */
$response['Data'] = $allCapaigns;
?>