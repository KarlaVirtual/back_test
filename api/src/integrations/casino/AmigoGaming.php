<?php

/**
 * Clase AmigoGaming
 *
 * Esta clase implementa la integración con el proveedor de juegos AmigoGaming.
 * Proporciona métodos para autenticar usuarios, consultar saldos, realizar débitos, créditos,
 * y manejar transacciones relacionadas con juegos.
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
use Backend\dto\Subproveedor;
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
 * Clase principal para la integración con el proveedor AmigoGaming.
 *
 * Esta clase contiene métodos para manejar la autenticación, transacciones,
 * y otras operaciones relacionadas con el proveedor de juegos AmigoGaming.
 */
class AmigoGaming
{
    /**
     * Token de autenticación utilizado para identificar al usuario.
     *
     * @var string
     */
    private $token;

    /**
     * Identificador externo del usuario.
     *
     * @var string
     */
    private $externalId;

    /**
     * Firma de validación para las transacciones.
     *
     * @var string
     */
    private $sign;

    /**
     * Objeto que representa la transacción API actual.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * Indica si hay un error en el hash de la transacción.
     *
     * @var boolean
     */
    private $errorHash = false;

    /**
     * Método actual que se está ejecutando en la clase.
     *
     * @var string
     */
    private $method;

    /**
     * Constructor de la clase AmigoGaming.
     *
     * @param string $token    Token de autenticación.
     * @param string $sign     Firma de validación.
     * @param string $external Identificador externo del usuario.
     */
    public function __construct($token, $sign = "", $external = "")
    {
        $this->token = $token;
        $this->sign = $sign;
        $this->externalId = $external;
    }

