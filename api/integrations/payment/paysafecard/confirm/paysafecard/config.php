<?php

/**
 * Configuración para la integración de pagos con Paysafecard.
 *
 * Este archivo contiene las configuraciones necesarias para la integración
 * con el sistema de pagos Paysafecard, incluyendo niveles de depuración,
 * claves de acceso, opciones de registro y entorno de ejecución.
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
 *                    - `debug_level` (int): Nivel de depuración. Valores posibles:
 *                    - 0: No mostrar mensajes de depuración.
 *                    - 1: Mostrar mensajes de depuración básicos.
 *                    - 2: Salida detallada (solicitudes, cURL y respuestas).
 *                    - `psc_key` (string): Clave de acceso para Paysafecard.
 *                    - `logging` (bool): Habilitar o deshabilitar el registro de solicitudes y respuestas.
 *                    - `environment` (string): Entorno del sistema. Valores posibles:
 *                    - `TEST`: Entorno de pruebas.
 *                    - `PRODUCTION`: Entorno de producción.
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

    'psc_key' => "psc_I-TAINMENT_EURO_MAN9310721657",

    'logging' => true,

    'environment' => "PRODUCTION",

];
