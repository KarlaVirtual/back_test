<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con ALPS.
 * Procesa los datos recibidos en formato JSON, valida la firma, y actualiza el estado de la transacción.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payment\Alps
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-07-01
 */

/**
 * Variables Globales:
 *
 * @var mixed $data Esta variable contiene datos que se procesan o retornan.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use \Backend\integrations\payment\Alps;

header('Content-Type: application/json');

$data = (file_get_contents('php://input'));

$esJson = json_decode($data, true);
if (json_last_error() === JSON_ERROR_NONE) {
    $data = json_encode($esJson);
} else {
    parse_str($data, $params);
    $data = json_encode($params);
}

$alps = new Alps();

try {
    if (strpos($_SERVER['REQUEST_URI'], 'approved')) {
        $alps->setStatus('A');
    } else if (strpos($_SERVER['REQUEST_URI'], 'rejected')) {
        $alps->setStatus('R');
    }

    $data = json_decode($data);
    $alps->setTransaccionId($data->trans_ID);
    $alps->setExternalId($data->trans_ID);
    http_response_code(200);
    $response = $alps->confirmation($data);

    if ($response->result  == 'error') {
        http_response_code(500);
    }

    echo json_encode($response);
} catch (Exception $err) {
    http_response_code(500);
    echo json_encode($err->getMessage());
}
