<?php

/**
 * Este archivo contiene un script para procesar solicitudes de autenticación
 * en la API del casino 'endorphina'.
 *
 * @category   Casino
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var string $_SERVER    Contiene la URI de la solicitud actual ['REQUEST_URI'].
 * @var string $body       Contiene el cuerpo de la solicitud HTTP.
 * @var object $data       Objeto JSON decodificado del cuerpo de la solicitud.
 * @var string $operatorId Identificador del operador extraído del cuerpo de la solicitud.
 * @var string $token      Token de autenticación extraído del cuerpo de la solicitud.
 * @var object $Ezugi      Instancia de la clase Ezugi para manejar la autenticación.
 * @var string $response   Respuesta generada por el método de autenticación.
 */

ini_set('display_errors', 'off');

require(__DIR__ . '../../../../../../vendor/autoload.php');

use Backend\integrations\casino\Ezugi;


/* Procesamos */

$URI = $_SERVER['REQUEST_URI'];
$body = file_get_contents('php://input');
if ($body != "") {
    $data = json_decode($body);

    $operatorId = $data->operatorId;
    $token = $data->token;


    $Ezugi = new Ezugi("", $token);

    $response = $Ezugi->Auth();

    syslog(LOG_WARNING, "AUTH EZUGI RESPONSE:" . $response);

    print $response;
}










