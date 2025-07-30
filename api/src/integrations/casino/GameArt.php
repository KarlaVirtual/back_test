<?php

/**
 * Clase GameArt para la integración con el proveedor de juegos GameArt.
 *
 * Esta clase contiene métodos para manejar transacciones como autenticación,
 * consulta de saldo, débitos, créditos, reversión de transacciones y generación de firmas.
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
use Backend\dto\UsuarioToken;
use Backend\dto\TransaccionApi;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\dto\Subproveedor;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransaccionApiMySqlDAO;

/**
 * Clase principal para la integración con el proveedor de juegos GameArt.
 *
 * Esta clase contiene métodos para manejar transacciones como autenticación,
 * consulta de saldo, débitos, créditos, reversión de transacciones y generación de firmas.
 */
class GameArt
{
    /**
     * Token de autenticación.
     *
     * @var string
     */
    private $token;

    /**
     * ID del usuario.
     *
     * @var string
     */
    private $usuarioId;

    /**
     * Firma utilizada para validaciones.
     *
     * @var string
     */
    private $sign;

    /**
     * Objeto de transacción API.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * Datos adicionales para las transacciones.
     *
     * @var mixed
     */
    private $data;

    /**
     * Tipo de transacción actual.
     *
     * @var string
     */
    private $tipo = "";

    /**
     * Constructor de la clase GameArt.
     *
     * @param string $token     Token de autenticación.
     * @param string $sign      Firma para validaciones.
     * @param string $usuarioId ID del usuario (opcional).
     */
    public function __construct($token, $sign, $usuarioId = "")
    {
        $this->token = $token;
        $this->sign = $sign;
        $this->usuarioId = $usuarioId;
    }

    /**
     * Genera una firma basada en el cuerpo de la solicitud.
     *
     * @param string $body Cuerpo de la solicitud.
     *
     * @return string Firma generada.
     */
    public function getSignature($body)
    {
        $Proveedor = new Proveedor("", "GAMEART");

        if ($this->token != "") {
            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
        } else {
            $UsuarioMandante = new UsuarioMandante($this->usuarioId);
            $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->usumandanteId);
        }

        try {
            $Producto = new Producto($UsuarioToken->productoId);
            $subproveedorId = $Producto->subproveedorId;
        } catch (Exception $e) {
            $Subproveedor = new Subproveedor("", "GAMEART");
            $subproveedorId = $Subproveedor->subproveedorId;
        }

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $PESignature = sha1($Credentials->SECRET_KEY . $body);

