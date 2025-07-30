<?php

/**
 * Clase Ezugi para la integración con el proveedor de juegos de casino.
 *
 * Este archivo contiene la implementación de la clase Ezugi, que maneja
 * las operaciones de autenticación, débito, crédito y rollback para
 * la integración con el proveedor de juegos Ezugi.
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
use Backend\dto\PuntoVenta;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransaccionApi;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioPerfil;
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
 * Clase principal para la integración con el proveedor de juegos Ezugi.
 *
 * Esta clase maneja las operaciones de autenticación, débito, crédito y rollback,
 * además de la comunicación con el proveedor y la gestión de transacciones.
 */
class EzugiBK
{
    /**
     * Identificador del operador.
     *
     * @var integer
     */
    private $operadorId;

    /**
     * Token de autenticación para las operaciones.
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
     * Objeto que representa la transacción API actual.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * Datos asociados a la operación actual.
     *
     * @var array
     */
    private $data;

    /**
     * Constructor de la clase Ezugi.
     *
     * @param integer $operadorId ID del operador.
     * @param string  $token      Token de autenticación.
     * @param string  $uid        Opcional Identificador único del usuario.
     */
    public function __construct($operadorId, $token, $uid = "")
    {
        $this->operadorId = $operadorId;
        $this->operadorId = 10178001;
        $this->token = $token;
        $this->uid = $uid;
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
     * Método para autenticar al usuario con el proveedor Ezugi.
     *
     * Realiza validaciones del token, usuario y mandante, y retorna
     * información del usuario autenticado.
     *
     * @return string Respuesta en formato JSON con los datos del usuario o error.
     * @throws Exception Si ocurre algún error durante la autenticación.
     */
    public function Auth()
    {
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

            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $Mandante = new Mandante($UsuarioMandante->mandante);


            $ConfigurationEnvironment = new ConfigurationEnvironment();

            if ($ConfigurationEnvironment->isDevelopment()) {
                $Clasificador = new Clasificador("", "EXCPRODUCT");

                try {
                    $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioMandante->getUsuarioMandante(), "A", $Clasificador->getClasificadorId(), '2');

                    if ($UsuarioConfiguracion->getProductoId() != "") {
                        throw new Exception("EXCPRODUCT", "20004");
                    }
                } catch (Exception $e) {
                    if ($e->getCode() != 46) {
                        throw $e;
                    }
                }
            }


            if ($Mandante->propio == "S") {
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);

                switch ($UsuarioPerfil->getPerfilId()) {
                    case "USUONLINE":
                        $Balance = $Usuario->getBalance();
                        break;

                    case "MAQUINAANONIMA":
                        /*$PuntoVenta = new PuntoVenta("",$UsuarioMandante->usuarioMandante);
                        $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                        $SaldoJuego = $PuntoVenta->getCreditosBase();
                        $Balance = $SaldoJuego;*/
                        $Balance = $Usuario->getBalance();
                        break;
                }

                $return = array(

                    "operatorId" => $this->getOperadorId(),
                    "uid" => $UsuarioMandante->usumandanteId,
                    "token" => $this->token,
                    "balance" => $Balance,
                    "currency" => $Usuario->moneda,
                    "language" => strtolower($Usuario->idioma),
                    "clienteIP" => $Usuario->dirIp,
                    "VIP" => 0,
                    "errorCode" => 0,
                    "errorDescription" => "ok",
                    "timestamp" => (round(microtime(true) * 1000))
                );


                return json_encode($return);
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza una operación de débito en el sistema.
     *
     * Este método procesa una transacción de débito, valida los datos
     * del usuario, el juego y el proveedor, y actualiza los saldos.
     *
     * @param string  $gameId        ID del juego.
     * @param string  $uid           Identificador único del usuario.
     * @param integer $betTypeID     Tipo de apuesta.
     * @param string  $currency      Moneda utilizada.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $serverId      ID del servidor.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param string  $seatId        ID del asiento.
     * @param string  $hash          Hash de seguridad.
     *
     * @return string Respuesta en formato JSON con los datos de la transacción o error.
     * @throws Exception Si ocurre algún error durante el débito.
     */
    public function Debit($gameId, $uid, $betTypeID, $currency, $debitAmount, $serverId, $roundId, $transactionId, $seatId, $hash)
    {
        $datos = array(
            "token" => $this->token,
            "operatorId" => $this->operadorId,
            "gameId" => $gameId,
            "seatId" => $seatId,
            "uid" => $uid,
            "betTypeID" => $betTypeID,
            "currency" => $currency,
            "debitAmount" => $debitAmount,
            "serverId" => $serverId,
            "roundId" => $roundId,
            "transactionId" => $transactionId,
            "hash" => $hash

        );

        $this->data = $datos;

        try {
            // Obtenemos el Proveedor con el abreviado EZZG
            $Proveedor = new Proveedor("", "EZZG");


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

            // Verificamos que el uid no sea vacio
            if ($uid == "") {
                throw new Exception("UID vacio", "10013");
            }

            // Obtenemos el Usuario Mandante con el UID
            $UsuarioMandante = new UsuarioMandante($uid);

            // Obtenemos el Usuario Token con el token
            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            // Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);


            if ($UsuarioMandante->usumandanteId == 16) {
                $result = '0';
                if ($Proveedor->getTipo() == 'CASINO') {
                    $UsuarioConfiguracion = new UsuarioConfiguracion();

                    $UsuarioConfiguracion->setUsuarioId($UsuarioMandante->usuarioMandante);
                    $result = $UsuarioConfiguracion->verifyLimitesCasino($this->transaccionApi->getValor());
                } elseif ($Proveedor->getTipo() == 'LIVECASINO') {
                    $UsuarioConfiguracion = new UsuarioConfiguracion();

                    $UsuarioConfiguracion->setUsuarioId($UsuarioMandante->usuarioMandante);
                    $result = $UsuarioConfiguracion->verifyLimitesCasinoVivo($this->transaccionApi->getValor());
                }

                if ($result != '0') {
                    throw new Exception("Limite de Autoexclusion", $result);
                }
            }

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "STI" . $betTypeID . $seatId);


            // Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            // Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            //  Agregamos Elementos a la Transaccion API
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());


            $ConfigurationEnvironment = new ConfigurationEnvironment();

            if ($ConfigurationEnvironment->isDevelopment()) {
                $Clasificador = new Clasificador("", "EXCPRODUCT");

                try {
                    $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioMandante->getUsuarioMandante(), "A", $Clasificador->getClasificadorId(), '2');

                    if ($UsuarioConfiguracion->getProductoId() != "") {
                        throw new Exception("EXCPRODUCT", "20004");
                    }
                } catch (Exception $e) {
                    if ($e->getCode() != 46) {
                        throw $e;
                    }
                }
            }

