<?php

/**
 * Esta clase se utiliza para interactuar con la API de Twilio.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-18
 */

namespace Backend\integrations\mensajeria\Twilio\chat;


use Twilio\Rest\Client;
use Twilio\TwiML\MessagingResponse;

/**
 * Importa la clase `Client` de la biblioteca Twilio.
 * Esta clase se utiliza para interactuar con la API de Twilio.
 */
class TwilioChat
{

    /**
     * Almacena la conversación actual.
     *
     * @var mixed
     */
    private $conversation;

    /**
     * ID de la cuenta de Twilio.
     *
     * @var string
     */
    private $accountId;

    /**
     * Token de autenticación de Twilio.
     *
     * @var string
     */
    private $authToken;

    /**
     * Cliente de Twilio para realizar operaciones.
     *
     * @var Client
     */
    private $twilio;

    /**
     * Constructor de la clase TwilioChat.
     *
     * @param string $accountId ID de la cuenta de Twilio.
     * @param string $authToken Token de autenticación de Twilio.
     */
    public function __construct($accountId, $authToken)
    {
        $this->accountId = $accountId;
        $this->authToken = $authToken;
        $twilio = new Client($this->accountId, $this->authToken);
    }

    /**
     * Obtiene la conversación actual.
     *
     * @return mixed La conversación actual.
     */
    public function getConversation()
    {
        return $this->conversation;
    }

    /**
     * Crea una nueva conversación en Twilio.
     *
     * @param string $name Nombre amigable para la conversación.
     *
     * @return void
     */
    public function createConversation($name)
    {
        $conversation = $this->twilio->conversations->v1->conversations
            ->create([
                    "friendlyName" => $name
                ]
            );
        $this->conversation = $conversation;
    }

    /**
     * Obtiene una conversación específica por su SID.
     *
     * @param string $conversationSid SID de la conversación.
     *
     * @return mixed La conversación obtenida.
     */
    public function fetchConversation($conversationSid)
    {
        $conversation = $this->twilio->conversations->v1->conversations($conversationSid)
            ->fetch();

        return $conversation;
    }

    /**
     * Agrega un participante a la conversación mediante SMS.
     *
     * @param string $ownNumber   Número del participante.
     * @param string $proxyNumber Número proxy de Twilio.
     *
     * @return mixed El participante agregado.
     */
    public function addParticipantSMS($ownNumber, $proxyNumber)
    {
        $participant = $twilio->conversations->v1->conversations($this->conversation->sid)
            ->participants
            ->create([
                    "messagingBindingAddress" => $ownNumber,
                    "messagingBindingProxyAddress" => $proxyNumber
                ]
            );
        return $participant;
    }

    /**
     * Agrega un participante a la conversación mediante chat.
     *
     * @param string $identity Identidad del participante.
     *
     * @return mixed El participante agregado.
     */
    public function addParticipantChat($identity)
    {
        $participant = $twilio->conversations->v1->conversations($this->conversation->sid)
            ->participants
            ->create([
                    "identity" => $identity
                ]
            );
        return $participant;
    }

    /**
     * Crea un mensaje en una conversación específica.
     *
     * @param string $conversationSid SID de la conversación.
     * @param string $author          Autor del mensaje.
     * @param string $message         Contenido del mensaje.
     *
     * @return mixed El mensaje creado.
     */
    public function createMessage($conversationSid, $author, $message)
    {
        $message = $twilio->conversations->v1->conversations($conversationSid)
            ->messages
            ->create([
                    "author" => $autor,
                    "body" => $message
                ]
            );
        return $message;
    }

    /**
     * Obtiene un mensaje específico de una conversación.
     *
     * @param string $conversationSid SID de la conversación.
     * @param string $messageSid      SID del mensaje.
     *
     * @return mixed El mensaje obtenido.
     */
    public function fetchMessage($conversationSid, $messageSid)
    {
        $message = $twilio->conversations->v1->conversations($conversationSid)
            ->messages($messageSid)
            ->fetch();
        return $message;
    }

    /**
     * Lista los mensajes de una conversación.
     *
     * @param string $conversationSid SID de la conversación.
     * @param string $messageSid      SID del mensaje (opcional).
     *
     * @return array Lista de mensajes.
     */
    public function listMessage($conversationSid, $messageSid)
    {
        $messages = $twilio->conversations->v1->conversations($conversationSid)
            ->messages
            ->read(20);

        return $messages->messages;
    }
}