        return $PESignature;
    }


    /**
     * Autentica al usuario con el proveedor GameArt.
     *
     * @return string Respuesta en formato JSON con el estado y el saldo.
     */
    public function Auth()
    {
        try {
            $Proveedor = new Proveedor("", "GAMEART");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);

            if ($this->usuarioId == "") {
                throw new Exception("UsuarioId vacio", "10021");
            }

            $UsuarioToken = new UsuarioToken('', $Proveedor->getProveedorId(), $this->usuarioId);
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $return = array(
                "status" => 200,
                "balance" => round($responseG->saldo, 2)
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el saldo del usuario.
     *
     * @return string Respuesta en formato JSON con el estado y el saldo.
     */
    public function getBalance()
    {
        try {
            $Proveedor = new Proveedor("", "GAMEART");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("BALANCE");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);

            if ($this->usuarioId == "") {
                throw new Exception("UsuarioId vacio", "10021");
            }

            $UsuarioToken = new UsuarioToken('', $Proveedor->getProveedorId(), $this->usuarioId);
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);

            $return = array(
                "status" => 200,
                "balance" => round($responseG->saldo, 2)
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un débito en la cuenta del usuario.
     *
     * @param string   $gameId        ID del juego.
     * @param float    $debitAmount   Monto a debitar.
     * @param string   $roundId       ID de la ronda.
     * @param string   $transactionId ID de la transacción.
     * @param mixed    $datos         Datos adicionales.
     * @param booleanl $freespin      Indica si es un giro gratis (opcional).
     *
     * @return string Respuesta en formato JSON con el estado y el saldo.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $freespin = false)
    {
        $this->tipo = "DEBIT";
        $this->data = $datos;
        try {
            //Obtenemos el Proveedor con el abreviado GameArt
            $Proveedor = new Proveedor("", "GAMEART");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);

            if ($this->usuarioId != "") {
                // Obtenemos el Usuario Token con el token
                $UsuarioToken = new UsuarioToken('', $Proveedor->getProveedorId(), $this->usuarioId);

                //  Obtenemos el Usuario Mandante con el Usuario Token
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                throw new Exception("UsuarioId vacio", "10021");
            }

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "GameArt");

            //Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin);
            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "status" => 200,
                "balance" => round($responseG->saldo, 2)
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
     * Reversa una transacción previa.
     *
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $roundId        ID de la ronda.
     * @param string $transactionId  ID de la transacción original.
     * @param string $player         Información del jugador.
     * @param mixed  $datos          Datos adicionales.
     *
     * @return string Respuesta en formato JSON con el estado y el saldo.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos)
    {
        $this->tipo = "ROLLBACK";
        $this->data = $datos;

        try {
            //Obtenemos el Proveedor con el abreviado GameArt
            $Proveedor = new Proveedor("", "GAMEART");

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

            if ($TransaccionApi2->getValor() != $rollbackAmount) {
                throw new Exception("Detalles de la transacción no coinciden", "10007");
            }

            $this->transaccionApi->setProductoId($producto);
            $this->transaccionApi->setIdentificador($identificador);

            $Game = new Game();
            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false, $roundId);

            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "status" => 200,
                "balance" => round($responseG->saldo, 2)
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
     * @param mixed  $datos         Datos adicionales.
     *
     * @return string Respuesta en formato JSON con el estado y el saldo.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos)
    {
        $this->tipo = "CREDIT";
        $this->data = $datos;

        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try {
            //Obtenemos el Proveedor con el abreviado GameArt
            $Proveedor = new Proveedor("", "GAMEART");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);
            //$this->transaccionApi->setIdentificador($roundId . "GameArt");

            /*  Obtenemos el Usuario Token con el token */
            if ($this->usuarioId != "") {
                // Obtenemos el Usuario Token con el token
                $UsuarioToken = new UsuarioToken('', $Proveedor->getProveedorId(), $this->usuarioId);

                //  Obtenemos el Usuario Mandante con el Usuario Token
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                throw new Exception("UsuarioId vacio", "10021");
            }

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "GameArt");

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
                "status" => 200,
                "balance" => round($responseG->saldo, 2)
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
     * Verifica el estado del sistema.
     *
     * @param mixed $param Parámetro de entrada.
     *
     * @return string Respuesta en formato JSON con el estado del sistema.
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
     * Convierte errores en respuestas JSON manejables.
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
        $Proveedor = new Proveedor("", "GAMEART");
        $response = array();

        switch ($code) {
            case 10011:
                $codeProveedor = 500;
                $messageProveedor = "Invalid Token.";

                break;
            case 10012:
                $codeProveedor = 500;
                $messageProveedor = "Token already used.";

                break;
            case 10015:
                $codeProveedor = 500;
                $messageProveedor = "Transaction has been cancelled.";

                break;
            case 10016:
                $codeProveedor = 403;
                $messageProveedor = "closed round.";
                break;

            case 20001:
                $codeProveedor = 500;
                $messageProveedor = "Insufficient funds";
                break;

            case 10001:
                switch ($this->tipo) {
                    case "DEBIT":
                        $codeProveedor = "B_04";
                        $messageProveedor = "Duplicate Transaction Id.";
                        $transaccionApi2 = new TransaccionApi("", $this->transaccionApi->getTransaccionId(), $Proveedor->getProveedorId());
                        $UsuarioMandante = new UsuarioMandante($transaccionApi2->usuarioId);
                        $Game = new Game();
                        $responseG = $Game->getBalance($UsuarioMandante);

                        $response = array(
                            "status" => 200,
                            "balance" => round($responseG->saldo, 2)
                        );
                        break;
                    case "CREDIT":
                        $codeProveedor = "W_06";
                        $messageProveedor = "Duplicate Transaction Id.";

                        $transaccionApi2 = new TransaccionApi("", $this->transaccionApi->getTransaccionId(), $Proveedor->getProveedorId());
                        $UsuarioMandante = new UsuarioMandante($transaccionApi2->usuarioId);
                        $Game = new Game();
                        $responseG = $Game->getBalance($UsuarioMandante);

                        $response = array(
                            "status" => 200,
                            "balance" => round($responseG->saldo, 2)
                        );

                        break;
                    case "ROLLBACK":

                        $transaccionApi2 = new TransaccionApi("", $this->transaccionApi->getTransaccionId(), $Proveedor->getProveedorId());
                        $UsuarioMandante = new UsuarioMandante($transaccionApi2->usuarioId);
                        $Game = new Game();

                        $responseG = $Game->getBalance($UsuarioMandante);

                        $response = array(
                            "status" => 200,
                            "balance" => round($responseG->saldo, 2)
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
                    "status" => 200,
                    "balance" => round($responseG->saldo, 2)
                );
                break;

            case 21010:
                $codeProveedor = 115;
                $messageProveedor = "Game not found";
                break;

            case 20002:
                $codeProveedor = "R_03";
                $messageProveedor = "Invalid Key.";
                break;

            default:
                $codeProveedor = 500;
                $messageProveedor = "Error General";
                break;
        }

        if ($code != 10001 && $code != 10010) {
            $respuesta = json_encode(array_merge($response, array(
                "status" => "error",
                "code" => $codeProveedor,
                "msg" => $messageProveedor
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
