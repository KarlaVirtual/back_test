<?php

/**
 * Clase `Imoon` para la integración con un proveedor de casino.
 *
 * Esta clase contiene métodos para manejar transacciones relacionadas con el proveedor de casino "Imoon".
 * Proporciona funcionalidades como autenticación, obtención de saldo, débito, crédito, reversión de transacciones,
 * finalización de rondas y manejo de errores.
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
use Backend\dto\Subproveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransaccionApi;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\dto\TransaccionJuego;
use Backend\mysql\TransaccionApiMySqlDAO;

/**
 * Clase `Imoon`.
 *
 * Esta clase representa la integración con el proveedor de casino "Imoon".
 * Contiene métodos para manejar transacciones relacionadas con el proveedor,
 * como autenticación, obtención de saldo, débito, crédito, reversión de transacciones,
 * y finalización de rondas.
 */
class Imoon
{

    /**
     * ID del jugador.
     *
     * @var string
     */
    private $playerId;

    /**
     * Token del jugador.
     *
     * @var string
     */
    private $playerToken;

    /**
     * Identificador único.
     *
     * @var string
     */
    private $uniqueId;

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
     * Método actual en ejecución.
     *
     * @var string
     */
    private $method = '';

    /**
     * Constructor de la clase `Imoon`.
     *
     * @param string $playerToken Token del jugador.
     * @param string $playerId    ID del jugador.
     * @param string $uniqueId    Identificador único.
     */
    public function __construct($playerToken, $playerId, $uniqueId)
    {
        if (! defined('JSON_PRESERVE_ZERO_FRACTION')) {
            define('JSON_PRESERVE_ZERO_FRACTION', 1024);
        }

        $this->playerToken = $playerToken;
        $this->playerId = $playerId;
        $this->uniqueId = $uniqueId;
    }

