<?php

/**
 * Clase Rubyplay
 *
 * Esta clase implementa la integración con el proveedor Rubyplay para realizar
 * operaciones relacionadas con autenticación, balance, débitos, créditos y rollbacks.
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
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\Subproveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransaccionApi;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\dto\TransaccionJuego;
use Backend\mysql\TransaccionApiMySqlDAO;

/**
 * Clase que implementa la integración con el proveedor Rubyplay.
 *
 * Proporciona métodos para realizar operaciones como autenticación,
 * consulta de balance, débitos, créditos y rollbacks.
 */
class Rubyplay
{
    /**
     * Token de autenticación.
     *
     * @var string
     */
    private $token;

    /**
     * ID externo del usuario.
     *
     * @var string
     */
    private $externalId;

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
     * Datos adicionales para las operaciones.
     *
     * @var mixed
     */
    private $data;

    /**
     * Método actual en ejecución.
     *
     * @var string
     */
    private $method = '';

    /**
     * Indica si hay un error en el hash.
     *
     * @var boolean
     */
    private $errorHash = false;

    /**
     * Constructor de la clase Rubyplay.
     *
     * @param string $token        Token de autenticación.
     * @param string $sign         Firma de seguridad.
     * @param string $external     ID externo del usuario (opcional).
     * @param string $hashOriginal Hash original para validación (opcional).
     */
    public function __construct($token, $sign, $external = "", $hashOriginal = "")
    {
        try {
            $responseEnable = file_get_contents(__DIR__ . '/../../../../logSit/enabled');
        } catch (Exception $e) {
        }

        if ($responseEnable == 'BLOCKED') {
            http_response_code(408);
            exit();
        }

        if ( ! defined('JSON_PRESERVE_ZERO_FRACTION')) {
            define('JSON_PRESERVE_ZERO_FRACTION', 1024);
        }

        $this->token = $token;
        $this->sign = $sign;
        $this->externalId = $external;
        if ($this->sign != $hashOriginal && false) {
            $this->errorHash = true;
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }
    }

    /**
     * Autentica al usuario con el token proporcionado.
     *
     * @param string $token Token de autenticación.
     *
     * @return array Respuesta de autenticación.
     */
    public function Auth($token)
    {
        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }

        $this->method = 'authenticate';
        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            $Proveedor = new Proveedor("", "RUBYPLAY");

            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $array = array(
                "playerId" => $responseG->usuarioId,
                "playerName" => $responseG->usuario,
                "currencyCode" => $responseG->moneda,
                "code" => '0',
                "description" => 'Success',
            );

