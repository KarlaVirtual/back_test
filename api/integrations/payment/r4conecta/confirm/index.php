<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con el sistema R4CONECTA.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payment\R4CONECTA
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $data                     Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $fp                       Variable que almacena información sobre la forma de pago.
 * @var mixed $log                      Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $_REQUEST                 Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $ConfigurationEnvironment Esta variable se utiliza para almacenar y manipular la configuración del entorno.
 * @var mixed $secrekey                 Esta variable se utiliza para almacenar y manipular la clave secreta.
 * @var mixed $sing                     Esta variable se utiliza para almacenar y manipular la firma digital.
 * @var mixed $confirm                  Variable que almacena la confirmación de una operación o transacción.
 * @var mixed $status                   Esta variable se utiliza para almacenar y manipular el estado.
 * @var mixed $transactionID            Variable que almacena el ID de la transacción.
 * @var mixed $orderId                  Variable que almacena el ID de la orden.
 * @var mixed $Amount                   Variable que almacena un monto o cantidad (posible duplicado de amount).
 * @var mixed $R4CONECTA                Variable específica para el sistema R4CONECTA.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\payment\R4CONECTA;
use Backend\integrations\payment\R4CONECTASERVICES;

header('Content-Type: application/json');

$headers = getallheaders();
$URI = $_SERVER['REQUEST_URI'];

if (! function_exists('getallheaders')) {
    /**
     * Obtiene todos los encabezados de la solicitud HTTP.
     *
     * @return array Un arreglo asociativo con los encabezados de la solicitud.
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

$data = file_get_contents('php://input');
$data = json_decode($data);

if (true) {

    $R4CONECTASERVICES = new R4CONECTASERVICES();

    if (strpos($URI, "/R4consulta") !== false) {
        $IdCliente = $data->IdCliente;

        $response = $R4CONECTASERVICES->R4consulta($IdCliente);
    }

    if (strpos($URI, "/R4notifica") !== false) {
        $IdComercio = $data->IdComercio;
        $Monto = $data->Monto;
        $Status = $data->CodigoRed;
        $Referencia = $data->Referencia;

        $response = $R4CONECTASERVICES->createRequestPayment($IdComercio, $Monto, $TelefonoComercio);
        $response = json_decode($response);

        $transactionID = $response->transproductoId;

        if ($response->abono == true && $Status == "00") {
            $R4CONECTA = new R4CONECTA($transactionID, $Referencia, $Monto, $Status);
            $R4CONECTA->confirmation(json_encode($data));

            unset($response->transproductoId);
        }
        $response = json_encode($response);
    }

    print_r($response);
}
