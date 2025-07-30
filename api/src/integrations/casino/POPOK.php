<?php

/**
 * Clase POPOK
 *
 * Esta clase implementa la integración con el proveedor de juegos POPOK.
 * Proporciona métodos para autenticar usuarios, consultar balances, realizar débitos, créditos y rollbacks,
 * así como manejar errores relacionados con las transacciones.
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
 * Clase que implementa la integración con el proveedor de juegos POPOK.
 * Proporciona métodos para manejar transacciones como autenticación, débitos, créditos y rollbacks.
 */
class POPOK
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
     * Identificador del usuario.
     *
     * @var integer|null
     */
    private $usuarioId;

    /**
     * Firma de la transacción.
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
     * Datos adicionales de la transacción.
     *
     * @var mixed
     */
    private $data;

    /**
     * Tipo de transacción.
     *
     * @var string
     */
    private $tipo = "";

    /**
     * Constructor de la clase.
     *
     * @param string $token Token de autenticación.
     */
    public function __construct($token)
    {
        try {
            $responseEnable = file_get_contents(__DIR__ . '/../../../../logSit/enabled');
        } catch (Exception $e) {
        }

        if ($responseEnable == 'BLOCKED') {
            http_response_code(408);
            exit();
        }

        $this->token = $token;
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
     * Autentica al usuario con el proveedor POPOK.
     *
     * @return string Respuesta en formato JSON con el estado y balance del usuario.
     */
    public function Auth()
    {
        try {
            $Proveedor = new Proveedor("", "POPOK");

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

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->proveedorId);
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $return = array(
                "status" => 200,
                "balance" => round($responseG->saldo, 2)
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del usuario.
     *
     * @return string Respuesta en formato JSON con el balance, moneda, país y ID del jugador.
     */
    public function getBalance()
    {
        try {
            $Proveedor = new Proveedor("", "POPOK");

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

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->proveedorId);

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $return = array(
                "playerId" => $responseG->usuarioId,
                "balance" => round($responseG->saldo, 2),
                "currency" => $responseG->moneda,
                "country" => $responseG->paisIso2,
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
     * @param mixed   $datos         Datos adicionales.
     * @param boolean $freespin      Indica si es un giro gratis.
     *
     * @return string Respuesta en formato JSON con el balance y detalles de la transacción.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $freespin = false)
    {
        $this->tipo = "DEBIT";
        $this->data = $datos;
        try {
            //Obtenemos el Proveedor con el abreviado POPOK
            $Proveedor = new Proveedor("", "POPOK");

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
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->proveedorId);

                //  Obtenemos el Usuario Mandante con el Usuario Token
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                throw new Exception("Token Vacio", "10011");
            }

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "POPOK");


            //Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());


            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin);
            $this->transaccionApi = $responseG->transaccionApi;


            $return = array(
                "transactionId" => $transactionId,
                "externalTrxId" => $responseG->transaccionId,
                "balance" => round($responseG->saldo, 2)
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
     * Realiza un rollback en la cuenta del usuario.
     *
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $roundId        ID de la ronda asociada.
     * @param string $transactionId  ID de la transacción a revertir.
     * @param mixed  $player         Información del jugador.
     * @param mixed  $datos          Datos adicionales de la transacción.
     *
     * @return string Respuesta en formato JSON con el balance y detalles de la transacción revertida.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos)
    {
        $this->tipo = "ROLLBACK";

        $this->data = $datos;


        try {
            //Obtenemos el Proveedor con el abreviado POPOK
            $Proveedor = new Proveedor("", "POPOK");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);

            try {
                $TransaccionApi2 = new TransaccionApi("", "D" . $transactionId, $Proveedor->getProveedorId());
                $producto = $TransaccionApi2->getProductoId();
                $identificador = $TransaccionApi2->getIdentificador();
                $UsuarioMandante = new UsuarioMandante($TransaccionApi2->getUsuarioId());
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            if ($TransaccionApi2->getValor() != $rollbackAmount) {
                throw new Exception("Detalles de la transacción no coinciden", "10007");
            }


            $this->transaccionApi->setProductoId($producto);
            $this->transaccionApi->setIdentificador($identificador);


            $Game = new Game();

            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false, $roundId);

            $this->transaccionApi = $responseG->transaccionApi;


            $return = array(
                "balance" => round($responseG->saldo, 2),
                "transactionId" => $transactionId,
                "externalTrxId" => $responseG->transaccionId
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
     * @param mixed  $datos         Datos adicionales de la transacción.
     *
     * @return string Respuesta en formato JSON con el balance y detalles de la transacción.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos)
    {
        $this->tipo = "CREDIT";
        $this->data = $datos;

        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try {
            //Obtenemos el Proveedor con el abreviado POPOK
            $Proveedor = new Proveedor("", "POPOK");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);
            //$this->transaccionApi->setIdentificador($roundId . "POPOK");

            /*  Obtenemos el Usuario Token con el token */
            if ($this->token != "") {
                // Obtenemos el Usuario Token con el token
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->proveedorId);

                //  Obtenemos el Usuario Mandante con el Usuario Token
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                throw new Exception("Token Vacio", "10011");
            }

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "POPOK");

            //  Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            //  Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());


            //  Agregamos Elementos a la Transaccion API
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());


            $Game = new Game();

            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, true);

            $this->transaccionApi = $responseG->transaccionApi;


            $return = array(
                "balance" => round($responseG->saldo, 2),
                "transactionId" => $transactionId,
                "externalTrxId" => $responseG->transaccionId
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
     * Converts an error code and message into a standardized JSON response.
     *
     * This method maps internal error codes to provider-specific error codes
     * and messages. It also logs the error in the transaction API if applicable.
     *
     * @param integer $code    The internal error code.
     * @param string  $message The error message.
     *
     * @return string JSON-encoded response with the standardized error details.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";
        $Proveedor = new Proveedor("", "POPOK");
        $response = array();

        switch ($code) {
            case 10011:
                $codeProveedor = "invalid.externalToken";
                $messageProveedor = "External token is invalid or expired";

                break;
            case 10012:
                $codeProveedor = "invalid.externalToken";
                $messageProveedor = "External token is invalid or expired";

                break;
            case 10015:
                $codeProveedor = "transaction.already.cancelled";
                $messageProveedor = "Already cancelled";

                break;

            case 20001:
                $codeProveedor = "insufficient.balance";
                $messageProveedor = "Insufficient player balance";

                break;

            case 10001:

                $codeProveedor = "transaction.already.exists";
                $messageProveedor = "Douplicate transaction";

                break;

            case 10010:
                $codeProveedor = "transaction.already.exists";
                $messageProveedor = "Douplicate transaction";

                break;

            default:
                $codeProveedor = "failure";
                $messageProveedor = "message:must not be blank";
                break;
        }


        if ($code != 10001 && $code != 10010) {
            $respuesta = json_encode(array_merge($response, array(
                "status" => "error",
                "code" => $codeProveedor,
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
