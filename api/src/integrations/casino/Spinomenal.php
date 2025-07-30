<?php

/**
 * Clase Spinomenal
 *
 * Esta clase proporciona una integración con el proveedor de juegos SPINOMENAL.
 * Contiene métodos para manejar transacciones como débitos, créditos, autenticación,
 * balance, y otras operaciones relacionadas con juegos.
 *
 * @category Integración
 * @package  Backend\integrations\casino
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */

namespace Backend\integrations\casino;

use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Mandante;
use Backend\dto\PromocionalLog;
use Backend\dto\Subproveedor;
use Backend\dto\SubproveedorMandantePais;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransaccionApi;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioToken;
use Backend\dto\Producto;
use Backend\dto\ProductoMandante;
use Backend\dto\Proveedor;
use Backend\integrations\casino\SPINOMENALSERVICES;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransaccionApiMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;
use DateTime;
use Exception;
use phpDocumentor\Reflection\Types\This;

/**
 * Clase Spinomenal
 *
 * Esta clase maneja la integración con el proveedor de juegos SPINOMENAL,
 * proporcionando métodos para realizar operaciones como autenticación,
 * transacciones de débito y crédito, obtención de balance, entre otros.
 */
class Spinomenal
{
    /**
     * Identificador del operador.
     *
     * @var mixed
     */
    private $operadorId;

    /**
     * Token de autenticación.
     *
     * @var string
     */
    private $token;

    /**
     * Identificador del usuario.
     *
     * @var string
     */
    private $usuarioId;

    /**
     * Objeto de transacción API.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * Datos adicionales de la transacción.
     *
     * @var array
     */
    private $data;

    /**
     * Código de asignación válido.
     *
     * @var boolean
     */
    private $ValAssingCode;

    /**
     * Identificador del ticket.
     *
     * @var string
     */
    private $ticketId;

    /**
     * Método de la transacción.
     *
     * @var string
     */
    private $method;

    /**
     * Identificador de la transacción de débito.
     *
     * @var string
     */
    private $transactionIdDebit;

    /**
     * Identificador de la transacción de crédito.
     *
     * @var string
     */
    private $transactionIdCredit;

    /**
     * Tipo de transacción.
     *
     * @var string
     */
    private $tipo = "";

    /**
     * Código del juego.
     *
     * @var string
     */
    private $GameCode = "";

    /**
     * Credenciales del proveedor.
     *
     * @var object
     */
    private $credentials = '';

    /**
     * Constructor de la clase Spinomenal.
     *
     * Inicializa las credenciales y configura el entorno para interactuar con el proveedor SPINOMENAL.
     *
     * @param string $token     Token de autenticación.
     * @param string $usuarioId ID del usuario (opcional).
     * @param string $GameCode  Código del juego (opcional).
     */
    public function __construct($token, $usuarioId = "", $GameCode = "")
    {
        $this->token = $token;
        $this->usuarioId = $usuarioId;
        $this->GameCode = $GameCode;

        $Proveedor = new Proveedor("", "SPINOMENAL");

        if ($this->token != "") {
            try {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } catch (Exception $e) {
                $UsuarioMandante = new UsuarioMandante($this->usuarioId);
            }
        } else {
            $UsuarioMandante = new UsuarioMandante($this->usuarioId);
        }

        $Producto = new Producto("", $this->GameCode, $Proveedor->getProveedorId());

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $this->credentials = json_decode($SubproveedorMandantePais->getCredentials());
    }

    /**
     * Obtiene el ID del operador.
     *
     * @return mixed ID del operador.
     */
    public function getOperadorId()
    {
        return $this->operadorId;
    }

