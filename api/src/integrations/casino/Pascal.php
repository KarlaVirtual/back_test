<?php

/**
 * Clase Pascal
 *
 * Esta clase implementa la integración con el proveedor Pascal para realizar
 * operaciones relacionadas con transacciones de juegos, como autenticación,
 * consulta de saldo, débitos, créditos, y más.
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
use Backend\mysql\TransaccionApiMySqlDAO;

/**
 * Clase Pascal
 *
 * Esta clase implementa la integración con el proveedor Pascal para realizar
 * operaciones relacionadas con transacciones de juegos, como autenticación,
 * consulta de saldo, débitos, créditos, y más.
 */
class Pascal
{
    /**
     * Token de autenticación.
     *
     * @var string
     */
    private $token;

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
     * Constructor de la clase Pascal.
     *
     * @param string $token Token de autenticación.
     * @param string $sign  Firma utilizada para validaciones.
     */
    public function __construct($token, $sign)
    {
        $this->token = $token;
        $this->sign = $sign;
    }

    /**
     * Autentica al usuario con el proveedor Pascal.
     *
     * @return string JSON con los datos de autenticación.
     * @throws Exception Si el token está vacío o ocurre un error.
     */
    public function Auth()
    {
        try {
            $Proveedor = new Proveedor("", "PASCAL");

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
            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $return = array(
                "token" => $UsuarioToken->token,
                "code" => 0,
                "currencyCode" => $UsuarioMandante->moneda,
                "balance" => intval(round($responseG->saldo, 2) * 100),
                "playerExternalId" => $UsuarioMandante->usuarioMandante,
                "playerId" => $UsuarioMandante->usumandanteId
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el saldo del usuario.
     *
     * @return string JSON con el saldo y la moneda del usuario.
     * @throws Exception Si el token está vacío o ocurre un error.
     */
    public function getBalance()
    {
        try {
            $Proveedor = new Proveedor("", "PASCAL");

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
                "balance" => round($responseG->saldo, 2),
                "currencyCode" => $UsuarioMandante->moneda,
                "code" => 0

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
     * @param mixed   $datos         Datos adicionales.
     * @param boolean $freespin      Indica si es un giro gratis.
     *
     * @return string JSON con el resultado del débito.
     * @throws Exception Si el token está vacío o ocurre un error.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $freespin = false)
    {
        $this->data = $datos;

        try {
            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            //Obtenemos el Proveedor con el abreviado Pascal
            $Proveedor = new Proveedor("", "PASCAL");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);

            //Obtenemos el Usuario Token con el token
            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            //Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "PASCAL");
            //Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin);

            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "code" => 0,
                "balance" => round($responseG->saldo, 2),
                "externalTransactionId" => $responseG->transaccionId

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
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $roundId        ID de la ronda.
     * @param string $transactionId  ID de la transacción.
     * @param string $player         ID del jugador.
     * @param mixed  $datos          Datos adicionales.
     *
     * @return string JSON con el resultado del rollback.
     * @throws Exception Si ocurre un error.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos)
    {
        $usuarioid = $player;
        $this->data = $datos;

        try {
            //Obtenemos el Proveedor con el abreviado Pascal
            $Proveedor = new Proveedor("", "PASCAL");

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
                "code" => 0,
                "balance" => round($responseG->saldo, 2),
                "externalTransactionId" => $responseG->transaccionId

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
     * @param string  $gameId        ID del juego.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param mixed   $datos         Datos adicionales.
     * @param boolean $isBonus       Indica si es un bono.
     *
     * @return string JSON con el resultado del crédito.
     * @throws Exception Si ocurre un error.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos, $isBonus = false)
    {
        $this->data = $datos;

        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try {
            //Obtenemos el Proveedor con el abreviado Pascal
            $Proveedor = new Proveedor("", "PASCAL");

            //Obtenemos el Usuario Token con el token
            try {
                // Obtenemos el Usuario Token con el token
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

                //  Obtenemos el Usuario Mandante con el Usuario Token
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } catch (Exception $e) {
                if ($e->getCode() == 21) {
                    try {
                        $usumandanteId = explode('P', $this->token)[1];
                        $UsuarioMandante = new UsuarioMandante($usumandanteId);
                    } catch (Exception $e) {
                        throw new Exception("Usuario no existe", "22");
                    }
                }
            }

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);
            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "PASCAL");

            //Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            //Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            //Agregamos Elementos a la Transaccion API
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());

            $Game = new Game();
            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, true, false, $isBonus);

            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "code" => 0,
                "balance" => round($responseG->saldo, 2),
                "externalTransactionId" => $responseG->transaccionId
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
     * Realiza un crédito total en la cuenta del usuario.
     *
     * @param string $gameId        ID del juego.
     * @param float  $creditAmount  Monto total a acreditar.
     * @param string $roundId       ID de la ronda.
     * @param string $transactionId ID de la transacción.
     * @param mixed  $datos         Datos adicionales.
     *
     * @return string JSON con el resultado del crédito total.
     * @throws Exception Si ocurre un error.
     */
    public function CreditTotal($gameId, $creditAmount, $roundId, $transactionId, $datos)
    {
        $this->data = $datos;

        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try {
            //Obtenemos el Proveedor con el abreviado Pascal
            $Proveedor = new Proveedor("", "PASCAL");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);

            //Obtenemos el Usuario Token con el token
            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            //Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "PASCAL");

            //Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            //Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            //Agregamos Elementos a la Transaccion API
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());


            $Game = new Game();
            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, true);

            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "code" => 0,
                "balance" => round($responseG->saldo, 2),
                "externalTransactionId" => $responseG->transaccionId
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
     * Refresca el token del usuario.
     *
     * @return string JSON con el resultado de la operación.
     * @throws Exception Si el token está vacío o ocurre un error.
     */
    public function refreshToken()
    {
        try {
            $Proveedor = new Proveedor("", "PASCAL");

            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            $return = array(
                "code" => 0
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Verifica un parámetro.
     *
     * @param mixed $param Parámetro a verificar.
     *
     * @return string JSON con los datos de verificación.
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
     * Convierte un error en un formato JSON.
     *
     * @param integer $code    Código del error.
     * @param string  $message Mensaje del error.
     *
     * @return string JSON con el código y descripción del error.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $response = array();
        $Proveedor = new Proveedor("", "PASCAL");

        switch ($code) {
            case 10011:
                $codeProveedor = '10';
                $messageProveedor = "TOKEN_NOT_FOUND";
                break;

            case 21:
                $codeProveedor = '10';
                $messageProveedor = "TOKEN_NOT_FOUND";
                break;

            case 22:
                $codeProveedor = '13';
                $messageProveedor = "INCORRECT_PARAMETERS_PASSED";
                break;

            case 20001:
                $codeProveedor = "4";
                $messageProveedor = "NOT_ENOUGH_MONEY";
                break;

            case 0:
                $codeProveedor = "11";
                $messageProveedor = "GENERAL_ERROR";
                break;

            case 27:
                $codeProveedor = "2";
                $messageProveedor = "WRONG_GAME_ID";
                break;

            case 28:
                $codeProveedor = "14";
                $messageProveedor = "TRANSACTION_NOT_FOUND";
                break;

            case 29:
                $codeProveedor = "14";
                $messageProveedor = "TRANSACTION_NOT_FOUND";
                break;

            case 10001:
                $codeProveedor = "7";
                $messageProveedor = "TRANSACTION_IS_ALREADY_EXIST";
                break;

            case 10004:
                $codeProveedor = "8";
                $messageProveedor = "TRANSACTION_IS_ALREADY_CANCELED";
                break;

            case 10010:
                $codeProveedor = "7";
                $messageProveedor = "TRANSACTION_IS_ALREADY_EXIST";
                break;

            case 10005:
                $codeProveedor = "14";
                $messageProveedor = "TRANSACTION_NOT_FOUND";
                break;

            default:
                $codeProveedor = "11";
                $messageProveedor = "GENERAL_ERROR";
                break;
        }


        $respuesta = json_encode(array_merge($response, array(
            "Code" => $codeProveedor,
            "Description" => $messageProveedor

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
