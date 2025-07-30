<?php

/**
 * Archivo principal para la integración de pagos con PayCIPS.
 *
 * Este archivo contiene la lógica para procesar transacciones de pago,
 * validar datos de entrada, y manejar respuestas de la API de PayCIPS.
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
 * @var mixed $_REQUEST                 Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $_ENV                     Variable superglobal que contiene el entorno de variables del sistema.
 * @var mixed $ConfigurationEnvironment Esta variable se utiliza para almacenar y manipular la configuración del entorno.
 * @var mixed $mensaje                  Esta variable contiene un mensaje informativo o de error, empleado para notificar el estado de una operación.
 * @var mixed $_POST                    Arreglo global que contiene los datos enviados mediante el método POST.
 * @var mixed $paycipsServices          Variable relacionada con los servicios de Paycips.
 * @var mixed $pan                      Variable que almacena el número de cuenta primaria (PAN).
 * @var mixed $cvv                      Variable que almacena el código de seguridad de la tarjeta.
 * @var mixed $expDateM                 Variable que almacena el mes de expiración de la tarjeta.
 * @var mixed $expDateY                 Variable que almacena el año de expiración de la tarjeta.
 * @var mixed $firstName                Variable que almacena el primer nombre.
 * @var mixed $lastName                 Variable que almacena el apellido.
 * @var mixed $amount                   Variable que almacena un monto o cantidad.
 * @var mixed $address                  Variable que almacena una dirección.
 * @var mixed $currency                 Variable que almacena la moneda utilizada en una transacción.
 * @var mixed $ip                       Variable que almacena la dirección IP.
 * @var mixed $country                  Esta variable indica la cantidad total de elementos o registros, útil para iteraciones o validaciones.
 * @var mixed $order                    Variable que almacena la orden.
 * @var mixed $email                    Variable que almacena la dirección de correo electrónico de un usuario.
 * @var mixed $lang                     Variable que define el idioma.
 * @var mixed $transId                  Variable que almacena el ID de la transacción.
 * @var mixed $expDate                  Variable que almacena la fecha de expiración.
 * @var mixed $TransaccionProducto      Variable que almacena información sobre una transacción de producto.
 * @var mixed $Usuario                  Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $Mandante                 Esta variable se utiliza para almacenar y manipular el mandante asociado.
 * @var mixed $response                 Esta variable almacena la respuesta generada por una operación o petición.
 * @var mixed $control                  Variable que almacena un código de control para una operación.
 * @var mixed $usuario_id               Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $documento_id             Variable que almacena el identificador de un documento.
 * @var mixed $Paycips                  Variable que almacena información relacionada con Paycips.
 * @var mixed $_GET                     Arreglo global que contiene los datos enviados mediante el método GET.
 * @var mixed $sessiontoken             Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $sessiontokenOrig         Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $sessiontokenEncrypt      Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $Producto                 Variable que almacena información del producto.
 * @var mixed $Pais                     Variable que almacena el nombre de un país.
 * @var mixed $_SERVER                  Variable superglobal que contiene información del servidor y entorno de ejecución.
 */

ini_set('display_errors', 'OFF');


require(__DIR__ . '../../../../../vendor/autoload.php');

header('Content-Type: text/html; charset=UTF-8');

use Backend\dto\Pais;
use Backend\dto\Usuario;
use Backend\dto\Registro;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Subproveedor;
use Backend\dto\TransaccionProducto;
use Backend\dto\SubproveedorMandante;
use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\payment\PayCIPS;
use Backend\integrations\payment\PAYCIPSSERVICES;

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

