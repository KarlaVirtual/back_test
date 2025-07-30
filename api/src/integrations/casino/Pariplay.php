<?php

/**
 * Clase Pariplay para la integración con la API de un proveedor de juegos de casino.
 *
 *  Esta clase contiene métodos para manejar la autenticación, creación de tokens,
 *  gestión de saldos, transacciones de débito, crédito, rollback y finalización de rondas.
 *  También incluye manejo de errores y generación de respuestas en formato JSON.
 *
 * @category Integración
 * @package  Backend\integrations\casino
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */

namespace Backend\integrations\casino;

use Exception;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransaccionApi;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\dto\TransaccionJuego;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransaccionApiMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;

/**
 * Clase que implementa la integración con el proveedor de juegos Pariplay.
 *
 * Esta clase contiene métodos para manejar la autenticación, creación de tokens,
 * gestión de saldos, transacciones de débito, crédito, rollback y finalización de rondas.
 * También incluye manejo de errores y generación de respuestas en formato JSON.
 */
class Pariplay
{
    /**
     * Token utilizado para la autenticación del usuario.
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
     * Firma utilizada para validar la integridad de los datos.
     *
     * @var string
     */
    private $sign;

    /**
     * Objeto que representa la transacción API actual.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * Datos asociados a la operación actual.
     *
     * @var mixed
     */
    private $data;

    /**
     * Indica si ocurrió un error relacionado con el hash.
     *
     * @var boolean
     */
    private $errorHash = false;

    /**
     * Constructor de la clase Pariplay.
     *
     * Inicializa los valores del token, firma, identificador externo y verifica
     * la integridad del hash proporcionado. Si el hash no coincide, se marca un error
     * y se genera una respuesta de error.
     *
     * @param string $token        Token utilizado para la autenticación.
     * @param string $sign         Firma utilizada para validar la integridad de los datos.
     * @param string $external     Identificador externo del usuario (opcional).
     * @param string $hashOriginal Hash original para validar la firma (opcional).
     */
    public function __construct($token, $sign, $external = "", $hashOriginal = "")
    {
        if ( ! defined('JSON_PRESERVE_ZERO_FRACTION')) {
            define('JSON_PRESERVE_ZERO_FRACTION', 1024);
        }

        $this->token = $token;
        $this->sign = $sign;
        $this->externalId = $external;
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
     * Autentica un token y devuelve las credenciales del usuario.
     *
     * @param string $userId ID del usuario.
     * @param string $token  Token de autenticación.
     *
     * @return string Credenciales del usuario en formato JSON.
     */
    public function autchToken($userId, $token)
    {
        try {
            $Proveedor = new Proveedor("", "PARIPLAY");

            try {
                $UsuarioToken = new UsuarioToken($token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } catch (Exception $e) {
                $UsuarioMandante = new UsuarioMandante($userId);
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->usumandanteId);
            }

            $Producto = new Producto($UsuarioToken->productoId);

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $credentials = json_decode($SubproveedorMandantePais->getCredentials());

            return $credentials->USERNAME_API . '_' . $credentials->PASSWORD_API;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Autentica un jugador en la plataforma.
     *
     * @param string $token        Token de autenticación.
     * @param string $PlayerId     ID del jugador.
     * @param string $PlatformType Tipo de plataforma.
     *
     * @return string Respuesta en formato JSON.
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

            $Proveedor = new Proveedor("", "PARIPLAY");

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
            //Caso 2 pariplay, por aqui no pasa la excepcion se lanza en el linea anterior.

            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));
            $return = array(
                "Balance" => $saldo,
                "BonusBalance" => 0.0
            );

            return json_encode($return, JSON_PRESERVE_ZERO_FRACTION);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }

