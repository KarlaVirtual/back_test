<?php

/**
 * Esta clase implementa la integración con el proveedor MOBADOO.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-22
 */

namespace Backend\integrations\virtual;

use Exception;
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransaccionApi;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\dto\TransaccionJuego;
use Backend\integrations\casino\Game;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionApiMySqlDAO;

/**
 * Clase Mobadoo
 *
 * Esta clase implementa la integración con el proveedor MOBADOO, permitiendo realizar operaciones
 * como autenticación, consulta de saldo, débito, crédito, rollback y finalización de rondas.
 */
class Mobadoo
{
    /**
     * Token utilizado para la autenticación del usuario.
     *
     * @var string
     */
    private $token;

    /**
     * Identificador del jugador utilizado en las transacciones.
     *
     * @var string
     */
    private $playerId;

    /**
     * Objeto para manejar las transacciones de la API.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * Identificador externo del usuario.
     *
     * @var string
     */
    private $externalId;

    /**
     * Identificador de data.
     *
     * @var string
     */
    private $data;

    /**
     * Constructor de la clase Mobadoo.
     *
     * Inicializa los valores del token y el identificador del jugador.
     * Además, configura la clave privada dependiendo del entorno (desarrollo o producción).
     *
     * @param string $token    Token utilizado para la autenticación del usuario.
     * @param string $playerId Identificador del jugador utilizado en las transacciones.
     */
    public function __construct($token, $playerId)
    {
        $this->token = $token;
        $this->playerId = $playerId;
    }


