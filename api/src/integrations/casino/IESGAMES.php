<?php

/**
 * Clase IESGAMES para la integración con un proveedor de juegos.
 *
 * @category Integración
 * @package  Backend\integrations\casino
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */

namespace Backend\integrations\casino;

use Exception;
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\UsuarioBono;
use Backend\dto\Subproveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\TransaccionApi;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\mysql\CiudadMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionApiMySqlDAO;

/**
 * Clase principal para la integración con el proveedor de juegos IESGAMES.
 * Contiene métodos para manejar transacciones, autenticación y operaciones relacionadas.
 */
class IESGAMES
{
    /**
     * Token de autenticación.
     *
     * @var string
     */
    private $token;

    /**
     * Identificador del usuario.
     *
     * @var integer|null
     */
    private $usuarioId;

    /**
     * Firma utilizada para validación.
     *
     * @var string
     */
    private $sign;

    /**
     * Objeto de transacción API.
     *
     * @var TransaccionApi|null
     */
    private $transaccionApi;

    /**
     * Datos asociados a la transacción.
     *
     * @var mixed
     */
    private $data;

    /**
     * Tipo de operación.
     *
     * @var string
     */
    private $tipo = "";

    /**
     * Constructor de la clase IESGAMES.
     *
     * @param string  $token  Token de autenticación.
     * @param integer $UserId ID del usuario.
     */
    public function __construct($token = "", $UserId = "")
    {
        $this->token = $token;
        $this->usuarioId = $UserId;
    }

