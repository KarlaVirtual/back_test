<?php

/**
 * Clase Zenith
 *
 * Esta clase proporciona métodos para interactuar con la integración de casino "Zenith".
 * Incluye funcionalidades para obtener firmas, consultar balances, realizar débitos, créditos y rollbacks,
 * así como manejar errores y convertirlos en respuestas estándar.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;

use DateTime;
use Exception;
use DateTimeZone;
use Backend\dto\Pais;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Proveedor;
use Backend\dto\PuntoVenta;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioToken;
use Backend\dto\TransjuegoLog;
use Backend\dto\UsuarioPerfil;
use Backend\dto\TransaccionApi;
use Backend\dto\UsuarioSession;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\ProductoMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\UsuarioHistorial;
use Backend\dto\TransaccionProducto;
use Backend\dto\UsuarioConfiguracion;
use Backend\mysql\UsuarioTokenMySqlDAO;

use Backend\websocket\WebsocketUsuario;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransaccionApiMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;

/**
 * Clase Zenith
 *
 * Esta clase contiene métodos para interactuar con la integración de casino "Zenith".
 * Proporciona funcionalidades como obtener firmas, consultar balances, realizar débitos,
 * créditos y rollbacks, además de manejar errores y convertirlos en respuestas estándar.
 */
class Zenith
{
    /**
     * Usuario asociado a la transacción.
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
     * Tipo de operación realizada (e.g., Debit, Credit, Rollback).
     *
     * @var string
     */
    private $type;

    /**
     * Moneda utilizada en la transacción.
     *
     * @var string
     */
    private $currency;

    /**
     * Identificador único de la transacción.
     *
     * @var string
     */
    private $transactionId;

    /**
     * Identificador de rastreo para la operación.
     *
     * @var string
     */
    private $traceId;

    /**
     * Constructor.
     * 
     * Constructor de la clase Zenith.
     *
     * @param string $currency Moneda utilizada.
     * @param string $user     Usuario asociado.
     * @param string $token    Token de autenticación.
     * @param string $traceId  ID de rastreo.
     */
    public function __construct($currency = "", $user = "", $token = "", $traceId = "")
    {
        $this->user = $user;
        $this->token = $token;
        $this->currency = $currency;
        $this->traceId = $traceId;
    }

