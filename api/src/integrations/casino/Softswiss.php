<?php

/**
 * Clase `Softswiss` para la integración con el proveedor de juegos Softswiss.
 *
 * Este archivo contiene la implementación de la clase `Softswiss`, que maneja
 * las operaciones relacionadas con la integración de juegos, como autenticación,
 * débitos, créditos, rollbacks y manejo de errores.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;

use Backend\utils\RedisConnectionTrait;
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
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransaccionApiMySqlDAO;

/**
 * Clase `Softswiss`.
 *
 * Esta clase maneja la integración con el proveedor de juegos Softswiss,
 * proporcionando métodos para autenticación, débitos, créditos, rollbacks
 * y manejo de errores relacionados con las transacciones de juegos.
 */
class Softswiss
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
    private $userId;

    /**
     * Firma de autenticación.
     *
     * @var string
     */
    private $sign;

    /**
     * Hash original para validación.
     *
     * @var string
     */
    private $hashOriginal;

    /**
     * Objeto de transacción API.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * Datos de la solicitud.
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
     * Constructor de la clase `Softswiss`.
     *
     * @param string $token        Token de autenticación.
     * @param string $userId       ID del usuario.
     * @param string $hashOriginal Hash original para validación.
     * @param string $sign         Firma de autenticación.
     */
    public function __construct($token = "", $userId = "", $hashOriginal = "", $sign = "")
    {
        if ( ! defined('JSON_PRESERVE_ZERO_FRACTION')) {
            define('JSON_PRESERVE_ZERO_FRACTION', 1024);
        }

        $this->token = $token;
        $this->userId = $userId;
        $this->hashOriginal = $hashOriginal;
        $this->sign = $sign;

        if ($this->sign != $this->hashOriginal) {
            $this->errorHash = true;
        }
    }

    /**
     * Genera una firma de autenticación (autchSign).
     *
     * @param string $data   Datos a firmar.
     * @param string $userId ID del usuario.
     * @param string $gameId ID del juego.
     *
     * @return string Firma generada.
     */
    public function autchSign($data, $userId, $gameId)
    {
        try {
            $Proveedor = new Proveedor("", "SOFTSWISS");
            $UsuarioMandante = new UsuarioMandante($userId);
            try {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $userId, "", "", "", "", "A");
            } catch (Exception $e) {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $userId, "", "", "", "", "I");
            }

            try {
                $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
            } catch (Exception $e) {
                $Producto = new Producto($UsuarioToken->productoId);
            }

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $AutchSign = hash_hmac('sha256', $data, $credentials->AUTH_TOKEN);

            return $AutchSign;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }




    /**
     * Autentica un usuario con el token proporcionado.
     *
     * @param string  $token     Token de autenticación.
     * @param boolean $isFree    Indica si es una operación gratuita.
     * @param boolean $isErrHTTP Indica si se debe manejar un error HTTP.
     *
     * @return array Respuesta de autenticación.
     */
    public function Auth($token, $isFree = false, $isErrHTTP = false)
    {
        if ($isFree == false) {
            if ($this->errorHash) {
                try {
                    throw new Exception("Forbidden.", "10030");
                } catch (Exception $e) {
                    $re_ = $this->convertError($e->getCode(), $e->getMessage());
                    if ($re_['code'] == 403 && $isErrHTTP == true) {
                        $array = array(
                            'code' => 'invalid_argument',
                            'meta' => array('api_code' => 403)
                        );
                        return $array;
                    } else {
                        return $this->convertError($e->getCode(), $e->getMessage());
                    }
                }
            }
        }

        $this->method = 'authenticate';
        try {
            if ($this->token == "" && $this->userId == "") {
                throw new Exception("Token vacio", "10011");
            }

            $Proveedor = new Proveedor("", "SOFTSWISS");

            if ($this->token != "") {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                $UsuarioMandante = new UsuarioMandante($this->userId);
            }

            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $array = array(
                "balance" => intval($responseG->saldo * 100)
            );

            return $array;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance de un usuario.
     *
     * @param string $transactionId ID de la transacción.
     *
     * @return array Respuesta con el balance.
     */
    public function getBalance($transactionId)
    {
        $this->method = 'authenticate';
        try {
            if ($this->token == "" && $this->userId == "") {
                throw new Exception("Token vacio", "10011");
            }

            $Proveedor = new Proveedor("", "SOFTSWISS");

            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->userId);
            }

            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $microtime = microtime(true);
            $microseconds = sprintf("%06d", ($microtime - floor($microtime)) * 1000000);
            $fechaActual = gmdate('Y-m-d\TH:i:s', time()) . '.' . $microseconds . 'Z';

            $array = array(
                "action_id" => $transactionId,
                "tx_id" => base64_encode($transactionId),
                "processed_at" => $fechaActual,
            );

            return $array;
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
     * @param mixed   $datos         Datos adicionales.
     * @param boolean $gameRoundEnd  Indica si la ronda del juego terminó.
     * @param string  $currency      Moneda utilizada.
     * @param boolean $isFree        Indica si es una operación gratuita.
     *
     * @return array Respuesta del débito.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $gameRoundEnd, $currency, $isFree = false)
    {
        if ($isFree == false) {
            if ($this->errorHash) {
                try {
                    throw new Exception("Forbidden.", "10030");
                } catch (Exception $e) {
                    return $this->convertError($e->getCode(), $e->getMessage());
                }
            }
        }

        $this->method = 'debit';
        $this->data = $datos;

        try {
            if ($this->token == "" && $this->userId == "") {
                throw new Exception("Token vacio", "10011");
            }

            $Proveedor = new Proveedor("", "SOFTSWISS");

            if ($this->token != "") {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                $UsuarioMandante = new UsuarioMandante($this->userId);
            }
            if ($UsuarioMandante->usumandanteId == 9179402) {
                exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" .$transactionId.' ##1# '. date('Y-m-d H:i:s')  . "' '#provisional' > /dev/null & ");
            }
            $Game = new Game();
            if ($UsuarioMandante->moneda != $currency) {
                throw new Exception("Invalid currency", "10029");
            }


            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);
            $this->transaccionApi->setIdentificador("Softswiss" . $roundId);

            if ($UsuarioMandante->usumandanteId == 9179402) {
                exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" .$transactionId.' ##2# '. date('Y-m-d H:i:s')  . "' '#provisional' > /dev/null & ");
            }

            $isfreeSpin = false;
            if (floatval($debitAmount) == 0) {
                $isfreeSpin = true;
            }

            if ($gameRoundEnd == true) {
                $End = true;
            } else {
                $End = false;
            }

            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $isfreeSpin, [], true, $End);
            if ($UsuarioMandante->usumandanteId == 9179402) {
                exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" .$transactionId.' ##3# '. date('Y-m-d H:i:s')  . "' '#provisional' > /dev/null & ");
            }
            $this->transaccionApi = $responseG->transaccionApi;

            $microtime = microtime(true);
            $microseconds = sprintf("%06d", ($microtime - floor($microtime)) * 1000000);
            $fechaActual = gmdate('Y-m-d\TH:i:s', time()) . '.' . $microseconds . 'Z';

            $array = array(
                "action_id" => $transactionId,
                "tx_id" => $responseG->transaccionId,
                "processed_at" => $fechaActual,
            );

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setTValue($this->transaccionApi->getTValue().'##'.date('Y-m-d H:i:s'));

            $this->transaccionApi->setRespuesta(json_encode($array));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();
            if ($UsuarioMandante->usumandanteId == 9179402) {
                exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" .$transactionId.' ##4# '. date('Y-m-d H:i:s')  . "' '#provisional' > /dev/null & ");
            }
            return $array;
        } catch (Exception $e) {
            $re_ = $this->convertError($e->getCode(), $e->getMessage());
            if ($re_['code'] == '4001') {
                $Proveedor = new Proveedor("", "SOFTSWISS");
                $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
                $TransjuegoLog = new TransjuegoLog("", "", "", $transactionId . '_' . $Producto->subproveedorId, $Producto->subproveedorId);
                $microtime = microtime(true);
                $microseconds = sprintf("%06d", ($microtime - floor($microtime)) * 1000000);
                $fechaActual = gmdate('Y-m-d\TH:i:s', time()) . '.' . $microseconds . 'Z';
                $array = array(
                    "action_id" => $transactionId,
                    "tx_id" => $TransjuegoLog->transjuegologId,
                    "processed_at" => $fechaActual,
                );
                return $array;
            } else {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }
    }

    /**
     * Realiza un rollback de una transacción.
     *
     * @param float   $rollbackAmount Monto a revertir.
     * @param string  $roundId        ID de la ronda.
     * @param string  $transactionId  ID de la transacción.
     * @param mixed   $datos          Datos adicionales.
     * @param boolean $gameRoundEnd   Indica si la ronda del juego terminó.
     * @param string  $OrTId          ID original de la transacción.
     *
     * @return array Respuesta del rollback.
     */

    public function Rollback($rollbackAmount, $roundId, $transactionId, $datos, $gameRoundEnd, $OrTId, $gameId)
    {
        if ($this->errorHash) {
            try {
                throw new Exception("Forbidden.", "10030");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }

        $this->method = 'Rollback';
        $this->data = $datos;

        try {
            $Proveedor = new Proveedor("", "SOFTSWISS");

            if ($this->token != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (Exception $e) {
                    $UsuarioMandante = new UsuarioMandante($this->userId);
                }
            } else {
                $UsuarioMandante = new UsuarioMandante($this->userId);
            }

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $OrTId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue($datos);
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($rollbackAmount);

            try {
                $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
            } catch (Exception $e) {
                $Producto = new Producto($UsuarioToken->productoId);
            }

            try {
                $TransjuegoLog = new TransjuegoLog("", "", "", $OrTId . '_' . $Producto->subproveedorId, $Producto->subproveedorId);
                $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());

                if (strpos($TransjuegoLog->getTipo(), 'DEBIT') !== false) {
                    $this->transaccionApi->setIdentificador($TransaccionJuego->getTicketId());
                    $AllowCreditTransaction = false;
                } else {
                    if (strpos($TransjuegoLog->getTipo(), 'CREDIT') !== false) {
                        $this->transaccionApi->setIdentificador($TransaccionJuego->getTicketId());
                        $AllowCreditTransaction = true;
                    } else {
                        throw new Exception("Transaccion no es Debit", "10006");
                    }
                }
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "5050");
            }

            $Game = new Game();

            if ($gameRoundEnd == true) {
                $end = 'I';
            } else {
                $end = 'A';
            }

            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false, '', '', '', $AllowCreditTransaction, '', $end);

            $this->transaccionApi = $responseG->transaccionApi;

            $microtime = microtime(true);
            $microseconds = sprintf("%06d", ($microtime - floor($microtime)) * 1000000);
            $fechaActual = gmdate('Y-m-d\TH:i:s', time()) . '.' . $microseconds . 'Z';

            $array = array(
                "action_id" => $transactionId,
                "tx_id" => $responseG->transaccionId,
                "processed_at" => $fechaActual,
            );

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta(json_encode($array));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $array;
        } catch (Exception $e) {
            $re_ = $this->convertError($e->getCode(), $e->getMessage());
            if ($re_['code'] == '4001') {
                $Proveedor = new Proveedor("", "SOFTSWISS");
                $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
                $TransjuegoLog = new TransjuegoLog("", "", "", $OrTId . '_' . $Producto->subproveedorId, $Producto->subproveedorId);
                $microtime = microtime(true);
                $microseconds = sprintf("%06d", ($microtime - floor($microtime)) * 1000000);
                $fechaActual = gmdate('Y-m-d\TH:i:s', time()) . '.' . $microseconds . 'Z';
                $array = array(
                    "action_id" => $transactionId,
                    "tx_id" => $TransjuegoLog->transjuegologId,
                    "processed_at" => $fechaActual,
                );
                return $array;
            } else {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }
    }

    /**
     * Realiza un crédito en el balance del usuario.
     *
     * @param string  $gameId        ID del juego.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param mixed   $datos         Datos adicionales.
     * @param boolean $isBonus       Indica si es un bono.
     * @param boolean $gameRoundEnd  Indica si la ronda del juego terminó.
     * @param string  $currency      Moneda utilizada.
     * @param boolean $isFree        Indica si es una operación gratuita.
     *
     * @return array Respuesta del crédito.
     */
    public function Credit($gameId = "", $creditAmount, $roundId, $transactionId, $datos, $isBonus = false, $gameRoundEnd, $currency, $isFree = false)
    {
        if ($isFree == false) {
            if ($this->errorHash) {
                try {
                    throw new Exception("Forbidden.", "10030");
                } catch (Exception $e) {
                    return $this->convertError($e->getCode(), $e->getMessage());
                }
            }
        }

        $this->method = 'credit';
        $this->data = $datos;
        try {
            if ($this->token == "" && $this->userId == "") {
                throw new Exception("Token vacio", "10011");
            }

            $Proveedor = new Proveedor("", "SOFTSWISS");

            if ($this->token != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (Exception $e) {
                    $UsuarioMandante = new UsuarioMandante($this->userId);
                }
            } else {
                $UsuarioMandante = new UsuarioMandante($this->userId);
            }

            $Game = new Game();
            $resp = $Game->autenticate($UsuarioMandante);
            if ($resp->moneda != $currency) {
                throw new Exception("Invalid currency", "10029");
            }

            try {
                $TransaccionJuego = new TransaccionJuego("", "SOFTSWISS" . $roundId);
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10004");
            }

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);
            $this->transaccionApi->setIdentificador("Softswiss" . $roundId);

            $ProductoMandante = new ProductoMandante("", "", $TransaccionJuego->productoId);
            $Producto = new Producto($ProductoMandante->productoId);

            if ($gameRoundEnd == true) {
                $End = true;
            } else {
                $End = false;
            }

            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $End, false, $isBonus, false);

            $this->transaccionApi = $responseG->transaccionApi;

            $microtime = microtime(true);
            $microseconds = sprintf("%06d", ($microtime - floor($microtime)) * 1000000);
            $fechaActual = gmdate('Y-m-d\TH:i:s', time()) . '.' . $microseconds . 'Z';

            $array = array(
                "action_id" => $transactionId,
                "tx_id" => $responseG->transaccionId,
                "processed_at" => $fechaActual,
            );

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta(json_encode($array));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $array;
        } catch (Exception $e) {
            $re_ = $this->convertError($e->getCode(), $e->getMessage());
            if ($re_['code'] == '4001') {
                $Proveedor = new Proveedor("", "SOFTSWISS");
                $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
                $TransjuegoLog = new TransjuegoLog("", "", "", $transactionId . '_' . $Producto->subproveedorId, $Producto->subproveedorId);
                $microtime = microtime(true);
                $microseconds = sprintf("%06d", ($microtime - floor($microtime)) * 1000000);
                $fechaActual = gmdate('Y-m-d\TH:i:s', time()) . '.' . $microseconds . 'Z';
                $array = array(
                    "action_id" => $transactionId,
                    "tx_id" => $TransjuegoLog->transjuegologId,
                    "processed_at" => $fechaActual,
                );
                return $array;
            } else {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }
    }

    /**
     * Convierte un error en un formato de respuesta estándar.
     *
     * @param integer $code    Código del error.
     * @param string  $message Mensaje del error.
     *
     * @return array Respuesta con el error convertido.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $Proveedor = new Proveedor("", "SOFTSWISS");

        if ($this->token != "") {
            try {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } catch (Exception $e) {
                $UsuarioMandante = new UsuarioMandante($this->userId);
            }
        } else {
            $UsuarioMandante = new UsuarioMandante($this->userId);
        }

        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
        $saldo = $Usuario->getBalance();

        $response = array();

        switch ($code) {
            case 10011:
                $codeProveedor = "101";
                $messageProveedor = "Player is invalid.";
                break;

            case 21:
                $codeProveedor = "101";
                $messageProveedor = "Player is invalid.";
                break;

            case 22:
                $codeProveedor = "101";
                $messageProveedor = "Player is invalid.";
                break;

            case 20001:
                $codeProveedor = "100";
                $messageProveedor = "Player has not enough funds to process an action.";
                break;

            case 0:
                $codeProveedor = "500";
                $messageProveedor = "Unknown error.";
                break;

            case 27:
                $codeProveedor = "405";
                $messageProveedor = "Game is not available to your casino.";
                break;

            case 28:
                $codeProveedor = "400";
                $messageProveedor = "Bad request.";
                break;

            case 29:
                $codeProveedor = "400";
                $messageProveedor = "Bad request.";
                break;

            case 10001:
                $codeProveedor = "4001";
                $messageProveedor = "Bad request.";
                break;

            case 10004:
                $codeProveedor = "5151";
                $messageProveedor = "Transaction with Rollback before.";
                break;

            case 10005:
                $codeProveedor = "400";
                $messageProveedor = "Bad request.";
                break;

            case 10014:
                $codeProveedor = "500";
                $messageProveedor = "Unknown error.";
                break;


            case 10010:
                $codeProveedor = "500";
                $messageProveedor = "Unknown error.";
                break;

            case 20002:
                $codeProveedor = "500";
                $messageProveedor = "Unknown error.";
                break;

            case 20003:
                $codeProveedor = "110";
                $messageProveedor = "Player is disabled.";
                break;

            case 10017:
                $codeProveedor = "154";
                $messageProveedor = "Currency is not allowed for the player.";
                break;

            case 10027:
                $codeProveedor = "500";
                $messageProveedor = "Unknown error.";
                break;

            case 10028:
                $codeProveedor = "500";
                $messageProveedor = "Unknown error.";
                break;

            case 10029:
                $codeProveedor = "154";
                $messageProveedor = "Currency is not allowed for the player.";
                break;

            case 10030:
                $codeProveedor = "403";
                $messageProveedor = "Forbidden.";
                break;

            case 5050:
                $codeProveedor = "5050";
                $messageProveedor = "Rollback";
                break;

            default:
                $codeProveedor = "500";
                $messageProveedor = "Unknown error.";
                break;
        }

        if ($codeProveedor != "") {
            $respuesta = array_merge($response, array(
                "code" => intval($codeProveedor),
                "message" => $messageProveedor,
                "balance" => intval($saldo * 100)
            ));
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
