<?php

/**
 * Este archivo maneja la integración con el servicio de pagos PayCIPS.
 * Procesa solicitudes GET para generar referencias de pago y mostrar métodos de pago disponibles.
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
 * @var mixed $sessiontoken             Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $TransaccionProducto      Variable que almacena información sobre una transacción de producto.
 * @var mixed $Usuario                  Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $Mandante                 Esta variable se utiliza para almacenar y manipular el mandante asociado.
 * @var mixed $amount                   Variable que almacena un monto o cantidad.
 * @var mixed $paycipsServices          Variable relacionada con los servicios de Paycips.
 * @var mixed $description              Variable que almacena una descripción.
 * @var mixed $transId                  Variable que almacena el ID de la transacción.
 * @var mixed $response                 Esta variable almacena la respuesta generada por una operación o petición.
 * @var mixed $link                     Variable que almacena un enlace o URL.
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
use Backend\integrations\payment\PAYCIPSSERVICES;

if (isset($_GET["id"])) {
    $ConfigurationEnvironment = new ConfigurationEnvironment();

    $_GET["id"] = str_replace(' ', '+', $_GET["id"]);

    $sessiontoken = $_GET["id"];
    $sessiontoken = $ConfigurationEnvironment->decrypt($sessiontoken);


    $TransaccionProducto = new TransaccionProducto($sessiontoken);

    if (isset($TransaccionProducto->productoId)) {
        $Usuario = new Usuario($TransaccionProducto->getUsuarioId());
        $Mandante = new Mandante($Usuario->mandante);
        $amount = $TransaccionProducto->valor + $TransaccionProducto->impuesto;

        $paycipsServices = new PAYCIPSSERVICES();
        $description = "Deposito Paycips";
        $transId = $sessiontoken;

        $response = $paycipsServices->GetPayRef($description, $amount, $transId, $Usuario->mandante);
        if ($response->success) {
            ?>
            <head>
                <link rel="stylesheet" href="bootstrap.css">
            </head>

            <body style="background-color: #989898;">
            <div id="content container">
                <div style="margin:auto; width:400px">
                    <img src="<?= $Mandante->logo ?>" style="margin-top:50px" class="img-fluid" alt="Responsive image">
                </div>
                <div class="card" style="margin:auto; margin-top:50px; width:330px">
                    <div class="card-body">
                        <h5 class="card-title">Seleccione método de pago</h5>
                        <p class="card-text">
                            <?php
                            foreach ($response->data->Links as $link) { ?>

                        <div class="card" style="width: 18rem;">
                            <div class="card-body">

                                <a href="<?= $link->URL ?>" class="card-link">
                                    <?php
                                    if ($link->Description == 'PayNet') {
                                        ?>
                                        <img src="https://images.virtualsoft.tech/m/msjT1636415670.png" style="width: 200px;margin: 0 auto;">
                                        <?php
                                    }
                                    if ($link->Description == 'OxxoPay') {
                                        ?>
                                        <img src="https://images.virtualsoft.tech/m/msjT1636415795.png" style="width: 200px;margin: 0 auto;">
                                        <?php
                                    }
                                    if ($link->Description == 'Oxxo24') {
                                        ?>
                                        <img src="https://images.virtualsoft.tech/m/msjT1636415795.png" style="width: 200px;margin: 0 auto;">
                                        <?php
                                    }
                                    ?>
                                    <h6 class=""><?= $link->Description ?></h6>
                                </a>
                                <p class="card-text">Código: <?= $link->Barcode ?> </p>
                            </div>
                        </div>
                        <?php
                        } ?>
                        <br>
                        <a href="<?= $Mandante->baseUrl ?>" class="card-link">
                            <button class="btn btn-primary btn-block">regresar</button>
                        </a>
                        </p>
                    </div>
                </div>
            </div>
            </body>

            <?php
        } else {
            ?>
            <head>
                <link rel="stylesheet" href="bootstrap.css">
            </head>
            <body style="background-color: #989898;">
            <div id="content">
                <div class="card" style="margin:auto; width:400px">
                    <div class="card-body">
                        <h5 class="card-title">Error</h5>
                        <p class="card-text">No se puede procesar la solicitud</p>
                    </div>
                </div>
            </body>
            <?php
        }
    } else {
        ?>
        <head>
            <link rel="stylesheet" href="bootstrap.css">
        </head>
        <body style="background-color: #989898;">
        <div class="card" style="margin:auto; margin-top:50px; width:500px">
            <div class="card-body">
                <h5 class="card-title">Mensaje</h5>
                <p class="card-text">No se puede procesar la solicitud</p>
            </div>
        </div>
        </body>
        <?php
    }
} else {
    ?>
    <head>
        <link rel="stylesheet" href="bootstrap.css">
    </head>
    <body style="background-color: #989898;">
    <div class="card" style="margin:auto; margin-top:50px; width:500px">
        <div class="card-body">
            <h5 class="card-title">Mensaje</h5>
            <p class="card-text">No se puede procesar la solicitud </p>
        </div>
    </div>
    </body>
    <?php
}

?>
