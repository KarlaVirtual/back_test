<?php

use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\dto\ConfigurationEnvironment;

/**
 * command/validate_phone
 *
 * Verificación de código de celular para usuario
 *
 * Este recurso se encarga de verificar el código de celular enviado por el usuario, comparándolo con el código
 * registrado en el sistema para confirmar la veracidad de la solicitud. En caso de éxito, se actualiza el estado de
 * verificación del celular del usuario en la base de datos.
 *
 * @param object $json : Objeto JSON recibido con los parámetros de la solicitud.
 * @param string $json ->params->code : Código de verificación del celular recibido del usuario.
 * @param object $json ->session->usuario : Usuario que realiza la solicitud.
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *code* (int): Código de éxito o error de la operación.
 *  - *rid* (string): Identificador de la solicitud.
 *  - *data* (array): Datos adicionales de la respuesta. En este caso estará vacío si la operación es exitosa.
 *
 * @throws Exception Si el código de verificación no es válido.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* establece instancias de clases utilizando datos del objeto JSON recibido. */
$code = $json->params->code;

$UsuarioMandate = new UsuarioMandante($json->session->usuario);
$Usuario = new Usuario($UsuarioMandate->usuarioMandante);

$ConfigurationEnvironment = new ConfigurationEnvironment();

/* Solicitud a método encargado de la verificación*/
$isValid = $ConfigurationEnvironment->validatePhoneCode($Usuario, $code);

if (!$isValid) throw new Exception('El codigo de verificacion no es correcto', 100094);


/* Actualiza la verificación del celular del usuario en la base de datos. */
$Usuario->verifCelular = 'S';
$Usuario->fechaVerifCelular = date('Y-m-d H:i:s');

$UsuarioMySqlDAO = new UsuarioMySqlDAO();
$UsuarioMySqlDAO->update($Usuario);
$UsuarioMySqlDAO->getTransaction()->commit();


/* Inicializa una respuesta con código, identificador y datos vacíos en un arreglo. */
$response = [];
$response['code'] = 0;
$response['rid'] = $json->rid;
$response['data'] = [];
?>