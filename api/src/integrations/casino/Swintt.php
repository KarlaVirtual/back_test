<?php

/**
 * Clase Swintt para la integración con el proveedor SWINTT.
 *
 * Este archivo contiene la implementación de la clase Swintt, que maneja
 * las operaciones relacionadas con la integración de juegos, incluyendo
 * autenticación, balance, débitos, créditos, rollbacks y finalización de rondas.
 *
 * @category   Integración
 * @package    API
 * @subpackage Casino
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;

use Backend\dto\Mandante;
use Backend\dto\Pais;
use Backend\dto\PromocionalLog;
use Backend\dto\Registro;
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
use Backend\websocket\WebsocketUsuario;
use Exception;
use SimpleXMLElement;

/**
 * Clase principal para la integración con el proveedor SWINTT.
 *
 * Esta clase maneja las operaciones relacionadas con la integración de juegos,
 * incluyendo autenticación, balance, débitos, créditos, rollbacks y finalización de rondas.
 */
class Swintt
{
    /**
     * Token de autenticación.
     *
     * @var string
     */
    private $token;

    /**
     * Método actual en ejecución.
     *
     * @var string
     */
    private $method;

    /**
     * ID del juego.
     *
     * @var string
     */
    private $gameid;

    /**
     * ID de la transacción.
     *
     * @var string
     */
    private $transactionId;

    /**
     * Constructor de la clase Swintt.
     *
     * @param string  $token         Token de autenticación.
     * @param integer $usuarioId     ID del usuario.
     * @param string  $gameid        ID del juego (opcional).
     * @param string  $transactionId ID de la transacción (opcional).
     */
    public function __construct($token, $usuarioId, $gameid = "", $transactionId = "")
    {
        $this->token = $token;
        $this->usuarioId = $usuarioId;
        $this->gameid = $gameid;
        $this->transactionId = $transactionId;
    }

