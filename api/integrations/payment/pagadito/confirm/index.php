<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con el sistema Pagadito.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payment\Pagadito
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_ENV         Variable superglobal que contiene el entorno de variables del sistema.
 * @var mixed $debugFixed   Variable utilizada para almacenar información de depuración corregida.
 * @var mixed $data         Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $log          Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $_REQUEST     Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $confirm      Variable que almacena la confirmación de una operación o transacción.
 * @var mixed $invoice      Variable que almacena el identificador de una factura.
 * @var mixed $result       Variable que almacena el resultado de una operación o transacción.
 * @var mixed $documento_id Variable que almacena el identificador de un documento.
 * @var mixed $valor        Variable que almacena un valor monetario o numérico.
 * @var mixed $usuario_id   Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $control      Variable que almacena un código de control para una operación.
 * @var mixed $Pagadito     Variable relacionada con el sistema de pagos Pagadito.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\payment\Pagadito;

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

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

// obtener headers
$headers = getallheaders();

// obtener data
$data = file_get_contents('php://input');

$data = json_decode($data);

if (isset($data)) {
    $codigo = $data->resource->ern;
    $partes = preg_split('/([A-Za-z]+)/', $codigo, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
    $invoice = $partes[2];

    $external_id = $data->resource->reference;
    $valor = $data->resource->amount->total;
    $response_code = $data->resource->status;

    $result = "PENDIENTE";
    if ($response_code == 'COMPLETED') {
        $result = "APROBADO";
    }

    $usuario_id = "";
    $control = "";

    /* Procesamos */
    $Pagadito = new Pagadito($invoice, $usuario_id, $external_id, $valor, $control, $result);
    $Pagadito->confirmation(json_encode($data));
}
