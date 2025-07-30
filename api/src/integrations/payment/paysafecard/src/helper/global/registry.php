<?php
/**
* Clase 'Okroute'
*
* Esta clase provee funciones para la api 'Okroute'
*
* Ejemplo de uso:
* $Okroute = new Okroute();
*
*
* @package ninguno
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public
* @see no
* @final
*
*/
final class Registry
{

    /**
    * Representación de 'language'
    *
    * @var string
    * @access public
    */
    public $language;

    /**
    * Representación de 'debug'
    *
    * @var string
    * @access public
    */
    public $debug;

    /**
    * Representación de 'config'
    *
    * @var string
    * @access public
    */
    public $config;

    /**
    * Representación de 'api'
    *
    * @var string
    * @access public
    */
    public $api;

    /**
    * Representación de 'data'
    *
    * @var array
    * @access private
    */
	private $data = array();

    /**
     * Método get
     *
     *
     * @return String $key key
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
	public function get($key) {
		return (isset($this->data[$key]) ? $this->data[$key] : null);
	}

    /**
     * Método set
     *
     *
     * @return String $key key
     * @return String $value value
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
	public function set($key, $value) {
		$this->data[$key] = $value;
	}

    /**
     * Método has
     *
     *
     * @return String $key key
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
	public function has($key) {
		return isset($this->data[$key]);
	}
}
?>