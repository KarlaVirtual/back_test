<?php

/**
 * Clase principal para la integración con el sistema de apuestas deportivas.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-21
 */

namespace Backend\integrations\sportsbook;

use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\BonoLog;
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\FlujoCaja;
use Backend\dto\ItTicketDet;
use Backend\dto\ItTicketEnc;
use Backend\dto\ItTicketEncInfo1;
use Backend\dto\Mandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Producto;
use Backend\dto\ProductoMandante;
use Backend\dto\ProductoDetalle;
use Backend\dto\Proveedor;
use Backend\dto\PuntoVenta;
use Backend\dto\Registro;
use Backend\dto\ItTransaccion;
use Backend\dto\JackpotInterno;
use Backend\dto\TranssportsbookApi;
use Backend\dto\TranssportsbookApiMandante;
use Backend\dto\TranssportsbookLog;
use Backend\dto\TranssportsbookDetalle;
use Backend\dto\Usuario;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioSession;
use Backend\dto\UsuarioToken;
use Backend\dto\Pais;
use Backend\dto\UsuarioJackpot;
use Backend\dto\UsuariojackpotGanador;
use Backend\dto\UsuarioTorneo;
use Backend\integrations\virtual\GOLDENRACESERVICES;
use Backend\integrations\virtual\MOBADOOSERVICES;

//use Backend\mysql\TranssportsbookApiMandanteMySqlDAO;
use Backend\mysql\BonoLogMySqlDAO;
use Backend\mysql\FlujoCajaMySqlDAO;
use Backend\mysql\ItTicketDetMySqlDAO;
use Backend\mysql\ItTicketEncInfo1MySqlDAO;
use Backend\mysql\ItTicketEncMySqlDAO;
use Backend\mysql\ItTransaccionMySqlDAO;
use Backend\mysql\TranssportsbookApiMySqlDAO;
use Backend\mysql\TranssportsbookDetalleMySqlDAO;
use Backend\mysql\TranssportsbookLogMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\mysql\UsuarioTorneoMySqlDAO;
use Backend\sql\Transaction;
use Backend\websocket\WebsocketUsuario;
use Exception;
use SimpleXMLElement;

/**
 * Clase principal para la integración con el sistema de apuestas deportivas.
 *
 * Proporciona métodos para gestionar transacciones, bonos y usuarios relacionados
 * con el sistema de apuestas deportivas.
 */
class Game
{

    /**
     * Representación de 'gameid'
     *
     * @var string
     */
    private $gameid;

    /**
     * Representación de 'mode'
     *
     * @var string
     */
    private $mode;

    /**
     * Representación de 'provider'
     *
     * @var string
     */
    private $provider;

    /**
     * Representación de 'lan'
     *
     * @var string
     */
    private $lan;

    /**
     * Representación de 'partnerid'
     *
     * @var string
     */
    private $partnerid;

    /**
     * Representación de 'usuarioToken'
     *
     * @var string
     */
    private $usuarioToken;

    /**
     * Representación de 'isMobile'
     *
     * @var string
     */
    private $isMobile;

    /**
     * URL para agregar una cuenta en el sistema Quisk.
     *
     * @var string
     */
    private $QuiskAddAcountURL = 'https://mpayment-uat.aiswebnet.com/AISConnect/Betting/AddAccount.ashx';

    /**
     * URL para actualizar una cuenta en el sistema Quisk.
     *
     * @var string
     */
    private $QuiskUpdateAcountURL = 'https://aisConnect-qa.aiswebnet.com/betApp/AddAccount';

    /**
     * URL para autorizar una apuesta en el sistema Quisk.
     *
     * @var string
     */
    private $QuiskAuthoriseBetURL = 'https://mpayment-uat.aiswebnet.com/AISConnect/Betting/AuthoriseBet.ashx';

    /**
     * URL para anular una apuesta en el sistema Quisk.
     *
     * @var string
     */
    private $QuiskVoidBetURL = 'https://mpayment-uat.aiswebnet.com/AISConnect/Betting/VoidBet.ashx';

    /**
     * URL para obtener resultados de apuestas en el sistema Quisk.
     *
     * @var string
     */
    private $QuiskGetResultsURL = 'https://mpayment-uat.aiswebnet.com/AISConnect/Betting/GetResults.ashx';

    /**
     * ID del comerciante en el sistema Quisk.
     *
     * @var string
     */
    private $QuiskMerchantId = '1143103530';

    /**
     * ID del usuario proveedor en el sistema Quisk.
     *
     * @var string
     */
    private $QuiskProviderUserId = 'Bet001';

    /**
     * Contraseña del usuario proveedor en el sistema Quisk.
     *
     * @var string
     */
    private $QuiskProviderPassword = '1Qaz2Wsx';

    /**
     * Clave API para autenticación en el sistema Quisk.
     *
     * @var string
     */
    private $QuiskAPIKey = '5486509221356732';

    /**
     * Clave secreta para autenticación en el sistema Quisk.
     *
     * @var string
     */
    private $QuiskSecretKey = 'npwedhrjskt32oerka9';

    /**
     * Clave API para transacciones VT en el sistema Quisk.
     *
     * @var string
     */
    private $QuiskVTAPIKey = 'kUy3cZ247gwyHNowUksnOCtGsLKmWKCRiiYXcRuhL7PFUB+o';

    /**
     * URL para transacciones VT en el sistema Quisk.
     *
     * @var string
     */
    private $QuiskVTURL = 'https://uatncp.quisk.co/vt/quiskGamingBet#';


    /**
     * Constructor de la clase Game.
     *
     * Inicializa las propiedades de la clase con los valores proporcionados,
     * aplicando una depuración de caracteres a cada uno de ellos.
     *
     * @param string $gameid       Identificador del juego.
     * @param string $mode         Modo de operación.
     * @param string $provider     Proveedor del juego.
     * @param string $lan          Idioma.
     * @param string $partnerid    Identificador del socio.
     * @param string $usuarioToken Token del usuario.
     * @param string $isMobile     Indica si el acceso es desde un dispositivo móvil ("true" o "false").
     *
     * @return void
     */
    public function __construct($gameid = "", $mode = "", $provider = "", $lan = "", $partnerid = "", $usuarioToken = "", $isMobile = "")
    {
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
     * Obtiene la URL del juego basado en el proveedor y otros parámetros.
     *
     * Este método utiliza diferentes servicios según el proveedor del juego
     * para obtener la URL correspondiente. También realiza ajustes en el idioma
     * y el modo de operación antes de realizar la solicitud.
     *
     * @return string|false La URL del juego si se obtiene correctamente, o false en caso de error.
     */
    public function getURL()
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
            $ProductoMandante = new ProductoMandante("", "", $this->gameid);
            $Producto = new Producto($ProductoMandante->productoId);

            $Proveedor = new Proveedor($Producto->getProveedorId());


            switch ($Proveedor->getAbreviado()) {
                case "IGP":
                    $IGPSERVICES = new IGPSERVICES();
                    $response = $IGPSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken);


                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;
                case "EZZG":
                    $EZUGISERVICES = new EZUGISERVICES();
                    $ProductoDetalle = new ProductoDetalle("", $Producto->getProductoId(), "TABLE");

                    $response = $EZUGISERVICES->getGame($ProductoDetalle->getPValue(), $this->lan, $isFun, $this->usuarioToken);

                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }


                    break;

                case "PTG":
                    $PATAGONIASERVICES = new PATAGONIASERVICES();
                    //$ProductoDetalle = new ProductoDetalle("", $Producto->getProductoId(), "TABLE");

