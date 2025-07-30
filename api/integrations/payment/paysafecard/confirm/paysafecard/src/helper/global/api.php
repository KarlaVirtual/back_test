<?php

/**
 * Este archivo contiene la clase `Api` que proporciona métodos para interactuar con un cliente SOAP.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-05-09
 */

/**
 * Clase Api
 *
 * Proporciona métodos para inicializar un cliente SOAP, realizar acciones y manejar errores.
 */
class Api
{

    /**
     * Cliente SOAP utilizado para realizar las solicitudes.
     *
     * @var \SoapClient|null
     */
    public $client = null;

    /**
     * Resultado de la última acción ejecutada en el cliente SOAP.
     *
     * @var mixed
     */
    public $result;

    /**
     * Mensaje de error generado durante la última acción SOAP.
     *
     * @var string|null
     */
    public $error;

    /**
     * Datos utilizados internamente por la clase.
     *
     * @var array
     */
    protected $_data = array();

    /**
     * Objeto de registro que contiene configuraciones necesarias.
     *
     * @var object
     */
    protected $_registry;

    /**
     * Configuración para la conexión SOAP.
     *
     * @var array
     */
    protected $_settings = array();


    /**
     * Constructor de la clase Api.
     *
     * @param object $_registry Objeto de registro que contiene configuraciones necesarias.
     */
    public function __construct($_registry)
    {
        $this->_registry = $_registry;
    }

    /**
     * Inicializa un nuevo cliente SOAP.
     *
     * @param bool $reinit Indica si se debe reinicializar el cliente existente.
     *
     * @return boolean Devuelve `true` si el cliente se inicializó correctamente, de lo contrario `false`.
     */
    public function newClient($reinit = false)
    {
        if ($this->client && $reinit == false) {
            return true;
        }
        $this->soapInit();
        try {
            $this->client = new SoapClient($this->_registry->config->get('Mode') == 'test' ? $this->_registry->config->get('ApiWsdlSandbox') : $this->_registry->config->get('ApiWsdlProductive'), $this->_settings);
            return true;
        } catch (SoapFault $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * Configura los parámetros necesarios para la conexión SOAP.
     *
     * Verifica si la extensión SOAP está habilitada y configura los ajustes
     * según el modo (prueba o productivo).
     *
     * @return void
     */
    private function soapInit()
    {
        if ( ! class_exists("SOAPClient")) {
            die('ERROR: SOAPClient isn´t enabled');
        }
        $this->_settings = array(
            'encoding' => 'utf-8',
            'connection_timeout' => 15,
            'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
            'compression' => SOAP_COMPRESSION_GZIP
        );
        if ($this->_registry->config->get('Mode') == 'test') {
            ini_set("soap.wsdl_cache_enabled", '0');
            ini_set("soap.wsdl_cache_ttl", '0');
            $wsdl = array(
                'user_agent' => 'steinweber UG class(testmode) v1.1',
                'trace' => true,
                'exceptions' => true,
                'cache_wsdl' => WSDL_CACHE_NONE
            );
        } else {
            $wsdl = array(
                'user_agent' => 'steinweber UG class v1.1',
                'trace' => false,
                'exceptions' => false
            );
        }
        $this->_settings = array_merge($this->_settings, $wsdl);
    }

    /**
     * Ejecuta una acción en el cliente SOAP.
     *
     * @param string $action    Nombre de la acción a ejecutar.
     * @param mixed  $parameter Parámetros necesarios para la acción.
     *
     * @return boolean Devuelve `true` si la acción se ejecutó correctamente, de lo contrario `false`.
     */
    public function action($action, $parameter)
    {
        try {
            $this->result = $this->client->{$action}($parameter);
            return true;
        } catch (SoapFault $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

}