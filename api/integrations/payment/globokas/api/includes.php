<?php

/**
 * Archivo de integración para la API de pagos de Globokas.
 *
 * Este archivo configura el entorno, inicializa sesiones y define los encabezados
 * necesarios para permitir el acceso a la API desde diferentes orígenes.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Documentación de Variables Globales:
 *
 * @var mixed $_SERVER Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $session Variable que almacena datos de sesión del usuario.
 */

ini_set('display_errors', 'OFF');
require_once __DIR__ . '/../../../../vendor/autoload.php';

use Backend\utils\SessionGeneral;


date_default_timezone_set('America/Bogota');
header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);


$session = new SessionGeneral();
$session->inicio_sesion('_s', false);
