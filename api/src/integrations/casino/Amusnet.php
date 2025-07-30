<?php

/**
 * Clase Amusnet para la integración con el proveedor de juegos AMUSNET.
 *
 * Este archivo contiene la implementación de la clase Amusnet, que proporciona
 * métodos para realizar operaciones como autenticación, débito, crédito, rollback,
 * y manejo de errores relacionados con las transacciones de juegos.
 *
 * @category   Integración
 * @package    API
 * @subpackage Casino
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;

use Backend\utils\RedisConnectionTrait;
use Exception;
use Backend\dto\Pais;
use SimpleXMLElement;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Categoria;
use Backend\dto\Proveedor;
use Backend\dto\Clasificador;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioToken;
use Backend\dto\SubproveedorMandantePais;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransaccionApi;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\ProductoMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\UsuarioHistorial;
use Backend\dto\CategoriaProducto;
use Backend\dto\TransaccionProducto;
use Backend\dto\UsuarioConfiguracion;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionApiMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;

/**
 * Clase principal para la integración con AMUSNET.
 */
class Amusnet
{
    /**
     *Contraseña del usuario.
     *
     * @var string
     */
    private $Password;

    /**
     * Nombre de usuario para la autenticación.
     *
     * @var string
     */
    private $UserName;

    /**
     * Token de autenticación del usuario.
     *
     * @var string
     */
    private $token;

    /**
     * Objeto que representa la transacción API.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * Método actual en uso para la operación.
     *
     * @var string
     */
    private $method;

    /**
     * Identificador del juego.
     *
     * @var string
     */
    private $gameId;

    /**
     * Identificador del usuario.
     *
     * @var string
     */
    private $usuarioId;

    /**
     * Datos adicionales relacionados con la operación.
     *
     * @var mixed
     */
    private $data;

    /**
     * Tipo de operación (e.g., DEBIT, CREDIT).
     *
     * @var string
     */
    private $tipo;

    /**
     * Constructor de la clase Amusnet.
     *
     * @param string $token Token de autenticación.
     * @param string $playerId Identificador del jugador.
     * @param string $gameId Identificador del juego.
     */
    public function __construct($token = "", $playerId, $gameId)
    {
        $seguir = true;

        if ($seguir) {


            $this->token = $token;
            $this->usuarioId = $playerId;
            $this->gameId = $gameId;

            $Proveedor = new Proveedor("", "AMUSNET");

            if ($this->token != "") {
                try {
                    $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                    $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
                } catch (Exception $e) {
                    $UsuarioMandante = new UsuarioMandante($this->usuarioId);
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->usumandanteId);
                }
            } else {
                $UsuarioMandante = new UsuarioMandante($this->usuarioId);
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->usumandanteId);
            }

            try {
                $Producto = new Producto($UsuarioToken->productoId);
            } catch (Exception $e) {
                $Producto = new Producto("", $this->gameId, $Proveedor->getProveedorId());
            }

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $this->UserName = $credentials->USERNAME;
            $this->Password = $credentials->PASSWORD;


