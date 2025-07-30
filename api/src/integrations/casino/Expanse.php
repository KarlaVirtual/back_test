<?php

/**
 * Clase Expanse para la integración con el proveedor de casino EXPANSE.
 *
 * Este archivo contiene la implementación de la clase Expanse, que maneja
 * las operaciones relacionadas con la integración de un proveedor de casino,
 * incluyendo autenticación, balance, débitos, créditos y rollbacks.
 *
 * @category Integración
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
use Backend\integrations\casino\EXPANSESERVICES;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransaccionApiMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;
use DateTime;
use Exception;
use phpDocumentor\Reflection\Types\This;

/**
 * Clase principal para la integración con el proveedor de casino EXPANSE.
 *
 * Esta clase contiene métodos para manejar operaciones como autenticación,
 * balance, débitos, créditos y rollbacks, entre otros.
 */
class Expanse
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
     * Objeto para manejar transacciones API.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * Datos asociados a la transacción.
     *
     * @var mixed
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
     * @var mixed
     */
    private $detalle = '';

    /**
     * Constructor de la clase Expanse.
     *
     * Inicializa los valores del token, usuario y código del juego, y configura
     * los objetos necesarios para la integración con el proveedor.
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

        $Proveedor = new Proveedor("", "EXPANSE");

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
        $this->detalle = json_decode($SubproveedorMandantePais->getDetalle());
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
     * Autentica al usuario con el proveedor de casino.
     *
     * Realiza la autenticación del usuario utilizando el token proporcionado
     * y devuelve información del jugador, como balance, idioma y moneda.
     *
     * @return string JSON con los datos del jugador o un error en caso de fallo.
     */
    public function Auth()
    {
        try {
            $Proveedor = new Proveedor("", "EXPANSE");

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
                "player_id" => $UsuarioMandante->usumandanteId,
                "currency" => $UsuarioMandante->moneda,
                "language" => $Usuario->idioma,
                "balance" => round($Usuario->getBalance(), 2)
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del usuario.
     *
     * Consulta el balance del usuario autenticado con el proveedor de casino.
     *
     * @return string JSON con el balance del usuario o un error en caso de fallo.
     */
    public function getBalance()
    {
        try {
            $Proveedor = new Proveedor("", "EXPANSE");

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
                "balance" => round($responseG->saldo, 2),
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza una operación de débito en el sistema del proveedor de casino.
     *
     * Este método maneja la lógica para debitar un monto del balance del usuario
     * en el contexto de un juego. Incluye la creación de una transacción API,
     * validaciones de usuario y producto, y la interacción con el sistema del proveedor.
     *
     * @param string  $gameId        Identificador del juego.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $roundId       Identificador de la ronda.
     * @param string  $transactionId Identificador de la transacción.
     * @param mixed   $datos         Datos adicionales relacionados con la transacción.
     * @param boolean $freespin      Indica si la transacción es parte de un freespin (opcional).
     *
     * @return string JSON con los detalles de la transacción y el balance actualizado,
     *                o un error en caso de fallo.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $freespin = false)
    {
        $this->tipo = "DEBIT";
        $this->data = $datos;

        try {
            $Proveedor = new Proveedor("", "EXPANSE");

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

            $this->transaccionApi->setIdentificador("EXPANSE" . $roundId);

            if ($freespin) {
                $product = $UsuarioToken->productoId;
                $gameId = new Producto($product);
                $gameId = $gameId->externoId;
            }

            //Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            //Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Game = new Game();

            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin);

            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "transaction_id" => $responseG->transaccionId,
                "balance" => round($Usuario->getBalance(), 2),
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
     * Realiza una operación de crédito en el sistema del proveedor de casino.
     *
     * Este método maneja la lógica para acreditar un monto al balance del usuario
     * en el contexto de un juego. Incluye la creación de una transacción API,
     * validaciones de usuario y producto, y la interacción con el sistema del proveedor.
     *
     * @param string  $gameId        Identificador del juego.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       Identificador de la ronda.
     * @param string  $transactionId Identificador de la transacción (opcional).
     * @param mixed   $datos         Datos adicionales relacionados con la transacción.
     * @param boolean $EndRound      Indica si la transacción finaliza la ronda (opcional).
     *
     * @return string JSON con los detalles de la transacción y el balance actualizado,
     *                o un error en caso de fallo.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos, $EndRound = false)
    {
        $this->tipo = "CREDIT";

        $this->data = $datos;

        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try {
            //Obtenemos el Proveedor con el abreviado Expanse
            $Proveedor = new Proveedor("", "EXPANSE");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);
            $this->transaccionApi->setIdentificador("EXPANSE" . $roundId);

            try {
                $TransaccionJuego = new TransaccionJuego("", "EXPANSE" . $roundId);
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            $ProductoMandante = new ProductoMandante("", "", $TransaccionJuego->productoId);
            $Producto = new Producto($ProductoMandante->productoId);

            //  Agregamos Elementos a la Transaccion API
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());

            $Game = new Game();
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);


            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $EndRound);

            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "transaction_id" => $responseG->transaccionId,
                "balance" => round($Usuario->getBalance(), 2)
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
     * Realiza una operación de rollback en el sistema del proveedor de casino.
     *
     * Este método maneja la lógica para revertir una transacción previamente realizada,
     * restaurando el balance del usuario al estado anterior. Incluye la creación de
     * una transacción API, validaciones de usuario y producto, y la interacción con
     * el sistema del proveedor.
     *
     * @param string $roundId       Identificador de la ronda.
     * @param string $transactionId Identificador de la transacción a revertir.
     * @param mixed  $player        Información del jugador.
     * @param mixed  $datos         Datos adicionales relacionados con la transacción.
     *
     * @return string JSON con el balance actualizado o un error en caso de fallo.
     */
    public function Rollback($roundId, $transactionId, $player, $datos)
    {
        $this->tipo = "ROLLBACK";

        $this->data = $datos;

        try {
            $Proveedor = new Proveedor("", "EXPANSE");

            //  Obtenemos el Usuario Token con el token
            $UsuarioToken = new UsuarioToken('', $Proveedor->getProveedorId(), $this->usuarioId);
            //  Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            //Obtenemos el Proveedor con el abreviado Expanse
            $Proveedor = new Proveedor("", "EXPANSE");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);
            $this->transaccionApi->setIdentificador($UsuarioMandante->getUsumandanteId() . "EXPANSE" . $roundId);


            try {
                $TransaccionApi2 = new TransaccionApi("", $transactionId, $Proveedor->getProveedorId());

                $producto = $TransaccionApi2->getProductoId();

                $UsuarioMandante = new UsuarioMandante($TransaccionApi2->getUsuarioId());
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            $this->transaccionApi->setProductoId($producto);

            $Game = new Game();

            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false, "", true, false, true);

            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "balance" => round($Usuario->getBalance(), 2),
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
     * Convierte un error en un formato comprensible para el proveedor.
     *
     * Este método toma un código de error y un mensaje, los mapea a códigos y mensajes
     * específicos del proveedor, y genera una respuesta en formato JSON. Además, registra
     * la transacción API con el estado de error en la base de datos.
     *
     * @param integer $code    Código de error recibido.
     * @param string  $message Mensaje de error recibido.
     *
     * @return string JSON con el código y mensaje de error mapeados al formato del proveedor.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $response = array();

        $Proveedor = new Proveedor("", "EXPANSE");
        $Subproveedor = new Subproveedor("", "EXPANSE");

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

            case 50002:
                $codeProveedor = 2;
                $messageProveedor = "Invalid request!";
                break;

            case 27:
                $codeProveedor = 3;
                $messageProveedor = " Game not found!";
                break;

            case 10011:
                $codeProveedor = 8;
                $messageProveedor = "Token parameter is missing!";
                break;

            case 100001:
                $codeProveedor = 7;
                $messageProveedor = "Market not found!";
                break;

            case 50001:
                $codeProveedor = 6;
                $messageProveedor = "Client not found!";
                break;

            case 30010:
                $codeProveedor = 13;
                $messageProveedor = "Session not found!";
                break;

            case 20000:
                $codeProveedor = 14;
                $messageProveedor = "Session is invalid!";
                break;

            case 300017:
                $codeProveedor = 15;
                $messageProveedor = "Session limit is reached!";
                break;

            case 28:
                $codeProveedor = 16;
                $messageProveedor = "Transaction failed!";
                break;

            case 10001:
                $codeProveedor = 18;
                $messageProveedor = "Transaction exists, but with different amount!";
                break;

            case 20001:
                $codeProveedor = 17;
                $messageProveedor = "Insufficient funds!";
                break;

            case 24:
                $codeProveedor = 6007;
                $messageProveedor = "UnknownPlayerId";
                break;

            default:
                $codeProveedor = 10;
                $messageProveedor = "General operator API error!";
                break;
        }

        if ($messageProveedor != "") {
            $respuesta = json_encode(array_merge($response, array(
                "err_code" => $codeProveedor,
                "err_message" => $messageProveedor
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