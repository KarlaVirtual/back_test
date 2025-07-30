<?php

/**
 * Index de la api de payment 'visa' en modo confirmación
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 * @date 06.09.17
 *
 */

require(__DIR__ . '../../../../vendor/autoload.php');

header('Content-Type: text/html; charset=UTF-8');

use Backend\dto\Usuario;
use Backend\dto\Subproveedor;
use Backend\dto\TransaccionProducto;
use Backend\integrations\payment\Visa;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransaccionProductoMySqlDAO;

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

$ConfigurationEnvironment = new ConfigurationEnvironment();

$sessiontoken = $_GET["id"];
if ($sessiontoken != "") {
    $sessiontoken = str_replace(" ", "+", $sessiontoken);
    $sessiontoken = $ConfigurationEnvironment->decrypt($sessiontoken);
    $sessiontokenEncrypt = $ConfigurationEnvironment->encrypt($sessiontoken);
}

if (isset($_GET["id"]) && $sessiontoken != '') {

    $URLConfirm = 'https://integrations.virtualsoft.tech/payment/visa/api/confirm/';
    $URLtimeouturl = 'https://doradobet.com/';
    $URLJS = 'https://static-content.vnforapps.com/v2/js/checkout.js';

    if ($ConfigurationEnvironment->isDevelopment()) {
        $URLConfirm = 'https://devadmin.doradobet.com/api/api/integrations/payment/visa/api/confirm/';
        $URLtimeouturl = 'https://devadmin.doradobet.com/doradobetdev/';
        $URLJS = 'https://static-content-qas.vnforapps.com/v2/js/checkout.js?qa=true';
    }

    $TransaccionProducto = new TransaccionProducto($sessiontoken);
    if ($TransaccionProducto->getEstadoProducto() != "P") {

?>

        <style>
            * {
                -webkit-box-sizing: border-box;
                box-sizing: border-box;
            }

            body {
                padding: 0;
                margin: 0;
            }

            #notfound {
                position: relative;
                height: 100vh;
            }

            #notfound .notfound {
                position: absolute;
                left: 50%;
                top: 30%;
                -webkit-transform: translate(-50%, -50%);
                -ms-transform: translate(-50%, -50%);
                transform: translate(-50%, -50%);
            }

            .notfound {
                max-width: 410px;
                width: 100%;
                text-align: center;
            }

            .notfound .notfound-404 {
                height: 280px;
                position: relative;
                z-index: -1;
            }

            .notfound .notfound-404 h1 {
                font-family: 'Montserrat', sans-serif;
                font-size: 230px;
                margin: 0px;
                font-weight: 900;
                position: absolute;
                left: 50%;
                -webkit-transform: translateX(-50%);
                -ms-transform: translateX(-50%);
                transform: translateX(-50%);
                background: url('../img/bg.jpg') no-repeat;
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-size: cover;
                background-position: center;
            }

            .notfound h2 {
                font-family: 'Montserrat', sans-serif;
                color: #000;
                font-size: 24px;
                font-weight: 700;
                text-transform: uppercase;
                margin-top: 0;
            }

            .notfound p {
                font-family: 'Montserrat', sans-serif;
                color: #000;
                font-size: 14px;
                font-weight: 400;
                margin-bottom: 20px;
                margin-top: 0px;
            }

            .notfound a {
                font-family: 'Montserrat', sans-serif;
                font-size: 14px;
                text-decoration: none;
                text-transform: uppercase;
                background: #0046d5;
                display: inline-block;
                padding: 15px 30px;
                border-radius: 40px;
                color: #fff;
                font-weight: 700;
                -webkit-box-shadow: 0px 4px 15px -5px #0046d5;
                box-shadow: 0px 4px 15px -5px #0046d5;
            }


            @media only screen and (max-width: 767px) {
                .notfound .notfound-404 {
                    height: 142px;
                }

                .notfound .notfound-404 h1 {
                    font-size: 112px;
                }
            }
        </style>

        <div id="notfound">
            <div class="notfound">
                <h2>404 - Page not found</h2>
            </div>
        </div>
    <?php
        exit();
    }

    $TransaccionProducto->setEstadoProducto('E');
    $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
    $TransaccionProductoMySqlDAO->update($TransaccionProducto);
    $TransaccionProductoMySqlDAO->getTransaction()->commit();

    $Usuario = new Usuario($TransaccionProducto->usuarioId);

    $Subproveedor = new Subproveedor("", "VISA");
    $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $Usuario->mandante, $Usuario->paisId);
    $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

    $url = $Credentials->URL;
    $merchant = $Credentials->MERCHANT;
    $Key = $Credentials->KEY;
    $secretKey = $Credentials->SECRET_KEY;
    $user = $Credentials->USER;
    $password = $Credentials->PASSWORD;

    $valor = $TransaccionProducto->getValor();
    $Visa = new Visa($sessiontoken . "", "", $valor, $sessiontoken, $url, $merchant, $Key, $secretKey, $user, $password);
    $token = $Visa->createToken();

    $Registro = new \Backend\dto\Registro("", $TransaccionProducto->getUsuarioId());

    $response = $Visa->Authorization($token);
    $responsejson = json_decode($response);

    ?>
    <header style="
    /* margin-top: 30%; */
    position: relative;
    display: block;
    padding: 10px;