    /**
     * Autentica al usuario con el proveedor AmigoGaming.
     *
     * @return string Respuesta en formato JSON con los datos del usuario autenticado.
     * @throws Exception Si el token o el identificador externo están vacíos.
     */
    public function Auth()
    {
        $this->method = 'authenticate';
        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }
            $Proveedor = new Proveedor("", "AMIGOGAMING");

            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }


            $Game = new Game();

            $responseG = $Game->autenticate($UsuarioMandante);

            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));
            $return = array(

                "userId" => $responseG->usuarioId,
                "currency" => $responseG->moneda, //$responseG->moneda
                "cash" => $saldo,
                "bonus" => 0,
                "error" => 0,
                "description" => "Success"
                //"token" => $UsuarioToken->token,
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }


    /**
     * Obtiene el saldo del usuario.
     *
     * @param string $playerId Identificador del jugador.
     *
     * @return string Respuesta en formato JSON con el saldo del usuario.
     * @throws Exception Si el identificador del jugador está vacío.
     */
    public function getBalance($playerId)
    {
        $this->externalId = $playerId;
        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }

        $this->method = 'balance';
        try {
            /*if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }*/

            if ($playerId == "") {
                throw new Exception("Token vacio", "10011");
            }

            $Proveedor = new Proveedor("", "AMIGOGAMING");


            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            $Game = new Game();

            $responseG = $Game->getBalance($UsuarioMandante);

            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

            $return = array(
                "currency" => $responseG->moneda, //$responseG->moneda
                "cash" => $saldo,
                "bonus" => 0,
                "error" => 0,
                "description" => "Success"

            );
            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Verifica el bono del usuario.
     *
     * @param string $playerId  Identificador del jugador.
     * @param string $roundId   Identificador de la ronda.
     * @param string $reference Referencia de la transacción.
     *
     * @return string Respuesta en formato JSON con los datos del bono.
     * @throws Exception Si el identificador del jugador está vacío.
     */
    public function bonusCheck($playerId, $roundId, $reference)
    {
        $this->externalId = $playerId;
        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }

        $this->method = 'balance';
        try {
            if ($playerId == "") {
                throw new Exception("Token vacio", "10011");
            }

            $Proveedor = new Proveedor("", "AMIGOGAMING");


            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            $Producto = new Producto($UsuarioToken->productoId);
            $Subproveedor = new Subproveedor($Producto->subproveedorId);
            $Game = new Game();

            $responseG = $Game->getBalance($UsuarioMandante, $Subproveedor->subproveedorId, $UsuarioToken->productoId);

            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

            $return = array(
                "transactionId" => $roundId . $reference,
                "currency" => $responseG->moneda,
                "cash" => $saldo,
                "bonus" => 0,
                "error" => 0,
                "description" => "Success"
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un débito en la cuenta del usuario.
     *
     * @param string  $gameId        Identificador del juego.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $roundId       Identificador de la ronda.
     * @param string  $transactionId Identificador de la transacción.
     * @param array   $datos         Datos adicionales de la transacción.
     * @param boolean $isfreeSpin    Indica si es un giro gratuito.
     *
     * @return string Respuesta en formato JSON con los datos de la transacción.
     * @throws Exception Si el identificador del usuario está vacío.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $isfreeSpin = false)
    {
        $this->method = 'debit';

        $this->data = $datos;

        try {
            /*  Obtenemos el Proveedor con el abreviado AMIGOGAMING */
            $Proveedor = new Proveedor("", "AMIGOGAMING");
            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);

            if ($this->externalId != "") {
                // Obtenemos el Usuario Token con el token
                $UsuarioToken = new UsuarioToken('', $Proveedor->getProveedorId(), $this->externalId);

                //  Obtenemos el Usuario Mandante con el Usuario Token
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                throw new Exception("UsuarioId vacio", "10021");
            }


            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "AMIGOGAMING");
            /*  Obtenemos el producto con el gameId  */
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            $Producto = new Producto($UsuarioToken->productoId);
            $Subproveedor = new Subproveedor($Producto->subproveedorId);
            $Game = new Game();

            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $isfreeSpin);
            $this->transaccionApi = $responseG->transaccionApi;

            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

            $respuesta = json_encode(array(
                "transactionId" => $responseG->transaccionId,
                "currency" => "EUR", //$responseG->moneda
                "cash" => $saldo,
                "bonus" => 0,
                "usedPromo" => 0,
                "error" => 0,
                "description" => "Success"
            ));


            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();


            return $respuesta;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un rollback de una transacción.
     *
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $roundId        Identificador de la ronda.
     * @param string $transactionId  Identificador de la transacción.
     * @param string $player         Identificador del jugador.
     * @param array  $datos          Datos adicionales de la transacción.
     *
     * @return string Respuesta en formato JSON con los datos de la transacción revertida.
     * @throws Exception Si la transacción no existe o los detalles no coinciden.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos)
    {
        $this->method = "ROLLBACK";

        $this->data = $datos;


        try {
            //Obtenemos el Proveedor con el abreviado AMIGOGAMING
            $Proveedor = new Proveedor("", "AMIGOGAMING");

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

            if ($TransaccionApi2->getValor() != $rollbackAmount) {
                throw new Exception("Detalles de la transacción no coinciden", "10007");
            }


            $this->transaccionApi->setProductoId($producto);
            $this->transaccionApi->setIdentificador($identificador);


            $Game = new Game();

            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false, $transactionId);


            $this->transaccionApi = $responseG->transaccionApi;
            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

            $respuesta = json_encode(array(
                "transactionId" => $responseG->transaccionId,
                "error" => 0,
                "description" => "Success"
            ));


            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();


            return $respuesta;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }


    /**
     * Realiza un rollback de una ronda completa.
     *
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $roundId        Identificador de la ronda.
     * @param string $transactionId  Identificador de la transacción.
     * @param string $player         Identificador del jugador.
     * @param array  $datos          Datos adicionales de la transacción.
     *
     * @return string Respuesta en formato JSON con los datos de la transacción revertida.
     * @throws Exception Si el hash es inválido o hay errores en la transacción.
     */
    public function RollbackRound($rollbackAmount, $roundId, $transactionId, $player, $datos)
    {
        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }


        $this->method = 'cancelReserve';

        $this->data = $datos;

        try {
            /*  Obtenemos el Proveedor con el abreviado AMIGOGAMING */
            $Proveedor = new Proveedor("", "AMIGOGAMING");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($rollbackAmount);


            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            $this->transaccionApi->setIdentificador("AMIGOGAMING" . $roundId);

            /*  Obtenemos el producto con el gameId  */
            $Game = new Game();

            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi);

            $this->transaccionApi = $responseG->transaccionApi;
            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

            $respuesta = json_encode(array(
                "transactionId" => $responseG->transaccionId,
                "cash" => $saldo,
                "currency" => $responseG->moneda, //$responseG->moneda
                "bonus" => 0,
                "usedPromo" => 0,
                "error" => 0,
                "description" => "Success"
            ));


            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();


            return $respuesta;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un crédito en la cuenta del usuario.
     *
     * @param string  $gameId        Identificador del juego.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       Identificador de la ronda.
     * @param string  $transactionId Identificador de la transacción.
     * @param array   $datos         Datos adicionales de la transacción.
     * @param boolean $isBonus       Indica si es un bono.
     *
     * @return string Respuesta en formato JSON con los datos de la transacción.
     * @throws Exception Si el identificador del usuario está vacío.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos, $isBonus = false)
    {
        $this->method = 'credit';

        $this->data = $datos;

        try {
            /*  Obtenemos el Proveedor con el abreviado AMIGOGAMING */
            $Proveedor = new Proveedor("", "AMIGOGAMING");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);
            //$this->transaccionApi->setIdentificador($roundId . "GameArt");

            /*  Obtenemos el Usuario Token con el token */
            if ($this->externalId != "") {
                // Obtenemos el Usuario Token con el token
                $UsuarioToken = new UsuarioToken('', $Proveedor->getProveedorId(), $this->externalId);

                //  Obtenemos el Usuario Mandante con el Usuario Token
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                throw new Exception("UsuarioId vacio", "10021");
            }

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "AMIGOGAMING");

            //  Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());
            /*  Obtenemos el producto con el $TransaccionJuego->productoId */
            $Producto = new Producto($ProductoMandante->productoId);
            //  Agregamos Elementos a la Transaccion API
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());

            $Game = new Game();

            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, true, false, $isBonus);

            $this->transaccionApi = $responseG->transaccionApi;


            /*  Retornamos el mensaje satisfactorio  */
            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

            $respuesta = json_encode(array(
                "transactionId" => $responseG->transaccionId,
                "currency" => $responseG->moneda, //$responseG->moneda
                "cash" => $saldo,
                "bonus" => 0,
                "error" => 0,
                "description" => "Success"
            ));


            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();


            return $respuesta;
        } catch (Exception $e) {
            //print_r($e);
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }


    /**
     * Verifica la conexión con el proveedor.
     *
     * @param mixed $param Parámetro de prueba.
     *
     * @return string Respuesta en formato JSON con los datos de la verificación.
     * @throws Exception Si el hash es inválido.
     */
    public function Check($param)
    {
        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }


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
     * @return string Respuesta en formato JSON con los datos del error.
     */
    public function convertError($code, $message)
    {
        try {
            $log = "";
            $log = $log . "/" . time();

            $log = $log . "\r\n" . "-------------------------" . "\r\n";
            $log = $log . ($message);

            $fp = fopen('Errorlog_' . date("Y-m-d") . '.log', 'a');
            fwrite($fp, $log);
            fclose($fp);
        } catch (Exception $e) {
        }
        $codeProveedor = "";
        $messageProveedor = "";

        $response = array();


        $Proveedor = new Proveedor("", "AMIGOGAMING");

        switch ($code) {
            case 10011:
                $codeProveedor = "4";
                $messageProveedor = "Player authentication failed due to invalid, not found or expired token.";
                break;

            case 21:
                $codeProveedor = "4";
                $messageProveedor = "Player authentication failed due to invalid, not found or expired token.";
                break;

            case 22:
                $codeProveedor = "4";
                $messageProveedor = "Player authentication failed due to invalid, not found or expired token.";
                break;

            case 20001:
                $codeProveedor = "1";
                $messageProveedor = "Insufficient balance";
                break;

            case 0:
                $codeProveedor = "100";
                $messageProveedor = "Internal server error";
                break;

            case 27:
                $codeProveedor = "8";
                $messageProveedor = "Game is not found or disabled";
                break;

            case 28:
                $codeProveedor = "100";
                $messageProveedor = "ROUND_NOT_FOUND";
                break;

            case 29:
                $codeProveedor = "120";
                $messageProveedor = "Transaction Not Found";
                break;

            case 10001:

                $codeProveedor = 0;
                $messageProveedor = "Success";


                if ($this->token != "") {
                    try {
                        /*  Obtenemos el Usuario Token con el token */
                        $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                        /*  Obtenemos el Usuario Mandante con el Usuario Token */
                        $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                    } catch (Exception $e) {
                        /*  Obtenemos el Usuario Mandante con el Usuario Token */
                        $UsuarioMandante = new UsuarioMandante($this->externalId);
                    }
                } else {
                    /*  Obtenemos el Usuario Mandante con el Usuario Token */
                    $UsuarioMandante = new UsuarioMandante($this->externalId);
                }

                $Game = new Game();

                $responseG = $Game->getBalance($UsuarioMandante);
                switch ($this->method) {
                    case "debit":
                        $TransaccionId = $this->transaccionApi->transaccionId;

                        $productoMandante = new ProductoMandante("", "", $this->transaccionApi->productoId);
                        $Producto = new Producto($productoMandante->productoId);
                        $SubProveedor = new Subproveedor($Producto->getSubproveedorId());
                        $transaction = $TransaccionId . '_' . $SubProveedor->getSubproveedorId();


                        $TransjuegoLog = new TransjuegoLog("", "", "", $transaction, $SubProveedor->getSubproveedorId(), "");
                        $TransaccionJuego = new TransaccionJuego($TransjuegoLog->getTransjuegoId());
                        $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                        $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

                        /*  Retornamos el mensaje satisfactorio  */
                        $response = array(
                            "transactionId" => $TransjuegoLog->getTransjuegologId(),
                            "currency" => $UsuarioMandante->moneda,
                            "cash" => $saldo,
                            "bonus" => 0,
                            "usedPromo" => 0,
                            "error" => 0,
                            "description" => "Success"
                        );
                        break;
                    case "credit":

                        $TransaccionId = $this->transaccionApi->transaccionId;

                        $productoMandante = new ProductoMandante("", "", $this->transaccionApi->productoId);
                        $Producto = new Producto($productoMandante->productoId);
                        $SubProveedor = new Subproveedor($Producto->getSubproveedorId());
                        $transaction = $TransaccionId . '_' . $SubProveedor->getSubproveedorId();


                        $TransjuegoLog = new TransjuegoLog("", "", "", $transaction, $SubProveedor->getSubproveedorId(), "");
                        $TransaccionJuego = new TransaccionJuego($TransjuegoLog->getTransjuegoId());
                        $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                        $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

                        /*  Retornamos el mensaje satisfactorio  */
                        $response = array(
                            "transactionId" => $TransjuegoLog->getTransjuegologId(),
                            "currency" => $UsuarioMandante->moneda,
                            "cash" => $saldo,
                            "bonus" => 0,
                            "error" => 0,
                            "description" => "Success"
                        );
                        break;

                    case "ROLLBACK":

                        $TransaccionId = $this->transaccionApi->transaccionId;

                        $productoMandante = new ProductoMandante("", "", $this->transaccionApi->productoId);
                        $Producto = new Producto($productoMandante->productoId);
                        $SubProveedor = new Subproveedor($Producto->getSubproveedorId());
                        $transaction = $TransaccionId . '_' . $SubProveedor->getSubproveedorId();


                        $TransjuegoLog = new TransjuegoLog("", "", "", $transaction, $SubProveedor->getSubproveedorId(), "");
                        $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

                        /*  Retornamos el mensaje satisfactorio  */
                        $response = array(
                            "transactionId" => $TransjuegoLog->getTransjuegologId(),
                            "error" => 0,
                            "description" => "Success"
                        );
                        break;
                }
                break;

            case 10004:
                $codeProveedor = "ERROR";
                $messageProveedor = "General Error. (" . $code . ")";
                $codeProveedor = "ERROR";
                $messageProveedor = "Apuesta con cancelacion antes.";
                break;

            case 10005:
                $codeProveedor = "0";
                $messageProveedor = "Bet Transaction not found";
                break;

            case 10014:
                $codeProveedor = "100";
                $messageProveedor = "General Error. (" . $code . ")";
                break;


            case 10010:
                $codeProveedor = "100";
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 20002:
                $codeProveedor = "5";
                $messageProveedor = "Invalid hash code";
                break;

            default:

                $codeProveedor = "120";
                $messageProveedor = "Internal server error";
                break;
        }


        if ($codeProveedor != "") {
            $respuesta = json_encode(array_merge($response, array(
                "error" => $codeProveedor,
                "description" => $messageProveedor
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