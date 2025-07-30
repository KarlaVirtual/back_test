<?php

/**
 * Clase Habanero para la integración con el proveedor de juegos Habanero.
 *
 * Este archivo contiene la implementación de métodos para manejar transacciones
 * relacionadas con el proveedor de juegos Habanero, como autenticación, débitos,
 * créditos, consultas de saldo y manejo de errores.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */

namespace Backend\integrations\casino;

use Exception;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\Subproveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransaccionApi;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\dto\TransaccionJuego;
use Backend\mysql\TransaccionApiMySqlDAO;

/**
 * Clase Habanero.
 *
 * Esta clase implementa la integración con el proveedor de juegos Habanero,
 * proporcionando métodos para manejar transacciones como autenticación,
 * débitos, créditos, consultas de saldo, y más.
 */
class Habanero
{
    /**
     * Token de autenticación utilizado para las transacciones.
     *
     * @var string
     */
    private $token;

    /**
     * Firma de seguridad para validar las transacciones.
     *
     * @var string
     */
    private $sign;

    /**
     * ID del usuario asociado a la transacción.
     *
     * @var string
     */
    private $usuarioId;

    /**
     * Objeto que representa la transacción API.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * Datos adicionales relacionados con la transacción.
     *
     * @var mixed
     */
    private $data;

    /**
     * Tipo de operación realizada (e.g., AUTH, DEBIT, CREDIT).
     *
     * @var string
     */
    private $tipo;

    /**
     * Constructor de la clase Habanero.
     *
     * @param string $token     Token de autenticación.
     * @param string $sign      Firma de seguridad.
     * @param string $usuarioId Opcional ID del usuario.
     */
    public function __construct($token, $sign, $usuarioId = "")
    {
        $this->token = $token;
        $this->sign = $sign;
        $this->usuarioId = $usuarioId;
    }