$ConfigurationEnvironment = new ConfigurationEnvironment();
$mensaje = "Transacción rechazada";
if (
    ! empty($_POST['pan']) &&
    ! empty($_POST['cvv']) &&
    ! empty($_POST['expDateM']) &&
    ! empty($_POST['expDateY']) &&
    ! empty($_POST['firstName']) &&
    ! empty($_POST['lastName']) &&
    ! empty($_POST['amount']) &&
    ! empty($_POST['address']) &&
    ! empty($_POST['currency']) &&
    // !empty($_POST['ip']) && 
    ! empty($_POST['country']) &&
    ! empty($_POST['order']) &&
    ! empty($_POST['email']) &&
    ! empty($_POST['lang']) &&
    ! empty($_POST['transId'])
) {
    $paycipsServices = new PAYCIPSSERVICES();

    $pan = $_POST['pan'];
    $cvv = $_POST['cvv'];
    $expDateM = $_POST['expDateM'];
    $expDateY = $_POST['expDateY'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $amount = $_POST['amount'];
    $address = $_POST['address'];
    $currency = $_POST['currency'];
    $ip = $_POST['ip'];
    $country = $_POST['country'];
    $order = $_POST['order'];
    $email = $_POST['email'];
    $lang = $_POST['lang'];
    $transId = $_POST['transId'];
    $expDate = $expDateM . $expDateY;

    $order = $ConfigurationEnvironment->decrypt($order);
    $transId = $ConfigurationEnvironment->decrypt($transId);


    $TransaccionProducto = new TransaccionProducto($transId);
    $Usuario = new Usuario($TransaccionProducto->getUsuarioId());
    $Mandante = new Mandante($Usuario->mandante);

    $email = $Usuario->login;

    if ($TransaccionProducto->estadoProducto == 'E') {
        $response = $paycipsServices->getCardAuth($pan, $cvv, $expDate, $firstName, $lastName, $email, $amount, $currency, $order, $ip, $lang, $address, $country, $transId, $Usuario->mandante);
        $control = "";
        $usuario_id = "";
        $documento_id = $response->data->SystemTrace;
        $Paycips = new PayCIPS($transId, $usuario_id, $documento_id, $amount, $control, $response->data->RespCode);
        $Paycips->confirmation();
    } else {
        $mensaje = "Transacción ya procesada";
    }
}

if (isset($_GET["id"])) {
    $_GET["id"] = str_replace(' ', '+', $_GET["id"]);

    $sessiontoken = $_GET["id"];
    $sessiontokenOrig = $_GET["id"];
    $sessiontoken = $ConfigurationEnvironment->decrypt($sessiontoken);
    $sessiontokenEncrypt = $ConfigurationEnvironment->encrypt($sessiontoken);

    $TransaccionProducto = new TransaccionProducto($sessiontoken);

    $Producto = new Producto($TransaccionProducto->productoId);

    $Usuario = new Usuario($TransaccionProducto->getUsuarioId());
    $Mandante = new Mandante($Usuario->mandante);
    $Usuario = new Usuario($TransaccionProducto->getUsuarioId());
    $Mandante = new Mandante($Usuario->mandante);

    $amount = $TransaccionProducto->valor;

    $Usuario = new Usuario($TransaccionProducto->getUsuarioId());
    $Pais = new Pais($Usuario->paisId);
    ?>
    <head>
        <link rel="stylesheet" href="bootstrap.css">
        <style>
            .spinner {
                width: 100%;
                height: 120%;
                top: 0;
                position: absolute;
                background-color: rgba(222, 222, 222, 0.3);
                display: none;
            }

            .loader {
                width: 100%;
                height: 100%;
                top: 0;
                animation: spin 1s infinite linear;
                border: solid 2vmin transparent;
                border-radius: 50%;
                border-right-color: #09f;
                border-top-color: #09f;
                box-sizing: border-box;
                height: 20vmin;
                left: calc(50% - 10vmin);
                position: fixed;
                top: calc(50% - 10vmin);
                width: 20vmin;
                z-index: 1;

                &:before {
                    animation: spin 2s infinite linear;
                    border: solid 2vmin transparent;
                    border-radius: 50%;
                    border-right-color: #3cf;
                    border-top-color: #3cf;
                    box-sizing: border-box;
                    content: "";
                    height: 16vmin;
                    left: 0;
                    position: absolute;
                    top: 0;
                    width: 16vmin;
                }

                &:after {
                    animation: spin 3s infinite linear;
                    border: solid 2vmin transparent;
                    border-radius: 50%;
                    border-right-color: #6ff;
                    border-top-color: #6ff;
                    box-sizing: border-box;
                    content: "";
                    height: 12vmin;
                    left: 2vmin;
                    position: absolute;
                    top: 2vmin;
                    width: 12vmin;
                }
            }

            @keyframes spin {
                100% {
                    transform: rotate(360deg);
                }
            }
        </style>
        <script>
            function validateCard(val, type) {
                switch (type) {
                    case 'pan':
                        if (val.length < 10 || val.length > 20) {
                            panMsg.innerHTML = "Número de Tarjeta debe ser mayor a 10 y menor de 20 a dígitos";
                        } else {
                            panMsg.innerHTML = '';
                        }
                        break;
                    case 'cvv':
                        if (val.length != 3) {
                            cvvMsg.innerHTML = "CVV debe ser de 3 dígitos";
                        } else {
                            cvvMsg.innerHTML = "";
                        }
                        break;
                    case 'expDateM':
                        if (val.length != 2) {
                            expDateMMsg.innerHTML = "El mes de expiración debe ser de dos dígitos";
                        } else {
                            expDateMMsg.innerHTML = "";
                        }
                        break;
                    case 'expDateY':
                        if (val.length != 2) {
                            expDateYMsg.innerHTML = "El año de expiración debe ser de dos dígitos";
                        } else {
                            expDateYMsg.innerHTML = "";
                        }
                        break;

                    default:
                        break;
                }
            }

            function limitKey(e, type) {
                var input = e.target.value;

                switch (type) {
                    case 'pan':
                        if (input.length > 19) {
                            return false;
                        } else {
                            return true;
                        }
                        break;
                    case 'cvv':
                        if (input.length > 2) {
                            return false;
                        } else {
                            return true;
                        }
                        break;
                    case 'expDateM':
                        if (input.length > 1) {
                            return false;
                        } else {
                            return true;
                        }
                        break;
                    case 'expDateY':
                        if (input.length > 1) {
                            return false;
                        } else {
                            return true;
                        }
                        break;

                    default:
                        break;
                }
            }

            function validateForm() {
                var pan = document.forms["form"]["pan"].value;
                var cvv = document.forms["form"]["cvv"].value;
                var expDateM = document.forms["form"]["expDateM"].value;
                var expDateY = document.forms["form"]["expDateY"].value;
                var firstName = document.forms["form"]["firstName"].value;
                var lastName = document.forms["form"]["lastName"].value;
                var address = document.forms["form"]["address"].value;

                var panMsg = document.getElementById("panMsg");
                var cvvMsg = document.getElementById("cvvMsg");
                var expDateMMsg = document.getElementById("expDateMMsg");
                var expDateYMsg = document.getElementById("expDateYMsg");
                var firstNameMsg = document.getElementById("firstNameMsg");
                var lastNameMsg = document.getElementById("lastNameMsg");
                var addressMsg = document.getElementById("addressMsg");

                if (pan.length < 10 || pan.length > 20) {
                    panMsg.innerHTML = "Número de Tarjeta debe ser mayor a 10 y menor de 20 a dígitos";
                    return false;
                } else {
                    panMsg.innerHTML = "";

                }

                if (cvv.length != 3) {
                    cvvMsg.innerHTML = "CVV debe ser de 3 dígitos";
                    return false;
                } else {
                    cvvMsg.innerHTML = "";
                }

                if (expDateM.length != 2) {
                    expDateMMsg.innerHTML = "El mes de expiración debe ser de dos dígitos";
                    return false;
                } else {
                    expDateMMsg.innerHTML = "";

                }

                if (expDateY.length != 2) {
                    expDateYMsg.innerHTML = "El año de expiración debe ser de dos dígitos";
                    return false;
                } else {
                    expDateYMsg.innerHTML = "";
                }

                if (firstName.length < 4) {
                    firstNameMsg.innerHTML = "El nombre debe ser mayor de 4 carácteres";
                    return false;
                } else {
                    firstNameMsg.innerHTML = "";
                }

                if (lastName.length < 4) {
                    lastNameMsg.innerHTML = "El apellido debe ser mayor de 4 carácteres";
                    return false;
                } else {
                    lastNameMsg.innerHTML = "";
                }

                if (address == "") {
                    addressMsg.innerHTML = "La direccíon no debe ir vacia";
                    return false;
                } else {
                    addressMsg.innerHTML = "";
                }

                document.getElementById("spinner").style.display = "block";
            }
        </script>

    </head>
    <body style="background-color: #989898;">
    <div id="content">
        <div style="margin:auto; width:400px">
            <img src="<?= $Mandante->logo ?>" style="margin-top:50px" class="img-fluid" alt="Responsive image">
        </div>
        <div class="card" style="margin:auto; margin-top:50px; width:500px">
            <div class="card-body">
                <h5 class="card-title"><?= $Producto->descripcion ?></h5>
                <p class="card-text">
                <form method="POST" name="form" onsubmit="return validateForm()" action="<?= $_SERVER['PHP_SELF'] ?>">
                    <div class="form-group">
                        <label for="pan">Número de Tarjeta:</label>
                        <input type="number" autocomplete="off" onkeypress="return limitKey(event, 'pan')" class="form-control" onchange="validateCard(this.value, 'pan')" name="pan" id="pan" placeholder="Número de Tarjeta">
                        <small id="panMsg" class="form-text text-muted"></small>
                    </div>

                    <div class="form-group">
                        <label for="cvv">CVV:</label>
                        <input type="text" autocomplete="off" onkeypress="return limitKey(event, 'cvv')" class="form-control" onchange="validateCard(this.value, 'cvv')" name="cvv" id="cvv" placeholder="CVV">
                        <small id="cvvMsg" class="form-text text-muted"></small>
                    </div>

                    <div class="form-group">
                        <label for="expDate">Fecha de Expiración:</label>
                        <input style="width:7rem" type="number" onkeypress="return limitKey(event, 'expDateM')" autocomplete="off" onchange="validateCard(this.value, 'expDateM')" class="form-control" name="expDateM" id="expDateM" placeholder="MM"><br>
                        <small id="expDateMMsg" class="form-text text-muted"></small>
                        <input style="width:7rem" type="number" onkeypress="return limitKey(event, 'expDateY')" autocomplete="off" onchange="validateCard(this.value, 'expDateY')" class="form-control" name="expDateY" id="expDateY" placeholder="YY">
                        <small id="expDateYMsg" class="form-text text-muted"></small>
                    </div>

                    <div class="form-group">
                        <label for="firstName">Nombre:</label>
                        <input type="text" autocomplete="off" class="form-control" name="firstName" id="firstName" placeholder="Nombre">
                        <small id="firstNameMsg" class="form-text text-muted"></small>
                    </div>

                    <div class="form-group">
                        <label for="lastName">Apellido:</label>
                        <input type="text" autocomplete="off" class="form-control" name="lastName" id="lastName" placeholder="Apellido">
                        <small id="lastNameMsg" class="form-text text-muted"></small>
                    </div>


                    <div class="form-group">
                        <label for="address">Dirección:</label>
                        <input type="text" autocomplete="off" class="form-control" name="address" id="address" placeholder="Dirección">
                        <small id="addressMsg" class="form-text text-muted"></small>
                    </div>

                    <input id="amount" name="amount" type="hidden" value="<?= $amount ?>">
                    <input id="currency" name="currency" type="hidden" value="<?= $Pais->moneda ?>">
                    <input id="ip" name="ip" type="hidden" value="<?= $Usuario->dirIp ?>">
                    <input id="country" name="country" type="hidden" value="<?= $Pais->paisNom ?>">
                    <input id="order" name="order" type="hidden" value="<?= $sessiontokenOrig ?>">
                    <input id="email" name="email" type="hidden" value="<?= $Usuario->login ?>">
                    <input id="lang" name="lang" type="hidden" value="<?= $Pais->idioma ?>">
                    <input id="transId" name="transId" type="hidden" value="<?= $sessiontokenOrig ?>">

                    <button type="submit" class="btn btn-primary btn-block">Enviar</button>
                </form>
                </p>
            </div>
        </div>
    </div>
    <div id="spinner" class="spinner">
        <div class="loader">
        </div>
    </body>
    <?php
}
if (isset($response->message)) {
    ?>
    <head>
        <link rel="stylesheet" href="bootstrap.css">
    </head>
    <body style="background-color: #989898;">
    <div id="content">
        <div style="margin:auto; width:400px">
            <img src="<?= $Mandante->logo ?>" style="margin-top:50px; margin-bottom:50px" class="img-fluid" alt="Responsive image">
        </div>
        <div class="card" style="margin:auto; width:400px">
            <div class="card-body">
                <h5 class="card-title">Mensaje</h5>
                <p class="card-text"><?= $response->data->RespText ?></p>
                <a href="<?= $Mandante->baseUrl ?>" class="card-link">
                    <button class="btn btn-primary btn-block">regresar</button>
                </a>
            </div>
        </div>
    </body>
    <?php
}
if ( ! isset($_GET["id"]) && ! isset($response->message)) {
    ?>
    <head>
        <link rel="stylesheet" href="bootstrap.css">
    </head>
    <body style="background-color: #989898;">
    <div id="content">
        <div style="margin:auto; width:400px">
            <img src="<?= $Mandante->logo ?>" style="margin-top:50px; margin-bottom:50px" class="img-fluid" alt="Responsive image">
        </div>
        <div class="card" style="margin:auto; width:400px">
            <div class="card-body">
                <h5 class="card-title">Error</h5>
                <p class="card-text"> <?= $mensaje ?></p>
                <a href="<?= $Mandante->baseUrl ?>" class="card-link">
                    <button class="btn btn-primary btn-block">regresar</button>
                </a>
            </div>
        </div>
    </body>
    <?php
}
exit();
?>