            //  Verificamos que la transaccionId no se haya procesado antes
            if ($this->transaccionApi->existsTransaccionIdAndProveedor("OK")) {
                // Si la transaccionId ha sido procesada, reportamos el error
                throw new Exception("Transaccion ya procesada", "10001");
            }

            // Creamos la Transaccion API Para verificar si hay antes hubo algun ROLLBACK antes
            $TransaccionApiRollback = new TransaccionApi();
            $TransaccionApiRollback->setProveedorId($Proveedor->getProveedorId());
            $TransaccionApiRollback->setProductoId($ProductoMandante->prodmandanteId);
            $TransaccionApiRollback->setUsuarioId($UsuarioMandante->getUsumandanteId());
            $TransaccionApiRollback->setTransaccionId('ROLLBACK' . $transactionId);
            $TransaccionApiRollback->setTipo("ROLLBACK");
            $TransaccionApiRollback->setTValue(json_encode($datos));
            $TransaccionApiRollback->setUsucreaId(0);
            $TransaccionApiRollback->setUsumodifId(0);


            //  Verificamos que la transaccionId no se haya procesado antes
            if ($TransaccionApiRollback->existsTransaccionIdAndProveedor("ERROR")) {
                // Si la transaccionId tiene un Rollback antes, reportamos el error
                throw new Exception("Transaccion con Rollback antes", "10004");
            }

