<?php

/**
 * Clase Apollo
 *
 * Esta clase implementa la integración con el proveedor de casino "Apollo".
 * Proporciona métodos para autenticación, manejo de transacciones, y obtención de información
 * como balance y credenciales del usuario.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */


namespace Backend\integrations\casino;

use DateTime;
use Exception;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioToken;
use Backend\dto\SubproveedorMandantePais;
use Backend\dto\TransjuegoLog;
use Backend\dto\PromocionalLog;
use Backend\dto\TransaccionApi;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;
use Backend\mysql\TransaccionApiMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;

/**
 * Clase Apollo
 *
 * Esta clase representa la integración con el proveedor de casino "Apollo".
 * Contiene métodos para manejar transacciones, autenticación, balance, y más.
 */
class Apollo
{
    /**
     * Token de autenticación utilizado para las solicitudes al proveedor.
     *
     * @var string
     */
    private $token;

    /**
     * Firma de seguridad utilizada para validar las solicitudes.
     *
     * @var string
     */
    private $sign;

    /**
     * Objeto que representa la transacción actual en la API.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * Datos adicionales relacionados con la transacción.
     *
     * @var array
     */
    private $data;


    /**
     * Constructor de la clase Apollo.
     *
     * @param string $token Token de autenticación.
     * @param string $sign  Firma de seguridad.
     */
    public function __construct($token, $sign)
    {
        $this->token = $token;
        $this->sign = $sign;
    }

    /**
     * Obtiene la clave de respuesta (RESPONSE\_KEY) del proveedor.
     *
     * @return string|null Clave de respuesta o un error en formato JSON.
     */
    public function getResponseKey()
    {
        try {
            $Proveedor = new Proveedor("", "APOLLO");
            $Subproveedor = new Subproveedor("", "APOLLO");

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->proveedorId);
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $credentials = json_decode($SubproveedorMandantePais->getCredentials());

            return $credentials->RESPONSE_KEY;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }


