<?php

/**
 * Clase Aadvark para la integración con el proveedor de juegos AADVARK.
 *
 * Esta clase contiene métodos para realizar operaciones como autenticación,
 * consulta de saldo, débito, crédito, y reversión de transacciones relacionadas
 * con el proveedor AADVARK.
 *
 * @category   Integración
 * @package    API
 * @subpackage Casino
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;

use Backend\dto\Subproveedor;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransaccionApi;
use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioToken;
use Backend\dto\Producto;
use Backend\dto\ProductoMandante;
use Backend\dto\Proveedor;
use Backend\mysql\TransaccionApiMySqlDAO;
use Exception;

/**
 * Clase principal para la integración con el proveedor de juegos AADVARK.
 *
 * Esta clase contiene métodos para realizar operaciones como autenticación,
 * consulta de saldo, débito, crédito y reversión de transacciones relacionadas
 * con el proveedor AADVARK.
 */
class Aadvark
{
    /**
     * Token de autenticación.
     *
     * @var string
     */
    private $token;

    /**
     * ID del jugador.
     *
     * @var string
     */
    private $playerId;

    /**
     * ID de la ronda.
     *
     * @var string
     */
    private $roundId;

    /**
     * Constructor de la clase Aadvark.
     *
     * @param string $token    Token de autenticación.
     * @param string $playerId Opcional ID del jugador.
     */
    public function __construct($token, $playerId = '')
    {
        $this->token = $token;
        $this->playerId = $playerId;
    }