    /**
     * Autentica al usuario con el proveedor SPINOMENAL.
     *
     * @param string $TimeStamp Marca de tiempo de la solicitud.
     * @param string $PartnerId ID del socio.
     *
     * @return string Respuesta en formato JSON con los detalles de la autenticación.
     * @throws Exception Si ocurre un error durante la autenticación.
     */
    public function Auth($TimeStamp, $PartnerId)
    {
        try {
            $Proveedor = new Proveedor("", "SPINOMENAL");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);

            if ($this->token == "") {
                throw new Exception("Token vacio", "10030");
            }

            if ($this->credentials->partnerId != $PartnerId) {
                throw new Exception("PartnerId Invalido ", "25");
            }

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $Mandante = new Mandante($UsuarioMandante->mandante);
            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $return = array(
                "PlayerInput" => array(
                    "PlayerId" => $UsuarioMandante->usumandanteId,
                    "Currency" => $responseG->moneda,
                    "TypeId" => 0,
                ),
                "Balance" => $responseG->saldo,
                "ErrorCode" => 0,
                "ErrorMessage" => null,
                "TimeStamp" => $TimeStamp,
            );


            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Reinicia el token del usuario.
     *
     * @param string $TimeStamp Marca de tiempo de la solicitud.
     *
     * @return string Respuesta en formato JSON con el nuevo token.
     * @throws Exception Si ocurre un error durante el reinicio del token.
     */
    public function ResetToken($TimeStamp)
    {
        try {
            $Proveedor = new Proveedor("", "SPINOMENAL");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);

            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
            $token = $UsuarioToken->createToken();
            $UsuarioToken->setToken($token);
            $UsuarioToken->setProductoId($UsuarioToken->productoId);
            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
            $UsuarioTokenMySqlDAO->update($UsuarioToken);
            $UsuarioTokenMySqlDAO->getTransaction()->commit();

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $Mandante = new Mandante($UsuarioMandante->mandante);
            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $return = array(
                "TimeStamp" => $TimeStamp,
                "GameToken" => $UsuarioToken->getToken(),
                "ErrorCode" => 0,
                "ErrorMessage" => null,
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del usuario.
     *
     * @param string $TimeStamp Marca de tiempo de la solicitud.
     * @param string $PartnerId ID del socio.
     *
     * @return string Respuesta en formato JSON con el balance del usuario.
     * @throws Exception Si ocurre un error al obtener el balance.
     */
    public function getBalance($TimeStamp, $PartnerId)
    {
        try {
            $Proveedor = new Proveedor("", "SPINOMENAL");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("BALANCE");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);

            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            if ($this->credentials->partnerId != $PartnerId) {
                throw new Exception("PartnerId Invalido ", "25");
            }

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);

            $return = array(
                "TimeStamp" => $TimeStamp,
                "Balance" => round($responseG->saldo, 2),
                "ErrorCode" => 0,
                "ErrorMessage" => null,
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un débito en la cuenta del usuario.
     *
     * @param string  $gameId        ID del juego.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param array   $datos         Datos adicionales de la transacción.
     * @param boolean $freespin      Indica si es un giro gratis (opcional).
     * @param string  $TimeStamp     Marca de tiempo de la solicitud.
     *
     * @return string Respuesta en formato JSON con los detalles del débito.
     * @throws Exception Si ocurre un error durante el débito.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $freespin = false, $TimeStamp)
    {
        $this->tipo = "DEBIT";
        $this->data = $datos;

        try {
            $Proveedor = new Proveedor("", "SPINOMENAL");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);

            if ($this->token != "") {
                // Obtenemos el Usuario Token con el token
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                //  Obtenemos el Usuario Mandante con el Usuario Token
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                $UsuarioMandante = new UsuarioMandante($this->usuarioId);
            }

            $this->transaccionApi->setIdentificador("SPINOMENAL" . $roundId);

            //Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
            //Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());
            $Game = new Game();

            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin);


            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "Balance" => $responseG->saldo,
                "ErrorCode" => 0,
                "ExtTransactionId" => $responseG->TransaccionId,
                "TimeStamp" => $TimeStamp,
                "ErrorMessage" => null,
            );

            //Guardamos la Transaccion Api necesaria de estado OK
            $this->transaccionApi->setRespuestaCodigo("OK");
            $this->transaccionApi->setRespuesta(json_encode($return));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un crédito en la cuenta del usuario.
     *
     * @param string  $gameId        ID del juego.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param array   $datos         Datos adicionales de la transacción.
     * @param boolean $isEndRound    Indica si es el final de la ronda.
     * @param string  $TimeStamp     Marca de tiempo de la solicitud.
     *
     * @return string Respuesta en formato JSON con los detalles del crédito.
     * @throws Exception Si ocurre un error durante el crédito.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos, $isEndRound, $TimeStamp)
    {
        $this->tipo = "CREDIT";

        $this->data = $datos;

        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try {
            //Obtenemos el Proveedor con el abreviado Spinomenal
            $Proveedor = new Proveedor("", "SPINOMENAL");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);
            $this->transaccionApi->setIdentificador($roundId . "Spinomenal");

            /*  Obtenemos el Usuario Token con el token */
            if ($this->token != "") {
                // Obtenemos el Usuario Token con el token
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

                //  Obtenemos el Usuario Mandante con el Usuario Token
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                $UsuarioMandante = new UsuarioMandante($this->usuarioId);
            }

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "SPINOMENAL");

            //  Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            //  Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());


            //  Agregamos Elementos a la Transaccion API
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());

