<?php

/**
 * Clase Platipus para la integración con el proveedor de juegos Platipus.
 *
 * Este archivo contiene la implementación de métodos para manejar transacciones
 * relacionadas con juegos, como obtener balances, realizar débitos, créditos,
 * y manejar errores. También incluye validaciones de tokens y firmas.
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
 * Clase Platipus.
 *
 * Esta clase maneja la integración con el proveedor de juegos Platipus,
 * proporcionando métodos para realizar transacciones como débitos, créditos,
 * obtención de balances, y manejo de errores.
 */
class Platipus
{
    /**
     * Token de autenticación.
     *
     * @var string
     */
    private $token;

    /**
     * Identificador externo del usuario.
     *
     * @var string
     */
    private $externalId;

    /**
     * Firma para validación de seguridad.
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
     * Indica si hubo un error en la validación del hash.
     *
     * @var boolean
     */
    private $errorHash = false;

    /**
     * Constructor de la clase Platipus.
     *
     * @param string $token        Token de autenticación.
     * @param string $sign         Firma de seguridad.
     * @param string $external     Identificador externo del usuario (opcional).
     * @param string $hashOriginal Hash original para validación (opcional).
     */
    public function __construct($token, $sign, $external = "", $hashOriginal = "")
    {
        if (! defined('JSON_PRESERVE_ZERO_FRACTION')) {
            define('JSON_PRESERVE_ZERO_FRACTION', 1024);
        }

        $this->token = $token;
        $this->sign = $sign;
        $this->externalId = $external;
        if ($this->sign != $hashOriginal) {
            $this->errorHash = true;
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }
    }

    /**
     * Obtiene el nombre de usuario asociado a un proveedor y un ID de usuario.
     *
     * @param string $providerid ID del proveedor.
     * @param string $userid     ID del usuario.
     * @param string $md5        Hash MD5 para validación.
     *
     * @return string JSON con el nombre de usuario y su ID.
     */
    public function getUserName($providerid, $userid, $md5)
    {
        $this->externalId = $userid; //Revisar

        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }

        try {
            if ($userid == "") {
                throw new Exception("Token vacio", "10011");
            }

            $Proveedor = new Proveedor("", "PLATIPUS");

            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante); //Revisar la funcion getBalance dentro de Game

            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

            $return = array(
                "userid" => $responseG->usuarioId,
                "username" => $responseG->usuario,
            );
            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del usuario asociado a un proveedor y un ID de usuario.
     *
     * @param string $providerid ID del proveedor.
     * @param string $userid     ID del usuario.
     * @param string $md5        Hash MD5 para validación.
     *
     * @return string JSON con el balance, ID de usuario y moneda.
     */
    public function getBalance($providerid, $userid, $md5)
    {
        $this->externalId = $userid; //Revisar

        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }

        $this->method = 'balance';
        try {
            if ($userid == "") {
                throw new Exception("Token vacio", "10011");
            }

            $Proveedor = new Proveedor("", "PLATIPUS");

            $UsuarioMandante = new UsuarioMandante($this->externalId);

            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante); //Revisar la funcion getBalance dentro de Game

            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

            $return = array(
                "balance" => $saldo,
                "userid" => $responseG->usuarioId,
                "currency" => $responseG->moneda
            );
            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del usuario asociado a un proveedor y un ID de usuario.
     *
     * Este método realiza la validación del token y el hash, y luego consulta el balance
     * del usuario a través de la clase Game. Devuelve el balance, el ID del usuario y la moneda.
     *
     * @param string $providerid ID del proveedor.
     * @param string $userid     ID del usuario.
     * @param string $md5        Hash MD5 para validación.
     *
     * @return string JSON con el balance, ID de usuario y moneda.
     * @throws Exception Si el token o el ID del usuario son inválidos.
     */
    public function getBalance2($providerid, $userid, $md5) //Revisar parametros
    {
        $this->externalId = $userid; //Revisar

        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }

        $this->method = 'balance';
        try {
            if ($userid == "") {
                throw new Exception("Token vacio", "10011");
            }

            $Proveedor = new Proveedor("", "PLATIPUS");

            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante); //Revisar la funcion getBalance dentro de Game

            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