    /**
     * Autentica al usuario con el proveedor AADVARK.
     *
     * @return string Respuesta en formato JSON con los datos del usuario autenticado.
     * @throws Exception Si el token está vacío o ocurre un error durante la autenticación.
     */
    public function Auth()
    {
        try {
            $Proveedor = new Proveedor("", "AADVARK");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);

            if ($this->token == "") {
                throw new Exception("Token vacio", "10030");
            }

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $return = [
                "id" => $UsuarioMandante->usumandanteId,
                "wallets" => [
                    [
                        "id" => $UsuarioMandante->usumandanteId,
                        "name" => $Usuario->nombre,
                        "balance" => round($Usuario->getBalance(), 2),
                        "currencyCode" => $UsuarioMandante->moneda
                    ]
                ],
            ];

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el saldo del usuario.
     *
     * @return string Respuesta en formato JSON con el saldo del usuario.
     * @throws Exception Si el token o el ID del jugador están vacíos.
     */
    public function getBalance()
    {
        try {
            $Proveedor = new Proveedor("", "AADVARK");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("BALANCE");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);

            if ($this->token == "" && $this->playerId == "") {
                throw new Exception("Token vacio", "10011");
            }

            try {
                $UsuarioToken = new UsuarioToken('', $Proveedor->getProveedorId(), $this->playerId);
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } catch (Exception $e) {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            }

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);

            $return = [
                "id" => $UsuarioMandante->usumandanteId,
                "wallets" => [
                    [
                        "id" => $UsuarioMandante->usumandanteId,
                        "name" => $Usuario->nombre,
                        "balance" => round($Usuario->getBalance(), 2),
                        "currencyCode" => $UsuarioMandante->moneda
                    ]
                ],
            ];
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
     * @param array   $datos         Datos adicionales de la transacción.
     * @param boolean $freespin      Opcional Indica si es un giro gratis.
     *
     * @return string Respuesta en formato JSON con el ID de la transacción.
     * @throws Exception Si el token está vacío o el juego no está disponible.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $freespin = false)
    {
        $this->data = $datos;

        try {
            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            //Obtenemos el Proveedor con el abreviado AADVARK
            $Proveedor = new Proveedor("", "AADVARK");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);

            try {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } catch (Exception $e) {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            }

            $this->transaccionApi->setIdentificador($roundId . "AADVARK");

            //Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            if (($UsuarioToken->getUsuarioId() == 65395) && (in_array($Proveedor->getProveedorId(), array('12', '68', '67')) || in_array($Producto->getProductoId(), array('5734', '5738', '5741', '5744', '5747', '5768')))) {
                throw new Exception("Juego no disponible ", "10011");
            }

            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin);
            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
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
     * @param array   $datos         Datos adicionales de la transacción.
     * @param boolean $isEndRound    Indica si es el final de la ronda.
     *
     * @return string Respuesta en formato JSON con el ID de la transacción.
     * @throws Exception Si ocurre un error durante la operación.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos, $isEndRound)
    {
        $this->tipo = "CREDIT";

        $this->roundId = $roundId;

        $this->data = $datos;

        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try {
            //Obtenemos el Proveedor con el abreviado Spinomenal
            $Proveedor = new Proveedor("", "AADVARK");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);

            $TransccionJuego = new TransaccionJuego('', $roundId . 'AADVARK');

            $UsuarioMandante = new UsuarioMandante($TransccionJuego->usuarioId);

            $this->transaccionApi->setIdentificador($roundId . "AADVARK");

            //  Obtenemos el producto con el gameId
            $ProdMandante = new ProductoMandante('', '', $TransccionJuego->productoId);

            $Producto = new Producto($ProdMandante->productoId);

            //  Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            //  Agregamos Elementos a la Transaccion API
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());

            $Game = new Game();

            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $isEndRound);

            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
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
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza una reversión de una transacción.
     *
     * @param string $roundId       ID de la ronda.
     * @param string $transactionId ID de la transacción.
     * @param string $player        ID del jugador.
     * @param array  $datos         Datos adicionales de la transacción.
     *
     * @return string Respuesta en formato JSON con el saldo actualizado.
     * @throws Exception Si la transacción no existe o ocurre un error.
     */
    public function Rollback($roundId, $transactionId, $player, $datos)
    {
        $this->tipo = "ROLLBACK";

        $this->data = $datos;

        try {
            $Proveedor = new Proveedor("", "AADVARK");

            //  Obtenemos el Usuario Token con el token
            $UsuarioToken = new UsuarioToken('', $Proveedor->getProveedorId(), $this->usuarioId);
            //  Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            //Obtenemos el Proveedor con el abreviado Expanse
            $Proveedor = new Proveedor("", "AADVARK");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);
            $this->transaccionApi->setIdentificador($roundId . "AADVARK");


            try {
                $TransaccionApi2 = new TransaccionApi("", $transactionId, $Proveedor->getProveedorId());
                $producto = $TransaccionApi2->getProductoId();

                $UsuarioMandante = new UsuarioMandante($TransaccionApi2->getUsuarioId());
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            $this->transaccionApi->setProductoId($producto);

            $Game = new Game();

            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false, "", true, false, true);

            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "Balance" => round($Usuario->getBalance(), 2),
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
     * Convierte un error en una respuesta JSON.
     *
     * @param integer $code    Código del error.
     * @param string  $message Mensaje del error.
     *
     * @return string Respuesta en formato JSON con los detalles del error.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $response = array();

        $Proveedor = new Proveedor("", "AADVARK");
        try {
            $TransccionJuego = new TransaccionJuego('', $this->roundId . 'AADVARK');

            $UsuarioMandante = new UsuarioMandante($TransccionJuego->usuarioId);
            $Game = new Game();

            $responseG = $Game->getBalance($UsuarioMandante);
            $saldo = $responseG->saldo;
        } catch (Exception $e) {
            $saldo = 0;
        }

        switch ($code) {
            case 10001:
                $codeProveedor = '';

                $response = array(
                    "Balance" => $saldo,
                );
                break;

            case 24:
                $codeProveedor = 'unauthorized';
                $messageProveedor = "User does not exist";
                break;

            case 20001:
                $codeProveedor = 'insufficient_balance';
                $messageProveedor = "User has insufficient balance";
                break;

            case 20014:
                $codeProveedor = 'rejected';
                $messageProveedor = "User limits reached";
                break;

            case 28:
                $codeProveedor = 5;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            default:
                $codeProveedor = 100000;
                $messageProveedor = "General Error";
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
            $this->transaccionApi->setRespuestaCodigo("ERROR_" . $code);
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->insert($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();
        }

        return $respuesta;
    }


}
