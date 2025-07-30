<?php

/**
 * Este archivo contiene un script para realizar pruebas de integración con el servicio SMS de OkRoute.
 * Se envía una solicitud HTTP para enviar un mensaje SMS y se procesa la respuesta en formato XML.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Configuración de errores:
 *
 * - Se habilita la visualización de errores para facilitar la depuración.
 */

error_reporting(E_ALL);
ini_set('display_errors', 'ON');

/**
 * Prueba de salida en consola para verificar el flujo del script.
 */
print_r("TEST2");

// Identificador único para seguimiento del script
// 5b755556-3a68-2d83-a171-ada2c09e2270

/**
 * Realiza una solicitud HTTP GET al servicio SMS de OkRoute.
 *
 * @var string $xml Contiene la respuesta en formato XML del servicio SMS.
 */
$xml = file_get_contents("http://185.64.57.141:8001/api?username=dorabe56&password=jojxpryb&ani=2&dnis=51982738676&message=prueba&command=submit&longMessageMode=cut");

/**
 * Pruebas adicionales de salida en consola.
 */
print_r("TEST3");

print_r("TEST3");

/**
 * Muestra la respuesta XML obtenida del servicio SMS.
 */
print_r($xml);
