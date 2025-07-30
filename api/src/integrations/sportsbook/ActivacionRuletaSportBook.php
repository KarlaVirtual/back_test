<?php

/**
 * Verificar torneo
 *
 * Este script se utiliza para procesar y agregar información relacionada con la ruleta.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-21
 */

require_once __DIR__ . '../../../../vendor/autoload.php';

use Backend\dto\BonoInterno;
use Backend\dto\RuletaDetalle;
use Backend\dto\RuletaInterno;
use Backend\dto\UsuarioRuleta;
use Backend\mysql\RuletaDetalleMySqlDAO;
use Backend\mysql\UsuarioRuletaMySqlDAO;

// Desactiva la visualización de errores en la salida
ini_set('display_errors', 'OFF');

// Genera un log con los argumentos y el contenido de la entrada
$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . json_encode($argv);
$log = $log . trim(file_get_contents('php://input'));

// Guarda el log en un archivo con la fecha actual
fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

/**
 * Verificar torneo
 *
 * Este script se utiliza para procesar y agregar información relacionada con la ruleta.
 */

// Argumentos recibidos desde la línea de comandos
$arg1 = $argv[1]; // ID del país del usuario mandante (ejemplo: 173)
$arg2 = $argv[2]; // ID del usuario mandante (ejemplo: 167)
$arg3 = $argv[3]; // Valor a recargar (ejemplo: 5)
$arg4 = $argv[4]; // Tipo de operación (ejemplo: 1)
$arg5 = $argv[5]; // Detalles de la ruleta o ID del ticket

// Crea una instancia de RuletaInterno
$RuletaInterno = new RuletaInterno();

// Llama al método para agregar información de la ruleta
$Response = $RuletaInterno->agregarRuleta($arg1, $arg2, $arg3, $arg4, "", "", "", $arg5);


