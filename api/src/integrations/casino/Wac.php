<?php

/**
 * Clase Wac
 *
 * Esta clase implementa la integración con el proveedor WAC para realizar operaciones
 * relacionadas con juegos, como autenticación, consulta de saldo, débito, crédito,
 * reembolsos y finalización de rondas. También maneja errores y genera respuestas en formato XML.
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
use SimpleXMLElement;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\Subproveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransaccionApi;
use Backend\dto\UsuarioMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\SubproveedorMandantePais;

/**
 * Clase que implementa la integración con el proveedor WAC.
 *
 * Proporciona métodos para realizar operaciones relacionadas con juegos,
 * como autenticación, consulta de saldo, débito, crédito, reembolsos y finalización de rondas.
 */
class Wac
{
    /**
     * Token de autenticación del usuario.
     *
     * @var string
     */
    private $token;

    /**
     * Método actual que se está ejecutando.
     *
     * @var string
     */
    private $method;

    /**
     * Nombre de usuario para autenticación.
     *
     * @var string
     */
    private $login;

    /**
     * Contraseña para autenticación.
     *
     * @var string
     */
    private $password;

    /**
     * Respuesta generada por las operaciones.
     *
     * @var string
     */
    private $respuesta;

    /**
     * Constructor de la clase Wac.
     *
     * Inicializa las credenciales y valida el token, login y contraseña.
     *
     * @param string $token    Token de autenticación.
     * @param string $method   Método a ejecutar.
     * @param string $login    Nombre de usuario (opcional).
     * @param string $password Contraseña (opcional).
     */
    public function __construct($token, $method, $login = "", $password = "")
    {
        $this->token = $token;
        $this->method = $method;
        $this->login = $login;
        $this->password = $password;

        $Proveedor = new Proveedor("", "WAC");

        try {
            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
        } catch (Exception $e) {
            try {
                throw new Exception("Validation error", "30022");
            } catch (Exception $e) {
                $this->respuesta = $this->convertError($e->getCode(), $e->getMessage());
            }
        }

        try {
            $Producto = new Producto($UsuarioToken->productoId);
        } catch (Exception $e) {
            try {
                throw new Exception("Validation error", "30022");
            } catch (Exception $e) {
                $this->respuesta = $this->convertError($e->getCode(), $e->getMessage());
            }
        }

        $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        if ($this->login !== $Credentials->LOGIN) {
            try {
                throw new Exception("Validation error", "30022");
            } catch (Exception $e) {
                $this->respuesta = $this->convertError($e->getCode(), $e->getMessage());
            }
        }

        if (($this->login === $Credentials->LOGIN && $this->password !== $Credentials->PASSWORD) || $this->token == null) {
            try {
                throw new Exception("Validation error", "30022");
            } catch (Exception $e) {
                $this->respuesta = $this->convertError($e->getCode(), $e->getMessage());
            }
        }
    }

