<?php

/** Realiza la carga del autoload y define la zona horaria */


/* configura la gestión de errores y permisos CORS en PHP. */
error_reporting(0);
ini_set('display_errors', 'off');
require_once __DIR__ . '../../vendor/autoload.php';

date_default_timezone_set('America/Bogota');

//header('Access-Control-Allow-Origin: http://127.0.0.1:5500');
//header('Access-Control-Allow-Origin: http://localhost');
//header('Access-Control-Allow-Origin: https://cert.doradobet.com');
header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);


