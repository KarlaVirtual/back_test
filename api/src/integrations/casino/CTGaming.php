<?php

/**
 * Clase CTGaming
 *
 * Esta clase implementa la integración con el proveedor CTGaming para realizar operaciones
 * relacionadas con juegos, como autenticación, balance, débitos, créditos, y más.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;

use Backend\dto\Mandante;
use Backend\dto\PromocionalLog;
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
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransaccionApiMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;
use DateTime;
use Exception;

/**
 * Clase CTGaming
 *
 * Esta clase implementa la integración con el proveedor CTGaming para realizar
 * operaciones relacionadas con juegos, como autenticación, balance, débitos, créditos, y más.
 */
class CTGaming
{
    /**
     * Identificador del operador.
     *
     * @var integer|null
     */
    private $operadorId;

    /**
     * Token de autenticación.
     *
     * @var string
     */
    private $token;

    /**
     * Identificador único del usuario.
     *
     * @var string|null
     */
    private $uid;

    /**
     * Firma de seguridad.
     *
     * @var string
     */
    private $sign;

    /**
     * Objeto de transacción API.
     *
     * @var TransaccionApi|null
     */
    private $transaccionApi;

    /**
     * Datos adicionales para la operación.
     *
     * @var mixed
     */
    private $data;

    /**
     * Identificador de la ronda principal.
     *
     * @var string|null
     */
    private $roundIdSuper;

    /**
     * Identificador externo.
     *
     * @var string|null
     */
    private $externalId;

    /**
     * Constructor de la clase.
     *
     * @param string $token Token de autenticación.
     * @param string $sign  Firma de seguridad.
     */
    public function __construct($token, $sign)
    {
        $this->token = $token;
        $this->sign = $sign;
    }

    /**
     * Obtiene el ID del operador.
     *
     * @return integer|null ID del operador.
     */
    public function getOperadorId()
    {
        return $this->operadorId;
    }

