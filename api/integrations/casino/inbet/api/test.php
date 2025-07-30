<?php

/**
 * Este archivo contiene un script para probar la API del casino 'inbet'.
 *
 * @category   Casino
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 * @access     public
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed  $_REQUEST Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var string $log      Variable que almacena un registro de las solicitudes y datos procesados.
 * @var string $body     Contenido del cuerpo de la solicitud HTTP recibido.
 * @var object $Inbet    Instancia de la clase Inbet utilizada para interactuar con la API.
 * @var mixed  $response Respuesta generada por la operación de autenticación en la API.
 */

error_reporting(E_ALL);
ini_set('display_errors', 'ON');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Inbet;


$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . json_encode($_REQUEST);
$log = $log . trim(file_get_contents('php://input'));
$body = file_get_contents('php://input');


$Inbet = new Inbet("", "13346577889273645363");
$response = $Inbet->Auth();

print ($response);