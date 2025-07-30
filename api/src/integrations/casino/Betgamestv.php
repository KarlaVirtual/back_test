<?php

/**
 * Clase Betgamestv
 *
 * Esta clase implementa la integración con el proveedor BETGAMESTV para realizar operaciones
 * relacionadas con autenticación, balance, transacciones de débito, crédito, rollback, entre otras.
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
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioToken;
use Backend\dto\SubproveedorMandantePais;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransaccionApi;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\ProductoMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\CategoriaProducto;
use Backend\dto\TransaccionProducto;
use Backend\dto\UsuarioConfiguracion;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionApiMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;

/**
 * Clase principal para manejar la integración con BETGAMESTV.
 */
class Betgamestv
{
    /**
     * Token de autenticación.
     *
     * @var string
     */
    private $token;

    /**
     * Firma del usuario.
     *
     * @var string
     */
    private $sign;

    /**
     * ID de la solicitud.
     *
     * @var string
     */
    private $request_id;

    /**
     * Objeto de transacción API.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * Datos de la solicitud.
     *
     * @var mixed
     */
    private $data;

    /**
     * Método de la operación.
     *
     * @var string
     */
    private $method;

    /**
     * ID global del ticket.
     *
     * @var string
     */
    private $ticketIdGlobal;

    /**
     * ID externo del usuario.
     *
     * @var string
     */
    private $externalId;

    /**
     * Clave secreta de la API.
     *
     * @var string
     */
    private $apisecret = '';

    /**
     * Constructor de la clase.
     *
     * @param string $token        Token de autenticación.
     * @param string $uid          ID del usuario.
     * @param string $externalId   ID externo del usuario.
     * @param string $method       Método de la operación.
     * @param string $signOriginal Firma original.
     * @param string $request_id   ID de la solicitud.
     */
    public function __construct($token, $uid = "", $externalId = "", $method = "", $signOriginal = "", $request_id = "")
    {
        if ($method != '') {
            $this->token = $token;
            $this->sign = $uid;
            $this->signOriginal = $signOriginal;
            $this->request_id = $request_id;
            $this->externalId = explode("Usuario", $externalId)[1];

            $this->method = $method;

            if ($this->token == '') {
                try {
                    throw new Exception("Token vacio", "20002");
                } catch (Exception $e) {
                    return $this->convertError($e->getCode(), $e->getMessage());
                }
            }
        }
    }

