<?php

/**
 * Clase TvBet para la integración con el proveedor de juegos TVBET.
 *
 * Este archivo contiene la implementación de la clase TvBet, que incluye métodos
 * para manejar transacciones, autenticación, balance, y otras operaciones relacionadas
 * con la integración de juegos. La clase utiliza múltiples dependencias para interactuar
 * con la base de datos y otros servicios.
 *
 * @category Integración
 * @package  Backend\integrations\casino
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */

namespace Backend\integrations\casino;

use Exception;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioToken;
use Backend\dto\SubproveedorMandantePais;
use Backend\dto\TransjuegoLog;
use Backend\dto\PromocionalLog;
use Backend\dto\TransaccionApi;
use Backend\dto\ProductoDetalle;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionApiMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;

/**
 * Clase principal para manejar la integración con TVBET.
 */
class TvBet
{
    /**
     * Identificador del operador.
     *
     * @var mixed
     */
    private $operadorId;

    /**
     * Token de autenticación.
     *
     * @var string
     */
    private $token;

    /**
     * Firma de seguridad.
     *
     * @var string
     */
    private $sign;

    /**
     * Objeto para manejar transacciones API.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * Datos relacionados con la transacción.
     *
     * @var array
     */
    private $data;

    /**
     * Tipo de transacción.
     *
     * @var string
     */
    private $tipoTransaccion;

    /**
     * Tipo de operación.
     *
     * @var string
     */
    private $tipo;

    /**
     * Identificador de la ronda superior.
     *
     * @var string
     */
    private $roundIdSuper;

    /**
     * Clave secreta para operaciones.
     *
     * @var string
     */
    private $secret_key = "";

    /**
     * Clave secreta para el entorno de desarrollo.
     *
     * @var string
     */
    private $secret_keyDEV = "7bde3eff4b7d72396914a3441d3ad90bd987d88cbb4626ceb6bb99a61bab5055";

    /**
     * Clave secreta para el entorno local de desarrollo.
     *
     * @var string
     */
    private $secret_keyLcDev = "F+)NB1qc181]qo9R";

    /**
     * Clave secreta para el entorno de producción.
     *
     * @var string
     */
    private $secret_keyPROD = "";

    /**
     * Constructor de la clase TvBet.
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
     * @return mixed
     */
    public function getOperadorId()
    {
        return $this->operadorId;
    }

