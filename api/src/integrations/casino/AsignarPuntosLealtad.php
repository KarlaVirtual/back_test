<?php

/**
 * Este archivo contiene un script para asignar puntos de lealtad en un sistema de casino.
 * Se procesan diferentes tipos de transacciones y se asignan puntos de lealtad a los usuarios
 * según las reglas definidas para cada caso.
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
 * @var mixed $log              Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $argv             Esta variable se utiliza para almacenar y manipular los argumentos pasados al script.
 * @var mixed $arg1             Esta variable se utiliza para almacenar y manipular el valor de 'arg1' en el contexto actual.
 * @var mixed $_REQUEST         Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $arg2             Esta variable se utiliza para almacenar y manipular el valor de 'arg2' en el contexto actual.
 * @var mixed $arg3             Esta variable se utiliza para almacenar y manipular el valor de 'arg3' en el contexto actual.
 * @var mixed $detalles2        Esta variable se utiliza para almacenar y manipular el valor de 'detalles2' en el contexto actual.
 * @var mixed $item             Variable que almacena un elemento genérico en una lista o estructura de datos.
 * @var mixed $TorneoInterno    Esta variable se utiliza para almacenar y manipular el valor de 'TorneoInterno' en el contexto actual.
 * @var mixed $respuesta        Esta variable se utiliza para almacenar y manipular la respuesta de una operación.
 * @var mixed $respuesta2       Esta variable se utiliza para almacenar y manipular el valor de 'respuesta2' en el contexto actual.
 * @var mixed $UsuarioRecarga   Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $Usuario          Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $UsuarioMandante  Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $valor            Variable que almacena un valor monetario o numérico.
 * @var mixed $ItTicketEnc      Variable que representa una entidad de ticket en el sistema.
 * @var mixed $TransjuegoLog    Variable que almacena registros de transacciones del sistema Transjuego.
 * @var mixed $TransaccionJuego Esta variable se utiliza para almacenar y manipular el valor de 'TransaccionJuego' en el contexto actual.
 * @var mixed $CuentaCobro      Variable que almacena información sobre una cuenta de cobro.
 * @var mixed $LealtadInterna   Esta variable se utiliza para almacenar y manipular el valor de 'LealtadInterna' en el contexto actual.
 * @var mixed $e                Esta variable se utiliza para capturar excepciones o errores en bloques try-catch.
 */

require_once __DIR__ . '../../../../vendor/autoload.php';

use Backend\dto\CuentaCobro;
use Backend\dto\ItTicketEnc;
use Backend\dto\LealtadInterna;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioRecarga;

ini_set('display_errors', 'OFF');

/**
 * Registra los argumentos y datos de entrada en un archivo de log.
 */
$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . json_encode($argv);
$log = $log . trim(file_get_contents('php://input'));
fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

if ($argv[1] == 'MANUAL') {
    exit();
} else {
    /**
     * Asigna los argumentos pasados al script a variables locales.
     */
    $arg1 = $argv[1];
    $arg2 = $argv[2];
    $arg3 = $argv[3];
}

/**
 * Procesa diferentes tipos de transacciones según el valor de `$arg3`.
 */
switch ($arg3) {
    case 10:
        /**
         * Caso 10: Procesa recargas de usuario.
         */
        $UsuarioRecarga = new UsuarioRecarga($arg2);
        $Usuario = new Usuario($UsuarioRecarga->usuarioId);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
        $valor = $UsuarioRecarga->valor;
        break;

    case 20:
        /**
         * Caso 20: Procesa tickets de apuestas.
         */
        $ItTicketEnc = new ItTicketEnc($arg2);
        $Usuario = new Usuario($ItTicketEnc->usuarioId);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
        $valor = $ItTicketEnc->vlrApuesta;
        break;

    case 30:
        /**
         * Caso 30: Procesa registros de transacciones de juegos.
         */
        $TransjuegoLog = new TransjuegoLog($arg2);
        $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);
        $UsuarioMandante = new UsuarioMandante($TransaccionJuego->usuarioId);
        $valor = $TransjuegoLog->valor;
        print_r($TransjuegoLog);

        if ($arg1 == 'LIVECASINO') {
            $arg3 = 31;
        }
        break;

    case 41:
        /**
         * Caso 41: Procesa cuentas de cobro.
         */
        $CuentaCobro = new CuentaCobro($arg2);

        $UsuarioMandante = new UsuarioMandante("", $CuentaCobro->usuarioId, $CuentaCobro->mandante);
        if ($CuentaCobro->estado == "I") {
            $valor = $CuentaCobro->valor;
        } else {
            $valor = 0;
        }
        break;
}

/**
 * Asigna puntos de lealtad si el mandante cumple con las condiciones.
 */
if ($UsuarioMandante->mandante == 8) {
    try {
        $LealtadInterna = new LealtadInterna();
        $LealtadInterna->AgregarPuntos($UsuarioMandante, 'S', $arg3, $arg2, $valor);
    } catch (Exception $e) {
        print_r($e);
    }
}
if (($UsuarioMandante->mandante == '0' && $UsuarioMandante->paisId == 173)) {
    try {
        $LealtadInterna = new LealtadInterna();
        $LealtadInterna->AgregarPuntos($UsuarioMandante, 'S', $arg3, $arg2, $valor);
    } catch (Exception $e) {
        print_r($e);
    }
}

