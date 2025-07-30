<?php

/**
 * Clase Pgsoft
 *
 * Esta clase contiene métodos para la integración con el proveedor PGSOFT.
 * Proporciona funcionalidades como autenticación, consulta de balance, débitos, créditos, y rollbacks.
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
use Backend\dto\Subproveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransaccionApi;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransaccionApiMySqlDAO;

/**
 * Clase Pgsoft
 *
 * Esta clase proporciona métodos para la integración con el proveedor PGSOFT,
 * incluyendo autenticación, consulta de balance, débitos, créditos y rollbacks.
 */
class Pgsoft
{
    /**
     * Nombre de usuario.
     *
     * @var string
     */
    private $user;

    /**
     * Tipo de operación o transacción.
     *
     * @var string
     */
    private $type;

    /**
     * Marca de tiempo de la última actualización.
     *
     * @var integer
     */
    private $time_Up;

    /**
     * Valor previo al ajuste.
     *
     * @var float
     */
    private $before;

    /**
     * Monto ajustado.
     *
     * @var float
     */
    private $adjAmount;

    /**
     * Monto a transferir.
     *
     * @var float
     */
    private $transfer_amount;

    /**
     * Token del usuario.
     *
     * @var string
     */
    private $token;

    /**
     * Identificador único del usuario.
     *
     * @var string
     */
    private $uid;

    /**
     * Objeto de transacción API.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * Datos adicionales de la transacción.
     *
     * @var mixed
     */
    private $data;

    /**
     * Constructor de la clase Pgsoft.
     *
     * @param string $user  Usuario.
     * @param string $token Token del usuario.
     * @param string $uid   Identificador único del usuario.
     */
    public function __construct($user = '', $token = '', $uid = "")
    {
        $this->user = $user;
        $this->token = $token;
        $this->uid = $uid;
    }

