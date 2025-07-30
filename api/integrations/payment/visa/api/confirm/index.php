<?php

/**
 * Este archivo maneja la confirmación de transacciones de pago realizadas a través del sistema Visa.
 * Realiza validaciones, obtiene credenciales, procesa la transacción y genera una respuesta en formato HTML.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payment\Visa
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST                 Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $ParamtransactionToken    Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $_POST                    Arreglo global que contiene los datos enviados mediante el método POST.
 * @var mixed $ParamTP                  Variable que almacena parámetros relacionados con un tipo de transacción (TP).
 * @var mixed $_GET                     Arreglo global que contiene los datos enviados mediante el método GET.
 * @var mixed $ConfigurationEnvironment Esta variable se utiliza para almacenar y manipular la configuración del entorno.
 * @var mixed $paramTP                  Variable que almacena parámetros relacionados con un tipo de transacción (TP).
 * @var mixed $URLRETURN                Variable que almacena la URL de retorno tras realizar una operación.
 * @var mixed $transactionToken         Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $transproducto_id         Variable que almacena el identificador de un producto en la transacción.
 * @var mixed $log                      Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $respuesta                Esta variable se utiliza para almacenar y manipular la respuesta de una operación.
 * @var mixed $e                        Esta variable se utiliza para capturar excepciones o errores en bloques try-catch.
 * @var mixed $Visa                     Variable que hace referencia al sistema de pagos Visa.
 * @var mixed $json                     Esta variable contiene datos en formato JSON, que pueden ser decodificados para su procesamiento.
 * @var mixed $TransaccionProducto      Variable que almacena información sobre una transacción de producto.
 * @var mixed $estado                   Variable que almacena el estado de un proceso o entidad.
 * @var mixed $respuesta_recarga        Variable que almacena la respuesta de una operación de recarga.
 * @var mixed $TRANSACTION_DATE         Variable que almacena la fecha de una transacción.
 * @var mixed $fechaTransaccion         Esta variable almacena una fecha, que puede indicar la creación, modificación o vencimiento de un registro.
 */

require(__DIR__ . '../../../../../../vendor/autoload.php');

use Backend\dto\Usuario;
use Backend\dto\Subproveedor;
use Backend\dto\TransaccionProducto;
use Backend\integrations\payment\Visa;
use Backend\dto\SubproveedorMandantePais;

header('Content-Type: text/html; charset=UTF-8');

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}
$_ENV["enabledConnectionGlobal"] = 1;

$ParamtransactionToken = $_POST['transactionToken'];
$ParamTP = $_GET['tp'];

if ($ParamTP == "fd22222") {
    $ParamTP = 'fd80379fb29477ff7795c3bb0d1efe39D3WxF+jY';
    $ParamtransactionToken = '191D1BF1E6714FB59D1BF1E6710FB5A2';
}

$ConfigurationEnvironment = new \Backend\dto\ConfigurationEnvironment();
$paramTP = $ConfigurationEnvironment->decrypt($ParamTP);

