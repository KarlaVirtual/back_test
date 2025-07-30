<?php

/**
 * Clase `Playson` para la integración con el proveedor de juegos PLAYSON.
 *
 * Este archivo contiene la implementación de la clase `Playson`, que maneja
 * la autenticación, transacciones de débito, crédito, rollback, y otras
 * operaciones relacionadas con la integración de juegos de casino.
 *
 * @category Integración
 * @package  Backend\integrations\casino
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */

namespace Backend\integrations\casino;

use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Subproveedor;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransaccionApi;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioToken;
use Backend\dto\Producto;
use Backend\dto\ProductoMandante;
use Backend\dto\Proveedor;
use Backend\mysql\TransaccionApiMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Exception;
use SimpleXMLElement;

/**
 * Clase `Playson`.
 *
 * Esta clase maneja la integración con el proveedor de juegos PLAYSON,
 * incluyendo autenticación, transacciones y otras operaciones relacionadas.
 */
class Playson
{

    /**
     * Token de autenticación del usuario.
     *
     * @var string
     */
    private $token;

    /**
     * Objeto que representa la transacción API.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * Datos adicionales relacionados con la transacción.
     *
     * @var array
     */
    private $data;

    /**
     * Método actual que se está ejecutando.
     *
     * @var string
     */
    private $method;

    /**
     * Identificador global del ticket.
     *
     * @var string
     */
    private $ticketIdGlobal;

    /**
     * Identificador externo del usuario.
     *
     * @var string
     */
    private $externalId;

    /**
     * Identificador del juego.
     *
     * @var string
     */
    private $gameId;

    /**
     * Versión del sistema o integración.
     *
     * @var string
     */
    private $version;

    /**
     * Sesión actual del usuario.
     *
     * @var string
     */
    private $session;

    /**
     * Identificador único global.
     *
     * @var string
     */
    private $guid;

    /**
     * Identificador del proveedor.
     *
     * @var string
     */
    private $proveedorId;

    /**
     * Objeto que representa al usuario mandante.
     *
     * @var UsuarioMandante
     */
    private $UsuarioMandanteP;

    /**
     * Objeto que representa el producto asociado.
     *
     * @var Producto
     */
    private $Producto;

    /**
     * Constructor de la clase `Playson`.
     *
     * @param string $token       Token de autenticación.
     * @param string $uid         Identificador único del usuario.
     * @param string $externalId  ID externo del usuario.
     * @param string $method      Método a ejecutar.
     * @param string $gameId      ID del juego.
     * @param string $session     Sesión actual.
     * @param string $proveedorId ID del proveedor.
     * @param string $guid        Identificador único global.
     */
    public function __construct($token, $uid = "", $externalId = "", $method = "", $gameId = "", $session = "", $proveedorId = "", $guid = "")
    {
        $this->token = $token;
        $this->sign = $uid;
        $this->externalId = $externalId;
        $this->gameId = $gameId;

        $this->method = $method;
        $this->session = $session;
        $this->guid = $guid;
        $this->proveedorId = $proveedorId;
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->version = '2';
        }

