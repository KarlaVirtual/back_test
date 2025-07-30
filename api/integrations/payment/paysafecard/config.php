<?php

/**
 * Configuración para la integración de pagos con Paysafecard.
 *
 * Este archivo contiene las configuraciones necesarias para la integración
 * con el sistema de pagos Paysafecard, incluyendo niveles de depuración,
 * claves de acceso, opciones de registro y entorno del sistema.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Configuración principal para Paysafecard.
 *
 * @var array $config Configuración específica para la integración de Paysafecard.
 *                    Incluye:
 *                    - `debug_level`: Nivel de depuración (0: Sin depuración, 1: Mensajes modales, 2: Salida detallada).
 *                    - `psc_key`: Clave de acceso para Paysafecard.
 *                    - `logging`: Habilita o deshabilita el registro de solicitudes y respuestas.
 *                    - `environment`: Entorno del sistema (TEST o PRODUCTION).
 */
$config = [

    /*
     * Depuración
     * Modificar depuración, activará más detalles en el mensaje de error
     * 0: No mostrar mensajes de depuración
     * 1: Modal debug messages
     * 2: Verbose output (requests, curl & responses)
     */
    'debug_level' => 1,


    'psc_key' => "psc_gamblingmaltaapco",


    'logging' => true,


    'environment' => "TEST",

];
