<?php

/**
 * Clase Tadagaming para la integración con el proveedor TADAGAMING.
 *
 * Este archivo contiene la implementación de la clase Tadagaming, que incluye
 * métodos para realizar operaciones como autenticación, consulta de saldo,
 * débito, crédito y reversión de transacciones en el sistema del proveedor.
 *
 * @category Red
 * @package  API
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
use Backend\integrations\casino\TADAGAMINGSERVICES;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransaccionApiMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;
use DateTime;
use Exception;
use phpDocumentor\Reflection\Types\This;

/**
 * Clase Tadagaming
 *
 * Proporciona métodos para interactuar con el sistema del proveedor TADAGAMING,
 * incluyendo autenticación, consulta de saldo, débito, crédito y reversión de transacciones.
 */
class Tadagaming
{
    /**
     * Identificador del operador.
     *
     * @var string
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
     * Código de asignación de valor.
     *
     * @var string
     */
    private $ValAssingCode;

    /**
     * Identificador del ticket.
     *
     * @var string
     */
    private $ticketId;

    /**
     * Método de la operación.
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
     * Tipo de operación.
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
     * Detalle de la operación.
     *
     * @var string
     */
    private $detalle = "";

    /**
     * Constructor de la clase Tadagaming.
     *
     * @param string $token     Token de autenticación.
     * @param string $usuarioId Opcional ID del usuario.
     * @param string $GameCode  Opcional Código del juego.
     */
    public function __construct($token, $usuarioId = "", $GameCode = "")
    {

        // Validar si el usuarioId inicia con "user"
        if (str_starts_with($usuarioId, 'user')) {
            $partes = explode('user', $usuarioId);
            $usuarioId = $partes[1]; // Extraemos solo el número
        }

        $this->token = $token;
        $this->usuarioId = $usuarioId;
        $this->GameCode = $GameCode;
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
     * Realiza la autenticación del usuario en el sistema del proveedor.
     *
     * @return string Respuesta en formato JSON con los datos del usuario autenticado.
     * @throws Exception Si el token está vacío o ocurre un error durante la autenticación.
     */
    public function Auth()
    {
        try {
            $Proveedor = new Proveedor("", "TADAGAMING");

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

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $return = array(
                "errorCode" => 0,
                "message" => 'success',
                "username" => "user" . $UsuarioMandante->usumandanteId,
                "currency" => $UsuarioMandante->moneda,
                "balance" => $responseG->saldo,
                "token" => $this->token,
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el saldo del usuario.
     *
     * @param string $TimeStamp Marca de tiempo de la solicitud.
     * @param string $PartnerId ID del socio.
     *
     * @return string Respuesta en formato JSON con el saldo del usuario.
     * @throws Exception Si el token está vacío o ocurre un error durante la consulta.
     */
    public function getBalance($TimeStamp, $PartnerId)
    {
        try {
            $Proveedor = new Proveedor("", "TADAGAMING");

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
     * @param boolean $freespin      Opcional Indica si es un giro gratis.
     *
     * @return string Respuesta en formato JSON con los detalles de la transacción.
     * @throws Exception Si ocurre un error durante el débito.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $freespin = false)
    {
        $this->tipo = "DEBIT";
        $this->data = $datos;

        try {
            $Proveedor = new Proveedor("", "TADAGAMING");

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

            $this->transaccionApi->setIdentificador("TADAGAMING" . $roundId);

            //Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            //Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Game = new Game();

            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin);

            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "errorCode" => 0,
                "message" => 'success',
                "username" => "user" . $UsuarioMandante->usumandanteId,
                "currency" => $UsuarioMandante->moneda,
                "balance" => $responseG->saldo,
                "txId" => $responseG->transaccionId,
                "token" => $this->token,
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
     * @param boolean $EndRound      Opcional Indica si es el final de la ronda.
     *
     * @return string Respuesta en formato JSON con los detalles de la transacción.
     * @throws Exception Si ocurre un error durante el crédito.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos, $EndRound = false)
    {
        $this->tipo = "CREDIT";

        $this->data = $datos;

        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try {
            //Obtenemos el Proveedor con el abreviado tadagaming
            $Proveedor = new Proveedor("", "TADAGAMING");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);
            $this->transaccionApi->setIdentificador("TADAGAMING" . $roundId);

            /*  Obtenemos el Usuario Token con el token */
            if ($this->token != "") {
                // Obtenemos el Usuario Token con el token
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

                //  Obtenemos el Usuario Mandante con el Usuario Token
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                $UsuarioMandante = new UsuarioMandante($this->usuarioId);
            }

            //  Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            //  Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());


            //  Agregamos Elementos a la Transaccion API
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());

            $Game = new Game();
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);


            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $EndRound);

            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "errorCode" => 0,
                "message" => 'success',
                "username" => "user" . $UsuarioMandante->usumandanteId,
                "currency" => $UsuarioMandante->moneda,
                "balance" => $responseG->saldo,
                "txId" => $responseG->transaccionId,
                "token" => $this->token,
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
     * Realiza una reversión de una transacción.
     *
     * @param string $gameId        ID del juego.
     * @param string $roundId       ID de la ronda.
     * @param string $transactionId ID de la transacción.
     * @param string $player        ID del jugador.
     * @param array  $datos         Datos adicionales de la transacción.
     *
     * @return string Respuesta en formato JSON con los detalles de la reversión.
     * @throws Exception Si ocurre un error durante la reversión.
     */
    public function Rollback($gameId, $roundId, $transactionId, $player, $datos)
    {
        $this->tipo = "ROLLBACK";

        $this->data = $datos;

        try {
            $Proveedor = new Proveedor("", "TADAGAMING");

            //  Obtenemos el Usuario Token con el token
            $UsuarioToken = new UsuarioToken('', $Proveedor->getProveedorId(), $this->usuarioId);
            //  Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            //Obtenemos el Proveedor con el abreviado tadagaming
            $Proveedor = new Proveedor("", "TADAGAMING");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);
            $this->transaccionApi->setIdentificador($UsuarioMandante->getUsumandanteId() . "TADAGAMING" . $roundId);

            try {
                $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
                $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());
            } catch (Exception $e) {
                throw new Exception("Producto no existe", "100001");
            }

            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);

            $Game = new Game();

            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false, "", true, false, true);

            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "Balance" => $responseG->saldo,
                "ErrorCode" => 0,
                "ExtTransactionId" => $responseG->TransaccionId,
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
     * Convierte un error en una respuesta JSON.
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

        $response = array();

        $Proveedor = new Proveedor("", "TADAGAMING");
        $Subproveedor = new Subproveedor("", "TADAGAMING");

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
            case 100001:
                $codeProveedor = 3;
                $messageProveedor = "InvalidParameters";
                break;

            case 10001:
                $codeProveedor = 1;
                $messageProveedor = "Already accepted";
                break;

            case 10030:
                $codeProveedor = 4;
                $messageProveedor = "Token expired";
                break;

            case 28:
                $codeProveedor = 1;
                $messageProveedor = "TransactionIdNotFound";
                break;

            case 29:
                $codeProveedor = 1;
                $messageProveedor = "TransactionIdNotFound";
                break;

            case 20001:
                $codeProveedor = 2;
                $messageProveedor = "Not enough balance";
                break;

            default:
                $codeProveedor = 5;
                $messageProveedor = "Other error";
                break;
        }

        if ($messageProveedor != "") {
            $respuesta = json_encode(array_merge($response, array(
                "ErrorCode" => $codeProveedor,
                "ErrorMessage" => $messageProveedor
            )));
        }

        if ($this->transaccionApi != null) {
            $this->transaccionApi->setTipo("R" . $this->transaccionApi->getTipo());
            $this->transaccionApi->setRespuestaCodigo("ERROR" . $code);
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->insert($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();
        }

        return $respuesta;
    }
}