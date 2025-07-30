<?php

/**
 * Esta clase proporciona métodos para interactuar con la API de ChatApi.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-18
 */

namespace Backend\integrations\mensajeria\ChatApi;

use Backend\dto\UsuarioMandante;
use Twilio\TwiML\MessagingResponse;
use Backend\integrations\mensajeria\Message;

/**
 * Clase ChatApi
 *
 * Esta clase proporciona métodos para interactuar con la API de ChatApi,
 * incluyendo el envío y la lectura de mensajes.
 */
class ChatApi
{

    /**
     * ID de la instancia de ChatApi.
     *
     * @var string
     */
    private $instanceId;

    /**
     * Token de autenticación para la API.
     *
     * @var string
     */
    private $token;

    /**
     * URL base para las solicitudes a la API.
     *
     * @var string
     */
    private $url;

    /**
     * Constructor de la clase ChatApi.
     *
     * @param string $instanceId ID de la instancia de ChatApi.
     * @param string $token      Token de autenticación para la API.
     */
    public function __construct($instanceId, $token)
    {
        $this->instanceId = $instanceId;
        $this->token = $token;
        $this->url = 'https://api.chat-api.com/instance' . $this->instanceId . '/message?token=' . $this->token;
    }

    /**
     * Envía un mensaje a través de la API.
     *
     * @param string $numeroUsuarioMandante Número de teléfono del usuario mandante.
     * @param string $numeroProveedor       Número de teléfono del proveedor.
     * @param string $mensaje               Contenido del mensaje a enviar.
     *
     * @return string Respuesta de la API.
     */
    public function sendMessage($numeroUsuarioMandante, $numeroProveedor, $mensaje)
    {
        $data = [
            'phone' => $numeroUsuarioMandante,
            'body' => $mensaje
        ];
        $json = json_encode($data);

        // Realiza una solicitud POST
        $options = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => 'Content-type: application/json',
                'content' => $json
            ]
        ]);

        $response = file_get_contents($this->url, false, $options);
        return $response;
    }

    /**
     * Lee los mensajes recibidos desde la API.
     *
     * @return array Lista de mensajes con autor y contenido.
     */
    public function read()
    {
        $messages = [];
        $result = file_get_contents($this->url);
        $data = json_decode($result, 1);

        foreach ($data['messages'] as $message) {
            $message = [];
            $message = [
                'author' => $message['author'],
                'body' => $message['body'],
            ];

            array_push($messages, $message);
        }

        return $messages;
    }
}