<?php

/**
 * Clase General para la integración con el casino.
 *
 * Esta clase contiene métodos para manejar transacciones de autenticación, balance,
 * débitos, créditos, reversión de transacciones y validaciones relacionadas con el casino.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;

use Backend\dto\Mandante;
use Backend\dto\Pais;
use Backend\dto\PromocionalLog;
use Backend\dto\Subproveedor;
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
 * Clase General para manejar la integración con el casino.
 *
 * Esta clase proporciona métodos para realizar autenticación,
 * consultar balances, realizar débitos, créditos, y manejar
 * transacciones relacionadas con el casino.
 */
class General
{
    /**
     * Identificador del operador.
     *
     * @var integer|null
     */
    private $operadorId;

    /**
     * Token de autenticación.
     *
     * @var string
     */
    private $token;

    /**
     * Identificador único del usuario.
     *
     * @var string|null
     */
    private $uid;

    /**
     * Firma de seguridad.
     *
     * @var string
     */
    private $sign;

    /**
     * ID externo asociado a la transacción.
     *
     * @var string
     */
    private $externalId;

    /**
     * Objeto de transacción API.
     *
     * @var TransaccionApi|null
     */
    private $transaccionApi;

    /**
     * Datos adicionales relacionados con la transacción.
     *
     * @var mixed
     */
    private $data;

    /**
     * Identificador de la ronda principal.
     *
     * @var string|null
     */
    private $roundIdSuper;

    /**
     * Constructor de la clase General.
     *
     * @param string $token      Token de autenticación.
     * @param string $sign       Firma de seguridad.
     * @param string $externalId ID externo (opcional).
     */
    public function __construct($token, $sign, $externalId = "")
    {
        $this->token = $token;
        $this->sign = $sign;
        $this->externalId = $externalId;
    }

    /**
     * Obtiene el ID del operador.
     *
     * @return integer|null ID del operador.
     */
    public function getOperadorId()
    {
        return $this->operadorId;
    }

