<?php

/**
 * Archivo de configuración para la integración de pagos con Paysafecard.
 *
 * Este archivo define constantes y configuraciones necesarias para el correcto
 * funcionamiento del sistema de integración con Paysafecard.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

// Configuración de errores
error_reporting(E_ALL);

// Verificación de versión de PHP
if (phpversion() < 5) {
    die('PHP satisface la versión ' . phpversion() . ' pero no cumple con el requisito');
}

// Verificación de extensión SOAP
if ( ! in_array('soap', get_loaded_extensions())) {
    echo 'El archivo php_soap.dll no fue cargado.';
    exit;
}

// Configuración de cabecera
header("Content-Type: text/html; charset=utf-8");

// Definición de rutas del sistema
define('DIR_APP', __DIR__ . '/');
define('DIR_FUNCTION', __DIR__ . '/function/');
define('DIR_HELPER_GLOBAL', __DIR__ . '/helper/global/');
define('DIR_LOG', __DIR__ . '/log/');

// Definición de dominio de uso
define('DOMAIN', 'http://127.0.0.1:8080/sample/');
