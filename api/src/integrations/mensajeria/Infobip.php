<?php

/**
 * Clase Infobip
 *
 * Esta clase se encarga de enviar mensajes a través de la API de Infobip.
 * Proporciona métodos para enviar mensajes SMS individuales y en masa,
 * utilizando las credenciales del subproveedor y los datos del usuario.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-23
 */

namespace Backend\integrations\mensajeria;

use Backend\dto\Mandante;
use Backend\dto\Pais;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\Subproveedor;
use Backend\dto\SubproveedorMandantePais;
use Backend\dto\Usuario;
use Backend\dto\Registro;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMensaje;
use \CurlWrapper;

/**
 * Clase Infobip
 *
 * Esta clase se encarga de enviar mensajes a través de la API de Infobip.
 * Utiliza las credenciales del subproveedor y los datos del usuario para enviar mensajes SMS.
 */
class Infobip
{
    /**
     * Constructor de la clase Infobip.
     *
     * Este constructor no realiza ninguna acción en este momento.
     * Se puede extender en el futuro para inicializar propiedades o realizar configuraciones adicionales.
     */
    public function __construct()
    {
    }

    /**
     * Metodo para enviar un mensaje a través de la API de Infobip.
     *
     * Este metodo utiliza las credenciales del subproveedor y los datos del usuario
     * para enviar un mensaje SMS a un número de teléfono específico.
     *
     * @param string         $phone          Número de teléfono del destinatario.
     * @param string         $message        Contenido del mensaje a enviar.
     * @param UsuarioMensaje $UsuarioMensaje Objeto que contiene información sobre los mensajes del usuario.
     *
     * @return string Respuesta de la API de Infobip o un mensaje de error en caso de fallo.
     */
    public function sendMessage($phone, $message, UsuarioMensaje $UsuarioMensaje)
    {
        $Subproveedor = new Subproveedor('', 'INFOBIP');

        $usuarioMandante = new UsuarioMandante($UsuarioMensaje->usutoId);
        $Usuario = new Usuario($usuarioMandante->getUsuarioMandante());
        $Pais = new Pais($usuarioMandante->paisId);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $arrayReq = array(
            "messages" => array(
                array(
                    "sender" => $Credentials->SENDER,
                    "destinations" => array(
                        array(
                            "to" => $Pais->prefijoCelular . $phone,
                        )
                    ),
                    "content" => array(
                        "text" => $message
                    )
                )
            ),
            "options" => array(
                "tracking" => array(
                    "shortenUrl" => true,
                    "trackClicks" => true,
                    "customDomain" => $Credentials->DOMINIO
                )
            )
        );

        $request = json_encode($arrayReq);

        $curl = new CurlWrapper($Credentials->URL . "sms/3/messages");

        $curl->setOptionsArray(array(
            CURLOPT_URL => $Credentials->URL . "sms/3/messages",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $request,
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . 'App ' . $Credentials->API_KEY,
                'Content-Type: application/json',
                'Accept: application/json'
            ),
        ));

        $result = $curl->execute();

        return $result;
    }

    /**
     * Metodo para enviar un mensaje a través de la API de Infobip.
     *
     * Este metodo utiliza las credenciales del subproveedor y los datos del usuario
     * para enviar un mensaje SMS a un número de teléfono específico.
     *
     * @param string         $phone           Número de teléfono del destinatario.
     * @param string         $message         Contenido del mensaje a enviar.
     * @param UsuarioMensaje $UsuarioMensajes Objeto que contiene información sobre los mensajes del usuario.
     *
     * @return string Respuesta de la API de Infobip.
     * @throws Exception Si ocurre un error al obtener las credenciales o al realizar la solicitud.
     */
    public function sendMessageBulk($phone, $message, $UsuarioMensajes)
    {
        // Crear un nuevo subproveedor con el identificador 'INFOBIP'.
        $Subproveedor = new Subproveedor('', 'INFOBIP');

        // Obtener información del usuario mandante.
        $UsuarioMandante = new UsuarioMandante($UsuarioMensajes[0]->usutoId);

        // Obtener las credenciales del subproveedor para el mandante y país.
        $SubproveedorMandantePais = new SubproveedorMandantePais("", $Subproveedor->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        // Crear el cuerpo de la solicitud para la API de Infobip.
        $arrayReq = array(
            "messages" => array(
                array(
                    "sender" => $Credentials->SENDER,
                    "destinations" => [],
                    "content" => [
                        "text" => $message,
                    ],
                )
            ),
            "options" => array(
                "tracking" => array(
                    "shortenUrl" => true,
                    "trackClicks" => true,
                    "customDomain" => $Credentials->DOMINIO
                )
            )
        );

        // Iterar sobre los mensajes del usuario.
        foreach ($UsuarioMensajes as $UsuarioMensaje) {
            $usuarioMandante = new UsuarioMandante($UsuarioMensaje->usutoId);
            $Registro = new Registro('', $usuarioMandante->usuarioMandante);
            $Pais = new Pais($usuarioMandante->paisId);

            // Obtener el número de teléfono del registro.
            $phone = $Registro->celular;

            $arrayReq["messages"][0]["destinations"][] = ["to" => $Pais->prefijoCelular . $phone];
        }

        // Convertir el cuerpo de la solicitud a formato JSON.
        $request = json_encode($arrayReq);

        // Llamar a la función para realizar la conexión con Infobip.
        $Response = $this->connectionInfobip($request, $Credentials);

        return $Response;
    }

    /**
     * Metodo para realizar la conexión con la API de Infobip.
     *
     * Este metodo utiliza la clase CurlWrapper para enviar una solicitud POST
     * a la API de Infobip con las credenciales y el cuerpo de la solicitud proporcionados.
     *
     * @param string $request     Cuerpo de la solicitud en formato JSON.
     * @param object $Credentials Objeto que contiene las credenciales de la API.
     *
     * @return string Respuesta de la API de Infobip.
     * @throws Exception Si ocurre un error al realizar la solicitud.
     */
    public function connectionInfobip($request, $Credentials)
    {
        // Crear una instancia de CurlWrapper con la URL de la API.
        $curl = new CurlWrapper($Credentials->URL . "sms/3/messages");

        // Configurar las opciones de la solicitud cURL.
        $curl->setOptionsArray(array(
            CURLOPT_URL => $Credentials->URL . "sms/3/messages",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $request,
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . 'App ' . $Credentials->API_KEY,
                'Content-Type: application/json',
                'Accept: application/json'
            ),
        ));

        // Ejecutar la solicitud y devolver el resultado.
        $result = $curl->execute();

        syslog(LOG_WARNING, "RESPONSE CONNECTION INFOBIP : " . $result);

        return $result;
    }
}
