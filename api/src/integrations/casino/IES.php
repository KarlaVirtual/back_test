<?php

/**
 * Clase principal para la integración con el sistema IES.
 *
 * Este archivo contiene la implementación de la clase `IES`, que maneja
 * las operaciones principales como autenticación, débito, crédito y manejo
 * de errores en el sistema IES. También incluye la gestión de transacciones
 * y comunicación con WebSocket.
 *
 * @category Integración
 * @package  Backend\integrations\casino
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */

namespace Backend\integrations\casino;

use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Consecutivo;
use Backend\dto\CuentaCobro;
use Backend\dto\FlujoCaja;
use Backend\dto\Mandante;
use Backend\dto\PuntoVenta;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransaccionApi;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioSession;
use Backend\dto\UsuarioToken;
use Backend\dto\Producto;
use Backend\dto\ProductoMandante;
use Backend\dto\Proveedor;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\FlujoCajaMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransaccionApiMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\websocket\WebsocketUsuario;
use Exception;

/**
 * Clase IES.
 *
 * Esta clase maneja la integración con el sistema IES, proporcionando
 * métodos para autenticación, débito, crédito, rollback y manejo de errores.
 */
class IES
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
     * Indica si el modo FUN está habilitado.
     *
     * @var boolean
     */
    private $isFun = false;

    /**
     * Objeto de transacción API.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * Constructor de la clase IES.
     *
     * Inicializa el objeto con un token y determina si el modo FUN está habilitado.
     *
     * @param string $token Token de autenticación.
     */
    public function __construct($token)
    {
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
     * Autentica al usuario en el sistema IES.
     *
     * Este método verifica el token proporcionado y devuelve el balance
     * del usuario autenticado, junto con la moneda y el estado.
     *
     * @return array|string Respuesta de autenticación o error en formato JSON.
     */
    public function Auth()
    {
        try {
            if ($this->isFun) {
                if ($this->token == "") {
                    throw new Exception("Token vacio", "01");
                }

                $Proveedor = new Proveedor("", "IES");
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
                /*  Creamos la Transaccion API  */
                $this->transaccionApi = new TransaccionApi();
                $this->transaccionApi->setTransaccionId("IESBalance");
                $this->transaccionApi->setTipo("BALANCE");
                $this->transaccionApi->setIdentificador("IESIESBalance");
                $this->transaccionApi->setTValue((''));
                $this->transaccionApi->setUsucreaId(0);
                $this->transaccionApi->setUsumodifId(0);
                $Proveedor = new Proveedor("", "IES");
                $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());

                if ($this->token == "") {
                    throw new Exception("Token vacio", "01");
                }


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
                    return ($return);
                }
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }


    /**
     * Realiza un débito en la cuenta del usuario.
     *
     * Este método descuenta un monto específico del balance del usuario
     * y registra la transacción en el sistema.
     *
     * @param float  $debitAmount   Monto a debitar.
     * @param string $transactionId ID de la transacción.
     * @param array  $data          Datos adicionales de la transacción.
     *
     * @return array|string Respuesta de la operación o error en formato JSON.
     */
    public function Debit($debitAmount, $transactionId, $data)
    {
        exit();

        $datos = $data;

        try {
            //  Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setIdentificador("IES" . $transactionId);
            $this->transaccionApi->setTValue(($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $Proveedor = new Proveedor("", "IES");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());

            if ($debitAmount < 0) {
                throw new Exception("No puede ser negativo el monto a debitar.", "0");
            }

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            // Modificacion TransaccionID Provisional
            $transactionId = $UsuarioMandante->usumandanteId . 'U' . $transactionId . time();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setIdentificador("IES" . $transactionId);

            //  Agregamos Elementos a la Transaccion API  
            $this->transaccionApi->setProductoId(0);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());

            //  Verificamos que la transaccionId no se haya procesado antes  
            if ($this->transaccionApi->existsTransaccionIdAndProveedor("OK")) {
                //  Si la transaccionId ha sido procesada, reportamos el error  
                throw new Exception("Transaccion ya procesada", "10001");
            }


            $Mandante = new Mandante($UsuarioMandante->mandante);

            if ($Mandante->propio == "S") {
                $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();

                $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

                if ($Transaction->isIsconnected()) {
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                    $oldBalance = $Usuario->getBalance();


                    if ($debitAmount > 0) {
                        $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);


                        switch ($UsuarioPerfil->getPerfilId()) {
                            case "USUONLINE":

                                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                                $oldBalance = $Usuario->getBalance();

                                $Usuario->debit($debitAmount, $Transaction);


                                break;

                            case "MAQUINAANONIMA":


                                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                                $oldBalance = $Usuario->getBalance();

                                $Usuario->debit($debitAmount, $Transaction);

                                break;
                        }
                    }

                    $CuentaCobro = new CuentaCobro();

                    $CuentaCobro->usuarioId = $Usuario->usuarioId;

                    $CuentaCobro->valor = $debitAmount;

                    $CuentaCobro->fechaPago = '';

                    $CuentaCobro->fechaCrea = date('Y-m-d H:i:s');


                    $CuentaCobro->usucambioId = 0;
                    $CuentaCobro->usurechazaId = 0;
                    $CuentaCobro->usupagoId = 0;

                    $CuentaCobro->fechaCambio = $CuentaCobro->fechaCrea;
                    $CuentaCobro->fechaAccion = $CuentaCobro->fechaCrea;


                    $CuentaCobro->estado = 'A';
                    $clave = GenerarClaveTicket2(5);

                    $claveEncrypt_Retiro = "12hur12b";

                    $CuentaCobro->clave = "aes_encrypt('" . $clave . "','" . $claveEncrypt_Retiro . "')";

                    $CuentaCobro->mandante = '0';

                    $CuentaCobro->dirIp = 0;

                    $CuentaCobro->impresa = 'S';

                    $CuentaCobro->mediopagoId = 0;
                    $CuentaCobro->puntoventaId = 0;

                    $valorPenalidad = 0;
                    $valorImpuesto = 0;
                    $creditos = 0;
                    $creditosBase = 0;

                    $CuentaCobro->costo = $valorPenalidad;
                    $CuentaCobro->impuesto = $valorImpuesto;
                    $CuentaCobro->creditos = $creditos;
                    $CuentaCobro->creditosBase = $creditosBase;

                    $CuentaCobro->transproductoId = 0;

                    $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO($Transaction);

                    $CuentaCobroMySqlDAO->insert($CuentaCobro);
                    $consecutivo_recarga = $CuentaCobro->cuentaId;

                    $UsuarioHistorial = new UsuarioHistorial();
                    $UsuarioHistorial->setUsuarioId($CuentaCobro->usuarioId);
                    $UsuarioHistorial->setDescripcion('');
                    $UsuarioHistorial->setMovimiento('S');
                    $UsuarioHistorial->setUsucreaId(0);
                    $UsuarioHistorial->setUsumodifId(0);
                    $UsuarioHistorial->setTipo(40);
                    $UsuarioHistorial->setValor($CuentaCobro->valor);
                    $UsuarioHistorial->setExternoId($consecutivo_recarga);

                    $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($CuentaCobroMySqlDAO->getTransaction());
                    $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);


                    // Commit de la transacción
                    $Transaction->commit();
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                    $Balance = $Usuario->getBalance();
                    $currency = $Usuario->moneda;

                    $return = array(

                        "oldBalance" => $oldBalance,
                        "newBalance" => $Balance
                    );

                    // Guardamos la Transaccion Api necesaria de estado OK
                    $this->transaccionApi->setRespuestaCodigo("OK");
                    $this->transaccionApi->setRespuesta(json_encode($return));
                    $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                    $TransaccionApiMySqlDAO->insert($this->transaccionApi);
                    $TransaccionApiMySqlDAO->getTransaction()->commit();


                    $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);

                    /*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
                    $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
                    $data = $Usuario->getWSMessage($UsuarioToken->getRequestId());

                    /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */


                    $UsuarioSession = new UsuarioSession();
                    $rules = [];

                    array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json2 = json_encode($filtro);


                    $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json2, true);

                    $usuarios = json_decode($usuarios);

                    $usuariosFinal = [];

                    $requestsIds = array();
                    foreach ($usuarios->data as $key => $value) {
                        array_push($requestsIds, $value->{'usuario_session.request_id'});
                    }

                    foreach ($usuarios->data as $key => $value) {
                        $dataF = str_replace("7040" . $UsuarioToken->getRequestId() . "1", "7040" . $value->{'usuario_session.request_id'} . "1", json_encode($data));
                        $dataF = json_decode($dataF);

                        foreach ($requestsIds as $requestsId) {
                            $WebsocketUsuario = new WebsocketUsuario($requestsId, $dataF);
                            $WebsocketUsuario->sendWSMessage($value->{'usuario_session.tipo'});
                        }
                    }


                    return ($return);
                }
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un rollback de una transacción.
     *
     * Este método revierte una transacción específica en el sistema.
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
     * Realiza un crédito en la cuenta del usuario.
     *
     * Este método incrementa el balance del usuario con un monto específico
     * y registra la transacción en el sistema.
     *
     * @param float  $creditAmount  Monto a acreditar.
     * @param string $transactionId ID de la transacción.
     * @param array  $data          Datos adicionales de la transacción.
     *
     * @return array|string Respuesta de la operación o error en formato JSON.
     */
    public function Credit($creditAmount, $transactionId, $data)
    {
        $datos = $data;

        try {
            // Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setIdentificador("IES" . $transactionId);
            $this->transaccionApi->setTValue(($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);

            $Proveedor = new Proveedor("", "IES");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            // Modificacion TransaccionID Provisional
            $transactionId = $UsuarioMandante->usumandanteId . 'U' . $transactionId . time();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setIdentificador("IES" . $transactionId);


            // Agregamos Elementos a la Transaccion API 
            $this->transaccionApi->setProductoId(0);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());

            //  Verificamos que la transaccionId no se haya procesado antes  
            if ($this->transaccionApi->existsTransaccionIdAndProveedor("OK")) {
                // Si la transaccionId ha sido procesada, reportamos el error
                throw new Exception("Transaccion ya procesada", "10001");
            }


            $Mandante = new Mandante($UsuarioMandante->mandante);

            if ($Mandante->propio == "S") {
                $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();

                $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

                if ($Transaction->isIsconnected()) {
                    if ($creditAmount > 0) {
                        $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);

                        switch ($UsuarioPerfil->getPerfilId()) {
                            case "USUONLINE":
                                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                                $oldBalance = $Usuario->getBalance();
                                $Usuario->credit($creditAmount, $Transaction);
                                break;

                            case "MAQUINAANONIMA":
                                /*$PuntoVenta = new PuntoVenta("",$UsuarioMandante->usuarioMandante);
                                $oldBalance = $PuntoVenta->getCreditosBase();
                                $PuntoVenta->setBalanceCreditosBase($creditAmount, $Transaction);*/

                                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                                $oldBalance = $Usuario->getBalance();
                                $Usuario->creditWin($creditAmount, $Transaction);

                                break;
                        }
                    }

                    $UsuarioLog = new UsuarioLog();
                    $UsuarioLog->setUsuarioId($UsuarioMandante->usuarioMandante);
                    $UsuarioLog->setUsuarioIp('222');
                    $UsuarioLog->setUsuariosolicitaId($UsuarioMandante->usuarioMandante);
                    $UsuarioLog->setUsuariosolicitaIp('222');
                    $UsuarioLog->setTipo("ESTADOUSUARIO");
                    $UsuarioLog->setEstado("A");
                    $UsuarioLog->setValorAntes("DEPOSIT");
                    $UsuarioLog->setValorDespues("");
                    $UsuarioLog->setUsucreaId(0);
                    $UsuarioLog->setUsumodifId(0);
                    $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO($Transaction);
                    $UsuarioLogMySqlDAO->insert($UsuarioLog);

                    $UsuarioRecarga = new UsuarioRecarga();
                    $UsuarioRecarga->setUsuarioId($Usuario->usuarioId);
                    $UsuarioRecarga->setFechaCrea(date('Y-m-d H:i:s'));
                    $UsuarioRecarga->setPuntoventaId($Usuario->usuarioId);
                    $UsuarioRecarga->setValor($creditAmount);
                    $UsuarioRecarga->setPorcenRegaloRecarga(0);
                    $UsuarioRecarga->setDirIp(0);
                    $UsuarioRecarga->setPromocionalId(0);
                    $UsuarioRecarga->setValorPromocional(0);
                    $UsuarioRecarga->setHost(0);
                    $UsuarioRecarga->setMandante(0);
                    $UsuarioRecarga->setPedido(0);
                    $UsuarioRecarga->setPorcenIva(0);
                    $UsuarioRecarga->setMediopagoId(0);
                    $UsuarioRecarga->setValorIva(0);
                    $UsuarioRecarga->setEstado('A');

                    $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO($Transaction);
                    $UsuarioRecargaMySqlDAO->insert($UsuarioRecarga);
                    $consecutivo_recarga = $UsuarioRecarga->recargaId;

                    $FlujoCaja = new FlujoCaja();
                    $FlujoCaja->setFechaCrea(date('Y-m-d'));
                    $FlujoCaja->setHoraCrea(date('H:i'));
                    $FlujoCaja->setUsucreaId($UsuarioMandante->getUsuarioMandante());
                    $FlujoCaja->setTipomovId('E');
                    $FlujoCaja->setValor($UsuarioRecarga->getValor());
                    $FlujoCaja->setRecargaId($UsuarioRecarga->getRecargaId());
                    $FlujoCaja->setMandante($UsuarioRecarga->getMandante());
                    $FlujoCaja->setTraslado('N');
                    $FlujoCaja->setFormapago1Id(1);
                    $FlujoCaja->setCuentaId('0');

                    if ($FlujoCaja->getFormapago2Id() == "") {
                        $FlujoCaja->setFormapago2Id(0);
                    }

                    if ($FlujoCaja->getValorForma1() == "") {
                        $FlujoCaja->setValorForma1(0);
                    }

                    if ($FlujoCaja->getValorForma2() == "") {
                        $FlujoCaja->setValorForma2(0);
                    }

                    if ($FlujoCaja->getCuentaId() == "") {
                        $FlujoCaja->setCuentaId(0);
                    }

                    if ($FlujoCaja->getPorcenIva() == "") {
                        $FlujoCaja->setPorcenIva(0);
                    }

                    if ($FlujoCaja->getValorIva() == "") {
                        $FlujoCaja->setValorIva(0);
                    }

                    $FlujoCajaMySqlDAO = new FlujoCajaMySqlDAO($Transaction);

                    $rowsUpdate = $FlujoCajaMySqlDAO->insert($FlujoCaja);

                    if ($rowsUpdate > 0) {
                        $UsuarioHistorial = new UsuarioHistorial();
                        $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                        $UsuarioHistorial->setDescripcion('');
                        $UsuarioHistorial->setMovimiento('E');
                        $UsuarioHistorial->setUsucreaId(0);
                        $UsuarioHistorial->setUsumodifId(0);
                        $UsuarioHistorial->setTipo(10);
                        $UsuarioHistorial->setValor($UsuarioRecarga->getValor());
                        $UsuarioHistorial->setExternoId($UsuarioRecarga->getRecargaId());

                        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                        $rowsUpdate = $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);
                    } else {
                        throw new Exception("Error General", "100000");
                    }

                    // Commit de la transacción
                    $Transaction->commit();

                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);


                    switch ($UsuarioPerfil->getPerfilId()) {
                        case "USUONLINE":
                            $Balance = $Usuario->getBalance();
                            break;

                        case "MAQUINAANONIMA":
                            $Balance = $Usuario->getBalance();
                            break;
                    }


                    $currency = $Usuario->moneda;

                    $Mandante = new Mandante($UsuarioMandante->getMandante());

                    $html_retiros = "<table style='width:430px;height:570px;border:1px solid black;'><tr><td align='center' valign='top'><img src='http://198.199.120.164/api/codigo_barras.php?barcode=0000000863&text=0000000863'></td></tr><tr><td align='center' valign='top'><div style='height:1px;'>&nbsp;</div></td></tr><tr><td align='center' valign='top'><font style='text-align:center;font-size:20px;font-weight:bold;'>NOTA DE RETIRO</font></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:16px;font-weight:normal;'>Nota de retiro No.:&nbsp;&nbsp;0000000863</font></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:16px;font-weight:normal;'>No. de Cliente:&nbsp;&nbsp;332</font></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:16px;font-weight:normal;'>Nombre:&nbsp;&nbsp;m m m m</font></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:16px;font-weight:normal;'>Fecha:&nbsp;&nbsp;2018-07-27 03:02:03</font></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:16px;font-weight:normal;'>Clave:&nbsp;&nbsp;LBYAD</font></td></tr><tr><td align='center' valign='top'><div style='height:1px;'>&nbsp;</div></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:18px;font-weight:bold;'>Valor a retirar:&nbsp;&nbsp;5</font></td></tr><tr><td align='center' valign='top'><img src='http://198.199.120.164/api/codigo_barras.php?barcode=LBYAD&text=LBYAD'></td></tr></table>";

                    $html = "<table style='width:180px;height:280px;border:1px solid black;'><tr><td align='center' valign='top'><img style=\"max-height:  60px;\" src='http://198.199.120.164/assets/images/logo.png'></td></tr><tr><td align='center' valign='top'><div style='height:1px;'>&nbsp;</div></td></tr><tr><td align='center' valign='top'><font style='text-align:center;font-size:20px;font-weight:bold;'>RECARGA</font></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>Recarga No.:&nbsp;&nbsp;0000000863</font></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>No. de Cliente:&nbsp;&nbsp;" . $Usuario->usuarioId . "</font></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>Nombre:&nbsp;&nbsp;" . $Usuario->nombre . "</font></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>Fecha:&nbsp;&nbsp;" . date('Y-m-d H:i:s') . "</font></td></tr><tr><td align='center' valign='top'><div style='height:1px;'>&nbsp;</div></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:15px;font-weight:bold;'>Valor :&nbsp;&nbsp;" . $creditAmount . "</font></td></tr></table>";
                    $html = "<table style='width:180px;height:280px;border:1px solid black;'><tr><td align='center' valign='top'><img style=\"max-height:  60px;\" src='http://198.199.120.164/assets/images/logo.png'></td></tr><tr><td align='center' valign='top'><div style='height:1px;'>&nbsp;</div></td></tr><tr><td align='center' valign='top'><font style='text-align:center;font-size:20px;font-weight:bold;'>RECARGA</font></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>Recarga No.:&nbsp;&nbsp;0000000863</font></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>No. de Cliente:&nbsp;&nbsp;" . $Usuario->usuarioId . "</font></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>Nombre:&nbsp;&nbsp;" . $Usuario->nombre . "</font></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>Fecha:&nbsp;&nbsp;" . date('Y-m-d H:i:s') . "</font></td></tr><tr><td align='center' valign='top'><div style='height:1px;'>&nbsp;</div></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:15px;font-weight:bold;'>Valor :&nbsp;&nbsp;" . $creditAmount . "</font></td></tr></table>";

                    $html = "

<!DOCTYPE html>
<html lang=\"en\" >

<head>

  <meta charset=\"UTF-8\">
  <title></title>
  
  
  
  
      <style>
      
      body {
    margin: 0px;
    display: inline-block;
}

      #invoice-POS {
  /*box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);*/
  padding: 2mm;
  width: 44mm;
  background: #FFF;
}
#invoice-POS ::selection {
  background: #f31544;
  color: #FFF;
}
#invoice-POS ::moz-selection {
  background: #f31544;
  color: #FFF;
}
#invoice-POS h1 {
  font-size: 1.5em;
  color: #222;
}
#invoice-POS h2 {
  font-size: .9em;
}
#invoice-POS h3 {
  font-size: 1.2em;
  font-weight: 300;
  line-height: 2em;
}
#invoice-POS p {
  font-size: .7em;
  color: #171717;
  line-height: 1.2em;
}
#invoice-POS #top, #invoice-POS #mid, #invoice-POS #bot {
  /* Targets all id with 'col-' */
  border-bottom: 1px solid #EEE;
}
#invoice-POS #top {
  min-height: 70px;
}
#invoice-POS #mid {
  /*min-height: 80px;*/
} 
#invoice-POS #bot {
  min-height: 50px;
  margin-top: 5px;
}
#invoice-POS #top .logo {
  height: 60px;
  width: 60px;
  background: url(" . $Mandante->logoPdf . ") no-repeat;
  background-size: 60px 60px;
}
#invoice-POS .clientlogo {
  float: left;
  height: 60px;
  width: 60px;
  background: url(http://michaeltruong.ca/images/client.jpg) no-repeat;
  background-size: 60px 60px;
  border-radius: 50px;
}
#invoice-POS .info {
  display: block;
  margin-left: 0;
}
#invoice-POS .title {
  float: right;
}
#invoice-POS .title p {
  text-align: right;
}
#invoice-POS table {
  width: 100%;
  border-collapse: collapse;
}
#invoice-POS .tabletitle {
  font-size: .8em;
  background: #EEE;
}
#invoice-POS .service {
  border-bottom: 1px solid #EEE;
}
#invoice-POS .item {
  width: 24mm;
}
#invoice-POS .itemtext {
  font-size: .5em;
}
#invoice-POS #legalcopy {
  margin-top: 5mm;
}

    </style>

  <script>
  window.console = window.console || function(t) {};
