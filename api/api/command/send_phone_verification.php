<?php

use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\UsuarioMandante;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Registro;

/**
 * command/send_phone_verification
 *
 * Genera un código de validación para el teléfono del usuario y envía un mensaje de texto con dicho código.
 *
 * @param string $usuario : Identificador del usuario en sesión.
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *code* (int): Código de error desde el proveedor.
 *  - *code2* (int): Código de validación generado.
 *  - *rid* (string): Identificador de la solicitud.
 *  - *data* (int): Código de validación generado.
 *
 *
 * @throws Exception Error al enviar el mensaje de validación.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Se crean objetos de usuario, registro y mandante a partir de datos JSON. */
$UsuarioMandante = new UsuarioMandante($json->session->usuario);
$Usuario = new Usuario($UsuarioMandante->usuarioMandante);
$Registro = new Registro('', $Usuario->usuarioId);

$Mandante = new Mandante($Usuario->mandante);

$ConfigurationEnvironment = new ConfigurationEnvironment();

/* Obtención código de verificación telefónico*/
$code = $ConfigurationEnvironment->generatePhoneValidationCode($Usuario);

$mensaje = $Mandante->nombre . ' - Su codigo de verificacion es: ' . $code;

$result = $ConfigurationEnvironment->EnviarMensajeTexto($mensaje, '', $Registro->celular, $Usuario->mandante, $UsuarioMandante);

if (!$result) throw new Exception('Error al enviar el mensaje', 100000);


/* crea un array de respuesta con varios códigos y datos. */
$response = [];
$response['code'] = 0;
$response['code2'] = $code;
$response['rid'] = $json->rid;
$response['data'] = $code;
?>