<?php

/**
 * Clase Onetouch para la integración con el proveedor ONETOUCH.
 *
 * Esta clase contiene métodos para manejar transacciones de balance, débitos, créditos,
 * reversión de transacciones y finalización de rondas en el contexto de un casino en línea.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;

use Backend\dto\Mandante;
use Backend\dto\PromocionalLog;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransaccionApi;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioToken;
use Backend\dto\Producto;
use Backend\dto\ProductoMandante;
use Backend\dto\Proveedor;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransaccionApiMySqlDAO;
use Backend\websocket\WebsocketUsuario;
use Exception;

/**
 * Clase principal para la integración con el proveedor ONETOUCH.
 *
 * Esta clase contiene métodos para manejar transacciones relacionadas con el balance,
 * débitos, créditos, reversión de transacciones y finalización de rondas en un casino en línea.
 */
class Onetouch
{
    /**
     * Token de autenticación del usuario.
     *
     * @var string
     */
    private $token;

    /**
     * Identificador externo.
     *
     * @var string
     */
    private $externalId;

    /**
     * Objeto de transacción API.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * Datos de la transacción.
     *
     * @var mixed
     */
    private $data;

    /**
     * UUID de la solicitud.
     *
     * @var string
     */
    private $request_uuid = " ";

    /**
     * Constructor de la clase Onetouch.
     *
     * @param string $token        Token de autenticación.
     * @param string $request_uuid UUID de la solicitud.
     */
    public function __construct($token, $request_uuid)
    {
        $this->token = $token;
        $this->request_uuid = $request_uuid;
    }

    /**
     * Obtiene el balance del usuario para un juego específico.
     *
     * @param string $game_id Identificador del juego.
     *
     * @return string JSON con el balance del usuario.
     * @throws Exception Si el token está vacío o ocurre un error.
     */
    public function getBalance($game_id)
    {
        try {
            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            $Proveedor = new Proveedor("", "ONETOUCH");


            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            }

            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);

            $saldo = intval(floatval(round($responseG->saldo, 5) * 100000));

            $return = array(
                "user" => $responseG->usuarioId,
                "status" => "RS_OK",
                "request_uuid" => $this->request_uuid,
                "currency" => $responseG->moneda,
                "balance" => $saldo
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un débito en el balance del usuario.
     *
     * @param string $gameId        Identificador del juego.
     * @param float  $debitAmount   Monto a debitar.
     * @param string $roundId       Identificador de la ronda.
     * @param string $transactionId Identificador de la transacción.
     * @param mixed  $datos         Datos adicionales de la transacción.
     *
     * @return string JSON con el resultado del débito.
     * @throws Exception Si el token está vacío o ocurre un error.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos)
    {
        $this->data = $datos;

        try {
            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado Onetouch */
            $Proveedor = new Proveedor("", "ONETOUCH");

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
            $this->transaccionApi->setIdentificador("ONETOUCH" . $roundId);

            $Game = new Game();

            $isfreeSpin = false;
            if (floatval($debitAmount) == 0) {
                $isfreeSpin = true;
            }
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $isfreeSpin);

            $this->transaccionApi = $responseG->transaccionApi;

            $saldo = intval(floatval(round($responseG->saldo, 5) * 100000));

            $respuesta = array(
                "user" => $responseG->usuarioId,
                "status" => "RS_OK",
                "request_uuid" => $this->request_uuid,
                "currency" => $responseG->moneda,
                "balance" => $saldo
            );


            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();


            return json_encode($respuesta);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un rollback (reversión) de una transacción específica.
     *
     * Este método se utiliza para revertir una transacción previamente realizada,
     * asegurando que los cambios realizados sean deshechos correctamente.
     *
     * @param string $roundId       Identificador de la ronda asociada.
     * @param string $transactionId Identificador de la transacción a revertir.
     * @param mixed  $datos         Datos adicionales relacionados con la transacción.
     *
     * @return string JSON con el resultado del rollback.
     * @throws Exception Si ocurre un error durante el proceso de rollback.
     */
    public function Rollback($roundId, $transactionId, $datos)
    {
        $this->method = 'cancelReserve';


        $this->data = $datos;

        try {
            /*  Obtenemos el Proveedor con el abreviado Onetouch */
            $Proveedor = new Proveedor("", "ONETOUCH");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);


            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            }

            try {
                $TransaccionApi2 = new TransaccionApi("", $transactionId, $Proveedor->getProveedorId());
                $jsonValue = json_decode($TransaccionApi2->getTValue());
                $valorTransaction = 0;

                $UsuarioMandante = new UsuarioMandante($TransaccionApi2->getUsuarioId());
                if (strpos($TransaccionApi2->getTipo(), 'DEBIT') !== false) {
                    $this->transaccionApi->setIdentificador($TransaccionApi2->getIdentificador());
                } else {
                    throw new Exception("Transaccion no es Debit", "10006");
                }
            } catch (Exception $e) {
                $transaccionNoExiste = true;
                throw new Exception("Transaccion no existe", "10005");
            }

            $Game = new Game();
            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi);

            $this->transaccionApi = $responseG->transaccionApi;
            $saldo = intval(floatval(round($responseG->saldo, 5) * 100000));

            $respuesta = array(
                "user" => $responseG->usuarioId,
                "status" => "RS_OK",
                "request_uuid" => $this->request_uuid,
                "currency" => $responseG->moneda,
                "balance" => $saldo
            );


            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();


