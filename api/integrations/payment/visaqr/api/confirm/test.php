<?php

/**
 * Este archivo contiene un script para procesar confirmaciones de transacciones
 * realizadas a través del sistema de pagos Visa. Genera una respuesta en formato
 * HTML que incluye detalles de la transacción, como el estado, el importe y la fecha.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $ConfigurationEnvironment Esta variable se utiliza para almacenar y manipular la configuración del entorno.
 * @var mixed $URLRETURN                Variable que almacena la URL de retorno tras realizar una operación.
 * @var mixed $transactionToken         Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $transproducto_id         Variable que almacena el identificador de un producto en la transacción.
 * @var mixed $_GET                     Arreglo global que contiene los datos enviados mediante el método GET.
 * @var mixed $Visa                     Variable que hace referencia al sistema de pagos Visa.
 * @var mixed $valor                    Variable que almacena un valor monetario o numérico.
 * @var mixed $respuesta                Esta variable se utiliza para almacenar y manipular la respuesta de una operación.
 * @var mixed $json                     Esta variable contiene datos en formato JSON, que pueden ser decodificados para su procesamiento.
 * @var mixed $TransaccionProducto      Variable que almacena información sobre una transacción de producto.
 * @var mixed $estado                   Variable que almacena el estado de un proceso o entidad.
 * @var mixed $respuesta_recarga        Variable que almacena la respuesta de una operación de recarga.
 * @var mixed $TRANSACTION_DATE         Variable que almacena la fecha de una transacción.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../../vendor/autoload.php');

use Backend\dto\TransaccionProducto;
use Backend\dto\Usuario;
use Backend\integrations\payment\Visa;

if (true) {
    $ConfigurationEnvironment = new \Backend\dto\ConfigurationEnvironment();
    $URLRETURN = "https://doradobet.com/";

    if ($ConfigurationEnvironment->isDevelopment()) {
        $URLRETURN = "https://dev.doradobet.com/";
    }
    $transactionToken = '286168';
    $transproducto_id = explode('121212', $_GET['tp'])[0];

    $Visa = new Visa($transproducto_id, $transactionToken, $valor, $transproducto_id);


    $respuesta = '{"dataMap":{"ACTION_CODE":"000"}}';
    $json = json_decode($respuesta);

    print_r($json);
    switch ($json->dataMap->ACTION_CODE) {
        case "000":

            $estado = "APROBADA";

            break;

        default:

            $estado = "RECHAZADA";
    }

    $respuesta_recarga = $Visa->confirmation($respuesta);

    if ($estado == "APROBADA") {
        echo '<header><img src="https://dev.doradobet.com/assets/images/logo.png" alt="DoradoBet logo" width="110" height="80" style=" margin: 0 auto; display: block;"> </header>';

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

        echo '<header><img src="https://dev.doradobet.com/assets/images/logo.png" alt="DoradoBet logo" width="110" height="80" style=" margin: 0 auto; display: block;"> </header>';

        echo "<div id='content'> <table>";

        echo "<tr><td>Dominio: https://doradobet.com</td></tr>";
        echo "<tr><td>Doradobet</td></tr>";
        echo "<tr><td>Teléfono: </td><td> (+51) 15971781 (Lima, Perú) </td></tr>";
        echo "<tr><td>Dirección Comercial: </td><td>Calle schell 374, Miraflores, Lima</td></tr>";
        echo "<tr><td>Número de pedido:</td><td> " . explode('121212', $transproducto_id)[0] . "</td></tr>";
        echo "<tr><td>Estado de la transacción:</td><td> " . $estado . "</td></tr>";
        echo "<tr><td>Número de Tarjeta enmascarada:</td><td> " . $json->data->CARD . " </td></tr>";
        echo "<tr><td>Fecha y hora del pedido: </td><td>" . date('Y-m-d H:i:s', $TRANSACTION_DATE->getTimestamp()) . " </td></tr>";
        echo "<tr><td>Importe: </td><td>" . $json->data->AMOUNT . " </td></tr>";
        echo "<tr><td>Moneda: </td><td>PEN</td></tr>";
        echo "<tr><td>Descripción del producto: </td><td> Recarga Cuenta Usuario </td></tr>";
        echo "<tr><td>Motivo: </td><td>" . $json->data->ACTION_DESCRIPTION . " </td></tr>";
        echo "<tr><td>Políticas de devolución:  </td><td><a style='padding: 0px;color: blue;' href='https://doradobet.com/terminosycondiciones'>Políticas de devolución</a> </td></tr>";
        echo "<tr><td>Términos y Condiciones : </td><td><a style='padding: 0px;color: blue;' href='https://doradobet.com/terminosycondiciones'>Términos y Condiciones</a></td></tr>";

        echo "</table></div>";
        echo "<footer> <div id='buttons'> <div class=''> <button onclick='window.print();'>Imprimir</button> </div> <div class=''> <a href='<?=$URLRETURN?>'> Retornar al comercio </a> </div> </footer>";
    }
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

    header, footer {
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
</style>