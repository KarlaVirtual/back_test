<?php
/**
* Index2 de la api 'api'
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/

require_once(__DIR__ . '/../api.php');

set_error_handler("exception_error_handler");

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, POST,    OPTIONS,PUT');

$entityBody = file_get_contents('php://input');


$json = json_decode($entityBody);

$object = json_decode($entityBody, true);

$object['session'] = array();

$object['session']['sid'] = "111";

$object = json_encode($object);

$object = json_decode($object);

$response = resolverAPI($object);

print_r($response);