"><img src="https://images.virtualsoft.tech/site/doradobet/logoneww.png" style="
    width: 69%;
    margin: 8px auto;
    position: relative;
    display: block;
    margin-top: 14px;
    height: auto;
"></header>
    <div id="content" style="background: #7d7d7d;">
        <form action="<?= $URLConfirm ?>?tp=<?php echo $sessiontokenEncrypt . ""; ?>&pn=<?php echo $sessiontoken . ""; ?>"
            method="post" class="ng-pristine ng-valid">
            <script src="<?= $URLJS ?>"
                data-sessiontoken="<?php echo $responsejson->sessionKey; ?>"
                data-channel="web"
                data-merchantid="<?= $Visa->getMerchantId() ?>" data-merchantlogo="https://images.virtualsoft.tech/site/doradobet/logoneww.png" data-formbuttoncolor="#D80000"
                data-purchasenumber="<?php echo $sessiontoken . ""; ?>" data-amount="<?php echo $valor; ?>"
                data-cardholdername="<?php echo $Registro->nombre1 . ' ' . $Registro->nombre2; ?>"
                data-cardholderlastname="<?php echo $Registro->apellido1 . ' ' . $Registro->apellido2; ?>"
                data-cardholderemail="<?php echo $Registro->email; ?>"
                data-expirationminutes='20'
                data-timeouturl='<?= $URLtimeouturl ?>'></script>
        </form>
    </div>
    <footer>
        <img src="https://www.visanet.com.pe/wp-content/uploads/2016/03/logo.png" style="
    /* width: 69%; */
    margin: 8px auto;
    position: relative;
    display: block;
    margin-top: 14px;
    height: auto;
" class="">
    </footer>

    <style>
        table {
            padding: 10px;
            border-radius: 5px;
            margin: 0 auto;
        }

        tr td:nth-child(2) {
            background: white;
        }


        tr td:nth-child(1) {
            background: #ffffff;
            color: black;
            padding: 5px;
            width: 269px;
            font-weight: bold;
        }

        tr td:nth-child(2) {
            background: white;
        }

        header,
        footer {
            background: #200061;
            height: 80px;
        }

        footer {
            padding: 10px 0px;
        }

        div#content {
            border: 1px solid #a2a2a2;
            text-align: center;
            padding: 20px;
        }

        div#buttons div {
            width: 40%;
            border-radius: 8px;
            padding: 10px;
            float: left;
            text-align: center;
            margin: 0px 10px;
        }

        button {
            background: white;
            border-radius: 5px;
            padding: 10px 25px;
            cursor: pointer;
        }

        tr {
            border-bottom: 1px solid #000;
            display: block;
        }

        a {
            text-decoration: none;
            color: black;
            background: white;
            padding: 13px 10px;
            border-radius: 5px;
        }

        a:hover,
        button:hover {
            background: #000000;
            color: white;
        }

        body {
            background: #ffffff;
            width: 500px;
            margin: 0 auto;
            /* border: 1px solid #000; */
            border-radius: 5px;
        }
    </style>
<?php

} else {
?>

    <style>
        * {
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
        }

        body {
            padding: 0;
            margin: 0;
        }

        #notfound {
            position: relative;
            height: 100vh;
        }

        #notfound .notfound {
            position: absolute;
            left: 50%;
            top: 30%;
            -webkit-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
        }

        .notfound {
            max-width: 410px;
            width: 100%;
            text-align: center;
        }

        .notfound .notfound-404 {
            height: 280px;
            position: relative;
            z-index: -1;
        }

        .notfound .notfound-404 h1 {
            font-family: 'Montserrat', sans-serif;
            font-size: 230px;
            margin: 0px;
            font-weight: 900;
            position: absolute;
            left: 50%;
            -webkit-transform: translateX(-50%);
            -ms-transform: translateX(-50%);
            transform: translateX(-50%);
            background: url('../img/bg.jpg') no-repeat;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-size: cover;
            background-position: center;
        }

        .notfound h2 {
            font-family: 'Montserrat', sans-serif;
            color: #000;
            font-size: 24px;
            font-weight: 700;
            text-transform: uppercase;
            margin-top: 0;
        }

        .notfound p {
            font-family: 'Montserrat', sans-serif;
            color: #000;
            font-size: 14px;
            font-weight: 400;
            margin-bottom: 20px;
            margin-top: 0px;
        }

        .notfound a {
            font-family: 'Montserrat', sans-serif;
            font-size: 14px;
            text-decoration: none;
            text-transform: uppercase;
            background: #0046d5;
            display: inline-block;
            padding: 15px 30px;
            border-radius: 40px;
            color: #fff;
            font-weight: 700;
            -webkit-box-shadow: 0px 4px 15px -5px #0046d5;
            box-shadow: 0px 4px 15px -5px #0046d5;
        }

        @media only screen and (max-width: 767px) {
            .notfound .notfound-404 {
                height: 142px;
            }

            .notfound .notfound-404 h1 {
                font-size: 112px;
            }
        }
    </style>

    <div id="notfound">
        <div class="notfound">
            <h2>404 - Page not found</h2>
        </div>
    </div>
<?php
}
exit();

?>