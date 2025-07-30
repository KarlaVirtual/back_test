<?php

/**
 * Clase Thunderkick para la integración con el proveedor de juegos Thunderkick.
 *
 * @category Integración
 * @package  Backend\integrations\casino
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */

namespace Backend\integrations\casino;

use Backend\dto\Mandante;
use Backend\dto\PromocionalLog;
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
use Backend\dto\Subproveedor;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransaccionApiMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;
use Exception;

/**
 * Clase principal para la integración con el proveedor de juegos Thunderkick.
 * Contiene métodos para la autenticación, manejo de transacciones y gestión de usuarios.
 */
class Thunderkick
{
    /**
     * Identificador del operador.
     *
     * @var string
     */
    private $operadorId;

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
     * Datos adicionales.
     *
     * @var mixed
     */
    private $data;

    /**
     * Identificador de la ronda principal.
     *
     * @var string
     */
    private $roundIdSuper;

    /**
     * Login seguro.
     *
     * @var string
     */
    private $secureLogin = 'drb_doradobet';

    /**
     * Clave secreta.
     *
     * @var string
     */
    private $secret_key = 'testKey';

    /**
     * Identificador del proveedor.
     *
     * @var string
     */
    private $providerId = 'ThunderkickPlay';

    /**
     * Indica si hay un error en el hash.
     *
     * @var boolean
     */
    private $errorHash = false;

    /**
     * Método actual en ejecución.
     *
     * @var string
     */
    private $method = "";

    /**
     * Identificador de la transacción de reversión.
     *
     * @var string
     */
    private $rollbackTransactionId = "";

    /**
     * Constructor de la clase Thunderkick.
     *
     * @param string $token                 Token de autenticación.
     * @param string $sign                  Firma de seguridad.
     * @param string $external              ID externo del usuario (opcional).
     * @param string $hashOriginal          Hash original para validación (opcional).
     * @param string $rollbackTransactionId ID de transacción de reversión (opcional).
     */
    public function __construct($token, $sign, $external = "", $hashOriginal = "", $rollbackTransactionId = "")
    {
        if ( ! defined('JSON_PRESERVE_ZERO_FRACTION')) {
            define('JSON_PRESERVE_ZERO_FRACTION', 1024);
        }

        $this->token = $token;
        $this->sign = $sign;
        $this->externalId = $external;
        $this->rollbackTransactionId = $rollbackTransactionId;

        if ($this->sign != $hashOriginal) {
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
     * @return string|null ID del operador.
     */
    public function getOperadorId()
    {
        return $this->operadorId;
    }

    /**
     * Autentica un usuario en la plataforma.
     *
     * @param string $token        Token de autenticación.
     * @param string $PlayerId     ID del jugador.
     * @param string $PlatformType Tipo de plataforma.
     *
     * @return string JSON con el balance del usuario.
     * @throws Exception Si ocurre un error durante la autenticación.
     */
    public function Authentication($token, $PlayerId, $PlatformType)
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

            $Proveedor = new Proveedor("", "THUNDERKICK");

            if ($this->externalId != "") {
                /*  Obtenemos el Usuario Mandante con el externalId */
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

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
                "Balance" => $saldo,
                "BonusBalance" => 0.0
            );

            return json_encode($return, JSON_PRESERVE_ZERO_FRACTION);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Crea un token para un usuario.
     *
     * @param string $Token         Token de autenticación.
     * @param string $PlayerId      ID del jugador.
     * @param string $GameCode      Código del juego.
     * @param string $FinancialMode Modo financiero.
     * @param array  $datos         Datos adicionales.
     *
     * @return string JSON con el token generado.
     * @throws Exception Si ocurre un error durante la creación del token.
     */
    public function CreateToken($Token, $PlayerId, $GameCode, $FinancialMode, $datos)
    {
        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }

        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            $Proveedor = new Proveedor("", "THUNDERKICK");


            /*  Obtenemos el Usuario Mandante con el Usuario externalId */
            $UsuarioMandante = new UsuarioMandante($this->externalId);

            $isFun = true;

            $usumandanteId = $UsuarioMandante->usumandanteId;

            $migameid = $GameCode;
            try {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId);

                $GameAssociatedToken = $UsuarioToken->createToken();
                $token = $Token;
                $UsuarioToken->setToken($token);

                $Producto = new Producto("", $GameCode, $Proveedor->getProveedorId());
                $UsuarioToken->setProductoId($Producto->getProductoId());

                $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                $UsuarioTokenMySqlDAO->update($UsuarioToken);

                $UsuarioTokenMySqlDAO->getTransaction()->commit();
            } catch (Exception $e) {
                if ($e->getCode() == 21) {
                    $UsuarioToken = new UsuarioToken();
                    $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
                    $UsuarioToken->setCookie('0');
                    $UsuarioToken->setRequestId('0');
                    $UsuarioToken->setUsucreaId(0);
                    $UsuarioToken->setUsumodifId(0);
                    $UsuarioToken->setUsuarioId($usumandanteId);
                    $token = $UsuarioToken->createToken();
                    $token = $Token;
                    $UsuarioToken->setToken(substr($token, 0, strlen($token) - strlen("vssv" . $migameid)) . "vssv" . $migameid);
                    $UsuarioToken->setSaldo(0);

                    $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                    $UsuarioTokenMySqlDAO->insert($UsuarioToken);

                    $UsuarioTokenMySqlDAO->getTransaction()->commit();
                } else {
                    throw $e;
                }
            }

            $return = array(
                "token" => $UsuarioToken->getToken()
            );

            return json_encode($return, JSON_PRESERVE_ZERO_FRACTION);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance de un usuario.
     *
     * @param string $token    Token de autenticación.
     * @param string $PlayerId ID del jugador.
     *
     * @return string JSON con el balance del usuario.
     * @throws Exception Si ocurre un error al obtener el balance.
     */
    public function getBalance($token, $PlayerId)
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
            if ($PlayerId == "") {
                throw new Exception("Token vacio", "10011");
            }

            $Proveedor = new Proveedor("", "THUNDERKICK");


            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante("", $this->externalId);
            }
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            $accountType = "REAL";