    /**
     * Funcion getSign.
     * 
     * Funcion para aobtener el signature.
     *
     * @param string $data       Moneda utilizada en las transacciones.
     * @param string $signHeader Nombre de usuario.
     * 
     * @param return $sign Identificador de seguimiento.
     */
    public function getSign($data = "", $signHeader)
    {

        $Proveedor = new Proveedor("", "ZENITH");
        try {
            if (!is_numeric($this->user)) {
                throw new Exception("Usuario inválido", 10021);
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
        if ($this->token != "") {
            try {

                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } catch (Exception $e) {

                $UsuarioMandante = new UsuarioMandante($this->user);
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->usumandanteId);
            }
        } else {

            $UsuarioMandante = new UsuarioMandante($this->user);
            $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->usumandanteId);
        }

        try {
            $Producto = new Producto($UsuarioToken->productoId);
            $SubproveedorId = $Producto->subproveedorId;
        } catch (Exception $e) {
            $Subproveedor = new Subproveedor('', 'ZENITH');
            $SubproveedorId = $Subproveedor->subproveedorId;
        }

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $SubproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $SECRET_KEY = $credentials->SECRET_KEY;
        $encodedData = json_encode($data);

        $signature = hash_hmac('sha256', $encodedData, $SECRET_KEY);

        if ($signHeader == "") {
            return $signHeader;
        }

        try {
            if ($signature !== $signHeader) {
                throw new Exception("SC_INVALID_SIGNATURE", "20002");
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
        return $signature;
    }

    /**
     * Consulta el balance del usuario.
     *
     * @return string Respuesta en formato JSON con el balance o error.
     */
    public function Balance()
    {
        $this->type = "Balance";

        try {
            $Proveedor = new Proveedor("", "ZENITH");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("Balance");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);

            $tokenInvalido = false;
            $userInvalido = false;

            if ($this->token != "") {
                try {

                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                } catch (Exception $e) {
                    $tokenInvalido = true;
                }
                if (!$tokenInvalido && $UsuarioToken !== null) {
                    try {
                        $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                    } catch (Exception $e) {
                        $tokenInvalido = true;
                    }
                }
            }
            if ($this->user != "") {
                try {
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                } catch (Exception $e) {
                    $userInvalido = true;
                }

                if (!$userInvalido && $UsuarioToken !== null) {
                    try {
                        $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                    } catch (Exception $e) {
                        $userInvalido = true;
                    }
                }
            }

            if ($this->token == "") {
                $tokenInvalido = true;
            }

            if ($this->user == "") {
                $userInvalido = true;
            }

            if ($this->user == "" && $this->token == "") {
                $userInvalido = true;
                $tokenInvalido = true;
            }


            if ($userInvalido) {
                throw new Exception("Usuario inválido", 10021);
            }

            if ($UsuarioMandante->moneda != $this->currency) {
                throw new Exception("SC_WRONG_CURRENCY", 10017);
            }

            $Producto = new Producto($UsuarioToken->productoId);
            $Subproveedor = new Subproveedor($Producto->subproveedorId);
            $Game = new Game();

            $responseG = $Game->getBalance($UsuarioMandante, $Subproveedor->subproveedorId, $UsuarioToken->productoId);

            $Balance = round($responseG->saldo, 2);

            if ($Balance === null || $Balance == 0) {
                throw new Exception("SC_INSUFFICIENT_FUNDS", 10020);
            }

            $return = array(
                "traceId" => $this->traceId,
                "status" => "SC_OK",
                "data" => array(
                    "username" => $this->user,
                    "currency" => $this->currency,
                    "balance" => $Balance
                )
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el End del usuario.
     *
     * Valida el usuario y el token antes de consultar el saldo.
     *
     * @return string roundId con el balance del usuario o un mensaje de error.
     * @throws Exception Si los parámetros son inválidos.
     */
    public function End($roundId)
    {
        $this->type = "Balance";

        try {
            $Proveedor = new Proveedor("", "ZENITH");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId(0);
            $this->transaccionApi->setTipo("Balance");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);

            $tokenInvalido = false;
            $userInvalido = false;

            if ($this->token != "") {
                try {

                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                } catch (Exception $e) {
                    $tokenInvalido = true;
                }
                if (!$tokenInvalido && $UsuarioToken !== null) {
                    try {
                        $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                    } catch (Exception $e) {
                        $tokenInvalido = true;
                    }
                }
            }
            if ($this->user != "") {
                try {
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                } catch (Exception $e) {
                    $userInvalido = true;
                }

                if (!$userInvalido && $UsuarioToken !== null) {
                    try {
                        $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                    } catch (Exception $e) {
                        $userInvalido = true;
                    }
                }
            }

            if ($this->token == "") {
                $tokenInvalido = true;
            }

            if ($this->user == "") {
                $userInvalido = true;
            }

            if ($userInvalido) {
                throw new Exception("Usuario inválido", 10021);
            }

            if ($UsuarioMandante->moneda != $this->currency) {
                throw new Exception("SC_WRONG_CURRENCY", 10017);
            }
            try {
                $TransaccionJuego = new TransaccionJuego("", "ZENITH" . $roundId);
            } catch (Exception $e) {
                throw new Exception("SC_TRANSACTION_NOT_EXISTS", 29);
            }

            $Producto = new Producto($UsuarioToken->productoId);
            $Subproveedor = new Subproveedor($Producto->subproveedorId);
            $Game = new Game();

            $responseG = $Game->getBalance($UsuarioMandante, $Subproveedor->subproveedorId, $UsuarioToken->productoId);

            $Balance = round($responseG->saldo, 2);

            if ($Balance === null || $Balance == 0) {
                throw new Exception("SC_INSUFFICIENT_FUNDS", 10020);
            }

            $return = array(
                "traceId" => $this->traceId,
                "status" => "SC_OK",
                "data" => array(
                    "username" => $this->user,
                    "currency" => $this->currency,
                    "balance" => $Balance
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
     * Este método se encarga de debitar una cantidad específica del balance del usuario,
     * validando la autenticación mediante token o usuario y registrando la transacción.
     *
     * @param string $gameId        ID del juego.
     * @param float  $debitAmount   Monto a debitar.
     * @param string $roundId       ID de la ronda de juego.
     * @param string $transactionId ID de la transacción.
     * @param array  $datos Datos   Adicionales de la transacción.
     * @param bool   $isfreeSpin    Indica si la transacción es parte de una tirada gratis.
     * @param bool   $gameRoundEnd  Indica si la ronda de juego ha terminado.
     * 
     * @return string JSON con el resultado de la transacción o un mensaje de error.
     * @throws Exception Si hay errores en la autenticación o en la transacción.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $isfreeSpin = false, $gameRoundEnd = false)
    {
        $this->type = "Debit";
        try {

            $Proveedor = new Proveedor("", "ZENITH");

            $tokenInvalido = false;
            $userInvalido = false;

            if ($this->token != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                } catch (Exception $e) {
                    $tokenInvalido = true;
                }
                if (!$tokenInvalido && $UsuarioToken !== null) {
                    try {
                        $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                    } catch (Exception $e) {
                        $tokenInvalido = true;
                    }
                }
            }
            if ($this->user != "") {
                try {
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                } catch (Exception $e) {
                    $userInvalido = true;
                }

                if (!$userInvalido && $UsuarioToken !== null) {
                    try {
                        $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                    } catch (Exception $e) {
                        $userInvalido = true;
                    }
                }
            }

            if ($this->token == "") {
                $tokenInvalido = true;
            }

            if ($this->user == "") {
                $userInvalido = true;
            }

            if ($this->user == "" && $this->token == "") {
                $userInvalido = true;
                $tokenInvalido = true;
            }

            if ($userInvalido) {
                throw new Exception("Usuario inválido", 10021);
            }

            $Game = new Game();

            $responseG = $Game->getBalance($UsuarioMandante);
            $Balance = round($responseG->saldo, 2);

            if ($Balance === null || $Balance == 0) {
                throw new Exception("SC_INSUFFICIENT_FUNDS", 10020);
            }

            if ($debitAmount > $Balance) {
                throw new Exception("SC_INSUFFICIENT_FUNDS", 10022);
            }
            if ($debitAmount === null) {
                throw new Exception("SC_INSUFFICIENT_FUNDS", 10022);
            }

            if ($UsuarioMandante->moneda != $this->currency) {
                throw new Exception("SC_WRONG_CURRENCY", 10017);
            }

            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);
            $this->transaccionApi->setIdentificador("ZENITH" . $roundId);

            if ($gameRoundEnd == true) {
                $End = true;
            } else {
                $End = false;
            }

            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $isfreeSpin, [], true, true);

            $Balance = round($responseG->saldo, 2);
            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "traceId" => $this->traceId,
                "status" => "SC_OK",
                "data" => array(
                    "username" => $this->user,
                    "currency" => $this->currency,
                    "balance" => $Balance
                )
            );

            $this->transaccionApi->setRespuesta($return);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un débito en la cuenta del usuario.
     *
     * Este método se encarga de debitar una cantidad específica del balance del usuario,
     * validando la autenticación mediante token o usuario y registrando la transacción.
     *
     * @param string $gameId        ID del juego.
     * @param float  $debitAmount   Monto a debitar.
     * @param string $roundId       ID de la ronda de juego.
     * @param string $transactionId ID de la transacción.
     * @param array  $datos Datos   Adicionales de la transacción.
     * @param bool   $isfreeSpin    Indica si la transacción es parte de una tirada gratis.
     * @param bool   $gameRoundEnd  Indica si la ronda de juego ha terminado.
     * 
     * @return string JSON con el resultado de la transacción o un mensaje de error.
     * @throws Exception Si hay errores en la autenticación o en la transacción.
     */
    public function adjustmentD($gameId, $debitAmount, $roundId, $transactionId, $datos, $isfreeSpin = false, $gameRoundEnd = false)
    {
        $this->type = "Debit";
        try {

            $Proveedor = new Proveedor("", "ZENITH");

            $tokenInvalido = false;
            $userInvalido = false;

            if ($this->token != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                } catch (Exception $e) {
                    $tokenInvalido = true;
                }
                if (!$tokenInvalido && $UsuarioToken !== null) {
                    try {
                        $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                    } catch (Exception $e) {
                        $tokenInvalido = true;
                    }
                }
            }
            if ($this->user != "") {
                try {
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                } catch (Exception $e) {
                    $userInvalido = true;
                }

                if (!$userInvalido && $UsuarioToken !== null) {
                    try {
                        $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                    } catch (Exception $e) {
                        $userInvalido = true;
                    }
                }
            }

            if ($this->token == "") {
                $tokenInvalido = true;
            }

            if ($this->user == "") {
                $userInvalido = true;
            }

            if ($this->user == "" && $this->token == "") {
                $userInvalido = true;
                $tokenInvalido = true;
            }

            if ($userInvalido) {
                throw new Exception("Usuario inválido", 10021);
            }

            $Game = new Game();

            $responseG = $Game->getBalance($UsuarioMandante);
            $Balance = round($responseG->saldo, 2);

            if ($Balance === null || $Balance == 0) {
                throw new Exception("SC_INSUFFICIENT_FUNDS", 10020);
            }

            if ($debitAmount > $Balance) {
                throw new Exception("SC_INSUFFICIENT_FUNDS", 10022);
            }
            if ($debitAmount === null) {
                throw new Exception("SC_INSUFFICIENT_FUNDS", 10022);
            }

            if ($UsuarioMandante->moneda != $this->currency) {
                throw new Exception("SC_WRONG_CURRENCY", 10017);
            }

            try {
                $TransaccionJuego = new TransaccionJuego("", "ZENITH" . $roundId);
            } catch (Exception $e) {
                throw new Exception("SC_TRANSACTION_NOT_EXISTS", 29);
            }

            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);
            $this->transaccionApi->setIdentificador("ZENITH" . $roundId);

            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $isfreeSpin, [], true, true);

            $Balance = round($responseG->saldo, 2);

            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "traceId" => $this->traceId,
                "status" => "SC_OK",
                "data" => array(
                    "username" => $this->user,
                    "currency" => $this->currency,
                    "balance" => $Balance
                )
            );

            $this->transaccionApi->setRespuesta($return);
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
     * Este método se encarga de debitar una cantidad específica del balance del usuario,
     * validando la autenticación mediante token o usuario y registrando la transacción.
     *
     * @param string $Producto      ID del juego.
     * @param float  $debitAmount   Monto a debitar.
     * @param float  $creditAmount  Monto a acreditar.
     * @param string $roundId       ID de la ronda de juego.
     * @param string $transactionId ID de la transacción.
     * @param array  $datos         Datos adicionales de la transaccion.
     * @param bool   $isBonus       Indica si la transacción es parte de una tirada gratis.
     * @param bool   $gameRoundEnd  Indica si la ronda de juego ha terminado.
     * 
     * @return string JSON con el resultado de la transacción o un mensaje de error.
     * @throws Exception Si hay errores en la autenticación o en la transacción.
     */
    public function Credit($Producto = "", $debitAmount, $creditAmount, $roundId, $transactionId, $datos, $isBonus = false, $gameRoundEnd)
    {
        $this->type = 'Credit';
        $this->transactionId = $transactionId;
        try {

            $Proveedor = new Proveedor("", "ZENITH");

            $tokenInvalido = false;
            $userInvalido = false;
            if ($this->token != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                } catch (Exception $e) {
                    $tokenInvalido = true;
                }
                if (!$tokenInvalido && $UsuarioToken !== null) {
                    try {
                        $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                    } catch (Exception $e) {
                        $tokenInvalido = true;
                    }
                }
            }

            if ($this->user != "") {
                try {
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                } catch (Exception $e) {
                    $userInvalido = true;
                }

                if (!$userInvalido && $UsuarioToken !== null) {
                    try {
                        $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                    } catch (Exception $e) {
                        $userInvalido = true;
                    }
                }
            }

            if ($this->token == "") {
                $tokenInvalido = true;
            }

            if ($this->user == "") {
                $userInvalido = true;
            }

            if ($this->user == "" && $this->token == "") {
                $userInvalido = true;
                $tokenInvalido = true;
            }

            if ($userInvalido) {
                throw new Exception("Usuario inválido", 10021);
            }

            if ($creditAmount == null || $creditAmount < 0) {
                throw new Exception("Fondos insuficientes", 10022);
            }

            if ($creditAmount === null) {
                throw new Exception("SC_INSUFFICIENT_FUNDS", 10022);
            }

            if ($UsuarioMandante->moneda != $this->currency) {
                throw new Exception("SC_WRONG_CURRENCY", 10017);
            }

            $Game = new Game();

            $responseG = $Game->getBalance($UsuarioMandante);
            $Balance = round($responseG->saldo, 2);

            if ($debitAmount > $Balance) {
                throw new Exception("SC_INSUFFICIENT_FUNDS", 10022);
            }

            $Producto = new Producto("", $Producto, $Proveedor->getProveedorId());
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);
            $this->transaccionApi->setIdentificador("ZENITH" . $roundId);

            if ($gameRoundEnd == true) {
                $End = true;
            } else {
                $End = false;
            }

            $Game = new Game();
            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $End, false, $isBonus, true);

            $Balance = round($responseG->saldo, 2) * 100;

            if ($creditAmount > $Balance) {
                throw new Exception("SC_INSUFFICIENT_FUNDS", 10022);
            }

            if ($Balance === null || $Balance == 0) {
                throw new Exception("SC_INSUFFICIENT_FUNDS", 10020);
            }

            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "traceId" => $this->traceId,
                "status" => "SC_OK",
                "data" => array(
                    "username" => $this->user,
                    "currency" => $this->currency,
                    "balance" => $Balance
                )
            );

