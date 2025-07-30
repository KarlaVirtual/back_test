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
 * Verificar si el usuario esta logueado y retorna los datos de el usuario de BO
 *
 * @param string $MaxRows : Información para generar las SQL contiene el limit de la consulta
 * @param string $OrderedItem : Información para generar las SQL contiene la columna para ordenar la consulta
 * @param string $SkeepRows : Información para generar las SQL contiene el inicio de la consulta (paginación)
 *
 * @return object $response Objeto con los atributos de respuesta en caso de error o en caso de aprobación.
 *
 * Objeto en caso de error:
 *
 *  Si ya hubo un logueo
 * $response['HasError'] = true;
 * $response['AlertType'] = "danger";
 * $response['AlertMessage'] = "f";
 * $response['ModelErrors'] = [];
 * $response["Data"] = array(
 *          "AuthenticationStatus" => 0,
 *          "PermissionList" => array(),
 *          "Version" => "Virtualsoft - v8.1.0"
 * );
 *
 *  Por error de login general desconocido
 * $response["HasError"] = true;
 * $response["AlertType"] = "danger";
 * $response["AlertMessage"] = "Invalid Username and/or password ('.$e->getCode().')";
 * $response["ModelErrors"] = [];
 *
 * $response["Data"] = array(
 *          "AuthenticationStatus" => 0,
 *
 *          "PermissionList" => array(),
 *          "Version" => "Virtualsoft - v8.1.0",
 *);
 *
 * Objeto en caso de aprobación:
 *
 *  $response["HasError"] = false;
 *  $response["AlertType"] = "success";
 *  $response["AlertMessage"] = "";
 *  $response["ModelErrors"] = [];
 *  $response["Data"] = array ("LangId" => strtolower($Usuario->idioma),
 *                             "UserName" => $Usuario->nombre,
 *                             "CurrencyId" => $Usuario->moneda,
 *                             "UserId" => $Usuario->usuarioId,
 *                             "AgentId" => $Usuario->usuarioId,
 *                             "PermissionList" => $menus_string,
 *                             "Version" => "Virtualsoft - v8.1.0",
 *                                            .
 *                                            .
 *                                            .
 *                                     Otra info del usuario
 *);
 *
 * @throws Exception Maneja excepciones, generando respuesta de error al fallar la autenticación
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


//Verifica si ya hubo un logueo

