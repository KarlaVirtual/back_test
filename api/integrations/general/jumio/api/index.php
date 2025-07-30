<?php

/**
 * Este archivo contiene un script para procesar archivos CSV subidos mediante una solicitud HTTP.
 * Realiza operaciones como la validación de datos, integración con servicios externos (Jumio Services),
 * y registro de logs para depuración y seguimiento.
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
 * @var mixed $URI             Esta variable contiene el URI de la petición actual.
 * @var mixed $_SERVER         Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $body            Esta variable contiene el contenido del cuerpo de la solicitud HTTP.
 * @var mixed $data            Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $_FILES          Variable global de PHP que contiene información sobre archivos subidos.
 * @var mixed $fileInfo        Variable que almacena información detallada de un archivo.
 * @var mixed $uploadDir       Variable que almacena el directorio donde se subirán archivos.
 * @var mixed $uploadPath      Variable que almacena la ruta completa del archivo subido.
 * @var mixed $handle          Variable que almacena un identificador de recurso o archivo.
 * @var mixed $lineas          Variable que almacena un conjunto de líneas de datos.
 * @var mixed $numeroFilas     Variable que almacena la cantidad de filas en un conjunto de datos.
 * @var mixed $contador        Variable que almacena un número utilizado para contar elementos.
 * @var mixed $datosRecorridos Variable que almacena los datos procesados en una iteración.
 * @var mixed $columna1        Variable que almacena datos de la primera columna de una tabla o estructura.
 * @var mixed $columna2        Variable que almacena datos de la segunda columna de una tabla o estructura.
 * @var mixed $columna3        Variable que almacena datos de la tercera columna de una tabla o estructura.
 * @var mixed $columna4        Variable que almacena datos de la cuarta columna de una tabla o estructura.
 * @var mixed $userReference   Esta variable se utiliza para almacenar y manipular la referencia del usuario.
 * @var mixed $status          Esta variable se utiliza para almacenar y manipular el estado.
 * @var mixed $accountId       Esta variable indica la cantidad total de elementos o registros, útil para iteraciones o validaciones.
 * @var mixed $workflowId      Variable que almacena el identificador único de un flujo de trabajo.
 * @var mixed $JUMIOSERVICES   Esta variable se utiliza en la integración con Jumio Services para procesos de verificación de identidad.
 * @var mixed $Usuario         Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $UsuarioMandante Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $token           Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $response        Esta variable almacena la respuesta generada por una operación o petición.
 * @var mixed $_ENV            Variable superglobal que contiene el entorno de variables del sistema.
 * @var mixed $log             Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $e               Esta variable se utiliza para capturar excepciones o errores en bloques try-catch.
 */

/*JUMIO*/

use Backend\dto\Clasificador;
use Backend\dto\Registro;
use Backend\dto\UsuarioLog2;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\UsuarioVerificacion;
use Backend\mysql\UsuarioLog2MySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Exception;


require(__DIR__ . '../../../../../vendor/autoload.php');


$URI = $_SERVER['REQUEST_URI'];

$body = file_get_contents('php://input');
$data = json_decode($body);

header('Content-Type: application/json');


try {
// Verificar si se ha enviado un archivo

    if (isset($_FILES['csvfile'])) {
        // Obtener información sobre el archivo
        $fileInfo = $_FILES['csvfile'];

        // Verificar si no hubo errores en la subida
        if ($fileInfo['error'] === UPLOAD_ERR_OK) {
            // Mover el archivo temporal a una ubicación permanente
            $uploadDir = __DIR__;
            $uploadPath = $uploadDir . "/" . basename($fileInfo['name']);

            if (move_uploaded_file($fileInfo['tmp_name'], $uploadPath)) {
                // El archivo se ha movido correctamente, ahora puedes leer su contenido
                // (Aquí puedes utilizar el código de lectura de CSV que discutimos anteriormente)
                if (($handle = fopen($uploadPath, 'r')) !== false) {
                    // Iterar sobre cada línea del archivo


                    if (file_exists($uploadPath)) {
                        // Leer el contenido del archivo
                        $lineas = file($uploadPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

                        // Contar el número de líneas
                        $numeroFilas = count($lineas);
                    }

                    $contador = 0;
                    $datosRecorridos = array();

                    while (($data = fgetcsv($handle, $numeroFilas, ',')) !== false) {
                        try {
                            $contador++;
                            if ($contador >= 2) {
                                // Verificar que la fila tiene exactamente 4 columnas
                                if (count($data) == 4) {
                                    // Obtener los valores de cada columna
                                    $columna1 = $data[0];
                                    $columna2 = $data[1];
                                    $columna3 = $data[2];
                                    $columna4 = $data[3];

                                    $userReference = $columna4;
                                    $status = $columna3;
                                    $accountId = $columna1;
                                    $workflowId = $columna2;

                                    $JUMIOSERVICES = new \Backend\integrations\auth\JUMIOSERVICES();

                                    $Usuario = new \Backend\dto\Usuario($userReference);
                                    $UsuarioMandante = new \Backend\dto\UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);
                                    $token = ($JUMIOSERVICES->accesToken($UsuarioMandante));

                                    $response = $JUMIOSERVICES->process($userReference, $status, $token, $accountId, $workflowId);

                                    if ($_ENV['debug']) {
                                        print_r(' Response ');
                                        print_r($response);
                                        print_r('PATH');
                                        print_r($uploadPath);
                                    }

                                    $log = "";
                                    $log = $log . "/" . time();

                                    $log = $log . "\r\n" . "-------------------------" . "\r\n";
                                    $log = $log . ($response);
                                    fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

                                    $datosRecorridos[] = $data;
                                }
                            }
                        } catch (\Exception $e) {
                            if ($_ENV['debug']) {
                                print_r(' Error ');
                                print_r($e);
                            }
                        }
                    }
                    echo json_encode($datosRecorridos);
                }

                unlink($uploadPath);
            } else {
                echo "Error al mover el archivo: " . error_get_last()['message'];
            }
        } else {
            echo "Error en la subida del archivo";
        }
    } else {
        echo "No se ha enviado ningún archivo";
    }
} catch (Exception $e) {
    if ($_ENV['debug']) {
        print_r(' Error ');
        print_r($e);
    }
}