            $this->transaccionApi->setRespuesta($return);
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
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $roundId        ID de la ronda de juego asociada.
     * @param string $transactionId  ID de la transacción original a revertir.
     * @param bool   $gameRoundEnd   Indica si la ronda de juego ha finalizado.
     * @param string $gameId         ID del juego involucrado en la transacción.
     * 
     * @return string JSON con el resultado del rollback o un mensaje de error.
     * @throws Exception Si hay errores en la validación del usuario o en la transacción.
     */
    public function Rollback($betId, $roundId, $datos, $gameId)
    {

        $this->type = 'Rollback';

        try {
            $Proveedor = new Proveedor("", "ZENITH");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $betId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setIdentificador("ZENITH" . $roundId);
            $userInvalido = false;
            if ($this->user != "") {
                try {
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                } catch (Exception $e) {
                    $userInvalido = true;
                }

                if (!$userInvalido && $UsuarioToken !== null) {
                    try {
                        $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                    } catch (Exception $e) {
                        $userInvalido = true;
                    }
                }
            }

            if ($this->user == "") {
                $userInvalido = true;
            }

            if ($userInvalido) {
                throw new Exception("Usuario inválido", 10021);
            }

            if ($UsuarioMandante->moneda != $this->currency) {
                throw new Exception("SC_WRONG_CURRENCY", 10017);
            }

            try {
                $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
                $TransjuegoLog = new TransjuegoLog("", "", "", 'C_' . $betId . '_' . $Producto->subproveedorId, $Producto->subproveedorId);
                $parts = explode('_', $TransjuegoLog->transaccionId);
                $transIdCredit = $parts[0] . '_' . $parts[1];
                $valorCredit = $TransjuegoLog->valor;

                $TransjuegoLog = new TransjuegoLog("", "", "", $betId . '_' . $Producto->subproveedorId, $Producto->subproveedorId);
                $parts = explode('_', $TransjuegoLog->transaccionId);
                $valorDebit = $TransjuegoLog->valor;
                $valor = $valorCredit - $valorDebit;

                $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                $this->transaccionApi->setTransaccionId('ROLLBACK' . $transIdCredit);
                $this->transaccionApi->setIdentificador($TransaccionJuego->getTicketId());
                $this->transaccionApi->setValor($valor);
            } catch (Exception $e) {
                $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
                $TransjuegoLog = new TransjuegoLog("", "", "", $betId . '_' . $Producto->subproveedorId, $Producto->subproveedorId);
                $parts = explode('_', $TransjuegoLog->transaccionId);
                $transId = $parts[0];

                $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());

                $this->transaccionApi->setTransaccionId('ROLLBACK' . $transId);
                $this->transaccionApi->setIdentificador($TransaccionJuego->getTicketId());
                $this->transaccionApi->setValor($TransjuegoLog->valor);
            }

            $Game = new Game();
            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false, '', true, false, true, false, 'I');
            $this->transaccionApi = $responseG->transaccionApi;

