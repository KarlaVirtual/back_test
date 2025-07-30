<?php
use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\PaisMandante;
use Backend\dto\ReferidoInvitacion;

/**
 * Obtención ejemplo de email y URL dinámica vinculada a un referente.
 *
 * @param object $json Objeto JSON que contiene los parámetros de la solicitud.
 * @param string $json->session->usuario Identificador del usuario en la sesión.
 *
 * @return array $response Arreglo que contiene el código de respuesta, asunto del email, mensaje del email y URL del referente.
 * @return int $response["code"] Código de respuesta inicializado en 0.
 * @return string $response['data']['Subject'] Asunto del email.
 * @return string $response['data']['Message'] Mensaje del email.
 * @return string $response['data']['Url'] URL del referente.
 *
 * @throws Exception Si ocurre un error durante la validación de la campaña o del usuario.
 */

/*Obtención instancias de datos vinculadas a los usuarios*/
$params = $json->params;
$ReferidoInvitacion = new ReferidoInvitacion();
$UsuarioMandante = new UsuarioMandante($json->session->usuario);
$Usuario = new Usuario($UsuarioMandante->usuarioMandante);
$UsuarioOtrainfo = new UsuarioOtrainfo($UsuarioMandante->usuarioMandante);
$PaisMandante = new PaisMandante("", $UsuarioMandante->getMandante(), $UsuarioMandante->getPaisId());

/** Validando disponibilidad de campaña y validez del usuario */
$PaisMandante->progReferidosDisponible();
$UsuarioOtrainfo->validarReferenteAvalado();

/** Recuperando asunto de email, mensaje de email y link del referente */
$referentUrl = $PaisMandante->getUrlReferentePersonalizado($UsuarioOtrainfo);
$subject = $ReferidoInvitacion->getAsuntoTemplatePersonalizado($UsuarioOtrainfo, $PaisMandante, $Usuario->idioma);
$htmlMessage = $ReferidoInvitacion->getMensajeTemplatePersonalizado($UsuarioOtrainfo, $PaisMandante, $Usuario->idioma);

/*Generando formato de respuesta*/
$response["code"] = 0;
$response['data']['Subject'] = $subject;
$response['data']['Message'] = $htmlMessage;
$response['data']['Url'] = $referentUrl;
?>