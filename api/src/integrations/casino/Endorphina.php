<?php

/**
 * Clase para la integración con el proveedor de juegos Endorphina.
 *
 * Este archivo contiene métodos para manejar transacciones relacionadas con juegos,
 * como autenticación, consulta de balance, débitos, créditos, y rollbacks.
 * También incluye manejo de errores específicos del proveedor.
 *
 * @category Red
 * @package  API
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
use Backend\dto\TransaccionApi;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\mysql\TransaccionApiMySqlDAO;

/**
 * Clase Endorphina.
 *
 * Proporciona métodos para interactuar con el proveedor de juegos Endorphina,
 * incluyendo autenticación, balance, débitos, créditos, y manejo de errores.
 */
class Endorphina
{

    /**
     * metodo de operacion.
     *
     * @var string
     */
    private $method;

    /**
     * Token de autenticación.
     *
     * @var string
     */
    private $token;

    /**
     * Firma de seguridad.
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
     * Datos adicionales de la transacción.
     *
     * @var array
     */
    private $data;

    /**
     * Identificador del jugador.
     *
     * @var string
     */
    private $player;

    /**
     * Constructor de la clase Endorphina.
     *
     * @param string $token  Token de autenticación.
     * @param string $sign   Firma de seguridad.
     * @param string $player Opcional Identificador del jugador.
     */
    public function __construct($token, $sign, $player = "")
    {
        $this->token = $token;
        $this->sign = $sign;
        $this->player = $player;
    }