/* Verifica si no está logueado y genera un mensaje de error. */
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


        /* Crea instancias de UsuarioMandante, Usuario y País utilizando datos de la sesión. */
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


        /* inicializa una respuesta y verifica la sesión global. */
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        if ($_SESSION["Global"] == "") {
            $_SESSION["Global"] == "N";
        }


        if ($_SESSION['usuario2'] == "5" && false) {
            //if ($_SESSION['usuario2'] == "163") {


            /* crea un array de menús en función de un usuario. */
            $menus = $Usuario->getMenus();
            $menus_string = array();
            array_push($menus_string, "ViewMenuSecurity");
            array_push($menus_string, "ViewMenuTeacher");
            array_push($menus_string, "ViewMenuManagement");
            array_push($menus_string, "ViewMenuCash");

            /* Se agregan elementos a un array de menú a partir de los datos proporcionados. */
            array_push($menus_string, "ViewMenuQueries");
            foreach ($menus as $key => $value) {
                array_push($menus_string, "view" . str_replace("_", "", str_replace(".php", "", $value["b.pagina"])));
            }

            $menus_string = obtenerMenu();

            /* obtiene proveedores de tipo "CASINO" de una función de reporte de países. */
            $paisesparamenu = obtenerPaisesReport();


            $Proveedor = new Proveedor();
            $Proveedor->setTipo("CASINO");

            $proveedores = $Proveedor->getProveedores();


            /* Crea un array final con proveedores y opción "Todos". */
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


            /* Crea un objeto proveedor, obtiene proveedores y los almacena en un array final. */
            $Proveedor = new Proveedor();
            $Proveedor->setTipo("LIVECASINO");

            $proveedores = $Proveedor->getProveedores();

            foreach ($proveedores as $key => $value) {

                $array = [];

                $array["id"] = $value->getProveedorId();
                $array["value"] = $value->getDescripcion();

                array_push($finalProveedores, $array);

            }


            /* Se crea un filtro de reglas para validar un tipo de proveedor en un producto. */
            $ProductoMandante = new ProductoMandante();

            $rules = [];
            array_push($rules, array("field" => "proveedor.tipo", "data" => "CASINO ", "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");

            /* Convierte datos en JSON y obtiene productos personalizados si la sesión es válida. */
            $json = json_encode($filtro);

            if ($_SESSION["Global"] == "S") {
                $Producto = new Producto();

                $productos = $Producto->getProductosCustom(" producto.* ", "producto.producto_id", "asc", $SkeepRows, $MaxRows, $json, true);
                $productos = json_decode($productos);
            } else {
                /* obtiene y decodifica productos de un mandante en formato JSON. */

                $productos = $ProductoMandante->getProductosMandanteCustom(" producto_mandante.*,mandante.*,producto.* ", "producto_mandante.prodmandante_id", "asc", $SkeepRows, $MaxRows, $json, true);
                $productos = json_decode($productos);

            }


            /* transforma productos en un nuevo formato según la configuración global de sesión. */
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


            /* Se define un conjunto de reglas basado en la sesión de usuario. */
            $rules = [];

            if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
                $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

                array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION['usuario'], "op" => "eq"));
                array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            }


            /* Código PHP que establece reglas basadas en la sesión del usuario y su perfil. */
            if ($_SESSION["win_perfil"] == "CONCESIONARIO2") {
                $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

                array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            }


            /* Código verifica el perfil de usuario y agrega reglas a un array en consecuencia. */
            if ($_SESSION["win_perfil"] == "PUNTOVENTA") {
                $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

                array_push($rules, array("field" => "concesionario.usuhijo_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            }

            /* Agrega reglas de validación basadas en el perfil y, opcionalmente, en el país. */
            array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));


            // Si el usuario esta condicionado por País
            if ($_SESSION['PaisCond'] == "S") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
            }
            // Si el usuario esta condicionado por el mandante y no es de Global

            /* Condiciona reglas de filtrado según la sesión del usuario, excluyendo reportes para Colombia. */
            if ($_SESSION['Global'] == "N") {
                array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
            }
            // Inactivamos reportes para el país Colombia
            //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


            $filtro = array("rules" => $rules, "groupOp" => "AND");

            /* convierte datos a JSON y obtiene información de puntos de venta personalizados. */
            $jsonbetshop = json_encode($filtro);


            $PuntoVenta = new PuntoVenta();


            $mandantes = $PuntoVenta->getPuntoVentasCustom("usuario.estado_valida,usuario.fecha_crea,usuario.moneda,usuario.fecha_ult,punto_venta.*", "punto_venta.puntoventa_id", "asc", 0, 100000, $jsonbetshop, true);


            /* Convierte datos de JSON a un array con ID y descripción de puntos de venta. */
            $mandantes = json_decode($mandantes);


            $finalBetShops = [];

            foreach ($mandantes->data as $key => $value) {

                $array = [];

                $array["id"] = $value->{"punto_venta.usuario_id"};
                $array["value"] = $value->{"punto_venta.descripcion"};

                array_push($finalBetShops, $array);

            }


            /* extrae información de una colección y la organiza en un arreglo. */
            $finalBetShops = [];

            foreach ($mandantes->data as $key => $value) {

                $array = [];

                $array["id"] = $value->{"punto_venta.usuario_id"};
                $array["value"] = $value->{"punto_venta.descripcion"};

                array_push($finalBetShops, $array);

            }


            /* verifica el perfil del usuario para ajustar la variable $ReportCountry. */
            $ReportCountry = $Usuario->paisId;

            if ($_SESSION["win_perfil2"] == "ADMIN" || $_SESSION["win_perfil2"] == "ADMIN2" || $_SESSION["win_perfil2"] == "OPERADOR" || $_SESSION["win_perfil2"] == "SA") {
                if ($_SESSION['PaisCond'] != "S") {
                    $ReportCountry = '0';
                }

            }


            /* verifica el perfil de usuario y obtiene saldos de recargas y juego. */
            $SaldoRecargas = 0;
            $SaldoJuego = 0;
            if ($_SESSION["win_perfil2"] == "CONCESIONARIO" || $_SESSION["win_perfil2"] == "CONCESIONARIO2" || $_SESSION["win_perfil2"] == "PUNTOVENTA") {
                $PuntoVenta = new PuntoVenta("", $_SESSION["usuario"]);

                $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                $SaldoJuego = $PuntoVenta->getCreditosBase();
            }


            /* Se inicializa un array vacío llamado $finalMandante en PHP. */
            $finalMandante = [];

            if ($_SESSION["GlobalConfig"] == "S") {


                /* crea un array con información de una empresa y sus países. */
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

                /* Se agrega un array a $finalMandante y se inicializa un objeto Mandante. */
                array_push($finalMandante, $array);


                $Mandante = new Mandante();

                $rules = [];
                // array_push($rules, array("field" => "proveedor.tipo", "data" => "CASINO ", "op" => "eq"));


                /* Crea un filtro en JSON y obtiene datos de mandantes ordenados ascendentemente. */
                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $mandantes = $Mandante->getMandantes("mandante.mandante", "asc", 0, 100, $json, true);
                $mandantes = json_decode($mandantes);


                /* itera sobre mandantes y genera un array con información estructurada. */
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
                /* Crea un arreglo con datos del mandante y países específicos. */

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


            /* Se crea un objeto y se asignan parámetros desde la sesión y los parámetros. */
            $PerfilSubmenu = new PerfilSubmenu();

            $Perfil_id = $_SESSION["win_perfil2"];
            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;


            /* asigna valores predeterminados si variables son vacías. */
            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }


            /* establece condiciones para configurar un límite de filas y un filtro en JSON. */
            if ($MaxRows == "") {
                $MaxRows = 100000;
            }

            $mismenus = "0";

            $json = '{"rules" : [{"field" : "menu.version", "data" : "2","op":"eq"},{"field" : "perfil_submenu.perfil_id", "data" : "' . $Perfil_id . '","op":"eq"}] ,"groupOp" : "AND"}';


            /* obtiene y decodifica submenús personalizados en formato JSON para su uso. */
            $menus = $PerfilSubmenu->getPerfilSubmenusCustom(" menu.*,submenu.*,perfil_submenu.* ", "menu.menu_id", "asc", $SkeepRows, $MaxRows, $json, true);

            $menus = json_decode($menus);

            $menus3 = [];
            $arrayf = [];

            /* Se inicializan dos arreglos vacíos para almacenar submenús y cadenas de menú. */
            $submenus = [];

            $menus_string = array();

            foreach ($menus->data as $key => $value) {


                /* Se asignan valores de menú y submenú a arreglos asociativos en PHP. */
                $m = [];
                $m["Id"] = $value->{"menu.menu_id"};
                $m["Name"] = $value->{"menu.descripcion"};

                $array = [];

                $array["Id"] = $value->{"submenu.submenu_id"};

                /* asigna valores a un array y concatena identificadores de menús. */
                $array["Name"] = $value->{"submenu.descripcion"};
                $array["Pagina"] = $value->{"submenu.pagina"};
                $array["IsGiven"] = true;
                $array["Action"] = "view";

                $mismenus = $mismenus . "," . $array["Id"];


                /* verifica condiciones y agrega elementos a arrays en PHP. */
                if ($arrayf["Id"] != "" && $m["Id"] != $arrayf["Id"]) {
                    array_push($menus_string, $arrayf["Pagina"]);

                    $arrayf["Permissions"] = $submenus;
                    array_push($menus3, $arrayf);
                    // $submenus = [];
                }

                /* Agrega elementos a arreglos con datos de un menú en PHP. */
                array_push($menus_string, $array["Pagina"]);

                $arrayf["Id"] = $value->{"menu.menu_id"};
                $arrayf["Name"] = $value->{"menu.descripcion"};
                $arrayf["Pagina"] = $value->{"menu.pagina"};

                array_push($submenus, $array);
            }

            /* Agrega elementos a arrays y asigna permisos a estructuras de datos. */
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


            /* obtiene menús y países, y configura un proveedor como "CASINO". */
            $menus_string = obtenerMenu();
            $paisesparamenu = obtenerPaisesReport();


            $Proveedor = new Proveedor();
            $Proveedor->setTipo("CASINO");


            /* Código obtiene proveedores y crea un array inicial con opción "Todos". */
            $proveedores = $Proveedor->getProveedores();

            $finalProveedores = [];
            $array = [];
            $array["id"] = '0';
            $array["value"] = 'Todos';

            /* Agrega proveedores a un arreglo final extrayendo id y descripción de cada proveedor. */
            array_push($finalProveedores, $array);

            foreach ($proveedores as $key => $value) {

                $array = [];

                $array["id"] = $value->getProveedorId();
                $array["value"] = $value->getDescripcion();

                array_push($finalProveedores, $array);

            }


            /* Se crea un proveedor y se recopilan sus datos en un array. */
            $Proveedor = new Proveedor();
            $Proveedor->setTipo("LIVECASINO");

            $proveedores = $Proveedor->getProveedores();

            foreach ($proveedores as $key => $value) {

                $array = [];

                $array["id"] = $value->getProveedorId();
                $array["value"] = $value->getDescripcion();

                array_push($finalProveedores, $array);

            }


            /* Código que define reglas de filtrado para un objeto ProductoMandante en PHP. */
            $ProductoMandante = new ProductoMandante();

            $rules = [];
            array_push($rules, array("field" => "proveedor.tipo", "data" => "CASINO ", "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");

            /* Codifica un filtro JSON y obtiene productos personalizados según configuración de sesión. */
            $json = json_encode($filtro);

            if ($_SESSION["GlobalConfig"] == "S") {
                $Producto = new Producto();

                $productos = $Producto->getProductosCustom(" producto.* ", "producto.producto_id", "asc", $SkeepRows, $MaxRows, $json, true);
                $productos = json_decode($productos);
            } else {
                /* obtiene productos de un mandante y los decodifica en formato JSON. */

                $productos = $ProductoMandante->getProductosMandanteCustom(" producto_mandante.*,mandante.*,producto.* ", "producto_mandante.prodmandante_id", "asc", $SkeepRows, $MaxRows, $json, true);
                $productos = json_decode($productos);

            }


            /* genera un arreglo final de productos basado en la configuración global. */
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


            /* define reglas de acceso para usuarios con perfil "CONCESIONARIO". */
            $rules = [];

            if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
                $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

                array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION['usuario'], "op" => "eq"));
                array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            }


            /* Código que agrega reglas a un arreglo basado en la sesión de usuario. */
            if ($_SESSION["win_perfil"] == "CONCESIONARIO2") {
                $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

                array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            }


            /* Verifica el perfil de usuario y agrega reglas específicas a un arreglo. */
            if ($_SESSION["win_perfil"] == "PUNTOVENTA") {
                $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

                array_push($rules, array("field" => "concesionario.usuhijo_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            }


            /* Agrega reglas de validación condicionales según el perfil y el país del usuario. */
            array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));


            // Si el usuario esta condicionado por País
            if ($_SESSION['PaisCond'] == "S") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
            }
            // Si el usuario esta condicionado por el mandante y no es de Global

            /* Condicional que añade reglas según sesión global y filtra reportes para Colombia. */
            if ($_SESSION['Global'] == "N") {
                array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
            }
            // Inactivamos reportes para el país Colombia
            //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


            $filtro = array("rules" => $rules, "groupOp" => "AND");

            /* convierte un filtro a JSON y obtiene datos de punto de venta. */
            $jsonbetshop = json_encode($filtro);


            $PuntoVenta = new PuntoVenta();


            $mandantes = $PuntoVenta->getPuntoVentasCustom("usuario.estado_valida,usuario.fecha_crea,usuario.moneda,usuario.fecha_ult,punto_venta.*", "punto_venta.puntoventa_id", "asc", 0, 100000, $jsonbetshop, true);


            /* Decodifica JSON y crea un array con id y descripción de puntos de venta. */
            $mandantes = json_decode($mandantes);


            $finalBetShops = [];

            foreach ($mandantes->data as $key => $value) {

                $array = [];

                $array["id"] = $value->{"punto_venta.usuario_id"};
                $array["value"] = $value->{"punto_venta.descripcion"};

                array_push($finalBetShops, $array);

            }


            /* Condiciona el país de reporte basado en el perfil del usuario y sesión actual. */
            $ReportCountry = $Usuario->paisId;
            if ($_SESSION["win_perfil2"] == "ADMIN" || $_SESSION["win_perfil2"] == "ADMIN2" || $_SESSION["win_perfil2"] == "OPERADOR" || $_SESSION["win_perfil2"] == "SA") {
                if ($_SESSION['PaisCond'] != "S") {
                    $ReportCountry = '0';
                }
            }


            /* Código que inicializa saldos basado en el perfil de usuario en sesión. */
            $SaldoRecargas = 0;
            $SaldoJuego = 0;
            if ($_SESSION["win_perfil2"] == "CONCESIONARIO" || $_SESSION["win_perfil2"] == "CONCESIONARIO2" || $_SESSION["win_perfil2"] == "PUNTOVENTA") {
                $PuntoVenta = new PuntoVenta("", $_SESSION["usuario"]);

                $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                $SaldoJuego = $PuntoVenta->getCreditosBase();
            }


            /* Verifica si la fecha de cierre de caja corresponde a hoy. */
            $fechaCierre = date("Y-m-d", strtotime($Usuario->fechaCierrecaja));
            $hizoCierreCaja = false;

            if ($fechaCierre == date("Y-m-d")) {
                $hizoCierreCaja = true;
            }


            /* Inicializa la variable $beginDay como falso en un contexto de programación. */
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


            /* Se inicializa un array vacío llamado $finalMandante en PHP. */
            $finalMandante = [];

            if ($_SESSION["GlobalConfig"] == "S") {


                /* Crea un array con información de un elemento y sus países asociados. */
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

                /* agrega elementos a un array y define reglas de filtrado. */
                array_push($finalMandante, $array);

                $Mandante = new Mandante();

                $rules = [];
                // array_push($rules, array("field" => "proveedor.tipo", "data" => "CASINO ", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                /* convierte un filtro a JSON y obtiene mandantes ordenados. */
                $json = json_encode($filtro);

                $mandantes = $Mandante->getMandantes("mandante.mandante", "asc", 0, 100, $json, true);
                $mandantes = json_decode($mandantes);


                foreach ($mandantes->data as $key => $value) {


                    /* Crea un array con información del "mandante" y países asociados. */
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


                    /* Agrega el contenido de `$array` al final de `$finalMandante`. */
                    array_push($finalMandante, $array);

                }
            } else {


                /* Se crea un array asociativo con datos de sesión y países. */
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


                /* agrega el contenido de `$array` a `$finalMandante` usando `array_push`. */
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
        /* Maneja excepciones, generando respuesta de error al fallar la autenticación. */


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

