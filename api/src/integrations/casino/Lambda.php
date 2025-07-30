<?php

/**
 * Clase Lambda
 *
 * Esta clase contiene métodos para manejar transacciones relacionadas con un casino en línea.
 * Proporciona funcionalidades como autenticación, consulta de balance, débito, crédito,
 * reversión de transacciones y validación de firmas.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
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
 * Clase Lambda
 *
 * Esta clase contiene métodos para manejar transacciones relacionadas con un casino en línea.
 * Proporciona funcionalidades como autenticación, consulta de balance, débito, crédito,
 * reversión de transacciones y validación de firmas.
 */
class Lambda
{
    /**
     * Identificador del operador.
     *
     * @var string
     */
    private $operadorId;

    /**
     * Usuario asociado a la transacción.
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
     * Objeto de transacción API.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * Identificador de la sesión.
     *
     * @var string
     */
    private $sessionId;

    /**
     * Tipo de operación actual.
     *
     * @var string
     */
    private $type;

    /**
     * Código del juego.
     *
     * @var string
     */
    private $GameCode;

    /**
     * Constructor de la clase Lambda.
     *
     * @param string $user  Usuario asociado a la transacción.
     * @param string $token Token de autenticación.
     */
    public function __construct($user = "", $token)
    {
        $this->token = $token;
        $this->user = $user;
    }

    /**
     * Método Auth
     *
     * Realiza la autenticación del usuario y devuelve información del jugador.
     *
     * @return string JSON con los datos del jugador autenticado.
     * @throws Exception Si el token es inválido o está vacío.
     */
    public function Auth()
    {
        $this->type = 'Auth';
        try {
            $Proveedor = new Proveedor("", "LAMBDA");

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
            } else {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                $UsuarioMandante = new UsuarioMandante($this->user);
            }
            $UsuarioOtraInfo = new UsuarioOtrainfo($UsuarioMandante->usuarioMandante);
            $fechaNacim = new DateTime($UsuarioOtraInfo->fechaNacim);
            $fechaISO8601 = $fechaNacim->setTime(0, 0, 0)->format("Y-m-d\TH:i:s.v\Z");
            $Registro = new Registro("", $UsuarioMandante->usuarioMandante);
            $Producto = new Producto($UsuarioToken->productoId);
            $Subproveedor = new Subproveedor($Producto->subproveedorId);

            $Ip = explode(",", $this->get_client_ip());
            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante, $Subproveedor->subproveedorId, $UsuarioToken->productoId);

            $Balance = floatval(number_format($responseG->saldo, 2, '.', ''));

            if ($Registro->sexo = "M") {
                $Registro->sexo = 1;
            } elseif ($Registro->sexo = "F") {
                $Registro->sexo = 2;
            } else {
                $Registro->sexo = 0;
            }

