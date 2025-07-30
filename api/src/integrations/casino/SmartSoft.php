<?php

/**
 * Clase SmartSoft
 *
 * Esta clase implementa la integración con el proveedor SmartSoft para realizar
 * operaciones relacionadas con transacciones de juegos, como autenticación,
 * consulta de saldo, débitos, créditos, reversión de transacciones y manejo de errores.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @version    1.0.0
 * @since      2025-04-27
 * @author     Desconocido
 */

namespace Backend\integrations\casino;

use Exception;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransaccionApi;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransaccionApiMySqlDAO;

/**
 * Clase SmartSoft
 *
 * Esta clase implementa la integración con el proveedor SmartSoft para realizar
 * operaciones relacionadas con transacciones de juegos, como autenticación,
 * consulta de saldo, débitos, créditos, reversión de transacciones y manejo de errores.
 */
class SmartSoft
{
    /**
     * Token de autenticación.
     *
     * @var string
     */
    private $token;

    /**
     * ID del usuario.
     *
     * @var string
     */
    private $usuarioId;

    /**
     * Firma utilizada en las transacciones.
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
     * Datos adicionales para las transacciones.
     *
     * @var mixed
     */
    private $data;

    /**
     * Tipo de operación actual (e.g., DEBIT, CREDIT, ROLLBACK).
     *
     * @var string
     */
    private $tipo = "";

    /**
     * Constructor de la clase SmartSoft.
     *
     * @param string $token     Token de autenticación.
     * @param string $usuarioId ID del usuario (opcional).
     */
    public function __construct($token, $usuarioId = "")
    {
        $this->token = $token;
        $this->usuarioId = $usuarioId;
    }

    /**
     * Autentica al usuario con el proveedor SmartSoft.
     *
     * @return string JSON con los datos de la sesión y usuario autenticado.
     * @throws Exception Si el token está vacío o ocurre un error en la autenticación.
     */
    public function Auth()
    {
        try {
            $Proveedor = new Proveedor("", "SMARTSOFT");

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

            if ($this->token != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (Exception $e) {
                    $UsuarioMandante = new UsuarioMandante($this->usuarioId);
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->usumandanteId);
                }
            } else {
                $UsuarioMandante = new UsuarioMandante($this->usuarioId);
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->usumandanteId);
            }

            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $Producto = new Producto($UsuarioToken->productoId);

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $PORTAL_NAME = $credentials->PORTAL_NAME;

