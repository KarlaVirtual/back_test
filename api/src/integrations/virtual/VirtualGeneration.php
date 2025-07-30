<?php

/**
 * Esta clase gestiona las operaciones relacionadas con transacciones virtuales.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-22
 */

namespace Backend\integrations\virtual;

use Backend\dto\Mandante;
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
 * Clase `VirtualGeneration`
 *
 * Esta clase gestiona las operaciones relacionadas con transacciones virtuales,
 * incluyendo débitos, créditos, rollbacks y resolución de transacciones.
 * Proporciona métodos para interactuar con usuarios, mandantes, productos y
 * proveedores, asegurando la integridad de las transacciones y el manejo de errores.
 */
class VirtualGeneration
{
    /**
     * Token de autenticación para la clase.
     *
     * @var string
     */
    private $token;

    /**
     * Indica si la operación es de tipo "Fu".
     *
     * @var boolean
     */
    private $isFu = false;

    /**
     * Objeto para gestionar transacciones API.
     *
     * @var integer
     */
    private $transaccionApi;

    /**
     * Identificador del producto utilizado en las transacciones.
     *
     * @var integer
     */
    private $ProductoId = 4374;

    /**
     * Constructor de la clase VirtualGeneration.
     *
     * @param string $token Token de autenticación para inicializar la clase.
     */
    public function __construct($token)
    {
        $this->token = $token;
        $this->ProductoId = 609;
    }

    /**
     * Obtiene el identificador del operador.
     *
     * @return mixed Identificador del operador.
     */
    public function getOperadorId()
    {
        return $this->operadorId;
    }

    /**
     * Realiza la autenticación del usuario o unidad según el entorno configurado.
     *
     * @return array Información del balance y estado de la autenticación.
     * @throws Exception Si el token o el unit_id están vacíos.
     */
    public function Auth()
    {
        try {
            if ($this->isFun) {
                if ($this->token == "") {
                    throw new Exception("Token vacio", "01");
                }
                /*  Obtenemos el Proveedor con el abreviado IGP */
                $Proveedor = new Proveedor("", "VGT");

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
                    return ($return);
                }
            } else {
                if ($this->unit_id == "") {
                    throw new Exception("Unit_ID vacio", "01");
                }
                /*  Obtenemos el Proveedor con el abreviado IGP */
                $Proveedor = new Proveedor("", "VGT");

                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), "", "", $this->unit_id);

