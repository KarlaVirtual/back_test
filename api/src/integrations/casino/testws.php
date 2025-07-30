<?php

/**
 * Este archivo contiene la implementación de la clase `WebsocketUsuario` para manejar
 * la comunicación con un servidor WebSocket utilizando ZeroMQ.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */

namespace Backend\integrations\casino;

use \ZMQContext;
use \ZMQ;

error_reporting(E_ALL);
ini_set('display_errors', 'ON');


$body = file_get_contents('php://input');
$WebsocketUsuario = new WebsocketUsuario(0, json_decode($body));
$WebsocketUsuario->sendWSMessage();

/**
 * Clase WebsocketUsuario
 *
 * Esta clase permite enviar mensajes a un servidor WebSocket mediante ZeroMQ.
 * Proporciona métodos para inicializar la conexión y enviar datos estructurados.
 */
class WebsocketUsuario
{
    private $sid;
    private $data;

    private $host = "127.0.0.1";
    private $port = "5555";

    /**
     * Constructor de la clase WebsocketUsuario.
     *
     * @param int   $sid  Identificador de sesión.
     * @param mixed $data Datos a enviar al servidor WebSocket.
     */
    public function __construct($sid, $data)
    {
        $this->sid = $sid;
        $this->data = $data;
    }

    /**
     * Envía un mensaje al servidor WebSocket.
     *
     * Este método utiliza ZeroMQ para conectarse a un servidor WebSocket y enviar
     * un mensaje en formato JSON que incluye el identificador de sesión, un código
     * y los datos proporcionados.
     *
     * @return void
     */
    public function sendWSMessage()
    {
        $context = new ZMQContext(1, true);
        $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
        $socket->connect("tcp://" . $this->host . ":" . $this->port);

        $array = array(
            "sid" => $this->sid,
            "rid" => 0,
            "code" => 0,
            "data" => $this->data
        );

        print_r($array);

        $socket->send(json_encode($array));
    }

}