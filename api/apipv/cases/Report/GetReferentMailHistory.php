 <?php

use Backend\dto\ReferidoInvitacion;
use Backend\dto\Pais;
use Backend\dto\UsuarioMandante;

/**
 * Report/GetReferentMailHistory
 * 
 * Obtiene el historial de correos enviados por un referente
 *
 * @param int $start          Posición inicial para paginación
 * @param int $limit          Límite de registros a retornar
 * @param int $usuidReferent  ID del usuario referente
 * @param int $country        ID del país a filtrar
 * @param string $dateFrom    Fecha inicial del rango (Y-m-d)
 * @param string $dateTo      Fecha final del rango (Y-m-d)
 * 
 * @return array {
 *   "HasError": boolean,       // Indica si hubo error
 *   "AlertType": string,       // Tipo de alerta (success, error)
 *   "AlertMessage": string,    // Mensaje descriptivo
 *   "ModelErrors": array,      // Errores del modelo
 *   "Data": array {
 *     "Messages": array,       // Lista de mensajes enviados
 *     "TotalCount": int       // Total de registros encontrados
 *   }
 * }
 */


// Obtiene los parámetros de paginación y filtrado desde la URL
$start = $_GET['start'];
$limit = $_GET['limit'];
$usuidReferent = $_GET['UsuidReferent'];
$country = $_GET['CountrySelect'];
$dateFrom = $_GET['dateFrom'];
if (!empty($dateFrom)) $dateFrom = date('Y-m-d 00:00:01', strtotime($dateFrom));
$dateTo = $_GET['dateTo'];
if (!empty($dateTo)) $dateTo = date('Y-m-d 23:59:59', strtotime($dateTo));

// Inicializa los filtros y valores por defecto para la paginación
$rules = [];
$start = ($start != '' && $start != null) ? $start : 0;
$limit = ($limit != '' && $limit != null) ? $limit : 10;

// Obtiene información del operador y configura el país por defecto
$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);
$position = $start;
$country = $country ?? ($_SESSION['PaisCondS'] ?? $UsuarioMandante->getPaisId());

// Construye las reglas de filtrado según los parámetros recibidos
if (!empty($usuidReferent)) array_push($rules, ['field' => 'referido_invitacion.usuid_referente', 'data' => $usuidReferent, 'op' => 'eq']);

if (!empty($country)) array_push($rules, ['field' => 'usuario.pais_id', 'data' => $country, 'op' => 'eq']);

if (!empty($dateFrom)) {
    $dateFrom = date('Y-m-d 00:00:01', strtotime($dateFrom));
    array_push($rules, ['field' => 'referido_invitacion.fecha_crea', 'data' => $dateFrom, 'op' => 'ge']);
}

if (!empty($dateTo)) {
    $dateFrom = date('Y-m-d 23:59:59', strtotime($dateTo));
    array_push($rules, ['field' => 'referido_invitacion.fecha_crea', 'data' => $dateTo, 'op' => 'le']);
}

// Configura y ejecuta la consulta para obtener las invitaciones
$filters = ['rules' => $rules, 'groupOp' => 'AND'];
$select = 'referido_invitacion.*, usuario.pais_id';
$orderColumn = 'referido_invitacion.fecha_crea';
$order = 'DESC';
$ReferidoInvitacion = new ReferidoInvitacion();
$invitations = $ReferidoInvitacion->getReferidoInvitacionCustom($select, $orderColumn, $order, $start, $limit, json_encode($filters), true);
$invitationsData = json_decode($invitations)->data;
$invitationsCount = json_decode($invitations)->count;

// Procesa los resultados y construye el array de mensajes
$messages = [];
foreach ($invitationsData as $invitation) {
    $Pais = new Pais($invitation->{'usuario.pais_id'});

    $message = [];
    $message['refinvitacionId'] = $invitation->{'referido_invitacion.refinvitacion_id'};
    $message['referentId'] = $invitation->{'referido_invitacion.usuid_referente'};
    $message['referredEmail'] = $invitation->{'referido_invitacion.referido_email'};
    $message['successfullyReferred'] = $invitation->{'referido_invitacion.referido_exitoso'} ?? '0';
    $message['subject'] = $invitation->{'referido_invitacion.asunto'};
    $message['message'] = $invitation->{'referido_invitacion.mensaje'};
    $message['sendDate'] = $invitation->{'referido_invitacion.fecha_crea'};
    $message['status'] = $invitation->{'referido_invitacion.estado'} == 'A' ? true : false;
    $message['country'] = $Pais->paisNom;
    array_push($messages, (object)$message);
}

// Prepara la respuesta final con los resultados
$response["HasError"] = false;
$response["Code"] = 0;
$response["AlertType"] = "success";
$response["AlertMessage"] = "Solicitud exitosa";
$response["ModelErrors"] = [];
$response["pos"] = $position;
$response["total_count"] = $invitationsCount[0]->{'.count'};
$response["data"] = $messages;

?>