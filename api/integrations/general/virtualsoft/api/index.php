<?php

/**
 * Este archivo implementa una API para procesar solicitudes HTTP y generar respuestas
 * basadas en datos procesados por la clase Report.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-05-09
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
 * Incluye la URI de la solicitud, los parámetros enviados y el cuerpo de la solicitud.
 */
$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . $_SERVER['REQUEST_URI'];
$log = $log . json_encode($_REQUEST);
$log = $log . trim(file_get_contents('php://input'));

// Guarda el log en un archivo con el nombre basado en la fecha actual.
fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

/**
 * Obtiene el cuerpo de la solicitud HTTP y lo decodifica desde JSON.
 * Se agrega un valor predeterminado al campo `mandante`.
 */
$body = file_get_contents('php://input');
$request = json_decode($body);
$request->mandante = "6";

/**
 * Crea una instancia de la clase Report para procesar los datos de la solicitud.
 */
$class = new Report($request);

/**
 * Carga los datos procesados por la clase Report y los imprime en formato JSON.
 */
$data = $class->loadData();
print_r(json_encode($data));