    /**
     * Autentica al usuario y genera una respuesta con información del jugador.
     *
     * @return string Respuesta en formato XML.
     */
    public function Auth()
    {
        $this->method = 'getPlayerInfo';
        try {
            /*  Obtenemos el Proveedor con el abreviado Wac */
            $Proveedor = new Proveedor("", "WAC");

            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            }

            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $saldo = intval(floatval(round($responseG->saldo, 2) * 100));

            $Result = new SimpleXMLElement("<message></message>");
            $Result2 = $Result->addChild('result');
            $Result2->addAttribute('name', $this->method);
            $Result2->addAttribute('success', "1");
            $Result3 = $Result2->addChild('returnset');
            $Result4 = $Result3->addChild('token');
            $Result5 = $Result3->addChild('loginName');
            $Result6 = $Result3->addChild('currency');
            $Result7 = $Result3->addChild('balance');

            $Result4->addAttribute('value', $this->token);
            $Result5->addAttribute('value', $UsuarioMandante->getUsumandanteId());
            $Result6->addAttribute('value', $UsuarioMandante->moneda);
            $Result7->addAttribute('value', $saldo);

            return explode("\n", $Result->asXML(), 2)[1];
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el saldo del usuario autenticado.
     *
     * @return string Respuesta en formato XML con el saldo.
     */
    public function getBalance()
    {
        $this->method = 'getBalance';
        try {
            /*  Obtenemos el Proveedor con el abreviado Wac */
            $Proveedor = new Proveedor("", "WAC");

            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            }

            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $saldo = intval(floatval(round($responseG->saldo, 2) * 100));

            $Result = new SimpleXMLElement("<message></message>");
            $Result2 = $Result->addChild('result');
            $Result2->addAttribute('name', $this->method);
            $Result2->addAttribute('success', "1");
            $Result3 = $Result2->addChild('returnset');
            $Result4 = $Result3->addChild('token');
            $Result7 = $Result3->addChild('balance');

            $Result4->addAttribute('value', $this->token);
            $Result7->addAttribute('value', $saldo);

            return explode("\n", $Result->asXML(), 2)[1];
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
     * @param array  $datos         Datos adicionales de la transacción.
     *
     * @return string Respuesta en formato XML.
     * @throws Exception Si el monto es negativo.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos)
    {
        $this->method = 'bet';

        if ($debitAmount < 0) {
            throw new Exception("Monto negativo", "10003");
        }

        try {
            /*  Obtenemos el Proveedor con el abreviado Wac */
            $Proveedor = new Proveedor("", "WAC");

            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
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
            $this->transaccionApi->setIdentificador("WAC" . $roundId);

            $Game = new Game();

            $isfreeSpin = false;
            if (floatval($debitAmount) == 0) {
                $isfreeSpin = true;
            }

            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $isfreeSpin);

            $this->transaccionApi = $responseG->transaccionApi;

            $saldo = intval(floatval(round($responseG->saldo, 2) * 100));

            $Result = new SimpleXMLElement("<message></message>");
            $Result2 = $Result->addChild('result');
            $Result2->addAttribute('name', $this->method);
            $Result2->addAttribute('success', "1");
            $Result3 = $Result2->addChild('returnset');
            $Result4 = $Result3->addChild('token');
            $Result5 = $Result3->addChild('balance');
            $Result6 = $Result3->addChild('transactionId');
            $Result7 = $Result3->addChild('alreadyProcessed');

            $Result4->addAttribute('value', $this->token);
            $Result5->addAttribute('value', $saldo);
            $Result6->addAttribute('value', $responseG->transaccionId);
            $Result7->addAttribute('value', "false");

            return explode("\n", $Result->asXML(), 2)[1];
        } catch (Exception $e) {
            print_r($e);
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un reembolso (rollback) de una transacción.
     *
     * @param float  $rollbackAmount Monto a reembolsar.
     * @param string $roundId        ID de la ronda.
     * @param string $transactionId  ID de la transacción original.
     * @param array  $datos          Datos adicionales de la transacción.
     *
     * @return string Respuesta en formato XML.
     * @throws Exception Si la transacción no existe o el monto no coincide.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $datos)
    {
        $this->method = 'refundTransaction';

        try {
            /*  Obtenemos el Proveedor con el abreviado Wac */
            $Proveedor = new Proveedor("", "WAC");

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
                $SubProveedor = new Subproveedor("", "WAC");
                $TransjuegoLog = new TransjuegoLog("", "", "", $transactionId . '_' . $SubProveedor->getSubproveedorId(), $SubProveedor->getSubproveedorId());
                $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                $this->transaccionApi->setIdentificador($TransaccionJuego->getTicketId());
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            // Verificamos que el valor del ticket sea igual al valor del Rollback
            if ($TransaccionJuego->getValorTicket() != $rollbackAmount) {
                throw new Exception("Valor ticket diferente al Rollback", "10003");
            }

            $Game = new Game();
            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi);

            $this->transaccionApi = $responseG->transaccionApi;

            $saldo = intval(floatval(round($responseG->saldo, 2) * 100));

            $Result = new SimpleXMLElement("<message></message>");
            $Result2 = $Result->addChild('result');
            $Result2->addAttribute('name', $this->method);
            $Result2->addAttribute('success', "1");
            $Result3 = $Result2->addChild('returnset');
            $Result4 = $Result3->addChild('token');
            $Result5 = $Result3->addChild('balance');
            $Result6 = $Result3->addChild('transactionId');
            $Result7 = $Result3->addChild('alreadyProcessed');

            $Result4->addAttribute('value', $this->token);
            $Result5->addAttribute('value', $saldo);
            $Result6->addAttribute('value', $responseG->transaccionId);
            $Result7->addAttribute('value', "false");

            return explode("\n", $Result->asXML(), 2)[1];
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
     * @param array   $datos         Datos adicionales de la transacción.
     * @param boolean $isBonus       Indica si es un bono (opcional).
     *
     * @return string Respuesta en formato XML.
     * @throws Exception Si el monto es negativo o el token está vacío.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos, $isBonus = false)
    {
        $this->method = 'win';

        if ($creditAmount < 0) {
            throw new Exception("Monto negativo", "10003");
        }

        try {
            if ($this->token == "" && $this->acctid == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado Wac */
            $Proveedor = new Proveedor("", "WAC");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);
            $this->transaccionApi->setIdentificador("WAC" . $roundId);

            try {
                $TransaccionJuego = new TransaccionJuego("", "WAC" . $roundId);
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->getUsumandanteId());
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            $Game = new Game();

            /*  Obtenemos el producto con el gameId  */
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, false, false, $isBonus);

            $this->transaccionApi = $responseG->transaccionApi;

            $saldo = intval(floatval(round($responseG->saldo, 2) * 100));

            $Result = new SimpleXMLElement("<message></message>");
            $Result2 = $Result->addChild('result');
            $Result2->addAttribute('name', $this->method);
            $Result2->addAttribute('success', "1");
            $Result3 = $Result2->addChild('returnset');
            $Result4 = $Result3->addChild('token');
            $Result5 = $Result3->addChild('balance');
            $Result6 = $Result3->addChild('transactionId');
            $Result7 = $Result3->addChild('alreadyProcessed');

            $Result4->addAttribute('value', $this->token);
            $Result5->addAttribute('value', $saldo);
            $Result6->addAttribute('value', $responseG->transaccionId);
            $Result7->addAttribute('value', "false");

            return explode("\n", $Result->asXML(), 2)[1];
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Finaliza una ronda de juego.
     *
     * @param string $RoundId ID de la ronda.
     * @param array  $datos   Datos adicionales de la transacción.
     * @param string $cur     Moneda utilizada.
     *
     * @return string Respuesta en formato XML.
     */
    public function EndRound($RoundId, $datos, $cur)
    {
        $this->method = 'finalizeResp';

        try {
            /*  Obtenemos el Proveedor con el abreviado Wac */
            $Proveedor = new Proveedor("", "WAC");

            if ($this->acctid != "") {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->acctid);
            }

            /*  Obtenemos el Proveedor con el abreviado Wac */
            $Proveedor = new Proveedor("", "WAC");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId("ENDROUND" . $RoundId);
            $this->transaccionApi->setTipo("ENDROUND");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);
            $this->transaccionApi->setIdentificador("WAC" . $RoundId);

            $Game = new Game();

            $responseG = $Game->endRound($this->transaccionApi);

            $this->transaccionApi = $responseG->transaccionApi;

            /*  Retornamos el mensaje satisfactorio  */

            $saldo = intval(floatval(round($responseG->saldo, 2) * 100));

            $Result = new SimpleXMLElement("<cw></cw>");

            $Result->addAttribute('type', $this->method);
            $Result->addAttribute('cur', $cur);
            $Result->addAttribute('amt', $saldo);
            $Result->addAttribute('err', '0');

            return explode("\n", $Result->asXML(), 2)[1];
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
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
     * Convierte un error en un mensaje de respuesta en formato XML.
     *
     * @param integer $code    Código de error.
     * @param string  $message Mensaje de error.
     *
     * @return string Respuesta en formato XML con el error.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $Proveedor = new Proveedor("", "WAC");

        switch ($code) {
            case 21:
                $codeProveedor = 101; //Ready
                $messageProveedor = "The player token is invalid";
                break;

            case 10011:
                $codeProveedor = 101; //Ready
                $messageProveedor = "The player token is invalid";
                break;

            case 22:
                $codeProveedor = 102; //Ready
                $messageProveedor = "The player token expired";
                break;

            case 30022:
                $codeProveedor = 103; //Ready
                $messageProveedor = "The authentication credentials for the API are incorrect";
                break;

            case 20001:
                $codeProveedor = 200; //Ready
                $messageProveedor = "Not enough credits";
                break;

            case 10003:
                $codeProveedor = 201; //Ready
                $messageProveedor = "Invalid amount";
                break;

            case 10002:
                $codeProveedor = 201; //Ready
                $messageProveedor = "Invalid amount";
                break;

            case 10005:

                if ($this->method == "refundTransaction") {
                    $codeProveedor = 202; //Ready
                    $messageProveedor = "Transaction not found";
                } else {
                    $codeProveedor = 9999; //Ready
                    $messageProveedor = "Round does not exist";
                }
                break;


            default:
                $codeProveedor = 0; //Ready
                $messageProveedor = "General Error. (" . $code . ")";
                break;
        }

        if ($codeProveedor != "") {
            $Result = new SimpleXMLElement("<message></message>");
            $Result2 = $Result->addChild('result');
            $Result2->addAttribute('name', $this->method);
            $Result2->addAttribute('success', "0");
            $Result3 = $Result2->addChild('returnset');
            $Result4 = $Result3->addChild('error');
            $Result5 = $Result3->addChild('errorCode');

            $Result4->addAttribute('value', $messageProveedor);
            $Result5->addAttribute('value', $codeProveedor);

            return explode("\n", $Result->asXML(), 2)[1];
        }
    }
}