            if ($Usuario->test == "N") {
                $accountType = "REAL";
            } elseif ($Usuario->test == "S") {
                $accountType = "REAL";
            }

            $accountType = "REAL";

            $Game = new Game();

            $responseG = $Game->getBalance($UsuarioMandante);

            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

            $return = array(
                "moneyAccounts" => [
                    array(
                        "balance" =>
                            array(
                                "amount" => floatval($saldo),
                                "currency" => $responseG->moneda
                            ),
                        "accountId" => $UsuarioToken->usuarioId,
                        "accountType" => $accountType
                    )

                ]

            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un débito en la cuenta del usuario.
     *
     * @param string  $gameId           ID del juego.
     * @param float   $debitAmount      Monto a debitar.
     * @param string  $roundId          ID de la ronda.
     * @param string  $transactionId    ID de la transacción.
     * @param boolean $EndGame          Indica si es el fin del juego.
     * @param array   $datos            Datos adicionales.
     * @param boolean $AllowClosedRound Permite rondas cerradas.
     * @param boolean $DebitAndCredit   Indica si es una transacción combinada.
     *
     * @return string JSON con el resultado del débito.
     * @throws Exception Si ocurre un error durante el débito.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $EndGame, $datos, $AllowClosedRound, $DebitAndCredit = false)
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

        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado Thunderkick */
            $Proveedor = new Proveedor("", "THUNDERKICK");