            return json_encode($respuesta);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un crédito en el balance del usuario.
     *
     * Este método se utiliza para agregar un monto al balance del usuario en un juego específico.
     *
     * @param string  $gameId        Identificador del juego.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       Identificador de la ronda.
     * @param string  $transactionId Identificador de la transacción.
     * @param mixed   $datos         Datos adicionales de la transacción.
     * @param boolean $isBonus       Indica si el crédito es un bono (opcional, por defecto false).
     * @param boolean $finished      Indica si la ronda está finalizada (opcional, por defecto false).
     *
     * @return string JSON con el resultado del crédito.
     * @throws Exception Si el token está vacío o ocurre un error.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos, $isBonus = false, $finished = false)
    {
        $this->data = $datos;

        try {
            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }
            /*  Obtenemos el Proveedor con el abreviado Onetouch */
            $Proveedor = new Proveedor("", "ONETOUCH");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);
            $this->transaccionApi->setIdentificador("ONETOUCH" . $roundId);


            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            }

            /*  Obtenemos el producto con el gameId  */
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            $Game = new Game();

            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $finished, false, $isBonus);

            $this->transaccionApi = $responseG->transaccionApi;


            /*  Retornamos el mensaje satisfactorio  */
            $saldo = intval(floatval(round($responseG->saldo, 5) * 100000));

            $respuesta = array(
                "user" => $responseG->usuarioId,
                "status" => "RS_OK",
                "request_uuid" => $this->request_uuid,
                "currency" => $responseG->moneda,
                "balance" => $saldo
            );


            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();


            return json_encode($respuesta);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Finaliza una ronda en el contexto del proveedor ONETOUCH.
     *
     * Este método se utiliza para marcar el final de una ronda de juego,
     * registrando la transacción correspondiente y actualizando el estado en la base de datos.
     *
     * @param string $roundId  Identificador de la ronda.
     * @param mixed  $datos    Datos adicionales relacionados con la ronda.
     * @param string $currency Moneda utilizada en la transacción.
     *
     * @return string JSON con el resultado de la operación.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function EndRound($roundId, $datos, $currency)
    {
        $this->data = $datos;

        try {
            /*  Obtenemos el Proveedor con el abreviado ONETOUCH */
            $Proveedor = new Proveedor("", "ONETOUCH");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId(0);
            $this->transaccionApi->setTipo("ENDROUND");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);
            $this->transaccionApi->setIdentificador("ONETOUCH" . $roundId);


            $Game = new Game();
            $responseG = $Game->endRound($this->transaccionApi);

            $this->transaccionApi = $responseG->transaccionApi;

            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

            $respuesta = json_encode(array(
                "status" => "RS_OK",
                "request_uuid" => $this->request_uuid
            ));

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return json_encode($respuesta);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Convierte un error en una respuesta JSON adecuada para el proveedor ONETOUCH.
     *
     * Este método toma un código de error y un mensaje, los mapea a códigos y mensajes
     * específicos del proveedor, y genera una respuesta JSON que incluye información
     * adicional sobre la transacción y el estado del error.
     *
     * @param integer $code    Código de error recibido.
     * @param string  $message Mensaje de error recibido.
     *
     * @return string JSON con la respuesta del error convertida.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $response = array();


        $Proveedor = new Proveedor("", "ONETOUCH");

        switch ($code) {
            case 10011:
                $codeProveedor = "-101"; //Ok
                $messageProveedor = "user not found";
                break;

            case 21:
                $codeProveedor = "-101"; //Ok
                $messageProveedor = "user not found.";
                break;

            case 22:
                $codeProveedor = "-101"; //Ok
                $messageProveedor = "user not found or expired token.";
                break;

            case 20001: //Ok
                $codeProveedor = "-106"; //Ok
                $messageProveedor = "Insufficient balance";
                break;

            case 0: //Ok
                $codeProveedor = "-100"; //Ok
                $messageProveedor = "Internal system error";
                break;

            case 29: //Revisar
                $codeProveedor = "-111";
                $messageProveedor = "transaction already processed";
                break;

            case 10001:

                $codeProveedor = "0";
                $messageProveedor = "Success";

                if ($this->token != "") {
                    /*  Obtenemos el Usuario Token con el token */
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    /*  Obtenemos el Usuario Mandante con el Usuario Token */
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                }

                $Game = new Game();

                $responseG = $Game->getBalance($UsuarioMandante);

                $tipo = $this->transaccionApi->getTipo();
                $TransaccionApi = new TransaccionApi("", $this->transaccionApi->getTransaccionId(), $Proveedor->getProveedorId());

                $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

                /*  Retornamos el mensaje satisfactorio  */
                $response = array(
                    "transactionId" => $TransaccionApi->transapiId,
                    "cash" => $saldo,
                    "currency" => $responseG->moneda,
                    "bonus" => 0,
                    "usedPromo" => 0,
                    "error" => "0",
                    "description" => "Success"
                );


                break;

            case 10005: //Revisar
                $codeProveedor = "-108";
                $messageProveedor = "duplicate remotetranid";
                break;

            case 20002: //OK
                $codeProveedor = "-103";
                $messageProveedor = "invalid md5/hash";
                break;

            default: //OK

                $codeProveedor = "120";
                $messageProveedor = "Internal server error";
                break;
        }

        if ($codeProveedor != "") {
            $respuesta = json_encode(array_merge($response, array(
                "errorCode" => $codeProveedor,
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
