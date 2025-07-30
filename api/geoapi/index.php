<?php
/**
 * Resúmen geográfico
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */

use Backend\dto\Usuario;

require_once __DIR__ . '../../vendor/autoload.php';

ini_set('display_errors', 'OFF');
ini_set('memory_limit', '-1');

date_default_timezone_set('America/Bogota');

header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
exit();