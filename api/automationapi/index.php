<?php
/**
 * Index de la api 'affiliates'
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */

use Backend\dto\Banco;
use Backend\dto\Banner;
use Backend\dto\BonoDetalle;
use Backend\dto\CategoriaProducto;
use Backend\dto\Clasificador;
use Backend\dto\Concesionario;
use Backend\dto\Consecutivo;
use Backend\dto\CuentaCobro;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
use Backend\dto\FlujoCaja;
use Backend\dto\ItTicketEnc;
use Backend\dto\Mandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\Pais;
use Backend\dto\Perfil;
use Backend\dto\PerfilSubmenu;
use Backend\dto\Producto;
use Backend\dto\PromocionalLog;
use Backend\dto\Proveedor;
use Backend\dto\PuntoVenta;
use Backend\dto\ProductoMandante;
use Backend\dto\Registro;
use Backend\dto\Submenu;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\Usuario;
use Backend\dto\Automation;
use Backend\dto\UsuarioAutomation;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioBanner;
use Backend\dto\UsuarioComision;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioLink;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMarketing;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioToken;
use Backend\dto\BonoInterno;
use Backend\dto\UsucomisionResumen;
use Backend\dto\UsumarketingResumen;
use Backend\mysql\BannerMySqlDAO;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\ConcesionarioMySqlDAO;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\FlujoCajaMySqlDAO;
use Backend\mysql\PerfilSubmenuMySqlDAO;
use Backend\mysql\PromocionalLogMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\UsuarioAutomationMySqlDAO;
use Backend\mysql\AutomationMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBannerMySqlDAO;
use Backend\mysql\UsuarioConfigMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioLinkMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMarketingMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\ProductoMySqlDAO;
use Backend\mysql\ProductoMandanteMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\websocket\WebsocketUsuario;

use Backend\utils\SessionGeneral;


header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);

header('Access-Control-Allow-Credentials: true');
header('Access-Control-Expose-Headers: Authentication');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS,PUT');
header('Content-Type: application/json');

require_once "require.php";
header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Access-Control-Allow-Headers, Access-Control-Allow-Credentials,Authentication, Origin, X-Requested-With, Content-Type, Bt');

header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);

ini_set('session.use_cookies', '1');


$session = new SessionGeneral();
$session->inicio_sesion('_s', false);

$URI = $_SERVER["REQUEST_URI"];

$params = file_get_contents('php://input');
$params = json_decode($params);
$response = array();


$urlApiAfiliados = "https://images.doradobet.com/affiliates/";

$URL_AFFILIATES_API = "http://localhost/proyectos/affiliates/global/";
$URL_AFFILIATES_API = "https://afiliados.doradobet.com/app/global/";
$claveEncrypt_Retiro = "12hur12b";

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit();
}

$ENCRYPTION_KEY = "D!@#$%^&*";

