<?php

/**
 * Clase Everymatrix
 *
 * Esta clase implementa la integración con el proveedor Everymatrix para realizar operaciones
 * relacionadas con la autenticación, balance, débitos, créditos y rollbacks en un sistema de casino.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */

namespace Backend\integrations\casino;

use Exception;
use Backend\dto\Pais;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\Subproveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransaccionApi;
use Backend\dto\UsuarioMandante;
use Backend\dto\TransaccionJuego;
use Backend\mysql\TransaccionApiMySqlDAO;

/**
 * Clase Everymatrix
 *
 * Esta clase implementa la integración con el proveedor Everymatrix para realizar
 * operaciones relacionadas con la autenticación, balance, débitos, créditos y rollbacks
 * en un sistema de casino.
 */
class Everymatrix
{
    /**
     * Usuario para la autenticación.
     *
     * @var string
     */
    private $user;

    /**
     * Token para la autenticación.
     *
     * @var string
     */
    private $token;

    /**
     * Objeto para manejar las transacciones API.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * Tipo de operación realizada.
     *
     * @var string
     */
    private $type;

    /**
     * Constructor de la clase Everymatrix.
     *
     * @param string $user  Usuario para la autenticación.
     * @param string $token Token para la autenticación.
     */
    public function __construct($user = '', $token = '')
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Método Auth
     *
     * Realiza la autenticación del usuario con el proveedor Everymatrix.
     *
     * @return string Respuesta en formato JSON con los datos del usuario autenticado.
     * @throws Exception Si el token o usuario están vacíos o si ocurre un error durante la autenticación.
     */
    public function Auth()
    {
        $this->type = 'GetAccount';

        try {
            $Proveedor = new Proveedor("", "EVERYMATRIX");

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

            if ($this->token != "") {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                $UsuarioMandante = new UsuarioMandante($this->user);
            }

            $Producto = new Producto($UsuarioToken->productoId);
            $Subproveedor = new Subproveedor($Producto->subproveedorId);
            $Pais = new Pais($UsuarioMandante->paisId);

            $Game = new Game();

            $responseG = $Game->autenticate($UsuarioMandante, $Subproveedor->subproveedorId, $UsuarioToken->productoId);

            switch ($Pais->paisId) {
                case "46":
                    $Pais->iso = "CHL";
                    break;
                case "146":
                    $Pais->iso = "MEX";
                    break;
                case "66":
                    $Pais->iso = "ECU";
                    break;
                case "173":
                    $Pais->iso = "PER";
                    break;
                case "68":
                    $Pais->iso = "SLV";
                    break;
                case "94":
                    $Pais->iso = "GTM";
                    break;
                case "33":
                    $Pais->iso = "BRA";
                    break;
                case "102":
                    $Pais->iso = "HND";
                    break;
            }

            $return = array(
                "ApiVersion" => "1.0",
                "Request" => $this->type,
                "ReturnCode" => 0,
                "SessionId" => $this->token,
                "ExternalUserId" => $UsuarioMandante->usumandanteId,
                "City" => "",
                "Country" => $Pais->iso,
                "Currency" => $UsuarioMandante->moneda,
                "UserName" => $UsuarioMandante->nombres,
                "FirstName" => $UsuarioMandante->nombres,
                "LastName" => $UsuarioMandante->apellidos,
                "Alias" => $UsuarioMandante->nombres,
                "Birthdate" => "1988-01-21",
                "RCPeriod" => 0,
                "AdditionalData" => null,
                "Message" => "Success",
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método Balance
     *
     * Obtiene el balance del usuario autenticado.
     *
     * @return string Respuesta en formato JSON con el balance del usuario.
     * @throws Exception Si el token o usuario están vacíos o si ocurre un error durante la operación.
     */
    public function Balance()
    {
        $this->type = 'GetBalance';

        try {
            $Proveedor = new Proveedor("", "EVERYMATRIX");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("GetBalance");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);

            if ($this->token == "" && $this->user == "") {
                throw new Exception("Token vacio", "10011");
            }

            if ($this->token != "") {
                try {
                    $TokenVal = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                } catch (Exception $e) {
                    throw new Exception("User Invalid", "10018");
                }
            }

            if ($this->token != "") {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                $UsuarioMandante = new UsuarioMandante($this->user);
            }

            $Producto = new Producto($UsuarioToken->productoId);
            $Subproveedor = new Subproveedor($Producto->subproveedorId);

            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante, $Subproveedor->subproveedorId, $UsuarioToken->productoId);

            $Balance = number_format($responseG->saldo, 2, '.', '');

            $return = array(
                "balance" => $Balance,
                "BonusMoney" => 0,
                "RealMoney" => $Balance,
                "Currency" => $UsuarioMandante->moneda,
                "SessionId" => $this->token,
                "AdditionalData" => null,
                "ApiVersion" => 1.0,
                "Request" => "GetBalance",
                "ReturnCode" => 0,
                "Message" => "Success",
                "Details" => null,
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método Debit
     *
     * Realiza un débito en la cuenta del usuario.
     *
     * @param string  $gameId        ID del juego.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param array   $datos         Datos adicionales.
     * @param boolean $gameRoundEnd  Indica si la ronda del juego ha terminado.
     * @param string  $type          Tipo de transacción (opcional).
     * @param string  $SessionId     ID de la sesión.
     *
     * @return string Respuesta en formato JSON con los detalles del débito.
     * @throws Exception Si ocurre un error durante la operación.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $gameRoundEnd, $type = '', $SessionId)
    {
        if ($type != '') {
            $this->type = $type;
        } else {
            $this->type = 'Debit';
        }

        try {
            if ($this->token == "" && $this->user == "") {
                throw new Exception("Token vacio", "10011");
            }

            $Proveedor = new Proveedor("", "EVERYMATRIX");

            if ($this->token != "") {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                $UsuarioMandante = new UsuarioMandante($this->user);
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
                $this->transaccionApi->setIdentificador("EVERYMATRIX" . $roundId);

                $isfreeSpin = false;
                if (floatval($debitAmount) == 0) {
                    $isfreeSpin = true;
                }

                if ($gameRoundEnd == true) {
                    $End = true;
                } else {
                    $End = false;
                }

                $Game = new Game();
                $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $isfreeSpin, [], '', $End);

                $Balance = number_format($responseG->saldo, 2, '.', '');

                $this->transaccionApi = $responseG->transaccionApi;
                $realMoneyAffected = -abs($debitAmount);

                $return = array(
                    "AccountTransactionId" => $transactionId,
                    "Currency" => $UsuarioMandante->moneda,
                    "Balance" => $Balance,
                    "SessionId" => $SessionId,
                    "BonusMoneyAffected" => 0,
                    "RealMoneyAffected" => $realMoneyAffected,
                    "AdditionalData" => null,
                    "ApiVersion" => "1.0",
                    "Request" => "WalletDebit",
                    "ReturnCode" => 0,
                    "Message" => "Success",
                    "Details" => null,
                );

                $this->transaccionApi->setRespuesta($return);
                $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                $TransaccionApiMySqlDAO->update($this->transaccionApi);
                $TransaccionApiMySqlDAO->getTransaction()->commit();

                return json_encode($return);
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método Credit
     *
     * Realiza un crédito en la cuenta del usuario.
     *
     * @param string  $Producto      Producto asociado al crédito.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param array   $datos         Datos adicionales.
     * @param boolean $isBonus       Indica si es un bono (opcional).
     * @param boolean $gameRoundEnd  Indica si la ronda del juego ha terminado.
     * @param string  $SessionId     ID de la sesión.
     *
     * @return string Respuesta en formato JSON con los detalles del crédito.
     * @throws Exception Si ocurre un error durante la operación.
     */
    public function Credit($Producto, $creditAmount, $roundId, $transactionId, $datos, $isBonus = false, $gameRoundEnd, $SessionId)
    {
        $this->type = 'Credit';
        try {
            if ($this->token == "" && $this->user == "") {
                throw new Exception("Token vacio", "10011");
            }

            $Proveedor = new Proveedor("", "EVERYMATRIX");

            if ($this->token != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (Exception $e) {
                    $UsuarioMandante = new UsuarioMandante($this->user);
                }
            } else {
                $UsuarioMandante = new UsuarioMandante($this->user);
            }

            $Producto = new Producto("", $Producto, $Proveedor->getProveedorId());
            $isRollback = false;

            if ($isRollback) {
                throw new Exception("Rollback antes", "10017");
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
            $this->transaccionApi->setIdentificador("EVERYMATRIX" . $roundId);

            if ($gameRoundEnd == true) {
                $End = true;
            } else {
                $End = false;
            }

            $Game = new Game();
            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $End, false, $isBonus, false);

            $this->transaccionApi = $responseG->transaccionApi;
            $Balance = number_format($responseG->saldo, 2, '.', '');

            $return = array(
                "ApiVersion" => "1.0",
                "Request" => "WalletCredit",
                "ReturnCode" => 0,
                "Details" => null,
                "SessionId" => $SessionId,
                "ExternalUserId" => $UsuarioMandante->usumandanteId,
                "AccountTransactionId" => $transactionId,
                "Balance" => $Balance,
                "Currency" => $UsuarioMandante->moneda,
                "Message" => "Success",
            );

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta($return);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método Rollback
     *
     * Realiza un rollback de una transacción previa.
     *
     * @param float   $rollbackAmount Monto a revertir.
     * @param string  $roundId        ID de la ronda.
     * @param string  $transactionId  ID de la transacción.
     * @param array   $datos          Datos adicionales.
     * @param boolean $gameRoundEnd   Indica si la ronda del juego ha terminado.
     * @param string  $gameId         ID del juego.
     * @param string  $SessionId      ID de la sesión.
     *
     * @return string Respuesta en formato JSON con los detalles del rollback.
     * @throws Exception Si ocurre un error durante la operación.
     */
    public function Rollback($rollbackAmount = "", $roundId, $transactionId, $datos, $gameRoundEnd, $gameId, $SessionId)
    {
        $this->type = 'Rollback';
        try {
            $Proveedor = new Proveedor("", "EVERYMATRIX");

            if ($this->token != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
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
                    $TransaccionJuego = new TransaccionJuego('', "EVERYMATRIX" . $roundId);
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
                    $this->transaccionApi->setTValue(json_encode($datos));
                    $this->transaccionApi->setUsucreaId(0);
                    $this->transaccionApi->setUsumodifId(0);
                    $this->transaccionApi->setValor($rollbackAmount);

                    try {
                        $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
                        $TransjuegoLog = new TransjuegoLog("", "", "", $transactionId . '_' . $Producto->subproveedorId, $Producto->subproveedorId);
                        $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);
                        $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());

                        if (strpos($TransjuegoLog->getTipo(), 'DEBIT') !== false || strpos($TransjuegoLog->getTipo(), 'CREDIT') !== false) {
                            $transId = rtrim($TransjuegoLog->transaccionId, "_" . $Producto->subproveedorId);
                            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transId);
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

                    $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false, '', '', '', true, '', $end);
                    $this->transaccionApi = $responseG->transaccionApi;

                    $Balance = number_format($responseG->saldo(), 2, '.', '');

                    $return = array(
                        "ApiVersion" => "1.0",
                        "Request" => "WalletCredit",
                        "ReturnCode" => 0,
                        "Details" => null,
                        "SessionId" => $SessionId,
                        "ExternalUserId" => $UsuarioMandante->usuarioMandante,
                        "AccountTransactionId" => $transactionId,
                        "Balance" => $Balance,
                        "Currency" => $UsuarioMandante->moneda,
                        "AdditionalData" => null,
                        "Message" => "Success"
                    );

                    /*  Guardamos la Transaccion Api necesaria de estado OK   */
                    $this->transaccionApi->setRespuesta($return);
                    $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                    $TransaccionApiMySqlDAO->update($this->transaccionApi);
                    $TransaccionApiMySqlDAO->getTransaction()->commit();

                    return json_encode($return);
                }
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método convertError
     *
     * Convierte un código de error y mensaje en una respuesta JSON.
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
            case 100000:
                $codeProveedor = 101;
                $messageProveedor = "Unkmown error";
                break;

            case 20004:
                $codeProveedor = 102;
                $messageProveedor = "User is blocked";
                break;

            case 21:
                $codeProveedor = 103;
                $messageProveedor = "User not found";
                break;

            case 20001:
                $codeProveedor = 104;
                $messageProveedor = "Insufficient funds";
                break;

            case 100013:
                $codeProveedor = 105;
                $messageProveedor = "IP is not allowed";
                break;

            case 10017:
                $codeProveedor = 106;
                $messageProveedor = "Currency not supported";
                break;

            case 28:
                $codeProveedor = 107;
                $messageProveedor = "Transaction is processing";
                break;

            case 29:
                $codeProveedor = 108;
                $messageProveedor = "Transaction not found";
                break;

            case 20004:
                $codeProveedor = 109;
                $messageProveedor = "Casino loss limit exceeded";
                break;

            case 20013:
                $codeProveedor = 110;
                $messageProveedor = "Casino stake limit exceeded";
                break;

            case 20014:
                $codeProveedor = 111;
                $messageProveedor = "Casino session limit exceeded";
                break;

            case 100030:
                $codeProveedor = 112;
                $messageProveedor = "Max stake limit exceeded";
                break;

            case 20027:
                $codeProveedor = 113;
                $messageProveedor = "User is self exluded";
                break;

            case 20003:
                $codeProveedor = 114;
                $messageProveedor = "User is not active";
                break;
        }

        $respuesta = json_encode(array_merge($response, array(
            "status" => "error",
            "message" => $messageProveedor,
            "code" => $codeProveedor,
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
