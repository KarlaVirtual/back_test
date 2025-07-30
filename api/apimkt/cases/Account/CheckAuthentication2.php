<?php

use Backend\dto\ApiTransaction;
use Backend\dto\Area;
use Backend\dto\Bono;
use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\BonoLog;
use Backend\dto\Cargo;
use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\CentroCosto;
use Backend\dto\Clasificador;
use Backend\dto\CodigoPromocional;
use Backend\dto\Competencia;
use Backend\dto\CompetenciaPuntos;
use Backend\dto\Concepto;
use Backend\dto\Concesionario;
use Backend\dto\Consecutivo;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\ContactoComercial;
use Backend\dto\ContactoComercialLog;
use Backend\dto\CuentaCobro;
use Backend\dto\CuentaContable;
use Backend\dto\CupoLog;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
use Backend\dto\Egreso;
use Backend\dto\Empleado;
use Backend\dto\FlujoCaja;
use Backend\dto\Flujocajafact;
use Backend\dto\Ingreso;
use Backend\dto\IntApuesta;
use Backend\dto\IntApuestaDetalle;
use Backend\dto\IntCompetencia;
use Backend\dto\IntDeporte;
use Backend\dto\IntEquipo;
use Backend\dto\IntEvento;
use Backend\dto\IntEventoApuesta;
use Backend\dto\IntEventoApuestaDetalle;
use Backend\dto\IntEventoDetalle;
use Backend\dto\IntRegion;
use Backend\dto\ItTicketEnc;
use Backend\dto\LenguajeMandante;
use Backend\dto\Mandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\Pais;
use Backend\dto\PaisMandante;
use Backend\dto\Perfil;
use Backend\dto\PerfilSubmenu;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Producto;
use Backend\dto\ProductoComision;
use Backend\dto\ProductoInterno;
use Backend\dto\ProductoTercero;
use Backend\dto\ProductoterceroUsuario;
use Backend\dto\PromocionalLog;
use Backend\dto\Proveedor;
use Backend\dto\ProveedorMandante;
use Backend\dto\ProveedorTercero;
use Backend\dto\PuntoVenta;
use Backend\dto\ProductoMandante;
use Backend\dto\Registro;
use Backend\dto\SaldoUsuonlineAjuste;
use Backend\dto\Submenu;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionApiMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransaccionSportsbook;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioAlerta;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioBloqueado;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioCierrecaja;
use Backend\dto\UsuarioComision;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioNotas;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioPublicidad;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioRecargaResumen;
use Backend\dto\UsuarioRetiroResumen;
use Backend\dto\UsuarioSaldo;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioTokenInterno;
use Backend\dto\UsucomisionResumen;
use Backend\dto\UsumarketingResumen;
use Backend\imports\Google\GoogleAuthenticator;
use Backend\integrations\mensajeria\Okroute;
use Backend\integrations\payout\LPGSERVICES;
use Backend\mysql\AreaMySqlDAO;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\BonoLogMySqlDAO;
use Backend\mysql\CargoMySqlDAO;
use Backend\mysql\CategoriaMySqlDAO;
use Backend\mysql\CategoriaProductoMySqlDAO;
use Backend\mysql\CentroCostoMySqlDAO;
use Backend\mysql\ClasificadorMySqlDAO;
use Backend\mysql\CodigoPromocionalMySqlDAO;
use Backend\mysql\CompetenciaPuntosMySqlDAO;
use Backend\mysql\ConceptoMySqlDAO;
use Backend\mysql\ConcesionarioMySqlDAO;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\ContactoComercialLogMySqlDAO;
use Backend\mysql\ContactoComercialMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\CuentaContableMySqlDAO;
use Backend\mysql\CupoLogMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\EgresoMySqlDAO;
use Backend\mysql\EmpleadoMySqlDAO;
use Backend\mysql\FlujoCajaMySqlDAO;
use Backend\mysql\IngresoMySqlDAO;
use Backend\mysql\IntApuestaDetalleMySqlDAO;
use Backend\mysql\IntApuestaMySqlDAO;
use Backend\mysql\IntCompetenciaMySqlDAO;
use Backend\mysql\IntDeporteMySqlDAO;
use Backend\mysql\IntEventoApuestaDetalleMySqlDAO;
use Backend\mysql\IntEventoApuestaMySqlDAO;
use Backend\mysql\IntEventoDetalleMySqlDAO;
use Backend\mysql\IntEventoMySqlDAO;
use Backend\mysql\IntRegionMySqlDAO;
use Backend\mysql\LenguajeMandanteMySqlDAO;
use Backend\mysql\MandanteDetalleMySqlDAO;
use Backend\mysql\MandanteMySqlDAO;
use Backend\mysql\PerfilSubmenuMySqlDAO;
use Backend\mysql\ProdMandanteTipoMySqlDAO;
use Backend\mysql\ProductoComisionMySqlDAO;
use Backend\mysql\ProductoTerceroMySqlDAO;
use Backend\mysql\ProductoterceroUsuarioMySqlDAO;
use Backend\mysql\ProveedorMandanteMySqlDAO;
use Backend\mysql\ProveedorMySqlDAO;
use Backend\mysql\ProveedorTerceroMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\SaldoUsuonlineAjusteMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\UsuarioAlertaMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBloqueadoMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\UsuarioCierrecajaMySqlDAO;
use Backend\mysql\UsuarioConfigMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\ProductoMySqlDAO;
use Backend\mysql\ProductoMandanteMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioNotasMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioPublicidadMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenInternoMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\mysql\UsucomisionResumenMySqlDAO;
use Backend\websocket\WebsocketUsuario;


