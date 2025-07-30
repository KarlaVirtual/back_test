<?php

/**
 * Clase Beterlive
 *
 * Esta clase implementa la integración con el proveedor de casino BETERLIVE.
 * Proporciona métodos para realizar operaciones como autenticación, débito, crédito, rollback y verificación.
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
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\Subproveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransaccionApi;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransaccionApiMySqlDAO;

/**
 * Clase que implementa la integración con el proveedor de casino BETERLIVE.
 * Proporciona métodos para realizar operaciones como autenticación, débito, crédito, rollback y verificación.
 */
class Beterlive
{
    /**
     * Token de autenticación del usuario.
     *
     * @var string
     */
    private $token;

    /**
     * Código del casino.
     *
     * @var string
     */
    private $casino;

    /**
     * Firma utilizada para validaciones.
     *
     * @var string
     */
    private $sign;

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
     * Tipo de operación (DEBIT, CREDIT, ROLLBACK, etc.).
     *
     * @var string
     */
    private $tipo;

    /**
     * Nombre de usuario.
     *
     * @var string
     */
    private $username;

    /**
     * Constructor de la clase.
     *
     * @param string $token    Token de autenticación.
     * @param string $casino   Código del casino.
     * @param string $username Nombre de usuario.
     */
    public function __construct($token, $casino, $username)
    {
        $this->token = $token;
        $this->casino = $casino;
        $this->username = $username;
    }

    /**
     * Obtiene la clave secreta (SECRET_KEY) del proveedor.
     *
     * @return string Clave secreta.
     * @throws Exception Si ocurre un error al obtener las credenciales.
     */
    public function getSecret($gameId = "")
    {
        try {
            $Proveedor = new Proveedor("", "BETERLIVE");

            if ($this->username != "") {
                $this->username = explode("Usuario", $this->username);
                $UsuarioMandante = new UsuarioMandante($this->username[1]);
            } else {
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (Exception $e) {
                    throw new Exception("Token vacio", "10011");
                }
            }

            if ($gameId == "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                } catch (Exception $e) {
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->usumandanteId);
                }
            }

            try {
                $Producto = new Producto('', $gameId, $Proveedor->getProveedorId());
            } catch (Exception $e) {
                $Producto = new Producto($UsuarioToken->productoId);
            }

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $SECRET_KEY = $credentials->SECRET_KEY;