    /**
     * Autenticación del usuario con el proveedor SWINTT.
     *
     * @return string Respuesta en formato JSON con los datos del usuario autenticado.
     * @throws Exception Si el token está vacío o ocurre un error durante la autenticación.
     */
    public function Auth()
    {
        try {
            $Proveedor = new Proveedor("", "SWINTT");

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

            $Game = new Game();
            $Pais = new Pais($UsuarioMandante->paisId);
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            $Balance = $Usuario->getBalance();

            $responseG = $Game->autenticate($UsuarioMandante);
            $message = "Request processed successfully";

            $return = array(
                "statusCode" => 8000,
                "message" => $message,
                "partnerSessionId" => $this->token,
                "player" => array(
                    "id" => $UsuarioMandante->usumandanteId,
                    "token" => $this->token,
                    "balance" => $Balance,
                    "country" => $Pais->iso,
                    "currencyId" => $UsuarioMandante->moneda,
                ),
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
     * @throws Exception Si el token o el usuario están vacíos o ocurre un error.
     */
    public function Balance()
    {
        $this->type = 'Balance';

        try {
            $Proveedor = new Proveedor("", "SWINTT");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);

            if ($this->token == "" && $this->user == "") {
                throw new Exception("Token vacio", "10011");
            }

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $Producto = new Producto($UsuarioToken->productoId);
            $Subproveedor = new Subproveedor($Producto->subproveedorId);
            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante, $Subproveedor->subproveedorId, $UsuarioToken->productoId);

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Balance = $Usuario->getBalance();
            $message = "Request processed successfully";

            $return = array(
                "statusCode" => 8000,
                "message" => $message,
                "balance" => $Balance,
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un débito en el balance del usuario.
     *
     * @param string  $gameId        ID del juego.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param array   $datos         Datos adicionales.
     * @param boolean $freespin      Indica si es un giro gratis (opcional).
     *
     * @return string Respuesta en formato JSON con los detalles del débito.
     * @throws Exception Si ocurre un error durante el débito.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $freespin = false)
    {
        $this->tipo = "DEBIT";
        $this->data = $datos;

        try {
            //Obtenemos el Proveedor con el abreviado SWINTT
            $Proveedor = new Proveedor("", "SWINTT");

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
                // Obtenemos el Usuario Token con el token
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                //  Obtenemos el Usuario Mandante con el Usuario Token
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            }

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            $Saldo = $Usuario->getBalance();
            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "SWINTT");

            //Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin);

            $this->transaccionApi = $responseG->transaccionApi;

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $NewSaldo = $Usuario->getBalance();

            $Message = "Request processed successfully";

            $return = array(
                "statusCode" => 8000,
                "message" => $Message,
                "amount" => $debitAmount,
                "prevBalance" => $Saldo,
                "balance" => $NewSaldo,
                "transactionId" => $transactionId,
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
     * @param string $gameId         ID del juego.
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $roundId        ID de la ronda.
     * @param string $transactionId  ID de la transacción.
     * @param array  $datos          Datos adicionales.
     *
     * @return string Respuesta en formato JSON con los detalles del rollback.
     * @throws Exception Si ocurre un error durante el rollback.
     */
    public function Rollback($gameId, $rollbackAmount, $roundId, $transactionId, $datos)
    {
        $this->tipo = "ROLLBACK";
        $this->data = $datos;

        try {
            //Obtenemos el Proveedor con el abreviado SWINTT
            $Proveedor = new Proveedor("", "SWINTT");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($rollbackAmount);

            try {
                $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
                $TransaccionJuego = new TransaccionJuego("", $roundId . $this->usuarioId . "SWINTT");
                $TransjuegoLog = new TransjuegoLog("", $TransaccionJuego->transjuegoId, "", $TransaccionJuego->transaccionId . '_' . $Producto->subproveedorId);
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                if (strpos($TransjuegoLog->getTipo(), 'DEBIT') !== false) {
                    $transId = explode("_", $TransjuegoLog->transaccionId);
                    $transId = $transId[0];
                    $this->transaccionApi->setTransaccionId('ROLLBACK' . $transId);
                    $this->transaccionApi->setIdentificador($TransaccionJuego->getTicketId());
                } else {
                    throw new Exception("Transaccion no es Debit", "10006");
                }
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            $this->transaccionApi->setProductoId($Producto->productoId);
            $Game = new Game();
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Saldo = $Usuario->getBalance();

            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false, "", true, false, true);

            $this->transaccionApi = $responseG->transaccionApi;
            $Message = "Request processed successfully";

            $return = array(
                "statusCode" => 8000,
                "message" => $Message,
                "balance" => $Saldo,
                "transactionId" => $transactionId,
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
     * Realiza un crédito en el balance del usuario.
     *
     * @param string  $gameId        ID del juego.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param array   $datos         Datos adicionales.
     * @param boolean $roundClosed   Indica si la ronda está cerrada.
     *
     * @return string Respuesta en formato JSON con los detalles del crédito.
     * @throws Exception Si ocurre un error durante el crédito.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos, $roundClosed)
    {
        $this->tipo = "CREDIT";
        $this->data = $datos;

        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try {
            //Obtenemos el Proveedor con el abreviado SWINTT
            $Proveedor = new Proveedor("", "SWINTT");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);
            $TransaccionJuego = new TransaccionJuego("", $roundId . $this->usuarioId . "SWINTT");

            try {
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                $TransaccionJuego = new TransaccionJuego("", $roundId . $UsuarioMandante->getUsumandanteId() . "SWINTT");
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "SWINTT");
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            $Saldo = $Usuario->getBalance();

            //  Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            //  Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            //  Agregamos Elementos a la Transaccion API
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());

            $Game = new Game();
            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $roundClosed);
            $this->transaccionApi = $responseG->transaccionApi;

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $NewSaldo = $Usuario->getBalance();

            $Message = "Request processed successfully";

            $return = array(
                "statusCode" => 8000,
                "message" => $Message,
                "amount" => $creditAmount,
                "prevBalance" => $Saldo,
                "balance" => $NewSaldo,
                "transactionId" => $transactionId,
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
     * Finaliza una ronda de juego.
     *
     * @param string $RoundId ID de la ronda.
     * @param array  $datos   Datos adicionales.
     * @param string $cur     Moneda utilizada.
     *
     * @return string Respuesta en formato XML con los detalles de la finalización.
     * @throws Exception Si ocurre un error durante la finalización de la ronda.
     */
    public function EndRound($RoundId, $datos, $cur)
    {
        $this->method = 'finalizeResp';

        try {
            /*  Obtenemos el Proveedor con el abreviado SWINTT */
            $Proveedor = new Proveedor("", "SWINTT");

            if ($this->acctid != "") {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->acctid);
            }


            /*  Obtenemos el Proveedor con el abreviado SWINTT */
            $Proveedor = new Proveedor("", "SWINTT");


            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId("ENDROUND" . $RoundId);
            $this->transaccionApi->setTipo("ENDROUND");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);
            $this->transaccionApi->setIdentificador("SWINTT" . $RoundId);

            $Game = new Game();

            $responseG = $Game->endRound($this->transaccionApi);

            $this->transaccionApi = $responseG->transaccionApi;


            /*  Retornamos el mensaje satisfactorio  */

            $saldo = ($responseG->saldo);

            $Result = new SimpleXMLElement("<cw></cw>");

            $Result->addAttribute('type', $this->method);
            $Result->addAttribute('cur', $cur);
            $Result->addAttribute('amt', $saldo);
            $Result->addAttribute('err', '0');

            return explode("\n", $Result->asXML(), 2)[1];
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
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

        $Proveedor = new Proveedor("", "SWINTT");
        $response = array();

        switch ($code) {
            case 28:
                $codeProveedor = 8010;
                $messageProveedor = "Invalid transaction";
                break;

            case 10005:
                $codeProveedor = 8010;
                $messageProveedor = "Invalid transaction";
                break;

            case 10001:
                $codeProveedor = 8010;
                $messageProveedor = "Invalid transaction";
                break;

            case 20001:
                $codeProveedor = 8004;
                $messageProveedor = "Insufficient funds";
                break;

            case 21:
                $codeProveedor = 8002;
                $messageProveedor = "Invalid token";
                break;

            case 10011:
                $codeProveedor = 8002;
                $messageProveedor = "Invalid token";
                break;


            case 26:
                $codeProveedor = 8007;
                $messageProveedor = "Game not found";
                break;

            case 24:
                $codeProveedor = 8021;
                $messageProveedor = "Invalid player";
                break;

            case 10017:
                $codeProveedor = 8012;
                $messageProveedor = "Invalid currency";
                break;

            default:
                $codeProveedor = 8500;
                $messageProveedor = "Internal error";
                break;
        }

        if ($messageProveedor != "") {
            $respuesta = json_encode(array_merge($response, array("message" => $messageProveedor, "statusCode" => $codeProveedor)));
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
