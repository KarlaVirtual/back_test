<?php

/**
 * Esta clase gestiona el envío de mensajes de WhatsApp utilizando diferentes proveedores.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-18
 */


namespace Backend\integrations\mensajeria;

use Exception;
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
use Backend\integrations\mensajeria\Twilio\Twilio;
use Backend\integrations\mensajeria\ChatApi\ChatApi;
use Backend\integrations\mensajeria\Vonage\Vonage;

/**
 * Clase Message
 *
 * Esta clase gestiona el envío de mensajes de WhatsApp utilizando diferentes proveedores.
 * Proporciona métodos para enviar mensajes, obtener datos de proveedores y registrar logs.
 */
class Message
{

    /**
     * Envia un mensaje de WhatsApp utilizando diferentes proveedores.
     *
     * @param UsuarioMandante $UsuarioMandante Objeto que contiene información del usuario mandante.
     * @param string          $mensaje         El mensaje que se enviará.
     *
     * @return array Respuesta del envío del mensaje.
     * @throws Exception Si no se puede obtener un proveedor válido.
     */
    public function SendWhatsAppMessage($UsuarioMandante, $mensaje)
    {
        $Pais = new Pais($UsuarioMandante->paisId);
        $Registro = new Registro("", $UsuarioMandante->usuarioMandante);
        $numeroUsuarioMandante = $Pais->prefijoCelular . $Registro->celular;
        $numeroUsuarioMandante = '+573012976239';
        //Controla la existencia del proveedor con el mandante
        try {
            $proveedor = "TWILIO";
            $objects = $this->getDataProvider($proveedor, $UsuarioMandante);
        } catch (Exception $e) {
            try {
                $proveedor = "CHATAPI";
                $objects = $this->getDataProvider($proveedor);
            } catch (Exception $e) {
                $proveedor = "VONAGE";
                $objects = $this->getDataProvider($proveedor);
            }
        }

        //obtener los detalles de conexion y numero del proveedor
        $data = json_decode($objects['SubproveedorMandante']->detalle);
        //obtener el id del proveedor
        $proveedorId = $objects['Proveedor']->proveedorId;
        $numeroProveedor = $data->numberPhone;

        $response = $this->getProviderMessage(
            $proveedor,
            $proveedorId,
            $data,
            $numeroUsuarioMandante,
            $UsuarioMandante,
            $mensaje
        );

        return $response;
    }

    /**
     * Obtiene el mensaje del proveedor y lo envía.
     *
     * @param string          $proveedor             Nombre del proveedor (TWILIO, CHATAPI, VONAGE).
     * @param int             $proveedorId           ID del proveedor.
     * @param object          $data                  Detalles de conexión del proveedor.
     * @param string          $numeroUsuarioMandante Número del usuario mandante.
     * @param UsuarioMandante $UsuarioMandante       Objeto que contiene información del usuario mandante.
     * @param string          $mensaje               El mensaje que se enviará.
     *
     * @return array Respuesta del envío del mensaje.
     */
    public function getProviderMessage(
        $proveedor,
        $proveedorId,
        $data,
        $numeroUsuarioMandante,
        $UsuarioMandante,
        $mensaje
    ) {
        switch ($proveedor) {
            case 'TWILIO':

                $twilio = new Twilio($data->accountId, $data->authToken);
                $response = $twilio->sendMessage($numeroUsuarioMandante, $data->numberPhone, $mensaje);

                if ( ! is_null($response) && $response != "") {
                    $this->saveLog($proveedorId, $UsuarioMandante->usumandanteId, $mensaje);
                    $return = $this->objResponse();
                } else {
                    $return = $this->objResponse(true);
                }
                break;

            case 'CHATAPI':
                $chatApi = new ChatApi($data->instanceId, $data->token);
                $response = $chatApi->sendMessage($numeroUsuarioMandante, $numeroProveedor, $mensaje);

                if ( ! is_null($response->sent)) {
                    $this->saveLog($proveedorId, $UsuarioMandante->usumandanteId, $mensaje);
                    $return = $this->objResponse();
                } else {
                    $return = $this->objResponse(true);
                }
                break;
            case 'VONAGE':
                $vonage = new Vonage($data->jwt);
                $response = $vonage->sendMessage($numeroUsuarioMandante, $numeroProveedor, $mensaje);

                if ( ! is_null($response->message_uuid)) {
                    $this->saveLog($proveedorId, $UsuarioMandante->usumandanteId, $mensaje);
                    $return = $this->objResponse();
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
     * Obtiene los datos del proveedor y subproveedor asociados al usuario mandante.
     *
     * @param string          $proveedor       Nombre del proveedor.
     * @param UsuarioMandante $UsuarioMandante Objeto que contiene información del usuario mandante.
     *
     * @return array Datos del proveedor y subproveedor.
     * @throws Exception Si ocurre un error al obtener los datos del proveedor.
     */
    public function getDataProvider($proveedor, $UsuarioMandante)
    {
        try {
            $Proveedor = new Proveedor("", $proveedor);
            $Subproveedor = new Subproveedor("", $Proveedor->abreviado);
            $SubproveedorMandante = new SubproveedorMandante($Subproveedor->subproveedorId, $UsuarioMandante->mandante);
        } catch (Exception $e) {
            throw new Exception($e);
        }

        return ([
            "Proveedor" => $Proveedor,
            "SubproveedorMandante" => $SubproveedorMandante,
        ]);
    }

    /**
     * Genera una respuesta estándar para las operaciones realizadas.
     *
     * @param bool $error Indica si hubo un error (true) o no (false).
     *
     * @return array Respuesta con el estado y mensaje.
     */
    public function objResponse($error = false)
    {
        if ( ! $error) {
            $error = false;
            $message = "sucess";
        } else {
            $error = true;
            $message = "failed";
        }

        return [
            "error" => $error,
            "message" => $message,
        ];
    }

    /**
     * Guarda un registro del mensaje enviado en el log.
     *
     * @param int    $proveedorId       ID del proveedor utilizado.
     * @param int    $usuarioMandanteId ID del usuario mandante.
     * @param string $mensaje           El mensaje enviado.
     *
     * @return void
     */
    public function saveLog($proveedorId, $usuarioMandanteId, $mensaje)
    {
    }
}