    /**
     * Método para autenticar al usuario.
     *
     * Este método realiza la autenticación del usuario utilizando el token proporcionado
     * y genera una respuesta con los datos del usuario autenticado.
     *
     * @return string Respuesta en formato JSON.
     */
    public function Auth()
    {
        try {
            $Proveedor = new Proveedor("", "TVBET");

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

            try {
                $ProductoDetalle = new ProductoDetalle('', $UsuarioToken->productoId, 'GAMEID');
                $prod = $ProductoDetalle->pValue;
                $Jacktop = strtoupper($prod);
            } catch (Exception $e) {
                $Jacktop = "0";
            }

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $Subproveedor = new Subproveedor("", "TVBET");

            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante, $Subproveedor);

            $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
            if ($Usuario->test == "S") {
                $ts = true;
            } else {
                $ts = false;
            }
            $data = array(
                "ti" => time(),
                "sc" => true,
                "cd" => 0,
                "er" => "",
                "val" => array(
                    "uid" => $UsuarioMandante->usumandanteId,
                    "cc" => $UsuarioMandante->moneda,
                    "to" => $UsuarioToken->getToken(),
                    "ts" => $ts, // true= prueba  false= real
                    "bl" => strval(round($responseG->saldo, 2))
                )
            );

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            $sign = $this->getSignature($data, $UsuarioMandante->mandante, $UsuarioMandante->paisId, $Jacktop);

            $return = array(
                "ti" => time(),
                "si" => $sign, //encrytación md5 y hash base64
                "sc" => true,
                "cd" => 0,
                "er" => "",
                "val" => array(
                    "uid" => $UsuarioMandante->usumandanteId,
                    "cc" => $UsuarioMandante->moneda,
                    "to" => $UsuarioToken->getToken(),
                    "ts" => $ts, // true= prueba  false= real
                    "bl" => strval(round($responseG->saldo, 2))
                )
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Genera una firma de seguridad para los datos proporcionados.
     *
     * @param array  $data     Datos a firmar.
     * @param string $Mandante Identificador del mandante.
     * @param string $Pais     Identificador del país.
     * @param string $Jacktop  Indicador de jackpot.
     *
     * @return string Firma generada.
     */
    function getSignature($data, $Mandante, $Pais, $Jacktop = '0')
    {
        $Subproveedor = new Subproveedor("", "TVBET");

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $Mandante, $Pais);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $SECRET_KEY = $credentials->SECRET_KEY;
        $SECRET_KEY_LIVE = $credentials->SECRET_KEY_LIVE;

        if ($Jacktop == 'JACKTOP') {
            $SECRET_KEY_FINAL = $SECRET_KEY_LIVE;
        } else {
            $SECRET_KEY_FINAL = $SECRET_KEY;
        }

        $data = json_encode($data);

        $stringData = ($data . $SECRET_KEY_FINAL);

        $stringData = md5($stringData);

        $sign = base64_encode(hex2bin($stringData));
        return $sign;
    }

    /**
     * Obtiene el balance del usuario.
     *
     * Este método consulta el balance del usuario autenticado y devuelve la información
     * en formato JSON.
     *
     * @return string Respuesta en formato JSON.
     */
    public function getBalance()
    {
        try {
            $Proveedor = new Proveedor("", "TVBET");

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

            try {
                $ProductoDetalle = new ProductoDetalle('', $UsuarioToken->productoId, 'GAMEID');
                $prod = $ProductoDetalle->pValue;
                $Jacktop = strtoupper($prod);
            } catch (Exception $e) {
                $Jacktop = "0";
            }

            $data = array(
                "ti" => time(),
                "sc" => true,
                "cd" => 0,
                "er" => "",
                "val" => array(
                    "to" => $UsuarioToken->getToken()
                )
            );

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            $sign = $this->getSignature($data, $UsuarioMandante->mandante, $UsuarioMandante->paisId, $Jacktop);


            $return = array(
                "ti" => time(),
                "si" => $sign, //encrytación md5 y hash base64
                "sc" => true,
                "cd" => 0,
                "er" => "",
                "val" => array(
                    "to" => $UsuarioToken->getToken()
                )
            );


            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Refresca el token de autenticación.
     *
     * Este método genera un nuevo token para el usuario autenticado.
     *
     * @return string Respuesta en formato JSON.
     */
    public function RefreshToken()
    {
        try {
            $Proveedor = new Proveedor("", "TVBET");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);

            if ($this->token != "") {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
            } else {
                throw new Exception("Token no existe", 21);
            }

            try {
                $ProductoDetalle = new ProductoDetalle('', $UsuarioToken->productoId, 'GAMEID');
                $prod = $ProductoDetalle->pValue;
                $Jacktop = strtoupper($prod);
            } catch (Exception $e) {
                $Jacktop = "0";
            }

            try {
                $UsuarioToken->setToken($UsuarioToken->createToken());
                $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
                $UsuarioToken->setProductoId($UsuarioToken->productoId);
                $UsuarioToken->setEstado('A');
                $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
                $UsuarioTokenMySqlDAO->insert($UsuarioToken);
                $UsuarioTokenMySqlDAO->getTransaction()->commit();
            } catch (Exception $e) {
                throw new Exception("Token no existe", 21);
            }

            $data = array(
                "ti" => time(),
                "sc" => true,
                "cd" => 0,
                "er" => "",
                "val" => array(
                    "to" => $UsuarioToken->getToken()
                )
            );

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            $sign = $this->getSignature($data, $UsuarioMandante->mandante, $UsuarioMandante->paisId, $Jacktop);

            $return = array(
                "ti" => time(),
                "si" => $sign, //encrytación md5 y hash base64
                "sc" => true,
                "cd" => 0,
                "er" => "",
                "val" => array(
                    "to" => $UsuarioToken->getToken()
                )
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
     * @return string Respuesta en formato JSON.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $freespin)
    {
        $this->tipo = "DEBIT";
        $this->data = $datos;

        $tt = explode("_", $transactionId);
        $this->tipoTransaccion = $tt[1];

        try {
            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            //Obtenemos el Proveedor con el abreviado TVBET
            $Proveedor = new Proveedor("", "TVBET");

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

            try {
                $ProductoDetalle = new ProductoDetalle('', $UsuarioToken->productoId, 'GAMEID');
                $prod = $ProductoDetalle->pValue;
                $Jacktop = strtoupper($prod);
            } catch (Exception $e) {
                $Jacktop = "0";
            }

            $this->transaccionApi->setIdentificador($roundId . "TVBET");
            //Obtenemos el producto con el gameId

            $Producto = new Producto($UsuarioToken->productoId, "", $Proveedor->getProveedorId());

            $this->transaccionApi->setTransaccionId($transactionId);
            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin);

            $this->transaccionApi = $responseG->transaccionApi;

            $data = array(
                "ti" => time(),
                "sc" => true,
                "cd" => 0,
                "er" => "",
                "val" => array(
                    "tid" => $responseG->transaccionId, // ID de la transacción
                    "dt" => time() //tiempo de transacción fecha crea
                )
            );

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            $sign = $this->getSignature($data, $UsuarioMandante->mandante, $UsuarioMandante->paisId, $Jacktop);

            $return = array(
                "ti" => time(),
                "si" => $sign, //encrytación md5 y hash base64
                "sc" => true,
                "cd" => 0,
                "er" => "",
                "val" => array(
                    "tid" => $responseG->transaccionId, // ID de la transacción
                    "dt" => time()
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
     * Realiza una operación de rollback.
     *
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $roundId        ID de la ronda.
     * @param string $transactionId  ID de la transacción.
     * @param string $player         ID del jugador.
     * @param array  $datos          Datos adicionales.
     *
     * @return string Respuesta en formato JSON.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos)
    {
        $this->tipo = "ROLLBACK";
        $usuarioid = $player;
        $tt = explode("_", $transactionId);
        $this->tipoTransaccion = $tt[1];
        $this->data = $datos;


        try {
            //Obtenemos el Proveedor con el abreviado TVBET
            $Proveedor = new Proveedor("", "TVBET");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);

            try {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $ProductoDetalle = new ProductoDetalle('', $UsuarioToken->productoId, 'GAMEID');
                $prod = $ProductoDetalle->pValue;
                $Jacktop = strtoupper($prod);
            } catch (Exception $e) {
                $Jacktop = "0";
            }

            try {
                $transactionId = explode('_', $transactionId);
                $transactionId = $transactionId[0];
                $transactionId = $transactionId . "_-1";
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
            $allowChangIfIsEnd = true;
            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false, $transactionId, $allowChangIfIsEnd);

            $this->transaccionApi = $responseG->transaccionApi;
            $transactionId = explode('_', $transactionId);
            $transactionId = $transactionId[0];
            $transactionId = $transactionId . "_2";
            //$this->transaccionApi->setTransaccionId("ROLLBACK".$transactionId);

            $data = array(
                "ti" => time(),
                "sc" => true,
                "cd" => 0,
                "er" => "",
                "val" => array(
                    "tid" => $responseG->transaccionId, // ID de la transacción
                    "dt" => time() //tiempo de transacción fecha crea
                )
            );

            $sign = $this->getSignature($data, $UsuarioMandante->mandante, $UsuarioMandante->paisId, $Jacktop);

            $return = array(
                "ti" => time(),
                "si" => $sign, //encrytación md5 y hash base64
                "sc" => true,
                "cd" => 0,
                "er" => "",
                "val" => array(
                    "tid" => $responseG->transaccionId, // ID de la transacción
                    "dt" => time()
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
     * Realiza una operación de crédito.
     *
     * @param string $gameId        ID del juego.
     * @param float  $creditAmount  Monto a acreditar.
     * @param string $roundId       ID de la ronda.
     * @param string $transactionId ID de la transacción.
     * @param array  $datos         Datos adicionales.
     *
     * @return string Respuesta en formato JSON.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos)
    {
        $this->tipo = "CREDIT";
        $this->data = $datos;
        $tt = explode("_", $transactionId);
        $this->tipoTransaccion = $tt[1];
        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try {
            //Obtenemos el Proveedor con el abreviado TVBET
            $Proveedor = new Proveedor("", "TVBET");
            $Subproveedor = new Subproveedor("", "TVBET");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);


            try { //Obtenemos el Usuario Token con el token
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

                //Obtenemos el Usuario Mandante con el Usuario Token
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } catch (Exception $e) {
                if ($e->getCode() == 21) {
                    $TransaccionJuego = new TransaccionJuego("", $roundId . "TVBET", $Proveedor->getProveedorId());

                    $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                }
            }

            try {
                $ProductoDetalle = new ProductoDetalle('', $UsuarioToken->productoId, 'GAMEID');
                $prod = $ProductoDetalle->pValue;
                $Jacktop = strtoupper($prod);
            } catch (Exception $e) {
                $Jacktop = "0";
            }

            $this->transaccionApi->setIdentificador($roundId . "TVBET");


            //Obtenemos el producto con el gameId
            $Producto = new Producto($UsuarioToken->productoId, "", $Proveedor->getProveedorId());


            //Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            //Agregamos Elementos a la Transaccion API
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());

            if ($this->transaccionApi->valor == 0) {
                $allowChangIfIsEnd = true;
            } else {
                $allowChangIfIsEnd = true;
            }
            $Game = new Game();

            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, true, false, false, $allowChangIfIsEnd);

            $this->transaccionApi = $responseG->transaccionApi;


            $data = array(
                "ti" => time(),
                "sc" => true,
                "cd" => 0,
                "er" => "",
                "val" => array(
                    "tid" => $responseG->transaccionId, // ID de la transacción
                    "dt" => time() //tiempo de transacción fecha crea
                )
            );

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            $sign = $this->getSignature($data, $UsuarioMandante->mandante, $UsuarioMandante->paisId, $Jacktop);

            $return = array(
                "ti" => time(),
                "si" => $sign, //encrytación md5 y hash base64
                "sc" => true,
                "cd" => 0,
                "er" => "",
                "val" => array(
                    "tid" => $responseG->transaccionId, // ID de la transacción
                    "dt" => time()
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
     * Obtiene información de pago de una transacción.
     *
     * @param string $transactionId ID de la transacción.
     *
     * @return string Respuesta en formato JSON.
     */
    public function GetPaymentInfo($transactionId)
    {
        try {
            $Proveedor = new Proveedor("", "TVBET");
            $Subproveedor = new Subproveedor("", "TVBET");

            $TransJuegoLog = new TransjuegoLog($transactionId);
            $TransaccionJuego = new TransaccionJuego($TransJuegoLog->getTransjuegoId());

            try {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $ProductoDetalle = new ProductoDetalle('', $UsuarioToken->productoId, 'GAMEID');
                $prod = $ProductoDetalle->pValue;
                $Jacktop = strtoupper($prod);
            } catch (Exception $e) {
                $Jacktop = "0";
            }

            $tt = $TransJuegoLog->transaccionId;
            $tt = explode('_', $tt);
            $tt = intval($tt[1]);


            switch ($tt) {
                case -1 :
                    $Transaccion = ($TransJuegoLog->getTransaccionId());
                    $Transaccion = explode('_', $Transaccion);
                    $Transaccion = $Transaccion[0];
                    break;

                case -2 :
                    $Transaccion = ($TransJuegoLog->getTransaccionId());
                    $Transaccion = explode('_', $Transaccion);
                    $Transaccion = $Transaccion[0];
                    break;

                case 1 :
                    $Transaccion = ($TransJuegoLog->getTransaccionId());
                    $Transaccion = explode('_', $Transaccion);
                    $Transaccion = $Transaccion[0];
                    break;

                case 2 :
                    $Transaccion = ($TransJuegoLog->getTransaccionId());
                    $Transaccion = explode('_', $Transaccion);
                    $Transaccion = $Transaccion[0];
                    break;

                case 4 :
                    $Transaccion = ($TransJuegoLog->getTransaccionId());
                    $Transaccion = explode('_', $Transaccion);
                    $Transaccion = $Transaccion[0];
                    break;
            }

            $data = array(
                "ti" => time(),
                "sc" => true,
                "cd" => 0,
                "er" => "",
                "val" => array(
                    "dt" => time(), //tiempo de transacción
                    "bid" => intval($Transaccion), //Id de apuesta
                    "tt" => $tt, // tipo de transacción
                    "uid" => $TransaccionJuego->getUsuarioId(),
                    "sm" => $TransJuegoLog->getValor(), // monto de la transacción
                )
            );

            $UsuarioMandante = new UsuarioMandante($TransaccionJuego->usuarioId);
            $sign = $this->getSignature($data, $UsuarioMandante->mandante, $UsuarioMandante->paisId, $Jacktop);

            $return = array(
                "ti" => time(),
                "si" => $sign, //encrytación md5 y hash base64
                "sc" => true,
                "cd" => 0,
                "er" => "",
                "val" => array(
                    "dt" => time(), //tiempo de transacción
                    "bid" => intval($Transaccion), //Id de apuesta
                    "tt" => $tt, // tipo de transacción
                    "uid" => $TransaccionJuego->getUsuarioId(),
                    "sm" => $TransJuegoLog->getValor(), // monto de la transacción
                )
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Verifica un parámetro proporcionado.
     *
     * @param mixed $param Parámetro a verificar.
     *
     * @return string Respuesta en formato JSON.
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
     * Este método maneja los errores generados en las operaciones y los convierte
     * en una respuesta JSON con el código y mensaje de error.
     *
     * @param integer $code    Código de error.
     * @param string  $message Mensaje de error.
     *
     * @return string Respuesta en formato JSON.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $response = array();

        $Proveedor = new Proveedor("", "TVBET");
        $Subproveedor = new Subproveedor("", "TVBET");

        try {
            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
            $ProductoDetalle = new ProductoDetalle('', $UsuarioToken->productoId, 'GAMEID');
            $prod = $ProductoDetalle->pValue;
            $Jacktop = strtoupper($prod);
        } catch (Exception $e) {
            $Jacktop = "0";
        }

        switch ($code) {
            case 10011:
                $codeProveedor = 4;
                $messageProveedor = "An interaction token not found.";

                break;

            case 21:
                $codeProveedor = 6;
                $messageProveedor = "Token obsolete.";
                break;

            case 22:
                $codeProveedor = 3;
                $messageProveedor = "User not found.";
                break;

            case 20001:
                $codeProveedor = 8;
                $messageProveedor = "Insufficient funds to make a bet";
                break;

            case 20002:
                $codeProveedor = 1;
                $messageProveedor = "Signature of request is invalid";
                break;

            case 0:
                $codeProveedor = 1000;
                $messageProveedor = "API system error";
                break;

            case 28:
                $codeProveedor = 14;
                $messageProveedor = "Transaction not found";
                break;

            case 29:
                $codeProveedor = 14;
                $messageProveedor = "Transaction not found";
                break;

            case 10001:

                $codeProveedor = 12;
                $messageProveedor = "Transaction already exists";

                switch ($this->tipo) {
                    case "DEBIT":
                        $transaccionApi2 = new TransjuegoLog("", "", "", $this->transaccionApi->transaccionId . "_" . $Subproveedor->getSubproveedorId(), $Subproveedor->getSubproveedorId());
                        $trasaccionId = $transaccionApi2->transjuegologId;

                        $data = array(
                            "ti" => time(),
                            "sc" => false,
                            "cd" => $codeProveedor,
                            "er" => $messageProveedor,
                            "val" => array(
                                "tid" => $trasaccionId, // ID de la transacción

                            )
                        );

                        $TransaccionJuego = new TransaccionJuego($transaccionApi2->getTransjuegoId());
                        $UsuarioMandante = new UsuarioMandante($TransaccionJuego->usuarioId);
                        $sign = $this->getSignature($data, $UsuarioMandante->mandante, $UsuarioMandante->paisId, $Jacktop);

                        $response = array(
                            "ti" => time(),
                            "si" => $sign, //encrytación md5 y hash base64
                            "sc" => false,
                            "cd" => $codeProveedor,
                            "er" => $messageProveedor,
                            "val" => array(
                                "tid" => $trasaccionId // ID de la transacción
                            )
                        );
                        break;
                    case "CREDIT":

                        $transaccionApi2 = new TransjuegoLog("", "", "", $this->transaccionApi->transaccionId . "_" . $Subproveedor->getSubproveedorId(), $Subproveedor->getSubproveedorId());
                        $trasaccionId = $transaccionApi2->transjuegologId;

                        $data = array(
                            "ti" => time(),
                            "sc" => false,
                            "cd" => $codeProveedor,
                            "er" => $messageProveedor,
                            "val" => array(
                                "tid" => $trasaccionId, // ID de la transacción

                            )
                        );

                        $TransaccionJuego = new TransaccionJuego($transaccionApi2->getTransjuegoId());
                        $UsuarioMandante = new UsuarioMandante($TransaccionJuego->usuarioId);
                        $sign = $this->getSignature($data, $UsuarioMandante->mandante, $UsuarioMandante->paisId, $Jacktop);

                        $response = array(
                            "ti" => time(),
                            "si" => $sign, //encrytación md5 y hash base64
                            "sc" => false,
                            "cd" => $codeProveedor,
                            "er" => $messageProveedor,
                            "val" => array(
                                "tid" => $trasaccionId // ID de la transacción
                            )
                        );
                        break;
                    case "ROLLBACK":
                        $transaccionApi2 = new TransjuegoLog("", "", "", $this->transaccionApi->transaccionId . "_" . $Subproveedor->getSubproveedorId(), $Subproveedor->getSubproveedorId());
                        $trasaccionId = $transaccionApi2->transjuegologId;

                        $data = array(
                            "ti" => time(),
                            "sc" => false,
                            "cd" => $codeProveedor,
                            "er" => $messageProveedor,
                            "val" => array(
                                "tid" => $trasaccionId, // ID de la transacción

                            )
                        );

                        $TransaccionJuego = new TransaccionJuego($transaccionApi2->getTransjuegoId());
                        $UsuarioMandante = new UsuarioMandante($TransaccionJuego->usuarioId);
                        $sign = $this->getSignature($data, $UsuarioMandante->mandante, $UsuarioMandante->paisId, $Jacktop);

                        $response = array(
                            "ti" => time(),
                            "si" => $sign, //encrytación md5 y hash base64
                            "sc" => false,
                            "cd" => $codeProveedor,
                            "er" => $messageProveedor,
                            "val" => array(
                                "tid" => $trasaccionId // ID de la transacción
                            )
                        );
                        break;
                }


                break;


            case 10010:
                $codeProveedor = 12;
                $messageProveedor = "Transaction already exists";

                $transaccionApi2 = new TransjuegoLog("", "", "", $this->transaccionApi->getTransaccionId() . "_" . $Subproveedor->getSubproveedorId(), $Subproveedor->getSubproveedorId());
                $trasaccionId = $transaccionApi2->transjuegologId;
                $data = array(
                    "ti" => time(),
                    "sc" => false,
                    "cd" => $codeProveedor,
                    "er" => $messageProveedor,
                    "val" => array(
                        "tid" => $trasaccionId // ID de la transacción
                        //tiempo de transacción
                    )
                );

                $TransaccionJuego = new TransaccionJuego($transaccionApi2->getTransjuegoId());
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->usuarioId);
                $sign = $this->getSignature($data, $UsuarioMandante->mandante, $UsuarioMandante->paisId, $Jacktop);

                $response = array(
                    "ti" => time(),
                    "si" => $sign, //encrytación md5 y hafsh base64
                    "sc" => false,
                    "cd" => $codeProveedor,
                    "er" => $messageProveedor,
                    "val" => array(
                        "tid" => $trasaccionId // ID de la transacción
                    )
                );
                break;

            case 10005:
                $codeProveedor = 14;
                $messageProveedor = "Transaction not found";

                break;
            case 10015:
                $codeProveedor = 16;
                $messageProveedor = "The bet has already been canceled";
                break;
            case 10027:
                $codeProveedor = 12;
                $messageProveedor = "Transaction already exists";


                $transaction = explode('_', $this->transaccionApi->getTransaccionId());
                $transaction = $transaction[0];
                $transaction = $transaction . "_" . $this->tipoTransaccion;

                $transaccionApi2 = new TransjuegoLog("", "", "", $transaction . "_" . $Subproveedor->getSubproveedorId(), $Subproveedor->getSubproveedorId());

                $trasaccionId = $transaccionApi2->transjuegologId;
                $data = array(
                    "ti" => time(),
                    "sc" => false,
                    "cd" => $codeProveedor,
                    "er" => $messageProveedor,
                    "val" => array(
                        "tid" => $trasaccionId // ID de la transacción
                        //tiempo de transacción
                    )
                );

                $TransaccionJuego = new TransaccionJuego($transaccionApi2->getTransjuegoId());
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->usuarioId);
                $sign = $this->getSignature($data, $UsuarioMandante->mandante, $UsuarioMandante->paisId, $Jacktop);

                $response = array(
                    "ti" => time(),
                    "si" => $sign, //encrytación md5 y hash base64
                    "sc" => false,
                    "cd" => $codeProveedor,
                    "er" => $messageProveedor,
                    "val" => array(
                        "tid" => $trasaccionId // ID de la transacción
                    )
                );
                break;

            default:
                $transaction = explode('_', $this->transaccionApi->getTransaccionId());
                $transaction = $transaction[0];
                $transaction = $transaction . "_" . $this->tipoTransaccion;

                $transaccionApi2 = new TransjuegoLog("", "", "", $transaction . "_" . $Subproveedor->getSubproveedorId(), $Subproveedor->getSubproveedorId());

                $codeProveedor = 1000;
                $messageProveedor = "API system error";
                $data = array(
                    "ti" => time(),
                    "sc" => false,
                    "cd" => $codeProveedor,
                    "er" => $messageProveedor,
                    "val" => null
                );
                $TransaccionJuego = new TransaccionJuego($transaccionApi2->getTransjuegoId());
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->usuarioId);
                $sign = $this->getSignature($data, $UsuarioMandante->mandante, $UsuarioMandante->paisId, $Jacktop);

                $response = array(
                    "ti" => time(),
                    "si" => $sign, //encrytación md5 y hash base64
                    "sc" => false,
                    "cd" => $codeProveedor,
                    "er" => $messageProveedor,
                    "val" => null


                );

                break;
        }

        if ($code != 10001 && $code != 10010 && $code != 10027) {
            try {
                $transaction = explode('_', $this->transaccionApi->getTransaccionId());
                $transaction = $transaction[0];
                $transaction = $transaction . "_" . $this->tipoTransaccion;

                $transaccionApi2 = new TransjuegoLog("", "", "", $transaction . "_" . $Subproveedor->getSubproveedorId(), $Subproveedor->getSubproveedorId());

                $data = array(
                    "ti" => time(),
                    "sc" => false,
                    "cd" => $codeProveedor,
                    "er" => $messageProveedor,
                    "val" => null
                );
                $TransaccionJuego = new TransaccionJuego($transaccionApi2->getTransjuegoId());
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->usuarioId);
                $sign = $this->getSignature($data, $UsuarioMandante->mandante, $UsuarioMandante->paisId, $Jacktop);

                $respuesta = json_encode(array_merge($response, array(

                    "ti" => time(),
                    "si" => $sign, //encrytación md5 y hash base64
                    "sc" => false,
                    "cd" => $codeProveedor,
                    "er" => $messageProveedor,
                    "val" => null


                )));
            } catch (Exception $e) {
                $data = array(
                    "ti" => time(),
                    "sc" => false,
                    "cd" => $codeProveedor,
                    "er" => $messageProveedor,
                    "val" => null
                );

                preg_match('/P(\d+)P/', $this->token, $matches);
                $id = $matches[1];
                $UsuarioMandante = new UsuarioMandante($id);

                $sign = $this->getSignature($data, $UsuarioMandante->mandante, $UsuarioMandante->paisId, $Jacktop);
                $respuesta = json_encode(array_merge($response, array(
                    "ti" => time(),
                    "si" => $sign, //encrytación md5 y hash base64
                    "sc" => false,
                    "cd" => $codeProveedor,
                    "er" => $messageProveedor,
                    "val" => null
                )));
            }
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
