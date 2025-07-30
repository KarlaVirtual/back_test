<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con Sagicor.
 * Procesa datos recibidos en formato JSON, registra logs para depuración y utiliza
 * clases del backend para manejar la lógica de negocio.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payment\Sagicor
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $data     Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $log      Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $_REQUEST Variable superglobal que contiene datos enviados a través del método REQUEST.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');


use Backend\integrations\payment\Sagicor;


/* Obtenemos Variables que nos llegan */
header('Content-Type: application/json');

$data = (file_get_contents('php://input'));

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . json_encode($_REQUEST);
$log = $log . trim(file_get_contents('php://input'));
fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

$data = json_decode($data);


