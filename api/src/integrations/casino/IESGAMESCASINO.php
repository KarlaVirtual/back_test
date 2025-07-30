<?php

/**
 * Clase principal para la integración con el proveedor de casino IESGAMESCASINO.
 *
 * Este archivo contiene la implementación de métodos para manejar transacciones
 * relacionadas con el proveedor de casino, como autenticación, balance, débitos,
 * créditos, rollbacks y manejo de errores.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;

use Exception;
use Backend\dto\Producto;
use Backend\dto\Subproveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\TransaccionApi;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\mysql\TransaccionApiMySqlDAO;

/**
 * Clase principal para manejar la integración con el proveedor de casino IESGAMESCASINO.
 *
 * Esta clase contiene métodos para realizar operaciones como autenticación,
 * manejo de balance, débitos, créditos, rollbacks y manejo de errores.
 */
class IESGAMESCASINO
{

    /**
     * Token de autenticación.
     *
     * @var string
     */
    private $token;

    /**
     * Identificador del usuario.
     *
     * @var string
     */
    private $usuarioId;

    /**
     * Firma utilizada para la autenticación o validación.
     *
     * @var string
     */
    private $sign;

    /**
     * Objeto para manejar transacciones API.
     *
     * @var TransaccionApi|null
     */
    private $transaccionApi;

    /**
     * Datos adicionales relacionados con la transacción.
     *
     * @var mixed
     */
    private $data;

    private $tipo = "";

    /**
     * Constructor de la clase.
     *
     * @param string $token  Token de autenticación.
     * @param string $UserId ID del usuario.
     */
    public function __construct($token = "", $UserId = "")
    {
        $this->token = $token;
        $this->usuarioId = $UserId;
    }

