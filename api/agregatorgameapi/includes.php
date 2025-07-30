<?php
use Backend\utils\SessionGeneral;

/** Configuración de errores y habilitación de sesión para el usuario */

/* Configura errores y permite accesos CORS desde orígenes específicos en PHP. */
error_reporting(0);
ini_set('display_errors', 'off');
require_once __DIR__ . '../../vendor/autoload.php';

date_default_timezone_set('America/Bogota');

//header('Access-Control-Allow-Origin: http://127.0.0.1:5500');
//header('Access-Control-Allow-Origin: http://localhost');
//header('Access-Control-Allow-Origin: https://cert.doradobet.com');
header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);


/* Se inicia una sesión general con parámetros específicos en el código proporcionado. */
$session = new SessionGeneral();
$session->inicio_sesion('_s', false);