    /**
     * Autentica al usuario con el proveedor MOBADOO.
     *
     * Este metodo realiza la autenticación del usuario utilizando el token proporcionado
     * y obtiene información relevante como el saldo, moneda y nombre del usuario.
     *
     * @return string JSON con los datos del usuario autenticado, incluyendo:
     *                - uuid: Identificador único del usuario.
     *                - currency: Moneda asociada al usuario.
     *                - credit: Saldo disponible del usuario.
     *                - hash: Token utilizado para la autenticación.
     *                - displayname: Nombre del usuario.
     * @throws Exception Si el token está vacío o si ocurre algún error durante la autenticación.
     */
    public function Auth()
    {
        try {
            $Proveedor = new Proveedor("", "MOBADOO");

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
            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

            $return = array(
                "uuid" => $UsuarioToken->getUsuarioId(),
                "currency" => $UsuarioMandante->moneda,
                "credit" => $saldo,
                "hash" => $this->token,
                "displayname" => $Usuario->nombre,
            );
            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del usuario autenticado.
     *
     * Este metodo consulta el saldo actual del usuario en el proveedor MOBADOO.
     *
     * @return string JSON con los datos del balance del usuario, incluyendo:
     *                - Amount: Saldo disponible del usuario.
     *                - Code: Código de estado de la operación (0 para éxito).
     *                - Status: Estado de la operación (vacío si es exitoso).
     * @throws Exception Si el token está vacío o si ocurre algún error durante la consulta del balance.
     */
    public function getBalance()
    {
        try {
            $Proveedor = new Proveedor("", "MOBADOO");

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

            $Balance = round($responseG->saldo, 2);
            $Balance = str_replace(',', '', number_format(round($Balance, 2), 2, '.', null));

            $return = array(
                "Amount" => $Balance,
                "Code" => "0",
                "Status" => "",
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Cierra la sesión del usuario autenticado.
     *
     * Este metodo realiza las siguientes acciones:
     * - Valida que el token no esté vacío.
     * - Obtiene el balance actual del usuario.
     * - Genera un nuevo token para el usuario.
     * - Actualiza el token en la base de datos.
     * - Calcula un fingerprint para la sesión.
     * - Retorna un JSON con los datos de la sesión cerrada, incluyendo:
     *   - playerId: Identificador del jugador.
     *   - currency: Moneda asociada al jugador.
     *   - balance: Saldo disponible del jugador.
     *   - sessionId: Nuevo token generado.
     *   - group: Grupo asociado.
     *   - timestamp: Marca de tiempo de la operación.
     *   - requestId: Identificador de la solicitud.
     *   - fingerprint: Huella digital de la sesión.
     *
     * @return string JSON con los datos de la sesión cerrada.
     * @throws Exception Si el token está vacío o si ocurre algún error durante el proceso.
     */
    public function logout()
    {
        try {
            $Proveedor = new Proveedor("", "MOBADOO");

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

            $Balance = round($responseG->saldo, 2);
            $Balance = str_replace(',', '', number_format(round($Balance, 2), 2, '.', null));

            $UsuarioToken->setToken($UsuarioToken->createToken());
            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
            $UsuarioTokenMySqlDAO->update($UsuarioToken);
            $UsuarioTokenMySqlDAO->getTransaction()->commit();

            $group = 'master';

            $timestamp = date(DATE_ISO8601);

            $return = array(
                "status" => true,
                "code" => 200,
                "message" => "Success",
                "data" => array(
                    "playerId" => $UsuarioToken->getUsuarioId(),
                    "currency" => $UsuarioMandante->moneda,
                    "balance" => $Balance,
                    "sessionId" => $UsuarioToken->getToken(),
                    "group" => $group,
                    "timestamp" => $timestamp,
                    "requestId" => $requestId,
                )
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }


    /**
     * Realiza un débito en la cuenta del usuario.
     *
     * Este metodo ejecuta una transacción de débito en la cuenta del usuario
     * asociada al proveedor MOBADOO. Valida el token o el identificador del jugador,
     * obtiene el balance actual, verifica el producto y realiza la transacción.
     *
     * @param string $gameId        Identificador del juego.
     * @param float  $debitAmount   Monto a debitar.
     * @param string $roundId       Identificador de la ronda.
     * @param string $transactionId Identificador de la transacción.
     * @param array  $datos         Datos adicionales para la transacción.
     *
     * @return string JSON con el estado de la transacción, incluyendo:
     *                - state: Estado de la operación ("OK" si es exitosa).
     *                - transaction_id: Identificador de la transacción realizada.
     *
     * @throws Exception Si el token o el identificador del jugador están vacíos,
     *                   o si ocurre algún error durante la transacción.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos)
    {
        $this->data = $datos;

        try {
            if ($this->token == "" && $this->playerId == "") {
                throw new Exception("Token vacio", "10011");
            }

            //Obtenemos el Proveedor con el abreviado MOBADOO
            $Proveedor = new Proveedor("", "MOBADOO");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);
            $this->transaccionApi->setIdentificador($roundId . "MOBADOO");

            if ($this->token != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (Exception $e) {
                    $UsuarioMandante = new UsuarioMandante($this->playerId);
                }
            } else {
                $UsuarioMandante = new UsuarioMandante($this->playerId);
            }

            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);

            $oldBalance = round($responseG->saldo, 2);
            $oldBalance = str_replace(',', '', number_format(round($oldBalance, 2), 2, '.', null));

            try {
                //Obtenemos el producto con el gameId
                $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
            } catch (Exception $e) {
                if ($e->getCode() == '26') {
                    $gameId = 'general';
                    $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
                }
            }

            //Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, false, [], false);
            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "state" => "OK",
                "transaction_id" => $responseG->transaccionId,
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
     * Este metodo ejecuta una transacción de crédito en la cuenta del usuario
     * asociada al proveedor MOBADOO. Valida el token o el identificador del jugador,
     * obtiene el balance actual, verifica el producto y realiza la transacción.
     *
     * @param string  $gameId        Identificador del juego.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       Identificador de la ronda.
     * @param string  $transactionId Identificador de la transacción.
     * @param array   $datos         Datos adicionales para la transacción.
     * @param boolean $isEndRound    Indica si la transacción finaliza la ronda.
     *
     * @return string JSON con el estado de la transacción, incluyendo:
     *                - state: Estado de la operación ("OK" si es exitosa).
     *                - transaction_id: Identificador de la transacción realizada.
     *
     * @throws Exception Si el token o el identificador del jugador están vacíos,
     *                   o si ocurre algún error durante la transacción.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos, $isEndRound = false)
    {
        $this->data = $datos;
        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try {
            if ($this->token == "" && $this->playerId == "") {
                throw new Exception("Token vacio", "10011");
            }

            //Obtenemos el Proveedor con el abreviado MOBADOO
            $Proveedor = new Proveedor("", "MOBADOO");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);
            $this->transaccionApi->setIdentificador($roundId . "MOBADOO");

            try {
                $TransaccionJuego = new TransaccionJuego("", $roundId . "MOBADOO");
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            try {
                $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
            } catch (Exception $e) {
                if ($e->getCode() == '26') {
                    $gameId = 'General';
                    $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
                }
            }

            //Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            //Agregamos Elementos a la Transaccion API
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());

            $Game = new Game();
            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $isEndRound, false, false, false);
            $this->transaccionApi = $responseG->transaccionApi;

            $Balance = round($responseG->saldo, 2);
            $Balance = str_replace(',', '', number_format(round($Balance, 2), 2, '.', null));

            $return = array(
                "state" => "OK",
                "transaction_id" => $responseG->transaccionId,
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
     * Realiza un rollback en la cuenta del usuario.
     *
     * Este metodo ejecuta una transacción de rollback en la cuenta del usuario
     * asociada al proveedor MOBADOO. Valida el identificador de la transacción,
     * obtiene el balance actual, verifica la existencia del ticket y realiza la operación.
     *
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $roundId        Identificador de la ronda.
     * @param string $transactionId  Identificador de la transacción original.
     * @param string $player         Identificador del jugador.
     * @param array  $datos          Datos adicionales para la transacción.
     *
     * @return string JSON con el estado de la transacción, incluyendo:
     *                - state: Estado de la operación ("OK" si es exitosa).
     *                - transaction_id: Identificador de la transacción realizada.
     *
     * @throws Exception Si el identificador de la transacción no existe,
     *                   si el ticket ya existe o si ocurre algún error durante la operación.
     */
    public function Rollback($gameId, $roundId, $transactionId, $player, $datos)
    {
        $usuarioid = $player;
        $this->data = $datos;

        try {
            //Obtenemos el Proveedor con el abreviado MOBADOO
            $Proveedor = new Proveedor("", "MOBADOO");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);

            try {
                $TransaccionJuego = new TransaccionJuego("", $roundId . "MOBADOO");
                $TransjuegoLog = new TransjuegoLog("", $TransaccionJuego->transjuegoId, "DEBIT");
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());

                if (strpos($TransjuegoLog->getTipo(), 'DEBIT') !== false) {
                    $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId . "_D");
                    $this->transaccionApi->setIdentificador($TransaccionJuego->getTicketId());
                } else {
                    throw new Exception("Transaccion no es Debit", "10006");
                }
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            $Game = new Game();
            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false, '', false);
            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "state" => "OK",
                "transaction_id" => $responseG->transaccionId,
            );

            //Guardamos la Transaccion Api necesaria de estado OK
            $this->transaccionApi->setRespuestaCodigo("OK");
            $this->transaccionApi->setRespuesta(json_encode($return));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return json_encode($return);
        } catch (Exception $e) {
            print_r($e);
            exit;
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Convierte un error en una respuesta JSON con un formato específico.
     *
     * Este metodo toma un código de error y un mensaje, los mapea a códigos y mensajes
     * específicos del proveedor, y genera una respuesta JSON con el estado de la transacción.
     * Además, registra la transacción en la base de datos si es necesario.
     *
     * @param integer $code    Código de error recibido.
     * @param string  $message Mensaje de error recibido.
     *
     * @return string JSON con la respuesta del error, incluyendo:
     *                - transaction_id: Identificador de la transacción (vacío en caso de error).
     *                - code: Código de error mapeado al proveedor.
     *                - state: Mensaje de error mapeado al proveedor.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $response = array();
        if ($this->transaccionApi != null) {
            $tipo = $this->transaccionApi->getTipo();
        }

        if ($tipo == "DEBIT" || $tipo == "CREDIT" || $tipo == "ROLLBACK") {
            $response = array_merge($response, array());
        } else {
            $response = array_merge($response, array());
        }

        switch ($code) {
            case 10011:
                $codeProveedor = 100;
                $messageProveedor = "Invalid token";
                break;

            case 21:
                $codeProveedor = 100;
                $messageProveedor = "Invalid token";
                break;
            case 22:
                $codeProveedor = 110;
                $messageProveedor = "Invalid player ID";
                break;

            case 20001:
                $codeProveedor = 121;
                $messageProveedor = "Insufficient funds";
                break;
            case 10017:
                $codeProveedor = 120;
                $messageProveedor = "Invalid currency code for player";
                break;

            case 27:
                $codeProveedor = 104;
                $messageProveedor = "Unknown request.";
                break;

            case 28:
                $codeProveedor = 112;
                $messageInterno = $code;
                $messageProveedor = "Game cycle does not exist.";
                break;

            case 29:
                $codeProveedor = 116;
                $messageProveedor = "Transaction already exists.";
                break;
            case 10001:
                $codeProveedor = 123;
                $messageProveedor = "Transaction already processed";
                break;
            case 10004:
                $codeProveedor = 105;
                $messageProveedor = "Request processing services unavailable.";
                break;

            case 10014:
                $codeProveedor = 104;
                $messageProveedor = "Unknown request.";
                break;

            case 10025:
                $codeProveedor = 115;
                $messageProveedor = "Game cycle exists.";
                break;

            case 10026:
                $codeProveedor = 112;
                $messageProveedor = "Game cycle does not exist.";
                break;

            case 10005:
                $codeProveedor = 131;
                $messageProveedor = "Original transaction not found";
                break;

            case 10027:
                $codeProveedor = 118;
                $messageInterno = $code;
                $messageProveedor = "Game cycle already closed.";
                break;
            case 26:
                $codeProveedor = 111;
                $messageInterno = $code;
                $messageProveedor = "Unsupported gameid.";
                break;

            default:
                $codeProveedor = 104;
                $messageInterno = $code;
                $messageProveedor = "Unknown request.";
                break;
        }

        $respuesta = (array_merge($response, array(
            "transaction_id" => "",
            "code" => $codeProveedor,
            "state" => $messageProveedor
        )));

        if ($this->transaccionApi != null) {
            $this->transaccionApi->setTipo("R" . $this->transaccionApi->getTipo());
            $this->transaccionApi->setRespuestaCodigo("ERROR_" . $code);
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->insert($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();
        }

        return json_encode($respuesta);
    }
}
