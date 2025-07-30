<?php

/**
 * Esta clase implementa la integración con el proveedor Nsoft, proporcionando métodos
 * para la autenticación.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-22
 */

namespace Backend\integrations\virtual;

use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Mandante;
use Backend\dto\Pais;
use Backend\dto\Registro;
use Backend\dto\Subproveedor;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransaccionApi;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioToken;
use Backend\dto\Producto;
use Backend\dto\ProductoMandante;
use Backend\dto\Proveedor;
use Backend\integrations\casino\Game;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransaccionApiMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;
use Exception;

/**
 * Clase Nsoft
 *
 * Esta clase implementa la integración con el proveedor Nsoft, proporcionando métodos
 * para la autenticación, manejo de sesiones, transacciones de débito, crédito, rollback,
 * y obtención de detalles del jugador y balance.
 */
class Nsoft
{
    /**
     * Token de autenticación del usuario.
     *
     * Este token se utiliza para autenticar las solicitudes realizadas
     * al proveedor Nsoft.
     *
     * @var string
     */
    private $token;

    /**
     * Objeto para manejar transacciones API.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * Método actual en ejecución.
     *
     * @var string
     */
    private $metodo;

    /**
     * Identificador del usuario.
     *
     * @var string
     */
    private $userId;

    /**
     * Datos adicionales para la transacción.
     *
     * @var array
     */
    private $data;

    /**
     * Constructor de la clase Nsoft.
     *
     * @param string $token     Token de autenticación del usuario.
     * @param string $UsuarioId ID del usuario.
     */
    public function __construct($token = "", $UsuarioId = "")
    {
        $this->token = $token;
        $this->userId = $UsuarioId;
    }

