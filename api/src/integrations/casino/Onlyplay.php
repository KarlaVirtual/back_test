<?php

/**
 * Clase `Onlyplay` para la integración con el proveedor de juegos ONLYPLAY.
 *
 * Este archivo contiene métodos para manejar la autenticación, débitos, créditos,
 * reversión de transacciones y manejo de errores en la integración con el proveedor.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */

namespace Backend\integrations\casino;

use DateTime;
use Exception;
use DateTimeZone;
use Backend\dto\Pais;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Proveedor;
use Backend\dto\PuntoVenta;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioToken;
use Backend\dto\TransjuegoLog;
use Backend\dto\UsuarioPerfil;
use Backend\dto\TransaccionApi;
use Backend\dto\UsuarioSession;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\ProductoMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\UsuarioHistorial;
use Backend\dto\TransaccionProducto;
use Backend\dto\UsuarioConfiguracion;
use Backend\mysql\UsuarioTokenMySqlDAO;

use Backend\websocket\WebsocketUsuario;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransaccionApiMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;

/**
 * Clase `Onlyplay`.
 *
 * Esta clase maneja la integración con el proveedor de juegos ONLYPLAY,
 * proporcionando métodos para autenticación, débitos, créditos, reversión
 * de transacciones y manejo de errores.
 */
class Onlyplay
{
    /**
     * Usuario asociado a la sesión.
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
     * Objeto para manejar las transacciones API.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * Identificador de la transacción actual.
     *
     * @var string
     */
    private $transactionId;

    /**
     * Identificador de la sesión actual.
     *
     * @var string
     */
    private $sessionId;


    /**
     * Constructor de la clase Onlyplay.
     *
     * @param string $user      Usuario asociado a la sesión.
     * @param string $sessionId ID de la sesión actual.
     */
    public function __construct($user = "", $sessionId)
    {
        $this->sessionId = $sessionId;
        $this->user = $user;
    }

