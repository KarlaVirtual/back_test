<?php

/**
 * Clase `Fantasy` para manejar integraciones con el proveedor FANTASY.
 *
 * Este archivo contiene métodos para realizar operaciones como autenticación,
 * consulta de balance, débitos, créditos, y rollbacks en el sistema del proveedor.
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
use Backend\dto\UsuarioPerfil;
use Backend\dto\TransaccionApi;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\dto\TransaccionJuego;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionApiMySqlDAO;

/**
 * Clase `Fantasy`.
 *
 * Esta clase maneja las integraciones con el proveedor FANTASY, proporcionando
 * métodos para realizar operaciones como autenticación, consulta de balance,
 * débitos, créditos y rollbacks.
 */
class Fantasy
{
    /**
     * Usuario asociado a la transacción.
     *
     * @var string
     */
    private $user;

    /**
     * ID de la transacción.
     *
     * @var string
     */
    private $transId;

    /**
     * Identificador único de usuario.
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
     * Tipo de operación actual.
     *
     * @var string
     */
    private $type;

    /**
     * Clave simétrica para operaciones de hash.
     *
     * @var string
     */
    private $symmetricKey = '';

    /**
     * Constructor de la clase `Fantasy`.
     *
     * Inicializa el usuario, el ID de transacción y la clave simétrica
     * dependiendo del entorno (desarrollo o producción).
     *
     * @param string $user    Usuario asociado.
     * @param string $transId ID de la transacción.
     */
    public function __construct($user = '', $transId = "")
    {
        $this->user = $user;
        $this->transId = $transId;

        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->symmetricKey = '4A73E239-BB29-490F-87AE-459CB9C40DD1';
        } else {
            $this->symmetricKey = 'b32a00fe-d091-400a-bb2d-14d06e9475d5';
        }
    }

    /**
     * Calcula un hash HMAC-SHA256 para un mensaje dado.
     *
     * @param string $messageTransactionId ID de la transacción del mensaje.
     *
     * @return string Hash calculado en formato hexadecimal.
     */
    public function computeHash($messageTransactionId)
    {
        $transactionBytes = utf8_encode($messageTransactionId);
        $key = utf8_encode($this->symmetricKey);

        $hash = hash_hmac('sha256', $transactionBytes, $key, true);
        $hashHex = bin2hex($hash);

        return strtolower($hashHex);
    }

    /**
     * Realiza la autenticación del usuario con el proveedor FANTASY.
     *
     * @param string $currency Moneda utilizada en la transacción.
     *
     * @return string Respuesta en formato JSON con el estado de la autenticación.
     * @throws Exception Si ocurre un error durante la autenticación.
     */
    public function Auth($currency)
    {
        $this->type = 'Auth';
        try {
            $Proveedor = new Proveedor("", "FANTASY");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);

            if ($this->user == "") {
                throw new Exception("Token vacio", "10011");
            }

            $UsuarioToken = new UsuarioToken();
            $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
            $UsuarioToken->setCookie('0');
            $UsuarioToken->setRequestId('0');
            $UsuarioToken->setUsucreaId(0);
            $UsuarioToken->setUsumodifId(0);
            $UsuarioToken->setUsuarioId($this->user);
            $UsuarioToken->setToken($UsuarioToken->createToken());
            $UsuarioToken->setSaldo(0);
            $UsuarioToken->setEstado('A');
            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
            $UsuarioTokenMySqlDAO->insert($UsuarioToken);
            $UsuarioTokenMySqlDAO->getTransaction()->commit();

            try {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } catch (Exception $e) {
                throw new Exception("User Invalid", "10018");
            }

            $Mandante = new Mandante($UsuarioMandante->mandante);

            if ($UsuarioToken->getEstado() != 'A') {
                throw new Exception("Token Inactivo", "10030");
            }

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);

            $Balance = floatval(number_format(round($Usuario->getBalance(), 2), 2, '.', ''));

            $key = $this->computeHash('7663ec73-ff7b-42d0-be94-273c862b426d');

            $return = array(
                "status" => 'OK',
                "sid" => $UsuarioToken->getToken(),
                "uuid" => $key
            );

            return json_encode($return);
        } catch (Exception $e) {
            $log = "";
            $log = $log . "\r\n" . "--------------Response-Error-500----------" . "\r\n";
            $log = $log . json_encode($e->getCode());
            $log = $log . json_encode($e->getMessage());
            $log = $log . "\r\n" . "---------------------------------" . "\r\n";
            //Save string to log, use FILE_APPEND to append.
            $Path = realpath('/home/backend/public_html/api/integrations/casino/fantasy/api');
            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Consulta el balance del usuario.
     *
     * @param string $gameId   ID del juego.
     * @param string $currency Moneda utilizada en la transacción.
     *
     * @return string Respuesta en formato JSON con el balance del usuario.
     * @throws Exception Si ocurre un error durante la consulta.
     */
    public function Balance($gameId, $currency)
    {
        $this->type = 'Balance';
        try {
            $Proveedor = new Proveedor("", "FANTASY");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);

            if ($this->user == "") {
                throw new Exception("Token vacio", "10011");
            }

            try {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } catch (Exception $e) {
                throw new Exception("User Invalid", "10018");
            }

            if ($UsuarioToken->getEstado() != 'A') {
                throw new Exception("Token Inactivo", "10030");
            }

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $Mandante = new Mandante($UsuarioMandante->mandante);

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);

            $Balance = floatval(number_format(round($Usuario->getBalance(), 2), 2, '.', ''));

            $return = array(
                "status" => 'OK',
                "balance" => $Balance,
                "bonus" => 0,
                "uuid" => $this->uid
            );

            return json_encode($return);
        } catch (Exception $e) {
            $log = "";
            $log = $log . "\r\n" . "--------------Response-Error-500----------" . "\r\n";
            $log = $log . json_encode($e->getCode());
            $log = $log . json_encode($e->getMessage());
            $log = $log . "\r\n" . "---------------------------------" . "\r\n";
            $Path = realpath('/home/backend/public_html/api/integrations/casino/fantasy/api');
            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

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
     * @param array   $datos         Datos adicionales de la transacción.
     * @param boolean $gameRoundEnd  Indica si la ronda del juego ha terminado.
     * @param string  $type          Tipo de operación (opcional).
     *
     * @return string Respuesta en formato JSON con el resultado del débito.
     * @throws Exception Si ocurre un error durante el débito.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $gameRoundEnd, $type = '')
    {
        if ($type != '') {
            $this->type = $type;
        } else {
            $this->type = 'Debit';
        }

        try {
            if ($this->user == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado FANTASY */
            $Proveedor = new Proveedor("", "FANTASY");

            try {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } catch (Exception $e) {
                throw new Exception("User Invalid", "10018");
            }

            $Game = new Game();

            /*  Obtenemos el producto con el gameId  */
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            $isRollback = false;
            try {
                $TransApi = new TransaccionApi("", "ROLLBACK" . $transactionId, $Proveedor->getProveedorId(), 'ERROR');
                if ($TransApi->tipo == 'RROLLBACK') {
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
                $this->transaccionApi->setIdentificador("FANTASY" . $roundId);

                $isfreeSpin = false;
                if (floatval($debitAmount) == 0) {
                    $isfreeSpin = true;
                }

                if ($gameRoundEnd == true) {
                    $End = true;
                } else {
                    $End = false;
                }

                $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $isfreeSpin, [], '', $End);

                $this->transaccionApi = $responseG->transaccionApi;

                $Balance = floatval(number_format(round($responseG->saldo, 2), 2, '.', ''));

                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                $return = array(
                    "isSuccess" => true,
                    "extTransactionId" => $this->transId,
                    "message?" => null,
                    "balance" => $Balance
                );

                /*  Guardamos la Transaccion Api necesaria de estado OK   */
                $this->transaccionApi->setRespuesta($return);
                $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                $TransaccionApiMySqlDAO->update($this->transaccionApi);
                $TransaccionApiMySqlDAO->getTransaction()->commit();

                return json_encode($return);
            }
        } catch (Exception $e) {
            $log = "";
            $log = $log . "\r\n" . "--------------Response-Error-500----------" . "\r\n";
            $log = $log . json_encode($e->getCode());
            $log = $log . json_encode($e->getMessage());
            $log = $log . "\r\n" . "---------------------------------" . "\r\n";
            $Path = realpath('/home/backend/public_html/api/integrations/casino/fantasy/api');
            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un crédito en la cuenta del usuario.
     *
     * @param string  $gameId        ID del juego (opcional).
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param array   $datos         Datos adicionales de la transacción.
     * @param boolean $isBonus       Indica si el crédito es un bono.
     * @param boolean $gameRoundEnd  Indica si la ronda del juego ha terminado.
     *
     * @return string Respuesta en formato JSON con el resultado del crédito.
     * @throws Exception Si ocurre un error durante el crédito.
     */
    public function Credit($gameId = "", $creditAmount, $roundId, $transactionId, $datos, $isBonus = false, $gameRoundEnd)
    {
        $this->type = 'Credit';
        try {
            if ($this->user == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado FANTASY */
            $Proveedor = new Proveedor("", "FANTASY");

            try {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } catch (Exception $e) {
                throw new Exception("User Invalid", "10018");
            }

            $Game = new Game();

            $isRollback = false;
            try {
                $TransaccionJuego = new TransaccionJuego("", "FANTASY" . $roundId);
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
                    $TransaccionJuego = new TransaccionJuego("", "FANTASY" . $roundId);
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
                $this->transaccionApi->setIdentificador("FANTASY" . $roundId);

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
                $Balance = floatval(number_format(round($responseG->saldo, 2), 2, '.', ''));

                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                $return = array(
                    "isSuccess" => true,
                    "extTransactionId" => $this->transId,
                    "message?" => null,
                    "balance" => $Balance
                );

                /*  Guardamos la Transaccion Api necesaria de estado OK   */
                $this->transaccionApi->setRespuesta($return);
                $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                $TransaccionApiMySqlDAO->update($this->transaccionApi);
                $TransaccionApiMySqlDAO->getTransaction()->commit();

                return json_encode($return);
            }
        } catch (Exception $e) {
            $log = "";
            $log = $log . "\r\n" . "--------------Response-Error-500----------" . "\r\n";
            $log = $log . json_encode($e->getCode());
            $log = $log . json_encode($e->getMessage());
            $log = $log . "\r\n" . "---------------------------------" . "\r\n";
            $Path = realpath('/home/backend/public_html/api/integrations/casino/fantasy/api');
            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un rollback de una transacción.
     *
     * @param float   $rollbackAmount Monto a revertir.
     * @param string  $roundId        ID de la ronda.
     * @param string  $transactionId  ID de la transacción.
     * @param string  $player         ID del jugador.
     * @param array   $datos          Datos adicionales de la transacción.
     * @param boolean $gameRoundEnd   Indica si la ronda del juego ha terminado.
     * @param string  $gameId         ID del juego.
     *
     * @return string Respuesta en formato JSON con el resultado del rollback.
     * @throws Exception Si ocurre un error durante el rollback.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos, $gameRoundEnd, $gameId)
    {
        $this->type = 'Rollback';
        try {
            /*  Obtenemos el Proveedor con el abreviado FANTASY */
            $Proveedor = new Proveedor("", "FANTASY");

            try {
                $UserVal = new UsuarioMandante($this->user);
            } catch (Exception $e) {
                throw new Exception("User Invalid", "10018");
            }

            try {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } catch (Exception $e) {
                throw new Exception("User Invalid", "10018");
            }

            $isRollback = false;
            try {
                $TransaccionJuego = new TransaccionJuego("", "FANTASY" . $roundId);
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
                $aggtrans = false;
                try {
                    $TransaccionJuego = new TransaccionJuego('', "FANTASY" . $roundId);
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

                    $Balance = floatval(number_format(round($responseG->saldo, 2), 2, '.', ''));

                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $return = array(
                        "isSuccess" => true,
                        "extTransactionId" => $this->transId,
                        "message?" => null,
                        "balance" => $Balance,
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
            $log = "";
            $log = $log . "\r\n" . "--------------Response-Error-500----------" . "\r\n";
            $log = $log . json_encode($e->getCode());
            $log = $log . json_encode($e->getMessage());
            $log = $log . "\r\n" . "---------------------------------" . "\r\n";
            $Path = realpath('/home/backend/public_html/api/integrations/casino/fantasy/api');
            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Convierte un error interno en un error comprensible para el proveedor.
     *
     * @param integer $code    Código de error interno.
     * @param string  $message Mensaje de error interno.
     *
     * @return string Respuesta en formato JSON con el error convertido.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $response = array();
        $Proveedor = new Proveedor("", "FANTASY");

        switch ($code) {
            case 10002:
                $codeProveedor = 1;
                $messageProveedor = "lack.of.funds";
                break;

            case 10003:
                $codeProveedor = 1;
                $messageProveedor = "lack.of.funds";
                break;

            case 10011:
                $codeProveedor = 6;
                $messageProveedor = "user.not.found";
                break;

            case 21:
                $codeProveedor = 6;
                $messageProveedor = "user.not.found";
                break;

            case 10030:
                $codeProveedor = 6;
                $messageProveedor = "user.not.found";
                break;

            case 10013:
                $codeProveedor = 7;
                $messageProveedor = "user.not.found";
                break;

            case 10005:
                $codeProveedor = 9;
                $messageProveedor = "transaction.not.found";
                break;

            case 22:
                $codeProveedor = 7;
                $messageProveedor = "user.not.found";
                break;

            case 50030:
                $codeProveedor = 9;
                $messageProveedor = "unknown.error";
                break;

            case 20001:
                $codeProveedor = 3;
                $messageProveedor = "lack.of.funds";
                break;

            case 0:
                $codeProveedor = 1;
                $messageProveedor = "unknown.error";
                break;

            case 27:
                $codeProveedor = 1;
                $messageProveedor = "unknown.error";
                break;

            case 10041:
                $codeProveedor = 1;
                $messageProveedor = "unknown.error";
                break;

            case 28:
                $codeProveedor = 1;
                $messageProveedor = "transaction.not.found";
                break;

            case 29:
                $codeProveedor = 1;
                $messageProveedor = "unknown.error";
                break;

            case 10001:
                $codeProveedor = 0;
                if ($this->type == 'Credit') {
                    $messageProveedor = "credit.already.exist";
                } else {
                    $messageProveedor = "debit.already.exist";
                }
                break;

            case 10016:
                $codeProveedor = 0;
                $messageProveedor = "debit.already.settled";
                break;

            case 10017:
                $codeProveedor = 0;
                $messageProveedor = "refund.already.settled";
                break;

            case 10018:
                $codeProveedor = 0;
                $messageProveedor = "user.not.found";
                break;

            case 10027:
                $codeProveedor = 0;
                $messageProveedor = "debit.already.settled";
                break;

            case 10004:
                $codeProveedor = 1;
                $messageProveedor = "final.error.action.failed";
                break;

            case 10014:
                $codeProveedor = 1;
                $messageProveedor = "debit.already.exist";
                break;

            default:
                $codeProveedor = 1;
                $messageProveedor = "unknown.error";
                break;
        }

        $respuesta = json_encode(array_merge($response, array(
            "isSuccess" => false,
            "extTransactionId" => $this->transId,
            "errorCode" => $messageProveedor
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
