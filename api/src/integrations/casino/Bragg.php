<?php

/**
 * Clase Bragg
 *
 * Esta clase implementa la integración con el proveedor BRAGG para realizar operaciones
 * relacionadas con transacciones de juegos, como autenticación, consulta de balance,
 * débitos, créditos y rollbacks. También incluye manejo de errores específicos del proveedor.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;

use Exception;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransaccionApi;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\dto\TransaccionJuego;
use Backend\mysql\TransaccionApiMySqlDAO;

/**
 * Clase Bragg
 *
 * Esta clase implementa la integración con el proveedor BRAGG para realizar operaciones
 * relacionadas con transacciones de juegos, como autenticación, consulta de balance,
 * débitos, créditos y rollbacks. También incluye manejo de errores específicos del proveedor.
 */
class Bragg
{
    /**
     * Token de autenticación.
     *
     * @var string
     */
    private $token;

    /**
     * Usuario asociado a la transacción.
     *
     * @var string
     */
    private $user;

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
     * Constructor de la clase Bragg.
     *
     * @param string $token Token de autenticación.
     * @param string $user  Usuario asociado.
     */
    public function __construct($token = "", $user = "")
    {
        $this->token = $token;
        $this->user = $user;
    }

    /**
     * Autentica al usuario con el proveedor BRAGG.
     *
     * @return string JSON con los datos del jugador y su balance.
     * @throws Exception Si el token está vacío o ocurre un error en la autenticación.
     */
    public function Auth()
    {
        try {
            $Proveedor = new Proveedor("", "BRAGG");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);

            if ($this->token != "") {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                throw new Exception("Token vacio", "10011");
            }

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Game = new Game();

            $responseG = $Game->autenticate($UsuarioMandante);
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Balance = intval(round($Usuario->getBalance(), 2) * 100);

            $return = array(
                "playerId" => $UsuarioMandante->usumandanteId,
                "balance" => $Balance,
                "currencyCode" => $UsuarioMandante->getMoneda(),
                "languageCode" => "ENG",
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del usuario.
     *
     * @return string JSON con el balance del usuario.
     * @throws Exception Si el usuario no existe o ocurre un error.
     */
    public function getBalance()
    {
        try {
            $Proveedor = new Proveedor("", "BRAGG");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);

            if ($this->user != "") {
                $UsuarioToken = new UsuarioToken('', $Proveedor->getProveedorId(), $this->user);
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                throw new Exception("Usuario no existe", "10011");
            }

            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Balance = intval(round($Usuario->getBalance(), 2) * 100);

            $return = array(
                "balance" => $Balance,
                "responseCode" => "OK",
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
     * @return string JSON con el balance actualizado.
     * @throws Exception Si ocurre un error durante el débito.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos)
    {
        try {
            /*  Obtenemos el Proveedor con el abreviado BRAGG */
            $Proveedor = new Proveedor("", "BRAGG");

            if ($this->user != "") {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                throw new Exception("Usuario no existe", "10011");
            }

            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            $isRollback = false;

            try {
                $TransApi = new TransaccionApi("", "ROLLBACK" . $transactionId, $Proveedor->getProveedorId(), 'ERROR');
                if ($TransApi->tipo == 'ROLLBACK') {
                    $isRollback = true;
                }
            } catch (Exception $e) {
                $isRollback = false;
            }

            if ($isRollback) {
                throw new Exception("Rollback antes", "10004");
            } else {
                /*  Creamos la Transaccion API  */
                $this->transaccionApi = new TransaccionApi();
                $this->transaccionApi->setTransaccionId($transactionId);
                $this->transaccionApi->setTipo("DEBIT");
                $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
                $this->transaccionApi->setTValue(json_encode($datos));
                $this->transaccionApi->setUsucreaId(0);
                $this->transaccionApi->setUsumodifId(0);
                $this->transaccionApi->setValor($debitAmount);
                $this->transaccionApi->setIdentificador("BRAGG" . $roundId);

                $isfreeSpin = false;

                if (floatval($debitAmount) == 0) {
                    $isfreeSpin = true;
                }

                $End = false;
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
                $Game = new Game();
                $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $isfreeSpin, [], '', $End);

                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                $Balance = intval(round($Usuario->getBalance(), 2) * 100);
                $this->transaccionApi = $responseG->transaccionApi;

                $return = array(
                    "balance" => $Balance,
                    "responseCode" => "OK"
                );

                //  Guardamos la Transaccion Api necesaria de estado OK
                $this->transaccionApi->setRespuestaCodigo("OK");
                $this->transaccionApi->setRespuesta(json_encode($return));
                $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                $TransaccionApiMySqlDAO->insert($this->transaccionApi);
                $TransaccionApiMySqlDAO->getTransaction()->commit();

                return json_encode($return);
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un rollback en la cuenta del usuario.
     *
     * Este método revierte una transacción previa en caso de error o cancelación.
     *
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $roundId        ID de la ronda asociada.
     * @param string $transactionId  ID de la transacción a revertir.
     * @param string $player         Identificador del jugador.
     * @param mixed  $datos          Datos adicionales para la transacción.
     *
     * @return string JSON con el balance actualizado o un error en caso de fallo.
     * @throws Exception Si ocurre un error durante el proceso de rollback.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos)
    {
        $usuarioid = explode("Usuario", $player)[1];

        $this->data = $datos;

        try {
            $Proveedor = new Proveedor("", "BRAGG");

            try {
                $UsuarioMandante = new UsuarioMandante($this->user);
            } catch (Exception $e) {
                throw new Exception("User Invalid", "10018");
            }

            if ($this->token != "") {
                try {
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->token);
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (Exception $e) {
                    $UsuarioMandante = new UsuarioMandante($this->user);
                }
            } else {
                $UsuarioMandante = new UsuarioMandante($this->user);
            }

            $isRollback = false;

            if ($isRollback) {
                throw new Exception("Rollback antes", "10017");
            } else {
                $aggtrans = false;
                try {
                    $TransaccionJuego = new TransaccionJuego('', "BRAGG" . $roundId);
                    if ($TransaccionJuego->getValorPremio() != 0) {
                        $aggtrans = false;
                    }
                } catch (Exception $e) {
                    $aggtrans = false;
                }

                if ($aggtrans) {
                    throw new Exception("Ronda cerrada", "10016");
                } else {
                    /*  Creamos la Transaccion API  */
                    $this->transaccionApi = new TransaccionApi();
                    $this->transaccionApi->setTipo("ROLLBACK");
                    $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
                    $this->transaccionApi->setTValue($datos);
                    $this->transaccionApi->setUsucreaId(0);
                    $this->transaccionApi->setUsumodifId(0);
                    $this->transaccionApi->setValor($rollbackAmount);
                    $AllowCreditTransaction = false;

                    $Game = new Game();
                    $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false, '', '', '', $AllowCreditTransaction, '', $end);

                    $this->transaccionApi = $responseG->transaccionApi;

                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                    $Balance = intval(round($Usuario->getBalance(), 2) * 100);

                    $return = array(
                        "balance" => $Balance,
                        "responseCode" => "OK"
                    );

                    //  Guardamos la Transaccion Api necesaria de estado OK
                    $this->transaccionApi->setRespuestaCodigo("OK");
                    $this->transaccionApi->setRespuesta(json_encode($return));
                    $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                    $TransaccionApiMySqlDAO->insert($this->transaccionApi);
                    $TransaccionApiMySqlDAO->getTransaction()->commit();

                    return json_encode($return);
                }
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un crédito en la cuenta del usuario.
     *
     * Este método procesa una transacción de crédito para un usuario en el sistema.
     *
     * @param string  $gameId        ID del juego asociado al crédito.
     * @param float   $creditAmount  Monto a acreditar en la cuenta del usuario.
     * @param string  $roundId       ID de la ronda asociada al crédito.
     * @param string  $transactionId ID de la transacción de crédito.
     * @param mixed   $datos         Datos adicionales relacionados con la transacción.
     * @param boolean $EndRound      Indica si la ronda debe finalizar después del crédito (opcional).
     *
     * @return string JSON con el balance actualizado y el estado de la transacción.
     * @throws Exception Si ocurre un error durante el proceso de crédito.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos, $EndRound = false)
    {
        try {
            /*  Obtenemos el Proveedor con el abreviado BRAGG */
            $Proveedor = new Proveedor("", "BRAGG");

            if ($this->user != "") {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                throw new Exception("Usuario no existe", "10011");
            }

            $Game = new Game();

            $isRollback = false;
            try {
                $TransaccionJuego = new TransaccionJuego("", "BRAGG" . $roundId);
                $TransjuegoLog = new TransjuegoLog("", $TransaccionJuego->transjuegoId, 'ROLLBACK');
                if ($TransjuegoLog->tipo == 'ROLLBACK') {
                    $isRollback = true;
                }
            } catch (Exception $e) {
                $isRollback = false;
            }

            if ($isRollback) {
                throw new Exception("Rollback antes", "10017");
            } else {
                try {
                    $TransaccionJuego = new TransaccionJuego("", "BRAGG" . $roundId);
                    $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                } catch (Exception $e) {
                    throw new Exception("Transaccion no existe", "10005");
                }

                /*  Creamos la Transaccion API  */
                $this->transaccionApi = new TransaccionApi();
                $this->transaccionApi->setTransaccionId($transactionId);
                $this->transaccionApi->setTipo("CREDIT");
                $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
                $this->transaccionApi->setTValue(json_encode($datos));
                $this->transaccionApi->setUsucreaId(0);
                $this->transaccionApi->setUsumodifId(0);
                $this->transaccionApi->setValor($creditAmount);
                $this->transaccionApi->setIdentificador("BRAGG" . $roundId);

                $ProductoMandante = new ProductoMandante("", "", $TransaccionJuego->productoId);
                /*  Obtenemos el producto con el $TransaccionJuego->productoId */
                $Producto = new Producto($ProductoMandante->productoId);

                $isBonus = false;

                if (floatval($creditAmount) == 0) {
                    $isBonus = true;
                }

                $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $EndRound, false, $isBonus, false);

                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                $Balance = intval(round($Usuario->getBalance(), 2) * 100);
                $this->transaccionApi = $responseG->transaccionApi;

                $return = array(
                    "TotalBalance" => $Balance,
                    "PlatformTransactionId" => $this->transaccionApi->transapiId,
                    "PlayerId" => $UsuarioMandante->getUsumandanteId(),
                    "Token" => $UsuarioToken->getToken(),
                    "HasError" => 0,
                    "ErrorId" => 0,
                    "ErrorDescription" => ""
                );

                $return = array(
                    "balance" => $Balance,
                    "responseCode" => "OK"
                );

                //  Guardamos la Transaccion Api necesaria de estado OK
                $this->transaccionApi->setRespuestaCodigo("OK");
                $this->transaccionApi->setRespuesta(json_encode($return));

                $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                $TransaccionApiMySqlDAO->insert($this->transaccionApi);
                $TransaccionApiMySqlDAO->getTransaction()->commit();

                return json_encode($return);
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Convierte un error en una respuesta JSON manejable.
     *
     * Este método toma un código de error y un mensaje, los mapea a códigos y mensajes
     * específicos del proveedor BRAGG, y genera una respuesta JSON que incluye información
     * adicional como el balance del usuario, si corresponde.
     *
     * @param integer $code    Código de error recibido.
     * @param string  $message Mensaje de error recibido.
     *
     * @return string Respuesta JSON con el código de error, descripción y datos adicionales.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $response = array();

        $Proveedor = new Proveedor("", "BRAGG");

        switch ($code) {
            case 10011:
                $codeProveedor = "TOKEN_NOT_FOUND";
                $messageProveedor = "Invalid Token";
                break;

            case 21:
                $codeProveedor = "TOKEN_NOT_FOUND";
                $messageProveedor = "Invalid Token";
                break;

            case 22:
                $codeProveedor = "TOKEN_NOT_FOUND";
                $messageProveedor = "Invalid Token";
                break;

            case 20001:

                $codeProveedor = "OUT_OF_MONEY";
                $messageProveedor = "Not Enough Balance";

                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                $Mandante = new Mandante($UsuarioMandante->mandante);

                if ($Mandante->propio == "S") {
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $Balance = (int)($Usuario->getBalance() * 100);

                    $response = array_merge($response, array(
                        "balance" => $Balance
                    ));
                }

                //http_response_code(500);
                break;

            case 0:
                $codeProveedor = "ERROR";
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 27:
                $codeProveedor = "ERROR";
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 28:
                $codeProveedor = "ROUND_NOT_FOUND";
                $messageProveedor = "ROUND_NOT_FOUND";
                break;

            case 29:
                $codeProveedor = "TRANSACTION_NOT_FOUND";
                $messageProveedor = "Transaction Not Found";
                break;

            case 10001:
                $codeProveedor = "ERROR";
                $codeProveedor = "ERROR";
                $messageProveedor = "Transaction Exists";

                $tipo = $this->transaccionApi->getTipo();

                $codeProveedor = "";
                $messageProveedor = "";

                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                $Mandante = new Mandante($UsuarioMandante->mandante);

                if ($Mandante->propio == "S") {
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $TransaccionApi2 = new TransaccionApi("", $this->transaccionApi->getTransaccionId(), $Proveedor->getProveedorId());

                    $Balance = (int)($Usuario->getBalance() * 100);

                    $response = array_merge($response, array(
                        "balance" => $Balance,
                        "responseCode" => "OK"
                    ));
                }
                break;

            case 10004:
                $codeProveedor = "ERROR";
                $messageProveedor = "General Error. (" . $code . ")";

                $codeProveedor = "ERROR";
                $messageProveedor = "Apuesta con cancelacion antes.";
                break;

            case 10005:
                $codeProveedor = "TRANSACTION_NOT_FOUND";
                $messageProveedor = "Transaction not found";
                break;

            case 10014:
                $codeProveedor = "ERROR";
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 10010:
                $codeProveedor = "ERROR";
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            default:
                $codeProveedor = "ERROR";
                $messageProveedor = "General Error. (" . $code . ")";
                break;
        }


        if ($codeProveedor != "") {
            $respuesta = json_encode(array_merge($response, array(
                "responseCode" => $codeProveedor,
                // "responseCode2" => $codeProveedor,
                "errorDescription" => $messageProveedor,
                "errorDescriptio2n" => $message
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
