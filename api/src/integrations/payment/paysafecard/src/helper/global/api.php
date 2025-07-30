<?php
/** 
* Clase 'Api'
* 
* Esta clase provee funciones para la api 'Api'
* 
* Ejemplo de uso: 
* $Api = new Api();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class Api
{

    /**
    * Representación de 'client'
    *
    * @var string
    * @access public
    */	
    public $client=NULL;

    /**
    * Representación de 'result'
    *
    * @var string
    * @access public
    */
    public $result;
    
    /**
    * Representación de 'error'
    *
    * @var string
    * @access public
    */
    public $error;
    
    /**
    * Representación de '_data'
    *
    * @var array
    * @access protected
    */
    protected $_data = array();
    
    /**
    * Representación de '_registry'
    *
    * @var array
    * @access protected
    */
    protected $_registry;
   
    /**
    * Representación de '_settings'
    *
    * @var array
    * @access protected
    */
    protected $_settings = array();
    

    /**
     * Método constructor
     *
     * @param array $_registry _registry 
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
        $this->_registry = $_registry;        
        
    }

    /**
     * Método para crear un nuevo cliente SOAP
     *
     *
     * @param boolean $reinit reinit
     *
     * @return boolean $ resultado de la operación
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function newClient($reinit=false)
	{
	   if($this->client && $reinit==false)
       {
        return true;
       }
		$this->soapInit();
		try
		{
			$this->client = new SoapClient( $this->_registry->config->get('Mode') == 'test' ? $this->_registry->config->get('ApiWsdlSandbox') : $this->_registry->config->get('ApiWsdlProductive'), $this->_settings );
            return true;
		}
		catch ( SoapFault $e )
		{
			$this->error = $e->getMessage();
            return false;
		}
	}
    
    /**
     * Método para iniciar una petición SOAP
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    private function soapInit()
	{
		// Comprobar si la función SOAP está activada
		if ( !class_exists( "SOAPClient" ) )
		{
			die( 'ERROR: SOAPClient isn´t enabled' );
		}

		// Cargar los parámetros para la conexión
		$this->_settings = array(
			'encoding' => 'utf-8',
			'connection_timeout' => 15,
			'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
			'compression' => SOAP_COMPRESSION_GZIP );
		
		// Modo prueba
		if ( $this->_registry->config->get('Mode') == 'test' )
		{
			ini_set( "soap.wsdl_cache_enabled", '0' );
			ini_set( "soap.wsdl_cache_ttl", '0' );
			$wsdl = array(
				'user_agent' => 'steinweber UG class(testmode) v1.1',
				'trace' => true,
				'exceptions' => true,
				'cache_wsdl' => WSDL_CACHE_NONE );
		}
		else
		{
			$wsdl = array(
				'user_agent' => 'steinweber UG class v1.1',
				'trace' => false,
				'exceptions' => false );
		}
		$this->_settings = array_merge( $this->_settings, $wsdl );
	}
    
    /**
     * Método para accionar
     *
     *
     * @param String $action action
     * @param array $parameter parameter
     *
     * @return boolean $ resultado de la operación
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function action($action,$parameter)
    {
        try
		{
			$this->result = $this->client->{$action}($parameter);
            return true;
		}
		catch ( SoapFault $e )
		{
			$this->error = $e->getMessage();
            return false;
		}
    }
    
}