    /**
     * Método para autenticar al usuario con el proveedor Habanero.
     *
     * @return string Respuesta en formato JSON con los detalles del jugador.
     * @throws Exception Si el token está vacío o ocurre un error durante la autenticación.
     */
    public function Auth()
    {
        $this->tipo = "AUTH";
        try {
            $Proveedor = new Proveedor("", "HABANERO");

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
                "playerdetailresponse" => array(
                    "status" => array(
                        "success" => true,
                        "autherror" => false,
                        "message" => ""
                    ),
                    "accountid" => $UsuarioMandante->usumandanteId,
                    "accountname" => $UsuarioMandante->nombres,
                    "balance" => floatval(round($responseG->saldo, 2)),
                    "currencycode" => $UsuarioMandante->getMoneda()

                )
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para obtener el saldo del usuario.
     *
     * @return string Respuesta en formato JSON con el saldo del jugador.
     * @throws Exception Si el token está vacío o ocurre un error durante la consulta.
     */
    public function getBalance()
    {
        try {
            $Proveedor = new Proveedor("", "HABANERO");

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
                "playerdetailresponse" => array(
                    "status" => array(
                        "success" => true,
                        "autherror" => false,
                        "message" => ""
                    ),
                    "accountid" => $UsuarioMandante->usumandanteId,
                    "accountname" => $UsuarioMandante->nombres,
                    "balance" => floatval(round($responseG->saldo, 2)),
                    "currencycode" => $UsuarioMandante->getMoneda()

                )
            );


            return json_encode($return);
        } catch (Exception $e) {
        }
    }

    /**
     * Método para realizar un débito en la cuenta del usuario.
     *
     * @param string  $gameId        ID del juego.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param mixed   $datos         Datos adicionales de la transacción.
     * @param boolean $freespin      Indica si es un giro gratis.
     *
     * @return string Respuesta en formato JSON con el resultado del débito.
     * @throws Exception Si el token está vacío o ocurre un error durante el débito.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $freespin)
    {
        $this->tipo = "DEBIT";
        $this->data = $datos;
        $timeInit = time();


        try {
            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            //Obtenemos el Proveedor con el abreviado HABANERO
            $Proveedor = new Proveedor("", "HABANERO");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);


            //Obtenemos el Usuario Token con el token
            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            //Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "HABANERO");
            //Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());


            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin);


            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "fundtransferresponse" => array(
                    "status" => array(
                        "success" => true,
                        "autherror" => false,
                        "nofunds" => false
                    ),
                    "balance" => floatval(round($responseG->saldo, 2)),
                    "currencycode" => $UsuarioMandante->getMoneda()
                )
            );


            //Guardamos la Transaccion Api necesaria de estado OK
            $this->transaccionApi->setRespuestaCodigo("OK");
            $this->transaccionApi->setRespuesta(json_encode($return));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            try {
                if ($UsuarioMandante->usumandanteId == 16) {
                    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . ((time() - $timeInit) * 1000) . 'msFinal ' . $this->transaccionApi->getTransaccionId() . ' ' . json_encode($return) . "' '#virtualsoft-cron' > /dev/null & ");
                }
            } catch (Exception $e) {
            }

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para consultar el estado de una transacción de débito.
     *
     * @param string $transactionId ID de la transacción.
     *
     * @return string Respuesta en formato JSON con el estado de la transacción.
     * @throws Exception Si el token está vacío o ocurre un error durante la consulta.
     */
    public function DebitQuery($transactionId)
    {
        try {
            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            //Obtenemos el Proveedor con el abreviado Booongo
            $Proveedor = new Proveedor("", "HABANERO");

            //Creamos la Transaccion API
            $transactionObje = new TransaccionApi("", $transactionId, $Proveedor->getProveedorId());

            if ($transactionObje->respuestaCodigo == 'OK') {
                $return = array(
                    "fundtransferresponse" => array(
                        "status" => array(
                            "success" => true,
                        ),

                    )
                );
            } else {
                $return = array(
                    "fundtransferresponse" => array(
                        "status" => array(
                            "success" => false
                        ),
                    )
                );
            }


            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para consultar el estado de una transacción de crédito.
     *
     * @param string $transactionId ID de la transacción.
     *
     * @return string Respuesta en formato JSON con el estado de la transacción.
     * @throws Exception Si el token está vacío o ocurre un error durante la consulta.
     */
    public function CreditQuery($transactionId)
    {
        try {
            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            //Obtenemos el Proveedor con el abreviado Booongo
            $Proveedor = new Proveedor("", "HABANERO");

            try {
                $SubProveedor = new Subproveedor("", "HABANERO");
                $TransjuegoLog = new TransjuegoLog("", "", "", $transactionId . '_' . $SubProveedor->getSubproveedorId(), $SubProveedor->getSubproveedorId());

                $return = array(
                    "fundtransferresponse" => array(
                        "status" => array(
                            "success" => true,
                        ),

                    )
                );
            } catch (Exception $e) {
                if ($e->getCode() == "28") {
                    $return = array(
                        "fundtransferresponse" => array(
                            "status" => array(
                                "success" => false
                            ),
                        )
                    );
                }
            }

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para realizar un rollback de una transacción.
     *
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $roundId        ID de la ronda.
     * @param string $transactionId  ID de la transacción.
     * @param string $player         ID del jugador.
     * @param mixed  $datos          Datos adicionales de la transacción.
     *
     * @return string Respuesta en formato JSON con el resultado del rollback.
     * @throws Exception Si ocurre un error durante el rollback.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos)
    {
        $this->tipo = "ROLLBACK";
        $usuarioid = $player;

        $this->data = $datos;


        try {
            //Obtenemos el Proveedor con el abreviado HABANERO
            $Proveedor = new Proveedor("", "HABANERO");

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
                $SubProveedor = new Subproveedor("", "HABANERO");
                $TransjuegoLog = new TransjuegoLog("", "", "", $transactionId . '_' . $SubProveedor->getSubproveedorId(), $SubProveedor->getSubproveedorId());
                $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                $this->transaccionApi->setIdentificador($TransaccionJuego->getTicketId());
                $producto = $TransjuegoLog->getProductoId();
                $this->transaccionApi->setProductoId($producto);
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }
            $Game = new Game();

            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false);

            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "fundtransferresponse" => array(
                    "status" => array(
                        "success" => true,
                        "refundstatus" => 1,
                    ),
                    "balance" => floatval(round($responseG->saldo, 2)),
                    "currencycode" => $UsuarioMandante->getMoneda()
                )
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
     * Método para realizar un crédito en la cuenta del usuario.
     *
     * @param string $gameId        ID del juego.
     * @param float  $creditAmount  Monto a acreditar.
     * @param string $roundId       ID de la ronda.
     * @param string $transactionId ID de la transacción.
     * @param mixed  $datos         Datos adicionales de la transacción.
     *
     * @return string Respuesta en formato JSON con el resultado del crédito.
     * @throws Exception Si ocurre un error durante el crédito.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos)
    {
        $this->tipo = "CREDIT";
        $this->data = $datos;


        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try {
            //Obtenemos el Proveedor con el abreviado HABANERO
            $Proveedor = new Proveedor("", "HABANERO");

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
                $TransaccionJuego = new TransaccionJuego("", $roundId . $this->usuarioId . "HABANERO");
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            $ProductoMandante = new ProductoMandante("", "", $TransaccionJuego->productoId);
            /*  Obtenemos el producto con el $TransaccionJuego->productoId */
            $Producto = new Producto($ProductoMandante->productoId);

            //Agregamos Elementos a la Transaccion API
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());
            $this->transaccionApi->setIdentificador($TransaccionJuego->getTicketId());


            $Game = new Game();

            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, true);

            $this->transaccionApi = $responseG->transaccionApi;


            $return = array(
                "fundtransferresponse" => array(
                    "status" => array(
                        "success" => true,
                        "autherror" => false,
                        "nofunds" => false
                    ),
                    "balance" => floatval(round($responseG->saldo, 2)),
                    "currencycode" => $UsuarioMandante->getMoneda()
                )
            );

            //Guardamos la Transaccion Api necesaria de estado OK
            $this->transaccionApi->setRespuestaCodigo("OK");
            $this->transaccionApi->setRespuesta(json_encode($return));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return json_encode($return);
        } catch (Exception $e) {
            if ($_REQUEST['isDebug'] == '1') {
                print_r($e);
            }

            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para cerrar la sesión del jugador.
     *
     * @return string Respuesta en formato JSON indicando que el jugador ha cerrado sesión.
     * @throws Exception Si ocurre un error durante el cierre de sesión.
     */
    public function logout()
    {
        try {
            $Proveedor = new Proveedor("", "HABANERO");

            $return = array(
                "succes" => true,
                "Message" => "Player Logged out"
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método para realizar una verificación básica.
     *
     * @param mixed $param Parámetro de entrada para la verificación.
     *
     * @return string Respuesta en formato JSON con los detalles de la verificación.
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
     * Método para convertir errores en respuestas JSON.
     *
     * @param integer $code    Código de error.
     * @param string  $message Mensaje de error.
     *
     * @return string Respuesta en formato JSON con los detalles del error.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $response = array();

        $Proveedor = new Proveedor("", "HABANERO");

        switch ($code) {
            case 10011:


                $response = array(
                    "playerdetailresponse" => array(
                        "status" => array(
                            "success" => false,
                            "autherror" => true,
                            "message" => "Token vacio",
                        )
                    )
                );

                break;

            case 21:
                $codeProveedor = '';
                $messageProveedor = "";
                switch ($this->tipo) {
                    case "AUTH":
                        $response = array(
                            "playerdetailresponse" => array(
                                "status" => array(
                                    "success" => false,
                                    "autherror" => true,
                                    "message" => "Token Invalid",
                                )
                            )
                        );

                        break;

                    case "DEBIT":
                        $response = array(
                            "fundtransferresponse" => array(
                                "status" => array(
                                    "success" => false,
                                    "autherror" => true,
                                    "message" => "Token Invalid",
                                )
                            )
                        );

                        break;
                    case "CREDIT":
                        $response = array(
                            "fundtransferresponse" => array(
                                "status" => array(
                                    "success" => false,
                                    "autherror" => true,
                                    "message" => "Token Invalid",
                                )
                            )
                        );

                        break;
                }
                break;

            case 22:
                $codeProveedor = '';
                $messageProveedor = "";
                $response = array(
                    "playerdetailresponse" => array(
                        "status" => array(
                            "success" => false,
                            "autherror" => true
                        )
                    )
                );

                break;
            case 20000:
                $codeProveedor = "";
                $messageProveedor = "";
                $response = array(
                    "playerdetailresponse" => array(
                        "status" => array(
                            "success" => false,
                            "autherror" => true,
                            "message" => "Token Expired"
                        )
                    )
                );

                break;
            case 20001:
                $codeProveedor = "";
                $messageProveedor = "";

                $UsuarioMandante = new UsuarioMandante($this->usuarioId);
                $Game = new Game();

                $responseG = $Game->getBalance($UsuarioMandante);

                $response = array(
                    "fundtransferresponse" => array(
                        "status" => array(
                            "success" => false,
                            "nofunds" => true

                        ),
                        "balance" => floatval(round($responseG->saldo, 2)),
                        "currencycode" => $UsuarioMandante->getMoneda()
                    )
                );


                break;

            case 28:
                $codeProveedor = "";
                $messageProveedor = "";
                $response = array(
                    "fundtransferresponse" => array(
                        "status" => array(
                            "success" => false
                        ),
                    )
                );


                break;

            case 29:
                $codeProveedor = "";
                $messageProveedor = "";
                $response = array(
                    "fundtransferresponse" => array(
                        "status" => array(
                            "success" => false
                        ),
                    )
                );


                break;

            case 10001:
                $codeProveedor = "";
                $messageProveedor = "";
                switch ($this->tipo) {
                    case "DEBIT":

                        $UsuarioMandante = new UsuarioMandante($this->usuarioId);
                        $Game = new Game();

                        $responseG = $Game->getBalance($UsuarioMandante);

                        $response = array(
                            "fundtransferresponse" => array(
                                "status" => array(
                                    "success" => false,
                                    "nofunds" => true

                                ),
                                "balance" => floatval(round($responseG->saldo, 2)),
                                "currencycode" => $UsuarioMandante->getMoneda()
                            )
                        );
                        break;

                    case "CREDIT":

                        $UsuarioMandante = new UsuarioMandante($this->usuarioId);
                        $Game = new Game();

                        $responseG = $Game->getBalance($UsuarioMandante);
                        $response = array(
                            "fundtransferresponse" => array(
                                "status" => array(
                                    "success" => true,
                                    "autherror" => false,
                                    "nofunds" => false
                                ),
                                "balance" => floatval(round($responseG->saldo, 2)),
                                "currencycode" => $UsuarioMandante->getMoneda()
                            )
                        );
                        break;

                    case "ROLLBACK":

                        $UsuarioMandante = new UsuarioMandante($this->usuarioId);
                        $Game = new Game();

                        $responseG = $Game->getBalance($UsuarioMandante);

                        $response = array(
                            "fundtransferresponse" => array(
                                "status" => array(
                                    "success" => true,
                                    "refundstatus" => 1

                                ),
                                "balance" => floatval(round($responseG->saldo, 2)),
                                "currencycode" => $UsuarioMandante->getMoneda()
                            )
                        );
                        break;
                }


                break;

            case 10005:
                $codeProveedor = '"';
                $messageProveedor = "";

                switch ($this->tipo) {
                    case "DEBIT":

                        $response = array(
                            "fundtransferresponse" => array(
                                "status" => array(
                                    "success" => false
                                ),
                            )
                        );
                        break;

                    case "CREDIT":

                        $response = array(
                            "fundtransferresponse" => array(
                                "status" => array(
                                    "success" => false
                                ),
                            )
                        );
                        break;

                    case "ROLLBACK":

                        $UsuarioMandante = new UsuarioMandante($this->usuarioId);
                        $Game = new Game();

                        $responseG = $Game->getBalance($UsuarioMandante);
                        $response = array(
                            "fundtransferresponse" => array(
                                "status" => array(
                                    "success" => true,
                                    "refundstatus" => 2,
                                ),
                                "balance" => floatval(round($responseG->saldo, 2)),
                                "currencycode" => $UsuarioMandante->getMoneda()
                            )
                        );
                        break;
                }

                break;

            default:
                $response = array(
                    "fundtransferresponse" => array(
                        "status" => array(
                            "success" => false,
                            "message" => "ERROR"

                        )
                    )
                );

                break;
        }

        if ($code != 10011 && $code != 21 && $code != 22 && $code != 20000 && $code != 20001 && $code != 28 && $code != 29 && $code != 10001 && $code != 10005) {
            $respuesta = json_encode(array_merge($response, array(
                "fundtransferresponse" => array(
                    "status" => array(
                        "success" => false
                    )

                )
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
