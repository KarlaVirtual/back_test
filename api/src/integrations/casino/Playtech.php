<?php

/**
 * Clase `Playtech` que implementa la integración con el proveedor de juegos Playtech.
 *
 * Este archivo contiene métodos para manejar transacciones como autenticación, balance, débitos, créditos,
 * y otras operaciones relacionadas con el proveedor Playtech. También incluye manejo de errores y
 * generación de respuestas en formato JSON.
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
use Backend\dto\Pais;
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransaccionApi;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\dto\TransaccionJuego;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionApiMySqlDAO;

/**
 * Clase `Playtech` que representa la integración con el proveedor de juegos Playtech.
 *
 * Esta clase contiene métodos para manejar transacciones como autenticación, balance,
 * débitos, créditos, y otras operaciones relacionadas con el proveedor Playtech.
 * También incluye manejo de errores y generación de respuestas en formato JSON.
 */
class Playtech
{
    /**
     * Token de autenticación del usuario.
     *
     * @var string
     */
    private $token;

    /**
     * ID del usuario autenticado.
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
     * Datos adicionales para las transacciones.
     *
     * @var mixed
     */
    private $data;

    /**
     * Tipo de operación actual.
     *
     * @var string
     */
    private $tipo = "";

    /**
     * ID de la solicitud actual.
     *
     * @var string
     */
    private $requestId = "";

    /**
     * Prefijo para el nombre de usuario en diferentes entornos.
     *
     * @var string
     */
    private $DOR = '';

    /**
     * Prefijo para el entorno de desarrollo.
     *
     * @var string
     */
    private $DORDEV = "DOR__";

    /**
     * Prefijo para el entorno de producción.
     *
     * @var string
     */
    private $DORROD = "";

