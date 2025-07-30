<?php

/**
 * Script para analizar código PHP y generar documentación PHPDoc automáticamente.
 *
 * Esta clase contiene métodos para manejar la autenticación, balance, débitos, créditos,
 * y rollbacks relacionados con las transacciones de juegos del proveedor REDRAKE.
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
use Backend\dto\Subproveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransaccionApi;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\dto\TransaccionJuego;
use Backend\mysql\TransaccionApiMySqlDAO;

/**
 * Clase RedRake.
 *
 * Esta clase maneja la integración con el proveedor REDRAKE, permitiendo realizar
 * operaciones como autenticación, consulta de balance, débitos, créditos y rollbacks.
 */
class RedRake
{
    /**
     * Token de autenticación.
     *
     * @var string
     */
    private $token;

    /**
     * Firma de autenticación.
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
     * Datos adicionales para las transacciones.
     *
     * @var mixed
     */
    private $data;

    /**
     * Constructor de la clase RedRake.
     *
     * @param string $token Token de autenticación.
     * @param string $sign  Firma de autenticación.
     */
    public function __construct($token, $sign)
    {
        $this->token = $token;
        $this->sign = $sign;
    }

    /**
     * Método para autenticar al usuario.
     *
     * @return string Respuesta en formato JSON con los datos de autenticación.
     * @throws Exception Si el token está vacío o ocurre un error en la autenticación.
     */
    public function Auth()
    {
        try {
            $Proveedor = new Proveedor("", "REDRAKE");

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
                $Balance = number_format($Usuario->getBalance(), 2, '.', '');

                $return = array(
                    "status" => array(
                        "code" => 0,
                        "msg" => ""
                    ),
                    "response" => array(
                        "accountid" => 'Usuario' . $UsuarioMandante->getUsumandanteId(),
                        "username" => 'Usuario' . $UsuarioMandante->getUsumandanteId(),
                        "currency" => $UsuarioMandante->getMoneda(),
                        "balance" => $Balance
                    ),
                );

                return json_encode($return, JSON_PRESERVE_ZERO_FRACTION);
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para obtener el balance del usuario.
     *
     * @return string Respuesta en formato JSON con el balance del usuario.
     * @throws Exception Si el token está vacío o ocurre un error al obtener el balance.
     */
    public function getBalance()
    {
        try {
            $Proveedor = new Proveedor("", "REDRAKE");

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
            $responseG = $Game->getBalance($UsuarioMandante); //Revisar la funcion getBalance dentro de Game

            $return = array(
                "status" => array(
                    "code" => 0,
                    "msg" => ""
                ),
                "response" => array(
                    "balance" => $responseG->saldo
                ),
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para realizar un débito en la cuenta del usuario.
     *
     * @param string  $gameId        ID del juego.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param mixed   $datos         Datos adicionales.
     * @param boolean $free          Indica si es una transacción gratuita.
     *
     * @return string Respuesta en formato JSON con el balance actualizado.
     * @throws Exception Si el token está vacío, el monto es negativo o ocurre un error.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $free = false)
    {
        $this->data = $datos;

        try {
            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            // Obtenemos el Proveedor con el abreviado REDRAKE
            $Proveedor = new Proveedor("", "REDRAKE");

            // Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);

            $timeInit = time();

            // Verificamos que el monto a debitar sea positivo
            if ($debitAmount < 0) {
                throw new Exception("No puede ser negativo el monto a debitar.", "10002");
            }

            // Obtenemos el Usuario Token con el token
            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            // Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $this->transaccionApi->setIdentificador($roundId . "REDRAKE");

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
                "status" => array(
                    "code" => 0,
                    "msg" => ""
                ),
                "response" => array(
                    "balance" => $responseG->saldo
                ),
            );

            //  Guardamos la Transaccion Api necesaria de estado OK
            $this->transaccionApi->setRespuesta(json_encode($return));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            try {
                if ($UsuarioMandante->usumandanteId == 16) {
                    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . ((time() - $timeInit) * 1000) . 'msFinal ' . $this->transaccionApi->getTransaccionId() . ' ' . json_encode($return) . "' '#virtualsoft-cron' > /dev/null & ");
                }
            } catch (Exception $e) {
            }

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para realizar un rollback en la cuenta del usuario.
     *
     * Este método revierte una transacción previa asociada a un juego.
     *
     * @param string $gameId         ID del juego.
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $roundId        ID de la ronda.
     * @param string $transactionId  ID de la transacción original.
     * @param mixed  $datos          Datos adicionales para la transacción.
     *
     * @return string Respuesta en formato JSON con el balance actualizado.
     * @throws Exception Si ocurre un error durante el proceso de rollback.
     */
    public function Rollback($gameId, $rollbackAmount, $roundId, $transactionId, $datos)
    {
        $this->data = $datos;

        try {
            // Obtenemos el Proveedor con el abreviado REDRAKE
            $Proveedor = new Proveedor("", "REDRAKE");

            // Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($rollbackAmount);

            try {
                $SubProveedor = new Subproveedor("", "REDRAKE");
                $TransjuegoLog = new TransjuegoLog("", "", "", $transactionId . '_' . $SubProveedor->getSubproveedorId(), $SubProveedor->getSubproveedorId());
                $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                $this->transaccionApi->setIdentificador($TransaccionJuego->getTicketId());
            } catch (Exception $e) {
                if ($_REQUEST['isDebug'] == '1') {
                    print_r($e);
                }
                throw new Exception("Transaccion no existe", "10005");
            }

            $Game = new Game();
            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false);

            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "status" => array(
                    "code" => 0,
                    "msg" => ""
                ),
                "response" => array(
                    "balance" => $responseG->saldo
                ),
            );

            //  Guardamos la Transaccion Api necesaria de estado OK
            $this->transaccionApi->setRespuesta(json_encode($return));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return json_encode($return);
        } catch (Exception $e) {
            if ($_REQUEST['isDebug'] == '1') {
                print_r($e);
            }
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para realizar un crédito en la cuenta del usuario.
     *
     * Este método permite acreditar un monto en la cuenta del usuario asociado a un juego.
     *
     * @param string  $gameId        ID del juego.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción. Si está vacío, se genera automáticamente.
     * @param mixed   $datos         Datos adicionales para la transacción.
     * @param boolean $isEndRound    Indica si es el final de la ronda.
     * @param boolean $free          Indica si es una transacción gratuita.
     *
     * @return string Respuesta en formato JSON con el balance actualizado.
     * @throws Exception Si ocurre un error durante el proceso de crédito.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos, $isEndRound = false, $free = false)
    {
        $this->data = $datos;

        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try {
            // Obtenemos el Proveedor con el abreviado REDRAKE
            $Proveedor = new Proveedor("", "REDRAKE");

            // Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);
            $this->transaccionApi->setIdentificador($roundId . "REDRAKE");

            try {
                $TransaccionJuego = new TransaccionJuego("", $roundId . "REDRAKE");
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            $ProductoMandante = new ProductoMandante("", "", $TransaccionJuego->productoId);
            /*  Obtenemos el producto con el $TransaccionJuego->productoId */
            $Producto = new Producto($ProductoMandante->productoId);

            //  Agregamos Elementos a la Transaccion API
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());

            $Game = new Game();
            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $isEndRound, false, $free);

            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "status" => array(
                    "code" => 0,
                    "msg" => ""
                ),
                "response" => array(
                    "balance" => $responseG->saldo
                ),
            );

            //  Guardamos la Transaccion Api necesaria de estado OK
            $this->transaccionApi->setRespuesta(json_encode($return));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return json_encode($return);
        } catch (Exception $e) {
            if ($_REQUEST['isDebug'] == '1') {
                print_r($e);
            }
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Convierte un error en una respuesta JSON adecuada.
     *
     * Este método toma un código de error y un mensaje, y los convierte en una respuesta JSON
     * que incluye un código de estado HTTP y un mensaje descriptivo. También registra la transacción
     * en caso de que sea necesario.
     *
     * @param integer $code    Código de error.
     * @param string  $message Mensaje descriptivo del error.
     *
     * @return string Respuesta en formato JSON con el código y mensaje del error.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";
        $response = array();

        $Proveedor = new Proveedor("", "REDRAKE");

        switch ($code) {
            case 10011:
                $codeProveedor = 3;
                $messageProveedor = "Supplied token is no longer valid or incorrect";
                http_response_code(404);
                break;

            case 21:
                $codeProveedor = 6;
                $messageProveedor = "Token not found";
                http_response_code(404);
                break;

            case 10013:
                $codeProveedor = 7;
                $messageProveedor = "User not found";
                http_response_code(404);
                break;

            case 22:
                $codeProveedor = 7;
                //$codeProveedor = "TOKEN_NOT_FOUND";
                $messageProveedor = "User not found";
                http_response_code(404);
                break;

            case 20001:
                $codeProveedor = 5;
                $messageProveedor = "Player has insufficient funds";
                http_response_code(402);
                break;

            case 20003:
                $codeProveedor = 4;
                $messageProveedor = "Usuario Inactivo";
                http_response_code(404);
                break;

            case 27:
                $codeProveedor = 6;
                $messageProveedor = "General Error. (" . $code . ")";
                http_response_code(500);
                break;

            case 28:
                $codeProveedor = 6;
                $messageProveedor = "Transaction error";
                http_response_code(404);
                break;

            case 29:
                $codeProveedor = 6;
                $messageProveedor = "Transaction error";
                http_response_code(404);
                break;

            case 10002:
                $codeProveedor = 7;
                $messageProveedor = "Transaction error - Monto Negativo";
                http_response_code(404);
                break;

            case 20027:
                $codeProveedor = 4;
                $messageProveedor = "Invalid user account";
                http_response_code(404);
                break;

            case 20024:
                $codeProveedor = 4;
                $messageProveedor = "Invalid user account - contingency";
                http_response_code(404);
                break;

            case 10001:
                $codeProveedor = "INTERNAL_ERROR";
                $messageProveedor = "General Error. (" . $code . ")";
                http_response_code(500);

                $SubProveedor = new Subproveedor("", "REDRAKE");

                $TransjuegoLog = new TransjuegoLog("", '', '', $this->transaccionApi->getTransaccionId() . '_' . $SubProveedor->getSubproveedorId(), $SubProveedor->getSubproveedorId());
                $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);

                $codeProveedor = "";
                $messageProveedor = "";

                $Game = new Game();
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());

                $responseG = $Game->getBalance($UsuarioMandante);

                $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));
                $saldo = intval($saldo * 1);

