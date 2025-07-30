<?php

use Backend\dto\UsuarioVerificacion;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\UsuarioVerificacionMySqlDAO;

/**
 *
 * command/update_validation
 *
 * Verificación de código de restablecimiento de contraseña
 *
 * Este proceso toma un código de restablecimiento, lo desencripta y verifica la validez del identificador de verificación asociado.
 * Si el identificador es numérico, se consulta su estado y se actualiza en la base de datos. Si el código ha expirado o su estado
 * no es 'NA', se marca como 'R' (Rechazado); de lo contrario, se marca como 'P' (Pendiente). Si el estado final es 'R', se lanza
 * una excepción indicando un error general.
 *
 * @param object $json : Objeto que contiene los parámetros recibidos, incluyendo `reset_code`.
 * @param string $ENCRYPTION_KEY : Clave de cifrado utilizada para desencriptar el código de restablecimiento.
 *
 * @return object $response es un array con los siguientes atributos:
 *  - *code* (int): Código de error, donde `0` indica éxito y `100000` representa un error general.
 *
 * Objeto en caso de error:
 *
 * "code" => 100000,
 * "result" => "Error General",
 * "data" => array(),
 *
 * @throws Exception Si el identificador no es numérico o si el estado final es 'R'.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* decodifica y desencripta un código de reinicio utilizando una clave de encriptación. */
$params = $json->params;
$reset_code = $params->reset_code;

$ConfigurationEnvironment = new ConfigurationEnvironment();

$data = $ConfigurationEnvironment->decrypt(str_replace(' ', '+', urldecode($reset_code)), $ENCRYPTION_KEY);

/* Separación bloques de datos para la validación*/
$parts = explode('_', $data);
$verification_id = $parts[0];

try {
    if (is_numeric($verification_id)) {

        $UsuarioVerificacion = new UsuarioVerificacion($verification_id);
        $expire_time = round((strtotime($UsuarioVerificacion->getFechaCrea()) - time()) / 3600, 2);

        $UsuarioVerificacion->setEstado($expire_time > 24 || $UsuarioVerificacion->getEstado() !== 'NA' ? 'R' : 'P');

        $UsuarioVerificacionMySqlDAO = new UsuarioVerificacionMySqlDAO();
        $UsuarioVerificacionMySqlDAO->update($UsuarioVerificacion);
        $UsuarioVerificacionMySqlDAO->getTransaction()->commit();

        if ($UsuarioVerificacion->getEstado() === 'R') throw new Exception('Error General', '100000');

    } else throw new Exception('Error General', '100000');
} catch (Exception $ex) {
    /*Manejo de excepciones*/

    throw $ex;
}


/* Se inicializa un arreglo `$response` con un código de estado inicial 0. */
$response = [];
$response['code'] = 0;
?>