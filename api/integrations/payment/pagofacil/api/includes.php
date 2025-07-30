<?php

/**
 * Archivo de integración con PagoFácil para gestionar pagos.
 *
 * Este archivo configura el entorno, inicializa sesiones y define los encabezados
 * necesarios para la comunicación con la API de PagoFácil.
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
 * @var mixed $_SERVER Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $session Variable que almacena datos de sesión del usuario.
 */

use Backend\utils\SessionGeneral;


ini_set('display_errors', 'OFF');
require_once __DIR__ . '/../../../../vendor/autoload.php';

date_default_timezone_set('America/Bogota');
header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Access-Control-Allow-Headers, Access-Control-Allow-Credentials,Authentication, Origin, X-Requested-With, Content-Type,x-token , authorization,Authorization');

$session = new SessionGeneral();
$session->inicio_sesion('_s', false);
