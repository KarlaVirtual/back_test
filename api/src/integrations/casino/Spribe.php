<?php

/**
 * Clase Spribe
 *
 * Esta clase implementa la integración con el proveedor de juegos SPRIBE.
 * Proporciona métodos para autenticar usuarios, consultar balances, realizar débitos, créditos,
 * y manejar errores relacionados con las transacciones.
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
use Backend\sql\Connection;
use Backend\dto\UsuarioToken;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransaccionApi;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\dto\TransaccionJuego;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\mysql\TransaccionApiMySqlDAO;

/**
 * Clase Spribe
 *
 * Esta clase implementa la integración con el proveedor de juegos SPRIBE.
 * Proporciona métodos para autenticar usuarios, consultar balances, realizar débitos, créditos,
 * y manejar errores relacionados con las transacciones.
 */
class Spribe
{
    /**
     * Token de autenticación del usuario.
     *
     * @var string
     */
    private $token;

    /**
     * Identificador externo del usuario.
     *
     * @var string
     */
    private $externalId;

    /**
     * Proveedor del juego.
     *
     * @var string
     */
    private $provider;

    /**
     * Objeto de transacción API.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * Datos adicionales de la transacción.
     *
     * @var array
     */
    private $data;

    /**
     * Representación de 'method'
     *
     * @var string
     */
    private $method;

    /**
     * Constructor de la clase Spribe.
     *
     * Inicializa los valores del token, firma, ID externo y verifica el estado del sistema.
     *
     * @param string $token        Token del usuario.
     * @param string $external     ID externo del usuario (opcional).
     */
    public function __construct($token, $external = "")
    {
        if (!defined('JSON_PRESERVE_ZERO_FRACTION')) {
            define('JSON_PRESERVE_ZERO_FRACTION', 1024);
        }

        $this->token = $token;
        $this->externalId = $external;
    }

