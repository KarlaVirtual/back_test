<?php

/**
 * Configura la visualización de errores y establece la zona horaria.
 * 
 * @param array $_ENV Variables de entorno que determinan el modo de depuración.
 * @param array $_SERVER Variables del servidor, como HTTP_ORIGIN, para configurar el acceso.
 * 
 * @return void No devuelve ningún valor, pero configura el entorno de ejecución.
 */

/* Configura la visualización de errores PHP según el entorno de depuración. */
ini_set('display_errors', 'off');
if ($_ENV['debug']) {
    error_reporting(E_ALL);
    ini_set("display_errors", "ON");
    $_ENV["debugFixed"] = '1';
    $debugFixed = '1';
}
require_once __DIR__ . '../../vendor/autoload.php';


/* Establece la zona horaria y permite el acceso desde orígenes específicos. */
date_default_timezone_set('America/Bogota');

//header('Access-Control-Allow-Origin: http://127.0.0.1:5500');
//header('Access-Control-Allow-Origin: http://localhost');
//header('Access-Control-Allow-Origin: https://cert.doradobet.com');
header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);


/* Configura la visualización de errores en modo debug si está habilitado. */
if ($_ENV['debug']) {
    error_reporting(E_ALL);
    ini_set("display_errors", "ON");
}