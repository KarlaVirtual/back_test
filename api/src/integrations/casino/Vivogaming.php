<?php

/**
 * Clase principal para la integración con el proveedor de juegos VivoGaming.
 *
 * Este archivo contiene la implementación de la clase `Vivogaming` y la clase auxiliar `MySimpleXMLElement`.
 * Proporciona métodos para manejar transacciones, autenticación, balance, y otras operaciones relacionadas
 * con la integración de juegos de casino.
 *
 * @category Integración
 * @package  Backend\integrations\casino
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
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
 * Clase Vivogaming
 *
 * Maneja la integración con el proveedor VivoGaming, incluyendo autenticación,
 * transacciones de débito, crédito, rollback, y consulta de balance.
 */
class Vivogaming
{
    /**
     * Token de autenticación para las solicitudes.
     *
     * @var string
     */
    private $token;

    /**
     * Firma utilizada para validar las solicitudes.
     *
     * @var string
     */
    private $sign;

    /**
     * Objeto que representa la transacción API actual.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * Datos adicionales relacionados con la solicitud.
     *
     * @var mixed
     */
    private $data;

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
     * Clave secreta de la API para la autenticación.
     *
     * @var string
     */
    private $apisecret = '';

    /**
     * Objeto XML utilizado para construir las solicitudes.
     *
     * @var MySimpleXMLElement
     */
    private $xmlRequest;

    /**
     * Clave de paso utilizada para la autenticación.
     *
     * @var string
     */
    private $PassKey = '';

    /**
     * Constructor de la clase Vivogaming.
     *
     * Inicializa los valores necesarios para la integración con VivoGaming.
     *
     * @param string $token      Token de autenticación.
     * @param string $uid        Identificador único del usuario.
     * @param string $externalId ID externo del usuario.
     */
    public function __construct($token, $uid = "", $externalId = "")
    {
        $this->token = (string)$token;
        $this->sign = (string)$uid;
        $this->externalId = (string)$externalId;

        $Proveedor = new Proveedor("", "VIVOGAMING");

        if ($this->token != "") {
            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
        } else {
            $UsuarioMandante = new UsuarioMandante($this->externalId);
            $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->usumandanteId);
        }

        $Producto = new Producto($UsuarioToken->productoId);

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $PASS_KEY = $credentials->PASS_KEY;
        $API_SECRET = $credentials->API_SECRET;

        $this->xmlRequest = new MySimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>' . "<REQUEST></REQUEST>");

