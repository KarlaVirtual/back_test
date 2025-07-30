<?php

/**
 * Clase End2end
 *
 * Esta clase proporciona métodos para realizar operaciones relacionadas con transacciones de juegos,
 * incluyendo autenticación, consulta de saldo, débitos, créditos, reversión de transacciones y manejo de errores.
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
use Backend\websocket\WebsocketUsuario;
use Exception;

/**
 * Clase principal para manejar las operaciones de integración con el casino.
 *
 * Proporciona métodos para realizar autenticación, consultas de saldo, débitos, créditos,
 * reversión de transacciones y manejo de errores relacionados con las transacciones de juegos.
 */
class End2end
{
    /**
     * Identificador del operador.
     *
     * @var mixed
     */
    private $operadorId;

    /**
     * Identificador del usuario.
     *
     * @var string
     */
    private $userId;

    /**
     * Identificador único del usuario.
     *
     * @var string
     */
    private $uid;

    /**
     * Objeto para manejar transacciones de la API.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * Datos adicionales relacionados con la transacción.
     *
     * @var mixed
     */
    private $data;

    /**
     * Identificador de la ronda principal.
     *
     * @var string
     */
    private $roundIdSuper;


    /**
     * Constructor de la clase End2end.
     *
     * @param string $userId ID del usuario (opcional).
     */
    public function __construct($userId = "")
    {
        if ($userId != "") {
            $this->userId = $userId;
        }
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
     * Realiza la autenticación del usuario.
     *
     * @return string Respuesta en formato JSON con los datos del usuario autenticado.
     * @throws Exception Si el token está vacío.
     */
    public function Auth()
    {
        try {
            $Proveedor = new Proveedor("", "E2E");

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

            $return = array(

                "PlayerId" => $UsuarioMandante->usumandanteId,
                "TotalBalance" => round($responseG->saldo, 2),
                "Token" => $UsuarioToken->getToken(),
                "HasError" => 0,
                "ErrorId" => 0,
                "ErrorDescription" => ""
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el saldo del usuario.
     *
     * @param string $account Cuenta del usuario.
     *
     * @return string Respuesta en formato JSON con el saldo del usuario.
     * @throws Exception Si el ID del usuario está vacío.
     */
    public function getBalance($account)
    {
        try {
            $Proveedor = new Proveedor("", "E2E");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("BALANCE");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);

            if ($this->userId == "") {
                throw new Exception("Usuario vacio", "10011");
            }

            $UsuarioMandante = new UsuarioMandante($this->userId);
            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);

            $return = array(
                "code" => 0,
                "realMoney" => round($responseG->saldo, 2)
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
     * @param string  $player        ID del jugador.
     * @param mixed   $datos         Datos adicionales.
     * @param boolean $freespin      Indica si es un giro gratis (opcional).
     *
     * @return string Respuesta en formato JSON con los detalles del débito.
     * @throws Exception Si el ID del usuario está vacío.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $player, $datos, $freespin = false)
    {
        $this->data = $datos;

        try {
            if ($this->userId == "") {
                throw new Exception("Id del usuario vacio", "10011");
            }

            //Obtenemos el Proveedor con el abreviado E2E
            $Proveedor = new Proveedor("", "E2E");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setUsuarioId($player);
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue($datos);
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);
            //Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($this->userId);

            $this->transaccionApi->setIdentificador($roundId . "_" . $UsuarioMandante->getUsumandanteId() . "E2E");

            try {
                //Obtenemos el producto con el gameId
                $Producto = new Producto("", "bingo", $Proveedor->getProveedorId());
            } catch (Exception $e) {
                if ($e->getCode() == '26') {
                    $gameId = 'General';
                    $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
                }
            }

            //Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin);

            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "code" => 0,
                "transactionId" => $responseG->transaccionId,
                "balance" => [
                    "realMoney" => round($responseG->saldo, 2)
                ],
                "usedMoney" => [
                    "realMoney" => $debitAmount
                ]
            );

            //Guardamos la Transaccion Api necesaria de estado OK
            $this->transaccionApi->setRespuestaCodigo("OK");
            $this->transaccionApi->setRespuesta(json_encode($return));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();
            sleep(15);
            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza una reversión individual de una transacción.
     *
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $roundId        ID de la ronda.
     * @param string $transactionId  ID de la transacción.
     * @param string $player         ID del jugador.
     * @param mixed  $datos          Datos adicionales.
     *
     * @return string Respuesta en formato JSON con los detalles de la reversión.
     * @throws Exception Si la transacción no existe.
     */
    public function RollbackIndividual($rollbackAmount, $roundId, $transactionId, $player, $datos)
    {
        $usuarioid = $player;

        $this->data = $datos;


        try {
            //Obtenemos el Proveedor con el abreviado E2E
            $Proveedor = new Proveedor("", "E2E");
            $Producto = new Producto('', '', $Proveedor->proveedorId);
            $ProductoMandante = new ProductoMandante($Producto->getProductoId());
            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);

            $identificador = "";
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
                "code" => 0,
                "transactionId" => $responseG->transaccionId,
                "balance" => [
                    "realMoney" => round($responseG->saldo, 2)
                ]
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
     * Realiza una reversión de transacciones asociadas a un juego.
     *
     * @param string  $gameId            ID del juego.
     * @param string  $accountIdentifier Identificador de la cuenta.
     * @param boolean $individual        Indica si la reversión es individual (opcional).
     *
     * @return string Respuesta en formato JSON con los detalles de la reversión.
     * @throws Exception Si no se encuentran transacciones.
     */
    public function Rollback($gameId, $accountIdentifier, $individual = false)
    {
        try {
            $MaxRows = 100;
            $SkeepRows = 0;
            $rules = [];
            array_push($rules, array("field" => "transaccion_juego.ticket_id", "data" => "$gameId", "op" => "cn"));
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);

            $Proveedor = new Proveedor("", "E2E");
            $TransaccionJuego = new TransaccionJuego();
            $transacciones = $TransaccionJuego->getTransaccionesCustom(" transaccion_juego.usuario_id, transaccion_juego.transaccion_id, transaccion_juego.valor_ticket ", "transaccion_juego.ticket_id", "asc", $SkeepRows, $MaxRows, $json, true, "");
            $transacciones = json_decode($transacciones);

            if ($transacciones->count[0]->{".count"} > 0) {
                foreach ($transacciones->data as $transaccion) {
                    if ($individual) {
                        $ticket = $transaccion->{"transaccion_juego.ticket_id"};
                        $gameId = explode("_", $ticket)[0];
                    }

                    $this->transaccionApi = new TransaccionApi();
                    $this->transaccionApi->setTransaccionId('ROLLBACK' . $transaccion->{"transaccion_juego.transaccion_id"});
                    $this->transaccionApi->setTipo("ROLLBACK");
                    $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
                    $this->transaccionApi->setTValue(json_encode(array($gameId, $accountIdentifier)));
                    $this->transaccionApi->setUsucreaId(0);
                    $this->transaccionApi->setUsumodifId(0);
                    $this->transaccionApi->setValor($transaccion->{"transaccion_juego.valor_ticket"});

                    $UsuarioMandante = new UsuarioMandante($transaccion->{"transaccion_juego.usuario_id"});
                    $this->transaccionApi->setIdentificador($gameId . "_" . $transaccion->{"transaccion_juego.transaccion_id"} . "E2E");

                    $Game = new Game();
                    $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi);

                    $return = array(
                        "code" => 0
                    );
                    $this->transaccionApi->setRespuestaCodigo("OK");
                    $this->transaccionApi->setRespuesta(json_encode($return));
                    $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                    $TransaccionApiMySqlDAO->update($this->transaccionApi);
                    $TransaccionApiMySqlDAO->getTransaction()->commit();
                }
            } else {
                return $this->convertError(29, "transaccion no encontrada");
            }

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un crédito en la cuenta del usuario.
     *
     * @param string $gameId        ID del juego.
     * @param float  $creditAmount  Monto a acreditar.
     * @param string $roundId       ID de la ronda.
     * @param string $transactionId ID de la transacción.
     * @param mixed  $datos         Datos adicionales.
     *
     * @return string Respuesta en formato JSON con los detalles del crédito.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos)
    {
        try {
            //Obtenemos el Proveedor con el abreviado E2E
            $Proveedor = new Proveedor("", "E2E");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);

            //Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($this->userId);

            $this->transaccionApi->setIdentificador($roundId . "_" . $UsuarioMandante->getUsumandanteId() . "E2E");

            try {
                //Obtenemos el producto con el gameId
                $Producto = new Producto("", "bingo", $Proveedor->getProveedorId());
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
            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, true);

            $this->transaccionApi = $responseG->transaccionApi;


            $return = array(
                "code" => 0,
                "transactionId" => $responseG->transaccionId,
                "balance" => [
                    "realMoney" => round($responseG->saldo, 2)
                ]
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
     * Verifica un parámetro y devuelve información relacionada.
     *
     * @param mixed $param Parámetro a verificar.
     *
     * @return string Respuesta en formato JSON con los detalles de la verificación.
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
     * Convierte un error en una respuesta JSON.
     *
     * @param integer $code    Código del error.
     * @param string  $message Mensaje del error.
     *
     * @return string Respuesta en formato JSON con los detalles del error.
     */
    public function convertError($code, $message)
    {
        // http_response_code(400);
        $codeProveedor = "";
        $messageProveedor = "";

        $response = array();

        $Proveedor = new Proveedor("", "E2E");

        switch ($code) {
            case 10011:
                $codeProveedor = 102;
                $messageProveedor = "Invalid Token";
                break;

            case 21:
                $codeProveedor = 102;
                $messageProveedor = "Invalid Token";
                break;

            case 22:
                $codeProveedor = 130;
                $messageProveedor = "Invalid Token";
                break;

            case 20001:
                $codeProveedor = "21";
                $messageProveedor = "Not Enough Balance";
                break;

            case 0:
                $codeProveedor = 130;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 27:
                $codeProveedor = 130;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 28:
                $codeProveedor = 130;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 29:
                $codeProveedor = 107;
                $messageProveedor = "Transaction Not Found";
                break;

            case 10001:
                $codeProveedor = 110;
                $messageProveedor = "Transaction Exists";
                break;

            case 10004:
                $codeProveedor = 130;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 10014:
                $codeProveedor = 130;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 10010:
                $codeProveedor = 130;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 10005:
                $codeProveedor = 107;
                $messageProveedor = "Transaction Not Found";
                break;

            default:
                $codeProveedor = 130;
                $messageProveedor = "General Error. (" . $code . ")";
                break;
        }


        if ($codeProveedor != "") {
            $respuesta = json_encode(array_merge($response, array(
                "code" => $codeProveedor,
                "message" => $messageProveedor
            )));
        } else {
            $respuesta = json_encode(array_merge($response));
        }

        if ($this->transaccionApi != null) {
            $this->transaccionApi->setTipo("R" . $this->transaccionApi->getTipo());
            $this->transaccionApi->setRespuestaCodigo("ERROR");
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->insert($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();
        }

        return $respuesta;
    }


}
