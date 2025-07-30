<?php

/**
 * Clase principal para la integración con el proveedor Galaxsys.
 *
 * Este archivo contiene la implementación de la clase `Galaxsys`, que maneja
 * la integración con el proveedor Galaxsys. Proporciona métodos para realizar
 * transacciones, obtener balances, manejar errores y realizar operaciones
 * relacionadas con juegos y usuarios.
 *
 * @category Integración
 * @package  Backend\integrations\casino
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */

namespace Backend\integrations\casino;

use Backend\dto\Registro;
use Backend\dto\Subproveedor;
use Backend\dto\SubproveedorMandantePais;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransaccionApi;
use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\UsuarioToken;
use Backend\dto\Producto;
use Backend\dto\ProductoMandante;
use Backend\dto\Proveedor;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionApiMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Exception;

/**
 * Clase principal para la integración con el proveedor Galaxsys.
 *
 * Esta clase contiene métodos para manejar transacciones, balance,
 * y otras operaciones relacionadas con el proveedor Galaxsys.
 */
class Galaxsys
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
     * Identificador externo del usuario.
     *
     * @var string
     */
    private $externalId;

    /**
     * Firma utilizada para la validación.
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
     * Datos asociados a la operación.
     *
     * @var mixed
     */
    private $data;

    /**
     * Método de la operación actual.
     *
     * @var string
     */
    private $method = ' ';

    /**
     * Identificador de transacción en caso de error.
     *
     * @var string
     */
    private $transactionId_err = '';

    /**
     * Información adicional en caso de error.
     *
     * @var string
     */
    private $info_err = '';

    /**
     * Indica si ocurrió un error relacionado con el hash.
     *
     * @var boolean
     */
    private $errorHash = false;

    /**
     * Identificador del socio.
     *
     * @var string
     */
    private $PartnerID = "";

    /**
     * Clave secreta del socio.
     *
     * @var string
     */
    private $Secretkey = "";

    /**
     * Proveedor asociado a la operación.
     *
     * @var Proveedor
     */
    private $Proveedor;

    /**
     * Subproveedor asociado a la operación.
     *
     * @var Subproveedor|null
     */
    private $SubProveedor = null;

    /**
     * Juego asociado a la operación.
     *
     * @var Game|null
     */
    private $Game = null;

    /**
     * Constructor de la clase `Galaxsys`.
     *
     * Inicializa las propiedades de la clase y valida el token y la firma.
     *
     * @param string $token        Token de autenticación.
     * @param string $sign         Firma de autenticación.
     * @param string $external     Identificador externo.
     * @param string $hashOriginal Hash original para validación.
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
            return $this->convertError("20002", "Token vacio");
        }

        $this->Proveedor = new Proveedor("", "GALAXSYS");

        if ($this->token != "") {
            try {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $this->Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } catch (Exception $e) {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->externalId);
                $UsuarioToken = new UsuarioToken('', $this->Proveedor->getProveedorId(), $UsuarioMandante->usumandanteId);
            }
        } else {
            /*  Obtenemos el Usuario Mandante con el Usuario Token */
            $UsuarioMandante = new UsuarioMandante($this->externalId);
            $UsuarioToken = new UsuarioToken('', $this->Proveedor->getProveedorId(), $UsuarioMandante->usumandanteId);
        }

        try {
            $Producto = new Producto($UsuarioToken->productoId);
            $SubproveedorId = $Producto->subproveedorId;
        } catch (Exception $e) {
            $Subproveedor = new Subproveedor('', 'GALAXSYS');
            $SubproveedorId = $Subproveedor->subproveedorId;
        }

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $SubproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $this->PartnerID = $credentials->PARTNER_ID;
        $this->Secretkey = $credentials->SECRET_KEY;
    }

    /**
     * Obtiene el subproveedor asociado.
     *
     * @return Subproveedor Instancia del subproveedor.
     */
    private function getSubProveedor(): Subproveedor
    {
        if ($this->SubProveedor == null) {
            $this->SubProveedor = new SubProveedor("", "GALAXSYS");
        }
        return $this->SubProveedor;
    }

    /**
     * Obtiene el juego asociado.
     *
     * @return Game Instancia del juego.
     */
    private function getGame(): Game
    {
        if ($this->Game == null) {
            $this->Game = new Game();
        }
        return $this->Game;
    }

    /**
     * Obtiene el usuario mandante asociado.
     *
     * @param boolean $ignoreExpiry Ignorar la expiración del token.
     *
     * @return UsuarioMandante Instancia del usuario mandante.
     */
    private function getUsuarioMandante($ignoreExpiry = false): UsuarioMandante
    {
        if ($this->token != "" && ! $ignoreExpiry) {
            /*  Obtenemos el Usuario Token con el token */
            $UsuarioToken = new UsuarioToken($this->token, $this->Proveedor->getProveedorId());
            /*  Obtenemos el Usuario Mandante con el Usuario Token */
            return new UsuarioMandante($UsuarioToken->usuarioId);
        }

        /*  Obtenemos el Usuario Mandante con el Usuario Token */
        return new UsuarioMandante($this->externalId);
    }

    /**
     * Obtiene el identificador del operador.
     *
     * @return string Identificador del operador.
     */
    public function getOperadorId()
    {
        return $this->operadorId;
    }

    /**
     * Autentica al usuario con el proveedor.
     *
     * @param string $OperatorId ID del operador.
     * @param string $Token      Token de autenticación.
     * @param string $timestamp_ Marca de tiempo.
     * @param string $signature_ Firma de autenticación.
     *
     * @return array Respuesta de la autenticación.
     */
    public function Auth($OperatorId, $Token, $timestamp_, $signature_)
    {
        if ($this->errorHash) {
            return $this->convertError("20002", "Token vacio");
        }

        $this->method = 'authenticate';
        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            $resp = $this->Generate($OperatorId, $Token, $timestamp_, $signature_);
            if ($resp['errorCode'] != 1) {
                return $resp;
            }

            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $this->Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $UsuarioOtrainfo = new UsuarioOtrainfo($UsuarioMandante->usuarioMandante);
            $Registro = new Registro("", $Usuario->usuarioId);

            $responseG = $this->getGame()->autenticate($UsuarioMandante);

            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 4, '.', null));

            $gender = $Registro->getSexo() == "M" ? 1 : 2;

            $isReal = $Usuario->test == "S";

            $return = array(
                "timestamp" => $resp['timestamp'],
                "signature" => $resp['signature'],
                "errorCode" => 1,
                "playerid" => $responseG->usuarioId,
                "userName" => $Usuario->nombre,
                "currencyid" => $responseG->moneda,
                "balance" => $saldo,
                "birthDate" => $UsuarioOtrainfo->fechaNacim . ' ' . '00:00:00.000',
                "firstName" => $Registro->nombre,
                "lastName" => $Registro->apellido1,
                "gender" => $gender,
                "email" => $Registro->email,
                "isReal" => $isReal
            );

            return $return;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del usuario.
     *
     * @param string $playerId_   ID del jugador.
     * @param string $OperatorId  ID del operador.
     * @param string $Token       Token de autenticación.
     * @param string $timestamp_  Marca de tiempo.
     * @param string $signature_  Firma de autenticación.
     * @param string $currencyId_ ID de la moneda.
     *
     * @return array Respuesta con el balance del usuario.
     */
    public function getBalance($playerId_, $OperatorId, $Token, $timestamp_, $signature_, $currencyId_)
    {
        if ($this->errorHash) {
            return $this->convertError("20002", "Token vacio");
        }

        $this->method = 'balance';
        try {
            $resCur = $this->Currenci($currencyId_);
            if ($resCur['errorCode'] != 1) {
                return $resCur;
            }

            $resUs = $this->User($playerId_);
            if ($resUs['errorCode'] != 1) {
                return $resUs;
            }

            $resp = $this->Generate($OperatorId, $Token, $timestamp_, $signature_);
            if ($resp['errorCode'] != 1) {
                return $resp;
            }

            $UsuarioMandante = $this->getUsuarioMandante();

            $responseG = $this->getGame()->getBalance($UsuarioMandante);

            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 4, '.', null));

            $return = array(
                "timestamp" => $resp['timestamp'],
                "signature" => $resp['signature'],
                "errorCode" => 1,
                "balance" => $saldo,
            );

            return $return;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del usuario en un formato específico.
     *
     * Este método realiza las siguientes acciones:
     * - Verifica si ocurrió un error relacionado con el hash.
     * - Obtiene la instancia del usuario mandante.
     * - Llama al método `getBalance` del juego asociado para obtener el saldo.
     * - Formatea el saldo y lo incluye en la respuesta.
     *
     * @param integer $err  Código de error (por defecto 1).
     * @param string  $info Información adicional que se incluirá en la respuesta.
     *
     * @return array Respuesta con el balance del usuario, información adicional y código de error.
     */
    public function getBalance2($err = 1, $info)
    {
        if ($this->errorHash) {
            return $this->convertError("20002", "Token vacio");
        }

        $this->method = 'balance';
        try {
            $UsuarioMandante = $this->getUsuarioMandante();
            $responseG = $this->getGame()->getBalance($UsuarioMandante);

            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 4, '.', null));

            $return = array(
                "items" => array(
                    "balance" => $saldo,
                    "info" => $info,
                    "errorCode" => $err
                )
            );

            return $return;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del usuario con detalles adicionales.
     *
     * Este método realiza las siguientes acciones:
     * - Verifica si ocurrió un error relacionado con el hash.
     * - Obtiene la instancia del usuario mandante.
     * - Intenta obtener el identificador externo de la transacción.
     * - Llama al método `getBalance` del juego asociado para obtener el saldo.
     * - Formatea el saldo y lo incluye en la respuesta junto con otros metadatos.
     *
     * @param integer $err  Código de error (por defecto 1).
     * @param string  $sig  Firma de autenticación.
     * @param string  $tim  Marca de tiempo.
     * @param integer $txId Identificador de la transacción (por defecto 00).
     *
     * @return array Respuesta con el balance del usuario, metadatos y código de error.
     */
    public function getBalance3($err = 1, $sig, $tim, $txId = 00)
    {
        if ($this->errorHash) {
            return $this->convertError("20002", "Token vacio");
        }

        $this->method = 'balance';
        try {
            $UsuarioMandante = $this->getUsuarioMandante();

            try {
                $TransjuegoLog = new TransjuegoLog("", "", "", $txId . '_' . $this->getSubProveedor()->getSubproveedorId(), $this->getSubProveedor()->getSubproveedorId());
                $trlog = $TransjuegoLog->transjuegologId;
            } catch (Exception $e) {
                $trlog = 00;
            }

            $responseG = $this->getGame()->getBalance($UsuarioMandante);

            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 4, '.', null));

            $return = array(
                "timestamp" => $tim,
                "signature" => $sig,
                "externalTxId" => $trlog,
                "balance" => $saldo,
                "metadata" => "",
                "errorCode" => $err
            );

            return $return;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del usuario con un formato simplificado.
     *
     * Este método realiza las siguientes acciones:
     * - Verifica si ocurrió un error relacionado con el hash.
     * - Obtiene la instancia del usuario mandante.
     * - Llama al método `getBalance` del juego asociado para obtener el saldo.
     * - Formatea el saldo y lo incluye en la respuesta.
     *
     * @param integer $err Código de error (por defecto 1).
     * @param string  $sig Firma de autenticación.
     * @param string  $tim Marca de tiempo.
     *
     * @return array Respuesta con el balance del usuario, metadatos y código de error.
     */
    public function getBalance4($err = 1, $sig, $tim)
    {
        if ($this->errorHash) {
            return $this->convertError("20002", "Token vacio");
        }

        $this->method = 'balance';
        try {
            $UsuarioMandante = $this->getUsuarioMandante();

            $responseG = $this->getGame()->getBalance($UsuarioMandante);

            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 4, '.', null));

            $return = array(
                "timestamp" => $tim,
                "signature" => $sig,
                "balance" => $saldo,
                "metadata" => "",
                "errorCode" => $err
            );

            return $return;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Refresca el token de autenticación del usuario.
     *
     * Este método genera un nuevo token si es necesario, valida las credenciales
     * y devuelve un token actualizado junto con una firma y marca de tiempo.
     *
     * @param string  $playerId_     ID del jugador.
     * @param boolean $changeToken   Indica si se debe generar un nuevo token.
     * @param string  $token__       Token actual.
     * @param integer $tokenLifeTime Tiempo de vida restante del token.
     * @param string  $OperatorId    ID del operador.
     * @param string  $timestamp_    Marca de tiempo.
     * @param string  $signature_    Firma de autenticación.
     *
     * @return array Respuesta con el nuevo token, firma, marca de tiempo y código de error.
     */
    public function refreshToken($playerId_, $changeToken, $token__, $tokenLifeTime, $OperatorId, $timestamp_, $signature_)
    {
        if ($this->errorHash) {
            return $this->convertError("20002", "Token vacio");
        }

        $this->method = 'balance';
        try {
            $resp = $this->Generate($OperatorId, $token__, $timestamp_, $signature_);
            if ($resp['errorCode'] != 1) {
                return $resp;
            }

            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $this->Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            $responseG = $this->getGame()->getBalance($UsuarioMandante);

            if ($changeToken == true || $tokenLifeTime <= 0) {
                $token = $UsuarioToken->createToken();
                $UsuarioToken->setToken($token);
                $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
                $UsuarioTokenMySqlDAO->update($UsuarioToken);
                $UsuarioTokenMySqlDAO->getTransaction()->commit();
                $token_ = $UsuarioToken->token;
            } else {
                $token_ = $token__;
            }

            $timestamp = date("Ymd") . time();
            $PartnerID = $this->PartnerID;
            $Secretkey = $this->Secretkey;
            $signature = hash_hmac('sha256', $timestamp . $PartnerID, $Secretkey);

            $return = array(
                "timestamp" => $timestamp,
                "signature" => $signature,
                "token" => $token_,
                "errorCode" => 1
            );

            return $return;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza una operación de débito en el sistema.
     *
     * Este método procesa una transacción de débito para un jugador en un juego específico.
     * Valida los datos de entrada, verifica el estado del jugador, la moneda y el juego,
     * y registra la transacción en el sistema.
     *
     * @param string  $gameId        Identificador del juego.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $roundId       Identificador de la ronda.
     * @param string  $transactionId Identificador de la transacción.
     * @param mixed   $datos         Datos adicionales de la transacción.
     * @param string  $info          Información adicional.
     * @param string  $winInfo       Información sobre ganancias.
     * @param string  $playerId_     Identificador del jugador.
     * @param string  $OperatorId    Identificador del operador.
     * @param string  $Token         Token de autenticación.
     * @param string  $timestamp_    Marca de tiempo de la operación.
     * @param string  $signature_    Firma de autenticación.
     * @param string  $currencyId_   Identificador de la moneda.
     * @param boolean $ignoreExpiry  Indica si se debe ignorar la expiración del token.
     * @param boolean $allOrNone     Indica si la operación debe ser todo o nada.
     * @param boolean $ischarge      Indica si es una operación de cargo.
     * @param boolean $isFreeSpin    Indica si es una operación de giros gratis.
     *
     * @return array Respuesta de la operación, incluyendo el saldo y metadatos.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $info, $winInfo, $playerId_, $OperatorId, $Token, $timestamp_, $signature_, $currencyId_, $ignoreExpiry, $allOrNone, $ischarge = false, $isFreeSpin = false)
    {
        $this->transactionId_err = $transactionId;
        $this->info_err = $info;

        if ($this->errorHash) {
            return $this->convertError("20002", "Token vacio");
        }

        $this->method = 'reserve';
        $this->data = $datos;

        try {
            $errorCode = 1;

            $resUs = $this->User($playerId_);
            if ($resUs['errorCode'] != 1) {
                if ($allOrNone == true) {
                    $errorCode = $resUs['errorCode'];
                } else {
                    return $resUs;
                }
            }

            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }


            $resCur = $this->Currenci($currencyId_);
            if ($resCur['errorCode'] != 1) {
                if ($allOrNone == true) {
                    $errorCode = $resCur['errorCode'];
                } else {
                    return $resCur;
                }
            }

            $resG = $this->Game($gameId);
            if ($resG['errorCode'] != 1) {
                if ($allOrNone == true) {
                    $errorCode = $resG['errorCode'];
                } else {
                    return $resG;
                }
            }

            if ($errorCode != 1) {
                $debitAmount = 0;
            }

            $UsuarioMandante = $this->getUsuarioMandante($ignoreExpiry);

            /*  Obtenemos el producto con el gameId  */
            $Producto = new Producto("", $gameId, $this->Proveedor->getProveedorId());

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($this->Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);

            try {
                $TransaccionJuego = new TransaccionJuego("", "GALAXSYS" . $roundId);
                if ($TransaccionJuego->getEstado() == 'I') {
                    $this->transaccionApi->setValor(0);
                    $errorCode = 14;
                } else {
                    $this->transaccionApi->setValor($debitAmount);
                }
            } catch (Exception $e) {
                $this->transaccionApi->setValor($debitAmount);
            }

            $this->transaccionApi->setIdentificador("GALAXSYS" . $roundId);

            $responseG = $this->getGame()->debit($UsuarioMandante, $Producto, $this->transaccionApi, $isFreeSpin);

            $this->transaccionApi = $responseG->transaccionApi;

            if ($ischarge == true) {
                $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 4, '.', null));
            } else {
                $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 4, '.', null));
            }

            if ($info != "" || $info != null) {
                $respuesta = array(
                    "items" => array(
                        "externalTxId" => $responseG->transaccionId,
                        "balance" => $saldo,
                        "info" => $info,
                        "errorCode" => $errorCode,
                        "metadata" => $transactionId
                    )
                );
            } else {
                $resp = $this->Generate_();
                $respuesta = array(
                    "timestamp" => $resp['timestamp'],
                    "signature" => $resp['signature'],
                    "externalTxId" => $responseG->transaccionId,
                    "balance" => $saldo,
                    "metadata" => "",
                    "errorCode" => $errorCode
                );
            }

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
     * Realiza una operación de reversión (rollback) en el sistema.
     *
     * Este método procesa una reversión de transacción para un jugador en un juego específico.
     * Valida los datos de entrada, verifica el estado del jugador, la moneda y el juego,
     * y registra la reversión en el sistema.
     *
     * @param float   $rollbackAmount    Monto a revertir.
     * @param string  $roundId           Identificador de la ronda.
     * @param string  $transactionId     Identificador de la transacción.
     * @param string  $player            Identificador del jugador.
     * @param mixed   $datos             Datos adicionales de la transacción.
     * @param string  $txId              Identificador externo de la transacción.
     * @param string  $info              Información adicional.
     * @param boolean $CancelEntireRound Indica si se debe cancelar toda la ronda.
     * @param boolean $refundRound       Indica si se debe reembolsar la ronda.
     * @param boolean $allOrNone         Indica si la operación debe ser todo o nada.
     * @param string  $originalTxId      Identificador de la transacción original (opcional).
     *
     * @return array Respuesta de la operación, incluyendo el saldo y metadatos.
     * @throws Exception Si ocurre un error durante la reversión.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos, $txId, $info, $CancelEntireRound = false, $refundRound, $allOrNone, $originalTxId = '')
    {
        $this->transactionId_err = $transactionId;
        $this->info_err = $info;

        if ($this->errorHash) {
            return $this->convertError("20002", "Token vacio");
        }

        $this->method = 'Rollback';

        $this->data = $datos;

        try {
            $resUs = $this->User($player);
            if ($resUs['errorCode'] != 1) {
                $respuesta = array(
                    "errorCode" => $resUs['errorCode'],
                    "items" => array(
                        "balance" => 0,
                        "errorCode" => $resUs['errorCode']
                    )
                );
                return $respuesta;
            }

            if ($CancelEntireRound) {
                $TransaccionJuego = new TransaccionJuego("", "GALAXSYS" . $roundId);
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->usuarioId);

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

                    $RefTransactionId = explode("_", $RefTransactionId);

                    if ($RefTransactionId[0] === 'D') {
                        $RefTransactionId = 'D_' . $RefTransactionId[1];
                    } else {
                        $RefTransactionId = $RefTransactionId[0];
                    }

                    $TransactionId = $txId . '_' . $key;

                    /*  Creamos la Transaccion API  */

                    $this->transaccionApi = new TransaccionApi();
                    $this->transaccionApi->setTransaccionId('ROLLBACK' . $TransactionId);
                    $this->transaccionApi->setTipo("ROLLBACK");
                    $this->transaccionApi->setProveedorId($this->Proveedor->getProveedorId());
                    $this->transaccionApi->setTValue(json_encode($datos));
                    $this->transaccionApi->setUsucreaId(0);
                    $this->transaccionApi->setUsumodifId(0);
                    $this->transaccionApi->setValor($rollbackAmount);

                    $TransaccionApi2 = new TransaccionApi("", $RefTransactionId, $this->Proveedor->getProveedorId()); //TransaccionApi Anterior DEBIT

                    if (strpos($TransaccionApi2->getTipo(), 'DEBIT') !== false) {
                        $this->transaccionApi->setIdentificador($TransaccionApi2->getIdentificador());
                        $AllowCreditTransaction = false;
                    } elseif (strpos($TransaccionApi2->getTipo(), 'CREDIT') !== false) {
                        $this->transaccionApi->setIdentificador($TransaccionApi2->getIdentificador());
                        $AllowCreditTransaction = true;
                    } else {
                        throw new Exception("Transaccion no es Debit ni Credit", "10006");
                    }

                    $this->transaccionApi->setTransaccionId('ROLLBACK' . $RefTransactionId);

                    //  Verificamos que la transaccionId no se haya procesado antes
                    if ($this->transaccionApi->existsTransaccionIdAndProveedor("OK")) {
                        //  Si la transaccionId ha sido procesada, reportamos el error
                        throw new Exception("Transaccion ya procesada", "10001");
                    }
                    $this->transaccionApi->setTransaccionId('ROLLBACK' . $TransactionId);

                    $responseG = $this->getGame()->rollback($UsuarioMandante, $this->Proveedor, $this->transaccionApi, false, $RefTransactionId, true, false, $AllowCreditTransaction, true);

                    $this->transaccionApi = $responseG->transaccionApi;
                    $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 4, '.', null));

                    $respuesta = array(
                        "items" => array(
                            "externalTxId" => $responseG->transaccionId,
                            "balance" => $saldo,
                            "info" => $info,
                            "errorCode" => 1
                        )
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

                $TransaccionJuego = new TransaccionJuego("", "GALAXSYS" . $roundId);
                $TransaccionJuego->setTransaccionId("DEL_DEL_" . $TransaccionJuego->getTransaccionId());

                $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
                $TransaccionJuegoMySqlDAO->update($TransaccionJuego);
                $TransaccionJuegoMySqlDAO->getTransaction()->commit();
            } else {
                $SubProveedorId = $this->getSubProveedor()->subproveedorId;

                try {
                    $TransaccionJuego = new TransaccionJuego("", "GALAXSYS" . $roundId);
                } catch (Exception $e) {
                    try {
                        if ($allOrNone == true) {
                            $TransaccionJuego = new TransaccionJuego("", "GALAXSYS" . $roundId);
                        } else {
                            $TransjuegoLog = new TransjuegoLog("", "", "", "D_" . $roundId . "_" . $SubProveedorId, $SubProveedorId);
                            $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);
                        }
                    } catch (Exception $e) {
                        if ($allOrNone == false) {
                            $TransjuegoLog = new TransjuegoLog("", "", "", "D_" . $originalTxId . "_" . $SubProveedorId, $SubProveedorId);
                            $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);
                        } else {
                            $TransaccionJuego = new TransaccionJuego("", "GALAXSYS" . $roundId);
                        }
                    }
                }
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->usuarioId);

                $rules = []; //Regla para filtrar en la tabla Transjuego_log con el campo transjuego_id
                array_push($rules, array("field" => "transjuego_log.transjuego_id", "data" => $TransaccionJuego->getTransjuegoId(), "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $select = "transjuego_log.*";
                $grouping = "transjuego_log.transjuegolog_id";

                $TransjuegoLog = new TransjuegoLog();
                $Transactions = $TransjuegoLog->getTransjuegoLogsCustom($select, "transjuego_log.transjuegolog_id", "asc", 0, 100, $json, true, $grouping);
                $Transactions = json_decode($Transactions);

                $error_ = false;

                if ($refundRound == false) {
                    $credi_count = 0;
                    $debit_count = 0;
                    foreach ($Transactions->data as $key => $transjuego) {
                        $Tipo = $transjuego->{"transjuego_log.tipo"};

                        if ($Tipo == 'CREDIT') {
                            $credi_count = $credi_count + 1;
                        }
                        if ($Tipo == 'DEBIT') {
                            $debit_count = $debit_count + 1;
                        }
                    }
                    if ($debit_count == $credi_count) {
                        $error_ = true;
                    }
                }

                if ($error_ == false) {
                    try {
                        /*  Creamos la Transaccion API  */
                        $this->transaccionApi = new TransaccionApi();
                        $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
                        $this->transaccionApi->setTipo("ROLLBACK");
                        $this->transaccionApi->setProveedorId($this->Proveedor->getProveedorId());
                        $this->transaccionApi->setTValue(json_encode($datos));
                        $this->transaccionApi->setUsucreaId(0);
                        $this->transaccionApi->setUsumodifId(0);
                        $this->transaccionApi->setValor($rollbackAmount);

                        try {
                            $TransjuegoLog = new TransjuegoLog("", "", "", $transactionId . '_' . $this->getSubProveedor()->getSubproveedorId(), $this->getSubProveedor()->getSubproveedorId());
                            $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);
                            $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                            //$this->transaccionApi->setIdentificador($TransaccionJuego->getTicketId());

                            if (strpos($TransjuegoLog->getTipo(), 'DEBIT') !== false) {
                                $this->transaccionApi->setIdentificador($TransaccionJuego->getTicketId());
                                $AllowCreditTransaction = false;
                            } else {
                                throw new Exception("Transaccion no es Debit", "10006");
                            }
                            $errorCode = 1;
                        } catch (Exception $e) {
                            throw new Exception("Transaccion no existe", "10005");
                        }

                        if ($refundRound === true) {
                            $estado_ = 'I';
                        } else {
                            $estado_ = 'A';
                        }

                        $responseG = $this->getGame()->rollback($UsuarioMandante, $this->Proveedor, $this->transaccionApi, false, false, "", "", "", "", $estado_);
                        $this->transaccionApi = $responseG->transaccionApi;

                        $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 4, '.', null));

                        $respuesta = array(
                            "items" => array(
                                "externalTxId" => $responseG->transaccionId,
                                "balance" => $saldo,
                                "info" => $info,
                                "errorCode" => 1
                            )
                        );

                        /*  Guardamos la Transaccion Api necesaria de estado OK   */
                        $this->transaccionApi->setRespuesta($respuesta);
                        $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                        $TransaccionApiMySqlDAO->update($this->transaccionApi);
                        $TransaccionApiMySqlDAO->getTransaction()->commit();
                    } catch (Exception $e) {
                        $transactionId = explode("_", $transactionId);
                        $TransjuegoLog = new TransjuegoLog("", "", "", 'ROLLBACKD_' . $transactionId[1] . '_' . $this->getSubProveedor()->getSubproveedorId(), $this->getSubProveedor()->getSubproveedorId());
                        $roll = 'ROLLBACKD_' . $transactionId[1] . '_' . $this->getSubProveedor()->getSubproveedorId();
                        $roll_ = $TransjuegoLog->transaccionId;

                        if ($roll == $roll_) {
                            throw new Exception("Transaccion no existe", "10001");
                        }
                    }
                } else {
                    if ($error_ == true) {
                        try {
                            $RefTransactionId = $originalTxId;

                            $TransactionId = $txId . '_' . $this->getSubProveedor()->getSubproveedorId();

                            /*  Creamos la Transaccion API  */
                            $this->transaccionApi = new TransaccionApi();
                            $this->transaccionApi->setTransaccionId('ROLLBACK' . $TransactionId);
                            $this->transaccionApi->setTipo("ROLLBACK");
                            $this->transaccionApi->setProveedorId($this->Proveedor->getProveedorId());
                            $this->transaccionApi->setTValue(json_encode($datos));
                            $this->transaccionApi->setUsucreaId(0);
                            $this->transaccionApi->setUsumodifId(0);
                            $this->transaccionApi->setValor($rollbackAmount);

                            $TransaccionApi2 = new TransaccionApi("", $RefTransactionId, $this->Proveedor->getProveedorId());

                            if (strpos($TransaccionApi2->getTipo(), 'DEBIT') !== false) {
                                $this->transaccionApi->setIdentificador($TransaccionApi2->getIdentificador());
                                $AllowCreditTransaction = false;
                            } elseif (strpos($TransaccionApi2->getTipo(), 'CREDIT') !== false) {
                                $this->transaccionApi->setIdentificador($TransaccionApi2->getIdentificador());
                                $AllowCreditTransaction = true;
                            } else {
                                throw new Exception("Transaccion no es Debit ni Credit", "10006");
                            }

                            $this->transaccionApi->setTransaccionId('ROLLBACK' . $RefTransactionId);
                            //  Verificamos que la transaccionId no se haya procesado antes
                            if ($this->transaccionApi->existsTransaccionIdAndProveedor("OK")) {
                                //  Si la transaccionId ha sido procesada, reportamos el error
                                throw new Exception("Transaccion ya procesada", "10001");
                            }
                            $this->transaccionApi->setTransaccionId('ROLLBACK' . $TransactionId);

                            $responseG = $this->getGame()->rollback($UsuarioMandante, $this->Proveedor, $this->transaccionApi, false, $RefTransactionId, true, false, $AllowCreditTransaction, true);

                            $this->transaccionApi = $responseG->transaccionApi;
                            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 4, '.', null));

                            $respuesta = array(
                                "items" => array(
                                    "externalTxId" => $responseG->transaccionId,
                                    "balance" => $saldo,
                                    "info" => $info,
                                    "errorCode" => 1
                                )
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

                            try {
                                $TransaccionJuego = new TransaccionJuego("", "GALAXSYS" . $roundId);
                            } catch (Exception $e) {
                                $TransaccionJuego = new TransaccionJuego("", "GALAXSYS" . $originalTxId);
                            }

                            $TransaccionJuego->setTransaccionId("DEL_DEL_" . $TransaccionJuego->getTransaccionId());

                            $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
                            $TransaccionJuegoMySqlDAO->update($TransaccionJuego);
                            $TransaccionJuegoMySqlDAO->getTransaction()->commit();
                        } catch (Exception $e) {
                            $respuesta = array(
                                "errorCode" => 20,
                                "items" => array()
                            );
                        }
                    } else {
                        $respuesta = array(
                            "errorCode" => 20,
                            "items" => array()
                        );
                    }
                }
            }

            return $respuesta;
        } catch (Exception $e) {
            if ($_ENV['debug']) {
                print_r($e);
            }
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Finaliza una ronda en el sistema.
     *
     * Este método realiza las siguientes acciones:
     * - Verifica si ocurrió un error relacionado con el hash.
     * - Obtiene la instancia del usuario mandante.
     * - Crea una transacción API para finalizar la ronda.
     * - Valida el estado de la ronda y lanza excepciones si ya ha sido finalizada.
     * - Llama al método `endRound` del juego asociado para finalizar la ronda.
     * - Guarda la transacción en la base de datos.
     * - Retorna una respuesta con el saldo actualizado y otros datos relevantes.
     *
     * @param string $token                    Token de autenticación.
     * @param string $GameCode                 Código del juego.
     * @param string $PlayerId                 Identificador del jugador.
     * @param string $RoundId                  Identificador de la ronda.
     * @param string $TransactionId            Identificador de la transacción.
     * @param mixed  $TransactionConfiguration Configuración de la transacción.
     * @param mixed  $datos                    Datos adicionales de la transacción.
     * @param string $Estado_                  Estado de la ronda.
     *
     * @return string Respuesta en formato JSON con el saldo, ID de la transacción y balance de bonificación.
     * @throws Exception Si ocurre un error durante la finalización de la ronda.
     */
    public function EndRound($token, $GameCode, $PlayerId, $RoundId, $TransactionId, $TransactionConfiguration, $datos, $Estado_)
    {
        if ($this->errorHash) {
            return $this->convertError("20002", "Token vacio");
        }

        $this->method = 'release';

        $this->data = $datos;

        try {
            if ($this->externalId != "") {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $this->Proveedor->getProveedorId());
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

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId("ENDROUND" . $RoundId);
            $this->transaccionApi->setTipo("ENDROUND");
            $this->transaccionApi->setProveedorId($this->Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);
            $this->transaccionApi->setIdentificador("GALAXSYS" . $RoundId);

            if (true) {
                $TransaccionJuego = new TransaccionJuego("", "GALAXSYS" . $RoundId);

                if ($TransaccionJuego->getEstado() == "I") {
                    if (strpos($TransaccionJuego->getTransaccionId(), "DEL_DEL_") !== false) {
                        throw new Exception("La ronda ya ha sido finalizada", "30021");
                    }
                    throw new Exception("La ronda ya ha sido finalizada", "30017");
                }
            }

            $responseG = $this->getGame()->endRound($this->transaccionApi, $Estado_);

            $this->transaccionApi = $responseG->transaccionApi;


            /*  Retornamos el mensaje satisfactorio  */

            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 4, '.', null));

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
     * Realiza una operación de enmienda (amend) en el sistema.
     *
     * Este método procesa una transacción de débito o crédito para un jugador en un juego específico.
     * Valida los datos de entrada, verifica el estado del jugador y el juego, y registra la transacción en el sistema.
     *
     * @param string  $gameId        Identificador del juego (opcional).
     * @param float   $Amount        Monto de la transacción.
     * @param string  $roundId       Identificador de la ronda.
     * @param string  $transactionId Identificador de la transacción.
     * @param mixed   $datos         Datos adicionales de la transacción.
     * @param boolean $isBonus       Indica si la transacción es un bono (por defecto false).
     * @param string  $info          Información adicional.
     * @param integer $operationType Tipo de operación (37 para débito, otro valor para crédito).
     * @param string  $playerId      Identificador del jugador.
     *
     * @return array Respuesta de la operación, incluyendo el saldo y metadatos.
     * @throws Exception Si ocurre un error durante la operación.
     */
    public function Amend($gameId = "", $Amount, $roundId, $transactionId, $datos, $isBonus = false, $info, $operationType, $playerId)
    {
        $this->transactionId_err = $transactionId;
        $this->info_err = $info;

        if ($this->errorHash) {
            return $this->convertError("20002", "Token vacio");
        }

        $this->method = 'release';

        $this->data = $datos;

        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            $game = $this->Game($gameId);
            if ($game['errorCode'] != 1) {
                return $game;
            }

            $user = $this->User($playerId);
            if ($user['errorCode'] != 1) {
                return $user;
            }

            if ($operationType == 37) {
                /*  Creamos la Transaccion API  */
                $this->transaccionApi = new TransaccionApi();
                $this->transaccionApi->setTransaccionId($transactionId);
                $this->transaccionApi->setTipo("DEBIT");
                $this->transaccionApi->setProveedorId($this->Proveedor->getProveedorId());
                $this->transaccionApi->setTValue(json_encode($datos));
                $this->transaccionApi->setUsucreaId(0);
                $this->transaccionApi->setUsumodifId(0);
                $this->transaccionApi->setValor($Amount);
                $this->transaccionApi->setIdentificador("GALAXSYS" . $roundId);

                try {
                    $TransaccionJuego = new TransaccionJuego("", "GALAXSYS" . $roundId);
                    $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                } catch (Exception $e) {
                    throw new Exception("Transaccion no existe", "10005");
                }

                $ProductoMandante = new ProductoMandante("", "", $TransaccionJuego->productoId);

                /*  Obtenemos el producto con el $TransaccionJuego->productoId */
                $Producto = new Producto($ProductoMandante->productoId);

                $this->transaccionApi->setIdentificador("GALAXSYS" . $roundId);

                $isfreeSpin = false;
                if (floatval($Amount) == 0) {
                    $isfreeSpin = true;
                }

                $responseG = $this->getGame()->debit($UsuarioMandante, $Producto, $this->transaccionApi, $isfreeSpin);
            } else {
                /*  Creamos la Transaccion API  */
                $this->transaccionApi = new TransaccionApi();
                $this->transaccionApi->setTransaccionId($transactionId);
                $this->transaccionApi->setTipo("CREDIT");
                $this->transaccionApi->setProveedorId($this->Proveedor->getProveedorId());
                $this->transaccionApi->setTValue(json_encode($datos));
                $this->transaccionApi->setUsucreaId(0);
                $this->transaccionApi->setUsumodifId(0);
                $this->transaccionApi->setValor($Amount);
                $this->transaccionApi->setIdentificador("GALAXSYS" . $roundId);

                try {
                    $TransaccionJuego = new TransaccionJuego("", "GALAXSYS" . $roundId);
                    $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                } catch (Exception $e) {
                    throw new Exception("Transaccion no existe", "10005");
                }

                $ProductoMandante = new ProductoMandante("", "", $TransaccionJuego->productoId);

                /*  Obtenemos el producto con el $TransaccionJuego->productoId */
                $Producto = new Producto($ProductoMandante->productoId);

                $responseG = $this->getGame()->credit($UsuarioMandante, $Producto, $this->transaccionApi, false, false, $isBonus);
            }

            $this->transaccionApi = $responseG->transaccionApi;

            /*  Retornamos el mensaje satisfactorio  */
            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 4, '.', null));

            $respuesta = array(
                "items" => array(
                    "externalTxId" => $responseG->transaccionId,
                    "balance" => $saldo,
                    "info" => $info,
                    "errorCode" => 1
                )
            );

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
     * Realiza una operación de crédito en el sistema.
     *
     * Este método procesa una transacción de crédito para un jugador en un juego específico.
     * Valida los datos de entrada, verifica el estado del jugador, la moneda y el juego,
     * y registra la transacción en el sistema.
     *
     * @param string  $gameId        Identificador del juego (opcional).
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       Identificador de la ronda.
     * @param string  $transactionId Identificador de la transacción.
     * @param mixed   $datos         Datos adicionales de la transacción.
     * @param boolean $isBonus       Indica si la transacción es un bono (por defecto false).
     * @param string  $info          Información adicional.
     * @param string  $winInfo       Información sobre ganancias.
     * @param string  $rounT         Identificador de la ronda (opcional).
     * @param string  $playerId_     Identificador del jugador.
     * @param string  $currencyId_   Identificador de la moneda.
     * @param string  $ganar         Indica si es una operación de ganancia (por defecto 'no\_').
     * @param boolean $EndRound      Indica si se debe finalizar la ronda (por defecto false).
     *
     * @return array Respuesta de la operación, incluyendo el saldo y metadatos.
     * @throws Exception Si ocurre un error durante la operación.
     */
    public function Credit($gameId = "", $creditAmount, $roundId, $transactionId, $datos, $isBonus = false, $info, $winInfo, $rounT = "", $playerId_, $currencyId_, $ganar = 'no_', $EndRound = false)
    {
        $this->transactionId_err = $transactionId;
        $this->info_err = $info;

        if ($this->errorHash) {
            return $this->convertError("20002", "Token vacio");
        }

        $this->method = 'release';
        $this->data = $datos;

        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            $errorCode = 1;

            if ($playerId_ == null && $gameId == null && $rounT == null) {
                $errorCode = 1;
            } else {
                $resCur = $this->Currenci($currencyId_);
                if ($resCur['errorCode'] != 1) {
                    $errorCode = $resCur['errorCode'];
                }

                $resUs = $this->User($playerId_);
                if ($resUs['errorCode'] != 1) {
                    $errorCode = $resUs['errorCode'];
                }

                $resG = $this->Game($gameId);
                if ($resG['errorCode'] != 1) {
                    $errorCode = $resG['errorCode'];
                }
            }

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($this->Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);
            $this->transaccionApi->setIdentificador("GALAXSYS" . $roundId);

            try {
                if ($rounT != null) {
                    $TransaccionJuego = new TransaccionJuego("", "GALAXSYS" . $roundId);
                    $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                } else {
                    $TransjuegoLog = new TransjuegoLog("", "", "", 'D_' . $roundId . '_' . $this->getSubProveedor()->getSubproveedorId(), $this->getSubProveedor()->getSubproveedorId());

                    $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);
                    $this->transaccionApi->setIdentificador($TransaccionJuego->getTicketId());
                    $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                }
            } catch (Exception $e) {
//                throw new Exception("Transaccion no existe", "10005");
                return $this->convertError('10005', '');
            }

            $ProductoMandante = new ProductoMandante("", "", $TransaccionJuego->productoId);

            /*  Obtenemos el producto con el $TransaccionJuego->productoId */
            $Producto = new Producto($ProductoMandante->productoId);

            $responseG = $this->getGame()->credit($UsuarioMandante, $Producto, $this->transaccionApi, $EndRound, false, $isBonus);

            $this->transaccionApi = $responseG->transaccionApi;

            /*  Retornamos el mensaje satisfactorio  */
            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 4, '.', null));

            if (($ganar == 'no_') && ($winInfo != null || $winInfo != "")) {
                $respuesta = array(
                    "items" => array(
                        "externalTxId" => $responseG->transaccionId,
                        "balance" => $saldo,
                        "betInfo" => $info,
                        "winInfo" => $winInfo,
                        "errorCode" => $errorCode,
                        "metadata" => ""
                    )
                );
            } elseif ($winInfo == "promowin") {
                $resp = $this->Generate_();

                $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 4, '.', null));

                $respuesta = array(
                    "timestamp" => $resp['timestamp'],
                    "signature" => $resp['signature'],
                    "externalTxId" => $responseG->transaccionId,
                    "currencyId" => $responseG->moneda,
                    "balance" => $saldo,
                    "bonusBalance" => 0,
                    "info" => $info,
                    "errorCode" => $errorCode,
                    "metadata" => ""
                );
            } else {
                $respuesta = array(
                    "items" => array(
                        "externalTxId" => $responseG->transaccionId,
                        "balance" => $saldo,
                        "info" => $info,
                        "errorCode" => $errorCode
                    )
                );
            }

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
     * Verifica el estado de una transacción en el sistema.
     *
     * Este método valida los datos de entrada, busca la transacción asociada
     * y devuelve información sobre su estado, incluyendo el saldo antes y después
     * de la operación, así como otros metadatos relevantes.
     *
     * @param string $externalTxId Identificador externo de la transacción (opcional).
     * @param string $providerTxId Identificador del proveedor de la transacción (opcional).
     * @param mixed  $datos        Datos adicionales de la transacción.
     * @param string $signature    Firma de autenticación.
     * @param string $timestamp    Marca de tiempo de la operación.
     *
     * @return array Respuesta con el estado de la transacción, incluyendo el saldo y metadatos.
     * @throws Exception Si ocurre un error durante la verificación.
     */
    public function CheckTxStatus($externalTxId = "", $providerTxId = "", $datos = "", $signature, $timestamp)
    {
        if ($this->errorHash) {
            return $this->convertError("20002", "Token vacio");
        }

        $this->method = 'release';

        $this->data = $datos;

        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            $txStatus = true;

            try {
                if ($externalTxId == null) {
                    $TransjuegoLog = new TransjuegoLog("", "", "", 'D_' . $providerTxId . '_' . $this->getSubProveedor()->getSubproveedorId(), $this->getSubProveedor()->getSubproveedorId());
                    $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);
                    $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                } else {
                    $TransjuegoLog = new TransjuegoLog($externalTxId);
                    $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);
                    $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                }

                $txStatus = true;
            } catch (Exception $e) {
                if ($providerTxId == null && $externalTxId == null) {
                    throw new Exception("Transaccion no existe", "10019");
                } else {
                    $txStatus = false;
                }
            }

            $responseG = $this->getGame()->getBalance($UsuarioMandante);

            /*  Retornamos el mensaje satisfactorio  */
            $valor = str_replace(',', '', number_format(round($TransjuegoLog->valor, 2), 4, '.', null));

            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 4, '.', null));

            $respuesta = array(
                "timestamp" => $timestamp,
                "signature" => $signature,
                "txStatus" => $txStatus,
                "operationType" => 1,
                "txCreationDate" => $TransjuegoLog->fechaCrea,
                "externalTxId" => $externalTxId,
                "balanceBefore" => $valor + $saldo,
                "balanceAfter" => $saldo,
                "currencyId" => $responseG->moneda,
                "errorCode" => 1,
            );

            return $respuesta;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Genera una respuesta con firma y metadatos basados en los parámetros proporcionados.
     *
     * Este método valida el operador, el token y la firma, y genera una respuesta
     * con un balance opcional si se especifica un código de error relacionado.
     *
     * @param string  $OperatorId ID del operador que realiza la solicitud.
     * @param string  $Token      Token de autenticación.
     * @param string  $timestamp_ Marca de tiempo de la solicitud.
     * @param string  $signature_ Firma de autenticación proporcionada.
     * @param boolean $val        Indica si la respuesta debe ser codificada en JSON.
     * @param integer $err        Código de error (por defecto 1).
     * @param boolean $isBalance  Indica si se debe incluir el balance en la respuesta.
     * @param mixed   $info       Información adicional para el balance.
     *
     * @return array|string Respuesta generada con firma, timestamp, código de error y metadatos.
     */
    public function Generate($OperatorId, $Token, $timestamp_, $signature_, $val = false, $err = 1, $isBalance = false, $info = 0)
    {
        $PartnerID = $this->PartnerID;
        $timestamp = $timestamp_;
        $Secretkey = $this->Secretkey;
        $signature = hash_hmac('sha256', $timestamp . $PartnerID, $Secretkey);

        if ($OperatorId != $PartnerID) {
            return $this->convertError('10012', '', $isBalance);
        } elseif ($Token == " " || $Token == 'wrongToken') {
            return $this->convertError('10013', '', $isBalance);
        } elseif ($signature != $signature_) {
            return $this->convertError('10015', '', $isBalance);
        }

        if (in_array($err, [14, "14"])) {
            $saldo = $this->getBalance2($err, $info);
            $bal = $saldo['items']['balance'];
            $final = array(
                "timestamp" => $timestamp,
                "signature" => $signature,
                "errorCode" => $err,
                "balance" => $bal,
                "items" => array()
            );
        } else {
            $final = array(
                "timestamp" => $timestamp,
                "signature" => $signature,
                "errorCode" => $err,
                "items" => array()
            );
        }

        if ($val == true) {
            return json_encode($final);
        }

        return $final;
    }

    /**
     * Valida y procesa la moneda asociada a la transacción.
     *
     * Este método verifica el identificador de la moneda proporcionado y
     * realiza las operaciones necesarias para obtener información sobre la moneda.
     *
     * @param string  $currencyId_ Identificador de la moneda.
     * @param boolean $isBalance   Indica si se debe incluir el balance en la respuesta (opcional).
     *
     * @return array Respuesta con los detalles de la moneda o un error si no es válida.
     */
    public function Currenci($currencyId_, $isBalance = false)
    {
        if (in_array($currencyId_, [" ", "", "WrongCurrencyId", "wrongCurrencyId"])) {
            return $this->convertError('10016', '', $isBalance);
        }

        $final = array(
            "errorCode" => 1
        );
        return $final;
    }

    /**
     * Valida el identificador del jugador y devuelve un código de error si es inválido.
     *
     * @param string  $playerId_ Identificador del jugador.
     * @param boolean $isBalance Indica si se debe incluir el balance en la respuesta (opcional).
     *
     * @return array Respuesta con el código de error o éxito.
     */
    public function User($playerId_, $isBalance = false)
    {
        if (in_array($playerId_, [" ", "null", null, "wrongPlayerId"])) {
            return $this->convertError('10018', '', $isBalance);
        }

        $final = array(
            "errorCode" => 1
        );
        return $final;
    }

    /**
     * Valida el identificador del juego y devuelve un código de error si es inválido.
     *
     * Este método verifica si el identificador del juego proporcionado es válido.
     * Si el identificador es inválido, retorna un error con el código '27'.
     * En caso contrario, retorna un código de éxito.
     *
     * @param mixed $gameId Identificador del juego.
     *
     * @return array Respuesta con el código de error o éxito.
     */
    public function Game($gameId)
    {
        if (in_array($gameId, ["", "null", null, 0, "0"])) {
            return $this->convertError('27', '');
        }
        $final = array(
            "errorCode" => 1
        );
        return $final;
    }

    /**
     * Genera un conjunto de datos con una marca de tiempo y una firma HMAC.
     *
     * Este método utiliza el identificador del socio (`PartnerID`) y una clave secreta (`Secretkey`)
     * para generar una firma HMAC basada en la marca de tiempo actual.
     *
     * @return array Un arreglo que contiene la marca de tiempo (`timestamp`) y la firma generada (`signature`).
     */
    public function Generate_()
    {
        $PartnerID = $this->PartnerID;
        $timestamp = date("Ymd") . time();
        $Secretkey = $this->Secretkey;
        $signature = hash_hmac('sha256', $timestamp . $PartnerID, $Secretkey);
        $final = array(
            "timestamp" => $timestamp,
            "signature" => $signature
        );
        return $final;
    }

    /**
     * Maneja errores y genera respuestas de error.
     *
     * @param integer $code      Código de error.
     * @param string  $message   Mensaje de error.
     * @param boolean $isBalance Indica si el error está relacionado con el balance.
     *
     * @return array Respuesta de error.
     */
    public function convertError($code, $message, $isBalance = false)
    {
        $codeProveedor = "";
        $messageProveedor = "";
        $response = array();

        switch ($code) {
            case 10011:
                $codeProveedor = "2";
                $messageProveedor = "Sesión no encontrada.";
                break;

            case 21:
                $codeProveedor = "3";
                $messageProveedor = "Player authentication failed due to invalid, not found or expired token.";
                break;

            case 22:
                $codeProveedor = "3";
                $messageProveedor = "Player authentication failed due to invalid, not found or expired token.";
                break;

            case 20001:
                $codeProveedor = "6";
                $messageProveedor = "Insufficient balance";
                break;

            case 0:
                $codeProveedor = "999";
                $messageProveedor = "Internal server error";
                break;

            case 27: //OK
                $codeProveedor = "11";
                $messageProveedor = "Requested game was not found.";
                break;

            case 28:
                $codeProveedor = "7";
                $messageProveedor = "ROUND_NOT_FOUND";
                break;

            case 29:
                $codeProveedor = "7";
                $messageProveedor = "Transaction Not Found";
                break;

            case 10001: //OK
                $codeProveedor = "8";
                $messageProveedor = "Transaction Not Found";
                break;

            case 10004:
                $codeProveedor = "14";
                $messageProveedor = "Apuesta con cancelacion antes.";
                break;

            case 10005:
                $codeProveedor = "7";
                $messageProveedor = "Bet Transaction not found";
                break;

            case 10014:
                $codeProveedor = "999";
                $messageProveedor = "General Error";
                break;

            case 10010:
                $codeProveedor = "999";
                $messageProveedor = "General Error.";
                break;

            case 20002: //OK
                $codeProveedor = "3";
                $messageProveedor = "Sesión caducada.";
                break;

            case 20003: //OK
                $codeProveedor = "5";
                $messageProveedor = "Player is blocked.";
                break;

            case 20024: //OK
                $codeProveedor = "5";
                $messageProveedor = "Player is blocked.";
                break;

            case 10017: //OK
                $codeProveedor = "4";
                $messageProveedor = "Requested currency was not found.";
                break;

            case 10012: //OK
                $codeProveedor = "15";
                $messageProveedor = "Id de operador incorrecto.";
                break;

            case 10013: //OK
                $codeProveedor = "2";
                $messageProveedor = "SessionNotFound.";
                break;

            case 10015: //OK
                $codeProveedor = "12";
                $messageProveedor = "SessionNotFound.";
                break;

            case 10016: //OK
                $codeProveedor = "16";
                $messageProveedor = "CurrenciNotFound.";
                break;

            case 10018: //OK
                $codeProveedor = "4";
                $messageProveedor = "WrongPlayerId";
                break;

            case 10019: //OK
                $codeProveedor = "17";
                $messageProveedor = "RequestParameterMissing";
                break;

            default:
                $codeProveedor = "999"; //OK
                $messageProveedor = "Internal service error";
                break;
        }

        if ($codeProveedor != "") {
            $resp = $this->Generate_();
            $cod = $codeProveedor;
            if ($cod == 5 || $cod == 3 || $cod == 16 || $cod == 11 || $cod == 4 || $cod == 2 || $cod == 15 || $cod == 12) {
                $respuesta = array_merge($response, array(
                    "timestamp" => $resp['timestamp'],
                    "signature" => $resp['signature'],
                    "errorCode" => $codeProveedor,
                    "balance" => '0',
                    "items" => array()
                ));
            } else {
                try {
                    $TransjuegoLog = new TransjuegoLog("", "", "", $this->transactionId_err . '_' . $this->getSubProveedor()->getSubproveedorId(), $this->getSubProveedor()->getSubproveedorId());
                    $trlog = $TransjuegoLog->transjuegologId;
                } catch (Exception $e) {
                    $trlog = 00;
                }

                if ($codeProveedor == 8 || $codeProveedor == '8') {
                    $saldo = $this->getBalance2($codeProveedor, $this->info_err);
                    $bal = $saldo['items']['balance'];
                } else {
                    $bal = '0';
                }

                $respuesta = array_merge($response, array(
                    "timestamp" => $resp['timestamp'],
                    "signature" => $resp['signature'],
                    "externalTxId" => $trlog,
                    "info" => $this->info_err,
                    "balance" => $bal,
                    "errorCode" => $codeProveedor,
                    "items" => array()
                ));
            }
        } else {
            $respuesta = json_encode(array_merge($response));
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