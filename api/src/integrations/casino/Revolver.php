<?php

/**
 * Clase principal para la integración con el proveedor REVOLVER.
 *
 *  Esta clase contiene métodos para manejar la autenticación, transacciones
 *  y operaciones relacionadas con el proveedor REVOLVER. Proporciona
 *  funcionalidades como autenticación, creación de tokens, débitos, créditos,
 *  reversión de transacciones y finalización de rondas.
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
use Backend\dto\TransaccionApi;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\dto\Subproveedor;
use Backend\dto\TransaccionJuego;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransaccionApiMySqlDAO;

/**
 * Clase principal para manejar la integración con el proveedor REVOLVER.
 *
 * Esta clase proporciona métodos para realizar operaciones como autenticación,
 * creación de tokens, débitos, créditos, reversión de transacciones y finalización de rondas.
 */
class REVOLVER
{
    /**
     * Token de autenticación utilizado para identificar al usuario.
     *
     * @var string
     */
    private $token;

    /**
     * Identificador externo del jugador.
     *
     * @var string
     */
    private $externalId;

    /**
     * Firma de la solicitud para validación de seguridad.
     *
     * @var string
     */
    private $sign;

    /**
     * Objeto que representa la transacción en la API.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * Datos adicionales relacionados con la operación.
     *
     * @var array
     */
    private $data;

    /**
     * Indica si hubo un error en la validación del hash.
     *
     * @var boolean
     */
    public $errorHash = false;

    /**
     * Método actual que se está ejecutando.
     *
     * @var string|boolean
     */
    public $method = false;

