<?php

/**
 * Configuración inicial para la integración de pagos con Paysafecard.
 *
 * Este archivo define constantes y verifica requisitos básicos para el correcto
 * funcionamiento del sistema, como la versión de PHP y extensiones necesarias.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

error_reporting(E_ALL);
if (phpversion() < 5) {
    die('PHP satisfied with the version ' . phpversion() . ' not the requirement');
}
if ( ! in_array('soap', get_loaded_extensions())) {
    echo 'The php_soap.dll was not loaded.';
    exit;
}
header("Content-Type: text/html; charset=utf-8");


//Sistema
define('DIR_APP', __DIR__ . '/');
define('DIR_FUNCTION', __DIR__ . '/function/');
define('DIR_HELPER_GLOBAL', __DIR__ . '/helper/global/');
define('DIR_LOG', __DIR__ . '/log/');
//Usage
define('DOMAIN', 'http://127.0.0.1:8080/sample/');