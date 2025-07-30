<?php

/**
 * Clase Itainment
 *
 * Esta clase se encarga de gestionar la integración con el proveedor Itainment.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-21
 */

namespace Backend\integrations\sportsbook;

use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\ItTicketEnc;
use Backend\dto\ItTransaccion;
use Backend\dto\Mandante;
use Backend\dto\Pais;
use Backend\dto\Perfil;
use Backend\dto\PuntoVenta;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransjuegoLog;
use Backend\dto\TranssportsbookApi;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioToken;
use Backend\dto\Producto;
use Backend\dto\ProductoMandante;
use Backend\dto\Proveedor;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TranssportsbookApiMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;
use Exception;
use SimpleXMLElement;

/**
 * Clase principal para la integración con el proveedor Itainment.
 *
 * Esta clase contiene métodos para gestionar transacciones,
 * consultar saldos, realizar débitos, créditos, y manejar errores
 * relacionados con la integración del proveedor.
 */
class Itainment
{

    /**
     * Este atributo almacena el nombre de usuario o credenciales necesarias
     * para realizar la autenticación en el sistema.
     *
     * @var string
     */
    private $Login;

    /**
     * Este atributo almacena la contraseña o clave de acceso
     * para realizar la autenticación en el sistema.
     *
     * @var string
     */
    private $Password;

    /**
     * Este atributo almacena el token de autenticación
     * que se utiliza para validar la sesión del usuario.
     *
     * @var string
     */
    private $token;

    /**
     * Este atributo almacena el ID externo del usuario
     * que se utiliza para identificar al usuario en sistemas externos.
     *
     * @var string
     */
    private $externalId;

    /**
     * Este atributo almacena el ID del usuario
     * que se utiliza para identificar al usuario en el sistema.
     *
     * @var string
     */
    private $uid;

    /**
     * Este atributo almacena el ID del proveedor
     * que se utiliza para identificar al proveedor en el sistema.
     *
     * @var string
     */
    private $transsportsbookApi;

    /**
     * Este atributo almacena el ID del proveedor
     * que se utiliza para identificar al proveedor en el sistema.
     *
     * @var string
     */
    private $data;

    /**
     * Este atributo almacena el metodo que se está utilizando
     * para realizar la operación en el sistema.
     *
     * @var string
     */
    private $method;

    /**
     * Este atributo almacena el ID del ticket global
     * que se utiliza para identificar al ticket en el sistema.
     *
     * @var string
     */
    private $ticketIdGlobal;

    /**
     * Este atributo almacena el ID del ticket
     * que se utiliza para identificar al ticket en el sistema.
     *
     * @var string
     */
    private $timeInit;

    /**
     * Constructor de la clase Itainment.
     *
     * Inicializa los atributos necesarios para la autenticación y configuración
     * del usuario en el sistema.
     *
     * @param string $Login      Nombre de usuario o credenciales para autenticación.
     * @param string $Password   Contraseña o clave de acceso para autenticación.
     * @param string $token      Token de autenticación para validar la sesión.
     * @param string $externalId Opcional ID externo del usuario para sistemas externos.
     */
    public function __construct($Login, $Password, $token, $externalId = '')
    {
        $this->Login = $Login;
        $this->Password = $Password;
        $this->token = $token;
        $this->externalId = $externalId;
    }

    /**
     * Metodo de autenticación.
     *
     * Este metodo realiza la autenticación del usuario en el sistema.
     * Valida el token proporcionado, obtiene información del usuario y genera
     * una respuesta en formato XML con los detalles de la cuenta.
     *
     * @return string Respuesta en formato XML con los detalles de la cuenta.
     * @throws Exception Si el token está vacío o si ocurre un error durante la autenticación.
     */
    public function Auth()
    {
        $this->method = 'GetAccountDetails';

        try {
            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }
            $timeG = time();

            // Obtiene el proveedor con el identificador "ITN"
            $Proveedor = new Proveedor("", "ITN");

            // Crea un objeto UsuarioToken con el token y el ID del proveedor
            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            // Obtiene información del usuario mandante
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            $diff = time() - $timeG;

            // Obtiene información adicional del mandante, perfil y país
            $Mandante = new Mandante($UsuarioMandante->mandante);
            $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);
            $diff = time() - $timeG;
            $Perfil = new Perfil($UsuarioPerfil->perfilId);
            $Pais = new Pais($UsuarioMandante->paisId);
            $diff = time() - $timeG;

            // Realiza la autenticación del usuario en el sistema de juegos
            $Game = new Game();
            $responseG = $Game->autenticate($UsuarioMandante);

            // Calcula el balance del usuario
            $Balance = intval(($responseG->saldo) * 100);

            // Genera la respuesta en formato XML
            $PKT = new SimpleXMLElement("<PKT></PKT>");
            $Result = $PKT->addChild('Result');
            $Result->addAttribute('Name', $this->method);
            $Result->addAttribute('Success', '1');

            $Returnset = $Result->addChild('Returnset');

            // Agrega el token a la respuesta
            $Token = $Returnset->addChild('Token');
            $Token->addAttribute('Type', 'string');
            $Token->addAttribute('Value', $UsuarioToken->getToken());

            // Agrega el nombre de usuario a la respuesta
            if ($UsuarioMandante->usuarioMandante == 332) {
                $LoginName = $Returnset->addChild('LoginName');
                $LoginName->addAttribute('Type', 'string');
                $LoginName->addAttribute('Value', 'm m m m');
            } else {
                $LoginName = $Returnset->addChild('LoginName');
                $LoginName->addAttribute('Type', 'string');
                $LoginName->addAttribute('Value', 'Usuario' . $UsuarioMandante->getUsumandanteId());
            }

            // Agrega la moneda y el país a la respuesta
            $Currency = $Returnset->addChild('Currency');
            $Currency->addAttribute('Type', 'string');
            $Currency->addAttribute('Value', $responseG->moneda);
            if($responseG->paisIso2 =='VX'){
                $responseG->paisIso2 ='VE';
            }
            $Country = $Returnset->addChild('Country');
            $Country->addAttribute('Type', 'string');
            $Country->addAttribute('Value', $responseG->paisIso2);


