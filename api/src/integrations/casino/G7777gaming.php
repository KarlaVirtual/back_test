<?php

/**
 * Clase `G7777gaming` para la integración con el proveedor de juegos 7777GAMING.
 *
 * Esta clase contiene métodos para manejar la autenticación, transacciones,
 * consultas de saldo y manejo de errores relacionados con la integración
 * del proveedor de juegos 7777GAMING.
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
use Backend\dto\Registro;
use Backend\dto\Proveedor;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioToken;
use Backend\dto\SubproveedorMandantePais;
use Backend\dto\TransjuegoLog;
use Backend\dto\PromocionalLog;
use Backend\dto\TransaccionApi;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioOtrainfo;
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
 * Clase `G7777gaming`.
 *
 * Esta clase proporciona métodos para la integración con el proveedor de juegos 7777GAMING.
 * Incluye funcionalidades como autenticación, manejo de transacciones, consultas de saldo,
 * y notificaciones de eventos.
 */
class G7777gaming
{
    /**
     * Identificador del operador.
     *
     * @var string
     */
    private $operadorId;

    /**
     * Token de autenticación.
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
     * Objeto para manejar transacciones API.
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
     * Identificador de la ronda principal.
     *
     * @var string
     */
    private $roundIdSuper;

    /**
     * Login seguro para la integración.
     *
     * @var string
     */
    private $secureLogin = 'drb_doradobet';

    /**
     * Método actual en ejecución.
     *
     * @var string
     */
    private $method = ' ';

    /**
     * Identificador del proveedor.
     *
     * @var string
     */
    private $providerId = '7777gamingPlay';

    /**
     * Indica si ocurrió un error en el hash.
     *
     * @var boolean
     */
    private $errorHash = false;

    /**
     * Clave de seguridad para las operaciones.
     *
     * @var string
     */
    private $KEY;

