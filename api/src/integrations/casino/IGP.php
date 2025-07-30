<?php

/**
 * Clase IGP
 *
 * Esta clase contiene métodos para manejar transacciones relacionadas con juegos de casino,
 * incluyendo operaciones de débito, crédito, rollback y autenticación de usuarios.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
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
 * Clase principal para la integración con el proveedor IGP.
 */
class IGP
{
    /**
     * ID del usuario que realiza las transacciones.
     *
     * @var integer|string
     */
    private $usuarioId;

    /**
     * Objeto para manejar las transacciones de la API.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;


    /**
     * Constructor de la clase IGP.
     *
     * @param integer|string $usuarioId ID del usuario que realiza las transacciones.
     */
    public function __construct($usuarioId)
    {
        $this->usuarioId = $usuarioId;
    }


    /**
     * Método para autenticar al usuario y obtener su balance.
     *
     * @return string JSON con el estado de la autenticación y el balance del usuario.
     */
    public function Auth()
    {
        try {
            if ($this->usuarioId == "") {
                throw new Exception("UsuarioId vacio", "10021");
            }

            $UsuarioMandante = new UsuarioMandante($this->usuarioId);

            $Mandante = new Mandante($UsuarioMandante->mandante);

            if ($Mandante->propio == "S") {
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                $Balance = round($Usuario->getBalance(), 2);

                $return = array(

                    "status" => 200,
                    "balance" => $Balance,
                    "msg" => ""

                );
                return json_encode($return);
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para realizar una transacción de débito.
     *
     * @param string  $gameId        ID del juego.
     * @param string  $remoteId      ID remoto de la transacción.
     * @param float   $amount        Monto a debitar.
     * @param string  $transactionId ID de la transacción.
     * @param boolean $gameplayFinal Indica si el juego ha finalizado.
     * @param string  $roundId       ID de la ronda.
     * @param array   $remoteData    Datos remotos adicionales.
     * @param string  $sessionId     ID de la sesión.
     * @param string  $key           Clave de seguridad.
     * @param string  $gamesessionId ID de la sesión de juego.
     * @param string  $gameIdHash    Hash del ID del juego.
     * @param array   $data          Datos adicionales de la transacción.
     *
     * @return string JSON con el estado de la transacción y el balance actualizado.
     */
    public function Debit($gameId, $remoteId, $amount, $transactionId, $gameplayFinal, $roundId, $remoteData, $sessionId, $key, $gamesessionId, $gameIdHash, $data)
    {
        $datos = $data;

        try {
            // Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setIdentificador($roundId . "IGP");
            $this->transaccionApi->setTValue(($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);

            // Obtenemos el Proveedor con el abreviado IGP 
            $Proveedor = new Proveedor("", "IGP");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());

            // Verificamos que el monto a debitar sea positivo 
            if ($amount < 0) {
                throw new Exception("No puede ser negativo el monto a debitar.", "10002");
            }

            // Obtenemos el Usuario Mandante con el UsuarioId 
            $UsuarioMandante = new UsuarioMandante($this->usuarioId);

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

            $TransaccionJuego = new TransaccionJuego();
            $TransaccionJuego->setProductoId($ProductoMandante->prodmandanteId);
            $TransaccionJuego->setTransaccionId($transactionId);
            $TransaccionJuego->setTicketId($roundId . "IGP");
            $TransaccionJuego->setValorTicket($amount);
            $TransaccionJuego->setValorPremio(0);
            $TransaccionJuego->setMandante($UsuarioMandante->mandante);
            $TransaccionJuego->setUsuarioId($UsuarioMandante->usumandanteId);
            $TransaccionJuego->setEstado("A");
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
                        $TransaccionJuego = new TransaccionJuego("", $roundId . "IGP", "");

                        if ($TransaccionJuego->getTransaccionId() != $transactionId) {
                            $TransaccionJuego->setValorTicket($TransaccionJuego->getValorTicket() + $amount);
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
                    $TransjuegoLog->setTValue(($datos));
                    $TransjuegoLog->setUsucreaId(0);
                    $TransjuegoLog->setUsumodifId(0);
                    $TransjuegoLog->setValor($amount);

                    $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);

                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);


                    $Usuario->debit($amount, $Transaction);


                    //Commit de la transacción 
                    $Transaction->commit();


                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                    $Balance = round($Usuario->getBalance(), 2);
                    $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
                    $data = $Usuario->getWSMessage($UsuarioToken->getRequestId());
                    $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                    $WebsocketUsuario->sendWSMessage();

                    $return = array(

                        "status" => "200",
                        "Balance" => $Balance,

                    );

                    // Guardamos la Transaccion Api necesaria de estado OK  
                    $this->transaccionApi->setRespuestaCodigo("OK");
                    $this->transaccionApi->setRespuesta(json_encode($return));
                    $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                    $TransaccionApiMySqlDAO->insert($this->transaccionApi);
                    $TransaccionApiMySqlDAO->getTransaction()->commit();

                    $return["transaction_id"] = $TransjuegoLog_id;

                    return json_encode($return);
                }
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para realizar una transacción de débito gratuito.
     *
     * @param string  $gameId        ID del juego.
     * @param string  $remoteId      ID remoto de la transacción.
     * @param float   $amount        Monto a debitar.
     * @param string  $transactionId ID de la transacción.
     * @param boolean $gameplayFinal Indica si el juego ha finalizado.
     * @param string  $roundId       ID de la ronda.
     * @param array   $remoteData    Datos remotos adicionales.
     * @param string  $sessionId     ID de la sesión.
     * @param string  $key           Clave de seguridad.
     * @param string  $gamesessionId ID de la sesión de juego.
     * @param string  $gameIdHash    Hash del ID del juego.
     * @param array   $data          Datos adicionales de la transacción.
     *
     * @return string JSON con el estado de la transacción y el balance actualizado.
     */
    public function DebitFree($gameId, $remoteId, $amount, $transactionId, $gameplayFinal, $roundId, $remoteData, $sessionId, $key, $gamesessionId, $gameIdHash, $data)
    {
        $datos = $data;

        try {
            // Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBITFREE");
            $this->transaccionApi->setIdentificador($roundId . "IGP");
            $this->transaccionApi->setTValue(($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);

            // Obtenemos el Proveedor con el abreviado IGP 
            $Proveedor = new Proveedor("", "IGP");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());

            // Verificamos que el monto a debitar sea positivo
            if ($amount < 0) {
                throw new Exception("No puede ser negativo el monto a debitar.", "10002");
            }

            // Obtenemos el Usuario Mandante con el UsuarioId 
            $UsuarioMandante = new UsuarioMandante($this->usuarioId);

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

            $TransaccionJuego = new TransaccionJuego();
            $TransaccionJuego->setProductoId($ProductoMandante->prodmandanteId);
            $TransaccionJuego->setTransaccionId($transactionId);
            $TransaccionJuego->setTicketId($roundId . "IGP");
            $TransaccionJuego->setValorTicket($amount);
            $TransaccionJuego->setValorPremio(0);
            $TransaccionJuego->setMandante($UsuarioMandante->mandante);
            $TransaccionJuego->setUsuarioId($UsuarioMandante->usumandanteId);
            $TransaccionJuego->setEstado("A");
            $TransaccionJuego->setUsucreaId(0);
            $TransaccionJuego->setUsumodifId(0);
            $TransaccionJuego->setTipo('FREE');
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
                        $TransaccionJuego = new TransaccionJuego("", $roundId . "IGP", "");

                        if ($TransaccionJuego->getTransaccionId() != $transactionId) {
                            $TransaccionJuego->setValorTicket($TransaccionJuego->getValorTicket() + $amount);
                            $TransaccionJuego->update($Transaction);
                        }

                        $transaccion_id = $TransaccionJuego->getTransjuegoId();
                    } else {
                        $transaccion_id = $TransaccionJuego->insert($Transaction);
                    }

                    $tipoTransaccion = "DEBITFREE";


                    $TransjuegoLog = new TransjuegoLog();
                    $TransjuegoLog->setTransjuegoId($transaccion_id);
                    $TransjuegoLog->setTransaccionId($transactionId);
                    $TransjuegoLog->setTipo($tipoTransaccion);
                    $TransjuegoLog->setTValue(($datos));
                    $TransjuegoLog->setUsucreaId(0);
                    $TransjuegoLog->setUsumodifId(0);
                    $TransjuegoLog->setValor($amount);

                    $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);

                    //$Usuario->setBalance($Balance - $TransaccionJuego->getValorTicket(), $Transaction);
                    //$Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                    //$Usuario->debit($amount, $Transaction);

                    // Commit de la transacción
                    $Transaction->commit();
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                    $Balance = round($Usuario->getBalance(), 2);
                    $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
                    $data = $Usuario->getWSMessage($UsuarioToken->getRequestId());
                    $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                    $WebsocketUsuario->sendWSMessage();

                    $return = array(

                        "status" => "200",
                        "Balance" => $Balance,

                    );

                    // Guardamos la Transaccion Api necesaria de estado OK  
                    $this->transaccionApi->setRespuestaCodigo("OK");
                    $this->transaccionApi->setRespuesta(json_encode($return));
                    $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                    $TransaccionApiMySqlDAO->insert($this->transaccionApi);
                    $TransaccionApiMySqlDAO->getTransaction()->commit();

                    $return["transaction_id"] = $TransjuegoLog_id;

                    return json_encode($return);
                }
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para realizar una transacción de crédito.
     *
     * @param string  $gameId        ID del juego.
     * @param string  $remoteId      ID remoto de la transacción.
     * @param float   $amount        Monto a acreditar.
     * @param string  $transactionId ID de la transacción.
     * @param boolean $gameplayFinal Indica si el juego ha finalizado.
     * @param string  $roundId       ID de la ronda.
     * @param array   $remoteData    Datos remotos adicionales.
     * @param string  $sessionId     ID de la sesión.
     * @param string  $key           Clave de seguridad.
     * @param string  $gamesessionId ID de la sesión de juego.
     * @param string  $gameIdHash    Hash del ID del juego.
     * @param array   $data          Datos adicionales de la transacción.
     *
     * @return string JSON con el estado de la transacción y el balance actualizado.
     */
    public function Credit($gameId, $remoteId, $amount, $transactionId, $gameplayFinal, $roundId, $remoteData, $sessionId, $key, $gamesessionId, $gameIdHash, $data)
    {
        $datos = $data;

        try {
            // Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setIdentificador($roundId . "IGP");
            $this->transaccionApi->setTValue(($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);

            // Obtenemos el Proveedor con el abreviado IGP 
            $Proveedor = new Proveedor("", "IGP");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());


            // Verificamos que el monto a debitar sea positivo
            if ($amount < 0) {
                throw new Exception("No puede ser negativo el monto a debitar.", "10002");
            }

            // Obtenemos el Usuario Mandante con el Usuario ID 
            $UsuarioMandante = new UsuarioMandante($this->usuarioId);


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


            $TransaccionJuego = new TransaccionJuego("", $roundId . "IGP");
            $Mandante = new Mandante($UsuarioMandante->mandante);

            if ($Mandante->propio == "S") {
                $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();

                $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

                if ($Transaction->isIsconnected()) {
                    $TransaccionJuego->setValorPremio($TransaccionJuego->getValorPremio() + $amount);

                    if ($gameplayFinal) {
                        if ($TransaccionJuego->getValorPremio() > 0) {
                            $TransaccionJuego->setPremiado("S");
                            $TransaccionJuego->setFechaPago(time());
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
                    $TransjuegoLog->setTValue(($datos));
                    $TransjuegoLog->setUsucreaId(0);
                    $TransjuegoLog->setUsumodifId(0);
                    $TransjuegoLog->setValor($amount);

                    $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);

                    //$Usuario->setBalance($Balance - $TransaccionJuego->getValorTicket(), $Transaction);

                    if ($sumaCreditos) {
                        if ($amount > 0) {
                            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                            $Usuario->creditWin($amount, $Transaction);
                        }
                    }

                    // Commit de la transacción
                    $Transaction->commit();
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                    $Balance = round($Usuario->getBalance(), 2);
                    $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
                    $data = $Usuario->getWSMessage($UsuarioToken->getRequestId());
                    $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                    $WebsocketUsuario->sendWSMessage();

                    $return = array(

                        "status" => "200",
                        "balance" => $Balance

                    );

                    // Guardamos la Transaccion Api necesaria de estado OK 
                    $this->transaccionApi->setRespuestaCodigo("OK");
                    $this->transaccionApi->setRespuesta(json_encode($return));
                    $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                    $TransaccionApiMySqlDAO->insert($this->transaccionApi);
                    $TransaccionApiMySqlDAO->getTransaction()->commit();

                    $return["transaction_id"] = $TransjuegoLog_id;

                    return json_encode($return);
                }
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para realizar un rollback de una transacción.
     *
     * @param string  $gameId        ID del juego.
     * @param string  $remoteId      ID remoto de la transacción.
     * @param float   $amount        Monto a revertir.
     * @param string  $transactionId ID de la transacción.
     * @param boolean $gameplayFinal Indica si el juego ha finalizado.
     * @param string  $roundId       ID de la ronda.
     * @param array   $remoteData    Datos remotos adicionales.
     * @param string  $sessionId     ID de la sesión.
     * @param string  $key           Clave de seguridad.
     * @param string  $gamesessionId ID de la sesión de juego.
     * @param string  $gameIdHash    Hash del ID del juego.
     * @param array   $data          Datos adicionales de la transacción.
     *
     * @return string JSON con el estado de la transacción y el balance actualizado.
     */
    public function Rollback($gameId, $remoteId, $amount, $transactionId, $gameplayFinal, $roundId, $remoteData, $sessionId, $key, $gamesessionId, $gameIdHash, $data)
    {
        $datos = $data;

        try {
            // Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setIdentificador($roundId . "IGP");
            $this->transaccionApi->setTValue(($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);

            // Obtenemos el Proveedor con el abreviado IGP 
            $Proveedor = new Proveedor("", "IGP");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());

            $UsuarioMandante = new UsuarioMandante($this->usuarioId);

            $TransaccionApiSuper = new TransaccionApi("", $transactionId, $Proveedor->getProveedorId());
            $TransaccionJuego = new TransaccionJuego("", $TransaccionApiSuper->getIdentificador());

            // Verificamos que el monto a debitar sea positivo 
            if ($amount < 0) {
                throw new Exception("No puede ser negativo el monto a debitar.", "10002");
            }

            if ($TransaccionApiSuper->getValor() != $amount) {
                throw new Exception("Valor ticket diferente al rollback", "10003");
            }

            $Mandante = new Mandante($UsuarioMandante->mandante);

            if ($Mandante->propio == "S") {
                $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
                $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

                if ($Transaction->isIsconnected()) {
                    //$TransaccionJuego->setEstado("I");

                    if ($TransaccionApiSuper->getTipo() == "DEBIT") {
                        $TransaccionJuego->setValorTicket($TransaccionJuego->getValorTicket() - $amount);
                    } elseif ($TransaccionApiSuper->getTipo() == "CREDIT") {
                        $TransaccionJuego->setValorPremio($TransaccionJuego->getValorPremio() - $amount);
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
                    $TransjuegoLog->setValor($amount);

                    $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);

                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    if ($TransaccionApiSuper->getTipo() == "DEBIT") {
                        $Usuario->credit($amount, $Transaction);
                    } elseif ($TransaccionApiSuper->getTipo() == "CREDIT") {
                        $Usuario->debit($amount, $Transaction);
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
     * Método para convertir errores en respuestas JSON.
     *
     * @param integer $code    Código de error.
     * @param string  $message Mensaje de error.
     *
     * @return string JSON con el código y mensaje de error.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = $code;
        $messageProveedor = $message;

        $response = [];

        switch ($code) {
            case 10021:
                $codeProveedor = 6;
                $messageProveedor = "Token not found";
                break;

            case 10001:
                $tipo = $this->transaccionApi->getTipo();
                $codeProveedor = 200;
                $messageProveedor = "Transaction alredy processed";

                // Obtenemos el Usuario Mandante con el Usuario ID */
                $UsuarioMandante = new UsuarioMandante($this->usuarioId);
                $Mandante = new Mandante($UsuarioMandante->mandante);

                if ($Mandante->propio == "S") {
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                    $saldo = round($Usuario->getBalance(), 2);

                    $response = array_merge($response, array(
                        "balance" => $saldo
                    ));
                }

                break;

            case 20001:
                $codeProveedor = 403;
                $messageProveedor = "Insufficient funds";
                break;

            default:
                $codeProveedor = 1;
                $messageProveedor = "General Error" . $message;
                break;
        }

        $respuesta = json_encode(array_merge($response, array(
            "status" => $codeProveedor,
            "msg" => $messageProveedor
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