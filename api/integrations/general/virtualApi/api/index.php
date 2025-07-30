<?php

/**
 * Este archivo es un punto de entrada para manejar solicitudes API en un entorno PHP.
 *
 * Proporciona funcionalidad para procesar solicitudes HTTP, cargar archivos específicos
 * según el URI, manejar excepciones y generar respuestas en formato JSON o CSV.
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
 * @var mixed $_REQUEST         Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $headers          Variable que almacena los encabezados HTTP de la solicitud.
 * @var mixed $_SERVER          Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $name             Variable que almacena el nombre de un archivo, usuario o entidad.
 * @var mixed $value            Esta variable se utiliza para almacenar y manipular valores asociados a claves.
 * @var mixed $URI              Esta variable contiene el URI de la petición actual.
 * @var mixed $arraySuper       Variable que almacena una lista extendida de datos.
 * @var mixed $filename         Variable que almacena el nombre completo de un archivo.
 * @var mixed $response         Esta variable almacena la respuesta generada por una operación o petición.
 * @var mixed $csv              Variable que almacena datos en formato CSV.
 * @var mixed $keys             Variable que almacena claves de un arreglo o estructura de datos.
 * @var mixed $i                Variable que almacena un índice en una iteración.
 * @var mixed $object           Variable que almacena un objeto genérico.
 * @var mixed $e                Esta variable se utiliza para capturar excepciones o errores en bloques try-catch.
 * @var mixed $params           Variable que contiene los parámetros de una solicitud, generalmente en forma de array.
 * @var mixed $code             Variable que almacena un código de referencia, error o identificación.
 * @var mixed $codeProveedor    Variable que almacena un código asociado a un proveedor.
 * @var mixed $messageProveedor Variable que almacena un mensaje proveniente de un proveedor.
 * @var mixed $message          Variable que almacena un mensaje informativo o de error dentro del sistema.
 */

ini_set("display_errors", "OFF");
if ($_REQUEST["debug"] == "1") {
    error_reporting(E_ALL);
    ini_set("display_errors", "ON");
}
header('Content-type: application/json');

require(__DIR__ . '../../../../../vendor/autoload.php');

try {
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
    $URI = $_SERVER["REQUEST_URI"];
    $arraySuper = explode("/", current(explode("?", $URI)));


    $arraySuper[oldCount($arraySuper) - 2] = ucfirst($arraySuper[oldCount($arraySuper) - 2]);

    if ($arraySuper[oldCount($arraySuper) - 1] != '') {
        $filename = __DIR__ . '/cases/' . $arraySuper[oldCount($arraySuper) - 1] . ".php";

        if (file_exists($filename)) {
            require_once $filename;
        } else {
            throw new Exception("Error General", "9");
        }

        if ($arraySuper[oldCount($arraySuper) - 1] != 'Autenticacion' && $arraySuper[oldCount($arraySuper) - 1] != 'Users' && $arraySuper[oldCount($arraySuper) - 1] != 'RegisterUser') {
            header('Content-Type: application/text');
            setlocale(LC_ALL, 'czech');

            if (json_encode($response) != "[]") {
                $keys = array_keys($response["Data"][0]);
                echo implode(",", $keys);
                echo "\n";

                for ($i = 0; $i < oldCount($response["Data"]); $i++) {
                    $object = $response["Data"][$i];// iterate through the array of objects
                    echo implode(",", $object);
                    echo "\n";
                }
            }
        } else {
            print_r(json_encode($response));
        }
    } else {
        throw new Exception("Error General", "9");
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
        case 50003:
            $codeProveedor = "102";  //credenciales incorrectas
            $messageProveedor = $message;
            break;

        case 50001:
            $codeProveedor = "100";  //campos vacios
            $messageProveedor = $message;
            break;

        case 50005:
            $codeProveedor = "101"; //Usuario no pertence al pais
            $messageProveedor = $message;
            break;
        case 50006:
            $codeProveedor = "101";  //usuario no pertenece al partner
            $messageProveedor = $message;
            break;

        case 50007:
            $codeProveedor = "106";     //nota de retirno no esta activa
            $messageProveedor = $message;
            break;

        case 50008:
            $codeProveedor = "9"; //nota de retiro no puede ser eliminada
            $messageProveedor = $message;
            break;

        case 50009:
            $codeProveedor = "9"; //nota de retiro ya eliminada
            $messageProveedor = $message;
            break;

        case 10018:
            $codeProveedor = "100"; //Codigo de pais incorrecto
            $messageProveedor = $message;
            break;

        case 10001:
            $codeProveedor = "6"; //Transacción ya procesada
            $messageProveedor = $message;
            break;

        case 100000:
            $codeProveedor = "9"; //error general
            $messageProveedor = $message;
            break;
        case 100031:
            $codeProveedor = "9"; //No se puede pagar nota de retiro
            $messageProveedor = $message;
            break;
        case 12:
            $codeProveedor = '10';
            $messageProveedor = 'No Existe la nota de retiro';

            break;
        case 24:
            $codeProveedor = "101"; //no existe el usuario
            $messageProveedor = 'No existe el usuario';

            break;

        case 10:
            $codeProveedor = "100"; // key incorrecta
            $messageProveedor = 'Key incorrecta';

            break;

        case 30003:
            $codeProveedor = "30003"; // key incorrecta
            $messageProveedor = $message;

            break;
        case 30012:
            $codeProveedor = "30012"; // key incorrecta
            $messageProveedor = $message;

            break;

        case 20001:
            $codeProveedor = "20001"; // key incorrecta
            $messageProveedor = 'El Usuario no tiene fondos suficientes para hacer este movimiento';

            break;

        case 87:
            $codeProveedor = "5"; // Transaccion no encontrada
            $messageProveedor = 'Transaccion no encontrada';

            break;

        case 300163:
            $codeProveedor = "300163";
            $messageProveedor = 'The email is already registered';

            break;

        case 300164:
            $codeProveedor = "300164";
            $messageProveedor = 'The identification number is already registered';

            break;

        case 300165:
            $codeProveedor = "300165";
            $messageProveedor = 'The phone number is already registered';

            break;

        case 300166:
            $codeProveedor = "300166";
            $messageProveedor = 'Invalid format. An email is expected';

            break;

        case 300167:
            $codeProveedor = "300167";
            $messageProveedor = 'The phone number must have 9 digits';

            break;

        case 300170:
            $codeProveedor = "300170";
            $messageProveedor = 'Incorrect authentication';

            break;

        default:
            $codeProveedor = '9';
            $messageProveedor = 'Error General (' . ($e->getCode()) . ')';

            break;
    }


    $response["Error"] = true;
    $response["Mensaje"] = $messageProveedor;
    $response["TotalCount"] = 0;
    $response["Data"] = [];
    print_r(json_encode($response));
}
