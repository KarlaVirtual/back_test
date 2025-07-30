<?php
/**
* Archivo de configuración PSC
*
*
* @package ninguno
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public
* @see no
* @date: 06.09.17
*
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

    /*
     * Key:
     * Set key, your psc key
     */

    'psc_key'     => "psc_I-TAINMENT_EURO_MAN9310721657",

    /*
     * Logging:
     * enable logging of requests and responses to file, default: true
     * might be disbaled in production mode
     */

    'logging'     => true,

    /*
     * Environment
     * set the systems environment.
     * Possible Values are:
     * TEST = Test environment
     * PRODUCTION = Productive Environment
     *
     */

    'environment' => "PRODUCTION",

];
