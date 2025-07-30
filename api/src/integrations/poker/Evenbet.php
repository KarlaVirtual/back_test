<?php

/**
 * Esta clase implementa la integración con el proveedor Evenbet.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-21
 */

namespace Backend\integrations\poker;

use Exception;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\TransaccionApi;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\integrations\casino\Game;
use Backend\mysql\TransaccionApiMySqlDAO;

/**
 * Clase Evenbet
 *
 * Esta clase implementa la integración con el proveedor Evenbet, permitiendo realizar operaciones
 * como autenticación, consulta de balance, débitos, créditos, reversión de transacciones y manejo de errores.
 */
class Evenbet
{

    /**
     * Token de autenticación utilizado para la integración con Evenbet.
     * Este token es necesario para realizar las operaciones con el proveedor.
     *
     * @var string
     */
    private $token;

    /**
     * Firma utilizada para la autenticación con Evenbet.
     * Se utiliza para validar las solicitudes enviadas al proveedor.
     *
     * @var string
     */
    private $sign;

    /**
     * Objeto de transacción API utilizado para registrar y manejar transacciones.
     * Representa las operaciones realizadas con el proveedor.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * Datos adicionales relacionados con la transacción actual.
     * Estos datos pueden incluir información específica de la operación.
     *
     * @var array
     */
    private $data;

    /**
     * Tipo de transacción que se está realizando.
     * Puede ser "AUTH", "DEBIT", "CREDIT", "ROLLBACK", entre otros.
     *
     * @var string
     */
    private $tipo;

    /**
     * Identificador único del usuario en el sistema Evenbet.
     * Este ID se utiliza para asociar las operaciones con un usuario específico.
     *
     * @var string
     */
    private $userId;

    /**
     * Constructor de la clase Evenbet.
     *
     * Inicializa los valores necesarios para la integración con Evenbet.
     * Verifica si el sistema está bloqueado antes de proceder.
     *
     * @param string $token  Token de autenticación utilizado para la integración.
     * @param string $sign   Firma utilizada para la autenticación.
     * @param string $userId Identificador del usuario.
     */
    public function __construct($token = "", $sign, $userId)
    {
        try {
            $responseEnable = file_get_contents(__DIR__ . '/../../../../logSit/enabled');
        } catch (Exception $e) {
        }

        if ($responseEnable == 'BLOCKED') {
            http_response_code(408);
            exit();
        }

        $this->token = $token;
        $this->sign = $sign;
        $this->userId = $userId;
    }

    /**
     * Realiza la autenticación del usuario con el proveedor Evenbet.
     *
     * Este metodo crea una transacción de tipo "AUTH" y utiliza las clases relacionadas
     * para autenticar al usuario con el proveedor. Devuelve el balance del usuario
     * en caso de éxito o un error en caso de fallo.
     *
     * @return string JSON con el balance del usuario, código de error y descripción del error.
     */
    public function Auth()
    {
        try {
            $Proveedor = new Proveedor("", "EVENBET");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);

            $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->userId);
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $return = array(
                "balance" => round($responseG->saldo, 2) * 100,
                "errorCode" => 0,
                "errorDescription" => "Completed successfully"
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del usuario con el proveedor Evenbet.
     *
     * Este metodo crea una transacción de tipo "BALANCE" y utiliza las clases relacionadas
     * para obtener el balance del usuario con el proveedor. Devuelve el balance del usuario
     * en caso de éxito o un error en caso de fallo.
     *
     * @return string JSON con el balance del usuario, código de error y descripción del error.
     */
    public function getBalance()
    {
        try {
            $Proveedor = new Proveedor("", "EVENBET");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("BALANCE");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);

            $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->userId);
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);
            $return = array(
                "balance" => round($responseG->saldo, 2) * 100,
                "errorCode" => 0,
                "errorDescription" => "Completed successfully"
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un débito en la cuenta del usuario con el proveedor Evenbet.
     *
     * Este metodo crea una transacción de tipo "DEBIT" y utiliza las clases relacionadas
     * para realizar un débito en la cuenta del usuario. Devuelve el balance actualizado
     * del usuario en caso de éxito o un error en caso de fallo.
     *
     * @param string $gameId        Identificador del juego.
     * @param float  $debitAmount   Monto a debitar.
     * @param string $roundId       Identificador de la ronda.
     * @param string $transactionId Identificador de la transacción.
     * @param array  $datos         Datos adicionales relacionados con la transacción.
     * @param bool   $freespin      Indica si la transacción es parte de un freespin.
     *
     * @return string JSON con el balance actualizado, código de error y descripción del error.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $freespin)
    {
        $this->data = $datos;
        $this->tipo = "DEBIT";
        try {
            //Obtenemos el Proveedor con el abreviado Evenbet
            $Proveedor = new Proveedor("", "EVENBET");

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
            $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->userId);

            //Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "EVENBET");
            //Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            //Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin, [], true, false);

            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "balance" => round($responseG->saldo, 2) * 100,
                "errorCode" => 0,
                "errorDescription" => "Completed successfully"
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
     * Realiza un rollback (reversión) de una transacción con el proveedor Evenbet.
     *
     * Este metodo crea una transacción de tipo "ROLLBACK" y utiliza las clases relacionadas
     * para revertir una transacción previamente realizada. Devuelve el balance actualizado
     * del usuario en caso de éxito o un error en caso de fallo.
     *
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $roundId        Identificador de la ronda.
     * @param string $transactionId  Identificador de la transacción a revertir.
     * @param string $player         Identificador del jugador.
     * @param array  $datos          Datos adicionales relacionados con la transacción.
     *
     * @return string JSON con el balance actualizado, código de error y descripción del error.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos)
    {
        $usuarioid = $player;
        $this->tipo = "ROLLBACK";
        $this->data = $datos;

        try {
            //Obtenemos el Proveedor con el abreviado Evenbet
            $Proveedor = new Proveedor("", "EVENBET");

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
                "balance" => round($responseG->saldo, 2) * 100,
                "errorCode" => 0,
                "errorDescription" => "Completed successfully"
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
     * Realiza un crédito en la cuenta del usuario con el proveedor Evenbet.
     *
     * Este metodo crea una transacción de tipo "CREDIT" y utiliza las clases relacionadas
     * para realizar un crédito en la cuenta del usuario. Devuelve el balance actualizado
     * del usuario en caso de éxito o un error en caso de fallo.
     *
     * @param string $gameId        Identificador del juego.
     * @param float  $creditAmount  Monto a acreditar.
     * @param string $roundId       Identificador de la ronda.
     * @param string $transactionId Identificador de la transacción.
     * @param array  $datos         Datos adicionales relacionados con la transacción.
     *
     * @return string JSON con el balance actualizado, código de error y descripción del error.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos)
    {
        $this->data = $datos;
        $this->tipo = "CREDIT";

        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try {
            //Obtenemos el Proveedor con el abreviado Evenbet
            $Proveedor = new Proveedor("", "EVENBET");

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
            $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->userId);

            //Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "EVENBET");

            //Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            //Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            //Agregamos Elementos a la Transaccion API
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());

            $Game = new Game();
            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, false);

            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "balance" => round($responseG->saldo, 2) * 100,
                "errorCode" => 0,
                "errorDescription" => "Completed successfully"
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
     * Verifica el estado del sistema y devuelve información relacionada.
     *
     * Este metodo genera un arreglo con información básica, incluyendo un identificador
     * de nodo, el parámetro proporcionado y la firma actual.
     *
     * @param mixed $param Parámetro de entrada para la verificación.
     *
     * @return string JSON con la información del nodo, el parámetro y la firma.
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
     * Convierte un código de error y un mensaje en una respuesta JSON estandarizada.
     *
     * Este metodo toma un código de error y un mensaje, los mapea a códigos y descripciones
     * específicos del proveedor, y genera una respuesta JSON. También registra la transacción
     * en caso de error.
     *
     * @param int    $code    Código de error recibido.
     * @param string $message Mensaje de error recibido.
     *
     * @return string Respuesta JSON con el código de error, descripción y balance (si aplica).
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $response = array();
        $Proveedor = new Proveedor("", "EVENBET");

