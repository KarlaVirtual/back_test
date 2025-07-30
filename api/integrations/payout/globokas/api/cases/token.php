<?php

/**
 * Este archivo contiene un script para la generación de un token JWT basado en las credenciales
 * proporcionadas por el usuario. El token se utiliza para autenticar y autorizar solicitudes
 * dentro del sistema.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payout\Globokas
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $params                   Variable que contiene los parámetros de una solicitud, generalmente en forma de array.
 * @var mixed $ConfigurationEnvironment Esta variable se utiliza para almacenar y manipular la configuración del entorno.
 * @var mixed $usuario                  Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $clave                    Esta variable guarda la clave o contraseña para autenticación y acceso seguro al sistema (generalmente encriptada).
 * @var mixed $header                   Variable que almacena un encabezado HTTP individual.
 * @var mixed $payload                  Variable que almacena los datos del cuerpo de una solicitud, usualmente en JSON.
 * @var mixed $key                      Esta variable se utiliza para almacenar y manipular claves genéricas.
 * @var mixed $signature                Esta variable se utiliza para almacenar y manipular la firma digital.
 * @var mixed $token                    Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $response                 Esta variable almacena la respuesta generada por una operación o petición.
 */

use Backend\dto\ConfigurationEnvironment;

$params = file_get_contents('php://input');
$params = json_decode($params);
require(__DIR__ . '../../../../../../vendor/autoload.php');
header('Content-Type: application/json');

$ConfigurationEnvironment = new ConfigurationEnvironment();


if ($ConfigurationEnvironment->isDevelopment()) {
    $usuario = 'VirtualSoft';
    $clave = 'cDMycmuNvIC%';
} else {
    $usuario = 'VirtualSoft';
    $clave = 'ODHoEcG%SMSF';
}


if ($params->user === $usuario && $params->password === $clave) {
    date_default_timezone_set("America/Bogota");


    $header = json_encode([
        'alg' => 'HS256',
        'typ' => 'JWT'
    ]);

    $payload = json_encode([
        'codigo' => 0,
        'mensaje' => 'OK',
        "usuario" => $usuario
    ]);

    $key = 'VirtualSoft';

    $signature = hash('sha256', $header . $payload . $key);

    $token = base64_encode($header) . '.' . base64_encode($payload) . '.' . $signature;

    $response = array();
    $response["success"] = true;
    $response["data"] = array("token" => $token);
    $response["mensaje"] = "OK";
} else {
    throw new Exception("Login con contraseña incorrectos", "30003");
}




