<?php

/**
 * Clase Evolutionoss
 *
 * Esta clase implementa la integración con el proveedor EVOLUTIONOSS para realizar
 * operaciones relacionadas con autenticación, balance, débitos, créditos y rollbacks.
 * Proporciona métodos para interactuar con el sistema del proveedor y manejar errores.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */

namespace Backend\integrations\casino;

use Backend\utils\RedisConnectionTrait;
use Exception;
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\Subproveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransaccionApi;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\dto\TransaccionJuego;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransaccionApiMySqlDAO;

/**
 * Clase Evolutionoss
 *
 * Esta clase implementa la integración con el proveedor EVOLUTIONOSS para realizar
 * operaciones relacionadas con autenticación, balance, débitos, créditos y rollbacks.
 * Proporciona métodos para interactuar con el sistema del proveedor y manejar errores.
 */
class Evolutionoss
{
    /**
     * Identificador del operador.
     *
     * @var string
     */
    private $operadorId;

    /**
     * Usuario del sistema.
     *
     * @var string
     */
    private $user;

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
     * Objeto para manejar transacciones API.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * Datos asociados a la operación.
     *
     * @var mixed
     */
    private $data;

    /**
     * Tipo de operación actual.
     *
     * @var string
     */
    private $type;

    /**
     * Constructor de la clase Evolutionoss.
     *
     * @param string $user  Usuario del sistema.
     * @param string $token Token de autenticación.
     * @param string $uid   Identificador único del usuario.
     */
    public function __construct($user = '', $token = '', $uid = "")
    {
        $this->user = $user;
        $this->token = $token;
        $this->uid = $uid;
    }

