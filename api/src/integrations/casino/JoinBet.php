<?php

/**
 * Clase `JoinBet` para manejar la integración con el proveedor de juegos "JOIN".
 *
 * Este archivo contiene la implementación de la clase `JoinBet`, que incluye métodos
 * para autenticar usuarios, gestionar sesiones, consultar balances, y realizar
 * transacciones de débito, crédito y reversión (rollback) en el contexto de un casino.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;

use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Mandante;
use Backend\dto\Subproveedor;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransaccionApi;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioConfiguracion;
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
 * Clase `JoinBet` para manejar la integración con el proveedor de juegos "JOIN".
 *
 * Esta clase incluye métodos para autenticar usuarios, gestionar sesiones,
 * consultar balances, y realizar transacciones de débito, crédito y reversión
 * en el contexto de un casino.
 */
class JoinBet
{
    /**
     * ID del operador.
     *
     * @var integer|null
     */
    private $operadorId;

    /**
     * Token de autenticación del usuario.
     *
     * @var string
     */
    private $token;

    /**
     * Indicador de funcionalidad especial.
     *
     * @var boolean
     */
    private $isFu = false;

    /**
     * Objeto de transacción API.
     *
     * @var TransaccionApi|null
     */
    private $transaccionApi;

    /**
     * Constructor de la clase `JoinBet`.
     *
     * @param string $token Token de autenticación del usuario.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Autentica al usuario y devuelve información básica.
     *
     * @return string JSON con los datos del usuario autenticado o un error.
     */
    public function Auth()
    {
        try {
            if ($this->token == "") {
                throw new Exception("Token vacio", "01");
            }

            $Proveedor = new Proveedor("", "JOIN");

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $Mandante = new Mandante($UsuarioMandante->mandante);

            if ($Mandante->propio == "S") {
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                $Balance = round($Usuario->getBalance(), 2);

                $return = array(

                    "user_id" => $UsuarioMandante->getUsumandanteId(),
                    "user_name" => "Usuario" . $UsuarioMandante->getUsumandanteId(),
                    "balance" => $Balance,
                    "currency" => $Usuario->moneda,
                    "language" => "ES",
                    "status" => "success"
                );
                return json_encode($return);
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Finaliza la sesión del usuario.
     *
     * @return string JSON con el estado de la operación.
     */
    public function EndSession()
    {
        try {
            if ($this->token == "") {
                throw new Exception("Token vacio", "01");
            }

            $Proveedor = new Proveedor("", "JOIN");

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());


            $UsuarioToken->setToken($UsuarioToken->createToken());

            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

            $UsuarioTokenMySqlDAO->update($UsuarioToken);

            $UsuarioTokenMySqlDAO->getTransaction()->commit();

            $return = array(
                "status" => "success"
            );
            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del usuario autenticado.
     *
     * @return string JSON con el balance del usuario o un error.
     */
    public function getBalance()
    {
        try {
            if ($this->token == "") {
                throw new Exception("Token vacio", "01");
            }

            $Proveedor = new Proveedor("", "JOIN");

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $Mandante = new Mandante($UsuarioMandante->mandante);

            if ($Mandante->propio == "S") {
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                $Balance = round($Usuario->getBalance(), 2);

                $return = array(

                    "balance" => $Balance,
                    "currency" => $Usuario->moneda,
                    "status" => "success"
                );
                return json_encode($return);
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza una transacción de débito.
     *
     * @param string $gameId        ID del juego.
     * @param string $uid           ID único del usuario.
     * @param float  $debitAmount   Monto a debitar.
     * @param string $transactionId ID de la transacción.
     * @param array  $data          Datos adicionales de la transacción.
     *
     * @return string JSON con el resultado de la operación.
     */
    public function Debit($gameId, $uid, $debitAmount, $transactionId, $data)
    {
        $datos = $data;

        try {
            //  Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setIdentificador("JOIN" . $uid);
            $this->transaccionApi->setTValue(($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);

            if ($debitAmount < 0) {
                throw new Exception("No puede ser negativo el monto a debitar.", "0");
            }
            $Proveedor = new Proveedor("", "JOIN");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());


            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);


            //  Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
            $Subproveedor = new Subproveedor($Producto->getSubproveedorId());

            $ConfigurationEnvironment = new ConfigurationEnvironment();
            if ($ConfigurationEnvironment->isDevelopment() || in_array($UsuarioMandante->mandante, array(0, 18, 19))) {
                $result = '0';
                if ($Subproveedor->getTipo() == 'CASINO') {
                    $UsuarioConfiguracion = new UsuarioConfiguracion();

                    $UsuarioConfiguracion->setUsuarioId($UsuarioMandante->usuarioMandante);
                    $result = $UsuarioConfiguracion->verifyLimitesCasino($this->transaccionApi->getValor(), $UsuarioMandante);
                } elseif ($Subproveedor->getTipo() == 'LIVECASINO') {
                    $UsuarioConfiguracion = new UsuarioConfiguracion();

                    $UsuarioConfiguracion->setUsuarioId($UsuarioMandante->usuarioMandante);
                    $result = $UsuarioConfiguracion->verifyLimitesCasinoVivo($this->transaccionApi->getValor(), $UsuarioMandante);
                }

                if ($result != '0') {
                    throw new Exception("Limite de Autoexclusion", $result);
                }
            }


            //  Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            //  Agregamos Elementos a la Transaccion API  */
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());

            //  Verificamos que la transaccionId no se haya procesado antes
            if ($this->transaccionApi->existsTransaccionIdAndProveedor("OK")) {
                //  Si la transaccionId ha sido procesada, reportamos el error
                throw new Exception("Transaccion ya procesada", "10001");
            }

            $TransaccionJuego = new TransaccionJuego();
            $TransaccionJuego->setProductoId($ProductoMandante->prodmandanteId);
            $TransaccionJuego->setTransaccionId($transactionId);
            $TransaccionJuego->setTicketId("JOIN" . $uid);
            $TransaccionJuego->setValorTicket($debitAmount);
            $TransaccionJuego->setValorPremio(0);
            $TransaccionJuego->setMandante($UsuarioMandante->mandante);
            $TransaccionJuego->setUsuarioId($UsuarioMandante->usumandanteId);
            $TransaccionJuego->setEstado("A");
            $TransaccionJuego->setPremiado("N");
            $TransaccionJuego->setFechaPago(date('Y-m-d H:i:s', time()));

            $TransaccionJuego->setUsucreaId(0);
            $TransaccionJuego->setUsumodifId(0);

            $ExisteTicket = false;

            if ($TransaccionJuego->existsTicketId()) {
                $ExisteTicket = true;
            }

            $Mandante = new Mandante($UsuarioMandante->mandante);

            if ($Mandante->propio == "S") {
                $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();

                $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

                if ($Transaction->isIsconnected()) {
                    if ($ExisteTicket) {
                        $TransaccionJuego = new TransaccionJuego("", "JOIN" . $uid, "");

                        if ($TransaccionJuego->getTransaccionId() != $transactionId) {
                            $TransaccionJuego->setValorTicket($TransaccionJuego->getValorTicket() + $debitAmount);
                            $TransaccionJuego->update($Transaction);
                        }
                        $transaccion_id = $TransaccionJuego->getTransjuegoId();
                    } else {
                        $transaccion_id = $TransaccionJuego->insert($Transaction);
                    }

                    $tipoTransaccion = "DEBIT";


                    $TransjuegoLog = new TransjuegoLog();
                    $TransjuegoLog->setTransjuegoId($transaccion_id);
                    $TransjuegoLog->setTransaccionId($transactionId);
                    $TransjuegoLog->setTipo($tipoTransaccion);
                    $TransjuegoLog->setTValue(json_encode($datos));
                    $TransjuegoLog->setUsucreaId(0);
                    $TransjuegoLog->setUsumodifId(0);
                    $TransjuegoLog->setValor($debitAmount);

                    $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);


                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);


                    $Usuario->debit($debitAmount, $Transaction);

                    $UsuarioHistorial = new UsuarioHistorial();
                    $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                    $UsuarioHistorial->setDescripcion('');
                    $UsuarioHistorial->setMovimiento('S');
                    $UsuarioHistorial->setUsucreaId(0);
                    $UsuarioHistorial->setUsumodifId(0);
                    $UsuarioHistorial->setTipo(30);
                    $UsuarioHistorial->setValor($debitAmount);
                    $UsuarioHistorial->setExternoId($TransjuegoLog_id);

                    $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                    $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

                    // Commit de la transacción

                    $Transaction->commit();

                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $Balance = $Usuario->getBalance();

                    $currency = $Usuario->moneda;

                    $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                    $data = $Usuario->getWSMessage($UsuarioToken->getRequestId());

                    $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);

                    $WebsocketUsuario->sendWSMessage();


                    $return = array(

                        "balance" => $Balance,
                        "currency" => $currency,
                        "status" => "OK"
                    );

                    //  Guardamos la Transaccion Api necesaria de estado OK
                    $this->transaccionApi->setRespuestaCodigo("OK");
                    $this->transaccionApi->setRespuesta(json_encode($return));
                    $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                    $TransaccionApiMySqlDAO->insert($this->transaccionApi);
                    $TransaccionApiMySqlDAO->getTransaction()->commit();


                    return json_encode($return);
                }
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza una reversión (rollback) de una transacción.
     *
     * @param string $gameId         ID del juego.
     * @param string $uid            ID único del usuario.
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $transactionId  ID de la transacción.
     * @param array  $data           Datos adicionales de la transacción.
     * @param string $hash           Hash de seguridad.
     *
     * @return string JSON con el resultado de la operación.
     */
    public function Rollback($gameId, $uid, $rollbackAmount, $transactionId, $data, $hash)
    {
        $datos = $data;

        try {
            //  Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setTValue(($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($rollbackAmount);

            //  Obtenemos el Proveedor con el abreviado JOIN
            $Proveedor = new Proveedor("", "JOIN");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());

            $UsuarioMandante = new UsuarioMandante($this->usuarioId);

            $TransaccionApiSuper = new TransaccionApi("", $transactionId, $Proveedor->getProveedorId());

            $TransaccionJuego = new TransaccionJuego("", $TransaccionApiSuper->getIdentificador());

            $this->transaccionApi->setIdentificador($TransaccionApiSuper->getIdentificador());

            $rollbackAmount = $TransaccionApiSuper->getValor();

            if ($TransaccionApiSuper->getValor() != $rollbackAmount) {
                throw new Exception("Valor ticket diferente al rollback", "10003");
            }

            $Mandante = new Mandante($UsuarioMandante->mandante);

            if ($Mandante->propio == "S") {
                $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();

                $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

                if ($Transaction->isIsconnected()) {
                    if ($TransaccionApiSuper->getTipo() == "DEBIT") {
                        $TransaccionJuego->setValorTicket($TransaccionJuego->getValorTicket() - $rollbackAmount);
                    } elseif ($TransaccionApiSuper->getTipo() == "CREDIT") {
                        $TransaccionJuego->setValorPremio($TransaccionJuego->getValorPremio() - $rollbackAmount);
                    }


                    $TransaccionJuego->update($Transaction);

                    $TransJuegoId = $TransaccionJuego->getTransjuegoId();

                    $TransjuegoLog = new TransjuegoLog();
                    $TransjuegoLog->setTransjuegoId($TransJuegoId);
                    $TransjuegoLog->setTransaccionId("ROLLBACK" . $transactionId);
                    $TransjuegoLog->setTipo("ROLLBACK");
                    $TransjuegoLog->setTValue(($datos));
                    $TransjuegoLog->setUsucreaId(0);
                    $TransjuegoLog->setUsumodifId(0);
                    $TransjuegoLog->setValor($rollbackAmount);

                    $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);

                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    if ($TransaccionApiSuper->getTipo() == "DEBIT") {
                        $Usuario->credit($rollbackAmount, $Transaction);

                        $UsuarioHistorial = new UsuarioHistorial();
                        $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                        $UsuarioHistorial->setDescripcion('');
                        $UsuarioHistorial->setMovimiento('E');
                        $UsuarioHistorial->setUsucreaId(0);
                        $UsuarioHistorial->setUsumodifId(0);
                        $UsuarioHistorial->setTipo(30);
                        $UsuarioHistorial->setValor($rollbackAmount);
                        $UsuarioHistorial->setExternoId($TransjuegoLog_id);

                        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);
                    } elseif ($TransaccionApiSuper->getTipo() == "CREDIT") {
                        $Usuario->debit($rollbackAmount, $Transaction);

                        $UsuarioHistorial = new UsuarioHistorial();
                        $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                        $UsuarioHistorial->setDescripcion('');
                        $UsuarioHistorial->setMovimiento('S');
                        $UsuarioHistorial->setUsucreaId(0);
                        $UsuarioHistorial->setUsumodifId(0);
                        $UsuarioHistorial->setTipo(30);
                        $UsuarioHistorial->setValor($rollbackAmount);
                        $UsuarioHistorial->setExternoId($TransjuegoLog_id);

                        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);
                    }

                    // Commit de la transacción
                    $Transaction->commit();

                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $Balance = round($Usuario->getBalance(), 2);

                    $return = array(

                        "status" => "200",
                        "balance" => $Balance

                    );

                    return json_encode($return);
                }
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza una transacción de crédito.
     *
     * @param string $gameId        ID del juego.
     * @param string $uid           ID único del usuario.
     * @param float  $creditAmount  Monto a acreditar.
     * @param string $transactionId ID de la transacción.
     * @param array  $data          Datos adicionales de la transacción.
     *
     * @return string JSON con el resultado de la operación.
     */
    public function Credit($gameId, $uid, $creditAmount, $transactionId, $data)
    {
        $datos = $data;

        try {
            //  Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setIdentificador("JOIN" . $uid);
            $this->transaccionApi->setTValue(($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);

            $Proveedor = new Proveedor("", "JOIN");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);


            //  Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            //  Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            //  Agregamos Elementos a la Transaccion API
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());

            //  Verificamos que la transaccionId no se haya procesado antes
            if ($this->transaccionApi->existsTransaccionIdAndProveedor("OK")) {
                //  Si la transaccionId ha sido procesada, reportamos el error
                throw new Exception("Transaccion ya procesada", "10001");
            }

            $TransaccionJuego = new TransaccionJuego("", "JOIN" . $uid);

            $Mandante = new Mandante($UsuarioMandante->mandante);

            if ($Mandante->propio == "S") {
                $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();

                $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

                if ($Transaction->isIsconnected()) {
                    $TransaccionJuego->setValorPremio($TransaccionJuego->getValorPremio() + $creditAmount);

                    $isEndRound = true;

                    if ($isEndRound) {
                        if ($TransaccionJuego->getValorPremio() > 0) {
                            $TransaccionJuego->setPremiado("S");
                            $TransaccionJuego->setFechaPago(date('Y-m-d H:i:s', time()));
                        }
                        $TransaccionJuego->setEstado("I");
                    }

                    $TransaccionJuego->update($Transaction);

                    $TransJuegoId = $TransaccionJuego->getTransjuegoId();

                    $tipoTransaccion = "CREDIT";

                    $sumaCreditos = true;

                    $TransjuegoLog = new TransjuegoLog();
                    $TransjuegoLog->setTransjuegoId($TransJuegoId);
                    $TransjuegoLog->setTransaccionId($transactionId);
                    $TransjuegoLog->setTipo($tipoTransaccion);
                    $TransjuegoLog->setTValue(json_encode($datos));
                    $TransjuegoLog->setUsucreaId(0);
                    $TransjuegoLog->setUsumodifId(0);
                    $TransjuegoLog->setValor($creditAmount);

                    $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);

                    if ($sumaCreditos) {
                        if ($creditAmount > 0) {
                            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                            $Usuario->creditWin($creditAmount, $Transaction);
                        }
                    }

                    // Commit de la transacción
                    $Transaction->commit();

                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $Balance = $Usuario->getBalance();

                    $currency = $Usuario->moneda;

                    $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                    $data = $Usuario->getWSMessage($UsuarioToken->getRequestId());

                    $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);

                    $WebsocketUsuario->sendWSMessage();

                    $return = array(

                        "balance" => $Balance,
                        "currency" => $currency,
                        "status" => "OK"
                    );

                    //  Guardamos la Transaccion Api necesaria de estado OK
                    $this->transaccionApi->setRespuestaCodigo("OK");
                    $this->transaccionApi->setRespuesta(json_encode($return));
                    $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                    $TransaccionApiMySqlDAO->insert($this->transaccionApi);
                    $TransaccionApiMySqlDAO->getTransaction()->commit();

                    return json_encode($return);
                }
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Convierte un error en un formato JSON estándar.
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

        switch ($code) {
            case 1:
                $codeProveedor = 6;
                $messageProveedor = "Token not found";
                break;
            case 2:
                $codeProveedor = 2;
                $messageProveedor = "Transaccion ya esta procesada";
                break;
            case 3:
                $codeProveedor = 3;
                $messageProveedor = "Insufficient funds" . $message;
                break;
            case 0:
                $codeProveedor = 1;
                $messageProveedor = "General Error" . $message;
                break;
            case 11:
                $codeProveedor = 9;
                $messageProveedor = "Transaction not found";
                break;

            default:
                $codeProveedor = 0;
                $messageProveedor = $message;
                break;
        }


        $respuesta = json_encode(array(

            "uid" => "",
            "token" => $this->token,
            "errorCode" => $codeProveedor,
            "errorDescription" => $messageProveedor,
            "timestamp" => time()
        ));

        if ($this->transaccionApi != null) {
            $this->transaccionApi->setTipo("R" . $this->transaccionApi->getTipo());
            $this->transaccionApi->setRespuestaCodigo("ERROR");
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->insert($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();
        }

        return $respuesta;
    }


}