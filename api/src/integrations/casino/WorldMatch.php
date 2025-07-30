<?php

/**
 * Clase `WorldMatch` para la integración con el proveedor de juegos WorldMatch.
 *
 * Este archivo contiene la implementación de la clase `WorldMatch`, que proporciona.
 * métodos para realizar operaciones como autenticación, consulta de saldo, débito,
 * crédito y reversión de transacciones con el proveedor de juegos WorldMatch.
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
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransaccionApiMySqlDAO;

/**
 * Clase `WorldMatch`.
 *
 * Esta clase proporciona métodos para la integración con el proveedor de juegos WorldMatch,
 * incluyendo autenticación, consulta de saldo, débito, crédito y reversión de transacciones.
 */
class WorldMatch
{
    /**
     * Identificador del skin del usuario.
     *
     * @var string
     */
    private $skin = "";

    /**
     * Token de autenticación del usuario.
     *
     * @var string
     */
    private $token;

    /**
     * Tipo de operación actual.
     *
     * @var string
     */
    private $type;

    /**
     * Identificador del usuario.
     *
     * @var integer
     */
    private $userid;

    /**
     * Objeto para manejar transacciones API.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * Constructor de la clase `WorldMatch`.
     *
     * @param string  $token  Token de autenticación del usuario.
     * @param integer $userid Identificador del usuario.
     * @param string  $skin   Identificador del skin del usuario.
     */
    public function __construct($token, $userid, $skin)
    {
        $this->token = $token;
        $this->userid = $userid;
        $this->skin = $skin;
    }

