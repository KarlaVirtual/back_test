<?php

/**
 * Clase Atomo
 *
 * Esta clase proporciona métodos para la integración con el proveedor "ATOMO".
 * Incluye funcionalidades como autenticación, manejo de balance, débitos, créditos,
 * y manejo de errores relacionados con transacciones.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;

use Backend\dto\Mandante;
use Backend\dto\Pais;
use Backend\dto\PromocionalLog;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransaccionApi;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioToken;
use Backend\dto\Producto;
use Backend\dto\ProductoMandante;
use Backend\dto\Proveedor;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransaccionApiMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;
use DateTime;
use Exception;

/**
 * Clase principal para la integración con el proveedor ATOMO.
 *
 * Esta clase contiene métodos para manejar autenticación, balance, transacciones
 * y otros procesos relacionados con el proveedor ATOMO.
 */
class Atomo
{
    /**
     * Identificador del operador.
     *
     * @var integer|null
     */
    private $operadorId;

    /**
     * Token de autenticación.
     *
     * @var string
     */
    private $token;

    /**
     * UID del usuario.
     *
     * @var string
     */
    private $uid;

    /**
     * Firma de seguridad.
     *
     * @var string
     */
    private $sign;

    /**
     * Objeto de transacción API.
     *
     * @var TransaccionApi|null
     */
    private $transaccionApi;

    /**
     * Datos adicionales para las transacciones.
     *
     * @var mixed
     */
    private $data;

    /**
     * Identificador de la ronda en el sistema.
     *
     * @var string
     */
    private $roundIdSuper;

    /**
     * Constructor de la clase Atomo.
     *
     * @param string $token Token de autenticación.
     * @param string $sign  Firma de seguridad.
     * @param string $uid   UID del usuario (opcional).
     */
    public function __construct($token, $sign, $uid = "")
    {
        $this->token = $token;
        $this->sign = $sign;
        $this->uid = $uid;
    }

    /**
     * Obtiene el ID del operador.
     *
     * @return integer|null ID del operador.
     */
    public function getOperadorId()
    {
        return $this->operadorId;
    }

    /**
     * Autentica al usuario utilizando el token o UID.
     *
     * @return string Respuesta en formato JSON con los datos del usuario autenticado.
     * @throws Exception Si el token o UID están vacíos.
     */
    public function Auth()
    {
        try {
            $Proveedor = new Proveedor("", "ATOMO");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);


            if ($this->token == "") {
                if ($this->uid == "") {
                    throw new Exception("Token vacio", "10011");
                }
            }

            if ($this->token == "") {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->uid);
            } else {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
            }


