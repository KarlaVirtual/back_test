<?php

/**
 * Clase Playngo para la integración con el proveedor de juegos PLAYNGO.
 * Descripción: Esta clase maneja la autenticación, balance, débitos, créditos y reversión de transacciones
 * con el proveedor de juegos PLAYNGO. Implementa métodos para interactuar con la API del proveedor y
 * gestionar las transacciones relacionadas con los usuarios.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */


namespace Backend\integrations\casino;

use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
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
 * Esta clase se encarga de la integración con el proveedor de juegos PLAYNGO,
 * proporcionando métodos para la autenticación, manejo de balance, débitos,
 * créditos y reversión de transacciones.
 */
class Playngo
{
    /**
     * Nombre de usuario para la autenticación.
     *
     * @var string
     */
    private $Login;

    /**
     * Contraseña para la autenticación.
     *
     * @var string
     */
    private $Password;

    /**
     * Token de autenticación.
     *
     * @var string
     */
    private $token;

    /**
     * Firma utilizada para la validación.
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
     * Objeto para manejar transacciones de la API.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * Datos adicionales para las operaciones.
     *
     * @var array
     */
    private $data;

    /**
     * Método a ejecutar en la operación.
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
     * Identificador del juego.
     *
     * @var string
     */
    private $gameId;

    /**
     * Versión de la integración.
     *
     * @var string
     */
    private $version;

    /**
     * Constructor de la clase Playngo.
     *
     * @param string $token      Token de autenticación.
     * @param string $uid        Identificador único del usuario (opcional).
     * @param string $externalId Identificador externo del usuario (opcional).
     * @param string $method     Método a ejecutar (opcional).
     * @param string $gameId     Identificador del juego (opcional).
     */
    public function __construct($token, $uid = "", $externalId = "", $method = "", $gameId = "")
    {
        $this->token = $token;
        $this->sign = $uid;
        $this->externalId = $externalId;
        $this->gameId = $gameId;

        $this->method = $method;

        $ConfigurationEnvironment = new ConfigurationEnvironment();

        try {
            $Proveedor = new Proveedor("", "PLAYNGO");
            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
        } catch (Exception $e) {
            $UsuarioMandante = new UsuarioMandante($this->externalId);
        }
        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->version = '2';
            $this->signOriginal = "stagestagestagestage";
        } else {
            if ($UsuarioMandante->paisId == 173) {
                $this->signOriginal = "wtQNQTljebyFWKGgEljAawgkc";
            }
        }

