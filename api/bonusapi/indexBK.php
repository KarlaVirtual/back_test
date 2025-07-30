<?php
/**
 * Index de la api 'bonusapi'
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */

use Backend\cms\CMSCategoria;
use Backend\dto\BonoDetalle;
use Backend\dto\CategoriaProducto;
use Backend\dto\CodigoPromocional;
use Backend\dto\CuentaCobro;
use Backend\dto\ItTicketEnc;
use Backend\dto\ItTicketEncInfo1;
use Backend\dto\Mandante;
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
use Backend\dto\TorneoDetalle;
use Backend\dto\TorneoInterno;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransjuegoInfo;
use Backend\dto\Usuario;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioToken;
use Backend\dto\BonoInterno;
use Backend\dto\UsuarioTorneo;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\PerfilSubmenuMySqlDAO;
use Backend\mysql\PromocionalLogMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\TorneoDetalleMySqlDAO;
use Backend\mysql\TorneoInternoMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\ProductoMySqlDAO;
use Backend\mysql\ProductoMandanteMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\websocket\WebsocketUsuario;

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


$domainSession = ".virtualsoft.tech";

if (strpos($_SERVER['HTTP_REFERER'], "netabet.com.mx") !== FALSE) {
    $domainSession = ".netabet.com.mx";
}

if ($domainSession != "") {
    session_name('SessionName');
    session_set_cookie_params(['SameSite' => 'None', 'Secure' => true]);
    session_set_cookie_params(
        1800,
        ini_get('session.cookie_path'),
        $domainSession
    );
}
$headers = getallheaders();


$Cookiee = $headers['Cookie'];
$Cookiee = explode( ";",$Cookiee);

$SessionName = "";


foreach ($Cookiee as $item) {

    if (trim(explode( "=",$item)[0]) == 'SessionName') {

        $SessionName = trim(explode( "=",$item)[1]);

    }
}

if ($SessionName != "" && $SessionName != null && $SessionName != 'null') {
    session_id($SessionName);
}


include "require.php";
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Access-Control-Allow-Headers, Access-Control-Allow-Credentials,Authentication, Origin, X-Requested-With, Content-Type,x-token');
header('Access-Control-Expose-Headers: Authentication');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS,PUT');
header('Content-Type: application/json');
ini_set('memory_limit', '-1');
header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);


$URI = $_SERVER["REQUEST_URI"];
$params = file_get_contents('php://input');
$params = json_decode($params);
$response = array();


$headers = getallheaders();

$URL_ITAINMENT2 = 'https://dataexport-altenar.biahosted.com';
//$URL_ITAINMENT = 'https://dataexport-altenar.biahosted.com';
$URL_ITAINMENT = 'https://dataexport-uof-altenar.biahosted.com';
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit();
}

switch (end(explode("/", current(explode("?", $URI))))) {

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
        $usuario = $params->Username;
        $clave = $params->Password;

        if ($clave == "" || $usuario == "") {
            $usuario = $params->username;
            $clave = $params->password;

            if ($clave == "" || $usuario == "") {

                $response["HasError"] = true;
                $response["AlertType"] = "danger";
                $response["AlertMessage"] = "Error, No hay credenciales.";
                $response["ModelErrors"] = [];

            } else {

                $Usuario = new Usuario();

                try {

                    $responseU = $Usuario->login($usuario, $clave);

                    /*
                    $UsuarioToken = new UsuarioToken("", $responseU->user_id);

                    $UsuarioToken->setRequestId($json->session->sid);
                    $UsuarioToken->setCookie(encrypt($responseU->user_id . "#" . time()));

                    $UsuarioTokenMySqlDAO = new \Backend\mysql\UsuarioTokenMySqlDAO();
                    $UsuarioTokenMySqlDAO->update($UsuarioToken);
                    $UsuarioTokenMySqlDAO->getTransaction()->commit();
                     */
                    $response["HasError"] = false;
                    $response["AlertType"] = "success";
                    $response["AlertMessage"] = "";
                    $response["ModelErrors"] = [];

                    /*
                    "ViewClients","ViewMenuDashBoard","ManageClients","ViewPaymentReport","ViewDepositWithdrawalReport","ViewBalance","ViewSalesReport","ViewClientTurnoverReport","ViewMenuReport",
                     */

                    $response["Data"] = array(
                        "AuthenticationStatus" => 0,
                        "AuthToken" => $responseU->auth_token,
                        "PermissionList" => array(
                            "ViewMenuSecurity", "ViewMenuTeacher", "AdminUser", "Contingencia", "Menu", "Perfil", "PerfilOpciones", "Submenu", "UsuarioPerfil", "Clasificador", "Concesionario", "ViewAddHocReport", "ViewMenuManagement", "ActivarRegistros", "AjustarSaldo", "AsignacionCupo", "Bonos", "CuentaCobroEliminar", "GestionRed", "RegistroRapido", "ChequeReimpresion", "RecargaReversion", "GestionContacto", "ViewMenuCash", "FlujoCaja", "PagoPremio", "PagoNotaRetiro", "RecargarCredito", "ViewMenuQueries", "FlujoCajaHistorico", "FlujoCajaResumido", "InformeCasino", "InformeGerencial", "ListadoRecargasRetiros", "PremiosPendientesPagar", "ConsultaOnlineDetalle", "ConsultaOnlineResumen",

                        ),
                    );

                } catch (Exception $e) {

                    $response["HasError"] = true;
                    $response["AlertType"] = "danger";
                    $response["AlertMessage"] = "Invalid Username and/or password ('.$e->getCode().')";
                    $response["ModelErrors"] = [];

                }

            }
        }

        break;

    /**
     * CheckToken
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
    case 'CheckToken':

        $UsuarioToken = new UsuarioToken($headers['authentication'], '0');

        $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId(), "");


        $data["UserName"] = $UsuarioMandante->usumandanteId;
        $data["UserId"] = $UsuarioMandante->usumandanteId;
        $data["FirstName"] = $UsuarioMandante->nombres;
        $data["Settings"] = array(
            "Language" => "en",
            "ReportCurrency" => "USD"
        );
        $data["PermissionList"] = array("BEManageCasinoBonus", "BEManageSportBonus", "BEViewCasinoBonus", "BEViewSportBonus", "EditBonus", "ManageBonus", "ManageClientBonuses", "ViewBonus");

        $response["HasError"] = false;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = '';
        $response["ModelErrors"] = [];
        $response["Result"] = $data;


        /*        print_r('{"Result":{"UserName":"danielftg@hotmail.com","LangId":"en","AuthenticationStatus":0,"UserId":56482,"Settings":{"Language":"en","TimeZone":null,"OddsType":null,"ReportCurrency":"EUR","ReportPartner":null,"IsSubscribedToNotification":null,"ReportsColumns":null},"PartnerId":161640,"PartnerName":"betbetbet","CurrencyId":"EUR","ServerTime":"2018-01-28T15:43:55.6898378","FirstName":"daniel","PermissionList":["AllBetshops","AllDocuments","AllMatches","AllSports","AssignAgentCredit","BEManageCasinoBonus","BEManageSportBonus","BEViewCasinoBonus","BEViewSportBonus","CDCashoutBet","CDCopyBet","CDEditBet","CDManageCashRegister","CDManageIncome","CDManageMovementDocuments","CDManageOutcome","CDPayWonBet","CDPrintBet","CDViewBetlist","CDViewBetshopSettings","CDViewBetSlip","CDViewBooklet","CDViewChat","CDViewClients","CDViewDeposit","CDViewHome","CDViewMatches","CDViewMovement","CDViewPaymentDocuments","CDViewPayments","CDViewSport","CDViewWithdraw","Create Campaign","CreateAddHocReport","CreateCampaign","CreateGroup","CreateOCampaign","CreateSegment","CreateTemplate","Delete AdHocReportResult","DeleteAddHocReport","DeleteBonus","DeleteCampaign","DeleteOCampaign","DeleteSegment","DeleteTemplate","EditAddHocReport","EditAdHocReportResult","EditBonus","EditCampaign","EditOCampaign","EditSegment","EditTemplate","Execute Segment","ExecuteAddHocReport","ExecuteCampaign","ExecuteOCampaign","ExecuteSegment","ExecuteTemplate","LiveBets","ManageAccount","ManageAddHocReport","ManageAgent","ManageAgentBetLimitGroups","ManageAgentCommissionGroups","ManageAgentGroups","ManageAgentMembers","ManageAgentPtGroups","ManageAgentRelationship","ViewGamesManagment","ViewCasinoProviders","ViewCasinoCategories","ViewCasinoGames","ManageAgentSubAccounts","ManageAgentSystem","ManageAgentTransfers","ManageBetShops","ManageBetShopsGroups","ManageBetShopUsers","ManageBonus","ManageCampaign","ManageCashDesks","ManageChat","ManageClientBalance","ManageClientBonuses","ManageClientCredit","ManageClientMessage","ManageClients","ManageClientTurnoverAndBonusReport","ManageCompetitions","ManageEmailTemplates","ManageLoyaltyGameGroups","ManageLoyaltyGameMultiplayers","ManageLoyaltyLevels","ManageLoyaltyRates","ManageMargins","ManageMessages","ManageOCampaign","ManagePartnersBooking","ManagePlayers","ManagePopulars","ManageReports","ManageSegment","ManageSettings","ManageSportLimits","ManageSuperBets","ManageTemplate","ManualSettlement","PreMatchBets","PrematchManageMatches","SGChatView","SGGamesView","SGplayeravatarmanage","SGplayeravatarview","SGPlayerblockingsview","SGplayerremarkcreate","SGplayerremarkview","SGPlayersView","SGStatisticsRake","SGStatisticsTournament","SGTablesView","SGTournamentsView","ViewAccount","ViewAccountTurnover","ViewAddHocReport","ViewAdHocReportResult","ViewAffiliate","ViewAgent","ViewAgentBetLimitGroups","ViewAgentBettingReport","ViewAgentCommissionGroups","ViewAgentGroups","ViewAgentMarketReport","ViewAgentMembers","ViewAgentMenu","ViewAgentPtGroups","ViewAgentSubAccounts","ViewAgentSummaryBalanceStatement","ViewAgentSystem","ViewAgentTransfers","ViewBalance","ViewBalanceGroups","ViewBalanceKinds","ViewBalanceReport","ViewBetReport","ViewBetShopBets","ViewBetShops","ViewBetShopsGroups","ViewBetShopUsers","ViewBonus","ViewCampaign","ViewCashDeskReport","ViewCashDesks","ViewCasino","ViewClientAccounts","ViewClientBalance","ViewClientBonuses","ViewClientCasinoBets","ViewClientDocuments","ViewClientLogHistory","ViewClientLogins","ViewClientMessage","ViewClientProfiles","ViewClients","ViewClientSportBets","ViewClientTurnoverAndBonusReport","ViewClientTurnoverReport","ViewCMS","ViewCompetitionReport","ViewCompetitions","ViewCountries","ViewCRM","ViewCurrencies","ViewDailySalesReport","ViewDashBoardActivePlayers","ViewDashBoardBottomFiveMatches","ViewDashBoardBottomFiveSelections","ViewDashBoardCasinoBets","ViewDashBoardNewRegistrations","ViewDashBoardSportBets","ViewDashBoardTopFiveGames","ViewDashBoardTopFiveMatches","ViewDashBoardTopFiveMatchesByStake","ViewDashBoardTopFivePlayers","ViewDashBoardTopFiveSelections","ViewDashBoardTopFiveSportsbookPlayers","ViewDocuments","ViewDocumentTypes","ViewEmailTemplates","ViewFreeBet","ViewGames","ViewGroup","ViewHeadCashDesks","ViewHelpDesk","ViewInfo","viewInvoice","ViewLanguages","ViewLoyaltyGameGroups","ViewLoyaltyGameMultiplayers","ViewLoyaltyLevels","ViewLoyaltyRates","ViewMargins","ViewMarketReport","ViewMarkets","ViewMarketTypeReport","ViewMatch","ViewMatchPackages","ViewMatchTurnoverReport","ViewMenuDashBoard","ViewMenuReport","ViewMessages","ViewOCampaign","ViewOtherSportMarkets","ViewPartnerMatchPackages","ViewPartnersBooking","ViewPaymentReport","ViewPaymentTypes","ViewPaymentTypesReport","ViewPermissions","ViewPlayers","ViewPopulars","ViewRegions","ViewReports","ViewSalesReport","ViewSegment","ViewSettings","ViewSportBonus","ViewSportLimits","ViewSportReport","ViewSports","ViewSportTurnoverReport","ViewStream","ViewTemplate","ViewUser","ViewUsers","ViewVerificationStep"],"AgentId":null,"PartnerBalanceChangeTime":"00:00:00","IsQRCodeSent":false,"QRCode":null,"PartnerLimitType":3,"PartnerLimit":1000.0},"HasError":false,"ErrorDescription":null,"ErrorId":0}');*/

        break;

    /**
     * GetProductTypes
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
    case "GetProductTypes":

        $response["HasError"] = false;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = "Success";
        $response["ModelErrors"] = [];
        $response["Result"] = array(
            array(
                "Id" => 0,
                "Name" => "Directo"),
            array(
                "Id" => 1,
                "Name" => "Casino"),
            array(
                "Id" => 2,
                "Name" => "SportsBook")
        );

        break;

    /**
     * SaldosTypes
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
    case "SaldosTypes":

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "Success";
        $response["ModelErrors"] = [];
        $response["Result"] = array(
            array(
                "Id" => 0,
                "Name" => "Saldo Creditos"),

            array(
                "Id" => 1,
                "Name" => "Saldo Premios")
        );

        break;

    /**
     * GetBonusTriggers
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
    case "GetBonusTriggers":

        $BonusTypeId = $params->BonusTypeId;

        $response["HasError"] = false;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = "Success";
        $response["ModelErrors"] = [];
        $response["Result"] = array(
            array(
                "Id" => 1,
                "Name" => "Deposit"),
            array(
                "Id" => 4,
                "Name" => "Withdraw"),
            array(
                "Id" => 5,
                "Name" => "Registration"),
            array(
                "Id" => 6,
                "Name" => "Login"),
            array(
                "Id" => 7,
                "Name" => "Activation"),
            array(
                "Id" => 8,
                "Name" => "RegistrationByPromoCode"),
            array(
                "Id" => 9,
                "Name" => "Verification")
        );

        if ($BonusTypeId != "") {

        }

        break;

    /**
     * GetPeriodTypes
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
    case "GetPeriodTypes":

        $response["HasError"] = false;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = "Success";
        $response["ModelErrors"] = [];
        $response["Result"] = array(
            array(
                "Id" => 1,
                "Name" => "None"),
            array(
                "Id" => 2,
                "Name" => "Day"),
            array(
                "Id" => 3,
                "Name" => "Week"),
            array(
                "Id" => 4,
                "Name" => "Month"),
            array(
                "Id" => 5,
                "Name" => "Year")
        );

        break;

    /**
     * GetFilteredCategories
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
    case "GetFilteredCategories":

        $response["HasError"] = false;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = "Success";
        $response["ModelErrors"] = [];
        $response["Result"] = array(
            array(
                "Id" => 1,
                "Name" => "None"),
            array(
                "Id" => 2,
                "Name" => "Day"),
            array(
                "Id" => 3,
                "Name" => "Week"),
            array(
                "Id" => 4,
                "Name" => "Month"),
            array(
                "Id" => 5,
                "Name" => "Year")
        );
        break;

    /**
     * GetBonusDefinitions
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
    case "GetBonusDefinitions":


        $params = file_get_contents('php://input');
        $params = json_decode($params);

        $ToDateLocal = $params->StartTimeLocal;
        $FromDateLocal = $params->EndTimeLocal;
        $TypeId = $params->TypeId;

        $FromDateLocal = $params->EndTimeLocal;
        $FromDateLocal = $params->EndTimeLocal;

        $MaxRows = $params->Limit;
        $OrderedItem = $params->OrderedItem;
        $SkeepRows = ($params->Offset) * $MaxRows;

        $StateType = $params->StateType;

        $rules = [];

        if ($StateType == 1) {

        } else {

        }

        /*if ($TypeId != "") {
            array_push($rules, array("field" => "producto.producto_id", "data" => "$TypeId", "op" => "eq"));
        }*/

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

        $BonoInterno = new BonoInterno();
        $BonoDetalle = new BonoDetalle();

        //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
        //$bonos = json_decode($bonos);


        $rules = [];

        array_push($rules, array("field" => "bono_detalle.tipo", "data" => "TIPOPRODUCTO", "op" => "eq"));
        array_push($rules, array("field" => "bono_interno.estado", "data" => "A", "op" => "eq"));

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

        $bonodetalles = $BonoDetalle->getBonoDetallesCustom(" bono_detalle.*,bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, TRUE);

        $bonodetalles = json_decode($bonodetalles);

        $final = [];


        foreach ($bonodetalles->data as $key => $value) {

            $array = [];

            $array["Id"] = $value->{"bono_interno.bono_id"};
            $array["Name"] = $value->{"bono_interno.nombre"};
            $array["Description"] = $value->{"bono_interno.descripcion"};
            $array["BeginDate"] = $value->{"bono_interno.fecha_inicio"};
            $array["EndDate"] = $value->{"bono_interno.fecha_fin"};
            $array["ProductTypeId"] = $value->{"bono_detalle.valor"};
            $array["TypeId"] = $value->{"bono_interno.tipo"};

            switch ($value->{"bono_interno.tipo"}) {
                case "2":
                    $array["Type"] = array(
                        "Id" => $value->{"bono_interno.tipo"},
                        "Name" => "Bono Deposito",
                        "TypeId" => $value->{"bono_interno.tipo"}
                    );

                    break;

                case "3":
                    $array["Type"] = array(
                        "Id" => $value->{"bono_interno.tipo"},
                        "Name" => "Bono No Deposito",
                        "TypeId" => $value->{"bono_interno.tipo"}
                    );

                    break;

                case "4":
                    $array["Type"] = array(
                        "Id" => $value->{"bono_interno.tipo"},
                        "Name" => "Bono Cash",
                        "TypeId" => $value->{"bono_interno.tipo"}
                    );

                    break;


            }


            array_push($final, $array);
        }

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];
        $response["Count"] = $bonodetalles->count[0]->{".count"};

        $response["Result"] = $final;


        break;

    case "GetCodesRegister":


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

        array_push($rules, array("field" => "codigo_promocional.funcion", "data" => "1", "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $CodigoPromocional = new CodigoPromocional();

        $data = $CodigoPromocional->getCodigoPromocionalsCustom("  codigo_promocional.* ", "codigo_promocional.codpromocional_id", "asc", $SkeepRows, $MaxRows, $json, true);

        $data = json_decode($data);
        $final = [];

        foreach ($data->data as $key => $value) {

            $array = [];


            $array["Id"] = $value->{"codigo_promocional.codpromocional_id"};
            $array["Code"] = $value->{"codigo_promocional.codigo"};
            $array["Name"] = $value->{"codigo_promocional.descripcion"};
            $array["CreatedLocalDate"] = $value->{"codigo_promocional.fecha_crea"};
            $array["State"] = $value->{"codigo_promocional.estado"};
            $array["UserId"] = $value->{"codigo_promocional.usuario_id"};
            $array["Function"] = $value->{"codigo_promocional.funcion"};

            array_push($final, $array);


        }

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Data"] = $final;
        $response["Result"] = $final;

        break;

    case "ChangeStateBonus":

        $Id = $params->Id;
        $State = $params->State;


        $error = false;
        if ($Id != '' && ($State == 'A' || $State == 'I')) {

            $BonoInterno = new BonoInterno($Id);

            if ($BonoInterno->estado != $State) {
                $BonoInterno->estado = $State;

                $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
                $BonoInternoMySqlDAO->update($BonoInterno);
                $BonoInternoMySqlDAO->getTransaction()->commit();

                $response["HasError"] = false;
                $response["AlertType"] = "success";
                $response["AlertMessage"] = "";
                $response["ModelErrors"] = [];

            } else {
                $error = true;

            }


        } else {
            $error = true;

        }
        if ($error) {
            $response["HasError"] = true;
            $response["AlertType"] = "error";
            $response["AlertMessage"] = "";
            $response["ModelErrors"] = [];

        }


        break;

    case "ChangeStateTournament":
        $Id = $params->Id;
        $State = $params->State;


        $error = false;
        if ($Id != '' && ($State == 'A' || $State == 'I')) {

            $TorneoInterno = new TorneoInterno($Id);

            if ($TorneoInterno->estado != $State) {
                $TorneoInterno->estado = $State;


                $TorneoInternoMySqlDAO = new TorneoInternoMySqlDAO();
                $TorneoInternoMySqlDAO->update($TorneoInterno);
                $TorneoInternoMySqlDAO->getTransaction()->commit();

                $response["HasError"] = false;
                $response["AlertType"] = "success";
                $response["AlertMessage"] = "";
                $response["ModelErrors"] = [];


            } else {
                $error = true;

            }


        } else {
            $error = true;

        }
        if ($error) {
            $response["HasError"] = true;
            $response["AlertType"] = "error";
            $response["AlertMessage"] = "";
            $response["ModelErrors"] = [];

        }


        break;

    /**
     * GetBonus
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
    case "GetBonus":


        $ToDateLocal = $params->StartTimeLocal;
        $FromDateLocal = $params->EndTimeLocal;
        $TypeId = $params->TypeId;

        $FromDateLocal = $params->EndTimeLocal;
        $FromDateLocal = $params->EndTimeLocal;

        $MaxRows = $params->Limit;
        $OrderedItem = $params->OrderedItem;
        $SkeepRows = ($params->Offset) * $MaxRows;

        $StateType = $params->StateType;

        $State = $params->State;


        $rules = [];

        if ($StateType == 1) {

        } else {

        }

        /*if ($TypeId != "") {
            array_push($rules, array("field" => "producto.producto_id", "data" => "$TypeId", "op" => "eq"));
        }*/

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

        $draw = $params->draw;
        $length = $params->length;
        $start = $params->start;

        if ($start != "") {
            $SkeepRows = $start;

        }

        if ($length != "") {
            $MaxRows = $length;

        }

        $json = json_encode($filtro);

        $BonoInterno = new BonoInterno();
        $BonoDetalle = new BonoDetalle();

        //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
        //$bonos = json_decode($bonos);


        $rules = [];

        if ($State == "A" || $State == "I") {

            array_push($rules, array("field" => "bono_interno.estado", "data" => "$State", "op" => "eq"));

        }

        array_push($rules, array("field" => "bono_detalle.tipo", "data" => "TIPOPRODUCTO", "op" => "eq"));


        // Si el usuario esta condicionado por País
        if ($_SESSION['PaisCond'] == "S") {
        }
        // Si el usuario esta condicionado por el mandante y no es de Global
        if ($_SESSION['Global'] == "N") {
            array_push($rules, array("field" => "bono_interno.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
        }

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

        $bonodetalles = $BonoDetalle->getBonoDetallesCustom(" bono_detalle.*,bono_interno.* ", "bono_interno.bono_id", "desc", $SkeepRows, $MaxRows, $json, TRUE);

        $bonodetalles = json_decode($bonodetalles);


        $final = [];


        foreach ($bonodetalles->data as $key => $value) {

            $array = [];

            $array["Id"] = $value->{"bono_interno.bono_id"};
            $array["Name"] = $value->{"bono_interno.nombre"};
            $array["Description"] = $value->{"bono_interno.descripcion"};
            $array["BeginDate"] = $value->{"bono_interno.fecha_inicio"};
            $array["EndDate"] = $value->{"bono_interno.fecha_fin"};
            $array["ProductTypeId"] = $value->{"bono_detalle.valor"};
            $array["TypeId"] = $value->{"bono_interno.tipo"};

            $array["State"] = $value->{"bono_interno.estado"};

            switch ($value->{"bono_interno.tipo"}) {
                case "2":
                    $array["Type"] = array(
                        "Id" => $value->{"bono_interno.tipo"},
                        "Name" => "Bono Deposito",
                        "TypeId" => $value->{"bono_interno.tipo"}
                    );

                    break;

                case "3":
                    $array["Type"] = array(
                        "Id" => $value->{"bono_interno.tipo"},
                        "Name" => "Bono No Deposito",
                        "TypeId" => $value->{"bono_interno.tipo"}
                    );

                    break;

                case "4":
                    $array["Type"] = array(
                        "Id" => $value->{"bono_interno.tipo"},
                        "Name" => "Bono Cash",
                        "TypeId" => $value->{"bono_interno.tipo"}
                    );

                    break;


                case "6":
                    $array["Type"] = array(
                        "Id" => $value->{"bono_interno.tipo"},
                        "Name" => "Freebet",
                        "TypeId" => $value->{"bono_interno.tipo"}
                    );

                    break;


            }


            array_push($final, $array);
        }

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];
        $response["Count"] = $bonodetalles->count[0]->{".count"};

        $response["Data"] = $final;


        break;

    /**
     * GetTournaments
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
    case "GetTournaments":


        $params = file_get_contents('php://input');
        $params = json_decode($params);

        $ToDateLocal = $params->StartTimeLocal;
        $FromDateLocal = $params->EndTimeLocal;
        $TypeId = $params->TypeId;

        $FromDateLocal = $params->EndTimeLocal;
        $FromDateLocal = $params->EndTimeLocal;

        $MaxRows = $params->Limit;
        $OrderedItem = $params->OrderedItem;
        $SkeepRows = ($params->Offset) * $MaxRows;

        $StateType = $params->StateType;
        $State = $params->State;

        $rules = [];


        // Si el usuario esta condicionado por País
        if ($_SESSION['PaisCond'] == "S") {
        }
        // Si el usuario esta condicionado por el mandante y no es de Global
        if ($_SESSION['Global'] == "N") {
            array_push($rules, array("field" => "torneo_interno.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
        }

        if ($StateType == 1) {

        } else {

        }


        /*if ($TypeId != "") {
            array_push($rules, array("field" => "producto.producto_id", "data" => "$TypeId", "op" => "eq"));
        }*/

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

        $draw = $params->draw;
        $length = $params->length;
        $start = $params->start;

        if ($start != "") {
            $SkeepRows = $start;

        }

        if ($length != "") {
            $MaxRows = $length;

        }

        $json = json_encode($filtro);

        $TorneoInterno = new TorneoInterno();
        $TorneoDetalle = new TorneoDetalle();

        //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
        //$bonos = json_decode($bonos);


        $rules = [];

        if ($State == "A" || $State == "I") {

            array_push($rules, array("field" => "torneo_interno.estado", "data" => "$State", "op" => "eq"));

        }

        array_push($rules, array("field" => "torneo_detalle.tipo", "data" => "TIPOPRODUCTO", "op" => "eq"));
        array_push($rules, array("field" => "torneo_detalle.tipo", "data" => "", "op" => "nn"));
        //array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));


        // Si el usuario esta condicionado por País
        if ($_SESSION['PaisCond'] == "S") {
        }
        // Si el usuario esta condicionado por el mandante y no es de Global
        if ($_SESSION['Global'] == "N") {
            array_push($rules, array("field" => "torneo_interno.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
        }


        // Si el usuario esta condicionado por País
        if ($_SESSION['PaisCond'] == "S") {
        }
        // Si el usuario esta condicionado por el mandante y no es de Global
        if ($_SESSION['Global'] == "N") {
            array_push($rules, array("field" => "torneo_interno.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
        }


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

        $torneodetalles = $TorneoDetalle->getTorneoDetallesCustom(" torneo_detalle.*,torneo_interno.* ", "torneo_interno.torneo_id", "asc", $SkeepRows, $MaxRows, $json, TRUE, "torneo_interno.torneo_id");

        $torneodetalles = json_decode($torneodetalles);

        $final = [];


        foreach ($torneodetalles->data as $key => $value) {

            $array = [];

            $array["Id"] = $value->{"torneo_interno.torneo_id"};
            $array["Name"] = $value->{"torneo_interno.nombre"};
            $array["Description"] = $value->{"torneo_interno.descripcion"};
            $array["BeginDate"] = $value->{"torneo_interno.fecha_inicio"};
            $array["EndDate"] = $value->{"torneo_interno.fecha_fin"};
            $array["ProductTypeId"] = $value->{"torneo_detalle.valor"};
            $array["TypeId"] = $value->{"torneo_interno.tipo"};

            $array["State"] = $value->{"torneo_interno.estado"};

            switch ($value->{"torneo_interno.tipo"}) {
                case "2":
                    $array["Type"] = array(
                        "Id" => $value->{"torneo_interno.tipo"},
                        "Name" => "Bono Deposito",
                        "TypeId" => $value->{"torneo_interno.tipo"}
                    );

                    break;

                case "3":
                    $array["Type"] = array(
                        "Id" => $value->{"torneo_interno.tipo"},
                        "Name" => "Bono No Deposito",
                        "TypeId" => $value->{"torneo_interno.tipo"}
                    );

                    break;

                case "4":
                    $array["Type"] = array(
                        "Id" => $value->{"torneo_interno.tipo"},
                        "Name" => "Bono Cash",
                        "TypeId" => $value->{"torneo_interno.tipo"}
                    );

                    break;


                case "6":
                    $array["Type"] = array(
                        "Id" => $value->{"torneo_interno.tipo"},
                        "Name" => "Freebet",
                        "TypeId" => $value->{"torneo_interno.tipo"}
                    );

                    break;


            }


            array_push($final, $array);
        }

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];
        $response["Count"] = $torneodetalles->count[0]->{".count"};
        $response["CountFiltered"] = $torneodetalles->count[0]->{".count"};

        $response["Data"] = $final;


        break;

    /**
     * GetBonusFullAcceptance
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
    case "GetBonusFullAcceptance":

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Result"] = array();

        break;

    /**
     * GetFreeBetBonusesByFilter
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
    case "GetFreeBetBonusesByFilter":


        $params = file_get_contents('php://input');
        $params = json_decode($params);

        $ToDateLocal = $params->StartTimeLocal;
        $FromDateLocal = $params->EndTimeLocal;
        $BonusType = $params->BonusType;


        $MaxRows = $params->MaxRows;
        $OrderedItem = $params->OrderedItem;
        $SkeepRows = $params->SkeepRows;


        $rules = [];

        if ($BonusType != "") {
            array_push($rules, array("field" => "bono_interno.tipo", "data" => "$BonusType", "op" => "eq"));
        }

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

        $BonoInterno = new BonoInterno();

        $bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, true);

        $bonos = json_decode($bonos);

        $final = [];

        foreach ($bonos->data as $key => $value) {

            $array = [];

            $array["Id"] = $value->{"bono_interno.bono_id"};
            $array["Name"] = $value->{"bono_interno.nombre"};
            $array["Description"] = $value->{"bono_interno.descripcion"};
            $array["BeginDate"] = $value->{"bono_interno.fecha_inicio"};
            $array["EndDate"] = $value->{"bono_interno.fecha_fin"};
            $array["ProductTypeId"] = 2;
            $array["TypeId"] = 6;
            $array["Type"] = array(
                "Id" => 2,
                "Name" => "Primer Deposito",
                "TypeId" => 2
            );
            $array["entity"] = array(
                "Id" => 2,
                "Name" => "Primer Deposito",
                "TypeId" => 2
            );

            array_push($final, $array);
        }

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Result"] = $final;


        break;

    /**
     * GetBonusDefinitionDetails
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
    case "GetBonusDefinitionDetails":

        $params = file_get_contents('php://input');
        $params = json_decode($params);

        $ToDateLocal = $params->StartTimeLocal;
        $FromDateLocal = $params->EndTimeLocal;
        $BonusId = $_REQUEST["Id"];

        $FromDateLocal = $params->EndTimeLocal;
        $FromDateLocal = $params->EndTimeLocal;

        $MaxRows = $params->MaxRows;
        $OrderedItem = $params->OrderedItem;
        $SkeepRows = $params->SkeepRows;

        $rules = [];


        if ($BonusId != "") {
            array_push($rules, array("field" => "bono_interno.bono_id", "data" => "$BonusId", "op" => "eq"));
        }


        // Si el usuario esta condicionado por País
        if ($_SESSION['PaisCond'] == "S") {
        }
        // Si el usuario esta condicionado por el mandante y no es de Global
        if ($_SESSION['Global'] == "N") {
            array_push($rules, array("field" => "bono_interno.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
        }


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

        $BonoInterno = new BonoInterno();

        $bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, true);

        $bonos = json_decode($bonos);

        $final = [];

        foreach ($bonos->data as $key => $value) {

            $rules = [];


            if ($BonusId != "") {
                array_push($rules, array("field" => "bono_interno.bono_id", "data" => $BonusId, "op" => "eq"));
            }

            /* if($PlayerExternalId != ""){
                 array_push($rules, array("field" => "promocional_log.usuario_id", "data" => $PlayerExternalId , "op" => "eq"));
             }*/

            $filtro = array("rules" => $rules, "groupOp" => "AND");

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 30;
            }

            $json = json_encode($filtro);

            // $PromocionalLog = new PromocionalLog();

            // $bonolog = $PromocionalLog->getPromocionalLogsCustom(" count(promocional_log.promolog_id) count, sum(promocional_log.valor) valor ", "promocional_log.promolog_id", "asc", $SkeepRows, $MaxRows, $json, true);

            // $bonolog = json_decode($bonolog);

            $rules = [];


            if ($BonusId != "") {
                array_push($rules, array("field" => "bono_detalle.bono_id", "data" => "$BonusId", "op" => "eq"));
            }

            $filtro = array("rules" => $rules, "groupOp" => "AND");

            $json = json_encode($filtro);

            $BonoDetalle = new BonoDetalle();

            $bonodetalles = $BonoDetalle->getBonoDetallesCustom(" bono_detalle.* ", "bono_detalle.bonodetalle_id", "asc", $SkeepRows, $MaxRows, $json, true);


            $bonodetalles = json_decode($bonodetalles);

            $array = [];

            $array["Id"] = $value->{"bono_interno.bono_id"};
            $array["Name"] = $value->{"bono_interno.nombre"};
            $array["Description"] = $value->{"bono_interno.descripcion"};
            $array["BeginDate"] = str_replace(" ", "T", $value->{"bono_interno.fecha_inicio"});
            $array["BeginDate"] = str_replace(" ", " ", $value->{"bono_interno.fecha_inicio"});

            $array["EndDate"] = str_replace(" ", "T", $value->{"bono_interno.fecha_fin"});
            $array["EndDate"] = str_replace(" ", " ", $value->{"bono_interno.fecha_fin"});
            $array["Priority"] = $value->{"bono_interno.orden"};
            $array["CodeGlobal"] = $value->{"bono_interno.codigo"};
            $array["WinBonusId"] = 0;
            $array["TypeBonusDeposit"] = 0;
            $array["TypeMaxRollover"] = 0;
            $array["TypeSaldo"] = 0;
            $array["UserRepeatBonus"] = false;

            $array["TypeId"] = 2;
            $array["Type"] = array(
                "Id" => 2,
                "Name" => "Primer Deposito",
                "TypeId" => 2
            );
            $array["PartnerBonus"] = array(
                "StartDate" => "2018-02-09T00:00:00",
                "EndDate" => "2018-02-09T00:00:00",
                "ExpirationDays" => 6,
                "BonusDetails" => array(
                    array(
                        "CurrencyId" => "USD"

                    )
                )
            );

            $array["MaximumDeposit"] = array();
            $array["MinimumDeposit"] = array();
            $array["MaxPayout"] = array();
            $array["MoneyRequirement"] = array();
            $array["MaxPayout"] = array();

            $array["TriggerDetails"] = array();
            $array["TriggerDetails"]["PaymentSystemIds"] = array();
            $array["TriggerDetails"]["Regions"] = array();
            $array["TriggerDetails"]["ConditionProduct"] = $value->{"bono_interno.condicional"};
            $array["DepositDefinition"] = array();
            $array["GamesByCategories"] = array();

            $array["ForeignRule"] = array();
            $array["ForeignRule"]["Info"] = array();
            $array["ForeignRule"]["Info"]["SportBonusRules"] = array();
            $array["ForeignRule"]["Info"]["LiveOrPreMatch"] = 0;
            // $array["CurrentCost"] = $bonolog->data[0]->{".valor"};
            // $array["PlayersCount"] = $bonolog->data[0]->{".count"};


            $array["IsVisibleForAllplayers"] = true;

            $sports = array();
            $matches = array();
            $competitions = array();
            $regions = array();
            $markets = array();

            foreach ($bonodetalles->data as $bonodetalle) {
                //Expiracion
                switch ($bonodetalle->{'bono_detalle.tipo'}) {
                    case "EXPDIA":
                        $array["ExpirationDays"] = intval($bonodetalle->{'bono_detalle.valor'});
                        $array["TypeDateExpiration"] = 1;

                        break;

                    case "TIPOPRODUCTO":
                        $array["ProductTypeId"] = intval($bonodetalle->{'bono_detalle.valor'});
                        break;

                    case "CANTDEPOSITOS":
                        $array["TriggerDetails"]["Count"] = intval($bonodetalle->{'bono_detalle.valor'});
                        break;

                    case "PAISESPERMITIDOS":
                        $array["TriggerDetails"]["AreAllowed"] = ($bonodetalle->{'bono_detalle.valor'});
                        break;

                    case "CONDEFECTIVO":
                        $array["TriggerDetails"]["IsFromCashDesk"] = boolval($bonodetalle->{'bono_detalle.valor'});
                        break;

                    case "EXPFECHA":
                        $array["ExpirationDate"] = ($bonodetalle->{'bono_detalle.valor'});
                        $array["TypeDateExpiration"] = 0;
                        break;

                    case "PORCENTAJE":
                        $array["DepositDefinition"]["BonusPercent"] = intval($bonodetalle->{'bono_detalle.valor'});
                        $array["TypeBonusDeposit"] = 1;
                        break;

                    case "WFACTORBONO":
                        $array["DepositDefinition"]["BonusWFactor"] = intval($bonodetalle->{'bono_detalle.valor'});
                        break;

                    case "WFACTORDEPOSITO":
                        $array["DepositDefinition"]["DepositWFactor"] = intval($bonodetalle->{'bono_detalle.valor'});
                        break;

                    case "NUMERODEPOSITO":
                        $array["DepositDefinition"]["DepositNumber"] = intval($bonodetalle->{'bono_detalle.valor'});
                        break;

                    case "MAXJUGADORES":
                        $array["MaxplayersCount"] = intval($bonodetalle->{'bono_detalle.valor'});
                        $array["IsVisibleForAllplayers"] = false;
                        break;


                    case "MAXPAGO":
                        array_push($array["MaxPayout"],

                            array(
                                "CurrencyId" => $bonodetalle->{'bono_detalle.moneda'},
                                "Amount" => intval($bonodetalle->{'bono_detalle.valor'})
                            )

                        );

                        break;

                    case "MAXDEPOSITO":

                        array_push($array["MaximumDeposit"],

                            array(
                                "CurrencyId" => $bonodetalle->{'bono_detalle.moneda'},
                                "Amount" => intval($bonodetalle->{'bono_detalle.valor'})
                            )

                        );
                        break;

                    case "MINDEPOSITO":

                        array_push($array["MinimumDeposit"],

                            array(
                                "CurrencyId" => $bonodetalle->{'bono_detalle.moneda'},
                                "Amount" => intval($bonodetalle->{'bono_detalle.valor'})
                            )

                        );
                        break;

                    case "VALORBONO":
                        array_push($array["MoneyRequirement"],

                            array(
                                "CurrencyId" => $bonodetalle->{'bono_detalle.moneda'},
                                "Amount" => intval($bonodetalle->{'bono_detalle.valor'})
                            )

                        );
                        break;

                    case "CONDPAYMENT":

                        array_push($array["TriggerDetails"]["PaymentSystemIds"],
                            $bonodetalle->{'bono_detalle.valor'}

                        );
                        break;

                    case "CONDPAISPV":

                        array_push($array["TriggerDetails"]["Regions"],
                            $bonodetalle->{'bono_detalle.valor'}

                        );
                        break;

                    case "CONDDEPARTAMENTOPV":

                        array_push($array["TriggerDetails"]["Departments"],
                            $bonodetalle->{'bono_detalle.valor'}

                        );
                        break;

                    case "CONDCIUDADPV":

                        array_push($array["TriggerDetails"]["Cities"],
                            $bonodetalle->{'bono_detalle.valor'}

                        );
                        break;

                    case "CONDPAISUSER":

                        array_push($array["TriggerDetails"]["RegionsUser"],
                            $bonodetalle->{'bono_detalle.valor'}

                        );
                        break;

                    case "CONDDEPARTAMENTOUSER":

                        array_push($array["TriggerDetails"]["DepartmentsUser"],
                            $bonodetalle->{'bono_detalle.valor'}

                        );
                        break;

                    case "CONDCIUDADUSER":

                        array_push($array["TriggerDetails"]["CitiesUser"],
                            $bonodetalle->{'bono_detalle.valor'}

                        );
                        break;

                    case "CONDPUNTOVENTA":

                        array_push($array["TriggerDetails"]["CashDesk"],
                            $bonodetalle->{'bono_detalle.valor'}

                        );
                        break;


                    case "LIVEORPREMATCH":
                        $array["ForeignRule"]["Info"]["LiveOrPreMatch"] = ($bonodetalle->{'bono_detalle.valor'});
                        break;

                    case "MINSELCOUNT":
                        $array["ForeignRule"]["Info"]["MinSelCount"] = ($bonodetalle->{'bono_detalle.valor'});
                        break;


                    case "MINSELPRICE":
                        $array["ForeignRule"]["Info"]["MinSelPrice"] = ($bonodetalle->{'bono_detalle.valor'});
                        break;

                    case "MINBETPRICE":
                        $array["ForeignRule"]["Info"]["MinBetPrice"] = ($bonodetalle->{'bono_detalle.valor'});
                        break;


                    case "MINSELPRICETOTAL":
                        $array["ForeignRule"]["Info"]["MinSelPriceTotal"] = ($bonodetalle->{'bono_detalle.valor'});
                        break;


                    case "REPITEMERCADOS":
                        $array["ForeignRule"]["Info"]["RepeatMercados"] = ($bonodetalle->{'bono_detalle.valor'});
                        break;


                    case "REPITEPARTIDOS":
                        $array["ForeignRule"]["Info"]["RepeatMatches"] = ($bonodetalle->{'bono_detalle.valor'});
                        break;

                    case "WINBONOID":
                        $array["WinBonusId"] = ($bonodetalle->{'bono_detalle.valor'});
                        break;

                    case "TIPOSALDO":
                        $array["TypeSaldo"] = ($bonodetalle->{'bono_detalle.valor'});
                        break;


                    case "REPETIRBONO":
                        if (($bonodetalle->{'bono_detalle.valor'}) == 1 || ($bonodetalle->{'bono_detalle.valor'}) == true) {
                            $array["UserRepeatBonus"] = true;

                        }
                        break;

                    case "TIPOMAXAPUESTAROLLOVER":
                        $array["TypeMaxRollover"] = ($bonodetalle->{'bono_detalle.valor'});
                        break;


                    case "FROZEWALLET":
                        $array["DepositDefinition"]["UseFrozeWallet"] = boolval($bonodetalle->{'bono_detalle.valor'});
                        break;

                    case "SUPPRESSWITHDRAWAL":
                        $array["DepositDefinition"]["SuppressWithdrawal"] = boolval($bonodetalle->{'bono_detalle.valor'});

                        break;

                    case "SCHEDULECOUNT":
                        $array["Schedule"]["Count"] = intval($bonodetalle->{'bono_detalle.valor'});
                        break;

                    case "SCHEDULENAME":
                        $array["Schedule"]["Name"] = ($bonodetalle->{'bono_detalle.valor'});
                        break;

                    case "SCHEDULEPERIOD":
                        $array["Schedule"]["Period"] = intval($bonodetalle->{'bono_detalle.valor'});
                        break;


                    case "SCHEDULEPERIODTYPE":
                        $array["Schedule"]["PeriodType"] = intval($bonodetalle->{'bono_detalle.valor'});
                        break;

                    default:

                        if (stristr($bonodetalle->{'bono_detalle.tipo'}, 'CONDGAME')) {
                            $id = str_replace("CONDGAME", "", $bonodetalle->{'bono_detalle.tipo'});
                            $json = '{"rules" : [{"field" : "producto_mandante.prodmandante_id", "data": "' . $id . '","op":"eq"}] ,"groupOp" : "AND"}';
                            $ProductoMandante = new ProductoMandante();
                            $productos = $ProductoMandante->getProductosMandanteCustom(" producto_mandante.*,mandante.*,producto.* ", "producto_mandante.prodmandante_id", "asc", 0, 1, $json, true);

                            $productos = json_decode($productos);
                            $producto = $productos->data[0];
                            $CategoriaProducto = new CategoriaProducto("", $producto->{'producto.producto_id'});
                            array_push($array["GamesByCategories"],
                                array(
                                    "Id" => $CategoriaProducto->categoriaId,
                                    "Name" => "",
                                    "Games" =>
                                        array(array(
                                            "Id" => $id,
                                            "WageringPercent" => intval($bonodetalle->{'bono_detalle.valor'}),
                                            "Name" => $producto->{'producto.descripcion'},
                                            "ProviderId" => $producto->{'producto.proveedor_id'},
                                            "selected" => true
                                        ))
                                )

                            );
                        }

                        if (stristr($bonodetalle->{'bono_detalle.tipo'}, 'ITAINMENT') && false) {
                            if (oldCount($sports) == 0) {
                                $sportstmp = getSports();

                                foreach ($sportstmp as $sport) {
                                    $sport["Markets"] = getMarketTypes($sport["Id"]);
                                    $sport["Regions"] = getRegions($sport["Id"]);
                                    foreach ($sport["Regions"] as $region) {
                                        $sport["Regions"]["Competitions"] = getCompetitions($sport["Id"], $region["Id"]);
                                        foreach ($sport["Regions"]["Competitions"] as $competition) {
                                            $sport["Regions"]["Competitions"]["Matches"] = getMatches($sport["Id"], $region["Id"], $competition["Id"]);
                                        }
                                    }
                                    array_push($sports, $sport);

                                }

                            }

                            $tipo = intval(str_replace("ITAINMENT", "", $bonodetalle->{'bono_detalle.tipo'}));
                            $id = intval($bonodetalle->{'bono_detalle.valor'});

                            $data = array(
                                "Id" => $id,

                                "ObjectTypeId" => $tipo,
                                "ObjectId" => $id
                            );

                            switch ($tipo) {
                                case 1:

                                    foreach ($sports as $sport) {

                                        if ($id == $sport["Id"]) {
                                            $data["Name"] = $sport["Name"];
                                        }
                                    }

                                    break;

                                case 3:

                                    foreach ($sports as $sport) {

                                        foreach ($sport["Regions"] as $region) {
                                            foreach ($sport["Regions"]["Competitions"] as $competition) {
                                                if ($id == $competition["Id"]) {
                                                    $data["Name"] = $competition["Name"];
                                                    $data["SportName"] = $sport["Name"];
                                                }
                                            }
                                        }
                                    }

                                    break;


                                case 4:

                                    foreach ($sports as $sport) {

                                        foreach ($sport["Regions"] as $region) {
                                            foreach ($sport["Regions"]["Competitions"] as $competition) {
                                                foreach ($sport["Regions"]["Competitions"]["Matches"] as $match) {
                                                    if ($id == $match["Id"]) {
                                                        $data["Name"] = $match["Name"];
                                                        $data["SportName"] = $sport["Name"];
                                                    }
                                                }
                                            }
                                        }
                                    }

                                    break;


                                case 5:
                                    $id = ($bonodetalle->{'bono_detalle.valor'});

                                    foreach ($sports as $sport) {

                                        foreach ($sport["Markets"] as $market) {
                                            if ($id == $market["Id"]) {
                                                $data["Name"] = $market["Name"];
                                                $data["SportName"] = $sport["Name"];
                                                $data["Id"] = $market["Id"];
                                                $data["ObjectId"] = $market["Id"];
                                            }
                                        }
                                    }

                                    break;
                            }
                            array_push($array["ForeignRule"]["Info"]["SportBonusRules"], $data


                            );
                        }
                        break;


                }


            }


            array_push($final, $array);
        }

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Result"] = $array;

        break;

    /**
     * GetBonusDetails
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
    case "GetBonusDetails":

        $params = file_get_contents('php://input');
        $params = json_decode($params);

        $ToDateLocal = $params->StartTimeLocal;
        $FromDateLocal = $params->EndTimeLocal;
        $BonusId = $_REQUEST["Id"];

        $FromDateLocal = $params->EndTimeLocal;
        $FromDateLocal = $params->EndTimeLocal;

        $MaxRows = $params->MaxRows;
        $OrderedItem = $params->OrderedItem;
        $SkeepRows = $params->SkeepRows;

        $rules = [];


        if ($BonusId != "") {
            array_push($rules, array("field" => "bono_interno.bono_id", "data" => "$BonusId", "op" => "eq"));
        }

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

        $BonoInterno = new BonoInterno();

        $bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, true);

        $bonos = json_decode($bonos);

        $final = [];

        foreach ($bonos->data as $key => $value) {

            $rules = [];

            if ($ToDateLocal != "") {
                array_push($rules, array("field" => "promocional_log.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
            }
            if ($FromDateLocal != "") {
                array_push($rules, array("field" => "promocional_log.fecha_crea", "data" => "$FromDateLocal", "op" => "ge"));
            }


            if ($BonusId != "") {
                array_push($rules, array("field" => "promocional_log.promocional_id", "data" => $BonusId, "op" => "eq"));
            }

            /* if($PlayerExternalId != ""){
                 array_push($rules, array("field" => "promocional_log.usuario_id", "data" => $PlayerExternalId , "op" => "eq"));
             }*/

            $filtro = array("rules" => $rules, "groupOp" => "AND");

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 30;
            }

            $json = json_encode($filtro);

            $PromocionalLog = new PromocionalLog();

            $bonolog = $PromocionalLog->getPromocionalLogsCustom(" count(promocional_log.promolog_id) count, sum(promocional_log.valor) valor ", "promocional_log.promolog_id", "asc", $SkeepRows, $MaxRows, $json, true);

            $bonolog = json_decode($bonolog);

            $rules = [];


            if ($BonusId != "") {
                array_push($rules, array("field" => "bono_detalle.bono_id", "data" => "$BonusId", "op" => "eq"));
            }

            $filtro = array("rules" => $rules, "groupOp" => "AND");

            $json = json_encode($filtro);

            $BonoDetalle = new BonoDetalle();

            $bonodetalles = $BonoDetalle->getBonoDetallesCustom(" bono_detalle.* ", "bono_detalle.bonodetalle_id", "asc", $SkeepRows, $MaxRows, $json, true);

            $bonodetalles = json_decode($bonodetalles);

            $array = [];

            $array["Id"] = $value->{"bono_interno.bono_id"};
            $array["Name"] = $value->{"bono_interno.nombre"};
            $array["Description"] = $value->{"bono_interno.descripcion"};
            $array["BeginDate"] = str_replace(" ", "T", $value->{"bono_interno.fecha_inicio"});

            $array["EndDate"] = str_replace(" ", "T", $value->{"bono_interno.fecha_fin"});
            $array["TypeId"] = 2;
            $array["Type"] = array(
                "Id" => 2,
                "Name" => "Primer Deposito",
                "TypeId" => 2
            );
            $array["PartnerBonus"] = array(
                "StartDate" => "2018-02-09T00:00:00",
                "EndDate" => "2018-02-09T00:00:00",
                "ExpirationDays" => 6,
                "BonusDetails" => array(
                    array(
                        "CurrencyId" => "USD"

                    )
                )
            );

            $array["MaximumDeposit"] = array();
            $array["MinimumDeposit"] = array();
            $array["MaxPayout"] = array();
            $array["MoneyRequirement"] = array();
            $array["MaxPayout"] = array();

            $array["TriggerDetails"] = array();
            $array["TriggerDetails"]["PaymentSystemIds"] = array();
            $array["TriggerDetails"]["Regions"] = array();
            $array["DepositDefinition"] = array();
            $array["GamesByCategories"] = array();

            $array["ForeignRule"] = array();
            $array["ForeignRule"]["Info"] = array();
            $array["ForeignRule"]["Info"]["SportBonusRules"] = array();
            $array["ForeignRule"] = array();
            $array["CurrentCost"] = $bonolog->data[0]->{".valor"};
            $array["PlayersCount"] = $bonolog->data[0]->{".count"};


            $array["IsVisibleForAllplayers"] = true;

            $sports = array();
            $matches = array();
            $competitions = array();
            $regions = array();
            $markets = array();

            foreach ($bonodetalles->data as $bonodetalle) {
                //Expiracion
                switch ($bonodetalle->{'bono_detalle.tipo'}) {
                    case "EXPDIA":
                        $array["ExpirationDays"] = intval($bonodetalle->{'bono_detalle.valor'});
                        break;

                    case "TIPOPRODUCTO":
                        $array["ProductTypeId"] = intval($bonodetalle->{'bono_detalle.valor'});
                        break;

                    case "CANTDEPOSITOS":
                        $array["TriggerDetails"]["Count"] = intval($bonodetalle->{'bono_detalle.valor'});
                        break;

                    case "PAISESPERMITIDOS":
                        $array["TriggerDetails"]["AreAllowed"] = ($bonodetalle->{'bono_detalle.valor'});
                        break;

                    case "CONDEFECTIVO":
                        $array["TriggerDetails"]["IsFromCashDesk"] = boolval($bonodetalle->{'bono_detalle.valor'});
                        break;

                    case "EXPFECHA":
                        $array["ExpirationDate"] = ($bonodetalle->{'bono_detalle.valor'});
                        break;

                    case "PORCENTAJE":
                        $array["DepositDefinition"]["BonusPercent"] = intval($bonodetalle->{'bono_detalle.valor'});
                        break;

                    case "WFACTORBONO":
                        $array["DepositDefinition"]["BonusWFactor"] = intval($bonodetalle->{'bono_detalle.valor'});
                        break;

                    case "WFACTORDEPOSITO":
                        $array["DepositDefinition"]["DepositWFactor"] = intval($bonodetalle->{'bono_detalle.valor'});
                        break;

                    case "NUMERODEPOSITO":
                        $array["DepositDefinition"]["DepositNumber"] = intval($bonodetalle->{'bono_detalle.valor'});
                        break;

                    case "MAXJUGADORES":
                        $array["MaxplayersCount"] = intval($bonodetalle->{'bono_detalle.valor'});
                        $array["IsVisibleForAllplayers"] = false;
                        break;


                    case "MAXPAGO":
                        array_push($array["MaxPayout"],

                            array(
                                "CurrencyId" => $bonodetalle->{'bono_detalle.moneda'},
                                "Amount" => intval($bonodetalle->{'bono_detalle.valor'})
                            )

                        );

                        break;

                    case "MAXDEPOSITO":

                        array_push($array["MaximumDeposit"],

                            array(
                                "CurrencyId" => $bonodetalle->{'bono_detalle.moneda'},
                                "Amount" => intval($bonodetalle->{'bono_detalle.valor'})
                            )

                        );
                        break;

                    case "MINDEPOSITO":

                        array_push($array["MinimumDeposit"],

                            array(
                                "CurrencyId" => $bonodetalle->{'bono_detalle.moneda'},
                                "Amount" => intval($bonodetalle->{'bono_detalle.valor'})
                            )

                        );
                        break;

                    case "VALORBONO":
                        array_push($array["MoneyRequirement"],

                            array(
                                "CurrencyId" => $bonodetalle->{'bono_detalle.moneda'},
                                "Amount" => intval($bonodetalle->{'bono_detalle.valor'})
                            )

                        );
                        break;

                    case "CONDPAYMENT":

                        array_push($array["TriggerDetails"]["PaymentSystemIds"],
                            $bonodetalle->{'bono_detalle.valor'}

                        );
                        break;

                    case "CONDPAIS":

                        array_push($array["TriggerDetails"]["Regions"],
                            $bonodetalle->{'bono_detalle.valor'}

                        );
                        break;

                    case "LIVEORPREMATCH":
                        $array["ForeignRule"]["Info"]["LiveOrPreMatch"] = ($bonodetalle->{'bono_detalle.valor'});
                        break;

                    case "MINSELCOUNT":
                        $array["ForeignRule"]["Info"]["MinSelCount"] = ($bonodetalle->{'bono_detalle.valor'});
                        break;

                    case "LIVEORPREMATCH":
                        $array["MinSelCount"] = ($bonodetalle->{'bono_detalle.valor'});
                        break;

                    case "MINSELPRICE":
                        $array["MinSelPrice"] = ($bonodetalle->{'bono_detalle.valor'});
                        break;

                    case "MINBETPRICE":
                        $array["MinBetPrice"] = ($bonodetalle->{'bono_detalle.valor'});
                        break;

                    case "FROZEWALLET":
                        $array["DepositDefinition"]["UseFrozeWallet"] = boolval($bonodetalle->{'bono_detalle.valor'});
                        break;

                    case "SUPPRESSWITHDRAWAL":
                        $array["DepositDefinition"]["SuppressWithdrawal"] = boolval($bonodetalle->{'bono_detalle.valor'});

                        break;

                    case "SCHEDULECOUNT":
                        $array["Schedule"]["Count"] = intval($bonodetalle->{'bono_detalle.valor'});
                        break;

                    case "SCHEDULENAME":
                        $array["Schedule"]["Name"] = ($bonodetalle->{'bono_detalle.valor'});
                        break;

                    case "SCHEDULEPERIOD":
                        $array["Schedule"]["Period"] = intval($bonodetalle->{'bono_detalle.valor'});
                        break;


                    case "SCHEDULEPERIODTYPE":
                        $array["Schedule"]["PeriodType"] = intval($bonodetalle->{'bono_detalle.valor'});
                        break;

                    default:

                        if (stristr($bonodetalle->{'bono_detalle.tipo'}, 'CONDGAME')) {
                            $id = str_replace("CONDGAME", "", $bonodetalle->{'bono_detalle.tipo'});
                            $json = '{"rules" : [{"field" : "producto_mandante.prodmandante_id", "data": "' . $id . '","op":"eq"}] ,"groupOp" : "AND"}';
                            $ProductoMandante = new ProductoMandante();
                            $productos = $ProductoMandante->getProductosMandanteCustom(" producto_mandante.*,mandante.*,producto.* ", "producto_mandante.prodmandante_id", "asc", 0, 1, $json, true);

                            $productos = json_decode($productos);
                            $producto = $productos->data[0];
                            $CategoriaProducto = new CategoriaProducto("", $producto->{'producto.producto_id'});
                            array_push($array["GamesByCategories"],
                                array(
                                    "Id" => $CategoriaProducto->categoriaId,
                                    "Name" => "",
                                    "Games" =>
                                        array(array(
                                            "Id" => $id,
                                            "WageringPercent" => intval($bonodetalle->{'bono_detalle.valor'}),
                                            "Name" => $producto->{'producto.descripcion'},
                                            "ProviderId" => $producto->{'producto.proveedor_id'},
                                            "selected" => true
                                        ))
                                )

                            );
                        }

                        if (stristr($bonodetalle->{'bono_detalle.tipo'}, 'ITAINMENT')) {
                            if (oldCount($sports) == 0) {
                                $sportstmp = getSports();

                                foreach ($sportstmp as $sport) {
                                    $sport["Markets"] = getMarketTypes($sport["Id"]);
                                    $sport["Regions"] = getRegions($sport["Id"]);
                                    foreach ($sport["Regions"] as $region) {
                                        $sport["Regions"]["Competitions"] = getCompetitions($sport["Id"], $region["Id"]);
                                        foreach ($sport["Regions"]["Competitions"] as $competition) {
                                            $sport["Regions"]["Competitions"]["Matches"] = getMatches($sport["Id"], $region["Id"], $competition["Id"]);
                                        }
                                    }
                                    array_push($sports, $sport);

                                }

                            }

                            $tipo = intval(str_replace("ITAINMENT", "", $bonodetalle->{'bono_detalle.tipo'}));
                            $id = intval($bonodetalle->{'bono_detalle.valor'});

                            $data = array(
                                "Id" => $id,

                                "ObjectTypeId" => $tipo,
                                "ObjectId" => $id
                            );

                            switch ($tipo) {
                                case 1:

                                    foreach ($sports as $sport) {

                                        if ($id == $sport["Id"]) {
                                            $data["Name"] = $sport["Name"];
                                        }
                                    }

                                    break;

                                case 3:

                                    foreach ($sports as $sport) {

                                        foreach ($sport["Regions"] as $region) {
                                            foreach ($sport["Regions"]["Competitions"] as $competition) {
                                                if ($id == $competition["Id"]) {
                                                    $data["Name"] = $competition["Name"];
                                                    $data["SportName"] = $sport["Name"];
                                                }
                                            }
                                        }
                                    }

                                    break;


                                case 4:

                                    foreach ($sports as $sport) {

                                        foreach ($sport["Regions"] as $region) {
                                            foreach ($sport["Regions"]["Competitions"] as $competition) {
                                                foreach ($sport["Regions"]["Competitions"]["Matches"] as $match) {
                                                    if ($id == $match["Id"]) {
                                                        $data["Name"] = $match["Name"];
                                                        $data["SportName"] = $sport["Name"];
                                                    }
                                                }
                                            }
                                        }
                                    }

                                    break;


                                case 5:
                                    $id = ($bonodetalle->{'bono_detalle.valor'});

                                    foreach ($sports as $sport) {

                                        foreach ($sport["Markets"] as $market) {
                                            if ($id == $market["Id"]) {
                                                $data["Name"] = $market["Name"];
                                                $data["SportName"] = $sport["Name"];
                                                $data["Id"] = $market["Id"];
                                                $data["ObjectId"] = $market["Id"];
                                            }
                                        }
                                    }

                                    break;
                            }
                            array_push($array["ForeignRule"]["Info"]["SportBonusRules"], $data


                            );
                        }
                        break;


                }


            }


            array_push($final, $array);
        }

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Result"] = $array;

        break;

    /**
     * GetBonusTypes
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
    case "GetBonusTypes":

        $response["HasError"] = false;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = "Success";
        $response["ModelErrors"] = [];
        $response["Result"] = array(

            /*
               array(
                   "Id" => 1,
                   "Name" => "SportBonus"),
               array(
                   "Id" => 2,
                   "Name" => "Bono Deposito"),

               array(
                   "Id" => 3,
                   "Name" => "NoDepositBonus"),

               array(
                   "Id" => 6,
                   "Name" => "FreeBet")

            array(
                   "Id" => 3,
                   "Name" => "Bono No Deposito"),
               array(
                   "Id" => 4,
                   "Name" => "Bono Cash"),
               array(
                   "Id" => 5,
                   "Name" => "FreeSpin"),
               array(
                   "Id" => 6,
                   "Name" => "FreeBet"),
               array(
                   "Id" => 7,
                   "Name" => "BonusMoney"),
               array(
                   "Id"=>"8",
                   "Name"=>"BetType"
               )
           );
            */

            array(
                "Id" => 2,
                "Name" => "Bono Deposito"),
            array(
                "Id" => 3,
                "Name" => "Bono No Deposito"),
            array(
                "Id" => 5,
                "Name" => "FreeCasino"),
            array(
                "Id" => 6,
                "Name" => "FreeBet")

        );


        break;

    /**
     * GetProviders
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
    case "GetProviders":
        $type = $_REQUEST["Type"];

        $tipo = 'CASINO';

        if ($type == 3) {
            $tipo = 'LIVECASINO';
        }

        if ($type == 4) {
            $tipo = 'VIRTUAL';
        }


        $Proveedor = new Proveedor();

        $SkeepRows = 0;
        $MaxRows = 1000000;

        $json = '{"rules" : [{"field" : "proveedor.tipo", "data": "' . $tipo . '","op":"eq"}] ,"groupOp" : "AND"}';

        $proveedores = $Proveedor->getProveedoresCustom(" proveedor.* ", "proveedor.proveedor_id", "asc", $SkeepRows, $MaxRows, $json, true);
        $proveedores = json_decode($proveedores);

        $final = [];

        foreach ($proveedores->data as $key => $value) {

            $array = [];

            $array["Id"] = $value->{"proveedor.proveedor_id"};
            $array["Name"] = $value->{"proveedor.descripcion"};

            array_push($final, $array);

        }

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Data"] = $final;

        break;

    /**
     * GetCategories
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
    case "GetCategories":

        $type = $_REQUEST["Type"];

        $tipo = 'CASINO';

        if ($type == 3) {
            $tipo = 'LIVECASINO';
        }

        if ($type == 4) {
            $tipo = 'VIRTUAL';
        }


        $CMSCategoria = new CMSCategoria("", $tipo);
        $Categorias = $CMSCategoria->getCategorias();
        $Categorias = json_decode($Categorias);

        $data = $Categorias->data;

        $final = [];


        foreach ($data as $key => $value) {

            $array = [];

            $array["Id"] = $value->id;
            $array["Name"] = $value->descripcion;

            array_push($final, $array);

        }

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Data"] = $final;

        break;

    /**
     * GetGames
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
    case "GetGames":

        $type = $params->Type;

        $tipo = 'CASINO';

        if ($type == 3) {
            $tipo = 'LIVECASINO';
        }

        if ($type == 4) {
            $tipo = 'VIRTUAL';
        }


        $ProviderId = $params->ProviderId;
        $Proveedor = new Proveedor($ProviderId);


        $ProductoMandante = new ProductoMandante();

        $rules = [];
        array_push($rules, array("field" => "proveedor.proveedor_id", "data" => "$ProviderId", "op" => "eq"));
        array_push($rules, array("field" => "proveedor.tipo", "data" => "$tipo", "op" => "eq"));


        // Si el usuario esta condicionado por País
        if ($_SESSION['PaisCond'] == "S") {
        }
        // Si el usuario esta condicionado por el mandante y no es de Global
        if ($_SESSION['Global'] == "N") {
            array_push($rules, array("field" => "producto_mandante.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
        }


        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $SkeepRows = 0;
        $MaxRows = 1000;

        $productos = $ProductoMandante->getProductosMandanteCustom(" producto_mandante.*,mandante.*,producto.* ", "producto_mandante.prodmandante_id", "asc", $SkeepRows, $MaxRows, $json, true);
        $productos = json_decode($productos);


        $data = array();
        $final = array();

        foreach ($productos->data as $producto) {


            array_push($final, array(
                "Id" => $producto->{'producto_mandante.prodmandante_id'},
                "Name" => $producto->{'producto.descripcion'},
                "ProviderId" => $ProviderId
            ));

        }

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Data"] = $final;

        break;

    /**
     * GetGamesByCategories
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
    case "GetGamesByCategories":

        $ProviderId = $params->ProviderId;
        $Proveedor = new Proveedor($ProviderId);


        $Productos = $Proveedor->getProductosTipo("", $Proveedor->getAbreviado(), 0, 100000, "", '0');

        $data = array();
        $final = array();

        foreach ($Productos as $producto) {
            $encontroCategoria = false;
            $item = array();
            $pos = 0;
            foreach ($final as $category) {
                if ($producto['categoria.categoria_id'] == $category['Id']) {
                    $item = $category;
                    $encontroCategoria = true;
                    break;
                }
                $pos = $pos + 1;
            }
            if (!$encontroCategoria) {
                $item["Id"] = $producto['categoria.categoria_id'];
                $item["Name"] = $producto['categoria.descripcion'];
                $item["Games"] = array();
                array_push($final, $item);
            }

            array_push($final[$pos]["Games"], array(
                "Id" => $producto['producto_mandante.prodmandante_id'],
                "Name" => $producto['producto.descripcion'],
                "ProviderId" => $ProviderId
            ));

        }


        /* $Producto = new Producto();

         $SkeepRows = 0;
         $MaxRows = 1000000;

         $json = '{"rules" : [{"field" : "producto.proveedor_id", "data": "' . $ProviderId . '","op":"eq"}] ,"groupOp" : "AND"}';

         $productos = $Producto->getProductos("producto.producto_id", "asc", $SkeepRows, $MaxRows, $json, true);

         $productos = json_decode($productos);

         $final = [];

         foreach ($productos->data as $key => $value) {

             $array = [];

             $array["Id"] = $value->{"producto.producto_id"};
             $array["Name"] = $value->{"producto.descripcion"};
             $array["ProviderId"] = $ProviderId;

             array_push($final, $array);

         }*/

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Data"] = $final;

        break;

    /**
     * CreateTournament
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
    case "CreateTournament":
        error_reporting(E_ALL);

        ini_set('display_errors', 'ON');


        if ($params == "" || $params == null) {
            exit();
        }
        $mandanteUsuario = 0;
        // Si el usuario esta condicionado por el mandante y no es de Global
        if ($_SESSION['Global'] == "N") {
            $mandanteUsuario = $_SESSION['mandante'];
        }


        $Description = $params->Description; //Descripcion del bono
        $Name = $params->Name; //Nombre del bono

        $StartDate = $params->BeginDate; //Fecha Inicial de la campaña
        $EndDate = $params->EndDate; //Fecha Final de la campaña
        $CodeGlobal = $params->CodeGlobal;
        $TypeRanking = $params->TypeRanking;

        $tipobono = 1;

        $ConditionProduct = 'OR';
        $Priority = $params->Priority;

        if ($Priority == "" || !is_numeric($Priority)) {
            $Priority = 0;
        }

        $cupo = 0;
        $cupoMaximo = 0;
        $jugadores = 0;
        $jugadoresMaximo = 0;
        $MaxplayersCount = $params->MaxplayersCount;
        $MinplayersCount = $params->MaxplayersCount;
        $jugadoresMaximo = $MaxplayersCount;

        $ForeignRule = $params->ForeignRule;
        $ForeignRuleInfo = $ForeignRule->Info;

        if (!is_object($ForeignRuleInfo)) {
            $ForeignRuleJSON = json_decode($ForeignRuleInfo);

        } else {
            $ForeignRuleJSON = $ForeignRuleInfo;
        }

        $LiveOrPreMatch = $ForeignRuleJSON->LiveOrPreMatch;
        $MinSelCount = $ForeignRuleJSON->MinSelCount; // Minimo cantidad de selecciones
        $MinSelPrice = $ForeignRuleJSON->MinSelPrice; // Minimo cuota seleccionada
        $MinSelPriceTotal = $ForeignRuleJSON->MinSelPriceTotal;
        $MinBetPrice = $ForeignRuleJSON->MinBetPrice; // Minimo apuesta cuota total

        $SportBonusRules = $ForeignRuleJSON->SportBonusRules;

        $MainImageURL = $params->MainImageURL;
        $RankingImageURL = $params->RankingImageURL;
        $BackgroundURL = $params->BackgroundURL;

        $PrizeDescription = $params->PrizeDescription;
        $PrizeImageURL = $params->PrizeImageURL;
        $Ranks = $params->Ranks;
        $RanksPrize = $params->RanksPrize;
        $LinesByPoints = ($params->LinesByPoints == true || $params->LinesByPoints == "true") ? 1 : 0;

        $AmountPrize = $params->AmountPrize;
        $TypeRule = ($params->TypeRule == 1) ? 1 : 0;


        $TriggerDetails = $params->TriggerDetails;
        $Count = $TriggerDetails->Count; //Cantidad de depositos

        //$AreAllowed = $TriggerDetails->AreAllowed; //Son Permitidos los paises

        $IsFromCashDesk = $TriggerDetails->IsFromCashDesk;
        $PaymentSystemId = $TriggerDetails->PaymentSystemId;
        $PaymentSystemIds = $TriggerDetails->PaymentSystemIds;

        $UserRepeatBonus = $params->UserRepeatBonus;

        $WinBonusId = $params->WinBonusId;
        $TypeSaldo = $params->TypeSaldo;


        $ConditionProduct = $TriggerDetails->ConditionProduct;
        if ($ConditionProduct != "OR" && $ConditionProduct != "AND") {
            $ConditionProduct = 'NA';
        }

        $Regions = $TriggerDetails->Regions;
        $Departments = $TriggerDetails->Departments;
        $Cities = $TriggerDetails->Cities;
        $CashDesks = $TriggerDetails->CashDesks;

        $RegionsUser = $TriggerDetails->RegionsUser;
        $DepartmentsUser = $TriggerDetails->DepartmentsUser;
        $CitiesUser = $TriggerDetails->CitiesUser;

        $BalanceZero = $TriggerDetails->BalanceZero;

        $Casino = $params->Casino->Info;
        $CasinoProduct = $Casino->Product;
        $CasinoProvider = $Casino->Provider;
        $CasinoCategory = $Casino->Category;


        $TypeBonusDeposit = $params->TypeBonusDeposit;


        $RulesText = str_replace("#######", "'", $params->RulesText);
        $RulesText = str_replace("'", "\'", $RulesText);
        $UserSubscribe = $params->UserSubscribe;
        $TypeProduct = ($params->TypeProduct == 0) ? 0 : 1;
        $TypeOtherProduct = $params->TypeOtherProduct;
        $tipobono = ($params->TypeProduct == 0) ? 1 : 2;

        if ($tipobono == 2) {

            if ($TypeOtherProduct == 0) {

            }
            if ($TypeOtherProduct == 3) {
                $tipobono = 3;
            }
            if ($TypeOtherProduct == 4) {
                $tipobono = 4;
            }
        }

        if ($UserSubscribe) {
            $UserSubscribe = 1;
        } else {
            $UserSubscribe = 0;
        }


        $IsVisibleForAllplayers = $params->IsVisibleForAllplayers; //Es para todos los usuarios

        $OpenToAllPlayers = $params->OpenToAllPlayers;//Es para todos los usuarios
        $Prefix = $params->Prefix;

        $PlayersChosen = $params->PlayersChosen;


        $ProductTypeId = $params->ProductTypeId;

        $TriggerId = $params->TriggerId;

        $TypeId = $params->TypeId;

        $Games = $params->Games;

        $condiciones = [];


        $FreeSpinDefinition = $params->FreeSpinDefinition;
        $AutomaticForfeitureLevel = $FreeSpinDefinition->AutomaticForfeitureLevel;
        $BonusMoneyExpirationDate = $FreeSpinDefinition->BonusMoneyExpirationDate;
        $BonusMoneyExpirationDays = $FreeSpinDefinition->BonusMoneyExpirationDays;
        $FreeSpinsTotalCount = $FreeSpinDefinition->FreeSpinsTotalCount;
        $WageringFactor = $FreeSpinDefinition->WageringFactor;
        $PlayersChosen = $params->PlayersChosen;
        $regex = <<<'END'
/
  (
    (?: [\x00-\x7F]                 # single-byte sequences   0xxxxxxx
    |   [\xC0-\xDF][\x80-\xBF]      # double-byte sequences   110xxxxx 10xxxxxx
    |   [\xE0-\xEF][\x80-\xBF]{2}   # triple-byte sequences   1110xxxx 10xxxxxx * 2
    |   [\xF0-\xF7][\x80-\xBF]{3}   # quadruple-byte sequence 11110xxx 10xxxxxx * 3 
    ){1,100}                        # ...one or more times
  )
| .                                 # anything else
/x
END;


        $TorneoInterno = new TorneoInterno();
        $TorneoInterno->nombre = $Name;
        $TorneoInterno->descripcion = $Description;
        $TorneoInterno->fechaInicio = $StartDate;
        $TorneoInterno->fechaFin = $EndDate;
        $TorneoInterno->tipo = $tipobono;
        $TorneoInterno->estado = 'A';
        $TorneoInterno->usucreaId = 0;
        $TorneoInterno->usumodifId = 0;
        $TorneoInterno->mandante = $mandanteUsuario;
        $TorneoInterno->condicional = $ConditionProduct;
        $TorneoInterno->orden = $Priority;
        $TorneoInterno->cupoActual = $cupo;
        $TorneoInterno->cupoMaximo = $cupoMaximo;
        $TorneoInterno->cantidadTorneos = $jugadores;
        $TorneoInterno->maximoTorneos = $jugadoresMaximo;
        $TorneoInterno->codigo = $CodeGlobal;
        $TorneoInterno->reglas = $RulesText;

        $TorneoDetalleMySqlDAO = new TorneoDetalleMySqlDAO();
        $transaccion = $TorneoDetalleMySqlDAO->getTransaction();
        $torneoId = $TorneoInterno->insert($transaccion);


        foreach ($SportBonusRules as $key => $value) {

            $TorneoDetalle = new TorneoDetalle();
            $TorneoDetalle->torneoId = $torneoId;
            $TorneoDetalle->tipo = "ITAINMENT" . $value->ObjectTypeId;
            $TorneoDetalle->moneda = '';
            $TorneoDetalle->valor = $value->ObjectId;
            $TorneoDetalle->descripcion = $value->Image;
            $TorneoDetalle->valor2 = '';
            $TorneoDetalle->valor3 = '';
            $TorneoDetalle->usucreaId = 0;
            $TorneoDetalle->usumodifId = 0;
            $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
            $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
        }


        $TorneoDetalle = new TorneoDetalle();
        $TorneoDetalle->torneoId = $torneoId;
        $TorneoDetalle->tipo = "VISIBILIDAD";
        $TorneoDetalle->moneda = '';
        $TorneoDetalle->valor = $TypeRule;
        $TorneoDetalle->valor2 = '';
        $TorneoDetalle->valor3 = '';
        $TorneoDetalle->usucreaId = 0;
        $TorneoDetalle->usumodifId = 0;
        $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
        $TorneoDetalleMysqlDAO->insert($TorneoDetalle);

        $TorneoDetalle = new TorneoDetalle();
        $TorneoDetalle->torneoId = $torneoId;
        $TorneoDetalle->tipo = "USERSUBSCRIBE";
        $TorneoDetalle->moneda = '';
        $TorneoDetalle->valor = $UserSubscribe;
        $TorneoDetalle->valor2 = '';
        $TorneoDetalle->valor3 = '';
        $TorneoDetalle->usucreaId = 0;
        $TorneoDetalle->usumodifId = 0;
        $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
        $TorneoDetalleMysqlDAO->insert($TorneoDetalle);

        $TorneoDetalle = new TorneoDetalle();
        $TorneoDetalle->torneoId = $torneoId;
        $TorneoDetalle->tipo = "TIPOPRODUCTO";
        $TorneoDetalle->moneda = '';
        $TorneoDetalle->valor = $TypeProduct;
        $TorneoDetalle->valor2 = '';
        $TorneoDetalle->valor3 = '';
        $TorneoDetalle->usucreaId = 0;
        $TorneoDetalle->usumodifId = 0;
        $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
        $TorneoDetalleMysqlDAO->insert($TorneoDetalle);

        if ($LiveOrPreMatch != "") {
            $TorneoDetalle = new TorneoDetalle();
            $TorneoDetalle->torneoId = $torneoId;
            $TorneoDetalle->tipo = "LIVEORPREMATCH";
            $TorneoDetalle->moneda = '';
            $TorneoDetalle->valor = $LiveOrPreMatch;
            $TorneoDetalle->valor2 = '';
            $TorneoDetalle->valor3 = '';
            $TorneoDetalle->usucreaId = 0;
            $TorneoDetalle->usumodifId = 0;
            $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
            $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
        }

        if ($MinSelCount != "") {
            $TorneoDetalle = new TorneoDetalle();
            $TorneoDetalle->torneoId = $torneoId;
            $TorneoDetalle->tipo = "MINSELCOUNT";
            $TorneoDetalle->moneda = '';
            $TorneoDetalle->valor = $MinSelCount;
            $TorneoDetalle->valor2 = '';
            $TorneoDetalle->valor3 = '';
            $TorneoDetalle->usucreaId = 0;
            $TorneoDetalle->usumodifId = 0;
            $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
            $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
        }

        if ($MinSelPrice != "") {
            $TorneoDetalle = new TorneoDetalle();
            $TorneoDetalle->torneoId = $torneoId;
            $TorneoDetalle->tipo = "MINSELPRICE";
            $TorneoDetalle->moneda = '';
            $TorneoDetalle->valor = $MinSelPrice;
            $TorneoDetalle->valor2 = '';
            $TorneoDetalle->valor3 = '';
            $TorneoDetalle->usucreaId = 0;
            $TorneoDetalle->usumodifId = 0;
            $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
            $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
        }

        if ($MinSelPriceTotal != "") {
            $TorneoDetalle = new TorneoDetalle();
            $TorneoDetalle->torneoId = $torneoId;
            $TorneoDetalle->tipo = "MINSELPRICETOTAL";
            $TorneoDetalle->moneda = '';
            $TorneoDetalle->valor = $MinSelPriceTotal;
            $TorneoDetalle->valor2 = '';
            $TorneoDetalle->valor3 = '';
            $TorneoDetalle->usucreaId = 0;
            $TorneoDetalle->usumodifId = 0;
            $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
            $TorneoDetalleMysqlDAO->insert($TorneoDetalle);

        }

        if ($MinBetPrice != "" && false) {
            $TorneoDetalle = new TorneoDetalle();
            $TorneoDetalle->torneoId = $torneoId;
            $TorneoDetalle->tipo = "MINBETPRICE";
            $TorneoDetalle->moneda = '';
            $TorneoDetalle->valor = $MinBetPrice;
            $TorneoDetalle->valor2 = '';
            $TorneoDetalle->valor3 = '';
            $TorneoDetalle->usucreaId = 0;
            $TorneoDetalle->usumodifId = 0;
            $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
            $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
        }

        foreach ($PrizeDescription as $key => $value) {
            $TorneoDetalle = new TorneoDetalle();
            $TorneoDetalle->torneoId = $torneoId;
            $TorneoDetalle->tipo = "PREMIODESCRIPCION";
            $TorneoDetalle->moneda = $value->CurrencyId;
            $TorneoDetalle->valor = $value->Amount;
            $TorneoDetalle->valor2 = '';
            $TorneoDetalle->valor3 = '';
            $TorneoDetalle->usucreaId = 0;
            $TorneoDetalle->usumodifId = 0;
            $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
            $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
        }

        foreach ($PrizeImageURL as $key => $value) {
            $TorneoDetalle = new TorneoDetalle();
            $TorneoDetalle->torneoId = $torneoId;
            $TorneoDetalle->tipo = "PREMIOIMAGEN";
            $TorneoDetalle->moneda = $value->CurrencyId;
            $TorneoDetalle->valor = $value->Amount;
            $TorneoDetalle->valor2 = '';
            $TorneoDetalle->valor3 = '';
            $TorneoDetalle->usucreaId = 0;
            $TorneoDetalle->usumodifId = 0;
            $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
            $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
        }


        /*foreach ($AmountPrize as $key => $value) {
            $TorneoDetalle = new TorneoDetalle();
            $TorneoDetalle->torneoId = $torneoId;
            $TorneoDetalle->tipo = "PREMIOMONTO";
            $TorneoDetalle->moneda = $value->CurrencyId;
            $TorneoDetalle->valor = $value->Amount;
            $TorneoDetalle->valor2 = '';
            $TorneoDetalle->valor3 = '';
            $TorneoDetalle->usucreaId = 0;
            $TorneoDetalle->usumodifId = 0;
            $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
            $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
        }*/


        foreach ($Ranks as $key => $value) {
            foreach ($value->Amount as $key2 => $value2) {

                $TorneoDetalle = new TorneoDetalle();
                $TorneoDetalle->torneoId = $torneoId;
                $TorneoDetalle->tipo = ($TypeRanking == 1) ? "RANKLINE" : "RANK";
                $TorneoDetalle->moneda = $value->CurrencyId;
                $TorneoDetalle->valor = $value2->initialRange;
                $TorneoDetalle->valor2 = $value2->finalRange;
                $TorneoDetalle->valor3 = $value2->credits;
                $TorneoDetalle->usucreaId = 0;
                $TorneoDetalle->usumodifId = 0;
                $TorneoDetalleMySqlDAO = new TorneoDetalleMySqlDAO($transaccion);
                $TorneoDetalleMySqlDAO->insert($TorneoDetalle);
            }
        }


        foreach ($RanksPrize as $key => $value) {
            foreach ($value->Amount as $key2 => $value2) {

                $description = "RANKAWARD";
                if ($value2->type == 0) {
                    $description = "RANKAWARDMAT";
                }

                $TorneoDetalle = new TorneoDetalle();
                $TorneoDetalle->torneoId = $torneoId;
                $TorneoDetalle->tipo = $description;
                $TorneoDetalle->moneda = $value->CurrencyId;
                $TorneoDetalle->valor = $value2->position;
                $TorneoDetalle->valor2 = $value2->description;
                $TorneoDetalle->valor3 = $value2->amount;
                $TorneoDetalle->usucreaId = 0;
                $TorneoDetalle->usumodifId = 0;
                $TorneoDetalleMySqlDAO = new TorneoDetalleMySqlDAO($transaccion);
                $TorneoDetalleMySqlDAO->insert($TorneoDetalle);
            }
        }

        if ($MainImageURL != "") {
            $TorneoDetalle = new TorneoDetalle();
            $TorneoDetalle->torneoId = $torneoId;
            $TorneoDetalle->tipo = "IMGPPALURL";
            $TorneoDetalle->moneda = '';
            $TorneoDetalle->valor = $MainImageURL;
            $TorneoDetalle->valor2 = '';
            $TorneoDetalle->valor3 = '';
            $TorneoDetalle->usucreaId = 0;
            $TorneoDetalle->usumodifId = 0;
            $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
            $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
        }

        if ($RankingImageURL != "") {
            $TorneoDetalle = new TorneoDetalle();
            $TorneoDetalle->torneoId = $torneoId;
            $TorneoDetalle->tipo = "RANKIMGURL";
            $TorneoDetalle->moneda = '';
            $TorneoDetalle->valor = $RankingImageURL;
            $TorneoDetalle->valor2 = '';
            $TorneoDetalle->valor3 = '';
            $TorneoDetalle->usucreaId = 0;
            $TorneoDetalle->usumodifId = 0;
            $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
            $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
        }


        if ($BackgroundURL != "") {
            $TorneoDetalle = new TorneoDetalle();
            $TorneoDetalle->torneoId = $torneoId;
            $TorneoDetalle->tipo = "BACKGROUNDURL";
            $TorneoDetalle->moneda = '';
            $TorneoDetalle->valor = $BackgroundURL;
            $TorneoDetalle->valor2 = '';
            $TorneoDetalle->valor3 = '';
            $TorneoDetalle->usucreaId = 0;
            $TorneoDetalle->usumodifId = 0;
            $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
            $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
        }

        if ($ProductTypeId !== "") {

            $TorneoDetalle = new TorneoDetalle();
            $TorneoDetalle->torneoId = $torneoId;
            $TorneoDetalle->tipo = "TIPOPRODUCTO";
            $TorneoDetalle->moneda = '';
            $TorneoDetalle->valor = $ProductTypeId;
            $TorneoDetalle->valor2 = '';
            $TorneoDetalle->valor3 = '';
            $TorneoDetalle->usucreaId = 0;
            $TorneoDetalle->usumodifId = 0;
            $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
            $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
        }

        if ($LinesByPoints == 1) {

            $TorneoDetalle = new TorneoDetalle();
            $TorneoDetalle->torneoId = $torneoId;
            $TorneoDetalle->tipo = "MULTLINEAS";
            $TorneoDetalle->moneda = '';
            $TorneoDetalle->valor = $LinesByPoints;
            $TorneoDetalle->valor2 = '';
            $TorneoDetalle->valor3 = '';
            $TorneoDetalle->usucreaId = 0;
            $TorneoDetalle->usumodifId = 0;
            $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
            $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
        }


        foreach ($PaymentSystemIds as $key => $value) {
            $TorneoDetalle = new TorneoDetalle();
            $TorneoDetalle->torneoId = $torneoId;
            $TorneoDetalle->tipo = "CONDPAYMENT";
            $TorneoDetalle->moneda = '';
            $TorneoDetalle->valor = $value;
            $TorneoDetalle->valor2 = '';
            $TorneoDetalle->valor3 = '';
            $TorneoDetalle->usucreaId = 0;
            $TorneoDetalle->usumodifId = 0;
            $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
            $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
        }


        foreach ($Regions as $key => $value) {

            $TorneoDetalle = new TorneoDetalle();
            $TorneoDetalle->torneoId = $torneoId;
            $TorneoDetalle->tipo = "CONDPAISPV";
            $TorneoDetalle->moneda = '';
            $TorneoDetalle->valor = $value;
            $TorneoDetalle->valor2 = '';
            $TorneoDetalle->valor3 = '';
            $TorneoDetalle->usucreaId = 0;
            $TorneoDetalle->usumodifId = 0;
            $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
            $TorneoDetalleMysqlDAO->insert($TorneoDetalle);

        }

        foreach ($Departments as $key => $value) {

            $TorneoDetalle = new TorneoDetalle();
            $TorneoDetalle->torneoId = $torneoId;
            $TorneoDetalle->tipo = "CONDDEPARTAMENTOPV";
            $TorneoDetalle->moneda = '';
            $TorneoDetalle->valor = $value;
            $TorneoDetalle->valor2 = '';
            $TorneoDetalle->valor3 = '';
            $TorneoDetalle->usucreaId = 0;
            $TorneoDetalle->usumodifId = 0;
            $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
            $TorneoDetalleMysqlDAO->insert($TorneoDetalle);

        }

        foreach ($Cities as $key => $value) {

            $TorneoDetalle = new TorneoDetalle();
            $TorneoDetalle->torneoId = $torneoId;
            $TorneoDetalle->tipo = "CONDCIUDADPV";
            $TorneoDetalle->moneda = '';
            $TorneoDetalle->valor = $value;
            $TorneoDetalle->valor2 = '';
            $TorneoDetalle->valor3 = '';
            $TorneoDetalle->usucreaId = 0;
            $TorneoDetalle->usumodifId = 0;
            $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
            $TorneoDetalleMysqlDAO->insert($TorneoDetalle);

        }

        if ($BalanceZero == "true" || $BalanceZero == 1 || $BalanceZero == true) {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "CONDBALANCE";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = '0';
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        foreach ($RegionsUser as $key => $value) {

            $TorneoDetalle = new TorneoDetalle();
            $TorneoDetalle->torneoId = $torneoId;
            $TorneoDetalle->tipo = "CONDPAISUSER";
            $TorneoDetalle->moneda = '';
            $TorneoDetalle->valor = $value;
            $TorneoDetalle->valor2 = '';
            $TorneoDetalle->valor3 = '';
            $TorneoDetalle->usucreaId = 0;
            $TorneoDetalle->usumodifId = 0;
            $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
            $TorneoDetalleMysqlDAO->insert($TorneoDetalle);

        }

        foreach ($DepartmentsUser as $key => $value) {

            $TorneoDetalle = new TorneoDetalle();
            $TorneoDetalle->torneoId = $torneoId;
            $TorneoDetalle->tipo = "CONDDEPARTAMENTOUSER";
            $TorneoDetalle->moneda = '';
            $TorneoDetalle->valor = $value;
            $TorneoDetalle->valor2 = '';
            $TorneoDetalle->valor3 = '';
            $TorneoDetalle->usucreaId = 0;
            $TorneoDetalle->usumodifId = 0;
            $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
            $TorneoDetalleMysqlDAO->insert($TorneoDetalle);


        }

        foreach ($CitiesUser as $key => $value) {

            $TorneoDetalle = new TorneoDetalle();
            $TorneoDetalle->torneoId = $torneoId;
            $TorneoDetalle->tipo = "CONDCIUDADUSER";
            $TorneoDetalle->moneda = '';
            $TorneoDetalle->valor = $value;
            $TorneoDetalle->valor2 = '';
            $TorneoDetalle->valor3 = '';
            $TorneoDetalle->usucreaId = 0;
            $TorneoDetalle->usumodifId = 0;
            $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
            $TorneoDetalleMysqlDAO->insert($TorneoDetalle);

        }

        foreach ($CashDesks as $key => $value) {

            $TorneoDetalle = new TorneoDetalle();
            $TorneoDetalle->torneoId = $torneoId;
            $TorneoDetalle->tipo = "CONDPUNTOVENTA";
            $TorneoDetalle->moneda = '';
            $TorneoDetalle->valor = $value;
            $TorneoDetalle->valor2 = '';
            $TorneoDetalle->valor3 = '';
            $TorneoDetalle->usucreaId = 0;
            $TorneoDetalle->usumodifId = 0;
            $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
            $TorneoDetalleMysqlDAO->insert($TorneoDetalle);

        }

        foreach ($Games as $key => $value) {
            $TorneoDetalle = new TorneoDetalle();
            $TorneoDetalle->torneoId = $torneoId;
            $TorneoDetalle->tipo = "CONDGAME" . $value->Id;
            $TorneoDetalle->moneda = '';
            $TorneoDetalle->valor = $value->WageringPercent;
            $TorneoDetalle->valor2 = '';
            $TorneoDetalle->valor3 = '';
            $TorneoDetalle->usucreaId = 0;
            $TorneoDetalle->usumodifId = 0;
            $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
            $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
        }

        foreach ($CasinoProduct as $key => $value) {
            $TorneoDetalle = new TorneoDetalle();
            $TorneoDetalle->torneoId = $torneoId;
            $TorneoDetalle->tipo = "CONDGAME" . $value->Id;
            $TorneoDetalle->moneda = '';
            $TorneoDetalle->valor = 100;
            $TorneoDetalle->valor2 = '';
            $TorneoDetalle->valor3 = '';
            $TorneoDetalle->usucreaId = 0;
            $TorneoDetalle->usumodifId = 0;
            $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
            $TorneoDetalleMysqlDAO->insert($TorneoDetalle);

        }
        foreach ($CasinoProvider as $key => $value) {
            $TorneoDetalle = new TorneoDetalle();
            $TorneoDetalle->torneoId = $torneoId;
            $TorneoDetalle->tipo = "CONDSUBPROVIDER" . $value->Id;
            $TorneoDetalle->moneda = '';
            $TorneoDetalle->valor = 100;
            $TorneoDetalle->valor2 = '';
            $TorneoDetalle->valor3 = '';
            $TorneoDetalle->usucreaId = 0;
            $TorneoDetalle->usumodifId = 0;
            $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
            $TorneoDetalleMysqlDAO->insert($TorneoDetalle);

        }
        foreach ($CasinoCategory as $key => $value) {
            $TorneoDetalle = new TorneoDetalle();
            $TorneoDetalle->torneoId = $torneoId;
            $TorneoDetalle->tipo = "CONDCATEGORY" . $value->Id;
            $TorneoDetalle->moneda = '';
            $TorneoDetalle->valor = 100;
            $TorneoDetalle->valor2 = '';
            $TorneoDetalle->valor3 = '';
            $TorneoDetalle->usucreaId = 0;
            $TorneoDetalle->usumodifId = 0;
            $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
            $TorneoDetalleMysqlDAO->insert($TorneoDetalle);

        }

        if ($WinBonusId != 0) {

            $TorneoDetalle = new TorneoDetalle();
            $TorneoDetalle->torneoId = $torneoId;
            $TorneoDetalle->tipo = "WINBONOID";
            $TorneoDetalle->moneda = '';
            $TorneoDetalle->valor = $WinBonusId;
            $TorneoDetalle->valor2 = '';
            $TorneoDetalle->valor3 = '';
            $TorneoDetalle->usucreaId = 0;
            $TorneoDetalle->usumodifId = 0;
            $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
            $TorneoDetalleMysqlDAO->insert($TorneoDetalle);


        } else {

            if (false) {
                $TorneoDetalle = new TorneoDetalle();
                $TorneoDetalle->torneoId = $torneoId;
                $TorneoDetalle->tipo = "TIPOSALDO";
                $TorneoDetalle->moneda = '';
                $TorneoDetalle->valor = $TypeSaldo;
                $TorneoDetalle->valor2 = '';
                $TorneoDetalle->valor3 = '';
                $TorneoDetalle->usucreaId = 0;
                $TorneoDetalle->usumodifId = 0;
                $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
                $TorneoDetalleMysqlDAO->insert($TorneoDetalle);

            }

        }


        /*


                if ($TriggerId != "") {
                    if($CodePromo != ""){

                        $TorneoDetalle = new TorneoDetalle();
                        $TorneoDetalle->torneoId = $torneoId;
                        $TorneoDetalle->tipo = "CODEPROMO";
                        $TorneoDetalle->moneda = '';
                        $TorneoDetalle->valor = $CodePromo;
                        $TorneoDetalle->usucreaId = 0;
                        $TorneoDetalle->usumodifId = 0;
                        $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
                        $TorneoDetalleMysqlDAO->insert($TorneoDetalle);

                    }
                }



                if ($FreeSpinsTotalCount != "" && $Prefix != "") {
                    $jugadoresAsignar = array();
                    $jugadoresAsignarFinal = array();

                    if ($PlayersChosen != "") {
                        $jugadoresAsignar = explode(",", $PlayersChosen);


                        foreach ($jugadoresAsignar as $item) {

                            array_push($jugadoresAsignarFinal, array("Id" => $item, "Valor" => $AutomaticForfeitureLevel));

                        }

                    }


                    $codigosarray = array();

                    for ($i = 1; $i <= $FreeSpinsTotalCount; $i++) {
                        $codigo = GenerarClaveTicket(4);

                        while (in_array($codigo, $codigosarray)) {
                            $codigo = GenerarClaveTicket(4);
                        }


                        $usuarioId = '0';
                        $valor = $AutomaticForfeitureLevel;

                        $valor_bono = $AutomaticForfeitureLevel;

                        $valor_base = $AutomaticForfeitureLevel;

                        $estado = 'L';

                        $errorId = '0';

                        $idExterno = '0';

                        $mandante = '0';


                        $usucreaId = '0';

                        $usumodifId = '0';


                        $apostado = '0';
                        $rollowerRequerido = '0';
                        $codigo = $Prefix . $codigo;


                        $UsuarioBono = new UsuarioBono();

                        $UsuarioBono->setUsuarioId($usuarioId);
                        $UsuarioBono->setBonoId($torneoId);
                        $UsuarioBono->setValor($valor);
                        $UsuarioBono->setValorBono($valor_bono);
                        $UsuarioBono->setValorBase($valor_base);
                        $UsuarioBono->setEstado($estado);
                        $UsuarioBono->setErrorId($errorId);
                        $UsuarioBono->setIdExterno($idExterno);
                        $UsuarioBono->setMandante($mandante);
                        $UsuarioBono->setUsucreaId($usucreaId);
                        $UsuarioBono->setUsumodifId($usumodifId);
                        $UsuarioBono->setApostado($apostado);
                        $UsuarioBono->setRollowerRequerido($rollowerRequerido);
                        $UsuarioBono->setCodigo($codigo);

                        $UsuarioBonoMysqlDAO= new UsuarioBonoMySqlDAO($transaccion);

                        $UsuarioBonoMysqlDAO->insert($UsuarioBono);

                        if ($jugadoresAsignarFinal[$i]["Id"] != "") {
                            $jugadoresAsignarFinal[$i]["Code"] = $codigo;
                        }

                        array_push($codigosarray, $codigo);

                    }
                }

                if ($MaxplayersCount != "" && $Prefix != "") {

                    $jugadoresAsignar = array();
                    $jugadoresAsignarFinal = array();

                    foreach ($MinimumAmount as $key => $value) {

                        $jugadoresAsignar = explode(",", $value->Amount);

                        foreach ($MaxPayout as $key2 => $value2) {

                            if ($value->CurrencyId == $value2->CurrencyId) {

                                foreach ($jugadoresAsignar as $item) {

                                    if($item != 0){

                                        array_push($jugadoresAsignarFinal, array("Id" => $item, "Valor" => $value2->Amount));

                                    }

                                }

                            }
                        }

                    }

                    $codigosarray = array();

                    for ($i = 0; $i < $MaxplayersCount; $i++) {
                        $codigo = GenerarClaveTicket(4);

                        while (in_array($codigo, $codigosarray)) {
                            $codigo = GenerarClaveTicket(4);
                        }


                        $usuarioId = '0';
                        $valor = '0';

                        $valor_bono = '0';

                        $valor_base = '0';
                        $estado = 'L';

                        $errorId = '0';

                        $idExterno = '0';

                        $mandante = '0';


                        $usucreaId = '0';

                        $usumodifId = '0';


                        $apostado = '0';
                        $rollowerRequerido = '0';
                        $codigo = $Prefix . $codigo;

                        $UsuarioBono = new UsuarioBono();

                        $UsuarioBono->setUsuarioId($usuarioId);
                        $UsuarioBono->setBonoId($torneoId);
                        $UsuarioBono->setValor($valor);
                        $UsuarioBono->setValorBono($valor_bono);
                        $UsuarioBono->setValorBase($valor_base);
                        $UsuarioBono->setEstado($estado);
                        $UsuarioBono->setErrorId($errorId);
                        $UsuarioBono->setIdExterno($idExterno);
                        $UsuarioBono->setMandante($mandante);
                        $UsuarioBono->setUsucreaId($usucreaId);
                        $UsuarioBono->setUsumodifId($usumodifId);
                        $UsuarioBono->setApostado($apostado);
                        $UsuarioBono->setRollowerRequerido($rollowerRequerido);
                        $UsuarioBono->setCodigo($codigo);

                        $UsuarioBonoMysqlDAO= new UsuarioBonoMySqlDAO($transaccion);

                        $inse=$UsuarioBonoMysqlDAO->insert($UsuarioBono);


                        if ($jugadoresAsignarFinal[$i]["Id"] != "") {

                            $jugadoresAsignarFinal[$i]["Code"] = $codigo;
                        }

                        array_push($codigosarray, $codigo);

                    }
                    for ($i = 0; $i < oldCount($jugadoresAsignarFinal); $i++) {

                        if ($jugadoresAsignarFinal[$i]["Id"] != "") {
                            $BonoInterno = new BonoInterno();

                            $usuarioSql = "select ciudad.ciudad_id,ciudad.depto_id,pais.pais_id FROM registro
          INNER JOIN ciudad ON (ciudad.ciudad_id = registro.ciudad_id)
          INNER JOIN departamento ON (ciudad.depto_id = departamento.depto_id)
          INNER JOIN pais ON (pais.pais_id = departamento.pais_id) WHERE registro.usuario_id='" . $jugadoresAsignarFinal[$i]["Id"] . "'";

                            $Usuario = $BonoInterno->execQuery($transaccion,$usuarioSql);


                            $dataUsuario = $Usuario;
                            $detalles = array(
                                "PaisUSER" => $dataUsuario[0]->pais_id,
                                "DepartamentoUSER" => $dataUsuario[0]->depto_id,
                                "CiudadUSER" => $dataUsuario[0]->ciudad_id,

                            );
                            $detalles = json_decode(json_encode($detalles));


                            $respuesta = $BonoInterno->agregarBonoFree($torneoId, $jugadoresAsignarFinal[$i]["Id"], "0", $detalles, true, $jugadoresAsignarFinal[$i]["Code"], $transaccion);

                        }

                    }
                }



                if ($FreeSpinsTotalCount != "" && $Prefix != "") {

                    for ($i = 1; $i <= oldCount($jugadoresAsignarFinal); $i++) {


                        $BonoInterno = new BonoInterno();

                        $usuarioSql = "select ciudad.ciudad_id,ciudad.depto_id,pais.pais_id FROM registro
          INNER JOIN ciudad ON (ciudad.ciudad_id = registro.ciudad_id)
          INNER JOIN departamento ON (ciudad.depto_id = departamento.depto_id)
          INNER JOIN pais ON (pais.pais_id = departamento.pais_id) WHERE registro.usuario_id='" . $jugadoresAsignarFinal[$i]["Id"] . "'";

                        $Usuario = $BonoInterno->execQuery($transaccion,$usuarioSql);


                        $dataUsuario = $Usuario;
                        $detalles = array(
                            "PaisUSER" => $dataUsuario[0]->pais_id,
                            "DepartamentoUSER" => $dataUsuario[0]->depto_id,
                            "CiudadUSER" => $dataUsuario[0]->ciudad_id,

                        );
                        $detalles = json_decode(json_encode($detalles));

                        $respuesta = $BonoInterno->agregarBonoFree($torneoId, $jugadoresAsignarFinal[$i]["Id"], "0", $detalles, true, $jugadoresAsignarFinal[$i]["Code"], $transaccion);


                    }
                }
                if ($MaxplayersCount != "" && $Prefix != "") {

                    for ($i = 0; $i < oldCount($jugadoresAsignarFinal); $i++) {

                        if ($jugadoresAsignarFinal[$i]["Id"] != "") {
                            $BonoInterno = new BonoInterno();

                            $usuarioSql = "select ciudad.ciudad_id,ciudad.depto_id,pais.pais_id FROM registro
          INNER JOIN ciudad ON (ciudad.ciudad_id = registro.ciudad_id)
          INNER JOIN departamento ON (ciudad.depto_id = departamento.depto_id)
          INNER JOIN pais ON (pais.pais_id = departamento.pais_id) WHERE registro.usuario_id='" . $jugadoresAsignarFinal[$i]["Id"] . "'";

                            $Usuario = $BonoInterno->execQuery($transaccion,$usuarioSql);


                            $dataUsuario = $Usuario;
                            $detalles = array(
                                "PaisUSER" => $dataUsuario[0]->pais_id,
                                "DepartamentoUSER" => $dataUsuario[0]->depto_id,
                                "CiudadUSER" => $dataUsuario[0]->ciudad_id,

                            );
                            $detalles = json_decode(json_encode($detalles));


                            $respuesta = $BonoInterno->agregarBonoFree($torneoId, $jugadoresAsignarFinal[$i]["Id"], "0", $detalles, true, $jugadoresAsignarFinal[$i]["Code"], $transaccion);

                        }

                    }
                }
                */

        $transaccion->commit();


        //$transaccion->commit();

        $response["HasError"] = false;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];
        $response["Result"] = array();

        break;


    /**
     * CreateBonus
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
    case "CreateBonus":
        $mandanteUsuario = 0;
        // Si el usuario esta condicionado por el mandante y no es de Global
        if ($_SESSION['Global'] == "N") {
            $mandanteUsuario = $_SESSION['mandante'];
        }


        $Description = $params->Description; //Descripcion del bono
        $Name = $params->Name; //Nombre del bono
        $StartDate = $params->BeginDate; //Fecha Inicial de la campaña
        $EndDate = $params->EndDate; //Fecha Final de la campaña

        $PartnerBonus = $params->PartnerBonus;

        $ExpirationDate = $PartnerBonus->ExpirationDate; //Fecha de expiracion del bono
        $ExpirationDays = $PartnerBonus->ExpirationDays; // Dias de expiracion del bono


        if ($ExpirationDate == "" && $ExpirationDays == "") {
            $ExpirationDate = $params->ExpirationDate; //Fecha de expiracion del bono
            $ExpirationDays = $params->ExpirationDays; // Dias de expiracion del bono

        }

        $LiveOrPreMatch = $params->LiveOrPreMatch;
        $MinSelCount = $params->MinSelCount;
        $MinSelPrice = $params->MinSelPrice;


        $CurrentCost = $params->CurrentCost;

        $DepositDefinition = $params->DepositDefinition;

        $BonusDefinition = $DepositDefinition->BonusDefinition;
        $BonusDefinitionId = $DepositDefinition->BonusDefinitionId;
        $BonusPercent = $DepositDefinition->BonusPercent; // Porcentaje del bono
        $BonusWFactor = $DepositDefinition->BonusWFactor; //Rollower bono
        $DepositNumber = $DepositDefinition->DepositNumber;
        $DepositWFactor = $DepositDefinition->DepositWFactor; //Rollower Deposito

        $SuppressWithdrawal = $DepositDefinition->SuppressWithdrawal; //Suprimir la retirada
        $UseFrozeWallet = $DepositDefinition->UseFrozeWallet;

        $IsVisibleForAllplayers = $params->IsVisibleForAllplayers; //Es para todos los usuarios
        $OpenToAllPlayers = $params->OpenToAllPlayers;//Es para todos los usuarios
        $MaxplayersCount = $params->MaxplayersCount; // Maximo jugadores que lo pueden obtener
        $Prefix = $params->Prefix;

        $PlayersChosen = $params->PlayersChosen;

        $ForeignRule = $params->ForeignRule;
        $ForeignRuleInfo = $ForeignRule->Info;


        if (!is_object($ForeignRuleInfo)) {
            $ForeignRuleJSON = json_decode($ForeignRuleInfo);

        } else {
            $ForeignRuleJSON = $ForeignRuleInfo;
        }


        $LiveOrPreMatch = $ForeignRuleJSON->LiveOrPreMatch;
        $MinSelCount = $ForeignRuleJSON->MinSelCount; // Minimo cantidad de selecciones
        $MinSelPrice = $ForeignRuleJSON->MinSelPrice; // Minimo cuota seleccionada
        $MinSelPriceTotal = $ForeignRuleJSON->MinSelPriceTotal;

        $TriggerId = $params->TriggerId;
        $CodePromo = $params->CodePromo;


        $MinBetPrice = $ForeignRuleJSON->MinBetPrice; // Minimo apuesta cuota total
        $SportBonusRules = $ForeignRuleJSON->SportBonusRules;

        $ProductTypeId = $params->ProductTypeId;

        $TriggerId = $params->TriggerId;

        if ($CodePromo != "") {
            $TriggerId = 1;
        }

        $TypeId = $params->TypeId;

        $Games = $params->Games;

        $condiciones = [];


        $MaxPayout = $params->MaxPayout; //Pago Maximo
        $MaximumBonusAmount = $params->MaximumBonusAmount; //Maximo valordel bono
        $MaximumDeposit = $params->MaximumDeposit;
        $MinimumDeposit = $params->MinimumDeposit;
        $MinimumAmount = $params->MinimumAmount;
        $MaximumAmount = $params->MaximumAmount;
        $MoneyRequirement = $params->MoneyRequirement;
        $MoneyRequirementAmount = $params->MoneyRequirementAmount;


        $Schedule = $params->Schedule; //Programar bono
        $ScheduleCount = $Schedule->Count; //
        $ScheduleName = $Schedule->Name; //Descripcion de la programacion
        $SchedulePeriod = $Schedule->Period;
        $SchedulePeriodType = $Schedule->PeriodType;

        $TriggerDetails = $params->TriggerDetails;
        $Count = $TriggerDetails->Count; //Cantidad de depositos

        //$AreAllowed = $TriggerDetails->AreAllowed; //Son Permitidos los paises

        $IsFromCashDesk = $TriggerDetails->IsFromCashDesk;
        $PaymentSystemId = $TriggerDetails->PaymentSystemId;
        $PaymentSystemIds = $TriggerDetails->PaymentSystemIds;

        $Regions = $TriggerDetails->Regions;
        $Departments = $TriggerDetails->Departments;
        $Cities = $TriggerDetails->Cities;
        $CashDesks = $TriggerDetails->CashDesks;

        $RegionsUser = $TriggerDetails->RegionsUser;
        $DepartmentsUser = $TriggerDetails->DepartmentsUser;
        $CitiesUser = $TriggerDetails->CitiesUser;
        $UserRepeatBonus = $params->UserRepeatBonus;

        $BalanceZero = $TriggerDetails->BalanceZero;

        $WinBonusId = $params->WinBonusId;
        $TypeSaldo = $params->TypeSaldo;
        $Priority = $params->Priority;

        if ($Priority == "" || !is_numeric($Priority)) {
            $Priority = 0;
        }


        $ConditionProduct = $TriggerDetails->ConditionProduct;
        if ($ConditionProduct != "OR" && $ConditionProduct != "AND") {
            $ConditionProduct = 'NA';
        }

        $FreeSpinDefinition = $params->FreeSpinDefinition;
        $AutomaticForfeitureLevel = $FreeSpinDefinition->AutomaticForfeitureLevel;
        $BonusMoneyExpirationDate = $FreeSpinDefinition->BonusMoneyExpirationDate;
        $BonusMoneyExpirationDays = $FreeSpinDefinition->BonusMoneyExpirationDays;
        $FreeSpinsTotalCount = $FreeSpinDefinition->FreeSpinsTotalCount;
        $WageringFactor = $FreeSpinDefinition->WageringFactor;
        $PlayersChosen = $params->PlayersChosen;
        $Casino = $params->Casino->Info;
        $CasinoCategory = $Casino->Category;
        $CasinoProvider = $Casino->Provider;
        $CasinoProduct = $Casino->Product;

        $BonusDetails = $params->BonusDetails;

        $RulesText = str_replace("'", "\'", $params->RulesText);

        $TypeBonusDeposit = $params->TypeBonusDeposit;

        $Type = ($params->Type == '1') ? '1' : '0';

        $tipobono = $TypeId;
        $cupo = 0;
        $cupoMaximo = 0;
        $jugadores = 0;
        $jugadoresMaximo = 0;

        if ($MaximumAmount != "" && $tipobono == 2) {
            $cupoMaximo = $MaximumAmount[0]->Amount;
        }

        if ($MaxplayersCount != "" && $tipobono == 2) {
            $jugadoresMaximo = $MaxplayersCount;
        }

        if ($cupoMaximo == "") {
            $cupoMaximo = 0;
        }

        $BonoInterno = new BonoInterno();
        $BonoInterno->nombre = $Name;
        $BonoInterno->descripcion = $Description;
        $BonoInterno->fechaInicio = $StartDate;
        $BonoInterno->fechaFin = $EndDate;
        $BonoInterno->tipo = $tipobono;
        $BonoInterno->estado = 'A';
        $BonoInterno->usucreaId = 0;
        $BonoInterno->usumodifId = 0;
        $BonoInterno->mandante = $mandanteUsuario;
        $BonoInterno->condicional = $ConditionProduct;
        $BonoInterno->orden = $Priority;
        $BonoInterno->cupoActual = $cupo;
        $BonoInterno->cupoMaximo = $cupoMaximo;
        $BonoInterno->cantidadBonos = $jugadores;
        $BonoInterno->maximoBonos = $jugadoresMaximo;


        $BonoInterno->reglas = $RulesText;

        if ($Type == '1') {
            $BonoInterno->publico = 'I';
        } else {
            $BonoInterno->publico = 'A';
        }

        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $transaccion = $BonoDetalleMySqlDAO->getTransaction();

        $bonoId = $BonoInterno->insert($transaccion);

        /*
        if($MaxplayersCount != "" && $Prefix != ""){

            $codigosarray=array();

            for ($i = 1; $i <= $MaxplayersCount; $i++) {
                $codigo = GenerarClaveTicket(4);

                while(in_array($codigo,$codigosarray)){
                    $codigo = GenerarClaveTicket(4);
                }



                $PromocionalLog = new PromocionalLog();

                $PromocionalLog->usuarioId= '0' ;

                $PromocionalLog->promocionalId=$bonoId ;

                $PromocionalLog->valor= '';

                $PromocionalLog->valorPromocional= '';

                $PromocionalLog->valorBase= '';

                $PromocionalLog->estado= 'L';

                $PromocionalLog->errorId= '';

                $PromocionalLog->idExterno= '';

                $PromocionalLog->mandante= '0';

                $PromocionalLog->version= '2';

                $PromocionalLog->usucreaId= '0';

                $PromocionalLog->usumodifId= '0';


                $PromocionalLog->apostado= '0';
                $PromocionalLog->rollowerRequerido= '0';
                $PromocionalLog->codigo= $Prefix . $codigo;

                $PromocionalLogMySqlDAO= new PromocionalLogMySqlDAO();
                $PromocionalLogMySqlDAO->insert($PromocionalLog);
                $PromocionalLogMySqlDAO->getTransaction()->commit();

                array_push($codigosarray,$codigo);

            }
        }

*/

        //Expiracion

        if ($ExpirationDays != "") {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "EXPDIA";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $ExpirationDays;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        if ($UserRepeatBonus != "") {

            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "REPETIRBONO";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $UserRepeatBonus;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);


        }

        if ($UseFrozeWallet != "") {

            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "FROZEWALLET";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $UseFrozeWallet;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        if ($SuppressWithdrawal != "") {


            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "SUPPRESSWITHDRAWAL";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $SuppressWithdrawal;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        if ($ScheduleCount != "") {


            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "SCHEDULECOUNT";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $ScheduleCount;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        if ($ScheduleName != "") {


            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "SCHEDULENAME";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $ScheduleName;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        if ($SchedulePeriod != "") {


            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "SCHEDULEPERIOD";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $SchedulePeriod;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        if ($SchedulePeriodType != "") {


            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "SCHEDULEPERIODTYPE";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $SchedulePeriodType;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        if ($ProductTypeId !== "") {

            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "TIPOPRODUCTO";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $ProductTypeId;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        if ($Count != "") {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "CANTDEPOSITOS";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $Count;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);

        }

        if ($AreAllowed != "") {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "PAISESPERMITIDOS";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $AreAllowed;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        if ($ExpirationDate != "") {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "EXPFECHA";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $ExpirationDate;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        if ($TypeBonusDeposit == '1') {


            if ($BonusPercent != "") {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "PORCENTAJE";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $BonusPercent;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);
            }
        }

        if ($BonusWFactor != "") {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "WFACTORBONO";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $BonusWFactor;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        if ($DepositWFactor != "") {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "WFACTORDEPOSITO";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $DepositWFactor;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }


        if ($DepositNumber != "") {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "NUMERODEPOSITO";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $DepositNumber;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        if ($IsFromCashDesk) {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "CONDEFECTIVO";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = 'true';
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        if ($MaxplayersCount != "") {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "MAXJUGADORES";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $MaxplayersCount;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }


        if ($WinBonusId != 0) {

            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "WINBONOID";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $WinBonusId;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);


        } else {

            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "TIPOSALDO";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $TypeSaldo;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);

        }


        if ($WinBonusId == "" || $WinBonusId == "0") {

            foreach ($MaxPayout as $key => $value) {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "MAXPAGO";
                $BonoDetalle->moneda = $value->CurrencyId;
                $BonoDetalle->valor = $value->Amount;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);
            }


        } else {

            foreach ($MaxPayout as $key => $value) {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "VALORROLLOWER";
                $BonoDetalle->moneda = $value->CurrencyId;
                $BonoDetalle->valor = $value->Amount;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);
            }

        }


        if ($tipobono == "2") {

            foreach ($MaximumDeposit as $key => $value) {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "MAXDEPOSITO";
                $BonoDetalle->moneda = $value->CurrencyId;
                $BonoDetalle->valor = $value->Amount;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);
            }

            foreach ($MinimumDeposit as $key => $value) {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "MINDEPOSITO";
                $BonoDetalle->moneda = $value->CurrencyId;
                $BonoDetalle->valor = $value->Amount;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);
            }
        }

        if ($TypeBonusDeposit == '0') {

            foreach ($MoneyRequirement as $key => $value) {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "VALORBONO";
                $BonoDetalle->moneda = $value->CurrencyId;
                $BonoDetalle->valor = $value->Amount;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);
            }
        }


        foreach ($PaymentSystemIds as $key => $value) {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "CONDPAYMENT";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $value;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }


        foreach ($Regions as $key => $value) {

            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "CONDPAISPV";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $value;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);

        }

        foreach ($Departments as $key => $value) {

            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "CONDDEPARTAMENTOPV";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $value;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);

        }

        foreach ($Cities as $key => $value) {

            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "CONDCIUDADPV";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $value;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);

        }

        if ($BalanceZero == "true" || $BalanceZero == 1 || $BalanceZero == true) {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "CONDBALANCE";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = '0';
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }


        foreach ($RegionsUser as $key => $value) {

            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "CONDPAISUSER";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $value;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);

        }

        foreach ($DepartmentsUser as $key => $value) {

            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "CONDDEPARTAMENTOUSER";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $value;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);


        }

        foreach ($CitiesUser as $key => $value) {

            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "CONDCIUDADUSER";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $value;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);

        }

        foreach ($CashDesks as $key => $value) {

            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "CONDPUNTOVENTA";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $value;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);

        }

        foreach ($Games as $key => $value) {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "CONDGAME" . $value->Id;
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $value->WageringPercent;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        foreach ($CasinoCategory as $key => $value) {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "CONDCATEGORY" . $value->Id;
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $value->Percentage;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);

        }

        foreach ($CasinoProvider as $key => $value) {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "CONDSUBPROVIDER" . $value->Id;
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $value->Percentage;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);

        }

        foreach ($CasinoProduct as $key => $value) {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "CONDGAME" . $value->Id;
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $value->Percentage;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);

        }

        foreach ($SportBonusRules as $key => $value) {

            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "ITAINMENT" . $value->ObjectTypeId;
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $value->ObjectId;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        if ($LiveOrPreMatch != "") {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "LIVEORPREMATCH";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $LiveOrPreMatch;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        if ($MinSelCount != "") {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "MINSELCOUNT";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $MinSelCount;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        if ($MinSelPrice != "") {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "MINSELPRICE";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $MinSelPrice;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        if ($MinSelPriceTotal != "") {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "MINSELPRICETOTAL";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $MinSelPriceTotal;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);

        }


        foreach ($BonusDetails as $key => $value) {
            if ($value->MinAmount != "") {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "MINAMOUNT";
                $BonoDetalle->moneda = $value->CurrencyId;
                $BonoDetalle->valor = $value->MinAmount;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);


            }
            if ($value->MaxAmount != "") {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "MAXAMOUNT";
                $BonoDetalle->moneda = $value->CurrencyId;
                $BonoDetalle->valor = $value->MaxAmount;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);

            }
            if ($value->Amount != "") {
                if ($value->Amount->MinAmount != "") {
                    $BonoDetalle = new BonoDetalle();
                    $BonoDetalle->bonoId = $bonoId;
                    $BonoDetalle->tipo = "MINAMOUNT";
                    $BonoDetalle->moneda = $value->CurrencyId;
                    $BonoDetalle->valor = $value->Amount->MinAmount;
                    $BonoDetalle->usucreaId = 0;
                    $BonoDetalle->usumodifId = 0;
                    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                    $BonoDetalleMysqlDAO->insert($BonoDetalle);


                }
                if ($value->Amount->MaxAmount != "") {
                    $BonoDetalle = new BonoDetalle();
                    $BonoDetalle->bonoId = $bonoId;
                    $BonoDetalle->tipo = "MAXAMOUNT";
                    $BonoDetalle->moneda = $value->CurrencyId;
                    $BonoDetalle->valor = $value->Amount->MaxAmount;
                    $BonoDetalle->usucreaId = 0;
                    $BonoDetalle->usumodifId = 0;
                    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                    $BonoDetalleMysqlDAO->insert($BonoDetalle);

                }
            }
        }


        if ($TriggerId != "") {
            if ($CodePromo != "") {

                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "CODEPROMO";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $CodePromo;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);

            }
        }


        if ($MinBetPrice != "" && false) {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "MINBETPRICE";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $MinBetPrice;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        if ($FreeSpinsTotalCount != "" && $Prefix != "") {
            $jugadoresAsignar = array();
            $jugadoresAsignarFinal = array();

            if ($PlayersChosen != "") {
                $jugadoresAsignar = explode(",", $PlayersChosen);


                foreach ($jugadoresAsignar as $item) {

                    array_push($jugadoresAsignarFinal, array("Id" => $item, "Valor" => $AutomaticForfeitureLevel));

                }

            }


            $codigosarray = array();

            for ($i = 1; $i <= $FreeSpinsTotalCount; $i++) {
                $codigo = GenerarClaveTicket(4);

                while (in_array($codigo, $codigosarray)) {
                    $codigo = GenerarClaveTicket(4);
                }


                $usuarioId = '0';
                $valor = $AutomaticForfeitureLevel;

                $valor_bono = $AutomaticForfeitureLevel;

                $valor_base = $AutomaticForfeitureLevel;

                $estado = 'L';

                $errorId = '0';

                $idExterno = '0';

                $mandante = '0';


                $usucreaId = '0';

                $usumodifId = '0';


                $apostado = '0';
                $rollowerRequerido = '0';
                $codigo = $Prefix . $codigo;


                $UsuarioBono = new UsuarioBono();

                $UsuarioBono->setUsuarioId($usuarioId);
                $UsuarioBono->setBonoId($bonoId);
                $UsuarioBono->setValor($valor);
                $UsuarioBono->setValorBono($valor_bono);
                $UsuarioBono->setValorBase($valor_base);
                $UsuarioBono->setEstado($estado);
                $UsuarioBono->setErrorId($errorId);
                $UsuarioBono->setIdExterno($idExterno);
                $UsuarioBono->setMandante($mandante);
                $UsuarioBono->setUsucreaId($usucreaId);
                $UsuarioBono->setUsumodifId($usumodifId);
                $UsuarioBono->setApostado($apostado);
                $UsuarioBono->setRollowerRequerido($rollowerRequerido);
                $UsuarioBono->setCodigo($codigo);

                $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($transaccion);

                $UsuarioBonoMysqlDAO->insert($UsuarioBono);

                if ($jugadoresAsignarFinal[$i]["Id"] != "") {
                    $jugadoresAsignarFinal[$i]["Code"] = $codigo;
                }

                array_push($codigosarray, $codigo);

            }
        }

        $darAUsuariosEspecificos = false;
        if (($MaxplayersCount != "" && $Prefix != "") || ($MaxplayersCount != "" && ($MinimumAmount) != '')) {
            $darAUsuariosEspecificos = true;
        }

        if ($darAUsuariosEspecificos) {

            $jugadoresAsignar = array();
            $jugadoresAsignarFinal = array();

            foreach ($MinimumAmount as $key => $value) {

                $jugadoresAsignar = explode(",", $value->Amount);

                foreach ($MaxPayout as $key2 => $value2) {

                    if ($value->CurrencyId == $value2->CurrencyId) {

                        foreach ($jugadoresAsignar as $item) {

                            if ($item != 0) {

                                array_push($jugadoresAsignarFinal, array("Id" => $item, "Valor" => $value2->Amount));

                            }

                        }

                    }
                }

            }

            $codigosarray = array();


            for ($i = 0; $i < $MaxplayersCount; $i++) {
                $codigo = GenerarClaveTicket(4);

                while (in_array($codigo, $codigosarray)) {
                    $codigo = GenerarClaveTicket(4);
                }


                $usuarioId = '0';
                $estado = 'L';

                /*if ($tipobono != 2) {
                    if ($jugadoresAsignarFinal[$i] != "" && $jugadoresAsignarFinal[$i]["Id"] != null) {
                        $usuarioId = $jugadoresAsignarFinal[$i]["Id"];
                        $estado = 'A';

                    }

                }*/

                if ($tipobono == "2") {
                    if ($jugadoresAsignarFinal[$i] != "" && $jugadoresAsignarFinal[$i]["Id"] != null) {
                        $usuarioId = $jugadoresAsignarFinal[$i]["Id"];
                        $estado = 'P';

                    }
                }

                $valor = '0';

                $valor_bono = '0';

                $valor_base = '0';

                $errorId = '0';

                $idExterno = '0';

                $mandante = '0';


                $usucreaId = '0';

                $usumodifId = '0';


                $apostado = '0';
                $rollowerRequerido = '0';
                $codigo = $Prefix . $codigo;

                $UsuarioBono = new UsuarioBono();

                $UsuarioBono->setUsuarioId($usuarioId);
                $UsuarioBono->setBonoId($bonoId);
                $UsuarioBono->setValor($valor);
                $UsuarioBono->setValorBono($valor_bono);
                $UsuarioBono->setValorBase($valor_base);
                $UsuarioBono->setEstado($estado);
                $UsuarioBono->setErrorId($errorId);
                $UsuarioBono->setIdExterno($idExterno);
                $UsuarioBono->setMandante($mandante);
                $UsuarioBono->setUsucreaId($usucreaId);
                $UsuarioBono->setUsumodifId($usumodifId);
                $UsuarioBono->setApostado($apostado);
                $UsuarioBono->setRollowerRequerido($rollowerRequerido);
                $UsuarioBono->setCodigo($codigo);
                $UsuarioBono->setVersion(0);
                $UsuarioBono->setExternoId(0);

                $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($transaccion);

                $inse = $UsuarioBonoMysqlDAO->insert($UsuarioBono);


                if ($jugadoresAsignarFinal[$i]["Id"] != "") {

                    $jugadoresAsignarFinal[$i]["Code"] = $codigo;
                }

                array_push($codigosarray, $codigo);

            }
        }

        $transaccion->commit();


        if ($darAUsuariosEspecificos) {


            if ($tipobono != 2) {

                for ($i = 0; $i < oldCount($jugadoresAsignarFinal); $i++) {

                    if ($jugadoresAsignarFinal[$i]["Id"] != "") {
                        $BonoInterno = new BonoInterno();

                        $usuarioSql = "select ciudad.ciudad_id,ciudad.depto_id,pais.pais_id,usuario.moneda,usuario.mandante FROM registro
  INNER JOIN usuario ON (usuario.usuario_id = registro.usuario_id)
  INNER JOIN ciudad ON (ciudad.ciudad_id = registro.ciudad_id)
  INNER JOIN departamento ON (ciudad.depto_id = departamento.depto_id)
  INNER JOIN pais ON (pais.pais_id = departamento.pais_id) WHERE registro.usuario_id='" . $jugadoresAsignarFinal[$i]["Id"] . "'";

                        $Usuario = $BonoInterno->execQuery($transaccion, $usuarioSql);


                        $dataUsuario = $Usuario;
                        if ($dataUsuario[0]->{'usuario.mandante'} != "") {
                            $detalles = array(
                                "PaisUSER" => $dataUsuario[0]->{'pais.pais_id'},
                                "DepartamentoUSER" => $dataUsuario[0]->{'ciudad.depto_id'},
                                "CiudadUSER" => $dataUsuario[0]->{'ciudad.ciudad_id'},
                                "MonedaUSER" => $dataUsuario[0]->{'usuario.moneda'},
                                "ValorDeposito" => 0

                            );
                            $detalles = json_decode(json_encode($detalles));

                            $respuesta = $BonoInterno->agregarBonoFree($bonoId, $jugadoresAsignarFinal[$i]["Id"], $dataUsuario[0]->{'usuario.mandante'}, $detalles, true, $jugadoresAsignarFinal[$i]["Code"], $transaccion);

                        }

                    }

                }
            }
        }

        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $transaccion = $BonoDetalleMySqlDAO->getTransaction();


        if ($FreeSpinsTotalCount != "" && $Prefix != "") {

            for ($i = 1; $i <= oldCount($jugadoresAsignarFinal); $i++) {


                $BonoInterno = new BonoInterno();

                $usuarioSql = "select ciudad.ciudad_id,ciudad.depto_id,pais.pais_id,usuario.mandante FROM registro
  INNER JOIN ciudad ON (ciudad.ciudad_id = registro.ciudad_id)
  INNER JOIN departamento ON (ciudad.depto_id = departamento.depto_id)
  INNER JOIN pais ON (pais.pais_id = departamento.pais_id) WHERE registro.usuario_id='" . $jugadoresAsignarFinal[$i]["Id"] . "'";

                $Usuario = $BonoInterno->execQuery($transaccion, $usuarioSql);


                $dataUsuario = $Usuario;
                $detalles = array(
                    "PaisUSER" => $dataUsuario[0]->pais_id,
                    "DepartamentoUSER" => $dataUsuario[0]->depto_id,
                    "CiudadUSER" => $dataUsuario[0]->ciudad_id,

                );
                $detalles = json_decode(json_encode($detalles));


                $respuesta = $BonoInterno->agregarBonoFree($bonoId, $jugadoresAsignarFinal[$i]["Id"], $dataUsuario[0]->{'usuario.mandante'}, $detalles, true, $jugadoresAsignarFinal[$i]["Code"], $transaccion);


            }
        }
        if ($MaxplayersCount != "" && $Prefix != "") {
            if ($tipobono == 2) {

                for ($i = 0; $i < oldCount($jugadoresAsignarFinal); $i++) {

                    if ($jugadoresAsignarFinal[$i]["Id"] != "") {
                        $BonoInterno = new BonoInterno();

                        $usuarioSql = "select ciudad.ciudad_id,ciudad.depto_id,pais.pais_id,usuario.mandante FROM registro
  INNER JOIN ciudad ON (ciudad.ciudad_id = registro.ciudad_id)
  INNER JOIN departamento ON (ciudad.depto_id = departamento.depto_id)
  INNER JOIN pais ON (pais.pais_id = departamento.pais_id) WHERE registro.usuario_id='" . $jugadoresAsignarFinal[$i]["Id"] . "'";

                        $Usuario = $BonoInterno->execQuery($transaccion, $usuarioSql);


                        $dataUsuario = $Usuario;
                        $detalles = array(
                            "PaisUSER" => $dataUsuario[0]->pais_id,
                            "DepartamentoUSER" => $dataUsuario[0]->depto_id,
                            "CiudadUSER" => $dataUsuario[0]->ciudad_id,

                        );
                        $detalles = json_decode(json_encode($detalles));


                        $respuesta = $BonoInterno->agregarBonoFree($bonoId, $jugadoresAsignarFinal[$i]["Id"], $dataUsuario[0]->{'usuario.mandante'}, $detalles, true, $jugadoresAsignarFinal[$i]["Code"], $transaccion);

                    }

                }
            }
        }
        //$transaccion->commit();

        $response["HasError"] = false;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];
        $response["Result"] = array();

        break;

    /**
     * UpdateBonus
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
    case "UpdateBonus":


        $mandanteUsuario = 0;
        // Si el usuario esta condicionado por el mandante y no es de Global
        if ($_SESSION['Global'] == "N") {
            $mandanteUsuario = $_SESSION['mandante'];
        }

        $bonoId = $params->Id;

        $RulesText = str_replace("'", "\'", $params->RulesText);
        $Type = ($params->Type == '1') ? '1' : '0';

        $Description = $params->Description; //Descripcion del bono
        $Name = $params->Name; //Nombre del bono
        $StartDate = $params->BeginDate; //Fecha Inicial de la campaña
        $EndDate = $params->EndDate; //Fecha Final de la campaña

        $PartnerBonus = $params->PartnerBonus;

        $ExpirationDate = $PartnerBonus->ExpirationDate; //Fecha de expiracion del bono
        $ExpirationDays = $PartnerBonus->ExpirationDays; // Dias de expiracion del bono


        if ($PartnerBonus == "") {
            $ExpirationDate = $params->ExpirationDate; //Fecha de expiracion del bono
            $ExpirationDays = $params->ExpirationDays; // Dias de expiracion del bono

        }

        $LiveOrPreMatch = $params->LiveOrPreMatch;
        $MinSelCount = $params->MinSelCount;
        $MinSelPrice = $params->MinSelPrice;


        $CurrentCost = $params->CurrentCost;

        $DepositDefinition = $params->DepositDefinition;

        $BonusDefinition = $DepositDefinition->BonusDefinition;
        $BonusDefinitionId = $DepositDefinition->BonusDefinitionId;
        $BonusPercent = $DepositDefinition->BonusPercent; // Porcentaje del bono
        $BonusWFactor = $DepositDefinition->BonusWFactor; //Rollower bono
        $DepositNumber = $DepositDefinition->DepositNumber;
        $DepositWFactor = $DepositDefinition->DepositWFactor; //Rollower Deposito

        $SuppressWithdrawal = $DepositDefinition->SuppressWithdrawal; //Suprimir la retirada
        $UseFrozeWallet = $DepositDefinition->UseFrozeWallet;

        $IsVisibleForAllplayers = $params->IsVisibleForAllplayers; //Es para todos los usuarios
        $OpenToAllPlayers = $params->OpenToAllPlayers;//Es para todos los usuarios
        $MaxplayersCount = $params->MaxplayersCount; // Maximo jugadores que lo pueden obtener

        $ForeignRule = $params->ForeignRule;
        $ForeignRuleInfo = $ForeignRule->Info;

        $ForeignRuleJSON = json_decode($ForeignRuleInfo);
        $LiveOrPreMatch = $ForeignRuleJSON->LiveOrPreMatch;
        $MinSelCount = $ForeignRuleJSON->MinSelCount; // Minimo cantidad de selecciones
        $MinSelPrice = $ForeignRuleJSON->MinSelPrice; // Minimo cuota seleccionada
        $MinBetPrice = $ForeignRuleJSON->MinBetPrice; // Minimo apuesta cuota total
        $SportBonusRules = $ForeignRuleJSON->SportBonusRules;

        $ProductTypeId = $params->ProductTypeId;

        $TriggerId = $params->TriggerId;

        $TypeId = $params->TypeId;

        $Games = $params->Games;

        $condiciones = [];


        $MaxPayout = $params->MaxPayout; //Pago Maximo
        $MaximumBonusAmount = $params->MaximumBonusAmount; //Maximo valordel bono
        $MaximumDeposit = $params->MaximumDeposit;
        $MinimumDeposit = $params->MinimumDeposit;
        $MoneyRequirement = $params->MoneyRequirement;
        $MoneyRequirementAmount = $params->MoneyRequirementAmount;

        $Schedule = $params->Schedule; //Programar bono
        $ScheduleCount = $Schedule->Count; //
        $ScheduleName = $Schedule->Name; //Descripcion de la programacion
        $SchedulePeriod = $Schedule->Period;
        $SchedulePeriodType = $Schedule->PeriodType;

        $TriggerDetails = $params->TriggerDetails;
        $Count = $TriggerDetails->Count; //Cantidad de depositos

        $AreAllowed = $TriggerDetails->AreAllowed; //Son Permitidos los paises

        $IsFromCashDesk = $TriggerDetails->IsFromCashDesk;
        $PaymentSystemId = $TriggerDetails->PaymentSystemId;
        $PaymentSystemIds = $TriggerDetails->PaymentSystemIds;
        $Regions = $TriggerDetails->Regions;

        $tipobono = $TypeId;

        $BonoInterno = new BonoInterno($bonoId);
        $BonoInterno->nombre = $Name;
        $BonoInterno->descripcion = $Description;
        /*$BonoInterno->fechaInicio = $StartDate;
        $BonoInterno->fechaFin = $EndDate;
        $BonoInterno->tipo = $tipobono;
        $BonoInterno->estado = 'A';
        $BonoInterno->usucreaId = 0;*/
        $BonoInterno->usumodifId = $_SESSION["usuario"];


        $BonoInterno->reglas = $RulesText;

        if ($Type == '1') {
            $BonoInterno->publico = 'I';
        } else {
            $BonoInterno->publico = 'A';
        }

        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $transaccion = $BonoDetalleMySqlDAO->getTransaction();
        $BonoInternoMySqlDAO = new BonoInternoMySqlDAO($transaccion);
        $BonoInternoMySqlDAO->update($BonoInterno);

        //$BonoDetalleMySqlDAO->deleteByBonoId($bonoId);

        //Expiracion

        /*if ($ExpirationDays != "") {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "EXPDIA";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $ExpirationDays;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        if ($UseFrozeWallet != "") {

            $BonoDetalle = new BonoDetalle("", $bonoId, "FROZEWALLET");
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "FROZEWALLET";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $UseFrozeWallet;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        if ($SuppressWithdrawal != "") {


            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "SUPPRESSWITHDRAWAL";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $SuppressWithdrawal;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        if ($ScheduleCount != "") {


            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "SCHEDULECOUNT";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $ScheduleCount;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        if ($ScheduleName != "") {


            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "SCHEDULENAME";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $ScheduleName;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        if ($SchedulePeriod != "") {


            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "SCHEDULEPERIOD";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $SchedulePeriod;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        if ($SchedulePeriodType != "") {


            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "SCHEDULEPERIODTYPE";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $SchedulePeriodType;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        if ($ProductTypeId !== "") {

            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "TIPOPRODUCTO";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $ProductTypeId;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        if ($Count != "") {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "CANTDEPOSITOS";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $Count;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);

        }

        if ($AreAllowed != "") {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "PAISESPERMITIDOS";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $AreAllowed;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        if ($ExpirationDate != "") {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "EXPFECHA";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $ExpirationDate;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        if($TypeBonusDeposit == '1') {

            if ($BonusPercent != "") {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "PORCENTAJE";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $BonusPercent;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);
            }
        }

        if ($BonusWFactor != "") {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "WFACTORBONO";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $BonusWFactor;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        if ($DepositWFactor != "") {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "WFACTORDEPOSITO";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $DepositWFactor;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }


        if ($DepositNumber != "") {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "NUMERODEPOSITO";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $DepositNumber;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        if ($IsFromCashDesk) {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "CONDEFECTIVO";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = 'true';
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        if ($MaxplayersCount != "") {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "MAXJUGADORES";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $MaxplayersCount;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }


        foreach ($MaxPayout as $key => $value) {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "MAXPAGO";
            $BonoDetalle->moneda = $value->CurrencyId;
            $BonoDetalle->valor = $value->Amount;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        foreach ($MaximumDeposit as $key => $value) {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "MAXDEPOSITO";
            $BonoDetalle->moneda = $value->CurrencyId;
            $BonoDetalle->valor = $value->Amount;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        foreach ($MinimumDeposit as $key => $value) {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "MINDEPOSITO";
            $BonoDetalle->moneda = $value->CurrencyId;
            $BonoDetalle->valor = $value->Amount;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }
        if($TypeBonusDeposit == '0') {

            foreach ($MoneyRequirement as $key => $value) {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "VALORBONO";
                $BonoDetalle->moneda = $value->CurrencyId;
                $BonoDetalle->valor = $value->Amount;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);
            }
        }

        foreach ($PaymentSystemIds as $key => $value) {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "CONDPAYMENT";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $value;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        foreach ($Regions as $key => $value) {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "CONDPAIS";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $value;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        foreach ($Games as $key => $value) {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "CONDGAME" . $value->Id;
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $value->WageringPercent;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        foreach ($SportBonusRules as $key => $value) {

            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "ITAINMENT" . $value->ObjectTypeId;
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $value->ObjectId;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        if ($LiveOrPreMatch != "") {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "LIVEORPREMATCH";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $LiveOrPreMatch;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        if ($MinSelCount != "") {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "MINSELCOUNT";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $MinSelCount;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        if ($MinSelPrice != "") {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "MINSELPRICE";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $MinSelPrice;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        if ($MinBetPrice != "" && false) {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "MINBETPRICE";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $LiveOrPreMatch;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }*/


        $transaccion->commit();

        $response["HasError"] = false;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];
        $response["Result"] = array();

        break;

    /**
     * SaveFreeBetBonus
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
    case "SaveFreeBetBonus":


        $mandanteUsuario = 0;
        // Si el usuario esta condicionado por el mandante y no es de Global
        if ($_SESSION['Global'] == "N") {
            $mandanteUsuario = $_SESSION['mandante'];
        }


        try {


            $Description = $params->Description; //Descripcion del bono
            $Name = $params->Name; //Nombre del bono

            $PartnerBonus = $params->PartnerBonus;

            $StartDate = $PartnerBonus->StartDate; //Fecha Inicial de la campaña
            $EndDate = $PartnerBonus->EndDate; //Fecha Final de la campaña

            $ExpirationDays = $PartnerBonus->ExpirationDays; // Dias de expiracion del bono

            $LiveOrPreMatch = $params->LiveOrPreMatch;
            $MinSelCount = $params->MinSelCount;
            $MinSelPrice = $params->MinSelPrice;
            $MinSelPriceTotal = $params->MinSelPriceTotal;

            $MinBetPrice = $ForeignRuleJSON->MinBetPrice; // Minimo apuesta cuota total

            $TriggerId = $params->TriggerId;
            $CodePromo = $params->CodePromo;


            $MaxplayersCount = $params->MaxplayersCount; // Maximo jugadores que lo pueden obtener
            $Prefix = $params->Prefix;

            $PlayersChosen = $params->PlayersChosen;
            $ProductTypeId = $params->ProductTypeId;


            $Games = $params->Games;

            $condiciones = [];

            $UserRepeatBonus = $params->UserRepeatBonus;
            $SportBonusRules = $params->SportBonusRules;

            $BonusDetails = $PartnerBonus->BonusDetails;

            $TriggerDetails = $params->TriggerDetails;

            $Casino = $params->Casino->Info;
            $CasinoProduct = $Casino->Product;

            $TypeBonusDeposit = $params->TypeBonusDeposit;


            $ConditionProduct = $TriggerDetails->ConditionProduct;
            if ($ConditionProduct != "OR" && $ConditionProduct != "AND") {
                $ConditionProduct = 'NA';
            }


            $tipobono = 6;
            $Priority = $params->Priority;

            if ($Priority == "" || !is_numeric($Priority)) {
                $Priority = 0;
            }

            $cupo = 0;
            $cupoMaximo = 0;
            $jugadores = 0;
            $jugadoresMaximo = 0;

            if ($MaximumAmount != "" && $tipobono == 2) {
                $cupoMaximo = $MaximumAmount[0]->Amount;
            }

            if ($MaxplayersCount != "" && $tipobono == 2) {
                $jugadoresMaximo = $MaxplayersCount;
            }

            $usucrea_id = $_SESSION["usuario"];
            $usumodif_id = $_SESSION["usuario"];

            $BonoInterno = new BonoInterno();
            $BonoInterno->nombre = $Name;
            $BonoInterno->descripcion = $Description;
            $BonoInterno->fechaInicio = $StartDate;
            $BonoInterno->fechaFin = $EndDate;
            $BonoInterno->tipo = $tipobono;
            $BonoInterno->estado = 'A';
            $BonoInterno->usucreaId = 0;
            $BonoInterno->usumodifId = 0;
            $BonoInterno->mandante = $mandanteUsuario;
            $BonoInterno->condicional = $ConditionProduct;
            $BonoInterno->orden = $Priority;
            $BonoInterno->cupoActual = $cupo;
            $BonoInterno->cupoMaximo = $cupoMaximo;
            $BonoInterno->cantidadBonos = $jugadores;
            $BonoInterno->maximoBonos = $jugadoresMaximo;


            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();

            $bonoId = $BonoInterno->insert($transaccion);


            if ($MaxplayersCount != "" && $Prefix != "") {

                $jugadoresAsignar = array();
                $jugadoresAsignarFinal = array();

                if ($PlayersChosen != "") {
                    $jugadoresAsignar = explode(",", $PlayersChosen);

                    foreach ($jugadoresAsignar as $item) {

                        array_push($jugadoresAsignarFinal, array("Id" => $item, "Valor" => 0));

                    }
                }


                $codigosarray = array();

                for ($i = 1; $i <= $MaxplayersCount; $i++) {
                    $codigo = GenerarClaveTicket(4);

                    while (in_array($codigo, $codigosarray)) {
                        $codigo = GenerarClaveTicket(4);
                    }

                    if ($jugadoresAsignarFinal[$i]["Id"] != "") {
                        $usuarioId = $jugadoresAsignarFinal[$i]["Id"];

                        $valor = $jugadoresAsignarFinal[$i]["Valor"];

                        $valor_bono = $jugadoresAsignarFinal[$i]["Valor"];

                        $valor_base = $jugadoresAsignarFinal[$i]["Valor"];


                    } else {
                        $usuarioId = '0';
                        $valor = '0';

                        $valor_bono = '0';

                        $valor_base = '0';

                    }


                    $estado = 'L';

                    $errorId = '0';

                    $idExterno = '0';

                    $mandante = '0';


                    $usucreaId = '0';

                    $usumodifId = '0';


                    $apostado = '0';
                    $rollowerRequerido = '0';
                    $codigo = $Prefix . $codigo;

                    $UsuarioBono = new UsuarioBono();

                    $UsuarioBono->setUsuarioId($usuarioId);
                    $UsuarioBono->setBonoId($bonoId);
                    $UsuarioBono->setValor($valor);
                    $UsuarioBono->setValorBono($valor_bono);
                    $UsuarioBono->setValorBase($valor_base);
                    $UsuarioBono->setEstado($estado);
                    $UsuarioBono->setErrorId($errorId);
                    $UsuarioBono->setIdExterno($idExterno);
                    $UsuarioBono->setMandante($mandante);
                    $UsuarioBono->setUsucreaId($usucreaId);
                    $UsuarioBono->setUsumodifId($usumodifId);
                    $UsuarioBono->setApostado($apostado);
                    $UsuarioBono->setRollowerRequerido($rollowerRequerido);
                    $UsuarioBono->setCodigo($codigo);
                    $UsuarioBono->setVersion(0);
                    $UsuarioBono->setExternoId($idExterno);

                    $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($transaccion);

                    $UsuarioBonoMysqlDAO->insert($UsuarioBono);


                    array_push($codigosarray, $codigo);

                }
            }


            //Expiracion

            if ($ExpirationDays != "") {

                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "EXPDIA";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $ExpirationDays;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);

            }

            if ($UserRepeatBonus != "" && ($UserRepeatBonus == "true" || $UserRepeatBonus == true)) {

                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "REPETIRBONO";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $UserRepeatBonus;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);


            }

            if ($Prefix != "") {

                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "PREFIX";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $Prefix;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);


            }

            if ($UseFrozeWallet != "") {

                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "FROZEWALLET";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $UseFrozeWallet;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);

            }

            if ($SuppressWithdrawal != "") {

                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "SUPPRESSWITHDRAWAL";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $SuppressWithdrawal;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);


            }

            if ($ScheduleCount != "") {

                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "SCHEDULECOUNT";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $ScheduleCount;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);


            }

            if ($ScheduleName != "") {

                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "SCHEDULENAME";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $ScheduleName;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);


            }

            if ($SchedulePeriod != "") {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "SCHEDULEPERIOD";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $SchedulePeriod;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);


            }

            if ($SchedulePeriodType != "") {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "SCHEDULEPERIODTYPE";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $SchedulePeriodType;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);


            }

            if ($ProductTypeId !== "") {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "TIPOPRODUCTO";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $ProductTypeId;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);


            }

            /*if ($Count != "") {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "CANTDEPOSITOS";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $Count;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);


            }*/

            if ($AreAllowed != "") {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "PAISESPERMITIDOS";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $AreAllowed;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);

            }

            if ($ExpirationDate != "") {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "EXPFECHA";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $ExpirationDate;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);

            }


            if ($TypeBonusDeposit == '1') {

                if ($BonusPercent != "") {
                    $BonoDetalle = new BonoDetalle();
                    $BonoDetalle->bonoId = $bonoId;
                    $BonoDetalle->tipo = "PORCENTAJE";
                    $BonoDetalle->moneda = '';
                    $BonoDetalle->valor = $BonusPercent;
                    $BonoDetalle->usucreaId = 0;
                    $BonoDetalle->usumodifId = 0;
                    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                    $BonoDetalleMysqlDAO->insert($BonoDetalle);


                }
            }

            /*if ($BonusWFactor != "") {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "WFACTORBONO";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $BonusWFactor;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);

            }

            if ($DepositWFactor != "") {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "WFACTORDEPOSITO";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $DepositWFactor;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);

            }


            if ($DepositNumber != "") {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "NUMERODEPOSITO";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $DepositNumber;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);


            }

            if ($IsFromCashDesk) {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "CONDEFECTIVO";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = 'true';
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);


            }*/

            if ($MaxplayersCount != "") {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "MAXJUGADORES";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $MaxplayersCount;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);


            }


            foreach ($MaxPayout as $key => $value) {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "MAXPAGO";
                $BonoDetalle->moneda = $value->CurrencyId;
                $BonoDetalle->valor = $value->Amount;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);


            }

            /*foreach ($MaximumDeposit as $key => $value) {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "MAXDEPOSITO";
                $BonoDetalle->moneda = $value->CurrencyId;
                $BonoDetalle->valor = $value->Amount;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);


            }

            foreach ($MinimumDeposit as $key => $value) {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "MINDEPOSITO";
                $BonoDetalle->moneda = $value->CurrencyId;
                $BonoDetalle->valor = $value->Amount;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);


            }*/


            if ($TypeBonusDeposit == '0') {

                foreach ($MoneyRequirement as $key => $value) {
                    $BonoDetalle = new BonoDetalle();
                    $BonoDetalle->bonoId = $bonoId;
                    $BonoDetalle->tipo = "VALORBONO";
                    $BonoDetalle->moneda = $value->CurrencyId;
                    $BonoDetalle->valor = $value->Amount;
                    $BonoDetalle->usucreaId = 0;
                    $BonoDetalle->usumodifId = 0;
                    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                    $BonoDetalleMysqlDAO->insert($BonoDetalle);


                }

            }

            /*foreach ($PaymentSystemIds as $key => $value) {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "CONDPAYMENT";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $value;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);


            }*/

            foreach ($Regions as $key => $value) {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "CONDPAISPV";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $value;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);

            }

            foreach ($Departments as $key => $value) {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "CONDDEPARTAMENTOPV";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $value;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);

            }

            foreach ($Cities as $key => $value) {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "CONDCIUDADPV";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $value;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);

            }

            if ($BalanceZero == "true" || $BalanceZero == 1 || $BalanceZero == true) {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "CONDBALANCE";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = '0';
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);
            }


            foreach ($RegionsUser as $key => $value) {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "CONDPAISUSER";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $value;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);


            }

            foreach ($DepartmentsUser as $key => $value) {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "CONDDEPARTAMENTOUSER";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $value;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);

            }

            foreach ($CitiesUser as $key => $value) {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "CONDCIUDADUSER";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $value;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);

            }

            foreach ($CashDesks as $key => $value) {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "CONDPUNTOVENTA";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $value;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);

            }


            foreach ($Games as $key => $value) {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "CONDGAME" . $value->Id;
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $value->WageringPercent;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);

            }

            foreach ($CasinoProduct as $key => $value) {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "CONDGAME" . $value->Id;
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $value->Percentage;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);

            }

            foreach ($SportBonusRules as $key => $value) {
                $dataA = array(
                    "ObjectTypeId" => $value->ObjectTypeId,
                    "Id" => $value->ObjectId,
                    "Name" => $value->Name,
                    "SportName" => $value->SportName,
                    "ObjectTypeId" => $value->Name
                );
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "ITAINMENT" . $value->ObjectTypeId;
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $value->ObjectId;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);

            }

            if ($LiveOrPreMatch != "") {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "LIVEORPREMATCH";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $LiveOrPreMatch;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);

            }

            if ($MinSelCount != "") {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "MINSELCOUNT";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $MinSelCount;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);

            }

            if ($MinSelPrice != "") {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "MINSELPRICE";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $MinSelPrice;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);

            }


            if ($MinSelPriceTotal != "") {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "MINSELPRICETOTAL";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $MinSelPriceTotal;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);

            }

            if ($TriggerId != "") {
                if ($CodePromo != "") {
                    $BonoDetalle = new BonoDetalle();
                    $BonoDetalle->bonoId = $bonoId;
                    $BonoDetalle->tipo = "CODEPROMO";
                    $BonoDetalle->moneda = '';
                    $BonoDetalle->valor = $CodePromo;
                    $BonoDetalle->usucreaId = 0;
                    $BonoDetalle->usumodifId = 0;
                    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                    $BonoDetalleMysqlDAO->insert($BonoDetalle);

                }
            }

            if ($MinBetPrice != "" && false) {

                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "MINBETPRICE";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $MinBetPrice;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);


            }


            foreach ($BonusDetails as $key => $value) {
                if ($value->MinAmount != "") {
                    $BonoDetalle = new BonoDetalle();
                    $BonoDetalle->bonoId = $bonoId;
                    $BonoDetalle->tipo = "MINAMOUNT";
                    $BonoDetalle->moneda = $value->CurrencyId;
                    $BonoDetalle->valor = $value->MinAmount;
                    $BonoDetalle->usucreaId = 0;
                    $BonoDetalle->usumodifId = 0;
                    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                    $BonoDetalleMysqlDAO->insert($BonoDetalle);


                }
                if ($value->MaxAmount != "") {
                    $BonoDetalle = new BonoDetalle();
                    $BonoDetalle->bonoId = $bonoId;
                    $BonoDetalle->tipo = "MAXAMOUNT";
                    $BonoDetalle->moneda = $value->CurrencyId;
                    $BonoDetalle->valor = $value->MaxAmount;
                    $BonoDetalle->usucreaId = 0;
                    $BonoDetalle->usumodifId = 0;
                    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                    $BonoDetalleMysqlDAO->insert($BonoDetalle);

                }
            }


            $transaccion->commit();

            $response["HasError"] = false;
            $response["AlertType"] = "danger";
            $response["AlertMessage"] = "";
            $response["ModelErrors"] = [];
            $response["Result"] = array();

        } catch (Exception $e) {

            //print_r($e);
        }
        break;

    /**
     * SetBonusState
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
    case "SetBonusState":

        $bonoId = $_REQUEST["id"];
        $enabled = $_REQUEST["enabled"];

        $estado = 'A';

        if ($enabled) {
            $estado = 'A';

        } else {
            $estado = 'I';

        }


        $BonoInterno = new BonoInterno($bonoId);
        $BonoInterno->estado = "I";

        $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
        $BonoInternoMySqlDAO->update($BonoInterno);
        $BonoInternoMySqlDAO->getTransaction()->commit();

        $response["HasError"] = false;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];


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
            $response["AlertMessage"] = "Success";
            $response["ModelErrors"] = [];

            $response["Data"] = array(
                "AuthenticationStatus" => 0,

                "PermissionList" => array(),
            );

        } else {

            try {

                $UsuarioMandante = new UsuarioMandante($_SESSION["usuario"]);
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

                if ($_SESSION["usuario"] == "163") {

                    $menus = $Usuario->getMenus();
                    $menus_string = array();
                    array_push($menus_string, "ViewMenuSecurity");
                    array_push($menus_string, "ViewMenuTeacher");
                    array_push($menus_string, "ViewMenuManagement");
                    array_push($menus_string, "ViewMenuCash");
                    array_push($menus_string, "ViewMenuQueries");
                    foreach ($menus as $key => $value) {
                        array_push($menus_string, "view" . str_replace("_", "", str_replace(".php", "", $value["b.pagina"])));
                    }

                    $response["Data"] = array(
                        "AuthenticationStatus" => 0,
                        "PartnerLimitType" => 1,
                        "FirstName" => $Usuario->nombre,
                        "Settings" => array(
                            "Language" => strtolower($Usuario->idioma),
                            "ReportCurrency" => "USD",

                        ),
                        "LangId" => strtolower($Usuario->idioma),
                        "UserName" => $Usuario->nombre,
                        "CurrencyId" => $Usuario->moneda,
                        "UserId" => $Usuario->usuarioId,
                        "PermissionList" => array_merge(array(
                            "ViewClientBonuses", "ViewPlayers", "ViewAddHocReport", "ViewScout", "ViewCMS", "ViewAffiliate", "SGPlayersView", "SGStatisticsRake", "ViewFinancialReports", "ViewPaymentReport", "AssignAgentCredit", "ManageAgentCredit", "ManageClientCredit", "ViewGames", "ViewClientSportBets", "ViewClientTransactions", "ViewClientLogins", "ViewClientCasinoBets", "ViewSportReport", "ViewMenuDashBoard", "ViewDashBoardActivePlayers", "ViewDashBoardNewRegistrations", "ViewDashBoardSportBets", "ViewDashBoardCasinoBets", "ViewDashBoardTopFiveGames", "ViewDashBoardTopSportsByStake", "ViewDashBoardTopFiveSportsbookPlayers", "ViewDashBoardTopFivePlayers", "ViewUsers", "ViewAgentTransfers", "ViewBalance", "ViewDepositWithdrawalReport", "PMManageSale", "PMManageProduct", "ViewSalesReport", "ViewTurnoverTaxReport", "ViewDepositRequests", "ViewWithdrawalRequests", "ViewDocuments", "ViewFinancialOperations", "ManageAgent", "ViewBetShopUsers", "ViewCashDesks", "ManageBetShopUsers", "ViewClientMessage", "ViewVerificationStep", "ResetClientPassword", "ViewAgentMenu", "ViewAgentSystem", "ViewAgent", "ViewAgentSubAccounts", "ViewAgentMembers", "ViewMessages", "ViewEmailTemplates", "ManageMessages", "ViewTranslation", "ViewSettings", "ViewStream", "PMViewPartner", "PMViewProduct", "PMViewSale", "hjkhjkhjk", "jhkhjkhjk", "ViewOddsFeed", "ViewMatch", "ViewSportLimits", "ViewPartnersBooking", "ViewMenuReport", "ViewCasinoReport", "ViewCashDeskReport", "ViewCRM", "ViewSegment", "CreateSegment", "ViewBetShops", "ViewClients", "ManageClients", "ViewMenuSecurity", "ViewMenuTeacher", "AdminUser", "Contingencia", "Menu", "Perfil", "PerfilOpciones", "Submenu", "UsuarioPerfil", "Clasificador", "Concesionario", "ViewAddHocReport", "ViewMenuManagement", "ActivarRegistros", "AjustarSaldo", "AsignacionCupo", "Bonos", "CuentaCobroEliminar", "GestionRed", "RegistroRapido", "ChequeReimpresion", "RecargaReversion", "GestionContacto", "ViewMenuCash", "FlujoCaja", "PagoPremio", "PagoNotaRetiro", "RecargarCredito", "ViewMenuQueries", "FlujoCajaHistorico", "FlujoCajaResumido", "InformeCasino", "InformeGerencial", "ListadoRecargasRetiros", "PremiosPendientesPagar", "ConsultaOnlineDetalle", "ConsultaOnlineResumen",

                        ), $menus_string),
                    );
                } else {

                    $PerfilSubmenu = new PerfilSubmenu();

                    $Perfil_id = $_SESSION["win_perfil2"];
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

                    $json = '{"rules" : [{"field" : "menu.version", "data" : "2","op":"eq"},{"field" : "perfil_submenu.perfil_id", "data" : "' . $Perfil_id . '","op":"eq"}] ,"groupOp" : "AND"}';

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

                    $arrayf["Permissions"] = $submenus;
                    array_push($menus3, $arrayf);

                    $IncludedPermission = $submenus;

                    /* $menus = $Usuario->getMenus();
                    array_push($menus_string, "ViewMenuSecurity");
                    array_push($menus_string, "ViewMenuTeacher");
                    array_push($menus_string, "ViewMenuManagement");
                    array_push($menus_string, "ViewMenuCash");
                    array_push($menus_string, "ViewMenuQueries");
                    foreach ($menus as $key => $value) {
                    array_push($menus_string, "view" . str_replace("_", "", str_replace(".php", "", $value["b.pagina"])));
                    } */

                    $response["Data"] = array(
                        "AuthenticationStatus" => 0,
                        "PartnerLimitType" => 1,
                        "FirstName" => $Usuario->nombre,
                        "Settings" => array(
                            "Language" => strtolower($Usuario->idioma),
                            "ReportCurrency" => "USD",
                        ),
                        "LangId" => strtolower($Usuario->idioma),
                        "UserName" => $Usuario->nombre,
                        "CurrencyId" => $Usuario->moneda,
                        "UserId" => $Usuario->usuarioId,
                        "PermissionList" => $menus_string,
                    );
                }

            } catch (Exception $e) {

                $response["HasError"] = true;
                $response["AlertType"] = "danger";
                $response["AlertMessage"] = "Error ('.$e->getCode().')";
                $response["ModelErrors"] = [];

            }

        }

        break;

    /**
     * CheckForLogin
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
    case 'CheckForLogin':
        $response["HasError"] = false;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = "Success";
        $response["ModelErrors"] = [];
        break;

    /**
     * GetSetting
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
    case 'GetSetting':
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];
        $response["Data"] = array(
            "ReportCurrencies" => array(
                array(
                    "Id" => "EUR",
                    "IsSelected" => 0,
                ),
                array(
                    "Id" => "PEN",
                    "IsSelected" => 0,
                ),
            ),
        );
        break;

    /**
     * GetSysDate
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
    case 'GetSysDate':
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Data"] = array(
            "ServerTime" => round(microtime(true) * 1000),

        );

        break;

    /**
     * GetBonusPlayers
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
    case 'GetBonusPlayers':

        $params = file_get_contents('php://input');
        $params = json_decode($params);

        $ToDateLocal = $params->ResultToDate;
        $FromDateLocal = $params->ResultFromDate;
        $BonusDefinitionIds = $params->BonusDefinitionIds;
        $PlayerExternalId = $params->PlayerExternalId;

        $MaxRows = $params->Limit;
        $OrderedItem = $params->OrderedItem;
        $SkeepRows = ($params->Offset) * $MaxRows;

        $rules = [];
        $string = "";

        foreach ($BonusDefinitionIds as $key => $value) {
            $string = $string . $value . ",";
        }

        if ($ToDateLocal != "") {
            array_push($rules, array("field" => "promocional_log.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
        }
        if ($FromDateLocal != "") {
            array_push($rules, array("field" => "promocional_log.fecha_crea", "data" => "$FromDateLocal", "op" => "ge"));
        }

        if ($BonusDefinitionIds != "") {
            array_push($rules, array("field" => "promocional_log.promocional_id", "data" => "" . $string . "0", "op" => "in"));
        }

        if ($PlayerExternalId != "") {
            array_push($rules, array("field" => "promocional_log.usuario_id", "data" => $PlayerExternalId, "op" => "eq"));
        }


        // Si el usuario esta condicionado por País
        if ($_SESSION['PaisCond'] == "S") {
        }
        // Si el usuario esta condicionado por el mandante y no es de Global
        if ($_SESSION['Global'] == "N") {
            array_push($rules, array("field" => "bono_interno.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
        }


        $filtro = array("rules" => $rules, "groupOp" => "AND");

        if ($SkeepRows == "") {
            $SkeepRows = 0;
        }

        if ($OrderedItem == "") {
            $OrderedItem = 1;
        }

        if ($MaxRows == "") {
            $MaxRows = 30;
        }

        $json = json_encode($filtro);

        $PromocionalLog = new PromocionalLog();

        $bonos = $PromocionalLog->getPromocionalLogsCustom(" promocional_log.* ", "promocional_log.promolog_id", "asc", $SkeepRows, $MaxRows, $json, true);

        $bonos = json_decode($bonos);

        $final = [];

        foreach ($bonos->data as $key => $value) {

            $array = [];

            $array["Id"] = $value->{"promocional_log.promolog_id"};
            $array["PlayerExternalId"] = $value->{"promocional_log.usuario_id"};
            $array["Amount"] = $value->{"promocional_log.valor"};
            $array["AmountBase"] = $value->{"promocional_log.valor_base"};
            $array["AmountBonus"] = $value->{"promocional_log.valor_promocional"};


            array_push($final, $array);
        }

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Result"] = $final;
        $response["Count"] = $bonos->count[0]->{".count"};


        break;

    /**
     * RemoveBonus
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
    case "RemoveBonus":

        $BonusId = $params->BonusId;

        $UsuarioBono = new UsuarioBono($BonusId);

        if ($UsuarioBono->estado != "R" && $UsuarioBono->estado != "E" && $UsuarioBono->estado != "I") {

            $UsuarioBonoMySqlDAO = new UsuarioBonoMySqlDAO();

            if ($UsuarioBono->estado == 'A' && floatval($UsuarioBono->rollowerRequerido) > 0) {
                $Registro = new Registro("", $UsuarioBono->getUsuarioId());

                $RegistroMySqlDAO = new RegistroMySqlDAO($UsuarioBonoMySqlDAO->getTransaction());

                $update = $RegistroMySqlDAO->updateBalance($Registro, "", "", -$UsuarioBono->getValor(), "", "", false);

            }

            $UsuarioBono->estado = "I";

            $UsuarioBonoMySqlDAO->update($UsuarioBono);
            $UsuarioBonoMySqlDAO->getTransaction()->commit();

            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "";
            $response["ModelErrors"] = [];

        } else {
            $response["HasError"] = true;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "";
            $response["ModelErrors"] = [];

        }

        break;

    /**
     * CheckRolloverBonus
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
    case "CheckRolloverBonus":

        error_reporting(E_ALL);
        ini_set("display_errors", "ON");
        $BonusId = $params->BonusId;

        $UsuarioBono = new UsuarioBono($BonusId);

        if ($UsuarioBono->estado == "A") {

            $UsuarioBono->estado = "I";
            /*
                        $UsuarioBonoMySqlDAO = new UsuarioBonoMySqlDAO();
                        $UsuarioBonoMySqlDAO->update($UsuarioBono);
                        $UsuarioBonoMySqlDAO->getTransaction()->commit();

                        */


            $MaxRows = 1000;
            $OrderedItem = 1;
            $SkeepRows = 0;

            $rules = [];
            //array_push($rules, array("field" => "it_ticket_enc.estado", "data" => "I", "op" => "eq"));
            //array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_cierre,' ',it_ticket_enc.hora_cierre)", "data" => "$FromDateLocal ", "op" => "ge"));
            //array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_cierre,' ',it_ticket_enc.hora_cierre)", "data" => "$ToDateLocal", "op" => "le"));
            array_push($rules, array("field" => "it_ticket_enc.usuario_id", "data" => $UsuarioBono->usuarioId, "op" => "eq"));
            array_push($rules, array("field" => "it_ticket_enc_info1.valor", "data" => $UsuarioBono->usubonoId, "op" => "eq"));
            array_push($rules, array("field" => "it_ticket_enc_info1.tipo", "data" => "ROLLOWER", "op" => "eq"));


            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json2 = json_encode($filtro);

            $ItTicketEncInfo1 = new ItTicketEncInfo1();

            $tickets = $ItTicketEncInfo1->getTicketsCustom(" it_ticket_enc_info1.*,it_ticket_enc.vlr_apuesta,it_ticket_enc.fecha_cierre,it_ticket_enc.fecha_crea ", $OrderedItem, $OrderType, $SkeepRows, $MaxRows, $json2, true);

            $tickets = json_decode($tickets);

            $final = [];
            $strApuestas = '0';

            foreach ($tickets->data as $key => $value) {

                $array = [];

                $array["Id"] = ($value->{"it_ticket_enc_info1.it_ticket2_id"});
                $array["TicketId"] = ($value->{"it_ticket_enc_info1.ticket_id"});
                $array["Valor"] = ($value->{"it_ticket_enc_info1.valor"});
                $array["Amount"] = ($value->{"it_ticket_enc.vlr_apuesta"});
                $array["DateCreate"] = ($value->{"it_ticket_enc.fecha_crea"});
                $array["DateClose"] = ($value->{"it_ticket_enc.fecha_cierre"});

                array_push($final, $array["TicketId"]);
                $strApuestas = $strApuestas . "," . $array["TicketId"];

            }


            $rules = [];

            array_push($rules, array("field" => "it_ticket_enc.ticket_id", "data" => "$strApuestas", "op" => "ni"));
            array_push($rules, array("field" => "it_ticket_enc.estado", "data" => "I", "op" => "eq"));
            array_push($rules, array("field" => "it_ticket_enc.freebet", "data" => "0", "op" => "eq"));
            array_push($rules, array("field" => "it_ticket_enc.eliminado", "data" => "N", "op" => "eq"));
            array_push($rules, array("field" => "it_ticket_enc.bet_status", "data" => "'S','N'", "op" => "in"));

            array_push($rules, array("field" => "it_ticket_enc.usuario_id", "data" => $UsuarioBono->usuarioId, "op" => "eq"));
            array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_crea,' ',it_ticket_enc.hora_crea)", "data" => $UsuarioBono->fechaCrea, "op" => "ge"));


            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);

            $ItTicketEnc = new ItTicketEnc();
            $tickets = $ItTicketEnc->getTicketsCustom(" it_ticket_enc.ticket_id ", "it_ticket_enc.it_ticket_id", "desc", $SkeepRows, $MaxRows, $json, true);
            $tickets = json_decode($tickets);

            $final = [];

            foreach ($tickets->data as $key => $value) {

                $array = [];

                $array["Id"] = $value->{"it_ticket_enc.ticket_id"};


                $BonoInterno = new BonoInterno();
                $BonoInterno->verificarBonoRollower($UsuarioBono->usuarioId, '', "SPORT", $array["Id"]);
            }

            if (oldCount($tickets->data) == 0) {
                print_r("ENTRO");
                $BonoInterno = new BonoInterno();
                $BonoInterno->verificarBonoRollower($UsuarioBono->usuarioId, '', "SPORT", '');

            }


            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "";
            $response["ModelErrors"] = [];

        } else {
            $response["HasError"] = true;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "";
            $response["ModelErrors"] = [];

        }

        break;

    /**
     * GetBonusDashboards
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
    case "GetBonusDashboards":

        $params = file_get_contents('php://input');
        $params = json_decode($params);

        $ToDateLocal = $params->ResultToDate;
        $FromDateLocal = $params->ResultFromDate;
        $BonusDefinitionIds = $params->BonusDefinitionIds;
        $PlayerExternalId = $params->PlayerExternalId;


        $MaxRows = $params->Limit;
        $OrderedItem = $params->OrderedItem;
        $SkeepRows = ($params->Offset) * $MaxRows;


        $rules = [];


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

        $select = "SUM(CASE WHEN bono_interno.estado =  'A' THEN 1 ELSE 0 END) cant_activos
        ";


        $BonoInterno = new BonoInterno();
        $data = $BonoInterno->getBonosCustom($select, "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);


        $data = json_decode($data);

        $value = $data->data[0];

        $final = [];
        $final["ActiveBonus"] = [];
        $final["ActiveBonus"]["Total"] = $value->{".cant_activos"};

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Result"] = $final;
        $response["Data"] = $final;


        break;

    /**
     * GetTournamentsDashboards
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
    case "GetTournamentsDashboards":

        $params = file_get_contents('php://input');
        $params = json_decode($params);

        $ToDateLocal = $params->ResultToDate;
        $FromDateLocal = $params->ResultFromDate;
        $BonusDefinitionIds = $params->BonusDefinitionIds;
        $PlayerExternalId = $params->PlayerExternalId;

        $MaxRows = $params->Limit;
        $OrderedItem = $params->OrderedItem;
        $SkeepRows = ($params->Offset) * $MaxRows;


        $rules = [];

        // Si el usuario esta condicionado por País
        if ($_SESSION['PaisCond'] == "S") {
        }
        // Si el usuario esta condicionado por el mandante y no es de Global
        if ($_SESSION['Global'] == "N") {
            array_push($rules, array("field" => "torneo_interno.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
        }


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

        $select = "SUM(CASE WHEN torneo_interno.estado =  'A' THEN 1 ELSE 0 END) cant_activos
        ";


        $TorneoInterno = new TorneoInterno();
        $data = $TorneoInterno->getTorneosCustom($select, "torneo_interno.torneo_id", "asc", $SkeepRows, $MaxRows, $json, false);


        $data = json_decode($data);

        $value = $data->data[0];

        $final = [];
        $final["ActiveTournaments"] = [];
        $final["ActiveTournaments"]["Total"] = $value->{".cant_activos"};

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Result"] = $final;
        $response["Data"] = $final;


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
        $params = file_get_contents('php://input');
        $params = json_decode($params);

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
        $FromDateLocal = date("Y-m-d H:i:s", strtotime($DateFrom));

        $DateTo = $params->DateTo;
        $ToDateLocal = date("Y-m-d H:i:s", strtotime($DateTo));


        if ($Id == 0) {
            $rules = [];
            array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));

            switch ($State) {
                case "A":
                    $fechaSql = "DATE_FORMAT(usuario_bono.fecha_crea,";
                    array_push($rules, array("field" => "usuario_bono.estado", "data" => "A", "op" => "eq"));
                    break;

                case "R":
                    $fechaSql = "DATE_FORMAT(usuario_bono.fecha_modif,";
                    array_push($rules, array("field" => "usuario_bono.estado", "data" => "R", "op" => "eq"));

                    break;

                case "E":
                    $fechaSql = "DATE_FORMAT(usuario_bono.fecha_modif,";
                    array_push($rules, array("field" => "usuario_bono.estado", "data" => "E", "op" => "eq"));

                    break;

                default:
                    $fechaSql = "DATE_FORMAT(usuario_bono.fecha_crea,";
                    break;
            }

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
                    $select = "SUM(usuario_bono.valor_base) valor, " . $fechaSql . " fecha";
                    break;

                case "2":
                    $select = "COUNT(usuario_bono.usuario_id) valor, " . $fechaSql . " fecha";
                    break;
                default:
                    $select = "SUM(usuario_bono.valor) valor, " . $fechaSql . " fecha";

                    break;
            }
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


            $UsuarioBono = new UsuarioBono();
            $data = $UsuarioBono->getUsuarioBonosCustom($select, "usuario_bono.usubono_id", "asc", $SkeepRows, $MaxRows, $json, true, $fechaSql);


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


            $final = [];

            $rules = [];

            array_push($rules, array("field" => "usuario_bono.fecha_crea", "data" => "$FromDateLocal", "op" => "ge"));
            array_push($rules, array("field" => "usuario_bono.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));

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

            $select = "SUM(CASE WHEN usuario_bono.estado =  'R' THEN usuario_bono.valor ELSE 0 END) valor_redimidos,
        SUM(CASE WHEN usuario_bono.estado =  'A' THEN usuario_bono.valor ELSE 0 END) valor_activos,
        SUM(CASE WHEN usuario_bono.estado =  'E' THEN usuario_bono.valor ELSE 0 END) valor_expirados,
        SUM(CASE WHEN usuario_bono.estado =  'R' THEN 1 ELSE 0 END) cant_redimidos,
        SUM(CASE WHEN usuario_bono.estado =  'A' THEN 1 ELSE 0 END) cant_activos,
        SUM(CASE WHEN usuario_bono.estado =  'E' THEN 1 ELSE 0 END) cant_expirados
        ";


            $UsuarioBono = new UsuarioBono();
            $data = $UsuarioBono->getUsuarioBonosCustom($select, "usuario_bono.usubono_id", "asc", $SkeepRows, $MaxRows, $json, true, '');


            $data = json_decode($data);

            $value = $data->data[0];

            $final["ActiveBonus"] = [];
            $final["ActiveBonus"]["Total"] = $value->{".cant_activos"};
            $final["ActiveBonus"]["Amount"] = round($value->{".valor_activos"}, 2);

            $rules = [];

            array_push($rules, array("field" => "usuario_bono.fecha_modif", "data" => "$FromDateLocal", "op" => "ge"));
            array_push($rules, array("field" => "usuario_bono.fecha_modif", "data" => "$ToDateLocal", "op" => "le"));

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

            $select = "SUM(CASE WHEN usuario_bono.estado =  'R' THEN usuario_bono.valor ELSE 0 END) valor_redimidos,
        SUM(CASE WHEN usuario_bono.estado =  'A' THEN usuario_bono.valor ELSE 0 END) valor_activos,
        SUM(CASE WHEN usuario_bono.estado =  'E' THEN usuario_bono.valor ELSE 0 END) valor_expirados,
        SUM(CASE WHEN usuario_bono.estado =  'R' THEN 1 ELSE 0 END) cant_redimidos,
        SUM(CASE WHEN usuario_bono.estado =  'A' THEN 1 ELSE 0 END) cant_activos,
        SUM(CASE WHEN usuario_bono.estado =  'E' THEN 1 ELSE 0 END) cant_expirados
        ";


            $UsuarioBono = new UsuarioBono();
            $data = $UsuarioBono->getUsuarioBonosCustom($select, "usuario_bono.usubono_id", "asc", $SkeepRows, $MaxRows, $json, true, '');


            $data = json_decode($data);

            $value = $data->data[0];


            $final["RedimBonus"] = [];
            $final["RedimBonus"]["Total"] = $value->{".cant_redimidos"};
            $final["RedimBonus"]["Amount"] = round($value->{".valor_redimidos"}, 2);
            $final["ExpiratedBonus"] = [];
            $final["ExpiratedBonus"]["Total"] = $value->{".cant_expirados"};
            $final["ExpiratedBonus"]["Amount"] = round($value->{".valor_expirados"}, 2);


            $final["AllBonus"] = [];
            $final["AllBonus"]["Total"] = $final["ActiveBonus"]["Total"] + $final["RedimBonus"]["Total"] + $final["ExpiratedBonus"]["Total"];
            $final["AllBonus"]["Amount"] = round($final["ActiveBonus"]["Amount"] + $final["RedimBonus"]["Amount"] + $final["ExpiratedBonus"]["Amount"], 2);


        }
        if ($Id == 1) {
            $rules = [];
            array_push($rules, array("field" => "usuario_mandante.moneda", "data" => "$Currency", "op" => "eq"));

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

            switch ($TypeAmount) {

                case "0":

                    $fechaSql = "DATE_FORMAT(transjuego_info.fecha_crea," . $fechaSql2;

                    $select = "SUM(transjuego_info.valor) valor, " . $fechaSql . " fecha";
                    $TransjuegoInfo = new TransjuegoInfo();
                    $data = $TransjuegoInfo->getTransjuegoInfosCustom($select, "transjuego_info.transjuegoinfo_id", "asc", $SkeepRows, $MaxRows, $json, true, $fechaSql);

                    break;


                case "1":
                    $fechaSql = "DATE_FORMAT(transjuego_info.fecha_crea," . $fechaSql2;

                    $select = "SUM(transaccion_api.valor) valor, " . $fechaSql . " fecha";
                    $TransjuegoInfo = new TransjuegoInfo();
                    $data = $TransjuegoInfo->getTransjuegoInfosCustom($select, "transjuego_info.transjuegoinfo_id", "asc", $SkeepRows, $MaxRows, $json, true, $fechaSql);

                    break;

                case "2":
                    $fechaSql = "DATE_FORMAT(usuario_torneo.fecha_crea," . $fechaSql2;
                    $select = "COUNT(usuario_torneo.usuario_id) valor, " . $fechaSql . " fecha";
                    $UsuarioTorneo = new UsuarioTorneo();
                    $data = $UsuarioTorneo->getUsuarioTorneosCustom($select, "usuario_torneo.usutorneo_id", "asc", $SkeepRows, $MaxRows, $json, true, $fechaSql);

                    break;

                case "3":
                    $fechaSql = "DATE_FORMAT(transjuego_info.fecha_crea," . $fechaSql2;
                    $select = "COUNT(transjuego_info.transjuegoinfo_id) valor, " . $fechaSql . " fecha";
                    $TransjuegoInfo = new TransjuegoInfo();
                    $data = $TransjuegoInfo->getTransjuegoInfosCustom($select, "transjuego_info.transjuegoinfo_id", "asc", $SkeepRows, $MaxRows, $json, true, $fechaSql);

                    break;
            }
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
            array_push($rules, array("field" => "usuario_torneo.fecha_crea", "data" => "$FromDateLocal", "op" => "ge"));
            array_push($rules, array("field" => "usuario_torneo.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));


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

            $select = "COUNT(*) usuarios,
        SUM(usuario_torneo.valor) creditos,
        SUM(usuario_torneo.valor_base) dinero
        ";


            $UsuarioTorneo = new UsuarioTorneo();
            $data = $UsuarioTorneo->getUsuarioTorneosCustom($select, "usuario_torneo.usutorneo_id", "asc", $SkeepRows, $MaxRows, $json, true, '');


            $data = json_decode($data);

            $value = $data->data[0];


            $final = [];
            $final["Credits"] = [];
            $final["Credits"]["Total"] = $value->{".creditos"};
            $final["Credits"]["Amount"] = $value->{".creditos"};
            $final["RealMoney"] = [];
            $final["RealMoney"]["Total"] = $value->{".dinero"};
            $final["RealMoney"]["Amount"] = $value->{".dinero"};
            $final["Players"] = [];
            $final["Players"]["Total"] = $value->{".usuarios"};
            $final["Players"]["Amount"] = $value->{".usuarios"};


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
     * GetBonusDetailDashboards
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
    case "GetBonusDetailDashboards":

        $params = file_get_contents('php://input');
        $params = json_decode($params);

        $ToDateLocal = $params->ResultToDate;
        $FromDateLocal = $params->ResultFromDate;
        $BonusDefinitionIds = $params->BonusDefinitionIds;
        $PlayerExternalId = $params->PlayerExternalId;

        $MaxRows = $params->Limit;
        $OrderedItem = $params->OrderedItem;
        $SkeepRows = ($params->Offset) * $MaxRows;

        $IdBonus = $_REQUEST["IdBonus"];


        $rules = [];

        array_push($rules, array("field" => "bono_interno.bono_id", "data" => "$IdBonus", "op" => "eq"));

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

        $select = "SUM(CASE WHEN usuario_bono.estado =  'R' THEN usuario_bono.valor ELSE 0 END) valor_redimidos,
        SUM(CASE WHEN usuario_bono.estado =  'A' THEN usuario_bono.valor ELSE 0 END) valor_activos,
        SUM(CASE WHEN usuario_bono.estado =  'E' THEN usuario_bono.valor ELSE 0 END) valor_expirados,
        SUM(CASE WHEN usuario_bono.estado =  'R' THEN 1 ELSE 0 END) cant_redimidos,
        SUM(CASE WHEN usuario_bono.estado =  'A' THEN 1 ELSE 0 END) cant_activos,
        SUM(CASE WHEN usuario_bono.estado =  'E' THEN 1 ELSE 0 END) cant_expirados
        ";


        $UsuarioBono = new UsuarioBono();
        $data = $UsuarioBono->getUsuarioBonosCustom($select, "usuario_bono.usubono_id", "asc", $SkeepRows, $MaxRows, $json, true, '');


        $data = json_decode($data);

        $value = $data->data[0];

        $final = [];
        $final["ActiveBonus"] = [];
        $final["ActiveBonus"]["Total"] = $value->{".cant_activos"};
        $final["ActiveBonus"]["Amount"] = number_format($value->{".valor_activos"}, 2);
        $final["RedimBonus"] = [];
        $final["RedimBonus"]["Total"] = $value->{".cant_redimidos"};
        $final["RedimBonus"]["Amount"] = number_format($value->{".valor_redimidos"}, 2);
        $final["ExpiratedBonus"] = [];
        $final["ExpiratedBonus"]["Total"] = $value->{".cant_expirados"};
        $final["ExpiratedBonus"]["Amount"] = number_format($value->{".valor_expirados"}, 2);
        $final["AllBonus"] = [];
        $final["AllBonus"]["Total"] = $final["ActiveBonus"]["Total"] + $final["RedimBonus"]["Total"] + $final["ExpiratedBonus"]["Total"];
        $final["AllBonus"]["Amount"] = $value->{".valor_activos"} + $value->{".valor_redimidos"} + $value->{".valor_expirados"};
        $final["AllBonus"]["Amount"] = number_format($final["AllBonus"]["Amount"], 2);

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Result"] = $final;
        $response["Data"] = $final;


        break;

    /**
     * GetTournamentDetailDashboards
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
    case "GetTournamentDetailDashboards":

        $params = file_get_contents('php://input');
        $params = json_decode($params);

        $ToDateLocal = $params->ResultToDate;
        $FromDateLocal = $params->ResultFromDate;
        $BonusDefinitionIds = $params->BonusDefinitionIds;
        $PlayerExternalId = $params->PlayerExternalId;

        $MaxRows = $params->Limit;
        $OrderedItem = $params->OrderedItem;
        $SkeepRows = ($params->Offset) * $MaxRows;

        $Id = $_REQUEST["Id"];


        $rules = [];

        array_push($rules, array("field" => "torneo_interno.torneo_id", "data" => "$Id", "op" => "eq"));

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

        $select = "COUNT(*) usuarios,
        SUM(usuario_torneo.valor) creditos,
        SUM(usuario_torneo.valor_base) dinero,
        SUM(usuario_torneo.valor_premio) premios,
        torneo_interno.fecha_inicio,
        torneo_interno.fecha_fin
        ";


        $UsuarioTorneo = new UsuarioTorneo();
        $data = $UsuarioTorneo->getUsuarioTorneosCustom($select, "usuario_torneo.usutorneo_id", "asc", $SkeepRows, $MaxRows, $json, true, '');


        $data = json_decode($data);

        $value = $data->data[0];

        $ts1 = strtotime($value->{"torneo_interno.fecha_inicio"});
        $ts2 = strtotime($value->{"torneo_interno.fecha_fin"});

        $seconds_diff = $ts2 - $ts1;

        $ts1 = strtotime($value->{"torneo_interno.fecha_fin"});
        $ts2 = strtotime(date('Y-m-d H:i:s'));
        $seconds_diff2 = $ts1 - $ts2;

        $porc = (($seconds_diff2 * 100) / $seconds_diff);

        $porc = 100 - $porc;

        if ($porc > 100) {
            $porc = 100;
        }
        $final = [];
        $final["Credits"] = [];
        $final["Credits"]["Total"] = $value->{".creditos"};
        $final["Credits"]["Amount"] = $value->{".creditos"};

        $final["Credits"]["Amount"] = round($final["Credits"]["Amount"], 2);

        $final["RealMoney"] = [];
        $final["RealMoney"]["Total"] = round($value->{".dinero"}, 2);
        $final["RealMoney"]["Amount"] = $value->{".dinero"};
        $final["RealMoney"]["AmountWin"] = $value->{".premios"};
        $final["RealMoney"]["GGR"] = $final["RealMoney"]["Amount"] - $final["RealMoney"]["AmountWin"];

        $final["RealMoney"]["Amount"] = round($final["RealMoney"]["Amount"], 2);
        $final["RealMoney"]["AmountWin"] = round($final["RealMoney"]["AmountWin"], 2);
        $final["RealMoney"]["GGR"] = round($final["RealMoney"]["GGR"], 2);


        $final["Players"] = [];
        $final["Players"]["Total"] = $value->{".usuarios"};
        $final["Players"]["Amount"] = $value->{".usuarios"};
        $final["Progress"] = [];
        $final["Progress"]["Total"] = round($porc, 2);
        $final["Progress"]["Amount"] = round($porc, 2);

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Result"] = $final;
        $response["Data"] = $final;


        break;

    /**
     * GetBonusPlayersV2
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
    case 'GetBonusPlayersV2':

        $params = file_get_contents('php://input');
        $params = json_decode($params);

        $ToDateLocal = $params->ResultToDate;
        $FromDateLocal = $params->ResultFromDate;
        //$EndDate =str_replace(" ", "T", $params->EndDate) ;
        //$BeginDate =str_replace(" ", "T", $params->BeginDate);

        $BonusDefinitionIds = $params->BonusDefinitionIds;
        $PlayerExternalId = $params->PlayerExternalId;
        $Code = $params->Code;
        $ExternalId = $params->ExternalId;
        $ResultTypeId = $params->ResultTypeId;

        switch ($ResultTypeId) {
            case 1:
                $ResultTypeId = 'A';
                break;
            case 2:
                $ResultTypeId = 'I';
                break;
            case 3:
                $ResultTypeId = 'E';
                break;
            case 4:
                $ResultTypeId = 'R';
                break;
            default:
                $ResultTypeId = '';
                break;
        }

        $MaxRows = $params->Limit;
        $OrderedItem = $params->OrderedItem;
        $SkeepRows = ($params->Offset) * $MaxRows;

        $IdBonus = $_REQUEST["IdBonus"];

        $OrderedItem = "usuario_bono.usubono_id";
        $OrderType = "desc";

        $IdBonus = $params->IdBonus;

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
                case "Id":
                    $OrderedItem = "usuario_bono.usubono_id";
                    $OrderType = $item->dir;
                    break;

                case "Date":
                    $OrderedItem = "usuario_bono.fecha_crea";
                    $OrderType = $item->dir;
                    break;

                case "PlayerExternalId":
                    $OrderedItem = "usuario_bono.usuario_id";
                    $OrderType = $item->dir;
                    break;

                case "AmountBonus":
                    $OrderedItem = "usuario_bono.valor";
                    $OrderType = $item->dir;

                    break;

                case "AmountBase":
                    $OrderedItem = "usuario_bono.valor_base";
                    $OrderType = $item->dir;
                    break;

            }

        }


        $rules = [];

        array_push($rules, array("field" => "bono_interno.bono_id", "data" => "$IdBonus", "op" => "eq"));

        if ($PlayerExternalId != "") {
            array_push($rules, array("field" => "usuario_bono.usuario_id", "data" => "$PlayerExternalId", "op" => "eq"));
        }


        if ($Code != "") {
            array_push($rules, array("field" => "usuario_bono.codigo", "data" => "$Code", "op" => "eq"));
        }


        if ($ExternalId != "") {
            array_push($rules, array("field" => "usuario_bono.externo_id", "data" => "$ExternalId", "op" => "eq"));
        }


        if ($ResultTypeId != "") {
            array_push($rules, array("field" => "usuario_bono.estado", "data" => "$ResultTypeId", "op" => "eq"));
        }


        if ($BeginDate != "") {
            array_push($rules, array("field" => "usuario_bono.fecha_crea", "data" => "$BeginDate", "op" => "ge"));
        }
        if ($EndDate != "") {
            array_push($rules, array("field" => "usuario_bono.fecha_crea", "data" => "$EndDate", "op" => "le"));
        }


        // Si el usuario esta condicionado por País
        if ($_SESSION['PaisCond'] == "S") {
        }
        // Si el usuario esta condicionado por el mandante y no es de Global
        if ($_SESSION['Global'] == "N") {
            array_push($rules, array("field" => "bono_interno.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
        }


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


        $UsuarioBono = new UsuarioBono();
        $data = $UsuarioBono->getUsuarioBonosCustom("usuario_bono.*,usuario.nombre", $OrderedItem, $OrderType, $SkeepRows, $MaxRows, $json, true, '');


        $data = json_decode($data);

        $final = [];

        foreach ($data->data as $key => $value) {

            $array = [];
            $array["Id"] = $value->{"usuario_bono.usubono_id"};
            $array["PlayerExternalId"] = $value->{"usuario_bono.usuario_id"};
            $array["PlayerName"] = $value->{"usuario.nombre"};
            $array["Amount"] = $value->{"usuario_bono.valor"};
            $array["AmountBase"] = $value->{"usuario_bono.valor_base"};
            $array["AmountBonus"] = $value->{"usuario_bono.valor_bono"};
            $array["Code"] = $value->{"usuario_bono.codigo"};
            $array["AmountToWager"] = $value->{"usuario_bono.rollower_requerido"};
            $array["WageredAmount"] = $value->{"usuario_bono.apostado"};
            $array["Date"] = $value->{"usuario_bono.fecha_crea"};
            $array["ExternalId"] = $value->{"usuario_bono.externo_id"};

            switch ($value->{"usuario_bono.estado"}) {
                case "A":
                    $array["ResultTypeId"] = 1;
                    break;

                case "E":
                    $array["ResultTypeId"] = 3;
                    break;

                case "R":
                    $array["ResultTypeId"] = 4;
                    break;

                case "I":
                    $array["ResultTypeId"] = 2;
                    break;

            }


            array_push($final, $array);
        }

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Result"] = $final;
        $response["Data"] = $final;
        $response["Count"] = $data->count[0]->{".count"};


        break;

    /**
     * GetTournamentPlayers
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
    case 'GetTournamentPlayers':

        $params = file_get_contents('php://input');
        $params = json_decode($params);

        $ToDateLocal = $params->ResultToDate;
        $FromDateLocal = $params->ResultFromDate;
        $BonusDefinitionIds = $params->BonusDefinitionIds;
        $PlayerExternalId = $params->PlayerExternalId;

        $MaxRows = $params->Limit;
        $OrderedItem = $params->OrderedItem;
        $SkeepRows = ($params->Offset) * $MaxRows;

        $OrderedItem = "usuario_torneo.valor";
        $OrderType = "desc";

        $Id = $_REQUEST["Id"];
        $Id = $params->Id;

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
                case "Position":
                    $OrderedItem = "position.position";
                    $OrderType = $item->dir;
                    break;

                case "PlayerExternalId":
                    $OrderedItem = "usuario_torneo.usuario_id";
                    $OrderType = $item->dir;
                    break;

                case "PlayerName":
                    $OrderedItem = "usuario_mandante.nombres";
                    $OrderType = $item->dir;
                    break;

                case "Amount":
                    $OrderedItem = "usuario_torneo.valor";
                    $OrderType = $item->dir;

                    break;

                case "AmountBase":
                    $OrderedItem = "usuario_torneo.valor_base";
                    $OrderType = $item->dir;
                    break;

                case "AmountWin":
                    $OrderedItem = "usuario_torneo.valor_premio";
                    $OrderType = $item->dir;
                    break;

                case "GGR":
                    $OrderedItem = "usuario_torneo.valor_base - usuario_torneo.valor_premio";
                    $OrderType = $item->dir;
                    break;
            }

        }

        if ($start != "") {
            $SkeepRows = $start;

        }

        if ($length != "") {
            $MaxRows = $length;

        }

        $rules = [];

        if ($PlayerExternalId != "") {
            array_push($rules, array("field" => "usuario_mandante.usuario_mandante", "data" => $PlayerExternalId, "op" => "eq"));

        }

        array_push($rules, array("field" => "torneo_interno.torneo_id", "data" => "$Id", "op" => "eq"));


        // Si el usuario esta condicionado por País
        if ($_SESSION['PaisCond'] == "S") {
        }
        // Si el usuario esta condicionado por el mandante y no es de Global
        if ($_SESSION['Global'] == "N") {
            array_push($rules, array("field" => "torneo_interno.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
        }


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


        $UsuarioTorneo = new UsuarioTorneo();
        $data = $UsuarioTorneo->getUsuarioTorneosCustom("usuario_torneo.*,usuario_mandante.nombres,position.position", $OrderedItem, $OrderType, $SkeepRows, $MaxRows, $json, true, '');


        $data = json_decode($data);

        $final = [];

        $pos = 1;
        foreach ($data->data as $key => $value) {

            $array = [];
            $array["Position"] = $value->{"position.position"};
            $array["Id"] = $value->{"usuario_torneo.usutorneo_id"};
            $array["PlayerExternalId"] = $value->{"usuario_torneo.usuario_id"};
            $array["PlayerName"] = $value->{"usuario_mandante.nombres"};
            $array["Amount"] = $value->{"usuario_torneo.valor"};
            $array["AmountBase"] = $value->{"usuario_torneo.valor_base"};
            $array["AmountWin"] = $value->{"usuario_torneo.valor_premio"};
            $array["GGR"] = ($value->{"usuario_torneo.valor_base"} - $value->{"usuario_torneo.valor_premio"});

            $array["Amount"] = round($array["Amount"], 2);
            $array["AmountBase"] = round($array["AmountBase"], 2);
            $array["AmountWin"] = round($array["AmountWin"], 2);
            $array["GGR"] = round($array["GGR"], 2);


            $array["Code"] = $value->{"usuario_torneo.codigo"};
            $array["AmountToWager"] = $value->{"usuario_torneo.rollower_requerido"};
            $array["WageredAmount"] = $value->{"usuario_torneo.apostado"};
            $array["Date"] = $value->{"usuario_torneo.fecha_crea"};
            $array["ExternalId"] = $value->{"usuario_torneo.externo_id"};

            switch ($value->{"usuario_torneo.estado"}) {
                case "A":
                    $array["ResultTypeId"] = 1;
                    break;

                case "E":
                    $array["ResultTypeId"] = 3;
                    break;

                case "R":
                    $array["ResultTypeId"] = 4;
                    break;

            }

            array_push($final, $array);
            $pos++;
        }

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Result"] = $final;
        $response["Data"] = $final;
        $response["Count"] = intval($data->count[0]->{".count"});


        break;

    /**
     * GetBetsRollower
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
    case "GetBetsRollower":

        $params = file_get_contents('php://input');
        $params = json_decode($params);

        $ToDateLocal = $params->ResultToDate;
        $FromDateLocal = $params->ResultFromDate;
        $BonusDefinitionIds = $params->BonusDefinitionIds;
        $PlayerExternalId = $params->PlayerExternalId;

        $MaxRows = $params->Limit;
        $OrderedItem = $params->OrderedItem;
        $SkeepRows = ($params->Offset) * $MaxRows;

        $Id = $_REQUEST["Id"];

        $OrderedItem = "it_ticket_enc_info1.it_ticket2_id";
        $OrderType = "desc";

        $Id = $_REQUEST["Id"];
        $Id = $params->Id;

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


        $UsuarioBono = new UsuarioBono($Id);


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


        $rules = [];
        //array_push($rules, array("field" => "it_ticket_enc.estado", "data" => "I", "op" => "eq"));
        //array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_cierre,' ',it_ticket_enc.hora_cierre)", "data" => "$FromDateLocal ", "op" => "ge"));
        //array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_cierre,' ',it_ticket_enc.hora_cierre)", "data" => "$ToDateLocal", "op" => "le"));
        array_push($rules, array("field" => "it_ticket_enc.usuario_id", "data" => $UsuarioBono->usuarioId, "op" => "eq"));
        array_push($rules, array("field" => "it_ticket_enc_info1.valor", "data" => $UsuarioBono->usubonoId, "op" => "eq"));
        array_push($rules, array("field" => "it_ticket_enc_info1.tipo", "data" => "ROLLOWER", "op" => "eq"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json2 = json_encode($filtro);

        $ItTicketEncInfo1 = new ItTicketEncInfo1();

        $tickets = $ItTicketEncInfo1->getTicketsCustom(" it_ticket_enc_info1.*,it_ticket_enc.vlr_apuesta,it_ticket_enc.fecha_cierre,it_ticket_enc.fecha_crea ", $OrderedItem, $OrderType, $SkeepRows, $MaxRows, $json2, true);

        $tickets = json_decode($tickets);

        $final = [];

        foreach ($tickets->data as $key => $value) {

            $array = [];

            $array["Id"] = ($value->{"it_ticket_enc_info1.it_ticket2_id"});
            $array["TicketId"] = ($value->{"it_ticket_enc_info1.ticket_id"});
            $array["Valor"] = ($value->{"it_ticket_enc_info1.valor"});
            $array["Amount"] = ($value->{"it_ticket_enc.vlr_apuesta"});
            $array["DateCreate"] = ($value->{"it_ticket_enc.fecha_crea"});
            $array["DateClose"] = ($value->{"it_ticket_enc.fecha_cierre"});

            array_push($final, $array);
        }

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Result"] = $final;
        $response["Data"] = $final;
        $response["Count"] = $tickets->count[0]->{".count"};


        break;

    /**
     * GetBetsTournament
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
    case "GetBetsTournament":

        $params = file_get_contents('php://input');
        $params = json_decode($params);

        $ToDateLocal = $params->ResultToDate;
        $FromDateLocal = $params->ResultFromDate;
        $BonusDefinitionIds = $params->BonusDefinitionIds;
        $PlayerExternalId = $params->PlayerExternalId;

        $search = $params->search;

        $MaxRows = $params->Limit;
        $OrderedItem = $params->OrderedItem;
        $SkeepRows = ($params->Offset) * $MaxRows;

        $Id = $_REQUEST["Id"];

        $Id = $params->Id;

        $draw = $params->draw;
        $length = $params->length;
        $start = $params->start;

        if ($start != "") {
            $SkeepRows = $start;

        }

        if ($length != "") {
            $MaxRows = $length;

        }

        $UsuarioTorneo = new UsuarioTorneo($Id);
        $TorneoInterno = new TorneoInterno($UsuarioTorneo->torneoId);
        $UsuarioMandante = new UsuarioMandante($UsuarioTorneo->usuarioId);


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


        if ($TorneoInterno->tipo == 1) {
            $rules = [];

            if ($search->value != "") {
                array_push($rules, array("field" => "it_ticket_enc.ticket_id", "data" => $search->value, "op" => "eq"));

            }
            //array_push($rules, array("field" => "it_ticket_enc.estado", "data" => "I", "op" => "eq"));
            //array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_cierre,' ',it_ticket_enc.hora_cierre)", "data" => "$FromDateLocal ", "op" => "ge"));
            //array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_cierre,' ',it_ticket_enc.hora_cierre)", "data" => "$ToDateLocal", "op" => "le"));
            // array_push($rules, array("field" => "it_ticket_enc.usuario_id", "data" => $UsuarioMandante->usuario_mandante, "op" => "eq"));
            array_push($rules, array("field" => "it_ticket_enc_info1.valor", "data" => $UsuarioTorneo->usutorneoId, "op" => "eq"));
            array_push($rules, array("field" => "it_ticket_enc_info1.tipo", "data" => "TORNEO", "op" => "eq"));


            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json2 = json_encode($filtro);

            $ItTicketEncInfo1 = new ItTicketEncInfo1();

            $tickets = $ItTicketEncInfo1->getTicketsCustom(" it_ticket_enc_info1.*,it_ticket_enc.vlr_apuesta,it_ticket_enc.fecha_cierre,it_ticket_enc.fecha_crea ", "it_ticket_enc_info1.it_ticket2_id", "asc", $SkeepRows, $MaxRows, $json2, true);

            $tickets = json_decode($tickets);

            $final = [];

            foreach ($tickets->data as $key => $value) {

                $array = [];

                $array["Id"] = ($value->{"it_ticket_enc_info1.it_ticket2_id"});
                $array["Type"] = 0;
                $array["TicketId"] = ($value->{"it_ticket_enc_info1.ticket_id"});
                $array["Valor"] = ($value->{"it_ticket_enc_info1.valor"});
                $array["Amount"] = ($value->{"it_ticket_enc_info1.valor2"});
                $array["AmountBase"] = ($value->{"it_ticket_enc.vlr_apuesta"});
                $array["DateCreate"] = ($value->{"it_ticket_enc.fecha_crea"});
                $array["DateClose"] = ($value->{"it_ticket_enc.fecha_cierre"});

                array_push($final, $array);
            }


            $json = json_encode($filtro);
        } elseif ($TorneoInterno->tipo == 2) {
            $rules = [];
            //array_push($rules, array("field" => "it_ticket_enc.estado", "data" => "I", "op" => "eq"));
            //array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_cierre,' ',it_ticket_enc.hora_cierre)", "data" => "$FromDateLocal ", "op" => "ge"));
            //array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_cierre,' ',it_ticket_enc.hora_cierre)", "data" => "$ToDateLocal", "op" => "le"));
            array_push($rules, array("field" => "transaccion_api.usuario_id", "data" => $UsuarioMandante->usumandanteId, "op" => "eq"));
            array_push($rules, array("field" => "transjuego_info.descripcion", "data" => $UsuarioTorneo->usutorneoId, "op" => "eq"));
            array_push($rules, array("field" => "transjuego_info.tipo", "data" => "TORNEO", "op" => "eq"));


            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json2 = json_encode($filtro);

            $TransjuegoInfo = new TransjuegoInfo();

            $tickets = $TransjuegoInfo->getTransjuegoInfosCustom(" transjuego_info.*,transaccion_api.valor,transaccion_api.identificador ", "transjuego_info.transjuegoinfo_id", "asc", $SkeepRows, $MaxRows, $json2, true, '');

            $tickets = json_decode($tickets);

            $final = [];

            foreach ($tickets->data as $key => $value) {

                $array = [];

                $array["Id"] = ($value->{"transjuego_info.transjuegoinfo_id"});
                $array["Type"] = 1;
                $array["TicketId"] = ($value->{"transaccion_api.identificador"});
                $array["Valor"] = ($value->{"transjuego_info.valor"});
                $array["Amount"] = ($value->{"transjuego_info.valor"});
                $array["AmountBase"] = ($value->{"transaccion_api.valor"});

                $array["DateCreate"] = ($value->{"transjuego_info.fecha_crea"});
                $array["DateClose"] = ($value->{"transjuego_info.fecha_crea"});

                array_push($final, $array);
            }
        }


        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Result"] = $final;
        $response["Data"] = $final;
        $response["Count"] = $tickets->count[0]->{".count"};


        break;

    /**
     * GetBonusResults
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
    case "GetBonusResults":
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Result"] = array();


        break;

    /**
     * GetClients
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
    case "GetClients":

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
        $json = '{"rules" : [{"field" : "usuario_perfil.perfil_id", "data" : "USUONLINE","op":"eq"}] ,"groupOp" : "AND"}';

        $usuarios = $Usuario->getUsuariosCustom("  DISTINCT (usuario.usuario_id),usuario.fecha_ult,usuario.moneda,usuario.idioma,usuario.dir_ip,usuario.login,usuario.estado,usuario.estado_esp,usuario.pais_id,usuario.fecha_crea,registro.cedula,registro.sexo,registro.ciudad_id,registro.nombre1,registro.nombre2,registro.apellido1,registro.email,registro.direccion,registro.telefono,registro.celular,registro.codigo_postal,registro.ciudnacim_id,registro.paisnacim_id,c.*,g.* ", "usuario.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true);

        $usuarios = json_decode($usuarios);

        $usuariosFinal = [];

        foreach ($usuarios->data as $key => $value) {

            $array = [];

            $array["Id"] = $value->{"usuario.usuario_id"};
            $array["Ip"] = $value->{"ausuario.dir_ip"};
            $array["Login"] = $value->{"usuario.login"};
            $array["Estado"] = array($value->{"usuario.estado"});
            $array["EstadoEspecial"] = $value->{"usuario.estado_esp"};
            $array["Idioma"] = $value->{"a.idioma"};
            $array["Nombre"] = $value->{"a.nombre"};
            $array["FirstName"] = $value->{"registro.nombre1"};
            $array["MiddleName"] = $value->{"registro.nombre2"};
            $array["LastName"] = $value->{"registro.apellido1"};
            $array["Email"] = $value->{"registro.email"};
            $array["Address"] = $value->{"registro.direccion"};
            $array["TipoUsuario"] = $value->{"usuario_perfil.perfil_id"};
            $array["Intentos"] = $value->{"usuario.intentos"};
            $array["Observaciones"] = $value->{"usuario.observ"};
            $array["Moneda"] = $value->{"usuario.moneda"};

            $array["Pais"] = $value->{"usuario.pais_id"};
            $array["City"] = $value->{"g.ciudad_nom"};

            $array["CreatedLocalDate"] = $value->{"usuario.fecha_crea"};

            $array["IsLocked"] = false;
            $array["BirthCity"] = $value->{"registro.ciudnacim_id"};
            $array["BirthDate"] = $value->{"c.fecha_nacim"};

            $array["BirthDepartment"] = $value->{"g.depto_id"};
            $array["BirthRegionCode2"] = $value->{"registro.paisnacim_id"};
            $array["BirthRegionId"] = $value->{"registro.paisnacim_id"};

            $array["CurrencyId"] = $value->{"usuario.moneda"};
            $array["DocNumber"] = $value->{"registro.cedula"};
            $array["Gender"] = $value->{"registro.sexo"};
            $array["Language"] = $value->{"usuario.idioma"};
            $array["Phone"] = $value->{"registro.telefono"};
            $array["MobilePhone"] = $value->{"registro.celular"};
            $array["LastLoginLocalDate"] = $value->{"usuario.fecha_ult"};
            $array["Province"] = $value->{"registro.ciudad_id"};
            $array["RegionId"] = $value->{"usuario.pais_id"};
            $array["CountryName"] = $value->{"usuario.pais_id"};
            $array["ZipCode"] = $value->{"registro.codigo_postal"};
            $array["IsVerified"] = true;
            array_push($usuariosFinal, $array);

        }

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Data"] = array(
            "Objects" => $usuariosFinal,
            "Count" => $usuarios->count[0]->{".count"},

        );

        break;

    /**
     * GetUsers
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
    case 'GetUsers':

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
            $MaxRows = 10;
        }

        $usuarios = $Usuario->getUsuarios("A", "", "a.usuario_id", "asc", $SkeepRows, $MaxRows);

        $usuarios = json_decode($usuarios);

        $usuariosFinal = [];

        foreach ($usuarios->data as $key => $value) {

            $array = [];

            $array["Id"] = $value->{"a.usuario_id"};
            $array["Ip"] = $value->{"a.dir_ip"};
            $array["Login"] = $value->{"a.login"};
            $array["Estado"] = array($value->{"a.estado"});
            $array["EstadoEspecial"] = $value->{"a.estado_esp"};
            $array["PermiteRecargas"] = $value->{".permite_recarga"};
            $array["ImprimeRecibo"] = $value->{".recibo_caja"};
            $array["Pais"] = $value->{"a.pais_id"};
            $array["Idioma"] = $value->{"a.idioma"};
            $array["Nombre"] = $value->{"a.nombre"};
            $array["FirstName"] = $value->{"a.nombre"};
            $array["TipoUsuario"] = $value->{"e.perfil_id"};
            $array["Intentos"] = $value->{"a.intentos"};
            $array["Observaciones"] = $value->{"a.observ"};
            $array["PinAgent"] = $value->{".pinagent"};
            $array["BloqueoVentas"] = $value->{"a.bloqueo_ventas"};
            $array["Moneda"] = $value->{"a.moneda"};
            $array["ActivarRecarga"] = $value->{"a.permite_activareg"};
            $array["City"] = $value->{"g.ciudad_nom"};
            $array["Phone"] = $value->{"f.telefono"};
            $array["FechaCrea"] = $value->{"a.fecha_crea"};
            $array["LastLoginLocalDate"] = $value->{"a.fecha_crea"};
            $array["FechaCrea"] = $value->{".fecha_ult"};
            $array["IsLocked"] = false;

            array_push($usuariosFinal, $array);

        }

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Data"] = array(
            "Objects" => $usuariosFinal,
            "Count" => $usuarios->count[0]->{".count"},

        );

        break;

    /**
     * GetReportColumns
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
    case 'GetReportColumns':
        $ReportName = $_GET["reportName"];

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "success";
        $response["ModelErrors"] = [];

        if ($ReportName == "PlayerInfo") {
            $response["Data"] = array(
                "RegionId", "Id", "Ip", "Clave", "Estado", "EstadoEspecial", "PermiteRecargas", "ImprimeRecibo", "Pais", "Idioma", "Nombre", "TipoUsuario", "Intentos", "Observaciones", "PinAgent", "BloqueoVentas", "Moneda", "ActivarRecarga", "Login", "FirstName", "LastName", "PersonalId", "Email", "AffilateId", "BTag", "IsSubscribeToEmail", "IsSubscribeToSMS", "ExternalId", "AccountHolder", "Address", "BirthCity", "BirthDate", "BirthDepartment", "BirthRegionCode2", "BirthRegionId", "CashDesk", "CreatedLocalDate", "CurrencyId", "DocIssueCode", "DocIssueDate", "DocIssuedBy", "DocNumber", "Gender", "PartnerName", "City", "IBAN", "RFId", "BTag", "IsUsingLoyaltyProgram", "LoyaltyLevelId", "TimeZone", "IsLoggedIn", "IsResident", "IsSubscribedToNewsletter", "IsTest", "IsVerified", "Language", "LastLoginLocalDate", "MiddleName", "MobilePhone", "Phone", "ProfileId", "PromoCode", "Province", "CountryName", "RegistrationSource", "SportsbookProfileId", "SwiftCode", "Title", "ZipCode", "IsLocked",

            );
        }
        if ($ReportName == "PlayerTables") {
            $response["Data"] = array(
                "Id", "Ip", "Clave", "Estado", "EstadoEspecial", "PermiteRecargas", "ImprimeRecibo", "Pais", "Idioma", "Nombre", "TipoUsuario", "Intentos", "Observaciones", "PinAgent", "BloqueoVentas", "Moneda", "ActivarRecarga", "Login", "FirstName", "LastName", "PersonalId", "Email", "AffilateId", "BTag", "IsSubscribeToEmail", "IsSubscribeToSMS", "ExternalId", "AccountHolder", "Address", "BirthCity", "BirthDate", "BirthDepartment", "BirthRegionCode2", "BirthRegionId", "CashDesk", "CreatedLocalDate", "CurrencyId", "DocIssueCode", "DocIssueDate", "DocIssuedBy", "DocNumber", "Gender", "PartnerName", "City", "IBAN", "RFId", "BTag", "IsUsingLoyaltyProgram", "LoyaltyLevelId", "TimeZone", "IsLoggedIn", "IsResident", "IsSubscribedToNewsletter", "IsTest", "IsVerified", "Language", "LastLoginLocalDate", "MiddleName", "MobilePhone", "Phone", "ProfileId", "PromoCode", "Province", "CountryName", "RegistrationSource", "SportsbookProfileId", "SwiftCode", "Title", "ZipCode", "IsLocked",

            );
        }
        if ($ReportName == "PlayersTable") {
            $response["Data"] = array(
                "Id", "Ip", "Clave", "Estado", "EstadoEspecial", "PermiteRecargas", "ImprimeRecibo", "Pais", "Idioma", "Nombre", "TipoUsuario", "Intentos", "Observaciones", "PinAgent", "BloqueoVentas", "Moneda", "ActivarRecarga", "Login", "FirstName", "LastName", "PersonalId", "Email", "AffilateId", "BTag", "IsSubscribeToEmail", "IsSubscribeToSMS", "ExternalId", "AccountHolder", "Address", "BirthCity", "BirthDate", "BirthDepartment", "BirthRegionCode2", "BirthRegionId", "CashDesk", "CreatedLocalDate", "CurrencyId", "DocIssueCode", "DocIssueDate", "DocIssuedBy", "DocNumber", "Gender", "PartnerName", "City", "IBAN", "RFId", "BTag", "IsUsingLoyaltyProgram", "LoyaltyLevelId", "TimeZone", "IsLoggedIn", "IsResident", "IsSubscribedToNewsletter", "IsTest", "IsVerified", "Language", "LastLoginLocalDate", "MiddleName", "MobilePhone", "Phone", "ProfileId", "PromoCode", "Province", "CountryName", "RegistrationSource", "SportsbookProfileId", "SwiftCode", "Title", "ZipCode", "IsLocked",

            );
        }

        if ($ReportName == "DashboardSettings") {
            $response["Data"] = array(
                "ActivePlayersToday", "NewRegistrationToday", "SportsByStakes", "TopFiveGames", "SportBets", "CasinoBets", "TopFiveMatches", "TopFiveCasinoPlayers",

            );
        }
        if ($ReportName == "DepositReportSettings") {
            $response["Data"] = array(
                "Id", "ClientId", "CreatedLocal", "TypeName", "CurrencyId", "ModifiedLocal", "PaymentSystemName", "CashDeskId", "State", "ExternalId", "Amount",

            );
        }

        break;

    /**
     * GetFilters
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
    case 'GetFilters':

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "success";
        $response["ModelErrors"] = [];

        $response["Data"] = array(
            "RegionFilter", "CountryFilter", "CurrencyFilter",
        );

        break;

    /**
     * GetClientById
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
    case 'GetClientById':

        $Usuario = new Usuario();

        $params = file_get_contents('php://input');
        $params = json_decode($params);
        $id = $_GET["id"];

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
            $MaxRows = 10;
        }
        $json = '{"rules" : [{"field" : "usuario.usuario_id", "data": "' . $id . '","op":"eq"}] ,"groupOp" : "AND"}';

        $usuarios = $Usuario->getUsuariosCustom("  DISTINCT (usuario.usuario_id),usuario.fecha_ult,usuario.moneda,usuario.idioma,usuario.dir_ip,usuario.login,usuario.estado,usuario.estado_esp,usuario.pais_id,usuario.fecha_crea,registro.cedula,registro.sexo,registro.ciudad_id,registro.nombre1,registro.nombre2,registro.apellido1,registro.email,registro.direccion,registro.telefono,registro.celular,registro.codigo_postal,registro.ciudnacim_id,registro.paisnacim_id,c.*,g.* ", "usuario.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true);

        $usuarios = json_decode($usuarios);

        $usuariosFinal = [];

        foreach ($usuarios->data as $key => $value) {

            $array = [];

            $array["Id"] = $value->{"usuario.usuario_id"};
            $array["Ip"] = $value->{"ausuario.dir_ip"};
            $array["Login"] = $value->{"usuario.login"};
            $array["Estado"] = array($value->{"usuario.estado"});
            $array["EstadoEspecial"] = $value->{"usuario.estado_esp"};
            $array["Idioma"] = $value->{"a.idioma"};
            $array["Nombre"] = $value->{"a.nombre"};
            $array["FirstName"] = $value->{"registro.nombre1"};
            $array["MiddleName"] = $value->{"registro.nombre2"};
            $array["LastName"] = $value->{"registro.apellido1"};
            $array["Email"] = $value->{"registro.email"};
            $array["Address"] = $value->{"registro.direccion"};
            $array["TipoUsuario"] = $value->{"usuario_perfil.perfil_id"};
            $array["Intentos"] = $value->{"usuario.intentos"};
            $array["Observaciones"] = $value->{"usuario.observ"};
            $array["Moneda"] = $value->{"usuario.moneda"};

            $array["Pais"] = $value->{"usuario.pais_id"};
            $array["City"] = $value->{"g.ciudad_nom"};

            $array["CreatedLocalDate"] = $value->{"usuario.fecha_crea"};

            $array["IsLocked"] = false;
            $array["BirthCity"] = $value->{"registro.ciudnacim_id"};
            $array["BirthDate"] = $value->{"c.fecha_nacim"};

            $array["BirthDepartment"] = $value->{"g.depto_id"};
            $array["BirthRegionCode2"] = $value->{"registro.paisnacim_id"};
            $array["BirthRegionId"] = $value->{"registro.paisnacim_id"};

            $array["CurrencyId"] = $value->{"usuario.moneda"};
            $array["DocNumber"] = $value->{"registro.cedula"};
            $array["Gender"] = $value->{"registro.sexo"};
            $array["Language"] = $value->{"usuario.idioma"};
            $array["Phone"] = $value->{"registro.telefono"};
            $array["MobilePhone"] = $value->{"registro.celular"};
            $array["LastLoginLocalDate"] = $value->{"usuario.fecha_ult"};
            $array["Province"] = $value->{"registro.ciudad_id"};
            $array["RegionId"] = $value->{"usuario.pais_id"};
            $array["CountryName"] = $value->{"usuario.pais_id"};
            $array["ZipCode"] = $value->{"registro.codigo_postal"};
            $array["IsVerified"] = true;
            $usuariosFinal = $array;

        }

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Data"] = $usuariosFinal;

        break;

    /**
     * GetBetShopById
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
    case "GetBetShopById":

        $params = file_get_contents('php://input');
        $params = json_decode($params);

        $id = $_REQUEST["id"];

        $Mandante = new Mandante();

        if ($SkeepRows == "") {
            $SkeepRows = 0;
        }

        if ($OrderedItem == "") {
            $OrderedItem = 1;
        }

        if ($MaxRows == "") {
            $MaxRows = 100000;
        }

        $json = '{"rules" : [{"field" : "punto_venta.puntoventa_id", "data": ' . $id . ',"op":"eq"},{"field" : "usuario_perfil.perfil_id", "data": "PUNTOVENTA","op":"eq"}] ,"groupOp" : "AND"}';

        $mandantes = $Mandante->getPuntosVentaTree("mandante.mandante", "asc", $SkeepRows, $MaxRows, $json, true);

        $mandantes = json_decode($mandantes);

        $final = [];

        foreach ($mandantes->data as $key => $value) {

            $array = [];

            $array["Id"] = $value->{"punto_venta.puntoventa_id"};
            $array["Name"] = $value->{"punto_venta.descripcion"};
            $array["CurrencyId"] = $value->{"usuario.moneda"};
            $array["Type"] = $value->{"tipo_punto.descripcion"};
            $array["MinBet"] = $value->{"usuario_premiomax.apuesta_min"};
            $array["PreMatchPercentage"] = $value->{"punto_venta.porcen_comision"};
            $array["LivePercentage"] = $value->{"punto_venta.porcen_comision"};
            $array["RecargasPercentage"] = $value->{"punto_venta.porcen_comision2"};
            $final = $array;

        }

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Data"] = $final;

        break;

        break;

    /**
     * GetCashDesks
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
    case "GetCashDesks":

        $params = file_get_contents('php://input');
        $params = json_decode($params);

        $id = $params->BetShopId;

        $Mandante = new Mandante();

        if ($SkeepRows == "") {
            $SkeepRows = 0;
        }

        if ($OrderedItem == "") {
            $OrderedItem = 1;
        }

        if ($MaxRows == "") {
            $MaxRows = 100000;
        }

        $json = '{"rules" : [{"field" : "punto_venta.puntoventa_id", "data": ' . $id . ',"op":"eq"},{"field" : "usuario_perfil.perfil_id", "data": "PUNTOVENTA","op":"eq"}] ,"groupOp" : "AND"}';

        $mandantes = $Mandante->getPuntosVentaTree("mandante.mandante", "asc", $SkeepRows, $MaxRows, $json, true);

        $mandantes = json_decode($mandantes);

        $final = [];

        foreach ($mandantes->data as $key => $value) {

            $array = [];

            $array["Id"] = $value->{"punto_venta.puntoventa_id"};
            $array["Name"] = $value->{"punto_venta.descripcion"};
            $array["CurrencyId"] = $value->{"usuario.moneda"};
            $array["Type"] = $value->{"tipo_punto.descripcion"};
            $array["MinBet"] = $value->{"usuario_premiomax.apuesta_min"};
            $array["PreMatchPercentage"] = $value->{"punto_venta.porcen_comision"};
            $array["LivePercentage"] = $value->{"punto_venta.porcen_comision"};
            $array["RecargasPercentage"] = $value->{"punto_venta.porcen_comision2"};
            array_push($final, $array);

        }

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Data"] = $final;

        break;

    /**
     * GetPartnerAdminUsers
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
    case "GetPartnerAdminUsers":

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $final = [];

        $array = [];

        $array["Id"] = 56575;
        $array["Name"] = "Daniel";
        $array["Adress"] = null;
        $array["AgentId"] = null;
        $array["CashDeskId"] = null;
        $array["CashDeskName"] = null;
        $array["CreatedLocalDate"] = "2018-01-13T17:03:13.024";
        $array["EMail"] = "danielftg@hotmail.com";
        $array["FirstName"] = "Daniel";
        $array["Hired"] = "0001-01-01T00:00:00";
        $array["IsAgent"] = false;
        $array["IsGiven"] = false;
        $array["IsQRCodeSent"] = false;
        $array["IsSuspended"] = false;
        $array["IsTwoFactorEnabled"] = false;
        $array["LastName"] = "Tqammaa";
        $array["PartnerId"] = 123213213123;
        $array["Password"] = null;
        $array["UserName"] = "danielftg@hotmail.com";

        array_push($final, $array);


        $response["Data"] = $final;

        break;

    /**
     * GetPartnerAdminUserById
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
    case "GetPartnerAdminUserById":

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $final = [];

        $array = [];

        $array["Id"] = 56575;
        $array["Name"] = "Daniel";
        $array["Adress"] = null;
        $array["AgentId"] = null;
        $array["CashDeskId"] = null;
        $array["CashDeskName"] = null;
        $array["CreatedLocalDate"] = "2018-01-13T17:03:13.024";
        $array["EMail"] = "danielftg@hotmail.com";
        $array["FirstName"] = "Daniel";
        $array["Hired"] = "0001-01-01T00:00:00";
        $array["IsAgent"] = false;
        $array["IsGiven"] = false;
        $array["IsQRCodeSent"] = false;
        $array["IsSuspended"] = false;
        $array["IsTwoFactorEnabled"] = false;
        $array["LastName"] = "Tqammaa";
        $array["PartnerId"] = 123213213123;
        $array["Password"] = null;
        $array["UserName"] = "danielftg@hotmail.com";

        array_push($final, $array);


        $response["Data"] = $array;

        break;

    /**
     * GetUserChangeHistoryTypes
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
    case "GetUserChangeHistoryTypes":

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $final = [];

        $array = [];

        $array["Fieldname"] = "FirstName";
        $array["FieldTranslation"] = "_FirstNameLabel_";
        array_push($final, $array);

        $array["Fieldname"] = "LastName";
        $array["FieldTranslation"] = "_FirstNameLabel_";
        array_push($final, $array);

        $array["Fieldname"] = "Address";
        $array["FieldTranslation"] = "_AddressLabel_";
        array_push($final, $array);

        $array["Fieldname"] = "PasswordHash";
        $array["FieldTranslation"] = "_PasswordLabel_";
        array_push($final, $array);

        $array["Fieldname"] = "EMail";
        $array["FieldTranslation"] = "_EmailLabel_";
        array_push($final, $array);

        $array["Fieldname"] = "Phone";
        $array["FieldTranslation"] = "_PhoneLabel_";
        array_push($final, $array);

        $array["Fieldname"] = "IsSuspended";
        $array["FieldTranslation"] = "_IsSuspendedLabel_";
        array_push($final, $array);


        $response["Data"] = $array;

        break;

    /**
     * GetPartnerAdminUsersByFilter
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
    case "GetPartnerAdminUsersByFilter":

        $params = file_get_contents('php://input');
        $params = json_decode($params);

        $id = $params->CashDeskId;

        $PuntoVenta = new PuntoVenta();

        if ($SkeepRows == "") {
            $SkeepRows = 0;
        }

        if ($OrderedItem == "") {
            $OrderedItem = 1;
        }

        if ($MaxRows == "") {
            $MaxRows = 100000;
        }

        $json = '{"rules" : [{"field" : "punto_venta.puntoventa_id", "data": ' . $id . ',"op":"eq"}] ,"groupOp" : "AND"}';

        $usuarios = $PuntoVenta->getUsuariosTree("usuario.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true);

        $usuarios = json_decode($usuarios);

        $final = [];

        foreach ($usuarios->data as $key => $value) {

            $array = [];

            $array["Id"] = $value->{"usuario.usuario_id"};
            $array["Name"] = $value->{"usuario.nombre"};
            array_push($final, $array);

        }

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Data"] = $final;

        break;

    /**
     * GetPartnerAdminUserById
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
    case "GetPartnerAdminUserById":

        $params = file_get_contents('php://input');
        $params = json_decode($params);

        $id = $_REQUEST["userId"];

        $Usuario = new Usuario($id);

        $final = [];

        $final["Id"] = $Usuario->usuarioId;
        $final["Name"] = $Usuario->nombre;
        $final["UserName"] = $Usuario->login;
        $final["Name"] = $Usuario->nombre;

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Data"] = $final;

        break;

    /**
     * GetCashDeskById
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
    case "GetCashDeskById";

        $params = file_get_contents('php://input');
        $params = json_decode($params);

        $id = $_REQUEST["id"];

        $PuntoVenta = new PuntoVenta($id);

        $final = [];

        $final["Id"] = $PuntoVenta->puntoventaId;
        $final["Name"] = $PuntoVenta->descripcion;

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Data"] = $final;

        break;

    /**
     * GetBetShops
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
    case "GetBetShops":

        $params = file_get_contents('php://input');
        $params = json_decode($params);

        $Mandante = new Mandante();

        if ($SkeepRows == "") {
            $SkeepRows = 0;
        }

        if ($OrderedItem == "") {
            $OrderedItem = 1;
        }

        if ($MaxRows == "") {
            $MaxRows = 100000;
        }

        $json = '{"rules" : [{"field" : "usuario_perfil.perfil_id", "data": "PUNTOVENTA","op":"eq"}] ,"groupOp" : "AND"}';

        $mandantes = $Mandante->getPuntosVentaTree("mandante.mandante", "asc", $SkeepRows, $MaxRows, $json, true);

        $mandantes = json_decode($mandantes);

        $final = [];

        foreach ($mandantes->data as $key => $value) {

            $array = [];

            $array["Id"] = $value->{"punto_venta.puntoventa_id"};
            $array["Name"] = $value->{"punto_venta.descripcion"};
            $array["Phone"] = $value->{"punto_venta.telefono"};
            $array["Email"] = $value->{"usuario.email"};
            $array["CityName"] = $value->{"ciudad.ciudad_nom"};
            $array["DepartmentName"] = $value->{"departamento.depto_nom"};
            $array["RegionName"] = $value->{"pais.pais_nom"};
            $array["CurrencyId"] = $value->{"usuario.moneda"};
            $array["Address"] = $value->{"punto_venta.direccion"};
            $array["CreatedDate"];
            $array["LastLoginDateLabel"];

            $array["Type"] = $value->{"tipo_punto.descripcion"};
            $array["MinBet"] = $value->{"usuario_premiomax.apuesta_min"};
            $array["PreMatchPercentage"] = $value->{"punto_venta.porcen_comision"};
            $array["LivePercentage"] = $value->{"punto_venta.porcen_comision"};
            $array["RecargasPercentage"] = $value->{"punto_venta.porcen_comision2"};

            array_push($final, $array);

        }

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Data"] = $final;

        break;

    /**
     * GetProducts
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
    case "GetProducts":

        $Producto = new Producto();

        $SkeepRows = 0;
        $MaxRows = 1000000;

        $json = '{"rules" : [{"field" : "a.usuario_id", "data": "1","op":"eq"}] ,"groupOp" : "AND"}';

        $productos = $Producto->getProductos("producto.producto_id", "asc", $SkeepRows, $MaxRows, $json, false);

        $productos = json_decode($productos);

        $final = [];

        foreach ($productos->data as $key => $value) {

            $array = [];

            $array["Id"] = $value->{"producto.producto_id"};
            $array["Name"] = $value->{"producto.descripcion"};

            array_push($final, $array);

        }

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Data"] = $final;

        break;

    /**
     * GetProductProviders
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
    case "GetProductProviders":

        $Proveedor = new Proveedor();
        $Proveedor->setTipo("CASINO");

        $proveedores = $Proveedor->getProveedores();

        $final = [];

        foreach ($proveedores as $key => $value) {

            $array = [];

            $array["Id"] = $value->getProveedorId();
            $array["Name"] = $value->getDescripcion();

            array_push($final, $array);

        }

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Data"] = $final;

        break;

    /**
     * GetProductProviders
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
    case "GetProductProviders":

        $Proveedor = new Proveedor();
        $Proveedor->setTipo("CASINO");

        $proveedores = $Proveedor->getProveedores();

        $final = [];

        foreach ($proveedores as $key => $value) {

            $array = [];

            $array["Id"] = $value->getProveedorId();
            $array["Name"] = $value->getDescripcion();

            array_push($final, $array);

        }

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Data"] = $final;

        break;

    /**
     * GetCasinoGamesReport
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
    case 'GetCasinoGamesReport':

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
            $MaxRows = 10000;
        }

        $TransaccionJuego = new TransaccionJuego();
        $data = $TransaccionJuego->getTransacciones("transaccion_juego.transjuego_id", "asc", $SkeepRows, $MaxRows, "", false);

        $data = json_decode($data);

        $final = [];

        foreach ($data->data as $key => $value) {

            $array = [];

            $array["Game"] = $value->{"producto.descripcion"};
            $array["ProviderName"] = $value->{"proveedor.descripcion"};
            $array["Bets"] = $value->{"producto.descripcion"};
            $array["Stakes"] = $value->{"transaccion_juego.valor_ticket"};
            $array["Winnings"] = $value->{"transaccion_juego.valor_premio"};
            $array["Profit"] = 0;
            $array["BonusCashBack"] = 0;

            array_push($final, $array);

        }

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Data"] = $final;

        break;

    /**
     * GetAgentSystems
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
    case "GetAgentSystems":

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $array = [];

        $array["UserName"] = "T ";
        $array["SystemName"] = "T";
        $array["FirstName"] = "T";
        $array["FirstName"] = "T";
        $array["Phone"] = "T";
        $array["LastLoginLocalDate"] = "T";
        $array["LastLoginIp"] = "T";
        $final = [];
        array_push($final, $array);

        $response["Data"] = $final;

        break;

    /**
     * GetRegions
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
    case "GetRegions":
        $SportId = $params->SportId;
        $sportId = $_REQUEST["sportId"];


        if ($SportId != "") {
            $BeginDate = $params->BeginDate;
            $EndDate = $params->EndDate;

            $regions = getRegions($SportId, $BeginDate, $EndDate);

            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "Operation has completed successfuly";
            $response["ModelErrors"] = [];
            $response["Data"] = $regions;

        } elseif ($sportId != "") {

            $json = '{"rules" : [{"field" : "int_region.deporte_id", "data" : "' . $sportId . '","op":"eq"}] ,"groupOp" : "AND"}';


            $IntRegion = new IntRegion();
            $regiones = $IntRegion->getRegionesCustom(" int_deporte.*,int_region.* ", "int_region.region_id", "asc", 0, 10000, $json, true);
            $regiones = json_decode($regiones);


            $final = array();

            foreach ($regiones->data as $region) {

                $array = array();
                $array["Id"] = $region->{"int_region.region_id"};
                $array["Name"] = $region->{"int_region.nombre"};

                array_push($final, $array);

            }
            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "Operation has completed successfuly";
            $response["ModelErrors"] = [];

            $response["Data"] = $final;


        } else {
            $Pais = new Pais();

            $SkeepRows = 0;
            $MaxRows = 1000000;

            $json = '{"rules" : [{"field" : "pais.estado", "data": "A","op":"eq"}] ,"groupOp" : "AND"}';

            $paises = $Pais->getPaises("pais_moneda.pais_id,ciudad.ciudad_id", "asc", $SkeepRows, $MaxRows, $json, true);

            $paises = json_decode($paises);

            $final = [
                array(
                    "Id" => "",
                    "Name" => "All",
                    "currencies" => array(
                        "Id" => "",
                        "Name" => "All",
                    ),
                    "departments" => array(
                        "Id" => "",
                        "Name" => "All",
                    )
                )
            ];
            $arrayf = [];
            $monedas = [];

            $ciudades = [];
            $departamentos = [];

            foreach ($paises->data as $key => $value) {

                $array = [];

                $array["Id"] = $value->{"pais.pais_id"};
                $array["Name"] = $value->{"pais.pais_nom"};

                $departamento_id = $value->{"departamento.depto_id"};
                $departamento_texto = $value->{"departamento.depto_nom"};

                $ciudad_id = $value->{"ciudad.ciudad_id"};
                $ciudad_texto = $value->{"ciudad.ciudad_nom"};

                if ($array["Id"] != $arrayf["Id"] && $arrayf["Id"] != "") {

                    $arrayf["currencies"] = array_unique($monedas);
                    $arrayf["departments"] = $departamentos;
                    array_push($final, $arrayf);

                    $arrayf = [];
                    $monedas = [];
                    $departamentos = [];
                    $ciudades = [];

                }

                $arrayf["Id"] = $value->{"pais.pais_id"};
                $arrayf["Name"] = $value->{"pais.pais_nom"};

                $moneda = [];
                $moneda["Id"] = $value->{"pais_moneda.paismoneda_id"};
                $moneda["Name"] = $value->{"pais_moneda.moneda"};

                array_push($monedas, $moneda);

                if ($departamento_idf != $departamento_id && $departamento_idf != "") {

                    $departamento = [];
                    $departamento["Id"] = $departamento_idf;
                    $departamento["Name"] = $departamento_textof;
                    $departamento["cities"] = $ciudades;

                    array_push($departamentos, $departamento);

                    $ciudades = [];

                    $ciudad = [];
                    $ciudad["Id"] = $ciudad_id;
                    $ciudad["Name"] = $ciudad_texto;

                    array_push($ciudades, $ciudad);

                } else {
                    $ciudad = [];
                    $ciudad["Id"] = $ciudad_id;
                    $ciudad["Name"] = $ciudad_texto;

                    array_push($ciudades, $ciudad);
                }

                $departamento_idf = $value->{"departamento.depto_id"};
                $departamento_textof = $value->{"departamento.depto_nom"};

            }

            $departamento = [];
            $departamento["Id"] = $departamento_idf;
            $departamento["Name"] = $departamento_textof;
            $departamento["cities"] = $ciudades;

            array_push($departamentos, $departamento);

            $ciudades = [];

            array_push($monedas, $moneda);
            $arrayf["currencies"] = array_unique($monedas);
            $arrayf["departments"] = $departamentos;

            array_push($final, $arrayf);

            $regiones = $final;

            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "";
            $response["ModelErrors"] = [];

            $response["Data"] = $regiones;
        }
        break;

    /**
     * GetActiveClients
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
    case "GetActiveClients":

        $ItTicketEnc = new ItTicketEnc();

        $params = file_get_contents('php://input');
        $params = json_decode($params);

        $ToDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->ToDateLocal) . ' +1 day'));
        $FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->FromDateLocal)));
        $Region = $params->Region;
        $Currency = $params->Currency;


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
            $MaxRows = 1000000000;
        }

        $rules = [];
        array_push($rules, array("field" => "it_ticket_enc.estado", "data" => "I", "op" => "eq"));
        array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_cierre,' ',it_ticket_enc.hora_cierre)", "data" => "$FromDateLocal ", "op" => "ge"));
        array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_cierre,' ',it_ticket_enc.hora_cierre)", "data" => "$ToDateLocal", "op" => "le"));

        if ($Region != "") {
            array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
        }

        if ($Currency != "") {
            array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
        }

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $ItTicketEnc = new ItTicketEnc();
        $tickets = $ItTicketEnc->getTicketsCustom(" COUNT( DISTINCT (it_ticket_enc.usuario_id) ) count  ", "it_ticket_enc.it_ticket_id", "asc", $SkeepRows, $MaxRows, $json, true);
        $tickets = json_decode($tickets);

        $NumeroJugadoresTickets = $tickets->data[0]->{".count"};


        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Data"] = $NumeroJugadoresTickets;

        break;

    /**
     * GetNewRegisteredClients
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
    case "GetNewRegisteredClients":

        $Usuario = new Usuario();

        $params = file_get_contents('php://input');
        $params = json_decode($params);

        $MaxCreatedLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->MaxCreatedLocal) . ' +1 day'));
        $MinCreatedLocal = $params->MinCreatedLocal;
        $Region = $params->Region;
        $Currency = $params->Currency;

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
            $MaxRows = 10;
        }

        $rules = [];
        array_push($rules, array("field" => "usuario.fecha_crea", "data" => "$MinCreatedLocal ", "op" => "ge"));
        array_push($rules, array("field" => "usuario.fecha_crea", "data" => "$MaxCreatedLocal", "op" => "le"));

        if ($Region != "") {
            array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
        }

        if ($Currency != "") {
            array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
        }

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);


        $usuarios = $Usuario->getUsuariosCustom(" COUNT(*) count ", "usuario.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true);

        $usuarios = json_decode($usuarios);

        $usuariosFinal = [];

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Data"] = $usuarios->data[0]->{".count"};

        break;

    /**
     * GetDepositSummary2
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
    case "GetDepositSummary2":

        $Usuario = new Usuario();

        $params = file_get_contents('php://input');
        $params = json_decode($params);

        $MaxCreatedLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->MaxCreatedLocal) . ' +1 day'));
        $MinCreatedLocal = $params->MinCreatedLocal;


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
            $MaxRows = 10;
        }

        $json = '{"rules" : [{"field" : "a.fecha_crea", "data": "' . $MinCreatedLocal . '","op":"ge"},{"field" : "a.fecha_crea", "data": "' . $MaxCreatedLocal . '","op":"le"}] ,"groupOp" : "AND"}';

        $usuarios = $Usuario->getUsuarios("A", "", "a.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true);

        $usuarios = json_decode($usuarios);

        $usuariosFinal = [];

        foreach ($usuarios->data as $key => $value) {

            $array = [];

            $array["Id"] = $value->{"a.usuario_id"};
            $array["Ip"] = $value->{"a.dir_ip"};
            $array["Login"] = $value->{"a.login"};
            $array["Estado"] = array($value->{"a.estado"});
            $array["EstadoEspecial"] = $value->{"a.estado_esp"};
            $array["PermiteRecargas"] = $value->{".permite_recarga"};
            $array["ImprimeRecibo"] = $value->{".recibo_caja"};
            $array["Pais"] = $value->{"a.pais_id"};
            $array["Idioma"] = $value->{"a.idioma"};
            $array["Nombre"] = $value->{"a.nombre"};
            $array["FirstName"] = $value->{"a.nombre"};
            $array["TipoUsuario"] = $value->{"e.perfil_id"};
            $array["Intentos"] = $value->{"a.intentos"};
            $array["Observaciones"] = $value->{"a.observ"};
            $array["PinAgent"] = $value->{".pinagent"};
            $array["BloqueoVentas"] = $value->{"a.bloqueo_ventas"};
            $array["Moneda"] = $value->{"a.moneda"};
            $array["ActivarRecarga"] = $value->{"a.permite_activareg"};
            $array["City"] = $value->{"g.ciudad_nom"};
            $array["Phone"] = $value->{"f.telefono"};
            $array["FechaCrea"] = $value->{"a.fecha_crea"};
            $array["LastLoginLocalDate"] = $value->{"a.fecha_crea"};
            $array["FechaCrea"] = $value->{".fecha_ult"};
            $array["IsLocked"] = false;

            array_push($usuariosFinal, $array);

        }

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Data"] = $usuarios->count[0]->{".count"};

        break;

    /**
     * GetDepositSummary
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
    case "GetDepositSummary":

        $UsuarioRecarga = new UsuarioRecarga();

        $params = file_get_contents('php://input');
        $params = json_decode($params);

        $ToDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->ToDateLocal) . ' +1 day'));
        $FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->FromDateLocal)));

        $Region = $params->Region;
        $Currency = $params->Currency;

        $MaxRows = $params->MaxRows;
        $OrderedItem = $params->OrderedItem;
        $SkeepRows = $params->SkeepRows;

        $rules = [];

        array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
        array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));

        if ($Region != "") {
            array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
        }

        if ($Currency != "") {
            array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
        }


        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        if ($SkeepRows == "") {
            $SkeepRows = 0;
        }

        if ($OrderedItem == "") {
            $OrderedItem = 1;
        }

        if ($MaxRows == "") {
            $MaxRows = 10000000;
        }


        if ($Region != "") {
            $usuarios = $UsuarioRecarga->getUsuarioRecargasCustom("SUM(usuario_recarga.valor) valor", "usuario_recarga.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true);

            $usuarios = json_decode($usuarios);

            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "";
            $response["ModelErrors"] = [];

            $response["Data"] = array(
                "DepositTotalCount" => $usuarios->count[0]->{".count"},
                "DepositTotalAmount" => $usuarios->data[0]->{".valor"},

            );

        } else {

            $usuarios = $UsuarioRecarga->getUsuarioRecargasCustom("COUNT(*) count,SUM(usuario_recarga.valor) valor,usuario.moneda", "usuario_recarga.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true, "usuario.moneda");

            $usuarios = json_decode($usuarios);
            setlocale(LC_ALL, 'czech');

            $valor_convertido = 0;
            $total = 0;
            foreach ($usuarios->data as $key => $value) {

                $converted_currency = currencyConverter($value->{"usuario.moneda"}, 'EUR', round($value->{".valor"}, 0));
                $valor_convertido = $valor_convertido + $converted_currency;
                $total = $total + $value->{".count"};

            }

            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "";
            $response["ModelErrors"] = [];

            $response["Data"] = array(
                "DepositTotalCount" => $total,
                "DepositTotalAmount" => $valor_convertido,

            );

        }

        break;

    /**
     * GetNewRegisteredClientsDepositSummary
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
    case "GetNewRegisteredClientsDepositSummary":

        $UsuarioRecarga = new UsuarioRecarga();

        $params = file_get_contents('php://input');
        $params = json_decode($params);

        $ToDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->ToDateLocal) . ' +1 day'));
        $FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->FromDateLocal)));
        $Region = $params->Region;
        $Currency = $params->Currency;

        $MaxRows = $params->MaxRows;
        $OrderedItem = $params->OrderedItem;
        $SkeepRows = $params->SkeepRows;

        $rules = [];

        array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
        array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
        array_push($rules, array("field" => "usuario.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
        array_push($rules, array("field" => "usuario.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));

        if ($Region != "") {
            array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
        }

        if ($Currency != "") {
            array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
        }

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);


        if ($SkeepRows == "") {
            $SkeepRows = 0;
        }

        if ($OrderedItem == "") {
            $OrderedItem = 1;
        }

        if ($MaxRows == "") {
            $MaxRows = 10000000;
        }

        if ($Region != "") {
            $usuarios = $UsuarioRecarga->getUsuarioRecargasCustom("SUM(usuario_recarga.valor) valor", "usuario_recarga.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true);

            $usuarios = json_decode($usuarios);

            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "";
            $response["ModelErrors"] = [];

            $response["Data"] = array(
                "DepositTotalCount" => $usuarios->count[0]->{".count"},
                "DepositTotalAmount" => $usuarios->data[0]->{".valor"},

            );

        } else {

            $usuarios = $UsuarioRecarga->getUsuarioRecargasCustom("COUNT(*) count,SUM(usuario_recarga.valor) valor,usuario.moneda", "usuario_recarga.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true, "usuario.moneda");

            $usuarios = json_decode($usuarios);
            setlocale(LC_ALL, 'czech');

            $valor_convertido = 0;
            $total = 0;
            foreach ($usuarios->data as $key => $value) {

                $converted_currency = currencyConverter($value->{"usuario.moneda"}, 'EUR', round($value->{".valor"}, 0));
                $valor_convertido = $valor_convertido + $converted_currency;
                $total = $total + $value->{".count"};

            }

            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "";
            $response["ModelErrors"] = [];

            $response["Data"] = array(
                "DepositTotalCount" => $total,
                "DepositTotalAmount" => $valor_convertido,

            );

        }


        break;

    /**
     * GetWithDrawalSummary
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
    case "GetWithDrawalSummary":

        $CuentaCobro = new CuentaCobro();

        $params = file_get_contents('php://input');
        $params = json_decode($params);

        $ToDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->ToDateLocal) . ' +1 day'));
        $FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->FromDateLocal)));
        $Region = $params->Region;
        $Currency = $params->Currency;
        $IsNewRegistered = $params->IsNewRegistered;

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
            $MaxRows = 10;
        }

        $rules = [];
        array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "I", "op" => "eq"));
        array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => "$FromDateLocal ", "op" => "ge"));
        array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => "$ToDateLocal", "op" => "le"));

        if ($Region != "") {
            array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
        }

        if ($Currency != "") {
            array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
        }

        if ($IsNewRegistered) {
            array_push($rules, array("field" => "usuario.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
            array_push($rules, array("field" => "usuario.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));

        }

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        if ($Region != "") {

            $cuentas = $CuentaCobro->getCuentasCobroCustom("SUM(cuenta_cobro.valor) valor", "cuenta_cobro.cuenta_id", "asc", $SkeepRows, $MaxRows, $json, true);

            $cuentas = json_decode($cuentas);

            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "";
            $response["ModelErrors"] = [];

            $response["Data"] = array(
                "WithDrawalTotalCount" => $cuentas->count[0]->{".count"},
                "WithDrawalTotalAmount" => $cuentas->data[0]->{".valor"},

            );
        } else {
            $cuentas = $CuentaCobro->getCuentasCobroCustom("COUNT(*) count,SUM(cuenta_cobro.valor) valor,usuario.moneda", "cuenta_cobro.cuenta_id", "asc", $SkeepRows, $MaxRows, $json, true, "usuario.moneda");

            $cuentas = json_decode($cuentas);

            $valor_convertido = 0;
            $total = 0;
            foreach ($cuentas->data as $key => $value) {

                $converted_currency = currencyConverter($value->{"usuario.moneda"}, 'EUR', round($value->{".valor"}, 0));
                $valor_convertido = $valor_convertido + $converted_currency;
                $total = $total + $value->{".count"};

            }

            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "";
            $response["ModelErrors"] = [];

            $response["Data"] = array(
                "WithDrawalTotalCount" => $total,
                "WithDrawalTotalAmount" => $valor_convertido,

            );
        }

        break;

    /**
     * GetCasinoBetSummary
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
    case "GetCasinoBetSummary":

        $TransaccionJuego = new TransaccionJuego();

        $params = file_get_contents('php://input');
        $params = json_decode($params);

        $ToDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->ToDateLocal) . ' +1 day'));
        $FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->FromDateLocal)));
        $Region = $params->Region;
        $Currency = $params->Currency;

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
            $MaxRows = 10;
        }

        $json = '{"rules" : [{"field" : "transaccion_juego.estado", "data" : "I","op":"eq"},{"field" : "transaccion_juego.fecha_modif", "data": "' . $FromDateLocal . '","op":"ge"},{"field" : "transaccion_juego.fecha_modif", "data": "' . $ToDateLocal . '","op":"le"}] ,"groupOp" : "AND"}';

        $transacciones = $TransaccionJuego->getTransaccionesCustom(" SUM(transaccion_juego.valor_ticket) apuestas, SUM(CASE WHEN transaccion_juego.premiado = 'S' THEN transaccion_juego.valor_premio ELSE 0 END) premios  ", "transaccion_juego.transjuego_id", "asc", $SkeepRows, $MaxRows, $json, true);

        $transacciones = json_decode($transacciones);

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Data"] = array(
            "BetAmount" => $transacciones->data[0]->{".apuestas"},
            "WinningAmount" => $transacciones->data[0]->{".premios"},

        );

        break;

    /**
     * GetSportBetSummary
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
    case "GetSportBetSummary":

        $ItTicketEnc = new ItTicketEnc();

        $params = file_get_contents('php://input');
        $params = json_decode($params);

        $ToDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->ToDateLocal) . ' +1 day'));
        $FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->FromDateLocal)));
        $Region = $params->Region;
        $Currency = $params->Currency;

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
            $MaxRows = 10;
        }

        $rules = [];
        array_push($rules, array("field" => "it_ticket_enc.estado", "data" => "I", "op" => "eq"));
        array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_cierre,' ',it_ticket_enc.hora_cierre)", "data" => "$FromDateLocal ", "op" => "ge"));
        array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_cierre,' ',it_ticket_enc.hora_cierre)", "data" => "$ToDateLocal", "op" => "le"));

        if ($Region != "") {
            array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
        }

        if ($Currency != "") {
            array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
        }

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        if ($Region != "") {

            $tickets = $ItTicketEnc->getTicketsCustom(" COUNT(*) count,SUM(it_ticket_enc.vlr_apuesta) apuestas, SUM(CASE WHEN it_ticket_enc.premiado = 'S' THEN it_ticket_enc.vlr_premio ELSE 0 END) premios  ", "it_ticket_enc.it_ticket_id", "asc", $SkeepRows, $MaxRows, $json, true);

            $tickets = json_decode($tickets);

            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "";
            $response["ModelErrors"] = [];

            $response["Data"] = array(
                "BetAmount" => intval($tickets->data[0]->{".apuestas"}),
                "WinningAmount" => intval($tickets->data[0]->{".premios"}),
                "BetCount" => intval($tickets->data[0]->{".count"}),

            );

        } else {
            $tickets = $ItTicketEnc->getTicketsCustom(" usuario.moneda,COUNT(*) count,SUM(it_ticket_enc.vlr_apuesta) apuestas, SUM(CASE WHEN it_ticket_enc.premiado = 'S' THEN it_ticket_enc.vlr_premio ELSE 0 END) premios  ", "it_ticket_enc.it_ticket_id", "asc", $SkeepRows, $MaxRows, $json, true, "usuario.moneda");

            $tickets = json_decode($tickets);

            $valor_convertido_apuestas = 0;
            $valor_convertido_premios = 0;
            $total = 0;
            foreach ($tickets->data as $key => $value) {

                $converted_currency = currencyConverter($value->{"usuario.moneda"}, 'EUR', round($value->{".apuestas"}, 0));
                $valor_convertido_apuestas = $valor_convertido_apuestas + $converted_currency;
                $converted_currency = currencyConverter($value->{"usuario.moneda"}, 'EUR', round($value->{".premios"}, 0));
                $valor_convertido_premios = $valor_convertido_premios + $converted_currency;

                $total = $total + $value->{".count"};

            }

            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "";
            $response["ModelErrors"] = [];

            $response["Data"] = array(
                "BetAmount" => intval($valor_convertido_apuestas),
                "WinningAmount" => intval($valor_convertido_premios),
                "BetCount" => $total,

            );
        }

        break;

    /**
     * GetDashboardResume
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
    case "GetDashboardResume":

        $UsuarioRecarga = new UsuarioRecarga();

        $params = file_get_contents('php://input');
        $params = json_decode($params);

        $ToDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->ToDateLocal) . ' +1 day'));
        $FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->FromDateLocal)));
        $Region = $params->Region;
        $Currency = $params->Currency;

        $MaxRows = $params->MaxRows;
        $OrderedItem = $params->OrderedItem;
        $SkeepRows = $params->SkeepRows;

        $rules = [];

        array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
        array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));

        if ($Region != "") {
            array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
        }

        if ($Currency != "") {
            array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
        }

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);


        if ($SkeepRows == "") {
            $SkeepRows = 0;
        }

        if ($OrderedItem == "") {
            $OrderedItem = 1;
        }

        if ($MaxRows == "") {
            $MaxRows = 10000000;
        }

        if ($Region != "") {

            $depositos = $UsuarioRecarga->getUsuarioRecargasCustom(" COUNT( DISTINCT (usuario_recarga.usuario_id) ) count, SUM(usuario_recarga.valor) depositos ", "  usuario_recarga.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true);
            $depositos = json_decode($depositos);

            $NumeroJugadoresDepositos = $depositos->data[0]->{".count"};
            $TotalDepositos = $depositos->data[0]->{".depositos"};

            $rules = [];
            array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "I", "op" => "eq"));
            array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => "$FromDateLocal ", "op" => "ge"));
            array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => "$ToDateLocal", "op" => "le"));

            if ($Region != "") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
            }

            if ($Currency != "") {
                array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
            }

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);


            $CuentaCobro = new CuentaCobro();
            $cuentas = $CuentaCobro->getCuentasCobroCustom("SUM(cuenta_cobro.valor) retiros", "cuenta_cobro.cuenta_id", "asc", $SkeepRows, $MaxRows, $json, true);

            $cuentas = json_decode($cuentas);

            $TotalRetiros = $cuentas->data[0]->{".retiros"};

            $rules = [];
            array_push($rules, array("field" => "it_ticket_enc.estado", "data" => "I", "op" => "eq"));
            array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_cierre,' ',it_ticket_enc.hora_cierre)", "data" => "$FromDateLocal ", "op" => "ge"));
            array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_cierre,' ',it_ticket_enc.hora_cierre)", "data" => "$ToDateLocal", "op" => "le"));

            if ($Region != "") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
            }

            if ($Currency != "") {
                array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
            }

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);

            $ItTicketEnc = new ItTicketEnc();
            $tickets = $ItTicketEnc->getTicketsCustom(" COUNT( DISTINCT (it_ticket_enc.usuario_id) ) count ,SUM(it_ticket_enc.vlr_apuesta) apuestas, SUM(CASE WHEN it_ticket_enc.premiado = 'S' THEN it_ticket_enc.vlr_premio ELSE 0 END) premios  ", "it_ticket_enc.it_ticket_id", "asc", $SkeepRows, $MaxRows, $json, true);
            $tickets = json_decode($tickets);

            $NumeroJugadoresTickets = $tickets->data[0]->{".count"};
            $ValorTickets = $tickets->data[0]->{".apuestas"};
            $ValorPremios = $tickets->data[0]->{".premios"};


            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "";
            $response["ModelErrors"] = [];

            $response["Data"] = array(
                "TotalPlayersByDeposit" => $NumeroJugadoresDepositos,
                "TotalPlayersByBet" => $NumeroJugadoresTickets,
                "BetPromByPlayer" => ($ValorTickets / $NumeroJugadoresTickets),
                "TotalAmountBets" => $ValorTickets,
                "TotalAmountWin" => $ValorPremios,
                "GGR" => floatval($ValorTickets - $ValorPremios),
                "TotalAmountDeposit" => $TotalDepositos,
                "TotalAmountWithDrawal" => $TotalRetiros,
                "DepositPromByPlayer" => ($TotalDepositos / $NumeroJugadoresDepositos)

            );

        } else {

            $depositos = $UsuarioRecarga->getUsuarioRecargasCustom("COUNT(DISTINCT (usuario_recarga.usuario_id)) count,SUM(usuario_recarga.valor) valor,usuario.moneda", "usuario_recarga.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true, "usuario.moneda");

            $depositos = json_decode($depositos);
            setlocale(LC_ALL, 'czech');

            $valor_convertido = 0;
            $total = 0;
            foreach ($depositos->data as $key => $value) {

                $converted_currency = currencyConverter($value->{"usuario.moneda"}, 'EUR', round($value->{".valor"}, 0));
                $valor_convertido = $valor_convertido + $converted_currency;
                $total = $total + $value->{".count"};

            }


            $NumeroJugadoresDepositos = $total;
            $TotalDepositos = $valor_convertido;

            $rules = [];
            array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "I", "op" => "eq"));
            array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => "$FromDateLocal ", "op" => "ge"));
            array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => "$ToDateLocal", "op" => "le"));

            if ($Region != "") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
            }

            if ($Currency != "") {
                array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
            }

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);


            $CuentaCobro = new CuentaCobro();

            $cuentas = $CuentaCobro->getCuentasCobroCustom("COUNT(*) count,SUM(cuenta_cobro.valor) valor,usuario.moneda", "cuenta_cobro.cuenta_id", "asc", $SkeepRows, $MaxRows, $json, true, "usuario.moneda");

            $cuentas = json_decode($cuentas);

            $valor_convertido = 0;
            $total = 0;
            foreach ($cuentas->data as $key => $value) {

                $converted_currency = currencyConverter($value->{"usuario.moneda"}, 'EUR', round($value->{".valor"}, 0));
                $valor_convertido = $valor_convertido + $converted_currency;
                $total = $total + $value->{".count"};

            }


            $TotalRetiros = $valor_convertido;

            $rules = [];
            array_push($rules, array("field" => "it_ticket_enc.estado", "data" => "I", "op" => "eq"));
            array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_cierre,' ',it_ticket_enc.hora_cierre)", "data" => "$FromDateLocal ", "op" => "ge"));
            array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_cierre,' ',it_ticket_enc.hora_cierre)", "data" => "$ToDateLocal", "op" => "le"));

            if ($Region != "") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
            }

            if ($Currency != "") {
                array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
            }

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);

            $ItTicketEnc = new ItTicketEnc();

            $tickets = $ItTicketEnc->getTicketsCustom("  usuario.moneda,COUNT(DISTINCT (it_ticket_enc.usuario_id) ) count,SUM(it_ticket_enc.vlr_apuesta) apuestas, SUM(CASE WHEN it_ticket_enc.premiado = 'S' THEN it_ticket_enc.vlr_premio ELSE 0 END) premios  ", "it_ticket_enc.it_ticket_id", "asc", $SkeepRows, $MaxRows, $json, true, "usuario.moneda");

            $tickets = json_decode($tickets);

            $valor_convertido_apuestas = 0;
            $valor_convertido_premios = 0;
            $total = 0;
            foreach ($tickets->data as $key => $value) {

                $converted_currency = currencyConverter($value->{"usuario.moneda"}, 'EUR', round($value->{".apuestas"}, 0));
                $valor_convertido_apuestas = $valor_convertido_apuestas + $converted_currency;
                $converted_currency = currencyConverter($value->{"usuario.moneda"}, 'EUR', round($value->{".premios"}, 0));
                $valor_convertido_premios = $valor_convertido_premios + $converted_currency;

                $total = $total + $value->{".count"};

            }


            $NumeroJugadoresTickets = $total;
            $ValorTickets = $valor_convertido_apuestas;
            $ValorPremios = $valor_convertido_premios;


            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "";
            $response["ModelErrors"] = [];

            $response["Data"] = array(
                "TotalPlayersByDeposit" => $NumeroJugadoresDepositos,
                "TotalPlayersByBet" => $NumeroJugadoresTickets,
                "BetPromByPlayer" => ($ValorTickets / $NumeroJugadoresTickets),
                "TotalAmountBets" => $ValorTickets,
                "TotalAmountWin" => $ValorPremios,
                "GGR" => floatval($ValorTickets - $ValorPremios),
                "TotalAmountDeposit" => $TotalDepositos,
                "TotalAmountWithDrawal" => $TotalRetiros,
                "DepositPromByPlayer" => ($TotalDepositos / $NumeroJugadoresDepositos)

            );


        }

        break;

    /**
     * GetPartnerRoles
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
    case "GetPartnerRoles":

        $Perfil = new Perfil();

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

        $json = '{"rules" : [] ,"groupOp" : "AND"}';

        $perfiles = $Perfil->getPerfilesCustom(" perfil.* ", "perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json, false);
        $perfiles = json_decode($perfiles);

        $perfilesfinal = [];

        foreach ($perfiles->data as $key => $value) {

            $array = [];

            $array["Id"] = $value->{"perfil.perfil_id"};
            $array["Name"] = $value->{"perfil.descripcion"};

            $json = '{"rules" : [{"field" : "usuario_perfil.perfil_id", "data" : "' . $array["Id"] . '","op":"eq"}] ,"groupOp" : "AND"}';

            $UsuarioPerfil = new UsuarioPerfil();
            $usuarioperfiles = $UsuarioPerfil->getUsuarioPerfilesCustom(" count(*) count ", "usuario_perfil.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true);
            $usuarioperfiles = json_decode($usuarioperfiles);
            $array["UserCount"] = $usuarioperfiles->count[0]->{".count"};

            $PerfilSubmenu = new PerfilSubmenu();
            $json = '{"rules" : [{"field" : "menu.version", "data" : "2","op":"eq"},{"field" : "perfil_submenu.perfil_id", "data" : "' . $array["Id"] . '","op":"eq"}] ,"groupOp" : "AND"}';
            $menus = $PerfilSubmenu->getPerfilSubmenusCustom(" COUNT(perfil_submenu.perfil_id) count ", "menu.menu_id", "asc", $SkeepRows, $MaxRows, $json, true);
            $menus = json_decode($menus);
            $array["PermissionCount"] = $menus->count[0]->{".count"};

            array_push($perfilesfinal, $array);
        }

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Data"] = $perfilesfinal;

        break;

    /**
     * GetGroupPermissions
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
    case "GetGroupPermissions":

        $PerfilSubmenu = new PerfilSubmenu();

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
            $MaxRows = 1000000;
        }

        $mismenus = "0";

        $json = '{"rules" : [{"field" : "menu.version", "data" : "2","op":"eq"},{"field" : "perfil_submenu.perfil_id", "data" : "' . $Perfil_id . '","op":"eq"}] ,"groupOp" : "AND"}';

        $menus = $PerfilSubmenu->getPerfilSubmenusCustom(" menu.*,submenu.*,perfil_submenu.* ", "menu.menu_id", "asc", $SkeepRows, $MaxRows, $json, true);

        $menus = json_decode($menus);

        $menus3 = [];
        $arrayf = [];
        $submenus = [];

        foreach ($menus->data as $key => $value) {

            $m = [];
            $m["Id"] = $value->{"menu.menu_id"};
            $m["Name"] = $value->{"menu.descripcion"};

            $array = [];

            $array["Id"] = $value->{"submenu.submenu_id"};
            $array["Name"] = $value->{"submenu.descripcion"};
            $array["IsGiven"] = true;
            $array["Action"] = "view";

            $mismenus = $mismenus . "," . $array["Id"];

            if ($arrayf["Id"] != "" && $m["Id"] != $arrayf["Id"]) {
                $arrayf["Permissions"] = $submenus;
                array_push($menus3, $arrayf);
                // $submenus = [];
            }

            $arrayf["Id"] = $value->{"menu.menu_id"};
            $arrayf["Name"] = $value->{"menu.descripcion"};

            array_push($submenus, $array);
        }

        $arrayf["Permissions"] = $submenus;
        array_push($menus3, $arrayf);

        $IncludedPermission = $submenus;

        $Submenu = new Submenu();

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

        $json = '{"rules" : [{"field" : "submenu.version", "data" : "2","op":"eq"}] ,"groupOp" : "AND"}';

        $menus = $Submenu->getSubMenusCustom(" menu.*,submenu.*, CASE WHEN submenu.submenu_id IN (" . $mismenus . ") THEN false ELSE true END mostrar", "menu.menu_id", "asc", $SkeepRows, $MaxRows, $json, true);

        $menus = json_decode($menus);

        $menus2 = [];
        $arrayf = [];
        $submenus = [];
        $children_final = [];

        foreach ($menus->data as $key => $value) {

            $m = [];
            $m["Id"] = $value->{"menu.menu_id"};
            $m["Name"] = $value->{"menu.descripcion"};

            $array = [];
            $children = [];

            if ($arrayf["Id"] != "" && $m["Id"] != $arrayf["Id"]) {
                $arrayf["Permissions"] = $submenus;
                $arrayf["Children"] = [];

                array_push($menus2, $arrayf);
                $submenus = [];
                $children_final = [];
            }

            $arrayf["Id"] = $value->{"menu.menu_id"};
            $arrayf["Name"] = $value->{"menu.descripcion"};

            if ($value->{".mostrar"}) {
                $array["Id"] = $value->{"submenu.submenu_id"};
                $array["Name"] = $value->{"submenu.descripcion"};
                $array["IsGiven"] = true;
                $array["Action"] = "view";
                array_push($submenus, $array);
            }
            $children["Id"] = $value->{"submenu.submenu_id"};
            $children["Name"] = $value->{"submenu.descripcion"};
            $children["IsGiven"] = true;
            $children["Action"] = "view";
            array_push($children_final, $children);
        }

        $arrayf["Permissions"] = $submenus;
        $arrayf["Children"] = [];
        $children_final = [];

        array_push($menus2, $arrayf);

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Data"] = array();

        $response["Data"]["IncludedPermission"] = $IncludedPermission;
        $response["Data"]["ExcludedPermissions"] = $menus2;

        break;

    /**
     * GetRoleById
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
    case "GetRoleById":

        $Perfil = new Perfil();

        $MaxRows = $params->MaxRows;
        $OrderedItem = $params->OrderedItem;
        $SkeepRows = $params->SkeepRows;

        $Perfil_id = $_GET["id"];

        if ($SkeepRows == "") {
            $SkeepRows = 0;
        }

        if ($OrderedItem == "") {
            $OrderedItem = 1;
        }

        if ($MaxRows == "") {
            $MaxRows = 100000;
        }

        $json = '{"rules" : [{"field" : "perfil.perfil_id", "data" : "' . $Perfil_id . '","op":"eq"}] ,"groupOp" : "AND"}';

        $perfiles = $Perfil->getPerfilesCustom(" perfil.* ", "perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json, true);
        $perfiles = json_decode($perfiles);
        $perfiles = $perfiles->data[0];

        $perfilesfinal = [];

        $perfilesfinal["Id"] = $perfiles->{"perfil.perfil_id"};
        $perfilesfinal["Name"] = $perfiles->{"perfil.descripcion"};

        $json = '{"rules" : [{"field" : "usuario_perfil.perfil_id", "data" : "' . $perfilesfinal["Id"] . '","op":"eq"}] ,"groupOp" : "AND"}';

        $UsuarioPerfil = new UsuarioPerfil();
        $usuarioperfiles = $UsuarioPerfil->getUsuarioPerfilesCustom(" count(*) count ", "usuario_perfil.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true);
        $usuarioperfiles = json_decode($usuarioperfiles);
        $perfilesfinal["UserCount"] = $usuarioperfiles->count[0]->{".count"};

        $PerfilSubmenu = new PerfilSubmenu();
        $json = '{"rules" : [{"field" : "menu.version", "data" : "2","op":"eq"},{"field" : "perfil_submenu.perfil_id", "data" : "' . $perfilesfinal["Id"] . '","op":"eq"}] ,"groupOp" : "AND"}';
        $menus = $PerfilSubmenu->getPerfilSubmenusCustom(" COUNT(perfil_submenu.perfil_id) count ", "menu.menu_id", "asc", $SkeepRows, $MaxRows, $json, true);
        $menus = json_decode($menus);
        $perfilesfinal["PermissionCount"] = $menus->count[0]->{".count"};

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Data"] = $perfilesfinal;

        break;

    /**
     * GetGroupUsers
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
    case "GetGroupUsers":

        $UsuarioPerfil = new UsuarioPerfil();

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

        $json = '{"rules" : [] ,"groupOp" : "AND"}';

        $usuarios = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.usuario_id,usuario.nombre,usuario_perfil.perfil_id ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json, false);

        $usuarios = json_decode($usuarios);
        $arrayf = [];

        foreach ($usuarios->data as $key => $value) {

            $array = [];

            $array["Id"] = $value->{"usuario.usuario_id"};
            $array["Name"] = $value->{"usuario.nombre"};
            $array["Role"] = $value->{"usuario_perfil.perfil_id"};

            if ($array["Role"] === $Perfil_id) {
                $array["IsGiven"] = true;

            } else {
                $array["IsGiven"] = false;

            }

            array_push($arrayf, $array);
        }

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Data"] = $arrayf;

        break;

    /**
     * GetClients
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
    case "GetClients":
        /*
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
        $MaxRows = 10;
        }

        $json = '{"rules" : [] ,"groupOp" : "AND"}';

        $usuarios = $Usuario->getUsuariosCustom( "usuario.","a.usuario_id", "asc", $SkeepRows, $MaxRows,$json,true);

        $usuarios = json_decode($usuarios);

        $usuariosFinal = [];

        foreach ($usuarios->data as $key => $value) {

        $array = [];

        $array["Id"] = $value->{"a.usuario_id"};
        $array["Ip"] = $value->{"a.dir_ip"};
        $array["Login"] = $value->{"a.login"};
        $array["Estado"] = array($value->{"a.estado"});
        $array["EstadoEspecial"] = $value->{"a.estado_esp"};
        $array["PermiteRecargas"] = $value->{".permite_recarga"};
        $array["ImprimeRecibo"] = $value->{".recibo_caja"};
        $array["Pais"] = $value->{"a.pais_id"};
        $array["Idioma"] = $value->{"a.idioma"};
        $array["Nombre"] = $value->{"a.nombre"};
        $array["FirstName"] = $value->{"registro.nombre1"};
        $array["MiddleName"] = $value->{"registro.nombre2"};
        $array["LastName"] = $value->{"registro.apellido1"};
        $array["Email"] = $value->{"registro.email"};
        $array["Address"] = $value->{"registro.direccion"};
        $array["TipoUsuario"] = $value->{"e.perfil_id"};
        $array["Intentos"] = $value->{"a.intentos"};
        $array["Observaciones"] = $value->{"a.observ"};
        $array["PinAgent"] = $value->{".pinagent"};
        $array["BloqueoVentas"] = $value->{"a.bloqueo_ventas"};
        $array["Moneda"] = $value->{"a.moneda"};
        $array["ActivarRecarga"] = $value->{"a.permite_activareg"};
        $array["City"] = $value->{"g.ciudad_nom"};
        $array["Phone"] = $value->{"f.telefono"};
        $array["FechaCrea"] = $value->{"a.fecha_crea"};
        $array["CreatedLocalDate"] = $value->{"a.fecha_crea"};
        $array["FechaCrea"] = $value->{".fecha_ult"};
        $array["IsLocked"] = false;
        $array["BirthCity"] = $value->{"registro.ciudnacim_id"};
        $array["BirthDate"] = $value->{"usuario_otrainfo.fecha_nacim"};

        $array["Birthday"] = $value->{".fecha_ult"};
        $array["BirthDepartment"] = $value->{"registro.ciudnacim_id"};
        $array["BirthRegionCode"] = $value->{".fecha_ult"};
        $array["BirthRegionId"] = $value->{".fecha_ult"};
        $array["CreatedLocalDate"] = $value->{"a.fecha_crea"};
        $array["CurrencyId"] = $value->{"a.moneda"};
        $array["DocNumber"] = $value->{"registro.cedula"};
        $array["Gender"] = $value->{"registro.sexo"};
        $array["Language"] = $value->{"a.idioma"};
        $array["Phone"] = $value->{"registro.telefono"};
        $array["MobilePhone"] = $value->{"registro.celular"};
        $array["LastLoginLocalDate"] = $value->{".fecha_ult"};
        $array["Province"] = $value->{"registro.ciudnacim_id"};
        $array["CountryName"] = $value->{"registro.nacionalidad_id"};
        $array["ZipCode"] = $value->{"registro.codigo_postal"};

        array_push($usuariosFinal, $array);

        }

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Data"] = array(
        "Objects" => $usuariosFinal,
        "Count" => $usuarios->count[0]->{".count"},

        );
         */
        break;

    /**
     * GetClientKpi
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
    case "GetClientKpi":

        $ItTicketEnc = new ItTicketEnc();

        $params = file_get_contents('php://input');
        $params = json_decode($params);

        $id = $_GET["id"];
        $ToDateLocal = $params->ToDateLocal;
        $FromDateLocal = $params->FromDateLocal;

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
            $MaxRows = 1000000;
        }

        $json = '{"rules" : [{"field" : "it_ticket_enc.usuario_id", "data" : "' . $id . '","op":"eq"}] ,"groupOp" : "AND"}';

        $tickets = $ItTicketEnc->getTicketsCustom(" COUNT(*) count,SUM(it_ticket_enc.vlr_apuesta) apuestas,SUM(CASE WHEN it_ticket_enc.premiado = 'S' THEN it_ticket_enc.vlr_premio ELSE 0 END) premios, SUM(CASE WHEN it_ticket_enc.estado = 'A' THEN 1 ELSE 0 END) count_sin,SUM(CASE WHEN it_ticket_enc.estado = 'A' THEN it_ticket_enc.vlr_apuesta ELSE 0 END) apuestas_sin  ", "it_ticket_enc.it_ticket_id", "asc", $SkeepRows, $MaxRows, $json, true);

        $tickets = json_decode($tickets);

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Data"] = array(
            "LastSportBetTimeLocal" => "",
            "TotalSportBets" => ($tickets->data[0]->{".count"}),
            "TotalUnsettledBets" => ($tickets->data[0]->{".count_sin"}),
            "TotalSportStakes" => ($tickets->data[0]->{".apuestas"}),
            "TotalUnsettledStakes" => ($tickets->data[0]->{".apuestas_sin"}),
            "TotalSportWinnings" => ($tickets->data[0]->{".premios"}),
            "SportProfitness" => (($tickets->data[0]->{".apuestas"}) / ($tickets->data[0]->{".premios"})),

        );

        break;

    /**
     * GetCurrencies
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
    case "GetCurrencies":
        $Pais = new Pais();

        $SkeepRows = 0;
        $MaxRows = 1000000;

        $json = '{"rules" : [{"field" : "pais.estado", "data": "A","op":"eq"}] ,"groupOp" : "AND"}';

        $paises = $Pais->getPaises("pais_moneda.pais_id,ciudad.ciudad_id", "asc", $SkeepRows, $MaxRows, $json, true);

        $paises = json_decode($paises);

        $final = [];
        $arrayf = [];
        $monedas = [];

        $ciudades = [];
        $departamentos = [];

        foreach ($paises->data as $key => $value) {

            $array = [];

            $array["Id"] = $value->{"pais.pais_id"};
            $array["Name"] = $value->{"pais.pais_nom"};

            $departamento_id = $value->{"departamento.depto_id"};
            $departamento_texto = $value->{"departamento.depto_nom"};

            $ciudad_id = $value->{"ciudad.ciudad_id"};
            $ciudad_texto = $value->{"ciudad.ciudad_nom"};

            if ($array["Id"] != $arrayf["Id"] && $arrayf["Id"] != "") {

                $arrayf["currencies"] = array_unique($monedas);
                $arrayf["departaments"] = $departamentos;
                array_push($final, $arrayf);
                array_push($monedas, $moneda);

                $arrayf = [];
                //$monedas = [];
                $departamentos = [];
                $ciudades = [];

            }

            $arrayf["Id"] = $value->{"pais.pais_id"};
            $arrayf["Name"] = $value->{"pais.pais_nom"};

            $moneda = [];
            $moneda["Id"] = $value->{"pais_moneda.moneda"};
            $moneda["Name"] = $value->{"pais_moneda.moneda"};


            if ($departamento_idf != $departamento_id && $departamento_idf != "") {

                $departamento = [];
                $departamento["Id"] = $departamento_idf;
                $departamento["Name"] = $departamento_textof;
                $departamento["cities"] = $ciudades;

                array_push($departamentos, $departamento);

                $ciudades = [];

                $ciudad = [];
                $ciudad["Id"] = $ciudad_id;
                $ciudad["Name"] = $ciudad_texto;

                array_push($ciudades, $ciudad);

            } else {
                $ciudad = [];
                $ciudad["Id"] = $ciudad_id;
                $ciudad["Name"] = $ciudad_texto;

                array_push($ciudades, $ciudad);
            }

            $departamento_idf = $value->{"departamento.depto_id"};
            $departamento_textof = $value->{"departamento.depto_nom"};

        }

        $departamento = [];
        $departamento["Id"] = $departamento_idf;
        $departamento["Name"] = $departamento_textof;
        $departamento["cities"] = $ciudades;

        array_push($departamentos, $departamento);

        $ciudades = [];

        array_push($monedas, $moneda);
        $arrayf["currencies"] = array_unique($monedas);
        $arrayf["departments"] = $departamentos;

        array_push($final, $arrayf);

        $regiones = [];

        $array["Id"] = "1";
        $array["Name"] = "America";
        $array["countries"] = $final;

        array_push($regiones, $array);

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Data"] = ($monedas);
        $response["Data"] = (unique_multidim_array2($monedas, "Id"));

        break;

    /**
     * SavePermissionsForRole
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
    case "SavePermissionsForRole":

        foreach ($params as $key => $value) {

            $Id = $value->Id;
            $Name = $value->Name;
            $IsGiven = $value->IsGiven;
            $Action = $value->Action;
            $Selected = $value->Selected;
            $PermissionId = $value->PermissionId;
            $UserId = $value->UserId;
            $role = $_REQUEST["roleId"];

            try {
                $msg = "entro4";

                $PerfilSubmenu = new PerfilSubmenu($role, $Id);

                if (!$IsGiven) {
                    $PerfilSubmenuMySqlDAO = new PerfilSubmenuMySqlDAO();
                    $PerfilSubmenuMySqlDAO->delete($PerfilSubmenu->perfilId, $PerfilSubmenu->submenuId);
                    $PerfilSubmenuMySqlDAO->getTransaction()->commit();
                    $msg = "entro5";

                }

            } catch (Exception $e) {
                $msg = "entro2";

                if ($IsGiven) {
                    $PerfilSubmenu = new PerfilSubmenu();
                    $PerfilSubmenu->perfilId = $UserId;
                    $PerfilSubmenu->submenuId = $PermissionId;
                    $PerfilSubmenu->adicionar = 'true';
                    $PerfilSubmenu->editar = 'true';
                    $PerfilSubmenu->eliminar = 'true';
                    $msg = "entro";
                    $PerfilSubmenuMySqlDAO = new PerfilSubmenuMySqlDAO();
                    $PerfilSubmenuMySqlDAO->insert($PerfilSubmenu);
                    $PerfilSubmenuMySqlDAO->getTransaction()->commit();
                }
            }
        }
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = $msg;
        $response["ModelErrors"] = [];

        $response["Data"] = [];

        break;

    /**
     * SavePermissionsForRole
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
    case "SavePermissionsForRole":

        foreach ($params as $key => $value) {

            $Id = $value->Id;
            $Name = $value->Name;
            $IsGiven = $value->IsGiven;
            $Action = $value->Action;
            $Selected = $value->Selected;
            $PermissionId = $value->PermissionId;
            $UserId = $value->UserId;
            $role = $_REQUEST["roleId"];

            try {
                $msg = "entro4";

                $PerfilSubmenu = new PerfilSubmenu($role, $Id);

                if (!$IsGiven) {
                    $PerfilSubmenuMySqlDAO = new PerfilSubmenuMySqlDAO();
                    $PerfilSubmenuMySqlDAO->delete($PerfilSubmenu->perfilId, $PerfilSubmenu->submenuId);
                    $PerfilSubmenuMySqlDAO->getTransaction()->commit();
                    $msg = "entro5";

                }

            } catch (Exception $e) {
                $msg = "entro2";

                if ($IsGiven) {
                    $PerfilSubmenu = new PerfilSubmenu();
                    $PerfilSubmenu->perfilId = $UserId;
                    $PerfilSubmenu->submenuId = $PermissionId;
                    $PerfilSubmenu->adicionar = 'true';
                    $PerfilSubmenu->editar = 'true';
                    $PerfilSubmenu->eliminar = 'true';
                    $msg = "entro";
                    $PerfilSubmenuMySqlDAO = new PerfilSubmenuMySqlDAO();
                    $PerfilSubmenuMySqlDAO->insert($PerfilSubmenu);
                    $PerfilSubmenuMySqlDAO->getTransaction()->commit();
                }
            }
        }
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = $msg;
        $response["ModelErrors"] = [];

        $response["Data"] = [];

        break;

    /**
     * GetPaymentSystems
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
    case "GetPaymentSystems":

        $Producto = new Producto();

        $SkeepRows = 0;
        $MaxRows = 1000000;

        $json = '{"rules" : [{"field" : "proveedor.tipo", "data": "PAYMENT","op":"eq"}] ,"groupOp" : "AND"}';

        $productos = $Producto->getProductos("producto.producto_id", "asc", $SkeepRows, $MaxRows, $json, true);

        $productos = json_decode($productos);

        $final = [];

        foreach ($productos->data as $key => $value) {

            $array = [];

            $array["Id"] = $value->{"producto.producto_id"};
            $array["Name"] = $value->{"producto.descripcion"};

            array_push($final, $array);

        }

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Data"] = $final;

        break;

    /**
     * GetPaymentSystemsTurnovers
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
    case "GetPaymentSystemsTurnovers":

        $TransaccionProducto = new TransaccionProducto();

        $params = file_get_contents('php://input');
        $params = json_decode($params);

        $ToDateLocal = $params->StartTimeLocal;
        $FromDateLocal = $params->EndTimeLocal;
        $TypeId = $params->TypeId;

        $FromDateLocal = $params->EndTimeLocal;
        $FromDateLocal = $params->EndTimeLocal;

        $MaxRows = $params->MaxRows;
        $OrderedItem = $params->OrderedItem;
        $SkeepRows = $params->SkeepRows;

        $rules = [];

        array_push($rules, array("field" => "transaccion_producto.estado", "data" => "I", "op" => "eq"));
        array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "A", "op" => "eq"));
        array_push($rules, array("field" => "transaccion_producto.fecha_modif", "data" => "$FromDateLocal ", "op" => "ge"));
        array_push($rules, array("field" => "transaccion_producto.fecha_modif", "data" => "$ToDateLocal", "op" => "le"));

        if ($TypeId != "") {
            array_push($rules, array("field" => "producto.producto_id", "data" => "$TypeId", "op" => "eq"));
        }

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

        $transacciones = $TransaccionProducto->getTransaccionesCustom(" transaccion_producto.*,producto.*,usuario.moneda ", "transaccion_producto.transproducto_id", "asc", $SkeepRows, $MaxRows, $json, true);

        $transacciones = json_decode($transacciones);

        $final = [];

        foreach ($transacciones->data as $key => $value) {

            $array = [];

            $array["PaymentTypeName"] = $value->{"producto.descripcion"};
            $array["AccountId"] = $value->{"transaccion_producto.usuario_id"};
            $array["Debit"] = $value->{"transaccion_producto.valor"};
            $array["Credit"] = 0;
            $array["CurrencyId"] = $value->{"usuario.moneda"};
            $array["ReciboId"] = $value->{"transaccion_producto.final_id"};

            array_push($final, $array);
        }

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Data"] = $final;

        break;

    /**
     * GetSportBetSummary
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
    case "GetSportBetSummary":

        $ItTicketEnc = new ItTicketEnc();

        $params = file_get_contents('php://input');
        $params = json_decode($params);

        $ToDateLocal = $params->ToDateLocal;
        $FromDateLocal = $params->FromDateLocal;

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
            $MaxRows = 10;
        }

        $json = '{"rules" : [{"field" : "it_ticket_enc.estado", "data" : "I","op":"eq"},{"field" : " CONCAT(it_ticket_enc.fecha_cierre,\' - \',it_ticket_enc.hora_cierre) ", "data": "' . $FromDateLocal . '","op":"ge"},{"field" : "CONCAT(it_ticket_enc.fecha_cierre,\' - \',it_ticket_enc.hora_cierre)", "data": "' . $ToDateLocal . '","op":"le"}] ,"groupOp" : "AND"}';

        $tickets = $ItTicketEnc->getTicketsCustom(" COUNT(*) count,SUM(it_ticket_enc.vlr_apuesta) apuestas, SUM(CASE WHEN it_ticket_enc.premiado = 'S' THEN it_ticket_enc.vlr_premio ELSE 0 END) premios  ", "it_ticket_enc.it_ticket_id", "asc", $SkeepRows, $MaxRows, $json, true);

        $tickets = json_decode($tickets);

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Data"] = array(
            "BetAmount" => intval($tickets->data[0]->{".apuestas"}),
            "WinningAmount" => intval($tickets->data[0]->{".premios"}),
            "BetCount" => intval($tickets->data[0]->{".count"}),

        );

        break;

    /**
     * GetDepositsWithdrawalsWithPaging
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
    case "GetDepositsWithdrawalsWithPaging":

        $UsuarioRecarga = new UsuarioRecarga();

        $params = file_get_contents('php://input');
        $params = json_decode($params);

        $ToDateLocal = $params->ToCreatedDateLocal;
        $FromDateLocal = $params->FromCreatedDateLocal;
        $PaymentSystemId = $params->PaymentSystemId;
        $CashDeskId = $params->CashDeskId;
        $ClientId = $params->ClientId;
        $AmountFrom = $params->AmountFrom;
        $AmountTo = $params->AmountTo;
        $CurrencyId = $params->CurrencyId;
        $ExternalId = $params->ExternalId;
        $Id = $params->Id;

        $MaxRows = $params->MaxRows;
        $OrderedItem = $params->OrderedItem;
        $SkeepRows = $params->SkeepRows;

        $rules = [];

        array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
        array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));

        if ($PaymentSystemId != "") {
            array_push($rules, array("field" => "producto.producto_id", "data" => "$PaymentSystemId", "op" => "eq"));
        }

        if ($CashDeskId != "") {
            array_push($rules, array("field" => "punto_venta.puntoventa_id", "data" => "$CashDeskId", "op" => "eq"));
        }
        if ($ClientId != "") {
            array_push($rules, array("field" => "usuario_recarga.usuario_id", "data" => "$ClientId", "op" => "eq"));
        }

        if ($AmountFrom != "") {
            array_push($rules, array("field" => "usuario_recarga.valor", "data" => "$AmountFrom", "op" => "ge"));
        }
        if ($AmountTo != "") {
            array_push($rules, array("field" => "usuario_recarga.valor", "data" => "$AmountTo", "op" => "le"));
        }

        if ($CurrencyId != "") {
            array_push($rules, array("field" => "usuario.moneda", "data" => "$CurrencyId", "op" => "eq"));
        }
        if ($ExternalId != "") {
            array_push($rules, array("field" => "transaccion_producto.externo_id", "data" => "$ExternalId", "op" => "eq"));
        }
        if ($Id != "") {
            array_push($rules, array("field" => "transaccion_producto.externo_id", "data" => "$Id", "op" => "eq"));
        }

        $filtro = array("rules" => $rules, "groupOp" => "AND");

        if ($SkeepRows == "") {
            $SkeepRows = 0;
        }

        if ($OrderedItem == "") {
            $OrderedItem = 1;
        }

        if ($MaxRows == "") {
            $MaxRows = 10;
        }

        $json = json_encode($filtro);

        $transacciones = $UsuarioRecarga->getUsuarioRecargasCustom(" transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true);

        $transacciones = json_decode($transacciones);

        $final = [];
        $totalm = 0;
        foreach ($transacciones->data as $key => $value) {
            $array = [];
            $totalm = $totalm + $value->{"transaccion_producto.valor"};
            if ($value->{"producto.descripcion"} == "") {

                $array["Id"] = $value->{"usuario_recarga.recarga_id"};
                $array["ClientId"] = $value->{"usuario_recarga.usuario_id"};
                $array["CreatedLocal"] = $value->{"usuario_recarga.fecha_crea"};
                $array["ModifiedLocal"] = $value->{"usuario_recarga.fecha_crea"};

                $array["Amount"] = $value->{"usuario_recarga.valor"};
                $array["PaymentSystemName"] = "Efectivo";
                $array["TypeName"] = "Payment";

                $array["CurrencyId"] = $value->{"usuario.moneda"};
                $array["CashDeskId"] = $value->{"usuario_recarga.puntoventa_id"};
                $array["State"] = "A";
                $array["Note"] = "";
                $array["ExternalId"] = "";

            } else {

                $array["Id"] = $value->{"usuario_recarga.recarga_id"};
                $array["ClientId"] = $value->{"transaccion_producto.usuario_id"};
                $array["CreatedLocal"] = $value->{"usuario_recarga.fecha_crea"};
                $array["ModifiedLocal"] = $value->{"transaccion_producto.fecha_modif"};

                $array["Amount"] = $value->{"transaccion_producto.valor"};

                $array["PaymentSystemName"] = $value->{"producto.descripcion"};
                $array["TypeName"] = "Payment";

                $array["CurrencyId"] = $value->{"usuario.moneda"};
                $array["CashDeskId"] = $value->{"usuario_recarga.puntoventa_id"};
                $array["State"] = $value->{"transaccion_producto.estado_producto"};
                $array["Note"] = "";
                $array["ExternalId"] = $value->{"transaccion_producto.externo_id"};
            }
            array_push($final, $array);
        }

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Data"] = array("Documents" => array("Objects" => $final,
            "Count" => $transacciones->count[0]->{".count"}),
            "TotalAmount" => $totalm,
        );

        break;

    /**
     * GetDocumentStates
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
    case "GetDocumentStates":

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Data"] = array(
            array("NumId" => "A", "Name" => "Aprobado"),
            array("NumId" => "R", "Name" => "Rechazado"),
            array("NumId" => "E", "Name" => "Enviado"),
        );

        break;

    /**
     * SaveClientMessages
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
    case "SaveClientMessages":
        foreach ($params as $key => $value) {

            $ClientId = $value->ClientId;
            $Message = $value->Message;
            $Title = $value->Title;

            try {
                $UsuarioMandante = new UsuarioMandante("", $ClientId, '0');
                $msg = "entro4";

                $UsuarioMensaje = new UsuarioMensaje();
                $UsuarioMensaje->usufromId = 0;
                $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                $UsuarioMensaje->isRead = 0;
                $UsuarioMensaje->body = $Message;
                $UsuarioMensaje->msubject = $Title;
                $msg = "entro5";

                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                $UsuarioMensajeMySqlDAO->getTransaction()->commit();

                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                /*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
                $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
                $data = $Usuario->getWSMessage($UsuarioToken->getRequestId());
                $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                $WebsocketUsuario->sendWSMessage();

                $msg = "entro6";

            } catch (Exception $e) {
                $msg = $e->getMessage();

            }
        }
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = $msg;
        $response["ModelErrors"] = [];

        $response["Data"] = [];

        break;

    /**
     * GetClientMessages
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
    case "GetClientMessages":
        if ($SkeepRows == "") {
            $SkeepRows = 0;
        }

        if ($OrderedItem == "") {
            $OrderedItem = 1;
        }

        if ($MaxRows == "") {
            $MaxRows = 10;
        }

        $mensajesRecibidos = [];


        $json2 = '{"rules" : [{"field" : "usuario_mensaje.usuto_id", "data": "0","op":"eq"}] ,"groupOp" : "AND"}';

        $UsuarioMensaje = new UsuarioMensaje();
        $usuarios = $UsuarioMensaje->getUsuarioMensajesCustom(" usuario_mensaje.*,usufrom.* ", "usuario_mensaje.usumensaje_id", "asc", $SkeepRows, $MaxRows, $json2, true);
        $usuarios = json_decode($usuarios);

        foreach ($usuarios->data as $key => $value) {

            $array = [];
            $array["Id"] = $value->{"usuario_mensaje.usumensaje_id"};
            $array["ClientId"] = $value->{"usuario_mensaje.usufrom_id"};
            $array["Login"] = $value->{"usuario_mensaje.usufrom_id"};
            $array["FirstName"] = $value->{"usufrom.nombres"};
            $array["LastName"] = $value->{"usufrom.apellidos"};
            if ($value->{"usuario_mensaje.is_read"} == 1) {
                $array["State"] = 2;

            } else {
                $array["State"] = 0;

            }
            $array["Title"] = $value->{"usuario_mensaje.msubject"};
            $array["Message"] = $value->{"usuario_mensaje.body"};

            array_push($mensajesRecibidos, $array);

        }

        $response = array();


        $response["data"] = array(
            "messages" => array()
        );

        $response["Data"] = $mensajesRecibidos;

        $response["code"] = 0;
        $response["rid"] = $json->rid;

        break;

    /**
     * SaveClientMessage
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
    case "SaveClientMessage":

        $ClientId = $params->ClientId;
        $Message = $params->Message;
        $Title = $params->Title;
        $ParentId = $params->ParentId;


        if ($ClientId != "") {
            try {
                $UsuarioMandante = new UsuarioMandante($ClientId);
                $msg = "entro4";

                $UsuarioMensaje2 = new UsuarioMensaje($ParentId);
                $UsuarioMensaje2->isRead = 1;

                $UsuarioMensaje = new UsuarioMensaje();
                $UsuarioMensaje->usufromId = 0;
                $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                $UsuarioMensaje->isRead = 0;
                $UsuarioMensaje->body = $Message;
                $UsuarioMensaje->msubject = $Title;
                $UsuarioMensaje->parentId = $ParentId;
                $msg = "entro5";

                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                $UsuarioMensajeMySqlDAO->update($UsuarioMensaje2);
                $UsuarioMensajeMySqlDAO->getTransaction()->commit();

                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                /*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
                $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
                $data = $Usuario->getWSMessage($UsuarioToken->getRequestId());
                $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                $WebsocketUsuario->sendWSMessage();

                $msg = "entro6";

            } catch (Exception $e) {
                $msg = $e->getMessage();

            }
            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = $msg . " - " . $ClientId;
            $response["ModelErrors"] = [];

            $response["Data"] = [];

        } else {
            $response["HasError"] = true;
            $response["AlertType"] = "error";
            $response["AlertMessage"] = "Datos incorrectos";
            $response["ModelErrors"] = [];

            $response["Data"] = [];
        }
        break;

    /**
     * GetPartnerList
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
    case "GetPartnerList":
        if ($SkeepRows == "") {
            $SkeepRows = 0;
        }

        if ($OrderedItem == "") {
            $OrderedItem = 1;
        }

        if ($MaxRows == "") {
            $MaxRows = 10;
        }

        $final = [];


        $json2 = '{"rules" : [{"field" : "usuario_mensaje.usuto_id", "data": "0","op":"eq"}] ,"groupOp" : "AND"}';


        $array = [];
        $array["Id"] = 0;
        $array["Name"] = "Doradobet";
        $array["Notes"] = "Dorado";
        $array["Domain"] = "www.doradobet.com";
        $array["SalesManagerId"] = 0;
        $array["LicenseOrigin"] = "TEST";
        $array["StatusId"] = 1;
        $array["IntegrationTypeId"] = 0;
        $array["ReleaseDate"] = "";
        $array["RegionId"] = 1;

        array_push($final, $array);

        $response = $final;


        break;

    /**
     * GetSaleList
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
    case "GetSaleList":

        if ($SkeepRows == "") {
            $SkeepRows = 0;
        }

        if ($OrderedItem == "") {
            $OrderedItem = 1;
        }

        if ($MaxRows == "") {
            $MaxRows = 10000;
        }


        $final = [];
        $ProductoMandante = new ProductoMandante();
        $json = '{"rules" : [{"field" : "", "data": "0","op":"eq"}] ,"groupOp" : "AND"}';

        $productos = $ProductoMandante->getProductosMandanteCustom(" producto_mandante.*,mandante.*,producto.* ", "producto_mandante.prodmandante_id", "asc", $SkeepRows, $MaxRows, $json, false);
        $productos = json_decode($productos);

        $final = [];

        foreach ($productos->data as $key => $value) {

            $array = [];

            $array["Id"] = $value->{"producto_mandante.prodmandante_id"};
            $array["ProviderId"] = $value->{"producto.proveedor_id"};
            $array["PartnerName"] = $value->{"mandante.descripcion"};
            $array["ProductName"] = $value->{"producto.descripcion"};
            $array["IsWorking"] = ($value->{"producto.estado"} == "A") ? true : false;
            $array["Notes"] = $value->{"producto.descripcion"};
            $array["RegionId"] = 1;

            array_push($final, $array);

        }

        $response = $final;

        break;

    /**
     * GetProductList
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
    case "GetProductList":

        $Producto = new Producto();

        $SkeepRows = 0;
        $MaxRows = 1000000;

        $json = '{"rules" : [{"field" : "a.", "data": "1","op":"eq"}] ,"groupOp" : "AND"}';

        $productos = $Producto->getProductosCustom(" producto.*,proveedor.* ", "producto.proveedor_id", "asc", $SkeepRows, $MaxRows, $json, false);

        $productos = json_decode($productos);

        $final = [];

        foreach ($productos->data as $key => $value) {

            $array = [];

            $array["Id"] = $value->{"producto.producto_id"};
            $array["CategoryId"] = 0;
            $array["ProviderId"] = $value->{"producto.proveedor_id"};
            $array["ProviderName"] = $value->{"proveedor.descripcion"};


            $array["Notes"] = $value->{"producto.descripcion"};
            array_push($final, $array);

        }


        $response = $final;


        break;

    /**
     * GetProductProviderList
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
    case "GetProductProviderList":

        $Proveedor = new Proveedor();

        $SkeepRows = 0;
        $MaxRows = 1000000;

        $json = '{"rules" : [{"field" : "", "data": "1","op":"eq"}] ,"groupOp" : "AND"}';

        $proveedores = $Proveedor->getProveedoresCustom(" proveedor.* ", "proveedor.proveedor_id", "asc", $SkeepRows, $MaxRows, $json, false);
        $proveedores = json_decode($proveedores);

        $final = [];

        foreach ($proveedores->data as $key => $value) {

            $array = [];

            $array["Id"] = $value->{"proveedor.proveedor_id"};
            $array["Name"] = $value->{"proveedor.descripcion"};

            array_push($final, $array);

        }

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response = $final;

        break;

    /**
     * GetProductCategoryList
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
    case "GetProductCategoryList":

        $Proveedor = new Proveedor();

        $SkeepRows = 0;
        $MaxRows = 1000000;

        $json = '{"rules" : [{"field" : "", "data": "1","op":"eq"}] ,"groupOp" : "AND"}';

        $proveedores = $Proveedor->getProveedoresCustom(" proveedor.* ", "proveedor.proveedor_id", "asc", $SkeepRows, $MaxRows, $json, false);
        $proveedores = json_decode($proveedores);

        $final = [];

        foreach ($proveedores->data as $key => $value) {

            $array = [];

            $array["Id"] = $value->{"proveedor.proveedor_id"};
            $array["Name"] = $value->{"proveedor.descripcion"};

            array_push($final, $array);

        }

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response = $final;


        break;

    /**
     * CreateProduct
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
    case "CreateProduct":

        $Notes = $params->Notes;
        $ProviderId = $params->ProviderId;
        $State = $params->State;
        $Verification = $params->Verification;
        $ImageUrl = $params->ImageUrl;
        $ExternalId = $params->ExternalId;
        $CategoryId = $params->CategoryId;
        $Verification = $params->Verification;


        $Producto = new Producto();

        $Producto->setDescripcion($Notes);
        $Producto->setProveedorId($ProviderId);
        $Producto->setEstado($State);
        $Producto->setImageUrl($ImageUrl);
        $Producto->setExternoId($ExternalId);
        $Producto->setVerifica($Verification);
        $Producto->setUsucreaId(0);
        $Producto->setUsumodifId(0);

        $response["ErrorCode"] = 0;
        $response["ErrorDescription"] = "success";

        try {
            $ProductoMySqlDAO = new ProductoMySqlDAO();
            $ProductoMySqlDAO->insert($Producto);
            $ProductoMySqlDAO->getTransaction()->commit();

        } catch (Exception $e) {
            $response["ErrorCode"] = $e->getCode();
            $response["ErrorDescription"] = " Ocurrio un error. Error: " . $e->getCode();

        }


        $response = $response;


        break;

    /**
     * CreateSale
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
    case "CreateSale":

        $ProductId = $params->ProductId;
        $PartnerId = $params->PartnerId;
        $State = ($params->IsWorking) ? "A" : "I";
        $Verification = ($params->IsVerification) ? "A" : "I";


        $ProductoMandante = new ProductoMandante();

        $ProductoMandante->productoId = $ProductId;
        $ProductoMandante->mandante = $PartnerId;
        $ProductoMandante->estado = $State;
        $ProductoMandante->verifica = $Verification;
        $ProductoMandante->usucreaId = 0;
        $ProductoMandante->usumodifId = 0;

        $response["ErrorCode"] = 0;
        $response["ErrorDescription"] = "success";

        try {
            $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO();
            $ProductoMandanteMySqlDAO->insert($ProductoMandante);
            $ProductoMandanteMySqlDAO->getTransaction()->commit();

        } catch (Exception $e) {
            $response["ErrorCode"] = $e->getCode();
            $response["ErrorDescription"] = " Ocurrio un error. Error: " . $e->getCode();

        }


        $response = $response;
        break;

    /**
     * GetSports
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
    case "GetSports":

        $obj = (explode("/", current(explode("?", $URI))));
        $count = oldCount($obj);
        switch ($obj[$count - 2] . "/" . $obj[$count - 1]) {
            case "Sport/GetSports":
                $BeginDate = $_REQUEST["BeginDate"];
                $EndDate = $_REQUEST["EndDate"];
                $sports = getSports($BeginDate, $EndDate);

                $response["HasError"] = false;
                $response["AlertType"] = "success";
                $response["AlertMessage"] = "Operation has completed successfuly";
                $response["ModelErrors"] = [];
                $response["Data"] = $sports;

                break;

            case "OddsFeed/GetSports":

                $json = '{"rules" : [{"field" : "", "data" : "2","op":"eq"}] ,"groupOp" : "AND"}';


                $IntDeporte = new IntDeporte();
                $sports = $IntDeporte->getDeportesCustom(" int_deporte.* ", "int_deporte.deporte_id", "asc", 0, 10000, $json, false);
                $sports = json_decode($sports);


                $final = array();

                foreach ($sports->data as $sport) {

                    $array = array();
                    $array["Id"] = $sport->{"int_deporte.deporte_id"};
                    $array["Name"] = $sport->{"int_deporte.nombre"};
                    $array["NameId"] = $sport->{"int_deporte.nombre"};

                    array_push($final, $array);

                }

                $response["Data"] = $final;


                $response["HasError"] = false;
                $response["AlertType"] = "success";
                $response["AlertMessage"] = "Operation has completed successfuly";
                $response["ModelErrors"] = [];

                break;
        }


        break;

    /**
     * GetMarketTypes
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
    case "GetMarketTypes":

        $BeginDate = $_REQUEST["BeginDate"];
        $EndDate = $_REQUEST["EndDate"];
        $sports = getMarketTypes($_REQUEST['sportId'], $BeginDate, $EndDate);

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "Operation has completed successfuly";
        $response["ModelErrors"] = [];
        $response["Data"] = $sports;


        break;

    /**
     * GetCompetitions
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
    case "GetCompetitions":

        $obj = (explode("/", current(explode("?", $URI))));
        $count = oldCount($obj);
        switch ($obj[$count - 2] . "/" . $obj[$count - 1]) {
            case "Sport/GetCompetitions":
                $BeginDate = $_REQUEST["BeginDate"];
                $EndDate = $_REQUEST["EndDate"];
                $sports = getCompetitions($_REQUEST['sportId'], $_REQUEST['regionId'], $BeginDate, $EndDate);

                $response["HasError"] = false;
                $response["AlertType"] = "success";
                $response["AlertMessage"] = "Operation has completed successfuly";
                $response["ModelErrors"] = [];
                $response["Data"] = $sports;


                break;

            case "OddsFeed/GetCompetitions":

                $sportId = $_REQUEST["sportId"];
                $regionId = $_REQUEST["regionId"];

                $json = '{"rules" : [{"field" : "int_competencia.region_id", "data" : "' . $regionId . '","op":"eq"}] ,"groupOp" : "AND"}';


                $IntCompetencia = new IntCompetencia();
                $competencias = $IntCompetencia->getCompetenciasCustom(" int_competencia.* ", "int_competencia.competencia_id", "asc", 0, 10000, $json, true);
                $competencias = json_decode($competencias);


                $final = array();

                foreach ($competencias->data as $competencia) {

                    $array = array();
                    $array["Id"] = $competencia->{"int_competencia.competencia_id"};
                    $array["Name"] = $competencia->{"int_competencia.nombre"};

                    array_push($final, $array);

                }

                $response["Data"] = $final;


                $response["HasError"] = false;
                $response["AlertType"] = "success";
                $response["AlertMessage"] = "Operation has completed successfuly";
                $response["ModelErrors"] = [];

                break;
        }


        break;

    /**
     * GetMatches
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
    case "GetMatches":
        $BeginDate = $params->BeginDate;
        $EndDate = $params->EndDate;

        $sports = getMatches($params->SportId, $params->RegionId, $params->CompetitionId, $BeginDate, $EndDate);

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "Operation has completed successfuly";
        $response["ModelErrors"] = [];
        $response["Data"] = $sports;
        break;


    default:
        # code...
        break;
}

if (json_encode($response) != "[]") {
    print_r(json_encode($response));

}

if ($URI == "/admin/dao/backapi/en/Financial/GetDepositsWithdrawalsWithPaging") {

    print_r('{"HasError":false,"AlertType":"danger","AlertMessage":"Invalid Username and/or password","ModelErrors":[],"Data":{"Documents" : {"Objects":[{"Id" : 1,"ClientId":1,"CreatedLocal":"07/07/2012 07:59:59","TypeName":1,"CurrencyId":1,"ModifiedLocal":"07/07/2012 07:59:59","PaymentSystemName":1,"CashDeskId":1,"State":1,"Note":1,"ExternalId":1,"Amount" : 1000}],"Count":1}, "ReportCurrencies" : [{"Id":"1","IsSelected":"1"}]}}');
}

if ($URI == "/admin/dao/backapi/es/Financial/GetDepositsWithdrawalsWithPaging") {

    //print_r('{"HasError":true,"AlertType":"danger","AlertMessage":"Invalid Username and/or password","ModelErrors":[],"Data":null}');
    print_r('{"HasError":false,"AlertType":"danger","AlertMessage":"Invalid Username and/or password","Objects":[],"Data":{"Documents" : {"Objects":[{"Id" : 1,"ClientId":1,"CreatedLocal":"07/07/2012 07:59:59","TypeName":1,"CurrencyId":1,"ModifiedLocal":"07/07/2012 07:59:59","PaymentSystemName":1,"CashDeskId":1,"State":1,"Note":1,"ExternalId":1,"Amount" : 1000}],"Count":1}, "ReportCurrencies" : [{"Id":"1","IsSelected":"1"}]}}');
}
if ($URI == "/admin/dao/backapi/en/Report/GetPaymentSystems") {

    print_r('{"HasError":false,"AlertType":"danger","AlertMessage":"Invalid Username and/or password","ModelErrors":[],"Data":{"Documents" : {"Objects":[{"Id" : 1,"ClientId":1,"CreatedLocal":"07/07/2012 07:59:59","TypeName":1,"CurrencyId":1,"ModifiedLocal":"07/07/2012 07:59:59","PaymentSystemName":1,"CashDeskId":1,"State":1,"Note":1,"ExternalId":1,"Amount" : 1000}],"Count":1}, "ReportCurrencies" : [{"Id":"1","IsSelected":"1"}]}}');
}

if ($URI == "/admin/dao/backapi/es/Report/GetPaymentSystems") {

    //print_r('{"HasError":true,"AlertType":"danger","AlertMessage":"Invalid Username and/or password","ModelErrors":[],"Data":null}');
    print_r('{"HasError":false,"AlertType":"danger","AlertMessage":"Invalid Username and/or password","Objects":[],"Data":{"Documents" : {"Objects":[{"Id" : 1,"ClientId":1,"CreatedLocal":"07/07/2012 07:59:59","TypeName":1,"CurrencyId":1,"ModifiedLocal":"07/07/2012 07:59:59","PaymentSystemName":1,"CashDeskId":1,"State":1,"Note":1,"ExternalId":1,"Amount" : 1000}],"Count":1}, "ReportCurrencies" : [{"Id":"1","IsSelected":"1"}]}}');
}

if ($URI == "/admin/dao/backapi/en/Financial/GetDocumentStates") {

    print_r('{"HasError":false,"AlertType":"danger","AlertMessage":"Invalid Username and/or password","ModelErrors":[],"Data": [{"NumId":"1","Name":"test"}]}');
}

if ($URI == "/admin/dao/backapi/es/Financial/GetDocumentStates") {

    //print_r('{"HasError":true,"AlertType":"danger","AlertMessage":"Invalid Username and/or password","ModelErrors":[],"Data":null}');
    print_r('{"HasError":false,"AlertType":"danger","AlertMessage":"Invalid Username and/or password","ModelErrors":[],"Data": [{"NumId":"1","Name":"test"}]}');
}
if ($URI == "/admin/dao/backapi/en/Setting/GetReportColumns?reportName=DepositReportSettings") {

    print_r('{"HasError":false,"AlertType":"danger","AlertMessage":"Invalid Username and/or password","ModelErrors":[],"Data": ["Id","ClientId","CreatedLocal","TypeName","CurrencyId","StakeCurrency","Amount","ModifiedLocal","PaymentSystemName","PaymentSystemName","State","Note","ExternalId"]}');
}

if ($URI == "/admin/dao/backapi/es/Setting/GetReportColumns?reportName=DepositReportSettings") {

    //print_r('{"HasError":true,"AlertType":"danger","AlertMessage":"Invalid Username and/or password","ModelErrors":[],"Data":null}');
    print_r('{"HasError":false,"AlertType":"danger","AlertMessage":"Invalid Username and/or password","ModelErrors":[],"Data": ["Id","ClientId","CreatedLocal","TypeName","CurrencyId","StakeCurrency","Amount","ModifiedLocal","PaymentSystemName","PaymentSystemName","State","Note","ExternalId"]}');
}
if ($URI == "/admin/dao/backapi/en/Client/GetClients") {

    print_r('{"HasError":false,"AlertType":"danger","AlertMessage":"Invalid Username and/or password","Objects":[],"Data":{"Objects":[{"Id" : 1,"Login":"Login","FirstName":"Pedro","LastName":"PErez","PersonalId":1,"Email":"test@test.com","AffilateId":1,"BTag":1,"IsSubscribeToEmail":false,"IsSubscribeToSMS ":true,"ExternalId":1,"AccountHolder" : 1000,"Address": "Calle","Address": "Calle","Address": "Calle","BirthCity": "Caldas","BirthDate": "07/07/2017","BirthDepartment": "Caldas","BirthRegionCode2": "2","BirthRegionId": "1","CashDeskId": "1","CreatedLocalDate": "07/07/2016 09:09:00","CurrencyId": "1","DocIssueCode": "1","DocIssueDate": "1","DocIssuedBy": "1","Gender": "M","IBAN": "1","IsLoggedIn ": true,"IsResident ": true,"IsSubscribedToNewsletter ": false,"IsTest ": true,"IsVerified ": true,"Language": "ES","LastLoginLocalDate": "07/07/1994 09:09:00","MiddleName": "1","MobilePhone": "1","Phone": "1","ProfileId": "1","PromoCode": "1","Province": "1","CountryName": "1","RegistrationSource": "1","SportsbookProfileId": "1","SwiftCode": "1","Title": "1","ZipCode": "1","IsLocked": true}],"Count":1}, "ReportCurrencies" : [{"Id":"1","IsSelected":"1"}]}');
}

if ($URI == "/admin/dao/backapi/es/Client/GetClients") {

    $Usuario = new Usuario(1);

    $params = file_get_contents('php://input');
    $params = json_decode($params);

    $MaxRows = $params->MaxRows;

    if ($MaxRows == "") {
        $MaxRows = 10;
    }

    $usuarios = $Usuario->getUsuarios("A", "", "a.usuario_id", "asc", "0", $MaxRows);

    $usuariosFinal = [];

    foreach ($usuarios as $key => $value) {
        $array = [];

        $array["Id"] = $value["a.usuario_id"];
        $array["Login"] = $value["a.login"];
        $array["FirstName"] = $value["a.nombre"];
        $array["LastName"] = $value["a.nombre"];
        $array["PersonalId"] = 1;
        $array["Email"] = $value["a.nombre"];
        $array["AffilateId"] = $value["a.nombre"];
        $array["LastName"] = $value["a.nombre"];

        $array["Id"] = 1;
        $array["Login"] = "Login";
        $array["FirstName"] = "Pedro";
        $array["LastName"] = "PErez";
        $array["PersonalId"] = 1;
        $array["Email"] = "test@test.com";
        $array["AffilateId"] = 1;
        $array["BTag"] = 1;
        $array["IsSubscribeToEmail"] = false;
        $array["IsSubscribeToSMS"] = true;
        $array["ExternalId"] = 1;
        $array["AccountHolder"] = 1000;
        $array["Address"] = "Calle";
        $array["Address"] = "Calle";
        $array["Address"] = "Calle";
        $array["BirthCity"] = "Caldas";
        $array["BirthDate"] = "07/07/2017";
        $array["BirthDepartment"] = "Caldas";
        $array["BirthRegionCode2"] = "2";
        $array["BirthRegionId"] = "1";
        $array["CashDeskId"] = "1";
        $array["CreatedLocalDate"] = "07/07/2016 09:09:00";
        $array["CurrencyId"] = "1";
        $array["DocIssueCode"] = "1";
        $array["DocIssueDate"] = "1";
        $array["DocIssuedBy"] = "1";
        $array["Gender"] = "M";
        $array["IBAN"] = "1";
        $array["IsLoggedIn "] = true;
        $array["IsResident "] = true;
        $array["IsSubscribedToNewsletter"] = false;
        $array["IsTest"] = true;
        $array["IsVerified"] = true;
        $array["Language"] = "ES";
        $array["LastLoginLocalDate"] = "07/07/1994 09:09:00";
        $array["MiddleName"] = "1";
        $array["MobilePhone"] = "1";
        $array["Phone"] = "1";
        $array["ProfileId"] = "1";
        $array["PromoCode"] = "1";
        $array["Province"] = "1";
        $array["CountryName"] = "1";
        $array["RegistrationSource"] = "1";
        $array["SportsbookProfileId"] = "1";
        $array["SwiftCode"] = "1";
        $array["Title"] = "1";
        $array["ZipCode"] = "1";
        $array["IsLocked"] = true;

        array_push($usuariosFinal, $array);
    }

    //print_r('{"HasError":true,"AlertType":"danger","AlertMessage":"Invalid Username and/or password","ModelErrors":[],"Data":null}');
    print_r('{"HasError":false,"AlertType":"danger","AlertMessage":"Invalid Username and/or password","Objects":[],"Data":{"Objects": ' . json_encode($usuariosFinal) . ',"Count": ' . oldCount($usuariosFinal) . '}, "ReportCurrencies" : [{"Id":"1","IsSelected":"1"}]}');
}

/**
 * Convertir divisas
 *
 * @param array $from_Currency from_Currency
 * @param String $to_Currency to_Currency
 * @param String $amount amounts
 *
 * @return String $convertido convertido
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
 * Obtener los deportes en el intervalo de dos fechas
 *
 * @param String $fecha_inicial fecha_inicial
 * @param String $fecha_final fecha_final
 *
 * @return array $array array
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function getSports($fecha_inicial, $fecha_final)
{

    global $URL_ITAINMENT;
    $rawdata = file_get_contents($URL_ITAINMENT . "/Export/GetEvents?importerId=8&from=" . $fecha_inicial . "&to=" . $fecha_final . "");
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
 * Obtener los market types de un deporte
 *
 * @param String $sport sport
 * @param String $fecha_inicial fecha_inicial
 * @param String $fecha_final fecha_final
 *
 * @return array $array array
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function getMarketTypes($sport, $fecha_inicial, $fecha_final)
{
    global $URL_ITAINMENT;

    $rawdata = file_get_contents($URL_ITAINMENT . "/Export/GetEvents?importerId=8&from=" . $fecha_inicial . "&to=" . $fecha_final . "");
    $data = simplexml_load_string($rawdata);
    $datos = json_decode($rawdata);
    $array = array();

    $existeMarcadorCorrecto = false;
    foreach ($datos as $item) {
        if ($sport == $item->SportId) {
            $rawdata2 = file_get_contents($URL_ITAINMENT . "/Export/GetMarkets?importerId=8&eventId=" . $item->Categories[0]->Championships[0]->Events[0]->EventId);
            $datos2 = json_decode($rawdata2);

            foreach ($datos2 as $item2) {
                $item_data = array(
                    "Id" => $item->SportId . "M" . $item2->MarketTypeid,
                    "Name" => $item2->Name
                );
                array_push($array, $item_data);

                if ($item2->MarketTypeid == 3 && $item->SportId == 1) {
                    $existeMarcadorCorrecto = true;
                }
            }


        }

    }

    if (!$existeMarcadorCorrecto && $sport == 1) {
        $item_data = array(
            "Id" => "1M3",
            "Name" => "Marcador Correcto(F)"
        );
        array_push($array, $item_data);
    }


    return $array;


}


/**
 * Obtener las regiones de un deporte
 *
 * @param String $sport sport
 * @param String $fecha_inicial fecha_inicial
 * @param String $fecha_final fecha_final
 *
 * @return array $array array
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function getRegions($sport, $fecha_inicial, $fecha_final)
{

    global $URL_ITAINMENT;

    $rawdata = file_get_contents($URL_ITAINMENT . "/Export/GetEvents?importerId=8&from=" . $fecha_inicial . "&to=" . $fecha_final . "");
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
 * Obtener las competencias de un deporte
 *
 * @param String $sport sport
 * @param String $region region
 * @param String $fecha_inicial fecha_inicial
 * @param String $fecha_final fecha_final
 *
 * @return array $array array
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
/**
 * Obtener las regiones de un deporte
 *
 * @param String $sport sport
 * @param String $fecha_inicial fecha_inicial
 * @param String $fecha_final fecha_final
 *
 * @return array $array array
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function getCompetitions($sport, $region, $fecha_inicial, $fecha_final)
{
    global $URL_ITAINMENT;

    $rawdata = file_get_contents($URL_ITAINMENT . "/Export/GetEvents?importerId=8&from=" . $fecha_inicial . "&to=" . $fecha_final . "");
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
 * Obtener información sobre un deporte
 *
 * @param String $sport sport
 * @param String $region region
 * @param String $competition competition
 * @param String $fecha_inicial fecha_inicial
 * @param String $fecha_final fecha_final
 *
 * @return array $array array
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function getMatches($sport, $region, $competition, $fecha_inicial, $fecha_final)
{
    global $URL_ITAINMENT;

    $rawdata = file_get_contents($URL_ITAINMENT . "/Export/GetEvents?importerId=8&from=" . $fecha_inicial . "&to=" . $fecha_final . "");
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
                                    "Name" => $item4->EventName
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
 * Generar una clave alfanumérica del ticket
 *
 * @param int $length length
 *
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
 * Crear arreglo unico a partir de uno multidimensiona
 *
 * @param array $array array
 * @param String $key key
 *
 * @return String $temp_array temp_array
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function unique_multidim_array($array, $key)
{
    $temp_array = array();
    $i = 0;
    $key_array = array();

    foreach ($array as $val) {
        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $temp_array[$i] = $val;
        }
        $i++;
    }
    return $temp_array;
}

/**
 * Crear arreglo unico a partir de uno multidimensiona
 *
 * @param array $array array
 * @param String $key key
 *
 * @return String $temp_array temp_array
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function unique_multidim_array2($array, $key)
{
    $temp_array = array();
    $i = 0;
    $key_array = array();

    foreach ($array as $val) {
        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            array_push($temp_array, $val);
        }
        $i++;
    }
    return $temp_array;
}

