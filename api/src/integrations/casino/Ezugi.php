<?php

/**
 * .PHP de la API del casino 'Ezugi'.
 * Este archivo actúa como procesamiento y respuesta de solicitudes entrantes
 * como autenticación, débitos, créditos y rollbacks desde la plataforma de Ezugi.
 *
 * @category    Documentación
 * @package     AutoDoc
 * @author      nicolas.guato@virtualsoft.tech
 * @version     1.0.0
 * @since       23/04/2025
 */

namespace Backend\integrations\casino;


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

/**
 * Clase 'Ezugi'
 *
 * Esta clase provee funciones para la api 'Ezugi'
 *
 * Ejemplo de uso:
 * $Ezugi = new Ezugi();
 *
 * @package ninguno
 * @author  nicolas.guato@virtualsoft.tech
 * @version ninguna
 * @access  public
 * @see     no
 * @since:  23/04/2025
 */
class Ezugi
{
    /**
     * Operador Id.
     *
     * Credencial para respuesta de método.
     *
     * @param string $operadorId ID del usuario (opcional).
     */
    private $operadorId;

    /**
     * Token.
     *
     * Variable global para validar métodos transaccionales.
     *
     * @param string $operadorId ID del usuario (opcional).
     */
    private $token;

    /**
     * uid.
     *
     * Variable global para responder métodos transaccionales.
     *
     * @param string $uid .
     */
    private $uid;

    /**
     * transaccionApi.
     *
     * Variable global para procesar métodos transaccionales.
     *
     * @param string $transaccionApi .
     */
    private $transaccionApi;

    /**
     * data.
     *
     * Variable global para procesar métodos transaccionales.
     *
     * @param string $data .
     */
    private $data;

    /**
     * tipo.
     *
     * Variable global para procesar métodos transaccionales.
     *
     * @param string $tipo.
     */
    private $tipo;


    /**
     * Constructor de la clase Ezugi.
     *
     * Inicializa las propiedades del usuario y configura el proveedor y el entorno.
     *
     * @param string $userId ID del usuario (opcional).
     * @param string $userName Nombre del usuario (opcional).
     */

    public function __construct($operadorId, $token, $uid = "")
    {
        $this->operadorId = $operadorId;
        $this->token = $token;
        $this->uid = $uid;
    }

    /**
     * Función Pública de la clase Ezugi.
     *
     * Devuelve el ID de Operador.
     *
     * @param string $operadorId .
     */
    public function getOperadorId()
    {
        return $this->operadorId;
    }


