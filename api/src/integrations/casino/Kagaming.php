<?php

/**
 * Clase `Kagaming` que implementa la integración con el proveedor de juegos KAGAMING.
 *
 * Este archivo contiene métodos para manejar la autenticación, balance, débitos, créditos,
 * rollbacks y otras operaciones relacionadas con la integración de juegos.
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
 * Clase `Kagaming` que implementa la integración con el proveedor de juegos KAGAMING.
 *
 * Esta clase contiene métodos para manejar la autenticación, balance, débitos, créditos,
 * rollbacks y otras operaciones relacionadas con la integración de juegos.
 */
class Kagaming
{
    /**
     * Usuario asociado a la sesión.
     *
     * @var string
     */
    private $user;

    /**
     * Token de autenticación del usuario.
     *
     * @var string
     */
    private $token;

    /**
     * Objeto de transacción API.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * ID de la sesión actual.
     *
     * @var string
     */
    private $sessionId;

    /**
     * Tipo de operación actual.
     *
     * @var string
     */
    private $type;

    /**
     * Constructor de la clase `Kagaming`.
     *
     * @param string $user      Usuario asociado a la sesión.
     * @param string $token     Token de autenticación del usuario.
     * @param string $sessionId ID de la sesión actual.
     */
    public function __construct($user = '', $token = '', $sessionId = '')
    {
        $this->user = $user;
        $this->token = $token;
        $this->sessionId = $sessionId;
    }

