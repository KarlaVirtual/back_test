<?php

/**
 * Clase `GamesGlobal` para la integración con el proveedor de juegos GAMESGLOBAL.
 *
 * Este archivo contiene la implementación de la clase `GamesGlobal`, que incluye métodos
 * para manejar la autenticación, balance, transacciones de débito, crédito, rollback,
 * y otros procesos relacionados con la integración de juegos.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;

use Exception;
use Backend\dto\Pais;
use SimpleXMLElement;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Categoria;
use Backend\dto\Proveedor;
use Backend\dto\Clasificador;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioToken;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransaccionApi;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\ProductoMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\CategoriaProducto;
use Backend\dto\TransaccionProducto;
use Backend\dto\UsuarioConfiguracion;
use Backend\websocket\WebsocketUsuario;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionApiMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;

/**
 * Clase `GamesGlobal`.
 *
 * Esta clase implementa la integración con el proveedor de juegos GAMESGLOBAL,
 * proporcionando métodos para manejar autenticación, balance, transacciones
 * de débito, crédito, rollback, y otros procesos relacionados.
 */
class GamesGlobal
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
    private $password;

    /**
     * Nombre de usuario para autenticación con Microgaming (versión 2).
     *
     * @var string
     */
    private $LoginMicrogaming2 = "rgil";

    /**
     * Contraseña para autenticación con Microgaming (versión 2).
     *
     * @var string
     */
    private $passwordMicrogaming2 = "rgip";

    /**
     * Nombre de usuario para autenticación con Microgaming.
     *
     * @var string
     */
    private $LoginMicrogaming = "microgaming";

    /**
     * Contraseña para autenticación con Microgaming.
     *
     * @var string
     */
    private $passwordMicrogaming = "m1cr0gam1ng";

    /**
     * Secuencia de la transacción.
     *
     * @var string
     */
    private $seq;

    /**
     * Token de autenticación.
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
     * Objeto de transacción API.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * Datos adicionales de la transacción.
     *
     * @var mixed
     */
    private $data;

    /**
     * Método actual en ejecución.
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
     * Constructor de la clase `GamesGlobal`.
     *
     * @param string $seq      Secuencia de la transacción.
     * @param string $token    Token de autenticación.
     * @param string $Login    Nombre de usuario para autenticación.
     * @param string $Password Contraseña para autenticación.
     */
    public function __construct($seq, $token, $Login, $Password)
    {
        $this->seq = $seq;
        $this->token = strval($token);
        $this->Login = $Login;
        $this->password = $Password;
    }

    /**
     * Verifica las credenciales de inicio de sesión.
     *
     * @return void
     * @throws Exception Si las credenciales son incorrectas.
     */
    public function checkLogin()
    {
        if ($this->Login != $this->LoginMicrogaming2 || $this->password != $this->passwordMicrogaming2) {
            throw new Exception("Error Login API", "10020");
        }
    }

    /**
     * Autentica al usuario y genera una respuesta XML con los datos del usuario.
     *
     * @return string Respuesta en formato XML.
     * @throws Exception Si ocurre un error durante la autenticación.
     */
    public function Auth()
    {
        $this->method = 'login';

        try {
            $this->checkLogin();
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }
            $Proveedor = new Proveedor("", "GAMESGLOBAL");

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

            $Producto = new Producto($UsuarioToken->productoId);
            $Subproveedor = new Subproveedor($Producto->subproveedorId);
            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante, $Subproveedor->subproveedorId, $UsuarioToken->productoId);

            $PKT = new SimpleXMLElement("<pkt></pkt>");

            $methodresponse = $PKT->addChild('methodresponse');
            $methodresponse->addAttribute('name', $this->method);
            $methodresponse->addAttribute('timestamp', date('Y/m/d H:i:s') . ".000");

            $Result = $methodresponse->addChild('result');

            if ($responseG->paisIso2 == "PE") {
                $PaisIso = "PER";
            }

            if ($responseG->paisIso2 == "EC") {
                $PaisIso = "ECU";
            }
            $Result->addAttribute('seq', $this->seq);
            $Result->addAttribute('token', $UsuarioToken->getToken());
            $Result->addAttribute('loginname', $responseG->usuario);
            $Result->addAttribute('currency', $responseG->moneda);
            $Result->addAttribute('country', $PaisIso);
            $Result->addAttribute('city', $responseG->paisIso2);
            $Result->addAttribute('balance', intval(round($responseG->saldo, 2) * 100));
            $Result->addAttribute('bonusbalance', "0");

            $extinfo = $Result->addChild('extinfo');


            return $PKT->asXML();
        } catch (Exception $e) {
            //print_r($e);
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }


    /**
     * Obtiene el balance del usuario autenticado.
     *
     * @return string Respuesta en formato XML con el balance del usuario.
     * @throws Exception Si ocurre un error al obtener el balance.
     */
    public function getBalance()
    {
        $this->method = 'getbalance';
        try {
            $this->checkLogin();
            $Proveedor = new Proveedor("", "GAMESGLOBAL");

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

            $Game = new Game();
            $Producto = new Producto($UsuarioToken->productoId);
            $Subproveedor = new Subproveedor($Producto->subproveedorId);
            $responseG = $Game->getBalance($UsuarioMandante, $Subproveedor->subproveedorId, $UsuarioToken->productoId);

            $PKT = new SimpleXMLElement("<pkt></pkt>");

            $methodresponse = $PKT->addChild('methodresponse');
            $methodresponse->addAttribute('name', $this->method);
            $methodresponse->addAttribute('timestamp', date('Y/m/d H:i:s') . ".000");

            $Result = $methodresponse->addChild('result');

            $Result->addAttribute('seq', $this->seq);
            $Result->addAttribute('token', $UsuarioToken->getToken());
            $Result->addAttribute('balance', intval(round($responseG->saldo, 2) * 100));
            $Result->addAttribute('bonusbalance', "0");

            $extinfo = $Result->addChild('extinfo');

            return $PKT->asXML();
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Finaliza el juego y genera una respuesta XML.
     *
     * @return string Respuesta en formato XML.
     * @throws Exception Si ocurre un error al finalizar el juego.
     */
    public function endGame()
    {
        $this->method = 'endgame';
        try {
            $this->checkLogin();
            $Proveedor = new Proveedor("", "GAMESGLOBAL");

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

            $Game = new Game();

            $responseG = $Game->getBalance($UsuarioMandante);

            $PKT = new SimpleXMLElement("<pkt></pkt>");

            $methodresponse = $PKT->addChild('methodresponse');
            $methodresponse->addAttribute('name', $this->method);
            $methodresponse->addAttribute('timestamp', date('Y/m/d H:i:s') . ".000");

            $Result = $methodresponse->addChild('result');

            $Result->addAttribute('seq', $this->seq);
            $Result->addAttribute('token', $UsuarioToken->getToken());
            $Result->addAttribute('balance', intval(round($responseG->saldo, 2) * 100));
            $Result->addAttribute('bonusbalance', "0");

            $extinfo = $Result->addChild('extinfo');

            return $PKT->asXML();
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Refresca el token del usuario.
     *
     * @return string Respuesta en formato XML con el nuevo token.
     * @throws Exception Si ocurre un error al refrescar el token.
     */
    public function Refreshtoken()
    {
        $this->method = 'refreshtoken';


        try {
            $this->checkLogin();

            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado MGMG */
            $Proveedor = new Proveedor("", "GAMESGLOBAL");

            /*  Obtenemos el Usuario Token con el token */
            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            /*  Retornamos el mensaje satisfactorio  */

            $PKT = new SimpleXMLElement("<pkt></pkt>");

            $methodresponse = $PKT->addChild('methodresponse');
            $methodresponse->addAttribute('name', $this->method);
            $methodresponse->addAttribute('timestamp', date('Y/m/d H:i:s') . ".000");

            $Result = $methodresponse->addChild('result');


            $Result->addAttribute('seq', $this->seq);
            $Result->addAttribute('token', $UsuarioToken->getToken());

            $extinfo = $Result->addChild('extinfo');


            return $PKT->asXML();
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza una transacción de débito.
     *
     * @param string  $gameId        ID del juego.
     * @param string  $ticketId      ID del ticket.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $transactionId ID de la transacción.
     * @param mixed   $datos         Datos adicionales de la transacción.
     * @param boolean $freespin      Indica si es una transacción de giros gratis.
     *
     * @return string Respuesta en formato XML.
     * @throws Exception Si ocurre un error durante la transacción.
     */
    public function Debit($gameId, $ticketId, $debitAmount, $transactionId, $datos, $freespin = false)
    {
        $this->method = 'play';
        $this->data = $datos;

        try {
            $this->checkLogin();
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            //  Obtenemos el Proveedor con el abreviado PLAYNGO
            $Proveedor = new Proveedor("", "GAMESGLOBAL");

            //  Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);
            if ($this->token != "") {
                $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

                //Obtenemos el Usuario Token con el token
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

                //  Obtenemos el Usuario Mandante con el Usuario Token
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                //  Obtenemos el Usuario Mandante con el Usuario Token
                $UsuarioMandante = new UsuarioMandante($this->externalId);
                //  Obtenemos el producto con el gameId
                $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
            }


            $this->transaccionApi->setIdentificador("GAMESGLOBAL" . $ticketId);

            $Game = new Game();


            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin);

            $this->transaccionApi = $responseG->transaccionApi;

            $PKT = new SimpleXMLElement("<pkt></pkt>");

            $methodresponse = $PKT->addChild('methodresponse');
            $methodresponse->addAttribute('name', $this->method);
            $methodresponse->addAttribute('timestamp', date('Y/m/d H:i:s') . ".000");

            $Result = $methodresponse->addChild('result');


            $Result->addAttribute('seq', $this->seq);

            if (json_decode($datos)->offline->{'0'} == "true") {
                $Result->addAttribute('token', $this->token);
            } else {
                $Result->addAttribute('token', $UsuarioToken->getToken());
            }
            $Result->addAttribute('balance', intval(round($responseG->saldo, 2) * 100));
            $Result->addAttribute('bonusbalance', "0");
            $Result->addAttribute('exttransactionid', $responseG->transaccionId);

            $extinfo = $Result->addChild('extinfo');

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
     * Realiza una transacción de rollback.
     *
     * @param string $gameId         ID del juego.
     * @param string $ticketId       ID del ticket.
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $transactionId  ID de la transacción.
     * @param mixed  $datos          Datos adicionales de la transacción.
     *
     * @return string Respuesta en formato XML.
     * @throws Exception Si ocurre un error durante la transacción.
     */
    public function Rollback($gameId, $ticketId, $rollbackAmount, $transactionId, $datos)
    {
        $this->method = 'play';

        $this->ticketIdGlobal = $ticketId;

        $this->data = $datos;

        try {
            $this->checkLogin();
            //  Obtenemos el Proveedor con el abreviado PLAYNGO
            $Proveedor = new Proveedor("", "GAMESGLOBAL");

            //  Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($rollbackAmount);

            $identificador = "";
            try {
                $TransaccionJuego = new TransaccionJuego("", "GAMESGLOBAL" . $ticketId, "");

                $this->transaccionApi->setTransaccionId('ROLLBACK' . $TransaccionJuego->getTransaccionId());

                $ProductoMandante = new ProductoMandante("", "", $TransaccionJuego->getProductoId());

                //  Obtenemos el Usuario Mandante con el Usuario Token
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());

                //  Obtenemos el Usuario Token con el token
                $UsuarioToken = new UsuarioToken("", "", $UsuarioMandante->usumandanteId, "", "", $ProductoMandante->productoId);
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }
            $this->transaccionApi->setIdentificador("GAMESGLOBAL" . $ticketId);

            $this->transaccionApi->setProductoId($TransaccionJuego->getProductoId());

            $Game = new Game();

            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi);

            $this->transaccionApi = $responseG->transaccionApi;


            $PKT = new SimpleXMLElement("<pkt></pkt>");

            $methodresponse = $PKT->addChild('methodresponse');
            $methodresponse->addAttribute('name', $this->method);
            $methodresponse->addAttribute('timestamp', date('Y/m/d H:i:s') . ".000");

            $Result = $methodresponse->addChild('result');


            $Result->addAttribute('seq', $this->seq);
            if (json_decode($datos)->offline->{'0'} == "true") {
                $Result->addAttribute('token', $this->token);
            } else {
                $Result->addAttribute('token', $UsuarioToken->getToken());
            }
            $Result->addAttribute('balance', intval(round($responseG->saldo, 2) * 100));
            $Result->addAttribute('bonusbalance', "0");
            $Result->addAttribute('exttransactionid', $responseG->transaccionId);

            $extinfo = $Result->addChild('extinfo');

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
     * Realiza una transacción de crédito.
     *
     * @param string  $gameId        ID del juego.
     * @param string  $ticketId      ID del ticket.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $transactionId ID de la transacción.
     * @param boolean $isEndRound    Indica si es el final de la ronda.
     * @param mixed   $datos         Datos adicionales de la transacción.
     *
     * @return string Respuesta en formato XML.
     * @throws Exception Si ocurre un error durante la transacción.
     */
    public function Credit($gameId, $ticketId, $creditAmount, $transactionId, $isEndRound, $datos)
    {
        $this->method = 'play';

        $this->ticketIdGlobal = $ticketId;


        $this->data = $datos;

        try {
            $this->checkLogin();
            /*  Obtenemos el Proveedor con el abreviado PRAGMATIC */
            $Proveedor = new Proveedor("", "GAMESGLOBAL");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);
            $this->transaccionApi->setIdentificador("GAMESGLOBAL" . $ticketId);

            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            try {
                $TransaccionJuego = new TransaccionJuego("", "GAMESGLOBAL" . $ticketId);

                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());

                $UsuarioToken = new UsuarioToken("", $Proveedor->proveedorId, $UsuarioMandante->usumandanteId);
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            $ProductoMandante = new ProductoMandante("", "", $TransaccionJuego->productoId);
            /*  Obtenemos el producto con el $TransaccionJuego->productoId */
            $Producto = new Producto($ProductoMandante->productoId);

            $Game = new Game();

            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $isEndRound);

            $this->transaccionApi = $responseG->transaccionApi;

            $PKT = new SimpleXMLElement("<pkt></pkt>");

            $methodresponse = $PKT->addChild('methodresponse');
            $methodresponse->addAttribute('name', $this->method);
            $methodresponse->addAttribute('timestamp', date('Y/m/d H:i:s') . ".000");

            $Result = $methodresponse->addChild('result');

            $Result->addAttribute('seq', $this->seq);
            if (json_decode($datos)->offline->{'0'} == "true") {
                $Result->addAttribute('token', $this->token);
            } else {
                $Result->addAttribute('token', $UsuarioToken->getToken());
            }
            $Result->addAttribute('balance', intval(round($responseG->saldo, 2) * 100));
            $Result->addAttribute('bonusbalance', "0");
            $Result->addAttribute('exttransactionid', $responseG->transaccionId);

            $extinfo = $Result->addChild('extinfo');


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
     * Convierte un error en una respuesta XML con el código y mensaje de error.
     *
     * @param integer $code    Código de error.
     * @param string  $message Mensaje de error.
     *
     * @return string Respuesta en formato XML con el error.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $PKT = new SimpleXMLElement("<pkt></pkt>");

        $methodresponse = $PKT->addChild('methodresponse');
        $methodresponse->addAttribute('name', $this->method);
        $methodresponse->addAttribute('timestamp', date('Y/m/d H:i:s') . ".000");

        $Result = $methodresponse->addChild('result');


        $Result->addAttribute('seq', $this->seq);

        $extinfo = $Result->addChild('extinfo');

        $Proveedor = new Proveedor("", "GAMESGLOBAL");


        switch ($code) {
            case 10011:
                $codeProveedor = 6001;
                $messageProveedor = "Token not found";

                break;
            case 21:

                $codeProveedor = 6001;
                $messageProveedor = "Token not found";
                break;
            case 10013:
                $codeProveedor = 6103;
                $messageProveedor = "User not found";
                break;
            case 22:
                $codeProveedor = 6103;
                $messageProveedor = "User not found";
                break;
            case 20001:
                $codeProveedor = 6503;
                $messageProveedor = "Insufficient funds";

                break;

            case 0:
                $codeProveedor = 6000;
                $messageProveedor = "General Error. (" . $code . ")";

                break;

            case 26:
                $codeProveedor = 6511;
                $messageProveedor = "The external system name does not exist (gamereference)";

                break;
            case 27:
                $codeProveedor = 6000;
                $messageProveedor = "General Error. (" . $code . ")";
                break;
            case 28:

                $codeProveedor = 104;
                $messageProveedor = "General Error. (" . $code . ")";


                break;
            case 29:
                $codeProveedor = 6000;
                $messageProveedor = "General Error. (" . $code . ")";

                break;

            case 10001:

                $codeProveedor = 6000;
                $messageProveedor = "General Error. (" . $code . ")";

                break;

            case 10004:
                $codeProveedor = 6000;
                $messageProveedor = "General Error. (" . $code . ")";


                break;
            case 10014:
                $codeProveedor = 6000;
                $messageProveedor = "General Error. (" . $code . ")";


                break;

            case 10020:
                $codeProveedor = 6003;
                $messageProveedor = "The authentication credentials for the API are incorrect.";


                break;

            default:
                $codeProveedor = 6000;
                $messageProveedor = "General Error. (" . $code . ")";


                break;
        }

        $Result->addAttribute('errorcode', $codeProveedor);
        $Result->addAttribute('errordescription', $messageProveedor);


        if ($this->transaccionApi != null) {
            $this->transaccionApi->setTipo("R" . $this->transaccionApi->getTipo());
            $this->transaccionApi->setRespuestaCodigo("ERROR_" . $code);
            $this->transaccionApi->setRespuesta((string)$PKT->asXML());
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->insert($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();
        }


        return $PKT->asXML();
    }


}
