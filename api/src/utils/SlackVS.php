<?php

namespace Backend\utils;

/**
 *Clase encargada de la generación de mensajes en Slack.
 *
 *@author Desconocido
 *@version 1.0
 *@package No aplica
 *@category No aplica
 *@since Desconocida
 */
class SlackVS
{
    /**
     * El canal de Slack al que se enviarán los mensajes.
     * @var string $channel
     */
    private $channel;

    /**
     * Constructor de la clase SlackVS.
     *
     * @param string $channel El canal de Slack al que se enviarán los mensajes.
     */
    public function __construct($channel)
    {
        $this->channel = $channel;
    }

    /**
     * Envía un mensaje al canal de Slack.
     *
     * @param string $message El mensaje a enviar.
     * @param string $variable Variable opcional para el mensaje.
     * @param string $type Tipo opcional del mensaje.
     */
    public function sendMessage($message, $variable = '', $type = '')
    {
        exec("php -f " . __DIR__ . "/../imports/Slack/message.php '" . $message . "' '#" . $this->channel . "' '" . $variable . "' '" . $type . "' > /dev/null & ");
    }
}