    /**
     * Método para autenticar al usuario con el proveedor KAGAMING.
     *
     * @return string JSON con los datos de autenticación y balance del usuario.
     * @throws Exception Si el token o el usuario están vacíos.
     */
    public function Auth()
    {
        $this->type = 'Auth';
        try {
            $Proveedor = new Proveedor("", "KAGAMING");

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

            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $Balance = intval(round($responseG->saldo, 2) * 100);

            $return = array(
                "playerId" => $this->user,
                "sessionId" => $this->sessionId,
                "currency" => $responseG->moneda,
                "balance" => $Balance,
                "status" => 'success',
                "statusCode" => 0
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para obtener el balance del usuario.
     *
     * @return string JSON con el balance del usuario.
     * @throws Exception Si el token o el usuario están vacíos.
     */
    public function Balance()
    {
        $this->type = 'Balance';
        try {
            $Proveedor = new Proveedor("", "KAGAMING");

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

            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);

            $Balance = intval(round($responseG->saldo, 2) * 100);

            $return = array(
                "balance" => $Balance,
                "status" => 'success',
                "statusCode" => 0,
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para realizar un débito en la cuenta del usuario.
     *
     * @param string  $gameId        ID del juego.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param array   $datos         Datos adicionales de la transacción.
     * @param boolean $gameRoundEnd  Indica si la ronda del juego ha terminado.
     * @param string  $currency      Moneda utilizada.
     * @param string  $type          Tipo de operación (opcional).
     *
     * @return string JSON con el balance actualizado.
     * @throws Exception Si ocurre un error durante la operación.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $gameRoundEnd, $currency, $type = '')
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

            /*  Obtenemos el Proveedor con el abreviado KAGAMING */
            $Proveedor = new Proveedor("", "KAGAMING");

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
                $this->transaccionApi->setIdentificador("KAGAMING" . $roundId);

                $isfreeSpin = false;
                if (floatval($debitAmount) == 0) {
                    $isfreeSpin = true;
                }

                if ($gameRoundEnd == true) {
                    $End = true;
                } else {
                    $End = false;
                }

                $Producto = new Producto($UsuarioToken->productoId);
                $Game = new Game();

                $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $isfreeSpin, [], '', $End);

                $Balance = intval(round($responseG->saldo, 2) * 100);
                $this->transaccionApi = $responseG->transaccionApi;

                $return = array(
                    "balance" => $Balance,
                    "status" => 'success',
                    "statusCode" => 0,
                );

                /*  Guardamos la Transaccion Api necesaria de estado OK   */
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
     * Método para realizar un crédito en la cuenta del usuario.
     *
     * @param string  $Producto      Producto asociado (opcional).
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param array   $datos         Datos adicionales de la transacción.
     * @param boolean $isBonus       Indica si es un bono (opcional).
     * @param boolean $gameRoundEnd  Indica si la ronda del juego ha terminado.
     * @param string  $currency      Moneda utilizada.
     *
     * @return string JSON con el balance actualizado.
     * @throws Exception Si ocurre un error durante la operación.
     */
    public function Credit($Producto = "", $creditAmount, $roundId, $transactionId, $datos, $isBonus = false, $gameRoundEnd, $currency)
    {
        $this->type = 'Credit';
        try {
            if ($this->token == "" && $this->user == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado KAGAMING */
            $Proveedor = new Proveedor("", "KAGAMING");

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

            $Game = new Game();

            $isRollback = false;
            try {
                $TransaccionJuego = new TransaccionJuego("", "KAGAMING" . $roundId);
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
                    $TransaccionJuego = new TransaccionJuego("", "KAGAMING" . $roundId);
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
                $this->transaccionApi->setIdentificador("KAGAMING" . $roundId);

                $ProductoMandante = new ProductoMandante("", "", $TransaccionJuego->productoId);
                /*  Obtenemos el producto con el $TransaccionJuego->productoId */
                $Producto = new Producto($ProductoMandante->productoId);

                if ($gameRoundEnd == true) {
                    $End = true;
                } else {
                    $End = false;
                }

                $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $End, false, $isBonus, false);

                $Balance = intval(round($responseG->saldo, 2) * 100);
                $this->transaccionApi = $responseG->transaccionApi;

                $return = array(
                    "balance" => $Balance,
                    "status" => 'success',
                    "statusCode" => 0,
                );

                /*  Guardamos la Transaccion Api necesaria de estado OK   */
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
     * Método para realizar un rollback de una transacción.
     *
     * @param float   $rollbackAmount Monto a revertir.
     * @param string  $roundId        ID de la ronda.
     * @param string  $transactionId  ID de la transacción.
     * @param array   $datos          Datos adicionales de la transacción.
     * @param boolean $gameRoundEnd   Indica si la ronda del juego ha terminado.
     * @param string  $gameId         ID del juego.
     * @param string  $type           Tipo de operación.
     *
     * @return string JSON con el balance actualizado.
     * @throws Exception Si ocurre un error durante la operación.
     */
    public function Rollback($rollbackAmount = "", $roundId, $transactionId, $datos, $gameRoundEnd, $gameId, $type)
    {
        $this->type = 'Rollback';

        try {
            $Proveedor = new Proveedor("", "KAGAMING");

            try {
                $UsuarioMandante = new UsuarioMandante($this->user);
            } catch (Exception $e) {
                throw new Exception("User Invalid", "10018");
            }

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
                    $TransaccionJuego = new TransaccionJuego('', "KAGAMING" . $roundId);
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
                    try {
                        $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
                        $TransaccionJuego = new TransaccionJuego("", "KAGAMING" . $roundId);
                        $trans = $TransaccionJuego->transaccionId . '_' . $Producto->subproveedorId;
                        if ($type == 'credit' || $type == 'play') {
                            $AllowCreditTransaction = true;
                            $trans = $TransaccionJuego->transaccionId . '_C_' . $Producto->subproveedorId;
                        }
                        $TransjuegoLog = new TransjuegoLog("", $TransaccionJuego->transjuegoId, "", $trans);
                        $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);
                        $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());

                        if (strpos($TransjuegoLog->getTipo(), 'DEBIT') !== false || strpos($TransjuegoLog->getTipo(), 'CREDIT') !== false) {
                            $transId = explode("_", $TransjuegoLog->transaccionId);
                            if ($type == 'credit' || $type == 'play') {
                                $transId = $transId[0] . '_' . $transId[1] . '_C';
                            } else {
                                $transId = $transId[0] . '_' . $transId[1];
                            }
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

                    $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false, '', '', '', $AllowCreditTransaction, '', $end);

                    $this->transaccionApi = $responseG->transaccionApi;

                    $Balance = intval(round($responseG->saldo, 2) * 100);

                    $return = array(
                        "balance" => $Balance,
                        "status" => 'success',
                        "statusCode" => 0,
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
     * Método para manejar giros gratis (free spins).
     *
     * @param string $currency Moneda utilizada.
     *
     * @return string JSON con los datos de la operación.
     * @throws Exception Si el token o el usuario están vacíos.
     */
    public function freeSpin($currency)
    {
        try {
            if ($this->token == "" && $this->user == "") {
                throw new Exception("Token vacio", "10011");
            }

            $return = array(
                "status" => 'success',
                "statusCode" => 0,
                "username" => $this->user,
                "currency" => $currency,
                "launchToken" => $this->token,

            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para finalizar una operación.
     *
     * @return string JSON con el estado de la operación.
     */
    public function End()
    {
        try {
            $return = array(
                "status" => 'success',
                "statusCode" => 0
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para convertir errores en respuestas JSON.
     *
     * @param integer $code             Código de error.
     * @param string  $messageProveedor Mensaje del proveedor.
     *
     * @return string JSON con el error convertido.
     */
    public function convertError($code, $messageProveedor)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $response = array();

        $Proveedor = new Proveedor("", "KAGAMING");

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

        $Game = new Game();
        $responseG = $Game->getBalance($UsuarioMandante);

        $Balance = intval(round($responseG->saldo, 2) * 100);

        switch ($code) {
            case 100001:
                $codeProveedor = 2;
                $messageProveedor = "Invalid request";
                break;

            case 100012:
                $codeProveedor = 3;
                $messageProveedor = "Invalid hash";
                break;

            case 26:
                $codeProveedor = 4;
                $messageProveedor = "Wallet not found specific to a licensee, operator, and game";
                break;

            case 10007:
                $codeProveedor = 5;
                $messageProveedor = "Request mismatch";
                break;

            case 20001:
                $codeProveedor = 10;
                $messageProveedor = "Insufficient funds in player game wallet";
                break;

            default:
                $messageProveedor = 1;
                $messageProveedor = "UNKNOWN_ERROR";
                break;
        }

        $respuesta = json_encode(array_merge($response, array(
            "status" => $messageProveedor,
            "balance" => $Balance,
            "bonus" => 0
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
