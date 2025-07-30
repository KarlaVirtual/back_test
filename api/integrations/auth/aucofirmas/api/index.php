<?php

/**
 * Este archivo contiene un script para procesar solicitudes HTTP relacionadas con la integración de AUCOSERVICES.
 * Se encarga de registrar logs, procesar datos de entrada y generar respuestas basadas en las operaciones realizadas.
 *
 * @category Seguridad
 * @package  Auth
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-05-09
 */

/**
 * Documentación de variables utilizadas en el archivo:
 *
 * @var string       $log          Variable utilizada para registrar mensajes y eventos de log en el sistema.
 * @var array        $_SERVER      Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var string       $body         Contenido del cuerpo de la solicitud HTTP.
 * @var string       $URI          URI de la petición actual.
 * @var mixed        $data         Datos procesados o retornados, que pueden incluir estructuras complejas.
 * @var AUCOSERVICES $AUCOSERVICES Instancia de la clase AUCOSERVICES para gestionar operaciones específicas.
 * @var mixed        $response     Respuesta generada por una operación o petición.
 * @var Exception    $e            Excepción capturada en caso de errores durante la ejecución.
 */


require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\auth\AUCOSERVICES;

// Inicialización del log con información básica de la solicitud
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

        // Procesar los datos utilizando la clase AUCOSERVICES
        $AUCOSERVICES = new AUCOSERVICES();
        $response = ($AUCOSERVICES->processSignature($data));

        // Registrar la respuesta en el log
        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);
        //Save string to log, use FILE_APPEND to append.

        // Guardar el log actualizado en un archivo
        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

        // Imprimir la respuesta generada
        print_r($response);
    } catch (Exception $e) {
    }
}



