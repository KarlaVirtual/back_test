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

    $ConfigurationEnvironment = new ConfigurationEnvironment();

    $x_invoice = $ConfigurationEnvironment->DepurarCaracteres($_POST['x_invoice']);

    if ($_GET['id'] != '' && $x_invoice == '') {
        $x_invoice = $_GET['id'];
    }
    if ($_GET['idd'] != '' && $x_invoice == '') {
        $x_invoice = $_GET['idd'];
        $x_invoice = str_replace(" ","+",$x_invoice);

        $x_invoice= $ConfigurationEnvironment->decrypt($x_invoice);

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


        $URLJS = "https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js";
        $URLREDIRECT = "https://partnerapi.virtualsoft.tech/Payment/Return";

        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $URLJS = "https://ravesandboxapi.flutterwave.com/flwv3-pug/getpaidx/api/flwpbf-inline.js";
            $URLREDIRECT = "https://devadmin.virtualsoft.tech/api/api/partner_api/Payment/Return";
        }

        IF ($country == 'NI') {
            $country = 'NG';
        }


        IF ($country == 'EC') {
            $country = 'NG';
        }
        ?>


        <body style="background: hsla(0,0%,88%,.95);text-align: center;">

        <div style="
                background: url(<?= $custom_logo ?>);
                width: 300px;
                height: 100px;
                background-size: contain;
                margin: 20px auto;
                "></div>

        <form>
            <a class="flwpug_getpaid"
               data-PBFPubKey="<?= $PBFPubKey ?>"
               data-txref="<?= $txref ?>"
               data-amount="<?= $amount ?>"
               data-customer_email="<?= $customer_email ?>"
               data-currency="<?= $currency ?>"
               data-pay_button_text="Pagar Ahora"
               data-country="<?= $country ?>"
               data-custom_logo="<?= $custom_logo ?>"
               data-custom_title="<?= $custom_title ?>"
               data-redirect_url="<?=$URLREDIRECT?>"></a>


            <script type="text/javascript" src="<?= $URLJS ?>"></script>
        </form>

        </body>

        <?php
    }

?>