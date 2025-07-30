<?php

/**
 * Clase Skywind para la integración con el proveedor de juegos SKYWIND.
 *
 * Esta clase contiene métodos para manejar transacciones de juegos, autenticación,
 * balance, débitos, créditos, y otras operaciones relacionadas con el proveedor SKYWIND.
 *
 * @category Integración
 * @package  API\Casino\Skywind
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
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
use Backend\dto\ConfigurationEnvironment;

/**
 * Clase principal para la integración con el proveedor de juegos SKYWIND.
 *
 * Esta clase contiene métodos para manejar transacciones, autenticación,
 * balance, débitos, créditos, y otras operaciones relacionadas con SKYWIND.
 */
class Skywind
{
    /**
     * Token de autenticación.
     *
     * @var string
     */
    private $token;

    /**
     * ID externo del usuario.
     *
     * @var string
     */
    private $externalId;

    /**
     * Objeto de transacción API.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * Datos adicionales para las operaciones.
     *
     * @var mixed
     */
    private $data;

    /**
     * ID del comerciante.
     *
     * @var string
     */
    private $merch_id = "";

    /**
     * Contraseña del comerciante.
     *
     * @var string
     */
    private $merch_pwd = "";

    /**
     * Respuesta generada por las operaciones.
     *
     * @var string
     */
    private $respuesta = "";