        $this->apisecret = $API_SECRET;
        $this->PassKey = $PASS_KEY;
    }

    /**
     * Método Auth
     *
     * Autentica al usuario con el proveedor VivoGaming.
     *
     * @return string XML con la respuesta de autenticación.
     * @throws Exception Si ocurre un error durante la autenticación.
     */
    public function Auth()
    {
        try {
            if (($this->token != "" && $this->token != "-")) {
                $this->xmlRequest->addChild('TOKEN', $this->token);
            }
            if ($this->externalId != "") {
                $this->xmlRequest->addChild('USERID', $this->externalId);
            }

            $this->xmlRequest->addChild('HASH', $this->sign);

            $hash = md5($this->token . $this->PassKey);

            if ($hash != $this->sign) {
                throw new Exception("Sign Error", "20002");
            }

            if (($this->token == "" || $this->token == "-") && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }
            $Proveedor = new Proveedor("", "VIVOGAMING");

            if ($this->token != "" && $this->token != "-") {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $VGSSYSTEM = new MySimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>' . "<VGSSYSTEM></VGSSYSTEM>");

            $VGSSYSTEM->appendXML($this->xmlRequest);
            $VGSSYSTEM->addChild('TIME', date("d M o H:i:s"));
            $RESPONSE = $VGSSYSTEM->addChild('RESPONSE');

            $RESPONSE->addChild('RESULT', 'OK');
            $RESPONSE->addChild('USERID', $responseG->usuario);
            $RESPONSE->addChild('USERNAME', $responseG->usuario);
            $RESPONSE->addChild('CURRENCY', $responseG->moneda);
            $RESPONSE->addChild('BALANCE', round($responseG->saldo, 2));

            return $VGSSYSTEM->asXML();
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del usuario.
     *
     * @param integer $gameId ID del juego (opcional).
     *
     * @return string XML con el balance del usuario.
     * @throws Exception Si ocurre un error al obtener el balance.
     */
    public function getBalance()
    {
        try {
            if ($this->externalId == '') {
                $this->externalId = '';
            }

            if (($this->token != "" && $this->token != "-")) {
                $this->xmlRequest->addChild('TOKEN', $this->token);
            }
            if ($this->externalId != "") {
                $this->xmlRequest->addChild('USERID', $this->externalId);
            }

            $this->xmlRequest->addChild('HASH', $this->sign);

            $hash = md5('Usuario' . $this->externalId . $this->PassKey);

            if ($hash != $this->sign) {
                throw new Exception("Sign Error", "20002");
            }


            if (($this->token == "" || $this->token == "-") && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            $Proveedor = new Proveedor("", "VIVOGAMING");

            if ($this->token != "" && $this->token != "-") {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);

            $VGSSYSTEM = new MySimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>' . "<VGSSYSTEM></VGSSYSTEM>");
            $VGSSYSTEM->appendXML($this->xmlRequest);
            $VGSSYSTEM->addChild('TIME', date("d M o H:i:s"));
            $RESPONSE = $VGSSYSTEM->addChild('RESPONSE');

            $RESPONSE->addChild('RESULT', 'OK');
            $RESPONSE->addChild('BALANCE', round($responseG->saldo, 2));

            return $VGSSYSTEM->asXML();
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Consulta el estado de una transacción.
     *
     * @param string $transactionId ID de la transacción.
     *
     * @return string XML con el estado de la transacción.
     * @throws Exception Si ocurre un error al consultar la transacción.
     */
    public function getStatusTransaction($transactionId)
    {
        try {
            if ($this->externalId == '') {
                $this->externalId = '';
            }

            if (($this->token != "" && $this->token != "-")) {
                $this->xmlRequest->addChild('TOKEN', $this->token);
            }
            if ($this->externalId != "") {
                $this->xmlRequest->addChild('USERID', $this->externalId);
            }
            $this->xmlRequest->addChild('CASINOTRANSACTIONID', $transactionId);
            $this->xmlRequest->addChild('HASH', $this->sign);

            $hash = md5('Usuario' . $this->externalId . $transactionId . $this->PassKey);

            if ($hash != $this->sign) {
                throw new Exception("Sign Error", "20002");
            }

            if (($this->token == "" || $this->token == "-") && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            $Proveedor = new Proveedor("", "VIVOGAMING");

            if ($this->token != "" && $this->token != "-") {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }


            $transaccionApi = new TransaccionApi();

            $transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $transaccionApi->setTransaccionId($transactionId);

            $transaccion = '';

            if ($transaccionApi->existsTransaccionIdAndProveedor("OK")) {
                $transaccionApi2 = new TransaccionApi("", $transaccionApi->getTransaccionId(), $Proveedor->getProveedorId());

                $transaccion = $transaccionApi2->getTransapiId();
            }
            $VGSSYSTEM = new MySimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>' . "<VGSSYSTEM></VGSSYSTEM>");
            $VGSSYSTEM->appendXML($this->xmlRequest);
            $VGSSYSTEM->addChild('TIME', date("d M o H:i:s"));


            if ($transaccion != '') {
                $RESPONSE = $VGSSYSTEM->addChild('RESPONSE');

                $RESPONSE->addChild('RESULT', 'OK');
                $RESPONSE->addChild('ECSYSTEMTRANSACTIONID', $transaccion);
            } else {
                $RESPONSE = $VGSSYSTEM->addChild('RESPONSE');

                $RESPONSE->addChild('RESULT', 'FAILED');
                $RESPONSE->addChild('CODE', 302);
            }

            return $VGSSYSTEM->asXML();
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza una transacción de débito.
     *
     * @param integer $gameId        ID del juego.
     * @param string  $ticketId      ID del ticket.
     * @param string  $uid           Identificador único del usuario.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $transactionId ID de la transacción.
     * @param mixed   $datos         Datos adicionales.
     * @param boolean $freespin      Indica si es un giro gratis.
     * @param array   $bets          Apuestas realizadas.
     *
     * @return string XML con la respuesta de la transacción.
     * @throws Exception Si ocurre un error durante la transacción.
     */
    public function Debit($tableId, $gameId, $ticketId, $uid, $debitAmount, $transactionId, $datos, $freespin = false, $bets = [])
    {
        if (($this->token == "" || $this->token == "-") && $this->externalId == "") {
            $this->xmlRequest->addChild('USERID', $this->externalId);
        }

        $this->xmlRequest->addChild('AMOUNT', $debitAmount);
        $this->xmlRequest->addChild('TRANSACTIONID', $transactionId);
        $this->xmlRequest->addChild('TRNTYPE', json_decode($datos)->trnType);
        $this->xmlRequest->addChild('GAMEID', $gameId);
        $this->xmlRequest->addChild('ROUNDID', $ticketId);
        $this->xmlRequest->addChild('TRNDESCRIPTION', json_decode($datos)->transactionDescription);
        $this->xmlRequest->addChild('HISTORY', json_decode($datos)->history);
        $this->xmlRequest->addChild('ISROUNDFINISHED', json_decode($datos)->isRoundFinished);
        $this->xmlRequest->addChild('HASH', $this->sign);

        $this->ticketIdGlobal = $ticketId;

        $this->data = $datos;

        try {
            $hash = md5('Usuario' . $this->externalId . $debitAmount . json_decode($datos)->trnType . json_decode($datos)->transactionDescription . $ticketId . $gameId . json_decode($datos)->history . $this->PassKey);

            $gameId = $gameId;

            if ($hash != $this->sign) {
                throw new Exception("Sign Error", "20002");
            }

            if (($this->token == "" || $this->token == "-") && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            $Proveedor = new Proveedor("", "VIVOGAMING");

            if ($this->token != "" && $this->token != "-") {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            $Producto = new Producto("", $tableId, $Proveedor->getProveedorId());

            //  Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue($datos);
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);
            $this->transaccionApi->setIdentificador($UsuarioMandante->getUsumandanteId() . "VIVOGAMING" . $ticketId);

            $Game = new Game();


            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin, $bets);

            $this->transaccionApi = $responseG->transaccionApi;

            $VGSSYSTEM = new MySimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>' . "<VGSSYSTEM></VGSSYSTEM>");
            $VGSSYSTEM->appendXML($this->xmlRequest);
            $VGSSYSTEM->addChild('TIME', date("d M o H:i:s"));

            $RESPONSE = $VGSSYSTEM->addChild('RESPONSE');

            $RESPONSE->addChild('RESULT', 'OK');
            $RESPONSE->addChild('BALANCE', round($responseG->saldo, 2));
            $RESPONSE->addChild('ECSYSTEMTRANSACTIONID', $responseG->transaccionId);

            $respuesta = $VGSSYSTEM->asXML();

            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $VGSSYSTEM->asXML();
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un rollback de una transacción.
     *
     * @param integer $gameId         ID del juego.
     * @param string  $ticketId       ID del ticket.
     * @param string  $uid            Identificador único del usuario.
     * @param float   $rollbackAmount Monto a revertir.
     * @param string  $transactionId  ID de la transacción.
     * @param mixed   $datos          Datos adicionales.
     *
     * @return string XML con la respuesta del rollback.
     * @throws Exception Si ocurre un error durante el rollback.
     */
    public function Rollback($tableId, $gameId, $ticketId, $uid, $rollbackAmount, $transactionId, $datos)
    {
        if (($this->token == "" || $this->token == "-") && $this->externalId == "") {
            $this->xmlRequest->addChild('USERID', $this->externalId);
        }

        $this->xmlRequest->addChild('AMOUNT', $rollbackAmount);
        $this->xmlRequest->addChild('TRANSACTIONID', $transactionId);
        $this->xmlRequest->addChild('TRNTYPE', json_decode($datos)->trnType);
        $this->xmlRequest->addChild('GAMEID', $gameId);
        $this->xmlRequest->addChild('ROUNDID', $ticketId);
        $this->xmlRequest->addChild('TRNDESCRIPTION', json_decode($datos)->transactionDescription);
        $this->xmlRequest->addChild('HISTORY', json_decode($datos)->history);
        $this->xmlRequest->addChild('ISROUNDFINISHED', json_decode($datos)->isRoundFinished);
        $this->xmlRequest->addChild('HASH', $this->sign);

        $this->ticketIdGlobal = $ticketId;

        $this->data = $datos;

        try {
            $hash = md5('Usuario' . $this->externalId . $rollbackAmount . json_decode($datos)->trnType . json_decode($datos)->transactionDescription . $ticketId . $gameId . json_decode($datos)->history . $this->PassKey);

            $gameId = $gameId;


            if ($hash != $this->sign) {
                throw new Exception("Sign Error", "20002");
            }

            $UsuarioMandante = new UsuarioMandante($this->externalId);

            $Proveedor = new Proveedor("", "VIVOGAMING");

            $TransaccionJuego = new TransaccionJuego("", $UsuarioMandante->getUsumandanteId() . "VIVOGAMING" . $ticketId, "");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue($datos);
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($rollbackAmount);

            $UsuarioMandante = new UsuarioMandante($TransaccionJuego->usuarioId);

            $this->transaccionApi->setIdentificador($UsuarioMandante->getUsumandanteId() . "VIVOGAMING" . $ticketId);

            $Game = new Game();
            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false, $TransaccionJuego->getTransaccionId());

            $this->transaccionApi = $responseG->transaccionApi;

            $VGSSYSTEM = new MySimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>' . "<VGSSYSTEM></VGSSYSTEM>");
            $VGSSYSTEM->appendXML($this->xmlRequest);
            $VGSSYSTEM->addChild('TIME', date("d M o H:i:s"));

            $RESPONSE = $VGSSYSTEM->addChild('RESPONSE');

            $RESPONSE->addChild('RESULT', 'OK');
            $RESPONSE->addChild('BALANCE', round($responseG->saldo, 2));
            $RESPONSE->addChild('ECSYSTEMTRANSACTIONID', $responseG->transaccionId);

            $respuesta = $VGSSYSTEM->asXML();

            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $respuesta;
        } catch (Exception $e) {
            if ($_ENV['debug']) {
                print_r($e);
            }
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza una transacción de crédito.
     *
     * @param integer $gameId        ID del juego.
     * @param string  $ticketId      ID del ticket.
     * @param string  $uid           Identificador único del usuario.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $transactionId ID de la transacción.
     * @param boolean $isEndRound    Indica si es el final de la ronda.
     * @param mixed   $datos         Datos adicionales.
     *
     * @return string XML con la respuesta de la transacción.
     * @throws Exception Si ocurre un error durante la transacción.
     */
    public function Credit($tableId, $gameId, $ticketId, $uid, $creditAmount, $transactionId, $isEndRound, $datos)
    {
        if (($this->token == "" || $this->token == "-") && $this->externalId == "") {
            $this->xmlRequest->addChild('USERID', $this->externalId);
        }
        $this->xmlRequest->addChild('AMOUNT', $creditAmount);
        $this->xmlRequest->addChild('TRANSACTIONID', $transactionId);
        $this->xmlRequest->addChild('TRNTYPE', json_decode($datos)->trnType);
        $this->xmlRequest->addChild('GAMEID', $gameId);
        $this->xmlRequest->addChild('ROUNDID', $ticketId);
        $this->xmlRequest->addChild('TRNDESCRIPTION', json_decode($datos)->transactionDescription);
        $this->xmlRequest->addChild('HISTORY', json_decode($datos)->history);
        $this->xmlRequest->addChild('ISROUNDFINISHED', json_decode($datos)->isRoundFinished);
        $this->xmlRequest->addChild('HASH', $this->sign);


        $hash = md5('Usuario' . $this->externalId . $creditAmount . json_decode($datos)->trnType . json_decode($datos)->transactionDescription . $ticketId . $gameId . json_decode($datos)->history . $this->PassKey);

        $gameId = $gameId;


        if ($hash != $this->sign) {
            throw new Exception("Sign Error", "20002");
        }

        $this->ticketIdGlobal = $ticketId;

        $this->data = $datos;

        try {
            if (($this->token == "" || $this->token == "-") && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }
            $Proveedor = new Proveedor("", "VIVOGAMING");

            $UsuarioMandante = new UsuarioMandante($this->externalId);

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue($datos);
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);
            $this->transaccionApi->setIdentificador($UsuarioMandante->getUsumandanteId() . "VIVOGAMING" . $ticketId);


            try {
                $TransaccionJuego = new TransaccionJuego("", $UsuarioMandante->getUsumandanteId() . "VIVOGAMING" . $ticketId);
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            $ProductoMandante = new ProductoMandante("", "", $TransaccionJuego->productoId);
            $Producto = new Producto($ProductoMandante->productoId);
            
            $Game = new Game();
            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $isEndRound, false);

            $this->transaccionApi = $responseG->transaccionApi;

            $VGSSYSTEM = new MySimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>' . "<VGSSYSTEM></VGSSYSTEM>");
            $VGSSYSTEM->appendXML($this->xmlRequest);
            $VGSSYSTEM->addChild('TIME', date("d M o H:i:s"));

            $RESPONSE = $VGSSYSTEM->addChild('RESPONSE');

            $RESPONSE->addChild('RESULT', 'OK');
            $RESPONSE->addChild('BALANCE', round($responseG->saldo, 2));
            $RESPONSE->addChild('ECSYSTEMTRANSACTIONID', $responseG->transaccionId);

            $respuesta = $VGSSYSTEM->asXML();

            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $VGSSYSTEM->asXML();
        } catch (Exception $e) {
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

        $VGSSYSTEM = new MySimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>' . "<VGSSYSTEM></VGSSYSTEM>");
        $VGSSYSTEM->appendXML($this->xmlRequest);
        $Proveedor = new Proveedor("", "VIVOGAMING");

        switch ($code) {
            case 10011:
                $codeProveedor = 400;
                $messageProveedor = "SESSIONEXPIRED";
                break;

            case 21:
                $codeProveedor = 400;
                $messageProveedor = "SESSIONEXPIRED";
                break;

            case 20002:
                $codeProveedor = 400;
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
                $codeProveedor = 300;
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
                $codeProveedor = 302;
                $messageProveedor = "INTERNAL";


                break;

            case 10001:

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

                try {
                    $transaccionApi2 = new TransaccionApi("", $this->transaccionApi->getTransaccionId(), $Proveedor->getProveedorId());


                    $VGSSYSTEM = new MySimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>' . "<VGSSYSTEM></VGSSYSTEM>");

                    $VGSSYSTEM->appendXML($this->xmlRequest);
                    $VGSSYSTEM->addChild('TIME', date("d M o H:i:s"));
                    $RESPONSE = $VGSSYSTEM->addChild('RESPONSE');

                    $RESPONSE->addChild('RESULT', 'OK');
                    $RESPONSE->addChild('BALANCE', round($responseG->saldo, 2));
                    $RESPONSE->addChild('ECSYSTEMTRANSACTIONID', $transaccionApi2->getTransapiId());
                } catch (Exception $e) {
                    $VGSSYSTEM = new MySimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>' . "<VGSSYSTEM></VGSSYSTEM>");

                    $VGSSYSTEM->appendXML($this->xmlRequest);

                    $VGSSYSTEM->addChild('TIME', date("d M o H:i:s"));

                    $RESPONSE = $VGSSYSTEM->addChild('RESPONSE');

                    $RESPONSE->addChild('RESULT', 'OK');
                    $RESPONSE->addChild('BALANCE', round($responseG->saldo, 2));
                    $RESPONSE->addChild('ECSYSTEMTRANSACTIONID', '0');
                }

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

                $VGSSYSTEM = new MySimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>' . "<VGSSYSTEM></VGSSYSTEM>");

                $VGSSYSTEM->addChild('token', $this->token);
                $VGSSYSTEM->addChild('success', 1);
                $VGSSYSTEM->addChild('error_code', 0);
                $VGSSYSTEM->addChild('error_text', '');
                $VGSSYSTEM->addChild('time', time());
                $params = $VGSSYSTEM->addChild('params', '');

                $params->addChild('balance_after', round(($responseG->saldo) * 100, 0));
                $params->addChild('already_processed', 1);


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


            default:
                $codeProveedor = 2;
                $messageProveedor = "INTERNAL";


                break;
        }

        if ($code != 10001 && $code != 10014) {
            $VGSSYSTEM->addChild('TIME', date("d M o H:i:s"));

            $RESPONSE = $VGSSYSTEM->addChild('RESPONSE', '');

            $RESPONSE->addChild('RESULT', 'FAILED');
            $RESPONSE->addChild('CODE', $codeProveedor);
        }
        if ($code == '0') {
            try {
                syslog(LOG_WARNING, "VIVOGAMING-CODEERROR " . $message);
            } catch (Exception $e) {
            }
        }


        if ($this->transaccionApi != null) {
            $Text = "CODEERROR:" . $code . " " . "MSJERROR:" . $message . " " . json_encode($VGSSYSTEM);
            $this->transaccionApi->setTipo("R" . $this->transaccionApi->getTipo() . "_" . $code);
            $this->transaccionApi->setRespuestaCodigo("ERROR_" . $code);
            //$this->transaccionApi->setRespuesta(trim(str_replace("&quot;","",$Text)));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->insert($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();
        }


        return $VGSSYSTEM->asXML();
    }
}
/**
 * Clase personalizada que extiende `SimpleXMLElement`.
 *
 * Proporciona métodos adicionales para manipular y agregar nodos XML.
 */
class MySimpleXMLElement extends SimpleXMLElement
{
    /**
     * Agrega un XML al nodo actual.
     *
     * @param SimpleXMLElement $append XML a agregar.
     * @param array            $array2 Datos adicionales.
     * @param integer          $contG  Contador de nodos.
     *
     * @return void
     */
    public function appendXML($append, $array2 = array(), $contG = 0)
    {
        $array = array();
        foreach ($append as $k => $v) {
            $array[$k] = (string)$v;
        }

        if ($array2[$append->getName()] != '' || $contG == 0) {
            if (strlen(trim((string)$array2[$append->getName()])) == 0) {
                $xml = $this->addChild($append->getName());
            } else {
                $xml = $this->addChild($append->getName(), (string)$array2[$append->getName()]);
            }


            $cont = 0;
            foreach ($append->children() as $child) {
                $xml->appendXML($child, $array, $contG++);
                $cont++;
            }

            foreach ($append->attributes() as $n => $v) {
                $xml->addAttribute($n, $v);
            }
        }
    }
}
