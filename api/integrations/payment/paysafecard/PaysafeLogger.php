<?php

/**
 * Este archivo contiene la clase `PaysafeLogger`, utilizada para registrar
 * información de solicitudes, respuestas HTTP y otros datos relacionados
 * con la integración de Paysafecard.
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
 * Clase responsable de registrar información en un archivo de log, incluyendo
 * detalles de solicitudes, respuestas HTTP y otros datos relevantes.
 */
class PaysafeLogger
{
    /**
     * Nombre del archivo de log donde se registrará la información.
     *
     * @var string
     */
    private $filename = "";

    /**
     * Constructor de la clase PaysafeLogger.
     *
     * Inicializa el nombre del archivo de log y configura la localización
     * para el formato de fecha y hora.
     *
     * @param string $filename Nombre del archivo de log (por defecto: "log.txt").
     */
    public function __construct($filename = "log.txt")
    {
        $this->filename = $filename;
        setlocale(LC_TIME, "de_DE");
    }

    /**
     * Registra información en el archivo de log.
     *
     * Escribe en el archivo de log la fecha y hora de la solicitud,
     * los datos de la solicitud, la información HTTP y la respuesta.
     *
     * @param mixed $request  Datos de la solicitud.
     * @param mixed $http     Información HTTP.
     * @param mixed $response Respuesta recibida.
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
