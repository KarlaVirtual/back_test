<?php
use Backend\dto\ReferidoInvitacion;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\UsuarioMandante;
use Backend\sql\Transaction;

/**
 * Inicializa las instancias de UsuarioMandante, UsuarioOtrainfo y ReferidoInvitacion.
 *
 * Obtiene información del usuario a partir de una sesión JSON, y configura las reglas
 * para filtrar invitaciones referidas antes de contar el total de invitaciones enviadas.
 *
 * @param int $json->session->usuario ID del usuario
 *
 * @return array $response Contiene el código de respuesta y el total de invitaciones enviadas
 *  - code:int Código de respuesta
 *  - data.TotalInvitations:int Total de invitaciones enviadas
 */

$UsuarioMandante = new UsuarioMandante($json->session->usuario);
$UsuarioOtraInfo = new UsuarioOtrainfo($UsuarioMandante->getUsuarioMandante());
$ReferidoInvitacion = new ReferidoInvitacion();

$rules = [];
$select = 'referido_invitacion.refinvitacion_id';
array_push($rules, ['field' => 'referido_invitacion.usuid_referente', 'data' => $UsuarioOtraInfo->getUsuarioId(), 'op' => 'eq']);
array_push($rules, ['field' => 'referido_invitacion.estado', 'data' => 'A', 'op' => 'eq']);
$filters = ['rules' => $rules, 'groupOp' => 'AND'];
$totalInvitations = $ReferidoInvitacion->getReferidoInvitacionCustom($select, 'referido_invitacion.refinvitacion_id', 'DESC', 0, 1, json_encode($filters), true, true);
$totalInvitations = json_decode($totalInvitations);
$sentInvitations = $totalInvitations->count[0]->{'0'};


$response["code"] = 0;
$response['data']['TotalInvitations'] = $sentInvitations;
?>