    /**
     * Autentica al usuario y genera una sesión de juego.
     *
     * @return string Respuesta en formato JSON con los datos de la sesión.
     * @throws Exception Si ocurre un error durante la autenticación.
     */
    public function Auth()
    {
        try {
            $Proveedor = new Proveedor("", "CTGAMING");

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

            //Obtenemos el producto con el gameId
            $Producto = new Producto($UsuarioToken->productoId);

            $UsuarioToken->setToken($UsuarioToken->createToken());

            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
            $UsuarioTokenMySqlDAO->update($UsuarioToken);
            $UsuarioTokenMySqlDAO->getTransaction()->commit();


            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $return = array(
                "Code" => 0,
                "playerId" => $UsuarioMandante->usumandanteId,
                "gameName" => $Producto->getExternoId(),
                "sessionId" => $UsuarioToken->getToken(),
                "balance" => intval(round($responseG->saldo, 2) * 100),
                "currency" => $responseG->moneda,
                "language" => 'es',
                "timestamp" => gmdate("Y-m-d\TH:i:s\Z")
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del usuario.
     *
     * @return string Respuesta en formato JSON con el balance del usuario.
     * @throws Exception Si ocurre un error al obtener el balance.
     */
    public function getBalance()
    {
        try {
            $Proveedor = new Proveedor("", "CTGAMING");

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
                "response_code" => "ok",
                "response_message" => "ok",
                "totalbalance" => intval(round($responseG->saldo, 2) * 100),
                "currency" => $responseG->moneda

            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Refresca la sesión del usuario.
     *
     * @return string Respuesta en formato JSON con el estado de la sesión.
     * @throws Exception Si ocurre un error al refrescar la sesión.
     */
    public function refreshSesion()
    {
        try {
            $Proveedor = new Proveedor("", "CTGAMING");


            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            $return = array(
                "Code" => 0,
                "Message" => "",
                "timestamp" => gmdate("Y-m-d\TH:i:s\Z")

            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un débito en el sistema de juego.
     *
     * @param string  $gameId        Identificador del juego.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $roundId       Identificador de la ronda.
     * @param string  $transactionId Identificador de la transacción.
     * @param mixed   $datos         Datos adicionales para la operación.
     * @param boolean $freespin      Indica si el débito es parte de una ronda gratuita (opcional).
     *
     * @return string Respuesta en formato JSON con el resultado del débito.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $freespin = false)
    {
        $this->data = $datos;

        try {
            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            //Obtenemos el Proveedor con el abreviado Booongo
            $Proveedor = new Proveedor("", "CTGAMING");

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

            //Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "CTGAMING");

            //Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            //Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin);
            $this->transaccionApi = $responseG->transaccionApi;


            $return = array(
                "response_code" => "ok",
                "response_message" => "ok",
                "totalbalance" => intval(round($responseG->saldo, 2) * 100),
                "currency" => $responseG->moneda
                //,"freeround_limit"=>"5"

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
     * Realiza un rollback (reversión) de una transacción en el sistema de juego.
     *
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $roundId        Identificador de la ronda.
     * @param string $transactionId  Identificador de la transacción original.
     * @param string $player         Identificador del jugador.
     * @param mixed  $datos          Datos adicionales para la operación.
     *
     * @return string Respuesta en formato JSON con el resultado del rollback.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos)
    {
        $this->externalId = $player;

        $this->data = $datos;


        try {
            //Obtenemos el Proveedor con el abreviado CTGaming
            $Proveedor = new Proveedor("", "CTGAMING");

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
                $TransaccionApi2 = new TransaccionApi("", $transactionId, $Proveedor->getProveedorId());
                $producto = $TransaccionApi2->getProductoId();
                $identificador = $TransaccionApi2->getIdentificador();
                $UsuarioMandante = new UsuarioMandante($TransaccionApi2->getUsuarioId());
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            $this->transaccionApi->setProductoId($producto);
            $this->transaccionApi->setIdentificador($identificador);


            $Game = new Game();

            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false);

            $this->transaccionApi = $responseG->transaccionApi;


            $return = array(
                "response_code" => "ok",
                "response_message" => "ok",
                "totalbalance" => intval(round($responseG->saldo, 2) * 100),
                "currency" => $responseG->moneda
                //,"freeround_limit"=>"5"

            );


            //Guardamos la Transaccion Api necesaria de estado OK
            $this->transaccionApi->setRespuestaCodigo("OK");
            $this->transaccionApi->setRespuesta(json_encode($return));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();


            return json_encode($return);
        } catch (Exception $e) {
            if ($_REQUEST["isDebug"] == 1) {
                print_r($e);
            }
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un crédito en el sistema de juego.
     *
     * @param string $gameId        Identificador del juego.
     * @param float  $creditAmount  Monto a acreditar.
     * @param string $roundId       Identificador de la ronda.
     * @param string $transactionId Identificador de la transacción. Si está vacío, se genera uno automáticamente.
     * @param mixed  $datos         Datos adicionales para la operación.
     * @param string $UsuarioId     Identificador del usuario (opcional).
     *
     * @return string Respuesta en formato JSON con el resultado del crédito.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos, $UsuarioId = "")
    {
        $this->data = $datos;

        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try {
            //Obtenemos el Proveedor con el abreviado Booongo
            $Proveedor = new Proveedor("", "CTGAMING");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);
            //$this->transaccionApi->setIdentificador($roundId . "CTGaming");

            /*  Obtenemos el Usuario Token con el token */
            try {
                // Obtenemos el Usuario Token con el token
                // $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

                //  Obtenemos el Usuario Mandante con el Usuario Token
                //$UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                $UsuarioMandante = new UsuarioMandante($UsuarioId);
            } catch (Exception $e) {
                if ($e->getCode() == 21) {
                    //$TransaccionJuego = new TransaccionApi("", $roundId . $UsuarioId. "CTGAMING",$Proveedor->getProveedorId());

                    $UsuarioMandante = new UsuarioMandante($UsuarioId);
                }
            }

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "CTGAMING");

            //  Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            //  Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            //  Agregamos Elementos a la Transaccion API
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());


            $Game = new Game();

            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, true);

            $this->transaccionApi = $responseG->transaccionApi;


            $return = array(
                "response_code" => "ok",
                "response_message" => "ok",
                "totalbalance" => intval(round($responseG->saldo, 2) * 100),
                "currency" => $responseG->moneda
                //,"freeround_limit"=>"5"

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
     * Verifica un parámetro y devuelve una respuesta en formato JSON.
     *
     * @param mixed $param Parámetro a verificar.
     *
     * @return string Respuesta en formato JSON con el nodo, el parámetro y la firma.
     */
    public function Check($param)
    {
        $return = array(

            "nodeId" => 123,
            "param" => $param,
            "sign" => $this->sign
        );

        return json_encode($return);
    }

    /**
     * Convierte un código de error y un mensaje en una respuesta JSON.
     *
     * Este método toma un código de error y un mensaje, los mapea a un código y mensaje
     * específicos del proveedor, y genera una respuesta en formato JSON. Además, registra
     * la transacción en caso de error.
     *
     * @param integer $code    Código de error.
     * @param string  $message Mensaje de error.
     *
     * @return string Respuesta en formato JSON con el código y mensaje del proveedor.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";
        $Proveedor = new Proveedor("", "CTGAMING");
        $response = array();

        switch ($code) {
            case 10011:
                $codeProveedor = "session_error";
                $messageProveedor = "Player session error";
                break;

            case 21:
                $codeProveedor = "account_error";
                $messageProveedor = "Incorrect account_id";
                break;

            case 22:
                $codeProveedor = "account_error";
                $messageProveedor = "Incorrect account_id";
                break;
            case 20000:
                $codeProveedor = "session_error";
                $messageProveedor = "Player session error";
                break;
            case 20001:
                $codeProveedor = "balance_error";
                $messageProveedor = "There is not enough player balance for this operation";
                break;

            case 20014:
                $codeProveedor = "limit_error";
                $messageProveedor = "A player limit";
                break;

            case 27:
                $codeProveedor = "account_error";
                $messageProveedor = "gameplay does not exist";
                break;

            case 28:
                $codeProveedor = "win_canceled";
                $messageProveedor = "When the ICasino system cancels the game.";
                break;

            case 29:
                $codeProveedor = "duplicate_transaction";
                $messageProveedor = "When the ICasino system cancels the game.";
                break;

            case 10001:
                $codeProveedor = "duplicate_transaction";
                $messageProveedor = "Incorrect account_id";

                $transaccionApi2 = new TransaccionApi("", $this->transaccionApi->getTransaccionId(), $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($transaccionApi2->usuarioId);
                $Game = new Game();

                $responseG = $Game->getBalance($UsuarioMandante);

                $response = array(
                    "response_code" => "ok",
                    "response_message" => "ok",
                    "totalbalance" => intval(round($responseG->saldo, 2) * 100),
                    "currency" => $responseG->moneda
                    //"freeround_limit" => ""

                );

                break;


            case 10014:
                $codeProveedor = "win_canceled";
                $messageProveedor = "When the ICasino system cancels the game";
                break;

            case 10005:
                try {
                    $transaccionApi2 = new TransaccionApi("", $this->transaccionApi->getTransaccionId(), $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($transaccionApi2->usuarioId);
                    $Game = new Game();

                    $responseG = $Game->getBalance($UsuarioMandante);

                    $response = array(
                        "response_code" => "ok",
                        "response_message" => "ok",
                        "totalbalance" => intval(round($responseG->saldo, 2) * 100),
                        "currency" => $responseG->moneda
                        //"freeround_limit" => ""

                    );
                } catch (Exception $e) {
                    $codeProveedor = "error";
                    $messageProveedor = "General Error. (" . $code . ")";
                }

                break;
            case 21010:
                $codeProveedor = "account_error";
                $messageProveedor = "gameplay does not exist or finished and marked as failed";
                break;
            default:
                $codeProveedor = "error";
                $messageProveedor = "General Error. (" . $code . ")";
                break;
        }


        if ($code != 10001 && $code != 10005) {
            $respuesta = json_encode(array_merge($response, array(
                "response_code" => $codeProveedor,
                "response_message" => $messageProveedor

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
