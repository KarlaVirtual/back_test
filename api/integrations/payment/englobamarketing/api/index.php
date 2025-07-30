<?php

/**
 * Archivo principal de la API de pago 'englobaMarketing' en modo confirmación.
 *
 * Este archivo procesa solicitudes de pago, decodifica parámetros enviados
 * y configura el entorno para la integración con el sistema de pagos.
 *
 * @package ninguno
 * @version ninguna
 * @access  public
 * @author  Karla Ramirez <karla.ramirez@virtualsoft.tech>
 * @date    18.02.2025
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

header('Content-Type: text/html; charset=UTF-8');

use Backend\dto\ConfigurationEnvironment;

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