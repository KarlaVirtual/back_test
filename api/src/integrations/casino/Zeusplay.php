<?php

/**
 * Clase Zeusplay para la integración con el proveedor ZEUSPLAY.
 *
 * Este archivo contiene métodos para manejar transacciones de débito, crédito,
 * rollback y consulta de saldo en la integración con ZEUSPLAY.
 * Proporciona funcionalidades para interactuar con usuarios, productos y
 * transacciones a través de la API del proveedor.
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
use Backend\dto\ProductoDetalle;
use Backend\dto\ProductoMandante;
use Backend\dto\Proveedor;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransaccionApiMySqlDAO;
use Backend\websocket\WebsocketUsuario;
use Exception;

/**
 * Clase Zeusplay.
 *
 * Esta clase proporciona métodos para la integración con el proveedor ZEUSPLAY,
 * incluyendo manejo de transacciones de débito, crédito, rollback y consulta de saldo.
 */
class Zeusplay
{
    /**
     * Token de sesión del usuario.
     *
     * @var string
     */
    private $token;

    /**
     * Identificador externo del jugador.
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
     * Constructor de la clase Zeusplay.
     *
     * @param string $sessionId       Token de sesión del usuario.
     * @param string $partnerPlayerId Identificador externo del jugador.
     */
    public function __construct($sessionId = "", $partnerPlayerId = "")
    {
        $this->token = $sessionId;
        $this->externalId = $partnerPlayerId;
    }

