<?php

/**
 * Clase Wazdan para la integración con el proveedor de juegos Wazdan.
 *
 * Este archivo contiene la implementación de métodos para manejar transacciones
 * relacionadas con el proveedor Wazdan, como autenticación, balance, débitos, créditos,
 * y manejo de errores. También incluye métodos para cerrar sesiones de juego y verificar
 * parámetros.
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
use Backend\dto\Pais;
use Backend\dto\PromocionalLog;
use Backend\dto\Registro;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransaccionApi;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\UsuarioToken;
use Backend\dto\Producto;
use Backend\dto\ProductoMandante;
use Backend\dto\Proveedor;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransaccionApiMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;
use Exception;

/**
 * Clase Wazdan.
 *
 * Esta clase implementa la integración con el proveedor de juegos Wazdan,
 * proporcionando métodos para manejar transacciones como autenticación,
 * balance, débitos, créditos, y más.
 */
class Wazdan
{
    /**
     * Identificador del operador.
     *
     * @var string|null
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
     * Firma de seguridad para validación.
     *
     * @var string|null
     */
    private $sign;

    /**
     * Objeto de transacción API.
     *
     * @var TransaccionApi|null
     */
    private $transaccionApi;

    /**
     * Datos adicionales relacionados con la transacción.
     *
     * @var array|null
     */
    private $data;

    /**
     * Identificador de la ronda en el sistema.
     *
     * @var string|null
     */
    private $roundIdSuper;

    /**
     * Constructor de la clase Wazdan.
     *
     * @param string $token Token de autenticación.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Obtiene el ID del operador.
     *
     * @return string|null ID del operador.
     */
    public function getOperadorId()
    {
        return $this->operadorId;
    }

