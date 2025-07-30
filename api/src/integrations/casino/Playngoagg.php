<?php

/**
 * Clase `Playngoagg` para la integración con el proveedor de juegos PLAYNGO.
 *
 * Esta clase contiene métodos para manejar la autenticación, balance, débitos, créditos,
 * reversión de transacciones y manejo de errores en la integración con el proveedor.
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
 * Clase `Playngoagg`.
 *
 * Esta clase maneja la integración con el proveedor de juegos PLAYNGO,
 * proporcionando métodos para autenticación, balance, débitos, créditos,
 * reversión de transacciones y manejo de errores.
 */
class Playngoagg
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
     * Firma utilizada para la autenticación.
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
     * Datos adicionales utilizados en las transacciones.
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
     * Constructor de la clase `Playngoagg`.
     *
     * @param string $token      Token de autenticación.
     * @param string $uid        Identificador único del usuario (opcional).
     * @param string $externalId Identificador externo del usuario (opcional).
     * @param string $method     Método a ejecutar (opcional).
     * @param string $gameId     Identificador del juego (opcional).
     * @param string $version    Versión de la integración (opcional).
     */
    public function __construct($token, $uid = "", $externalId = "", $method = "", $gameId = "", $version = "")
    {
        $this->token = $token;
        $this->sign = $uid;
        $this->externalId = $externalId;
        $this->gameId = $gameId;

        $this->method = $method;
        $this->version = $version;

        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->version = '2';
        }

        if ( ! $ConfigurationEnvironment->isDevelopment()) {
        }
    }

    /**
     * Método para autenticar al usuario con el proveedor.
     *
     * @return string XML con la respuesta de autenticación.
     * @throws Exception Si ocurre un error durante la autenticación.
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
                    $Producto = new Producto("", $this->gameId, $Proveedor->getProveedorId());

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

            if ($this->version == "2") {
                $PKT->addChild('affiliateId', $UsuarioMandante->getMandante());
            } else {
                $PKT->addChild('affiliateId', '');
            }
            $PKT->addChild('real', $responseG->saldo);
            $PKT->addChild('externalGameSessionId', '');
            $PKT->addChild('region', '3');
            $PKT->addChild('gender', 'm');

            $PKT->addChild('gameId', $Producto->getExternoId());

            if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X2') {
                return 'hola' . $PKT->asXML();
            }

            return $PKT->asXML();
        } catch (Exception $e) {
            if ($_ENV['debug']) {
                print_r($e);
            }
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para obtener el balance del usuario.
     *
     * @param string $gameId Identificador del juego.
     *
     * @return string XML con el balance del usuario.
     * @throws Exception Si ocurre un error al obtener el balance.
     */
    public function getBalance($gameId)
    {
        $this->method = 'balance';
        try {
            $Proveedor = new Proveedor("", "PLAYNGO");

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
     * @throws Exception Si ocurre un error durante el débito.
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
            if ($_ENV['debug']) {
                print_r($e);
            }

            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para revertir una transacción (rollback).
     *
     * @param string $gameId         Identificador del juego.
     * @param string $ticketId       Identificador del ticket.
     * @param string $uid            Identificador único del usuario.
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $transactionId  Identificador de la transacción.
     * @param array  $datos          Datos adicionales de la transacción.
     *
     * @return string XML con la respuesta de la reversión.
     * @throws Exception Si ocurre un error durante la reversión.
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
            }


            //  Obtenemos el producto con el gameId
            // $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());


            $Game = new Game();

            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi);

            $this->transaccionApi = $responseG->transaccionApi;


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
     * @throws Exception Si ocurre un error durante el crédito.
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

            /*  Obtenemos el producto con el gameId  */
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

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
            if ($_ENV['debug']) {
                print_r($e);
                exit();
            }
            if ($e->getCode() == "28") {
                try {
                    $this->Debit($gameId, $ticketId, "", 0, "FS" . $transactionId, $datos, false);
                    return $this->Credit($gameId, $ticketId, $uid, $creditAmount, $transactionId, $isEndRound, $datos);
                } catch (Exception $e) {
                    return $this->convertError($e->getCode(), $e->getMessage());
                }
            }

            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para convertir errores en respuestas manejables.
     *
     * @param integer $code    Código del error.
     * @param string  $message Mensaje del error.
     *
     * @return string XML con la respuesta del error.
     */
    public function convertError($code, $message)
    {
        if ($_REQUEST['isDebug'] == '1') {
            print_r($code);
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

            case '0':


                try {
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
                } catch (Exception $e) {
                    $codeProveedor = 2;
                    $messageProveedor = "";
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
            $PKT->addChild('real', 0);

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