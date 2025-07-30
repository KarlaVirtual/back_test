<?php

/**
 * Clase Booongo para la integración con el proveedor de juegos Booongo.
 *
 * Este archivo contiene la implementación de la clase Booongo, que maneja
 * las operaciones relacionadas con la integración de juegos, como autenticación,
 * balance, débitos, créditos, y más. También incluye el manejo de errores
 * específicos del proveedor.
 *
 * @category   Integración
 * @package    API
 * @subpackage Casino
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
 * Clase Booongo.
 *
 * Esta clase maneja la integración con el proveedor de juegos Booongo,
 * permitiendo realizar operaciones como autenticación, débitos, créditos,
 * obtención de balance, y manejo de errores específicos del proveedor.
 */
class Booongo
{
    /**
     * ID del operador.
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
     * @var string
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
     * Datos adicionales de la transacción.
     *
     * @var mixed
     */
    private $data;

    /**
     * ID de la ronda principal.
     *
     * @var string|null
     */
    private $roundIdSuper;

    /**
     * Constructor de la clase Booongo.
     *
     * @param string $token Token de autenticación.
     * @param string $sign  Firma de seguridad.
     * @param string $uid   Identificador único del usuario.
     */
    public function __construct($token, $sign, $uid)
    {
        $this->token = $token;
        $this->sign = $sign;
        $this->uid = $uid;
    }

    /**
     * Obtiene el ID del operador.
     *
     * @return integer|null
     */
    public function getOperadorId()
    {
        return $this->operadorId;
    }