            return $array;
        } catch (Exception $e) {
            $re_ = $this->convertError($e->getCode(), $e->getMessage());
            $dat_ = json_decode($re_);
            $array = array(
                "playerId" => null,
                "playerName" => null,
                "currencyCode" => null,
                "code" => $dat_->error_code,
                "description" => $dat_->error_msg
            );
            return $array;
        }
    }

    /**
     * Obtiene el balance del usuario.
     *
     * @param string $playerId ID del jugador.
     * @param string $currency Moneda del balance.
     *
     * @return array Respuesta con el balance.
     */
    public function getBalance($playerId, $currency)
    {
        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }

        $this->method = 'balance';
        try {
            $Proveedor = new Proveedor("", "RUBYPLAY");

            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            $Game = new Game();
            $resp = $Game->autenticate($UsuarioMandante);
            if ($resp->moneda != $currency) {
                throw new Exception("Invalid currency", "10029");
            }

            $responseG = $Game->getBalance($UsuarioMandante);

            $saldo = str_replace(',', '', number_format($responseG->saldo, 2));

            $array = array(
                "code" => '0',
                "description" => 'Success',
                "balance" => intval($saldo),
            );

            return $array;
        } catch (Exception $e) {
            $re_ = $this->convertError($e->getCode(), $e->getMessage());
            $dat_ = json_decode($re_);
            $array = array(
                "code" => $dat_->error_code,
                "description" => $dat_->error_msg,
                "balance" => 0,
            );
            return $array;
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
     * @param boolean $gameRoundEnd  Indica si la ronda del juego ha terminado.
     * @param string  $currency      Moneda del débito.
     *
     * @return array Respuesta del débito.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $gameRoundEnd, $currency)
    {
        $this->method = 'debit';
        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }

        $this->data = $datos;

        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado Rubyplay */
            $Proveedor = new Proveedor("", "RUBYPLAY");

            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            $Game = new Game();
            $resp = $Game->autenticate($UsuarioMandante);
            if ($resp->moneda != $currency) {
                throw new Exception("Invalid currency", "10029");
            }

            /*  Obtenemos el producto con el gameId  */
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);
            $this->transaccionApi->setIdentificador("Rubyplay" . $roundId);

            $isfreeSpin = false;
            if (floatval($debitAmount) == 0) {
                $isfreeSpin = true;
            }

            if ($gameRoundEnd == true) {
                $End = false;
            } else {
                $End = true;
            }

            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $isfreeSpin, [], '', $End);

            $this->transaccionApi = $responseG->transaccionApi;

            $saldo = str_replace(',', '', number_format($responseG->saldo, 2));

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            $array = array(
                "code" => '0',
                "description" => 'Success',
                "balance" => intval($saldo),
            );

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta($array);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $array;
        } catch (Exception $e) {
            $re_ = $this->convertError($e->getCode(), $e->getMessage());
            $dat_ = json_decode($re_);
            $array = array(
                "code" => $dat_->error_code,
                "description" => $dat_->error_msg,
                "balance" => $dat_->balance,
            );
            return $array;
        }
    }

    /**
     * Realiza un rollback de una transacción.
     *
     * @param float   $rollbackAmount Monto a revertir.
     * @param string  $roundId        ID de la ronda.
     * @param string  $transactionId  ID de la transacción.
     * @param string  $player         ID del jugador.
     * @param mixed   $datos          Datos adicionales.
     * @param boolean $gameRoundEnd   Indica si la ronda del juego ha terminado.
     *
     * @return array Respuesta del rollback.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos, $gameRoundEnd)
    {
        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }

        $this->method = 'Rollback';

        $this->data = $datos;

        try {
            /*  Obtenemos el Proveedor con el abreviado Rubyplay */
            $Proveedor = new Proveedor("", "RUBYPLAY");

            if ($this->token != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (Exception $e) {
                    $UsuarioMandante = new UsuarioMandante($this->externalId);
                    //$UsuarioMandante = new UsuarioMandante("", $this->externalId, $Usuario->mandante);
                }
            } else {
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue($datos);
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($rollbackAmount);

            try {
                $SubProveedor = new SubProveedor("", "RUBYPLAY");
                $TransjuegoLog = new TransjuegoLog("", "", "", $transactionId . '_' . $SubProveedor->getSubproveedorId(), $SubProveedor->getSubproveedorId());
                $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());

                if (strpos($TransjuegoLog->getTipo(), 'DEBIT') !== false) {
                    $this->transaccionApi->setIdentificador($TransaccionJuego->getTicketId());
                } else {
                    throw new Exception("Transaccion no es Debit", "10006");
                }
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            $Game = new Game();

            if ($gameRoundEnd == true) {
                $end = 'I';
            } else {
                $end = 'A';
            }

            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false, '', '', '', '', '', $end);

            $this->transaccionApi = $responseG->transaccionApi;
            $saldo = str_replace(',', '', number_format($responseG->saldo, 2));

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            $array = array(
                "code" => '0',
                "description" => 'Success',
                "balance" => intval($saldo)
            );

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta($array);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $array;
        } catch (Exception $e) {
            $re_ = $this->convertError($e->getCode(), $e->getMessage());
            $dat_ = json_decode($re_);
            $array = array(
                "code" => $dat_->error_code,
                "description" => $dat_->error_msg,
                "balance" => $dat_->balance,
            );
            return $array;
        }
    }

    /**
     * Realiza un crédito en la cuenta del usuario.
     *
     * @param string  $gameId        ID del juego (opcional).
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param mixed   $datos         Datos adicionales.
     * @param boolean $isBonus       Indica si es un bono.
     * @param boolean $gameRoundEnd  Indica si la ronda del juego ha terminado.
     * @param string  $currency      Moneda del crédito.
     *
     * @return array Respuesta del crédito.
     */
    public function Credit($gameId = "", $creditAmount, $roundId, $transactionId, $datos, $isBonus = false, $gameRoundEnd, $currency)
    {
        $this->method = 'credit';

        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }

        $this->data = $datos;

        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado Rubyplay */
            $Proveedor = new Proveedor("", "RUBYPLAY");

            if ($this->token != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (Exception $e) {
                    $UsuarioMandante = new UsuarioMandante($this->externalId);
                    //$UsuarioMandante = new UsuarioMandante("", $this->externalId, $Usuario->mandante);
                }
            } else {
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            $Game = new Game();
            $resp = $Game->autenticate($UsuarioMandante);
            if ($resp->moneda != $currency) {
                throw new Exception("Invalid currency", "10029");
            }

            try {
                $SubProveedor = new SubProveedor("", "RUBYPLAY");
                $TransjuegoLog = new TransjuegoLog("", "", "", $transactionId . '_' . $SubProveedor->getSubproveedorId(), $SubProveedor->getSubproveedorId());
                $tipo = $TransjuegoLog->getTipo();

                if ($tipo == 'CREDIT') {
                    throw new Exception("Transaccion credit duplicate", "10028");
                }
            } catch (Exception $e) {
                if ($e->getCode() == '10028') {
                    $re_ = $this->convertError($e->getCode(), $e->getMessage());
                    $dat_ = json_decode($re_);
                    $array = array(
                        "code" => $dat_->error_code,
                        "description" => $dat_->error_msg,
                        "balance" => $dat_->balance,
                    );
                    return $array;
                }
            }

            try {
                $TransaccionJuego = new TransaccionJuego("", "RUBYPLAY" . $roundId);
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
            $this->transaccionApi->setIdentificador("Rubyplay" . $roundId);

            $ProductoMandante = new ProductoMandante("", "", $TransaccionJuego->productoId);
            /*  Obtenemos el producto con el $TransaccionJuego->productoId */
            $Producto = new Producto($ProductoMandante->productoId);

            if ($gameRoundEnd == true) {
                $End = true;
            } else {
                $End = false;
            }

            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $End, false, $isBonus, false);

            $this->transaccionApi = $responseG->transaccionApi;

            /*  Retornamos el mensaje satisfactorio  */
            $saldo = str_replace(',', '', number_format($responseG->saldo, 2));

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            $array = array(
                "code" => '0',
                "description" => 'Success',
                "balance" => intval($saldo),
            );

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta($array);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $array;
        } catch (Exception $e) {
            if ($_ENV['debug']) {
                print_r($e);
            }
            $re_ = $this->convertError($e->getCode(), $e->getMessage());
            $dat_ = json_decode($re_);
            $array = array(
                "code" => $dat_->error_code,
                "description" => $dat_->error_msg,
                "balance" => $dat_->balance,
            );
            return $array;
        }
    }

    /**
     * Convierte un error en un formato de respuesta estándar.
     *
     * @param integer $code    Código de error.
     * @param string  $message Mensaje de error.
     *
     * @return string Respuesta en formato JSON.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $response = array();

        $Proveedor = new Proveedor("", "RUBYPLAY");

        switch ($code) {
            case 10011:
                $codeProveedor = "104";
                $messageProveedor = "Invalid player sessionToken";
                $balance = 0;
                break;

            case 21:
                $codeProveedor = "104";
                $messageProveedor = "Invalid player sessionToken";
                $balance = 0;
                break;

            case 22:
                $codeProveedor = "104";
                $messageProveedor = "Invalid player sessionToken";
                $balance = 0;
                break;

            case 20001:
                $codeProveedor = "103";
                $messageProveedor = "Insufficient funds";
                $balance = 0;
                break;

            case 0:
                $codeProveedor = "1";
                $messageProveedor = "Internal server error";
                $balance = 0;
                break;

            case 27: //OK
                $codeProveedor = "4";
                $messageProveedor = "Requested game was not found.";
                $balance = 0;
                break;

            case 28:
                $codeProveedor = "6";
                $messageProveedor = "ROUND_NOT_FOUND";
                $balance = 0;
                break;

            case 29:
                $codeProveedor = "6";
                $messageProveedor = "Transaction Not Found";
                $balance = 0;
                break;

            case 10001: //OK
                if ($this->token != "") {
                    try {
                        $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                        $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                    } catch (Exception $e) {
                        $UsuarioMandante = new UsuarioMandante($this->externalId);
                        //$UsuarioMandante = new UsuarioMandante("", $this->externalId, $Usuario->mandante);
                    }
                } else {
                    $UsuarioMandante = new UsuarioMandante($this->externalId);
                }
                $Game = new Game();
                $responseG = $Game->getBalance($UsuarioMandante);
                $saldo = str_replace(',', '', number_format($responseG->saldo, 2));

                if ($this->method == 'debit') {
                    $codeProveedor = "106";
                    $messageProveedor = "Duplicate transaction Id";
                    $balance = 0;
                } else {
                    if ($this->method == 'credit') {
                        $codeProveedor = "106";
                        $messageProveedor = "Duplicate transaction Id";
                        $balance = 0;
                    } else {
                        $codeProveedor = "0";
                        $messageProveedor = "Success";
                        $balance = intval($saldo);
                    }
                }
                break;

            case 10004:
                $codeProveedor = "1";
                $messageProveedor = "Apuesta con cancelacion antes.";
                $balance = 0;
                break;

            case 10005:
                $codeProveedor = "0";
                $messageProveedor = "Success";
                $balance = 0;
                break;

            case 10014:
                $codeProveedor = "1";
                $messageProveedor = "General Error. (" . $code . ")";
                $balance = 0;
                break;

            case 10010:
                $codeProveedor = "1";
                $messageProveedor = "General Error. (" . $code . ")";
                $balance = 0;
                break;

            case 20002: //OK
                $codeProveedor = "1";
                $messageProveedor = "Hash Mismatch.";
                $balance = 0;
                break;

            case 20003: //OK
                $codeProveedor = "1";
                $messageProveedor = "Player is blocked.";
                $balance = 0;
                break;

            case 10017: //OK
                $codeProveedor = "1";
                $messageProveedor = "Requested currency was not found.";
                $balance = 0;
                break;

            case 10027: //OK
                $codeProveedor = "0";
                $messageProveedor = "Success";
                $balance = 0;
                break;

            case 10028: //OK
                if ($this->token != "") {
                    try {
                        $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                        $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                    } catch (Exception $e) {
                        $UsuarioMandante = new UsuarioMandante($this->externalId);
                        //$UsuarioMandante = new UsuarioMandante("", $this->externalId, $Usuario->mandante);
                    }
                } else {
                    $UsuarioMandante = new UsuarioMandante($this->externalId);
                }

                $Game = new Game();
                $responseG = $Game->getBalance($UsuarioMandante);
                $saldo = str_replace(',', '', number_format($responseG->saldo, 2));
                $codeProveedor = "0";
                $messageProveedor = "Success";
                $balance = intval($saldo);
                break;

            case 10029: //OK
                $codeProveedor = "101";
                $messageProveedor = "Invalid currency";
                $balance = 0;
                break;

            default:
                $codeProveedor = "1"; //OK
                $messageProveedor = "Internal service error";
                $balance = 0;
                break;
        }

        if ($codeProveedor != "") {
            $respuesta = json_encode(array_merge($response, array(
                "error_code" => $codeProveedor,
                "error_msg" => $messageProveedor,
                "balance" => $balance,
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
