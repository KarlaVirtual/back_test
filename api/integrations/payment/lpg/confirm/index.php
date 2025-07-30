<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con LPG.
 * Procesa datos enviados mediante solicitudes HTTP, valida la información y realiza
 * operaciones relacionadas con la confirmación de transacciones.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payment\LPG
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $data       Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $_REQUEST   Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $fp         Variable que almacena información sobre la forma de pago.
 * @var mixed $confirm    Variable que almacena la confirmación de una operación o transacción.
 * @var mixed $public_key Variable que almacena una clave pública utilizada en procesos de cifrado.
 * @var mixed $time       Variable que almacena información de tiempo.
 * @var mixed $channel    Variable que almacena el canal por el cual se ejecuta una operación.
 * @var mixed $amount     Variable que almacena un monto o cantidad.
 * @var mixed $currency   Variable que almacena la moneda utilizada en una transacción.
 * @var mixed $trans_ID   Variable que almacena el identificador de una transacción.
 * @var mixed $signature  Esta variable se utiliza para almacenar y manipular la firma digital.
 * @var mixed $LPG        Variable que almacena información sobre LPG (posiblemente un sistema o empresa).
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\payment\LPG;


/* Obtenemos Variables que nos llegan */

$data = json_encode($_REQUEST);

$fp = fopen('log3.log', 'a');

fwrite($fp, json_encode($data));

fclose($fp);

$data = json_decode($data);


if (isset($data)) {
    $confirm = ($data);

    $public_key = $confirm->public_key;
    $time = $confirm->time;
    $channel = $confirm->channel;
    $amount = $confirm->amount;
    $currency = $confirm->currency;
    $trans_ID = $confirm->trans_ID;
    $signature = $confirm->signature;
    if ($trans_ID != "") {
        /* Procesamos */

        $LPG = new LPG($trans_ID, 0, $trans_ID, $amount, $signature, "approved");

        print_r($LPG->confirmation());
    }
}

if ($_REQUEST["pagada"] != "") {
    /* Procesamos */

    $LPG = new LPG($_REQUEST["pagada"], 0, $_REQUEST["pagada"], $_REQUEST["amount"], 0, "approved");

    print_r($LPG->confirmation());
}