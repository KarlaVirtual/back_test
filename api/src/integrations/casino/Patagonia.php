<?php
/**
 * Script para analizar código PHP y generar documentación PHPDoc automáticamente.
 *
 * Esta clase implementa la integración con un proveedor de casino (PTG) para realizar operaciones
 * como autenticación, consulta de saldo, débito, crédito y reversión de transacciones.
 *
 * @category Integración
 * @package  Backend\integrations\casino
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */

namespace Backend\integrations\casino;

use Backend\dto\BonoInterno;
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Mandante;
use Backend\dto\Pais;
use Backend\dto\Registro;
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
use Backend\dto\UsuarioOtrainfo;
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
use SimpleXMLElement;

/**
 * Clase Patagonia
 *
 * Esta clase implementa la integración con el proveedor de casino Patagonia (PTG).
 * Proporciona métodos para realizar operaciones como autenticación, consulta de saldo,
 * débito, crédito, reversión de transacciones y manejo de errores.
 */
class Patagonia
{
    /**
     * Usuario para autenticación.
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
     * Token de sesión.
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
     * Objeto para manejar transacciones API.
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
     * Constructor de la clase Patagonia.
     *
     * @param string $Login    Usuario para autenticación.
     * @param string $Password Contraseña para autenticación.
     * @param string $token    Token de sesión.
     * @param string $uid      Identificador único del usuario (opcional).
     */
    public function __construct($Login, $Password, $token, $uid = "")
    {
        $this->Login = $Login;
        $this->Password = $Password;
        $this->token = $token;
        $this->uid = $uid;
    }

