<?php

use Backend\sql\Transaction;
use Backend\dto\ReferidoInvitacion;
use Backend\mysql\ReferidoInvitacionMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\UsuarioMandante;
use Backend\dto\Usuario;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\PaisMandante;

/**
 * command/send_referent_invitation
 *
 * Envía invitaciones por correo electrónico a los referidos de un usuario, validando la disponibilidad del programa de referidos y la validez de los correos electrónicos.
 *
 * @param array $EmailAddresses : Lista de direcciones de correo a las que se enviarán las invitaciones.
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *code* (int): Código de estado de la operación.
 *  - *data* (array): Contiene el resultado del proceso de envío.
 *    - *AlertMessage* (string): Mensaje informativo sobre el estado de los envíos.
 *    - *SentFailured* (array): Lista de correos electrónicos que no se pudieron enviar.
 *
 *
 * @throws Exception Asunto de referidos vacio en envio de invitacion
 * @throws Exception Mensaje de referidos vacio en envio de invitacion
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* obtiene direcciones de correo y valida un programa de referidos. */
$params = $json->params;
$emailAddresses = $params->EmailAddresses;


/** Validando disponibilidad del programa de referidos */
$UsuarioMandante = new UsuarioMandante($json->session->usuario);

/* Crea un objeto Usuario y verifica su estado como referente avalado. */
$Usuario = new Usuario($UsuarioMandante->usuarioMandante);
$PaisMandante = new PaisMandante("", $Usuario->mandante, $Usuario->paisId);
$PaisMandante->progReferidosDisponible();


/** Validando que usuario sea un referente avalado */
$UsuarioOtrainfo = new UsuarioOtrainfo($Usuario->getUsuarioId());

/* valida un referente y recupera un asunto para una invitación personalizada. */
$UsuarioOtrainfo->validarReferenteAvalado();


/**Recuperando asunto y plantilla que conforman la invitación*/
$ReferidoInvitacion = new ReferidoInvitacion();
//Recuperando asunto
$subject = $ReferidoInvitacion->getAsuntoTemplatePersonalizado($UsuarioOtrainfo, $PaisMandante, $Usuario->idioma);

/* verifica si el asunto está vacío y lanza una excepción si es así. */
if ($subject == null || $subject == "") {
    //ERROR Mensaje NO enviado por asunto vacío
    throw new Exception('Asunto de referidos vacio en envio de invitacion', 4003);
}
//Recuperando plantilla
$htmlMessage = $ReferidoInvitacion->getMensajeTemplatePersonalizado($UsuarioOtrainfo, $PaisMandante, $Usuario->idioma);

/* Valida si el mensaje está vacío y lanza una excepción si es necesario. */
if ($htmlMessage == null || $htmlMessage == "") {
    //ERROR Mensaje NO enviado por mensaje vacío
    throw new Exception('Mensaje de referidos vacio en envio de invitacion', 4004);
}


/** Procesos de validaciones pre-envío, envío de correo y validaciones pos-envío */
$Transaction = new Transaction();

/* Inicializa un array vacío llamado $mailingResult para almacenar resultados de envíos. */
$mailingResult = [];
foreach ($emailAddresses as $emailAddress) {
    /** Validaciones pre-envio */

    /* valida si una dirección de correo electrónico supera 320 caracteres. */
    $validEmail = true;
    //Validando tamaño máximo de la dirección email
    if ($validEmail && strlen($emailAddress) > 320) {
        //Direccion email supera el máximo de caracteres permitidos
        $validEmail = false;
    }

    //Verificando dirección email valida

    /* Verifica la validez de un correo electrónico antes de continuar con el envío. */
    if ($validEmail && (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL))) {
        //Dirección email inválida
        $validEmail = false;
    }
    if (!$validEmail) {
        //Si el email NO es válido se saltará el envío y las validaciones pos-envío pasando a la siguiente iteración
        $mailingResult[$emailAddress] = false;
        continue;
    }


    /** Guardado del mensaje */

    /* Crea una invitación referida con datos del usuario y mensaje asociado. */
    $ReferidoInvitacion = new ReferidoInvitacion();
    $ReferidoInvitacion->setUsuidReferente($Usuario->getUsuarioId());
    $ReferidoInvitacion->setReferidoEmail($emailAddress);
    $ReferidoInvitacion->setAsunto($subject);
    $ReferidoInvitacion->setMensaje($htmlMessage);
    $ReferidoInvitacion->setLeido(0);

    /* inserta una invitación, configurando usuario y estado, y genera un enlace asociado. */
    $ReferidoInvitacion->SetUsucreaId(0);
    $ReferidoInvitacion->setUsumodifId(0);
    $ReferidoInvitacion->setEstado('A');
    $ReferidoInvitacionMySqlDAO = new ReferidoInvitacionMySqlDAO($Transaction);
    $ReferidoInvitacionMySqlDAO->insert($ReferidoInvitacion);


    /** Adición link del referido */
    $newUrl = $PaisMandante->getUrlReferentePersonalizado($UsuarioOtrainfo, $ReferidoInvitacion->getRefinvitacionId());

    /* Reemplaza un enlace en un mensaje HTML y envía un correo electrónico. */
    $finalHtmlMessage = preg_replace('/#referentlink#/', $newUrl, $htmlMessage);


    /** Envío del correo */
    $ConfigurationEnvironment = new ConfigurationEnvironment();
    $finalSentStatus = $ConfigurationEnvironment->EnviarCorreoVersion2($emailAddress, "", "", $subject, "", "", $finalHtmlMessage, "", "", "", $PaisMandante->getMandante(), true);


    /** Validaciones pos-envío y actualización de la invitación*/

    /* Se actualiza el estado y mensaje de una invitación basándose en el envío de correo. */
    $mailingResult[$emailAddress] = $finalSentStatus;
    $ReferidoInvitacion->setMensaje($finalHtmlMessage);
    $ReferidoInvitacion->setEstado($finalSentStatus ? 'A' : 'I');
    $ReferidoInvitacionMySqlDAO->update($ReferidoInvitacion);
}

/* confirma una transacción y filtra correos electrónicos fallidos. */
$Transaction->commit();


/** Recolección envíos fallidos - respuesta a frontend */
$failuredMails = array_filter($mailingResult, function ($status) {
    return !$status;
});

/* verifica el estado de los correos enviados y genera mensajes de alerta. */
$failuredMails = array_keys($failuredMails);

if (count($failuredMails) < count($mailingResult) && count($failuredMails) > 0) {
    $alertMessage = "Invitaciones parcialmente enviadas";
} elseif (count($failuredMails) == count($mailingResult)) {
    $alertMessage = "Ninguna invitación enviada";
} else {
    /* asigna un mensaje a una variable si no se cumple una condición. */

    $alertMessage = "Invitaciones enviadas";
}


/* crea un array de respuesta con un mensaje y errores de envío. */
$response["code"] = 0;
$response["data"]["AlertMessage"] = $alertMessage;
$response["data"]["SentFailured"] = $failuredMails;
?>