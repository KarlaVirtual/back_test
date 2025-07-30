<?php
use Backend\utils\SessionGeneral;

/**
 * Archivo de inclusión para la API 'apipv'.
 *
 * Este archivo configura la zona horaria, desactiva la visualización de errores,
 * gestiona las sesiones y habilita CORS desde el origen del cliente.
 */

/* Desactiva errores, oculta su visualización y establece la zona horaria de Bogotá. */
error_reporting(
    0

);
ini_set("display_errors", "OFF");
require_once __DIR__ . '../../../vendor/autoload.php';

date_default_timezone_set('America/Bogota');
//header('Access-Control-Allow-Origin: http://127.0.0.1:5500');
//header('Access-Control-Allow-Origin: http://localhost');
//header('Access-Control-Allow-Origin: https://cert.doradobet.com');

/* Código PHP para gestionar sesiones y habilitar CORS desde el origen del cliente. */
header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
header('Access-Control-Allow-Credentials: true');


$session = new SessionGeneral();
$session->inicio_sesion('_s', false);
