<?php

/**
 * Clase Mascot para la integración con el proveedor de juegos MASCOT.
 *
 * Esta clase contiene métodos para manejar transacciones relacionadas con juegos,
 * como autenticación, consulta de balance, débito, crédito, y reversión de transacciones.
 * También incluye manejo de errores y generación de respuestas en formato JSON-RPC.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;

use Backend\dto\BonoInterno;
use Backend\dto\Mandante;
use Backend\dto\PromocionalLog;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransaccionApi;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioToken;
use Backend\dto\Producto;
use Backend\dto\ProductoMandante;
use Backend\dto\Proveedor;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransaccionApiMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\websocket\WebsocketUsuario;
use Exception;

/**
 * Clase Mascot.
 *
 * Esta clase representa la integración con el proveedor de juegos MASCOT.
 * Contiene métodos para manejar transacciones relacionadas con juegos,
 * como autenticación, consulta de balance, débito, crédito, y reversión de transacciones.
 */
class Mascot
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
     * @var string
     */
    private $uid;

    /**
     * Tipo de transacción.
     *
     * @var string
     */
    private $tipo;

    /**
     * Objeto de transacción API.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * Datos relacionados con la transacción.
     *
     * @var mixed
     */
    private $data;

    /**
     * Identificador de la transacción.
     *
     * @var string
     */
    private $id;

    /**
     * Identificador del usuario.
     *
     * @var string
     */
    private $userId;

    /**
     * Identificador del round principal.
     *
     * @var string
     */
    private $roundIdSuper;

    /**
     * Constructor de la clase Mascot.
     *
     * @param string $token      Token de autenticación.
     * @param string $playerName Nombre del jugador.
     */
    public function __construct($token, $playerName)
    {
        $this->token = $token;
        $this->userId = $playerName;
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
     * Autentica al usuario con el proveedor MASCOT.
     *
     * @return string Respuesta en formato JSON-RPC.
     */
    public function Auth()
    {
        try {
            $Proveedor = new Proveedor("", "MASCOT");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);


            if ($this->token == "") {
                $UsuarioMandante = new UsuarioMandante($this->userId);
                // $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->getUsumandanteId());
            } else {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            }

            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $return = array();

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del usuario.
     *
     * @param integer $id      ID de la solicitud.
     * @param string  $bonusId Opcional ID del bono.
     *
     * @return string Respuesta en formato JSON-RPC.
     */
    public function getBalance($id, $bonusId = "")
    {
        try {
            $Proveedor = new Proveedor("", "MASCOT");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("BALANCE");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);


            if ($this->token == "") {
                $UsuarioMandante = new UsuarioMandante($this->userId);
                // $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->getUsumandanteId());
            } else {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            }

            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);

            if ($bonusId != "") {
                $rules = [];
                array_push($rules, array("field" => "usuario_bono.estado", "data" => "A", "op" => "eq"));
                array_push($rules, array("field" => "usuario_bono.usuario_id", "data" => $UsuarioMandante->usuarioMandante, "op" => "eq"));
                array_push($rules, array("field" => "usuario_bono.bono_id", "data" => $bonusId, "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $UsuarioBono = new UsuarioBono();
                $Bonos = $UsuarioBono->getUsuarioBonosCustom("usuario_bono.*", "usuario_bono.usubono_id", "asc", 0, 1, $json, true);
                $Bonos = json_decode($Bonos);
                $UserBono = $Bonos->data[0];

                //$UsuarioBono = new UsuarioBono("",$UsuarioMandante->usuarioMandante,$bonusId,'','A');

                if ($_ENV['debug']) {
                    print_r($UserBono->{"usuario_bono.apostado"});
                }
                if (intval($UserBono->{"usuario_bono.apostado"}) > 0) {
                    $return = array(
                        "jsonrpc" => "2.0",
                        "id" => $id,
                        "result" => array(
                            "balance" => intval(round($responseG->saldo, 2) * 100),
                            "freeroundsLeft" => intval($UserBono->{"usuario_bono.apostado"}),
                        )
                    );
                } else {
                    $return = array(
                        "jsonrpc" => "2.0",
                        "id" => $id,
                        "result" => array(
                            "balance" => intval(round($responseG->saldo, 2) * 100)
                        )
                    );
                }
            } else {
                $return = array(
                    "jsonrpc" => "2.0",
                    "id" => $id,
                    "result" => array(
                        "balance" => intval(round($responseG->saldo, 2) * 100)
                    )
                );
            }


            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un débito en el sistema del proveedor MASCOT.
     *
     * @param string  $gameId           Identificador del juego.
     * @param float   $debitAmount      Monto a debitar.
     * @param string  $roundId          Identificador de la ronda.
     * @param string  $transactionId    Identificador de la transacción.
     * @param mixed   $datos            Datos adicionales de la transacción.
     * @param boolean $freespin         Indica si es un giro gratis.
     * @param string  $id               Identificador único de la solicitud.
     * @param string  $bonusId          Opcional Identificador del bono.
     * @param string  $chargeFreerounds Opcional Número de giros gratis a cargar.
     * @param boolean $Freeproveedor    Opcional Indica si el proveedor maneja los giros gratis.
     *
     * @return string Respuesta en formato JSON-RPC con el nuevo balance y el ID de la transacción.
     *
     * @throws Exception Si el token está vacío o si ocurre un error durante el proceso.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $freespin, $id, $bonusId = "", $chargeFreerounds = "", $Freeproveedor = "")
    {
        $this->data = $datos;
        $this->tipo = "DEBIT";
        $this->id = $id;
        $chargeFreerounds = intval($chargeFreerounds);
        try {
            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            //Obtenemos el Proveedor con el abreviado MASCOT
            $Proveedor = new Proveedor("", "MASCOT");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);


            if ($this->token == "") {
                $UsuarioMandante = new UsuarioMandante($this->userId);
                // $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->getUsumandanteId());
            } else {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            }


            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "MASCOT");
            //Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            //Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin);

            $this->transaccionApi = $responseG->transaccionApi;

            if ($chargeFreerounds === 0 || $freespin == true) {
                $rules = [];
                array_push($rules, array("field" => "usuario_bono.estado", "data" => "A", "op" => "eq"));
                array_push($rules, array("field" => "usuario_bono.usuario_id", "data" => $UsuarioMandante->usuarioMandante, "op" => "eq"));
                array_push($rules, array("field" => "usuario_bono.bono_id", "data" => $bonusId, "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $UsuarioBono = new UsuarioBono();
                $Bonos = $UsuarioBono->getUsuarioBonosCustom("usuario_bono.*", "usuario_bono.usubono_id", "asc", 0, 1, $json, true);
                $Bonos = json_decode($Bonos);
                $UserBono = $Bonos->data[0];
                $UserBonus = $UserBono->{"usuario_bono.usubono_id"};
                if ($_ENV['debug']) {
                    print_r($UserBonus);
                }
            }

            if ($chargeFreerounds === 0 && $Freeproveedor == false) {
                $UsuarioBono = new UsuarioBono($UserBonus);
                $UsuarioBono->setEstado("R");
                $UsuarioBonoMySqlDAO = new UsuarioBonoMySqlDAO();
                $Transaction = $UsuarioBonoMySqlDAO->getTransaction();
                $Transaction->getConnection()->beginTransaction();
                $UsuarioBonoMySqlDAO = new UsuarioBonoMySqlDAO($Transaction);
                $UsuarioBonoMySqlDAO->update($UsuarioBono);
                $Transaction->commit();
            }

            if ($freespin == true) {
                $BonoInterno = new BonoInterno();
                $UsuarioBonoMySqlDAO = new UsuarioBonoMySqlDAO();
                $UsuarioBono = new UsuarioBono($UserBonus);
                $Transaction = $UsuarioBonoMySqlDAO->getTransaction();

                $BonoInterno->RetarGiros($chargeFreerounds, $Transaction, $UsuarioBono);
                $Transaction->commit();
            }

            $return = array(
                "jsonrpc" => "2.0",
                "id" => $id,
                "result" => array(
                    "newBalance" => intval(round($responseG->saldo, 2) * 100),
                    "transactionId" => $responseG->transaccionId
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
     * Realiza una reversión de una transacción en el sistema del proveedor MASCOT.
     *
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $roundId        Identificador de la ronda.
     * @param string $transactionId  Identificador de la transacción original.
     * @param string $player         Identificador del jugador.
     * @param mixed  $datos          Datos adicionales de la transacción.
     * @param string $id             Identificador único de la solicitud.
     *
     * @return string Respuesta en formato JSON-RPC.
     *
     * @throws Exception Si ocurre un error durante el proceso o si la transacción no existe.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos, $id)
    {
        $usuarioid = $player;

        $this->data = $datos;
        $data = json_decode($datos);
        $gameId = $data->params->gameId;
        $this->id = $id;
        try {
            //Obtenemos el Proveedor con el abreviado MASCOT
            $Proveedor = new Proveedor("", "MASCOT");

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
                "jsonrpc" => "2.0",
                "id" => $id,
                "result" => array()
            );


            //Guardamos la Transaccion Api necesaria de estado OK
            $this->transaccionApi->setRespuestaCodigo("OK");
            $this->transaccionApi->setRespuesta(json_encode($return));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();


            return json_encode($return);
        } catch (Exception $e) {
            if ($_REQUEST['isDebug'] == '1') {
                print_r($e);
            }
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un crédito en el sistema del proveedor MASCOT.
     *
     * @param string  $gameId        Identificador del juego.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       Identificador de la ronda.
     * @param string  $transactionId Identificador de la transacción.
     * @param mixed   $datos         Datos adicionales de la transacción.
     * @param string  $id            Identificador único de la solicitud.
     * @param boolean $freespin      Indica si es un giro gratis (opcional).
     * @param string  $bonusId       Identificador del bono (opcional).
     *
     * @return string Respuesta en formato JSON-RPC con el nuevo balance, ID de la transacción y giros gratis restantes.
     *
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos, $id, $freespin = "", $bonusId = "")
    {
        $this->data = $datos;
        $this->tipo = "CREDIT";
        $this->id = $id;
        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try {
            //Obtenemos el Proveedor con el abreviado MASCOT
            $Proveedor = new Proveedor("", "MASCOT");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);

            if ($this->token == "") {
                $UsuarioMandante = new UsuarioMandante($this->userId);
                // $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->getUsumandanteId());
            } else {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            }

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "MASCOT");


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


            if ($freespin == true && $bonusId != "") {
                $rules = [];
                array_push($rules, array("field" => "usuario_bono.estado", "data" => "A", "op" => "eq"));
                array_push($rules, array("field" => "usuario_bono.usuario_id", "data" => $UsuarioMandante->usuarioMandante, "op" => "eq"));
                array_push($rules, array("field" => "usuario_bono.bono_id", "data" => $bonusId, "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $UsuarioBono = new UsuarioBono();
                $Bonos = $UsuarioBono->getUsuarioBonosCustom("usuario_bono.*", "usuario_bono.usubono_id", "asc", 0, 1, $json, true);
                $Bonos = json_decode($Bonos);
                $UserBono = $Bonos->data[0];
                $UserBonus = $UserBono->{"usuario_bono.usubono_id"};

                if ($_ENV['debug']) {
                    print_r($UserBonus);
                }

                $UsuarioBono = new UsuarioBono($UserBonus);

                $return = array(
                    "jsonrpc" => "2.0",
                    "id" => $id,
                    "result" => array(
                        "newBalance" => intval(round($responseG->saldo, 2) * 100),
                        "transactionId" => $responseG->transaccionId,
                        "freeroundsLeft" => intval($UsuarioBono->apostado)
                    )
                );
            } else {
                $return = array(
                    "jsonrpc" => "2.0",
                    "id" => $id,
                    "result" => array(
                        "newBalance" => intval(round($responseG->saldo, 2) * 100),
                        "transactionId" => $responseG->transaccionId,
                        "freeroundsLeft" => 0
                    )
                );
            }


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
     * @return string Respuesta en formato JSON que incluye el nodo, el parámetro y la firma.
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
     * Convierte un error en una respuesta en formato JSON.
     *
     * Este método maneja diferentes códigos de error y genera una respuesta
     * adecuada en formato JSON-RPC. También registra la transacción en caso
     * de error y actualiza la base de datos con la información correspondiente.
     *
     * @param integer $code    Código de error.
     * @param string  $message Mensaje de error.
     *
     * @return string Respuesta en formato JSON que incluye el código y mensaje de error.
     */
    public function convertError($code, $message)
    {
        if ($_ENV['debug']) {
            print_r($code);
            print_r($message);
        }

        $codeProveedor = "";
        $messageProveedor = "";


        $Proveedor = new Proveedor("", "MASCOT");
        $response = array();
        switch ($code) {
            case 10011:
                $codeProveedor = '1';
                $messageProveedor = "";

                break;

            case 21:
                $codeProveedor = '1';
                $messageProveedor = "";
                break;

            case 22:
                $codeProveedor = '1';
                $messageProveedor = "";
                break;

            case 20001:
                $codeProveedor = "6";
                $messageProveedor = "ErrNotEnoughMoneyCode";
                break;


            case 10001:

                $codeProveedor = '';
                $messageProveedor = "";
                $response = array(
                    "jsonrpc" => "2.0",
                    "id" => $this->id,
                    "result" => array()
                );


                break;

            case 10017:
                $codeProveedor = "2";
                $messageProveedor = "currency code not supported";
                break;

            case 10002:

                switch ($this->tipo) {
                    case "DEBIT":
                        $codeProveedor = "4";
                        $messageProveedor = "";
                        break;
                    case "CREDIT":
                        $codeProveedor = "3";
                        $messageProveedor = "";
                        break;
                }

                break;


            case 10005:
                $codeProveedor = '';
                $messageProveedor = "";
                $response = array(
                    "jsonrpc" => "2.0",
                    "id" => $this->id,
                    "result" => array()
                );

                break;

            default:
                $codeProveedor = "1";
                $messageProveedor = "";
                break;
        }

        if ($code != 10005 && $code != 10001) {
            $respuesta = json_encode(array_merge($response, array(
                "jsonrpc" => "2.0",
                "id" => time(),
                "error" => array(
                    "code" => $codeProveedor,
                    "message" => $messageProveedor
                )

            )));
        } else {
            $respuesta = json_encode(($response));
            $respuesta = json_encode(($response), JSON_FORCE_OBJECT);
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
