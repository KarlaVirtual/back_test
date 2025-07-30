<?php

/**
 * Clase UniversalSoft
 *
 * Esta clase implementa la integración con el proveedor UniversalSoft para realizar operaciones
 * relacionadas con juegos, como autenticación, manejo de tokens, consultas de saldo, débitos, créditos,
 * y otras transacciones. Proporciona métodos para interactuar con la API del proveedor y manejar
 * errores de manera estructurada.
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
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\mysql\TransaccionApiMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;

/**
 * Clase UniversalSoft
 *
 * Esta clase representa la integración con el proveedor UniversalSoft, proporcionando
 * métodos para realizar operaciones relacionadas con juegos, como autenticación,
 * manejo de tokens, consultas de saldo, débitos, créditos, y más.
 */
class UniversalSoft
{

    /**
     * Token de autenticación.
     *
     * @var string
     */
    private $token;

    /**
     * Identificador del usuario.
     *
     * @var string
     */
    private $usuarioId;

    /**
     * Objeto para manejar transacciones API.
     *
     * @var TransaccionApi|null
     */
    private $transaccionApi;

    /**
     * Datos adicionales para la operación.
     *
     * @var mixed
     */
    private $data;

    /**
     * Identificadores adicionales.
     *
     * @var mixed
     */
    private $ids;

    /**
     * Tipo de operación.
     *
     * @var string
     */
    private $tipo = "";

    /**
     * Identificador externo.
     *
     * @var string
     */
    private $externalId = "";

    /**
     * Constructor de la clase.
     *
     * @param string $token     Token de autenticación.
     * @param string $sign      Firma de seguridad (opcional).
     * @param string $usuarioId ID del usuario (opcional).
     */
    public function __construct($token, $sign = "", $usuarioId = "")
    {
        $this->token = $token;
        $this->sign = $sign;
        $this->usuarioId = $usuarioId;
    }