            if (($UsuarioPerfil->perfilId == 'USUONLINE' && intval($responseG->usuarioId) > 73758) || (in_array(intval($responseG->usuarioId), array(70363, 72098, 67393, 73497, 57196)))) {
                $responseG->usuarioId = $responseG->usuarioId . 'U';
            }
            if (($UsuarioPerfil->perfilId != 'USUONLINE' && intval($responseG->usuarioId) > 140040) || (in_array(intval($responseG->usuarioId), array(77491)))) {
                $responseG->usuarioId = $responseG->usuarioId . 'P';
            }

            // Agrega el ID externo del usuario a la respuesta
            $ExternalUserID = $Returnset->addChild('ExternalUserID');
            $ExternalUserID->addAttribute('Type', 'string');
            $ExternalUserID->addAttribute('Value', $responseG->usuarioId);


            $diff = time() - $timeG;
            //syslog(10,'ITN-DIFF-TIME 10-1 ' .'AUTH'. $UsuarioMandante->getUsuarioMandante()." ". $diff);

            // Agrega el path de afiliación a la respuesta
            $AffiliationPath = $Returnset->addChild('AffiliationPath');
            $AffiliationPath->addAttribute('Type', 'string');
            $AffiliationPath->addAttribute('Value', $UsuarioMandante->getAffiliationPathAltenar());

            // Agrega el tipo de usuario externo a la respuesta
            $ExternalUserType = $Returnset->addChild('ExternalUserType');
            $ExternalUserType->addAttribute('Type', 'int');
            $ExternalUserType->addAttribute('Value', '3');

            // Agrega el balance del usuario a la respuesta
            $UserBalance = $Returnset->addChild('UserBalance');
            $UserBalance->addAttribute('Type', 'string');
            $UserBalance->addAttribute('Value', $Balance);
            $diff = time() - $timeG;

