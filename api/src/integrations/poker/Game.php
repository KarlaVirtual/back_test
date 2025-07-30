<?php

/**
 * Agrega una clase a los elementos especificados.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-21
 */

namespace Backend\integrations\casino;

use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Mandante;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Producto;
use Backend\dto\ProductoMandante;
use Backend\dto\ProductoDetalle;
use Backend\dto\Proveedor;
use Backend\dto\ProveedorMandante;
use Backend\dto\PuntoVenta;
use Backend\dto\Subproveedor;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionApiMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioAutomation2;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioSession;
use Backend\dto\UsuarioToken;
use Backend\dto\Pais;
use Backend\integrations\virtual\GOLDENRACESERVICES;
use Backend\integrations\virtual\XPRESSSERVICES;
use Backend\mysql\TransaccionApiMandanteMySqlDAO;
use Backend\mysql\TransaccionApiMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\websocket\WebsocketUsuario;
use Exception;

/**
 * Agrega una clase a los elementos especificados.
 *
 * @param {HTMLCollection} elements - Colección de elementos a los que se les agregará la clase.
 * @param {string} className - Nombre de la clase que se agregará.
 */
class Game
{


    /**
     * ID del juego.
     *
     * @var string
     */
    private $gameid;

    /**
     * Modo de juego (por ejemplo, demo o real).
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
     * Idioma configurado para el juego.
     *
     * @var string
     */
    private $lan;

    /**
     * ID del socio asociado al juego.
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
     * Indica si el usuario está en un dispositivo móvil.
     *
     * @var bool
     */
    private $isMobile;

    /**
     * Constructor de la clase Game.
     *
     * Inicializa las propiedades de la clase con los valores proporcionados,
     * aplicando una depuración de caracteres a las cadenas de entrada.
     *
     * @param string $gameid       ID del juego.
     * @param string $mode         Modo de juego (por ejemplo, demo o real).
     * @param string $provider     Proveedor del juego.
     * @param string $lan          Idioma configurado para el juego.
     * @param string $partnerid    ID del socio asociado al juego.
     * @param string $usuarioToken Token del usuario para autenticación.
     * @param string $isMobile     Indica si el usuario está en un dispositivo móvil ("true" o vacío).
     */
    public function __construct(
        $gameid = "",
        $mode = "",
        $provider = "",
        $lan = "",
        $partnerid = "",
        $usuarioToken = "",
        $isMobile = ""
    ) {
        $this->gameid = DepurarCaracteres($gameid);
        $this->mode = DepurarCaracteres($mode);
        $this->provider = DepurarCaracteres($provider);
        $this->lan = DepurarCaracteres($lan);
        $this->usuarioToken = DepurarCaracteres($usuarioToken);
        $this->partnerid = DepurarCaracteres($partnerid);
        $this->isMobile = false;

        if ($isMobile == "true") {
            $this->isMobile = true;
        }
    }

