<?php

/**
 * Clase `Egt` para la integración con el proveedor de casino EGT.
 *
 * Este archivo contiene la implementación de métodos para manejar
 * transacciones, autenticación, balance, débitos, créditos y rollbacks
 * relacionados con el proveedor de casino EGT.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */

namespace Backend\integrations\casino;


use DateTime;
use Exception;
use DateTimeZone;
use Backend\dto\Pais;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Proveedor;
use Backend\dto\PuntoVenta;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioToken;
use Backend\dto\TransjuegoLog;
use Backend\dto\UsuarioPerfil;
use Backend\dto\TransaccionApi;
use Backend\dto\UsuarioSession;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\ProductoMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\UsuarioHistorial;
use Backend\dto\TransaccionProducto;
use Backend\dto\UsuarioConfiguracion;
use Backend\mysql\UsuarioTokenMySqlDAO;

use Backend\websocket\WebsocketUsuario;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransaccionApiMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;

/**
 * Clase principal para manejar la integración con el proveedor EGT.
 */
class Egt
{
    /**
     * Usuario asociado a la sesión.
     *
     * @var string
     */
    private $user;

    /**
     * Token de autenticación.
     *
     * @var string
     */
    private $token;

    /**
     * Objeto para manejar transacciones API.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * ID de la sesión actual.
     *
     * @var string
     */
    private $sessionId;

    /**
     * ID de la transacción actual.
     *
     * @var string
     */
    private $transactionId;

    /**
     * Tipo de operación actual (e.g., Credit, Debit, Rollback).
     *
     * @var string
     */
    private $type;