            $return = array(
                "SessionId" => $UsuarioToken->getToken(),
                "UserName" => $UsuarioMandante->nombres,
                "ClientExternalKey" => $UsuarioMandante->usumandanteId,
                "CurrencyCode" => $responseG->moneda,
                "PortalName" => $PORTAL_NAME,
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el saldo del usuario autenticado.
     *
     * @return string JSON con el saldo y la moneda del usuario.
     * @throws Exception Si el token está vacío o ocurre un error al obtener el saldo.
     */
    public function getBalance()
    {
        try {
            $Proveedor = new Proveedor("", "SMARTSOFT");

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

            if ($this->token != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (Exception $e) {
                    $UsuarioMandante = new UsuarioMandante($this->usuarioId);
                }
            } else {
                $UsuarioMandante = new UsuarioMandante($this->usuarioId);
            }

            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);

            $return = array(
                "CurrencyCode" => $responseG->moneda,
                "Amount" => round($responseG->saldo, 2)
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
     * @param mixed   $datos         Datos adicionales de la transacción.
     * @param boolean $freespin      Indica si es un giro gratis (opcional).
     *
     * @return string JSON con el ID de la transacción y el saldo restante.
     * @throws Exception Si ocurre un error durante el débito.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $freespin = false)
    {
        $this->tipo = "DEBIT";
        $this->data = $datos;
        try {
            //Obtenemos el Proveedor con el abreviado SmartSoft
            $Proveedor = new Proveedor("", "SMARTSOFT");

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
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (Exception $e) {
                    $UsuarioMandante = new UsuarioMandante($this->usuarioId);
                }
            } else {
                $UsuarioMandante = new UsuarioMandante($this->usuarioId);
            }

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "SMARTSOFT");

            //Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin);
            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "TransactionId" => $responseG->transaccionId,
                "Balance" => round($responseG->saldo, 2)
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
     * Reversa una transacción previa (rollback).
     *
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $roundId        ID de la ronda.
     * @param string $transactionId  ID de la transacción original.
     * @param string $player         ID del jugador.
     * @param mixed  $datos          Datos adicionales de la transacción.
     *
     * @return string JSON con el ID de la transacción y el saldo actualizado.
     * @throws Exception Si ocurre un error durante la reversión.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos)
    {
        $this->tipo = "ROLLBACK";
        $usuarioid = $player;
        $this->data = $datos;
        try {
            //Obtenemos el Proveedor con el abreviado SmartSoft
            $Proveedor = new Proveedor("", "SMARTSOFT");

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
                $TransaccionApi2 = new TransaccionApi("", $transactionId, $Proveedor->getProveedorId());
                $producto = $TransaccionApi2->getProductoId();
                $identificador = $TransaccionApi2->getIdentificador();
                $UsuarioMandante = new UsuarioMandante($TransaccionApi2->getUsuarioId());
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            $this->transaccionApi->setProductoId($producto);
            $this->transaccionApi->setIdentificador($identificador);

            $Game = new Game();
            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false, "", true, false, true);
            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "TransactionId" => $responseG->transaccionId,
                "Balance" => round($responseG->saldo, 2)
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
     * @param string  $gameId        ID del juego.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param mixed   $datos         Datos adicionales de la transacción.
     * @param boolean $isEndRound    Indica si es el final de la ronda.
     *
     * @return string JSON con el ID de la transacción y el saldo actualizado.
     * @throws Exception Si ocurre un error durante el crédito.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos, $isEndRound, $betTransactionId)
    {
        $this->tipo = "CREDIT";
        $this->data = $datos;

        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try {
            //Obtenemos el Proveedor con el abreviado SmartSoft
            $Proveedor = new Proveedor("", "SMARTSOFT");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);

            try {
                $TransaccionApi = new TransaccionApi("", $betTransactionId, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($TransaccionApi->getUsuarioId());
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "SMARTSOFT");

            try {
                $ProductoMandante = new ProductoMandante("", "", $TransaccionApi->productoId);
                $Producto = new Producto($ProductoMandante->productoId);
            } catch (Exception $e) {
                $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
                $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());
            }

            //  Agregamos Elementos a la Transaccion API
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());

            $Game = new Game();
            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $isEndRound);
            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "TransactionId" => $responseG->transaccionId,
                "Balance" => round($responseG->saldo, 2)
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
     * Convierte un error en un formato legible y lo registra.
     *
     * @param integer $code    Código del error.
     * @param string  $message Mensaje del error.
     *
     * @return string JSON con los detalles del error.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $Proveedor = new Proveedor("", "SMARTSOFT");
        $response = array();

