<?php

/**
 * Clase `Elinmejorable` que representa la integración con un proveedor de casino.
 *
 * Este archivo contiene la implementación de la clase `Elinmejorable`, que incluye
 * métodos para manejar transacciones de autenticación, balance, débito, crédito y rollback
 * con el proveedor de casino "Elinmejorable".
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */

namespace Backend\integrations\casino;

use Exception;
use Backend\dto\Pais;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\TransaccionApi;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\mysql\TransaccionApiMySqlDAO;

/**
 * Clase `Elinmejorable`.
 *
 * Representa la integración con el proveedor de casino "Elinmejorable".
 * Contiene métodos para manejar transacciones como autenticación, balance,
 * débito, crédito y rollback.
 */
class Elinmejorable
{

    /**
     * Objeto de transacción API.
     *
     * @var TransaccionApi|null
     */
    private $transaccionApi;

    /**
     * Datos adicionales de la transacción.
     *
     * @var mixed
     */
    private $data;

    /**
     * Identificador de usuario.
     *
     * @var string|null
     */
    private $usuarioId;

    /**
     * Constructor de la clase.
     *
     * @param string $playerId ID del jugador.
     */
    public function __construct($playerId)
    {
        $this->usuarioId = $playerId;
    }

    /**
     * Autentica al usuario con el proveedor.
     *
     * @return string Respuesta en formato JSON con los datos del usuario autenticado.
     * @throws Exception Si el usuario no está definido.
     */
    public function Auth()
    {
        try {
            $Proveedor = new Proveedor("", "ELINMEJORABLE");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);

            if ($this->usuarioId == "") {
                throw new Exception("usuario vacio", "10021");
            }

            $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->usuarioId);
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            $Pais = new Pais($UsuarioMandante->paisId);

            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $return = array(
                "username" => "Usuario" . $UsuarioMandante->usumandanteId,
                "currency" => $UsuarioMandante->moneda,
                "country" => $Pais->iso,
                "balance" => intval(round($responseG->saldo, 2) * 100),
                "displayName" => $UsuarioMandante->nombres,
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del usuario.
     *
     * @return string Respuesta en formato JSON con el balance del usuario.
     * @throws Exception Si el usuario no está definido.
     */
    public function getBalance()
    {
        try {
            $Proveedor = new Proveedor("", "ELINMEJORABLE");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("BALANCE");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);

            if ($this->usuarioId == "") {
                throw new Exception("usuario vacio", "10021");
            }

            $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $this->usuarioId);
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);

            $return = array(
                "code" => "OK",
                "user" => intval($UsuarioMandante->usumandanteId),
                "currency" => $responseG->moneda,
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
     * Este método se encarga de procesar una transacción de débito para un usuario
     * en el sistema del proveedor de casino "Elinmejorable".
     *
     * @param string  $gameId        Identificador del juego.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $roundId       Identificador de la ronda.
     * @param string  $transactionId Identificador de la transacción.
     * @param mixed   $datos         Datos adicionales de la transacción.
     * @param boolean $freespin      Indica si la transacción es parte de un freespin.
     *
     * @return string Respuesta en formato JSON con los detalles de la transacción.
     * @throws Exception Si el usuario no está definido o si ocurre un error durante el proceso.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $freespin)
    {
        $this->data = $datos;

        try {
            if ($this->usuarioId == "") {
                throw new Exception("usuario vacio", "10021");
            }

            //Obtenemos el Proveedor con el abreviado Elinmejorable
            $Proveedor = new Proveedor("", "ELINMEJORABLE");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);

            //Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($this->usuarioId);

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "ELINMEJORABLE");
            //Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            //Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin);

            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "code" => "OK",
                "transactionId" => intval($responseG->transaccionId),
                "currency" => $responseG->moneda,
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
     * Realiza un rollback en la cuenta del usuario.
     *
     * Este método se encarga de revertir una transacción previamente realizada
     * en el sistema del proveedor de casino "Elinmejorable".
     *
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $roundId        Identificador de la ronda.
     * @param string $transactionId  Identificador de la transacción original.
     * @param string $player         Identificador del jugador.
     * @param mixed  $datos          Datos adicionales de la transacción.
     *
     * @return string Respuesta en formato JSON con los detalles del rollback.
     * @throws Exception Si ocurre un error durante el proceso o si la transacción no existe.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos)
    {
        $this->data = $datos;

        try {
            //Obtenemos el Proveedor con el abreviado Elinmejorable
            $Proveedor = new Proveedor("", "ELINMEJORABLE");

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
                "code" => "OK",
                "transactionId" => intval($responseG->transaccionId),
                "currency" => $responseG->moneda,
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
     * Este método procesa una transacción de crédito para un usuario en el sistema
     * del proveedor de casino "Elinmejorable".
     *
     * @param string $gameId        Identificador del juego.
     * @param float  $creditAmount  Monto a acreditar.
     * @param string $roundId       Identificador de la ronda.
     * @param string $transactionId Identificador de la transacción. Si está vacío, se genera uno.
     * @param mixed  $datos         Datos adicionales de la transacción.
     *
     * @return string Respuesta en formato JSON con los detalles de la transacción.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos)
    {
        $this->data = $datos;

        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try {
            //Obtenemos el Proveedor con el abreviado Elinmejorable
            $Proveedor = new Proveedor("", "ELINMEJORABLE");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);

            //Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($this->usuarioId);

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "ELINMEJORABLE");

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
                "code" => "OK",
                "transactionId" => intval($responseG->transaccionId),
                "currency" => $responseG->moneda,
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
            if ($_ENV['debug']) {
                print_r($e);
            }

            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Convierte un error en una respuesta JSON manejable.
     *
     * Este método toma un código de error y un mensaje, los mapea a un código y mensaje
     * específicos del proveedor, y genera una respuesta en formato JSON. Además, si existe
     * una transacción activa, actualiza su estado como error en la base de datos.
     *
     * @param integer $code    Código de error recibido.
     * @param string  $message Mensaje de error recibido.
     *
     * @return string Respuesta en formato JSON con el código y mensaje del error.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $response = array();

        $Proveedor = new Proveedor("", "ELINMEJORABLE");

        switch ($code) {
            case 10011:
                $codeProveedor = $code;
                $messageProveedor = $message;
                break;

            case 10021:
                $codeProveedor = $code;
                $messageProveedor = "UsuarioId Vacio";
                break;

            case 21:
                $codeProveedor = $code;
                $messageProveedor = $message;
                break;

            case 22:
                $codeProveedor = $code;
                $messageProveedor = "Usuario no existe";
                break;

            case 24:
                $codeProveedor = $code;
                $messageProveedor = "Usuario no existe";
                break;

            case 20001:
                $codeProveedor = $code;
                $messageProveedor = "Fondos insuficientes";
                break;

            case 10016:
                $codeProveedor = $code;
                $messageProveedor = "Ronda Cerrada";
                break;

            case 10001:
                $codeProveedor = $code;
                $messageProveedor = "Transaccion  Ya existe";
                break;

            case 10005:
                $codeProveedor = $code;
                $messageProveedor = "No existe la transacción";
                break;

            default:
                $codeProveedor = "100000";
                $messageProveedor = "Internal error";
                break;
        }

        $respuesta = json_encode(array_merge($response, array(
            "code" => $codeProveedor,
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
