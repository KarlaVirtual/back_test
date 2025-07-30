<?php

/**
 * Propósito: Este recurso retorna los parametros de configuracion de sesion de los usuario
 * Descripción de variables:
 *    - Partner: Mandante al cual se le va a consultar la configuracion (obligatorio)
 *    - CountrySelected: Pais al cual se le va a consultar la configuracion (obligatorio)
 **/

use Backend\dto\MandanteDetalle;

/**
 * @OA\Info(title="My API", version="0.1")
 */

/**
 * @OA\Post(
 *     path="/apipv/cases/PartnerSettingsSitebuilder/GetPartnerSettingsSitebuilder",
 *     summary="Este recurso retorna los parametros de configuracion de sesion de los usuario",
 *     description="Este recurso retorna los parametros de configuracion de sesion de los usuario",
 *     tags={"PartnerSettingsSitebuilder"},
 *
 *     @OA\Parameter(
 *         name="Partner",
 *         in="query",
 *         required=true,
 *         description="Mandante al cual se le va a consultar la configuracion",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="CountrySelected",
 *         in="query",
 *         required=true,
 *         description="Pais al cual se le va a consultar la configuracion",
 *         @OA\Schema(type="string")
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Respuesta exitosa",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="HasError", description="Indica si hubo error", type="boolean", example=false),
 *             @OA\Property(property="AlertType", description="Tipo de alerta", type="string", example="success"),
 *             @OA\Property(property="AlertMessage", description="Mensaje de alerta", type="string", example=""),
 *             @OA\Property(property="ModelErrors", description="Errores del modelo", type="array", @OA\Items(type="string")),
 *             @OA\Property(property="Data", description="Datos de respuesta", type="Array", example={})
 *         )
 *     )
 * )
 */

/**
 * PartnerSettingsSitebuilder/GetPartnerSettingsSitebuilder
 *
 * Este recurso retorna los parámetros de configuración de sesión de los usuarios.
 *
 * @param array $_REQUEST Arreglo con los siguientes valores:
 * @param int $_REQUEST['Partner'] Mandante al cual se consultará la configuración (obligatorio).
 * @param string $_REQUEST['CountrySelected'] País al cual se consultará la configuración (obligatorio).
 * 
 * 
 * @return array $response Respuesta con los siguientes valores:
 *     - HasError: Indica si hubo un error (boolean).
 *     - AlertType: Tipo de alerta (string).
 *     - AlertMessage: Mensaje de alerta (string).
 *     - ModelErrors: Errores del modelo (array).
 *     - Data: Datos de configuración del socio (array con claves y valores).
 * @throws Exception Si faltan parámetros obligatorios o se detecta un error general.
 */

$Partner = $_REQUEST['Partner'];
$Country = $_REQUEST['CountrySelected'];

if ($Partner === '' || empty($Country)) throw new Exception('Error generela', 30001);


/* Se definen abreviaturas y reglas para validar campos en una estructura de datos. */
$abbreviations = ['TYPEREGISTER', 'APPCHANPERSONALINF', 'REGISTERACTIVATION', 'REQREGACT', 'DAYSNOTIFYPASSEXPIRE', 'SESSIONINACTIVITYMIN', 'WRONGATTEMPTSLOGIN', 'SESSIONDURATIONMIN', 'DAYALERTCHANGEPASS', 'DAYSEXPIREPASSWORD', 'DAYSEXPIRETEMPPASS', 'MINLENPASSWORD'];

$rules = [];

array_push($rules, ['field' => 'mandante_detalle.estado', 'data' => 'A', 'op' => 'eq']);
array_push($rules, ['field' => 'mandante_detalle.pais_id', 'data' => $Country, 'op' => 'eq']);

/* Se crean reglas de filtrado y se generan en formato JSON para consulta. */
array_push($rules, ['field' => 'mandante_detalle.mandante', 'data' => $Partner, 'op' => 'eq']);
array_push($rules, ['field' => 'clasificador.estado', 'data' => 'A', 'op' => 'eq']);
array_push($rules, ['field' => 'clasificador.abreviado', 'data' => '"' . implode('", "', $abbreviations) . '"', 'op' => 'in']);

$filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

$MandanteDetalle = new MandanteDetalle();

/* Consulta detalles de mandantes y define tipos de configuraciones para el sistema. */
$queryPartnerDetail = $MandanteDetalle->getMandanteDetallesCustom2('clasificador.abreviado, mandante_detalle.valor', 'clasificador.clasificador_id', 'ASC', 0, 100, $filters, true);
$queryPartnerDetail = json_decode($queryPartnerDetail, true);

$types = [
    'TYPEREGISTER' => 'TypeRegister',
    'APPCHANPERSONALINF' => 'ApproveChangesInformation',
    'REGISTERACTIVATION' => 'AutomaticallyActive',
    'REQREGACT' => 'ActivateRegisterUser',
    'DAYSNOTIFYPASSEXPIRE' => 'DaysNotifyBeforePasswordExpire',
    'SESSIONINACTIVITYMIN' => 'SessionInativityLength',
    'WRONGATTEMPTSLOGIN' => 'UserWrongLoginAttempts',
    'SESSIONDURATIONMIN' => 'SessionLength',
    'DAYALERTCHANGEPASS' => 'DaysAlertChangePassword',
    'DAYSEXPIREPASSWORD' => 'UserPasswordExpireDays',
    'DAYSEXPIRETEMPPASS' => 'UserTempPasswordExpireDays',
    'MINLENPASSWORD' => 'UserPasswordMinLength'
];


/* Genera un array de detalles de socios a partir de datos consultados y tipos definidos. */
$partnerDetailData = [];

$partnerDetailData = array_map(function () {
    return '';
}, array_flip($types));

foreach ($queryPartnerDetail['data'] as $key => $value) {
    $partnerDetailData[$types[$value['clasificador.abreviado']]] = $value['mandante_detalle.valor'];
}


/* establece una respuesta exitosa sin errores y con datos de un socio. */
$response['HasError'] = false;
$response['AlertType'] = 'success';
$response['AlertMessage'] = '';
$response['ModelErrors'] = [];
$response['Data'] = $partnerDetailData;

?>