        //error_reporting(E_ALL);
        //ini_set('display_errors', 'ON');

    }

    /**
     * Crea un nuevo token para un jugador.
     *
     * @param string $Token         Token inicial.
     * @param string $PlayerId      ID del jugador.
     * @param string $GameCode      Código del juego.
     * @param string $FinancialMode Modo financiero.
     * @param array  $datos         Datos adicionales.
     *
     * @return string Token generado en formato JSON.
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

            $Proveedor = new Proveedor("", "PARIPLAY");

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
     * Obtiene el balance de un jugador.
     *
     * @param string $playerId ID del jugador.
     *
     * @return string Balance del jugador en formato JSON.
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
            $Proveedor = new Proveedor("", "PARIPLAY");

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
     * Obtiene el balance de un jugador utilizando un token.
     *
     * @param string $token    Token de autenticación.
     * @param string $PlayerId ID del jugador.
     *
     * @return string Balance del jugador en formato JSON.
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

            $Proveedor = new Proveedor("", "PARIPLAY");

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
                "Balance" => $saldo,
                "BonusBalance" => 0.0
            );
            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza una transacción de débito.
     *
     * @param string  $gameId           ID del juego.
     * @param float   $debitAmount      Monto a debitar.
     * @param string  $roundId          ID de la ronda.
     * @param string  $transactionId    ID de la transacción.
     * @param boolean $EndGame          Indica si la ronda ha terminado.
     * @param array   $datos            Datos adicionales.
     * @param boolean $AllowClosedRound Permite rondas cerradas.
     * @param boolean $DebitAndCredit   Indica si es una transacción combinada.
     *
     * @return string Respuesta de la transacción en formato JSON.
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

        $this->method = 'reserve';

        $this->data = $datos;

        try {
            if ( ! is_numeric($this->externalId)) {
                throw new Exception("InvalidUserId", "22");
            }

            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado PARIPLAY */
            $Proveedor = new Proveedor("", "PARIPLAY");

            if ($this->externalId != "") {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                if ($this->externalId != "") {
                    if ($UsuarioMandante->usumandanteId != $UsuarioToken->usuarioId) {
                        throw new Exception("Usuario no coincide con token", "30012"); //Caso 206 de Pariplay

                    }
                }
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
            $this->transaccionApi->setIdentificador("PARIPLAY" . $roundId);


            if ($EndGame === true && $AllowClosedRound === false || $DebitAndCredit === true) {
                if (true) {
                    try {
                        $TransaccionApi2 = new TransaccionApi("", "ENDROUND" . $roundId, $Proveedor->getProveedorId(), "OK");
                        if ($DebitAndCredit === true) {
                            //  Verificamos que la transaccionId no se haya procesado antes
                            if ($this->transaccionApi->existsTransaccionIdAndProveedor("OK")) {
                                //  Si la transaccionId ha sido procesada, reportamos el error
                                throw new Exception("Transaccion ya procesada", "10001");
                            }
                            throw new Exception("The debit or credit was already settled", "10016");
                        }

                        throw new Exception("La ronda ya ha sido finalizada", "30017");
                    } catch (Exception $e) {
                        if ($e->getCode() == '30017' || $e->getCode() == '10016' || $e->getCode() == '10001') {
                            throw  $e;
                        }
                    }
                }
            }

            if ($AllowClosedRound === false) {
                try {
                    $TransaccionApi3 = new TransaccionJuego("", "PARIPLAY" . $roundId, $transactionId);
                    if ($TransaccionApi3->getEstado() == "I") {
                        throw new Exception("La ronda ya ha sido cancelada", "30018");
                    }
                } catch (Exception $e) {
                    if ($e->getCode() == '30018') {
                        throw $e;
                    }
                }
            }

            $Game = new Game();

            $isfreeSpin = false;
            if (floatval($debitAmount) == 0) {
                $isfreeSpin = true;
            }

            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $isfreeSpin);

            $this->transaccionApi = $responseG->transaccionApi;

            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

            $respuesta = json_encode(array(
                "Balance" => $saldo,
                "BonusBalance" => 0.0,
                "TransactionId" => $responseG->transaccionId
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
     * @param string  $token             Token de autenticación.
     * @param string  $RefTransactionId  ID de la transacción de referencia.
     * @param string  $GameCode          Código del juego.
     * @param string  $PlayerId          ID del jugador.
     * @param string  $RoundId           ID de la ronda.
     * @param boolean $CancelEntireRound Indica si se cancela toda la ronda.
     * @param string  $TransactionId     ID de la transacción.
     * @param string  $Reason            Razón del rollback.
     * @param float   $RollbackAmount    Monto del rollback.
     * @param array   $datos             Datos adicionales.
     * @param boolean $DebitAndCredit    Indica si es una transacción combinada.
     *
     * @return string Respuesta del rollback en formato JSON.
     */
    public function Rollback($token, $RefTransactionId, $GameCode, $PlayerId, $RoundId, $CancelEntireRound = false, $TransactionId, $Reason, $RollbackAmount, $datos, $DebitAndCredit = false)
    {
        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }

        $this->method = 'cancelReserve';

        $this->data = $datos;

        try {
            if ( ! is_numeric($this->externalId)) {
                throw new Exception("InvalidUserId", "22");
            }

            /*  Obtenemos el Proveedor con el abreviado PARIPLAY */
            $Proveedor = new Proveedor("", "PARIPLAY");

            if ($this->externalId != "") {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                if ($this->externalId != "") {
                    if ($UsuarioMandante->usumandanteId != $UsuarioToken->usuarioId) {
                        throw new Exception("Usuario no coincide con token", "30012");
                    }
                }
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            if ($CancelEntireRound) {
                $TransaccionJuego = new TransaccionJuego("", "PARIPLAY" . $RoundId);

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

                    $transaccionNoExiste = false;

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

                    $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante(), '', $UsuarioMandante->paisId);
                    $TransaccionJuego = new TransaccionJuego("", "PARIPLAY" . $RoundId);


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

                    $respuesta = json_encode(array(
                        "Balance" => $saldo, //OK
                        "BonusBalance" => 0.0, //OK
                        "TransactionId" => $responseG->transaccionId //OK
                    ));

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
                $TransaccionJuego = new TransaccionJuego("", "PARIPLAY" . $RoundId);
                $TransaccionJuego->setTransaccionId("DEL_DEL_" . $TransaccionJuego->getTransaccionId());

                $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
                $TransaccionJuegoMySqlDAO->update($TransaccionJuego);
                $TransaccionJuegoMySqlDAO->getTransaction()->commit();
            } else {
                $transaccionNoExiste = false;

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

                $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante(), '', $UsuarioMandante->paisId);
                $TransaccionJuego = new TransaccionJuego("", "PARIPLAY" . $RoundId);

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

                $respuesta = json_encode(array(
                    "Balance" => $saldo, //OK
                    "BonusBalance" => 0.0, //OK
                    "TransactionId" => $responseG->transaccionId //OK
                ));

                /*  Guardamos la Transaccion Api necesaria de estado OK   */
                $this->transaccionApi->setRespuesta($respuesta);
                $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                $TransaccionApiMySqlDAO->update($this->transaccionApi);
                $TransaccionApiMySqlDAO->getTransaction()->commit();

                $TransaccionJuego = new TransaccionJuego("", "PARIPLAY" . $RoundId);
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

            return $respuesta;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un rollback de una ronda completa.
     *
     * @param float  $rollbackAmount Monto del rollback.
     * @param string $roundId        ID de la ronda.
     * @param string $transactionId  ID de la transacción.
     * @param string $player         ID del jugador.
     * @param array  $datos          Datos adicionales.
     *
     * @return string Respuesta del rollback en formato JSON.
     */
    public function RollbackRound($rollbackAmount, $roundId, $transactionId, $player, $datos)
    {
        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }

        $this->method = 'cancelReserve';

        $this->data = $datos;

        try {
            /*  Obtenemos el Proveedor con el abreviado PARIPLAY */
            $Proveedor = new Proveedor("", "PARIPLAY");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($rollbackAmount);

            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            $this->transaccionApi->setIdentificador("PARIPLAY" . $roundId);

            /*  Obtenemos el producto con el gameId  */
            //$Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

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
     * Realiza una transacción de crédito.
     *
     * @param string  $gameId           ID del juego.
     * @param float   $creditAmount     Monto a acreditar.
     * @param string  $roundId          ID de la ronda.
     * @param string  $transactionId    ID de la transacción.
     * @param array   $datos            Datos adicionales.
     * @param boolean $isBonus          Indica si es un bono.
     * @param boolean $EndGame          Indica si la ronda ha terminado.
     * @param boolean $AllowClosedRound Permite rondas cerradas.
     *
     * @return string Respuesta de la transacción en formato JSON.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos, $isBonus = false, $EndGame = false, $AllowClosedRound = true)
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
            if ( ! is_numeric($this->externalId)) {
                throw new Exception("InvalidUserId", "22");
            }

            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado PARIPLAY */
            $Proveedor = new Proveedor("", "PARIPLAY");

            if ($this->externalId != "") {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            } else {
                try {
                    $TransaccionJuego = new TransaccionJuego("", "PARIPLAY" . $roundId);
                    $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                } catch (Exception $e) {
                    throw new Exception("Transaccion no existe", "10005");
                }
            }

            $TransaccionJuego = new TransaccionJuego("", "PARIPLAY" . $roundId);

            if ($TransaccionJuego->getUsuarioId() != $this->externalId) {
                throw new Exception("Player Id was not found for the roundId", "30013");
            }

            /*  Obtenemos el producto con el gameId  */
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante(), '', $UsuarioMandante->paisId);

            if ($TransaccionJuego->getProductoId() != $ProductoMandante->getProdmandanteId()) {
                throw new Exception("Game was not found for the roundId", "30014");
            }

            if ($AllowClosedRound === false) {
                try {
                    $TransaccionJuego = new TransaccionJuego("", "PARIPLAY" . $roundId, $transactionId);

                    if ($TransaccionJuego->getEstado() == "I") {
                        if (strpos($TransaccionJuego->getTransaccionId(), "DEL_DEL_") !== false) {
                            throw new Exception("La ronda ya ha sido finalizada", "30021");
                        }
                        throw new Exception("La ronda ya ha sido finalizada", "30017");
                    }
                } catch (Exception $e) {
                    if ($e->getCode() == '30021' || $e->getCode() == '30017') {
                        throw $e;
                    }
                }
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
            $this->transaccionApi->setIdentificador("PARIPLAY" . $roundId);

            $Game = new Game();
            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $EndGame, false, $isBonus, $AllowClosedRound);
            //$responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, true, false, $isBonus);

            $this->transaccionApi = $responseG->transaccionApi;

            /*  Retornamos el mensaje satisfactorio  */
            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

            $respuesta = json_encode(array(
                "Balance" => $saldo,
                "BonusBalance" => 0.0,
                "TransactionId" => $responseG->transaccionId

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
     * @param string $token                    Token de autenticación.
     * @param string $GameCode                 Código del juego.
     * @param string $PlayerId                 ID del jugador.
     * @param string $RoundId                  ID de la ronda.
     * @param string $TransactionId            ID de la transacción.
     * @param array  $TransactionConfiguration Configuración de la transacción.
     * @param array  $datos                    Datos adicionales.
     *
     * @return string Respuesta de la finalización en formato JSON.
     */
    public function EndRound($token, $GameCode, $PlayerId, $RoundId, $TransactionId, $TransactionConfiguration, $datos)
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
            /*  Obtenemos el Proveedor con el abreviado PARIPLAY */
            $Proveedor = new Proveedor("", "PARIPLAY");

            if ($this->externalId != "") {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                if ($this->externalId != "") {
                    if ($UsuarioMandante->usumandanteId != $UsuarioToken->usuarioId) {
                        throw new Exception("Usuario no coincide con token", "30012");
                    }
                }
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            $transaccionNoExiste = false;

            /*  Obtenemos el producto con el gameId  */
            $Producto = new Producto("", $GameCode, $Proveedor->getProveedorId());

            /*  Obtenemos el Proveedor con el abreviado PARIPLAY */
            $Proveedor = new Proveedor("", "PARIPLAY");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId("ENDROUND" . $RoundId);
            $this->transaccionApi->setTipo("ENDROUND");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);
            $this->transaccionApi->setIdentificador("PARIPLAY" . $RoundId);

            if (true) {
                $TransaccionJuego = new TransaccionJuego("", "PARIPLAY" . $RoundId);

                if ($TransaccionJuego->getEstado() == "I") {
                    if (strpos($TransaccionJuego->getTransaccionId(), "DEL_DEL_") !== false) {
                        throw new Exception("La ronda ya ha sido finalizada", "30021");
                    }
                    throw new Exception("La ronda ya ha sido finalizada", "30017");
                }
            }

            $Game = new Game();

            $responseG = $Game->endRound($this->transaccionApi);

            $this->transaccionApi = $responseG->transaccionApi;

            /*  Retornamos el mensaje satisfactorio  */

            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

            $respuesta = json_encode(array(
                "Balance" => $saldo,
                "TransactionId" => $this->transaccionApi->transapiId,
                "BonusBalance" => 0
            ));

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta($respuesta);
            $this->transaccionApi->setIdentificador($TransactionId);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $respuesta;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Verifica un parámetro y devuelve información básica.
     *
     * @param mixed $param Parámetro a verificar.
     *
     * @return string Respuesta en formato JSON.
     */
    public function Check($param)
    {
        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }

        $return = array(
            "nodeId" => 123,
            "param" => $param,
            "sign" => $this->sign
        );

        return json_encode($return);
    }

    /**
     * Convierte un error en una respuesta JSON con el código y mensaje correspondiente.
     *
     * @param integer $code    Código del error.
     * @param string  $message Mensaje del error.
     *
     * @return string Respuesta del error en formato JSON.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";
        $response = array();
        $Proveedor = new Proveedor("", "PARIPLAY");
        $saldo = 0;

        switch ($code) {
            case 10011:
                $codeProveedor = "8"; //OK
                $messageProveedor = "Token was not found."; //OK
                break;

            case 21:
                $codeProveedor = "8"; //OK
                $messageProveedor = "InvalidToken";
                break;

            case 22:
                $codeProveedor = "10"; //OK
                $messageProveedor = "InvalidUserId";
                break;

            case 20001:
                $codeProveedor = "1"; //OK
                $messageProveedor = "Player doesn't have enough funds in order to make a wager.";

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
                    "Error" => array(
                        "ErrorCode" => $codeProveedor,
                        "Balance" => $saldo
                    )
                );

                break;

            case 0:
                $codeProveedor = "900"; //OK
                $messageProveedor = "An unexpected error occurred.";
                break;

            case 26:
                $codeProveedor = "7"; //OK
                $messageProveedor = "Invalid Game";
                break;

            case 27:
                $codeProveedor = "7"; //OK
                $messageProveedor = "Game was not found.";
                break;

            case 28:
                $codeProveedor = "9"; //OK
                $messageProveedor = "Round was not found"; //OK
                break;

            case 29:
                $codeProveedor = "6"; //OK
                $messageProveedor = "Transaction Id was not found.";
                break;

            case 10016:
                $codeProveedor = "17"; //OK
                $messageProveedor = "Round already ended and can't accept any more wagers or winnings.";

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
                    "Balance" => $saldo,
                    "BonusBalance" => 0,
                    "Error" => array(
                        "ErrorCode" => $codeProveedor,
                        "Balance" => $saldo
                    )
                );

                break;

            case 10001:
                $codeProveedor = "11";
                $messageProveedor = "TransactionAlreadySettled";

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
                    "Error" => array(
                        "ErrorCode" => $codeProveedor,
                        "Balance" => $saldo
                    )
                );

                break;

            case 10004:
                $codeProveedor = "18";
                $messageProveedor = "The transaction was already cancelled ";
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
                $messageProveedor = "General Error System. (" . $code . ")";
                break;

            case 20002: //Falta corregir este error.

                $codeProveedor = "8"; //OK
                $messageProveedor = "Token was not found."; //OK
                break;

            case 20003:
                $codeProveedor = "12"; //OK
                $messageProveedor = "AccountIsLocked"; //OK
                break;

            case 20024:
                $codeProveedor = "12"; //OK
                $messageProveedor = "AccountIsLocked"; //OK
                break;

            case 10002:
                $codeProveedor = "3"; //OK
                $messageProveedor = "InvalidNegativeAmount"; //OK
                break;

            case 30012:
                $codeProveedor = "10"; //OK
                $messageProveedor = "UserId does not match token"; //OK
                break;

            case 30013:
                $codeProveedor = "10"; //OK
                $messageProveedor = "Player Id was not found for the roundId.";
                break;

            case 30014:
                $codeProveedor = "7"; //OK
                $messageProveedor = "Game was not found for the roundId.";
                break;

            case 30015:
                $codeProveedor = "10"; //OK
                $messageProveedor = "UserId is different from the Wager UserId that needed to be cancelled.";
                break;

            case 30016:
                $codeProveedor = "8"; //OK
                $messageProveedor = "CancelTransaction for that Bet with a different token";
                break;

            case 30017:
                $codeProveedor = "17"; //OK
                $messageProveedor = "Round already ended and can't accept any more wagers or winnings.";

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
                    "Balance" => $saldo,
                    "BonusBalance" => 0,
                    "Error" => array(
                        "ErrorCode" => $codeProveedor,
                        "Balance" => $saldo
                    )
                );

                break;

            case 30018:
                $codeProveedor = "18"; //OK
                $messageProveedor = "The transaction was already cancelled.";
                break;

            case 10027:
                $codeProveedor = "9";
                $messageProveedor = "Invalid Round";
                break;

            case 30019:
                $codeProveedor = "7"; //OK
                $messageProveedor = "CancelTransaction GameCode is different from Token GameCode.";
                break;

            case 30022:
                $codeProveedor = "17"; //OK
                $messageProveedor = "Round already ended and can't accept any more wagers or winnings.";

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

                $tipo = $this->transaccionApi->getTipo();
                $TransaccionApi = new TransaccionApi("", $this->transaccionApi->getTransaccionId(), $Proveedor->getProveedorId());

                $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

                /*  Retornamos el mensaje satisfactorio  */
                $response = array(
                    "Balance" => $saldo,
                    "BonusBalance" => 0,
                    "Error" => array(
                        "ErrorCode" => $codeProveedor,
                        "Balance" => $saldo
                    )
                );


                break;

            case 30021:
                $codeProveedor = "9"; //OK
                $messageProveedor = "Round was not found";
                break;


            default:
                $codeProveedor = "120";
                $messageProveedor = "Internal server error";
                break;
        }

        if ($codeProveedor != "") {
            $respuesta = json_encode(
                array_merge(
                    $response,
                    array(
                        "Error" => array(
                            "ErrorCode" => $codeProveedor,
                            "Balance" => $saldo
                        )
                    )
                )
            );
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
