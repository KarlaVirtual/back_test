<?php

/**
 * Clase `Game`.
 *
 *  Este archivo contiene la definición de la clase `Game` y sus métodos relacionados con la integración
 *  de juegos de casino. Incluye dependencias necesarias y configuraciones específicas para el manejo
 *  de datos y servicios de terceros.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-27
 */

namespace Backend\integrations\casino;

use Exception;
use Backend\dto\Pais;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\FlujoCaja;
use Backend\dto\Proveedor;
use Backend\dto\PuntoVenta;
use Backend\dto\BonoInterno;
use Backend\dto\Clasificador;
use Backend\dto\Subproveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\TransjuegoLog;
use Backend\dto\UsuarioPerfil;
use Backend\dto\TransaccionApi;
use Backend\dto\TransjuegoInfo;
use Backend\dto\UsuarioSession;
use Backend\dto\MandanteDetalle;
use Backend\dto\ProductoDetalle;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\ProductoMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\UsuarioHistorial;
use Backend\dto\CategoriaMandante;
use Backend\dto\ProveedorMandante;
use Backend\mysql\FlujoCajaMySqlDAO;
use Backend\dto\SubproveedorMandante;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\TransaccionApiMandante;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\utils\RedisConnectionTrait;
use Backend\websocket\WebsocketUsuario;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransaccionApiMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\integrations\poker\EVENBETSERVICES;
use Backend\integrations\virtual\NSOFTSERVICES;
use Backend\integrations\virtual\XPRESSSERVICES;
use Backend\integrations\poker\ESAGAMINGSERVICES;
use Backend\integrations\virtual\MOBADOOSERVICES;
use Backend\mysql\TransaccionApiMandanteMySqlDAO;
use Backend\integrations\virtual\GOLDENRACESERVICES;

/**
 * Clase `Game`
 *
 * Representa la lógica y las operaciones relacionadas con la integración
 * de juegos de casino. Contiene propiedades y métodos para gestionar
 * la interacción con servicios de terceros y manejar datos específicos
 * del juego.
 */
class Game
{
    /**
     * Identificador del juego.
     *
     * @var string
     */
    private $gameid;

    /**
     * Modo de operación del juego (por ejemplo, demo o real).
     *
     * @var string
     */
    private $mode;

    /**
     * Proveedor del juego.
     *
     * @var string
     */
    private $provider;

    /**
     * Idioma del juego.
     *
     * @var string
     */
    private $lan;

    /**
     * Identificador del socio o partner.
     *
     * @var string
     */
    private $partnerid;

    /**
     * Token del usuario para autenticación.
     *
     * @var string
     */
    private $usuarioToken;

    /**
     * Indica si el juego se está ejecutando en un dispositivo móvil.
     *
     * @var boolean
     */
    private $isMobile;

    /**
     * Indica si el juego es un mini-juego.
     *
     * @var boolean
     */
    private $miniGame;

    /**
     * Modo mínimo del juego.
     *
     * @var integer
     */
    private $minimode;

    /**
     * Constructor de la clase `Game`.
     *
     * Inicializa las propiedades del juego con los valores proporcionados,
     * depurando los caracteres de las entradas y configurando valores predeterminados.
     *
     * @param string $gameid Identificador del juego.
     * @param string $mode Modo de operación del juego (demo o real).
     * @param string $provider Proveedor del juego.
     * @param string $lan Idioma del juego.
     * @param string $partnerid Identificador del socio o partner.
     * @param string $usuarioToken Token del usuario para autenticación.
     * @param string $isMobile Indica si el juego se ejecuta en un dispositivo móvil ("true" o "false").
     * @param boolean $miniGame Indica si el juego es un mini-juego.
     * @param integer $minimode Modo mínimo del juego.
     */
    public function __construct($gameid = "", $mode = "", $provider = "", $lan = "", $partnerid = "", $usuarioToken = "", $isMobile = "", $miniGame = false, $minimode = 0)
    {
        $_ENV["NOLOYALTY"] = 1;
        $this->gameid = DepurarCaracteres($gameid);
        $this->mode = DepurarCaracteres($mode);
        $this->provider = DepurarCaracteres($provider);
        $this->lan = DepurarCaracteres($lan);
        $this->usuarioToken = DepurarCaracteres($usuarioToken);
        $this->partnerid = DepurarCaracteres($partnerid);
        $this->isMobile = false;
        $this->miniGame = false;
        $this->minimode = $minimode;

        if ($isMobile == "true") {
            $this->isMobile = true;
        }
        if ($miniGame == "true") {
            $this->miniGame = true;
        }
    }