    /**
     * Método Auth
     *
     * Realiza la autenticación del usuario y devuelve los detalles de la cuenta.
     *
     * @return string XML con los detalles de la cuenta o un error en caso de fallo.
     */
    public function Auth()
    {
        $this->method = 'GetAccountDetails';

        try {

            $Proveedor = new Proveedor("", "PTG");

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

            if ($this->token != "") {

                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            }

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Mandante = new Mandante($UsuarioMandante->mandante);

            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            if ($Mandante->propio == "S") {

                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                $Registro = new Registro("", $UsuarioMandante->usuarioMandante);
                $UsuarioOtrainfo = new UsuarioOtrainfo($UsuarioMandante->getUsuarioMandante());

                $PKT = new SimpleXMLElement("<PKT></PKT>");

                $Result = $PKT->addChild('Result');
                $Result->addAttribute('Name', $this->method);
                $Result->addAttribute('Success', '1');

                $Returnset = $Result->addChild('Returnset');

                $Token = $Returnset->addChild('Token');
                $Token->addAttribute('Type', 'string');
                $Token->addAttribute('Value', $UsuarioToken->getToken());

                $LoginName = $Returnset->addChild('LoginName');
                $LoginName->addAttribute('Type', 'string');
                $LoginName->addAttribute('Value', 'Usuario' . $UsuarioMandante->getUsumandanteId());

                $Currency = $Returnset->addChild('Currency');
                $Currency->addAttribute('Type', 'string');
                $Currency->addAttribute('Value', $UsuarioMandante->getMoneda());

                $Pais = new Pais($UsuarioMandante->getPaisId());

                $Country = $Returnset->addChild('Country');
                $Country->addAttribute('Type', 'string');
                $Country->addAttribute('Value', $Pais->iso);

                $Birthdate = $Returnset->addChild('Birthdate');
                $Birthdate->addAttribute('Type', 'date');
                $Birthdate->addAttribute('Value', $UsuarioOtrainfo->getFechaNacim());

                $Registration = $Returnset->addChild('Registration');
                $Registration->addAttribute('Type', 'date');
                $Registration->addAttribute('Value', date('Y-m-d', strtotime($Usuario->fechaCrea)));

                $Gender = $Returnset->addChild('Gender');
                $Gender->addAttribute('Type', 'string');
                $Gender->addAttribute('Value', $Registro->getSexo());

                return $PKT->asXML();
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método getBalance
     *
     * Obtiene el saldo actual del usuario autenticado.
     *
     * @return string XML con el saldo del usuario o un error en caso de fallo.
     */
    public function getBalance()
    {
        $this->method = 'GetBalance';
        try {
            $Proveedor = new Proveedor("", "PTG");

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

            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Mandante = new Mandante($UsuarioMandante->mandante);

            if ($Mandante->propio == "S") {
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                $Balance = intval(($Usuario->getBalance()) * 100);

                $PKT = new SimpleXMLElement("<PKT></PKT>");

                $Result = $PKT->addChild('Result');
                $Result->addAttribute('Name', $this->method);
                $Result->addAttribute('Success', '1');

                $Returnset = $Result->addChild('Returnset');

                $Token = $Returnset->addChild('Token');
                $Token->addAttribute('Type', 'string');
                $Token->addAttribute('Value', $UsuarioToken->getToken());

                $LoginName = $Returnset->addChild('Balance');
                $LoginName->addAttribute('Type', 'string');
                $LoginName->addAttribute('Value', "$Balance");


                return $PKT->asXML();
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método Debit
     *
     * Realiza un débito (apuesta) en la cuenta del usuario.
     *
     * @param string $gameId        Identificador del juego.
     * @param string $ticketId      Identificador del ticket.
     * @param string $uid           Identificador único del usuario.
     * @param float  $debitAmount   Monto a debitar.
     * @param string $transactionId Identificador de la transacción.
     * @param array  $datos         Datos adicionales de la transacción.
     *
     * @return string XML con el resultado de la operación o un error en caso de fallo.
     */
    public function Debit($gameId, $ticketId, $uid, $debitAmount, $transactionId, $datos)
    {
        $this->method = 'PlaceBet';
        $this->ticketIdGlobal = $ticketId;

        $this->data = $datos;

        try {
            /*  Obtenemos el Proveedor con el abreviado PTG */
            $Proveedor = new Proveedor("", "PTG");
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);
            $this->transaccionApi->setIdentificador("PTG" . $ticketId);

            $isfreeSpin = false;
            $End = false;

            if ($this->token != "") {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            }

            $Producto = new Producto($UsuarioToken->productoId);
            $Game = new Game();

            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $isfreeSpin, [], '', $End);

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Balance = intval(($Usuario->getBalance()) * 100);
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
            $ExtTransactionID->addAttribute('Value', "$transactionId");

            $ExtTransactionID = $Returnset->addChild('AlreadyProcessed');
            $ExtTransactionID->addAttribute('Type', 'bool');
            $ExtTransactionID->addAttribute('Value', "false");
            $respuesta = $PKT->asXML();


            //  Guardamos la Transaccion Api necesaria de estado OK
            $this->transaccionApi->setRespuestaCodigo("OK");
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->insert($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $PKT->asXML();
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método Rollback
     *
     * Realiza una reversión de una transacción previa (rollback).
     *
     * @param string $gameId         Identificador del juego.
     * @param string $ticketId       Identificador del ticket.
     * @param string $uid            Identificador único del usuario.
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $transactionId  Identificador de la transacción original.
     * @param array  $datos          Datos adicionales de la transacción.
     *
     * @return string XML con el resultado de la operación o un error en caso de fallo.
     */
    public function Rollback($gameId, $ticketId, $uid, $rollbackAmount, $transactionId, $datos)
    {
        $this->method = 'RefundBet';

        $this->ticketIdGlobal = $ticketId;

        $this->data = $datos;

        try {
            $Proveedor = new Proveedor("", "PTG");
            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue($datos);
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($rollbackAmount);
            $AllowCreditTransaction = false;
            try {

                $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
                $TransaccionJuego = new TransaccionJuego("", "PTG" . $ticketId);
                $trans = $TransaccionJuego->transaccionId . '_' . $Producto->subproveedorId;

                $TransjuegoLog = new TransjuegoLog("",  $TransaccionJuego->transjuegoId, "", $trans);
                $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }
            if ($this->token != "") {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
            }

            $Game = new Game();
            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false, '', '', '', $AllowCreditTransaction, '', false);

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Balance = intval(($Usuario->getBalance()) * 100);

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
            $ExtTransactionID->addAttribute('Value', "$transactionId");

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
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método Credit
     *
     * Realiza un crédito (premio) en la cuenta del usuario.
     *
     * @param string  $gameId        Identificador del juego.
     * @param string  $ticketId      Identificador del ticket.
     * @param string  $uid           Identificador único del usuario.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $transactionId Identificador de la transacción.
     * @param boolean $isEndRound    Indica si es el final de la ronda.
     * @param array   $datos         Datos adicionales de la transacción.
     *
     * @return string XML con el resultado de la operación o un error en caso de fallo.
     */
    public function Credit($gameId, $ticketId, $uid, $creditAmount, $transactionId, $isEndRound, $datos)
    {
        $this->method = 'AwardWinnings';

        $this->ticketIdGlobal = $ticketId;

        $this->data = $datos;

        try {
            //  Obtenemos el Proveedor con el abreviado PTG
            $Proveedor = new Proveedor("", "PTG");

            try {
                $TransaccionJuego = new TransaccionJuego("", "PTG" . $ticketId);
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            //  Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);
            $this->transaccionApi->setIdentificador("PTG" . $ticketId);

            $ProductoMandante = new ProductoMandante("", "", $TransaccionJuego->productoId);
            /*  Obtenemos el producto con el $TransaccionJuego->productoId */
            $Producto = new Producto($ProductoMandante->productoId);

            $End = false;
            $isBonus = false;

            $Game = new Game();
            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $End, false, $isBonus, false);

            $Balance = intval(round($responseG->saldo, 2) * 100);

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
            $ExtTransactionID->addAttribute('Value', "$transactionId");

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
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método convertError
     *
     * Convierte un error en un formato XML para ser devuelto al cliente.
     *
     * @param integer $code    Código del error.
     * @param string  $message Mensaje del error.
     *
     * @return string XML con los detalles del error.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $PKT = new SimpleXMLElement("<PKT></PKT>");

        $Result = $PKT->addChild('Result');
        $Result->addAttribute('Name', $this->method);
        $Result->addAttribute('Success', '0');

        $Returnset = $Result->addChild('Returnset');
        $Proveedor = new Proveedor("", "PTG");

        if ($this->transaccionApi != null) {
            $tipo = $this->transaccionApi->getTipo();
        }

        switch ($code) {

            case 10011:
                $codeProveedor = 1;
                $messageProveedor = "Token Expired";
                break;

            case 21:
                $codeProveedor = 1;
                $messageProveedor = "Token Expired";
                break;

            case 10013:
                $codeProveedor = 7;
                $messageProveedor = "User not found";
                break;

            case 22:
                $codeProveedor = 7;
                $messageProveedor = "User not found";
                break;

            case 20001:
                $codeProveedor = 6;
                $messageProveedor = "Insufficient funds";
                break;

            case 0:
                $codeProveedor = 2;
                $messageProveedor = "General Error. (" . $code . ")" . $message;
                break;

            case 27:
                $codeProveedor = 2;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 28:
                $codeProveedor = 2;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 29:
                $codeProveedor = 2;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 10001:

                $codeProveedor = 0;
                $messageProveedor = "ok";

                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                $Mandante = new Mandante($UsuarioMandante->mandante);

                if ($Mandante->propio == "S") {
                    $token = $UsuarioToken->getToken();
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $saldo = intval(($Usuario->getBalance()) * 100);

                    $tipo = $this->transaccionApi->getTipo();
                    $TransaccionJuego = new TransaccionJuego("", "PTG" . $this->ticketIdGlobal, "");
                    $TransjuegoLog = new TransjuegoLog("", $TransaccionJuego->getTransjuegoId(), $tipo);

                    //  Retornamos el mensaje satisfactorio  */
                    $PKT = new SimpleXMLElement("<PKT></PKT>");

                    $Result = $PKT->addChild('Result');
                    $Result->addAttribute('Name', $this->method);
                    $Result->addAttribute('Success', '1');

                    $Returnset = $Result->addChild('Returnset');

                    $Token = $Returnset->addChild('Token');
                    $Token->addAttribute('Type', 'string');
                    $Token->addAttribute('Value', "$token");

                    $Balancex = $Returnset->addChild('Balance');
                    $Balancex->addAttribute('Type', 'string');
                    $Balancex->addAttribute('Value', "$saldo");

                    $ExtTransactionID = $Returnset->addChild('ExtTransactionID');
                    $ExtTransactionID->addAttribute('Type', 'long');
                    $ExtTransactionID->addAttribute('Value', "$TransjuegoLog->transjuegologId");

                    $ExtTransactionID = $Returnset->addChild('AlreadyProcessed');
                    $ExtTransactionID->addAttribute('Type', 'bool');
                    $ExtTransactionID->addAttribute('Value', "true");
                }


                break;

            case 10004:
                $codeProveedor = 2;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 10014:
                $codeProveedor = 2;
                $messageProveedor = "General Error. (" . $code . ")";
                break;


            default:
                $codeProveedor = 2;
                $messageProveedor = "General Error. (" . $code . ")";


                break;
        }

        if ($code != 10001) {

            $Error = $Returnset->addChild('Error');
            $Error->addAttribute('Type', 'string');
            $Error->addAttribute('Value', $messageProveedor);

            $ErrorCode = $Returnset->addChild('ErrorCode');
            $ErrorCode->addAttribute('Type', 'string');
            $ErrorCode->addAttribute('Value', $codeProveedor);
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
