<?php

/**
 * Clase para integrar la funcionalidad de Twilio en la mensajería.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-18
 */

namespace Backend\integrations\mensajeria\Twilio;

use Twilio\Rest\Client;
use Twilio\TwiML\MessagingResponse;
use Backend\integrations\mensajeria\Message;

/**
 * Clase para integrar la funcionalidad de Twilio en la mensajería.
 */
class Twilio
{

    /**
     * El Account SID de Twilio.
     *
     * @var string
     */
    protected $accountId;

    /**
     * El Auth Token de Twilio.
     *
     * @var string
     */
    protected $authToken;

    /**
     * Constructor de la clase Twilio.
     *
     * @param string $accountId El Account SID de Twilio.
     * @param string $authToken El Auth Token de Twilio.
     */
    public function __construct($accountId, $authToken)
    {
        $this->accountId = $accountId;
        $this->authToken = $authToken;
    }

    /**
     * Envía un mensaje utilizando la API de Twilio.
     *
     * @param string $numeroUsuarioMandante El número del destinatario (incluye código de país).
     * @param string $numeroProveedor       El número del remitente (incluye código de país).
     * @param string $mensaje               El contenido del mensaje a enviar.
     * @param bool   $wpp                   Indica si el mensaje se enviará por WhatsApp (true) o SMS (false). Por
     *                                      defecto es true.
     *
     * @return mixed El objeto del mensaje enviado.
     */
    public function sendMessage($numeroUsuarioMandante, $numeroProveedor, $mensaje, $wpp = true)
    {
        // Valida que el envío sea por medio de WhatsApp
        if ($wpp) {
            $type = "whatsapp:";
        } else {
            $type = "";
        }

        // Instancia el objeto del proveedor para enviar el mensaje
        $client = new Client($this->account_sid, $this->auth_token);

        // Envía el mensaje
        $messege = $client->messages->create(
            $type . "+" . $numeroUsuarioMandante,
            array(
                'from' => $numeroProveedor,
                'body' => $mensaje
            )
        );

        return $message;
    }

    /**
     * Responde a un SMS recibido utilizando Twilio.
     *
     * @return string La respuesta generada en formato XML.
     */
    public function replySMS()
    {
        header("content-type: text/xml");

        $response = new MessagingResponse();
        $response->message(
            "I'm using the Twilio PHP library to respond to this SMS!"
        );

        return $response;
    }
}