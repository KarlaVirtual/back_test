<?php

/**
 * Esta clase gestiona la creación y manejo de conversaciones de mensajería.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-18
 */

namespace Backend\integrations\mensajeria;

use Backend\dto\Pais;
use Backend\dto\Mandante;
use Backend\dto\Registro;
use Backend\dto\Proveedor;
use Backend\dto\Subproveedor;
use Backend\dto\UsuarioMensaje;
use Backend\dto\MandanteDetalle;
use Backend\dto\UsuarioMandante;
use Backend\dto\SubproveedorMandante;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\integrations\mensajeria\Twilio\chat\TwilioChat;

/**
 * Clase Message.
 *
 * Esta clase gestiona la creación y manejo de conversaciones de mensajería
 * utilizando diferentes proveedores, como Twilio.
 */
class Message
{

    /**
     * Crea un nuevo chat para un usuario mandante.
     *
     * @param object $UsuarioMandante Objeto que contiene los datos del usuario mandante.
     *
     * @return string Respuesta en formato JSON con los datos del chat o un error.
     */
    public function createChat($UsuarioMandante)
    {
        $provider = $this->loadProvider();

        if ( ! isset($provider->error)) {
            return $this->loadChat($provider, $UsuarioMandante);
        } else {
            return $this->objResponse(true);
        }
    }

    /**
     * Carga los datos del proveedor de mensajería.
     *
     * @return string Respuesta en formato JSON con los datos del proveedor o un error.
     */
    public function loadProvider()
    {
        try {
            $proveedor = "TWILIO";
            $objects = $this->getDataProvider($proveedor);
        } catch (Exection $e) {
            return $this->objResponse(true);
        }

        // Obtener los datos del proveedor
        return json_encode([
            "name" => $provider,
            "data" => $objects->SubproveedorMandante->detalle,
            "provider" => $objects->Proveedor
        ]);
    }

    /**
     * Carga un chat utilizando los datos del proveedor y el usuario mandante.
     *
     * @param object $provider        Objeto con los datos del proveedor.
     * @param object $UsuarioMandante Objeto con los datos del usuario mandante.
     *
     * @return string Respuesta en formato JSON con los datos del chat o un error.
     */
    public function loadChat($provider, $UsuarioMandante)
    {
        switch ($provider->name) {
            case 'TWILIO':
                $twilio = new TwilioChat($provider->data->accountId, $provider->data->authToken);
                // Inicio del chat
                $twilio->createConversation("New conversation");
                // Agregar número del proveedor al chat
                $participant = $twilio->addParticipantSMS($provider->data->celular, $provider->data->proxycel);
                // Agregar al usuario a la conversación
                $twilio->addParticipantChat($UsuarioMandante->nombres);
                // Obtener los datos de la conversación
                $conversation = $twilio->getConversation();

                $message = "Hola, " . $UsuarioMandante->nombres . ". ¿Cómo podemos ayudarte?";
                // Crear un mensaje dentro de la conversación
                $objMessage = $twilio->createMessage(
                    $conversation->sid,
                    $participant->messaging_binding->address,
                    $message
                );

                if ($objMessage->sid != "") {
                    $conversationId = $this->saveLog(
                        $provider->provider->ProveedorId,
                        $UsuarioMandante->usuarioMandante,
                        $message
                    );
                }
                return json_encode([
                    "conversation" => $conversation,
                    "conversationId" => $conversationId,
                    "user" => $UsuarioMandante->nombres
                ]);
                break;
            default:
                $return = $this->objResponse(true);
                break;
        }

        return $return;
    }

    /**
     * Envía un mensaje dentro de una conversación existente.
     *
     * @param string $conversationId  ID de la conversación.
     * @param object $UsuarioMandante Objeto con los datos del usuario mandante.
     * @param string $message         Mensaje a enviar.
     *
     * @return string Respuesta en formato JSON con el estado del envío.
     */
    public function sendMessege($conversationId, $UsuarioMandante, $message)
    {
        $provider = $this->loadProvider();

        switch ($provider->name) {
            case 'TWILIO':
                $twilio = new TwilioChat($provider->data->accountId, $provider->data->authToken);
                $objMessage = $twilio->createMessage($conversationId, $UsuarioMandante->nombres, $message);

                if ($objMessage->sid != "") {
                    $id = $this->saveLog($provider->provider->ProveedorId, $UsuarioMandante->usuarioMandante, $message);
                    $return = $this->objResponse(false, $id);
                } else {
                    $return = $this->objResponse(true);
                }
                break;

            default:
                $return = $this->objResponse(true);
                break;
        }

        return $return;
    }

    /**
     * Obtiene los datos del proveedor y subproveedor mandante.
     *
     * @param string $proveedor Nombre del proveedor.
     *
     * @return string Respuesta en formato JSON con los datos del proveedor y subproveedor mandante.
     * @throws Exception Si ocurre un error al obtener los datos.
     */
    public function getDataProvider($proveedor)
    {
        try {
            $Proveedor = new Proveedor("", $proveedor);
            $Subproveedor = new Subproveedor("", $Proveedor->abreviado);
            $SubproveedorMandante = new SubproveedorMandante($Subproveedor->subproveedorId, $UsuarioMandante->mandante);
        } catch (Exeption $e) {
            throw new Exception($e);
        }

        return json_encode([
            "Proveedor" => $Proveedor,
            "SubproveedorMandante" => $SubproveedorMandante,
        ]);
    }

    /**
     * Genera una respuesta estándar en formato JSON.
     *
     * @param bool  $error Indica si hubo un error.
     * @param mixed $id    Mensaje o identificador de la respuesta.
     *
     * @return string Respuesta en formato JSON.
     */
    public function objResponse($error = false, $id)
    {
        if ( ! $error) {
            $error = false;
            $message = $id;
        } else {
            $error = true;
            $message = "failed";
        }

        return json_encode([
            "error" => $error,
            "message" => $message,
        ]);
    }

    /**
     * Guarda un registro del mensaje enviado en la base de datos.
     *
     * @param int    $proveedorId       ID del proveedor.
     * @param int    $usuarioMandanteId ID del usuario mandante.
     * @param string $mensaje           Contenido del mensaje.
     *
     * @return integer ID del registro guardado.
     */
    public function saveLog($proveedorId, $usuarioMandanteId, $mensaje)
    {
        $UsuarioMensaje = new UsuarioMensaje();
        $UsuarioMensaje->usufromId = 0;
        $UsuarioMensaje->usutoId = $usuarioMandanteId;
        $UsuarioMensaje->isRead = 0;
        $UsuarioMensaje->body = $mensaje;
        $UsuarioMensaje->msubject = "";
        $UsuarioMensaje->tipo = "WHATSAPP";
        $UsuarioMensaje->parentId = 0;
        $UsuarioMensaje->proveedorId = $proveedorId;

        $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
        $id = $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
        $UsuarioMensajeMySqlDAO->getTransaction()->commit();

        return $id;
    }
}
