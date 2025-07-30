<?php

/**
 * Esta clase proporciona métodos para interactuar con la integración de juegos de ESA Gaming.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-21
 */

namespace Backend\integrations\poker;

use Backend\integrations\casino\Game;
use Exception;
use \SoapClient;
use Backend\dto\Pais;
use \SimpleXMLElement;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Proveedor;
use Backend\dto\Categoria;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioToken;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransaccionApi;
use Backend\dto\ProductoDetalle;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\CategoriaProducto;
use Backend\mysql\ProductoMySqlDAO;
use Backend\dto\TransaccionProducto;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionApiMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;

/**
 * Clase EsaGaming
 *
 * Esta clase proporciona métodos para interactuar con la integración de juegos de ESA Gaming.
 * Incluye funcionalidades como autenticación de usuarios, obtención de información de usuarios,
 * manejo de transacciones y balance, entre otros.
 */
class EsaGaming
{
    /**
     * URL del servicio de ESA Gaming.
     *
     * @var string
     */
    private $URL = "https://doradobet-test.egaming.com/";

    /**
     * Metodo a ejecutar en la integración.
     *
     * @var string
     */
    private $method = "";

    /**
     * Parámetros para la solicitud.
     *
     * @var array
     */
    private $params = [];

    /**
     * ID del usuario.
     *
     * @var string
     */
    private $userId = "";

    /**
     * Nombre del usuario.
     *
     * @var string
     */
    private $userName = "";

    /**
     * Proveedor del servicio.
     *
     * @var string
     */
    private $provider = "ESAGAMING";

    /**
     * Instancia de la transacción API.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * Instancia del proveedor.
     *
     * @var Proveedor
     */
    private $Proveedor;