            if ($this->externalId != "") {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante("", $this->externalId);
            }

            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante("", $this->externalId);
            }


            /*  Obtenemos el producto con el gameId  */
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());


            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue($datos);
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);
            $this->transaccionApi->setIdentificador("THUNDERKICK" . $roundId);

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            $accountType = "REAL";

            if ($Usuario->test == "N") {
                $accountType = "REAL";
            } elseif ($Usuario->test == "S") {
                $accountType = "REAL";
            }

            $Game = new Game();

            $isfreeSpin = false;
            if (floatval($debitAmount) == 0) {
                $isfreeSpin = true;
            }

            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $isfreeSpin);

            $this->transaccionApi = $responseG->transaccionApi;

            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

            $respuesta = array(
                "balances" => array(
                    "moneyAccounts" => [
                        array(
                            "balance" => array(
                                "amount" => floatval($saldo),
                                "currency" => $responseG->moneda
                            ),
                            "accountId" => $UsuarioToken->usuarioId,
                            "accountType" => $accountType
                        ),

                    ]
                ),
                "extBetTransactionId" => $responseG->transaccionId
            );

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return json_encode($respuesta);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza una reversión de transacción.
     *
     * @param string  $token             Token de autenticación.
     * @param string  $RefTransactionId  ID de la transacción de referencia.
     * @param string  $GameCode          Código del juego.
     * @param string  $PlayerId          ID del jugador.
     * @param string  $RoundId           ID de la ronda.
     * @param boolean $CancelEntireRound Indica si se cancela toda la ronda.
     * @param string  $TransactionId     ID de la transacción.
     * @param float   $RollbackAmount    Monto a revertir.
     * @param array   $datos             Datos adicionales.
     * @param boolean $DebitAndCredit    Indica si es una transacción combinada.
     *
     * @return string JSON con el resultado de la reversión.
     * @throws Exception Si ocurre un error durante la reversión.
     */
    public function Rollback2($token, $RefTransactionId, $GameCode, $PlayerId, $RoundId, $CancelEntireRound = false, $TransactionId, $RollbackAmount, $datos, $DebitAndCredit = false)
    {
        $this->method = 'rollback';
        $this->rollbackTransactionId = $TransactionId;

        $this->data = $datos;

        try {
            /*  Obtenemos el Proveedor con el abreviado THUNDERKICK */
            $Proveedor = new Proveedor("", "THUNDERKICK");

            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            if ($CancelEntireRound) {
                $TransaccionJuego = new TransaccionJuego("", "THUNDERKICK" . $RoundId);

                $rules = []; //Regla para filtrar en la tabla Transjuego_log con el campo transjuego_id
                array_push($rules, array("field" => "transjuego_log.transjuego_id", "data" => $TransaccionJuego->getTransjuegoId(), "op" => "eq"));


                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $select = "transjuego_log.*";
                $grouping = "transjuego_log.transjuegolog_id";


                $TransjuegoLog = new TransjuegoLog();
                $Transactions = $TransjuegoLog->getTransjuegoLogsCustom($select, "transjuego_log.transjuegolog_id", "asc", 0, 100, $json, true, $grouping);
                $Transactions = json_decode($Transactions);


                foreach ($Transactions->data as $key => $transjuego) {
                    $RefTransactionId = $transjuego->{"transjuego_log.transaccion_id"};
                    $RefTransactionId = explode("_", $RefTransactionId)[0];

                    $TransactionId = $TransactionId . '_' . $key;

                    /*  Obtenemos el producto con el gameId  */
                    $Producto = new Producto("", $GameCode, $Proveedor->getProveedorId());

                    /*  Creamos la Transaccion API  */
                    $this->transaccionApi = new TransaccionApi();
                    $this->transaccionApi->setTransaccionId('ROLLBACK' . $TransactionId);
                    $this->transaccionApi->setTipo("ROLLBACK");
                    $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
                    $this->transaccionApi->setTValue(json_encode($datos));
                    $this->transaccionApi->setUsucreaId(0);
                    $this->transaccionApi->setUsumodifId(0);
                    $this->transaccionApi->setValor($RollbackAmount);


                    $TransaccionApi2 = new TransaccionApi("", $RefTransactionId, $Proveedor->getProveedorId()); //TransaccionApi Anterior DEBIT


                    $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());
                    $TransaccionJuego = new TransaccionJuego("", "THUNDERKICK" . $RoundId);


                    if (strpos($TransaccionApi2->getTipo(), 'DEBIT') !== false) {
                        $this->transaccionApi->setIdentificador($TransaccionApi2->getIdentificador());
                        $AllowCreditTransaction = false;
                    } else {
                        if (strpos($TransaccionApi2->getTipo(), 'CREDIT') !== false) {
                            $this->transaccionApi->setIdentificador($TransaccionApi2->getIdentificador());
                            $AllowCreditTransaction = true;
                        } else {
                            throw new Exception("Transaccion no es Debit ni Credit", "10006");
                        }
                    }

                    $this->transaccionApi->setTransaccionId('ROLLBACK' . $RefTransactionId);
                    //  Verificamos que la transaccionId no se haya procesado antes
                    if ($this->transaccionApi->existsTransaccionIdAndProveedor("OK")) {
                        //  Si la transaccionId ha sido procesada, reportamos el error
                        throw new Exception("Transaccion ya procesada", "10001");
                    }
                    $this->transaccionApi->setTransaccionId('ROLLBACK' . $TransactionId);


                    $Game = new Game();

                    $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false, $RefTransactionId, true, false, $AllowCreditTransaction, true);

                    $this->transaccionApi = $responseG->transaccionApi;
                    $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

                    $accountType = "REAL";

                    $respuesta = array(
                        "balances" => array(
                            "moneyAccounts" => [
                                array(
                                    "balance" => array(
                                        "amount" => floatval($saldo),
                                        "currency" => $responseG->moneda
                                    ),
                                    "accountId" => $UsuarioToken->usuarioId,
                                    "accountType" => $accountType
                                ),

                            ]
                        ),
                        "extRollbackTransactionId" => $responseG->transaccionId
                    );

                    /*  Guardamos la Transaccion Api necesaria de estado OK   */
                    $this->transaccionApi->setRespuesta($respuesta);
                    $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                    $TransaccionApiMySqlDAO->update($this->transaccionApi);
                    $TransaccionApiMySqlDAO->getTransaction()->commit();

                    $TransaccionApi22 = $this->transaccionApi;

                    $this->transaccionApi->setTransaccionId('ROLLBACK' . $RefTransactionId);
                    $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                    $TransaccionApiMySqlDAO->insert($TransaccionApi22);
                    $TransaccionApiMySqlDAO->getTransaction()->commit();
                }
                $TransaccionJuego = new TransaccionJuego("", "THUNDERKICK" . $RoundId);
                $TransaccionJuego->setTransaccionId("DEL_DEL_" . $TransaccionJuego->getTransaccionId());

                $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
                $TransaccionJuegoMySqlDAO->update($TransaccionJuego);
                $TransaccionJuegoMySqlDAO->getTransaction()->commit();
            } else {
                /*  Obtenemos el producto con el gameId  */
                $Producto = new Producto("", $GameCode, $Proveedor->getProveedorId());


                /*  Creamos la Transaccion API  */
                $this->transaccionApi = new TransaccionApi();
                $this->transaccionApi->setTransaccionId('ROLLBACK' . $TransactionId);
                $this->transaccionApi->setTipo("ROLLBACK");
                $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
                $this->transaccionApi->setTValue(json_encode($datos));
                $this->transaccionApi->setUsucreaId(0);
                $this->transaccionApi->setUsumodifId(0);
                $this->transaccionApi->setValor($RollbackAmount);


                $TransaccionApi2 = new TransaccionApi("", $RefTransactionId, $Proveedor->getProveedorId()); //TransaccionApi Anterior DEBIT


                $UsuarioMandante2 = new UsuarioMandante($TransaccionApi2->getUsuarioId());

                if ($UsuarioMandante2->usumandanteId != $this->externalId) {
                    throw new Exception("El UserId es diferente de UserId de la apuesta que debe cancelarse", "30015");
                }

                $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());
                $TransaccionJuego = new TransaccionJuego("", "THUNDERKICK" . $RoundId);


                if ($TransaccionJuego->getProductoId() != $ProductoMandante->getProdmandanteId()) {
                    throw new Exception("Game was not found for the roundId", "30014");
                }

                if (strpos($TransaccionApi2->getTipo(), 'DEBIT') !== false) {
                    $this->transaccionApi->setIdentificador($TransaccionApi2->getIdentificador());
                    $AllowCreditTransaction = false;
                } else {
                    if (strpos($TransaccionApi2->getTipo(), 'CREDIT') !== false) {
                        $this->transaccionApi->setIdentificador($TransaccionApi2->getIdentificador());
                        $AllowCreditTransaction = true;
                    } else {
                        throw new Exception("Transaccion no es Debit ni Credit", "10006");
                    }
                }

                $this->transaccionApi->setTransaccionId('ROLLBACK' . $RefTransactionId);
                //  Verificamos que la transaccionId no se haya procesado antes
                if ($this->transaccionApi->existsTransaccionIdAndProveedor("OK")) {
                    //  Si la transaccionId ha sido procesada, reportamos el error
                    throw new Exception("Transaccion ya procesada", "10001");
                }
                $this->transaccionApi->setTransaccionId('ROLLBACK' . $TransactionId);

                $Game = new Game();

                $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false, $RefTransactionId, true, false, $AllowCreditTransaction, true);

                $this->transaccionApi = $responseG->transaccionApi;
                $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

                $accountType = "REAL";

                $respuesta = array(
                    "balances" => array(
                        "moneyAccounts" => [
                            array(
                                "balance" => array(
                                    "amount" => floatval($saldo),
                                    "currency" => $responseG->moneda
                                ),
                                "accountId" => $UsuarioToken->usuarioId,
                                "accountType" => $accountType
                            ),

                        ]
                    ),
                    "extRollbackTransactionId" => $responseG->transaccionId
                );

                /*  Guardamos la Transaccion Api necesaria de estado OK   */
                $this->transaccionApi->setRespuesta($respuesta);
                $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                $TransaccionApiMySqlDAO->update($this->transaccionApi);
                $TransaccionApiMySqlDAO->getTransaction()->commit();

                $TransaccionJuego = new TransaccionJuego("", "THUNDERKICK" . $RoundId);
                $TransaccionJuego->setTransaccionId("DEL_" . $TransaccionJuego->getTransaccionId());

                $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
                $TransaccionJuegoMySqlDAO->update($TransaccionJuego);
                $TransaccionJuegoMySqlDAO->getTransaction()->commit();

                $TransaccionApi22 = $this->transaccionApi;

                $this->transaccionApi->setTransaccionId('ROLLBACK' . $RefTransactionId);
                $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                $TransaccionApiMySqlDAO->insert($TransaccionApi22);
                $TransaccionApiMySqlDAO->getTransaction()->commit();
            }


            return json_encode($respuesta);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un crédito en la cuenta del usuario.
     *
     * @param string  $gameId           ID del juego.
     * @param float   $creditAmount     Monto a acreditar.
     * @param string  $roundId          ID de la ronda.
     * @param string  $transactionId    ID de la transacción.
     * @param array   $datos            Datos adicionales.
     * @param boolean $isBonus          Indica si es un bono.
     * @param boolean $EndGame          Indica si es el fin del juego.
     * @param boolean $AllowClosedRound Permite rondas cerradas.
     * @param string  $betTransactionId ID de la transacción de apuesta (opcional).
     * @param boolean $DebitAndCredit   Indica si es una transacción combinada.
     *
     * @return string JSON con el resultado del crédito.
     * @throws Exception Si ocurre un error durante el crédito.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos, $isBonus = false, $EndGame = false, $AllowClosedRound = true, $betTransactionId = "", $DebitAndCredit = false)
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


            /*  Obtenemos el Proveedor con el abreviado Thunderkick */
            $Proveedor = new Proveedor("", "THUNDERKICK");

            if ($this->externalId != "") {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante("", $this->externalId);
            }

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
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);
            $this->transaccionApi->setIdentificador("THUNDERKICK" . $roundId);

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            $accountType = "REAL";

            if ($Usuario->test == "N") {
                $accountType = "REAL";
            } elseif ($Usuario->test == "S") {
                $accountType = "REAL";
            }

            $Game = new Game();

            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $EndGame, false, $isBonus, $AllowClosedRound);

            $this->transaccionApi = $responseG->transaccionApi;


            /*  Retornamos el mensaje satisfactorio  */
            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));


            $respuesta = array(
                "balances" => array(
                    "moneyAccounts" => [
                        array(
                            "balance" => array(
                                "amount" => floatval($saldo),
                                "currency" => $responseG->moneda
                            ),
                            "accountId" => $UsuarioToken->usuarioId,
                            "accountType" => $accountType
                        )
                    ]
                ),
                "extWinTransactionId" => $responseG->transaccionId
            );


            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return json_encode($respuesta);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Convierte un error en un formato JSON.
     *
     * @param integer $code    Código del error.
     * @param string  $message Mensaje del error.
     *
     * @return string JSON con el error convertido.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";
        $response = array();
        $Proveedor = new Proveedor("", "THUNDERKICK");
        $Subproveedor = new Subproveedor("", "THUNDERKICK");

        switch ($code) {
            case 10011:

                $codeProveedor = "100"; //OK
                $messageProveedor = "Token was not found."; //OK
                break;

            case 21:

                $codeProveedor = "100"; //OK
                $messageProveedor = "InvalidToken";
                break;

            case 22:

                $codeProveedor = "210"; //OK
                $messageProveedor = "Invalid account.";
                break;

            case 20001:

                $codeProveedor = "200"; //OK
                $messageProveedor = "Not enough money to cover bet";

                if ($this->token != "") {
                    /*  Obtenemos el Usuario Token con el token */
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    /*  Obtenemos el Usuario Mandante con el Usuario Token */
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } else {
                    /*  Obtenemos el Usuario Mandante con el Usuario Token */
                    $UsuarioMandante = new UsuarioMandante($this->externalId);
                }

                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                $accountType = "REAL";

                if ($Usuario->test == "N") {
                    $accountType = "REAL";
                } elseif ($Usuario->test == "S") {
                    $accountType = "REAL";
                }

                $Game = new Game();

                $responseG = $Game->getBalance($UsuarioMandante);

                $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

                $response = array(
                    "errorCode" => $codeProveedor,
                    "errorMessage" => $messageProveedor,
                    "balances" => array(
                        "moneyAccounts" => [
                            array(
                                "balance" => array(
                                    "amount" => floatval($saldo),
                                    "currency" => $responseG->moneda
                                ),
                                "accountId" => $UsuarioToken->usuarioId,
                                "accountType" => $accountType
                            ),

                        ]
                    ),
                );

                header("HTTP/1.1 533 OK");

                break;

            case 29:

                $codeProveedor = "250"; //OK
                $messageProveedor = "Invalid bet transaction.";
                break;


            case 28:

                if ($this->method == 'credit') {
                    $codeProveedor = "260"; //OK
                    $messageProveedor = "Invalid win transaction.";
                } elseif ($this->method == 'rollback') {
                    $codeProveedor = "251"; //OK
                    $messageProveedor = "Rollback bet transaction.";
                } elseif ($this->method == 'debit') {
                    $codeProveedor = "250"; //OK
                    $messageProveedor = "Invalid bet transaction.";
                }

                if ($this->token != "") {
                    /*  Obtenemos el Usuario Token con el token */
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    /*  Obtenemos el Usuario Mandante con el Usuario Token */
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } else {
                    /*  Obtenemos el Usuario Mandante con el Usuario Token */
                    $UsuarioMandante = new UsuarioMandante($this->externalId);
                }


                $accountType = "REAL";

                $Game = new Game();

                $responseG = $Game->getBalance($UsuarioMandante);

                $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

                $response = array(
                    "balances" => array(
                        "moneyAccounts" => [
                            array(
                                "balance" => array(
                                    "amount" => floatval($saldo),
                                    "currency" => $responseG->moneda
                                ),
                                "accountId" => $UsuarioToken->usuarioId,
                                "accountType" => $accountType
                            )
                        ]
                    )
                );

                if ($this->method == "rollback") {
                    $response['extRollbackTransactionId'] = null;
                    header("HTTP/1.1 200 OK");
                } elseif ($this->method == "credit") {
                    $response['extWinTransactionId'] = null;
                    header("HTTP/1.1 533 OK");
                } elseif ($this->method == "debit") {
                    $response['extBetTransactionId'] = null;
                    header("HTTP/1.1 533 OK");
                }


                break;

            case 10001:

                if ($this->method == 'rollback') {
                    $transaccionId = 'ROLLBACK' . $this->rollbackTransactionId . "_" . '0' . "_" . $Subproveedor->getSubproveedorId();
                } else {
                    $transaccionId = $this->transaccionApi->transaccionId . "_" . $Subproveedor->getSubproveedorId();
                }

                $TransjuegoLog = new TransjuegoLog("", "", "", $transaccionId, $Subproveedor->getSubproveedorId());


                if ($this->token != "") {
                    /*  Obtenemos el Usuario Token con el token */
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    /*  Obtenemos el Usuario Mandante con el Usuario Token */
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } else {
                    /*  Obtenemos el Usuario Mandante con el Usuario Token */
                    $UsuarioMandante = new UsuarioMandante($this->externalId);
                }


                $accountType = "REAL";

                $Game = new Game();

                $responseG = $Game->getBalance($UsuarioMandante);

                $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

                $response = array(
                    "balances" => array(
                        "moneyAccounts" => [
                            array(
                                "balance" => array(
                                    "amount" => floatval($saldo),
                                    "currency" => $responseG->moneda
                                ),
                                "accountId" => $UsuarioToken->usuarioId,
                                "accountType" => $accountType
                            )
                        ]
                    )
                );


                if ($this->transaccionApi->tipo == "ROLLBACK") {
                    $response['extRollbackTransactionId'] = $TransjuegoLog->transjuegologId;
                    header("HTTP/1.1 200 OK");
                } elseif ($this->transaccionApi->tipo == "DEBIT") {
                    $response['extBetTransactionId'] = $TransjuegoLog->transjuegologId;
                    header("HTTP/1.1 200 OK");
                } elseif ($this->transaccionApi->tipo == "CREDIT") {
                    $response['extWinTransactionId'] = $TransjuegoLog->transjuegologId;
                    header("HTTP/1.1 200 OK");
                }


                break;

            case 10004:

                $codeProveedor = "251";
                $messageProveedor = "Rollback bet transaction.";
                break;

            case 10005:

                $codeProveedor = "251";
                $messageProveedor = "Rollback bet transaction.";

                if ($this->token != "") {
                    /*  Obtenemos el Usuario Token con el token */
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    /*  Obtenemos el Usuario Mandante con el Usuario Token */
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } else {
                    /*  Obtenemos el Usuario Mandante con el Usuario Token */
                    $UsuarioMandante = new UsuarioMandante($this->externalId);
                }

                $accountType = "REAL";

                $Game = new Game();

                $responseG = $Game->getBalance($UsuarioMandante);

                $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

                $response = array(
                    "balances" => array(
                        "moneyAccounts" => [
                            array(
                                "balance" => array(
                                    "amount" => floatval($saldo),
                                    "currency" => $responseG->moneda
                                ),
                                "accountId" => $UsuarioToken->usuarioId,
                                "accountType" => $accountType
                            )
                        ]
                    )
                );

                if ($this->method == 'rollback') {
                    $response['extRollbackTransactionId'] = null;
                }

                break;


            case 10014:
                $codeProveedor = "100";
                $messageProveedor = "General Error. (" . $code . ")";
                break;


            case 10010:
                $codeProveedor = "100";
                $messageProveedor = "General Error System. (" . $code . ")";
                break;

            case 20002:

                $codeProveedor = "100"; //OK
                $messageProveedor = "Token was not found.";
                break;

            default:

                $codeProveedor = "120";
                $messageProveedor = "Internal server error";
                break;
        }


        if ($codeProveedor != "") {
            $respuesta = json_encode(
                array_merge(array(

                    "errorCode" => $codeProveedor,
                    "errorMessage" => $messageProveedor

                ), $response)
            );
        } else {
            $respuesta = json_encode(array_merge($response));
        }

        if ($this->transaccionApi != null) {
            $this->transaccionApi->setTipo("R" . $this->transaccionApi->getTipo());
            $this->transaccionApi->setRespuestaCodigo("ERROR");
            $this->transaccionApi->setRespuesta($respuesta);

            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->insert($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();
        }


        return $respuesta;
    }
}
