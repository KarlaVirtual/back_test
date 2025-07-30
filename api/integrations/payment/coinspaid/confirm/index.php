<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con CoinsPaid.
 * Procesa datos entrantes, registra logs y realiza operaciones relacionadas con facturas y transacciones.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-02-06
 */

/**
 * Descripción de variables globales:
 *
 * @var mixed $data                     Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $fp                       Variable que almacena información sobre la forma de pago.
 * @var mixed $log                      Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $confirm                  Variable que almacena la confirmación de una operación o transacción.
 * @var mixed $invoice_valor            Variable que almacena el valor de una factura.
 * @var mixed $invoice                  Variable que almacena el identificador de una factura.
 * @var mixed $result                   Variable que almacena el resultado de una operación o transacción.
 * @var mixed $documento_id             Variable que almacena el identificador de un documento.
 * @var mixed $valor                    Variable que almacena un valor monetario o numérico.
 * @var mixed $usuario_id               Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $control                  Variable que almacena un código de control para una operación.
 * @var mixed $CoinsPaid                Variable que almacena información relacionada con CoinsPaid.
 * @var mixed $ConfigurationEnvironment Esta variable se utiliza para almacenar y manipular la configuración del entorno.
 * @var mixed $url                      Esta variable se utiliza para almacenar y manipular una URL genérica.
 * @var mixed $curl                     Variable que almacena una solicitud o respuesta CURL.
 * @var mixed $response                 Esta variable almacena la respuesta generada por una operación o petición.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\payment\CoinsPaid;


/* Obtenemos Variables que nos llegan */
header('Content-Type: application/json');


$data = (file_get_contents('php://input'));

$fp = fopen('log_' . date("Y-m-d") . '.log', 'a');
$log = " /" . time();
$log = $log . "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
$log = $log . ($data);

fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


if ($data != '') {
    $data = json_decode($data);

    $confirm = ($data);

    if ($confirm->type == 'invoice') {
        $invoice_valor = $confirm->foreign_id;
        $invoice_valor = explode("/", $invoice_valor);
        $invoice = $invoice_valor[0];
        $result = $confirm->status;
        if ($result == 'failed' || $result == 'not_confirmed') {
            $result = 'cancelled';
        }
        $documento_id = "";
        if ($confirm->transactions !== null && oldCount($confirm->transactions) > 0) {
            $documento_id = $confirm->transactions[0];

            $documento_id = $documento_id->txid;
        } else {
            if ($confirm->transactions != null) {
                $documento_id = $confirm->transactions->id;
            }
        }

        $valor = 0;
        $usuario_id = "";
        $control = "";

        /* Procesamos */
        $CoinsPaid = new CoinsPaid($invoice, $usuario_id, $documento_id, $valor, $control, $result);
        $CoinsPaid->confirmation(json_encode($data));
    } else {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $url = 'https://apidev.virtualsoft.tech/integrations/payout/coinspaid/confirm/';
        } else {
            $url = 'https://integrations.virtualsoft.tech/payout/coinspaid/confirm/';
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($confirm),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }
}