<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con PagoEfectivo.
 * Procesa las solicitudes HTTP entrantes, valida las firmas digitales y confirma las transacciones.
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
 * @var mixed $headers                  Variable que almacena los encabezados HTTP de la solicitud.
 * @var mixed $_SERVER                  Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $name                     Variable que almacena el nombre de un archivo, usuario o entidad.
 * @var mixed $value                    Esta variable se utiliza para almacenar y manipular valores asociados a claves.
 * @var mixed $log                      Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $_REQUEST                 Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $body                     Esta variable contiene el contenido del cuerpo de la solicitud HTTP.
 * @var mixed $data                     Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $ConfigurationEnvironment Esta variable se utiliza para almacenar y manipular la configuración del entorno.
 * @var mixed $secretKey                Variable que almacena la clave secreta.
 * @var mixed $PESignature              Variable que almacena la firma digital de PagoEfectivo.
 * @var mixed $PagoEfectivo2            Variable adicional para pagos en efectivo.
 * @var mixed $signatureReq             Variable que almacena la solicitud de firma.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\payment\PagoEfectivo;
use Backend\dto\TransaccionProducto;
use Backend\integrations\payment\PagoEfectivo2;

if ( ! function_exists('getallheaders')) {
    /**
     * Obtiene todos los encabezados HTTP de la solicitud actual.
     *
     * Esta función es una implementación personalizada de `getallheaders` para entornos
     * donde dicha función no está disponible. Recorre las variables del servidor (`$_SERVER`)
     * y extrae los encabezados HTTP, formateándolos en un arreglo asociativo.
     *
     * @return array Un arreglo asociativo donde las claves son los nombres de los encabezados
     *               HTTP y los valores son sus respectivos contenidos.
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
$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . json_encode($_REQUEST);
$log = $log . json_encode(getallheaders());
$log = $log . trim(file_get_contents('php://input'));
fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

$body = file_get_contents('php://input');
$body = str_replace(' ', '', $body);
$body = preg_replace("[\n|\r|\n\r]", "", $body);

$data = json_decode($body);

$headers = getallheaders();

if ($data != "") {
    $ConfigurationEnvironment = new ConfigurationEnvironment();

    if ($ConfigurationEnvironment->isDevelopment()) {
        $secretKey = "wsZOTokNdj+J9e/kApR9fQHAngfzniJRHSGkpTws";
    } else {
        $secretKey = "N6JzYLr5u+9qV5wPgf/CdFNzM9LcWo4xovtQbofG";
    }

    $PESignature = hash_hmac('sha256', $body, $secretKey);


    /* Procesamos */

    $PagoEfectivo2 = new PagoEfectivo2();

    $signatureReq = $headers["Pe-Signature"];

    if ($signatureReq == "") {
        $signatureReq = $headers["PE-Signature"];
    }

    if ($PESignature == $signatureReq) {
        $PagoEfectivo2->confirmation($data);
        print_r("OK");
    } else {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $secretKey = "SR/MKjxZgQnRwMSoKBl6wTjp7RZsmpgZ0Xi5G34L";
        } else {
            $secretKey = "edNBeEg+HGpb5mtn63NFZk7qQ8OMa1qaN8eQb00a";
        }

        $PESignature = hash_hmac('sha256', $body, $secretKey);


        /* Procesamos */

        $PagoEfectivo2 = new PagoEfectivo2();

        $signatureReq = $headers["Pe-Signature"];

        if ($signatureReq == "") {
            $signatureReq = $headers["PE-Signature"];
        }
        if ($signatureReq == "") {
            $signatureReq = $headers["pe-signature"];
        }

        if ($PESignature == $signatureReq) {
            $PagoEfectivo2->confirmation($data);
            print_r("OK");
        }
    }
}