            $UsuarioToken->setToken($UsuarioToken->createToken());

            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
            $UsuarioTokenMySqlDAO->update($UsuarioToken);
            $UsuarioTokenMySqlDAO->getTransaction()->commit();


            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $Pais = new Pais($UsuarioMandante->paisId);

            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $return = array(
                "resultCode" => 1,
                "resultMsg" => "",
                "userId" => $UsuarioMandante->usumandanteId,
                "username" => 'USER' . $UsuarioMandante->usumandanteId,
                "screenName" => 'USER' . $UsuarioMandante->usumandanteId,
                "firstname" => $UsuarioMandante->nombres,
                "lastname" => $UsuarioMandante->nombres,
                "dateOfBirth" => '',
                "email" => '',
                "country" => $Pais->iso,
                "currency" => $responseG->moneda,
                "agentId" => '0',
                "agentName" => '',
                "flags" => '0'
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Autentica al usuario utilizando credenciales.
     *
     * @param string  $username  Nombre de usuario.
     * @param string  $password  Contraseña.
     * @param integer $partnerId ID del socio.
     *
     * @return string Respuesta en formato JSON con los datos del usuario autenticado.
     * @throws Exception Si el partner no está disponible.
     */
    public function AuthWithCredentials($username, $password, $partnerId)
    {
        try {
            $Proveedor = new Proveedor("", "ATOMO");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);


            if ($partnerId != '0') {
                throw new Exception("Partner no disponible", "10011");
            }

            $Usuario = new Usuario();


            $Usuario->login($username, $password, 0, intval($partnerId));


            $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $Usuario->usuarioId);

            $UsuarioToken->setToken($UsuarioToken->createToken());

            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
            $UsuarioTokenMySqlDAO->update($UsuarioToken);
            $UsuarioTokenMySqlDAO->getTransaction()->commit();


            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $Pais = new Pais($UsuarioMandante->paisId);

            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $return = array(
                "resultCode" => 1,
                "resultMsg" => "",
                "userId" => $UsuarioMandante->usumandanteId,
                "context" => '',
                "screenName" => 'USER' . $UsuarioMandante->usumandanteId,
                "lastUpdate" => '',
                "bonusActive" => ''
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del usuario.
     *
     * @return string Respuesta en formato JSON con el balance y la moneda del usuario.
     * @throws Exception Si el token o UID están vacíos.
     */
    public function getBalance()
    {
        try {
            $Proveedor = new Proveedor("", "ATOMO");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("BALANCE");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);


            if ($this->token == "") {
                if ($this->uid == "") {
                    throw new Exception("Token vacio", "10011");
                }
            }

            if ($this->token == "") {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->uid);
            } else {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
            }


            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);


            $return = array(
                "resultCode" => 1,
                "resultMsg" => "",

                "balance" => round($responseG->saldo, 2),
                "currency" => $responseG->moneda,
                "bonusActive" => ""
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Refresca la sesión del usuario.
     *
     * @return string Respuesta en formato JSON con el estado de la sesión.
     * @throws Exception Si el token está vacío.
     */
    public function refreshSesion()
    {
        try {
            $Proveedor = new Proveedor("", "ATOMO");


            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            $return = array(
                "result" => 0,
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
     * @param mixed  $datos         Datos adicionales.
     *
     * @return string Respuesta en formato JSON con el resultado del débito.
     * @throws Exception Si el token está vacío.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos)
    {
        $this->data = $datos;

        try {
            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            //Obtenemos el Proveedor con el abreviado ATOMO
            $Proveedor = new Proveedor("", "ATOMO");

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

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "ATOMO");


            // Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());


            //Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi);
            $this->transaccionApi = $responseG->transaccionApi;


            $return = array(
                "resultCode" => 1,
                "resultMsg" => "",
                "extTransactionId" => $responseG->transaccionId,
                "balance" => round($responseG->saldo, 2),
                "currency" => $responseG->moneda
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
     * @param mixed   $datos          Datos adicionales.
     *
     * @return string Respuesta en formato JSON con el resultado del rollback.
     * @throws Exception Si la transacción no existe.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos)
    {
        $usuarioid = $player;

        $this->data = $datos;


        try {
            //Obtenemos el Proveedor con el abreviado ATOMO
            $Proveedor = new Proveedor("", "ATOMO");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $roundId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);

            //Obtenemos el Usuario Mandante con el Usuario Token
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
                "resultCode" => 1,
                "resultMsg" => "",
                "extTransactionId" => $responseG->transaccionId,
                "balance" => round($responseG->saldo, 2),
                "currency" => $responseG->moneda
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
     * @param mixed  $datos         Datos adicionales.
     *
     * @return string Respuesta en formato JSON con el resultado del crédito.
     * @throws Exception Si ocurre un error durante la operación.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos)
    {
        $this->data = $datos;

        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try {
            //Obtenemos el Proveedor con el abreviado ATOMO
            $Proveedor = new Proveedor("", "ATOMO");

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

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "ATOMO");


            // Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            //Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            //Agregamos Elementos a la Transaccion API
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());


            $Game = new Game();

            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, true);

            $this->transaccionApi = $responseG->transaccionApi;


            $return = array(
                "resultCode" => 1,
                "resultMsg" => "",
                "extTransactionId" => $responseG->transaccionId,
                "balance" => round($responseG->saldo, 2),
                "currency" => $responseG->moneda
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
     * Verifica un parámetro.
     *
     * @param mixed $param Parámetro a verificar.
     *
     * @return string Respuesta en formato JSON con los datos del parámetro.
     */
    public function Check($param)
    {
        $return = array(

            "nodeId" => 123,
            "param" => $param,
            "sign" => $this->sign
        );

        return json_encode($return);
    }

    /**
     * Convierte un error en una respuesta JSON.
     *
     * @param integer $code    Código del error.
     * @param string  $message Mensaje del error.
     *
     * @return string Respuesta en formato JSON con los detalles del error.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $response = array();

        switch ($code) {
            case 10011:
                $codeProveedor = 102;
                $messageProveedor = "Invalid Token";
                break;

            case 21:
                $codeProveedor = 102;
                $messageProveedor = "Invalid Token";
                break;

            case 22:
                $codeProveedor = 130;
                $messageProveedor = "Invalid Token";
                break;

            case 20001:
                $codeProveedor = "21";
                $messageProveedor = "Not Enough Balance";
                break;

            case 0:
                $codeProveedor = 130;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 27:
                $codeProveedor = 130;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 28:
                $codeProveedor = 130;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 29:
                $codeProveedor = 107;
                $messageProveedor = "Transaction Not Found";
                break;

            case 10001:
                $codeProveedor = 110;
                $messageProveedor = "Transaction Exists";
                break;

            case 10004:
                $codeProveedor = 130;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 10014:
                $codeProveedor = 130;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 10010:
                $codeProveedor = 130;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 10005:
                $codeProveedor = 107;
                $messageProveedor = "Transaction Not Found";
                break;

            default:
                $codeProveedor = 130;
                $messageProveedor = "General Error. (" . $code . ")";
                break;
        }


        if ($codeProveedor != "") {
            $respuesta = json_encode(array_merge($response, array(
                "HasError" => 1,
                "ErrorId" => $codeProveedor,
                "ErrorDescription" => $messageProveedor
            )));
        } else {
            $respuesta = json_encode(array_merge($response));
        }

        if ($this->transaccionApi != null) {
            $this->transaccionApi->setTipo("R" . $this->transaccionApi->getTipo());
            $this->transaccionApi->setRespuestaCodigo("ERROR");
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->insert($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();
        }

        return $respuesta;
    }


}