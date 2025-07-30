<?php

/**
 * Archivo principal para la integración de pagos con InternPay.
 *
 * Este archivo se encarga de procesar solicitudes GET, decodificar parámetros
 * y configurar el entorno para la integración con servicios de pago.
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
 * @var mixed $_GET                     Arreglo global que contiene los datos enviados mediante el método GET.
 * @var mixed $ConfigurationEnvironment Esta variable se utiliza para almacenar y manipular la configuración del entorno.
 * @var mixed $id                       Variable que almacena un identificador genérico.
 * @var mixed $params                   Variable que contiene los parámetros de una solicitud, generalmente en forma de array.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

header('Content-Type: text/html; charset=UTF-8');

use Backend\dto\Pais;
use Backend\dto\Usuario;
use Backend\dto\Registro;
use Backend\dto\Mandante;
use Backend\dto\Subproveedor;
use Backend\dto\TransaccionProducto;
use Backend\dto\SubproveedorMandante;
use Backend\integrations\payment\PayCIPS;
use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\payment\PAYPHONEPAYSERVICES;

if (isset($_GET["id"])) {
    $ConfigurationEnvironment = new ConfigurationEnvironment();

    $id = $_GET["id"];
    $id = base64_decode($id);

    $params = explode("##", $id);
}
?>

<head>
    <link rel="stylesheet" href="https://cdn.payphonetodoesposible.com/box/v1.1/payphone-payment-box.css">
    <script type='module' src="https://cdn.payphonetodoesposible.com/box/v1.1/payphone-payment-box.js"></script>
</head>

<body style="background-color: #989898;">
<script>
    window.addEventListener('DOMContentLoaded', () => {

        ppb = new PPaymentButtonBox({
            token: '<?=$params[1]?>',
            amount: <?=$params[2]?>,
            amountWithoutTax: <?=$params[2]?>,
            amountWithTax: 0,
            tax: 0,
            service: 0,
            tip: 0,
            storeId: "<?=$params[3]?>",
            reference: "<?=$params[4]?>",
            currency: '<?=$params[5]?>',
            clientTransactionId: '<?=$params[6]?>',
            lang: '<?=$params[7]?>',
            defaultMethod: "card",
            showPaymentMethodSelector: true,
            showCardPayment: true,
            showPayphonePayment: true,
            showMainButton: true,

        }).render('pp-button');

    })
</script>
<div id="pp-button"/>
</body>