            // Creamos la Transaccion por el Juego
            $TransaccionJuego = new TransaccionJuego();
            $TransaccionJuego->setProductoId($ProductoMandante->prodmandanteId);
            $TransaccionJuego->setTransaccionId($transactionId);
            $TransaccionJuego->setTicketId($roundId . $UsuarioMandante->getUsumandanteId() . "STI" . $betTypeID . $seatId);
            $TransaccionJuego->setValorTicket($debitAmount);
            $TransaccionJuego->setValorPremio(0);
            $TransaccionJuego->setMandante($UsuarioMandante->mandante);
            $TransaccionJuego->setUsuarioId($UsuarioMandante->usumandanteId);
            $TransaccionJuego->setEstado("A");
            $TransaccionJuego->setUsucreaId(0);
            $TransaccionJuego->setUsumodifId(0);
            $TransaccionJuego->setPremiado('N');
            $TransaccionJuego->setFechaPago(date('Y-m-d H:i:s'));

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
                    // Validacion en Maquina
                    if ($UsuarioMandante->getUsumandanteId() == "1722" || $UsuarioMandante->getUsumandanteId() == "1796") {
                        $ProveedorMaquina = new Proveedor("", "IES");
                        // Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket
                        $UsuarioTokenMaquina = new UsuarioToken("", $ProveedorMaquina->getProveedorId(), $UsuarioMandante->getUsumandanteId());


                        $data = array(
                            "command" => "betshop-livecasino-bet",
                            "sid" => $UsuarioTokenMaquina->getRequestId(),
                            "params" => array(
                                "externalId" => $roundId . $UsuarioMandante->getUsumandanteId() . "STI" . $betTypeID . $seatId,
                                "amount" => $debitAmount,
                                "currency" => "COP",
                                "message" => "Apuesta en el juego ruleta",
                                "playId" => "Ruleta123456",
                                "nameGame" => "Ruleta"
                            ),
                            "rid" => "2018-01-23T18:53:04.139Z"
                        );
                        $WebsocketUsuario = new WebsocketUsuario($UsuarioTokenMaquina->getRequestId(), $data);


                        $mensajeMaquina = $WebsocketUsuario->sendWSMessageWithReturn();

                        // Creamos la Transaccion API Para verificar si hay antes hubo algun ROLLBACK antes
                        $TransaccionApiMaquina = new TransaccionApi();
                        $TransaccionApiMaquina->setProveedorId($ProveedorMaquina->getProveedorId());
                        $TransaccionApiMaquina->setProductoId(0);
                        $TransaccionApiMaquina->setUsuarioId($UsuarioMandante->getUsumandanteId());
                        $TransaccionApiMaquina->setTransaccionId($transactionId);
                        $TransaccionApiMaquina->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "STI" . $betTypeID . $seatId);
                        $TransaccionApiMaquina->setTipo("DEBITMACHINE");
                        $TransaccionApiMaquina->setTValue(json_encode($data));
                        $TransaccionApiMaquina->setUsucreaId(0);
                        $TransaccionApiMaquina->setUsumodifId(0);
                        $TransaccionApiMaquina->setRespuestaCodigo($mensajeMaquina);
                        $TransaccionApiMaquina->setRespuesta(($mensajeMaquina));
                        $TransaccionApiMaquina->setValor($debitAmount);

                        $TransaccionApiMySqlDAO2 = new TransaccionApiMySqlDAO();
                        $TransaccionApiMySqlDAO2->insert($TransaccionApiMaquina);
                        $TransaccionApiMySqlDAO2->getTransaction()->commit();

