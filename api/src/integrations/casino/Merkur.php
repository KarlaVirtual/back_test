<?php

/**
 * Clase Merkur para la integración con el proveedor de casino Merkur.
 *
 * Esta clase contiene métodos para manejar la autenticación, balance, débitos, créditos,
 * y reversiones de transacciones relacionadas con el proveedor de casino Merkur.
 *
 * @category   Integración
 * @package    API
 * @subpackage Casino
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
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
 * Esta clase representa la integración con el proveedor de casino Merkur,
 * proporcionando métodos para manejar autenticación, balance, débitos, créditos
 * y reversiones de transacciones.
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
     * Firma utilizada para validar la autenticación.
     *
     * @var string
     */
    private $sign;

    /**
     * Firma original utilizada para la validación.
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
     * Datos adicionales relacionados con la transacción.
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
     * Constructor de la clase Merkur.
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
     * @return string Respuesta en formato JSON con los datos de autenticación.
     */
    public function Auth()
    {
        $this->method = 'login';
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
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Balance = intval($Usuario->getBalance() * 100);

            $return = array(
                "responseType" => $this->method,
                "balance" => array(
                    "amount" => $Balance,
                    "currency" => $responseG->moneda,
                ),
                "playerId" => $UsuarioMandante->usumandanteId,
                "sessionToken" => $UsuarioToken->getToken(),
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para obtener el balance del usuario.
     *
     * @return string Respuesta en formato JSON con el balance del usuario.
     */
    public function getBalance()
    {
        $this->method = 'balance';

        try {
            $Proveedor = new Proveedor("", "MERKUR");

            if ($this->token != "") {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            $Game = new Game();

            $responseG = $Game->getBalance($UsuarioMandante);

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Balance = intval($Usuario->getBalance() * 100);

            $return = array(
                "responseType" => $this->method,
                "balance" => array(
                    "amount" => $Balance,
                    "currency" => $responseG->moneda,
                ),
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para realizar un débito en el balance del usuario.
     *
     * @param string  $gameId        Identificador del juego.
     * @param string  $ticketId      Identificador del ticket.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $transactionId Identificador de la transacción.
     * @param array   $datos         Datos adicionales de la transacción.
     * @param boolean $freespin      Indica si es un giro gratis (opcional).
     *
     * @return string Respuesta en formato JSON con los datos de la transacción.
     */
    public function Debit($gameId, $ticketId, $debitAmount, $transactionId, $datos, $freespin = false)
    {
        $this->method = 'stake';
        $this->ticketIdGlobal = $ticketId;

        $this->data = $datos;

        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            $Proveedor = new Proveedor("", "MERKUR");

            if ($this->token != "") {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

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
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Balance = intval($Usuario->getBalance() * 100);

            $this->transaccionApi = $responseG->transaccionApi;


            $respuesta = array(
                "responseType" => $this->method,
                "balance" => array(
                    "amount" => $Balance,
                    "currency" => $responseG->moneda,
                ),
                "casinoTransactionId" => $responseG->transaccionId
            );
            $respuesta = json_encode($respuesta);

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
     * Método para realizar una reversión de una transacción.
     *
     * @param string $gameId         Identificador del juego.
     * @param string $ticketId       Identificador del ticket.
     * @param string $uid            Identificador único del usuario.
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $transactionId  Identificador de la transacción original.
     * @param array  $datos          Datos adicionales de la transacción.
     *
     * @return string Respuesta en formato JSON con los datos de la reversión.
     */
    public function Rollback($gameId, $ticketId, $uid, $rollbackAmount, $transactionId, $datos)
    {
        $this->method = 'rollback';

        $this->ticketIdGlobal = $ticketId;

        $this->data = $datos;

        try {
            $Proveedor = new Proveedor("", "MERKUR");

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
                $TransaccionApi2 = new TransaccionApi("", $transactionId, $Proveedor->getProveedorId());


                $UsuarioMandante = new UsuarioMandante($TransaccionApi2->getUsuarioId());

                $identificador = $TransaccionApi2->getIdentificador();
                $this->transaccionApi->setIdentificador($identificador);
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            $Game = new Game();
            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi);

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Balance = intval($Usuario->getBalance() * 100);

            $this->transaccionApi = $responseG->transaccionApi;

            $respuesta = array(
                "responseType" => $this->method,
                "balance" => array(
                    "amount" => $Balance,
                    "currency" => $responseG->moneda,
                ),
                "casinoTransactionId" => $responseG->transaccionId
            );
            $respuesta = json_encode($respuesta);

            $this->transaccionApi->setRespuesta(($respuesta));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $respuesta;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para realizar un crédito en el balance del usuario.
     *
     * @param string  $gameId        Identificador del juego.
     * @param string  $ticketId      Identificador del ticket.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $transactionId Identificador de la transacción.
     * @param boolean $isEndRound    Indica si es el final de la ronda.
     * @param array   $datos         Datos adicionales de la transacción.
     * @param boolean $free          Indica si es un crédito gratuito.
     *
     * @return string Respuesta en formato JSON con los datos de la transacción.
     */
    public function Credit($gameId, $ticketId, $creditAmount, $transactionId, $isEndRound, $datos, $free)
    {
        $this->method = 'winnings';

        $this->ticketIdGlobal = $ticketId;


        $this->data = $datos;

        try {
            $Proveedor = new Proveedor("", "MERKUR");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);
            $this->transaccionApi->setIdentificador("MERKUR" . $ticketId);


            $TransaccionJuego = new TransaccionJuego("", "MERKUR" . $ticketId);
            $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());

            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
            $Game = new Game();

            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $isEndRound, false, $free);

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Balance = intval($Usuario->getBalance() * 100);

            $this->transaccionApi = $responseG->transaccionApi;

            $respuesta = array(
                "responseType" => $this->method,
                "balance" => array(
                    "amount" => $Balance,
                    "currency" => $responseG->moneda,
                ),
                "casinoTransactionId" => $responseG->transaccionId
            );
            $respuesta = json_encode($respuesta);

            $this->transaccionApi->setRespuesta(($respuesta));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $respuesta;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para convertir errores en respuestas formateadas.
     *
     * @param integer $code    Código del error.
     * @param string  $message Mensaje del error.
     *
     * @return string Respuesta en formato JSON con los detalles del error.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";


        $respuesta = array();

        $Proveedor = new Proveedor("", "MERKUR");


        if ($this->transaccionApi != null) {
            $tipo = $this->transaccionApi->getTipo();
        }

        switch ($code) {
            case 10011:
                $codeProveedor = 'Auth211';
                $messageProveedor = "SessionToken expired";
                break;

            case 21:
                $codeProveedor = 'Auth200';
                $messageProveedor = "StartToken unknown";
                break;

            case 20002:
                $codeProveedor = 'Tech200';
                $messageProveedor = "SIGN INCORRECT";
                break;

            case 10013:
                $codeProveedor = 'Auth200';
                $messageProveedor = "StartToken unknown";
                break;

            case 22:
                $codeProveedor = 'Auth200';
                $messageProveedor = "StartToken unknown";
                break;

            case 20001:
                $codeProveedor = 'Book100';
                $messageProveedor = "Not enough money";
                break;


            case 20003:
                $codeProveedor = 'Auth100';
                $messageProveedor = "Access denied";
                break;

            case 0:
                $codeProveedor = 'Tech100';
                $messageProveedor = "Unexpected Error";
                break;

            case 27:
                $codeProveedor = 'Tech100';
                $messageProveedor = "Unexpected Error";
                break;

            case 28:

                $codeProveedor = 'Tech100';
                $messageProveedor = "Unexpected Error";

                break;
            case 29:
                $codeProveedor = 'Tech100';
                $messageProveedor = "Unexpected Error";


                break;


            case 10005:

                $respuesta = array(
                    "responseType" => $this->method
                );


                break;


            case 10001:


                $codeProveedor = 'Tech100';
                $messageProveedor = "Unexpected Error";
                try {
                    $tipo = $this->transaccionApi->getTipo();
                    $transaccionApi2 = new TransaccionApi("", $this->transaccionApi->getTransaccionId(), $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($transaccionApi2->usuarioId);

                    $Game = new Game();

                    $responseG = $Game->getBalance($UsuarioMandante);


                    $respuesta = array(
                        "responseType" => $this->method,
                        "balance" => array(
                            "amount" => intval($responseG->saldo),
                            "currency" => $responseG->moneda,
                        ),
                        "casinoTransactionId" => $transaccionApi2->getTransapiId()
                    );
                } catch (Exception $e) {
                    return $this->convertError($e->getCode(), $e->getMessage());
                }


                break;

            case 10004:
                $codeProveedor = 'Tech100';
                $messageProveedor = "Unexpected Error";

                break;
            case 10014:
                $codeProveedor = 'Tech100';
                $messageProveedor = "Unexpected Error";


                break;
            case 20005:
                $codeProveedor = 'Auth100';
                $messageProveedor = "Access denied";


                break;
            case 20006:
                $codeProveedor = 'Auth100';
                $messageProveedor = "Access denied";


                break;
            case 20007:
                $codeProveedor = 'Auth100';
                $messageProveedor = "Access denied";


                break;


            default:
                $codeProveedor = 'Tech100';
                $messageProveedor = "Unexpected Error";


                break;
        }

        if ($code != "10001" && $code != "10005") {
            $respuesta["responseType"] = $this->method;
            $respuesta["error"] = array(
                "casinoErrorText" => $messageProveedor,
                "casinoErrorText2" => $message
            ,
                "errorCode" => $codeProveedor
            );
        }

        $respuesta = json_encode($respuesta);


        if ($this->transaccionApi != null) {
            $this->transaccionApi->setTipo("R" . $this->transaccionApi->getTipo());
            $this->transaccionApi->setTransaccionId($this->transaccionApi->getTransaccionId() . '_E' . $code);
            $this->transaccionApi->setRespuestaCodigo("ERROR");
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->insert($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();
        }


        return $respuesta;
    }

}