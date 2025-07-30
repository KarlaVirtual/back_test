<?php

/**
 * Clase Eagaming para la integración con el proveedor de juegos EAGAMING.
 *
 * Este archivo contiene métodos para manejar la autenticación, balance, débitos, créditos,
 * reversión de transacciones y manejo de errores relacionados con la integración de juegos.
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
 * Clase principal para manejar las operaciones de integración con EAGAMING.
 */
class Eagaming
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
     * Identificador único del usuario.
     *
     * @var mixed
     */
    private $uid;

    /**
     * Firma para validar las solicitudes.
     *
     * @var string
     */
    private $sign;

    /**
     * Objeto para manejar transacciones API.
     *
     * @var string
     * @access private
     */
    private $transaccionApi;

    /**
     * Datos adicionales de la transacción.
     *
     * @var mixed
     */
    private $data;

    /**
     * Identificador de la ronda de juego.
     *
     * @var string
     */
    private $roundId;

    /**
     * Constructor de la clase Eagaming.
     *
     * @param string $token   Token de autenticación.
     * @param string $sign    Firma para validar las solicitudes.
     * @param string $roundId Identificador de la ronda de juego.
     */
    public function __construct($token="", $roundId="")
    {
        $this->token = $token;
        $this->roundId = $roundId;
    }

    /**
     * Autentica al usuario con el proveedor EAGAMING.
     *
     * @return string Respuesta en formato JSON con el estado, balance y moneda.
     * @throws Exception Si ocurre un error durante la autenticación.
     */
    public function Auth()
    {

        try
        {

            $Proveedor = new Proveedor("", "EAGAMING");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);


            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $Game = new Game();

            $responseG = $Game->autenticate($UsuarioMandante);


            $return = array(
                "status" => 200,
                "balance" => (($responseG->saldo)),
                "currency" => $UsuarioMandante->getMoneda()
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }


    /**
     * Obtiene el balance del usuario autenticado.
     *
     * @return string Respuesta en formato JSON con el estado y balance.
     * @throws Exception Si ocurre un error al obtener el balance.
     */
    public function getBalance()
    {

        try
        {

            $Proveedor = new Proveedor("", "EAGAMING");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("BALANCE");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);


            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            } else {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            }

            $Game = new Game();

            $responseG = $Game->getBalance($UsuarioMandante);

            $return = array(
                "status" => 200,
                "balance" => (($responseG->saldo))
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
     * @param string  $roundId       Identificador de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param array   $datos         Datos adicionales de la transacción.
     * @param boolean $isFreeSpin    Indica si es un giro gratis.
     *
     * @return string Respuesta en formato JSON con el estado, balance, referencia y moneda.
     * @throws Exception Si ocurre un error durante el débito.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $isFreeSpin = false)
    {
        $this->data = $datos;

        try
        {

            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            // Obtenemos el Proveedor con el abreviado EAGAMING
            $Proveedor = new Proveedor("", "EAGAMING");

            // Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);


            // Obtenemos el Usuario Token con el token
            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            // Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);


            $this->transaccionApi->setIdentificador($roundId . "EAGAMING");

            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $isFreeSpin);

            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "status" => 200,
                "balance" => (($responseG->saldo)),
                "referenceId" => $responseG->transaccionId,
                "currency" => $responseG->moneda,

            );

            // Guardamos la Transaccion Api necesaria de estado OK
            $this->transaccionApi->setRespuestaCodigo("OK");
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
     * Realiza una reversión de una transacción (rollback).
     *
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $roundId        Identificador de la ronda.
     * @param string $transactionId  ID de la transacción a revertir.
     * @param string $player         Identificador del jugador.
     * @param array  $datos          Datos adicionales de la transacción.
     *
     * @return string Respuesta en formato JSON con el estado, balance, referencia y moneda.
     * @throws Exception Si ocurre un error durante la reversión.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos)
    {


        $this->data = $datos;

        try
        {

            // Obtenemos el Proveedor con el abreviado EAGAMING
            $Proveedor = new Proveedor("", "EAGAMING");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($rollbackAmount);


            // Obtenemos el Usuario Token con el token
            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            // Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $this->transaccionApi->setIdentificador($roundId . "EAGAMING");

            $Game = new Game();

            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi);

            $this->transaccionApi = $responseG->transaccionApi;


            $return = array(
                "status" => 200,
                "balance" => (($responseG->saldo)),
                "referenceId" => $responseG->transaccionId,
                "currency" => $UsuarioMandante->getMoneda()
            );

            // Guardamos la Transaccion Api necesaria de estado OK
            $this->transaccionApi->setRespuestaCodigo("OK");
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
     * Realiza un crédito en la cuenta del usuario.
     *
     * @param string  $gameId        ID del juego.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       Identificador de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param array   $datos         Datos adicionales de la transacción.
     * @param boolean $isfree        Indica si es un crédito gratuito.
     *
     * @return string Respuesta en formato JSON con el estado, balance, referencia y moneda.
     * @throws Exception Si ocurre un error durante el crédito.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos, $isfree = false)
    {
        $this->data = $datos;

        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try
        {

            // Obtenemos el Proveedor con el abreviado EAGAMING
            $Proveedor = new Proveedor("", "EAGAMING");

            // Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);
            $this->transaccionApi->setIdentificador($roundId . "EAGAMING");

            // Obtenemos el Usuario Token con el token
            try {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } catch (Exception $e) {
                if ($e->getCode() == 21) {
                    $TransaccionJuego = new TransaccionJuego("", $roundId . "EAGAMING");
                    $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                }
            }

            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());

            $Game = new Game();

            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, true, false, $isfree);

            $this->transaccionApi = $responseG->transaccionApi;


            // Retornamos el mensaje satisfactorio
            $return = array(
                "status" => 200,
                "balance" => (($responseG->saldo)),
                "referenceId" => $responseG->transaccionId,
                "currency" => $responseG->moneda
            );

            // Guardamos la Transaccion Api necesaria de estado OK
            $this->transaccionApi->setRespuestaCodigo("OK");
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
     * Convierte un error en una respuesta JSON manejable.
     *
     * @param integer $code    Código del error.
     * @param string  $message Mensaje del error.
     *
     * @return string Respuesta en formato JSON con el código y mensaje del error.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";
        $response = array();

        $Proveedor = new Proveedor("", "EAGAMING");

        switch ($code) {
            case 10011:
                $codeProveedor = "403";
                $messageProveedor = "No such session.";
                break;

            case 21:
                $codeProveedor = "403";
                $messageProveedor = "No such session.";
                break;

            case 22:
                $codeProveedor = "403";
                $messageProveedor = "No such session.";
                break;

            case 20001:
                $codeProveedor = "403";
                $messageProveedor = "Insufficient funds.";
                break;

            case 0:
                $codeProveedor = "403";
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 27:
                $codeProveedor = "403";
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 28:
                $codeProveedor = "403";
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 29:
                $codeProveedor = "403";
                $messageProveedor = "Transaction Not Found";

                $tipo = $this->transaccionApi->getTipo();

                if ($tipo == "ROLLBACK") {
                    $codeProveedor = '';
                    $messageProveedor = "";

                    if ($this->token != "") {
                        try {
                            /*  Obtenemos el Usuario Token con el token */
                            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                            /*  Obtenemos el Usuario Mandante con el Usuario Token */
                            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                        } catch (Exception $e) {
                            $TransaccionJuego = new TransaccionJuego("", $this->roundId . "EAGAMING");
                            $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                        }
                    } else {
                        $TransaccionJuego = new TransaccionJuego("", $this->roundId . "EAGAMING");
                        $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                    }

                    $Mandante = new Mandante($UsuarioMandante->mandante);

                    if ($Mandante->propio == "S") {
                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                        $Balance = $Usuario->getBalance();

                        $response = array_merge($response, array(
                            "balance" => (($Balance)),
                        ));
                    }
                }

                break;

            case 10001:

                $codeProveedor = 0;
                $messageProveedor = "Already processed";

                if ($this->token != "") {
                    try {
                        /*  Obtenemos el Usuario Token con el token */
                        $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                        /*  Obtenemos el Usuario Mandante con el Usuario Token */
                        $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                    } catch (Exception $e) {
                        $TransaccionJuego = new TransaccionJuego("", $this->roundId . "EAGAMING");
                        $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                    }
                } else {
                    $TransaccionJuego = new TransaccionJuego("", $this->roundId . "EAGAMING");
                    $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                }

                $Mandante = new Mandante($UsuarioMandante->mandante);

                if ($Mandante->propio == "S") {
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $Balance = $Usuario->getBalance();

                    $response = array_merge($response, array(
                        "balance" => (($Balance)),
                        "currency" => $UsuarioMandante->getMoneda(),
                        "message" => "Already processed"

                    ));
                }


                break;

            case 10004:
                $codeProveedor = "REQUEST_DECLINED";
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 10014:
                $codeProveedor = "REQUEST_DECLINED";
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 10010:
                $codeProveedor = "REQUEST_DECLINED";
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 20001:
                $codeProveedor = "403";
                $messageProveedor = "Insufficients Funds";
                break;

            case 20002:
                break;

            case 20003:
                $codeProveedor = "ACCOUNT_BLOCKED";
                $messageProveedor = "ACCOUNT_BLOCKED";
                break;

            case 10005:

                try {
                    if ($this->token != "") {
                        try {
                            /*  Obtenemos el Usuario Token con el token */
                            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                            /*  Obtenemos el Usuario Mandante con el Usuario Token */
                            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                        } catch (Exception $e) {
                            $TransaccionJuego = new TransaccionJuego("", $this->roundId . "EAGAMING");
                            $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                        }
                    } else {
                        $TransaccionJuego = new TransaccionJuego("", $this->roundId . "EAGAMING");
                        $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                    }

                    $Mandante = new Mandante($UsuarioMandante->mandante);

                    if ($Mandante->propio == "S") {
                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                        $Balance = $Usuario->getBalance();

                        $response = array_merge($response, array(
                            "balance" => (($Balance)),
                        ));
                    }
                } catch (Exception $e) {
                    $codeProveedor = "400";
                    $messageProveedor = "No such session.";
                }

                $this->transaccionApi->setValor(0);
                break;

            default:
                $codeProveedor = '500';
                $messageProveedor = "Unexpected error. (" . $code . ")";
                break;
        }


        if ($codeProveedor != "") {
            $respuesta = json_encode(array_merge($response, array(
                "status" => intval($codeProveedor),
                "msg" => $messageProveedor
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