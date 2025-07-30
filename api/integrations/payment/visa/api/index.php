<?php

/**
 * Este archivo contiene la lógica para procesar pagos a través de la integración con Visa.
 * Se encarga de manejar la configuración del entorno, la validación de tokens de sesión,
 * y la inicialización de transacciones de productos.
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
 * @var mixed $ConfigurationEnvironment    Esta variable se utiliza para almacenar y manipular la configuración del entorno.
 * @var mixed $_GET                        Arreglo global que contiene los datos enviados mediante el método GET.
 * @var mixed $sessiontoken                Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $sessiontokenEncrypt         Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $URLConfirm                  Variable que almacena la URL de confirmación.
 * @var mixed $URLtimeouturl               Variable que almacena la URL a donde se redirige en caso de tiempo de espera.
 * @var mixed $URLJS                       Variable que almacena la URL de un archivo JavaScript relacionado con la transacción.
 * @var mixed $TransaccionProducto         Variable que almacena información sobre una transacción de producto.
 * @var mixed $TransaccionProductoMySqlDAO Variable que representa la capa de acceso a datos MySQL para transacciones de productos.
 * @var mixed $valor                       Variable que almacena un valor monetario o numérico.
 * @var mixed $Visa                        Variable que hace referencia al sistema de pagos Visa.
 * @var mixed $token                       Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $Registro                    Variable que almacena información sobre un registro.
 * @var mixed $response                    Esta variable almacena la respuesta generada por una operación o petición.
 * @var mixed $responsejson                Variable que almacena la respuesta de una transacción en formato JSON.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

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

$_GET["id"] = str_replace(' ', '+', $_GET["id"]);

$sessiontoken = $_GET["id"];
$sessiontoken = $ConfigurationEnvironment->decrypt($sessiontoken);
$sessiontokenEncrypt = $ConfigurationEnvironment->encrypt($sessiontoken);

if (isset($_GET["id"]) && $sessiontoken != '' && is_numeric($sessiontoken)) {
    $URLConfirm = 'https://visa.virtualsoft.tech/confirm/';
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

    $valor = $TransaccionProducto->getValor() + $TransaccionProducto->getImpuesto();
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
"><img src="https://images.virtualsoft.tech/site/doradobet/logoneww.png" style="
    /* width: 69%; */
    margin: 8px auto;
    position: relative;
    display: block;
    /* margin-top: 14px; */
    height: 100%;
"></header>

    <body>
    <div id="content" style="background: #7d7d7d;">
        <form action="<?= $URLConfirm ?>?tp=<?php
        echo $sessiontokenEncrypt . ""; ?>&pn=<?php
        echo $sessiontoken . ""; ?>"
              method="post" class="ng-pristine ng-valid">
            <script src="<?= $URLJS ?>"
                    data-sessiontoken="<?php
                    echo $responsejson->sessionKey; ?>"
                    data-channel="web"
                    data-merchantid="<?= $Visa->getMerchantId() ?>" data-merchantlogo="https://images.virtualsoft.tech/site/doradobet/doradobet-borde-azul.png" data-formbuttoncolor="#D80000"
                    data-purchasenumber="<?php
                    echo $sessiontoken . ""; ?>" data-amount="<?php
            echo $valor; ?>"
                    data-cardholdername="<?php
                    echo $Registro->nombre1 . ' ' . $Registro->nombre2; ?>"
                    data-cardholderlastname="<?php
                    echo $Registro->apellido1 . ' ' . $Registro->apellido2; ?>"
                    data-cardholderemail="<?php
                    echo $Registro->email; ?>"
                    data-expirationminutes='20'
                    data-timeouturl='<?= $URLtimeouturl ?>'>
            </script>
        </form>
    </div>
    </body>
    <footer style="
    padding: 40px;
">
        <img src="https://images.virtualsoft.tech/providers/visanet.png" style="
    /* width: 69%; */
    margin: 8px auto;
    position: relative;
    display: block;
    margin-top: 14px;
        height: 100%;
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
            height: 40px;
            padding: 15px;
        }

        div#content {
            border: 1px solid #a2a2a2;
            text-align: center;
            padding: 50px;
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
            border-radius: 5px;
        }

        button.default {
            height: 40px;
            width: 187px;
            background-size: cover !important;
        }

        body {
            width: 100%;
            max-width: 600px;
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