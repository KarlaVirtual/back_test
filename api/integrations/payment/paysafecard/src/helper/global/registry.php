<?php

/**
 * Este archivo contiene la implementación de la clase `Registry`,
 * que actúa como un contenedor para almacenar y recuperar datos clave-valor.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-05-09
 */

/**
 * Clase `Registry`
 *
 * Esta clase proporciona métodos para almacenar, recuperar y verificar
 * la existencia de datos utilizando un enfoque de registro global.
 */
final class Registry
{
    /**
     * Idioma configurado en el sistema.
     *
     * @var mixed
     */
    public $language;

    /**
     * Indicador de modo depuración.
     *
     * @var mixed
     */
    public $debug;

    /**
     * Configuración general del sistema.
     *
     * @var mixed
     */
    public $config;

    /**
     * API utilizada en el sistema.
     *
     * @var mixed
     */
    public $api;

    /**
     * Almacén interno de datos clave-valor.
     *
     * @var array
     */
    private $data = array();

    /**
     * Obtiene un valor del registro.
     *
     * @param string $key La clave del valor a recuperar.
     *
     * @return mixed|null El valor asociado a la clave, o null si no existe.
     */
    public function get($key)
    {
        return (isset($this->data[$key]) ? $this->data[$key] : null);
    }

    /**
     * Establece un valor en el registro.
     *
     * @param string $key   La clave del valor a establecer.
     * @param mixed  $value El valor a asociar con la clave.
     *
     * @return void
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * Verifica si una clave existe en el registro.
     *
     * @param string $key La clave a verificar.
     *
     * @return boolean `true` si la clave existe, `false` en caso contrario.
     */
    public function has($key)
    {
        return isset($this->data[$key]);
    }
}

?>