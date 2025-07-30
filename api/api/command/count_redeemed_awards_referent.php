<?php
use Backend\sql\Transaction;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioOtrainfo;

/** Retorna el total de premios redimidos por el referente
 *
 *@return array
 * - code: int Respuesta de la petición
 * - data.TotalCount: int Cantidad de premios redimidos
 */

/** Recuperando información del referente */
$Transaction = new Transaction();
$UsuarioMandante = new UsuarioMandante($json->session->usuario);
$UsuarioOtrainfo = new UsuarioOtrainfo($UsuarioMandante->getUsuarioMandante());

/** Consultando total de premios redimidos por el referente */
$rules = [];
array_push($rules, ['field' => 'usuario_bono.usuario_id', 'data' => $UsuarioOtrainfo->getUsuarioId(), 'op' => 'eq']);
array_push($rules, ['field' => 'usuario_bono.estado', 'data' => '"A","R","E"', 'op' => 'in']);
array_push($rules, ['field' => 'usuario_bono.usuid_referido', 'data' => '', 'op' => 'nn']);

// Solicita los registros enviando los filtros a tener en cuenta
$filters = ['rules' => $rules, 'groupOp' => 'AND'];
$UsuarioBono = new UsuarioBono();
$countRedeemedBonuses = $UsuarioBono->getUsuarioBonosCustom('*', 'usuario_bono.usubono_id', 'ASC', 0, 1, json_encode($filters), true, '', '', '', true);
$countRedeemedBonuses = json_decode($countRedeemedBonuses)->count->{'0'} ?? '0';

$response["code"] = 0;
$response["data"]['TotalCount'] = $countRedeemedBonuses;

