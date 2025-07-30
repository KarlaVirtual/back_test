<?php

/**
 * Archivo de configuración para la integración de pagos con Paysafecard.
 *
 * Este archivo contiene la clase `Config`, que permite gestionar configuraciones
 * dinámicas mediante métodos para obtener, verificar, establecer y cargar datos
 * de configuración desde archivos externos.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-05-09
 */

/**
 * Clase Config
 *
 * Proporciona métodos para gestionar configuraciones dinámicas, incluyendo
 * la carga de configuraciones desde archivos externos.
 */
class Config
{

    /**
     * Almacena los datos de configuración cargados dinámicamente.
     *
     * @var array
     */
    protected $data = array();


    /**
     * Obtiene el valor asociado a una clave específica.
     *
     * @param string $key Clave de la configuración.
     *
     * @return mixed Valor asociado a la clave.
     */
    public function get($key)
    {
        return $this->data[$key];
    }

    /**
     * Verifica si existe una clave específica en la configuración.
     *
     * @param string $key Clave de la configuración.
     *
     * @return boolean `true` si la clave existe, `false` en caso contrario.
     */
    public function has($key)
    {
        return isset($this->data[$key]);
    }

    /**
     * Establece un valor para una clave específica en la configuración.
     *
     * @param string $key   Clave de la configuración.
     * @param mixed  $value Valor a asociar con la clave.
     *
     * @return void
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * Carga configuraciones desde un archivo externo.
     *
     * @param string $function Nombre de la función o módulo cuya configuración se cargará.
     *
     * @return void
     * @throws Exception Si el archivo de configuración no existe.
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