    /**
     * Autenticación de usuario para el proveedor Ezugi (abreviado como 'EZZG').
     *
     * Este método valida el token recibido y autentica al jugador en la plataforma.
     * También prepara una transacción de tipo 'AUTH' para registro interno.
     *
     * @access public
     */
    public function Auth()
    {
        $this->tipo = "AUTH";

        try {
            $Proveedor = new Proveedor("", "EZZG");

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
            }

            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Balance = floatval(number_format(round($Usuario->getBalance(), 2), 2, '.', ''));

            $return = array(

                "operatorId" => $this->getOperadorId(),
                "uid" => strtolower($UsuarioMandante->usumandanteId),
                "token" => $this->token,
                "balance" => $Balance,
                "currency" => $Usuario->moneda,
                "language" => strtolower($Usuario->idioma),
                "clientIP" => explode(',', $Usuario->dirIp)[0],
                "VIP" => '0',
                "errorCode" => 0,
                "errorDescription" => "ok",
                "timestamp" => (round(microtime(true) * 1000))
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Debita una cantidad del saldo del jugador para una apuesta.
     *
     * Este método es invocado cuando el jugador realiza una apuesta en un juego.
     * Se registra la transacción, se verifica el usuario a través del token y se actualiza el saldo.
     *
     * @access public
     *
     * @param string $gameId ID del juego donde se realiza la apuesta.
     * @param string $currency Moneda utilizada en la transacción.
     * @param float $debitAmount Monto a debitar del saldo del jugador.
     * @param string $roundId Identificador de la ronda en curso.
     * @param string $transactionId ID único de la transacción.
     * @param object $datos Objeto con información adicional de la transacción.
     *
     */
    public function Debit($gameId, $currency, $debitAmount, $roundId, $transactionId, $datos)
    {

        $this->data = $datos;
        $this->tipo = "DEBIT";

        try {
            $Proveedor = new Proveedor("", "EZZG");
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);
            $this->transaccionApi->setIdentificador("EZZG" . $roundId . $UsuarioMandante->getUsumandanteId());

            $Game = new Game();

            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, false, [], false, false);

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Balance = floatval(number_format(round($Usuario->getBalance(), 2), 2, '.', ''));

            $return = array(
                "operatorId" => $this->getOperadorId(),
                "uid" => $UsuarioMandante->usumandanteId,
                "token" => $this->token,
                "balance" => $Balance,
                "currency" => $currency,
                "roundId" => $roundId,
                "transactionId" => $transactionId,
                "errorCode" => 0,
                "errorDescription" => "ok",
                "timestamp" => (round(microtime(true) * 1000))
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
     * Realiza una reversión de una transacción anterior (rollback).
     *
     * Este método se utiliza para revertir una apuesta previamente realizada,
     * devolviendo el monto correspondiente al saldo del jugador. Es comúnmente usado
     * cuando una ronda es cancelada o se detecta algún fallo en la transacción original.
     *
     * @access public
     *
     * @param string $currency Moneda utilizada en la transacción original.
     * @param float $rollbackAmount Monto a devolver al jugador.
     * @param string $roundId Identificador de la ronda que se desea revertir.
     * @param string $transactionId ID original de la transacción que se quiere revertir.
     * @param object $datos Objeto con los datos completos de la transacción original.
     *
     * @return string JSON con información del estado final del rollback y del jugador.
     */
    public function Rollback($currency, $rollbackAmount, $roundId, $transactionId, $datos)
    {

        $this->data = $datos;
        $this->tipo = "ROLLBACK";

        try {

            $Proveedor = new Proveedor("", "EZZG");

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($rollbackAmount);
            $this->transaccionApi->setIdentificador("EZZG" . $roundId . $UsuarioMandante->getUsumandanteId());

            $Game = new Game();

            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false, '', '', '', false, '');

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Balance = floatval(number_format(round($Usuario->getBalance(), 2), 2, '.', ''));

            $return = array(

                "operatorId" => $this->getOperadorId(),
                "uid" => $UsuarioMandante->usumandanteId,
                "roundId" => $roundId,
                "token" => $this->token,
                "balance" => $Balance,
                "currency" => $currency,
                "transactionId" => $transactionId,
                "errorCode" => 0,
                "errorDescription" => "ok",
                "timestamp" => (round(microtime(true) * 1000))
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
     * Acredita ganancias al jugador luego de una ronda de juego.
     *
     * Este método se ejecuta cuando una ronda ha finalizado y el jugador debe recibir
     * sus ganancias correspondientes. También puede ejecutarse parcialmente si aún no
     * finaliza la ronda (según el flag `isEndRound`).
     *
     * @access public
     *
     * @param string $gameId Identificador del juego en curso.
     * @param string $currency Moneda utilizada en la transacción.
     * @param float $creditAmount Monto a acreditar al jugador.
     * @param string $roundId Identificador de la ronda de juego.
     * @param string $transactionId ID único de la transacción de crédito.
     * @param bool $isEndRound Indica si la ronda ha finalizado o no.
     * @param object $datos Objeto con datos adicionales de la transacción.
     *
     * @return string JSON con la información del nuevo balance y estado de la operación.
     */
    public function Credit($gameId, $currency, $creditAmount, $roundId, $transactionId, $isEndRound, $datos)
    {

        $this->data = $datos;
        $this->tipo = "CREDIT";

        try {

            $Proveedor = new Proveedor("", "EZZG");
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);
            $this->transaccionApi->setIdentificador("EZZG" . $roundId . $UsuarioMandante->getUsumandanteId());

            if ($isEndRound == true) {
                $End = true;
            } else {
                $End = false;
            }

            $Game = new Game();
            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $End, false, false, false);

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Balance = floatval(number_format(round($Usuario->getBalance(), 2), 2, '.', ''));

            $return = array(

                "operatorId" => $this->getOperadorId(),
                "uid" => $UsuarioMandante->usumandanteId,
                "roundId" => $roundId,
                "token" => $this->token,
                "balance" => $Balance,
                "currency" => $currency,
                "transactionId" => $transactionId,
                "errorCode" => 0,
                "errorDescription" => "ok",
                "timestamp" => (round(microtime(true) * 1000))
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
     * Genera una respuesta estandarizada para errores ocurridos durante las transacciones.
     *
     * Este método se utiliza para devolver una estructura de error controlada hacia el proveedor,
     * incluyendo información del operador, el token del jugador y el mensaje de error correspondiente.
     *
     * @access public
     *
     * @param string|int $code Código de error generado.
     * @param string $message Descripción del error ocurrido.
     *
     * @return string JSON con la información de error estandarizada.
     */
    public function convertError($code, $message)
    {

        $codeProveedor = "";
        $messageProveedor = "";


        if ($this->uid != '') {
            $response = array(

                "operatorId" => $this->getOperadorId(),
                "uid" => $this->uid,
                "token" => $this->token,
                "timestamp" => (round(microtime(true) * 1000))
            );
        } else {
            if ($code != 10030) {
                $response = array(

                    "operatorId" => $this->getOperadorId(),
                    "token" => $this->token,
                    "timestamp" => (round(microtime(true) * 1000))
                );
            } else {
                $response = array(

                    "operatorId" => $this->getOperadorId(),
                    "timestamp" => (round(microtime(true) * 1000))
                );
            }
        }
        $tipo = $this->tipo;

        if ($tipo == "DEBIT" || $tipo == "CREDIT" || $tipo == "ROLLBACK") {

            $response = array_merge($response, array(
                'transactionId' => $this->data->transactionId,
                'roundId' => $this->data->roundId
            ));
        } else {
            $response = array_merge($response, array(
                'VIP' => '0'
            ));
        }
        $Proveedor = new Proveedor("", "EZZG");

        switch ($code) {
            case 10002:
                $codeProveedor = 1;
                $messageProveedor = "Negative amount";

                if ($this->token != '') {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                    $Mandante = new Mandante($UsuarioMandante->mandante);

                    if ($Mandante->propio == "S") {
                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                        $saldo = floatval(number_format(round($Usuario->getBalance(), 2), 2, '.', ''));

                        $response = array_merge($response, array(
                            "balance" => $saldo,
                            "currency" => $UsuarioMandante->getMoneda()
                        ));
                    }
                }

                break;
            case 10003:
                $codeProveedor = 1;
                $messageProveedor = "Invalid Amount";

                if ($this->token != '') {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                    $Mandante = new Mandante($UsuarioMandante->mandante);

                    if ($Mandante->propio == "S") {
                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                        $saldo = floatval(number_format(round($Usuario->getBalance(), 2), 2, '.', ''));

                        $response = array_merge($response, array(
                            "balance" => $saldo,
                            "currency" => $UsuarioMandante->getMoneda()
                        ));
                    }
                }
                break;

            case 10011:
                $codeProveedor = 6;
                $messageProveedor = "Token not found";
                break;
            case 21:
                $codeProveedor = 6;
                $messageProveedor = "Token not found";

                $response = array_merge($response, array(
                    "balance" => 0,
                    "currency" => ''
                ));

                if ($this->uid != '') {


                    try {


                        $UsuarioMandante = new UsuarioMandante($this->uid);
                        $response = array_merge($response, array(
                            "currency" => $UsuarioMandante->getMoneda()
                        ));
                    } catch (Exception $e) {
                    }
                }

                break;
            case 10030:
                $codeProveedor = 6;
                $messageProveedor = "Token not found";

                $response = array_merge($response, array(
                    "balance" => 0
                ));

                break;
            case 10013:
                $codeProveedor = 7;
                $messageProveedor = "User not found";

                $response = array_merge($response, array(
                    "balance" => 0,
                    "currency" => ''
                ));

                try {
                    if ($this->uid != '' && is_numeric($this->uid)) {


                        try {


                            $UsuarioMandante = new UsuarioMandante($this->uid);
                            $response = array_merge($response, array(
                                "currency" => $UsuarioMandante->getMoneda()
                            ));
                        } catch (Exception $e) {
                        }
                    } elseif ($this->token != '') {
                        $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

                        $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                        $Mandante = new Mandante($UsuarioMandante->mandante);

                        if ($Mandante->propio == "S") {
                            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                            $saldo = floatval(number_format(round($Usuario->getBalance(), 2), 2, '.', ''));

                            $response = array_merge($response, array(
                                "currency" => $UsuarioMandante->getMoneda()
                            ));
                        }
                    }
                } catch (Exception $e) {
                }

                break;
            case 10005:
                $codeProveedor = 9;
                $messageProveedor = "Transaction not found";

                try {
                    if ($this->token != '') {

                        $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

                        $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                        $Mandante = new Mandante($UsuarioMandante->mandante);

                        if ($Mandante->propio == "S") {
                            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                            $saldo = floatval(number_format(round($Usuario->getBalance(), 2), 2, '.', ''));

                            $response = array_merge($response, array(
                                "balance" => $saldo,
                                "currency" => $UsuarioMandante->getMoneda()
                            ));
                        }
                    }
                } catch (Exception $e) {
                }
                break;
            case 22:
                $codeProveedor = 7;
                $messageProveedor = "User not found";

                $response = array_merge($response, array(
                    "balance" => 0,
                    "currency" => ''
                ));

                if ($this->uid != '') {


                    try {


                        $UsuarioMandante = new UsuarioMandante($this->uid);
                        $response = array_merge($response, array(
                            "currency" => $UsuarioMandante->getMoneda()
                        ));
                    } catch (Exception $e) {
                    }
                }
                break;
            case 50030:
                $codeProveedor = 9;
                $messageProveedor = "Corresponding debit transaction not found Player’s balance is not updated";

                try {
                    if ($this->token != '') {

                        $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

                        $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                        $Mandante = new Mandante($UsuarioMandante->mandante);

                        if ($Mandante->propio == "S") {
                            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                            $saldo = floatval(number_format(round($Usuario->getBalance(), 2), 2, '.', ''));

                            $response = array_merge($response, array(
                                "balance" => $saldo,
                                "currency" => $UsuarioMandante->getMoneda()
                            ));
                        }
                    }
                } catch (Exception $e) {
                }

                break;
            case 20001:
                $codeProveedor = 3;
                $messageProveedor = "Insufficient funds";

                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                $Mandante = new Mandante($UsuarioMandante->mandante);

                if ($Mandante->propio == "S") {
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $saldo = floatval(number_format(round($Usuario->getBalance(), 2), 2, '.', ''));

                    $response = array_merge($response, array(
                        "balance" => $saldo,
                        "currency" => $UsuarioMandante->getMoneda()
                    ));
                }


                break;

            case 0:
                $codeProveedor = 1;
                $messageProveedor = "General Error. (" . $code . ")";

                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                $Mandante = new Mandante($UsuarioMandante->mandante);

                if ($Mandante->propio == "S") {
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $saldo = floatval(number_format(round($Usuario->getBalance(), 2), 2, '.', ''));

                    $response = array_merge($response, array(
                        "balance" => $saldo,
                        "currency" => $UsuarioMandante->getMoneda()
                    ));
                }

                break;
            case 27:
                $codeProveedor = 1;
                $messageProveedor = "General Error. (" . $code . ")";
                break;
            case 10041:
                $codeProveedor = 1;
                $messageProveedor = "General Error. (" . $code . ")";
                break;
            case 28:

                $tipo = $this->tipo;

                if ($tipo == "ROLLBACK") {
                    $codeProveedor = 9;
                    $messageProveedor = "Transaction not found";

                    if ($this->token != '') {
                        try {

                            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

                            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                            $Mandante = new Mandante($UsuarioMandante->mandante);

                            if ($Mandante->propio == "S") {
                                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                                $saldo = floatval(number_format(round($Usuario->getBalance(), 2), 2, '.', ''));

                                $response = array_merge($response, array(
                                    "balance" => $saldo,
                                    "currency" => $UsuarioMandante->getMoneda()
                                ));
                            }
                        } catch (Exception $e) {
                        }
                    }
                } else {
                    $codeProveedor = 1;
                    $messageProveedor = "General Error. (" . $code . ")";

                    if ($this->token != '') {
                        try {

                            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

                            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                            $Mandante = new Mandante($UsuarioMandante->mandante);

                            if ($Mandante->propio == "S") {
                                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                                $saldo = floatval(number_format(round($Usuario->getBalance(), 2), 2, '.', ''));

                                $response = array_merge($response, array(
                                    "balance" => $saldo,
                                    "currency" => $UsuarioMandante->getMoneda()
                                ));
                            }
                        } catch (Exception $e) {
                        }
                    }
                }


                break;
            case 29:

                $tipo = $this->tipo;

                if ($tipo == "ROLLBACK") {
                    $codeProveedor = 9;
                    $messageProveedor = "Transaction not found";

                    if ($this->token != '') {
                        try {
                            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

                            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                            $Mandante = new Mandante($UsuarioMandante->mandante);

                            if ($Mandante->propio == "S") {
                                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                                $saldo = floatval(number_format(round($Usuario->getBalance(), 2), 2, '.', ''));

                                $response = array_merge($response, array(
                                    "balance" => $saldo,
                                    "currency" => $UsuarioMandante->getMoneda()
                                ));
                            }
                        } catch (Exception $e) {
                        }
                    }
                } else {
                    $codeProveedor = 1;
                    $messageProveedor = "General Error. (" . $code . ")";

                    if ($this->token != '') {
                        try {
                            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

                            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                            $Mandante = new Mandante($UsuarioMandante->mandante);

                            if ($Mandante->propio == "S") {
                                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                                $saldo = floatval(number_format(round($Usuario->getBalance(), 2), 2, '.', ''));

                                $response = array_merge($response, array(
                                    "balance" => $saldo,
                                    "currency" => $UsuarioMandante->getMoneda()
                                ));
                            }
                        } catch (Exception $e) {
                        }
                    }
                }


                break;

            case 10001:

                $tipo = $this->tipo;

                if ($tipo == "ROLLBACK") {
                    $codeProveedor = 0;
                    $messageProveedor = "Transaction alredy processed";

                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                    $Mandante = new Mandante($UsuarioMandante->mandante);

                    if ($Mandante->propio == "S") {
                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                        $saldo = floatval(number_format(round($Usuario->getBalance(), 2), 2, '.', ''));

                        $response = array_merge($response, array(
                            "balance" => $saldo,
                            "currency" => $UsuarioMandante->getMoneda()
                        ));
                    }
                } else {
                    $codeProveedor = 0;
                    $messageProveedor = "ok";

                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                    $Mandante = new Mandante($UsuarioMandante->mandante);

                    if ($Mandante->propio == "S") {
                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                        $saldo = floatval(number_format(round($Usuario->getBalance(), 2), 2, '.', ''));

                        $response = array_merge($response, array(
                            "balance" => $saldo,
                            "currency" => $UsuarioMandante->getMoneda()
                        ));
                    }
                }


                break;

            case 10004:
                $codeProveedor = 1;
                $messageProveedor = "Debit after Rollback";

                if ($this->token != '') {

                    try {
                        $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

                        $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                        $Mandante = new Mandante($UsuarioMandante->mandante);

                        if ($Mandante->propio == "S") {
                            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                            $saldo = floatval(number_format(round($Usuario->getBalance(), 2), 2, '.', ''));

                            $response = array_merge($response, array(
                                "balance" => $saldo,
                                "currency" => $UsuarioMandante->getMoneda()
                            ));
                        }
                    } catch (Exception $e) {
                    }
                }

                break;
            case 10014:
                $codeProveedor = 1;
                $messageProveedor = "Debit transaction already processed";


                if ($this->token != '') {

                    try {
                        $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

                        $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                        $Mandante = new Mandante($UsuarioMandante->mandante);

                        if ($Mandante->propio == "S") {
                            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                            $saldo = floatval(number_format(round($Usuario->getBalance(), 2), 2, '.', ''));

                            $response = array_merge($response, array(
                                "balance" => $saldo,
                                "currency" => $UsuarioMandante->getMoneda()
                            ));
                        }
                    } catch (Exception $e) {
                    }
                }

                break;


            default:
                $codeProveedor = 1;
                $messageProveedor = "General Error. (" . $code . ")";

                if ($this->token != '') {

                    try {


                        $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

                        $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                        $Mandante = new Mandante($UsuarioMandante->mandante);

                        if ($Mandante->propio == "S") {
                            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                            $saldo = floatval(number_format(round($Usuario->getBalance(), 2), 2, '.', ''));

                            $response = array_merge($response, array(
                                "balance" => $saldo,
                                "currency" => $UsuarioMandante->getMoneda()
                            ));
                        }
                    } catch (Exception $e) {
                    }
                }

                break;
        }

        $respuesta = json_encode(array_merge($response, array(
            "errorCode" => $codeProveedor,
            "errorDescription" => $messageProveedor,
        )));

        if ($this->transaccionApi != null) {
            $this->transaccionApi->setTipo("R" . $this->tipo);
            $this->transaccionApi->setRespuestaCodigo("ERROR_" . $code);
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->insert($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();
        }
        /*$respuesta = json_encode(array_merge($response, array(
                  "errmsj" => $message

              )));*/

        return $respuesta;
    }
}