    /**
     * Constructor de la clase Skywind.
     *
     * @param string $token     Token de autenticación.
     * @param string $external  ID externo del usuario.
     * @param string $merch_id  ID del comerciante.
     * @param string $merch_pwd Contraseña del comerciante.
     */
    public function __construct($token = "", $external = "", $merch_id = "", $merch_pwd = "")
    {
        $this->token = $token;
        $this->externalId = $external;
        $this->merch_id = $merch_id;
        $this->merch_pwd = $merch_pwd;

        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            if ($this->merch_id !== "swdorabetcwstg" || $this->merch_id === "" || $this->merch_id === null) {
                try {
                    throw new Exception("Validation error", "30022");
                } catch (Exception $e) {
                    $this->respuesta = $this->convertError($e->getCode(), $e->getMessage());
                }
            }

            if (($this->merch_id === "swdorabetcwstg" && $this->merch_pwd !== "glyblHkqa8bgwqgw") || $this->token == null) {
                try {
                    throw new Exception("Validation error", "30022");
                } catch (Exception $e) {
                    $this->respuesta = $this->convertError($e->getCode(), $e->getMessage());
                }
            }
        } else {
            if ($this->merch_id !== "swdorabetcwprod") {
                try {
                    throw new Exception("Validation error", "30022");
                } catch (Exception $e) {
                    $this->respuesta = $this->convertError($e->getCode(), $e->getMessage());
                }
            }


            if (($this->merch_id === "swdorabetcwprod" && $this->merch_pwd !== "S6b269SQsl0fXnUj") || $this->token == null) {
                try {
                    throw new Exception("Validation error", "30022");
                } catch (Exception $e) {
                    $this->respuesta = $this->convertError($e->getCode(), $e->getMessage());
                }
            }
        }
    }

    /**
     * Obtiene la respuesta generada por las operaciones.
     *
     * @return string Respuesta generada.
     */
    public function getRespuesta()
    {
        return $this->respuesta;
    }

    /**
     * Crea un ticket para el usuario.
     *
     * @param string $merch_id ID del comerciante.
     *
     * @return string JSON con el ticket generado.
     */
    public function CreateTicket($merch_id)
    {
        try {
            $Proveedor = new Proveedor("", "SKYWIND");

            try {
                $UsuarioMandante = new UsuarioMandante($this->externalId); //UsuarioMandante con Id -> cust_id
                $usumandanteId = $UsuarioMandante->usumandanteId;
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId);
                $token = $UsuarioToken->createToken();
                $UsuarioToken->setToken($token);

                $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
                $UsuarioTokenMySqlDAO->update($UsuarioToken);
                $UsuarioTokenMySqlDAO->getTransaction()->commit();
            } catch (Exception $e) {
                if ($e->getCode() == 22) { //Si no existe el UsuarioMandante con Id ->

                    $Usuario = new Usuario($merch_id);

                    $UsuarioMandante = new UsuarioMandante();

                    $UsuarioMandante->mandante = $Usuario->mandante;
                    //$UsuarioMandante->dirIp = $dir_ip;
                    $UsuarioMandante->nombres = $Usuario->nombre;
                    $UsuarioMandante->apellidos = '';
                    $UsuarioMandante->estado = 'A';
                    $UsuarioMandante->email = $Usuario->login;
                    $UsuarioMandante->moneda = $Usuario->moneda;
                    $UsuarioMandante->paisId = $Usuario->paisId;
                    $UsuarioMandante->saldo = 0;
                    $UsuarioMandante->usuarioMandante = $Usuario->usuarioId;
                    $UsuarioMandante->usucreaId = 0;
                    $UsuarioMandante->usumodifId = 0;

                    $UsuarioMandanteMySqlDAO = new UsuarioMandanteMySqlDAO();
                    $usuario_id = $UsuarioMandanteMySqlDAO->insert($UsuarioMandante);

                    $UsuarioMandanteMySqlDAO->getTransaction()->getConnection()->commit();

                    $UsuarioToken = new UsuarioToken();
                    $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
                    $UsuarioToken->setCookie('0');
                    $UsuarioToken->setRequestId('0');
                    $UsuarioToken->setUsucreaId(0);
                    $UsuarioToken->setUsumodifId(0);
                    $UsuarioToken->setUsuarioId($usuario_id);
                    $token = $UsuarioToken->createToken();
                    $UsuarioToken->setToken($token);
                    $UsuarioToken->setSaldo(0);

                    $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                    $UsuarioTokenMySqlDAO->insert($UsuarioToken);

                    $UsuarioTokenMySqlDAO->getTransaction()->commit();
                } else {
                    throw $e;
                }
            }

            $return = array(
                "ticket" => $UsuarioToken->getToken()
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Autentica al usuario con el proveedor SKYWIND.
     *
     * @param string $merch_id  ID del comerciante.
     * @param string $merch_pwd Contraseña del comerciante.
     * @param string $ip        Dirección IP del usuario.
     *
     * @return string JSON con los datos de autenticación.
     */
    public function Auth($merch_id = "", $merch_pwd = "", $ip = "")
    {
        $this->method = 'authenticate';
        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            $Proveedor = new Proveedor("", "SKYWIND");

            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            if ($Usuario->test == "N") {
                $test_cust = false;
            } elseif ($Usuario->test == "S") {
                $test_cust = true;
            }

            $Game = new Game();

            $responseG = $Game->autenticate($UsuarioMandante);
            $return = array(

                "error_code" => 0,
                "cust_session_id" => $this->token, //Revisar esta linea ya que se debe crear un token.
                "cust_id" => 'Usuario' . $responseG->usuarioId, //OK
                "currency_code" => $responseG->moneda, //OK
                "test_cust" => $test_cust, //OK
                "country" => $responseG->paisIso2, //OK

            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del usuario.
     *
     * @param string $playerId  ID del jugador.
     * @param string $merch_id  ID del comerciante.
     * @param string $merch_pwd Contraseña del comerciante.
     *
     * @return string JSON con el balance del usuario.
     */
    public function getBalance($playerId, $merch_id, $merch_pwd)
    {
        $this->externalId = $playerId;

        $this->method = 'balance';

        try {
            if ($playerId == "") {
                throw new Exception("Token vacio", "10011");
            }

            $Proveedor = new Proveedor("", "SKYWIND");


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
                "error_code" => 0,
                "balance" => floatval($saldo),
                "currency_code" => $responseG->moneda
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un débito en la cuenta del usuario.
     *
     * @param string $gameId        ID del juego.
     * @param float  $debitAmount   Monto a debitar.
     * @param string $roundId       ID de la ronda.
     * @param string $transactionId ID de la transacción.
     * @param array  $datos         Datos adicionales.
     * @param string $event_id      ID del evento.
     * @param string $trx_id        ID de la transacción externa.
     * @param string $merch_id      ID del comerciante.
     * @param string $merch_pwd     Contraseña del comerciante.
     *
     * @return string JSON con el resultado del débito.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $event_id, $trx_id, $merch_id, $merch_pwd)
    {
        $this->method = 'reserve';

        $this->data = $datos;

        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado SKYWIND */
            $Proveedor = new Proveedor("", "SKYWIND");

            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }


            /*  Obtenemos el producto con el gameId  */
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);
            $this->transaccionApi->setIdentificador("SKYWIND" . $roundId);

            $Game = new Game();


            $isfreeSpin = false;
            if (floatval($debitAmount) == 0) {
                $isfreeSpin = true;
            }
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $isfreeSpin);

            $this->transaccionApi = $responseG->transaccionApi;

            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

            $respuesta = json_encode(array(
                "error_code" => 0,
                "balance" => floatval($saldo),
                "trx_id" => $trx_id
            ));


            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();


            return $respuesta;
        } catch (Exception $e) {
            if ($_ENV['debug']) {
                print_r($e);
            }
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un rollback de una transacción.
     *
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $roundId        ID de la ronda.
     * @param string $transactionId  ID de la transacción.
     * @param string $player         ID del jugador.
     * @param array  $datos          Datos adicionales.
     * @param string $event_id       ID del evento.
     * @param string $trx_id         ID de la transacción externa.
     * @param string $merch_id       ID del comerciante.
     * @param string $merch_pwd      Contraseña del comerciante.
     *
     * @return string JSON con el resultado del rollback.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos, $event_id, $trx_id, $merch_id, $merch_pwd)
    {
        $this->method = 'cancelReserve';

        $this->data = $datos;

        try {
            /*  Obtenemos el Proveedor con el abreviado SKYWIND */
            $Proveedor = new Proveedor("", "SKYWIND");

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

            $transaccionNoExiste = false;

            try {
                $TransaccionApi2 = new TransaccionApi("", $transactionId, $Proveedor->getProveedorId());
                $jsonValue = json_decode($TransaccionApi2->getTValue());
                $valorTransaction = 0;

                $UsuarioMandante = new UsuarioMandante($TransaccionApi2->getUsuarioId());
                if (strpos($TransaccionApi2->getTipo(), 'DEBIT') !== false) {
                    $this->transaccionApi->setIdentificador($TransaccionApi2->getIdentificador());
                } else {
                    throw new Exception("Transaccion no es Debit", "10006");
                }
            } catch (Exception $e) {
                $transaccionNoExiste = true;
                throw new Exception("Transaccion no existe", "10005");
            }

            $Game = new Game();

            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi);

            $this->transaccionApi = $responseG->transaccionApi;
            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

            $respuesta = json_encode(array(
                "error_code" => 0,
                "balance" => floatval($saldo),
                "trx_id" => $trx_id
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
     * @param string  $gameId        ID del juego.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param array   $datos         Datos adicionales.
     * @param boolean $isBonus       Indica si es un bono.
     * @param string  $event_id      ID del evento.
     * @param string  $trx_id        ID de la transacción externa.
     * @param string  $merch_id      ID del comerciante.
     * @param string  $merch_pwd     Contraseña del comerciante.
     *
     * @return string JSON con el resultado del crédito.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos, $isBonus = false, $event_id, $trx_id, $merch_id, $merch_pwd)
    {
        if ($creditAmount < 0) {
            try {
                throw new Exception("Negative win", "10002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }


        $this->method = 'release';

        $this->data = $datos;

        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }
            /*  Obtenemos el Proveedor con el abreviado SKYWIND */
            $Proveedor = new Proveedor("", "SKYWIND");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);
            $this->transaccionApi->setIdentificador("SKYWIND" . $roundId);


            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            /*  Obtenemos el producto con el gameId  */
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            $Game = new Game();

            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, true, false, $isBonus);

            $this->transaccionApi = $responseG->transaccionApi;


            /*  Retornamos el mensaje satisfactorio  */
            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

            $respuesta = json_encode(array(
                "error_code" => 0,
                "balance" => floatval($saldo),
                "trx_id" => $trx_id
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
     * Finaliza una ronda de juego.
     *
     * @param string $gameId  ID del juego.
     * @param string $roundId ID de la ronda.
     * @param array  $datos   Datos adicionales.
     * @param string $trx_id  ID de la transacción externa.
     *
     * @return string JSON con el resultado de la operación.
     */
    public function EndRound($gameId, $roundId, $datos, $trx_id)
    {
        $this->method = 'release';

        $this->data = $datos;

        try {
            /*  Obtenemos el Proveedor con el abreviado SKYWIND */
            $Proveedor = new Proveedor("", "SKYWIND");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId(0);
            $this->transaccionApi->setTipo("ENDROUND");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);
            $this->transaccionApi->setIdentificador("SKYWIND" . $roundId);


            $Game = new Game();

            $responseG = $Game->endRound($this->transaccionApi);

            $this->transaccionApi = $responseG->transaccionApi;


            /*  Retornamos el mensaje satisfactorio  */

            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

            $respuesta = json_encode(array(
                "error_code" => 0,
                "balance" => floatval($saldo),
                "trx_id" => $trx_id
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
     * Convierte un error en un formato JSON entendible por el proveedor.
     *
     * @param integer $code    Código del error.
     * @param string  $message Mensaje del error.
     *
     * @return string JSON con el error convertido.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $response = array();
        $Proveedor = new Proveedor("", "SKYWIND");


        switch ($code) {
            case 10001:

                $codeProveedor = 1; //OK
                $messageProveedor = "Duplicate transaction";

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

                $TransaccionApi = new TransaccionApi("", $this->transaccionApi->getTransaccionId(), $Proveedor->getProveedorId());
                $trx_id = explode("_", $TransaccionApi->getTransaccionId());
                $trx_id_old = $trx_id[1];


                $responseG = $Game->getBalance($UsuarioMandante);

                $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));


                $response = array(
                    "balance" => floatval($saldo),
                    "currency_code" => $responseG->moneda,
                    "trx_id" => $trx_id_old
                );

                break;

            case 21:
                $codeProveedor = -2; //OK
                $messageProveedor = "Player not found";
                break;

            case 22:
                $codeProveedor = -2; //OK
                $messageProveedor = "Player not found";
                break;

            case 10011:
                $codeProveedor = -3; //OK
                $messageProveedor = "Game token expired";
                break;

            case 20003:

                $codeProveedor = -301; //OK
                $messageProveedor = "Player is suspended";
                break;

            case 20024:

                $codeProveedor = -301; //OK
                $messageProveedor = "Player is suspended";
                break;

            case 20001:
                $codeProveedor = -4;//OK
                $messageProveedor = "Insufficient balance";

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

                $response = array(
                    "balance" => floatval($saldo),
                    "currency_code" => $responseG->moneda,
                );
                break;

            case 29:
                $codeProveedor = -7; //OK
                $messageProveedor = "Transaction not found";
                break;

            case 30022:
                $codeProveedor = -1; //OK
                $messageProveedor = "Merchant internal error";
                break;

            case 10005:
                $codeProveedor = -7; //OK
                $messageProveedor = "Merchant internal error";
                break;

            default:
                $codeProveedor = -1;//Ok
                $messageProveedor = "Merchant internal error";
                break;
        }

        if ($codeProveedor != "") {
            $respuesta = json_encode(array_merge($response, array(
                "error_code" => $codeProveedor,
                "error_msg" => $messageProveedor
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
