<?php

/**
 * Clase Fazi para la integración con el proveedor FAZI en un sistema de casino.
 *
 *  Esta clase contiene métodos para manejar transacciones de débito, crédito, rollback, autenticación y balance
 *  en el contexto de un sistema de casino. Se conecta con el proveedor FAZI y realiza operaciones relacionadas
 *  con usuarios, productos y transacciones.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
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
use Backend\dto\UsuarioHistorial;
use Backend\mysql\TransaccionApiMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;

/**
 * Clase Fazi
 *
 * Esta clase representa la integración con el proveedor FAZI en un sistema de casino.
 * Proporciona métodos para manejar transacciones como débito, crédito, rollback, autenticación y balance.
 * También incluye funcionalidades para interactuar con usuarios, productos y transacciones.
 */
class Fazi
{
    /**
     * Token de autenticación.
     *
     * @var string
     */
    private $token;

    /**
     * ID del usuario.
     *
     * @var integer
     */
    private $uid;

    /**
     * Firma de seguridad.
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
     * Datos de la transacción.
     *
     * @var array
     */
    private $data;

    /**
     * Identificador del round en caso de superposición.
     *
     * @var string
     */
    private $roundIdSuper;

    /**
     * Constructor de la clase Fazi.
     *
     * @param string $token Token de autenticación.
     * @param string $sign  Firma de seguridad.
     */
    public function __construct($token, $sign)
    {
        $responseEnable = file_get_contents(__DIR__ . '/../../../../logSit/enabled');

        if ($responseEnable == 'BLOCKED') {
            http_response_code(408);
            exit();
        }

        $this->token = $token;
        $this->sign = $sign;
    }

