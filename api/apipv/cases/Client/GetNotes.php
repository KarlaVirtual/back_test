<?php

use Backend\dto\UsuarioNota;
use Backend\dto\UsuarioMandante;

/**
 * Obtiene notas de usuario basadas en filtros proporcionados.
 *
 * @param array $params Parámetros de entrada obtenidos de la solicitud:
 * @param int $NoteId ID de la nota (opcional).
 * @param int $UserTo ID del usuario destinatario (opcional).
 * @param int $UserFrom ID del usuario remitente (opcional).
 * @param int $ReferenceId ID de referencia (opcional).
 * @param string $DateFrom Fecha de inicio del filtro (opcional).
 * @param string $DateTo Fecha de fin del filtro (opcional).
 * @param int $start Índice inicial para la paginación (opcional, por defecto 0).
 * @param int $count Cantidad de registros a obtener (opcional, por defecto 10).
 * 
 * 
 * @return array $response Respuesta JSON con los siguientes valores:
 *                         - HasError: Indica si hubo un error (true/false).
 *                         - AlertReferenceId: Tipo de alerta (success/error).
 *                         - AlertMessage: Mensaje de alerta.
 *                         - ModelErrors: Lista de errores del modelo (puede ser vacía).
 *                         - data: Lista de notas obtenidas.
 * @throws Exception Si ocurre un error al convertir usuarios o al obtener notas.
 */

/*Obtiene parámetros de la solicitud y los inicializa con valores predeterminados si están vacíos.*/
$NoteId = $_REQUEST['NoteId'];
$UserTo = $_REQUEST['UserTo'];
$UserFrom = $_REQUEST['UserFrom'];
$ReferenceId = $_REQUEST['ReferenceId'];
$DateFrom = !empty($_REQUEST['DateFrom']) ? date('Y-m-d 00:00:00', strtotime($_REQUEST['DateFrom'])) : '';
$DateTo = !empty($_REQUEST['DateTo']) ? date('Y-m-d 23:59:59', strtotime($_REQUEST['DateTo'])) : '';
$partner = $_SESSION['mandante'] == -1 ? '' : $_SESSION['mandante'];
$start = $_REQUEST['start'] ?: 0;
$count = $_REQUEST['count'] ?: 10;

/*Convierte UserTo y UserFrom a usumandanteId o los establece en '0' si hay excepción.*/
if (!empty($UserTo)) {
    try {
        $UsuarioMandante = new UsuarioMandante('', $UserTo, $_SESSION['mandante']);
        $UserTo = $UsuarioMandante->usumandanteId;
    } catch (Exception $ex) {
        $UserTo = '0';
    }
}

if (!empty($UserFrom)) {
    try {
        $UsuarioMandante = new UsuarioMandante('', $UserFrom, $_SESSION['mandante']);
        $UserFrom = $UsuarioMandante->usumandanteId;
    } catch (Exception $ex) {
        $UserFrom = '0';
    }
}

/*Genera reglas de filtro basadas en parámetros de fecha, usuario y referencia.*/
$rules = [];

if (!empty($DateFrom) && !empty($DateTo) && $DateTo > $DateFrom) $DateTo = date('Y-m-d 23:59:59', strtotime($DateFrom . ' + 5 days'));

if (!empty($NoteId)) array_push($rules, ['field' => 'usuario_nota.usunota_id', 'data' => $NoteId, 'op' => 'eq']);
if (!empty($ReferenceId)) array_push($rules, ['field' => 'usuario_nota.tipo', 'data' => $ReferenceId, 'op' => 'eq']);
if (!empty($refId)) array_push($rules, ['field' => 'usuario_nota.ref_id', 'data' => $refId, 'op' => 'eq']);
if (!empty($DateFrom)) array_push($rules, ['field' => 'usuario_nota.fecha_crea', 'data' => $DateFrom, 'op' => 'ge']);
if (!empty($DateTo)) array_push($rules, ['field' => 'usuario_nota.fecha_crea', 'data' => $DateTo, 'op' => 'le']);
if ($UserTo != '') array_push($rules, ['field' => 'usuario_nota.usuto_id', 'data' => $UserTo, 'op' => 'eq']);
if ($UserFrom != '') array_push($rules, ['field' => 'usuario_nota.usufrom_id', 'data' => $UserFrom, 'op' => 'eq']);
if ($partner != '') array_push($rules, ['field' => 'usuario_nota.mandante', 'data' => $partner, 'op' => 'eq']);

$filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

$UsurioNota = new UsuarioNota();

$notes = $UsurioNota->getUsuarioNotaCustom('usuario_nota.*, usuto.usuario_mandante, usuto.nombres, usufrom.usuario_mandante, usufrom.nombres, clasificador.descripcion', 'usuario_nota.usunota_id', 'asc', $start, $count, $filter, true);

$notes = json_decode($notes, true);

$allNotes = [];

/*Convierte datos de notas de usuario a un formato específico y los agrega a un array.*/
foreach ($notes['data'] as $key => $value) {
    $data = [];
    $data['Id'] = $value['usuario_nota.usunota_id'];
    $data['Description'] = $value['usuario_nota.descripcion'];
    $data['Type'] = $value['clasificador.descripcion'];
    $data['UserTo'] = $value['usuto.usuario_mandante'] . ' - ' . $value['usuto.nombres'];
    $data['UserFrom'] = $value['usufrom.usuario_mandante'] . ' - ' . $value['usufrom.nombres'];
    $data['ReferenceId'] = $value['usuario_nota.ref_id'];
    $data['Partner'] = $value['usuario_nota.mandante'];
    $data['DateAt'] = $value['usuario_nota.fecha_crea'];

    array_push($allNotes, $data);
}

/*Genera una respuesta JSON con notas de usuario y estado de la operación.*/
$response = [];
$response['HasError'] = false;
$response['AlertReferenceId'] = 'success';
$response['AlertMessage'] = '';
$response['ModelErrors'] = [];
$response['data'] = $allNotes;

?>