if (!function_exists('getallheaders')) {
    function getallheaders()
    {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}

if (false) {
    if (end(explode("/", current(explode("?", $URI)))) != "Login") {


        $response["HasError"] = true;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = "Usuario no logueado";
        $response["ModelErrors"] = [];
        $response["redirect_url"] = "https://afiliados.doradobet.com/";
        print_r(json_encode($response));

        exit();
    }
}


//$URI=str_replace("stst", "setBannerStatAll", $URI);
try {
    switch (end(explode("/", current(explode("?", $URI))))) {


        case 'Logout':
            $_SESSION = array();
            session_destroy();
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = true;

            break;

        /**
         * CheckAuthentication
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case 'CheckAuthentication':

            //Verifica si ya hubo un logueo
            if (!$_SESSION['logueado']) {
                $response["HasError"] = true;
                $response["AlertType"] = "danger";
                $response["AlertMessage"] = "Invalid Username and/or password ('.$e->getCode().')";
                $response["ModelErrors"] = [];
                $response["redirect_url"] = "https://afiliados.doradobet.com/";

                $response["Data"] = array(
                    "AuthenticationStatus" => 0,

                    "PermissionList" => array(),
                );

            } else {

                try {

                    $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);
                    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

                    /*
                $UsuarioToken = new UsuarioToken("", $responseU->user_id);

                $UsuarioToken->setRequestId($json->session->sid);
                $UsuarioToken->setCookie(encrypt($responseU->user_id . "#" . time()));

                $UsuarioTokenMySqlDAO = new \Backend\mysql\UsuarioTokenMySqlDAO();
                $UsuarioTokenMySqlDAO->update($UsuarioToken);
                $UsuarioTokenMySqlDAO->getTransaction()->commit();
                 */

                    /*
                "ViewClients","ViewMenuDashBoard","ManageClients","ViewPaymentReport","ViewDepositWithdrawalReport","ViewBalance","ViewSalesReport","ViewClientTurnoverReport","ViewMenuReport",
                 */

                    /*
                "ViewAgentMenu", "ViewAgentSystem", "ViewAgent", "ViewAgentSubAccounts", "ViewAgentMembers", "ViewMessages", "ViewEmailTemplates", "ManageMessages", "ViewTranslation", "ViewSettings", "ViewStream", "PMViewPartner", "PMViewProduct", "PMViewSale", "hjkhjkhjk", "ViewOddsFeed", "ViewMatch", "ViewSportLimits", "ViewPartnersBooking", "ViewMenuReport", "ViewCasinoReport", "ViewCashDeskReport", "ViewCRM", "ViewSegment", "CreateSegment", "ViewBetShops", "ViewClients", "ManageClients",
                 */

                    $response["HasError"] = false;
                    $response["AlertType"] = "success";
                    $response["AlertMessage"] = "";
                    $response["ModelErrors"] = [];


                    //if ($_SESSION['usuario2'] == "5") {
                    if ($_SESSION['usuario2'] == "163") {

                        $menus_string = obtenerMenu();


                        $response["Data"] = array(
                            "AuthenticationStatus" => 0,
                            "PartnerLimitType" => 1,
                            "FirstName" => $Usuario->nombre,
                            "Settings" => array(
                                "Language" => strtolower($Usuario->idioma),
                                "ReportCurrency" => $Usuario->moneda,
                                "TimeZone" => $Usuario->timezone,

                            ),
                            "LangId" => strtolower($Usuario->idioma),
                            "UserName" => $Usuario->nombre,
                            "CurrencyId" => $Usuario->moneda,
                            "UserId" => $Usuario->usuarioId,
                            "AgentId" => $Usuario->usuarioId,
                            "PermissionList" => $menus_string,
                            "PermissionList2" => array_merge(array("ManageDepositRequests",
                                "ManageWithdrawalRequests", "ManageUsers", "ViewClientBonuses", "ViewPlayers", "ViewAddHocReport", "ViewScout", "ViewCMS", "ViewAffiliate", "SGPlayersView", "SGStatisticsRake", "ViewFinancialReports", "ViewPaymentReport", "AssignAgentCredit", "ManageAgentCredit", "ViewAgentGroups", "ViewAgentCommissionGroups", "ViewAgentPtGroups", "ViewAgentBetLimitGroups", "ViewAgentGroups", "ViewAgentGroups", "ManageAgentCommissionGroups", "ManageAgentBetLimitGroups", "ManageAgentGroups", "ManageClientCredit", "ViewGames", "ViewClientSportBets", "ViewClientTransactions", "ViewClientLogins", "ViewClientCasinoBets", "ViewSportReport", "ViewMenuDashBoard", "ViewDashBoardActivePlayers", "ViewDashBoardNewRegistrations", "ViewDashBoardSportBets", "ViewDashBoardCasinoBets", "ViewDashBoardTopFiveGames", "ViewDashBoardTopSportsByStake", "ViewDashBoardTopFiveSportsbookPlayers", "ViewDashBoardTopFivePlayers", "ViewUsers", "ViewUsersMenu", "ViewUsersLogs", "ViewAgentTransfers", "ViewBalance", "ViewDepositWithdrawalReport", "PMManageSale", "PMManageProduct", "ViewSalesReport", "ViewTurnoverTaxReport", "ViewDepositRequests", "ViewWithdrawalRequests", "ViewDocuments", "ViewFinancialOperations", "ManageAgent", "ViewBetShopUsers", "ViewCashDesks", "ManageBetShopUsers", "ViewClientMessage", "ViewVerificationStep", "ResetClientPassword", "ViewAgentMenu", "ViewAgentSystem", "ViewAgent", "ViewAgentSubAccounts", "ViewAgentMembers", "ViewMessages", "ViewEmailTemplates", "ManageMessages", "ViewTranslation", "ViewSettings", "ViewStream", "PMViewPartner", "PMViewProduct", "PMViewSale", "hjkhjkhjk", "jhkhjkhjk", "ViewOddsFeed", "ViewMatch", "ViewSportLimits", "ViewPartnersBooking", "ViewMenuReport", "ViewCasinoReport", "ViewCashDeskReport", "ViewCRM", "ViewSegment", "CreateSegment", "ViewBetShops", "ViewClients", "ManageClients", "ViewMenuSecurity", "ViewMenuTeacher", "AdminUser", "Contingencia", "Menu", "Perfil", "PerfilOpciones", "Submenu", "UsuarioPerfil", "Clasificador", "Concesionario", "ViewAddHocReport", "ViewMenuManagement", "ActivarRegistros", "AjustarSaldo", "AsignacionCupo", "Bonos", "CuentaCobroEliminar", "GestionRed", "RegistroRapido", "ChequeReimpresion", "RecargaReversion", "GestionContacto", "ViewMenuCash", "FlujoCaja", "PagoPremio", "PagoNotaRetiro", "RecargarCredito", "ViewMenuQueries", "FlujoCajaHistorico", "FlujoCajaResumido", "InformeCasino", "InformeGerencial", "ListadoRecargasRetiros", "PremiosPendientesPagar", "ConsultaOnlineDetalle", "ConsultaOnlineResumen", "MakeCorrection", "Trabajaconnosotros", "ViewSportsBookReports", "ViewBetReport", "ViewSportReport", "ViewCompetitionReport", "ViewMarketReport", "ViewSports", "ViewCompetitions", "ViewClientLogHistory", "ManageTranslation", "ManageProviders", "ManagePartnerProducts"

                            ), $menus_string),

                        );
                    } else {

                        $menus_string = obtenerMenu();


                        $documentData = "";
                        $DocumentoUsuario = new DocumentoUsuario();
                        $DocumentoUsuario->usuarioId = $Usuario->usuarioId;
                        $Documentos = $DocumentoUsuario->getDocumentosNoProcesados(1);

                        if (oldCount($Documentos) > 0) {
                            $Documentos = json_decode(json_encode($Documentos))[0];
                            $documentData = array(
                                "accept" => false,
                                "slug" => $Documentos->{'descarga.ruta'},
                                "id" => intval($Documentos->{'descarga.descarga_id'}),
                                "checksum" => $Documentos->{'descarga.descarga_id'}
                            );
                        } else {
                            $documentData = array(
                                "accept" => true
                            );
                        }

                        $response["Data"] = array(
                            "AuthenticationStatus" => 0,
                            "PartnerLimitType" => 1,
                            "FirstName" => $Usuario->nombre,
                            "Settings" => array(
                                "Language" => strtolower($Usuario->idioma),
                                "ReportCurrency" => $Usuario->moneda,
                                "TimeZone" => $Usuario->timezone,

                            ),
                            "LangId" => strtolower($Usuario->idioma),
                            "UserName" => $Usuario->nombre,
                            "Document" => $documentData,
                            "CurrencyId" => $Usuario->moneda,
                            "UserId" => $Usuario->usuarioId,
                            "UserId2" => $_SESSION['usuario2'],
                            "AgentId" => $Usuario->usuarioId,
                            "UrlAffiliation" => "https://doradobet.com/#/?btag=" . encrypt($_SESSION['usuario2'] . "_0", $ENCRYPTION_KEY),
                            "PermissionList" => $menus_string,
                        );
                    }

                } catch (Exception $e) {

                    $response["HasError"] = true;
                    $response["AlertType"] = "danger";
                    $response["AlertMessage"] = "Invalid Username and/or password ('.$e->getCode().')";
                    $response["ModelErrors"] = [];

                    $response["Data"] = array(
                        "AuthenticationStatus" => 0,

                        "PermissionList" => array(),
                    );

                }

            }

            break;

        case "setDocumentUser":
            $id = $params->Id;
            $type = $params->Type;

            if (is_numeric($id) && is_numeric($type)) {

                $ClientId = $_SESSION["usuario"];

                if ($type == 1) {
                    $Descarga = new Descarga($id);

                    if ($Descarga->estado == "A") {
                        $DocumentoUsuario = new DocumentoUsuario();

                        $DocumentoUsuario->usuarioId = $ClientId;
                        $DocumentoUsuario->documentoId = $Descarga->descargaId;
                        $DocumentoUsuario->version = $Descarga->version;
                        $DocumentoUsuario->estadoAprobacion = "A";

                        $DocumentoUsuarioMySqlDAO = new DocumentoUsuarioMySqlDAO();
                        $DocumentoUsuarioMySqlDAO->insert($DocumentoUsuario);
                        $DocumentoUsuarioMySqlDAO->getTransaction()->commit();
                    }
                }
                if ($type == 0) {
                    $Descarga = new Descarga($id);

                    if ($Descarga->estado == "A") {
                        $DocumentoUsuario = new DocumentoUsuario();

                        $DocumentoUsuario->usuarioId = $ClientId;
                        $DocumentoUsuario->documentoId = $Descarga->descargaId;
                        $DocumentoUsuario->version = $Descarga->version;
                        $DocumentoUsuario->estadoAprobacion = "R";

                        $DocumentoUsuarioMySqlDAO = new DocumentoUsuarioMySqlDAO();
                        $DocumentoUsuarioMySqlDAO->insert($DocumentoUsuario);
                        $DocumentoUsuarioMySqlDAO->getTransaction()->commit();
                    }

                }
                $response["status"] = true;

            } else {
                $response["status"] = false;
                $response["error"] = "Ocurrio un error, comuniquese con soporte.";

            }

            break;

        /**
         * change-password
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "change-password":

            $oldPassword = $params->oldPassword;
            $newPassword = $params->newPassword;


            $Usuario = new Usuario ($_SESSION["usuario"]);

            if ($Usuario->checkClave($oldPassword)) {
                $Usuario->changeClave($newPassword);

                $response["status"] = true;
                $response["html"] = "";
                $response["result"] = true;
                $response["notification"] = array();

            } else {
                $response["status"] = false;
                $response["html"] = "";
                $response["result"] = false;
                $response["notification"] = array();

            }

            break;

        case 'resetpassword':

            $token = $params->token;
            $password = $params->password;

            $activation_code = $token;
            $code = (decrypt($activation_code, $ENCRYPTION_KEY));

            $seguir = true;

            if (strpos($code, "_") == -1) {
                $seguir = false;
            }

            if ($seguir) {
                $usuariologId = explode("_", $code)[0];

                if (!is_numeric($usuariologId)) {
                    $response["success"] = false;
                    $response["error"] = "Ocurrio un error, comuniquese con soporte.1 ";
                } else {
                    $UsuarioLog = new UsuarioLog($usuariologId);

                    $hourdiff = round((strtotime($UsuarioLog->fechaCrea) - time()) / 3600, 2);

                    if ($seguir) {
                        if (str_replace("==", "", $UsuarioLog->getValorAntes()) != $activation_code) {
                            $response["success"] = false;
                            $response["error"] = "Ocurrio un error, comuniquese con soporte.2 ";

                            $seguir = false;
                        } else {


                            $Usuario = new Usuario ($UsuarioLog->usuarioId);
                            $Usuario->changeClave($password);

                            $response["success"] = true;

                        }
                    }
                }
            } else {
                $response["success"] = false;
                $response["error"] = "Ocurrio un error, comuniquese con soporte. ";

            }


            break;
        case 'verifyCodeReset':

            $activation_code = $params->activation_code;
            $code = (decrypt($activation_code, $ENCRYPTION_KEY));

            $seguir = true;

            if (strpos($code, "_") == -1) {
                $seguir = false;
            }

            if ($seguir) {
                $usuariologId = explode("_", $code)[0];

                if (!is_numeric($usuariologId)) {
                    $response["success"] = false;
                    $response["error"] = "Ocurrio un error, comuniquese con soporte.1 ";
                } else {
                    $UsuarioLog = new UsuarioLog($usuariologId);

                    $hourdiff = round((strtotime($UsuarioLog->fechaCrea) - time()) / 3600, 2);

                    if ($hourdiff > 24 || $UsuarioLog->getEstado() != 'P') {
                        $response["success"] = false;
                        $response["error"] = "El recurso de recuperación ha expirado.";
                        $seguir = false;

                    }

                    if ($seguir) {
                        if (str_replace("==", "", $UsuarioLog->getValorAntes()) != $activation_code) {
                            $response["success"] = false;
                            $response["error"] = "Ocurrio un error, comuniquese con soporte.2 ";

                            $seguir = false;
                        } else {

                            $UsuarioLog->setEstado('A');
                            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
                            $UsuarioLogMySqlDAO->update($UsuarioLog);
                            $UsuarioLogMySqlDAO->getTransaction()->commit();


                            $response["status"] = true;

                        }
                    }
                }
            } else {
                $response["success"] = false;
                $response["error"] = "Ocurrio un error, comuniquese con soporte. ";

            }


            break;

        case 'forgotpassword':

            $ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
            $ip = explode(",", $ip)[0];

            $email = $params->email;

            $Usuario = new Usuario();
            $Usuario->login = $email;


            if (!$Usuario->exitsLogin(1)) {
                $response["success"] = false;
                $response["error"] = "El usuario no existe. ";

            } else {
                $Usuario = new Usuario('', $email, 1);


                $UsuarioLog = new UsuarioLog();
                $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                $UsuarioLog->setUsuarioIp($ip);

                $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                $UsuarioLog->setUsuariosolicitaIp($ip);

                $UsuarioLog->setTipo("TOKENPASS");
                $UsuarioLog->setEstado("P");
                $UsuarioLog->setValorAntes('');
                $UsuarioLog->setValorDespues('');
                $UsuarioLog->setUsucreaId(0);
                $UsuarioLog->setUsumodifId(0);


                $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
                $usuariologId = $UsuarioLogMySqlDAO->insert($UsuarioLog);
                $UsuarioLogMySqlDAO->getTransaction()->commit();

                $code = (encrypt($usuariologId . "_" . time(), $ENCRYPTION_KEY));


                $UsuarioLog->setValorAntes($code);
                $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
                $usuariologId = $UsuarioLogMySqlDAO->update($UsuarioLog);
                $UsuarioLogMySqlDAO->getTransaction()->commit();


                $email = $Usuario->login;

                //Arma el mensaje para el usuario que se registra
                $mensaje_txt = "Recientemente usted solicito un cambio de contrase&#241;a, es muy f&#225;cil, solo haz click en el siguiente boton y puede cambiar la contrase&#241;a.<br><br> ";
                $mensaje_txt = $mensaje_txt . "<a style='text-decoration: blink;' href='https://afiliados.doradobet.com/password-reset/" . $code . "'><div style=\"
    /* height: 60px; */
    width: 200px;
    margin: 10px auto;
    background: #9E9E9E;
    border-radius: 10px;
    padding: 5px;
    color: white;
    text-transform: uppercase;
    font-weight: bold;
    text-align: center;
\">Cambiar Contrase&#241;a</div></a> <br><br>";

                $mensaje_txt = $mensaje_txt . "<p>Si no puede cambiar su contrase&#241;a, por favor p&#243;ngase en contacto con nosotros.</p><br><br>";
                $mensaje_txt = $mensaje_txt . "<p style='text-align: left;'><b>Equipo afiliados.</b> </p>" . "<br>";
                $mensaje_txt = $mensaje_txt . "<p style='text-align: left;'>Doradobet. </p>" . "";

                $email = 'danielftg@hotmail.com';
                //Destinatarios
                $destinatarios = $email;

                //Envia el mensaje de correo
                $envio = EnviarCorreo($destinatarios, 'admin@doradobet.com', 'Doradobet', 'Recuperacion de clave de afiliados', 'mail_registro.php', 'Recuperaci&#243;n de clave de afiliados', $mensaje_txt, $dominio, $compania, $color_email);

                $response["status"] = true;
                $response["result"] = true;
                $response["success"] = true;

            }


            break;

        /**
         * Login
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case 'Login':

            $plataforma = 1;
            if (strpos($params->username, '@')) {
                //VAFILV
                $usuario = "" . $params->username;

            } else {
                $usuario = $params->username;
                $plataforma = 0;
            }
            $clave = $params->password;


            $seguir = true;

            if ($clave == "" || $usuario == "") {
                //$usuario = $params->username;
                //$clave = $params->password;

                if ($clave == "" || $usuario == "") {

                    $response["HasError"] = true;
                    $response["AlertType"] = "danger";
                    $response["AlertMessage"] = "Invalid Username and/or password ('.$e->getCode().')";
                    $response["ModelErrors"] = [];
                    $seguir = false;

                }
            }


            if ($seguir) {
                $Usuario = new Usuario();


                $responseU = $Usuario->login($usuario, $clave, $plataforma);

                $response["HasError"] = false;
                $response["AlertType"] = "success";
                $response["AlertMessage"] = "";
                $response["ModelErrors"] = [];
                $response["redirect_url"] = "https://afiliados.doradobet.com/app/#/dashboard";


                header('Authentication: ' . $responseU->auth_token);

                $response["Data"] = array(
                    "AuthenticationStatus" => 0,
                    "PermissionList" => array(),
                );


            }

            break;

        /**
         * sendCorreo
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "sendCorreo":


            $email = "danielftg@hotmail.com";
            $code = (encrypt($email, $ENCRYPTION_KEY));

            //Arma el mensaje para el usuario que se registra
            $mensaje_txt = "Bienvenido a afiliados.  Por favor ingresa a este link  <a href='https://admin.doradobet.com/affiliates/activate>Aquí</a> o en el siguiente vinculo ";
            $mensaje_txt = $mensaje_txt . "<a href='https://admin.doradobet.com/affiliates/activate'>https://admin.doradobet.com/affiliates/activate</a> e ingresa el siguiente codigo para verificar tu correo electronico: <br><br>";
            $mensaje_txt = $mensaje_txt . "<b>Código: </b> " . $code . "<br><br>";

            $mensaje_txt = $mensaje_txt . "Nota importante: sugerimos que una vez acceda al sistema por primera vez, cambie la clave inmediatamente; ademas como recomendacion adicional, asegure su cuenta cambiando dicha clave regularmente." . "<br><br>";

            //Destinatarios
            $destinatarios = $email;

            //Envia el mensaje de correo
            $envio = EnviarCorreo($destinatarios, 'admin@doradobet.com', 'Doradobet', 'Bienvenido a Afiliados Doradobet', 'mail_registro.php', 'Bienvenido a Afiliados Doradobet', $mensaje_txt, $dominio, $compania, $color_email);


            break;

        /**
         * verify
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "verify":


            $activation_code = $params->activation_code;

            $decode = decrypt($activation_code);

            try {
                $Usuario = new Usuario("", "" . $decode);

                if ($Usuario->verifCorreo == "N") {
                    $Usuario->verifCorreo = "S";

                    $UsuarioMySqlDAO = new UsuarioMySqlDAO();

                    $UsuarioMySqlDAO->update($Usuario);
                    $UsuarioMySqlDAO->getTransaction()->commit();

                    //Arma el mensaje para el usuario que se registra
                    $mensaje_txt = "Saludos, la validación ha sido exitosa; puedes ingresar cuando la cuenta sea activada por nuestros operadores. ";

                    //Destinatarios
                    $destinatarios = $decode;

                    //Envia el mensaje de correo
                    $envio = EnviarCorreo($destinatarios, 'admin@doradobet.com', 'Doradobet', 'Afiliados Doradobet - Validación exitosa', 'mail_registro.php', 'Validación exitosa', $mensaje_txt, $dominio, $compania, $color_email);


                    $response["success"] = true;

                } else {
                    $response["success"] = false;

                }

            } catch (Exception $e) {
                $response["success"] = false;

            }


            break;

        /**
         * register
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "register":


            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . trim(file_get_contents('php://input'));
//Save string to log, use FILE_APPEND to append.

            fwriteCustom('log_' . date("Y-m-d") . '.log',$log);


            $country = $params->country;
            $currency = ($params->currency != 'PEN' && $params->currency != 'USD' && $params->currency != 'EUR') ? "PEN" : $params->currency;
            $email = "" . $params->email;
            $password = $params->password;
            $site = $params->site;
            $skype = $params->skype;
            $name = $params->name;
            $lastname = $params->lastname;
            $phone = $params->phone;


            $Address = '';
            $CityId = 0;
            $CountryId = 0;
            $CurrencyId = $currency;
            $DocumentLegalID = $params->DocumentLegalID;
            $Email = $email;
            $GroupId = 0;
            $IP = '000:000:000:000';
            $Latitud = 0;
            $Longitud = 0;
            $ManagerDocument = '';
            $ManagerName = '';
            $ManagerPhone = '00';
            $MobilePhone = $phone;
            $Name = $name;
            $Login = $email;
            $Phone = $phone;
            $RegionId = 0;

            $Type = 1;
            $tipoUsuario = "";
            $seguir = true;


            if ($Type == 1) {
                $tipoUsuario = 'AFILIADOR';
            }

            $RepresentLegalDocument = '';
            $RepresentLegalName = '';
            $RepresentLegalPhone = '';


            $Address = $Address;
            $CurrencyId = $CurrencyId;
            $Email = $Email;
            $FirstName = $Name;
            $Id = $params->Id;
            $IsSuspended = false;
            $LastLoginIp = "";
            $LastLoginLocalDate = "";
            $LastName = "";
            $clave = '';
            $SystemName = '';
            $UserId = '';
            $UserName = $email;
            $Phone = $phone;

            $login = $Login;
            $Password = $password;

            $Usuario = new Usuario();
            $Usuario->login = $login;


            if ($Usuario->exitsLogin(1)) {
                $response["success"] = false;
                $response["error"] = array(
                    "email" => "El email ya esta en uso, por favor ingresa otro diferente. ");

            } elseif ($Id != "" && $UserId != "" && $seguir) {

            } elseif ($seguir) {


                $CanReceipt = false;
                $CanDeposit = false;
                $CanActivateRegister = false;

                if ($CanReceipt == true) {
                    $CanReceipt = 'S';
                } else {
                    $CanReceipt = 'N';
                }

                if ($CanDeposit == true) {
                    $CanDeposit = 'S';
                } else {
                    $CanDeposit = 'N';
                }

                if ($CanActivateRegister == true) {
                    $CanActivateRegister = 'S';
                } else {
                    $CanActivateRegister = 'N';
                }

                $Consecutivo = new Consecutivo("", "USU", "");

                $consecutivo_usuario = $Consecutivo->numero;

                $consecutivo_usuario++;

                $ConsecutivoMySqlDAO = new ConsecutivoMySqlDAO();

                $Consecutivo->setNumero($consecutivo_usuario);


                $ConsecutivoMySqlDAO->update($Consecutivo);

                $ConsecutivoMySqlDAO->getTransaction()->commit();

                $PuntoVenta = new PuntoVenta($CashDeskId);

                $countryId = 0;
                try {
                    $Pais = new Pais("", $country);

                    $countryId = $Pais->paisId;
                } catch (Exception $e) {

                }


                $Usuario->usuarioId = $consecutivo_usuario;


                $Usuario->nombre = $FirstName;

                $Usuario->estado = 'I';

                $Usuario->fechaUlt = date('Y-m-d H:i:s');

                $Usuario->claveTv = '';

                $Usuario->estadoAnt = 'I';

                $Usuario->intentos = 0;

                $Usuario->estadoEsp = 'A';

                $Usuario->observ = '';

                $Usuario->dirIp = '';

                $Usuario->eliminado = 'N';

                $Usuario->mandante = '0';

                $Usuario->usucreaId = '0';

                $Usuario->usumodifId = '0';

                $Usuario->claveCasino = '';
                $token_itainment = GenerarClaveTicket2(12);

                $Usuario->tokenItainment = $token_itainment;

                $Usuario->fechaClave = '';

                $Usuario->retirado = '';

                $Usuario->fechaRetiro = '';

                $Usuario->horaRetiro = '';

                $Usuario->usuretiroId = '0';

                $Usuario->bloqueoVentas = 'N';

                $Usuario->infoEquipo = '';

                $Usuario->estadoJugador = 'AC';

                $Usuario->tokenCasino = '';

                $Usuario->sponsorId = 0;

                $Usuario->verifCorreo = 'N';

                $Usuario->paisId = $countryId;

                $Usuario->moneda = $currency;

                $Usuario->idioma = 'ES';

                $Usuario->permiteActivareg = $CanActivateRegister;

                $Usuario->test = 'N';

                $Usuario->tiempoLimitedeposito = '0';

                $Usuario->tiempoAutoexclusion = '0';

                $Usuario->cambiosAprobacion = 'S';

                $Usuario->timezone = '-5';

                $Usuario->puntoventaId = 0;

                $Usuario->fechaCrea = date('Y-m-d H:i:s');

                $Usuario->origen = 0;

                $Usuario->fechaValida = date('Y-m-d H:i:s');
                $Usuario->estadoValida = 'N';
                $Usuario->usuvalidaId = 0;
                $Usuario->fechaValida = date('Y-m-d H:i:s');
                $Usuario->estadoValida = 'N';
                $Usuario->contingencia = 'I';
                $Usuario->contingenciaDeportes = 'I';
                $Usuario->contingenciaCasino = 'I';
                $Usuario->contingenciaCasvivo = 'I';
                $Usuario->contingenciaVirtuales = 'I';
                $Usuario->contingenciaPoker = 'I';
                $Usuario->restriccionIp = 'I';
                $Usuario->ubicacionLongitud = '';
                $Usuario->ubicacionLatitud = '';
                $Usuario->usuarioIp = '';
                $Usuario->tokenGoogle = 'I';
                $Usuario->tokenLocal = 'I';
                $Usuario->saltGoogle = '';
                $Usuario->skype = $skype;
                $Usuario->plataforma = 1;


                $Usuario->fechaActualizacion = $Usuario->fechaCrea;
                $Usuario->documentoValidado = "A";
                $Usuario->fechaDocvalido = $Usuario->fechaCrea;
                $Usuario->usuDocvalido = 0;

                $UsuarioConfig = new UsuarioConfig();
                $UsuarioConfig->permiteRecarga = $CanDeposit;
                $UsuarioConfig->pinagent = '';
                $UsuarioConfig->reciboCaja = $CanReceipt;
                $UsuarioConfig->mandante = 0;
                $UsuarioConfig->usuarioId = $consecutivo_usuario;


                $Concesionario = new Concesionario();

                if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
                    $UsuarioPerfil = new UsuarioPerfil();
                    $UsuarioPerfil->usuarioId = $consecutivo_usuario;

                    $UsuarioPerfil->perfilId = $tipoUsuario;
                    $UsuarioPerfil->mandante = 0;
                    $UsuarioPerfil->pais = 'N';
                    $UsuarioPerfil->global = 'N';


                    // $ConcesionarioU = new Concesionario($_SESSION["usuario"], 0);

                    $Concesionario->setUsupadreId($_SESSION["usuario"]);
                    $Concesionario->setUsuhijoId($consecutivo_usuario);
                    $Concesionario->setusupadre2Id(0);
                    $Concesionario->setusupadre3Id(0);
                    $Concesionario->setusupadre4Id(0);
                    $Concesionario->setPorcenhijo(0);
                    $Concesionario->setPorcenpadre1(0);
                    $Concesionario->setPorcenpadre2(0);
                    $Concesionario->setPorcenpadre3(0);
                    $Concesionario->setPorcenpadre4(0);
                    $Concesionario->setProdinternoId(0);
                    $Concesionario->setMandante(0);
                    $Concesionario->setUsucreaId(0);
                    $Concesionario->setUsumodifId(0);
                    $Concesionario->setEstado("A");
                } elseif ($_SESSION["win_perfil"] == "CONCESIONARIO2") {
                    $UsuarioPerfil = new UsuarioPerfil();
                    $UsuarioPerfil->usuarioId = $consecutivo_usuario;
                    $UsuarioPerfil->perfilId = $tipoUsuario;
                    $UsuarioPerfil->mandante = 0;
                    $UsuarioPerfil->pais = 'N';
                    $UsuarioPerfil->global = 'N';


                    $ConcesionarioU = new Concesionario($_SESSION["usuario"], 0);

                    $Concesionario->setUsupadreId($ConcesionarioU->getUsupadreId());
                    $Concesionario->setUsuhijoId($consecutivo_usuario);
                    $Concesionario->setusupadre2Id($_SESSION["usuario"]);
                    $Concesionario->setusupadre3Id(0);
                    $Concesionario->setusupadre4Id(0);
                    $Concesionario->setPorcenhijo(0);
                    $Concesionario->setPorcenpadre1(0);
                    $Concesionario->setPorcenpadre2(0);
                    $Concesionario->setPorcenpadre3(0);
                    $Concesionario->setPorcenpadre4(0);
                    $Concesionario->setProdinternoId(0);
                    $Concesionario->setMandante(0);
                    $Concesionario->setUsucreaId(0);
                    $Concesionario->setUsumodifId(0);
                    $Concesionario->setEstado("A");
                } else {

                    $UsuarioPerfil = new UsuarioPerfil();
                    $UsuarioPerfil->usuarioId = $consecutivo_usuario;
                    $UsuarioPerfil->perfilId = $tipoUsuario;
                    $UsuarioPerfil->mandante = 0;
                    $UsuarioPerfil->pais = 'N';
                    $UsuarioPerfil->global = 'N';

                    $Concesionario->setUsupadreId(0);
                    $Concesionario->setUsuhijoId($consecutivo_usuario);
                    $Concesionario->setusupadre2Id(0);
                    $Concesionario->setusupadre3Id(0);
                    $Concesionario->setusupadre4Id(0);
                    $Concesionario->setPorcenhijo(0);
                    $Concesionario->setPorcenpadre1(0);
                    $Concesionario->setPorcenpadre2(0);
                    $Concesionario->setPorcenpadre3(0);
                    $Concesionario->setPorcenpadre4(0);
                    $Concesionario->setProdinternoId(0);
                    $Concesionario->setMandante(0);
                    $Concesionario->setUsucreaId(0);
                    $Concesionario->setUsumodifId(0);
                    $Concesionario->setEstado("A");
                }


                $UsuarioPremiomax = new UsuarioPremiomax();


                $UsuarioPremiomax->usuarioId = $consecutivo_usuario;

                $UsuarioPremiomax->premioMax = 0;

                $UsuarioPremiomax->usumodifId = 0;

                $UsuarioPremiomax->fechaModif = "";

                $UsuarioPremiomax->cantLineas = 0;

                $UsuarioPremiomax->premioMax1 = 0;

                $UsuarioPremiomax->premioMax2 = 0;

                $UsuarioPremiomax->premioMax3 = 0;

                $UsuarioPremiomax->apuestaMin = 0;

                $UsuarioPremiomax->valorDirecto = 0;

                $UsuarioPremiomax->premioDirecto = 0;

                $UsuarioPremiomax->mandante = 0;

                $UsuarioPremiomax->optimizarParrilla = "N";

                $UsuarioPremiomax->textoOp1 = "";

                $UsuarioPremiomax->textoOp2 = "";

                $UsuarioPremiomax->urlOp2 = "";

                $UsuarioPremiomax->textoOp3 = 0;

                $UsuarioPremiomax->urlOp3 = 0;

                $UsuarioPremiomax->valorEvento = 0;

                $UsuarioPremiomax->valorDiario = 0;


                $PuntoVenta = new PuntoVenta();
                $PuntoVenta->descripcion = $site;
                $PuntoVenta->nombreContacto = $ManagerName;
                $PuntoVenta->ciudadId = $CityId->Id;
                $PuntoVenta->ciudadId = $CityId;
                $PuntoVenta->direccion = $Address;
                $PuntoVenta->barrio = '';
                $PuntoVenta->telefono = $Phone;
                $PuntoVenta->email = $Email;
                $PuntoVenta->periodicidadId = 0;
                $PuntoVenta->clasificador1Id = 0;
                $PuntoVenta->clasificador2Id = 0;
                $PuntoVenta->clasificador3Id = 0;
                $PuntoVenta->valorRecarga = 0;
                $PuntoVenta->valorCupo = '0';
                $PuntoVenta->valorCupo2 = '0';
                $PuntoVenta->porcenComision = '0';
                $PuntoVenta->porcenComision2 = '0';
                $PuntoVenta->estado = 'A';
                $PuntoVenta->usuarioId = '0';
                $PuntoVenta->mandante = 0;
                $PuntoVenta->moneda = $CurrencyId;
                //$PuntoVenta->moneda = $CurrencyId->Id;
                $PuntoVenta->idioma = 'ES';
                $PuntoVenta->cupoRecarga = 0;
                $PuntoVenta->creditosBase = 0;
                $PuntoVenta->creditos = 0;
                $PuntoVenta->creditosAnt = 0;
                $PuntoVenta->creditosBaseAnt = 0;
                $PuntoVenta->usuarioId = $consecutivo_usuario;


                $UsuarioMySqlDAO = new UsuarioMySqlDAO();

                $UsuarioMySqlDAO->insert($Usuario);


                $UsuarioConfigMySqlDAO = new UsuarioConfigMySqlDAO($UsuarioMySqlDAO->getTransaction());

                $ConcesionarioMySqlDAO = new ConcesionarioMySqlDAO($UsuarioMySqlDAO->getTransaction());

                $ConcesionarioMySqlDAO->insert($Concesionario);

                $UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO($UsuarioMySqlDAO->getTransaction());

                $UsuarioPerfilMySqlDAO->insert($UsuarioPerfil);

                $UsuarioPremiomaxMySqlDAO = new UsuarioPremiomaxMySqlDAO($UsuarioMySqlDAO->getTransaction());

                $UsuarioPremiomaxMySqlDAO->insert($UsuarioPremiomax);

                $UsuarioConfigMySqlDAO->insert($UsuarioConfig);

                $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($UsuarioMySqlDAO->getTransaction());
                $PuntoVentaMySqlDAO->insert($PuntoVenta);

                $UsuarioMySqlDAO->getTransaction()->commit();


                $UsuarioMySqlDAO->updateClave($Usuario, $Password);

                $response["id"] = $consecutivo_usuario;

                $response["success"] = true;


                //Arma el mensaje para el usuario que se registra
                $mensaje_txt = "Bienvenido al plan de afiliados de Doradobet. Los operadores verificaran la información y activaran la cuenta, le avisaremos cuando esta este activada. ";

                //Destinatarios
                $destinatarios = $params->email;

                //Envia el mensaje de correo
                $envio = EnviarCorreo($destinatarios, 'admin@doradobet.com', 'Doradobet', 'Afiliados Doradobet - Registro exitoso', 'mail_registro.php', 'Bienvenido', $mensaje_txt, $dominio, $compania, $color_email);


            } else {
                $response["HasError"] = true;
                $response["AlertType"] = "success";
                $response["AlertMessage"] = "Error no puede crearlo";
                $response["ModelErrors"] = [];

            }


            break;

        /**
         * getWidgetsData
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "getWidgetsData":


            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            $filter = $params->filter;
            $action = $filter->action;

            $dateFilter1 = date("Y-m-d 00:00:00", strtotime('-1 days'));
            $dateFilter2 = date("Y-m-d 23:59:59", strtotime('-1 days'));

            $dateFilterFrom = date("Y-m-d H:i:s", strtotime($filter->date->from));
            // $dateFilterFrom = "2018-01-01 00::s";
            $dateFilterTo = date("Y-m-d 23:59:59", strtotime($filter->date->to));


            $productsReportPlayersTotal = array(array(
                "administrativeCost" => 0,
                "deposit" => 0,
                "bets" => 0,
                "wins" => 0,
                "grossRevenue" => 0,
                "expences" => 0,
                "convertedBonuses" => 0,
                "netRevenue" => 0,
                "bonus" => 0,
                "tax" => 0,
                "commission" => 0,

            ));


            $TotalProducsStatistics = array();


            //Obtenemos los montos de los productos en las fechas
            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 1000;
            }

            $rules = [];
            array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => $dateFilterFrom, "op" => "ge"));
            array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => $dateFilterTo, "op" => "le"));

            array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);

            $UsucomisionResumen = new UsucomisionResumen();
            $UsucomisionResumens = $UsucomisionResumen->getUsucomisionResumenGroupCustom(" SUM(usucomision_resumen.comision) totalcomision,SUM(usucomision_resumen.valor) total,usucomision_resumen.tipo,producto_interno.abreviado ", "usucomision_resumen.tipo", "asc ", $SkeepRows, $MaxRows, $json, true, "usucomision_resumen.tipo");
            $UsucomisionResumens = json_decode($UsucomisionResumens);

            $final = array();
            $array1 = array(

                "bettingGames " => "0",
                "bettingGamesCommission " => "0",
                "bettingGamesProfit " => "0",
                "brandId " => "0",
                "brandIdCommission " => "0",
                "brandIdProfit " => "0",
                "liveGames " => "0",
                "liveGamesCommission " => "0",
                "liveGamesProfit " => "0",
                "nativePoker " => "0",
                "nativePokerCommission " => "0",
                "nativePokerProfit " => "0",
                "poolBettingGames " => "0",
                "poolBettingGamesCommission " => "0",
                "poolBettingGamesProfit " => "0",
                "skillGames " => "0",
                "skillGamesCommission " => "0",
                "skillGamesProfit " => "0",
                "slots " => "0",
                "slotsCommission " => "0",
                "slotsProfit " => "0",
                "sportsbook " => "0",
                "sportsbookCommission " => "0",
                "sportsbookProfit " => "0",
                "tableGames " => "0",
                "tableGamesCommission " => "0",
                "tableGamesProfit " => "0",
                "total " => "0",
                "totalCommission " => "0",
                "totalProfit " => "0",
                "videoPoker " => "0",
                "videoPokerCommission " => "0",
                "videoPokerProfit " => "0",
                "virtualGames " => "0",
                "virtualGamesCommission " => "0",
                "virtualGamesProfit " => "0"

            );
            foreach ($UsucomisionResumens->data as $key => $value) {


                switch ($value->{'producto_interno.abreviado'}) {

                    case "BETSPORT":
                        $productsReportPlayersTotal[0]["bets"] = $productsReportPlayersTotal[0]["bets"] + $value->{'.total'};
                        $array1["sportsbook"] = $array1["sportsbook"] + $value->{'.total'};
                        $array1["total"] = $array1["total"] + $value->{'.total'};
                        //$array1["sportsbookProfit"] = $array1[0]["sportsbookProfit"] + $value->{'.total'};
                        break;


                    case "WINSPORT":
                        $productsReportPlayersTotal[0]["wins"] = $productsReportPlayersTotal[0]["wins"] + $value->{'.total'};
                        $array1["sportsbook"] = $array1["sportsbook"] - $value->{'.total'};
                        $array1["total"] = $array1["total"] - $value->{'.total'};

                        // $array1["sportsbookProfit"] = $array1[0]["sportsbookProfit"] + $value->{'.total'};
                        break;

                    case "DEPOSITO":
                        $productsReportPlayersTotal[0]["deposit"] = $productsReportPlayersTotal[0]["deposit"] + $value->{'.total'};

                        break;


                }


            }

            array_push($TotalProducsStatistics, $array1);


            $productsReportByPlayersTotals = array();


            //Obtenemos el monto por producto por fecha


            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 1000;
            }

            $rules = [];
            array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => $dateFilterFrom, "op" => "ge"));
            array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => $dateFilterTo, "op" => "le"));
            array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);

            $UsucomisionResumen = new UsucomisionResumen();
            $UsucomisionResumens = $UsucomisionResumen->getUsucomisionResumenGroupCustom(" SUM(usucomision_resumen.comision) totalcomision,SUM(usucomision_resumen.valor) total,usucomision_resumen.tipo,producto_interno.abreviado,DATE_FORMAT(usucomision_resumen.fecha_crea,'%Y-%m-%d') fecha ", "DATE_FORMAT(usucomision_resumen.fecha_crea,'%Y-%m-%d')", "asc ", $SkeepRows, $MaxRows, $json, true, "DATE_FORMAT(usucomision_resumen.fecha_crea,'%Y-%m-%d'),usucomision_resumen.tipo");
            $UsucomisionResumens = json_decode($UsucomisionResumens);

            $final = array();
            $fecha = "";

            foreach ($UsucomisionResumens->data as $key => $value) {

                if ($fecha != $value->{'.fecha'}) {
                    if ($fecha != "") {
                        array_push($final, $array1);
                    }
                    $fecha = $value->{'.fecha'};
                    $array1 = array(
                        "administrativeCost" => "0",
                        "bets" => "0",
                        "bonus" => "0",
                        "commission" => "0",
                        "convertedBonuses" => "0",
                        "date" => $fecha,
                        "deposit" => "0",
                        "expences" => "0",
                        "grossRevenue" => "0",
                        "netRevenue" => "0",
                        "tax" => "0",
                        "wins" => "0"
                    );

                }


                switch ($value->{'producto_interno.abreviado'}) {

                    case "BETSPORT":
                        $array1["bets"] = $array1["bets"] + $value->{'.total'};
                        break;

                    case "WINSPORT":
                        $array1["wins"] = $array1["wins"] + $value->{'.total'};
                        break;

                    case "DEPOSITO":
                        $array1["deposit"] = $array1["deposit"] + $value->{'.total'};

                        break;


                }


            }

            array_push($final, $array1);


            //Obtenemos TOP Jugadores
            $productsReportByPlayersTotals = $final;

            $MediaStat = array();


            //Obtenemos el monto por producto por fecha


            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 1000;
            }

            $rules = [];
            array_push($rules, array("field" => "usumarketing_resumen.fecha_crea", "data" => $dateFilterFrom, "op" => "ge"));
            array_push($rules, array("field" => "usumarketing_resumen.fecha_crea", "data" => $dateFilterTo, "op" => "le"));
            array_push($rules, array("field" => "usumarketing_resumen.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);

            $UsumarketingResumen = new UsumarketingResumen();
            $UsumarketingResumens = $UsumarketingResumen->getUsumarketingResumenCustom(" SUM(usumarketing_resumen.valor) total,usumarketing_resumen.tipo,DATE_FORMAT(usumarketing_resumen.fecha_crea,'%Y-%m-%d') fecha ", "DATE_FORMAT(usumarketing_resumen.fecha_crea,'%Y-%m-%d')", "asc ", $SkeepRows, $MaxRows, $json, true, "DATE_FORMAT(usumarketing_resumen.fecha_crea,'%Y-%m-%d'),usumarketing_resumen.tipo", "DATE_FORMAT(usumarketing_resumen.fecha_crea, '%Y-%m-%d'),usumarketing_resumen.tipo");
            $UsumarketingResumens = json_decode($UsumarketingResumens);

            $final = array();
            $fecha = "";
            $array1 = array();

            foreach ($UsumarketingResumens->data as $key => $value) {

                if ($fecha != $value->{'.fecha'}) {
                    if ($fecha != "") {
                        array_push($final, $array1);
                    }
                    $fecha = $value->{'.fecha'};
                    $array1 = array(
                        "date" => $fecha
                    );

                }


                switch ($value->{'usumarketing_resumen.tipo'}) {

                    case "LINKVISIT":
                        $array1["visits"] = $array1["visits"] + $value->{'.total'};
                        break;

                    case "CLICKBANNER":
                        $array1["clicks"] = $array1["clicks"] + $value->{'.total'};
                        break;


                }


            }

            array_push($final, $array1);

            //Obtenemos TOP Jugadores
            $MediaStat = $final;


            $TopUsers = array();

            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 1000;
            }

            $rules = [];
            array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => $dateFilterFrom, "op" => "ge"));
            array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => $dateFilterTo, "op" => "le"));
            array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);

            $UsucomisionResumen = new UsucomisionResumen();
            $UsucomisionResumens = $UsucomisionResumen->getUsucomisionResumenGroupCustom(" SUM(usucomision_resumen.comision) totalcomision,SUM(usucomision_resumen.valor) total,usucomision_resumen.usuarioref_id ", "usucomision_resumen.usuarioref_id", "asc ", $SkeepRows, $MaxRows, $json, true, "usucomision_resumen.usuarioref_id");
            $UsucomisionResumens = json_decode($UsucomisionResumens);


            foreach ($UsucomisionResumens->data as $key => $value) {
                $array = array(
                    "playerId" => $value->{'usucomision_resumen.usuarioref_id'},
                    "profit" => $value->{'.total'},
                    "commission" => $value->{'.totalcomision'}
                );

                array_push($TopUsers, $array);


            }

            $sumClick = 0;
            $sumClickAyer = 0;
            $sumClickTodos = 0;

            $sumRegistro = 0;
            $sumRegistroAyer = 0;
            $sumRegistroTodos = 0;

            $sumComision = 0;
            $sumComisionAyer = 0;
            $sumComisionTodos = 0;


            //Obtenemos el marketing en las fechas


            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 1000;
            }

            $rules = [];
            array_push($rules, array("field" => "usumarketing_resumen.fecha_crea", "data" => $dateFilterFrom, "op" => "ge"));
            array_push($rules, array("field" => "usumarketing_resumen.fecha_crea", "data" => $dateFilterTo, "op" => "le"));
            array_push($rules, array("field" => "usumarketing_resumen.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);

            $UsumarketingResumen = new UsumarketingResumen();
            $UsuarioMarketings = $UsumarketingResumen->getUsumarketingResumenGroupCustom(" SUM(usumarketing_resumen.valor) total,usumarketing_resumen.tipo ", "usumarketing_resumen.tipo", "asc ", $SkeepRows, $MaxRows, $json, true, "usumarketing_resumen.tipo");
            $UsuarioMarketings = json_decode($UsuarioMarketings);


            foreach ($UsuarioMarketings->data as $key => $value) {

                switch ($value->{'usumarketing_resumen.tipo'}) {
                    case "LINKVISIT":

                        $sumClick = $value->{'.total'};

                        break;

                    case "CLICKBANNER":

                        $sumClick = $value->{'.total'};

                        break;


                    case "REGISTRO":

                        $sumRegistro = $value->{'.total'};

                        break;


                }

            }


            //Obtenemos los clicks

            $rules = [];

            array_push($rules, array("field" => "usumarketing_resumen.fecha_crea", "data" => $dateFilter1, "op" => "ge"));
            array_push($rules, array("field" => "usumarketing_resumen.fecha_crea", "data" => $dateFilter2, "op" => "le"));
            array_push($rules, array("field" => "usumarketing_resumen.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));


            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);


            $UsumarketingResumen = new UsumarketingResumen();
            $UsuarioMarketings = $UsumarketingResumen->getUsumarketingResumenGroupCustom(" SUM(usumarketing_resumen.valor) total,usumarketing_resumen.tipo ", "usumarketing_resumen.tipo", "asc ", $SkeepRows, $MaxRows, $json, true, "usumarketing_resumen.tipo");
            $UsuarioMarketings = json_decode($UsuarioMarketings);


            foreach ($UsuarioMarketings->data as $key => $value) {

                switch ($value->{'usumarketing_resumen.tipo'}) {
                    case "CLICKBANNER":

                        $sumClickTodos = $value->{'.total'};
                        $sumClickAyer = $value->{'.total'};

                        break;

                    case "REGISTRO":

                        $sumRegistroAyer = $value->{'.total'};
                        $sumRegistroTodos = $value->{'.total'};

                        break;


                }

            }

            //Obtenemos los clicks

            $rules = [];

            array_push($rules, array("field" => "usumarketing_resumen.fecha_crea", "data" => $dateFilter1, "op" => "ge"));
            array_push($rules, array("field" => "usumarketing_resumen.fecha_crea", "data" => $dateFilter2, "op" => "le"));
            array_push($rules, array("field" => "usumarketing_resumen.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));


            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);


            $UsumarketingResumen = new UsumarketingResumen();
            $UsuarioMarketings = $UsumarketingResumen->getUsumarketingResumenGroupCustom(" SUM(usumarketing_resumen.valor) total,usumarketing_resumen.tipo ", "usumarketing_resumen.tipo", "asc ", $SkeepRows, $MaxRows, $json, true, "usumarketing_resumen.tipo");
            $UsuarioMarketings = json_decode($UsuarioMarketings);


            foreach ($UsuarioMarketings->data as $key => $value) {

                switch ($value->{'usumarketing_resumen.tipo'}) {
                    case "CLICKBANNER":

                        $sumClickTodos = $value->{'.total'};

                        break;

                    case "REGISTRO":

                        $sumRegistroTodos = $value->{'.total'};

                        break;


                }

            }


            $MaxRows = 1;
            $OrderedItem = 1;
            $SkeepRows = 0;

            $rules = [];
            array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => $dateFilter1, "op" => "ge"));
            array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => $dateFilter2, "op" => "le"));
            array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);

            $UsucomisionResumen = new UsucomisionResumen();
            $UsucomisionResumens = $UsucomisionResumen->getUsucomisionResumenGroupCustom(" SUM(usucomision_resumen.comision) totalcomision,SUM(usucomision_resumen.valor) total ", "usucomision_resumen.usuario_id", "asc ", $SkeepRows, $MaxRows, $json, true, "usucomision_resumen.usuario_id");
            $UsucomisionResumens = json_decode($UsucomisionResumens);

            $sumComisionAyer = $UsucomisionResumens->data[0]->{'.totalcomision'};
            $sumComision = $UsucomisionResumens->data[0]->{'.totalcomision'};
            $sumComisionTodos = $UsucomisionResumens->data[0]->{'.totalcomision'};

            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "charts" => array(
                    // Grafico de estadisticas
                    "getMediaStats" => array(
                        array(
                            "activeMedia" => 0,
                            "sumClick" => $sumClick,
                            "sumSignUps" => $sumRegistro,
                            "sumUnique" => 0,
                            "sumView" => 0,

                        )

                    ),
                    "getTotalProductStatistics" => $TotalProducsStatistics,
                    "productsReportByPlayersTotals" => array(
                        "records" => $productsReportByPlayersTotals

                    ),
                    "MediaStat" => array(
                        "records" => $MediaStat

                    ),
                    "getUsersStatistics" => array(
                        array(
                            "count" => 0,
                            "name" => "signUps",
                            "title" => "Sign Ups",

                        ),
                        array(
                            "count" => 0,
                            "name" => "depositing",
                            "title" => "Depositing",

                        ), array(
                            "count" => 0,
                            "name" => "firstDepositing",
                            "title" => "firstDepositing",

                        ), array(
                            "count" => 0,
                            "name" => "activeUsers",
                            "title" => "activeUsers",

                        ), array(
                            "count" => 0,
                            "name" => "firstActiveUsers",
                            "title" => "firstActiveUsers",

                        ),

                    )
                ),
                "widgets" => array(
                    "activeBannersCount" => array(
                        "activeBannersCount" => 0,
                        "yesterdayBannersCount" => $sumClickAyer,

                        "allBannersCount" => $sumClickTodos

                    ),

                    "commissionsForYesterday" => array(
                        "allCommission" => $sumComisionAyer,
                        "yesterdayCommission" => $sumComisionTodos

                    ),
                    "getTopUsers" => $TopUsers,
                    "getNewRegisteredPlayersCount" => array(
                        "count" => $sumRegistroAyer,
                        "totalPlayers" => $sumRegistroTodos

                    ),
                    "productsReportByPlayersTotals" => array(
                        "records" => $productsReportPlayersTotal,
                        "titles" => "",
                        "total" => "",
                        "totalRecordsCount" => "1",
                    ),
                    "getAcceptedWithdrawCount" => array(
                        "count" => "0",
                        "total" => "$ 0"

                    ),
                    "getDeniedWithdrawCount" => array(
                        "count" => "0",
                        "total" => "$ 0"

                    ),
                    "getPendingWithdrawCount" => array(
                        "count" => "0",
                        "total" => "$ 0"

                    ),

                )
            );

            $response["notification"] = array();

            break;


        case "getKpi2":

            $agentId = $params->agentId;

            if ($agentId == 0) {
                $agentId = "";
            }

            $arrayf = array();

            $rules = [];

            if ($agentId != "") {
                array_push($rules, array("field" => "data.afiliador_id", "data" => "$agentId", "op" => "eq"));

            }

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);


            $Usuario = new Usuario();
            $usuarios = $Usuario->getUsuariosResumenAfiliadosCustom(" data.tipo, SUM(ayer) ayer, SUM(mes_actual) mes_actual, SUM(mes_anterior) mes_anterior, SUM(acumulado_anio) acumulado_anio ", "usuario.usuario_id", "asc", 0, 100000, $json, true, "tipo");

            $usuarios = json_decode($usuarios);

            foreach ($usuarios->data as $datum) {
                $array = array(
                    "Text" => $datum->{'data.tipo'},
                    "Yesterday" => number_format($datum->{'.ayer'}, 2),
                    "CurrentMonth" => number_format($datum->{'.mes_actual'}, 2),
                    "LastMonth" => number_format($datum->{'.mes_anterior'}, 2),
                    "AccumulatedCurrentYear" => number_format($datum->{'.acumulado_anio'}, 2),
                );

                array_push($arrayf, $array);

            }


            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => $arrayf,
                "titles" => "",
                "total" => 1,
                "totalRecordsCount" => 10,

            );


            $response["notification"] = array();

            break;

        /**
         * getLandingPageNames
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "getLandingPageNames":

            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                array("name" => "Home", "id" => "0"),
                array("name" => "Deportes", "id" => "1"),
                array("name" => "Registro", "id" => "2")


            );

            $response["notification"] = array();

            break;

        /**
         * getMarketingSourcesNames
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "getMarketingSourcesNames":

            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                array("name" => "Test", "marketingSourceId" => "1"),
                array("name" => "sports", "marketingSourceId" => "2")


            );

            $response["notification"] = array();

            break;


        /**
         * createLink
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "createAutomation":

            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            $DateExpiration = $params->DateExpiration;
            $DateExpiration = date("Y-m-d H:i:s", strtotime(str_replace("T", " ", $DateExpiration)));
            $DateBegin = $params->DateBegin;
            $DateBegin = date("Y-m-d H:i:s", strtotime(str_replace("T", " ", $DateBegin)));

            $Name = $params->Name;
            $TimeAction = $params->TimeAction;
            $Trigger = $params->Trigger;
            $query = $params->query;
            $queryAction = $params->queryAction;
            $UserRepeat = $params->UserRepeat;
            $ValueType = $params->ValueType;

            foreach ($Trigger as $item) {
                $Automation = new Automation();

                $Automation->setUsuarioId(0);
                $Automation->setTipo($item);
                $Automation->setValor(json_encode($query));
                $Automation->setUsucreaId(0);
                $Automation->setUsumodifId(0);
                $Automation->setEstado('A');
                $Automation->setAccion(json_encode($queryAction));
                $Automation->setNombre($Name);
                $Automation->setDescripcion('');
                $Automation->setFechaInicio($DateExpiration);
                $Automation->setFechaFin($DateExpiration);
                $Automation->setTipoTiempo($TimeAction);
                $Automation->setValorTipo($ValueType);

                if ($UserRepeat == true) {
                    $UserRepeat = 1;
                } else {
                    $UserRepeat = 0;
                }
                $Automation->setUsuarioRepite($UserRepeat);


                $AutomationMySqlDAO = new AutomationMySqlDAO();
                $AutomationMySqlDAO->insert($Automation);
                $AutomationMySqlDAO->getTransaction()->commit();

            }


            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = "1234";
            $response["notification"] = array();

            //{"status":true,"html":"","result":"18946","notification":[]}

            break;

        /**
         * createLink
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "createLink":

            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            $linkData = $params->linkData;
            $linkName = $linkData->linkName;
            $landingPageId = (!is_numeric($linkData->landingPageId)) ? 0 : $linkData->landingPageId;
            $marketingSourceId = $linkData->marketingSourceId;
            $siteId = $linkData->siteId;
            $withBtag = $linkData->withBtag;


            $UsuarioLink = new UsuarioLink();

            $UsuarioLink->setUsuarioId($UsuarioMandante->getUsuarioMandante());
            $UsuarioLink->setUsucreaId(0);
            $UsuarioLink->setUsumodifId(0);
            $UsuarioLink->setNombre($linkName);
            $UsuarioLink->setEstado('A');
            $UsuarioLink->setLink($landingPageId);

            $UsuarioLinkMySqlDAO = new UsuarioLinkMySqlDAO();
            $UsuarioLinkMySqlDAO->insert($UsuarioLink);
            $UsuarioLinkMySqlDAO->getTransaction()->commit();


            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = "1234";
            $response["notification"] = array();

            //{"status":true,"html":"","result":"18946","notification":[]}

            break;

        /**
         * addMarketingSource
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "addMarketingSource":
            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);


            $name = $params->name;
            $site = $linkData->site;


            $UsuarioLink = new UsuarioLink();

            $UsuarioLink->setUsuarioId($UsuarioMandante->getUsuarioMandante());
            $UsuarioLink->setUsucreaId(0);
            $UsuarioLink->setUsumodifId(0);
            $UsuarioLink->setNombre($linkName);
            $UsuarioLink->setEstado('A');
            $UsuarioLink->setLink('');

            $UsuarioLinkMySqlDAO = new UsuarioLinkMySqlDAO();
            $UsuarioLinkMySqlDAO->insert($UsuarioLink);
            $UsuarioLinkMySqlDAO->getTransaction()->commit();


            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = "1234";
            $response["notification"] = array();

            //{"status":true,"html":"","result":"18946","notification":[]}

            break;

        case "AddBanner":

            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            $fileType = $params->mediaType;
            $expireDate = date("Y-m-d H:i:s", strtotime(str_replace("T", " ", $params->expirationDate)));
            $name = $params->name;
            $width = $params->width;
            $height = $params->height;
            $fileSize = $params->fileSize;
            $uploadDate = $params->uploadDate;
            $language = $params->lenguage;
            $visible = $params->visible;
            $image = $params->image;
            $region = $params->region;
            $productId = 0;


            $filetype = $fileType;

            $filename = time() . '.' . explode($filetype, "/")[1];
            $dirsave = '/home/backend/images/affiliates/' . $filename;

            $Banner = new Banner();

            $Banner->setUsuarioId($UsuarioMandante->getUsumandanteId());
            $Banner->setEstado('A');
            $Banner->setHeight($height);
            $Banner->setWidth($width);
            $Banner->setIdioma($language);
            $Banner->setNombre($name);
            $Banner->setProductointernoId(0);
            $Banner->setTipo('IMAGE');
            $Banner->setFechaExpiracion($expireDate);
            $Banner->setPublico('S');
            $Banner->setUsucreaId(0);
            $Banner->setUsumodifId(0);
            $Banner->setBsize($fileSize);
            $Banner->setFilename($filename);
            $Banner->setPaisId($region);

            $BannerMySqlDAO = new BannerMySqlDAO();
            $BannerMySqlDAO->insert($Banner);
            $BannerMySqlDAO->getTransaction()->commit();

            $data = $image;

            list($type, $data) = explode(';', $data);
            list(, $data) = explode(',', $data);
            $data = base64_decode($data);

            file_put_contents($dirsave, $data);

            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array();
            $response["notification"] = array();

            break;

        /**
         * uploadBanner
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "uploadBanner":

            $fileType = $_POST["fileType"];
            $expireDate = $_POST["expireDate"];
            $name = $_POST["name"];
            $width = $_POST["width"];
            $height = $_POST["height"];
            $fileSize = $_POST["fileSize"];
            $mediaType = $_POST["mediaType"];
            $uploadDate = $_POST["uploadDate"];
            $language = $_POST["language"];
            $visible = $_POST["visible"];
            $productId = $_POST["productId"];


            $filename = $_FILES['file']['name'];
            $filetype = $_FILES['file']['type'];

            $filename = time() . '.' . $_POST["fileType"];
            $dirsave = '/home/backend/images/affiliates/' . $filename;

            $Banner = new Banner();

            $Banner->setUsuarioId(1);
            $Banner->setEstado('A');
            $Banner->setHeight($height);
            $Banner->setWidth($width);
            $Banner->setIdioma($language);
            $Banner->setNombre($name);
            $Banner->setProductointernoId(1);
            $Banner->setTipo('IMAGE');
            $Banner->setFechaExpiracion('2018-06-06 00:00:00');
            $Banner->setPublico('S');
            $Banner->setUsucreaId(0);
            $Banner->setUsumodifId(0);
            $Banner->setBsize('19');
            $Banner->setFilename($filename);

            $BannerMySqlDAO = new BannerMySqlDAO();
            $BannerMySqlDAO->insert($Banner);
            $BannerMySqlDAO->getTransaction()->commit();

            if ($filetype == 'image/jpeg' or $filetype == 'image/png' or $filetype == 'image/gif') {
                if (move_uploaded_file($_FILES['file']['tmp_name'], $dirsave)) {

                } else {

                }

            }
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array();
            $response["notification"] = array();

            break;

        /**
         * getAffiliatePaymentSystems
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "getAffiliatePaymentSystems":

            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                array(
                    "systemId" => "369",
                    "className" => "AstroPay",
                    "fields" => '{"amount":{"type":"text","title":"Amount:"},"email":{"type":"text","title":"Email:"},"x_name":{"type":"text","title":"Name Surname:"},"x_document":{"type":"text","title":"Document number:"}}',
                    "name" => "Astropay",
                    "status" => 1,
                ),
                array(
                    "systemId" => "370",
                    "className" => "skrill",
                    "fields" => '{"email":{"type":"text","title":"Email:"},"amount":{"type":"text","title":"Amount:"}}',
                    "name" => "skrill",
                    "status" => 1,
                ),
                array(
                    "systemId" => "371",
                    "className" => "Neteller",
                    "fields" => '{"amount":{"type":"text","title":"Amount:"},"email":{"type":"text","title":"Email:"}}',
                    "name" => "Neteller",
                    "status" => 1,
                )
                /*,

                    array(
                        "systemId" => "372",
                        "className" => "EcoPayz",
                        "fields" => '{"amount":{"type":"text","title":"Amount:"},"ClientAccountNumber":{"type":"text","title":"Wallet id:"}}',
                        "name" => "EcoPayz",
                        "status" => 1,
                    ),
                    array(
                        "systemId" => "373",
                        "className" => "UpayCard",
                        "fields" => '{"amount":{"type":"text","title":"Amount"}}',
                        "name" => "UpayCard",
                        "status" => 1,
                    ),
                    array(
                        "systemId" => "897",
                        "className" => "Jeton",
                        "fields" => '{"amount":{"title":"amount"}}',
                        "name" => "Jeton",
                        "status" => 1,
                    )
                */

            );

            $response["notification"] = array();
            break;

        /**
         * getAffiliateDefaultWithdraw
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "getAffiliateDefaultWithdraw":
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array();

            $response["notification"] = array();

            break;

        /**
         * makeFavorite
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "makeFavorite":
            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            $bannerId = $params->bannerId;
            $response["notification"] = array();

            if ($bannerId != "") {
                try {
                    $UsuarioBanner = new UsuarioBanner("", $UsuarioMandante->getUsuarioMandante(), $bannerId);

                    if ($UsuarioBanner->getFavorito() == "S") {
                        $UsuarioBanner->setFavorito('N');

                    } else {
                        $UsuarioBanner->setFavorito('S');

                    }

                    $UsuarioBannerMySqlDAO = new UsuarioBannerMySqlDAO();

                    $UsuarioBannerMySqlDAO->update($UsuarioBanner);
                    $UsuarioBannerMySqlDAO->getTransaction()->commit();

                } catch (Exception $e) {
                    if ($e->getCode() == "33") {
                        $UsuarioBanner = new UsuarioBanner();
                        $UsuarioBanner->setFavorito('S');
                        $UsuarioBanner->setBannerId($bannerId);
                        $UsuarioBanner->setEstado('A');
                        $UsuarioBanner->setUsuarioId($UsuarioMandante->getUsuarioMandante());
                        $UsuarioBanner->setUsumodifId(0);
                        $UsuarioBanner->setUsucreaId(0);
                        $UsuarioBanner->setLandingId(0);
                        $UsuarioBanner->setMandante(0);

                        $UsuarioBannerMySqlDAO = new UsuarioBannerMySqlDAO();

                        $UsuarioBannerMySqlDAO->insert($UsuarioBanner);
                        $UsuarioBannerMySqlDAO->getTransaction()->commit();

                    } else {
                        throw $e;
                    }
                }
                $response["status"] = true;
                $response["html"] = "";
                $response["result"] = array();
                $response["notification"] = array();

            }

            break;

        /**
         * activateBanner
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "activateBanner":
            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            $bannerId = $params->bannerId;
            $landingPageId = $params->landingPageId;
            $withBtag = $params->withBtag;

            $response["notification"] = array();

            if ($bannerId != "") {
                try {
                    $UsuarioBanner = new UsuarioBanner("", $UsuarioMandante->getUsuarioMandante(), $bannerId);
                    $UsuarioBanner->setUsumodifId(0);
                    $UsuarioBanner->setUsucreaId(0);
                    $UsuarioBanner->setLandingId($landingPageId);
                    $UsuarioBanner->setMandante(0);


                    $UsuarioBannerMySqlDAO = new UsuarioBannerMySqlDAO();

                    $UsuarioBannerMySqlDAO->update($UsuarioBanner);
                    $UsuarioBannerMySqlDAO->getTransaction()->commit();

                } catch (Exception $e) {
                    if ($e->getCode() == "33") {
                        $UsuarioBanner = new UsuarioBanner();
                        $UsuarioBanner->setFavorito('N');
                        $UsuarioBanner->setBannerId($bannerId);
                        $UsuarioBanner->setEstado('A');
                        $UsuarioBanner->setUsuarioId($UsuarioMandante->getUsuarioMandante());
                        $UsuarioBanner->setUsumodifId(0);
                        $UsuarioBanner->setUsucreaId(0);
                        $UsuarioBanner->setLandingId($landingPageId);
                        $UsuarioBanner->setMandante(0);

                        $UsuarioBannerMySqlDAO = new UsuarioBannerMySqlDAO();

                        $UsuarioBannerMySqlDAO->insert($UsuarioBanner);
                        $UsuarioBannerMySqlDAO->getTransaction()->commit();

                    } else {
                        throw $e;
                    }
                }
                $response["status"] = true;
                $response["html"] = "";
                $response["result"] = array("script" => createScript($URL_AFFILIATES_API, $UsuarioMandante->getUsuarioMandante(), $UsuarioBanner->usubannerId));
                $response["notification"] = array();

            }


            break;

        /**
         * getMediaById
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "getMediaById":

            $mediaParams = $params->mediaParams;
            $mId = $mediaParams->mId;
            $lId = $mediaParams->lId;

            if ($mId != "") {
                $UsuarioBanner = new UsuarioBanner($mId);
                $Banner = new Banner($UsuarioBanner->getBannerId());


                $response["status"] = true;
                $response["html"] = "";
                $response["result"] = array(
                    "bannerPath" => $urlApiAfiliados . "" . $Banner->getFilename(),
                    "height" => $Banner->getHeight(),
                    "origin" => "",
                    "trackingLink" => "https://doradobet.com/#/?btag=" . encrypt($UsuarioBanner->getUsuarioId() . "_" . $UsuarioBanner->getUsubannerId(), $ENCRYPTION_KEY),
                    "typeName" => "Image",
                    "width" => $Banner->getWidth(),

                );

                $response["notification"] = array();


            } else {
                $response["status"] = true;
                $response["html"] = "";
                $response["result"] = array(
                    "bannerPath" => "",
                    "origin" => "",
                    "trackingLink" => null

                );

                $response["notification"] = array();

            }


            break;

        /**
         * setBannerStatAll
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "setBannerStatAll":


            $mId = $params->mId;
            $type = $params->type;

            if ($mId != "") {

                if ($type == "click") {
                    $UsuarioBanner = new UsuarioBanner($mId);
                    $Banner = new Banner($UsuarioBanner->getBannerId());

                    $UsuarioMarketing = new UsuarioMarketing();
                    $UsuarioMarketing->setUsuarioId($UsuarioBanner->getUsuarioId());
                    $UsuarioMarketing->setUsucreaId($UsuarioBanner->getUsuarioId());
                    $UsuarioMarketing->setUsumodifId($UsuarioBanner->getUsuarioId());
                    $UsuarioMarketing->setValor(1);
                    $UsuarioMarketing->setTipo('CLICKBANNER');
                    $UsuarioMarketing->setExternoId($mId);
                    $UsuarioMarketing->setIp('199.199.199.199');
                    $UsuarioMarketing->setUsuariorefId(0);
                    $UsuarioMarketing->setLinkId(0);
                    $UsuarioMarketing->setBannerId($mId);

                    $UsuarioMarketingMySqlDAO = new UsuarioMarketingMySqlDAO();
                    $UsuarioMarketingMySqlDAO->insert($UsuarioMarketing);
                    $UsuarioMarketingMySqlDAO->getTransaction()->commit();


                }

                if ($type == "linkvisit") {
                    $UsuarioLink = new UsuarioLink($mId);


                    $UsuarioMarketing = new UsuarioMarketing();
                    $UsuarioMarketing->setUsuarioId($UsuarioLink->getUsuarioId());
                    $UsuarioMarketing->setUsucreaId($UsuarioLink->getUsuarioId());
                    $UsuarioMarketing->setUsumodifId($UsuarioLink->getUsuarioId());
                    $UsuarioMarketing->setValor(1);
                    $UsuarioMarketing->setTipo('LINKVISIT');
                    $UsuarioMarketing->setExternoId($mId);
                    $UsuarioMarketing->setIp('199.199.199.199');
                    $UsuarioMarketing->setUsuariorefId(0);
                    $UsuarioMarketing->setLinkId($mId);
                    $UsuarioMarketing->setBannerId(0);

                    $UsuarioMarketingMySqlDAO = new UsuarioMarketingMySqlDAO();
                    $UsuarioMarketingMySqlDAO->insert($UsuarioMarketing);
                    $UsuarioMarketingMySqlDAO->getTransaction()->commit();


                }


                $response["status"] = true;
                $response["html"] = "";
                $response["result"] = "";
                $response["notification"] = array();

            } else {
                $response["status"] = true;
                $response["html"] = "";
                $response["result"] = "";
                $response["notification"] = array();

            }

            break;

        /**
         * stst
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "stst":
            $headers = getallheaders();

            $bt = $headers['Bt'];

            $data = decrypt($bt, "");

            $dir_ip = explode(",", $_SERVER["HTTP_X_FORWARDED_FOR"])[0];


            $mId = explode("__", $data)[1];
            $type = $params->type;

            if ($mId != "" && is_numeric($mId)) {

                $UsuarioLink = new UsuarioLink($mId);


                $UsuarioMarketing = new UsuarioMarketing();
                $UsuarioMarketing->setUsuarioId($UsuarioLink->getUsuarioId());
                $UsuarioMarketing->setUsucreaId($UsuarioLink->getUsuarioId());
                $UsuarioMarketing->setUsumodifId($UsuarioLink->getUsuarioId());
                $UsuarioMarketing->setValor(1);
                $UsuarioMarketing->setTipo('LINKVISIT');
                $UsuarioMarketing->setExternoId($mId);
                $UsuarioMarketing->setIp($dir_ip);
                $UsuarioMarketing->setUsuariorefId(0);
                $UsuarioMarketing->setLinkId($mId);
                $UsuarioMarketing->setBannerId(0);

                $UsuarioMarketingMySqlDAO = new UsuarioMarketingMySqlDAO();
                $UsuarioMarketingMySqlDAO->insert($UsuarioMarketing);
                $UsuarioMarketingMySqlDAO->getTransaction()->commit();


                $response["status"] = true;
                $response["html"] = "";
                $response["result"] = "";
                $response["notification"] = array();

            } else {
                $response["status"] = true;
                $response["html"] = "";
                $response["result"] = "";
                $response["notification"] = array();

            }

            break;


        case "getAgentsSystem":


            $UsuarioPerfil = new UsuarioPerfil();
            $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

            $Perfil_id = $_GET["roleId"];
            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 100000000;
            }

            $mismenus = "0";

            $rules = [];


            if ($_SESSION['PaisCond'] == "S") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
            }

            if ($_SESSION['Global'] == "N") {
                array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
            }


            if ($_SESSION["win_perfil"] == "") {
                array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json2 = json_encode($filtro);

                $usuarios = $UsuarioPerfil->getChilds(" usuario.usuario_id,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json2, true);
            } else {
                array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "AFILIADOR", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json2 = json_encode($filtro);

                $usuarios = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.usuario_id,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json2, true);

            }


            $usuarios = json_decode($usuarios);
            $arrayf = [];

            foreach ($usuarios->data as $key => $value) {
                $array = [];
                $array["id"] = $value->{"usuario.usuario_id"};
                $array["name"] = $value->{"usuario.nombre"};

                array_push($arrayf, $array);
            }


            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => $arrayf,
                "titles" => "",
                "total" => $usuarios->count[0]->{".count"},
                "totalRecordsCount" => $usuarios->count[0]->{".count"},

            );

            $response["notification"] = array();


            break;


        /**
         * getAvailableBanners
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "getAvailableBanners":

            $Banner = new Banner();
            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            $RegionId = $params->RegionId;
            $Languages = $params->Languages;

            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            $draw = $params->draw;
            $length = $params->length;
            $start = $params->start;

            $search = $params->search;

            if ($start != "") {
                $SkeepRows = $start;

            }

            if ($length != "") {
                $MaxRows = $length;

            }


            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 1000;
            }

            $rules = [];
            //array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "USUONLINE", "op" => "eq"));

            if ($RegionId != "") {
                array_push($rules, array("field" => "banner.pais_id", "data" => "$RegionId", "op" => "eq"));

            }
            if ($Languages != "") {
                array_push($rules, array("field" => "banner.idioma", "data" => "$Languages", "op" => "eq"));

            }

            if ($search != "") {
                if ($search->value != "") {
                    array_push($rules, array("field" => "banner.nombre", "data" => "$search->value", "op" => "cn"));

                }
            }

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);

            $Banner = new Banner();
            $Banners = $Banner->getBannersUsuarioCustom($UsuarioMandante->getUsuarioMandante(), " banner.*,usuario_banner.* ", "banner.fecha_crea", "desc", $SkeepRows, $MaxRows, $json, true);
            $Banners = json_decode($Banners);

            $final = array();

            foreach ($Banners->data as $key => $value) {
                $isfavorito = 0;
                $isActivate = 0;

                if ($value->{"usuario_banner.favorito"} == 'S') {
                    $isfavorito = 1;
                }
                if ($value->{"usuario_banner.usubanner_id"} != 0) {
                    $isActivate = 1;
                }

                $array = array();
                $array = array(
                    "activateBanner" => $isActivate,
                    "affiliateId" => $UsuarioMandante->getUsumandanteId(),
                    "canDelete" => 0,
                    "canEdit" => 1,
                    "ctr" => "50",
                    "expireDate" => "",
                    "favorite" => $isfavorito,
                    "filename" => $value->{"banner.filename"},
                    "height" => $value->{"banner.height"},
                    "id" => $value->{"banner.banner_id"},
                    "isPublished" => "1",
                    "language" => $value->{"banner.idioma"},
                    "languages" => $value->{"banner.idioma"},
                    "mine" => 0,
                    "name" => $value->{"banner.banner_id"},
                    "oldFileName" => "",
                    "oldType" => "",
                    "params" => "",
                    "partnerId" => "288",
                    "path" => $urlApiAfiliados . $value->{"banner.filename"},
                    "preview" => 0,
                    "productId" => "1",
                    "productName" => "Sportsbook",
                    "size" => $value->{"banner.bsize"},
                    "status" => "OK",
                    "typeId" => "2",
                    "typeName" => "Image",
                    "updateDate" => $value->{"banner.fecha_modif"},
                    "uploadDate" => $value->{"banner.fecha_crea"},
                    "username" => $UsuarioMandante->getUsuarioMandante(),
                    "width" => $value->{"banner.width"},
                );

                array_push($final, $array);

            }

            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => $final,
                "titles" => "",
                "total" => $Banners->count[0]->{".count"},
                "totalRecordsCount" => $Banners->count[0]->{".count"},

            );

            $response["notification"] = array();


            break;

        /**
         * GetBalance
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "GetBalance":
            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);
            $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

            $response["status"] = true;
            $response["result"] = $Usuario->creditosAfiliacion;

            break;

        /**
         * GetDashboards
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "GetDashboards":

            $ToDateLocal = $params->ResultToDate;
            $FromDateLocal = $params->ResultFromDate;
            $BonusDefinitionIds = $params->BonusDefinitionIds;
            $PlayerExternalId = $params->PlayerExternalId;

            $MaxRows = $params->Limit;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = ($params->Offset) * $MaxRows;

            $Id = $params->Id;
            $TypeAmount = $params->TypeAmount;
            $State = $params->State;
            $TypeReport = $params->TypeReport;
            $Currency = $params->Currency;
            $DateFrom = $params->DateFrom;
            $FromDateLocal = date("Y-m-d 00:00:00", strtotime($DateFrom));

            $DateTo = $params->DateTo;
            $ToDateLocal = date("Y-m-d 23:59:59", strtotime($DateTo));

            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            $response["DateFrom"] = $FromDateLocal;
            $response["DateTo"] = $ToDateLocal;

            $linkId = $params->linkId;
            $agentId = $params->agentId;

            if ($agentId == 0) {
                $agentId = "";
            }
            if ($linkId == 0) {
                $linkId = "";
            }

            $linkSelectId = $params->linkSelectId;


            if ($linkSelectId == 0) {
                $linkSelectId = "";
            }

            if ($Id == 0) {
                $rules = [];
                // array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));


                $fechaSql = "DATE_FORMAT(usucomision_resumen.fecha_crea,";

                switch ($TypeReport) {
                    case "0":
                        $fechaSql = $fechaSql . "'%Y-%m')";
                        break;
                    case "2":
                        $fechaSql = $fechaSql . "'%Y-%m-%d %H')";
                        break;

                    default:
                        $fechaSql = $fechaSql . "'%Y-%m-%d')";
                        break;

                }

                switch ($TypeAmount) {

                    case "1":
                        $select = "SUM(usucomision_resumen.comision) valor, " . $fechaSql . " fecha";
                        break;

                    case "2":
                        $select = "COUNT(usuario_bono.usuario_id) valor, " . $fechaSql . " fecha";
                        break;
                    default:
                        $select = "SUM(usucomision_resumen.comision) valor, " . $fechaSql . " fecha";

                        break;
                }
                array_push($rules, array("field" => "$fechaSql", "data" => "$FromDateLocal", "op" => "ge"));
                array_push($rules, array("field" => "$fechaSql", "data" => "$ToDateLocal", "op" => "le"));

                if ($linkId != "") {
                    array_push($rules, array("field" => "usucomision_resumen.externo_id", "data" => $linkId, "op" => "eq"));

                }

                if ($agentId != "") {
                    array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => $agentId, "op" => "eq"));

                } else {
                    array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

                }

                //array_push($rules, array("field" => "bono_interno.bono_id", "data" => "$IdBonus", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                if ($SkeepRows == "") {
                    $SkeepRows = 0;
                }

                if ($OrderedItem == "") {
                    $OrderedItem = 1;
                }

                if ($MaxRows == "") {
                    $MaxRows = 1000000000;
                }

                $json = json_encode($filtro);


                $UsucomisionResumen = new UsucomisionResumen();
                $UsucomisionResumens = $UsucomisionResumen->getUsucomisionResumenGroupCustom($select, "usucomision_resumen.usucomresumen_id", "asc ", $SkeepRows, $MaxRows, $json, true, $fechaSql);
                $UsucomisionResumens = json_decode($UsucomisionResumens);


                $finalLabel = [];
                $finalAmount = [];

                $amount = 0;

                foreach ($UsucomisionResumens->data as $key => $value) {
                    $array = array(
                        "start" => strtotime($value->{'.fecha'})
                    );
                    array_push($finalLabel, $array);
                    array_push($finalAmount, $value->{'.valor'});


                    $amount = $amount + $value->{'.valor'};
                }

                $final = [];
                $final["Comission"] = [];
                $final["Comission"]["Total"] = $amount;
                $final["Comission"]["Amount"] = $amount;

            }
            if ($Id == 1) {

                $rules = [];

                //array_push($rules, array("field" => "usuario_marketing.moneda", "data" => "$Currency", "op" => "eq"));

                $fechaSql2 = '';

                switch ($TypeReport) {
                    case "0":
                        $fechaSql2 = "'%Y-%m')";
                        break;
                    case "2":
                        $fechaSql2 = "'%Y-%m-%d %H')";
                        break;

                    default:
                        $fechaSql2 = "'%Y-%m-%d')";
                        break;

                }
                $SkeepRows = 0;
                $MaxRows = 100;

                $fechaSql = "DATE_FORMAT(usuario_marketing.fecha_crea," . $fechaSql2;
                $fechaSql = "usuario_marketing.fecha_crea";

                array_push($rules, array("field" => "$fechaSql", "data" => "$FromDateLocal", "op" => "ge"));
                array_push($rules, array("field" => "$fechaSql", "data" => "$ToDateLocal", "op" => "le"));

                //array_push($rules, array("field" => "bono_interno.bono_id", "data" => "$IdBonus", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                if ($SkeepRows == "") {
                    $SkeepRows = 0;
                }

                if ($OrderedItem == "") {
                    $OrderedItem = 1;
                }

                if ($MaxRows == "") {
                    $MaxRows = 1000000000;
                }

                $json = json_encode($filtro);

                if ($linkId != "") {
                    // array_push($rules, array("field" => "registro.externo_id", "data" => $linkId, "op" => "eq"));

                }

                if ($agentId != "") {
                    array_push($rules, array("field" => "usuario_marketing.usuario_id", "data" => "$agentId", "op" => "eq"));

                } else {
                    array_push($rules, array("field" => "usuario_marketing.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

                }

                switch ($TypeAmount) {

                    case "1":
                        array_push($rules, array("field" => "tipo", "data" => "CLICKBANNER", "op" => "eq"));
                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json = json_encode($filtro);

                        $fechaSql = "DATE_FORMAT(usuario_marketing.fecha_crea," . $fechaSql2;

                        $select = "SUM(usuario_marketing.valor) valor, " . $fechaSql . " fecha";
                        $UsuarioMarketing = new UsuarioMarketing();
                        $data = $UsuarioMarketing->getUsuarioMarketingCustom($select, "usuario_marketing.usumarketing_id", "asc", $SkeepRows, $MaxRows, $json, true, $fechaSql);

                        break;


                    case "2":
                        array_push($rules, array("field" => "tipo", "data" => "REGISTRO", "op" => "eq"));
                        array_push($rules, array("field" => "banner_id", "data" => "0", "op" => "ne"));
                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json = json_encode($filtro);

                        $fechaSql = "DATE_FORMAT(usuario_marketing.fecha_crea," . $fechaSql2;

                        $select = "SUM(usuario_marketing.valor) valor, " . $fechaSql . " fecha";
                        $UsuarioMarketing = new UsuarioMarketing();
                        $data = $UsuarioMarketing->getUsuarioMarketingCustom($select, "usuario_marketing.usumarketing_id", "asc", $SkeepRows, $MaxRows, $json, true, $fechaSql);

                        break;

                }

                $data = json_decode($data);

                $finalLabel = [];
                $finalAmount = [];

                foreach ($data->data as $key => $value) {
                    $array = array(
                        "start" => strtotime($value->{'.fecha'})
                    );
                    array_push($finalLabel, $array);
                    array_push($finalAmount, $value->{'.valor'});
                }

                $rules = [];
                //array_push($rules, array("field" => "usuario_marketing.moneda", "data" => "$Currency", "op" => "eq"));

                $fechaSql2 = '';

                switch ($TypeReport) {
                    case "0":
                        $fechaSql2 = "'%Y-%m')";
                        break;
                    case "2":
                        $fechaSql2 = "'%Y-%m-%d %H')";
                        break;

                    default:
                        $fechaSql2 = "'%Y-%m-%d')";
                        break;

                }
                $SkeepRows = 0;
                $MaxRows = 100;


                if ($agentId != "") {
                    array_push($rules, array("field" => "usuario_marketing.usuario_id", "data" => "$agentId", "op" => "eq"));

                } else {
                    array_push($rules, array("field" => "usuario_marketing.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

                }

                $fechaSql = "DATE_FORMAT(usuario_marketing.fecha_crea," . $fechaSql2;
                $fechaSql = "usuario_marketing.fecha_crea";

                array_push($rules, array("field" => "$fechaSql", "data" => "$FromDateLocal", "op" => "ge"));
                array_push($rules, array("field" => "$fechaSql", "data" => "$ToDateLocal", "op" => "le"));

                //array_push($rules, array("field" => "bono_interno.bono_id", "data" => "$IdBonus", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                if ($SkeepRows == "") {
                    $SkeepRows = 0;
                }

                if ($OrderedItem == "") {
                    $OrderedItem = 1;
                }

                if ($MaxRows == "") {
                    $MaxRows = 1000000000;
                }

                // array_push($rules, array("field" => "tipo", "data" => "REGISTRO", "op" => "le"));
                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $fechaSql = "DATE_FORMAT(usuario_marketing.fecha_crea," . $fechaSql2;

                $select = "SUM(usuario_marketing.valor) valor, usuario_marketing.tipo";
                $UsuarioMarketing = new UsuarioMarketing();
                $data = $UsuarioMarketing->getUsuarioMarketingCustom($select, "usuario_marketing.usumarketing_id", "asc", $SkeepRows, $MaxRows, $json, true, "usuario_marketing.tipo");
                $data = json_decode($data);


                $final = [];

                foreach ($data->data as $datum) {
                    switch ($datum->{'usuario_marketing.tipo'}) {

                        case "CLICKBANNER":
                            $final["Clicks"] = [];
                            $final["Clicks"]["Total"] = $datum->{'.valor'};
                            $final["Clicks"]["Amount"] = $datum->{'.valor'};

                            break;
                    }
                }


                $rules = [];
                //array_push($rules, array("field" => "usuario_marketing.moneda", "data" => "$Currency", "op" => "eq"));

                $fechaSql2 = '';

                switch ($TypeReport) {
                    case "0":
                        $fechaSql2 = "'%Y-%m')";
                        break;
                    case "2":
                        $fechaSql2 = "'%Y-%m-%d %H')";
                        break;

                    default:
                        $fechaSql2 = "'%Y-%m-%d')";
                        break;

                }
                $SkeepRows = 0;
                $MaxRows = 100;

                $fechaSql = "DATE_FORMAT(usuario_marketing.fecha_crea," . $fechaSql2;
                $fechaSql = "usuario_marketing.fecha_crea";

                array_push($rules, array("field" => "$fechaSql", "data" => "$FromDateLocal", "op" => "ge"));
                array_push($rules, array("field" => "$fechaSql", "data" => "$ToDateLocal", "op" => "le"));

                //array_push($rules, array("field" => "bono_interno.bono_id", "data" => "$IdBonus", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                if ($SkeepRows == "") {
                    $SkeepRows = 0;
                }

                if ($OrderedItem == "") {
                    $OrderedItem = 1;
                }

                if ($MaxRows == "") {
                    $MaxRows = 1000000000;
                }


                if ($agentId != "") {
                    array_push($rules, array("field" => "usuario_marketing.usuario_id", "data" => "$agentId", "op" => "eq"));

                } else {
                    array_push($rules, array("field" => "usuario_marketing.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

                }

                array_push($rules, array("field" => "tipo", "data" => "REGISTRO", "op" => "le"));
                array_push($rules, array("field" => "banner_id", "data" => "0", "op" => "ne"));
                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $fechaSql = "DATE_FORMAT(usuario_marketing.fecha_crea," . $fechaSql2;

                $select = "SUM(usuario_marketing.valor) valor, usuario_marketing.tipo";
                $UsuarioMarketing = new UsuarioMarketing();
                $data = $UsuarioMarketing->getUsuarioMarketingCustom($select, "usuario_marketing.usumarketing_id", "asc", $SkeepRows, $MaxRows, $json, true, "usuario_marketing.tipo");
                $data = json_decode($data);


                foreach ($data->data as $datum) {
                    switch ($datum->{'usuario_marketing.tipo'}) {
                        case "REGISTRO":
                            $final["Players"] = [];
                            $final["Players"]["Total"] = $datum->{'.valor'};
                            $final["Players"]["Amount"] = $datum->{'.valor'};

                            break;
                    }
                }


            }


            if ($Id == 2) {

                $rules = [];

                //array_push($rules, array("field" => "usuario_marketing.moneda", "data" => "$Currency", "op" => "eq"));

                $fechaSql2 = '';

                switch ($TypeReport) {
                    case "0":
                        $fechaSql2 = "'%Y-%m')";

                        break;
                    case "2":
                        $fechaSql2 = "'%Y-%m-%d %H')";
                        break;

                    default:
                        $fechaSql2 = "'%Y-%m-%d')";
                        break;

                }
                $SkeepRows = 0;
                $MaxRows = 100;

                $fechaSql = "DATE_FORMAT(usuario_marketing.fecha_crea,'%Y-%m-%d')";
                $fechaSql = "usuario_marketing.fecha_crea";

                array_push($rules, array("field" => "$fechaSql", "data" => "$FromDateLocal", "op" => "ge"));
                array_push($rules, array("field" => "$fechaSql", "data" => "$ToDateLocal", "op" => "le"));

                //array_push($rules, array("field" => "bono_interno.bono_id", "data" => "$IdBonus", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                if ($SkeepRows == "") {
                    $SkeepRows = 0;
                }

                if ($OrderedItem == "") {
                    $OrderedItem = 1;
                }

                if ($MaxRows == "") {
                    $MaxRows = 1000000000;
                }

                $json = json_encode($filtro);


                if ($linkId != "") {
                    array_push($rules, array("field" => "usuario_marketing.externo_id", "data" => $linkId, "op" => "eq"));

                }

                if ($linkSelectId != "") {
                    array_push($rules, array("field" => "usuario_marketing.externo_id", "data" => $linkSelectId, "op" => "eq"));
                }

                if ($agentId != "") {
                    array_push($rules, array("field" => "usuario_marketing.usuario_id", "data" => "$agentId", "op" => "eq"));

                } else {
                    array_push($rules, array("field" => "usuario_marketing.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

                }


                switch ($TypeAmount) {

                    case "1":
                        array_push($rules, array("field" => "tipo", "data" => "LINKVISIT", "op" => "eq"));
                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json = json_encode($filtro);

                        $fechaSql = "DATE_FORMAT(usuario_marketing.fecha_crea," . $fechaSql2;

                        $select = "SUM(usuario_marketing.valor) valor, " . $fechaSql . " fecha";
                        $UsuarioMarketing = new UsuarioMarketing();
                        $data = $UsuarioMarketing->getUsuarioMarketingCustom($select, "usuario_marketing.usumarketing_id", "asc", $SkeepRows, $MaxRows, $json, true, $fechaSql);

                        break;


                    case "2":
                        array_push($rules, array("field" => "tipo", "data" => "REGISTRO", "op" => "eq"));
                        array_push($rules, array("field" => "link_id", "data" => "0", "op" => "ne"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json = json_encode($filtro);

                        $fechaSql = "DATE_FORMAT(usuario_marketing.fecha_crea," . $fechaSql2;

                        $select = "SUM(usuario_marketing.valor) valor, " . $fechaSql . " fecha";
                        $UsuarioMarketing = new UsuarioMarketing();
                        $data = $UsuarioMarketing->getUsuarioMarketingCustom($select, "usuario_marketing.usumarketing_id", "asc", $SkeepRows, $MaxRows, $json, true, $fechaSql);

                        break;

                }

                $data = json_decode($data);

                $finalLabel = [];
                $finalAmount = [];

                foreach ($data->data as $key => $value) {
                    $array = array(
                        "start" => strtotime($value->{'.fecha'})
                    );
                    array_push($finalLabel, $array);
                    array_push($finalAmount, $value->{'.valor'});
                }

                $rules = [];
                //array_push($rules, array("field" => "usuario_marketing.moneda", "data" => "$Currency", "op" => "eq"));

                $fechaSql2 = '';

                switch ($TypeReport) {
                    case "0":
                        $fechaSql2 = "'%Y-%m')";
                        break;
                    case "2":
                        $fechaSql2 = "'%Y-%m-%d %H')";
                        break;

                    default:
                        $fechaSql2 = "'%Y-%m-%d')";
                        break;

                }
                $SkeepRows = 0;
                $MaxRows = 100;

                $fechaSql = "DATE_FORMAT(usuario_marketing.fecha_crea," . $fechaSql2;
                $fechaSql = "usuario_marketing.fecha_crea";

                array_push($rules, array("field" => "$fechaSql", "data" => "$FromDateLocal", "op" => "ge"));
                array_push($rules, array("field" => "$fechaSql", "data" => "$ToDateLocal", "op" => "le"));

                //array_push($rules, array("field" => "bono_interno.bono_id", "data" => "$IdBonus", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                if ($SkeepRows == "") {
                    $SkeepRows = 0;
                }

                if ($OrderedItem == "") {
                    $OrderedItem = 1;
                }

                if ($MaxRows == "") {
                    $MaxRows = 1000000000;
                }

                if ($linkId != "") {
                    array_push($rules, array("field" => "usuario_marketing.externo_id", "data" => $linkId, "op" => "eq"));

                }


                if ($linkSelectId != "") {
                    array_push($rules, array("field" => "usuario_marketing.externo_id", "data" => $linkSelectId, "op" => "eq"));
                }

                if ($agentId != "") {
                    array_push($rules, array("field" => "usuario_marketing.usuario_id", "data" => "$agentId", "op" => "eq"));

                } else {
                    array_push($rules, array("field" => "usuario_marketing.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

                }

                // array_push($rules, array("field" => "tipo", "data" => "REGISTRO", "op" => "le"));
                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $fechaSql = "DATE_FORMAT(usuario_marketing.fecha_crea," . $fechaSql2;

                $select = "SUM(usuario_marketing.valor) valor, usuario_marketing.tipo";
                $UsuarioMarketing = new UsuarioMarketing();
                $data = $UsuarioMarketing->getUsuarioMarketingCustom($select, "usuario_marketing.usumarketing_id", "asc", $SkeepRows, $MaxRows, $json, true, "usuario_marketing.tipo");
                $data = json_decode($data);


                $final = [];

                $final["Clicks"] = [];
                $final["Clicks"]["Total"] = 0;
                $final["Clicks"]["Amount"] = 0;

                foreach ($data->data as $datum) {
                    switch ($datum->{'usuario_marketing.tipo'}) {

                        case "LINKVISIT":
                            $final["Clicks"] = [];
                            $final["Clicks"]["Total"] = $datum->{'.valor'};
                            $final["Clicks"]["Amount"] = $datum->{'.valor'};

                            break;
                    }
                }


                $rules = [];
                //array_push($rules, array("field" => "usuario_marketing.moneda", "data" => "$Currency", "op" => "eq"));

                $fechaSql2 = '';

                switch ($TypeReport) {
                    case "0":
                        $fechaSql2 = "'%Y-%m')";
                        break;
                    case "2":
                        $fechaSql2 = "'%Y-%m-%d %H')";
                        break;

                    default:
                        $fechaSql2 = "'%Y-%m-%d')";
                        break;

                }
                $SkeepRows = 0;
                $MaxRows = 100;

                $fechaSql = "DATE_FORMAT(usuario_marketing.fecha_crea," . $fechaSql2;
                $fechaSql = "usuario_marketing.fecha_crea";

                array_push($rules, array("field" => "$fechaSql", "data" => "$FromDateLocal", "op" => "ge"));
                array_push($rules, array("field" => "$fechaSql", "data" => "$ToDateLocal", "op" => "le"));

                //array_push($rules, array("field" => "bono_interno.bono_id", "data" => "$IdBonus", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                if ($SkeepRows == "") {
                    $SkeepRows = 0;
                }

                if ($OrderedItem == "") {
                    $OrderedItem = 1;
                }

                if ($MaxRows == "") {
                    $MaxRows = 1000000000;
                }


                if ($linkId != "") {
                    array_push($rules, array("field" => "usuario_marketing.link_id", "data" => $linkId, "op" => "eq"));

                }


                if ($linkSelectId != "") {
                    array_push($rules, array("field" => "usuario_marketing.externo_id", "data" => $linkSelectId, "op" => "eq"));
                }


                if ($agentId != "") {
                    array_push($rules, array("field" => "usuario_marketing.usuario_id", "data" => "$agentId", "op" => "eq"));

                } else {
                    array_push($rules, array("field" => "usuario_marketing.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

                }


                array_push($rules, array("field" => "tipo", "data" => "REGISTRO", "op" => "le"));
                // array_push($rules, array("field" => "link_id", "data" => "0", "op" => "ne"));
                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $fechaSql = "DATE_FORMAT(usuario_marketing.fecha_crea," . $fechaSql2;

                $select = "SUM(usuario_marketing.valor) valor, usuario_marketing.tipo";
                $UsuarioMarketing = new UsuarioMarketing();
                $data = $UsuarioMarketing->getUsuarioMarketingCustom($select, "usuario_marketing.usumarketing_id", "asc", $SkeepRows, $MaxRows, $json, true, "usuario_marketing.tipo");
                $data = json_decode($data);

                $final["Players"] = [];
                $final["Players"]["Total"] = 0;
                $final["Players"]["Amount"] = 0;


                foreach ($data->data as $datum) {
                    switch ($datum->{'usuario_marketing.tipo'}) {
                        case "REGISTRO":
                            $final["Players"] = [];
                            $final["Players"]["Total"] = $datum->{'.valor'};
                            $final["Players"]["Amount"] = $datum->{'.valor'};

                            break;
                    }
                }


            }

            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "";
            $response["ModelErrors"] = [];

            $response["Result"] = $final;
            $response["Data"] = array(
                "Label" => $finalLabel,
                "Amount" => $finalAmount,
                "Total" => $final
            );


            break;

        /**
         * getMyMedia
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "getMyMedia":

            $Banner = new Banner();
            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);
            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 1000;
            }

            $rules = [];
            //array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "USUONLINE", "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);


            $Banners = $Banner->getBannersUsuarioCustom($UsuarioMandante->getUsuarioMandante(), " banner.*,usuario_banner.*  ", "banner.banner_id", "asc", $SkeepRows, $MaxRows, $json, true);
            $Banners = json_decode($Banners);

            $final = array();

            foreach ($Banners->data as $key => $value) {
                $isfavorito = 0;

                $isActivate = 0;

                if ($value->{"usuario_banner.favorito"} == 'S') {
                    $isfavorito = 1;
                }
                if ($value->{"usuario_banner.landing_id"} != 0) {
                    $isActivate = 1;
                }

                $array = array();
                $array = array(
                    "activateBanner" => $isActivate,
                    "affiliateId" => $UsuarioMandante->getUsumandanteId(),
                    "canDelete" => 0,
                    "canEdit" => 0,
                    "ctr" => "50",
                    "expireDate" => "",
                    "favorite" => $isfavorito,
                    "filename" => $value->{"banner.filename"},
                    "height" => $value->{"banner.height"},
                    "id" => $value->{"banner.banner_id"},
                    "isPublished" => "1",
                    "language" => $value->{"banner.idioma"},
                    "languages" => $value->{"banner.idioma"},
                    "mine" => 0,
                    "name" => $value->{"banner.banner_id"},
                    "oldFileName" => "",
                    "oldType" => "",
                    "params" => "",
                    "partnerId" => "288",
                    "path" => $urlApiAfiliados . $value->{"banner.filename"},
                    "preview" => 0,
                    "productId" => "1",
                    "productName" => "Sportsbook",
                    "size" => $value->{"banner.bsize"},
                    "status" => "OK",
                    "typeId" => "2",
                    "typeName" => "Image",
                    "updateDate" => $value->{"banner.fecha_modif"},
                    "uploadDate" => $value->{"banner.fecha_crea"},
                    "username" => $UsuarioMandante->getNombres(),
                    "width" => $value->{"banner.width"},
                );

                array_push($final, $array);

            }

            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => $final,
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => oldCount($final),

            );

            $response["notification"] = array();

            break;

        /**
         * getActiveBanners
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "getActiveBanners":

            $Banner = new Banner();
            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            $draw = $params->draw;
            $length = $params->length;
            $start = $params->start;

            if ($start != "") {
                $SkeepRows = $start;

            }

            if ($length != "") {
                $MaxRows = $length;

            }

            $columns = $params->columns;
            $order = $params->order;


            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 1000;
            }

            $rules = [];
            /*            array_push($rules, array("field" => "usuario_banner.landing_id", "data" => "0", "op" => "ne"));*/
            array_push($rules, array("field" => "usuario_banner.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);


            $Banners = $Banner->getBannersUsuarioCustom($UsuarioMandante->getUsuarioMandante(), " banner.*,usuario_banner.*  ", "banner.banner_id", "asc", $SkeepRows, $MaxRows, $json, true);
            $Banners = json_decode($Banners);


            $final = array();

            foreach ($Banners->data as $key => $value) {

                $isfavorito = 0;
                $isActivate = 0;

                if ($value->{"usuario_banner.favorito"} == 'S') {
                    $isfavorito = 1;
                }
                if ($value->{"usuario_banner.landing_id"} != 0) {
                    $isActivate = 1;
                }

                $array = array();
                $array = array(
                    "activateBanner" => $isActivate,

                    "affiliateId" => 0,
                    "canDelete" => 0,
                    "canEdit" => 0,
                    "ctr" => "50",
                    "expireDate" => "",
                    "favorite" => $isfavorito,
                    "filename" => $value->{"banner.filename"},
                    "height" => $value->{"banner.height"},
                    "id" => $value->{"banner.banner_id"},
                    "isPublished" => "1",
                    "language" => $value->{"banner.idioma"},
                    "languages" => $value->{"banner.idioma"},
                    "mine" => 0,
                    "name" => $value->{"banner.banner_id"},
                    "oldFileName" => "",
                    "oldType" => "",
                    "params" => "",
                    "partnerId" => "288",
                    "path" => $urlApiAfiliados . $value->{"banner.filename"},
                    "preview" => 0,
                    "productId" => "1",
                    "productName" => "Sportsbook",
                    "size" => $value->{"banner.bsize"},
                    "status" => "OK",
                    "typeId" => "2",
                    "typeName" => "Image",
                    "updateDate" => $value->{"banner.fecha_modif"},
                    "uploadDate" => $value->{"banner.fecha_crea"},
                    "username" => $UsuarioMandante->getUsuarioMandante(),
                    "width" => $value->{"banner.width"},
                );

                array_push($final, $array);

            }

            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => $final,
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => oldCount($final),

            );

            $response["notification"] = array();

            break;

        case "GetKpi":

            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            $UsucomisionResumen = new UsucomisionResumen();

            $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace("T", " ", $params->EndDate)));
            $FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace("T", " ", $params->BeginDate)));
            $BetShopId = $params->BetShopId;
            $ClientId = $params->ClientId;
            $PaymentTypeId = $params->PaymentTypeId;
            $State = $params->State;
            $WithDrawTypeId = $params->WithDrawTypeId;
            $ByAllowDate = $params->ByAllowDate;

            $ByAllowDate = (bool)($ByAllowDate);

            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            $draw = $params->draw;
            $length = $params->length;
            $start = $params->start;

            $OrderedItem = "usucomision_resumen.fecha_crea";
            $OrderType = "asc";


            if ($start != "") {
                $SkeepRows = $start;

            }

            if ($length != "") {
                $MaxRows = $length;

            }

            $datediff = strtotime(str_replace("T", " ", $params->EndDate)) - strtotime(str_replace("T", " ", $params->BeginDate));

            $daysDiff = round($datediff / (60 * 60 * 24));


            $FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace("T", " ", $params->BeginDate) . ' + ' . intval($SkeepRows) . ' day'));

            $sum = intval($SkeepRows) + $MaxRows;
            $FromDateLocal2 = date("Y-m-d 23:59:59", strtotime(str_replace("T", " ", $params->BeginDate) . ' + ' . $sum . ' day'));

            if ($FromDateLocal2 > $ToDateLocal) {

            } else {
                $ToDateLocal = $FromDateLocal2;
            }


            $datediff2 = strtotime($ToDateLocal) - strtotime($FromDateLocal);

            $daysDiff2 = round($datediff2 / (60 * 60 * 24));


            /*
                        $columns = $params->columns;
                        $order = $params->order;


                        foreach ($order as $item) {

                            switch ($columns[$item->column]->data) {
                                case "Id":
                                    $OrderedItem = "usucomision_resumen.usucomresumen_id";
                                    $OrderType = $item->dir;
                                    break;

                                case "Date":
                                    $OrderedItem = "usucomision_resumen.fecha_crea";
                                    $OrderType = $item->dir;
                                    break;


                                case "AmountBase":
                                    $OrderedItem = "usucomision_resumen.valor_base";
                                    $OrderType = $item->dir;
                                    break;


                                case "Commissions":
                                    $OrderedItem = "usucomision_resumen.comision";
                                    $OrderType = $item->dir;
                                    break;


                                case "NetAmount":
                                    $OrderedItem = "usucomision_resumen.comision";
                                    $OrderType = $item->dir;
                                    break;

                            }

                        }*/

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 10000;
            }

            $rules = [];
            //array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "I", "op" => "eq"));

            if (!$ByAllowDate || $ByAllowDate == "false") {
                //  array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
                //  array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));

            } else {
                //    array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
                //   array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));

            }

            array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
            array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));

            array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));


            if ($State != "") {
                array_push($rules, array("field" => "usucomision_resumen.estado", "data" => "$State", "op" => "eq"));
            }


            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);


            $UsucomisionResumens = $UsucomisionResumen->getUsucomisionResumenGroupCustom(" usucomision_resumen.fecha_crea,SUM(usucomision_resumen.comision) comision ", $OrderedItem, $OrderType, $SkeepRows, $MaxRows, $json, true, "usucomision_resumen.fecha_crea");
            $UsucomisionResumens = json_decode($UsucomisionResumens);

            $final2 = array();
            $final = array();
            for ($i = 0; $i < $daysDiff2; $i++) {
                $sum = $i + intval($SkeepRows);
                $array = [];
                $array["Date"] = date("Y-m-d", strtotime(str_replace("T", " ", $params->BeginDate) . ' + ' . $sum . ' day'));
                $array["Commission"] = 0;
                $array["Clicks"] = 0;
                $array["Register"] = 0;


                array_push($final2, $array);

            }


            foreach ($final2 as $item) {

                foreach ($UsucomisionResumens->data as $key => $value) {


                    print_r($value);
                    if ($item["Date"] == date("Y-m-d", strtotime($value->{"usucomision_resumen.fecha_crea"}))) {

                        $item["Commission"] = $value->{".comision"};
                        $item["Clicks"] = 0;
                        $item["Register"] = 0;

                    }
                }
                array_push($final, $item);


            }

            $rules = [];
            array_push($rules, array("field" => "usuario_marketing.fecha_crea", "data" => $FromDateLocal, "op" => "ge"));
            array_push($rules, array("field" => "usuario_marketing.fecha_crea", "data" => $ToDateLocal, "op" => "le"));
            array_push($rules, array("field" => "usuario_marketing.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);

            $fechaSql = "DATE_FORMAT(usuario_marketing.fecha_crea,'%Y-%m-%d')";

            $select = "SUM(usuario_marketing.valor) valor, " . $fechaSql . " fecha, usuario_marketing.tipo";
            $UsuarioMarketing = new UsuarioMarketing();
            $data = $UsuarioMarketing->getUsuarioMarketingCustom($select, "usuario_marketing.usumarketing_id", "asc", $SkeepRows, $MaxRows, $json, true, "usuario_marketing.tipo," . $fechaSql);
            $data = json_decode($data);

            $final2 = $final;
            $final = array();
            $fecha = "";
            $array1 = array();

            foreach ($final2 as $item) {


                foreach ($data->data as $key => $value) {
                    if ($item["Date"] == $value->{'.fecha'}) {


                        switch ($value->{'usuario_marketing.tipo'}) {

                            case "LINKVISIT":
                                $item["Clicks"] = $item["Clicks"] + $value->{'.valor'};
                                break;

                            case "CLICKBANNER":
                                $item["Clicks"] = $item["Clicks"] + $value->{'.valor'};
                                break;

                            case "REGISTRO":
                                $item["Register"] = $item["Register"] + $value->{'.valor'};
                                break;


                        }
                    }

                }


                array_push($final, $item);


            }


            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => $final,
                "titles" => "",
                "total" => $daysDiff,
                "totalRecordsCount" => $daysDiff,

            );

            $response["notification"] = array();

            break;

        /**
         * GetCommisions
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "GetCommisions":

            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            $UsucomisionResumen = new UsucomisionResumen();

            $ToDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->ToDateLocal) . ' +1 day'));
            $FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->FromDateLocal)));
            $BetShopId = $params->BetShopId;
            $ClientId = $params->ClientId;
            $PaymentTypeId = $params->PaymentTypeId;
            $State = $params->State;
            $WithDrawTypeId = $params->WithDrawTypeId;
            $ByAllowDate = $params->ByAllowDate;

            $ByAllowDate = (bool)($ByAllowDate);

            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            $draw = $params->draw;
            $length = $params->length;
            $start = $params->start;

            $OrderedItem = "usucomision_resumen.usucomresumen_id";
            $OrderType = "desc";


            if ($start != "") {
                $SkeepRows = $start;

            }

            if ($length != "") {
                $MaxRows = $length;

            }

            $columns = $params->columns;
            $order = $params->order;


            foreach ($order as $item) {

                switch ($columns[$item->column]->data) {
                    case "Id":
                        $OrderedItem = "usucomision_resumen.usucomresumen_id";
                        $OrderType = $item->dir;
                        break;

                    case "Date":
                        $OrderedItem = "usucomision_resumen.fecha_crea";
                        $OrderType = $item->dir;
                        break;


                    case "AmountBase":
                        $OrderedItem = "usucomision_resumen.valor_base";
                        $OrderType = $item->dir;
                        break;


                    case "Commissions":
                        $OrderedItem = "usucomision_resumen.comision";
                        $OrderType = $item->dir;
                        break;


                    case "NetAmount":
                        $OrderedItem = "usucomision_resumen.comision";
                        $OrderType = $item->dir;
                        break;

                }

            }

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 10000;
            }

            $rules = [];
            //array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "I", "op" => "eq"));

            if (!$ByAllowDate || $ByAllowDate == "false") {
                //  array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
                //  array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));

            } else {
                //    array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
                //   array_push($rules, array("field" => "usucomision_resumen.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));

            }

            array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => "7073", "op" => "eq"));


            if ($State != "") {
                array_push($rules, array("field" => "usucomision_resumen.estado", "data" => "$State", "op" => "eq"));
            }


            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);


            $UsucomisionResumens = $UsucomisionResumen->getUsucomisionResumenGroupCustom(" usucomision_resumen.*,usuario.login,usuario.nombre,clasificador.* ", $OrderedItem, $OrderType, $SkeepRows, $MaxRows, $json, true, "usucomision_resumen.usucomresumen_id");
            $UsucomisionResumens = json_decode($UsucomisionResumens);


            $final = array();
            foreach ($UsucomisionResumens->data as $key => $value) {

                $array = [];

                $array["Id"] = $value->{"usucomision_resumen.usucomresumen_id"};
                $array["Date"] = $value->{"usucomision_resumen.fecha_crea"};

                $array["Description"] = $value->{"clasificador.descripcion"};
                $array["AmountBase"] = $value->{"usucomision_resumen.valor"};
                $array["Commissions"] = $value->{"usucomision_resumen.comision"};
                $array["Tax"] = 0;
                $array["NetAmount"] = $value->{"usucomision_resumen.valor"} - $array["Tax"];

                $array["State"] = "1";

                array_push($final, $array);
            }

            $count = 0;

            if (is_numeric($UsucomisionResumens->count[0]->{'.count'})) {
                $count = $UsucomisionResumens->count[0]->{'.count'};
            }

            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => $final,
                "titles" => "",
                "total" => $count,
                "totalRecordsCount" => $count,

            );

            $response["notification"] = array();

            break;

        /**
         * getMarketingSourcesReport
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "getMarketingSourcesReport":
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => array(/*array("affiliateId" => "39705", "marketingSourceId" => "7614", "name" => "First Added", "site" => "http=>\/\/luckybet.com", "date" => "2018-04-13 16=>31=>01", "click" => "0", "impressions" => "0", "signUp" => "0", "CTR" => "0.00", "CR" => "0.00")*/
                ),
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => "1",

            );

            $response["notification"] = array();


            break;

        /**
         * getPlayersLinksStatistics
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "getPlayersLinksStatistics":
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => array(/*
                    array("affiliateId" => "39705", "name" => "luckybet", "linkId" => "18946", "createDate" => "2018-06-23 22=>57=>34", "marketingSourceName" => "First Added", "website" => "https=>\/\/luckybet.com\/", "marketingSourceId" => "7614", "clickLink" => "0", "signUp" => "0", "ratio" => "0.00", "playersCount" => "0", "deposits" => "0.00", "turnover" => "0.00", "profitness" => "0.00", "commissions" => "0.00", "grossRevenue" => "0.00", "netRevenue" => "0.00", "NDDACC" => "0.00", "NDACC" => "0")*/),
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => "1",

            );

            $response["notification"] = array();


            break;

        /**
         * getMediaStatisticsPro
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "getMediaStatisticsPro":
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => array(/*
                    array("affiliateId" => "39705", "marketingSourceId" => "7614", "name" => "First Added", "site" => "http=>\/\/luckybet.com", "date" => "2018-04-13 16=>31=>01", "click" => "0", "impressions" => "0", "signUp" => "0", "CTR" => "0.00", "CR" => "0.00")*/
                ),
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => "1",

            );

            $response["notification"] = array();


            break;

        /**
         * getFavoriteBanners
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "getFavoriteBanners":


            $Banner = new Banner();
            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            $draw = $params->draw;
            $length = $params->length;
            $start = $params->start;

            if ($start != "") {
                $SkeepRows = $start;

            }

            if ($length != "") {
                $MaxRows = $length;

            }

            $columns = $params->columns;
            $order = $params->order;


            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 1000;
            }

            $rules = [];
            array_push($rules, array("field" => "usuario_banner.favorito", "data" => "S", "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);


            $Banners = $Banner->getBannersUsuarioCustom($UsuarioMandante->getUsuarioMandante(), " banner.*,usuario_banner.*  ", "banner.banner_id", "asc", $SkeepRows, $MaxRows, $json, true);
            $Banners = json_decode($Banners);

            $final = array();

            foreach ($Banners->data as $key => $value) {


                $isfavorito = 0;
                $isActivate = 0;

                if ($value->{"usuario_banner.favorito"} == 'S') {
                    $isfavorito = 1;
                }
                if ($value->{"usuario_banner.landing_id"} != 0) {
                    $isActivate = 1;
                }

                $array = array();
                $array = array(
                    "activateBanner" => $isActivate,

                    "affiliateId" => 0,
                    "canDelete" => 0,
                    "canEdit" => 0,
                    "ctr" => "50",
                    "expireDate" => "",
                    "favorite" => $isfavorito,
                    "filename" => $value->{"banner.filename"},
                    "height" => $value->{"banner.height"},
                    "id" => $value->{"banner.banner_id"},
                    "isPublished" => "1",
                    "language" => $value->{"banner.idioma"},
                    "languages" => $value->{"banner.idioma"},
                    "mine" => 0,
                    "name" => $value->{"banner.banner_id"},
                    "oldFileName" => "",
                    "oldType" => "",
                    "params" => "",
                    "partnerId" => "288",
                    "path" => $urlApiAfiliados . $value->{"banner.filename"},
                    "preview" => 0,
                    "productId" => "1",
                    "productName" => "Sportsbook",
                    "size" => $value->{"banner.bsize"},
                    "status" => "OK",
                    "typeId" => "2",
                    "typeName" => "Image",
                    "updateDate" => $value->{"banner.fecha_modif"},
                    "uploadDate" => $value->{"banner.fecha_crea"},
                    "username" => $UsuarioMandante->getUsuarioMandante(),
                    "width" => $value->{"banner.width"},
                );

                array_push($final, $array);

            }

            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => $final,
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => oldCount($final),

            );

            $response["notification"] = array();

            break;

        /**
         * createScriptForBanners
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "createScriptForBanners":

            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            $mediaId = $params->mediaId;

            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 1000;
            }

            $rules = [];
            array_push($rules, array("field" => "usuario_banner.banner_id", "data" => $mediaId, "op" => "eq"));
            array_push($rules, array("field" => "usuario_banner.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);

            $Banner = new Banner();
            $Banners = $Banner->getBannersUsuarioCustom($UsuarioMandante->getUsuarioMandante(), " banner.*,usuario_banner.*  ", "banner.banner_id", "asc", $SkeepRows, $MaxRows, $json, true);
            $Banners = json_decode($Banners);


            $final = array();

            $usubanner_id = $Banners->data[0]->{"usuario_banner.usubanner_id"};
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = createScript($URL_AFFILIATES_API, $UsuarioMandante->getUsuarioMandante(), $usubanner_id);

            $response["notification"] = array();

            break;

        /**
         * getAllLanguagesInSystem
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "getAllLanguagesInSystem":

            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                array("locale" => "ar_IQ", "name" => "Arabic", "swarmKey" => "arb", "active" => "1"), array("locale" => "az", "name" => "Azerbaijani", "swarmKey" => "aze", "active" => "0"), array("locale" => "bg", "name" => "Bulgarian", "swarmKey" => "bgr", "active" => "0"), array("locale" => "cs_CZ", "name" => "Czech", "swarmKey" => "cze", "active" => "1"), array("locale" => "de_DE", "name" => "German", "swarmKey" => "ger", "active" => "1"), array("locale" => "el_GR", "name" => "Greek", "swarmKey" => "gre", "active" => "1"), array("locale" => "en_GB", "name" => "English", "swarmKey" => "eng", "active" => "1"), array("locale" => "es_ES", "name" => "Español", "swarmKey" => "spa", "active" => "1"), array("locale" => "et", "name" => "Estonian", "swarmKey" => "est", "active" => "0"), array("locale" => "fa_IR", "name" => "Persian", "swarmKey" => "far", "active" => "1"), array("locale" => "fr_FR", "name" => "Français", "swarmKey" => "fra", "active" => "1"), array("locale" => "he_IL", "name" => "Hebrew", "swarmKey" => "heb", "active" => "1"), array("locale" => "hy", "name" => "Armenian", "swarmKey" => "arm", "active" => "0"), array("locale" => "id", "name" => "Indonesian", "swarmKey" => "ind", "active" => "0"), array("locale" => "it_IT", "name" => "Italiano", "swarmKey" => "ita", "active" => "1"), array("locale" => "ja", "name" => "Japanese", "swarmKey" => "jpn", "active" => "0"), array("locale" => "ka_GE", "name" => "Georgian", "swarmKey" => "geo", "active" => "1"), array("locale" => "ko_KR", "name" => "한국어", "swarmKey" => "kor", "active" => "1"), array("locale" => "ku", "name" => "Kurdish", "swarmKey" => "kur", "active" => "0"), array("locale" => "lt", "name" => "Lithuanian", "swarmKey" => "lit", "active" => "0"), array("locale" => "lv", "name" => "Latvian", "swarmKey" => "lav", "active" => "0"), array("locale" => "ms", "name" => "Malay", "swarmKey" => "msa", "active" => "0"), array("locale" => "nl", "name" => "Dutch", "swarmKey" => "nld", "active" => "0"), array("locale" => "no", "name" => "Norway", "swarmKey" => "nor", "active" => "0"), array("locale" => "pl_PL", "name" => "Polish", "swarmKey" => "pol", "active" => "0"), array("locale" => "pt_PT", "name" => "Portuguese", "swarmKey" => "por", "active" => "1"), array("locale" => "ro", "name" => "Romanian", "swarmKey" => "ron", "active" => "0"), array("locale" => "ru_RU", "name" => "Русский", "swarmKey" => "rus", "active" => "1"), array("locale" => "sk", "name" => "Slovak", "swarmKey" => "slo", "active" => "0"), array("locale" => "sl", "name" => "Slovene", "swarmKey" => "slv", "active" => "0"), array("locale" => "sr", "name" => "Serbian", "swarmKey" => "srp", "active" => "0"), array("locale" => "sv_SE", "name" => "Swedish", "swarmKey" => null, "active" => "1"), array("locale" => "tr_TR", "name" => "Türkçe", "swarmKey" => "tur", "active" => "1"), array("locale" => "uk", "name" => "Ukrainian", "swarmKey" => "ukr", "active" => "0"), array("locale" => "zh_CN", "name" => "Chinese", "swarmKey" => "zho", "active" => "1")

            );
            $response["result"] = array(
                array("locale" => "es_ES", "name" => "Español", "swarmKey" => "spa", "active" => "1")
            );
            $response["notification"] = array();

            break;

        /**
         * get-social-links
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "get-social-links":
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                array("id" => "1", "key" => "facebook", "name" => "Facebook", "isShared" => "1", "url" => "", "active" => "0"), array("id" => "2", "key" => "twitter", "name" => "Twitter", "isShared" => "1", "url" => "", "active" => "0"), array("id" => "3", "key" => "google-plus", "name" => "Google Plus", "isShared" => "1", "url" => "", "active" => "0"), array("id" => "4", "key" => "vkontakte", "name" => "VK", "isShared" => "0", "url" => "", "active" => "0"), array("id" => "5", "key" => "instagram", "name" => "Instagram", "isShared" => "0", "url" => "", "active" => "0"), array("id" => "6", "key" => "youtube", "name" => "Youtube", "isShared" => "0", "url" => "", "active" => "0"), array("id" => "7", "key" => "odnoklassniki", "name" => "Odnoklassniki", "isShared" => "0", "url" => "", "active" => "0"), array("id" => "8", "key" => "linkedin", "name" => "LinkedIn", "isShared" => "1", "url" => "", "active" => "0"), array("id" => "9", "key" => "vimeo", "name" => "Vimeo", "isShared" => "0", "url" => "", "active" => "0"), array("id" => "10", "key" => "telegram", "name" => "Telegram", "isShared" => "1", "url" => "", "active" => "0")
            );

            $response["notification"] = array();

            break;

        /**
         * getWithdrawLis
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "getWithdrawLis":
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => array(),
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => "0"

            );

            $response["notification"] = array();

            break;

        /**
         * getCorrectionLogStatistics
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "getCorrectionLogStatistics":
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => array(),
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => "0"

            );

            $response["notification"] = array();

            break;

        /**
         * getInboxMessages
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "getInboxMessages":

            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 100;
            }

            $mensajesEnviados = [];
            $mensajesRecibidos = [];


            $json2 = '{"rules" : [{"field" : "usuario_mensaje.usuto_id", "data": "' . $UsuarioMandante->usumandanteId . '","op":"eq"}] ,"groupOp" : "AND"}';

            $UsuarioMensaje = new UsuarioMensaje();
            $usuarios = $UsuarioMensaje->getUsuarioMensajesCustom(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "asc", $SkeepRows, $MaxRows, $json2, true);

            $usuarios = json_decode($usuarios);


            foreach ($usuarios->data as $key => $value) {

                $array = [];

                $array["text"] = $value->{"usuario_mensaje.body"};
                $array["checked"] = intval($value->{"usuario_mensaje.is_read"});
                $array["open"] = false;
                $array["date"] = $value->{"usuario_mensaje.fecha_crea"};
                $array["id"] = $value->{"usuario_mensaje.usumensaje_id"};
                $array["subject"] = $value->{"usuario_mensaje.msubject"};
                $array["thread_id"] = $value->{"usuario_mensaje.parent_id"};

                array_push($mensajesRecibidos, $array);

            }


            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => $mensajesRecibidos,
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => "0"

            );

            $response["notification"] = array();

            break;

        /**
         * getSentBoxMessages
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "getSentBoxMessages":

            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 100;
            }

            $mensajesEnviados = [];
            $mensajesRecibidos = [];


            $json2 = '{"rules" : [{"field" : "usuario_mensaje.usufrom_id", "data": "' . $UsuarioMandante->usumandanteId . '","op":"eq"}] ,"groupOp" : "AND"}';

            $UsuarioMensaje = new UsuarioMensaje();
            $usuarios = $UsuarioMensaje->getUsuarioMensajesCustom(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "asc", $SkeepRows, $MaxRows, $json2, true);

            $usuarios = json_decode($usuarios);


            foreach ($usuarios->data as $key => $value) {

                $array = [];

                $array["text"] = $value->{"usuario_mensaje.body"};
                $array["checked"] = intval($value->{"usuario_mensaje.is_read"});
                $array["date"] = 1514649066;
                $array["id"] = 123213213;
                $array["subject"] = $value->{"usuario_mensaje.msubject"};
                $array["thread_id"] = null;

                array_push($mensajesEnviados, $array);

            }

            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => $mensajesEnviados,
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => "0"

            );

            $response["notification"] = array();

            break;

        /**
         * getCommissionPlanLogs
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "getCommissionPlanLogs":
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => array(),
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => "0"

            );

            $response["notification"] = array();

            break;

        /**
         * getPartnerBonusCostsPerProduct
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "getPartnerBonusCostsPerProduct":
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => array(
                    array("percent" => 0)
                ),
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => "0"

            );

            $response["notification"] = array();

            break;

        /**
         * getCostsLogs
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "getCostsLogs":
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => array(),
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => "0"

            );

            $response["notification"] = array();

            break;

        /**
         * getTaxesList
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "getTaxesList":
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => array(),
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => "0"

            );

            $response["notification"] = array();

            break;

        /**
         * getCarryOverLogByAffiliate
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "getCarryOverLogByAffiliate":
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => array(),
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => "0"

            );

            $response["notification"] = array();

            break;

        /**
         * getAffiliateCommissionPlansByProduct
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "getAffiliateCommissionPlansByProduct":
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                array(
                    "agentMax" => "35",
                    "agentMin" => "25",
                    "parentMax" => "0",
                    "parentMin" => "0",
                    "period" => "monthly",
                    "productId" => "1",
                    "productName" => "Sportsbook"
                )
            );

            $response["notification"] = array();

            break;

        /**
         * getAffiliatesFlatFee
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "getAffiliatesFlatFee":
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "amount" => "0.00"

            );

            $response["notification"] = array();

            break;

        case "GetMyAccount":


            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);
            $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
            $Registro = new Registro('', $UsuarioMandante->getUsuarioMandante());

            $Pais = new Pais($Usuario->paisId);

            //$PuntoVenta = new PuntoVenta("",$UsuarioMandante->getUsuarioMandante());


            $MaxRows = 1;
            $OrderedItem = 1;
            $SkeepRows = 0;

            $rules = [];
            array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
            array_push($rules, array("field" => "usucomision_resumen.estado", "data" => "A", "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);

            $UsucomisionResumen = new UsucomisionResumen();
            $UsucomisionResumens = $UsucomisionResumen->getUsucomisionResumenGroupCustom(" SUM(usucomision_resumen.comision) totalcomision,SUM(usucomision_resumen.valor) total ", "usucomision_resumen.usuario_id", "asc ", $SkeepRows, $MaxRows, $json, true, "usucomision_resumen.usuario_id");
            $UsucomisionResumens = json_decode($UsucomisionResumens);


            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "addInBazalt" => "OK",
                "address" => $Registro->getDireccion(),
                "affiliateId" => $Usuario->usuarioId,
                //"BalanceAvailable" => $PuntoVenta->getCreditosBase(),
                //"BalanceCPA" => $PuntoVenta->getCreditosBase(),
                "CommissionPending" => ($UsucomisionResumens->data[0]->{'.totalcomision'} > 0 ? $UsucomisionResumens->data[0]->{'.totalcomision'} : 0),
                //"BalanceCurrent" => $PuntoVenta->getCreditosBase(),
                "affiliate_id" => null,
                "agentRole" => "0",
                "availableWallet" => "0",
                "birthday" => null,
                "cellPhone" => $Registro->getCelular(),
                "city" => "Bogota",
                "companyName" => null,
                "contactPhone" => null,
                "countryCode" => $Pais->iso,
                "country" => $Pais->paisNom,
                "email" => $Usuario->login,
                "gender" => "MALE",
                "hideCompleteNotification" => "0",
                "isAgent" => "0",
                "lastLogin" => $Usuario->fechaUlt,
                "lastName" => $Registro->getApellido1(),
                "left" => "38422",
                "level" => "1",
                "locale" => "es_ES",
                "login" => "NOK",
                "mainCurrency" => $Usuario->moneda,
                "name" => $Usuario->nombre,
                "noNegative" => null,
                "parentAffiliateId" => "0",
                "parentAffiliate_id" => "0",
                "partnerId" => "288",
                "planId" => "2",
                "planTypeId" => "0",
                "promoCode" => null,
                "registerDate" => $Registro->fechaValida,
                "registerIp" => $Usuario->dirIp,
                "reportCurrency" => null,
                "right" => "38423",
                "role" => "2",
                "secondLastName" => null,
                "secondName" => null,
                "session" => null,
                "sites" => null,
                "status" => "2",
                "terminalId" => null,
                "timezone" => "Asia/Yerevan",
                "tmpDateAvailable" => "2015-01-01 00:00:00",
                "username" => $Usuario->login,
                "verified" => "YES",
                "zipCode" => "50100"

            );

            $response["notification"] = array();

            break;

        /**
         * get-current-user-info
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "get-current-user-info":


            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);
            $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
            $Registro = new Registro('', $UsuarioMandante->getUsuarioMandante());

            $Pais = new Pais($Usuario->paisId);

            //$PuntoVenta = new PuntoVenta("",$UsuarioMandante->getUsuarioMandante());


            $MaxRows = 1;
            $OrderedItem = 1;
            $SkeepRows = 0;

            $rules = [];
            array_push($rules, array("field" => "usucomision_resumen.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
            array_push($rules, array("field" => "usucomision_resumen.estado", "data" => "A", "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);

            $UsucomisionResumen = new UsucomisionResumen();
            $UsucomisionResumens = $UsucomisionResumen->getUsucomisionResumenGroupCustom(" SUM(usucomision_resumen.comision) totalcomision,SUM(usucomision_resumen.valor) total ", "usucomision_resumen.usuario_id", "asc ", $SkeepRows, $MaxRows, $json, true, "usucomision_resumen.usuario_id");
            $UsucomisionResumens = json_decode($UsucomisionResumens);


            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "addInBazalt" => "OK",
                "address" => $Registro->getDireccion(),
                "affiliateId" => $Usuario->usuarioId,
                //"BalanceAvailable" => $PuntoVenta->getCreditosBase(),
                //"BalanceCPA" => $PuntoVenta->getCreditosBase(),
                "CommissionPending" => ($UsucomisionResumens->data[0]->{'.totalcomision'} > 0 ? $UsucomisionResumens->data[0]->{'.totalcomision'} : 0),
                //"BalanceCurrent" => $PuntoVenta->getCreditosBase(),
                "affiliate_id" => null,
                "agentRole" => "0",
                "availableWallet" => "0",
                "birthday" => null,
                "cellPhone" => $Registro->getCelular(),
                "city" => "Bogota",
                "companyName" => null,
                "contactPhone" => null,
                "countryCode" => $Pais->iso,
                "country" => $Pais->paisNom,
                "email" => $Usuario->login,
                "gender" => "MALE",
                "hideCompleteNotification" => "0",
                "isAgent" => "0",
                "lastLogin" => $Usuario->fechaUlt,
                "lastName" => $Registro->getApellido1(),
                "left" => "38422",
                "level" => "1",
                "locale" => "es_ES",
                "login" => "NOK",
                "mainCurrency" => $Usuario->moneda,
                "name" => $Usuario->nombre,
                "noNegative" => null,
                "parentAffiliateId" => "0",
                "parentAffiliate_id" => "0",
                "partnerId" => "288",
                "passHash" => "be23e4ece20a44250a651ee75488c3e09e76fa630999be0ff9f49b4abcc66f66",
                "passSalt" => "aOiusQFccFotXTXy6xyKNQ==",
                "planId" => "2",
                "planTypeId" => "0",
                "promoCode" => null,
                "registerDate" => $Registro->fechaValida,
                "registerIp" => $Usuario->dirIp,
                "reportCurrency" => null,
                "right" => "38423",
                "role" => "2",
                "secondLastName" => null,
                "secondName" => null,
                "session" => null,
                "sites" => null,
                "status" => "2",
                "terminalId" => null,
                "timezone" => "Asia/Yerevan",
                "tmpDateAvailable" => "2015-01-01 00:00:00",
                "username" => $Usuario->login,
                "verified" => "YES",
                "zipCode" => "50100"

            );

            $response["notification"] = array();

            break;

        /**
         * getPartnerAllAffiliates
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "getPartnerAllAffiliates":
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => array(
                    "addInBazalt" => "OK",
                    "address" => "calle 6",
                    "affiliateId" => "39705",
                    "affiliate_id" => null,
                    "agentRole" => "0",
                    "availableWallet" => "0",
                    "birthday" => null,
                    "cellPhone" => "3012376249",
                    "city" => "Bogota",
                    "companyName" => null,
                    "contactPhone" => null,
                    "countryCode" => "CO",
                    "email" => "danielftg@hotmail.com",
                    "gender" => "MALE",
                    "hideCompleteNotification" => "0",
                    "isAgent" => "0",
                    "lastLogin" => $Usuario->fechaUlt,
                    "lastName" => "Tamayo",
                    "left" => "38422",
                    "level" => "1",
                    "locale" => "es_ES",
                    "login" => "NOK",
                    "mainCurrency" => "USD",
                    "name" => "Daniel",
                    "noNegative" => null,
                    "parentAffiliateId" => "0",
                    "parentAffiliate_id" => "0",
                    "partnerId" => "288",
                    "passHash" => "be23e4ece20a44250a651ee75488c3e09e76fa630999be0ff9f49b4abcc66f66",
                    "passSalt" => "aOiusQFccFotXTXy6xyKNQ==",
                    "planId" => "2",
                    "planTypeId" => "0",
                    "promoCode" => null,
                    "registerDate" => $Usuario->fechaCrea,
                    "registerIp" => "162.158.122.28",
                    "reportCurrency" => null,
                    "right" => "38423",
                    "role" => "2",
                    "secondLastName" => null,
                    "secondName" => null,
                    "session" => null,
                    "sites" => null,
                    "status" => "2",
                    "terminalId" => null,
                    "timezone" => "Asia/Yerevan",
                    "tmpDateAvailable" => "2015-01-01 00:00:00",
                    "username" => "danielftg@hotmail.com",
                    "verified" => "YES",
                    "zipCode" => "50100"
                ),
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => "0"

            );

            $response["notification"] = array();

            break;

        /**
         * getProductReport
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "getProductReport":
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => array(
                    array("GGR" => "0.00",
                        "RAKE" => "",
                        "activePlayersCount" => "0",
                        "bonus" => "0.00",
                        "bonusBet" => "0.00",
                        "commission" => "0.00",
                        "expences" => "0.00",
                        "netRevenue" => "0.00",
                        "productId" => "1",
                        "productName" => "Sportsbook",
                        "totalBets" => "0.00",
                        "totalWins" => "0.00",
                    ),
                    array(
                        "GGR" => "0.00",
                        "RAKE" => "",
                        "activePlayersCount" => "0",
                        "bonus" => "0.00",
                        "bonusBet" => "0.00",
                        "commission" => "0.00",
                        "expences" => "0.00",
                        "netRevenue" => "0.00",
                        "productId" => "2",
                        "productName" => "Live Games",
                        "totalBets" => "0.00",
                        "totalWins" => "0.00",
                    )
                ),
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => "0"

            );

            $response["notification"] = array();
            break;

        /**
         * getWithdrawStatisticsForChart
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "getWithdrawStatisticsForChart":
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => array(
                    array(
                        "acceptedAmount" => 0,
                        "acceptedCount" => 0,
                        "date" => "2018-06-01",
                        "deniedAmount" => 0,
                        "deniedCount" => 0,
                        "monthDay" => "01/06",
                        "pendingAmount" => 0,
                        "pendingCount" => 0
                    ),
                    array(
                        "acceptedAmount" => 0,
                        "acceptedCount" => 0,
                        "date" => "2018-06-02",
                        "deniedAmount" => 0,
                        "deniedCount" => 0,
                        "monthDay" => "02/06",
                        "pendingAmount" => 0,
                        "pendingCount" => 0
                    ),
                    array(
                        "acceptedAmount" => 0,
                        "acceptedCount" => 0,
                        "date" => "2018-06-03",
                        "deniedAmount" => 0,
                        "deniedCount" => 0,
                        "monthDay" => "03/06",
                        "pendingAmount" => 0,
                        "pendingCount" => 0
                    ),
                    array(
                        "acceptedAmount" => 0,
                        "acceptedCount" => 0,
                        "date" => "2018-06-04",
                        "deniedAmount" => 0,
                        "deniedCount" => 0,
                        "monthDay" => "04/06",
                        "pendingAmount" => 0,
                        "pendingCount" => 0
                    ),
                    array(
                        "acceptedAmount" => 0,
                        "acceptedCount" => 0,
                        "date" => "2018-06-05",
                        "deniedAmount" => 0,
                        "deniedCount" => 0,
                        "monthDay" => "05/06",
                        "pendingAmount" => 0,
                        "pendingCount" => 0
                    ),

                    array(
                        "acceptedAmount" => 0,
                        "acceptedCount" => 0,
                        "date" => "2018-06-06",
                        "deniedAmount" => 0,
                        "deniedCount" => 0,
                        "monthDay" => "06/06",
                        "pendingAmount" => 0,
                        "pendingCount" => 0
                    ),

                    array(
                        "acceptedAmount" => 0,
                        "acceptedCount" => 0,
                        "date" => "2018-06-07",
                        "deniedAmount" => 0,
                        "deniedCount" => 0,
                        "monthDay" => "07/06",
                        "pendingAmount" => 0,
                        "pendingCount" => 0
                    ),

                    array(
                        "acceptedAmount" => 0,
                        "acceptedCount" => 0,
                        "date" => "2018-06-08",
                        "deniedAmount" => 0,
                        "deniedCount" => 0,
                        "monthDay" => "08/06",
                        "pendingAmount" => 0,
                        "pendingCount" => 0
                    ),

                    array(
                        "acceptedAmount" => 0,
                        "acceptedCount" => 0,
                        "date" => "2018-06-09",
                        "deniedAmount" => 0,
                        "deniedCount" => 0,
                        "monthDay" => "05/09",
                        "pendingAmount" => 0,
                        "pendingCount" => 0
                    ),

                    array(
                        "acceptedAmount" => 0,
                        "acceptedCount" => 0,
                        "date" => "2018-06-10",
                        "deniedAmount" => 0,
                        "deniedCount" => 0,
                        "monthDay" => "10/06",
                        "pendingAmount" => 0,
                        "pendingCount" => 0
                    ),

                    array(
                        "acceptedAmount" => 0,
                        "acceptedCount" => 0,
                        "date" => "2018-06-11",
                        "deniedAmount" => 0,
                        "deniedCount" => 0,
                        "monthDay" => "11/06",
                        "pendingAmount" => 0,
                        "pendingCount" => 0
                    )

                ),
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => "0"

            );

            $response["notification"] = array();

            break;


        case "EditCommision":


            $param = $params;
            //foreach ($params as $param) {
            if (true) {
                $Id = $param->Id;
                $Commissions = $param->Commissions;
                $ProductId = $Commissions->Id;

                $ProductName = $Commissions->ProductName;
                $ComissionLevel1 = $Commissions->ComissionLevel1;
                $ComissionLevel2 = $Commissions->ComissionLevel2;
                $ComissionLevel3 = $Commissions->ComissionLevel3;
                $ComissionLevel4 = $Commissions->ComissionLevel4;
                $ComissionLevelBetShop = $Commissions->ComissionLevelBetShop;


                $FromId = $Id;
                try {
                    $ConcesionarioU = new Concesionario($FromId);
                    $ConcesionarioAntes = new Concesionario($FromId, $ProductId);
                    $Concesionario = new Concesionario();

                    $ConcesionarioAntes->setEstado('I');

                    $Concesionario->setUsupadreId($ConcesionarioU->getUsupadreId());
                    $Concesionario->setUsuhijoId($ConcesionarioU->getUsuhijoId());
                    $Concesionario->setusupadre2Id($ConcesionarioU->getUsupadre2Id());
                    $Concesionario->setusupadre3Id($ConcesionarioU->getUsupadre3Id());
                    $Concesionario->setusupadre4Id($ConcesionarioU->getUsupadre4Id());
                    $Concesionario->setPorcenhijo($ComissionLevelBetShop);
                    $Concesionario->setPorcenpadre1($ComissionLevel1);
                    $Concesionario->setPorcenpadre2($ComissionLevel2);
                    $Concesionario->setPorcenpadre3($ComissionLevel3);
                    $Concesionario->setPorcenpadre4($ComissionLevel4);
                    $Concesionario->setProdinternoId($ProductId);
                    $Concesionario->setEstado('A');
                    $Concesionario->setMandante(0);
                    $Concesionario->setUsucreaId(0);
                    $Concesionario->setUsumodifId(0);

                    $ConcesionarioMySqlDAO = new ConcesionarioMySqlDAO();

                    $ConcesionarioMySqlDAO->getTransaction();
                    $ConcesionarioMySqlDAO->update($ConcesionarioAntes);
                    $ConcesionarioMySqlDAO->insert($Concesionario);

                    $ConcesionarioMySqlDAO->getTransaction()->commit();

                } catch (Exception $e) {
                    if ($e->getCode() == "48") {
                        $ConcesionarioU = new Concesionario($FromId);
                        $Concesionario = new Concesionario($FromId);

                        $Concesionario->setUsupadreId($ConcesionarioU->getUsupadreId());
                        $Concesionario->setUsuhijoId($ConcesionarioU->getUsuhijoId());
                        $Concesionario->setusupadre2Id($ConcesionarioU->getUsupadre2Id());
                        $Concesionario->setusupadre3Id($ConcesionarioU->getUsupadre3Id());
                        $Concesionario->setusupadre4Id($ConcesionarioU->getUsupadre4Id());
                        $Concesionario->setPorcenhijo($ComissionLevelBetShop);
                        $Concesionario->setPorcenpadre1($ComissionLevel1);
                        $Concesionario->setPorcenpadre2($ComissionLevel2);
                        $Concesionario->setPorcenpadre3($ComissionLevel3);
                        $Concesionario->setPorcenpadre4($ComissionLevel4);
                        $Concesionario->setProdinternoId($ProductId);
                        $Concesionario->setMandante(0);
                        $Concesionario->setUsucreaId(0);
                        $Concesionario->setUsumodifId(0);
                        $Concesionario->setEstado('A');

                        $ConcesionarioMySqlDAO = new ConcesionarioMySqlDAO();

                        $ConcesionarioMySqlDAO->getTransaction();
                        $ConcesionarioMySqlDAO->insert($Concesionario);

                        $ConcesionarioMySqlDAO->getTransaction()->commit();
                    } else {
                        throw $e;

                    }
                }

            }


            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "Operation has completed successfully";
            $response["ModelErrors"] = [];


            $response["Data"] = array();


            break;


        case "GetAgentComissionItems":

            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "Operation has completed successfully";
            $response["ModelErrors"] = [];

            $FromId = $params->Id;

            $result_array = array();

            $campos = "";
            $cont = 0;

            $rules = [];

            array_push($rules, array("field" => "clasificador.tipo", "data" => "PCOM", "op" => "eq"));
            array_push($rules, array("field" => "clasificador.estado", "data" => "A", "op" => "eq"));
            array_push($rules, array("field" => "clasificador.estado", "data" => "A", "op" => "eq"));
            array_push($rules, array("field" => "concesionario.estado", "data" => "DISP", "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $jsonfiltro = json_encode($filtro);


            $Concesionario = new Concesionario();
            $productos = $Concesionario->getConcesionariosProductoInternoCustom("clasificador.clasificador_id,clasificador.descripcion, concesionario.porcenpadre1,concesionario.porcenpadre2,concesionario.porcenpadre3,concesionario.porcenpadre4,concesionario.porcenhijo  ", "clasificador.clasificador_id", "asc", 0, 10000, $jsonfiltro, true, $FromId);
            $productos = json_decode($productos);


            $final = array();

            foreach ($productos->data as $producto) {

                $array = array(
                    "Id" => $producto->{"clasificador.clasificador_id"},
                    "ProductName" => $producto->{"clasificador.descripcion"},
                    "ComissionLevel1" => ($producto->{"concesionario.porcenpadre1"} == "") ? 0 : floatval($producto->{"concesionario.porcenpadre1"}),
                    "ComissionLevel2" => ($producto->{"concesionario.porcenpadre2"} == "") ? 0 : floatval($producto->{"concesionario.porcenpadre2"}),
                    "ComissionLevel3" => ($producto->{"concesionario.porcenpadre3"} == "") ? 0 : floatval($producto->{"concesionario.porcenpadre3"}),
                    "ComissionLevel4" => ($producto->{"concesionario.porcenpadre4"} == "") ? 0 : floatval($producto->{"concesionario.porcenpadre4"}),
                    "ComissionLevelBetShop" => ($producto->{"concesionario.porcenhijo"} == "") ? 0 : floatval($producto->{"concesionario.porcenhijo"})

                );
                array_push($final, $array);

            }


            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => $final,
                "titles" => "",
                "total" => oldCount($final),
                "totalRecordsCount" => oldCount($final)

            );

            break;


        /**
         * GetPlayersDashboards
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "GetPlayersDashboards":


            if ($_SESSION["usuario2"] == 5) {
                // $UsuarioMandante = new UsuarioMandante(5637);
                $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            } else {
                $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            }

            $BannerId = $params->BannerId;
            $LinkId = $params->LinkId;

            $linkId = $params->linkId;
            $agentId = $params->agentId;

            if ($agentId == 0) {
                $agentId = "";
            }

            if ($linkId == 0) {
                $linkId = "";
            }

            $Usuario = new Usuario();


            $rules = [];
            array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "USUONLINE", "op" => "eq"));


            if ($linkId != "") {
                array_push($rules, array("field" => "registro.link_id", "data" => $linkId, "op" => "eq"));

            }

            if ($agentId != "") {
                array_push($rules, array("field" => "registro.afiliador_id", "data" => $agentId, "op" => "eq"));

            } else {
                array_push($rules, array("field" => "registro.afiliador_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

            }

            if ($BannerId != "") {
                array_push($rules, array("field" => "registro.banner_id", "data" => $BannerId, "op" => "eq"));

            }

            if ($LinkId != "") {
                array_push($rules, array("field" => "registro.link_id", "data" => $LinkId, "op" => "eq"));

            }
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);


            $usuarios = $Usuario->getUsuariosCustom(" COUNT( DISTINCT (usuario.usuario_id)) usuarios ", "usuario.usuario_id", "asc", 0, 100000, $json, true);

            $usuarios = json_decode($usuarios);


            $final = [];
            $final["Players"] = [];
            $final["Players"]["Total"] = $usuarios->data[0]->{".usuarios"};

            $response["status"] = true;
            $response["html"] = "";

            $response["result"] = $final;

            $response["notification"] = array();

            break;

        case "setAutomationState":

            $Id = $params->Id;
            $State = $params->Action;
            $Observation = $params->Observation;

            if ($Id != "" && ($State == "A" || $State == "N")) {

                $UsuarioAutomation = new UsuarioAutomation($Id);

                $UsuarioAutomationMySqlDAO = new UsuarioAutomationMySqlDAO();
                $Transaction = $UsuarioAutomationMySqlDAO->getTransaction();

                if ($UsuarioAutomation->getEstado() == 'P') {


                    switch ($UsuarioAutomation->getTipo()) {

                        case "deposit_created":
                            /* $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO($Transaction);

                             $UsuarioRecarga = new UsuarioRecarga($UsuarioAutomation->getExternoId());

                             if ($State == "A") {
                                 $UsuarioRecarga->setEstado('A');

                             } elseif($State == "N") {
                                 $UsuarioRecarga->setEstado('R');

                                 $puntoventa_id = $UsuarioRecarga->getPuntoventaId();
                                 $valor = $UsuarioRecarga->getValor();

                                 $Usuario = new Usuario($UsuarioRecarga->getUsuarioId());

                                 if($puntoventa_id > 0){

                                     $FlujoCaja = new FlujoCaja();
                                     $FlujoCaja->setFechaCrea(date('Y-m-d'));
                                     $FlujoCaja->setHoraCrea(date('H:i'));
                                     $FlujoCaja->setUsucreaId($puntoventa_id);
                                     $FlujoCaja->setTipomovId('S');
                                     $FlujoCaja->setValor($valor);
                                     $FlujoCaja->setRecargaId($UsuarioRecarga->getRecargaId());
                                     $FlujoCaja->setMandante($UsuarioRecarga->getMandante());

                                     if ($FlujoCaja->getFormapago1Id() == "") {
                                         $FlujoCaja->setFormapago1Id(0);
                                     }

                                     if ($FlujoCaja->getFormapago2Id() == "") {
                                         $FlujoCaja->setFormapago2Id(0);
                                     }

                                     if ($FlujoCaja->getValorForma1() == "") {
                                         $FlujoCaja->setValorForma1(0);
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
                                     $FlujoCajaMySqlDAO->insert($FlujoCaja);

                                     $PuntoVenta = new PuntoVenta("", $puntoventa_id);

                                     $PuntoVenta->setBalanceCupoRecarga($valor);


                                     $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($Transaction);

                                     $PuntoVentaMySqlDAO->update($PuntoVenta);

                                 }
                             }

                             $UsuarioRecargaMySqlDAO->update($UsuarioRecarga);*/


                            break;


                        case "withdraw_created":
                            $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO($Transaction);

                            $CuentaCobro = new CuentaCobro($UsuarioAutomation->getExternoId());

                            if ($CuentaCobro->getEstado() == 'P') {
                                if ($State == "A") {
                                    $CuentaCobro->setEstado('A');

                                } elseif ($State == "N") {
                                    $CuentaCobro->setEstado('R');

                                    $CuentaCobro->setEstado('R');
                                    $CuentaCobro->setUsurechazaId($_SESSION['usuario2']);
                                    $CuentaCobro->setFechaCambio(date("Y-m-d H:i:s"));
                                    $CuentaCobro->setDiripCambio('');
                                    $CuentaCobro->setMensajeUsuario($ClientNotes);
                                    $CuentaCobro->setObservacion($RejectReason);

                                    if ($CuentaCobro->getUsupagoId() == "") {
                                        $CuentaCobro->setUsupagoId(0);
                                    }

                                    if ($CuentaCobro->getFechaAccion() == "") {
                                        $CuentaCobro->setFechaAccion(date("Y-m-d H:i:s"));
                                    }

                                    $Usuario = new Usuario($CuentaCobro->getUsuarioId());
                                    $UsuarioPerfil = new UsuarioPerfil($CuentaCobro->getUsuarioId());

                                    if ($UsuarioPerfil->getPerfilId() == "USUONLINE") {
                                        $Usuario->creditWin($CuentaCobro->getValor(), $CuentaCobroMySqlDAO->getTransaction());

                                        $UsuarioHistorial = new UsuarioHistorial();
                                        $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                                        $UsuarioHistorial->setDescripcion('');
                                        $UsuarioHistorial->setMovimiento('E');
                                        $UsuarioHistorial->setUsucreaId(0);
                                        $UsuarioHistorial->setUsumodifId(0);
                                        $UsuarioHistorial->setTipo(40);
                                        $UsuarioHistorial->setValor($CuentaCobro->getValor());
                                        $UsuarioHistorial->setExternoId($CuentaCobro->getCuentaId());

                                        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($CuentaCobroMySqlDAO->getTransaction());
                                        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);


                                    } else {
                                        /*$Usuario->creditosAfiliacion = "creditos_afiliacion + " . $CuentaCobro->getValor();

                                        $UsuarioMySqlDAO = new UsuarioMySqlDAO($CuentaCobroMySqlDAO->getTransaction());

                                        $UsuarioMySqlDAO->update($Usuario);*/

                                    }


                                }

                                $CuentaCobroMySqlDAO->update($CuentaCobro);

                            }


                            break;

                    }

                    if ($State == "A") {
                        if ($UsuarioAutomation->getEstado() == "P") {
                            $UsuarioAutomation->setEstado('A');
                        }

                    } elseif ($State == "N") {
                        if ($UsuarioAutomation->getEstado() == "P") {
                            $UsuarioAutomation->setEstado('R');
                        }
                    }
                    $UsuarioAutomation->setObservacion($Observation);
                    $UsuarioAutomation->setFechaAccion(date('Y-m-d H:i:s'));

                    $UsuarioAutomationMySqlDAO->update($UsuarioAutomation);
                    $Transaction->commit();

                    $response["status"] = true;
                    $response["html"] = "";
                    $response["notification"] = array();
                } else {
                    $response["status"] = false;
                    $response["html"] = "";
                    $response["AlertMessage"] = "Error en la solicitud.";

                }

            } else {
                $response["status"] = false;
                $response["html"] = "";
                $response["AlertMessage"] = "Error en la solicitud.";

            }

            break;

        case "setAgentStateValidate":

            $Id = $params->Id;
            $State = $params->Action;

            if ($Id != "" && ($State == "A" || $State == "I")) {

                $Usuario = new Usuario($Id);

                if ($State == "A") {
                    if ($Usuario->estadoValida == "N") {
                        $Usuario->estadoValida = 'A';

                        $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                        $UsuarioMySqlDAO->update($Usuario);
                        $UsuarioMySqlDAO->getTransaction()->commit();
                    }

                } else {
                    if ($Usuario->estadoValida == "N") {
                        $Usuario->estadoValida = 'I';

                        $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                        $UsuarioMySqlDAO->update($Usuario);
                        $UsuarioMySqlDAO->getTransaction()->commit();

                    }

                }

                $response["status"] = true;
                $response["html"] = "";
                $response["notification"] = array();

            } else {
                $response["status"] = false;
                $response["html"] = "";
                $response["AlertMessage"] = "Error en la solicitud.";

            }

            break;

        case "setAgentChangeExpiration":

            $Id = $params->Id;
            $MaximumCommission = $params->MaximumCommission;
            $TimeExpiration = $params->TimeExpiration;

            if ($TimeExpiration == '') {
                $TimeExpiration = 0;
            }

            if ($Id != "") {

                $Usuario = new Usuario($Id);
                $cambios = false;

                if ($MaximumCommission != '') {
                    $cambios = true;
                    $Usuario->maximaComision = $MaximumCommission;


                }

                if ($TimeExpiration != '') {
                    $cambios = true;

                    $Usuario->tiempoComision = $TimeExpiration;

                }

                if ($cambios) {
                    $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                    $UsuarioMySqlDAO->update($Usuario);
                    $UsuarioMySqlDAO->getTransaction()->commit();

                }


                $response["status"] = true;
                $response["html"] = "";
                $response["notification"] = array();

            } else {
                $response["status"] = false;
                $response["html"] = "";
                $response["AlertMessage"] = "Error en la solicitud.";

            }

            break;

        case "changeStateAgent":

            $Id = $params->Id;
            $State = $params->State;

            if ($Id != "" && ($State == true || $State == false)) {

                $Usuario = new Usuario($Id);

                if ($State) {
                    if ($Usuario->estado == "I") {
                        $Usuario->estado = 'A';

                        $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                        $UsuarioMySqlDAO->update($Usuario);
                        $UsuarioMySqlDAO->getTransaction()->commit();
                    }

                } else {
                    if ($Usuario->estado == "A") {
                        $Usuario->estado = 'I';

                        $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                        $UsuarioMySqlDAO->update($Usuario);
                        $UsuarioMySqlDAO->getTransaction()->commit();

                    }

                }

                $response["status"] = true;
                $response["html"] = "";
                $response["notification"] = array();

            } else {
                $response["status"] = false;
                $response["html"] = "";
                $response["AlertMessage"] = "Error en la solicitud.";

            }

            break;


        case "changeDragNegativeAgent":

            $Id = $params->Id;
            $DragNegative = $params->DragNegative;

            if ($Id != "" && ($DragNegative == true || $DragNegative == false)) {

                $Usuario = new Usuario($Id);

                if ($DragNegative) {
                    if ($Usuario->arrastraNegativo == "0") {
                        $Usuario->arrastraNegativo = '1';

                        $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                        $UsuarioMySqlDAO->update($Usuario);
                        $UsuarioMySqlDAO->getTransaction()->commit();
                    }

                } else {
                    if ($Usuario->arrastraNegativo == "1") {
                        $Usuario->arrastraNegativo = '0';

                        $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                        $UsuarioMySqlDAO->update($Usuario);
                        $UsuarioMySqlDAO->getTransaction()->commit();

                    }

                }

                $response["status"] = true;
                $response["html"] = "";
                $response["notification"] = array();

            } else {
                $response["status"] = false;
                $response["html"] = "";
                $response["AlertMessage"] = "Error en la solicitud.";

            }

            break;

        case "getAgentsTwoLevels":

            if ($_SESSION["win_perfil"] != "ADMINAFILIADOS" && $_SESSION["win_perfil"] != "ADMIN" && $_SESSION["win_perfil"] != "SA" && $_SESSION["win_perfil"] != "ADMIN2") {
                exit();
            }


            $UsuarioPerfil = new UsuarioPerfil();

            if ($_SESSION["usuario2"] == 5) {
                $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            } else {
                $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            }

            $Perfil_id = $_GET["roleId"];
            $Type = ($_GET["Type"] != 1 && $_GET["Type"] != 0) ? '' : $_GET["Type"];
            $Type = 1;
            $IsRegisterActivate = ($_GET["IsRegisterActivate"] != "A" && $_GET["IsRegisterActivate"] != "I" && $_GET["IsRegisterActivate"] != "N" && $_GET["IsRegisterActivate"] != "R") ? '' : $_GET["IsRegisterActivate"];
            $IsActivate = ($_GET["IsActivate"] != "A" && $_GET["IsActivate"] != "I") ? '' : $_GET["IsActivate"];

            $IsRegisterActivate = ($params->IsRegisterActivate != "A" && $params->IsRegisterActivate != "I" && $params->IsRegisterActivate != "N" && $params->IsRegisterActivate != "R") ? '' : $params->IsRegisterActivate;
            $IsActivate = ($params->IsActivate != "A" && $params->IsActivate != "I") ? '' : $params->IsActivate;
            $Name = $params->Name;

            $tipoUsuario = "";

            $draw = $params->draw;
            $length = $params->length;
            $start = $params->start;

            if ($start != "") {
                $SkeepRows = $start;
            } else {
                $SkeepRows = 0;

            }

            if ($length != "") {
                $MaxRows = $length;
            }

            $columns = $params->columns;
            $order = $params->order;


            $seguir = true;

            if ((!is_numeric($MaxRows) || !is_numeric($SkeepRows))) {
                $seguir = false;


            }

            if ($_SESSION["win_perfil"] == "PUNTOVENTA" || $_SESSION["win_perfil"] == "CAJERO") {
                $seguir = false;

            }

            /*
                        if ($SkeepRows == "") {
                            $SkeepRows = 0;
                        }

                        if ($OrderedItem == "") {
                            $OrderedItem = 1;
                        }

                        if ($MaxRows == "") {
                            $MaxRows = 100000000;
                        }*/
            if ($seguir) {

                $mismenus = "0";

                $rules = [];


                if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
                    array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                    array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));

                    if ($Type == "1") {
                        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "AFILIADOR", "op" => "eq"));

                    } else {
                        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "CONCESIONARIO2", "op" => "eq"));
                    }

                    if ($IsRegisterActivate != "") {
                        array_push($rules, array("field" => "usuario.estado_valida", "data" => "$IsRegisterActivate", "op" => "eq"));
                    }

                    if ($IsActivate != "") {
                        array_push($rules, array("field" => "usuario.estado", "data" => "$IsActivate", "op" => "eq"));
                    }

                    if ($Name != "") {
                        array_push($rules, array("field" => "usuario.nombre", "data" => "$Name", "op" => "cn"));
                    }


                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json2 = json_encode($filtro);

                    $usuarios = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.skype,usuario.usuario_id,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,usuario.fecha_ult,usuario.fecha_crea,usuario.moneda,punto_venta.*,departamento.*,ciudad.*,pais.*  ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json2, true);

                } elseif ($_SESSION["win_perfil"] == "CONCESIONARIO2") {
                    array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                    array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));

                    if ($Type == "1") {
                        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "AFILIADOR", "op" => "eq"));

                    } else {
                        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));
                    }

                    if ($IsRegisterActivate != "") {
                        array_push($rules, array("field" => "usuario.estado_valida", "data" => "$IsRegisterActivate", "op" => "eq"));
                    }

                    if ($IsActivate != "") {
                        array_push($rules, array("field" => "usuario.estado", "data" => "$IsActivate", "op" => "eq"));
                    }

                    if ($Name != "") {
                        array_push($rules, array("field" => "usuario.nombre", "data" => "$Name", "op" => "cn"));
                    }

                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json2 = json_encode($filtro);

                    $usuarios = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.skype,usuario.usuario_id,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,usuario.fecha_ult,usuario.fecha_crea,usuario.moneda,punto_venta.*,departamento.*,ciudad.*,pais.*   ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json2, true);

                } elseif ($_SESSION["win_perfil"] == "ADMINAFILIADOS") {
                    array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "AFILIADOR", "op" => "eq"));


                    if ($IsRegisterActivate != "") {
                        array_push($rules, array("field" => "usuario.estado_valida", "data" => "$IsRegisterActivate", "op" => "eq"));
                    }

                    if ($IsActivate != "") {
                        array_push($rules, array("field" => "usuario.estado", "data" => "$IsActivate", "op" => "eq"));
                    }

                    if ($Name != "") {
                        array_push($rules, array("field" => "usuario.nombre", "data" => "$Name", "op" => "cn"));
                    }


                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json2 = json_encode($filtro);

                    $usuarios = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.skype,usuario.usuario_id,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,usuario.fecha_ult,usuario.fecha_crea,usuario.moneda,punto_venta.*,departamento.*,ciudad.*,pais.*  ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json2, true);

                } else {

                    if ($Type == "1") {
                        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "AFILIADOR", "op" => "eq"));

                    } elseif (($Type == "1")) {
                        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "'CONCESIONARIO','CONCESIONARIO2'", "op" => "in"));
                    } else {
                        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "'AFILIADOR','CONCESIONARIO','CONCESIONARIO2'", "op" => "in"));

                    }

                    if ($IsRegisterActivate != "") {
                        array_push($rules, array("field" => "usuario.estado_valida", "data" => "$IsRegisterActivate", "op" => "eq"));
                    }

                    if ($IsActivate != "") {
                        array_push($rules, array("field" => "usuario.estado", "data" => "$IsActivate", "op" => "eq"));
                    }

                    if ($Name != "") {
                        array_push($rules, array("field" => "usuario.nombre", "data" => "$Name", "op" => "cn"));
                    }

                    if ($_SESSION['PaisCond'] == "S") {
                        array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
                    }
                    if ($_SESSION['Global'] == "N") {
                        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
                    }
                    array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json2 = json_encode($filtro);


                    $usuarios = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.maxima_comision,usuario.tiempo_comision,usuario.arrastra_negativo,usuario.skype,usuario.estado_valida,usuario.usuario_id,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id,usuario.moneda,usuario.fecha_ult,usuario.fecha_crea,punto_venta.*,departamento.*,ciudad.*,pais.*  ", "usuario.usuario_id", "asc", $SkeepRows, $MaxRows, $json2, true);

                }

                $usuarios = json_decode($usuarios);
                $arrayf = [];

                $balanceAgent = 0;

                foreach ($usuarios->data as $key => $value) {

                    if ($isList != 1) {

                        $array = [];
                        $array["id"] = $value->{"usuario.usuario_id"};
                        $array["Id"] = $value->{"usuario.usuario_id"};
                        $array["StateValidate"] = $value->{"usuario.estado_valida"};

                        if ($_SESSION["win_perfil2"] != "CONCESIONARIO" && $_SESSION["win_perfil2"] != "CONCESIONARIO2") {
                            $array["Action"] = $value->{"usuario.estado_valida"};

                        } else {
                            $array["Action"] = '';
                        }
                        $array["Site"] = $value->{"punto_venta.descripcion"};
                        $array["Skype"] = $value->{"punto_venta.skype"};

                        $array["State"] = $value->{"usuario.estado"};
                        $array["StateSwitch"] = ($value->{"usuario.estado"} == "A") ? true : false;

                        $array["MaximumCommission"] = $value->{"usuario.maxima_comision"};
                        $array["TimeExpiration"] = $value->{"usuario.tiempo_comision"};
                        $array["DragNegative"] = $value->{"usuario.arrastra_negativo"};

                        $array["UserName"] = str_replace("VAFILV", '', $value->{"usuario.login"});
                        $array["Name"] = $value->{"usuario.nombre"};
                        $array["Email"] = str_replace("VAFILV", "", $value->{"punto_venta.email"});
                        $array["Phone"] = $value->{"punto_venta.telefono"};
                        $array["Address"] = $value->{"punto_venta.direccion"};
                        $array["CurrencyId"] = $value->{"usuario.moneda"};
                        $array["RegionName"] = $value->{"pais.pais_nom"};
                        $array["DepartmentName"] = $value->{"departamento.depto_nom"};
                        $array["CityName"] = $value->{"ciudad.ciudad_nom"};
                        $array["SystemName"] = 22;
                        $array["IsSuspended"] = ($value->{"usuario.estado"} == 'A' ? false : true);
                        $array["AgentBalance"] = $value->{"punto_venta.creditos_base"};
                        $array["PlayerCount"] = 0;
                        $array["LastLoginDateLabel"] = $value->{"usuario.fecha_ult"};
                        $array["CreatedDate"] = $value->{"usuario.fecha_crea"};
                        $array["Children"] = array();

                        if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
                            $rules2 = array();

                            array_push($rules2, array("field" => "concesionario.usupadre_id", "data" => $value->{"usuario.usuario_id"}, "op" => "eq"));
                            array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
                            array_push($rules2, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));
                            $filtro = array("rules" => $rules2, "groupOp" => "AND");
                            $json2 = json_encode($filtro);

                            $usuariosdetalle = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.usuario_id,usuario.nombre,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json2, true);
                            $usuariosdetalle = json_decode($usuariosdetalle);


                            foreach ($usuariosdetalle->data as $key2 => $value2) {
                                $array2 = [];

                                $array2["Id"] = $value2->{"usuario.usuario_id"};

                                $array2["UserName"] = $value2->{"usuario.login"};
                                $array2["Name"] = $value2->{"usuario.nombre"};

                                $array2["SystemName"] = 22;
                                $array2["IsSuspended"] = ($value2->{"usuario.estado"} == 'A' ? false : truee);
                                $array2["AgentBalance"] = $value2->{"punto_venta.creditos_base"};
                                $array2["PlayerCount"] = 0;
                                array_push($array["Children"], $array2);


                            }

                        } elseif ($_SESSION["win_perfil"] == "CONCESIONARIO2") {

                        } else {
                            $rules2 = array();

                            array_push($rules2, array("field" => "concesionario.usupadre_id", "data" => $value->{"usuario.usuario_id"}, "op" => "eq"));
                            array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
                            array_push($rules2, array("field" => "usuario_perfil.perfil_id", "data" => "CONCESIONARIO2", "op" => "eq"));
                            $filtro = array("rules" => $rules2, "groupOp" => "AND");
                            $json2 = json_encode($filtro);

                            $usuariosdetalle = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.usuario_id,usuario.nombre,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json2, true);
                            $usuariosdetalle = json_decode($usuariosdetalle);

                            foreach ($usuariosdetalle->data as $key2 => $value2) {
                                $array2 = [];

                                $array2["Id"] = $value2->{"usuario.usuario_id"};
                                $array2["UserName"] = $value2->{"usuario.login"};
                                $array2["Name"] = $value2->{"usuario.nombre"};

                                $array2["SystemName"] = 22;
                                $array2["IsSuspended"] = ($value2->{"usuario.estado"} == 'A' ? false : truee);
                                $array2["AgentBalance"] = $value2->{"punto_venta.creditos_base"};
                                $array2["PlayerCount"] = 0;
                                $array["LastLoginDateLabel"] = $value2->{"usuario.fecha_ult"};
                                $array2["Children"] = array();

                                if (true) {
                                    $rules3 = array();

                                    array_push($rules3, array("field" => "concesionario.usupadre_id", "data" => $value->{"usuario.usuario_id"}, "op" => "eq"));
                                    array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
                                    array_push($rules3, array("field" => "usuario_perfil.perfil_id", "data" => "CONCESIONARIO2", "op" => "eq"));
                                    $filtro = array("rules" => $rules3, "groupOp" => "AND");
                                    $json3 = json_encode($filtro);

                                    $usuariosdetalle = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.usuario_id,usuario.nombre,punto_venta.creditos_base,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json3, true);
                                    $usuariosdetalle = json_decode($usuariosdetalle);


                                    foreach ($usuariosdetalle->data as $key3 => $value3) {
                                        $array2 = [];

                                        $array2["Id"] = $value3->{"usuario.usuario_id"};
                                        $array2["UserName"] = $value3->{"usuario.login"};
                                        $array2["Name"] = $value3->{"usuario.nombre"};
                                        $array2["SystemName"] = 22;
                                        $array2["IsSuspended"] = ($value3->{"usuario.estado"} == 'A' ? false : truee);
                                        $array2["AgentBalance"] = $value3->{"punto_venta.creditos_base"};
                                        $array2["PlayerCount"] = 0;
                                        array_push($array2["Children"], $array3);


                                    }

                                }

                                array_push($array["Children"], $array2);


                            }


                        }
                    } else {
                        $array = [];
                        $array["id"] = $value->{"usuario.usuario_id"};
                        $array["Name"] = $value->{"usuario.nombre"};

                    }

                    array_push($arrayf, $array);

                    $balanceAgent = $balanceAgent + $value->{"punto_venta.creditos_base"};
                }


                $response["status"] = true;
                $response["html"] = "";
                $response["result"] = array(
                    "records" => $arrayf,
                    "titles" => "",
                    "total" => $usuarios->count[0]->{".count"},
                    "totalRecordsCount" => $usuarios->count[0]->{".count"}

                );

                $response["notification"] = array();


                /*
                            $response["HasError"] = false;
                            $response["AlertType"] = "success";
                            $response["AlertMessage"] = "";
                            $response["ModelErrors"] = [];

                            $response["Data"] = array(
                                "DownStreamChildrenCount"=>100,
                                "DownStreamChildrenBalanceSum"=>1000,
                                "DownStreamPlayerCount"=>100,
                                "DownStreamPlayerBalanceSum"=>100,
                                "Children"=>array(
                                    array(
                                        "UserName"=>"test",
                                        "AgentId"=>1,
                                        "SystemName"=>1,
                                        "PlayerCount"=>100,
                                        "AgentBalance"=>1000,
                                        "Children"=>array(
                                            array(
                                                "UserName"=>"test2",
                                                "SystemName"=>1,

                                                "PlayerCount"=>100,
                                                "AgentBalance"=>1000,

                                            )
                                        )
                                    )
                                )
                            );
                */
            }
            break;


        /**
         * getPlayers
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "getPlayers":

            if ($_SESSION["usuario2"] == 5) {
                $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            } else {
                $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            }


            $Usuario = new Usuario();

            $params = file_get_contents('php://input');
            $params = json_decode($params);


            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;


            $OrderedItem = "it_ticket_enc_info1.it_ticket2_id";
            $OrderType = "desc";

            $draw = $params->draw;
            $length = $params->length;
            $start = $params->start;

            if ($start != "") {
                $SkeepRows = $start;

            }

            if ($length != "") {
                $MaxRows = $length;

            }

            $columns = $params->columns;
            $order = $params->order;

            foreach ($order as $item) {

                switch ($columns[$item->column]->data) {
                    case "TicketId":
                        $OrderedItem = "it_ticket_enc_info1.ticket_id";
                        $OrderType = $item->dir;
                        break;

                    case "DateCreate":
                        $OrderedItem = "it_ticket_enc.fecha_crea";
                        $OrderType = $item->dir;
                        break;

                    case "DateClose":
                        $OrderedItem = "it_ticket_enc.fecha_cierre";
                        $OrderType = $item->dir;
                        break;

                    case "Amount":
                        $OrderedItem = "it_ticket_enc_info1.valor";
                        $OrderType = $item->dir;

                        break;
                }

            }

            $LinkId = $params->LinkId;
            $BannerId = $params->BannerId;

            $linkId = $params->linkId;
            $agentId = $params->agentId;

            if ($agentId == 0) {
                $agentId = "";
            }

            if ($linkId == 0) {
                $linkId = "";
            }

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 1000;
            }

            $rules = [];
            array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "USUONLINE", "op" => "eq"));

            if ($linkId != "") {
                array_push($rules, array("field" => "registro.link_id", "data" => $linkId, "op" => "eq"));

            }

            if ($agentId != "") {
                array_push($rules, array("field" => "registro.afiliador_id", "data" => $agentId, "op" => "eq"));

            } else {
                array_push($rules, array("field" => "registro.afiliador_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

            }


            if ($LinkId != "") {
                array_push($rules, array("field" => "registro.link_id", "data" => $LinkId, "op" => "eq"));

            }

            if ($BannerId != "") {
                array_push($rules, array("field" => "registro.banner_id", "data" => $BannerId, "op" => "eq"));

            }


            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);


            $usuarios = $Usuario->getUsuariosCustom("  DISTINCT (usuario.usuario_id),d.ciudad_nom ciudad, usuario.fecha_ult,registro.banner_id,registro.link_id,usuario.moneda,usuario.idioma,usuario.dir_ip,usuario.login,usuario.estado,usuario.estado_esp,usuario.pais_id,usuario.fecha_crea,registro.cedula,registro.sexo,registro.ciudad_id,registro.estado_valida,registro.nombre1,registro.nombre2,registro.apellido1,registro.email,registro.direccion,registro.telefono,registro.celular,registro.codigo_postal,registro.ciudnacim_id,registro.paisnacim_id,c.*,g.*,usuario.tiempo_limitedeposito,usuario.tiempo_autoexclusion,usuario.cambios_aprobacion,registro.creditos,registro.creditos_base ", "usuario.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true);

            $usuarios = json_decode($usuarios);

            $usuariosFinal = [];

            foreach ($usuarios->data as $key => $value) {

                $array = [];

                $array["playerId"] = $value->{"usuario.usuario_id"};
                $array["city"] = $value->{"d.ciudad"};
                $array["country"] = $value->{"usuario.pais_id"};
                $array["device"] = '';
                $array["registrationDate"] = $value->{"usuario.fecha_crea"};
                $array["bannerId"] = $value->{"registro.banner_id"};
                $array["linkId"] = $value->{"registro.link_id"};


                array_push($usuariosFinal, $array);

            }

            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => $usuariosFinal,
                "titles" => "",
                "total" => $usuarios->count[0]->{".count"},
                "totalRecordsCount" => $usuarios->count[0]->{".count"}

            );

            $response["notification"] = array();
            break;

        /**
         * getPlayersStatisticsPro
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "getPlayersStatisticsPro":

            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);


            $Usuario = new Usuario();

            $params = file_get_contents('php://input');
            $params = json_decode($params);


            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 1000;
            }

            $rules = [];
            array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "USUONLINE", "op" => "eq"));


            array_push($rules, array("field" => "registro.afiliador_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));


            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);


            $usuarios = $Usuario->getUsuariosCustom("  DISTINCT (usuario.usuario_id),d.ciudad_nom ciudad, usuario.fecha_ult,registro.banner_id,registro.link_id,usuario.moneda,usuario.idioma,usuario.dir_ip,usuario.login,usuario.estado,usuario.estado_esp,usuario.pais_id,usuario.fecha_crea,registro.cedula,registro.sexo,registro.ciudad_id,registro.estado_valida,registro.nombre1,registro.nombre2,registro.apellido1,registro.email,registro.direccion,registro.telefono,registro.celular,registro.codigo_postal,registro.ciudnacim_id,registro.paisnacim_id,c.*,g.*,usuario.tiempo_limitedeposito,usuario.tiempo_autoexclusion,usuario.cambios_aprobacion,registro.creditos,registro.creditos_base ", "usuario.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true);

            $usuarios = json_decode($usuarios);

            $usuariosFinal = [];

            foreach ($usuarios->data as $key => $value) {

                $array = [];

                $array["playerId"] = $value->{"usuario.usuario_id"};
                $array["city"] = $value->{"d.ciudad"};
                $array["country"] = $value->{"usuario.pais_id"};
                $array["device"] = '';
                $array["registrationDate"] = $value->{"usuario.fecha_crea"};
                $array["bannerId"] = $value->{"registro.banner_id"};
                $array["linkId"] = $value->{"registro.link_id"};


                array_push($usuariosFinal, $array);

            }

            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => $usuariosFinal,
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => "0"

            );

            $response["notification"] = array();
            break;

        /**
         * getDeletedPlayerLog
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "getDeletedPlayerLog":
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => array(),
                "titles" => "",
                "total" => "0",
                "totalRecordsCount" => "0"

            );

            $response["notification"] = array();

            break;

        case "getAgentsLinks":

            if ($_SESSION["usuario2"] == 5) {
                $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            } else {
                $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            }
            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            $AgentId = $params->AgentId;

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 1000;
            }

            $rules = [];

            if ($AgentId != "") {
                array_push($rules, array("field" => "usuario_link.usuario_id", "data" => "$AgentId", "op" => "eq"));

            }

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);

            $UsuarioLink = new UsuarioLink();

            $UsuarioLink = $UsuarioLink->getUsuarioLinksCustom(" usuario_link.*,usuario_mandante.* ", "usuario_link.usulink_id", "asc", $SkeepRows, $MaxRows, $json, true);
            $UsuarioLink = json_decode($UsuarioLink);

            $final = array();


            foreach ($UsuarioLink->data as $key => $value) {

                $array = array(
                    "id" => $value->{"usuario_link.usulink_id"},
                    "name" => $value->{"usuario_link.nombre"}
                );

                array_push($final, $array);

            }

            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => $final,
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => oldCount($final),

            );


            $response["notification"] = array();

            break;

        case "autmtest":


            $ObjAutomation = $params;

            $Automation = new Automation();
            $resppp = $Automation->CheckAutomation(json_decode(json_encode($ObjAutomation)), $ObjAutomation->method, $ObjAutomation->user_id, "");

            print_r($resppp);
            break;


        case "analize":


            $ObjAutomation = $params;

            $Automation = new Automation();
            $resppp = $Automation->CheckAutomation(json_decode(json_encode($ObjAutomation)), $ObjAutomation->method, $ObjAutomation->user_id, "");

            $response["response"] = $resppp;

            break;
        /**
         * getAutomations
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "getAutomations":

            if ($_SESSION["usuario2"] == 5) {
                $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            } else {
                $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            }
            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            $State = $params->State;
            $linkId = $params->linkId;
            $agentId = $params->agentId;

            if ($agentId == 0) {
                $agentId = "";
            }

            if ($linkId == 0) {
                $linkId = "";
            }


            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 1000;
            }

            $rules = [];

            if ($State != "") {
                array_push($rules, array("field" => "automation.estado", "data" => $State, "op" => "eq"));

            }


            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);

            $Automation = new Automation();

            $UsuarioLink = $Automation->getAutomationsCustom(" automation.* ", "automation.automation_id", "asc", $SkeepRows, $MaxRows, $json, true);
            $UsuarioLink = json_decode($UsuarioLink);

            $final = array();


            foreach ($UsuarioLink->data as $key => $value) {

                $array = array();

                $array["Id"] = $value->{'automation.automation_id'};
                $array["Name"] = $value->{'automation.nombre'};
                $array["State"] = $value->{'automation.estado'};
                $array["Trigger"] = $value->{'automation.tipo'};
                $array["TimeAction"] = $value->{'automation.tipo_tiempo'};
                $array["DateBegin"] = $value->{'automation.fecha_inicio'};
                $array["DateEnd"] = $value->{'automation.fecha_fin'};
                $array["CreatedDate"] = $value->{'automation.fecha_crea'};
                $array["Query"] = json_decode($value->{'automation.valor'});
                $array["QueryAction"] = json_decode($value->{'automation.accion'});


                array_push($final, $array);

            }

            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => $final,
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => oldCount($final),

            );


            $response["notification"] = array();

            break;


  /**
   * getAutomationNotifications
   *
   *
   * @param no
   *
   * @return no
   * @throws no
   *
   * @access public
   * @see no
   * @since no
   * @deprecated no
   */
        case "getAutomationNotifications":

            if ($_SESSION["usuario2"] == 5) {
                $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            } else {
                $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            }
            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            $State = $params->State;
            $linkId = $params->linkId;
            $agentId = $params->agentId;
            $automationId = $params->automationId;
            $NivelSelect0 = $params->NivelSelect0;
            $NivelSelect1 = $params->NivelSelect1;
            $NivelSelect2 = $params->NivelSelect2;
            $automationTrigger = $params->automationTrigger;

            if ($agentId == 0) {
                $agentId = "";
            }

            if ($linkId == 0) {
                $linkId = "";
            }


            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 1000;
            }

            $rules = [];
            array_push($rules, array("field" => "usuario_automation.estado", "data" => 'P', "op" => "eq"));
           // array_push($rules, array("field" => "usuario_automation.estado", "data" => 'P', "op" => "eq"));


            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);

            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomations = $UsuarioAutomation->getUsuarioAutomationsCustom(" usuario_automation.*,automation.nombre ", "usuario_automation.usuautomation_id", "asc", $SkeepRows, $MaxRows, $json, true);
            $UsuarioAutomations = json_decode($UsuarioAutomations);

            $final = array();


            foreach ($UsuarioAutomations->data as $key => $value) {

                $array = array();

                $array["id"] = $value->{'usuario_automation.usuautomation_id'};
                $array["level"] = $value->{'usuario_automation.nivel'};
                $array["state"] = $value->{'usuario_automation.estado'};
                $array["idAutomation"] = $value->{'usuario_automation.automation_id'};

                $array["user"] = $value->{'usuario_automation.usuario_id'};
                $array["date"] = $value->{'usuario_automation.fecha_crea'};

                $array["message"] = $value->{'automation.nombre'};


                array_push($final, $array);

            }


            $count = 0;

            if (is_numeric($UsuarioAutomations->count[0]->{'.count'})) {
                $count = $UsuarioAutomations->count[0]->{'.count'};
            }

            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => $final,
                "titles" => "",
                "total" => $count,
                "totalRecordsCount" => $count,

            );


            $response["notification"] = array();

            break;

        /**
         * getAutomationReport
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "getAutomationReport":

            if ($_SESSION["usuario2"] == 5) {
                $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            } else {
                $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            }
            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            $State = $params->State;
            $linkId = $params->linkId;
            $agentId = $params->agentId;
            $automationId = $params->automationId;
            $Id = $params->Id;
            $NivelSelect0 = $params->NivelSelect0;
            $NivelSelect1 = $params->NivelSelect1;
            $NivelSelect2 = $params->NivelSelect2;
            $automationTrigger = $params->automationTrigger;

            if ($agentId == 0) {
                $agentId = "";
            }

            if ($linkId == 0) {
                $linkId = "";
            }


            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 1000;
            }

            $rules = [];

            if ($State != "") {
                array_push($rules, array("field" => "usuario_automation.estado", "data" => $State, "op" => "eq"));

            }
            if ($automationId != "" && $automationId != "0") {
                array_push($rules, array("field" => "usuario_automation.automation_id", "data" => $automationId, "op" => "eq"));

            }
            if ($Id != "" && $Id != "0") {
                array_push($rules, array("field" => "usuario_automation.usuautomation_id", "data" => $Id, "op" => "eq"));

            }
            $autnivel = "'-1'";
            if ($NivelSelect0 != "" && $NivelSelect0) {
                $autnivel = $autnivel . ",'0'";

                // array_push($rules, array("field" => "usuario_automation.nivel", "data" => 0, "op" => "eq"));

            }
            if ($NivelSelect1 != "" && $NivelSelect1) {
                //array_push($rules, array("field" => "usuario_automation.nivel", "data" => 1, "op" => "eq"));
                $autnivel = $autnivel . ",'1'";

            }
            if ($NivelSelect2 != "" && $NivelSelect2) {
                //array_push($rules, array("field" => "usuario_automation.nivel", "data" => 2, "op" => "eq"));
                $autnivel = $autnivel . ",'2'";
            }


            if ($autnivel != "'-1'") {
                array_push($rules, array("field" => "usuario_automation.nivel", "data" => $autnivel, "op" => "in"));
            }


            $auttrigger = "'0'";
            if ($automationTrigger != "") {
                foreach ($automationTrigger as $itemTrigger) {
                    $auttrigger = $auttrigger . ",'" . $itemTrigger . "'";

                }
            }

            if ($auttrigger != "'0'") {
                array_push($rules, array("field" => "usuario_automation.tipo", "data" => $auttrigger, "op" => "in"));
            }

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);

            $UsuarioAutomation = new UsuarioAutomation();

            $UsuarioAutomations = $UsuarioAutomation->getUsuarioAutomationsCustom(" usuario_automation.*,usuario_automation.* ", "usuario_automation.usuautomation_id", "asc", $SkeepRows, $MaxRows, $json, true);
            $UsuarioAutomations = json_decode($UsuarioAutomations);

            $final = array();


            foreach ($UsuarioAutomations->data as $key => $value) {

                $array = array();

                $array["Id"] = $value->{'usuario_automation.usuautomation_id'};
                $array["State"] = $value->{'usuario_automation.estado'};
                $array["User"] = $value->{'usuario_automation.usuario_id'};
                $array["Type"] = $value->{'usuario_automation.tipo'};
                $array["ExternalId"] = $value->{'usuario_automation.externo_id'};
                $array["Nivel"] = $value->{'usuario_automation.nivel'};
                $array["Amount"] = $value->{'usuario_automation.valor'};
                $array["Observation"] = $value->{'usuario_automation.observacion'};
                $array["DateCreated"] = $value->{'usuario_automation.fecha_crea'};
                $array["DateAction"] = $value->{'usuario_automation.fecha_accion'};


                $array["Automation"] = array();
                $array["Automation"]["Id"] = $value->{'usuario_automation.usuautomation_id'};
                $array["Automation"]["Name"] = $value->{'usuario_automation.nombre'};
                $array["Automation"]["State"] = $value->{'usuario_automation.estado'};
                $array["Automation"]["Trigger"] = $value->{'usuario_automation.tipo'};
                $array["Automation"]["TimeAction"] = $value->{'usuario_automation.tipo_tiempo'};
                $array["Automation"]["DateBegin"] = $value->{'usuario_automation.fecha_inicio'};
                $array["Automation"]["DateEnd"] = $value->{'usuario_automation.fecha_fin'};
                $array["Automation"]["CreatedDate"] = $value->{'usuario_automation.fecha_crea'};
                $array["Automation"]["Query"] = json_decode($value->{'usuario_automation.valor'});
                $array["Automation"]["QueryAction"] = json_decode($value->{'usuario_automation.accion'});

                array_push($final, $array);

            }


            $count = 0;

            if (is_numeric($UsuarioAutomations->count[0]->{'.count'})) {
                $count = $UsuarioAutomations->count[0]->{'.count'};
            }

            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => $final,
                "titles" => "",
                "total" => $count,
                "totalRecordsCount" => $count,

            );


            $response["notification"] = array();

            break;



        /**
         * getLinks
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "getLinks":

            if ($_SESSION["usuario2"] == 5) {
                $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            } else {
                $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            }
            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            $BannerId = $params->BannerId;
            $LinkId = $params->LinkId;

            $linkId = $params->linkId;
            $agentId = $params->agentId;

            if ($agentId == 0) {
                $agentId = "";
            }

            if ($linkId == 0) {
                $linkId = "";
            }


            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 1000;
            }

            $rules = [];

            if ($linkId != "") {
                array_push($rules, array("field" => "usuario_link.link_id", "data" => $linkId, "op" => "eq"));

            }

            if ($agentId != "") {
                array_push($rules, array("field" => "usuario_link.usuario_id", "data" => $agentId, "op" => "eq"));

            } else {
                array_push($rules, array("field" => "usuario_link.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

            }


            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);

            $UsuarioLink = new UsuarioLink();

            $UsuarioLink = $UsuarioLink->getUsuarioLinksCustom(" usuario_link.*,usuario_mandante.* ", "usuario_link.usulink_id", "asc", $SkeepRows, $MaxRows, $json, true);
            $UsuarioLink = json_decode($UsuarioLink);

            $final = array();


            foreach ($UsuarioLink->data as $key => $value) {
                $trackingLink = "https://doradobet.com/#/";

                switch ($value->{"usuario_link"}) {
                    case 0:
                        $trackingLink = $trackingLink;
                        break;
                    case 1:
                        $trackingLink = $trackingLink . "apuestas";
                        break;
                    case 1:
                        $trackingLink = $trackingLink . "registro";
                        break;
                }
                $string = "&utm_source=Afiliados&utm_medium=Link&utm_campaign=" . $UsuarioMandante->nombres . "_" . $value->{"usuario_link.nombre"};

                $array = array();
                $array = array(
                    "affiliateId" => "17649",
                    "createDate" => "2018-06-28 17:43:54",
                    "creator" => $value->{"usuario_mandante.usumandante_id"},
                    "id" => $value->{"usuario_link.usulink_id"},
                    "marketingSourceName" => "1",
                    "name" => $value->{"usuario_link.nombre"},
                    "siteId" => "",
                    "tag1" => "",
                    "tag2" => "",
                    "tag3" => "288",
                    "trackingLink" => $trackingLink . "?btag=" . encrypt($value->{"usuario_link.usuario_id"} . "__" . $value->{"usuario_link.usulink_id"}, $ENCRYPTION_KEY) . $string,
                );

                array_push($final, $array);

            }

            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => $final,
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => oldCount($final),

            );


            $response["notification"] = array();

            break;


        case "getActiveBanner":

            if ($_SESSION["usuario2"] == 5) {
                $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            } else {
                $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            }

            $linkId = $params->linkId;
            $agentId = $params->agentId;

            if ($agentId == 0) {
                $agentId = "";
            }

            if ($linkId == 0) {
                $linkId = "";
            }


            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 1000;
            }

            $rules = [];

            if ($agentId != "") {
                array_push($rules, array("field" => "usuario_banner.usuario_id", "data" => $agentId, "op" => "eq"));

            } else {
                array_push($rules, array("field" => "usuario_banner.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

            }


            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);


            $Banner = new Banner();
            $Banners = $Banner->getBannersUsuarioCustom($UsuarioMandante->getUsuarioMandante(), " banner.*,usuario_banner.* ", "banner.banner_id", "asc", $SkeepRows, $MaxRows, $json, true);
            $Banners = json_decode($Banners);

            $final = array();

            foreach ($Banners->data as $key => $value) {

                $array = array();
                $array = array(
                    "Id" => $value->{"usuario_banner.usubanner_id"},
                    "BannerId" => $value->{"usuario_banner.banner_id"},
                    "Description" => $value->{"banner.nombre"},
                    "ActiveDate" => $value->{"usuario_banner.fecha_crea"}
                );

                array_push($final, $array);

            }


            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => $final,
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => oldCount($final),

            );


            $response["notification"] = array();

            break;


        /**
         * GetLinksDashboards
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "GetLinksDashboards":

            if ($_SESSION["usuario2"] == 5) {
                $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            } else {
                $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            }
            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;


            $linkId = $params->linkId;
            $agentId = $params->agentId;

            if ($agentId == 0) {
                $agentId = "";
            }

            if ($linkId == 0) {
                $linkId = "";
            }


            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 10000;
            }

            $rules = [];

            if ($linkId != "") {
                array_push($rules, array("field" => "usuario_link.link_id", "data" => $linkId, "op" => "eq"));

            }

            if ($agentId != "") {
                array_push($rules, array("field" => "usuario_link.usuario_id", "data" => $agentId, "op" => "eq"));

            } else {
                array_push($rules, array("field" => "usuario_link.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

            }


            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);

            $UsuarioLink = new UsuarioLink();

            $UsuarioLink = $UsuarioLink->getUsuarioLinksCustom(" COUNT(usuario_link.usulink_id) links ", "usuario_link.usulink_id", "asc", $SkeepRows, $MaxRows, $json, true);
            $UsuarioLink = json_decode($UsuarioLink);

            $final = [];
            $final["Links"] = [];
            $final["Links"]["Total"] = $UsuarioLink->data[0]->{".links"};


            $response["status"] = true;
            $response["html"] = "";

            $response["result"] = $final;

            $response["notification"] = array();

            break;

        case "GetActiveBannerDashboards":

            if ($_SESSION["usuario2"] == 5) {
                $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            } else {
                $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            }


            $linkId = $params->linkId;
            $agentId = $params->agentId;

            if ($agentId == 0) {
                $agentId = "";
            }

            if ($linkId == 0) {
                $linkId = "";
            }


            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 10000;
            }

            $rules = [];


            if ($agentId != "") {
                array_push($rules, array("field" => "usuario_banner.usuario_id", "data" => $agentId, "op" => "eq"));

            } else {
                array_push($rules, array("field" => "usuario_banner.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

            }


            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);

            $Banner = new Banner();

            $Banners = $Banner->getBannersUsuarioCustom($UsuarioMandante->getUsuarioMandante(), " banner.*,usuario_banner.* ", "banner.banner_id", "asc", $SkeepRows, $MaxRows, $json, true);
            $Banners = json_decode($Banners);


            $final = [];
            $final["ActiveBanner"] = [];
            $final["ActiveBanner"]["Total"] = $Banners->data[0]->{".links"};


            $response["status"] = true;
            $response["html"] = "";

            $response["result"] = $final;

            $response["notification"] = array();

            break;

        /**
         * getCurrentAffiliate
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "getCurrentAffiliate":

            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);


            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = $UsuarioMandante->getUsumandanteId();

            $response["notification"] = array();

            break;

        /**
         * getGeneralLinkClicks
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "getGeneralLinkClicks":
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "clickCount" => ""
            );

            $response["notification"] = array();

            break;

        /**
         * getMarketingSources
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "getMarketingSources":
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => array(
                    array(
                        "affiliateId" => 39705,
                        "createDate" => "2018-04-13 16:31:01",
                        "creator" => "danielftg@hotmail.com",
                        "marketingSourceId" => "7614",
                        "name" => "First Added",
                        "partnerId" => "288",
                        "site" => "http://www.com",
                        "updateDate" => "0000-00-00 00:00:00",

                    )

                ),
                "titles" => "",
                "total" => "",
                "totalRecordsCount" => "0"

            );

            $response["notification"] = array();
            break;

        /**
         * getSocialShareList
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "getSocialShareList":

            print_r('{"status":true,"html":"","result":{"records":[],"totalRecordsCount":"0","titles":null,"total":null},"notification":[]}');
            break;

        /**
         * AddBankAccount
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "AddBankAccount":

            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);
            $Usuario = new Usuario ($UsuarioMandante->getUsuarioMandante());


            $tieneRestricciones = 0;
            $cumpleRestricciones = false;

            try {
                $Clasificador = new Clasificador("", "MAXACCOUNTSBANK");

                $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

                $tieneRestricciones = $MandanteDetalle->getValor();


            } catch (Exception $e) {

                if ($e->getCode() == 34) {
                    $tieneRestricciones = 0;
                } elseif ($e->getCode() == 41) {
                    $tieneRestricciones = 0;
                } else {
                    throw $e;
                }
            }


            if ($tieneRestricciones > 0) {
                $rules = [];
                array_push($rules, array("field" => "usuario_banco.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                array_push($rules, array("field" => "usuario_banco.estado", "data" => "A", "op" => "eq"));


                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json2 = json_encode($filtro);

                $UsuarioBanco = new UsuarioBanco();

                $configuraciones = $UsuarioBanco->getUsuarioBancosCustom(" usuario_banco.* ", "usuario_banco.usubanco_id", "asc", 0, 100, $json2, true);

                $configuraciones = json_decode($configuraciones);

                if (intval($configuraciones->count[0]->{'.count'}) >= intval($tieneRestricciones)) {

                } else {
                    $cumpleRestricciones = true;
                }
            } else {
                $cumpleRestricciones = true;
            }

            if ($cumpleRestricciones) {
                $account = $params->Account;
                $account_type = ($params->TypeAccount == 1) ? 1 : 0;
                $bank = $params->Bank;
                $client_type = 0;
                $cod_interbank = $params->InterbankCode;
                $conf_account = $params->ConfirmAccount;


                $UsuarioBanco = new UsuarioBanco();
                $UsuarioBanco->setUsuarioId($UsuarioMandante->getUsuarioMandante());
                $UsuarioBanco->setBancoId($bank);
                $UsuarioBanco->setCuenta($account);
                $UsuarioBanco->setTipoCuenta($account_type);
                $UsuarioBanco->setTipoCliente($client_type);
                $UsuarioBanco->setUsucreaId($UsuarioMandante->getUsuarioMandante());
                $UsuarioBanco->setUsumodifId($UsuarioMandante->getUsuarioMandante());
                $UsuarioBanco->setEstado('A');
                $UsuarioBanco->setCodigo($cod_interbank);

                $UsuarioBancoMySqlDAO = new UsuarioBancoMySqlDAO();

                $UsuarioBancoMySqlDAO->insert($UsuarioBanco);
                $UsuarioBancoMySqlDAO->getTransaction()->commit();


                $response["status"] = true;
                $response["html"] = "";
            } else {
                $response["status"] = true;
                $response["html"] = "";
            }


            break;

        /**
         * GetBanks
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "GetBanks":

            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);
            $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
            //$Pais = new Pais($Usuario->paisId);

            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 1000;
            }

            $rules = [];
            array_push($rules, array("field" => "banco.pais_id", "data" => "$Usuario->paisId", "op" => "eq"));
            array_push($rules, array("field" => "banco.estado", "data" => "A", "op" => "eq"));


            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json2 = json_encode($filtro);

            $Banco = new Banco();

            $Bancos = $Banco->getBancosCustom(" banco.* ", "banco.banco_id", "asc", $SkeepRows, $MaxRows, $json2, true);


            $Bancos = json_decode($Bancos);

            $BancosData = array();

            foreach ($Bancos->data as $key => $value) {


                $arraybanco = array();
                $arraybanco["Id"] = ($value->{"banco.banco_id"});
                $arraybanco["Name"] = ($value->{"banco.descripcion"});

                array_push($BancosData, $arraybanco);


            }


            $response = array();
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => $BancosData
            );


            break;

        case "GetRegions":

            $Pais = new Pais();

            $SkeepRows = 0;
            $MaxRows = 1000000;

            $json = '{"rules" : [{"field" : "pais.estado", "data": "A","op":"eq"}] ,"groupOp" : "AND"}';

            $paises = $Pais->getPaisesCustom("pais.pais_id", "asc", $SkeepRows, $MaxRows, $json, true);

            $paises = json_decode($paises);


            $PaisesData = array();

            foreach ($paises->data as $key => $value) {


                $arraybanco = array();
                $arraybanco["Id"] = ($value->{"pais.pais_id"});
                $arraybanco["Name"] = ($value->{"pais.pais_nom"});

                array_push($PaisesData, $arraybanco);


            }

            $response = array();
            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => $PaisesData
            );


            break;


        /**
         * CreateWithdraw
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "CreateWithdraw":

            $AccountBank = $params->AccountBank;
            $Type = $params->Type;
            $Value = $params->Value;
            $ConfirmValue = $params->ConfirmValue;


            $valorFinal = $Value;
            $valorImpuesto = 0;
            $valorPenalidad = 0;
            $creditos = $Value;


            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            $ClientId = $UsuarioMandante->getUsuarioMandante();
            $Usuario = new Usuario($ClientId);

            if ($creditos > 0) {

                if ($Usuario->creditosAfiliacion < $creditos) {
                    throw new Exception("Insufficient balance", "58");
                }
            } else {
                throw new Exception("Insufficient balance", "58");
            }

            $UsuarioBanco = new UsuarioBanco($AccountBank);


            if ($UsuarioBanco->usuarioId != $Usuario->usuarioId) {
                throw new Exception("No existe Cuenta Bancaria", "67");

            }
            $amount = $Value;
            $service = "UserBank";
            $id = $AccountBank;
            $balance = 0;


            //$UsuarioMandante = new UsuarioMandante($json->session->usuario);
            //$Usuario = new Usuario ($UsuarioMandante->getUsuarioMandante());

            //Verificamos limite de minimo retiro
            /*$Clasificador = new Clasificador("", "MINWITHDRAW");
            $minimoMontoPremios = 0;
            try {
                $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
                $minimoMontoPremios = $MandanteDetalle->getValor();
            } catch (Exception $e) {
            }

            if ($amount < $minimoMontoPremios) {
                throw new Exception("MINIMO MONTO PARA RETIROS" . $amount . "-" . $minimoMontoPremios, "54");
            }

            //Verificamos limite de maximo retiro
            $Clasificador = new Clasificador("", "MAXWITHDRAW");
            $maximooMontoPremios = -1;
            try {
                $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
                $maximooMontoPremios = $MandanteDetalle->getValor();
            } catch (Exception $e) {
            }

            if ($amount > $maximooMontoPremios && $maximooMontoPremios != -1) {
                throw new Exception("MAXIMO MONTO PARA RETIROS" . $amount . "-" . $minimoMontoPremios, "55");
            }*/


            //Verificamos impuesto retiro

            //Si es de Saldo Premios
            if ($creditos > 0) {

                $impuesto = -1;
                try {
                    $Clasificador = new Clasificador("", "TAXWITHDRAWAWARD");

                    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
                    $impuesto = $MandanteDetalle->getValor();
                } catch (Exception $e) {
                }

                if ($impuesto > 0) {
                    $impuestoDesde = -1;
                    try {
                        $Clasificador = new Clasificador("", "TAXWITHDRAWAWARDFROM");
                        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
                        $impuestoDesde = $MandanteDetalle->getValor();
                    } catch (Exception $e) {
                    }

                    if ($impuestoDesde != -1) {
                        if ($amount >= $impuestoDesde) {
                            $valorImpuesto = ($impuesto / 100) * $valorFinal;
                            $valorFinal = $valorFinal - $valorImpuesto;
                        }
                    }
                }
            }

            $Consecutivo = new Consecutivo("", "RET", "");

            $consecutivo_recarga = $Consecutivo->numero;

            $consecutivo_recarga++;

            $ConsecutivoMySqlDAO = new ConsecutivoMySqlDAO();

            $Consecutivo->setNumero($consecutivo_recarga);


            $ConsecutivoMySqlDAO->update($Consecutivo);

            $ConsecutivoMySqlDAO->getTransaction()->commit();


            $CuentaCobro = new CuentaCobro();


            $CuentaCobro->cuentaId = $consecutivo_recarga;

            $CuentaCobro->usuarioId = $ClientId;

            $CuentaCobro->valor = $valorFinal;

            $CuentaCobro->fechaPago = '';

            $CuentaCobro->fechaCrea = date('Y-m-d H:i:s');


            $CuentaCobro->usucambioId = 0;
            $CuentaCobro->usurechazaId = 0;
            $CuentaCobro->usupagoId = 0;

            $CuentaCobro->fechaCambio = $CuentaCobro->fechaCrea;
            $CuentaCobro->fechaAccion = $CuentaCobro->fechaCrea;


            $CuentaCobro->estado = 'A';
            $clave = GenerarClaveTicket2(5);

            $CuentaCobro->clave = "aes_encrypt('" . $clave . "','" . $claveEncrypt_Retiro . "')";

            $CuentaCobro->mandante = '0';

            $CuentaCobro->dirIp = '';

            $CuentaCobro->impresa = 'S';

            $CuentaCobro->mediopagoId = 0;
            $CuentaCobro->puntoventaId = 0;

            $CuentaCobro->costo = $valorPenalidad;
            $CuentaCobro->impuesto = $valorImpuesto;
            $CuentaCobro->creditos = $creditos;
            $CuentaCobro->creditosBase = 0;

            $CuentaCobro->transproductoId = 0;

            $method = "";
            switch ($service) {
                case "local":
                    $method = "pdf";
                    $status_message = '<table style="width:430px;height: 355px;/* border:1px solid black; */"><tbody><tr><td align="center" valign="top"><font style="text-align:center;font-size:20px;font-weight:bold;">NOTA DE RETIRO</font></td></tr><tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Nota de retiro No.:&nbsp;&nbsp;' . $consecutivo_recarga . '</font></td></tr><tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">No. de Cliente:&nbsp;&nbsp;' . $ClientId . '</font></td></tr><tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Nombre:&nbsp;&nbsp;' . $Usuario->nombre . '</font></td></tr><tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Fecha:&nbsp;&nbsp;' . $CuentaCobro->fechaCrea . '</font></td></tr><tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Clave:&nbsp;&nbsp;' . $clave . '</font></td></tr><tr><td align="center" valign="top"><div style="height:1px;">&nbsp;</div></td></tr>
<tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Valor a retirar:&nbsp;&nbsp;' . $amount . '</font></td></tr>
<tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Impuesto:&nbsp;&nbsp;' . $valorImpuesto . '</font></td></tr>
<tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Costo:&nbsp;&nbsp;' . $valorPenalidad . '</font></td></tr>
<tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Valor a entregar:&nbsp;&nbsp;' . $valorFinal . '</font></td></tr>

</tbody></table>';
                    break;

                /**
                 * UserBank
                 *
                 *
                 * @param no
                 *
                 * @return no
                 * @throws no
                 *
                 * @access public
                 * @see no
                 * @since no
                 * @deprecated no
                 */
                case "UserBank":
                    $method = "0";
                    $status_message = "";

                    $CuentaCobro->mediopagoId = $id;

                    break;
            }


            $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();

            $CuentaCobroMySqlDAO->insert($CuentaCobro);


            if ($creditos > 0) {
                $Usuario->creditosAfiliacion = "creditos_afiliacion - " . $creditos;

                $UsuarioMySqlDAO = new UsuarioMySqlDAO($CuentaCobroMySqlDAO->getTransaction());

                $UsuarioMySqlDAO->update($Usuario);

            }


            $CuentaCobroMySqlDAO->getTransaction()->commit();

            $response["status"] = true;
            $response["html"] = "";

            break;


        /**
         * GetWithdrawls
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "GetWithdrawls":

            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);


            $agentId = $params->agentId;
            $BeginDate = $params->BeginDate;
            $EndDate = $params->EndDate;
            $AccountBank = $params->AccountBank;
            $State = $json->params->State;


            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            $draw = $params->draw;
            $length = $params->length;
            $start = $params->start;

            if ($start != "") {
                $SkeepRows = $start;

            }

            if ($length != "") {
                $MaxRows = $length;

            }


            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 1000;
            }

            $rules = [];
            if ($_SESSION["win_perfil"] != "ADMINAFILIADOS" && $_SESSION["win_perfil"] != "ADMIN" && $_SESSION["win_perfil"] != "SA" && $_SESSION["win_perfil"] != "ADMIN2") {
                array_push($rules, array("field" => "cuenta_cobro.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
            }

            if ($BeginDate != "") {
                array_push($rules, array("field" => "cuenta_cobro.fecha_crea", "data" => $BeginDate, "op" => "ge"));

            }

            if ($EndDate != "") {
                array_push($rules, array("field" => "cuenta_cobro.fecha_crea", "data" => $EndDate, "op" => "le"));

            }

            if ($AccountBank != "") {
                array_push($rules, array("field" => "cuenta_cobro.mediopago_id", "data" => $AccountBank, "op" => "le"));

            }

            if ($State == "0") {
                array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "A", "op" => "le"));

            }

            if ($State == "1") {
                array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "P", "op" => "le"));

            }

            if ($State == "3") {
                array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "I", "op" => "le"));

            }

            if ($State == "4") {
                array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "R", "op" => "le"));

            }


            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json2 = json_encode($filtro);

            $CuentaCobro = new CuentaCobro();

            $cuentas = $CuentaCobro->getCuentasCobroCustom(" usuario.nombre,usuario_banco.cuenta,cuenta_cobro.fecha_pago,cuenta_cobro.impuesto,cuenta_cobro.cuenta_id,cuenta_cobro.estado,cuenta_cobro.valor,cuenta_cobro.fecha_crea ", "cuenta_cobro.cuenta_id", "asc", $SkeepRows, $MaxRows, $json2, true, "cuenta_cobro.cuenta_id");

            $cuentas = json_decode($cuentas);

            $cuentasData = array();

            foreach ($cuentas->data as $key => $value) {


                $arraybet = array();
                $arraybet["UserName"] = ($value->{"usuario.nombre"});
                $arraybet["Id"] = ($value->{"cuenta_cobro.cuenta_id"});
                $arraybet["AmountBase"] = ($value->{"cuenta_cobro.valor"});
                $arraybet["Tax"] = ($value->{"cuenta_cobro.impuesto"});
                $arraybet["NetAmount"] = ($value->{"cuenta_cobro.valor"});
                $arraybet["CreationDate"] = ($value->{"cuenta_cobro.fecha_crea"});
                $arraybet["AccountBank"] = ($value->{"usuario_banco.cuenta"});
                $arraybet["payment_system_name"] = 'local';

                if ($value->{"cuenta_cobro.estado"} == "I") {
                    $arraybet["State"] = 3;

                } elseif ($value->{"cuenta_cobro.estado"} == "A") {
                    $arraybet["State"] = 0;

                } elseif ($value->{"cuenta_cobro.estado"} == "P") {
                    $arraybet["State"] = 2;

                } elseif ($value->{"cuenta_cobro.estado"} == "R") {
                    $arraybet["State"] = 4;

                }
                $arraybet["PayDate"] = ($value->{"cuenta_cobro.fecha_pago"});

                array_push($cuentasData, $arraybet);


            }


            $count = 0;

            if (is_numeric($cuentas->count[0]->{'.count'})) {
                $count = $cuentas->count[0]->{'.count'};
            }

            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => $cuentasData,
                "titles" => "",
                "total" => $count,
                "totalRecordsCount" => $count,

            );

            $response["notification"] = array();


            break;

        /**
         * GetBankAccounts
         *
         *
         * @param no
         *
         * @return no
         * @throws no
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        case "GetBankAccounts":

            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

            $agentId = $params->agentId;
            $Account = $params->Account;
            $Bank = $params->Bank;
            $InterbankCode = $params->InterbankCode;
            $TypeAccount = $params->TypeAccount;
            $State = $json->params->State;

            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            $draw = $params->draw;
            $length = $params->length;
            $start = $params->start;

            if ($start != "") {
                $SkeepRows = $start;

            }

            if ($length != "") {
                $MaxRows = $length;

            }


            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 1000;
            }

            $rules = [];

            if ($_SESSION["win_perfil"] != "ADMINAFILIADOS" && $_SESSION["win_perfil"] != "ADMIN" && $_SESSION["win_perfil"] != "SA" && $_SESSION["win_perfil"] != "ADMIN2") {
                array_push($rules, array("field" => "usuario_banco.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
            }

            if ($agentId != "") {
                array_push($rules, array("field" => "usuario_banco.usuario_id", "data" => $agentId, "op" => "eq"));

            }

            if ($Account != "") {
                array_push($rules, array("field" => "usuario_banco.cuenta", "data" => $Account, "op" => "eq"));

            }

            if ($Bank != "") {
                array_push($rules, array("field" => "banco.banco_id", "data" => $Bank, "op" => "eq"));

            }

            if ($InterbankCode != "") {
                array_push($rules, array("field" => "usuario_banco.codigo", "data" => $InterbankCode, "op" => "eq"));

            }

            if ($TypeAccount != "") {
                array_push($rules, array("field" => "usuario_banco.tipo_cuenta", "data" => $TypeAccount, "op" => "eq"));

            }

            if ($State == "1") {
                array_push($rules, array("field" => "usuario_banco.estado", "data" => "A", "op" => "eq"));
            }

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json2 = json_encode($filtro);

            $UsuarioBanco = new UsuarioBanco();

            $configuraciones = $UsuarioBanco->getUsuarioBancosCustom(" usuario.nombre,usuario_banco.*,banco.* ", "usuario_banco.estado", "asc", $SkeepRows, $MaxRows, $json2, true);


            $configuraciones = json_decode($configuraciones);

            $configuracionesData = array();

            foreach ($configuraciones->data as $key => $value) {

                $arraybanco = array();
                $arraybanco["UserName"] = ($value->{"usuario.nombre"});
                $arraybanco["Id"] = ($value->{"usuario_banco.usubanco_id"});
                $arraybanco["Account"] = ($value->{"usuario_banco.cuenta"});
                $arraybanco["InterbankCode"] = ($value->{"usuario_banco.codigo"});
                $arraybanco["TypeAccount"] = $value->{"usuario_banco.tipo_cuenta"};
                $arraybanco["Bank"] = $value->{"banco.descripcion"};

                switch ($arraybanco["TypeAccount"]) {
                    case "0":
                        $arraybanco["TypeAccount"] = "Ahorros";
                        break;

                    case "1":
                        $arraybanco["TypeAccount"] = "Corriente";

                        break;
                }

                $arraybanco["client_type"] = $value->{"usuario_banco.tipo_cliente"};

                switch ($arraybanco["client_type"]) {
                    case "1":
                        $arraybanco["client_type"] = "Person";
                        break;

                    case "0":
                        $arraybanco["client_type"] = "Current";

                        break;
                }

                $arraybanco["State"] = ($value->{"usuario_banco.estado"} == "A") ? 0 : 1;
                $arraybanco["coin"] = 'PEN';

                if ($arraybanco["state"] == "A") {
                    // $arraybanco["state"] = '1';

                } elseif ($arraybanco["state"] == "I") {
                    $arraybanco["state"] = 'C';

                }
                array_push($configuracionesData, $arraybanco);


            }


            $count = 0;

            if (is_numeric($configuraciones->count[0]->{'.count'})) {
                $count = $configuraciones->count[0]->{'.count'};
            }

            $response["status"] = true;
            $response["html"] = "";
            $response["result"] = array(
                "records" => $configuracionesData,
                "titles" => "",
                "total" => $count,
                "totalRecordsCount" => $count,

            );

            $response["notification"] = array();

            break;

        default:

            # code...
            break;
    }
} catch (Exception $e) {

    switch ($e->getCode()) {
        case 50:
            $response["HasError"] = true;

            $response["AlertType"] = "danger";
            $response["AlertMessage"] = 'El usuario o la contraseña son incorrectos, revisa los datos ingresados.';
            // $response["AlertMessage"] = '-|' . "" . ' |-' . '(' . $e->getCode() . ')';
            $response["ModelErrors"] = [];
            $response["status"] = false;
            $response["result"] = false;
            $response["success"] = false;

            break;

        default:
            $response["HasError"] = true;
            $response["status"] = false;
            $response["success"] = false;
            $response["result"] = false;
            $response["AlertType"] = "danger";
            $response["AlertMessage"] = '-|' . $e->getMessage() . ' |-' . '(' . $e->getCode() . ')';
            // $response["AlertMessage"] = '-|' . "" . ' |-' . '(' . $e->getCode() . ')';
            $response["AlertMessage"] = 'Error en la solicitud. Comunicate con soporte, Reporta el codigo de error: ' . $e->getCode();
            $response["ModelErrors"] = [];

            break;
    }
}
if (json_encode($response) != "[]") {
    print_r(json_encode($response));

}

/**
 * Enviar un correo
 *
 * @param String $id id
 * @return String $random_key random_key
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function currencyConverter($from_Currency, $to_Currency, $amount)
{
    $from_Currency = urlencode($from_Currency);
    $to_Currency = urlencode($to_Currency);
    $encode_amount = 1;

    $rawdata = file_get_contents("http://api.currencies.zone/v1/quotes/$from_Currency/$to_Currency/json?quantity=$amount&key=44|YSqBgkAbvbGfenoxx62OaSnoD~rF8rw~");
    $rawdata = json_decode($rawdata);

    return $rawdata->result->amount;
}

/**
 * Enviar un correo
 *
 * @param String $id id
 * @return String $random_key random_key
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function getSports()
{

    $rawdata = file_get_contents("http://datafeeds-itainment.biahosted.com/public-api/Export/GetEvents?importerId=1&from=2017-07-3&to=2017-07-3");
    $data = simplexml_load_string($rawdata);
    $datos = json_decode($rawdata);
    $array = array();
    foreach ($datos as $item) {
        $item_data = array(
            "Id" => $item->SportId,
            "Name" => $item->Name
        );
        array_push($array, $item_data);
    }


    return $array;

}

/**
 * Enviar un correo
 *
 * @param String $id id
 * @return String $random_key random_key
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function getMarketTypes($sport)
{

    $rawdata = file_get_contents("http://datafeeds-itainment.biahosted.com/public-api/Export/GetEvents?importerId=1&from=2017-07-3&to=2017-07-3");
    $data = simplexml_load_string($rawdata);
    $datos = json_decode($rawdata);
    $array = array();
    foreach ($datos as $item) {
        if ($sport == $item->SportId) {
            $rawdata2 = file_get_contents("http://datafeeds-itainment.biahosted.com/public-api/Export/GetMarkets?importerId=1&eventId=" . $item->Categories[0]->Championships[0]->Events[0]->EventId);
            $datos2 = json_decode($rawdata2);

            foreach ($datos2 as $item2) {
                $item_data = array(
                    "Id" => $item->SportId . "M" . $item2->MarketTypeid,
                    "Name" => $item2->Name
                );
                array_push($array, $item_data);
            }

        }

    }


    return $array;

}

/**
 * Obtener la región de un deporte pasado como parámetro
 *
 * @param String $sport sport
 * @return array $array region
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function getRegions($sport)
{

    $rawdata = file_get_contents("http://datafeeds-itainment.biahosted.com/public-api/Export/GetEvents?importerId=1&from=2017-07-3&to=2017-07-3");
    $data = simplexml_load_string($rawdata);
    $datos = json_decode($rawdata);
    $array = array();
    foreach ($datos as $item) {

        if ($sport == $item->SportId) {
            foreach ($item->Categories as $item2) {
                $item_data = array(
                    "Id" => $item2->CategoryId,
                    "Name" => $item2->Name
                );
                array_push($array, $item_data);
            }


        }

    }


    return $array;

}

/**
 * Obtener las competiciones de un deporte pasado como parámetro
 *
 * @param String $sport sport
 * @param String $region region
 *
 * @return array $array competiciones
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function getCompetitions($sport, $region)
{

    $rawdata = file_get_contents("http://datafeeds-itainment.biahosted.com/public-api/Export/GetEvents?importerId=1&from=2017-07-3&to=2017-07-3");
    $data = simplexml_load_string($rawdata);
    $datos = json_decode($rawdata);
    $array = array();
    foreach ($datos as $item) {

        if ($sport == $item->SportId) {
            foreach ($item->Categories as $item2) {
                if ($item2->CategoryId == $region) {
                    foreach ($item2->Championships as $item3) {
                        $item_data = array(
                            "Id" => $item3->ChampionshipId,
                            "Name" => $item3->Name
                        );
                        array_push($array, $item_data);
                    }
                }

            }


        }

    }


    return $array;

}

/**
 * Obtener información de un deporte pasado como parámetro
 *
 * @param String $sport sport
 * @param String $region region
 * @param String $competition competition
 *
 * @return array $array array
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function getMatches($sport, $region, $competition)
{

    $rawdata = file_get_contents("http://datafeeds-itainment.biahosted.com/public-api/Export/GetEvents?importerId=1&from=2017-07-3&to=2017-07-3");
    $data = simplexml_load_string($rawdata);
    $datos = json_decode($rawdata);
    $array = array();
    foreach ($datos as $item) {

        if ($sport == $item->SportId) {
            foreach ($item->Categories as $item2) {
                if ($item2->CategoryId == $region) {
                    foreach ($item2->Championships as $item3) {

                        if ($item3->ChampionshipId == $competition) {
                            foreach ($item3->Events as $item4) {
                                $item_data = array(
                                    "Id" => $item4->EventId,
                                    "Name" => $item4->Name
                                );
                                array_push($array, $item_data);
                            }
                        }

                    }
                }

            }


        }

    }


    return $array;

}

/**
 * Generar una clave para el ticket
 *
 * @param int $length length
 * @return String $randomString randomString
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function GenerarClaveTicket($length)
{
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

/**
 * Crear script
 *
 * @param String $URL_AFFILIATES_API URL_AFFILIATES_API
 * @param String $account account
 * @param String $mId mId
 *
 * @return String $script script
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function createScript($URL_AFFILIATES_API, $account, $mId)
{
    $script = '<script type="text/javascript">!function(e,t,a,n,c,s){e.affScriptCount = e.affScriptCount == undefined ? 0 : e.affScriptCount+1;if(e.affScriptUrl === undefined){e.affScriptUrl = {};}e.affScriptUrl[e.affScriptCount] = n;s = s + "_" + e.affScriptCount;e.bcAnalyticsObject=c,e[c]=e[c]||function(){(e[c].q=e[c].q||[]).push(arguments),e[c].u=e[c].u||n};var i=t.createElement(a),o=t.getElementsByTagName(a)[0];i.async=!0,i.src=n+"analytics/banner.js",i.id=s,!t.getElementById(s)&&o.parentNode.insertBefore(i,o)}(window,document,"script","' . $URL_AFFILIATES_API . '","ba","bafTrSc"),ba("_setUrl","' . "https://api.doradobet.com/affiliates/" . '"),ba("_setAccount",' . $account . '),ba("_mId",' . $mId . ');</script><div data-ti="' . $account . '_' . $mId . '"></div>';

    return $script;
}

/**
 * Encriptar con el método AES-128-CTR
 *
 * @param array $data data
 * @param String $encryption_key encryption_key
 *
 * @return String $encrypted_string resultado de la operación
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function encrypt($data, $encryption_key = "")
{
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-128-CTR'));
    $encrypted_string = bin2hex($iv) . openssl_encrypt($data, 'AES-128-CTR', gethostname() . "|" . ip2long($_SERVER['SERVER_ADDR']), 0, $iv);
    return $encrypted_string;
}

/**
 * Desencriptar con el método AES-128-CTR
 *
 * @param array $data data
 * @param String $encryption_key encryption_key
 *
 * @return String|boolean $decrypted_string resultado de la operación
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function decrypt($data, $encryption_key = "")
{

    $iv_strlen = 2 * openssl_cipher_iv_length('AES-128-CTR');
    if (preg_match("/^(.{" . $iv_strlen . "})(.+)$/", $data, $regs)) {
        list(, $iv, $crypted_string) = $regs;
        $decrypted_string = openssl_decrypt($crypted_string, 'AES-128-CTR', gethostname() . "|" . ip2long($_SERVER['SERVER_ADDR']), 0, hex2bin($iv));
        return $decrypted_string;
    } else {
        return FALSE;
    }
}

/**
 * Generar una clave para el ticket
 *
 * @param int $length length
 * @return String $randomString randomString
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
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

/**
 * Enviar un correo
 *
 * @param String $c_address c_address
 * @param String $c_from c_from
 * @param String $c_fromname c_fromname
 * @param String $c_subject c_subject
 * @param String $c_include c_include
 * @param String $c_mensaje c_mensaje
 * @param String $c_dominio c_dominio
 * @param String $c_compania c_compania
 * @param String $c_color c_color
 *
 * @return boolean $ resultado de la operación
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function EnviarCorreo2($c_address, $c_from, $c_fromname, $c_subject, $c_include, $c_mensaje, $c_dominio, $c_compania, $c_color)
{


    print_r("entroo");
    require("../src/imports/phpmailer/class.phpmailer.php");
    require("../src/imports/phpmailer/class.smtp.php");
    print_r("entroo2");


    //Crea las instancias y el cuerpo del correo
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->Host = "localhost";
    $mail->SMTPDebug = 0;
    $mail->From = $c_from;
    $mail->FromName = $c_fromname;
    $mail->Subject = $c_subject;

    $correo_mensaje = $c_mensaje;
    $correo_dominio = $c_dominio;
    $correo_compania = $c_compania;
    $correo_color = $c_color;

    $message = '<html>

<head>
    <title><? echo $correo_compania;?> - Registro</title>
    <script src="https://use.fontawesome.com/cf4a881f9a.js"></script>
    <style type="text/css" media="screen">
        .blanco {
            color: #579438;
        }

        a.blanco:hover{
            color: silver;
        }
    </style>
</head>

<body>
<table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
    <tbody>
    <tr style="background: #434041;">
        <td align="left" valign="top">
            <img src="https://doradobet.com/assets/images/logo.png" width="250" height="250" align="right" style="/* margin-left:10px; *//* position: absolute; *//* margin-top: -120px; */width: 120px;height: 100px;float: left;/* display: inline-block; *//* left: 0px; *//* position: relative; */" "="">
        </td>
    </tr>
    <tr>
        <td align="center" valign="top" bgcolor="#f0f0f0" style="background-color:#f0f0f0; font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#000000; padding:10px 10px 50px 10px;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:40px;">
                <tbody>
                <tr>
                    <td align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#525252;">

                        <div style="font-size:28px;">Registro - <? echo $correo_dominio;?></div>
                        <br>
                        <div><? echo $correo_mensaje;?></div>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td align="left" valign="top" style="background: #DAA903;" class="gradient">
            <table width="100%" border="0" cellspacing="0" cellpadding="15">
                <tbody>
                <tr>
                    <td align="left" valign="top" style="color:#ffffff; font-family:Arial, Helvetica, sans-serif; font-size:13px;"
                        class="success">Atentamente,
                        <br>Servicio al Cliente
                        <br>Sitio Web: <a href="https://<? echo $correo_dominio;?>" target="_blank" style="color:#ffffff; text-decoration:none;">https://<? echo $correo_dominio;?></a>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