    /**
     * Autentica al usuario en la plataforma.
     *
     * @param string $user_token    Token del usuario.
     * @param string $session_token Token de sesión.
     * @param string $platform      Plataforma del usuario.
     * @param string $currency      Moneda utilizada.
     *
     * @return string Respuesta en formato JSON con los datos del usuario autenticado.
     */
    public function auth($user_token, $session_token, $platform, $currency)
    {
        $this->method = 'authenticate';

        try {

            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            $Proveedor = new Proveedor("", "SPRIBE");

            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            }

            $UsuarioToken->setToken($session_token);
            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
            $UsuarioTokenMySqlDAO->update($UsuarioToken);
            $UsuarioTokenMySqlDAO->getTransaction()->commit();

            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $saldo = intval(floatval(round($responseG->saldo, 3) * 1000));

            $return = array(
                "code" => 200,
                "message" => "OK",
                "data" => array(
                    "user_id" => $responseG->usuarioId,
                    "username" => $responseG->usuario,
                    "balance" => $saldo,
                    "currency" => $responseG->moneda
                ),
            );

            return json_encode($return, JSON_PRESERVE_ZERO_FRACTION);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene información del usuario autenticado.
     *
     * @param string $user_id       ID del usuario.
     * @param string $session_token Token de sesión.
     * @param string $currency      Moneda utilizada.
     *
     * @return string Respuesta en formato JSON con los datos del usuario.
     */
    public function info($user_id, $session_token, $currency)
    {
        $this->method = 'authenticate';

        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            $Proveedor = new Proveedor("", "SPRIBE");

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

            $saldo = intval(floatval(round($responseG->saldo, 3) * 1000));

            $return = array(
                "code" => 200,
                "message" => "OK",
                "data" => array(
                    "user_id" => $responseG->usuarioId,
                    "username" => $responseG->usuario,
                    "balance" => $saldo,
                    "currency" => $responseG->moneda
                ),
            );

            return json_encode($return, JSON_PRESERVE_ZERO_FRACTION);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del usuario (método alternativo).
     *
     * @param string $playerId ID del jugador.
     *
     * @return string Respuesta en formato JSON con el balance del usuario.
     */
    public function getBalance2($playerId)
    {
        try {
            $Proveedor = new Proveedor("", "SPRIBE");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("BALANCE");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);

            if ($playerId == "") {
                throw new Exception("Token vacio", "10011");
            }

            $UsuarioMandante = new UsuarioMandante($playerId);
            $Mandante = new Mandante($UsuarioMandante->mandante);

            if ($Mandante->propio == "S") {
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                $Balance = (int)($Usuario->getBalance() * 100);

                $return = array(
                    "balance" => $Balance,
                );
                return json_encode($return);
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del usuario.
     *
     * @param string $playerId ID del jugador.
     *
     * @return string Respuesta en formato JSON con el balance del usuario.
     */
    public function getBalance($playerId)
    {
        $this->externalId = $playerId;
        $this->method = 'balance';

        try {
            if ($playerId == "") {
                throw new Exception("Token vacio", "10011");
            }

            $Proveedor = new Proveedor("", "SPRIBE");

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
            $responseG = $Game->getBalance($UsuarioMandante);

            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

            $return = array(
                "user_id" => $responseG->usuarioId,
                "session_token" => $this->token,
                "currency" => $responseG->moneda
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
     * @param string $provider      Proveedor del juego.
     * @param array  $datos         Datos adicionales de la transacción.
     *
     * @return string Respuesta en formato JSON con los detalles del débito.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $provider, $datos)
    {
        $this->provider = $provider;
        $this->method = 'reserve';
        $this->data = $datos;

        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado SPRIBE */
            $Proveedor = new Proveedor("", "SPRIBE");

            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            if ($UsuarioToken->usuarioId == 168712) {
                $connection = new Connection();
                $connection = $_ENV["connectionGlobal"];
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
            $this->transaccionApi->setIdentificador("SPRIBE" . $UsuarioMandante->usumandanteId . '_' . $roundId);

            $isfreeSpin = false;
            if (floatval($debitAmount) ==  0) {
                $isfreeSpin = true;
            }

            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $isfreeSpin);
            $this->transaccionApi = $responseG->transaccionApi;

            $debitAmount = intval(floatval(round($debitAmount, 3) * 1000));
            $saldo = intval(floatval(round($responseG->saldo, 3) * 1000));

            $respuesta = json_encode(array(
                "code" => 200,
                "message" => "OK",
                "data" => array(
                    "operator_tx_id" => $responseG->transaccionId,
                    "new_balance" => $saldo,
                    "old_balance" => $saldo + $debitAmount,
                    "user_id" => $responseG->usuarioId,
                    "currency" => $responseG->moneda,
                    "provider" => $provider,
                    "provider_tx_id" => $transactionId
                )
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
     * Realiza un rollback de una transacción.
     *
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $roundId        ID de la ronda.
     * @param string $transactionId  ID de la transacción.
     * @param string $player         ID del jugador.
     * @param array  $datos          Datos adicionales de la transacción.
     * @param string $provider       Proveedor del juego.
     *
     * @return string Respuesta en formato JSON con los detalles del rollback.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos, $provider)
    {
        $this->provider = $provider;
        $this->method = 'cancelReserve';
        $this->data = $datos;

        try {
            /*  Obtenemos el Proveedor con el abreviado SPRIBE */
            $Proveedor = new Proveedor("", "SPRIBE");

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
            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false);
            $this->transaccionApi = $responseG->transaccionApi;

            $rollbackAmount = intval(floatval(round($rollbackAmount, 3) * 1000));
            $saldo = intval(floatval(round($responseG->saldo, 3) * 1000));

            $respuesta = json_encode(array(
                "code" => 200,
                "message" => "OK",
                "data" => array(
                    "user_id" => $responseG->usuarioId,
                    "operator_tx_id" => $responseG->transaccionId,
                    "provider" => $provider,
                    "provider_tx_id" => $roundId,
                    "old_balance" => ($saldo) - $rollbackAmount,
                    "new_balance" => $saldo,
                    "currency" => $responseG->moneda,
                )
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
     * Realiza un crédito en la cuenta del usuario.
     *
     * @param string  $gameId        ID del juego.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param array   $datos         Datos adicionales de la transacción.
     * @param boolean $isBonus       Indica si es un bono (opcional).
     * @param string  $provider      Proveedor del juego.
     *
     * @return string Respuesta en formato JSON con los detalles del crédito.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos, $isBonus = false, $provider)
    {
        $this->provider = $provider;
        $this->method = 'release';
        $this->data = $datos;

        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado SPRIBE */
            $Proveedor = new Proveedor("", "SPRIBE");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);

            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            $this->transaccionApi->setIdentificador("SPRIBE" . $UsuarioMandante->usumandanteId . '_' . $roundId);

            /*  Obtenemos el producto con el gameId  */
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            $Game = new Game();
            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, true, false, $isBonus);
            $this->transaccionApi = $responseG->transaccionApi;

            /*  Retornamos el mensaje satisfactorio  */
            $creditAmount = intval(floatval(round($creditAmount, 3) * 1000));
            $saldo = intval(floatval(round($responseG->saldo, 3) * 1000));

            $respuesta = json_encode(array(
                "code" => 200,
                "message" => "OK",
                "data" => array(
                    "operator_tx_id" => $responseG->transaccionId,
                    "new_balance" => $saldo,
                    "old_balance" => $saldo - $creditAmount,
                    "user_id" => $responseG->usuarioId,
                    "currency" => $responseG->moneda,
                    "provider" => $this->provider,
                    "provider_tx_id" => $transactionId
                )
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
     * @param integer $code    Código del error.
     * @param string  $message Mensaje del error.
     *
     * @return string Respuesta en formato JSON con los detalles del error.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";
        $response = array();

        $Proveedor = new Proveedor("", "SPRIBE");

        switch ($code) {
            case 10011:
                $codeProveedor = "401";
                $messageProveedor = "User token is invalid";
                break;

            case 21:
                $codeProveedor = "403";
                $messageProveedor = "User token is expired";
                break;

            case 22:
                $codeProveedor = "4";
                $messageProveedor = "Player authentication failed due to invalid, not found or expired token.";
                break;

            case 20001:
                $codeProveedor = "402";
                $messageProveedor = "Insufficient fund";
                break;

            case 0:
                $codeProveedor = "500";
                $messageProveedor = "Internal error";
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
                $codeProveedor = "408";
                $messageProveedor = "Transaction does not found";
                break;

            case 10001:
                $codeProveedor = "409";
                $messageProveedor = "Duplicate transaction";

                if ($this->token != "") {
                    try {
                        /* Obtenemos el Usuario Token con el token */
                        $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                        /*  Obtenemos el Usuario Mandante con el Usuario Token */
                        $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                    } catch (Exception $e) {
                        /*  Obtenemos el Usuario Mandante con el Usuario Token */
                        $UsuarioMandante = new UsuarioMandante($this->externalId);
                    }
                } else {
                    /*  Obtenemos el Usuario Mandante con el Usuario Token */
                    $UsuarioMandante = new UsuarioMandante($this->externalId);
                }

                $ProductoMandante = new ProductoMandante('', '', $this->transaccionApi->getProductoId());
                $Producto = new Producto($ProductoMandante->productoId);

                $TransjuegoLog = new TransjuegoLog("", '', '', $this->transaccionApi->getTransaccionId() . '_' . $Producto->getSubproveedorId(), $Producto->getSubproveedorId());
                $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);

                $Game = new Game();
                $responseG = $Game->getBalance($UsuarioMandante);

                /*  Retornamos el mensaje satisfactorio  */
                $response = array(
                    "code" => $codeProveedor,
                    "message" => $messageProveedor,
                    "data" => array(
                        "operator_tx_id" => $TransjuegoLog->transjuegologId,
                        "new_balance" => $responseG->saldo,
                        "old_balance" => $responseG->saldo - $TransjuegoLog->valor,
                        "user_id" => $responseG->usuarioId,
                        "currency" => $responseG->moneda,
                        "provider" => $this->provider,
                        "provider_tx_id" => explode('_', $TransjuegoLog->transaccionId)[0]
                    )
                );
                break;

            case 10004:
                $codeProveedor = "409";
                $messageProveedor = "Duplicate transaction.";
                break;

            case 10005:
                $codeProveedor = "408";
                $messageProveedor = "Transaction does not found";
                break;

            case 10014:
                $codeProveedor = "100";
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 10010:
                $codeProveedor = "100";
                $messageProveedor = "General - Error. (" . $code . ")";
                break;

            default:
                $codeProveedor = "500";
                $messageProveedor = "Internal server error";
                break;
        }

        if ($codeProveedor != "") {
            $respuesta = json_encode(array_merge($response, array(
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

            if ($this->transaccionApi->getUsuarioId() == 168712) {
                $connection = $_ENV["enabledConnectionGlobal"];
                $connection = $_ENV["connectionGlobal"];
            }

            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->insert($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();
        }

        return $respuesta;
    }
}