    /**
     * Autentica al usuario con el proveedor Booongo.
     *
     * @return string JSON con los datos del usuario autenticado y su balance.
     */
    public function Auth()
    {
        try {
            $Proveedor = new Proveedor("", "BOOONGO");

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
                "uid" => $this->uid,
                "player" => array(
                    "id" => $UsuarioMandante->usumandanteId,
                    "nick" => $UsuarioMandante->nombres,
                    "currency" => $UsuarioMandante->moneda
                ),
                "balance" => array(
                    "value" => intval(round($responseG->saldo, 2) * 100),
                    "version" => intval($UsuarioMandante->usumandanteId . time())
                )
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del usuario.
     *
     * @return string JSON con el balance del usuario.
     */
    public function getBalance()
    {
        try {
            $Proveedor = new Proveedor("", "BOOONGO");

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
                "uid" => $this->uid,
                "balance" => array(
                    "value" => intval(round($responseG->saldo, 2) * 100),
                    "version" => intval($UsuarioMandante->usumandanteId . time())
                )
            );


            return json_encode($return);
        } catch (Exception $e) {
            $return = array(
                "uid" => $this->uid,
                "error" => array(
                    "code" => $e->getCode(),
                    "message" => $e->getMessage()
                )
            );
            return json_encode($return);
        }
    }

    /**
     * Realiza un débito en el balance del usuario.
     *
     * Este método se encarga de procesar una transacción de débito para un usuario
     * en el sistema Booongo. Valida el token, obtiene la información del proveedor,
     * usuario y producto, y realiza la operación de débito. Además, guarda la transacción
     * en la base de datos y devuelve el balance actualizado del usuario.
     *
     * @param string  $gameId        Identificador del juego.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $roundId       Identificador de la ronda.
     * @param string  $transactionId Identificador de la transacción.
     * @param mixed   $datos         Datos adicionales de la transacción.
     * @param boolean $freespin      Indica si la transacción es parte de un freespin.
     *
     * @return string JSON con el balance actualizado del usuario o un error en caso de fallo.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $freespin)
    {
        $this->data = $datos;

        try {
            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            //Obtenemos el Proveedor con el abreviado Booongo
            $Proveedor = new Proveedor("", "BOOONGO");

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

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "BOOONGO");
            //Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            //Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin);

            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "uid" => $this->uid,
                "balance" => array(
                    "value" => intval(round($responseG->saldo, 2) * 100),
                    "version" => intval($UsuarioMandante->usumandanteId . time())
                ),
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
     * Realiza un rollback en el balance del usuario.
     *
     * Este método se encarga de revertir una transacción previamente realizada
     * en el sistema Booongo. Valida el token, obtiene la información del proveedor,
     * usuario y producto, y realiza la operación de rollback. Además, guarda la
     * transacción en la base de datos y devuelve el balance actualizado del usuario.
     *
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $roundId        Identificador de la ronda.
     * @param string $transactionId  Identificador de la transacción original.
     * @param string $player         Identificador del jugador.
     * @param mixed  $datos          Datos adicionales de la transacción.
     *
     * @return string JSON con el balance actualizado del usuario o un error en caso de fallo.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos)
    {
        $usuarioid = $player;

        $this->data = $datos;


        try {
            //Obtenemos el Proveedor con el abreviado Booongo
            $Proveedor = new Proveedor("", "BOOONGO");

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


            //Obtenemos el producto con el gameId
            // $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());


            $Game = new Game();

            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false);

            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "uid" => $this->uid,
                "balance" => array(
                    "value" => intval(round($responseG->saldo, 2) * 100),
                    "version" => intval($UsuarioMandante->usumandanteId . time())
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
     * Realiza un crédito en el balance del usuario.
     *
     * Este método procesa una transacción de crédito para un usuario en el sistema Booongo.
     * Valida el token, obtiene la información del proveedor, usuario y producto, y realiza
     * la operación de crédito. Además, guarda la transacción en la base de datos y devuelve
     * el balance actualizado del usuario.
     *
     * @param string $gameId        Identificador del juego.
     * @param float  $creditAmount  Monto a acreditar.
     * @param string $roundId       Identificador de la ronda.
     * @param string $transactionId Identificador de la transacción.
     * @param mixed  $datos         Datos adicionales de la transacción.
     *
     * @return string JSON con el balance actualizado del usuario o un error en caso de fallo.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos)
    {
        $this->data = $datos;

        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try {
            //Obtenemos el Proveedor con el abreviado Booongo
            $Proveedor = new Proveedor("", "BOOONGO");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);

            //Obtenemos el Usuario Token con el token
            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            //Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "BOOONGO");


            //Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());


            //Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            //Agregamos Elementos a la Transaccion API
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());


            $Game = new Game();

            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, true);

            $this->transaccionApi = $responseG->transaccionApi;


            $return = array(
                "uid" => $this->uid,
                "balance" => array(
                    "value" => intval(round($responseG->saldo, 2) * 100),
                    "version" => intval($UsuarioMandante->usumandanteId . time())
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
     * Cierra la sesión del usuario en el sistema Booongo.
     *
     * Este método se encarga de realizar el proceso de cierre de sesión para un usuario.
     * Valida el token, obtiene la información del proveedor y del usuario, y devuelve
     * una respuesta indicando que la sesión ha sido cerrada.
     *
     * @return string JSON con el identificador único del usuario o un error en caso de fallo.
     */
    public function logout()
    {
        try {
            $Proveedor = new Proveedor("", "BOOONGO");
            if ($this->token != "") {
                //  Obtenemos el Usuario Token con el token
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                //  Obtenemos el Usuario Mandante con el Usuario Token
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                if ($this->token == "") {
                    throw new Exception("Token vacio", "10011");
                }
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            }

            $Game = new Game();

            $return = array(
                "uid" => $this->uid,
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Verifica un parámetro y devuelve un JSON con información relacionada.
     *
     * Este método toma un parámetro de entrada, lo procesa y devuelve un JSON
     * que incluye un identificador de nodo, el parámetro recibido y la firma
     * de seguridad asociada.
     *
     * @param mixed $param El parámetro a verificar.
     *
     * @return string JSON con el identificador de nodo, el parámetro y la firma.
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
     * Convierte un código de error y un mensaje en un JSON con información detallada.
     *
     * Este método toma un código de error y un mensaje, los procesa y devuelve un JSON
     * con información relacionada, incluyendo el código y mensaje traducidos al formato
     * del proveedor. También maneja casos específicos como transacciones existentes o
     * no encontradas, y actualiza la base de datos con la información de la transacción.
     *
     * @param integer $code    Código de error recibido.
     * @param string  $message Mensaje de error recibido.
     *
     * @return string JSON con información del error o balance actualizado en casos específicos.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $response = array(
            "uid" => $this->uid
        );

        $Proveedor = new Proveedor("", "BOOONGO");

        switch ($code) {
            case 10011:
                $codeProveedor = 'INVALID_TOKEN';
                $messageProveedor = "Passed token was not generated by the Operator.";

                break;

            case 21:
                $codeProveedor = 'INVALID_TOKEN';
                $messageProveedor = "The token is expired.";
                break;

            case 22:
                $codeProveedor = 'GAME_NOT_ALLOWED';
                $messageProveedor = "The player is not allowed to play this game.";
                break;

            case 20001:
                $codeProveedor = "FUNDS_EXCEED";
                $messageProveedor = "Insufficient funds";
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
                $codeProveedor = "TRANSACTION EXISTS";
                $messageProveedor = "Transaction Exists";
                if ($this->token != "" && $this->token != "-") {
                    //  Obtenemos el Usuario Token con el token
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    //  Obtenemos el Usuario Mandante con el Usuario Token
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } else {
                    //  Obtenemos el Usuario Mandante con el Usuario Token
                    $UsuarioMandante = new UsuarioMandante($this->externalId);
                }

                $Game = new Game();

                $responseG = $Game->getBalance($UsuarioMandante);

                $tipo = $this->transaccionApi->getTipo();


                $transaccionApi2 = new TransaccionApi("", $this->transaccionApi->getTransaccionId(), $Proveedor->getProveedorId());

                $return = array(
                    "uid" => $this->uid,
                    "balance" => array(
                        "value" => intval(round($responseG->saldo, 2) * 100),
                        "version" => intval($UsuarioMandante->usumandanteId . time())
                    ),
                );
                return json_encode($return);
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
                $codeProveedor = 'FATAL_ERROR';
                $messageProveedor = "Transaction Not Found";

                if ($this->token != "" && $this->token != "-") {
                    //  Obtenemos el Usuario Token con el token
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    //  Obtenemos el Usuario Mandante con el Usuario Token
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } else {
                    //  Obtenemos el Usuario Mandante con el Usuario Token
                    $UsuarioMandante = new UsuarioMandante($this->externalId);
                }

                $Game = new Game();

                $responseG = $Game->getBalance($UsuarioMandante);

                //$tipo = $this->transaccionApi->getTipo();
                //TransaccionJuego = new TransaccionJuego("", "BETGAMESTV" . $this->ticketIdGlobal, "");
                //$TransjuegoLog = new TransjuegoLog("", $TransaccionJuego->getTransjuegoId(), $tipo);

                // $transaccionApi2 = new TransaccionApi("",$this->transaccionApi->getTransaccionId(),$Proveedor->getProveedorId());

                $return = array(
                    "uid" => $this->uid,
                    "balance" => array(
                        "value" => intval(round($responseG->saldo, 2) * 100),
                        "version" => intval($UsuarioMandante->usumandanteId . time())
                    ),
                );
                return json_encode($return);
                break;

            default:
                $codeProveedor = 130;
                $messageProveedor = "General Error. (" . $code . ")";
                break;
        }

        if ($code != 10001 && $code != 10005) {
            $respuesta = json_encode(array_merge($response, array(
                "error" => array(
                    "code" => $codeProveedor,
                    "message" => $messageProveedor
                )

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
