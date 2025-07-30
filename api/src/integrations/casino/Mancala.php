<?php

/**
 * Clase Mancala para la integración con el proveedor de juegos Mancala.
 *
 * Esta clase contiene métodos para manejar transacciones de juegos, autenticación de usuarios,
 * obtención de balances, débitos, créditos y rollbacks, además de la conversión de errores.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;

use Backend\dto\Mandante;
use Backend\dto\PromocionalLog;
use Backend\dto\Subproveedor;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransaccionApi;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioToken;
use Backend\dto\Producto;
use Backend\dto\ProductoMandante;
use Backend\dto\Proveedor;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransaccionApiMySqlDAO;
use Backend\websocket\WebsocketUsuario;
use Exception;

/**
 * Clase principal para la integración con el proveedor de juegos Mancala.
 *
 * Esta clase contiene métodos para manejar transacciones de juegos, autenticación de usuarios,
 * obtención de balances, débitos, créditos y rollbacks, además de la conversión de errores.
 */
class Mancala
{
    /**
     * Identificador del operador.
     *
     * @var mixed
     */
    private $operadorId;

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
     * Identificador único del usuario.
     *
     * @var string
     */
    private $uid;

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
     * Datos adicionales de la transacción.
     *
     * @var array
     */
    private $data;

    /**
     * Identificador de la ronda superior.
     *
     * @var string
     */
    private $roundIdSuper;

    /**
     * Login seguro para la integración.
     *
     * @var string
     */
    private $secureLogin = 'drb_doradobet';

    /**
     * Método actual en uso.
     *
     * @var string
     */
    private $method = ' ';

    /**
     * Identificador del proveedor.
     *
     * @var string
     */
    private $providerId = 'MancalaPlay';

    /**
     * Indica si hay un error en el hash.
     *
     * @var boolean
     */
    private $errorHash = false;


    /**
     * Constructor de la clase Mancala.
     *
     * @param string $token        Token de autenticación.
     * @param string $sign         Firma de seguridad.
     * @param string $external     Identificador externo (opcional).
     * @param string $hashOriginal Hash original para validación (opcional).
     */
    public function __construct($token, $sign, $external = "", $hashOriginal = "")
    {
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
     * Obtiene el ID del operador.
     *
     * @return mixed ID del operador.
     */
    public function getOperadorId()
    {
        return $this->operadorId;
    }

    /**
     * Autentica al usuario con el proveedor Mancala.
     *
     * @return string Respuesta en formato JSON con los datos de autenticación.
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
            $Proveedor = new Proveedor("", "MANCALA");

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

                "userId" => $responseG->usuarioId,
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
     * Obtiene el balance del jugador (versión 2).
     *
     * @param string $playerId ID del jugador.
     *
     * @return string Respuesta en formato JSON con el balance del jugador.
     */
    public function getBalance2($playerId)
    {
        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }

        try {
            $Proveedor = new Proveedor("", "MANCALA");

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
     * Obtiene el balance del jugador.
     *
     * @param string $playerId ID del jugador (opcional).
     *
     * @return string Respuesta en formato JSON con el balance del jugador.
     */
    public function getBalance($playerId = "")
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
            $Proveedor = new Proveedor("", "MANCALA");

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
                "Error" => 0,
                "Balance" => $saldo
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un débito en la cuenta del jugador.
     *
     * @param string $gameId        ID del juego.
     * @param float  $debitAmount   Monto a debitar.
     * @param string $roundId       ID de la ronda.
     * @param string $transactionId ID de la transacción.
     * @param array  $datos         Datos adicionales de la transacción.
     *
     * @return string Respuesta en formato JSON con el resultado del débito.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos)
    {
        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }

        $this->method = 'reserve';

        $this->data = $datos;

        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado MANCALA */
            $Proveedor = new Proveedor("", "MANCALA");

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
            $Producto = new Producto($UsuarioToken->productoId);

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);
            $this->transaccionApi->setIdentificador("MANCALA" . $roundId);

