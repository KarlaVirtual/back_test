<?php

/**
 * Clase Aviatrix
 *
 * Esta clase implementa la integración con el proveedor AVIATRIX para realizar
 * operaciones relacionadas con transacciones de juegos, como autenticación,
 * débito, crédito y reversión. También maneja la conversión de errores específicos
 * del proveedor a respuestas estándar.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;

use Backend\dto\Mandante;
use Backend\dto\Pais;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransaccionApi;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioToken;
use Backend\dto\Producto;
use Backend\dto\ProductoMandante;
use Backend\dto\Proveedor;
use Backend\mysql\TransaccionApiMySqlDAO;
use DateTime;
use Exception;

/**
 * Clase Aviatrix
 *
 * Esta clase implementa la integración con el proveedor AVIATRIX para realizar
 * operaciones relacionadas con transacciones de juegos, como autenticación,
 * débito, crédito y reversión. También maneja la conversión de errores específicos
 * del proveedor a respuestas estándar.
 */
class Aviatrix
{
    /**
     * Token de autenticación del usuario.
     *
     * @var string
     */
    private $token;

    /**
     * ID del usuario asociado.
     *
     * @var string
     */
    private $usuarioId;

    /**
     * Objeto de transacción API.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * Constructor de la clase Aviatrix.
     *
     * @param string $token     Token de autenticación.
     * @param string $usuarioId ID del usuario (opcional).
     */
    public function __construct($token, $usuarioId = "")
    {
        $this->token = $token;
        $this->usuarioId = $usuarioId;
    }

    /**
     * Autentica al usuario con el proveedor AVIATRIX.
     *
     * @return string JSON con los datos del usuario autenticado.
     * @throws Exception Si ocurre un error durante la autenticación.
     */
    public function Auth()
    {
        try {
            $Proveedor = new Proveedor("", "AVIATRIX");

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
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $Mandante = new Mandante($UsuarioMandante->mandante);
            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $Pais = new Pais($UsuarioMandante->paisId);
            $return = array(
                "playerId" => $UsuarioMandante->usumandanteId,
                "country" => $Pais->iso,
                "balance" => round($responseG->saldo * 100),
                "currency" => $responseG->moneda,
            );
            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza una operación de débito en el proveedor AVIATRIX.
     *
     * @param string  $gameId        ID del juego.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param array   $datos         Datos adicionales de la transacción.
     * @param boolean $freespin      Indica si es un giro gratis (opcional).
     *
     * @return string JSON con los datos de la transacción.
     * @throws Exception Si ocurre un error durante el débito.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $freespin = false)
    {
        $this->tipo = "DEBIT";
        $this->data = $datos;

        try {
            //Obtenemos el Proveedor con el abreviado AVIATRIX
            $Proveedor = new Proveedor("", "AVIATRIX");

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

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "AVIATRIX");

            //Obtenemos el producto con el gameId

            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            //Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());
            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin);
            $this->transaccionApi = $responseG->transaccionApi;

            $fecha_actual = new DateTime();
            $fecha = $fecha_actual->format('Y-m-d\TH:i:s.vO');

            $return = array(
                "createdAt" => $fecha,
                "balance" => round($responseG->saldo * 100),
                "processedTxId" => $responseG->transaccionId,
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
     * Realiza una operación de crédito en el proveedor AVIATRIX.
     *
     * @param string  $gameId        ID del juego.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param array   $datos         Datos adicionales de la transacción.
     * @param boolean $roundClosed   Indica si la ronda está cerrada.
     * @param boolean $freespin      Indica si es un giro gratis (opcional).
     *
     * @return string JSON con los datos de la transacción.
     * @throws Exception Si ocurre un error durante el crédito.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos, $roundClosed, $freespin = false)
    {
        $this->tipo = "CREDIT";
        $this->data = $datos;

        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try {
            //Obtenemos el Proveedor con el abreviado AVIATRIX
            $Proveedor = new Proveedor("", "AVIATRIX");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);

            try {
                $TransaccionJuego = new TransaccionJuego("", $roundId . $this->usuarioId . "AVIATRIX");
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "AVIATRIX");

            //  Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            //  Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            //  Agregamos Elementos a la Transaccion API
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());

            $Game = new Game();
            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $roundClosed, false, $freespin);

