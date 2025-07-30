<?php

/**
 * Este archivo maneja la integración con el servicio de autenticación AUCOSERVICES.
 * Procesa solicitudes HTTP entrantes, registra logs y devuelve respuestas basadas en los datos procesados.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables usadas en el script:
 *
 * @var mixed $log          Variable para registrar mensajes y eventos de log en el sistema.
 * @var mixed $_SERVER      Superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $body         Contenido del cuerpo de la solicitud HTTP.
 * @var mixed $URI          URI de la petición actual.
 * @var mixed $data         Datos procesados o retornados, incluyendo estructuras complejas.
 * @var mixed $AUCOSERVICES Instancia para gestionar operaciones específicas de AUCOSERVICES.
 * @var mixed $response     Respuesta generada por una operación o petición.
 * @var mixed $e            Excepciones o errores capturados en bloques try-catch.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\auth\AUCOSERVICES;

// Registro inicial del log con la URI y el cuerpo de la solicitud
$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . $_SERVER['REQUEST_URI'];
$log = $log . trim(file_get_contents('php://input'));

// Guardar el log en un archivo
fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

// Obtener el cuerpo de la solicitud HTTP
$body = file_get_contents('php://input');

// Obtener el URI de la solicitud
$URI = $_SERVER['REQUEST_URI'];

if ($body != "") {
    try {
        // Decodificar el cuerpo de la solicitud como JSON
        $data = json_decode($body);

        /* Procesar los datos con AUCOSERVICES */
        $AUCOSERVICES = new AUCOSERVICES();
        $response = ($AUCOSERVICES->process($data));

        // Registrar la respuesta en el log
        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);
        //Save string to log, use FILE_APPEND to append.

        // Guardar el log en un archivo
        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

        // Imprimir la respuesta
        print_r($response);
    } catch (Exception $e) {
    }
}



