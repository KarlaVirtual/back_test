<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con PayU.
 *
 * Procesa los datos recibidos en la solicitud, valida la firma digital y realiza
 * las acciones necesarias para confirmar la transacción. También registra logs
 * para facilitar la depuración y el seguimiento de las operaciones.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables superglobales de PHP:
 *
 * @var mixed $_REQUEST                 Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $_ENV                     Variable superglobal que contiene el entorno de variables del sistema.
 * @var mixed $ConfigurationEnvironment Esta variable se utiliza para almacenar y manipular la configuración del entorno.
 * @var mixed $ApiKey                   Variable que almacena la clave de API.
 * @var mixed $IdComercio               Variable que almacena el ID del comercio.
 * @var mixed $param                    Variable que define un parámetro.
 * @var mixed $paramsArray              Variable que almacena un arreglo de parámetros.
 * @var mixed $data                     Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $value                    Esta variable se utiliza para almacenar y manipular valores asociados a claves.
 * @var mixed $stringValue              Variable que almacena un valor en formato de texto.
 * @var mixed $parts                    Variable que almacena partes o secciones de un dato.
 * @var mixed $new_value                Variable que almacena un nuevo valor.
 * @var mixed $signature                Esta variable se utiliza para almacenar y manipular la firma digital.
 * @var mixed $fp                       Variable que almacena información sobre la forma de pago.
 * @var mixed $log                      Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $response                 Esta variable almacena la respuesta generada por una operación o petición.
 * @var mixed $confirm                  Variable que almacena la confirmación de una operación o transacción.
 * @var mixed $invoice                  Variable que almacena el identificador de una factura.
 * @var mixed $result                   Variable que almacena el resultado de una operación o transacción.
 * @var mixed $documento_id             Variable que almacena el identificador de un documento.
 * @var mixed $valor                    Variable que almacena un valor monetario o numérico.
 * @var mixed $usuario_id               Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $control                  Variable que almacena un código de control para una operación.
 * @var mixed $Payu                     Variable que almacena información relacionada con Payu.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\payment\Payu;

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

header('Content-Type: application/json');

$param = file_get_contents('php://input');
$param = parse_str($param, $paramsArray);
$data = json_encode($paramsArray, JSON_PRETTY_PRINT);
$data = json_decode($data);

$value = $data->value;

// Redondear a dos decimales
$value = round($value, 2);

// Convertir el valor a string con dos decimales
$stringValue = number_format($value, 2, '.', '');

// Dividir el valor en parte entera y decimal
$parts = explode('.', $stringValue);

// Validar si el segundo decimal es '00'
if (isset($parts[1]) && $parts[1] === '00') {
    // Si es '00', formatear el valor con un solo decimal
    $new_value = number_format($value, 1, '.', '');
} else {
    // Si no, mantener los dos decimales
    $new_value = $stringValue;
}


$fp = fopen('log_' . date("Y-m-d") . '.log', 'a');

$log = " /" . time();
$log = $log . "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
$log = $log . json_encode($_REQUEST);
$log = $log . trim(file_get_contents('php://input'));

fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

$confirm = ($data);
$invoice = $confirm->reference_sale;
$result = $confirm->state_pol;
$documento_id = $confirm->transaction_id;
$valor = $confirm->amount;
$currency = $confirm->currency;
$state_pol = $confirm->state_pol;
$usuario_id = "";
$control = "";

$Payu = new Payu($invoice, $usuario_id, $documento_id, $valor, $control, $result);
$signature = $Payu->getSign($new_value, $currency, $state_pol);

syslog(LOG_WARNING, "PAYU SIGNATURE: " . $signature . " DATA SIGNATURE: " . $data->sign);

if ($signature == $data->sign) {
    $response = [
        'status' => 'OK',
        'message' => 'Sucess'
    ];

    /* Procesamos */
    $Payu = new Payu($invoice, $usuario_id, $documento_id, $valor, $control, $result);
    $Payu->confirmation(json_encode($data));

    http_response_code(200);
} else {
    $response = [
        'status' => 'error',
        'message' => 'inválid signature'
    ];
    http_response_code(403);
}

echo json_encode($response);