            $this->transaccionApi = $responseG->transaccionApi;

            $fecha_actual = new DateTime();
            $fecha = $fecha_actual->format('Y-m-d\TH:i:s.vO');

            $return = array(
                "createdAt" => $fecha,
                "balance" => round($responseG->saldo * 100),
                "processedTxId" => $responseG->transaccionId,
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
     * Realiza una operación de reversión (rollback) en el proveedor AVIATRIX.
     *
     * @param string $gameId         ID del juego.
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $roundId        ID de la ronda.
     * @param string $transactionId  ID de la transacción.
     * @param array  $datos          Datos adicionales de la transacción.
     *
     * @return string JSON con los datos de la transacción.
     * @throws Exception Si ocurre un error durante la reversión.
     */
    public function Rollback($gameId, $rollbackAmount, $roundId, $transactionId, $datos)
    {
        $this->tipo = "ROLLBACK";
        $this->data = $datos;

        try {
            //Obtenemos el Proveedor con el abreviado AVIATRIX
            $Proveedor = new Proveedor("", "AVIATRIX");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($rollbackAmount);

            try {
                $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
                $TransaccionJuego = new TransaccionJuego("", $roundId . $this->usuarioId . "AVIATRIX");
                $TransjuegoLog = new TransjuegoLog("", $TransaccionJuego->transjuegoId, "", $TransaccionJuego->transaccionId . '_' . $Producto->subproveedorId);
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                if (strpos($TransjuegoLog->getTipo(), 'DEBIT') !== false) {
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

            $this->transaccionApi->setProductoId($Producto->productoId);

            $Game = new Game();

            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false, "", true, false, true);

            $this->transaccionApi = $responseG->transaccionApi;

            $fecha_actual = new DateTime();
            $fecha = $fecha_actual->format('Y-m-d\TH:i:s.vO');

            $return = array(
                "createdAt" => $fecha,
                "balance" => round($responseG->saldo * 100),
                "processedTxId" => $responseG->transaccionId,
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
     * Convierte un error del proveedor en una respuesta estándar.
     *
     * @param integer $code    Código de error del proveedor.
     * @param string  $message Mensaje de error del proveedor.
     *
     * @return string JSON con el mensaje de error convertido.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $Proveedor = new Proveedor("", "AVIATRIX");
        $response = array();

        switch ($code) {
            case 50007:
                $codeProveedor = 400;
                $messageProveedor = "Invalid session token";
                break;

            case 10006:
                $codeProveedor = 400;
                $messageProveedor = "Invalid transaction";
                break;

            case 10005:
                $codeProveedor = 404;
                $messageProveedor = "Bet not found";
                break;

            case 29:
                $codeProveedor = 403;
                $messageProveedor = "Transaction has been cancelled";
                break;

            case 20001:
                $codeProveedor = 403;
                $messageProveedor = "Insufficient balance";
                break;

            case 10017:
                $codeProveedor = 400;
                $messageProveedor = "Invalid player currency";
                break;

            case 21:
                $codeProveedor = 401;
                $messageProveedor = "Session token expired";
                break;


            case 26:
                $codeProveedor = 404;
                $messageProveedor = "Product not found";
                break;

            case 24:
                $codeProveedor = 404;
                $messageProveedor = "Player not found";
                break;

            case 10005:
                $codeProveedor = 404;
                $messageProveedor = "Bet not found";
                break;

            case 59:
                $codeProveedor = 403;
                $messageProveedor = "Insufficient balance";
                break;

            case 100083:
                $codeProveedor = 429;
                $messageProveedor = "Service overloaded";
                break;

            case 0:
                $codeProveedor = 403;
                $messageProveedor = "Bet limit exceeded";
                break;

            default:
                $codeProveedor = 429;
                $messageProveedor = "Service overloaded";
                break;
        }

        if ($messageProveedor != "") {
            $respuesta = json_encode(array_merge($response, array("message" => $messageProveedor)));
        }

        if ($this->transaccionApi != null) {
            $this->transaccionApi->setTipo("R" . $this->transaccionApi->getTipo());
            $this->transaccionApi->setRespuestaCodigo("ERROR_" . $code);
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->insert($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();
        }
        http_response_code($codeProveedor);
        return $respuesta;
    }
}