    /**
     * Autentica al usuario con el proveedor Nsoft
     *
     * @return string Respuesta en formato JSON con los detalles del usuario autenticado
     */
    public function Auth()
    {
        try {
            $Proveedor = new Proveedor("", "NSOFT");

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
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            $saldo = floatval(round($Usuario->getBalance(), 2) * 100);

            $return = array(
                "uuid" => $UsuarioToken->getUsuarioId(),
                "currency" => $UsuarioMandante->moneda,
                "credit" => $saldo,
                "hash" => $this->token,
                "displayname" => $Usuario->nombre,
            );
            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Verifica la validez de la sesión del usuario.
     *
     * Este método realiza los siguientes pasos:
     * 1. Valida que el token no esté vacío.
     * 2. Busca al proveedor con nombre "NSOFT".
     * 3. Crea una transacción API para la autenticación.
     * 4. Obtiene la información del usuario a través del token.
     * 5. Devuelve un JSON indicando si la sesión es válida.
     *
     * @return string JSON con la propiedad `isValid` establecida en `true` si la sesión es válida,
     *                junto con el `sessionId`. En caso de error, devuelve un mensaje de error.
     *
     * @throws Exception Si el token está vacío o si ocurre un error en la autenticación.
     */
    public function SessionCheck()
    {
        try {
            $Proveedor = new Proveedor("", "NSOFT");

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
                "isValid" => true,
                "sessionId" => $UsuarioToken->token
            );
            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Verifica la validez de un token de usuario y realiza una autenticación.
     *
     * Esta función realiza una autenticación mediante un token proporcionado. Si el token es válido, la función
     * devuelve un array con la propiedad `isValid` establecida en `true`. Si el token está vacío o ocurre cualquier
     * error durante el proceso, se lanzará una excepción y la función devolverá el error correspondiente.
     *
     * @return string Un JSON con la propiedad `isValid` establecida en `true` si el token es válido.
     *                Si ocurre un error, se devuelve un mensaje de error con un código de excepción.
     *
     * @throws Exception Si el token está vacío o si ocurre un error en la autenticación.
     */
    public function TempToken()
    {
        try {
            $Proveedor = new Proveedor("", "NSOFT");

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
                "isValid" => true,
            );
            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del usuario en base al ID del usuario proporcionado.
     *
     * Este método realiza los siguientes pasos:
     * 1. Busca al proveedor con nombre "NSOFT".
     * 2. Crea una transacción API para consultar el balance.
     * 3. Obtiene la información del usuario a través del ID del usuario.
     * 4. Consulta el saldo del usuario desde el sistema de juegos.
     * 5. Devuelve el balance en formato JSON con el estado, saldo y moneda.
     *
     * @return string JSON codificado con la siguiente estructura:
     *                [
     *                  "status" => "OK",
     *                  "balance" => (float) Saldo del usuario en centavos,
     *                  "currency" => (string) Moneda del usuario
     *                ]
     *                o bien un mensaje de error si ocurre una excepción.
     *
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function getBalance()
    {
        try {
            $Proveedor = new Proveedor("", "NSOFT");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("BALANCE");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);


            $UsuarioMandante = new UsuarioMandante($this->userId);

            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Balance = floatval(round($Usuario->getBalance(), 2) * 100);

            $return = array(
                "status" => "OK",
                "balance" => $Balance,
                "currency" => $UsuarioMandante->moneda
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene los detalles del jugador.
     *
     * Este método realiza los siguientes pasos:
     * 1. Valida que el token no esté vacío.
     * 2. Busca al proveedor con nombre "NSOFT".
     * 3. Crea una transacción API para consultar el balance.
     * 4. Obtiene la información del usuario a través del token.
     * 5. Devuelve un JSON con los detalles del jugador, incluyendo ID, nombre de usuario, correo electrónico,
     *    nombre y apellido.
     *
     * @return string JSON con los detalles del jugador:
     *                [
     *                  "id" => (int) ID del usuario mandante,
     *                  "username" => (string) Nombre de usuario,
     *                  "email" => (string) Correo electrónico,
     *                  "firstName" => (string) Primer nombre,
     *                  "lastName" => (string) Primer apellido
     *                ]
     *
     * @throws Exception Si el token está vacío o si ocurre un error durante el proceso.
     */
    public function getPlayerDetails()
    {
        try {
            $Proveedor = new Proveedor("", "NSOFT");

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
            $Registro = new Registro("", $UsuarioMandante->usuarioMandante);
            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);

            $Balance = floatval(number_format(round($responseG->saldo, 2), 2, '.', ''));


            $return = array(
                "id" => $UsuarioMandante->usumandanteId,
                "username" => $UsuarioMandante->usuarioMandante,
                "email" => $UsuarioMandante->email,
                "firstName" => $Registro->nombre1,
                "lastName" => $Registro->apellido1,
            );

            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }


    /**
     * Procesa un débito en una transacción de juego.
     *
     * Esta función maneja el proceso de débito en el contexto de una transacción de juego, validando el token de usuario,
     * creando una transacción API, y actualizando el saldo del usuario. En caso de un error, se captura la excepción y
     * se devuelve el mensaje de error correspondiente.
     *
     * @param int    $gameId        El ID del juego relacionado con la transacción de débito.
     * @param float  $debitAmount   La cantidad de dinero a debitar en la transacción.
     * @param string $roundId       El identificador de la ronda en la que se realiza la transacción.
     * @param string $transactionId El ID de la transacción asociada al débito.
     * @param array  $datos         Datos adicionales necesarios para la transacción.
     *
     * @return string Un JSON que contiene el estado de la transacción (`status`), el saldo actual del usuario (`balance`),
     *                la moneda utilizada (`currency`), y un mensaje indicando el estado de la transacción (`msg`).
     *
     * @throws Exception Si ocurre un error durante el proceso de débito, como un token vacío, juego no disponible,
     *                   o problemas con el producto o el usuario.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos)
    {
        $this->data = $datos;

        try {
            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            //Obtenemos el Proveedor con el abreviado Nsoft
            $Proveedor = new Proveedor("", "NSOFT");

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

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "NSOFT");

            $Game = new Game();
            $responseG = $Game->getBalance($UsuarioMandante);

            $oldBalance = round($responseG->saldo, 2);
            $oldBalance = str_replace(',', '', number_format(round($oldBalance, 2), 2, '.', null));

            try {
                //Obtenemos el producto con el gameId
                $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
            } catch (Exception $e) {
                if ($e->getCode() == '26') {
                    $gameId = 'general';
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

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Balance = floatval(round($Usuario->getBalance(), 2) * 100);

            $return = array(
                "status" => "OK",
                "balance" => $Balance,
                "currency" => $UsuarioMandante->moneda,
                "msg" => "Transaccion procesada",
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
     * Realiza una transacción de rollback
     *
     * @param float  $rollbackAmount Monto a revertir.
     * @param string $roundId        ID de la ronda.
     * @param string $transactionId  ID de la transacción.
     * @param string $player         ID del jugador.
     * @param array  $datos          Datos adicionales de la transacción.
     *
     * @return string Respuesta en formato JSON con el resultado de la transacción.
     */
    public function Rollback($rollbackAmount, $roundId, $transactionId, $player, $datos)
    {
        $usuarioid = $player;
        $this->data = $datos;


        try {
            //Obtenemos el Proveedor con el abreviado Nsoft
            $Proveedor = new Proveedor("", "NSOFT");

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
                $TransaccionApi2 = new TransaccionApi("", $transactionId, $Proveedor->getProveedorId());
                $identificador = $TransaccionApi2->getIdentificador();
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            $UsuarioMandante = new UsuarioMandante($TransaccionApi2->getUsuarioId());
            $TransaccionJuego = new TransaccionJuego("", $identificador);

            if ( ! $TransaccionJuego->existsTicketId()) {
                throw new Exception("Ticket ID ya existe", "10026");
            }

            $this->transaccionApi->setIdentificador($identificador);
            $oldBalance = 0;

            try {
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                $Balance = floatval(round($Usuario->getBalance(), 2) * 100);
            } catch (Exception $e) {
            }

            //Obtenemos el producto con el gameId
            // $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            $Game = new Game();
            $responseG = $Game->rollback($UsuarioMandante, $Proveedor, $this->transaccionApi, false, $transactionId, false);
            $this->transaccionApi = $responseG->transaccionApi;

            $return = array(
                "status" => "OK",
                "balance" => $Balance,
                "currency" => $UsuarioMandante->moneda,
                "msg" => "Transaccion procesada",
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
     * Procesa un crédito en una transacción de juego.
     *
     * Esta función maneja el proceso de crédito en el contexto de una transacción de juego, validando el token de usuario,
     * creando una transacción API, y actualizando el saldo del usuario. En caso de un error, se captura la excepción y
     * se devuelve el mensaje de error correspondiente.
     *
     * @param int    $gameId        El ID del juego relacionado con la transacción de crédito.
     * @param float  $creditAmount  La cantidad de dinero a acreditar en la transacción.
     * @param string $roundId       El identificador de la ronda en la que se realiza la transacción.
     * @param string $transactionId El ID de la transacción asociada al crédito.
     * @param array  $datos         Datos adicionales necesarios para la transacción.
     * @param bool   $isEndRound    Indica si la transacción corresponde al final de una ronda. (Opcional, por defecto es `false`).
     *
     * @return string Un JSON que contiene el estado de la transacción (`status`), el saldo actual del usuario (`balance`),
     *                la moneda utilizada (`currency`), y un mensaje indicando el estado de la transacción (`msg`).
     *
     * @throws Exception Si ocurre un error durante el proceso de crédito, como un token vacío, juego no disponible,
     *                   o problemas con el producto o el usuario.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos, $isEndRound = false)
    {
        $this->data = $datos;
        $this->metodo = "CREDIT";
        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try {
            //Obtenemos el Proveedor con el abreviado Nsoft
            $Proveedor = new Proveedor("", "NSOFT");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId("CREDIT" . $transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);

            //Obtenemos el Usuario Token con el token

            //Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($this->userId);

            $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->getUsumandanteId());

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "NSOFT");

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

            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, $isEndRound, false, false, false);

            $this->transaccionApi = $responseG->transaccionApi;

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Balance = floatval(round($Usuario->getBalance(), 2) * 100);


            $return = array(
                "status" => "OK",
                "balance" => $Balance,
                "currency" => $UsuarioMandante->moneda,
                "msg" => "Transaccion procesada",
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
     * ConfirmTransation - Procesa la confirmación de una transacción y actualiza el balance del usuario.
     *
     * Esta función realiza un "ROLLBACK" en una transacción, verificando el estado de la misma y ajustando
     * el saldo del usuario correspondiente.
     *
     * @param string $transactionId El identificador único de la transacción que se desea confirmar o revertir.
     *                              Ejemplo: "abc123".
     * @param array  $datos         Un array con los datos adicionales necesarios para procesar la transacción.
     *                              Ejemplo: [
     *                              "key" => "value",
     *                              "anotherKey" => "anotherValue"
     *                              ].
     *
     * @return string Retorna una respuesta en formato JSON con el estado de la transacción y el balance actualizado.
     *
     * @throws Exception Si ocurre algún error durante el proceso, se lanza una excepción con el mensaje y código de error.
     */
    public function ConfirmTransation($transactionId, $datos)
    {
        $this->data = $datos;

        $this->tipo = "ROLLBACK";
        try {
            //Obtenemos el Proveedor con el abreviado Beterlive
            $Proveedor = new Proveedor("", "NSOFT");
            $SubProveedor = new Subproveedor("", "NSOFT");

            $TransjuegoLog = new TransjuegoLog("", "", "", $transactionId . '_' . $SubProveedor->getSubproveedorId(), $SubProveedor->getSubproveedorId());

            if ($TransjuegoLog != "") {
                $UsuarioMandante = new UsuarioMandante($this->userId);
                $Game = new Game();
                $responseG = $Game->getBalance($UsuarioMandante);

                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                $Balance = floatval(round($Usuario->getBalance(), 2) * 100);
            }

            $return = array(
                "status" => "OK",
                "balance" => $Balance,
                "currency" => $UsuarioMandante->moneda,
                "msg" => "Transaccion procesada",
            );


            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Convierte errores internos del sistema en un formato estándar para el proveedor NSOFT.
     *
     * Este método mapea los códigos de error generados internamente por el sistema a los códigos
     * y descripciones que espera el proveedor NSOFT. Si el código recibido es el especial `10001`
     * (éxito), adicionalmente retorna el estado del balance del usuario.
     *
     * También permite manejar errores como usuario no encontrado, token expirado, transacción duplicada,
     * hash inválido o saldo insuficiente.
     *
     * @param int|string $code    Código de error interno del sistema.
     * @param string     $message Mensaje de error interno detallado.
     *
     * @return string JSON con el código y mensaje adaptado al proveedor, o con datos de balance si es éxito.
     */
    public function convertError($code, $message)
    {
        $Proveedor = new Proveedor("", "NSOFT");

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
                $codeProveedor = "INVALID_USER_TOKEN";
                $messageProveedor = "Invalid user token";
                break;
            case 21:
                $codeProveedor = "INVALID_USER_TOKEN";
                $messageProveedor = "Invalid user token";
                break;

            case 20001:
                $codeProveedor = "INSUFFICIENT_FUNDS";
                $messageProveedor = "Insufficient user fund";

                break;

            case 10017:
                $codeProveedor = 120;
                $messageProveedor = "Invalid currency code for player";
                break;

            case 28:
                $codeProveedor = "PAYMENT_ID_NOT_FOUND";
                $messageProveedor = "Payment id not found";
                switch ($this->metodo) {
                    case "CREDIT":


                        $transaccionApi2 = new TransaccionApi("", $this->transaccionApi->getTransaccionId(), $Proveedor->getProveedorId());

                        $UsuarioMandante = new UsuarioMandante($transaccionApi2->usuarioId);
                        $Game = new Game();
                        $responseG = $Game->getBalance($UsuarioMandante);

                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                        $Balance = floatval(round($Usuario->getBalance(), 2) * 100);

                        $return = array(
                            "status" => "OK",
                            "balance" => $Balance,
                            "currency" => $UsuarioMandante->moneda,
                            "msg" => "Transaccion procesada",
                        );
                        return json_encode($return);
                        break;
                }
                break;
            case 29:
                $codeProveedor = "PAYMENT_ID_NOT_FOUND";
                $messageProveedor = "Payment id not found";

                break;

            case 10001:
                $codeProveedor = "DUPLICATE_PAYMENT_ID";
                $messageProveedor = "Duplicate payment id";

                break;

            case 10004:
                $codeProveedor = 105;

                $messageProveedor = "Request processing services unavailable.";
                break;

            case 10005:

                $codeProveedor = "PAYMENT_ID_NOT_FOUND";
                $messageProveedor = "Payment id not found";

                break;

            default:
                $codeProveedor = "ERROR";
                $messageProveedor = "Generic error";

                break;
        }

        $respuesta = (array_merge($response, array(
            "code" => $codeProveedor,
            "state" => $messageProveedor
        )));

        if ($this->transaccionApi != null) {
            $this->transaccionApi->setTipo("R" . $this->transaccionApi->getTipo());
            $this->transaccionApi->setRespuestaCodigo("ERROR" . $code);
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->insert($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();
        }


        return json_encode($respuesta);
    }
}
