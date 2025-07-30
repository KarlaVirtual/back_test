<?php

/**
 * Clase Betixon para la integración con un proveedor de juegos de casino.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;

use Backend\dto\Mandante;
use Backend\dto\PromocionalLog;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransaccionApi;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
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
 * Clase Betixon.
 *
 * Esta clase implementa la integración con el proveedor de juegos de casino Betixon.
 * Proporciona métodos para autenticar usuarios, gestionar transacciones de débito, crédito y reversa,
 * así como consultar saldos y verificar parámetros.
 */
class Betixon
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
     * Objeto para gestionar transacciones API.
     *
     * @var TransaccionApi|null
     */
    private $transaccionApi;

    /**
     * Datos adicionales para las transacciones.
     *
     * @var array|null
     */
    private $data;

    /**
     * Identificador del round extendido.
     *
     * @var string|null
     */
    private $roundIdSuper;

    /**
     * Constructor de la clase Betixon.
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
     * Autentica al usuario y obtiene su saldo.
     *
     * @return string JSON con los datos del usuario autenticado y su saldo.
     */
    public function Auth()
    {
        try {
            $Proveedor = new Proveedor("", "BTX");

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

            $Mandante = new Mandante($UsuarioMandante->mandante);

            if ($Mandante->propio == "S") {
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                $Balance = $Usuario->getBalance();

                $return = array(

                    "PlayerId" => $UsuarioMandante->usumandanteId,
                    "TotalBalance" => $Balance,
                    "Token" => $UsuarioToken->getToken(),
                    "HasError" => 0,
                    "ErrorId" => 0,
                    "ErrorDescription" => ""
                );
                return json_encode($return);
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el saldo actual del usuario.
     *
     * @return string JSON con el saldo del usuario.
     */
    public function getBalance()
    {
        try {
            $Proveedor = new Proveedor("", "BTX");

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

                $Balance = $Usuario->getBalance();

                $return = array(

                    "PlayerId" => $UsuarioMandante->getUsumandanteId(),
                    "TotalBalance" => $Balance,
                    "HasError" => 0,
                    "ErrorId" => 0,
                    "ErrorDescription" => ""

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
     * @param string $roundId       ID del round.
     * @param string $transactionId ID de la transacción.
     * @param array  $datos         Datos adicionales.
     *
     * @return string JSON con el resultado de la operación.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos)
    {
        $this->data = $datos;

        try {
            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            // Obtenemos el Proveedor con el abreviado BTX 
            $Proveedor = new Proveedor("", "BTX");

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

            // Obtenemos el Usuario Token con el token 
            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            // Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "BTX");


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

                throw new Exception("Transaccion con Rollback antes", "10004");
            }

            // Creamos la Transaccion por el Juego
            $TransaccionJuego = new TransaccionJuego();
            $TransaccionJuego->setProductoId($ProductoMandante->prodmandanteId);
            $TransaccionJuego->setTransaccionId($transactionId);
            $TransaccionJuego->setTicketId($roundId . $UsuarioMandante->getUsumandanteId() . "BTX");
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

            // Verificamos si existe el ticket_id antes, de ser positivo, tendriamos que combinar las apuestas 
            if ($TransaccionJuego->existsTicketId()) {
                $this->roundIdSuper = $roundId . $UsuarioMandante->getUsumandanteId() . "BTX";
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
                        $TransaccionJuego = new TransaccionJuego("", $roundId . $UsuarioMandante->getUsumandanteId() . "BTX", "");

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
                    }

                    // Commit de la transacción                   
                    $Transaction->commit();

                    // Consultamos de nuevo el usuario para obtener el saldo 
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                    $Balance = $Usuario->getBalance();

                    // Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket 
                    $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                    // Enviamos el mensaje Websocket al Usuario para que actualice el saldo 
                    $data = $Usuario->getWSMessage($UsuarioToken->getRequestId());
                    $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                    $WebsocketUsuario->sendWSMessage();

                    $return = array(
                        "TotalBalance" => $Balance,
                        "PlatformTransactionId" => $this->transaccionApi->transapiId,
                        "PlayerId" => $UsuarioMandante->getUsumandanteId(),
                        "Token" => $UsuarioToken->getToken(),
                        "HasError" => 0,
                        "ErrorId" => 0,
                        "ErrorDescription" => ""
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
     * Reversa una transacción previa.
     *
     * @param float  $rollbackAmount Monto a reversar.
     * @param string $roundId        ID del round.
     * @param string $transactionId  ID de la transacción original.
     * @param string $player         Identificador del jugador.
     * @param array  $datos          Datos adicionales.
     *
     * @return string JSON con el resultado de la operación.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos)
    {
        $usuarioid = explode("Usuario", $player)[1];

        $this->data = $datos;

        try {
            // Obtenemos el Proveedor con el abreviado BTX
            $Proveedor = new Proveedor("", "BTX");

            // Creamos la Transaccion API  
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);


            // Obtenemos el Usuario Token con el token
            //$UsuarioToken = new UsuarioToken($this->token,$Proveedor->getProveedorId());

            // Obtenemos el Usuario Mandante con el Usuario Token 
            //$UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            $UsuarioMandante = new UsuarioMandante($usuarioid);

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "BTX");


            // Verificamos que la transaccionId no se haya procesado antes
            if ($this->transaccionApi->existsTransaccionIdAndProveedor("OK")) {
                // Si la transaccionId ha sido procesada, reportamos el error
                throw new Exception("Transaccion ya procesada", "10001");
            }


            $transaccionNoExiste = false;

            try {
                $TransaccionApi2 = new TransaccionApi("", $transactionId, $Proveedor->getProveedorId());
                $jsonValue = json_decode($TransaccionApi2->getTValue());
                $valorTransaction = 0;

                // Agregamos Elementos a la Transaccion API 
                $this->transaccionApi->setProductoId($TransaccionApi2->getProductoId());
                $this->transaccionApi->setUsuarioId($TransaccionApi2->getUsuarioId());


                if (strpos($TransaccionApi2->getTipo(), 'DEBIT') !== false) {
                } else {
                    throw new Exception("Transaccion no es Debit", "10006");
                }
            } catch (Exception $e) {
                $transaccionNoExiste = true;
                throw new Exception("Transaccion no existe", "10005");
            }

            if ( ! $transaccionNoExiste) {
                // Creamos la Transaccion por el Juego
                $TransaccionJuego = new TransaccionJuego("", $TransaccionApi2->getIdentificador());
                $valorTransaction = $TransaccionJuego->getValorTicket();

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
                        $TransjuegoLog->setValor($TransaccionJuego->getValorTicket());
                        $this->transaccionApi->setValor($TransaccionJuego->getValorTicket());

                        $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);

                        // Obtenemos el Usuario para hacerle el credito
                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                        $Usuario->credit($TransaccionJuego->getValorTicket(), $Transaction);

                        // Commit de transacción
                        $Transaction->commit();

                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                        $Balance = ($Usuario->getBalance());


                        $return = array(
                            "TotalBalance" => $Balance,
                            "PlatformTransactionId" => $this->transaccionApi->transapiId,
                            "PlayerId" => $UsuarioMandante->getUsumandanteId(),
                            "HasError" => 0,
                            "ErrorId" => 0,
                            "ErrorDescription" => ""
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
            } else {
                // Obtenemos Mandante para verificar sus caracteristicas
                $Mandante = new Mandante($UsuarioMandante->mandante);

                // Verificamos si el mandante es Propio
                if ($Mandante->propio == "S") {
                    // Obtenemos la Transaccion de la BD para crear y registrar el Debito con esta transaccion
                    $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
                    $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

                    // Verificamos que la Transaccion si este conectada y lista para usarse 
                    if ($Transaction->isIsconnected()) {
                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                        $Balance = $Usuario->getBalance();

                        // Guardamos la Transaccion Api necesaria de estado OK
                        $this->transaccionApi->setRespuestaCodigo("OK");
                        $this->transaccionApi->setRespuesta(json_encode(array("balance" => $Balance)));

                        $trans = $this->transaccionApi->insert($Transaction);

                        $Transaction->commit();

                        $return = array(
                            "balance" => $Balance,
                            "transactionId" => $trans
                        );

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
     * @param string $roundId       ID del round.
     * @param string $transactionId ID de la transacción.
     * @param array  $datos         Datos adicionales.
     *
     * @return string JSON con el resultado de la operación.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos)
    {
        $this->data = $datos;

        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try {
            // Obtenemos el Proveedor con el abreviado BTX
            $Proveedor = new Proveedor("", "BTX");

            // Creamos la Transaccion API 
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);

            // Obtenemos el Usuario Token con el token 
            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            // Obtenemos el Usuario Mandante con el Usuario Token 
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "BTX");


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


            // Obtenemos la Transaccion Juego 
            $TransaccionJuego = new TransaccionJuego("", $roundId . $UsuarioMandante->getUsumandanteId() . "BTX");

            // Obtenemos el mandante para verificar sus caracteristicas  
            $Mandante = new Mandante($UsuarioMandante->mandante);


            // Verificamos si el mandante es propio  
            if ($Mandante->propio == "S") {
                // Obtenemos la Transaccion de la BD para crear y registrar el Debito con esta transaccion
                $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
                $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

                // Verificamos que la Transaccion si este conectada y lista para usarse  
                if ($Transaction->isIsconnected()) {
                    // Obtenemos el ID de la TransaccionJuego
                    $TransJuegoId = $TransaccionJuego->getTransjuegoId();

                    // Obtenemos el tipo de transacción dependiendo a betTypeID y si se le suma los creditos o no 
                    $sumaCreditos = true;
                    $tipoTransaccion = "CREDIT";

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


                    // Actualizamos la Transaccion Juego con los respectivas actualizaciones  
                    $TransaccionJuego->setValorPremio($TransaccionJuego->getValorPremio() + $creditAmount);
                    if ($TransaccionJuego->getValorPremio() > 0) {
                        $TransaccionJuego->setPremiado("S");
                        $TransaccionJuego->setFechaPago(date('Y-m-d H:i:s', time()));
                    }

                    $TransaccionJuego->setEstado("I");

                    $TransaccionJuego->update($Transaction);

                    // Si suma los creditos, hacemos el respectivo CREDIT  
                    if ($sumaCreditos) {
                        if ($creditAmount > 0) {
                            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                            $Usuario->creditWin($creditAmount, $Transaction);
                        }
                    }

                    // Commit de la transacción
                    $Transaction->commit();

                    // Consultamos de nuevo el usuario para obtener el saldo  
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                    $Balance = $Usuario->getBalance();

                    // Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  
                    $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                    // Enviamos el mensaje Websocket al Usuario para que actualice el saldo  
                    $data = $Usuario->getWSMessage($UsuarioToken->getRequestId());
                    $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                    $WebsocketUsuario->sendWSMessage();

                    // Retornamos el mensaje satisfactorio  

                    $return = array(
                        "TotalBalance" => $Balance,
                        "PlatformTransactionId" => $this->transaccionApi->transapiId,
                        "PlayerId" => $UsuarioMandante->getUsumandanteId(),
                        "Token" => $UsuarioToken->getToken(),
                        "HasError" => 0,
                        "ErrorId" => 0,
                        "ErrorDescription" => ""
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
     * Verifica parámetros específicos.
     *
     * @param mixed $param Parámetro a verificar.
     *
     * @return string JSON con los resultados de la verificación.
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
     * Convierte errores en respuestas estructuradas.
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

        $response = array();


        $Proveedor = new Proveedor("", "BTX");

        switch ($code) {
            case 10011:
                $codeProveedor = 102;
                $messageProveedor = "Invalid Token";
                break;

            case 21:
                $codeProveedor = 102;
                $messageProveedor = "Invalid Token";
                break;

            case 22:
                $codeProveedor = 130;
                $messageProveedor = "Invalid Token";
                break;

            case 20001:
                $codeProveedor = "21";
                $messageProveedor = "Not Enough Balance";
                break;

            case 0:
                $codeProveedor = 130;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 27:
                $codeProveedor = 130;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 28:
                $codeProveedor = 130;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 29:
                $codeProveedor = 107;
                $messageProveedor = "Transaction Not Found";
                break;

            case 10001:
                $codeProveedor = 110;
                $messageProveedor = "Transaction Exists";
                break;

            case 10004:
                $codeProveedor = 130;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 10014:
                $codeProveedor = 130;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 10010:
                $codeProveedor = 130;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 10005:
                $codeProveedor = 107;
                $messageProveedor = "Transaction Not Found";
                break;

            default:
                $codeProveedor = 130;
                $messageProveedor = "General Error. (" . $code . ")";
                break;
        }


        if ($codeProveedor != "") {
            $respuesta = json_encode(array_merge($response, array(
                "HasError" => 1,
                "ErrorId" => $codeProveedor,
                "ErrorDescription" => $messageProveedor
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