            // Retorna la respuesta en formato XML
            return explode("\n", $PKT->asXML(), 2)[1];
        } catch (Exception $e) {
            // Manejo de errores
            if ($_ENV['debug']) {
                print_r($e);
            }
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance del usuario.
     *
     * Este metodo realiza la autenticación del usuario, consulta el balance
     * en el sistema de juegos y genera una respuesta en formato XML con los
     * detalles del balance y otra información relevante.
     *
     * @return string Respuesta en formato XML con los detalles del balance.
     * @throws Exception Si el token está vacío o si ocurre un error durante la operación.
     */
    public function getBalance()
    {
        $timeG = time();

        $this->method = 'GetBalance';
        try {
            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }
            $Proveedor = new Proveedor("", "ITN");

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);
            $diff = time() - $timeG;
            //syslog(10,'ITN-DIFF-TIME 0 ' .'BALANCE'. $UsuarioMandante->getUsuarioMandante()." ". $diff);

            $Mandante = new Mandante($UsuarioMandante->mandante);

            if ($Mandante->propio == "S") {
                $Game = new Game();

                $responseG = $Game->autenticate($UsuarioMandante);


                $diff = time() - $timeG;
                //syslog(10,'ITN-DIFF-TIME 10 ' .'BALANCE'. $UsuarioMandante->getUsuarioMandante()." ". $diff);

                $Balance = intval(($responseG->saldo) * 100);

                $PKT = new SimpleXMLElement("<PKT></PKT>");

                $Result = $PKT->addChild('Result');
                $Result->addAttribute('Name', $this->method);
                $Result->addAttribute('Success', '1');

                $Returnset = $Result->addChild('Returnset');

                $Token = $Returnset->addChild('Token');
                $Token->addAttribute('Type', 'string');
                $Token->addAttribute('Value', $UsuarioToken->getToken());


                $Currency = $Returnset->addChild('Currency');
                $Currency->addAttribute('Type', 'string');
                $Currency->addAttribute('Value', $responseG->moneda);


                if (($UsuarioPerfil->perfilId == 'USUONLINE' && intval($responseG->usuarioId) > 73758) || (in_array(intval($responseG->usuarioId), array(70363, 72098, 67393, 73497, 57196)))) {
                    $responseG->usuarioId = $responseG->usuarioId . 'U';
                }
                if (($UsuarioPerfil->perfilId != 'USUONLINE' && intval($responseG->usuarioId) > 140040) || (in_array(intval($responseG->usuarioId), array(77491)))) {
                    $responseG->usuarioId = $responseG->usuarioId . 'P';
                }
                $ExternalUserID = $Returnset->addChild('ExternalUserID');
                $ExternalUserID->addAttribute('Type', 'string');
                $ExternalUserID->addAttribute('Value', $responseG->usuarioId);

                $LoginName = $Returnset->addChild('Balance');
                $LoginName->addAttribute('Type', 'string');
                $LoginName->addAttribute('Value', "$Balance");


                return explode("\n", $PKT->asXML(), 2)[1];
            }
        } catch (Exception $e) {
            if ($_ENV['debug']) {
                print_r($e);
            }
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un débito en el sistema de juegos.
     *
     * Este metodo realiza un débito en el sistema de juegos, consulta el saldo
     * y genera una respuesta en formato XML con los detalles del débito y otra
     * información relevante.
     *
     * @param String $gameId        ID del juego.
     * @param String $ticketId      ID del ticket.
     * @param String $uid           ID del usuario.
     * @param String $debitAmount   Monto a debitar.
     * @param String $transactionId ID de la transacción.
     * @param String $detalles      Detalles adicionales.
     * @param String $datos         Datos adicionales.
     * @param String $impuesto      Impuesto aplicable (opcional).
     * @param String $datos2        Datos adicionales (opcional).
     *
     * @return string Respuesta en formato XML con los detalles del débito.
     * @throws Exception Si ocurre un error durante la operación.
     */
    public function Debit($gameId, $ticketId, $uid, $debitAmount, $transactionId, $detalles, $datos, $impuesto = '0', $datos2 = '')
    {
        $timeG = time();

        $this->method = 'PlaceBet';
        $this->ticketIdGlobal = $ticketId;

        $this->data = $datos;

        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado ITN */
            $Proveedor = new Proveedor("", "ITN");

            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
            }

            $diff = time() - $timeG;

            /*  Obtenemos el producto con el gameId  */
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());


            /*  Creamos la Transaccion API  */
            $this->transsportsbookApi = new TranssportsbookApi();
            $this->transsportsbookApi->setTransaccionId($transactionId);
            $this->transsportsbookApi->setTipo("BET");
            $this->transsportsbookApi->setProveedorId($Proveedor->getProveedorId());
            $this->transsportsbookApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transsportsbookApi->setTValue($datos2);
            $this->transsportsbookApi->setUsucreaId(0);
            $this->transsportsbookApi->setUsumodifId(0);
            $this->transsportsbookApi->setValor($debitAmount);
            $this->transsportsbookApi->setIdentificador($ticketId);

            $this->transsportsbookApi->setGameReference($datos->GameReference);
            $this->transsportsbookApi->setBetStatus($datos->BetStatus);
            $this->transsportsbookApi->setMandante($UsuarioMandante->mandante);
            $this->transsportsbookApi->setTranssportId(0);


            $Game = new Game();
            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transsportsbookApi, $datos, $detalles, $impuesto);
            $Balance = intval(($responseG->saldo) * 100);

            $this->transsportsbookApi = $responseG->transsportsbookApi;

            $diff = time() - $timeG;

            /*  Retornamos el mensaje satisfactorio  */
            $PKT = new SimpleXMLElement("<PKT></PKT>");

            $Result = $PKT->addChild('Result');
            $Result->addAttribute('Name', $this->method);
            $Result->addAttribute('Success', '1');

            $Returnset = $Result->addChild('Returnset');

            $Token = $Returnset->addChild('Token');
            $Token->addAttribute('Type', 'string');
            $token = $UsuarioToken->getToken();
            $Token->addAttribute('Value', "$token");

            $Balancex = $Returnset->addChild('Balance');
            $Balancex->addAttribute('Type', 'string');
            $Balancex->addAttribute('Value', "$Balance");

            $ExtTransactionID = $Returnset->addChild('ExtTransactionID');
            $ExtTransactionID->addAttribute('Type', 'string');
            $ExtTransactionID->addAttribute('Value', "$responseG->transaccionId");

            $AlreadyProcessed = $Returnset->addChild('AlreadyProcessed');
            $AlreadyProcessed->addAttribute('Type', 'bool');
            $AlreadyProcessed->addAttribute('Value', "false");

            $RegulatorAssignedId = $Returnset->addChild('RegulatorAssignedId');
            $RegulatorAssignedId->addAttribute('Type', 'string');
            $RegulatorAssignedId->addAttribute('Value', "$responseG->clave");

            if ($responseG->saldoFree != '' && $responseG->saldoFree != '0' && $responseG->saldoFree != null) {
                $BonusAmount = $Returnset->addChild('BonusAmount');
                $BonusAmount->addAttribute('Type', 'string');
                $BonusAmount->addAttribute('Value', intval(($responseG->saldoFree) * 100));
            }

            if ($responseG->idUsuarioRelacionado != '' && $responseG->idUsuarioRelacionado != '0' && $responseG->idUsuarioRelacionado != null) {
                $CustomerId = $Returnset->addChild('CustomerId');
                $CustomerId->addAttribute('Type', 'string');
                $CustomerId->addAttribute('Value', $responseG->idUsuarioRelacionado);
            }

            $respuesta = $PKT->asXML();

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transsportsbookApi->setRespuestaCodigo("OK");
            $this->transsportsbookApi->setRespuesta($respuesta);
            $TranssportsbookApiMySqlDAO = new TranssportsbookApiMySqlDAO();
            $TranssportsbookApiMySqlDAO->update($this->transsportsbookApi);
            $TranssportsbookApiMySqlDAO->getTransaction()->commit();

            return explode("\n", $PKT->asXML(), 2)[1];
        } catch (Exception $e) {
            if ($_ENV['debug']) {
                print_r($e);
            }
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Verifica el débito en el sistema de juegos.
     *
     * Este metodo verifica el débito en el sistema de juegos, consulta el saldo
     * y genera una respuesta en formato XML con los detalles del débito y otra
     * información relevante.
     *
     * @param String $gameId        ID del juego.
     * @param String $ticketId      ID del ticket.
     * @param String $transactionId ID de la transacción.
     * @param String $datos         Datos adicionales.
     * @param String $datos2        Datos adicionales (opcional).
     *
     * @return string Respuesta en formato XML con los detalles del débito.
     * @throws Exception Si ocurre un error durante la operación.
     */
    public function CheckDebit($gameId, $ticketId, $transactionId, $datos, $datos2 = '')
    {
        $timeG = time();


        $this->method = 'PlaceBet';
        $this->ticketIdGlobal = $ticketId;


        try {
            /*  Obtenemos el Proveedor con el abreviado ITN */
            $Proveedor = new Proveedor("", "ITN");

            if ($this->token != "") {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } else {
            }


            /*  Obtenemos el producto con el gameId  */
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());


            /*  Creamos la Transaccion API  */
            $this->transsportsbookApi = new TranssportsbookApi();
            $this->transsportsbookApi->setTransaccionId($transactionId);
            $this->transsportsbookApi->setTipo("BETCHECK");
            $this->transsportsbookApi->setProveedorId($Proveedor->getProveedorId());
            $this->transsportsbookApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transsportsbookApi->setTValue($datos2);
            $this->transsportsbookApi->setUsucreaId(0);
            $this->transsportsbookApi->setUsumodifId(0);
            $this->transsportsbookApi->setValor(0);
            $this->transsportsbookApi->setIdentificador($ticketId);

            $this->transsportsbookApi->setGameReference($datos->GameReference);
            $this->transsportsbookApi->setBetStatus($datos->BetStatus);
            $this->transsportsbookApi->setMandante($UsuarioMandante->mandante);
            $this->transsportsbookApi->setTranssportId(0);

            $Game = new Game();
            $responseG = $Game->checkdebit($UsuarioMandante, $Producto, $this->transsportsbookApi, $datos);
            $Balance = intval(($responseG->saldo) * 100);
            $this->transsportsbookApi = $responseG->transsportsbookApi;


            /*  Retornamos el mensaje satisfactorio  */
            $PKT = new SimpleXMLElement("<PKT></PKT>");

            $Result = $PKT->addChild('Result');
            $Result->addAttribute('Name', $this->method);
            $Result->addAttribute('Success', '1');

            $Returnset = $Result->addChild('Returnset');

            $Token = $Returnset->addChild('Token');
            $Token->addAttribute('Type', 'string');
            $token = $UsuarioToken->getToken();
            $Token->addAttribute('Value', "$token");

            $Balancex = $Returnset->addChild('Balance');
            $Balancex->addAttribute('Type', 'string');
            $Balancex->addAttribute('Value', "$Balance");

            $ExtTransactionID = $Returnset->addChild('ExtTransactionID');
            $ExtTransactionID->addAttribute('Type', 'string');
            $ExtTransactionID->addAttribute('Value', "$responseG->transaccionId");

            $AlreadyProcessed = $Returnset->addChild('AlreadyProcessed');
            $AlreadyProcessed->addAttribute('Type', 'bool');
            $AlreadyProcessed->addAttribute('Value', "true");
            $respuesta = $PKT->asXML();

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transsportsbookApi->setRespuestaCodigo("OK");
            $this->transsportsbookApi->setRespuesta($respuesta);
            $TranssportsbookApiMySqlDAO = new TranssportsbookApiMySqlDAO();
            $TranssportsbookApiMySqlDAO->update($this->transsportsbookApi);
            $TranssportsbookApiMySqlDAO->getTransaction()->commit();

            return explode("\n", $PKT->asXML(), 2)[1];
        } catch (Exception $e) {
            if ($_ENV['debug']) {
                print_r($e);
            }

            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un rollback en el sistema de juegos.
     *
     * Este metodo realiza un rollback en el sistema de juegos, consulta el saldo
     * y genera una respuesta en formato XML con los detalles del rollback y otra
     * información relevante.
     *
     * @param String $gameId         ID del juego.
     * @param String $ticketId       ID del ticket.
     * @param String $uid            ID del usuario.
     * @param String $rollbackAmount Monto a revertir.
     * @param String $transactionId  ID de la transacción.
     * @param String $datos          Datos adicionales.
     * @param String $datos2         Datos adicionales (opcional).
     *
     * @return string Respuesta en formato XML con los detalles del rollback.
     * @throws Exception Si ocurre un error durante la operación.
     */
    public function Rollback($gameId, $ticketId, $uid, $rollbackAmount, $transactionId, $datos, $datos2 = '')
    {
        $timeG = time();

        $this->method = 'RefundBet';

        $this->ticketIdGlobal = $ticketId;

        $this->data = $datos;
        $this->timeInit = time();

        if ($_ENV['debug']) {
            print_r($this);
        }

        try {
            /*  Obtenemos el Proveedor con el abreviado ITN */
            $Proveedor = new Proveedor("", "ITN");

            /*  Creamos la Transaccion API  */
            $this->transsportsbookApi = new TranssportsbookApi();
            $this->transsportsbookApi->setTransaccionId($transactionId);
            $this->transsportsbookApi->setTipo("ROLLBACK");
            $this->transsportsbookApi->setProveedorId($Proveedor->getProveedorId());
            $this->transsportsbookApi->setTValue($datos2);
            $this->transsportsbookApi->setUsucreaId(0);
            $this->transsportsbookApi->setUsumodifId(0);
            $this->transsportsbookApi->setValor($rollbackAmount);
            $this->transsportsbookApi->setTranssportId(0);
            $this->transsportsbookApi->setMandante(0);
            $this->transsportsbookApi->setIdentificador($ticketId);


            if ($this->externalId != '') {
                $Usuario = new Usuario(str_replace('U', '', str_replace('P', '', $this->externalId)));
                $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);

                try {
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->usumandanteId);
                } catch (Exception $e) {
                    if ($_ENV['debug']) {
                        print_r($e);
                    }

                    if ($e->getCode() == 21) {
                        $UsuarioToken = new UsuarioToken();
                        $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
                        $UsuarioToken->setCookie('0');
                        $UsuarioToken->setRequestId('0');
                        $UsuarioToken->setUsucreaId(0);
                        $UsuarioToken->setUsumodifId(0);
                        $UsuarioToken->setUsuarioId($UsuarioMandante->usumandanteId);
                        $token = $UsuarioToken->createToken();
                        $UsuarioToken->setToken($token);
                        $UsuarioToken->setSaldo(0);

                        $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                        $UsuarioTokenMySqlDAO->insert($UsuarioToken);

                        $UsuarioTokenMySqlDAO->getTransaction()->commit();
                    } else {
                        throw $e;
                    }
                }
            } else {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            }


            $this->transsportsbookApi->setIdentificador($ticketId);

            /*  Obtenemos el producto con el gameId  */
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());


            $Game = new Game();


            $responseG = $Game->rollback($UsuarioMandante, $Producto, $this->transsportsbookApi, $datos);


            $this->transsportsbookApi = $responseG->transsportsbookApi;

            $Balance = intval(($responseG->saldo) * 100);


            /*  Retornamos el mensaje satisfactorio  */
            $PKT = new SimpleXMLElement("<PKT></PKT>");

            $Result = $PKT->addChild('Result');
            $Result->addAttribute('Name', $this->method);
            $Result->addAttribute('Success', '1');

            $Returnset = $Result->addChild('Returnset');

            $Token = $Returnset->addChild('Token');
            $Token->addAttribute('Type', 'string');
            $token = $UsuarioToken->getToken();
            $Token->addAttribute('Value', "$token");

            $Balancex = $Returnset->addChild('Balance');
            $Balancex->addAttribute('Type', 'string');
            $Balancex->addAttribute('Value', "$Balance");

            $ExtTransactionID = $Returnset->addChild('ExtTransactionID');
            $ExtTransactionID->addAttribute('Type', 'long');
            $ExtTransactionID->addAttribute('Value', "$responseG->transaccionId");

            $ExtTransactionID = $Returnset->addChild('AlreadyProcessed');
            $ExtTransactionID->addAttribute('Type', 'bool');
            $ExtTransactionID->addAttribute('Value', "false");
            $respuesta = $PKT->asXML();

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transsportsbookApi->setRespuestaCodigo("OK");
            $this->transsportsbookApi->setRespuesta(json_encode($respuesta));


            $Balance = intval(($responseG->saldo) * 100);

            /*  Retornamos el mensaje satisfactorio  */
            $PKT = new SimpleXMLElement("<PKT></PKT>");

            $Result = $PKT->addChild('Result');
            $Result->addAttribute('Name', $this->method);
            $Result->addAttribute('Success', '1');

            $Returnset = $Result->addChild('Returnset');

            $Token = $Returnset->addChild('Token');
            $Token->addAttribute('Type', 'string');
            $token = $UsuarioToken->getToken();
            $Token->addAttribute('Value', "$token");

            $Balancex = $Returnset->addChild('Balance');
            $Balancex->addAttribute('Type', 'string');
            $Balancex->addAttribute('Value', $Balance);

            $ExtTransactionID = $Returnset->addChild('ExtTransactionID');
            $ExtTransactionID->addAttribute('Type', 'long');
            $ExtTransactionID->addAttribute('Value', $responseG->transaccionId);

            $ExtTransactionID = $Returnset->addChild('AlreadyProcessed');
            $ExtTransactionID->addAttribute('Type', 'bool');
            $ExtTransactionID->addAttribute('Value', "false");
            $respuesta = $PKT->asXML();

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transsportsbookApi->setRespuesta(json_encode($respuesta));
            $TranssportsbookApiMySqlDAO = new TranssportsbookApiMySqlDAO();
            $TranssportsbookApiMySqlDAO->update($this->transsportsbookApi);
            $TranssportsbookApiMySqlDAO->getTransaction()->commit();

            if ($_ENV['debug']) {
                print_r('TimeEND ');
                print_r(((time() - $this->timeInit) * 1000));
            }

            return $respuesta;
        } catch (Exception $e) {
            if ($_ENV['debug']) {
                print_r('TimeEND ');
                print_r(((time() - $this->timeInit) * 1000));
                print_r($e);
            }
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un crédito en el sistema de juegos.
     *
     * Este metodo realiza un crédito en el sistema de juegos, consulta el saldo
     * y genera una respuesta en formato XML con los detalles del crédito y otra
     * información relevante.
     *
     * @param String $gameId        ID del juego.
     * @param String $ticketId      ID del ticket.
     * @param String $uid           ID del usuario.
     * @param String $creditAmount  Monto a acreditar.
     * @param String $transactionId ID de la transacción.
     * @param String $isEndRound    Indica si es el final de la ronda (opcional).
     * @param String $datos         Datos adicionales.
     * @param String $method        Metodo utilizado (opcional).
     * @param String $datos2        Datos adicionales (opcional).
     *
     * @return string Respuesta en formato XML con los detalles del crédito.
     * @throws Exception Si ocurre un error durante la operación.
     */
    public function Credit($gameId, $ticketId, $uid, $creditAmount, $transactionId, $isEndRound, $datos, $method = "", $datos2 = '')
    {
        $timeG = time();

        $this->method = $method;

        $this->ticketIdGlobal = $ticketId;

        $this->data = $datos;

        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }
            /*  Obtenemos el Proveedor con el abreviado ITN */
            $Proveedor = new Proveedor("", "ITN");

            /*  Creamos la Transaccion API  */
            $this->transsportsbookApi = new TranssportsbookApi();
            $this->transsportsbookApi->setTransaccionId($transactionId);
            $this->transsportsbookApi->setTipo($datos->TipoTransaccion);

            $this->transsportsbookApi->setProveedorId($Proveedor->getProveedorId());
            $this->transsportsbookApi->setTValue($datos2);
            $this->transsportsbookApi->setUsucreaId(0);
            $this->transsportsbookApi->setUsumodifId(0);
            $this->transsportsbookApi->setValor($creditAmount);
            $this->transsportsbookApi->setIdentificador($ticketId);
            $this->transsportsbookApi->setGameReference($datos->GameReference);
            $this->transsportsbookApi->setBetStatus($datos->BetStatus);


            $ItTicketEnc = new ItTicketEnc($ticketId);
            $UsuarioMandante = new UsuarioMandante('', $ItTicketEnc->usuarioId, $ItTicketEnc->mandante);


            /*  Obtenemos el Usuario Token con el token */

            try {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->usumandanteId);

                $token = $UsuarioToken->createToken();
            } catch (Exception $e) {
                if ($_ENV['debug']) {
                    print_r($e);
                }


                if ($e->getCode() == 21) {
                    $UsuarioToken = new UsuarioToken();
                    $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
                    $UsuarioToken->setCookie('0');
                    $UsuarioToken->setRequestId('0');
                    $UsuarioToken->setUsucreaId(0);
                    $UsuarioToken->setUsumodifId(0);
                    $UsuarioToken->setUsuarioId($UsuarioMandante->usumandanteId);
                    $token = $UsuarioToken->createToken();
                    $UsuarioToken->setToken($token);
                    $UsuarioToken->setSaldo(0);

                    $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                    $UsuarioTokenMySqlDAO->insert($UsuarioToken);

                    $UsuarioTokenMySqlDAO->getTransaction()->commit();
                } else {
                    throw $e;
                }
            }

            /*  Obtenemos el producto con el gameId  */
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());


            $Game = new Game();

            $responseG = $Game->credit($UsuarioMandante, $Producto, $this->transsportsbookApi, $isEndRound, $datos);
            $Balance = intval(($responseG->saldo) * 100);


            $this->transsportsbookApi = $responseG->transsportsbookApi;


            /*  Retornamos el mensaje satisfactorio  */

            $PKT = new SimpleXMLElement("<PKT></PKT>");

            $Result = $PKT->addChild('Result');
            $Result->addAttribute('Name', $this->method);
            $Result->addAttribute('Success', '1');

            $Returnset = $Result->addChild('Returnset');

            $Token = $Returnset->addChild('Token');
            $Token->addAttribute('Type', 'string');
            $Token->addAttribute('Value', $token);

            $Balancex = $Returnset->addChild('Balance');
            $Balancex->addAttribute('Type', 'string');
            $Balancex->addAttribute('Value', "$Balance");

            $ExtTransactionID = $Returnset->addChild('ExtTransactionID');
            $ExtTransactionID->addAttribute('Type', 'long');
            $ExtTransactionID->addAttribute('Value', "$responseG->transaccionId");

            $ExtTransactionID = $Returnset->addChild('AlreadyProcessed');
            $ExtTransactionID->addAttribute('Type', 'bool');
            $ExtTransactionID->addAttribute('Value', "false");
            $respuesta = $PKT->asXML();

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transsportsbookApi->setRespuestaCodigo("OK");
            $this->transsportsbookApi->setRespuesta(json_encode($respuesta));
            $TranssportsbookApiMySqlDAO = new TranssportsbookApiMySqlDAO();
            $TranssportsbookApiMySqlDAO->update($this->transsportsbookApi);
            $TranssportsbookApiMySqlDAO->getTransaction()->commit();

            return explode("\n", $PKT->asXML(), 2)[1];
        } catch (Exception $e) {
            if ($_ENV['debug']) {
                print_r($e);
            }


            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un bono en el sistema de juegos.
     *
     * Este metodo realiza un bono en el sistema de juegos, consulta el saldo
     * y genera una respuesta en formato XML con los detalles del bono y otra
     * información relevante.
     *
     * @param String $gameId        ID del juego.
     * @param String $BonusId       ID del bono.
     * @param String $BonusPlanId   ID del plan de bonos.
     * @param String $BonusAmount   Monto del bono.
     * @param String $transactionId ID de la transacción.
     * @param String $datos         Datos adicionales.
     * @param String $method        Metodo utilizado (opcional).
     * @param String $datos2        Datos adicionales (opcional).
     *
     * @return string Respuesta en formato XML con los detalles del bono.
     * @throws Exception Si ocurre un error durante la operación.
     */
    public function AwardBonus($gameId, $BonusId, $BonusPlanId, $BonusAmount, $transactionId, $datos, $method = "", $datos2 = '')
    {
        $timeG = time();
        $this->method = $method;

        $this->ticketIdGlobal = $BonusId;

        $this->data = $datos;

        $BonusAmount = $BonusAmount / 100;

        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado ITN */
            $Proveedor = new Proveedor("", "ITN");

            /*  Creamos la Transaccion API  */
            $this->transsportsbookApi = new TranssportsbookApi();
            $this->transsportsbookApi->setTransaccionId($transactionId);
            $this->transsportsbookApi->setTipo('WINBONUS');
            $this->transsportsbookApi->setProveedorId($Proveedor->getProveedorId());
            $this->transsportsbookApi->setTValue($datos2);
            $this->transsportsbookApi->setUsucreaId(0);
            $this->transsportsbookApi->setUsumodifId(0);
            $this->transsportsbookApi->setValor($BonusAmount);
            $this->transsportsbookApi->setIdentificador($BonusId);


            if ($this->externalId != '') {
                $Usuario = new Usuario(str_replace('U', '', str_replace('P', '', $this->externalId)));
                $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);
            } else {
                /*  Obtenemos el Usuario Token con el token */
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            }


            /*  Obtenemos el Usuario Token con el token */
            try {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->usumandanteId);

                $token = $UsuarioToken->createToken();
            } catch (Exception $e) {
                if ($_ENV['debug']) {
                    print_r($e);
                }


                if ($e->getCode() == 21) {
                    $UsuarioToken = new UsuarioToken();
                    $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
                    $UsuarioToken->setCookie('0');
                    $UsuarioToken->setRequestId('0');
                    $UsuarioToken->setUsucreaId(0);
                    $UsuarioToken->setUsumodifId(0);
                    $UsuarioToken->setUsuarioId($UsuarioMandante->usumandanteId);
                    $token = $UsuarioToken->createToken();
                    $UsuarioToken->setToken($token);
                    $UsuarioToken->setSaldo(0);

                    $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                    $UsuarioTokenMySqlDAO->insert($UsuarioToken);

                    $UsuarioTokenMySqlDAO->getTransaction()->commit();
                } else {
                    throw $e;
                }
            }
            /*  Obtenemos el producto con el gameId  */
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());


            $Game = new Game();

            $responseG = $Game->AwardBonus($UsuarioMandante, $Producto, $this->transsportsbookApi, $BonusId, $BonusPlanId, $datos);
            $Balance = intval(($responseG->saldo) * 100);


            $this->transsportsbookApi = $responseG->transsportsbookApi;


            /*  Retornamos el mensaje satisfactorio  */

            $PKT = new SimpleXMLElement("<PKT></PKT>");

            $Result = $PKT->addChild('Result');
            $Result->addAttribute('Name', $this->method);
            $Result->addAttribute('Success', '1');

            $Returnset = $Result->addChild('Returnset');

            $Token = $Returnset->addChild('Token');
            $Token->addAttribute('Type', 'string');
            $Token->addAttribute('Value', $token);

            $ExtTransactionID = $Returnset->addChild('ExtTransactionID');
            $ExtTransactionID->addAttribute('Type', 'long');
            $ExtTransactionID->addAttribute('Value', "$responseG->transaccionId");

            $ExtTransactionID = $Returnset->addChild('AlreadyProcessed');
            $ExtTransactionID->addAttribute('Type', 'bool');
            $ExtTransactionID->addAttribute('Value', "false");

            $Balancex = $Returnset->addChild('Balance');
            $Balancex->addAttribute('Type', 'string');
            $Balancex->addAttribute('Value', "$Balance");


            $respuesta = $PKT->asXML();

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transsportsbookApi->setRespuestaCodigo("OK");
            $this->transsportsbookApi->setRespuesta(json_encode($respuesta));
            $TranssportsbookApiMySqlDAO = new TranssportsbookApiMySqlDAO();
            $TranssportsbookApiMySqlDAO->update($this->transsportsbookApi);
            $TranssportsbookApiMySqlDAO->getTransaction()->commit();

            return explode("\n", $PKT->asXML(), 2)[1];
        } catch (Exception $e) {
            if ($_ENV['debug']) {
                print_r($e);
            }

            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un ajuste de saldo en el sistema de juegos.
     *
     * Este metodo realiza un ajuste de saldo en el sistema de juegos, consulta
     * el saldo y genera una respuesta en formato XML con los detalles del ajuste
     * y otra información relevante.
     *
     * @param String $gameId        ID del juego.
     * @param String $BonusStatus   Estado del bono.
     * @param String $BonusId       ID del bono.
     * @param String $BonusPlanId   ID del plan de bonos.
     * @param String $BonusAmount   Monto del bono.
     * @param String $transactionId ID de la transacción.
     * @param String $datos         Datos adicionales.
     * @param String $method        Metodo utilizado (opcional).
     * @param String $datos2        Datos adicionales (opcional).
     *
     * @return string Respuesta en formato XML con los detalles del ajuste.
     * @throws Exception Si ocurre un error durante la operación.
     */
    public function BonusBalance($gameId, $BonusStatus, $BonusId, $BonusPlanId, $BonusAmount, $transactionId, $datos, $method = "", $datos2 = '')
    {
        $timeG = time();
        $this->method = $method;

        $this->ticketIdGlobal = $BonusId;

        $this->data = $datos;

        $BonusAmount = $BonusAmount / 100;

        try {
            if ($this->token == "" && $this->externalId == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado ITN */
            $Proveedor = new Proveedor("", "ITN");

            /*  Creamos la Transaccion API  */
            $this->transsportsbookApi = new TranssportsbookApi();
            $this->transsportsbookApi->setTransaccionId($transactionId);
            $this->transsportsbookApi->setTipo('WINBONUS');
            $this->transsportsbookApi->setProveedorId($Proveedor->getProveedorId());
            $this->transsportsbookApi->setTValue($datos2);
            $this->transsportsbookApi->setUsucreaId(0);
            $this->transsportsbookApi->setUsumodifId(0);
            $this->transsportsbookApi->setValor($BonusAmount);
            $this->transsportsbookApi->setIdentificador($BonusId);


            $Usuario = new Usuario(str_replace('U', '', str_replace('P', '', $this->externalId)));

            /*  Obtenemos el Usuario Mandante con el Usuario Token */
            $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);


            /*  Obtenemos el Usuario Token con el token */

            try {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->usumandanteId);

                $token = $UsuarioToken->createToken();
            } catch (Exception $e) {
                if ($_ENV['debug']) {
                    print_r($e);
                }


                if ($e->getCode() == 21) {
                    $UsuarioToken = new UsuarioToken();
                    $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
                    $UsuarioToken->setCookie('0');
                    $UsuarioToken->setRequestId('0');
                    $UsuarioToken->setUsucreaId(0);
                    $UsuarioToken->setUsumodifId(0);
                    $UsuarioToken->setUsuarioId($UsuarioMandante->usumandanteId);
                    $token = $UsuarioToken->createToken();
                    $UsuarioToken->setToken($token);
                    $UsuarioToken->setSaldo(0);

                    $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                    $UsuarioTokenMySqlDAO->insert($UsuarioToken);

                    $UsuarioTokenMySqlDAO->getTransaction()->commit();
                } else {
                    throw $e;
                }
            }
            /*  Obtenemos el producto con el gameId  */
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());


            $Game = new Game();

            $responseG = $Game->BonusBalance($UsuarioMandante, $Producto, $this->transsportsbookApi, $BonusStatus, $BonusId, $BonusPlanId, $BonusAmount, $datos);
            $Balance = intval(($responseG->saldo) * 100);


            $this->transsportsbookApi = $responseG->transsportsbookApi;


            /*  Retornamos el mensaje satisfactorio  */

            $PKT = new SimpleXMLElement("<PKT></PKT>");

            $Result = $PKT->addChild('Result');
            $Result->addAttribute('Name', $this->method);
            $Result->addAttribute('Success', '1');

            $Returnset = $Result->addChild('Returnset');

            $Token = $Returnset->addChild('Token');
            $Token->addAttribute('Type', 'string');
            $Token->addAttribute('Value', $token);

            $ExtTransactionID = $Returnset->addChild('ExtTransactionID');
            $ExtTransactionID->addAttribute('Type', 'long');
            $ExtTransactionID->addAttribute('Value', "$responseG->transaccionId");

            $ExtTransactionID = $Returnset->addChild('AlreadyProcessed');
            $ExtTransactionID->addAttribute('Type', 'bool');
            $ExtTransactionID->addAttribute('Value', "false");

            $Balancex = $Returnset->addChild('Balance');
            $Balancex->addAttribute('Type', 'string');
            $Balancex->addAttribute('Value', "$Balance");


            $respuesta = $PKT->asXML();

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transsportsbookApi->setRespuestaCodigo("OK");
            $this->transsportsbookApi->setRespuesta(json_encode($respuesta));
            $TranssportsbookApiMySqlDAO = new TranssportsbookApiMySqlDAO();
            $TranssportsbookApiMySqlDAO->update($this->transsportsbookApi);
            $TranssportsbookApiMySqlDAO->getTransaction()->commit();

            return explode("\n", $PKT->asXML(), 2)[1];
        } catch (Exception $e) {
            if ($_ENV['debug']) {
                print_r($e);
            }


            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Convierte un error en una respuesta XML.
     *
     * Este metodo convierte un error en una respuesta XML con el código y mensaje
     * de error proporcionados.
     *
     * @param int    $code    Código de error.
     * @param string $message Mensaje de error.
     *
     * @return string Respuesta en formato XML con el código y mensaje de error.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";

        $PKT = new SimpleXMLElement("<PKT></PKT>");

        $Result = $PKT->addChild('Result');
        $Result->addAttribute('Name', $this->method);
        $Result->addAttribute('Success', '0');

        $Returnset = $Result->addChild('Returnset');
        $Proveedor = new Proveedor("", "ITN");


        if ($this->transsportsbookApi != null) {
            $tipo = $this->transsportsbookApi->getTipo();
        }

        switch ($code) {
            case 0:
                $codeProveedor = 1;
                $messageProveedor = "Ocurrio un error inesperado";
                break;


            case 10011:
                $codeProveedor = 1;
                $messageProveedor = "Token Expired";
                break;

            case 21:
                $codeProveedor = 90; //OK
                $messageProveedor = "Token Expired";
                break;

            case 10013:
                $codeProveedor = 7;
                $messageProveedor = "User not found";
                break;


            case 300000:
                $codeProveedor = 15;
                $messageProveedor = "No existen bonos ";
                break;

            case 300001:
                $codeProveedor = 16;
                $messageProveedor = "No existen bonos ";
                break;

            case 22:
                $codeProveedor = 7;
                $messageProveedor = "User not found";
                break;

            case 24:

                $codeProveedor = 2;
                $messageProveedor = "General Error. (" . $code . ")" . $message;

                break;

            case 20001:
                $codeProveedor = 10; //OK
                $messageProveedor = "Insufficient funds";
                break;

            case 0:
                $codeProveedor = 2;
                $messageProveedor = "General Error. (" . $code . ")" . $message;
                break;

            case 27:
                $codeProveedor = 2;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 28:
                $codeProveedor = 2;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 29:
                $codeProveedor = 2;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 300002:
                $codeProveedor = 2;
                $messageProveedor = "General Error. (" . $code . ")";
                break;


            case 300003:
                $codeProveedor = 3;
                $messageProveedor = "General Error. (" . $code . ")";
                break;


            case 10001:
                $codeProveedor = 0;
                $messageProveedor = "ok";


                if ($this->token != "" && $this->token != "-") {
                } else {
                }

                try {
                    $transaccionApi2 = new TranssportsbookApi("", $this->transsportsbookApi->getTransaccionId(), $Proveedor->getProveedorId());
                    $ItTransaccion = new ItTransaccion('', '', '', $this->transsportsbookApi->getTransaccionId());
                    $UsuarioMandante = new UsuarioMandante($transaccionApi2->usuarioId);

                    $Game = new Game();

                    try {
                        $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->getUsumandanteId());
                    } catch (Exception $e) {
                        if ($_ENV['debug']) {
                            print_r($e);
                        }
                        if ($e->getCode() == 21) {
                            $UsuarioToken = new UsuarioToken();
                            $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
                            $UsuarioToken->setCookie('0');
                            $UsuarioToken->setRequestId('0');
                            $UsuarioToken->setUsucreaId(0);
                            $UsuarioToken->setUsumodifId(0);
                            $UsuarioToken->setUsuarioId($UsuarioMandante->usumandanteId);
                            $token = $UsuarioToken->createToken();
                            $UsuarioToken->setToken($token);
                            $UsuarioToken->setSaldo(0);

                            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                            $UsuarioTokenMySqlDAO->insert($UsuarioToken);

                            $UsuarioTokenMySqlDAO->getTransaction()->commit();
                        }
                    }
                    $responseG = $Game->getBalance($UsuarioMandante, false);

                    $Balance = intval(($responseG->saldo) * 100);


                    $PKT = new SimpleXMLElement("<PKT></PKT>");

                    $Result = $PKT->addChild('Result');
                    $Result->addAttribute('Name', $this->method);
                    $Result->addAttribute('Success', '1');

                    $Returnset = $Result->addChild('Returnset');

                    $Token = $Returnset->addChild('Token');
                    $Token->addAttribute('Type', 'string');
                    $Token->addAttribute('Value', $UsuarioToken->getToken());

                    $Balancex = $Returnset->addChild('Balance');
                    $Balancex->addAttribute('Type', 'string');
                    $Balancex->addAttribute('Value', "$Balance");

                    $ExtTransactionID = $Returnset->addChild('ExtTransactionID');
                    $ExtTransactionID->addAttribute('Type', 'long');
                    $ExtTransactionID->addAttribute('Value', $ItTransaccion->itCuentatransId);

                    $ExtTransactionID = $Returnset->addChild('AlreadyProcessed');
                    $ExtTransactionID->addAttribute('Type', 'bool');
                    $ExtTransactionID->addAttribute('Value', "true");
                } catch (Exception $e) {
                    $codeProveedor = 2;
                    $messageProveedor = "General Error. (" . $code . ")";

                    //$codeProveedor=0;
                    $Error = $Returnset->addChild('Error');
                    $Error->addAttribute('Type', 'string');
                    $Error->addAttribute('Value', $messageProveedor);

                    $ErrorCode = $Returnset->addChild('ErrorCode');
                    $ErrorCode->addAttribute('Type', 'string');
                    $ErrorCode->addAttribute('Value', $codeProveedor);
                }

                break;

            case 10004:
                $codeProveedor = 2;
                $messageProveedor = "General Error. (" . $code . ")";
                break;

            case 10005:
                $codeProveedor = 2;
                $messageProveedor = "No existe la transaccion";
                break;

            case 10014:
                $codeProveedor = 2;
                $messageProveedor = "General Error. (" . $code . ")";
                break;


            case 50007:
                $codeProveedor = 95;
                $messageProveedor = $message;
                break;


            case 20003:
                $codeProveedor = 10;
                $messageProveedor = $message;
                break;


            case 100085:
                $codeProveedor = 10; //OK
                $messageProveedor = 'Usuario autoexcluido';
                break;


            case 20004:
                $codeProveedor = 10; //OK
                $messageProveedor = 'Usuario autoexcluido';
                break;


            case 100030:
                $codeProveedor = 10;
                $messageProveedor = $message;
                break;

            default:
                $codeProveedor = 99;
                $messageProveedor = "General Error. (" . $code . ")";
                break;
        }

        if ($code != 10001) {
            $Error = $Returnset->addChild('Error');
            $Error->addAttribute('Type', 'string');
            $Error->addAttribute('Value', $messageProveedor);

            $ErrorCode = $Returnset->addChild('ErrorCode');
            $ErrorCode->addAttribute('Type', 'string');
            $ErrorCode->addAttribute('Value', $codeProveedor);
        }


        if ($this->transsportsbookApi != null) {
            $Text = $PKT->asXML();
            if ($this->transsportsbookApi->transsportId == '') {
                $this->transsportsbookApi->transsportId = 0;
            }

            if ($this->transsportsbookApi->getUsuarioId() == '') {
                $this->transsportsbookApi->setUsuarioId(0);
            }

            if ($this->transsportsbookApi->getMandante() == '') {
                $this->transsportsbookApi->setMandante(0);
            }


            $this->transsportsbookApi->setTipo("R" . $this->transsportsbookApi->getTipo());
            $this->transsportsbookApi->setRespuestaCodigo("E_" . $code);
            $this->transsportsbookApi->setRespuesta($Text);
            $TranssportsbookApiMySqlDAO = new TranssportsbookApiMySqlDAO();
            $TranssportsbookApiMySqlDAO->insert($this->transsportsbookApi);
            $TranssportsbookApiMySqlDAO->getTransaction()->commit();
        }
        if ($_ENV['debug']) {
            print_r('TimeEND2 ');
            print_r(((time() - $this->timeInit) * 1000));
            print_r($e);
        }

        return explode("\n", $PKT->asXML(), 2)[1];
    }
}
