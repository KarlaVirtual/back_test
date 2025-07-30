<?php

/**
 * Este archivo contiene la clase `Config`, que proporciona métodos para gestionar
 * configuraciones dinámicas, incluyendo la carga de configuraciones desde archivos externos.
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
 * Proporciona métodos para obtener, verificar, establecer y cargar configuraciones.
 */
class Config
{
    /**
     * Almacena los datos de configuración en un arreglo asociativo.
     *
     * @var array
     */
    protected $data = array();

    /**
     * Obtiene el valor asociado a una clave específica.
     *
     * @param string $key La clave de la configuración.
     *
     * @return mixed El valor asociado a la clave.
     */
    public function get($key)
    {
        return $this->data[$key];
    }

    /**
     * Verifica si existe una clave específica en la configuración.
     *
     * @param string $key La clave a verificar.
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
     * @param string $key   La clave de la configuración.
     * @param mixed  $value El valor a asociar con la clave.
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
     * @param string $function El nombre de la función o módulo cuya configuración se cargará.
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