    /**
     * Autentica al usuario con el proveedor BETGAMESTV.
     *
     * @return string XML con la respuesta de autenticación.
     * @throws Exception Si ocurre un error durante la autenticación.
     */
    public function Auth()
    {
        try {
            if (($this->token == "" || $this->token == "-") && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }
            $Proveedor = new Proveedor("", "BETGAMESTV");

            if ($this->token != "" && $this->token != "-") {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            $Game = new Game();

            $responseG = $Game->autenticate($UsuarioMandante);

            $root = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>' . "<root></root>");

            $root->addChild('method', $this->method);
            $root->addChild('token', $this->token);
            $root->addChild('success', 1);
            $root->addChild('error_code', 0);
            $root->addChild('error_text', '');
            $root->addChild('time', time());
            $params = $root->addChild('params', '');

            $params->addChild('user_id', $responseG->usuario);
            $params->addChild('username', 'User' . $responseG->usuario);
            $params->addChild('currency', $responseG->moneda);
            $params->addChild('info', '-');


            // Generamos un UUID para response_id
            $response_id = $this->generateUUID();

            $root->addChild('response_id', $response_id);

            // Calculamos la firma HMAC-SHA256 usando el response_id y la clave secreta
            $signature = $this->getSignature($response_id);
            $root->addChild('signature', $signature);

            return $root->asXML();
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del usuario.
     *
     * @return string XML con el balance del usuario.
     * @throws Exception Si ocurre un error al obtener el balance.
     */
    public function getBalance()
    {
        try {
            $Proveedor = new Proveedor("", "BETGAMESTV");


            if ($this->token != "" && $this->token != "-") {
                //  Obtenemos el Usuario Token con el token
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                //  Obtenemos el Usuario Mandante con el Usuario Token
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                //  Obtenemos el Usuario Mandante con el Usuario Token
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            $Game = new Game();

            $responseG = $Game->getBalance($UsuarioMandante);

            $root = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>' . "<root></root>");

            $root->addChild('method', $this->method);
            $root->addChild('token', $this->token);
            $root->addChild('success', 1);
            $root->addChild('error_code', 0);
            $root->addChild('error_text', '');
            $root->addChild('time', time());
            $params = $root->addChild('params', '');

            $params->addChild('balance', round(($responseG->saldo) * 100, 0));


            // Generamos un UUID para response_id
            $response_id = $this->generateUUID();
            $root->addChild('response_id', $response_id);

            // Calculamos la firma HMAC-SHA256 usando el response_id y la clave secreta
            $signature = $this->getSignature($response_id);
            $root->addChild('signature', $signature);

            return $root->asXML();
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método de prueba para verificar la conexión con el proveedor.
     *
     * @return string XML con la respuesta del ping.
     * @throws Exception Si ocurre un error durante el ping.
     */
    public function ping()
    {
        try {
            $Proveedor = new Proveedor("", "BETGAMESTV");


            $root = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>' . "<root></root>");

            $root->addChild('method', $this->method);
            $root->addChild('token', '-');
            $root->addChild('success', 1);
            $root->addChild('error_code', 0);
            $root->addChild('error_text', '');
            $root->addChild('time', time());
            $params = $root->addChild('params', '');

            // Generamos un UUID para response_id
            $response_id = $this->generateUUID();
            $root->addChild('response_id', $response_id);

            // Calculamos la firma HMAC-SHA256 usando el response_id y la clave secreta
            $signature = $this->getSignature($response_id);
            $root->addChild('signature', $signature);

            return $root->asXML();
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Refresca el token del usuario.
     *
     * @param integer $gameId ID del juego (opcional).
     *
     * @return string XML con el nuevo token.
     * @throws Exception Si ocurre un error al refrescar el token.
     */
    public function refreshToken($gameId = 0)
    {
        try {
            $Proveedor = new Proveedor("", "BETGAMESTV");


            if ($this->token != "" && $this->token != "-") {
                //  Obtenemos el Usuario Token con el token
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                //  Obtenemos el Usuario Mandante con el Usuario Token
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                //  Obtenemos el Usuario Mandante con el Usuario Token
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            $root = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>' . "<root></root>");

            $root->addChild('method', $this->method);
            $root->addChild('token', $UsuarioToken->getToken());
            $root->addChild('success', 1);
            $root->addChild('error_code', 0);
            $root->addChild('error_text', '');
            $root->addChild('time', time());
            $params = $root->addChild('params', '');

            if ($this->method == 'request_new_token') {
                $params->addChild('new_token', $UsuarioToken->getToken());
            }

            // Generamos un UUID para response_id
            $response_id = $this->generateUUID();
            $root->addChild('response_id', $response_id);

            // Calculamos la firma HMAC-SHA256 usando el response_id y la clave secreta
            $signature = $this->getSignature($response_id);
            $root->addChild('signature', $signature);

            return $root->asXML();
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza una transacción de débito.
     *
     * @param integer $gameId        ID del juego.
     * @param string  $ticketId      ID del ticket.
     * @param string  $uid           ID del usuario.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $transactionId ID de la transacción.
     * @param mixed   $datos         Datos adicionales.
     * @param boolean $freespin      Indica si es un giro gratis.
     * @param array   $bets          Apuestas realizadas.
     *
     * @return string XML con la respuesta del débito.
     * @throws Exception Si ocurre un error durante el débito.
     */
    public function Debit($gameId, $ticketId, $uid, $debitAmount, $transactionId, $datos, $freespin = false, $bets = [])
    {
        $this->ticketIdGlobal = $ticketId;

        $this->data = $datos;

        try {
            if (($this->token == "" || $this->token == "-") && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            $Proveedor = new Proveedor("", "BETGAMESTV");

            if ($this->token != "" && $this->token != "-") {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }


            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);
            $this->transaccionApi->setIdentificador("BETGAMESTV" . $ticketId);

            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin, $bets);

            $this->transaccionApi = $responseG->transaccionApi;

            $root = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>' . "<root></root>");

            $root->addChild('method', $this->method);
            $root->addChild('token', $this->token);
            $root->addChild('success', 1);
            $root->addChild('error_code', 0);
            $root->addChild('error_text', '');
            $root->addChild('time', time());
            $params = $root->addChild('params', '');

            $params->addChild('balance_after', round(($responseG->saldo) * 100, 0));
            $params->addChild('already_processed', 0);

            $response_id = $this->generateUUID();
            $root->addChild('response_id', $response_id);

            $signature = $this->getSignature($response_id);
            $root->addChild('signature', $signature);

            $respuesta = $root->asXML();

            //  Guardamos la Transaccion Api necesaria de estado OK
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
     * Realiza una transacción de rollback.
     *
     * @param integer $gameId         ID del juego.
     * @param string  $ticketId       ID del ticket.
     * @param string  $uid            ID del usuario.
     * @param float   $rollbackAmount Monto a revertir.
     * @param string  $transactionId  ID de la transacción.
     * @param mixed   $datos          Datos adicionales.
     *
     * @return string XML con la respuesta del rollback.
     * @throws Exception Si ocurre un error durante el rollback.
     */
    public function Rollback($gameId, $ticketId, $uid, $rollbackAmount, $transactionId, $datos)
    {
        $this->ticketIdGlobal = $ticketId;

        $this->data = $datos;

        try {
            $Proveedor = new Proveedor("", "BETGAMESTV");

            //  Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($rollbackAmount);

            try {
                $TransaccionJuego = new TransaccionJuego("", "BETGAMESTV" . $ticketId, "");
                $this->transaccionApi->setTransaccionId('ROLLBACK' . $TransaccionJuego->getTransaccionId());

                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            $this->transaccionApi->setIdentificador("BETGAMESTV" . $ticketId);

            $Game = new Game();
            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi);
            $this->transaccionApi = $responseG->transaccionApi;


            $root = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>' . "<root></root>");

            $root->addChild('method', $this->method);
            $root->addChild('token', $this->token);
            $root->addChild('success', 1);
            $root->addChild('error_code', 0);
            $root->addChild('error_text', '');
            $root->addChild('time', time());
            $params = $root->addChild('params', '');

            $params->addChild('balance_after', round(($responseG->saldo) * 100, 0));
            $params->addChild('already_processed', 0);


            $response_id = $this->generateUUID();
            $root->addChild('response_id', $response_id);

            $signature = $this->getSignature($response_id);
            $root->addChild('signature', $signature);

            $respuesta = $root->asXML();

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
     * @param integer $gameId        ID del juego.
     * @param string  $ticketId      ID del ticket.
     * @param string  $uid           ID del usuario.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $transactionId ID de la transacción.
     * @param boolean $isEndRound    Indica si es el final de la ronda.
     * @param mixed   $datos         Datos adicionales.
     *
     * @return string XML con la respuesta del crédito.
     * @throws Exception Si ocurre un error durante el crédito.
     */
    public function Credit($gameId, $ticketId, $uid, $creditAmount, $transactionId, $isEndRound, $datos)
    {
        $this->ticketIdGlobal = $ticketId;


        $this->data = $datos;

        try {
            if (($this->token == "" || $this->token == "-") && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }
            $Proveedor = new Proveedor("", "BETGAMESTV");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);
            $this->transaccionApi->setIdentificador("BETGAMESTV" . $ticketId);


            try {
                $TransaccionJuego = new TransaccionJuego("", "BETGAMESTV" . $ticketId, "");

                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
            $Game = new Game();

            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $isEndRound, false);

            $this->transaccionApi = $responseG->transaccionApi;

            $root = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>' . "<root></root>");

            $root->addChild('method', $this->method);
            $root->addChild('token', $this->token);
            $root->addChild('success', 1);
            $root->addChild('error_code', 0);
            $root->addChild('error_text', '');
            $root->addChild('time', time());
            $params = $root->addChild('params', '');

            $params->addChild('balance_after', round(($responseG->saldo) * 100, 0));
            $params->addChild('already_processed', 0);


            // Generamos un UUID para response_id
            $response_id = $this->generateUUID();
            $root->addChild('response_id', $response_id);

            // Calculamos la firma HMAC-SHA256 usando el response_id y la clave secreta
            $signature = $this->getSignature($response_id);
            $root->addChild('signature', $signature);

            $respuesta = $root->asXML();


            //  Guardamos la Transaccion Api necesaria de estado OK
            $this->transaccionApi->setRespuesta(json_encode($respuesta));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $root->asXML();
        } catch (Exception $e) {
            if ($_ENV['debug']) {
                print_r('entro');
                print_r($e);
            }

            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Convierte un error en una respuesta XML.
     *
     * @param integer $code    Código del error.
     * @param string  $message Mensaje del error.
     *
     * @return string XML con la respuesta del error.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";


        $root = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>' . "<root></root>");

        $root->addChild('method', $this->method);
        $root->addChild('token', $this->token);
        $root->addChild('success', 0);

        $Proveedor = new Proveedor("", "BETGAMESTV");


        if ($this->transaccionApi != null) {
            $tipo = $this->transaccionApi->getTipo();
        }

        switch ($code) {
            case 10011:
                $codeProveedor = 3;
                $messageProveedor = "SESSIONEXPIRED";
                break;

            case 21:
                $codeProveedor = 3;
                $messageProveedor = "SESSIONEXPIRED";
                break;

            case 20002:
                $codeProveedor = 1;
                $messageProveedor = "wrong signature";
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
                $codeProveedor = 703;
                $messageProveedor = "Insufficient balance";
                break;

            case 100030:
                $codeProveedor = 703;
                $messageProveedor = "Insufficient balance";
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

                $codeProveedor = 700;
                $messageProveedor = "there is no PAYIN with provided bet_id";


                break;
            case 29:
                $codeProveedor = 700;
                $messageProveedor = "INTERNAL";

                break;

            case 10001:

                $codeProveedor = 2;
                $messageProveedor = "INTERNAL";

                $tipo = $this->transaccionApi->getTipo();

                $ProductoMandante = new ProductoMandante('', '', $this->transaccionApi->getProductoId());
                $Producto = new Producto($ProductoMandante->productoId);


                $TransjuegoLog = new TransjuegoLog("", '', '', $this->transaccionApi->getTransaccionId() . '_' . $Producto->getSubproveedorId(), $Producto->getSubproveedorId());
                $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);

                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->usuarioId);

                if ($UsuarioMandante != null && $UsuarioMandante->mandante != '') {
                    $Game = new Game();

                    $responseG = $Game->getBalance($UsuarioMandante);
                }
                $root = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>' . "<root></root>");

                $root->addChild('method', $this->method);
                $root->addChild('token', $this->token);
                $root->addChild('success', 1);
                $root->addChild('error_code', 0);
                $root->addChild('error_text', '');
                $root->addChild('time', time());
                $params = $root->addChild('params', '');

                $params->addChild('balance_after', round(($responseG->saldo) * 100, 0));
                $params->addChild('already_processed', 1);

                $response_id = $this->generateUUID();
                $signature = $this->getSignature($response_id);

                $root->addChild('response_id', $response_id);
                $root->addChild('signature', $signature);


                break;

            case 10004:
                $codeProveedor = 2;
                $messageProveedor = "INTERNAL";

                break;
            case 10014:
                $codeProveedor = 2;
                $messageProveedor = "INTERNAL";

                if ($this->token != "" && $this->token != "-") {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } else {
                    $UsuarioMandante = new UsuarioMandante($this->externalId);
                }

                $Game = new Game();
                $responseG = $Game->getBalance($UsuarioMandante);

                $tipo = $this->transaccionApi->getTipo();
                $root = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>' . "<root></root>");

                $root->addChild('method', $this->method);
                $root->addChild('token', $this->token);
                $root->addChild('success', 1);
                $root->addChild('error_code', 0);
                $root->addChild('error_text', '');
                $root->addChild('time', time());
                $params = $root->addChild('params', '');

                $params->addChild('balance_after', round(($responseG->saldo) * 100, 0));
                $params->addChild('already_processed', 1);

                $response_id = $this->generateUUID();
                $signature = $this->getSignature($response_id);

                $root->addChild('response_id', $response_id);
                $root->addChild('signature', $signature);

                break;

            case 20005:
                $codeProveedor = 701;
                $messageProveedor = "ACCOUNTLOCKED";


                break;
            case 20006:
                $codeProveedor = 701;
                $messageProveedor = "ACCOUNTLOCKED";


                break;
            case 20007:
                $codeProveedor = 701;
                $messageProveedor = "ACCOUNTLOCKED";


                break;

            case 10005:
                $codeProveedor = 700;
                $messageProveedor = "INTERNAL";
                break;


            default:
                $codeProveedor = 2;
                $messageProveedor = "INTERNAL";


                break;
        }

        if ($code != 10001 && $code != 10014) {
            $root->addChild('error_code', $codeProveedor);
            $root->addChild('error_text', $messageProveedor);
            $root->addChild('time', time());

            $response_id = $this->generateUUID();
            $signature = $this->getSignature($response_id);

            $root->addChild('response_id', $response_id);
            $root->addChild('signature', $signature);
        }

        if ($this->transaccionApi != null) {
            $Text = $root->asXML();
            $this->transaccionApi->setTipo("R" . $this->transaccionApi->getTipo());
            $this->transaccionApi->setRespuestaCodigo("ERROR_" . $code);
            $this->transaccionApi->setRespuesta($Text);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->insert($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();
        }


        return $root->asXML();
    }

    /**
     * Genera una firma HMAC-SHA256.
     *
     * @param string $response_id ID de la respuesta.
     *
     * @return string Firma generada.
     */
    public function getSignature($response_id)
    {
        $Proveedor = new Proveedor("", "BETGAMESTV");
        $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
        $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

        $Producto = new Producto($UsuarioToken->productoId);

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $SECRET_KEY = $credentials->SECRET_KEY;
        return hash_hmac('sha256', $response_id, $SECRET_KEY);
    }

    /**
     * Genera un UUID aleatorio.
     *
     * @return string UUID generado.
     */
    public function generateUUID()
    {
        // Genera un UUID aleatorio válido.
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}
