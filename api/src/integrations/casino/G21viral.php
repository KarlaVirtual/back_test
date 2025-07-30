<?php

/**
 * Archivo de integración para la API del casino 'G21viral'.
 * Este archivo contiene la lógica para procesar y responder a solicitudes entrantes
 * relacionadas con autenticación, débitos, créditos y rollbacks desde la plataforma de G21viral.
 *
 * @category Integración
 * @package  Backend\integrations\casino
 * @author   sebastian.rico@virtualsoft.tech
 * @version  1.0.0
 * @since    23/04/2025
 */

namespace Backend\integrations\casino;

use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Exception;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\TransaccionApi;
use Backend\dto\ProductoDetalle;
use Backend\dto\UsuarioMandante;
use Backend\mysql\TransaccionApiMySqlDAO;

class G21viral
{
    /**
     * Identificador del juego.
     *
     * @var string
     */
    private $gameId;

    /**
     * Token de autenticación.
     *
     * @var string
     */
    private $token;

    /**
     * Identificador del jugador.
     *
     * @var string
     */
    private $playerId;

    /**
     * Constructor de la clase G21viral.
     *
     * @param string $gameId   ID del juego.
     * @param string $token    Token de autenticación.
     * @param string $playerId ID del jugador.
     */
    public function __construct($gameId, $token, $playerId)
    {
        $this->gameId = $gameId;
        $this->token = $token;
        $this->playerId = $playerId;
    }

    /**
     * Obtiene el balance del jugador.
     *
     * @return string JSON con el balance y la moneda del jugador.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function Balance()
    {
        try {
            $Proveedor = new Proveedor("", "21VIRAL");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);

            if ($this->token != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (Exception $e) {
                    throw new Exception("Token vacio", "10011");
                }
            } else {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->playerId);
                $UsuarioMandante = new UsuarioMandante($this->playerId);
            }

            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Balance = round($Usuario->getBalance(), 2);

            $return = array(
                "balance" => $Balance,
                "currency" => $Usuario->moneda
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
     * @param string $currency      Moneda utilizada.
     * @param float  $debitAmount   Monto a debitar.
     * @param string $roundId       ID de la ronda.
     * @param string $transactionId ID de la transacción.
     * @param array  $datos         Datos adicionales de la transacción.
     *
     * @return string JSON con el balance actualizado y la moneda.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function Debit($gameId, $currency, $debitAmount, $roundId, $transactionId, $datos)
    {
        $this->data = $datos;

        try {
            $Proveedor = new Proveedor("", "21VIRAL");
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);
            $this->transaccionApi->setIdentificador("21VIRAL" . $roundId);

            if ($this->token != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (Exception $e) {
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->playerId);
                    $UsuarioMandante = new UsuarioMandante($this->playerId);
                }
            } else {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->playerId);
                $UsuarioMandante = new UsuarioMandante($this->playerId);
            }

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            $Game = new Game();

            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, false, [], true, false);

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Balance = round($Usuario->getBalance(), 2);

            $return = array(
                "balance" => $Balance,
                "currency" => $currency,
                "operatorTransactionId" => $transactionId
            );

            $this->transaccionApi->setRespuestaCodigo("OK");
            $this->transaccionApi->setRespuesta(json_encode($return));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->insert($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un crédito en la cuenta del jugador.
     *
     * @param string  $gameId        ID del juego.
     * @param string  $currency      Moneda utilizada.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param boolean $isEndRound    Indica si es el final de la ronda.
     * @param array   $datos         Datos adicionales de la transacción.
     *
     * @return string JSON con el balance actualizado y la moneda.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function Credit($gameId, $currency, $creditAmount, $roundId, $transactionId, $isEndRound = false, $datos)
    {
        $this->data = $datos;

        try {
            $Proveedor = new Proveedor("", "21VIRAL");
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);
            $this->transaccionApi->setIdentificador("21VIRAL" . $roundId);

            try {
                $TransaccionJuego = new TransaccionJuego("", "21VIRAL" . $roundId);
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            $Game = new Game();
            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $isEndRound, false, false, false);

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Balance = floatval(number_format(round($Usuario->getBalance(), 2), 2, '.', ''));

            $return = array(
                "balance" => $Balance,
                "currency" => $currency,
                "operatorTransactionId" => $transactionId
            );

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuestaCodigo("OK");
            $this->transaccionApi->setRespuesta(json_encode($return));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->insert($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un rollback de una transacción.
     *
     * @param string $currency       Moneda utilizada.
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $roundId        ID de la ronda.
     * @param string $transactionId  ID de la transacción.
     * @param array  $datos          Datos adicionales de la transacción.
     *
     * @return string JSON con el balance actualizado y la moneda.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function Rollback($currency, $rollbackAmount, $roundId, $transactionId, $datos)
    {
        $this->data = $datos;
        try {
            $Proveedor = new Proveedor("", "21VIRAL");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($rollbackAmount);
            $this->transaccionApi->setIdentificador("21VIRAL" . $roundId);

            try {
                $Producto = new Producto("", $this->gameId, $Proveedor->getProveedorId());
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

            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false, '', '', '', false, '', false);

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Balance = round($Usuario->getBalance(), 2);

            $return = array(
                "balance" => $Balance,
                "currency" => $currency,
                "operatorTransactionId" => $transactionId
            );

            $this->transaccionApi->setRespuestaCodigo("OK");
            $this->transaccionApi->setRespuesta(json_encode($return));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->insert($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Convierte un error en una respuesta JSON con detalles del error.
     *
     * @param integer $code    Código del error.
     * @param string  $message Mensaje del error.
     *
     * @return string JSON con los detalles del error.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $Proveedor = new Proveedor("", "21VIRAL");

        switch ($code) {
            case 20002:
                $codeProveedor = "AuthenticationFailure";
                $messageProveedor = "AuthenticationFailure";
                http_response_code(401);
                break;
            case 300023:
                $codeProveedor = "RequestValidationFailure";
                $messageProveedor = "RequestValidationFailure";
                http_response_code(422);
                break;
            case 20001:
                $codeProveedor = "InsufficientFunds";
                $messageProveedor = "InsufficientFunds";
                http_response_code(422);
                break;
            case 20003:
                $codeProveedor = "PlayerBlocked";
                $messageProveedor = "PlayerBlocked";
                http_response_code(422);
                break;
            case 20004:
                $codeProveedor = "PlayerSelfExclusion";
                $messageProveedor = "PlayerSelfExclusion";
                http_response_code(422);
                break;
            case 20013:
                $codeProveedor = "SpendLimitExceeded";
                $messageProveedor = "SpendLimitExceeded";
                http_response_code(422);
                break;
            case 10005:
                $codeProveedor = "RealMoneyNotAllowed";
                $messageProveedor = "RealMoneyNotAllowed";
                http_response_code(422);
                break;

            default:
                $codeProveedor = 1;
                $messageProveedor = "General Error. (" . $code . ")";
                break;
        }

        $respuesta = json_encode(array(
            "viralErrorCode" => $codeProveedor,
            "message" => $messageProveedor,
        ));

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