    /**
     * Autentica al usuario con el proveedor Wazdan.
     *
     * @return string Respuesta en formato JSON con los datos del usuario y balance.
     * @throws Exception Si ocurre un error durante la autenticación.
     */
    public function Auth()
    {
        try {
            $Proveedor = new Proveedor("", "WAZDAN");

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

            $Registro = new Registro("", $UsuarioMandante->getUsuarioMandante());
            $UsuarioOtrainfo = new UsuarioOtrainfo($UsuarioMandante->getUsuarioMandante());
            $Pais = new Pais($UsuarioMandante->paisId);
            $Mandante = new Mandante($UsuarioMandante->getMandante());
            switch ($Mandante->mandante) {
                case 0:
                    $parnet = "Doradobet";
                    break;

                case 1:
                    //    $parnet = "Guyana";
                    break;

                case 2:
                    // $parnet = "Justbet";
                    break;

                case 6:
                    //$parnet = "Netabet";
                    break;

                case 8:
                    $parnet = "Ecuabet";
                    break;
            }

            $return = array(
                "status" => 0,
                "user" => array(
                    "id" => "Usuario" . $UsuarioMandante->usumandanteId,
                    "currency" => $UsuarioMandante->moneda,
                    "birthDate" => $UsuarioOtrainfo->getFechaNacim(),
                    "gender" => $Registro->getSexo(),
                    "country" => $Pais->iso,
                    "skinId" => $parnet
                ),
                "funds" => array(
                    "balance" => doubleval($responseG->saldo)
                ),
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
            $Proveedor = new Proveedor("", "WAZDAN");

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
                "status" => 0,
                "funds" => array(
                    "balance" => doubleval($responseG->saldo)
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
     * @param string  $gameId        ID del juego.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param array   $datos         Datos adicionales.
     * @param boolean $freespin      Indica si es un giro gratis.
     *
     * @return string Respuesta en formato JSON con el balance actualizado.
     * @throws Exception Si ocurre un error durante el débito.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $freespin)
    {
        $this->data = $datos;

        try {
            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            //Obtenemos el Proveedor con el abreviado Wazdan
            $Proveedor = new Proveedor("", "WAZDAN");

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

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "WAZDAN");
            //Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            //Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin);

            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "status" => 0,
                "funds" => array(
                    "balance" => doubleval($responseG->saldo)
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
     * Realiza un rollback de una transacción.
     *
     * @param float  $rollbackAmount             Monto a revertir.
     * @param string $roundId                    ID de la ronda.
     * @param string $transactionId              ID de la transacción.
     * @param string $player                     ID del jugador.
     * @param array  $datos                      Datos adicionales.
     * @param string $reference_transaction_uuid UUID de la transacción de referencia.
     *
     * @return string Respuesta en formato JSON con el balance actualizado.
     * @throws Exception Si ocurre un error durante el rollback.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos, $reference_transaction_uuid)
    {
        $this->tipo = "ROLLBACK";

        $this->data = $datos;

        $this->player = $player;
        try {
            //Obtenemos el Proveedor con el abreviado Caleta
            $Proveedor = new Proveedor("", "WAZDAN");

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
                $TransaccionApi2 = new TransaccionApi("", $reference_transaction_uuid, $Proveedor->getProveedorId());
                $producto = $TransaccionApi2->getProductoId();
                $identificador = $TransaccionApi2->getIdentificador();
                $UsuarioMandante = new UsuarioMandante($TransaccionApi2->getUsuarioId());
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }


            $this->transaccionApi->setProductoId($producto);
            $this->transaccionApi->setIdentificador($identificador);


            $Game = new Game();

            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false, $reference_transaction_uuid, false);

            $this->transaccionApi = $responseG->transaccionApi;


            $return = array(
                "status" => 0,
                "funds" => array(
                    "balance" => doubleval($responseG->saldo)
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
     * Realiza un crédito en la cuenta del usuario.
     *
     * @param string $gameId        ID del juego.
     * @param float  $creditAmount  Monto a acreditar.
     * @param string $roundId       ID de la ronda.
     * @param string $transactionId ID de la transacción.
     * @param array  $datos         Datos adicionales.
     *
     * @return string Respuesta en formato JSON con el balance actualizado.
     * @throws Exception Si ocurre un error durante el crédito.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos)
    {
        $this->data = $datos;

        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try {
            //Obtenemos el Proveedor con el abreviado Wazdan
            $Proveedor = new Proveedor("", "WAZDAN");

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

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "Wazdan");


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
                "status" => 0,
                "funds" => array(
                    "balance" => doubleval($responseG->saldo)
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
     * Cierra la sesión de juego del usuario.
     *
     * @return string Respuesta en formato JSON indicando el estado de la operación.
     * @throws Exception Si ocurre un error al cerrar la sesión.
     */
    public function gameClose()
    {
        try {
            $Proveedor = new Proveedor("", "WAZDAN");

            if ($this->token != "") {
                //  Obtenemos el Usuario Token con el token
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                //  Obtenemos el Usuario Mandante con el Usuario Token
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                throw new Exception("Token vacio", "10011");
            }


            if ($UsuarioToken->getUsutokenId() != "") {
                $UsuarioToken->setToken($UsuarioToken->getToken());
                $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                $UsuarioTokenMySqlDAO->update($UsuarioToken);
                $UsuarioTokenMySqlDAO->getTransaction()->commit();
            }

            $return = array(
                "status" => 0,
            );

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
     * @return string Respuesta en formato JSON con los datos del parámetro.
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
     * Convierte un error en una respuesta JSON manejable.
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

        $Proveedor = new Proveedor("", "WAZDAN");

        switch ($code) {
            case 10011:
                $codeProveedor = 1;
                $messageProveedor = "Session not found";

                break;
            case 10012:
                $codeProveedor = 3;
                $messageProveedor = "Session already exists";

                break;
            case 21:
                $codeProveedor = 1;
                $messageProveedor = "Session not found.";
                break;

            case 20001:
                $codeProveedor = 8;
                $messageProveedor = "Insufficient funds";
                break;

            case 10001:
                $codeProveedor = "";
                $messageProveedor = "";
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


                $response = array(
                    "status" => 0,
                    "funds" => array(
                        "balance" => doubleval($responseG->saldo)
                    )
                );


                break;


            case 20000:
                $codeProveedor = 2;
                $messageProveedor = "Session expired";
                break;

            case 10005:
                $codeProveedor = '';
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

                $response = array(
                    "status" => 0,
                    "funds" => array(
                        "balance" => doubleval($responseG->saldo)
                    )
                );
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
