<?php
/**
 * Includes de la api 'apipv'
 *
 * Configura la zona horaria, habilita el acceso CORS para credenciales y realiza validaciones para solicitudes específicas.
 *
 * Este recurso establece la zona horaria a "America/Bogota" y permite solicitudes CORS (Cross-Origin Resource Sharing)
 * con credenciales. Además, verifica si la solicitud proviene de una IP específica, en cuyo caso ajusta los encabezados
 * CORS para permitir accesos específicos desde orígenes determinados. También establece una configuración especial de
 * encabezados para solicitudes que provienen de la IP '172.105.16.250'.
 *
 * A continuación, también se verifica un entorno de depuración y se inicia una sesión general si la variable de entorno
 * `debug` está habilitada.
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */


require_once __DIR__ . '../../vendor/autoload.php';

use Backend\utils\SessionGeneral;


/* Configura la zona horaria y permite solicitudes CORS con credenciales. */
date_default_timezone_set('America/Bogota');
//header('Access-Control-Allow-Origin: http://127.0.0.1:5500');
//header('Access-Control-Allow-Origin: http://localhost');
//header('Access-Control-Allow-Origin: https://cert.doradobet.com');
header('Access-Control-Allow-Credentials: true');

$dir_ipG2 = $_SERVER["HTTP_X_FORWARDED_FOR"];

/* Permite acceso CORS y configura encabezados para una IP específica en PHP. */
if (strpos($dir_ipG2, '172.105.16.250') !== false) {
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Headers: refresh,authorization,Access-Control-Allow-Origin, Access-Control-Allow-Headers, Access-Control-Allow-Credentials,Authentication, Origin, X-Requested-With, Content-Type,x-token,rept');
    header('Access-Control-Expose-Headers: Authentication');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS,PUT');
    header('Content-Type: application/json');
    ini_set('memory_limit', '-1');
    header('Content-type: application/json');


    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
    header("Access-Control-Allow-Private-Network: true");

}

/* verifica un entorno de depuración y comienza una sesión. */
if ($_ENV["debug"]) {
}
$session = new SessionGeneral();
$session->inicio_sesion('_s', false);

