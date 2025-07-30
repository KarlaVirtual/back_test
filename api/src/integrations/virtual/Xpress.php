<?php

/**
 * Maneja las operaciones relacionadas con la integración del proveedor XPRESS.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-22
 */

namespace Backend\integrations\virtual;

use Exception;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioPerfil;
use Backend\dto\TransaccionApi;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\dto\TransaccionJuego;
use Backend\integrations\casino\Game;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransaccionApiMySqlDAO;

/**
 * Clase Xpress
 * Maneja las operaciones relacionadas con la integración del proveedor XPRESS.
 */
class Xpress
{
    /**
     * Token de autenticación del usuario.
     *
     * @var string
     */
    private $token;

    /**
     * Objeto para manejar las transacciones de la API.
     *
     * @var string
     */
    private $transaccionApi;

    /**
     * ID de la solicitud actual.
     *
     * @var string
     */
    private $RequestId = '';

    /**
     * Clave privada utilizada para la autenticación.
     *
     * @var string
     */
    private $privateKey = '';

    /**
     * ID externo del usuario.
     *
     * @var string
     */
    private $externalId;

    /**
     * Constructor de la clase Xpress.
     *
     * @param string $token      Token de autenticación.
     * @param string $sign       Firma opcional.
     * @param string $RequestId  ID de la solicitud.
     * @param string $externalId ID externo del usuario.
     */
    public function __construct($token, $sign = '', $RequestId = '', $externalId = '')
    {
        $Proveedor = new Proveedor("", "XPRESS");

        $this->token = $token;
        $this->externalId = $externalId;
        $this->RequestId = $RequestId;

        if ($this->token != "") {
            try {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } catch (Exception $e) {
                $UsuarioMandante = new UsuarioMandante($this->externalId);
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->usumandanteId);
            }
        } else {
            $UsuarioMandante = new UsuarioMandante($this->externalId);
            $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->usumandanteId);
        }


        $Producto = new Producto($UsuarioToken->productoId);

        $SubproveedorMandantePais = new SubproveedorMandantePais(
            '',
            $Producto->subproveedorId,
            $UsuarioMandante->mandante,
            $UsuarioMandante->paisId
        );
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $this->privateKey = $Credentials->PRIVATE_KEY;
    }

    /**
     * Metodo Auth
     * Autentica al usuario y genera una respuesta con los datos del jugador.
     *
     * @return string Respuesta en formato JSON con los datos del jugador.
     */
    public function Auth()
    {
        try {
            $Proveedor = new Proveedor("", "XPRESS");

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

            $Balance = round($responseG->saldo, 2);
            $Balance = str_replace(',', '', number_format(round($Balance, 2), 2, '.', null));

            $group = 'master';
            $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);
            if ($UsuarioPerfil->getPerfilId() == 'PUNTOVENTA') {
                $group = 'puntopropio';
            } else {
                if ($UsuarioPerfil->getPerfilId() == 'CAJERO') {
                    $group = 'agentes';
                } else {
                    $group = 'master';
                }
            }

            $requestId = $this->RequestId;
            $timestamp = date(DATE_ISO8601);
            $fingerprint = md5(
                $UsuarioToken->getUsuarioId(
                ) . '' . $UsuarioMandante->moneda . 'Usuario' . '' . $UsuarioToken->getUsuarioId(
                ) . '' . $Balance . '' . $UsuarioToken->getToken(
                ) . $group . $timestamp . $requestId . '' . $this->privateKey
            );

            $return = array(
                "status" => true,
                "code" => 200,
                "message" => "Success",
                "data" => array(
                    "playerId" => $UsuarioToken->getUsuarioId(),
                    "currency" => $UsuarioMandante->moneda,
                    "playerNickname" => 'Usuario' . $UsuarioToken->getUsuarioId(),
                    "balance" => $Balance,
                    "sessionId" => $UsuarioToken->getToken(),
                    "group" => $group,
                    "requestId" => $requestId,
                    "timestamp" => $timestamp,
                    "fingerprint" => $fingerprint
                )
            );
            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Metodo getBalance
     * Obtiene el balance actual del jugador.
     *
     * @return string Respuesta en formato JSON con el balance del jugador.
     */
    public function getBalance()
    {
        try {
            $Proveedor = new Proveedor("", "XPRESS");

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


            $Balance = round($responseG->saldo, 2);
            $Balance = str_replace(',', '', number_format(round($Balance, 2), 2, '.', null));


            $group = 'master';
            $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);
            if ($UsuarioPerfil->getPerfilId() == 'PUNTOVENTA') {
                $group = 'puntopropio';
            } else {
                if ($UsuarioPerfil->getPerfilId() == 'CAJERO') {
                    $group = 'agentes';
                } else {
                    $group = 'master';
                }
            }

            $requestId = $this->RequestId;
            $timestamp = date(DATE_ISO8601);
            $fingerprint = md5(
                $UsuarioToken->getUsuarioId(
                ) . '' . $UsuarioMandante->moneda . '' . $Balance . '' . $UsuarioToken->getToken(
                ) . $group . $timestamp . $requestId . '' . $this->privateKey
            );

            $return = array(
                "status" => true,
                "code" => 200,
                "message" => "Success",
                "data" => array(
                    "playerId" => $UsuarioToken->getUsuarioId(),
                    "currency" => $UsuarioMandante->moneda,
                    "balance" => $Balance,
                    "sessionId" => $UsuarioToken->getToken(),
                    "group" => $group,
                    "timestamp" => $timestamp,
                    "requestId" => $requestId,
                    "fingerprint" => $fingerprint
                )
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Metodo logout
     * Cierra la sesión del jugador y actualiza el token.
     *
     * @return string Respuesta en formato JSON indicando el éxito de la operación.
     */
    public function logout()
    {
        try {
            $Proveedor = new Proveedor("", "XPRESS");

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

            $Balance = round($responseG->saldo, 2);
            $Balance = str_replace(',', '', number_format(round($Balance, 2), 2, '.', null));

            $UsuarioToken->setToken($UsuarioToken->createToken());
            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
            $UsuarioTokenMySqlDAO->update($UsuarioToken);
            $UsuarioTokenMySqlDAO->getTransaction()->commit();

            $group = 'master';
            $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);
            if ($UsuarioPerfil->getPerfilId() == 'PUNTOVENTA') {
                $group = 'puntopropio';
            } else {
                if ($UsuarioPerfil->getPerfilId() == 'CAJERO') {
                    $group = 'agentes';
                } else {
                    $group = 'master';
                }
            }

            $requestId = $this->RequestId;
            $timestamp = date(DATE_ISO8601);
            $fingerprint = md5(
                $UsuarioToken->getUsuarioId(
                ) . '' . $UsuarioMandante->moneda . '' . $Balance . '' . $UsuarioToken->getToken(
                ) . $group . $timestamp . $requestId . '' . $this->privateKey
            );

            $return = array(
                "status" => true,
                "code" => 200,
                "message" => "Success",
                "data" => array(
                    "playerId" => $UsuarioToken->getUsuarioId(),
                    "currency" => $UsuarioMandante->moneda,
                    "balance" => $Balance,
                    "sessionId" => $UsuarioToken->getToken(),
                    "group" => $group,
                    "timestamp" => $timestamp,
                    "requestId" => $requestId,
                    "fingerprint" => $fingerprint
                )
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Metodo Debit
     * Realiza un débito en la cuenta del jugador.
     *
     * @param string $gameId        ID del juego.
     * @param float  $debitAmount   Monto a debitar.
     * @param string $roundId       ID de la ronda.
     * @param string $transactionId ID de la transacción.
     * @param mixed  $datos         Datos adicionales.
     * @param string $infoTicket    Información del ticket (opcional).
     *
     * @return string Respuesta en formato JSON con los datos de la transacción.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $infoTicket = "")
    {
        $this->data = $datos;

        try {
            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            //Obtenemos el Proveedor con el abreviado XPRESS
            $Proveedor = new Proveedor("", "XPRESS");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue($datos);
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);

            //Obtenemos el Usuario Token con el token
            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            //Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $this->transaccionApi->setIdentificador($roundId . '_' . $UsuarioMandante->getUsumandanteId() . "XPRESS");

            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);

            $oldBalance = round($responseG->saldo, 2);
            $oldBalance = str_replace(',', '', number_format(round($oldBalance, 2), 2, '.', null));

            try {
                //Obtenemos el producto con el gameId
                $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
            } catch (Exception $e) {
                if ($e->getCode() == '26') {
                    $gameId = 'General';
                    $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
                }
            }

            if (($UsuarioToken->getUsuarioId() == 65395) && (in_array(
                        $Proveedor->getProveedorId(),
                        array('12', '68', '67')
                    ) || in_array($Producto->getProductoId(), array('5734', '5738', '5741', '5744', '5747', '5768')))) {
                throw new Exception("Juego no disponible ", "10011");
            }

            //Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, false, [], false);
            $this->transaccionApi = $responseG->transaccionApi;

            $TransjuegoLog_id = $responseG->transaccionId;
            $Balance = round($responseG->saldo, 2);
            $Balance = str_replace(',', '', number_format(round($Balance, 2), 2, '.', null));

            $group = 'master';
            $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);
            if ($UsuarioPerfil->getPerfilId() == 'PUNTOVENTA') {
                $group = 'puntopropio';
            } else {
                if ($UsuarioPerfil->getPerfilId() == 'CAJERO') {
                    $group = 'agentes';
                } else {
                    $group = 'master';
                }
            }

            $requestId = $this->RequestId;
            $timestamp = date(DATE_ISO8601);
            $fingerprint = md5(
                $UsuarioToken->getUsuarioId(
                ) . '' . $UsuarioMandante->moneda . '' . $Balance . $oldBalance . $TransjuegoLog_id . $UsuarioToken->getToken(
                ) . $group . $timestamp . $requestId . '' . $this->privateKey
            );

            $return = array(
                "status" => true,
                "code" => 200,
                "message" => "Success",
                "data" => array(
                    "playerId" => $UsuarioToken->getUsuarioId(),
                    "currency" => $UsuarioMandante->moneda,
                    "balance" => $Balance,
                    "oldBalance" => $oldBalance,
                    "transactionId" => $TransjuegoLog_id,
                    "sessionId" => $UsuarioToken->getToken(),
                    "group" => $group,
                    "timestamp" => date(DATE_ISO8601),
                    "requestId" => $requestId,
                    "fingerprint" => $fingerprint
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
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Metodo Rollback
     * Realiza un rollback de una transacción previa.
     *
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $roundId        ID de la ronda.
     * @param string $transactionId  ID de la transacción original.
     * @param string $player         ID del jugador.
     * @param mixed  $datos          Datos adicionales.
     *
     * @return string Respuesta en formato JSON con los datos de la transacción revertida.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos)
    {
        $usuarioid = $player;
        $this->data = $datos;

        try {
            //Obtenemos el Proveedor con el abreviado XPRESS
            $Proveedor = new Proveedor("", "XPRESS");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $roundId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue($datos);
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);

            $identificador = "";

            try {
                $TransaccionApi2 = new TransaccionApi("", $transactionId, $Proveedor->getProveedorId());
                $identificador = $TransaccionApi2->getIdentificador();
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            $UsuarioMandante = new UsuarioMandante($TransaccionApi2->getUsuarioId());

            $TransaccionJuego = new TransaccionJuego(
                "",
                $roundId . '_' . $UsuarioMandante->getUsumandanteId() . "XPRESS",
                $Proveedor->getProveedorId()
            );

            if ( ! $TransaccionJuego->existsTicketId()) {
                throw new Exception("Ticket ID ya existe", "10026");
            }

            $this->transaccionApi->setIdentificador($identificador);

            $oldBalance = 0;

            try {
                $Game = new Game();
                $responseG = $Game->getBalance($UsuarioMandante);

                $oldBalance = round($responseG->saldo, 2);
                $oldBalance = str_replace(',', '', number_format(round($oldBalance, 2), 2, '.', null));
            } catch (Exception $e) {
            }

            $Game = new Game();
            $responseG = $Game->rollback(
                $UsuarioMandante,
                $Proveedor,
                $this->transaccionApi,
                false,
                $transactionId,
                false
            );
            $this->transaccionApi = $responseG->transaccionApi;

            $TransjuegoLog_id = $responseG->transaccionId;
            $Balance = round($responseG->saldo, 2);
            $Balance = str_replace(',', '', number_format(round($Balance, 2), 2, '.', null));

            $UsuarioToken = new UsuarioToken('', $Proveedor->getProveedorId(), $responseG->usuarioId);

            $group = 'master';
            $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);
            if ($UsuarioPerfil->getPerfilId() == 'PUNTOVENTA') {
                $group = 'puntopropio';
            } else {
                if ($UsuarioPerfil->getPerfilId() == 'CAJERO') {
                    $group = 'agentes';
                } else {
                    $group = 'master';
                }
            }

            $requestId = $this->RequestId;
            $timestamp = date(DATE_ISO8601);
            $fingerprint = md5(
                $UsuarioToken->getUsuarioId(
                ) . '' . $UsuarioMandante->moneda . '' . $Balance . $oldBalance . $TransjuegoLog_id . $UsuarioToken->getToken(
                ) . $group . $timestamp . $requestId . '' . $this->privateKey
            );

            $return = array(
                "status" => true,
                "code" => 200,
                "message" => "Success",
                "data" => array(
                    "playerId" => $UsuarioToken->getUsuarioId(),
                    "currency" => $UsuarioMandante->moneda,
                    "balance" => $Balance,
                    "oldBalance" => $oldBalance,
                    "transactionId" => $TransjuegoLog_id,
                    "sessionId" => $UsuarioToken->getToken(),
                    "group" => $group,
                    "timestamp" => date(DATE_ISO8601),
                    "requestId" => $requestId,
                    "fingerprint" => $fingerprint
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
            if ($e->getCode() == "10001") {
                return $this->convertError("10027", "10027");
            }

            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Metodo Credit
     * Realiza un crédito en la cuenta del jugador.
     *
     * @param string  $gameId        ID del juego.
     * @param float   $creditAmount  Monto a acreditar.
     * @param string  $roundId       ID de la ronda.
     * @param string  $transactionId ID de la transacción.
     * @param mixed   $datos         Datos adicionales.
     * @param boolean $isEndRound    Indica si es el final de la ronda (opcional).
     * @param string  $ticketPv      Ticket del punto de venta (opcional).
     *
     * @return string Respuesta en formato JSON con los datos de la transacción.
     */
    public function Credit(
        $gameId,
        $creditAmount,
        $roundId,
        $transactionId,
        $datos,
        $isEndRound = false,
        $ticketPv = ''
    ) {
        $this->data = $datos;

        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try {
            //Obtenemos el Proveedor con el abreviado XPRESS
            $Proveedor = new Proveedor("", "XPRESS");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue($datos);
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);

            //Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($this->externalId);

            $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->getUsumandanteId());

            $this->transaccionApi->setIdentificador($roundId . '_' . $UsuarioMandante->getUsumandanteId() . "XPRESS");

            $oldBalance = 0;

            try {
                $Game = new Game();
                $responseG = $Game->getBalance($UsuarioMandante);

                $oldBalance = round($responseG->saldo, 2);
                $oldBalance = str_replace(',', '', number_format(round($oldBalance, 2), 2, '.', null));
            } catch (Exception $e) {
            }

            try {
                //Obtenemos el producto con el gameId
                $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
            } catch (Exception $e) {
                if ($e->getCode() == '26') {
                    $gameId = 'General';
                    $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
                }
            }
            //Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            //Agregamos Elementos a la Transaccion API
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());

            $Game = new Game();
            $responseG = $Game->credit(
                $UsuarioMandante,
                $Producto,
                $this->transaccionApi,
                $isEndRound,
                false,
                false,
                false
            );
            $this->transaccionApi = $responseG->transaccionApi;

            $TransjuegoLog_id = $responseG->transaccionId;
            $Balance = round($responseG->saldo, 2);
            $Balance = str_replace(',', '', number_format(round($Balance, 2), 2, '.', null));

            $group = 'master';
            $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);
            if ($UsuarioPerfil->getPerfilId() == 'PUNTOVENTA') {
                $group = 'puntopropio';
            } else {
                if ($UsuarioPerfil->getPerfilId() == 'CAJERO') {
                    $group = 'agentes';
                } else {
                    $group = 'master';
                }
            }

            $requestId = $this->RequestId;
            $timestamp = date(DATE_ISO8601);
            $fingerprint = md5(
                $UsuarioToken->getUsuarioId(
                ) . '' . $UsuarioMandante->moneda . '' . $Balance . $oldBalance . $TransjuegoLog_id . $UsuarioToken->getToken(
                ) . $group . $timestamp . $requestId . '' . $this->privateKey
            );

            $return = array(
                "status" => true,
                "code" => 200,
                "message" => "Success",
                "data" => array(
                    "playerId" => $UsuarioToken->getUsuarioId(),
                    "currency" => $UsuarioMandante->moneda,
                    "balance" => $Balance,
                    "oldBalance" => $oldBalance,
                    "transactionId" => $TransjuegoLog_id,
                    "sessionId" => $UsuarioToken->getToken(),
                    "group" => $group,
                    "timestamp" => date(DATE_ISO8601),
                    "requestId" => $requestId,
                    "fingerprint" => $fingerprint
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
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Metodo convertError
     * Convierte un código de error interno en un mensaje de error estándar.
     *
     * @param integer $code    Código de error interno.
     * @param string  $message Mensaje de error interno.
     *
     * @return string Respuesta en formato JSON con el error convertido.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $response = array();
        if ($this->transaccionApi != null) {
            $tipo = $this->transaccionApi->getTipo();
        }

        if ($tipo == "DEBIT" || $tipo == "CREDIT" || $tipo == "ROLLBACK") {
            $response = array_merge($response, array());
        } else {
            $response = array_merge($response, array());
        }

        switch ($code) {
            case 10011:
                $codeProveedor = 106;
                $messageInterno = $code;
                $messageProveedor = "Invalid secure token.";
                break;

            case 21:
                $codeProveedor = 106;
                $messageInterno = $code;
                $messageProveedor = "Invalid secure token.";
                break;

            case 22:
                $codeProveedor = 106;
                $messageInterno = $code;
                $messageProveedor = "Invalid secure token.";
                break;

            case 20001:
                $codeProveedor = 107;
                $messageInterno = $code;
                $messageProveedor = "Insufficient funds";
                break;

            case 0:
                $codeProveedor = 104;
                $messageInterno = $code;
                $messageProveedor = "Unknown request.";
                break;

            case 27:
                $codeProveedor = 104;
                $messageInterno = $code;
                $messageProveedor = "Unknown request.";
                break;

            case 28:
                $codeProveedor = 112;
                $messageInterno = $code;
                $messageProveedor = "Game cycle does not exist.";
                break;

            case 29:
                $codeProveedor = 116;
                $messageInterno = $code;
                $messageProveedor = "Transaction already exists.";
                break;

            case 10001:
                $codeProveedor = 116;
                $messageInterno = $code;
                $messageProveedor = "Transaction already exists.";
                break;

            case 10004:
                $codeProveedor = 105;
                $messageInterno = $code;
                $messageProveedor = "Request processing services unavailable.";
                break;

            case 10014:
                $codeProveedor = 104;
                $messageInterno = $code;
                $messageProveedor = "Unknown request.";
                break;

            case 10025:
                $codeProveedor = 115;
                $messageInterno = $code;
                $messageProveedor = "Game cycle exists.";
                break;

            case 10026:
                $codeProveedor = 112;
                $messageInterno = $code;
                $messageProveedor = "Game cycle does not exist.";
                break;

            case 10005:
                $codeProveedor = 117;
                $messageInterno = $code;
                $messageProveedor = "Transaction does not exist.";
                break;

            case 10027:
                $codeProveedor = 118;
                $messageInterno = $code;
                $messageProveedor = "Game cycle already closed.";
                break;

            case 26:
                $codeProveedor = 111;
                $messageInterno = $code;
                $messageProveedor = "Unsupported gameid.";
                break;

            default:
                $codeProveedor = 104;
                $messageInterno = $code;
                $messageProveedor = "Unknown request.";
                break;
        }

        $respuesta = (array_merge($response, array(
            "status" => false,
            "code" => $codeProveedor,
            "message" => $messageProveedor,
            "message2" => $messageInterno
        )));

        if ($this->transaccionApi != null) {
            $this->transaccionApi->setTipo("R" . $this->transaccionApi->getTipo());
            $this->transaccionApi->setRespuestaCodigo("ERROR_" . $code);
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->insert($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();
        }

        return json_encode($respuesta);
    }
}
