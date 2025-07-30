<?php

/**
 * Clase Boss para la integración con el proveedor de casino BOSS.
 *
 * Esta clase contiene métodos para manejar transacciones como autenticación,
 * consulta de balance, débitos, créditos, reversión de transacciones y manejo de errores.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
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
 * Clase Boss.
 *
 * Esta clase maneja la integración con el proveedor de casino BOSS,
 * proporcionando métodos para realizar transacciones como autenticación,
 * consulta de balance, débitos, créditos, reversión de transacciones y manejo de errores.
 */
class Boss
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
     * Firma para validación.
     *
     * @var string
     */
    private $sign;

    /**
     * Firma original utilizada para validación.
     *
     * @var string
     */
    private $signOriginal = "D0rad0DEV";

// private $signOriginal = "D0rad0PROD";

    /**
     * Objeto para manejar transacciones API.
     *
     * @var TransaccionApi|null
     */
    private $transaccionApi;

    /**
     * Datos adicionales para las transacciones.
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
     * Constructor de la clase Boss.
     *
     * @param string $token Token de autenticación.
     * @param string $sign  Firma para validación.
     */
    public function __construct($token = "", $sign = "")
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
     * Autentica al usuario con el proveedor BOSS.
     *
     * @return string Respuesta en formato JSON con el balance y la moneda.
     */
    public function Auth()
    {
        try {
            if ($this->sign != $this->signOriginal) {
                //throw new Exception("Sign Error", "20002");
            }

            $Proveedor = new Proveedor("", "BOSS");

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

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $Game = new Game();

            $responseG = $Game->autenticate($UsuarioMandante);


            $return = array(
                "status" => 200,
                "balance" => (($responseG->saldo)),
                "currency" => $UsuarioMandante->getMoneda()
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del usuario.
     *
     * @return string Respuesta en formato JSON con el balance y la moneda.
     */
    public function getBalance()
    {
        try {
            if ($this->sign != $this->signOriginal) {
                ////throw new Exception("Sign Error", "20002");
            }

            $Proveedor = new Proveedor("", "BOSS");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("BALANCE");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);


            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            } else {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            }

            $Game = new Game();

            $responseG = $Game->getBalance($UsuarioMandante);

            $return = array(
                "status" => 200,
                "balance" => (($responseG->saldo)),
                "currency" => $responseG->moneda

            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un débito en el balance del usuario.
     *
     * @param string $gameId        ID del juego.
     * @param float  $debitAmount   Monto a debitar.
     * @param string $roundId       ID de la ronda.
     * @param string $transactionId ID de la transacción.
     * @param mixed  $datos         Datos adicionales.
     *
     * @return string Respuesta en formato JSON con el balance actualizado.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos)
    {
        $this->data = $datos;

        try {
            if ($this->sign != $this->signOriginal) {
                //throw new Exception("Sign Error", "20002");
            }

            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            // Obtenemos el Proveedor con el abreviado BOSS 
            $Proveedor = new Proveedor("", "BOSS");

            // Creamos la Transaccion API 
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);


            // Obtenemos el Usuario Token con el token 
            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            // Obtenemos el Usuario Mandante con el Usuario Token 
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);


            $this->transaccionApi->setIdentificador($roundId . "BOSS");


            // Obtenemos el producto con el gameId  
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());


            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi);

            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "status" => 200,
                "balance" => (($responseG->saldo)),
                "referenceId" => $responseG->transaccionId,
                "currency" => $responseG->moneda,

            );

            // Guardamos la Transaccion Api necesaria de estado OK  
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
     * Realiza una reversión de una transacción (rollback) en el balance del usuario.
     *
     * Este método se utiliza para revertir una transacción previamente realizada,
     * actualizando el balance del usuario y registrando la transacción como un rollback.
     *
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $roundId        ID de la ronda asociada.
     * @param string $transactionId  ID de la transacción a revertir.
     * @param string $player         Identificador del jugador.
     * @param mixed  $datos          Datos adicionales para la transacción.
     *
     * @return string Respuesta en formato JSON con el balance actualizado y otros detalles.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos)
    {
        // $usuarioid = explode("Usuario", $player)[1];
        $this->data = $datos;

        try {
            if ($this->sign != $this->signOriginal) {
                //throw new Exception("Sign Error", "20002");
            }

            // Obtenemos el Proveedor con el abreviado BOSS 
            $Proveedor = new Proveedor("", "BOSS");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($rollbackAmount);


            // Obtenemos el Usuario Token con el token 
            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            // Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $this->transaccionApi->setIdentificador($roundId . "BOSS");

            // Obtenemos el producto con el gameId 
            // $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());


            $Game = new Game();

            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi);

            $this->transaccionApi = $responseG->transaccionApi;


            $return = array(
                "status" => 200,
                "balance" => (($responseG->saldo)),
                "referenceId" => $responseG->transaccionId,
                "currency" => $UsuarioMandante->getMoneda()
            );

            // Guardamos la Transaccion Api necesaria de estado OK 
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
     * Realiza un crédito en el balance del usuario.
     *
     * Este método se utiliza para agregar un monto al balance del usuario,
     * registrando la transacción como un crédito.
     *
     * @param string $gameId        ID del juego asociado al crédito.
     * @param float  $creditAmount  Monto a acreditar.
     * @param string $roundId       ID de la ronda asociada.
     * @param string $transactionId ID de la transacción. Si está vacío, se genera uno automáticamente.
     * @param mixed  $datos         Datos adicionales para la transacción.
     *
     * @return string Respuesta en formato JSON con el balance actualizado y otros detalles.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos)
    {
        $this->data = $datos;

        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try {
            if ($this->sign != $this->signOriginal) {
                //throw new Exception("Sign Error", "20002");
            }

            // Obtenemos el Proveedor con el abreviado BOSS 
            $Proveedor = new Proveedor("", "BOSS");

            // Creamos la Transaccion API  
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);
            $this->transaccionApi->setIdentificador($roundId . "BOSS");

            // Obtenemos el Usuario Token con el token 
            try {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } catch (Exception $e) {
                if ($e->getCode() == 21) {
                    $TransaccionJuego = new TransaccionJuego("", $roundId . "BOSS");
                    $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                }
            }

            // Obtenemos el Usuario Mandante con el Usuario Token

            // Obtenemos el producto con el gameId 
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            // Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego 
            // $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            // Agregamos Elementos a la Transaccion API 
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());

            $Game = new Game();

            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, true);

            $this->transaccionApi = $responseG->transaccionApi;


            // Retornamos el mensaje satisfactorio 
            $return = array(
                "status" => 200,
                "balance" => (($responseG->saldo)),
                "referenceId" => $responseG->transaccionId,
                "currency" => $responseG->moneda
            );

            // Guardamos la Transaccion Api necesaria de estado OK 
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
     * Verifica el estado del sistema o realiza una comprobación con un parámetro dado.
     *
     * @param mixed $param Parámetro para la comprobación.
     *
     * @return string Respuesta en formato JSON con los detalles de la comprobación.
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
     * Convierte un error en una respuesta JSON adecuada.
     *
     * Este método toma un código de error y un mensaje, y los convierte en una
     * respuesta JSON que incluye detalles del error, balance del usuario (si aplica),
     * y otros datos relevantes. También registra la transacción como un error en
     * la base de datos si es necesario.
     *
     * @param integer $code    Código del error.
     * @param string  $message Mensaje descriptivo del error.
     *
     * @return string Respuesta en formato JSON con los detalles del error.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";
        $response = array();

        $Proveedor = new Proveedor("", "BOSS");

        switch ($code) {
            case 10011:
                $codeProveedor = "403";
                $messageProveedor = "No such session.";
                break;

            case 21:
                $codeProveedor = "403";
                $messageProveedor = "No such session.";
                break;

            case 22:
                $codeProveedor = "403";
                $messageProveedor = "No such session.";
                break;

            case 20001:
                $codeProveedor = "402";
                $messageProveedor = "Insufficient funds.";
                break;

            case 0:
                $codeProveedor = "402";
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 27:
                $codeProveedor = "402";
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 28:
                $codeProveedor = "402";
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 29:
                $codeProveedor = "402";
                $messageProveedor = "Transaction Not Found";

                $tipo = $this->transaccionApi->getTipo();

                if ($tipo == "ROLLBACK") {
                    $codeProveedor = '';
                    $messageProveedor = "";

                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                    $Mandante = new Mandante($UsuarioMandante->mandante);

                    if ($Mandante->propio == "S") {
                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                        $Balance = $Usuario->getBalance();

                        $response = array_merge($response, array(
                            "balance" => (($Balance)),
                        ));
                    }
                }

                break;

            case 10001:

                $codeProveedor = 0;
                $messageProveedor = "Already processed";

                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                $Mandante = new Mandante($UsuarioMandante->mandante);

                if ($Mandante->propio == "S") {
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $Balance = $Usuario->getBalance();

                    $response = array_merge($response, array(
                        "balance" => (($Balance)),
                        "currency" => $UsuarioMandante->getMoneda(),
                        "message" => "Already processed"

                    ));
                }


                break;

            case 10004:
                $codeProveedor = "REQUEST_DECLINED";
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 10014:
                $codeProveedor = "REQUEST_DECLINED";
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 10010:
                $codeProveedor = "REQUEST_DECLINED";
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 20001:
                $codeProveedor = "402";
                $messageProveedor = "Insufficients Funds";
                break;

            case 20002:
                break;

            case 20003:
                $codeProveedor = "ACCOUNT_BLOCKED";
                $messageProveedor = "ACCOUNT_BLOCKED";
                break;

            case 10005:

                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

                    $Mandante = new Mandante($UsuarioMandante->mandante);

                    if ($Mandante->propio == "S") {
                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                        $Balance = $Usuario->getBalance();

                        $response = array_merge($response, array(
                            "balance" => (($Balance)),
                        ));
                    }
                } catch (Exception $e) {
                    $codeProveedor = "400";
                    $messageProveedor = "No such session.";
                }

                $this->transaccionApi->setValor(0);
                break;

            default:
                $codeProveedor = 'UNKNOWN_ERROR';
                $messageProveedor = "Unexpected error. (" . $code . ")";
                break;
        }


        if ($codeProveedor != "") {
            $respuesta = json_encode(array_merge($response, array(
                "status" => intval($codeProveedor),
                "error" => $messageProveedor
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