    /**
     * Realiza la autenticación del usuario con el proveedor.
     *
     * @return string Respuesta en formato JSON con los datos de autenticación.
     */
    public function Auth()
    {
        try {
            $Proveedor = new Proveedor("", "APOLLO");

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

            //Obtenemos el producto con el gameId
            $Producto = new Producto($UsuarioToken->productoId);

            $UsuarioToken->setToken($UsuarioToken->createToken());

            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
            $UsuarioTokenMySqlDAO->update($UsuarioToken);
            $UsuarioTokenMySqlDAO->getTransaction()->commit();


            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $return = array(
                "result" => 0,
                "playerId" => $UsuarioMandante->usumandanteId,
                "sessionId" => $UsuarioToken->getToken(),
                "casinoId" => $UsuarioMandante->mandante,
                "gameName" => $Producto->getExternoId(),
                "gameMode" => "Real",
                "balance" => round($responseG->saldo, 2),
                "currency" => $responseG->moneda,
                "timestamp" => gmdate("Y-m-d\TH:i:s\Z")
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del usuario desde el proveedor.
     *
     * @return string Respuesta en formato JSON con el balance y la moneda.
     */
    public function getBalance()
    {
        try {
            $Proveedor = new Proveedor("", "APOLLO");

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
            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);

            $return = array(
                "result" => 0,
                "balance" => round($responseG->saldo, 2),
                "currency" => $responseG->moneda,
                "timestamp" => gmdate("Y-m-d\TH:i:s\Z")

            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Refresca la sesión del usuario y obtiene el balance actualizado.
     *
     * @return string Respuesta en formato JSON con el balance y la moneda.
     */
    public function refreshSesion()
    {
        try {
            $Proveedor = new Proveedor("", "APOLLO");


            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);
            $return = array(
                "result" => 0,
                "balance" => round($responseG->saldo, 2),
                "currency" => $responseG->moneda,
                "timestamp" => gmdate("Y-m-d\TH:i:s\Z")

            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un débito en la cuenta del usuario.
     *
     * @param string $gameId        ID del juego.
     * @param float  $debitAmount   Monto a debitar.
     * @param string $roundId       ID de la ronda.
     * @param string $transactionId ID de la transacción.
     * @param array  $datos         Datos adicionales de la transacción.
     *
     * @return string Respuesta en formato JSON con el resultado del débito.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos)
    {
        $this->data = $datos;

        try {
            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            //Obtenemos el Proveedor con el abreviado APOLLO
            $Proveedor = new Proveedor("", "APOLLO");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);


            //Obtenemos el Usuario Token con el token
            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            //Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "APOLLO");


            //Obtenemos el producto con el gameId
            $Producto = new Producto($UsuarioToken->productoId);


            if (($UsuarioToken->getUsuarioId() == 65395) && (in_array($Proveedor->getProveedorId(), array('12', '68', '67')) || in_array($Producto->getProductoId(), array('5734', '5738', '5741', '5744', '5747', '5768')))) {
                throw new Exception("Juego no disponible ", "10011");
            }

            //Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi);
            $this->transaccionApi = $responseG->transaccionApi;


            $return = array(
                "result" => 0,
                "transactionId" => $responseG->transaccionId,
                "balance" => round($responseG->saldo, 2),
                "currency" => $responseG->moneda,
                "timestamp" => gmdate("Y-m-d\TH:i:s\Z")

            );


            //Guardamos la Transaccion Api necesaria de estado OK
            $this->transaccionApi->setRespuestaCodigo("OK");
            $this->transaccionApi->setRespuesta(json_encode($return));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();


            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }


    /**
     * Realiza un rollback de una transacción.
     *
     * @param float   $rollbackAmount Monto a revertir.
     * @param string  $roundId        ID de la ronda.
     * @param string  $transactionId  ID de la transacción.
     * @param integer $player         ID del jugador.
     * @param array   $datos          Datos adicionales de la transacción.
     *
     * @return string Respuesta en formato JSON con el resultado del rollback.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos)
    {
        $usuarioid = $player;

        $this->data = $datos;


        try {
            //Obtenemos el Proveedor con el abreviado APOLLO
            $Proveedor = new Proveedor("", "APOLLO");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $roundId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);

            $UsuarioMandante = new UsuarioMandante($usuarioid);

            $identificador = "";
            try {
                $TransaccionApi2 = new TransaccionApi("", $roundId, $Proveedor->getProveedorId());

                $identificador = $TransaccionApi2->getIdentificador();
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            $this->transaccionApi->setIdentificador($identificador);

            $Game = new Game();
            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false);

            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "TotalBalance" => round($responseG->saldo, 2),
                "PlatformTransactionId" => $responseG->transaccionId,
                "PlayerId" => $UsuarioMandante->getUsumandanteId(),
                "HasError" => 0,
                "ErrorId" => 0,
                "ErrorDescription" => ""
            );

            //Guardamos la Transaccion Api necesaria de estado OK
            $this->transaccionApi->setRespuestaCodigo("OK");
            $this->transaccionApi->setRespuesta(json_encode($return));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();


            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un crédito en la cuenta del usuario.
     *
     * @param string $gameId        ID del juego.
     * @param float  $creditAmount  Monto a acreditar.
     * @param string $roundId       ID de la ronda.
     * @param string $transactionId ID de la transacción.
     * @param array  $datos         Datos adicionales de la transacción.
     *
     * @return string Respuesta en formato JSON con el resultado del crédito.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos)
    {
        $this->data = $datos;

        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try {
            //Obtenemos el Proveedor con el abreviado APOLLO
            $Proveedor = new Proveedor("", "APOLLO");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);

            //Obtenemos el Usuario Token con el token
            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            //Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "APOLLO");


            //Obtenemos el producto con el gameId
            $Producto = new Producto($UsuarioToken->productoId);

            //Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            //Agregamos Elementos a la Transaccion API
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());


            $Game = new Game();

            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, true);

            $this->transaccionApi = $responseG->transaccionApi;


            $return = array(
                "result" => 0,
                "balance" => round($responseG->saldo, 2),
                "currency" => $responseG->moneda,
                "transactionId" => $responseG->transaccionId,
                //"winTransactionIds" => [$responseG->transaccionId],
                "timestamp" => gmdate("Y-m-d\TH:i:s\Z")

            );

            //Guardamos la Transaccion Api necesaria de estado OK
            $this->transaccionApi->setRespuestaCodigo("OK");
            $this->transaccionApi->setRespuesta(json_encode($return));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Convierte un error en un formato de respuesta JSON.
     *
     * @param integer $code    Código de error.
     * @param string  $message Mensaje de error.
     *
     * @return string Respuesta en formato JSON con el código y mensaje de error.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $response = array();

        switch ($code) {
            case 10011:
                $codeProveedor = 11;
                $messageProveedor = "PlayerHandle does not exist";
                break;

            case 21:
                $codeProveedor = 11;
                $messageProveedor = "PlayerHandle does not exist";
                break;

            case 22:
                $codeProveedor = 11;
                $messageProveedor = "PlayerHandle does not exist";
                break;

            case 20001:
                $codeProveedor = "3";
                $messageProveedor = "Insufficient credit";
                break;

            case 0:
                $codeProveedor = 999;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 27:
                $codeProveedor = 999;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 28:
                $codeProveedor = 999;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 29:
                $codeProveedor = 107;
                $messageProveedor = "Transaction Not Found";
                break;

            case 10001:
                $codeProveedor = 15;
                $messageProveedor = "Transaction Exists";
                break;

            case 10004:
                $codeProveedor = 999;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 10014:
                $codeProveedor = 999;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 10010:
                $codeProveedor = 999;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 10005:
                $codeProveedor = 15;
                $messageProveedor = "Transaction Not Found";
                break;

            default:
                $codeProveedor = 999;
                $messageProveedor = "General Error. (" . $code . ")";
                break;
        }


        if ($codeProveedor != "") {
            $respuesta = json_encode(array_merge($response, array(
                "result" => $codeProveedor,
                "msg" => $messageProveedor
            )));
        } else {
            $respuesta = json_encode(array_merge($response));
        }

        if ($this->transaccionApi != null) {
            $this->transaccionApi->setTipo("R" . $this->transaccionApi->getTipo());
            $this->transaccionApi->setRespuestaCodigo("ERROR_" . $code);
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->insert($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();
        }

        return $respuesta;
    }
}