    /**
     * Autentica un token y obtiene el token de autenticación del proveedor.
     *
     * @param string $userId ID del usuario.
     * @param string $token  Token de autenticación.
     *
     * @return string|null Token de autenticación del proveedor o error.
     */
    public function autchToken($userId, $token)
    {
        try {

            if($userId != '' && $userId != '0' && $userId != null) {


                $redisParam = ['ex' => 86400];
                $cachedKey = 'CREDENTIALS'.'+EVOLUTION+' . $userId;


                /* Conecta a Redis y recupera un valor basado en un clave generada. */
                $redis = RedisConnectionTrait::getRedisInstance(
                    true,
                    'redis-13988.c39707.us-central1-mz.gcp.cloud.rlrcp.com',
                    13988,
                    'LrWXJFKjCS9PYCnprkLA1gRCqhLEcu0D',
                    'default'
                );
                if ($redis != null) {
                    $cachedValue = ($redis->get($cachedKey));
                    if (!empty($cachedValue)) {
                        $cachedValue = json_decode($cachedValue);
                        if ($cachedValue != null) {
                            return $cachedValue->TOKEN_AUTH;
                        }
                    }
                }
            }
            $Proveedor = new Proveedor("", "EVOLUTIONOSS");

            try {
                $UsuarioToken = new UsuarioToken($token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } catch (Exception $e) {
                $UsuarioMandante = new UsuarioMandante($userId);
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->usumandanteId);
            }

            $Producto = new Producto($UsuarioToken->productoId);

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $credentials = json_decode($SubproveedorMandantePais->getCredentials());



            if ($redis != null) {
                try {
                    $redis->set($cachedKey, json_encode($credentials), $redisParam);

                } catch (Exception $e) {
                }
            }

            return $credentials->TOKEN_AUTH;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }


    /**
     * Autentica un token y obtiene si es correcto o no.
     *
     * @param string $token  Token de autenticación.
     *
     * @return boolean Devuelve true o false dependiendo si existe el token en el sistema.
     */
    public function checkAuth($token)
    {
        try {

            if($token != '' && $token != '0' && $token != null) {

                $cachedKey = 'TOKEN_AUTH'.'+EVOLUTIONOSS+' . $token;


                /* Conecta a Redis y recupera un valor basado en un clave generada. */
                $redis = RedisConnectionTrait::getRedisInstance(
                    true,
                    'redis-13988.c39707.us-central1-mz.gcp.cloud.rlrcp.com',
                    13988,
                    'LrWXJFKjCS9PYCnprkLA1gRCqhLEcu0D',
                    'default'
                );
                if ($redis != null) {
                    $cachedValue = ($redis->get($cachedKey));
                    if (!empty($cachedValue)) {
                        $cachedValue = json_decode($cachedValue);
                        if ($cachedValue != null) {
                            return true;
                        }
                    }
                }
            }

            return false;

        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Realiza la autenticación del usuario y genera un token de sesión.
     *
     * @param boolean $sidUpdate Indica si se debe actualizar el SID.
     *
     * @return string JSON con el estado de la operación y el token generado.
     */
    public function Auth($sidUpdate)
    {
        $this->type = 'Auth';
        try {
            $Proveedor = new Proveedor("", "EVOLUTIONOSS");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);

            if ($this->token == "" && $this->user == "") {
                throw new Exception("Token vacio", "10011");
            }

            try {
                $UserVal = new UsuarioMandante($this->user);
            } catch (Exception $e) {
                throw new Exception("User Invalid", "10018");
            }

            if ($this->token != "") {
                try {
                    $TokenVal = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                } catch (Exception $e) {
                    throw new Exception("User Invalid", "10018");
                }
            }

            if ($this->token != "") {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                $UsuarioMandante = new UsuarioMandante($this->user);
            }

            if ($UsuarioMandante->usumandanteId == '' || $UsuarioMandante->usumandanteId == null || $UsuarioMandante->usumandanteId == '0') {
                throw new Exception("User Invalid", "10018");
            }

            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            if ($sidUpdate && $this->token == "") {
                $Producto = new Producto($UsuarioToken->productoId);
                $UsuarioToken = new UsuarioToken();
                $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
                $UsuarioToken->setProductoId($Producto->productoId);
                $UsuarioToken->setCookie('0');
                $UsuarioToken->setRequestId('0');
                $UsuarioToken->setUsucreaId(0);
                $UsuarioToken->setUsumodifId(0);
                $UsuarioToken->setUsuarioId($UsuarioMandante->usumandanteId);
                $UsuarioToken->setToken($UsuarioToken->createToken());
                $UsuarioToken->setSaldo(0);
                $UsuarioToken->setEstado('A');
                $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
                $UsuarioTokenMySqlDAO->insert($UsuarioToken);
                $UsuarioTokenMySqlDAO->getTransaction()->commit();
            }

            $return = array(
                "status" => 'OK',
                "sid" => $UsuarioToken->getToken(),
                "uuid" => $this->uid
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del usuario para un juego específico.
     *
     * @param string $gameId   Identificador del juego.
     * @param string $currency Moneda en la que se solicita el balance.
     *
     * @return string JSON con el estado de la operación, balance, bono y UUID.
     *
     * @throws Exception Si el token o usuario están vacíos, o si el usuario no es válido.
     */
    public function Balance($gameId, $currency)
    {
        $this->type = 'Balance';
        try {
            $Proveedor = new Proveedor("", "EVOLUTIONOSS");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);

            if ($this->token == "" && $this->user == "") {
                throw new Exception("Token vacio", "10011");
            }

            try {
                $UserVal = new UsuarioMandante($this->user);
            } catch (Exception $e) {
                throw new Exception("User Invalid", "10018");
            }

            try {
                $TokenVal = new UsuarioToken($this->token, $Proveedor->getProveedorId());
            } catch (Exception $e) {
                throw new Exception("User Invalid", "10018");
            }

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            if ($UsuarioMandante->usumandanteId == '' || $UsuarioMandante->usumandanteId == null || $UsuarioMandante->usumandanteId == '0') {
                throw new Exception("User Invalid", "10018");
            }

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            $Balance = floatval(number_format(round($Usuario->getBalance(), 2), 2, '.', ''));

            $return = array(
                "status" => 'OK',
                "balance" => $Balance,
                "bonus" => 0,
                "uuid" => $this->uid
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un débito en el sistema del proveedor.
     *
     * Este método procesa una transacción de débito para un usuario en un juego específico.
     * Valida el token y el usuario, verifica la moneda, y maneja posibles rollbacks.
     * Si todo es válido, crea una transacción API y realiza el débito.
     *
     * @param string  $gameId        Identificador del juego.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $roundId       Identificador de la ronda.
     * @param string  $transactionId Identificador de la transacción.
     * @param mixed   $datos         Datos adicionales para la transacción.
     * @param boolean $gameRoundEnd  Indica si la ronda del juego ha terminado.
     * @param string  $currency      Moneda en la que se realiza la transacción.
     * @param string  $type          Tipo de operación (opcional).
     *
     * @return string JSON con el estado de la operación, balance y UUID.
     *
     * @throws Exception Si el token o usuario son inválidos, si la moneda no coincide,
     *                   o si ya existe un rollback previo.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $gameRoundEnd, $currency, $type = '')
    {
        if ($type != '') {
            $this->type = $type;
        } else {
            $this->type = 'Debit';
        }

        try {
            if ($this->token == "" && $this->user == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado EVOLUTIONOSS */
            $Proveedor = new Proveedor("", "EVOLUTIONOSS");

            try {
                $UserVal = new UsuarioMandante($this->user);
            } catch (Exception $e) {
                throw new Exception("User Invalid", "10018");
            }
            try {
                $TokenVal = new UsuarioToken($this->token, $Proveedor->getProveedorId());
            } catch (Exception $e) {
                throw new Exception("User Invalid", "10018");
            }

            if ($this->token != "") {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                $UsuarioMandante = new UsuarioMandante($this->user);
            }

            if ($UsuarioMandante->usumandanteId == '' || $UsuarioMandante->usumandanteId == null || $UsuarioMandante->usumandanteId == '0') {
                throw new Exception("User Invalid", "10018");
            }

            $Game = new Game();
            $resp = $Game->autenticate($UsuarioMandante);
            if ($resp->moneda != $currency) {
                throw new Exception("Invalid currency", "10029");
            }

            /*  Obtenemos el producto con el gameId  */
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            $isRollback = false;
            try {
                $TransApi = new TransaccionApi("", "ROLLBACK" . $transactionId, $Proveedor->getProveedorId(), 'ERROR');
                if ($TransApi->tipo == 'RROLLBACK') {
                    $isRollback = true;
                }
            } catch (Exception $e) {
                $isRollback = false;
            }

            if ($isRollback) {
                throw new Exception("Rollback antes", "10004");
            } else {
                /*  Creamos la Transaccion API  */
                $this->transaccionApi = new TransaccionApi();
                $this->transaccionApi->setTransaccionId($transactionId);
                $this->transaccionApi->setTipo("DEBIT");
                $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
                $this->transaccionApi->setTValue(json_encode($datos));
                $this->transaccionApi->setUsucreaId(0);
                $this->transaccionApi->setUsumodifId(0);
                $this->transaccionApi->setValor($debitAmount);
                $this->transaccionApi->setIdentificador("EVOLUTIONOSS" . $roundId);

                $isfreeSpin = false;
                if (floatval($debitAmount) == 0) {
                    $isfreeSpin = true;
                }

                if ($gameRoundEnd == true) {
                    $End = true;
                } else {
                    $End = false;
                }

                $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $isfreeSpin, [], '', $End);

                $this->transaccionApi = $responseG->transaccionApi;

                $Balance = floatval(number_format(round($responseG->saldo, 2), 2, '.', ''));

                $return = array(
                    "status" => 'OK',
                    "balance" => $Balance,
                    "bonus" => 0,
                    "uuid" => $this->uid,
                );

                /*  Guardamos la Transaccion Api necesaria de estado OK   */
                $this->transaccionApi->setRespuesta($return);
                $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                $TransaccionApiMySqlDAO->update($this->transaccionApi);
                $TransaccionApiMySqlDAO->getTransaction()->commit();

                return json_encode($return);
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un crédito en el sistema del proveedor.
     *
     * Este método procesa una transacción de crédito para un usuario en un juego específico.
     * Valida el token y el usuario, verifica la moneda, y maneja posibles rollbacks.
     * Si todo es válido, crea una transacción API y realiza el crédito.
     *
     * @param string  $gameId        Identificador del juego (opcional).
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       Identificador de la ronda.
     * @param string  $transactionId Identificador de la transacción.
     * @param mixed   $datos         Datos adicionales para la transacción.
     * @param boolean $isBonus       Indica si el crédito es un bono (opcional).
     * @param boolean $gameRoundEnd  Indica si la ronda del juego ha terminado.
     * @param string  $currency      Moneda en la que se realiza la transacción.
     *
     * @return string JSON con el estado de la operación, balance y UUID.
     *
     * @throws Exception Si el token o usuario son inválidos, si la moneda no coincide,
     *                   o si ya existe un rollback previo.
     */
    public function Credit($gameId = "", $creditAmount, $roundId, $transactionId, $datos, $isBonus = false, $gameRoundEnd, $currency)
    {
        $this->type = 'Credit';
        try {
            if ($this->token == "" && $this->user == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado EVOLUTIONOSS */
            $Proveedor = new Proveedor("", "EVOLUTIONOSS");

            try {
                $UserVal = new UsuarioMandante($this->user);
            } catch (Exception $e) {
                throw new Exception("User Invalid", "10018");
            }

            if ($this->token != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (Exception $e) {
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                    $UsuarioMandante = new UsuarioMandante($this->user);
                }
            } else {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                $UsuarioMandante = new UsuarioMandante($this->user);
            }

            if ($UsuarioMandante->usumandanteId == '' || $UsuarioMandante->usumandanteId == null || $UsuarioMandante->usumandanteId == '0') {
                throw new Exception("User Invalid", "10018");
            }

            $Game = new Game();
            $resp = $Game->autenticate($UsuarioMandante);
            if ($currency != 'NA') {
                if ($resp->moneda != $currency) {
                    throw new Exception("Invalid currency", "10029");
                }
            }

            $isRollback = false;
            try {
                $TransaccionJuego = new TransaccionJuego("", "EVOLUTIONOSS" . $roundId);
                $TransjuegoLog = new TransjuegoLog("", $TransaccionJuego->transjuegoId, 'ROLLBACK');
                if ($TransjuegoLog->tipo == 'ROLLBACK') {
                    $isRollback = true;
                }
            } catch (Exception $e) {
                $isRollback = false;
            }

            if ($isRollback) {
                throw new Exception("Rollback antes", "10017");
            } else {
                try {
                    $TransaccionJuego = new TransaccionJuego("", "EVOLUTIONOSS" . $roundId);
                    $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                } catch (Exception $e) {
                    throw new Exception("Transaccion no existe", "10005");
                }

                if ($UsuarioMandante->usumandanteId == '' || $UsuarioMandante->usumandanteId == null || $UsuarioMandante->usumandanteId == '0') {
                    throw new Exception("User Invalid", "10018");
                }

                /*  Creamos la Transaccion API  */
                $this->transaccionApi = new TransaccionApi();
                $this->transaccionApi->setTransaccionId($transactionId);
                $this->transaccionApi->setTipo("CREDIT");
                $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
                $this->transaccionApi->setTValue(json_encode($datos));
                $this->transaccionApi->setUsucreaId(0);
                $this->transaccionApi->setUsumodifId(0);
                $this->transaccionApi->setValor($creditAmount);
                $this->transaccionApi->setIdentificador("EVOLUTIONOSS" . $roundId);

                $ProductoMandante = new ProductoMandante("", "", $TransaccionJuego->productoId);
                /*  Obtenemos el producto con el $TransaccionJuego->productoId */
                $Producto = new Producto($ProductoMandante->productoId);

                if ($gameRoundEnd == true) {
                    $End = true;
                } else {
                    $End = false;
                }

                $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $End, false, $isBonus, false);

                $this->transaccionApi = $responseG->transaccionApi;

                /*  Retornamos el mensaje satisfactorio  */
                $Balance = floatval(number_format(round($responseG->saldo, 2), 2, '.', ''));

                $return = array(
                    "status" => 'OK',
                    "balance" => $Balance,
                    "bonus" => 0,
                    "uuid" => $this->uid,
                );

                /*  Guardamos la Transaccion Api necesaria de estado OK   */
                $this->transaccionApi->setRespuesta($return);
                $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                $TransaccionApiMySqlDAO->update($this->transaccionApi);
                $TransaccionApiMySqlDAO->getTransaction()->commit();

                return json_encode($return);
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un rollback en el sistema del proveedor.
     *
     * Este método procesa una transacción de rollback para un usuario en un juego específico.
     * Valida el token y el usuario, verifica si ya existe un rollback previo, y maneja posibles errores.
     * Si todo es válido, crea una transacción API y realiza el rollback.
     *
     * @param float   $rollbackAmount Monto a revertir.
     * @param string  $roundId        Identificador de la ronda.
     * @param string  $transactionId  Identificador de la transacción.
     * @param string  $player         Identificador del jugador.
     * @param mixed   $datos          Datos adicionales para la transacción.
     * @param boolean $gameRoundEnd   Indica si la ronda del juego ha terminado.
     * @param string  $gameId         Identificador del juego.
     *
     * @return string JSON con el estado de la operación, balance y UUID.
     *
     * @throws Exception Si el token o usuario son inválidos, si ya existe un rollback previo,
     *                   o si la transacción no es válida.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos, $gameRoundEnd, $gameId)
    {
        $this->type = 'Rollback';
        try {
            /*  Obtenemos el Proveedor con el abreviado EVOLUTIONOSS */
            $Proveedor = new Proveedor("", "EVOLUTIONOSS");

            try {
                $UserVal = new UsuarioMandante($this->user);
            } catch (Exception $e) {
                throw new Exception("User Invalid", "10018");
            }
            try {
                $TokenVal = new UsuarioToken($this->token, $Proveedor->getProveedorId());
            } catch (Exception $e) {
                throw new Exception("User Invalid", "10018");
            }

            if ($this->token != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (Exception $e) {
                    $UsuarioMandante = new UsuarioMandante($this->user);
                    //$UsuarioMandante = new UsuarioMandante("", $this->externalId, $Usuario->mandante);
                }
            } else {
                $UsuarioMandante = new UsuarioMandante($this->user);
            }

            if ($UsuarioMandante->usumandanteId == '' || $UsuarioMandante->usumandanteId == null || $UsuarioMandante->usumandanteId == '0') {
                throw new Exception("User Invalid", "10018");
            }

            $isRollback = false;
            try {
                $TransaccionJuego = new TransaccionJuego("", "EVOLUTIONOSS" . $roundId);
                $TransjuegoLog = new TransjuegoLog("", $TransaccionJuego->transjuegoId, 'ROLLBACK');
                if ($TransjuegoLog->tipo == 'ROLLBACK') {
                    $isRollback = true;
                }
            } catch (Exception $e) {
                $isRollback = false;
            }

            if ($isRollback) {
                throw new Exception("Rollback antes", "10017");
            } else {
                $aggtrans = false;
                try {
                    $TransaccionJuego = new TransaccionJuego('', "EVOLUTIONOSS" . $roundId);
                    if ($TransaccionJuego->getValorPremio() != 0) {
                        $aggtrans = false;
                    }
                } catch (Exception $e) {
                    $aggtrans = false;
                }

                if ($aggtrans) {
                    throw new Exception("Ronda cerrada", "10016");
                } else {
                    /*  Creamos la Transaccion API  */
                    $this->transaccionApi = new TransaccionApi();
                    $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
                    $this->transaccionApi->setTipo("ROLLBACK");
                    $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
                    $this->transaccionApi->setTValue($datos);
                    $this->transaccionApi->setUsucreaId(0);
                    $this->transaccionApi->setUsumodifId(0);
                    $this->transaccionApi->setValor($rollbackAmount);

                    try {
                        $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
                        $TransjuegoLog = new TransjuegoLog("", "", "", $transactionId . '_' . $Producto->getSubproveedorId(), $Producto->getSubproveedorId());
                        $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);
                        $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());

                        if (strpos($TransjuegoLog->getTipo(), 'DEBIT') !== false) {
                            $this->transaccionApi->setIdentificador($TransaccionJuego->getTicketId());
                        } else {
                            throw new Exception("Transaccion no es Debit", "10006");
                        }
                    } catch (Exception $e) {
                        throw new Exception("Transaccion no existe", "10005");
                    }

                    if ($gameRoundEnd == true) {
                        $end = 'I';
                    } else {
                        $end = 'A';
                    }

                    $Game = new Game();
                    $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false, '', '', '', '', '', $end);

                    $this->transaccionApi = $responseG->transaccionApi;

                    $Balance = floatval(number_format(round($responseG->saldo, 2), 2, '.', ''));

                    $return = array(
                        "status" => 'OK',
                        "balance" => $Balance,
                        "bonus" => 0,
                        "uuid" => $this->uid,
                    );

                    /*  Guardamos la Transaccion Api necesaria de estado OK   */
                    $this->transaccionApi->setRespuesta($return);
                    $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                    $TransaccionApiMySqlDAO->update($this->transaccionApi);
                    $TransaccionApiMySqlDAO->getTransaction()->commit();

                    return json_encode($return);
                }
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Converts an error code and message into a standardized response.
     *
     * This method maps internal error codes to provider-specific error codes and messages.
     * It also retrieves the user's balance if applicable and formats the response as JSON.
     * If a transaction API object exists, it updates the transaction with the error details.
     *
     * @param integer $code    The internal error code.
     * @param string  $message The error message.
     *
     * @return string JSON response containing the error status, balance, bonus, and UUID.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $response = array();

        $Proveedor = new Proveedor("", "EVOLUTIONOSS");

        if ($this->token != "") {
            try {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } catch (Exception $e) {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                $UsuarioMandante = new UsuarioMandante($this->user);
            }
        } else {
            $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
            $UsuarioMandante = new UsuarioMandante($this->user);
        }

        try {
            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);
            $Balance = floatval(number_format(round($responseG->saldo, 2), 2, '.', ''));
        } catch (Exception $e) {
            $Balance = null;
        }

        switch ($code) {
            case 10002:
                $codeProveedor = 1;
                $messageProveedor = "INSUFFICIENT_FUNDS";
                break;

            case 10003:
                $codeProveedor = 1;
                $messageProveedor = "INSUFFICIENT_FUNDS";
                break;

            case 10011:
                $codeProveedor = 6;
                $messageProveedor = "INVALID_SID";
                break;

            case 21:
                $codeProveedor = 6;
                $messageProveedor = "INVALID_SID";
                break;

            case 10030:
                $codeProveedor = 6;
                $messageProveedor = "INVALID_SID";
                break;

            case 10013:
                $codeProveedor = 7;
                $messageProveedor = "ACCOUNT_LOCKED";
                break;

            case 20003:
                $codeProveedor = 7;
                $messageProveedor = "ACCOUNT_LOCKED";
                break;

            case 10005:
                $codeProveedor = 9;
                $messageProveedor = "BET_DOES_NOT_EXIST";
                break;

            case 22:
                $codeProveedor = 7;
                $messageProveedor = "ACCOUNT_LOCKED";
                break;

            case 50030:
                $codeProveedor = 9;
                $messageProveedor = "UNKNOWN_ERROR";
                break;

            case 20001:
                $codeProveedor = 3;
                $messageProveedor = "INSUFFICIENT_FUNDS";
                break;

            case 0:
                $codeProveedor = 1;
                $messageProveedor = "UNKNOWN_ERROR";
                break;

            case 27:
                $codeProveedor = 1;
                $messageProveedor = "UNKNOWN_ERROR";
                break;

            case 10041:
                $codeProveedor = 1;
                $messageProveedor = "UNKNOWN_ERROR";
                break;

            case 28:
                $codeProveedor = 1;
                if ($this->type == 'Rollback') {
                    $messageProveedor = "BET_DOES_NOT_EXIST";
                } else {
                    $messageProveedor = "UNKNOWN_ERROR";
                }
                break;

            case 29:
                $codeProveedor = 1;
                $messageProveedor = "UNKNOWN_ERROR";
                break;

            case 10001:
                $codeProveedor = 0;
                if ($this->type == 'Credit') {
                    $messageProveedor = "BET_ALREADY_SETTLED";
                } else {
                    if ($this->type == 'promo_payout') {
                        $messageProveedor = "BET_ALREADY_SETTLED";
                    } else {
                        if ($this->type == 'Rollback') {
                            $messageProveedor = "BET_ALREADY_SETTLED";
                        } else {
                            $messageProveedor = "BET_ALREADY_EXIST";
                        }
                    }
                }
                break;

            case 10016:
                $codeProveedor = 0;
                $messageProveedor = "BET_ALREADY_SETTLED";
                break;

            case 10017:
                $codeProveedor = 0;
                $messageProveedor = "BET_ALREADY_SETTLED";
                break;

            case 10018:
                $codeProveedor = 0;
                $messageProveedor = "INVALID_PARAMETER";
                break;

            case 10027:
                $codeProveedor = 0;
                $messageProveedor = "BET_ALREADY_SETTLED";
                break;

            case 10004:
                $codeProveedor = 1;
                $messageProveedor = "FINAL_ERROR_ACTION_FAILED";
                break;

            case 10014:
                $codeProveedor = 1;
                $messageProveedor = "BET_ALREADY_EXIST";
                break;

            default:
                $codeProveedor = 1;
                $messageProveedor = "UNKNOWN_ERROR";
                break;
        }

        if ($messageProveedor == "INVALID_PARAMETER") {
            $Balance = null;
        }

        $respuesta = json_encode(array_merge($response, array(
            "status" => $messageProveedor,
            "balance" => $Balance,
            "bonus" => 0,
            "uuid" => $this->uid,
        )));

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
