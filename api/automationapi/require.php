<?php
/**
* Requires de la api 'affiliates'
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/

error_reporting(0);
ini_set('display_errors', 'OFF');
require_once __DIR__ . '../../vendor/autoload.php';

date_default_timezone_set('America/Bogota');

//header('Access-Control-Allow-Origin: http://127.0.0.1:5500');
//header('Access-Control-Allow-Origin: http://localhost');
header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);

header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Access-Control-Allow-Headers, Access-Control-Allow-Credentials,Authentication, Origin, X-Requested-With, Content-Type');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS,PUT');
header('Content-Type: application/json');