    /**
     * Obtiene la URL del juego basado en los parámetros proporcionados.
     *
     * Este método realiza múltiples validaciones y configuraciones para determinar
     * la URL del juego, dependiendo del proveedor, idioma, modo de juego y otros
     * factores. También maneja la autenticación del usuario y verifica la disponibilidad
     * del juego.
     *
     * @param string $gameGId ID del juego global (opcional).
     *
     * @return string|boolean La URL del juego si se genera correctamente, o `false` en caso de error.
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


        try {
            $Mandante = new Mandante($this->partnerid);

            if ($gameGId != '') {
                $Producto = new Producto($gameGId);

                $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $Mandante->mandante);
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
                } else {
                    if (($this->usuarioToken == '' && $Proveedor->getAbreviado(
                        ) != 'VIVOGAMING' && $Proveedor->getAbreviado() != 'VGT' && $Proveedor->getAbreviado(
                        ) != 'GDR' && $Subproveedor->getAbreviado() != 'PRAGMATICLIVE' && $Proveedor->getAbreviado(
                        ) != 'ITN' && $Proveedor->getAbreviado() != 'XPRESS')) {
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
                        "site" => $siteId,
                        "key" => $siteKey,
                        "token" => $this->usuarioToken
                    );

                    $result = sendRequest($urlApi . $method, "POST", $data);

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

                    if ($userid == "" || ! is_numeric($userid)) {
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


            if (($usuarioMandante == 65395) && (in_array($Proveedor->getProveedorId(), array('12', '68', '67')
                    ) || in_array($Producto->getProductoId(), array('5734', '5738', '5741', '5744', '5747', '5768')))) {
                throw new Exception("Juego no disponible ", "10000");
            }

            switch ($Proveedor->getAbreviado()) {
                case "IGP":
                    $IGPSERVICES = new IGPSERVICES();
                    $response = $IGPSERVICES->getGame(
                        $Producto->getExternoId(),
                        $this->lan,
                        $isFun,
                        $this->usuarioToken,
                        $usuarioMandante
                    );
                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }
                    break;

                case "EZZG":
                    $EZUGISERVICES = new EZUGISERVICES();
                    $ProductoDetalle = new ProductoDetalle("", $Producto->getProductoId(), "TABLE");

                    $response = $EZUGISERVICES->getGame(
                        $ProductoDetalle->getPValue(),
                        $this->lan,
                        $isFun,
                        $this->usuarioToken,
                        $usuarioMandante
                    );

                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }
                    break;

                case "PTG":
                    $PATAGONIASERVICES = new PATAGONIASERVICES();

                    $response = $PATAGONIASERVICES->getGame(
                        $Producto->getExternoId(),
                        $this->lan,
                        $isFun,
                        $this->usuarioToken,
                        $usuarioMandante
                    );

                    if ( ! $response->error) {
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
                    $response = $INBETSERVICES->getGame(
                        $ProductoDetalle->getPValue(),
                        $this->lan,
                        $isFun,
                        $this->usuarioToken,
                        $usuarioMandante
                    );

                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                case "WMT":
                    $WORLDMATCHSERVICES = new WORLDMATCHSERVICES();

                    $response = $WORLDMATCHSERVICES->getGame(
                        $Producto->getExternoId(),
                        $this->lan,
                        $isFun,
                        $this->usuarioToken,
                        $usuarioMandante
                    );

                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "GDR":
                    $GOLDENRACESERVICES = new GOLDENRACESERVICES();


                    $response = $GOLDENRACESERVICES->getGame(
                        $Producto->getExternoId(),
                        $this->lan,
                        $isFun,
                        $this->usuarioToken,
                        $usuarioMandante
                    );

                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "VGT":
                    $VIRTUALGENERATIONSERVICES = new VIRTUALGENERATIONSERVICES();
                    $response = $VIRTUALGENERATIONSERVICES->getGame(
                        $Producto->getExternoId(),
                        $this->lan,
                        $isFun,
                        $this->usuarioToken,
                        $this->isMobile,
                        $usuarioMandante
                    );
                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "ITN":

                    try {
                        $UsuarioTokenSite = new UsuarioToken($this->usuarioToken, '0');

                        try {
                            $UsuarioMandante = new UsuarioMandante($UsuarioTokenSite->getUsuarioId());
                            $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
                        } catch (Exception $e) {
                        }

                        return $Usuario->tokenItainment;
                    } catch (Exception $e) {
                    }

                    break;

                case "MGMG":

                    $MICROGAMINGSERVICES = new MICROGAMINGSERVICES();
                    $ProductoDetalle = new ProductoDetalle("", $Producto->getProductoId(), "GAMEID");

                    $response = $MICROGAMINGSERVICES->getGame(
                        $ProductoDetalle->getPValue(),
                        $this->lan,
                        $isFun,
                        $this->usuarioToken,
                        $usuarioMandante
                    );
                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "JOIN":
                    $JOINSERVICES = new JOINSERVICES();

                    $response = $JOINSERVICES->getGamePage(
                        $Producto->getExternoId(),
                        $this->lan,
                        $isFun,
                        $this->usuarioToken,
                        $usuarioMandante
                    );

                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "JOINPOKER":
                    $JOINSERVICES = new JOINSERVICES();

                    $response = $JOINSERVICES->getGame2(
                        $Producto->getExternoId(),
                        $this->lan,
                        $isFun,
                        $this->usuarioToken,
                        $usuarioMandante
                    );

                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "ENPH":
                    $ENDORPHINASERVICES = new ENDORPHINASERVICES();

                    $response = $ENDORPHINASERVICES->getGame(
                        $Producto->getExternoId(),
                        $this->lan,
                        $isFun,
                        $this->usuarioToken,
                        $Producto->getProductoId(),
                        $usuarioMandante
                    );

                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;


                case "BTX":

                    $BETIXONSERVICES = new BETIXONSERVICES();

                    $response = $BETIXONSERVICES->getGame(
                        $Producto->getExternoId(),
                        $this->lan,
                        $isFun,
                        $this->usuarioToken,
                        $Producto->getProductoId(),
                        $usuarioMandante
                    );

                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "ESAGAMING":

                    $ESAGAMINGSERVICES = new ESAGAMINGSERVICES();
                    $response = $ESAGAMINGSERVICES->getGame(
                        $Producto->getExternoId(),
                        $this->lan,
                        $isFun,
                        $this->usuarioToken,
                        $Producto->getProductoId(),
                        $usuarioMandante
                    );

                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "ORYX":

                    $ORYXSERVICES = new ORYXSERVICES();

                    $response = $ORYXSERVICES->getGame(
                        $Producto->getExternoId(),
                        $this->lan,
                        $isFun,
                        $this->usuarioToken,
                        $Producto->getProductoId(),
                        $usuarioMandante
                    );

                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;


                case "QTECH":

                    $QTECHSERVICES = new QTECHSERVICES();

                    $response = $QTECHSERVICES->getGame(
                        $Producto->getExternoId(),
                        $this->lan,
                        $isFun,
                        $this->usuarioToken,
                        $usuarioMandante
                    );

                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;


                case "PLAYNGO":

                    $PLAYNGOSERVICES = new PLAYNGOSERVICES();

                    $response = $PLAYNGOSERVICES->getGame(
                        $Producto->getExternoId(),
                        $this->lan,
                        $isFun,
                        $this->usuarioToken,
                        $Producto->getProductoId(),
                        $this->isMobile,
                        $usuarioMandante
                    );

                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "PLAYSON":

                    $PLAYSONSERVICES = new PLAYSONSERVICES();

                    $response = $PLAYSONSERVICES->getGame(
                        $Producto->getExternoId(),
                        $this->lan,
                        $isFun,
                        $this->usuarioToken,
                        $Producto->getProductoId(),
                        $this->isMobile,
                        $usuarioMandante
                    );

                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "BOSS":

                    $BOSSSERVICES = new BOSSSERVICES();

                    $response = $BOSSSERVICES->getGame(
                        $Producto->getExternoId(),
                        $this->lan,
                        $isFun,
                        $this->usuarioToken,
                        $Producto->getProductoId(),
                        $usuarioMandante
                    );

                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;


                case "PRAGMATIC":

                    $PRAGMATICSERVICES = new PRAGMATICSERVICES();

                    $response = $PRAGMATICSERVICES->getGame(
                        $Producto->getExternoId(),
                        $this->lan,
                        $isFun,
                        $this->usuarioToken,
                        $Producto->getProductoId(),
                        $this->isMobile,
                        $usuarioMandante
                    );

                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "BETGAMESTV":
                    $BETGAMESTVSERVICES = new BETGAMESTVSERVICES();

                    $response = $BETGAMESTVSERVICES->getGame(
                        $Producto->getExternoId(),
                        $this->lan,
                        $isFun,
                        $this->usuarioToken,
                        $Producto->getProductoId(),
                        $this->isMobile,
                        $usuarioMandante
                    );

                    if ( ! $response->error) {
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

                    $response = $VIVOGAMINGSERVICES->getGame(
                        $Producto->getExternoId(),
                        $this->lan,
                        $isFun,
                        $this->usuarioToken,
                        $Producto->getProductoId(),
                        $this->isMobile,
                        $usuarioMandante
                    );

                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;


                case "REDRAKE":

                    $REDRAKESERVICES = new REDRAKESERVICES();

                    $response = $REDRAKESERVICES->getGame(
                        $Producto->getExternoId(),
                        $this->lan,
                        $isFun,
                        $this->usuarioToken,
                        $Producto->getProductoId(),
                        $this->isMobile,
                        $usuarioMandante
                    );

                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;


                case "EVOPLAY":

                    $EVOPLAYSERVICES = new EVOPLAYSERVICES();

                    $response = $EVOPLAYSERVICES->getGame(
                        $Producto->getExternoId(),
                        $this->lan,
                        $isFun,
                        $this->usuarioToken,
                        $Producto->getProductoId(),
                        $this->isMobile,
                        $usuarioMandante
                    );

                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;


                case "XPRESS":

                    $XPRESSSERVICES = new XPRESSSERVICES();

                    $response = $XPRESSSERVICES->getGame(
                        $Producto->getExternoId(),
                        $this->lan,
                        $isFun,
                        $this->usuarioToken,
                        $Producto->getProductoId(),
                        $usuarioMandante,
                        $this->isMobile,
                        $ProductoMandante
                    );

                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "EAGAMING":

                    $EAGAMINGSERVICES = new EAGAMINGSERVICES();

                    $response = $EAGAMINGSERVICES->getGame(
                        $Producto->getExternoId(),
                        $this->lan,
                        $isFun,
                        $this->usuarioToken,
                        $Producto->getProductoId(),
                        $usuarioMandante
                    );

                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;


                case "FAZI":

                    $FAZISERVICES = new FAZISERVICES();

                    $response = $FAZISERVICES->getGame(
                        $Producto->getExternoId(),
                        $this->lan,
                        $isFun,
                        $this->usuarioToken,
                        $Producto->getProductoId(),
                        $usuarioMandante
                    );

                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;


                case "APOLLO":
                    $APOLLOSERVICES = new APOLLOSERVICES();

                    $response = $APOLLOSERVICES->getGame(
                        $Producto->getExternoId(),
                        $this->lan,
                        $isFun,
                        $this->usuarioToken,
                        $Producto->getProductoId(),
                        $usuarioMandante
                    );

                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;


                case "MERKUR":
                    $MERKURSERVICES = new MERKURSERVICES();

                    $response = $MERKURSERVICES->getGame(
                        $Producto->getExternoId(),
                        $this->lan,
                        $isFun,
                        $this->usuarioToken,
                        $Producto->getProductoId(),
                        $usuarioMandante
                    );

                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;


                case "TOMHORN":
                    $TOMHORNSERVICES = new TOMHORNSERVICES();

                    $response = $TOMHORNSERVICES->getGame(
                        $Producto->getExternoId(),
                        $this->lan,
                        $isFun,
                        $this->usuarioToken,
                        $Producto->getProductoId(),
                        $usuarioMandante
                    );

                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Autentica a un usuario mandante y devuelve información relevante.
     *
     * Este método realiza la autenticación de un usuario mandante, verificando su estado
     * y obteniendo información como el saldo, moneda, país, y otros datos. Si el mandante
     * es propio, se realizan validaciones adicionales sobre el estado del usuario. Si no
     * es propio, se realiza una solicitud externa para autenticar al usuario.
     *
     * @param UsuarioMandante $UsuarioMandante Objeto que representa al usuario mandante.
     *
     * @return object Objeto con información del usuario autenticado, incluyendo:
     *                - usuarioId: ID del usuario.
     *                - moneda: Moneda asociada al usuario.
     *                - usuario: Nombre del usuario.
     *                - paisId: ID del país del usuario.
     *                - paisIso2: Código ISO del país del usuario.
     *                - idioma: Idioma del usuario.
     *                - saldo: Saldo del usuario.
     *
     * @throws Exception Si ocurre algún error durante la autenticación o las validaciones.
     */
    public function autenticate($UsuarioMandante = "")
    {
        $Mandante = new Mandante($UsuarioMandante->mandante);

        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $Clasificador = new Clasificador("", "EXCPRODUCT");

            try {
                $UsuarioConfiguracion = new UsuarioConfiguracion(
                    $UsuarioMandante->getUsuarioMandante(),
                    "A",
                    $Clasificador->getClasificadorId(),
                    '3'
                );

                if ($UsuarioConfiguracion->getProductoId() != "") {
                    throw new Exception("EXCPRODUCT", "20004");
                }
            } catch (Exception $e) {
                if ($e->getCode() != 46) {
                    throw $e;
                }
            }
        }


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
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);
            $Pais = new Pais($Usuario->paisId);

            if ($Usuario->estado != "A") {
                throw new Exception("Usuario Inactivo", "20003");
            }

            if ($Usuario->contingencia == "A") {
                throw new Exception("Usuario Contingencia", "20024");
            }

            if ($Usuario->contingenciaCasino == "A") {
                throw new Exception("Usuario Contingencia", "20024");
            }

            switch ($UsuarioPerfil->getPerfilId()) {
                case "USUONLINE":
                    $Balance = $Usuario->getBalance();
                    break;

                case "MAQUINAANONIMA":

                    $Balance = $Usuario->getBalance();

                    break;
            }

            $respuesta->usuarioId = $UsuarioMandante->usumandanteId;
            $respuesta->moneda = $UsuarioMandante->moneda;
            $respuesta->usuario = "Usuario" . $UsuarioMandante->usumandanteId;
            $respuesta->paisId = $Pais->paisId;
            $respuesta->paisIso2 = $Pais->iso;
            $respuesta->idioma = 'ES';
            $respuesta->saldo = $Balance;
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

            if ($userid == "" || ! is_numeric($userid)) {
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
            $respuesta->saldo = $balance;
        }

        return $respuesta;
    }

    /**
     * Obtiene el balance de un usuario mandante.
     *
     * Este método verifica si el mandante es propio o externo y realiza las validaciones
     * necesarias para obtener el balance del usuario. Si el mandante es externo, se realiza
     * una solicitud externa para obtener el balance.
     *
     * @param UsuarioMandante $UsuarioMandante Objeto que representa al usuario mandante.
     *
     * @return object Objeto con información del balance del usuario, incluyendo:
     *                - usuarioId: ID del usuario.
     *                - moneda: Moneda asociada al usuario.
     *                - usuario: Nombre del usuario.
     *                - saldo: Saldo del usuario.
     *
     * @throws Exception Si ocurre algún error durante las validaciones o la solicitud externa.
     */
    public function getBalance($UsuarioMandante = "")
    {
        $Mandante = new Mandante($UsuarioMandante->mandante);

        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $Clasificador = new Clasificador("", "EXCPRODUCT");

            try {
                $UsuarioConfiguracion = new UsuarioConfiguracion(
                    $UsuarioMandante->getUsuarioMandante(),
                    "A",
                    $Clasificador->getClasificadorId(),
                    '3'
                );

                if ($UsuarioConfiguracion->getProductoId() != "") {
                    throw new Exception("EXCPRODUCT", "20004");
                }
            } catch (Exception $e) {
                if ($e->getCode() != 46) {
                    throw $e;
                }
            }
        }


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

            if ($userid == "" || ! is_numeric($userid)) {
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
    }

    /**
     * Realiza un débito en el sistema, verificando múltiples condiciones y reglas de negocio.
     *
     * Este método maneja tanto mandantes propios como externos, validando configuraciones,
     * límites, y procesando transacciones relacionadas con apuestas y juegos.
     *
     * @param UsuarioMandante $UsuarioMandante       Objeto que representa al usuario mandante.
     * @param Producto        $Producto              Objeto que representa el producto asociado al débito.
     * @param TransaccionApi  $transaccionApi        Objeto que contiene los datos de la transacción API.
     * @param bool            $free                  Indica si el débito es gratuito (por ejemplo, giros gratis).
     * @param array           $bets                  Lista de apuestas asociadas al débito.
     * @param bool            $ExisteTicketPermitido Indica si se permite la existencia de tickets duplicados.
     *
     * @return object Objeto con información de la transacción, incluyendo:
     *                - usuarioId: ID del usuario.
     *                - moneda: Moneda asociada al usuario.
     *                - usuario: Nombre del usuario.
     *                - transaccionId: ID de la transacción.
     *                - saldo: Saldo del usuario después del débito.
     *                - transaccionApi: Objeto de la transacción API.
     *
     * @throws Exception Si ocurre algún error durante las validaciones o el procesamiento.
     */
    public function debit(
        $UsuarioMandante,
        $Producto,
        $transaccionApi,
        $free = false,
        $bets = [],
        $ExisteTicketPermitido = true
    ) {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            try {
                $tipo = "EXCTIMEOUT";
                $Tipo = new Clasificador("", $tipo);
                $UsuarioConfiguracion = new UsuarioConfiguracion(
                    $UsuarioMandante->getUsuarioMandante(),
                    'A',
                    $Tipo->getClasificadorId()
                );

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
                $UsuarioConfiguracion = new UsuarioConfiguracion(
                    $UsuarioMandante->getUsuarioMandante(),
                    "A",
                    $Clasificador->getClasificadorId(),
                    '3'
                );

                if ($UsuarioConfiguracion->getProductoId() != "") {
                    throw new Exception("EXCPRODUCT", "20004");
                }
            } catch (Exception $e) {
                if ($e->getCode() != 46) {
                    throw $e;
                }
            }

            $CategoriaProducto = new CategoriaProducto("", $Producto->getProductoId(), "CASINO");

            $Categoria = new Categoria ($CategoriaProducto->getCategoriaId());

            $categoriaId = 0;
            $subcategoriaId = 0;

            if ($Categoria->getSuperior() == 0) {
                $categoriaId = $Categoria->getCategoriaId();
            } else {
                $categoriaId = $Categoria->getSuperior();

                $subcategoriaId = $Categoria->getCategoriaId();
            }
            if ($categoriaId != 0) {
                $Clasificador = new Clasificador("", "EXCCASINOCATEGORY");

                try {
                    $UsuarioConfiguracion = new UsuarioConfiguracion(
                        $UsuarioMandante->getUsuarioMandante(),
                        "A",
                        $Clasificador->getClasificadorId(),
                        $categoriaId
                    );


                    if ($UsuarioConfiguracion->getProductoId() != "") {
                        throw new Exception("EXCCASINOCATEGORY", "20005");
                    }
                } catch (Exception $e) {
                    if ($e->getCode() != 46) {
                        throw $e;
                    }
                }
            }


            if ($subcategoriaId != 0) {
                $Clasificador = new Clasificador("", "EXCCASINOSUBCATEGORY");

                try {
                    $UsuarioConfiguracion = new UsuarioConfiguracion(
                        $UsuarioMandante->getUsuarioMandante(),
                        "A",
                        $Clasificador->getClasificadorId(),
                        $subcategoriaId
                    );

                    if ($UsuarioConfiguracion->getProductoId() != "") {
                        throw new Exception("EXCCASINOSUBCATEGORY", "20006");
                    }
                } catch (Exception $e) {
                    if ($e->getCode() != 46) {
                        throw $e;
                    }
                }
            }

            $Clasificador = new Clasificador("", "EXCCASINOGAME");

            try {
                $UsuarioConfiguracion = new UsuarioConfiguracion(
                    $UsuarioMandante->getUsuarioMandante(),
                    "A",
                    $Clasificador->getClasificadorId(),
                    $Producto->getProductoId()
                );

                if ($UsuarioConfiguracion->getProductoId() != "") {
                    throw new Exception("EXCCASINOGAME", "20007");
                }
            } catch (Exception $e) {
                if ($e->getCode() != 46) {
                    throw $e;
                }
            }
        }

        if ($UsuarioMandante->usumandanteId == 16) {
            $Proveedor = new Proveedor($Producto->getProveedorId());

            $result = '0';
            if ($Proveedor->getTipo() == 'CASINO') {
                $UsuarioConfiguracion = new UsuarioConfiguracion();

                $UsuarioConfiguracion->setUsuarioId($UsuarioMandante->usuarioMandante);
                $result = $UsuarioConfiguracion->verifyLimitesCasino($transaccionApi->getValor());
            } elseif ($Proveedor->getTipo() == 'LIVECASINO') {
                $UsuarioConfiguracion = new UsuarioConfiguracion();

                $UsuarioConfiguracion->setUsuarioId($UsuarioMandante->usuarioMandante);
                $result = $UsuarioConfiguracion->verifyLimitesCasinoVivo($transaccionApi->getValor());
            }

            if ($result != '0') {
                throw new Exception("Limite de Autoexclusion", $result);
            }
        }


        $log = microtime() . "-----------T1--------------" . "\r\n";

        $debitAmount = $transaccionApi->getValor();

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

        //  Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
        $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());


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


        $log = microtime() . "-----------T3--------------" . "\r\n";


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


        $log = microtime() . "-----------T4--------------" . "\r\n";


        // Obtenemos la Transaccion de la BD para crear y registrar el Debito con esta transaccion
        $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
        $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

        if ($bets == []) {
            array_push($bets, array(
                "id" => $transaccionApi->getIdentificador(),
                "amount" => $debitAmount,
                "transactionId" => $transactionId
            ));
        }

        foreach ($bets as $bet) {
            $identificador = $bet["id"];
            $amount = $bet["amount"];
            $transactionId = $bet["transactionId"];

            //  Creamos la Transaccion por el Juego
            $TransaccionJuego = new TransaccionJuego();
            $TransaccionJuego->setProductoId($ProductoMandante->prodmandanteId);
            $TransaccionJuego->setTransaccionId($transactionId);
            $TransaccionJuego->setTicketId($identificador);
            $TransaccionJuego->setValorTicket($amount);
            $TransaccionJuego->setValorPremio(0);
            $TransaccionJuego->setMandante($UsuarioMandante->mandante);
            $TransaccionJuego->setUsuarioId($UsuarioMandante->usumandanteId);
            $TransaccionJuego->setEstado("A");
            $TransaccionJuego->setUsucreaId(0);
            $TransaccionJuego->setUsumodifId(0);
            $TransaccionJuego->setTipo('NORMAL');
            $TransaccionJuego->setPremiado('N');
            $TransaccionJuego->setFechaPago(date('Y-m-d H:i:s', time()));

            if ($free) {
                $TransaccionJuego->setTipo('FREESPIN');
            }

            $ExisteTicket = false;


            //  Verificamos si existe el ticket_id antes, de ser positivo, tendriamos que combinar las apuestas
            if ($TransaccionJuego->existsTicketId()) {
                $ExisteTicket = true;
            }
            if ( ! $ExisteTicketPermitido && $ExisteTicket) {
                //  Si la transaccionId ha sido procesada, reportamos el error
                throw new Exception("Ticket ID ya existe", "10025");
            }


            $log = microtime() . "-----------T5--------------" . "\r\n";

            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

            //  Obtenemos el mandante para verificar sus caracteristicas
            $Mandante = new Mandante($UsuarioMandante->mandante);


            //  Verificamos si Existe el ticket para combinar las apuestas.
            if ($ExisteTicket) {
                //  Obtenemos la Transaccion Juego y combinamos las aúestas.
                $TransaccionJuego = new TransaccionJuego("", $identificador, "");

                if ($TransaccionJuego->getTransaccionId() != $transactionId) {
                    $TransaccionJuego->setValorTicket(' valor_ticket + ' . $transaccionApi->getValor());
                    $TransaccionJuego->update($Transaction);
                }

                $transaccion_id = $TransaccionJuego->getTransjuegoId();
            } else {
                $transaccion_id = $TransaccionJuego->insert($Transaction);
            }


            $log = microtime() . "-----------T6--------------" . "\r\n";

            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

            $saldoCreditos = 0;
            $saldoCreditosBase = 0;
            $saldoBonos = 0;
            $saldoFree = 0;

            //  Verificamos que el mandante sea Propio, para proceder con nuestros Usuarios
            if ($Mandante->propio == "S") {
                $log = microtime() . "-----------T6-1--------------" . "\r\n";


                //  Obtenemos nuestro Usuario y hacemos el debito
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);


                $log = microtime() . "-----------T6-2--------------" . "\r\n";


                $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);


                $log = microtime() . "-----------T6-3--------------" . "\r\n";


                if ($Usuario->estado != "A") {
                    throw new Exception("Usuario Inactivo", "20003");
                }

                if ($Usuario->contingencia == "A") {
                    throw new Exception("Usuario Contingencia", "20024");
                }

                if ($Usuario->contingenciaCasino == "A") {
                    throw new Exception("Usuario Contingencia", "20024");
                }


                if ( ! $free) {
                    if ($UsuarioMandante->mandante == '2') {
                        if (floatval($Usuario->getBalance()) > 1000000) {
                            throw new Exception(
                                "You cannot continue the operation, because you have more than 600,000 in your balance",
                                100030
                            );
                        }
                    }

                    switch ($UsuarioPerfil->getPerfilId()) {
                        case "USUONLINE":
                            $Proveedor = new Proveedor($Producto->getProveedorId());

                            if ($ConfigurationEnvironment->isDevelopment() || $Proveedor->abreviado == 'ENPH') {
                                $BonoInterno = new BonoInterno();


                                $detalles = array(
                                    "TransaccionApi" => $transaccionApi
                                );
                                $detalles = json_encode($detalles);

                                $Subproveedor = new Subproveedor($Producto->getSubproveedorId());

                                if ($Subproveedor->getTipo() == "VIRTUAL") {
                                    $tipoProducto = "VIRTUAL";
                                } elseif ($Subproveedor->getTipo() == "LIVECASINO") {
                                    $tipoProducto = "LIVECASINO";
                                } else {
                                    $tipoProducto = "CASINO";
                                }


                                $responseFree = $BonoInterno->verificarBonoFree(
                                    $UsuarioMandante,
                                    $detalles,
                                    $tipoProducto,
                                    $Transaction,
                                    $transaccionApi
                                );

                                if ($responseFree->WinBonus) {
                                    $amount = $responseFree->AmountDebit;
                                    $debitBonus = $responseFree->AmountBonus;

                                    $saldoFree = $debitBonus;

                                    $TransaccionJuego->setTipo('FREECASH');


                                    $TransaccionJuego->setValorTicket($amount);
                                    $TransaccionJuego->setValorGratis($debitBonus);
                                }
                            }
                            if ($ConfigurationEnvironment->isDevelopment()) {
                                $Usuario->debit($amount, $Transaction, 1, true);
                            } else {
                                $Usuario->debit($amount, $Transaction, 1);
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
            } else {
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

                    $TransaccionApiMandanteMySqlDAO = new TransaccionApiMandanteMySqlDAO();
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
                    $Transaction->rollback();
                    $codeException = $e->getCode();
                    $messageException = $e->getMessage();

                    $TransaccionApiMandante->setRespuestaCodigo($codeException);
                    $TransaccionApiMandanteMySqlDAO = new TransaccionApiMandanteMySqlDAO();
                    $TransaccionApiMandanteMySqlDAO->update($TransaccionApiMandante);
                    $TransaccionApiMandanteMySqlDAO->getTransaction()->commit();

                    $this->convertErrorMandante($codeException, $messageException);
                }
            }

            if (floatval($saldoFree) > 0) {
                $TransaccionJuego->update($Transaction);
            }


            $log = microtime() . "-----------T7--------------" . "\r\n";

            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

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

            $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);


            $log = microtime() . "-----------T8--------------" . "\r\n";

            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


            if ($Mandante->propio == "S") {
                $UsuarioHistorial = new UsuarioHistorial();
                $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                $UsuarioHistorial->setDescripcion('');
                $UsuarioHistorial->setMovimiento('S');
                $UsuarioHistorial->setUsucreaId(0);
                $UsuarioHistorial->setUsumodifId(0);
                $UsuarioHistorial->setTipo(30);
                $UsuarioHistorial->setValor($amount);
                $UsuarioHistorial->setExternoId($TransjuegoLog_id);

                $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);
            }
        }


        $log = microtime() . "-----------T9--------------" . "\r\n";


        $Transaction->commit();


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
                    $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                    //  Enviamos el mensaje Websocket al Usuario para que actualice el saldo

                    $UsuarioSession = new UsuarioSession();
                    $rules = [];

                    array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                    array_push(
                        $rules,
                        array(
                            "field" => "usuario_session.usuario_id",
                            "data" => $UsuarioMandante->getUsumandanteId(),
                            "op" => "eq"
                        )
                    );

                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json = json_encode($filtro);


                    $usuarios = $UsuarioSession->getUsuariosCustom(
                        "usuario_session.*",
                        "usuario_session.ususession_id",
                        "asc",
                        0,
                        100,
                        $json,
                        true
                    );

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
        $transaccionApi->setRespuestaCodigo('OK');
        $transaccionApi->setRespuesta('');
        $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
        $TransaccionApiMySqlDAO->insert($transaccionApi);

        if ($TransaccionApiMandante != "" && $TransaccionApiMandante != null) {
            $TransaccionApiMandante->setTransapiId($transaccionApi->transapiId);
            $TransaccionApiMandanteMySqlDAO = new TransaccionApiMandanteMySqlDAO(
                $TransaccionApiMySqlDAO->getTransaction()
            );
            $TransaccionApiMandanteMySqlDAO->update($TransaccionApiMandante);
        }

        $TransaccionApiMySqlDAO->getTransaction()->commit();


        $respuesta->usuarioId = $UsuarioMandante->usumandanteId;
        $respuesta->moneda = $UsuarioMandante->moneda;
        $respuesta->usuario = "Usuario" . $UsuarioMandante->usumandanteId;
        $respuesta->saldo = $Balance;
        $respuesta->transaccionId = $TransjuegoLog_id;
        $respuesta->transaccionApi = $transaccionApi;

        if ( ! $free) {
            $typeP = "CASINO";

            $Proveedor = new Proveedor($Producto->getProveedorId());

            if ($Proveedor->getTipo() == 'CASINO') {
                $typeP = "CASINO";
            } elseif ($Proveedor->getTipo() == 'LIVECASINO') {
                $typeP = "LIVECASINO";
            }

            if ($UsuarioMandante->usuarioMandante == 886) {
            }

            exec(
                "php -f " . __DIR__ . "/VerificarTorneo.php " . $typeP . " " . $transaccionApi->transapiId . " " . $UsuarioMandante->usuarioMandante . " > /dev/null &"
            );
        }

        if ($saldoFree != null && $saldoFree > 0) {
        }

        return $respuesta;
    }

    /**
     * Realiza una operación de crédito para un usuario en un juego.
     *
     * @param object $UsuarioMandante   Objeto que representa al usuario mandante.
     * @param object $Producto          Objeto que representa el producto asociado al juego.
     * @param object $transaccionApi    Objeto que contiene los datos de la transacción API.
     * @param bool   $isEndRound        Indica si la ronda ha finalizado.
     * @param bool   $onlyOneWin        Indica si solo se permite un único crédito por ganancia.
     * @param bool   $free              Indica si la transacción es gratuita (freespin).
     * @param bool   $allowChangIfIsEnd Indica si se permiten cambios después de que el ticket esté cerrado.
     *
     * @return object Respuesta con los detalles de la transacción, saldo y usuario.
     *
     * @throws Exception Si ocurren errores como montos negativos, transacciones duplicadas,
     *                   tickets cerrados, o errores en la comunicación con el mandante.
     */
    public function credit(
        $UsuarioMandante,
        $Producto,
        $transaccionApi,
        $isEndRound,
        $onlyOneWin = false,
        $free = false,
        $allowChangIfIsEnd = true
    ) {
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
        $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

        //  Agregamos Elementos a la Transaccion API
        $transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
        $transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());


        //  Verificamos que la transaccionId no se haya procesado antes
        if ($transaccionApi->existsTransaccionIdAndProveedor("OK")) {
            //  Si la transaccionId ha sido procesada, reportamos el error
            throw new Exception("Transaccion ya procesada", "10001");
        }


        //  Obtenemos la Transaccion Juego
        $TransaccionJuego = new TransaccionJuego("", $transaccionApi->getIdentificador());


        $log = date("Y-m-d H:i:s") . "-----------T2--------------" . "\r\n";


        if ($TransaccionJuego->getEstado() == 'I' && ! $allowChangIfIsEnd) {
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


        //  Creamos el respectivo Log de la transaccion Juego
        $TransjuegoLog = new TransjuegoLog();
        $TransjuegoLog->setTransjuegoId($TransJuegoId);
        $TransjuegoLog->setTransaccionId($transaccionApi->getTransaccionId());
        $TransjuegoLog->setTipo($tipoTransaccion);
        $TransjuegoLog->setTValue($transaccionApi->getTValue());
        $TransjuegoLog->setUsucreaId(0);
        $TransjuegoLog->setUsumodifId(0);
        $TransjuegoLog->setValor($creditAmount);

        $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);

        $log = date("Y-m-d H:i:s") . "-----------T3--------------" . "\r\n";

        if ($onlyOneWin) {
            if ( ! $TransjuegoLog->isEqualsNewCredit()) {
                // Si el numero de creditos es mayor al de los debitos sacamos error
                throw new Exception("CREDIT MAYOR A DEBIT", "10014");
            }
        }

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
                            $Usuario->creditWin($creditAmount, $Transaction);
                            $UsuarioHistorial = new UsuarioHistorial();
                            $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                            $UsuarioHistorial->setDescripcion('');
                            $UsuarioHistorial->setMovimiento('E');
                            $UsuarioHistorial->setUsucreaId(0);
                            $UsuarioHistorial->setUsumodifId(0);
                            $UsuarioHistorial->setTipo(30);
                            $UsuarioHistorial->setValor($creditAmount);
                            $UsuarioHistorial->setExternoId($TransjuegoLog_id);

                            $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                            $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);
                            break;

                        case "MAQUINAANONIMA":

                            $Usuario->creditWin($creditAmount, $Transaction);
                            $UsuarioHistorial = new UsuarioHistorial();
                            $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                            $UsuarioHistorial->setDescripcion('');
                            $UsuarioHistorial->setMovimiento('E');
                            $UsuarioHistorial->setUsucreaId(0);
                            $UsuarioHistorial->setUsumodifId(0);
                            $UsuarioHistorial->setTipo(30);
                            $UsuarioHistorial->setValor($creditAmount);
                            $UsuarioHistorial->setExternoId($TransjuegoLog_id);

                            $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                            $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

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
                $TransaccionApiMandante->setUsucreaId(0);
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
                $Transaction->rollBack();
                $codeException = $e->getCode();
                $messageException = $e->getMessage();

                $TransaccionApiMandante->setRespuestaCodigo($codeException);
                $TransaccionApiMandanteMySqlDAO = new TransaccionApiMandanteMySqlDAO();
                $TransaccionApiMandanteMySqlDAO->update($TransaccionApiMandante);
                $TransaccionApiMandanteMySqlDAO->getTransaction()->commit();

                $this->convertErrorMandante($codeException, $messageException);
            }
        }


        $log = date("Y-m-d H:i:s") . "-----------T5--------------" . "\r\n";

        // Commit de la transacción
        $Transaction->commit();

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
                    $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                    //  Enviamos el mensaje Websocket al Usuario para que actualice el saldo

                    $UsuarioSession = new UsuarioSession();
                    $rules = [];

                    array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                    array_push(
                        $rules,
                        array(
                            "field" => "usuario_session.usuario_id",
                            "data" => $UsuarioMandante->getUsumandanteId(),
                            "op" => "eq"
                        )
                    );

                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json = json_encode($filtro);


                    $usuarios = $UsuarioSession->getUsuariosCustom(
                        "usuario_session.*",
                        "usuario_session.ususession_id",
                        "asc",
                        0,
                        100,
                        $json,
                        true
                    );

                    $usuarios = json_decode($usuarios);

                    $usuariosFinal = [];

                    foreach ($usuarios->data as $key => $value) {
                        $data = $UsuarioMandante->getWSProfileSite($value->{'usuario_session.request_id'});

                        $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                        $WebsocketUsuario->sendWSMessage();
                    }
                }
            }
            //  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket
            $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

            $ConfigurationEnvironment = new ConfigurationEnvironment();

            if ($ConfigurationEnvironment->isDevelopment() && false) {
                $UsuarioToken = new UsuarioToken("", '1', $UsuarioMandante->getUsumandanteId());

                $data = $UsuarioMandante->getWSProfileSite($UsuarioToken->getRequestId());
                $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                $WebsocketUsuario->sendWSMessage();
            }
        }

        // Guardamos la Transaccion Api necesaria de estado OK
        $transaccionApi->setRespuestaCodigo("OK");
        $transaccionApi->setRespuesta('');
        $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
        $TransaccionApiMySqlDAO->insert($transaccionApi);

        if ($TransaccionApiMandante != "" && $TransaccionApiMandante != null) {
            $TransaccionApiMandante->setTransapiId($transaccionApi->transapiId);
            $TransaccionApiMandanteMySqlDAO = new TransaccionApiMandanteMySqlDAO(
                $TransaccionApiMySqlDAO->getTransaction()
            );
            $TransaccionApiMandanteMySqlDAO->update($TransaccionApiMandante);
        }


        $TransaccionApiMySqlDAO->getTransaction()->commit();


        if ($sumaCreditos) {
            $typeP = "CASINO";

            $Proveedor = new Proveedor($Producto->getProveedorId());

            if ($Proveedor->getTipo() == 'CASINO') {
                $typeP = "CASINO";
            } elseif ($Proveedor->getTipo() == 'LIVECASINO') {
                $typeP = "LIVECASINO";
            }

            exec(
                "php -f " . __DIR__ . "/VerificarTorneoPremio.php " . $typeP . " " . $transaccionApi->transapiId . " " . $UsuarioMandante->usuarioMandante . " > /dev/null &"
            );
        }

        $respuesta->usuarioId = $UsuarioMandante->usumandanteId;
        $respuesta->moneda = $UsuarioMandante->moneda;
        $respuesta->usuario = "Usuario" . $UsuarioMandante->usumandanteId;
        $respuesta->saldo = $Balance;
        $respuesta->transaccionId = $TransjuegoLog_id;
        $respuesta->transaccionApi = $transaccionApi;


        $log = date("Y-m-d H:i:s") . "-----------TU--------------" . "\r\n";


        return $respuesta;
    }

    /**
     * Finaliza una ronda de juego para un usuario.
     *
     * Este método actualiza el estado de la transacción del juego a "I" (inactiva),
     * verifica las características del mandante y realiza las operaciones necesarias
     * para finalizar la ronda, como actualizar el saldo del usuario y enviar mensajes
     * WebSocket si es necesario.
     *
     * @param object $transaccionApi Objeto que contiene los datos de la transacción API.
     *
     * @return object Respuesta con los detalles de la transacción, saldo y usuario.
     *
     * @throws Exception Si ocurren errores como usuario inactivo, datos inconsistentes
     *                   o errores en la comunicación con el mandante.
     */
    public function endRound($transaccionApi)
    {
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

        $TransaccionJuego->setEstado("I");

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
            $Balance = $result->player->balance;
            $currency = $result->player->currency;

            if ($userid == "" || ! is_numeric($userid)) {
                throw new Exception("No coinciden ", "50001");
            }

            if ($Balance == "") {
                throw new Exception("No coinciden ", "50001");
            }

            if ($currency == "") {
                throw new Exception("No coinciden ", "50001");
            }
        }

        // Commit de la transacción
        $Transaction->commit();

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
                    $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                    //  Enviamos el mensaje Websocket al Usuario para que actualice el saldo

                    $UsuarioSession = new UsuarioSession();
                    $rules = [];

                    array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                    array_push(
                        $rules,
                        array(
                            "field" => "usuario_session.usuario_id",
                            "data" => $UsuarioMandante->getUsumandanteId(),
                            "op" => "eq"
                        )
                    );

                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json = json_encode($filtro);


                    $usuarios = $UsuarioSession->getUsuariosCustom(
                        "usuario_session.*",
                        "usuario_session.ususession_id",
                        "asc",
                        0,
                        100,
                        $json,
                        true
                    );

                    $usuarios = json_decode($usuarios);

                    $usuariosFinal = [];

                    foreach ($usuarios->data as $key => $value) {
                        $data = $UsuarioMandante->getWSProfileSite($value->{'usuario_session.request_id'});

                        $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                        $WebsocketUsuario->sendWSMessage();
                    }
                }
            }
            //  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket
            $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

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
        $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
        $TransaccionApiMySqlDAO->insert($transaccionApi);

        $TransaccionApiMySqlDAO->getTransaction()->commit();


        $respuesta->usuarioId = $UsuarioMandante->usumandanteId;
        $respuesta->moneda = $UsuarioMandante->moneda;
        $respuesta->usuario = "Usuario" . $UsuarioMandante->usumandanteId;
        $respuesta->saldo = $Balance;
        $respuesta->transaccionId = $transaccionApi->transapiId;
        $respuesta->transaccionApi = $transaccionApi;

        return $respuesta;
    }

    /**
     * Realiza un rollback de una transacción de juego.
     *
     * Este método revierte una transacción previamente realizada, asegurándose de que
     * los valores sean consistentes y que no se procesen transacciones duplicadas.
     * También actualiza los registros relacionados y maneja las características del mandante.
     *
     * @param object $UsuarioMandante       Objeto que representa al usuario mandante.
     * @param object $Proveedor             Objeto que representa al proveedor del juego.
     * @param object $transaccionApi        Objeto que contiene los datos de la transacción API.
     * @param bool   $validacionValorTicket Indica si se debe validar que el valor del ticket coincida con el rollback.
     * @param string $TransaccionEspecifica Identificador de una transacción específica para el rollback (opcional).
     * @param bool   $allowChangIfIsEnd     Indica si se permiten cambios después de que el ticket esté cerrado.
     *
     * @return object Respuesta con los detalles de la transacción, saldo y usuario.
     *
     * @throws Exception Si ocurren errores como transacciones duplicadas, valores inconsistentes,
     *                   tickets cerrados o errores en la comunicación con el mandante.
     */
    public function rollback(
        $UsuarioMandante,
        $Proveedor,
        $transaccionApi,
        $validacionValorTicket = true,
        $TransaccionEspecifica = '',
        $allowChangIfIsEnd = true
    ) {
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

        //  Agregamos Elementos a la Transaccion API
        $transaccionApi->setProveedorId($Proveedor->getProveedorId());
        $transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());

        //  Verificamos que la transaccionId no se haya procesado antes
        if ($transaccionApi->existsTransaccionIdAndProveedor("OK")) {
            //  Si la transaccionId ha sido procesada, reportamos el error
            throw new Exception("Transaccion ya procesada", "10001");
        }

        $transaccionApi->setValor(0);

        try {
            $TransaccionApi2 = new TransaccionApi("", $transaccionIdARollback, $Proveedor->getProveedorId());
            $jsonValue = json_decode($TransaccionApi2->getTValue());
            $valorTransaction = 0;

            if ($UsuarioMandante->getUsumandanteId() == '') {
                $transaccionApi->setUsuarioId($TransaccionApi2->getUsuarioId());
            }

            if (strpos($TransaccionApi2->getTipo(), 'DEBIT') !== false) {
                $valorTransaction = $jsonValue->debitAmount;
            } else {
                throw new Exception("Transaccion no es Debit", "10006");
            }
        } catch (Exception $e) {
            throw new Exception("Transaccion no existe", "10005");
        }

        $rollbackAmount = $TransaccionApi2->getValor();

        // Verificamos que el monto a creditar sea positivo
        if ($rollbackAmount < 0) {
            throw new Exception("No puede ser negativo el monto a debitar.", "10002");
        }

        $transaccionApi->setValor($TransaccionApi2->getValor());

        // Creamos la Transaccion por el Juego
        $TransaccionJuego = new TransaccionJuego("", $transaccionApi->getIdentificador());

        // Obtenemos la Transaccion de la BD para crear y registrar el Debito con esta transaccion
        $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
        $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

        if ($TransaccionJuego->getEstado() == 'I' && ! $allowChangIfIsEnd) {
            //  Si la transaccion no es permitido para cambios despues de cerrado el ticket
            throw new Exception("El ticket ya esta cerrado", "10027");
        }

        //  Verificamos que el valor del ticket sea igual al valor del Rollback
        if ($validacionValorTicket && $TransaccionJuego->getValorTicket() != $rollbackAmount) {
            throw new Exception("Valor ticket diferente al Rollback", "10003");
        }

        //  Actualizamos Transaccion Juego
        $TransaccionJuego->setEstado("I");
        $TransaccionJuego->setValorTicket(' valor_ticket - ' . $rollbackAmount);
        $TransaccionJuego->update($Transaction);

        //  Obtenemos el Transaccion Juego ID
        $TransJuegoId = $TransaccionJuego->getTransjuegoId();

        //  Creamos el Log de Transaccion Juego
        $TransjuegoLog = new TransjuegoLog();
        $TransjuegoLog->setTransjuegoId($TransJuegoId);
        $TransjuegoLog->setTransaccionId($transaccionApi->getTransaccionId());
        $TransjuegoLog->setTipo("ROLLBACK");
        $TransjuegoLog->setTValue($transaccionApi->getTValue());
        $TransjuegoLog->setUsucreaId(0);
        $TransjuegoLog->setUsumodifId(0);
        $TransjuegoLog->setValor($rollbackAmount);

        $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);


        //  Obtenemos Mandante para verificar sus caracteristicas
        $Mandante = new Mandante($UsuarioMandante->mandante);


        //  Verificamos si el mandante es Propio
        if ($Mandante->propio == "S") {
            //  Obtenemos el Usuario para hacerle el credito
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);

            switch ($UsuarioPerfil->getPerfilId()) {
                case "USUONLINE":
                    $Usuario->credit($rollbackAmount, $Transaction);
                    $UsuarioHistorial = new UsuarioHistorial();
                    $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                    $UsuarioHistorial->setDescripcion('');
                    $UsuarioHistorial->setMovimiento('C');
                    $UsuarioHistorial->setUsucreaId(0);
                    $UsuarioHistorial->setUsumodifId(0);
                    $UsuarioHistorial->setTipo(30);
                    $UsuarioHistorial->setValor($rollbackAmount);
                    $UsuarioHistorial->setExternoId($TransjuegoLog_id);

                    $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                    $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

                    break;

                case "MAQUINAANONIMA":

                    $Usuario->credit($rollbackAmount, $Transaction);
                    $UsuarioHistorial = new UsuarioHistorial();
                    $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                    $UsuarioHistorial->setDescripcion('');
                    $UsuarioHistorial->setMovimiento('C');
                    $UsuarioHistorial->setUsucreaId(0);
                    $UsuarioHistorial->setUsumodifId(0);
                    $UsuarioHistorial->setTipo(30);
                    $UsuarioHistorial->setValor($rollbackAmount);
                    $UsuarioHistorial->setExternoId($TransjuegoLog_id);

                    $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                    $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

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

                $TransaccionApiMandanteMySqlDAO = new TransaccionApiMandanteMySqlDAO($Transaction);
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
                    //throw new Exception("Error en mandante ", $code);
                }

                if ($balance == "") {
                    throw new Exception("No coinciden ", "50001");
                }

                if ($transactionIdMandante == "") {
                    throw new Exception("No coinciden ", "50001");
                }

                $Balance = $balance;
            } catch (Exception $e) {
                $Transaction->rollback();
                $codeException = $e->getCode();
                $messageException = $e->getMessage();

                $TransaccionApiMandante->setRespuestaCodigo($codeException);
                $TransaccionApiMandanteMySqlDAO = new TransaccionApiMandanteMySqlDAO();
                $TransaccionApiMandanteMySqlDAO->update($TransaccionApiMandante);
                $TransaccionApiMandanteMySqlDAO->getTransaction()->commit();


                $this->convertErrorMandante($codeException, $messageException);
            }
        }

        // Commit de la transacción
        $Transaction->commit();


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
            }
        }

        //  Guardamos la Transaccion Api necesaria de estado OK
        $transaccionApi->setRespuestaCodigo("OK");
        $transaccionApi->setRespuesta('');
        $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
        $TransaccionApiMySqlDAO->insert($transaccionApi);

        if ($TransaccionApiMandante != "" && $TransaccionApiMandante != null) {
            $TransaccionApiMandante->setTransapiId($transaccionApi->transapiId);
            $TransaccionApiMandanteMySqlDAO = new TransaccionApiMandanteMySqlDAO(
                $TransaccionApiMySqlDAO->getTransaction()
            );
            $TransaccionApiMandanteMySqlDAO->update($TransaccionApiMandante);
        }

        $TransaccionApiMySqlDAO->getTransaction()->commit();


        $respuesta->usuarioId = $UsuarioMandante->usumandanteId;
        $respuesta->moneda = $UsuarioMandante->moneda;
        $respuesta->usuario = "Usuario" . $UsuarioMandante->usumandanteId;
        $respuesta->saldo = $Balance;
        $respuesta->transaccionId = $TransjuegoLog_id;
        $respuesta->transaccionApi = $transaccionApi;


        return $respuesta;
    }

    /**
     * Converts error codes from the mandante (client) into exceptions.
     *
     * This method maps specific error codes to corresponding exceptions with predefined
     * error messages and codes. If the error code does not match any predefined case,
     * it throws a generic exception with the provided code.
     *
     * @param string $code    The error code received from the mandante.
     * @param string $message The error message associated with the error code.
     *
     * @return void
     * @throws Exception If the error code matches a predefined case or is unrecognized.
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
                throw new Exception("", $code);
                break;
        }
    }

    /**
     * Redirects the user to the provided URL if the application is running in a mobile environment
     * and not inside an app (`in_app` is not equal to '1').
     *
     * @param string $in_app Indicates if the application is running inside an app ('1' if true).
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

                    <?
                } elseif ($provider == "VGT") {
                    if ($mode == 'fun') {
                        print('<div style="width: 100%;height: 97%;background: black;background: url(&quot;' . $bgCasino . '&quot;) 50% 0px no-repeat;font: 15px/20px Quicksand,Arial,Helvetica,sans-serif;"><div style="
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
                        print('<iframe frameborder="0" src="' . explode(
                                "&lobby",
                                $URL
                            )[0] . '" class="embed-responsive-item" style="width: 100%;height: 100%;"></iframe>');
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
            /*
            if($user_token=="0167wtapy61pnedcdcmvf34uwpq4x7"){
                echo "
              <script>
                var hasFlash = false;
                try {
                  hasFlash = Boolean(new ActiveXObject('ShockwaveFlash.ShockwaveFlash'));
                } catch (exception) {
                  hasFlash = ('undefined' != typeof navigator.mimeTypes['application/x-shockwave-flash']);
                }

                if (hasFlash) {
                  alert('SI tiene');
                } else {
                  alert('NO tiene');

                }
              </script>";

            }

            */
        } catch (Exception $e) {
            //print_r('<div style="display: none">'.$e.'</div>');
            print('<div style="width: 100%;height: 97%;background: black;background: url(&quot;' . $bgCasino . '&quot;) 50% 0px no-repeat;font: 15px/20px Quicksand,Arial,Helvetica,sans-serif;"><div style="
    color: white;
    text-align: center;
    padding-top: 30%;
    /* display: inline-block; */
    font-size: 35px;
    text-transform: uppercase;
"> Juego No disponible</div>
</div>');
        }
    }

}

/**
 * Depura un texto eliminando caracteres no deseados.
 *
 * Esta función elimina una serie de caracteres específicos del texto proporcionado,
 * como comillas, corchetes, llaves, caracteres especiales, entre otros.
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
