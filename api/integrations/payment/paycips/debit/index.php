<?php

/**
 * Archivo principal para la integración de pagos con PayCIPS.
 *
 * Este archivo contiene la lógica para procesar solicitudes de pago y manejar
 * la interacción con los servicios de PayCIPS. Incluye validaciones de datos
 * de entrada, desencriptación de tokens, y generación de respuestas basadas
 * en el estado de las transacciones.
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
 * @var mixed $mensaje                  Esta variable contiene un mensaje informativo o de error, empleado para notificar el estado de una operación.
 * @var mixed $_POST                    Arreglo global que contiene los datos enviados mediante el método POST.
 * @var mixed $paycipsServices          Variable relacionada con los servicios de Paycips.
 * @var mixed $account                  Esta variable indica la cantidad total de elementos o registros, útil para iteraciones o validaciones.
 * @var mixed $idBank                   Variable que almacena el ID del banco.
 * @var mixed $description              Variable que almacena una descripción.
 * @var mixed $name                     Variable que almacena el nombre de un archivo, usuario o entidad.
 * @var mixed $amount                   Variable que almacena un monto o cantidad.
 * @var mixed $transId                  Variable que almacena el ID de la transacción.
 * @var mixed $ConfigurationEnvironment Esta variable se utiliza para almacenar y manipular la configuración del entorno.
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
 * @var mixed $banks                    Variable que almacena una lista de bancos.
 * @var mixed $_SERVER                  Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $bank                     Variable que almacena información sobre un banco específico.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

header('Content-Type: text/html; charset=UTF-8');

use Backend\dto\Pais;
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Mandante;
use Backend\dto\Subproveedor;
use Backend\dto\UsuarioMandante;
use Backend\dto\TransaccionProducto;
use Backend\dto\SubproveedorMandante;
use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\payment\PayCIPS;
use Backend\integrations\payment\PAYCIPSSERVICES;

$mensaje = "No se puede procesar la solicitud";

if ( ! empty($_POST['account']) && ! empty($_POST['name']) && ! empty($_POST['amount'])) {
    $paycipsServices = new PAYCIPSSERVICES();

    $account = $_POST['account'];
    $idBank = $_POST['bank'];
    $description = "Deposito Paycips";
    $name = $_POST['name'];
    $amount = $_POST['amount'];
    $transId = $_POST['transId'];

    $transId = $ConfigurationEnvironment->decrypt($transId);

    $TransaccionProducto = new TransaccionProducto($transId);
    $Usuario = new Usuario($TransaccionProducto->getUsuarioId());
    $Mandante = new Mandante($Usuario->mandante);

    if ($TransaccionProducto->estadoProducto == 'E') {
        $response = $paycipsServices->GetSPEI($account, $idBank, $description, $name, $amount, $transId, $Usuario->mandante);
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
    $ConfigurationEnvironment = new ConfigurationEnvironment();
    $_GET["id"] = str_replace(' ', '+', $_GET["id"]);

    $sessiontoken = $_GET["id"];
    $sessiontokenOrig = $_GET["id"];
    $sessiontoken = $ConfigurationEnvironment->decrypt($sessiontoken);
    $sessiontokenEncrypt = $ConfigurationEnvironment->encrypt($sessiontoken);

    $TransaccionProducto = new TransaccionProducto($sessiontoken);

    if (isset($TransaccionProducto->productoId)) {
        $Producto = new Producto($TransaccionProducto->productoId);

        $Usuario = new Usuario($TransaccionProducto->getUsuarioId());
        $Mandante = new Mandante($Usuario->mandante);
        $amount = $TransaccionProducto->valor;

        $paycipsServices = new PAYCIPSSERVICES();
        $banks = $paycipsServices->getBankList($sessiontoken, $Usuario->mandante);

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
                        case 'account':
                            if (val.length < 10 || val.length > 20) {
                                panMsg.innerHTML = "El número de cuenta debe ser mayor a 10 y menor de 20 a dígitos";
                            } else {
                                panMsg.innerHTML = '';
                            }
                            break;
                    }
                }

                function limitKey(e, type) {
                    var input = e.target.value;

                    switch (type) {
                        case 'account':
                            if (input.length > 19) {
                                return false;
                            } else {
                                return true;
                            }
                            break;
                    }
                }

                function validateForm() {
                    var account = document.forms["form"]["account"].value;
                    var name = document.forms["form"]["name"].value;

                    var acountMsg = document.getElementById('acountMsg');
                    var nameMsg = document.getElementById('nameMsg');

                    if (account.length < 10 || account.length > 20) {
                        acountMsg.innerHTML = "El número de cuenta debe ser mayor a 10 y menor a 20 dígitos";
                        return false;
                    } else {
                        acountMsg.innerHTML = "";
                    }

                    if (name == "") {
                        nameMsg.innerHTML = "Nombre requerido";
                        return false;
                    } else {
                        nameMsg.innerHTML = "";
                    }

                    if (name.length < 4) {
                        nameMsg.innerHTML = "El nombre debe ser de 4 o mas carácteres";
                        return false;
                    } else {
                        nameMsg.innerHTML = "";
                    }

                    document.getElementById("spinner").style.display = "block";
                }
            </script>
        </head>
        <body style="background-color: #989898;">
        <div id="content container">
            <div style="margin:auto; width:400px">
                <img src="<?= $Mandante->logo ?>" style="margin-top:50px" class="img-fluid" alt="Responsive image">
            </div>
            <div class="card" style="margin:auto; margin-top:50px; width:500px">
                <div class="card-body">
                    <h5 class="card-title"><?= $Producto->descripcion ?></h5>
                    <p class="card-text">
                    <form method="POST" name="form" onsubmit="return validateForm()" action="<?= $_SERVER['PHP_SELF'] ?>">
                        <div class="form-group">
                            <label for="account">Número de cuenta:</label>
                            <input type="number" autocomplete="off" onkeypress="return limitKey(event, 'account')" class="form-control" name="account" id="account" placeholder="Numero de cuenta">
                            <small id="acountMsg" class="form-text text-muted"></small>
                        </div>

                        <div class="form-group">
                            <label for="bank">Banco:</label>
                            <select name="bank" class="form-control">
                                <?php
                                foreach ($banks as $bank) {
                                    echo "<option value ='" . $bank->InstFinId . "'>" . $bank->InstFinName . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <!-- <div class="form-group">
                            <label for="Descripcion">Descripción:</label>
                            <input type="text" autocomplete="off" class="form-control" name="descripcion" id="descripcion" placeholder="Descripción">
                        </div> -->

                        <div class="form-group">
                            <label for="name">Nombre:</label>
                            <input type="text" autocomplete="off" class="form-control" name="name" id="name" placeholder="Nombre completo">
                            <small id="nameMsg" class="form-text text-muted"></small>
                        </div>

                        <!-- <div class="form-group">
                            <label for="amount">Valor:</label>
                            <input type="number" class="form-control" name="amount" id="amount" placeholder="Valor a transferir">
                        </div> -->

                        <input id="transId" name="transId" type="hidden" value="<?= $sessiontokenOrig ?>">
                        <input id="amount" name="amount" type="hidden" value="<?= $amount ?>">

                        <button type="submit" onclick="disabledButtom()" id="button" class="btn btn-primary btn-block">Enviar</button>
                        <!-- <img src="../img/credit.png" alt="credito"> -->
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
    } else {
        ?>
        <head>
            <link rel="stylesheet" href="bootstrap.css">
        </head>
        <body style="background-color: #989898;">
        <div class="card" style="margin:auto; margin-top:50px; width:500px">
            <div class="card-body">
                <h5 class="card-title">Mensaje</h5>
                <p class="card-text"><?= $mensaje ?></p>
            </div>
        </div>
        </body>
        <?php
    }
}
if (isset($response->message)) {
    ?>
    <head>
        <link rel="stylesheet" href="bootstrap.css">
    </head>
    <body style="background-color: #989898;">
    <div style="margin:auto; width:400px">
        <img src="<?= $Mandante->logo ?>" style="margin-top:50px" class="img-fluid" alt="Responsive image">
    </div>
    <div class="card" style="margin:auto; margin-top:50px; width:500px">
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
    <div class="card" style="margin:auto; margin-top:50px; width:500px">
        <div class="card-body">
            <h5 class="card-title">Mensaje</h5>
            <p class="card-text"><?= $mensaje ?></p>
        </div>
    </div>
    </body>
    <?php
}
exit();
?>