    /**
     * Método para autenticar al usuario con el proveedor FAZI.
     *
     * @return string JSON con los datos del usuario autenticado.
     * @throws Exception Si ocurre un error durante la autenticación.
     */
    public function Auth()
    {
        try {
            $Proveedor = new Proveedor("", "FAZI");

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

            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);
            $responseGSaldo = $Game->getBalance($UsuarioMandante);

            $Balance = intval($responseGSaldo->saldo * 100);

            $return = array(
                "player" => "Usuario" . $UsuarioMandante->usumandanteId,
                "balance" => $Balance,
                "currency" => $responseG->moneda
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para obtener el balance del usuario.
     *
     * @return string JSON con el balance del usuario.
     * @throws Exception Si ocurre un error al obtener el balance.
     */
    public function getBalance()
    {
        try {
            $Proveedor = new Proveedor("", "FAZI");

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
            $Mandante = new Mandante($UsuarioMandante->mandante);

            if ($Mandante->propio == "S") {
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                $Balance = intval($Usuario->getBalance() * 100);

                if ($Usuario->getBalance() <= 0.2) {
                    $Balance = 1;
                    $Balance = intval($Balance * 100);
                }

                $return = array(
                    "balance" => $Balance
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
     * @param integer $gameId        ID del juego.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param array   $datos         Datos adicionales de la transacción.
     *
     * @return string JSON con el resultado de la transacción.
     * @throws Exception Si ocurre un error durante la transacción.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos)
    {
        $this->data = $datos;

        try {
            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado FAZI */
            $Proveedor = new Proveedor("", "FAZI");

            /*  Obtenemos el Usuario Token con el token */
            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
            /*  Obtenemos el Usuario Mandante con el Usuario Token */
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            /*  Obtenemos el producto con el gameId  */
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            // Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);
            $this->transaccionApi->setIdentificador($roundId . "FAZI");

            $Game = new Game();
            $isfreeSpin = false;
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $isfreeSpin);

            $this->transaccionApi = $responseG->transaccionApi;

            $saldo = intval($responseG->saldo * 100);

            $respuesta = json_encode(array(
                "transactionId" => $responseG->transaccionId,
                "balance" => $saldo
            ));

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $respuesta;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para realizar una transacción de débito con validaciones adicionales.
     *
     * Este método permite realizar una transacción de débito para un usuario en el sistema,
     * verificando previamente que el monto sea positivo, que la transacción no haya sido procesada
     * anteriormente y que no exista un rollback previo asociado a la transacción.
     *
     * @param integer $gameId        ID del juego.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param array   $datos         Datos adicionales de la transacción.
     *
     * @return string JSON con el resultado de la transacción.
     * @throws Exception Si ocurre un error durante la transacción.
     */
    public function Debit2($gameId, $debitAmount, $roundId, $transactionId, $datos)
    {
        $this->data = $datos;

        try {
            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            // Obtenemos el Proveedor con el abreviado FAZI
            $Proveedor = new Proveedor("", "FAZI");

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
                throw new Exception("No puede ser negativo el monto a debitar.", "1002");
            }

            // Obtenemos el Usuario Token con el token
            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            // Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $this->transaccionApi->setIdentificador($roundId . "FAZI");

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
                throw new Exception("Transaccion ya procesada", "1001");
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

            $DebitConRollbackAntes = false;
            // Verificamos que la transaccionId no se haya procesado antes
            if ($TransaccionApiRollback->existsTransaccionIdAndProveedor("ERROR")) {
                // Si la transaccionId tiene un Rollback antes, reportamos el error
                $DebitConRollbackAntes = true;
                throw new Exception("Transaccion con Rollback antes", "1004");
            }

            // Creamos la Transaccion por el Juego
            $TransaccionJuego = new TransaccionJuego();
            $TransaccionJuego->setProductoId($ProductoMandante->prodmandanteId);
            $TransaccionJuego->setTransaccionId($transactionId);
            $TransaccionJuego->setTicketId($roundId . "FAZI");
            $TransaccionJuego->setValorTicket($debitAmount);
            $TransaccionJuego->setValorPremio(0);
            $TransaccionJuego->setMandante($UsuarioMandante->mandante);
            $TransaccionJuego->setUsuarioId($UsuarioMandante->usumandanteId);
            $TransaccionJuego->setEstado("A");
            $TransaccionJuego->setUsucreaId(0);
            $TransaccionJuego->setUsumodifId(0);
            $TransaccionJuego->setPremiado("N");
            $TransaccionJuego->setFechaPago(date('Y-m-d H:i:s'));

            $ExisteTicket = false;

            // Verificamos si existe el ticket_id antes, de ser positivo, tendriamos que combinar las apuestas
            if ($TransaccionJuego->existsTicketId()) {
                $this->roundIdSuper = $roundId . "FAZI";
                throw new Exception("Transaccion Juego Existe", "10010");
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
                        $TransaccionJuego = new TransaccionJuego("", $roundId . "FAZI", "");

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
                    $TransjuegoLog->setValor($debitAmount);
                    $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);

                    if ( ! $DebitConRollbackAntes) {
                        // Obtenemos nuestro Usuario y hacemos el debito
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
                    }

                    //Commit de la transacción
                    $Transaction->commit();

                    // Consultamos de nuevo el usuario para obtener el saldo
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                    $Balance = intval($Usuario->getBalance() * 100);

                    // Guardamos la Transaccion Api necesaria de estado OK
                    $this->transaccionApi->setRespuestaCodigo("OK");
                    $this->transaccionApi->setRespuesta(json_encode(array("balance" => $Balance)));
                    $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                    $TransaccionApiMySqlDAO->insert($this->transaccionApi);
                    $TransaccionApiMySqlDAO->getTransaction()->commit();

                    $return = array(
                        "balance" => $Balance,
                        "transactionId" => $this->transaccionApi->transapiId
                    );

                    $free = false;
                    if ( ! $free) {
                    }

                    return json_encode($return);
                }
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un rollback de una transacción de débito.
     *
     * Este método permite revertir una transacción de débito previamente realizada.
     * Valida que la transacción exista y que sea del tipo "DEBIT". Si la transacción
     * es válida, se comunica con el proveedor para realizar el rollback y actualiza
     * la información en la base de datos.
     *
     * @param integer $gameId         ID del juego asociado a la transacción.
     * @param float   $rollbackAmount Monto a revertir.
     * @param string  $roundId        ID de la ronda asociada.
     * @param string  $transactionId  ID de la transacción a revertir.
     * @param string  $player         Identificador del jugador.
     * @param array   $datos          Datos adicionales de la transacción.
     *
     * @return string JSON con el balance actualizado y el ID de la transacción.
     * @throws Exception Si ocurre un error durante el proceso de rollback.
     */
    public function Rollback($gameId, $rollbackAmount, $roundId, $transactionId, $player, $datos)
    {
        $this->data = $datos;

        try {
            /*  Obtenemos el Proveedor con el abreviado FAZI */
            $Proveedor = new Proveedor("", "FAZI");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($rollbackAmount);

            $transaccionNoExiste = false;

            try {
                $TransaccionApi2 = new TransaccionApi("", $transactionId, $Proveedor->getProveedorId());
                $jsonValue = json_decode($TransaccionApi2->getTValue());
                $valorTransaction = 0;

                $UsuarioMandante = new UsuarioMandante($TransaccionApi2->getUsuarioId());
                if (strpos($TransaccionApi2->getTipo(), 'DEBIT') !== false) {
                    $this->transaccionApi->setIdentificador($TransaccionApi2->getIdentificador());
                } else {
                    throw new Exception("Transaccion no es Debit", "1006");
                }
            } catch (Exception $e) {
                $transaccionNoExiste = true;
                throw new Exception("Transaccion no existe", "10005");
            }

            $Game = new Game();
            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi);

            $this->transaccionApi = $responseG->transaccionApi;
            $saldo = intval($responseG->saldo * 100);

            $respuesta = json_encode(array(
                "balance" => $saldo,
                "transactionId" => $responseG->transaccionId
            ));

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $respuesta;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para realizar una transacción de crédito.
     *
     * Este método permite acreditar un monto a un usuario en el sistema.
     * Valida que la transacción no haya sido procesada previamente y actualiza
     * la información en la base de datos. También registra un log de la transacción
     * y actualiza el saldo del usuario.
     *
     * @param integer $gameId        ID del juego asociado a la transacción.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       ID de la ronda asociada.
     * @param string  $transactionId ID de la transacción.
     * @param array   $datos         Datos adicionales de la transacción.
     *
     * @return string JSON con el balance actualizado y el ID de la transacción.
     * @throws Exception Si ocurre un error durante el proceso de crédito.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos)
    {
        $this->data = $datos;

        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try {
            // Obtenemos el Proveedor con el abreviado FAZI
            $Proveedor = new Proveedor("", "FAZI");

            // Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);

            /*  Obtenemos el Usuario Token con el token */
            try {
                // Obtenemos el Usuario Token con el token
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

                //  Obtenemos el Usuario Mandante con el Usuario Token
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } catch (Exception $e) {
                if ($e->getCode() == 21) {
                    $TransaccionJuego = new TransaccionJuego("", $roundId . "FAZI", $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                }
            }

            $this->transaccionApi->setIdentificador($roundId . "FAZI");

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
                throw new Exception("Transaccion ya procesada", "1001");
            }

            //  Obtenemos la Transaccion Juego
            $TransaccionJuego = new TransaccionJuego("", $roundId . "FAZI");

            //  Obtenemos el mandante para verificar sus caracteristicas
            $Mandante = new Mandante($UsuarioMandante->mandante);

            //  Verificamos si el mandante es propio
            if ($Mandante->propio == "S") {
                // Obtenemos la Transaccion de la BD para crear y registrar el Debito con esta transaccion
                $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
                $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

                //  Verificamos que la Transaccion si este conectada y lista para usarse
                if ($Transaction->isIsconnected()) {
                    //  Obtenemos el ID de la TransaccionJuego
                    $TransJuegoId = $TransaccionJuego->getTransjuegoId();

                    //  Obtenemos el tipo de transacción dependiendo a betTypeID y si se le suma los creditos o no
                    $sumaCreditos = true;
                    $tipoTransaccion = "CREDIT";

                    //  Creamos el respectivo Log de la transaccion Juego
                    $TransjuegoLog = new TransjuegoLog();
                    $TransjuegoLog->setTransjuegoId($TransJuegoId);
                    $TransjuegoLog->setTransaccionId($transactionId);
                    $TransjuegoLog->setTipo($tipoTransaccion);
                    $TransjuegoLog->setTValue(json_encode($datos));
                    $TransjuegoLog->setUsucreaId(0);
                    $TransjuegoLog->setUsumodifId(0);
                    $TransjuegoLog->setValor($creditAmount);

                    $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);

                    //  Actualizamos la Transaccion Juego con los respectivas actualizaciones
                    $TransaccionJuego->setValorPremio($TransaccionJuego->getValorPremio() + $creditAmount);

                    if ($TransaccionJuego->getValorPremio() > 0) {
                        $TransaccionJuego->setPremiado("S");
                        $TransaccionJuego->setFechaPago(date('Y-m-d H:i:s'));
                    }

                    $TransaccionJuego->setEstado("I");

                    $TransaccionJuego->update($Transaction);

                    //  Si suma los creditos, hacemos el respectivo CREDIT
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

                    // Commit de la transacción
                    $Transaction->commit();

                    // Consultamos de nuevo el usuario para obtener el saldo
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                    $Balance = intval($Usuario->getBalance() * 100);

                    //  Guardamos la Transaccion Api necesaria de estado OK
                    $this->transaccionApi->setRespuestaCodigo("OK");
                    $this->transaccionApi->setRespuesta(json_encode(array("balance" => $Balance)));
                    $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                    $TransaccionApiMySqlDAO->insert($this->transaccionApi);
                    $TransaccionApiMySqlDAO->getTransaction()->commit();

                    if ($sumaCreditos) {
                        $promolog = $Usuario->verificarBono("casino", $ProductoMandante->prodmandanteId, $TransaccionJuego->getValorTicket());
                    }

                    if ($roundId == 0) {
                        $return = array(
                            "balance" => $Balance,
                            "transactionId" => "0"
                        );
                    } else {
                        $return = array(
                            "balance" => $Balance,
                            "transactionId" => $this->transaccionApi->transapiId
                        );
                    }

                    if ($sumaCreditos) {
                        exec("php -f " . __DIR__ . "/VerificarTorneoPremio.php CASINO " . $this->transaccionApi->transapiId . " " . $UsuarioMandante->usuarioMandante . " > /dev/null &");
                    }

                    return json_encode($return);
                }
            }
        } catch (Exception $e) {
            if ($_ENV['debug']) {
                print_r($e);
            }

            if (strpos($e->getMessage(), 'INSERT INTO casino_transprovisional') !== false) {
                //  Si la transaccionId ha sido procesada, reportamos el error
                return $this->convertError("10001", 'Transaccion ya procesada');
            } else {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }
    }

    /**
     * Método para realizar una verificación simple.
     *
     * Este método devuelve un JSON con un identificador de nodo, el parámetro recibido
     * y la firma de seguridad asociada a la instancia actual.
     *
     * @param mixed $param Parámetro a incluir en la respuesta.
     *
     * @return string JSON con los datos de verificación.
     */
    public function Check($param)
    {
        $return = array(
            "nodeId" => 123,
            "param" => $param,
            "sign" => $this->sign
        );

        return json_encode($return);
    }

    /**
     * Convierte un error en una respuesta JSON estructurada.
     *
     * Este método toma un código de error y un mensaje, y los convierte en una respuesta JSON
     * que incluye información adicional como el código y mensaje del proveedor, así como el balance
     * del usuario si es aplicable. También registra la transacción en la base de datos si es necesario.
     *
     * @param integer $code    Código de error.
     * @param string  $message Mensaje de error.
     *
     * @return string JSON con la respuesta del error.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";
        $response = array();

        $Proveedor = new Proveedor("", "FAZI");

        switch ($code) {
            case 1001:
                $codeProveedor = 200;
                $messageProveedor = "Success ";
                http_response_code(200);

                $TransaccionApi = new TransaccionApi("", $this->transaccionApi->getTransaccionId(), $Proveedor->getProveedorId());

                $codeProveedor = "";
                $messageProveedor = "";

                $Game = new Game();
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($TransaccionApi->getUsuarioId());

                $responseG = $Game->getBalance($UsuarioMandante);

                $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));
                $saldo = intval($saldo * 100);

                /*  Retornamos el mensaje satisfactorio  */
                $response = array(
                    "balance" => $saldo,
                    "transactionId" => $TransaccionApi->getTransapiId()
                );
                http_response_code(200);

                break;
            case 10001:
                $codeProveedor = 200;
                $messageProveedor = "Success ";

                try {
                    $SubProveedor = new Subproveedor("", "FAZI");
                    if ($_ENV['debug']) {
                        print_r($SubProveedor);
                    }
                    $TransjuegoLog = new TransjuegoLog("", '', '', $this->transaccionApi->getTransaccionId() . '_' . $SubProveedor->getSubproveedorId(), '0');
                    if ($_ENV['debug']) {
                        print_r($TransjuegoLog);
                    }
                    $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);

                    $codeProveedor = "";
                    $messageProveedor = "";

                    $Game = new Game();
                    /*  Obtenemos el Usuario Mandante con el Usuario Token */
                    $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());

                    $responseG = $Game->getBalance($UsuarioMandante);

                    $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));
                    $saldo = intval($saldo * 100);
                    /*  Retornamos el mensaje satisfactorio  */
                    $response = array(
                        "balance" => $saldo,
                        "transactionId" => $TransjuegoLog->transjuegologId
                    );
                    http_response_code(200);
                } catch (Exception $e) {
                    /*  Retornamos el mensaje satisfactorio  */
                    $codeProveedor = 7002;
                    $messageProveedor = "BetAlreadyCanceled";
                    http_response_code(404);
                }

                break;

            case 27:
                $codeProveedor = 1001;
                $messageProveedor = "BadParameters";
                http_response_code(404);
                break;

            case 10014:
                $codeProveedor = 1001;
                $messageProveedor = "BadParameters";
                http_response_code(302);
                break;

            case 20003:
                $codeProveedor = 3002;
                $messageProveedor = "NotValidUserName";
                http_response_code(404);
                break;

            case 10013:
                $codeProveedor = 3008;
                $messageProveedor = "AccountNotExists";
                http_response_code(404);
                break;

            case 22:
                $codeProveedor = 3008;
                $messageProveedor = "AccountNotExists ";
                http_response_code(404);
                break;

            case 10011:
                $codeProveedor = 6004;
                $messageProveedor = "TokenExpired";
                http_response_code(404);
                break;

            case 21:
                $codeProveedor = 6004;
                $messageProveedor = "TokenExpired";
                http_response_code(404);
                break;

            case 21017:
                $codeProveedor = 6004;
                $messageProveedor = "TokenExpired";
                http_response_code(401);
                break;

            case 10010:
                $codeProveedor = 7000;
                $messageProveedor = "TransactionExists";
                http_response_code(404);
                break;

            case 10005:
                $codeProveedor = 7000;
                $messageProveedor = "TransactionExists";
                http_response_code(404);
                break;

            case 1004:
                $codeProveedor = 7000;
                $messageProveedor = "TransactionExists";
                http_response_code(200);
                break;

            case 1005:
                $codeProveedor = 7001;
                $messageProveedor = "BetDoesNotExist";
                http_response_code(404);
                break;

            case 28:
                $codeProveedor = 7001;
                $messageProveedor = "BetDoesNotExist";
                http_response_code(404);
                break;

            case 20001:
                $codeProveedor = 7003;
                $messageProveedor = "InsufficientFunds";
                http_response_code(402);
                break;

            case 0:
                $codeProveedor = 9999;
                $messageProveedor = "General Error.";
                http_response_code(500);
                break;
            
            case 21010:
                $codeProveedor = 9999;
                $messageProveedor = "General Error.";
                http_response_code(503);
                break;

            case 21017:
                $codeProveedor = 9999;
                $messageProveedor = "General Error.";
                http_response_code(503);
                break;

            default:
                $codeProveedor = 9999;
                $messageProveedor = "General Error.";
                http_response_code(500);
                break;
        }

        if ($codeProveedor != "") {
            $respuesta = json_encode(array_merge($response, array(
                "code" => $codeProveedor,
                "code2" => $code
            )));
        } else {
            $respuesta = json_encode(array_merge($response));
        }

        if ($this->transaccionApi != null) {
            $this->transaccionApi->setTipo("R" . $this->transaccionApi->getTipo());
            $this->transaccionApi->setRespuestaCodigo("ERROR_" . $code);
            $this->transaccionApi->setRespuesta($respuesta);
            $this->transaccionApi->setTransaccionId($this->transaccionApi->getTransaccionId() . "_" . $code);

            if ($this->transaccionApi->getValor == "") {
                $this->transaccionApi->setValor(0);
            }

            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->insert($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();
        }

        return $respuesta;
    }
}