    /**
     * Constructor de la clase `Egt`.
     *
     * @param string $user      Usuario asociado a la sesión.
     * @param string $token     Token de autenticación.
     * @param string $sessionId ID de la sesión actual.
     */
    public function __construct($user = "", $token, $sessionId)
    {

        $this->token = $token;
        $this->user = $user;
        $this->sessionId = $sessionId;
    }
    /**
     * Valida el checksum de los datos recibidos.
     *
     * @param object $data        Datos enviados por el cliente.
     * @param object $fieldsHTTP  Datos enviados por el cliente.
     * @param array  $checksumServer     Encabezados HTTP de la solicitud.
     *
     * @return boolean|string Devuelve `true` si el checksum es válido, o un error en caso contrario.
     */
    function validateChecksum($data, $checksumServer, $fieldsHTTP)
    {

        try {
            $Proveedor = new Proveedor("", "EGT");
            $Subproveedor = new Subproveedor("", "EGT");

            try {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->proveedorId);
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } catch (Exception $e) {
                $UsuarioToken = new UsuarioToken("", $Proveedor->proveedorId, $this->user);
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            }

            $SubproveedorMandantePais =  new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $fieldsArray = explode(',', $fieldsHTTP);
            $valuesToConcatenate = [];

            foreach ($fieldsArray as $field) {
                if (property_exists($data, $field)) {
                    $valuesToConcatenate[] = $data->$field;
                } else {
                    throw new Exception("ERR_FIELD_MISSING: $field is not present in data", 24);
                }
            }
            $concatenated = implode(',', $valuesToConcatenate);

            $checksum = base64_encode(hash_hmac('sha512', $concatenated, $credentials->key, true));

            $checksumServerFixed = stripslashes($checksumServer);

            if ($checksum !== $checksumServerFixed) {
                throw new Exception("ERR_INTEGRITY_CHECK_FAILED", 23);
            }

            return true;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Autentica al usuario con el proveedor EGT.
     *
     * @return string JSON con el resultado de la autenticación, incluyendo el balance y la moneda.
     */
    public function Auth()
    {

        try {
            $Proveedor = new Proveedor("", "EGT");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);

            try {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->proveedorId);
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } catch (Exception $e) {
                throw new Exception("ERR_INVALID_TOKEN", "21");
            }

            try {
                $UsuarioToken = new UsuarioToken("", $Proveedor->proveedorId, $this->user);
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } catch (Exception $e) {
                throw new Exception("ERR_INVALID_PLAYER_ID", "10018");
            }

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            $Producto = new Producto($UsuarioToken->productoId);
            $Subproveedor = new Subproveedor($Producto->subproveedorId);

            $Game = new Game();

            $responseG = $Game->autenticate($UsuarioMandante, $Subproveedor->subproveedorId, $UsuarioToken->productoId);

            $Balance = intval($Usuario->getBalance() * 100);

            $UsuarioToken->setRequestId($this->sessionId);
            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
            $UsuarioTokenMySqlDAO->update($UsuarioToken);
            $UsuarioTokenMySqlDAO->getTransaction()->commit();

            $return = array(
                "currency" => $responseG->moneda,
                "balance" => $Balance,
                "statusCode" => "OK",
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Refresca el token de autenticación del usuario.
     *
     * Este método genera un nuevo token de autenticación para el usuario
     * asociado a la sesión actual. Valida que la sesión sea válida antes
     * de proceder a generar el nuevo token.
     *
     * @return string JSON con el nuevo token de autenticación y el estado de la operación.
     * @throws Exception Si el usuario o la sesión no son válidos.
     */
    public function refreshToken()
    {

        try {

            $Proveedor = new Proveedor("", "EGT");

            try {

                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
            } catch (Exception $e) {
                throw new Exception("User Invalid", "10018");
            }

            if ($UsuarioToken->requestId != $this->sessionId) {
                throw new Exception("Session Invalid", "10017");
            }


            $token = $UsuarioToken->createToken();
            $UsuarioToken->setToken($token);
            $UsuarioToken->setProductoId($UsuarioToken->productoId);
            $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
            $UsuarioToken->setRequestId($this->sessionId);
            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
            $UsuarioTokenMySqlDAO->update($UsuarioToken);
            $UsuarioTokenMySqlDAO->getTransaction()->commit();
            $token_ = $UsuarioToken->token;

            $return = array(
                "defenceCode" => $token_,
                "statusCode" => "OK",
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }
    public function terminate()
    {
        try {

            if ($this->user == "") {
                throw new Exception("Usuario vacio", "10011");
            }

            $return = array(
                "statusCode" => "OK",
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del usuario en la moneda especificada.
     *
     * Este método valida la sesión del usuario, verifica la moneda proporcionada
     * y obtiene el balance actual del usuario desde el proveedor EGT.
     *
     * @param string $currency Moneda en la que se desea obtener el balance.
     *
     * @return string JSON con el balance del usuario y el estado de la operación.
     * @throws Exception Si el usuario, la sesión o la moneda no son válidos.
     */
    public function Balance($currency)
    {

        try {
            $Proveedor = new Proveedor("", "EGT");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("BALANCE");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);


            try {
                $UsuarioToken = new UsuarioToken("", $Proveedor->proveedorId, $this->user);
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } catch (Exception $e) {
                throw new Exception("ERR_INVALID_PLAYER_ID", "10018");
            }

            if ($currency == "" || $currency != $UsuarioMandante->moneda) {
                throw new Exception("Moneda Incorrecta", "97");
            }

            if ($UsuarioToken->requestId != $this->sessionId) {
                throw new Exception("Session Invalid", "10017");
            }

            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Balance = intval($Usuario->getBalance() * 100);

            $return = array(
                "balance" => $Balance,
                "statusCode" => "OK",
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un débito en la cuenta del usuario.
     *
     * Este método procesa una transacción de débito para un usuario en el sistema,
     * validando la sesión, la moneda y otros parámetros necesarios. También maneja
     * casos de rollback y errores relacionados con la transacción.
     *
     * @param string  $gameId        ID del juego asociado al débito.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $roundId       ID de la ronda de juego.
     * @param string  $transactionId ID de la transacción.
     * @param array   $datos         Datos adicionales para la transacción.
     * @param boolean $isfreeSpin    Indica si es una jugada gratuita (opcional).
     * @param boolean $gameRoundEnd  Indica si la ronda de juego ha terminado (opcional).
     * @param string  $currency      Moneda en la que se realiza la transacción.
     *
     * @return string JSON con el resultado de la operación, incluyendo el balance actualizado.
     * @throws Exception Si ocurre algún error durante el proceso.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $isfreeSpin = false, $gameRoundEnd, $currency)
    {
        try {

            $Proveedor = new Proveedor("", "EGT");
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            if ($this->token != "") {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                $UsuarioMandante = new UsuarioMandante($this->user);
            }

            if ($currency == "" || $currency != $UsuarioMandante->moneda) {
                throw new Exception("Moneda Incorrecta", "97");
            }

            if ($UsuarioToken->requestId != $this->sessionId) {
                throw new Exception("ERR_UNKNOWN", "10017");
            }

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Balance = intval($Usuario->getBalance() * 100);

            if ($debitAmount > $Balance) {
                throw new Exception("ERR_NOT_ENOUGH_MONEY", "20001");
            }

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);
            $this->transaccionApi->setIdentificador("EGT" . $roundId);

            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $isfreeSpin, [], true, $gameRoundEnd);

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Balance = intval($Usuario->getBalance() * 100);

            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "balance" => $Balance,
                "casinoTransferId" => (string)$transactionId,
                "bonusAmount" => 0,
                "realAmount" => intval($debitAmount * 100),
                "statusCode" => "OK",
            );

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta($return);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza una transacción de crédito en la cuenta del usuario.
     *
     * Este método procesa una transacción de crédito para un usuario en el sistema,
     * validando la sesión, la moneda y otros parámetros necesarios. También maneja
     * casos de rollback y errores relacionados con la transacción.
     *
     * @param string  $gameId        Producto asociado a la transacción (opcional).
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       ID de la ronda de juego.
     * @param string  $transactionId ID de la transacción.
     * @param array   $datos         Datos adicionales para la transacción.
     * @param boolean $isBonus       Indica si es un bono.
     * @param boolean $gameRoundEnd  Indica si la ronda de juego ha terminado.
     *
     * @return string JSON con el resultado de la operación, incluyendo el balance actualizado.
     * @throws Exception Si ocurre algún error durante el proceso.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos, $isBonus, $gameRoundEnd)
    {
        $this->transactionId = $transactionId;
        $this->type = "Credit";
        try {

            $Proveedor = new Proveedor("", "EGT");
            $Producto = new Producto("", $gameId);

            if ($this->token != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (Exception $e) {
                    $UsuarioMandante = new UsuarioMandante($this->user);
                }
            } else {
                $UsuarioMandante = new UsuarioMandante($this->user);
            }

            try {
                $TransaccionJuego = new TransaccionJuego("", "EGT" . $roundId);
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);
            $this->transaccionApi->setIdentificador("EGT" . $roundId);

            $Game = new Game();
            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $gameRoundEnd, false, $isBonus, false);

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Balance = intval($Usuario->getBalance() * 100);
            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "balance" => $Balance,
                "casinoTransferId" => (string)$transactionId,
                "bonusAmount" => 0,
                "realAmount" => intval($creditAmount * 100),
                "statusCode" => "OK",
            );

            $this->transaccionApi->setRespuesta($return);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un rollback de una transacción en la cuenta del usuario.
     *
     * Este método procesa una reversión de transacción para un usuario en el sistema,
     * validando la sesión, la transacción y otros parámetros necesarios. Maneja casos
     * de errores relacionados con la transacción y actualiza el balance del usuario.
     *
     * @param string $roundId       ID de la ronda de juego asociada al rollback.
     * @param string $transactionId ID de la transacción a revertir.
     *
     * @return string JSON con el resultado de la operación, incluyendo el balance actualizado.
     * @throws Exception Si ocurre algún error durante el proceso.
     */
    public function Rollback($roundId, $transactionId)
    {
        $this->transactionId = $transactionId;
        $this->type = "Rollback";

        try {
            $Proveedor = new Proveedor("", "EGT");

            if ($this->token != "") {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                $UsuarioMandante = new UsuarioMandante($this->user);
            }

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);

            try {
                $TransaccionJuego = new TransaccionJuego("", "EGT" . $roundId);
                $Subproveedor = new Subproveedor("", "EGT");
                $TransjuegoLog = new TransjuegoLog("",  $TransaccionJuego->transjuegoId, "", $TransaccionJuego->transaccionId . "_" . $Subproveedor->subproveedorId, $TransaccionJuego->productoId);
                $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());

                $transId = explode("_", $TransjuegoLog->transaccionId);
                $transId = $transId[0];

                $this->transaccionApi->setTransaccionId('ROLLBACK' . $transId);
                $this->transaccionApi->setIdentificador($TransaccionJuego->getTicketId());
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            $Game = new Game();
            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false, '', '', '', false, '', "I");

            $this->transaccionApi = $responseG->transaccionApi;

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Balance = intval($Usuario->getBalance() * 100);

            $return = array(
                "casinoTransferId" => (string)$transactionId,
                "statusCode" => "OK",
                "balance" => $Balance,
            );

            $this->transaccionApi->setRespuesta($return);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Converts error codes and messages into a standardized response format.
     *
     * This method maps internal error codes to provider-specific error codes
     * and messages. It also handles the creation and persistence of error
     * responses in the database if a transaction API object is available.
     *
     * @param integer $code             Internal error code.
     * @param string  $messageProveedor Error message from the provider.
     *
     * @return string JSON-encoded response with the standardized error information.
     */
    public function convertError($code, $messageProveedor)
    {

        $codeProveedor = "";
        $response = array();

        switch ($code) {
            case 23:
                $codeProveedor = "ERR_INTEGRITY_CHECK_FAILED";
                $messageProveedor = "Message integrity check failed";
                http_response_code(200);
                break;

            case 24:
                $codeProveedor = "ERR_FIELD_MISSING";
                $messageProveedor = "Missing fields";
                http_response_code(200);
                break;

            case 20002:
                $codeProveedor = "ERR_MISSING_HEADERS";
                $messageProveedor = "Message missing headers";
                http_response_code(200);
                break;

            case 10020:
                $codeProveedor = "ERR_INVALID_FIELDS";
                $messageProveedor = "Message invalid fields";
                http_response_code(200);
                break;

            case 21:
                $codeProveedor = "ERR_INVALID_TOKEN";
                $messageProveedor = "The supplied defence code token is not valid";
                http_response_code(200);
                break;

            case 10018:
                $codeProveedor = "ERR_INVALID_PLAYER_ID";
                $messageProveedor = "Invalid player ID";
                http_response_code(200);
                break;

            case 10017:
                $codeProveedor = "ERR_UNKNOWN";
                $messageProveedor = "Invalid Session";
                http_response_code(200);
                break;

            case 10027:
                $codeProveedor = "ERR_UNKNOWN";
                $messageProveedor = "Round Finished";
                http_response_code(200);
                break;

            case 20001:
                $codeProveedor = "ERR_NOT_ENOUGH_MONEY";
                $messageProveedor = "Insufficient funds";
                http_response_code(200);
                break;

            case 97:
                $codeProveedor = "ERR_INVALID_ACCOUNT";
                $messageProveedor = "Invalid currency";
                http_response_code(200);
                break;

            case 10001:

                $codeProveedor = "OK";
                $messageProveedor = "Already reversed";

                switch ($this->type) {
                    case "Credit":
                        $response = array(
                            "balance" => 0,
                            "casinoTransferId" => (string)$this->transactionId,
                            "bonusAmount" => 0,
                            "realAmount" => 0,
                            "statusCode" => "OK",
                        );

                        http_response_code(200);
                        break;

                    case "Rollback":
                        $codeProveedor = "ERR_TRANSFER_ROLLED_BACK";
                        $response = array(
                            "casinoTransferId" => null,
                            "statusCode" => $codeProveedor,
                            "balance" => 0,
                        );
                }
                http_response_code(200);
                break;

            case 10005:
                $codeProveedor = "ERR_TRANSFER_DOES_NOT_EXIST";
                $messageProveedor = "already reversed";
                http_response_code(200);
                break;

            default:
                $codeProveedor = "ERR_UNKNOWN";
                $messageProveedor = "Internal server error";
                http_response_code(200);
                break;
        }

        $respuesta = json_encode(array_merge($response, array(
            "statusCode" => $codeProveedor,
        )));

        if ($this->transaccionApi != null) {
            $this->transaccionApi->setTipo("R" . $this->transaccionApi->getTipo());
            $this->transaccionApi->setRespuestaCodigo("ERROR_" . $code);
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->insert($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();
        }

        return $respuesta;
    }
}
