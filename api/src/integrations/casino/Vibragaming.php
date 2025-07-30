<?php

/**
 * Clase Vibragaming para la integración con el proveedor de juegos Vibragaming.
 *
 * Este archivo contiene la implementación de la clase Vibragaming, que maneja
 * la autenticación, balance, débitos, créditos, y otras operaciones relacionadas
 * con la integración de juegos del proveedor Vibragaming.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
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
 * Clase principal para manejar la integración con Vibragaming.
 */
class Vibragaming
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
     * Identificador de la transacción.
     *
     * @var string
     */
    private $transactionId;

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
     * Datos adicionales para la operación.
     *
     * @var mixed
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
     * Método actual de la operación.
     *
     * @var string
     */
    private $method = ' ';

    /**
     * Identificador del proveedor.
     *
     * @var string
     */
    private $providerId = 'VibragamingPlay';

    /**
     * Indica si hay un error en el hash.
     *
     * @var boolean
     */
    private $errorHash = false;

    /**
     * Constructor de la clase Vibragaming.
     *
     * @param string $token    Token de autenticación.
     * @param string $sign     Firma de seguridad.
     * @param string $external Identificador externo (opcional).
     */
    public function __construct($token, $sign, $external = "")
    {
        $this->token = $token;
        $this->sign = $sign;
        $this->externalId = $external;
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
     * Autentica al usuario con el proveedor Vibragaming.
     *
     * @return string Respuesta en formato JSON con los datos de autenticación.
     * @throws Exception Si el token o el identificador externo están vacíos.
     */
    public function Auth()
    {
        $this->method = 'authenticate';
        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }
            $Proveedor = new Proveedor("", "VIBRAGAMING");

            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->externalId);
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->getUsumandanteId());
            }


            $Game = new Game();

            $responseG = $Game->autenticate($UsuarioMandante);


            $return = array(

                "result" => "OK",
                "timestamp" => (round(microtime(true) * 1000)),
                "data" => array(
                    //"stakeLevels" => "", //Opcional
                    //"stakeDefaultLevel" => "", //Opcional
                    "accountBalance" => $responseG->saldo,
                    "accountCurrency" => $responseG->moneda,
                    //"accountFreeBalance" => 0, //Opcional
                    "token" => $UsuarioToken->token
                ),


            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del jugador utilizando su ID.
     *
     * @param string $playerId ID del jugador.
     *
     * @return string Respuesta en formato JSON con el balance del jugador.
     * @throws Exception Si el ID del jugador está vacío o ocurre un error.
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
            $Proveedor = new Proveedor("", "Vibragaming");

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
     * Obtiene el balance del jugador autenticado.
     *
     * @return string Respuesta en formato JSON con el balance del jugador.
     * @throws Exception Si ocurre un error durante la operación.
     */
    public function getBalance()
    {
        $this->method = 'balance';

        try {
            $Proveedor = new Proveedor("", "VIBRAGAMING");


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


            $saldo = intval(floatval(round($responseG->saldo, 2) * 100));


            $fecha = date_timestamp_get(date_create());

            $return = array(
                "result" => "OK",
                "timestamp" => $fecha,
                "data" => array(
                    "accountBalance" => $saldo,
                    "accountCurrency" => $responseG->moneda,
                    "token" => $UsuarioToken->token,
                ),
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
     * @param array  $datos         Datos adicionales para la transacción.
     *
     * @return string Respuesta en formato JSON con los detalles de la transacción.
     * @throws Exception Si el token o el identificador externo están vacíos o ocurre un error.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos)
    {
        $this->method = 'reserve';

        $this->data = $datos;

        $this->transactionId = $transactionId;


        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado Vibragaming */
            $Proveedor = new Proveedor("", "VIBRAGAMING");

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
            $this->transaccionApi->setIdentificador("VIBRAGAMING" . $roundId);

            $Game = new Game();

            $isfreeSpin = false;
            if (floatval($debitAmount) == 0) {
                $isfreeSpin = true;
            }

            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $isfreeSpin);

            $this->transaccionApi = $responseG->transaccionApi;

            $saldo = intval(floatval(round($responseG->saldo, 2) * 100));

            $fecha = date_timestamp_get(date_create());

            $return = array(
                "result" => "OK",
                "timestamp" => $fecha,
                "data" => array(
                    "accountBalance" => $saldo,
                    "accountCurrency" => $responseG->moneda,
                    "transactionId" => $transactionId,
                    "token" => $UsuarioToken->token
                )
            );


            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta(json_encode($return));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un rollback de una transacción.
     *
     * @param string $roundId       ID de la ronda.
     * @param string $transactionId ID de la transacción.
     * @param array  $datos         Datos adicionales para la transacción.
     *
     * @return string Respuesta en formato JSON con los detalles del rollback.
     * @throws Exception Si la transacción no existe o ocurre un error.
     */
    public function Rollback($roundId, $transactionId, $datos)
    {
        $this->method = 'Rollback';

        $this->data = $datos;

        try {
            /*  Obtenemos el Proveedor con el abreviado Vibragaming */
            $Proveedor = new Proveedor("", "VIBRAGAMING");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);


            try {
                $SubProveedor = new Subproveedor("", "VIBRAGAMING");
                $TransjuegoLog = new TransjuegoLog("", "", "", $transactionId . '_' . $SubProveedor->getSubproveedorId(), $SubProveedor->getSubproveedorId());
                $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                $this->transaccionApi->setIdentificador($TransaccionJuego->getTicketId());
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }


            $Game = new Game();

            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi);

            $this->transaccionApi = $responseG->transaccionApi;

            $saldo = intval(floatval(round($responseG->saldo, 2) * 100));

            $fecha = date_timestamp_get(date_create());


            $return = array(
                "result" => "OK",
                "timestamp" => $fecha,
                "data" => array(
                    "accountBalance" => $saldo,
                    "accountCurrency" => $responseG->moneda,
                    "transactionId" => $transactionId,
                ),
            );


            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta(json_encode($return));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return json_encode($return);
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
     * @param array   $datos         Datos adicionales para la transacción.
     * @param boolean $isBonus       Indica si el crédito es un bono (opcional).
     *
     * @return string Respuesta en formato JSON con los detalles de la transacción.
     * @throws Exception Si el token o el identificador externo están vacíos o ocurre un error.
     */
    public function Credit($gameId = "", $creditAmount, $roundId, $transactionId, $datos, $isBonus = false)
    {
        $this->method = 'release';

        $this->data = $datos;

        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }
            /*  Obtenemos el Proveedor con el abreviado Vibragaming */
            $Proveedor = new Proveedor("", "VIBRAGAMING");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);
            $this->transaccionApi->setIdentificador("VIBRAGAMING" . $roundId);

            try {
                $TransaccionJuego = new TransaccionJuego("", "VIBRAGAMING" . $roundId);
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->getUsumandanteId());
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            $ProductoMandante = new ProductoMandante("", "", $TransaccionJuego->productoId);
            /*  Obtenemos el producto con el $TransaccionJuego->productoId */
            $Producto = new Producto($ProductoMandante->productoId);

            $Game = new Game();

            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, false, false, $isBonus);

            $this->transaccionApi = $responseG->transaccionApi;

            $saldo = intval(floatval(round($responseG->saldo, 2) * 100));


            $fecha = date_timestamp_get(date_create());


            $return = array(
                "result" => "OK",
                "timestamp" => $fecha,
                "data" => array(
                    "accountBalance" => $saldo,
                    "accountCurrency" => $responseG->moneda,
                    "transactionId" => $transactionId,
                    "token" => $UsuarioToken->token
                ),
            );


            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta(json_encode($return));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();


            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Convierte un error en una respuesta JSON estándar.
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


        $Proveedor = new Proveedor("", "Vibragaming");

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

                $codeProveedor = "";
                $messageProveedor = "";

                if ($this->token != "") {
                    try {
                        /*  Obtenemos el Usuario Token con el token */
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

                $Game = new Game();

                $responseG = $Game->getBalance($UsuarioMandante);

                $saldo = intval(floatval(round($responseG->saldo, 2) * 100));

                $fecha = date_timestamp_get(date_create());

                /*  Retornamos el mensaje satisfactorio  */
                $response = array(
                    "result" => "OK",
                    "timestamp" => $fecha,
                    "data" => array(
                        "accountBalance" => $saldo,
                        "accountCurrency" => $responseG->moneda,
                        "transactionId" => $this->transactionId,
                        "token" => $UsuarioToken->token
                    )
                );

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
                            if ($this->token != "") {
                                try {
                                    /*  Obtenemos el Usuario Token con el token */
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

                            $Game = new Game();

                            $responseG = $Game->getBalance($UsuarioMandante);

                            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

                            /*  Retornamos el mensaje satisfactorio  */
                            $response = array(
                                "Error" => 0,
                                "Balance" => $saldo
                            );
                        } catch (Exception $e) {
                            if ($e->getCode() == 21 || $e->getCode() == 29) {
                                /*  Retornamos el mensaje satisfactorio  */
                                $response = array(
                                    "Error" => 0,
                                    "Balance" => 1.00
                                );
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


        if ($codeProveedor != "") {
            $fecha = date_timestamp_get(date_create());

            $respuesta = array(
                "result" => "ERROR",
                "timestamp" => $fecha,
                "error" => array(
                    "code" => $codeProveedor,
                    "message" => $messageProveedor
                ),
            );

            $respuesta = json_encode($respuesta);
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