        if ($this->token == "") {
            $this->token = $this->guid;
        }
    }

    /**
     * Método para autenticar al usuario.
     *
     * @return string XML con la respuesta de autenticación.
     * @throws Exception Si ocurre un error durante la autenticación.
     */
    public function Auth()
    {
        $this->method = 'enter';
        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }
            $Proveedor = new Proveedor("", "PLAYSON");

            if ($this->token != "") {
                $Producto = new Producto("", $this->gameId, $Proveedor->getProveedorId());
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId(), "", "", "", $Producto->productoId);
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                if (intval($this->externalId) == 0) {
                    throw new Exception("Token vacio", "10011");
                }
                $UsuarioMandante = new UsuarioMandante($this->externalId);
                $UsuarioToken = new UsuarioToken('', $Proveedor->getProveedorId(), $UsuarioMandante->getUsumandanteId());
            }

            $UsuarioToken->setToken($this->guid);
            $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
            $UsuarioToken->setProductoId($Producto->getProductoId());
            $UsuarioToken->setToken($this->guid);
            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
            $UsuarioTokenMySqlDAO->insert($UsuarioToken);
            $UsuarioTokenMySqlDAO->getTransaction()->commit();

            $Game = new Game();

            $responseG = $Game->autenticate($UsuarioMandante, "");

            $PKT = new SimpleXMLElement("<service></service>");

            $PKT->addAttribute('session', $this->session);
            $PKT->addAttribute('time', str_replace(" ", "T", date('Y-m-d H:i:s')));
            $enter = $PKT->addChild('enter');
            $enter->addAttribute('id', $this->proveedorId);
            $enter->addAttribute('result', 'ok');
            $Balance = $enter->addChild('balance');
            $Balance->addAttribute('currency', $responseG->moneda);
            $Balance->addAttribute('type', 'real');
            $Balance->addAttribute('value', intval($responseG->saldo * 100));
            $Balance->addAttribute('version', time());
            $User = $enter->addChild('user');
            $User->addAttribute('mode', "normal");
            $User->addAttribute('type', "real");
            $User->addAttribute('wlid', $responseG->usuarioId);
            $control = $enter->addChild('control');
            $control->addAttribute('enable', 'true');
            $control->addAttribute('stream', 'game-data');

            return $PKT->asXML();
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para obtener el balance del usuario.
     *
     * @return string XML con el balance del usuario.
     * @throws Exception Si ocurre un error al obtener el balance.
     */
    public function getBalance()
    {
        $this->method = 'balance';
        try {
            $Proveedor = new Proveedor("", "PLAYSON");

            if ($this->token != "") {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                if (intval($this->externalId) == 0) {
                    throw new Exception("Token vacio", "10011");
                }
                $UsuarioMandante = new UsuarioMandante($this->externalId);
                $UsuarioToken = new UsuarioToken('', $Proveedor->getProveedorId(), $UsuarioMandante->getUsumandanteId());
            }


            $Game = new Game();

            $responseG = $Game->getBalance($UsuarioMandante);

            $PKT = new SimpleXMLElement("<service></service>");
            $PKT->addAttribute('session', $this->session);
            $PKT->addAttribute('time', str_replace(" ", "T", date('Y-m-d H:i:s')));
            $getBalance = $PKT->addChild('getbalance');
            $getBalance->addAttribute('id', $this->proveedorId);
            $getBalance->addAttribute('result', 'ok');
            $Balance = $getBalance->addChild('balance');
            $Balance->addAttribute('value', intval($responseG->saldo * 100));
            $Balance->addAttribute('version', time());
            $Balance->addAttribute('type', 'real');
            $Balance->addAttribute('currency', $responseG->moneda);


            return $PKT->asXML();
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }


    /**
     * Método para realizar una transacción de débito y crédito.
     *
     * @param string  $gameId              ID del juego.
     * @param string  $ticketId            ID del ticket.
     * @param float   $debitAmount         Monto a debitar.
     * @param float   $creditAmount        Monto a acreditar.
     * @param string  $transactionIdDebit  ID de la transacción de débito.
     * @param string  $transactionIdCredit ID de la transacción de crédito.
     * @param array   $datos               Datos adicionales de la transacción.
     * @param boolean $freespin            Indica si es un giro gratis.
     *
     * @return string XML con la respuesta de la transacción.
     * @throws Exception Si ocurre un error durante la transacción.
     */
    public function DebitAndCredit($gameId, $ticketId, $debitAmount, $creditAmount, $transactionIdDebit, $transactionIdCredit, $datos, $freespin)
    {
        $this->method = 'roundbetwin';
        $this->ticketIdGlobal = $ticketId;

        $this->data = $datos;

        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            $Proveedor = new Proveedor("", "PLAYSON");

            if ($this->token != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId(), "", "", "");
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (Exception $e) {
                    if ($e->getCode() == 21) {
                        $UsuarioToken = new UsuarioToken('INACT--' . $this->token, $Proveedor->getProveedorId(), "", "", "", "", "", "I");
                        $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                    }
                }
            } else {
                if (intval($this->externalId) == 0) {
                    throw new Exception("Token vacio", "10011");
                }
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }
            $Producto = new Producto("", $this->gameId, $Proveedor->getProveedorId());

            //  Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionIdDebit);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);
            $this->transaccionApi->setIdentificador($UsuarioMandante->getUsumandanteId() . "PLAYSON" . $ticketId);


            $Credit = new TransaccionApi();
            $Credit->setTransaccionId("credit" . $transactionIdCredit);
            $Credit->setTipo("CREDIT");
            $Credit->setProveedorId($Proveedor->getProveedorId());
            $Credit->setTValue(json_encode($datos));
            $Credit->setUsucreaId(0);
            $Credit->setUsumodifId(0);
            $Credit->setValor($creditAmount);
            $Credit->setIdentificador($UsuarioMandante->getUsumandanteId() . "PLAYSON" . $ticketId);
            $Game = new Game();

            $this->Producto = $Producto;
            $this->UsuarioMandanteP = $UsuarioMandante;

            $responseG = $Game->debitAndcredit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin, [], true, false, $Credit, false);

            $this->transaccionApi = $responseG->transaccionApi;

            $PKT = new SimpleXMLElement("<service></service>");

            $PKT->addAttribute('session', $this->session);
            $PKT->addAttribute('time', str_replace(" ", "T", date('Y-m-d H:i:s')));
            $roundbetwin = $PKT->addChild('roundbetwin');
            $roundbetwin->addAttribute('id', $this->proveedorId);
            $roundbetwin->addAttribute('result', 'ok');
            $Balance = $roundbetwin->addChild('balance');
            $Balance->addAttribute('value', intval($responseG->saldo * 100));
            $Balance->addAttribute('version', $responseG->transaccionId);
            $Balance->addAttribute('type', 'real');
            $Balance->addAttribute('currency', $responseG->moneda);

            $respuesta = $PKT->asXML();

            $Credit->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($Credit);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $respuesta;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para realizar un rollback de una transacción.
     *
     * @param string $gameId         ID del juego.
     * @param string $ticketId       ID del ticket.
     * @param string $uid            Identificador único del usuario.
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $transactionId  ID de la transacción.
     * @param array  $datos          Datos adicionales de la transacción.
     *
     * @return string XML con la respuesta del rollback.
     * @throws Exception Si ocurre un error durante el rollback.
     */
    public function Rollback($gameId, $ticketId, $uid, $rollbackAmount, $transactionId, $datos)
    {
        $this->method = 'refund';

        $this->ticketIdGlobal = $ticketId;

        $this->data = $datos;

        try {
            $Proveedor = new Proveedor("", "PLAYSON");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($rollbackAmount);


            try {
                $SubProveedor = new Subproveedor("", "PLAYSON");
                $TransjuegoLog = new TransjuegoLog("", "", "", str_replace("credit", "", $transactionId) . '_' . $SubProveedor->getSubproveedorId(), $SubProveedor->getSubproveedorId());
                $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                $this->transaccionApi->setIdentificador($UsuarioMandante->getUsumandanteId() . "PLAYSON" . $ticketId);
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            $Game = new Game();

            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi);

            $this->transaccionApi = $responseG->transaccionApi;

            $PKT = new SimpleXMLElement("<service></service>");

            $PKT->addAttribute('session', $this->session);
            $PKT->addAttribute('time', str_replace(" ", "T", date('Y-m-d H:i:s')));
            $roundbetwin = $PKT->addChild('refund');
            $roundbetwin->addAttribute('id', $this->proveedorId);
            $roundbetwin->addAttribute('result', 'ok');
            $Balance = $roundbetwin->addChild('balance');
            $Balance->addAttribute('value', intval($responseG->saldo * 100));
            $Balance->addAttribute('version', $responseG->transaccionId);
            $Balance->addAttribute('type', 'real');
            $Balance->addAttribute('currency', $responseG->moneda);

            $respuesta = $PKT->asXML();

            $this->transaccionApi->setRespuesta(json_encode($respuesta));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $respuesta;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para realizar una transacción de crédito.
     *
     * @param string  $gameId        ID del juego.
     * @param string  $ticketId      ID del ticket.
     * @param string  $uid           Identificador único del usuario.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $transactionId ID de la transacción.
     * @param boolean $isEndRound    Indica si es el final de la ronda.
     * @param array   $datos         Datos adicionales de la transacción.
     *
     * @return string XML con la respuesta de la transacción.
     * @throws Exception Si ocurre un error durante la transacción.
     */
    public function Credit($gameId, $ticketId, $uid, $creditAmount, $transactionId, $isEndRound, $datos)
    {
        if ($this->UsuarioMandanteP->usumandanteId == '16') {
            exec("php -f /home/home2/backend/api/src/imports/Slack/message.php 'TEST2' '#virtualsoft-cron' > /dev/null  ");
            exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $this->UsuarioMandanteP->usumandanteId . " " . $this->Producto->productoId . " " . $this->proveedorId . " " . $ticketId . " " . $creditAmount . " " . $isEndRound . " " . $transactionId . "' '#virtualsoft-cron' > /dev/null & ");
        }

        $this->method = 'roundbetwin';

        $this->ticketIdGlobal = $ticketId;

        $this->data = $datos;

        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }
            $Proveedor = new Proveedor("", "PLAYSON");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);

            $this->transaccionApi->setIdentificador($this->UsuarioMandanteP->getUsumandanteId() . "PLAYSON" . $ticketId);

            $Game = new Game();

            $responseG = $Game->credit($this->UsuarioMandanteP, $this->Producto, $this->transaccionApi, $isEndRound);

            $this->transaccionApi = $responseG->transaccionApi;

            $PKT = new SimpleXMLElement("<service></service>");

            $PKT->addAttribute('session', $this->session);
            $PKT->addAttribute(
                'time',
                str_replace(
                    " ",
                    "T",
                    date('Y-m-d H:i:s')
                )
            );
            $roundbetwin = $PKT->addChild('roundbetwin');
            $roundbetwin->addAttribute('id', $this->proveedorId);
            $roundbetwin->addAttribute('result', 'ok');
            $Balance = $roundbetwin->addChild('balance');
            $Balance->addAttribute('value', intval($responseG->saldo * 100));
            $Balance->addAttribute('version', $responseG->transaccionId);
            $Balance->addAttribute('type', 'real');
            $Balance->addAttribute('currency', $responseG->moneda);

            $respuesta = $PKT->asXML();

            $this->transaccionApi->setRespuesta(json_encode($respuesta));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $PKT->asXML();
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para cerrar la sesión del usuario.
     *
     * @return string XML con la respuesta del cierre de sesión.
     * @throws Exception Si ocurre un error durante el cierre de sesión.
     */
    public function logout()
    {
        $this->method = 'logout';
        try {
            $Proveedor = new Proveedor("", "PLAYSON");

            if ($this->token != "") {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                if (intval($this->externalId) == 0) {
                    throw new Exception("Token vacio", "10011");
                }
                $UsuarioMandante = new UsuarioMandante($this->externalId);
                $UsuarioToken = new UsuarioToken('', $Proveedor->getProveedorId(), $UsuarioMandante->getUsumandanteId());
            }


            if ($UsuarioToken->getUsutokenId() != "") {
                $UsuarioToken->setToken('INACT--' . $UsuarioToken->getToken());
                $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                $UsuarioTokenMySqlDAO->update($UsuarioToken);
                $UsuarioTokenMySqlDAO->getTransaction()->commit();
            }


            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);

            $PKT = new SimpleXMLElement("<service></service>");
            $PKT->addAttribute('session', $this->session);
            $PKT->addAttribute('time', str_replace(" ", "T", date('Y-m-d H:i:s')));
            $getBalance = $PKT->addChild('logout');
            $getBalance->addAttribute('id', $this->proveedorId);
            $getBalance->addAttribute('result', 'ok');
            $Balance = $getBalance->addChild('balance');
            $Balance->addAttribute('value', intval($responseG->saldo * 100));
            $Balance->addAttribute('version', time());
            $Balance->addAttribute('type', 'real');
            $Balance->addAttribute('currency', $responseG->moneda);

            return $PKT->asXML();
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para convertir errores en respuestas XML.
     *
     * @param integer $code    Código del error.
     * @param string  $message Mensaje del error.
     *
     * @return string XML con la respuesta del error.
     */
    public function convertError($code, $message)
    {
        if ($_ENV["connectionGlobal"] != null && $_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"]->isBeginTransaction == 2) {
            ($_ENV["connectionGlobal"])->rollBack();
        }
        $codeProveedor = "";
        $messageProveedor = "";

        $PKT = new SimpleXMLElement("<service></service>");
        $PKT->addAttribute('session', $this->session);
        $PKT->addAttribute('time', str_replace(" ", "T", date('Y-m-d H:i:s')));
        $Result = $PKT->addChild($this->method);
        $Result->addAttribute('id', $this->proveedorId);
        $Result->addAttribute('result', "fail");
        $Error = $Result->addChild('error');


        if ($this->transaccionApi != null) {
            $tipo = $this->transaccionApi->getTipo();
        }

        switch ($code) {
            case 10011:
                $codeProveedor = 'INVALID_KEY';
                $messageProveedor = "An invalid authentication key";
                break;

            case 21:
                if ($this->method == "roundbetwin") {
                    $PKT = new SimpleXMLElement("<service></service>");

                    $PKT->addAttribute('session', $this->session);
                    $PKT->addAttribute('time', str_replace(" ", "T", date('Y-m-d H:i:s')) . 'Z');
                    $roundbetwin = $PKT->addChild($this->method);
                    $roundbetwin->addAttribute('id', $this->proveedorId);
                    $roundbetwin->addAttribute('result', 'ok');
                    $Balance = $roundbetwin->addChild('balance');
                    $Balance->addAttribute('value', 0);
                    $Balance->addAttribute('version', '');
                    $Balance->addAttribute('type', 'real');
                    $Balance->addAttribute('currency', '');
                } else {
                    $codeProveedor = 'KEY_EXPIRED';
                    $messageProveedor = "An authentication key has expired";
                }
                break;

            case 20001:
                $codeProveedor = 'NOT_ENOUGH_MONEY ';
                $messageProveedor = "The balance is insufficient for making a bet";
                break;

            case 20003:
                $codeProveedor = 'USER_BLOCKED';
                $messageProveedor = "User is blocked.";
                break;

            case 0:
                $codeProveedor = 'WL_ERROR ';
                $messageProveedor = "Internal site error";
                break;

            case 27:
                $codeProveedor = 'WL_ERROR ';
                $messageProveedor = "Internal site error";
                break;

            case 29:
                $codeProveedor = "WL_ERROR";
                $messageProveedor = "Internal site error";
                break;

            case 10004:
                $codeProveedor = "WL_ERROR";
                $messageProveedor = "Internal site error";
                break;

            case 10014:
                $codeProveedor = "WL_ERROR";
                $messageProveedor = "Internal site error";
                break;

            case 20005:
                $codeProveedor = 'USER_BLOCKED';
                $messageProveedor = "User is blocked";
                break;

            case 20006:
                $codeProveedor = 'USER_BLOCKED';
                $messageProveedor = "User is blocked";
                break;

            case 20007:
                $codeProveedor = 'USER_BLOCKED';
                $messageProveedor = "User is blocked";
                break;

            case 10005:
                $codeProveedor = "WL_ERROR";
                $messageProveedor = "Internal site error";
                break;

            case 21010:
                $codeProveedor = "USER_BLOCKED";
                $messageProveedor = "User is blocked";
                break;

            case 10001:

                if ($this->method == "refund" || true) {
                    try {
                        $ProductoMandante = new ProductoMandante('', '', $this->transaccionApi->getProductoId());
                        $Producto = new Producto($ProductoMandante->productoId);

                        $TransjuegoLog = new TransjuegoLog("", '', '', $this->transaccionApi->getTransaccionId() . '_' . $Producto->getSubproveedorId(), $Producto->getSubproveedorId());
                        $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);

                        $codeProveedor = "";
                        $messageProveedor = "";

                        $Game = new Game();
                        $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());

                        $responseG = $Game->getBalance($UsuarioMandante);

                        $PKT = new SimpleXMLElement("<service></service>");

                        $PKT->addAttribute('session', $this->session);
                        $PKT->addAttribute('time', str_replace(" ", "T", date('Y-m-d H:i:s')) . 'Z');
                        $roundbetwin = $PKT->addChild($this->method);
                        $roundbetwin->addAttribute('id', $this->proveedorId);
                        $roundbetwin->addAttribute('result', 'ok');
                        $Balance = $roundbetwin->addChild('balance');
                        $Balance->addAttribute('value', intval($responseG->saldo * 100));
                        $Balance->addAttribute('version', $TransjuegoLog->transjuegologId);
                        $Balance->addAttribute('type', 'real');
                        $Balance->addAttribute('currency', $responseG->moneda);
                    } catch (Exception $e) {
                        $PKT = new SimpleXMLElement("<service></service>");

                        $PKT->addAttribute('session', $this->session);
                        $PKT->addAttribute('time', str_replace(" ", "T", date('Y-m-d H:i:s')) . 'Z');
                        $roundbetwin = $PKT->addChild($this->method);
                        $roundbetwin->addAttribute('id', $this->proveedorId);
                        $roundbetwin->addAttribute('result', 'ok');
                        $Balance = $roundbetwin->addChild('balance');
                        $Balance->addAttribute('value', 0);
                        $Balance->addAttribute('version', '');
                        $Balance->addAttribute('type', 'real');
                        $Balance->addAttribute('currency', '');
                    }
                } else {
                    $codeProveedor = "WL_ERROR";
                    $messageProveedor = "Internal site error";
                }
                break;

            default:
                $codeProveedor = "WL_ERROR";
                $messageProveedor = "Internal site error";
                break;
        }

        if ($code != 10001 || true) {
            $Error->addAttribute('code', $codeProveedor);
            $Error->addAttribute('msg', $messageProveedor . $code);
        }

        if ($this->transaccionApi != null) {
            $Text = $PKT->asXML();
            $this->transaccionApi->setTipo("R" . $this->transaccionApi->getTipo());
            $this->transaccionApi->setRespuestaCodigo("ERROR_" . $code);
            $this->transaccionApi->setRespuesta($Text);
            $this->transaccionApi->setTransaccionId($this->transaccionApi->getTransaccionId() . '_E' . $code);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->insert($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();
        }

        return $PKT->asXML();
    }
}