    /**
     * Método para autenticar y firmar una solicitud.
     *
     * @param string $secret_key     Clave secreta del proveedor.
     * @param string $operator_token Token del operador.
     *
     * @return string Respuesta en formato JSON.
     */
    public function autchSign($secret_key, $operator_token)
    {
        try {
            $Proveedor = new Proveedor("", "PGSOFT");

            if ($this->token != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (Exception $e) {
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                    $UsuarioMandante = new UsuarioMandante($this->user);
                }
            } else {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                $UsuarioMandante = new UsuarioMandante($this->user);
            }

            try {
                $Producto = new Producto($UsuarioToken->productoId);
                $subproveedorId = $Producto->subproveedorId;
            } catch (Exception $e) {
                $Subproveedor = new Subproveedor("", "PGSOFT");
                $subproveedorId = $Subproveedor->subproveedorId;
            }

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

            if ($Credentials->SECRET_KEY !== $secret_key) {
                $codeError = 1204;
                $Message = 'Invalid secret';
            } elseif ($Credentials->OPERATOR !== $operator_token) {
                $codeError = 1204;
                $Message = 'Invalid operator';
            } else {
                $codeError = 0;
                $Message = '';
            }

            $response = array(
                "data" => null,
                "error" => array(
                    "code" => $codeError,
                    "message" => $Message,
                ),
            );

            return json_encode($response);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para autenticar al usuario.
     *
     * @return string Respuesta en formato JSON con los datos del usuario.
     */
    public function Auth()
    {
        $this->type = 'Auth';
        try {
            $Proveedor = new Proveedor("", "PGSOFT");

            try {
                $UserVal = new UsuarioMandante($this->user);
            } catch (Exception $e) {
                throw new Exception("User Invalid", "10019");
            }
            try {
                $TokenVal = new UsuarioToken($this->token, $Proveedor->getProveedorId());
            } catch (Exception $e) {
                throw new Exception("User Invalid", "10018");
            }

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
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (Exception $e) {
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                    $UsuarioMandante = new UsuarioMandante($this->user);
                }
            } else {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                $UsuarioMandante = new UsuarioMandante($this->user);
            }

            if ($UsuarioToken->getEstado() != 'A') {
                throw new Exception("Token Inactivo", "10030");
            }

            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $return = array(
                "data" => array(
                    "player_name" => $UsuarioMandante->usumandanteId,
                    "nickname" => $responseG->usuario,
                    "currency" => $responseG->moneda,
                ),
                "error" => null
            );

            return json_encode($return, JSON_PRESERVE_ZERO_FRACTION);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para obtener el balance del usuario.
     *
     * Este método consulta el balance actual del usuario asociado al token o nombre de usuario
     * proporcionado. Valida el estado del token y genera una respuesta en formato JSON con
     * el balance y la moneda del usuario.
     *
     * @param string $gameId Identificador del juego.
     *
     * @return string Respuesta en formato JSON con el balance del usuario.
     *
     * @throws Exception Si el usuario o el token no son válidos, o si el token está inactivo.
     */
    public function Balance($gameId)
    {
        $this->type = 'Balance';
        try {
            $Proveedor = new Proveedor("", "PGSOFT");

            try {
                $UserVal = new UsuarioMandante($this->user);
            } catch (Exception $e) {
                throw new Exception("User Invalid", "10019");
            }
            if ($this->token != "") {
                try {
                    $TokenVal = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                } catch (Exception $e) {
                    throw new Exception("User Invalid", "10018");
                }
            }

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
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (Exception $e) {
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                    $UsuarioMandante = new UsuarioMandante($this->user);
                }
            } else {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                $UsuarioMandante = new UsuarioMandante($this->user);
            }

            if ($UsuarioToken->getEstado() != 'A') {
                throw new Exception("Token Inactivo", "10030");
            }

            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);

            $Balance = round($responseG->saldo, 2);

            $microtime = microtime(true);
            $time = round($microtime * 1000);

            $return = array(
                "data" => array(
                    "currency_code" => $responseG->moneda,
                    "balance_amount" => $Balance,
                    "updated_time" => $time,
                ),
                "error" => null
            );

            return json_encode($return, JSON_PRESERVE_ZERO_FRACTION);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para debitar un monto del balance del usuario.
     *
     * Este método realiza una operación de débito en el balance del usuario asociado al token o nombre de usuario
     * proporcionado. Valida el estado del token, el balance disponible y la moneda del usuario antes de realizar
     * la transacción. Genera una respuesta en formato JSON con el balance actualizado y otros datos relevantes.
     *
     * @param string  $gameId          Identificador del juego.
     * @param float   $debitAmount     Monto a debitar.
     * @param string  $roundId         Identificador de la ronda de juego.
     * @param string  $transactionId   Identificador único de la transacción.
     * @param mixed   $datos           Datos adicionales de la transacción.
     * @param boolean $gameRoundEnd    Indica si la ronda de juego ha finalizado.
     * @param string  $currency        Moneda en la que se realiza la transacción.
     * @param string  $timeUp          Marca de tiempo de la transacción (opcional).
     * @param float   $adjAmount       Monto ajustado (opcional).
     * @param float   $before          Balance previo al ajuste (opcional).
     * @param string  $type            Tipo de operación (opcional).
     * @param float   $transfer_amount Monto real transferido (opcional).
     *
     * @return string Respuesta en formato JSON con el balance actualizado o un error en caso de fallo.
     *
     * @throws Exception Si el token o el usuario no son válidos, si el balance es insuficiente, o si la moneda no coincide.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $gameRoundEnd, $currency, $timeUp = '', $adjAmount = 0, $before = 0, $type = "", $transfer_amount = 0, $isFreeSpin = false)
    {
        $this->type = 'Debit';
        if ($type != "") {
            $this->type = $type;
        }
        $this->time_Up = $timeUp;
        $this->before = $before;
        $this->adjAmount = $adjAmount;
        $this->transfer_amount = $transfer_amount;

        try {
            if ($this->token == "" && $this->user == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado PGSOFT */
            $Proveedor = new Proveedor("", "PGSOFT");

            try {
                $UserVal = new UsuarioMandante($this->user);
            } catch (Exception $e) {
                throw new Exception("User Invalid", "10019");
            }

            if ($this->token != "") {
                try {
                    $TokenVal = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                } catch (Exception $e) {
                    throw new Exception("User Invalid", "10018");
                }
            }

            if ($this->token != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (Exception $e) {
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                    $UsuarioMandante = new UsuarioMandante($this->user);
                }
            } else {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                $UsuarioMandante = new UsuarioMandante($this->user);
            }

            $Game = new Game();
            $resp = $Game->getBalance($UsuarioMandante);

            $Balance_old = round($resp->saldo, 2);
            if (intval($debitAmount) > intval($Balance_old)) {
                throw new Exception("Balance invalid", "10002");
            }

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
            $this->transaccionApi->setIdentificador("PGSOFT" . $roundId);

            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $isFreeSpin, [], true, $gameRoundEnd);
            $this->transaccionApi = $responseG->transaccionApi;

            $resp = $Game->getBalance($UsuarioMandante);
            $Balance = round($resp->saldo, 2);

            $microtime = microtime(true);
            $time = round($microtime * 1000);

            $return = array(
                "data" => array(
                    "currency_code" => $responseG->moneda,
                    "balance_amount" => $Balance,
                    "real_transfer_amount" => $transfer_amount,
                    "updated_time" => $timeUp,
                ),
                "error" => null
            );

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta(json_encode($return));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return json_encode($return, JSON_PRESERVE_ZERO_FRACTION);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para acreditar un monto al balance del usuario.
     *
     * Este método realiza una operación de crédito en el balance del usuario asociado al token o nombre de usuario
     * proporcionado. Valida el estado del token, la moneda del usuario y otros parámetros antes de realizar
     * la transacción. Genera una respuesta en formato JSON con el balance actualizado y otros datos relevantes.
     *
     * @param string  $gameId          Identificador del juego (opcional).
     * @param float   $creditAmount    Monto a acreditar.
     * @param string  $roundId         Identificador de la ronda de juego.
     * @param string  $transactionId   Identificador único de la transacción.
     * @param mixed   $datos           Datos adicionales de la transacción.
     * @param boolean $isBonus         Indica si el crédito es un bono (opcional).
     * @param boolean $gameRoundEnd    Indica si la ronda de juego ha finalizado.
     * @param string  $currency        Moneda en la que se realiza la transacción.
     * @param string  $timeUp          Marca de tiempo de la transacción (opcional).
     * @param boolean $adjust          Indica si se trata de un ajuste (opcional).
     * @param float   $amount          Monto ajustado (opcional).
     * @param float   $before          Balance previo al ajuste (opcional).
     * @param string  $type            Tipo de operación (opcional).
     * @param float   $transfer_amount Monto real transferido (opcional).
     *
     * @return string Respuesta en formato JSON con el balance actualizado o un error en caso de fallo.
     *
     * @throws Exception Si el token o el usuario no son válidos, si la moneda no coincide, o si la transacción no existe.
     */
    public function Credit($gameId = "", $creditAmount, $roundId, $transactionId, $datos, $gameRoundEnd, $currency, $timeUp = '', $adjust = false, $amount = '', $before = '', $type = "", $transfer_amount = 0, $isFreeSpin = false)
    {
        $this->type = 'Credit';
        if ($type != "") {
            $this->type = $type;
        }
        $this->time_Up = $timeUp;
        $this->before = $before;
        $this->adjAmount = $amount;
        $this->transfer_amount = $transfer_amount;

        try {
            if ($this->token == "" && $this->user == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado PGSOFT */
            $Proveedor = new Proveedor("", "PGSOFT");

            try {
                $UserVal = new UsuarioMandante($this->user);
            } catch (Exception $e) {
                throw new Exception("User Invalid", "10019");
            }

            if ($this->token != "") {
                try {
                    $TokenVal = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                } catch (Exception $e) {
                    throw new Exception("User Invalid", "10018");
                }
            }

            if ($this->token != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (Exception $e) {
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                    $UsuarioMandante = new UsuarioMandante($this->user);
                }
            } else {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                $UsuarioMandante = new UsuarioMandante($this->user);
            }

            $Game = new Game();
            $resp = $Game->autenticate($UsuarioMandante);
            if ($resp->moneda != $currency) {
                throw new Exception("Invalid currency", "10029");
            }

            try {
                $TransaccionJuego = new TransaccionJuego("", "PGSOFT" . $roundId);
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
            $this->transaccionApi->setIdentificador("PGSOFT" . $roundId);

            /*  Obtenemos el producto con el $TransaccionJuego->productoId */
            $ProductoMandante = new ProductoMandante("", "", $TransaccionJuego->productoId);
            $Producto = new Producto($ProductoMandante->productoId);

            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $gameRoundEnd, false, $isFreeSpin, false);
            $this->transaccionApi = $responseG->transaccionApi;

            $resp = $Game->getBalance($UsuarioMandante);
            $Balance = round($resp->saldo, 2);

            if ($adjust) {
                $return = array(
                    "data" => array(
                        "real_transfer_amount" => $transfer_amount,
                        "adjust_amount" => $amount,
                        "balance_before" => $before,
                        "balance_after" => $Balance,
                        "updated_time" => $timeUp,
                    ),
                    "error" => null
                );
            } else {
                $return = array(
                    "data" => array(
                        "currency_code" => $responseG->moneda,
                        "balance_amount" => $Balance,
                        "real_transfer_amount" => $transfer_amount,
                        "updated_time" => $timeUp,
                    ),
                    "error" => null
                );
            }

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta(json_encode($return));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return json_encode($return, JSON_PRESERVE_ZERO_FRACTION);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para realizar un rollback de una transacción.
     *
     * Este método revierte una transacción previamente realizada, validando el estado del token,
     * el usuario y la transacción asociada. Genera una respuesta en formato JSON con el balance
     * actualizado del usuario o un error en caso de fallo.
     *
     * @param float   $rollbackAmount Monto a revertir.
     * @param string  $roundId        Identificador de la ronda de juego.
     * @param string  $transactionId  Identificador único de la transacción.
     * @param string  $player         Identificador del jugador.
     * @param mixed   $datos          Datos adicionales de la transacción.
     * @param boolean $gameRoundEnd   Indica si la ronda de juego ha finalizado.
     * @param string  $gameId         Identificador del juego.
     *
     * @return string Respuesta en formato JSON con el balance actualizado o un error en caso de fallo.
     *
     * @throws Exception Si el token, el usuario o la transacción no son válidos.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos, $gameRoundEnd, $gameId)
    {
        $this->type = 'Rollback';
        try {
            /*  Obtenemos el Proveedor con el abreviado PGSOFT */
            $Proveedor = new Proveedor("", "PGSOFT");

            if ($this->token != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (Exception $e) {
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                    $UsuarioMandante = new UsuarioMandante($this->user);
                }
            } else {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                $UsuarioMandante = new UsuarioMandante($this->user);
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
                $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
                $TransjuegoLog = new TransjuegoLog("", "", "", $transactionId . '_' . $Producto->getSubproveedorId(), $Producto->getSubproveedorId());
                $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                //$this->transaccionApi->setIdentificador($TransaccionJuego->getTicketId());

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

            $resp = $Game->getBalance($UsuarioMandante);
            $Balance = round($resp->saldo, 2);

            $microtime = microtime(true);
            $time = round($microtime * 1000);

            $return = array(
                "data" => array(
                    "currency_code" => $responseG->moneda,
                    "balance_amount" => $Balance,
                    "updated_time" => $time,
                ),
                "error" => null
            );

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta(json_encode($return));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return json_encode($return, JSON_PRESERVE_ZERO_FRACTION);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Convierte un error en una respuesta JSON adecuada.
     *
     * Este método toma un código de error y un mensaje, y los convierte en una
     * respuesta JSON que incluye información adicional sobre el error, como
     * el código y el mensaje del proveedor, así como datos relacionados con
     * el balance del usuario si corresponde.
     *
     * @param integer $code    Código de error.
     * @param string  $message Mensaje de error.
     *
     * @return string Respuesta en formato JSON con los detalles del error.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";
        $consu = true;

        $response = array();

        $Proveedor = new Proveedor("", "PGSOFT");

        if ($this->token != "") {
            try {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } catch (Exception $e) {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                $UsuarioMandante = new UsuarioMandante($this->user);
            }
        } else {
            try {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                $UsuarioMandante = new UsuarioMandante($this->user);
            } catch (Exception $e) {
                $code = 10019;
                $consu = false;
            }
        }

        if ($consu) {
            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);
            $Balance = round($responseG->saldo, 2);
        }

        switch ($code) {
            case 10002:
                $codeProveedor = 3202;
                $messageProveedor = "Not enough cash balance to bet";
                break;

            case 10003:
                $codeProveedor = 3202;
                $messageProveedor = "Not enough cash balance to bet";
                break;

            case 10011:
                $codeProveedor = 3005;
                $messageProveedor = "Player wallet does not exist";
                break;

            case 21:
                $codeProveedor = 3005;
                $messageProveedor = "Player wallet does not exist";
                break;

            case 10030:
                $codeProveedor = 3005;
                $messageProveedor = "Player wallet does not exist";
                break;

            case 10013:
                $codeProveedor = 3004;
                $messageProveedor = "Player does not exist";
                break;

            case 10005:
                $codeProveedor = 3021;
                $messageProveedor = "Bet does not exist";
                break;

            case 22:
                $codeProveedor = 3004;
                $messageProveedor = "Player does not exist";
                break;

            case 50030:
                $codeProveedor = 1200;
                $messageProveedor = "Internal server error";
                break;

            case 20001:
                $codeProveedor = 3202;
                $messageProveedor = "Not enough cash balance to bet";
                break;

            case 0:
                $codeProveedor = 1200;
                $messageProveedor = "Internal server error";
                break;

            case 27:
                $codeProveedor = 1200;
                $messageProveedor = "Internal server error";
                break;

            case 10041:
                $codeProveedor = 1200;
                $messageProveedor = "Internal server error";
                break;

            case 28:
                $codeProveedor = 1200;
                $messageProveedor = "Internal server error";
                break;

            case 29:
                $codeProveedor = 1200;
                $messageProveedor = "Internal server error";
                break;

            case 10001:
                if ($this->type == 'Debit' || $this->type == 'Credit') {
                    $return = array(
                        "data" => array(
                            "currency_code" => $UsuarioMandante->moneda,
                            "balance_amount" => $Balance,
                            "real_transfer_amount" => $this->transfer_amount,
                            "updated_time" => $this->time_Up,
                        ),
                        "error" => null
                    );
                } elseif ($this->type == 'Adjustment') {
                    $return = array(
                        "data" => array(
                            "real_transfer_amount" => $this->transfer_amount,
                            "adjust_amount" => $this->adjAmount,
                            "balance_before" => $this->before,
                            "balance_after" => $Balance,
                            "updated_time" => $this->time_Up,
                        ),
                        "error" => null
                    );
                } else {
                    $codeProveedor = 3032;
                    $messageProveedor = "Bet already existed";
                }
                break;

            case 10016:
                $codeProveedor = 3033;
                $messageProveedor = "Bet failed";
                break;

            case 10017:
                $codeProveedor = 3033;
                $messageProveedor = "Bet failed";
                break;

            case 10018:
                $codeProveedor = 1300;
                $messageProveedor = "Invalid player session";
                break;

            case 10019:
                $codeProveedor = 1305;
                $messageProveedor = "Invalid player";
                break;

            case 10004:
                $codeProveedor = 3033;
                $messageProveedor = "Bet failed";
                break;

            case 10014:
                $codeProveedor = 3033;
                $messageProveedor = "Bet failed";
                break;

            case 3073:
                $codeProveedor = 3073;
                $messageProveedor = "Invalid multiplier";
                break;

            case 3107:
                $codeProveedor = 3107;
                $messageProveedor = "Invalid real transfer amount";
                break;

            default:
                $codeProveedor = 1200;
                $messageProveedor = "Internal server error";
                break;
        }

        if ($return != '') {
            $respuesta = json_encode($return, JSON_PRESERVE_ZERO_FRACTION);
        } else {
            $respuesta = json_encode(array_merge($response, array(
                "data" => null,
                "error" => array(
                    "code" => strval($codeProveedor),
                    "message" => $messageProveedor,
                ),
            )), JSON_PRESERVE_ZERO_FRACTION);
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
