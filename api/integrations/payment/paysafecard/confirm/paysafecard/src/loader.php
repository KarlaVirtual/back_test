<?php

/**
 * Este archivo contiene la clase `Loader` y su funcionalidad asociada.
 * Se encarga de cargar archivos de configuración y funciones auxiliares
 * necesarias para la integración con Paysafecard.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

require_once '_config.php';

// Carga todos los archivos PHP en el directorio de helpers globales.
$helper = glob(DIR_HELPER_GLOBAL . '*.php');
foreach ($helper as $function) {
    include_once $function;
}

/**
 * Clase final `Loader`
 *
 * Esta clase se utiliza para cargar archivos base y específicos
 * relacionados con una clase dada.
 */
final class Loader
{
    /**
     * Constructor de la clase `Loader`.
     *
     * Carga el archivo base y, si existe, el archivo `loader.php`
     * correspondiente a la clase especificada.
     *
     * @param string $loadClass Nombre de la clase a cargar.
     */
    public function __construct($loadClass)
    {
        include_once DIR_FUNCTION . 'base.php';
        if (file_exists(DIR_FUNCTION . $loadClass . '/loader.php')) {
            include_once DIR_FUNCTION . $loadClass . '/loader.php';
        }
    }
}