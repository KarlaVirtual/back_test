<?php

/**
 * Este archivo contiene un script para verificar torneos en un sistema de casino.
 * Utiliza clases y métodos relacionados con la gestión de torneos internos y
 * permite procesar argumentos pasados por línea de comandos o solicitudes manuales.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */

/**
 * Variables utilizadas:
 *
 * @var mixed $log           Variable para registrar mensajes y eventos de log.
 * @var mixed $argv          Argumentos pasados al script desde la línea de comandos.
 * @var mixed $arg1          Argumento 1, utilizado para identificar el tipo de operación.
 * @var mixed $_REQUEST      Superglobal que contiene datos enviados por REQUEST.
 * @var mixed $arg2          Argumento 2, utilizado para identificar IDs específicos.
 * @var mixed $arg3          Argumento 3, utilizado para identificar el usuario.
 * @var mixed $detalles2     Detalles de la operación, como juegos y valores de apuesta.
 * @var mixed $item          Elemento genérico en una lista o estructura de datos.
 * @var mixed $TorneoInterno Instancia de la clase TorneoInterno.
 * @var mixed $respuesta     Respuesta de la operación de verificación.
 * @var mixed $respuesta2    Respuesta adicional de la operación.
 * @var mixed $arg4          Argumento 4, utilizado para datos adicionales.
 */

require_once __DIR__ . '../../../../vendor/autoload.php';

use Backend\dto\BonoInterno;
use Backend\dto\TorneoInterno;


$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . json_encode($argv);
$log = $log . trim(file_get_contents('php://input'));
//Save string to log, use FILE_APPEND to append.

fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


if ($argv[1] == 'MANUAL') {
    exit();
} else {
    $arg1 = $argv[1];
    $arg2 = $argv[2];
    $arg3 = $argv[3];
    $arg4 = $argv[4];
}

$detalles2 = array(
    "JuegosCasino" => array(
        array(
            "Id" => 2
        )

    ),
    "ValorApuesta" => 2000
);


$TorneoInterno = new TorneoInterno();
$respuesta = $TorneoInterno->verificarTorneoUsuario($arg3, $detalles2, $arg1, $arg2, $arg4);