                /*  Retornamos el mensaje satisfactorio  */
                $response = array(
                    "status" => array(
                        "code" => 0,
                        "msg" => ""
                    ),
                    "response" => array(
                        "balance" => $saldo
                    ),
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

                $return = array(
                    "status" => array(
                        "code" => 0,
                        "msg" => ""
                    ),
                    "response" => array(
                        "balance" => 0
                    ),
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
                $codeProveedor = 6;
                $messageProveedor = "Transaction error";
                http_response_code(404);
                break;

            default:
                $codeProveedor = 6;
                $messageProveedor = "General Error. (" . $code . ")";
                http_response_code(500);
                break;
        }

        if ($codeProveedor != "") {
            $respuesta = json_encode(array_merge($response, array(
                "status" => array(
                    "code" => $codeProveedor,
                    "msg" => $messageProveedor
                ),
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
                if ($_REQUEST['isDebug'] == '1') {
                    print_r('ENTRO');
                }

                $response = array(
                    "balance" => 0
                );

                $response = array(
                    "status" => array(
                        "code" => 0,
                        "msg" => ""
                    ),
                    "response" => array(
                        "balance" => 0
                    ),
                );

                $respuesta = json_encode(array_merge($response));

                http_response_code(200);
            }
        }

        return $respuesta;
    }
}