    /**
     * Autentica al jugador.
     *
     * @param string $playerId ID del jugador.
     *
     * @return array Respuesta de autenticación.
     */
    public function Auth($playerId)
    {
        $this->method = 'authenticate';

        try {
            if ($this->playerToken == "" && $this->playerId == "") {
                throw new Exception("Token vacio", "10011");
            }

            $Proveedor = new Proveedor("", "IMOON");

            if ($this->playerToken != "") {
                $UsuarioToken = new UsuarioToken($this->playerToken, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                $UsuarioMandante = new UsuarioMandante($this->playerId);
            }

            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));
            $Mandante = new Mandante($UsuarioMandante->mandante);

            $array = array(
                "balance" => intval($saldo),
                "currency" => $responseG->moneda,
                "status" => 'OK',
                "uniqueId" => $this->uniqueId,
                "brandId" => $Mandante->mandante,
                "brandName" => $Mandante->nombre,
            );

            return $array;
        } catch (Exception $e) {
            $re_ = $this->convertError($e->getCode(), $e->getMessage());
            $dat_ = json_decode($re_);
            $array = array(
                "balance" => $dat_->balance,
                "currency" => $dat_->currency,
                "status" => $dat_->error_msg,
                "uniqueId" => $this->uniqueId
            );
            return $array;
        }
    }

    /**
     * Obtiene el balance del jugador (versión 2).
     *
     * @param string $playerId ID del jugador.
     *
     * @return string JSON con el balance del jugador.
     */
    public function getBalance2($playerId)
    {
        try {
            $Proveedor = new Proveedor("", "IMOON");

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
     * @param string $playerId ID del jugador.
     * @param string $currency Moneda del jugador.
     *
     * @return array Respuesta con el balance.
     */
    public function getBalance($playerId, $currency)
    {
        $this->method = 'balance';

        try {
            $Proveedor = new Proveedor("", "IMOON");

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
            $resp = $Game->autenticate($UsuarioMandante);

            if ($resp->moneda != $currency) {
                throw new Exception("Invalid currency", "10029");
            }

            $responseG = $Game->getBalance($UsuarioMandante);

            $saldo = str_replace(',', '', number_format($responseG->saldo, 2));

            $array = array(
                "code" => '0',
                "description" => 'Success',
                "balance" => intval($saldo),
            );

            return $array;
        } catch (Exception $e) {
            $re_ = $this->convertError($e->getCode(), $e->getMessage());
            $dat_ = json_decode($re_);
            $array = array(
                "code" => $dat_->error_code,
                "description" => $dat_->error_msg,
                "balance" => 0,
            );
            return $array;
        }
    }

    /**
     * Realiza un débito en la cuenta del jugador.
     *
     * @param string  $gameId        ID del juego.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param array   $datos         Datos adicionales.
     * @param boolean $gameRoundEnd  Indica si la ronda ha terminado.
     * @param string  $currency      Moneda del jugador.
     *
     * @return array Respuesta del débito.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $gameRoundEnd, $currency)
    {
        $this->method = 'debit';
        $this->data = $datos;

        try {
            if ($this->playerToken == "" && $this->playerId == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado Imoon */
            $Proveedor = new Proveedor("", "IMOON");

            if ($this->playerToken != "") {
                $UsuarioToken = new UsuarioToken($this->playerToken, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                $UsuarioMandante = new UsuarioMandante($this->playerId);
            }

            $Game = new Game();
            $resp = $Game->autenticate($UsuarioMandante);

            if ($resp->moneda != $currency) {
                throw new Exception("Invalid currency", "10029");
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
            $this->transaccionApi->setIdentificador("Imoon" . $roundId);

            $isfreeSpin = false;
            if (floatval($debitAmount) == 0) {
                $isfreeSpin = true;
            }

            if ($gameRoundEnd == true) {
                $End = false;
            } else {
                $End = true;
            }

            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $isfreeSpin, [], '', $End);
            $this->transaccionApi = $responseG->transaccionApi;

            $saldo = str_replace(',', '', number_format($responseG->saldo, 2));

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            $array = array(
                "balance" => intval($saldo),
                "betId" => $transactionId,
                "currency" => $responseG->moneda,
                "status" => 'OK'
            );

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta($array);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $array;
        } catch (Exception $e) {
            $re_ = $this->convertError($e->getCode(), $e->getMessage());
            $dat_ = json_decode($re_);
            $array = array(
                "balance" => $dat_->balance,
                "betId" => $transactionId,
                "currency" => $dat_->currency,
                "status" => $dat_->error_msg
            );
            return $array;
        }
    }

    /**
     * Realiza un rollback de una transacción.
     *
     * @param float   $rollbackAmount Monto a revertir.
     * @param string  $roundId        ID de la ronda.
     * @param string  $transactionId  ID de la transacción.
     * @param string  $player         ID del jugador.
     * @param array   $datos          Datos adicionales.
     * @param boolean $gameRoundEnd   Indica si la ronda ha terminado.
     *
     * @return array Respuesta del rollback.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos, $gameRoundEnd)
    {
        $this->method = 'Rollback';
        $this->data = $datos;

        try {
            /*  Obtenemos el Proveedor con el abreviado Imoon */
            $Proveedor = new Proveedor("", "IMOON");

            if ($this->playerToken != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->playerToken, $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (Exception $e) {
                    $UsuarioMandante = new UsuarioMandante($this->playerId);
                    //$UsuarioMandante = new UsuarioMandante("", $this->externalId, $Usuario->mandante);
                }
            } else {
                $UsuarioMandante = new UsuarioMandante($this->playerId);
            }

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
                $SubProveedor = new SubProveedor("", "IMOON");
                $TransjuegoLog = new TransjuegoLog("", "", "", $transactionId . '_' . $SubProveedor->getSubproveedorId(), $SubProveedor->getSubproveedorId());
                $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                //$this->transaccionApi->setIdentificador($TransaccionJuego->getTicketId());

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

            $saldo = str_replace(',', '', number_format($responseG->saldo, 2));

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            $array = array(
                "balance" => intval($saldo),
                "betId" => $transactionId,
                "currency" => $responseG->moneda,
                "status" => 'OK'
            );

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta($array);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $array;
        } catch (Exception $e) {
            $re_ = $this->convertError($e->getCode(), $e->getMessage());
            $dat_ = json_decode($re_);
            $array = array(
                "balance" => $dat_->balance,
                "betId" => $transactionId,
                "currency" => $dat_->currency,
                "status" => $dat_->error_msg
            );
            return $array;
        }
    }

    /**
     * Finaliza una ronda.
     *
     * @param string $RoundId       ID de la ronda.
     * @param string $TransactionId ID de la transacción.
     * @param array  $datos         Datos adicionales.
     * @param string $Estado_       Estado de la ronda.
     *
     * @return array Respuesta de la finalización.
     */
    public function EndRound($RoundId, $TransactionId, $datos, $Estado_)
    {
        $this->data = $datos;

        try {
            /*  Obtenemos el Proveedor con el abreviado IMOON */
            $Proveedor = new Proveedor("", "IMOON");

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

            /*  Obtenemos el Proveedor con el abreviado IMOON */
            $Proveedor = new Proveedor("", "IMOON");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId("ENDROUND" . $RoundId);
            $this->transaccionApi->setTipo("ENDROUND");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);
            $this->transaccionApi->setIdentificador("IMOON" . $RoundId);

            if (true) {
                $TransaccionJuego = new TransaccionJuego("", "IMOON" . $RoundId);
                if ($TransaccionJuego->getEstado() == "I") {
                    if (strpos($TransaccionJuego->getTransaccionId(), "DEL_DEL_") !== false) {
                        throw new Exception("La ronda ya ha sido finalizada", "30021");
                    }
                    throw new Exception("La ronda ya ha sido finalizada", "30017");
                }
            }

            $Game = new Game();
            $responseG = $Game->endRound($this->transaccionApi, $Estado_);
            $this->transaccionApi = $responseG->transaccionApi;

            /*  Retornamos el mensaje satisfactorio  */
            $saldo = str_replace(',', '', number_format($responseG->saldo, 2));

            $array = array(
                "code" => '0',
                "description" => 'Success',
                "balance" => intval($saldo),
            );

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta($array);
            $this->transaccionApi->setIdentificador($TransactionId);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $array;
        } catch (Exception $e) {
            $re_ = $this->convertError($e->getCode(), $e->getMessage());
            $dat_ = json_decode($re_);
            $array = array(
                "code" => $dat_->error_code,
                "description" => $dat_->error_msg,
                "balance" => 0,
            );
            return $array;
        }
    }

    /**
     * Realiza un crédito en la cuenta del jugador.
     *
     * @param string  $gameId        ID del juego.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param array   $datos         Datos adicionales.
     * @param boolean $isBonus       Indica si es un bono.
     * @param boolean $gameRoundEnd  Indica si la ronda ha terminado.
     * @param string  $currency      Moneda del jugador.
     * @param string  $betId         ID de la apuesta.
     *
     * @return array Respuesta del crédito.
     */
    public function Credit($gameId = "", $creditAmount, $roundId, $transactionId, $datos, $isBonus = false, $gameRoundEnd, $currency, $betId)
    {
        $this->method = 'credit';
        $this->data = $datos;

        try {
            if ($this->playerToken == "" && $this->playerId == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado Imoon */
            $Proveedor = new Proveedor("", "IMOON");

            if ($this->playerToken != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->playerToken, $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (Exception $e) {
                    $UsuarioMandante = new UsuarioMandante($this->playerId);
                    //$UsuarioMandante = new UsuarioMandante("", $this->externalId, $Usuario->mandante);
                }
            } else {
                $UsuarioMandante = new UsuarioMandante($this->playerId);
            }

            $Game = new Game();
            $resp = $Game->autenticate($UsuarioMandante);

            if ($resp->moneda != $currency) {
                throw new Exception("Invalid currency", "10029");
            }

            try {
                $TransaccionJuego = new TransaccionJuego("", "Imoon" . $roundId);
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
            $this->transaccionApi->setIdentificador("Imoon" . $roundId);

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
            $saldo = str_replace(',', '', number_format($responseG->saldo, 2));

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            $array = array(
                "balance" => intval($saldo),
                "betId" => $betId,
                "currency" => $responseG->moneda,
                "status" => 'OK'
            );

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta($array);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $array;
        } catch (Exception $e) {
            $re_ = $this->convertError($e->getCode(), $e->getMessage());
            $dat_ = json_decode($re_);
            $array = array(
                "balance" => $dat_->balance,
                "betId" => $betId,
                "currency" => $dat_->currency,
                "status" => $dat_->error_msg
            );
            return $array;
        }
    }

    /**
     * Convierte un error en una respuesta estructurada.
     *
     * @param integer $code    Código del error.
     * @param string  $message Mensaje del error.
     *
     * @return string JSON con la respuesta del error.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $response = array();

        $Proveedor = new Proveedor("", "IMOON");

        if ($this->playerToken != "") {
            try {
                $UsuarioToken = new UsuarioToken($this->playerToken, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } catch (Exception $e) {
                $UsuarioMandante = new UsuarioMandante($this->playerId);
                //$UsuarioMandante = new UsuarioMandante("", $this->externalId, $Usuario->mandante);
            }
        } else {
            $UsuarioMandante = new UsuarioMandante($this->playerId);
        }
        $Game = new Game();
        $responseG = $Game->getBalance($UsuarioMandante);
        $moneda = $responseG->moneda;
        $saldo = str_replace(',', '', number_format($responseG->saldo, 2));

        switch ($code) {
            case 10011:
                $codeProveedor = "104";
                $messageProveedor = "PLAYER_NOT_FOUND";
                $balance = intval($saldo);
                break;

            case 21:
                $codeProveedor = "104";
                $messageProveedor = "PLAYER_NOT_FOUND";
                $balance = intval($saldo);
                break;

            case 22:
                $codeProveedor = "104";
                $messageProveedor = "PLAYER_NOT_FOUND";
                $balance = intval($saldo);
                break;

            case 20001:
                $codeProveedor = "103";
                $messageProveedor = "INSUFFICIENT_FUNDS";
                $balance = intval($saldo);
                break;

            case 0:
                $codeProveedor = "1";
                $messageProveedor = "INTERNAL_ERROR";
                $balance = intval($saldo);
                break;

            case 27: //OK
                $codeProveedor = "4";
                $messageProveedor = "INTERNAL_ERROR";
                $balance = intval($saldo);
                break;

            case 28:
                $codeProveedor = "6";
                $messageProveedor = "INTERNAL_ERROR";
                $balance = intval($saldo);
                break;

            case 29:
                $codeProveedor = "6";
                $messageProveedor = "INTERNAL_ERROR";
                $balance = intval($saldo);
                break;

            case 10001: //OK
                if ($this->method == 'debit') {
                    $codeProveedor = "106";
                    $messageProveedor = "INTERNAL_ERROR";
                    $balance = intval($saldo);
                } else {
                    if ($this->method == 'credit') {
                        $codeProveedor = "106";
                        $messageProveedor = "OK";
                        $balance = intval($saldo);
                    } else {
                        $codeProveedor = "0";
                        $messageProveedor = "OK";
                        $balance = intval($saldo);
                    }
                }
                break;

            case 10004:
                $codeProveedor = "1";
                $messageProveedor = "INTERNAL_ERROR";
                $balance = intval($saldo);
                break;

            case 10005:
                $codeProveedor = "0";
                $messageProveedor = "OK";
                $balance = intval($saldo);
                break;

            case 10014:
                $codeProveedor = "1";
                $messageProveedor = "INTERNAL_ERROR";
                $balance = intval($saldo);
                break;

            case 10010:
                $codeProveedor = "1";
                $messageProveedor = "INTERNAL_ERROR";
                $balance = intval($saldo);
                break;

            case 20002: //OK
                $codeProveedor = "1";
                $messageProveedor = "INTERNAL_ERROR";
                $balance = intval($saldo);
                break;

            case 20003: //OK
                $codeProveedor = "1";
                $messageProveedor = "PLAYER_NOT_FOUND";
                $balance = intval($saldo);
                break;

            case 10017: //OK
                $codeProveedor = "1";
                $messageProveedor = "INTERNAL_ERROR";
                $balance = intval($saldo);
                break;

            case 10027: //OK
                $codeProveedor = "0";
                $messageProveedor = "OK";
                $balance = intval($saldo);
                break;

            case 10028: //OK
                $codeProveedor = "0";
                $messageProveedor = "OK";
                $balance = intval($saldo);
                break;

            case 10029: //OK
                $codeProveedor = "101";
                $messageProveedor = "INTERNAL_ERROR";
                $balance = intval($saldo);
                break;

            default:
                $codeProveedor = "1"; //OK
                $messageProveedor = "INTERNAL_ERROR";
                $balance = intval($saldo);
                break;
        }

        if ($codeProveedor != "") {
            $respuesta = json_encode(array_merge($response, array(
                "error_code" => $codeProveedor,
                "error_msg" => $messageProveedor,
                "balance" => $balance,
                "currency" => $moneda,
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