            $return = array(
                "playerId" => $responseG->usuarioId,
                "username" => $responseG->usuario,
                "currencyCode" => $responseG->moneda,
                "balance" => $Balance,
                "gender" => $Registro->sexo,
                "birthDate" => $fechaISO8601,
                "ipAddress" => $Ip[0],
                "countryCode" => $responseG->paisIso2,
                "sessionId" => $this->token,
                "operatorCode" => (string)$UsuarioMandante->mandante
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método Balance
     *
     * Consulta el balance del usuario autenticado.
     *
     * @return string JSON con el balance y la moneda del usuario.
     * @throws Exception Si el token o el usuario son inválidos.
     */
    public function Balance()
    {
        $this->type = 'Balance';
        try {
            $Proveedor = new Proveedor("", "LAMBDA");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);

            if ($this->token == "" && $this->user == "") {
                throw new Exception("Token vacio", "10011");
            }

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $Producto = new Producto($UsuarioToken->productoId);
            $Subproveedor = new Subproveedor($Producto->subproveedorId);
            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante, $Subproveedor->subproveedorId, $UsuarioToken->productoId);

            $Balance = floatval(number_format($responseG->saldo, 2, '.', ''));

            $return = array(
                "amount" => $Balance,
                "currencyCode" => $responseG->moneda,
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método Debit
     *
     * Realiza un débito en la cuenta del usuario.
     *
     * @param string  $gameId        ID del juego.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param array   $datos         Datos adicionales de la transacción.
     * @param boolean $isfreeSpin    Indica si es un giro gratis.
     * @param boolean $gameRoundEnd  Indica si la ronda del juego ha terminado.
     *
     * @return string JSON con los detalles de la transacción.
     * @throws Exception Si ocurre un error durante el débito.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $isfreeSpin = false, $gameRoundEnd = true)
    {
        try {
            if ($this->token == "" && $this->user == "") {
                throw new Exception("Token vacio", "10011");
            }

            $Proveedor = new Proveedor("", "LAMBDA");

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
                $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
            }catch (Exception $e){
                $Producto = new Producto($UsuarioToken->productoId);
            }

            $isRollback = false;
            try {
                $TransApi = new TransaccionApi("", "ROLLBACK" . $transactionId, $Proveedor->getProveedorId(), 'ERROR');
                if ($TransApi->tipo == 'ROLLBACK') {
                    $isRollback = true;
                }
            } catch (Exception $e) {
                $isRollback = false;
            }

            if ($isRollback) {
                throw new Exception("Rollback antes", "10004");
            } else {
                if ($isfreeSpin == true) {
                    $debitAmount = 0;
                }

                /*  Creamos la Transaccion API  */
                $this->transaccionApi = new TransaccionApi();
                $this->transaccionApi->setTransaccionId($transactionId);
                $this->transaccionApi->setTipo("DEBIT");
                $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
                $this->transaccionApi->setTValue(json_encode($datos));
                $this->transaccionApi->setUsucreaId(0);
                $this->transaccionApi->setUsumodifId(0);
                $this->transaccionApi->setValor($debitAmount);
                $this->transaccionApi->setIdentificador("LAMBDA" . $roundId . $UsuarioMandante->getUsumandanteId());


                if ($gameRoundEnd == true) {
                    $End = true;
                } else {
                    $End = false;
                }

                $Game = new Game();
                $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $isfreeSpin, [], true, $End);

                $Balance = floatval(number_format($responseG->saldo, 2, '.', ''));
                $this->transaccionApi = $responseG->transaccionApi;

                $return = array(
                    "transactionID" => $transactionId,
                    "balance" => $Balance,
                    "currencyCode" => $responseG->moneda,
                );

                /*  Guardamos la Transaccion Api necesaria de estado OK   */
                $this->transaccionApi->setRespuesta($return);
                $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                $TransaccionApiMySqlDAO->update($this->transaccionApi);
                $TransaccionApiMySqlDAO->getTransaction()->commit();

                return json_encode($return);
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método Credit
     *
     * Realiza un crédito en la cuenta del usuario.
     *
     * @param string  $Producto      Producto asociado al crédito.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param array   $datos         Datos adicionales de la transacción.
     * @param boolean $isBonus       Indica si es un bono.
     * @param boolean $gameRoundEnd  Indica si la ronda del juego ha terminado.
     *
     * @return string JSON con los detalles de la transacción.
     * @throws Exception Si ocurre un error durante el crédito.
     */
    public function Credit($Producto = "", $creditAmount, $roundId, $transactionId, $datos, $isBonus = false, $gameRoundEnd)
    {
        $this->type = 'Credit';
        try {
            if ($this->token == "" && $this->user == "") {
                throw new Exception("Token vacio", "10011");
            }

            $Proveedor = new Proveedor("", "LAMBDA");

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

            $Game = new Game();
            if ($datos->freeSpinId != "") {
                $isBonus = true;
            }

            $isRollback = false;

            try {
                $TransaccionJuego = new TransaccionJuego("", "LAMBDA" . $roundId . $UsuarioMandante->getUsumandanteId());
                $TransjuegoLog = new TransjuegoLog("", $TransaccionJuego->transjuegoId, 'ROLLBACK');
                if ($TransjuegoLog->tipo == 'ROLLBACK') {
                    $isRollback = true;
                }
            } catch (Exception $e) {
                $isRollback = false;
            }

            if ($isRollback) {
                throw new Exception("Rollback antes", "10017");
            } else {
                try {
                    $TransaccionJuego = new TransaccionJuego("", "LAMBDA" . $roundId . $UsuarioMandante->getUsumandanteId());
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
                $this->transaccionApi->setIdentificador("LAMBDA" . $roundId . $UsuarioMandante->getUsumandanteId());

                $ProductoMandante = new ProductoMandante("", "", $TransaccionJuego->productoId);
                $Producto = new Producto($ProductoMandante->productoId);

                if ($gameRoundEnd == true) {
                    $End = true;
                } else {
                    $End = false;
                }

                $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $End, false, $isBonus, false);

                $Balance = floatval(number_format($responseG->saldo, 2, '.', ''));
                $this->transaccionApi = $responseG->transaccionApi;


                $return = array(
                    "transactionID" => $transactionId,
                    "balance" => $Balance,
                    "currencyCode" => $responseG->moneda,
                );

                $this->transaccionApi->setRespuesta($return);
                $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                $TransaccionApiMySqlDAO->update($this->transaccionApi);
                $TransaccionApiMySqlDAO->getTransaction()->commit();

                return json_encode($return);
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método Rollback
     *
     * Realiza una reversión de una transacción previa.
     *
     * @param float   $rollbackAmount Monto a revertir.
     * @param string  $roundId        ID de la ronda.
     * @param string  $transactionId  ID de la transacción.
     * @param boolean $gameRoundEnd   Indica si la ronda del juego ha terminado.
     * @param string  $gameId         ID del juego.
     *
     * @return string JSON con los detalles de la reversión.
     * @throws Exception Si ocurre un error durante la reversión.
     */
    public function Rollback($rollbackAmount = "", $roundId, $transactionId, $gameRoundEnd, $gameId)
    {
        $this->type = 'Rollback';

        try {
            $Proveedor = new Proveedor("", "LAMBDA");

            try {
                $UsuarioMandante = new UsuarioMandante($this->user);
            } catch (Exception $e) {
                throw new Exception("User Invalid", "10018");
            }

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

            $isRollback = false;

            if ($isRollback) {
                throw new Exception("Rollback antes", "10017");
            } else {
                $aggtrans = false;
                try {
                    $TransaccionJuego = new TransaccionJuego('', "LAMBDA" . $roundId . $UsuarioMandante->getUsumandanteId());
                    if($TransaccionJuego->getValorPremio() != 0){
                        $aggtrans = false;
                    }
                } catch (Exception $e) {
                    $aggtrans = false;
                }

                if ($aggtrans) {
                    throw new Exception("Ronda cerrada", "10016");
                } else {
                    /*  Creamos la Transaccion API  */
                    $this->transaccionApi = new TransaccionApi();
                    $this->transaccionApi->setTipo("ROLLBACK");
                    $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
                    $this->transaccionApi->setUsucreaId(0);
                    $this->transaccionApi->setUsumodifId(0);
                    $this->transaccionApi->setValor($rollbackAmount);
                    $AllowCreditTransaction = true;

                    try {
                        $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
                        $TransaccionJuego = new TransaccionJuego("", "LAMBDA" . $roundId . $UsuarioMandante->getUsumandanteId());
                        $TransjuegoLog = new TransjuegoLog("",  $TransaccionJuego->transjuegoId, "", $transactionId."_".$Producto->subproveedorId);
                        $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);
                        $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());

                        if (strpos($TransjuegoLog->getTipo(), 'DEBIT') !== false || strpos($TransjuegoLog->getTipo(), 'CREDIT') !== false) {
                            $transId = explode("_", $TransjuegoLog->transaccionId);
                            $transId = $transId[0];

                            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transId);
                            $this->transaccionApi->setIdentificador($TransaccionJuego->getTicketId());
                        } else {
                            throw new Exception("Transaccion no es Debit", "10006");
                        }
                    } catch (Exception $e) {
                        throw new Exception("Transaccion no existe", "10005");
                    }

                    $Game = new Game();

                    if ($gameRoundEnd == true) {
                        $end = 'I';
                    } else {
                        $end = 'A';
                    }

                    $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false, '', '', '', $AllowCreditTransaction, '', $end);

                    $this->transaccionApi = $responseG->transaccionApi;
                    $Balance = floatval(number_format($responseG->saldo, 2, '.', ''));

                    $return = array(
                        "transactionID" => $transactionId,
                        "balance" => $Balance,
                        "currencyCode" => $responseG->moneda,
                    );

                    $this->transaccionApi->setRespuesta($return);
                    $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                    $TransaccionApiMySqlDAO->update($this->transaccionApi);
                    $TransaccionApiMySqlDAO->getTransaction()->commit();

                    return json_encode($return);
                }
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Valida la firma de una solicitud utilizando una clave pública.
     *
     * Este método verifica la validez de una firma digital proporcionada en la solicitud
     * comparándola con una cadena concatenada de datos y una clave pública asociada.
     *
     * @param object $data Objeto que contiene los datos necesarios para la validación de la firma.
     *                     Debe incluir propiedades como `timestamp`, `operatorCode`, `casinoCode`,
     *                     `gameTypeId`, `playerId`, `token`, `sessionId`, entre otros.
     *
     * @return string|null Devuelve un error en formato JSON si la validación falla, o `null` si es válida.
     * @throws Exception Si la firma es inválida o faltan datos requeridos.
     */
    function validateSignature($data)
    {
        try {
            $signatureBase64 = $_SERVER['HTTP_SIGNATURE'] ?? '';
            if (empty($signatureBase64)) {
                throw new Exception('La firma es obligatoria.', 20002);
            }
            $signature = base64_decode($signatureBase64);

            $decodedTimestamp = urldecode($data->timestamp);
            $date = new DateTime($decodedTimestamp, new DateTimeZone('UTC'));
            $timestampFormatted = $date->format('YmdHis');

            $concatenatedString =
                ($data->operatorCode ?? '') .
                ($data->casinoCode ?? '') .
                ($data->gameTypeId ?? '') .
                ($data->playerId ?? '') .
                ($data->token ?? '') .
                ($data->sessionId ?? '') .
                ($data->canceledTransactionUniqueId ?
                    ($data->transactionUniqueId ?? '') .
                    ($data->canceledTransactionUniqueId ?? '') .
                    ($data->canceledTransactionReference ?? '') .
                    ($data->amount ?? '') .
                    ($data->currencyCode ?? '') .
                    ($data->roundId ?? '') .
                    ($data->description ?? '') .
                    $timestampFormatted .
                    ($data->ipAddress ?? '') :
                    ($data->amount ?? '') .
                    ($data->currencyCode ?? '') .
                    ($data->transactionUniqueId ?? '') .
                    ($data->canceledTransactionUniqueId ?? '') .
                    ($data->roundId ?? '') .
                    ($data->betId ?? '') .
                    ($data->freeSpinId ?? '') .
                    ($data->description ?? '') .
                    ($data->transactionUniqueId ?
                        $timestampFormatted . ($data->ipAddress ?? '') :
                        ($data->ipAddress ?? '') . $timestampFormatted));


            $Proveedor = new Proveedor("", "LAMBDA");
            $Producto = new Producto("", $data->gameTypeId, $Proveedor->getProveedorId());

            $UsuarioToken = new UsuarioToken($this->token, $Producto->proveedorId);
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $pubkey_pem = "-----BEGIN PUBLIC KEY-----\n$credentials->public\n-----END PUBLIC KEY-----";

            $publicKey = openssl_pkey_get_public($pubkey_pem);
            $isValid = openssl_verify($concatenatedString, $signature, $publicKey, OPENSSL_ALGO_SHA512);
            openssl_free_key($publicKey);

            if ($isValid !== 1) {
                throw new Exception('Firma inválida.', 20002);
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método convertError
     *
     * Convierte un código de error en una respuesta JSON.
     *
     * @param integer $code             Código de error.
     * @param string  $messageProveedor Mensaje del proveedor.
     *
     * @return string Respuesta JSON con el código y mensaje de error.
     */
    public function convertError($code, $messageProveedor)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $response = array();

        switch ($code) {
            case 23:
                $codeProveedor = 440;
                $messageProveedor = "Operator not found";
                http_response_code(404); // Not Found
                break;

            case 26:
                $codeProveedor = 441;
                $messageProveedor = "Invalid game id";
                http_response_code(400); // Bad Request
                break;

            case 24:
                $codeProveedor = 442;
                $messageProveedor = "Unknown player";
                http_response_code(404); // Not Found
                break;

            case 10017:
                $codeProveedor = 443;
                $messageProveedor = "Invalid currency";
                http_response_code(400); // Bad Request
                break;

            case 20001:
                $codeProveedor = 445;
                $messageProveedor = "Not enough balance";
                http_response_code(402); // Payment Required
                break;

            case 10010:
                $codeProveedor = 446;
                $messageProveedor = "Transaction already exists";
                http_response_code(409); // Conflict
                break;

            case 28:
                $codeProveedor = 447;
                $messageProveedor = "Transaction not found";
                http_response_code(404); // Not Found
                break;

            case 10011:
                $codeProveedor = 448;
                $messageProveedor = "Invalid Token";
                http_response_code(401); // Unauthorized
                break;

            case 20000:
                $codeProveedor = 449;
                $messageProveedor = "Session Expired";
                http_response_code(401); // Unauthorized
                break;

            case 20002:
                $codeProveedor = 450;
                $messageProveedor = "Invalid Signature";
                http_response_code(401); // Unauthorized
                break;

            default:
                $codeProveedor = 499;
                $messageProveedor = "General Error";
                http_response_code(400); // Bad Request
                break;
        }

        $respuesta = json_encode(array_merge($response, array(
            "code" => $codeProveedor,
            "message" => $messageProveedor
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

    /**
     * Método get_client_ip
     *
     * Obtiene la dirección IP del cliente.
     *
     * @return string Dirección IP del cliente.
     */
    function get_client_ip()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP')) {
            $ipaddress = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ipaddress = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ipaddress = getenv('HTTP_FORWARDED');
        } elseif (getenv('REMOTE_ADDR')) {
            $ipaddress = getenv('REMOTE_ADDR');
        } else {
            $ipaddress = 'UNKNOWN';
        }
        return $ipaddress;
    }


}