<?php

/**
 * Clase AirDice para la integración con el proveedor de juegos AIRDICE.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;

use Backend\dto\SubproveedorMandantePais;
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
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionApiMySqlDAO;

/**
 * Esta clase proporciona métodos para manejar la autenticación, balance, débitos, créditos,
 * y rollbacks relacionados con el proveedor de juegos AIRDICE.
 */
class AirDice
{
    /**
     * Identificador del operador.
     *
     * @var mixed
     */
    private $operadorId;

    /**
     * Nombre de usuario o identificador del usuario.
     *
     * @var string
     */
    private $user;

    /**
     * Token de autenticación del usuario.
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
     * Datos adicionales relacionados con la transacción.
     *
     * @var mixed
     */
    private $data;

    /**
     * Tipo de operación o método actual.
     *
     * @var string
     */
    private $type;

    /**
     * Identificador de la transacción.
     *
     * @var string
     */
    private $transactionId;

    /**
     * Acción original asociada a la transacción.
     *
     * @var string
     */
    private $originalAction;

    /**
     * Nombre de usuario para autenticación con el proveedor.
     *
     * @var string
     */
    private $UserName;

    /**
     * Contraseña para autenticación con el proveedor.
     *
     * @var string
     */
    private $Password;

    /**
     * Identificador del usuario en el sistema.
     *
     * @var string
     */
    private $usuarioId;

    /**
     * Identificador del juego.
     *
     * @var string
     */
    private $gameId;

    /**
     * Constructor de la clase AirDice.
     *
     * @param string $token    Token del usuario.
     * @param string $user     Identificador del usuario.
     * @param string $game_ref Referencia del juego.
     */
    public function __construct($token = '', $user = '', $game_ref = '')
    {
        $this->user = $user;
        $this->token = $token;
        $this->token = $token;
        $this->usuarioId = $user;
        $this->gameId = $game_ref;

        $Proveedor = new Proveedor("", "AIRDICE");

        if ($this->token != "") {
            try {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } catch (Exception $e) {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->user);
                $UsuarioToken = new UsuarioToken('', $Proveedor->getProveedorId(), $UsuarioMandante->usumandanteId);
            }
        } else {
            /*  Obtenemos el Usuario Mandante con el Usuario Token */
            $UsuarioMandante = new UsuarioMandante($this->user);
            $UsuarioToken = new UsuarioToken('', $Proveedor->getProveedorId(), $UsuarioMandante->usumandanteId);
        }

