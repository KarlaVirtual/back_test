<?php

/**
 * Este script configura el entorno inicial del servidor, incluyendo la configuración de errores,
 * la carga de dependencias y la habilitación de CORS.
 * 
 * @param string $_SERVER['HTTP_ORIGIN'] Origen de la solicitud HTTP, utilizado para configurar CORS.
 * 
 * @return void Este script no devuelve valores directamente.
 */

/* Configura errores, carga dependencias y permite CORS en el servidor. */
ini_set('display_errors', 'of');
require_once __DIR__ . '../../vendor/autoload.php';

date_default_timezone_set('America/Bogota');

//header('Access-Control-Allow-Origin: http://127.0.0.1:5500');
//header('Access-Control-Allow-Origin: http://localhost');
//header('Access-Control-Allow-Origin: https://cert.doradobet.com');
header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);


