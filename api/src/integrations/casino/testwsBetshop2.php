<?php

/**
 * Este archivo contiene la implementación de la clase `WebsocketUsuario` para manejar
 * la comunicación con un servidor WebSocket utilizando la librería ZeroMQ.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */


namespace Backend\integrations\casino;

use Backend\dto\Proveedor;
use Backend\dto\UsuarioToken;
use \ZMQContext;
use \ZMQ;
use ZMQSocket;

error_reporting(E_ALL);
ini_set('display_errors', 'ON');


$body = file_get_contents('php://input');
$WebsocketUsuario = new WebsocketUsuario(0, json_decode($body));
$WebsocketUsuario->sendWSMessage();

/*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */


$data = array(
    "command" => "betshop-livecasino-bet",
    "sid" => $_REQUEST["id"],

    "params" => array(
        "externalId" => "DIRECTDEBITT17961",
        "amount" => 635300,
        "currency" => "COP",
        "message" => "Apuesta en el juego ruleta",
        "playId" => "Ruleta123456",
        "nameGame" => "Ruleta"
    ),
    "rid" => "123456789"
);
print_r($data);

$WebsocketUsuario = new WebsocketUsuario($_REQUEST["id"], $data);
$mensajeMaquina = $WebsocketUsuario->sendWSMessageWithReturn();

print_r($mensajeMaquina);

/**
 * Clase WebsocketUsuario
 *
 * Esta clase permite enviar mensajes a un servidor WebSocket y recibir respuestas
 * utilizando la librería ZeroMQ. Proporciona métodos para enviar mensajes simples
 * y mensajes con retorno.
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
     * Inicializa la instancia con un identificador de sesión (sid) y los datos a enviar.
     *
     * @param mixed $sid  Identificador de sesión.
     * @param mixed $data Datos a enviar al servidor WebSocket.
     */
    public function __construct($sid, $data)
    {
        $this->sid = $sid;
        $this->data = $data;
    }

    /**
     * Envía un mensaje al servidor WebSocket sin esperar una respuesta.
     *
     * Este método utiliza un socket de tipo PUSH para enviar datos al servidor.
     * Los datos enviados se codifican en formato JSON.
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

    /**
     * Envía un mensaje al servidor WebSocket sin esperar una respuesta.
     *
     * Este método utiliza un socket de tipo PUSH para enviar datos al servidor.
     * Los datos enviados se codifican en formato JSON.
     *
     * @return string Retorna "yes" como confirmación de envío.
     */
    public function sendWSMessageWithReturn2()
    {
        $context = new ZMQContext();
        $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
        $socket->connect("tcp://" . $this->host . ":" . $this->port);

        $array = $this->data;

        print_r(json_encode($array));
        $socket->send(json_encode($array));

        return "yes";
    }

    /**
     * Envía un mensaje al servidor WebSocket y espera una respuesta.
     *
     * Este método utiliza un socket de tipo REQ para enviar datos al servidor
     * y recibir una respuesta.
     *
     * @return string Respuesta recibida del servidor.
     */
    public function sendWSMessageWithReturn()
    {
        // This is our new stuff
        $socket = new ZMQSocket(new ZMQContext(), ZMQ::SOCKET_REQ);

        $socket->connect("tcp://127.0.0.1:5552");


        $array = $this->data;

        $socket->send(json_encode($array));

        return $socket->recv();
    }

}