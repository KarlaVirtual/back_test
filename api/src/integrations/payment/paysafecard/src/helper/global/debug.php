<?php
/** 
* Clase 'Debug'
* 
* Esta clase provee funciones para la api 'Debug'
* 
* Ejemplo de uso: 
* $Debug = new Debug();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class Debug
{

    /**
    * Representación de '_status'
    *
    * @var string
    * @access protected
    */
    protected $_status;

    /**
    * Representación de '_logFile'
    *
    * @var string
    * @access protected
    */
    protected $_logFile;
    
    /**
     * Método constructor
     *
     * @param String $_registry _registry
     * 
     * @return no
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($_registry)
    {
        $this->_status = $_registry->config->get('DebugStatus');
        $this->_logFile = DIR_LOG.$_registry->config->get('LogFile');
    }
        
    /**
     * Método debug
     *
     * @param String $message message
     * @param array $data data
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
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