            $Game = new Game();

            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $isEndRound);

            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "Balance" => $responseG->saldo,
                "ErrorCode" => 0,
                "ExtTransactionId" => $responseG->TransaccionId,
                "TimeStamp" => $TimeStamp,
                "ErrorMessage" => null,
            );

            //Guardamos la Transaccion Api necesaria de estado OK
            $this->transaccionApi->setRespuestaCodigo("OK");
            $this->transaccionApi->setRespuesta(json_encode($return));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza una operación combinada de débito y crédito.
     *
     * @param string  $gameId              ID del juego.
     * @param string  $ticketId            ID del ticket.
     * @param float   $debitAmount         Monto a debitar.
     * @param float   $creditAmount        Monto a acreditar.
     * @param string  $transactionIdDebit  ID de la transacción de débito.
     * @param string  $transactionIdCredit ID de la transacción de crédito.
     * @param array   $datos               Datos adicionales de la transacción.
     * @param boolean $freespin            Indica si es un giro gratis.
     * @param string  $TransactionType     Tipo de transacción.
     * @param string  $PartnerId           ID del socio.
     * @param string  $token               Token de autenticación.
     * @param boolean $IsRetry             Indica si es un reintento.
     * @param boolean $ValAssingCode       Código de asignación válido (opcional).
     * @param boolean $IsEndRound          Indica si es el final de la ronda (opcional).
     *
     * @return string Respuesta en formato JSON con los detalles de la operación.
     * @throws Exception Si ocurre un error durante la operación.
     */
    public function DebitAndCredit($gameId, $ticketId, $debitAmount, $creditAmount, $transactionIdDebit, $transactionIdCredit, $datos, $freespin, $TransactionType, $PartnerId, $token, $IsRetry, $ValAssingCode = false, $IsEndRound = false)
    {
        $this->method = $TransactionType;
        $this->transactionIdDebit = $transactionIdDebit;
        $this->transactionIdCredit = $transactionIdCredit;
        $this->data = $datos;
        $this->ValAssingCode = $ValAssingCode;
        $this->ticketId = $ticketId;

        //  Obtenemos el Proveedor con el abreviado SPINOMENAL
        $Proveedor = new Proveedor("", "SPINOMENAL");

        try {
            if ( ! $IsRetry) {
                if ($this->token != "") {
                    try {
                        //Obtenemos el Usuario Token con el token
                        $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    } catch (Exception $e) {
                        throw new Exception("Token invalido", "50007");
                    }
                }
            }

            try {
                $UserVal = new UsuarioMandante($this->usuarioId);
            } catch (Exception $e) {
                throw new Exception("User Invalid", "24");
            }

            if ($this->token == "" && $this->usuarioId == "") {
                throw new Exception("Token vacio", "10011");
            }

            if ($this->credentials->partnerId != $PartnerId) {
                throw new Exception("PartnerId Invalido ", "25");
            }

            if ($this->token != "") {
                try {
                    //Obtenemos el Usuario Token con el token
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId(), "", "", "");
                    //Obtenemos el Usuario Mandante con el Usuario Token
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                    $Producto = new Producto($UsuarioToken->getProductoId());
                } catch (Exception $e) {
                    //  Obtenemos el Usuario Token con el token
                    $UsuarioToken = new UsuarioToken('', $Proveedor->getProveedorId(), $this->usuarioId);
                    //  Obtenemos el Usuario Mandante con el Usuario Token
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                    $Producto = new Producto($UsuarioToken->getProductoId());
                }
            } else {
                //  Obtenemos el Usuario Token con el token
                $UsuarioToken = new UsuarioToken('', $Proveedor->getProveedorId(), $this->usuarioId);
                //  Obtenemos el Usuario Mandante con el Usuario Token
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                $Producto = new Producto($UsuarioToken->getProductoId());
            }

            //  Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionIdDebit);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);
            $this->transaccionApi->setIdentificador($UsuarioMandante->getUsumandanteId() . "SPINOMENAL" . $ticketId);

            //  Creamos la Transaccion API
            $Credit = new TransaccionApi();
            $Credit->setTransaccionId("credit" . $transactionIdCredit);
            $Credit->setTipo("CREDIT");
            $Credit->setProveedorId($Proveedor->getProveedorId());
            $Credit->setTValue(json_encode($datos));
            $Credit->setUsucreaId(0);
            $Credit->setUsumodifId(0);
            $Credit->setValor($creditAmount);
            $Credit->setIdentificador($UsuarioMandante->getUsumandanteId() . "SPINOMENAL" . $ticketId);
            $Game = new Game();

            $this->Producto = $Producto;
            $this->UsuarioMandanteP = $UsuarioMandante;

            $responseG = $Game->debitAndcredit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin, [], true, false, $Credit, $IsEndRound);
            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "Balance" => $responseG->saldo,
                "ErrorCode" => 0,
                "ExtTransactionId" => $responseG->transaccionId,
            );

            //Guardamos la Transaccion Api necesaria de estado OK
            $this->transaccionApi->setRespuestaCodigo("OK");
            $this->transaccionApi->setRespuesta(json_encode($return));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Inicia una ronda de giros gratis.
     *
     * @param string $PartnerId ID del socio.
     *
     * @return string Respuesta en formato JSON con los detalles de la operación.
     * @throws Exception Si ocurre un error durante la operación.
     */
    public function FreeRoundStart($PartnerId)
    {
        try {
            $Proveedor = new Proveedor("", "SPINOMENAL");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);

            if ($this->token == "") {
                throw new Exception("Token vacio", "10030");
            }

            if ($this->credentials->partnerId != $PartnerId) {
                throw new Exception("PartnerId Invalido ", "25");
            }

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $Mandante = new Mandante($UsuarioMandante->mandante);
            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $return = array(
                "Balance" => $responseG->saldo,
                "ErrorCode" => 0,

            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Verifica una transacción específica.
     *
     * @param string $RoundId       ID de la ronda.
     * @param string $transactionId ID de la transacción.
     *
     * @return string Respuesta en formato JSON con los detalles de la transacción.
     * @throws Exception Si ocurre un error durante la verificación.
     */
    public function CheckTransaccion($RoundId, $transactionId)
    {
        try {
            $Proveedor = new Proveedor("", "SPINOMENAL");
            $Subproveedor = new Subproveedor("", "SPINOMENAL");

            if ($this->token != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (Exception $e) {
                    $UsuarioMandante = new UsuarioMandante($this->usuarioId);
                }
            } else {
                $UsuarioMandante = new UsuarioMandante($this->usuarioId);
            }

            try {
                $transjuego = new TransjuegoLog("", "", "", 'creditC_' . $RoundId . '_' . $Subproveedor->subproveedorId, $Subproveedor->subproveedorId);
            } catch (Exception $e) {
                throw new Exception("Ronda incorrecta", "28");
            }

            try {
                $transaction = new TransaccionApi("", $transactionId, $Proveedor->proveedorId);
            } catch (Exception $e) {
                $transaction = new TransaccionApi("", $transactionId, $Proveedor->proveedorId, 'ERROR');
                if ($transaction->respuestaCodigo == 'ERROR') {
                    throw new Exception("Transaccion incorrecta", "28");
                }
            }

            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $return = array(
                "Balance" => $responseG->saldo,
                "ErrorCode" => 0,
                "ExtTransactionId" => $transjuego->transjuegologId
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Finaliza una ronda de juego.
     *
     * @param string $roundId       ID de la ronda.
     * @param array  $datos         Datos adicionales de la transacción.
     * @param string $TimeStamp     Marca de tiempo de la solicitud.
     * @param string $TransactionId ID de la transacción.
     *
     * @return string Respuesta en formato JSON con los detalles de la operación.
     * @throws Exception Si ocurre un error durante la operación.
     */
    public function EndRound($roundId, $datos, $TimeStamp, $TransactionId)
    {
        $this->data = $datos;

        try {
            /*  Obtenemos el Proveedor con el abreviado SPINOMENAL */
            $Proveedor = new Proveedor("", "SPINOMENAL");

            if ($this->usuarioId != "") {
                $UsuarioMandante = new UsuarioMandante($this->usuarioId);
            } else {
                $UsuarioMandante = new UsuarioMandante($this->usuarioId);
            }

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($roundId . "ENDROUND");
            $this->transaccionApi->setTipo("ENDROUND");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);
            $this->transaccionApi->setIdentificador($UsuarioMandante->getUsumandanteId() . "SPINOMENAL" . $roundId);

            $Game = new Game();

            $responseG = $Game->endRound($this->transaccionApi);
            $this->transaccionApi = $responseG->transaccionApi;

            /*  Retornamos el mensaje satisfactorio  */
            $return = array(
                "Balance" => $responseG->saldo,
                "ErrorCode" => 0,
                "ExtTransactionId" => $responseG->transaccionId,
                "TimeStamp" => $TimeStamp,
                "ErrorMessage" => null,
            );

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta($return);
            $this->transaccionApi->setIdentificador($TransactionId);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un rollback de una transacción.
     *
     * @param string $roundId       ID de la ronda.
     * @param string $transactionId ID de la transacción.
     * @param string $player        ID del jugador.
     * @param array  $datos         Datos adicionales de la transacción.
     * @param string $TimeStamp     Marca de tiempo de la solicitud.
     *
     * @return string Respuesta en formato JSON con los detalles del rollback.
     * @throws Exception Si ocurre un error durante el rollback.
     */
    public function Rollback($roundId, $transactionId, $player, $datos, $TimeStamp)
    {
        $this->tipo = "ROLLBACK";

        $this->data = $datos;

        try {
            $Proveedor = new Proveedor("", "SPINOMENAL");

            //  Obtenemos el Usuario Token con el token
            $UsuarioToken = new UsuarioToken('', $Proveedor->getProveedorId(), $this->usuarioId);
            //  Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            //Obtenemos el Proveedor con el abreviado Spinomenal
            $Proveedor = new Proveedor("", "SPINOMENAL");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);
            $this->transaccionApi->setIdentificador($UsuarioMandante->getUsumandanteId() . "SPINOMENAL" . $roundId);


            try {
                $TransaccionApi2 = new TransaccionApi("", $transactionId, $Proveedor->getProveedorId());

                $producto = $TransaccionApi2->getProductoId();

                $UsuarioMandante = new UsuarioMandante($TransaccionApi2->getUsuarioId());
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            $this->transaccionApi->setProductoId($producto);

            $Game = new Game();

            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false, "", true, false, true);

            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "Balance" => $responseG->saldo,
                "ErrorCode" => 0,
                "ExtTransactionId" => $responseG->TransaccionId,
                "TimeStamp" => $TimeStamp,
                "ErrorMessage" => null,
            );

            //Guardamos la Transaccion Api necesaria de estado OK
            $this->transaccionApi->setRespuestaCodigo("OK");
            $this->transaccionApi->setRespuesta(json_encode($return));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Genera una firma para los datos proporcionados.
     *
     * @param string $datos Datos a firmar.
     *
     * @return string Firma generada.
     */
    public function Sign($datos)
    {
        $signature = md5($datos . $this->credentials->PK);

        return $signature;
    }

    /**
     * Convierte un error en un formato de respuesta estándar.
     *
     * @param integer $code    Código de error.
     * @param string  $message Mensaje de error.
     *
     * @return string Respuesta en formato JSON con los detalles del error.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $response = array();

        $Proveedor = new Proveedor("", "SPINOMENAL");
        $Subproveedor = new Subproveedor("", "SPINOMENAL");

        if ($this->token != "") {
            try {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } catch (Exception $e) {
                $UsuarioMandante = new UsuarioMandante($this->usuarioId);
            }
        } else {
            $UsuarioMandante = new UsuarioMandante($this->usuarioId);
        }

        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

        switch ($code) {
            case 25:
                $codeProveedor = 5001;
                $messageProveedor = "PartnerIdNotFound";
                break;

            case 27:
                $codeProveedor = 5002;
                $messageProveedor = "GameCodeNotFound";
                break;

            case 21010:
                $codeProveedor = 5003;
                $messageProveedor = "GameIsntAttachedToBrand";;
                break;

            case 10017:
                $codeProveedor = 5007;
                $messageProveedor = "CurrencyCodeNotFound";
                break;

            case 10017:
                $codeProveedor = 5008;
                $messageProveedor = "DifferentCurrencyAlreadyAssigned";
                break;

            case 100000:
                $codeProveedor = 5016;
                $messageProveedor = "GeneralError";
                break;

            case 100001:
                $codeProveedor = 6001;
                $messageProveedor = "InvalidParameters";
                break;

            case 20002:
                $codeProveedor = 6002;
                $messageProveedor = "InvalidSignature";
                break;

            case 50007:
                $codeProveedor = 6003;
                $messageProveedor = "InvalidToken";
                break;

            case 20003:
                $codeProveedor = 6004;
                $messageProveedor = "PlayerAccountLocked";
                break;

            case 20013:
                $codeProveedor = 6006;
                $messageProveedor = "ResponsibleGamingLimit";
                break;

            case 24:
                $codeProveedor = 6007;
                $messageProveedor = "UnknownPlayerId";
                break;

            case 28:
                $codeProveedor = 6010;
                $messageProveedor = "TransactionIdNotFound";
                break;

            case 29:
                $codeProveedor = 6010;
                $messageProveedor = "TransactionIdNotFound";
                break;

            case 20001:
                $codeProveedor = 6011;
                $messageProveedor = "insufficientFunds";
                break;

            case 10002:
                $codeProveedor = 6016;
                $messageProveedor = "InvalidBetNotWithTermsAndConditions";
                break;

            case 26:
                $codeProveedor = 6017;
                $messageProveedor = "InvalidTargetGameCode";
                break;

            case 10027:
                $codeProveedor = 7011;
                $messageProveedor = "FR_InvalidPromoOnOperatorSide";
                break;

            case 10001:
                $codeProveedor = 7011;
                $messageProveedor = "FR_InvalidPromoOnOperatorSide";

                if ($this->method == 'BetAndWin' || $this->method == 'Win' || $this->method == 'FreeRounds_End' || $this->method == 'FreeRounds_Win') {
                    try {
                        $transjuego = new TransjuegoLog("", "", "", 'credit' . $this->transactionIdCredit . '_' . $Subproveedor->subproveedorId, $Subproveedor->subproveedorId);
                        $transjuegoid = new TransaccionJuego($transjuego->transjuegoId);
                        $round = explode('SPINOMENAL', $transjuegoid->ticketId);
                        $TransId = explode('_', $transjuego->transaccionId);
                        $IdeP = true;

                        if ($this->ValAssingCode) {
                            if ($round[1] == $this->ticketId) {
                                $IdeP = true;
                            } else {
                                $IdeP = false;
                            }
                        } elseif ($this->ValAssingCode) {
                            if ($this->transactionIdCredit == $TransId[1]) {
                                $IdeP = false;
                            } else {
                                $IdeP = true;
                            }
                        }

                        if ($IdeP) {
                            $result = array(
                                "Balance" => $Usuario->getBalance(),
                                "ErrorCode" => 0,
                                "ExtTransactionId" => $transjuego->transjuegologId,
                            );
                        }
                    } catch (Exception $e) {
                    }
                }

                break;

            default:
                $codeProveedor = 6008;
                $messageProveedor = "InternalError";
                break;
        }

        if ($result != '') {
            $respuesta = json_encode($result);
        } else {
            $respuesta = json_encode(array_merge($response, array(
                "ErrorCode" => $codeProveedor,
                "ErrorMessage" => $messageProveedor,
                "ErrorDisplayText" => $messageProveedor,
            )));
        }

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