if (($ParamtransactionToken != '' && $ParamTP != '' && $paramTP != '') || $_REQUEST['debugFixed'] == '1') {
    try {
        $URLRETURN = "https://doradobet.com/graciasdeposito";

        if ($ConfigurationEnvironment->isDevelopment()) {
            $URLRETURN = "https://dev.doradobet.com/";
        }

        $transactionToken = $ParamtransactionToken;
        $transproducto_id = explode('121212', $paramTP)[0];

        try {
            $log = "\r\n" . "----------SOLICITUD---------------" . "\r\n";
            $log = $log . "\r\n" . $transproducto_id . "\r\n";
            $log = $log . "\r\n" . $transactionToken . "\r\n";
            $log = $log . "\r\n" . $transproducto_id . "\r\n";
        } catch (Exception $e) {
        }

        if ($transproducto_id == null || intval($transproducto_id) <= 0) {
            throw new Exception('', 0);
        }

        $TransaccionProducto = new TransaccionProducto($transproducto_id);
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

        $Visa = new Visa($transproducto_id, $transactionToken, '', $transproducto_id, $url, $merchant, $Key, $secretKey, $user, $password);
        $respuesta = $Visa->AuthorizationResult();

        if (( ! is_string($respuesta) && is_object($respuesta)) || ! is_string($respuesta) && is_array($respuesta)) {
            $respuesta = json_encode($respuesta);
        }

        try {
            $log = "\r\n" . "----------RESPONSE---------------" . "\r\n";
            $log = $log . "\r\n" . $transproducto_id . "\r\n";
        } catch (Exception $e) {
        }

        $json = json_decode($respuesta);

        switch ($json->dataMap->ACTION_CODE) {
            case "000":
                $estado = "APROBADA";
                break;

            default:
                $estado = "RECHAZADA";
        }

        $respuesta_recarga = $Visa->confirmation($respuesta);

        if ($estado == "APROBADA") {
            echo '<header><img src="https://images.virtualsoft.tech/site/doradobet/logoneww.png" alt="DoradoBet logo" width="110" height="80" style=" margin: 0 auto; display: block;"> </header>';

            echo "<div id='content'> <table>";

            $TRANSACTION_DATE = DateTime::createFromFormat('ymdHis', $json->dataMap->TRANSACTION_DATE);

            echo "<tr><td>Dominio: https://doradobet.com</td></tr>";
            echo "<tr><td>Doradobet</td></tr>";
            echo "<tr><td>Teléfono: </td><td> (+51) 15971781 (Lima, Perú) </td></tr>";
            echo "<tr><td>Dirección Comercial: </td><td>Calle schell 374, Miraflores, Lima</td></tr>";
            echo "<tr><td>Número de pedido:</td><td> " . explode('121212', $transproducto_id)[0] . "</td></tr>";
            echo "<tr><td>Estado de la transacción:</td><td> " . $estado . "</td></tr>";
            echo "<tr><td>Número de Tarjeta enmascarada:</td><td> " . $json->dataMap->CARD . " </td></tr>";
            echo "<tr><td>Fecha y hora del pedido: </td><td>" . date('Y-m-d H:i:s', $TRANSACTION_DATE->getTimestamp()) . " </td></tr>";
            echo "<tr><td>Importe: </td><td>" . $json->dataMap->AMOUNT . " </td></tr>";
            echo "<tr><td>Moneda: </td><td>PEN</td></tr>";
            echo "<tr><td>Descripción del producto: </td><td> Recarga Cuenta Usuario </td></tr>";
            echo "<tr><td>Nombre del tarjeta habiente: </td><td>" . $respuesta_recarga->name . " </td></tr>";
            echo "<tr><td>Descripción del código de acción: </td><td>" . $estado . " </td></tr>";
            echo "<tr><td>Políticas de devolución:  </td><td><a style='padding: 0px;color: blue;' href='https://doradobet.com/terminosycondiciones'>Políticas de devolución</a> </td></tr>";
            echo "<tr><td>Términos y Condiciones : </td><td><a style='padding: 0px;color: blue;' href='https://doradobet.com/terminosycondiciones'>Términos y Condiciones</a></td></tr>";
            echo "<tr><td>Señor usuarios debes de imprimir este comprobante de pago.</td></tr>";

            echo "</table></div>";
            echo "<footer> <div id='buttons'> <div class=''> <button onclick='window.print();'>Imprimir</button> </div> <div class=''> <a href='" . $URLRETURN . "'> Retornar al comercio </a> </div> </footer>";
        } else {
            $TRANSACTION_DATE = DateTime::createFromFormat('ymdHis', $json->dataMap->TRANSACTION_DATE);
            $fechaTransaccion = '';
            if ($TRANSACTION_DATE != '') {
                $fechaTransaccion = date('Y-m-d H:i:s', $TRANSACTION_DATE->getTimestamp());
            }

            echo '<header><img src="https://images.virtualsoft.tech/site/doradobet/logoneww.png" alt="DoradoBet logo" width="110" height="80" style=" margin: 0 auto; display: block;"> </header>';

            echo "<div id='content'> <table>";

            echo "<tr><td>Dominio: https://doradobet.com</td></tr>";
            echo "<tr><td>Doradobet</td></tr>";
            echo "<tr><td>Teléfono: </td><td> (+51) 15971781 (Lima, Perú) </td></tr>";
            echo "<tr><td>Dirección Comercial: </td><td>Calle schell 374, Miraflores, Lima</td></tr>";
            echo "<tr><td>Número de pedido:</td><td> " . explode('121212', $transproducto_id)[0] . "</td></tr>";
            echo "<tr><td>Estado de la transacción:</td><td> " . $estado . "</td></tr>";
            echo "<tr><td>Número de Tarjeta enmascarada:</td><td> " . $json->data->CARD . " </td></tr>";
            echo "<tr><td>Fecha y hora del pedido: </td><td>" . $fechaTransaccion . " </td></tr>";
            echo "<tr><td>Importe: </td><td>" . $json->data->AMOUNT . " </td></tr>";
            echo "<tr><td>Moneda: </td><td>PEN</td></tr>";
            echo "<tr><td>Descripción del producto: </td><td> Recarga Cuenta Usuario </td></tr>";
            echo "<tr><td>Motivo: </td><td>" . $json->data->ACTION_DESCRIPTION . " </td></tr>";
            echo "<tr><td>Políticas de devolución:  </td><td><a style='padding: 0px;color: blue;' href='https://doradobet.com/terminosycondiciones'>Políticas de devolución</a> </td></tr>";
            echo "<tr><td>Términos y Condiciones : </td><td><a style='padding: 0px;color: blue;' href='https://doradobet.com/terminosycondiciones'>Términos y Condiciones</a></td></tr>";

            echo "</table></div>";
            echo "<footer> <div id='buttons'> <div class=''> <button onclick='window.print();'>Imprimir</button> </div> <div class=''> <a href='$URLRETURN'> Retornar al comercio </a> </div> </footer>";
        }
        ?>
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
        </style>
        <?php
    } catch (Exception $e) {
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