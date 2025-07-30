<?php

/**
 * Clase `Inbet` para la integración con el proveedor de casino Inbet.
 *
 * Este archivo contiene la implementación de la clase `Inbet`, que maneja
 * las operaciones relacionadas con la autenticación, débito, crédito y
 * manejo de errores para la integración con el proveedor de casino Inbet.
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
 * Clase `Inbet`.
 *
 * Esta clase maneja la integración con el proveedor de casino Inbet,
 * incluyendo operaciones de autenticación, débito, crédito y manejo de errores.
 */
class Inbet
{
    /**
     * ID del operador.
     *
     * @var integer
     */
    private $operadorId;

    /**
     * Token de autenticación.
     *
     * @var string
     */
    private $token;

    /**
     * Indica si el token es de tipo "FUN".
     *
     * @var boolean
     */
    private $isFu = false;

    /**
     * Objeto de transacción API.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * Constructor de la clase `Inbet`.
     *
     * @param integer $operadorId ID del operador.
     * @param string  $token      Token de autenticación.
     */
    public function __construct($operadorId, $token)
    {
        $this->operadorId = $operadorId;
        $this->operadorId = 10178001;

        if (explode("|", $token)[1] != null) {
            $token_str = explode("|", $token)[0];
            $type = explode("|", $token)[1];

            if ($type === "FUN") {
                $this->token = $token_str;
                $this->isFun = true;
            }
        } else {
            $this->token = $token;
        }
    }

    /**
     * Obtiene el ID del operador.
     *
     * @return integer ID del operador.
     */
    public function getOperadorId()
    {
        return $this->operadorId;
    }

