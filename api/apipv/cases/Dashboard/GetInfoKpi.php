<?php

use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Pais;

/**
 * Dashboard/GetInfoKpi
 *
 * Este script obtiene información clave de rendimiento (KPI) sobre los usuarios registrados, activos, inactivos y con primeros depósitos.
 *
 * @param GET $Country (string): Código del país.
 * @param GET $Partner (string): Identificador del socio o mandante.
 * @param GET $ToDateLocal (string): Fecha final del rango en formato "Y-m-d H:i:s".
 * @param GET $FromDateLocal (string): Fecha inicial del rango en formato "Y-m-d H:i:s".
 *
 * @return array $response Respuesta en formato JSON con los siguientes atributos:
 *  - HasError (boolean): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta (e.g., "success", "error").
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *  - Data (array): Información estructurada con los siguientes datos:
 *      - TotalUsers (int): Total de usuarios registrados.
 *      - UsersRecords (int): Total de registros de usuarios.
 *      - ActiveUsers (int): Total de usuarios activos.
 *      - InactiveUsers (int): Total de usuarios inactivos.
 *      - FirstDeposits (int): Total de usuarios con primeros depósitos.
 *
 * @throws Exception Si faltan parámetros obligatorios o son inválidos.
 */

/* valida parámetros GET y crea un objeto "Pais" si son correctos. */
$Country = $_GET['Country'];
$Partner = $_GET['Partner'];
$ToDateLocal = $_GET['ToDateLocal'];
$FromDateLocal = $_GET['FromDateLocal'];

if (empty($ToDateLocal) || empty($FromDateLocal) || empty($Country) || $Partner === '') throw new Exception('Error en los parametros enviados', 100001);

$Pais = new Pais($Country);

/* Se inicializa un objeto Mandante y se formatean fechas para reglas. */
$Mandante = new Mandante($Partner);

$ToDateLocal = date('Y-m-d 00:00:00', strtotime($ToDateLocal));
$FromDateLocal = date('Y-m-d 23:59:59', strtotime($FromDateLocal));

$rules = [];


/* Se añaden reglas y filtros para consultas de usuario en formato JSON. */
array_push($rules, ['field' => 'usuario.fecha_crea', 'data' => $ToDateLocal, 'op' => 'ge']);
array_push($rules, ['field' => 'usuario.fecha_crea', 'data' => $FromDateLocal, 'op' => 'le']);
array_push($rules, ['field' => 'usuario.mandante', 'data' => $Mandante->mandante, 'op' => 'eq']);
array_push($rules, ['field' => 'usuario.pais_id', 'data' => $Pais->paisId, 'op' => 'eq']);

$filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

/* cuenta usuarios registrados, activos e inactivos, retornando resultados en formato JSON. */
$Usuario = new Usuario();
$data = (string)$Usuario->getUsuariosCustom2('COUNT(*) usuarios_registrados, COUNT(CASE WHEN usuario.estado = "A" THEN 1 END) as usuarios_activos, COUNT(CASE WHEN usuario.estado = "I" THEN 1 END) as usuarios_inactivos, COUNT(CASE WHEN usuario.fecha_primerdeposito IS NOT NULL THEN 1 END) as primeros_depositos', 'usuario.usuario_id', 'ASC', 0, 100, $filters, true);
$data = json_decode($data, true);

$response['HasError'] = false;
$response['AlertType'] = 'success';

/* organiza respuestas sobre usuarios en un formato estructurado. */
$response['AlertMessage'] = '';
$response['ModelErrors'] = [];
$response['Data'] = [
    'TotalUsers' => $data['count'][0]['.count'],
    'UsersRecords' => $data['data'][0]['.usuarios_registrados'],
    'ActiveUsers' => $data['data'][0]['.usuarios_activos'],
    'InactiveUsers' => $data['data'][0]['.usuarios_inactivos'],
    'FirstDeposits' => $data['data'][0]['.primeros_depositos']
];
?>