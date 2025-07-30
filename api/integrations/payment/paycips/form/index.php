<?php

/**
 * Archivo principal para la integración de pagos con PayCIPS.
 *
 * Este archivo se encarga de procesar la información de la transacción,
 * configurar el entorno, y generar la URL de confirmación para el formulario
 * de pago de PayCIPS.
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
 * @var mixed $ConfigurationEnvironment Esta variable se utiliza para almacenar y manipular la configuración del entorno.
 * @var mixed $_GET                     Arreglo global que contiene los datos enviados mediante el método GET.
 * @var mixed $sessiontoken             Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $sessiontokenEncrypt      Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $URLConfirm               Variable que almacena la URL de confirmación.
 * @var mixed $OpMode                   Variable que define el modo de operación.
 * @var mixed $TransaccionProducto      Variable que almacena información sobre una transacción de producto.
 * @var mixed $transaccion_id           Variable que almacena el ID de la transacción.
 * @var mixed $valor                    Variable que almacena un valor monetario o numérico.
 * @var mixed $Usuario                  Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $Mandante                 Esta variable se utiliza para almacenar y manipular el mandante asociado.
 * @var mixed $Subproveedor             Variable que almacena información del subproveedor.
 * @var mixed $Pais                     Variable que almacena el nombre de un país.
 * @var mixed $TxRef1                   Variable que almacena la referencia de la transacción.
 * @var mixed $Detalle                  Variable que almacena detalles adicionales.
 * @var mixed $ContractNo               Variable que almacena el número de contrato.
 * @var mixed $Registro                 Variable que almacena información sobre un registro.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

header('Content-Type: text/html; charset=UTF-8');

use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Pais;
use Backend\dto\Registro;
use Backend\dto\Subproveedor;
use Backend\dto\SubproveedorMandante;
use Backend\dto\TransaccionProducto;
use Backend\dto\Usuario;
use Backend\integrations\payment\PayCIPS;


$ConfigurationEnvironment = new ConfigurationEnvironment();

$_GET["id"] = str_replace(' ', '+', $_GET["id"]);

$sessiontoken = $_GET["id"];
$sessiontoken = $ConfigurationEnvironment->decrypt($sessiontoken);
$sessiontokenEncrypt = $ConfigurationEnvironment->encrypt($sessiontoken);

if (isset($_GET["id"]) && $sessiontoken != '' && is_numeric($sessiontoken)) {
    $URLConfirm = 'https://PayCIPS.com/Test/PayCIPSCheckoutForm';


    if ($ConfigurationEnvironment->isDevelopment()) {
        $URLConfirm = 'https://PayCIPS.com/Test/PayCIPSCheckoutForm';
        $OpMode = 'TEST';
    } else {
        $URLConfirm = 'https://PayCIPS.com/Test/PayCIPSCheckoutForm';
        $OpMode = 'PROD';
    }

    $TransaccionProducto = new TransaccionProducto($sessiontoken);
    $transaccion_id = $TransaccionProducto->transproductoId;
    $valor = $TransaccionProducto->getValor();
    $Usuario = new Usuario($TransaccionProducto->getUsuarioId());
    $Mandante = $Usuario->mandante;
    $Subproveedor = new Subproveedor('', 'PAYCIPS');
    $Pais = new Pais($Usuario->paisId);
    $TxRef1 = $Pais->iso;
    $Subproveedor = new SubproveedorMandante($Subproveedor->getSubproveedorId(), $Mandante, '');
    $Detalle = $Subproveedor->detalle;
    $Detalle = json_decode($Detalle);
    $ContractNo = $Detalle->ContractNo;

    $Registro = new Registro("", $TransaccionProducto->getUsuarioId());

    $URLConfirm = $URLConfirm . "?amount=600MX&TxOrderId={$transaccion_id}&TxRef1={$TxRef1}&ContractNo={$ContractNo}&OpMode={$OpMode}"
    ?>
    <header style="
    /* margin-top: 30%; */
    position: relative;
    display: block;
"><img src="" style="
    /* width: 69%; */
    margin: 8px auto;
    position: relative;
    display: block;
    /* margin-top: 14px; */
    height: 100%;
"></header>
    <body>
    <div id="content" style="background: #7d7d7d;">

        <?php
        print('<iframe frameborder="0" src="' . $URLConfirm . '" class="embed-responsive-item" style="width: 100%;height: 100%;margin-top: -5px;" ></iframe>');
        ?>


    </div>
    </body>

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

        header, footer {
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

        a:hover, button:hover {
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

        button.default {
            /* width: 187px; */
            /* height: 40px; */
            /* font-size: 36px; */
            /* width: 200px; */
            height: 40px;
            width: 187px;
            background-size: cover !important;
        }

        body {
            /*
                        margin: 30% auto;
            */
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
            background: url() no-repeat;
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
            <!--
                        <p>The page you are looking for might have been removed had its name changed or is temporarily unavailable.</p>
                        <a href="#">Go To Homepage</a>
    -->
        </div>
    </div>
    <?php
}
exit();


?>