        switch ($code) {
            case 10011:
                $codeProveedor = 2;
                $messageProveedor = "Player not found.";
                break;

            case 21:
                $codeProveedor = 2;
                $messageProveedor = "Player not found.";
                break;

            case 22:
                $codeProveedor = 2;
                $messageProveedor = "Player not found";
                break;

            case 20001:
                $codeProveedor = 3;
                $messageProveedor = "Insufficient funds";
                break;

            case 28:
                $codeProveedor = 5;
                $messageProveedor = "Reference transaction does not exist";
                break;

            case 29:
                $codeProveedor = 5;
                $messageProveedor = "Reference transaction does not exist";
                break;

            case 10001:
                $codeProveedor = "TRANSACTION EXISTS";
                $messageProveedor = "Transaction Exists";

                switch ($this->tipo) {
                    case "DEBIT":
                        $codeProveedor = "TRANSACTION EXISTS";
                        $messageProveedor = "Transaction Exists";
                        $transaccionApi2 = new TransaccionApi("", $this->transaccionApi->getTransaccionId(), $Proveedor->getProveedorId());

                        $UsuarioMandante = new UsuarioMandante($transaccionApi2->usuarioId);
                        $Game = new Game();
                        $responseG = $Game->getBalance($UsuarioMandante);

                        $response = array(
                            "balance" => round($responseG->saldo, 2) * 100,
                            "errorCode" => 0,
                            "errorDescription" => "Transaction already processed"
                        );
                        break;
                    case "CREDIT":
                        $codeProveedor = "";
                        $messageProveedor = "Duplicate Transaction Id.";

                        $transaccionApi2 = new TransaccionApi("", $this->transaccionApi->getTransaccionId(), $Proveedor->getProveedorId());

                        $UsuarioMandante = new UsuarioMandante($transaccionApi2->usuarioId);
                        $Game = new Game();
                        $responseG = $Game->getBalance($UsuarioMandante);

                        $response = array(
                            "balance" => round($responseG->saldo, 2) * 100,
                            "errorCode" => 0,
                            "errorDescription" => "Transaction already processed"
                        );

                        break;
                    case "ROLLBACK":
                        $transaccionApi2 = new TransaccionApi("", $this->transaccionApi->getTransaccionId(), $Proveedor->getProveedorId());
                        $UsuarioMandante = new UsuarioMandante($transaccionApi2->usuarioId);
                        $Game = new Game();

                        $responseG = $Game->getBalance($UsuarioMandante);

                        $response = array(
                            "balance" => round($responseG->saldo, 2) * 100,
                            "errorCode" => 0,
                            "errorDescription" => "Transaction already processed"
                        );

                        break;
                }
                break;

            case 10005:
                $codeProveedor = 5;
                $messageProveedor = "Reference transaction does not exist";
                break;

            case 20002:
                $codeProveedor = 1;
                $messageProveedor = "Invalid signature";
                break;

            default:
                $codeProveedor = 4;
                $messageProveedor = "Invalid request params";
                break;
        }

        if ($code != 10001) {
            $respuesta = json_encode(array_merge($response, array(
                "errorCode" => $codeProveedor,
                "errorDescription" => $messageProveedor
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