    /**
     * Obtiene el saldo del jugador.
     *
     * @param string $partnerPlayerId Identificador externo del jugador (opcional).
     *
     * @return string JSON con el saldo del jugador y la moneda.
     */
    public function getBalance($partnerPlayerId = "")
    {
        $this->externalId = $partnerPlayerId;


        try {
            $Proveedor = new Proveedor("", "ZEUSPLAY");


            if ($this->externalId != "") {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            } else {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            }


            $Game = new Game();

            $responseG = $Game->getBalance($UsuarioMandante);

            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 4), 4, '.', null));


            $return = array(
                "playerWallet" => array(
                    "currencyCode" => $UsuarioMandante->moneda,
                    "amount" => $saldo
                )
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza una transacción de débito.
     *
     * @param string $gameId        Identificador del juego.
     * @param float  $debitAmount   Monto a debitar.
     * @param string $roundId       Identificador de la ronda.
     * @param string $transactionId Identificador de la transacción.
     * @param array  $datos         Datos adicionales de la transacción.
     *
     * @return string JSON con el saldo actualizado del jugador.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos)
    {
        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado ZEUSPLAY */
            $Proveedor = new Proveedor("", "ZEUSPLAY");

            if ($this->externalId != "") {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }


            /*  Obtenemos el producto con el gameId  */
            $Producto = new Producto('', $gameId, $Proveedor->getProveedorId());


            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);
            $this->transaccionApi->setIdentificador("ZEUSPLAY" . $roundId);

            $Game = new Game();

            $isfreeSpin = false;
            if (floatval($debitAmount) == 0) {
                $isfreeSpin = false;
            }

            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $isfreeSpin);

            $this->transaccionApi = $responseG->transaccionApi;

            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

            $return = array(
                "playerWallet" => array(
                    "currencyCode" => $UsuarioMandante->moneda,
                    "amount" => $saldo
                )
            );


            /*  Guardamos la Transaccion Api necesaria de estado OK   */
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
     * @param string  $RefTransactionId  Identificador de la transacción de referencia.
     * @param string  $GameCode          Código del juego.
     * @param string  $RoundId           Identificador de la ronda.
     * @param boolean $CancelEntireRound Indica si se cancela toda la ronda.
     * @param string  $TransactionId     Identificador de la transacción.
     * @param float   $RollbackAmount    Monto a revertir.
     * @param array   $datos             Datos adicionales de la transacción.
     *
     * @return string JSON con el saldo actualizado del jugador.
     */
    public function Rollback($RefTransactionId, $GameCode, $RoundId, $CancelEntireRound = false, $TransactionId, $RollbackAmount, $datos)
    {
        try {
            /*  Obtenemos el Proveedor con el abreviado ZEUSPLAY */
            $Proveedor = new Proveedor("", "ZEUSPLAY");

            if ($this->externalId != "") {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                if ($this->externalId != "") {
                    if ($UsuarioMandante->usumandanteId != $UsuarioToken->usuarioId) {
                        throw new Exception("Usuario no coincide con token", "30012");
                    }
                }
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            if ($CancelEntireRound) {
                $TransaccionJuego = new TransaccionJuego("", "ZEUSPLAY" . $RoundId);

                $rules = []; //Regla para filtrar en la tabla Transjuego_log con el campo transjuego_id
                array_push($rules, array("field" => "transjuego_log.transjuego_id", "data" => $TransaccionJuego->getTransjuegoId(), "op" => "eq"));


                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $select = "transjuego_log.*";
                $grouping = "transjuego_log.transjuegolog_id";


                $TransjuegoLog = new TransjuegoLog();
                $Transactions = $TransjuegoLog->getTransjuegoLogsCustom($select, "transjuego_log.transjuegolog_id", "asc", 0, 100, $json, true, $grouping);
                $Transactions = json_decode($Transactions);

                foreach ($Transactions->data as $key => $transjuego) {
                    $RefTransactionId = $transjuego->{"transjuego_log.transaccion_id"};
                    $RefTransactionId = explode("_", $RefTransactionId)[0];

                    $TransactionId = $TransactionId . '_' . $key;

                    /*  Creamos la Transaccion API  */
                    $this->transaccionApi = new TransaccionApi();
                    $this->transaccionApi->setTransaccionId('ROLLBACK' . $TransactionId);
                    $this->transaccionApi->setTipo("ROLLBACK");
                    $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
                    $this->transaccionApi->setTValue(json_encode($datos));
                    $this->transaccionApi->setUsucreaId(0);
                    $this->transaccionApi->setUsumodifId(0);
                    $this->transaccionApi->setValor($RollbackAmount);


                    $TransaccionApi2 = new TransaccionApi("", $RefTransactionId, $Proveedor->getProveedorId()); //TransaccionApi Anterior DEBIT


                    if (strpos($TransaccionApi2->getTipo(), 'DEBIT') !== false) {
                        $this->transaccionApi->setIdentificador($TransaccionApi2->getIdentificador());
                        $AllowCreditTransaction = false;
                    } else {
                        if (strpos($TransaccionApi2->getTipo(), 'CREDIT') !== false) {
                            $this->transaccionApi->setIdentificador($TransaccionApi2->getIdentificador());
                            $AllowCreditTransaction = true;
                        } else {
                            throw new Exception("Transaccion no es Debit ni Credit", "10006");
                        }
                    }

                    $this->transaccionApi->setTransaccionId('ROLLBACK' . $RefTransactionId);
                    //  Verificamos que la transaccionId no se haya procesado antes
                    if ($this->transaccionApi->existsTransaccionIdAndProveedor("OK")) {
                        //  Si la transaccionId ha sido procesada, reportamos el error
                        throw new Exception("Transaccion ya procesada", "10001");
                    }
                    $this->transaccionApi->setTransaccionId('ROLLBACK' . $TransactionId);


                    $Game = new Game();

                    $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false, $RefTransactionId, true, false, $AllowCreditTransaction, true);

                    $this->transaccionApi = $responseG->transaccionApi;
                    $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));


                    $return = array(
                        "playerWallet" => array(
                            "currencyCode" => $UsuarioMandante->moneda,
                            "amount" => $saldo
                        )
                    );

                    /*  Guardamos la Transaccion Api necesaria de estado OK   */
                    $this->transaccionApi->setRespuesta(json_encode($return));
                    $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                    $TransaccionApiMySqlDAO->update($this->transaccionApi);
                    $TransaccionApiMySqlDAO->getTransaction()->commit();

                    $TransaccionApi22 = $this->transaccionApi;

                    $this->transaccionApi->setTransaccionId('ROLLBACK' . $RefTransactionId);
                    $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                    $TransaccionApiMySqlDAO->insert($TransaccionApi22);
                    $TransaccionApiMySqlDAO->getTransaction()->commit();
                }

                $TransaccionJuego = new TransaccionJuego("", "ZEUSPLAY" . $RoundId);
                $TransaccionJuego->setTransaccionId("DELZ_DELZ_" . $TransaccionJuego->getTransaccionId());

                $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
                $TransaccionJuegoMySqlDAO->update($TransaccionJuego);
                $TransaccionJuegoMySqlDAO->getTransaction()->commit();
            }


            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza una transacción de crédito.
     *
     * @param string  $gameId        Identificador del juego.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       Identificador de la ronda.
     * @param string  $transactionId Identificador de la transacción.
     * @param array   $datos         Datos adicionales de la transacción.
     * @param boolean $isBonus       Indica si es un bono.
     * @param boolean $finished      Indica si la transacción está finalizada.
     *
     * @return string JSON con el saldo actualizado del jugador.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos, $isBonus = false, $finished = false)
    {
        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }
            /*  Obtenemos el Proveedor con el abreviado ZEUSPLAY */
            $Proveedor = new Proveedor("", "ZEUSPLAY");

            if ($this->externalId != "") {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);
            $this->transaccionApi->setIdentificador("ZEUSPLAY" . $roundId);


            try {
                $TransaccionJuego = new TransaccionJuego("", "ZEUSPLAY" . $roundId);
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            $ProductoMandante = new ProductoMandante("", "", $TransaccionJuego->productoId);
            /*  Obtenemos el producto con el $TransaccionJuego->productoId */
            $Producto = new Producto($ProductoMandante->productoId);

            $Game = new Game();

            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $finished, false, $isBonus);

            $this->transaccionApi = $responseG->transaccionApi;


            /*  Retornamos el mensaje satisfactorio  */
            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

            $return = array(
                "playerWallet" => array(
                    "currencyCode" => $UsuarioMandante->moneda,
                    "amount" => $saldo
                )
            );


            /*  Guardamos la Transaccion Api necesaria de estado OK   */
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
     * Convierte un error en un formato JSON estándar.
     *
     * @param integer $code    Código del error.
     * @param string  $message Mensaje del error.
     *
     * @return string JSON con el mensaje y código de error.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = 1000;
        $messageProveedor = "Something went wrong";

        $respuesta = array(
            "message" => $messageProveedor,
            "errorCode" => $codeProveedor
        );


        return json_encode($respuesta);
    }
}