    /**
     * Obtiene la URL del juego basado en el identificador del juego y otros parámetros.
     *
     * @param string $gameGId Identificador del juego (opcional).
     *
     * @return mixed URL del juego si no hay errores, o `false` en caso de error.
     * @throws Exception Si ocurre algún error durante el proceso.
     */
    public function getURL($gameGId = '')
    {
        $isFun = true;


        if ($this->mode === "real") {
            $isFun = false;
        }

        if ($this->lan == "spa") {
            $this->lan = "es";
        }

        if ($this->lan == "eng") {
            $this->lan = "en";
        }
        if ($this->usuarioToken == "") {
            $isFun = true;
        }

        try {
            $Mandante = new Mandante($this->partnerid);

            if ($gameGId != '') {
                $Producto = new Producto($gameGId);

                $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $Mandante->mandante, '', '');
            } else {
                $ProductoMandante = new ProductoMandante("", "", $this->gameid);
            }


            if ($ProductoMandante->mandante != $Mandante->mandante) {
                throw new Exception("Juego no disponible ", "10000");
            }


            $Producto = new Producto($ProductoMandante->productoId);
            $Proveedor = new Proveedor($Producto->getProveedorId());
            $Subproveedor = new Subproveedor($Producto->getSubproveedorId());


            $ProveedorMandante = new ProveedorMandante($Proveedor->getProveedorId(), $Mandante->mandante);

            if ($Producto->estado == "I") {
                throw new Exception("Casino Inactivo", "20023");
            }

            if ($ProveedorMandante->estado == "I") {
                throw new Exception("Casino Inactivo", "20023");
            }


            $usuarioMandante = 0;


            if ($Mandante->propio == "S") {
                if ($Proveedor->getTipo() == 'CASINO') {
                    $ProdMandanteTipo = new ProdMandanteTipo("CASINO", $Mandante->mandante);

                    if ($ProdMandanteTipo->estado == "I") {
                        throw new Exception("Casino Inactivo", "20023");
                    }
                    if ($ProdMandanteTipo->contingencia == "A") {
                        throw new Exception("Casino en contingencia", "20024");
                    }
                }


                $ConfigurationEnvironment = new ConfigurationEnvironment();
                if ($ConfigurationEnvironment->isDevelopment()) {
                    if ($Proveedor->getTipo() == 'LIVECASINO') {
                    }
                }


                if ($this->usuarioToken != '') {
                    $UsuarioTokenSite = new UsuarioToken($this->usuarioToken, '0');
                    $UsuarioMandante = new UsuarioMandante($UsuarioTokenSite->getUsuarioId());


                    if ($UsuarioMandante->getMandante() != $ProductoMandante->mandante) {
                        throw new Exception("Juego no disponible ", "10000");
                    }

                    $usuarioMandante = $UsuarioTokenSite->getUsuarioId();

                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    if ($Usuario->contingenciaCasino == "A" && $Subproveedor->getTipo() == 'CASINO') {
                        throw new Exception("Usuario Contingencia", "20024");
                    }

                    if ($Usuario->contingenciaCasvivo == "A" && $Subproveedor->getTipo() == 'LIVECASINO') {
                        throw new Exception("Usuario Contingencia", "20024");
                    }
                    if ($Usuario->contingenciaVirtuales == "A" && $Subproveedor->getTipo() == 'VIRTUAL') {
                        throw new Exception("Usuario Contingencia", "20024");
                    }
                } else {
                    if (($this->usuarioToken == '' && $this->miniGame == false)) {
                        throw new Exception("Juego no disponible ", "10000");
                    }
                }
            } else {
                $ProdMandanteTipo = new ProdMandanteTipo('CASINO', $Mandante->mandante);

                if ($ProdMandanteTipo->tipoIntegracion == "0") {
                    $ProductoMandante = new ProductoMandante("", "", $this->gameid);

                    $UsuarioTokenSite = new UsuarioToken($this->usuarioToken, '0');
                    $UsuarioMandante = new UsuarioMandante($UsuarioTokenSite->getUsuarioId());

                    if ($UsuarioMandante->getMandante() != $ProductoMandante->mandante) {
                        throw new Exception("Juego no disponible ", "10000");
                    }

                    $Producto = new Producto($ProductoMandante->productoId);

                    $Proveedor = new Proveedor($Producto->getProveedorId());

                    $usuarioMandante = $UsuarioTokenSite->getUsuarioId();
                }

                if ($ProdMandanteTipo->tipoIntegracion == "1" || $ProdMandanteTipo->tipoIntegracion == "2") {
                    //Detalles del partner
                    $urlApi = $ProdMandanteTipo->urlApi;
                    $siteId = $ProdMandanteTipo->siteId;
                    $siteKey = $ProdMandanteTipo->siteKey;

                    $method = "/authenticate";
                    $data = array(
                        //"site" => $ProdMandanteTipo->siteId,
                        "sign" => $ProdMandanteTipo->siteKey,
                        "token" => $this->usuarioToken
                    );

                    $data = array(
                        "sign" => $ProdMandanteTipo->siteKey,
                        "token" => $this->usuarioToken
                    );

                    $result = $Mandante->sendRequest($ProdMandanteTipo->urlApi . "/authenticate", "POST", $data);

                    $result = json_decode(json_encode($result));

                    if ($result == "") {
                        throw new Exception("No coinciden ", "50001");
                    }
                    $error = strtolower($result->error);
                    $code = strtolower($result->code);


                    if ($error == "" || $error == 'true' || $error == '1') {
                        throw new Exception("No coinciden ", "50001");
                    }

                    $userid = $result->player->userid;
                    $balance = $result->player->balance;
                    $name = $result->player->name;
                    $lastname = $result->player->lastname;
                    $currency = $result->player->currency;
                    $dirip = $result->player->ip;
                    $country = $result->player->country;
                    $email = $result->player->email;

                    if ($userid == "" || !is_numeric($userid)) {
                        throw new Exception("No coinciden ", "50001");
                    }

                    if ($balance == "") {
                        throw new Exception("No coinciden ", "50001");
                    }

                    if ($name == "") {
                        throw new Exception("No coinciden ", "50001");
                    }

                    if ($currency == "") {
                        throw new Exception("No coinciden ", "50001");
                    }

                    if ($country == "") {
                        throw new Exception("No coinciden ", "50001");
                    }


                    try {
                        $token = $this->usuarioToken;
                        $UsuarioMandante = new UsuarioMandante("", $userid, $Mandante->mandante);
                        $UsuarioMandante->tokenExterno = $token;
                        $UsuarioMandanteMySqlDAO = new UsuarioMandanteMySqlDAO();
                        $UsuarioMandanteMySqlDAO->update($UsuarioMandante);

                        $UsuarioMandanteMySqlDAO->getTransaction()->getConnection()->commit();
                        // $UsuarioToken = new UsuarioToken("",'0',$UsuarioMandante->getUsumandanteId());
                    } catch (Exception $e) {
                        if ($e->getCode() == 22) {
                            $dir_ip = '';

                            $Pais = new Pais("", strtoupper($country));

                            $UsuarioMandante = new UsuarioMandante();

                            $UsuarioMandante->mandante = $Mandante->mandante;
                            $UsuarioMandante->dirIp = $dir_ip;
                            $UsuarioMandante->nombres = $name;
                            $UsuarioMandante->apellidos = $lastname;
                            $UsuarioMandante->estado = 'A';
                            $UsuarioMandante->email = $email;
                            $UsuarioMandante->moneda = $currency;
                            $UsuarioMandante->paisId = $Pais->paisId;
                            $UsuarioMandante->saldo = $balance;
                            $UsuarioMandante->usuarioMandante = $userid;
                            $UsuarioMandante->usucreaId = 0;
                            $UsuarioMandante->usumodifId = 0;
                            $UsuarioMandante->tokenExterno = $token;
                            $UsuarioMandante->tokenInterno = '';
                            $UsuarioMandante->propio = 'N';
                            $UsuarioMandanteMySqlDAO = new UsuarioMandanteMySqlDAO();
                            $usuario_id = $UsuarioMandanteMySqlDAO->insert($UsuarioMandante);

                            $UsuarioMandanteMySqlDAO->getTransaction()->getConnection()->commit();
                        }
                    }

                    $usuarioMandante = $UsuarioMandante->getUsumandanteId();
                }
            }

            try {
                $SubproveedorMandantePais = new SubproveedorMandantePais("", $Subproveedor->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
                $isTest = $SubproveedorMandantePais->getUsuariosPrueba();
                $isTestUser = $Usuario->test;
            } catch (Exception $e) {
                $isTest = 'N';
                $isTestUser = 'N';
            }

            if ($isTestUser == 'N') {
                if ($isTest == 'S') {
                    throw new Exception("Juego no disponible ", "10000");
                }
            }
            if ($Subproveedor->getEstado() == "I") {
                throw new Exception("Juego no disponible ", "10000");
            }

            $SubproveedorMandante = new  SubproveedorMandante($Producto->subproveedorId, $UsuarioMandante->mandante);
            if ($SubproveedorMandante->estado == "I") {
                throw new Exception("Juego no disponible ", "10000");
            }

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            if ($SubproveedorMandantePais->getEstado() == "I") {
                throw new Exception("Juego no disponible ", "10000");
            }

            if ($ProductoMandante->estado == "I") {
                throw new Exception("Juego no disponible ", "10000");
            }

            if ($ProductoMandante->habilitacion == "I") {
                throw new Exception("Juego no disponible ", "10000");
            }


            if (($usuarioMandante == 65395) && (in_array($Proveedor->getProveedorId(), array('12', '68', '67')) || in_array($Producto->getProductoId(), array('5734', '5738', '5741', '5744', '5747', '5768')))) {
                throw new Exception("Juego no disponible ", "10000");
            }

            if ($UsuarioMandante != null) {
                if ($UsuarioMandante->mandante == 1) {
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                    $this->lan = $Usuario->idioma;
                    $this->lan = strtolower($this->lan);
                }
            }

            if ($_REQUEST['isDebug'] == '1') {
                print_r($ProductoMandante);
                print_r($Producto);
                print_r($Proveedor);
            }

            switch ($Proveedor->getAbreviado()) {
                case "IGP":
                    $IGPSERVICES = new IGPSERVICES();
                    $response = $IGPSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $usuarioMandante);
                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }
                    break;

                case "EZZG":
                    $EZUGISERVICES = new EZUGISERVICES();
                    $response = $EZUGISERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $usuarioMandante, $Producto);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }
                    break;

                case "PTG":
                    $SALSASERVICES = new SALSASERVICES();

                    $response = $SALSASERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }
                    break;


                case "INB":

                    if ($this->lan == "es") {
                        $this->lan = "es_ES";
                    }

                    $INBETSERVICES = new INBETSERVICES();
                    $ProductoDetalle = new ProductoDetalle("", $Producto->getProductoId(), "GAMEID");
                    $response = $INBETSERVICES->getGame($ProductoDetalle->getPValue(), $this->lan, $isFun, $this->usuarioToken, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }
                    break;

                case "WMT":
                    $WORLDMATCHSERVICES = new WORLDMATCHSERVICES();

                    $response = $WORLDMATCHSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "GDR":
                    $GOLDENRACESERVICES = new GOLDENRACESERVICES();


                    $response = $GOLDENRACESERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "VGT":
                    $VIRTUALGENERATIONSERVICES = new VIRTUALGENERATIONSERVICES();
                    $response = $VIRTUALGENERATIONSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $this->isMobile, $usuarioMandante, $Mandante);
                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "ITN":

                    try {
                        $UsuarioTokenSite = new UsuarioToken($this->usuarioToken, '0');

                        $skinItn = '';
                        $skinJsITN = '';
                        $walletCode = '';

                        try {
                            $UsuarioMandante = new UsuarioMandante($UsuarioTokenSite->getUsuarioId());
                            $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
                            $Mandante = new Mandante($UsuarioMandante->getMandante());
                            $PaisMandante = new \Backend\dto\PaisMandante('', $Usuario->mandante, $Usuario->paisId);

                            if ($PaisMandante->estado != 'A') {
                                throw new Exception("No existe Token", "21");
                            }

                            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $Usuario->mandante, $Usuario->paisId);
                            $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

                            $skinItn = $Credentials->SKIN_ID2;
                            $skinJsITN = $Credentials->SKIN_JS;
                            $walletCode = $Credentials->WALLET_CODE;

                        } catch (Exception $e) {
                        }

                        $tokenSB = $Usuario->tokenItainment;
                        $virtualSportId = 35;

                        if (true) {
                            $Proveedor = new Proveedor('', 'ITN');
                            $ProductoDetalle = new ProductoDetalle('', $Producto->getProductoId(), "GAMEID");
                            $virtualSportId = $ProductoDetalle->pValue;
                            try {
                                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->usumandanteId);
                            } catch (Exception $e) {
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

                            $tokenSB = $UsuarioToken->getToken();
                        }

                        $response = array(
                            "error" => false,
                            "proveedor" => 'ITN',
                            "token" => $tokenSB,
                            "page" => $Producto->externoId,
                            "country" => $Usuario->paisId,
                            "skinItn" => $skinItn,
                            "skinJsITN" => $skinJsITN,
                            "walletCode" => $walletCode,
                            "virtualSportId" => $virtualSportId,
                        );

                        return json_decode(json_encode($response));
                    } catch (Exception $e) {
                        // print_r($e);
                    }

                    break;

                case "MGMG":

                    $MICROGAMINGSERVICES = new MICROGAMINGSERVICES();
                    $ProductoDetalle = new ProductoDetalle("", $Producto->getProductoId(), "GAMEID");

                    $response = $MICROGAMINGSERVICES->getGame($ProductoDetalle->getPValue(), $this->lan, $isFun, $this->usuarioToken, $usuarioMandante);
                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "JOIN":
                    $JOINSERVICES = new JOINSERVICES();

                    $response = $JOINSERVICES->getGamePage($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "JOINPOKER":
                    $JOINSERVICES = new JOINSERVICES();

                    $response = $JOINSERVICES->getGame2($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "ENPH":
                    $ENDORPHINASERVICES = new ENDORPHINASERVICES();

                    $response = $ENDORPHINASERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "EVERYMATRIX":
                    $EVERYMATRIXSERVICES = new EVERYMATRIXSERVICES();

                    $response = $EVERYMATRIXSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "RAW":
                    $rawServices = new RAWSERVICES();
                    $response = $rawServices->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $this->isMobile, $usuarioMandante);
                    
                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;
                
                case "RFRANCO":
                    $rfrancoServices = new RFRANCOSERVICES();
                    $response = $rfrancoServices->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $usuarioMandante);
                    
                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;


                case "BTX":

                    $BETIXONSERVICES = new BETIXONSERVICES();

                    $response = $BETIXONSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;


                case "ORYX":

                    $ORYXSERVICES = new ORYXSERVICES();

                    $response = $ORYXSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "BRAGG":

                    $BRAGGSERVICES = new BRAGGSERVICES();

                    $response = $BRAGGSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "LAMBDA":

                    $LAMBDASERVICES = new LAMBDASERVICES();

                    $response = $LAMBDASERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "ZENITH":

                    $ZENITHSERVICES = new ZENITHSERVICES();

                    $response = $ZENITHSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "ONLYPLAY":

                    $ONLYPLAYSERVICES = new ONLYPLAYSERVICES();

                    $response = $ONLYPLAYSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;


                case "QTECH":

                    $QTECHSERVICES = new QTECHSERVICES();

                    $response = $QTECHSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "PLAYNGO":

                    $PLAYNGOSERVICES = new PLAYNGOSERVICES();

                    if ($this->miniGame == true) {
                        $response = $PLAYNGOSERVICES->getGamemini($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante, $this->partnerid);
                    } else {
                        $response = $PLAYNGOSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);
                    }

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "TVBET":


                    $TVBETSERVICES = new TVBETSERVICES();

                    $response = $TVBETSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "HABANERO":


                    $HABANEROSERVICES = new HABANEROSERVICES();

                    $response = $HABANEROSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "BOSS":

                    $BOSSSERVICES = new BOSSSERVICES();

                    $response = $BOSSSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "ESAGAMING":
                    $ESAGAMINGSERVICES = new ESAGAMINGSERVICES();
                    $response = $ESAGAMINGSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "EVENBET":

                    $EVENBETSERVICES = new EVENBETSERVICES();
                    $response = $EVENBETSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;


                case "NSOFT":

                    $NSOFTSERVICES = new NSOFTSERVICES();

                    $response = $NSOFTSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "GAMEART":


                    $GAMEARTSERVICES = new GAMEARTSERVICES();
                    $response = $GAMEARTSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;
                case "AMIGOGAMING":


                    $AMIGOGAMINGSERVICES = new AMIGOGAMINGSERVICES();
                    $response = $AMIGOGAMINGSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "IESGAMESCASINO":


                    $IESGAMESCASINOSERVICES = new IESGAMESCASINOSERVICES();
                    $response = $IESGAMESCASINOSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;
                case "IESGAMES":


                    $IESGAMESCASINOSERVICES = new IESGAMESCASINOSERVICES();
                    $response = $IESGAMESCASINOSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "ONETOUCH":


                    $ONETOUCHSERVICES = new ONETOUCHSERVICES();
                    $response = $ONETOUCHSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "E2E":
                    $END2ENDSERVICES = new END2ENDSERVICES();
                    $response = $END2ENDSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;
                case "CTGAMING":

                    $CTGAMINGSERVICES = new CTGAMINGSERVICES();

                    $response = $CTGAMINGSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);


                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;
                case "ISOFTBET":

                    $ISOFTBETSERVICES = new ISOFTBETSERVICES();

                    $response = $ISOFTBETSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }


                    break;
                case "BOOONGO":

                    $BOOONGOSERVICES = new BOOONGOSERVICES();

                    $response = $BOOONGOSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "PLATIPUS":

                    $PLATIPUSSERVICES = new PLATIPUSSERVICES();

                    $ProductoDetalle = new ProductoDetalle("", $Producto->getProductoId(), "GAMEID");

                    $response = $PLATIPUSSERVICES->getGame($ProductoDetalle->getPValue(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "VIBRAGAMING":

                    $VIBRAGAMINGSERVICES = new VIBRAGAMINGSERVICES();

                    $response = $VIBRAGAMINGSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;
                case "PARIPLAY":

                    $PARIPLAYSERVICES = new PARIPLAYSERVICES();

                    $response = $PARIPLAYSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);


                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;
                case "PRAGMATIC":

                    $this->miniGame = true;
                    if ($this->miniGame == true) {
                        $userToken = $this->usuarioToken;
                        $product = new Producto($Producto->getProductoId());
                        $providers = new Proveedor($product->proveedorId);
                        $provider = $providers->abreviado;
                        $miniGameUrl = $ConfigurationEnvironment->isDevelopment() ? "https://apidev.virtualsoft.tech/casino/minigames/play/?token={$userToken}_{$provider}" : "https://casino.virtualsoft.tech/minigames/play/?token={$userToken}_{$provider}";
                    }

                    $PRAGMATICSERVICES = new PRAGMATICSERVICES();

                    $response = $PRAGMATICSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante, $this->miniGame, $miniGameUrl, $this->minimode);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "BETGAMESTV":
                    $BETGAMESTVSERVICES = new BETGAMESTVSERVICES();

                    $response = $BETGAMESTVSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }
                    break;

                case "SPINOMENAL":
                    $SPINOMENALSERVICES = new SPINOMENALSERVICES();

                    $response = $SPINOMENALSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }
                    break;

                case "VIVOGAMING":

                    if ($this->usuarioToken == '') {
                        $isFun = true;
                    }

                    $VIVOGAMINGSERVICES = new VIVOGAMINGSERVICES();

                    $response = $VIVOGAMINGSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;


                case "REDRAKE":
                    $REDRAKESERVICES = new REDRAKESERVICES();

                    $response = $REDRAKESERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;


                case "EVOPLAY":

                    $EVOPLAYSERVICES = new EVOPLAYSERVICES();

                    $response = $EVOPLAYSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;


                case "XPRESS":

                    $XPRESSSERVICES = new XPRESSSERVICES();

                    $response = $XPRESSSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $usuarioMandante, $this->isMobile, $ProductoMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "EAGAMING":

                    $EAGAMINGSERVICES = new EAGAMINGSERVICES();

                    $response = $EAGAMINGSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;


                case "FAZI":

                    $FAZISERVICES = new FAZISERVICES();

                    $response = $FAZISERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;


                case "APOLLO":
                    $APOLLOSERVICES = new APOLLOSERVICES();

                    $response = $APOLLOSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "AVIATRIX":
                    $AVIATRIXSERVICES = new AVIATRIXSERVICES();

                    $response = $AVIATRIXSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "TADAGAMING":
                    $TADAGAMINGSERVICES = new TADAGAMINGSERVICES();

                    $response = $TADAGAMINGSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "EXPANSE":
                    $EXPANSESERVICES = new EXPANSESERVICES();

                    $response = $EXPANSESERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "MERKUR":
                    $MERKURSERVICES = new MERKURSERVICES();

                    $response = $MERKURSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;


                case "TOMHORN":
                    $TOMHORNSERVICES = new TOMHORNSERVICES();

                    $response = $TOMHORNSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;


                case "PLAYSON":

                    $PLAYSONSERVICES = new PLAYSONSERVICES();

                    $response = $PLAYSONSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;


                case "CALETA":


                    $CALETASERVICES = new CALETASERVICES();
                    $response = $CALETASERVICES->getGame($Producto->getExternoId(), '', $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "UNIVERSALS":
                    if ($UsuarioMandante->usuarioMandante == '2366930') {
                        throw new Exception("Juego no disponible ", "10000");
                    }
                    $UNIVERSALSOFTSERVICES = new UNIVERSALSOFTSERVICES();
                    $respuesta = $UNIVERSALSOFTSERVICES->API_crear_usuario($UsuarioMandante);

                    $resp = json_decode($respuesta->response);

                    if ($_REQUEST['isDebug'] == '1') {
                        print_r('isDebug');
                        print_r($resp);
                    }
                    if ($resp->Id == 1) {
                        $response = $UNIVERSALSOFTSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $this->isMobile, $usuarioMandante);
                        if (!$response->error) {
                            return $response->response;
                        } else {
                            return false;
                        }
                    } else {
                        throw new Exception("Ip no Encontrado", "100013");
                    }


                    break;

                case "MASCOT":

                    $MASCOTSERVICES = new MASCOTSERVICES();

                    $response = $MASCOTSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }


                    break;

                case "WAZDAN":

                    $WAZDANSERVICES = new WAZDANSERVICES();

                    $response = $WAZDANSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }


                    break;

                case "MANCALA":

                    $MANCALASERVICES = new MANCALASERVICES();

                    $response = $MANCALASERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "SPRIBE":

                    $SPRIBESERVICES = new SPRIBESERVICES();

                    $response = $SPRIBESERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "SKYWIND":

                    $SKYWINDSERVICES = new SKYWINDSERVICES();

                    $response = $SKYWINDSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "SWINTT":

                    $SWINTTSERVICES = new SWINTTSERVICES();

                    $response = $SWINTTSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "SMARTSOFT":

                    $SMARTSOFTSERVICES = new SMARTSOFTSERVICES();
                    $UsuarioMandante = new UsuarioMandante($usuarioMandante);

                    $response = $SMARTSOFTSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }


                    break;

                case "BOOMING":

                    $BOOMINGSERVICES = new BOOMINGSERVICES();

                    $response = $BOOMINGSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "THUNDERKICK":

                    $THUNDERKICKSERVICES = new THUNDERKICKSERVICES();

                    $response = $THUNDERKICKSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "REVOLVER":

                    $REVOLVERSERVICES = new REVOLVERSERVICES();

                    $response = $REVOLVERSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;


                case "WAC":

                    $WACSERVICES = new WACSERVICES();

                    $response = $WACSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;


                case "URGENTGAMES":

                    $URGENTGAMESSERVICES = new URGENTGAMESSERVICES();

                    $response = $URGENTGAMESSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "BETERLIVE":
                    $BETERLIVESERVICES = new BETERLIVESERVICES();

                    $response = $BETERLIVESERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;
                case "RUBYPLAY":
                    $RUBYPLAYSERVICES = new RUBYPLAYSERVICES();
                    $response = $RUBYPLAYSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $usuarioMandante);
                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }
                    break;

                case "GAMESGLOBAL":
                    $GAMESGLOBALSERVICES = new GAMESGLOBALSERVICES();
                    $response = $GAMESGLOBALSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $usuarioMandante);
                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }
                    break;

                case "7777GAMING":
                    $G7777GAMINGSERVICES = new G7777GAMINGSERVICES();
                    $response = $G7777GAMINGSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $usuarioMandante);
                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }
                    break;

                case "21VIRAL":
                    $G21VIRALSERVICES = new G21VIRALSERVICES();
                    $response = $G21VIRALSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $usuarioMandante);
                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }
                    break;

                case "EVOLUTIONOSS":
                    $EVOLUTIONOSSSERVICES = new EVOLUTIONOSSSERVICES();
                    $response = $EVOLUTIONOSSSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);
                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }
                    break;

                case "KAGAMING":
                    $KAGAMINGSERVICES = new KAGAMINGSERVICES();
                    $response = $KAGAMINGSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);
                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }
                    break;

                case "BELATRA":
                    $BELATRASERVICES = new BELATRASERVICES();
                    $response = $BELATRASERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);
                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }
                    break;

                case "EGT":
                    $EGTSERVICES = new EGTSERVICES();
                    $response = $EGTSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);
                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }
                    break;

                case "FANTASY":
                    $FANTASYSERVICES = new FANTASYSERVICES();
                    $response = $FANTASYSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);
                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }
                    break;

                case "PASCAL":

                    $PASCALSERVICES = new PASCALSERVICES();

                    $response = $PASCALSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }


                    break;

                case "AMUSNET":

                    $AMUSNETSERVICES = new AMUSNETSERVICES();

                    $response = $AMUSNETSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;


                case "ELINMEJORABLE":

                    $ELINMEJORABLESERVICES = new ELINMEJORABLESERVICES();

                    $response = $ELINMEJORABLESERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "GALAXSYS":

                    $GALAXSYSSERVICES = new GALAXSYSSERVICES();

                    $response = $GALAXSYSSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "AIRDICE":
                    $AIRDICESERVICES = new AIRDICESERVICES();
                    $response = $AIRDICESERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $usuarioMandante);
                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }
                    break;


                case "POPOK":

                    $POPOKSERVICES = new POPOKSERVICES();

                    $response = $POPOKSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;


                case "PLAYTECH":


                    $PLAYTECHSERVICES = new PLAYTECHSERVICES();
                    $response = $PLAYTECHSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;
                case "ZEUSPLAY":

                    $ZEUSPLAYSERVICES = new ZEUSPLAYSERVICES();

                    $response = $ZEUSPLAYSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }


                    break;


                case "MOBADOO":

                    $MOBADOOSERVICES = new MOBADOOSERVICES();
                    $response = $MOBADOOSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);

                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;
                case "IMOON":
                    $IMOONSERVICES = new IMOONSERVICES();
                    $response = $IMOONSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $usuarioMandante);
                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }
                    break;
                case "SOFTSWISS":
                    $SOFTSWISSSERVICES = new SOFTSWISSSERVICES();
                    $response = $SOFTSWISSSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $usuarioMandante);
                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }
                    break;
                case "PGSOFT":
                    $PGSOFTSERVICES = new PGSOFTSERVICES();
                    $response = $PGSOFTSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);
                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }
                    break;
                case "GENERAL":
                    $GENERALSERVICES = new GENERALSERVICES();
                    $response = $GENERALSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);
                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }
                    break;
                case "PANILOTTERY":
                    $PANILOTERYSERVICES = new PANILOTTERYSERVICES();
                    $response = $PANILOTERYSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);
                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }
                    break;
                case "AADVARK":
                    $AADVARKSERVICES = new AADVARKSERVICES();
                    $response = $AADVARKSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);
                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }
                    break;
                case "SISVENPROL":
                    $SISVENPROLSERVICES = new SISVENPROLSERVICES();
                    $response = $SISVENPROLSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId(), $this->isMobile, $usuarioMandante);
                    if (!$response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }
                    break;
            }
        } catch (Exception $e) {
            if ($_REQUEST['isDebug'] == '1') {
                print_r($e);
            }
            throw $e;
        }
    }

    /**
     * Autentica un usuario mandante y devuelve información del usuario.
     *
     * @param object $UsuarioMandante Objeto que contiene información del usuario mandante.
     *
     * @return object Objeto con información del usuario autenticado, incluyendo saldo, moneda, país, etc.
     * @throws Exception Si ocurre un error durante la autenticación o validación de datos.
     */
    public function autenticate($UsuarioMandante = "")
    {
        try {
            $Mandante = new Mandante($UsuarioMandante->mandante);

            $ConfigurationEnvironment = new ConfigurationEnvironment();


            $respuesta = json_decode(json_encode(array(
                "usuarioId" => "",
                "moneda" => "",
                "usuario" => "",
                "paisId" => "",
                "paisIso2" => "",
                "idioma" => "",
                "saldo" => ""
            )));

            if ($Mandante->propio == "S") {
                if ($ConfigurationEnvironment->isDevelopment() || $UsuarioMandante->mandante == 19) {
                    $Clasificador = new Clasificador("", "EXCPRODUCT");

                    try {
                        $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioMandante->getUsuarioMandante(), "A", $Clasificador->getClasificadorId(), '3');

                        if ($UsuarioConfiguracion->getProductoId() != "") {
                            throw new Exception("EXCPRODUCT", "20004");
                        }
                    } catch (Exception $e) {
                        if ($e->getCode() != 46) {
                            throw $e;
                        }
                    }
                }


                // CHECK SESSION //
                if ($ConfigurationEnvironment->isDevelopment() || $UsuarioMandante->mandante == 18) {
                    try {
                        $UsuarioToken2 = new UsuarioToken('', '0', $UsuarioMandante->usumandanteId);
                    } catch (Exception $ex) {
                        if ($ex->getCode() == 21) throw $ex;
                    }
                }


                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                $Registro = new Registro('', $UsuarioMandante->usuarioMandante);
                $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);
                $Pais = new Pais($Usuario->paisId);

                if ($Usuario->estado != "A") {
                    throw new Exception("Usuario Inactivo", "20003");
                }

                if ($Usuario->contingencia == "A") {
                    throw new Exception("Usuario Contingencia", "20024");
                }


                switch ($UsuarioPerfil->getPerfilId()) {
                    case "USUONLINE":
                        $Balance = $Usuario->getBalance();
                        break;

                    case "MAQUINAANONIMA":

                        $Balance = $Usuario->getBalance();
                        break;

                    case "PUNTOVENTA":
                        $PuntoVenta = new PuntoVenta("", $UsuarioMandante->usuarioMandante);

                        $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                        $SaldoJuego = $PuntoVenta->getCreditosBase();

                        $Balance = $SaldoJuego;
                        break;

                    case "CAJERO":
                        $PuntoVenta = new PuntoVenta("", $Usuario->puntoventaId);

                        $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                        $SaldoJuego = $PuntoVenta->getCreditosBase();

                        $Balance = $SaldoJuego;
                        break;
                }

                $respuesta->usuarioId = $UsuarioMandante->usumandanteId;
                $respuesta->moneda = $UsuarioMandante->moneda;
                $respuesta->usuario = "Usuario" . $UsuarioMandante->usumandanteId;
                $respuesta->paisId = $Pais->paisId;
                $respuesta->paisIso2 = $Pais->iso;
                $respuesta->idioma = 'ES';
                $respuesta->saldo = $Balance;

                $respuesta->documento = $Registro->cedula;
                $respuesta->tipoDocumento = $Registro->tipoDoc;
                $respuesta->email = $Usuario->login;
            } else {
                $Pais = new Pais($UsuarioMandante->paisId);
                $ProdMandanteTipo = new ProdMandanteTipo("CASINO", $Mandante->mandante);
                $data = array(
                    "sign" => $ProdMandanteTipo->siteKey,
                    "token" => $UsuarioMandante->tokenExterno
                );

                $result = $Mandante->sendRequest($ProdMandanteTipo->urlApi . "/authenticate", "POST", $data);

                $result = json_decode(json_encode($result));

                if ($result == "") {
                    throw new Exception("No coinciden ", "50001");
                }


                $error = strtolower($result->error);
                $code = strtolower($result->code);

                if ($error == "" || $error == '1') {
                    $this->convertErrorMandante('M' . $code, "Error en mandante");
                }

                $userid = $result->player->userid;
                $balance = $result->player->balance;
                $name = $result->player->name;
                $lastname = $result->player->lastname;
                $currency = $result->player->currency;
                $dirip = $result->player->ip;
                $country = $result->player->country;
                $email = $result->player->email;
                $language = $result->player->language;

                if ($userid == "" || !is_numeric($userid)) {
                    throw new Exception("No coinciden ", "50001");
                }

                if ($balance == "") {
                    throw new Exception("No coinciden ", "50001");
                }

                if ($name == "") {
                    throw new Exception("No coinciden ", "50001");
                }

                if ($currency == "") {
                    throw new Exception("No coinciden ", "50001");
                }

                if ($country == "") {
                    throw new Exception("No coinciden ", "50001");
                }

                $respuesta->usuarioId = $UsuarioMandante->usumandanteId;
                $respuesta->moneda = $UsuarioMandante->moneda;
                $respuesta->usuario = "Usuario" . $UsuarioMandante->usumandanteId;
                $respuesta->paisId = $Pais->paisId;
                $respuesta->paisIso2 = $Pais->iso;
                $respuesta->idioma = 'ES';
                $respuesta->documento = '';
                $respuesta->tipoDocumento = '';
                $respuesta->saldo = $balance;
                $respuesta->email = '';
            }

            return $respuesta;
        } catch (Exception $e) {
            if ($_ENV['debug']) {
                print_r($e);
            }
            if ($_ENV["connectionGlobal"] != null && $_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"]->isBeginTransaction == 2) {
                ($_ENV["connectionGlobal"])->rollBack();
            }

            if ($_ENV["connectionGlobal"] != null && $_ENV["connectionGlobal"] != '') {
                if (($_ENV["connectionGlobal"])->inTransaction()) {
                    ($_ENV["connectionGlobal"])->rollBack();
                }
            }
            throw $e;
        }
    }

    /**
     * Obtiene el balance de un usuario mandante.
     *
     * @param object $UsuarioMandante Objeto que contiene información del usuario mandante.
     *
     * @return object Objeto con información del balance del usuario, incluyendo usuarioId, moneda, usuario y saldo.
     * @throws Exception Si ocurre un error durante la obtención del balance o validación de datos.
     */
    public function getBalance($UsuarioMandante = "", $isRollback = false)
    {
        try {
            $Mandante = new Mandante($UsuarioMandante->mandante);

            $ConfigurationEnvironment = new ConfigurationEnvironment();


            $respuesta = json_decode(json_encode(array(
                "usuarioId" => "",
                "moneda" => "",
                "usuario" => "",
                "saldo" => ""
            )));

            if ($Mandante->propio == "S") {
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);
                $Pais = new Pais($Usuario->paisId);


                if ($ConfigurationEnvironment->isDevelopment() || $UsuarioMandante->mandante == 19) {
                    $Clasificador = new Clasificador("", "EXCPRODUCT");

                    try {
                        $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioMandante->getUsuarioMandante(), "A", $Clasificador->getClasificadorId(), '3');

                        if ($UsuarioConfiguracion->getProductoId() != "") {
                            throw new Exception("EXCPRODUCT", "20004");
                        }
                    } catch (Exception $e) {
                        if ($e->getCode() != 46) {
                            throw $e;
                        }
                    }
                }


                if (($ConfigurationEnvironment->isDevelopment() || $UsuarioMandante->mandante == 18) && !$isRollback) {
                    try {
                        $UsuarioToken2 = new UsuarioToken('', '0', $UsuarioMandante->usumandanteId);
                    } catch (Exception $ex) {
                        if ($ex->getCode() == 21) throw $ex;
                    }
                }


                if ($Usuario->estado != "A") {
                    throw new Exception("Usuario Inactivo", "20003");
                }

                switch ($UsuarioPerfil->getPerfilId()) {
                    case "USUONLINE":
                        $Balance = $Usuario->getBalance();
                        break;

                    case "MAQUINAANONIMA":


                        $Balance = $Usuario->getBalance();

                        break;

                    case "PUNTOVENTA":
                        $PuntoVenta = new PuntoVenta("", $UsuarioMandante->usuarioMandante);

                        $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                        $SaldoJuego = $PuntoVenta->getCreditosBase();

                        $Balance = $SaldoJuego;
                        break;

                    case "CAJERO":
                        $PuntoVenta = new PuntoVenta("", $Usuario->puntoventaId);

                        $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                        $SaldoJuego = $PuntoVenta->getCreditosBase();

                        $Balance = $SaldoJuego;
                        break;
                }

                $respuesta->usuarioId = $UsuarioMandante->usumandanteId;
                $respuesta->moneda = $UsuarioMandante->moneda;
                $respuesta->usuario = "Usuario" . $UsuarioMandante->usumandanteId;
                $respuesta->saldo = $Balance;
            } else {
                $Pais = new Pais($UsuarioMandante->paisId);
                $ProdMandanteTipo = new ProdMandanteTipo("CASINO", $Mandante->mandante);

                $data = array(
                    "sign" => $ProdMandanteTipo->siteKey,
                    "token" => $UsuarioMandante->tokenExterno
                );

                $result = $Mandante->sendRequest($ProdMandanteTipo->urlApi . "/balance", "POST", $data);

                $result = json_decode(json_encode($result));

                if ($result == "") {
                    throw new Exception("La solicitud al mandante fue vacia ", "50002");
                }


                $error = strtolower($result->error);
                $code = strtolower($result->code);

                if ($error == "" || $error == '1') {
                    $this->convertErrorMandante('M' . $code, "Error en mandante");
                }

                $userid = $result->player->userid;
                $balance = $result->player->balance;
                $currency = $result->player->currency;

                if ($userid == "" || !is_numeric($userid)) {
                    throw new Exception("No coinciden ", "50001");
                }

                if ($balance == "") {
                    throw new Exception("No coinciden ", "50001");
                }

                if ($currency == "") {
                    throw new Exception("No coinciden ", "50001");
                }

                $respuesta->usuarioId = $UsuarioMandante->usumandanteId;
                $respuesta->moneda = $UsuarioMandante->moneda;
                $respuesta->usuario = "Usuario" . $UsuarioMandante->usumandanteId;
                $respuesta->saldo = $balance;
            }


            return $respuesta;
        } catch (Exception $e) {
            if ($_ENV['debug']) {
                print_r($e);
            }
            if ($_ENV["connectionGlobal"] != null && $_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"]->isBeginTransaction == 2) {
                ($_ENV["connectionGlobal"])->rollBack();
            }

            if ($_ENV["connectionGlobal"] != null && $_ENV["connectionGlobal"] != '') {
                if (($_ENV["connectionGlobal"])->inTransaction()) {
                    ($_ENV["connectionGlobal"])->rollBack();
                }
            }
            throw $e;
        }
    }

    /**
     * Realiza un débito en el sistema para un usuario mandante.
     *
     * @param object $UsuarioMandante Objeto que representa al usuario mandante.
     * @param object $Producto Objeto que representa el producto asociado a la transacción.
     * @param object $transaccionApi Objeto que contiene los detalles de la transacción API.
     * @param boolean $free Indica si la transacción es gratuita (por defecto es false).
     * @param array $bets Lista de apuestas asociadas a la transacción (por defecto es un array vacío).
     * @param boolean $ExisteTicketPermitido Indica si se permite la existencia de tickets previos (por defecto es true).
     * @param boolean $allowChangIfIsEnd Indica si se permiten cambios después de que el ticket esté cerrado (por defecto es true).
     *
     * @return object Respuesta con los detalles de la transacción, incluyendo usuario, saldo y transacción.
     *
     * @throws Exception Si ocurre algún error durante el proceso de débito.
     */
    public function debit($UsuarioMandante, $Producto, $transaccionApi, $free = false, $bets = [], $ExisteTicketPermitido = true, $allowChangIfIsEnd = true)
    {
        try {
            $timeInit = time();
            $messageSlackTime='*New:* '.$transaccionApi->getTransaccionId().' \n '.' \n ';
            if (true) {
                $messageSlackTime.= $transaccionApi->getTransaccionId().' *#INT#1#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;
            }
            try {
                if ($UsuarioMandante->usumandanteId == 16) {
                    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . ((time() - $timeInit) * 1000) . 'ms1 ' . $transaccionApi->getTransaccionId() . "' '#virtualsoft-cron' > /dev/null & ");
                }
            } catch (Exception $e) {
            }
            $ConfigurationEnvironment = new ConfigurationEnvironment();
            $Mandante = new Mandante($UsuarioMandante->mandante);

            $Subproveedor = new Subproveedor($Producto->getSubproveedorId());
            $Proveedor = new Proveedor($Producto->getProveedorId());
            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            //  Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante(), '', $UsuarioMandante->paisId);
            $ProveedorMandante = new ProveedorMandante($Producto->proveedorId, $UsuarioMandante->mandante);
            $SubproveedorMandante = new  SubproveedorMandante($Producto->subproveedorId, $UsuarioMandante->mandante);



            if ($Mandante->propio == 'S' && ($ConfigurationEnvironment->isDevelopment() || in_array($UsuarioMandante->mandante, array(0, 18, 19)))) {
                try {
                    $tipo = "EXCTIMEOUT";
                    $Tipo = new Clasificador("", $tipo);
                    $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioMandante->getUsuarioMandante(), 'A', $Tipo->getClasificadorId());

                    if (strtotime($UsuarioConfiguracion->getValor()) > (time())) {
                        throw new Exception("El usuario ingresado esta autoexcluido. ", "20027");
                    }
                } catch (Exception $e) {
                    if ($e->getCode() != '46') {
                        throw $e;
                    }
                }


                $Clasificador = new Clasificador("", "EXCPRODUCT");

                try {
                    $tipoProducto = '';
                    if ($Subproveedor->getTipo() == "CASINO") {
                        $tipoProducto = 3;
                    }
                    if ($Subproveedor->getTipo() == "LIVECASINO") {
                        $tipoProducto = 2;
                    }
                    if ($Subproveedor->getTipo() == "VIRTUAL") {
                        $tipoProducto = 1;
                    }
                    if ($Subproveedor->getTipo() == "SPORT") {
                        $tipoProducto = 0;
                    }
                    if ($tipoProducto != '') {
                        $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioMandante->getUsuarioMandante(), "A", $Clasificador->getClasificadorId(), $tipoProducto);

                        if ($UsuarioConfiguracion->getProductoId() != "") {
                            throw new Exception("EXCPRODUCT", "20004");
                        }
                    }
                } catch (Exception $e) {
                    if ($e->getCode() != 46) {
                        throw $e;
                    }
                }
                try {
                    $CategoriaMandante = new CategoriaMandante($Producto->getCategoriaId());

                    $categoriaId = 0;
                    $subcategoriaId = 0;

                    if ($CategoriaMandante->getCatmandanteId() != 0) {
                        $categoriaId = $CategoriaMandante->getCatmandanteId();
                    }

                    if ($categoriaId != 0) {
                        $Clasificador = new Clasificador("", "EXCCASINOCATEGORY");

                        try {
                            $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioMandante->getUsuarioMandante(), "A", $Clasificador->getClasificadorId(), $categoriaId);


                            if ($UsuarioConfiguracion->getProductoId() != "") {
                                throw new Exception("EXCCASINOCATEGORY", "20005");
                            }
                        } catch (Exception $e) {
                            if ($e->getCode() != 46) {
                                throw $e;
                            }
                        }
                    }
                } catch (Exception $e) {
                    if ($e->getCode() != 46 && $e->getCode() != 49 && $e->getCode() != '01') {
                        throw $e;
                    }
                }


                $Clasificador = new Clasificador("", "EXCCASINOGAME");

                try {
                    $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioMandante->getUsuarioMandante(), "A", $Clasificador->getClasificadorId(), $Producto->getProductoId());

                    if ($UsuarioConfiguracion->getProductoId() != "") {
                        throw new Exception("EXCCASINOGAME", "20007");
                    }
                } catch (Exception $e) {
                    if ($e->getCode() != 46) {
                        throw $e;
                    }
                }
            }



            if ($Mandante->propio == 'S' && (($ConfigurationEnvironment->isDevelopment() && $Mandante->propio == 'S') || $UsuarioMandante->mandante == 18)) {
                try {
                    $UsuarioToken2 = new UsuarioToken('', '0', $UsuarioMandante->usumandanteId);
                } catch (Exception $ex) {
                    if ($ex->getCode() == 21) throw $ex;
                }
            }

            if (true) {
                $messageSlackTime.= $transaccionApi->getTransaccionId().' *#INT#1-1#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;
            }

            if ($Mandante->propio == 'S' && ($ConfigurationEnvironment->isDevelopment() || in_array($UsuarioMandante->mandante, array(0, 18, 19)))) {
                $result = '0';
                if ($Subproveedor->getTipo() == 'CASINO') {
                    $UsuarioConfiguracion = new UsuarioConfiguracion();

                    $UsuarioConfiguracion->setUsuarioId($UsuarioMandante->usuarioMandante);
                    $result = $UsuarioConfiguracion->verifyLimitesCasino($transaccionApi->getValor(), $UsuarioMandante);
                } elseif ($Subproveedor->getTipo() == 'LIVECASINO') {
                    $UsuarioConfiguracion = new UsuarioConfiguracion();

                    $UsuarioConfiguracion->setUsuarioId($UsuarioMandante->usuarioMandante);
                    $result = $UsuarioConfiguracion->verifyLimitesCasinoVivo($transaccionApi->getValor(), $UsuarioMandante);
                } elseif ($Subproveedor->getTipo() == 'VIRTUAL') {
                    $UsuarioConfiguracion = new UsuarioConfiguracion();

                    $UsuarioConfiguracion->setUsuarioId($UsuarioMandante->usuarioMandante);
                    $result = $UsuarioConfiguracion->verifyLimitesVirtuales($transaccionApi->getValor(), $UsuarioMandante);
                }

                if ($result != '0') {
                    throw new Exception("Limite de Autoexclusion", $result);
                }
            }


            $log = microtime() . "-----------T1--------------" . "\r\n";

            $debitAmount = $transaccionApi->getValor();


            //Impuesto a las apuestas.
            try {
                $Clasificador = new Clasificador("", "TAXBET");
                $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $Clasificador->getClasificadorId(), $UsuarioMandante->paisId, 'A');
                $taxedValue = $MandanteDetalle->valor;
            } catch (Exception $e) {
                $taxedValue = 0;
            }

            if (true) {
                $messageSlackTime.= ' \n*'.$UsuarioMandante->usumandanteId . '*####' .$transaccionApi->getTransaccionId().' *#INT#2#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;
            }
            $totalTax = $debitAmount * ($taxedValue / 100);
            $debitAmountTax = $debitAmount * (1 + $taxedValue / 100);

            $respuesta = json_decode(json_encode(array(
                "usuarioId" => "",
                "moneda" => "",
                "usuario" => "",
                "transaccionId" => "",
                "saldo" => "",
                "transaccionApi" => ""
            )));

            $transactionId = $transaccionApi->getTransaccionId();

            //  Verificamos que el monto a debitar sea positivo
            if ($debitAmount < 0) {
                throw new Exception("No puede ser negativo el monto a debitar.", "10002");
            }


            if (true) {
                $messageSlackTime.= ' \n*'.$UsuarioMandante->usumandanteId . '*####' .$transaccionApi->getTransaccionId().' *#INT#3#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;
            }

            //Lista de IDs externos de productos permitidos por el sistema aun estando Inactivos.
            $productosPermitidos = ["D0_PL", "DF_BETL", "DEFAULT_00", "DEFAULT", "DE_7777"];

            if ($Producto->estado == "I") {
                if ($free && $Producto->proveedorId == 48) {
                } else {
                    if (!in_array($Producto->externoId, $productosPermitidos)) {
                        throw new Exception("Juego Inactivado.", "21010");
                    }
                }
            }

            if ($Proveedor->estado == "I") {
                if ($free && $Producto->proveedorId == 48) {
                } else {
                    throw new Exception("Proveedor Inactivado.", "21010");
                }
            }

            if (true) {
                $messageSlackTime.= ' \n*'.$UsuarioMandante->usumandanteId . '*####' .$transaccionApi->getTransaccionId().' *#INT#4#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;
            }

            if ($SubproveedorMandantePais->getEstado() == "I") {
                if ($free && $Producto->proveedorId == 48) {
                } else {
                    throw new Exception("Proveedor Inactivado.", "21010");
                }
            }
            if (true) {
                $messageSlackTime.= ' \n*'.$UsuarioMandante->usumandanteId . '*####' .$transaccionApi->getTransaccionId().' *#INT#5#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;
            }


            if ($ProductoMandante->estado == "I") {
                if ($free && $Producto->proveedorId == 48) {
                } else {
                    if (!in_array($Producto->externoId, $productosPermitidos)) {
                        throw new Exception("Juego Inactivado.", "21010");
                    }
                }
            }

            if ($ProductoMandante->habilitacion == "I") {
                if ($free && $Producto->proveedorId == 48) {
                } else {
                    if (!in_array($Producto->externoId, $productosPermitidos)) {
                        throw new Exception("Juego Inactivado.", "21010");
                    }
                }
            }

            if ($Producto->estado == "I") {
                if (!in_array($Producto->externoId, $productosPermitidos)) {
                    throw new Exception("Producto Inactivado.", "300101");
                }
            }

            if ($Proveedor->getEstado() == "I") {
                throw new Exception("Proveedor Inactivado.", "300096");
            }

            if (true) {
                $messageSlackTime.= ' \n*'.$UsuarioMandante->usumandanteId . '*####' .$transaccionApi->getTransaccionId().' *#INT#6#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;
            }

            if ($ProveedorMandante->estado == "I") {
                throw new Exception("Proveedor Mandante Inactivado.", "300097");
            }

            if ($Subproveedor->getEstado() == "I") {
                throw new Exception("Subproveeedor Inactivado.", "300098");
            }

            if (true) {
                $messageSlackTime.= ' \n*'.$UsuarioMandante->usumandanteId . '*####' .$transaccionApi->getTransaccionId().' *#INT#7#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;
            }

            if ($SubproveedorMandante->estado == "I") {
                throw new Exception("Subproveeedor Mandante Inactivado.", "300099");
            }

            if ($SubproveedorMandantePais->getEstado() == "I") {
                throw new Exception("Subproveeedor Mandante Pais Inactivado.", "300100");
            }

            if ($ProductoMandante->estado == "I") {
                if (!in_array($Producto->externoId, $productosPermitidos)) {
                    throw new Exception("ProductoMandante Inactivado.", "300102");
                }
            }

            if ($ProductoMandante->habilitacion == "I") {
                if (!in_array($Producto->externoId, $productosPermitidos)) {
                    throw new Exception("ProductoMandante Inabilitado.", "300103");
                }
            }

            if (true) {
                $messageSlackTime.= ' \n*'.$UsuarioMandante->usumandanteId . '*####' .$transaccionApi->getTransaccionId().' *#INT#8#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;
            }
            //  Agregamos Elementos a la Transaccion API
            $transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());

            $log = microtime() . "-----------T2--------------" . "\r\n";

            if($UsuarioMandante->usumandanteId != 9179402) {

                //  Verificamos que la transaccionId no se haya procesado antes
                if ($transaccionApi->existsTransaccionIdAndProveedor("OK")) {
                    //  Si la transaccionId ha sido procesada, reportamos el error
                    throw new Exception("Transaccion ya procesada", "10001");

                    $transactionId = "ND" . $transactionId;
                    $transaccionApi->setTransaccionId($transactionId);
                }
            }
            try {
                if ($UsuarioMandante->usumandanteId == 16) {
                    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . ((time() - $timeInit) * 1000) . 'msYaP1 ' . $transaccionApi->getTransaccionId() . "' '#virtualsoft-cron' > /dev/null & ");
                }


                if (((time() - $timeInit) * 1000) >= 2000 && $_ENV['enabledSlowIntegrations'] === true) {
                    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' SLOW 1 " . ((time() - $timeInit) * 1000) . 'ms01 ' . $transaccionApi->getTransaccionId() . "' '#alertas-integraciones' > /dev/null & ");
                }
            } catch (Exception $e) {
            }

            $log = microtime() . "-----------T3--------------" . "\r\n";

            if (false) {
                //  Creamos la Transaccion API Para verificar si hay antes hubo algun ROLLBACK antes
                $TransaccionApiRollback = new TransaccionApi();
                $TransaccionApiRollback->setProveedorId($Producto->getProveedorId());
                $TransaccionApiRollback->setProductoId($ProductoMandante->prodmandanteId);
                $TransaccionApiRollback->setUsuarioId($UsuarioMandante->getUsumandanteId());
                $TransaccionApiRollback->setTransaccionId('ROLLBACK' . $transactionId);
                $TransaccionApiRollback->setTipo("ROLLBACK");
                $TransaccionApiRollback->setTValue('');
                $TransaccionApiRollback->setUsucreaId(0);
                $TransaccionApiRollback->setUsumodifId(0);

                //  Verificamos que la transaccionId no se haya procesado antes
                if ($TransaccionApiRollback->existsTransaccionIdAndProveedor("ERROR")) {
                    //  Si la transaccionId tiene un Rollback antes, reportamos el error
                    throw new Exception("Transaccion con Rollback antes", "10004");
                }
            }

            $log = microtime() . "-----------T4--------------" . "\r\n";

            if (true) {
                $messageSlackTime.= ' \n*'.$UsuarioMandante->usumandanteId . '*####' .$transaccionApi->getTransaccionId().' *#INT#9#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;
            }
            // Obtenemos la Transaccion de la BD para crear y registrar el Debito con esta transaccion
            $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
            $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

            if (true) {
                $messageSlackTime.= ' \n*'.$UsuarioMandante->usumandanteId . '*####' .$transaccionApi->getTransaccionId().' *#INT#10#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;
            }
            try {
                if ($UsuarioMandante->usumandanteId == 16) {
                    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . ((time() - $timeInit) * 1000) . 'mstrans ' . $transaccionApi->getTransaccionId() . "' '#virtualsoft-cron' > /dev/null & ");
                }

                if (((time() - $timeInit) * 1000) >= 2000 && $_ENV['enabledSlowIntegrations'] === true) {
                    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' SLOW 2 " . ((time() - $timeInit) * 1000) . 'ms02 ' . $transaccionApi->getTransaccionId() . "' '#alertas-integraciones' > /dev/null & ");
                }
            } catch (Exception $e) {
            }

            if ($bets == []) {
                array_push($bets, array(
                    "id" => $transaccionApi->getIdentificador(),
                    "amount" => $debitAmount,
                    "amountTax" => $debitAmountTax,
                    "transactionId" => $transactionId
                ));
            }

            foreach ($bets as $bet) {
                try {
                    if ($UsuarioMandante->usumandanteId == 16) {
                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . ((time() - $timeInit) * 1000) . 'msforeach ' . $transaccionApi->getTransaccionId() . "' '#virtualsoft-cron' > /dev/null & ");
                    }
                } catch (Exception $e) {
                }

                if (true) {
                    $messageSlackTime.= ' \n*'.$UsuarioMandante->usumandanteId . '*####' .$transaccionApi->getTransaccionId().' *#INT#11#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;
                }
                try {
                    if ($UsuarioMandante->usumandanteId == 16) {
                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . ((time() - $timeInit) * 1000) . 'msforeach2medio ' . $transaccionApi->getTransaccionId() . "' '#virtualsoft-cron' > /dev/null & ");
                    }

                    if (((time() - $timeInit) * 1000) >= 2000 && $_ENV['enabledSlowIntegrations'] === true) {
                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' SLOW 3 " . ((time() - $timeInit) * 1000) . 'ms04 ' . $transaccionApi->getTransaccionId() . "' '#alertas-integraciones' > /dev/null & ");
                    }
                } catch (Exception $e) {
                }
                $identificador = $bet["id"];
                $amount = $bet["amount"];
                $amountTax = $bet["amountTax"];
                $transactionId = $bet["transactionId"];


                if (($UsuarioMandante->usuarioMandante == 5382313)) {
                    if ($ProductoMandante->productoId == 13470) {
                        if ($amount > 500) {
                            throw new Exception("EXCCASINOGAME", "20007");
                        }
                    }
                }

                $clave_ticket = GenerarClaveTicket2(6);

                if (true) {
                    $messageSlackTime.= ' \n*'.$UsuarioMandante->usumandanteId . '*####' .$transaccionApi->getTransaccionId().' *#INT#12#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;
                }
                //  Creamos la Transaccion por el Juego
                $TransaccionJuego = new TransaccionJuego();
                $TransaccionJuego->setProductoId($ProductoMandante->prodmandanteId);
                $TransaccionJuego->setTransaccionId($transactionId);
                $TransaccionJuego->setTicketId($identificador);
                $TransaccionJuego->setValorTicket($amount);
                $TransaccionJuego->setImpuesto($totalTax);
                $TransaccionJuego->setValorPremio(0);
                $TransaccionJuego->setMandante($UsuarioMandante->mandante);
                $TransaccionJuego->setUsuarioId($UsuarioMandante->usumandanteId);
                $TransaccionJuego->setClave($clave_ticket);

                try {
                    if ($UsuarioMandante->usumandanteId == 16) {
                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . ((time() - $timeInit) * 1000) . 'msforeach2 ' . $transaccionApi->getTransaccionId() . "' '#virtualsoft-cron' > /dev/null & ");
                    }

                    if (((time() - $timeInit) * 1000) >= 2000 && $_ENV['enabledSlowIntegrations'] === true) {
                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' SLOW 4 " . ((time() - $timeInit) * 1000) . 'ms06 ' . $transaccionApi->getTransaccionId() . "' '#alertas-integraciones' > /dev/null & ");
                    }
                } catch (Exception $e) {
                }

                $TransaccionJuego->setEstado("A");
                $TransaccionJuego->setUsucreaId(intval((time() - $timeInit) * 1000));
                $TransaccionJuego->setUsumodifId(0);
                $TransaccionJuego->setTipo('NORMAL');
                $TransaccionJuego->setPremiado('N');
                $TransaccionJuego->setFechaPago(date('Y-m-d H:i:s', time()));

                try {
                    if ($UsuarioMandante->usumandanteId == 16) {
                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . ((time() - $timeInit) * 1000) . 'ms1.112 ' . $transaccionApi->getTransaccionId() . "' '#virtualsoft-cron' > /dev/null & ");
                    }

                    if (((time() - $timeInit) * 1000) >= 2000 && $_ENV['enabledSlowIntegrations'] === true) {
                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' SLOW 5 " . ((time() - $timeInit) * 1000) . 'ms08 ' . $transaccionApi->getTransaccionId() . "' '#alertas-integraciones' > /dev/null & ");
                    }
                } catch (Exception $e) {
                }

                if ($free) {
                    $TransaccionJuego->setTipo('FREESPIN');
                }

                $ExisteTicket = true;

                //  Verificamos si existe el ticket_id antes, de ser positivo, tendriamos que combinar las apuestas


                try {
                    $TransaccionJuego2 = new TransaccionJuego("", $identificador, "");
                } catch (Exception $e) {
                    $ExisteTicket = false;
                }

                if (true) {
                    $messageSlackTime.= ' \n*'.$UsuarioMandante->usumandanteId . '*####' .$transaccionApi->getTransaccionId().' *#INT#13#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;
                }
                if (!$ExisteTicketPermitido && $ExisteTicket) {
                    //  Si la transaccionId ha sido procesada, reportamos el error
                    throw new Exception("Ticket ID ya existe", "10025");
                }


                try {
                    if ($UsuarioMandante->usumandanteId == 16) {
                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . ((time() - $timeInit) * 1000) . 'ms1.1112 ' . $transaccionApi->getTransaccionId() . "' '#virtualsoft-cron' > /dev/null & ");
                    }

                    if (((time() - $timeInit) * 1000) >= 2000 && $_ENV['enabledSlowIntegrations'] === true) {
                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' SLOW 6 " . ((time() - $timeInit) * 1000) . 'ms10 ' . $transaccionApi->getTransaccionId() . "' '#alertas-integraciones' > /dev/null & ");
                    }
                } catch (Exception $e) {
                }

                $log = microtime() . "-----------T5--------------" . "\r\n";

                try {
                    if ($UsuarioMandante->usumandanteId == 16) {
                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . ((time() - $timeInit) * 1000) . 'ms1.12 ' . $transaccionApi->getTransaccionId() . "' '#virtualsoft-cron' > /dev/null & ");
                    }

                    if (((time() - $timeInit) * 1000) >= 2000 && $_ENV['enabledSlowIntegrations'] === true) {
                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' SLOW 7 " . ((time() - $timeInit) * 1000) . 'ms12 ' . $transaccionApi->getTransaccionId() . "' '#alertas-integraciones' > /dev/null & ");
                    }
                } catch (Exception $e) {
                }

                //  Verificamos si Existe el ticket para combinar las apuestas.
                if ($ExisteTicket) {
                    //  Obtenemos la Transaccion Juego y combinamos las aúestas.
                    if ($ProductoMandante->productoId == 11289) {
                        $TransaccionJuego = new TransaccionJuego("", $identificador, "", $Transaction);
                    } else {
                        $TransaccionJuego = new TransaccionJuego("", $identificador, "");
                    }

                    if ($TransaccionJuego->getTransaccionId() != $transactionId) {
                        $TransaccionJuego->setValorTicket(' valor_ticket + ' . $amount);
                        $TransaccionJuego->setImpuesto(' impuesto + ' . $totalTax);
                    }


                    if ($TransaccionJuego->getEstado() == 'I' && !$allowChangIfIsEnd) {
                        //  Si la transaccion no es permitido para cambios despues de cerrado el ticket
                        throw new Exception("El ticket ya esta cerrado", "10027");
                    }
                }

                if (true) {
                    $messageSlackTime.= ' \n*'.$UsuarioMandante->usumandanteId . '*####' .$transaccionApi->getTransaccionId().' *#INT#14#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;
                }
                if ($_ENV['debug']) {
                    print_r(' JERSON2 AQUI');
                    print_r($Transaction);
                }

                $log = microtime() . "-----------T6--------------" . "\r\n";

                $saldoCreditos = 0;
                $saldoCreditosBase = 0;
                $saldoBonos = 0;
                $saldoFree = 0;


                try {
                    if ($UsuarioMandante->usumandanteId == 16) {
                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . ((time() - $timeInit) * 1000) . 'ms1.2 ' . $transaccionApi->getTransaccionId() . "' '#virtualsoft-cron' > /dev/null & ");
                    }


                    if (((time() - $timeInit) * 1000) >= 2000 && $_ENV['enabledSlowIntegrations'] === true) {
                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' SLOW 8 " . ((time() - $timeInit) * 1000) . 'ms14 ' . $transaccionApi->getTransaccionId() . "' '#alertas-integraciones' > /dev/null & ");
                    }
                } catch (Exception $e) {
                }
                //  Verificamos que el mandante sea Propio, para proceder con nuestros Usuarios
                if ($Mandante->propio == "S") {
                    //  Obtenemos nuestro Usuario y hacemos el debito
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);


                    if ($Mandante->propio == 'S' && $Usuario->test == 'S') {
                        try {
                            $Clasificador = new Clasificador("", "LIMITPERCLIENTTEST");
                            $tipoProducto = '';
                            if ($Subproveedor->getTipo() == "CASINO") {
                                $tipoProducto = 3;
                            }
                            if ($Subproveedor->getTipo() == "LIVECASINO") {
                                $tipoProducto = 2;
                            }
                            if ($Subproveedor->getTipo() == "VIRTUAL") {
                                $tipoProducto = 1;
                            }
                            if ($Subproveedor->getTipo() == "SPORT") {
                                $tipoProducto = 0;
                            }
                            if ($tipoProducto != '') {
                                $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioMandante->getUsuarioMandante(), "A", $Clasificador->getClasificadorId(), $tipoProducto);

                                if (floatval($UsuarioConfiguracion->getValor()) > 0 && floatval($UsuarioConfiguracion->getValor()) < floatval($debitAmount)) {
                                    throw new Exception("LIMITPERCLIENTTEST", "300018");
                                }
                            }
                        } catch (Exception $e) {
                            if ($e->getCode() != 46) {
                                throw $e;
                            }
                        }

                        try {
                            $tipoProducto = '';
                            if ($Subproveedor->getTipo() == "CASINO") {
                                $tipoProducto = "CASINOUSUONLINE";
                            }
                            if ($Subproveedor->getTipo() == "LIVECASINO") {
                                $tipoProducto = "LIVECASINOUSUONLINE";
                            }
                            if ($Subproveedor->getTipo() == "VIRTUAL") {
                                $tipoProducto = "VIRTUALUSUONLINE";
                            }
                            if ($Subproveedor->getTipo() == "SPORT") {
                                $tipoProducto = "SPORTUSUONLINE";
                            }
                            if ($Subproveedor->getTipo() == "POKER") {
                                $tipoProducto = "POKERUSUONLINE";
                            }
                            if ($tipoProducto != '') {
                                try {
                                    $Clasificador = new Clasificador("", $tipoProducto);
                                    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $Clasificador->getClasificadorId(), $UsuarioMandante->paisId, 'A');

                                    if ($MandanteDetalle->valor == 'A') {
                                        throw new Exception("imposible realizar una apuesta en este momento", "20024");
                                    }
                                } catch (Exception $e) {
                                    if ($e->getCode() != 34 && $e->getCode() != 41) {
                                        throw $e;
                                    }
                                }
                            }
                        } catch (Exception $e) {
                            if ($e->getCode() != 46) {
                                throw $e;
                            }
                        }
                    }

                    $log = microtime() . "-----------T6-1--------------" . "\r\n";
                    $log = microtime() . "-----------T6-2--------------" . "\r\n";

                    $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);
                    if ($UsuarioPerfil->perfilId == "USUONLINE") {
                        try {
                            $Clasificador = new Clasificador("", "ACCVERIFFORCASINO");

                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

                            if ($MandanteDetalle->getValor() == '1') {
                                if ($Usuario->verifcedulaPost != "S" || $Usuario->verifcedulaAnt != 'S') {
                                    throw new Exception("La cuenta necesita estar verificada para poder apostar", "21017");
                                }
                            }
                        } catch (Exception $e) {
                            if ($e->getCode() != 34 && $e->getCode() != 41) {
                                throw $e;
                            }
                        }
                    }

                    $log = microtime() . "-----------T6-3--------------" . "\r\n";

                    if ($Usuario->estado != "A" && !$free) {
                        throw new Exception("Usuario Inactivo", "20003");
                    }

                    if ($Usuario->contingencia == "A" && !$free) {
                        throw new Exception("Usuario Contingencia", "20024");
                    }
                    if ($Subproveedor == null) {
                        $Subproveedor = new Subproveedor($Producto->getSubproveedorId());
                    }

                    if ($Usuario->contingenciaCasino == "A" && $Subproveedor->getTipo() == 'CASINO' && !$free) {
                        throw new Exception("Usuario Contingencia", "20024");
                    }

                    if ($Usuario->contingenciaCasvivo == "A" && $Subproveedor->getTipo() == 'LIVECASINO' && !$free) {
                        throw new Exception("Usuario Contingencia", "20024");
                    }
                    if ($Usuario->contingenciaVirtuales == "A" && $Subproveedor->getTipo() == 'VIRTUAL' && !$free) {
                        throw new Exception("Usuario Contingencia", "20024");
                    }


                    if (!$free) {
                        if ($UsuarioMandante->mandante == '2') {
                            if (floatval($Usuario->getBalance()) > 1000000) {
                                throw new Exception("You cannot continue the operation, because you have more than 600,000 in your balance", 100030);
                            }
                        }

                        switch ($UsuarioPerfil->getPerfilId()) {
                            case "USUONLINE":

                                if ($ConfigurationEnvironment->isDevelopment() || ($UsuarioMandante->usumandanteId == 2372853) || ($UsuarioMandante->usumandanteId == 16) || ($UsuarioMandante->usumandanteId == 1390809) || ($UsuarioMandante->usumandanteId == 168712) || ($Subproveedor->getSubproveedorId() == '71' && $UsuarioMandante->mandante == '0') || ($UsuarioMandante->mandante == '15') || ($UsuarioMandante->mandante == '18') || ($UsuarioMandante->mandante == '0') || true) {
                                    $BonoInterno = new BonoInterno();


                                    $detalles = array(
                                        "TransaccionApi" => $transaccionApi
                                    );
                                    $detalles = json_encode($detalles);


                                    if ($Subproveedor->getTipo() == "VIRTUAL") {
                                        $tipoProducto = "VIRTUAL";
                                    } elseif ($Subproveedor->getTipo() == "LIVECASINO") {
                                        $tipoProducto = "LIVECASINO";
                                    } else {
                                        $tipoProducto = "CASINO";
                                    }

                                    $cachedKey = 'TIENEBONOFREECASH' . '+' . $UsuarioMandante->usumandanteId;

                                    $seguirVerificarFreeCash =false;


                                    /* Conecta a Redis y recupera un valor basado en un clave generada. */
                                    $redis = RedisConnectionTrait::getRedisInstance(
                                        true,
                                        'redis-13988.c39707.us-central1-mz.gcp.cloud.rlrcp.com',
                                        13988,
                                        'LrWXJFKjCS9PYCnprkLA1gRCqhLEcu0D',
                                        'default'
                                    );


                                    if ($redis != null) {
                                        $cachedValue = ($redis->get($cachedKey));
                                        if (!empty($cachedValue)) {
                                            if($cachedValue =='1'){
                                                $seguirVerificarFreeCash=true;
                                            }
                                        }
                                    }
                                    if($seguirVerificarFreeCash) {
                                        $responseFree = $BonoInterno->verificarBonoFree($UsuarioMandante, $detalles, $tipoProducto, $Transaction, $transaccionApi, $TransaccionJuego, $ProductoMandante, $Producto, $Usuario);


                                        if ($responseFree->WinBonus) {
                                            $conimpuesto = true;
                                            try {
                                                if ($amountTax == $amount && $amount != 0) {
                                                    $conimpuesto = false;
                                                    //Obtenemos el porcentaje de impuesto
                                                    $impuestoPorcentaje = ($amountTax - $amount) * 100 / $amount;
                                                }
                                            } catch (Exception $e) {
                                            }
                                            $amount = $responseFree->AmountDebit;
                                            $debitBonus = $responseFree->AmountBonus;

                                            if ($conimpuesto) {
                                                $amountTax = $amount;
                                            } else {
                                                $amountTax = $amount;
                                            }

                                            $saldoFree = $debitBonus;

                                            $TransaccionJuego->setTipo('FREECASH');


                                            $TransaccionJuego->setValorTicket($amount);
                                            $TransaccionJuego->setValorGratis($debitBonus);
                                        }
                                    }
                                }


                                try {
                                    if ($UsuarioMandante->usumandanteId == 16) {
                                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . ((time() - $timeInit) * 1000) . 'ms1..3 ' . $transaccionApi->getTransaccionId() . "' '#virtualsoft-cron' > /dev/null & ");
                                    }

                                    if (((time() - $timeInit) * 1000) >= 2000 && $_ENV['enabledSlowIntegrations'] === true) {
                                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' SLOW 9 " . ((time() - $timeInit) * 1000) . 'ms14 ' . $transaccionApi->getTransaccionId() . "' '#alertas-integraciones' > /dev/null & ");
                                    }
                                } catch (Exception $e) {
                                }
                                if (true) {
                                    $messageSlackTime.= ' \n*'.$UsuarioMandante->usumandanteId . '*####' .$transaccionApi->getTransaccionId().' *#INT#15#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;
                                }
                                try {
                                    if ($ConfigurationEnvironment->isDevelopment()) {
                                        $Usuario->debit($amountTax, $Transaction, 1, true);
                                    } else {
                                        $Usuario->debit($amountTax, $Transaction, 1, true);
                                    }
                                } catch (Exception $e) {
                                    try {
                                        if ($UsuarioMandante->mandante == 13) {
                                            if (true) {
                                                $redirectUrl = '/gestion/deposito?frm=lgn';

                                                $dataSend = array(
                                                    "redirectUrl" => $redirectUrl
                                                );

                                                try {
                                                    exec("php -f " . __DIR__ . "/EnviarMensajeWS.php " . $UsuarioMandante->getUsumandanteId() . " " . base64_encode(json_encode($dataSend)) . " > /dev/null &");
                                                } catch (Exception $e) {
                                                }
                                            }
                                        }
                                    } catch (Exception $e) {
                                    }


                                    throw $e;
                                }

                                if (true) {
                                    $messageSlackTime.= ' \n*'.$UsuarioMandante->usumandanteId . '*####' .$transaccionApi->getTransaccionId().' *#INT#16#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;
                                }
                                try {
                                    if ($UsuarioMandante->usumandanteId == 16) {
                                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . ((time() - $timeInit) * 1000) . 'ms1.3 ' . $transaccionApi->getTransaccionId() . "' '#virtualsoft-cron' > /dev/null & ");
                                    }

                                    if (((time() - $timeInit) * 1000) >= 2000 && $_ENV['enabledSlowIntegrations'] === true) {
                                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' SLOW 10 " . ((time() - $timeInit) * 1000) . 'ms16 ' . $transaccionApi->getTransaccionId() . "' '#alertas-integraciones' > /dev/null & ");
                                    }
                                } catch (Exception $e) {
                                }

                                $log = microtime() . "-----------T6-4--------------" . "\r\n";


                                //  Verificamos si Existe el ticket para combinar las apuestas.
                                if ($ExisteTicket) {

                                    if ($TransaccionJuego->getTransaccionId() != $transactionId) {
                                        $TransaccionJuego->update($Transaction);
                                    }

                                    $transaccion_id = $TransaccionJuego->getTransjuegoId();

                                } else {
                                    $transaccion_id = $TransaccionJuego->insert($Transaction);
                                }

                                if (true) {
                                    $messageSlackTime.= ' \n*'.$UsuarioMandante->usumandanteId . '*####' .$transaccionApi->getTransaccionId().' *#INT#17#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;
                                }
                                break;

                            case "MAQUINAANONIMA":


                                if ($ConfigurationEnvironment->isDevelopment()) {
                                    $Usuario->debit($amount, $Transaction, 1, true);
                                } else {
                                    $Usuario->debit($amount, $Transaction, 1);
                                }

                                //  Verificamos si Existe el ticket para combinar las apuestas.
                                if ($ExisteTicket) {

                                    if ($TransaccionJuego->getTransaccionId() != $transactionId) {
                                        $TransaccionJuego->update($Transaction);
                                    }

                                    $transaccion_id = $TransaccionJuego->getTransjuegoId();

                                } else {
                                    $transaccion_id = $TransaccionJuego->insert($Transaction);
                                }

                                break;

                            case "PUNTOVENTA":
                            case "CAJERO":


                                //  Verificamos si Existe el ticket para combinar las apuestas.
                                if ($ExisteTicket) {

                                    if ($TransaccionJuego->getTransaccionId() != $transactionId) {
                                        $TransaccionJuego->update($Transaction);
                                    }

                                    $transaccion_id = $TransaccionJuego->getTransjuegoId();

                                } else {
                                    $transaccion_id = $TransaccionJuego->insert($Transaction);
                                }
                                try {
                                    $Clasificador = new Clasificador('', 'RELATIONSHIPUSERONLINE');
                                    $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioMandante->usuarioMandante, 'A', $Clasificador->getClasificadorId());
                                    $IdUsuarioRelacionado = $UsuarioConfiguracion->valor;

                                    if ($IdUsuarioRelacionado != '' && $IdUsuarioRelacionado != null && intval($IdUsuarioRelacionado) > 0) {
                                        $TransjuegoInfo = new TransjuegoInfo();
                                        $TransjuegoInfo->setProductoId($ProductoMandante->prodmandanteId);
                                        $TransjuegoInfo->setTransaccionId($transaccionApi->getTransaccionId());
                                        $TransjuegoInfo->setTipo("USUARIORELACIONADO");
                                        $TransjuegoInfo->setDescripcion($UsuarioMandante->usumandanteId);
                                        $TransjuegoInfo->setValor($IdUsuarioRelacionado);
                                        $TransjuegoInfo->setTransapiId(0);
                                        $TransjuegoInfo->setUsucreaId(intval((time() - $timeInit) * 1000));
                                        $TransjuegoInfo->setUsumodifId(0);
                                        $TransjuegoInfo->setIdentificador($transaccionApi->getIdentificador());
                                        if ($TransaccionJuego != null) {
                                            $TransjuegoInfo->setTransjuegoId($TransaccionJuego->getTransjuegoId());
                                        }
                                        $TransjuegoInfo->insert($Transaction);
                                    }
                                } catch (Exception $ex) {
                                }

                                $FlujoCaja = new FlujoCaja();
                                $FlujoCaja->setFechaCrea(date('Y-m-d'));
                                $FlujoCaja->setHoraCrea(date('H:i'));
                                $FlujoCaja->setUsucreaId($Usuario->usuarioId);
                                $FlujoCaja->setTicketId('CASI_' . $TransaccionJuego->getTransjuegoId());
                                $FlujoCaja->setTipomovId('E');
                                $FlujoCaja->setValor($amount);
                                $FlujoCaja->setRecargaId(0);
                                $FlujoCaja->setMandante($Usuario->mandante);
                                $FlujoCaja->setValorForma1($amount);
                                $FlujoCaja->setTraslado('N');
                                $FlujoCaja->setFormapago1Id(1);
                                $FlujoCaja->setCuentaId('0');

                                if ($FlujoCaja->getFormapago2Id() == "") {
                                    $FlujoCaja->setFormapago2Id(0);
                                }
                                if ($FlujoCaja->getValorForma2() == "") {
                                    $FlujoCaja->setValorForma2(0);
                                }
                                if ($FlujoCaja->getCuentaId() == "") {
                                    $FlujoCaja->setCuentaId(0);
                                }
                                if ($FlujoCaja->getPorcenIva() == "") {
                                    $FlujoCaja->setPorcenIva(0);
                                }
                                if ($FlujoCaja->getValorIva() == "") {
                                    $FlujoCaja->setValorIva(0);
                                }

                                $FlujoCajaMySqlDAO = new FlujoCajaMySqlDAO($Transaction);

                                $rowsUpdate = $FlujoCajaMySqlDAO->insert($FlujoCaja);
                                if ($rowsUpdate == 0) {
                                    throw new Exception("You have reached your gaming balance limit. Please withdraw to place bet", 100030);
                                }

                                if ($UsuarioPerfil->getPerfilId() == 'CAJERO') {
                                    $PuntoVenta = new PuntoVenta("", $Usuario->puntoventaId);
                                } else {
                                    $PuntoVenta = new PuntoVenta("", $Usuario->usuarioId);
                                }

                                $rowsUpdate = $PuntoVenta->setBalanceCreditosBase(-$amountTax, $Transaction);
                                if ($rowsUpdate == 0) {
                                    throw new Exception("You have reached your gaming balance limit. Please withdraw to place bet", 100030);
                                }
                                break;
                        }
                    }else{
                        //  Verificamos si Existe el ticket para combinar las apuestas.
                        if ($ExisteTicket) {

                            if ($TransaccionJuego->getTransaccionId() != $transactionId) {
                                $TransaccionJuego->update($Transaction);
                            }

                            $transaccion_id = $TransaccionJuego->getTransjuegoId();

                        } else {
                            $transaccion_id = $TransaccionJuego->insert($Transaction);
                        }

                    }
                } else {

                    //  Verificamos si Existe el ticket para combinar las apuestas.
                    if ($ExisteTicket) {

                        if ($TransaccionJuego->getTransaccionId() != $transactionId) {
                            $TransaccionJuego->update($Transaction);
                        }

                        $transaccion_id = $TransaccionJuego->getTransjuegoId();

                    } else {
                        $transaccion_id = $TransaccionJuego->insert($Transaction);
                    }

                    try {
                        $ProdMandanteTipo = new ProdMandanteTipo("CASINO", $Mandante->mandante);
                        $data = array(
                            "sign" => $ProdMandanteTipo->siteKey,
                            "token" => $UsuarioMandante->tokenExterno,
                            "gamecode" => $ProductoMandante->prodmandanteId,
                            "amount" => $amount,
                            "roundid" => $transaccion_id,
                            "transactionid" => 0
                        );

                        $TransaccionApiMandante = new TransaccionApiMandante();
                        $TransaccionApiMandante->setTransaccionId('');
                        $TransaccionApiMandante->setTipo("DEBIT");
                        $TransaccionApiMandante->setProveedorId($Producto->getProveedorId());
                        $TransaccionApiMandante->setTValue(json_encode($data));
                        $TransaccionApiMandante->setUsucreaId(intval((time() - $timeInit) * 1000));
                        $TransaccionApiMandante->setUsumodifId(0);
                        $TransaccionApiMandante->setValor($amount);
                        $TransaccionApiMandante->setIdentificador($identificador);
                        $TransaccionApiMandante->setProductoId($ProductoMandante->prodmandanteId);
                        $TransaccionApiMandante->setUsuarioId($UsuarioMandante->getUsumandanteId());
                        $TransaccionApiMandante->setTransapiId(0);

                        $TransaccionApiMandanteMySqlDAO = new TransaccionApiMandanteMySqlDAO($Transaction);
                        $transapimandanteId = $TransaccionApiMandanteMySqlDAO->insert($TransaccionApiMandante);

                        $data["transactionid"] = $transapimandanteId;
                        $TransaccionApiMandante->setTValue(json_encode($data));


                        $result = $Mandante->sendRequest($ProdMandanteTipo->urlApi . "/debit", "POST", $data);

                        $result = json_decode(json_encode($result));

                        $TransaccionApiMandante->setRespuesta(json_encode($result));
                        $TransaccionApiMandante->setRespuestaCodigo(0);
                        $TransaccionApiMandante->setTransaccionId($result->transactionid);

                        $TransaccionApiMandanteMySqlDAO = new TransaccionApiMandanteMySqlDAO($Transaction);
                        $TransaccionApiMandanteMySqlDAO->update($TransaccionApiMandante);


                        if ($result == "") {
                            throw new Exception("La solicitud al mandante fue vacia ", "50002");
                        }


                        $balance = $result->balance;
                        $transactionIdMandante = $result->transactionid;
                        $error = strtolower($result->error);
                        $code = strtolower($result->code);


                        if ($error == "" || $error == 'true' || $error == '1') {
                            $this->convertErrorMandante('M' . $code, "Error en mandante");
                        }

                        if ($balance == "") {
                            throw new Exception("Error en los datos enviados ", "50001");
                        }

                        if ($transactionIdMandante == "") {
                            throw new Exception("Error en los datos enviados ", "50001");
                        }

                        $Balance = $balance;
                    } catch (Exception $e) {
                        $codeException = $e->getCode();
                        $messageException = $e->getMessage();

                        $TransaccionApiMandante->setRespuestaCodigo($codeException);
                        $TransaccionApiMandanteMySqlDAO = new TransaccionApiMandanteMySqlDAO();
                        $TransaccionApiMandanteMySqlDAO->update($TransaccionApiMandante);
                        $TransaccionApiMandanteMySqlDAO->getTransaction()->commit();

                        $this->convertErrorMandante($codeException, $messageException);
                    }
                }

                if (true) {
                    $messageSlackTime.= ' \n*'.$UsuarioMandante->usumandanteId . '*####' .$transaccionApi->getTransaccionId().' *#INT#18#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;
                }
                try {
                    if ($UsuarioMandante->usumandanteId == 16) {
                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . ((time() - $timeInit) * 1000) . 'ms2 ' . $transaccionApi->getTransaccionId() . "' '#virtualsoft-cron' > /dev/null & ");
                    }

                    if (((time() - $timeInit) * 1000) >= 2000 && $_ENV['enabledSlowIntegrations'] === true) {
                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' SLOW 10-7 " . ((time() - $timeInit) * 1000) . 'ms18 ' . $transaccionApi->getTransaccionId() . "' '#alertas-integraciones' > /dev/null & ");
                    }
                } catch (Exception $e) {
                }



                try {
                    if ($UsuarioMandante->usumandanteId == 16) {
                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . ((time() - $timeInit) * 1000) . 'ms2 ' . $transaccionApi->getTransaccionId() . "' '#virtualsoft-cron' > /dev/null & ");
                    }

                    if (((time() - $timeInit) * 1000) >= 2000 && $_ENV['enabledSlowIntegrations'] === true) {
                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' SLOW 10-8 " . ((time() - $timeInit) * 1000) . 'ms18 ' . $transaccionApi->getTransaccionId() . "' '#alertas-integraciones' > /dev/null & ");
                    }
                } catch (Exception $e) {
                }

                $log = microtime() . "-----------T7--------------" . "\r\n";

                /*  Obtenemos el tipo de Transaccion dependiendo de el betTypeID  */
                $tipoTransaccion = "DEBIT";

                /*  Creamos el log de la transaccion juego para auditoria  */
                $TransjuegoLog = new TransjuegoLog();
                $TransjuegoLog->setTransjuegoId($transaccion_id);
                $TransjuegoLog->setTransaccionId($transactionId);
                $TransjuegoLog->setTipo($tipoTransaccion);
                $TransjuegoLog->setTValue($transaccionApi->getTValue());
                $TransjuegoLog->setUsucreaId(intval((time() - $timeInit) * 1000));
                $TransjuegoLog->setUsumodifId(0);
                $TransjuegoLog->setValor($amount);

                $TransjuegoLog->setSaldoCreditos($saldoCreditos);
                $TransjuegoLog->setSaldoCreditosBase($saldoCreditosBase);
                $TransjuegoLog->setSaldoBonos($saldoBonos);
                $TransjuegoLog->setSaldoFree($saldoFree);
                $TransjuegoLog->setProductoId($ProductoMandante->prodmandanteId);
                $TransjuegoLog->setProveedorId($Producto->getSubproveedorId());

                $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);


                if ($taxedValue > 0) {
                    $TransjuegoLog2 = new TransjuegoLog();
                    $TransjuegoLog2->setTransjuegoId($transaccion_id);
                    $TransjuegoLog2->setTransaccionId('TXDB' . $transactionId);
                    $TransjuegoLog2->setTipo('TAXBET');
                    $TransjuegoLog2->setTValue($taxedValue);
                    $TransjuegoLog2->setUsucreaId(intval((time() - $timeInit) * 1000));
                    $TransjuegoLog2->setUsumodifId(0);
                    $TransjuegoLog2->setValor($totalTax);
                    $TransjuegoLog2->setSaldoCreditos($saldoCreditos);
                    $TransjuegoLog2->setSaldoCreditosBase($saldoCreditosBase);
                    $TransjuegoLog2->setSaldoBonos($saldoBonos);
                    $TransjuegoLog2->setSaldoFree($saldoFree);
                    $TransjuegoLog2->setProductoId($ProductoMandante->prodmandanteId);
                    $TransjuegoLog2->setProveedorId($Producto->getSubproveedorId());
                    $TransjuegoLogTax_id = $TransjuegoLog2->insert($Transaction);
                }

                $log = microtime() . "-----------T8--------------" . "\r\n";
            }

            if (true) {
                $messageSlackTime.= ' \n*'.$UsuarioMandante->usumandanteId . '*####' .$transaccionApi->getTransaccionId().' *#INT#19#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;

                if(((time() - $timeInit) * 1000)>2000){
                    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" .$messageSlackTime  . "' '#provisional' > /dev/null & ");

                }
            }

            try {
                if ($UsuarioMandante->usumandanteId == 16) {
                    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . ((time() - $timeInit) * 1000) . 'ms2 ' . $transaccionApi->getTransaccionId() . "' '#virtualsoft-cron' > /dev/null & ");
                }

                if (((time() - $timeInit) * 1000) >= 2000 && $_ENV['enabledSlowIntegrations'] === true) {
                    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' SLOW 11 " . ((time() - $timeInit) * 1000) . 'ms18 ' . $transaccionApi->getTransaccionId() . "' '#alertas-integraciones' > /dev/null & ");
                }
            } catch (Exception $e) {
            }
            $log = microtime() . "-----------T9--------------" . "\r\n";

            //  Guardamos la Transaccion Api necesaria de estado OK
            $transaccionApi->setUsucreaId(intval((time() - $timeInit) * 1000));


            $transaccionApi->setRespuestaCodigo('OK');
            $transaccionApi->setRespuesta('');
            $transaccionApi->setTValue($transaccionApi->getTValue());
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO($Transaction);
            $TransaccionApiMySqlDAO->insert($transaccionApi);

            if ($TransaccionApiMandante != "" && $TransaccionApiMandante != null) {
                $TransaccionApiMandante->setTransapiId($transaccionApi->transapiId);
                $TransaccionApiMandanteMySqlDAO = new TransaccionApiMandanteMySqlDAO($TransaccionApiMySqlDAO->getTransaction());
                $TransaccionApiMandanteMySqlDAO->update($TransaccionApiMandante);
            }


            try {
                if ($UsuarioMandante->usumandanteId == 16) {
                    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . ((time() - $timeInit) * 1000) . 'msantes ' . $transaccionApi->getTransaccionId() . "' '#virtualsoft-cron' > /dev/null & ");
                }
                if (((time() - $timeInit) * 1000) >= 2000 && $_ENV['enabledSlowIntegrations'] === true) {
                    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' SLOW 12 " . ((time() - $timeInit) * 1000) . 'ms20 ' . $transaccionApi->getTransaccionId() . "' '#alertas-integraciones' > /dev/null & ");
                }
            } catch (Exception $e) {
            }

            if ($_ENV['debug']) {
                print_r(' JERSON AQUI');
                print_r($Transaction);
                print_r($TransaccionJuego);
                print_r($Mandante);
            }

            try {
                //Información de poker para guardar en la tabla transjuego_info el movimiento de un Debit
                if ($Producto->getExternoId() == "EvenBetPoker" || $Producto->getExternoId() == "ps") {
                    $datos = json_decode(json_decode($transaccionApi->getTValue()));

                    //  Obtenemos tournamentId dependiento del producto (EvenBet o PokerStars)
                    if ($Producto->getExternoId() == "ps") {
                        // Obtenemos tournamentId para Playtech
                        $tournamentId = $datos->tournamentDetails->tournamentCode;
                    } else {
                        // Obtenemos tournamentId para EvenBet
                        $tournamentId = $datos->tournamentId;
                    }

                    if ($tournamentId != 0) {
                        // Creamos el respectivo Log de la transaccion Juego
                        $TransjuegoInfo = new TransjuegoInfo();
                        $TransjuegoInfo->setProductoId($ProductoMandante->getProdmandanteId());
                        $TransjuegoInfo->setTransaccionId($transaccionApi->getTransaccionId());
                        $TransjuegoInfo->setTipo("DEBITPOKERTORNEO");
                        $TransjuegoInfo->setDescripcion(($tournamentId) . $debitAmount * 100);
                        $TransjuegoInfo->setDescripcionTxt(($tournamentId) . $debitAmount * 100);
                        $TransjuegoInfo->setValor($debitAmount);
                        $TransjuegoInfo->setTransapiId($TransjuegoLog->transjuegologId);
                        $TransjuegoInfo->setUsucreaId(0);
                        $TransjuegoInfo->setUsumodifId(0);
                        $TransjuegoInfo->setIdentificador($transaccionApi->getIdentificador());
                        if ($TransaccionJuego != null) {
                            $TransjuegoInfo->setTransjuegoId($TransaccionJuego->getTransjuegoId());
                        }

                        //$TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO($Transaction);
                        $TransjuegoInfo->insert($Transaction);
                    }
                }
            } catch (Exception $e) {
            }

            // Commit de la transacción
            $Transaction->commit();

            if (true) {
                $messageSlackTime.= ' \n*'.$UsuarioMandante->usumandanteId . '*####' .$transaccionApi->getTransaccionId().' *#INT#20#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;

                if(((time() - $timeInit) * 1000)>2000){
                    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" .$messageSlackTime  . "' '#provisional' > /dev/null & ");

                }
            }


            if ($_ENV['debug']) {
                print_r(' JERSON AQUI22');
                print_r($Transaction);
            }
            try {
                if ($UsuarioMandante->usumandanteId == 16) {
                    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . ((time() - $timeInit) * 1000) . 'ms2 ' . $transaccionApi->getTransaccionId() . "' '#virtualsoft-cron' > /dev/null & ");
                }

                if (((time() - $timeInit) * 1000) >= 2000 && $_ENV['enabledSlowIntegrations'] === true) {
                    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' SLOW 10-9 " . ((time() - $timeInit) * 1000) . 'ms18 ' . $transaccionApi->getTransaccionId() . "' '#alertas-integraciones' > /dev/null & ");
                }
            } catch (Exception $e) {
            }
            if ($Mandante->propio == "S") {
                switch ($UsuarioPerfil->getPerfilId()) {
                    case "USUONLINE":
                        $H_user = $Usuario->usuarioId;
                        $isPV = '0';
                        break;
                    case "PUNTOVENTA":
                    case "CAJERO":
                        $H_user = $Usuario->puntoventaId;
                        $isPV = '1';
                        break;
                }
                $UsuarioHistorial = new UsuarioHistorial();
                $UsuarioHistorial->setUsuarioId($H_user);
                $UsuarioHistorial->setDescripcion('');
                $UsuarioHistorial->setMovimiento('S');
                $UsuarioHistorial->setUsucreaId(0);
                $UsuarioHistorial->setUsumodifId(0);
                $UsuarioHistorial->setTipo(30);
                $UsuarioHistorial->setValor($amountTax);
                $UsuarioHistorial->setExternoId($TransjuegoLog_id);
                $UsuarioHistorial->setCustoms($Producto->productoId);

                $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO();
                $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, $isPV);
                $UsuarioHistorialMySqlDAO->getTransaction()->commit();
            }


            if ($Mandante->propio == "S") {
                try {
                    switch ($UsuarioPerfil->getPerfilId()) {
                        case "PUNTOVENTA":
                        case "CAJERO":
                            $dataSend = [
                                "type" => 'BetVirtual',
                                "ticketId" => $TransaccionJuego->getTransjuegoId(),
                            ];
                            $WebsocketUsuario = new WebsocketUsuario('', '');
                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend, true);
                            break;
                    }
                } catch (Exception $e) {
                }
                //  Consultamos de nuevo el usuario para obtener el saldo
                if ($Usuario == null) {
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                }
                if ($UsuarioPerfil == null) {
                    $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);
                }
                switch ($UsuarioPerfil->getPerfilId()) {
                    case "USUONLINE":
                        $Balance = $Usuario->getBalance();
                        break;

                    case "MAQUINAANONIMA":

                        $Balance = $Usuario->getBalance();
                        break;

                    case "PUNTOVENTA":
                        $PuntoVenta = new PuntoVenta("", $UsuarioMandante->usuarioMandante);

                        $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                        $SaldoJuego = $PuntoVenta->getCreditosBase();

                        $Balance = $SaldoJuego;
                        break;

                    case "CAJERO":
                        $PuntoVenta = new PuntoVenta("", $Usuario->puntoventaId);

                        $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                        $SaldoJuego = $PuntoVenta->getCreditosBase();

                        $Balance = $SaldoJuego;
                        break;
                }

                if ($UsuarioPerfil != '') {
                    if ($UsuarioPerfil->getPerfilId() == 'MAQUINAANONIMA') {
                        //  Enviamos el mensaje Websocket al Usuario para que actualice el saldo

                        $UsuarioSession = new UsuarioSession();
                        $rules = [];

                        array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                        array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json = json_encode($filtro);


                        $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                        $usuarios = json_decode($usuarios);

                        $usuariosFinal = [];

                        $requestsIds = array();
                        foreach ($usuarios->data as $key => $value) {
                            array_push($requestsIds, $value->{'usuario_session.request_id'});
                        }

                        foreach ($usuarios->data as $key => $value) {
                            exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . '*MACHINE-2* ' . 'prueba' . "' '#events-machine' > /dev/null & ");
                            $data = $Usuario->getWSMessage($value->{'usuario_session.request_id'});

                            foreach ($requestsIds as $requestsId) {
                                $WebsocketUsuario = new WebsocketUsuario($requestsId, $data);
                                $WebsocketUsuario->sendWSMessage($value->{'usuario_session.tipo'});
                            }
                        }
                    }
                }

                $ConfigurationEnvironment = new ConfigurationEnvironment();

                if ($ConfigurationEnvironment->isDevelopment() && false) {
                    $UsuarioToken = new UsuarioToken("", '1', $UsuarioMandante->getUsumandanteId());

                    $data = $UsuarioMandante->getWSProfileSite($UsuarioToken->getRequestId());
                    $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                    $WebsocketUsuario->sendWSMessage();
                }
            }


            $respuesta->usuarioId = $UsuarioMandante->usumandanteId;
            $respuesta->moneda = $UsuarioMandante->moneda;
            $respuesta->usuario = "Usuario" . $UsuarioMandante->usumandanteId;
            $respuesta->saldo = $Balance;
            $respuesta->transaccionId = $TransjuegoLog_id;
            $respuesta->transaccionApi = $transaccionApi;


            if ($saldoFree != null && $saldoFree > 0) {
                $mensajesRecibidos = [];
                $array = [];

                $array["body"] = ':money_with_wings: ¡ :black_joker: Te has gastado ' . $saldoFree . ' ' . $UsuarioMandante->getMoneda() . ' de tu saldo gratis de casino :black_joker: ! :money_with_wings: ';

                array_push($mensajesRecibidos, $array);
                $data = array();
                $data["messages"] = $mensajesRecibidos;
                //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                if (in_array($UsuarioMandante->mandante, array('0', 8, 6)) || true) {
                    $dataSend = $data;

                    try {
                    } catch (Exception $e) {
                    }
                }
            }

            $log = microtime() . "-----------T10--------------" . "\r\n";

            return $respuesta;
        } catch (Exception $e) {
            if ($_ENV['debug']) {
                print_r($e);
            }

            if ($_ENV["connectionGlobal"] != null && $_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"]->isBeginTransaction == 2) {
                ($_ENV["connectionGlobal"])->rollBack();
                if ($_ENV['debug']) {
                    print_r('entroooo2');
                }
            }

            if ($_ENV["connectionGlobal"] != null && $_ENV["connectionGlobal"] != '') {
                if (($_ENV["connectionGlobal"])->inTransaction()) {
                    ($_ENV["connectionGlobal"])->rollBack();
                }
            }
            if (strpos($e->getMessage(), 'INSERT INTO casino_transprovisional') !== false) {
                //  Si la transaccionId ha sido procesada, reportamos el error
                throw new Exception("Transaccion ya procesada", "10001");
            } else {
                throw $e;
            }
        }
    }

    /**
     * Realiza operaciones de débito y crédito en una transacción.
     *
     * @param object $UsuarioMandante Objeto que representa al usuario mandante.
     * @param object $Producto Objeto que representa el producto asociado a la transacción.
     * @param object $transaccionApi Objeto que contiene los detalles de la transacción API.
     * @param boolean $free Indica si la transacción es gratuita (por defecto es false).
     * @param array $bets Lista de apuestas asociadas a la transacción (por defecto es un array vacío).
     * @param boolean $ExisteTicketPermitido Indica si se permite la existencia previa del ticket (por defecto es true).
     * @param boolean $allowChangIfIsEnd Indica si se permiten cambios si la transacción está finalizada (por defecto es true).
     * @param object $transaccionApiCredit Objeto que contiene los detalles de la transacción de crédito.
     * @param boolean $isEndRound Indica si la transacción corresponde al final de una ronda (por defecto es false).
     * @param boolean $onlyOneWin Indica si solo se permite un único ganador (por defecto es false).
     *
     * @return mixed Devuelve el resultado de la operación de débito y crédito.
     * @throws Exception Si ocurre algún error durante la operación.
     */
    public function debitAndcredit($UsuarioMandante, $Producto, $transaccionApi, $free = false, $bets = [], $ExisteTicketPermitido = true, $allowChangIfIsEnd = true, $transaccionApiCredit, $isEndRound = false, $onlyOneWin = false)
    {
        try {
            $timeInit = time();
            try {
                if ($UsuarioMandante->usumandanteId == 16) {
                    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . ((time() - $timeInit) * 1000) . 'ms1 ' . $transaccionApi->getTransaccionId() . "' '#virtualsoft-cron' > /dev/null & ");
                }
            } catch (Exception $e) {
            }
            $timeInit = time();
            $messageSlackTime='';
            $messageSlackTime.= $transaccionApi->getTransaccionId().' *#INTDC#1-1#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;

            $ConfigurationEnvironment = new ConfigurationEnvironment();
            $Subproveedor = new Subproveedor($Producto->getSubproveedorId());

            $Mandante = new Mandante($UsuarioMandante->mandante);

            if ($Mandante->propio == 'S' && ($ConfigurationEnvironment->isDevelopment() || in_array($UsuarioMandante->mandante, array(0, 18, 19)))) {
                try {
                    $tipo = "EXCTIMEOUT";
                    $Tipo = new Clasificador("", $tipo);
                    $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioMandante->getUsuarioMandante(), 'A', $Tipo->getClasificadorId());

                    if (strtotime($UsuarioConfiguracion->getValor()) > (time())) {
                        throw new Exception("El usuario ingresado esta autoexcluido. ", "20027");
                    }
                } catch (Exception $e) {
                    if ($e->getCode() != '46') {
                        throw $e;
                    }
                }


                $Clasificador = new Clasificador("", "EXCPRODUCT");

                try {
                    $tipoProducto = '';
                    if ($Subproveedor->getTipo() == "CASINO") {
                        $tipoProducto = 3;
                    }
                    if ($Subproveedor->getTipo() == "LIVECASINO") {
                        $tipoProducto = 2;
                    }
                    if ($Subproveedor->getTipo() == "VIRTUAL") {
                        $tipoProducto = 1;
                    }
                    if ($Subproveedor->getTipo() == "SPORT") {
                        $tipoProducto = 0;
                    }
                    if ($tipoProducto != '') {
                        $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioMandante->getUsuarioMandante(), "A", $Clasificador->getClasificadorId(), $tipoProducto);

                        if ($UsuarioConfiguracion->getProductoId() != "") {
                            throw new Exception("EXCPRODUCT", "20004");
                        }
                    }
                } catch (Exception $e) {
                    if ($e->getCode() != 46) {
                        throw $e;
                    }
                }

                try {
                    $CategoriaMandante = new CategoriaMandante($Producto->getCategoriaId());

                    $categoriaId = 0;
                    $subcategoriaId = 0;

                    if ($CategoriaMandante->getCatmandanteId() != 0) {
                        $categoriaId = $CategoriaMandante->getCatmandanteId();
                    }

                    if ($categoriaId != 0) {
                        $Clasificador = new Clasificador("", "EXCCASINOCATEGORY");

                        try {
                            $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioMandante->getUsuarioMandante(), "A", $Clasificador->getClasificadorId(), $categoriaId);


                            if ($UsuarioConfiguracion->getProductoId() != "") {
                                throw new Exception("EXCCASINOCATEGORY", "20005");
                            }
                        } catch (Exception $e) {
                            if ($e->getCode() != 46) {
                                throw $e;
                            }
                        }
                    }
                } catch (Exception $e) {
                    if ($e->getCode() != 46 && $e->getCode() != 49 && $e->getCode() != '01') {
                        throw $e;
                    }
                }


                $Clasificador = new Clasificador("", "EXCCASINOGAME");

                try {
                    $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioMandante->getUsuarioMandante(), "A", $Clasificador->getClasificadorId(), $Producto->getProductoId());

                    if ($UsuarioConfiguracion->getProductoId() != "") {
                        throw new Exception("EXCCASINOGAME", "20007");
                    }
                } catch (Exception $e) {
                    if ($e->getCode() != 46) {
                        throw $e;
                    }
                }
            }
            $messageSlackTime.= $transaccionApi->getTransaccionId().' *#INTDC#1#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;

            if ($Mandante->propio == 'S' && ($ConfigurationEnvironment->isDevelopment() || in_array($UsuarioMandante->mandante, array(0, 18, 19)))) {
                $result = '0';
                if ($Subproveedor->getTipo() == 'CASINO') {
                    $UsuarioConfiguracion = new UsuarioConfiguracion();

                    $UsuarioConfiguracion->setUsuarioId($UsuarioMandante->usuarioMandante);
                    $result = $UsuarioConfiguracion->verifyLimitesCasino($transaccionApi->getValor(), $UsuarioMandante);
                } elseif ($Subproveedor->getTipo() == 'LIVECASINO') {
                    $UsuarioConfiguracion = new UsuarioConfiguracion();

                    $UsuarioConfiguracion->setUsuarioId($UsuarioMandante->usuarioMandante);
                    $result = $UsuarioConfiguracion->verifyLimitesCasinoVivo($transaccionApi->getValor(), $UsuarioMandante);
                } elseif ($Subproveedor->getTipo() == 'VIRTUAL') { // verificar limitacion para Virtuales
                    $UsuarioConfiguracion = new UsuarioConfiguracion();

                    $UsuarioConfiguracion->setUsuarioId($UsuarioMandante->usuarioMandante);
                    $result = $UsuarioConfiguracion->verifyLimitesVirtuales($transaccionApi->getValor(), $UsuarioMandante);
                }

                if ($result != '0') {
                    throw new Exception("Limite de Autoexclusion", $result);
                }
            }
            $messageSlackTime.= $transaccionApi->getTransaccionId().' *#INTDC#2#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;

            // CONTINGENCIAS //

            try {
                $Clasificador = new Clasificador('', 'TOTALCONTINGENCE');
                $MandanteDetallePartner = new MandanteDetalle('', $UsuarioMandante->mandante, $Clasificador->getClasificadorId(), $UsuarioMandante->paisId, 'A');
                if ($MandanteDetallePartner->getValor() == 1) throw new Exception('We are currently in the process of maintaining the site.', 30004);
                $MandateDetalleTotal = new MandanteDetalle('', '-1', $Clasificador->clasificadorId, '0', 'A');
                if ($MandanteDetallePartner->getValor() == 1 || $MandateDetalleTotal->getValor() == 1) {
                    $UsuarioToken = new UsuarioToken($this->usuarioToken, '0');
                    $UsuarioToken->setEstado('I');

                    $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
                    $UsuarioTokenMySqlDAO->update($UsuarioToken);
                    $UsuarioTokenMySqlDAO->getTransaction()->commit();

                    throw new Exception('We are currently in the process of maintaining the site.', 30004);
                };
            } catch (Exception $ex) {
                if ($ex->getCode() == 30004) throw $ex;
            }
            $messageSlackTime.= $transaccionApi->getTransaccionId().' *#INTDC#3#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;


            if ($Subproveedor->tipo === 'CASINO') {
                try {
                    $Clasificador = new Clasificador('', 'TOTALCONTINGENCECASINO');
                    $MandanteDetalle = new MandanteDetalle('', '-1', $Clasificador->getClasificadorId(), '0', 'A');

                    if ($MandanteDetalle->getValor() == 1) throw new Exception('We are currently in the process of maintaining the site.', 30004);
                } catch (Exception $ex) {
                    if ($ex->getCode() == 30004) throw $ex;
                }
            }

            if ($Subproveedor->tipo === 'LIVECASINO') {
                try {
                    $Clasificador = new Clasificador('', 'TOTALCONTINGENCECASINOLIVE');
                    $MandanteDetalle = new MandanteDetalle('', '-1', $Clasificador->getClasificadorId(), '0', 'A');

                    if ($MandanteDetalle->getValor() == 1) throw new Exception('We are currently in the process of maintaining the site.', 30004);
                } catch (Exception $ex) {
                    if ($ex->getCode() == 30004) throw $ex;
                }
            }

            if ($Subproveedor->tipo === 'VIRTUAL') {
                try {
                    $Clasificador = new Clasificador('', 'TOTALCONTINGENCEVIRTUAL');
                    $MandanteDetalle = new MandanteDetalle('', '-1', $Clasificador->getClasificadorId(), '0', 'A');

                    if ($MandanteDetalle->getValor() == 1) throw new Exception('We are currently in the process of maintaining the site.', 30004);
                } catch (Exception $ex) {
                    if ($ex->getCode() == 30004) throw $ex;
                }
            }

            if ($Subproveedor->tipo === 'POKER') {
                try {
                    $Clasificador = new Clasificador('', 'TOTALCONTINGENCEPOKER');
                    $MandanteDetalle = new MandanteDetalle('', '-1', $Clasificador->getClasificadorId(), '0', 'A');

                    if ($MandanteDetalle->getValor() == 1) throw new Exception('We are currently in the process of maintaining the site.', 30004);
                } catch (Exception $ex) {
                    if ($ex->getCode() == 30004) throw $ex;
                }
            }
            $messageSlackTime.= $transaccionApi->getTransaccionId().' *#INTDC#4#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;

            $log = microtime() . "-----------T1--------------" . "\r\n";

            $debitAmount = $transaccionApi->getValor();


            try {
                $Clasificador = new Clasificador("", "LIMITPERCLIENTTEST");
                $tipoProducto = '';
                if ($Subproveedor->getTipo() == "CASINO") {
                    $tipoProducto = 3;
                }
                if ($Subproveedor->getTipo() == "LIVECASINO") {
                    $tipoProducto = 2;
                }
                if ($Subproveedor->getTipo() == "VIRTUAL") {
                    $tipoProducto = 1;
                }
                if ($Subproveedor->getTipo() == "SPORT") {
                    $tipoProducto = 0;
                }
                if ($tipoProducto != '') {
                    $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioMandante->getUsuarioMandante(), "A", $Clasificador->getClasificadorId(), $tipoProducto);

                    if (floatval($UsuarioConfiguracion->getValor()) > 0 && floatval($UsuarioConfiguracion->getValor()) < floatval($debitAmount)) {
                        throw new Exception("LIMITPERCLIENTTEST", "300018");
                    }
                }
            } catch (Exception $e) {
                if ($e->getCode() != 46) {
                    throw $e;
                }
            }


            try {
                $Clasificador = new Clasificador("", "LIMITPERCLIENTTEST");
                $tipoProducto = '';
                if ($Subproveedor->getTipo() == "CASINO") {
                    $tipoProducto = 3;
                }
                if ($Subproveedor->getTipo() == "LIVECASINO") {
                    $tipoProducto = 2;
                }
                if ($Subproveedor->getTipo() == "VIRTUAL") {
                    $tipoProducto = 1;
                }
                if ($Subproveedor->getTipo() == "SPORT") {
                    $tipoProducto = 0;
                }
                if ($tipoProducto != '') {
                    $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioMandante->getUsuarioMandante(), "A", $Clasificador->getClasificadorId(), $tipoProducto);

                    if (floatval($UsuarioConfiguracion->getValor()) > 0 && floatval($UsuarioConfiguracion->getValor()) < floatval($debitAmount)) {
                        throw new Exception("LIMITPERCLIENTTEST", "300018");
                    }
                }
            } catch (Exception $e) {
                if ($e->getCode() != 46) {
                    throw $e;
                }
            }

            //Impuesto a las apuestas.
            try {
                $Clasificador = new Clasificador("", "TAXBET");
                $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $Clasificador->getClasificadorId(), $UsuarioMandante->paisId, 'A');
                $taxedValue = $MandanteDetalle->valor;
            } catch (Exception $e) {
                $taxedValue = 0;
            }

            $totalTax = $debitAmount * ($taxedValue / 100);
            $debitAmountTax = $debitAmount * (1 + $taxedValue / 100);
            $messageSlackTime.= $transaccionApi->getTransaccionId().' *#INTDC#5#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;

            $respuesta = json_decode(json_encode(array(
                "usuarioId" => "",
                "moneda" => "",
                "usuario" => "",
                "transaccionId" => "",
                "saldo" => "",
                "transaccionApi" => ""
            )));

            $transactionId = $transaccionApi->getTransaccionId();

            //  Verificamos que el monto a debitar sea positivo
            if ($debitAmount < 0) {
                throw new Exception("No puede ser negativo el monto a debitar.", "10002");
            }
            $Proveedor = new Proveedor($Producto->getProveedorId());

            //Lista de IDs externos de productos permitidos por el sistema aun estando Inactivos.
            $productosPermitidos = ["D0_PL", "DF_BETL", "DEFAULT_00", "DEFAULT", "DE_7777"];

            if ($Producto->estado == "I") {
                if ($free && $Producto->proveedorId == 48) {
                } else {
                    if (!in_array($Producto->externoId, $productosPermitidos)) {
                        throw new Exception("Juego Inactivado.", "21010");
                    }
                }
            }

            if ($Proveedor->estado == "I") {
                if ($free && $Producto->proveedorId == 48) {
                } else {
                    throw new Exception("Proveedor Inactivado.", "21010");
                }
            }

            //  Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante(), '', $UsuarioMandante->paisId);


            if ($ProductoMandante->estado == "I") {
                if ($free && $Producto->proveedorId == 48) {
                } else {
                    if (!in_array($Producto->externoId, $productosPermitidos)) {
                        throw new Exception("Juego Inactivado.", "21010");
                    }
                }
            }

            if ($ProductoMandante->habilitacion == "I") {
                if ($free && $Producto->proveedorId == 48) {
                } else {
                    if (!in_array($Producto->externoId, $productosPermitidos)) {
                        throw new Exception("Juego Inactivado.", "21010");
                    }
                }
            }

            if ($Producto->estado == "I") {
                if (!in_array($Producto->externoId, $productosPermitidos)) {
                    throw new Exception("Producto Inactivado.", "300101");
                }
            }

            if ($Proveedor->getEstado() == "I") {
                throw new Exception("Proveedor Inactivado.", "300096");
            }

            $ProveedorMandante = new ProveedorMandante($Producto->proveedorId, $UsuarioMandante->mandante);
            if ($ProveedorMandante->estado == "I") {
                throw new Exception("Proveedor Mandante Inactivado.", "300097");
            }

            if ($Subproveedor->getEstado() == "I") {
                throw new Exception("Subproveeedor Inactivado.", "300098");
            }

            $SubproveedorMandante = new  SubproveedorMandante($Producto->subproveedorId, $UsuarioMandante->mandante);
            if ($SubproveedorMandante->estado == "I") {
                throw new Exception("Subproveeedor Mandante Inactivado.", "300099");
            }

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            if ($SubproveedorMandantePais->getEstado() == "I") {
                throw new Exception("Subproveeedor Mandante Pais Inactivado.", "300100");
            }

            if ($ProductoMandante->estado == "I") {
                if (!in_array($Producto->externoId, $productosPermitidos)) {
                    throw new Exception("ProductoMandante Inactivado.", "300102");
                }
            }

            if ($ProductoMandante->habilitacion == "I") {
                if (!in_array($Producto->externoId, $productosPermitidos)) {
                    throw new Exception("ProductoMandante Inabilitado.", "300103");
                }
            }
            $messageSlackTime.= $transaccionApi->getTransaccionId().' *#INTDC#6#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;

            //  Agregamos Elementos a la Transaccion API
            $transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());


            $log = microtime() . "-----------T2--------------" . "\r\n";

            //  Verificamos que la transaccionId no se haya procesado antes
            if ($transaccionApi->existsTransaccionIdAndProveedor("OK")) {
                //  Si la transaccionId ha sido procesada, reportamos el error
                throw new Exception("Transaccion ya procesada", "10001");

                $transactionId = "ND" . $transactionId;
                $transaccionApi->setTransaccionId($transactionId);
            }

            try {
                if ($UsuarioMandante->usumandanteId == 16) {
                    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . ((time() - $timeInit) * 1000) . 'msYaP1 ' . $transaccionApi->getTransaccionId() . "' '#virtualsoft-cron' > /dev/null & ");
                }


                if (((time() - $timeInit) * 1000) >= 2000 && $_ENV['enabledSlowIntegrations'] === true) {
                    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' SLOW 1 " . ((time() - $timeInit) * 1000) . 'ms01 ' . $transaccionApi->getTransaccionId() . "' '#alertas-integraciones' > /dev/null & ");
                }
            } catch (Exception $e) {
            }


            $log = microtime() . "-----------T3--------------" . "\r\n";

            if (false) {
                //  Creamos la Transaccion API Para verificar si hay antes hubo algun ROLLBACK antes
                $TransaccionApiRollback = new TransaccionApi();
                $TransaccionApiRollback->setProveedorId($Producto->getProveedorId());
                $TransaccionApiRollback->setProductoId($ProductoMandante->prodmandanteId);
                $TransaccionApiRollback->setUsuarioId($UsuarioMandante->getUsumandanteId());
                $TransaccionApiRollback->setTransaccionId('ROLLBACK' . $transactionId);
                $TransaccionApiRollback->setTipo("ROLLBACK");
                $TransaccionApiRollback->setTValue('');
                $TransaccionApiRollback->setUsucreaId(0);
                $TransaccionApiRollback->setUsumodifId(0);

                //  Verificamos que la transaccionId no se haya procesado antes
                if ($TransaccionApiRollback->existsTransaccionIdAndProveedor("ERROR")) {
                    //  Si la transaccionId tiene un Rollback antes, reportamos el error
                    throw new Exception("Transaccion con Rollback antes", "10004");
                }
            }

            $log = microtime() . "-----------T4--------------" . "\r\n";

            // Obtenemos la Transaccion de la BD para crear y registrar el Debito con esta transaccion
            $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
            $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

            try {
                if ($UsuarioMandante->usumandanteId == 16) {
                    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . ((time() - $timeInit) * 1000) . 'mstrans ' . $transaccionApi->getTransaccionId() . "' '#virtualsoft-cron' > /dev/null & ");
                }

                if (((time() - $timeInit) * 1000) >= 2000 && $_ENV['enabledSlowIntegrations'] === true) {
                    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' SLOW 2 " . ((time() - $timeInit) * 1000) . 'ms02 ' . $transaccionApi->getTransaccionId() . "' '#alertas-integraciones' > /dev/null & ");
                }
            } catch (Exception $e) {
            }

            if ($bets == []) {
                array_push($bets, array(
                    "id" => $transaccionApi->getIdentificador(),
                    "amount" => $debitAmount,
                    "amountTax" => $debitAmountTax,
                    "transactionId" => $transactionId
                ));
            }
            foreach ($bets as $bet) {
                try {
                    if ($UsuarioMandante->usumandanteId == 16) {
                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . ((time() - $timeInit) * 1000) . 'msforeach ' . $transaccionApi->getTransaccionId() . "' '#virtualsoft-cron' > /dev/null & ");
                    }
                } catch (Exception $e) {
                }
                try {
                    if ($UsuarioMandante->usumandanteId == 16) {
                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . ((time() - $timeInit) * 1000) . 'msforeach2medio ' . $transaccionApi->getTransaccionId() . "' '#virtualsoft-cron' > /dev/null & ");
                    }

                    if (((time() - $timeInit) * 1000) >= 2000 && $_ENV['enabledSlowIntegrations'] === true) {
                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' SLOW 3 " . ((time() - $timeInit) * 1000) . 'ms04 ' . $transaccionApi->getTransaccionId() . "' '#alertas-integraciones' > /dev/null & ");
                    }
                } catch (Exception $e) {
                }
                $identificador = $bet["id"];
                $amount = $bet["amount"];
                $amountTax = $bet["amountTax"];
                $transactionId = $bet["transactionId"];


                if (($UsuarioMandante->usuarioMandante == 5382313)) {
                    if ($ProductoMandante->productoId == 13470) {
                        if ($amount > 500) {
                            throw new Exception("EXCCASINOGAME", "20007");
                        }
                    }
                }
                $messageSlackTime.= $transaccionApi->getTransaccionId().' *#INTDC#7#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;

                //  Creamos la Transaccion por el Juego
                $TransaccionJuego = new TransaccionJuego();
                $TransaccionJuego->setProductoId($ProductoMandante->prodmandanteId);
                $TransaccionJuego->setTransaccionId($transactionId);
                $TransaccionJuego->setTicketId($identificador);
                $TransaccionJuego->setValorTicket($amount);
                $TransaccionJuego->setImpuesto($totalTax);
                $TransaccionJuego->setValorPremio(0);
                $TransaccionJuego->setMandante($UsuarioMandante->mandante);
                $TransaccionJuego->setUsuarioId($UsuarioMandante->usumandanteId);

                try {
                    if ($UsuarioMandante->usumandanteId == 16) {
                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . ((time() - $timeInit) * 1000) . 'msforeach2 ' . $transaccionApi->getTransaccionId() . "' '#virtualsoft-cron' > /dev/null & ");
                    }


                    if (((time() - $timeInit) * 1000) >= 2000 && $_ENV['enabledSlowIntegrations'] === true) {
                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' SLOW 4 " . ((time() - $timeInit) * 1000) . 'ms06 ' . $transaccionApi->getTransaccionId() . "' '#alertas-integraciones' > /dev/null & ");
                    }
                } catch (Exception $e) {
                }
                $TransaccionJuego->setEstado("A");
                $TransaccionJuego->setUsucreaId(0);
                $TransaccionJuego->setUsumodifId(0);
                $TransaccionJuego->setTipo('NORMAL');
                $TransaccionJuego->setPremiado('N');
                $TransaccionJuego->setFechaPago(date('Y-m-d H:i:s', time()));

                try {
                    if ($UsuarioMandante->usumandanteId == 16) {
                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . ((time() - $timeInit) * 1000) . 'ms1.112 ' . $transaccionApi->getTransaccionId() . "' '#virtualsoft-cron' > /dev/null & ");
                    }

                    if (((time() - $timeInit) * 1000) >= 2000 && $_ENV['enabledSlowIntegrations'] === true) {
                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' SLOW 5 " . ((time() - $timeInit) * 1000) . 'ms08 ' . $transaccionApi->getTransaccionId() . "' '#alertas-integraciones' > /dev/null & ");
                    }
                } catch (Exception $e) {
                }

                if ($free) {
                    $TransaccionJuego->setTipo('FREESPIN');
                }

                $ExisteTicket = true;

                //  Verificamos si existe el ticket_id antes, de ser positivo, tendriamos que combinar las apuestas


                try {
                    $TransaccionJuego2 = new TransaccionJuego("", $identificador, "");
                } catch (Exception $e) {
                    $ExisteTicket = false;
                }

                if (!$ExisteTicketPermitido && $ExisteTicket) {
                    //  Si la transaccionId ha sido procesada, reportamos el error
                    throw new Exception("Ticket ID ya existe", "10025");
                }
                $messageSlackTime.= $transaccionApi->getTransaccionId().' *#INTDC#8#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;


                try {
                    if ($UsuarioMandante->usumandanteId == 16) {
                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . ((time() - $timeInit) * 1000) . 'ms1.1112 ' . $transaccionApi->getTransaccionId() . "' '#virtualsoft-cron' > /dev/null & ");
                    }

                    if (((time() - $timeInit) * 1000) >= 2000 && $_ENV['enabledSlowIntegrations'] === true) {
                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' SLOW 6 " . ((time() - $timeInit) * 1000) . 'ms10 ' . $transaccionApi->getTransaccionId() . "' '#alertas-integraciones' > /dev/null & ");
                    }
                } catch (Exception $e) {
                }

                $log = microtime() . "-----------T5--------------" . "\r\n";


                //  Obtenemos el mandante para verificar sus caracteristicas
                $Mandante = new Mandante($UsuarioMandante->mandante);

                try {
                    if ($UsuarioMandante->usumandanteId == 16) {
                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . ((time() - $timeInit) * 1000) . 'ms1.12 ' . $transaccionApi->getTransaccionId() . "' '#virtualsoft-cron' > /dev/null & ");
                    }

                    if (((time() - $timeInit) * 1000) >= 2000 && $_ENV['enabledSlowIntegrations'] === true) {
                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' SLOW 7 " . ((time() - $timeInit) * 1000) . 'ms12 ' . $transaccionApi->getTransaccionId() . "' '#alertas-integraciones' > /dev/null & ");
                    }
                } catch (Exception $e) {
                }

                //  Verificamos si Existe el ticket para combinar las apuestas.
                if ($ExisteTicket) {
                    //  Obtenemos la Transaccion Juego y combinamos las aúestas.
                    if ($ProductoMandante->productoId == 11289) {
                        $TransaccionJuego = new TransaccionJuego("", $identificador, "", $Transaction);
                    } else {
                        $TransaccionJuego = new TransaccionJuego("", $identificador, "");
                    }

                    if ($TransaccionJuego->getTransaccionId() != $transactionId) {
                        $TransaccionJuego->setValorTicket(' valor_ticket + ' . $amount);
                        $TransaccionJuego->setImpuesto(' impuesto + ' . $totalTax);
                    }

                    if ($TransaccionJuego->getEstado() == 'I' && !$allowChangIfIsEnd) {
                        //  Si la transaccion no es permitido para cambios despues de cerrado el ticket
                        throw new Exception("El ticket ya esta cerrado", "10027");
                    }
                }
                $messageSlackTime.= $transaccionApi->getTransaccionId().' *#INTDC#9#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;

                $log = microtime() . "-----------T6--------------" . "\r\n";


                $saldoCreditos = 0;
                $saldoCreditosBase = 0;
                $saldoBonos = 0;
                $saldoFree = 0;


                try {
                    if ($UsuarioMandante->usumandanteId == 16) {
                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . ((time() - $timeInit) * 1000) . 'ms1.2 ' . $transaccionApi->getTransaccionId() . "' '#virtualsoft-cron' > /dev/null & ");
                    }


                    if (((time() - $timeInit) * 1000) >= 2000 && $_ENV['enabledSlowIntegrations'] === true) {
                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' SLOW 8 " . ((time() - $timeInit) * 1000) . 'ms14 ' . $transaccionApi->getTransaccionId() . "' '#alertas-integraciones' > /dev/null & ");
                    }
                } catch (Exception $e) {
                }
                //  Verificamos que el mandante sea Propio, para proceder con nuestros Usuarios
                if ($Mandante->propio == "S") {
                    $log = microtime() . "-----------T6-1--------------" . "\r\n";


                    //  Obtenemos nuestro Usuario y hacemos el debito
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);


                    $log = microtime() . "-----------T6-2--------------" . "\r\n";

                    $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);


                    $log = microtime() . "-----------T6-3--------------" . "\r\n";

                    if ($Usuario->estado != "A" && !$free) {
                        throw new Exception("Usuario Inactivo", "20003");
                    }

                    if ($Usuario->contingencia == "A" && !$free) {
                        throw new Exception("Usuario Contingencia", "20024");
                    }
                    if ($Subproveedor == null) {
                        $Subproveedor = new Subproveedor($Producto->getSubproveedorId());
                    }

                    if ($Usuario->contingenciaCasino == "A" && $Subproveedor->getTipo() == 'CASINO' && !$free) {
                        throw new Exception("Usuario Contingencia", "20024");
                    }

                    if ($Usuario->contingenciaCasvivo == "A" && $Subproveedor->getTipo() == 'LIVECASINO' && !$free) {
                        throw new Exception("Usuario Contingencia", "20024");
                    }
                    if ($Usuario->contingenciaVirtuales == "A" && $Subproveedor->getTipo() == 'VIRTUAL' && !$free) {
                        throw new Exception("Usuario Contingencia", "20024");
                    }

                    $messageSlackTime.= $transaccionApi->getTransaccionId().' *#INTDC#10#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;

                    if (!$free) {
                        if ($UsuarioMandante->mandante == '2') {
                            if (floatval($Usuario->getBalance()) > 1000000) {
                                throw new Exception("You cannot continue the operation, because you have more than 600,000 in your balance", 100030);
                            }
                        }

                        switch ($UsuarioPerfil->getPerfilId()) {
                            case "USUONLINE":
                                //$Proveedor = new Proveedor($Producto->getProveedorId());

                                if ($ConfigurationEnvironment->isDevelopment() || ($UsuarioMandante->usumandanteId == 2372853) || ($UsuarioMandante->usumandanteId == 16) || ($UsuarioMandante->usumandanteId == 1390809) || ($UsuarioMandante->usumandanteId == 168712) || ($Subproveedor->getSubproveedorId() == '71' && $UsuarioMandante->mandante == '0') || ($UsuarioMandante->mandante == '15') || ($UsuarioMandante->mandante == '18') || ($UsuarioMandante->mandante == '0') || true) {
                                    $BonoInterno = new BonoInterno();


                                    $detalles = array(
                                        "TransaccionApi" => $transaccionApi
                                    );
                                    $detalles = json_encode($detalles);


                                    $responseFree = $BonoInterno->verificarBonoFree($UsuarioMandante, $detalles, "CASINO", $Transaction, $transaccionApi, $TransaccionJuego, $ProductoMandante, $Producto, $Usuario);


                                    if ($responseFree->WinBonus) {
                                        $conimpuesto = true;
                                        try {
                                            if ($amountTax == $amount && $amount != 0) {
                                                $conimpuesto = false;
                                                //Obtenemos el porcentaje de impuesto
                                                $impuestoPorcentaje = ($amountTax - $amount) * 100 / $amount;
                                            }
                                        } catch (Exception $e) {
                                        }
                                        $amount = $responseFree->AmountDebit;
                                        $debitBonus = $responseFree->AmountBonus;

                                        if ($conimpuesto) {
                                            $amountTax = $amount;
                                        } else {
                                            $amountTax = $amount;
                                        }
                                        $saldoFree = $debitBonus;

                                        $TransaccionJuego->setTipo('FREECASH');


                                        $TransaccionJuego->setValorTicket($amount);
                                        $TransaccionJuego->setValorGratis($debitBonus);
                                    }
                                }


                                try {
                                    if ($UsuarioMandante->usumandanteId == 16) {
                                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . ((time() - $timeInit) * 1000) . 'ms1..3 ' . $transaccionApi->getTransaccionId() . "' '#virtualsoft-cron' > /dev/null & ");
                                    }

                                    if (((time() - $timeInit) * 1000) >= 2000 && $_ENV['enabledSlowIntegrations'] === true) {
                                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' SLOW 9 " . ((time() - $timeInit) * 1000) . 'ms14 ' . $transaccionApi->getTransaccionId() . "' '#alertas-integraciones' > /dev/null & ");
                                    }
                                } catch (Exception $e) {
                                }

                                try {
                                    if ($ConfigurationEnvironment->isDevelopment()) {
                                        $Usuario->debit($amountTax, $Transaction, 1, true);
                                    } else {
                                        $Usuario->debit($amountTax, $Transaction, 1, true);
                                    }
                                } catch (Exception $e) {
                                    try {
                                        if ($UsuarioMandante->mandante == 13) {
                                            //$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
                                            if (true) {
                                                $redirectUrl = '/gestion/deposito?frm=lgn';

                                                $dataSend = array(
                                                    "redirectUrl" => $redirectUrl
                                                );
                                                try {
                                                } catch (Exception $e) {
                                                }
                                            }
                                        }
                                    } catch (Exception $e) {
                                    }


                                    throw $e;
                                }

                                try {
                                    if ($UsuarioMandante->usumandanteId == 16) {
                                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . ((time() - $timeInit) * 1000) . 'ms1.3 ' . $transaccionApi->getTransaccionId() . "' '#virtualsoft-cron' > /dev/null & ");
                                    }

                                    if (((time() - $timeInit) * 1000) >= 2000 && $_ENV['enabledSlowIntegrations'] === true) {
                                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' SLOW 10 " . ((time() - $timeInit) * 1000) . 'ms16 ' . $transaccionApi->getTransaccionId() . "' '#alertas-integraciones' > /dev/null & ");
                                    }
                                } catch (Exception $e) {
                                }

                                $log = microtime() . "-----------T6-4--------------" . "\r\n";


                                break;

                            case "MAQUINAANONIMA":

                                if ($ConfigurationEnvironment->isDevelopment()) {
                                    $Usuario->debit($amount, $Transaction, 1, true);
                                } else {
                                    $Usuario->debit($amount, $Transaction, 1);
                                }

                                break;
                        }
                    }


                    //  Verificamos si Existe el ticket para combinar las apuestas.
                    if ($ExisteTicket) {
                        if ($TransaccionJuego->getTransaccionId() != $transactionId) {
                            $TransaccionJuego->update($Transaction);
                        }
                        $transaccion_id = $TransaccionJuego->getTransjuegoId();
                    } else {
                        $transaccion_id = $TransaccionJuego->insert($Transaction);
                    }
                    $messageSlackTime.= $transaccionApi->getTransaccionId().' *#INTDC#11#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;

                } else {

                    //  Verificamos si Existe el ticket para combinar las apuestas.
                    if ($ExisteTicket) {
                        if ($TransaccionJuego->getTransaccionId() != $transactionId) {
                            $TransaccionJuego->update($Transaction);
                        }
                        $transaccion_id = $TransaccionJuego->getTransjuegoId();
                    } else {
                        $transaccion_id = $TransaccionJuego->insert($Transaction);
                    }


                    try {
                        $ProdMandanteTipo = new ProdMandanteTipo("CASINO", $Mandante->mandante);
                        $data = array(
                            "sign" => $ProdMandanteTipo->siteKey,
                            "token" => $UsuarioMandante->tokenExterno,
                            "gamecode" => $ProductoMandante->prodmandanteId,
                            "amount" => $amount,
                            "roundid" => $transaccion_id,
                            "transactionid" => 0
                        );

                        $TransaccionApiMandante = new TransaccionApiMandante();
                        $TransaccionApiMandante->setTransaccionId('');
                        $TransaccionApiMandante->setTipo("DEBIT");
                        $TransaccionApiMandante->setProveedorId($Producto->getProveedorId());
                        $TransaccionApiMandante->setTValue(json_encode($data));
                        $TransaccionApiMandante->setUsucreaId(0);
                        $TransaccionApiMandante->setUsumodifId(0);
                        $TransaccionApiMandante->setValor($amount);
                        $TransaccionApiMandante->setIdentificador($identificador);
                        $TransaccionApiMandante->setProductoId($ProductoMandante->prodmandanteId);
                        $TransaccionApiMandante->setUsuarioId($UsuarioMandante->getUsumandanteId());
                        $TransaccionApiMandante->setTransapiId(0);

                        $TransaccionApiMandanteMySqlDAO = new TransaccionApiMandanteMySqlDAO($Transaction);
                        $transapimandanteId = $TransaccionApiMandanteMySqlDAO->insert($TransaccionApiMandante);

                        $data["transactionid"] = $transapimandanteId;
                        $TransaccionApiMandante->setTValue(json_encode($data));


                        $result = $Mandante->sendRequest($ProdMandanteTipo->urlApi . "/debit", "POST", $data);

                        $result = json_decode(json_encode($result));

                        $TransaccionApiMandante->setRespuesta(json_encode($result));
                        $TransaccionApiMandante->setRespuestaCodigo(0);
                        $TransaccionApiMandante->setTransaccionId($result->transactionid);

                        $TransaccionApiMandanteMySqlDAO = new TransaccionApiMandanteMySqlDAO($Transaction);
                        $TransaccionApiMandanteMySqlDAO->update($TransaccionApiMandante);


                        if ($result == "") {
                            throw new Exception("La solicitud al mandante fue vacia ", "50002");
                        }


                        $balance = $result->balance;
                        $transactionIdMandante = $result->transactionid;
                        $error = strtolower($result->error);
                        $code = strtolower($result->code);


                        if ($error == "" || $error == 'true' || $error == '1') {
                            $this->convertErrorMandante('M' . $code, "Error en mandante");
                        }

                        if ($balance == "") {
                            throw new Exception("Error en los datos enviados ", "50001");
                        }

                        if ($transactionIdMandante == "") {
                            throw new Exception("Error en los datos enviados ", "50001");
                        }

                        $Balance = $balance;
                    } catch (Exception $e) {
                        $codeException = $e->getCode();
                        $messageException = $e->getMessage();

                        $TransaccionApiMandante->setRespuestaCodigo($codeException);
                        $TransaccionApiMandanteMySqlDAO = new TransaccionApiMandanteMySqlDAO();
                        $TransaccionApiMandanteMySqlDAO->update($TransaccionApiMandante);
                        $TransaccionApiMandanteMySqlDAO->getTransaction()->commit();

                        $this->convertErrorMandante($codeException, $messageException);
                    }
                }


                $log = microtime() . "-----------T7--------------" . "\r\n";

                /*  Obtenemos el tipo de Transaccion dependiendo de el betTypeID  */
                $tipoTransaccion = "DEBIT";

                /*  Creamos el log de la transaccion juego para auditoria  */
                $TransjuegoLog = new TransjuegoLog();
                $TransjuegoLog->setTransjuegoId($transaccion_id);
                $TransjuegoLog->setTransaccionId($transactionId);
                $TransjuegoLog->setTipo($tipoTransaccion);
                $TransjuegoLog->setTValue($transaccionApi->getTValue());
                $TransjuegoLog->setUsucreaId(0);
                $TransjuegoLog->setUsumodifId(0);
                $TransjuegoLog->setValor($amount);

                $TransjuegoLog->setSaldoCreditos($saldoCreditos);
                $TransjuegoLog->setSaldoCreditosBase($saldoCreditosBase);
                $TransjuegoLog->setSaldoBonos($saldoBonos);
                $TransjuegoLog->setSaldoFree($saldoFree);
                $TransjuegoLog->setProductoId($ProductoMandante->prodmandanteId);
                $TransjuegoLog->setProveedorId($Producto->getSubproveedorId());

                $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);
                $messageSlackTime.= $transaccionApi->getTransaccionId().' *#INTDC#12#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;

                if ($taxedValue > 0) {
                    $TransjuegoLog2 = new TransjuegoLog();
                    $TransjuegoLog2->setTransjuegoId($transaccion_id);
                    $TransjuegoLog2->setTransaccionId('TXDB' . $transactionId);
                    $TransjuegoLog2->setTipo('TAXBET');
                    $TransjuegoLog2->setTValue($taxedValue);
                    $TransjuegoLog2->setUsucreaId(0);
                    $TransjuegoLog2->setUsumodifId(0);
                    $TransjuegoLog2->setValor($totalTax);
                    $TransjuegoLog2->setSaldoCreditos($saldoCreditos);
                    $TransjuegoLog2->setSaldoCreditosBase($saldoCreditosBase);
                    $TransjuegoLog2->setSaldoBonos($saldoBonos);
                    $TransjuegoLog2->setSaldoFree($saldoFree);
                    $TransjuegoLog2->setProductoId($ProductoMandante->prodmandanteId);
                    $TransjuegoLog2->setProveedorId($Producto->getSubproveedorId());
                    $TransjuegoLogTax_id = $TransjuegoLog2->insert($Transaction);
                }

                $messageSlackTime.= $transaccionApi->getTransaccionId().' *#INTDC#13#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;

                $log = microtime() . "-----------T8--------------" . "\r\n";


                $respuesta = json_decode(json_encode(array(
                    "usuarioId" => "",
                    "moneda" => "",
                    "usuario" => "",
                    "transaccionId" => "",
                    "saldo" => "",
                    "transaccionApi" => ""
                )));

                $creditAmount = $transaccionApiCredit->getValor();


                //  Verificamos que el monto a creditar sea positivo
                if ($creditAmount < 0) {
                    throw new Exception("No puede ser negativo el monto a debitar.", "10002");
                }


                //  Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
                $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante(), '', $UsuarioMandante->paisId);

                //  Agregamos Elementos a la Transaccion API
                $transaccionApiCredit->setProductoId($ProductoMandante->prodmandanteId);
                $transaccionApiCredit->setUsuarioId($UsuarioMandante->getUsumandanteId());


                //  Verificamos que la transaccionId no se haya procesado antes
                if ($transaccionApiCredit->existsTransaccionIdAndProveedor("OK")) {
                    //  Si la transaccionId ha sido procesada, reportamos el error
                    throw new Exception("Transaccion ya procesada", "10001");
                }


                //  Obtenemos la Transaccion Juego
                //$TransaccionJuego = new TransaccionJuego("", $transaccionApi->getIdentificador());


                $log = date("Y-m-d H:i:s") . "-----------T2--------------" . "\r\n";


                if ($TransaccionJuego->getEstado() == 'I' && !$allowChangIfIsEnd) {
                    //  Si la transaccion no es permitido para cambios despues de cerrado el ticket
                    throw new Exception("El ticket ya esta cerrado", "10027");
                }

                // Obtenemos la Transaccion de la BD para crear y registrar el Debito con esta transaccion
                $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
                $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

                //  Obtenemos el ID de la TransaccionJuego
                $TransJuegoId = $TransaccionJuego->getTransjuegoId();

                //  Obtenemos el tipo de transacción dependiendo a betTypeID y si se le suma los creditos o no
                $sumaCreditos = false;
                $tipoTransaccion = "CREDIT";

                //  Actualizamos la Transaccion Juego con los respectivas actualizaciones
                $TransaccionJuego->setValorPremio($TransaccionJuego->getValorPremio() + $creditAmount);

                $messageSlackTime.= $transaccionApi->getTransaccionId().' *#INTDC#14#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;

                if ($free && $TransaccionJuego->getTipo() == 'NORMAL') {
                    $TransaccionJuego->setTipo('FREESPIN');
                }
                if ($isEndRound) {
                    if ($TransaccionJuego->getValorPremio() > 0) {
                        $TransaccionJuego->setPremiado("S");
                        $TransaccionJuego->setFechaPago(date('Y-m-d H:i:s', time()));
                        $sumaCreditos = true;
                    }

                    $TransaccionJuego->setEstado("I");
                }


                $log = date("Y-m-d H:i:s") . "-----------T4--------------" . "\r\n";


                $TransaccionJuego->update($Transaction);
                $messageSlackTime.= $transaccionApi->getTransaccionId().' *#INTDC#15#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;


                //  Obtenemos el mandante para verificar sus caracteristicas
                $Mandante = new Mandante($UsuarioMandante->mandante);


                //  Verificamos si el mandante es propio
                if ($Mandante->propio == "S") {
                    //  Obtenemos nuestro Usuario y hacemos el debito
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                    $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);

                    if ($Usuario->estado != "A") {
                    }

                    if ($creditAmount > 0) {
                        $sumaCreditos = true;
                    }

                    //  Si suma los creditos, hacemos el respectivo CREDIT
                    if ($sumaCreditos) {
                        if ($creditAmount > 0) {
                            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                            switch ($UsuarioPerfil->getPerfilId()) {
                                case "USUONLINE":

                                    $freecashEnSaldoRecargas = false;

                                    if ($Usuario->mandante == 14 && $TransaccionJuego->getTipo() == 'FREESPIN' && date('Y-m-d H:i:s') >= '2023-03-27 08:00:00' && false) {
                                        $freecashEnSaldoRecargas = true;
                                    }
                                    if (
                                        $Usuario->mandante == 0 && $Usuario->paisId == 2 && $TransaccionJuego->getTipo() == 'FREESPIN'
                                        && date('Y-m-d H:i:s') >= '2023-04-01 00:00:00'
                                        && date('Y-m-d H:i:s') <= '2023-04-02 23:59:59'
                                    ) {
                                        $freecashEnSaldoRecargas = true;
                                    }

                                    if ($freecashEnSaldoRecargas) {
                                        $Usuario->credit($creditAmount, $Transaction);
                                    } else {
                                        $Usuario->creditWin($creditAmount, $Transaction);
                                    }
                                    $messageSlackTime.= $transaccionApi->getTransaccionId().' *#INTDC#16#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;


                                    break;

                                case "MAQUINAANONIMA":

                                    $Usuario->creditWin($creditAmount, $Transaction);

                                    break;
                            }
                        }
                    }
                } else {
                    try {
                        $ProdMandanteTipo = new ProdMandanteTipo("CASINO", $Mandante->mandante);
                        $data = array(
                            //"site" => $ProdMandanteTipo->siteId,
                            "userid" => $UsuarioMandante->usuarioMandante,
                            "sign" => $ProdMandanteTipo->siteKey,
                            "token" => $UsuarioMandante->tokenExterno,
                            "gamecode" => $ProductoMandante->prodmandanteId,
                            "amount" => $creditAmount,
                            "roundid" => $TransaccionJuego->getTransjuegoId(),
                            "transactionid" => 0
                        );

                        $transaccionApiCreditMandante = new TransaccionApiMandante();
                        $transaccionApiCreditMandante->setTransaccionId('');
                        $transaccionApiCreditMandante->setTipo("CREDIT");
                        $transaccionApiCreditMandante->setProveedorId($Producto->getProveedorId());
                        $transaccionApiCreditMandante->setTValue(json_encode($data));
                        $transaccionApiCreditMandante->setUsucreaId(0);
                        $transaccionApiCreditMandante->setUsumodifId(0);
                        $transaccionApiCreditMandante->setValor($creditAmount);
                        $transaccionApiCreditMandante->setIdentificador($transaccionApi->getIdentificador());
                        $transaccionApiCreditMandante->setProductoId($ProductoMandante->prodmandanteId);
                        $transaccionApiCreditMandante->setUsuarioId($UsuarioMandante->getUsumandanteId());
                        $transaccionApiCreditMandante->setTransapiId(0);

                        $transaccionApiCreditMandanteMySqlDAO = new TransaccionApiMandanteMySqlDAO($Transaction);
                        $transapimandanteId = $transaccionApiCreditMandanteMySqlDAO->insert($transaccionApiCreditMandante);

                        $data["transactionid"] = $transapimandanteId;
                        $transaccionApiCreditMandante->setTValue(json_encode($data));

                        $result = $Mandante->sendRequest($ProdMandanteTipo->urlApi . "/credit", "POST", $data);

                        $result = json_decode(json_encode($result));

                        $transaccionApiCreditMandante->setRespuesta(json_encode($result));
                        $transaccionApiCreditMandante->setRespuestaCodigo(0);
                        $transaccionApiCreditMandante->setTransaccionId($result->transactionid);

                        $transaccionApiCreditMandanteMySqlDAO = new TransaccionApiMandanteMySqlDAO();
                        $transaccionApiCreditMandanteMySqlDAO->update($transaccionApiCreditMandante);


                        if ($result == "") {
                            throw new Exception("La solicitud al mandante fue vacia ", "50002");
                        }


                        $balance = $result->balance;
                        $transactionIdMandante = $result->transactionid;
                        $error = strtolower($result->error);
                        $code = strtolower($result->code);


                        if ($error == "" || $error == 'true' || $error == '1') {
                            $this->convertErrorMandante('M' . $code, "Error en mandante");
                        }

                        if ($balance == "") {
                            throw new Exception("No coinciden ", "50001");
                        }

                        if ($transactionIdMandante == "") {
                            throw new Exception("No coinciden ", "50001");
                        }

                        $Balance = $balance;
                    } catch (Exception $e) {
                        $codeException = $e->getCode();
                        $messageException = $e->getMessage();

                        $transaccionApiCreditMandante->setRespuestaCodigo($codeException);
                        $transaccionApiCreditMandanteMySqlDAO = new TransaccionApiMandanteMySqlDAO();
                        $transaccionApiCreditMandanteMySqlDAO->update($transaccionApiCreditMandante);
                        $transaccionApiCreditMandanteMySqlDAO->getTransaction()->commit();

                        $this->convertErrorMandante($codeException, $messageException);
                    }
                }

                //  Creamos el respectivo Log de la transaccion Juego
                $TransjuegoLog = new TransjuegoLog();
                $TransjuegoLog->setTransjuegoId($TransJuegoId);
                $TransjuegoLog->setTransaccionId($transaccionApiCredit->getTransaccionId());
                $TransjuegoLog->setTipo($tipoTransaccion);
                $TransjuegoLog->setTValue($transaccionApiCredit->getTValue());
                $TransjuegoLog->setUsucreaId(0);
                $TransjuegoLog->setUsumodifId(0);
                $TransjuegoLog->setValor($creditAmount);
                $TransjuegoLog->setProductoId($ProductoMandante->prodmandanteId);
                $TransjuegoLog->setProveedorId($Producto->getSubproveedorId());

                $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);
                $messageSlackTime.= $transaccionApi->getTransaccionId().' *#INTDC#17#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;


                $log = date("Y-m-d H:i:s") . "-----------T3--------------" . "\r\n";


                if ($onlyOneWin) {
                    if (!$TransjuegoLog->isEqualsNewCredit()) {
                        // Si el numero de creditos es mayor al de los debitos sacamos error
                        throw new Exception("CREDIT MAYOR A DEBIT", "10014");
                    }
                }

                $log = date("Y-m-d H:i:s") . "-----------T5--------------" . "\r\n";

                $messageSlackTime.= $transaccionApi->getTransaccionId().' *#INTDC#18#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;

                if ($Mandante->propio == "S") {
                    //  Consultamos de nuevo el usuario para obtener el saldo
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                    $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);

                    switch ($UsuarioPerfil->getPerfilId()) {
                        case "USUONLINE":
                            $Balance = $Usuario->getBalance();
                            break;

                        case "MAQUINAANONIMA":

                            $Balance = $Usuario->getBalance();
                            break;
                    }
                    if ($UsuarioPerfil != '') {
                        if ($UsuarioPerfil->getPerfilId() == 'MAQUINAANONIMA') {
                            //  Enviamos el mensaje Websocket al Usuario para que actualice el saldo

                            $UsuarioSession = new UsuarioSession();
                            $rules = [];

                            array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                            array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                            $json = json_encode($filtro);


                            $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                            $usuarios = json_decode($usuarios);

                            $usuariosFinal = [];

                            $requestsIds = array();
                            foreach ($usuarios->data as $key => $value) {
                                array_push($requestsIds, $value->{'usuario_session.request_id'});
                            }
                            foreach ($usuarios->data as $key => $value) {
                                $data = $Usuario->getWSMessage($value->{'usuario_session.request_id'});

                                foreach ($requestsIds as $requestsId) {
                                    $WebsocketUsuario = new WebsocketUsuario($requestsId, $data);
                                    $WebsocketUsuario->sendWSMessage($value->{'usuario_session.tipo'});
                                }
                            }
                        }
                    }
                }
                $messageSlackTime.= $transaccionApi->getTransaccionId().' *#INTDC#19#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;

                // Guardamos la Transaccion Api necesaria de estado OK
                $transaccionApiCredit->setRespuestaCodigo("OK");
                $transaccionApiCredit->setRespuesta('');
                $transaccionApiCreditMySqlDAO = new TransaccionApiMySqlDAO($Transaction);
                $transaccionApiCreditMySqlDAO->insert($transaccionApiCredit);

                if ($transaccionApiCreditMandante != "" && $transaccionApiCreditMandante != null) {
                    $transaccionApiCreditMandante->setTransapiId($transaccionApiCredit->transapiId);
                    $transaccionApiCreditMandanteMySqlDAO = new TransaccionApiMandanteMySqlDAO($transaccionApiCreditMySqlDAO->getTransaction());
                    $transaccionApiCreditMandanteMySqlDAO->update($transaccionApiCreditMandante);
                }

                // Commit de la transacción
                $Transaction->commit();

                $messageSlackTime.= $transaccionApi->getTransaccionId().' *#INTDC#20#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;

                if ($Mandante->propio == "S") {
                    $UsuarioHistorial = new UsuarioHistorial();
                    $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                    $UsuarioHistorial->setDescripcion('');
                    $UsuarioHistorial->setMovimiento('S');
                    $UsuarioHistorial->setUsucreaId(0);
                    $UsuarioHistorial->setUsumodifId(0);
                    $UsuarioHistorial->setTipo(30);
                    $UsuarioHistorial->setValor($amountTax);
                    $UsuarioHistorial->setExternoId($TransjuegoLog_id);

                    $UsuarioHistorial->setCustoms($Producto->productoId);

                    $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO();
                    $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);
                    $UsuarioHistorialMySqlDAO->getTransaction()->commit();
                }

                if ($Mandante->propio == "S") {
                    $UsuarioHistorial = new UsuarioHistorial();
                    $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                    $UsuarioHistorial->setDescripcion('');
                    $UsuarioHistorial->setMovimiento('E');
                    $UsuarioHistorial->setUsucreaId(0);
                    $UsuarioHistorial->setUsumodifId(0);
                    $UsuarioHistorial->setTipo(30);
                    $UsuarioHistorial->setValor($creditAmount);
                    $UsuarioHistorial->setExternoId($TransjuegoLog_id);

                    $UsuarioHistorial->setCustoms($Producto->productoId);

                    $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO();
                    $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);
                    $UsuarioHistorialMySqlDAO->getTransaction()->commit();
                }

                $ConfigurationEnvironment = new ConfigurationEnvironment();

                if ($sumaCreditos) {
                    $typeP = "CASINO";

                    $Proveedor = new Proveedor($Producto->getProveedorId());

                    if ($Proveedor->getTipo() == 'CASINO') {
                        $typeP = "CASINO";
                    } elseif ($Proveedor->getTipo() == 'LIVECASINO') {
                        $typeP = "LIVECASINO";
                    }


                    try {
                        if (!$ConfigurationEnvironment->isDevelopment()) {
                            if ($creditAmount >= 1000 && $UsuarioMandante->moneda == 'PEN') {
                                try {
                                    $message = "Casino *Usuario:* " . $UsuarioMandante->usuarioMandante . " - *Premio:* " . $UsuarioMandante->moneda . " " . $creditAmount;
                                } catch (Exception $e) {
                                }
                            }
                            if ($creditAmount >= 250 && $UsuarioMandante->moneda == 'USD' && $UsuarioMandante->mandante == '8') {
                                try {
                                    $message = "Casino *Usuario:* " . $UsuarioMandante->usuarioMandante . " - *Premio:* " . $UsuarioMandante->moneda . " " . $creditAmount;
                                } catch (Exception $e) {
                                }
                            }
                            if ($creditAmount >= 160613 && $UsuarioMandante->moneda == 'CRC') {
                                try {
                                    $message = "Casino *Usuario:* " . $UsuarioMandante->usuarioMandante . " - *Premio:* " . $UsuarioMandante->moneda . " " . $creditAmount;
                                } catch (Exception $e) {
                                }
                            }
                            if ($creditAmount >= 5156 && $UsuarioMandante->moneda == 'MXN' && $UsuarioMandante->mandante == '6') {
                                try {
                                    $message = "Casino *Usuario:* " . $UsuarioMandante->usuarioMandante . " - *Premio:* " . $UsuarioMandante->moneda . " " . $creditAmount;
                                } catch (Exception $e) {
                                }
                            }
                            if ($creditAmount >= 216281 && $UsuarioMandante->moneda == 'CLP') {
                                try {
                                    $message = "Casino *Usuario:* " . $UsuarioMandante->usuarioMandante . " - *Premio:* " . $UsuarioMandante->moneda . " " . $creditAmount;
                                } catch (Exception $e) {
                                }
                            }
                        }
                    } catch (Exception $e) {
                    }
                }

                if ($UsuarioMandante->mandante == "0") {
                    try {
                        $useragent = $_SERVER['HTTP_USER_AGENT'];
                        $jsonServer = json_encode($_SERVER);
                        $serverCodif = base64_encode($jsonServer);


                        $ismobile = '';

                        if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match(
                                '/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4)
                            )) {
                            $ismobile = '1';
                        }
                        $iPod = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");
                        $iPhone = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
                        $iPad = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
                        $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
                        $webOS = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");

                        if ($iPod || $iPhone) {
                            $ismobile = '1';
                        } elseif ($iPad) {
                            $ismobile = '1';
                        } elseif ($Android) {
                            $ismobile = '1';
                        }
                    } catch (Exception $e) {
                    }
                }
            }
            $messageSlackTime.= $transaccionApi->getTransaccionId().' *#INTDC#21#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;


            try {
                if ($UsuarioMandante->usumandanteId == 16) {
                    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . ((time() - $timeInit) * 1000) . 'ms2 ' . $transaccionApi->getTransaccionId() . "' '#virtualsoft-cron' > /dev/null & ");
                }

                if (((time() - $timeInit) * 1000) >= 2000 && $_ENV['enabledSlowIntegrations'] === true) {
                    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' SLOW 11 " . ((time() - $timeInit) * 1000) . 'ms18 ' . $transaccionApi->getTransaccionId() . "' '#alertas-integraciones' > /dev/null & ");
                }
            } catch (Exception $e) {
            }
            $log = microtime() . "-----------T9--------------" . "\r\n";

            //  Guardamos la Transaccion Api necesaria de estado OK
            $transaccionApi->setUsucreaId(intval((time() - $timeInit) * 1000));


            $transaccionApi->setRespuestaCodigo('OK');
            $transaccionApi->setRespuesta('');
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO($Transaction);
            $TransaccionApiMySqlDAO->insert($transaccionApi);

            if ($TransaccionApiMandante != "" && $TransaccionApiMandante != null) {
                $TransaccionApiMandante->setTransapiId($transaccionApi->transapiId);
                $TransaccionApiMandanteMySqlDAO = new TransaccionApiMandanteMySqlDAO($TransaccionApiMySqlDAO->getTransaction());
                $TransaccionApiMandanteMySqlDAO->update($TransaccionApiMandante);
            }

            $messageSlackTime.= $transaccionApi->getTransaccionId().' *#INTDC#22#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;

            try {
                if ($UsuarioMandante->usumandanteId == 16) {
                    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . ((time() - $timeInit) * 1000) . 'msantes ' . $transaccionApi->getTransaccionId() . "' '#virtualsoft-cron' > /dev/null & ");
                }
                if (((time() - $timeInit) * 1000) >= 2000 && $_ENV['enabledSlowIntegrations'] === true) {
                    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' SLOW 12 " . ((time() - $timeInit) * 1000) . 'ms20 ' . $transaccionApi->getTransaccionId() . "' '#alertas-integraciones' > /dev/null & ");
                }
            } catch (Exception $e) {
            }


            // Commit de la transacción

            if ($Mandante->propio == "S") {
                //  Consultamos de nuevo el usuario para obtener el saldo
                if ($Usuario == null) {
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                }
                if ($UsuarioPerfil == null) {
                    $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);
                }
                switch ($UsuarioPerfil->getPerfilId()) {
                    case "USUONLINE":
                        $Balance = $Usuario->getBalance();
                        break;

                    case "MAQUINAANONIMA":

                        $Balance = $Usuario->getBalance();
                        break;
                }

                if ($UsuarioPerfil != '') {
                    if ($UsuarioPerfil->getPerfilId() == 'MAQUINAANONIMA') {
                        //  Enviamos el mensaje Websocket al Usuario para que actualice el saldo

                        $UsuarioSession = new UsuarioSession();
                        $rules = [];

                        array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                        array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json = json_encode($filtro);


                        $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                        $usuarios = json_decode($usuarios);

                        $usuariosFinal = [];

                        $requestsIds = array();
                        foreach ($usuarios->data as $key => $value) {
                            array_push($requestsIds, $value->{'usuario_session.request_id'});
                        }

                        foreach ($usuarios->data as $key => $value) {
                            exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . '*MACHINE-2* ' . 'prueba' . "' '#events-machine' > /dev/null & ");
                            $data = $Usuario->getWSMessage($value->{'usuario_session.request_id'});

                            foreach ($requestsIds as $requestsId) {
                                $WebsocketUsuario = new WebsocketUsuario($requestsId, $data);
                                $WebsocketUsuario->sendWSMessage($value->{'usuario_session.tipo'});
                            }
                        }
                    }
                }

                $ConfigurationEnvironment = new ConfigurationEnvironment();

                if ($ConfigurationEnvironment->isDevelopment() && false) {
                    $UsuarioToken = new UsuarioToken("", '1', $UsuarioMandante->getUsumandanteId());

                    $data = $UsuarioMandante->getWSProfileSite($UsuarioToken->getRequestId());
                    $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                    $WebsocketUsuario->sendWSMessage();
                }
            }

            $messageSlackTime.= $transaccionApi->getTransaccionId().' *#INTDC#23#* '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;

            $respuesta->usuarioId = $UsuarioMandante->usumandanteId;
            $respuesta->moneda = $UsuarioMandante->moneda;
            $respuesta->usuario = "Usuario" . $UsuarioMandante->usumandanteId;
            $respuesta->saldo = $Balance;
            $respuesta->transaccionId = $TransjuegoLog_id;
            $respuesta->transaccionApi = $transaccionApi;

            if (!$free) {
                $typeP = "CASINO";

                $Proveedor = new Proveedor($Producto->getProveedorId());
                $Subproveedor = new Subproveedor($Producto->getSubproveedorId());

                if ($Subproveedor->getTipo() == 'CASINO') {
                    $typeP = "CASINO";
                } elseif ($Subproveedor->getTipo() == 'LIVECASINO') {
                    $typeP = "LIVECASINO";
                } elseif ($Subproveedor->getTipo() == 'VIRTUAL') {
                    $typeP = 'VIRTUAL';
                }

                if (true) {
                }

                if ($ConfigurationEnvironment->isDevelopment() || $UsuarioMandante->mandante == 8 || ($UsuarioMandante->mandante == 0 && $UsuarioMandante->paisId == 173)) {
                }

                if ($ConfigurationEnvironment->isDevelopment() || ($UsuarioMandante->mandante == '8') || $UsuarioMandante->usuarioMandante == 130578 || $UsuarioMandante->usuarioMandante == 886 || (($Producto->getProveedorId() == '56' || $Producto->getProveedorId() == '48' || $Producto->getProveedorId() == '109') && $UsuarioMandante->mandante == '0')) {
                }


                try {
                    if (!$ConfigurationEnvironment->isDevelopment()) {
                        if ($amount >= 1000 && $UsuarioMandante->moneda == 'PEN') {
                            try {
                                $message = "Casino *Usuario:* " . $UsuarioMandante->usuarioMandante . " - *Apuesta:* " . $UsuarioMandante->moneda . " " . $amount;
                            } catch (Exception $e) {
                            }
                        }
                        if ($amount >= 250 && $UsuarioMandante->moneda == 'USD' && $UsuarioMandante->mandante == '8') {
                            try {
                                $message = "Casino *Usuario:* " . $UsuarioMandante->usuarioMandante . " - *Apuesta:* " . $UsuarioMandante->moneda . " " . $amount;
                            } catch (Exception $e) {
                            }
                        }
                        if ($amount >= 160613 && $UsuarioMandante->moneda == 'CRC') {
                            try {
                                $message = "Casino *Usuario:* " . $UsuarioMandante->usuarioMandante . " - *Apuesta:* " . $UsuarioMandante->moneda . " " . $amount;
                            } catch (Exception $e) {
                            }
                        }
                        if ($amount >= 5156 && $UsuarioMandante->moneda == 'MXN' && $UsuarioMandante->mandante == '6') {
                            try {
                                $message = "Casino *Usuario:* " . $UsuarioMandante->usuarioMandante . " - *Apuesta:* " . $UsuarioMandante->moneda . " " . $amount;
                            } catch (Exception $e) {
                            }
                        }
                        if ($amount >= 216281 && $UsuarioMandante->moneda == 'CLP') {
                            try {
                                $message = "Casino *Usuario:* " . $UsuarioMandante->usuarioMandante . " - *Apuesta:* " . $UsuarioMandante->moneda . " " . $amount;
                            } catch (Exception $e) {
                            }
                        }
                    }
                } catch (Exception $e) {
                }

                try {
                    if ($UsuarioMandante->usumandanteId == 16) {
                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . ((time() - $timeInit) * 1000) . 'ms3 ' . $transaccionApi->getTransaccionId() . "' '#virtualsoft-cron' > /dev/null & ");
                    }
                    if (((time() - $timeInit) * 1000) >= 2000 && $_ENV['enabledSlowIntegrations'] === true) {
                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' SLOW 13 " . ((time() - $timeInit) * 1000) . 'ms3 ' . $transaccionApi->getTransaccionId() . "' '#alertas-integraciones' > /dev/null & ");
                    }
                } catch (Exception $e) {
                }
            }

            if ($saldoFree != null && $saldoFree > 0) {
                $mensajesRecibidos = [];
                $array = [];

                $array["body"] = ':money_with_wings: ¡ :black_joker: Te has gastado ' . $saldoFree . ' ' . $UsuarioMandante->getMoneda() . ' de tu saldo gratis de casino :black_joker: ! :money_with_wings: ';

                array_push($mensajesRecibidos, $array);
                $data = array();
                $data["messages"] = $mensajesRecibidos;

                if (in_array($UsuarioMandante->mandante, array('0', 8, 6)) || true) {
                    $dataSend = $data;

                    try {
                    } catch (Exception $e) {
                    }
                }
            }

            $messageSlackTime.= $transaccionApi->getTransaccionId().' *#INTDC#24 '. date('Y-m-d H:i:s', $t = microtime(true)) . '.' . sprintf('%03d', ($t - floor($t)) * 1000) ;


            if(((time() - $timeInit) * 1000)>2000){
                exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" .$messageSlackTime  . "' '#provisional' > /dev/null & ");

            }
            $log = date("Y-m-d H:i:s") . "-----------T1--------------" . "\r\n";

            $respuesta->usuarioId = $UsuarioMandante->usumandanteId;
            $respuesta->moneda = $UsuarioMandante->moneda;
            $respuesta->usuario = "Usuario" . $UsuarioMandante->usumandanteId;
            $respuesta->saldo = $Balance;
            $respuesta->transaccionId = $TransjuegoLog_id;
            $respuesta->transaccionApi = $transaccionApi;


            $log = date("Y-m-d H:i:s") . "-----------TU--------------" . "\r\n";


            return $respuesta;
        } catch (Exception $e) {
            if ($_ENV['debug']) {
                print_r($e);
            }

            if ($_ENV["connectionGlobal"] != null && $_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"]->isBeginTransaction == 2) {
                ($_ENV["connectionGlobal"])->rollBack();
                if ($_ENV['debug']) {
                    print_r('entroooo2');
                }
            }

            if ($_ENV["connectionGlobal"] != null && $_ENV["connectionGlobal"] != '') {
                if (($_ENV["connectionGlobal"])->inTransaction()) {
                    ($_ENV["connectionGlobal"])->rollBack();
                }
            }
            if (strpos($e->getMessage(), 'INSERT INTO casino_transprovisional') !== false) {
                //  Si la transaccionId ha sido procesada, reportamos el error
                throw new Exception("Transaccion ya procesada", "10001");
            } else {
                throw $e;
            }
        }
    }

    /**
     * Realiza un crédito a un usuario en el sistema.
     *
     * @param UsuarioMandante $UsuarioMandante Objeto que representa al usuario mandante.
     * @param Producto $Producto Objeto que representa el producto asociado.
     * @param TransaccionApi $transaccionApi Objeto que contiene los datos de la transacción API.
     * @param boolean $isEndRound Indica si la ronda ha finalizado.
     * @param boolean $onlyOneWin Indica si solo se permite un único crédito ganador.
     * @param boolean $free Indica si el crédito es gratuito.
     * @param boolean $allowChangIfIsEnd Indica si se permiten cambios después de finalizar la ronda.
     *
     * @return object Respuesta con información del usuario, saldo y transacción.
     * @throws Exception Si ocurre algún error durante el proceso.
     */
    public function credit($UsuarioMandante, $Producto, $transaccionApi, $isEndRound, $onlyOneWin = false, $free = false, $allowChangIfIsEnd = true)
    {
        try {
            $timeInit = time();
            $log = date("Y-m-d H:i:s") . "-----------T1--------------" . "\r\n";

            $respuesta = json_decode(json_encode(array(
                "usuarioId" => "",
                "moneda" => "",
                "usuario" => "",
                "transaccionId" => "",
                "saldo" => "",
                "transaccionApi" => ""
            )));

            $creditAmount = $transaccionApi->getValor();


            //  Verificamos que el monto a creditar sea positivo
            if ($creditAmount < 0) {
                throw new Exception("No puede ser negativo el monto a debitar.", "10002");
            }


            //  Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante(), '', $UsuarioMandante->paisId);

            //  Agregamos Elementos a la Transaccion API
            $transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());


            if($UsuarioMandante->usumandanteId != 9179402) {

                //  Verificamos que la transaccionId no se haya procesado antes
                if ($transaccionApi->existsTransaccionIdAndProveedor("OK")) {
                    //  Si la transaccionId ha sido procesada, reportamos el error
                    throw new Exception("Transaccion ya procesada", "10001");
                }
            }
            //  Obtenemos la Transaccion Juego
            $TransaccionJuego = new TransaccionJuego("", $transaccionApi->getIdentificador());

            $log = date("Y-m-d H:i:s") . "-----------T2--------------" . "\r\n";

            if ($TransaccionJuego->getEstado() == 'I' && !$allowChangIfIsEnd) {
                //  Si la transaccion no es permitido para cambios despues de cerrado el ticket
                throw new Exception("El ticket ya esta cerrado", "10027");
            }

            // Obtenemos la Transaccion de la BD para crear y registrar el Debito con esta transaccion
            $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
            $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

            //  Obtenemos el ID de la TransaccionJuego
            $TransJuegoId = $TransaccionJuego->getTransjuegoId();

            //  Obtenemos el tipo de transacción dependiendo a betTypeID y si se le suma los creditos o no
            $sumaCreditos = false;
            $tipoTransaccion = "CREDIT";


            //  Actualizamos la Transaccion Juego con los respectivas actualizaciones
            $TransaccionJuego->setValorPremio($TransaccionJuego->getValorPremio() + $creditAmount);


            if ($free && $TransaccionJuego->getTipo() == 'NORMAL') {
                $TransaccionJuego->setTipo('FREESPIN');
            }
            if ($isEndRound) {
                if ($TransaccionJuego->getValorPremio() > 0) {
                    $TransaccionJuego->setPremiado("S");
                    $TransaccionJuego->setFechaPago(date('Y-m-d H:i:s', time()));
                    $sumaCreditos = true;
                }

                $TransaccionJuego->setEstado("I");
            }


            $log = date("Y-m-d H:i:s") . "-----------T4--------------" . "\r\n";


            $TransaccionJuego->update($Transaction);


            //  Obtenemos el mandante para verificar sus caracteristicas
            $Mandante = new Mandante($UsuarioMandante->mandante);


            //  Verificamos si el mandante es propio
            if ($Mandante->propio == "S") {
                //  Obtenemos nuestro Usuario y hacemos el debito
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);

                if ($Usuario->estado != "A") {
                }

                if ($creditAmount > 0) {
                    $sumaCreditos = true;
                }

                //  Si suma los creditos, hacemos el respectivo CREDIT
                if ($sumaCreditos) {
                    if ($creditAmount > 0) {
                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                        switch ($UsuarioPerfil->getPerfilId()) {
                            case "USUONLINE":

                                $freecashEnSaldoRecargas = false;

                                if ($Usuario->mandante == 14 && $TransaccionJuego->getTipo() == 'FREESPIN' && date('Y-m-d H:i:s') >= '2023-03-27 08:00:00' && false) {
                                    $freecashEnSaldoRecargas = true;
                                }
                                if (
                                    $Usuario->mandante == 0 && $Usuario->paisId == 2 && $TransaccionJuego->getTipo() == 'FREESPIN'
                                    && date('Y-m-d H:i:s') >= '2023-04-01 00:00:00'
                                    && date('Y-m-d H:i:s') <= '2023-04-02 23:59:59'
                                ) {
                                    $freecashEnSaldoRecargas = true;
                                }

                                if ($freecashEnSaldoRecargas) {
                                    $Usuario->credit($creditAmount, $Transaction);
                                } else {
                                    $Usuario->creditWin($creditAmount, $Transaction);
                                }


                                break;

                            case "MAQUINAANONIMA":
                                $Usuario->creditWin($creditAmount, $Transaction);

                                break;

                            case "PUNTOVENTA":
                            case "CAJERO":

                                $Usuario->creditWin(0, $Transaction);

                                break;
                        }
                    }
                }
            } else {
                try {
                    $ProdMandanteTipo = new ProdMandanteTipo("CASINO", $Mandante->mandante);
                    $data = array(
                        "userid" => $UsuarioMandante->usuarioMandante,
                        "sign" => $ProdMandanteTipo->siteKey,
                        "token" => $UsuarioMandante->tokenExterno,
                        "gamecode" => $ProductoMandante->prodmandanteId,
                        "amount" => $creditAmount,
                        "roundid" => $TransaccionJuego->getTransjuegoId(),
                        "transactionid" => 0
                    );

                    $TransaccionApiMandante = new TransaccionApiMandante();
                    $TransaccionApiMandante->setTransaccionId('');
                    $TransaccionApiMandante->setTipo("CREDIT");
                    $TransaccionApiMandante->setProveedorId($Producto->getProveedorId());
                    $TransaccionApiMandante->setTValue(json_encode($data));
                    $TransaccionApiMandante->setUsucreaId(intval((time() - $timeInit) * 1000));
                    $TransaccionApiMandante->setUsumodifId(0);
                    $TransaccionApiMandante->setValor($creditAmount);
                    $TransaccionApiMandante->setIdentificador($transaccionApi->getIdentificador());
                    $TransaccionApiMandante->setProductoId($ProductoMandante->prodmandanteId);
                    $TransaccionApiMandante->setUsuarioId($UsuarioMandante->getUsumandanteId());
                    $TransaccionApiMandante->setTransapiId(0);

                    $TransaccionApiMandanteMySqlDAO = new TransaccionApiMandanteMySqlDAO($Transaction);
                    $transapimandanteId = $TransaccionApiMandanteMySqlDAO->insert($TransaccionApiMandante);

                    $data["transactionid"] = $transapimandanteId;
                    $TransaccionApiMandante->setTValue(json_encode($data));

                    $result = $Mandante->sendRequest($ProdMandanteTipo->urlApi . "/credit", "POST", $data);

                    $result = json_decode(json_encode($result));

                    $TransaccionApiMandante->setRespuesta(json_encode($result));
                    $TransaccionApiMandante->setRespuestaCodigo(0);
                    $TransaccionApiMandante->setTransaccionId($result->transactionid);

                    $TransaccionApiMandanteMySqlDAO = new TransaccionApiMandanteMySqlDAO($Transaction);
                    $TransaccionApiMandanteMySqlDAO->update($TransaccionApiMandante);


                    if ($result == "") {
                        throw new Exception("La solicitud al mandante fue vacia ", "50002");
                    }


                    $balance = $result->balance;
                    $transactionIdMandante = $result->transactionid;
                    $error = strtolower($result->error);
                    $code = strtolower($result->code);


                    if ($error == "" || $error == 'true' || $error == '1') {
                        $this->convertErrorMandante('M' . $code, "Error en mandante");
                    }

                    if ($balance == "") {
                        throw new Exception("No coinciden ", "50001");
                    }

                    if ($transactionIdMandante == "") {
                        throw new Exception("No coinciden ", "50001");
                    }

                    $Balance = $balance;
                } catch (Exception $e) {
                    if ($_ENV['debug']) {
                        print_r($e);
                    }


                    $codeException = $e->getCode();
                    $messageException = $e->getMessage();

                    $TransaccionApiMandante->setRespuestaCodigo($codeException);
                    $TransaccionApiMandanteMySqlDAO = new TransaccionApiMandanteMySqlDAO($Transaction);
                    $TransaccionApiMandanteMySqlDAO->update($TransaccionApiMandante);
                    $TransaccionApiMandanteMySqlDAO->getTransaction()->commit();

                    $this->convertErrorMandante($codeException, $messageException);
                }
            }

            //  Creamos el respectivo Log de la transaccion Juego
            $TransjuegoLog = new TransjuegoLog();
            $TransjuegoLog->setTransjuegoId($TransJuegoId);
            $TransjuegoLog->setTransaccionId($transaccionApi->getTransaccionId());
            $TransjuegoLog->setTipo($tipoTransaccion);
            $TransjuegoLog->setTValue($transaccionApi->getTValue());
            $TransjuegoLog->setUsucreaId(intval((time() - $timeInit) * 1000));
            $TransjuegoLog->setUsumodifId(0);
            $TransjuegoLog->setValor($creditAmount);
            $TransjuegoLog->setProductoId($ProductoMandante->prodmandanteId);
            $TransjuegoLog->setProveedorId($Producto->getSubproveedorId());

            $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);

            $log = date("Y-m-d H:i:s") . "-----------T3--------------" . "\r\n";

            if ($onlyOneWin) {
                if (!$TransjuegoLog->isEqualsNewCredit()) {
                    // Si el numero de creditos es mayor al de los debitos sacamos error
                    throw new Exception("CREDIT MAYOR A DEBIT", "10014");
                }
            }

            $log = date("Y-m-d H:i:s") . "-----------T5--------------" . "\r\n";


            if ($Mandante->propio == "S") {
                //  Consultamos de nuevo el usuario para obtener el saldo
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);

                switch ($UsuarioPerfil->getPerfilId()) {
                    case "USUONLINE":
                        $Balance = $Usuario->getBalance();
                        break;

                    case "MAQUINAANONIMA":

                        $Balance = $Usuario->getBalance();
                        break;

                    case "PUNTOVENTA":
                        $PuntoVenta = new PuntoVenta("", $UsuarioMandante->usuarioMandante);

                        $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                        $SaldoJuego = $PuntoVenta->getCreditosBase();

                        $Balance = $SaldoJuego;
                        break;

                    case "CAJERO":
                        $PuntoVenta = new PuntoVenta("", $Usuario->puntoventaId);

                        $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                        $SaldoJuego = $PuntoVenta->getCreditosBase();

                        $Balance = $SaldoJuego;
                        break;
                }

                if ($UsuarioPerfil != '') {
                    if ($UsuarioPerfil->getPerfilId() == 'MAQUINAANONIMA') {
                        //  Enviamos el mensaje Websocket al Usuario para que actualice el saldo

                        $UsuarioSession = new UsuarioSession();
                        $rules = [];

                        array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                        array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json = json_encode($filtro);


                        $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                        $usuarios = json_decode($usuarios);

                        $usuariosFinal = [];

                        $requestsIds = array();
                        foreach ($usuarios->data as $key => $value) {
                            array_push($requestsIds, $value->{'usuario_session.request_id'});
                        }
                        foreach ($usuarios->data as $key => $value) {
                            //$data = $UsuarioMandante->getWSProfileSite($value->{'usuario_session.request_id'});
                            $data = $Usuario->getWSMessage($value->{'usuario_session.request_id'});

                            foreach ($requestsIds as $requestsId) {
                                $WebsocketUsuario = new WebsocketUsuario($requestsId, $data);
                                $WebsocketUsuario->sendWSMessage($value->{'usuario_session.tipo'});
                            }
                        }
                    }
                }
            }

            // Guardamos la Transaccion Api necesaria de estado OK
            $transaccionApi->setRespuestaCodigo("OK");
            $transaccionApi->setRespuesta('');
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO($Transaction);
            $TransaccionApiMySqlDAO->insert($transaccionApi);

            if ($TransaccionApiMandante != "" && $TransaccionApiMandante != null) {
                $TransaccionApiMandante->setTransapiId($transaccionApi->transapiId);
                $TransaccionApiMandanteMySqlDAO = new TransaccionApiMandanteMySqlDAO($TransaccionApiMySqlDAO->getTransaction());
                $TransaccionApiMandanteMySqlDAO->update($TransaccionApiMandante);
            }

            try {
                //Información de poker para guardar en la tabla transjuego_info el movimiento de un Credit
                if ($Producto->getExternoId() == "EvenBetPoker" || $Producto->getExternoId() == "ps") {
                    $datos = json_decode(json_decode($transaccionApi->getTValue()));

                    //  Obtenemos tournamentId dependiento del producto (EvenBet o PokerStars)
                    if ($Producto->getExternoId() == "ps") {
                        // Obtenemos tournamentId y rake para Playtech
                        $tournamentId = $datos->tournamentDetails->tournamentCode;
                        $rake = $datos->gameSessionClose->rake;
                    } else {
                        // Obtenemos tournamentId y rake para EvenBet
                        $tournamentId = $datos->tournamentId;
                        $rake = $datos->rake;
                        $rake = $rake / 1000;
                        $rake = $rake / 100;
                    }

                    if ($rake != 0) {
                        // Creamos el respectivo Log de la transaccion Juego
                        $TransjuegoInfo = new TransjuegoInfo();
                        $TransjuegoInfo->setProductoId($ProductoMandante->getProdmandanteId());
                        $TransjuegoInfo->setTransaccionId($transaccionApi->getTransaccionId());
                        $TransjuegoInfo->setTipo("RAKE");
                        $TransjuegoInfo->setDescripcion($rake);
                        $TransjuegoInfo->setDescripcionTxt($rake);
                        $TransjuegoInfo->setValor($creditAmount);
                        $TransjuegoInfo->setTransapiId($TransjuegoLog->transjuegologId);
                        $TransjuegoInfo->setUsucreaId(intval((time() - $timeInit) * 1000));
                        $TransjuegoInfo->setUsumodifId(0);
                        $TransjuegoInfo->setIdentificador($transaccionApi->getIdentificador());
                        if ($TransaccionJuego != null) {
                            $TransjuegoInfo->setTransjuegoId($TransaccionJuego->getTransjuegoId());
                        }

                        //$TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO($Transaction);
                        $TransjuegoInfo->insert($Transaction);
                    }

                    if ($tournamentId != 0) {
                        //  Creamos el respectivo Log de la transaccion Juego
                        $TransjuegoInfo = new TransjuegoInfo();
                        $TransjuegoInfo->setProductoId($ProductoMandante->prodmandanteId);
                        $TransjuegoInfo->setTransaccionId($transaccionApi->getTransaccionId());
                        $TransjuegoInfo->setTipo("CREDITPOKERTORNEO");
                        $TransjuegoInfo->setDescripcion(($tournamentId) . $creditAmount * 100);
                        $TransjuegoInfo->setDescripcionTxt(($tournamentId) . $creditAmount * 100);
                        $TransjuegoInfo->setValor($creditAmount);
                        $TransjuegoInfo->setTransapiId($TransjuegoLog->transjuegologId);
                        $TransjuegoInfo->setUsucreaId(intval((time() - $timeInit) * 1000));
                        $TransjuegoInfo->setUsumodifId(0);
                        $TransjuegoInfo->setIdentificador($transaccionApi->getIdentificador());
                        if ($TransaccionJuego != null) {
                            $TransjuegoInfo->setTransjuegoId($TransaccionJuego->getTransjuegoId());
                        }

                        $TransjuegoInfo->insert($Transaction);
                    }
                }
            } catch (Exception $e) {
            }


            // Commit de la transacción
            $Transaction->commit();


            if ($Mandante->propio == "S") {
                switch ($UsuarioPerfil->getPerfilId()) {
                    case "USUONLINE":
                        $H_user = $Usuario->usuarioId;
                        $isPV = '0';
                        $Movimiento = 'E';

                        $UsuarioHistorial = new UsuarioHistorial();
                        $UsuarioHistorial->setUsuarioId($H_user);
                        $UsuarioHistorial->setDescripcion('');
                        $UsuarioHistorial->setMovimiento($Movimiento);
                        $UsuarioHistorial->setUsucreaId(intval((time() - $timeInit) * 1000));
                        $UsuarioHistorial->setUsumodifId(0);
                        $UsuarioHistorial->setTipo(30);
                        $UsuarioHistorial->setValor($creditAmount);
                        $UsuarioHistorial->setExternoId($TransjuegoLog_id);

                        $UsuarioHistorial->setCustoms($Producto->productoId);

                        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO();
                        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, $isPV);
                        $UsuarioHistorialMySqlDAO->getTransaction()->commit();
                        break;
                }
            }


            $ConfigurationEnvironment = new ConfigurationEnvironment();

            if ($sumaCreditos) {
                $typeP = "CASINO";

                $Proveedor = new Proveedor($Producto->getProveedorId());

                if ($Proveedor->getTipo() == 'CASINO') {
                    $typeP = "CASINO";
                } elseif ($Proveedor->getTipo() == 'LIVECASINO') {
                    $typeP = "LIVECASINO";
                }


                try {
                    if (!$ConfigurationEnvironment->isDevelopment()) {
                        if ($creditAmount >= 1000 && $UsuarioMandante->moneda == 'PEN') {
                            try {
                                $message = "Casino *Usuario:* " . $UsuarioMandante->usuarioMandante . " - *Premio:* " . $UsuarioMandante->moneda . " " . $creditAmount;
                            } catch (Exception $e) {
                            }
                        }
                        if ($creditAmount >= 250 && $UsuarioMandante->moneda == 'USD' && $UsuarioMandante->mandante == '8') {
                            try {
                                $message = "Casino *Usuario:* " . $UsuarioMandante->usuarioMandante . " - *Premio:* " . $UsuarioMandante->moneda . " " . $creditAmount;
                            } catch (Exception $e) {
                            }
                        }
                        if ($creditAmount >= 160613 && $UsuarioMandante->moneda == 'CRC') {
                            try {
                                $message = "Casino *Usuario:* " . $UsuarioMandante->usuarioMandante . " - *Premio:* " . $UsuarioMandante->moneda . " " . $creditAmount;
                            } catch (Exception $e) {
                            }
                        }
                        if ($creditAmount >= 5156 && $UsuarioMandante->moneda == 'MXN' && $UsuarioMandante->mandante == '6') {
                            try {
                                $message = "Casino *Usuario:* " . $UsuarioMandante->usuarioMandante . " - *Premio:* " . $UsuarioMandante->moneda . " " . $creditAmount;
                            } catch (Exception $e) {
                            }
                        }
                        if ($creditAmount >= 216281 && $UsuarioMandante->moneda == 'CLP') {
                            try {
                                $message = "Casino *Usuario:* " . $UsuarioMandante->usuarioMandante . " - *Premio:* " . $UsuarioMandante->moneda . " " . $creditAmount;
                            } catch (Exception $e) {
                            }
                        }
                    }
                } catch (Exception $e) {
                }
            }


            if ($UsuarioMandante->mandante == "0") {
                try {
                    $useragent = $_SERVER['HTTP_USER_AGENT'];
                    $jsonServer = json_encode($_SERVER);
                    $serverCodif = base64_encode($jsonServer);


                    $ismobile = '';

                    if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
                        $ismobile = '1';
                    }
                    $iPod = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");
                    $iPhone = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
                    $iPad = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
                    $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
                    $webOS = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");

                    if ($iPod || $iPhone) {
                        $ismobile = '1';
                    } elseif ($iPad) {
                        $ismobile = '1';
                    } elseif ($Android) {
                        $ismobile = '1';
                    }
                } catch (Exception $e) {
                }
            }

            $respuesta->usuarioId = $UsuarioMandante->usumandanteId;
            $respuesta->moneda = $UsuarioMandante->moneda;
            $respuesta->usuario = "Usuario" . $UsuarioMandante->usumandanteId;
            $respuesta->saldo = $Balance;
            $respuesta->transaccionId = $TransjuegoLog_id;
            $respuesta->transaccionApi = $transaccionApi;


            $log = date("Y-m-d H:i:s") . "-----------TU--------------" . "\r\n";


            return $respuesta;
        } catch (Exception $e) {
            if ($_ENV['debug']) {
                print_r($e);
            }
            if ($_ENV["connectionGlobal"] != null && $_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"]->isBeginTransaction == 2) {
                ($_ENV["connectionGlobal"])->rollBack();
            }

            if ($_ENV["connectionGlobal"] != null && $_ENV["connectionGlobal"] != '') {
                if (($_ENV["connectionGlobal"])->inTransaction()) {
                    ($_ENV["connectionGlobal"])->rollBack();
                }
            }
            if (strpos($e->getMessage(), 'INSERT INTO casino_transprovisional') !== false) {
                //  Si la transaccionId ha sido procesada, reportamos el error
                throw new Exception("Transaccion ya procesada", "10001");
            } else {
                throw $e;
            }
        }
    }

    /**
     * Finaliza una ronda de juego actualizando el estado de la transacción y el saldo del usuario.
     *
     * @param TransaccionApi $transaccionApi Objeto que contiene los datos de la transacción API.
     * @param string $Estado Estado final de la transacción (por defecto 'I').
     *
     * @return object Respuesta con información del usuario, saldo y transacción.
     * @throws Exception Si ocurre algún error durante el proceso.
     */
    public function endRound($transaccionApi, $Estado = 'I')
    {
        try {
            $respuesta = json_decode(json_encode(array(
                "usuarioId" => "",
                "moneda" => "",
                "usuario" => "",
                "transaccionId" => "",
                "saldo" => "",
                "transaccionApi" => ""
            )));

            //  Obtenemos la Transaccion Juego
            $TransaccionJuego = new TransaccionJuego("", $transaccionApi->getIdentificador());


            //  Agregamos Elementos a la Transaccion API
            $transaccionApi->setProductoId($TransaccionJuego->getProductoId());
            $transaccionApi->setUsuarioId($TransaccionJuego->getUsuarioId());


            // Obtenemos la Transaccion de la BD para crear y registrar el Debito con esta transaccion
            $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
            $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

            //  Actualizamos la Transaccion Juego con los respectivas actualizaciones

            $TransaccionJuego->setEstado($Estado);

            $TransaccionJuego->update($Transaction);

            $UsuarioMandante = new UsuarioMandante($TransaccionJuego->usuarioId);


            //  Obtenemos el mandante para verificar sus caracteristicas
            $Mandante = new Mandante($UsuarioMandante->mandante);


            //  Verificamos si el mandante es propio
            if ($Mandante->propio == "S") {
                //  Obtenemos nuestro Usuario y hacemos el debito
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);

                if ($Usuario->estado != "A") {
                    throw new Exception("Usuario Inactivo", "20003");
                }
            } else {
                $Pais = new Pais($UsuarioMandante->paisId);
                $ProdMandanteTipo = new ProdMandanteTipo("CASINO", $Mandante->mandante);
                $data = array(
                    //"site" => $ProdMandanteTipo->siteId,
                    "sign" => $ProdMandanteTipo->siteKey,
                    "token" => $UsuarioMandante->tokenExterno
                );

                $result = $Mandante->sendRequest($ProdMandanteTipo->urlApi . "/balance", "POST", $data);

                $result = json_decode(json_encode($result));

                if ($result == "") {
                    throw new Exception("La solicitud al mandante fue vacia ", "50002");
                }

                $error = strtolower($result->error);
                $code = strtolower($result->code);

                if ($error == "" || $error == '1') {
                    $this->convertErrorMandante('M' . $code, "Error en mandante");
                    //throw new Exception("Error en mandante ", $code);
                }

                $userid = $result->player->userid;
                $Balance = $result->player->balance;
                $currency = $result->player->currency;

                if ($userid == "" || !is_numeric($userid)) {
                    throw new Exception("No coinciden ", "50001");
                }

                if ($Balance == "") {
                    throw new Exception("No coinciden ", "50001");
                }

                if ($currency == "") {
                    throw new Exception("No coinciden ", "50001");
                }
            }


            if ($Mandante->propio == "S") {
                //  Consultamos de nuevo el usuario para obtener el saldo
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);

                switch ($UsuarioPerfil->getPerfilId()) {
                    case "USUONLINE":
                        $Balance = $Usuario->getBalance();
                        break;

                    case "MAQUINAANONIMA":
                        $Balance = $Usuario->getBalance();
                        break;

                    case "PUNTOVENTA":
                        $PuntoVenta = new PuntoVenta("", $UsuarioMandante->usuarioMandante);

                        $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                        $SaldoJuego = $PuntoVenta->getCreditosBase();

                        //$Balance = $SaldoJuego + $SaldoRecargas;
                        $Balance = $SaldoJuego;
                        break;

                    case "CAJERO":
                        $PuntoVenta = new PuntoVenta("", $Usuario->puntoventaId);

                        $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                        $SaldoJuego = $PuntoVenta->getCreditosBase();

                        $Balance = $SaldoJuego;
                        break;
                }

                if ($UsuarioPerfil != '') {
                    if ($UsuarioPerfil->getPerfilId() == 'MAQUINAANONIMA') {
                        //  Enviamos el mensaje Websocket al Usuario para que actualice el saldo

                        $UsuarioSession = new UsuarioSession();
                        $rules = [];

                        array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                        array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json = json_encode($filtro);


                        $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                        $usuarios = json_decode($usuarios);

                        $usuariosFinal = [];

                        foreach ($usuarios->data as $key => $value) {
                            $data = $UsuarioMandante->getWSProfileSite($value->{'usuario_session.request_id'});

                            $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                            $WebsocketUsuario->sendWSMessage();
                        }
                    }
                }

                $ConfigurationEnvironment = new ConfigurationEnvironment();

                if ($ConfigurationEnvironment->isDevelopment() && false) {
                    $UsuarioToken = new UsuarioToken("", '1', $UsuarioMandante->getUsumandanteId());

                    $data = $UsuarioMandante->getWSProfileSite($UsuarioToken->getRequestId());
                    $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                    $WebsocketUsuario->sendWSMessage();
                }
            }

            //  Guardamos la Transaccion Api necesaria de estado OK
            $transaccionApi->setRespuestaCodigo("OK");
            $transaccionApi->setRespuesta('');
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO($Transaction);
            $TransaccionApiMySqlDAO->insert($transaccionApi);

            // Commit de la transacción
            $Transaction->commit();

            $respuesta->usuarioId = $UsuarioMandante->usumandanteId;
            $respuesta->moneda = $UsuarioMandante->moneda;
            $respuesta->usuario = "Usuario" . $UsuarioMandante->usumandanteId;
            $respuesta->saldo = $Balance;
            $respuesta->transaccionId = $transaccionApi->transapiId;
            $respuesta->transaccionApi = $transaccionApi;

            return $respuesta;
        } catch (Exception $e) {
            if ($_ENV['debug']) {
                print_r($e);
            }
            if ($_ENV["connectionGlobal"] != null && $_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"]->isBeginTransaction == 2) {
                ($_ENV["connectionGlobal"])->rollBack();
            }

            if ($_ENV["connectionGlobal"] != null && $_ENV["connectionGlobal"] != '') {
                if (($_ENV["connectionGlobal"])->inTransaction()) {
                    ($_ENV["connectionGlobal"])->rollBack();
                }
            }
            throw $e;
        }
    }

    /**
     * Realiza un rollback de una transacción de juego, actualizando el estado de la transacción
     * y el saldo del usuario según corresponda.
     *
     * @param UsuarioMandante $UsuarioMandante Objeto que representa al usuario mandante.
     * @param Proveedor $Proveedor Objeto que representa al proveedor del juego.
     * @param TransaccionApi $transaccionApi Objeto que contiene los datos de la transacción API.
     * @param boolean $validacionValorTicket Indica si se debe validar el valor del ticket (por defecto true).
     * @param string $TransaccionEspecifica Identificador de una transacción específica para el rollback (opcional).
     * @param boolean $allowChangIfIsEnd Permite cambios si la transacción ya está cerrada (por defecto true).
     * @param boolean $validacionValorTransaccion Indica si se debe validar el valor de la transacción (por defecto false).
     * @param boolean $AllowCreditTransaction Permite transacciones de crédito en el rollback (por defecto false).
     * @param boolean $CheckDeleteRound Verifica si la ronda debe ser eliminada (por defecto false).
     * @param string $estado Estado final de la transacción (por defecto 'I').
     *
     * @return object Respuesta con información del usuario, saldo y transacción.
     * @throws Exception Si ocurre algún error durante el proceso.
     */
    public function rollback($UsuarioMandante, $Proveedor, $transaccionApi, $validacionValorTicket = true, $TransaccionEspecifica = '', $allowChangIfIsEnd = true, $validacionValorTransaccion = false, $AllowCreditTransaction = false, $CheckDeleteRound = false, $estado = 'I')
    {
        $timeInit = time();
        try {
            $respuesta = json_decode(json_encode(array(
                "usuarioId" => "",
                "moneda" => "",
                "usuario" => "",
                "transaccionId" => "",
                "saldo" => "",
                "transaccionApi" => ""
            )));


            $transaccionIdARollback = '';

            if ($TransaccionEspecifica == '') {
                $transaccionIdARollback = explode("ROLLBACK", $transaccionApi->getTransaccionId())[1];
            } else {
                $transaccionIdARollback = $TransaccionEspecifica;
            }

            $transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());

            //  Verificamos que la transaccionId no se haya procesado antes
            if ($transaccionApi->existsTransaccionIdAndProveedor("OK")) {
                //  Si la transaccionId ha sido procesada, reportamos el error
                throw new Exception("Transaccion ya procesada", "10001");
            }

            $rollbackAmountOrig = $transaccionApi->getValor();
            $transaccionApi->setValor(0);

            // Creamos la Transaccion por el Juego
            $TransaccionJuego = new TransaccionJuego("", $transaccionApi->getIdentificador());
            $ProductoMandante = new ProductoMandante("", "", $TransaccionJuego->getProductoId());
            $Producto = new Producto($ProductoMandante->productoId);


            try {
                $TransaccionApi2 = new TransjuegoLog("", "", "", $transaccionIdARollback . '_' . $Producto->getSubproveedorId(), $Producto->getSubproveedorId());
                $jsonValue = json_decode($TransaccionApi2->getTValue());
                $valorTransaction = 0;

                if ($UsuarioMandante->getUsumandanteId() == '') {
                    $TransaccionJuego22 = new TransaccionJuego($TransaccionApi2->getTransjuegoId());

                    $transaccionApi->setUsuarioId($TransaccionJuego22->getUsuarioId());
                }

                if (strpos($TransaccionApi2->getTipo(), 'DEBIT') !== false) {
                    $valorTransaction = $TransaccionApi2->getValor();

                    if ($validacionValorTransaccion && $valorTransaction != $rollbackAmountOrig) {
                        throw new Exception("Valor ticket diferente al Rollback", "10003");
                    }
                } elseif (strpos($TransaccionApi2->getTipo(), 'CREDIT') !== false && $AllowCreditTransaction) {
                    $valorTransaction = $TransaccionApi2->getValor();

                    if ($validacionValorTransaccion && $valorTransaction != $rollbackAmountOrig) {
                        throw new Exception("Valor ticket diferente al Rollback", "10003");
                    }
                } else {
                    throw new Exception("Transaccion no es Debit", "10006");
                }
            } catch (Exception $e) {
                throw new Exception("Transaccion no existe", "10005");
            }

            try {
                $TransjuegoLogTax = new TransjuegoLog("", "", "", 'TXDB' . $transaccionIdARollback . '_' . $Producto->getSubproveedorId(), $Producto->getSubproveedorId());
                $valueTax = $TransjuegoLogTax->getValor();
            } catch (Exception $e) {
                $valueTax = 0;
            }

            $rollbackAmount = $TransaccionApi2->getValor();
            $rollbackAmountTax = $TransaccionApi2->getValor() + $valueTax;

            // Verificamos que el monto a creditar sea positivo
            if ($rollbackAmount < 0) {
                throw new Exception("No puede ser negativo el monto a debitar.", "10002");
            }

            $transaccionApi->setValor($TransaccionApi2->getValor());


            // Obtenemos la Transaccion de la BD para crear y registrar el Debito con esta transaccion
            $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
            $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

            if ($CheckDeleteRound) {
                if ($TransaccionJuego->getEstado() == "I") {
                    if (strpos($TransaccionJuego->getTransaccionId(), "DEL_DEL_") !== false) {
                        throw new Exception("La ronda ya ha sido finalizada", "30021");
                    }
                }
            }

            if ($TransaccionJuego->getEstado() == 'I' && !$allowChangIfIsEnd) {
                //  Si la transaccion no es permitido para cambios despues de cerrado el ticket
                throw new Exception("El ticket ya esta cerrado", "10027");
            }

            //  Verificamos que el valor del ticket sea igual al valor del Rollback
            if ($validacionValorTicket && $TransaccionJuego->getValorTicket() != $rollbackAmount) {
                throw new Exception("Valor ticket diferente al Rollback", "10003");
            }

            //  Obtenemos Mandante para verificar sus caracteristicas
            $Mandante = new Mandante($UsuarioMandante->mandante);
            $premiado = $TransaccionJuego->premiado;

            //  Verificamos si el mandante es Propio
            if ($Mandante->propio == "S") {
                //  Obtenemos el Usuario para hacerle el credito
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);

                //  Actualizamos Transaccion Juego
                $TransaccionJuego->setEstado($estado);
                if (strpos($TransaccionApi2->getTipo(), 'DEBIT') !== false) {
                    if (!in_array($UsuarioPerfil->getPerfilId(), ['PUNTOVENTA', 'CAJERO'])) {
                        $TransaccionJuego->setValorTicket(' valor_ticket - ' . $rollbackAmount);
                        $TransaccionJuego->setImpuesto(' impuesto - ' . $valueTax);
                    } else {
                        if ($TransaccionJuego->premioPagado == 'N' && $premiado == 'N') {
                            $TransaccionJuego->setValorPremio($TransaccionJuego->getValorPremio() + $rollbackAmountTax);
                            $TransaccionJuego->setPremiado("S");
                        } else {
                            throw new Exception("Ya cerrada", "10027");
                        }
                    }
                } elseif (strpos($TransaccionApi2->getTipo(), 'CREDIT') !== false) {
                    if (!in_array($UsuarioPerfil->getPerfilId(), ['PUNTOVENTA', 'CAJERO'])) {
                        $TransaccionJuego->setValorPremio(' valor_premio - ' . $rollbackAmount);
                    } else {
                        if ($TransaccionJuego->premioPagado == 'N' && $premiado == 'N') {
                            $TransaccionJuego->setValorPremio($TransaccionJuego->getValorPremio() + $rollbackAmountTax);
                            $TransaccionJuego->setPremiado("S");
                        } else {
                            throw new Exception("Ya cerrada", "10027");
                        }
                    }
                }
            } else {
                //  Actualizamos Transaccion Juego
                $TransaccionJuego->setEstado($estado);
                if (strpos($TransaccionApi2->getTipo(), 'DEBIT') !== false) {
                    $TransaccionJuego->setValorTicket(' valor_ticket - ' . $rollbackAmount);
                    $TransaccionJuego->setImpuesto(' impuesto - ' . $valueTax);
                } elseif (strpos($TransaccionApi2->getTipo(), 'CREDIT') !== false) {
                    $TransaccionJuego->setValorPremio(' valor_premio - ' . $rollbackAmount);
                }
            }

            $TransaccionJuego->update($Transaction);

            //  Obtenemos el Transaccion Juego ID
            $TransJuegoId = $TransaccionJuego->getTransjuegoId();

            //  Creamos el Log de Transaccion Juego
            $TransjuegoLog = new TransjuegoLog();
            $TransjuegoLog->setTransjuegoId($TransJuegoId);
            $TransjuegoLog->setTransaccionId($transaccionApi->getTransaccionId());
            $TransjuegoLog->setTipo("ROLLBACK");

            if (strpos($TransaccionApi2->getTipo(), 'CREDIT') !== false) {
                $rollbackAmount = -$rollbackAmount;
            }

            $TransjuegoLog->setTValue($transaccionApi->getTValue());
            $TransjuegoLog->setUsucreaId(intval((time() - $timeInit) * 1000));
            $TransjuegoLog->setUsumodifId(0);
            $TransjuegoLog->setValor($rollbackAmount);
            $TransjuegoLog->setProductoId($ProductoMandante->prodmandanteId);
            $TransjuegoLog->setProveedorId($Producto->getSubproveedorId());

            $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);

            //  Verificamos si el mandante es Propio
            if ($Mandante->propio == "S") {
                //  Obtenemos el Usuario para hacerle el credito
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);

                switch ($UsuarioPerfil->getPerfilId()) {
                    case "USUONLINE":

                        if (strpos($TransaccionApi2->getTipo(), 'CREDIT') !== false && $AllowCreditTransaction) {
                            $Usuario->debit($rollbackAmountTax, $Transaction, 1, true);
                        } else {
                            $Usuario->credit($rollbackAmountTax, $Transaction);
                        }
                        break;

                    case "MAQUINAANONIMA":

                        if (strpos($TransaccionApi2->getTipo(), 'CREDIT') !== false && $AllowCreditTransaction) {
                            $Usuario->debit($rollbackAmountTax, $Transaction, 1, true);
                        } else {
                            $Usuario->credit($rollbackAmountTax, $Transaction);
                        }
                        break;

                    case "PUNTOVENTA":
                    case "CAJERO":

                        if ($TransaccionJuego->premioPagado == 'N' && $premiado == 'N') {
                            $TransjuegoLog = new TransjuegoLog();
                            $TransjuegoLog->setTransjuegoId($TransJuegoId);
                            $TransjuegoLog->setTransaccionId('CREDIT' . $transaccionApi->getTransaccionId());
                            $TransjuegoLog->setTipo('CREDIT');
                            $TransjuegoLog->setTValue($rollbackAmountTax);
                            $TransjuegoLog->setUsucreaId(intval((time() - $timeInit) * 1000));
                            $TransjuegoLog->setUsumodifId(0);
                            $TransjuegoLog->setValor($rollbackAmountTax);
                            $TransjuegoLog->setProductoId($ProductoMandante->prodmandanteId);
                            $TransjuegoLog->setProveedorId($Producto->getSubproveedorId());

                            $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);

                            if ($UsuarioPerfil->getPerfilId() == 'CAJERO') {
                                $PuntoVenta = new PuntoVenta("", $Usuario->puntoventaId);
                            } else {
                                $PuntoVenta = new PuntoVenta("", $Usuario->usuarioId);
                            }

                            $Usuario->creditWin(0, $Transaction);
                        } else {
                            throw new Exception("Ya cerrada", "10027");
                        }
                        break;
                }


                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                switch ($UsuarioPerfil->getPerfilId()) {
                    case "USUONLINE":
                        $Balance = $Usuario->getBalance();
                        break;

                    case "MAQUINAANONIMA":

                        $Balance = $Usuario->getBalance();
                        break;

                    case "PUNTOVENTA":
                        $PuntoVenta = new PuntoVenta("", $UsuarioMandante->usuarioMandante);

                        $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                        $SaldoJuego = $PuntoVenta->getCreditosBase();

                        $Balance = $SaldoJuego;
                        break;

                    case "CAJERO":
                        $PuntoVenta = new PuntoVenta("", $Usuario->puntoventaId);

                        $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                        $SaldoJuego = $PuntoVenta->getCreditosBase();

                        $Balance = $SaldoJuego;
                        break;
                }
            } else {
                try {
                    $TransaccionApiMandante = new TransaccionApiMandante("", "", "", "", $TransaccionApi2->getTransapiId());

                    $ProdMandanteTipo = new ProdMandanteTipo("CASINO", $Mandante->mandante);
                    $data = array(
                        "sign" => $ProdMandanteTipo->siteKey,
                        "token" => $UsuarioMandante->tokenExterno,
                        "amount" => $rollbackAmount,
                        "transactionid" => 0,
                        "transactionRollback" => $TransaccionApiMandante->getTransapimandanteId()
                    );

                    $TransaccionApiMandante = new TransaccionApiMandante();
                    $TransaccionApiMandante->setTransaccionId('');
                    $TransaccionApiMandante->setTipo("ROLLBACK");
                    $TransaccionApiMandante->setProveedorId($Proveedor->getProveedorId());
                    $TransaccionApiMandante->setTValue(json_encode($data));
                    $TransaccionApiMandante->setUsucreaId(0);
                    $TransaccionApiMandante->setUsumodifId(0);
                    $TransaccionApiMandante->setValor($rollbackAmount);
                    $TransaccionApiMandante->setIdentificador($transaccionApi->getIdentificador());
                    $TransaccionApiMandante->setProductoId(0);
                    $TransaccionApiMandante->setUsuarioId($UsuarioMandante->getUsumandanteId());
                    $TransaccionApiMandante->setTransapiId(0);

                    $TransaccionApiMandanteMySqlDAO = new TransaccionApiMandanteMySqlDAO($Transaction);
                    $transapimandanteId = $TransaccionApiMandanteMySqlDAO->insert($TransaccionApiMandante);

                    $data["transactionid"] = $transapimandanteId;
                    $TransaccionApiMandante->setTValue(json_encode($data));

                    $result = $Mandante->sendRequest($ProdMandanteTipo->urlApi . "/rollback", "POST", $data);

                    $result = json_decode(json_encode($result));

                    $TransaccionApiMandante->setRespuesta(json_encode($result));
                    $TransaccionApiMandante->setRespuestaCodigo(0);
                    $TransaccionApiMandante->setTransaccionId($result->transactionid);

                    $TransaccionApiMandanteMySqlDAO = new TransaccionApiMandanteMySqlDAO();
                    $TransaccionApiMandanteMySqlDAO->update($TransaccionApiMandante);


                    if ($result == "") {
                        throw new Exception("La solicitud al mandante fue vacia ", "50002");
                    }


                    $balance = $result->balance;
                    $transactionIdMandante = $result->transactionid;
                    $error = strtolower($result->error);
                    $code = strtolower($result->code);


                    if ($error == "" || $error == '1') {
                        $this->convertErrorMandante('M' . $code, "Error en mandante");
                    }

                    if ($balance == "") {
                        throw new Exception("No coinciden ", "50001");
                    }

                    if ($transactionIdMandante == "") {
                        throw new Exception("No coinciden ", "50001");
                    }

                    $Balance = $balance;
                } catch (Exception $e) {
                    $codeException = $e->getCode();
                    $messageException = $e->getMessage();

                    $TransaccionApiMandante->setRespuestaCodigo($codeException);
                    $TransaccionApiMandanteMySqlDAO = new TransaccionApiMandanteMySqlDAO();
                    $TransaccionApiMandanteMySqlDAO->update($TransaccionApiMandante);
                    $TransaccionApiMandanteMySqlDAO->getTransaction()->commit();


                    $this->convertErrorMandante($codeException, $messageException);
                }
            }

            //  Verificamos si el mandante es Propio
            if ($Mandante->propio == "S") {
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);

                switch ($UsuarioPerfil->getPerfilId()) {
                    case "USUONLINE":
                        $Balance = $Usuario->getBalance();
                        break;

                    case "MAQUINAANONIMA":

                        $Balance = $Usuario->getBalance();
                        break;

                    case "PUNTOVENTA":
                        $PuntoVenta = new PuntoVenta("", $UsuarioMandante->usuarioMandante);

                        $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                        $SaldoJuego = $PuntoVenta->getCreditosBase();

                        $Balance = $SaldoJuego;
                        break;

                    case "CAJERO":
                        $PuntoVenta = new PuntoVenta("", $Usuario->puntoventaId);

                        $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                        $SaldoJuego = $PuntoVenta->getCreditosBase();

                        $Balance = $SaldoJuego;
                        break;
                }
            }

            //  Guardamos la Transaccion Api necesaria de estado OK
            $transaccionApi->setRespuestaCodigo("OK");
            $transaccionApi->setRespuesta('');
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO($Transaction);
            $TransaccionApiMySqlDAO->insert($transaccionApi);

            if ($TransaccionApiMandante != "" && $TransaccionApiMandante != null) {
                $TransaccionApiMandante->setTransapiId($transaccionApi->transapiId);
                $TransaccionApiMandanteMySqlDAO = new TransaccionApiMandanteMySqlDAO($TransaccionApiMySqlDAO->getTransaction());
                $TransaccionApiMandanteMySqlDAO->update($TransaccionApiMandante);
            }


            // Commit de la transacción
            $Transaction->commit();

            if ($Mandante->propio == "S") {
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);

                switch ($UsuarioPerfil->getPerfilId()) {
                    case "USUONLINE":
                        $UsuarioHistorial = new UsuarioHistorial();
                        $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                        $UsuarioHistorial->setDescripcion('');
                        $UsuarioHistorial->setMovimiento('C');
                        $UsuarioHistorial->setUsucreaId(0);
                        $UsuarioHistorial->setUsumodifId(0);
                        $UsuarioHistorial->setTipo(30);
                        $UsuarioHistorial->setValor($rollbackAmount);
                        $UsuarioHistorial->setExternoId($TransjuegoLog_id);

                        $UsuarioHistorial->setCustoms($Producto->productoId);

                        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO();
                        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);
                        $UsuarioHistorialMySqlDAO->getTransaction()->commit();
                        break;
                }
            }

            $respuesta->usuarioId = $UsuarioMandante->usumandanteId;
            $respuesta->moneda = $UsuarioMandante->moneda;
            $respuesta->usuario = "Usuario" . $UsuarioMandante->usumandanteId;
            $respuesta->saldo = $Balance;
            $respuesta->transaccionId = $TransjuegoLog_id;
            $respuesta->transaccionApi = $transaccionApi;


            return $respuesta;
        } catch (Exception $e) {
            if ($_ENV['debug']) {
                print_r($e);
            }
            if ($_ENV["connectionGlobal"] != null && $_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"]->isBeginTransaction == 2) {
                ($_ENV["connectionGlobal"])->rollBack();
            }

            if ($_ENV["connectionGlobal"] != null && $_ENV["connectionGlobal"] != '') {
                if (($_ENV["connectionGlobal"])->inTransaction()) {
                    ($_ENV["connectionGlobal"])->rollBack();
                }
            }
            if (strpos($e->getMessage(), 'INSERT INTO casino_transprovisional') !== false) {
                //  Si la transaccionId ha sido procesada, reportamos el error
                throw new Exception("Transaccion ya procesada", "10001");
            } else {
                throw $e;
            }
        }
    }

    /**
     * Genera y devuelve el HTML del juego para ser mostrado en el contenedor.
     *
     * @param string $in_app Indica si el juego se ejecuta dentro de una aplicación específica.
     *
     * @return void
     */
    function getGameHtml($in_app = '')
    {
        header("Content-Type: text/html; charset=utf-8");

        try {
            $Mandante = new Mandante($this->partnerid);
            $ProductoMandante = new ProductoMandante("", "", $this->gameid);

            if ($ProductoMandante->mandante != $Mandante->mandante) {
                throw new Exception("Juego no disponible ", "10000");
            }


            $Producto = new Producto($ProductoMandante->productoId);
            $Proveedor = new Proveedor($Producto->getProveedorId());

            switch ($Mandante->mandante) {
                case '0':
                    $bgCasino = 'https://images.doradobet.com/productos/casino/casino-background.jpg';
                    break;

                case '3':
                    $bgCasino = 'https://images.virtualsoft.tech/site/miravalle/bgCasino.jpg';
                    break;

                default:
                    $bgCasino = '';
                    break;
            }


            $URL = $this->getURL();
            $proveedor = $URL->proveedor;

            $mode = $this->mode;
            $isMobile = $this->isMobile;

            $proveedor = $Proveedor->getAbreviado();
            $provider = $Proveedor->getAbreviado();

            if ($provider != null) {
                if ($provider === "INB") {
                }


                if ($provider == "EZZG") {
                    if ($isMobile == "true" && $in_app != '1') {
                        //echo '<script>parent.parent.location.href = "' . $URL . '";</script>';
                        echo '<script>var a = document.createElement(\'a\');
a.href="' . $URL . '";
a.target = \'_top\';

a.click();
</script>
';
                    }
                }

                if ($provider == "GDR") {
                    $ConfigurationEnvironment = new ConfigurationEnvironment();

                    if ($ConfigurationEnvironment->isDevelopment()) {
                        if ($isMobile == "true") {
                            echo '<div id="golden-race-mobile-app"></div>
<script src="https://test-virtual.golden-race.net/mobile-v2/golden-race-mobile-loader.js" id="golden-race-mobile-loader"></script>
<script>
    document.addEventListener(\'DOMContentLoaded\', function() {
        var grLoader = grMobileLoader({
            onlineHash:      "' . $URL->loginHash . '"// Credentials for external API login.
        });
     });
</script>';
                        } else {
                            /*    echo '
                         <div id="golden-race-online-app"></div>
                          <div id="golden-race-app"></div>


                  <script src="https://virtual.golden-race.net/web-v2/golden-race-online-loader.js" id="golden-race-online-loader"></script>
                <!-- <script src=\'https://test-virtual.golden-race.net/web-v2/loader.js\' id=\'golden-race-loader\'></script>
                 --><script>
                document.addEventListener(\'DOMContentLoaded\', function () {
                    var loader = grOnlineLoader({
                        onlineHash: "' . $URL->loginHash . '"
                    });
                });


                </script>
                ';*/

                            if ($URL->play_for_fun == true) {
                                echo '
               <div id="golden-race-desktop-app"></div>


       <script src="https://test-virtual.golden-race.net/desktop-v3/default/golden-race-desktop-loader.js" id="golden-race-desktop-loader"></script>
     <!-- <script src=\'https://test-virtual.golden-race.net/web-v2/loader.js\' id=\'golden-race-loader\'></script>
      --><script>
     document.addEventListener(\'DOMContentLoaded\', function () {
         var loader = grDesktopLoader({
             hwId: "cf4386e2-9460-423d-aa34-98dda0b90149"
         });
     });


     </script>
     ';
                            } else {
                                echo '
               <div id="golden-race-desktop-app"></div>


       <script src="https://test-virtual.golden-race.net/desktop-v3/default/golden-race-desktop-loader.js" id="golden-race-desktop-loader"></script>
     <!-- <script src=\'https://test-virtual.golden-race.net/web-v2/loader.js\' id=\'golden-race-loader\'></script>
      --><script>
     document.addEventListener(\'DOMContentLoaded\', function () {
         var loader = grDesktopLoader({
             onlineHash: "' . $URL->loginHash . '"
         });
     });


     </script>
     ';
                            }
                        }
                    } else {
                        if ($isMobile == "true") {
                            if ($URL->play_for_fun == true) {
                                echo '<div id="golden-race-mobile-app"></div>
<script src="https://virtual.golden-race.net/mobile-v2/golden-race-mobile-loader.js" id="golden-race-mobile-loader"></script>
<script>
    document.addEventListener(\'DOMContentLoaded\', function() {
        var grLoader = grMobileLoader({
             hwId: "cf4386e2-9460-423d-aa34-98dda0b90149"
        });
     });
</script>';
                            } else {
                                echo '<div id="golden-race-mobile-app"></div>
<script src="https://virtual.golden-race.net/mobile-v2/golden-race-mobile-loader.js" id="golden-race-mobile-loader"></script>
<script>
    document.addEventListener(\'DOMContentLoaded\', function() {
        var grLoader = grMobileLoader({
            onlineHash:      "' . $URL->loginHash . '"// Credentials for external API login.
        });
     });
</script>';
                            }
                        } else {
                            /*    echo '
                         <div id="golden-race-online-app"></div>
                          <div id="golden-race-app"></div>


                  <script src="https://virtual.golden-race.net/web-v2/golden-race-online-loader.js" id="golden-race-online-loader"></script>
                <!-- <script src=\'https://test-virtual.golden-race.net/web-v2/loader.js\' id=\'golden-race-loader\'></script>
                 --><script>
                document.addEventListener(\'DOMContentLoaded\', function () {
                    var loader = grOnlineLoader({
                        onlineHash: "' . $URL->loginHash . '"
                    });
                });


                </script>
                ';*/

                            if ($URL->play_for_fun == true) {
                                echo '
          <div id="golden-race-desktop-app"></div>
 
 
  <script src="https://virtual.golden-race.net/desktop-v3/default/golden-race-desktop-loader.js" id="golden-race-desktop-loader"></script>
<!-- <script src=\'https://test-virtual.golden-race.net/web-v2/loader.js\' id=\'golden-race-loader\'></script>
 --><script>
document.addEventListener(\'DOMContentLoaded\', function () {
    var loader = grDesktopLoader({
        hwId: "cf4386e2-9460-423d-aa34-98dda0b90149"
    });
});


</script>
';
                            } else {
                                echo '
          <div id="golden-race-desktop-app"></div>
 
 
  <script src="https://virtual.golden-race.net/desktop-v3/default/golden-race-desktop-loader.js" id="golden-race-desktop-loader"></script>
<!-- <script src=\'https://test-virtual.golden-race.net/web-v2/loader.js\' id=\'golden-race-loader\'></script>
 --><script>
document.addEventListener(\'DOMContentLoaded\', function () {
    var loader = grDesktopLoader({
        onlineHash: "' . $URL->loginHash . '",
        language:"es-ES"
    });
});


