<?php

/**
 * Clase Tomhorn
 *
 * Esta clase implementa la integración con el proveedor de juegos Tomhorn.
 * Proporciona métodos para autenticar usuarios, obtener balances, realizar débitos, créditos, rollbacks y otras operaciones relacionadas con transacciones de juegos.
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
use Backend\dto\PromocionalLog;
use Backend\dto\Subproveedor;
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
 * Clase que implementa la integración con el proveedor de juegos Tomhorn.
 * Proporciona métodos para manejar transacciones de juegos, autenticación,
 * balance, débitos, créditos, rollbacks y más.
 */
class Tomhorn
{
    /**
     * Identificador del operador.
     *
     * @var mixed
     */
    private $operadorId;

    /**
     * Token de autenticación.
     *
     * @var string
     */
    private $token;

    /**
     * Identificador único del usuario.
     *
     * @var mixed
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
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * Datos adicionales de la transacción.
     *
     * @var array
     */
    private $data;

    /**
     * Identificador de la ronda principal.
     *
     * @var string
     */
    private $roundIdSuper;

    /**
     * Identificador externo opcional.
     *
     * @var string
     */
    private $externoId;

    /**
     * Constructor de la clase Tomhorn.
     *
     * @param string $token Token de autenticación.
     * @param string $sign  Firma de seguridad.
     * @param string $name  Nombre externo opcional.
     */
    public function __construct($token, $sign, $name = "")
    {
        $this->token = $token;
        $this->sign = $sign;
        $this->externoId = $name;
    }

    /**
     * Obtiene el ID del operador.
     *
     * @return mixed ID del operador.
     */
    public function getOperadorId()
    {
        return $this->operadorId;
    }