            if ($redis != null) {
                try {
                    $redis->set($cachedKey, json_encode($credentials), $redisParam);

                } catch (Exception $e) {
                }
            }

        }
    }


    /**
     * Método para autenticar un usuario.
     *
     * @param string $UserName Nombre de usuario.
     * @param string $Password Contraseña del usuario.
     * @param string $PortalCode Código del portal.
     *
     * @return string Respuesta en formato XML.
     */
    public function Auth($UserName, $Password, $PortalCode)
    {
        $this->method = "<AuthResponse></AuthResponse>";
        try {
            if ($UserName === $this->UserName && $Password === $this->Password) {
                $Proveedor = new Proveedor("", "AMUSNET");
            } else {
                throw new Exception("Credenciales incorrectas", "20002");
            }
            //validar si el Id del resquest es el mismo Id de usuarioToken.

            if ($this->usuarioId == "") {
                throw new Exception("UsuariId vacio", "10021");
            } else {
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->proveedorId);

                if ($UsuarioToken->usuarioId != $this->usuarioId) {
                    throw new Exception("Token vacio", "10011");
                }
                //  Obtenemos el Usuario Mandante con el Usuario Token
                $UsuarioMandante = new UsuarioMandante($this->usuarioId);

                $Producto = new Producto("", $this->gameId, $Proveedor->getProveedorId());
            }

            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            $ConfigurationEnvironment = new ConfigurationEnvironment();

            if ($ConfigurationEnvironment->isDevelopment()) {
                $Currency = explode("_", $PortalCode);
                $Currency = $Currency[3];
            } else {
                $Currency = explode("_", $PortalCode);
                $Currency = $Currency[2];
            }

            if ($UsuarioMandante->moneda != $Currency) {
                throw new Exception("Moneda incorrecta", "10017");
            }

            $Producto = new Producto($UsuarioToken->productoId);

            $UsuarioToken->setToken($UsuarioToken->createToken());
            $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
            $UsuarioToken->setProductoId($Producto->productoId);
            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
            $UsuarioTokenMySqlDAO->insert($UsuarioToken);
            $UsuarioTokenMySqlDAO->getTransaction()->commit();

            $Game = new Game();

            $responseG = $Game->autenticate($UsuarioMandante);
            $saldo = floatval($responseG->saldo * 100);

            $PKT = new SimpleXMLElement("<AuthResponse></AuthResponse>");
            $PKT->addChild('Balance', $saldo);
            $PKT->addChild('ErrorCode', 1000);
            $PKT->addChild('AuthenticationToken', $UsuarioToken->token);
            $PKT->addChild('ErrorMessage', "OK");

            return $PKT->asXML();
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el código de defensa del usuario.
     *
     * @return string Respuesta en formato XML.
     */
    public function GetDefenceCode()
    {
        try {
            $Proveedor = new Proveedor("", "AMUSNET");

            if ($this->token != "") {
                //  Obtenemos el Usuario Token con el token
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                //  Obtenemos el Usuario Mandante con el Usuario Token
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
                //  Obtenemos el Usuario Mandante con el Usuario Token
                $UsuarioMandante = new UsuarioMandante($this->usuarioId);
                $UsuarioToken = new UsuarioToken('', $Proveedor->getProveedorId(), $UsuarioMandante->getUsumandanteId());
            }
            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);
            $PKT = new SimpleXMLElement("<GetDefenceCodeResponse></GetDefenceCodeResponse>");
            $PKT->addChild('PlayerId', $UsuarioMandante->usumandanteId);
            $PKT->addChild('DefenceCode', $UsuarioToken->token);
            $PKT->addChild('ErrorCode', 1000);
            $PKT->addChild('ErrorMessage', "OK");
            return $PKT->asXML();
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un débito en la cuenta del usuario.
     *
     * @param string $gameId Identificador del juego.
     * @param string $ticketId Identificador del ticket.
     * @param float $debitAmount Monto a debitar.
     * @param string $transactionId Identificador de la transacción.
     * @param mixed $datos Datos adicionales.
     * @param boolean $freespin Indica si es un giro gratis.
     * @param string $UserName Nombre de usuario.
     * @param string $Password Contraseña del usuario.
     *
     * @return string Respuesta en formato XML.
     */
    public function Debit($gameId, $ticketId, $debitAmount, $transactionId, $datos, $freespin, $UserName, $Password)
    {
        try {
            $this->data = $datos;
            $this->method = "<WithdrawResponse></WithdrawResponse>";
            $this->tipo = "DEBIT";
            if ($UserName === $this->UserName && $Password === $this->Password) {
                //  Obtenemos el Proveedor con el abreviado AMUSNET
                $Proveedor = new Proveedor("", "AMUSNET");
            } else {
                throw new Exception("Credenciales incorrectas", "20002");
            }


            $UsuarioMandante = new UsuarioMandante($this->usuarioId);
            //  Obtenemos el Usuario Mandante con el Usuario Token
            $Producto = new Producto("", $gameId, $Proveedor->proveedorId);

            $UsuarioToken = new UsuarioToken("", $Proveedor->proveedorId, $UsuarioMandante->usumandanteId);
            if ($UsuarioToken->usuarioId != $this->usuarioId) {
                throw new Exception("Token vacio", "10011");
            }

            //  Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);

            $this->transaccionApi->setIdentificador($ticketId . $UsuarioMandante->getUsumandanteId() . "AMUSNET");
            $Game = new Game();

            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin);

            $this->transaccionApi = $responseG->transaccionApi;
            $saldo = floatval($responseG->saldo * 100);

            //  Retornamos el mensaje satisfactorio

            $PKT = new SimpleXMLElement("<WithdrawResponse></WithdrawResponse>");
            $PKT->addChild('Balance', $saldo);
            $PKT->addChild('CasinoTransferId', $responseG->transaccionId);
            $PKT->addChild('ErrorCode', 1000);
            $PKT->addChild('ErrorMessage', "OK");

            $respuesta = $PKT->asXML();

            //  Guardamos la Transaccion Api necesaria de estado OK
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $PKT->asXML();
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un débito y una respuesta en la cuenta del usuario.
     *
     * @param string $gameId Identificador del juego.
     * @param string $ticketId Identificador del ticket.
     * @param float $debitAmount Monto a debitar.
     * @param string $transactionId Identificador de la transacción.
     * @param mixed $datos Datos adicionales.
     * @param boolean $freespin Indica si es un giro gratis.
     * @param string $UserName Nombre de usuario.
     * @param string $Password Contraseña del usuario.
     *
     * @return string Respuesta en formato XML.
     */
    public function WithdrawAndResponse($gameId, $ticketId, $debitAmount, $transactionId, $datos, $freespin, $UserName, $Password)
    {
        try {
            $this->data = $datos;
            $this->method = "<WithdrawAndDepositResponse></WithdrawAndDepositResponse>";
            $this->tipo = "DEBIT";
            if ($UserName === $this->UserName && $Password === $this->Password) {
                //  Obtenemos el Proveedor con el abreviado AMUSNET
                $Proveedor = new Proveedor("", "AMUSNET");
            } else {
                throw new Exception("Credenciales incorrectas", "20002");
            }

            $UsuarioMandante = new UsuarioMandante($this->usuarioId);

            //  Obtenemos el Usuario Mandante con el Usuario Token
            $Producto = new Producto("", $gameId, $Proveedor->proveedorId);

            //  Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);

            $this->transaccionApi->setIdentificador($ticketId . $UsuarioMandante->getUsumandanteId() . "AMUSNET");
            $Game = new Game();

            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin);

            $this->transaccionApi = $responseG->transaccionApi;
            $saldo = floatval($responseG->saldo * 100);

            //  Retornamos el mensaje satisfactorio

            $PKT = new SimpleXMLElement("<WithdrawAndDepositResponse></WithdrawAndDepositResponse>");
            $PKT->addChild('Balance', $saldo);
            $PKT->addChild('CasinoTransferId', $responseG->transaccionId);
            $PKT->addChild('ErrorCode', 1000);
            $PKT->addChild('ErrorMessage', "OK");

            $respuesta = $PKT->asXML();

            //  Guardamos la Transaccion Api necesaria de estado OK
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $PKT->asXML();
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un rollback en la cuenta del usuario.
     *
     * @param string $gameId Identificador del juego.
     * @param string $roundId Identificador de la ronda.
     * @param float $rollbackAmount Monto a revertir.
     * @param string $transactionId Identificador de la transacción.
     * @param mixed $datos Datos adicionales.
     * @param string $UserName Nombre de usuario.
     * @param string $Password Contraseña del usuario.
     *
     * @return string Respuesta en formato XML.
     */
    public function Rollback($gameId, $roundId, $rollbackAmount, $transactionId, $datos, $UserName, $Password)
    {
        try {
            $this->data = $datos;
            $this->method = "<DepositResponse></DepositResponse>";
            $this->tipo = "ROLLBACK";
            if ($UserName === $this->UserName && $Password === $this->Password) {
                //  Obtenemos el Proveedor con el abreviado AMUSNET
                $Proveedor = new Proveedor("", "AMUSNET");
            } else {
                throw new Exception("Credenciales incorrectas", "20002");
            }


            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor(0);

            $TransaccionJuego = new TransaccionJuego('', $roundId . $this->usuarioId . "AMUSNET");

            if ($TransaccionJuego->getValorPremio() != 0) {
                throw new Exception("Ronda cerrada", "10016");
            }

            try {
                $TransaccionApi2 = new TransaccionApi("", $transactionId, $Proveedor->getProveedorId());
                $producto = $TransaccionApi2->getProductoId();
                $identificador = $TransaccionApi2->getIdentificador();
                $UsuarioMandante = new UsuarioMandante($TransaccionApi2->getUsuarioId());
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            if ($TransaccionApi2->getValor() != $rollbackAmount) {
                throw new Exception("Detalles de la transacción no coinciden", "10007");
            }

            $this->transaccionApi->setProductoId($producto);
            $this->transaccionApi->setIdentificador($identificador);

            $Game = new Game();

            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false);

            $this->transaccionApi = $responseG->transaccionApi;
            $saldo = floatval($responseG->saldo * 100);

            $PKT = new SimpleXMLElement("<DepositResponse></DepositResponse>");
            $PKT->addChild('Balance', $saldo);
            $PKT->addChild('CasinoTransferId', $responseG->transaccionId);
            $PKT->addChild('ErrorCode', 1000);
            $PKT->addChild('ErrorMessage', "OK");

            $respuesta = $PKT->asXML();

            //Guardamos la Transaccion Api necesaria de estado OK
            $this->transaccionApi->setRespuestaCodigo("OK");
            $this->transaccionApi->setRespuesta(json_encode($respuesta));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $PKT->asXML();
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un crédito en la cuenta del usuario.
     *
     * @param string $gameId Identificador del juego.
     * @param string $roundId Identificador de la ronda.
     * @param float $creditAmount Monto a acreditar.
     * @param string $transactionId Identificador de la transacción.
     * @param mixed $datos Datos adicionales.
     * @param boolean $isEndRound Indica si es el final de la ronda.
     * @param string $UserName Nombre de usuario.
     * @param string $Password Contraseña del usuario.
     *
     * @return string Respuesta en formato XML.
     */
    public function Credit($gameId, $roundId, $creditAmount, $transactionId, $datos, $isEndRound = false, $UserName, $Password)
    {
        try {
            $this->data = $datos;
            $this->method = "<DepositResponse></DepositResponse>";
            $this->tipo = "CREDIT";
            if ($UserName === $this->UserName && $Password === $this->Password) {
                //  Obtenemos el Proveedor con el abreviado AMUSNET
                $Proveedor = new Proveedor("", "AMUSNET");
            } else {
                throw new Exception("Credenciales incorrectas", "20002");
            }


            $UsuarioMandante = new UsuarioMandante($this->usuarioId);

            //  Obtenemos el Proveedor con el abreviado AMUSNET
            $Producto = new Producto("", $gameId, $Proveedor->proveedorId);

            //  Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "AMUSNET");
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());
            //  Agregamos Elementos a la Transaccion API
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());
            $Game = new Game();

            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $isEndRound);

            $this->transaccionApi = $responseG->transaccionApi;

            //  Retornamos el mensaje satisfactorio
            $saldo = floatval($responseG->saldo * 100);

            $PKT = new SimpleXMLElement("<DepositResponse></DepositResponse>");
            $PKT->addChild('Balance', $saldo);
            $PKT->addChild('CasinoTransferId', $responseG->transaccionId);
            $PKT->addChild('ErrorCode', 1000);
            $PKT->addChild('ErrorMessage', "OK");

            $respuesta = $PKT->asXML();

            //  Guardamos la Transaccion Api necesaria de estado OK
            $this->transaccionApi->setRespuesta(json_encode($respuesta));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $PKT->asXML();
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un crédito y genera una respuesta en la cuenta del usuario.
     *
     * @param string $gameId Identificador del juego.
     * @param string $roundId Identificador de la ronda.
     * @param float $creditAmount Monto a acreditar.
     * @param string $transactionId Identificador de la transacción.
     * @param mixed $datos Datos adicionales.
     * @param boolean $isEndRound Indica si es el final de la ronda.
     * @param string $UserName Nombre de usuario.
     * @param string $Password Contraseña del usuario.
     *
     * @return string Respuesta en formato XML.
     */
    public function DepositResponse($gameId, $roundId, $creditAmount, $transactionId, $datos, $isEndRound = false, $UserName, $Password)
    {
        try {
            $this->data = $datos;
            $this->method = "<WithdrawAndDepositResponse></WithdrawAndDepositResponse>";
            $this->tipo = "CREDITRESPONSE";
            if ($UserName === $this->UserName && $Password === $this->Password) {
                //  Obtenemos el Proveedor con el abreviado AMUSNET
                $Proveedor = new Proveedor("", "AMUSNET");
            } else {
                throw new Exception("Credenciales incorrectas", "20002");
            }


            $UsuarioMandante = new UsuarioMandante($this->usuarioId);

            //  Obtenemos el Proveedor con el abreviado AMUSNET
            $Producto = new Producto("", $gameId, $Proveedor->proveedorId);

            //  Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "AMUSNET");
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());
            //  Agregamos Elementos a la Transaccion API
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());
            $Game = new Game();

            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $isEndRound);

            $this->transaccionApi = $responseG->transaccionApi;

            $saldo = floatval($responseG->saldo * 100);
            //  Retornamos el mensaje satisfactorio


            $PKT = new SimpleXMLElement("<WithdrawAndDepositResponse></WithdrawAndDepositResponse>");
            $PKT->addChild('Balance', $saldo);
            $PKT->addChild('CasinoTransferId', $responseG->transaccionId);
            $PKT->addChild('ErrorCode', 1000);
            $PKT->addChild('ErrorMessage', "OK");

            $respuesta = $PKT->asXML();

            //  Guardamos la Transaccion Api necesaria de estado OK
            $this->transaccionApi->setRespuesta(json_encode($respuesta));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $PKT->asXML();
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Convierte un error en una respuesta XML.
     *
     * @param integer $code Código del error.
     * @param string $message Mensaje del error.
     *
     * @return string Respuesta en formato XML.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $Proveedor = new Proveedor("", "AMUSNET");

        switch ($code) {
            case 10011:
                $codeProveedor = '3100';
                $messageProveedor = "EXPIRED";
                break;
            case 10012:
                $codeProveedor = '3100';
                $messageProveedor = "EXPIRED";
                break;
            case 21:
                $codeProveedor = '3100';
                $messageProveedor = "EXPIRED";
                break;


            case 20001:
                $codeProveedor = '3100';
                $messageProveedor = "INSUFFICIENT_FUNDS";
                break;

            case 0:
                $codeProveedor = '3000';
                $messageProveedor = "INTERNAL_SERVER_ERROR";
                break;

            case 10017:
                $codeProveedor = '3000';
                $messageProveedor = "INTERNAL_SERVER_ERROR";
                break;

            case 10021:
                $codeProveedor = '3000';
                $messageProveedor = "INTERNAL_SERVER_ERROR";
                break;

            case 10001:
                $codeProveedor = "1100";
                $messageProveedor = "DUPLICATE";
                $UsuarioMandante = new UsuarioMandante($this->usuarioId);

                $Game = new Game();
                $responseG = $Game->getBalance($UsuarioMandante);

                $PKT = new SimpleXMLElement($this->method);

                $PKT->addChild('ErrorCode', $codeProveedor);
                $PKT->addChild('ErrorMessage', $messageProveedor);
                $PKT->addChild('Balance', intval($responseG->saldo * 100));

                break;
            case 20002:
                $codeProveedor = "3000";
                $messageProveedor = "INTERNAL_SERVER_ERROR";
                break;

            default:
                $codeProveedor = "3000";
                $messageProveedor = "INTERNAL_SERVER_ERROR";
                break;
        }

        if ($code != 10001) {
            $PKT = new SimpleXMLElement($this->method);

            $PKT->addChild('ErrorCode', $codeProveedor);
            $PKT->addChild('ErrorMessage', $messageProveedor);
        }

        if ($this->transaccionApi != null) {
            $Text = $PKT->asXML();
            $this->transaccionApi->setTipo("R" . $this->transaccionApi->getTipo());
            $this->transaccionApi->setRespuestaCodigo("ERROR_" . $code);
            $this->transaccionApi->setRespuesta($Text);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->insert($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();
        }

        return $PKT->asXML();
    }
}
