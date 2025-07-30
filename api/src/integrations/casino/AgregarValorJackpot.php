<?php

/**
 * Este script se encarga de procesar transacciones relacionadas con apuestas y
 * actualizar el valor del Jackpot interno según el tipo de transacción y vertical.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $log           Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $argv          Esta variable se utiliza para almacenar y manipular los argumentos pasados al script.
 * @var mixed $arg1          Esta variable se utiliza para almacenar y manipular el valor de 'arg1' en el contexto actual.
 * @var mixed $arg2          Esta variable se utiliza para almacenar y manipular el valor de 'arg2' en el contexto actual.
 * @var mixed $vertical      Esta variable se utiliza para almacenar y manipular el valor de 'vertical' en el contexto actual.
 * @var mixed $JackpoInterno Esta variable se utiliza para almacenar y manipular el valor de 'JackpoInterno' en el contexto actual.
 */

require_once __DIR__ . '/../../../vendor/autoload.php';
set_time_limit(0);

use Backend\dto\JackpotInterno;

ini_set('display_errors', 'OFF');

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . json_encode($argv);
$log = $log . trim(file_get_contents('php://input'));
fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


$arg1 = $argv[1]; //Tipo de transaccion (CASINO, LIVECASINO, VIRTUALES, SPORTBOOK)
$arg2 = $argv[2]; //ID Transaccion (transjuego_log.transjuegolog_id o it_ticket_enc.ticket_id)

//Definiendo vertical por la cual sumará la apuesta al Jackpot
$vertical = match ($arg1) {
    'CASINO' => 'CASINO',
    'LIVECASINO' => 'LIVECASINO',
    'VIRTUALES' => 'VIRTUAL',
    'VIRTUAL' => 'VIRTUAL',
    'SPORTBOOK' => 'SPORTBOOK',
    default => null
};

$JackpoInterno = new JackpotInterno();
$JackpoInterno->intentarAcreditarApuesta($vertical, $arg2);