            $return = array(
                "balance" => $saldo,
                "userid" => $responseG->usuarioId,
                "currency" => $responseG->moneda
            );
            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un débito en el balance del usuario.
     *
     * Este método procesa una transacción de débito para un usuario en un juego específico.
     * Valida el token y el identificador externo del usuario, crea una transacción API,
     * y actualiza el balance del usuario en el sistema.
     *
     * @param string $gameId        Identificador del juego.
     * @param string $gameName      Nombre del juego.
     * @param float  $debitAmount   Monto a debitar.
     * @param string $roundId       Identificador de la ronda.
     * @param string $transactionId Identificador de la transacción.
     * @param array  $datos         Datos adicionales para la transacción.
     *
     * @return string JSON con el balance actualizado y el código de éxito.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function Debit($gameId, $gameName, $debitAmount, $roundId, $transactionId, $datos)
    {

        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }

        $this->method = 'reserve';

        $this->data = $datos;

        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado PLATIPUS */
            $Proveedor = new Proveedor("", "PLATIPUS");

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
            $this->transaccionApi->setIdentificador("PLATIPUS" . $roundId);

            $Game = new Game();

            $isfreeSpin = false;
            if (floatval($debitAmount) == 0) {
                $isfreeSpin = true;
            }

            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $isfreeSpin);

            $this->transaccionApi = $responseG->transaccionApi;

            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

            $respuesta = json_encode(array(
                "balance" => $saldo,
                "successCode" => "0"
            ));

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $respuesta;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un rollback de una transacción de débito.
     *
     * Este método revierte una transacción previamente realizada, validando que
     * la transacción exista y no haya sido procesada antes. Si la transacción es válida,
     * se actualiza el estado de la transacción en la base de datos, se registra un log
     * y se acredita el monto correspondiente al usuario.
     *
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $roundId        Identificador de la ronda.
     * @param string $transactionId  Identificador de la transacción original.
     * @param string $player         Identificador del jugador.
     * @param array  $datos          Datos adicionales para la transacción.
     *
     * @return string JSON con el balance actualizado y el código de respuesta.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function Rollback2($rollbackAmount, $roundId, $transactionId, $player, $datos)
    {
        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }

        $usuarioid = explode("Usuario", $player)[1];

        $this->data = $datos;

        try {
            /*  Obtenemos el Proveedor con el abreviado PLATIPUS */
            $Proveedor = new Proveedor("", "PLATIPUS");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);

            /*  Verificamos que la transaccionId no se haya procesado antes  */
            if ($this->transaccionApi->existsTransaccionIdAndProveedor("OK")) {
                /*  Si la transaccionId ha sido procesada, reportamos el error  */
                throw new Exception("Transaccion ya procesada", "10001");
            }

            $transaccionNoExiste = false;

            try {
                $TransaccionApi2 = new TransaccionApi("", $transactionId, $Proveedor->getProveedorId());
                $jsonValue = json_decode($TransaccionApi2->getTValue());
                $valorTransaction = 0;

                /*  Agregamos Elementos a la Transaccion API  */
                $this->transaccionApi->setProductoId($TransaccionApi2->getProductoId());
                $this->transaccionApi->setUsuarioId($TransaccionApi2->getUsuarioId());

                if (strpos($TransaccionApi2->getTipo(), 'DEBIT') !== false) {
                } else {
                    throw new Exception("Transaccion no es Debit", "10006");
                }
                $UsuarioMandante = new UsuarioMandante($TransaccionApi2->getUsuarioId());

                $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "PLATIPUS");
            } catch (Exception $e) {
                $transaccionNoExiste = true;
                throw new Exception("Transaccion no existe", "10005");
            }

            if (! $transaccionNoExiste) {
                /*  Creamos la Transaccion por el Juego  */
                $TransaccionJuego = new TransaccionJuego("", $TransaccionApi2->getIdentificador());
                $valorTransaction = $TransaccionApi2->getValor();

                /*  Obtenemos Mandante para verificar sus caracteristicas  */
                $Mandante = new Mandante($UsuarioMandante->mandante);

                /*  Verificamos si el mandante es Propio  */
                if ($Mandante->propio == "S") {
                    /* Obtenemos la Transaccion de la BD para crear y registrar el Debito con esta transaccion */
                    $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
                    $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

                    /*  Verificamos que la Transaccion si este conectada y lista para usarse  */
                    if ($Transaction->isIsconnected()) {
                        /*  Actualizamos Transaccion Juego  */
                        $TransaccionJuego->setEstado("I");
                        $TransaccionJuego->setValorTicket($TransaccionJuego->getValorTicket() - $valorTransaction);
                        $TransaccionJuego->update($Transaction);

                        /*  Obtenemos el Transaccion Juego ID  */
                        $TransJuegoId = $TransaccionJuego->getTransjuegoId();

                        /*  Creamos el Log de Transaccion Juego  */
                        $TransjuegoLog = new TransjuegoLog();
                        $TransjuegoLog->setTransjuegoId($TransJuegoId);
                        $TransjuegoLog->setTransaccionId("ROLLBACK" . $transactionId);
                        $TransjuegoLog->setTipo("ROLLBACK");
                        $TransjuegoLog->setTValue(json_encode($datos));
                        $TransjuegoLog->setUsucreaId(0);
                        $TransjuegoLog->setUsumodifId(0);
                        $TransjuegoLog->setValor($valorTransaction);

                        $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);

                        /*  Obtenemos el Usuario para hacerle el credito  */
                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                        $Usuario->credit($valorTransaction, $Transaction);


                        $Transaction->commit();

                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                        $Balance = (int)($Usuario->getBalance() * 100);

                        $return = array(
                            "balance" => $Balance,
                            "responseCode" => "OK"
                        );
                        /*  Guardamos la Transaccion Api necesaria de estado OK   */
                        $this->transaccionApi->setRespuestaCodigo("OK");
                        $this->transaccionApi->setRespuesta(json_encode($return));
                        $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                        $TransaccionApiMySqlDAO->update($this->transaccionApi);
                        $TransaccionApiMySqlDAO->getTransaction()->commit();

                        return json_encode($return);
                    }
                }
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un rollback de una transacción de débito.
     *
     * Este método revierte una transacción previamente realizada, validando que
     * la transacción exista y no haya sido procesada antes. Si la transacción es válida,
     * se actualiza el estado de la transacción en la base de datos, se registra un log
     * y se acredita el monto correspondiente al usuario.
     *
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $roundId        Identificador de la ronda.
     * @param string $transactionId  Identificador de la transacción original.
     * @param string $player         Identificador del jugador.
     * @param array  $datos          Datos adicionales para la transacción.
     *
     * @return string JSON con el balance actualizado y el código de respuesta.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos)
    {
        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }

        $this->method = 'cancelReserve';

        $this->data = $datos;

        try {
            /*  Obtenemos el Proveedor con el abreviado PLATIPUS */
            $Proveedor = new Proveedor("", "PLATIPUS");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($rollbackAmount);

            try {
                $SubProveedor = new Subproveedor("", "PLATIPUS");
                $TransjuegoLog = new TransjuegoLog("", "", "", $transactionId . '_' . $SubProveedor->getSubproveedorId(), $SubProveedor->getSubproveedorId());
                $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                $this->transaccionApi->setIdentificador($TransaccionJuego->getTicketId());
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            $Game = new Game();
            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi);

            $this->transaccionApi = $responseG->transaccionApi;
            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

            $respuesta = json_encode(array(
                "balance" => $saldo,
                "successCode" => "0"
            ));

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $respuesta;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un rollback de una ronda completa.
     *
     * Este método revierte todas las transacciones asociadas a una ronda específica.
     * Valida el token y el identificador externo del usuario, crea una transacción API,
     * y actualiza el balance del usuario en el sistema.
     *
     * @param float  $rollbackAmount Monto total a revertir.
     * @param string $roundId        Identificador de la ronda.
     * @param string $transactionId  Identificador de la transacción original.
     * @param string $player         Identificador del jugador.
     * @param array  $datos          Datos adicionales para la transacción.
     *
     * @return string JSON con el balance actualizado, detalles de la transacción y el código de éxito.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function RollbackRound($rollbackAmount, $roundId, $transactionId, $player, $datos)
    {
        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }

        $this->method = 'cancelReserve';
        $this->data = $datos;

        try {
            /*  Obtenemos el Proveedor con el abreviado PLATIPUS */
            $Proveedor = new Proveedor("", "PLATIPUS");

            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($rollbackAmount);

            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($this->externalId);
            }

            $this->transaccionApi->setIdentificador("PLATIPUS" . $roundId);

            $Game = new Game();
            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi);

            $this->transaccionApi = $responseG->transaccionApi;
            $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

            $respuesta = json_encode(array(
                "transactionId" => $responseG->transaccionId,
                "cash" => $saldo,
                "currency" => $responseG->moneda,
                "bonus" => 0,
                "usedPromo" => 0,
                "error" => 0,
                "description" => "Success"
            ));

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $respuesta;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un crédito en el balance del usuario.
     *
     * Este método procesa una transacción de crédito para un usuario en un juego específico.
     * Valida el token y el identificador externo del usuario, crea una transacción API,
     * y actualiza el balance del usuario en el sistema.
     *
     * @param string  $gameId        Identificador del juego.
     * @param string  $gameName      Nombre del juego.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       Identificador de la ronda.
     * @param string  $transactionId Identificador de la transacción.
     * @param array   $datos         Datos adicionales para la transacción.
     * @param boolean $isBonus       Indica si el crédito es un bono (opcional).
     * @param boolean $finished      Indica si la transacción está finalizada (opcional).
     *
     * @return string JSON con el balance actualizado y el código de éxito.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function Credit($gameId, $gameName, $creditAmount, $roundId, $transactionId, $datos, $isBonus = false, $finished = false)
    {
        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }

        $this->method = 'release';
        $this->data = $datos;

        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado PLATIPUS */
            $Proveedor = new Proveedor("", "PLATIPUS");

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
            $this->transaccionApi->setIdentificador("PLATIPUS" . $roundId);

            try {
                $TransaccionJuego = new TransaccionJuego("", "PLATIPUS" . $roundId);
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

            $respuesta = json_encode(array(
                "successCode" => "0",
                "balance" => $saldo
            ));

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $respuesta;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Verifica el estado del sistema y devuelve información básica.
     *
     * Este método realiza una verificación simple del sistema, devolviendo un nodo
     * de identificación, el parámetro recibido y la firma actual.
     *
     * @param mixed $param Parámetro de entrada para la verificación.
     *
     * @return string JSON con el nodo de identificación, el parámetro recibido y la firma.
     * @throws Exception Si el hash de validación es incorrecto.
     */
    public function Check($param)
    {
        if ($this->errorHash) {
            try {
                throw new Exception("Token vacio", "20002");
            } catch (Exception $e) {
                return $this->convertError($e->getCode(), $e->getMessage());
            }
        }

        $return = array(
            "nodeId" => 123,
            "param" => $param,
            "sign" => $this->sign
        );

        return json_encode($return);
    }

    /**
     * Converts an error code and message into a standardized JSON response.
     *
     * This method maps internal error codes to provider-specific error codes
     * and messages. It also updates the transaction API object with the error
     * details and commits the changes to the database.
     *
     * @param integer $code    Internal error code.
     * @param string  $message Error message.
     *
     * @return string JSON response with the mapped error code and message.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $response = array();

        $Proveedor = new Proveedor("", "PLATIPUS");

        switch ($code) {
            case 10011:
                $codeProveedor = "-101"; //Ok
                $messageProveedor = "user not found";
                break;

            case 21:
                $codeProveedor = "-112"; //Ok
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
                $codeProveedor = "-111";
                $messageProveedor = "Success";

                if ($this->token != "") {
                    try {
                        /*  Obtenemos el Usuario Token con el token */
                        $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                        /*  Obtenemos el Usuario Mandante con el Usuario Token */
                        $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                    } catch (Exception $e) {
                        /*  Obtenemos el Usuario Mandante con el Usuario Token */
                        $UsuarioMandante = new UsuarioMandante($this->externalId);
                    }
                } else {
                    /*  Obtenemos el Usuario Mandante con el Usuario Token */
                    $UsuarioMandante = new UsuarioMandante($this->externalId);
                }

                $Game = new Game();
                $responseG = $Game->getBalance($UsuarioMandante);

                $tipo = $this->transaccionApi->getTipo();
                $TransaccionApi = new TransaccionApi("", $this->transaccionApi->getTransaccionId(), $Proveedor->getProveedorId());

                $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));

                /*  Retornamos el mensaje satisfactorio  */
                $response = array(
                    "errorCode" => $codeProveedor
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
            $this->transaccionApi->setTransaccionId($this->transaccionApi->getTipo());
            $this->transaccionApi->setRespuestaCodigo("ERROR_" . $code);
            $this->transaccionApi->setRespuesta($respuesta);

            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->insert($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();
        }


        return $respuesta;
    }
}