        switch ($code) {
            case 10011:
                $codeProveedor = 500;
                $messageProveedor = "Invalid Token.";
                header('X-ErrorCode:' . $codeProveedor);
                header('X-ErrorMessage:' . $messageProveedor);
                break;

            case 10012:
                $codeProveedor = 500;
                $messageProveedor = "Token already used.";
                header('X-ErrorCode:' . $codeProveedor);
                header('X-ErrorMessage:' . $messageProveedor);
                break;

            case 10015:
                $codeProveedor = 500;
                $messageProveedor = "Transaction has been cancelled.";
                header('X-ErrorCode:' . $codeProveedor);
                header('X-ErrorMessage:' . $messageProveedor);
                break;

            case 10016:
                $codeProveedor = 500;
                $messageProveedor = "Trying to cancel a bet from an already closed round.";
                header('X-ErrorCode:' . $codeProveedor);
                header('X-ErrorMessage:' . $messageProveedor);
                break;

            case 21:
                $codeProveedor = 500;
                $messageProveedor = "Token already used.";
                header('X-ErrorCode:' . $codeProveedor);
                header('X-ErrorMessage:' . $messageProveedor);
                break;

            case 22:
                $codeProveedor = 500;
                $messageProveedor = "Player not found.";
                header('X-ErrorCode:' . $codeProveedor);
                header('X-ErrorMessage:' . $messageProveedor);
                break;

            case 20001:
                $codeProveedor = "500";
                $messageProveedor = "Insufficient Funds.";
                header('X-ErrorCode:' . $codeProveedor);
                header('X-ErrorMessage:' . $messageProveedor);
                break;

            case 20005:
                $codeProveedor = 500;
                $messageProveedor = "active self-exclusion.";
                header('X-ErrorCode:' . $codeProveedor);
                header('X-ErrorMessage:' . $messageProveedor);
                break;

            case 28:
                $codeProveedor = 500;
                $messageProveedor = "Invalid cancel, Transaction does not exist.";
                header('X-ErrorCode:' . $codeProveedor);
                header('X-ErrorMessage:' . $messageProveedor);
                break;

            case 29:
                $codeProveedor = 500;
                $messageProveedor = "Duplicate Transaction Id.";
                header('X-ErrorCode:' . $codeProveedor);
                header('X-ErrorMessage:' . $messageProveedor);
                break;

            case 10001:
                switch ($this->tipo) {
                    case "DEBIT":
                        $codeProveedor = "";
                        $messageProveedor = "Duplicate Transaction Id.";

                        $ProductoMandante = new ProductoMandante('', '', $this->transaccionApi->getProductoId());
                        $Producto = new Producto($ProductoMandante->productoId);

                        $TransjuegoLog = new TransjuegoLog("", '', '', $this->transaccionApi->getTransaccionId() . '_' . $Producto->getSubproveedorId(), $Producto->getSubproveedorId());
                        $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);

                        $UsuarioMandante = new UsuarioMandante($TransaccionJuego->usuarioId);
                        $Game = new Game();
                        $responseG = $Game->getBalance($UsuarioMandante);

                        $response = array(
                            "TransactionId" => $TransjuegoLog->transjuegologId,
                            "balance" => round($responseG->saldo, 2)
                        );
                        break;

                    case "CREDIT":
                        $codeProveedor = "";
                        $messageProveedor = "Duplicate Transaction Id.";

                        $ProductoMandante = new ProductoMandante('', '', $this->transaccionApi->getProductoId());
                        $Producto = new Producto($ProductoMandante->productoId);


                        $TransjuegoLog = new TransjuegoLog("", '', '', $this->transaccionApi->getTransaccionId() . '_' . $Producto->getSubproveedorId(), $Producto->getSubproveedorId());
                        $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);

                        $UsuarioMandante = new UsuarioMandante($TransaccionJuego->usuarioId);
                        $Game = new Game();
                        $responseG = $Game->getBalance($UsuarioMandante);

                        $response = array(
                            "TransactionId" => $TransjuegoLog->transjuegologId,
                            "Balance" => round($responseG->saldo, 2)
                        );
                        break;

                    case "ROLLBACK":
                        $ProductoMandante = new ProductoMandante('', '', $this->transaccionApi->getProductoId());
                        $Producto = new Producto($ProductoMandante->productoId);

                        $TransjuegoLog = new TransjuegoLog("", '', '', $this->transaccionApi->getTransaccionId() . '_' . $Producto->getSubproveedorId(), $Producto->getSubproveedorId());
                        $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);

                        $UsuarioMandante = new UsuarioMandante($TransaccionJuego->usuarioId);
                        $Game = new Game();

                        $responseG = $Game->getBalance($UsuarioMandante);

                        $response = array(
                            "TransactionId" => $TransjuegoLog->transjuegologId,
                            "Balance" => round($responseG->saldo, 2)
                        );
                        break;
                }
                break;

            case 10010:
                $codeProveedor = "";
                $messageProveedor = "Duplicate Transaction Id.";

                $transaccionApi2 = new TransaccionApi("", $this->transaccionApi->getTransaccionId(), $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($transaccionApi2->usuarioId);
                $Game = new Game();

                $responseG = $Game->getBalance($UsuarioMandante);

                $response = array(
                    "TransactionId" => $transaccionApi2->transapiId,
                    "Balance" => round($responseG->saldo, 2)
                );
                break;

            case 10005:
                $codeProveedor = 500;
                $messageProveedor = "Invalid cancel, Transaction does not exist";
                header('X-ErrorCode:' . $codeProveedor);
                header('X-ErrorMessage:' . $messageProveedor);
                break;

            default:
                $codeProveedor = "";
                $messageProveedor = "";
                break;
        }

        if ($code != 10001 && $code != 10010) {
            $respuesta = json_encode(array_merge($response, array(
                "status" => "error",
                "code" => $codeProveedor,
                "message" => $messageProveedor
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