    /**
     * Método para autenticar al usuario con el proveedor ONLYPLAY.
     *
     * Este método valida el token de sesión, obtiene información del usuario
     * y realiza la autenticación con el proveedor de juegos.
     *
     * @return string JSON con el resultado de la autenticación y el balance del usuario.
     * @throws Exception Si el token de sesión está vacío o ocurre un error en la autenticación.
     */
    public function Auth()
    {
        try {
            $Proveedor = new Proveedor("", "ONLYPLAY");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);

            if ($this->sessionId == "") {
                throw new Exception("Token vacio", "10011");
            }

            if ($this->sessionId != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->sessionId, $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (Exception $e) {
                    $UsuarioMandante = new UsuarioMandante($this->user);
                }
            } else {
                $UsuarioMandante = new UsuarioMandante($this->user);
            }

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $Balance = intval($Usuario->getBalance() * 100);

            $return = array(
                "success" => true,
                "balance" => $Balance,
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para realizar un débito en el proveedor ONLYPLAY.
     *
     * Este método registra una transacción de débito, valida la sesión y el usuario,
     * y comunica la operación al proveedor de juegos.
     *
     * @param integer $gameId        ID del juego.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $roundId       ID de la ronda de juego.
     * @param string  $transactionId ID de la transacción.
     * @param array   $datos         Datos adicionales de la transacción.
     * @param boolean $isfreeSpin    Indica si es un giro gratuito.
     * @param boolean $gameRoundEnd  Indica si la ronda de juego ha finalizado.
     *
     * @return string JSON con el resultado del débito y el balance actualizado.
     * @throws Exception Si el token de sesión está vacío o ocurre un error en la operación.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $isfreeSpin = false, $gameRoundEnd = true)
    {
        try {
            if ($this->sessionId == "" && $this->user == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado ONLYPLAY */
            $Proveedor = new Proveedor("", "ONLYPLAY");

            if ($this->sessionId != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->sessionId, $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (Exception $e) {
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                    $UsuarioMandante = new UsuarioMandante($this->user);
                }
            } else {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                $UsuarioMandante = new UsuarioMandante($this->user);
            }


            if ($gameId == 0) {
                $Producto = new Producto($UsuarioToken->productoId, "", $Proveedor->getProveedorId());
            } else {
                $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
            }

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            if ($gameRoundEnd == true) {
                $End = true;
            } else {
                $End = false;
            }

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);
            $this->transaccionApi->setIdentificador("ONLYPLAY" . $roundId);

            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $isfreeSpin, [], true, $End);

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Balance = intval($Usuario->getBalance() * 100);

            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "success" => true,
                "balance" => $Balance,
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
     * Método para realizar un crédito en el proveedor ONLYPLAY.
     *
     * Este método registra una transacción de crédito, valida la sesión y el usuario,
     * y comunica la operación al proveedor de juegos.
     *
     * @param string  $Producto      Producto asociado al crédito.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       ID de la ronda de juego.
     * @param string  $transactionId ID de la transacción.
     * @param array   $datos         Datos adicionales de la transacción.
     * @param boolean $isBonus       Indica si es un bono.
     * @param boolean $gameRoundEnd  Indica si la ronda de juego ha finalizado.
     *
     * @return string JSON con el resultado del crédito y el balance actualizado.
     * @throws Exception Si el token de sesión está vacío o ocurre un error en la operación.
     */
    public function Credit($Producto = "", $creditAmount, $roundId, $transactionId, $datos, $isBonus = false, $gameRoundEnd)
    {
        try {
            if ($this->sessionId == "" && $this->user == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado ONLYPLAY */
            $Proveedor = new Proveedor("", "ONLYPLAY");

            if ($this->sessionId != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->sessionId, $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (Exception $e) {
                    $UsuarioMandante = new UsuarioMandante($this->user);
                }
            } else {
                $UsuarioMandante = new UsuarioMandante($this->user);
            }

            $Game = new Game();

            try {
                $TransaccionJuego = new TransaccionJuego("", "ONLYPLAY" . $roundId);
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
            $this->transaccionApi->setIdentificador("ONLYPLAY" . $roundId);

            $ProductoMandante = new ProductoMandante("", "", $TransaccionJuego->productoId);
            /*  Obtenemos el producto con el $TransaccionJuego->productoId */
            $Producto = new Producto($ProductoMandante->productoId);

            if ($gameRoundEnd == true) {
                $End = true;
            } else {
                $End = false;
            }
            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $End, false, $isBonus, true);

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Balance = intval($Usuario->getBalance() * 100);
            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "success" => true,
                "balance" => $Balance,
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
     * Método para realizar un rollback de una transacción en el proveedor ONLYPLAY.
     *
     * Este método revierte una transacción previamente realizada, valida la sesión
     * y el usuario, y comunica la operación al proveedor de juegos.
     *
     * @param string $roundId       ID de la ronda de juego.
     * @param string $transactionId ID de la transacción a revertir.
     *
     * @return string JSON con el resultado del rollback y el balance actualizado.
     * @throws Exception Si el usuario es inválido, la transacción no existe o ya fue revertida.
     */
    public function Rollback($roundId, $transactionId, $gameId)
    {
        $this->transactionId = $transactionId;

        try {

            $Proveedor = new Proveedor("", "ONLYPLAY");
            if ($this->sessionId != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->sessionId, $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (Exception $e) {
                    $UsuarioMandante = new UsuarioMandante($this->user);
                }
            } else {
                $UsuarioMandante = new UsuarioMandante($this->user);
            }

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setIdentificador("ONLYPLAY" . $roundId);

            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
            
            try {
                $TransjuegoLog = new TransjuegoLog("", "", "", $transactionId . '_' . $Producto->subproveedorId, $Producto->subproveedorId);

                $parts = explode('_', $TransjuegoLog->transaccionId);
                $transId = $parts[0];

                $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());

                $this->transaccionApi->setTransaccionId('ROLLBACK' . $transId);
                $this->transaccionApi->setIdentificador($TransaccionJuego->getTicketId());
                $this->transaccionApi->setValor($TransaccionJuego->valorTicket);
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }


            $Game = new Game();
            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false, '', true, true, false, '');

            $this->transaccionApi = $responseG->transaccionApi;

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Balance = intval($Usuario->getBalance() * 100);

            $return = array(
                "success" => true,
                "balance" => $Balance,
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
     * Método para convertir errores en respuestas manejables.
     *
     * Este método traduce códigos de error en mensajes comprensibles y
     * registra la información de la transacción fallida.
     *
     * @param integer $code             Código de error.
     * @param string  $messageProveedor Mensaje del proveedor asociado al error.
     *
     * @return string JSON con el código y mensaje de error traducido.
     */
    public function convertError($code, $messageProveedor)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $response = array();

        $Proveedor = new Proveedor("", "ONLYPLAY");

        if ($this->sessionId != "") {
            try {
                $UsuarioToken = new UsuarioToken($this->sessionId, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } catch (Exception $e) {
                $UsuarioMandante = new UsuarioMandante($this->user);
            }
        } else {
            $UsuarioMandante = new UsuarioMandante($this->user);
        }

        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
        $Balance = intval($Usuario->getBalance() * 100);

        switch ($code) {
            case 24:
                $codeProveedor = 442;
                $messageProveedor = "Bet is more then max_bet";
                break;

            case 10027:
                $result = array(
                    "success" => true,
                    "balance" => $Balance,
                );
                break;

            case 10001:
                $result = array(
                    "success" => true,
                    "balance" => $Balance,
                );
                break;

            case 10017:
                $codeProveedor = 5011;
                $messageProveedor = "Bet is less then min_bet";
                break;

            case 20001:
                $codeProveedor = 5001;
                $messageProveedor = "Insufficient funds";
                break;


            case 28:
                $codeProveedor = 4005;
                $messageProveedor = "Referent transaction not found";
                break;


            case 20000:
                $codeProveedor = 2401;
                $messageProveedor = "Session not found or expired";
                break;


            default:
                $codeProveedor = 499;
                $messageProveedor = "General Error";
                break;
        }

        if ($result != '') {
            $respuesta = json_encode($result);
        } else {
            $respuesta = json_encode(array_merge($response, array(
                "code" => $codeProveedor,
                "message" => $messageProveedor
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
