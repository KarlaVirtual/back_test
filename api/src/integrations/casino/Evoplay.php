<?php

/**
 * Clase Evoplay para la integración con el proveedor de juegos EVOPLAY.
 *
 * Esta clase contiene métodos para manejar transacciones relacionadas con juegos,
 * como autenticación, consulta de balance, débito, crédito, reversión de transacciones,
 * y manejo de errores. También incluye métodos auxiliares para verificar parámetros
 * y convertir errores en respuestas JSON.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
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
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioToken;
use Backend\dto\Producto;
use Backend\dto\ProductoMandante;
use Backend\dto\Proveedor;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransaccionApiMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\websocket\WebsocketUsuario;
use Exception;

/**
 * Clase principal para la integración con el proveedor de juegos EVOPLAY.
 *
 * Esta clase contiene métodos para manejar transacciones relacionadas con juegos,
 * como autenticación, consulta de balance, débito, crédito, reversión de transacciones,
 * y manejo de errores. También incluye métodos auxiliares para verificar parámetros
 * y convertir errores en respuestas JSON.
 */
class Evoplay
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
     * Objeto para manejar transacciones API.
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
     * Identificador de la ronda principal.
     *
     * @var string
     */
    private $roundIdSuper;

    /**
     * Constructor de la clase Evoplay.
     *
     * @param string $token Token de autenticación.
     * @param string $sign  Firma de seguridad.
     */
    public function __construct($token, $sign)
    {
        $this->token = $token;
        $this->sign = $sign;
    }

    /**
     * Obtiene el ID del operador.
     *
     * @return string ID del operador.
     */
    public function getOperadorId()
    {
        return $this->operadorId;
    }

    /**
     * Autentica al usuario y devuelve su balance y moneda.
     *
     * @return string Respuesta en formato JSON con el balance y moneda del usuario.
     */
    public function Auth()
    {
        try {
            $Proveedor = new Proveedor("", "EVOPLAY");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);


            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $Mandante = new Mandante($UsuarioMandante->mandante);

            if ($Mandante->propio == "S") {
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                $Balance = round($Usuario->getBalance() * 1, 2);


                $return = array(
                    "status" => "ok",
                    "data" => array(
                        "currency" => $UsuarioMandante->getMoneda(),
                        "balance" => $Balance

                    ),

                );

                return json_encode($return);
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del usuario autenticado.
     *
     * @return string Respuesta en formato JSON con el balance y moneda del usuario.
     */
    public function getBalance()
    {
        try {
            $Proveedor = new Proveedor("", "EVOPLAY");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("BALANCE");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);


            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $Mandante = new Mandante($UsuarioMandante->mandante);

            if ($Mandante->propio == "S") {
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                $Balance = round($Usuario->getBalance() * 1, 2, PHP_ROUND_HALF_DOWN);

                $return = array(
                    "status" => "ok",
                    "data" => array(
                        "balance" => $Balance,
                        "currency" => $Usuario->moneda

                    ),

                );
                return json_encode($return);
            }
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
     * @param array   $datos         Datos adicionales.
     * @param boolean $free          Indica si es una transacción gratuita.
     *
     * @return string Respuesta en formato JSON con el balance actualizado.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $free = false)
    {
        $this->data = $datos;

        try {
            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            // Obtenemos el Proveedor con el abreviado EVOPLAY
            $Proveedor = new Proveedor("", "EVOPLAY");

            // Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);


            // Verificamos que el monto a debitar sea positivo
            if ($debitAmount < 0) {
                throw new Exception("No puede ser negativo el monto a debitar.", "10002");
            }

            // Obtenemos el Usuario Token con el token
            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            // Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $this->transaccionApi->setIdentificador($roundId . "EVOPLAY");

            // Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            // Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            // Agregamos Elementos a la Transaccion API
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());

            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $free);

            $this->transaccionApi = $responseG->transaccionApi;


            $return = array(
                "status" => "ok",
                "data" => array(
                    "balance" => $responseG->saldo * 1,
                    "currency" => $responseG->moneda

                ),

            );
            //  Guardamos la Transaccion Api necesaria de estado OK
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
     * @param string $gameId         ID del juego.
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $roundId        ID de la ronda.
     * @param string $transactionId  ID de la transacción original.
     * @param array  $datos          Datos adicionales relacionados con la transacción.
     *
     * @return string Respuesta en formato JSON con el balance actualizado o un error.
     */
    public function Rollback($gameId, $rollbackAmount, $roundId, $transactionId, $datos)
    {
        $this->data = $datos;


        try {
            $Proveedor = new Proveedor("", "EVOPLAY");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($rollbackAmount);

            $transaccionNoExiste = false;

            try {
                $TransaccionApi2 = new TransaccionApi("", $transactionId, $Proveedor->getProveedorId());
                $jsonValue = json_decode($TransaccionApi2->getTValue());
                $valorTransaction = 0;

                $UsuarioMandante = new UsuarioMandante($TransaccionApi2->getUsuarioId());
                if (strpos($TransaccionApi2->getTipo(), 'DEBIT') !== false) {
                } else {
                    throw new Exception("Transaccion no es Debit", "10006");
                }
            } catch (Exception $e) {
                $transaccionNoExiste = true;
                throw new Exception("Transaccion no existe", "10005");
            }


            $this->transaccionApi->setIdentificador($roundId . "EVOPLAY");


            $Game = new Game();

            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi);

            $this->transaccionApi = $responseG->transaccionApi;


            $return = array(
                "status" => "ok",
                "data" => array(
                    "balance" => $responseG->saldo * 1,
                    "currency" => $responseG->moneda

                ),

            );

            //  Guardamos la Transaccion Api necesaria de estado OK
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
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param array   $datos         Datos adicionales relacionados con la transacción.
     * @param boolean $isEndRound    Indica si es el final de la ronda.
     *
     * @return string Respuesta en formato JSON con el balance actualizado o un error.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos, $isEndRound = false)
    {
        $this->data = $datos;

        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try {
            $Proveedor = new Proveedor("", "EVOPLAY");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);

            try {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } catch (Exception $e) {
                if ($e->getCode() == 21) {
                    $TransaccionApi2 = new TransaccionApi("", $transactionId, $Proveedor->getProveedorId());

                    $UsuarioMandante = new UsuarioMandante($TransaccionApi2->getUsuarioId());
                }
            }

            $this->transaccionApi->setIdentificador($roundId . "EVOPLAY");


            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());


            $Game = new Game();

            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $isEndRound);

            $this->transaccionApi = $responseG->transaccionApi;


            $return = array(
                "status" => "ok",
                "data" => array(
                    "balance" => $responseG->saldo * 1,
                    "currency" => $responseG->moneda

                ),

            );
            //  Guardamos la Transaccion Api necesaria de estado OK
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
     * Verifica un parámetro y devuelve una respuesta con información adicional.
     *
     * @param mixed $param Parámetro a verificar.
     *
     * @return string Respuesta en formato JSON con el nodo, el parámetro y la firma.
     */
    public function Check($param)
    {
        $return = array(

            "nodeId" => 123,
            "param" => $param,
            "sign" => $this->sign
        );

        return json_encode($return);
    }

    /**
     * Convierte un código de error y un mensaje en una respuesta estructurada en formato JSON.
     *
     * Este método mapea códigos de error a códigos y mensajes específicos del proveedor,
     * y opcionalmente incluye información adicional como el estado de reembolso.
     * También registra el error en la API de transacciones y actualiza el estado de la transacción si es aplicable.
     *
     * @param integer $code    Código de error a convertir.
     * @param string  $message Mensaje de error a convertir.
     *
     * @return string Respuesta codificada en JSON con los detalles del error o el balance actualizado.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";
        $response = array();

        $no_refund = "";

        $Proveedor = new Proveedor("", "EVOPLAY");

        switch ($code) {
            case 10011:
                $codeProveedor = 6;
                $codeProveedor = "TOKEN_NOT_FOUND";
                $messageProveedor = "Token not found";
                break;

            case 21:
                $codeProveedor = 6;
                $codeProveedor = "TOKEN_NOT_FOUND";
                $messageProveedor = "Token not found";
                break;

            case 10013:
                $codeProveedor = 7;
                $codeProveedor = "TOKEN_NOT_FOUND";
                $messageProveedor = "User not found";
                break;

            case 22:
                $codeProveedor = 7;
                $codeProveedor = "TOKEN_NOT_FOUND";
                $messageProveedor = "User not found";
                break;

            case 20001:
                $codeProveedor = "INSUFFICIENT_FUNDS";
                $messageProveedor = "Player has insufficient funds";

                $no_refund = "1";


                break;

            case 0:
                $codeProveedor = "INTERNAL_ERROR";
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 27:
                $codeProveedor = "INTERNAL_ERROR";
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 28:
                $codeProveedor = "INTERNAL_ERROR";
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 29:
                $codeProveedor = "INTERNAL_ERROR";
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 10001:
                $codeProveedor = "INTERNAL_ERROR";
                $messageProveedor = "General Error. (" . $code . ")";


                $TransaccionApi = new TransaccionApi("", $this->transaccionApi->getTransaccionId(), $Proveedor->getProveedorId());

                $codeProveedor = "";
                $messageProveedor = "";

                $Game = new Game();
                $UsuarioMandante = new UsuarioMandante($TransaccionApi->getUsuarioId());

                $responseG = $Game->getBalance($UsuarioMandante);

                $saldo = $responseG->saldo * 1;

                $response = array(
                    "status" => "ok",
                    "data" => array(
                        "balance" => $saldo,
                        "currency" => $responseG->moneda

                    ),

                );

                break;

            case 10004:
                $codeProveedor = "";
                $messageProveedor = "General Error. (" . $code . ")";

                $response = array(
                    "balance" => 0
                );
                $return = array(
                    "status" => "ok",
                    "data" => array(
                        "balance" => 0

                    ),

                );

                break;

            case 10005:
                $codeProveedor = "10005";
                $messageProveedor = "General Error. (" . $code . ")";
                $messageProveedor = "Transaction not found";

                $no_refund = "1";

                break;

            case 10014:
                $codeProveedor = "INTERNAL_ERROR";
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 10010:
                $codeProveedor = "INTERNAL_ERROR";
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            default:
                $codeProveedor = "INTERNAL_ERROR";
                $messageProveedor = "General Error. (" . $code . ")";
                break;
        }


        if ($codeProveedor != "") {
            $arrayscope = array(
                "scope" => "user",
                "message" => $messageProveedor
            );

            if ($no_refund != "") {
                $arrayscope = array_merge($arrayscope, array("no_refund" => $no_refund));
            }

            $respuesta = json_encode(array_merge($response, array(
                "status" => "error",
                "error" => $arrayscope,
            )));
        } else {
            $respuesta = json_encode(array_merge($response));
        }

        if ($this->transaccionApi != null) {
            $this->transaccionApi->setTipo("R" . $this->transaccionApi->getTipo());
            $this->transaccionApi->setRespuestaCodigo("ERROR_" . $code);
            $this->transaccionApi->setRespuesta($respuesta);

            if ($this->transaccionApi->getValor == "") {
                $this->transaccionApi->setValor(0);
            }

            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->insert($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();


            if ($this->transaccionApi->getTipo() == "RROLLBACK" && ($code == 10004 || $code == 10005)) {
                $saldo = 0;
                $moneda = '';
                if ($this->token != '') {
                    try {
                        $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

                        $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                        $Game = new Game();
                        $responseG = $Game->getBalance($UsuarioMandante);

                        $saldo = $responseG->saldo * 1;
                        $moneda = $responseG->moneda;
                    } catch (Exception $e) {
                    }
                }
                $respuesta = json_encode(array_merge($response, array(
                    "status" => "ok",
                    "data" => array(
                        "balance" => $saldo,
                        "currency" => $moneda

                    ),
                )));
            }
        }


        return $respuesta;
    }


}