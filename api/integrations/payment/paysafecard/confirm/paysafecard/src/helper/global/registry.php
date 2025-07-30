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
 * Clase Registry
 *
 * Esta clase proporciona métodos para gestionar un registro de datos
 * clave-valor, permitiendo almacenar, recuperar y verificar la existencia
 * de claves en el registro.
 */
final class Registry
{

    /**
     * Idioma configurado en el sistema.
     *
     * @var string
     */
    public $language;

    /**
     * Indicador de modo depuración.
     *
     * @var boolean
     */
    public $debug;

    /**
     * Configuración general del sistema.
     *
     * @var array
     */
    public $config;

    /**
     * API utilizada para la integración.
     *
     * @var object
     */
    public $api;

    /**
     * Almacén interno de datos clave-valor.
     *
     * @var array
     */
    private $data = array();

    /**
     * Obtiene el valor asociado a una clave específica.
     *
     * @param string $key La clave del dato a recuperar.
     *
     * @return mixed|null El valor asociado a la clave, o null si no existe.
     */
    public function get($key)
    {
        return (isset($this->data[$key]) ? $this->data[$key] : null);
    }

    /**
     * Establece un valor para una clave específica en el registro.
     *
     * @param string $key   La clave del dato a almacenar.
     * @param mixed  $value El valor a asociar con la clave.
     *
     * @return void
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * Verifica si una clave específica existe en el registro.
     *
     * @param string $key La clave a verificar.
     *
     * @return boolean True si la clave existe, false en caso contrario.
     */
    public function has($key)
    {
        return isset($this->data[$key]);
    }
}

?>