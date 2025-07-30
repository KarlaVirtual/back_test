<?php

/**
 * Este archivo contiene la clase `Debug`, utilizada para registrar mensajes de depuración
 * en un archivo de log cuando la funcionalidad de depuración está habilitada.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Clase Debug
 *
 * Proporciona métodos para registrar mensajes de depuración en un archivo de log.
 */
class Debug
{

    /**
     * Estado de la depuración, habilitado o deshabilitado.
     *
     * @var boolean
     */
    protected $_status;

    /**
     * Ruta del archivo de log donde se registran los mensajes de depuración.
     *
     * @var string
     */
    protected $_logFile;

    /**
     * Constructor de la clase Debug.
     *
     * Inicializa el estado de depuración y el archivo de log a partir de la configuración.
     *
     * @param object $_registry Objeto de registro que contiene la configuración.
     */
    public function __construct($_registry)
    {
        $this->_status = $_registry->config->get('DebugStatus');
        $this->_logFile = DIR_LOG . $_registry->config->get('LogFile');
    }

    /**
     * Registra un mensaje de depuración en el archivo de log.
     *
     * Si la depuración está habilitada, escribe el mensaje junto con la fecha y hora
     * en el archivo de log especificado.
     *
     * @param string $message Mensaje a registrar.
     * @param array  $data    Datos adicionales opcionales que se serializan y se añaden al mensaje.
     *
     * @return void
     */
    public function _debug($message, $data = array())
    {
        if ($this->_status == true) {
            $time = date("[Y-m-d H:i:s] ");
            $message .= $data != array() ? " | " . serialize($data) : "";
            error_log($time . $message . "\n \r", 3, $this->_logFile);
        }
    }
}