    /**
     * Constructor de la clase EsaGaming.
     *
     * Inicializa las propiedades del usuario y configura el proveedor y el entorno.
     *
     * @param string $userId   ID del usuario (opcional).
     * @param string $userName Nombre del usuario (opcional).
     */
    public function __construct($userId = "", $userName = "")
    {
        if ($userId != "") {
            $this->userId = $userId;
        }
        if ($$userName != "") {
            $this->userName = $userName;
        }

        // Configura el proveedor del servicio.
        $Proveedor = new Proveedor("", $this->provider);

        // Configura el entorno de ejecución.
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
            // Configuración específica para el entorno de desarrollo.
        } else {
            // Configuración específica para el entorno de producción.
        }
    }

    /**
     * Autentica a un usuario en el sistema de ESA Gaming.
     *
     * Este metodo procesa una solicitud de autenticación de usuario, valida las credenciales
     * y devuelve información relevante del usuario autenticado.
     *
     * @param string $request JSON que contiene los datos de la solicitud de autenticación.
     *                        Debe incluir `username`, `password` y `partnerId`.
     *
     * @return string Respuesta en formato de cadena con los datos del usuario autenticado,
     *                incluyendo `resultCode`, `resultMsg`, `userId`, `screenName`, `lastUpdate` y `bonusActive`.
     *
     * @throws Exception Si ocurre un error durante el proceso de autenticación.
     */
    public function userAuthenticate($request)
    {
        try {
            $request = json_decode($request);

            switch ($request->partnerId) {
                case 1:
                    $request->partnerId = '0';
                    break;
                case 2:
                    $request->partnerId = '1';
                    break;
                case 3:
                    $request->partnerId = '2';
                    break;
                case 4:
                    $request->partnerId = '6';
                    break;
            }

            $Usuario = new Usuario('', $request->username);


            $auth = $Usuario->login($request->username, $request->password, 0, $request->partnerId);

            $UsuarioMandante = new UsuarioMandante($auth->user_id);

            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            $resultCode = 1;
            $bonusActive = 0;

            $return = "resultCode=" . $resultCode . "&resultMsg=Success&userId=" . intval(
                    $responseG->usuarioId
                ) . "&context=&screenName=" . "usuario_" . $Usuario->usuarioId . "&lastUpdate=" . date(
                    "Y-m-d H:i:s",
                    strtotime($UsuarioMandante->fechaModif)
                ) . "&bonusActive=" . $bonusActive;

            return $return;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Establece el nombre de pantalla (screenName) para un usuario.
     *
     * Este metodo configura los parámetros necesarios para actualizar
     * el nombre de pantalla de un usuario en el sistema.
     *
     * @return void
     */
    public function UserSetScreenName()
    {
        $this->params = [
            "userId" => $this->userId,
            "screenName" => $this->screenName
        ];
    }

    /**
     * Obtiene información detallada de un usuario.
     *
     * Este metodo recupera información del usuario, incluyendo su nombre, apellidos,
     * fecha de nacimiento, correo electrónico, país y moneda asociada.
     *
     * @return string Respuesta en formato de cadena con los datos del usuario,
     *                incluyendo `resultCode`, `resultMsg`, `userId`, `username`,
     *                `screenName`, `firstname`, `lastname`, `dateOfBirth`, `email`,
     *                `country` y `currency`.
     *
     * @throws Exception Si el `userId` está vacío o si ocurre un error durante el proceso.
     */
    public function userGetInfo()
    {
        try {
            if ($this->validateData([$this->userId])) {
                throw new Exception("userId vacio", "10011");
            }

            $UsuarioMandante = new UsuarioMandante($this->userId);


            $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
            $UsuarioOtrainfo = new UsuarioOtrainfo($UsuarioMandante->usuarioMandante);
            $Game = new Game();

            $responseG = $Game->autenticate($UsuarioMandante);
            $resultCode = 1;


            $return = "resultCode=" . $resultCode . "&resultMsg=Success&userId=" . intval(
                    $responseG->usuarioId
                ) . "&username=" . $Usuario->login . "&screenName=" . "usuario_" . $Usuario->usuarioId . "&firstname=" . $UsuarioMandante->nombres . "&lastname=" . $UsuarioMandante->apellidos . "&dateOfBirth=" . $UsuarioOtrainfo->getFechaNacim(
                ) . "&email=" . $UsuarioMandante->email . "&country=" . $responseG->paisIso2 . "&currency=" . $responseG->moneda;

            return $return;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance de un usuario.
     *
     * Este metodo recupera el saldo actual del usuario, junto con la moneda asociada
     * y un indicador de si hay bonos activos.
     *
     * @return string Respuesta en formato de cadena con los datos del balance,
     *                incluyendo `resultCode`, `resultMsg`, `balance`, `currency` y `bonusActive`.
     *
     * @throws Exception Si el `userId` está vacío o si ocurre un error durante el proceso.
     */
    public function userGetBalance()
    {
        try {
            if ($this->validateData([$this->userId])) {
                throw new Exception("userId vacio", "10011");
            }

            $Game = new Game();
            $UsuarioMandante = new UsuarioMandante($this->userId);

            $responseG = $Game->getBalance($UsuarioMandante);
            $resultCode = 1;
            $bonusActive = 0;
            $return = "resultCode=" . $resultCode . "&resultMsg=Success&balance=" . round(
                    $responseG->saldo,
                    2
                ) . "&currency=" . $responseG->moneda . "&bonusActive=" . $bonusActive;

            return $return;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Procesa una transacción de usuario en el sistema.
     *
     * Este metodo maneja tanto las apuestas como las ganancias de un usuario.
     * Si el monto total de la apuesta es mayor a 0, se realiza un débito.
     * Si el monto total de la ganancia es mayor a 0, se realiza un crédito.
     *
     * @param object $request Objeto que contiene los datos de la transacción, incluyendo:
     *                        - `context->game_id`: ID del juego.
     *                        - `context->total_bet_amount`: Monto total apostado.
     *                        - `context->total_win_amount`: Monto total ganado.
     *                        - `context->session_id`: ID de la sesión.
     *                        - `transactionId`: ID de la transacción.
     *
     * @return string Respuesta en formato de cadena con los resultados de la transacción.
     *
     * @throws Exception Si ocurre un error durante el proceso de la transacción.
     */
    public function UserTransaction($request)
    {
        try {
            if ($request->context->total_bet_amount > 0) {
                $response = $this->Debit(
                    $request->context->game_id,
                    $request->context->total_bet_amount,
                    $request->context->session_id,
                    $request->transactionId,
                    $request
                );
            }

            if ($request->context->total_win_amount > 0) {
                if ($request->context->total_bet_amount == 0 || $request->context->total_bet_amount == null) {
                    $this->Debit(
                        $request->context->game_id,
                        0,
                        $request->context->session_id,
                        $request->transactionId,
                        $request
                    );
                }
                $response = $this->Credit(
                    $request->context->game_id,
                    $request->context->total_win_amount,
                    $request->context->session_id,
                    "CREDIT" . $request->transationId,
                    $request
                );
            }

            return $response;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un débito en el sistema.
     *
     * Este metodo procesa una transacción de débito para un usuario, asociada a un juego específico.
     * Se encarga de registrar la transacción en la base de datos y actualizar el saldo del usuario.
     *
     * @param string $gameId        ID del juego asociado al débito.
     * @param float  $debitAmount   Monto a debitar.
     * @param string $roundId       ID de la ronda o sesión de juego.
     * @param string $transactionId ID único de la transacción.
     * @param object $datos         Datos adicionales relacionados con la transacción.
     * @param bool   $freespin      Indica si la transacción es parte de un freespin (opcional, por defecto `false`).
     *
     * @return string Respuesta en formato de cadena con los resultados del débito, incluyendo
     *                `resultCode`, `resultMsg`, `extTransactionId`, `balance` y `currency`.
     *
     * @throws Exception Si ocurre un error durante el proceso de débito.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos, $freespin = false)
    {
        $this->data = $datos;
        try {
            $Proveedor = new Proveedor("", "ESAGAMING");

            //Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);

            $UsuarioMandante = new UsuarioMandante($this->userId);


            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "ESAGAMING");

            //Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->proveedorId);


            //Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $freespin);
            $this->transaccionApi = $responseG->transaccionApi;
            $resultCode = 1;
            $return = "resultCode=" . $resultCode . "&resultMsg=Success&extTransactionId=" . $responseG->transaccionId . "&balance=" . round(
                    $responseG->saldo,
                    2
                ) . "&currency=" . $responseG->moneda;

            $this->transaccionApi->setRespuestaCodigo("OK");
            $this->transaccionApi->setRespuesta(json_encode($return));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $return;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un crédito en el sistema.
     *
     * Este método procesa una transacción de crédito para un usuario, asociada a un juego específico.
     * Se encarga de registrar la transacción en la base de datos y actualizar el saldo del usuario.
     *
     * @param string $gameId        ID del juego asociado al crédito.
     * @param float  $creditAmount  Monto a acreditar.
     * @param string $roundId       ID de la ronda o sesión de juego.
     * @param string $transactionId ID único de la transacción.
     * @param object $datos         Datos adicionales relacionados con la transacción.
     *
     * @return string Respuesta en formato de cadena con los resultados del crédito, incluyendo
     *                `resultCode`, `resultMsg`, `extTransactionId`, `balance` y `currency`.
     *
     * @throws Exception Si ocurre un error durante el proceso de crédito.
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos)
    {
        try {
            //Obtenemos el Proveedor con el abreviado ISOFTBET
            $Proveedor = new Proveedor("", "ESAGAMING");

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
                $UsuarioMandante = new UsuarioMandante($this->userId);
            } catch (Exception $e) {
                if ($e->getCode() == 21) {
                    $UsuarioMandante = new UsuarioMandante($this->userId);
                }
            }

            $this->transaccionApi->setIdentificador($roundId . $UsuarioMandante->getUsumandanteId() . "ESAGAMING");

            //  Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            //  Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            //  Agregamos Elementos a la Transaccion API
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());


            $Game = new Game();
            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transaccionApi, true);

            $this->transaccionApi = $responseG->transaccionApi;

            $resultCode = 1;
            $return = "resultCode=" . $resultCode . "&resultMsg=Success&extTransactionId=" . $responseG->transaccionId . "&balance=" . round(
                    $responseG->saldo,
                    2
                ) . "&currency=" . $responseG->moneda;

            //Guardamos la Transaccion Api necesaria de estado OK
            $this->transaccionApi->setRespuestaCodigo("OK");
            $this->transaccionApi->setRespuesta(json_encode($return));
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();

            return $return;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Procesa una solicitud entrante y ejecuta el método correspondiente basado en el valor de `method`.
     *
     * Este metodo decodifica la solicitud JSON, identifica el método a ejecutar y llama al método
     * correspondiente de la clase. Si el método no es válido, devuelve un error.
     *
     * @param string $request JSON que contiene los datos de la solicitud, incluyendo el campo `method`.
     *
     * @return mixed Respuesta del método ejecutado o un error si el método no es válido.
     */
    public function load($request)
    {
        $request = json_decode($request);
        $this->method = $request->method;
        switch ($this->method) {
            case 'UserAuthenticate':
                return $this->userAuthenticate($request);
                break;
            case 'UserGetInfo':
                return $this->userGetInfo();
                break;
            case 'UserGetBalance':
                return $this->userGetBalance();
                break;
            case 'UserTransaction':
                return $this->userTransaction($request);
                break;
            default:
                return $this->hasError("Metodo no valido");
                break;
        }
    }

    /**
     * Genera una respuesta de error con un código y un mensaje.
     *
     * @param int    $code    Código del error.
     * @param string $message Mensaje descriptivo del error.
     *
     * @return array Arreglo asociativo con las claves `resultCode` y `resultMsg`.
     */
    public function hasError($code, $message)
    {
        return [
            "resultCode" => $code,
            "resultMsg" => $message,
        ];
    }

    /**
     * Valida si los datos proporcionados son válidos.
     *
     * Este metodo verifica si alguno de los elementos en el arreglo proporcionado está vacío.
     * Si encuentra un elemento vacío, devuelve `true`, indicando que los datos no son válidos.
     *
     * @param array $data Arreglo de datos a validar.
     *
     * @return boolean `true` si algún elemento está vacío, de lo contrario `false`.
     */
    public function validateData($data)
    {
        foreach ($data as $key) {
            if ($key == "") {
                return true;
            }
        }
    }

    /**
     * Convierte un error en una respuesta formateada para el sistema.
     *
     * Este metodo toma un código de error y un mensaje, y los convierte en una respuesta
     * que puede ser entendida por el sistema de ESA Gaming. También maneja casos específicos
     * como transacciones no encontradas, usuarios bloqueados, y errores de sesión.
     *
     * @param int    $code    Código del error.
     * @param string $message Mensaje descriptivo del error.
     *
     * @return string Respuesta formateada con los detalles del error.
     */
    public function convertError($code, $message)
    {
        $Proveedor = new Proveedor("", "ESAGAMING");
        $codeProveedor = "";
        $messageProveedor = "";
        $response = array();

        switch ($code) {
            case 10011:
                $codeProveedor = "2";
                $messageProveedor = "Fail, User not found";
                break;

            case 21:
                $codeProveedor = "2";
                $messageProveedor = "Fail, User not found";
                break;

            case 22:
                $codeProveedor = "2";
                $messageProveedor = "Fail, User not found";
                break;
            case 24:
                $codeProveedor = "3";
                $messageProveedor = "Fail, Username/Password mismatch";
                break;
            case 30003:
                $codeProveedor = "3";
                $messageProveedor = "Fail, Username/Password mismatch";
                break;

            case 20001:
                $codeProveedor = "-12";
                $messageProveedor = "Fail, Insufficient balance";
                break;

            case 29:
                $codeProveedor = "0";
                $messageProveedor = "Transaction Not Found";

                $tipo = $this->transaccionApi->getTipo();

                if ($tipo == "ROLLBACK") {
                    $codeProveedor = '';
                    $messageProveedor = "";
                    $UsuarioMandante = new UsuarioMandante($this->userId);

                    $Mandante = new Mandante($UsuarioMandante->mandante);

                    if ($Mandante->propio == "S") {
                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                        $Balance = $Usuario->getBalance();
                        $response = "balance=" . $Balance;
                    }
                }

                break;

            case 10001:

                $codeProveedor = '1';
                $messageProveedor = "Already processed";

                $transaccionApi2 = new TransjuegoLog(
                    "",
                    "",
                    "" . $this->transaccionApi->getTransaccionId(),
                    $Proveedor->getProveedorId()
                );

                $UsuarioMandante = new UsuarioMandante($this->userId);

                $Mandante = new Mandante($UsuarioMandante->mandante);

                if ($Mandante->propio == "S") {
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $Balance = $Usuario->getBalance();
                    $response = "resultCode=" . $codeProveedor . "&resultMsg=Success&extTransactionId=" . $transaccionApi2->getTransjuegologId(
                        ) . "&balance=" . round($Balance, 2) . "&currency=" . $UsuarioMandante->moneda;
                }
                break;


            case 20003:
                $codeProveedor = 2;
                $messageProveedor = "ACCOUNT_BLOCKED";
                break;

            case 10005:

                try {
                    $UsuarioMandante = new UsuarioMandante($this->userId);

                    $Mandante = new Mandante($UsuarioMandante->mandante);

                    if ($Mandante->propio == "S") {
                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                        $Balance = $Usuario->getBalance();
                        $response = "balance=" . $Balance;
                        /*
                        $response = array_merge($response, array(
                            "balance" => (($Balance)),
                        ));
                        */
                    }
                } catch (Exception $e) {
                    $codeProveedor = '0';
                    $messageProveedor = "No such session.";
                }

                $this->transaccionApi->setValor(0);
                break;

            default:
                $codeProveedor = '0';
                $messageProveedor = "Failure";
                break;
        }


        if ($codeProveedor != "") {
            $respuesta = "resultCode=" . intval($codeProveedor) . "&resultMsg=" . $messageProveedor;
        } else {
            $respuesta = $response;
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

