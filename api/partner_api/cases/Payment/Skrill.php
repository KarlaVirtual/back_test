<?php
header('Content-Type: text/html');


use Backend\dto\ProveedorMandante;
use Backend\dto\Registro;
use Backend\dto\UsuarioLog;
use Backend\dto\Usuario;
use Backend\dto\Pais;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\TransaccionProducto;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\dto\Mandante;
use Backend\dto\Producto;

try {


    $ConfigurationEnvironment = new ConfigurationEnvironment();

    $x_invoice = $ConfigurationEnvironment->DepurarCaracteres($_POST['x_invoice']);

    if ($_GET['id'] != '' && $x_invoice == '') {
        $x_invoice = $_GET['id'];
    }

    if ($x_invoice != '' && is_numeric($x_invoice)) {

        $TransaccionProducto = new TransaccionProducto($x_invoice);


        $Usuario = new Usuario($TransaccionProducto->getUsuarioId());
        $Registro = new Registro('', $Usuario->usuarioId);

        $Mandante = new Mandante($Usuario->mandante);

        $Pais = new Pais($Usuario->paisId);

        $Producto = new Producto($TransaccionProducto->getProductoId());

        $ProveedorMandante = new ProveedorMandante($Producto->getProveedorId(), $Usuario->mandante);

        $detalle = json_decode($ProveedorMandante->detalle);

        $PBFPubKey = $detalle->public_key;

        $customer_email = $Usuario->login;
        $customer_firstname = $Registro->nombre1;
        $customer_lastname = $Registro->apellido1;

        $custom_description = 'Deposito';

        $custom_logo = $Mandante->logo;

        $redirect = $Mandante->baseUrl . '/gestion/deposito/pendiente';


        if ($Mandante->mandante == 0) {
            $custom_logo = 'https://images.virtualsoft.tech/site/doradobet/logo-doradobet.png';
        }

        $custom_title = $Mandante->descripcion;

        $amount = $TransaccionProducto->getValor();
        $customer_phone = $Registro->getCelular();
        $country = strtoupper($Pais->iso);

        $currency = strtoupper($Usuario->moneda);

        $txref = $TransaccionProducto->transproductoId;

        $integrity_hash = '6800d2dcbb7a91f5f9556e1b5820096d3d74ed4560343fc89b03a42701da4f30';


        IF ($country == 'NI') {
            $country = 'NG';
        }

        $status_url =  'https://integrations.virtualsoft.tech/payment/skrill/confirm/';

        if($ConfigurationEnvironment->isDevelopment()){
            $status_url = 'https://devadmin.doradobet.com/api/api/integrations/payment/skrill/confirm/';

        }
        // <input type="hidden" name="payment_methods" value="NTL">
        $customer_emailPartner='payments@i-tainment.com';
        ?>


        <body style="background: hsla(0,0%,88%,.95);text-align: center;">

        <div style="
                background: url(<?= $custom_logo ?>);
                width: 300px;
                height: 100px;
                background-size: contain;
                margin: 20px auto;
                "></div>



        <form action="https://pay.skrill.com" method="post" target="_blank">
            <input type="hidden" name="pay_to_email" value="<?=$customer_emailPartner?>">
            <input type="hidden" name="return_url" value="<?= $redirect ?>">
            <input type="hidden" name="return_url_text" value="Retornar al comercio">
            <input type="hidden" name="return_url_target" value="_self">

            <input type="hidden" name="cancel_url" value="<?= $redirect ?>">
            <input type="hidden" name="cancel_url" value="_self">

            <input type="hidden" name="status_url"
                   value="<?=$status_url?>">

            <input type="hidden" name="logo_url" value="<?= $custom_logo ?>">

            <input type="hidden" name="transaction_id" value="<?= $txref ?>">

            <input type="hidden" name="language" value="ES">
            <input type="hidden" name="amount" value="<?= $amount ?>">
            <input type="hidden" name="currency" value="<?= $currency ?>">
            <input type="hidden" name="detail1_description" value="Description:">
            <input type="hidden" name="detail1_text" value="Deposito">
            <input type="hidden" name="recipient_description" value="<?= $Mandante->nombre ?>">


            <input type="submit" value="Pagar"></form>


        </body>

        <?php
    }
}catch (Exception $e){
    print_r($e);
}
?>