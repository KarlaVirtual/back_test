<?php

/**
 * Clase `ISoftBet` para manejar la integración con el proveedor ISOFTBET.
 *
 *  Esta clase contiene métodos para realizar operaciones como autenticación,
 *  débito, crédito, rollback, consulta de balance, generación de reportes y manejo
 *  de errores relacionados con las transacciones del proveedor ISOFTBET.
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
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\Subproveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransaccionApi;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\UsuarioConfiguracion;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransaccionApiMySqlDAO;

/**
 * Clase `ISoftBet` para manejar la integración con el proveedor ISOFTBET.
 *
 * Esta clase contiene métodos para realizar operaciones como autenticación,
 * débito, crédito, rollback, consulta de balance, generación de reportes y manejo
 * de errores relacionados con las transacciones del proveedor ISOFTBET.
 */
class ISoftBet
{
    /**
     * Token de autenticación para las solicitudes.
     *
     * @var string
     */
    private $token;

    /**
     * ID del usuario asociado a la transacción.
     *
     * @var integer
     */
    private $usuarioId;

    /**
     * Firma de seguridad utilizada para validar las solicitudes.
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
     * Datos adicionales relacionados con la transacción.
     *
     * @var mixed
     */
    private $data;

    /**
     * Tipo de operación realizada (e.g., DEBIT, CREDIT, ROLLBACK).
     *
     * @var string
     */
    private $tipo = "";

    /**
     * Moneda utilizada en la transacción.
     *
     * @var string
     */
    private $currency = "";

    /**
     * Constructor de la clase ISoftBet.
     *
     * @param string  $token     Token de autenticación.
     * @param string  $sign      Firma de seguridad.
     * @param integer $usuarioId ID del usuario.
     */
    public function __construct($token = "", $sign = "", $usuarioId = "")
    {
        $this->token = $token;
        $this->sign = $sign;
        $this->usuarioId = $usuarioId;

        $Proveedor = new Proveedor("", "ISOFTBET");
        if ($this->token != "") {
            try {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } catch (Exception $e) {
                $UsuarioMandante = new UsuarioMandante($this->usuarioId);
            }
        } else {
            $UsuarioMandante = new UsuarioMandante($this->usuarioId);
        }

        $this->currency = $UsuarioMandante->moneda;
    }

    /**
     * Genera una firma HMAC para el cuerpo de la solicitud.
     *
     * @param string $body Cuerpo de la solicitud.
     *
     * @return string Firma generada.
     */
    public function sign($body)
    {
        try {
            $Proveedor = new Proveedor("", "ISOFTBET");

            if ($this->token != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (Exception $e) {
                    $UsuarioMandante = new UsuarioMandante($this->usuarioId);
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->usumandanteId);
                }
            } else {
                $UsuarioMandante = new UsuarioMandante($this->usuarioId);
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->usumandanteId);
            }

            $Producto = new Producto($UsuarioToken->productoId);

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $PESignature = hash_hmac('sha256', $body, $Credentials->SECRET_KEY);