            $Balance = round($responseG->saldo, 2);
            if ($Balance == null || $Balance <= 0) {
                throw new Exception("Fondos insuficientes", 10020);
            }

            $return = array(
                "traceId" => $this->traceId,
                "status" => "SC_OK",
                "data" => array(
                    "username" => $this->user,
                    "currency" => $this->currency,
                    "balance" => $Balance
                )
            );

            $this->transaccionApi->setRespuesta($return);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }


    /**
     * Convierte un error en una respuesta estándar.
     *
     * @param integer $code             Código de error.
     * @param string  $messageProveedor Mensaje del proveedor.
     *
     * @param int    $code             Código de error interno.
     * @param string $messageProveedor Mensaje del proveedor asociado al error.
     * 
     * @return string JSON con el mensaje de error estructurado.
     */
    public function convertError($code, $messageProveedor)
    {

        $Proveedor = new Proveedor("", "ZENITH");
        $messageProveedor = "";
        $response = array();

        switch ($code) {

            case 21:
                $messageProveedor = "SC_SESSION_EXPIRED";
                http_response_code(200);
                break;

            case 10011:
                $messageProveedor = "SC_INVALID_TOKEN";
                http_response_code(200);
                break;

            case 10021:
                $messageProveedor = "SC_USER_NOT_EXISTS";
                http_response_code(200);
                break;

            case 29:
                $messageProveedor = "SC_TRANSACTION_NOT_EXISTS";
                http_response_code(200);
                break;

            case 28:
                $messageProveedor = "SC_TRANSACTION_NOT_EXISTS";
                http_response_code(200);
                break;

            case 20002:
                $messageProveedor = "SC_INVALID_SIGNATURE";
                http_response_code(200);
                break;

            case 10017:
                $messageProveedor = "SC_WRONG_CURRENCY";
                http_response_code(200);
                break;

            case 100001:
                $messageProveedor = "SC_WRONG_PARAMETERS";
                http_response_code(200);
                break;

            case 10022:
                $messageProveedor = "SC_INSUFFICIENT_FUNDS";
                http_response_code(200);
                break;

            case 20001:
                $messageProveedor = "SC_INSUFFICIENT_FUNDS";
                http_response_code(200);
                break;

            case 10020:
                switch ($this->type) {
                    case "Balance":

                        $response = array(
                            "traceId" => $this->traceId,
                            "status" => "SC_OK",
                            "data" => array(
                                "username" => $this->user,
                                "currency" => $this->currency,
                                "balance" => null,
                            )
                        );
                        $messageProveedor = "SC_OK";

                        http_response_code(200);
                        break;
                    case "Debit":
                        $response = array(
                            "traceId" => $this->traceId,
                            "status" => "SC_OK",
                            "data" => array(
                                "username" => $this->user,
                                "currency" => $this->currency,
                                "balance" => null,
                            )
                        );
                        $messageProveedor = "SC_OK";
                        http_response_code(200);
                        break;
                    case "Credit":

                        $response = array(
                            "traceId" => $this->traceId,
                            "status" => "SC_OK",
                            "data" => array(
                                "username" => $this->user,
                                "currency" => $this->currency,
                                "balance" => null,
                            )
                        );
                }

                $messageProveedor = "SC_OK";

                http_response_code(200);
                break;

            case 10005:
                $messageProveedor = "SC_TRANSACTION_DOES_NOT_EXIST";
                http_response_code(200);
                break;

            case 10001:

                switch ($this->type) {
                    case "Debit":

                        if ($this->token != "") {
                            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                        } else {
                            $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                        }

                        $Game = new Game();

                        $responseG = $Game->getBalance($UsuarioMandante);
                        $Balance = round($responseG->saldo, 2);

                        if ($Balance == 0) {
                            $Balance = null;
                        }
                        $response = array(
                            "traceId" => $this->traceId,
                            "status" => "SC_OK",
                            "data" => array(
                                "username" => $this->user,
                                "currency" => $this->currency,
                                "balance" => $Balance
                            )
                        );
                        $messageProveedor = "SC_OK";

                        http_response_code(200);
                        break;
                    case "Credit":
                        if ($this->token != "") {
                            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                        } else {
                            $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                        }

                        $Game = new Game();

                        $responseG = $Game->getBalance($UsuarioMandante);
                        $Balance = round($responseG->saldo, 2);
                        if ($Balance == 0) {
                            $Balance = null;
                        }

                        $response = array(
                            "traceId" => $this->traceId,
                            "status" => "SC_OK",
                            "data" => array(
                                "username" => $this->user,
                                "currency" => $this->currency,
                                "balance" => $Balance
                            )
                        );
                        $messageProveedor = "SC_OK";

                        http_response_code(200);
                        break;

                    case "Rollback":

                        if ($this->token != "") {
                            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                        } else {
                            $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->user);
                            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                        }

                        $Game = new Game();

                        $responseG = $Game->getBalance($UsuarioMandante);
                        $Balance = round($responseG->saldo, 2);

                        if ($Balance == 0) {
                            $Balance = null;
                        }

                        $response = array(
                            "traceId" => $this->traceId,
                            "status" => "SC_OK",
                            "data" => array(
                                "username" => $this->user,
                                "currency" => $this->currency,
                                "balance" => $Balance
                            )
                        );
                }
                $messageProveedor = "SC_OK";
                http_response_code(200);
                break;

            default:
                $messageProveedor = "General Error";
                http_response_code(200);
                break;
        }

        $respuesta = json_encode(array_merge($response, array(
            "traceId" => $this->traceId,
            "status" => $messageProveedor
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