    /**
     * Autentica al usuario con el proveedor de casino.
     *
     * @return string JSON con los datos de autenticación.
     */
    public function Auth()
    {
        try {
            $Subproveedor = new Subproveedor("", "IESGAMESCASINO");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Subproveedor->getSubproveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);

            $UsuarioToken = new UsuarioToken("", $Subproveedor->getProveedorId(), $this->usuarioId);
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $saldo = floatval(round($responseG->saldo, 2));

            $return = array(
                "playerId" => $UsuarioMandante->usumandanteId,
                "totalBalance" => $saldo,
                "token" => $UsuarioToken->getToken(),
                "hasError" => 0,
                "errorId" => 0
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode());
        }
    }

    /**
     * Obtiene el balance del usuario.
     *
     * @return string JSON con el balance del usuario.
     */
    public function getBalance()
    {
        try {
            $Subproveedor = new Subproveedor("", "IESGAMESCASINO");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("BALANCE");
            $this->transaccionApi->setProveedorId($Subproveedor->getSubproveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);

            $UsuarioToken = new UsuarioToken("", $Subproveedor->getProveedorId(), $this->usuarioId);
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);

            $saldo = floatval(round($responseG->saldo, 2));

            $return = array(
                "playerId" => $UsuarioMandante->usumandanteId,
                "totalBalance" => $saldo,
                "token" => $UsuarioToken->getToken(),
                "hasError" => 0,
                "errorId" => 0
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode());
        }
    }

    /**
     * Realiza un débito en la cuenta del usuario.
     *
     * @param string  $gameId        ID del juego.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param mixed   $datos         Datos adicionales.
     * @param boolean $freespin      Indica si es un giro gratis.
     *
     * @return string JSON con los resultados del débito.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $freespin = false)
    {
        $this->tipo = "DEBIT";
        $this->data = $datos;

        try {
            //Obtenemos el Proveedor con el abreviado IESGAMES
            $Subproveedor = new Subproveedor("", "IESGAMESCASINO");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Subproveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);

            $UsuarioToken = new UsuarioToken("", $Subproveedor->getProveedorId(), $this->usuarioId);
            //Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "IESGAMESCASINO");
            //Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Subproveedor->getProveedorId());

            //Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin);

            $this->transaccionApi = $responseG->transaccionApi;
            $saldo = floatval(round($responseG->saldo, 2));

            $return = array(
                "playerId" => $UsuarioMandante->usumandanteId,
                "totalBalance" => $saldo,
                "token" => $UsuarioToken->getToken(),
                "platformTransactionId" => $responseG->transaccionApi->transaccionId,
                "hasError" => 0,
                "errorId" => 0,
            );

            //Guardamos la Transaccion Api necesaria de estado OK
            $this->transaccionApi->setRespuestaCodigo("OK");
            $this->transaccionApi->setRespuesta(json_encode($return));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode());
        }
    }

    /**
     * Realiza un débito local en la cuenta del usuario.
     *
     * @param string  $gameId        ID del juego.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param mixed   $datos         Datos adicionales.
     * @param boolean $freespin      Indica si es un giro gratis.
     *
     * @return string JSON con los resultados del débito local.
     */
    public function DebitLocal($gameId, $debitAmount, $roundId, $transactionId, $datos, $freespin = false)
    {
        $this->tipo = "DEBIT";
        $this->data = $datos;

        try {
            //Obtenemos el Proveedor con el abreviado IESGAMES
            $Subproveedor = new Subproveedor("", "IESGAMESCASINO");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Subproveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);

            $UsuarioToken = new UsuarioToken($this->token, $Subproveedor->getProveedorId());
            //Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "IESGAMESCASINO");

            //Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Subproveedor->getProveedorId());

            //Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin);

            $this->transaccionApi = $responseG->transaccionApi;
            $saldo = floatval(round($responseG->saldo, 2));

            $return = array(
                "playerId" => $UsuarioMandante->usumandanteId,
                "totalBalance" => $saldo,
                "token" => $UsuarioToken->getToken(),
                "platformTransactionId" => $responseG->transaccionApi->transaccionId,
                "hasError" => 0,
                "errorId" => 0,
            );

            //Guardamos la Transaccion Api necesaria de estado OK
            $this->transaccionApi->setRespuestaCodigo("OK");
            $this->transaccionApi->setRespuesta(json_encode($return));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode());
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
     * @return string JSON con los resultados del rollback.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos)
    {
        $this->tipo = "ROLLBACK";
        $usuarioid = $player;
        $this->data = $datos;

        try {
            $Subproveedor = new Subproveedor("", "IESGAMESCASINO");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Subproveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);

            try {
                $TransaccionApi2 = new TransaccionApi("", $transactionId, $Subproveedor->getProveedorId());
                $producto = $TransaccionApi2->getProductoId();
                $identificador = $TransaccionApi2->getIdentificador();
                $UsuarioMandante = new UsuarioMandante($TransaccionApi2->getUsuarioId());
                //Obtenemos el Usuario Token con el token
                $UsuarioToken = new UsuarioToken("", $Subproveedor->getProveedorId(), $this->usuarioId);
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            $this->transaccionApi->setProductoId($producto);
            $this->transaccionApi->setIdentificador($identificador);

            $Game = new Game();
            $responseG = $Game->rollback($UsuarioMandante, $Subproveedor, $this->transaccionApi, false);
            $this->transaccionApi = $responseG->transaccionApi;

            $saldo = floatval(round($responseG->saldo, 2));

            $return = array(
                "playerId" => $UsuarioMandante->usumandanteId,
                "totalBalance" => $saldo,
                "token" => $UsuarioToken->getToken(),
                "platformTransactionId" => $responseG->transaccionApi->transaccionId,
                "hasError" => 0,
                "errorId" => 0,
            );

            //Guardamos la Transaccion Api necesaria de estado OK
            $this->transaccionApi->setRespuestaCodigo("OK");
            $this->transaccionApi->setRespuesta(json_encode($return));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode());
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
     * @return string JSON con los resultados del crédito.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos)
    {
        $this->tipo = "CREDIT";
        $this->data = $datos;
        $array = json_decode($datos);
        $currency = $array->currency;

        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try {
            //Obtenemos el Proveedor con el abreviado IESGAMES
            $Subproveedor = new Subproveedor("", "IESGAMESCASINO");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Subproveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);

            /*  Obtenemos el Usuario Token con el token */
            // Obtenemos el Usuario Token con el token
            $UsuarioToken = new UsuarioToken("", $Subproveedor->getProveedorId(), $this->usuarioId);

            //  Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "IESGAMESCASINO");

            //  Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Subproveedor->getProveedorId());

            //  Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            //  Agregamos Elementos a la Transaccion API
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());

            $Game = new Game();
            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, true);
            $this->transaccionApi = $responseG->transaccionApi;

            $saldo = floatval(round($responseG->saldo, 2));

            $return = array(
                "playerId" => $UsuarioMandante->usumandanteId,
                "totalBalance" => $saldo,
                "token" => $UsuarioToken->getToken(),
                "platformTransactionId" => $responseG->transaccionApi->transaccionId,
                "hasError" => 0,
                "errorId" => 0,
            );

            //Guardamos la Transaccion Api necesaria de estado OK
            $this->transaccionApi->setRespuestaCodigo("OK");
            $this->transaccionApi->setRespuesta(json_encode($return));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode());
        }
    }

    /**
     * Convierte un código de error en un mensaje legible.
     *
     * @param integer $code Código de error.
     *
     * @return string JSON con el mensaje de error.
     */
    public function convertError($code)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $Subproveedor = new Subproveedor("", "IESGAMESCASINO");
        $response = array();

        switch ($code) {
            case 22:
                $codeProveedor = 8; //OK
                $messageProveedor = "Wrong Player Id";
                break;

            case 20001:
                $codeProveedor = 21; //OK
                $messageProveedor = "Not Enough Balance or Redeemed Code";
                break;

            case 20003:
                $codeProveedor = 29; //OK
                $messageProveedor = "Player is Blocked";
                break;

            case 10011:
                $codeProveedor = 102; //OK
                $messageProveedor = "Invalid Token";
                break;

            case 21:
                $codeProveedor = 102; //OK
                $messageProveedor = "Invalid Token";
                break;

            case 28:
                $codeProveedor = 107; //OK
                $messageProveedor = "Transaction Not Found";
                break;

            case 29:
                $codeProveedor = 107; //OK
                $messageProveedor = "Transaction Not Found";
                break;

            case 10005:
                $codeProveedor = 107; //OK
                $messageProveedor = "Transaction Not Found";
                break;

            case 10015:
                $codeProveedor = 111; //OK
                $messageProveedor = "Rollback already processed";
                break;

            case 10001:
                $codeProveedor = 110; //OK
                $messageProveedor = "Transaction Exists";
                break;

            case 10010:
                $codeProveedor = 110; //OK
                $messageProveedor = "Transaction Exists";
                break;

            case 10007:
                $codeProveedor = 109; //OK
                $messageProveedor = "Wrong Transaction Amount or Wrong Transaction Code";
                break;

            case 10008:
                $codeProveedor = 109; //OK
                $messageProveedor = "Wrong Transaction Amount or Wrong Transaction Code";
                break;

            case 20000:
                $codeProveedor = 112;
                $messageProveedor = "Session expired";
                break;

            default:
                $codeProveedor = 130; //OK
                $messageProveedor = "General Error";
                break;
        }

        $respuesta = json_encode(array_merge($response, array(
            "code" => $codeProveedor,
            "message" => $messageProveedor
        )));

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
