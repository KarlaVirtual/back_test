<?php
/**
 * Este archivo contiene un script para probar la API de casino 'virtualgeneration'.
 *
 * @category   Casino
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2017-10-18
 * @access     public
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $VIRTUALGENERATIONSERVICES Objeto que maneja los servicios de la API de Virtual Generation.
 */

error_reporting(E_ALL);
ini_set('display_errors', 'ON');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Ezugi;
use Backend\integrations\casino\VIRTUALGENERATIONSERVICES;

$VIRTUALGENERATIONSERVICES = new VIRTUALGENERATIONSERVICES();
print_r($VIRTUALGENERATIONSERVICES->Auth(1));