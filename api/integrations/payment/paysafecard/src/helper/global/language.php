<?php

/**
 * Este archivo contiene la clase `Language`, que se encarga de gestionar
 * la carga y obtención de datos relacionados con los idiomas en el sistema.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-05-09
 */

/**
 * Clase Language
 *
 * Esta clase permite cargar archivos de idioma, gestionar los datos de idioma
 * y obtener valores específicos de las claves de idioma.
 */
class Language
{
    /**
     * Idioma actual configurado en el sistema.
     *
     * @var string
     */
    public $language;

    /**
     * Datos cargados del archivo de idioma.
     *
     * @var array
     */
    protected $_data = array();

    /**
     * Registro del sistema que contiene configuraciones y dependencias.
     *
     * @var object
     */
    protected $_registry;

    /**
     * Directorio donde se encuentran los archivos de idioma.
     *
     * @var string
     */
    protected $_dir;

    /**
     * Constructor de la clase Language.
     *
     * Inicializa el registro, configura el directorio de idiomas,
     * obtiene los idiomas permitidos y carga el idioma predeterminado.
     *
     * @param object $_registry Registro del sistema que contiene configuraciones y dependencias.
     */
    public function __construct($_registry)
    {
        $this->_registry = $_registry;
        $this->_dir = DIR_FUNCTION . $this->_registry->config->get('LoadedClass') . '/language/';
        $languages = glob($this->_dir . '*', GLOB_ONLYDIR);
        $allowedLanguages = array();
        foreach ($languages as $language) {
            $allowedLanguages[] = str_replace($this->_dir, '', $language);
        }
        $this->_registry->config->set('AllowedLanguages', $allowedLanguages);
        $this->load();
    }

    /**
     * Carga un archivo de idioma y fusiona sus datos con los existentes.
     *
     * @param string|boolean $filename Nombre del archivo de idioma a cargar.
     *                                 Si no se especifica, se utiliza el idioma configurado.
     *
     * @return array Datos del idioma cargado.
     * @throws Error Si el archivo de idioma no se encuentra.
     */
    public function load($filename = false)
    {
        $filename = $filename ? $filename : $this->_registry->config->get('Language');
        $file = $this->_dir . $this->_registry->config->get('Language') . '/' . $filename . '.php';
        if (file_exists($file)) {
            $_ = array();

            require($file);

            $this->_data = array_merge($this->_data, $_);

            return $this->_data;
        } else {
            trigger_error('Error: Could not load language ' . $filename . '!');
        }
    }

    /**
     * Obtiene el valor asociado a una clave de idioma.
     *
     * @param string $key Clave del idioma a buscar.
     *
     * @return string Valor asociado a la clave, o la clave misma si no existe.
     */
    public function get($key)
    {
        return (isset($this->_data[$key]) ? $this->_data[$key] : $key);
    }
}