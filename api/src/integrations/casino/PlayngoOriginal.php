<?php

/**
 * Clase Playngo para la integración con el proveedor de juegos de casino PLAYNGO.
 *
 * @category Integración
 * @package  Backend\integrations\casino
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */

namespace Backend\integrations\casino;

use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\Clasificador;
use Backend\dto\Mandante;
use Backend\dto\Pais;
use Backend\dto\ProdMandanteTipo;
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
use SimpleXMLElement;

/**
 * Clase Playngo.
 *
 * Esta clase se encarga de manejar la integración con el proveedor de juegos de casino PLAYNGO.
 * Proporciona métodos para autenticar usuarios, gestionar transacciones y manejar errores.
 */
class Playngo
{
    /**
     * Nombre de usuario para autenticación.
     *
     * @var string
     */
    private $Login;

    /**
     * Contraseña para autenticación.
     *
     * @var string
     */
    private $Password;

    /**
     * Token de autenticación del usuario.
     *
     * @var string
     */
    private $token;

    /**
     * Firma utilizada para validar solicitudes.
     *
     * @var string
     */
    private $sign;

    /**
     * Firma original utilizada en el entorno de desarrollo.
     *
     * @var string
     */
    private $signOriginalDEV = "stagestagestagestage";

    /**
     * Firma original utilizada en el entorno de producción.
     *
     * @var string
     */
    private $signOriginal = "yryUXrbbkAYoGOAsVFXZJmogm";

    /**
     * Objeto para manejar transacciones API.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * Datos asociados a la solicitud actual.
     *
     * @var array
     */
    private $data;

    /**
     * Método actual que se está ejecutando.
     *
     * @var string
     */
    private $method;

    /**
     * Identificador global del ticket.
     *
     * @var string
     */
    private $ticketIdGlobal;

    /**
     * Identificador externo del usuario.
     *
     * @var string
     */
    private $externalId;

    /**
     * Constructor de la clase Playngo.
     *
     * @param string $token      Token de autenticación.
     * @param string $uid        Identificador único del usuario (opcional).
     * @param string $externalId Identificador externo del usuario (opcional).
     */
    public function __construct($token, $uid = "", $externalId = "")
    {
        $this->token = $token;
        $this->sign = $uid;
        $this->externalId = $externalId;

        if ($this->sign != $this->signOriginal) {
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }
    }

    /**
     * Método para autenticar al usuario.
     *
     * @return string XML con la respuesta de autenticación.
     * @throws Exception Si el token o externalId están vacíos o el usuario está inactivo.
     */
    public function Auth()
    {
        $this->method = 'authenticate';
        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }
            $Proveedor = new Proveedor("", "PLAYNGO");

            if ($this->token != "") {
                //  Obtenemos el Usuario Token con el token 
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                //  Obtenemos el Usuario Mandante con el Usuario Token 
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                //  Obtenemos el Usuario Mandante con el Usuario Token 
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            $Mandante = new Mandante($UsuarioMandante->mandante);

            if ($Mandante->propio == "S") {
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);


                if ($Usuario->estado != "A") {
                    throw new Exception("Usuario Inactivo", "20003");
                }

                $Balance = $Usuario->getBalance();

                $PKT = new SimpleXMLElement("<authenticate></authenticate>");

                $PKT->addChild('externalId', $UsuarioToken->usuarioId);
                $PKT->addChild('statusCode', 0);
                $PKT->addChild('statusMessage', 'ok');
                $PKT->addChild('userCurrency', $UsuarioMandante->getMoneda());
                $PKT->addChild('nickname', 'Usuario' . $UsuarioToken->usuarioId);
                $PKT->addChild('country', 'PE');
                $PKT->addChild('birthdate', '1970-01-01');
                $PKT->addChild('registration', '2010-05-05');
                $PKT->addChild('language', 'ES');
                $PKT->addChild('affiliateId', '');
                $PKT->addChild('real', $Balance);
                $PKT->addChild('externalGameSessionId', '');
                $PKT->addChild('region', '3');
                $PKT->addChild('gender', 'm');

                return $PKT->asXML();
            } else {
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }


    /**
     * Método para obtener el balance del usuario.
     *
     * @param string $gameId Identificador del juego.
     *
     * @return string XML con el balance del usuario.
     * @throws Exception Si el usuario está inactivo o hay errores en la transacción.
     */
    public function getBalance($gameId)
    {
        $this->method = 'balance';
        try {
            $Proveedor = new Proveedor("", "PLAYNGO");


            if ($this->token != "") {
                //  Obtenemos el Usuario Token con el token 
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                //  Obtenemos el Usuario Mandante con el Usuario Token 
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                //  Obtenemos el Usuario Mandante con el Usuario Token 
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            //  Obtenemos el producto con el gameId  
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());


            try {
            } catch (Exception $e) {
                if ($e->getCode() != 49) {
                    throw $e;
                }
            }

            $Mandante = new Mandante($UsuarioMandante->mandante);

            if ($Mandante->propio == "S") {
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);


                if ($Usuario->estado != "A") {
                    throw new Exception("Usuario Inactivo", "20003");
                }

                $Balance = (($Usuario->getBalance()));

                $PKT = new SimpleXMLElement("<balance></balance>");

                $PKT->addChild('statusCode', 0);
                $PKT->addChild('currency', $UsuarioMandante->getMoneda());
                $PKT->addChild('real', $Balance);

                return $PKT->asXML();
            } else {
                $Pais = new Pais($UsuarioMandante->paisId);
                $ProdMandanteTipo = new ProdMandanteTipo("CASINO", $Mandante->mandante);
                $data = array(
                    "site" => $ProdMandanteTipo->siteId,
                    "key" => $ProdMandanteTipo->siteKey,
                    "token" => $UsuarioMandante->tokenExterno
                );

                $result = $Mandante->sendRequest($ProdMandanteTipo->urlApi . "/authenticate", "POST", $data);
                $result = array(
                    "error" => 'false',
                    "player" => array(
                        "userid" => 1,
                        "balance" => 1,
                        "name" => 1,
                        "country" => '173',
                        "currency" => 'PEN'

                    )
                );
                $result = json_decode(json_encode($result));

                if ($result == "") {
                    throw new Exception("No coinciden ", "50001");
                }

                if ($result->error == "" || $result->error == 'true') {
                    throw new Exception("No coinciden ", "50001");
                }

                $userid = $result->player->userid;
                $balance = $result->player->balance;
                $name = $result->player->name;
                $lastname = $result->player->lastname;
                $currency = $result->player->currency;
                $dirip = $result->player->ip;
                $country = $result->player->country;
                $email = $result->player->email;

                if ($userid == "" || ! is_numeric($userid)) {
                    throw new Exception("No coinciden ", "50001");
                }

                if ($balance == "") {
                    throw new Exception("No coinciden ", "50001");
                }

                if ($name == "") {
                    throw new Exception("No coinciden ", "50001");
                }

                if ($currency == "") {
                    throw new Exception("No coinciden ", "50001");
                }

                if ($country == "") {
                    throw new Exception("No coinciden ", "50001");
                }

                $PKT = new SimpleXMLElement("<authenticate></authenticate>");

                $PKT->addChild('statusCode', 0);
                $PKT->addChild('currency', $UsuarioMandante->getMoneda());
                $PKT->addChild('real', $balance);

                return $PKT->asXML();
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para realizar un débito en la cuenta del usuario.
     *
     * @param string $gameId        Identificador del juego.
     * @param string $ticketId      Identificador del ticket.
     * @param string $uid           Identificador único del usuario.
     * @param float  $debitAmount   Monto a debitar.
     * @param string $transactionId Identificador de la transacción.
     * @param array  $datos         Datos adicionales de la transacción.
     *
     * @return string XML con la respuesta de la transacción.
     * @throws Exception Si hay errores en la transacción o el usuario está inactivo.
     */
    public function Debit($gameId, $ticketId, $uid, $debitAmount, $transactionId, $datos)
    {
        $this->method = 'reserve';
        $this->ticketIdGlobal = $ticketId;

        $this->data = $datos;

        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            //  Obtenemos el Proveedor con el abreviado PLAYNGO 
            $Proveedor = new Proveedor("", "PLAYNGO");

            if ($this->token != "") {
                //  Obtenemos el Usuario Token con el token 
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                //  Obtenemos el Usuario Mandante con el Usuario Token 
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                //  Obtenemos el Usuario Mandante con el Usuario Token 
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }


            //  Obtenemos el producto con el gameId  
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

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

            $this->transaccionApi->setIdentificador("PLAYNGO" . $ticketId);

            //  Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego  
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            //  Agregamos Elementos a la Transaccion API  
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());

            //  Verificamos que la transaccionId no se haya procesado antes  
            if ($this->transaccionApi->existsTransaccionIdAndProveedor("OK")) {
                //  Si la transaccionId ha sido procesada, reportamos el error
                throw new Exception("Transaccion ya procesada", "10001");

                $transactionId = "ND" . $transactionId;
                $this->transaccionApi->setTransaccionId($transactionId);
            }


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


            //  Verificamos que la transaccionId no se haya procesado antes  
            if ($TransaccionApiRollback->existsTransaccionIdAndProveedor("ERROR")) {
                //  Si la transaccionId tiene un Rollback antes, reportamos el error
                throw new Exception("Transaccion con Rollback antes", "10004");
            }

            //  Creamos la Transaccion por el Juego  
            $TransaccionJuego = new TransaccionJuego();
            $TransaccionJuego->setProductoId($ProductoMandante->prodmandanteId);
            $TransaccionJuego->setTransaccionId($transactionId);
            $TransaccionJuego->setTicketId("PLAYNGO" . $ticketId);
            $TransaccionJuego->setValorTicket($debitAmount);
            $TransaccionJuego->setValorPremio(0);
            $TransaccionJuego->setMandante($UsuarioMandante->mandante);
            $TransaccionJuego->setUsuarioId($UsuarioMandante->usumandanteId);
            $TransaccionJuego->setEstado("A");
            $TransaccionJuego->setUsucreaId(0);
            $TransaccionJuego->setUsumodifId(0);
            $TransaccionJuego->setTipo('NORMAL');
            $TransaccionJuego->setPremiado('N');
            $TransaccionJuego->setFechaPago(date('Y-m-d H:i:s', time()));

            $ExisteTicket = false;

            //  Verificamos si existe el ticket_id antes, de ser positivo, tendriamos que combinar las apuestas  
            if ($TransaccionJuego->existsTicketId()) {
                $ExisteTicket = true;
            }


            //  Obtenemos el mandante para verificar sus caracteristicas  
            $Mandante = new Mandante($UsuarioMandante->mandante);


            //  Verificamos que el mandante sea Propio, para proceder con nuestros Usuarios  
            if ($Mandante->propio == "S") {
                //  Obtenemos nuestro Usuario y hacemos el debito
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                if ($Usuario->estado != "A") {
                    throw new Exception("Usuario Inactivo", "20003");
                }

                // Obtenemos la Transaccion de la BD para crear y registrar el Debito con esta transaccion 
                $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
                $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();


                //  Verificamos que la Transaccion si este conectada y lista para usarse  
                if ($Transaction->isIsconnected()) {
                    //  Verificamos si Existe el ticket para combinar las apuestas.
                    if ($ExisteTicket) {
                        //  Obtenemos la Transaccion Juego y combinamos las aúestas.
                        $TransaccionJuego = new TransaccionJuego("", "PLAYNGO" . $ticketId, "");
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


                    $Usuario->debit($debitAmount, $Transaction);


                    // Commit de la transacción
                    $Transaction->commit();

                    //  Consultamos de nuevo el usuario para obtener el saldo  
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                    $Balance = (($Usuario->getBalance()));

                    //  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  
                    $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                    //  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  
                    $data = $Usuario->getWSMessage($UsuarioToken->getRequestId());
                    $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                    $WebsocketUsuario->sendWSMessage();

                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->getUsumandanteId());

                    //  Retornamos el mensaje satisfactorio  
                    $PKT = new SimpleXMLElement("<reserve></reserve>");

                    $PKT->addChild('externalTransactionId', $TransjuegoLog_id);
                    $PKT->addChild('real', $Balance);
                    $PKT->addChild('currency', $UsuarioMandante->getMoneda());
                    $PKT->addChild('statusCode', 0);
                    $PKT->addChild('statusMessage', 'ok');

                    $respuesta = $PKT->asXML();

                    //  Guardamos la Transaccion Api necesaria de estado OK   
                    $this->transaccionApi->setRespuestaCodigo("OK");
                    $this->transaccionApi->setRespuesta($respuesta);
                    $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                    $TransaccionApiMySqlDAO->insert($this->transaccionApi);
                    $TransaccionApiMySqlDAO->getTransaction()->commit();

                    return $PKT->asXML();
                }
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un rollback de una transacción.
     *
     * Este método revierte una transacción previamente realizada, asegurando que
     * los valores asociados sean restaurados correctamente. Verifica que la transacción
     * no haya sido procesada antes y que los valores sean consistentes.
     *
     * @param string $gameId         Identificador del juego.
     * @param string $ticketId       Identificador del ticket.
     * @param string $uid            Identificador único del usuario.
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $transactionId  Identificador de la transacción.
     * @param array  $datos          Datos adicionales de la transacción.
     *
     * @return string XML con la respuesta de la transacción.
     * @throws Exception Si hay errores en la transacción o el usuario está inactivo.
     */
    public function Rollback($gameId, $ticketId, $uid, $rollbackAmount, $transactionId, $datos)
    {
        $this->method = 'cancelReserve';

        $this->ticketIdGlobal = $ticketId;

        $this->data = $datos;

        try {
            //  Obtenemos el Proveedor con el abreviado PLAYNGO
            $Proveedor = new Proveedor("", "PLAYNGO");

            //  Creamos la Transaccion API  
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($rollbackAmount);

            //  Obtenemos el Usuario Token con el token 
            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            //  Obtenemos el Usuario Mandante con el Usuario Token 
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $this->transaccionApi->setIdentificador("PLAYNGO" . $ticketId);

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

            //  Creamos la Transaccion por el Juego  
            $TransaccionJuego = new TransaccionJuego("", "PLAYNGO" . $ticketId);


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

                    //  Verificamos que el valor del ticket sea igual al valor del Rollback  
                    if ($TransaccionJuego->getValorTicket() != $rollbackAmount) {
                        throw new Exception("Valor ticket diferente al Rollback", "10003");
                    }


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
                    $TransjuegoLog->setValor($rollbackAmount);

                    $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);


                    //  Obtenemos el Usuario para hacerle el credito
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                    $Usuario->credit($TransaccionJuego->getValorTicket(), $Transaction);

                    // Commit de la transacción
                    $Transaction->commit();

                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                    $Balance = (($Usuario->getBalance()));

                    //  Retornamos el mensaje satisfactorio  
                    $PKT = new SimpleXMLElement("<PKT></PKT>");

                    $Result = $PKT->addChild('Result');
                    $Result->addAttribute('Name', $this->method);
                    $Result->addAttribute('Success', '1');

                    $Returnset = $Result->addChild('Returnset');

                    $Token = $Returnset->addChild('Token');
                    $Token->addAttribute('Type', 'string');
                    $token = $UsuarioToken->getToken();
                    $Token->addAttribute('Value', "$token");

                    $Balancex = $Returnset->addChild('Balance');
                    $Balancex->addAttribute('Type', 'string');
                    $Balancex->addAttribute('Value', "$Balance");

                    $ExtTransactionID = $Returnset->addChild('ExtTransactionID');
                    $ExtTransactionID->addAttribute('Type', 'long');
                    $ExtTransactionID->addAttribute('Value', "$TransjuegoLog_id");

                    $ExtTransactionID = $Returnset->addChild('AlreadyProcessed');
                    $ExtTransactionID->addAttribute('Type', 'bool');
                    $ExtTransactionID->addAttribute('Value', "false");
                    $respuesta = $PKT->asXML();

                    //  Guardamos la Transaccion Api necesaria de estado OK   
                    $this->transaccionApi->setRespuestaCodigo("OK");
                    $this->transaccionApi->setRespuesta(json_encode($respuesta));
                    $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                    $TransaccionApiMySqlDAO->insert($this->transaccionApi);
                    $TransaccionApiMySqlDAO->getTransaction()->commit();

                    return $respuesta;
                }
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para realizar un crédito en la cuenta del usuario.
     *
     * @param string  $gameId        Identificador del juego.
     * @param string  $ticketId      Identificador del ticket.
     * @param string  $uid           Identificador único del usuario.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $transactionId Identificador de la transacción.
     * @param boolean $isEndRound    Indica si es el final de la ronda.
     * @param array   $datos         Datos adicionales de la transacción.
     *
     * @return string XML con la respuesta de la transacción.
     * @throws Exception Si hay errores en la transacción o el usuario está inactivo.
     */
    public function Credit($gameId, $ticketId, $uid, $creditAmount, $transactionId, $isEndRound, $datos)
    {
        $this->method = 'release';

        $this->ticketIdGlobal = $ticketId;


        $this->data = $datos;

        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }
            //  Obtenemos el Proveedor con el abreviado PLAYNGO 
            $Proveedor = new Proveedor("", "PLAYNGO");

            //  Creamos la Transaccion API  
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);


            if ($this->token != "") {
                //  Obtenemos el Usuario Token con el token 
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                //  Obtenemos el Usuario Mandante con el Usuario Token 
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                //  Obtenemos el Usuario Mandante con el Usuario Token 
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            $this->transaccionApi->setIdentificador("PLAYNGO" . $ticketId);

            //  Obtenemos el producto con el gameId  */
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
            $TransaccionJuego = new TransaccionJuego("", "PLAYNGO" . $ticketId);

            //  Obtenemos el mandante para verificar sus caracteristicas  
            $Mandante = new Mandante($UsuarioMandante->mandante);


            //  Verificamos si el mandante es propio  
            if ($Mandante->propio == "S") {
                //  Obtenemos nuestro Usuario y hacemos el debito
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                if ($Usuario->estado != "A") {
                    throw new Exception("Usuario Inactivo", "20003");
                }

                // Obtenemos la Transaccion de la BD para crear y registrar el Debito con esta transaccion 
                $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
                $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

                //  Verificamos que la Transaccion si este conectada y lista para usarse  
                if ($Transaction->isIsconnected()) {
                    //  Obtenemos el ID de la TransaccionJuego
                    $TransJuegoId = $TransaccionJuego->getTransjuegoId();

                    //  Obtenemos el tipo de transacción dependiendo a betTypeID y si se le suma los creditos o no 
                    $sumaCreditos = false;
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

                    if ( ! $TransjuegoLog->isEqualsNewCredit()) {
                    }

                    //  Actualizamos la Transaccion Juego con los respectivas actualizaciones  
                    $TransaccionJuego->setValorPremio($TransaccionJuego->getValorPremio() + $creditAmount);
                    if ($isEndRound) {
                        if ($TransaccionJuego->getValorPremio() > 0) {
                            $TransaccionJuego->setPremiado("S");
                            $TransaccionJuego->setFechaPago(date('Y-m-d H:i:s', time()));
                            $sumaCreditos = true;
                        }
                        $TransaccionJuego->setEstado("I");
                    }
                    $TransaccionJuego->update($Transaction);

                    if ($creditAmount > 0) {
                        $sumaCreditos = true;
                    }

                    //  Si suma los creditos, hacemos el respectivo CREDIT  
                    if ($sumaCreditos) {
                        if ($creditAmount > 0) {
                            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                            $Usuario->creditWin($creditAmount, $Transaction);
                        }
                    }

                    // Commit de la transacción
                    $Transaction->commit();

                    //  Consultamos de nuevo el usuario para obtener el saldo  
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                    $Balance = (($Usuario->getBalance()));

                    //  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  
                    $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                    //  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  
                    $data = $Usuario->getWSMessage($UsuarioToken->getRequestId());
                    $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                    $WebsocketUsuario->sendWSMessage();

                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->getUsumandanteId());

                    //  Retornamos el mensaje satisfactorio  
                    $PKT = new SimpleXMLElement("<release></release>");

                    $PKT->addChild('externalTransactionId', $TransjuegoLog_id);
                    $PKT->addChild('real', $Balance);
                    $PKT->addChild('currency', $UsuarioMandante->getMoneda());
                    $PKT->addChild('statusCode', 0);

                    $respuesta = $PKT->asXML();


                    //  Guardamos la Transaccion Api necesaria de estado OK   
                    $this->transaccionApi->setRespuestaCodigo("OK");
                    $this->transaccionApi->setRespuesta(json_encode($respuesta));
                    $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                    $TransaccionApiMySqlDAO->insert($this->transaccionApi);
                    $TransaccionApiMySqlDAO->getTransaction()->commit();

                    return $respuesta;
                }
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para convertir errores en respuestas XML.
     *
     * @param integer $code    Código de error.
     * @param string  $message Mensaje de error.
     *
     * @return string XML con la respuesta de error.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $PKT = new SimpleXMLElement("<" . $this->method . "></" . $this->method . ">");

        $Proveedor = new Proveedor("", "PLAYNGO");


        if ($this->transaccionApi != null) {
            $tipo = $this->transaccionApi->getTipo();
        }

        switch ($code) {
            case 10011:
                $codeProveedor = 10;
                $messageProveedor = "SESSIONEXPIRED";
                break;

            case 21:
                $codeProveedor = 10;
                $messageProveedor = "SESSIONEXPIRED";
                break;

            case 20002:
                $codeProveedor = 4;
                $messageProveedor = "WRONGUSERNAMEPASSWORD";
                break;

            case 10013:
                $codeProveedor = 1;
                $messageProveedor = "NOUSER";
                break;

            case 22:
                $codeProveedor = 1;
                $messageProveedor = "NOUSER";
                break;

            case 20001:
                $codeProveedor = 7;
                $messageProveedor = "insufficient funds";
                break;

            case 20003:
                $codeProveedor = 6;
                $messageProveedor = "ACCOUNTDISABLED";
                break;

            case 0:
                $codeProveedor = 2;
                $messageProveedor = "INTERNAL";
                break;

            case 27:
                $codeProveedor = 2;
                $messageProveedor = "INTERNAL";
                break;

            case 28:

                $codeProveedor = 2;
                $messageProveedor = "INTERNAL";

                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                $Mandante = new Mandante($UsuarioMandante->mandante);

                if ($Mandante->propio == "S") {
                    $token = $UsuarioToken->getToken();
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $Balance = $Usuario->getBalance();


                    //  Retornamos el mensaje satisfactorio
                    $PKT = new SimpleXMLElement("<" . $this->method . "></" . $this->method . ">");

                    $PKT->addChild('externalTransactionId', '');
                    $PKT->addChild('real', $Balance);
                    $PKT->addChild('currency', $UsuarioMandante->getMoneda());
                    $PKT->addChild('statusCode', 0);
                    $PKT->addChild('statusMessage', 'ok');
                }

                break;

            case 29:
                $codeProveedor = 2;
                $messageProveedor = "INTERNAL";
                break;

            case 10001:

                $codeProveedor = 2;
                $messageProveedor = "INTERNAL";

                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                $Mandante = new Mandante($UsuarioMandante->mandante);

                if ($Mandante->propio == "S") {
                    $token = $UsuarioToken->getToken();
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $Balance = $Usuario->getBalance();


                    $tipo = $this->transaccionApi->getTipo();
                    $TransaccionJuego = new TransaccionJuego("", "PLAYNGO" . $this->ticketIdGlobal, "");
                    $TransjuegoLog = new TransjuegoLog("", $TransaccionJuego->getTransjuegoId(), $tipo);


                    //  Retornamos el mensaje satisfactorio
                    $PKT = new SimpleXMLElement("<reserve></reserve>");

                    $PKT->addChild('externalTransactionId', $TransjuegoLog->getTransjuegologId());
                    $PKT->addChild('real', $Balance);
                    $PKT->addChild('currency', $UsuarioMandante->getMoneda());
                    $PKT->addChild('statusCode', 0);
                    $PKT->addChild('statusMessage', 'ok');
                }


                break;

            case 10004:
                $codeProveedor = 2;
                $messageProveedor = "INTERNAL";
                break;

            case 10014:
                $codeProveedor = 2;
                $messageProveedor = "INTERNAL";
                break;

            case 20005:
                $codeProveedor = 5;
                $messageProveedor = "ACCOUNTLOCKED";
                break;

            case 20006:
                $codeProveedor = 5;
                $messageProveedor = "ACCOUNTLOCKED";
                break;

            case 20007:
                $codeProveedor = 5;
                $messageProveedor = "ACCOUNTLOCKED";
                break;

            default:
                $codeProveedor = 2;
                $messageProveedor = "INTERNAL";
                break;
        }

        if ($code != 10001 && $code != 28) {
            $PKT->addChild('statusCode', $codeProveedor);
            $PKT->addChild('statusMessage', $messageProveedor);
            //$PKT->addChild('statusMessage2',$message);
            $PKT->addChild('real', 0);
            $PKT->addChild('currency', "PEN");
        }


        if ($this->transaccionApi != null) {
            $Text = $PKT->asXML();
            $this->transaccionApi->setTipo("R" . $this->transaccionApi->getTipo());
            $this->transaccionApi->setRespuestaCodigo("ERROR");
            $this->transaccionApi->setRespuesta($Text);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->insert($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();
        }


        return $PKT->asXML();
    }


}