    /**
     * Autentica al usuario y devuelve información del jugador.
     *
     * @return string JSON con los datos del jugador o un error.
     * @throws Exception Si el token o la firma son inválidos.
     */
    public function Auth()
    {
        try {
            if ($this->sign != 'ZQaMmI597Z8kvvFJRls589adWFqP35ek') {
                throw new Exception("Token vacio", "10011");
            }

            $Proveedor = new Proveedor("", "GENERAL");

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
            $Pais = new Pais($UsuarioMandante->getPaisId());
            $return = array(

                "error" => '0',
                "code" => '0',
                "player" => array(
                    "userid" => $UsuarioMandante->usumandanteId,
                    "typeidentitycard" => $responseG->tipoDocumento,
                    "identitycard" => $responseG->documento,
                    "email" => $responseG->email,
                    "balance" => round($responseG->saldo, 2),
                    "name" => $UsuarioMandante->getNombres(),
                    "lastname" => $UsuarioMandante->getApellidos(),
                    "currency" => $responseG->moneda,
                    "country" => $Pais->iso,
                    "dirip" => '',
                    "language" => ''
                )
            );

            return json_encode($return);
        } catch (Exception $e) {
            if ($_ENV['debug']) {
                print_r($e);
            }

            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del jugador.
     *
     * @return string JSON con el balance del jugador o un error.
     * @throws Exception Si el token o la firma son inválidos.
     */
    public function getBalance()
    {
        try {
            if ($this->sign != 'ZQaMmI597Z8kvvFJRls589adWFqP35ek') {
                throw new Exception("Token vacio", "10011");
            }

            $Proveedor = new Proveedor("", "GENERAL");

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

                "error" => '0',
                "code" => '0',
                "player" => array(
                    "userid" => $UsuarioMandante->usumandanteId,
                    "balance" => round($responseG->saldo, 2),
                    "currency" => $responseG->moneda,
                )
            );

            return json_encode($return);
        } catch (Exception $e) {
            if ($_ENV['debug']) {
                print_r($e);
            }

            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un débito en la cuenta del jugador.
     *
     * @param string  $gameId        ID del juego.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param mixed   $datos         Datos adicionales.
     * @param boolean $freespin      Indica si es un giro gratis (opcional).
     *
     * @return string JSON con el resultado del débito o un error.
     * @throws Exception Si el token o la firma son inválidos.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $freespin = false)
    {
        $this->data = $datos;

        try {
            if ($this->sign != 'ZQaMmI597Z8kvvFJRls589adWFqP35ek') {
                throw new Exception("Token vacio", "10011");
            }

            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            //Obtenemos el Proveedor con el abreviado GENERAL
            $Proveedor = new Proveedor("", "GENERAL");

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

            $this->transaccionApi->setIdentificador($roundId . "GENERAL");


            try {
                //Obtenemos el producto con el gameId
                $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
            } catch (Exception $e) {
                if ($e->getCode() == '26') {
                    $gameId = 'General';
                    $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
                }
            }

            if (($UsuarioToken->getUsuarioId() == 65395) && (in_array($Proveedor->getProveedorId(), array('12', '68', '67')) || in_array($Producto->getProductoId(), array('5734', '5738', '5741', '5744', '5747', '5768')))) {
                throw new Exception("Juego no disponible ", "10011");
            }

            //Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin);
            $this->transaccionApi = $responseG->transaccionApi;


            $return = array(
                "error" => 0,
                "code" => 0,
                "balance" => round($responseG->saldo, 2),
                "transactionid" => $responseG->transaccionId
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
     * Realiza una reversión de una transacción.
     *
     * @param float  $rollbackAmount      Monto a revertir.
     * @param string $transactionRollback ID de la transacción a revertir.
     * @param string $transactionId       ID de la nueva transacción.
     * @param mixed  $datos               Datos adicionales.
     *
     * @return string JSON con el resultado de la reversión o un error.
     * @throws Exception Si el token o la firma son inválidos.
     */
    public function Rollback($rollbackAmount, $transactionRollback, $transactionId, $datos)
    {
        $this->data = $datos;


        try {
            if ($this->sign != 'ZQaMmI597Z8kvvFJRls589adWFqP35ek') {
                throw new Exception("Token vacio", "10011");
            }

            //Obtenemos el Proveedor con el abreviado GENERAL
            $Proveedor = new Proveedor("", "GENERAL");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionRollback);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);

            $identificador = "";

            try {
                $SubProveedor = new Subproveedor("", "GENERAL");
                $TransjuegoLog = new TransjuegoLog("", "", "", $transactionRollback . '_' . $SubProveedor->getSubproveedorId(), $SubProveedor->getSubproveedorId());
                $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                $this->transaccionApi->setIdentificador($TransaccionJuego->getTicketId());
            } catch (Exception $e) {
                if ($_ENV['debug']) {
                    print_r($e);
                }

                throw new Exception("Transaccion no existe", "10005");
            }

            $Game = new Game();

            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false);

            $this->transaccionApi = $responseG->transaccionApi;


            $return = array(
                "error" => 0,
                "code" => 0,
                "balance" => round($responseG->saldo, 2),
                "transactionid" => $responseG->transaccionId
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
     * Realiza un crédito en la cuenta del jugador.
     *
     * @param string $gameId        ID del juego.
     * @param float  $creditAmount  Monto a acreditar.
     * @param string $roundId       ID de la ronda.
     * @param string $transactionId ID de la transacción.
     * @param mixed  $datos         Datos adicionales.
     *
     * @return string JSON con el resultado del crédito o un error.
     * @throws Exception Si el token o la firma son inválidos.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos)
    {
        $this->data = $datos;

        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try {
            if ($this->sign != 'ZQaMmI597Z8kvvFJRls589adWFqP35ek') {
                throw new Exception("Token vacio", "10011");
            }

            //Obtenemos el Proveedor con el abreviado GENERAL
            $Proveedor = new Proveedor("", "GENERAL");

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
                $TransaccionJuego = new TransaccionJuego("", $roundId . "GENERAL");
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                $this->transaccionApi->setIdentificador($roundId . "GENERAL");
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            $ProductoMandante = new ProductoMandante("", "", $TransaccionJuego->productoId);
            /*  Obtenemos el producto con el $TransaccionJuego->productoId */
            $Producto = new Producto($ProductoMandante->productoId);

            //Agregamos Elementos a la Transaccion API
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());


            $Game = new Game();

            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, true);

            $this->transaccionApi = $responseG->transaccionApi;


            $return = array(
                "error" => 0,
                "code" => 0,
                "balance" => round($responseG->saldo, 2),
                "transactionid" => $responseG->transaccionId
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
     * Verifica un parámetro y devuelve información básica.
     *
     * @param mixed $param Parámetro a verificar.
     *
     * @return string JSON con la información del nodo y el parámetro.
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
     * Convierte un error en un formato JSON estándar.
     *
     * @param integer $code    Código del error.
     * @param string  $message Mensaje del error.
     *
     * @return string JSON con el error convertido.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $response = array();

        $Proveedor = new Proveedor("", "GENERAL");

        switch ($code) {
            case 10011:
                $codeProveedor = 1;
                $messageProveedor = "Invalid Token";
                break;

            case 21:
                $codeProveedor = 1;
                $messageProveedor = "Invalid Token";
                break;

            case 22:
                $codeProveedor = 1;
                $messageProveedor = "Invalid Token";
                break;

            case 20001:
                $codeProveedor = "4";
                $messageProveedor = "Not Enough Balance";
                break;

            case 0:
                $codeProveedor = 9;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 27:
                $codeProveedor = 5;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 28:
                $codeProveedor = 5;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 29:
                $codeProveedor = 5;
                $messageProveedor = "Transaction Not Found";
                break;

            case 10001:
                $codeProveedor = 6;
                $messageProveedor = "Transaction Exists";
                break;

            case 10004:
                $codeProveedor = 9;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 10014:
                $codeProveedor = 9;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 10010:
                $codeProveedor = 9;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 10005:
                $codeProveedor = 5;
                $messageProveedor = "Transaction Not Found";
                break;

            default:
                $codeProveedor = 9;
                $messageProveedor = "General Error. (" . $code . ")";
                break;
        }


        if ($codeProveedor != "") {
            $respuesta = json_encode(array_merge($response, array(
                "error" => 1,
                "code" => $codeProveedor
            )));
        } else {
            $respuesta = json_encode(array_merge($response));
        }

        if ($this->transaccionApi != null) {
            $this->transaccionApi->setTipo("R" . $this->transaccionApi->getTipo());
            $this->transaccionApi->setRespuestaCodigo("ERROR");
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->insert($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();
        }

        return $respuesta;
    }


}