        try {
            $Producto = new Producto($UsuarioToken->productoId);
            $SubproveedorId = $Producto->subproveedorId;
        } catch (Exception $e) {
            $Subproveedor = new Subproveedor('', 'AIRDICE');
            $SubproveedorId = $Subproveedor->subproveedorId;
        }

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $SubproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $this->UserName = $credentials->CUSTOMER;
        $this->Password = $credentials->CUSTOMER_KEY;
    }

    /**
     * Obtiene el ID del operador.
     *
     * @return mixed ID del operador.
     */
    public function getOperadorId()
    {
        return $this->operadorId;
    }

    /**
     * Autentica al usuario con las credenciales proporcionadas.
     *
     * @param string $game_ref Referencia del juego.
     * @param string $login    Nombre de usuario.
     * @param string $password Contraseña del usuario.
     *
     * @return string Respuesta en formato JSON.
     * @throws Exception Si ocurre un error durante la autenticación.
     */
    public function Auth($game_ref, $login, $password)
    {
        $this->type = 'GetAccountDetails';
        try {
            if ($login === $this->UserName && $password === $this->Password) {
                $Proveedor = new Proveedor("", "AIRDICE");
            } else {
                throw new Exception("Credenciales incorrectas", "20002");
            }

            $Proveedor = new Proveedor("", "AIRDICE");

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

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $Producto = new Producto("", $game_ref, $Proveedor->getProveedorId());

            if ($this->token != "") {
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

            $Balance = intval(round($Usuario->getBalance(), 2) * 100);

            $return = [
                "method" => $this->type,
                "stat" => 0,
                "token" => $UsuarioToken->getToken(),
                "player_id" => $UsuarioMandante->usumandanteId,
                "currency" => $UsuarioMandante->moneda,
            ];

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del usuario autenticado.
     *
     * @param string $login    Nombre de usuario.
     * @param string $password Contraseña del usuario.
     *
     * @return string Respuesta en formato JSON con el balance.
     * @throws Exception Si ocurre un error al obtener el balance.
     */
    public function Balance($login, $password)
    {
        $this->type = 'GetBalance';
        try {
            if ($login === $this->UserName && $password === $this->Password) {
                $Proveedor = new Proveedor("", "AIRDICE");
            } else {
                throw new Exception("Credenciales incorrectas", "20002");
            }

            $Proveedor = new Proveedor("", "AIRDICE");

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

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Balance = intval(round($Usuario->getBalance(), 2) * 100);

            $return = array(
                "method" => $this->type,
                "stat" => 0,
                "currency" => $UsuarioMandante->moneda,
                "balance" => $Balance,
                "freespins" => 0,
                "freestake" => 0,
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
     * @param boolean $isfreeSpin    Indica si es un giro gratis.
     * @param string  $UserName      Nombre de usuario.
     * @param string  $Password      Contraseña del usuario.
     *
     * @return string Respuesta en formato JSON.
     * @throws Exception Si ocurre un error durante el débito.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $isfreeSpin = false, $UserName, $Password)
    {
        $this->type = 'PlaceBet';
        $this->transactionId = $transactionId;

        try {
            if ($UserName === $this->UserName && $Password === $this->Password) {
                $Proveedor = new Proveedor("", "AIRDICE");
            } else {
                throw new Exception("Credenciales incorrectas", "20002");
            }

            if ($this->token == "" && $this->user == "") {
                throw new Exception("Token vacio", "10011");
            }

            $Proveedor = new Proveedor("", "AIRDICE");

            if ($this->token != "") {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                $UsuarioMandante = new UsuarioMandante($this->user);
            }

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $balance_old = intval(round($Usuario->getBalance() * 100, 2));
            $balance_old = number_format($balance_old / 100, 2, '.', '');

            $diferencia = $debitAmount - $balance_old;
            $diferencia = round($diferencia, 2);
            if ($diferencia === 0.01) {
                throw new Exception("Maximo amount", "1000203");
            }

            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            $isRollback = false;
            try {
                $TransApi = new TransaccionApi("", "ROLLBACK" . $transactionId, $Proveedor->getProveedorId(), 'ERROR');
                if ($TransApi->tipo == 'ROLLBACK') {
                    $isRollback = true;
                }
            } catch (Exception $e) {
                $isRollback = false;
            }

            if ($isRollback) {
                throw new Exception("Rollback antes", "10004");
            } else {
                $this->transaccionApi = new TransaccionApi();
                $this->transaccionApi->setTransaccionId($transactionId);
                $this->transaccionApi->setTipo("DEBIT");
                $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
                $this->transaccionApi->setTValue(json_encode($datos));
                $this->transaccionApi->setUsucreaId(0);
                $this->transaccionApi->setUsumodifId(0);
                $this->transaccionApi->setValor($debitAmount);
                $this->transaccionApi->setIdentificador("AIRDICE" . $roundId);

                $Game = new Game();
                $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $isfreeSpin, [], true);

                $this->transaccionApi = $responseG->transaccionApi;

                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                $Balance = intval(round($Usuario->getBalance(), 2) * 100);

                $return = array(
                    "method" => $this->type,
                    "stat" => 0,
                    "currency" => $UsuarioMandante->moneda,
                    "currency_base" => 100,
                    "balance" => $Balance,
                    "freespins" => 0,
                    "freestake" => 0,
                    "ext_trans_id" => $responseG->transaccionId,
                    "promo" => "",
                );

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
     * Realiza un crédito en la cuenta del usuario.
     *
     * @param mixed   $Producto      Producto relacionado con el crédito.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param array   $datos         Datos adicionales.
     * @param boolean $isfreeSpin    Indica si es un giro gratis.
     * @param string  $UserName      Nombre de usuario.
     * @param string  $Password      Contraseña del usuario.
     * @param boolean $EndRound      Indica si la ronda ha terminado.
     *
     * @return string Respuesta en formato JSON.
     * @throws Exception Si ocurre un error durante el crédito.
     */
    public function Credit($Producto, $creditAmount, $roundId, $transactionId, $datos, $isfreeSpin = false, $UserName, $Password, $EndRound = false)
    {
        $this->type = 'AwardWinnings';

        $this->transactionId = $transactionId;

        try {
            if ($UserName === $this->UserName && $Password === $this->Password) {
                $Proveedor = new Proveedor("", "AIRDICE");
            } else {
                throw new Exception("Credenciales incorrectas", "20002");
            }

            if ($this->token == "" && $this->user == "") {
                throw new Exception("Token vacio", "10011");
            }


            /*  Obtenemos el Proveedor con el abreviado AIRDICE */
            $Proveedor = new Proveedor("", "AIRDICE");

            if ($this->token != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (Exception $e) {
                    $UsuarioMandante = new UsuarioMandante($this->user);
                }
            } else {
                $UsuarioMandante = new UsuarioMandante($this->user);
            }

            $isRollback = false;

            if ($isRollback) {
                throw new Exception("Rollback antes", "100177");
            } else {
                try {
                    $TransaccionJuego = new TransaccionJuego("", "AIRDICE" . $roundId);
                    $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                } catch (Exception $e) {
                    throw new Exception("Transaccion no existe", "10005");
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
                $this->transaccionApi->setIdentificador("AIRDICE" . $roundId);

                $ProductoMandante = new ProductoMandante("", "", $TransaccionJuego->productoId);
                /*  Obtenemos el producto con el $TransaccionJuego->productoId */
                $Producto = new Producto($ProductoMandante->productoId);

                $Game = new Game();
                $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $EndRound, false, $isfreeSpin, false);

                $this->transaccionApi = $responseG->transaccionApi;

                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                $Balance = intval(round($Usuario->getBalance(), 2) * 100);

                $return = array(
                    "method" => $this->type,
                    "stat" => 0,
                    "currency" => $UsuarioMandante->moneda,
                    "currency_base" => 100,
                    "balance" => $Balance,
                    "freespins" => "",
                    "freestake" => "",
                    "ext_trans_id" => $responseG->transaccionId,
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
     * Realiza un rollback de una transacción.
     *
     * @param float   $rollbackAmount Monto a revertir.
     * @param string  $roundId        ID de la ronda.
     * @param string  $transactionId  ID de la transacción.
     * @param array   $datos          Datos adicionales.
     * @param boolean $gameRoundEnd   Indica si la ronda del juego ha terminado.
     * @param string  $UserName       Nombre de usuario.
     * @param string  $Password       Contraseña del usuario.
     *
     * @return string Respuesta en formato JSON.
     * @throws Exception Si ocurre un error durante el rollback.
     */
    public function Rollback($rollbackAmount = "", $roundId, $transactionId, $datos, $gameRoundEnd, $UserName, $Password)
    {
        $this->type = 'CancelTransaction';

        $this->transactionId = $transactionId;

        $data = json_decode($datos);
        $gameId = $data->game_ref;

        try {
            if ($UserName === $this->UserName && $Password === $this->Password) {
                $Proveedor = new Proveedor("", "AIRDICE");
            } else {
                throw new Exception("Credenciales incorrectas", "20002");
            }

            /*  Obtenemos el Proveedor con el abreviado AIRDICE */
            $Proveedor = new Proveedor("", "AIRDICE");

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

            $isRollback = false;

            if ($isRollback) {
                throw new Exception("Rollback antes", "10001");
            } else {
                $aggtrans = false;
                try {
                    $TransaccionJuego = new TransaccionJuego('', "AIRDICE" . $roundId);
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
                    $this->transaccionApi->setTipo("ROLLBACK");
                    $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
                    $this->transaccionApi->setTValue(json_encode($datos));
                    $this->transaccionApi->setUsucreaId(0);
                    $this->transaccionApi->setUsumodifId(0);
                    $this->transaccionApi->setValor($rollbackAmount);

                    try {
                        $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
                        $TransjuegoLog = new TransjuegoLog("", "", "", $this->transactionId . '_' . $Producto->subproveedorId, $Producto->subproveedorId);
                        $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);
                        $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());

                        if (strpos($TransjuegoLog->getTipo(), 'DEBIT') !== false || strpos($TransjuegoLog->getTipo(), 'CREDIT') !== false) {
                            $transId = explode("_", $TransjuegoLog->transaccionId);
                            $transId = $transId[0];
                            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transId);
                            $this->transaccionApi->setIdentificador($TransaccionJuego->getTicketId());
                        } else {
                            throw new Exception("Transaccion no es Debit", "10006");
                        }
                    } catch (Exception $e) {
                        throw new Exception("Transaccion no existe", "10005");
                    }
                    $this->transaccionApi->setProductoId($TransaccionJuego->getProductoId());

                    $Game = new Game();

                    if ($gameRoundEnd == true) {
                        $end = 'I';
                    } else {
                        $end = 'A';
                    }

                    $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false, '', '', '', true, '', $end);

                    $this->transaccionApi = $responseG->transaccionApi;

                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                    $Balance = intval(round($Usuario->getBalance(), 2) * 100);

                    $return = array(
                        "method" => $this->type,
                        "stat" => 0,
                        "currency" => $UsuarioMandante->moneda,
                        "currency_base" => 100,
                        "balance" => $Balance,
                        "freespins" => "",
                        "freestake" => "",
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
     * Convierte un error en una respuesta JSON.
     *
     * @param integer $code             Código del error.
     * @param string  $messageProveedor Mensaje del proveedor.
     *
     * @return string Respuesta en formato JSON con el error.
     */
    public function convertError($code, $messageProveedor)
    {
        $response = array();

        $Proveedor = new Proveedor("", "AIRDICE");
        $Subproveedor = new Subproveedor("", "AIRDICE");

        if ($this->token != "") {
            try {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } catch (Exception $e) {
                $UsuarioMandante = new UsuarioMandante($this->user);
            }
        } else {
            $UsuarioMandante = new UsuarioMandante($this->user);
        }

        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
        $Balance = intval(round($Usuario->getBalance(), 2) * 100);

        switch ($code) {
            case 10002:
                $codeProveedor = 6;
                $messageProveedor = "Not enough money to place bet, or no suitable free credit available";
                break;

            case 1000203:
                $codeProveedor = 6;
                $messageProveedor = "Player has not enough funds to process an action";
                break;

            case 24:
                $codeProveedor = 2;
                $messageProveedor = "Authentication failed";
                break;

            case 10018:
                $codeProveedor = 101;
                $messageProveedor = "Player is invalid";
                break;

            case 10011:
                $codeProveedor = 105;
                $messageProveedor = "Player reached customized bet limit";
                break;

            case 20001:
                $codeProveedor = 5;
                $messageProveedor = "User spend limit is exceeded.";
                break;

            case 21:
                $codeProveedor = 107;
                $messageProveedor = "Game is forbidden to the player";
                break;

            case 24:
                $codeProveedor = 10;
                $messageProveedor = "User account is frozen/disabled";
                break;

            case 21010:
                $codeProveedor = 153;
                $messageProveedor = "Game is not available in Player's country";
                break;

            case 100000:
                $codeProveedor = 400;
                $messageProveedor = "Bad request. (Bad formatted json)";
                break;

            case 20002:
                $codeProveedor = 2;
                $messageProveedor = "Authentication failed.";
                break;

            case 100000:
                $codeProveedor = 404;
                $messageProveedor = "Not found";
                break;

            case 21010:
                $codeProveedor = 405;
                $messageProveedor = "Game is not available to casino";
                break;

            case 100000:
                $codeProveedor = 500;
                $messageProveedor = "Unknown error";
                break;

            case 300000:
                $codeProveedor = 600;
                $messageProveedor = "Game provider doesn't provide freespins";
                break;

            case 300000:
                $codeProveedor = 601;
                $messageProveedor = "Impossible to issue freespins in requested game";
                break;

            case 26:
                $codeProveedor = 602;
                $messageProveedor = "Should provide at least one game to issue freespins";
                break;

            case 300025:
                $codeProveedor = 603;
                $messageProveedor = "Bad expiration date. Expiration date should be in future and freespins shouldn't be active for more than 1 month";
                break;

            case 300001:
                $codeProveedor = 605;
                $messageProveedor = "Can't change issue state from its current to requested";
                break;

            case 300001:
                $codeProveedor = 606;
                $messageProveedor = "Can't change issue state when issue status is not synced";
                break;

            case 300000:
                $codeProveedor = 607;
                $messageProveedor = "Can't issue one freespin issue at different game providers";
                break;

            case 100011:
                $codeProveedor = 611;
                $messageProveedor = "Freespins issue has already expired";
                break;

            case 100011:
                $codeProveedor = 620;
                $messageProveedor = "Freespins issue can't be canceled";
                break;

            case 10001:
                $codeProveedor = 101;
                $messageProveedor = "Bad request. (Bad formatted json)";

                switch ($this->type) {
                    case "bet":
                        $transjuego = new TransjuegoLog("", "", "", $this->transactionId . '_' . $Subproveedor->subproveedorId, $Subproveedor->subproveedorId);

                        $result = array(
                            "method" => $this->type,
                            "stat" => 0,
                            "currency" => $UsuarioMandante->moneda,
                            "currency_base" => 100,
                            "balance" => $Balance,
                            "freespins" => "",
                            "freestake" => "",
                            "ext_trans_id" => $transjuego->transjuegologId,
                        );
                        break;

                    case "AwardWinnings":
                        $transjuego = new TransjuegoLog("", "", "", $this->transactionId . '_' . $Subproveedor->subproveedorId, $Subproveedor->subproveedorId);

                        $result = array(
                            "method" => $this->type,
                            "stat" => 0,
                            "currency" => $UsuarioMandante->moneda,
                            "currency_base" => 100,
                            "balance" => $Balance,
                            "freespins" => "",
                            "freestake" => "",
                            "ext_trans_id" => $transjuego->transjuegologId,
                        );
                        break;

                    case "CancelTransaction":
                        $transjuego = new TransjuegoLog("", "", "", 'ROLLBACK' . $this->transactionId . '_' . $Subproveedor->subproveedorId, $Subproveedor->subproveedorId);
                        $result = array(
                            "method" => $this->type,
                            "stat" => 0,
                            "currency" => $UsuarioMandante->moneda,
                            "currency_base" => 100,
                            "balance" => $Balance,
                            "freespins" => "",
                            "freestake" => "",
                        );
                        break;

                    case "PlaceBet":
                        $transjuego = new TransjuegoLog("", "", "", $this->transactionId . '_' . $Subproveedor->subproveedorId, $Subproveedor->subproveedorId);
                        $result = array(
                            "method" => $this->type,
                            "stat" => 0,
                            "currency" => $UsuarioMandante->moneda,
                            "currency_base" => 100,
                            "balance" => $Balance,
                            "freespins" => 0,
                            "freestake" => 0,
                            "ext_trans_id" => $transjuego->transjuegologId,
                            "promo" => "",
                        );
                        break;
                }

            case 10017:
                $codeProveedor = 101;
                $messageProveedor = "Player is invalid";
                break;

            case 100177:
                $codeProveedor = 400;
                $messageProveedor = "Bad request. (Bad formatted json)";
                break;

            case 10005:
                switch ($this->type) {
                    case "CancelTransaction":
                        $result = array(
                            "method" => $this->type,
                            "stat" => 13,
                            "currency" => $UsuarioMandante->moneda,
                            "currency_base" => 100,
                            "balance" => $Balance,
                            "freespins" => "",
                            "freestake" => "",
                            "ext_trans_id" => "",
                            "promo" => "",
                        );
                        break;
                    default:
                        $codeProveedor = 400;
                        $messageProveedor = "Bad request. (Bad formatted json)";
                        break;
                }
            default:
                $codeProveedor = 500;
                $messageProveedor = "Unknown error";
                break;
        }

        if ($result != '') {
            $respuesta = json_encode($result);
        } else {
            http_response_code(400);
            $respuesta = json_encode(array_merge($response, array(
                "code" => $codeProveedor,
                "message" => $messageProveedor,
                "balance" => $Balance
            )));
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
