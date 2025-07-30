<?php

/**
 * Clase `Belatra` que implementa la integración con el proveedor de juegos BELATRA.
 *
 * Este archivo contiene la lógica para manejar transacciones relacionadas con el proveedor BELATRA,
 * incluyendo autenticación, balance, débitos, créditos y rollbacks. También incluye la conversión
 * de errores específicos del proveedor a un formato estándar.
 *
 * @category   Integración
 * @package    API
 * @subpackage Casino
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
use Backend\dto\PuntoVenta;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioToken;
use Backend\dto\SubproveedorMandantePais;
use Backend\dto\TransjuegoLog;
use Backend\dto\UsuarioPerfil;
use Backend\dto\TransaccionApi;
use Backend\dto\UsuarioSession;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\UsuarioHistorial;
use Backend\dto\TransaccionProducto;
use Backend\dto\UsuarioConfiguracion;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;
use Backend\mysql\TransaccionApiMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;

use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;

/**
 * Clase `Belatra`.
 *
 * Esta clase implementa la integración con el proveedor de juegos BELATRA,
 * proporcionando métodos para manejar transacciones como autenticación,
 * balance, débitos, créditos y rollbacks.
 */
class Belatra
{
    /**
     * Identificador del operador.
     *
     * @var string
     */
    private $operadorId;

    /**
     * Usuario asociado a la transacción.
     *
     * @var string
     */
    private $user;

    /**
     * Token de autenticación del usuario.
     *
     * @var string
     */
    private $token;

    /**
     * Identificador único del usuario.
     *
     * @var string
     */
    private $uid;

    /**
     * Objeto para manejar transacciones API.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * Datos asociados a la transacción.
     *
     * @var mixed
     */
    private $data;

    /**
     * Tipo de operación actual.
     *
     * @var string
     */
    private $type;

    /**
     * Identificador de la sesión actual.
     *
     * @var string
     */
    private $sessionId;

    /**
     * Identificador de la transacción actual.
     *
     * @var string
     */
    private $transactionId;

    /**
     * Acción original asociada a la transacción.
     *
     * @var string
     */
    private $originalAction;

    /**
     * Constructor de la clase `Belatra`.
     *
     * @param string $user      Usuario asociado a la transacción.
     * @param string $token     Token de autenticación del usuario.
     * @param string $sessionId ID de la sesión actual.
     */
    public function __construct($user = '', $token = '', $sessionId = '')
    {
        $this->user = $user;
        $this->token = $token;
        $this->sessionId = $sessionId;
    }

    /**
     * Obtiene la firma de autenticación para un juego específico.
     *
     * @param string $game Nombre o identificador del juego.
     *
     * @return string Token de autenticación generado.
     */
    public function getSignature($game)
    {
        $Proveedor = new Proveedor("", "BELATRA");

        try {
            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
        } catch (Exception $e) {
            $UsuarioMandante = new UsuarioMandante($this->user);
            $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->usumandanteId);
        }