    /**
     * Constructor de la clase `Playtech`.
     *
     * @param string $token     Token de autenticación.
     * @param string $requestId ID de la solicitud.
     * @param string $username  Nombre de usuario.
     */
    public function __construct($token, $requestId, $username)
    {
        $this->token = $token;
        $this->requestId = $requestId;
        $this->usuarioId = $username;

        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->DOR = $this->DORDEV;
        } else {
            $this->DOR = $this->DORROD;
        }
    }

    /**
     * Autentica al usuario con el proveedor Playtech.
     *
     * @return string Respuesta en formato JSON con los datos de autenticación.
     */
    public function Auth()
    {
        try {
            if ($this->usuarioId == "InvalidUsernameTest") {
                throw new Exception("Token vacio", "10011");
            }

            $Proveedor = new Proveedor("", "PLAYTECH");

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
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $Pais = new Pais($UsuarioMandante->paisId);
            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $return = array(
                "requestId" => $this->requestId,
                "username" => $this->DOR . $UsuarioMandante->usumandanteId,
                "permanentExternalToken" => $UsuarioToken->getToken(),
                "currencyCode" => $responseG->moneda,
                "countryCode" => $Pais->iso,
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del usuario autenticado con el proveedor Playtech.
     *
     * Este método realiza una consulta al proveedor para obtener el balance actual
     * del usuario autenticado. Si el token de autenticación está vacío, lanza una excepción.
     *
     * @return string Respuesta en formato JSON con el balance del usuario y la marca de tiempo.
     * @throws Exception Si ocurre un error durante la operación.
     */
    public function getBalance()
    {
        try {
            $Proveedor = new Proveedor("", "PLAYTECH");

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

            try {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
            } catch (Exception $e) {
                $UsuarioToken = new UsuarioToken('', $Proveedor->getProveedorId(), $this->usuarioId);
            }

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);

            date_default_timezone_set('GMT+0');
            $time = new DateTime('now');
            $date = $time->format('Y-m-d H:i:s.v');
            date_default_timezone_set('America/Bogota');

            $return = array(
                "requestId" => $this->requestId,
                "balance" => array(
                    "real" => round($responseG->saldo, 2),
                    "timestamp" => $date
                )
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del usuario autenticado con el proveedor Playtech.
     *
     * Este método realiza una consulta al proveedor para obtener el balance actual
     * del usuario autenticado. Si el token de autenticación está vacío, lanza una excepción.
     *
     * @return string Respuesta en formato JSON con el balance del usuario y la marca de tiempo.
     * @throws Exception Si ocurre un error durante la operación.
     */
    public function submitdialog()
    {
        try {
            $Proveedor = new Proveedor("", "PLAYTECH");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("BALANCE");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);

            if ($this->token != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                } catch (Exception $e) {
                    $UsuarioToken = new UsuarioToken('', $Proveedor->getProveedorId(), $this->usuarioId);
                }
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                $UsuarioMandante = new UsuarioMandante($this->usuarioId);
            }

            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);

            $return = array(
                "requestId" => $this->requestId,
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Mantiene activa la sesión del usuario con el proveedor Playtech.
     *
     * Este método verifica si el token o el ID de usuario están vacíos y lanza una excepción en caso afirmativo.
     * Luego, intenta obtener el balance del usuario autenticado con el proveedor.
     *
     * @return string Respuesta en formato JSON con el ID de la solicitud.
     * @throws Exception Si ocurre un error durante la operación.
     */
    public function keepAlive()
    {
        try {
            $Proveedor = new Proveedor("", "PLAYTECH");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("BALANCE");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);

            if ($this->token == "" && $this->usuarioId == "") {
                throw new Exception("Token vacio", "10011");
            }

            try {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
            } catch (Exception $e) {
                $UsuarioToken = new UsuarioToken('', $Proveedor->getProveedorId(), $this->usuarioId);
            }

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);

            $return = array(
                "requestId" => $this->requestId,
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza una transacción de débito con el proveedor Playtech.
     *
     * Este método permite debitar un monto del balance del usuario en el proveedor Playtech.
     * Valida la existencia de la transacción, obtiene los datos del usuario y del producto,
     * y actualiza la transacción en la base de datos.
     *
     * @param string  $gameId        ID del juego asociado a la transacción.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $roundId       ID de la ronda asociada.
     * @param string  $transactionId ID de la transacción.
     * @param mixed   $datos         Datos adicionales para la transacción.
     * @param boolean $freespin      Indica si la transacción es un giro gratis (opcional, por defecto false).
     * @param boolean $defaulGame    Indica si se debe usar un juego por defecto (opcional, por defecto false).
     * @param string  $gamePoker     Identificador del juego de póker (opcional).
     *
     * @return string Respuesta en formato JSON con los detalles de la transacción y el balance actualizado.
     * @throws Exception Si ocurre un error durante la operación.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $freespin = false, $defaulGame = false, $gamePoker = "")
    {
        $this->tipo = "DEBIT";
        $this->data = $datos;
        try {
            //Obtenemos el Proveedor con el abreviado Playtech
            $Proveedor = new Proveedor("", "PLAYTECH");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);

            //Obtenemos el producto con el gameId
            if ($gameId != '') {
                try {
                    if ($gamePoker == "ps") {
                        $Producto = new Producto("", $gamePoker, $Proveedor->getProveedorId());
                    } else {
                        $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
                    }
                } catch (Exception $e) {
                    $Producto = new Producto("", "D0_PL", $Proveedor->getProveedorId());
                }
            } else {
                if ($defaulGame == true) {
                    $Producto = new Producto("", "D0_PL", $Proveedor->getProveedorId());
                } else {
                    throw new Exception("No existe " . get_class($this), "26");
                }
            }
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

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "PLAYTECH");

            //Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            //$ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            $this->transaccionApi->setTransaccionId("ROLLBACK" . $transactionId);

            if ($this->transaccionApi->existsTransaccionIdAndProveedor("ERROR")) {
                throw new Exception("Trasacción con rollback", "10015");
            }

            if ($this->transaccionApi->existsTransaccionIdAndProveedor("OK")) {
                throw new Exception("Trasacción con rollback", "10015");
            }

            $this->transaccionApi->setTransaccionId($transactionId);
            $Game = new Game();

            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin);

            $this->transaccionApi = $responseG->transaccionApi;

            date_default_timezone_set('GMT+0');
            $time = new DateTime('now');
            $date = $time->format('Y-m-d H:i:s.v');
            date_default_timezone_set('America/Bogota');
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            $return = array(
                "requestId" => $this->requestId,
                "externalTransactionCode" => $responseG->transaccionId,
                "externalTransactionDate" => $date,
                "balance" => array(
                    "real" => round($Usuario->getBalance(), 2),
                    "timestamp" => $date
                )
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
     * Finaliza la sesión del usuario con el proveedor Playtech.
     *
     * Este método invalida el token de autenticación del usuario y actualiza su estado
     * en la base de datos. Si el token no está disponible, utiliza el ID del usuario
     * para realizar la operación. Finalmente, devuelve una respuesta en formato JSON
     * con el ID de la solicitud.
     *
     * @return string Respuesta en formato JSON con el ID de la solicitud.
     * @throws Exception Si ocurre un error durante la operación.
     */
    public function logout()
    {
        try {
            $Proveedor = new Proveedor("", "PLAYTECH");

            if ($this->token != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (Exception $e) {
                    $UsuarioToken = new UsuarioToken('', $Proveedor->getProveedorId(), $this->usuarioId);
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                }
            } else {
                $UsuarioToken = new UsuarioToken('', $Proveedor->getProveedorId(), $this->usuarioId);
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            }

            $UsuarioToken->setEstado('I');
            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
            $UsuarioTokenMySqlDAO->update($UsuarioToken);
            $UsuarioTokenMySqlDAO->getTransaction()->commit();

            $Game = new Game();

            $return = array(
                "requestId" => $this->requestId,
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un rollback de una transacción con el proveedor Playtech.
     *
     * Este método deshace una transacción previamente realizada, verificando
     * que la transacción exista y que la ronda no esté cerrada. Si la transacción
     * es válida, se comunica con el proveedor para realizar el rollback y actualiza
     * el estado de la transacción en la base de datos.
     *
     * @param float   $rollbackAmount Monto a revertir.
     * @param string  $roundId        ID de la ronda asociada.
     * @param string  $transactionId  ID de la transacción a revertir.
     * @param string  $player         ID del jugador.
     * @param mixed   $datos          Datos adicionales para la transacción.
     * @param boolean $isPoker        Indica si la transacción es de tipo póker (opcional, por defecto true).
     *
     * @return string Respuesta en formato JSON con los detalles del rollback.
     * @throws Exception Si ocurre un error durante la operación.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos, $isPoker = true)
    {
        $this->tipo = "ROLLBACK";
        $usuarioid = $player;
        $this->data = $datos;

        try {
            //Obtenemos el Proveedor con el abreviado
            $Proveedor = new Proveedor("", "PLAYTECH");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);

            try {
                $TransaccionJuego = new TransaccionJuego('', $roundId . $player . "PLAYTECH");
                //$TransaccionApi2 = new TransaccionApi("", $transactionId, $Proveedor->getProveedorId());
                $producto = $TransaccionJuego->getProductoId();
                $identificador = $TransaccionJuego->getTicketId();
                $transId = $TransaccionJuego->getTransaccionId();
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            if ($TransaccionJuego->getValorPremio() != 0) {
                throw new Exception("Ronda cerrada", "10016");
            }

            $this->transaccionApi->setProductoId($producto);
            if ($isPoker) {
                $this->transaccionApi->setTransaccionId('ROLLBACK' . $transId);
            }

            $this->transaccionApi->setIdentificador($identificador);

            $Game = new Game();
            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false);

            $this->transaccionApi = $responseG->transaccionApi;

            date_default_timezone_set('GMT+0');
            $time = new DateTime('now');
            $date = $time->format('Y-m-d H:i:s.v');
            date_default_timezone_set('America/Bogota');

            $return = array(
                "requestId" => $this->requestId,
                "externalTransactionCode" => $responseG->transaccionId,
                "externalTransactionDate" => $date,
                "balance" => array(
                    "real" => round($responseG->saldo, 2),
                    "timestamp" => $date
                )
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
     * Realiza una transacción de crédito con el proveedor Playtech.
     *
     * Este método permite acreditar un monto al balance del usuario en el proveedor Playtech.
     * Valida la existencia de la transacción, obtiene los datos del usuario y del producto,
     * y actualiza la transacción en la base de datos.
     *
     * @param string  $gameId        ID del juego asociado a la transacción.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       ID de la ronda asociada.
     * @param string  $transactionId ID de la transacción (opcional, se genera si está vacío).
     * @param mixed   $datos         Datos adicionales para la transacción.
     * @param boolean $isFreeSpin    Indica si la transacción es un giro gratis (opcional, por defecto false).
     * @param string  $gamePoker     Identificador del juego de póker (opcional).
     *
     * @return string Respuesta en formato JSON con los detalles de la transacción y el balance actualizado.
     * @throws Exception Si ocurre un error durante la operación.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos, $isFreeSpin = false, $gamePoker = "")
    {
        $this->tipo = "CREDIT";
        $this->data = $datos;

        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try {
            //Obtenemos el Proveedor con el abreviado Playtech
            $Proveedor = new Proveedor("", "PLAYTECH");

            try {
                $TransaccionJuego = new TransaccionJuego("", $roundId . $this->usuarioId . "PLAYTECH");
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);

            /*  Obtenemos el Usuario Token con el token */
            if ($this->token != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                } catch (Exception $e) {
                    $UsuarioToken = new UsuarioToken('', $Proveedor->getProveedorId(), $this->usuarioId);
                }
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                $UsuarioMandante = new UsuarioMandante($this->usuarioId);
            }

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "PLAYTECH");

            //Obtenemos el producto
            $ProductoMandante = new ProductoMandante("", "", $TransaccionJuego->productoId);
            $Producto = new Producto($ProductoMandante->productoId);

            //  Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            //  Agregamos Elementos a la Transaccion API
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());

            $Game = new Game();
            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, true, false, $isFreeSpin);

            $this->transaccionApi = $responseG->transaccionApi;

            date_default_timezone_set('GMT+0');
            $time = new DateTime('now');
            $date = $time->format('Y-m-d H:i:s.v');
            date_default_timezone_set('America/Bogota');

            $return = array(
                "requestId" => $this->requestId,
                "externalTransactionCode" => $responseG->transaccionId,
                "externalTransactionDate" => $date,
                "balance" => array(
                    "real" => round($responseG->saldo, 2),
                    "timestamp" => $date
                )
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
     * Converts an error code and message into a JSON response.
     *
     * This method maps error codes to predefined error descriptions and formats
     * the response in JSON. If the error code is `10001`, additional processing
     * is performed to retrieve balance information. The method also logs the
     * error in the database if a transaction API object is available.
     *
     * @param integer $code    The error code to be converted.
     * @param string  $message The error message associated with the code.
     *
     * @return string JSON response containing the error details or additional
     *                information if the error code is `10001`.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";

        $Proveedor = new Proveedor("", "PLAYTECH");
        $response = array();

        date_default_timezone_set('GMT+0');
        $time = new DateTime('now');
        $date = $time->format('Y-m-d H:i:s.v');
        date_default_timezone_set('America/Bogota');

        switch ($code) {
            case 10011:
                $codeProveedor = "ERR_AUTHENTICATION_FAILED";
                break;

            case 10015:
                $codeProveedor = "ERR_TRANSACTION_DECLINED";
                break;

            case 10017:
                $codeProveedor = "ERR_TRANSACTION_DECLINED";
                break;

            case 21:
                $codeProveedor = "ERR_AUTHENTICATION_FAILED";
                break;

                $codeProveedor = "ERR_PLAYER_NOT_FOUND";
                break;

            case 20001:
                $codeProveedor = "ERR_INSUFFICIENT_FUNDS";
                break;

            case 26:
                $codeProveedor = "CONSTRAINT_VIOLATION";
                break;

            case 27:
                $codeProveedor = "ERR_TRANSACTION_DECLINED";
                break;

            case 28:
                $codeProveedor = "ERR_TRANSACTION_DECLINED";
                break;

            case 29:
                $codeProveedor = "ERR_TRANSACTION_DECLINED";
                break;

            case 10001:
                $codeProveedor = "ERR_TRANSACTION_DECLINED";

                $ProductoMandante = new ProductoMandante('', '', $this->transaccionApi->getProductoId());
                $Producto = new Producto($ProductoMandante->productoId);

                $TransjuegoLog = new TransjuegoLog("", '', '', $this->transaccionApi->getTransaccionId() . '_' . $Producto->getSubproveedorId(), $Producto->getSubproveedorId());
                $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);

                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->usuarioId);

                $Game = new Game();
                $responseG = $Game->getBalance($UsuarioMandante);

                $response = array(
                    "requestId" => $this->requestId,
                    "externalTransactionCode" => $TransjuegoLog->transjuegologId,
                    "externalTransactionDate" => $date,
                    "balance" => array(
                        "real" => round($responseG->saldo, 2),
                        "timestamp" => $date
                    )
                );

                break;

            case 10005:
                $codeProveedor = "ERR_NO_BET";
                break;

            default:
                $codeProveedor = "ERR_TRANSACTION_DECLINED";
                break;
        }

        if ($code != 10001) {
            $respuesta = json_encode(array_merge($response, array(
                "requestId" => $this->requestId,
                "error" => array(
                    "description" => $codeProveedor,
                    "code" => $codeProveedor
                )
            )));
        } else {
            $respuesta = json_encode(array_merge($response));
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