                    $response = $PATAGONIASERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken);

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

                    $response = $INBETSERVICES->getGame($ProductoDetalle->getPValue(), $this->lan, $isFun, $this->usuarioToken);

                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                case "WMT":
                    $WORLDMATCHSERVICES = new WORLDMATCHSERVICES();

                    $response = $WORLDMATCHSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken);

                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "GDR":
                    $GOLDENRACESERVICES = new GOLDENRACESERVICES();


                    $response = $GOLDENRACESERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken);

                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "VGT":
                    $VIRTUALGENERATIONSERVICES = new VIRTUALGENERATIONSERVICES();
                    $response = $VIRTUALGENERATIONSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $this->isMobile);
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

                    $response = $MICROGAMINGSERVICES->getGame($ProductoDetalle->getPValue(), $this->lan, $isFun, $this->usuarioToken);
                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "JOIN":
                    $JOINSERVICES = new JOINSERVICES();

                    $response = $JOINSERVICES->getGamePage($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken);

                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "JOINPOKER":
                    $JOINSERVICES = new JOINSERVICES();

                    $response = $JOINSERVICES->getGame2($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken);

                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "ENPH":
                    $ENDORPHINASERVICES = new ENDORPHINASERVICES();

                    $response = $ENDORPHINASERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId());

                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;


                case "BTX":

                    $BETIXONSERVICES = new BETIXONSERVICES();

                    $response = $BETIXONSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId());

                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;


                case "ORYX":

                    $ORYXSERVICES = new ORYXSERVICES();

                    $response = $ORYXSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId());

                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;


                case "QTECH":

                    $QTECHSERVICES = new QTECHSERVICES();

                    $response = $QTECHSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken);

                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "PLAYNGO":

                    $PLAYNGOSERVICES = new PLAYNGOSERVICES();

                    $response = $PLAYNGOSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId());

                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;

                case "MOBADOO":

                    $MOBADOOSERVICES = new MOBADOOSERVICES();

                    $response = $MOBADOOSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId());

                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;


                case "BOSS":

                    $BOSSSERVICES = new BOSSSERVICES();

                    $response = $BOSSSERVICES->getGame($Producto->getExternoId(), $this->lan, $isFun, $this->usuarioToken, $Producto->getProductoId());

                    if ( ! $response->error) {
                        return $response->response;
                    } else {
                        return false;
                    }

                    break;
            }
        } catch (Exception $e) {
        }
    }


    /**
     * Autentica a un usuario mandante en el sistema.
     *
     * Este método realiza varias validaciones y configuraciones relacionadas con el usuario mandante,
     * incluyendo la verificación de su estado, perfil, y balance. También maneja diferentes casos
     * según el tipo de mandante y su configuración.
     *
     * @param UsuarioMandante $UsuarioMandante Objeto que contiene información del usuario mandante.
     *
     * @return object Respuesta con información del usuario autenticado, incluyendo su saldo y otros datos.
     * @throws Exception Si ocurre algún error durante la autenticación o las validaciones.
     */
    public function autenticate($UsuarioMandante = "")
    {
        try {
            $timeG = time();


            $ConfigurationEnvironment = new ConfigurationEnvironment();

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $diff = time() - $timeG;
            //syslog(10, 'ITN-DIFF-TIME 1 ' . 'AUTH' . $UsuarioMandante->getUsuarioMandante() . " " . $diff);


            // CHECK SESSION //
            if ($ConfigurationEnvironment->isDevelopment() || $UsuarioMandante->mandante == 18) {
                try {
                    $UsuarioToken2 = new UsuarioToken('', '0', $UsuarioMandante->usumandanteId);
                } catch (Exception $ex) {
                    if ($ex->getCode() == 21) throw $ex;
                }
            }

            try {
                $Clasificador = new Clasificador('', 'PHONEVERIFICATION');
                $tipo = $Clasificador->getClasificadorId();
                $valorVerif = 1;
                $MandanteDetalle = new MandanteDetalle('', $Usuario->mandante, $tipo, $Usuario->paisId, '', $valorVerif);
                if ($MandanteDetalle->estado == 'A') {
                    if ($Usuario->verifCelular == 'N') {
                        throw new Exception('Celular no verificado.', 100095);
                    }
                }
            } catch (Exception $e) {
                if ($e->getCode() == 100095) throw $e;
            }


            $diff = time() - $timeG;
            //syslog(10, 'ITN-DIFF-TIME 2 ' . 'AUTH' . $UsuarioMandante->getUsuarioMandante() . " " . $diff);

            $Mandante = new Mandante($UsuarioMandante->mandante);

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
                $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                $Pais = new Pais($Usuario->paisId);


                if ($Usuario->estado != "A") {
                    throw new Exception("Usuario Inactivo", "20003");
                }
                if ($Usuario->billeteraId == 1) {
                    throw new Exception("Usuario Inactivo", "20003");
                }

                switch ($UsuarioPerfil->getPerfilId()) {
                    case "USUONLINE":
                        $diff = time() - $timeG;
                        //syslog(10, 'ITN-DIFF-TIME 2-1 ' . 'AUTH' . $UsuarioMandante->getUsuarioMandante() . " " . $diff);

                        try {
                            $Clasificador = new Clasificador("", "SPORTUSUONLINE");
                            $MandanteDetalle = new MandanteDetalle("", $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

                            if ($MandanteDetalle->valor == 'A') {
                                throw new Exception("imposible realizar una apuesta en este momento", "20024");
                            }
                        } catch (Exception $e) {
                            if ($e->getCode() != 34 && $e->getCode() != 41) {
                                throw $e;
                            }
                        }
                        $Balance = $Usuario->getBalance();

                        if ($Usuario->mandante == '2') {
                            $Balance = 100000 * 100;
                        }
                        if (true) {
                            $diff = time() - $timeG;
                            //syslog(10, 'ITN-DIFF-TIME 2-2 ' . 'AUTH' . $UsuarioMandante->getUsuarioMandante() . " " . $diff);

                            $bonoSql = "select 
                    SUM(b.valor) valor,a.bono_id,
                    SUM(a.apostado) apostado from  usuario_bono a 
                        INNER JOIN bono_interno bn ON (bn.bono_id =a.bono_id ) 
                        INNER JOIN bono_detalle b ON (b.bono_id=bn.bono_id AND b.tipo='MINAMOUNT') 
                        INNER JOIN usuario ON (usuario.usuario_id = a.usuario_id) 
                                             where a.usuario_id='" . $Usuario->usuarioId . "' 
                                             AND bn.tipo=6 AND a.estado='A' AND b.moneda=usuario.moneda";

                            $BonoInterno = new \Backend\dto\BonoInterno();
                            $apmin2_RS = $BonoInterno->execQuery("", $bonoSql);

                            $diff = time() - $timeG;
                            //syslog(10, 'ITN-DIFF-TIME 2-3 ' . 'AUTH' . $UsuarioMandante->getUsuarioMandante() . " " . $diff);


                            $bono = 0;
                            $apostado = 0;
                            foreach ($apmin2_RS as $key => $value) {
                                $bono = floatval(
                                    $value->{'.valor'}
                                );

                                $apostado = floatval(
                                    $value->{'.apostado'}
                                );
                            }
                            if ($Balance < $bono) {
                                $Balance = $bono;
                            }

                            if ($Balance < $apostado) {
                                $Balance = $apostado;
                            }
                        }
                        break;

                    case "MAQUINAANONIMA":
                        $diff = time() - $timeG;
                        //syslog(10, 'ITN-DIFF-TIME 2-1 ' . 'AUTH' . $UsuarioMandante->getUsuarioMandante() . " " . $diff);


                        /*$PuntoVenta = new PuntoVenta("",$UsuarioMandante->usuarioMandante);

                    $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                    $SaldoJuego = $PuntoVenta->getCreditosBase();

                    $Balance = $SaldoJuego;*/

                        $Balance = $Usuario->getBalance();


                        break;

                    case "PUNTOVENTA":
                        $diff = time() - $timeG;
                        //syslog(10, 'ITN-DIFF-TIME 2-1 ' . 'AUTH' . $UsuarioMandante->getUsuarioMandante() . " " . $diff);

                        try {
                            $Clasificador = new Clasificador("", "SPORTBETSHOP");
                            $MandanteDetalle = new MandanteDetalle("", $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

                            if ($MandanteDetalle->valor == 'A') {
                                throw new Exception("imposible realizar una apuesta en este momento", "20024");
                            }
                        } catch (Exception $e) {
                            if ($e->getCode() != 34 && $e->getCode() != 41) {
                                throw $e;
                            }
                        }
                        $diff = time() - $timeG;
                        //syslog(10, 'ITN-DIFF-TIME 2-2 ' . 'AUTH' . $UsuarioMandante->getUsuarioMandante() . " " . $diff);

                        $PuntoVenta = new PuntoVenta("", $UsuarioMandante->usuarioMandante);

                        $diff = time() - $timeG;
                        //syslog(10, 'ITN-DIFF-TIME 2-3 ' . 'AUTH' . $UsuarioMandante->getUsuarioMandante() . " " . $diff);

                        $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                        $SaldoJuego = $PuntoVenta->getCreditosBase();

                        $Balance = $SaldoJuego + $SaldoRecargas;


                        break;


                    case "CAJERO":
                        $diff = time() - $timeG;
                        //syslog(10, 'ITN-DIFF-TIME 2-1 ' . 'AUTH' . $UsuarioMandante->getUsuarioMandante() . " " . $diff);

                        try {
                            $Clasificador = new Clasificador("", "CASHIERSPORT");
                            $MandanteDetalle = new MandanteDetalle("", $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

                            if ($MandanteDetalle->valor == 'A') {
                                throw new Exception("imposible realizar una apuesta en este momento", "20024");
                            }
                        } catch (Exception $e) {
                            if ($e->getCode() != 34 && $e->getCode() != 41) {
                                throw $e;
                            }
                        }
                        $diff = time() - $timeG;
                        //syslog(10, 'ITN-DIFF-TIME 2-2 ' . 'AUTH' . $UsuarioMandante->getUsuarioMandante() . " " . $diff);


                        $PuntoVenta = new PuntoVenta("", $Usuario->puntoventaId);

                        $diff = time() - $timeG;
                        //syslog(10, 'ITN-DIFF-TIME 2-3 ' . 'AUTH' . $UsuarioMandante->getUsuarioMandante() . " " . $diff);

                        $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                        $SaldoJuego = $PuntoVenta->getCreditosBase();

                        $Balance = $SaldoJuego + $SaldoRecargas;

                        break;
                }

                $diff = time() - $timeG;
                //syslog(10, 'ITN-DIFF-TIME 3 ' . 'AUTH' . $UsuarioMandante->getUsuarioMandante() . " " . $diff);

                $respuesta->usuarioId = $UsuarioMandante->usuarioMandante;
                $respuesta->moneda = $UsuarioMandante->moneda;
                $respuesta->usuario = "Usuario" . $UsuarioMandante->usuarioMandante;
                $respuesta->paisId = $Pais->paisId;
                $respuesta->paisIso2 = $Pais->iso;
                $respuesta->idioma = 'ES';
                $respuesta->saldo = $Balance;
            } else {
                $Pais = new Pais($UsuarioMandante->paisId);
                $ProdMandanteTipo = new ProdMandanteTipo("SPORTSBOOK", $Mandante->mandante);
                $data = array(
                    //"site" => $ProdMandanteTipo->siteId,
                    "sign" => $ProdMandanteTipo->siteKey,
                    "token" => $UsuarioMandante->tokenExterno
                );

                $result = $Mandante->sendRequest($ProdMandanteTipo->urlApi . "/authenticate", "POST", $data);
                $result = array(
                    "error" => 'false',
                    "player" => array(
                        "userid" => 1,
                        "balance" => 1,
                        "name" => 1,
                        "country" => '173',
                        "currency" => 'PEN'

                    )
                );
                $result = json_decode(json_encode($result));

                if ($result == "") {
                    throw new Exception("No coinciden ", "50001");
                }


                $error = $result->error;
                $code = $result->code;

                if ($error == "" || $error == '1') {
                    throw new Exception("Error en mandante ", "M" . $code);
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


                $respuesta->usuarioId = $UsuarioMandante->usuarioMandante;
                $respuesta->moneda = $UsuarioMandante->moneda;
                $respuesta->usuario = "Usuario" . $UsuarioMandante->usuarioMandante;
                $respuesta->paisId = $Pais->paisId;
                $respuesta->paisIso2 = $Pais->iso;
                $respuesta->idioma = 'ES';
                $respuesta->saldo = $balance;
            }

            $diff = time() - $timeG;
            //syslog(10, 'ITN-DIFF-TIME 4 ' . 'AUTH' . $UsuarioMandante->getUsuarioMandante() . " " . $diff);


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
     * Este método realiza validaciones y configuraciones relacionadas con el usuario mandante,
     * incluyendo la verificación de su estado, perfil y balance. También maneja diferentes casos
     * según el tipo de mandante y su configuración.
     *
     * @param UsuarioMandante $UsuarioMandante  Objeto que contiene información del usuario mandante.
     * @param boolean         $checkInactividad Indica si se debe verificar la inactividad del usuario.
     *
     * @return object Respuesta con información del balance del usuario, incluyendo su saldo y otros datos.
     * @throws Exception Si ocurre algún error durante la obtención del balance o las validaciones.
     */
    public function getBalance($UsuarioMandante = "", $checkInactividad = true)
    {
        try {
            $ConfigurationEnvironment = new ConfigurationEnvironment();


            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);


            // CHECK SESSION //
            if ($ConfigurationEnvironment->isDevelopment() || $UsuarioMandante->mandante == 18) {
                try {
                    $UsuarioToken2 = new UsuarioToken('', '0', $UsuarioMandante->usumandanteId);
                } catch (Exception $ex) {
                    if ($ex->getCode() == 21) throw $ex;
                }
            }

            $Mandante = new Mandante($UsuarioMandante->mandante);

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
                        try {
                            $Clasificador = new Clasificador("", "SPORTUSUONLINE");
                            $MandanteDetalle = new MandanteDetalle("", $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

                            if ($MandanteDetalle->valor == 'A') {
                                throw new Exception("imposible realizar una apuesta en este momento", "20024");
                            }
                        } catch (Exception $e) {
                            if ($e->getCode() != 34 && $e->getCode() != 41) {
                                throw $e;
                            }
                        }

                        $Balance = $Usuario->getBalance();

                        break;

                    case "MAQUINAANONIMA":


                        /*$PuntoVenta = new PuntoVenta("",$UsuarioMandante->usuarioMandante);

                    $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                    $SaldoJuego = $PuntoVenta->getCreditosBase();

                    $Balance = $SaldoJuego;*/

                        $Balance = $Usuario->getBalance();


                        break;

                    case "PUNTOVENTA":

                        try {
                            $Clasificador = new Clasificador("", "SPORTBETSHOP");
                            $MandanteDetalle = new MandanteDetalle("", $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

                            if ($MandanteDetalle->valor == 'A') {
                                throw new Exception("imposible realizar una apuesta en este momento", "20024");
                            }
                        } catch (Exception $e) {
                            if ($e->getCode() != 34 && $e->getCode() != 41) {
                                throw $e;
                            }
                        }

                        $PuntoVenta = new PuntoVenta("", $UsuarioMandante->usuarioMandante);

                        $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                        $SaldoJuego = $PuntoVenta->getCreditosBase();

                        $Balance = $SaldoJuego + $SaldoRecargas;


                        break;

                    case "CAJERO":

                        try {
                            $Clasificador = new Clasificador("", "CASHIERSPORT");
                            $MandanteDetalle = new MandanteDetalle("", $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

                            if ($MandanteDetalle->valor == 'A') {
                                throw new Exception("imposible realizar una apuesta en este momento", "20024");
                            }
                        } catch (Exception $e) {
                            if ($e->getCode() != 34 && $e->getCode() != 41) {
                                throw $e;
                            }
                        }

                        $UsuarioCajero = new Usuario($UsuarioMandante->usuarioMandante);
                        $PuntoVenta = new PuntoVenta("", $UsuarioCajero->puntoventaId);

                        $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                        $SaldoJuego = $PuntoVenta->getCreditosBase();

                        $Balance = $SaldoJuego + $SaldoRecargas;


                        break;
                }

                $respuesta->usuarioId = $UsuarioMandante->usuarioMandante;
                $respuesta->moneda = $UsuarioMandante->moneda;
                $respuesta->usuario = "Usuario" . $UsuarioMandante->usuarioMandante;
                $respuesta->saldo = $Balance;
            } else {
                $Pais = new Pais($UsuarioMandante->paisId);
                $ProdMandanteTipo = new ProdMandanteTipo("SPORTSBOOK", $Mandante->mandante);
                $data = array(
                    //"site" => $ProdMandanteTipo->siteId,
                    "sign" => $ProdMandanteTipo->siteKey,
                    "token" => $UsuarioMandante->tokenExterno
                );

                $result = $Mandante->sendRequest($ProdMandanteTipo->urlApi . "/balance", "POST", $data);
                $result = array(
                    "error" => 'false',
                    "player" => array(
                        "userid" => 1,
                        "balance" => 2,
                        "country" => '173',
                        "currency" => 'PEN'

                    )
                );
                $result = json_decode(json_encode($result));

                if ($result == "") {
                    throw new Exception("La solicitud al mandante fue vacia ", "50002");
                }


                $error = $result->error;
                $code = $result->code;

                if ($error == "" || $error == '1') {
                    throw new Exception("Error en mandante ", "M" . $code);
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


                $respuesta->usuarioId = $UsuarioMandante->usuarioMandante;
                $respuesta->moneda = $UsuarioMandante->moneda;
                $respuesta->usuario = "Usuario" . $UsuarioMandante->usuarioMandante;
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
     * Realiza un débito en la cuenta del usuario para una transacción de apuestas deportivas.
     *
     * @param UsuarioMandante $UsuarioMandante    Objeto que representa al usuario mandante.
     * @param mixed           $Producto           Producto asociado a la transacción.
     * @param mixed           $transsportsbookApi Objeto que contiene la información de la transacción API.
     * @param mixed           $ticket             Información del ticket de la apuesta.
     * @param array           $detalles           Detalles de la apuesta.
     * @param string          $impuesto           Opcional Impuesto aplicado a la apuesta. Por defecto es '0'.
     *
     * @return object Respuesta con información de la transacción, saldo y otros datos relevantes.
     *
     * @throws Exception Si la cuenta del usuario no está verificada.
     * @throws Exception Si el usuario está en contingencia.
     * @throws Exception Si el sitio está en mantenimiento.
     * @throws Exception Si el usuario está autoexcluido.
     * @throws Exception Si el monto a debitar es negativo.
     * @throws Exception Si la transacción ya fue procesada.
     * @throws Exception Si el ticket ya existe.
     * @throws Exception Si el usuario no tiene fondos suficientes.
     * @throws Exception Si el valor apostado es menor al mínimo configurado.
     * @throws Exception Si el usuario está inactivo.
     * @throws Exception Si el punto de venta está bloqueado para ventas.
     * @throws Exception Si se alcanza el límite de saldo de juego.
     */
    public function debit(UsuarioMandante $UsuarioMandante, $Producto, $transsportsbookApi, $ticket, $detalles, $impuesto = '0')
    {
        try {
            $ConfigurationEnvironment = new ConfigurationEnvironment();

            $timeG = time();


            $detallesSportsbookRuleta = $detalles;

            $transsportsbookApi->setUsuarioId($UsuarioMandante->getUsumandanteId());
            //$Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            // CONTIGENCES //

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);


            if ($UsuarioPerfil->perfilId == "USUONLINE") {
                try {
                    $Clasificador = new Clasificador("", "ACCVERIFFORSPORTSBOOK");

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

            $diff = time() - $timeG;
            //syslog(10, 'ITN-DIFF-TIME 1 ' . 'DEBIT' . $transsportsbookApi->getIdentificador() . " " . $diff);


            if ($Usuario->contingenciaDeportes == "A") {
                throw new Exception("Usuario Contingencia", "20024");
            }
            try {
                $Clasificador = new Clasificador('', 'TOTALCONTINGENCE');
                $MandanteDetallePartner = new MandanteDetalle('', $UsuarioMandante->mandante, $Clasificador->getClasificadorId(), $UsuarioMandante->paisId, 'A');

                if ($MandanteDetallePartner->getValor() == 1) {
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

            try {
                $Clasificador = new Clasificador('', 'TOTALCONTINGENCE');
                $MandanteDetalleTotal = new MandanteDetalle('', '-1', $Clasificador->getClasificadorId(), '0', 'A');

                if ($MandanteDetalleTotal->getValor() == 1) {
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


            $diff = time() - $timeG;
            //syslog(10, 'ITN-DIFF-TIME 2 ' . 'DEBIT' . $transsportsbookApi->getIdentificador() . " " . $diff);

            // CHECK SESSION //
            if ($ConfigurationEnvironment->isDevelopment() || $UsuarioMandante->mandante == 18) {
                try {
                    $UsuarioToken2 = new UsuarioToken('', '0', $UsuarioMandante->usumandanteId);
                } catch (Exception $ex) {
                    if ($ex->getCode() == 21) throw $ex;
                }
            }

            try {
                $Clasificador = new Clasificador('', 'TOTALCONTINGENCESPORT');
                $MandanteDetalle = new MandanteDetalle('', '-1', $Clasificador->getClasificadorId(), '0', 'A');
                if ($MandanteDetalle->getValor() == 1) throw new Exception('We are currently in the process of maintaining the site.', 30004);
            } catch (Exception $ex) {
                if ($ex->getCode() == 30004) throw $ex;
            }

            // EXCLUTIONS //

            try {
                $Clasificador = new Clasificador('', 'EXCTIMEOUT');

                $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioMandante->usuarioMandante, 'A', $Clasificador->getClasificadorId());

                if ($UsuarioConfiguracion->getValor() > date('Y-m-d H:i:s')) throw new Exception('Partial self-excluded user by time', 100085);
            } catch (Exception $ex) {
                if ($ex->getCode() == 100085) throw $ex;
            }


            if ($ConfigurationEnvironment->isDevelopment() || in_array($UsuarioMandante->mandante, array(0, 18, 19))) {
                $result = '0';
                $UsuarioConfiguracion = new UsuarioConfiguracion();

                $UsuarioConfiguracion->setUsuarioId($UsuarioMandante->usuarioMandante);
                $result = $UsuarioConfiguracion->verifyLimitesDeportivas($transsportsbookApi->getValor(), $UsuarioMandante);


                if ($result != '0') {
                    throw new Exception("Limite de Autoexclusion", $result);
                }
            }


            if ($ConfigurationEnvironment->isDevelopment() || in_array($UsuarioMandante->mandante, array(0, 18, 19))) {
                $Clasificador = new Clasificador("", "EXCPRODUCT");

                try {
                    $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioMandante->getUsuarioMandante(), "A", $Clasificador->getClasificadorId(), '0');

                    if ($UsuarioConfiguracion->getProductoId() != "") {
                        throw new Exception("EXCPRODUCT", "20004");
                    }
                } catch (Exception $e) {
                    if ($e->getCode() != 46) {
                        throw $e;
                    }
                }
            }

            $debitAmount = $transsportsbookApi->getValor();

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

            $respuesta = json_decode(json_encode(array(
                "usuarioId" => "",
                "moneda" => "",
                "usuario" => "",
                "transaccionId" => "",
                "saldo" => "",
                "transsportsbookApi" => ""
            )));

            $transactionId = $transsportsbookApi->getTransaccionId();

            /*  Verificamos que el monto a debitar sea positivo */
            if ($debitAmount < 0) {
                throw new Exception("No puede ser negativo el monto a debitar.", "10002");
            }

            /*  Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego  */
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());


            /*  Agregamos Elementos a la Transaccion API  */
            $transsportsbookApi->setProductoId($ProductoMandante->prodmandanteId);


            /*  Verificamos que la transaccionId no se haya procesado antes  */
            if ($transsportsbookApi->existsTransaccionIdAndProveedor("OK")) {
                /*  Si la transaccionId ha sido procesada, reportamos el error  */
                throw new Exception("Transaccion ya procesada", "10001");

                $transactionId = "ND" . $transactionId;
                $transsportsbookApi->setTransaccionId($transactionId);
            }


            $diff = time() - $timeG;
            //syslog(10, 'ITN-DIFF-TIME 3 ' . 'DEBIT' . $transsportsbookApi->getIdentificador() . " " . $diff);

            $clave_ticket = GenerarClaveTicket2(6);

            $TipoBeneficiario = '';
            $BeneficiarioId = 0;
            $Registro = new Registro('', $Usuario->usuarioId);


            if ($UsuarioPerfil->perfilId == "USUONLINE") {
                try {
                    if ($BeneficiarioId == '0') {
                        $puntoVentaIPSql = "select b.usuario_id usuario_puntoventa, c.usuario_id usuario_afiliador, concesionario.usuhijo_id
from usuario a
         INNER JOIN registro r ON (r.usuario_id = a.usuario_id)
         left outer join usuario b on (b.usuario_ip = '" . $ticket->DirIp . "' and b.mandante = a.mandante and b.usuario_ip != '0' and
                                       b.usuario_ip != '' and not b.usuario_ip is null)
         LEFT OUTER JOIN clasificador ON (clasificador.abreviado = 'SPORTPVIP')
         LEFT OUTER JOIN concesionario ON (concesionario.prodinterno_id = clasificador.clasificador_id AND
                                           usuhijo_id = b.usuario_id AND concesionario.estado = 'A')
         left outer join usuario_perfil c on (r.afiliador_id = c.usuario_id)
where a.usuario_id = '" . $Usuario->usuarioId . "' ";


                        $BonoInterno = new \Backend\dto\BonoInterno();
                        $puntoVentaIPSqlData = $BonoInterno->execQuery("", $puntoVentaIPSql);
                        if (oldCount($puntoVentaIPSqlData) > 0) {
                            $puntoVentaIP = $puntoVentaIPSqlData[0];
                            if ($puntoVentaIP->{'b.usuario_puntoventa'} != ''
                                && $puntoVentaIP->{'b.usuario_puntoventa'} != '0') {
                                $TipoBeneficiario = '1';
                                $BeneficiarioId = $puntoVentaIP->{'b.usuario_puntoventa'};
                            }
                        }
                    }
                    if ($BeneficiarioId == '0') {
                        if ($Registro->afiliadorId != 0 && $Registro->afiliadorId != '' && $Registro->afiliadorId != null) {
                            $UsuarioPerfil2 = new UsuarioPerfil($Registro->afiliadorId);

                            if ($UsuarioPerfil2->perfilId == 'PUNTOVENTA') {
                                $TipoBeneficiario = '2';
                                $BeneficiarioId = $Registro->afiliadorId;
                            } else {
                                $TipoBeneficiario = '2';
                                $BeneficiarioId = $Registro->afiliadorId;
                            }
                        }
                    }
                } catch (Exception $e) {
                }
            }


            $diff = time() - $timeG;
            //syslog(10, 'ITN-DIFF-TIME 4 ' . 'DEBIT' . $transsportsbookApi->getIdentificador() . " " . $diff);

            $mandante = $UsuarioMandante->mandante;
            $billetera_id = $Usuario->billeteraId;

            if ($mandante == 2 && $billetera_id != '0') {
                throw new Exception("No tiene fondos suficientes", "20001");
            }


            /*  Creamos la Transaccion por el Juego  */
            $ItTicketEnc = new ItTicketEnc();
            //$ItTicketEnc->setProductoId($ProductoMandante->prodmandanteId);
            $ItTicketEnc->setTransaccionId($transactionId);
            $ItTicketEnc->setTicketId($transsportsbookApi->getIdentificador());
            $ItTicketEnc->setVlrApuesta($debitAmount);
            $ItTicketEnc->setImpuestoApuesta($totalTax);
            $ItTicketEnc->setVlrPremio($ticket->PremioProy);
            $ItTicketEnc->setUsuarioId($UsuarioMandante->usuarioMandante);

            $ItTicketEnc->setGameReference($ticket->GameReference);
            $ItTicketEnc->setBetStatus($ticket->BetStatus);
            $ItTicketEnc->setCantLineas($ticket->CantLineas);
            $ItTicketEnc->setFechaCrea(date('Y-m-d', time()));
            $ItTicketEnc->setHoraCrea(date('H:i:s', time()));
            $ItTicketEnc->setClave($clave_ticket);


            $ItTicketEnc->setMandante($UsuarioMandante->mandante);
            $ItTicketEnc->setDirIp($ticket->DirIp);
            $ItTicketEnc->setFreebet('0');
            $ItTicketEnc->setBetMode($ticket->BetMode);
            $ItTicketEnc->setEstado("A");
            $ItTicketEnc->setPremiado('N');
            $ItTicketEnc->setPremioPagado('N');
            $ItTicketEnc->setEliminado('N');
            $ItTicketEnc->setFechaModifica('');
            $ItTicketEnc->setCantLineas($ticket->CantLineas);
            $ItTicketEnc->setUsumodifId('0');
            $ItTicketEnc->setImpuesto($impuesto);
            $ItTicketEnc->setValorPremioPrevio('0'); //valor_premio_previo
            $ItTicketEnc->setTransaccionWallet(''); //transaccion_wallet
            $ItTicketEnc->setWallet('0'); //wallet
            $ItTicketEnc->setBeneficiarioId($BeneficiarioId);
            $ItTicketEnc->setTipoBeneficiario($TipoBeneficiario);


            if ($UsuarioPerfil->perfilId == "USUONLINE") {
                $creditos_base = $Registro->getCreditosBase();
                $saldoRetiros = $Registro->getCreditos();
                //Calcula el valor de creditos base que tiene que restar de acuerdo al valor de la apuesta
                if ($debitAmountTax > $creditos_base) {
                    $valor_base = $creditos_base;
                    $valor_adicional = $debitAmountTax - $creditos_base;
                } else {
                    $valor_base = $debitAmountTax;
                    $valor_adicional = 0;
                }
                if ($Usuario->mandante == 2 && $Usuario->billeteraId != '0' && $UsuarioPerfil->perfilId == "USUONLINE") {
                    $valor_adicional = '0';
                    $valor_base = '0';
                }
            } else {
                $PuntoVenta = new PuntoVenta("", $Usuario->puntoventaId);

                $UsuarioPremiomax = new UsuarioPremiomax($UsuarioMandante->usuarioMandante);


                if ($Usuario->bloqueoVentas == "S" && $Usuario->bloqueoVentas != null && $Usuario->bloqueoVentas != '') {
                    throw new Exception("Punto de Venta bloqueado para ventas", "20001");
                }

                if (floatval($debitAmountTax) < floatval($UsuarioPremiomax->apuestaMin) && $UsuarioPremiomax->apuestaMin > 0 && $UsuarioPremiomax->apuestaMin != null && $UsuarioPremiomax->apuestaMin != "") {
                    throw new Exception("Valor apostado menor a la apuesta minima configurada", "20001");
                }

                $creditos_base = $PuntoVenta->getCreditosBase();
                //Calcula el valor de creditos base que tiene que restar de acuerdo al valor de la apuesta
                if ($debitAmountTax > $creditos_base) {
                    throw new Exception("No tiene fondos suficientes", "20001");
                }
                $valor_base = $debitAmountTax;
                $valor_adicional = 0;
            }

            $ItTicketEnc->setSaldoCreditos($valor_adicional);
            $ItTicketEnc->setSaldoCreditosBase($valor_base);

            $ExisteTicket = false;


            /*  Verificamos si existe el ticket_id antes, de ser positivo, tendriamos que combinar las apuestas  */
            if ($ItTicketEnc->existsTicketId()) {
                $ExisteTicket = true;
            }


            if (($UsuarioPerfil->perfilId == "PUNTOVENTA" || $UsuarioPerfil->perfilId == "CAJERO") && $UsuarioMandante->paisId == '173' && $UsuarioMandante->mandante == '0' && ((date('H:i:s') >= '00:00:00' && date('H:i:s') <= '06:59:59'))) {
                throw new Exception('We are currently in the process of maintaining the site.', 30004);
            }

            /*  Obtenemos el mandante para verificar sus caracteristicas  */
            $Mandante = new Mandante($UsuarioMandante->mandante);

            /* Obtenemos la Transaccion de la BD para crear y registrar el Debito con esta transaccion */
            $ItTicketEncMySqlDAO = new ItTicketEncMySqlDAO();
            $Transaction = $ItTicketEncMySqlDAO->getTransaction();


            $diff = time() - $timeG;
            //syslog(10, 'ITN-DIFF-TIME 5 ' . 'DEBIT' . $transsportsbookApi->getIdentificador() . " " . $diff);

            /*  Verificamos si Existe el ticket para combinar las apuestas.  */
            if ($ExisteTicket) {
                throw new Exception("El ticket ya existe", "300004");
            } else {
            }


            $ItTicketDetMySqlDAO = new ItTicketDetMySqlDAO($Transaction);


            foreach ($detalles as $detalle) {
                $ItTicketDet = new ItTicketDet();
                $ItTicketDet->setTicketId($ItTicketEnc->ticketId);//ticket_id
                $ItTicketDet->setApuestaId($detalle->eventoid);//apuesta_id
                $ItTicketDet->setAgrupadorId($detalle->agrupadorid);//agrupador_id

                if (strlen($detalle->evento) > 100) {
                    $detalle->evento = substr($detalle->evento, 0, 99);
                }
                if (strlen($detalle->opcion) > 50) {
                    $detalle->opcion = substr($detalle->opcion, 0, 45);
                }
                if (strlen($detalle->agrupador) > 50) {
                    $detalle->agrupador = substr($detalle->agrupador, 0, 45);
                }


                $ItTicketDet->setAgrupador($detalle->agrupador);//agrupador
                $ItTicketDet->setApuesta($detalle->evento);
                $ItTicketDet->setOpcion($detalle->opcion);//opcion
                $ItTicketDet->setLogro($detalle->logro);//logro
                $ItTicketDet->setFechaEvento($detalle->fecha);//fecha_evento
                $ItTicketDet->setHoraEvento($detalle->hora);//hora_evento
                $ItTicketDet->setSportid($detalle->sportid);//sportid
                $ItTicketDet->setMandante($UsuarioMandante->mandante);//mandante
                $ItTicketDet->setLigaid($detalle->ligaid);//ligaid
                $ItTicketDet->setMatchid($detalle->matchid);//matchid

                $ItTicketDetMySqlDAO->insert($ItTicketDet);
            }

            /*  Obtenemos el tipo de Transaccion dependiendo de el betTypeID  */
            $tipoTransaccion = "BET";


            $diff = time() - $timeG;
            //syslog(10, 'ITN-DIFF-TIME 6 ' . 'DEBIT' . $transsportsbookApi->getIdentificador() . " " . $diff);

            /*  Creamos el log de la transaccion juego para auditoria  */

            if ($taxedValue > 0) {
                $ItTransaccion2 = new ItTransaccion();
                $ItTransaccion2->setTipo('TAXBET');
                $ItTransaccion2->setTicketId($ItTicketEnc->ticketId);
                $ItTransaccion2->setGameReference($ticket->GameReference);
                $ItTransaccion2->setUsuarioId($UsuarioMandante->usuarioMandante);
                $ItTransaccion2->setBetStatus($ticket->BetStatus);
                $ItTransaccion2->setValor($totalTax);
                $ItTransaccion2->setTransaccionId('TXDB' . $transsportsbookApi->getTransaccionId());
                $ItTransaccion2->setMandante($UsuarioMandante->mandante);
            }

            $ItTransaccion = new ItTransaccion();
            $ItTransaccion->setTipo($tipoTransaccion);
            $ItTransaccion->setTicketId($ItTicketEnc->ticketId);
            $ItTransaccion->setGameReference($ticket->GameReference);
            $ItTransaccion->setUsuarioId($UsuarioMandante->usuarioMandante);
            $ItTransaccion->setBetStatus($ticket->BetStatus);
            $ItTransaccion->setValor($debitAmount);
            $ItTransaccion->setTransaccionId($transsportsbookApi->getTransaccionId());
            $ItTransaccion->setMandante($UsuarioMandante->mandante);


            $IdUsuarioRelacionado = '';

            /*  Verificamos que el mandante sea Propio, para proceder con nuestros Usuarios  */
            if ($Mandante->propio == "S") {
                /*  Obtenemos nuestro Usuario y hacemos el debito  */
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);


                if ($Usuario->estado != "A") {
                    throw new Exception("Usuario Inactivo", "20003");
                }

                switch ($UsuarioPerfil->getPerfilId()) {
                    case "USUONLINE":


                        $diff = time() - $timeG;
                        //syslog(10, 'ITN-DIFF-TIME 7 ' . 'DEBIT' . $transsportsbookApi->getIdentificador() . " " . $diff);

                        if ($Usuario->billeteraId == 1) {
                        } else {
                            if ($UsuarioMandante->mandante == '2') {
                                if (floatval($Usuario->getBalance()) > 1000000) {
                                    throw new Exception("You have reached your gaming balance limit. Please withdraw to place bet", 100030);
                                }
                            }

                            $array = array();

                            $detalleCuotaTotal = 1;
                            foreach ($detalles as $detalle) {
                                $detalle->vlr_apuesta = $ItTicketEnc->vlrApuesta;

                                $detalles = array(
                                    "DeporteMercado" => $detalle->sportid . "M" . $detalle->agrupadorid,
                                    "Deporte" => $detalle->sportid,
                                    "Liga" => $detalle->ligaid,
                                    "Evento" => $detalle->apuesta_id,
                                    "Cuota" => $detalle->logro

                                );
                                $detalleValorApuesta = $detalle->vlr_apuesta;


                                array_push($array, $detalles);

                                $detalleCuotaTotal = $detalleCuotaTotal * $detalle->logro;
                            }
                            $detallesFinal = json_decode(json_encode($array));

                            $detalleSelecciones = $detallesFinal;

                            $detalles = array(
                                "TransaccionApi" => $transsportsbookApi,
                                "Selecciones" => $detalleSelecciones,
                                "CuotaTotal" => $detalleCuotaTotal,
                                "BetMode" => $ItTicketEnc->betmode,
                                "ValorApuesta" => $ItTicketEnc->vlrApuesta
                            );
                            $detalles = json_decode(json_encode($detalles));


                            $BonoInterno = new BonoInterno();
                            $responseFree = $BonoInterno->verificarBonoFree($UsuarioMandante, $detalles, "SPORT", $Transaction, $transsportsbookApi);

                            if ($responseFree->WinBonus) {
                                $ItTicketEnc->setVlrApuesta($debitAmount);
                                $ItTransaccion->setValor($debitAmount);
                                $debitAmount = $responseFree->AmountDebit;
                                $debitBonus = $responseFree->AmountBonus;

                                $debitAmountTax = $debitAmount * (1 + $taxedValue / 100);


                                if ($UsuarioPerfil->perfilId == "USUONLINE") {
                                    $creditos_base = $Registro->getCreditosBase();
                                    $saldoRetiros = $Registro->getCreditos();
                                    //Calcula el valor de creditos base que tiene que restar de acuerdo al valor de la apuesta
                                    if ($debitAmountTax > $creditos_base) {
                                        $valor_base = $creditos_base;
                                        $valor_adicional = $debitAmountTax - $creditos_base;
                                    } else {
                                        $valor_base = $debitAmountTax;
                                        $valor_adicional = 0;
                                    }
                                    if ($Usuario->mandante == 2 && $Usuario->billeteraId != '0' && $UsuarioPerfil->perfilId == "USUONLINE") {
                                        $valor_adicional = '0';
                                        $valor_base = '0';
                                    }
                                } else {
                                    $PuntoVenta = new PuntoVenta("", $Usuario->puntoventaId);

                                    $UsuarioPremiomax = new UsuarioPremiomax($UsuarioMandante->usuarioMandante);


                                    if ($Usuario->bloqueoVentas == "S" && $Usuario->bloqueoVentas != null && $Usuario->bloqueoVentas != '') {
                                        throw new Exception("Punto de Venta bloqueado para ventas", "20001");
                                    }

                                    if (floatval($debitAmountTax) < floatval($UsuarioPremiomax->apuestaMin) && $UsuarioPremiomax->apuestaMin > 0 && $UsuarioPremiomax->apuestaMin != null && $UsuarioPremiomax->apuestaMin != "") {
                                        throw new Exception("Valor apostado menor a la apuesta minima configurada", "20001");
                                    }

                                    $creditos_base = $PuntoVenta->getCreditosBase();
                                    //Calcula el valor de creditos base que tiene que restar de acuerdo al valor de la apuesta
                                    if ($debitAmountTax > $creditos_base) {
                                        throw new Exception("No tiene fondos suficientes", "20001");
                                    }
                                    $valor_base = $debitAmountTax;
                                    $valor_adicional = 0;
                                }

                                $ItTicketEnc->setSaldoCreditos($valor_adicional);
                                $ItTicketEnc->setSaldoCreditosBase($valor_base);

                                $saldoFree = $debitBonus;

                                $ItTicketEnc->setFreebet($responseFree->Bono);


                                $BonoLog = new BonoLog();
                                $BonoLog->setUsuarioId($Usuario->usuarioId);
                                $BonoLog->setTipo('F');
                                $BonoLog->setValor($debitBonus);
                                $BonoLog->setFechaCrea(date('Y-m-d H:i:s'));
                                $BonoLog->setFechaCierre(date('Y-m-d H:i:s'));
                                $BonoLog->setEstado('L');
                                $BonoLog->setErrorId(0);
                                $BonoLog->setIdExterno($responseFree->Bono);
                                $BonoLog->setMandante($Usuario->mandante);
                                $BonoLog->setTipobonoId(5);
                                $BonoLog->setTiposaldoId('1');


                                $BonoLogMySqlDAO = new BonoLogMySqlDAO($Transaction);
                                $BonoLogMySqlDAO->insert($BonoLog);
                                /*$UsuarioHistorial = new UsuarioHistorial();
                            $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                            $UsuarioHistorial->setDescripcion('');
                            $UsuarioHistorial->setMovimiento('E');
                            $UsuarioHistorial->setUsucreaId(0);
                            $UsuarioHistorial->setUsumodifId(0);
                            $UsuarioHistorial->setTipo(50);
                            $UsuarioHistorial->setValor($ItTransaccion->getValor());
                            $UsuarioHistorial->setExternoId($ItTicketEnc->ticketId);

                            $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                            $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);*/
                            }

                            if ($debitAmountTax > 0) {
                                $diff = time() - $timeG;
                                //syslog(10, 'ITN-DIFF-TIME 7-1 ' . 'DEBIT' . $transsportsbookApi->getIdentificador() . " " . $diff);


                                $Usuario->debit($debitAmountTax, $Transaction);

                                $UsuarioHistorial = new UsuarioHistorial();
                                $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                                $UsuarioHistorial->setDescripcion('');
                                $UsuarioHistorial->setMovimiento('S');
                                $UsuarioHistorial->setUsucreaId(0);
                                $UsuarioHistorial->setUsumodifId(0);
                                $UsuarioHistorial->setTipo(20);
                                $UsuarioHistorial->setValor($debitAmountTax);
                                $UsuarioHistorial->setExternoId($ItTicketEnc->ticketId);

                                $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                                $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

                                $diff = time() - $timeG;
                                //syslog(10, 'ITN-DIFF-TIME 7-2 ' . 'DEBIT' . $transsportsbookApi->getIdentificador() . " " . $diff);


                            }
                        }

                        break;

                    case "MAQUINAANONIMA":


                        $diff = time() - $timeG;
                        //syslog(10, 'ITN-DIFF-TIME 7 ' . 'DEBIT' . $transsportsbookApi->getIdentificador() . " " . $diff);


                        /*$PuntoVenta = new PuntoVenta("",$UsuarioMandante->usuarioMandante);

                    $PuntoVenta->setBalanceCreditosBase(-$debitAmount,$Transaction);*/


                        $Usuario->debit($debitAmount, $Transaction);

                        $diff = time() - $timeG;
                        //syslog(10, 'ITN-DIFF-TIME 7-1 ' . 'DEBIT' . $transsportsbookApi->getIdentificador() . " " . $diff);


                        $UsuarioHistorial = new UsuarioHistorial();
                        $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                        $UsuarioHistorial->setDescripcion('');
                        $UsuarioHistorial->setMovimiento('S');
                        $UsuarioHistorial->setUsucreaId(0);
                        $UsuarioHistorial->setUsumodifId(0);
                        $UsuarioHistorial->setTipo(20);
                        $UsuarioHistorial->setValor($ItTransaccion->getValor());
                        $UsuarioHistorial->setExternoId($ItTicketEnc->ticketId);

                        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

                        $diff = time() - $timeG;
                        //syslog(10, 'ITN-DIFF-TIME 7-2 ' . 'DEBIT' . $transsportsbookApi->getIdentificador() . " " . $diff);

                        break;

                    case "PUNTOVENTA":


                        $diff = time() - $timeG;
                        //syslog(10, 'ITN-DIFF-TIME 7 ' . 'DEBIT' . $transsportsbookApi->getIdentificador() . " " . $diff);


                        try {
                            $Clasificador = new Clasificador('', 'RELATIONSHIPUSERONLINE');
                            $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioMandante->usuarioMandante, 'A', $Clasificador->getClasificadorId());
                            $IdUsuarioRelacionado = $UsuarioConfiguracion->valor;

                            if ($IdUsuarioRelacionado != '' && $IdUsuarioRelacionado != null && intval($IdUsuarioRelacionado) > 0) {
                                $ItTicketEncInfo1 = new ItTicketEncInfo1();
                                $ItTicketEncInfo1->ticketId = $ItTicketEnc->getTicketId();
                                $ItTicketEncInfo1->tipo = 'USUARIORELACIONADO';
                                $ItTicketEncInfo1->valor = $IdUsuarioRelacionado;
                                $ItTicketEncInfo1->valor2 = 0;
                                $ItTicketEncInfo1->fechaCrea = date('Y-m-d H:i:s');
                                $ItTicketEncInfo1->fechaModif = date('Y-m-d H:i:s');
                                $ItTicketEncInfo1MySqlDAO = new ItTicketEncInfo1MySqlDAO($Transaction);
                                $ItTicketEncInfo1MySqlDAO->insert($ItTicketEncInfo1);
                            }
                        } catch (Exception $ex) {
                        }

                        $diff = time() - $timeG;
                        //syslog(10, 'ITN-DIFF-TIME 7-1 ' . 'DEBIT' . $transsportsbookApi->getIdentificador() . " " . $diff);

                        $FlujoCaja = new FlujoCaja();
                        $FlujoCaja->setFechaCrea(date('Y-m-d'));
                        $FlujoCaja->setHoraCrea(date('H:i'));
                        $FlujoCaja->setUsucreaId($Usuario->usuarioId);
                        $FlujoCaja->setTicketId($ItTransaccion->getTicketId());
                        $FlujoCaja->setTipomovId('E');
                        $FlujoCaja->setValor($ItTransaccion->getValor());
                        $FlujoCaja->setRecargaId(0);
                        $FlujoCaja->setMandante($Usuario->mandante);
                        $FlujoCaja->setValorForma1($ItTransaccion->getValor());

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

                        $diff = time() - $timeG;
                        //syslog(10, 'ITN-DIFF-TIME 7-2 ' . 'DEBIT' . $transsportsbookApi->getIdentificador() . " " . $diff);

                        if ($rowsUpdate > 0) {
                            $PuntoVenta = new PuntoVenta("", $Usuario->puntoventaId);
                            $rowsUpdate = $PuntoVenta->setBalanceCreditosBase(-$debitAmountTax, $Transaction);

                            $diff = time() - $timeG;
                            //syslog(10, 'ITN-DIFF-TIME 7-3 ' . 'DEBIT' . $transsportsbookApi->getIdentificador() . " " . $diff);

                            if ($rowsUpdate > 0) {
                                $UsuarioHistorial = new UsuarioHistorial();
                                $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                                $UsuarioHistorial->setDescripcion('');
                                $UsuarioHistorial->setMovimiento('S');
                                $UsuarioHistorial->setUsucreaId(0);
                                $UsuarioHistorial->setUsumodifId(0);
                                $UsuarioHistorial->setTipo(20);
                                $UsuarioHistorial->setValor($ItTransaccion->getValor());
                                $UsuarioHistorial->setExternoId($ItTicketEnc->ticketId);

                                $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                                $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');

                                $diff = time() - $timeG;
                                //syslog(10, 'ITN-DIFF-TIME 7-4 ' . 'DEBIT' . $transsportsbookApi->getIdentificador() . " " . $diff);


                            } else {
                                throw new Exception("You have reached your gaming balance limit. Please withdraw to place bet", 100030);
                            }
                        }

                        break;


                    case "CAJERO":


                        $diff = time() - $timeG;
                        //syslog(10, 'ITN-DIFF-TIME 7 ' . 'DEBIT' . $transsportsbookApi->getIdentificador() . " " . $diff);


                        try {
                            $Clasificador = new Clasificador('', 'RELATIONSHIPUSERONLINE');
                            $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioMandante->usuarioMandante, 'A', $Clasificador->getClasificadorId());
                            $IdUsuarioRelacionado = $UsuarioConfiguracion->valor;

                            if ($IdUsuarioRelacionado != '' && $IdUsuarioRelacionado != null && intval($IdUsuarioRelacionado) > 0) {
                                $ItTicketEncInfo1 = new ItTicketEncInfo1();
                                $ItTicketEncInfo1->ticketId = $ItTicketEnc->getTicketId();
                                $ItTicketEncInfo1->tipo = 'USUARIORELACIONADO';
                                $ItTicketEncInfo1->valor = $IdUsuarioRelacionado;
                                $ItTicketEncInfo1->valor2 = 0;
                                $ItTicketEncInfo1->fechaCrea = date('Y-m-d H:i:s');
                                $ItTicketEncInfo1->fechaModif = date('Y-m-d H:i:s');
                                $ItTicketEncInfo1MySqlDAO = new ItTicketEncInfo1MySqlDAO($Transaction);
                                $ItTicketEncInfo1MySqlDAO->insert($ItTicketEncInfo1);
                            }
                        } catch (Exception $ex) {
                        }

                        $diff = time() - $timeG;
                        //syslog(10, 'ITN-DIFF-TIME 7-2 ' . 'DEBIT' . $transsportsbookApi->getIdentificador() . " " . $diff);


                        $FlujoCaja = new FlujoCaja();
                        $FlujoCaja->setFechaCrea(date('Y-m-d'));
                        $FlujoCaja->setHoraCrea(date('H:i'));
                        $FlujoCaja->setUsucreaId($Usuario->usuarioId);
                        $FlujoCaja->setTicketId($ItTransaccion->getTicketId());
                        $FlujoCaja->setTipomovId('E');
                        $FlujoCaja->setValor($ItTransaccion->getValor());
                        $FlujoCaja->setRecargaId(0);
                        $FlujoCaja->setMandante($Usuario->mandante);
                        $FlujoCaja->setValorForma1($ItTransaccion->getValor());

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

                        if ($rowsUpdate > 0) {
                            $diff = time() - $timeG;
                            //syslog(10, 'ITN-DIFF-TIME 7-3 ' . 'DEBIT' . $transsportsbookApi->getIdentificador() . " " . $diff);

                            $PuntoVenta = new PuntoVenta("", $Usuario->puntoventaId);
                            $rowsUpdate = $PuntoVenta->setBalanceCreditosBase(-$debitAmountTax, $Transaction);

                            $diff = time() - $timeG;
                            //syslog(10, 'ITN-DIFF-TIME 7-4 ' . 'DEBIT' . $transsportsbookApi->getIdentificador() . " " . $diff);

                            if ($rowsUpdate > 0) {
                                $UsuarioHistorial = new UsuarioHistorial();
                                $UsuarioHistorial->setUsuarioId($Usuario->puntoventaId);
                                $UsuarioHistorial->setDescripcion('');
                                $UsuarioHistorial->setMovimiento('S');
                                $UsuarioHistorial->setUsucreaId(0);
                                $UsuarioHistorial->setUsumodifId(0);
                                $UsuarioHistorial->setTipo(20);
                                $UsuarioHistorial->setValor($ItTransaccion->getValor());
                                $UsuarioHistorial->setExternoId($ItTicketEnc->ticketId);

                                $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                                $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');

                                $diff = time() - $timeG;
                                //syslog(10, 'ITN-DIFF-TIME 7-5 ' . 'DEBIT' . $transsportsbookApi->getIdentificador() . " " . $diff);


                            } else {
                                throw new Exception("You have reached your gaming balance limit. Please withdraw to place bet", 100030);
                            }
                        } else {
                            throw new Exception("You have reached your gaming balance limit. Please withdraw to place bet", 100030);
                        }

                        break;
                }
            }


            $ItTransaccionMySqlDAO = new ItTransaccionMySqlDAO($Transaction);

            $ItCuentatransId = $ItTransaccionMySqlDAO->insert($ItTransaccion);
            if ($taxedValue > 0) {
                $ItCuentatransId2 = $ItTransaccionMySqlDAO->insert($ItTransaccion2);
            }


            $diff = time() - $timeG;
            //syslog(10, 'ITN-DIFF-TIME 8-0 ' . 'DEBIT' . $transsportsbookApi->getIdentificador() . " " . $diff);


            $transaccion_id = $ItTicketEncMySqlDAO->insert($ItTicketEnc);

            $transsportsbookApi->setTranssportId($transaccion_id);
            /**
             * COMMIT de la transacción
             */

            $Transaction->commit();


            $diff = time() - $timeG;
            //syslog(10, 'ITN-DIFF-TIME 8 ' . 'DEBIT' . $transsportsbookApi->getIdentificador() . " " . $diff);

            if ($UsuarioPerfil->getPerfilId() == 'USUONLINE') {
                $typeP = 'SPORTBOOK';
                //exec("php -f " . __DIR__ . "/../casino/AgregarValorJackpot.php " . $typeP . " " . $ItTicketEnc->ticketId . " > /dev/null &");

            }

            if ($Mandante->propio == "S") {
                /*  Consultamos de nuevo el usuario para obtener el saldo  */
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);

                $detalles2 = array(
                    "JuegosCasino" => array(
                        array(
                            "Id" => 2
                        )

                    ),
                    "ValorApuesta" => 2000
                );

                $BonoInterno = new BonoInterno();
                //$respuesta = $BonoInterno->verificarBonoRollower($Usuario->usuarioId, $detalles2, 'SPORT', $ItTicketEnc->ticketId);


                switch ($UsuarioPerfil->getPerfilId()) {
                    case "USUONLINE":

                        if ($Usuario->billeteraId == 1) {
                            $Balance = 100000;
                        } else {
                            $Balance = (($Usuario->getBalance()));
                        }


                        break;

                    case "MAQUINAANONIMA":


                        /*$PuntoVenta = new PuntoVenta("",$UsuarioMandante->usuarioMandante);


                    $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                    $SaldoJuego = $PuntoVenta->getCreditosBase();

                    $Balance = $SaldoJuego;*/


                        $Balance = (($Usuario->getBalance()));

                        break;

                    case "PUNTOVENTA":

                        $PuntoVenta = new PuntoVenta("", $UsuarioMandante->usuarioMandante);

                        $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                        $SaldoJuego = $PuntoVenta->getCreditosBase();

                        $Balance = $SaldoJuego + $SaldoRecargas;


                        break;
                }


                /*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
                if (false) {
                    try {
                        $UsuarioMandante = new UsuarioMandante('203');

                        /*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
                        $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
//$data = $Usuario->getWSMessage($UsuarioToken->getRequestId());

                        /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */


                        $UsuarioSession = new UsuarioSession();
                        $rules = [];

                        array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                        array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json2 = json_encode($filtro);
                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                        $data = $Usuario->getWSMessage($UsuarioToken->getRequestId());


                        $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 1000000, $json2, true);

                        $usuarios = json_decode($usuarios);

                        $usuariosFinal = [];

                        foreach ($usuarios->data as $key => $value) {
                            //$dataF = $UsuarioMandante->getWSProfileSite($value->{'usuario_session.request_id'});
                            //$dataF = $UsuarioMandante->getWSProfileSite($value->{'usuario_session.request_id'});

                            $data2 = array(
                                "machinePrint" => '',
                                "messageIntern" => '',
                                "continueToFront" => 1,
                                "machinePrintURL" => 'https://operatorapi.virtualsoft.tech/machineprint/deposit?id='

                            );

                            $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data2);
                            $WebsocketUsuario->sendWSMessage($value->{'usuario_session.tipo'});


                            $dataF = str_replace("7040" . $UsuarioToken->getRequestId() . "1", "7040" . $value->{'usuario_session.request_id'} . "1", json_encode($data));
                            $dataF = json_decode($dataF, true);
                            $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $dataF);
                            $WebsocketUsuario->sendWSMessage($value->{'usuario_session.tipo'});
                        }
                    } catch (Exception $e) {
                    }
                }
            }

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $transsportsbookApi->setRespuestaCodigo(0);
            $transsportsbookApi->setRespuesta('');
            $TranssportsbookApiMySqlDAO = new TranssportsbookApiMySqlDAO();
            $TranssportsbookApiMySqlDAO->insert($transsportsbookApi);

            if ($TranssportsbookApiMandante != "" && $TranssportsbookApiMandante != null) {
                $TranssportsbookApiMandante->settranssportapiId($transsportsbookApi->transsportapiId);
                $TranssportsbookApiMandanteMySqlDAO = new TranssportsbookApiMandanteMySqlDAO($TranssportsbookApiMySqlDAO->getTransaction());
                $TranssportsbookApiMandanteMySqlDAO->update($TranssportsbookApiMandante);
            }

            $TranssportsbookApiMySqlDAO->getTransaction()->commit();

            //exec("php -f ".__DIR__."/ActivacionRuletaSportBook.php ".$UsuarioMandante->paisId  ." ".$UsuarioMandante->usuarioMandante." ".$debitAmount." ". 1 ." ".$detallesSportsbookRuleta." > /dev/null &");
            $ticketId = $ItTicketEnc->ticketId;
            $useragent = $_SERVER['HTTP_USER_AGENT'];
            $jsonServer = json_encode($_SERVER);
            $serverCodif = base64_encode($jsonServer);


            $ismobile = '';

            if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match(
                    '/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4)
                )) {
                $ismobile = '1';
            }
//Detect special conditions devices
            $iPod = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");
            $iPhone = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
            $iPad = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
            $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
            $webOS = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");


//do something with this information
            if ($iPod || $iPhone) {
                $ismobile = '1';
            } elseif ($iPad) {
                $ismobile = '1';
            } elseif ($Android) {
                $ismobile = '1';
            }

            $diff = time() - $timeG;
            //syslog(10, 'ITN-DIFF-TIME 9 ' . 'DEBIT' . $transsportsbookApi->getIdentificador() . " " . $diff);

            exec("php -f " . __DIR__ . "/../integrations/crm/AgregarCrm.php " . $Usuario->usuarioId . " " . "BETSPORTSBOOKCRM" . " " . $ItTicketEnc->itTicketId . " " . $serverCodif . " " . $ismobile . " > /dev/null &");

            $respuesta->usuarioId = $UsuarioMandante->usuarioMandante;
            $respuesta->moneda = $UsuarioMandante->moneda;
            $respuesta->usuario = "Usuario" . $UsuarioMandante->usuarioMandante;
            $respuesta->saldo = $Balance;
            $respuesta->transaccionId = $ItTransaccion->itCuentatransId;
            $respuesta->transsportsbookApi = $transsportsbookApi;
            $respuesta->clave = $ItTicketEnc->getClave();
            $respuesta->saldoFree = $saldoFree;
            $respuesta->idUsuarioRelacionado = $IdUsuarioRelacionado;

            /*$UsuarioToken->setFechaModif(date('Y-m-d H:i:s'));
        $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
        $UsuarioTokenMySqlDAO->update($UsuarioToken);
        $UsuarioTokenMySqlDAO->getTransaction()->commit();*/


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
     * Verifica y realiza el débito de un usuario para una transacción de juego.
     *
     * @param object $UsuarioMandante    Objeto que representa al usuario mandante.
     * @param object $Producto           Objeto que representa el producto asociado al juego.
     * @param object $transsportsbookApi Objeto que contiene los datos de la transacción API.
     * @param mixed  $ticket             Información del ticket asociado a la transacción.
     *
     * @return object Respuesta con información del usuario, saldo y detalles de la transacción.
     *
     * @throws Exception Si ocurre algún error durante el proceso, como usuario inactivo o transacción ya procesada.
     */
    public function checkdebit($UsuarioMandante, $Producto, $transsportsbookApi, $ticket)
    {
        try {
            $debitAmount = $transsportsbookApi->getValor();

            $respuesta = json_decode(json_encode(array(
                "usuarioId" => "",
                "moneda" => "",
                "usuario" => "",
                "transaccionId" => "",
                "saldo" => "",
                "transsportsbookApi" => ""
            )));

            $transactionId = $transsportsbookApi->getTransaccionId();

            /*  Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego  */
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());


            /*  Agregamos Elementos a la Transaccion API  */
            $transsportsbookApi->setProductoId($ProductoMandante->prodmandanteId);
            $transsportsbookApi->setUsuarioId($UsuarioMandante->getUsumandanteId());


            /*  Creamos la Transaccion por el Juego  */
            $ItTicketEnc = new ItTicketEnc($transsportsbookApi->getIdentificador());

            /*  Verificamos que la transaccionId no se haya procesado antes  */
            if ($transsportsbookApi->existsTransaccionIdAndProveedor("OK")) {
                /*  Si la transaccionId ha sido procesada, reportamos el error  */
                throw new Exception("Transaccion ya procesada", "10005");
            }


            /*  Obtenemos el mandante para verificar sus caracteristicas  */
            $Mandante = new Mandante($UsuarioMandante->mandante);


            /*  Verificamos que el mandante sea Propio, para proceder con nuestros Usuarios  */
            if ($Mandante->propio == "S") {
                /*  Obtenemos nuestro Usuario y hacemos el debito  */
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);

                if ($Usuario->estado != "A") {
                    throw new Exception("Usuario Inactivo", "20003");
                }


                switch ($UsuarioPerfil->getPerfilId()) {
                    case "USUONLINE":


                        //$Usuario->debit($debitAmount, $Transaction);


                        break;

                    case "MAQUINAANONIMA":

                        /*
                                        $PuntoVenta = new PuntoVenta("",$UsuarioMandante->usuarioMandante);
                                        $PuntoVenta->setBalanceCreditosBase($debitAmount,$Transaction);
                    */

                        break;
                }
            } else {
                throw new Exception("Usuario Inactivo", "20003");
            }

            /**
             * COMMIT de la transacción
             */

            //$Transaction->commit();


            if ($Mandante->propio == "S") {
                /*  Consultamos de nuevo el usuario para obtener el saldo  */
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);


                switch ($UsuarioPerfil->getPerfilId()) {
                    case "USUONLINE":

                        $Balance = $Usuario->getBalance();

                        break;

                    case "MAQUINAANONIMA":


                        /*$PuntoVenta = new PuntoVenta("",$UsuarioMandante->usuarioMandante);

                    $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                    $SaldoJuego = $PuntoVenta->getCreditosBase();

                    $Balance = $SaldoJuego;*/

                        $Balance = $Usuario->getBalance();


                        break;

                    case "PUNTOVENTA":

                        $PuntoVenta = new PuntoVenta("", $UsuarioMandante->usuarioMandante);

                        $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                        $SaldoJuego = $PuntoVenta->getCreditosBase();

                        $Balance = $SaldoJuego + $SaldoRecargas;


                        break;
                }


                /*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
                /*$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

            /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
                /*$data = $UsuarioMandante->getWSProfileSite($UsuarioToken->getRequestId());
            $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
            $WebsocketUsuario->sendWSMessage();*/
                //$UsuarioToken = new UsuarioToken("", $Producto->getProveedorId(), $UsuarioMandante->getUsumandanteId());

            }

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $transsportsbookApi->setRespuestaCodigo(0);
            $transsportsbookApi->setRespuesta('');
            $TranssportsbookApiMySqlDAO = new TranssportsbookApiMySqlDAO();
            $TranssportsbookApiMySqlDAO->insert($transsportsbookApi);


            $TranssportsbookApiMySqlDAO->getTransaction()->commit();


            $respuesta->usuarioId = $UsuarioMandante->usuarioMandante;
            $respuesta->moneda = $UsuarioMandante->moneda;
            $respuesta->usuario = "Usuario" . $UsuarioMandante->usuarioMandante;
            $respuesta->saldo = $Balance;
            $respuesta->transaccionId = '';
            $respuesta->transsportsbookApi = $transsportsbookApi;


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
     * Procesa la transacción de un ticket, actualizando su estado y manejando las operaciones
     * relacionadas con el usuario, mandante y otros elementos del sistema.
     *
     * @param object  $UsuarioMandante    Información del usuario mandante.
     * @param object  $Producto           Información del producto asociado.
     * @param object  $transsportsbookApi Objeto que contiene los datos de la transacción API.
     * @param boolean $isEndRound         Indica si la transacción corresponde al final de una ronda.
     * @param string  $ticket             Información del ticket asociado.
     *
     * @return object Respuesta con los detalles de la transacción, saldo y otros datos relevantes.
     *
     * @throws Exception Si ocurre algún error durante el procesamiento de la transacción.
     */
    public function credit($UsuarioMandante, $Producto, $transsportsbookApi, $isEndRound, $ticket)
    {
        try {
            $respuesta = json_decode(json_encode(array(
                "usuarioId" => "",
                "moneda" => "",
                "usuario" => "",
                "transaccionId" => "",
                "saldo" => "",
                "transsportsbookApi" => ""
            )));

            $creditAmount = $transsportsbookApi->getValor();


            /*  Verificamos que el monto a creditar sea positivo */
            if ($creditAmount < 0) {
                throw new Exception("No puede ser negativo el monto a debitar.", "10002");
            }


            /*  Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego  */
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            /*  Agregamos Elementos a la Transaccion API  */
            $transsportsbookApi->setProductoId($ProductoMandante->prodmandanteId);
            $transsportsbookApi->setUsuarioId($UsuarioMandante->getUsumandanteId());
            $transsportsbookApi->setMandante($UsuarioMandante->getMandante());


            /*  Verificamos que la transaccionId no se haya procesado antes  */
            if ($transsportsbookApi->existsTransaccionIdAndProveedor("OK")) {
                /*  Si la transaccionId ha sido procesada, reportamos el error  */
                throw new Exception("Transaccion ya procesada", "10001");
            }


            /*  Obtenemos la Transaccion Juego   */


            $ItTicketEnc = new ItTicketEnc($transsportsbookApi->getIdentificador());


            /* Obtenemos la Transaccion de la BD para crear y registrar el Debito con esta transaccion */
            $ItTicketEncMySqlDAO = new ItTicketEncMySqlDAO();
            $Transaction = $ItTicketEncMySqlDAO->getTransaction();

            /*  Obtenemos el ID de la ItTicketEnc  */
            $TranssportId = $ItTicketEnc->getItTicketId();

            /*  Obtenemos el valor de apuesta original de ItTicketEnc  */
            $valorOrgApuesta = $ItTicketEnc->getVlrApuesta();

            /*  Obtenemos el valor creditos original de ItTicketEnc  */
            $valorOrgCreditos = $ItTicketEnc->getSaldoCreditos();

            /*  Obtenemos el valor base original de ItTicketEnc  */
            $valorOrgCreditosBase = $ItTicketEnc->getSaldoCreditosBase();

            $transsportsbookApi->setTranssportId($TranssportId);

            /*  Obtenemos el tipo de transacción dependiendo a betTypeID y si se le suma los creditos o no */
            $sumaCreditos = false;
            $tipoTransaccion = $ticket->TipoTransaccion;


            /*  ESTADOS DE LAS APUESTAS DE ITAINMENT
       C = Open (Abierto)
       S = Won (Gano)
       N = Lost (Perdio)
       A = Void (No Accion)
       R = Pending (Pendiente)
       W = Waiting (En Espera)
       J = Rejected (Rechazada)
       M = RejectedByMTS (Rechazada por Regla)
       T = Cashout (Retiro Voluntario)
        */

            /*  Creamos el log de la transaccion juego para auditoria  */
            $ItTransaccion = new ItTransaccion();
            $ItTransaccion->setTipo($tipoTransaccion);
            $ItTransaccion->setTicketId($ItTicketEnc->ticketId);
            $ItTransaccion->setGameReference($ticket->GameReference);
            $ItTransaccion->setUsuarioId($UsuarioMandante->usuarioMandante);
            $ItTransaccion->setBetStatus($ticket->BetStatus);
            $ItTransaccion->setValor($creditAmount);
            $ItTransaccion->setTransaccionId($transsportsbookApi->getTransaccionId());
            $ItTransaccion->setMandante($UsuarioMandante->mandante);


            $ItTransaccionMySqlDAO = new ItTransaccionMySqlDAO($Transaction);

            $ItCuentatransId = $ItTransaccionMySqlDAO->insert($ItTransaccion);


            /*  Actualizamos la Transaccion Juego con los respectivas actualizaciones  */
            if ($isEndRound) {
                $estado_ticket = "I";
                if ($ticket->BetStatus == "C" or $ticket->BetStatus == "R" or $ticket->BetStatus == "W") {
                    $estado_ticket = "A";
                }

                /*  Consultamos de nuevo el usuario para obtener el saldo  */
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);

                switch ($tipoTransaccion) {
                    case "LOSS":
                        if ($UsuarioPerfil->perfilId == 'USUONLINE') {
                            $ItTicketEnc->setPremiado("N");
                            $ItTicketEnc->setVlrPremio(0);
                            $ItTicketEnc->setPremioPagado("N");

                            if ($ItTicketEnc->getFechaCierre() == '' || $ItTicketEnc->getFechaCierre() == null) {
                                $ItTicketEnc->setFechaCierre(date('Y-m-d H:i:s', time()));
                            }

                            $ItTicketEnc->setFechaPago('');
                            $ItTicketEnc->setHoraPago('');
                            $ItTicketEnc->setEstado('I');
                        } else {
                            if (($ItTicketEnc->getFechaCierre() == '' || $ItTicketEnc->getFechaCierre() == null) && $ItTicketEnc->getPremioPagado() == 'N') {
                                $ItTicketEnc->setFechaCierre(date('Y-m-d H:i:s', time()));
                            }

                            if ($ItTicketEnc->getPremioPagado() == 'N') {
                                $ItTicketEnc->setVlrPremio(0);
                                $ItTicketEnc->setPremiado("N");
                                $ItTicketEnc->setPremioPagado("N");
                                $ItTicketEnc->setFechaPago('');
                                $ItTicketEnc->setHoraPago('');
                                $ItTicketEnc->setEstado('I');
                            }
                        }

                        break;

                    case "WIN":

                        //Verificamos que el premio ya fue pagado
                        if ($ItTicketEnc->getPremioPagado() == "S") {
                            throw new Exception("Premio ya fue pagado", "300002"); //FALTA
                        }
                        /*  Consultamos de nuevo el usuario para obtener el saldo  */
                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                        $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);


                        if ($UsuarioPerfil->perfilId == 'USUONLINE') {
                            $impuesto = 0;


                            try {
                                $Clasificador = new Clasificador("", "TAXPRIZEUSUONLINE");
                                $minimoMontoPremios = 0;

                                $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->paisId, 'A');

                                $impuestoPorcSobrePremio = $MandanteDetalle->getValor();

                                $paraImpuesto = floatval($creditAmount) - floatval($ItTicketEnc->getVlrApuesta());
                                if ($paraImpuesto < 0) {
                                    $impuesto += 0;
                                } else {
                                    $impuesto += floatval($impuestoPorcSobrePremio / 100) * $paraImpuesto;
                                }
                            } catch (Exception $e) {
                            }

                            $ItTicketEnc->setImpuesto($impuesto);
                        }
                        if ($UsuarioPerfil->perfilId == 'USUONLINE') {
                            $ItTicketEnc->setVlrPremio($creditAmount);
                            if ($ItTicketEnc->getVlrPremio() > 0) {
                            }
                            $ItTicketEnc->setPremiado("S");
                            $ItTicketEnc->setFechaPago(date('Y-m-d H:i:s', time()));
                            $ItTicketEnc->setFechaCierre(date('Y-m-d H:i:s', time()));
                            $ItTicketEnc->setPremioPagado("S");
                        } else {
                            $ItTicketEnc->setVlrPremio($creditAmount);
                            if ($ItTicketEnc->getVlrPremio() > 0) {
                                if (($ItTicketEnc->getFechaCierre() == '' || $ItTicketEnc->getFechaCierre() == null) && $ItTicketEnc->getPremioPagado() == 'N') {
                                    $ItTicketEnc->setFechaCierre(date('Y-m-d H:i:s', time()));
                                }

                                if ($ItTicketEnc->getPremioPagado() == 'N') {
                                    $ItTicketEnc->setPremiado("S");
                                }

                                $ItTicketEnc->setPremioPagado("N");
                                $ItTicketEnc->setFechaMaxpago(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . "+ 90 days")));
                            }
                        }


                        break;

                    case "CASHOUT":

                        if ($ItTicketEnc->freebet != 0 && $ItTicketEnc->freebet != "") {
                            throw new Exception("El cashout no se puede aceptar por politicas internas", "300003"); //FALTA
                        }

                        $valorPremio1 = 0;
                        $valorPremio2 = 0;
                        try {
                            $ItTicketEncInfo = new ItTicketEncInfo1("", $ItTicketEnc->ticketId, 'JACKPOT');
                            $userJackpot = new UsuarioJackpot($ItTicketEncInfo->valor);
                            $userJackpotWin = new UsuariojackpotGanador("", $userJackpot->usujackpotId, $userJackpot->jackpotId, 'INCOME_SPORTBOOK');
                            $valorPremio1 = $userJackpot->valorPremio;
                            $valorPremio2 = $userJackpotWin->valorPremio;
                        } catch (Exception $e) {
                        }

                        if ($valorPremio1 > 0 && $valorPremio2 > 0) {
                            throw new Exception("El cashout no se puede aceptar por politicas internas", "300003"); //FALTA
                        }

                        if ($UsuarioPerfil->perfilId == 'USUONLINE') {
                            $impuesto = 0;


                            try {
                                $Clasificador = new Clasificador("", "TAXPRIZEUSUONLINE");
                                $minimoMontoPremios = 0;

                                $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->paisId, 'A');

                                $impuestoPorcSobrePremio = $MandanteDetalle->getValor();

                                $paraImpuesto = floatval($creditAmount) - floatval($ItTicketEnc->getVlrApuesta());
                                if ($paraImpuesto < 0) {
                                    $impuesto += 0;
                                } else {
                                    $impuesto += floatval($impuestoPorcSobrePremio / 100) * $paraImpuesto;
                                }
                            } catch (Exception $e) {
                            }

                            $ItTicketEnc->setImpuesto($impuesto);
                        }

                        $ItTicketEnc->setVlrPremio($creditAmount);
                        $ItTicketEnc->setPremiado("S");
                        //$ItTicketEnc->setFechaPago(date('Y-m-d H:i:s', time()));

                        if ($ItTicketEnc->fechaCierre == "" || $ItTicketEnc->fechaCierre == null) {
                            $ItTicketEnc->setFechaCierre(date('Y-m-d H:i:s', time()));
                        }


                        if ($UsuarioPerfil->perfilId != 'USUONLINE') {
                            $ItTicketEnc->setPremioPagado("N");
                            $ItTicketEnc->setFechaMaxpago(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . "+ 90 days")));
                        } else {
                            $ItTicketEnc->setPremioPagado("S");
                        }

                        break;

                    case "NEWDEBIT":


                        if ($UsuarioPerfil->perfilId == 'USUONLINE') {
                            if ($ItTicketEnc->getVlrPremio() - $creditAmount == 0) {
                                $ItTicketEnc->setPremiado("N");
                            }

                            if ($ItTicketEnc->getVlrPremio() - $creditAmount == 0) {
                                $ItTicketEnc->setPremioPagado("N");
                            }
                            $ItTicketEnc->setVlrPremio($ItTicketEnc->getVlrPremio() - $creditAmount);

                            $ItTicketEnc->setEliminado("N");
                        } else {
                            if ($ItTicketEnc->getPremioPagado() == 'N') {
                                if ($ItTicketEnc->getVlrPremio() - $creditAmount == 0) {
                                    $ItTicketEnc->setPremiado("N");
                                }

                                if ($ItTicketEnc->getVlrPremio() - $creditAmount == 0) {
                                    $ItTicketEnc->setPremioPagado("N");
                                }

                                $ItTicketEnc->setVlrPremio($ItTicketEnc->getVlrPremio() - $creditAmount);

                                $ItTicketEnc->setEliminado("N");
                            }
                        }

                        break;

                    case "NEWCREDIT":


                        if ($UsuarioPerfil->perfilId == 'USUONLINE') {
                            $ItTicketEnc->setPremiado("S");
                            $ItTicketEnc->setFechaPago(date('Y-m-d H:i:s', time()));
                            $ItTicketEnc->setPremioPagado("S");
                            $ItTicketEnc->setVlrPremio($ItTicketEnc->getVlrPremio() + $creditAmount);
                        } else {
                            //Verificamos que el premio ya fue pagado
                            if ($ItTicketEnc->getPremioPagado() == "S") {
                                throw new Exception("Premio ya fue pagado", "300002"); //FALTA
                            }
                            if ($ItTicketEnc->getPremioPagado() == 'N') {
                                $ItTicketEnc->setVlrPremio($ItTicketEnc->getVlrPremio() + $creditAmount);
                                $ItTicketEnc->setPremiado("S");
                                $ItTicketEnc->setPremioPagado("N");
                                $ItTicketEnc->setFechaMaxpago(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . "+ 90 days")));
                            }
                        }


                        break;

                    case "STAKEDECREASE":


                        if ($UsuarioPerfil->perfilId == 'USUONLINE') {
                            if ($estado_ticket == "A") {
                            } else {
                                if ($ItTicketEnc->fechaCierre == "" || $ItTicketEnc->fechaCierre == null) {
                                    $ItTicketEnc->setFechaCierre(date('Y-m-d H:i:s', time()));
                                }
                            }

                            $ItTicketEnc->setVlrApuesta($ItTicketEnc->getVlrApuesta() - $creditAmount);

                            $valor_base = 0;
                            $valor_adicional = 0;

                            $creditos_base = $ItTicketEnc->getSaldoCreditosBase();
                            $saldoRetiros = $ItTicketEnc->getSaldoCreditos();

                            if ($UsuarioPerfil->perfilId == "USUONLINE") {
                                /* Obtenemos los valores declarados ateriormente de ItTicketEnc */
                                $valorApuesta = $valorOrgApuesta;
                                $saldoCreditos = $saldoRetiros;
                                $saldoCreditosBase = $creditos_base;

                                /* Obtenemos el porcentaje base de la apuesta */
                                $porcentajeBase = 0;
                                if ($saldoCreditosBase != 0) {
                                    $porcentajeBase = ($saldoCreditosBase / $valorApuesta) * 100;
                                }

                                /* Obtenemos el porcentaje creditos de la apuesta */
                                $porcentajeCreditos = 0;
                                if ($saldoCreditos != 0) {
                                    $porcentajeCreditos = ($saldoCreditos / $valorApuesta) * 100;
                                }

                                /* Obtenemos el valor base aplicando los porcentajes a creditAmount */
                                $valor_base = ($porcentajeBase / 100) * $creditAmount;
                                $valor_base = $saldoCreditosBase - $valor_base;

                                /* Obtenemos el valor creditos aplicando los porcentajes a creditAmount */
                                $valor_adicional = ($porcentajeCreditos / 100) * $creditAmount;
                                $valor_adicional = $saldoCreditos - $valor_adicional;
                            } else {
                                $valor_base = $saldoRetiros - $creditAmount;
                                $valor_adicional = 0;
                            }

                            $ItTicketEnc->setSaldoCreditos($valor_adicional);
                            $ItTicketEnc->setSaldoCreditosBase($valor_base);
                        } else {
                            if ($ItTicketEnc->getPremioPagado() == 'N') {
                                if ($estado_ticket == "A") {
                                } else {
                                    if ($ItTicketEnc->fechaCierre == "" || $ItTicketEnc->fechaCierre == null) {
                                        $ItTicketEnc->setFechaCierre(date('Y-m-d H:i:s', time()));
                                    }
                                }

                                $ItTicketEnc->setVlrApuesta($ItTicketEnc->getVlrApuesta() - $creditAmount);
                            }
                        }


                        break;

                    default:

                        break;
                }

                $ItTicketEnc->setEstado($estado_ticket);
                $ItTicketEnc->setBetStatus($ticket->BetStatus);
            }


            /*  Obtenemos el mandante para verificar sus caracteristicas  */
            $Mandante = new Mandante($UsuarioMandante->mandante);


            /*  Verificamos si el mandante es propio  */
            if ($Mandante->propio == "S") {
                /*  Obtenemos nuestro Usuario y hacemos el debito  */
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);

                if ($Usuario->estado != "A") {
                    //throw new Exception("Usuario Inactivo", "20003");

                }
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);


                switch ($UsuarioPerfil->getPerfilId()) {
                    case "USUONLINE":
                        switch ($tipoTransaccion) {
                            case "WIN":
                                $ItTicketEnc->setVlrPremio($creditAmount);
                                if ($ItTicketEnc->getVlrPremio() > 0) {
                                    if ($Usuario->billeteraId == 1 && false) {
                                        $Registro = new Registro("", $Usuario->usuarioId);

                                        $GetResultsRequest = new SimpleXMLElement("<GetResultsRequest></GetResultsRequest>");

                                        $ExternalAccountId = $GetResultsRequest->addChild('ExternalAccountId', $Usuario->usuarioId);
                                        //$ExternalAccountId->('Value', $usuario_id);

                                        $MobileNumber = $GetResultsRequest->addChild('MobileNumber', $Registro->celular);

                                        $ProviderUserId = $GetResultsRequest->addChild('ProviderUserId', $this->QuiskProviderUserId);

                                        $ProviderPassword = $GetResultsRequest->addChild('ProviderPassword', sha1($this->QuiskProviderPassword));

                                        $MerchantId = $GetResultsRequest->addChild('MerchantId', $this->QuiskMerchantId);

                                        $PaymentTransReference = $GetResultsRequest->addChild('PaymentTransReference', $ItTicketEnc->getTransaccionWallet());
                                        $BetNumber = $GetResultsRequest->addChild('BetNumber', str_replace("ITN", "", $ItTicketEnc->ticketId));
                                        $WinningAmount = $GetResultsRequest->addChild('WinningAmount', number_format($creditAmount, 2, '.', ''));
                                        $BetAmount = $GetResultsRequest->addChild('BetAmount', number_format($ItTicketEnc->getVlrApuesta(), 2, '.', ''));
                                        $Bettingcategory = $GetResultsRequest->addChild('BettingCategory', '1');
                                        $TransDateTime = $GetResultsRequest->addChild('TransDateTime', date('Ymd H:i:s'));


                                        $responseQuisk = $this->createRequestQuisk($this->QuiskGetResultsURL, str_replace('<?xml version="1.0"?>\n', "", $GetResultsRequest->asXML()));

                                        try {
                                            $responseQuiskXML = simplexml_load_string($responseQuisk);
                                        } catch (Exception $e) {
                                            $TranssportsbookApi3 = new TranssportsbookApi();
                                            $TranssportsbookApi3->setTranssportId($ItTicketEnc->getItTicketId());
                                            $TranssportsbookApi3->setTransaccionId('QUISK' . $transsportsbookApi->getTransaccionId());
                                            $TranssportsbookApi3->setTipo('QUISK');
                                            $TranssportsbookApi3->setTValue($GetResultsRequest->asXML() . $e->getMessage());
                                            $TranssportsbookApi3->setUsucreaId(0);
                                            $TranssportsbookApi3->setUsumodifId(0);
                                            $TranssportsbookApi3->setValor($creditAmount);
                                            $TranssportsbookApi3->setUsuarioId($UsuarioMandante->usuarioMandante);

                                            $TranssportsbookApi3->setMandante($UsuarioMandante->mandante);
                                            $TranssportsbookApi3->setProveedorId(0);
                                            $TranssportsbookApi3->setIdentificador(0);

                                            $TranssportsbookApiMySqlDAO2 = new TranssportsbookApiMySqlDAO();

                                            $TranssportsbookApiMySqlDAO2->insert($TranssportsbookApi3);
                                            $TranssportsbookApiMySqlDAO2->getTransaction()->commit();
                                        }


                                        if ($responseQuiskXML) {
                                            $TranssportsbookApi3 = new TranssportsbookApi();
                                            $TranssportsbookApi3->setTranssportId($ItTicketEnc->getItTicketId());
                                            $TranssportsbookApi3->setTransaccionId('QUISK' . $transsportsbookApi->getTransaccionId());
                                            $TranssportsbookApi3->setTipo('QUISK');
                                            $TranssportsbookApi3->setTValue($GetResultsRequest->asXML() . $responseQuiskXML->asXML());
                                            $TranssportsbookApi3->setUsucreaId(0);
                                            $TranssportsbookApi3->setUsumodifId(0);
                                            $TranssportsbookApi3->setValor($creditAmount);
                                            $TranssportsbookApi3->setUsuarioId($UsuarioMandante->usuarioMandante);

                                            $TranssportsbookApi3->setMandante($UsuarioMandante->mandante);
                                            $TranssportsbookApi3->setProveedorId(0);
                                            $TranssportsbookApi3->setIdentificador(0);

                                            $TranssportsbookApiMySqlDAO2 = new TranssportsbookApiMySqlDAO();

                                            $TranssportsbookApiMySqlDAO2->insert($TranssportsbookApi3);
                                            $TranssportsbookApiMySqlDAO2->getTransaction()->commit();

                                            switch ($responseQuiskXML->Status) {
                                                case "SUCCESS":
                                                    $Balance = 10000;

                                                    break;
                                                case "BAD_REQUEST":
                                                    throw new Exception("BAD_REQUEST", 50002);

                                                    break;
                                                case "INSUFFICIENT_FUNDS":
                                                    throw new Exception("INSUFFICIENT_FUNDS", 50003);

                                                    break;
                                                case "INVALID_BET_NUMBER":
                                                    throw new Exception("INVALID_BET_NUMBER", 50004);

                                                    break;
                                                case "INVALID_MERCHANT":
                                                    throw new Exception("INVALID_MERCHANT", 50005);

                                                    break;
                                                case "INVALID_TOKEN":
                                                    throw new Exception("INVALID_TOKEN", 50006);

                                                    break;
                                                case "USER_NOT_FLAGGED_BETTING":
                                                    throw new Exception("USER_NOT_FLAGGED_BETTING", 50007);

                                                    break;
                                                default:
                                                    throw new Exception($responseQuiskXML->Status, 50007);

                                                    break;
                                            }
                                        } else {
                                            $TranssportsbookApi3 = new TranssportsbookApi();
                                            $TranssportsbookApi3->setTranssportId($ItTicketEnc->getItTicketId());
                                            $TranssportsbookApi3->setTransaccionId('QUISK' . $transsportsbookApi->getTransaccionId());
                                            $TranssportsbookApi3->setTipo('QUISK');
                                            $TranssportsbookApi3->setTValue($GetResultsRequest->asXML() . $responseQuisk);
                                            $TranssportsbookApi3->setUsucreaId(0);
                                            $TranssportsbookApi3->setUsumodifId(0);
                                            $TranssportsbookApi3->setValor($creditAmount);
                                            $TranssportsbookApi3->setUsuarioId($UsuarioMandante->usuarioMandante);

                                            $TranssportsbookApi3->setMandante($UsuarioMandante->mandante);
                                            $TranssportsbookApi3->setProveedorId(0);
                                            $TranssportsbookApi3->setIdentificador(0);

                                            $TranssportsbookApiMySqlDAO2 = new TranssportsbookApiMySqlDAO();

                                            $TranssportsbookApiMySqlDAO2->insert($TranssportsbookApi3);
                                            $TranssportsbookApiMySqlDAO2->getTransaction()->commit();


                                            throw new Exception("QuiskNoRESPONDE", 50008);
                                        }
                                    } else {
                                        $Usuario->creditWin(($creditAmount - $impuesto), $Transaction);


                                        $UsuarioHistorial = new UsuarioHistorial();
                                        $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                                        $UsuarioHistorial->setDescripcion('');
                                        $UsuarioHistorial->setMovimiento('E');
                                        $UsuarioHistorial->setUsucreaId(0);
                                        $UsuarioHistorial->setUsumodifId(0);
                                        $UsuarioHistorial->setTipo(20);
                                        $UsuarioHistorial->setValor($ItTransaccion->getValor() - $impuesto);
                                        $UsuarioHistorial->setExternoId($ItTicketEnc->ticketId);

                                        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                                        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);
                                    }
                                }
                                break;

                            case "CASHOUT":

                                if ($Usuario->billeteraId == 1 && false) {
                                    $Registro = new Registro("", $Usuario->usuarioId);

                                    $GetResultsRequest = new SimpleXMLElement("<GetResultsRequest></GetResultsRequest>");

                                    $ExternalAccountId = $GetResultsRequest->addChild('ExternalAccountId', $Usuario->usuarioId);
                                    //$ExternalAccountId->('Value', $usuario_id);

                                    $MobileNumber = $GetResultsRequest->addChild('MobileNumber', $Registro->celular);

                                    $ProviderUserId = $GetResultsRequest->addChild('ProviderUserId', $this->QuiskProviderUserId);

                                    $ProviderPassword = $GetResultsRequest->addChild('ProviderPassword', sha1($this->QuiskProviderPassword));

                                    $MerchantId = $GetResultsRequest->addChild('MerchantId', $this->QuiskMerchantId);

                                    $PaymentTransReference = $GetResultsRequest->addChild('PaymentTransReference', $ItTicketEnc->getTransaccionWallet());
                                    $BetNumber = $GetResultsRequest->addChild('BetNumber', str_replace("ITN", "", $ItTicketEnc->ticketId));
                                    $WinningAmount = $GetResultsRequest->addChild('WinningAmount', number_format($creditAmount, 2, '.', ''));
                                    $BetAmount = $GetResultsRequest->addChild('BetAmount', number_format($ItTicketEnc->getVlrApuesta(), 2, '.', ''));
                                    $Bettingcategory = $GetResultsRequest->addChild('BettingCategory', '1');
                                    $TransDateTime = $GetResultsRequest->addChild('TransDateTime', date('Ymd H:i:s'));


                                    $responseQuisk = $this->createRequestQuisk($this->QuiskGetResultsURL, str_replace('<?xml version="1.0"?>\n', "", $GetResultsRequest->asXML()));


                                    try {
                                        $responseQuiskXML = simplexml_load_string($responseQuisk);
                                    } catch (Exception $e) {
                                        $TranssportsbookApi3 = new TranssportsbookApi();
                                        $TranssportsbookApi3->setTranssportId($ItTicketEnc->getItTicketId());
                                        $TranssportsbookApi3->setTransaccionId('QUISK' . $transsportsbookApi->getTransaccionId());
                                        $TranssportsbookApi3->setTipo('QUISK');
                                        $TranssportsbookApi3->setTValue($GetResultsRequest->asXML() . $e->getMessage());
                                        $TranssportsbookApi3->setUsucreaId(0);
                                        $TranssportsbookApi3->setUsumodifId(0);
                                        $TranssportsbookApi3->setValor($creditAmount);
                                        $TranssportsbookApi3->setUsuarioId($UsuarioMandante->usuarioMandante);

                                        $TranssportsbookApi3->setMandante($UsuarioMandante->mandante);
                                        $TranssportsbookApi3->setProveedorId(0);
                                        $TranssportsbookApi3->setIdentificador(0);

                                        $TranssportsbookApiMySqlDAO2 = new TranssportsbookApiMySqlDAO();

                                        $TranssportsbookApiMySqlDAO2->insert($TranssportsbookApi3);
                                        $TranssportsbookApiMySqlDAO2->getTransaction()->commit();
                                    }

                                    if ($responseQuiskXML) {
                                        $TranssportsbookApi3 = new TranssportsbookApi();
                                        $TranssportsbookApi3->setTranssportId($ItTicketEnc->getItTicketId());
                                        $TranssportsbookApi3->setTransaccionId('QUISK' . $transsportsbookApi->getTransaccionId());
                                        $TranssportsbookApi3->setTipo('QUISK');
                                        $TranssportsbookApi3->setTValue($GetResultsRequest->asXML() . $responseQuiskXML->asXML());
                                        $TranssportsbookApi3->setUsucreaId(0);
                                        $TranssportsbookApi3->setUsumodifId(0);
                                        $TranssportsbookApi3->setValor($creditAmount);
                                        $TranssportsbookApi3->setUsuarioId($UsuarioMandante->usuarioMandante);

                                        $TranssportsbookApi3->setMandante($UsuarioMandante->mandante);
                                        $TranssportsbookApi3->setProveedorId(0);
                                        $TranssportsbookApi3->setIdentificador(0);

                                        $TranssportsbookApiMySqlDAO2 = new TranssportsbookApiMySqlDAO();

                                        $TranssportsbookApiMySqlDAO2->insert($TranssportsbookApi3);
                                        $TranssportsbookApiMySqlDAO2->getTransaction()->commit();


                                        switch ($responseQuiskXML->Status) {
                                            case "SUCCESS":
                                                $Balance = 10000;

                                                break;
                                            case "BAD_REQUEST":
                                                throw new Exception("BAD_REQUEST", 50002);

                                                break;
                                            case "INSUFFICIENT_FUNDS":
                                                throw new Exception("INSUFFICIENT_FUNDS", 50003);

                                                break;
                                            case "INVALID_BET_NUMBER":
                                                throw new Exception("INVALID_BET_NUMBER", 50004);

                                                break;
                                            case "INVALID_MERCHANT":
                                                throw new Exception("INVALID_MERCHANT", 50005);

                                                break;
                                            case "INVALID_TOKEN":
                                                throw new Exception("INVALID_TOKEN", 50006);

                                                break;
                                            case "USER_NOT_FLAGGED_BETTING":
                                                throw new Exception("USER_NOT_FLAGGED_BETTING", 50007);

                                                break;
                                            default:
                                                throw new Exception($responseQuiskXML->Status, 50007);

                                                break;
                                        }
                                    } else {
                                        $TranssportsbookApi3 = new TranssportsbookApi();
                                        $TranssportsbookApi3->setTranssportId($ItTicketEnc->getItTicketId());
                                        $TranssportsbookApi3->setTransaccionId('QUISK' . $transsportsbookApi->getTransaccionId());
                                        $TranssportsbookApi3->setTipo('QUISK');
                                        $TranssportsbookApi3->setTValue($GetResultsRequest->asXML() . $responseQuisk);
                                        $TranssportsbookApi3->setUsucreaId(0);
                                        $TranssportsbookApi3->setUsumodifId(0);
                                        $TranssportsbookApi3->setValor($creditAmount);
                                        $TranssportsbookApi3->setUsuarioId($UsuarioMandante->usuarioMandante);

                                        $TranssportsbookApi3->setMandante($UsuarioMandante->mandante);
                                        $TranssportsbookApi3->setProveedorId(0);
                                        $TranssportsbookApi3->setIdentificador(0);

                                        $TranssportsbookApiMySqlDAO2 = new TranssportsbookApiMySqlDAO();

                                        $TranssportsbookApiMySqlDAO2->insert($TranssportsbookApi3);
                                        $TranssportsbookApiMySqlDAO2->getTransaction()->commit();


                                        throw new Exception("QuiskNoRESPONDE", 50008);
                                    }
                                } else {
                                    $Usuario->creditWin($creditAmount - $impuesto, $Transaction);


                                    $UsuarioHistorial = new UsuarioHistorial();
                                    $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                                    $UsuarioHistorial->setDescripcion('');
                                    $UsuarioHistorial->setMovimiento('E');
                                    $UsuarioHistorial->setUsucreaId(0);
                                    $UsuarioHistorial->setUsumodifId(0);
                                    $UsuarioHistorial->setTipo(20);
                                    $UsuarioHistorial->setValor($ItTransaccion->getValor() - $impuesto);
                                    $UsuarioHistorial->setExternoId($ItTicketEnc->ticketId);

                                    $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                                    $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

                                    $SkeepRows = 0;
                                    $OrderedItem = 1;
                                    $MaxRows = 1000000000;

                                    $rules = [];

                                    array_push($rules, array("field" => "it_ticket_enc_info1.ticket_id", "data" => $ItTicketEnc->ticketId, "op" => "eq"));
                                    array_push($rules, array("field" => "it_ticket_enc_info1.tipo", "data" => "TORNEO", "op" => "eq"));

                                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                                    $json2 = json_encode($filtro);

                                    $ItTicketEncInfo1 = new ItTicketEncInfo1();

                                    $tickets = $ItTicketEncInfo1->getTicketsCustom(" it_ticket_enc_info1.*,it_ticket_enc.vlr_apuesta,it_ticket_enc.fecha_cierre,it_ticket_enc.fecha_crea ", "it_ticket_enc_info1.it_ticket2_id", "asc", $SkeepRows, $MaxRows, $json2, true);

                                    $tickets = json_decode($tickets);


                                    if (intval($tickets->count[0]->{".count"}) > 0) {
                                        $usutorneoId = $tickets->data[0]->{'it_ticket_enc_info1.valor'};
                                        $itTicket2Id = $tickets->data[0]->{'it_ticket_enc_info1.it_ticket2_id'};
                                        $creditosConvert = $tickets->data[0]->{'it_ticket_enc_info1.valor2'};

                                        $UsuarioTorneo = new UsuarioTorneo($usutorneoId);

                                        $UsuarioTorneo->setValor($UsuarioTorneo->getValor() - $creditosConvert);
                                        $UsuarioTorneo->setValorBase($UsuarioTorneo->getValorBase() - $ItTicketEnc->getVlrApuesta());

                                        $UsuarioTorneoMysqlDAO = new UsuarioTorneoMySqlDAO($Transaction);

                                        $usutorneoId = $UsuarioTorneoMysqlDAO->update($UsuarioTorneo);

                                        $BonoInterno = new BonoInterno();

                                        $sql = "DELETE FROM it_ticket_enc_info1 where it_ticket2_id='" . $itTicket2Id . "'";

                                        $dataDelete = $BonoInterno->execQuery($Transaction, $sql);
                                    }
                                }

                                break;

                            case "NEWDEBIT":


                                if ($ItTicketEnc->getWallet() == 1) {
                                    throw new Exception("No tiene fondos suficientes", "20001");

                                    $Registro = new Registro("", $Usuario->usuarioId);
                                    $UsuarioOtrainfo = new UsuarioOtrainfo($Usuario->usuarioId);
                                    $AuthoriseBetRequest = new SimpleXMLElement("<AuthoriseBetRequest></AuthoriseBetRequest>");

                                    $ExternalAccountId = $AuthoriseBetRequest->addChild('ExternalAccountId', $Usuario->usuarioId);
                                    //$ExternalAccountId->('Value', $usuario_id);

                                    $MobileNumber = $AuthoriseBetRequest->addChild('MobileNumber', $Registro->celular);

                                    $TaxId = $AuthoriseBetRequest->addChild('TaxId', $Registro->cedula);

                                    $ProviderUserId = $AuthoriseBetRequest->addChild('ProviderUserId', $this->QuiskProviderUserId);

                                    $ProviderPassword = $AuthoriseBetRequest->addChild('ProviderPassword', sha1($this->QuiskProviderPassword));

                                    $MerchantId = $AuthoriseBetRequest->addChild('MerchantId', $this->QuiskMerchantId);

                                    $TokenId = $AuthoriseBetRequest->addChild('TokenId', $Usuario->tokenQuisk);
                                    $BettingNumber = $AuthoriseBetRequest->addChild('BettingNumber', $transsportsbookApi->getIdentificador());
                                    $BetAmount = $AuthoriseBetRequest->addChild('BetAmount', $debitAmount);
                                    $BetAmount = $AuthoriseBetRequest->addChild('TransDateTime', date('Y-m-d H:i:s'));


                                    $responseQuisk = $this->createRequestQuisk($this->QuiskAuthoriseBetURL, $AuthoriseBetRequest->asXML());

                                    $responseQuiskXML = simplexml_load_string($responseQuisk);

                                    if ($responseQuiskXML) {
                                        switch ($responseQuiskXML->Status) {
                                            case "SUCCESS":

                                                break;
                                            case "BAD_REQUEST":
                                                throw new Exception("BAD_REQUEST", 50002);

                                                break;
                                            case "INSUFFICIENT_FUNDS":
                                                throw new Exception("INSUFFICIENT_FUNDS", 50003);

                                                break;
                                            case "INVALID_BET_NUMBER":
                                                throw new Exception("INVALID_BET_NUMBER", 50004);

                                                break;
                                            case "INVALID_MERCHANT":
                                                throw new Exception("INVALID_MERCHANT", 50005);

                                                break;
                                            case "INVALID_TOKEN":
                                                throw new Exception("INVALID_TOKEN", 50006);

                                                break;
                                            case "USER_NOT_FLAGGED_BETTING":
                                                throw new Exception("USER_NOT_FLAGGED_BETTING", 50007);

                                                break;
                                        }
                                    } else {
                                        throw new Exception("QuiskNoRESPONDE", 50008);
                                    }
                                } else {
                                    $impuesto = 0;
                                    $creditAmount2 = $creditAmount;
                                    if ($ItTicketEnc->getImpuesto() != '' &&
                                        $ItTicketEnc->getImpuesto() != null &&
                                        $ItTicketEnc->getImpuesto() > 0) {
                                        $impuesto = $ItTicketEnc->getImpuesto();
                                        $creditAmount2 = $creditAmount2 - $impuesto;

                                        $ItTicketEnc->setImpuesto(0);
                                    }

                                    $Usuario->debit($creditAmount2, $Transaction);


                                    $UsuarioHistorial = new UsuarioHistorial();
                                    $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                                    $UsuarioHistorial->setDescripcion('');
                                    $UsuarioHistorial->setMovimiento('S');
                                    $UsuarioHistorial->setUsucreaId(0);
                                    $UsuarioHistorial->setUsumodifId(0);
                                    $UsuarioHistorial->setTipo(20);
                                    $UsuarioHistorial->setValor($ItTransaccion->getValor());
                                    $UsuarioHistorial->setExternoId($ItTicketEnc->ticketId);

                                    $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                                    $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);
                                }


                                break;

                            case "NEWCREDIT":

                                if ($ItTicketEnc->getWallet() == 1) {
                                    throw new Exception("No tiene fondos suficientes", "20001");
                                }

                                $Usuario->creditWin($creditAmount, $Transaction);

                                $UsuarioHistorial = new UsuarioHistorial();
                                $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                                $UsuarioHistorial->setDescripcion('');
                                $UsuarioHistorial->setMovimiento('E');
                                $UsuarioHistorial->setUsucreaId(0);
                                $UsuarioHistorial->setUsumodifId(0);
                                $UsuarioHistorial->setTipo(20);
                                $UsuarioHistorial->setValor($ItTransaccion->getValor());
                                $UsuarioHistorial->setExternoId($ItTicketEnc->ticketId);

                                $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                                $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);
                                break;

                            case "STAKEDECREASE":

                                /* Obtenemos los valores declarados ateriormente de ItTicketEnc */
                                $valorApuesta = $valorOrgApuesta;
                                $saldoCreditos = $valorOrgCreditos;
                                $saldoCreditosBase = $valorOrgCreditosBase;

                                /* Obtenemos el porcentaje base de la apuesta */
                                $porcentajeBase = 0;
                                if ($saldoCreditosBase != 0) {
                                    $porcentajeBase = ($saldoCreditosBase / $valorApuesta) * 100;
                                }

                                /* Obtenemos el porcentaje creditos de la apuesta */
                                $porcentajeCreditos = 0;
                                if ($saldoCreditos != 0) {
                                    $porcentajeCreditos = ($saldoCreditos / $valorApuesta) * 100;
                                }

                                /* Obtenemos el valor base aplicando los porcentajes a creditAmount */
                                $valorBase = ($porcentajeBase / 100) * $creditAmount;

                                /* Obtenemos el valor creditos aplicando los porcentajes a creditAmount */
                                $valorCreditos = ($porcentajeCreditos / 100) * $creditAmount;

                                $Usuario->creditWin3($valorCreditos, $Transaction, false, $valorBase);

                                $UsuarioHistorial = new UsuarioHistorial();
                                $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                                $UsuarioHistorial->setDescripcion('');
                                $UsuarioHistorial->setMovimiento('E');
                                $UsuarioHistorial->setUsucreaId(0);
                                $UsuarioHistorial->setUsumodifId(0);
                                $UsuarioHistorial->setTipo(25);
                                $UsuarioHistorial->setValor($ItTransaccion->getValor());
                                $UsuarioHistorial->setExternoId($ItTicketEnc->ticketId);

                                $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                                $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);
                                break;

                            default:

                                break;
                        }

                        break;

                    case "MAQUINAANONIMA":


                        $PuntoVenta = new PuntoVenta("", $UsuarioMandante->usuarioMandante);

                        switch ($tipoTransaccion) {
                            case "LOSS":
                                $ItTicketEnc->setPremiado("N");
                                $ItTicketEnc->setVlrPremio(0);
                                $ItTicketEnc->setPremioPagado("N");
                                $ItTicketEnc->setFechaCierre(date('Y-m-d H:i:s', time()));

                                break;

                            case "WIN":
                                $ItTicketEnc->setVlrPremio($creditAmount);
                                if ($ItTicketEnc->getVlrPremio() > 0) {
                                    $ItTicketEnc->setPremiado("S");
                                    //$ItTicketEnc->setFechaPago(date('Y-m-d H:i:s', time()));
                                    $ItTicketEnc->setFechaCierre(date('Y-m-d H:i:s', time()));
                                    $ItTicketEnc->setPremioPagado("N");
                                }
                                break;

                            case "CASHOUT":
                                $ItTicketEnc->setVlrPremio($creditAmount);
                                $ItTicketEnc->setPremiado("S");
                                //$ItTicketEnc->setFechaPago(date('Y-m-d H:i:s', time()));
                                $ItTicketEnc->setPremioPagado("N");
                                $ItTicketEnc->setFechaCierre(date('Y-m-d H:i:s', time()));


                                break;

                            case "NEWDEBIT":
                                $ItTicketEnc->setVlrPremio($ItTicketEnc->getVlrPremio() - $creditAmount);

                                if ($ItTicketEnc->getVlrPremio() == 0) {
                                    $ItTicketEnc->setPremiado("N");
                                    $ItTicketEnc->setPremioPagado("N");
                                } else {
                                }
                                break;

                            case "NEWCREDIT":
                                $ItTicketEnc->setVlrPremio($ItTicketEnc->getVlrPremio() + $creditAmount);

                                $ItTicketEnc->setPremiado("S");
                                // $ItTicketEnc->setFechaPago(date('Y-m-d H:i:s', time()));
                                $ItTicketEnc->setPremioPagado("N");


                                break;

                            case "STAKEDECREASE":

                                if ($ticket->BetStatus == "R" or $ticket->BetStatus == "W") {
                                } else {
                                    $ItTicketEnc->setFechaCierre(date('Y-m-d H:i:s', time()));
                                }
                                $ItTicketEnc->setVlrApuesta($ItTicketEnc->getVlrApuesta() - $creditAmount);


                                break;

                            default:

                                break;
                        }


                        switch ($tipoTransaccion) {
                            case "LOSS":

                                break;

                            case "WIN":
                                //$PuntoVenta->setBalanceCreditosBase($creditAmount,$Transaction);
                                break;

                            case "CASHOUT":
                                //$PuntoVenta->setBalanceCreditosBase($creditAmount,$Transaction);

                                break;

                            case "NEWDEBIT":
                                //$PuntoVenta->setBalanceCreditosBase(-$creditAmount,$Transaction);

                                break;

                            case "NEWCREDIT":
                                //$PuntoVenta->setBalanceCreditosBase($creditAmount,$Transaction);

                                break;

                            case "STAKEDECREASE":

                                //$PuntoVenta->setBalanceCreditosBase($creditAmount,$Transaction);

                                break;

                            default:

                                break;
                        }


                        break;


                    case "PUNTOVENTA":


                        switch ($tipoTransaccion) {
                            case "WIN":

                                if ($Usuario->paisId == '2') {
                                    $BonoInterno = new BonoInterno();

                                    $sql = "INSERT INTO cheque (nro_cheque,pais_id,origen,documento_id,ticket_id,mandante) SELECT CASE WHEN max(a.nro_cheque)+1 IS NULL THEN 1 ELSE max(a.nro_cheque)+1 END," . $Usuario->paisId . ",'TK'," . $ItTicketEnc->ticketId . "," . $ItTicketEnc->ticketId . "," . $Usuario->mandante . " from cheque a where a.mandante=" . $Usuario->mandante . " and a.pais_id=" . $Usuario->paisId;

                                    $data = $BonoInterno->execQuery($Transaction, $sql);
                                }


                                break;

                            case "CASHOUT":


                                if ($Usuario->paisId == '2') {
                                    $BonoInterno = new BonoInterno();

                                    $sql = "INSERT INTO cheque (nro_cheque,pais_id,origen,documento_id,ticket_id,mandante) SELECT CASE WHEN max(a.nro_cheque)+1 IS NULL THEN 1 ELSE max(a.nro_cheque)+1 END," . $Usuario->paisId . ",'TK'," . $ItTicketEnc->ticketId . "," . $ItTicketEnc->ticketId . "," . $Usuario->mandante . " from cheque a where a.mandante=" . $Usuario->mandante . " and a.pais_id=" . $Usuario->paisId;

                                    $data = $BonoInterno->execQuery($Transaction, $sql);
                                }


                                break;


                            case "STAKEDECREASE":

                                $FlujoCaja = new FlujoCaja();
                                $FlujoCaja->setFechaCrea(date('Y-m-d'));
                                $FlujoCaja->setHoraCrea(date('H:i'));
                                $FlujoCaja->setUsucreaId($Usuario->usuarioId);
                                $FlujoCaja->setTicketId($ItTransaccion->getTicketId());
                                $FlujoCaja->setTipomovId('E');
                                $FlujoCaja->setValor('-' . $ItTransaccion->getValor());
                                $FlujoCaja->setRecargaId(0);
                                $FlujoCaja->setMandante($Usuario->mandante);
                                $FlujoCaja->setValorForma1($ItTransaccion->getValor());

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


                                $PuntoVenta = new PuntoVenta("", $Usuario->puntoventaId);
                                $rowsUpdate = $PuntoVenta->setBalanceCreditosBase($ItTransaccion->getValor(), $Transaction);

                                if ($rowsUpdate > 0) {
                                    $UsuarioHistorial = new UsuarioHistorial();
                                    $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                                    $UsuarioHistorial->setDescripcion('');
                                    $UsuarioHistorial->setMovimiento('S');
                                    $UsuarioHistorial->setUsucreaId(0);
                                    $UsuarioHistorial->setUsumodifId(0);
                                    $UsuarioHistorial->setTipo(20);
                                    $UsuarioHistorial->setValor($ItTransaccion->getValor());
                                    $UsuarioHistorial->setExternoId($ItTicketEnc->ticketId);

                                    $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                                    $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');
                                } else {
                                    throw new Exception("You have reached your gaming balance limit. Please withdraw to place bet", 100030);
                                }


                                break;

                            default:

                                break;
                        }


                        break;

                    case "CAJERO":


                        switch ($tipoTransaccion) {
                            case "WIN":

                                if ($Usuario->paisId == '2') {
                                    $BonoInterno = new BonoInterno();

                                    $sql = "INSERT INTO cheque (nro_cheque,pais_id,origen,documento_id,ticket_id,mandante) SELECT CASE WHEN max(a.nro_cheque)+1 IS NULL THEN 1 ELSE max(a.nro_cheque)+1 END," . $Usuario->paisId . ",'TK'," . $ItTicketEnc->ticketId . "," . $ItTicketEnc->ticketId . "," . $Usuario->mandante . " from cheque a where a.mandante=" . $Usuario->mandante . " and a.pais_id=" . $Usuario->paisId;

                                    $data = $BonoInterno->execQuery($Transaction, $sql);
                                }


                                break;

                            case "CASHOUT":


                                if ($Usuario->paisId == '2') {
                                    $BonoInterno = new BonoInterno();

                                    $sql = "INSERT INTO cheque (nro_cheque,pais_id,origen,documento_id,ticket_id,mandante) SELECT CASE WHEN max(a.nro_cheque)+1 IS NULL THEN 1 ELSE max(a.nro_cheque)+1 END," . $Usuario->paisId . ",'TK'," . $ItTicketEnc->ticketId . "," . $ItTicketEnc->ticketId . "," . $Usuario->mandante . " from cheque a where a.mandante=" . $Usuario->mandante . " and a.pais_id=" . $Usuario->paisId;

                                    $data = $BonoInterno->execQuery($Transaction, $sql);
                                }


                                break;


                            case "STAKEDECREASE":

                                $FlujoCaja = new FlujoCaja();
                                $FlujoCaja->setFechaCrea(date('Y-m-d'));
                                $FlujoCaja->setHoraCrea(date('H:i'));
                                $FlujoCaja->setUsucreaId($Usuario->usuarioId);
                                $FlujoCaja->setTicketId($ItTransaccion->getTicketId());
                                $FlujoCaja->setTipomovId('E');
                                $FlujoCaja->setValor('-' . $ItTransaccion->getValor());
                                $FlujoCaja->setRecargaId(0);
                                $FlujoCaja->setMandante($Usuario->mandante);
                                $FlujoCaja->setValorForma1($ItTransaccion->getValor());

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


                                $PuntoVenta = new PuntoVenta("", $Usuario->puntoventaId);
                                $rowsUpdate = $PuntoVenta->setBalanceCreditosBase($ItTransaccion->getValor(), $Transaction);

                                if ($rowsUpdate > 0) {
                                    $UsuarioHistorial = new UsuarioHistorial();
                                    $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                                    $UsuarioHistorial->setDescripcion('');
                                    $UsuarioHistorial->setMovimiento('S');
                                    $UsuarioHistorial->setUsucreaId(0);
                                    $UsuarioHistorial->setUsumodifId(0);
                                    $UsuarioHistorial->setTipo(20);
                                    $UsuarioHistorial->setValor($ItTransaccion->getValor());
                                    $UsuarioHistorial->setExternoId($ItTicketEnc->ticketId);

                                    $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                                    $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');
                                } else {
                                    throw new Exception("You have reached your gaming balance limit. Please withdraw to place bet", 100030);
                                }


                                break;

                            default:

                                break;
                        }


                        break;
                }
            } else {
                try {
                    $ProdMandanteTipo = new ProdMandanteTipo("SPORTSBOOK", $Mandante->mandante);
                    $data = array(
                        //"site" => $ProdMandanteTipo->siteId,
                        "sign" => $ProdMandanteTipo->siteKey,
                        "token" => $UsuarioMandante->tokenExterno,
                        "gamecode" => $ProductoMandante->prodmandanteId,
                        "amount" => $creditAmount,
                        "roundid" => $transsportsbookApi->getIdentificador(),
                        "transactionid" => 0
                    );

                    $TranssportsbookApiMandante = new TranssportsbookApiMandante();
                    $TranssportsbookApiMandante->setTransaccionId('');
                    $TranssportsbookApiMandante->setTipo("WIN");
                    $TranssportsbookApiMandante->setProveedorId($Producto->getProveedorId());
                    $TranssportsbookApiMandante->setTValue(json_encode($data));
                    $TranssportsbookApiMandante->setUsucreaId(0);
                    $TranssportsbookApiMandante->setUsumodifId(0);
                    $TranssportsbookApiMandante->setValor($creditAmount);
                    $TranssportsbookApiMandante->setIdentificador($transsportsbookApi->getIdentificador());
                    $TranssportsbookApiMandante->setProductoId($ProductoMandante->prodmandanteId);
                    $TranssportsbookApiMandante->setUsuarioId($UsuarioMandante->getUsumandanteId());
                    $TranssportsbookApiMandante->settranssportapiId(0);

                    $TranssportsbookApiMandanteMySqlDAO = new TranssportsbookApiMandanteMySqlDAO();
                    $transapimandanteId = $TranssportsbookApiMandanteMySqlDAO->insert($TranssportsbookApiMandante);
                    $TranssportsbookApiMandanteMySqlDAO->getTransaction()->commit();

                    $data["transactionid"] = $transapimandanteId;
                    $TranssportsbookApiMandante->setTValue(json_encode($data));

                    $result = $Mandante->sendRequest($ProdMandanteTipo->urlApi . "/credit", "POST", $data);
                    /*$result = array(
                    "error" => 'false',
                    "code" => 0,
                    "balance" => 1,
                    "currency" => 'PEN',
                    "transactionid" => 1
                );*/


                    $result = json_decode(json_encode($result));

                    $TranssportsbookApiMandante->setRespuesta(json_encode($result));
                    $TranssportsbookApiMandante->setRespuestaCodigo(0);
                    $TranssportsbookApiMandante->setTransaccionId($result->transactionid);

                    $TranssportsbookApiMandanteMySqlDAO = new TranssportsbookApiMandanteMySqlDAO();
                    $TranssportsbookApiMandanteMySqlDAO->update($TranssportsbookApiMandante);
                    $TranssportsbookApiMandanteMySqlDAO->getTransaction()->commit();


                    if ($result == "") {
                        throw new Exception("La solicitud al mandante fue vacia ", "50002");
                    }


                    $balance = $result->balance;
                    $transactionIdMandante = $result->transactionid;
                    $error = $result->error;
                    $code = $result->code;


                    if ($error == "" || $error == 'true') {
                        throw new Exception("Error en mandante ", "M" . $code);
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

                    $TranssportsbookApiMandante->setRespuestaCodigo($codeException);
                    $TranssportsbookApiMandanteMySqlDAO = new TranssportsbookApiMandanteMySqlDAO();
                    $TranssportsbookApiMandanteMySqlDAO->update($TranssportsbookApiMandante);
                    $TranssportsbookApiMandanteMySqlDAO->getTransaction()->commit();
                }
            }

            $estado_ticket = "I";


            if ($ticket->BetStatus == "R" or $ticket->BetStatus == "W") {
                $estado_ticket = "A";
            }

            $ItTicketEnc->setEstado($estado_ticket);


            $ItTicketEncMySqlDAO->update($ItTicketEnc);


            //$ItTicketEnc->update($Transaction);


            // COMMIT de la transacción
            $Transaction->commit();

            if ($Mandante->propio == "S") {
                /*  Consultamos de nuevo el usuario para obtener el saldo  */
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);


                switch ($UsuarioPerfil->getPerfilId()) {
                    case "USUONLINE":

                        $Balance = $Usuario->getBalance();

                        break;

                    case "MAQUINAANONIMA":


                        /*$PuntoVenta = new PuntoVenta("",$UsuarioMandante->usuarioMandante);

                    $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                    $SaldoJuego = $PuntoVenta->getCreditosBase();

                    $Balance = $SaldoJuego;*/

                        $Balance = $Usuario->getBalance();

                        break;

                    case "PUNTOVENTA":

                        $PuntoVenta = new PuntoVenta("", $UsuarioMandante->usuarioMandante);

                        $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                        $SaldoJuego = $PuntoVenta->getCreditosBase();

                        $Balance = $SaldoJuego + $SaldoRecargas;


                        break;
                }
                try {
                    /*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
                    $UsuarioToken = new UsuarioToken("", '1', $UsuarioMandante->getUsumandanteId());

                    /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
                    $data = $UsuarioMandante->getWSProfileSite($UsuarioToken->getRequestId());
                    $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                    $WebsocketUsuario->sendWSMessage();
                    //$UsuarioToken = new UsuarioToken("", $Producto->getProveedorId(), $UsuarioMandante->getUsumandanteId());
                } catch (Exception $e) {
                }
            }
            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $transsportsbookApi->setRespuestaCodigo("OK");
            $transsportsbookApi->setRespuesta('');
            $TranssportsbookApiMySqlDAO = new TranssportsbookApiMySqlDAO();
            $TranssportsbookApiMySqlDAO->insert($transsportsbookApi);

            if ($TranssportsbookApiMandante != "" && $TranssportsbookApiMandante != null) {
                $TranssportsbookApiMandante->settranssportapiId($transsportsbookApi->transsportapiId);
                $TranssportsbookApiMandanteMySqlDAO = new TranssportsbookApiMandanteMySqlDAO($TranssportsbookApiMySqlDAO->getTransaction());
                $TranssportsbookApiMandanteMySqlDAO->update($TranssportsbookApiMandante);
            }

            $TranssportsbookApiMySqlDAO->getTransaction()->commit();
            $useragent = $_SERVER['HTTP_USER_AGENT'];
            $jsonServer = json_encode($_SERVER);
            $serverCodif = base64_encode($jsonServer);


            $ismobile = '';

            if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match(
                    '/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4)
                )) {
                $ismobile = '1';
            }
//Detect special conditions devices
            $iPod = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");
            $iPhone = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
            $iPad = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
            $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
            $webOS = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");


//do something with this information
            if ($iPod || $iPhone) {
                $ismobile = '1';
            } elseif ($iPad) {
                $ismobile = '1';
            } elseif ($Android) {
                $ismobile = '1';
            }

            exec("php -f " . __DIR__ . "/../integrations/crm/AgregarCrm.php " . $Usuario->usuarioId . " " . "WINSPORTSBOOKCRM" . " " . $ItTicketEnc->itTicketId . " " . $serverCodif . " " . $ismobile . " > /dev/null &");
            if ($Usuario->billeteraId == 2) {
                $Balance = 1000000;
            }
            if ($tipoTransaccion == 'LOSS' || $tipoTransaccion == 'WIN') {
                $detalles2 = array(
                    "JuegosCasino" => array(
                        array(
                            "Id" => 2
                        )

                    ),
                    "ValorApuesta" => 0
                );
                $BonoInterno = new BonoInterno();
                //$respuesta2 = $BonoInterno->verificarBonoRollower($Usuario->usuarioId, $detalles2, 'SPORT', $ItTicketEnc->ticketId);

            }


            $respuesta->usuarioId = $UsuarioMandante->usuarioMandante;
            $respuesta->moneda = $UsuarioMandante->moneda;
            $respuesta->usuario = "Usuario" . $UsuarioMandante->usuarioMandante;
            $respuesta->saldo = $Balance;
            $respuesta->transaccionId = $ItTransaccion->itCuentatransId;
            $respuesta->transsportsbookApi = $transsportsbookApi;

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
     * Procesa la asignación de un bono a un usuario.
     *
     * @param object $UsuarioMandante    Información del usuario mandante.
     * @param object $Producto           Información del producto asociado.
     * @param object $transsportsbookApi Objeto que contiene los datos de la transacciónAPI.
     * @param string $BonusId            Identificador del bono.
     * @param string $BonusPlanId        Identificador del plan de bono.
     * @param object $ticket             Información del ticket asociado.
     *
     * @return object Respuesta con los detalles de la transacción, saldo y otros datos relevantes.
     *
     * @throws Exception Si ocurre algún error durante el procesamiento del bono.
     */
    public function AwardBonus($UsuarioMandante, $Producto, $transsportsbookApi, $BonusId, $BonusPlanId, $ticket)
    {
        try {
            $respuesta = json_decode(json_encode(array(
                "usuarioId" => "",
                "moneda" => "",
                "usuario" => "",
                "transaccionId" => "",
                "saldo" => "",
                "transsportsbookApi" => ""
            )));

            $BonusAmount = $transsportsbookApi->getValor();


            /*  Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego  */
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            /*  Agregamos Elementos a la Transaccion API  */
            $transsportsbookApi->setProductoId($ProductoMandante->prodmandanteId);
            $transsportsbookApi->setUsuarioId($UsuarioMandante->getUsumandanteId());
            $transsportsbookApi->setMandante($UsuarioMandante->getMandante());


            /*  Verificamos que la transaccionId no se haya procesado antes  */
            if ($transsportsbookApi->existsTransaccionIdAndProveedor("OK")) {
                /*  Si la transaccionId ha sido procesada, reportamos el error  */
                throw new Exception("Transaccion ya procesada", "10001");
            }


            /*  Consultamos de nuevo el usuario para obtener el saldo  */
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);


            if ($UsuarioPerfil->perfilId != 'USUONLINE') {
                throw new Exception("No existen bonos", "300000");
            }


            $UsuarioMySqlDAO = new UsuarioMySqlDAO();
            $Transaction = $UsuarioMySqlDAO->getTransaction();

            $rules = [];


            array_push($rules, array("field" => "bono_detalle.tipo", "data" => "BONUSPLANIDALTENAR", "op" => "eq"));
            array_push($rules, array("field" => "bono_detalle.valor", "data" => trim($BonusPlanId), "op" => "eq"));
            array_push($rules, array("field" => "usuario_bono.externo_id", "data" => trim($BonusId), "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");

            $json = json_encode($filtro);

            $UsuarioBono = new UsuarioBono();
            $data = $UsuarioBono->getUsuarioBonosCustom("usuario_bono.*", "usuario_bono.usubono_id", 'asc', 0, 1000, $json, true, '', 'BONUSPLANIDALTENAR');
            $data = json_decode($data);


            /*CHECK mapear los codigos de error*/

            if (intval($data->count[0]->{".count"}) == 0) {
                $rules = array();
                array_push($rules, array("field" => "bono_detalle.tipo", "data" => "BONUSPLANIDALTENAR", "op" => "eq"));
                array_push($rules, array("field" => "bono_detalle.valor", "data" => $BonusPlanId, "op" => "eq"));
                //array_push($rules, array("field" => "bono_interno.estado", "data" => 'A', "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");


                $SkeepRows = 0;
                $OrderedItem = 1;
                $MaxRows = 1000000000;


                $json = json_encode($filtro);

                $BonoDetalle = new BonoDetalle();
                $bonodetalles = $BonoDetalle->getBonoDetallesCustom("bono_detalle.*,bono_interno.* ", "bono_interno.bono_id", "desc", $SkeepRows, $MaxRows, $json, true);

                $bonodetalles = json_decode($bonodetalles);

                if (intval($bonodetalles->count[0]->{".count"}) == 0) {
                    throw new Exception("No existen bonos", "300000");
                }
                $BonoId = $bonodetalles->data[0]->{'bono_interno.bono_id'};


                $UsuarioBono = new UsuarioBono();

                $UsuarioBono->setUsuarioId($Usuario->usuarioId);
                $UsuarioBono->setBonoId($BonoId);
                $UsuarioBono->setValor($BonusAmount);
                $UsuarioBono->setValorBono($BonusAmount);
                $UsuarioBono->setValorBase($BonusAmount);
                $UsuarioBono->setEstado('R');
                $UsuarioBono->setErrorId('0');
                $UsuarioBono->setIdExterno($BonusId);
                $UsuarioBono->setMandante($Usuario->mandante);
                $UsuarioBono->setUsucreaId('0');
                $UsuarioBono->setUsumodifId('0');
                $UsuarioBono->setApostado('0');
                $UsuarioBono->setVersion('2');
                $UsuarioBono->setRollowerRequerido('0');
                $UsuarioBono->setCodigo('');
                $UsuarioBono->setExternoId($BonusId);
                $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($Transaction);

                $usubonoId = $UsuarioBonoMysqlDAO->insert($UsuarioBono);


                $BonoLog = new BonoLog();
                $BonoLog->setUsuarioId($Usuario->usuarioId);
                $BonoLog->setTipo('AF');
                $BonoLog->setValor($BonusAmount);
                $BonoLog->setFechaCrea(date('Y-m-d H:i:s'));
                $BonoLog->setFechaCierre(date('Y-m-d H:i:s'));
                $BonoLog->setEstado('L');
                $BonoLog->setErrorId(0);
                $BonoLog->setIdExterno($BonusId);
                $BonoLog->setMandante($Usuario->mandante);
                $BonoLog->setTipobonoId(0);
                $BonoLog->setTiposaldoId('1');


                $BonoLogMySqlDAO = new BonoLogMySqlDAO($Transaction);
                $BonoLogMySqlDAO->insert($BonoLog);

                try {
                    $tValue = $transsportsbookApi->getTValue();

                    $xmlObject = simplexml_load_string($tValue);

                    $bonusTypeId = (int)$xmlObject->Method->Params->BonusTypeId['Value'];

                    if ($bonusTypeId === 6) {
                        $Usuario->creditWin($BonusAmount, $Transaction);
                    } else {
                        $Usuario->credit($BonusAmount, $Transaction);
                    }
                } catch (Exception $e) {
                }


                $UsuarioHistorial = new UsuarioHistorial();
                $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                $UsuarioHistorial->setDescripcion('');
                $UsuarioHistorial->setMovimiento('E');
                $UsuarioHistorial->setUsucreaId(0);
                $UsuarioHistorial->setUsumodifId(0);
                $UsuarioHistorial->setTipo(20);
                $UsuarioHistorial->setValor($BonusAmount);
                $UsuarioHistorial->setExternoId($usubonoId);

                $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);
            } else {
                $UsuarioBono = new UsuarioBono($data->data[0]->{'usuario_bono.usubono_id'});

                if ($UsuarioBono->estado == 'R') {
                    throw new Exception("No se pudo procesar el bono porque ya fue procesado previamente o su estado actual no admite la liberacion.", "300001");
                } else {
                    $UsuarioBono->setValor($BonusAmount);
                    $UsuarioBono->setEstado('R');
                    $UsuarioBono->setIdExterno($BonusId);
                    $UsuarioBono->setExternoId($BonusId);

                    $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($Transaction);

                    $usubonoId = $UsuarioBonoMysqlDAO->update($UsuarioBono);


                    $BonoLog = new BonoLog();
                    $BonoLog->setUsuarioId($Usuario->usuarioId);
                    $BonoLog->setTipo('AF');
                    $BonoLog->setValor($BonusAmount);
                    $BonoLog->setFechaCrea(date('Y-m-d H:i:s'));
                    $BonoLog->setFechaCierre(date('Y-m-d H:i:s'));
                    $BonoLog->setEstado('L');
                    $BonoLog->setErrorId(0);
                    $BonoLog->setIdExterno($BonusId);
                    $BonoLog->setMandante($Usuario->mandante);
                    $BonoLog->setTipobonoId(0);
                    $BonoLog->setTiposaldoId('1');


                    $BonoLogMySqlDAO = new BonoLogMySqlDAO($Transaction);
                    $BonoLogMySqlDAO->insert($BonoLog);


                    $Usuario->credit($BonusAmount, $Transaction);

                    $UsuarioHistorial = new UsuarioHistorial();
                    $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                    $UsuarioHistorial->setDescripcion('');
                    $UsuarioHistorial->setMovimiento('E');
                    $UsuarioHistorial->setUsucreaId(0);
                    $UsuarioHistorial->setUsumodifId(0);
                    $UsuarioHistorial->setTipo(20);
                    $UsuarioHistorial->setValor($BonusAmount);
                    $UsuarioHistorial->setExternoId($UsuarioBono->usubonoId);

                    $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                    $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);
                }
            }

            $transsportsbookApi->setTranssportId('0');

            /*  Obtenemos el tipo de transacción dependiendo a betTypeID y si se le suma los creditos o no */
            $sumaCreditos = false;
            $tipoTransaccion = $ticket->TipoTransaccion;


            /*  ESTADOS DE LAS APUESTAS DE ITAINMENT
       C = Open (Abierto)
       S = Won (Gano)
       N = Lost (Perdio)
       A = Void (No Accion)
       R = Pending (Pendiente)
       W = Waiting (En Espera)
       J = Rejected (Rechazada)
       M = RejectedByMTS (Rechazada por Regla)
       T = Cashout (Retiro Voluntario)
        */

            /*  Creamos el log de la transaccion juego para auditoria  */
            $ItTransaccion = new ItTransaccion();

            $ItTransaccion->setTipo($tipoTransaccion);
            $ItTransaccion->setTicketId('#');
            $ItTransaccion->setGameReference('#');
            $ItTransaccion->setUsuarioId($UsuarioMandante->usuarioMandante);
            $ItTransaccion->setBetStatus('#');
            $ItTransaccion->setValor($BonusAmount);
            $ItTransaccion->setTransaccionId($transsportsbookApi->getTransaccionId());
            $ItTransaccion->setMandante($UsuarioMandante->mandante);


            $ItTransaccionMySqlDAO = new ItTransaccionMySqlDAO($Transaction);

            $ItCuentatransId = $ItTransaccionMySqlDAO->insert($ItTransaccion);


            // COMMIT de la transacción
            $Transaction->commit();


            /*  Obtenemos el mandante para verificar sus caracteristicas  */
            $Mandante = new Mandante($UsuarioMandante->mandante);


            if ($Mandante->propio == "S") {
                /*  Consultamos de nuevo el usuario para obtener el saldo  */
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);


                switch ($UsuarioPerfil->getPerfilId()) {
                    case "USUONLINE":

                        $Balance = $Usuario->getBalance();

                        break;

                    case "MAQUINAANONIMA":


                        /*$PuntoVenta = new PuntoVenta("",$UsuarioMandante->usuarioMandante);

                    $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                    $SaldoJuego = $PuntoVenta->getCreditosBase();

                    $Balance = $SaldoJuego;*/

                        $Balance = $Usuario->getBalance();

                        break;

                    case "PUNTOVENTA":

                        $PuntoVenta = new PuntoVenta("", $UsuarioMandante->usuarioMandante);

                        $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                        $SaldoJuego = $PuntoVenta->getCreditosBase();

                        $Balance = $SaldoJuego + $SaldoRecargas;


                        break;
                }
                try {
                    /*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
                    $UsuarioToken = new UsuarioToken("", '1', $UsuarioMandante->getUsumandanteId());

                    /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
                    $data = $UsuarioMandante->getWSProfileSite($UsuarioToken->getRequestId());
                    $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                    $WebsocketUsuario->sendWSMessage();
                    //$UsuarioToken = new UsuarioToken("", $Producto->getProveedorId(), $UsuarioMandante->getUsumandanteId());
                } catch (Exception $e) {
                }
            }
            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $transsportsbookApi->setRespuestaCodigo("OK");
            $transsportsbookApi->setRespuesta('');
            $TranssportsbookApiMySqlDAO = new TranssportsbookApiMySqlDAO();
            $TranssportsbookApiMySqlDAO->insert($transsportsbookApi);

            $TranssportsbookApiMySqlDAO->getTransaction()->commit();

            $respuesta->usuarioId = $UsuarioMandante->usuarioMandante;
            $respuesta->moneda = $UsuarioMandante->moneda;
            $respuesta->usuario = "Usuario" . $UsuarioMandante->usuarioMandante;
            $respuesta->saldo = $Balance;
            $respuesta->transaccionId = $ItTransaccion->itCuentatransId;
            $respuesta->transsportsbookApi = $transsportsbookApi;

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
     * Procesa el balance de un bono para un usuario.
     *
     * @param object $UsuarioMandante    Información del usuario mandante.
     * @param object $Producto           Información del producto asociado.
     * @param object $transsportsbookApi Objeto que contiene los datos de la transacción API.
     * @param string $BonusStatus        Estado del bono (por ejemplo, 'Active').
     * @param string $BonusId            Identificador del bono.
     * @param string $BonusPlanId        Identificador del plan de bono.
     * @param float  $BonusAmount        Monto del bono.
     * @param object $ticket             Información del ticket asociado.
     *
     * @return object Respuesta con los detalles de la transacción, saldo y otros datos relevantes.
     *
     * @throws Exception Si ocurre algún error durante el procesamiento del balance del bono.
     */
    public function BonusBalance($UsuarioMandante, $Producto, $transsportsbookApi, $BonusStatus, $BonusId, $BonusPlanId, $BonusAmount, $ticket)
    {
        try {
            $respuesta = json_decode(json_encode(array(
                "usuarioId" => "",
                "moneda" => "",
                "usuario" => "",
                "transaccionId" => "",
                "saldo" => "",
                "transsportsbookApi" => ""
            )));

            $BonusAmount = $transsportsbookApi->getValor();


            /*  Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego  */
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            /*  Agregamos Elementos a la Transaccion API  */
            $transsportsbookApi->setProductoId($ProductoMandante->prodmandanteId);
            $transsportsbookApi->setUsuarioId($UsuarioMandante->getUsumandanteId());
            $transsportsbookApi->setMandante($UsuarioMandante->getMandante());


            /*  Verificamos que la transaccionId no se haya procesado antes  */
            if ($transsportsbookApi->existsTransaccionIdAndProveedor("OK")) {
                /*  Si la transaccionId ha sido procesada, reportamos el error  */
                throw new Exception("Transaccion ya procesada", "10001");
            }


            /*  Consultamos de nuevo el usuario para obtener el saldo  */
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);


            if ($UsuarioPerfil->perfilId != 'USUONLINE') {
                throw new Exception("No existen bonos", "300000");
            }


            $UsuarioMySqlDAO = new UsuarioMySqlDAO();
            $Transaction = $UsuarioMySqlDAO->getTransaction();


            $rules = array();
            array_push($rules, array("field" => "bono_detalle.tipo", "data" => "BONUSPLANIDALTENAR", "op" => "eq"));
            array_push($rules, array("field" => "bono_detalle.valor", "data" => $BonusPlanId, "op" => "eq"));
            //array_push($rules, array("field" => "bono_interno.estado", "data" => 'A', "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");

            $SkeepRows = 0;
            $OrderedItem = 1;
            $MaxRows = 1000000000;


            $json = json_encode($filtro);

            $BonoDetalle = new BonoDetalle();
            $bonodetalles = $BonoDetalle->getBonoDetallesCustom("bono_detalle.*,bono_interno.* ", "bono_interno.bono_id", "desc", $SkeepRows, $MaxRows, $json, true);

            $bonodetalles = json_decode($bonodetalles);


            /*CHECK mapear los codigos de error*/

            if (intval($bonodetalles->count[0]->{".count"}) == 0) {
                throw new Exception("No existen bonos", "300000");
            } else {
                $BonoId = $bonodetalles->data[0]->{'bono_interno.bono_id'};

                if ($BonusStatus != 'Active') {
                    $rules = [];


                    array_push($rules, array("field" => "usuario_bono.externo_id", "data" => $BonusId, "op" => "eq"));
                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);

                    $UsuarioBono = new UsuarioBono();
                    $data = $UsuarioBono->getUsuarioBonosCustom("usuario_bono.*", "usuario_bono.usubono_id", 'asc', 0, 1000, $json, true, '', true, false);
                    $data = json_decode($data);


                    if (intval($data->count[0]->{".count"}) == 0) {
                        $UsuarioBono = new UsuarioBono();

                        $UsuarioBono->setUsuarioId($Usuario->usuarioId);
                        $UsuarioBono->setBonoId($BonoId);
                        $UsuarioBono->setValor($BonusAmount);
                        $UsuarioBono->setValorBono($BonusAmount);
                        $UsuarioBono->setValorBase($BonusAmount);
                        $UsuarioBono->setEstado('E');
                        $UsuarioBono->setErrorId('0');
                        $UsuarioBono->setIdExterno($BonusId);
                        $UsuarioBono->setMandante($Usuario->mandante);
                        $UsuarioBono->setUsucreaId('0');
                        $UsuarioBono->setUsumodifId('0');
                        $UsuarioBono->setApostado('0');
                        $UsuarioBono->setVersion('2');
                        $UsuarioBono->setRollowerRequerido('0');
                        $UsuarioBono->setCodigo('');
                        $UsuarioBono->setExternoId($BonusId);
                        $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($Transaction);

                        $usubonoId = $UsuarioBonoMysqlDAO->insert($UsuarioBono);


                        $BonoLog = new BonoLog();
                        $BonoLog->setUsuarioId($Usuario->usuarioId);
                        $BonoLog->setTipo('AF');
                        $BonoLog->setValor($BonusAmount);
                        $BonoLog->setFechaCrea(date('Y-m-d H:i:s'));
                        $BonoLog->setFechaCierre(date('Y-m-d H:i:s'));
                        $BonoLog->setEstado('L');
                        $BonoLog->setErrorId(0);
                        $BonoLog->setIdExterno($BonusId);
                        $BonoLog->setMandante($Usuario->mandante);
                        $BonoLog->setTipobonoId(0);
                        $BonoLog->setTiposaldoId('1');


                        $BonoLogMySqlDAO = new BonoLogMySqlDAO($Transaction);
                        $BonoLogMySqlDAO->insert($BonoLog);
                    } else {
                        $UsuarioBono = new UsuarioBono($data->data[0]->{'usuario_bono.usubono_id'});
                        $UsuarioBono->setEstado('E');
                        $UsuarioBono->setIdExterno($BonusId);
                        $UsuarioBono->setExternoId($BonusId);

                        $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($Transaction);

                        $usubonoId = $UsuarioBonoMysqlDAO->update($UsuarioBono);
                    }
                } else {
                    $UsuarioBono = new UsuarioBono();

                    $UsuarioBono->setUsuarioId($Usuario->usuarioId);
                    $UsuarioBono->setBonoId($BonoId);
                    $UsuarioBono->setValor($BonusAmount);
                    $UsuarioBono->setValorBono($BonusAmount);
                    $UsuarioBono->setValorBase($BonusAmount);
                    $UsuarioBono->setEstado('A');
                    $UsuarioBono->setErrorId('0');
                    $UsuarioBono->setIdExterno($BonusId);
                    $UsuarioBono->setMandante($Usuario->mandante);
                    $UsuarioBono->setUsucreaId('0');
                    $UsuarioBono->setUsumodifId('0');
                    $UsuarioBono->setApostado('0');
                    $UsuarioBono->setVersion('2');
                    $UsuarioBono->setRollowerRequerido('0');
                    $UsuarioBono->setCodigo('');
                    $UsuarioBono->setExternoId($BonusId);
                    $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($Transaction);

                    $usubonoId = $UsuarioBonoMysqlDAO->insert($UsuarioBono);


                    $BonoLog = new BonoLog();
                    $BonoLog->setUsuarioId($Usuario->usuarioId);
                    $BonoLog->setTipo('AF');
                    $BonoLog->setValor($BonusAmount);
                    $BonoLog->setFechaCrea(date('Y-m-d H:i:s'));
                    $BonoLog->setEstado('A');
                    $BonoLog->setErrorId(0);
                    $BonoLog->setIdExterno($BonusId);
                    $BonoLog->setMandante($Usuario->mandante);
                    $BonoLog->setTipobonoId(0);
                    $BonoLog->setTiposaldoId('1');


                    $BonoLogMySqlDAO = new BonoLogMySqlDAO($Transaction);
                    $BonoLogMySqlDAO->insert($BonoLog);
                }
            }

            $transsportsbookApi->setTranssportId('0');

            /*  Obtenemos el tipo de transacción dependiendo a betTypeID y si se le suma los creditos o no */
            $sumaCreditos = false;
            $tipoTransaccion = $ticket->TipoTransaccion;


            /*  ESTADOS DE LAS APUESTAS DE ITAINMENT
       C = Open (Abierto)
       S = Won (Gano)
       N = Lost (Perdio)
       A = Void (No Accion)
       R = Pending (Pendiente)
       W = Waiting (En Espera)
       J = Rejected (Rechazada)
       M = RejectedByMTS (Rechazada por Regla)
       T = Cashout (Retiro Voluntario)
        */

            /*  Creamos el log de la transaccion juego para auditoria  */
            $ItTransaccion = new ItTransaccion();

            $ItTransaccion->setTipo($tipoTransaccion);
            $ItTransaccion->setTicketId('#');
            $ItTransaccion->setGameReference('#');
            $ItTransaccion->setUsuarioId($UsuarioMandante->usuarioMandante);
            $ItTransaccion->setBetStatus('#');
            $ItTransaccion->setValor($BonusAmount);
            $ItTransaccion->setTransaccionId($transsportsbookApi->getTransaccionId());
            $ItTransaccion->setMandante($UsuarioMandante->mandante);


            $ItTransaccionMySqlDAO = new ItTransaccionMySqlDAO($Transaction);

            $ItCuentatransId = $ItTransaccionMySqlDAO->insert($ItTransaccion);


            // COMMIT de la transacción
            $Transaction->commit();


            /*  Obtenemos el mandante para verificar sus caracteristicas  */
            $Mandante = new Mandante($UsuarioMandante->mandante);


            if ($Mandante->propio == "S") {
                /*  Consultamos de nuevo el usuario para obtener el saldo  */
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);


                switch ($UsuarioPerfil->getPerfilId()) {
                    case "USUONLINE":

                        $Balance = $Usuario->getBalance();

                        break;

                    case "MAQUINAANONIMA":


                        /*$PuntoVenta = new PuntoVenta("",$UsuarioMandante->usuarioMandante);

                    $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                    $SaldoJuego = $PuntoVenta->getCreditosBase();

                    $Balance = $SaldoJuego;*/

                        $Balance = $Usuario->getBalance();

                        break;

                    case "PUNTOVENTA":

                        $PuntoVenta = new PuntoVenta("", $UsuarioMandante->usuarioMandante);

                        $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                        $SaldoJuego = $PuntoVenta->getCreditosBase();

                        $Balance = $SaldoJuego + $SaldoRecargas;


                        break;
                }
                try {
                    /*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
                    $UsuarioToken = new UsuarioToken("", '1', $UsuarioMandante->getUsumandanteId());

                    /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
                    $data = $UsuarioMandante->getWSProfileSite($UsuarioToken->getRequestId());
                    $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                    $WebsocketUsuario->sendWSMessage();
                    //$UsuarioToken = new UsuarioToken("", $Producto->getProveedorId(), $UsuarioMandante->getUsumandanteId());
                } catch (Exception $e) {
                }
            }
            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $transsportsbookApi->setRespuestaCodigo("OK");
            $transsportsbookApi->setRespuesta('');
            $TranssportsbookApiMySqlDAO = new TranssportsbookApiMySqlDAO();
            $TranssportsbookApiMySqlDAO->insert($transsportsbookApi);

            $TranssportsbookApiMySqlDAO->getTransaction()->commit();

            if ($Usuario->billeteraId == 2) {
                $Balance = 1000000;
            }


            $respuesta->usuarioId = $UsuarioMandante->usuarioMandante;
            $respuesta->moneda = $UsuarioMandante->moneda;
            $respuesta->usuario = "Usuario" . $UsuarioMandante->usuarioMandante;
            $respuesta->saldo = $Balance;
            $respuesta->transaccionId = $ItTransaccion->itCuentatransId;
            $respuesta->transsportsbookApi = $transsportsbookApi;

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
     * Realiza el proceso de rollback para una transacción de apuesta.
     *
     * @param object $UsuarioMandante    Información del usuario mandante.
     * @param object $Producto           Información del producto asociado.
     * @param object $transsportsbookApi Objeto de la transacción API.
     * @param object $ticket             Información del ticket de la apuesta.
     *
     * @return object Respuesta con los detalles del rollback.
     * @throws Exception Si ocurre algún error durante el proceso.
     */
    public function rollback($UsuarioMandante, $Producto, $transsportsbookApi, $ticket)
    {
        try {
            $respuesta = json_decode(json_encode(array(
                "usuarioId" => "",
                "moneda" => "",
                "usuario" => "",
                "transaccionId" => "",
                "saldo" => "",
                "transsportsbookApi" => ""
            )));


            /*  Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego  */
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            /*  Agregamos Elementos a la Transaccion API  */
            $transsportsbookApi->setProductoId($ProductoMandante->prodmandanteId);
            $transsportsbookApi->setUsuarioId($UsuarioMandante->getUsumandanteId());

            /*  Verificamos que la transaccionId no se haya procesado antes  */
            if ($transsportsbookApi->existsTransaccionIdAndProveedor("OK")) {
                if ($_ENV['debug']) {
                    print_r($transsportsbookApi);
                }
                /*  Si la transaccionId ha sido procesada, reportamos el error  */
                throw new Exception("Transaccion ya procesada", "10001");
            }


            $noExisteTransaccion = false;
            try {
                $ItTicketEnc = new ItTicketEnc($transsportsbookApi->getIdentificador());
            } catch (Exception $e) {
                $noExisteTransaccion = true;
            }

            if ($noExisteTransaccion) {
                throw new Exception("Transaccion no existe", "10005");
            }


            $rollbackAmount = $ItTicketEnc->getVlrApuesta();

            $transsportsbookApi->setTranssportId($ItTicketEnc->getItTicketId());

            /* Obtenemos la Transaccion de la BD para crear y registrar el Debito con esta transaccion */
            $ItTicketEncMySqlDAO = new ItTicketEncMySqlDAO();
            $Transaction = $ItTicketEncMySqlDAO->getTransaction();

            /*  Verificamos que la Transaccion si este conectada y lista para usarse  */
            //if ($Transaction->isIsconnected()) {


            $ItTransaccionMySqlDAO = new ItTicketEncMySqlDAO($Transaction);

            $itTicketId = $ItTransaccionMySqlDAO->update($ItTicketEnc);

            /*  Verificamos que el valor del ticket sea igual al valor del Rollback  */
            if ($ItTicketEnc->getVlrApuesta() != $rollbackAmount) {
                //throw new Exception("Valor ticket diferente al Rollback", "10003");
            }


            /*  Obtenemos el Transaccion Juego ID  */
            $TranssportId = $ItTicketEnc->getItTicketId();

            $tipoTransaccion = $ticket->TipoTransaccion;

            $ItTransaccion = new ItTransaccion();
            $ItTransaccion->setTipo($tipoTransaccion);
            $ItTransaccion->setTicketId($ItTicketEnc->ticketId);
            $ItTransaccion->setGameReference($ticket->GameReference);
            $ItTransaccion->setUsuarioId($UsuarioMandante->usuarioMandante);
            $ItTransaccion->setBetStatus($ticket->BetStatus);
            $ItTransaccion->setValor($rollbackAmount);
            $ItTransaccion->setTransaccionId($transsportsbookApi->getTransaccionId());
            $ItTransaccion->setMandante($UsuarioMandante->mandante);


            $ItTransaccionMySqlDAO = new ItTransaccionMySqlDAO($Transaction);

            $ItCuentatransId = $ItTransaccionMySqlDAO->insert($ItTransaccion);

            /*  Obtenemos Mandante para verificar sus caracteristicas  */
            $Mandante = new Mandante($UsuarioMandante->mandante);

            /*  Verificamos si el mandante es Propio  */
            if ($Mandante->propio == "S") {
                /*  Obtenemos el Usuario para hacerle el credito  */
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);


                if ($ItTicketEnc->freebet != 0 && $ItTicketEnc->freebet != "") {
                    $UsuarioBono = new UsuarioBono($ItTicketEnc->freebet);

                    $UsuarioBono->estado = 'A';
                    $UsuarioBono->valor = '0';
                    $UsuarioBono->externoId = '0';


                    $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($Transaction);

                    $usubonoId = $UsuarioBonoMysqlDAO->update($UsuarioBono);

                    $BonoLog = new BonoLog();
                    $BonoLog->setUsuarioId($Usuario->usuarioId);
                    $BonoLog->setTipo('F');
                    $BonoLog->setValor($ItTransaccion->getValor());
                    $BonoLog->setFechaCrea(date('Y-m-d H:i:s'));
                    $BonoLog->setFechaCierre(date('Y-m-d H:i:s'));
                    $BonoLog->setEstado('E');
                    $BonoLog->setErrorId(0);
                    $BonoLog->setIdExterno($usubonoId);
                    $BonoLog->setMandante($Usuario->mandante);
                    $BonoLog->setTipobonoId(5);
                    $BonoLog->setTiposaldoId('1');


                    $BonoLogMySqlDAO = new BonoLogMySqlDAO($Transaction);
                    $BonoLogMySqlDAO->insert($BonoLog);
                }


                switch ($UsuarioPerfil->getPerfilId()) {
                    case "USUONLINE":

                        if ($Usuario->billeteraId == 1) {
                            $Registro = new Registro("", $Usuario->usuarioId);

                            $VoidBetRequest = new SimpleXMLElement("<VoidBetRequest></VoidBetRequest>");

                            $ExternalAccountId = $VoidBetRequest->addChild('ExternalAccountId', $Usuario->usuarioId);
                            //$ExternalAccountId->('Value', $usuario_id);

                            $MobileNumber = $VoidBetRequest->addChild('MobileNumber', $Registro->celular);

                            $ProviderUserId = $VoidBetRequest->addChild('ProviderUserId', $this->QuiskProviderUserId);

                            $ProviderPassword = $VoidBetRequest->addChild('ProviderPassword', sha1($this->QuiskProviderPassword));

                            $MerchantId = $VoidBetRequest->addChild('MerchantId', $this->QuiskMerchantId);

                            $PaymentTransReference = $VoidBetRequest->addChild('PaymentTransReference', $ItTicketEnc->getTransaccionWallet());
                            $BettingNumber = $VoidBetRequest->addChild('BettingNumber', str_replace("ITN", "", $ItTicketEnc->ticketId));
                            $BetAmount = $VoidBetRequest->addChild('BetAmount', number_format($rollbackAmount, 2, '.', ''));
                            $Bettingcategory = $VoidBetRequest->addChild('BettingCategory', 1);
                            $BetAmount = $VoidBetRequest->addChild('TransDateTime', date('Ymd H:i:s'));


                            $responseQuisk = $this->createRequestQuisk($this->QuiskVoidBetURL, str_replace('<?xml version="1.0"?>\n', "", $VoidBetRequest->asXML()));


                            try {
                                $responseQuiskXML = simplexml_load_string($responseQuisk);
                            } catch (Exception $e) {
                                $TranssportsbookApi3 = new TranssportsbookApi();
                                $TranssportsbookApi3->setTranssportId($ItTicketEnc->getItTicketId());
                                $TranssportsbookApi3->setTransaccionId('QUISK' . $transsportsbookApi->getTransaccionId());
                                $TranssportsbookApi3->setTipo('QUISK');
                                $TranssportsbookApi3->setTValue($VoidBetRequest->asXML() . $e->getMessage());
                                $TranssportsbookApi3->setUsucreaId(0);
                                $TranssportsbookApi3->setUsumodifId(0);
                                $TranssportsbookApi3->setValor($ItTicketEnc->getVlrApuesta());
                                $TranssportsbookApi3->setUsuarioId($UsuarioMandante->usuarioMandante);

                                $TranssportsbookApi3->setMandante($UsuarioMandante->mandante);
                                $TranssportsbookApi3->setProveedorId(0);
                                $TranssportsbookApi3->setIdentificador(0);

                                $TranssportsbookApiMySqlDAO2 = new TranssportsbookApiMySqlDAO();

                                $TranssportsbookApiMySqlDAO2->insert($TranssportsbookApi3);
                                $TranssportsbookApiMySqlDAO2->getTransaction()->commit();
                            }


                            if ($responseQuiskXML) {
                                $TranssportsbookApi3 = new TranssportsbookApi();
                                $TranssportsbookApi3->setTranssportId($ItTicketEnc->getItTicketId());
                                $TranssportsbookApi3->setTransaccionId('QUISK' . $transsportsbookApi->getTransaccionId());
                                $TranssportsbookApi3->setTipo('QUISK');
                                $TranssportsbookApi3->setTValue($VoidBetRequest->asXML() . $responseQuiskXML->asXML());
                                $TranssportsbookApi3->setUsucreaId(0);
                                $TranssportsbookApi3->setUsumodifId(0);
                                $TranssportsbookApi3->setValor($ItTicketEnc->getVlrApuesta());
                                $TranssportsbookApi3->setUsuarioId($UsuarioMandante->usuarioMandante);

                                $TranssportsbookApi3->setMandante($UsuarioMandante->mandante);
                                $TranssportsbookApi3->setProveedorId(0);
                                $TranssportsbookApi3->setIdentificador(0);
                                $TranssportsbookApi3->setProveedorId(0);
                                $TranssportsbookApi3->setIdentificador(0);

                                $TranssportsbookApiMySqlDAO2 = new TranssportsbookApiMySqlDAO();

                                $TranssportsbookApiMySqlDAO2->insert($TranssportsbookApi3);
                                $TranssportsbookApiMySqlDAO2->getTransaction()->commit();

                                switch ($responseQuiskXML->Status) {
                                    case "SUCCESS":
                                        $Balance = 10000;

                                        break;
                                    case "BAD_REQUEST":
                                        throw new Exception("BAD_REQUEST", 50002);

                                        break;
                                    case "INSUFFICIENT_FUNDS":
                                        throw new Exception("INSUFFICIENT_FUNDS", 50003);

                                        break;
                                    case "INVALID_BET_NUMBER":
                                        throw new Exception("INVALID_BET_NUMBER", 50004);

                                        break;
                                    case "INVALID_MERCHANT":
                                        throw new Exception("INVALID_MERCHANT", 50005);

                                        break;
                                    case "INVALID_TOKEN":
                                        throw new Exception("INVALID_TOKEN", 50006);

                                        break;
                                    case "USER_NOT_FLAGGED_BETTING":
                                        throw new Exception("USER_NOT_FLAGGED_BETTING", 50007);

                                        break;
                                    default:
                                        throw new Exception($responseQuiskXML->Status, 50007);

                                        break;
                                }
                            } else {
                                $TranssportsbookApi3 = new TranssportsbookApi();
                                $TranssportsbookApi3->setTranssportId($ItTicketEnc->getItTicketId());
                                $TranssportsbookApi3->setTransaccionId('QUISK' . $transsportsbookApi->getTransaccionId());
                                $TranssportsbookApi3->setTipo('QUISK');
                                $TranssportsbookApi3->setTValue($VoidBetRequest->asXML() . $responseQuisk);
                                $TranssportsbookApi3->setUsucreaId(0);
                                $TranssportsbookApi3->setUsumodifId(0);
                                $TranssportsbookApi3->setValor($ItTicketEnc->getVlrApuesta());
                                $TranssportsbookApi3->setUsuarioId($UsuarioMandante->usuarioMandante);

                                $TranssportsbookApi3->setMandante($UsuarioMandante->mandante);
                                $TranssportsbookApi3->setProveedorId(0);
                                $TranssportsbookApi3->setIdentificador(0);
                                $TranssportsbookApi3->setProductoId(0);
                                $TranssportsbookApi3->setProductoId(0);

                                $TranssportsbookApiMySqlDAO2 = new TranssportsbookApiMySqlDAO();

                                $TranssportsbookApiMySqlDAO2->insert($TranssportsbookApi3);
                                $TranssportsbookApiMySqlDAO2->getTransaction()->commit();

                                throw new Exception("QuiskNoRESPONDE", 50008);
                            }
                        } else {
                            if ( ! ($ItTicketEnc->freebet != 0 && $ItTicketEnc->freebet != "")) {
                                $saldo_creditos_base = $ItTicketEnc->getSaldoCreditosBase();
                                $saldo_creditos = $ItTicketEnc->getSaldoCreditos();

                                if ($saldo_creditos_base > 0) {
                                    $Usuario->credit($saldo_creditos_base, $Transaction);
                                }

                                if ($saldo_creditos > 0) {
                                    $Usuario->creditWin($saldo_creditos, $Transaction);
                                }

                                $Balance = (($Usuario->getBalance()));


                                $UsuarioHistorial = new UsuarioHistorial();
                                $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                                $UsuarioHistorial->setDescripcion('');
                                $UsuarioHistorial->setMovimiento('C');
                                $UsuarioHistorial->setUsucreaId(0);
                                $UsuarioHistorial->setUsumodifId(0);
                                $UsuarioHistorial->setTipo(20);
                                $UsuarioHistorial->setValor($ItTransaccion->getValor());
                                $UsuarioHistorial->setExternoId($ItTicketEnc->ticketId);

                                $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                                $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);
                            }
                            /*  Actualizamos Transaccion Juego  */


                            if ($ItTicketEnc->getFechaCierre() == '' || $ItTicketEnc->getFechaCierre() == null) {
                                $ItTicketEnc->setEstado("I");
                                $ItTicketEnc->setEliminado("S");
                                $ItTicketEnc->setBetStatus($ticket->BetStatus);


                                $ItTicketEnc->setFechaCierre(date('Y-m-d H:i:s', time()));
                            } else {
                                if ($ItTicketEnc->getFechaCierre() == date('Y-m-d')) {
                                    $ItTicketEnc->setEstado("I");
                                    $ItTicketEnc->setEliminado("S");
                                    $ItTicketEnc->setBetStatus($ticket->BetStatus);
                                }
                            }


                            $SkeepRows = 0;
                            $OrderedItem = 1;
                            $MaxRows = 1000000000;


                            $rules = [];

                            array_push($rules, array("field" => "it_ticket_enc_info1.ticket_id", "data" => $ItTicketEnc->ticketId, "op" => "eq"));
                            array_push($rules, array("field" => "it_ticket_enc_info1.tipo", "data" => "TORNEO", "op" => "eq"));

                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                            $json2 = json_encode($filtro);

                            $ItTicketEncInfo1 = new ItTicketEncInfo1();

                            $tickets = $ItTicketEncInfo1->getTicketsCustom(" it_ticket_enc_info1.*,it_ticket_enc.vlr_apuesta,it_ticket_enc.fecha_cierre,it_ticket_enc.fecha_crea ", "it_ticket_enc_info1.it_ticket2_id", "asc", $SkeepRows, $MaxRows, $json2, true);

                            $tickets = json_decode($tickets);


                            if (intval($tickets->count[0]->{".count"}) > 0) {
                                $usutorneoId = $tickets->data[0]->{'it_ticket_enc_info1.valor'};
                                $itTicket2Id = $tickets->data[0]->{'it_ticket_enc_info1.it_ticket2_id'};
                                $creditosConvert = $tickets->data[0]->{'it_ticket_enc_info1.valor2'};


                                $UsuarioTorneo = new UsuarioTorneo($usutorneoId);


                                $UsuarioTorneo->setValor($UsuarioTorneo->getValor() - $creditosConvert);
                                $UsuarioTorneo->setValorBase($UsuarioTorneo->getValorBase() - $ItTicketEnc->getVlrApuesta());


                                $UsuarioTorneoMysqlDAO = new UsuarioTorneoMySqlDAO($Transaction);
                                $usutorneoId = $UsuarioTorneoMysqlDAO->update($UsuarioTorneo);


                                $BonoInterno = new BonoInterno();

                                $sql = "DELETE FROM it_ticket_enc_info1 where it_ticket2_id='" . $itTicket2Id . "'";

                                $dataDelete = $BonoInterno->execQuery($Transaction, $sql);
                            }
                        }
                        break;

                    case "MAQUINAANONIMA":


                        /*$PuntoVenta = new PuntoVenta("",$UsuarioMandante->usuarioMandante);

                    $PuntoVenta->setBalanceCreditosBase($rollbackAmount,$Transaction);*/


                        $Usuario->credit($rollbackAmount, $Transaction);
                        $Balance = (($Usuario->getBalance()));


                        $UsuarioHistorial = new UsuarioHistorial();
                        $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                        $UsuarioHistorial->setDescripcion('');
                        $UsuarioHistorial->setMovimiento('E');
                        $UsuarioHistorial->setUsucreaId(0);
                        $UsuarioHistorial->setUsumodifId(0);
                        $UsuarioHistorial->setTipo(20);
                        $UsuarioHistorial->setValor($ItTransaccion->getValor());
                        $UsuarioHistorial->setExternoId($ItTicketEnc->ticketId);

                        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

                        /*  Actualizamos Transaccion Juego  */
                        $ItTicketEnc->setEstado("I");
                        $ItTicketEnc->setEliminado("S");
                        $ItTicketEnc->setBetStatus($ticket->BetStatus);

                        if ($ItTicketEnc->getFechaCierre() == '' || $ItTicketEnc->getFechaCierre() == null) {
                            $ItTicketEnc->setFechaCierre(date('Y-m-d H:i:s', time()));
                        }


                        break;


                    case "PUNTOVENTA":

                        $bet_status = $ticket->BetStatus;
                        $descripcion = $ticket->Description;

                        if ($bet_status == "J" || $bet_status == "M" || $bet_status == "D" || ($bet_status == "A" && $descripcion == "Rollback bet")) {
                            /*  Actualizamos Transaccion Juego  */
                            $ItTicketEnc->setEstado("I");
                            $ItTicketEnc->setEliminado("S");
                            $ItTicketEnc->setBetStatus($ticket->BetStatus);

                            if ($ItTicketEnc->getFechaCierre() == '' || $ItTicketEnc->getFechaCierre() == null) {
                                $ItTicketEnc->setFechaCierre(date('Y-m-d H:i:s', time()));
                            }

                            $ItTicketEnc->setPremiado('N');
                            $ItTicketEnc->setVlrPremio($ItTicketEnc->getVlrPremio() + $ItTransaccion->getValor());

                            $FlujoCaja = new FlujoCaja();
                            $FlujoCaja->setFechaCrea(date('Y-m-d'));
                            $FlujoCaja->setHoraCrea(date('H:i'));
                            $FlujoCaja->setUsucreaId($Usuario->usuarioId);
                            $FlujoCaja->setTicketId($ItTransaccion->getTicketId());
                            $FlujoCaja->setTipomovId('E');
                            $FlujoCaja->setValor('-' . $ItTransaccion->getValor());
                            $FlujoCaja->setRecargaId(0);
                            $FlujoCaja->setMandante($Usuario->mandante);
                            $FlujoCaja->setValorForma1($ItTransaccion->getValor());

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

                            $saldo_creditos = $ItTransaccion->getValor();

                            $PuntoVenta = new PuntoVenta("", $Usuario->puntoventaId);
                            $rowsUpdate = $PuntoVenta->setBalanceCreditosBase($saldo_creditos, $Transaction);

                            if ($rowsUpdate > 0) {
                                $UsuarioHistorial = new UsuarioHistorial();
                                $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                                $UsuarioHistorial->setDescripcion('');
                                $UsuarioHistorial->setMovimiento('E');
                                $UsuarioHistorial->setUsucreaId(0);
                                $UsuarioHistorial->setUsumodifId(0);
                                $UsuarioHistorial->setTipo(20);
                                $UsuarioHistorial->setValor($ItTransaccion->getValor());
                                $UsuarioHistorial->setExternoId($ItTicketEnc->ticketId);

                                $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                                $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');
                            } else {
                                throw new Exception("You have reached your gaming balance limit. Please withdraw to place bet", 100030);
                            }
                        } else {
                            /*  Actualizamos Transaccion Juego  */
                            $ItTicketEnc->setEstado("I");
                            $ItTicketEnc->setBetStatus($ticket->BetStatus);

                            if ($ItTicketEnc->getFechaCierre() == '' || $ItTicketEnc->getFechaCierre() == null) {
                                $ItTicketEnc->setFechaCierre(date('Y-m-d H:i:s', time()));
                            }

                            $ItTicketEnc->setPremiado('S');

                            $ItTicketEnc->setPremioPagado('N');

                            $ItTicketEnc->setVlrPremio($ItTransaccion->getValor());

                            $ItTicketEnc->setFechaMaxpago(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . "+ 90 days")));


                            if ($Usuario->paisId == '2') {
                                $BonoInterno = new BonoInterno();

                                $sql = "INSERT INTO cheque (nro_cheque,pais_id,origen,documento_id,ticket_id,mandante) SELECT CASE WHEN max(a.nro_cheque)+1 IS NULL THEN 1 ELSE max(a.nro_cheque)+1 END," . $Usuario->paisId . ",'TK'," . $ItTicketEnc->ticketId . "," . $ItTicketEnc->ticketId . "," . $Usuario->mandante . " from cheque a where a.mandante=" . $Usuario->mandante . " and a.pais_id=" . $Usuario->paisId;

                                $data = $BonoInterno->execQuery($Transaction, $sql);
                            }
                        }

                        break;

                    case "CAJERO":

                        $bet_status = $ticket->BetStatus;
                        $descripcion = $ticket->Description;

                        if ($bet_status == "J" || $bet_status == "M" || $bet_status == "D" || ($bet_status == "A" && $descripcion == "Rollback bet")) {
                            /*  Actualizamos Transaccion Juego  */
                            $ItTicketEnc->setEstado("I");
                            $ItTicketEnc->setEliminado("S");
                            $ItTicketEnc->setBetStatus($ticket->BetStatus);

                            if ($ItTicketEnc->getFechaCierre() == '' || $ItTicketEnc->getFechaCierre() == null) {
                                $ItTicketEnc->setFechaCierre(date('Y-m-d H:i:s', time()));
                            }

                            $ItTicketEnc->setPremiado('N');
                            $ItTicketEnc->setVlrPremio($ItTicketEnc->getVlrPremio() + $ItTransaccion->getValor());

                            $FlujoCaja = new FlujoCaja();
                            $FlujoCaja->setFechaCrea(date('Y-m-d'));
                            $FlujoCaja->setHoraCrea(date('H:i'));
                            $FlujoCaja->setUsucreaId($Usuario->usuarioId);
                            $FlujoCaja->setTicketId($ItTransaccion->getTicketId());
                            $FlujoCaja->setTipomovId('E');
                            $FlujoCaja->setValor('-' . $ItTransaccion->getValor());
                            $FlujoCaja->setRecargaId(0);
                            $FlujoCaja->setMandante($Usuario->mandante);
                            $FlujoCaja->setValorForma1($ItTransaccion->getValor());

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

                            $saldo_creditos = $ItTransaccion->getValor();

                            $PuntoVenta = new PuntoVenta("", $Usuario->puntoventaId);
                            $rowsUpdate = $PuntoVenta->setBalanceCreditosBase($saldo_creditos, $Transaction);

                            if ($rowsUpdate > 0) {
                                $UsuarioHistorial = new UsuarioHistorial();
                                $UsuarioHistorial->setUsuarioId($Usuario->puntoventaId);
                                $UsuarioHistorial->setDescripcion('');
                                $UsuarioHistorial->setMovimiento('S');
                                $UsuarioHistorial->setUsucreaId(0);
                                $UsuarioHistorial->setUsumodifId(0);
                                $UsuarioHistorial->setTipo(20);
                                $UsuarioHistorial->setValor($ItTransaccion->getValor());
                                $UsuarioHistorial->setExternoId($ItTicketEnc->ticketId);

                                $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                                $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');
                            } else {
                                throw new Exception("You have reached your gaming balance limit. Please withdraw to place bet", 100030);
                            }
                        } else {
                            /*  Actualizamos Transaccion Juego  */
                            $ItTicketEnc->setEstado("I");
                            $ItTicketEnc->setBetStatus($ticket->BetStatus);

                            if ($ItTicketEnc->getFechaCierre() == '' || $ItTicketEnc->getFechaCierre() == null) {
                                $ItTicketEnc->setFechaCierre(date('Y-m-d H:i:s', time()));
                            }

                            $ItTicketEnc->setPremiado('S');

                            $ItTicketEnc->setPremioPagado('N');

                            $ItTicketEnc->setVlrPremio($ItTransaccion->getValor());

                            $ItTicketEnc->setFechaMaxpago(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . "+ 90 days")));


                            if ($Usuario->paisId == '2') {
                                $BonoInterno = new BonoInterno();

                                $sql = "INSERT INTO cheque (nro_cheque,pais_id,origen,documento_id,ticket_id,mandante) SELECT CASE WHEN max(a.nro_cheque)+1 IS NULL THEN 1 ELSE max(a.nro_cheque)+1 END," . $Usuario->paisId . ",'TK'," . $ItTicketEnc->ticketId . "," . $ItTicketEnc->ticketId . "," . $Usuario->mandante . " from cheque a where a.mandante=" . $Usuario->mandante . " and a.pais_id=" . $Usuario->paisId;

                                $data = $BonoInterno->execQuery($Transaction, $sql);
                            }
                        }

                        break;
                }
            } else {
            }

            $ItTransaccionMySqlDAO = new ItTicketEncMySqlDAO($Transaction);

            $itTicketId = $ItTransaccionMySqlDAO->update($ItTicketEnc);


            // COMMIT de la transacción
            $Transaction->commit();


            /*  Verificamos si el mandante es Propio  */
            if ($Mandante->propio == "S") {
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);


                switch ($UsuarioPerfil->getPerfilId()) {
                    case "USUONLINE":

                        $Balance = $Usuario->getBalance();

                        break;

                    case "MAQUINAANONIMA":


                        /*$PuntoVenta = new PuntoVenta("",$UsuarioMandante->usuarioMandante);

                    $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                    $SaldoJuego = $PuntoVenta->getCreditosBase();

                    $Balance = $SaldoJuego;*/

                        $Balance = $Usuario->getBalance();

                        break;

                    case "PUNTOVENTA":

                        $PuntoVenta = new PuntoVenta("", $UsuarioMandante->usuarioMandante);

                        $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                        $SaldoJuego = $PuntoVenta->getCreditosBase();

                        $Balance = $SaldoJuego + $SaldoRecargas;


                        break;
                }
            }

            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $transsportsbookApi->setRespuestaCodigo("OK");
            $transsportsbookApi->setRespuesta('');
            $TranssportsbookApiMySqlDAO = new TranssportsbookApiMySqlDAO();
            $TranssportsbookApiMySqlDAO->insert($transsportsbookApi);


            $TranssportsbookApiMySqlDAO->getTransaction()->commit();


            $respuesta->usuarioId = $UsuarioMandante->usuarioMandante;
            $respuesta->moneda = $UsuarioMandante->moneda;
            $respuesta->usuario = "Usuario" . $UsuarioMandante->usuarioMandante;
            $respuesta->saldo = $Balance;
            $respuesta->transaccionId = $ItTransaccion->itCuentatransId;
            $respuesta->transsportsbookApi = $transsportsbookApi;


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
     * Convierte un código de error específico del mandante en una excepción con un código de error interno.
     *
     * @param string $code    Código de error del mandante.
     * @param string $message Mensaje de error asociado al código (actualmente no utilizado).
     *
     * @return void
     * @throws Exception Si el código de error corresponde a un caso manejado o no reconocido.
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
     * Crea una solicitud a la API de Quisk utilizando cURL.
     *
     * @param string $URL URL de la API de Quisk.
     * @param string $xml XML a enviar en la solicitud.
     *
     * @return string Respuesta de la API.
     */
    function createRequestQuisk($URL, $xml)
    {
        $ch = curl_init($URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_SSLVERSION, 6);

        $string = 'hola';
        $string = utf8_encode(($xml));
        $key = $this->QuiskSecretKey;
        $signature = (base64_encode(hash_hmac('sha1', $string, $key, $raw_output = true)));

// Set HTTP Header for POST request
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: text/xml;encoding: utf-8',
            'Date: 2019-11-07T12:30:40.112Z',
            'Authorisation: 5486509221356732:' . $signature,
            'Content-Length: ' . strlen($xml)

        ));

//$rs = curl_exec($ch);
        $result = (curl_exec($ch));

        // Close cURL session handle
        curl_close($ch);

        return $result;
    }
}

/**
 * Depura un texto eliminando caracteres no deseados.
 *
 * Esta función reemplaza una serie de caracteres específicos en el texto proporcionado
 * con una cadena vacía, eliminándolos del texto. Es útil para limpiar entradas de texto
 * y evitar caracteres que puedan causar problemas en el procesamiento.
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
 * Genera una clave aleatoria compuesta únicamente por dígitos numéricos.
 *
 * @param integerl $length La longitud de la clave a generar.
 *
 * @return string La clave generada aleatoriamente.
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
