<?php
/**
* Chat vía websocket 
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/

error_reporting(E_ALL);
ini_set('display_errors', 'on');




 function sendMessage($sid,$data)
{


// This is our new stuff
    $context = new ZMQContext(1, true);
    $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
    $socket->connect("tcp://127.0.0.1:5555");



    $array = array(
        "sid" => $sid,
        "rid" => 0,
        "code" => 0,
        "data" => $data
    );

    print_r(json_encode($array));

    $socket->send(json_encode($array));

}

sendMessage("164","");