    /**
     * Autentica al usuario y devuelve el balance y la moneda.
     *
     * @return string JSON con el balance, moneda y estado.
     * @throws Exception Si ocurre un error durante la autenticación.
     */
    public function Auth()
    {
        try {
            if ($this->isFun) {
                if ($this->token == "") {
                    throw new Exception("Token vacio", "01");
                }

                $Proveedor = new Proveedor("", "INB");
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                $Mandante = new Mandante($UsuarioMandante->mandante);

                if ($Mandante->propio == "S") {
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                    $Balance = $Usuario->getBalance();

                    $return = array(

                        "balance" => $Balance,
                        "currency" => "FUN",
                        "status" => "OK"
                    );
                    return json_encode($return);
                }
            } else {
                if ($this->token == "") {
                    throw new Exception("Token vacio", "01");
                }

                $Proveedor = new Proveedor("", "INB");
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                $Mandante = new Mandante($UsuarioMandante->mandante);

                if ($Mandante->propio == "S") {
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $Balance = $Usuario->getBalance();

                    $return = array(

                        "balance" => $Balance,
                        "currency" => $Usuario->moneda,
                        "status" => "OK"
                    );
                    return json_encode($return);
                }
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza una operación de débito.
     *
     * @param string  $gameId        ID del juego.
     * @param string  $uid           ID del usuario.
     * @param string  $game          Tipo de juego.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $denomination  Denominación de la moneda.
     * @param float   $bet           Apuesta realizada.
     * @param integer $lines         Líneas de juego.
     * @param string  $result        Resultado del juego.
     * @param string  $transactionId ID de la transacción.
     * @param array   $data          Datos adicionales.
     *
     * @return string JSON con el balance, moneda y estado.
     * @throws Exception Si ocurre un error durante la operación.
     */
    public function Debit($gameId, $uid, $game, $debitAmount, $denomination, $bet, $lines, $result, $transactionId, $data)
    {
        if ($game == "slot") {
            $datos = array(
                "token" => $this->token,
                "gameId" => $gameId,
                "uid" => $uid,
                "game" => $game,
                "debitAmount" => $debitAmount,
                "denomination" => $denomination,
                "bet" => $bet,
                "lines" => $lines

            );
        } else {
            $datos = array(
                "token" => $this->token,
                "gameId" => $gameId,
                "uid" => $uid,
                "game" => $game,
                "debitAmount" => ($debitAmount),
                "denomination" => $denomination,
                "bet" => (($bet)),
                "lines" => $lines,
                "result" => (($result))

            );
        }


        $datos = $data;

        try {
            //  Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setIdentificador("INB" . $uid);
            $this->transaccionApi->setTValue(($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);

            if ($debitAmount < 0) {
                throw new Exception("No puede ser negativo el monto a debitar.", "0");
            }
            $Proveedor = new Proveedor("", "INB");


            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);


            //  Obtenemos el producto con el gameId  */
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
            $TransaccionJuego->setTicketId("INB" . $uid);
            $TransaccionJuego->setValorTicket($debitAmount);
            $TransaccionJuego->setValorPremio(0);
            $TransaccionJuego->setMandante($UsuarioMandante->mandante);
            $TransaccionJuego->setUsuarioId($UsuarioMandante->usumandanteId);
            $TransaccionJuego->setEstado("A");
            $TransaccionJuego->setUsucreaId(0);
            $TransaccionJuego->setUsumodifId(0);
            $TransaccionJuego->setPremiado('N');
            $TransaccionJuego->setFechaPago(date('Y-m-d H:i:s'));
            $TransaccionJuego->setTipo('NORMAL');

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
                        $TransaccionJuego = new TransaccionJuego("", "INB" . $uid, "");

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

                    //Commit de la transacción
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
     * Realiza una operación de rollback (reversión).
     *
     * @param string  $gameId         ID del juego.
     * @param string  $uid            ID del usuario.
     * @param integer $betTypeID      Tipo de apuesta.
     * @param string  $currency       Moneda utilizada.
     * @param float   $rollbackAmount Monto a revertir.
     * @param string  $serverId       ID del servidor.
     * @param string  $roundId        ID de la ronda.
     * @param string  $transactionId  ID de la transacción.
     * @param integer $seatId         ID del asiento.
     * @param string  $hash           Hash de seguridad.
     *
     * @return void
     */
    public function Rollback($gameId, $uid, $betTypeID, $currency, $rollbackAmount, $serverId, $roundId, $transactionId, $seatId, $hash)
    {
    }

    /**
     * Realiza una operación de crédito.
     *
     * @param string  $gameId        ID del juego.
     * @param string  $uid           ID del usuario.
     * @param string  $game          Tipo de juego.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $denomination  Denominación de la moneda.
     * @param float   $bet           Apuesta realizada.
     * @param integer $lines         Líneas de juego.
     * @param string  $result        Resultado del juego.
     * @param string  $transactionId ID de la transacción.
     * @param array   $data          Datos adicionales.
     *
     * @return string JSON con el balance, moneda y estado.
     * @throws Exception Si ocurre un error durante la operación.
     */
    public function Credit($gameId, $uid, $game, $creditAmount, $denomination, $bet, $lines, $result, $transactionId, $data)
    {
        if ($game == "slot") {
            $datos = array(
                "token" => $this->token,
                "gameId" => $gameId,
                "uid" => $uid,
                "game" => $game,
                "creditAmount" => $creditAmount,
                "denomination" => $denomination,
                "bet" => $bet,
                "lines" => $lines

            );
        } else {
            $datos = array(
                "token" => $this->token,
                "gameId" => $gameId,
                "uid" => $uid,
                "game" => $game,
                "creditAmount" => ($creditAmount),
                "denomination" => $denomination,
                "bet" => (($bet)),
                "lines" => $lines,
                "result" => (($result))

            );
        }

        $datos = $data;

        try {
            //  Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setIdentificador("INB" . $uid);
            $this->transaccionApi->setTValue(($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);

            $Proveedor = new Proveedor("", "INB");

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);


            //  Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            //  Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            //  Agregamos Elementos a la Transaccion API
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());

            //  Verificamos que la transaccionId no se haya procesado antes
            if ($this->transaccionApi->existsTransaccionIdAndProveedor("OK")) {
                //  Si la transaccionId ha sido procesada, reportamos el error
                throw new Exception("Transaccion ya procesada", "10001");
            }

            $TransaccionJuego = new TransaccionJuego("", "INB" . $uid);

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
                            $TransaccionJuego->setFechaPago(date('Y-m-d H:i:s'));
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

                    //$Usuario->setBalance($Balance - $TransaccionJuego->getValorTicket(), $Transaction);

                    if ($sumaCreditos) {
                        if ($creditAmount > 0) {
                            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                            $Usuario->creditWin($creditAmount, $Transaction);


                            $UsuarioHistorial = new UsuarioHistorial();
                            $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                            $UsuarioHistorial->setDescripcion('');
                            $UsuarioHistorial->setMovimiento('E');
                            $UsuarioHistorial->setUsucreaId(0);
                            $UsuarioHistorial->setUsumodifId(0);
                            $UsuarioHistorial->setTipo(30);
                            $UsuarioHistorial->setValor($creditAmount);
                            $UsuarioHistorial->setExternoId($TransjuegoLog_id);

                            $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                            $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);
                        }
                    }

                    //Commit de la transacción
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
     * Convierte un error en una respuesta JSON.
     *
     * @param integer $code    Código del error.
     * @param string  $message Mensaje del error.
     *
     * @return string Respuesta JSON con los detalles del error.
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
        }


        $respuesta = json_encode(array(

            "operatorId" => $this->getOperadorId(),
            "uid" => "",
            "token" => $this->token,
            "errorCode" => $codeProveedor,
            "errorDescription" => $messageProveedor,
            "msjerr" => $message,
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