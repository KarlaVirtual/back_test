<?php

/**
 * Este archivo contiene la clase `paysafecard_base`, que proporciona métodos para configurar y gestionar
 * la integración con el sistema de pagos Paysafecard.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Clase `paysafecard_base`
 *
 * Esta clase incluye métodos para configurar el idioma, el modo, las credenciales del comerciante,
 * y para manejar errores y cargar configuraciones relacionadas con Paysafecard.
 */
class paysafecard_base
{

    /**
     * Registro del sistema que contiene configuraciones y dependencias.
     *
     * @var object
     */
    public $registry;

    /**
     * Último error registrado en el sistema.
     *
     * @var string|null
     */
    protected $_error;

    /**
     * Información del cliente asociada a la transacción.
     *
     * @var array|null
     */
    protected $_customerInfo;

    /**
     * Configura el estado de depuración y el archivo de registro.
     *
     * @param boolean        $status  Estado de depuración (true para habilitar, false para deshabilitar).
     * @param string|boolean $logFile Ruta del archivo de registro (opcional).
     *
     * @return void
     */
    public function setDebug($status = false, $logFile = false)
    {
        $this->registry->config->set('DebugStatus', $status);
        if ($logFile) {
            $this->registry->config->set('LogFile', $logFile);
        }
    }

    /**
     * Configura el idioma del sistema.
     *
     * @param string $language Código del idioma (por defecto 'de').
     *
     * @return void
     */
    public function setLanguage($language = 'de')
    {
        if (in_array($language, $this->registry->config->get('AllowedLanguages'))) {
            $this->registry->config->set('Language', $language);
        } else {
            $this->registry->config->set('Language', $this->registry->config->get('AllowedLanguages')[0]);
        }
        $this->registry->config->set('LanguageFolder', $this->registry->config->get('DirLanguage') . $this->language . '/');
        $this->load($this->registry->config->get('Language'));
    }

    /**
     * Configura las credenciales del comerciante.
     *
     * @param string $username Nombre de usuario del comerciante.
     * @param string $password Contraseña del comerciante.
     *
     * @return void
     */
    public function setMerchant($username, $password)
    {
        $this->registry->config->set('merchant', array('username' => $username, 'password' => $password));
    }

    /**
     * Configura el modo de operación del sistema.
     *
     * @param string $mode Modo de operación (por ejemplo, 'test' o 'live').
     *
     * @return void
     */
    public function setMode($mode)
    {
        $this->registry->config->set('Mode', $mode);
    }

    /**
     * Obtiene el último error registrado.
     *
     * @return string|null Mensaje del error o null si no hay errores.
     */
    public function getError()
    {
        return $this->_error;
    }

    /**
     * Registra un error en el sistema.
     *
     * @param string $error Mensaje del error.
     *
     * @return void
     */
    protected function setError($error)
    {
        $this->_error = $error;
        $this->registry->debug->_debug($error);
    }

    /**
     * Obtiene la información del cliente.
     *
     * @return array|null Información del cliente o null si no está disponible.
     */
    public function getCustomerInfo()
    {
        return $this->_customerInfo;
    }

    /**
     * Carga la configuración inicial del sistema.
     *
     * @param string $loadedClass Nombre de la clase cargada.
     *
     * @return void
     */
    protected function loadConfig($loadedClass)
    {
        $this->registry = new Registry();
        $this->registry->config = new Config();
        $this->registry->config->set('LoadedClass', $loadedClass);
        $this->registry->config->load($loadedClass);
        $this->registry->language = new Language($this->registry);
        $this->registry->debug = new Debug($this->registry);
        $this->registry->api = new Api($this->registry);
    }
}