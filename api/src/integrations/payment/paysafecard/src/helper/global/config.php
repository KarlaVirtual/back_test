<?php
/**
* Clase 'Config'
*
* Esta clase provee funciones para la api 'Config'
*
* Ejemplo de uso:
* $Config = new Config();
*
*
* @package ninguno
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public
* @see no
*
*/
class Config
{

    /**
    * Representación de 'data'
    *
    * @var array
    * @access protected
    */
    protected $data = array();


    /**
     * Método get
     *
     *
     * @param String $key key
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function get($key)
    {
        return $this->data[$key];
    }

    /**
     * Método has
     *
     *
     * @param String $key key
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function has($key)
    {
        return isset($this->data[$key]);
    }

    /**
     * Método set
     *
     *
     * @param String $key key
     * @param String $value value
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * Método load
     *
     *
     * @param String $function function
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function load($function)
    {
        $file = DIR_FUNCTION . $function . '/config.php';
        if (file_exists($file)) {
            $_ = array();

            require($file);

            $this->data = array_merge($this->data, $_);
        } else {
            trigger_error('Error: Could not load config ' . $function . '!');
            exit();
        }
    }
}