        try {
            $Producto = new Producto($UsuarioToken->productoId);
        } catch (Exception $e) {
            $Producto = new Producto("", $game, $Proveedor->getProveedorId());
        }

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());
        $AUTH_TOKEN = $credentials->AUTH_TOKEN;

        return $AUTH_TOKEN;
    }

    /**
     * Realiza la autenticación del usuario y valida la moneda.
     *
     * @param string $currency Moneda a validar.
     *
     * @return string JSON con el balance del usuario.
     */
    public function Auth($currency)
    {
        $this->type = 'Auth';
        try {
            $Proveedor = new Proveedor("", "BELATRA");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);

            if ($this->token == "" && $this->user == "") {
                throw new Exception("Token vacio", "10011");
            }

            try {
                $UserVal = new UsuarioMandante($this->user);
            } catch (Exception $e) {
                throw new Exception("User Invalid", "10018");
            }

            if ($this->token != "") {
                try {
                    $TokenVal = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                } catch (Exception $e) {
                    throw new Exception("User Invalid", "10018");
                }
            }

            if ($this->token != "") {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                $UsuarioMandante = new UsuarioMandante($this->user);
            }

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Producto = new Producto($UsuarioToken->productoId);
            $Subproveedor = new Subproveedor($Producto->subproveedorId);
            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante, $Subproveedor->subproveedorId, $UsuarioToken->productoId);

            if ($currency != $responseG->moneda) {
                throw new Exception("Moneda Incorrecta", "10017");
            }

            $Balance = intval(round($responseG->saldo, 2) * 100);

            $return = array(
                "balance" => $Balance,
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance actual del usuario.
     *
     * @return string JSON con el balance del usuario y el estado de la operación.
     */
    public function Balance()
    {
        $this->type = 'Balance';
        try {
            $Proveedor = new Proveedor("", "BELATRA");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);

            if ($this->token == "" && $this->user == "") {
                throw new Exception("Token vacio", "10011");
            }

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $Producto = new Producto($UsuarioToken->productoId);
            $Subproveedor = new Subproveedor($Producto->subproveedorId);
            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante, $Subproveedor->subproveedorId, $UsuarioToken->productoId);

            $Balance = intval(round($responseG->saldo, 2) * 100);

            $return = array(
                "balance" => $Balance,
                "status" => 'success',
                "statusCode" => 0,
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un débito en la cuenta del usuario.
     *
     * @param string  $gameId        ID del juego.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param array   $datos         Datos adicionales de la transacción.
     * @param boolean $gameRoundEnd  Indica si la ronda del juego ha terminado.
     * @param string  $currency      Moneda de la transacción.
     * @param string  $type          Tipo de transacción.
     * @param boolean $isfreeSpin    Indica si es una jugada gratuita.
     * @param float   $amount        Monto adicional.
     *
     * @return string JSON con el balance actualizado y detalles de la transacción.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $gameRoundEnd = false, $currency, $type = '', $isfreeSpin, $amount)
    {
        if ($type != '') {
            $this->type = $type;
        } else {
            $this->type = 'Debit';
        }

        $this->transactionId = $transactionId;

        try {
            if ($this->token == "" && $this->user == "") {
                throw new Exception("Token vacio", "10011");
            }

            if ($debitAmount < 0 && $amount < 0) {
                throw new Exception("Monto negativo", "10002");
            }

            $Proveedor = new Proveedor("", "BELATRA");

            if ($this->token != "") {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                $UsuarioMandante = new UsuarioMandante($this->user);
            }

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $balance_old = intval(round($Usuario->getBalance() * 100, 2));
            $balance_old = number_format($balance_old / 100, 2, '.', '');

            $diferencia = $debitAmount - $balance_old;
            $diferencia = round($diferencia, 2);
            if ($diferencia === 0.01) {
                throw new Exception("Maximo amount", "1000203");
            }

            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            $isRollback = false;
            try {
                $TransApi = new TransaccionApi("", "ROLLBACK" . $transactionId, $Proveedor->getProveedorId(), 'ERROR');
                if ($TransApi->tipo == 'ROLLBACK') {
                    $isRollback = true;
                }
            } catch (Exception $e) {
                $isRollback = false;
            }

            if ($isRollback) {
                throw new Exception("Rollback antes", "10004");
            } else {
                $this->transaccionApi = new TransaccionApi();
                $this->transaccionApi->setTransaccionId($transactionId);
                $this->transaccionApi->setTipo("DEBIT");
                $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
                $this->transaccionApi->setTValue(json_encode($datos));
                $this->transaccionApi->setUsucreaId(0);
                $this->transaccionApi->setUsumodifId(0);
                $this->transaccionApi->setValor($debitAmount);
                $this->transaccionApi->setIdentificador("BELATRA" . $roundId);

                if ($gameRoundEnd == true) {
                    $End = true;
                } else {
                    $End = false;
                }

                $Game = new Game();

                $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $isfreeSpin, [], true, $End);
                $this->transaccionApi = $responseG->transaccionApi;

                $Balance = intval(round($responseG->saldo, 2) * 100);

                if ($currency != $responseG->moneda) {
                    throw new Exception("Moneda Incorrecta", "10017");
                }

                $return = array(
                    "balance" => $Balance,
                    "action_id" => $transactionId,
                    "tx_id" => $responseG->transaccionId
                );

                $this->transaccionApi->setRespuesta($return);
                $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                $TransaccionApiMySqlDAO->update($this->transaccionApi);
                $TransaccionApiMySqlDAO->getTransaction()->commit();

                return json_encode($return);
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un crédito en la cuenta del usuario.
     *
     * @param string  $Producto      Producto asociado.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param array   $datos         Datos adicionales de la transacción.
     * @param boolean $isfreeSpin    Indica si es una jugada gratuita.
     * @param boolean $gameRoundEnd  Indica si la ronda del juego ha terminado.
     * @param string  $currency      Moneda de la transacción.
     * @param float   $amount        Monto adicional.
     * @param string  $type          Tipo de transacción.
     *
     * @return string JSON con el balance actualizado y detalles de la transacción.
     */
    public function Credit($Producto = "", $creditAmount, $roundId, $transactionId, $datos, $isfreeSpin = false, $gameRoundEnd = false, $currency, $amount, $type)
    {
        if ($type != '') {
            $this->type = $type;
        } else {
            $this->type = 'Credit';
        }

        $this->transactionId = $transactionId;

        try {
            if ($this->token == "" && $this->user == "") {
                throw new Exception("Token vacio", "10011");
            }

            if ($creditAmount < 0 && $amount < 0) {
                throw new Exception("Monto negativo", "10002");
            }

            /*  Obtenemos el Proveedor con el abreviado BELATRA */
            $Proveedor = new Proveedor("", "BELATRA");

            if ($this->token != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (Exception $e) {
                    $UsuarioMandante = new UsuarioMandante($this->user);
                }
            } else {
                $UsuarioMandante = new UsuarioMandante($this->user);
            }

            $Game = new Game();

            $isRollback = false;

            if ($isRollback) {
                throw new Exception("Rollback antes", "100177");
            } else {
                try {
                    $TransaccionJuego = new TransaccionJuego("", "BELATRA" . $roundId);
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
                $this->transaccionApi->setIdentificador("BELATRA" . $roundId);

                $ProductoMandante = new ProductoMandante("", "", $TransaccionJuego->productoId);
                /*  Obtenemos el producto con el $TransaccionJuego->productoId */
                $Producto = new Producto($ProductoMandante->productoId);

                if ($gameRoundEnd == true) {
                    $End = true;
                } else {
                    $End = false;
                }

                $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $End, false, $isfreeSpin, false);

                $this->transaccionApi = $responseG->transaccionApi;

                $Balance = intval(round($responseG->saldo, 2) * 100);

                if ($currency != $responseG->moneda) {
                    throw new Exception("Moneda Incorrecta", "10017");
                }

                $return = array(
                    "balance" => $Balance,
                    "action_id" => $transactionId,
                    "tx_id" => $responseG->transaccionId
                );

                /*  Guardamos la Transaccion Api necesaria de estado OK   */
                $this->transaccionApi->setRespuesta($return);
                $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                $TransaccionApiMySqlDAO->update($this->transaccionApi);
                $TransaccionApiMySqlDAO->getTransaction()->commit();

                return json_encode($return);
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }


    /**
     * Realiza un rollback de una transacción previa.
     *
     * @param float   $rollbackAmount Monto a revertir.
     * @param string  $roundId        ID de la ronda.
     * @param string  $transactionId  ID de la transacción.
     * @param array   $datos          Datos adicionales de la transacción.
     * @param boolean $gameRoundEnd   Indica si la ronda del juego ha terminado.
     * @param string  $gameId         ID del juego.
     * @param string  $originalAction Acción original a revertir.
     * @param string  $type           Tipo de transacción.
     *
     * @return string JSON con el balance actualizado y detalles de la transacción.
     */
    public function Rollback($rollbackAmount = "", $roundId, $transactionId, $datos, $gameRoundEnd, $gameId, $originalAction, $type)
    {
        if ($type != '') {
            $this->type = $type;
        } else {
            $this->type = 'Rollback';
        }

        $this->originalAction = $originalAction;
        $this->transactionId = $transactionId;

        try {
            /*  Obtenemos el Proveedor con el abreviado BELATRA */
            $Proveedor = new Proveedor("", "BELATRA");

            if ($this->token != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (Exception $e) {
                    $UsuarioMandante = new UsuarioMandante($this->user);
                    //$UsuarioMandante = new UsuarioMandante("", $this->externalId, $Usuario->mandante);
                }
            } else {
                $UsuarioMandante = new UsuarioMandante($this->user);
            }

            $isRollback = false;

            if ($isRollback) {
                throw new Exception("Rollback antes", "10001");
            } else {
                $aggtrans = false;
                try {
                    $TransaccionJuego = new TransaccionJuego('', "BELATRA" . $roundId);
                    if ($TransaccionJuego->getValorPremio() != 0) {
                        $aggtrans = false;
                    }
                } catch (Exception $e) {
                    $aggtrans = false;
                }

                if ($aggtrans) {
                    throw new Exception("Ronda cerrada", "10016");
                } else {
                    /*  Creamos la Transaccion API  */
                    $this->transaccionApi = new TransaccionApi();
                    $this->transaccionApi->setTipo("ROLLBACK");
                    $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
                    $this->transaccionApi->setTValue(json_encode($datos));
                    $this->transaccionApi->setUsucreaId(0);
                    $this->transaccionApi->setUsumodifId(0);
                    $this->transaccionApi->setValor($rollbackAmount);

                    try {
                        $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
                        $TransjuegoLog = new TransjuegoLog("", "", "", $this->originalAction . '_' . $Producto->subproveedorId, $Producto->subproveedorId);
                        $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);
                        $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());

                        if (strpos($TransjuegoLog->getTipo(), 'DEBIT') !== false || strpos($TransjuegoLog->getTipo(), 'CREDIT') !== false) {
                            $transId = explode("_", $TransjuegoLog->transaccionId);
                            $transId = $transId[0];
                            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transId);
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

                    $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false, '', '', '', true, '', $end);

                    $this->transaccionApi = $responseG->transaccionApi;
                    $Balance = intval(round($responseG->saldo, 2) * 100);

                    $return = array(
                        "balance" => $Balance,
                        "action_id" => $transactionId,
                        "tx_id" => $responseG->transaccionId
                    );

                    /*  Guardamos la Transaccion Api necesaria de estado OK   */
                    $this->transaccionApi->setRespuesta($return);
                    $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                    $TransaccionApiMySqlDAO->update($this->transaccionApi);
                    $TransaccionApiMySqlDAO->getTransaction()->commit();

                    return json_encode($return);
                }
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Convierte un error del proveedor en un formato estándar.
     *
     * @param integer $code             Código de error del proveedor.
     * @param string  $messageProveedor Mensaje de error del proveedor.
     *
     * @return string JSON con el error convertido.
     */
    public function convertError($code, $messageProveedor)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $response = array();

        $Proveedor = new Proveedor("", "BELATRA");
        $Subproveedor = new Subproveedor("", "BELATRA");

        if ($this->token != "") {
            try {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } catch (Exception $e) {
                $UsuarioMandante = new UsuarioMandante($this->user);
            }
        } else {
            $UsuarioMandante = new UsuarioMandante($this->user);
        }

        $Balance = intval(round($responseG->saldo, 2) * 100);

        switch ($code) {
            case 10002:
                $codeProveedor = 400;
                $messageProveedor = "Player has not enough funds to process an action";
                break;

            case 1000203:
                $codeProveedor = 100;
                $messageProveedor = "Player has not enough funds to process an action";
                break;

            case 24:
                $codeProveedor = 101;
                $messageProveedor = "Player is invalid";
                break;

            case 10018:
                $codeProveedor = 101;
                $messageProveedor = "Player is invalid";
                break;

            case 10011:
                $codeProveedor = 105;
                $messageProveedor = "Player reached customized bet limit";
                break;

            case 20001:
                $codeProveedor = 106;
                $messageProveedor = "Bet exceeded max bet limit";
                break;

            case 21:
                $codeProveedor = 107;
                $messageProveedor = "Game is forbidden to the player";
                break;

            case 24:
                $codeProveedor = 110;
                $messageProveedor = "Player is disabled";
                break;

            case 21010:
                $codeProveedor = 153;
                $messageProveedor = "Game is not available in Player's country";
                break;

            case 100000:
                $codeProveedor = 400;
                $messageProveedor = "Bad request. (Bad formatted json)";
                break;

            case 20002:
                $codeProveedor = 403;
                $messageProveedor = "Forbidden. (Request sign doesn't match)";
                break;

            case 100000:
                $codeProveedor = 404;
                $messageProveedor = "Not found";
                break;

            case 21010:
                $codeProveedor = 405;
                $messageProveedor = "Game is not available to casino";
                break;

            case 100000:
                $codeProveedor = 500;
                $messageProveedor = "Unknown error";
                break;

            case 300000:
                $codeProveedor = 600;
                $messageProveedor = "Game provider doesn't provide freespins";
                break;

            case 300000:
                $codeProveedor = 601;
                $messageProveedor = "Impossible to issue freespins in requested game";
                break;

            case 26:
                $codeProveedor = 602;
                $messageProveedor = "Should provide at least one game to issue freespins";
                break;

            case 300025:
                $codeProveedor = 603;
                $messageProveedor = "Bad expiration date. Expiration date should be in future and freespins shouldn't be active for more than 1 month";
                break;

            case 300001:
                $codeProveedor = 605;
                $messageProveedor = "Can't change issue state from its current to requested";
                break;

            case 300001:
                $codeProveedor = 606;
                $messageProveedor = "Can't change issue state when issue status is not synced";
                break;

            case 300000:
                $codeProveedor = 607;
                $messageProveedor = "Can't issue one freespin issue at different game providers";
                break;

            case 100011:
                $codeProveedor = 611;
                $messageProveedor = "Freespins issue has already expired";
                break;

            case 100011:
                $codeProveedor = 620;
                $messageProveedor = "Freespins issue can't be canceled";
                break;

            case 10001:
                $codeProveedor = 400;
                $messageProveedor = "Bad request. (Bad formatted json)";

                switch ($this->type) {
                    case "bet":
                        $transjuego = new TransjuegoLog("", "", "", $this->transactionId . '_' . $Subproveedor->subproveedorId, $Subproveedor->subproveedorId);

                        $result = array(
                            "balance" => $Balance,
                            "action_id" => $this->transactionId,
                            "tx_id" => $transjuego->transjuegologId
                        );
                        break;

                    case "win":
                        $transjuego = new TransjuegoLog("", "", "", $this->transactionId . '_' . $Subproveedor->subproveedorId, $Subproveedor->subproveedorId);

                        $result = array(
                            "balance" => $Balance,
                            "action_id" => $this->transactionId,
                            "tx_id" => $transjuego->transjuegologId
                        );
                        break;

                    case "rollback":
                        $transjuego = new TransjuegoLog("", "", "", 'ROLLBACK' . $this->originalAction . '_' . $Subproveedor->subproveedorId, $Subproveedor->subproveedorId);
                        $result = array(
                            "balance" => $Balance,
                            "action_id" => $this->transactionId,
                            "tx_id" => $transjuego->transjuegologId
                        );
                        break;
                }

            case 10017:
                $codeProveedor = 101;
                $messageProveedor = "Player is invalid";
                break;

            case 100177:
                $codeProveedor = 400;
                $messageProveedor = "Bad request. (Bad formatted json)";
                break;

            case 10005:
                switch ($this->type) {
                    case "rollback":
                        $result = array(
                            "balance" => $Balance,
                            "action_id" => $this->transactionId,
                            "tx_id" => ""
                        );
                        break;
                    default:
                        $codeProveedor = 400;
                        $messageProveedor = "Bad request. (Bad formatted json)";
                        break;
                }
            default:
                $codeProveedor = 500;
                $messageProveedor = "Unknown error";
                break;
        }

        if ($result != '') {
            $respuesta = json_encode($result);
        } else {
            http_response_code(400);
            $respuesta = json_encode(array_merge($response, array(
                "code" => $codeProveedor,
                "message" => $messageProveedor,
                "balance" => $Balance
            )));
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