        if ($this->sign != $this->signOriginal && false) {
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }
    }

    /**
     * Método para autenticar al usuario con el proveedor PLAYNGO.
     *
     * @return string XML con la respuesta de autenticación.
     */
    public function Auth()
    {
        $this->method = 'authenticate';
        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }
            $Proveedor = new Proveedor("", "PLAYNGO");


            if ($this->version == "2") {
                if ($this->token != "") {
                    $Producto = new Producto("", $this->gameId, $Proveedor->getProveedorId());

                    //  Obtenemos el Usuario Token con el token
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId(), "", "", "", $Producto->productoId);
                    //  Obtenemos el Usuario Mandante con el Usuario Token
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } else {
                    //  Obtenemos el Usuario Mandante con el Usuario Token
                    $UsuarioMandante = new UsuarioMandante($this->externalId);
                }
            } else {
                if ($this->token != "") {
                    //  Obtenemos el Usuario Token con el token
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    //  Obtenemos el Usuario Mandante con el Usuario Token
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } else {
                    //  Obtenemos el Usuario Mandante con el Usuario Token
                    $UsuarioMandante = new UsuarioMandante($this->externalId);
                }
            }

            $Game = new Game();

            $responseG = $Game->autenticate($UsuarioMandante);


            $PKT = new SimpleXMLElement("<authenticate></authenticate>");

            $PKT->addChild('externalId', $responseG->usuarioId);
            $PKT->addChild('statusCode', 0);
            $PKT->addChild('statusMessage', 'ok');
            $PKT->addChild('userCurrency', $responseG->moneda);
            $PKT->addChild('nickname', $responseG->usuario);
            $PKT->addChild('country', $responseG->paisIso2);
            $PKT->addChild('birthdate', '1970-01-01');
            $PKT->addChild('registration', '2010-05-05');
            $PKT->addChild('language', 'ES');


            $Mandante = new Mandante($UsuarioMandante->mandante);
            $market = $Mandante->nombre;

            if (strpos($market, '.') !== false) { // Solo aplicar explode si hay un punto
                $market = explode(".", $market);
            }
            $market_label = is_array($market) ? $market[0] : $market;


            if ($this->version == "2") {
                $PKT->addChild('affiliateId', $market_label);
            } else {
                $PKT->addChild('affiliateId', $market_label);
            }

            $PKT->addChild('real', $responseG->saldo);
            $PKT->addChild('externalGameSessionId', '');
            $PKT->addChild('region', '3');
            $PKT->addChild('gender', 'm');


            return $PKT->asXML();
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
     */
    public function getBalance($gameId)
    {
        $this->method = 'balance';
        try {
            $Proveedor = new Proveedor("", "PLAYNGO");

            if ($this->version == "2") {
                if ($this->token != "") {
                    $Producto = new Producto("", $this->gameId, $Proveedor->getProveedorId());

                    //  Obtenemos el Usuario Token con el token
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId(), "", "", "", $Producto->productoId);
                    //  Obtenemos el Usuario Mandante con el Usuario Token
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } else {
                    if (intval($this->externalId) == 0) {
                        throw new Exception("Token vacio", "10011");
                    }
                    //  Obtenemos el Usuario Mandante con el Usuario Token
                    $UsuarioMandante = new UsuarioMandante($this->externalId);
                }
            } else {
                if ($this->token != "") {
                    //  Obtenemos el Usuario Token con el token
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    //  Obtenemos el Usuario Mandante con el Usuario Token
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } else {
                    //  Obtenemos el Usuario Mandante con el Usuario Token
                    $UsuarioMandante = new UsuarioMandante($this->externalId);
                }
            }


            //  Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            try {
            } catch (Exception $e) {
                if ($e->getCode() != 49) {
                    throw $e;
                }
            }

            $Game = new Game();

            $responseG = $Game->getBalance($UsuarioMandante);

            $PKT = new SimpleXMLElement("<balance></balance>");
            $PKT->addChild('real', $responseG->saldo);

            $PKT->addChild('statusCode', 0);
            $PKT->addChild('statusMessage', 'ok');
            return $PKT->asXML();
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para realizar un débito en la cuenta del usuario.
     *
     * @param string  $gameId        Identificador del juego.
     * @param string  $ticketId      Identificador del ticket.
     * @param string  $uid           Identificador único del usuario.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $transactionId Identificador de la transacción.
     * @param array   $datos         Datos adicionales de la transacción.
     * @param boolean $freespin      Indica si es un giro gratis (opcional).
     *
     * @return string XML con la respuesta del débito.
     */
    public function Debit($gameId, $ticketId, $uid, $debitAmount, $transactionId, $datos, $freespin = false)
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


            if ($this->version == "2") {
                if ($this->token != "") {
                    $Producto = new Producto("", $this->gameId, $Proveedor->getProveedorId());

                    //  Obtenemos el Usuario Token con el token
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId(), "", "", "", $Producto->productoId);
                    //  Obtenemos el Usuario Mandante con el Usuario Token
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } else {
                    //  Obtenemos el Usuario Mandante con el Usuario Token
                    $UsuarioMandante = new UsuarioMandante($this->externalId);
                }
            } else {
                if ($this->token != "") {
                    //  Obtenemos el Usuario Token con el token
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    //  Obtenemos el Usuario Mandante con el Usuario Token
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } else {
                    //  Obtenemos el Usuario Mandante con el Usuario Token
                    $UsuarioMandante = new UsuarioMandante($this->externalId);
                }
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
            $this->transaccionApi->setIdentificador("PLAYNGO" . $ticketId);

            $Game = new Game();


            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin);

            $this->transaccionApi = $responseG->transaccionApi;


            //  Retornamos el mensaje satisfactorio
            $PKT = new SimpleXMLElement("<reserve></reserve>");

            $PKT->addChild('externalTransactionId', $responseG->transaccionId);
            $PKT->addChild('real', $responseG->saldo);
            $PKT->addChild('currency', $responseG->moneda);
            $PKT->addChild('statusCode', 0);
            $PKT->addChild('statusMessage', 'ok');

            $respuesta = $PKT->asXML();

            //  Guardamos la Transaccion Api necesaria de estado OK
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();


            return $PKT->asXML();
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para realizar una reversión de una transacción.
     *
     * @param string $gameId         Identificador del juego.
     * @param string $ticketId       Identificador del ticket.
     * @param string $uid            Identificador único del usuario.
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $transactionId  Identificador de la transacción.
     * @param array  $datos          Datos adicionales de la transacción.
     *
     * @return string XML con la respuesta de la reversión.
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

            $this->transaccionApi->setIdentificador("PLAYNGO" . $ticketId);

            if ($this->version == '2') {
                $identificador = "";
                try {
                    $TransaccionJuego = new TransaccionJuego("", "PLAYNGO" . $ticketId, "");

                    $this->transaccionApi->setTransaccionId('ROLLBACK' . $TransaccionJuego->getTransaccionId());

                    $ProductoMandante = new ProductoMandante("", "", $TransaccionJuego->getProductoId());

                    //  Obtenemos el Usuario Mandante con el Usuario Token
                    $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());

                    //  Obtenemos el Usuario Token con el token
                    $UsuarioToken = new UsuarioToken("", "", $UsuarioMandante->usumandanteId, "", "", $ProductoMandante->productoId);
                } catch (Exception $e) {
                    throw new Exception("Transaccion no existe", "10005");
                }
            } else {
                try {
                    $TransaccionJuego = new TransaccionJuego("", "PLAYNGO" . $ticketId, "");

                    $this->transaccionApi->setTransaccionId('ROLLBACK' . $TransaccionJuego->getTransaccionId());

                    $ProductoMandante = new ProductoMandante("", "", $TransaccionJuego->getProductoId());

                    //  Obtenemos el Usuario Mandante con el Usuario Token
                    $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());

                    //  Obtenemos el Usuario Token con el token
                    //$UsuarioToken = new UsuarioToken("","",$UsuarioMandante->usumandanteId,"","",$ProductoMandante->productoId);

                } catch (Exception $e) {
                    throw new Exception("Transaccion no existe", "10005");
                }
            }
            $this->transaccionApi->setProductoId($TransaccionJuego->getProductoId());

            $Game = new Game();

            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false, '', true, false, true);

            $this->transaccionApi = $responseG->transaccionApi;


            //  Retornamos el mensaje satisfactorio
            $PKT = new SimpleXMLElement("<cancelReserve></cancelReserve>");

            $PKT->addChild('externalTransactionId', $responseG->transaccionId);
            $PKT->addChild('statusCode', 0);

            $respuesta = $PKT->asXML();

            //  Guardamos la Transaccion Api necesaria de estado OK
            $this->transaccionApi->setRespuesta(json_encode($respuesta));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $respuesta;
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
     * @return string XML con la respuesta del crédito.
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
            $this->transaccionApi->setIdentificador("PLAYNGO" . $ticketId);


            if ($this->version == "2") {
                try {
                    $TransaccionJuego = new TransaccionJuego("", "PLAYNGO" . $ticketId);
                    $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                } catch (Exception $e) {
                    throw new Exception("Transaccion no existe", "10005");
                }
            } else {
                try {
                    $TransaccionJuego = new TransaccionJuego("", "PLAYNGO" . $ticketId);
                    $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                } catch (Exception $e) {
                    try {
                        $UsuarioMandante = new UsuarioMandante($this->externalId);
                    } catch (Exception $e) {
                        throw new Exception("Transaccion no existe", "10005");
                    }

                    throw new Exception("Transaccion no existe", "10005");
                }
            }

            $ProductoMandante = new ProductoMandante("", "", $TransaccionJuego->productoId);
            /*  Obtenemos el producto con el $TransaccionJuego->productoId */
            $Producto = new Producto($ProductoMandante->productoId);

            $Game = new Game();

            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $isEndRound);

            $this->transaccionApi = $responseG->transaccionApi;


            //  Retornamos el mensaje satisfactorio


            $PKT = new SimpleXMLElement("<release></release>");

            $PKT->addChild('externalTransactionId', $responseG->transaccionId);
            $PKT->addChild('real', $responseG->saldo);
            $PKT->addChild('currency', $responseG->moneda);
            $PKT->addChild('statusCode', 0);
            $PKT->addChild('statusMessage', 'ok');

            $respuesta = $PKT->asXML();


            //  Guardamos la Transaccion Api necesaria de estado OK
            $this->transaccionApi->setRespuesta(json_encode($respuesta));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $PKT->asXML();
        } catch (Exception $e) {
            if ($e->getCode() == "28") {
                $this->Debit($gameId, $ticketId, "", 0, "FS" . $transactionId, $datos, false);
                return $this->Credit($gameId, $ticketId, $uid, $creditAmount, $transactionId, $isEndRound, $datos);
            }

            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para convertir errores en respuestas XML.
     *
     * @param integer $code    Código del error.
     * @param string  $message Mensaje del error.
     *
     * @return string XML con la respuesta del error.
     */
    public function convertError($code, $message)
    {
        //syslog(10,'PLAYNGOERROR ' . $code . ' ' . ($message));
        try {
            $log = "";
            $log = $log . "/" . time();

            $log = $log . "\r\n" . "-------------------------" . "\r\n";
            $log = $log . ($message);

            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);
        } catch (Exception $e) {
        }

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
                $codeProveedor = 4;
                $messageProveedor = "WRONGUSERNAMEPASSWORD";
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
                $messageProveedor = "NOTENOUGHMONEY";
                break;


            case 20003:
                $codeProveedor = 6;
                $messageProveedor = "ACCOUNTDISABLED";
                break;
            case 20024:
                $codeProveedor = 5;
                $messageProveedor = "ACCOUNTLOCKED";
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


                if ($this->version == "2") {
                    if ($this->token != "") {
                        $Producto = new Producto("", $this->gameId, $Proveedor->getProveedorId());

                        //  Obtenemos el Usuario Token con el token
                        $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId(), "", "", "", $Producto->productoId);
                        //  Obtenemos el Usuario Mandante con el Usuario Token
                        $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                    } else {
                        //  Obtenemos el Usuario Mandante con el Usuario Token
                        $UsuarioMandante = new UsuarioMandante($this->externalId);
                    }
                } else {
                    if ($this->token != "") {
                        //  Obtenemos el Usuario Token con el token
                        $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                        //  Obtenemos el Usuario Mandante con el Usuario Token
                        $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                    } else {
                        //  Obtenemos el Usuario Mandante con el Usuario Token
                        $UsuarioMandante = new UsuarioMandante($this->externalId);
                    }
                }


                $Game = new Game();

                $responseG = $Game->getBalance($UsuarioMandante);

                //  Retornamos el mensaje satisfactorio
                $PKT = new SimpleXMLElement("<" . $this->method . "></" . $this->method . ">");

                $PKT->addChild('externalTransactionId', '');
                $PKT->addChild('real', $responseG->saldo);
                $PKT->addChild('currency', $responseG->moneda);
                $PKT->addChild('statusCode', 0);
                $PKT->addChild('statusMessage', 'ok');


                break;
            case 29:
                $codeProveedor = 2;
                $messageProveedor = "INTERNAL";


                break;

            case 10001:

                $codeProveedor = 2;
                $messageProveedor = "INTERNAL";


                if ($this->version == "2") {
                    if ($this->token != "") {
                        $Producto = new Producto("", $this->gameId, $Proveedor->getProveedorId());

                        //  Obtenemos el Usuario Token con el token
                        $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId(), "", "", "", $Producto->productoId);
                        //  Obtenemos el Usuario Mandante con el Usuario Token
                        $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                    } else {
                        //  Obtenemos el Usuario Mandante con el Usuario Token
                        $UsuarioMandante = new UsuarioMandante($this->externalId);
                    }
                } else {
                    if ($this->token != "") {
                        //  Obtenemos el Usuario Token con el token
                        $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                        //  Obtenemos el Usuario Mandante con el Usuario Token
                        $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                    } else {
                        //  Obtenemos el Usuario Mandante con el Usuario Token
                        $UsuarioMandante = new UsuarioMandante($this->externalId);
                    }
                }


                $Game = new Game();

                $responseG = $Game->getBalance($UsuarioMandante);

                $tipo = $this->transaccionApi->getTipo();
                $TransaccionJuego = new TransaccionJuego("", "PLAYNGO" . $this->ticketIdGlobal, "");

                $TransjuegoLog = new TransjuegoLog("", $TransaccionJuego->getTransjuegoId(), $tipo);


                //  Retornamos el mensaje satisfactorio
                $PKT = new SimpleXMLElement("<" . $this->method . "></" . $this->method . ">");

                $PKT->addChild('externalTransactionId', $TransjuegoLog->getTransjuegologId());
                $PKT->addChild('real', $responseG->saldo);
                $PKT->addChild('currency', $responseG->moneda);
                $PKT->addChild('statusCode', 0);
                $PKT->addChild('statusMessage', 'ok');


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

            case 10005:
                $codeProveedor = 0;
                $messageProveedor = "ok";


                break;

            default:
                $codeProveedor = 2;
                $messageProveedor = "";


                break;
        }
        $PKT->addChild('statusMessage2', $code);


        if ($code != 10001 && $code != 28) {
            $PKT->addChild('statusCode', $codeProveedor);
            $PKT->addChild('statusMessage', $messageProveedor);
            //$PKT->addChild('statusMessage2',$message);
            $PKT->addChild('real', 0);
            //$PKT->addChild('currency',"PEN");

        }


        if ($this->transaccionApi != null) {
            $Text = $PKT->asXML();
            $this->transaccionApi->setTipo("R" . $this->transaccionApi->getTipo());
            $this->transaccionApi->setRespuestaCodigo("ERROR_" . $code);
            $this->transaccionApi->setRespuesta($Text);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->insert($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();
        }


        return $PKT->asXML();
    }


}