    /**
     * Constructor de la clase REVOLVER.
     *
     * @param string $token        Token de autenticación.
     * @param string $playerId     ID del jugador.
     * @param string $sign         Firma de la solicitud.
     * @param string $hashOriginal Hash original para validación.
     * @param string $requestOrder Orden de la solicitud.
     */
    public function __construct($token, $playerId = "", $sign, $hashOriginal = "", $requestOrder)
    {
        $this->token = $token;
        $this->externalId = $playerId;
        $this->sign = $sign;

        $Proveedor = new Proveedor("", "REVOLVER");

        if ($token != "") {
            try {
                $UsuarioToken = new UsuarioToken($token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } catch (Exception $e) {
                $UsuarioMandante = new UsuarioMandante($playerId);
            }
        } else {
            $UsuarioMandante = new UsuarioMandante($playerId);
            $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->usumandanteId);
        }

        try {
            $Producto = new Producto($UsuarioToken->productoId);
            $subproveedorId = $Producto->subproveedorId;
        } catch (Exception $e) {
            $Subproveedor = new Subproveedor('', 'REVOLVER');
            $subproveedorId = $Subproveedor->subproveedorId;
        }

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $SECRET_KEY = $credentials->SECRET_KEY;
        $hashOriginal = sha1($requestOrder . $SECRET_KEY);

        if ($this->sign != $hashOriginal) {
            $this->errorHash = true;
            try {
                throw new Exception("Wrong Signature", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }
    }

    /**
     * Autentica al usuario y devuelve información del jugador.
     *
     * @return string JSON con los datos del jugador y el estado de la sesión.
     */
    public function Authentication()
    {
        $this->method = 'authenticate';

        try {
            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            $Proveedor = new Proveedor("", "REVOLVER");

            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            }

            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $saldo = intval(floatval(round($responseG->saldo, 2) * 100));

            $return = array(
                "code" => 200,
                "data" => array(
                    "playerId" => $responseG->usuarioId,
                    "currency" => $responseG->moneda,
                    "language" => $responseG->idioma,
                    "nickname" => $responseG->usuario,
                    "balance" => $saldo,
                    "countryCode" => $responseG->paisIso2,
                    "sessionState" => $this->token
                ),
                "message" => "Success"
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Crea un token para el jugador y lo asocia a un juego.
     *
     * @param string $Token         Token a crear.
     * @param string $PlayerId      ID del jugador.
     * @param string $GameCode      Código del juego.
     * @param string $FinancialMode Modo financiero.
     * @param array  $datos         Datos adicionales.
     *
     * @return string JSON con el token creado.
     */
    public function CreateToken($Token, $PlayerId, $GameCode, $FinancialMode, $datos)
    {
        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            $Proveedor = new Proveedor("", "REVOLVER");

            /*  Obtenemos el Usuario Mandante con el Usuario externalId */
            $UsuarioMandante = new UsuarioMandante($this->externalId);

            $isFun = true;

            $usumandanteId = $UsuarioMandante->usumandanteId;

            $migameid = $GameCode;

            try {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId);
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
     * Obtiene el balance del jugador.
     *
     * @param string $PlayerId ID del jugador.
     *
     * @return string JSON con el balance del jugador.
     */
    public function getBalance($PlayerId)
    {
        $this->method = 'balance';
        try {
            if ($PlayerId == "") {
                throw new Exception("Token vacio", "10011");
            }

            $Proveedor = new Proveedor("", "REVOLVER");

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

            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);

            $saldo = intval(floatval(round($responseG->saldo, 2) * 100));

            $return = array(
                "code" => 200,
                "data" => array(
                    "balance" => $saldo,
                    "sessionState" => $this->token
                ),
                "message" => "Success"
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un débito en la cuenta del jugador.
     *
     * @param string  $gameId           ID del juego.
     * @param float   $debitAmount      Monto a debitar.
     * @param string  $roundId          ID de la ronda.
     * @param string  $transactionId    ID de la transacción.
     * @param boolean $EndGame          Indica si la ronda ha terminado.
     * @param array   $datos            Datos adicionales.
     * @param float   $amount           Monto de la transacción.
     * @param boolean $AllowClosedRound Permitir rondas cerradas.
     * @param boolean $DebitAndCredit   Indica si es una operación combinada de débito y crédito.
     *
     * @return string JSON con el resultado de la operación.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $EndGame, $datos, $amount, $AllowClosedRound = false, $DebitAndCredit = false)
    {
        $this->method = 'reserve';
        $this->data = $datos;

        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado REVOLVER */
            $Proveedor = new Proveedor("", "REVOLVER");

            if ($this->externalId != "") {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
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
            $this->transaccionApi->setIdentificador("REVOLVER" . $roundId);

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
                    $TransaccionApi3 = new TransaccionJuego("", "REVOLVER" . $roundId, $transactionId);
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

            $saldo = intval(floatval(round($responseG->saldo, 2) * 100));

            $return = array(
                "code" => 200,
                "data" => array(
                    "transactionId" => $responseG->transaccionId,
                    "transactionStatus" => 1,
                    "balance" => $saldo
                ),
                "message" => "Success"
            );

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta($return);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Reversa una transacción previamente realizada.
     *
     * @param string $roundId       ID de la ronda.
     * @param string $transactionId ID de la transacción.
     * @param array  $datos         Datos adicionales.
     *
     * @return string JSON con el resultado de la reversión.
     */
    public function Rollback($roundId, $transactionId, $datos)
    {
        $this->method = 'cancelReserve';
        $this->data = $datos;

        try {
            /*  Obtenemos el Proveedor con el abreviado Onetouch */
            $Proveedor = new Proveedor("", "REVOLVER");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);

            /*  Obtenemos el Usuario Mandante con el Usuario Token */
            $UsuarioMandante = new UsuarioMandante($this->externalId);

            try {
                $TransaccionApi2 = new TransaccionApi("", $transactionId, $Proveedor->getProveedorId());
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
            $saldo = intval(floatval(round($responseG->saldo, 2) * 100));

            $respuesta = array(
                "code" => 200,
                "data" => array(
                    "transactionId" => $responseG->transactionId,
                    "transactionStatus" => 3,
                    "balance" => $saldo
                ),
                "message" => "Success"
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
     * Realiza un crédito en la cuenta del jugador.
     *
     * @param string  $gameId           ID del juego.
     * @param float   $creditAmount     Monto a acreditar.
     * @param string  $roundId          ID de la ronda.
     * @param string  $transactionId    ID de la transacción.
     * @param array   $datos            Datos adicionales.
     * @param boolean $isBonus          Indica si es un bono.
     * @param boolean $EndGame          Indica si la ronda ha terminado.
     * @param boolean $AllowClosedRound Permitir rondas cerradas.
     *
     * @return string JSON con el resultado de la operación.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos, $isBonus = false, $EndGame = false, $AllowClosedRound = true)
    {
        $this->method = 'release';
        $this->data = $datos;

        try {
            if ( ! is_numeric($this->externalId)) {
                throw new Exception("InvalidUserId", "22");
            }

            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado REVOLVER */
            $Proveedor = new Proveedor("", "REVOLVER");

            /*  Obtenemos el Usuario Mandante con el Usuario Token */
            $UsuarioMandante = new UsuarioMandante($this->externalId);

            $TransaccionJuego = new TransaccionJuego("", "REVOLVER" . $roundId);

            if ($TransaccionJuego->getUsuarioId() != $this->externalId) {
                throw new Exception("Player Id was not found for the roundId", "30013");
            }

            /*  Obtenemos el producto con el gameId  */
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante(), '', $UsuarioMandante->getPaisId());

            if ($TransaccionJuego->getProductoId() != $ProductoMandante->getProdmandanteId()) {
                throw new Exception("Game was not found for the roundId", "30014");
            }

            if ($AllowClosedRound === false) {
                try {
                    $TransaccionJuego = new TransaccionJuego("", "REVOLVER" . $roundId, $transactionId);

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
            $this->transaccionApi->setIdentificador("REVOLVER" . $roundId);

            $Game = new Game();
            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $EndGame, false, $isBonus, $AllowClosedRound);

            $this->transaccionApi = $responseG->transaccionApi;

            /*  Retornamos el mensaje satisfactorio  */
            $saldo = intval(floatval(round($responseG->saldo, 2) * 100));

            $return = array(
                "code" => 200,
                "data" => array(
                    "transactionId" => $responseG->transaccionId,
                    "transactionStatus" => 1,
                    "balance" => $saldo
                ),
                "message" => "Success"
            );

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta($return);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return json_encode($return);
        } catch (Exception $e) {
            if ($_ENV['debug']) {
                print_r($e);
            }
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
     * @return string JSON con el resultado de la operación.
     */
    public function EndRound($token, $GameCode, $PlayerId, $RoundId, $TransactionId, $TransactionConfiguration, $datos)
    {
        $this->method = 'release';
        $this->data = $datos;

        try {
            /*  Obtenemos el Proveedor con el abreviado REVOLVER */
            $Proveedor = new Proveedor("", "REVOLVER");

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

            /*  Obtenemos el Proveedor con el abreviado REVOLVER */
            $Proveedor = new Proveedor("", "REVOLVER");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId("ENDROUND" . $RoundId);
            $this->transaccionApi->setTipo("ENDROUND");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);
            $this->transaccionApi->setIdentificador("REVOLVER" . $RoundId);

            if (true) {
                $TransaccionJuego = new TransaccionJuego("", "REVOLVER" . $RoundId);

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
     * Convierte errores en respuestas JSON manejables.
     *
     * @param integer $code    Código de error.
     * @param string  $message Mensaje de error.
     *
     * @return string JSON con la información del error.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";
        $response = array();
        $Proveedor = new Proveedor("", "REVOLVER");
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
                $codeProveedor = ""; //OK
                $messageProveedor = "";

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

                /*  Retornamos el mensaje satisfactorio  */
                $saldo = intval(floatval(round($responseG->saldo, 2) * 100));

                $response = array(
                    "code" => 1503,
                    "data" => array(
                        "transactionId" => $responseG->transaccionId,
                        "transactionStatus" => 1,
                        "balance" => $saldo
                    ),
                    "message" => "Insufficient Funds"
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

                /*  Retornamos el mensaje satisfactorio  */
                $response = array(
                    "Balance" => $saldo,
                    "BonusBalance" => 0,
                    "Error" => array(
                        "code" => $codeProveedor,
                        "Balance" => $saldo
                    )
                );
                break;

            case 10001:
                $codeProveedor = "";
                $messageProveedor = "";

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

                /*  Retornamos el mensaje satisfactorio  */
                $response = array(
                    "code" => "200",
                    "data" => array(
                        "transactionId" => $responseG->transaccionId,
                        "transactionStatus" => 2,
                        "balance" => $saldo
                    ),
                    "message" => "Transaction Declined"
                );
                break;

            case 10004:
                $codeProveedor = "18";
                $messageProveedor = "The transaction was already cancelled ";
                break;

            case 10005:
                $codeProveedor = "";
                $messageProveedor = "";

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

                $response = array(
                    "code" => 200,
                    "data" => array(
                        "transactionId" => $responseG->transactionId,
                        "transactionStatus" => 3,
                        "balance" => $saldo
                    ),
                    "message" => "Bet Transaction not found"
                );
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
                $codeProveedor = " ";
                $messageProveedor = " ";

                $response = array(
                    "code" => 1403,
                    "data" => array(
                        "sessionState" => $this->token
                    ),
                    "message" => "Wrong Signature"
                );
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
                $codeProveedor = ""; //OK
                $messageProveedor = ""; //OK

                $response = array(
                    "code" => "199",
                    "data" => array(
                        "transactionStatus" => 2
                    ),
                    "message" => "Invalid Negative Amount"
                );
                break;

            case 30012:
                $codeProveedor = ""; //OK
                $messageProveedor = ""; //OK

                $response = array(
                    "code" => 1501,
                    "data" => array(
                        "balance" => 0,
                        "sessionState" => $this->token
                    ),
                    "message" => "User Not Found"
                );
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

                /*  Retornamos el mensaje satisfactorio  */
                $response = array(
                    "Balance" => $saldo,
                    "BonusBalance" => 0,
                    "Error" => array(
                        "code" => $codeProveedor,
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

                $tipo = $this->transaccionApi->getTipo();
                $TransaccionApi = new TransaccionApi("", $this->transaccionApi->getTransaccionId(), $Proveedor->getProveedorId());

                $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

                /*  Retornamos el mensaje satisfactorio  */
                $response = array(
                    "Balance" => $saldo,
                    "BonusBalance" => 0,
                    "Error" => array(
                        "code" => $codeProveedor,
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
                            "code" => $codeProveedor,
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
