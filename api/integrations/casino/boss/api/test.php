<?php
/**
 * Este archivo contiene un script para probar la API de casino 'boss'.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Configuración de errores para el script.
 */
error_reporting(E_ALL);
ini_set('display_errors', 'ON');

/**
 * Carga automática de clases mediante Composer.
 */
require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Inbet;

/**
 * Registro de datos de entrada.
 *
 * @var string $log Cadena que almacena los datos de entrada y los formatea para el registro.
 */
$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . json_encode($_REQUEST);
$log = $log . trim(file_get_contents('php://input'));

/**
 * Cuerpo de la solicitud HTTP.
 *
 * @var string $body Contenido del cuerpo de la solicitud HTTP.
 */
$body = file_get_contents('php://input');

/**
 * Ejecución de la autenticación en la API de Inbet.
 *
 * @var Inbet $Inbet    Objeto de la clase Inbet para interactuar con la API.
 * @var mixed $response Respuesta de la API tras la autenticación.
 */
$Inbet = new Inbet("", "13346577889273645363");
$response = $Inbet->Auth();

/**
 * Imprime la respuesta de la API.
 */
print ($response);