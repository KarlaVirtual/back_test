<?php

/**
 * Este archivo implementa una API general para manejar solicitudes HTTP y procesar datos.
 *
 * Proporciona funcionalidad para manejar encabezados, procesar el cuerpo de las solicitudes,
 * cargar archivos específicos según el URI, y gestionar errores de manera centralizada.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-02-06
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST         Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $headers          Variable que almacena los encabezados HTTP de la solicitud.
 * @var mixed $_SERVER          Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $name             Variable que almacena el nombre de un archivo, usuario o entidad.
 * @var mixed $value            Esta variable se utiliza para almacenar y manipular valores asociados a claves.
 * @var mixed $body             Esta variable contiene el contenido del cuerpo de la solicitud HTTP.
 * @var mixed $URI              Esta variable contiene el URI de la petición actual.
 * @var mixed $arraySuper       Variable que almacena una lista extendida de datos.
 * @var mixed $data             Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $extension        Variable que almacena la extensión de un archivo.
 * @var mixed $filename         Variable que almacena el nombre completo de un archivo.
 * @var mixed $response         Esta variable almacena la respuesta generada por una operación o petición.
 * @var mixed $e                Esta variable se utiliza para capturar excepciones o errores en bloques try-catch.
 * @var mixed $params           Variable que contiene los parámetros de una solicitud, generalmente en forma de array.
 * @var mixed $code             Variable que almacena un código de referencia, error o identificación.
 * @var mixed $codeProveedor    Variable que almacena un código asociado a un proveedor.
 * @var mixed $messageProveedor Variable que almacena un mensaje proveniente de un proveedor.
 * @var mixed $message          Variable que almacena un mensaje informativo o de error dentro del sistema.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../vendor/autoload.php');
header('Content-Type: application/json');

try {
    if ($_REQUEST["debug"] == "1") {
        error_reporting(E_ALL);
        ini_set("display_errors", "ON");
    }

    if ( ! function_exists('getallheaders')) {
        /**
         * Obtiene todos los encabezados HTTP de la solicitud actual.
         *
         * Esta función es una implementación personalizada de `getallheaders` para entornos
         * donde la función nativa no está disponible. Recorre la variable superglobal `$_SERVER`
         * para construir un array asociativo con los encabezados HTTP.
         *
         * @return array Un array asociativo donde las claves son los nombres de los encabezados
         *               y los valores son los valores correspondientes de los encabezados.
         */
        function getallheaders()
        {
            $headers = [];
            foreach ($_SERVER as $name => $value) {
                if (substr($name, 0, 5) == 'HTTP_') {
                    $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                }
            }
            return $headers;
        }
    }

    $body = file_get_contents('php://input');

    if (true) {
        $URI = $_SERVER["REQUEST_URI"];
        $arraySuper = explode("/", current(explode("?", $URI)));

        $data = json_decode($body);
        $extension = "";
        $filename = __DIR__ . '/method/' . $arraySuper[oldCount($arraySuper) - 1] . ".php";

        if (file_exists($filename)) {
            require $filename;
        } else {
            $response["code"] = 12;
            $response['msg'] = "General Error";
        }
    }
} catch (Exception $e) {
    if ($_REQUEST['isDebug'] == '1') {
        print_r($e);
    }

    $code = $e->getCode();

    $codeProveedor = "";
    $messageProveedor = "";
    $message = $e->getMessage();

    $response = array();

    switch ($code) {
        default:
            $codeProveedor = '9';
            $messageProveedor = 'Error General (' . ($e->getCode()) . ')';

            break;
    }


    $response["code"] = $codeProveedor;
    $response["msg"] = $messageProveedor;
}

if (json_encode($response) != "[]") {
    print_r(json_encode($response));
}
