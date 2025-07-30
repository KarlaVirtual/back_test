<?php

/**
 * Este archivo contiene la clase `PaysafeLogger`, utilizada para registrar
 * información de solicitudes, respuestas y datos HTTP en un archivo de log.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-05-09
 */

/**
 * Clase PaysafeLogger
 *
 * Clase encargada de registrar información de solicitudes, respuestas y datos HTTP
 * en un archivo de log para propósitos de depuración y monitoreo.
 */
class PaysafeLogger
{
    /**
     * Nombre del archivo donde se registrarán los logs.
     *
     * @var string
     */
    private $filename = "";

    /**
     * Constructor de la clase PaysafeLogger.
     *
     * @param string $filename Nombre del archivo donde se registrarán los logs.
     *                         Por defecto, "log.txt".
     */
    public function __construct($filename = "log.txt")
    {
        $this->filename = $filename;
        setlocale(LC_TIME, "de_DE");
    }

    /**
     * Registra información de la solicitud, respuesta y datos HTTP en el archivo de log.
     *
     * @param mixed $request  Datos de la solicitud a registrar.
     * @param mixed $http     Información HTTP asociada a la solicitud.
     * @param mixed $response Respuesta obtenida de la solicitud.
     *
     * @return void
     */
    public function log($request, $http, $response)
    {
        file_put_contents($this->filename, strftime("Requested at: %A, %d. %B %Y %H:%M:%S\n"), FILE_APPEND | LOCK_EX);
        file_put_contents($this->filename, "\nRequest: ", FILE_APPEND | LOCK_EX);
        file_put_contents($this->filename, print_r($request, true), FILE_APPEND | LOCK_EX);
        file_put_contents($this->filename, "\nHTTP: ", FILE_APPEND | LOCK_EX);
        file_put_contents($this->filename, print_r($http, true), FILE_APPEND | LOCK_EX);
        file_put_contents($this->filename, "\nResponse: ", FILE_APPEND | LOCK_EX);
        file_put_contents($this->filename, print_r($response, true), FILE_APPEND | LOCK_EX);
        file_put_contents($this->filename, "--------------------------------------------\n", FILE_APPEND | LOCK_EX);
    }
}
