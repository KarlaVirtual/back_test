<?php

/**
 * Este archivo contiene un script para la activación de la ruleta en el casino.
 * Se encarga de procesar los argumentos proporcionados, registrar logs y realizar
 * la operación de agregar una ruleta en el sistema.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $log              Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $argv             Esta variable se utiliza para almacenar y manipular los argumentos pasados al script.
 * @var mixed $arg1             Esta variable se utiliza para almacenar y manipular el valor de 'arg1' en el contexto actual.
 * @var mixed $UsuarioMandante  Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $arg2             Esta variable se utiliza para almacenar y manipular el valor de 'arg2' en el contexto actual.
 * @var mixed $arg3             Esta variable se utiliza para almacenar y manipular el valor de 'arg3' en el contexto actual.
 * @var mixed $amount           Variable que almacena un monto o cantidad.
 * @var mixed $arg4             Esta variable se utiliza para almacenar y manipular el valor de 'arg4' en el contexto actual.
 * @var mixed $Tipo             Esta variable se utiliza para almacenar y manipular el valor de 'Tipo' en el contexto actual.
 * @var mixed $arg5             Esta variable se utiliza para almacenar y manipular el valor de 'arg5' en el contexto actual.
 * @var mixed $Categoria        Esta variable se utiliza para almacenar y manipular el valor de 'Categoria' en el contexto actual.
 * @var mixed $arg6             Esta variable se utiliza para almacenar y manipular el valor de 'arg6' en el contexto actual.
 * @var mixed $Subproveedor     Variable que almacena información del subproveedor.
 * @var mixed $arg7             Esta variable se utiliza para almacenar y manipular el valor de 'arg7' en el contexto actual.
 * @var mixed $ProductoMandante Esta variable se utiliza para almacenar y manipular el valor de 'ProductoMandante' en el contexto actual.
 * @var mixed $RuletaInterno    Esta variable se utiliza para almacenar y manipular el valor de 'RuletaInterno' en el contexto actual.
 * @var mixed $Response         Esta variable contiene la respuesta generada por una operación o solicitud.
 */

require_once __DIR__ . '../../../../vendor/autoload.php';

use Backend\dto\BonoInterno;
use Backend\dto\CuentaCobro;
use Backend\dto\ItTicketEnc;
use Backend\dto\LealtadInterna;
use Backend\dto\RuletaDetalle;
use Backend\dto\RuletaInterno;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\Usuario;
use Backend\dto\Registro;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioRuleta;
use Backend\mysql\RuletaDetalleMySqlDAO;
use Backend\mysql\UsuarioRuletaMySqlDAO;
use Backend\mysql\CiudadMySqlDAO;

ini_set('display_errors', 'ON');



// Asignación de argumentos
$arg1 = $argv[1]; // Identificador del país del usuario mandante
$arg2 = $argv[2]; // Identificador del usuario mandante
$arg3 = $argv[3]; // Monto o cantidad
$arg4 = $argv[4]; // Tipo de operación
$arg5 = $argv[5]; // Identificador de la categoría
$arg6 = $argv[6]; // Identificador del subproveedor
$arg7 = $argv[7]; // Identificador del producto mandante
print_r('entro');

// Crear una nueva instancia de RuletaInterno y agregar la ruleta
$RuletaInterno = new RuletaInterno();
$Response = $RuletaInterno->agregarRuleta($arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7,'');
print_r($Response);