</script>
';
                            }
                        }
                    }
                } elseif ($provider == "ITN") {
                    //$srcItn ='https://sports-itainment.biahosted.com/StaticResources/betinactionApi.js';
                    $srcItn = 'https://sb1client-altenar.biahosted.com/static/AltenarSportsbook.js';
                    if ($isMobile) {
                        //$srcItn ='https://sports-itainment.biahosted.com/StaticResources/betinactionApi.js';
                        $srcItn = 'https://sb1client-altenar.biahosted.com/static/AltenarSportsbook.js';
                    }
                    ?>
                    <link rel="stylesheet" href="https://doradobet.com/assets/css/custom/custom2.css">
                    <style>
                        * {
                            margin: 0;
                            padding: 0;
                            color: white;
                            text-decoration: initial;
                        }

                        menuvirtual .menuWrap ul li.active {
                            background: #d1b004;
                        }

                        menuvirtual .menuWrap ul li.active span {
                            color: white;
                        }

                        menuvirtual .menuWrap ul li:hover span {
                        }

                        menuvirtual .menuWrap ul li {
                            cursor: pointer;
                            background: rgb(255, 255, 255);
                            display: inline-flex;
                        }


                        menuvirtual .menuWrap ul li span {
                            color: #79680c;
                        }

                        menuvirtual {
                            width: 100%;
                        }

                        svg {
                            fill: #d2b100;
                        }


                        menuvirtual .menuWrap ul li.active svg {
                            fill: white;
                            margin-right: 5px;
                        }
                    </style>
                    <div id="virtual-wrapper" class="">
                        <div id="virtual-content"><!--
                <menuvirtual class="menuvirtual">
                    <div class="LogoLine">
                        <div class="menuWrap">
                            <div class="topmenuvirtual">
                                <ul>
                                    <li class="main-item menu-home active vfwc" onclick='showVirtual("vfwc")'><svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;height: 20px;" xml:space="preserve">
<g>
    <g transform="translate(1 1)">
        <path style="fill:#F57C00;" d="M365.933,118.467c0,61.267-49.667,110.933-110.933,110.933c-4.278,0.004-8.553-0.252-12.8-0.768
			c-55.837-6.605-97.902-53.939-97.902-110.165S186.363,14.906,242.2,8.301c4.247-0.516,8.522-0.772,12.8-0.768
			c29.421,0,57.638,11.688,78.442,32.492S365.933,89.045,365.933,118.467z"></path>
        <path style="fill:#FF9801;" d="M340.333,118.467c-0.014,56.305-42.205,103.669-98.133,110.165
			c-55.837-6.605-97.902-53.939-97.902-110.165S186.363,14.906,242.2,8.301c10.493,1.166,20.757,3.869,30.464,8.021
			c37.788,15.99,63.656,51.541,67.243,92.416C340.163,111.981,340.333,115.224,340.333,118.467z"></path>
        <path style="fill:#FF5722;" d="M338.627,194.243c4.582-8.121,4.386-18.092-0.512-26.027c-3.917-6.373-10.866-10.252-18.347-10.24
			c-4.618-0.012-9.113,1.487-12.8,4.267c-0.085,0-0.085,0.085-0.171,0.085C291.774,173.226,273.55,178.811,255,178.2
			c-4.222,0.012-8.44-0.244-12.629-0.768h-0.427c-14.027-1.502-27.4-6.716-38.741-15.104c-7.605-5.785-18.127-5.82-25.771-0.085
			c-2.206,1.627-4.087,3.653-5.547,5.973c-4.929,7.926-5.126,17.913-0.512,26.027c23.893,41.899,89.259,177.579-10.24,257.024
			h187.733C249.368,371.821,314.733,236.141,338.627,194.243z"></path>
        <path style="fill:#D84315;" d="M348.867,451.267h-25.6c-99.499-79.445-34.133-215.125-10.24-257.024
			c4.582-8.121,4.386-18.092-0.512-26.027c-1.459-2.32-3.341-4.346-5.547-5.973c3.687-2.78,8.182-4.278,12.8-4.267
			c7.481-0.012,14.43,3.867,18.347,10.24c4.898,7.935,5.094,17.905,0.512,26.027C314.733,236.141,249.368,371.821,348.867,451.267z"></path>
        <circle style="fill:#FFEB3A;" cx="255" cy="144.067" r="34.133"></circle>
        <path style="fill:#FDD834;" d="M365.592,110.616c-8.607,0.729-17.276,0.095-25.685-1.877
			c-3.587-40.875-29.455-76.426-67.243-92.416c-0.427-3.328-0.512-6.059-0.597-7.509C323.159,16.8,361.956,59.031,365.592,110.616z"></path>
        <g>
            <path style="fill:#FFEB3A;" d="M339.907,108.739c-34.219-8.704-21.675-43.179-42.24-50.005
				c-18.261-6.059-23.467-29.44-25.003-42.411C310.452,32.312,336.32,67.864,339.907,108.739z"></path>
            <path style="fill:#FFEB3A;" d="M206.189,72.045c-4.063,1.083-8.008,2.569-11.776,4.437c-3.784,2.247-6.905,5.456-9.045,9.301
				c-1.365,2.645-2.304,5.547-3.755,8.192c-4.201,5.922-11.309,9.067-18.517,8.192c-5.904-0.101-11.8-0.499-17.664-1.195
				c6.157-38.367,31.916-70.728,67.925-85.333c3.227,9.747,5.681,19.734,7.339,29.867C223.573,56.789,217.239,68.376,206.189,72.045
				z"></path>
        </g>
        <path style="fill:#FFB301;" d="M365.933,469.699v32.768H144.067v-32.768c0.019-10.172,8.26-18.413,18.432-18.432h185.003
			C357.673,451.285,365.914,459.527,365.933,469.699z"></path>
        <path style="fill:#FEC108;" d="M340.333,469.699v32.768H144.067v-32.768c0.019-10.172,8.26-18.413,18.432-18.432h159.403
			C332.073,451.285,340.314,459.527,340.333,469.699z"></path>
    </g>
    <g>
        <path d="M145.067,512h221.867c4.713,0,8.533-3.82,8.533-8.533v-34.133c-0.054-12.719-9.438-23.469-22.033-25.242
			c-67.567-55.27-61.594-126.251-41.207-180.582c0.816-1.143,1.332-2.472,1.502-3.866c9.741-24.236,22.38-47.204,37.641-68.403
			c0.068-0.077,0.171-0.102,0.239-0.188l2.722-3.669c13.808-19.953,21.183-43.652,21.137-67.917c0-2.714-0.213-5.385-0.393-8.064
			c0.005-0.091,0.005-0.182,0-0.273c0-0.085-0.051-0.154-0.06-0.239c-3.847-54.779-44.643-99.829-98.773-109.073
			c-1.11-0.421-2.296-0.605-3.482-0.538h-0.051C267.175,0.466,261.592,0.039,256,0c-58.525,0.013-108.401,42.47-117.76,100.241
			c-0.092,0.246-0.172,0.497-0.239,0.751c0,0.205,0.051,0.384,0,0.58c-0.932,5.92-1.423,11.901-1.468,17.894
			c-0.025,24.41,7.446,48.239,21.402,68.267l2.458,3.311c0.06,0.077,0.145,0.102,0.205,0.179
			c16.026,22.263,49.792,75.785,52.736,133.973v7.603c0.044,0.313,0.107,0.624,0.188,0.93
			c0.008,43.371-20.338,84.232-54.955,110.362c-12.595,1.772-21.979,12.523-22.033,25.242v34.133
			C136.533,508.18,140.354,512,145.067,512z M357.009,103.253c-27.102-0.589-31.13-12.476-35.635-26.069
			c-3.132-9.446-7.031-21.205-20.002-25.532c-12.006-4.019-16.725-19.789-18.577-30.942c38.858,10.468,67.935,42.796,74.24,82.543
			H357.009z M183.612,47.078c7.454-7.445,16.008-13.702,25.361-18.551c1.833,6.296,3.258,12.704,4.267,19.183
			c1.289,9.071-1.664,14.874-8.653,17.203l-1.818,0.572c-4.054,1.129-7.976,2.688-11.699,4.651
			c-5.169,3.065-9.417,7.466-12.297,12.74c-0.725,1.408-1.357,2.884-1.988,4.361c-0.483,1.221-1.027,2.417-1.63,3.584
			c-1.903,3.413-7.68,3.959-10.948,3.814c-2.415,0-4.881-0.154-7.39-0.307C161.3,76.44,170.562,60.108,183.612,47.078z
			 M230.11,334.259c0.14-0.477,0.237-0.965,0.29-1.459v-93.867c0-4.713-3.821-8.533-8.533-8.533c-4.713,0-8.533,3.821-8.533,8.533
			v14.063c-10.173-25.685-23.425-50.041-39.467-72.533c-0.691-0.853-1.246-1.613-1.707-2.295l-0.529-0.725l-0.068-0.06
			c-11.717-17.039-17.981-37.236-17.963-57.916c0-2.765,0.188-5.495,0.401-8.226c3.413,0.247,6.724,0.41,9.984,0.461h0.606
			c10.195,0.892,20.035-3.98,25.506-12.629c0.898-1.692,1.707-3.429,2.423-5.205c0.452-1.084,0.905-2.159,1.306-2.953
			c1.369-2.454,3.351-4.512,5.751-5.973c2.646-1.319,5.416-2.373,8.269-3.149l2.039-0.64c15.052-4.852,23.864-20.441,20.258-35.84
			c-1.228-7.893-2.993-15.694-5.282-23.347c10.064-3.232,20.568-4.885,31.138-4.898c3.055,0,6.076,0.179,9.088,0.444
			c1.638,15.292,7.799,42.598,30.882,50.347c4.429,1.476,6.118,5.41,9.207,14.711c4.983,15.027,12.407,37.239,53.222,37.751
			c-0.098,20.355-6.302,40.212-17.809,57.003c0,0.051-0.102,0.077-0.145,0.128l-0.529,0.725c-0.503,0.683-1.058,1.442-2.048,2.688
			c-16.046,22.498-29.285,46.87-39.424,72.576c-12.16,16.102-50.978,72.721-50.978,139.093c0,4.713,3.821,8.533,8.533,8.533
			s8.533-3.82,8.533-8.533c0.542-27.555,6.887-54.686,18.62-79.625c-6.875,48.214,9.667,96.815,44.527,130.825H184.32
			C213.952,415.067,230.508,375.486,230.11,334.259z M153.6,469.333c0-4.713,3.821-8.533,8.533-8.533h187.733
			c4.713,0,8.533,3.821,8.533,8.533v25.6H153.6V469.333z"></path>
        <path d="M238.933,469.333h-17.067c-4.713,0-8.533,3.821-8.533,8.533s3.82,8.533,8.533,8.533h17.067
			c4.713,0,8.533-3.82,8.533-8.533S243.646,469.333,238.933,469.333z"></path>
        <path d="M307.2,469.333h-34.133c-4.713,0-8.533,3.821-8.533,8.533s3.821,8.533,8.533,8.533H307.2c4.713,0,8.533-3.82,8.533-8.533
			S311.913,469.333,307.2,469.333z"></path>
        <path d="M331.947,148.881c1.363-2.729,1.166-5.977-0.515-8.521c-1.682-2.544-4.593-3.999-7.637-3.814s-5.759,1.978-7.122,4.707
			c-5.115,8.462-12.44,15.368-21.188,19.977c7.366-17.839,1.84-38.401-13.475-50.144c-15.316-11.743-36.607-11.743-51.923,0
			s-20.841,32.305-13.475,50.144c-8.766-4.626-16.104-11.557-21.222-20.045c-1.372-2.724-4.093-4.509-7.138-4.683
			c-3.045-0.174-5.951,1.29-7.625,3.84c-1.673,2.55-1.859,5.799-0.487,8.523c0.768,1.604,20.028,38.869,75.861,38.869
			S331.093,150.468,331.947,148.881z M230.4,145.067c0-14.139,11.462-25.6,25.6-25.6c14.138,0,25.6,11.462,25.6,25.6
			c0,14.138-11.462,25.6-25.6,25.6C241.861,170.667,230.4,159.205,230.4,145.067z"></path>
    </g>
</g>
</svg><span
                                                class="menu-item_Center">  Copa del Mundo 2018</span></li>
                                    <li class="main-item menu-home vfec" onclick='showVirtual("vfec")'><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;height: 20px;" xml:space="preserve">
<g>
    <rect x="408.122" y="52.064" style="fill:#D4E4AC;" width="24.018" height="48.036"></rect>
    <rect x="79.86" y="52.064" style="fill:#D4E4AC;" width="24.018" height="48.036"></rect>
</g>
                                            <rect x="223.979" y="276.241" style="fill:#E2804F;" width="64.052" height="24.018"></rect>
                                            <rect x="239.984" y="300.259" style="fill:#F2BF7E;" width="32.025" height="24.018"></rect>
                                            <rect x="199.961" y="428.353" style="fill:#D4E4AC;" width="112.088" height="24.018"></rect>
                                            <path style="fill:#F2BF7E;" d="M336.062,476.395H175.938l0,0c0-13.265,10.754-24.019,24.019-24.019h112.088
	C325.309,452.377,336.062,463.13,336.062,476.395L336.062,476.395z"></path>
                                            <polyline style="fill:#D4E4AC;" points="279.935,252.58 279.935,276.515 232.065,276.515 232.065,252.58 "></polyline>
                                            <rect x="95.875" y="36.049" style="fill:#E2804F;" width="320.25" height="24.018"></rect>
                                            <path style="fill:#F2BF7E;" d="M407.935,100.1c0,84.014-67.921,152.119-151.935,152.119S104.065,184.113,104.065,100.1V60.06h303.87
	V100.1z"></path>
                                            <circle style="fill:#FFFFFF;" cx="256" cy="156.139" r="64.052"></circle>
                                            <rect x="223.74" y="292.121" width="64.52" height="15.61"></rect>
                                            <rect x="240.39" y="316.056" width="31.22" height="15.61"></rect>
                                            <rect x="247.675" y="339.991" width="15.61" height="72.846"></rect>
                                            <path d="M343.868,484.2H168.132v-7.805c0-17.547,14.277-31.823,31.824-31.823h112.087c17.547,0,31.824,14.276,31.824,31.823v7.805
	H343.868z M185.747,468.59h140.507c-2.762-5.009-8.096-8.408-14.209-8.408H199.957C193.843,460.182,188.51,463.583,185.747,468.59z"></path>
                                            <rect x="95.74" y="27.8" width="320.52" height="15.61"></rect>
                                            <path d="M512,177.309V52.776h-87.415v46.829h15.61v-31.22h56.195v98.619L349.89,229.622c40.183-29.087,66.371-76.307,66.371-129.522
	V52.776H95.74V100.1c0,53.216,26.188,100.434,66.371,129.521L15.61,167.004V68.385h56.195v31.22h15.61V52.776H0v124.534
	l168.585,72.057v27.15H128v-16.65h-15.61v32.26h71.805v-49.08c12.358,6.206,25.626,10.859,39.545,13.712V283.8h64.52v-27.043
	c13.919-2.852,27.187-7.504,39.545-13.712v49.08h71.805v-32.26H384v16.65h-40.585v-27.15L512,177.309z M272.65,268.19H239.35v-9.041
	c5.475,0.567,11.027,0.875,16.65,0.875s11.176-0.308,16.65-0.875V268.19z M256,244.414c-79.761,0-144.65-64.74-144.65-144.315
	V68.385H400.65V100.1C400.65,179.675,335.761,244.414,256,244.414z"></path>
                                            <rect x="199.805" y="420.121" width="112.39" height="15.61"></rect>
                                            <path d="M256,227.999c-39.621,0-71.856-32.234-71.856-71.855S216.379,84.289,256,84.289s71.856,32.235,71.856,71.856
	S295.621,227.999,256,227.999z M256,99.898c-31.014,0-56.246,25.232-56.246,56.246c0,31.013,25.232,56.245,56.246,56.245
	s56.246-25.232,56.246-56.245C312.246,125.13,287.014,99.898,256,99.898z"></path>
                                            <path style="fill:#E2804F;" d="M278.197,187.168c-3.534,1.97-9.57,3.948-16.231,3.948c-10.204,0-19.57-4.159-25.402-11.861
	c-2.807-3.541-4.887-8.014-5.825-13.534h-6.763v-7.389h5.724c0-0.523,0-1.142,0-1.767c0-1.04,0.102-2.088,0.102-3.127h-5.825v-7.389
	h6.966c1.36-5.622,3.855-10.406,7.186-14.261c5.935-6.661,14.261-10.618,24.042-10.618c6.349,0,11.869,1.462,15.614,3.127
	l-2.916,11.861c-2.706-1.142-6.974-2.494-11.548-2.494c-4.996,0-9.578,1.665-12.807,5.622c-1.454,1.665-2.604,4.057-3.331,6.763
	h25.918v7.389h-27.476c-0.109,1.04-0.109,2.19-0.109,3.229c0,0.625,0,1.04,0,1.665H273.1v7.389h-26.122
	c0.727,3.127,1.869,5.52,3.432,7.287c3.331,3.745,8.225,5.308,13.424,5.308c4.793,0,9.679-1.564,11.869-2.706L278.197,187.168z"></path>

</svg><span
                                                class="menu-item_Center"> Eurocopa</span> </span> </li>
                                    <li class="main-item menu-home vfl " onclick='showVirtual("vfl")'><svg height="480pt" viewBox="0 0 480 480" width="480pt" xmlns="http://www.w3.org/2000/svg" style="
    height: 20px;
    width: auto;
"><path d="m240 0c-132.546875 0-240 107.453125-240 240s107.453125 240 240 240 240-107.453125 240-240c-.148438-132.484375-107.515625-239.851562-240-240zm8.566406 69.191406 83.433594-33.351562c9.46875 4.285156 18.628906 9.222656 27.414062 14.777344l.21875.136718c8.632813 5.46875 16.882813 11.519532 24.695313 18.109375l.671875.585938c3.503906 2.984375 6.910156 6.074219 10.222656 9.261719.417969.410156.855469.800781 1.273438 1.21875 3.472656 3.390624 6.835937 6.886718 10.089844 10.484374.269531.304688.527343.625.796874.929688 2.855469 3.199219 5.601563 6.511719 8.265626 9.878906.640624.800782 1.28125 1.601563 1.902343 2.402344 2.890625 3.742188 5.6875 7.550781 8.328125 11.480469l-16.632812 70.703125-81.832032 27.28125-78.828124-63.074219zm-186.125 34.480469c.621094-.800781 1.253906-1.601563 1.894532-2.398437 2.632812-3.339844 5.355468-6.597657 8.167968-9.777344.304688-.335938.585938-.679688.886719-1.015625 3.234375-3.605469 6.582031-7.097657 10.050781-10.480469.398438-.390625.796875-.800781 1.214844-1.160156 3.285156-3.167969 6.664062-6.238282 10.136719-9.207032l.800781-.671874c7.742188-6.542969 15.914062-12.554688 24.460938-18l.3125-.199219c8.734374-5.542969 17.835937-10.472657 27.25-14.761719l83.816406 33.191406v80.800782l-78.832032 63.0625-81.832031-27.230469-16.632812-70.703125c2.664062-3.921875 5.429687-7.722656 8.304687-11.449219zm-9.640625 259.089844c-2.351562-3.585938-4.601562-7.238281-6.746093-10.960938l-.519532-.898437c-2.132812-3.703125-4.152344-7.46875-6.054687-11.292969l-.066407-.121094c-4.007812-8.046875-7.527343-16.328125-10.535156-24.800781v-.078125c-1.421875-4-2.71875-8.097656-3.917968-12.21875l-.433594-1.519531c-1.097656-3.871094-2.09375-7.785156-2.984375-11.742188-.078125-.386718-.175781-.753906-.253907-1.136718-1.964843-8.9375-3.375-17.984376-4.226562-27.097657l48.839844-58.605469 81.265625 27.085938 23.585937 94.335938-38.753906 51.5625zm240.472657 94.78125c-4 .992187-8.105469 1.847656-12.210938 2.617187-.574219.113282-1.160156.207032-1.734375.3125-3.496094.625-7.03125 1.160156-10.574219 1.597656-.945312.121094-1.882812.25-2.824218.363282-3.289063.382812-6.609376.671875-9.9375.910156-1.046876.070312-2.082032.175781-3.128907.242188-4.253906.261718-8.542969.414062-12.863281.414062-3.957031 0-7.890625-.105469-11.800781-.3125-.472657 0-.925781-.078125-1.398438-.113281-3.480469-.199219-6.945312-.460938-10.402343-.796875l-.398438-.074219c-7.574219-.820313-15.105469-2.023437-22.558594-3.597656l-47.320312-74.089844 38.144531-50.863281h111.46875l38.769531 51.199218zm165.496093-169.542969c-.082031.382812-.175781.753906-.257812 1.136719-.894531 3.953125-1.890625 7.867187-2.984375 11.742187l-.429688 1.519532c-1.203125 4.121093-2.496094 8.203124-3.921875 12.21875v.078124c-3.007812 8.472657-6.523437 16.753907-10.535156 24.800782l-.066406.121094c-1.914063 3.828124-3.929688 7.59375-6.054688 11.292968l-.519531.898438c-2.132812 3.734375-4.378906 7.378906-6.734375 10.945312l-78.929687 12.445313-39.023438-51.519531 23.574219-94.3125 81.265625-27.085938 48.839844 58.605469c-.847657 9.117187-2.257813 18.171875-4.222657 27.113281zm0 0"></path></svg><span
                                                class="menu-item_Center"> Futbol</span></li>
                                    <li class="main-item menu-home vhc " onclick='showVirtual("vhc")'><svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="196.285px" height="196.285px" viewBox="0 0 196.285 196.285" style="enable-background:new 0 0 196.285 196.285;height: 20px;width: auto;" xml:space="preserve">
<g>
    <path d="M38.228,155.075c-26.554-39.188-7.814-69.794-7.814-69.794c-4.354-1.289-18.279,7.837-18.279,7.837
		c7.832-28.702,50.91-53.087,50.91-53.087c-4.782-1.75-18.273,1.726-18.273,1.726c35.243-33.919,78.326-21.306,78.326-21.306
		C124.398,13.048,145.73,0,145.73,0c-2.182,2.161-3.062,13.926-3.062,13.926c2.176-3.922,9.15-7.837,9.15-7.837
		c-3.913,9.126,0,28.703,0,28.703c11.312,11.322,23.927,50.49,23.927,50.49l4.8,6.96c6.094,8.715,3.854,22.417-2.182,25.667
		c-22.62,12.212-24.808-10.001-24.808-10.001c-18.713-2.176-32.639-23.07-32.639-23.07c-25.23,13.051-11.178,49.869-0.284,64.007
		c13.997,18.123,3.748,47.44,3.748,47.44S64.448,193.803,38.228,155.075z"></path>
</g>

</svg><span
                                                class="menu-item_Center"> Carrera de caballos</span></li>
                                    <li class="main-item menu-home vdr " onclick='showVirtual("vdr")'><svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                                                                           viewBox="0 0 420.326 420.326" style="enable-background:new 0 0 420.326 420.326;height:20px; width:auto;" xml:space="preserve">
<g>
    <path d="M228.053,116.191C228.532,116.243,227.597,116.067,228.053,116.191L228.053,116.191z"/>
    <path d="M415.501,229.82l-101.146-52.965c-2.592-11.371-8.942-25.952-24.06-38.143c-15.015-12.109-35.906-19.814-62.242-22.521
		L27.658,94.656c-4.641-0.505-8.891,2.623-9.797,7.198c-0.113,0.571-2.711,14.166,2.438,30.739
		c2.181,7.02,5.985,15.256,12.577,23.378l-26.269,7.247c-4.612,1.272-7.423,5.931-6.397,10.604
		c0.122,0.556,3.114,13.764,14.148,26.747c7.718,9.082,20.66,19.491,41.182,23.883c-18.63,40.612-24.831,77.128-25.109,78.809
		c-0.406,2.45,0.219,4.96,1.726,6.934c1.508,1.974,3.764,3.237,6.234,3.49l116.955,11.99c0.311,0.032,0.618,0.047,0.925,0.047
		c4.063,0,7.679-2.749,8.71-6.759c6.019-23.404,17.513-54.518,47.719-54.518c15.748,0,33.407,7.798,48.724,15.107
		c21.949,10.475,42.852,15.786,62.125,15.786c35.848,0,56.374-18.09,63.731-26.137c26.525-1.407,32.873-22.809,33.043-31.227
		C420.393,234.562,418.525,231.403,415.501,229.82z M285.544,193.069c-2.1,2.273-4.959,3.593-8.051,3.715
		c-0.154,0.006-0.309,0.009-0.462,0.009c-6.243,0-11.327-4.887-11.575-11.126c-0.249-6.385,4.737-11.782,11.115-12.037
		c0.158-0.006,0.312-0.009,0.463-0.009c6.243,0,11.328,4.887,11.576,11.126C288.732,187.841,287.643,190.796,285.544,193.069z"/>
</g>
</svg><span
                                                class="menu-item_Center"> Carrera de perros</span></li>
                                    <li class="main-item menu-home vbl " onclick='showVirtual("vbl")'><svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="488.839px" height="488.839px" viewBox="0 0 488.839 488.839" style="enable-background:new 0 0 488.839 488.839;width: auto;height: 20px;" xml:space="preserve">
<g>
    <g id="_x31_4_26_">
        <g>
            <path d="M264.32,39.487c5.529-7.328,4.64-22.871,3.853-36.582c-0.03-0.591-0.063-1.169-0.096-1.756
				C260.327,0.409,252.482,0,244.542,0c-30.425,0-59.537,5.605-86.406,15.766c19.167,9.211,48.836,25.065,81.814,48.49
				C251.202,54.573,259.688,45.634,264.32,39.487z"></path>
            <path d="M388.163,178.955c-12.047,1.361-22.207,3.338-30.896,6.111c41.537,72.522,57.732,164.145,61.695,230.522
				c41.15-41.928,67.283-98.609,69.754-161.393C460.362,219.343,422.8,175.026,388.163,178.955z"></path>
            <path d="M141.232,116.356c29.185-6.073,56.604-20.762,78.466-36.267c-45.608-31.412-83.648-47.714-91.215-50.817
				C81.166,54.851,43.302,95.606,21.274,144.987c28.632-11.408,59.641-17.212,90.098-22.867
				C121.225,120.29,131.41,118.399,141.232,116.356z"></path>
            <path d="M310.171,123.463c2.605,2.618,5.203,5.281,7.783,7.994c9.521,9.995,18.16,20.927,26.082,32.493
				c11.537-4.356,25.119-7.445,41.377-9.283c38.307-4.333,72.369,26.012,101.604,59.42c-6.268-50.645-27.989-96.482-60.354-132.631
				c-17.666,2.129-37.361,5.736-57.435,11.726C356.06,97.115,335.089,107.801,310.171,123.463z"></path>
            <path d="M278.011,249.688c10.025-33.955,21.637-58.432,44.135-74.32c-6.752-9.693-14.018-18.79-21.889-27.054
				c-3.641-3.824-7.323-7.517-11.021-11.145c-90.263,61.439-213.906,174.3-225.149,272.086
				c37.53,41.062,88.892,69.222,146.712,77.211c21.053-41.915,40.868-127.124,52.646-177.801
				C269.104,284.324,273.988,263.307,278.011,249.688z"></path>
            <path d="M175.668,195.843c31.472-29.001,64.708-55.007,95.107-75.908c-10.133-8.965-20.297-17.229-30.275-24.808
				c-25.249,18.98-58.307,37.674-94.291,45.158c-10.077,2.097-20.396,4.014-30.372,5.866
				c-38.438,7.138-75.336,14.051-106.366,31.364c-6.043,21.271-9.348,43.697-9.348,66.905c0,51.917,16.238,100.02,43.839,139.604
				C61.843,313.227,123.175,244.214,175.668,195.843z"></path>
            <path d="M291.829,105.975c29.078-18.538,54.147-31.356,70.412-36.211c14.606-4.362,28.934-7.539,42.502-9.856
				c-31.353-27.245-69.678-46.663-111.986-55.127c0.938,17.07,1.347,35.811-8.922,49.427c-4.879,6.466-13.133,15.334-23.873,24.955
				C270.48,87.35,281.169,96.278,291.829,105.975z"></path>
            <path d="M301.45,256.609c-3.816,12.928-8.63,33.625-14.199,57.588c-16.444,70.755-32.179,134.067-50.555,174.442
				c2.609,0.083,5.217,0.199,7.846,0.199c56.963,0,109.328-19.539,150.885-52.206c-1.641-69.461-18.293-167.71-60.238-240.643
				C318.921,208.08,310.165,227.101,301.45,256.609z"></path>
        </g>
    </g>
</g>
</svg><span
                                                class="menu-item_Center"> Baloncesto</span></li>
                                    <li class="main-item menu-home  vto" onclick='showVirtual("vto")'><svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 56 56" style="enable-background:new 0 0 56 56;height: 20px;width: auto;" xml:space="preserve">
<path style="fill:#D3EF30;" d="M7.062,27.616c0.228,5.148,2.462,9.679,6.296,12.763c3.979,3.201,9.2,4.521,14.318,3.617
	c3.038-0.535,6.062-2.102,8.743-4.53c1.628-1.475,3.111-3.121,4.408-4.891c3.796-5.178,4.756-11.798,2.505-17.277
	c-2.603-6.334-8.319-10.295-15.3-10.603c-4.898,0.316-9.002-0.945-11.856-3.663c-0.096-0.092-0.168-0.199-0.219-0.313
	C10.348,5.394,5.775,9.884,2.984,15.427c0.101,0.049,0.2,0.107,0.283,0.192C6.077,18.479,7.39,22.626,7.062,27.616z"></path>
                                            <path style="fill:#D3EF30;" d="M28,0c-3.558,0-6.958,0.671-10.089,1.881c2.436,2.115,5.892,3.092,10.043,2.814l0.055-0.004
	l0.054,0.002c7.807,0.323,14.207,4.751,17.12,11.844c2.514,6.119,1.464,13.483-2.741,19.22c-1.377,1.878-2.951,3.625-4.679,5.191
	c-2.961,2.682-6.329,4.417-9.738,5.018c-1.173,0.207-2.351,0.308-3.52,0.308c-4.503,0-8.885-1.508-12.4-4.336
	C7.802,38.476,5.301,33.4,5.062,27.646l-0.002-0.055l0.003-0.055c0.289-4.251-0.741-7.761-2.954-10.205C0.753,20.62,0,24.221,0,28
	c0,15.464,12.536,28,28,28s28-12.536,28-28C56,12.536,43.464,0,28,0z"></path>
                                            <path style="fill:#FFFFFF;" d="M5.063,27.536l-0.003,0.055l0.002,0.055c0.238,5.754,2.739,10.83,7.042,14.292
	c3.516,2.828,7.897,4.336,12.4,4.336c1.169,0,2.347-0.102,3.52-0.308c3.409-0.601,6.777-2.335,9.738-5.018
	c1.728-1.566,3.302-3.313,4.679-5.191c4.205-5.737,5.255-13.101,2.741-19.22C42.27,9.444,35.869,5.017,28.062,4.693l-0.054-0.002
	l-0.055,0.004c-4.151,0.279-7.607-0.699-10.043-2.814c-0.663,0.256-1.316,0.532-1.954,0.837c0.052,0.114,0.123,0.222,0.219,0.313
	c2.854,2.718,6.958,3.979,11.856,3.663c6.98,0.308,12.697,4.269,15.3,10.603c2.251,5.479,1.291,12.099-2.505,17.277
	c-1.297,1.771-2.78,3.416-4.408,4.891c-2.682,2.429-5.705,3.995-8.743,4.53c-5.118,0.903-10.34-0.416-14.318-3.617
	c-3.834-3.084-6.068-7.615-6.296-12.763c0.327-4.989-0.985-9.137-3.796-11.997c-0.083-0.085-0.182-0.143-0.283-0.192
	c-0.313,0.622-0.607,1.257-0.874,1.904C4.323,19.776,5.352,23.285,5.063,27.536z"></path>
</svg><span
                                                class="menu-item_Center"> Tennis</span></li>
                                </ul>
                            </div>
                            <ul class="menu-sub  ActiveSub"></ul>
                        </div>
                    </div>
                </menuvirtual>-->
                            <div id="BIA_client_container"></div>
                        </div>
                        <script type="text/javascript"
                                src="<?= $srcItn ?>"></script>

                        <script>


                            function showVirtual(Page) {
                                var options = {
                                    token: '<?= $URL ?>',
                                    skinid: <?= ($_GET["lan"] == "eng") ? "'doradobet3'" : "'doradobet'" ?>,
                                    walletcode: '190582',
                                    full: true,
                                    page: Page,
                                    lang: 'es-ES',
                                    fixed: false
                                    <?php
                                    if ($isMobile == "true") {
                                        echo ', mobile:true';
                                    }
                                    ?>
                                };
                                var BIA = new AltenarSportsbook('#BIA_client_container', options);

                                if (Page == "vfec") {

                                    var els = document.getElementsByClassName('active');
                                    removeClass(els, 'active');

                                    var els = document.getElementsByClassName('vfec');
                                    addClass(els, 'active');
                                }
                                if (Page == "vfl") {
                                    var els = document.getElementsByClassName('active');
                                    removeClass(els, 'active');

                                    var els = document.getElementsByClassName('vfl');
                                    addClass(els, 'active');

                                }
                                if (Page == "vhc") {
                                    var els = document.getElementsByClassName('active');
                                    removeClass(els, 'active');

                                    var els = document.getElementsByClassName('vhc');
                                    addClass(els, 'active');
                                }
                                if (Page == "vdr") {
                                    var els = document.getElementsByClassName('active');
                                    removeClass(els, 'active');

                                    var els = document.getElementsByClassName('vdr');
                                    addClass(els, 'active');
                                }
                                if (Page == "vbl") {
                                    var els = document.getElementsByClassName('active');
                                    removeClass(els, 'active');

                                    var els = document.getElementsByClassName('vbl');
                                    addClass(els, 'active');
                                }
                                if (Page == "vto") {
                                    var els = document.getElementsByClassName('active');
                                    removeClass(els, 'active');

                                    var els = document.getElementsByClassName('vto');
                                    addClass(els, 'active');
                                }
                                if (Page == "vfec") {
                                    var els = document.getElementsByClassName('active');
                                    removeClass(els, 'active');

                                    var els = document.getElementsByClassName('vfec');
                                    addClass(els, 'active');
                                }
                                if (Page == "vfwc") {
                                    var els = document.getElementsByClassName('active');
                                    removeClass(els, 'active');

                                    var els = document.getElementsByClassName('vfwc');
                                    addClass(els, 'active');
                                }

                            }


                            function addClass(elements, className) {
                                for (var i = 0; i < elements.length; i++) {
                                    var element = elements[i];
                                    if (element.classList) {
                                        element.classList.add(className);
                                    } else {
                                        element.className += ' ' + className;
                                    }
                                }
                            }

                            function removeClass(elements, className) {
                                for (var i = 0; i < elements.length; i++) {
                                    var element = elements[i];
                                    if (element.classList) {
                                        element.classList.remove(className);
                                    } else {
                                        element.className = element.className.replace(new RegExp('(^|\\b)' + className.split(' ').join('|') + '(\\b|$)', 'gi'), ' ');
                                    }
                                }
                            }

                            showVirtual('vfwc');


                        </script>
                    </div>

                    <style>

                        @media (max-width: 600px) {

                            li.main-item.active span {
                                display: inline-block !important;
                            }

                            li.main-item span {
                                display: none !important;
                            }
                        }

                    </style>

                    <?php
                } elseif ($provider == "VGT") {
                    if ($mode == 'fun') {
                        print('<div style="width: 100%;height: 97%;background: black;background: url(' . $bgCasino . ') 50% 0px no-repeat;font: 15px/20px Quicksand,Arial,Helvetica,sans-serif;"><div style="
    color: white;
    text-align: center;
    padding-top: 30%;
    /* display: inline-block; */
    font-size: 35px;
    text-transform: uppercase;
"> Juego No disponible en modo DEMO</div>
</div>');
                    } else {
                        print('<iframe frameborder="0" src="' . $URL . '" class="embed-responsive-item" style="width: 100%;height: 100%;margin-top: -5px;" ></iframe>');
                    }
                } elseif ($provider == "PLAYNGO") {
                    if ($isMobile == "true" && $in_app != '1') {
                        //echo '<script>parent.parent.location.href = "' . $URL . '";</script>';

                        echo '<script>var a = document.createElement(\'a\');
a.href="' . $URL . '";
a.target = \'_top\';

a.click();
</script>
';
                    } elseif ($isMobile == "true" && $in_app == '1') {
                        print('<iframe frameborder="0" src="' . explode("&lobby", $URL)[0] . '" class="embed-responsive-item" style="width: 100%;height: 100%;"></iframe>');
                    } else {
                        print('<div ng-if="isDiv" id="pngCasinoGame" style="width: 100%; height: auto;"></div>');

                        print('<script src="' . $URL . '" type="text/javascript">function reloadgame(gameId, user) { window.location.reload(false);  }</script>');
                        print('<script></script>');
                    }
                } elseif ($provider == "PRAGMATIC") {
                    if ($isMobile == "true" && $in_app != '1') {
                        //echo '<script>parent.parent.location.href = "' . $URL . '";</script>';

                        echo '<script>var a = document.createElement(\'a\');
a.href="' . $URL . '";
a.target = \'_top\';

a.click();
</script>
';
                    }
                    print('<iframe frameborder="0" src="' . $URL . '" class="embed-responsive-item" style="width: 100%;height: 97%;"></iframe>');
                } elseif ($provider == "EZZG") {
                    print('<iframe frameborder="0" src="' . $URL . '" class="embed-responsive-item" style="width: 100%;height: 97%;"></iframe>');
                } elseif ($provider == "BETGAMESTV") {
                    print('<script type="text/javascript"> 
	var _bt = _bt || [];    
	_bt.push([\'server\', \'' . $URL->serverParam . '\']);
	_bt.push([\'partner\', \'' . $URL->partnerParam . '\']);
	_bt.push([\'token\', \'' . $URL->token . '\']);
	_bt.push([\'language\', \'' . $URL->languageParam . '\']);
	_bt.push([\'timezone\', \'' . $URL->timezoneParam . '\']);
	//_bt.push([\'is_mobile\', \'<is_mobile>\']);
	//_bt.push([\'current_game\', \'<current_game>\']);
	//_bt.push([\'odds_format\', \'<odds_format>\']);
	//_bt.push([\'home_url\', \'<home_button_url>\']);
	
	(function(){
	document.write(\'<\'+\'script type="text/javascript" src="' . $URL->jsURL . '?ts=\' + Date.now() + \'"><\'+\'/script>\');
	})();
</script>
<script type="text/javascript">BetGames.frame(_bt);</script>
');
                } elseif ($provider == "ORYX") {
                    if ($isMobile == "true" && $in_app != '1') {
                        //echo '<script>parent.parent.location.href = "' . $URL . '";</script>';
                        echo '<script>var a = document.createElement(\'a\');
a.href="' . $URL . '";
a.target = \'_top\';

a.click();
</script>
';
                    }
                    print('<iframe frameborder="0" src="' . $URL . '" class="embed-responsive-item" style="width: 100%;height: 97%;"></iframe>');
                } elseif ($provider == "XPRESS") {
                    if (($isMobile == "true" && $in_app != '1')) {
                        //echo '<script>parent.parent.location.href = "' . $URL . '";</script>';

                        echo '<script>var a = document.createElement(\'a\');
a.href="' . $URL . '";
a.target = \'_top\';

a.click();
</script>
';
                    }
                    print('
                     <script>
        var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
        var eventer = window[eventMethod];
        var messageEvent = eventMethod === "attachEvent" ? "onmessage" : "message";
        eventer(messageEvent, function (e) {

            switch (e.data.action) {
                case "game.loaded":
                    // Game successfully loaded.
                    break;
                case "game.balance.changed":
                    // Game Balance changed.
                    break;
                case "game.cycle.started":
                    // Ticket placing...
                    break;
                case "game.cycle.end":
                    // Ticket placed
                    break;
                case "game.goto.home":
                    //Game has to be redirected to the home lobby page.(exit)
                    break;
                case "game.goto.history":
                    // History modal opens
                    break;
                case "game.resize.height":
                    // iframe height should be: e.data.value;
                    document.getElementById("container").style.height = e.data.value;
                    break;
                case "game.get.clientrect":
                    // iframe selector.
                    e.source.postMessage({action: "game.clientrect", value: document.getElementById("container").getBoundingClientRect()}, \'*\');
                    break;
                case "game.get.clientheight":
                    // iframe selector.
                    e.source.postMessage({action: "game.clientheight", value: document.getElementById("container").offsetHeight}, \'*\');
                    break;
                case "game.get.innerheight":
                    // general window selector.
                    e.source.postMessage({action: "game.innerheight", value: window.innerHeight}, \'*\');
                    break;
            }
        });

    </script><div id="container"><iframe frameborder="0" src="' . $URL . '" class="embed-responsive-item" style="width: 100%;height: 100%;" scrolling="yes"></iframe></div>');
                } else {
                    if (($isMobile == "true" && $in_app != '1')) {
                        //echo '<script>parent.parent.location.href = "' . $URL . '";</script>';

                        echo '<script>var a = document.createElement(\'a\');
a.href="' . $URL . '";
a.target = \'_top\';

a.click();
</script>
';
                    }
                    print('<iframe frameborder="0" src="' . $URL . '" class="embed-responsive-item" style="width: 100%;height: 100%;"></iframe>');
                }
            }
        } catch (Exception $e) {
            if ($_ENV['debug']) {
                print_r($e);
            }


            $html = '
<head><meta name="viewport" content="width=device-width, user-scalable=no">
</head>
<style>

/*
VIEW IN FULL SCREEN MODE
FULL SCREEN MODE: http://salehriaz.com/404Page/404.html

DRIBBBLE: https://dribbble.com/shots/4330167-404-Page-Lost-In-Space
*/

@import url();

@-moz-keyframes rocket-movement { 100% {-moz-transform: translate(1200px,-600px);} }
@-webkit-keyframes rocket-movement {100% {-webkit-transform: translate(1200px,-600px); } }
@keyframes rocket-movement { 100% {transform: translate(1200px,-600px);} }
@-moz-keyframes spin-earth { 100% { -moz-transform: rotate(-360deg); transition: transform 20s;  } }
@-webkit-keyframes spin-earth { 100% { -webkit-transform: rotate(-360deg); transition: transform 20s;  } }
@keyframes spin-earth{ 100% { -webkit-transform: rotate(-360deg); transform:rotate(-360deg); transition: transform 20s; } }

@-moz-keyframes move-astronaut {
    100% { -moz-transform: translate(-160px, -160px);}
}
@-webkit-keyframes move-astronaut {
    100% { -webkit-transform: translate(-160px, -160px);}
}
@keyframes move-astronaut{
    100% { -webkit-transform: translate(-160px, -160px); transform:translate(-160px, -160px); }
}
@-moz-keyframes rotate-astronaut {
    100% { -moz-transform: rotate(-720deg);}
}
@-webkit-keyframes rotate-astronaut {
    100% { -webkit-transform: rotate(-720deg);}
}
@keyframes rotate-astronaut{
    100% { -webkit-transform: rotate(-720deg); transform:rotate(-720deg); }
}

@-moz-keyframes glow-star {
    40% { -moz-opacity: 0.3;}
    90%,100% { -moz-opacity: 1; -moz-transform: scale(1.2);}
}
@-webkit-keyframes glow-star {
    40% { -webkit-opacity: 0.3;}
    90%,100% { -webkit-opacity: 1; -webkit-transform: scale(1.2);}
}
@keyframes glow-star{
    40% { -webkit-opacity: 0.3; opacity: 0.3;  }
    90%,100% { -webkit-opacity: 1; opacity: 1; -webkit-transform: scale(1.2); transform: scale(1.2); border-radius: 999999px;}
}

.spin-earth-on-hover{
    
    transition: ease 200s !important;
    transform: rotate(-3600deg) !important;
}

html, body{
    margin: 0;
    width: 100%;
    height: 100%;
    font-family: \'Dosis\', sans-serif;
    font-weight: 300;
    -webkit-user-select: none; /* Safari 3.1+ */
    -moz-user-select: none; /* Firefox 2+ */
    -ms-user-select: none; /* IE 10+ */
    user-select: none; /* Standard syntax */
}

.bg-purple{
    background: black;
    background-repeat: repeat-x;
    background-size: cover;
    background-position: left top;
    height: 100%;
    overflow: hidden;
    
}

.custom-navbar{
    padding-top: 15px;
}

.brand-logo{
    margin-left: 25px;
    margin-top: 5px;
    display: inline-block;
}

.navbar-links{
    display: inline-block;
    float: right;
    margin-right: 15px;
    text-transform: uppercase;
    
    
}

ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
/*    overflow: hidden;*/
    display: flex; 
    align-items: center; 
}

li {
    float: left;
    padding: 0px 15px;
}

li a {
    display: block;
    color: white;
    text-align: center;
    text-decoration: none;
    letter-spacing : 2px;
    font-size: 12px;
    
    -webkit-transition: all 0.3s ease-in;
    -moz-transition: all 0.3s ease-in;
    -ms-transition: all 0.3s ease-in;
    -o-transition: all 0.3s ease-in;
    transition: all 0.3s ease-in;
}

li a:hover {
    color: #ffcb39;
}

.btn-request{
    padding: 10px 25px;
    border: 1px solid #FFCB39;
    border-radius: 100px;
    font-weight: 400;
}

.btn-request:hover{
    background-color: #FFCB39;
    color: #fff;
    transform: scale(1.05);
    box-shadow: 0px 20px 20px rgba(0,0,0,0.1);
}

.btn-go-home{
    position: relative;
    z-index: 200;
    margin: 15px auto;
    width: 100px;
    padding: 10px 15px;
    border: 1px solid #FFCB39;
    border-radius: 100px;
    font-weight: 400;
    display: block;
    color: white;
    text-align: center;
    text-decoration: none;
    letter-spacing : 2px;
    font-size: 11px;
    
    -webkit-transition: all 0.3s ease-in;
    -moz-transition: all 0.3s ease-in;
    -ms-transition: all 0.3s ease-in;
    -o-transition: all 0.3s ease-in;
    transition: all 0.3s ease-in;
}

.btn-go-home:hover{
    background-color: #FFCB39;
    color: #fff;
    transform: scale(1.05);
    box-shadow: 0px 20px 20px rgba(0,0,0,0.1);
}

.central-body{
/*    width: 100%;*/
    padding: 30% 5% 10% 5%;
    text-align: center;
}

.objects img{
    z-index: 90;
    pointer-events: none;
}

.object_rocket{
    z-index: 95;
    position: absolute;
    transform: translateX(-50px);
    top: 75%;
    pointer-events: none;
    animation: rocket-movement 200s linear infinite both running;
}

.object_earth{
    position: absolute;
    top: 20%;
    left: 15%;
    z-index: 90;
/*    animation: spin-earth 100s infinite linear both;*/
}

.object_moon{
    position: absolute;
    top: 12%;
    left: 25%;
/*
    transform: rotate(0deg);
    transition: transform ease-in 99999999999s;
*/
}

.earth-moon{
    
}

.object_astronaut{
    animation: rotate-astronaut 200s infinite linear both alternate;
}

.box_astronaut{
    z-index: 110 !important;
    position: absolute;
    top: 60%;
    right: 20%;
    will-change: transform;
    animation: move-astronaut 50s infinite linear both alternate;
}

.image-404{
    position: relative;
    z-index: 100;
    pointer-events: none;
}

.stars{
    background: url(http://salehriaz.com/404Page/img/overlay_stars.svg);
    background-repeat: repeat;
    background-size: contain;
    background-position: left top;
    height: 100%;
}

.glowing_stars .star{
    position: absolute;
    border-radius: 100%;
    background-color: #fff;
    width: 3px;
    height: 3px;
    opacity: 0.3;
    will-change: opacity;
}

.glowing_stars .star:nth-child(1){
    top: 80%;
    left: 25%;
    animation: glow-star 2s infinite ease-in-out alternate 1s;
}
.glowing_stars .star:nth-child(2){
    top: 20%;
    left: 40%;
    animation: glow-star 2s infinite ease-in-out alternate 3s;
}
.glowing_stars .star:nth-child(3){
    top: 25%;
    left: 25%;
    animation: glow-star 2s infinite ease-in-out alternate 5s;
}
.glowing_stars .star:nth-child(4){
    top: 75%;
    left: 80%;
    animation: glow-star 2s infinite ease-in-out alternate 7s;
}
.glowing_stars .star:nth-child(5){
    top: 90%;
    left: 50%;
    animation: glow-star 2s infinite ease-in-out alternate 9s;
}

@media only screen and (max-width: 600px){
    .navbar-links{
        display: none;
    }
    
    .custom-navbar{
        text-align: center;
    }
    
    .brand-logo img{
        width: 120px;
    }
    
    .box_astronaut{
        top: 70%;
    }
    
    .central-body{
        padding-top: 60%;
    }
    .btn-go-home{
        background-color: #FFCB39;
    color: #fff;
    transform: scale(1.05);
    box-shadow: 0px 20px 20px rgba(0,0,0,0.1);
    }
}

.title{
    color: white;
}
</style><section class="page_404">
	<!--
VIEW IN FULL SCREEN MODE
FULL SCREEN MODE: http://salehriaz.com/404Page/404.html

DRIBBBLE: https://dribbble.com/shots/4330167-404-Page-Lost-In-Space
-->

<body class="bg-purple">
        
        <div class="stars">
            <div class="custom-navbar">
            </div>
            <div class="objects">
              <div class="earth-moon">
                    <img class="object_earth" src="http://salehriaz.com/404Page/img/earth.svg" width="100px">
                    <img class="object_moon" src="http://salehriaz.com/404Page/img/moon.svg" width="80px">
                </div>
                <div class="box_astronaut">
                    <img class="object_astronaut" src="http://salehriaz.com/404Page/img/astronaut.svg" width="140px">
                </div>
            </div>
            <div class="glowing_stars">
                <div class="star"></div>
                <div class="star"></div>
                <div class="star"></div>
                <div class="star"></div>
                <div class="star"></div>

            </div>

        </div>

    </body>';
            header('Content-Type: html');

            echo($html);
        }
    }

    /**
     * Convierte un código de error del mandante en una excepción específica.
     *
     * @param string $code Código de error proporcionado por el mandante.
     * @param string $message Mensaje de error asociado al código.
     *
     * @return void
     * @throws Exception Lanza una excepción con un código de error específico.
     */
    public function convertErrorMandante($code, $message)
    {
        switch ($code) {
            case "M1":
                throw new Exception("", "10011");
                break;

            case "M2":
                throw new Exception("", "20003");
                break;

            case "M3":
                throw new Exception("", "20007");
                break;

            case "M4":
                throw new Exception("", "20001");
                break;

            case "M5":
                break;

            case "M6":
                break;

            case "M7":
                break;

            case "M8":
                break;

            default:
                throw new Exception("", 100000);
        }
        throw new Exception("", 100000);
    }
}

/**
 * Elimina caracteres no deseados de una cadena de texto.
 *
 * Esta función reemplaza una lista de caracteres específicos por una cadena vacía
 * en el texto proporcionado. Es útil para depurar o limpiar entradas de texto.
 *
 * @param string $texto_depurar El texto que se desea depurar.
 *
 * @return string El texto depurado sin los caracteres no deseados.
 */
function DepurarCaracteres($texto_depurar)
{
    $texto_depurar = str_replace("'", "", $texto_depurar);
    $texto_depurar = str_replace('"', "", $texto_depurar);
    $texto_depurar = str_replace(">", "", $texto_depurar);
    $texto_depurar = str_replace("<", "", $texto_depurar);
    $texto_depurar = str_replace("[", "", $texto_depurar);
    $texto_depurar = str_replace("]", "", $texto_depurar);
    $texto_depurar = str_replace("{", "", $texto_depurar);
    $texto_depurar = str_replace("}", "", $texto_depurar);
    $texto_depurar = str_replace("�", "", $texto_depurar);
    $texto_depurar = str_replace("`", "", $texto_depurar);
    $texto_depurar = str_replace("|", "", $texto_depurar);
    $texto_depurar = str_replace("�", "", $texto_depurar);
    $texto_depurar = str_replace("�", "", $texto_depurar);
    $texto_depurar = str_replace("%", "", $texto_depurar);
    $texto_depurar = str_replace("&", "", $texto_depurar);
    $texto_depurar = str_replace("�", "", $texto_depurar);
    $texto_depurar = str_replace("~", "", $texto_depurar);
    $texto_depurar = str_replace("+", "", $texto_depurar);
    $texto_depurar = str_replace("^", "", $texto_depurar);
    $texto_depurar = str_replace("/", "", $texto_depurar);
    //$texto_retornar = addslashes($texto_depurar);

    $c = null;
    return $texto_depurar;
}

/**
 * Genera una clave aleatoria de longitud específica compuesta por números.
 *
 * @param integer $length La longitud de la clave aleatoria a generar.
 *
 * @return string La clave aleatoria generada.
 */
function GenerarClaveTicket2($length)
{
    $characters = '0123456789';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