                        if ($mensajeMaquina == "ERROR") {
                            throw new Exception("No se puede procesar.", "10041");
                        }
                    }


                    // Verificamos si Existe el ticket para combinar las apuestas.
                    if ($ExisteTicket) {
                        // Obtenemos la Transaccion Juego y combinamos las aúestas.
                        $TransaccionJuego = new TransaccionJuego("", $roundId . $UsuarioMandante->getUsumandanteId() . "STI" . $betTypeID . $seatId, "");

                        if ($TransaccionJuego->getTransaccionId() != $transactionId) {
                            $TransaccionJuego->setValorTicket($TransaccionJuego->getValorTicket() + $debitAmount);
                            $TransaccionJuego->update($Transaction);
                        }

                        $transaccion_id = $TransaccionJuego->getTransjuegoId();
                    } else {
                        $transaccion_id = $TransaccionJuego->insert($Transaction);
                    }

                    // Obtenemos el tipo de Transaccion dependiendo de el betTypeID
                    $tipoTransaccion = "";

                    switch ($betTypeID) {
                        case 1:
                            $tipoTransaccion = "DEBIT";
                            break;
                        case 3:
                            $tipoTransaccion = "DEBITTIP";
                            break;

                        case 4:
                            $tipoTransaccion = "DEBITINSURANCE";
                            break;

                        case 5:
                            $tipoTransaccion = "DEBITDOUBLE";
                            break;

                        case 6:
                            $tipoTransaccion = "DEBITSPLIT";
                            break;

                        case 7:
                            $tipoTransaccion = "DEBITANTE";
                            break;

                        case 16:
                            $tipoTransaccion = "DEBITTABLE";
                            break;

                        case 17:
                            $tipoTransaccion = "DEBITSPLITBB";
                            break;

                        case 18:
                            $tipoTransaccion = "DEBITDOUBLEBB";
                            break;

                        case 19:
                            $tipoTransaccion = "DEBITINSURANCEBB";
                            break;

                        case 24:
                            $tipoTransaccion = "DEBITCALL";
                            break;
                    }

                    // Creamos el log de la transaccion juego para auditoria
                    $TransjuegoLog = new TransjuegoLog();
                    $TransjuegoLog->setTransjuegoId($transaccion_id);
                    $TransjuegoLog->setTransaccionId($transactionId);
                    $TransjuegoLog->setTipo($tipoTransaccion);
                    $TransjuegoLog->setTValue(json_encode($datos));
                    $TransjuegoLog->setUsucreaId(0);
                    $TransjuegoLog->setUsumodifId(0);
                    $TransjuegoLog->setValor($debitAmount);

                    $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);

                    // Obtenemos nuestro Usuario y hacemos el debito
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                    $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);

                    if ($Usuario->estado != "A") {
                        throw new Exception("Usuario Inactivo", "20003");
                    }


                    switch ($UsuarioPerfil->getPerfilId()) {
                        case "USUONLINE":
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


                            break;

                        case "MAQUINAANONIMA":
                            /*$PuntoVenta = new PuntoVenta("",$UsuarioMandante->usuarioMandante);
                            $PuntoVenta->setBalanceCreditosBase(-$debitAmount,$Transaction);*/


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

                            break;
                    }

                    // Commit de transacción
                    $Transaction->commit();

                    // Consultamos de nuevo el usuario para obtener el saldo
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                    $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);

                    switch ($UsuarioPerfil->getPerfilId()) {
                        case "USUONLINE":
                            $Balance = $Usuario->getBalance();
                            break;

                        case "MAQUINAANONIMA":
                            /*$PuntoVenta = new PuntoVenta("",$UsuarioMandante->usuarioMandante);
                            $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                            $SaldoJuego = $PuntoVenta->getCreditosBase();
                            $Balance = $SaldoJuego;*/
                            $Balance = $Usuario->getBalance();
                            break;
                    }

                    // Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket
                    $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                    // Enviamos el mensaje Websocket al Usuario para que actualice el saldo
                    $data = $Usuario->getWSMessage($UsuarioToken->getRequestId());
                    $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                    $WebsocketUsuario->sendWSMessage();

                    $ConfigurationEnvironment = new ConfigurationEnvironment();

                    if ($ConfigurationEnvironment->isDevelopment()) {
                        $UsuarioToken = new UsuarioToken("", '1', $UsuarioMandante->getUsumandanteId());

                        $data = $UsuarioMandante->getWSProfileSite($UsuarioToken->getRequestId());
                        $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                        $WebsocketUsuario->sendWSMessage();
                    }

                    // Retornamos el mensaje satisfactorio
                    $return = array(
                        "operatorId" => $this->getOperadorId(),
                        "uid" => $UsuarioMandante->usumandanteId,
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

                    exec("php -f " . __DIR__ . "/VerificarTorneo.php LIVECASINO " . $this->transaccionApi->transapiId . " " . $UsuarioMandante->usuarioMandante . " > /dev/null &");

                    return json_encode($return);
                }
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza una operación de rollback en el sistema.
     *
     * Este método revierte una transacción de débito previa, valida los datos
     * del usuario, el juego y el proveedor, y actualiza los saldos.
     *
     * @param string  $gameId         ID del juego.
     * @param string  $uid            Identificador único del usuario.
     * @param integer $betTypeID      Tipo de apuesta.
     * @param string  $currency       Moneda utilizada.
     * @param float   $rollbackAmount Monto a revertir.
     * @param string  $serverId       ID del servidor.
     * @param string  $roundId        ID de la ronda.
     * @param string  $transactionId  ID de la transacción.
     * @param string  $seatId         ID del asiento.
     * @param string  $hash           Hash de seguridad.
     *
     * @return string Respuesta en formato JSON con los datos de la transacción o error.
     * @throws Exception Si ocurre algún error durante el rollback.
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

        switch ($betTypeID) {
            case 101:
                $betTypeID2 = 1;
                break;

            case 104:
                $betTypeID2 = 4;
                break;

            case 105:
                $betTypeID2 = 5;
                break;

            case 106:
                $betTypeID2 = 6;
                break;

            case 107:
                $betTypeID2 = 7;
                break;

            case 116:
                $betTypeID2 = 16;
                break;

            case 117:
                $betTypeID2 = 17;
                break;

            case 118:
                $betTypeID2 = 18;
                break;

            case 119:
                $betTypeID2 = 19;
                break;

            case 124:
                $betTypeID2 = 24;
                break;
        }

        try {
            // Obtenemos el Proveedor con el abreviado EZZG
            $Proveedor = new Proveedor("", "EZZG");

            // Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($rollbackAmount);

            // Verificamos que el uid no sea vacio
            if ($uid == "") {
                throw new Exception("UID vacio", "10013");
            }

            // Obtenemos el Usuario Mandante con el UID
            $UsuarioMandante = new UsuarioMandante($uid);


            // Obtenemos el Usuario Token con el token
            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            // Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "STI" . $betTypeID2 . $seatId);

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
            } catch (Execption $e) {
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
            $TransaccionJuego = new TransaccionJuego("", $TransaccionApi2->getIdentificador());

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
                    $TransaccionJuego->setValorPremio($rollbackAmount);
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
                    $TransjuegoLog->setValor($rollbackAmount);

                    $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);

                    // Obtenemos el Usuario para hacerle el credito
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                    $Usuario->credit($TransaccionJuego->getValorTicket(), $Transaction);


                    $UsuarioHistorial = new UsuarioHistorial();
                    $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                    $UsuarioHistorial->setDescripcion('');
                    $UsuarioHistorial->setMovimiento('C');
                    $UsuarioHistorial->setUsucreaId(0);
                    $UsuarioHistorial->setUsumodifId(0);
                    $UsuarioHistorial->setTipo(30);
                    $UsuarioHistorial->setValor($TransaccionJuego->getValorTicket());
                    $UsuarioHistorial->setExternoId($TransjuegoLog_id);

                    $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                    $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);


                    // Commit de la transacción
                    $Transaction->commit();

                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                    $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);

                    switch ($UsuarioPerfil->getPerfilId()) {
                        case "USUONLINE":
                            $Balance = $Usuario->getBalance();
                            break;

                        case "MAQUINAANONIMA":

                            $Balance = $Usuario->getBalance();
                            break;
                    }

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
     * Realiza una operación de crédito en el sistema.
     *
     * Este método procesa una transacción de crédito, valida los datos
     * del usuario, el juego y el proveedor, y actualiza los saldos.
     *
     * @param string  $gameId         ID del juego.
     * @param string  $uid            Identificador único del usuario.
     * @param integer $betTypeID      Tipo de apuesta.
     * @param string  $currency       Moneda utilizada.
     * @param float   $creditAmount   Monto a acreditar.
     * @param string  $serverId       ID del servidor.
     * @param string  $roundId        ID de la ronda.
     * @param string  $transactionId  ID de la transacción.
     * @param string  $seatId         ID del asiento.
     * @param string  $gameDataString Datos adicionales del juego.
     * @param bool    $isEndRound     Indica si es el final de la ronda.
     * @param int     $creditIndex    Índice del crédito.
     * @param string  $hash           Hash de seguridad.
     *
     * @return string Respuesta en formato JSON con los datos de la transacción o error.
     * @throws Exception Si ocurre algún error durante el crédito.
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

        switch ($betTypeID) {
            case 101:
                $betTypeID2 = 1;
                break;

            case 104:
                $betTypeID2 = 4;
                break;

            case 105:
                $betTypeID2 = 5;
                break;

            case 106:
                $betTypeID2 = 6;
                break;

            case 107:
                $betTypeID2 = 7;
                break;

            case 116:
                $betTypeID2 = 16;
                break;

            case 117:
                $betTypeID2 = 17;
                break;

            case 118:
                $betTypeID2 = 18;
                break;

            case 119:
                $betTypeID2 = 19;
                break;

            case 124:
                $betTypeID2 = 24;
                break;
        }


        try {
            // Obtenemos el Proveedor con el abreviado EZZG
            $Proveedor = new Proveedor("", "EZZG");

            // Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);

            // Verificamos que el uid no sea vacio
            if ($uid == "") {
                throw new Exception("UID vacio", "10013");
            }

            // Obtenemos el Usuario Mandante con el UID
            $UsuarioMandante = new UsuarioMandante($uid);

            // Obtenemos el Usuario Token con el token
            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            // Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "STI" . $betTypeID2 . $seatId);

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
                    $TransaccionJuego = new TransaccionJuego("", $roundId . $UsuarioMandante->getUsumandanteId() . "STI" . $betTypeID2 . $seatId);
                } catch (Exception $e) {
                    if ($e->getCode() == "28") {
                        // Creamos la Transaccion por el Juego
                        $TransaccionJuego = new TransaccionJuego();
                        $TransaccionJuego->setProductoId($ProductoMandante->prodmandanteId);
                        $TransaccionJuego->setTransaccionId($transactionId);
                        $TransaccionJuego->setTicketId($roundId . $UsuarioMandante->getUsumandanteId() . "STI" . $betTypeID2 . $seatId);
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
                        $TransjuegoLog->setValor(0);

                        $TransjuegoLog_id = $TransjuegoLog->insert($TransactionTemp);

                        $TransactionTemp->commit();
                    }
                }
            }

            // Obtenemos la Transaccion Juego
            $TransaccionJuego = new TransaccionJuego("", $roundId . $UsuarioMandante->getUsumandanteId() . "STI" . $betTypeID2 . $seatId);

            // Obtenemos el mandante para verificar sus caracteristicas
            $Mandante = new Mandante($UsuarioMandante->mandante);


            // Verificamos si el mandante es propio
            if ($Mandante->propio == "S") {
                // Obtenemos la Transaccion de la BD para crear y registrar el Debito con esta transaccion
                $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
                $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

                // Verificamos que la Transaccion si este conectada y lista para usarse
                if ($Transaction->isIsconnected()) {
                    // Validacion en Maquina
                    if ($UsuarioMandante->getUsumandanteId() == "1722" || $UsuarioMandante->getUsumandanteId() == "1796") {
                        $ProveedorMaquina = new Proveedor("", "IES");

                        // Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket
                        $UsuarioTokenMaquina = new UsuarioToken("", $ProveedorMaquina->getProveedorId(), $UsuarioMandante->getUsumandanteId());


                        $data = array(
                            "command" => "betshop-livecasino-win",
                            "sid" => $UsuarioTokenMaquina->getRequestId(),
                            "params" => array(
                                "externalId" => $roundId . $UsuarioMandante->getUsumandanteId() . "STI" . $betTypeID . $seatId,
                                "amount" => $creditAmount,
                                "currency" => "COP",
                                "message" => "Apuesta en el juego ruleta",
                                "playId" => "Ruleta123456",
                                "nameGame" => "Ruleta"
                            ),
                            "rid" => "2018-01-23T18:53:04.139Z"
                        );

                        $WebsocketUsuario = new WebsocketUsuario($UsuarioTokenMaquina->getRequestId(), $data);
                        $mensajeMaquina = $WebsocketUsuario->sendWSMessageWithReturn();

                        // Creamos la Transaccion API Para verificar si hay antes hubo algun ROLLBACK antes
                        $TransaccionApiMaquina = new TransaccionApi();
                        $TransaccionApiMaquina->setProveedorId($ProveedorMaquina->getProveedorId());
                        $TransaccionApiMaquina->setProductoId(0);
                        $TransaccionApiMaquina->setUsuarioId($UsuarioMandante->getUsumandanteId());
                        $TransaccionApiMaquina->setTransaccionId($transactionId);
                        $TransaccionApiMaquina->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "STI" . $betTypeID . $seatId);
                        $TransaccionApiMaquina->setTipo("CREDITMACHINE");
                        $TransaccionApiMaquina->setTValue(json_encode($data));
                        $TransaccionApiMaquina->setUsucreaId(0);
                        $TransaccionApiMaquina->setUsumodifId(0);
                        $TransaccionApiMaquina->setRespuestaCodigo($mensajeMaquina);
                        $TransaccionApiMaquina->setRespuesta(json_encode($mensajeMaquina));
                        $TransaccionApiMaquina->setValor($creditAmount);

                        $TransaccionApiMySqlDAO2 = new TransaccionApiMySqlDAO();
                        $TransaccionApiMySqlDAO2->insert($TransaccionApiMaquina);
                        $TransaccionApiMySqlDAO2->getTransaction()->commit();

                        if ($mensajeMaquina == "ERROR") {
                            throw new Exception("No se puede procesar.", "10041");
                        }
                    }


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
                    $TransjuegoLog->setValor($creditAmount);

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
                            $TransaccionJuego->setFechaPago((date('Y-m-d H:i:s')));
                        }
                        $TransaccionJuego->setEstado("I");
                    }
                    $TransaccionJuego->update($Transaction);

                    // Si suma los creditos, hacemos el respectivo CREDIT
                    if ($sumaCreditos) {
                        if ($creditAmount > 0) {
                            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                            $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);


                            switch ($UsuarioPerfil->getPerfilId()) {
                                case "USUONLINE":

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


                                    break;

                                case "MAQUINAANONIMA":

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


                                    break;
                            }
                        }
                    }

                    // Commit de transacción
                    $Transaction->commit();

                    // Consultamos de nuevo el usuario para obtener el saldo
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                    $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);

                    switch ($UsuarioPerfil->getPerfilId()) {
                        case "USUONLINE":
                            $Balance = $Usuario->getBalance();
                            break;

                        case "MAQUINAANONIMA":
                            /*$PuntoVenta = new PuntoVenta("",$UsuarioMandante->usuarioMandante);
                            $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                            $SaldoJuego = $PuntoVenta->getCreditosBase();
                            $Balance = $SaldoJuego;*/

                            $Balance = $Usuario->getBalance();

                            break;
                    }
                    // Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket
                    $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                    // Enviamos el mensaje Websocket al Usuario para que actualice el saldo
                    $data = $Usuario->getWSMessage($UsuarioToken->getRequestId());
                    $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                    $WebsocketUsuario->sendWSMessage();


                    $ConfigurationEnvironment = new ConfigurationEnvironment();

                    if ($ConfigurationEnvironment->isDevelopment()) {
                        $UsuarioToken = new UsuarioToken("", '1', $UsuarioMandante->getUsumandanteId());

                        $data = $UsuarioMandante->getWSProfileSite($UsuarioToken->getRequestId());
                        $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                        $WebsocketUsuario->sendWSMessage();
                    }

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


                    if ($sumaCreditos) {
                        exec("php -f " . __DIR__ . "/VerificarTorneoPremio.php LIVECASINO " . $this->transaccionApi->transapiId . " " . $UsuarioMandante->usuarioMandante . " > /dev/null &");
                    }

                    return json_encode($return);
                }
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Convierte un error en una respuesta JSON estándar.
     *
     * Este método mapea los códigos de error internos a los códigos
     * y mensajes esperados por el proveedor Ezugi.
     *
     * @param integer $code    Código de error interno.
     * @param string  $message Mensaje de error interno.
     *
     * @return string Respuesta en formato JSON con el error convertido.
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

        $tipo = $this->transaccionApi->getTipo();

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

        $Proveedor = new Proveedor("", "EZZG");

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

                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

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

                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

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

            case 10041:
                $codeProveedor = 1;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 28:

                $tipo = $this->transaccionApi->getTipo();

                if ($tipo == "ROLLBACK") {
                    $codeProveedor = 9;
                    $messageProveedor = "Transaction not found";

                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

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
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
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

                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

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

                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

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

                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

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

                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

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

                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

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

                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

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

                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

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