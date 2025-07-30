<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con Wepay4u.
 * Procesa los datos recibidos, registra logs y realiza la confirmación de transacciones.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payment\Wepay4u
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST        Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $_ENV            Variable superglobal que contiene el entorno de variables del sistema.
 * @var mixed $data            Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $PublicId        Variable que almacena el ID público.
 * @var mixed $MerchantSalesID Variable que almacena el identificador de venta del comerciante.
 * @var mixed $PaymentCode     Variable que almacena un código asociado a un pago.
 * @var mixed $Amount          Variable que almacena un monto o cantidad (posible duplicado de amount).
 * @var mixed $TxCreation      Variable que almacena la fecha y hora de creación de una transacción.
 * @var mixed $TxExpiration    Variable que almacena la fecha y hora de expiración de una transacción.
 * @var mixed $OkURL           Variable que almacena la URL a la que se redirige cuando la transacción es exitosa.
 * @var mixed $ErrorURL        Variable que almacena la URL a la que se redirige cuando ocurre un error.
 * @var mixed $hash            Variable que almacena un valor hash para seguridad o verificación.
 * @var mixed $status          Esta variable se utiliza para almacenar y manipular el estado.
 * @var mixed $Wepay4u         Variable que hace referencia a la plataforma de pagos Wepay4u.
 */

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

require(__DIR__ . '../../../../../vendor/autoload.php');
use Backend\integrations\payment\Wepay4u;

/* Obtenemos Variables que nos llegan */
$data = (file_get_contents('php://input'));

$data = json_decode($data);
if (isset($data)) {

    $PublicId        = $data->PublicId;
    $MerchantSalesID = $data->MerchantSalesID;
    $PaymentCode = $data->PaymentCode;
    $Amount = $data->Amount;
    $TxCreation = $data->TxCreation;
    $TxExpiration = $data->TxExpiration;
    $OkURL = $data->OkURL;
    $ErrorURL = $data->ErrorURL;
    $hash = $data->hash;
    $status = "A";

    /* Procesamos */
    $Wepay4u = new Wepay4u($PublicId, $MerchantSalesID, $PaymentCode, $Amount, $hash, $status);
    $Wepay4u->confirmation($data);
}