            return $PESignature;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Autentica al usuario y genera un token de sesión.
     *
     * @param string $sesion ID de la sesión.
     *
     * @return string Respuesta en formato JSON.
     */
    public function Auth($sesion)
    {
        try {
            $Proveedor = new Proveedor("", "ISOFTBET");

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

            try {
                $UsuarioToken = new UsuarioToken($sesion, $Proveedor->getProveedorId());
                throw new Exception("Token ya registado", "10012");
            } catch (Exception $e) {
                if ($e->getCode() == "10012") {
                    throw $e;
                }
            }

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $UsuarioToken->setToken($sesion);
            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
            $UsuarioTokenMySqlDAO->insert($UsuarioToken);
            $UsuarioTokenMySqlDAO->getTransaction()->commit();

            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $return = array(
                "status" => "success",
                "playerid" => $UsuarioMandante->usumandanteId,
                "sessionid" => $UsuarioToken->getToken(),
                "balance" => intval(round($responseG->saldo, 2) * 100),
                "currency" => $responseG->moneda,
                "realplayer" => true,
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Genera o valida un token para el usuario.
     *
     * @param object $data Datos del usuario.
     *
     * @return string Respuesta en formato JSON.
     */
    public function Token($data)
    {
        try {
            $Proveedor = new Proveedor("", "ISOFTBET");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);

            if ($this->token != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                } catch (Exception $e) {
                    $UsuarioToken = new UsuarioToken('', $Proveedor->getProveedorId(), $data->playerid);
                }
            } else {
                $UsuarioToken = new UsuarioToken('', $Proveedor->getProveedorId(), $data->playerid);
            }

            $Producto = new Producto($UsuarioToken->productoId);

            try {
                if ($this->token == "") {
                    $UsuarioToken = new UsuarioToken('', $Proveedor->getProveedorId(), $data->playerid);
                    $UsuarioToken->setToken($UsuarioToken->createToken());
                    $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
                    $UsuarioToken->setProductoId($Producto->productoId);
                } else {
                    throw new Exception("Token no existe", 21);
                }
            } catch (Exception $e) {
                if ($e->getCode() == 21) {
                    $UsuarioToken = new UsuarioToken();
                    $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
                    $UsuarioToken->setCookie('0');
                    $UsuarioToken->setRequestId('0');
                    $UsuarioToken->setUsucreaId(0);
                    $UsuarioToken->setUsumodifId(0);
                    $UsuarioToken->setUsuarioId($data->playerid);
                    $UsuarioToken->setToken($data->sessionid);
                    $UsuarioToken->setSaldo(0);
                    $UsuarioToken->setProductoId($Producto->productoId);
                }
            }

            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
            $UsuarioTokenMySqlDAO->insert($UsuarioToken);
            $UsuarioTokenMySqlDAO->getTransaction()->commit();

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $return = array(
                "status" => "success",
                "token" => $UsuarioToken->getToken()
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del usuario.
     *
     * @return string Respuesta en formato JSON con el balance.
     */
    public function getBalance()
    {
        try {
            $Proveedor = new Proveedor("", "ISOFTBET");

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
                "status" => "success",
                "balance" => intval(round($responseG->saldo, 2) * 100),
                "currency" => $responseG->moneda
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Finaliza la sesión del usuario.
     *
     * @return string Respuesta en formato JSON.
     */
    public function End()
    {
        try {
            $Proveedor = new Proveedor("", "ISOFTBET");

            if ($this->token != "") {
                //  Obtenemos el Usuario Token con el token
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                //  Obtenemos el Usuario Mandante con el Usuario Token
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                if (intval($this->usuarioId) == 0) {
                    throw new Exception("Token vacio", "10011");
                }
                //  Obtenemos el Usuario Mandante con el Usuario Token
                $UsuarioMandante = new UsuarioMandante($this->usuarioId);
                $UsuarioToken = new UsuarioToken('', $Proveedor->getProveedorId(), $UsuarioMandante->getUsumandanteId());
            }

            if ($UsuarioToken->getUsutokenId() != "") {
                $UsuarioToken->setToken($UsuarioToken->getToken());
                $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
                $UsuarioTokenMySqlDAO->update($UsuarioToken);
                $UsuarioTokenMySqlDAO->getTransaction()->commit();
            }

            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);

            $return = array(
                "status" => "success",
                "balance" => intval(round($responseG->saldo, 2) * 100),
                "currency" => $responseG->moneda
            );

            return json_encode($return);
        } catch (Exception $e) {
            if ($_ENV['debug']) {
                print_r($e);
            }
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
     * @param mixed   $datos         Datos adicionales.
     * @param boolean $freespin      Indica si es un freespin.
     *
     * @return string Respuesta en formato JSON.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $freespin = false)
    {
        $this->tipo = "DEBIT";
        $this->data = $datos;
        try {
            //Obtenemos el Proveedor con el abreviado ISOFTBET
            $Proveedor = new Proveedor("", "ISOFTBET");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);

            if ($this->token != "") {
                // Obtenemos el Usuario Token con el token
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                //  Obtenemos el Usuario Mandante con el Usuario Token
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                $UsuarioMandante = new UsuarioMandante($this->usuarioId);
            }

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "ISOFTBET");

            //Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
            $Subproveedor = new Subproveedor($Producto->getSubproveedorId());

            $ConfigurationEnvironment = new ConfigurationEnvironment();
            if ($ConfigurationEnvironment->isDevelopment() || in_array($UsuarioMandante->mandante, array(0, 18, 19))) {
                $result = '0';
                if ($Subproveedor->getTipo() == 'CASINO') {
                    $UsuarioConfiguracion = new UsuarioConfiguracion();

                    $UsuarioConfiguracion->setUsuarioId($UsuarioMandante->usuarioMandante);
                    $result = $UsuarioConfiguracion->verifyLimitesCasino($this->transaccionApi->getValor(), $UsuarioMandante);
                } elseif ($Subproveedor->getTipo() == 'LIVECASINO') {
                    $UsuarioConfiguracion = new UsuarioConfiguracion();

                    $UsuarioConfiguracion->setUsuarioId($UsuarioMandante->usuarioMandante);
                    $result = $UsuarioConfiguracion->verifyLimitesCasinoVivo($this->transaccionApi->getValor(), $UsuarioMandante);
                }

                if ($result != '0') {
                    throw new Exception("Limite de Autoexclusion", $result);
                }
            }

            $this->transaccionApi->setTransaccionId("ROLLBACK" . $transactionId);

            if ($this->transaccionApi->existsTransaccionIdAndProveedor("ERROR")) {
                throw new Exception("Trasacción con rollback", "10015");
            }

            if ($this->transaccionApi->existsTransaccionIdAndProveedor("OK")) {
                throw new Exception("Trasacción con rollback", "10015");
            }

            $this->transaccionApi->setTransaccionId($transactionId);
            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin);
            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "status" => "success",
                "balance" => intval(round($responseG->saldo, 2) * 100),
                "currency" => $responseG->moneda
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
     * @param float   $rollbackAmount Monto a revertir.
     * @param string  $roundId        ID de la ronda.
     * @param string  $transactionId  ID de la transacción.
     * @param integer $player         ID del jugador.
     * @param mixed   $datos          Datos adicionales.
     *
     * @return string Respuesta en formato JSON.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos)
    {
        $this->tipo = "ROLLBACK";
        $usuarioid = $player;
        $this->data = $datos;

        try {
            //Obtenemos el Proveedor con el abreviado ISoftBet
            $Proveedor = new Proveedor("", "ISOFTBET");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);

            $TransaccionJuego = new TransaccionJuego('', $roundId . $player . "ISOFTBET");

            if ($TransaccionJuego->getValorPremio() != 0) {
                throw new Exception("Ronda cerrada", "10016");
            }

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
            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false);

            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "status" => "success",
                "balance" => intval(round($responseG->saldo, 2) * 100),
                "currency" => $responseG->moneda
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
     * @param mixed  $datos         Datos adicionales relacionados con la transacción.
     *
     * @return string Respuesta en formato JSON con el resultado del crédito.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos)
    {
        $this->tipo = "CREDIT";

        $this->data = $datos;
        $array = json_decode($datos);
        $currency = $array->currency;

        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try {
            //Obtenemos el Proveedor con el abreviado ISOFTBET
            $Proveedor = new Proveedor("", "ISOFTBET");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);
            //$this->transaccionApi->setIdentificador($roundId . "ISoftBet");

            /*  Obtenemos el Usuario Token con el token */
            if ($this->token != "") {
                // Obtenemos el Usuario Token con el token
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                //  Obtenemos el Usuario Mandante con el Usuario Token
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                $UsuarioMandante = new UsuarioMandante($this->usuarioId);
            }

            if ($UsuarioMandante->getMoneda() != $currency) {
                throw new Exception("Moneda diferente", "10017");
            }
            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "ISOFTBET");

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
                "status" => "success",
                "balance" => intval(round($responseG->saldo, 2) * 100),
                "currency" => $responseG->moneda
            );

            //Guardamos la Transaccion Api necesaria de estado OK
            $this->transaccionApi->setRespuestaCodigo("OK");
            $this->transaccionApi->setRespuesta(json_encode($return));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return json_encode($return);
        } catch (Exception $e) {
            if ($_ENV['debug']) {
                print_r($e);
            }
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Verifica un parámetro de prueba.
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
     * Genera un reporte de transacciones en un rango de fechas.
     *
     * @param string $dateFrom Fecha de inicio (YYYY-MM-DD).
     * @param string $dateTo   Fecha de fin (YYYY-MM-DD).
     *
     * @return string Respuesta en formato JSON con el reporte.
     */
    public function report($dateFrom, $dateTo)
    {
        $rules = [];
        array_push($rules, array("field" => "transaccion_juego.usuario_id", "data" => $this->usuarioId, "op" => "eq"));
        array_push($rules, array("field" => "transaccion_juego.fecha_crea", "data" => $dateFrom, "op" => "ge"));
        array_push($rules, array("field" => "transaccion_juego.fecha_crea", "data" => $dateTo, "op" => "le"));
        array_push($rules, array("field" => "proveedor.abreviado", "data" => "ISOFTBET", "op" => "eq"));
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $select = "SUM(CASE WHEN transaccion_juego.ticket_id like '%ISOFTBET%' THEN 1 ELSE 0 END) as rounds";
        $grouping = "transaccion_juego.usuario_id";

        $TransaccionJuego = new TransaccionJuego();
        $data = $TransaccionJuego->getTransaccionesCustom2($select, "transaccion_juego.usuario_id", "asc", 0, 100, $json, true, $grouping);
        $data = json_decode($data);
        $array = array();
        $arrayfinal = array();

        if ($data->data != "" && $data->data != null) {
            foreach ($data->data as $key => $value) {
                $array["rounds"] = intval($data->data[0]->{".rounds"});
            }
        } else {
            $array["rounds"] = 0;
        }
        array_push($arrayfinal, $array);

        $rules = [];
        array_push($rules, array("field" => "transaccion_juego.usuario_id", "data" => $this->usuarioId, "op" => "eq"));
        array_push($rules, array("field" => "transjuego_log.fecha_crea", "data" => $dateFrom, "op" => "ge"));
        array_push($rules, array("field" => "transjuego_log.fecha_crea", "data" => $dateTo, "op" => "le"));
        array_push($rules, array("field" => "proveedor.abreviado", "data" => "ISOFTBET", "op" => "eq"));
        array_push($rules, array("field" => "transjuego_log.tipo", "data" => "ROLLBACK", "op" => "eq"));
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $select = "SUM(transjuego_log.valor) as bets_amount_cancelled";
        $grouping = "transaccion_juego.usuario_id";

        $TransjuegoLog = new TransjuegoLog();
        $data = $TransjuegoLog->getTransjuegoLogsCustom2($select, "transaccion_juego.usuario_id", "asc", 0, 100, $json, true, $grouping);
        $data = json_decode($data);
        $arrayfinal2 = array();
        if ($data->data != "" && $data->data != null) {
            foreach ($data->data as $key => $value) {
                $array["bets_amount_cancelled"] = intval(round($data->data[0]->{".bets_amount_cancelled"}, 2) * 100);
            }
        } else {
            $array["bets_amount_cancelled"] = 0;
        }

        $MontoCancelado = $array["bets_amount_cancelled"];
        array_push($arrayfinal2, $array);
        array_push($arrayfinal, $arrayfinal2);

        $rules = [];
        array_push($rules, array("field" => "transaccion_juego.usuario_id", "data" => $this->usuarioId, "op" => "eq"));
        array_push($rules, array("field" => "transjuego_log.fecha_crea", "data" => $dateFrom, "op" => "ge"));
        array_push($rules, array("field" => "transjuego_log.fecha_crea", "data" => $dateTo, "op" => "le"));
        array_push($rules, array("field" => "proveedor.abreviado", "data" => "ISOFTBET", "op" => "eq"));
        array_push($rules, array("field" => "transjuego_log.tipo", "data" => "DEBIT", "op" => "eq"));
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $select = "count(transjuego_log.tipo) as bets, SUM(transjuego_log.valor) as bets_amount";
        $grouping = "transaccion_juego.usuario_id";

        $TransjuegoLog = new TransjuegoLog();
        $data = $TransjuegoLog->getTransjuegoLogsCustom2($select, "transaccion_juego.usuario_id", "asc", 0, 100, $json, true, $grouping);
        $data = json_decode($data);
        $arrayfinal3 = array();

        if ($data->data != "" && $data->data != null) {
            foreach ($data->data as $key => $value) {
                $array["bets"] = intval($data->data[0]->{".bets"});

                $array["bets_amount"] = (intval(round($data->data[0]->{".bets_amount"}, 2) * 100) - $MontoCancelado);
                //$array["bets_amount"] = intval(round($data->data[0]->{".bets_amount"},2)*100) ;
            }
        } else {
            $array["bets"] = 0;
            $array["bets_amount"] = 0;
        }

        array_push($arrayfinal3, $array);
        array_push($arrayfinal2, $arrayfinal3);

        $rules = [];
        array_push($rules, array("field" => "transaccion_juego.usuario_id", "data" => $this->usuarioId, "op" => "eq"));
        array_push($rules, array("field" => "transjuego_log.fecha_crea", "data" => $dateFrom, "op" => "ge"));
        array_push($rules, array("field" => "transjuego_log.fecha_crea", "data" => $dateTo, "op" => "le"));
        array_push($rules, array("field" => "proveedor.abreviado", "data" => "ISOFTBET", "op" => "eq"));
        array_push($rules, array("field" => "transjuego_log.tipo", "data" => "CREDIT", "op" => "eq"));
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $select = "count(transjuego_log.tipo) as wins, SUM(transjuego_log.valor) as wins_amount";
        $grouping = "transaccion_juego.usuario_id";

        $TransjuegoLog = new TransjuegoLog();
        $data = $TransjuegoLog->getTransjuegoLogsCustom2($select, "transaccion_juego.usuario_id", "asc", 0, 100, $json, true, $grouping);
        $data = json_decode($data);
        $arrayfinal4 = array();

        if ($data->data != "" && $data->data != null) {
            foreach ($data->data as $key => $value) {
                $array["wins"] = intval($data->data[0]->{".wins"});
                $array["wins_amount"] = intval(round($data->data[0]->{".wins_amount"}, 2) * 100);
            }
        } else {
            $array["wins"] = 0;
            $array["wins_amount"] = 0;
        }

        $array["transactions"] = $array["bets"] + $array["wins"];

        array_push($arrayfinal4, $array);
        array_push($arrayfinal3, $arrayfinal4);

        $rules = [];
        array_push($rules, array("field" => "transaccion_juego.usuario_id", "data" => $this->usuarioId, "op" => "eq"));
        array_push($rules, array("field" => "transjuego_log.fecha_crea", "data" => $dateFrom, "op" => "ge"));
        array_push($rules, array("field" => "transjuego_log.fecha_crea", "data" => $dateTo, "op" => "le"));
        array_push($rules, array("field" => "proveedor.abreviado", "data" => "ISOFTBET", "op" => "eq"));
        //array_push($rules, array("field" => "transjuego_log.tipo", "data" => "DEBIT", "op" => "eq"));
        array_push($rules, array("field" => "transaccion_juego.tipo", "data" => "FREESPIN", "op" => "eq"));
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $select = "count(transjuego_log.tipo) as fround_bets";
        $grouping = "transaccion_juego.usuario_id";

        $TransjuegoLog = new TransjuegoLog();
        $data = $TransjuegoLog->getTransjuegoLogsCustom2($select, "transaccion_juego.usuario_id", "asc", 0, 100, $json, true, $grouping);
        $data = json_decode($data);
        $arrayfinal5 = array();

        if ($data->data != "" && $data->data != null) {
            foreach ($data->data as $key => $value) {
                $array["fround_bets"] = intval($data->data[0]->{".fround_bets"});
            }
        } else {
            $array["fround_bets"] = 0;
        }

        array_push($arrayfinal5, $array);
        array_push($arrayfinal4, $arrayfinal5);

        $rules = [];
        array_push($rules, array("field" => "transaccion_juego.usuario_id", "data" => $this->usuarioId, "op" => "eq"));
        array_push($rules, array("field" => "transjuego_log.fecha_crea", "data" => $dateFrom, "op" => "ge"));
        array_push($rules, array("field" => "transjuego_log.fecha_crea", "data" => $dateTo, "op" => "le"));
        array_push($rules, array("field" => "proveedor.abreviado", "data" => "ISOFTBET", "op" => "eq"));
        array_push($rules, array("field" => "transjuego_log.tipo", "data" => "CREDIT", "op" => "eq"));
        array_push($rules, array("field" => "transjuego_log.valor", "data" => 0, "op" => "gt"));
        array_push($rules, array("field" => "transaccion_juego.tipo", "data" => "FREESPIN", "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $select = "SUM(transjuego_log.valor) as fround_wins_amount";
        $grouping = "transaccion_juego.usuario_id";

        $TransjuegoLog = new TransjuegoLog();
        $data = $TransjuegoLog->getTransjuegoLogsCustom2($select, "transaccion_juego.usuario_id", "asc", 0, 100, $json, true, $grouping);
        $data = json_decode($data);
        $arrayfinal6 = array();

        if ($data->data != "" && $data->data != null) {
            foreach ($data->data as $key => $value) {
                $array["fround_wins_amount"] = intval(round($data->data[0]->{".fround_wins_amount"}, 2) * 100);
            }
        } else {
            $array["fround_wins_amount"] = 0;
        }

        array_push($arrayfinal6, $array);
        array_push($arrayfinal5, $arrayfinal6);

        $response = $arrayfinal6;
        return json_encode($response);
    }

    /**
     * Convierte un código de error en un mensaje legible.
     *
     * @param integer $code    Código de error.
     * @param string  $message Mensaje de error.
     *
     * @return string Respuesta en formato JSON con el error convertido.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";
        $action = "void";
        $Proveedor = new Proveedor("", "ISOFTBET");
        $response = array();

        switch ($code) {
            case 10011:
                $codeProveedor = "I_03";
                $messageProveedor = "Invalid Token.";
                break;

            case 10012:
                $codeProveedor = "R_11";
                $messageProveedor = "Token already used.";
                break;

            case 10015:
                $codeProveedor = "B_05";
                $messageProveedor = "Transaction has been cancelled.";
                break;

            case 10016:
                $codeProveedor = "C_04";
                $messageProveedor = "Trying to cancel a bet from an already closed round.";
                break;

            case 10017:
                $codeProveedor = "R_13";
                $messageProveedor = "currency is incorrect";
                break;
            case 21:
                $codeProveedor = "R_11";
                $messageProveedor = "Token already used.";
                break;

            case 22:
                $codeProveedor = "R_09";
                $messageProveedor = "Player not found.";
                break;

            case 20001:
                $codeProveedor = "B_03";
                $messageProveedor = "Insufficient Funds.";
                $action = "continue";
                break;

            case 27:
                $codeProveedor = "R_10";
                $messageProveedor = "Game is not configured for this licensee.";
                break;

            case 28:
                $codeProveedor = "C_03";
                $messageProveedor = "Invalid cancel, Transaction does not exist.";
                $codeProveedor = "C_04";
                $messageProveedor = "Trying to cancel a bet from an already closed round.";
                break;

            case 29:
                $codeProveedor = "W_06";
                $messageProveedor = "Duplicate Transaction Id.";
                break;

            case 10001:
                switch ($this->tipo) {
                    case "DEBIT":
                        $codeProveedor = "B_04";
                        $messageProveedor = "Duplicate Transaction Id.";

                        $ProductoMandante = new ProductoMandante('', '', $this->transaccionApi->getProductoId());
                        $Producto = new Producto($ProductoMandante->productoId);


                        $TransjuegoLog = new TransjuegoLog("", '', '', $this->transaccionApi->getTransaccionId() . '_' . $Producto->getSubproveedorId(), $Producto->getSubproveedorId());
                        $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);

                        $UsuarioMandante = new UsuarioMandante($TransaccionJuego->usuarioId);

                        $Game = new Game();
                        $responseG = $Game->getBalance($UsuarioMandante);
                        $array = json_decode($this->data);
                        $Amount = floatval(round($array->action->parameters->amount, 2) / 100);

                        if ($TransjuegoLog->getValor() == $Amount) {
                            $response = array(
                                "status" => "success",
                                "balance" => intval(round($responseG->saldo, 2) * 100),
                                "currency" => $responseG->moneda
                            );
                        } else {
                            $response = array(
                                "status" => "error",
                                "code" => "B_04",
                                "message" => "Duplicate Transaction Id",
                                "action" => $action
                            );
                        }
                        break;
                    case "CREDIT":
                        $codeProveedor = "W_06";
                        $messageProveedor = "Duplicate Transaction Id.";

                        $ProductoMandante = new ProductoMandante('', '', $this->transaccionApi->getProductoId());
                        $Producto = new Producto($ProductoMandante->productoId);

                        $TransjuegoLog = new TransjuegoLog("", '', '', $this->transaccionApi->getTransaccionId() . '_' . $Producto->getSubproveedorId(), $Producto->getSubproveedorId());
                        $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);

                        $UsuarioMandante = new UsuarioMandante($TransaccionJuego->usuarioId);
                        $Game = new Game();
                        $responseG = $Game->getBalance($UsuarioMandante);
                        $array = json_decode($this->data);
                        $Amount = floatval(round($array->action->parameters->amount, 2) / 100);

                        if ($TransjuegoLog->getValor() == $Amount) {
                            $response = array(
                                "status" => "success",
                                "balance" => intval(round($responseG->saldo, 2) * 100),
                                "currency" => $responseG->moneda
                            );
                        } else {
                            $response = array(
                                "status" => "error",
                                "code" => "B_04",
                                "message" => "Duplicate Transaction Id",
                                "action" => $action
                            );
                        }

                        break;
                    case "ROLLBACK":

                        $transaccionApi2 = new TransaccionApi("", $this->transaccionApi->getTransaccionId(), $Proveedor->getProveedorId());
                        $UsuarioMandante = new UsuarioMandante($transaccionApi2->usuarioId);
                        $Game = new Game();

                        $responseG = $Game->getBalance($UsuarioMandante);

                        $response = array(
                            "status" => "success",
                            "balance" => intval(round($responseG->saldo, 2) * 100),
                            "currency" => $responseG->moneda
                        );

                        break;
                }
                break;

            case 10010:
                $codeProveedor = "B_05";
                $messageProveedor = "Duplicate Transaction Id.";

                $transaccionApi2 = new TransaccionApi("", $this->transaccionApi->getTransaccionId(), $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($transaccionApi2->usuarioId);
                $Game = new Game();

                $responseG = $Game->getBalance($UsuarioMandante);

                $response = array(
                    "status" => "success",
                    "balance" => intval(round($responseG->saldo, 2) * 100),
                    "currency" => $responseG->moneda
                );
                break;

            case 10005:
                $codeProveedor = "C_03";
                $messageProveedor = "Invalid cancel, Transaction does not exist";
                break;

            case 10007:
                $codeProveedor = "C_05";
                $messageProveedor = "Transaction details do not match";
                break;

            case 10008:
                $codeProveedor = "W-06";
                $messageProveedor = "Duplicate Transaction Id.";
                break;

            case 100012:
                $codeProveedor = "R_03";
                $messageProveedor = "Invalid HMAC.";
                break;

            case 30018:
                $codeProveedor = "W_03";
                $messageProveedor = "Invalid Round.";
                break;

            default:
                $codeProveedor = "ERnn";
                $messageProveedor = "Technical Error";
                break;
        }

        if ($code != 10001 && $code != 10010) {
            $respuesta = json_encode(array_merge($response, array(
                "status" => "error",
                "code" => $codeProveedor,
                "message" => $messageProveedor,
                "action" => $action
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
