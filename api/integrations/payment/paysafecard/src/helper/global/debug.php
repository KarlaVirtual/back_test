<?php

/**
 * Este archivo contiene la clase Debug, utilizada para registrar mensajes de depuración
 * en un archivo de log cuando el estado de depuración está habilitado.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-05-09
 */
class Debug
{

    /**
     * Indica si el estado de depuración está habilitado.
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
     * Inicializa el estado de depuración y la ruta del archivo de log
     * utilizando la configuración proporcionada en el registro.
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
     * Si el estado de depuración está habilitado, se escribe el mensaje
     * junto con la fecha y hora actuales. Si se proporciona un arreglo
     * de datos, este se serializa y se incluye en el mensaje.
     *
     * @param string $message Mensaje de depuración a registrar.
     * @param array  $data    Datos adicionales opcionales para incluir en el log.
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