</body>

</html>
';
    $mail->msgHTML($message);
    $mail->AddAddress($c_address, $c_address);
    $mail->SMTPAuth = false;

    //Verifica si el correo se envió satisfactoriamente
    $enviado = false;
    if ($mail->Send()) {
        print_r("RESP2");
        $enviado = true;

    }

    //Retorna la respuesta
    print_r("RESP");
    print_r($enviado);
}


/**
 * Enviar un correo
 *
 * @param String $c_address c_address
 * @param String $c_from c_from
 * @param String $c_fromname c_fromname
 * @param String $c_subject c_subject
 * @param String $c_include c_include
 * @param String $c_title c_title
 * @param String $c_mensaje c_mensaje
 * @param String $c_dominio c_dominio
 * @param String $c_compania c_compania
 * @param String $c_color c_color
 *
 * @return boolean $ resultado de la operación
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function EnviarCorreo($c_address, $c_from, $c_fromname, $c_subject, $c_include, $c_title, $c_mensaje, $c_dominio, $c_compania, $c_color)
{


    require("../src/imports/phpmailer/class.phpmailer.php");
    require("../src/imports/phpmailer/class.smtp.php");


    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
    try {
        //Server settings
        $mail->SMTPDebug = 2;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'localhost';  // Specify main and backup SMTP servers
        //$mail->SMTPAuth = true;                               // Enable SMTP authentication
        //$mail->From = 'aa@aa.com';
        //$mail->FromName = "daniel";
        $mail->Subject = "tEST";
        $mail->SMTPDebug = 1;

        //Recipients
        $mail->setFrom($c_from, $c_fromname);
        $mail->addAddress($c_address, $c_address);     // Add a recipient
        //$mail->addAddress('ellen@example.com');               // Name is optional
        //$mail->addReplyTo('info@example.com', 'Information');
        //$mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        //Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

        $message = '
        <!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900" rel="stylesheet">
	<style>
		@import url(https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.css);

		body {
			font-family: \'Roboto\', sans-serif;
			text-decoration: none;
			font-size: 14px;
		}
		p {
			padding: 2rem;
			margin: 0;
		}
		.container {
			height: 600px;
			width: 100%;
		}
		.container .header{
			height: 330px;
			width: 100%;

		}
		.container .header div{
			height: 330px;
			background-size:  auto 102%;
			background-repeat: no-repeat;
			background-position: bottom;
		}


		.contain{
			height: auto;
		}
		.contain p{
			text-align: center;
			color: grey;
			line-height: 20px;
			padding-top: 1rem;
			padding-bottom: 1rem;
		}
		.contain h1{
			text-align: center;
			color: #b48303;
			margin: 0;
			padding-top: 15px;
		}

		.footer{
			height: 50px;
			background: #b48303;
		}
		.contain .social {
			height: 40px;
		}
		.contain div:first-child{
			height: auto;
		}
		.contain .social #l1 li{
			display:list-item;
			xlist-style:none;
		}

		.contain .social #l2 li{
			display: inline;
		}

		.contain .social #l1, .contain .social #l2{
			text-align: center;
			padding: 0;
			margin: 0;
		}

		.contain .social .social-icons li {
			font-size: 1.2em;
			padding: 0.8em;
			margin: 0;
		}
		.contain .social .social-icons a{
			color: #848484;
		}
		.footer p {
			font-weight: 300;
			text-align: center;
			color: white;
			padding: 1rem 2rem;
		}
	</style>
</head>
<body>
	<div class="container">
		<div class="header">
<img sr="" src="https://images.doradobet.com/site/doradobet/email/bg.jpg" style="
    width: 100%;
">		
		</div>

		<div class="contain">
			<div>
				<h1>' . $c_title . '</h1>
				<p>' . $c_mensaje . '</p>
			</div>
			
			<div class="social">
				<ul class="social-icons" id="l2">
					  <li><a target="_blank" href="https://www.facebook.com/doradobetcom/" class="social-icon" style=" width: 25px; height: 25px; display: inline-block; "> <i class="fa fa-facebook" ><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="25px" height="25px" viewBox="0 0 96.227 96.227" style="enable-background:new 0 0 96.227 96.227;" xml:space="preserve">
<g>
	<path d="M73.099,15.973l-9.058,0.004c-7.102,0-8.477,3.375-8.477,8.328v10.921h16.938l-0.006,17.106H55.564v43.895H37.897V52.332   h-14.77V35.226h14.77V22.612C37.897,7.972,46.84,0,59.9,0L73.1,0.021L73.099,15.973L73.099,15.973z" fill="#084848"/>
</g>

</svg>
					  </i></a></li>
					  <li><a target="_blank" href="https://twitter.com/doradobet/" class="social-icon" style=" width: 25px; height: 25px; display: inline-block; "> <i class="fa fa-twitter"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="25px" height="25px" viewBox="0 0 94.135 94.135" style="enable-background:new 0 0 94.135 94.135;" xml:space="preserve">
<g>
	<path d="M39.11,67.145c2.201,2.27,4.872,3.404,8.011,3.404h22.612c3.135,0,5.83,1.159,8.072,3.475   c2.245,2.312,3.364,5.084,3.364,8.32s-1.119,6.018-3.364,8.324c-2.242,2.311-4.928,3.467-8.07,3.467H47.131   c-9.416,0-17.462-3.445-24.143-10.344c-6.686-6.895-10.026-15.202-10.026-24.919v-47.07c0-3.329,1.114-6.13,3.34-8.4   C18.527,1.136,21.247,0,24.457,0c3.115,0,5.796,1.155,8.016,3.473c2.229,2.309,3.344,5.081,3.344,8.321v11.791h33.885   c3.148,0,5.847,1.158,8.098,3.471c2.253,2.311,3.373,5.086,3.373,8.325c0,3.233-1.12,6.009-3.365,8.321   c-2.242,2.311-4.936,3.468-8.072,3.468H35.814v11.691C35.814,62.107,36.911,64.867,39.11,67.145z" fill="#084848"/>
</g>

</svg>
					  </i></a></li>
					  <li><a target="_blank" href="https://www.instagram.com/doradobetlatam/" class="social-icon" style=" width: 25px; height: 25px; display: inline-block; "> <i class="fa fa-instagram"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="25px" height="25px" viewBox="0 0 97.395 97.395" style="enable-background:new 0 0 97.395 97.395;" xml:space="preserve">
<g>
	<path d="M12.501,0h72.393c6.875,0,12.5,5.09,12.5,12.5v72.395c0,7.41-5.625,12.5-12.5,12.5H12.501C5.624,97.395,0,92.305,0,84.895   V12.5C0,5.09,5.624,0,12.501,0L12.501,0z M70.948,10.821c-2.412,0-4.383,1.972-4.383,4.385v10.495c0,2.412,1.971,4.385,4.383,4.385   h11.008c2.412,0,4.385-1.973,4.385-4.385V15.206c0-2.413-1.973-4.385-4.385-4.385H70.948L70.948,10.821z M86.387,41.188h-8.572   c0.811,2.648,1.25,5.453,1.25,8.355c0,16.2-13.556,29.332-30.275,29.332c-16.718,0-30.272-13.132-30.272-29.332   c0-2.904,0.438-5.708,1.25-8.355h-8.945v41.141c0,2.129,1.742,3.872,3.872,3.872h67.822c2.13,0,3.872-1.742,3.872-3.872V41.188   H86.387z M48.789,29.533c-10.802,0-19.56,8.485-19.56,18.953c0,10.468,8.758,18.953,19.56,18.953   c10.803,0,19.562-8.485,19.562-18.953C68.351,38.018,59.593,29.533,48.789,29.533z" fill="#084848"/>
</g>
<g>
</g>
</svg>
					  </i></a></li>
					  <li><a target="_blank" href="https://www.youtube.com/channel/UCuxJjrf89zWId29oOBq7Iqg" class="social-icon" style=" width: 25px; height: 25px; display: inline-block; "> <i class="fa fa-youtube"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="25px" height="25px" viewBox="0 0 90.677 90.677" style="enable-background:new 0 0 90.677 90.677;" xml:space="preserve">
<g>
	<g>
		<path d="M82.287,45.907c-0.937-4.071-4.267-7.074-8.275-7.521c-9.489-1.06-19.098-1.065-28.66-1.06    c-9.566-0.005-19.173,0-28.665,1.06c-4.006,0.448-7.334,3.451-8.27,7.521c-1.334,5.797-1.35,12.125-1.35,18.094    c0,5.969,0,12.296,1.334,18.093c0.936,4.07,4.264,7.073,8.272,7.521c9.49,1.061,19.097,1.065,28.662,1.061    c9.566,0.005,19.171,0,28.664-1.061c4.006-0.448,7.336-3.451,8.272-7.521c1.333-5.797,1.34-12.124,1.34-18.093    C83.61,58.031,83.62,51.704,82.287,45.907z M28.9,50.4h-5.54v29.438h-5.146V50.4h-5.439v-4.822H28.9V50.4z M42.877,79.839h-4.629    v-2.785c-1.839,2.108-3.585,3.136-5.286,3.136c-1.491,0-2.517-0.604-2.98-1.897c-0.252-0.772-0.408-1.994-0.408-3.796V54.311    h4.625v18.795c0,1.084,0,1.647,0.042,1.799c0.111,0.718,0.462,1.082,1.082,1.082c0.928,0,1.898-0.715,2.924-2.166v-19.51h4.629    L42.877,79.839L42.877,79.839z M60.45,72.177c0,2.361-0.159,4.062-0.468,5.144c-0.618,1.899-1.855,2.869-3.695,2.869    c-1.646,0-3.234-0.914-4.781-2.824v2.474h-4.625V45.578h4.625v11.189c1.494-1.839,3.08-2.769,4.781-2.769    c1.84,0,3.078,0.969,3.695,2.88c0.311,1.027,0.468,2.715,0.468,5.132V72.177z M77.907,67.918h-9.251v4.525    c0,2.363,0.773,3.543,2.363,3.543c1.139,0,1.802-0.619,2.066-1.855c0.043-0.251,0.104-1.279,0.104-3.134h4.719v0.675    c0,1.491-0.057,2.518-0.099,2.98c-0.155,1.024-0.519,1.953-1.08,2.771c-1.281,1.854-3.179,2.768-5.595,2.768    c-2.42,0-4.262-0.871-5.599-2.614c-0.981-1.278-1.485-3.29-1.485-6.003v-8.941c0-2.729,0.447-4.725,1.43-6.015    c1.336-1.747,3.177-2.617,5.54-2.617c2.321,0,4.161,0.87,5.457,2.617c0.969,1.29,1.432,3.286,1.432,6.015v5.285H77.907z" fill="#084848"/>
		<path d="M70.978,58.163c-1.546,0-2.321,1.181-2.321,3.541v2.362h4.625v-2.362C73.281,59.344,72.508,58.163,70.978,58.163z" fill="#084848"/>
		<path d="M53.812,58.163c-0.762,0-1.534,0.36-2.307,1.125v15.559c0.772,0.774,1.545,1.14,2.307,1.14    c1.334,0,2.012-1.14,2.012-3.445V61.646C55.824,59.344,55.146,58.163,53.812,58.163z" fill="#084848"/>
		<path d="M56.396,34.973c1.705,0,3.479-1.036,5.34-3.168v2.814h4.675V8.82h-4.675v19.718c-1.036,1.464-2.018,2.188-2.953,2.188    c-0.626,0-0.994-0.37-1.096-1.095c-0.057-0.153-0.057-0.722-0.057-1.817V8.82h-4.66v20.4c0,1.822,0.156,3.055,0.414,3.836    C53.854,34.363,54.891,34.973,56.396,34.973z" fill="#084848"/>
		<path d="M23.851,20.598v14.021h5.184V20.598L35.271,0h-5.242l-3.537,13.595L22.812,0h-5.455c1.093,3.209,2.23,6.434,3.323,9.646    C22.343,14.474,23.381,18.114,23.851,20.598z" fill="#084848"/>
		<path d="M42.219,34.973c2.342,0,4.162-0.881,5.453-2.641c0.981-1.291,1.451-3.325,1.451-6.067v-9.034    c0-2.758-0.469-4.774-1.451-6.077c-1.291-1.765-3.11-2.646-5.453-2.646c-2.33,0-4.149,0.881-5.443,2.646    c-0.993,1.303-1.463,3.319-1.463,6.077v9.034c0,2.742,0.47,4.776,1.463,6.067C38.069,34.092,39.889,34.973,42.219,34.973z     M39.988,16.294c0-2.387,0.724-3.577,2.231-3.577c1.507,0,2.229,1.189,2.229,3.577v10.852c0,2.387-0.722,3.581-2.229,3.581    c-1.507,0-2.231-1.194-2.231-3.581V16.294z" fill="#084848"/>
	</g>
</g>

</svg>
					  </i></a></li>
					  <li><a target="_blank" href="https://plus.google.com/u/0/109119436366679125879/" class="social-icon" style=" width: 25px; height: 25px; display: inline-block; "> <i class="fa fa-google-plus"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="25px" height="25px" viewBox="0 0 96.669 96.669" style="enable-background:new 0 0 96.669 96.669;" xml:space="preserve">
<g>
	<g>
		<path d="M50.91,55.189l-4.533-3.522c-1.38-1.144-3.27-2.656-3.27-5.422c0-2.778,1.889-4.544,3.527-6.18    c5.279-4.157,10.557-8.581,10.557-17.903c0-9.586-6.031-14.629-8.923-17.022h7.795L64.244,0H39.459    C32.658,0,22.856,1.608,15.68,7.533c-5.408,4.666-8.046,11.099-8.046,16.892c0,9.831,7.548,19.798,20.88,19.798    c1.259,0,2.636-0.124,4.022-0.252c-0.623,1.515-1.252,2.777-1.252,4.917c0,3.905,2.006,6.299,3.774,8.567    c-5.663,0.39-16.237,1.018-24.033,5.809c-7.424,4.415-9.684,10.84-9.684,15.377c0,9.334,8.8,18.028,27.045,18.028    c21.636,0,33.089-11.971,33.089-23.823C61.477,64.139,56.447,59.854,50.91,55.189z M34.431,40.691    c-10.824,0-15.727-13.992-15.727-22.434c0-3.288,0.623-6.682,2.763-9.333C23.486,6.4,27,4.762,30.281,4.762    c10.434,0,15.846,14.118,15.846,23.197c0,2.271-0.251,6.296-3.144,9.207C40.96,39.187,37.574,40.691,34.431,40.691z     M34.555,91.387c-13.46,0-22.139-6.438-22.139-15.392c0-8.949,8.048-11.978,10.816-12.979c5.281-1.777,12.076-2.024,13.21-2.024    c1.258,0,1.887,0,2.889,0.126c9.568,6.81,13.721,10.203,13.721,16.65C53.053,85.573,46.635,91.387,34.555,91.387z" fill="#084848"/>
		<polygon points="82.679,40.499 82.679,27.894 76.455,27.894 76.455,40.499 63.869,40.499 63.869,46.793 76.455,46.793     76.455,59.477 82.679,59.477 82.679,46.793 95.328,46.793 95.328,40.499   " fill="#084848"/>
	</g>
</g>

</svg></i></a></li>
				</ul>
			</div>	
		</div>

		<div class="footer">
				<p>© 2017 Doradobet. Todos los derechos reservados.</p>			
		</div>
	</div>
</body>
</html>

';
        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $c_subject;
        //$mail->Body = 'This is the HTML message body <b>in bold!</b>';
        $mail->msgHTML($message);

        $ret = $mail->send();

        return true;
    } catch (Exception $e) {

        return false;
    }

}


function obtenerMenu()
{

    $menus_string = json_decode(
        '[
                {"id": "dashboard", "icon": "icon-dashboard", "value": "Dashboards"},
                
                {
                    "id": "productsFather", "icon": "icon-cubes", "value": "Products", "data": [
                        {"id": "providers", "value": "Providers"},
                        {"id": "products", "value": "Products"},
                        {"id": "partnersProducts", "value": "Partners Products"},
                        {"id": "partnersProductsCountry", "value": "Partners Products Country"},
                        {"id": "categories", "value": "Categories"},
                        {"id": "categoriesProducts", "value": "CategoriesProducts"}
                        
                    ]
                },
                {"id": "players", "icon": "icon-players", "value": "Players"},
                {
                    "id": "reports", "icon": "icon-pie-chart", "value": "Reports", "data": [
                        {"id": "depositReport", "value": "Deposit Report"},
                        {"id": "autoexclusionUsers", "value": "Autoexclusion Users"},
                        {"id": "casinoGamesReport", "value": "Casino Games Report"},
                        {"id": "bonusReport", "value": "Bonus Report"},
                        {"id": "playersReport", "value": "Players Report"},
                        {"id": "historicalCashFlow", "value": "Historical Cash Flow"},
                        {"id": "summaryCashFlow", "value": "Summary Cash Flow"},
                        {"id": "informeGerencial", "value": "Gerencial Report"},
                        {"id": "betsReport", "value": "Bets Report"}
                    ]
                },
                {
                    "id": "security", "icon": "icon-security", "value": "Security", "data": [
                        {"id": "adminUser", "value": "Admin User"},
                        {"id": "contingency", "value": "Contingency"},
                        {"id": "menus", "value": "Menus"},
                        {"id": "profile", "value": "Profile"},
                        {"id": "profileOptions", "value": "Profile - Options"},
                        {"id": "subMenu", "value": "Sub Menu"},
                        {"id": "userProfile", "value": "User Profile"}
                    ]
                },
                {
                    "id": "teacher", "icon": "icon-storage", "value": "Teacher", "data": [
                        {"id": "qualifying", "value": "Qualifying"},
                        {"id": "franchisee", "value": "Franchisee"},
                        {"id": "registeredDocuments", "value": "Registered Documents"}
                    ]
                },
                {
                    "id": "Management", "icon": "icon-database", "value": "Management", "data": [
                        {"id": "adjustPayment", "value": "Adjust Payment"},
                        {"id": "assignmentQuota", "value": "assignment Quota"},
                        {"id": "bonus", "value": "Bonus"},
                        {"id": "eliminateNoteWithdraw", "value": "Eliminate Note Withdraw"},
                        {"id": "managementNetwork", "value": "Management Network"},
                        {"id": "registerFast", "value": "Register Fast"},
                        {"id": "reprintCheck", "value": "Reprint Check"},
                        {"id": "reversionReload", "value": "Reversion Reload"},
                        {"id": "managementContact", "value": "Management Contact"}
                    ]
                },
                {
                    "id": "Cash", "icon": "icon-banknote", "value": "Cash", "data": [
                        {"id": "flujoCaja", "value": "Flujo de Caja"},
                        {"id": "pagoPremio", "value": "Pago Premio"},
                        {"id": "pagoNotaRetiro", "value": "Pago Nota Retiro"},
                        {"id": "recargarCredito", "value": "Recargar Credito"}
                    ]
                },
                {
                    "id": "queries", "icon": "icon-file-text", "value": "Queries", "data": [
                        {"id": "flujoCajaHistorico", "value": "Flujo Caja Historico"},
                        {"id": "flujoCajaResumido", "value": "Flujo Caja Resumido"},
                        {"id": "informeCasino", "value": "Informe Casino"},
                        {"id": "informeGerencial", "value": "Informe Gerencial"},
                        {"id": "listadoRecargasRetiros", "value": "Listado Recargas Retiros"},
                        {"id": "premiosPendientesPagar", "value": "Premios Pendientes Pagar"},
                        {"id": "consultaOnlineDetalle", "value": "Consulta Online Detalle"},
                        {"id": "consultaOnlineResumen", "value": "Consulta Online Resumen"}
                    ]
                },
                {
                    "id": "betShopManagement", "icon": "icon-shop", "value": "Bet Shop Management", "data": [
                        {"id": "betShop", "value": "Bet Shop"},
                        {"id": "managePointsGraphics", "value": "Manage Points Graphics"}
                    ]
                },
                {
                    "id": "agentSystem", "icon": "icon-user-secret", "value": "Agent System", "data": [
                        {"id": "myAccount", "value": "My Account"},
                        {"id": "agentList", "value": "Agent List"},
                        {"id": "agentsTree", "value": "Agents Tree"},
                        {"id": "subAccounts", "value": "Sub Accounts"},
                        {"id": "playersList", "value": "Players List"},
                        {"id": "transfers", "value": "Transfers"},
                        {"id": "groupManagement", "value": "Group Management"}
                    ]
                },
                {
                    "id": "financial", "icon": "icon-financial", "value": "Financial", "data": [
                        {"id": "pagoPremio", "value": "Pago Premio"},
                        {"id": "pagoNotaRetiro", "value": "Pago Nota Retiro"},
                        {"id": "recargarCredito", "value": "Recargar Credito"},
                        {"id": "depositRequests", "value": "Deposit Requests"},
                        {"id": "withdrawalRequests", "value": "Withdrawal Requests"},
                        {"id": "transactionss", "value": "Transactions"}
                    ]
                },
                {
                    "id": "tools", "icon": "icon-tools", "value": "Tools", "data": [
                        {"id": "partnerSettings", "value": "Partner Settings"},
                        {"id": "translationManager", "value": "Translation Manager"},
                        {"id": "emailTemplate", "value": "Email Template"},
                        {"id": "messagesList", "value": "Messages List"}
                    ]
                },

                {"id": "transactions", "value": "Transactions", "icon": "mdi mdi-cart"},
                {"id": "customers", "value": "Customers", "icon": "mdi mdi-account-box"},
                {"id": "payhistoryview", "value": "Payment History", "icon": "mdi mdi-chart-areaspline"},
                {"id": "widgets", "value": "Widgets", "icon": "mdi mdi-widgets"},
                {"id": "demos", "value": "Demos", "icon": "mdi mdi-monitor-dashboard"},
                {"id": "prices", "value": "Prices", "icon": "mdi mdi-currency-usd"},
                {"id": "tutorials", "value": "Tutorials", "icon": "mdi mdi-school"}
            ]'
    );

    $menu_string = json_decode(
        '[
                {"id": "dashboard", "icon": "icon-dashboard", "value": "Dashboards"},
{
                    "id": "myconfiguration", "icon": "icon-tools", "value": "Mi Configuracion", "data": [
                        {"id": "myConfiguration.myInformation", "value": "Mi Informacion"},
                        {"id": "myConfiguration.changeMyPassword", "value": "Cambiar Contraseña"},
                        {"id": "myConfiguration.qrgoogle", "value": "QR Google"}
                        
                    ]
                },
                
                {
                    "id": "productsFather", "icon": "icon-cubes", "value": "Products", "data": [
                        {"id": "providers", "value": "Providers"},
                        {"id": "products", "value": "Products"},
                        {"id": "partnersProviders", "value": "Partners Proveedores"},
                        {"id": "partnersTypeProduct", "value": "Partners Tipo Producto"},
                        {"id": "partnersProducts", "value": "Partners Products"},
                        {"id": "partnersProductsCountry", "value": "Partners Products Country"},
                        {"id": "categories", "value": "Categories"},
                        {"id": "categoriesProducts", "value": "CategoriesProducts"}
                        
                    ]
                },
                {"id": "adminUserManagement", "icon": "icon-players", "show":"false", "value": "adminUserManagement"},
                {"id": "customers", "icon": "icon-players", "show":"false", "value": "Jugadores"},
                {"id": "customersAggregator", "icon": "icon-players", "show":"false", "value": "Jugadores"},
                {"id": "agentListManagement", "icon": "icon-players", "show":"false", "value": "Jugadores"},
                {"id": "addAgentListManagement", "icon": "icon-players", "show":"false", "value": "addAgentListManagement"},
                {"id": "addBetShopManagement", "icon": "icon-players", "show":"false", "value": "addBetShopManagement"},
                {"id": "addAdminUserManagement", "icon": "icon-players", "show":"false", "value": "addAdminUserManagement"},
                {"id": "settings", "icon": "icon-players", "show":"false", "value": "settings"},

                {"id": "playersList", "icon": "icon-players", "value": "Jugadores"},
                {"id": "playersListAggregator", "icon": "icon-players", "value": "Jugadores"},
                {
                    "id": "reports", "icon": "icon-pie-chart", "value": "Reportes", "data": [
                        {"id": "depositReport", "value": "Reporte de depósitos"},
                        {"id": "autoexclusionUsers", "value": "Autoexclusiones de Usuario"},
                        {"id": "casinoGamesReport", "value": "Reporte de casino "},
                        {"id": "bonusReport", "value": "Reporte de bonos"},
                        {"id": "playersReport", "value": "Reporte de Jugadores"},
                        {"id": "historicalCashFlow", "value": "Flujo de Caja Histórico"},
                        {"id": "summaryCashFlow", "value": "Flujo de Caja Resumido"},
                        {"id": "informeGerencial", "value": "Informe Gerencial"},
                        {"id": "betsReport", "value": "Reporte de Apuestas"},
                        {"id": "usuarioOnlineResumido", "value": "Usuario online Resumido"},
                        {"id": "relationUserAggregator", "value": "Usuario - Agregator"},
                        {"id": "paidPendingAwards", "value": "premiosPendientesPagar"}

                    ]
                },
                
                
                {
                    "id": "accounting", "icon": "icon-security", "value": "Contabilidad", "data": [
                        {"id": "accounting.costCenter", "value": "Centros de costo", "add": true},
                        {"id": "accounting.area", "value": "Area", "add": true},
                        {"id": "accounting.position", "value": "Cargo", "add": true},
                        {"id": "accounting.typeCenterPosition", "value": "Tipos", "add": true},
                        {"id": "accounting.employees", "value": "Empleados", "add": true},
                        {"id": "accounting.expenses", "value": "Egresos", "add": true},
                        {"id": "accounting.incomes", "value": "Ingresos", "add": true},
                        {"id": "accounting.providers", "value": "Proveedores terceros", "add": true},
                        {"id": "accounting.concepts", "value": "Conceptos", "add": true},
                        {"id": "accounting.accounts", "value": "Cuentas", "add": true},
                        {"id": "accounting.productsThirdBetShop", "value": "Productos terceros", "add": true},
                        {"id": "accounting.productsThirdByBetShop", "value": "Productos terceros Punto de venta", "add": true},
                        {"id": "accounting.closingDayReport", "value": "Reporte cierre de dia"},
                        {"id": "accounting.squareDayReport", "value": "Reporte de cuadre de dia", "add": true}

                    ]
                },
                {
                    "id": "security", "icon": "icon-security", "value": "Security", "data": [
                        {"id": "approvalLogs", "value": "Aprobar logs", "add": true},

                        {"id": "adminUser", "value": "Admin User"},
                        {"id": "usuariosbloqueados", "value": "Usuarios Bloqueados"},
                        {"id": "contingency", "value": "Contingency"},
                        {"id": "menus", "value": "Menus"},
                        {"id": "profile", "value": "Profile"},
                        {"id": "profileOptions", "value": "Profile - Options"},
                        {"id": "subMenu", "value": "Sub Menu"},
                        {"id": "userProfile", "value": "User Profile"},
                        {"id": "competitors.competitors", "value": "Competidores"}
                    ]
                },
                {
                    "id": "management", "icon": "icon-security", "value": "Gestion", "data": [
                        {"id": "activateRegistration", "value": "Activar Registros", "add": true}
                    ]
                },
                {
                    "id": "Cash", "icon": "icon-banknote", "value": "Caja", "data": [
                        {"id": "flujoCaja", "value": "Flujo de Caja"},
                        {"id": "pagoPremio", "value": "Pago Premio"},
                        {"id": "pagoNotaRetiro", "value": "Pago Nota Retiro"},
                        {"id": "recargarCredito", "value": "Recargar Credito"}
                    ]
                },
                
                {
                    "id": "messages", "icon": "icon-pie-chart", "value": "Mensajes", "data": [
                        {"id": "messages.messageList", "value": "Lista"}
                                            ]
                },
                
                {
                    "id": "tools", "icon": "icon-pie-chart", "value": "Herramientas", "data": [
                        {"id": "tools.translationManager", "value": "Traducción"},
                                                {"id": "tools.uploadImage", "value": "Subir imagen"}

                                            ]
                },
                
                
                {
                    "id": "betShopManagement", "icon": "icon-shop", "value": "Gestión Punto de Venta", "data": [
                        {"id": "betShop", "value": "Punto de Venta"},
                        {"id": "cashiers", "value": "Cajeros"},
                        {"id": "managePointsGraphics", "value": "Gestión Puntos Gráfico"},
                        {"id": "betShopCompetence", "value": "Puntos de venta Competencia"}
                    ]
                },
                {
                    "id": "agentSystem", "icon": "icon-user-secret", "value": "Agentes", "data": [
                        {"id": "agentList", "value": "Lista de Agentes"},
                        {"id": "agentsTree", "value": "Árbol de Agentes"},
                        {"id": "agentsInform", "value": "Informe de Agentes"},
                        {"id": "agentTransfers", "value": "Transferencias"},
                        {"id": "agent.requestsAgent", "value": "Solicitudes"},
                        {"id": "agent.requirementsAgent", "value": "Requisitos"}

                    ]
                },
                {
                    "id": "financial", "icon": "icon-financial", "value": "Financiero", "data": [
                        {"id": "depositRequests", "value": "Solicitudes de Deposito"},
                        {"id": "withdrawalRequests", "value": "Solicitudes de Retiro"}
                    ]
                }   ,
                {"id": "closeBox", "icon": "icon-financial", "value": "Cierre de caja"}
                         ]'
    );

    /*
        switch ($_SESSION["win_perfil2"]) {
            case "PUNTOVENTA":
                $menu_string = json_decode(
                    '[
                    {"id": "dashboard", "icon": "icon-dashboard", "value": "Dashboards"},
                                    {"id": "betting", "icon": "icon-dashboard", "value": "Apuestas"},
                    {"id": "bettingVirtual", "icon": "icon-dashboard", "value": "Apuestas Virtuales"},
                    {
                        "id": "myconfiguration", "icon": "icon-cubes", "value": "Mi Configuracion", "data": [
                            {"id": "myConfiguration.myInformation", "value": "Mi Informacion"},
                            {"id": "myConfiguration.changeMyPassword", "value": "Cambiar Contraseña"},
                            {"id": "myConfiguration.qrgoogle", "value": "QR Google"}

                        ]
                    },

                    {
                        "id": "reports", "icon": "icon-pie-chart", "value": "Reportes", "data": [
                            {"id": "summaryCashFlow", "value": "Flujo de Caja Resumido"},
                                                                            {"id": "betsReportSecond", "value": "Reporte de Apuestas"}

                                                ]
                    },
                    {
                        "id": "Cash", "icon": "icon-banknote", "value": "Caja", "data": [
                            {"id": "flujoCaja", "value": "Flujo de Caja"},
                            {"id": "pagoPremio", "value": "Pago Premio"},
                            {"id": "pagoNotaRetiro", "value": "Pago Nota Retiro"},
                            {"id": "recargarCredito", "value": "Recargar Credito"}
                        ]
                    },
                    {
                        "id": "betShopManagement", "icon": "icon-shop", "value": "Gestión Punto de Venta", "data": [
                            {"id": "cashiers", "value": "Cajeros"}
                                                ]
                    }
                           ]'
                );

                break;

            case "CAJERO":
                $menu_string = json_decode(
                    '[
                    {"id": "dashboard", "icon": "icon-dashboard", "value": "Dashboards"},
                    {"id": "betting", "icon": "icon-dashboard", "value": "Apuestas"},
                    {"id": "bettingVirtual", "icon": "icon-dashboard", "value": "Apuestas Virtuales"},
                    {
                        "id": "myconfiguration", "icon": "icon-cubes", "value": "Mi Configuracion", "data": [
                            {"id": "myConfiguration.myInformation", "value": "Mi Informacion"},
                            {"id": "myConfiguration.changeMyPassword", "value": "Cambiar Contraseña"},
                            {"id": "myConfiguration.qrgoogle", "value": "QR Google"}

                        ]
                    },
                    {
                        "id": "reports", "icon": "icon-pie-chart", "value": "Reportes", "data": [
                            {"id": "summaryCashFlow", "value": "Flujo de Caja Resumido"},
                                                    {"id": "betsReportSecond", "value": "Reporte de Apuestas"}

                                                ]
                    },
                    {
                        "id": "Cash", "icon": "icon-banknote", "value": "Caja", "data": [
                            {"id": "flujoCaja", "value": "Flujo de Caja"},
                            {"id": "pagoPremio", "value": "Pago Premio"},
                            {"id": "pagoNotaRetiro", "value": "Pago Nota Retiro"},
                            {"id": "recargarCredito", "value": "Recargar Credito"}
                        ]
                    }
                           ]'
                );

                break;

            case "CONCESIONARIO":

                $menu_string = json_decode(
                    '[
                    {"id": "dashboard", "icon": "icon-dashboard", "value": "Dashboards"},
    {
                        "id": "myconfiguration", "icon": "icon-cubes", "value": "Mi Configuracion", "data": [
                            {"id": "myConfiguration.myInformation", "value": "Mi Informacion"},
                            {"id": "myConfiguration.changeMyPassword", "value": "Cambiar Contraseña"},
                            {"id": "myConfiguration.qrgoogle", "value": "QR Google"}

                        ]
                    },
                    {"id": "agentListManagement", "icon": "icon-players", "show":"false", "value": "Jugadores"},
                    {"id": "addAgentListManagement", "icon": "icon-players", "show":"false", "value": "addAgentListManagement"},
                    {"id": "addBetShopManagement", "icon": "icon-players", "show":"false", "value": "addBetShopManagement"},
                    {
                        "id": "reports", "icon": "icon-pie-chart", "value": "Reportes", "data": [
                            {"id": "depositReport", "value": "Reporte de depósitos"},
                            {"id": "summaryCashFlow", "value": "Flujo de Caja Resumido"},
                            {"id": "betsReportSecond", "value": "Reporte de Apuestas"}
                        ]
                    },
                    {
                        "id": "Cash", "icon": "icon-banknote", "value": "Caja", "data": [
                            {"id": "flujoCaja", "value": "Flujo de Caja"}
                                                ]
                    },
                    {
                        "id": "betShopManagement", "icon": "icon-shop", "value": "Gestión Punto de Venta", "data": [
                            {"id": "betShop", "value": "Punto de Venta"},
                            {"id": "cashiers", "value": "Cajeros"}
                        ]
                    },
                    {
                        "id": "agentSystem", "icon": "icon-user-secret", "value": "Agentes", "data": [
                            {"id": "agentList", "value": "Lista de Agentes"},
                            {"id": "agentsTree", "value": "Árbol de Agentes"},
                            {"id": "agentsInform", "value": "Informe de Agentes"},
                            {"id": "agentTransfers", "value": "Transferencias"}

                        ]
                    }
                             ]'
                );


                break;

        }

    */
    $PerfilSubmenu = new PerfilSubmenu();

    $Perfil_id = $_SESSION["win_perfil2"];
    $Usuario_id = $_SESSION["usuario"];
    $MaxRows = $params->MaxRows;
    $OrderedItem = $params->OrderedItem;
    $SkeepRows = $params->SkeepRows;

    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }

    if ($MaxRows == "") {
        $MaxRows = 100000;
    }

    $mismenus = "0";

    $rules = [];

    array_push($rules, array("field" => "menu.version", "data" => "2", "op" => "eq"));
    array_push($rules, array("field" => "perfil_submenu.perfil_id", "data" => "$Perfil_id", "op" => "eq"));

    if ($Perfil_id == "CUSTOM") {
        array_push($rules, array("field" => "perfil_submenu.usuario_id", "data" => "$Usuario_id", "op" => "eq"));
    }

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);


    $menus = $PerfilSubmenu->getPerfilSubmenusCustom(" menu.*,submenu.*,perfil_submenu.* ", "menu.menu_id", "asc", $SkeepRows, $MaxRows, $json, true);

    $menus = json_decode($menus);

    $menus3 = [];
    $arrayf = [];
    $submenus = [];

    $menus_string = array();

    foreach ($menus->data as $key => $value) {

        $m = [];
        $m["Id"] = $value->{"menu.menu_id"};
        $m["Name"] = $value->{"menu.descripcion"};

        $array = [];

        $array["Id"] = $value->{"submenu.submenu_id"};
        $array["Name"] = $value->{"submenu.descripcion"};
        $array["Pagina"] = $value->{"submenu.pagina"};
        $array["IsGiven"] = true;
        $array["Action"] = "view";
        $array["add"] = ($value->{"perfil_submenu.adicionar"} == "true") ? true : false;
        $array["edit"] = ($value->{"perfil_submenu.editar"} == "true") ? true : false;
        $array["delete"] = ($value->{"perfil_submenu.eliminar"} == "true") ? true : false;
        $array["Add"] = ($value->{"perfil_submenu.adicionar"} == "true") ? true : false;
        $array["Edit"] = ($value->{"perfil_submenu.editar"} == "true") ? true : false;
        $array["Delete"] = ($value->{"perfil_submenu.eliminar"} == "true") ? true : false;

        $mismenus = $mismenus . "," . $array["Id"];

        if ($arrayf["Id"] != "" && $m["Id"] != $arrayf["Id"]) {
            array_push($menus_string, $arrayf["Pagina"]);

            $arrayf["Permissions"] = $submenus;
            array_push($menus3, $arrayf);
            // $submenus = [];
        }
        array_push($menus_string, $array["Pagina"]);

        $arrayf["Id"] = $value->{"menu.menu_id"};
        $arrayf["Name"] = $value->{"menu.descripcion"};
        $arrayf["Pagina"] = $value->{"menu.pagina"};

        array_push($submenus, $array);
    }
    array_push($menus_string, $arrayf["Pagina"]);


    if ($Perfil_id != "CUSTOM") {

        $rules = [];

        array_push($rules, array("field" => "menu.version", "data" => "2", "op" => "eq"));
        array_push($rules, array("field" => "perfil_submenu.perfil_id", "data" => "$Perfil_id", "op" => "eq"));

        array_push($rules, array("field" => "perfil_submenu.usuario_id", "data" => "$Usuario_id", "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);


        $menus = $PerfilSubmenu->getPerfilSubmenusCustom(" menu.*,submenu.*,perfil_submenu.* ", "menu.menu_id", "asc", $SkeepRows, $MaxRows, $json, true);

        $menus = json_decode($menus);

        $menus3 = [];
        $arrayf = [];

        foreach ($menus->data as $key => $value) {

            $m = [];
            $m["Id"] = $value->{"menu.menu_id"};
            $m["Name"] = $value->{"menu.descripcion"};

            $array = [];

            $array["Id"] = $value->{"submenu.submenu_id"};
            $array["Name"] = $value->{"submenu.descripcion"};
            $array["Pagina"] = $value->{"submenu.pagina"};
            $array["IsGiven"] = true;
            $array["Action"] = "view";
            $array["add"] = ($value->{"perfil_submenu.adicionar"} == "true") ? true : false;
            $array["edit"] = ($value->{"perfil_submenu.editar"} == "true") ? true : false;
            $array["delete"] = ($value->{"perfil_submenu.eliminar"} == "true") ? true : false;
            $array["Add"] = ($value->{"perfil_submenu.adicionar"} == "true") ? true : false;
            $array["Edit"] = ($value->{"perfil_submenu.editar"} == "true") ? true : false;
            $array["Delete"] = ($value->{"perfil_submenu.eliminar"} == "true") ? true : false;

            $mismenus = $mismenus . "," . $array["Id"];

            if ($arrayf["Id"] != "" && $m["Id"] != $arrayf["Id"]) {
                array_push($menus_string, $arrayf["Pagina"]);

                $arrayf["Permissions"] = $submenus;
                array_push($menus3, $arrayf);
                // $submenus = [];
            }
            array_push($menus_string, $array["Pagina"]);

            $arrayf["Id"] = $value->{"menu.menu_id"};
            $arrayf["Name"] = $value->{"menu.descripcion"};
            $arrayf["Pagina"] = $value->{"menu.pagina"};

            array_push($submenus, $array);
        }
        array_push($menus_string, $arrayf["Pagina"]);


        $rules = [];

        array_push($rules, array("field" => "menu.version", "data" => "2", "op" => "eq"));
        array_push($rules, array("field" => "perfil_submenu.perfil_id", "data" => "CUSTOM", "op" => "eq"));

        array_push($rules, array("field" => "perfil_submenu.usuario_id", "data" => "$Usuario_id", "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);


        $menus = $PerfilSubmenu->getPerfilSubmenusCustom(" menu.*,submenu.*,perfil_submenu.* ", "menu.menu_id", "asc", $SkeepRows, $MaxRows, $json, true);

        $menus = json_decode($menus);

        $menus3 = [];
        $arrayf = [];

        foreach ($menus->data as $key => $value) {

            $m = [];
            $m["Id"] = $value->{"menu.menu_id"};
            $m["Name"] = $value->{"menu.descripcion"};

            $array = [];

            $array["Id"] = $value->{"submenu.submenu_id"};
            $array["Name"] = $value->{"submenu.descripcion"};
            $array["Pagina"] = $value->{"submenu.pagina"};
            $array["IsGiven"] = true;
            $array["Action"] = "view";
            $array["add"] = ($value->{"perfil_submenu.adicionar"} == "true") ? true : false;
            $array["edit"] = ($value->{"perfil_submenu.editar"} == "true") ? true : false;
            $array["delete"] = ($value->{"perfil_submenu.eliminar"} == "true") ? true : false;
            $array["Add"] = ($value->{"perfil_submenu.adicionar"} == "true") ? true : false;
            $array["Edit"] = ($value->{"perfil_submenu.editar"} == "true") ? true : false;
            $array["Delete"] = ($value->{"perfil_submenu.eliminar"} == "true") ? true : false;

            $mismenus = $mismenus . "," . $array["Id"];

            if ($arrayf["Id"] != "" && $m["Id"] != $arrayf["Id"]) {
                array_push($menus_string, $arrayf["Pagina"]);

                $arrayf["Permissions"] = $submenus;
                array_push($menus3, $arrayf);
                // $submenus = [];
            }
            array_push($menus_string, $array["Pagina"]);

            $arrayf["Id"] = $value->{"menu.menu_id"};
            $arrayf["Name"] = $value->{"menu.descripcion"};
            $arrayf["Pagina"] = $value->{"menu.pagina"};

            array_push($submenus, $array);
        }
        array_push($menus_string, $arrayf["Pagina"]);


    }

    $submenus = json_decode(json_encode($submenus));


    foreach ($menu_string as $key => $item) {
        $continuar = true;

        if (!in_array($item->id, $menus_string)) {

            unset($menu_string[$key]);
            $continuar = false;

        } else {
            $searchedValue = $item->id;
            $item2 = reset(array_filter(
                $submenus,
                function ($e) use (&$searchedValue) {
                    return $e->Pagina == $searchedValue;
                }
            ));

            $item->add = $item2->add;
            $item->edit = $item2->edit;
            $item->delete = $item2->delete;
            $item->add = true;
            $item->edit = true;
            $item->delete = true;

        }

        if ($continuar) {
            if (oldCount($item->data) > 0) {

                foreach ($item->data as $key2 => $datum) {
                    if (!in_array($datum->id, $menus_string)) {
                        unset($menu_string[$key]->data[$key2]);

                    } else {
                        $searchedValue = $datum->id;
                        $item3 = reset(array_filter(
                            $submenus,
                            function ($e) use (&$searchedValue) {
                                return $e->Pagina == $searchedValue;
                            }
                        ));

                        $datum->add = $item3->add;
                        $datum->edit = $item3->edit;
                        $datum->delete = $item3->delete;
                        $datum->add = true;
                        $datum->edit = true;
                        $datum->delete = true;

                    }


                }

            }
        }
    }


    foreach ($submenus as $key => $item) {
        $continuar = true;

        $searchedValue = $item->Pagina;

        $item2 = reset(array_filter(
            $menu_string,
            function ($e) use (&$searchedValue) {
                return $e->Pagina == $searchedValue;
            }
        ));

        if ($item2 == null || $item2->id == null || $item2->id == "") {
            $itemD = array(
                "id" => $item->Pagina,
                "add" => true,
                "edit" => true,
                "delete" => true,
                "show" => "false"

            );
            array_push($menu_string, $itemD);

        }
    }


    $menu_string2 = array();
    foreach ($menu_string as $key => $item) {
        array_push($menu_string2, $item);

        if (oldCount($item->data) > 0) {
            $arr = $item->data;
            $menu_string2[oldCount($menu_string2) - 1]->data = array();

            foreach ($arr as $key2 => $datum) {
                array_push($menu_string2[oldCount($menu_string2) - 1]->data, $datum);
            }

        }
    }


    $menu_string3 = json_decode(
        '[
                {"id": "dashboard", "icon": "icon-dashboard", "value": "Dashboards"},
{
                    "id": "myconfiguration", "icon": "icon-tools", "value": "Mi Configuracion", "data": [
                        {"id": "myConfiguration.myInformation", "value": "Mi Informacion"},
                        {"id": "myConfiguration.changeMyPassword", "value": "Cambiar Contraseña"},
                        {"id": "myConfiguration.qrgoogle", "value": "QR Google"}
                        
                    ]
                },
                
                {
                    "id": "productsFather", "icon": "icon-cubes", "value": "Products", "data": [
                        {"id": "providers", "value": "Providers"},
                        {"id": "products", "value": "Products"},
                        {"id": "partnersProviders", "value": "Partners Proveedores"},
                        {"id": "partnersTypeProduct", "value": "Partners Tipo Producto"},
                        {"id": "partnersProducts", "value": "Partners Products"},
                        {"id": "partnersProductsCountry", "value": "Partners Products Country"},
                        {"id": "categories", "value": "Categories"},
                        {"id": "categoriesProducts", "value": "CategoriesProducts"}
                        
                    ]
                },
                {"id": "adminUserManagement", "icon": "icon-players", "show":"false", "value": "adminUserManagement"},
                {"id": "customers", "icon": "icon-players", "show":"false", "value": "Jugadores"},
                {"id": "agentListManagement", "icon": "icon-players", "show":"false", "value": "Jugadores"},
                {"id": "addAgentListManagement", "icon": "icon-players", "show":"false", "value": "addAgentListManagement"},
                {"id": "addBetShopManagement", "icon": "icon-players", "show":"false", "value": "addBetShopManagement"},
                {"id": "addAdminUserManagement", "icon": "icon-players", "show":"false", "value": "addAdminUserManagement"},
                {"id": "leagues.addLeagueManagement", "icon": "icon-players", "show":"false", "value": "Añadir ligas"},
                {"id": "machine.addMachineManagement", "icon": "icon-players", "show":"false", "value": "Añadir Maquina"},
                {"id": "machine.machineManagement", "icon": "icon-players", "show":"false", "value": "Detalles maquina "},
                {"id": "settings", "icon": "icon-players", "show":"false", "value": "settings"},
                {"id": "withdrawalRequestsApprove", "icon": "icon-players", "show":"false", "value": "withdrawalRequestsApprove"},

                {"id": "playersList", "icon": "icon-players", "value": "Jugadores"},
                 {
                    "id": "partner", "icon": "icon-partner", "value": "Partner", "data": [
                        {"id": "partner.PartnerSettings", "value": "PartnerSettings"}
                                            ]
                },
                                {"id": "leagues.leaguesManagement", "icon": "icon-players", "show":"false", "value": "Jugadores"},
                {
                        "id": "leagues", "icon": "icon-pie-chart", "value": "Ligas", "data": [
                            {"id": "leagues.leaguesList", "value": "Lista de Ligas"}
                                                ]
                    },
                {
                    "id": "requests", "icon": "icon-pie-chart", "value": "Request", "data": [
                        {"id": "requests.registrationRequests", "value": "registrationRequests"}
                                            ]
                },
                {
                    "id": "machine", "icon": "icon-pie-chart", "value": "Maquinas", "data": [
                        {"id": "machine.machineRegister", "value": "Lista"},
                        {"id": "machine.information", "value": "Registrar"},
                        {"id": "machine.pagoPremioMaquina", "value": "Pago Premio"},
                        {"id": "machine.pagoNotaCobro", "value": "Pago Premio"},
                                                {"id": "machine.managePointsGraphics", "value": "Maquinas Grafica"}

                                            ]
                },
                {
                    "id": "messages", "icon": "icon-pie-chart", "value": "Mensajes", "data": [
                        {"id": "messages.messageList", "value": "Lista"}
                                            ]
                },
                {
                    "id": "tools", "icon": "icon-pie-chart", "value": "Herramientas", "data": [
                        {"id": "tools.translationManager", "value": "Traducción"},
                        {"id": "tools.uploadImage", "value": "Subir imagen"}
                                            ]
                },
                
                
                {
                    "id": "reports", "icon": "icon-pie-chart", "value": "Reportes", "data": [
                        {"id": "depositReport", "value": "Reporte de depósitos"},
                        {"id": "autoexclusionUsers", "value": "Autoexclusiones de Usuario"},
                        {"id": "casinoGamesReport", "value": "Reporte de casino "},
                        {"id": "bonusReport", "value": "Reporte de bonos"},
                        {"id": "playersReport", "value": "Reporte de Jugadores"},
                        {"id": "historicalCashFlow", "value": "Flujo de Caja Histórico"},
                        {"id": "summaryCashFlow", "value": "Flujo de Caja Resumido"},
                        {"id": "informeGerencial", "value": "Informe Gerencial"},
                        {"id": "betsReport", "value": "Reporte de Apuestas"},
                        {"id": "usuarioOnlineResumido", "value": "Usuario online Resumido"},
                        {"id": "promotionalCodes", "value": "Codigos Promocionales"},
                        {"id": "relationUserAggregator", "value": "Usuario - Agregator"},
                        {"id": "paidPendingAwards", "value": "premiosPendientesPagar"}
                        
                    ]
                },
                
                {
                    "id": "accounting", "icon": "icon-security", "value": "Contabilidad", "data": [
                        {"id": "accounting.costCenter", "value": "Centros de costo", "add": true},
                        {"id": "accounting.area", "value": "Area", "add": true},
                        {"id": "accounting.position", "value": "Cargo", "add": true},
                        {"id": "accounting.typeCenterPosition", "value": "Tipos", "add": true},
                        {"id": "accounting.employees", "value": "Empleados", "add": true},
                        {"id": "accounting.expenses", "value": "Egresos", "add": true},
                        {"id": "accounting.incomes", "value": "Ingresos", "add": true},
                        {"id": "accounting.providers", "value": "Proveedores terceros", "add": true},
                        {"id": "accounting.concepts", "value": "Conceptos", "add": true},
                        {"id": "accounting.accounts", "value": "Cuentas", "add": true},
                        {"id": "accounting.productsThirdBetShop", "value": "Productos terceros", "add": true},
                        {"id": "accounting.productsThirdByBetShop", "value": "Productos terceros Punto de venta", "add": true},
                        {"id": "accounting.closingDayReport", "value": "Reporte cierre de dia"},
                        {"id": "accounting.squareDayReport", "value": "Reporte de cuadre de dia", "add": true}

                    ]
                },
                {
                    "id": "security", "icon": "icon-security", "value": "Security", "data": [
                        {"id": "approvalLogs", "value": "Aporbar Logs", "add": true},
                        {"id": "adminUser", "value": "Admin User", "add": true},
                        {"id": "usuariosbloqueados", "value": "Usuarios Bloqueados"},
                        {"id": "contingency", "value": "Contingency"},
                        {"id": "menus", "value": "Menus"},
                        {"id": "profile", "value": "Profile"},
                        {"id": "profileOptions", "value": "Profile - Options"},
                        {"id": "subMenu", "value": "Sub Menu"},
                        {"id": "userProfile", "value": "User Profile"},
                        {"id": "competitors.competitors", "value": "Competidores"}
                    ]
                },
                {
                    "id": "Cash", "icon": "icon-banknote", "value": "Caja", "data": [
                        {"id": "flujoCaja", "value": "Flujo de Caja"},
                        {"id": "pagoPremio", "value": "Pago Premio"},
                        {"id": "pagoNotaRetiro", "value": "Pago Nota Retiro"},
                        {"id": "recargarCredito", "value": "Recargar Credito"}
                    ]
                },
                {
                    "id": "betShopManagement", "icon": "icon-shop", "value": "Gestión Punto de Venta", "data": [
                        {"id": "betShop", "value": "Punto de Venta", "add": true},
                        {"id": "cashiers", "value": "Cajeros"},
                        {"id": "managePointsGraphics", "value": "Gestión Puntos Gráfico"},
                        {"id": "betShopCompetence", "value": "Puntos de venta Competencia"}
                    ]
                },
                {
                    "id": "agentSystem", "icon": "icon-user-secret", "value": "Agentes", "data": [
                        {"id": "agentList", "value": "Lista de Agentes"},
                        {"id": "agentsTree", "value": "Árbol de Agentes"},
                        {"id": "agentsInform", "value": "Informe de Agentes"},
                        {"id": "agentTransfers", "value": "Transferencias"},
                        {"id": "agent.requestsAgent", "value": "Solicitudes"},
                        {"id": "agent.requirementsAgent", "value": "Requisitos"}

                    ]
                },
                {
                    "id": "financial", "icon": "icon-financial", "value": "Financiero", "data": [
                        {"id": "depositRequests", "value": "Solicitudes de Deposito"},
                        {"id": "withdrawalRequests", "value": "Solicitudes de Retiro","Edit":"true"}
                    ]
                }            ]'
    );
    /*{
                        "id": "leagues", "icon": "icon-pie-chart", "value": "Ligas", "data": [
                            {"id": "leagues.leaguesList", "value": "Lista de Ligas"}
                                                ]
                    },*/

    return ($menu_string2);

}


