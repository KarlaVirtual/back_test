<?php

/**
 * Clase Oryx para la integración con el proveedor ORYX.
 *
 * Esta clase contiene métodos para manejar transacciones relacionadas con juegos de casino,
 * incluyendo autenticación, débitos, créditos, rollbacks y consultas de saldo.
 *
 * @category Integración
 * @package  Backend\integrations\casino
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */

namespace Backend\integrations\casino;

use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Mandante;
use Backend\dto\PromocionalLog;
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
 * Clase principal para la integración con el proveedor ORYX.
 *
 * Esta clase contiene métodos para manejar transacciones relacionadas con juegos de casino,
 * incluyendo autenticación, débitos, créditos, rollbacks y consultas de saldo.
 */
class Oryx
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
     * Identificador único del usuario.
     *
     * @var string|null
     */
    private $uid;

    /**
     * Firma de seguridad.
     *
     * @var string
     */
    private $sign;

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
     * Identificador de la ronda extendida.
     *
     * @var string|null
     */
    private $roundIdSuper;

    /**
     * Constructor de la clase Oryx.
     *
     * @param string $token Token de autenticación.
     * @param string $sign  Firma de seguridad.
     */
    public function __construct($token, $sign)
    {
        $this->token = $token;
        $this->sign = $sign;
    }

    /**
     * Obtiene el ID del operador.
     *
     * @return integer|null ID del operador.
     */
    public function getOperadorId()
    {
        return $this->operadorId;
    }

    /**
     * Autentica al usuario y devuelve información básica.
     *
     * @return string JSON con información del usuario autenticado.
     * @throws Exception Si ocurre un error durante la autenticación.
     */
    public function Auth()
    {
        try {
            $Proveedor = new Proveedor("", "ORYX");

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

            if ($Mandante->propio == "S") {
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                $Balance = (int)($Usuario->getBalance() * 100);

                $return = array(

                    "playerId" => $UsuarioToken->getToken(),
                    "balance" => $Balance,
                    "currencyCode" => $UsuarioMandante->getMoneda(),
                    "languageCode" => "ENG",
                );
                return json_encode($return);
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del usuario utilizando su ID.
     *
     * @param string $playerId ID del jugador.
     *
     * @return string JSON con el balance del usuario.
     * @throws Exception Si ocurre un error al obtener el balance.
     */
    public function getBalance2($playerId)
    {
        try {
            $Proveedor = new Proveedor("", "ORYX");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("BALANCE");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);


            if ($playerId == "") {
                throw new Exception("Token vacio", "10011");
            }


            $UsuarioMandante = new UsuarioMandante($playerId);

            $Mandante = new Mandante($UsuarioMandante->mandante);

            if ($Mandante->propio == "S") {
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                $Balance = (int)($Usuario->getBalance() * 100);

                $return = array(
                    "balance" => $Balance,

                );
                return json_encode($return);
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del usuario utilizando el token.
     *
     * @param string $playerId ID del jugador.
     *
     * @return string JSON con el balance del usuario.
     * @throws Exception Si ocurre un error al obtener el balance.
     */
    public function getBalance($playerId)
    {
        try {
            $Proveedor = new Proveedor("", "ORYX");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("BALANCE");
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

            if ($Mandante->propio == "S") {
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                $Balance = (int)($Usuario->getBalance() * 100);

                $return = array(
                    "balance" => $Balance,
                    "responseCode" => "OK",


                );
                return json_encode($return);
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un débito en la cuenta del usuario.
     *
     * @param string $gameId        ID del juego.
     * @param float  $debitAmount   Monto a debitar.
     * @param string $roundId       ID de la ronda.
     * @param string $transactionId ID de la transacción.
     * @param array  $datos         Datos adicionales de la transacción.
     *
     * @return string JSON con el resultado de la operación.
     * @throws Exception Si ocurre un error durante el débito.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos)
    {
        $this->data = $datos;

        try {
            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            //  Obtenemos el Proveedor con el abreviado ORYX
            $Proveedor = new Proveedor("", "ORYX");

            //  Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);

            //  Verificamos que el monto a debitar sea positivo
            if ($debitAmount < 0) {
                throw new Exception("No puede ser negativo el monto a debitar.", "10002");
            }

            //  Obtenemos el Usuario Token con el token
            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            //  Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "ORYX");


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

            //  Agregamos Elementos a la Transaccion API
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());

            //  Creamos la Transaccion API Para verificar si hay antes hubo algun ROLLBACK antes
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
            //  Verificamos que la transaccionId no se haya procesado antes
            if ($TransaccionApiRollback->existsTransaccionIdAndProveedor("OK")) {
                //  Si la transaccionId tiene un Rollback antes, reportamos el error
                $DebitConRollbackAntes = true;

                throw new Exception("Transaccion con Rollback antes", "10004");
            }

            //  Verificamos que la transaccionId no se haya procesado antes
            if ($TransaccionApiRollback->existsTransaccionIdAndProveedor("ERROR")) {
                //  Si la transaccionId tiene un Rollback antes, reportamos el error
                $DebitConRollbackAntes = true;

                throw new Exception("Transaccion con Rollback antes", "10004");
            }


            //  Verificamos que la transaccionId no se haya procesado antes
            if ($this->transaccionApi->existsTransaccionIdAndProveedor("OK")) {
                //  Si la transaccionId ha sido procesada, reportamos el error
                throw new Exception("Transaccion ya procesada", "10001");
            }


            //  Creamos la Transaccion por el Juego
            $TransaccionJuego = new TransaccionJuego();
            $TransaccionJuego->setProductoId($ProductoMandante->prodmandanteId);
            $TransaccionJuego->setTransaccionId($transactionId);
            $TransaccionJuego->setTicketId($roundId . $UsuarioMandante->getUsumandanteId() . "ORYX");
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

            //  Verificamos si existe el ticket_id antes, de ser positivo, tendriamos que combinar las apuestas
            if ($TransaccionJuego->existsTicketId()) {
                $ExisteTicket = true;
                $this->roundIdSuper = $roundId . $UsuarioMandante->getUsumandanteId() . "ORYX";
                //throw new Exception("Transaccion Juego Existe", "10010");
            }

            //  Obtenemos el mandante para verificar sus caracteristicas
            $Mandante = new Mandante($UsuarioMandante->mandante);

            //  Verificamos que el mandante sea Propio, para proceder con nuestros Usuarios
            if ($Mandante->propio == "S") {
                // Obtenemos la Transaccion de la BD para crear y registrar el Debito con esta transaccion
                $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
                $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

                //  Verificamos que la Transaccion si este conectada y lista para usarse
                if ($Transaction->isIsconnected()) {
                    //  Verificamos si Existe el ticket para combinar las apuestas.
                    if ($ExisteTicket) {
                        //  Obtenemos la Transaccion Juego y combinamos las aúestas.
                        $TransaccionJuego = new TransaccionJuego("", $roundId . $UsuarioMandante->getUsumandanteId() . "ORYX", "");
                        if ($TransaccionJuego->getTransaccionId() != $transactionId) {
                            $TransaccionJuego->setValorTicket($TransaccionJuego->getValorTicket() + $debitAmount);
                            $TransaccionJuego->update($Transaction);
                        }
                        $transaccion_id = $TransaccionJuego->getTransjuegoId();
                    } else {
                        $transaccion_id = $TransaccionJuego->insert($Transaction);
                    }

                    //  Obtenemos el tipo de Transaccion dependiendo de el betTypeID
                    $tipoTransaccion = "DEBIT";

                    //  Creamos el log de la transaccion juego para auditoria
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
                        //  Obtenemos nuestro Usuario y hacemos el debito
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

                    // Commit de la transacción
                    $Transaction->commit();


                    if ($UsuarioMandante->paisId == "173" && ($UsuarioMandante->usumandanteId == '16' || $UsuarioMandante->usumandanteId == '2372853')) {
                        $Subproveedor = new Subproveedor($Producto->getSubproveedorId());

                        //$ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());
                        exec("php -f " . __DIR__ . "/ActivacionRuletaCasino.php " . $UsuarioMandante->paisId . " " . $UsuarioMandante->usumandanteId . " " . $debitAmount . " " . 2 . " " . '1' . " " . $Subproveedor->getSubproveedorId() . " " . $ProductoMandante->prodmandanteId . " > /dev/null &");
                    }


                    //  Consultamos de nuevo el usuario para obtener el saldo
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                    $Balance = (int)($Usuario->getBalance() * 100);

                    $return = array(
                        "balance" => $Balance,
                        "responseCode" => "OK"
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
     * Realiza un rollback de una transacción.
     *
     * @param float  $rollbackAmount Monto del rollback.
     * @param string $roundId        ID de la ronda.
     * @param string $transactionId  ID de la transacción.
     * @param string $player         Información del jugador.
     * @param array  $datos          Datos adicionales de la transacción.
     *
     * @return string JSON con el resultado de la operación.
     * @throws Exception Si ocurre un error durante el rollback.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos)
    {
        $usuarioid = explode("Usuario", $player)[1];

        $this->data = $datos;


        try {
            //  Obtenemos el Proveedor con el abreviado ORYX
            $Proveedor = new Proveedor("", "ORYX");

            //  Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);

            //  Verificamos que la transaccionId no se haya procesado antes
            if ($this->transaccionApi->existsTransaccionIdAndProveedor("OK")) {
                //  Si la transaccionId ha sido procesada, reportamos el error
                throw new Exception("Transaccion ya procesada", "10001");
            }

            $transaccionNoExiste = false;

            try {
                $TransaccionApi2 = new TransaccionApi("", $transactionId, $Proveedor->getProveedorId());
                $jsonValue = json_decode($TransaccionApi2->getTValue());
                $valorTransaction = 0;

                //  Agregamos Elementos a la Transaccion API
                $this->transaccionApi->setProductoId($TransaccionApi2->getProductoId());
                $this->transaccionApi->setUsuarioId($TransaccionApi2->getUsuarioId());


                if (strpos($TransaccionApi2->getTipo(), 'DEBIT') !== false) {
                } else {
                    throw new Exception("Transaccion no es Debit", "10006");
                }
                $UsuarioMandante = new UsuarioMandante($TransaccionApi2->getUsuarioId());

                $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "ORYX");
            } catch (Exception $e) {
                $transaccionNoExiste = true;
                throw new Exception("Transaccion no existe", "10005");
            }

            if ( ! $transaccionNoExiste) {
                //  Creamos la Transaccion por el Juego
                $TransaccionJuego = new TransaccionJuego("", $TransaccionApi2->getIdentificador());
                $valorTransaction = $TransaccionApi2->getValor();

                //  Obtenemos Mandante para verificar sus caracteristicas
                $Mandante = new Mandante($UsuarioMandante->mandante);

                //  Verificamos si el mandante es Propio
                if ($Mandante->propio == "S") {
                    // Obtenemos la Transaccion de la BD para crear y registrar el Debito con esta transaccion
                    $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
                    $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

                    //  Verificamos que la Transaccion si este conectada y lista para usarse
                    if ($Transaction->isIsconnected()) {
                        //  Actualizamos Transaccion Juego
                        $TransaccionJuego->setEstado("I");
                        $TransaccionJuego->setValorTicket($TransaccionJuego->getValorTicket() - $valorTransaction);
                        $TransaccionJuego->update($Transaction);


                        //  Obtenemos el Transaccion Juego ID
                        $TransJuegoId = $TransaccionJuego->getTransjuegoId();

                        //  Creamos el Log de Transaccion Juego
                        $TransjuegoLog = new TransjuegoLog();
                        $TransjuegoLog->setTransjuegoId($TransJuegoId);
                        $TransjuegoLog->setTransaccionId("ROLLBACK" . $transactionId);
                        $TransjuegoLog->setTipo("ROLLBACK");
                        $TransjuegoLog->setTValue(json_encode($datos));
                        $TransjuegoLog->setUsucreaId(0);
                        $TransjuegoLog->setUsumodifId(0);
                        $TransjuegoLog->setValor($valorTransaction);

                        $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);

                        //  Obtenemos el Usuario para hacerle el credito
                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                        $Usuario->credit($valorTransaction, $Transaction);

                        $UsuarioHistorial = new UsuarioHistorial();
                        $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                        $UsuarioHistorial->setDescripcion('');
                        $UsuarioHistorial->setMovimiento('E');
                        $UsuarioHistorial->setUsucreaId(0);
                        $UsuarioHistorial->setUsumodifId(0);
                        $UsuarioHistorial->setTipo(30);
                        $UsuarioHistorial->setValor($valorTransaction);
                        $UsuarioHistorial->setExternoId($TransjuegoLog_id);

                        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

                        // Commit de la transacción
                        $Transaction->commit();

                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                        $Balance = (int)($Usuario->getBalance() * 100);


                        $return = array(
                            "balance" => $Balance,
                            "responseCode" => "OK"
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
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un rollback de una ronda completa.
     *
     * @param float  $rollbackAmount Monto del rollback.
     * @param string $roundId        ID de la ronda.
     * @param string $transactionId  ID de la transacción.
     * @param string $player         Información del jugador.
     * @param array  $datos          Datos adicionales de la transacción.
     *
     * @return string JSON con el resultado de la operación.
     * @throws Exception Si ocurre un error durante el rollback.
     */
    public function RollbackRound($rollbackAmount, $roundId, $transactionId, $player, $datos)
    {
        $usuarioid = explode("Usuario", $player)[1];

        $this->data = $datos;


        try {
            //  Obtenemos el Proveedor con el abreviado ORYX
            $Proveedor = new Proveedor("", "ORYX");

            //  Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);


            //  Obtenemos el Usuario Token con el token
            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            // Obtenemos el Usuario Mandante con el Usuario Token

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);


            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "ORYX");

            $transaccionNoExiste = false;

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "ORYX");


            //  Verificamos que la transaccionId no se haya procesado antes
            if ($this->transaccionApi->existsTransaccionIdAndProveedor("OK")) {
                //  Si la transaccionId ha sido procesada, reportamos el error
                throw new Exception("Transaccion ya procesada", "10001");
            }


            if ( ! $transaccionNoExiste) {
                //  Creamos la Transaccion por el Juego
                $TransaccionJuego = new TransaccionJuego("", $roundId . $UsuarioMandante->getUsumandanteId() . "ORYX", "");

                $valorTransaction = $TransaccionJuego->getValorTicket();

                //  Obtenemos Mandante para verificar sus caracteristicas
                $Mandante = new Mandante($UsuarioMandante->mandante);

                //  Verificamos si el mandante es Propio
                if ($Mandante->propio == "S") {
                    // Obtenemos la Transaccion de la BD para crear y registrar el Debito con esta transaccion
                    $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
                    $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

                    //  Verificamos que la Transaccion si este conectada y lista para usarse
                    if ($Transaction->isIsconnected()) {
                        //  Actualizamos Transaccion Juego
                        $TransaccionJuego->setEstado("I");
                        $TransaccionJuego->update($Transaction);

                        //  Obtenemos el Transaccion Juego ID
                        $TransJuegoId = $TransaccionJuego->getTransjuegoId();

                        //  Creamos el Log de Transaccion Juego
                        $TransjuegoLog = new TransjuegoLog();
                        $TransjuegoLog->setTransjuegoId($TransJuegoId);
                        $TransjuegoLog->setTransaccionId("ROLLBACK" . $transactionId);
                        $TransjuegoLog->setTipo("ROLLBACK");
                        $TransjuegoLog->setTValue(json_encode($datos));
                        $TransjuegoLog->setUsucreaId(0);
                        $TransjuegoLog->setUsumodifId(0);
                        $TransjuegoLog->setValor($TransaccionJuego->getValorTicket());

                        $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);

                        //  Obtenemos el Usuario para hacerle el credito
                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                        $Usuario->credit($TransaccionJuego->getValorTicket(), $Transaction);

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
                        $Balance = (int)($Usuario->getBalance() * 100);


                        $return = array(
                            "balance" => $Balance,
                            "responseCode" => "OK"
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
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un crédito en la cuenta del usuario.
     *
     * @param string $gameId        ID del juego.
     * @param float  $creditAmount  Monto a acreditar.
     * @param string $roundId       ID de la ronda.
     * @param string $transactionId ID de la transacción.
     * @param array  $datos         Datos adicionales de la transacción.
     *
     * @return string JSON con el resultado de la operación.
     * @throws Exception Si ocurre un error durante el crédito.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos)
    {
        $this->data = $datos;

        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try {
            //  Obtenemos el Proveedor con el abreviado ORYX
            $Proveedor = new Proveedor("", "ORYX");

            //  Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);

            //  Obtenemos el Usuario Token con el token
            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            //  Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "ORYX");


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


            //  Obtenemos la Transaccion Juego
            $TransaccionJuego = new TransaccionJuego("", $roundId . $UsuarioMandante->getUsumandanteId() . "ORYX");

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
                        $TransaccionJuego->setFechaPago(date('Y-m-d H:i:s', time()));
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

                    //  Consultamos de nuevo el usuario para obtener el saldo
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                    $Balance = (int)($Usuario->getBalance() * 100);

                    //  Retornamos el mensaje satisfactorio

                    $return = array(
                        "TotalBalance" => $Balance,
                        "PlatformTransactionId" => $this->transaccionApi->transapiId,
                        "PlayerId" => $UsuarioMandante->getUsumandanteId(),
                        "Token" => $UsuarioToken->getToken(),
                        "HasError" => 0,
                        "ErrorId" => 0,
                        "ErrorDescription" => ""
                    );

                    $return = array(
                        "balance" => $Balance,
                        "responseCode" => "OK"
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
     * Verifica un parámetro y devuelve información básica.
     *
     * @param mixed $param Parámetro a verificar.
     *
     * @return string JSON con la información del parámetro.
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
     * Convierte un error en un formato JSON manejable.
     *
     * @param integer $code    Código del error.
     * @param string  $message Mensaje del error.
     *
     * @return string JSON con la información del error.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $response = array();


        $Proveedor = new Proveedor("", "ORYX");

        switch ($code) {
            case 10011:
                $codeProveedor = "TOKEN_NOT_FOUND";
                $messageProveedor = "Invalid Token";

                http_response_code(400);

                break;
            case 21:
                $codeProveedor = "TOKEN_NOT_FOUND";
                $messageProveedor = "Invalid Token";

                http_response_code(400);
                break;
            case 22:
                $codeProveedor = "TOKEN_NOT_FOUND";
                $messageProveedor = "Invalid Token";

                http_response_code(400);
                break;
            case 20001:

                $codeProveedor = "OUT_OF_MONEY";
                $messageProveedor = "Not Enough Balance";

                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                $Mandante = new Mandante($UsuarioMandante->mandante);

                if ($Mandante->propio == "S") {
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $Balance = (int)($Usuario->getBalance() * 100);

                    $response = array_merge($response, array(
                        "balance" => $Balance
                    ));
                }

                break;

            case 0:
                $codeProveedor = "ERROR";
                $messageProveedor = "General Error. (" . $code . ")";

                http_response_code(500);


                break;
            case 27:
                $codeProveedor = "ERROR";
                $messageProveedor = "General Error. (" . $code . ")";

                http_response_code(500);
                break;
            case 28:


                $codeProveedor = "ROUND_NOT_FOUND";
                $messageProveedor = "ROUND_NOT_FOUND";

                http_response_code(500);


                break;
            case 29:

                $codeProveedor = "TRANSACTION_NOT_FOUND";
                $messageProveedor = "Transaction Not Found";
                http_response_code(500);


                break;

            case 10001:

                $codeProveedor = "ERROR";
                $codeProveedor = "ERROR";
                $messageProveedor = "Transaction Exists";


                $tipo = $this->transaccionApi->getTipo();

                $codeProveedor = "";
                $messageProveedor = "";

                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                $Mandante = new Mandante($UsuarioMandante->mandante);

                if ($Mandante->propio == "S") {
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $TransaccionApi2 = new TransaccionApi("", $this->transaccionApi->getTransaccionId(), $Proveedor->getProveedorId());

                    $Balance = (int)($Usuario->getBalance() * 100);

                    $response = array_merge($response, array(
                        "balance" => $Balance,
                        "responseCode" => "OK"
                    ));
                }

                break;

            case 10004:

                $codeProveedor = "ERROR";
                $messageProveedor = "General Error. (" . $code . ")";

                $codeProveedor = "ERROR";
                $messageProveedor = "Apuesta con cancelacion antes.";
                http_response_code(400);

                break;

            case 10005:
                $codeProveedor = "TRANSACTION_NOT_FOUND";
                $messageProveedor = "Transaction not found";
                http_response_code(400);

                break;
            case 10014:

                $codeProveedor = "ERROR";
                $messageProveedor = "General Error. (" . $code . ")";

                http_response_code(500);

                break;


            case 10010:

                $codeProveedor = "ERROR";
                $messageProveedor = "General Error. (" . $code . ")";

                http_response_code(500);


                break;

            default:
                $codeProveedor = "ERROR";
                $messageProveedor = "General Error. (" . $code . ")";

                http_response_code(500);


                break;
        }


        if ($codeProveedor != "") {
            $respuesta = json_encode(array_merge($response, array(
                "responseCode" => $codeProveedor,
                // "responseCode2" => $codeProveedor,
                "errorDescription" => $messageProveedor,
                "errorDescriptio2n" => $message
            )));
        } else {
            $respuesta = json_encode(array_merge($response));
        }

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