    /**
     * Autentica al usuario y genera una sesión de juego.
     *
     * @return string Respuesta en formato JSON con los datos de la sesión.
     * @throws Exception Si ocurre un error durante la autenticación.
     */
    public function Auth()
    {
        try {
            $Proveedor = new Proveedor("", "TOMHORN");

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
                "Code" => 0,
                "playerId" => $UsuarioMandante->usumandanteId,
                "gameName" => $Producto->getExternoId(),
                "sessionId" => $UsuarioToken->getToken(),
                "balance" => round($responseG->saldo, 2),
                "currency" => $responseG->moneda,
                "language" => 'es',
                "timestamp" => gmdate("Y-m-d\TH:i:s\Z")
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del usuario.
     *
     * @return string Respuesta en formato JSON con el balance del usuario.
     * @throws Exception Si ocurre un error al obtener el balance.
     */
    public function getBalance()
    {
        try {
            $Proveedor = new Proveedor("", "TOMHORN");

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


            if (strpos($this->token, "Usuario") !== false) {
                $UsuarioMandante = new UsuarioMandante(str_replace("Usuario", "", $this->token));
            } else {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            }

            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);

            $return = array(
                "Code" => 0,
                "Balance" => array(
                    "Amount" => round($responseG->saldo, 2),
                    "Currency" => $responseG->moneda
                )
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Refresca la sesión del usuario.
     *
     * @return string Respuesta en formato JSON con el estado de la operación.
     * @throws Exception Si ocurre un error al refrescar la sesión.
     */
    public function refreshSesion()
    {
        try {
            $Proveedor = new Proveedor("", "TOMHORN");


            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            $return = array(
                "Code" => 0,
                "Message" => "",
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
     * @param string  $gameId        ID del juego.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param array   $datos         Datos adicionales de la transacción.
     * @param boolean $freespin      Indica si es un giro gratis.
     *
     * @return string Respuesta en formato JSON con los detalles de la transacción.
     * @throws Exception Si ocurre un error durante el débito.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $freespin = false)
    {
        $this->data = $datos;

        try {
            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            //Obtenemos el Proveedor con el abreviado TOMHORN
            $Proveedor = new Proveedor("", "TOMHORN");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);


            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "TOMHORN");


            //Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());


            //Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin);
            $this->transaccionApi = $responseG->transaccionApi;


            $return = array(
                "Code" => 0,
                "Message" => "",

                "Transaction" => array(
                    "Balance" => round($responseG->saldo, 2),
                    "Currency" => $responseG->moneda,
                    "ID" => $responseG->transaccionId

                )
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
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $roundId        ID de la ronda.
     * @param string $transactionId  ID de la transacción.
     * @param string $player         ID del jugador.
     * @param array  $datos          Datos adicionales de la transacción.
     *
     * @return string Respuesta en formato JSON con el estado de la operación.
     * @throws Exception Si ocurre un error durante el rollback.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos)
    {
        $usuarioid = $player;

        $this->data = $datos;


        try {
            //Obtenemos el Proveedor con el abreviado TOMHORN
            $Proveedor = new Proveedor("", "TOMHORN");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $roundId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);


            try {
                $SubProveedor = new Subproveedor("", "TOMHORN");
                $TransjuegoLog = new TransjuegoLog("", "", "", $transactionId . '_' . $SubProveedor->getSubproveedorId(), $SubProveedor->getSubproveedorId());
                $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                $this->transaccionApi->setIdentificador($TransaccionJuego->getTicketId());
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }


            $Game = new Game();

            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false);

            $this->transaccionApi = $responseG->transaccionApi;


            $return = array(
                "Code" => 0,
                "Message" => ""
            );


            //Guardamos la Transaccion Api necesaria de estado OK
            $this->transaccionApi->setRespuestaCodigo("OK");
            $this->transaccionApi->setRespuesta(json_encode($return));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();


            return json_encode($return);
        } catch (Exception $e) {
            if ($_REQUEST['isDebug'] == '1') {
                print_r($e);
            }
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un crédito en la cuenta del usuario.
     *
     * @param string  $gameId        ID del juego.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param array   $datos         Datos adicionales de la transacción.
     * @param boolean $roundClosed   Indica si la ronda está cerrada.
     * @param boolean $isFreeSpin    Indica si es un giro gratis.
     *
     * @return string Respuesta en formato JSON con los detalles de la transacción.
     * @throws Exception Si ocurre un error durante el crédito.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos, $roundClosed, $isFreeSpin = false)
    {
        $this->data = $datos;
        ! empty($isFreeSpin) ? $isFreeSpin = true : $isFreeSpin = false;

        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try {
            //Obtenemos el Proveedor con el abreviado TOMHORN
            $Proveedor = new Proveedor("", "TOMHORN");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);

            /*//Obtenemos el Usuario Token con el token
            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            //Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);*/


            try {
                $UsuarioMandante = new UsuarioMandante($this->externoId);
                $TransaccionJuego = new TransaccionJuego("", $roundId . $UsuarioMandante->getUsumandanteId() . "TOMHORN");
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            $ProductoMandante = new ProductoMandante("", "", $TransaccionJuego->productoId);
            /*  Obtenemos el producto con el $TransaccionJuego->productoId */
            $Producto = new Producto($ProductoMandante->productoId);

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "TOMHORN");


            /*//Obtenemos el producto con el gameId
            $Producto = new Producto("",$gameId,$Proveedor->getProveedorId());

            //Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());*/

            //Agregamos Elementos a la Transaccion API
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());


            $Game = new Game();

            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $roundClosed, false, $isFreeSpin);

            $this->transaccionApi = $responseG->transaccionApi;


            $return = array(
                "Code" => 0,
                "Message" => "",

                "Transaction" => array(
                    "Balance" => round($responseG->saldo, 2),
                    "Currency" => $responseG->moneda,
                    "ID" => $responseG->transaccionId

                )
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
     * Verifica un parámetro y devuelve información relacionada.
     *
     * @param mixed $param Parámetro a verificar.
     *
     * @return string Respuesta en formato JSON con los detalles de la verificación.
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
     * Convierte un error en un formato de respuesta estándar.
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
                $codeProveedor = 5;
                $messageProveedor = "PlayerHandle does not exist";
                break;

            case 21:
                $codeProveedor = 5;
                $messageProveedor = "PlayerHandle does not exist";
                break;

            case 22:
                $codeProveedor = 5;
                $messageProveedor = "PlayerHandle does not exist";
                break;

            case 20001:
                $codeProveedor = "6";
                $messageProveedor = "Insufficient credit";
                break;

            case 0:
                $codeProveedor = 1;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 27:
                $codeProveedor = 1;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 28:
                $codeProveedor = 1;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 29:
                $codeProveedor = '';
                $messageProveedor = "Transaction Not Found";
                break;

            case 10001:
                $codeProveedor = 1;
                $messageProveedor = "Transaction Exists";
                break;

            case 10004:
                $codeProveedor = '9';
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 10014:
                $codeProveedor = '';
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 10010:
                $codeProveedor = '11';
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 10005:
                $codeProveedor = 12;
                $messageProveedor = "Transaction Not Found";
                break;

            default:
                $codeProveedor = '';
                $messageProveedor = "General Error. (" . $code . ")";
                break;
        }


        if ($codeProveedor == "") {
            $respuesta = json_encode(array_merge($response, array(
                "Code" => 1,
                "Message" => $messageProveedor
            )));
        } else {
            $respuesta = json_encode(array_merge($response, array(
                "Code" => $codeProveedor,
                "Message" => $messageProveedor
            )));
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
