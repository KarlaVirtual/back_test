<?php
/**
* Clase 'paysafecard_base'
*
* Esta clase provee funciones para la api 'paysafecard_base'
*
* Ejemplo de uso:
* $paysafecard_base = new paysafecard_base();
*
*
* @package ninguno
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public
* @see no
* @date: 14.09.17
*
*/
class paysafecard_base
{

    /**
    * Representación de 'registry'
    *
    * @var string
    * @access public
    */
    public $registry;

    /**
    * Representación de '_error'
    *
    * @var string
    * @access protected
    */
    protected $_error;

    /**
    * Representación de '_customerInfo'
    *
    * @var string
    * @access protected
    */
    protected $_customerInfo;

    /**
     * Método para establecer la depuración
     *
     *
     * @param String $status status
     * @param String $logFile logFile
     *
     * @return no
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function setDebug($status=false, $logFile=false)
    {
        $this->registry->config->set('DebugStatus',$status);
        if($logFile)
        {
            $this->registry->config->set('LogFile',$logFile);
        }
    }

    /**
     * Método para establecer un lenguaje
     *
     *
     * @param String $language language
     *
     * @return no
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function setLanguage($language = 'de')
    {
        if (in_array($language, $this->registry->config->get('AllowedLanguages'))) {
            $this->registry->config->set('Language',$language);
        } else {
            $this->registry->config->set('Language',$this->registry->config->get('AllowedLanguages')[0]);
        }
        $this->registry->config->set('LanguageFolder',$this->registry->config->get('DirLanguage').$this->language.'/');
        $this->load($this->registry->config->get('Language'));
    }

    /**
     * Método para establecer un comerciante
     *
     *
     * @param String $username username
     * @param String $password password
     *
     * @return no
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function setMerchant($username,$password)
    {
        $this->registry->config->set('merchant',array('username'=>$username,'password' => $password));
    }

    /**
     * Método para establecer un modo
     *
     *
     * @param String $mode mode
     *
     * @return no
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function setMode($mode)
    {
        $this->registry->config->set('Mode',$mode);
    }

    /**
     * Método para obtener un error
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getError()
    {
        return $this->_error;
    }

     /**
     * Método para establecer un error
     *
     *
     * @param String $error error
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    protected function setError($error)
    {
        $this->_error = $error;
        $this->registry->debug->_debug($error);
    }

     /**
     * Método para obtener la información del cliente
     *
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getCustomerInfo()
    {
        return $this->_customerInfo;
    }

    /**
     * Método para cargar la configuración
     *
     *
     * @param String $loadedClass loadedClass
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    protected function loadConfig($loadedClass)
    {
        $this->registry = new Registry();
        $this->registry->config = new Config();
        $this->registry->config->set('LoadedClass',$loadedClass);
        $this->registry->config->load($loadedClass);
        $this->registry->language = new Language($this->registry);
        $this->registry->debug = new Debug($this->registry);
        $this->registry->api = new Api($this->registry);
    }
}