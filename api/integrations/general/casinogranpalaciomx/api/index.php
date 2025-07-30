<?php

/**
 * Archivo principal para la integración con Casino Gran Palacio MX.
 *
 * Este script procesa solicitudes HTTP entrantes, registra logs de las mismas
 * y utiliza la clase `Report` para cargar y devolver datos procesados.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $log      Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $_SERVER  Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $_REQUEST Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $body     Esta variable contiene el contenido del cuerpo de la solicitud HTTP.
 * @var mixed $request  Variable que representa la solicitud HTTP, conteniendo datos como parámetros y encabezados.
 * @var mixed $class    Define una clase en PHP.
 * @var mixed $data     Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\general\report\Report;

/**
 * Registra información de la solicitud HTTP en un archivo de log.
 *
 * Se incluye la URI de la solicitud, los parámetros enviados y el cuerpo de la solicitud.
 */
$log = "\r\n" . "-------------------------" . "\r\n";

$log = $log . $_SERVER['REQUEST_URI'];
$log = $log . json_encode($_REQUEST);
$log = $log . trim(file_get_contents('php://input'));
//Save string to log, use FILE_APPEND to append.

// Guarda el log en un archivo con la fecha actual.
fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

/**
 * Procesa el cuerpo de la solicitud HTTP.
 *
 * Decodifica el contenido JSON del cuerpo de la solicitud y agrega el campo `mandante`.
 */
$body = file_get_contents('php://input');
$request = json_decode($body);
$request->mandante = "6";

$class = new Report($request);

$data = $class->loadData();

// Devuelve los datos procesados como una respuesta JSON.
print_r(json_encode($data));


