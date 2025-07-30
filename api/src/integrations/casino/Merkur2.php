<?php

/**
 * Clase Merkur para la integración con el proveedor de casino Merkur.
 *
 * Este archivo contiene la implementación de métodos para realizar operaciones
 * como autenticación, consulta de saldo, débito, crédito y reversión de transacciones
 * con el proveedor de casino Merkur.
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
 * Clase Merkur.
 *
 * Esta clase implementa la integración con el proveedor de casino Merkur,
 * proporcionando métodos para realizar operaciones como autenticación,
 * consulta de saldo, débito, crédito y reversión de transacciones.
 */
class Merkur
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
     * Token de autenticación del usuario.
     *
     * @var string
     */
    private $token;

    /**
     * Identificador único del usuario.
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
     * Objeto que representa la transacción API.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * Datos adicionales relacionados con la operación.
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
     * Identificador global del ticket de la transacción.
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
     * Constructor de la clase Merkur.
     *
     * @param string $token      Token de autenticación.
     * @param string $uid        Identificador único del usuario (opcional).
     * @param string $externalId Identificador externo del usuario (opcional).
     *
     * @throws Exception Si el token no coincide con el valor esperado.
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
     * Método para autenticar al usuario con el proveedor Merkur.
     *
     * @return string Respuesta en formato XML con el resultado de la autenticación.
     * @throws Exception Si el token o el identificador externo están vacíos.
     */
    public function Auth()
    {
        $this->method = 'authenticate';
        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }
            $Proveedor = new Proveedor("", "MERKUR");

            if ($this->token != "") {
                //  Obtenemos el Usuario Token con el token 
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                //  Obtenemos el Usuario Mandante con el Usuario Token 
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                //  Obtenemos el Usuario Mandante con el Usuario Token 
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }


            $Game = new Game();

            $responseG = $Game->autenticate($UsuarioMandante);


            $PKTS = new SimpleXMLElement("<authorizePlayerResponse></authorizePlayerResponse>");
            $PKT = $PKTS->addChild('return');
            $PKT->addChild('sessionId', $UsuarioToken->getToken());

            return $PKTS->asXML();
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para obtener el saldo del usuario en un juego específico.
     *
     * @param string $gameId Identificador del juego.
     *
     * @return string Respuesta en formato XML con el saldo del usuario.
     * @throws Exception Si ocurre un error durante la consulta del saldo.
     */
    public function getBalance($gameId)
    {
        $this->method = 'balance';
        try {
            $Proveedor = new Proveedor("", "MERKUR");


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

            $Game = new Game();

            $responseG = $Game->getBalance($UsuarioMandante);

            $PKTS = new SimpleXMLElement("<getBalanceResponse></getBalanceResponse>");
            $PKT = $PKTS->addChild('return');
            $PKT->addChild('balance', $responseG->saldo);

            return $PKTS->asXML();
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para realizar un débito en el saldo del usuario.
     *
     * @param string  $gameId        Identificador del juego.
     * @param string  $ticketId      Identificador del ticket de la transacción.
     * @param string  $uid           Identificador único del usuario.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $transactionId Identificador de la transacción.
     * @param array   $datos         Datos adicionales de la transacción.
     * @param boolean $freespin      Indica si la transacción es un giro gratis (opcional).
     *
     * @return string Respuesta en formato XML con el resultado del débito.
     * @throws Exception Si ocurre un error durante la operación.
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

            //  Obtenemos el Proveedor con el abreviado MERKUR 
            $Proveedor = new Proveedor("", "MERKUR");

            if ($this->token != "") {
                //  Obtenemos el Usuario Token con el token 
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                //  Obtenemos el Usuario Mandante con el Usuario Token */
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
            $this->transaccionApi->setIdentificador("MERKUR" . $ticketId);

            $Game = new Game();


            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin);

            $this->transaccionApi = $responseG->transaccionApi;


            //  Retornamos el mensaje satisfactorio
            $PKTS = new SimpleXMLElement("<withdrawResponse></withdrawResponse>");
            $PKT = $PKTS->addChild('return');
            $PKT->addChild('balance', $responseG->saldo);


            $PKT->addChild('transactionId', $responseG->transaccionId);

            $respuesta = $PKT->asXML();

            //  Guardamos la Transaccion Api necesaria de estado OK   
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();


            return $PKTS->asXML();
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para revertir una transacción de débito.
     *
     * @param string $gameId         Identificador del juego.
     * @param string $ticketId       Identificador del ticket de la transacción.
     * @param string $uid            Identificador único del usuario.
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $transactionId  Identificador de la transacción original.
     * @param array  $datos          Datos adicionales de la transacción.
     *
     * @return string Respuesta en formato XML con el resultado de la reversión.
     * @throws Exception Si ocurre un error durante la operación.
     */
    public function Rollback($gameId, $ticketId, $uid, $rollbackAmount, $transactionId, $datos)
    {
        $this->method = 'cancelReserve';

        $this->ticketIdGlobal = $ticketId;

        $this->data = $datos;

        try {
            //  Obtenemos el Proveedor con el abreviado MERKUR
            $Proveedor = new Proveedor("", "MERKUR");

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


            $this->transaccionApi->setIdentificador("MERKUR" . $ticketId);
            $Game = new Game();

            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi);

            $this->transaccionApi = $responseG->transaccionApi;


            //  Retornamos el mensaje satisfactorio
            $PKTS = new SimpleXMLElement("<withdrawResponse></withdrawResponse>");
            $PKT = $PKTS->addChild('return');
            $PKT->addChild('balance', $responseG->saldo);
            $PKT->addChild('transactionId', $responseG->transaccionId);

            $respuesta = $PKTS->asXML();

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
     * Método para realizar un crédito en el saldo del usuario.
     *
     * @param string  $gameId        Identificador del juego.
     * @param string  $ticketId      Identificador del ticket de la transacción.
     * @param string  $uid           Identificador único del usuario.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $transactionId Identificador de la transacción.
     * @param boolean $isEndRound    Indica si la transacción finaliza la ronda.
     * @param array   $datos         Datos adicionales de la transacción.
     *
     * @return string Respuesta en formato XML con el resultado del crédito.
     * @throws Exception Si ocurre un error durante la operación.
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
            //  Obtenemos el Proveedor con el abreviado MERKUR 
            $Proveedor = new Proveedor("", "MERKUR");

            //  Creamos la Transaccion API  
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);
            $this->transaccionApi->setIdentificador("MERKUR" . $ticketId);


            if ($this->token != "") {
                //  Obtenemos el Usuario Token con el token
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                //  Obtenemos el Usuario Mandante con el Usuario Token 
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                //  Obtenemos el Usuario Mandante con el Usuario Token 
                $UsuarioMandante = new UsuarioMandante($this->externalId);
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

            $respuesta = $PKT->asXML();

            //  Guardamos la Transaccion Api necesaria de estado OK   
            $this->transaccionApi->setRespuesta(json_encode($respuesta));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $PKT->asXML();
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para convertir errores en respuestas formateadas.
     *
     * @param integer $code    Código de error.
     * @param string  $message Mensaje de error.
     *
     * @return string Respuesta en formato XML con el error formateado.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";


        $PKT = new SimpleXMLElement("<" . $this->method . "></" . $this->method . ">");

        $Proveedor = new Proveedor("", "MERKUR");


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

                break;
            case 29:
                $codeProveedor = 2;
                $messageProveedor = "INTERNAL";


                break;

            case 10001:

                $codeProveedor = 2;
                $messageProveedor = "INTERNAL";


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

        $PKTS = new SimpleXMLElement("<Fault></Fault>");
        $PKT1 = $PKTS->addChild('detail');
        $PKT = $PKT1->addChild($this->method);

        $PKT->addChild('errorCode', $codeProveedor);
        $PKT->addChild('message', $messageProveedor);


        if ($this->transaccionApi != null) {
            $Text = $PKTS->asXML();
            $this->transaccionApi->setTipo("R" . $this->transaccionApi->getTipo());
            $this->transaccionApi->setRespuestaCodigo("ERROR");
            $this->transaccionApi->setRespuesta($Text);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->insert($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();
        }


        return $PKTS->asXML();
    }


}