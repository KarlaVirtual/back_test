<?php

/**
 * Este archivo contiene un script para verificar sorteos en un sistema de casino.
 * Utiliza clases y métodos relacionados con sorteos internos y transacciones de juego.
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
 * @var mixed $arg1          Primer argumento del script, utilizado en el contexto del sorteo.
 * @var mixed $_REQUEST      Superglobal que contiene datos enviados al script.
 * @var mixed $arg2          Segundo argumento del script, utilizado en el contexto del sorteo.
 * @var mixed $arg3          Tercer argumento del script, utilizado en el contexto del sorteo.
 * @var mixed $detalles2     Detalles relacionados con los juegos de casino y apuestas.
 * @var mixed $item          Elemento genérico en una lista o estructura de datos.
 * @var mixed $SorteoInterno Instancia de la clase SorteoInterno para manejar sorteos.
 * @var mixed $respuesta     Respuesta de una operación de verificación de sorteo.
 * @var mixed $respuesta2    Respuesta adicional de una operación de verificación de sorteo.
 * @var mixed $arg4          Cuarto argumento del script, utilizado en el contexto del sorteo.
 */

require_once __DIR__ . '../../../../vendor/autoload.php';

use Backend\dto\BonoInterno;
use Backend\dto\SorteoInterno;


$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . json_encode($argv);
$log = $log . trim(file_get_contents('php://input'));

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


$SorteoInterno = new SorteoInterno();
$respuesta = $SorteoInterno->verificarSorteoUsuario($arg3, $detalles2, $arg1, $arg2, $arg4);
