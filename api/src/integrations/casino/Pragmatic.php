<?php

/**
 * Clase Pragmatic para la integración con el proveedor de juegos Pragmatic.
 *
 * Este archivo contiene la implementación de métodos para manejar transacciones
 * relacionadas con juegos, como autenticación, balance, débitos, créditos,
 * rollbacks y finalización de rondas. También incluye manejo de errores y
 * registro de transacciones.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
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
 * Clase Pragmatic.
 *
 * Esta clase implementa la integración con el proveedor de juegos Pragmatic,
 * proporcionando métodos para manejar transacciones relacionadas con juegos,
 * como autenticación, balance, débitos, créditos, rollbacks y finalización de rondas.
 */
class Pragmatic
{
    /**
     * Token de autenticación.
     *
     * @var string
     */
    private $token;

    /**
     * Identificador externo.
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
     * Datos de la transacción.
     *
     * @var array
     */
    private $data;

    /**
     * Indica si hay un error en el hash.
     *
     * @var boolean
     */
    private $errorHash = false;

    /**
     * Método de la transacción.
     *
     * @var string
     */
    private $method;

    /**
     * Indica si es un bono.
     *
     * @var boolean
     */
    private $isbonus;

    /**
     * Constructor de la clase Pragmatic.
     *
     * Inicializa los valores del token, firma y externalId. También verifica si
     * el sistema está habilitado y define constantes necesarias.
     *
     * @param string $token        Token de autenticación.
     * @param string $sign         Firma de seguridad.
     * @param string $external     Identificador externo (opcional).
     * @param string $hashOriginal Hash original (opcional).
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
    }

    /**
     * Método para autenticar al usuario.
     *
     * Valida el token o el externalId y autentica al usuario con el proveedor.
     * Retorna información del usuario, como saldo y moneda.
     *
     * @return string Respuesta en formato JSON con los datos del usuario o error.
     */
    public function Auth()
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
            $Proveedor = new Proveedor("", "PRAGMATIC");

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

            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));
            $return = array(
                "userId" => $responseG->usuarioId . "_" . $responseG->moneda,
                "cash" => $saldo,
                "bonus" => 0,
                "currency" => $responseG->moneda,
                "error" => 0,
                "description" => "Success"
            );

            return json_encode($return, JSON_PRESERVE_ZERO_FRACTION);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del jugador.
     *
     * Recupera el saldo del jugador utilizando su externalId o token.
     *
     * @param string $playerId Identificador del jugador.
     *
     * @return string Respuesta en formato JSON con el saldo del jugador o error.
     */
    public function getBalance($playerId)
    {
        $this->externalId = $playerId;
        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }

        $this->method = 'balance';
        try {
            if ($playerId == "") {
                throw new Exception("Token vacio", "10011");
            }

            $Proveedor = new Proveedor("", "PRAGMATIC");

            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            /*  Obtenemos el producto con el gameId  */
            //$Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);

            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

            $return = array(
                "cash" => $saldo,
                "bonus" => 0,
                "currency" => $responseG->moneda
            );
            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un débito en la cuenta del jugador.
     *
     * Deduce un monto específico del saldo del jugador y registra la transacción.
     *
     * @param string  $gameId        Identificador del juego.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $roundId       Identificador de la ronda.
     * @param string  $transactionId Identificador de la transacción.
     * @param array   $datos         Datos adicionales de la transacción.
     * @param boolean $isbonus       Indica si es un bono (opcional).
     * @param boolean $isjackpot     Indica si es un jackpot (opcional).
     *
     * @return string Respuesta en formato JSON con el resultado de la transacción.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $isbonus = false, $isjackpot = false)
    {
        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }

        $this->method = 'debit';
        $this->data = $datos;
        $this->isbonus = $isbonus;

        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado PRAGMATIC */
            $Proveedor = new Proveedor("", "PRAGMATIC");

            if ($this->token != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                } catch (Exception $e) {
                    $UsuarioToken = new UsuarioToken('', $Proveedor->getProveedorId(), $this->externalId);
                }
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            /*  Obtenemos el producto con el gameId  */
            try {
                if ($gameId == 'DE_00') {
                    $Producto = new Producto("", 'DEFAULT_00', $Proveedor->getProveedorId());
                } else {
                    $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
                }
            } catch (Exception $e) {
                $Producto = new Producto("", 'DEFAULT_00', $Proveedor->getProveedorId());
            }

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);
            $this->transaccionApi->setIdentificador("PRAGMATIC" . $roundId);

            $Game = new Game();

            $isfreeSpin = false;
            if ($isjackpot == false) {
                if ($isbonus == true) {
                    $isfreeSpin = true;
                }
            }

            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $isfreeSpin);

            $this->transaccionApi = $responseG->transaccionApi;

            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

            $respuesta = json_encode(array(
                "transactionId" => $responseG->transaccionId,
                "cash" => $saldo,
                "currency" => $responseG->moneda,
                "bonus" => 0,
                "usedPromo" => 0,
                "error" => 0,
                "description" => "Success"
            ));

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $respuesta;
        } catch (Exception $e) {
            if ($_ENV['debug']) {
                print_r($this);
                print_r($e);
            }
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un rollback de una transacción.
     *
     * Cancela una transacción previa y actualiza el saldo del jugador.
     *
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $roundId        Identificador de la ronda.
     * @param string $transactionId  Identificador de la transacción original.
     * @param string $player         Identificador del jugador.
     * @param array  $datos          Datos adicionales de la transacción.
     *
     * @return string Respuesta en formato JSON con el resultado del rollback.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos)
    {
        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }

        $this->method = 'rollback';
        $this->data = $datos;

        try {
            /*  Obtenemos el Proveedor con el abreviado PRAGMATIC */
            $Proveedor = new Proveedor("", "PRAGMATIC");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($rollbackAmount);

            $transaccionNoExiste = false;

            try {
                $TransaccionApi2 = new TransaccionApi("", $transactionId, $Proveedor->getProveedorId());
                $jsonValue = json_decode($TransaccionApi2->getTValue());
                $valorTransaction = 0;

                $UsuarioMandante = new UsuarioMandante($TransaccionApi2->getUsuarioId());
                if (strpos($TransaccionApi2->getTipo(), 'DEBIT') !== false) {
                    $this->transaccionApi->setIdentificador($TransaccionApi2->getIdentificador());
                } else {
                    throw new Exception("Transaccion no es Debit", "10006");
                }
            } catch (Exception $e) {
                $transaccionNoExiste = true;
                throw new Exception("Transaccion no existe", "10005");
            }

            $Game = new Game();
            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi);

            $this->transaccionApi = $responseG->transaccionApi;
            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

            $respuesta = json_encode(array(
                "transactionId" => $responseG->transaccionId,
                "cash" => $saldo,
                "currency" => $responseG->moneda,
                "bonus" => 0,
                "usedPromo" => 0,
                "error" => 0,
                "description" => "Success"
            ));

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $respuesta;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un crédito en la cuenta del jugador.
     *
     * Agrega un monto específico al saldo del jugador y registra la transacción.
     *
     * @param string  $gameId        Identificador del juego.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       Identificador de la ronda.
     * @param string  $transactionId Identificador de la transacción.
     * @param array   $datos         Datos adicionales de la transacción.
     * @param boolean $isBonus       Indica si es un bono (opcional).
     *
     * @return string Respuesta en formato JSON con el resultado de la transacción.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos, $isBonus = false)
    {
        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }

        $this->method = 'credit';
        $this->data = $datos;

        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado PRAGMATIC */
            $Proveedor = new Proveedor("", "PRAGMATIC");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);
            $this->transaccionApi->setIdentificador("PRAGMATIC" . $roundId);

            try {
                $TransaccionJuego = new TransaccionJuego("", "PRAGMATIC" . $roundId);
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            $ProductoMandante = new ProductoMandante("", "", $TransaccionJuego->productoId);
            // /*  Obtenemos el producto con el $TransaccionJuego->productoId */
            $Producto = new Producto($ProductoMandante->productoId);

            $Game = new Game();
            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, true, false, $isBonus);

            $this->transaccionApi = $responseG->transaccionApi;

            /*  Retornamos el mensaje satisfactorio  */
            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

            $respuesta = json_encode(array(
                "transactionId" => $responseG->transaccionId,
                "cash" => $saldo,
                "currency" => $responseG->moneda,
                "bonus" => 0,
                "usedPromo" => 0,
                "error" => 0,
                "description" => "Success"
            ));

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $respuesta;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Finaliza una ronda de juego.
     *
     * Libera los recursos asociados a una ronda y actualiza el estado de la transacción.
     *
     * @param string $gameId  Identificador del juego.
     * @param string $roundId Identificador de la ronda.
     * @param array  $datos   Datos adicionales de la transacción.
     *
     * @return string Respuesta en formato JSON con el resultado de la operación.
     */
    public function EndRound($gameId, $roundId, $datos)
    {
        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }

        $this->method = 'endRound';
        $this->data = $datos;

        try {
            /*  Obtenemos el Proveedor con el abreviado PRAGMATIC */
            $Proveedor = new Proveedor("", "PRAGMATIC");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId(0);
            $this->transaccionApi->setTipo("ENDROUND");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);
            $this->transaccionApi->setIdentificador("PRAGMATIC" . $roundId);


            $Game = new Game();
            $responseG = $Game->endRound($this->transaccionApi);

            $this->transaccionApi = $responseG->transaccionApi;

            /*  Retornamos el mensaje satisfactorio  */
            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

            $respuesta = json_encode(array(
                "cash" => $saldo,
                "bonus" => 0
            ));

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $respuesta;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Convierte un error en una respuesta JSON.
     *
     * Maneja los errores generados durante las transacciones, registra los detalles
     * en un archivo de log y retorna una respuesta estructurada.
     *
     * @param integer $code    Código del error.
     * @param string  $message Mensaje del error.
     *
     * @return string Respuesta en formato JSON con los detalles del error.
     */
    public function convertError($code, $message)
    {
        $isRollback = false;
        if ($this->method == 'rollback') {
            $isRollback = true;
        }

        $codeProveedor = "";
        $messageProveedor = "";

        $response = array();
        $Proveedor = new Proveedor("", "PRAGMATIC");

        switch ($code) {
            case 10011:
                $codeProveedor = "4";
                $messageProveedor = "Player authentication failed due to invalid, not found or expired token.";
                break;

            case 21:
                $codeProveedor = "4";
                $messageProveedor = "Player authentication failed due to invalid, not found or expired token.";
                break;

            case 22:
                $codeProveedor = "4";
                $messageProveedor = "Player authentication failed due to invalid, not found or expired token.";
                break;

            case 20001:
                $codeProveedor = "1";
                $messageProveedor = "Insufficient balance";
                break;

            case 0:
                $codeProveedor = "120";
                $messageProveedor = "Internal server error";
                break;

            case 27:
                $codeProveedor = "8";
                $messageProveedor = "Game is not found or disabled";
                break;

            case 28:
                $codeProveedor = "120";
                $messageProveedor = "ROUND_NOT_FOUND";
                break;

            case 29:
                $codeProveedor = "120";
                $messageProveedor = "Transaction Not Found";
                break;

            case 10001:

                $codeProveedor = "0";
                $messageProveedor = "Success";

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
                $responseG = $Game->getBalance($UsuarioMandante, $isRollback);

                $ProductoMandante = new ProductoMandante('', '', $this->transaccionApi->getProductoId());
                $Producto = new Producto($ProductoMandante->productoId);

                $TransjuegoLog = new TransjuegoLog("", '', '', $this->transaccionApi->getTransaccionId() . '_' . $Producto->getSubproveedorId(), $Producto->getSubproveedorId());

                $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

                /*  Retornamos el mensaje satisfactorio  */
                $response = array(
                    "transactionId" => $TransjuegoLog->transjuegologId,
                    "cash" => $saldo,
                    "currency" => $responseG->moneda,
                    "bonus" => 0,
                    "usedPromo" => 0,
                    "error" => "0",
                    "description" => "Success"
                );
                break;

            case 10004:
                $codeProveedor = "ERROR";
                $messageProveedor = "General Error. (" . $code . ")";
                $codeProveedor = "ERROR";
                $messageProveedor = "Apuesta con cancelacion antes.";
                break;

            case 10005:
                $codeProveedor = "0";
                $messageProveedor = "Bet Transaction not found";
                break;

            case 10014:
                $codeProveedor = "100";
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 10010:
                $codeProveedor = "100";
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 20002:
                $codeProveedor = "5";
                $messageProveedor = "Invalid hash code";
                break;

            default:
                $codeProveedor = "120";
                $messageProveedor = "Internal server error";
                break;
        }

        if ($codeProveedor != "") {
            $respuesta = json_encode(array_merge($response, array(
                "error" => $codeProveedor,
                "responseCode2" => $code,
                "description" => $messageProveedor
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
