<?php

use Backend\sql\Transaction;
use Backend\dto\ReferidoInvitacion;
use Backend\mysql\ReferidoInvitacionMySqlDAO;
use Backend\dto\UsuarioOtrainfo;

/**
 * Marca una invitación de referente como leída.
 *
 * @param object $json Objeto JSON que contiene los parámetros de entrada, incluyendo el enlace de referencia.
 * @param  string $params ReferentLink Enlace de referencia.
 *
 * @return void Modifica el array $response con el código de respuesta y los datos.
 *  -code:int Código de respuesta.
 *  -data:array Datos de respuesta.
 */

/* obtiene un enlace de referencia y lo identifica mediante una clase. */
$params = $json->params;
$referentLink = $params->ReferentLink;

$Transaction = new Transaction();
$UsuarioOtraInfo = new UsuarioOtrainfo();
$referentInfo = $UsuarioOtraInfo->identificarLinkReferente($referentLink);

/* Actualiza el estado de invitación como leído en la base de datos. */
if ($referentInfo['refinvitacionId']) {
    $ReferidoInvitacionMySqlDAO = new ReferidoInvitacionMySqlDAO($Transaction);
    $ReferidoInvitacion = new ReferidoInvitacion($referentInfo['refinvitacionId']);
    $ReferidoInvitacion->setLeido(true);
    $ReferidoInvitacionMySqlDAO->update($ReferidoInvitacion);
    $Transaction->commit();
}



/* Se inicializa un código de respuesta y un array vacío en PHP. */
$response["code"] = 0;
$response["data"] = [];
?>