            $Game = new Game();

            $isfreeSpin = false;
            if (floatval($debitAmount) == 0) {
                $isfreeSpin = true;
            }
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $isfreeSpin);

            $this->transaccionApi = $responseG->transaccionApi;

            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

            $respuesta = json_encode(array(
                "Error" => 0,
                "Balance" => $saldo
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
     * Realiza un rollback en la cuenta del jugador (versión 2).
     *
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $roundId        ID de la ronda.
     * @param string $transactionId  ID de la transacción.
     * @param string $player         Información del jugador.
     * @param array  $datos          Datos adicionales de la transacción.
     *
     * @return string Respuesta en formato JSON con el resultado del rollback.
     */
    public function Rollback2($rollbackAmount, $roundId, $transactionId, $player, $datos)
    {
        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }


        $usuarioid = explode("Usuario", $player)[1];

        $this->data = $datos;


        try {
            /*  Obtenemos el Proveedor con el abreviado MANCALA */
            $Proveedor = new Proveedor("", "MANCALA");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);

            /*  Verificamos que la transaccionId no se haya procesado antes  */
            if ($this->transaccionApi->existsTransaccionIdAndProveedor("OK")) {
                /*  Si la transaccionId ha sido procesada, reportamos el error  */
                throw new Exception("Transaccion ya procesada", "10001");
            }

            $transaccionNoExiste = false;

            try {
                $TransaccionApi2 = new TransaccionApi("", $transactionId, $Proveedor->getProveedorId());
                $jsonValue = json_decode($TransaccionApi2->getTValue());
                $valorTransaction = 0;

                /*  Agregamos Elementos a la Transaccion API  */
                $this->transaccionApi->setProductoId($TransaccionApi2->getProductoId());
                $this->transaccionApi->setUsuarioId($TransaccionApi2->getUsuarioId());


                if (strpos($TransaccionApi2->getTipo(), 'DEBIT') !== false) {
                } else {
                    throw new Exception("Transaccion no es Debit", "10006");
                }
                $UsuarioMandante = new UsuarioMandante($TransaccionApi2->getUsuarioId());

                $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "MANCALA");
            } catch (Exception $e) {
                $transaccionNoExiste = true;
                throw new Exception("Transaccion no existe", "10005");
            }

            if ( ! $transaccionNoExiste) {
                /*  Creamos la Transaccion por el Juego  */
                $TransaccionJuego = new TransaccionJuego("", $TransaccionApi2->getIdentificador());
                $valorTransaction = $TransaccionApi2->getValor();

                /*  Obtenemos Mandante para verificar sus caracteristicas  */
                $Mandante = new Mandante($UsuarioMandante->mandante);

                /*  Verificamos si el mandante es Propio  */
                if ($Mandante->propio == "S") {
                    /* Obtenemos la Transaccion de la BD para crear y registrar el Debito con esta transaccion */
                    $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
                    $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

                    /*  Verificamos que la Transaccion si este conectada y lista para usarse  */
                    if ($Transaction->isIsconnected()) {
                        /*  Actualizamos Transaccion Juego  */
                        $TransaccionJuego->setEstado("I");
                        $TransaccionJuego->setValorTicket($TransaccionJuego->getValorTicket() - $valorTransaction);
                        $TransaccionJuego->update($Transaction);


                        /*  Obtenemos el Transaccion Juego ID  */
                        $TransJuegoId = $TransaccionJuego->getTransjuegoId();

                        /*  Creamos el Log de Transaccion Juego  */
                        $TransjuegoLog = new TransjuegoLog();
                        $TransjuegoLog->setTransjuegoId($TransJuegoId);
                        $TransjuegoLog->setTransaccionId("ROLLBACK" . $transactionId);
                        $TransjuegoLog->setTipo("ROLLBACK");
                        $TransjuegoLog->setTValue(json_encode($datos));
                        $TransjuegoLog->setUsucreaId(0);
                        $TransjuegoLog->setUsumodifId(0);
                        $TransjuegoLog->setValor($valorTransaction);

                        $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);

                        /*  Obtenemos el Usuario para hacerle el credito  */
                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                        $Usuario->credit($valorTransaction, $Transaction);


                        $Transaction->commit();

                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                        $Balance = (int)($Usuario->getBalance() * 100);


                        $return = array(
                            "balance" => $Balance,
                            "responseCode" => "OK"
                        );
                        /*  Guardamos la Transaccion Api necesaria de estado OK   */
                        $this->transaccionApi->setRespuestaCodigo("OK");
                        $this->transaccionApi->setRespuesta(json_encode($return));
                        $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                        $TransaccionApiMySqlDAO->update($this->transaccionApi);
                        $TransaccionApiMySqlDAO->getTransaction()->commit();

                        return json_encode($return);
                    }
                }
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un rollback en la cuenta del jugador.
     *
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $roundId        ID de la ronda.
     * @param string $transactionId  ID de la transacción.
     * @param string $player         Información del jugador.
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

        $this->method = 'Rollback';

        $this->data = $datos;

        try {
            /*  Obtenemos el Proveedor con el abreviado MANCALA */
            $Proveedor = new Proveedor("", "MANCALA");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($rollbackAmount);

            try {
                $SubProveedor = new Subproveedor("", "MANCALA");
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

            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi);

            $this->transaccionApi = $responseG->transaccionApi;
            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

            $respuesta = json_encode(array(
                "Error" => 0,
                "Balance" => $saldo
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
     * @param string  $gameId        ID del juego (opcional).
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param array   $datos         Datos adicionales de la transacción.
     * @param boolean $isBonus       Indica si el crédito es un bono (opcional).
     *
     * @return string Respuesta en formato JSON con el resultado del crédito.
     */
    public function Credit($gameId = "", $creditAmount, $roundId, $transactionId, $datos, $isBonus = false)
    {
        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }

        $this->method = 'release';

        $this->data = $datos;

        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado MANCALA */
            $Proveedor = new Proveedor("", "MANCALA");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);
            $this->transaccionApi->setIdentificador("MANCALA" . $roundId);

            try {
                $TransaccionJuego = new TransaccionJuego("", "MANCALA" . $roundId);
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            $ProductoMandante = new ProductoMandante("", "", $TransaccionJuego->productoId);
            /*  Obtenemos el producto con el $TransaccionJuego->productoId */
            $Producto = new Producto($ProductoMandante->productoId);

            $Game = new Game();

            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, false, false, $isBonus);

            $this->transaccionApi = $responseG->transaccionApi;

            /*  Retornamos el mensaje satisfactorio  */
            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

            $respuesta = json_encode(array(
                "Error" => 0,
                "Balance" => $saldo
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
     * @return string Respuesta en formato JSON con el error convertido.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $response = array();

        $Proveedor = new Proveedor("", "MANCALA");

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

            case 27: //OK
                $codeProveedor = "8";
                $messageProveedor = "Requested game was not found.";
                break;

            case 28:
                $codeProveedor = "120";
                $messageProveedor = "ROUND_NOT_FOUND";
                break;

            case 29:
                $codeProveedor = "120";
                $messageProveedor = "Transaction Not Found";
                break;

            case 10001: //OK

                $codeProveedor = "0";
                $messageProveedor = "No errors";

                $TransaccionApi = new TransaccionApi("", $this->transaccionApi->getTransaccionId(), $Proveedor->getProveedorId());

                if ($this->token != "") {
                    try {
                        /*  Obtenemos el Usuario Token con el token */
                        $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                        /*  Obtenemos el Usuario Mandante con el Usuario Token */
                        $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                    } catch (Exception $e) {
                        /*  Obtenemos el Usuario Mandante con el Usuario Token */
                        $UsuarioMandante = new UsuarioMandante($TransaccionApi->usuarioId);
                    }
                } else {
                    /*  Obtenemos el Usuario Mandante con el Usuario Token */
                    $UsuarioMandante = new UsuarioMandante($TransaccionApi->usuarioId);
                }

                $Game = new Game();

                $responseG = $Game->getBalance($UsuarioMandante);

                $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

                /*  Retornamos el mensaje satisfactorio  */
                $response = json_encode(array(
                    "Error" => 0,
                    "Balance" => $saldo
                ));

                break;

            case 10004:
                $codeProveedor = "ERROR";
                $messageProveedor = "General Error. (" . $code . ")";
                $codeProveedor = "4";
                $messageProveedor = "Apuesta con cancelacion antes.";
                break;

            case 10005:

                if ($this->method == 'Rollback') {
                    if ($this->token != "") {
                        try {
                            $TransaccionApi = new TransaccionApi("", $this->transaccionApi->getTransaccionId(), $Proveedor->getProveedorId());

                            if ($this->token != "") {
                                try {
                                    /*  Obtenemos el Usuario Token con el token */
                                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                                    /*  Obtenemos el Usuario Mandante con el Usuario Token */
                                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                                } catch (Exception $e) {
                                    /*  Obtenemos el Usuario Mandante con el Usuario Token */
                                    $UsuarioMandante = new UsuarioMandante($TransaccionApi->usuarioId);
                                }
                            } else {
                                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                                $UsuarioMandante = new UsuarioMandante($TransaccionApi->usuarioId);
                            }

                            $Game = new Game();

                            $responseG = $Game->getBalance($UsuarioMandante);

                            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

                            /*  Retornamos el mensaje satisfactorio  */
                            $response = json_encode(array(
                                "Error" => 0,
                                "Balance" => $saldo
                            ));
                        } catch (Exception $e) {
                            if ($e->getCode() == 21 || $e->getCode() == 29) {
                                /*  Retornamos el mensaje satisfactorio  */
                                $response = json_encode(array(
                                    "Error" => 0,
                                    "Balance" => 1.00
                                ));
                            }
                        }
                    }
                } else {
                    $codeProveedor = "4";
                    $messageProveedor = "Bet Transaction not found";
                }

                break;

            case 10014:

                $codeProveedor = "100";
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 10010:
                $codeProveedor = "100";
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 20002: //OK
                $codeProveedor = "1";
                $messageProveedor = "Hash Mismatch.";
                break;

            case 20003: //OK
                $codeProveedor = "5";
                $messageProveedor = "Player is blocked.";
                break;

            case 10017: //OK
                $codeProveedor = "7";
                $messageProveedor = "Requested currency was not found.";
                break;

            default:

                $codeProveedor = "4"; //OK
                $messageProveedor = "Internal service error";
                break;
        }

        if (is_array($response)) {
            if ($codeProveedor != "") {
                $respuesta = json_encode(array_merge($response, array(
                    "Error" => $codeProveedor,
                    "Msg" => $messageProveedor
                )));
            } else {
                $respuesta = json_encode(array_merge($response));
            }
        } else {
            if ($codeProveedor != "") {
                $respuesta = json_encode(array_merge(json_decode($response, true), array(
                    "Error" => $codeProveedor,
                    "Msg" => $messageProveedor
                )));
            } else {
                $respuesta = json_encode(array_merge(json_decode($response, true)));
            }
        }

        if ($this->transaccionApi != null) {
            $this->transaccionApi->setTipo("R" . $this->transaccionApi->getTipo());
            $this->transaccionApi->setRespuestaCodigo("ERROR" . $code);
            $this->transaccionApi->setRespuesta($respuesta);

            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->insert($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();
        }

        return $respuesta;
    }
}