            return $SECRET_KEY;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Autentica al usuario en el sistema.
     *
     * @return string Respuesta en formato JSON con los datos del usuario autenticado.
     * @throws Exception Si ocurre un error durante la autenticación.
     */
    public function Auth()
    {
        $this->username = explode("Usuario", $this->username);
        try {
            $Proveedor = new Proveedor("", "BETERLIVE");

            $UsuarioMandante = new UsuarioMandante($this->username[1]);
            if ($this->username[1] != $UsuarioMandante->usumandanteId) {
                throw new Exception("UsuarioId no coincide", "24");
            }

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

            if ($this->casino != "virtualsoft") {
                throw new Exception("Codigo del casino incorecto", "10022");
            }

            $Pais = new Pais($UsuarioMandante->paisId);
            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $return = array(
                "username" => "Usuario" . $UsuarioMandante->usumandanteId,
                "currency" => $UsuarioMandante->moneda,
                "country" => $Pais->iso,
                "balance" => intval(round($responseG->saldo, 2) * 100),
                "displayName" => $UsuarioMandante->nombres,
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza una operación de débito.
     *
     * @param string  $gameId        ID del juego.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param array   $datos         Datos adicionales.
     * @param boolean $freespin      Indica si es un giro gratis.
     *
     * @return string Respuesta en formato JSON con el balance actualizado.
     * @throws Exception Si ocurre un error durante la operación.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $freespin)
    {
        $this->data = $datos;
        $this->tipo = "DEBIT";
        try {
            $this->username = explode("Usuario", $this->username);
            $UsuarioMandante = new UsuarioMandante($this->username[1]);

            if ($this->username[1] != $UsuarioMandante->usumandanteId || $this->username[1] == "" || $UsuarioMandante->usumandanteId == "") {
                throw new Exception("UsuarioId no coincide", "24");
            }

            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            if ($this->casino != "virtualsoft") {
                throw new Exception("Codigo del casino incorecto", "10022");
            }

            if ($debitAmount < 0) {
                throw new Exception("Monto negativo", "10002");
            }

            //Obtenemos el Proveedor con el abreviado Beterlive
            $Proveedor = new Proveedor("", "BETERLIVE");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);

            //Obtenemos el Usuario Token con el token
            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "BETERLIVE");
            //Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            //Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin, [], true, false);
            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "transactionId" => $transactionId,
                "balance" => intval(round($responseG->saldo, 2) * 100),
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
     * Realiza una operación de rollback.
     *
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $roundId        ID de la ronda.
     * @param string $transactionId  ID de la transacción.
     * @param string $player         Nombre del jugador.
     * @param array  $datos          Datos adicionales.
     *
     * @return string Respuesta en formato JSON con el balance actualizado.
     * @throws Exception Si ocurre un error durante la operación.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos)
    {
        $this->data = $datos;

        $this->tipo = "ROLLBACK";
        try {
            $this->username = explode("Usuario", $this->username);
            $UsuarioMandante = new UsuarioMandante($this->username[1]);

            if ($this->username[1] != $UsuarioMandante->usumandanteId || $this->username[1] == "" || $UsuarioMandante->usumandanteId == "") {
                throw new Exception("UsuarioId no coincide", "24");
            }

            //Obtenemos el Proveedor con el abreviado Beterlive
            $Proveedor = new Proveedor("", "BETERLIVE");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);

            if ($this->casino != "virtualsoft") {
                throw new Exception("Codigo del casino incorecto", "10022");
            }

            $SubProveedor = new SubProveedor("", "BETERLIVE");
            $TransaccionJuego = new TransaccionJuego("", $roundId . $UsuarioMandante->usumandanteId . $SubProveedor->abreviado);

            if ($TransaccionJuego->getEstado() == "I" && $TransaccionJuego->getPremiado() == "S") {
                throw new Exception("Rollback no permitido", "10027");
            }

            $TransjuegoLog = new TransjuegoLog("", "", "", $transactionId . '_' . $SubProveedor->getSubproveedorId(), $SubProveedor->getSubproveedorId());

            if (strpos($TransjuegoLog->getTipo(), 'DEBIT') !== false) {
                $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);
                $this->transaccionApi->setIdentificador($TransaccionJuego->getTicketId());
            }

            $Game = new Game();
            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false, $TransaccionJuego->transaccionId, false, false, false, false, "A");
            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "transactionId" => $TransaccionJuego->transaccionId,
                "balance" => intval(round($responseG->saldo, 2) * 100),
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
     * Realiza una operación de crédito.
     *
     * @param string $gameId         ID del juego.
     * @param float  $creditAmount   Monto a acreditar.
     * @param string $roundId        ID de la ronda.
     * @param string $transactionId  ID de la transacción.
     * @param array  $datos          Datos adicionales.
     * @param float  $totalBetAmount Monto total apostado.
     *
     * @return string Respuesta en formato JSON con el balance actualizado.
     * @throws Exception Si ocurre un error durante la operación.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos, $totalBetAmount)
    {
        $this->data = $datos;
        $this->tipo = "CREDIT";
        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try {
            $this->username = explode("Usuario", $this->username);
            $UsuarioMandante = new UsuarioMandante($this->username[1]);

            if ($this->username[1] != $UsuarioMandante->usumandanteId || $this->username[1] == "" || $UsuarioMandante->usumandanteId == "") {
                throw new Exception("UsuarioId no coincide", "24");
            }

            //Obtenemos el Proveedor con el abreviado Beterlive
            $Proveedor = new Proveedor("", "BETERLIVE");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);

            if ($this->casino != "virtualsoft") {
                throw new Exception("Codigo del casino incorecto", "10022");
            }

            $TranssacionJuego = new TransaccionJuego("", $roundId . $UsuarioMandante->getUsumandanteId() . "BETERLIVE");

            if (round($TranssacionJuego->valorTicket, 2) != $totalBetAmount) {
                throw new Exception("Monto Incorrecto del bet", "10019");
            }

            //Obtenemos el Usuario Mandante con el Usuario Token
            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "BETERLIVE");

            //Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            //Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            //Agregamos Elementos a la Transaccion API
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());

            $Game = new Game();
            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, true, "", "", false);
            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "transactionId" => $transactionId,
                "balance" => intval(round($responseG->saldo, 2) * 100),
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
     * Convierte un error en un formato de respuesta estándar.
     *
     * @param integer $code    Código del error.
     * @param string  $message Mensaje del error.
     *
     * @return string Respuesta en formato JSON con el error convertido.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $response = array();
        $Proveedor = new Proveedor("", "BETERLIVE");

        switch ($code) {
            case 10011:
                http_response_code(422);
                $codeProveedor = 'invalid.session.key';
                $messageProveedor = "invalid.session.key";
                break;

            case 21:
                http_response_code(422);
                $codeProveedor = 'invalid.session.key';
                $messageProveedor = "invalid.session.key";
                break;

            case 22:
                http_response_code(422);
                $codeProveedor = 'player.not.found';
                $messageProveedor = "player.not.found";
                break;

            case 24:
                http_response_code(422);
                $codeProveedor = 'player.not.found';
                $messageProveedor = "player.not.found";
                break;

            case 26:
                http_response_code(422);
                $codeProveedor = 'invalid.casino.behaviour';
                $messageProveedor = "invalid.casino.behaviour";
                break;

            case 10005:
                http_response_code(422);
                $codeProveedor = "invalid.transaction.id";
                $messageProveedor = "invalid.transaction.id";
                break;

            case 28:
                switch ($this->tipo) {
                    case "CREDIT":
                        http_response_code(422);
                        $codeProveedor = 'invalid.casino.behaviour';
                        $messageProveedor = "invalid.casino.behaviour";
                        break;

                    default:
                        http_response_code(422);
                        $codeProveedor = 'invalid.transaction.id';
                        $messageProveedor = "invalid.transaction.id";
                        break;
                }
                break;

            case 10022:
                http_response_code(422);
                $codeProveedor = 'player.not.found';
                $messageProveedor = "player.not.found";
                break;

            case 10027:
                http_response_code(422);
                $codeProveedor = "invalid.casino.behaviour";
                $messageProveedor = "invalid.casino.behaviour";
                break;

            case 10019:
                http_response_code(422);
                $codeProveedor = "invalid.casino.behaviour";
                $messageProveedor = "invalid.casino.behaviour";
                break;

            case 10002:
                http_response_code(422);
                $codeProveedor = 'bad.request';
                $messageProveedor = "bad.request";
                break;

            case 20001:
                http_response_code(422);
                $codeProveedor = "insufficient.balance";
                $messageProveedor = "Insufficient player balance";
                break;

            case 50001:
                http_response_code(422);
                $codeProveedor = 'player.not.found';
                $messageProveedor = "player.not.found";
                break;

            case 10001:
                if ($this->token != "" && $this->token != "-") {
                    try {
                        //  Obtenemos el Usuario Token con el token
                        $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                        //  Obtenemos el Usuario Mandante con el Usuario Token
                        $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                    } catch (Exception $e) {
                        throw new Exception("Token vacio", "10011");
                    }
                } else {
                    throw new Exception("Token vacio", "10011");
                }

                $Game = new Game();
                $responseG = $Game->getBalance($UsuarioMandante);
                $transaccionApi2 = new TransaccionApi("", $this->transaccionApi->getTransaccionId(), $Proveedor->getProveedorId());

                if ($transaccionApi2->tipo != $this->tipo) {
                    http_response_code(422);
                    $codeProveedor = "invalid.transaction.id";
                    $messageProveedor = "invalid.transaction.id";
                    $return = array(
                        "code" => $codeProveedor,
                        "message" => $messageProveedor,
                    );
                    return json_encode($return);
                } else {
                    switch ($this->tipo) {
                        case "ROLLBACK":
                            $TransaccionJuego = new TransaccionJuego("", $transaccionApi2->identificador);
                            $return = array(
                                "transactionId" => $TransaccionJuego->transaccionId,
                                "balance" => intval(round($responseG->saldo, 2) * 100),

                            );
                            return json_encode($return);
                            break;

                        case "CREDIT":
                            $return = array(
                                "transactionId" => $transaccionApi2->transaccionId,
                                "balance" => intval(round($responseG->saldo, 2) * 100),

                            );
                            return json_encode($return);
                            break;

                        case "DEBIT":
                            $return = array(
                                "transactionId" => $transaccionApi2->transaccionId,
                                "balance" => intval(round($responseG->saldo, 2) * 100),

                            );
                            return json_encode($return);
                            break;
                    }
                }
                break;

            default:
                http_response_code(422);
                $codeProveedor = "error.internal";
                $messageProveedor = "Internal error";
                break;
        }

        if ($code != 10001) {
            $respuesta = json_encode(array_merge($response, array(
                "code" => $codeProveedor,
                "message" => $messageProveedor
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
