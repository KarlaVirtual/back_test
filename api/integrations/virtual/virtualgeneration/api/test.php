<?php

/**
 * Este archivo contiene un ejemplo de integración con servicios de generación virtual.
 * Incluye la configuración inicial, la carga de dependencias y una llamada de ejemplo
 * a un servicio de autenticación.
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
 * @var mixed $VIRTUALGENERATIONSERVICES Variable que almacena servicios de generación virtual.
 */

error_reporting(E_ALL);
ini_set('display_errors', 'ON');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Ezugi;
use Backend\integrations\casino\VIRTUALGENERATIONSERVICES;

$VIRTUALGENERATIONSERVICES = new VIRTUALGENERATIONSERVICES();
print_r($VIRTUALGENERATIONSERVICES->Auth(1));