/**
 * Account/CheckUserLoginPassword
 *
 * Verificar si el usuario esta logueado y retornar los datos de el usuario
 *
 * @return array $response
 *  - HasError: boolean
 *  - AlertType: string
 *  - AlertMessage: string
 *  - ModelErrors: array
 *  - Data: array
 *      - AuthenticationStatus: int
 *      - PermissionList: array
 *      - SaldoRecargas: int
 *      - SaldoJuego: int
 *      - PartnerLimitType: int
 */


//Verifica si ya hubo un logueo

/* Verifica si la sesión está activa; si no, retorna un mensaje de error. */
if (!$_SESSION['logueado']) {
    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = "Invalid Username and/or password ('.$e->getCode().')";
    $response["ModelErrors"] = [];

    $response["Data"] = array(
        "AuthenticationStatus" => 0,

        "PermissionList" => array(),
    );

} else {

    try {


        /* Se crean instancias de usuario y país utilizando datos de sesión y otras clases. */
        $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);
        $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
        $Pais = new Pais($Usuario->paisId);

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


        /* inicializa un arreglo de respuesta y verifica una sesión global vacía. */
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        if ($_SESSION["Global"] == "") {
            $_SESSION["Global"] == "N";
        }


        if ($_SESSION['usuario2'] == "5" && false) {
            //if ($_SESSION['usuario2'] == "163") {


            /* almacena menús de usuario en un arreglo para uso posterior. */
            $menus = $Usuario->getMenus();
            $menus_string = array();
            array_push($menus_string, "ViewMenuSecurity");
            array_push($menus_string, "ViewMenuTeacher");
            array_push($menus_string, "ViewMenuManagement");
            array_push($menus_string, "ViewMenuCash");

            /* Inserta elementos en un array de menú, luego obtiene el menú actualizado. */
            array_push($menus_string, "ViewMenuQueries");
            foreach ($menus as $key => $value) {
                array_push($menus_string, "view" . str_replace("_", "", str_replace(".php", "", $value["b.pagina"])));
            }

            $menus_string = obtenerMenu();

            /* obtiene una lista de proveedores de tipo "CASINO". */
            $paisesparamenu = obtenerPaisesReport();


            $Proveedor = new Proveedor();
            $Proveedor->setTipo("CASINO");

            $proveedores = $Proveedor->getProveedores();


            /* Crea un array de proveedores con un elemento predeterminado "Todos". */
            $finalProveedores = [];
            $array = [];
            $array["id"] = '0';
            $array["value"] = 'Todos';
            array_push($finalProveedores, $array);

            foreach ($proveedores as $key => $value) {

                $array = [];

                $array["id"] = $value->getProveedorId();
                $array["value"] = $value->getDescripcion();

                array_push($finalProveedores, $array);

            }


            /* Se obtienen proveedores de tipo "LIVECASINO" y se almacenan en un array. */
            $Proveedor = new Proveedor();
            $Proveedor->setTipo("LIVECASINO");

            $proveedores = $Proveedor->getProveedores();

            foreach ($proveedores as $key => $value) {

                $array = [];

                $array["id"] = $value->getProveedorId();
                $array["value"] = $value->getDescripcion();

                array_push($finalProveedores, $array);

            }


            /* Crea un filtro basado en reglas para un proveedor específico de tipo "CASINO". */
            $ProductoMandante = new ProductoMandante();

            $rules = [];
            array_push($rules, array("field" => "proveedor.tipo", "data" => "CASINO ", "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");

            /* convierte un filtro en JSON y obtiene productos personalizados si la sesión lo permite. */
            $json = json_encode($filtro);

            if ($_SESSION["Global"] == "S") {
                $Producto = new Producto();

                $productos = $Producto->getProductosCustom(" producto.* ", "producto.producto_id", "asc", $SkeepRows, $MaxRows, $json, true);
                $productos = json_decode($productos);
            } else {
                /* Obtiene productos personalizados y los decodifica de formato JSON a array. */

                $productos = $ProductoMandante->getProductosMandanteCustom(" producto_mandante.*,mandante.*,producto.* ", "producto_mandante.prodmandante_id", "asc", $SkeepRows, $MaxRows, $json, true);
                $productos = json_decode($productos);

            }


            /* organiza productos en un arreglo basado en configuración global de sesión. */
            $finalProductos = [];

            foreach ($productos->data as $key => $value) {

                $array = [];
                if ($_SESSION["GlobalConfig"] == "S") {
                    $array["id"] = $value->{"producto.producto_id"};
                    $array["value"] = $value->{"producto.descripcion"};

                } else {
                    $array["id"] = $value->{"producto_mandante.prodmandante_id"};
                    $array["value"] = $value->{"producto.descripcion"};

                }
                array_push($finalProductos, $array);

            }


            /* Define reglas de acceso según el perfil de usuario "CONCESIONARIO". */
            $rules = [];

            if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
                $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

                array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION['usuario'], "op" => "eq"));
                array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            }


            /* Validación de sesión y creación de reglas para concesionario en PHP. */
            if ($_SESSION["win_perfil"] == "CONCESIONARIO2") {
                $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

                array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            }


            /* Verifica el perfil de usuario y agrega reglas para el concesionario en un array. */
            if ($_SESSION["win_perfil"] == "PUNTOVENTA") {
                $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

                array_push($rules, array("field" => "concesionario.usuhijo_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            }

            /* Se agregan reglas de filtrado según el perfil y, opcionalmente, el país del usuario. */
            array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));


            // Si el usuario esta condicionado por País
            if ($_SESSION['PaisCond'] == "S") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
            }
            // Si el usuario esta condicionado por el mandante y no es de Global

            /* define reglas de filtrado basadas en condiciones de sesión y país. */
            if ($_SESSION['Global'] == "N") {
                array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
            }
            // Inactivamos reportes para el país Colombia
            array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


            $filtro = array("rules" => $rules, "groupOp" => "AND");

            /* Convierte datos a JSON y obtiene puntos de venta filtrados desde la base de datos. */
            $jsonbetshop = json_encode($filtro);


            $PuntoVenta = new PuntoVenta();


            $mandantes = $PuntoVenta->getPuntoVentasCustom("usuario.estado_valida,usuario.fecha_crea,usuario.moneda,usuario.fecha_ult,punto_venta.*", "punto_venta.puntoventa_id", "asc", 0, 100000, $jsonbetshop, true);


            /* Convierte datos JSON a un arreglo con id y descripción de puntos de venta. */
            $mandantes = json_decode($mandantes);


            $finalBetShops = [];

            foreach ($mandantes->data as $key => $value) {

                $array = [];

                $array["id"] = $value->{"punto_venta.usuario_id"};
                $array["value"] = $value->{"punto_venta.descripcion"};

                array_push($finalBetShops, $array);

            }


            /* Recopila información de "mandantes" en un nuevo array de apuestas. */
            $finalBetShops = [];

            foreach ($mandantes->data as $key => $value) {

                $array = [];

                $array["id"] = $value->{"punto_venta.usuario_id"};
                $array["value"] = $value->{"punto_venta.descripcion"};

                array_push($finalBetShops, $array);

            }


            /* Verifica el país del usuario según su perfil y condición de sesión. */
            $ReportCountry = $Usuario->paisId;

            if ($_SESSION["win_perfil2"] == "ADMIN" || $_SESSION["win_perfil2"] == "ADMIN2" || $_SESSION["win_perfil2"] == "OPERADOR" || $_SESSION["win_perfil2"] == "SA") {
                if ($_SESSION['PaisCond'] != "S") {
                    $ReportCountry = '0';
                }

            }


            /* inicializa saldos y verifica el perfil del usuario para obtener datos. */
            $SaldoRecargas = 0;
            $SaldoJuego = 0;
            if ($_SESSION["win_perfil2"] == "CONCESIONARIO" || $_SESSION["win_perfil2"] == "CONCESIONARIO2" || $_SESSION["win_perfil2"] == "PUNTOVENTA") {
                $PuntoVenta = new PuntoVenta("", $_SESSION["usuario"]);

                $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                $SaldoJuego = $PuntoVenta->getCreditosBase();
            }


            /* Se inicializa un array vacío llamado "finalMandante" en PHP. */
            $finalMandante = [];

            if ($_SESSION["GlobalConfig"] == "S") {


                /* Se crea un array con datos de una empresa y países asociados. */
                $array = [];
                $array["id"] = "-1";
                $array["value"] = "Virtualsoft";

                $array["Countries"] = array(
                    array(
                        "id" => "2",
                        "value" => "Nicaragua"
                    ),
                    array(
                        "id" => "173",
                        "value" => "Perú"
                    )
                );

                /* Se agrega un arreglo a `$finalMandante` y se inicia un objeto `Mandante`. */
                array_push($finalMandante, $array);


                $Mandante = new Mandante();

                $rules = [];
                // array_push($rules, array("field" => "proveedor.tipo", "data" => "CASINO ", "op" => "eq"));


                /* Genera un filtro JSON y obtiene mandantes desde la base de datos. */
                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $mandantes = $Mandante->getMandantes("mandante.mandante", "asc", 0, 100, $json, true);
                $mandantes = json_decode($mandantes);


                /* Itera sobre datos y organiza información en un array con países específicos. */
                foreach ($mandantes->data as $key => $value) {
                    $array = [];
                    $array["id"] = $value->{"mandante.mandante"};
                    $array["value"] = $value->{"mandante.descripcion"};

                    $array["Countries"] = array(
                        array(
                            "id" => "2",
                            "value" => "Nicaragua"
                        ),
                        array(
                            "id" => "173",
                            "value" => "Perú"
                        )
                    );

                    array_push($finalMandante, $array);

                }
            } else {
                /* crea un array con datos de sesión y países, luego lo agrega a otro array. */

                $array = [];

                $array["id"] = $_SESSION["mandante"];
                $array["value"] = $_SESSION["mandante"];

                $array["Countries"] = array(
                    array(
                        "id" => "2",
                        "value" => "Nicaragua"
                    ),
                    array(
                        "id" => "173",
                        "value" => "Perú"
                    )
                );
                array_push($finalMandante, $array);

            }

            $response["Data"] = array(
                "AuthenticationStatus" => 0,
                "SaldoRecargas" => $SaldoRecargas,
                "SaldoJuego" => $SaldoJuego,

                "PartnerLimitType" => 1,
                "FirstName" => $Usuario->nombre,
                "Settings" => array(
                    "Language" => strtolower($Usuario->idioma),
                    "ReportCurrency" => $Usuario->monedaReporte,
                    "ReportCountry" => $ReportCountry,
                    "TimeZone" => $Usuario->timezone,

                ),
                "LangId" => strtolower($Usuario->idioma),
                "UserName" => $Usuario->nombre,
                "CurrencyId" => $Usuario->moneda,
                "UserId" => $Usuario->usuarioId,
                "AgentId" => $Usuario->usuarioId,
                "PermissionList" => $menus_string,
                "Countries" => $paisesparamenu,
                "BetShops" => $finalBetShops,

                "ProvidersCasino" => array(
                    array(
                        "id" => "",
                        "value" => "Todos"
                    ),
                    array(
                        "id" => "27",
                        "value" => "Microgaming"
                    )
                ),
                "Partners" => $finalMandante,
                "PartnerSelected" => $_SESSION["mandante"],
                "BetShops" => $finalBetShops,
                "GamesCasino" => $finalProductos,
                "ProvidersCasino" => $finalProveedores,
                "PermissionList2" => array_merge(array("ManageDepositRequests",
                    "ManageWithdrawalRequests", "ManageUsers", "ViewClientBonuses", "ViewPlayers", "ViewAddHocReport", "ViewScout", "ViewCMS", "ViewAffiliate", "SGPlayersView", "SGStatisticsRake", "ViewFinancialReports", "ViewPaymentReport", "AssignAgentCredit", "ManageAgentCredit", "ViewAgentGroups", "ViewAgentCommissionGroups", "ViewAgentPtGroups", "ViewAgentBetLimitGroups", "ViewAgentGroups", "ViewAgentGroups", "ManageAgentCommissionGroups", "ManageAgentBetLimitGroups", "ManageAgentGroups", "ManageClientCredit", "ViewGames", "ViewClientSportBets", "ViewClientTransactions", "ViewClientLogins", "ViewClientCasinoBets", "ViewSportReport", "ViewMenuDashBoard", "ViewDashBoardActivePlayers", "ViewDashBoardNewRegistrations", "ViewDashBoardSportBets", "ViewDashBoardCasinoBets", "ViewDashBoardTopFiveGames", "ViewDashBoardTopSportsByStake", "ViewDashBoardTopFiveSportsbookPlayers", "ViewDashBoardTopFivePlayers", "ViewUsers", "ViewUsersMenu", "ViewUsersLogs", "ViewAgentTransfers", "ViewBalance", "ViewDepositWithdrawalReport", "PMManageSale", "PMManageProduct", "ViewSalesReport", "ViewTurnoverTaxReport", "ViewDepositRequests", "ViewWithdrawalRequests", "ViewDocuments", "ViewFinancialOperations", "ManageAgent", "ViewBetShopUsers", "ViewCashDesks", "ManageBetShopUsers", "ViewClientMessage", "ViewVerificationStep", "ResetClientPassword", "ViewAgentMenu", "ViewAgentSystem", "ViewAgent", "ViewAgentSubAccounts", "ViewAgentMembers", "ViewMessages", "ViewEmailTemplates", "ManageMessages", "ViewTranslation", "ViewSettings", "ViewStream", "PMViewPartner", "PMViewProduct", "PMViewSale", "hjkhjkhjk", "jhkhjkhjk", "ViewOddsFeed", "ViewMatch", "ViewSportLimits", "ViewPartnersBooking", "ViewMenuReport", "ViewCasinoReport", "ViewCashDeskReport", "ViewCRM", "ViewSegment", "CreateSegment", "ViewBetShops", "ViewClients", "ManageClients", "ViewMenuSecurity", "ViewMenuTeacher", "AdminUser", "Contingencia", "Menu", "Perfil", "PerfilOpciones", "Submenu", "UsuarioPerfil", "Clasificador", "Concesionario", "ViewAddHocReport", "ViewMenuManagement", "ActivarRegistros", "AjustarSaldo", "AsignacionCupo", "Bonos", "CuentaCobroEliminar", "GestionRed", "RegistroRapido", "ChequeReimpresion", "RecargaReversion", "GestionContacto", "ViewMenuCash", "FlujoCaja", "PagoPremio", "PagoNotaRetiro", "RecargarCredito", "ViewMenuQueries", "FlujoCajaHistorico", "FlujoCajaResumido", "InformeCasino", "InformeGerencial", "ListadoRecargasRetiros", "PremiosPendientesPagar", "ConsultaOnlineDetalle", "ConsultaOnlineResumen", "MakeCorrection", "Trabajaconnosotros", "ViewSportsBookReports", "ViewBetReport", "ViewSportReport", "ViewCompetitionReport", "ViewMarketReport", "ViewSports", "ViewCompetitions", "ViewClientLogHistory", "ManageTranslation", "ManageProviders", "ManagePartnerProducts"

                ), $menus_string),

            );
        } else {


            /* Se inicializa un objeto y se obtienen parámetros de sesión y configuración. */
            $PerfilSubmenu = new PerfilSubmenu();

            $Perfil_id = $_SESSION["win_perfil2"];
            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;


            /* establece valores predeterminados para dos variables si están vacías. */
            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }


            /* asigna valor a $MaxRows y crea un objeto JSON con reglas. */
            if ($MaxRows == "") {
                $MaxRows = 100000;
            }

            $mismenus = "0";

            $json = '{"rules" : [{"field" : "menu.version", "data" : "2","op":"eq"},{"field" : "perfil_submenu.perfil_id", "data" : "' . $Perfil_id . '","op":"eq"}] ,"groupOp" : "AND"}';


            /* Se obtienen submenús filtrados y se decodifican en formato JSON. */
            $menus = $PerfilSubmenu->getPerfilSubmenusCustom(" menu.*,submenu.*,perfil_submenu.* ", "menu.menu_id", "asc", $SkeepRows, $MaxRows, $json, true);

            $menus = json_decode($menus);

            $menus3 = [];
            $arrayf = [];

            /* Se inicializan dos arrays vacíos: uno para submenús y otro para cadenas de menús. */
            $submenus = [];

            $menus_string = array();

            foreach ($menus->data as $key => $value) {


                /* Se crea un array con información de un menú y su submenú a partir de un objeto. */
                $m = [];
                $m["Id"] = $value->{"menu.menu_id"};
                $m["Name"] = $value->{"menu.descripcion"};

                $array = [];

                $array["Id"] = $value->{"submenu.submenu_id"};

                /* asigna valores a un array y concatena IDs a la variable $mismenus. */
                $array["Name"] = $value->{"submenu.descripcion"};
                $array["Pagina"] = $value->{"submenu.pagina"};
                $array["IsGiven"] = true;
                $array["Action"] = "view";

                $mismenus = $mismenus . "," . $array["Id"];


                /* Condicional que añade páginas y permisos a los arrays si se cumplen ciertas condiciones. */
                if ($arrayf["Id"] != "" && $m["Id"] != $arrayf["Id"]) {
                    array_push($menus_string, $arrayf["Pagina"]);

                    $arrayf["Permissions"] = $submenus;
                    array_push($menus3, $arrayf);
                    // $submenus = [];
                }

                /* Agrega datos de menú y submenú a sus respectivos arreglos en PHP. */
                array_push($menus_string, $array["Pagina"]);

                $arrayf["Id"] = $value->{"menu.menu_id"};
                $arrayf["Name"] = $value->{"menu.descripcion"};
                $arrayf["Pagina"] = $value->{"menu.pagina"};

                array_push($submenus, $array);
            }

            /* Se agregan páginas y permisos a arrays usando array_push en PHP. */
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


            /* Se obtienen datos de menú y países para configurar un proveedor de tipo "CASINO". */
            $menus_string = obtenerMenu();
            $paisesparamenu = obtenerPaisesReport();


            $Proveedor = new Proveedor();
            $Proveedor->setTipo("CASINO");


            /* Se obtiene una lista de proveedores y se inicializa un array con valores predeterminados. */
            $proveedores = $Proveedor->getProveedores();

            $finalProveedores = [];
            $array = [];
            $array["id"] = '0';
            $array["value"] = 'Todos';

            /* Agrega datos de proveedores a un arreglo final. */
            array_push($finalProveedores, $array);

            foreach ($proveedores as $key => $value) {

                $array = [];

                $array["id"] = $value->getProveedorId();
                $array["value"] = $value->getDescripcion();

                array_push($finalProveedores, $array);

            }


            /* Se crea un proveedor y se obtienen datos de proveedores en un arreglo. */
            $Proveedor = new Proveedor();
            $Proveedor->setTipo("LIVECASINO");

            $proveedores = $Proveedor->getProveedores();

            foreach ($proveedores as $key => $value) {

                $array = [];

                $array["id"] = $value->getProveedorId();
                $array["value"] = $value->getDescripcion();

                array_push($finalProveedores, $array);

            }


            /* Crea reglas de filtro para el proveedor tipo "CASINO" en un producto. */
            $ProductoMandante = new ProductoMandante();

            $rules = [];
            array_push($rules, array("field" => "proveedor.tipo", "data" => "CASINO ", "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");

            /* Convierte $filtro a JSON y obtiene productos si la configuración global es "S". */
            $json = json_encode($filtro);

            if ($_SESSION["GlobalConfig"] == "S") {
                $Producto = new Producto();

                $productos = $Producto->getProductosCustom(" producto.* ", "producto.producto_id", "asc", $SkeepRows, $MaxRows, $json, true);
                $productos = json_decode($productos);
            } else {
                /* obtiene productos mandantes y los convierte a formato JSON. */

                $productos = $ProductoMandante->getProductosMandanteCustom(" producto_mandante.*,mandante.*,producto.* ", "producto_mandante.prodmandante_id", "asc", $SkeepRows, $MaxRows, $json, true);
                $productos = json_decode($productos);

            }


            /* genera un array final con productos según configuración de sesión. */
            $finalProductos = [];

            foreach ($productos->data as $key => $value) {

                $array = [];
                if ($_SESSION["GlobalConfig"] == "S") {
                    $array["id"] = $value->{"producto.producto_id"};
                    $array["value"] = $value->{"producto.descripcion"};

                } else {
                    $array["id"] = $value->{"producto_mandante.prodmandante_id"};
                    $array["value"] = $value->{"producto.descripcion"};

                }
                array_push($finalProductos, $array);

            }


            /* Define reglas de acceso basadas en el perfil de usuario "CONCESIONARIO". */
            $rules = [];

            if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
                $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

                array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION['usuario'], "op" => "eq"));
                array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            }


            /* Condiciona reglas basadas en el perfil de usuario de concesionario en sesión. */
            if ($_SESSION["win_perfil"] == "CONCESIONARIO2") {
                $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

                array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            }


            /* Condicional que configura reglas según el perfil "PUNTOVENTA" en la sesión. */
            if ($_SESSION["win_perfil"] == "PUNTOVENTA") {
                $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

                array_push($rules, array("field" => "concesionario.usuhijo_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            }


            /* Se agregan reglas de filtro para usuarios según su perfil y país. */
            array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));


            // Si el usuario esta condicionado por País
            if ($_SESSION['PaisCond'] == "S") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
            }
            // Si el usuario esta condicionado por el mandante y no es de Global

            /* Se crean reglas de filtro basadas en la sesión y el país especificado. */
            if ($_SESSION['Global'] == "N") {
                array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
            }
            // Inactivamos reportes para el país Colombia
            array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


            $filtro = array("rules" => $rules, "groupOp" => "AND");

            /* convierte datos a JSON y obtiene información de puntos de venta personalizados. */
            $jsonbetshop = json_encode($filtro);


            $PuntoVenta = new PuntoVenta();


            $mandantes = $PuntoVenta->getPuntoVentasCustom("usuario.estado_valida,usuario.fecha_crea,usuario.moneda,usuario.fecha_ult,punto_venta.*", "punto_venta.puntoventa_id", "asc", 0, 100000, $jsonbetshop, true);


            /* convierte datos JSON en un arreglo con identificadores y descripciones de puntos de venta. */
            $mandantes = json_decode($mandantes);


            $finalBetShops = [];

            foreach ($mandantes->data as $key => $value) {

                $array = [];

                $array["id"] = $value->{"punto_venta.usuario_id"};
                $array["value"] = $value->{"punto_venta.descripcion"};

                array_push($finalBetShops, $array);

            }


            /* establece condiciones para modificar el país reportado según el perfil del usuario. */
            $ReportCountry = $Usuario->paisId;
            if ($_SESSION["win_perfil2"] == "ADMIN" || $_SESSION["win_perfil2"] == "ADMIN2" || $_SESSION["win_perfil2"] == "OPERADOR" || $_SESSION["win_perfil2"] == "SA") {
                if ($_SESSION['PaisCond'] != "S") {
                    $ReportCountry = '0';
                }
            }


            /* Calcula saldos de recarga y juego para ciertos usuarios en sesión. */
            $SaldoRecargas = 0;
            $SaldoJuego = 0;
            if ($_SESSION["win_perfil2"] == "CONCESIONARIO" || $_SESSION["win_perfil2"] == "CONCESIONARIO2" || $_SESSION["win_perfil2"] == "PUNTOVENTA") {
                $PuntoVenta = new PuntoVenta("", $_SESSION["usuario"]);

                $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                $SaldoJuego = $PuntoVenta->getCreditosBase();
            }


            /* Verifica si la fecha de cierre de caja coincide con la fecha actual. */
            $fechaCierre = date("Y-m-d", strtotime($Usuario->fechaCierrecaja));
            $hizoCierreCaja = false;

            if ($fechaCierre == date("Y-m-d")) {
                $hizoCierreCaja = true;
            }


            /* Variable que indica si ha comenzado un día determinado en una lógica de programación. */
            $beginDay = false;

            /*if ($_SESSION["win_perfil2"] == "CAJERO") {
                $TipoDineroInicial = new Clasificador("", "ACCAMOUNTDAY");


                $rules = [];
                array_push($rules, array("field" => "ingreso.usucajero_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                array_push($rules, array("field" => "ingreso.fecha_crea", "data" => date("Y-m-d 00:00:00"), "op" => "ge"));
                array_push($rules, array("field" => "ingreso.tipo_id", "data" => $TipoDineroInicial->getClasificadorId(), "op" => "eq"));


                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $Ingreso = new Ingreso();

                $data = $Ingreso->getIngresosCustom("  ingreso.* ", "ingreso.ingreso_id", "asc", $SkeepRows, $MaxRows, $json, true);

                $data = json_decode($data);

                foreach ($data->data as $key => $value) {
                    $beginDay = true;
                }


            } elseif ($_SESSION["win_perfil2"] == "PUNTOVENTA") {

                if ($Usuario->fechaCierrecaja == "") {

                    $TipoDineroInicial = new Clasificador("", "ACCAMOUNTDAY");


                    $rules = [];
                    array_push($rules, array("field" => "ingreso.usucajero_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                    array_push($rules, array("field" => "ingreso.fecha_crea", "data" => date("Y-m-d 00:00:00"), "op" => "ge"));
                    array_push($rules, array("field" => "ingreso.tipo_id", "data" => $TipoDineroInicial->getClasificadorId(), "op" => "eq"));


                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json = json_encode($filtro);

                    $Ingreso = new Ingreso();

                    $data = $Ingreso->getIngresosCustom("  ingreso.* ", "ingreso.ingreso_id", "asc", $SkeepRows, $MaxRows, $json, true);

                    $data = json_decode($data);

                    foreach ($data->data as $key => $value) {
                        $beginDay = true;
                    }

                } else {
                    $beginDay = true;
                }


            } else {
                $beginDay = true;
            }*/


            /* Se inicializa un arreglo vacío llamado $finalMandante en PHP. */
            $finalMandante = [];

            if ($_SESSION["GlobalConfig"] == "S") {


                /* Código crea un array con información de un perfil y países asociados. */
                $array = [];
                $array["id"] = "-1";
                $array["value"] = "Virtualsoft";

                $array["Countries"] = array(
                    array(
                        "id" => "2",
                        "value" => "Nicaragua"
                    ),
                    array(
                        "id" => "173",
                        "value" => "Perú"
                    )
                );

                /* agrega un array y define un filtro con reglas para un mandante. */
                array_push($finalMandante, $array);

                $Mandante = new Mandante();

                $rules = [];
                // array_push($rules, array("field" => "proveedor.tipo", "data" => "CASINO ", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                /* Convierte un filtro a JSON y obtiene mandantes ordenados de una base de datos. */
                $json = json_encode($filtro);

                $mandantes = $Mandante->getMandantes("mandante.mandante", "asc", 0, 100, $json, true);
                $mandantes = json_decode($mandantes);


                foreach ($mandantes->data as $key => $value) {


                    /* Crea un arreglo con información de mandante y países relacionados. */
                    $array = [];
                    $array["id"] = $value->{"mandante.mandante"};
                    $array["value"] = $value->{"mandante.descripcion"};

                    $array["Countries"] = array(
                        array(
                            "id" => "2",
                            "value" => "Nicaragua"
                        ),
                        array(
                            "id" => "173",
                            "value" => "Perú"
                        )
                    );


                    /* Añade el contenido de `$array` al final de `$finalMandante`. */
                    array_push($finalMandante, $array);

                }
            } else {


                /* Se crea un array con información de sesión y países. */
                $array = [];

                $array["id"] = $_SESSION["mandante"];
                $array["value"] = $_SESSION["mandante"];

                $array["Countries"] = array(
                    array(
                        "id" => "2",
                        "value" => "Nicaragua"
                    ),
                    array(
                        "id" => "173",
                        "value" => "Perú"
                    )
                );


                /* La función añade un elemento al final de un array en PHP. */
                array_push($finalMandante, $array);

            }

            $response["Data"] = array(
                "AuthenticationStatus" => 0,
                "SaldoRecargas" => $SaldoRecargas,
                "SaldoJuego" => $SaldoJuego,
                "PartnerLimitType" => 1,
                "FirstName" => $Usuario->nombre,
                "Settings" => array(
                    "Language" => strtolower($Usuario->idioma),
                    "ReportCurrency" => $Usuario->monedaReporte,
                    "ReportCountry" => $ReportCountry,
                    "TimeZone" => $Usuario->timezone,

                ),
                "LangId" => strtolower($Usuario->idioma),
                "UserName" => $Usuario->nombre,
                "CurrencyId" => $Usuario->moneda,
                "UserId" => $Usuario->usuarioId,
                "UserId2" => $_SESSION['usuario2'],
                "AgentId" => $Usuario->usuarioId,
                "Countries" => $paisesparamenu,
                "BetShops" => $finalBetShops,
                "ProvidersCasino" => $finalProveedores,
                "CloseCashBox" => $hizoCierreCaja,
                "BeginDay" => $beginDay,

                "Partners" => $finalMandante,
                "PartnerSelected" => $_SESSION["mandante"],
                "GamesCasino" => $finalProductos,
                "PermissionList" => $menus_string,
            );
        }

    } catch (Exception $e) {
        /* Captura errores en autenticación y devuelve una respuesta estructurada con detalles. */


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