</script>

  
  
  <script>
  if (document.location.search.match(/type=embed/gi)) {
    window.parent.postMessage(\"resize\", \"*\");
  }
</script>


</head>

<body translate=\"no\" >

  
  <div id=\"invoice-POS\">
    
    <center id=\"top\">
      <div class=\"logo\"></div>
      <!--<div class=\"info\"> 
        <h2>SBISTechs Inc</h2>
      </div>-->
    </center>
    
    <div id=\"mid\">
      <div class=\"info\">
        <h2 align='center'>Recarga</h2>
        <p> 
            <b>Fecha:</b> " . $UsuarioRecarga->getFechaCrea() . "</br>
            <b>Recarga:</b> " . $consecutivo_recarga . "</br>
        </p>
      </div>
    </div><!--End Invoice Mid-->
    
    <div id=\"bot\">

					<div id=\"table\">
						<table>
							<tr class=\"tabletitle\">
								<td class=\"item\"><h2>Valor</h2></td>
								<td class=\"Hours\"><h2>" . $creditAmount . "</h2></td>
							</tr>

							<!--<tr class=\"service\">
								<td class=\"tableitem\"><p class=\"itemtext\">Communication</p></td>
								<td class=\"tableitem\"><p class=\"itemtext\">5</p></td>
								<td class=\"tableitem\"><p class=\"itemtext\">$375.00</p></td>
							</tr>


							<tr class=\"tabletitle\">
								<td></td>
								<td class=\"Rate\"><h2>tax</h2></td>
								<td class=\"payment\"><h2>$419.25</h2></td>
							</tr>

							<tr class=\"tabletitle\">
								<td></td>
								<td class=\"Rate\"><h2>Total</h2></td>
								<td class=\"payment\"><h2>$3,644.25</h2></td>
							</tr>-->

						</table>
					</div><!--End Table-->

					<div id=\"legalcopy\">
					</div>

				</div><!--End InvoiceBot-->
  </div><!--End Invoice-->
  
  </body>

</html>
 

";


                    $ConfigurationEnvironment = new ConfigurationEnvironment();
                    $idRecarga = $ConfigurationEnvironment->encrypt($UsuarioRecarga->recargaId);

                    $return = array(

                        "newBalance" => $Balance,
                        "oldBalance" => $oldBalance,
                        "machinePrint22" => $html,
                        "machinePrintURL" => 'https://operatorapi.virtualsoft.tech/machine/machineprint?id=' . $idRecarga

                    );
                    $return2 = array(

                        "newBalance" => $Balance,
                        "oldBalance" => $oldBalance
                    );


                    /*  Guardamos la Transaccion Api necesaria de estado OK   */
                    $this->transaccionApi->setRespuestaCodigo("OK");
                    $this->transaccionApi->setRespuesta(json_encode($return2));
                    $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                    $TransaccionApiMySqlDAO->insert($this->transaccionApi);
                    $TransaccionApiMySqlDAO->getTransaction()->commit();

                    $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);

                    /*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
                    $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
                    $data = $Usuario->getWSMessage($UsuarioToken->getRequestId());

                    /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */


                    $UsuarioSession = new UsuarioSession();
                    $rules = [];

                    array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json2 = json_encode($filtro);


                    $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json2, true);

                    $usuarios = json_decode($usuarios);

                    $usuariosFinal = [];

                    $requestsIds = array();
                    foreach ($usuarios->data as $key => $value) {
                        array_push($requestsIds, $value->{'usuario_session.request_id'});
                    }

                    foreach ($usuarios->data as $key => $value) {
                        $data2 = array(
                            "machinePrint" => '',
                            "messageIntern" => '',
                            "continueToFront" => 1,
                            "machinePrintURL" => 'https://operatorapi.virtualsoft.tech/machineprint/deposit?id=' . $idRecarga

                        );
                        $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data2);
                        $WebsocketUsuario->sendWSMessage($value->{'usuario_session.tipo'});


                        $dataF = str_replace("7040" . $UsuarioToken->getRequestId() . "1", "7040" . $value->{'usuario_session.request_id'} . "1", json_encode($data));
                        $dataF = json_decode($dataF);


                        foreach ($requestsIds as $requestsId) {
                            $WebsocketUsuario = new WebsocketUsuario($requestsId, $dataF);
                            $WebsocketUsuario->sendWSMessage($value->{'usuario_session.tipo'});
                        }
                    }

                    return ($return);
                }
            }
        } catch (Exception $e) {
            $this->transaccionApi->setRespuestaCodigo("ERROR");
            $this->transaccionApi->setRespuesta(json_encode(array()));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->insert($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Convierte un error en un formato estándar.
     *
     * Este método mapea los códigos de error internos a códigos y mensajes
     * estándar para el proveedor.
     *
     * @param integer $code    Código de error interno.
     * @param string  $message Mensaje de error interno.
     *
     * @return string Respuesta del error en formato JSON.
     * @throws Exception Si ocurre un error durante la conversión.
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
                $codeProveedor = 4;
                $messageProveedor = "General Error" . $message;
                break;
        }


        $respuesta = json_encode(array(

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

        throw new Exception($messageProveedor, $codeProveedor);

        return $respuesta;
    }


}