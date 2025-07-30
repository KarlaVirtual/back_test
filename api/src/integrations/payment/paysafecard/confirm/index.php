<?php
require(__DIR__ . '../../../../../vendor/autoload.php');
use Backend\integrations\payment\PaySafecard;

/**
 * Confirmar Astropay
*
*
* @package ninguno
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public
* @see no
* @date: 06.09.17
*
*/


$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . json_encode($_REQUEST);
$log = $log . trim(file_get_contents('php://input'));
//Save string to log, use FILE_APPEND to append.

fwriteCustom('log_' . date("Y-m-d") . '.log',$log);

//{"pn":"1","mtid":"170","eventType":"ASSIGN_CARDS","serialNumbers":"9805101157;EUR;25.00;IE00013;"}

$body = json_encode($_REQUEST);


if ($body != "") {
    $data = json_decode($body);

    $result = $data->pn;

    $invoice = $data->mtid;

    $usuario_id = $data->mtid;

    $documento_id = explode(";",$data->serialNumbers)[0];

    $valor = explode(";",$data->mtid)[2];

    $control = $data->mtid;

    /* Procesamos */

    $PaySafecard = new PaySafecard($invoice, $usuario_id, $documento_id, $valor, $control, $result);

    $PaySafecard->confirmation();
}