<?php

/**
 * Clase Okroute para interactuar con la API de mensajería de Okroute.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-18
 */

namespace Backend\integrations\mensajeria;

use Backend\dto\Pais;
use Backend\dto\Proveedor;
use Backend\dto\Usuario;
use Backend\dto\Registro;
use Backend\dto\UsuarioMensaje;

/**
 * Clase Okroute para interactuar con la API de mensajería de Okroute.
 *
 * Proporciona métodos para enviar mensajes, autenticar usuarios y obtener balances.
 */
class Okroute
{

    /**
     * URL base para las solicitudes a la API de Okroute.
     *
     * @var string
     */
    private $URL = 'http://185.64.57.141:8001/api';

    /**
     * Nombre de usuario para la autenticación en la API.
     *
     * @var string
     */
    private $username = 'dorabe56';

    /**
     * Contraseña para la autenticación en la API.
     *
     * @var string
     */
    private $password = 'jojxpryb';

    /**
     * Constructor de la clase Okroute.
     */
    public function __construct()
    {
    }

    /**
     * Envía un mensaje a un usuario específico.
     *
     * @param int            $usuarioId      ID del usuario al que se enviará el mensaje.
     * @param string         $message        Contenido del mensaje a enviar.
     * @param UsuarioMensaje $UsuarioMensaje Objeto que contiene información del mensaje.
     *
     * @return void
     * @throws Exception Si ocurre un error al procesar la solicitud.
     */
    public function sendMessage($usuarioId, $message, $UsuarioMensaje)
    {
        $Proveedor = new Proveedor("", "OKROUTE");

        $Usuario = new Usuario($usuarioId);

        $Registro = new Registro('', $usuarioId);
        $Pais = new Pais($Usuario->paisId);

        $request = "?username=dorabe56&password=jojxpryb";
        $request = $request . "&ani=" . $UsuarioMensaje->getUsumensajeId();
        $request = $request . "&dnis=" . $Pais->prefijoCelular . $Registro->celular;
        $request = $request . "&message=" . $message;
        $request = $request . "&command=submit";
        $request = $request . "&longMessageMode=cut";

        $result = file_get_contents($this->URL . $request);

        $result = json_decode($result);

        $UsuarioMensaje->setExternoId($result->message_id);
        $UsuarioMensaje->setProveedorId($Proveedor->getProveedorId());
    }

    /**
     * Envía un mensaje a una lista de números específicos.
     *
     * @param string         $numbers        Números de destino separados por comas.
     * @param string         $message        Contenido del mensaje a enviar.
     * @param UsuarioMensaje $UsuarioMensaje Objeto que contiene información del mensaje.
     *
     * @return void
     * @throws Exception Si ocurre un error al procesar la solicitud.
     */
    public function sendMessageWithNumbers($numbers, $message, $UsuarioMensaje)
    {
        $Proveedor = new Proveedor("", "OKROUTE");

        $request = "?username=dorabe56&password=jojxpryb";
        $request = $request . "&ani=" . $UsuarioMensaje->getUsumensajeId();
        $request = $request . "&dnis=" . $numbers;
        $request = $request . "&message=" . $message;
        $request = $request . "&command=submit";
        $request = $request . "&longMessageMode=cut";

        $result = file_get_contents($this->URL . $request);

        $result = json_decode($result);

        $UsuarioMensaje->setExternoId($result->message_id);
        $UsuarioMensaje->setProveedorId($Proveedor->getProveedorId());
    }

    /**
     * Realiza la autenticación del usuario y devuelve información del balance.
     *
     * @return string JSON con información del balance y el token del usuario.
     * @throws Exception Si el token está vacío o si ocurre un error durante la autenticación.
     */
    public function Auth()
    {
        try {
            $Proveedor = new Proveedor("", "BTX");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);


            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $Mandante = new Mandante($UsuarioMandante->mandante);

            if ($Mandante->propio == "S") {
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                $Balance = $Usuario->getBalance();

                $return = array(

                    "PlayerId" => $UsuarioMandante->usumandanteId,
                    "TotalBalance" => $Balance,
                    "Token" => $UsuarioToken->getToken(),
                    "HasError" => 0,
                    "ErrorId" => 0,
                    "ErrorDescription" => ""
                );
                return json_encode($return);
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del usuario autenticado.
     *
     * @return string JSON con información del balance del usuario.
     * @throws Exception Si el token está vacío o si ocurre un error al obtener el balance.
     */
    public function getBalance()
    {
        try {
            $Proveedor = new Proveedor("", "BTX");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("BALANCE");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);


            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $Mandante = new Mandante($UsuarioMandante->mandante);

            if ($Mandante->propio == "S") {
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                $Balance = $Usuario->getBalance();

                $return = array(

                    "PlayerId" => $UsuarioMandante->getUsumandanteId(),
                    "TotalBalance" => $Balance,
                    "HasError" => 0,
                    "ErrorId" => 0,
                    "ErrorDescription" => ""

                );
                return json_encode($return);
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }


}