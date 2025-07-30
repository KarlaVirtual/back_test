<?php

/**
 * Este archivo contiene un script para interactuar con los servicios de generación virtual.
 * Se utiliza para autenticar y probar la integración con el servicio VIRTUALGENERATIONSERVICES.
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
 * @var mixed $VIRTUALGENERATIONSERVICES Variable que almacena servicios de generación virtual.
 */

error_reporting(E_ALL);
ini_set('display_errors', 'ON');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Ezugi;
use Backend\integrations\casino\VIRTUALGENERATIONSERVICES;

$VIRTUALGENERATIONSERVICES = new VIRTUALGENERATIONSERVICES();
print_r($VIRTUALGENERATIONSERVICES->Auth(1));