<?php

/**
 * Clase `Caleta` para manejar integraciones con el proveedor de juegos CALETA.
 *
 * Este archivo contiene la implementación de la clase `Caleta`, que incluye métodos
 * para autenticar usuarios, manejar transacciones (créditos, débitos, rollbacks),
 * verificar firmas y obtener balances. También gestiona errores y los convierte
 * en respuestas específicas del proveedor.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
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
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransaccionApiMySqlDAO;

/**
 * Clase `Caleta` para manejar integraciones con el proveedor de juegos CALETA.
 *
 * Esta clase incluye métodos para autenticar usuarios, manejar transacciones
 * (créditos, débitos, rollbacks), verificar firmas, obtener balances y gestionar errores.
 */
class Caleta
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
     * @var integer
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
     * Tipo de operación actual.
     *
     * @var string
     */
    private $tipo = "";

    /**
     * Identificador del jugador.
     *
     * @var string
     */
    private $player = "";

    /**
     * UUID de la solicitud.
     *
     * @var string
     */
    private $request_uuid = "";

    /**
     * Constructor de la clase `Caleta`.
     *
     * @param string $token        Token de autenticación.
     * @param string $sign         Firma para validaciones.
     * @param string $request_uuid UUID de la solicitud (opcional).
     */
    public function __construct($token, $sign, $request_uuid = "")
    {
        $this->token = $token;
        $this->sign = $sign;
        $this->request_uuid = $request_uuid;
    }

    /**
     * Verifica la validez de un mensaje y su firma.
     *
     * @param string  $msg       Mensaje a verificar.
     * @param string  $signature Firma del mensaje.
     * @param integer $user      ID del usuario.
     *
     * @return integer Resultado de la verificación (1 si es válido, 0 si no lo es).
     */
    public function verify($msg, $signature, $user)
    {
        try {
            $Proveedor = new Proveedor("", "CALETA");

            if ($this->token != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (Exception $e) {
                    $UsuarioMandante = new UsuarioMandante($user);
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->usumandanteId);
                }
            } else {
                $UsuarioMandante = new UsuarioMandante($user);
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->usumandanteId);
            }

            $Producto = new Producto($UsuarioToken->productoId);

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $public_key = base64_decode($Credentials->PUBLIC_KEY);

            $pubkey_pem = "-----BEGIN PUBLIC KEY-----\n$public_key\n-----END PUBLIC KEY-----";

            $key = openssl_pkey_get_public($pubkey_pem);

            $result = openssl_verify($msg, base64_decode($signature), $key, OPENSSL_ALGO_SHA256);
            return $result;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Autentica un usuario y genera una sesión.
     *
     * @return string Respuesta en formato JSON con los datos de la sesión.
     */
    public function Auth()
    {
        try {
            $Proveedor = new Proveedor("", "CALETA");

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
                $UsuarioToken = new UsuarioToken('', $Proveedor->getProveedorId());
                throw new Exception("Token ya registado", "10012");
            } catch (Exception $e) {
                if ($e->getCode() == "10012") {
                    throw $e;
                }
            }

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            $UsuarioToken->setToken('');
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
                "balance" => intval(round($responseG->saldo, 2) * 100000),
                "currency" => $responseG->moneda,
                "realplayer" => true,

            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Genera un nuevo token para el usuario y lo almacena en la base de datos.
     *
     * Este método crea una transacción de tipo "TOKEN", genera un nuevo token
     * para el usuario asociado al proveedor "CALETA", y lo guarda en la base de datos.
     * Luego, autentica al usuario y devuelve el token generado.
     *
     * @return string Respuesta en formato JSON que contiene el token generado.
     * @throws Exception Si ocurre algún error durante el proceso.
     */
    public function Token()
    {
        try {
            $Proveedor = new Proveedor("", "CALETA");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("TOKEN");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
            $UsuarioToken->setToken($UsuarioToken->createToken());

            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
            $UsuarioTokenMySqlDAO->insert($UsuarioToken);
            $UsuarioTokenMySqlDAO->getTransaction()->commit();

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $return = array(
                "token" => $UsuarioToken->getToken()
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del usuario asociado al token actual.
     *
     * Este método realiza una consulta al proveedor "CALETA" para obtener el balance
     * del usuario autenticado. Si el token está vacío, lanza una excepción.
     *
     * @return string Respuesta en formato JSON con el balance del usuario, moneda y otros datos.
     * @throws Exception Si ocurre algún error durante el proceso.
     */
    public function getBalance()
    {
        try {
            $Proveedor = new Proveedor("", "CALETA");

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
                "user" => "user" . $UsuarioMandante->usumandanteId,
                "status" => "RS_OK",
                "request_uuid" => $this->request_uuid,
                "currency" => $responseG->moneda,
                "balance" => intval(round($responseG->saldo, 2) * 100000)
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un débito en la cuenta del usuario.
     *
     * Este método procesa una transacción de débito para un usuario en el sistema,
     * verificando el token, obteniendo los datos del usuario y el producto asociado,
     * y actualizando la transacción en la base de datos.
     *
     * @param string  $gameId        ID del juego asociado al débito.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $roundId       ID de la ronda asociada.
     * @param string  $transactionId ID de la transacción.
     * @param mixed   $datos         Datos adicionales para la transacción.
     * @param boolean $freespin      Indica si el débito es parte de un freespin (opcional).
     *
     * @return string Respuesta en formato JSON con el estado de la transacción y el balance actualizado.
     * @throws Exception Si ocurre algún error durante el proceso.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $freespin = false)
    {
        $this->tipo = "DEBIT";
        $this->data = $datos;
        try {
            //Obtenemos el Proveedor con el abreviado Caleta
            $Proveedor = new Proveedor("", "CALETA");

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

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "CALETA");

            //Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            $this->transaccionApi->setTransaccionId("ROLLBACK" . $transactionId);

            if ($this->transaccionApi->existsTransaccionIdAndProveedor("ERROR")) {
                throw new Exception("Trasacción con rollback", "10015");
            }
            if ($this->transaccionApi->existsTransaccionIdAndProveedor("OK")) {
                throw new Exception("Trasacción con rollback", "10015");
            }

            $this->transaccionApi->setTransaccionId($transactionId);
            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin);
            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "user" => "user" . $UsuarioMandante->usumandanteId,
                "status" => "RS_OK",
                "request_uuid" => $this->request_uuid,
                "currency" => $responseG->moneda,
                "balance" => intval(round($responseG->saldo, 2) * 100000)
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
     * Realiza un rollback en la cuenta del usuario.
     *
     * Este método procesa una transacción de rollback para un usuario en el sistema,
     * verificando el token, obteniendo los datos del usuario y el producto asociado,
     * y actualizando la transacción en la base de datos.
     *
     * @param float  $rollbackAmount             Monto a revertir.
     * @param string $roundId                    ID de la ronda asociada.
     * @param string $transactionId              ID de la transacción original.
     * @param string $player                     Identificador del jugador.
     * @param mixed  $datos                      Datos adicionales para la transacción.
     * @param string $reference_transaction_uuid UUID de la transacción de referencia.
     *
     * @return string Respuesta en formato JSON con el estado de la transacción y el balance actualizado.
     * @throws Exception Si ocurre algún error durante el proceso.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos, $reference_transaction_uuid)
    {
        if ($_ENV['debug']) {
            print_r('Jerson Rollback');
        }
        $this->tipo = "ROLLBACK";

        $this->data = $datos;

        $this->player = $player;
        try {
            //Obtenemos el Proveedor con el abreviado Caleta
            $Proveedor = new Proveedor("", "CALETA");

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
                $TransaccionApi2 = new TransaccionApi("", $reference_transaction_uuid, $Proveedor->getProveedorId());
                $producto = $TransaccionApi2->getProductoId();
                $identificador = $TransaccionApi2->getIdentificador();
                $UsuarioMandante = new UsuarioMandante($TransaccionApi2->getUsuarioId());
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            $this->transaccionApi->setProductoId($producto);
            $this->transaccionApi->setIdentificador($identificador);

            $Game = new Game();
            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false, $reference_transaction_uuid, false);

            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "user" => "user" . $UsuarioMandante->usumandanteId,
                "status" => "RS_OK",
                "request_uuid" => $this->request_uuid,
                "currency" => $responseG->moneda,
                "balance" => intval(round($responseG->saldo, 2) * 100000)
            );

            //Guardamos la Transaccion Api necesaria de estado OK
            $this->transaccionApi->setRespuestaCodigo("OK");
            $this->transaccionApi->setRespuesta(json_encode($return));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return json_encode($return);
        } catch (Exception $e) {
            if ($_ENV['debug']) {
                print_r($e);
            }
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un crédito en la cuenta del usuario.
     *
     * Este método procesa una transacción de crédito para un usuario en el sistema,
     * verificando el token, obteniendo los datos del usuario y el producto asociado,
     * y actualizando la transacción en la base de datos.
     *
     * @param string $gameId        ID del juego asociado al crédito.
     * @param float  $creditAmount  Monto a acreditar.
     * @param string $roundId       ID de la ronda asociada.
     * @param string $transactionId ID de la transacción (opcional, se genera uno si está vacío).
     * @param mixed  $datos         Datos adicionales para la transacción.
     *
     * @return string Respuesta en formato JSON con el estado de la transacción y el balance actualizado.
     * @throws Exception Si ocurre algún error durante el proceso.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos)
    {
        $this->tipo = "CREDIT";

        $this->data = $datos;

        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try {
            //Obtenemos el Proveedor con el abreviado Caleta
            $Proveedor = new Proveedor("", "CALETA");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);

            /*  Obtenemos el Usuario Token con el token */
            if ($this->token != "") {
                // Obtenemos el Usuario Token con el token
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                //  Obtenemos el Usuario Mandante con el Usuario Token
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                $UsuarioMandante = new UsuarioMandante($this->usuarioId);
            }

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "CALETA");

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
                "user" => "user" . $UsuarioMandante->usumandanteId,
                "status" => "RS_OK",
                "request_uuid" => $this->request_uuid,
                "currency" => $responseG->moneda,
                "balance" => intval(round($responseG->saldo, 2) * 100000)
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
     * Método `Check`.
     *
     * Este método devuelve un arreglo JSON con información de prueba, incluyendo
     * un identificador de nodo, el parámetro recibido y la firma actual.
     *
     * @param mixed $param Parámetro de entrada que será incluido en la respuesta.
     *
     * @return string Respuesta en formato JSON con los datos proporcionados.
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
     * Convierte un error en una respuesta específica del proveedor.
     *
     * Este método toma un código de error y un mensaje, y los convierte en una
     * respuesta JSON que incluye un código de error específico del proveedor.
     * También registra la transacción en la base de datos si es necesario.
     *
     * @param integer $code    Código de error recibido.
     * @param string  $message Mensaje de error recibido.
     *
     * @return string Respuesta en formato JSON con el código y mensaje del proveedor.
     */
    public function convertError($code, $message)
    {
        //print_r($code);
        $codeProveedor = "";

        $Proveedor = new Proveedor("", "CALETA");
        $response = array();

        switch ($code) {
            case 10011:
                $codeProveedor = "RS_ERROR_INVALID_TOKEN";
                break;

            case 10015:
                $codeProveedor = "RS_ERROR_TRANSACTION_ROLLED_BACK";
                break;

            case 10017:
                $codeProveedor = "RS_ERROR_WRONG_CURRENCY";

                break;
            case 21:
                $codeProveedor = "RS_ERROR_INVALID_TOKEN";
                break;

            case 20001:
                $codeProveedor = "RS_ERROR_NOT_ENOUGH_MONEY";
                break;

            case 26:
                $codeProveedor = "RS_ERROR_INVALID_GAME";
                break;

            case 27:
                $codeProveedor = "RS_ERROR_INVALID_GAME";
                break;

            case 28:
                $codeProveedor = "RS_ERROR_TRANSACTION_DOES_NOT_EXIST";
                break;

            case 29:
                $codeProveedor = "RS_ERROR_TRANSACTION_DOES_NOT_EXIST";
                break;

            case 10001:
                $codeProveedor = "RS_ERROR_DUPLICATE_TRANSACTION";
                $messageProveedor = "";

                $transaccionApi2 = new TransaccionApi("", $this->transaccionApi->getTransaccionId(), $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($transaccionApi2->usuarioId);
                $Game = new Game();

                $responseG = $Game->getBalance($UsuarioMandante);

                $response = array(
                    "user" => "user" . $UsuarioMandante->usumandanteId,
                    "status" => "RS_OK",
                    "request_uuid" => $this->request_uuid,
                    "currency" => $responseG->moneda,
                    "balance" => intval(round($responseG->saldo, 2) * 100000)
                );
                break;

            case 10005:
                switch ($this->tipo) {
                    case "ROLLBACK":
                        $codeProveedor = "RS_ERROR_TRANSACTION_DOES_NOT_EXIST";

                        $UsuarioMandante = new UsuarioMandante($this->player);

                        $Game = new Game();

                        $responseG = $Game->getBalance($UsuarioMandante);

                        $response = array(
                            "user" => "user" . $UsuarioMandante->usumandanteId,
                            "status" => "RS_OK",
                            "request_uuid" => $this->request_uuid,
                            "currency" => $responseG->moneda,
                            "balance" => intval(round($responseG->saldo, 2) * 100000)
                        );
                        break;

                    case "DEBIT":
                        $codeProveedor = "RS_ERROR_TRANSACTION_DOES_NOT_EXIST";
                        break;

                    case "CREDIT":
                        $codeProveedor = "RS_ERROR_TRANSACTION_DOES_NOT_EXIST";
                        break;
                }
                break;

            case 100012:
                $codeProveedor = "RS_ERROR_INVALID_SIGNATURE";
                break;

            default:
                $codeProveedor = "RS_ERROR_UNKNOWN";
                break;
        }

        if ($code != 10001 and $code != 10005) {
            $respuesta = json_encode(array_merge($response, array(
                "status" => "error",
                "code" => $codeProveedor

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
