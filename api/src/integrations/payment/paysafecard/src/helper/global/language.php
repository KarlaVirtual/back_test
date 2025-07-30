<?php
/** 
* Clase 'Language'
* 
* Esta clase provee funciones para la api 'Language'
* 
* Ejemplo de uso: 
* $Language = new Language();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class Language
{

    /**
    * Representación de 'language'
    *
    * @var string
    * @access private
    */
    public $language;

    /**
    * Representación de '_data'
    *
    * @var string
    * @access protected
    */
    protected $_data = array();

    /**
    * Representación de '_registry'
    *
    * @var string
    * @access protected
    */
    protected $_registry;

    /**
    * Representación de '_dir'
    *
    * @var string
    * @access protected
    */
    protected $_dir;
        
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
        $this->_registry = $_registry;    
        $this->_dir = DIR_FUNCTION.$this->_registry->config->get('LoadedClass').'/language/';
        $languages = glob($this->_dir.'*', GLOB_ONLYDIR);
        $allowedLanguages = array();
        foreach($languages as $language)
        {
           $allowedLanguages[] = str_replace($this->_dir,'',$language);           
        }
        $this->_registry->config->set('AllowedLanguages',$allowedLanguages);
        $this->load();
    }
            
    /**
     * Método load
     *
     * @param String $filename filename
     * @return String $ data
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function load($filename=false) {
        $filename = $filename?$filename:$this->_registry->config->get('Language');
		$file = $this->_dir.$this->_registry->config->get('Language') .'/'. $filename . '.php';
		if (file_exists($file)) {
			$_ = array();

			require($file);

			$this->_data = array_merge($this->_data, $_);

			return $this->_data;
		}
        else
        {
			trigger_error('Error: Could not load language ' . $filename . '!');
		}
	}
            
    /**
     * Método get
     *
     * @param String $key key
     * @return String $ key
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function get($key) {
		return (isset($this->_data[$key]) ? $this->_data[$key] : $key);
	}
}