<?php

/**
 * Este archivo contiene un script para verificar el rollover de bonos en un sistema de casino.
 * Se encarga de procesar datos de entrada, ya sea desde la superglobal $_REQUEST o desde los argumentos
 * de línea de comandos, y utiliza la clase BonoInterno para realizar la verificación del bono.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed       $_REQUEST    Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed       $arg1        Tipo de operación a realizar.
 * @var mixed       $arg2        ID asociado a la operación.
 * @var mixed       $arg3        ID del usuario.
 * @var mixed       $arg4        ID de la transacción del juego.
 * @var mixed       $arg5        Tiempo de espera en microsegundos.
 * @var mixed       $argv        Argumentos pasados al script desde la línea de comandos.
 * @var array       $detalles2   Detalles de la apuesta, incluyendo juegos y valor de la apuesta.
 * @var BonoInterno $BonoInterno Instancia de la clase BonoInterno para verificar el rollover del bono.
 * @var mixed       $respuesta   Resultado de la verificación del bono.
 */

require_once __DIR__ . '../../../../vendor/autoload.php';

use Backend\dto\BonoInterno;

sleep(2);

if ($_REQUEST["tipo"] != "" && $_REQUEST["id"] != "") {
    // Asignación de valores desde $_REQUEST
    $arg1 = $_REQUEST["tipo"];
    $arg2 = $_REQUEST["id"];
    $arg3 = $_REQUEST["userid"];
    $arg4 = $_REQUEST["transjueglogId"];
    $arg5 = $_REQUEST["msleep"];
} else {
    // Asignación de valores desde los argumentos de línea de comandos
    $arg1 = $argv[1];
    $arg2 = $argv[2];
    $arg3 = $argv[3];
    $arg4 = $argv[4];
    $arg5 = $argv[5];
}

if ($arg5 != null && $arg5 > 0) {
    // Pausa en microsegundos si se especifica
    usleep($arg5);
}

// Detalles de la apuesta
$detalles2 = array(
    "JuegosCasino" => array(
        array(
            "Id" => 2
        )
    ),
    "ValorApuesta" => 2000
);

print_r($argv);

// Creación de la instancia de BonoInterno y verificación del bono
$BonoInterno = new BonoInterno();
$respuesta = $BonoInterno->verificarBonoRollower($arg3, $detalles2, $arg1, $arg2, $arg4);
print_r('entro');
print_r($respuesta);

