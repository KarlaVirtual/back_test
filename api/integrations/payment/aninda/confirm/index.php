<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con Aninda.
 *
 * Procesa los datos recibidos en la solicitud, registra información en un archivo de log
 * y utiliza la clase Aninda para confirmar transacciones.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Davison Valencia <davison.valencia@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-28
 */

/**
 * Documentación de Variables Globales:
 *
 * @var mixed $data          Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $trx           Variable que almacena el identificador de una transacción.
 * @var mixed $externoId     Variable que almacena un identificador genérico del proveedor.
 * @var mixed $status        Esta variable se utiliza para almacenar y manipular el estado.
 * @var mixed $amonut        Esta variable se utiliza para almacenar y manipular valores asociados a claves.
 * @var mixed $Aninda        Variable que almacena información relacionada con Aninda.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use \Backend\integrations\payment\Aninda;

header('Content-Type: application/json');

/* Obtenemos Variables que nos llegan */
$data = (file_get_contents('php://input'));
$data = json_decode($data);

if (isset($data)) {

    //Aninda
    $trx = $data->TraderTransactionID;
    $externoId = $data->PaymentTransactionID;
    $status = $data->Status;
    $amonut = $data->Amount;

    if ($status == 'Successful') {
        $status = 'SUCCESS';
    } else {
        $status = 'PROGRESS';
    }

    /* Procesamos */
    $Aninda = new Aninda($trx, $status, $externoId, $amonut);
    $response = $Aninda->confirmation(json_encode($data));

    $response = json_decode($response);

    $data_ = array();
    if ($response->result == 'success') {
        $data_["HasError"] = false;
        $data_["Description"] = "SUCCESS";
        $data_["Data"] = "";
        $data_["ID"] = 100;
    } elseif ($response->result == 'error') {
        $data_["HasError"] = false;
        $data_["Description"] = "SUCCESS";
        $data_["Data"] = "";
        $data_["ID"] = 100;
    } elseif ($response->result == '') {
        $data_["HasError"] = false;
        $data_["Description"] = "SUCCESS";
        $data_["Data"] = "";
        $data_["ID"] = 100;
    } else {
        $data_["HasError"] = true;
        $data_["Description"] = "FAIL";
        $data_["Data"] = "";
        $data_["ID"] = 101;
    }

    echo json_encode($data_);
}
