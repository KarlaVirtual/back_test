<?php

/**
 * Clase IGP para manejar integraciones con el sistema de casino.
 *
 * Este archivo contiene la implementación de la clase IGP, que incluye métodos
 * para autenticar usuarios, realizar débitos, créditos y rollbacks en el sistema
 * de casino, así como manejar errores y registrar transacciones.
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
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioToken;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Exception;

/**
 * Clase IGP.
 *
 * Esta clase maneja las integraciones con el sistema de casino, proporcionando
 * métodos para autenticar usuarios, realizar débitos, créditos y rollbacks,
 * así como manejar errores y registrar transacciones.
 */
class IGP
{
    /**
     * ID del usuario autenticado.
     *
     * @var string
     */
    private $usuarioId;

    /**
     * Constructor de la clase IGP.
     *
     * @param string $usuarioId ID del usuario.
     */
    public function __construct($usuarioId)
    {
        $this->usuarioId = $usuarioId;
    }

    /**
     * Autentica al usuario y devuelve su balance si es válido.
     *
     * @return string JSON con el estado, balance y mensaje.
     * @throws Exception Si el usuario no es válido o no tiene permisos.
     */
    public function Auth()
    {
        try {
            if ($this->usuarioId == "") {
                throw new Exception("UsuarioId vacio", "01");
            }


            $UsuarioMandante = new UsuarioMandante($this->usuarioId);

            $Mandante = new Mandante($UsuarioMandante->mandante);

            if ($Mandante->propio == "S") {
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                $Balance = $Usuario->getBalance();

                $return = array(

                    "status" => 200,
                    "balance" => $Balance,
                    "msg" => ""

                );
                return json_encode($return);
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un débito en el sistema de casino.
     *
     * @param string  $gameId        ID del juego.
     * @param string  $remoteId      ID remoto.
     * @param float   $amount        Monto a debitar.
     * @param string  $transactionId ID de la transacción.
     * @param boolean $gameplayFinal Indica si es la jugada final.
     * @param string  $roundId       ID de la ronda.
     * @param string  $remoteData    Datos remotos adicionales.
     * @param string  $sessionId     ID de la sesión.
     * @param string  $key           Clave de seguridad.
     * @param string  $gamesessionId ID de la sesión de juego.
     * @param string  $gameIdHash    Hash del ID del juego.
     *
     * @return string JSON con el estado, balance y mensaje.
     * @throws Exception Si ocurre un error durante el débito.
     */
    public function Debit($gameId, $remoteId, $amount, $transactionId, $gameplayFinal, $roundId, $remoteData, $sessionId, $key, $gamesessionId, $gameIdHash)
    {
        $datos = array(
            "usuarioId" => $this->usuarioId,
            "gameId" => $gameId,
            "remoteId" => $remoteId,
            "amount" => $amount,
            "transactionId" => $transactionId,
            "gameplayFinal" => $gameplayFinal,
            "roundId" => $roundId,
            "remoteData" => $remoteData,
            "sessionId" => $sessionId,
            "key" => $key,
            "gamesessionId" => $gamesessionId,
            "gameIdHash" => $gameIdHash
        );

        //  syslog(LOG_WARNING, "LLEGO:" . json_encode($datos));


        try {
            $Proveedor = new Proveedor("", "IGP");


            $Producto = new Producto("", "", "");

            $UsuarioMandante = new UsuarioMandante($this->usuarioId);

            $TransaccionJuego = new TransaccionJuego();
            $TransaccionJuego->setProductoId($gameId);
            $TransaccionJuego->setTransaccionId($transactionId);
            $TransaccionJuego->setTicketId($roundId . "IGP");
            $TransaccionJuego->setValorTicket($amount);
            $TransaccionJuego->setValorPremio(0);
            $TransaccionJuego->setMandante($UsuarioMandante->mandante);
            $TransaccionJuego->setUsuarioId($UsuarioMandante->usumandanteId);
            $TransaccionJuego->setEstado("A");
            $TransaccionJuego->setUsucreaId(0);
            $TransaccionJuego->setUsumodifId(0);

            if ($TransaccionJuego->existsTransaccionId()) {
                throw new Exception("TransaccionId ya fue procesada", "02");
            }

            $ExisteTicket = false;

            if ($TransaccionJuego->existsTicketId()) {
                $ExisteTicket = true;
            }

            $Mandante = new Mandante($UsuarioMandante->mandante);

            if ($Mandante->propio == "S") {
                $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();

                $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

                if ($Transaction->isIsconnected()) {
                    if ($ExisteTicket) {
                        $TransaccionJuego = new TransaccionJuego("", $roundId . "IGP" . $gamesessionId, "");

                        if ($TransaccionJuego->getTransaccionId() != $transactionId) {
                            $TransaccionJuego->setValorTicket($TransaccionJuego->getValorTicket() + $amount);
                            $TransaccionJuego->update($Transaction);
                        }
                        $transaccion_id = $TransaccionJuego->getTransjuegoId();
                    } else {
                        $transaccion_id = $TransaccionJuego->insert($Transaction);
                    }

                    $tipoTransaccion = "DEBIT";


                    $TransjuegoLog = new TransjuegoLog();
                    $TransjuegoLog->setTransjuegoId($transaccion_id);
                    $TransjuegoLog->setTransaccionId($transactionId);
                    $TransjuegoLog->setTipo($tipoTransaccion);
                    $TransjuegoLog->setTValue(json_encode($datos));
                    $TransjuegoLog->setUsucreaId(0);
                    $TransjuegoLog->setUsumodifId(0);

                    $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);

                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);


                    $Usuario->debit($amount, $Transaction);

                    // Commit de la transacción
                    $Transaction->commit();

                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $Balance = $Usuario->getBalance();


                    require_once("../../../websocket/chat2.php");

                    $data = $UsuarioMandante->getWSMessage();

                    $UsuarioToken = new UsuarioToken("", $UsuarioMandante->getUsumandanteId());

                    sendWSMessage($UsuarioToken->getRequestId(), $data);


                    $return = array(

                        "status" => "200",
                        "Balance" => $Balance,
                        "msg" => ""

                    );

                    return json_encode($return);
                }
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un crédito en el sistema de casino.
     *
     * @param string  $gameId        ID del juego.
     * @param string  $remoteId      ID remoto.
     * @param float   $amount        Monto a acreditar.
     * @param string  $transactionId ID de la transacción.
     * @param boolean $gameplayFinal Indica si es la jugada final.
     * @param string  $roundId       ID de la ronda.
     * @param string  $remoteData    Datos remotos adicionales.
     * @param string  $sessionId     ID de la sesión.
     * @param string  $key           Clave de seguridad.
     * @param string  $gamesessionId ID de la sesión de juego.
     * @param string  $gameIdHash    Hash del ID del juego.
     *
     * @return string JSON con el estado y balance.
     * @throws Exception Si ocurre un error durante el crédito.
     */
    public function Credit($gameId, $remoteId, $amount, $transactionId, $gameplayFinal, $roundId, $remoteData, $sessionId, $key, $gamesessionId, $gameIdHash)
    {
        $datos = array(
            "usuarioId" => $this->usuarioId,
            "gameId" => $gameId,
            "remoteId" => $remoteId,
            "amount" => $amount,
            "transactionId" => $transactionId,
            "gameplayFinal" => $gameplayFinal,
            "roundId" => $roundId,
            "remoteData" => $remoteData,
            "sessionId" => $sessionId,
            "key" => $key,
            "gamesessionId" => $gamesessionId,
            "gameIdHash" => $gameIdHash
        );

        try {
            $UsuarioMandante = new UsuarioMandante($this->usuarioId);

            $TransaccionJuego = new TransaccionJuego("", $roundId . "IGP" . $gamesessionId);

            $Mandante = new Mandante($UsuarioMandante->mandante);

            if ($Mandante->propio == "S") {
                $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();

                $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

                if ($Transaction->isIsconnected()) {
                    $TransaccionJuego->setValorPremio($TransaccionJuego->getValorPremio() + $amount);

                    if ($gameplayFinal) {
                        if ($TransaccionJuego->getValorPremio() > 0) {
                            $TransaccionJuego->setPremiado("S");
                            $TransaccionJuego->setFechaPago(time());
                        }
                        $TransaccionJuego->setEstado("I");
                    }

                    $TransaccionJuego->update($Transaction);

                    $TransJuegoId = $TransaccionJuego->getTransjuegoId();

                    $tipoTransaccion = "CREDIT";

                    $sumaCreditos = true;

                    $TransjuegoLog = new TransjuegoLog();
                    $TransjuegoLog->setTransjuegoId($TransJuegoId);
                    $TransjuegoLog->setTransaccionId($transactionId);
                    $TransjuegoLog->setTipo($tipoTransaccion);
                    $TransjuegoLog->setTValue(json_encode($datos));
                    $TransjuegoLog->setUsucreaId(0);
                    $TransjuegoLog->setUsumodifId(0);

                    $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);


                    if ($sumaCreditos) {
                        if ($amount > 0) {
                            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                            $Usuario->credit($amount, $Transaction);
                        }
                    }

                    // Commit de la transacción
                    $Transaction->commit();

                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $Balance = $Usuario->getBalance();

                    $return = array(

                        "status" => "200",
                        "balance" => $Balance

                    );

                    return json_encode($return);
                }
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un rollback de una transacción en el sistema de casino.
     *
     * @param string  $gameId        ID del juego.
     * @param string  $remoteId      ID remoto.
     * @param float   $amount        Monto a revertir.
     * @param string  $transactionId ID de la transacción.
     * @param boolean $gameplayFinal Indica si es la jugada final.
     * @param string  $roundId       ID de la ronda.
     * @param string  $remoteData    Datos remotos adicionales.
     * @param string  $sessionId     ID de la sesión.
     * @param string  $key           Clave de seguridad.
     * @param string  $gamesessionId ID de la sesión de juego.
     * @param string  $gameIdHash    Hash del ID del juego.
     *
     * @return string JSON con el estado y balance.
     * @throws Exception Si ocurre un error durante el rollback.
     */
    public function Rollback($gameId, $remoteId, $amount, $transactionId, $gameplayFinal, $roundId, $remoteData, $sessionId, $key, $gamesessionId, $gameIdHash)
    {
        $datos = array(
            "token" => $this->token,
            "gameId" => $gameId,
            "remoteId" => $remoteId,
            "amount" => $amount,
            "transactionId" => $transactionId,
            "gameplayFinal" => $gameplayFinal,
            "roundId" => $roundId,
            "remoteData" => $remoteData,
            "sessionId" => $sessionId,
            "key" => $key,
            "gamesessionId" => $gamesessionId,
            "gameIdHash" => $gameIdHash
        );

        try {
            $UsuarioToken = new UsuarioToken($this->token);

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $TransaccionJuego = new TransaccionJuego("", $roundId . "IGP" . $gamesessionId);

            if ($TransaccionJuego->getValorTicket() != $amount) {
                throw new Exception("General Error", "04");
            }

            $Mandante = new Mandante($UsuarioMandante->mandante);

            if ($Mandante->propio == "S") {
                $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();

                $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

                if ($Transaction->isIsconnected()) {
                    $TransaccionJuego->setEstado("I");

                    $TransaccionJuego->update($Transaction);

                    $TransJuegoId = $TransaccionJuego->getTransjuegoId();

                    $TransjuegoLog = new TransjuegoLog();
                    $TransjuegoLog->setTransjuegoId($TransJuegoId);
                    $TransjuegoLog->setTransaccionId("ROLLBACK" . $transactionId);
                    $TransjuegoLog->setTipo("ROLLBACK");
                    $TransjuegoLog->setTValue(json_encode($datos));
                    $TransjuegoLog->setUsucreaId(0);
                    $TransjuegoLog->setUsumodifId(0);

                    $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);

                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $Usuario->credit($TransaccionJuego->getValorTicket(), $Transaction);

                    // Commit de la transacción
                    $Transaction->commit();

                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $Balance = $Usuario->getBalance();

                    $return = array(

                        "status" => "200",
                        "balance" => $Balance

                    );

                    return json_encode($return);
                }
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Convierte un error en un formato JSON legible.
     *
     * @param integer $code    Código del error.
     * @param string  $message Mensaje del error.
     *
     * @return string JSON con el estado y mensaje del error.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        switch ($code) {
            case 1:
                $codeProveedor = 6;
                $messageProveedor = "Token not found";
                break;
            case 2:
                $codeProveedor = 2;
                $messageProveedor = "Transaccion ya esta procesada";
                break;
            case 3:
                $codeProveedor = 403;
                $messageProveedor = "Insufficient funds";
                break;
            case 3:
                $codeProveedor = 1;
                $messageProveedor = "General Error";
                break;
        }


        return json_encode(array(
            "status" => $codeProveedor,
            "msg" => $messageProveedor
        ));
    }

}