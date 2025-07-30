<?php

/**
 * Esta clase maneja la integración con el proveedor Goldenrace.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-22
 */

namespace Backend\integrations\virtual;

use Backend\dto\ConfigurationEnvironment;
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
 * Clase Goldenrace
 *
 * Esta clase maneja la integración con el proveedor Goldenrace.
 * Proporciona métodos para autenticar usuarios, debitar y acreditar fondos,
 * y manejar transacciones de juego.
 */
class Goldenrace
{
    /**
     * ID del operador utilizado para identificar al usuario o entidad en la integración.
     *
     * @var string
     */
    private $unit_id;

    /**
     * ID del operador utilizado para identificar al usuario o entidad en la integración.
     *
     * @var string
     */
    private $ext_id;

    /**
     * ID del operador utilizado para identificar al usuario o entidad en la integración.
     *
     * @var string
     */
    private $isFu = false;

    /**
     * ID del operador utilizado para identificar al usuario o entidad en la integración.
     *
     * @var string
     */
    private $transaccionApi;

    /**
     * ID del operador utilizado para identificar al usuario o entidad en la integración.
     *
     * @var string
     */
    private $productDEV = "4368";

    /**
     * ID del operador utilizado para identificar al usuario o entidad en la integración.
     *
     * @var string
     */
    private $product = "427";

    /**
     * ID del operador utilizado para identificar al usuario o entidad en la integración.
     *
     * @var string
     */
    private $tipo = "";

