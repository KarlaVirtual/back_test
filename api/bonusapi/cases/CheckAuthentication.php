<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

//Verifica si ya hubo un logueo
use Backend\dto\PerfilSubmenu;
use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;

/**
 * bonusapi/cases/CheckAuthentication
 *
 * Autenticación de usuario y obtención de permisos
 *
 * Este recurso autentica al usuario basado en la sesión activa y obtiene su lista de permisos según su perfil.
 *
 * @param object $params : Objeto que contiene los parámetros de entrada.
 *   - *MaxRows* (int, opcional): Número máximo de filas a recuperar en la consulta.
 *   - *OrderedItem* (int, opcional): Campo por el cual se ordenará la consulta.
 *   - *SkeepRows* (int, opcional): Número de filas a omitir en la consulta.
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Tipo de alerta generada ("success" o "danger").
 *  - *AlertMessage* (string): Mensaje de alerta generado por el proceso.
 *  - *ModelErrors* (array): Retorna un array vacío si no hay errores en el modelo.
 *  - *Data* (array): Contiene la información del usuario autenticado y su lista de permisos.
 *    - *AuthenticationStatus* (int): Estado de autenticación (0 si la autenticación fue exitosa).
 *    - *PartnerLimitType* (int): Tipo de límite de asociación.
 *    - *FirstName* (string): Nombre del usuario autenticado.
 *    - *Settings* (array): Configuración del usuario.
 *      - *Language* (string): Idioma configurado por el usuario.
 *      - *ReportCurrency* (string): Moneda de reporte, por defecto "USD".
 *    - *LangId* (string): Idioma del usuario en minúsculas.
 *    - *UserName* (string): Nombre de usuario.
 *    - *CurrencyId* (string): Identificador de la moneda del usuario.
 *    - *UserId* (int): Identificador del usuario.
 *    - *PermissionList* (array): Lista de permisos asignados al usuario.
 *
 * Ejemplo de respuesta en caso de error:
 *  - *HasError* => true,
 *  - *AlertType* => "danger",
 *  - *AlertMessage* => "Error ([Código de error])",
 *  - *ModelErrors* => array(),
 *
 * @throws Exception Si ocurre un error en la autenticación o recuperación de permisos del usuario.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* verifica la sesión y genera una respuesta de error si no está logueado. */
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


        /* Se crean objetos UsuarioMandante y Usuario a partir de la sesión activa del usuario. */
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


        /* Código que inicializa un array de respuesta sin errores y con mensaje de éxito. */
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        if ($_SESSION["usuario"] == "163") {


            /* obtiene menús de usuario y agrega opciones específicas a un array. */
            $menus = $Usuario->getMenus();
            $menus_string = array();
            array_push($menus_string, "ViewMenuSecurity");
            array_push($menus_string, "ViewMenuTeacher");
            array_push($menus_string, "ViewMenuManagement");
            array_push($menus_string, "ViewMenuCash");

            /* construye un array de menús y una respuesta con permisos de usuario. */
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


            /* Código para inicializar un objeto y gestionar parámetros de sesión y configuración. */
            $PerfilSubmenu = new PerfilSubmenu();

            $Perfil_id = $_SESSION["win_perfil2"];
            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;


            /* Inicializa variables si están vacías: $SkeepRows a 0 y $OrderedItem a 1. */
            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }


            /* Establece un límite de filas y construye una consulta JSON para menus. */
            if ($MaxRows == "") {
                $MaxRows = 100000;
            }

            $mismenus = "0";

            $json = '{"rules" : [{"field" : "menu.version", "data" : "2","op":"eq"},{"field" : "perfil_submenu.perfil_id", "data" : "' . $Perfil_id . '","op":"eq"}] ,"groupOp" : "AND"}';


            /* Obtiene y decodifica menús personalizados desde la base de datos en formato JSON. */
            $menus = $PerfilSubmenu->getPerfilSubmenusCustom(" menu.*,submenu.*,perfil_submenu.* ", "menu.menu_id", "asc", $SkeepRows, $MaxRows, $json, true);

            $menus = json_decode($menus);

            $menus3 = [];
            $arrayf = [];

            /* Se inicializan dos arreglos vacíos para almacenar menús y submenús. */
            $submenus = [];

            $menus_string = array();

            foreach ($menus->data as $key => $value) {


                /* Asignación de valores de menú y submenú a un array en PHP. */
                $m = [];
                $m["Id"] = $value->{"menu.menu_id"};
                $m["Name"] = $value->{"menu.descripcion"};

                $array = [];

                $array["Id"] = $value->{"submenu.submenu_id"};

                /* asigna valores a un array y concatena un ID a una cadena. */
                $array["Name"] = $value->{"submenu.descripcion"};
                $array["Pagina"] = $value->{"submenu.pagina"};
                $array["IsGiven"] = true;
                $array["Action"] = "view";

                $mismenus = $mismenus . "," . $array["Id"];


                /* Condicional que añade elementos a un array si se cumplen ciertas condiciones. */
                if ($arrayf["Id"] != "" && $m["Id"] != $arrayf["Id"]) {
                    array_push($menus_string, $arrayf["Pagina"]);

                    $arrayf["Permissions"] = $submenus;
                    array_push($menus3, $arrayf);
                    // $submenus = [];
                }

                /* Se añaden elementos a los arreglos $menus_string y $submenus con datos del menú. */
                array_push($menus_string, $array["Pagina"]);

                $arrayf["Id"] = $value->{"menu.menu_id"};
                $arrayf["Name"] = $value->{"menu.descripcion"};
                $arrayf["Pagina"] = $value->{"menu.pagina"};

                array_push($submenus, $array);
            }

            /* agrega elementos a un array y asigna permisos en una estructura. */
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


            /* Genera una respuesta estructurada con datos del usuario y configuración en formato array. */
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
        /* Manejo de excepciones en PHP, devuelve un mensaje de error en formato JSON. */


        $response["HasError"] = true;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = "Error ('.$e->getCode().')";
        $response["ModelErrors"] = [];

    }

}