    /**
     * Método para autenticar al usuario con el proveedor WorldMatch.
     *
     * @return string Respuesta en formato JSON con los datos de autenticación.
     * @throws Exception Si ocurre un error durante la autenticación.
     */
    public function Auth()
    {
        try {
            if ($this->token == "") {
                throw new Exception("Token vacio", "01");
            }

            $Proveedor = new Proveedor("", "WMT");
            $Subproveedor = new Subproveedor("", "WMT");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);

            if ($this->token != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (Exception $e) {
                    throw new Exception("Usuario Inactivo", "20003");
                }
            } else {
                throw new Exception("Token vacio", "01");
            }

            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $SubproveedorMandantePais = new SubproveedorMandantePais("", $Subproveedor->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $return = array(
                "result" => 0,
                "message" => "OK",
                "data" => array(
                    "userId" => $UsuarioMandante->usumandanteId,
                    "token" => $this->token,
                    "username" => "user" . $UsuarioMandante->usumandanteId,
                    "licensee" => $Credentials->licensee,
                    "skin" => $this->skin,
                    "language" => $responseG->idioma,
                    "currency" => $UsuarioMandante->moneda
                )
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para obtener el saldo del usuario.
     *
     * @return string Respuesta en formato JSON con el saldo y la moneda del usuario.
     * @throws Exception Si ocurre un error durante la consulta del saldo.
     */
    public function Balance()
    {
        try {
            if ($this->token == "") {
                throw new Exception("Token vacio", "01");
            }

            $Proveedor = new Proveedor("", "WMT");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("BALANCE");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);

            $return = array(
                "result" => 0,
                "message" => "OK",
                "data" => array(
                    "amount" => $responseG->saldo,
                    "currency" => $responseG->moneda
                )
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para realizar un débito en la cuenta del usuario.
     *
     * @param string  $gameId        Identificador del juego.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $roundId       Identificador de la ronda.
     * @param string  $transactionId Identificador de la transacción.
     * @param array   $datos         Datos adicionales de la transacción.
     * @param boolean $gameRoundEnd  Indica si la ronda del juego ha terminado.
     * @param string  $currency      Moneda de la transacción.
     * @param string  $type          Tipo de operación (opcional).
     *
     * @return string Respuesta en formato JSON con los datos de la transacción.
     * @throws Exception Si ocurre un error durante el débito.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $gameRoundEnd, $currency, $type = '')
    {
        if ($type != '') {
            $this->type = $type;
        } else {
            $this->type = 'Debit';
        }

        try {
            if ($debitAmount == 0) {
                $transactionId = "D" . $transactionId;
            }

            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado WMT */
            $Proveedor = new Proveedor("", "WMT");

            if ($this->token != "") {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                throw new Exception("Token vacio", "10011");
            }

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
            $this->transaccionApi->setIdentificador("WMT" . $roundId);

            if ($gameRoundEnd == true) {
                $End = true;
            } else {
                $End = false;
            }

            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, false, [], true, $End);

            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "result" => 0,
                "message" => "",
                "data" => array(
                    "transactionid" => $responseG->transaccionId,
                    "balance" => $responseG->saldo,
                    "currency" => $responseG->moneda
                )
            );

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
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
     * Método para realizar un crédito en la cuenta del usuario.
     *
     * Este método acredita un monto en la cuenta del usuario asociado a un juego
     * específico. También actualiza el balance del usuario y registra la transacción
     * en la base de datos.
     *
     * @param string  $gameId        Identificador del juego (opcional).
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       Identificador de la ronda.
     * @param string  $transactionId Identificador de la transacción.
     * @param array   $datos         Datos adicionales de la transacción.
     * @param boolean $isBonus       Indica si el crédito es un bono (opcional).
     * @param boolean $gameRoundEnd  Indica si la ronda del juego ha terminado.
     * @param string  $currency      Moneda de la transacción.
     *
     * @return string Respuesta en formato JSON con los datos de la transacción.
     * @throws Exception Si ocurre un error durante el crédito.
     */
    public function Credit($gameId = "", $creditAmount, $roundId, $transactionId, $datos, $isBonus = false, $gameRoundEnd, $currency)
    {
        $this->type = 'Credit';

        try {
            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado WMT */
            $Proveedor = new Proveedor("", "WMT");

            try {
                $TransaccionJuego = new TransaccionJuego("", "WMT" . $roundId);
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
            $this->transaccionApi->setIdentificador("WMT" . $roundId);

            $ProductoMandante = new ProductoMandante("", "", $TransaccionJuego->productoId);
            $Producto = new Producto($ProductoMandante->productoId);

            if ($gameRoundEnd == true) {
                $End = true;
            } else {
                $End = false;
            }

            $Game = new Game();
            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $End, false, $isBonus, false);

            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "result" => 0,
                "message" => "",
                "data" => array(
                    "transactionid" => $responseG->transaccionId,
                    "balance" => $responseG->saldo,
                    "currency" => $responseG->moneda
                )
            );

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
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
     * Método para realizar una reversión (rollback) de una transacción.
     *
     * Este método permite revertir una transacción previamente realizada, asegurando
     * que los datos asociados a la transacción sean actualizados correctamente en el sistema.
     *
     * @param string  $transactionId Identificador de la transacción a revertir.
     * @param array   $datos         Datos adicionales relacionados con la transacción.
     * @param boolean $gameRoundEnd  Indica si la ronda del juego ha terminado.
     * @param string  $type          Tipo de operación (opcional).
     *
     * @return string Respuesta en formato JSON con los datos de la transacción revertida.
     * @throws Exception Si ocurre un error durante la reversión.
     */
    public function Rollback($transactionId, $datos, $gameRoundEnd, $type)
    {
        $this->type = 'Rollback';

        try {
            $Proveedor = new Proveedor("", "WMT");

            if ($this->token != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (Exception $e) {
                    $UsuarioMandante = new UsuarioMandante($this->userid);
                }
            } else {
                $UsuarioMandante = new UsuarioMandante($this->userid);
            }

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId("ROLLBACK" . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);

            try {
                $TransApi = new TransaccionApi("", $transactionId, $Proveedor->getProveedorId());
                $ProductoMandante = new ProductoMandante("", "", $TransApi->productoId);
                $Producto = new Producto($ProductoMandante->productoId);
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

            $Game = new Game();

            if ($gameRoundEnd == true) {
                $end = 'I';
            } else {
                $end = 'A';
            }

            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false, '', '', '', false, '', $end);
            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "result" => 0,
                "message" => "ok",
                "data" => array(
                    "transactionid" => $responseG->transaccionId
                )
            );

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
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
     * Convierte un código de error y un mensaje en una respuesta JSON.
     *
     * Este método toma un código de error y un mensaje, los mapea a códigos y mensajes
     * específicos del proveedor, y genera una respuesta JSON. Además, si existe una
     * transacción API activa, actualiza su estado con la información del error.
     *
     * @param integer $code    Código de error recibido.
     * @param string  $message Mensaje de error recibido.
     *
     * @return string Respuesta en formato JSON con el código y mensaje del proveedor.
     */
    public function convertError($code, $message)
    {
        $Proveedor = new Proveedor("", "WMT");

        $codeProveedor = "";
        $messageProveedor = "";
        $response = array();

        switch ($code) {
            case 10021:
                $codeProveedor = 6;
                $messageProveedor = "Token not found";
                break;

            case 21:
                $codeProveedor = 6;
                $messageProveedor = "Token not found";
                break;

            case 10001:

                $response = array(
                    "result" => 0,
                    "message" => "",
                    "data" => array(
                        "transactionid" => "",
                        "balance" => 0,
                        "currency" => ""
                    )
                );
                switch ($this->type) {
                    case "Debit":

                        try {
                            $TransaccionApi = new TransaccionApi("", $this->transaccionApi->getTransaccionId(), $Proveedor->getProveedorId());

                            $Game = new Game();
                            $UsuarioMandante = new UsuarioMandante($TransaccionApi->getUsuarioId());

                            $responseG = $Game->getBalance($UsuarioMandante);
                            $codeProveedor = 0;

                            $saldo = $responseG->saldo * 1;
                            $response = array(
                                "result" => 0,
                                "message" => "",
                                "data" => array(
                                    "transactionid" => $this->transaccionApi->getTransaccionId(),
                                    "balance" => $saldo,
                                    "currency" => $responseG->moneda
                                )
                            );
                            http_response_code(200);
                        } catch (Exception $e) {
                        }

                        break;

                    case "Credit":
                        try {
                            $TransaccionApi = new TransaccionApi("", $this->transaccionApi->getTransaccionId(), $Proveedor->getProveedorId());

                            $Game = new Game();
                            $UsuarioMandante = new UsuarioMandante($TransaccionApi->getUsuarioId());

                            $responseG = $Game->getBalance($UsuarioMandante);
                            $codeProveedor = 0;

                            $saldo = $responseG->saldo * 1;
                            $response = array(
                                "result" => 0,
                                "message" => "",
                                "data" => array(
                                    "transactionid" => $this->transaccionApi->getTransaccionId(),
                                    "balance" => $saldo,
                                    "currency" => $responseG->moneda
                                )
                            );
                            http_response_code(200);
                        } catch (Exception $e) {
                        }

                        break;

                    case "Rollback":
                        try {
                            $TransaccionApi = new TransaccionApi("", $this->transaccionApi->getTransaccionId(), $Proveedor->getProveedorId());

                            $Game = new Game();
                            $UsuarioMandante = new UsuarioMandante($TransaccionApi->getUsuarioId());

                            $responseG = $Game->getBalance($UsuarioMandante);
                            $codeProveedor = 0;
                            $messageProveedor = "ok";

                            $saldo = $responseG->saldo * 1;
                            $response = array(
                                "result" => 0,
                                "message" => "",
                                "data" => array(
                                    "transactionid" => $this->transaccionApi->getTransaccionId()
                                )
                            );
                            http_response_code(200);
                        } catch (Exception $e) {
                        }

                        break;
                }
                http_response_code(200);
                break;

            case 10025:
                $codeProveedor = 2;
                $messageProveedor = "Transaccion ya esta procesada";
                break;

            case 28:
                $codeProveedor = 2;
                $messageProveedor = "Blocking Error";
                break;
            case 29:
                $codeProveedor = 2;
                $messageProveedor = "TransactionNotFound";

                switch ($this->type) {
                    case "Rollback":
                        $codeProveedor = 0;
                        $messageProveedor = "";

                        $response = array(
                            "result" => 0,
                        );
                }
                http_response_code(200);
                break;
            case 10005:
                $codeProveedor = 2;
                $messageProveedor = "TransactionNotFound";

                switch ($this->type) {
                    case "Rollback":
                        $codeProveedor = 0;
                        $messageProveedor = "";

                        $response = array(
                            "result" => 0,
                        );
                }
                http_response_code(200);
                break;

            case 20001:
                $codeProveedor = 2;
                $messageProveedor = "exceeding user balance";
                break;

            default:
                $codeProveedor = 1;
                $messageProveedor = "General Error";
                break;
        }

        $respuesta = json_encode(array_merge($response, array(
            "result" => $codeProveedor,
            "message" => $messageProveedor
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
