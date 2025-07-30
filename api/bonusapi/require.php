<?php
/**
 * Includes de la api 'apipv'
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */

use Backend\utils\SessionGeneral;

/**
 * bonusapi/require
 *
 * Configuración Inicial y Control de Accesos
 *
 * Configura los ajustes iniciales del entorno, incluyendo la gestión de sesiones, configuraciones de cabeceras HTTP
 * y otras funciones auxiliares como la obtención de todas las cabeceras y la conversión a booleano.
 *
 * Establece el control de acceso a través de CORS (Cross-Origin Resource Sharing), permitiendo solicitudes desde
 * orígenes específicos, y asegurando que las credenciales sean incluidas en las solicitudes HTTP.
 *
 * @param none
 *
 * @return void
 *
 * @throws Exception Si ocurre algún error al iniciar la sesión o al manejar las cabeceras.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Desactiva errores y permite solicitudes CORS desde el origen del servidor. */
error_reporting(0);
ini_set('display_errors', 'off');

require_once __DIR__ . '../../vendor/autoload.php';
date_default_timezone_set('America/Bogota');
//header('Access-Control-Allow-Origin: http://127.0.0.1:5500');
//header('Access-Control-Allow-Origin: http://localhost');
//header('Access-Control-Allow-Origin: https://cert.doradobet.com');
header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);

/* Permite el acceso CORS y gestiona sesiones, obteniendo todos los encabezados HTTP. */
header('Access-Control-Allow-Credentials: true');


$session = new SessionGeneral();
$session->inicio_sesion('_s', false);

if (!function_exists('getallheaders')) {
    function getallheaders()
    {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}


/* Define la función `boolval` si no existe, regresando valor booleano de una variable. */
if (!function_exists('boolval')) {

    function boolval($var)
    {
        return !!$var;
    }
}