    /**
     * Autentica al usuario con el proveedor Endorphina.
     *
     * @return string Respuesta en formato JSON con los datos del jugador y el juego.
     * @throws Exception Si el token está vacío o ocurre un error durante la autenticación.
     */
    public function Auth()
    {
        $this->method = "AUTH";
        try {
            $Proveedor = new Proveedor("", "ENPH");

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
                $token_explode = explode("vssv", $this->token);

                $game = new Producto($token_explode[1]);

                $gameExt = str_replace("%40", "@", $game->externoId);

                $respuesta = json_encode(array(
                    "player" => "Usuario" . $UsuarioMandante->usumandanteId,
                    "currency" => $Usuario->moneda,
                    "game" => $gameExt
                ));
                return $respuesta;
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del jugador.
     *
     * @return string Respuesta en formato JSON con el balance del jugador.
     * @throws Exception Si el token está vacío o ocurre un error durante la consulta.
     */
    public function getBalance()
    {
        $this->method = "BALANCE";
        try {
            $Proveedor = new Proveedor("", "ENPH");

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

            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Balance = intval(round($Usuario->getBalance(), 2) * 1000);

            $respuesta = json_encode(array(
                "balance" => $Balance
            ));

            return $respuesta;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un débito en la cuenta del jugador.
     *
     * @param string  $gameId        ID del juego.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param array   $datos         Datos adicionales de la transacción.
     * @param boolean $isfreeSpin    Indica si es un giro gratis (opcional).
     *
     * @return string Respuesta en formato JSON con el balance actualizado y el ID de la transacción.
     * @throws Exception Si el token o el jugador no están definidos, o si ocurre un error durante el débito.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $isfreeSpin = false)
    {
        $this->data = $datos;
        $this->method = "DEBIT";
        try {
            if ($this->token == "" && $this->player == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado ENPH */
            $Proveedor = new Proveedor("", "ENPH");

            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
            } else {
                /*  Obtenemos el Usuario Token con el usuario */
                $UsuarioToken = new UsuarioToken('', $Proveedor->getProveedorId(), $this->player);
            }

            /*  Obtenemos el Usuario Mandante con el Usuario Token */
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            /*  Obtenemos el producto con el gameId  */
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            // Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);
            $this->transaccionApi->setIdentificador($roundId . "ENPH" . $UsuarioMandante->getUsumandanteId());

            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $isfreeSpin);
            $this->transaccionApi = $responseG->transaccionApi;

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Balance = intval(round($Usuario->getBalance(), 2) * 1000);

            $respuesta = json_encode(array(
                "transactionId" => $responseG->transaccionId,
                "balance" => $Balance
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
     * @param string $gameId         ID del juego.
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $roundId        ID de la ronda.
     * @param string $transactionId  ID de la transacción original.
     * @param string $player         Identificador del jugador.
     * @param array  $datos          Datos adicionales de la transacción.
     *
     * @return string Respuesta en formato JSON con el balance actualizado y el ID de la transacción.
     * @throws Exception Si ocurre un error durante el rollback.
     */
    public function Rollback($gameId, $rollbackAmount, $roundId, $transactionId, $player, $datos)
    {
        $usuarioid = explode("Usuario", $player)[1];
        $this->data = $datos;
        $this->method = "ROLLBACK";
        try {
            // Obtenemos el Proveedor con el abreviado ENPH
            $Proveedor = new Proveedor("", "ENPH");

            // Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($rollbackAmount);

            $UsuarioMandante = new UsuarioMandante($usuarioid);

            $this->transaccionApi->setIdentificador($roundId . "ENPH" . $UsuarioMandante->getUsumandanteId());

            $Game = new Game();
            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi);
            $this->transaccionApi = $responseG->transaccionApi;

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Balance = intval(round($Usuario->getBalance(), 2) * 1000);

            $respuesta = json_encode(array(
                "balance" => $Balance,
                "transactionId" => $this->transaccionApi->transapiId
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
     * Realiza un crédito en la cuenta del jugador.
     *
     * @param string  $gameId        ID del juego.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción (opcional).
     * @param array   $datos         Datos adicionales de la transacción.
     * @param boolean $isFreeSpin    Indica si es un giro gratis (opcional).
     *
     * @return string Respuesta en formato JSON con el balance actualizado y el ID de la transacción.
     * @throws Exception Si ocurre un error durante el crédito.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos, $isFreeSpin = false)
    {
        $this->data = $datos;
        $this->method = "CREDIT";
        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try {
            // Obtenemos el Proveedor con el abreviado ENPH
            $Proveedor = new Proveedor("", "ENPH");

            // Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);

            /*  Obtenemos el Usuario Token con el token */
            try {
                if ($this->token != "") {
                    /*  Obtenemos el Usuario Token con el token */
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                } else {
                    /*  Obtenemos el Usuario Token con el usuario */
                    $UsuarioToken = new UsuarioToken('', $Proveedor->getProveedorId(), $this->player);
                }
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } catch (Exception $e) {
                if ($e->getCode() == 21) {
                    $TransaccionJuego = new TransaccionApi("", $roundId, $Proveedor->getProveedorId());

                    $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                }
            }

            $this->transaccionApi->setIdentificador($roundId . "ENPH" . $UsuarioMandante->getUsumandanteId());

            //  Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            //  Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            $Game = new Game();
            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, true, false, $isFreeSpin);

            $this->transaccionApi = $responseG->transaccionApi;
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Balance = intval(round($Usuario->getBalance(), 2) * 1000);

            $respuesta = json_encode(array(
                "balance" => $Balance,
                "transactionId" => $this->transaccionApi->transapiId
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
     * Convierte errores en respuestas JSON manejables.
     *
     * @param integer $code    Código de error.
     * @param string  $message Mensaje de error.
     *
     * @return string Respuesta en formato JSON con el código y mensaje de error.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";
        $response = array();

        $Proveedor = new Proveedor("", "ENPH");

        switch ($code) {
            case 10011:
                $codeProveedor = "TOKEN_NOT_FOUND";
                $messageProveedor = "Token not found";
                http_response_code(404);
                break;

            case 21:
                $codeProveedor = "TOKEN_NOT_FOUND";
                $messageProveedor = "Token not found";
                http_response_code(404);
                break;

            case 10013:
                $codeProveedor = "TOKEN_NOT_FOUND";
                $messageProveedor = "User not found";
                http_response_code(404);
                break;

            case 22:
                $codeProveedor = "TOKEN_NOT_FOUND";
                $messageProveedor = "User not found";
                http_response_code(404);
                break;

            case 20001:
                $codeProveedor = "INSUFFICIENT_FUNDS";
                $messageProveedor = "Player has insufficient funds";
                http_response_code(402);
                break;

            case 0:
                $codeProveedor = "INTERNAL_ERROR";
                $messageProveedor = "General Error. (" . $code . ")";
                http_response_code(500);
                break;

            case 27:
                $codeProveedor = "ACCESS_DENIED";
                $messageProveedor = "There is no ordering product";
                http_response_code(400);
                break;

            case 28:
                if ($this->method == "ROLLBACK") {
                    $response = array(
                        "balance" => 0,
                        "transactionId" => $this->transaccionApi->transapiId
                    );
                } else {
                    $codeProveedor = "ACCESS_DENIED";
                    $messageProveedor = "There is no Game Transaction";
                    http_response_code(400);
                }
                break;

            case 10001:
                $codeProveedor = "TRANSACTION_PROCESSED";
                $messageProveedor = "Transaction processed";
                http_response_code(200);

                $TransaccionApi = new TransaccionApi("", $this->transaccionApi->getTransaccionId(), $Proveedor->getProveedorId());

                $codeProveedor = "";
                $messageProveedor = "";

                $Game = new Game();
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($TransaccionApi->getUsuarioId());

                $responseG = $Game->getBalance($UsuarioMandante);

                $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));
                $saldo = intval($saldo * 1000);

                /*  Retornamos el mensaje satisfactorio  */
                $response = array(
                    "balance" => $saldo,
                    "transactionId" => $TransaccionApi->getTransapiId()
                );
                http_response_code(200);
                break;

            case 10004:
                $codeProveedor = "";
                $messageProveedor = "General Error. (" . $code . ")";

                /*  Retornamos el mensaje satisfactorio  */
                $response = array(
                    "balance" => 0
                );

                http_response_code(200);
                break;

            case 10005:
                $codeProveedor = "";
                $messageProveedor = "General Error. (" . $code . ")";

                /*  Retornamos el mensaje satisfactorio  */
                $response = array(
                    "balance" => 0
                );

                http_response_code(200);
                break;

            case 10014:
                $codeProveedor = "LIMIT_REACHED";
                $messageProveedor = "General Error. (" . $code . ")";
                http_response_code(403);
                break;

            case 10010:
                $codeProveedor = "LIMIT_REACHED";
                $messageProveedor = "General Error. (" . $code . ")";
                http_response_code(403);
                break;

            default:
                $codeProveedor = "INTERNAL_ERROR";
                $messageProveedor = "General Error. (" . $code . "). (" . $message . ")";
                http_response_code(500);
                break;
        }

        if ($codeProveedor != "") {
            $respuesta = json_encode(array_merge($response, array(
                "code" => $codeProveedor,
                "message" => $message
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
                $respuesta = json_encode(array_merge($response, array(
                    "balance" => 0,
                    "transactionId" => $this->transaccionApi->transapiId
                )));

                http_response_code(200);
            }
        }

        return $respuesta;
    }
}