    /**
     * Constructor de la clase `G7777gaming`.
     *
     * Inicializa los valores necesarios para la integración con el proveedor 7777GAMING.
     *
     * @param string $token        Token de autenticación.
     * @param string $sign         Firma de seguridad.
     * @param string $external     ID externo del usuario.
     * @param string $hashOriginal Hash original para validación.
     */
    public function __construct($token, $sign, $external = "", $hashOriginal = "")
    {
        if (! defined('JSON_PRESERVE_ZERO_FRACTION')) {
            define('JSON_PRESERVE_ZERO_FRACTION', 1024);
        }

        $this->token = $token;
        $this->sign = $sign;
        $this->externalId = $external;
        if ($this->sign != $hashOriginal && false) {
            $this->errorHash = true;
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }

        $Proveedor = new Proveedor("", "7777GAMING");

        if ($this->token != "") {
            try {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } catch (Exception $e) {
                try {
                    $UsuarioMandante = new UsuarioMandante($this->externalId);
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->usumandanteId);
                } catch (Exception $e) {
                    $Usuario = new Usuario($this->externalId);
                    $UsuarioMandante = new UsuarioMandante("", $this->externalId, $Usuario->mandante);
                    try {
                        $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->usumandanteId);
                    } catch (Exception $e) {
                    }
                }
            }
        } else {
            $UsuarioMandante = new UsuarioMandante($this->externalId);
            $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->usumandanteId);
        }

        try {
            if ($UsuarioToken === null) {
                throw new Exception("Token vacio", "20002");
            }
            $gameId = $UsuarioToken->getProductoId();
            $Producto = new Producto($gameId, "", $Proveedor->getProveedorId());
        } catch (Exception $e) {
            $Producto = new Producto("", "DE_7777", $Proveedor->getProveedorId());
        }

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $TRANS_KEY = $credentials->TRANS_KEY;
        $this->KEY = $TRANS_KEY;
    }

    /**
     * Obtiene el ID del operador.
     *
     * @return string ID del operador.
     */
    public function getOperadorId()
    {
        return $this->operadorId;
    }

    /**
     * Método para autenticar al usuario.
     *
     * @param mixed $app_ Datos de la aplicación.
     *
     * @return string Respuesta en formato JSON.
     */
    public function Auth($app_)
    {
        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }

        $this->method = 'authenticate';
        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }
            $Proveedor = new Proveedor("", "7777GAMING");

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

            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $saldo = str_replace(',', '', number_format(round($Usuario->getBalance(), 2), 2, '.', null));

            $token = $UsuarioToken->createToken();
            $UsuarioToken->setToken($token);
            $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
            $UsuarioToken->setProductoId($UsuarioToken->productoId);
            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
            $UsuarioTokenMySqlDAO->insert($UsuarioToken);
            $UsuarioTokenMySqlDAO->getTransaction()->commit();

            $array = array(
                "token" => $UsuarioToken->getToken(),
                "user_id" => intval($UsuarioMandante->usumandanteId),
                "username" => $Usuario->nombre,
                "currency" => $responseG->moneda,
                "language" => $Usuario->idioma,
                "balance" => $saldo * 100,
                "bonus_balance" => 0,
                "error_code" => 0,
                "error_msg" => '',
            );

            $signature = hash('sha256', $this->KEY . '##' . base64_encode(json_encode($array)) . '##' . $this->KEY);

            $return = array(
                "app_data" => base64_encode(json_encode($array)),
                "signature" => $signature
            );

            return json_encode($return, JSON_PRESERVE_ZERO_FRACTION);
        } catch (Exception $e) {
            $re_ = $this->convertError($e->getCode(), $e->getMessage());
            $dat_ = json_decode($re_);

            $array = array(
                "token" => 0,
                "user_id" => 0,
                "username" => 0,
                "currency" => 0,
                "language" => 0,
                "balance" => 1.00 * 100,
                "bonus_balance" => 0,
                "error_code" => $dat_->error_code,
                "error_msg" => $dat_->error_msg
            );
            $signature = hash('sha256', $this->KEY . '##' . base64_encode(json_encode($array)) . '##' . $this->KEY);
            $return = array(
                "app_data" => base64_encode(json_encode($array)),
                "signature" => $signature
            );
            return json_encode($return);
        }
    }

    /**
     * Método para verificar si el servicio está activo.
     *
     * @return string Respuesta en formato JSON.
     */
    public function Alive()
    {
        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }

        $this->method = 'authenticate';
        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }
            $Proveedor = new Proveedor("", "7777GAMING");

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
            $UsuarioOtrainfo = new UsuarioOtrainfo($UsuarioMandante->usuarioMandante);
            $Registro = new Registro("", $Usuario->usuarioId);

            $Game = new Game();

            $responseG = $Game->autenticate($UsuarioMandante);

            $saldo = str_replace(',', '', number_format(round($Usuario->getBalance(), 2), 2, '.', null));


            $array = array(
                "user_id" => intval($UsuarioMandante->usumandanteId),
                "username" => $Usuario->nombre,
                "currency" => $responseG->moneda,
                "language" => $Usuario->idioma,
                "balance" => $saldo * 100,
                "bonus_balance" => 0,
                "error_code" => 0,
                "error_msg" => '',
            );

            $signature = hash('sha256', $this->KEY . '##' . base64_encode(json_encode($array)) . '##' . $this->KEY);

            $return = array(
                "app_data" => base64_encode(json_encode($array)),
                "signature" => $signature
            );

            return json_encode($return, JSON_PRESERVE_ZERO_FRACTION);
        } catch (Exception $e) {
            $re_ = $this->convertError($e->getCode(), $e->getMessage());
            $dat_ = json_decode($re_);

            $array = array(
                "user_id" => 0,
                "username" => 0,
                "currency" => 0,
                "language" => 0,
                "balance" => 1.00 * 100,
                "bonus_balance" => 0,
                "error_code" => $dat_->error_code,
                "error_msg" => $dat_->error_msg
            );
            $signature = hash('sha256', $this->KEY . '##' . base64_encode(json_encode($array)) . '##' . $this->KEY);
            $return = array(
                "app_data" => base64_encode(json_encode($array)),
                "signature" => $signature
            );
            return json_encode($return);
        }
    }

    /**
     * Método para notificar eventos al proveedor.
     *
     * @return string Respuesta en formato JSON.
     */
    public function notify()
    {
        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }

        $this->method = 'authenticate';
        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            $Proveedor = new Proveedor("", "7777GAMING");

            if ($this->token != "") {
                try {
                    /*  Obtenemos el Usuario Token con el token */
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    /*  Obtenemos el Usuario Mandante con el Usuario Token */
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (\Throwable $th) {
                    /*  Obtenemos el Usuario Mandante con el Usuario Token */
                    $UsuarioMandante = new UsuarioMandante($this->externalId);
                }
            } else {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $saldo = str_replace(',', '', number_format(round($Usuario->getBalance(), 2), 2, '.', null));

            $array = array(
                "balance" => $saldo * 100,
                "bonus_balance" => 0,
                "error_code" => 0,
                "error_type" => "info",
                "error_msg" => '',
            );

            $signature = hash('sha256', $this->KEY . '##' . base64_encode(json_encode($array)) . '##' . $this->KEY);

            $return = array(
                "app_data" => base64_encode(json_encode($array)),
                "signature" => $signature
            );

            return json_encode($return, JSON_PRESERVE_ZERO_FRACTION);
        } catch (Exception $e) {
            $re_ = $this->convertError($e->getCode(), $e->getMessage());
            $dat_ = json_decode($re_);

            $array = array(
                "balance" => 1.00 * 100,
                "bonus_balance" => 0,
                "error_code" => $dat_->error_code,
                "error_type" => "warning",
                "error_msg" => $dat_->error_msg
            );
            $signature = hash('sha256', $this->KEY . '##' . base64_encode(json_encode($array)) . '##' . $this->KEY);
            $return = array(
                "app_data" => base64_encode(json_encode($array)),
                "signature" => $signature
            );
            return json_encode($return);
        }
    }


    /**
     * Obtiene el saldo del usuario (versión 2).
     *
     * @param string $playerId ID del jugador.
     *
     * @return string Respuesta en formato JSON con el saldo.
     */
    public function getBalance2($playerId)
    {
        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }

        try {
            $Proveedor = new Proveedor("", "7777GAMING");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("BALANCE");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);


            if ($playerId == "") {
                throw new Exception("Token vacio", "10011");
            }


            $UsuarioMandante = new UsuarioMandante($playerId);

            $Mandante = new Mandante($UsuarioMandante->mandante);

            if ($Mandante->propio == "S") {
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                $Balance = (int)($Usuario->getBalance() * 100);

                $return = array(
                    "balance" => $Balance,

                );
                return json_encode($return);
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el saldo del usuario.
     *
     * @param string $playerId ID del jugador.
     *
     * @return string Respuesta en formato JSON con el saldo.
     */
    public function getBalance($playerId = "")
    {
        //$this->externalId=$playerId;

        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }

        $this->method = 'balance';
        try {
            $Proveedor = new Proveedor("", "7777GAMING");


            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            /* Obtenemos el producto con el gameId */
            //$Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $UsuarioOtrainfo = new UsuarioOtrainfo($UsuarioMandante->usuarioMandante);
            $Registro = new Registro("", $Usuario->usuarioId);

            $Game = new Game();

            $responseG = $Game->getBalance($UsuarioMandante);

            $saldo = str_replace(',', '', number_format(round($Usuario->getBalance(), 2), 2, '.', null));


            $array = array(
                "user_id" => intval($UsuarioMandante->usumandanteId),
                "username" => $Usuario->nombre,
                "currency" => $responseG->moneda,
                "language" => $Usuario->idioma,
                "balance" => $saldo * 100,
                "bonus_balance" => 0,
                "error_code" => 0,
                "error_msg" => '',
            );

            $signature = hash('sha256', $this->KEY . '##' . base64_encode(json_encode($array)) . '##' . $this->KEY);

            $return = array(
                "app_data" => base64_encode(json_encode($array)),
                "signature" => $signature
            );

            return json_encode($return, JSON_PRESERVE_ZERO_FRACTION);
        } catch (Exception $e) {
            $re_ = $this->convertError($e->getCode(), $e->getMessage());
            $dat_ = json_decode($re_);

            $array = array(
                "user_id" => 0,
                "username" => 0,
                "currency" => 0,
                "language" => 0,
                "balance" => 1.00 * 100,
                "bonus_balance" => 0,
                "error_code" => $dat_->error_code,
                "error_msg" => $dat_->error_msg
            );
            $signature = hash('sha256', $this->KEY . '##' . base64_encode(json_encode($array)) . '##' . $this->KEY);
            $return = array(
                "app_data" => base64_encode(json_encode($array)),
                "signature" => $signature
            );
            return json_encode($return);
        }
    }

    /**
     * Método para debitar un monto del saldo del usuario.
     *
     * @param string  $gameId        ID del juego.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param mixed   $datos         Datos adicionales.
     * @param boolean $isfreeSpin    Indica si es un giro gratis.
     * @param string  $type          Tipo de operación.
     *
     * @return string Respuesta en formato JSON.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $isfreeSpin = false, $type = '')
    {
        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }

        $this->method = 'reserve';

        $this->data = $datos;

        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado 7777gaming */
            $Proveedor = new Proveedor("", "7777GAMING");

            if ($this->token != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (Exception $e) {
                    $UsuarioMandante = new UsuarioMandante($this->externalId);
                }
            } else {
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            if ($gameId == '') {
                try {
                    $Producto = new Producto("", "DE_7777", $Proveedor->getProveedorId());
                } catch (Exception $e) {
                    $Producto = new Producto($UsuarioToken->productoId);
                }
            } else {
                $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
            }

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);
            $this->transaccionApi->setIdentificador("7777GAMING" . $roundId);

            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $isfreeSpin);

            $this->transaccionApi = $responseG->transaccionApi;

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            $saldo = str_replace(',', '', number_format(round($Usuario->getBalance(), 2), 2, '.', null));

            if ($type == 'notify') {
                $array = array(
                    "balance" => $saldo * 100,
                    "bonus_balance" => 0,
                    "error_code" => 0,
                    "error_type" => "info",
                    "error_msg" => '',
                );
            } else {
                $array = array(
                    "used_cash_bet_amount" => $debitAmount * 100,
                    "used_bonus_bet_amount" => 0,
                    "user_id" => intval($UsuarioMandante->usumandanteId),
                    "username" => $Usuario->nombre,
                    "currency" => $responseG->moneda,
                    "language" => $Usuario->idioma,
                    "balance" => $saldo * 100,
                    "bonus_balance" => 0,
                    "error_code" => 0,
                    "error_msg" => '',
                );
            }

            $signature = hash('sha256', $this->KEY . '##' . base64_encode(json_encode($array)) . '##' . $this->KEY);

            $return = array(
                "app_data" => base64_encode(json_encode($array)),
                "signature" => $signature
            );

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta($return);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();


            return json_encode($return);
        } catch (Exception $e) {
            $re_ = $this->convertError($e->getCode(), $e->getMessage());
            $dat_ = json_decode($re_);

            $array = array(
                "used_cash_bet_amount" => 0,
                "used_bonus_bet_amount" => 0,
                "user_id" => 0,
                "username" => 0,
                "currency" => 0,
                "language" => 0,
                "balance" => 1.00 * 100,
                "bonus_balance" => 0,
                "error_code" => $dat_->error_code,
                "error_msg" => $dat_->error_msg
            );
            $signature = hash('sha256', $this->KEY . '##' . base64_encode(json_encode($array)) . '##' . $this->KEY);
            $return = array(
                "app_data" => base64_encode(json_encode($array)),
                "signature" => $signature
            );
            return json_encode($return);
        }
    }

    /**
     * Método para realizar un rollback de una transacción.
     *
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $roundId        ID de la ronda.
     * @param string $transactionId  ID de la transacción.
     * @param string $player         ID del jugador.
     * @param mixed  $datos          Datos adicionales.
     * @param string $txId           ID de la transacción externa.
     *
     * @return string Respuesta en formato JSON.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos, $txId)
    {
        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }

        $this->method = 'Rollback';

        $this->data = $datos;

        try {
            /*  Obtenemos el Proveedor con el abreviado 7777gaming */
            $Proveedor = new Proveedor("", "7777GAMING");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($rollbackAmount);

            try {
                $SubProveedor = new SubProveedor("", "7777GAMING");
                $TransjuegoLog = new TransjuegoLog("", "", "", $transactionId . '_' . $SubProveedor->getSubproveedorId(), $SubProveedor->getSubproveedorId());
                $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                //$this->transaccionApi->setIdentificador($TransaccionJuego->getTicketId());


                if (strpos($TransjuegoLog->getTipo(), 'DEBIT') !== false) {
                    $this->transaccionApi->setIdentificador($TransaccionJuego->getTicketId());
                } else {
                    throw new Exception("Transaccion no es Debit", "10006");
                }
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }


            $Game = new Game();

            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi);

            $this->transaccionApi = $responseG->transaccionApi;

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            $saldo = str_replace(',', '', number_format(round($Usuario->getBalance(), 2), 2, '.', null));


            $array = array(
                "balance" => $saldo * 100,
                "bonus_balance" => 0,
                "error_code" => 0,
                "error_msg" => '',
            );

            $signature = hash('sha256', $this->KEY . '##' . base64_encode(json_encode($array)) . '##' . $this->KEY);

            $return = array(
                "app_data" => base64_encode(json_encode($array)),
                "signature" => $signature
            );

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta($return);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return json_encode($return, JSON_PRESERVE_ZERO_FRACTION);
        } catch (Exception $e) {
            $re_ = $this->convertError($e->getCode(), $e->getMessage());

            $dat_ = json_decode($re_);

            $code = $dat_->error_code;
            if ($dat_->error_msg == "Bet Transaction not found") {
                $code = 0;
            }

            $array = array(
                "balance" => 1.00 * 100,
                "bonus_balance" => 0,
                "error_code" => $code,
                "error_msg" => $dat_->error_msg,
            );
            $signature = hash('sha256', $this->KEY . '##' . base64_encode(json_encode($array)) . '##' . $this->KEY);
            $return = array(
                "app_data" => base64_encode(json_encode($array)),
                "signature" => $signature
            );

            return json_encode($return);
        }
    }

    /**
     * Método para enmendar una transacción.
     *
     * @param string  $gameId        ID del juego.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param mixed   $datos         Datos adicionales.
     * @param boolean $isBonus       Indica si es un bono.
     *
     * @return string Respuesta en formato JSON.
     */
    public function Amend($gameId = "", $creditAmount, $roundId, $transactionId, $datos, $isBonus = false)
    {
        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
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

            /*  Obtenemos el Proveedor con el abreviado 7777gaming */
            $Proveedor = new Proveedor("", "7777GAMING");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);
            $this->transaccionApi->setIdentificador("7777GAMING" . $roundId);


            try {
                $TransaccionJuego = new TransaccionJuego("", "7777GAMING" . $roundId);
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            $ProductoMandante = new ProductoMandante("", "", $TransaccionJuego->productoId);
            /*  Obtenemos el producto con el $TransaccionJuego->productoId */
            $Producto = new Producto($ProductoMandante->productoId);

            $Game = new Game();

            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, false, false, $isBonus);

            $this->transaccionApi = $responseG->transaccionApi;


            /*  Retornamos el mensaje satisfactorio  */
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $saldo = str_replace(',', '', number_format(round($Usuario->getBalance(), 2), 2, '.', null));

            $signature = "";

            $respuesta = json_encode(
                array(
                    "timestamp" => 0,
                    "signature" => $signature,
                    "errorCode" => 1,
                    "Items" => array(
                        "externalTxId" => $responseG->transaccionId,
                        "balance" => $saldo,
                        "info" => $TransaccionJuego->transaccionId,
                        "errorCode" => 1
                    )
                )
            );


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
     * Método para acreditar un monto al saldo del usuario.
     *
     * @param string  $gameId        ID del juego.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param mixed   $datos         Datos adicionales.
     * @param boolean $isfreeSpin    Indica si es un giro gratis.
     * @param string  $type_         Tipo de operación.
     *
     * @return string Respuesta en formato JSON.
     */
    public function Credit($gameId = "", $creditAmount, $roundId, $transactionId, $datos, $isfreeSpin = false, $type_ = '')
    {
        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
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

            /*  Obtenemos el Proveedor con el abreviado 7777gaming */
            $Proveedor = new Proveedor("", "7777GAMING");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);
            $this->transaccionApi->setIdentificador("7777GAMING" . $roundId);

            try {
                $TransaccionJuego = new TransaccionJuego("", "7777GAMING" . $roundId);
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            if ($TransaccionJuego->productoId != '') {
                try {
                    $ProductoMandante = new ProductoMandante("", "", $TransaccionJuego->productoId);
                    $Producto = new Producto($ProductoMandante->productoId);
                } catch (Exception $e) {
                    $Producto = new Producto("", "DE_7777", $Proveedor->getProveedorId());
                }
            } else {
                $Producto = new Producto("", "DE_7777", $Proveedor->getProveedorId());
            }

            $Game = new Game();
            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, false, false, $isfreeSpin);

            $this->transaccionApi = $responseG->transaccionApi;

            /*  Retornamos el mensaje satisfactorio  */
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            $saldo = str_replace(',', '', number_format(round($Usuario->getBalance(), 2), 2, '.', null));

            if ($type_ == 'settle_bet') {
                $array = array(
                    "balance" => $saldo * 100,
                    "used_bonus_won_amount" => 0,
                    "bonus_balance" => 0,
                    "used_cash_won_amount" => $creditAmount * 100,
                    "error_code" => 0,
                    "error_msg" => '',
                );
            } else {
                if ($type_ == 'event_win') {
                    $array = array(
                        "balance" => $saldo * 100,
                        "bonus_balance" => 0,
                        "error_code" => 0,
                        "error_msg" => '',
                    );
                } else {
                    if ($type_ == 'notify') {
                        $array = array(
                            "balance" => $saldo * 100,
                            "bonus_balance" => 0,
                            "error_code" => 0,
                            "error_type" => "info",
                            "error_msg" => '',
                        );
                    }
                }
            }

            $signature = hash('sha256', $this->KEY . '##' . base64_encode(json_encode($array)) . '##' . $this->KEY);

            $return = array(
                "app_data" => base64_encode(json_encode($array)),
                "signature" => $signature
            );

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta($return);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return json_encode($return, JSON_PRESERVE_ZERO_FRACTION);
        } catch (Exception $e) {
            $re_ = $this->convertError($e->getCode(), $e->getMessage());
            $dat_ = json_decode($re_);
            if ($dat_->error_code == null || $dat_->error_code == '') {
                $error_code = 1;
                $error_msg = "Internal service error";
            } else {
                $error_code = $dat_->error_code;
                $error_msg = $dat_->error_msg;
            }

            if ($type_ == 'settle_bet') {
                $array = array(
                    "balance" => 1.00 * 100,
                    "used_bonus_won_amount" => 0,
                    "bonus_balance" => 0,
                    "used_cash_won_amount" => 0,
                    "error_code" => $error_code,
                    "error_msg" => $error_msg
                );
            } else {
                if ($type_ == 'event_win') {
                    $array = array(
                        "balance" => 1.00 * 100,
                        "bonus_balance" => 0,
                        "error_code" => $error_code,
                        "error_msg" => $error_msg
                    );
                } else {
                }
            }
            $signature = hash('sha256', $this->KEY . '##' . base64_encode(json_encode($array)) . '##' . $this->KEY);
            $return = array(
                "app_data" => base64_encode(json_encode($array)),
                "signature" => $signature
            );
            return json_encode($return);
        }
    }


    /**
     * Método para verificar el estado de una transacción.
     *
     * @param string  $externalTxId  ID externo de la transacción.
     * @param string  $providerTxId  ID del proveedor de la transacción.
     * @param mixed   $datas         Datos adicionales.
     * @param string  $gameId        ID del juego.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param mixed   $datos         Datos adicionales.
     * @param boolean $isBonus       Indica si es un bono.
     *
     * @return string Respuesta en formato JSON.
     */
    public function CheckTxStatus($externalTxId = "", $providerTxId = "", $datas = "", $gameId = "", $creditAmount = "", $roundId = "", $transactionId = "", $datos = "", $isBonus = false)
    {
        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
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

            /*  Obtenemos el Proveedor con el abreviado 7777gaming */
            $Proveedor = new Proveedor("", "7777GAMING");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);
            $this->transaccionApi->setIdentificador("7777GAMING" . $roundId);


            try {
                $TransaccionJuego = new TransaccionJuego("", "7777GAMING" . $roundId);
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            $ProductoMandante = new ProductoMandante("", "", $TransaccionJuego->productoId);
            /*  Obtenemos el producto con el $TransaccionJuego->productoId */
            $Producto = new Producto($ProductoMandante->productoId);

            $Game = new Game();

            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, false, false, $isBonus);

            $this->transaccionApi = $responseG->transaccionApi;


            /*  Retornamos el mensaje satisfactorio  */
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $saldo = str_replace(',', '', number_format(round($Usuario->getBalance(), 2), 2, '.', null));

            $signature = "";

            $respuesta = json_encode(
                array(
                    "timestamp" => 0,
                    "signature" => $signature,
                    "txStatus" => true,
                    "operationType" => 1,
                    "txCreationDate" => 1,
                    "externalTxId" => 1,
                    "balanceBefore" => 1,
                    "balanceAfter" => 1,
                    "currencyId" => 1,
                    "errorCode" => 1,

                )
            );


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
     * Método para convertir errores en respuestas JSON.
     *
     * @param integer $code    Código de error.
     * @param string  $message Mensaje de error.
     *
     * @return string Respuesta en formato JSON con el error.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $response = array();


        $Proveedor = new Proveedor("", "7777GAMING");

        switch ($code) {
            case 10011:
                $codeProveedor = 5;
                $messageProveedor = "Player authentication failed due to invalid, not found or expired token.";
                break;

            case 21:
                $codeProveedor = 5;
                $messageProveedor = "Player authentication failed due to invalid, not found or expired token.";
                break;

            case 22:
                $codeProveedor = 5;
                $messageProveedor = "Player authentication failed due to invalid, not found or expired token.";
                break;

            case 20001:
                $codeProveedor = 6;
                $messageProveedor = "Insufficient balance";
                break;

            case 0:
                $codeProveedor = 1;
                $messageProveedor = "Internal server error";
                break;

            case 27: //OK
                $codeProveedor = 4;
                $messageProveedor = "Requested game was not found.";
                break;

            case 28:
                $codeProveedor = 6;
                $messageProveedor = "ROUND_NOT_FOUND";
                break;

            case 29:
                $codeProveedor = 6;
                $messageProveedor = "Transaction Not Found";
                break;

            case 10001: //OK

                $codeProveedor = 0;
                $messageProveedor = "No errors";

                try {
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
                    //$TransaccionApi = new TransaccionApi("", $this->transaccionApi->getTransaccionId(), $Proveedor->getProveedorId());
                    $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

                    /*  Retornamos el mensaje satisfactorio  */
                    $response = array(
                        "error_code" => 0,
                        "balance" => $saldo * 100
                    );
                } catch (Exception $e) {
                    $codeProveedor = 1; //OK
                    $messageProveedor = "Internal service error";
                }

                break;

            case 10004:
                $codeProveedor = 1;
                $messageProveedor = "Apuesta con cancelacion antes.";
                break;

            case 10005:

                if ($this->method == 'Rollback') {
                    if ($this->token != "") {
                        try {
                            if ($this->token != "") {
                                try {
                                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                                } catch (Exception $e) {
                                    $UsuarioMandante = new UsuarioMandante($this->externalId);
                                }
                            } else {
                                $UsuarioMandante = new UsuarioMandante($this->externalId);
                            }

                            $Game = new Game();
                            $responseG = $Game->getBalance($UsuarioMandante);
                            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

                            /*  Retornamos el mensaje satisfactorio  */
                            $response = array(
                                "error_code" => 1,
                                "error_msg" => 'Bet Transaction not found',
                                "bonus_balance" => 0,
                                "balance" => $saldo * 100
                            );
                        } catch (Exception $e) {
                            if ($e->getCode() == 21 || $e->getCode() == 29) {
                                /*  Retornamos el mensaje satisfactorio  */
                                $response = array(
                                    "error_code" => 1,
                                    "error_msg" => 'Bet Transaction not found',
                                    "bonus_balance" => 0,
                                    "balance" => 1.00 * 100
                                );
                            }
                        }
                    }
                } else {
                    $codeProveedor = 1;
                    $messageProveedor = "Bet Transaction not found";
                }

                break;

            case 10014:

                $codeProveedor = 1;
                $messageProveedor = "General Error. (" . $code . ")";
                break;


            case 10010:
                $codeProveedor = 1;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 20002: //OK
                $codeProveedor = 1;
                $messageProveedor = "Hash Mismatch.";
                break;

            case 20003: //OK
                $codeProveedor = 1;
                $messageProveedor = "Player is blocked.";
                break;

            case 10017: //OK
                $codeProveedor = 1;
                $messageProveedor = "Requested currency was not found.";
                break;

            default:

                $codeProveedor = 1; //OK
                $messageProveedor = "Internal service error";
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
            $this->transaccionApi->setRespuestaCodigo("ERROR_" . $code);
            $this->transaccionApi->setRespuesta($respuesta);

            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->insert($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();
        }


        return $respuesta;
    }
}