    /**
     * Autentica una sesión con el proveedor.
     *
     * @param string $sesion Sesión a autenticar.
     *
     * @return string Respuesta en formato JSON.
     * @throws Exception Si ocurre un error durante la autenticación.
     */
    public function Auth($sesion)
    {
        try {
            $Proveedor = new Proveedor("", "UNIVERSALS");

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
            $UsuarioToken->setToken($sesion);
            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
            $UsuarioTokenMySqlDAO->insert($UsuarioToken);
            $UsuarioTokenMySqlDAO->getTransaction()->commit();

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $return = array(
                "status" => "success",
                "playerid" => $UsuarioMandante->usumandanteId,
                "sessionid" => $UsuarioToken->getToken(),
                "balance" => floatval($responseG->saldo),
                "currency" => $responseG->moneda,
                "realplayer" => true,
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Genera un token para un usuario.
     *
     * @param object $data Datos del usuario.
     *
     * @return string Respuesta en formato JSON.
     * @throws Exception Si ocurre un error durante la generación del token.
     */
    public function Token($data)
    {
        try {
            $Proveedor = new Proveedor("", "UNIVERSALS");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);

            try {
                if ($this->token == "") {
                    $UsuarioToken = new UsuarioToken('', $Proveedor->getProveedorId(), $data->playerid);
                    $UsuarioToken->setToken($UsuarioToken->createToken());
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
                    $UsuarioToken->setProductoId(0);
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
     * Obtiene el saldo del usuario.
     *
     * @return string Respuesta en formato JSON con el saldo.
     * @throws Exception Si ocurre un error durante la consulta.
     */
    public function getBalance()
    {
        try {
            $Proveedor = new Proveedor("", "UNIVERSALS");

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
                "status" => 'ok',
                "ubalance" => floatval($responseG->saldo),
                "errcode" => "NOERR",
                "errmsg" => "No hubo error en la operación"
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
     * @throws Exception Si ocurre un error durante la finalización.
     */
    public function End()
    {
        try {
            $Proveedor = new Proveedor("", "UNIVERSALS");

            if ($this->token != "") {
                //  Obtenemos el Usuario Token con el token
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                //  Obtenemos el Usuario Mandante con el Usuario Token
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                if (intval($this->externalId) == 0) {
                    throw new Exception("Token vacio", "10011");
                }
                //  Obtenemos el Usuario Mandante con el Usuario Token
                $UsuarioMandante = new UsuarioMandante($this->externalId);
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
                "balance" => floatval($responseG->saldo),
                "currency" => $responseG->moneda
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
     * @param string  $datos         Datos adicionales.
     * @param boolean $freespin      Indica si es un giro gratis (opcional).
     *
     * @return string Respuesta en formato JSON.
     * @throws Exception Si ocurre un error durante el débito.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $freespin = false)
    {
        $this->tipo = "DEBIT";
        $this->data = $datos;

        try {
            //Obtenemos el Proveedor con el abreviado UniversalSoft
            $Proveedor = new Proveedor("", "UNIVERSALS");

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

            $this->transaccionApi->setTransaccionId($transactionId . $UsuarioMandante->getUsumandanteId());
            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "UNIVERSALS");

            //Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin, []);
            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "resp" => "ok",
                "balance" => floatval($responseG->saldo),
                "saldo" => floatval($responseG->saldo + $debitAmount), //saldo anterior de la apuesta
                "idtx" => $responseG->transaccionId
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
     * Verifica un débito en la cuenta del usuario.
     *
     * @param string  $gameId        ID del juego.
     * @param float   $debitAmount   Monto a verificar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param string  $datos         Datos adicionales.
     * @param boolean $freespin      Indica si es un giro gratis (opcional).
     *
     * @return string Respuesta en formato JSON.
     * @throws Exception Si ocurre un error durante la verificación.
     */
    public function CheckDebit($gameId, $debitAmount, $roundId, $transactionId, $datos, $freespin = false)
    {
        $this->tipo = "DEBIT";
        $this->data = $datos;

        try {
            //Obtenemos el Proveedor con el abreviado UniversalSoft
            $Proveedor = new Proveedor("", "UNIVERSALS");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('GETB-' . $transactionId);
            $this->transaccionApi->setTipo("GETBALANCE");
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

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "UNIVERSALS");

            //Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);
            //$this->transaccionApi = $responseG->transaccionApi;

            if ((floatval($responseG->saldo) < floatval($debitAmount))) {
                throw new Exception("Insufficients Funds", "20001");
            }

            $return = array(
                "resp" => "ok",
                "balance" => floatval($responseG->saldo - $debitAmount),
                "saldo" => floatval($responseG->saldo), //saldo anterior de la apuesta
                "idtx" => 'GETB-' . $transactionId
            );

            //Guardamos la Transaccion Api necesaria de estado OK
            $this->transaccionApi->setRespuestaCodigo("OK");
            $this->transaccionApi->setRespuesta(json_encode($return));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->insert($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Verifica una transacción.
     *
     * @param string $transactionId ID de la transacción.
     * @param string $serial        Número de serie.
     * @param string $id_us         ID del usuario.
     * @param string $hora          Hora de la transacción.
     * @param string $idtx          ID de la transacción.
     * @param float  $Amount        Monto de la transacción.
     * @param string $datos         Datos adicionales.
     *
     * @return string Respuesta en formato JSON.
     * @throws Exception Si ocurre un error durante la verificación.
     */
    public function Check($transactionId, $serial, $id_us, $hora, $idtx, $Amount, $datos)
    {
        $this->data = $datos;

        try {
            // Creamos el log de la transaccion juego para auditoria
            $TransjuegoLog = new TransjuegoLog($idtx);
            $TransjuegoLog->setTransaccionId($serial);
            $TransjuegoLogMySqlDAO = new TransjuegoLogMySqlDAO();
            $TransjuegoLogMySqlDAO->update($TransjuegoLog);
            $TransjuegoLogMySqlDAO->getTransaction()->commit();

            $TransaccionJuego = new TransaccionJuego($TransjuegoLog->getTransjuegoId());
            $UsuarioMandante = new UsuarioMandante($TransaccionJuego->usuarioId);
            $TransaccionJuego->setTicketId($serial . $UsuarioMandante->getUsumandanteId() . "UNIVERSALS");
            $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
            $TransaccionJuegoMySqlDAO->update($TransaccionJuego);
            $TransaccionJuegoMySqlDAO->getTransaction()->commit();

            $return = array(
                "resp" => "ok",
                "serial" => $serial,
                "idtx" => $idtx,
                "id_us" => $idtx
            );

            return json_encode($return);
        } catch (Exception $e) {
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
     * @param string $datos          Datos adicionales.
     *
     * @return string Respuesta en formato JSON.
     * @throws Exception Si ocurre un error durante el rollback.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos)
    {
        $this->tipo = "ROLLBACK";
        $this->data = $datos;

        try {
            //Obtenemos el Proveedor con el abreviado UniversalSoft
            $Proveedor = new Proveedor("", "UNIVERSALS");

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

            $this->transaccionApi->setProductoId($producto);
            $this->transaccionApi->setIdentificador($identificador);

            $Game = new Game();
            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false);
            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "accion" => 'confirma_status_API',
                "id_operacion" => $responseG->transaccionId
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
     * @param string $datos         Datos adicionales.
     * @param string $ids           Identificadores adicionales (opcional).
     *
     * @return string Respuesta en formato JSON.
     * @throws Exception Si ocurre un error durante el crédito.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos, $ids = '')
    {
        $this->ids = $ids;
        $this->tipo = "CREDIT";
        $this->data = $datos;
        $array = json_decode($datos);

        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        } else {
            $transactionId = "C" . $transactionId;
        }

        try {
            //Obtenemos el Proveedor con el abreviado UniversalSoft
            $Proveedor = new Proveedor("", "UNIVERSALS");
            $Subproveedor = new Subproveedor("", "UNIVERSALS");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);
            //$this->transaccionApi->setIdentificador($roundId . "UniversalSoft");

            $TransjuegoLog = new TransjuegoLog("", "", "", str_replace('C', '', $transactionId) . '_' . $Subproveedor->subproveedorId, $Subproveedor->subproveedorId);

            $TransaccionJuego = new TransaccionJuego($TransjuegoLog->getTransjuegoId());
            /*  Obtenemos el Usuario Token con el token */
            $UsuarioMandante = new UsuarioMandante($TransaccionJuego->usuarioId);

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "UNIVERSALS");

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
                "accion" => 'confirma_status_API',
                "id_operacion" => $responseG->transaccionId
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
     * Convierte un código de error y un mensaje en una respuesta estructurada.
     *
     * Este método maneja los errores generados durante las transacciones,
     * asignando códigos y mensajes específicos según el tipo de error.
     * También registra la transacción con el estado de error en la base de datos.
     *
     * @param integer $code    Código de error.
     * @param string  $message Mensaje descriptivo del error.
     *
     * @return string Respuesta en formato JSON con los detalles del error.
     */
    public function convertError($code, $message)
    {
        $Proveedor = new Proveedor("", "UNIVERSALS");
        $Subproveedor = new Subproveedor("", "UNIVERSALS");
        $response = array();

        switch ($code) {
            case 10001:
                $codeProveedor = "duplicate_transaction";
                $messageProveedor = "Incorrect account_id";
                $response = array(
                    "accion" => 'confirma_status_API',
                    "id_operacion" => ''
                );
                if ($this->tipo != 'DEBIT') {
                    $transaccionApi2 = new TransjuegoLog("", "", "", $this->transaccionApi->transaccionId, $Subproveedor->getSubproveedorId());
                    $trasaccionId = $transaccionApi2->transjuegologId;

                    $response = array(
                        "accion" => 'confirma_status_API',
                        "id_operacion" => $trasaccionId
                    );
                }
                break;
            case 28:
                $codeProveedor = "duplicate_transaction";
                $messageProveedor = "Incorrect account_id";
                $response = array(
                    "resp" => 'OK',
                    "id" => $this->ids,
                    "Msg" => "Confirmado"
                );
                break;
            case 20001:
                $codeProveedor = "duplicate_transaction";
                $messageProveedor = "Incorrect account_id";
                $response = array(
                    "resp" => 'fo',
                    "id" => 'fo',
                    "Mgs" => "El jugador no tiene saldo disponible"
                );

                break;
            default:
                $codeProveedor = "duplicate_transaction";
                $messageProveedor = "Incorrect account_id";
                $response = array(
                    "resp" => 'fo',
                    "id" => 'fo',
                    "Mgs" => "El jugador no tiene saldo disponible"
                );
                break;
        }

        if (false) {
            $respuesta = json_encode(array_merge($response, array(
                "resp" => 'OK',
                "id" => "",
                "Msg" => "Confirmado"
            )));
        } else {
            $respuesta = json_encode(array_merge($response));
        }

        if ($this->transaccionApi != null) {
            $this->transaccionApi->setTipo("R" . $this->transaccionApi->getTipo() . '_' . $code);
            $this->transaccionApi->setRespuestaCodigo("ERROR_" . $code);
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->insert($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();
        }

        return $respuesta;
    }
}
