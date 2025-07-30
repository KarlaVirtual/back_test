<?php
/** Configura las cabeceras de la petición http con valores tomados de $_SERVER
 *Por otro lado define la configuración de reportería de errores con base en las
 * banderas de debugging
 */


/* configura la visualización de errores según la variable de entorno 'debug'. */
error_reporting(0);
ini_set('display_errors', 'OFF');

require_once __DIR__ . '../../vendor/autoload.php';

if ($_ENV['debug']) {
    error_reporting(E_ALL);
    ini_set("display_errors", "ON");
    $_ENV["debugFixed"] = '1';
    $debugFixed = '1';
}


/* Configura el encabezado CORS para permitir solicitudes desde el origen especificado. */
date_default_timezone_set('America/Bogota');

//header('Access-Control-Allow-Origin: http://127.0.0.1:5500');
//header('Access-Control-Allow-Origin: http://localhost');
header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);

header('Access-Control-Allow-Credentials: true');

/* Configura encabezados HTTP para permitir solicitudes CORS y definir tipo de contenido JSON. */
header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Access-Control-Allow-Headers, Access-Control-Allow-Credentials,Authentication, Origin, X-Requested-With, Content-Type');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS,PUT');
header('Content-Type: application/json');