    /**
     * Autentica al usuario y genera un token.
     *
     * @return string Respuesta en formato JSON con los datos del usuario autenticado.
     */
    public function Auth()
    {
        try {
            $Subproveedor = new Subproveedor("", "IESGAMES");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Subproveedor->getSubproveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);

            $UsuarioToken = new UsuarioToken("", $Subproveedor->getProveedorId(), $this->usuarioId);
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $saldo = floatval(round($responseG->saldo, 2));

            $return = array(
                "playerId" => $UsuarioMandante->usumandanteId,
                "totalBalance" => $saldo,
                "token" => $UsuarioToken->getToken(),
                "hasError" => 0,
                "errorId" => 0
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode());
        }
    }

    /**
     * Obtiene el balance del usuario.
     *
     * @return string Respuesta en formato JSON con el balance del usuario.
     */
    public function getBalance()
    {
        try {
            $Subproveedor = new Subproveedor("", "IESGAMES");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("BALANCE");
            $this->transaccionApi->setProveedorId($Subproveedor->getSubproveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);

            $UsuarioToken = new UsuarioToken("", $Subproveedor->getProveedorId(), $this->usuarioId);
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);

            $saldo = floatval(round($responseG->saldo, 2));

            $return = array(
                "playerId" => $UsuarioMandante->usumandanteId,
                "totalBalance" => $saldo,
                "token" => $UsuarioToken->getToken(),
                "hasError" => 0,
                "errorId" => 0
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode());
        }
    }

    /**
     * Realiza un débito en la cuenta del usuario.
     *
     * @param string  $gameId        Identificador del juego.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $roundId       Identificador de la ronda.
     * @param string  $transactionId Identificador de la transacción.
     * @param mixed   $datos         Datos adicionales relacionados con la transacción.
     * @param boolean $freespin      Indica si el débito es parte de un freespin (opcional).
     *
     * @return string Respuesta en formato JSON con los detalles de la transacción.
     */
    public function  Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $freespin = false)
    {
        $this->tipo = "DEBIT";
        $this->data = $datos;

        try {
            //Obtenemos el Proveedor con el abreviado IESGAMES
            $Subproveedor = new Subproveedor("", "IESGAMES");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Subproveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);

            //Obtenemos el Usuario Token con el token
            //$UsuarioToken = new UsuarioToken($this->token, $Subproveedor->getSubproveedorId());
            $UsuarioToken = new UsuarioToken("", $Subproveedor->getProveedorId(), $this->usuarioId);
            //Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "IESGAMES");

            //Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Subproveedor->getProveedorId());

            //Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin);

            $this->transaccionApi = $responseG->transaccionApi;
            $saldo = floatval(round($responseG->saldo, 2));

            $return = array(
                "playerId" => $UsuarioMandante->usumandanteId,
                "totalBalance" => $saldo,
                "token" => $UsuarioToken->getToken(),
                "platformTransactionId" => $responseG->transaccionApi->transaccionId,
                "hasError" => 0,
                "errorId" => 0,
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

            return $this->convertError($e->getCode());
        }
    }

    /**
     * Realiza un débito local en la cuenta del usuario.
     *
     * @param string  $gameId        Identificador del juego.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $roundId       Identificador de la ronda.
     * @param string  $transactionId Identificador de la transacción.
     * @param mixed   $datos         Datos adicionales relacionados con la transacción.
     * @param boolean $freespin      Indica si el débito es parte de un freespin (opcional).
     * @param integer $NumCards      Número de cartas asociadas a la transacción.
     *
     * @return string Respuesta en formato JSON con los detalles de la transacción.
     */
    public function DebitLocal($gameId, $debitAmount, $roundId, $transactionId, $datos, $freespin = false, $NumCards)
    {
        $this->tipo = "DEBIT";
        $this->data = $datos;

        try {
            //Obtenemos el Proveedor con el abreviado IESGAMES
            $Subproveedor = new Subproveedor("", "IESGAMES");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Subproveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);

            //Obtenemos el Usuario Token con el token
            //$UsuarioToken = new UsuarioToken($this->token, $Subproveedor->getSubproveedorId());
            $UsuarioToken = new UsuarioToken($this->token, $Subproveedor->getProveedorId());
            //Obtenemos el Usuario Mandante con el Usuario Token

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "IESGAMES");
            //Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Subproveedor->getProveedorId());

            //Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            //$ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin);

            $IESGAMESSERVICES = new \Backend\integrations\casino\IESGAMESSERVICES();

            $data = array(
                "playerId" => $UsuarioMandante->getUsumandanteId(),
                "currency" => $UsuarioMandante->getMoneda(),
                "amount" => floatval($debitAmount),
                "gameCode" => $roundId,
                "platformTransactionId" => $responseG->transaccionId,
                "numCards" => $NumCards
            );

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            $respon = $IESGAMESSERVICES->SecuencialExternalBet($data, $Usuario, $Producto);

            if ($respon->error == true) {
                $return = $this->Rollback($debitAmount, $roundId, $transactionId, $UsuarioMandante->getUsumandanteId(), json_encode($datos));
                $return = json_decode($return);
            } else {
                $this->transaccionApi = $responseG->transaccionApi;
                $saldo = floatval(round($responseG->saldo, 2));
                $return = array(
                    "playerId" => $UsuarioMandante->usumandanteId,
                    "totalBalance" => $saldo,
                    "token" => $UsuarioToken->getToken(),
                    "platformTransactionId" => $responseG->transaccionId,
                    "hasError" => 0,
                    "errorId" => 0,
                );

                //Guardamos la Transaccion Api necesaria de estado OK
                $this->transaccionApi->setTValue(json_encode($respon));

                $this->transaccionApi->setRespuestaCodigo("OK");
                $this->transaccionApi->setRespuesta(json_encode($return));
                $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                $TransaccionApiMySqlDAO->update($this->transaccionApi);
                $TransaccionApiMySqlDAO->getTransaction()->commit();
            }

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode());
        }
    }

    /**
     * Realiza un débito en la cuenta del usuario utilizando un código de bono.
     *
     * @param string  $gameId        Identificador del juego.
     * @param float   $debitAmount   Monto a debitar.
     * @param string  $roundId       Identificador de la ronda.
     * @param string  $transactionId Identificador de la transacción.
     * @param mixed   $datos         Datos adicionales relacionados con la transacción.
     * @param boolean $freespin      Indica si el débito es parte de un freespin.
     * @param string  $bonuscode     Código del bono utilizado en la transacción.
     *
     * @return string Respuesta en formato JSON con los detalles de la transacción.
     */
    public function DebitBono($gameId, $debitAmount, $roundId, $transactionId, $datos, $freespin, $bonuscode)
    {
        $this->tipo = "DEBIT";
        $this->data = $datos;

        try {

            //Obtenemos el Proveedor con el abreviado IESGAMES
            $Subproveedor = new Subproveedor("", "IESGAMES");
            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId("CODE" . $transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Subproveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);

            //Obtenemos el Usuario Token con el token
            //$UsuarioToken = new UsuarioToken($this->token, $Subproveedor->getSubproveedorId());
            $UsuarioToken = new UsuarioToken("", $Subproveedor->getProveedorId(), $this->usuarioId);
            //Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "IESGAMES");
            //Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Subproveedor->getProveedorId());

            //Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin);

            $this->transaccionApi = $responseG->transaccionApi;
            $saldo = floatval(round($responseG->saldo, 2));

            $ConfigurationEnvironment = new ConfigurationEnvironment();
            $bonuscode = $ConfigurationEnvironment->DepurarCaracteres($bonuscode);
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Registro = new Registro('', $Usuario->usuarioId);
            $CiudadMySqlDAO = new CiudadMySqlDAO();
            $Ciudad = $CiudadMySqlDAO->load($Registro->ciudadId);

            $codeUsuarioBono = '';
            $SkeepRows = 0;
            $OrderedItem = 1;
            $MaxRows = 100;
            $mensajesEnviados = [];
            $mensajesRecibidos = [];

            $json2 = '{"rules" : [{"field" : "usuario_bono.codigo", "data": "' . $bonuscode . '","op":"eq"},{"field" : "usuario_bono.estado", "data": "L","op":"eq"},{"field" : "bono_interno.estado", "data": "A","op":"eq"},{"field" : "bono_interno.tipo", "data": "8","op":"eq"}] ,"groupOp" : "AND"}';

            $UsuarioBono = new UsuarioBono();
            $UsuarioBonos = $UsuarioBono->getUsuarioBonosCustom(" usuario_bono.*,bono_interno.* ", "usuario_bono.usubono_id", "asc", $SkeepRows, $MaxRows, $json2, true);
            $UsuarioBonos = json_decode($UsuarioBonos);

            if ($UsuarioBonos->count[0]->{'.count'} > 0) {
                $codeUsuarioBono = $UsuarioBonos->data[0]->{'usuario_bono.bono_id'};
            }

            $BonoDetalle = new BonoDetalle();
            $rules = [];

            array_push($rules, array("field" => "bono_interno.bono_id", "data" => $codeUsuarioBono, "op" => "eq"));
            array_push($rules, array("field" => "bono_interno.estado", "data" => "A", "op" => "eq"));

            array_push($rules, array("field" => "bono_detalle.tipo", "data" => "NUMBERCARTONS", "op" => "eq"));
            array_push($rules, array("field" => "bono_detalle.moneda", "data" => $UsuarioMandante->moneda, "op" => "eq"));
            $filtro = array("rules" => $rules, "groupOp" => "AND");

            $json = json_encode($filtro);

            $bonodetalles = $BonoDetalle->getBonoDetallesCustom(" bono_detalle.*, bono_interno.*", "bono_interno.bono_id", "asc", 0, 1000, $json, true);
            $bonodetalles = json_decode($bonodetalles);
            if (oldCount($bonodetalles->data) > 0) {
                $CantidadCartones = $bonodetalles->data[0]->{'bono_detalle.valor'};
            }

            $detalles = array(
                "Depositos" => 0,
                "DepositoEfectivo" => false,
                "MetodoPago" => 0,
                "ValorDeposito" => 0,
                "PaisPV" => 0,
                "DepartamentoPV" => 0,
                "CiudadPV" => 0,
                "PuntoVenta" => 0,
                "PaisUSER" => $Usuario->paisId,
                "DepartamentoUSER" => $Ciudad->deptoId,
                "CiudadUSER" => $Registro->ciudadId,
                "MonedaUSER" => $Usuario->moneda,
                "CodePromo" => $bonuscode
            );

            $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();

            $BonoInterno = new BonoInterno();
            $detalles = json_decode(json_encode($detalles));

            $Transaction = $BonoInternoMySqlDAO->getTransaction();

            if ($codeUsuarioBono == "") {
                //$responseBonus = $BonoInterno->agregarBono("", $Usuario->usuarioId, $Usuario->mandante, $detalles, $Transaction);
            } else {
                $responseBonus = $BonoInterno->agregarBonoFree($codeUsuarioBono, $Usuario->usuarioId, $Usuario->mandante, $detalles, '', $bonuscode, $Transaction);
            }

            $existeBono = false;

            if ($responseBonus->WinBonus) {
                $existeBono = true;
                $Transaction->commit();
            } else {
                throw new Exception("El codigo de bono ingresado es incorrecto", "30008");
            }

            $platformTransactionId = explode("CODE", $responseG->transaccionApi->transaccionId);

            if ($existeBono) {
                $return = array(
                    "playerId" => $UsuarioMandante->usumandanteId,
                    "totalBalance" => $saldo,
                    "numCards" => $CantidadCartones,
                    "amount" => $debitAmount,
                    "token" => $UsuarioToken->getToken(),
                    "platformTransactionId" => $platformTransactionId[1],
                    "hasError" => 0,
                    "errorId" => 0,
                );
                //Guardamos la Transaccion Api necesaria de estado OK
                $this->transaccionApi->setRespuestaCodigo("OK");
                $this->transaccionApi->setRespuesta(json_encode($return));
                $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                $TransaccionApiMySqlDAO->update($this->transaccionApi);
                $TransaccionApiMySqlDAO->getTransaction()->commit();

                return json_encode($return);
            } else {
                throw new Exception("El codigo de bono ingresado es incorrecto", "30008");
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode());
        }
    }

    /**
     * Realiza un rollback en la cuenta del usuario.
     *
     * Este método revierte una transacción previamente realizada, asegurando que
     * los valores asociados a la transacción sean restaurados correctamente.
     *
     * @param float   $rollbackAmount Monto a revertir.
     * @param string  $roundId        Identificador de la ronda.
     * @param string  $transactionId  Identificador de la transacción.
     * @param integer $player         Identificador del jugador.
     * @param mixed   $datos          Datos adicionales relacionados con la transacción.
     *
     * @return string Respuesta en formato JSON con los detalles del rollback.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos)
    {
        $this->tipo = "ROLLBACK";
        $usuarioid = $player;
        $this->data = $datos;

        try {
            $Subproveedor = new Subproveedor("", "IESGAMES");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Subproveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);

            try {
                $TransaccionApi2 = new TransaccionApi("", $transactionId, $Subproveedor->getProveedorId());
                $producto = $TransaccionApi2->getProductoId();
                $identificador = $TransaccionApi2->getIdentificador();
                $UsuarioMandante = new UsuarioMandante($TransaccionApi2->getUsuarioId());
                //Obtenemos el Usuario Token con el token
                $UsuarioToken = new UsuarioToken("", $Subproveedor->getProveedorId(), $this->usuarioId);
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            $this->transaccionApi->setProductoId($producto);
            $this->transaccionApi->setIdentificador($identificador);

            $Game = new Game();
            $responseG = $Game->rollback($UsuarioMandante, $Subproveedor, $this->transaccionApi, false);
            $this->transaccionApi = $responseG->transaccionApi;

            $saldo = floatval(round($responseG->saldo, 2));

            $return = array(
                "playerId" => $UsuarioMandante->usumandanteId,
                "totalBalance" => $saldo,
                "token" => $UsuarioToken->getToken(),
                "platformTransactionId" => $responseG->transaccionApi->transaccionId,
                "hasError" => 0,
                "errorId" => 0,
            );

            //Guardamos la Transaccion Api necesaria de estado OK
            $this->transaccionApi->setRespuestaCodigo("OK");
            $this->transaccionApi->setRespuesta(json_encode($return));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode());
        }
    }

    /**
     * Realiza un crédito en la cuenta del usuario.
     *
     * Este método procesa una transacción de crédito para un usuario en un juego específico.
     *
     * @param string $gameId        Identificador del juego.
     * @param float  $creditAmount  Monto a acreditar.
     * @param string $roundId       Identificador de la ronda.
     * @param string $transactionId Identificador de la transacción. Si está vacío, se genera uno automáticamente.
     * @param mixed  $datos         Datos adicionales relacionados con la transacción.
     *
     * @return string Respuesta en formato JSON con los detalles de la transacción.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos)
    {
        $this->tipo = "CREDIT";
        $this->data = $datos;
        $array = json_decode($datos);
        $currency = $array->currency;

        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try {
            //Obtenemos el Proveedor con el abreviado IESGAMES
            $Subproveedor = new Subproveedor("", "IESGAMES");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Subproveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);
            //$this->transaccionApi->setIdentificador($roundId . "IESGAMES");

            /*  Obtenemos el Usuario Token con el token */
            // Obtenemos el Usuario Token con el token
            $UsuarioToken = new UsuarioToken("", $Subproveedor->getProveedorId(), $this->usuarioId);

            //  Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "IESGAMES");

            //  Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Subproveedor->getProveedorId());

            //  Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            //  Agregamos Elementos a la Transaccion API
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());

            $Game = new Game();
            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, true);
            $this->transaccionApi = $responseG->transaccionApi;

            $saldo = floatval(round($responseG->saldo, 2));

            $return = array(
                "playerId" => $UsuarioMandante->usumandanteId,
                "totalBalance" => $saldo,
                "token" => $UsuarioToken->getToken(),
                "platformTransactionId" => $responseG->transaccionApi->transaccionId,
                "hasError" => 0,
                "errorId" => 0,
            );

            //Guardamos la Transaccion Api necesaria de estado OK
            $this->transaccionApi->setRespuestaCodigo("OK");
            $this->transaccionApi->setRespuesta(json_encode($return));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode());
        }
    }

    /**
     * Convierte un código de error en una respuesta de error estandarizada.
     *
     * Este método mapea códigos de error específicos a mensajes y códigos de error
     * predefinidos para la integración con IESGAMES. También registra el error en
     * la API de transacciones si es aplicable.
     *
     * @param integer $code Código de error a convertir.
     *
     * @return string Respuesta codificada en JSON con los detalles del error.
     */
    public function convertError($code)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $Subproveedor = new Subproveedor("", "IESGAMES");
        $response = array();

        switch ($code) {
            case 22:
                $codeProveedor = 8; //OK
                $messageProveedor = "Wrong Player Id";
                break;

            case 20001:
                $codeProveedor = 21; //OK
                $messageProveedor = "Not Enough Balance or Redeemed Code";
                break;

            case 20003:
                $codeProveedor = 29; //OK
                $messageProveedor = "Player is Blocked";
                break;

            case 10011:
                $codeProveedor = 102; //OK
                $messageProveedor = "Invalid Token";
                break;

            case 21:
                $codeProveedor = 102; //OK
                $messageProveedor = "Invalid Token";
                break;

            case 28:
                $codeProveedor = 107; //OK
                $messageProveedor = "Transaction Not Found";
                break;

            case 29:
                $codeProveedor = 107; //OK
                $messageProveedor = "Transaction Not Found";
                break;

            case 10005:
                $codeProveedor = 107; //OK
                $messageProveedor = "Transaction Not Found";
                break;

            case 10015:
                $codeProveedor = 111; //OK
                $messageProveedor = "Rollback already processed";
                break;

            case 10001:
                $codeProveedor = 110; //OK
                $messageProveedor = "Transaction Exists";
                break;

            case 10010:
                $codeProveedor = 110; //OK
                $messageProveedor = "Transaction Exists";
                break;

            case 10007:
                $codeProveedor = 109; //OK
                $messageProveedor = "Wrong Transaction Amount or Wrong Transaction Code";
                break;

            case 10008:
                $codeProveedor = 109; //OK
                $messageProveedor = "Wrong Transaction Amount or Wrong Transaction Code";
                break;

            case 20000:
                $codeProveedor = 112;
                $messageProveedor = "Session expired";
                break;

            default:
                $codeProveedor = 130; //OK
                $messageProveedor = "General Error";
                break;
        }

        $respuesta = json_encode(array_merge($response, array(
            "hasError" => 1,
            "errorId" => $codeProveedor,
            "errorDescription" => $messageProveedor
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