                $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId());

                $Mandante = new Mandante($UsuarioMandante->mandante);

                if ($Mandante->propio == "S") {
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $Balance = $Usuario->getBalance();

                    $return = array(
                        "credit" => $Balance,
                        "currencyCode" => $Usuario->moneda,
                        "ext_id" => $this->ext_id,
                        "extToken" => $UsuarioToken->getToken(),
                        "login_hash" => "",
                        "result" => "success"
                    );
                    return ($return);
                }
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un débito de gasto en la cuenta del usuario.
     *
     * @param float  $debitAmount   Monto a debitar.
     * @param string $transactionId Identificador de la transacción.
     * @param array  $data          Datos adicionales relacionados con la transacción.
     *
     * @return array Resultado de la operación.
     * @throws Exception Si el monto a debitar es negativo o la transacción ya fue procesada.
     */
    public function DebitSpend($debitAmount, $transactionId, $data)
    {
        $datos = json_encode($data);;


        try {
            /*  Obtenemos el Proveedor con el abreviado IGP */
            $Proveedor = new Proveedor("", "VGT");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId("DS" . $transactionId);
            $this->transaccionApi->setTipo("DEBITSPEND");
            $this->transaccionApi->setIdentificador("VGT" . $transactionId);
            $this->transaccionApi->setTValue(($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);

            if ($debitAmount < 0) {
                throw new Exception("No puede ser negativo el monto a debitar.", "0");
            }


            $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), "", "", $this->unit_id);

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId());


            /*  Obtenemos el producto con el gameId  */
            $Producto = new Producto($this->ProductoId, $this->ProductoId, $Proveedor->getProveedorId());

            /*  Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego  */
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            /*  Agregamos Elementos a la Transaccion API  */
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());

            /*  Verificamos que la transaccionId no se haya procesado antes  */
            if ($this->transaccionApi->existsTransaccionIdAndProveedor("OK")) {
                /*  Si la transaccionId ha sido procesada, reportamos el error  */
                throw new Exception("Transaccion ya procesada", "10001");
            }
            $ticketid = '';
            $TransaccionJuego = new TransaccionJuego();
            $TransaccionJuego->setProductoId($ProductoMandante->prodmandanteId);
            $TransaccionJuego->setTransaccionId($transactionId);
            $TransaccionJuego->setTicketId("VGT" . $ticketid);
            $TransaccionJuego->setValorTicket($debitAmount);
            $TransaccionJuego->setValorPremio(0);
            $TransaccionJuego->setMandante($UsuarioMandante->mandante);
            $TransaccionJuego->setUsuarioId($UsuarioMandante->usumandanteId);
            $TransaccionJuego->setEstado("A");
            $TransaccionJuego->setUsucreaId(0);
            $TransaccionJuego->setUsumodifId(0);
            $TransaccionJuego->setPremiado('N');
            $TransaccionJuego->setFechaPago(date('Y-m-d H:i:s'));


            $Mandante = new Mandante($UsuarioMandante->mandante);

            if ($Mandante->propio == "S") {
                $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();

                $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

                if ($Transaction->isIsconnected()) {
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $BalanceOld = $Usuario->getBalance();

                    $Usuario->debit($debitAmount, $Transaction);


                    $Transaction->commit();

                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $Balance = $Usuario->getBalance();

                    $currency = $Usuario->moneda;

                    $return = array(

                        "result" => "success",
                        "oldCredit" => $BalanceOld,
                        "newCredit" => $Balance,
                        "ext_id" => $this->ext_id,
                        "unit_id" => $this->unit_id,
                        'tmp_id' => (round(microtime(true) * 1000))
                    );

                    /*  Guardamos la Transaccion Api necesaria de estado OK   */
                    $this->transaccionApi->setRespuestaCodigo("OK");
                    $this->transaccionApi->setRespuesta(json_encode($return));
                    $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                    $TransaccionApiMySqlDAO->insert($this->transaccionApi);
                    $TransaccionApiMySqlDAO->getTransaction()->commit();

                    exec(
                        "php -f " . __DIR__ . "/../casino/VerificarTorneo.php VIRTUAL " . $this->transaccionApi->transapiId . " " . $UsuarioMandante->usuarioMandante . " > /dev/null &"
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
     * @param string $ticketid      Identificador del ticket.
     * @param float  $debitAmount   Monto a debitar.
     * @param string $transactionId Identificador de la transacción.
     * @param array  $data          Datos adicionales relacionados con la transacción.
     *
     * @return array Resultado de la operación.
     * @throws Exception Si el monto a debitar es negativo o la transacción ya fue procesada.
     */
    public function Debit($ticketid, $debitAmount, $transactionId, $data)
    {
        $datos = json_encode($data);;

        try {
            /*  Obtenemos el Proveedor con el abreviado IGP */
            $Proveedor = new Proveedor("", "VGT");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setIdentificador("VGT" . $ticketid);
            $this->transaccionApi->setTValue(($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setValor($debitAmount);

            if ($debitAmount < 0) {
                throw new Exception("No puede ser negativo el monto a debitar.", "0");
            }


            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId(), "", "");

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId());


            /*  Obtenemos el producto con el gameId  */
            $Producto = new Producto($this->ProductoId, $this->ProductoId, $Proveedor->getProveedorId());

            /*  Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego  */
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            /*  Agregamos Elementos a la Transaccion API  */
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());

            /*  Creamos la Transaccion API  */
            $SpendtransaccionApi = new TransaccionApi();
            $SpendtransaccionApi->setTransaccionId($transactionId);
            $SpendtransaccionApi->setTipo("DEBIT");
            $SpendtransaccionApi->setIdentificador("VGT" . $ticketid);
            $SpendtransaccionApi->setTValue(($datos));
            $SpendtransaccionApi->setUsucreaId(0);
            $SpendtransaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);

            /*  Verificamos que la transaccionId no se haya procesado antes  */
            if ($this->transaccionApi->existsTransaccionIdAndProveedor("OK")) {
                /*  Si la transaccionId ha sido procesada, reportamos el error  */
                throw new Exception("Transaccion ya procesada", "10001");
            }

            $TransaccionJuego = new TransaccionJuego();
            $TransaccionJuego->setProductoId($ProductoMandante->prodmandanteId);
            $TransaccionJuego->setTransaccionId($transactionId);
            $TransaccionJuego->setTicketId("VGT" . $ticketid);
            $TransaccionJuego->setValorTicket($debitAmount);
            $TransaccionJuego->setValorPremio(0);
            $TransaccionJuego->setMandante($UsuarioMandante->mandante);
            $TransaccionJuego->setUsuarioId($UsuarioMandante->usumandanteId);
            $TransaccionJuego->setEstado("A");
            $TransaccionJuego->setUsucreaId(0);
            $TransaccionJuego->setUsumodifId(0);
            $TransaccionJuego->setPremiado('N');
            $TransaccionJuego->setFechaPago(date('Y-m-d H:i:s'));


            $Mandante = new Mandante($UsuarioMandante->mandante);

            if ($Mandante->propio == "S") {
                $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();

                $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

                if ($Transaction->isIsconnected()) {
                    $transaccion_id = $TransaccionJuego->insert($Transaction);


                    $tipoTransaccion = "DEBIT";


                    $TransjuegoLog = new TransjuegoLog();
                    $TransjuegoLog->setTransjuegoId($transaccion_id);
                    $TransjuegoLog->setTransaccionId($transactionId);
                    $TransjuegoLog->setTipo($tipoTransaccion);
                    $TransjuegoLog->setTValue(json_encode($datos));
                    $TransjuegoLog->setUsucreaId(0);
                    $TransjuegoLog->setUsumodifId(0);
                    $TransjuegoLog->setValor($debitAmount);


                    $saldoCreditos = 0;
                    $saldoCreditosBase = 0;
                    $saldoBonos = 0;
                    $saldoFree = 0;


                    $TransjuegoLog->setSaldoCreditos($saldoCreditos);
                    $TransjuegoLog->setSaldoCreditosBase($saldoCreditosBase);
                    $TransjuegoLog->setSaldoBonos($saldoBonos);
                    $TransjuegoLog->setSaldoFree($saldoFree);
                    $TransjuegoLog->setProductoId($ProductoMandante->prodmandanteId);
                    $TransjuegoLog->setProveedorId($Producto->getSubproveedorId());


                    $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);


                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $BalanceOld = $Usuario->getBalance();

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


                    $Transaction->commit();

                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $Balance = $Usuario->getBalance();

                    $currency = $Usuario->moneda;

                    $return = array(
                        "status" => 1,
                        "Balance" => $Balance
                    );

                    /*  Guardamos la Transaccion Api necesaria de estado OK   */
                    $this->transaccionApi->setRespuestaCodigo("OK");
                    $this->transaccionApi->setRespuesta(json_encode($return));
                    $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                    $TransaccionApiMySqlDAO->insert($this->transaccionApi);
                    $TransaccionApiMySqlDAO->getTransaction()->commit();

                    exec(
                        "php -f " . __DIR__ . "/../casino/VerificarTorneo.php VIRTUAL " . $this->transaccionApi->transapiId . " " . $UsuarioMandante->usuarioMandante . " > /dev/null &"
                    );

                    return ($return);
                }
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un rollback (reversión) de una transacción.
     *
     * @param string $ticketid       Identificador del ticket.
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $transactionId  Identificador de la transacción.
     * @param array  $data           Datos adicionales relacionados con la transacción.
     *
     * @return array Resultado de la operación.
     * @throws Exception Si la transacción ya fue procesada o el valor del ticket no coincide con el rollback.
     */
    public function Rollback($ticketid, $rollbackAmount, $transactionId, $data)
    {
        $datos = json_encode($data);

        try {
            /*  Obtenemos el Proveedor con el abreviado VGT */
            $Proveedor = new Proveedor("", "VGT");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $ticketid);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($rollbackAmount);


            /*  Obtenemos el Usuario Token con el UID */
            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId(), "", "");


            /*  Obtenemos el Usuario Mandante con el UID */
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId());

            $this->transaccionApi->setIdentificador("VGT" . $ticketid);

            /*  Obtenemos el producto con el gameId  */
            $Producto = new Producto($this->ProductoId, $this->ProductoId, $Proveedor->getProveedorId());

            /*  Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego  */
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            /*  Agregamos Elementos a la Transaccion API  */
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());


            /*  Verificamos que la transaccionId no se haya procesado antes  */
            if ($this->transaccionApi->existsTransaccionIdAndProveedor("OK")) {
                /*  Si la transaccionId ha sido procesada, reportamos el error  */
                throw new Exception("Transaccion ya procesada", "10001");
            }


            /*  Creamos la Transaccion por el Juego  */
            $TransaccionJuego = new TransaccionJuego("", "VGT" . $ticketid);
            $valorTransaction = $TransaccionJuego->getValorTicket();

            $rollbackAmount = $valorTransaction;

            /*  Verificamos que el valor del ticket sea igual al valor del Rollback  */
            if ($valorTransaction != $rollbackAmount) {
                // throw new Exception("Valor ticket diferente al Rollback", "10003");
            }

            /*  Obtenemos Mandante para verificar sus caracteristicas  */
            $Mandante = new Mandante($UsuarioMandante->mandante);

            /*  Verificamos si el mandante es Propio  */
            if ($Mandante->propio == "S") {
                /* Obtenemos la Transaccion de la BD para crear y registrar el Debito con esta transaccion */
                $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
                $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

                /*  Verificamos que la Transaccion si este conectada y lista para usarse  */
                if ($Transaction->isIsconnected()) {
                    /*  Actualizamos Transaccion Juego  */
                    $TransaccionJuego->setEstado("I");
                    $TransaccionJuego->setValorPremio($rollbackAmount);
                    $TransaccionJuego->update($Transaction);


                    /*  Obtenemos el Transaccion Juego ID  */
                    $TransJuegoId = $TransaccionJuego->getTransjuegoId();

                    /*  Creamos el Log de Transaccion Juego  */
                    $TransjuegoLog = new TransjuegoLog();
                    $TransjuegoLog->setTransjuegoId($TransJuegoId);
                    $TransjuegoLog->setTransaccionId("ROLLBACK" . $ticketid);
                    $TransjuegoLog->setTipo("ROLLBACK");
                    $TransjuegoLog->setTValue(json_encode($datos));
                    $TransjuegoLog->setUsucreaId(0);
                    $TransjuegoLog->setUsumodifId(0);
                    $TransjuegoLog->setValor($rollbackAmount);

                    $saldoCreditos = 0;
                    $saldoCreditosBase = 0;
                    $saldoBonos = 0;
                    $saldoFree = 0;


                    $TransjuegoLog->setSaldoCreditos($saldoCreditos);
                    $TransjuegoLog->setSaldoCreditosBase($saldoCreditosBase);
                    $TransjuegoLog->setSaldoBonos($saldoBonos);
                    $TransjuegoLog->setSaldoFree($saldoFree);
                    $TransjuegoLog->setProductoId($ProductoMandante->prodmandanteId);
                    $TransjuegoLog->setProveedorId($Producto->getSubproveedorId());

                    $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);

                    /*  Obtenemos el Usuario para hacerle el credito  */
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $BalanceOld = $Usuario->getBalance();

                    $Usuario->creditWin($TransaccionJuego->getValorTicket(), $Transaction);


                    $UsuarioHistorial = new UsuarioHistorial();
                    $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                    $UsuarioHistorial->setDescripcion('');
                    $UsuarioHistorial->setMovimiento('E');
                    $UsuarioHistorial->setUsucreaId(0);
                    $UsuarioHistorial->setUsumodifId(0);
                    $UsuarioHistorial->setTipo(30);
                    $UsuarioHistorial->setValor($TransaccionJuego->getValorTicket());
                    $UsuarioHistorial->setExternoId($TransjuegoLog_id);

                    $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                    $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);


                    $Transaction->commit();

                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                    $Balance = $Usuario->getBalance();

                    $return = array(
                        "status" => 1,
                        "Balance" => $Balance
                    );

                    /*  Guardamos la Transaccion Api necesaria de estado OK   */
                    $this->transaccionApi->setRespuestaCodigo("OK");
                    $this->transaccionApi->setRespuesta(json_encode($return));
                    $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                    $TransaccionApiMySqlDAO->insert($this->transaccionApi);
                    $TransaccionApiMySqlDAO->getTransaction()->commit();


                    return ($return);
                }
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Resuelve una transacción, acreditando el monto correspondiente.
     *
     * @param string $ticketid      Identificador del ticket.
     * @param float  $creditAmount  Monto a acreditar.
     * @param string $transactionId Identificador de la transacción.
     * @param array  $data          Datos adicionales relacionados con la transacción.
     *
     * @return array Resultado de la operación.
     * @throws Exception Si la transacción ya fue procesada.
     */
    public function Solve($ticketid, $creditAmount, $transactionId, $data)
    {
        $datos = json_encode($data);;

        try {
            /*  Obtenemos el Proveedor con el abreviado IGP */
            $Proveedor = new Proveedor("", "VGT");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId("SOLVE" . $transactionId);
            $this->transaccionApi->setTipo("SOLVE");
            $this->transaccionApi->setIdentificador("VGT" . $ticketid);
            $this->transaccionApi->setTValue(($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);

            $this->transaccionApi->setValor($creditAmount);

            $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), "", "", $this->unit_id);

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId());


            /*  Obtenemos el producto con el gameId  */
            $Producto = new Producto($this->ProductoId, $this->ProductoId, $Proveedor->getProveedorId());

            /*  Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego  */
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            /*  Agregamos Elementos a la Transaccion API  */
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());

            /*  Verificamos que la transaccionId no se haya procesado antes  */
            if ($this->transaccionApi->existsTransaccionIdAndProveedor("OK")) {
                /*  Si la transaccionId ha sido procesada, reportamos el error  */
                throw new Exception("Transaccion ya procesada", "10001");
            }

            $TransaccionJuego = new TransaccionJuego("", "VGT" . $ticketid);

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

                    //$TransaccionJuego->update($Transaction);

                    $TransJuegoId = $TransaccionJuego->getTransjuegoId();

                    $tipoTransaccion = "SOLVE";

                    $sumaCreditos = true;

                    $TransjuegoLog = new TransjuegoLog();
                    $TransjuegoLog->setTransjuegoId($TransJuegoId);
                    $TransjuegoLog->setTransaccionId("SOLVE" . $transactionId);
                    $TransjuegoLog->setTipo($tipoTransaccion);
                    $TransjuegoLog->setTValue(json_encode($datos));
                    $TransjuegoLog->setUsucreaId(0);
                    $TransjuegoLog->setUsumodifId(0);
                    $TransjuegoLog->setValor($creditAmount);

                    $saldoCreditos = 0;
                    $saldoCreditosBase = 0;
                    $saldoBonos = 0;
                    $saldoFree = 0;


                    $TransjuegoLog->setSaldoCreditos($saldoCreditos);
                    $TransjuegoLog->setSaldoCreditosBase($saldoCreditosBase);
                    $TransjuegoLog->setSaldoBonos($saldoBonos);
                    $TransjuegoLog->setSaldoFree($saldoFree);
                    $TransjuegoLog->setProductoId($ProductoMandante->prodmandanteId);
                    $TransjuegoLog->setProveedorId($Producto->getSubproveedorId());

                    // $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);

                    if ($sumaCreditos) {
                        if ($creditAmount > 0) {
                        }
                    }

                    $Transaction->commit();

                    $return = array(
                        "type" => "WalletCreditResponse",
                        "ticketId" => $transactionId,
                        "result" => "success",
                        "errorId" => 0,
                        "errorMessage" => "SUCCESS"
                    );

                    /*  Guardamos la Transaccion Api necesaria de estado OK   */
                    $this->transaccionApi->setRespuestaCodigo("OK");
                    $this->transaccionApi->setRespuesta(json_encode($return));
                    $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                    $transapi_id = $TransaccionApiMySqlDAO->insert($this->transaccionApi);
                    $TransaccionApiMySqlDAO->getTransaction()->commit();


                    return ($return);
                }
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un crédito en la cuenta del usuario.
     *
     * @param string  $ticketid      Identificador del ticket.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $transactionId Identificador de la transacción.
     * @param boolean $isEndRound    Indica si es el final de la ronda.
     * @param string  $CouponStatus  Estado del cupón.
     * @param array   $data          Datos adicionales relacionados con la transacción.
     *
     * @return array Resultado de la operación.
     * @throws Exception Si la transacción ya fue procesada.
     */
    public function Credit($ticketid, $creditAmount, $transactionId, $isEndRound, $CouponStatus, $data)
    {
        $datos = json_encode($data);;

        try {
            /*  Obtenemos el Proveedor con el abreviado IGP */
            $Proveedor = new Proveedor("", "VGT");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId("CREDIT" . $transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setIdentificador("VGT" . $ticketid);
            $this->transaccionApi->setTValue(($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);


            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId(), "", "");

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId());


            /*  Obtenemos el producto con el gameId  */
            $Producto = new Producto($this->ProductoId, $this->ProductoId, $Proveedor->getProveedorId());

            /*  Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego  */
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            /*  Agregamos Elementos a la Transaccion API  */
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());

            /*  Verificamos que la transaccionId no se haya procesado antes  */
            if ($this->transaccionApi->existsTransaccionIdAndProveedor("OK")) {
                /*  Si la transaccionId ha sido procesada, reportamos el error  */
                throw new Exception("Transaccion ya procesada", "10001");
            }

            $TransaccionJuego = new TransaccionJuego("", "VGT" . $ticketid);

            $Mandante = new Mandante($UsuarioMandante->mandante);

            if ($Mandante->propio == "S") {
                $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();

                $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

                if ($Transaction->isIsconnected()) {
                    $TransaccionJuego->setValorPremio($TransaccionJuego->getValorPremio() + $creditAmount);


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
                    $TransjuegoLog->setTransaccionId("CREDIT" . $transactionId);
                    $TransjuegoLog->setTipo($tipoTransaccion);
                    $TransjuegoLog->setTValue(json_encode($datos));
                    $TransjuegoLog->setUsucreaId(0);
                    $TransjuegoLog->setUsumodifId(0);
                    $TransjuegoLog->setValor($creditAmount);

                    $saldoCreditos = 0;
                    $saldoCreditosBase = 0;
                    $saldoBonos = 0;
                    $saldoFree = 0;


                    $TransjuegoLog->setSaldoCreditos($saldoCreditos);
                    $TransjuegoLog->setSaldoCreditosBase($saldoCreditosBase);
                    $TransjuegoLog->setSaldoBonos($saldoBonos);
                    $TransjuegoLog->setSaldoFree($saldoFree);
                    $TransjuegoLog->setProductoId($ProductoMandante->prodmandanteId);
                    $TransjuegoLog->setProveedorId($Producto->getSubproveedorId());

                    $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);

                    if ($sumaCreditos) {
                        if ($creditAmount > 0) {
                            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                            $BalanceOld = $Usuario->getBalance();

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


                    $return = array(
                        "status" => 1,
                        "Balance" => '0'
                    );

                    /*  Guardamos la Transaccion Api necesaria de estado OK   */
                    $this->transaccionApi->setRespuestaCodigo("OK");
                    $this->transaccionApi->setRespuesta(json_encode($return));
                    $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                    $TransaccionApiMySqlDAO->insert($this->transaccionApi);
                    $TransaccionApiMySqlDAO->getTransaction()->commit();


                    $Transaction->commit();

                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $Balance = $Usuario->getBalance();

                    $currency = $Usuario->moneda;

                    $return = array(
                        "status" => 1,
                        "Balance" => $Balance
                    );

                    /*  Guardamos la Transaccion Api necesaria de estado OK   */
                    $this->transaccionApi->setRespuestaCodigo("OK");
                    $this->transaccionApi->setRespuesta(json_encode($return));
                    $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                    $TransaccionApiMySqlDAO->insert($this->transaccionApi);
                    $TransaccionApiMySqlDAO->getTransaction()->commit();

                    if ($sumaCreditos) {
                        exec(
                            "php -f " . __DIR__ . "/../casino/VerificarTorneoPremio.php VIRTUAL " . $this->transaccionApi->transapiId . " " . $UsuarioMandante->usuarioMandante . " > /dev/null &"
                        );
                    }

                    return ($return);
                }
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Convierte un código de error en una respuesta estructurada.
     *
     * @param integer $code    Código de error.
     * @param string  $message Mensaje de error.
     *
     * @return array Respuesta estructurada con el error convertido.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $response = array();

        if ($this->transaccionApi != null) {
            $tipo = $this->transaccionApi->getTipo();
        }


        if ($tipo == "DEBIT" || $tipo == "CREDIT" || $tipo == "ROLLBACK") {
            $response = array_merge($response, array());
        } else {
            $response = array_merge($response, array());
        }

        /*  Obtenemos el Proveedor con el abreviado IGP */
        $Proveedor = new Proveedor("", "VGT");

        switch ($code) {
            case 10011:
                $codeProveedor = 2;
                $messageInterno = $code;
                $messageProveedor = "INVALID_SESSION";
                break;
            case 21:
                $codeProveedor = 2;
                $messageInterno = $code;
                $messageProveedor = "INVALID_SESSION";
                break;
            case 10013:
                $codeProveedor = 2;
                $messageInterno = $code;
                $messageProveedor = "INVALID_SESSION";
                break;
            case 22:
                $codeProveedor = 2;
                $messageInterno = $code;
                $messageProveedor = "INVALID_SESSION";
                break;
            case 20001:
                $codeProveedor = 3;
                $messageInterno = $code;
                $messageProveedor = "Insufficient funds";

                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId(), "", "");

                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                $Mandante = new Mandante($UsuarioMandante->mandante);

                if ($Mandante->propio == "S") {
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $saldo = $Usuario->getBalance();

                    $response = array_merge($response, array(
                        "Status" => 4,
                        "Balance" => $saldo
                    ));
                }


                break;

            case 0:
                $codeProveedor = 1;
                $messageInterno = $code;
                $messageProveedor = "General Error.";

                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId(), "", "");

                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                $Mandante = new Mandante($UsuarioMandante->mandante);

                if ($Mandante->propio == "S") {
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $saldo = $Usuario->getBalance();

                    $response = array_merge($response, array(
                        "Status" => 4,
                        "Balance" => $saldo
                    ));
                }

                break;
            case 27:
                $codeProveedor = 1;
                $messageInterno = $code;
                $messageProveedor = "General Error.";
                break;
            case 28:

                $tipo = $this->transaccionApi->getTipo();

                if ($tipo == "ROLLBACK") {
                    $codeProveedor = 1;
                    $messageInterno = $code;
                    $messageProveedor = "TICKET_NOT_FOUND";

                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId(), "", "");

                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                    $Mandante = new Mandante($UsuarioMandante->mandante);

                    if ($Mandante->propio == "S") {
                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                        $saldo = $Usuario->getBalance();

                        $response = array_merge($response, array(
                            "Status" => 4,
                            "Balance" => $saldo
                        ));
                    }
                } else {
                    $codeProveedor = 1;
                    $messageInterno = $code;
                    $messageProveedor = "General Error.";

                    $codeProveedor = 1;
                    $messageInterno = $code;
                    $messageProveedor = "TICKET_NOT_FOUND";
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId(), "", "");

                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                    $Mandante = new Mandante($UsuarioMandante->mandante);

                    if ($Mandante->propio == "S") {
                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                        $saldo = $Usuario->getBalance();

                        $response = array_merge($response, array(
                            "Status" => 4,
                            "Balance" => $saldo
                        ));
                    }
                }


                break;
            case 29:

                $tipo = $this->transaccionApi->getTipo();

                if ($tipo == "ROLLBACK") {
                    $codeProveedor = 4;
                    $messageInterno = $code;
                    $messageProveedor = "TICKET_NOT_FOUND";

                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId(), "", "");

                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                    $Mandante = new Mandante($UsuarioMandante->mandante);

                    if ($Mandante->propio == "S") {
                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                        $saldo = $Usuario->getBalance();

                        $response = array_merge($response, array(
                            "Status" => 4,
                            "Balance" => $saldo
                        ));
                    }
                } else {
                    $codeProveedor = 1;
                    $messageInterno = $code;
                    $messageProveedor = "General Error.";

                    $codeProveedor = 4;
                    $messageInterno = $code;
                    $messageProveedor = "TICKET_NOT_FOUND";

                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId(), "", "");

                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                    $Mandante = new Mandante($UsuarioMandante->mandante);

                    if ($Mandante->propio == "S") {
                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                        $saldo = $Usuario->getBalance();

                        $response = array_merge($response, array(
                            "Status" => 4,
                            "Balance" => $saldo
                        ));
                    }
                }


                break;

            case 10001:

                $tipo = $this->transaccionApi->getTipo();

                if ($tipo == "ROLLBACK") {
                    $codeProveedor = 4;
                    $messageInterno = $code;
                    $messageProveedor = "TRANSACTION_ALREADY_EXISTS";

                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId(), "", "");

                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                    $Mandante = new Mandante($UsuarioMandante->mandante);

                    if ($Mandante->propio == "S") {
                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                        $saldo = $Usuario->getBalance();

                        $response = array_merge($response, array(
                            "Status" => 4,
                            "Balance" => $saldo
                        ));
                    }
                } else {
                    /*  Obtenemos el Proveedor con el abreviado IGP */
                    $Proveedor = new Proveedor("", "VGT");
                    $codeProveedor = 4;
                    $messageInterno = $code;
                    $TransaccionApi2 = new TransaccionApi(
                        "",
                        str_replace('CREDIT', '', $this->transaccionApi->getTransaccionId()),
                        $Proveedor->getProveedorId()
                    ); //TransaccionApi Anterior DEBIT
                    $messageProveedor = "TRANSACTION_ALREADY_EXISTS";

                    // $UsuarioToken = new UsuarioToken($this->token);

                    $UsuarioMandante = new UsuarioMandante($TransaccionApi2->usuarioId);

                    $Mandante = new Mandante($UsuarioMandante->mandante);

                    if ($Mandante->propio == "S") {
                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                        $saldo = $Usuario->getBalance();

                        $response = array_merge($response, array(
                            "Status" => 4,
                            "Balance" => $saldo
                        ));
                    }
                }


                break;

            case 10004:
                $codeProveedor = 1;
                $messageInterno = $code;
                $messageProveedor = "General Error.";

                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId(), "", "");

                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                $Mandante = new Mandante($UsuarioMandante->mandante);

                if ($Mandante->propio == "S") {
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $saldo = $Usuario->getBalance();

                    $response = array_merge($response, array(
                        "Status" => 4,
                        "Balance" => $saldo
                    ));
                }

                break;
            case 10014:
                $codeProveedor = 1;
                $messageInterno = $code;
                $messageProveedor = "General Error.";

                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId(), "", "");

                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                $Mandante = new Mandante($UsuarioMandante->mandante);

                if ($Mandante->propio == "S") {
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $saldo = $Usuario->getBalance();

                    $response = array_merge($response, array(
                        "Status" => 4,
                        "Balance" => $saldo
                    ));
                }

                break;


            default:
                $codeProveedor = 1;
                $messageInterno = $code;
                $messageProveedor = "General Error.";

                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId(), "", "");

                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                $Mandante = new Mandante($UsuarioMandante->mandante);

                if ($Mandante->propio == "S") {
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $saldo = $Usuario->getBalance();

                    $response = array_merge($response, array(
                        "Status" => 4,
                        "Balance" => $saldo
                    ));
                }

                break;
        }

        if ($this->transaccionApi != null) {
            $this->transaccionApi->setTipo("R" . $this->transaccionApi->getTipo());
            $this->transaccionApi->setRespuestaCodigo("ERROR_" . $code);
            $this->transaccionApi->setRespuesta(json_encode($response));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->insert($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();
        }


        return $response;
    }


}
