<?php

/**
 * Clase Bgb
 *
 * Esta clase representa la integración con el proveedor BGB y contiene métodos
 * para manejar transacciones como débito, crédito y rollback, además de autenticación.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @version    1.0.0
 * @since      2025-04-27
 * @author     Desconocido
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
 * Clase Bgb
 *
 * Esta clase representa la integración con el proveedor BGB y contiene métodos
 * para manejar transacciones como débito, crédito y rollback, además de autenticación.
 */
class Bgb
{
    /**
     * Identificador del operador.
     *
     * @var integer|null
     */
    private $operadorId;

    /**
     * Token de autenticación.
     *
     * @var string
     */
    private $token;

    /**
     * Objeto para manejar transacciones API.
     *
     * @var TransaccionApi|null
     */
    private $transaccionApi;

    /**
     * Datos adicionales de la transacción.
     *
     * @var array|null
     */
    private $data;

    /**
     * Constructor de la clase Bgb.
     *
     * @param string $token Token de autenticación para la integración.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Obtiene el identificador del operador.
     *
     * @return integer|null Identificador del operador.
     */
    public function getOperadorId()
    {
        return $this->operadorId;
    }

    /**
     * Metodo para autenticar un usuario y obtener su balance.
     *
     * @return string JSON con el resultado de la autenticación.
     * @throws Exception Si el token está vacío o ocurre un error.
     */
    public function Auth()
    {
        try {
            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            // Obtenemos el Proveedor con el abreviado BGB
            $Proveedor = new Proveedor("", "BGB");

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $Mandante = new Mandante($UsuarioMandante->mandante);

            if ($Mandante->propio == "S") {
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                $Balance = $Usuario->getBalance();

                $return = array(
                    "ErrorId" => 0,
                    "Token" => $this->token,
                    "Amount" => ($Balance * 100),
                    "ResponseDate" => (date("Y-m-d H:i:s", time()))
                );
                return json_encode($return);
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Metodo para realizar un débito en el sistema.
     *
     * @param string $gameId        ID del juego.
     * @param float  $debitAmount   Monto a debitar.
     * @param string $transactionId ID de la transacción.
     * @param array  $datos         Datos adicionales de la transacción.
     *
     * @return string JSON con el resultado del débito.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function Debit($gameId, $debitAmount, $transactionId, $datos)
    {
        $this->data = $datos;

        try {
            // Obtenemos el Proveedor con el abreviado BGB
            $Proveedor = new Proveedor("", "BGB");

            // Creamos la Transaccion API  
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);

            // Verificamos que el monto a debitar sea positivo
            if ($debitAmount < 0) {
                throw new Exception("No puede ser negativo el monto a debitar.", "10002");
            }

            // Obtenemos el Usuario Token con el token 
            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            // Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $this->transaccionApi->setIdentificador($TicketID . $UsuarioMandante->getUsumandanteId() . "BGB");

            // Obtenemos el producto con el gameId 
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

            // Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego 
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            // Agregamos Elementos a la Transaccion API 
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());


            // Verificamos que la transaccionId no se haya procesado antes 
            if ($this->transaccionApi->existsTransaccionIdAndProveedor("OK")) {
                // Si la transaccionId ha sido procesada, reportamos el error  */
                throw new Exception("Transaccion ya procesada", "10001");
            }

            // Creamos la Transaccion API Para verificar si hay antes hubo algun ROLLBACK antes */
            $TransaccionApiRollback = new TransaccionApi();
            $TransaccionApiRollback->setProveedorId($Proveedor->getProveedorId());
            $TransaccionApiRollback->setProductoId($ProductoMandante->prodmandanteId);
            $TransaccionApiRollback->setUsuarioId($UsuarioMandante->getUsumandanteId());
            $TransaccionApiRollback->setTransaccionId('ROLLBACK' . $transactionId);
            $TransaccionApiRollback->setTipo("ROLLBACK");
            $TransaccionApiRollback->setTValue(json_encode($datos));
            $TransaccionApiRollback->setUsucreaId(0);
            $TransaccionApiRollback->setUsumodifId(0);


            // Verificamos que la transaccionId no se haya procesado antes
            if ($TransaccionApiRollback->existsTransaccionIdAndProveedor("ERROR")) {
                // Si la transaccionId tiene un Rollback antes, reportamos el error
                throw new Exception("Transaccion con Rollback antes", "10004");
            }

            // Creamos la Transaccion por el Juego 
            $TransaccionJuego = new TransaccionJuego();
            $TransaccionJuego->setProductoId($ProductoMandante->prodmandanteId);
            $TransaccionJuego->setTransaccionId($transactionId);
            $TransaccionJuego->setTicketId($TicketID . $UsuarioMandante->getUsumandanteId() . "BGB");
            $TransaccionJuego->setValorTicket($debitAmount);
            $TransaccionJuego->setValorPremio(0);
            $TransaccionJuego->setMandante($UsuarioMandante->mandante);
            $TransaccionJuego->setUsuarioId($UsuarioMandante->usumandanteId);
            $TransaccionJuego->setEstado("A");
            $TransaccionJuego->setUsucreaId(0);
            $TransaccionJuego->setUsumodifId(0);

            $ExisteTicket = false;

            // Verificamos si existe el ticket_id antes, de ser positivo, tendriamos que combinar las apuestas 
            if ($TransaccionJuego->existsTicketId()) {
                $ExisteTicket = true;
            }

            // Obtenemos el mandante para verificar sus caracteristicas  
            $Mandante = new Mandante($UsuarioMandante->mandante);

            // Verificamos que el mandante sea Propio, para proceder con nuestros Usuarios  
            if ($Mandante->propio == "S") {
                // Obtenemos la Transaccion de la BD para crear y registrar el Debito con esta transaccion
                $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
                $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

                // Verificamos que la Transaccion si este conectada y lista para usarse  
                if ($Transaction->isIsconnected()) {
                    // Verificamos si Existe el ticket para combinar las apuestas.
                    if ($ExisteTicket) {
                        // Obtenemos la Transaccion Juego y combinamos las aúestas.
                        $TransaccionJuego = new TransaccionJuego("", $TicketID . $UsuarioMandante->getUsumandanteId() . "BGB", "");

                        if ($TransaccionJuego->getTransaccionId() != $transactionId) {
                            $TransaccionJuego->setValorTicket($TransaccionJuego->getValorTicket() + $debitAmount);
                            $TransaccionJuego->update($Transaction);
                        }
                        $transaccion_id = $TransaccionJuego->getTransjuegoId();
                    } else {
                        $transaccion_id = $TransaccionJuego->insert($Transaction);
                    }

                    // Obtenemos el tipo de Transaccion dependiendo de el betTypeID  
                    $tipoTransaccion = "DEBIT";

                    // Creamos el log de la transaccion juego para auditoria 
                    $TransjuegoLog = new TransjuegoLog();
                    $TransjuegoLog->setTransjuegoId($transaccion_id);
                    $TransjuegoLog->setTransaccionId($transactionId);
                    $TransjuegoLog->setTipo($tipoTransaccion);
                    $TransjuegoLog->setTValue(json_encode($datos));
                    $TransjuegoLog->setUsucreaId(0);
                    $TransjuegoLog->setUsumodifId(0);
                    $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);

                    // Obtenemos nuestro Usuario y hacemos el debito 
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                    $Usuario->debit($debitAmount, $Transaction);

                    // Commit de la transacción
                    $Transaction->commit();


                    // Consultamos de nuevo el usuario para obtener el saldo 
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                    $Balance = $Usuario->getBalance();

                    // Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket 
                    $UsuarioToken = new UsuarioToken("", $UsuarioMandante->getUsumandanteId());

                    // Enviamos el mensaje Websocket al Usuario para que actualice el saldo 
                    $data = $Usuario->getWSMessage($UsuarioToken->getRequestId());
                    $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                    $WebsocketUsuario->sendWSMessage();


                    // Retornamos el mensaje satisfactorio 
                    $return = array(
                        "ErrorId" => 0,
                        "token" => $this->token,
                        "Amount" => ($Balance * 100),
                        "ResponseDate" => (date("Y-m-d H:i:s", time())),
                        "OperationCode" => $transactionId
                    );

                    // Guardamos la Transaccion Api necesaria de estado OK 
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
     * Metodo para realizar un rollback de una transacción.
     *
     * @param string  $gameId         ID del juego.
     * @param string  $uid            Identificador único del usuario.
     * @param integer $betTypeID      Tipo de apuesta.
     * @param string  $currency       Moneda utilizada.
     * @param float   $rollbackAmount Monto del rollback.
     * @param string  $serverId       ID del servidor.
     * @param string  $roundId        ID de la ronda.
     * @param string  $transactionId  ID de la transacción.
     * @param string  $seatId         ID del asiento.
     * @param string  $hash           Hash de seguridad.
     *
     * @return string JSON con el resultado del rollback.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function Rollback($gameId, $uid, $betTypeID, $currency, $rollbackAmount, $serverId, $roundId, $transactionId, $seatId, $hash)
    {
        $datos = array(
            "token" => $this->token,
            "operatorId" => $this->operadorId,
            "gameId" => $gameId,
            "seatId" => $seatId,
            "uid" => $uid,
            "betTypeID" => $betTypeID,
            "currency" => $currency,
            "rollbackAmount" => $rollbackAmount,
            "serverId" => $serverId,
            "roundId" => $roundId,
            "transactionId" => $transactionId,
            "hash" => $hash

        );
        $this->data = $datos;

        try {
            // Obtenemos el Proveedor con el abreviado BGB
            $Proveedor = new Proveedor("", "BGB");

            // Creamos la Transaccion API  
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);

            // Verificamos que el uid no sea vacio 
            if ($uid == "") {
                throw new Exception("UID vacio", "10013");
            }

            // Obtenemos el Usuario Mandante con el UID 
            $UsuarioMandante = new UsuarioMandante($uid);


            // Obtenemos el Usuario Token con el token
            $UsuarioToken = new UsuarioToken($this->token);

            // Obtenemos el Usuario Mandante con el Usuario Token 
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $this->transaccionApi->setIdentificador($TicketID . $UsuarioMandante->getUsumandanteId() . "BGB");

            // Obtenemos el producto con el gameId  
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            // Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego  
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            // Agregamos Elementos a la Transaccion API  
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());


            // Verificamos que la transaccionId no se haya procesado antes  
            if ($this->transaccionApi->existsTransaccionIdAndProveedor("OK")) {
                // Si la transaccionId ha sido procesada, reportamos el error
                throw new Exception("Transaccion ya procesada", "10001");
            }


            try {
                $TransaccionApi2 = new TransaccionApi("", $transactionId, $Proveedor->getProveedorId());
                $jsonValue = json_decode($TransaccionApi2->getTValue());
                $valorTransaction = 0;

                if (strpos($TransaccionApi2->getTipo(), 'DEBIT') !== false) {
                    $valorTransaction = $jsonValue->debitAmount;
                } else {
                    throw new Exception("Transaccion no es Debit", "10006");
                }
            } catch (Excption $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            $seatIdSecond = $jsonValue->seatId;
            $betTypeIDSecond = $jsonValue->betTypeID;

            if ($betTypeIDSecond == '6') {
                if (strpos($seatId, '-') === false) {
                    $seatIdSecond = $seatIdSecond . '-2';
                }
            }


            // Creamos la Transaccion por el Juego 
            $TransaccionJuego = new TransaccionJuego("", $jsonValue->roundId . $TransaccionApi2->getUsuarioId() . "STI" . $betTypeIDSecond . $seatIdSecond);

            // Verificamos que el valor del ticket sea igual al valor del Rollback 
            if ($valorTransaction != $rollbackAmount) {
                throw new Exception("Valor ticket diferente al Rollback", "10003");
            }

            // Obtenemos Mandante para verificar sus caracteristicas  
            $Mandante = new Mandante($UsuarioMandante->mandante);

            // Verificamos si el mandante es Propio  
            if ($Mandante->propio == "S") {
                // Obtenemos la Transaccion de la BD para crear y registrar el Debito con esta transaccion
                $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
                $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

                // Verificamos que la Transaccion si este conectada y lista para usarse  
                if ($Transaction->isIsconnected()) {
                    // Actualizamos Transaccion Juego
                    $TransaccionJuego->setEstado("I");
                    $TransaccionJuego->update($Transaction);


                    // Obtenemos el Transaccion Juego ID  
                    $TransJuegoId = $TransaccionJuego->getTransjuegoId();

                    // Creamos el Log de Transaccion Juego  
                    $TransjuegoLog = new TransjuegoLog();
                    $TransjuegoLog->setTransjuegoId($TransJuegoId);
                    $TransjuegoLog->setTransaccionId("ROLLBACK" . $transactionId);
                    $TransjuegoLog->setTipo("ROLLBACK");
                    $TransjuegoLog->setTValue(json_encode($datos));
                    $TransjuegoLog->setUsucreaId(0);
                    $TransjuegoLog->setUsumodifId(0);
                    $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);

                    // Obtenemos el Usuario para hacerle el credito  
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                    $Usuario->credit($TransaccionJuego->getValorTicket(), $Transaction);

                    //Comit de la transacción
                    $Transaction->commit();

                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                    $Balance = $Usuario->getBalance();

                    $return = array(

                        "operatorId" => $this->getOperadorId(),
                        "uid" => $UsuarioMandante->usumandanteId,
                        "roundId" => $roundId,
                        "token" => $this->token,
                        "balance" => $Balance,
                        "currency" => $currency,
                        "transactionId" => $TransaccionJuego->getTransaccionId(),
                        "errorCode" => 0,
                        "errorDescription" => "ok",
                        "timestamp" => (round(microtime(true) * 1000))
                    );

                    // Guardamos la Transaccion Api necesaria de estado OK  
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
     * Metodo para realizar un crédito en el sistema.
     *
     * @param string  $gameId         ID del juego.
     * @param string  $uid            Identificador único del usuario.
     * @param integer $betTypeID      Tipo de apuesta.
     * @param string  $currency       Moneda utilizada.
     * @param float   $creditAmount   Monto del crédito.
     * @param string  $serverId       ID del servidor.
     * @param string  $roundId        ID de la ronda.
     * @param string  $transactionId  ID de la transacción.
     * @param string  $seatId         ID del asiento.
     * @param string  $gameDataString Datos adicionales del juego.
     * @param boolean $isEndRound     Indica si es el final de la ronda.
     * @param integer $creditIndex    Índice del crédito.
     * @param string  $hash           Hash de seguridad.
     *
     * @return string JSON con el resultado del crédito.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function Credit($gameId, $uid, $betTypeID, $currency, $creditAmount, $serverId, $roundId, $transactionId, $seatId, $gameDataString, $isEndRound, $creditIndex, $hash)
    {
        $datos = array(
            "token" => $this->token,
            "operatorId" => $this->operadorId,
            "gameId" => $gameId,
            "seatId" => $seatId,
            "uid" => $uid,
            "betTypeID" => $betTypeID,
            "currency" => $currency,
            "creditAmount" => $creditAmount,
            "serverId" => $serverId,
            "roundId" => $roundId,
            "transactionId" => $transactionId,
            "gameDataString" => $gameDataString,
            "isEndRound" => $isEndRound,
            "creditIndex" => $creditIndex,
            "hash" => $hash

        );

        $this->data = $datos;

        try {
            // Obtenemos el Proveedor con el abreviado BGB
            $Proveedor = new Proveedor("", "BGB");

            // Creamos la Transaccion API  
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);

            // Verificamos que el uid no sea vacio 
            if ($uid == "") {
                throw new Exception("UID vacio", "10013");
            }

            // Obtenemos el Usuario Mandante con el UID 
            $UsuarioMandante = new UsuarioMandante($uid);

            // Obtenemos el Usuario Token con el token 
            $UsuarioToken = new UsuarioToken($this->token);

            // Obtenemos el Usuario Mandante con el Usuario Token 
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $this->transaccionApi->setIdentificador($TicketID . $UsuarioMandante->getUsumandanteId() . "BGB");


            // Obtenemos el producto con el gameId  
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            // Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego 
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            // Agregamos Elementos a la Transaccion API  
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());


            // Verificamos que la transaccionId no se haya procesado antes  
            if ($this->transaccionApi->existsTransaccionIdAndProveedor("OK")) {
                // Si la transaccionId ha sido procesada, reportamos el error
                throw new Exception("Transaccion ya procesada", "10001");
            }

            // Verificamos si llega un SEATID SPLIT sin TransaccionJuego 
            if (strpos($seatId, '-') !== false) {
                try {
                    // Obtenemos la Transaccion Juego 
                    $TransaccionJuego = new TransaccionJuego("", $TicketID . $UsuarioMandante->getUsumandanteId() . "BGB");
                } catch (Exception $e) {
                    if ($e->getCode() == "28") {
                        // Creamos la Transaccion por el Juego
                        $TransaccionJuego = new TransaccionJuego();
                        $TransaccionJuego->setProductoId($ProductoMandante->prodmandanteId);
                        $TransaccionJuego->setTransaccionId($transactionId);
                        $TransaccionJuego->setTicketId($TicketID . $UsuarioMandante->getUsumandanteId() . "BGB");
                        $TransaccionJuego->setValorTicket(0);
                        $TransaccionJuego->setValorPremio(0);
                        $TransaccionJuego->setMandante($UsuarioMandante->mandante);
                        $TransaccionJuego->setUsuarioId($UsuarioMandante->usumandanteId);
                        $TransaccionJuego->setEstado("A");
                        $TransaccionJuego->setUsucreaId(0);
                        $TransaccionJuego->setUsumodifId(0);

                        // Obtenemos la Transaccion de la BD para crear y registrar el Debito con esta transaccion 
                        $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
                        $TransactionTemp = $TransaccionJuegoMySqlDAO->getTransaction();

                        $transaccion_id = $TransaccionJuego->insert($TransactionTemp);

                        // Creamos el log de la transaccion juego para auditoria  
                        $TransjuegoLog = new TransjuegoLog();
                        $TransjuegoLog->setTransjuegoId($transaccion_id);
                        $TransjuegoLog->setTransaccionId("DEBAUTO" . $transactionId);
                        $TransjuegoLog->setTipo("DEBIT");
                        $TransjuegoLog->setTValue('');
                        $TransjuegoLog->setUsucreaId(0);
                        $TransjuegoLog->setUsumodifId(0);
                        $TransjuegoLog_id = $TransjuegoLog->insert($TransactionTemp);

                        $TransactionTemp->commit();
                    }
                }
            }

            // Obtenemos la Transaccion Juego 
            $TransaccionJuego = new TransaccionJuego("", $TicketID . $UsuarioMandante->getUsumandanteId() . "BGB");

            // Obtenemos el mandante para verificar sus caracteristicas  
            $Mandante = new Mandante($UsuarioMandante->mandante);


            // Verificamos si el mandante es propio  
            if ($Mandante->propio == "S") {
                // Obtenemos la Transaccion de la BD para crear y registrar el Debito con esta transaccion *
                $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
                $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

                // Verificamos que la Transaccion si este conectada y lista para usarse  
                if ($Transaction->isIsconnected()) {
                    // Obtenemos el ID de la TransaccionJuego
                    $TransJuegoId = $TransaccionJuego->getTransjuegoId();

                    // Obtenemos el tipo de transacción dependiendo a betTypeID y si se le suma los creditos o no 
                    $sumaCreditos = false;
                    $tipoTransaccion = "";

                    switch ($betTypeID) {
                        case 101:
                            $tipoTransaccion = "CREDIT";
                            $sumaCreditos = true;
                            break;
                        case 104:
                            $tipoTransaccion = "CREDITINSURANCE";
                            $sumaCreditos = true;
                            break;
                        case 105:
                            $tipoTransaccion = "CREDITDOUBLE";
                            $sumaCreditos = false;
                            break;
                        case 106:
                            $tipoTransaccion = "CREDITSPLIT";
                            $sumaCreditos = true;
                            break;
                        case 107:
                            $tipoTransaccion = "CREDITANTE";
                            $sumaCreditos = false;
                            break;
                        case 116:
                            $tipoTransaccion = "CREDITTABLE";
                            $sumaCreditos = true;
                            break;
                        case 117:
                            $tipoTransaccion = "CREDITSPLITBB";
                            $sumaCreditos = true;
                            break;
                        case 118:
                            $tipoTransaccion = "CREDITDOUBLEBB";
                            $sumaCreditos = true;
                            break;
                        case 119:
                            $tipoTransaccion = "CREDITINSURANCEBB";
                            $sumaCreditos = true;
                            break;
                        case 124:
                            $tipoTransaccion = "CREDITCALL";
                            $sumaCreditos = true;
                            break;
                    }

                    // Creamos el respectivo Log de la transaccion Juego  
                    $TransjuegoLog = new TransjuegoLog();
                    $TransjuegoLog->setTransjuegoId($TransJuegoId);
                    $TransjuegoLog->setTransaccionId($transactionId);
                    $TransjuegoLog->setTipo($tipoTransaccion);
                    $TransjuegoLog->setTValue(json_encode($datos));
                    $TransjuegoLog->setUsucreaId(0);
                    $TransjuegoLog->setUsumodifId(0);
                    $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);

                    if ( ! $TransjuegoLog->isEqualsNewCredit()) {
                        // Si el numero de creditos es mayor al de los debitos sacamos error
                        throw new Exception("CREDIT MAYOR A DEBIT", "10014");
                    }

                    // Actualizamos la Transaccion Juego con los respectivas actualizaciones  
                    $TransaccionJuego->setValorPremio($TransaccionJuego->getValorPremio() + $creditAmount);
                    if ($isEndRound) {
                        if ($TransaccionJuego->getValorPremio() > 0) {
                            $TransaccionJuego->setPremiado("S");
                            $TransaccionJuego->setFechaPago(date('Y-m-d H:i:s', time()));
                        }
                        $TransaccionJuego->setEstado("I");
                    }
                    $TransaccionJuego->update($Transaction);

                    // Si suma los creditos, hacemos el respectivo CREDIT  
                    if ($sumaCreditos) {
                        if ($creditAmount > 0) {
                            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                            $Usuario->credit($creditAmount, $Transaction);
                        }
                    }

                    // Commit de la transacción
                    $Transaction->commit();

                    // Consultamos de nuevo el usuario para obtener el saldo  
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                    $Balance = $Usuario->getBalance();

                    // Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  
                    $UsuarioToken = new UsuarioToken("", $UsuarioMandante->getUsumandanteId());

                    // Enviamos el mensaje Websocket al Usuario para que actualice el saldo  
                    $data = $Usuario->getWSMessage($UsuarioToken->getRequestId());
                    $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                    $WebsocketUsuario->sendWSMessage();

                    // Retornamos el mensaje satisfactorio  

                    $return = array(

                        "operatorId" => $this->getOperadorId(),
                        "uid" => $UsuarioMandante->usumandanteId,
                        "roundId" => $roundId,
                        "token" => $this->token,
                        "balance" => $Balance,
                        "currency" => $currency,
                        "transactionId" => $TransjuegoLog->getTransaccionId(),
                        "errorCode" => 0,
                        "errorDescription" => "ok",
                        "timestamp" => (round(microtime(true) * 1000))
                    );

                    // Guardamos la Transaccion Api necesaria de estado OK   
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
     * Metodo para convertir errores en respuestas JSON.
     *
     * @param integer $code    Código del error.
     * @param string  $message Mensaje del error.
     *
     * @return string JSON con la respuesta del error.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $response = array(

            "operatorId" => $this->getOperadorId(),
            "uid" => $this->uid,
            "token" => $this->token,
            "timestamp" => (round(microtime(true) * 1000))
        );

        if ($this->transaccionApi != null) {
            $tipo = $this->transaccionApi->getTipo();
        }


        if ($tipo == "DEBIT" || $tipo == "CREDIT" || $tipo == "ROLLBACK") {
            $response = array_merge($response, array(
                'transactionId' => $this->data['transactionId'],
                'roundId' => $this->data['roundId']
            ));
        } else {
            $response = array_merge($response, array(
                'VIP' => 0
            ));
        }

        switch ($code) {
            case 10011:
                $codeProveedor = 6;
                $messageProveedor = "Token not found";
                break;

            case 21:
                $codeProveedor = 6;
                $messageProveedor = "Token not found";
                break;

            case 10013:
                $codeProveedor = 7;
                $messageProveedor = "User not found";
                break;

            case 22:
                $codeProveedor = 7;
                $messageProveedor = "User not found";
                break;

            case 20001:
                $codeProveedor = 3;
                $messageProveedor = "Insufficient funds";

                $UsuarioToken = new UsuarioToken($this->token);

                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                $Mandante = new Mandante($UsuarioMandante->mandante);

                if ($Mandante->propio == "S") {
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $saldo = $Usuario->getBalance();

                    $response = array_merge($response, array(
                        "balance" => $saldo,
                        "currency" => $UsuarioMandante->getMoneda()
                    ));
                }


                break;

            case 0:
                $codeProveedor = 1;
                $messageProveedor = "General Error. (" . $code . ")";

                $UsuarioToken = new UsuarioToken($this->token);

                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                $Mandante = new Mandante($UsuarioMandante->mandante);

                if ($Mandante->propio == "S") {
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $saldo = $Usuario->getBalance();

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

            case 28:

                $tipo = $this->transaccionApi->getTipo();

                if ($tipo == "ROLLBACK") {
                    $codeProveedor = 9;
                    $messageProveedor = "Transaction not found";

                    $UsuarioToken = new UsuarioToken($this->token);

                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                    $Mandante = new Mandante($UsuarioMandante->mandante);

                    if ($Mandante->propio == "S") {
                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                        $saldo = $Usuario->getBalance();

                        $response = array_merge($response, array(
                            "balance" => $saldo,
                            "currency" => $UsuarioMandante->getMoneda()
                        ));
                    }
                } else {
                    $codeProveedor = 1;
                    $messageProveedor = "General Error. (" . $code . ")";

                    $UsuarioToken = new UsuarioToken($this->token);

                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                    $Mandante = new Mandante($UsuarioMandante->mandante);

                    if ($Mandante->propio == "S") {
                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                        $saldo = $Usuario->getBalance();

                        $response = array_merge($response, array(
                            "balance" => $saldo,
                            "currency" => $UsuarioMandante->getMoneda()
                        ));
                    }
                }


                break;

            case 29:

                $tipo = $this->transaccionApi->getTipo();

                if ($tipo == "ROLLBACK") {
                    $codeProveedor = 9;
                    $messageProveedor = "Transaction not found";

                    $UsuarioToken = new UsuarioToken($this->token);

                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                    $Mandante = new Mandante($UsuarioMandante->mandante);

                    if ($Mandante->propio == "S") {
                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                        $saldo = $Usuario->getBalance();

                        $response = array_merge($response, array(
                            "balance" => $saldo,
                            "currency" => $UsuarioMandante->getMoneda()
                        ));
                    }
                } else {
                    $codeProveedor = 1;
                    $messageProveedor = "General Error. (" . $code . ")";

                    $UsuarioToken = new UsuarioToken($this->token);

                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                    $Mandante = new Mandante($UsuarioMandante->mandante);

                    if ($Mandante->propio == "S") {
                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                        $saldo = $Usuario->getBalance();

                        $response = array_merge($response, array(
                            "balance" => $saldo,
                            "currency" => $UsuarioMandante->getMoneda()
                        ));
                    }
                }

                break;

            case 10001:

                $tipo = $this->transaccionApi->getTipo();

                if ($tipo == "ROLLBACK") {
                    $codeProveedor = 0;
                    $messageProveedor = "Transaction alredy processed";

                    $UsuarioToken = new UsuarioToken($this->token);

                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                    $Mandante = new Mandante($UsuarioMandante->mandante);

                    if ($Mandante->propio == "S") {
                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                        $saldo = $Usuario->getBalance();

                        $response = array_merge($response, array(
                            "balance" => $saldo,
                            "currency" => $UsuarioMandante->getMoneda()
                        ));
                    }
                } else {
                    $codeProveedor = 0;
                    $messageProveedor = "ok";

                    $UsuarioToken = new UsuarioToken($this->token);

                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                    $Mandante = new Mandante($UsuarioMandante->mandante);

                    if ($Mandante->propio == "S") {
                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                        $saldo = $Usuario->getBalance();

                        $response = array_merge($response, array(
                            "balance" => $saldo,
                            "currency" => $UsuarioMandante->getMoneda()
                        ));
                    }
                }

                break;

            case 10004:
                $codeProveedor = 1;
                $messageProveedor = "General Error. (" . $code . ")";

                $UsuarioToken = new UsuarioToken($this->token);

                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                $Mandante = new Mandante($UsuarioMandante->mandante);

                if ($Mandante->propio == "S") {
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $saldo = $Usuario->getBalance();

                    $response = array_merge($response, array(
                        "balance" => $saldo,
                        "currency" => $UsuarioMandante->getMoneda()
                    ));
                }

                break;

            case 10014:

                $codeProveedor = 1;
                $messageProveedor = "General Error. (" . $code . ")";

                $UsuarioToken = new UsuarioToken($this->token);

                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                $Mandante = new Mandante($UsuarioMandante->mandante);

                if ($Mandante->propio == "S") {
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $saldo = $Usuario->getBalance();

                    $response = array_merge($response, array(
                        "balance" => $saldo,
                        "currency" => $UsuarioMandante->getMoneda()
                    ));
                }

                break;


            default:

                $codeProveedor = 1;
                $messageProveedor = "General Error. (" . $code . ")";

                $UsuarioToken = new UsuarioToken($this->token);

                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                $Mandante = new Mandante($UsuarioMandante->mandante);

                if ($Mandante->propio == "S") {
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $saldo = $Usuario->getBalance();

                    $response = array_merge($response, array(
                        "balance" => $saldo,
                        "currency" => $UsuarioMandante->getMoneda()
                    ));
                }

                break;
        }

        $respuesta = json_encode(array_merge($response, array(
            "errorCode" => $codeProveedor,
            "errorDescription" => $messageProveedor
        )));

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