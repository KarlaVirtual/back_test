<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con Megapayz.
 *
 * Procesa los datos recibidos en la solicitud, registra información en un archivo de log
 * y utiliza la clase Megapayz para confirmar transacciones.
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
 * @var mixed $trx            Variable que almacena el identificador de una transacción.
 * @var mixed $externoId     Variable que almacena un identificador genérico del proveedor.
 * @var mixed $status        Esta variable se utiliza para almacenar y manipular el estado.
 * @var mixed $amonut        Esta variable se utiliza para almacenar y manipular valores asociados a claves.
 * @var mixed $Megapayz      Variable que almacena información relacionada con Megapayz.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use \Backend\integrations\payment\Megapayz;

header('Content-Type: application/json');

/* Obtenemos Variables que nos llegan */
$data = (file_get_contents('php://input'));
$data = json_decode($data);

if (isset($data)) {

    //Megapayz
    $trx = $data->trx;
    $externoId = $data->transaction_id;
    $status = $data->status;
    $amonut = $data->amount;

    if ($status == 'confirmed') {
        $status = 'SUCCESS';
    } else {
        $status = 'PROGRESS';
    }

    /* Procesamos */
    $Megapayz = new Megapayz($trx, $status, $externoId, $amonut);
    $response = $Megapayz->confirmation(json_encode($data));

    $response = json_decode($response);

    $data_ = array();
    if ($response->result == 'success') {
        $data_["status"] = true;
        $data_["code"] = 200;
        $data_["message"] = "OK";
    } elseif ($response->result == 'error') {
        $data_["status"] = true;
        $data_["code"] = 200;
        $data_["message"] = "OK";
    } elseif ($response->result == '') {
        $data_["status"] = true;
        $data_["code"] = 200;
        $data_["message"] = "OK";
    } else {
        $data_["status"] = false;
        $data_["code"] = 99999;
        $data_["message"] = "ERROR";
    }

    echo json_encode($data_);
}