    /**
     * Constructor de la clase Goldenrace.
     *
     * Inicializa los valores de `ext_id` y `unit_id` y configura el producto
     * dependiendo del entorno de desarrollo.
     *
     * @param string $ext_id  Identificador externo del operador.
     * @param string $unit_id Identificador de la unidad del operador.
     */
    public function __construct($ext_id, $unit_id)
    {
        $this->ext_id = $ext_id;
        $this->unit_id = $unit_id;

        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->product = $this->productDEV;
        }
    }

    /**
     * Metodo para obtener el ID del operador.
     *
     * @return string El ID del operador.
     */
    public function getOperadorId()
    {
        return $this->operadorId;
    }


    /**
     * Metodo para autenticar al usuario.
     *
     * Este metodo verifica si el usuario está en modo "FUN" o no, y realiza las siguientes acciones:
     * - Si está en modo "FUN", valida el token y obtiene el balance del usuario.
     * - Si no está en modo "FUN", valida el `unit_id` y obtiene el balance del usuario junto con el token externo.
     *
     * @return array Retorna un arreglo con la información del balance, moneda y otros datos relevantes.
     * @throws Exception Si el token o el `unit_id` están vacíos, o si ocurre algún error durante el proceso.
     */
    public function Auth()
    {
        try {
            if ($this->isFun) {
                if ($this->token == "") {
                    throw new Exception("Token vacio", "01");
                }
                /*  Obtenemos el Proveedor con el abreviado IGP */
                $Proveedor = new Proveedor("", "GDR");

                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                $Mandante = new Mandante($UsuarioMandante->mandante);

                if ($Mandante->propio == "S") {
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $Balance = round($Usuario->getBalance(), 2);

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
                $Proveedor = new Proveedor("", "GDR");

                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), "", "", $this->unit_id);

                $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId());

                $Mandante = new Mandante($UsuarioMandante->mandante);

                if ($Mandante->propio == "S") {
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $Balance = round($Usuario->getBalance(), 2);

                    $return = array(
                        "credit" => $Balance,
                        "currencyCode" => $Usuario->moneda,
                        "extToken" => $UsuarioToken->getToken()
                        /* ,"login_hash" => "",
                         "result" => "success",
                         "ext_id" => $this->ext_id*/

                    );
                    return ($return);
                }
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }


    /**
     * Metodo DebitSpend
     *
     * Este metodo realiza un débito en la cuenta del usuario para una transacción específica.
     *
     * Pasos principales:
     * - Valida que el monto a debitar no sea negativo.
     * - Crea una transacción API y la asocia con el proveedor y el producto.
     * - Verifica que la transacción no haya sido procesada previamente.
     * - Realiza el débito en la cuenta del usuario y actualiza el balance.
     * - Guarda la transacción en la base de datos y retorna el resultado.
     *
     * @param float  $debitAmount   Monto a debitar.
     * @param string $transactionId Identificador único de la transacción.
     * @param array  $data          Datos adicionales relacionados con la transacción.
     *
     * @return array Resultado de la operación, incluyendo el balance anterior y el nuevo balance.
     * @throws Exception Si el monto es negativo o si la transacción ya fue procesada.
     */
    public function DebitSpend($debitAmount, $transactionId, $data)
    {
        $this->tipo = 'SellResponse';

        $datos = json_encode($data);

        try {
            /* Obtenemos el Proveedor con el abreviado IGP */
            $Proveedor = new Proveedor("", "GDR");

            /* Creamos la Transacción API */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId("DS" . $transactionId);
            $this->transaccionApi->setTipo("DEBITSPEND");
            $this->transaccionApi->setIdentificador("GDR" . $transactionId);
            $this->transaccionApi->setTValue(($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);

            if ($debitAmount < 0) {
                throw new Exception("No puede ser negativo el monto a debitar.", "0");
            }

            $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), "", "", $this->unit_id);
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId());

            /* Obtenemos el producto con el gameId */
            $Producto = new Producto($this->product, $this->product, $Proveedor->getProveedorId());

            /* Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego */
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            /* Agregamos Elementos a la Transacción API */
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());

            /* Verificamos que la transacción no se haya procesado antes */
            if ($this->transaccionApi->existsTransaccionIdAndProveedor("OK")) {
                throw new Exception("Transacción ya procesada", "10001");
            }

            $ticketid = '';
            $TransaccionJuego = new TransaccionJuego();
            $TransaccionJuego->setProductoId($ProductoMandante->prodmandanteId);
            $TransaccionJuego->setTransaccionId($transactionId);
            $TransaccionJuego->setTicketId("GDR" . $ticketid);
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

                    /* Guardamos la Transacción API necesaria de estado OK */
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
     * Metodo Debit
     *
     * Este metodo realiza un débito en la cuenta del usuario para una transacción específica.
     *
     * Pasos principales:
     * - Valida que el monto a debitar no sea negativo.
     * - Crea una transacción API y la asocia con el proveedor y el producto.
     * - Verifica que la transacción no haya sido procesada previamente.
     * - Realiza el débito en la cuenta del usuario y actualiza el balance.
     * - Guarda la transacción en la base de datos y retorna el resultado.
     *
     * @param string $ticketid      Identificador del ticket.
     * @param float  $debitAmount   Monto a debitar.
     * @param string $transactionId Identificador único de la transacción.
     * @param array  $data          Datos adicionales relacionados con la transacción.
     *
     * @return array Resultado de la operación, incluyendo el balance anterior y el nuevo balance.
     * @throws Exception Si el monto es negativo o si la transacción ya fue procesada.
     */
    public function Debit($ticketid, $debitAmount, $transactionId, $data)
    {
        $this->tipo = 'SellResponse';

        $datos = json_encode($data);


        try {
            /*  Obtenemos el Proveedor con el abreviado IGP */
            $Proveedor = new Proveedor("", "GDR");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setIdentificador("GDR" . $ticketid);
            $this->transaccionApi->setTValue(($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);

            if ($debitAmount < 0) {
                throw new Exception("No puede ser negativo el monto a debitar.", "0");
            }

            $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), "", "", $this->unit_id);

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId());

            /* Obtenemos el producto con el gameId */
            $Producto = new Producto($this->product, $this->product, $Proveedor->getProveedorId());

            /* Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego */
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            /* Agregamos Elementos a la Transacción API */
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());

            /* Creamos la Transacción API */
            $SpendtransaccionApi = new TransaccionApi();
            $SpendtransaccionApi->setTransaccionId("DS" . $transactionId);
            $SpendtransaccionApi->setTipo("DEBIT");
            $SpendtransaccionApi->setIdentificador("GDR" . $ticketid);
            $SpendtransaccionApi->setTValue(($datos));
            $SpendtransaccionApi->setUsucreaId(0);
            $SpendtransaccionApi->setUsumodifId(0);

            if ($this->transaccionApi->existsTransaccionIdAndProveedor("OK")) {
                /* Si la transacción ya fue procesada, reportamos el error */
                throw new Exception("Transaccion ya procesada", "10001");
            }

            $TransaccionJuego = new TransaccionJuego();
            $TransaccionJuego->setProductoId($ProductoMandante->prodmandanteId);
            $TransaccionJuego->setTransaccionId($transactionId);
            $TransaccionJuego->setTicketId("GDR" . $ticketid);
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
                        "type" => "SellResponse",
                        "result" => "success",
                        "errorId" => 0,
                        "errorMessage" => "SUCCESS",
                        "ticketId" => $transactionId,
                        "oldCredit" => $BalanceOld,
                        "newCredit" => $Balance,
                        //"extTicketId" => $transaccion_id,
                        "extTransactionID" => $transaccion_id
                    );

                    /* Guardamos la Transacción API con estado OK */
                    $this->transaccionApi->setRespuestaCodigo("OK");
                    $this->transaccionApi->setRespuesta(json_encode($return));
                    $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                    $TransaccionApiMySqlDAO->insert($this->transaccionApi);
                    $TransaccionApiMySqlDAO->getTransaction()->commit();

                    exec(
                        "php -f " . __DIR__ . "/../casino/VerificarTorneo.php VIRTUAL " . $this->transaccionApi->transapiId . " " . $UsuarioMandante->usuarioMandante . " > /dev/null &"
                    );
                    exec(
                        "php -f " . __DIR__ . "/../casino/AgregarValorJackpot.php VIRTUAL " . $TransjuegoLog_id . " > /dev/null &"
                    );

                    return ($return);
                }
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Metodo Rollback
     *
     * Este metodo realiza un rollback (reversión) de una transacción específica.
     *
     * Pasos principales:
     * - Verifica que el `unit_id` no esté vacío.
     * - Obtiene el token del usuario y el mandante asociado.
     * - Valida que el valor del ticket coincida con el monto del rollback.
     * - Actualiza el estado de la transacción de juego y registra un log de la transacción.
     * - Realiza el crédito correspondiente al usuario y actualiza su balance.
     * - Guarda la transacción en la base de datos y retorna el resultado.
     *
     * @param string $ticketid       Identificador del ticket.
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $transactionId  Identificador único de la transacción.
     * @param array  $data           Datos adicionales relacionados con la transacción.
     *
     * @return array Resultado de la operación, incluyendo el balance anterior y el nuevo balance.
     * @throws Exception Si el `unit_id` está vacío, si el valor del ticket no coincide con el rollback,
     *                   o si la transacción ya fue procesada.
     */
    public function Rollback($ticketid, $rollbackAmount, $transactionId, $data)
    {
        $this->tipo = 'WalletCreditResponse';

        $datos = json_encode($data);

        try {
            /*  Obtenemos el Proveedor con el abreviado EZZG */
            $Proveedor = new Proveedor("", "GDR");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($rollbackAmount);

            /*  Verificamos que el uid no sea vacio */
            if ($this->unit_id == "") {
                throw new Exception("Unit ID vacio", "10013");
            }

            /*  Obtenemos el Usuario Token con el UID */
            $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), "", "", $this->unit_id);

            /*  Obtenemos el Usuario Mandante con el UID */
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId());

            $this->transaccionApi->setIdentificador("GDR" . $ticketid);

            /*  Obtenemos el producto con el gameId  */
            $Producto = new Producto($this->product, $this->product, $Proveedor->getProveedorId());

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
            $TransaccionJuego = new TransaccionJuego("", "GDR" . $ticketid);
            $valorTransaction = $TransaccionJuego->getValorTicket();

            /*  Verificamos que el valor del ticket sea igual al valor del Rollback  */
            if ($valorTransaction != $rollbackAmount) {
                throw new Exception("Valor ticket diferente al Rollback", "10003");
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
                    $TransaccionJuego->update($Transaction);

                    /*  Obtenemos el Transaccion Juego ID  */
                    $TransJuegoId = $TransaccionJuego->getTransjuegoId();

                    /*  Creamos el Log de Transaccion Juego  */
                    $TransjuegoLog = new TransjuegoLog();
                    $TransjuegoLog->setTransjuegoId($TransJuegoId);
                    $TransjuegoLog->setTransaccionId("ROLLBACK" . $transactionId);
                    $TransjuegoLog->setTipo("ROLLBACK");
                    $TransjuegoLog->setTValue(json_encode($datos));
                    $TransjuegoLog->setUsucreaId(0);
                    $TransjuegoLog->setUsumodifId(0);
                    $TransjuegoLog->setValor($rollbackAmount);

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
                        "type" => "WalletCreditResponse",
                        "result" => "success",
                        "errorId" => 0,
                        "errorMessage" => "SUCCESS",
                        "ticketId" => $transactionId,
                        "oldCredit" => $BalanceOld,
                        "newCredit" => $Balance,
                        "extTransactionID" => ""
                    );

                    /*  Guardamos la Transaccion Api necesaria de estado OK   */
                    $this->transaccionApi->setRespuestaCodigo("OK");
                    $this->transaccionApi->setRespuesta(json_encode($return));
                    $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                    $Transapi_id = $TransaccionApiMySqlDAO->insert($this->transaccionApi);
                    $TransaccionApiMySqlDAO->getTransaction()->commit();

                    $return = array(
                        "type" => "WalletCreditResponse",
                        "result" => "success",
                        "errorId" => 0,
                        "errorMessage" => "SUCCESS",
                        "ticketId" => $transactionId,
                        "oldCredit" => $BalanceOld,
                        "newCredit" => $Balance,
                        "extTransactionID" => $Transapi_id
                    );

                    return ($return);
                }
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Metodo Solve
     *
     * Este metodo maneja la resolución de una transacción específica, acreditando un monto al usuario.
     *
     * Pasos principales:
     * - Crea una transacción API y la asocia con el proveedor, producto y usuario.
     * - Verifica que la transacción no haya sido procesada previamente.
     * - Actualiza el valor del premio en la transacción de juego.
     * - Si es el final de la ronda, marca la transacción como premiada y actualiza su estado.
     * - Guarda la transacción en la base de datos y retorna el resultado.
     *
     * @param string $ticketid      Identificador del ticket.
     * @param float  $creditAmount  Monto a acreditar.
     * @param string $transactionId Identificador único de la transacción.
     * @param array  $data          Datos adicionales relacionados con la transacción.
     *
     * @return array Resultado de la operación, incluyendo el estado y detalles de la transacción.
     * @throws Exception Si la transacción ya fue procesada o si ocurre algún error durante el proceso.
     */
    public function Solve($ticketid, $creditAmount, $transactionId, $data)
    {
        $this->tipo = 'WalletResponse';

        $datos = json_encode($data);

        try {
            /*  Obtenemos el Proveedor con el abreviado IGP */
            $Proveedor = new Proveedor("", "GDR");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId("SOLVE" . $transactionId);
            $this->transaccionApi->setTipo("SOLVE");
            $this->transaccionApi->setIdentificador("GDR" . $ticketid);
            $this->transaccionApi->setTValue(($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);

            $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), "", "", $this->unit_id);

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId());

            /*  Obtenemos el producto con el gameId  */
            $Producto = new Producto($this->product, $this->product, $Proveedor->getProveedorId());

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

            $TransaccionJuego = new TransaccionJuego("", "GDR" . $ticketid);

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

                    // $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);

                    if ($sumaCreditos) {
                        if ($creditAmount > 0) {
                            // $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                            // $BalanceOld = $Usuario->getBalance();

                            //$Usuario->credit($creditAmount, $Transaction);

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
     * Metodo Credit
     *
     * Este metodo acredita un monto en la cuenta del usuario para una transacción específica.
     *
     * Pasos principales:
     * - Crea una transacción API y la asocia con el proveedor, producto y usuario.
     * - Verifica que la transacción no haya sido procesada previamente.
     * - Actualiza el valor del premio en la transacción de juego.
     * - Si es el final de la ronda, marca la transacción como premiada y actualiza su estado.
     * - Guarda la transacción en la base de datos y retorna el resultado.
     *
     * @param string $ticketid      Identificador del ticket.
     * @param float  $creditAmount  Monto a acreditar.
     * @param string $transactionId Identificador único de la transacción.
     * @param array  $data          Datos adicionales relacionados con la transacción.
     *
     * @return array Resultado de la operación, incluyendo el balance anterior y el nuevo balance.
     * @throws Exception Si la transacción ya fue procesada o si ocurre algún error durante el proceso.
     */
    public function Credit($ticketid, $creditAmount, $transactionId, $data)
    {
        $this->tipo = 'WalletCreditResponse';

        $datos = json_encode($data);

        try {
            /*  Obtenemos el Proveedor con el abreviado IGP */
            $Proveedor = new Proveedor("", "GDR");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId("CREDIT" . $transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setIdentificador("GDR" . $ticketid);
            $this->transaccionApi->setTValue(($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);

            $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), "", "", $this->unit_id);

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId());

            /*  Obtenemos el producto con el gameId  */
            $Producto = new Producto($this->product, $this->product, $Proveedor->getProveedorId());

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

            $TransaccionJuego = new TransaccionJuego("", "GDR" . $ticketid);

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
                    $TransjuegoLog->setTransaccionId("CREDIT" . $transactionId);
                    $TransjuegoLog->setTipo($tipoTransaccion);
                    $TransjuegoLog->setTValue(json_encode($datos));
                    $TransjuegoLog->setUsucreaId(0);
                    $TransjuegoLog->setUsumodifId(0);
                    $TransjuegoLog->setValor($creditAmount);

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

                    $Transaction->commit();

                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $Balance = $Usuario->getBalance();

                    $currency = $Usuario->moneda;

                    $return = array(
                        "type" => "WalletCreditResponse",
                        "result" => "success",
                        "errorId" => 0,
                        "errorMessage" => "SUCCESS",
                        "ticketId" => $transactionId,
                        "oldCredit" => $BalanceOld,
                        "newCredit" => $Balance,
                        "extTransactionID" => ""
                    );

                    /*  Guardamos la Transaccion Api necesaria de estado OK   */
                    $this->transaccionApi->setRespuestaCodigo("OK");
                    $this->transaccionApi->setRespuesta(json_encode($return));
                    $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                    $transapi_id = $TransaccionApiMySqlDAO->insert($this->transaccionApi);
                    $TransaccionApiMySqlDAO->getTransaction()->commit();

                    $return = array(
                        "type" => "WalletCreditResponse",
                        "result" => "success",
                        "errorId" => 0,
                        "errorMessage" => "SUCCESS",
                        "ticketId" => $transactionId,
                        "oldCredit" => $BalanceOld,
                        "newCredit" => $Balance,
                        "extTransactionID" => $transapi_id
                    );

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
     * Metodo convertError
     *
     * Este metodo convierte un código de error y un mensaje en una respuesta estructurada.
     * También registra la información del error en la base de datos si es necesario.
     *
     * Pasos principales:
     * - Determina el tipo de transacción y el identificador del ticket.
     * - Genera una respuesta basada en el código de error proporcionado.
     * - Registra la transacción API con el estado de error en la base de datos.
     *
     * @param int    $code    Código de error.
     * @param string $message Mensaje de error.
     *
     * @return array Respuesta estructurada con información del error.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $ticketid = 0;

        if ($this->transaccionApi->getIdentificador() != "") {
            $ticketid = intval(explode("GDR", $this->transaccionApi->getIdentificador())[1]);
        }

        $response = array(
            "type" => $this->tipo,
            "ticketId" => $ticketid,
            "result" => "error"
        );

        if ($this->transaccionApi != null) {
            $tipo = $this->transaccionApi->getTipo();
        }

        if ($tipo == "DEBIT" || $tipo == "CREDIT" || $tipo == "ROLLBACK") {
            $response = array_merge($response, array());
        } else {
            $response = array_merge($response, array());
        }

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
                $messageProveedor = "NOT_ENOUGH_CREDIT";

                $response["type"] = "SellResponse";

                break;

            case 0:
                $codeProveedor = 1;
                $messageInterno = $code;
                $messageProveedor = "General Error.";
                break;
            case 27:
                $codeProveedor = 1;
                $messageInterno = $code;
                $messageProveedor = "General Error.";
                break;
            case 28:
                $tipo = $this->transaccionApi->getTipo();

                if ($tipo == "ROLLBACK") {
                    $codeProveedor = 4;
                    $messageInterno = $code;
                    $messageProveedor = "TICKET_NOT_FOUND";
                } else {
                    $codeProveedor = 1;
                    $messageInterno = $code;
                    $messageProveedor = "General Error.";

                    $codeProveedor = 4;
                    $messageInterno = $code;
                    $messageProveedor = "TICKET_NOT_FOUND";
                }
                break;
            case 29:
                $tipo = $this->transaccionApi->getTipo();

                if ($tipo == "ROLLBACK") {
                    $codeProveedor = 4;
                    $messageInterno = $code;
                    $messageProveedor = "TICKET_NOT_FOUND";
                } else {
                    $codeProveedor = 1;
                    $messageInterno = $code;
                    $messageProveedor = "General Error.";

                    $codeProveedor = 4;
                    $messageInterno = $code;
                    $messageProveedor = "TICKET_NOT_FOUND";
                }
                break;

            case 10001:
                $tipo = $this->transaccionApi->getTipo();

                if ($tipo == "ROLLBACK") {
                    $codeProveedor = 0;
                    $messageInterno = $code;
                    $messageProveedor = "TRANSACTION_ALREADY_EXISTS";
                } else {
                    $codeProveedor = 0;
                    $messageInterno = $code;
                    $messageProveedor = "TRANSACTION_ALREADY_EXISTS";

                    $Proveedor = new Proveedor("", "GDR");

                    $TransaccionApi = new TransaccionApi(
                        "",
                        $this->transaccionApi->getTransaccionId(),
                        $Proveedor->getProveedorId(),
                        ""
                    );

                    $UsuarioMandante = new UsuarioMandante($TransaccionApi->getUsuarioId());
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $Balance = $Usuario->getBalance();

                    $response["errorMessage"] = $TransaccionApi->getTransapiId();
                    $response["extTransactionID"] = $TransaccionApi->getTransapiId();
                    $response["oldCredit"] = $Balance;
                    $response["newCredit"] = $Balance;
                    $response["result"] = "success";

                    $codeProveedor = 0;
                    $messageProveedor = "SUCCESS";
                }
                break;

            case 10004:
                $codeProveedor = 1;
                $messageInterno = $code;
                $messageProveedor = "General Error.";
                break;
            case 10014:
                $codeProveedor = 1;
                $messageInterno = $code;
                $messageProveedor = "General Error.";
                break;

            default:
                $codeProveedor = 1;
                $messageInterno = $code;
                $messageProveedor = "General Error.";
                break;
        }

        $respuesta = (array_merge($response, array(
            "errorId" => $codeProveedor,
            "errorMessage" => $messageProveedor
        )));

        if ($this->transaccionApi != null) {
            $this->transaccionApi->setTipo("R" . $this->transaccionApi->getTipo());
            $this->transaccionApi->setRespuestaCodigo("ERROR");
            $this->transaccionApi->setRespuesta(json_encode($respuesta));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->insert($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();
        }

        return $respuesta;
    }

}