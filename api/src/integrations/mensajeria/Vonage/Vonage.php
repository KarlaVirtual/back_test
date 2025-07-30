<?php

/**
 * Clase Vonage para manejar la integración con la API de Vonage.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-18
 */

namespace Backend\integrations\mensajeria\Vonage;

use Backend\integrations\mensajeria\Message;

/**
 * Clase Vonage para manejar la integración con la API de Vonage.
 * Proporciona métodos para enviar mensajes a través de la API.
 */
class Vonage
{

    /**
     * Token JWT utilizado para la autenticación.
     *
     * @var string
     */
    protected $jwt;

    /**
     * URL base para las solicitudes.
     *
     * @var string
     */
    private $url;

    /**
     * URL de producción para las solicitudes.
     *
     * @var string
     */
    private $urlProd = "https://api.nexmo.com/v0.1/messages";

    /**
     * URL de desarrollo para las solicitudes.
     *
     * @var string
     */
    private $urlDev = "https://messages-sandbox.nexmo.com/v0.1/messages";

    /**
     * Constructor de la clase Vonage.
     *
     * @param string $jwt Token JWT para la autenticación.
     */
    public function __construct($jwt)
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->url = $this->urlDev;
            $this->jwt = $this->jwtDev;
        } else {
            $this->url = $this->urlProd;
            $this->jwt = $this->jwtProd;
        }
        $this->jwt = $jwt;
    }

    /**
     * Envía un mensaje a través de la API de Vonage.
     *
     * @param string $numeroUsuarioMandante Número del remitente (usuario mandante).
     * @param string $numeroProveedor       Número del destinatario (proveedor).
     * @param string $mensaje               Contenido del mensaje a enviar.
     *
     * @return mixed Respuesta de la API de Vonage.
     */
    public function sendMessage($numeroUsuarioMandante, $numeroProveedor, $mensaje)
    {
        // Datos para el envío del mensaje al proveedor
        $data = [
            "from" => [
                "type" => "whatsapp",
                "number" => $numeroUsuarioMandante
            ],
            "to" => [
                "type" => "whatsapp",
                "number" => $numeroProveedor
            ],
            "message" => [
                "content" => [
                    "type" => "text",
                    "text" => $mensaje
                ]
            ]
        ];

        $data = json_encode($data);
        $authorization = "Authorization: Bearer" . $this->jwt;

        $headers = [
            'Content-Type: application/json',
            $authorization
        ];

        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $result = json_decode(curl_exec($ch));

        return ($result);
    }
}