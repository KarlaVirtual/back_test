<?php

use Backend\dto\Pais;
use Backend\dto\Ingreso;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\PuntoVenta;
use Backend\dto\Clasificador;
use Backend\dto\PaisMandante;
use Backend\dto\Subproveedor;
use Backend\dto\PerfilSubmenu;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;

/**
 * Account/CheckUserLoginPassword
 *
 * Verificar si el usuario esta logueado y retornar los datos de el usuario
 *
 * @return array $response
 *  - HasError: boolean Indica si hubo un error
 *  - AlertType: string Indica el tipo de alerta
 *  - AlertMessage: string Mensaje de alerta
 *  - ModelErrors: array Lista de errores de validación
 *  - Data: array Datos de la respuesta
 *    - AuthenticationStatus: int Estado de autenticación
 *    - PermissionList: array Lista de permisos
 */

//Verifica si ya hubo un logueo

/* verifica si el usuario está logueado y retorna un error si no. */
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

        // Obtenemos UsuarioMandante de el usuario actual

        /* crea objetos de usuario y país basado en la sesión actual. */
        $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);
        $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

        // Obtenemos el pais de el usuario actual
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


        /* Código inicializa respuesta y verifica si la sesión del usuario es global. */
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        // Verificamos si el usuario es Global
        if ($_SESSION["Global"] == "") {
            $_SESSION["Global"] == "N";
        }


        if ($_SESSION['usuario2'] == "5" && false) {
            //if ($_SESSION['usuario2'] == "163") {


            /* crea un array con permisos de menú para un usuario específico. */
            $menus = $Usuario->getMenus();
            $menus_string = array();
            array_push($menus_string, "ViewMenuSecurity");
            array_push($menus_string, "ViewMenuTeacher");
            array_push($menus_string, "ViewMenuManagement");
            array_push($menus_string, "ViewMenuCash");

            /* agrega elementos a un arreglo y obtiene un menú. */
            array_push($menus_string, "ViewMenuQueries");
            foreach ($menus as $key => $value) {
                array_push($menus_string, "view" . str_replace("_", "", str_replace(".php", "", $value["b.pagina"])));
            }

            $menus_string = obtenerMenu();

            /* Código que obtiene países y configura un proveedor como tipo "CASINO". */
            $paisesparamenu = obtenerPaisesReport();


            $Proveedor = new Proveedor();
            $Proveedor->setTipo("CASINO");

            $paramMandante = '';

            /* Asigna valor a $paramMandante según el estado de $_SESSION['Global']. */
            if ($_SESSION['Global'] == "N") {
                $paramMandante = $_SESSION['mandante'];
            } else {
                $paramMandante = $_SESSION["mandanteLista"];

            }

            /* Convierte `paramMandante` a minúsculas y obtiene proveedores con ese parámetro. */
            $paramMandante = strtolower($paramMandante);

            $proveedores = $Proveedor->getProveedores($paramMandante, 'A');

            $finalProveedores = [];
            $array = [];

            /* construye un array de proveedores con identificadores y descripciones. */
            $array["id"] = '0';
            $array["value"] = 'Todos';
            array_push($finalProveedores, $array);

            foreach ($proveedores as $key => $value) {

                $array = [];

                $array["id"] = $value->getProveedorId();
                $array["value"] = $value->getDescripcion();

                array_push($finalProveedores, $array);

            }


            /* Se crea un objeto Proveedor y se establece su tipo condicionalmente según la sesión. */
            $Proveedor = new Proveedor();
            $Proveedor->setTipo("LIVECASINO");

            $paramMandante = '';
            if ($_SESSION['Global'] == "N") {
                $paramMandante = $_SESSION['mandante'];
            } else {
                /* Se asigna la variable $paramMandante con datos de la sesión "mandanteLista". */

                $paramMandante = $_SESSION["mandanteLista"];


            }


            /* obtiene proveedores y los almacena en un array estructurado. */
            $proveedores = $Proveedor->getProveedores($paramMandante, 'A');

            foreach ($proveedores as $key => $value) {

                $array = [];

                $array["id"] = $value->getProveedorId();
                $array["value"] = $value->getDescripcion();

                array_push($finalProveedores, $array);

            }


            /* Se crea un objeto Proveedor y se inicializa su tipo basado en la sesión. */
            $Proveedor = new Proveedor();
            $Proveedor->setTipo("VIRTUAL");

            $paramMandante = '';
            if ($_SESSION['Global'] == "N") {
                $paramMandante = $_SESSION['mandante'];
            } else {
                /* Se establece una variable con datos de sesión si la condición no se cumple. */

                $paramMandante = $_SESSION["mandanteLista"];

            }


            /* obtiene proveedores y los almacena en un arreglo final. */
            $proveedores = $Proveedor->getProveedores($paramMandante, 'A');

            foreach ($proveedores as $key => $value) {

                $array = [];

                $array["id"] = $value->getProveedorId();
                $array["value"] = $value->getDescripcion();

                array_push($finalProveedores, $array);

            }


            /*
            * Obtenemos los Subproveedores de casino disponibles para el usuario
            */


            /* Se crea un objeto Subproveedor y se asigna un tipo basado en una sesión. */
            $Subproveedor = new Subproveedor();
            $Subproveedor->setTipo("CASINO");

            $paramMandante = '';
            if ($_SESSION['Global'] == "N") {
                $paramMandante = $_SESSION['mandante'];
            } else {
                /* Se asigna el valor de la sesión "mandanteLista" a la variable $paramMandante. */

                $paramMandante = $_SESSION["mandanteLista"];

            }

            /* convierte a minúsculas el parámetro y obtiene subproveedores activos. */
            $paramMandante = strtolower($paramMandante);

            $proveedores = $Subproveedor->getSubproveedores($paramMandante, 'A');

            $finalSubProveedores = [];
            $array = [];

            /* Crea un array con subproveedores y sus descripciones, incluyendo proveedores relacionados. */
            $array["id"] = '0';
            $array["value"] = 'Todos';
            array_push($finalSubProveedores, $array);

            foreach ($proveedores as $key => $value) {

                $array = [];

                $array["id"] = $value->getSubproveedorId();
                $array["value"] = $value->getDescripcion();

                foreach ($finalProveedores as $finalProveedore) {
                    if ($finalProveedore["id"] == $value->getProveedorId()) {
                        $array["value"] = $array["value"] . ' (' . $finalProveedore["value"] . ')';
                    }

                }

                array_push($finalSubProveedores, $array);

            }

            /*
             * Obtenemos los subproveedores de casino en vivo disponibles para el usuario
             */

            /* Se crea un objeto Subproveedor y se establece su tipo según la sesión. */
            $Subproveedor = new Subproveedor();
            $Subproveedor->setTipo("LIVECASINO");

            $paramMandante = '';
            if ($_SESSION['Global'] == "N") {
                $paramMandante = $_SESSION['mandante'];
            } else {
                /* Secuencia para asignar a $paramMandante el valor de una sesión de usuario. */

                $paramMandante = $_SESSION["mandanteLista"];

            }

            /* Convierte nombres a minúsculas y agrupa subproveedores con sus descripciones respectivas. */
            $paramMandante = strtolower($paramMandante);

            $proveedores = $Subproveedor->getSubproveedores($paramMandante, 'A');

            foreach ($proveedores as $key => $value) {

                $array = [];

                $array["id"] = $value->getSubproveedorId();
                $array["value"] = $value->getDescripcion();

                foreach ($finalProveedores as $finalProveedore) {
                    if ($finalProveedore["id"] == $value->getProveedorId()) {
                        $array["value"] = $array["value"] . ' (' . $finalProveedore["value"] . ')';
                    }

                }

                array_push($finalSubProveedores, $array);

            }


            /* Se crea un objeto Subproveedor y se configura su tipo según la sesión. */
            $Subproveedor = new Subproveedor();
            $Subproveedor->setTipo("VIRTUAL");

            $paramMandante = '';
            if ($_SESSION['Global'] == "N") {
                $paramMandante = $_SESSION['mandante'];
            } else {
                /* asigna el valor de "mandanteLista" a $paramMandante si no se cumple una condición. */

                $paramMandante = $_SESSION["mandanteLista"];

            }

            /* Convierte parámetros a minúsculas y genera un arreglo con proveedores relacionados. */
            $paramMandante = strtolower($paramMandante);

            $proveedores = $Subproveedor->getSubproveedores($paramMandante, 'A');

            foreach ($proveedores as $key => $value) {

                $array = [];

                $array["id"] = $value->getSubproveedorId();
                $array["value"] = $value->getDescripcion();

                foreach ($finalProveedores as $finalProveedore) {
                    if ($finalProveedore["id"] == $value->getProveedorId()) {
                        $array["value"] = $array["value"] . ' (' . $finalProveedore["value"] . ')';
                    }

                }

                array_push($finalSubProveedores, $array);

            }


            /* Se define un filtro de reglas para productos de un proveedor específico. */
            $ProductoMandante = new ProductoMandante();

            $rules = [];
            array_push($rules, array("field" => "proveedor.tipo", "data" => "CASINO ", "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");

            /* Codifica un filtro en JSON y obtiene productos según la sesión activa. */
            $json = json_encode($filtro);

            if ($_SESSION["Global"] == "S") {
                $Producto = new Producto();

                $productos = $Producto->getProductosCustom(" producto.* ", "producto.producto_id", "asc", $SkeepRows, $MaxRows, $json, true);
                $productos = json_decode($productos);
            } else {
                /* Agrega reglas de filtrado para obtener productos mandante según condiciones específicas. */

                array_push($rules, array("field" => "producto_mandante.mandante", "data" => $_SESSION["mandante"], "op" => "eq"));
                array_push($rules, array("field" => "proveedor_mandante.estado", "data" => 'A', "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $productos = $ProductoMandante->getProductosMandanteCustom(" producto_mandante.*,mandante.*,producto.* ", "producto_mandante.prodmandante_id", "asc", $SkeepRows, $MaxRows, $json, true);
                $productos = json_decode($productos);

            }


            /* procesa productos y genera un array según la sesión global. */
            $finalProductos = [];

            foreach ($productos->data as $key => $value) {

                $array = [];
                if ($_SESSION["Global"] == "S") {
                    $array["id"] = $value->{"producto.producto_id"};
                    $array["value"] = $value->{"producto.descripcion"};

                } else {
                    $array["id"] = $value->{"producto_mandante.prodmandante_id"};
                    $array["value"] = $value->{"producto.descripcion"};

                }
                array_push($finalProductos, $array);

            }


            /* Se definen reglas de validación para usuarios con perfil "CONCESIONARIO". */
            $rules = [];

            if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
                $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

                array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION['usuario'], "op" => "eq"));
                array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            }


            /* Código que define reglas dependiendo del perfil del usuario en sesión. */
            if ($_SESSION["win_perfil"] == "CONCESIONARIO2") {
                $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

                array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            }


            /* verifica el perfil de sesión y define reglas para concesionarios. */
            if ($_SESSION["win_perfil"] == "PUNTOVENTA") {
                $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

                array_push($rules, array("field" => "concesionario.usuhijo_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            }

            /* Agrega reglas de validación según el perfil de usuario y condiciones de país. */
            array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));


            // Si el usuario esta condicionado por País
            if ($_SESSION['PaisCond'] == "S") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
            }
            // Si el usuario esta condicionado por el mandante y no es de Global

            /* Condiciona reglas basadas en la variable de sesión 'Global' y 'mandanteLista'. */
            if ($_SESSION['Global'] == "N") {
                array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
            } else {

                if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                    array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                }

            }

            // Inactivamos reportes para el país Colombia

            /* Agrega reglas de filtrado y las convierte a formato JSON para procesamiento posterior. */
            array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $jsonbetshop = json_encode($filtro);


            $PuntoVenta = new PuntoVenta();


            /* Extrae datos de puntos de venta, decodifica JSON y prepara un arreglo final. */
            $mandantes = $PuntoVenta->getPuntoVentasCustom("usuario.estado_valida,usuario.fecha_crea,usuario.moneda,usuario.fecha_ult,punto_venta.*", "punto_venta.puntoventa_id", "asc", 0, 100000, $jsonbetshop, true);

            $mandantes = json_decode($mandantes);


            $finalBetShops = [];


            /* Itera sobre datos, extrae información y la agrega a un nuevo array. */
            foreach ($mandantes->data as $key => $value) {

                $array = [];

                $array["id"] = $value->{"punto_venta.usuario_id"};
                $array["value"] = $value->{"punto_venta.descripcion"};

                array_push($finalBetShops, $array);

            }


            /* crea un array de tiendas de apuestas a partir de datos de "mandantes". */
            $finalBetShops = [];

            foreach ($mandantes->data as $key => $value) {

                $array = [];

                $array["id"] = $value->{"punto_venta.usuario_id"};
                $array["value"] = $value->{"punto_venta.descripcion"};

                array_push($finalBetShops, $array);

            }


            /* Validación de permisos de usuario y ajuste de país en función de sesión. */
            $ReportCountry = $Usuario->paisId;

            if ($_SESSION["win_perfil2"] == "ADMIN" || $_SESSION["win_perfil2"] == "ADMIN2" || $_SESSION["win_perfil2"] == "OPERADOR" || $_SESSION["win_perfil2"] == "SA") {
                if ($_SESSION['PaisCond'] != "S") {
                    $ReportCountry = '0';
                }

            }


            /* Inicializa saldos y obtiene datos del usuario según su perfil de concesionario. */
            $SaldoRecargas = 0;
            $SaldoJuego = 0;
            if ($_SESSION["win_perfil2"] == "CONCESIONARIO" || $_SESSION["win_perfil2"] == "CONCESIONARIO2" || $_SESSION["win_perfil2"] == "PUNTOVENTA") {
                $PuntoVenta = new PuntoVenta("", $_SESSION["usuario"]);

                $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                $SaldoJuego = $PuntoVenta->getCreditosBase();
            }


            /* Verifica el perfil del usuario y obtiene datos de un punto de venta. */
            if ($_SESSION["win_perfil2"] == "CAJERO") {
                $PuntoVenta = new PuntoVenta("", $Usuario->puntoventaId);

                $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                $SaldoJuego = $PuntoVenta->getCreditosBase();
            }


            /* Se inicializa un arreglo vacío llamado $finalMandante en PHP. */
            $finalMandante = [];

            if ($_SESSION["GlobalConfig"] == "S") {


                /* Crea un array asociativo con identificador y un país predeterminado. */
                $array = [];
                $array["id"] = "-1";
                $array["value"] = "Virtualsoft";

                $array["Countries"] = array(
                    array(
                        "id" => "",
                        "value" => "Todos"
                    )
                );


                /* Se crean reglas de filtro para una consulta basada en condiciones específicas. */
                $rules = [];

                array_push($rules, array("field" => "pais_mandante.mandante", "data" => $array["id"], "op" => "eq"));
                array_push($rules, array("field" => "pais_mandante.estado", "data" => "A", "op" => "eq"));


                $filtro = array("rules" => $rules, "groupOp" => "AND");

                /* codifica un filtro JSON y obtiene datos de "PaisMandante". */
                $json = json_encode($filtro);

                $PaisMandante = new PaisMandante();

                $paises = $PaisMandante->getPaisMandantesCustom(" pais_mandante.*,pais.* ", "pais_mandante.paismandante_id", "asc", 0, 1000, $json, true);
                $paises = json_decode($paises);


                /* Autocompleta un arreglo con datos de países a partir de un objeto JSON. */
                foreach ($paises->data as $key2 => $value2) {
                    $array2 = [];
                    $array2["id"] = $value2->{"pais_mandante.pais_id"};
                    $array2["value"] = $value2->{"pais.pais_nom"};
                    array_push($array["Countries"], $array2);

                }

                /* Se agrega un array a `$finalMandante` y se inicializa un objeto `Mandante`. */
                array_push($finalMandante, $array);


                $Mandante = new Mandante();

                $rules = [];


                /* Se añaden reglas de filtrado si hay datos en la sesión. */
                if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                    array_push($rules, array("field" => "mandante.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                }
                // array_push($rules, array("field" => "proveedor.tipo", "data" => "CASINO ", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                /* Codifica un filtro a JSON y obtiene mandantes ordenados en un rango específico. */
                $json = json_encode($filtro);

                $mandantes = $Mandante->getMandantes("mandante.mandante", "asc", 0, 100, $json, true);
                $mandantes = json_decode($mandantes);


                foreach ($mandantes->data as $key => $value) {

                    /* Crea un array asociativo con información de mandante y un país predeterminado. */
                    $array = [];
                    $array["id"] = $value->{"mandante.mandante"};
                    $array["value"] = $value->{"mandante.descripcion"};
                    $array["Countries"] = array(
                        array(
                            "id" => "",
                            "value" => "Todos"
                        )
                    );


                    /* crea un filtro de reglas para condiciones de búsqueda en una base de datos. */
                    $rules = [];

                    array_push($rules, array("field" => "pais_mandante.mandante", "data" => $array["id"], "op" => "eq"));
                    array_push($rules, array("field" => "pais_mandante.estado", "data" => "A", "op" => "eq"));


                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    /* convierte un filtro a JSON, recupera datos de países y los decodifica. */
                    $json = json_encode($filtro);

                    $PaisMandante = new PaisMandante();

                    $paises = $PaisMandante->getPaisMandantesCustom(" pais_mandante.*,pais.* ", "pais_mandante.paismandante_id", "asc", 0, 1000, $json, true);
                    $paises = json_decode($paises);


                    /* recorre países y crea un arreglo con id y nombre. */
                    foreach ($paises->data as $key2 => $value2) {
                        $array2 = [];
                        $array2["id"] = $value2->{"pais_mandante.pais_id"};
                        $array2["value"] = $value2->{"pais.pais_nom"};
                        array_push($array["Countries"], $array2);

                    }


                    /* añade el contenido de `$array` a `$finalMandante`. */
                    array_push($finalMandante, $array);

                }
            } else {

                /* crea un array con información de sesión y un país por defecto. */
                $array = [];

                $array["id"] = $_SESSION["mandante"];
                $array["value"] = $_SESSION["mandante"];

                $array["Countries"] = array(
                    array(
                        "id" => "",
                        "value" => "Todos"
                    )
                );


                /* Se define un filtro con reglas de comparación para las propiedades de "pais_mandante". */
                $rules = [];

                array_push($rules, array("field" => "pais_mandante.mandante", "data" => $array["id"], "op" => "eq"));
                array_push($rules, array("field" => "pais_mandante.estado", "data" => "A", "op" => "eq"));


                $filtro = array("rules" => $rules, "groupOp" => "AND");

                /* Codifica un filtro a JSON, obtiene y decodifica datos de países mandantes. */
                $json = json_encode($filtro);

                $PaisMandante = new PaisMandante();

                $paises = $PaisMandante->getPaisMandantesCustom(" pais_mandante.*,pais.* ", "pais_mandante.paismandante_id", "asc", 0, 1000, $json, true);
                $paises = json_decode($paises);


                /* Recorre países, extrae ID y nombre, y los agrega a un array. */
                foreach ($paises->data as $key2 => $value2) {
                    $array2 = [];
                    $array2["id"] = $value2->{"pais_mandante.pais_id"};
                    $array2["value"] = $value2->{"pais.pais_nom"};
                    array_push($array["Countries"], $array2);

                }
            }


            /* Se inicializa un array vacío llamado $MediaLibrary en PHP. */
            $MediaLibrary = array();

            $response["Data"] = array(
                "MediaLibrary" => $MediaLibrary,
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
                "SubProvidersCasino" => $finalSubProveedores,
                "PermissionList2" => array_merge(array("ManageDepositRequests",
                    "ManageWithdrawalRequests", "ManageUsers", "ViewClientBonuses", "ViewPlayers", "ViewAddHocReport", "ViewScout", "ViewCMS", "ViewAffiliate", "SGPlayersView", "SGStatisticsRake", "ViewFinancialReports", "ViewPaymentReport", "AssignAgentCredit", "ManageAgentCredit", "ViewAgentGroups", "ViewAgentCommissionGroups", "ViewAgentPtGroups", "ViewAgentBetLimitGroups", "ViewAgentGroups", "ViewAgentGroups", "ManageAgentCommissionGroups", "ManageAgentBetLimitGroups", "ManageAgentGroups", "ManageClientCredit", "ViewGames", "ViewClientSportBets", "ViewClientTransactions", "ViewClientLogins", "ViewClientCasinoBets", "ViewSportReport", "ViewMenuDashBoard", "ViewDashBoardActivePlayers", "ViewDashBoardNewRegistrations", "ViewDashBoardSportBets", "ViewDashBoardCasinoBets", "ViewDashBoardTopFiveGames", "ViewDashBoardTopSportsByStake", "ViewDashBoardTopFiveSportsbookPlayers", "ViewDashBoardTopFivePlayers", "ViewUsers", "ViewUsersMenu", "ViewUsersLogs", "ViewAgentTransfers", "ViewBalance", "ViewDepositWithdrawalReport", "PMManageSale", "PMManageProduct", "ViewSalesReport", "ViewTurnoverTaxReport", "ViewDepositRequests", "ViewWithdrawalRequests", "ViewDocuments", "ViewFinancialOperations", "ManageAgent", "ViewBetShopUsers", "ViewCashDesks", "ManageBetShopUsers", "ViewClientMessage", "ViewVerificationStep", "ResetClientPassword", "ViewAgentMenu", "ViewAgentSystem", "ViewAgent", "ViewAgentSubAccounts", "ViewAgentMembers", "ViewMessages", "ViewEmailTemplates", "ManageMessages", "ViewTranslation", "ViewSettings", "ViewStream", "PMViewPartner", "PMViewProduct", "PMViewSale", "hjkhjkhjk", "jhkhjkhjk", "ViewOddsFeed", "ViewMatch", "ViewSportLimits", "ViewPartnersBooking", "ViewMenuReport", "ViewCasinoReport", "ViewCashDeskReport", "ViewCRM", "ViewSegment", "CreateSegment", "ViewBetShops", "ViewClients", "ManageClients", "ViewMenuSecurity", "ViewMenuTeacher", "AdminUser", "Contingencia", "Menu", "Perfil", "PerfilOpciones", "Submenu", "UsuarioPerfil", "Clasificador", "Concesionario", "ViewAddHocReport", "ViewMenuManagement", "ActivarRegistros", "AjustarSaldo", "AsignacionCupo", "Bonos", "CuentaCobroEliminar", "GestionRed", "RegistroRapido", "ChequeReimpresion", "RecargaReversion", "GestionContacto", "ViewMenuCash", "FlujoCaja", "PagoPremio", "PagoNotaRetiro", "RecargarCredito", "ViewMenuQueries", "FlujoCajaHistorico", "FlujoCajaResumido", "InformeCasino", "InformeGerencial", "ListadoRecargasRetiros", "PremiosPendientesPagar", "ConsultaOnlineDetalle", "ConsultaOnlineResumen", "MakeCorrection", "Trabajaconnosotros", "ViewSportsBookReports", "ViewBetReport", "ViewSportReport", "ViewCompetitionReport", "ViewMarketReport", "ViewSports", "ViewCompetitions", "ViewClientLogHistory", "ManageTranslation", "ManageProviders", "ManagePartnerProducts"

                ), $menus_string),

            );
        } else {

            //Obtenemos el menu de el usuario actual


            /* Se inicializa un objeto y se obtienen parámetros de una sesión. */
            $PerfilSubmenu = new PerfilSubmenu();
            $Perfil_id = $_SESSION["win_perfil2"];

            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;


            /* inicializa variables si no tienen un valor asignado. */
            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }


            /* establece un valor por defecto para $MaxRows y define variables iniciales. */
            if ($MaxRows == "") {
                $MaxRows = 100000;
            }

            $mismenus = "0";

            $mandanteMenu = 0;


            /* genera un JSON para validar permisos de menú según condiciones específicas. */
            if ($_SESSION["mandante"] != '') {
                $mandanteMenu = $_SESSION["mandante"];
            }
            $mandanteMenu = '-1';

            $json = '{"rules" : [{"field" : "menu.version", "data" : "2","op":"eq"},{"field" : "perfil_submenu.perfil_id", "data" : "' . $Perfil_id . '","op":"eq"},{"field" : "perfil_submenu.mandante", "data" : "' . $mandanteMenu . '","op":"eq"}] ,"groupOp" : "AND"}';

            //Llamamos al metodo para obtener los menus

            /* Se obtienen y decodifican menús personalizados de la base de datos. */
            $menus = $PerfilSubmenu->getPerfilSubmenusCustom(" menu.*,submenu.*,perfil_submenu.* ", "menu.menu_id", "asc", $SkeepRows, $MaxRows, $json, true);

            $menus = json_decode($menus);

            $menus3 = [];
            $arrayf = [];

            /* Inicializa un arreglo vacío para submenús y otro para cadenas de menús. */
            $submenus = [];

            $menus_string = array();

            foreach ($menus->data as $key => $value) {


                /* crea arreglos para almacenar información de menú y submenú. */
                $m = [];
                $m["Id"] = $value->{"menu.menu_id"};
                $m["Name"] = $value->{"menu.descripcion"};

                $array = [];

                $array["Id"] = $value->{"submenu.submenu_id"};

                /* Se crean elementos de un array a partir de propiedades de un objeto. */
                $array["Name"] = $value->{"submenu.descripcion"};
                $array["Pagina"] = $value->{"submenu.pagina"};
                $array["IsGiven"] = true;
                $array["Action"] = "view";

                $mismenus = $mismenus . "," . $array["Id"];


                /* Condicional que agrega páginas y permisos a arrays si se cumplen ciertas condiciones. */
                if ($arrayf["Id"] != "" && $m["Id"] != $arrayf["Id"]) {
                    array_push($menus_string, $arrayf["Pagina"]);

                    $arrayf["Permissions"] = $submenus;
                    array_push($menus3, $arrayf);
                    // $submenus = [];
                }

                /* agrega elementos a arrays, organizando información de menús y submenús. */
                array_push($menus_string, $array["Pagina"]);

                $arrayf["Id"] = $value->{"menu.menu_id"};
                $arrayf["Name"] = $value->{"menu.descripcion"};
                $arrayf["Pagina"] = $value->{"menu.pagina"};

                array_push($submenus, $array);
            }

            /* Añade elementos a arrays y asigna permisos a un array asociado. */
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


            //Llamamos al metodo para obtener los menus

            /* obtiene menús, países y proveedores de casino para el usuario. */
            $menus_string = obtenerMenu();

            //Obtenemos los paises disponibles para el usuario
            $paisesparamenu = obtenerPaisesReport();

            /*
             * Obtenemos los proveedores de casino disponibles para el usuario
             */

            $Proveedor = new Proveedor();

            /* Código establece tipo de proveedor y ajusta parámetro según la sesión global. */
            $Proveedor->setTipo("CASINO");

            $paramMandante = '';
            if ($_SESSION['Global'] == "N") {
                $paramMandante = $_SESSION['mandante'];
            } else {
                /* Asigna el valor de la sesión "mandanteLista" a la variable $paramMandante. */

                $paramMandante = $_SESSION["mandanteLista"];

            }


            /* Se obtienen proveedores y se inicializa un arreglo con una opción "Todos". */
            $proveedores = $Proveedor->getProveedores($paramMandante, 'A');

            $finalProveedores = [];
            $array = [];
            $array["id"] = '0';
            $array["value"] = 'Todos';

            /* Agrega proveedores con sus ID y descripciones a un array final. */
            array_push($finalProveedores, $array);

            foreach ($proveedores as $key => $value) {

                $array = [];

                $array["id"] = $value->getProveedorId();
                $array["value"] = $value->getDescripcion();

                array_push($finalProveedores, $array);

            }

            /*
             * Obtenemos los proveedores de casino en vivo disponibles para el usuario
             */

            /* Crea un objeto Proveedor y establece su tipo basado en la sesión global. */
            $Proveedor = new Proveedor();
            $Proveedor->setTipo("LIVECASINO");

            $paramMandante = '';
            if ($_SESSION['Global'] == "N") {
                $paramMandante = $_SESSION['mandante'];
            } else {
                /* Asignación de valor a $paramMandante usando una variable de sesión. */

                $paramMandante = $_SESSION["mandanteLista"];

            }


            /* Se obtienen proveedores y se construye un array con su ID y descripción. */
            $proveedores = $Proveedor->getProveedores($paramMandante, 'A');

            foreach ($proveedores as $key => $value) {

                $array = [];

                $array["id"] = $value->getProveedorId();
                $array["value"] = $value->getDescripcion();

                array_push($finalProveedores, $array);

            }


            /* Se crea un objeto Proveedor y se asigna un tipo, verificando una sesión. */
            $Proveedor = new Proveedor();
            $Proveedor->setTipo("VIRTUAL");

            $paramMandante = '';
            if ($_SESSION['Global'] == "N") {
                $paramMandante = $_SESSION['mandante'];
            } else {
                /* asigna el valor de "mandanteLista" a $paramMandante desde la sesión. */

                $paramMandante = $_SESSION["mandanteLista"];

            }


            /* Obtiene proveedores y los almacena en un array con id y descripción. */
            $proveedores = $Proveedor->getProveedores($paramMandante, 'A');

            foreach ($proveedores as $key => $value) {

                $array = [];

                $array["id"] = $value->getProveedorId();
                $array["value"] = $value->getDescripcion();

                array_push($finalProveedores, $array);

            }

            /*
            * Obtenemos los Subproveedores de casino disponibles para el usuario
            */

            /* Inicializa una lista vacía y crea un objeto Subproveedor con tipo "CASINO". */
            $finalSubProveedores = [];

            $Subproveedor = new Subproveedor();
            $Subproveedor->setTipo("CASINO");

            $paramMandante = '';

            /* Asigna un valor a $paramMandante basado en la condición de la sesión 'Global'. */
            if ($_SESSION['Global'] == "N") {
                $paramMandante = $_SESSION['mandante'];
            } else {
                $paramMandante = $_SESSION["mandanteLista"];

            }

            /* Convierte a minúsculas una variable y obtiene subproveedores en un array. */
            $paramMandante = strtolower($paramMandante);


            $proveedores = $Subproveedor->getSubproveedores($paramMandante, 'A');

            $array = [];

            /* Se construye un array de subproveedores, incluyendo información de proveedores relacionados. */
            $array["id"] = '0';
            $array["value"] = 'Todos';
            array_push($finalSubProveedores, $array);

            foreach ($proveedores as $key => $value) {

                $array = [];

                $array["id"] = $value->getSubproveedorId();
                $array["value"] = $value->getDescripcion();

                foreach ($finalProveedores as $finalProveedore) {
                    if ($finalProveedore["id"] == $value->getProveedorId()) {
                        $array["value"] = $array["value"] . ' (' . $finalProveedore["value"] . ')';
                    }

                }

                array_push($finalSubProveedores, $array);

            }

            /*
             * Obtenemos los subproveedores de casino en vivo disponibles para el usuario
             */

            /* Se crea un objeto Subproveedor y se configura su tipo según la sesión. */
            $Subproveedor = new Subproveedor();
            $Subproveedor->setTipo("LIVECASINO");

            $paramMandante = '';
            if ($_SESSION['Global'] == "N") {
                $paramMandante = $_SESSION['mandante'];
            } else {
                /* Asignación de variable `$paramMandante` desde la sesión si no se cumple una condición. */

                $paramMandante = $_SESSION["mandanteLista"];

            }

            /* Convierte proveedores en un array con sus descripciones y relacionados. */
            $paramMandante = strtolower($paramMandante);

            $proveedores = $Subproveedor->getSubproveedores($paramMandante, 'A');

            foreach ($proveedores as $key => $value) {

                $array = [];

                $array["id"] = $value->getSubproveedorId();
                $array["value"] = $value->getDescripcion();

                foreach ($finalProveedores as $finalProveedore) {
                    if ($finalProveedore["id"] == $value->getProveedorId()) {
                        $array["value"] = $array["value"] . ' (' . $finalProveedore["value"] . ')';
                    }

                }

                array_push($finalSubProveedores, $array);

            }


            /* Se crea un objeto Subproveedor y se establece su tipo como "VIRTUAL". */
            $Subproveedor = new Subproveedor();
            $Subproveedor->setTipo("VIRTUAL");

            $paramMandante = '';
            if ($_SESSION['Global'] == "N") {
                $paramMandante = $_SESSION['mandante'];
            } else {
                /* Asignación de la variable $paramMandante desde la sesión si no se cumple una condición. */

                $paramMandante = $_SESSION["mandanteLista"];

            }

            /* Convierte nombres a minúsculas y crea un arreglo con subproveedores y descripciones. */
            $paramMandante = strtolower($paramMandante);

            $proveedores = $Subproveedor->getSubproveedores($paramMandante, 'A');

            foreach ($proveedores as $key => $value) {

                $array = [];

                $array["id"] = $value->getSubproveedorId();
                $array["value"] = $value->getDescripcion();

                foreach ($finalProveedores as $finalProveedore) {
                    if ($finalProveedore["id"] == $value->getProveedorId()) {
                        $array["value"] = $array["value"] . ' (' . $finalProveedore["value"] . ')';
                    }

                }

                array_push($finalSubProveedores, $array);

            }


            /*
             * Obtenemos los productos de casino disponibles para el usuario
             */


            /* Se crea un filtro con reglas para validar el tipo de proveedor "CASINO". */
            $ProductoMandante = new ProductoMandante();

            $rules = [];
            array_push($rules, array("field" => "proveedor.tipo", "data" => "CASINO ", "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");

            /* codifica un filtro y recupera productos según condiciones específicas. */
            $json = json_encode($filtro);


            // Si el usuario es de Global en configuración entonces los ID son diferentes

            if ($_SESSION["Global"] == "S") {
                $Producto = new Producto();

                $productos = $Producto->getProductosCustom(" producto.* ", "producto.producto_id", "asc", $SkeepRows, $MaxRows, $json, true);
                $productos = json_decode($productos);
            } else {
                /* Añade reglas de filtrado para obtener productos mandante y los convierte a JSON. */


                array_push($rules, array("field" => "producto_mandante.mandante", "data" => $_SESSION["mandante"], "op" => "eq"));
                array_push($rules, array("field" => "proveedor_mandante.estado", "data" => 'A', "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);


                $productos = $ProductoMandante->getProductosMandanteCustom(" producto_mandante.*,mandante.*,producto.* ", "producto_mandante.prodmandante_id", "asc", $SkeepRows, $MaxRows, $json, true);
                $productos = json_decode($productos);

            }


            /* crea un arreglo de productos basado en la sesión actual. */
            $finalProductos = [];

            foreach ($productos->data as $key => $value) {

                $array = [];
                if ($_SESSION["Global"] == "S") {
                    $array["id"] = $value->{"producto.producto_id"};
                    $array["value"] = $value->{"producto.descripcion"};

                } else {
                    $array["id"] = $value->{"producto_mandante.prodmandante_id"};
                    $array["value"] = $value->{"producto.descripcion"};

                }
                array_push($finalProductos, $array);

            }


            /* Establece reglas de acceso para usuarios concesionarios basadas en su ID. */
            $rules = [];

            // Si el usuario es un Agente 1 - CONCESIONARIO entonces obtenemos su red mediante usupadre_id
            if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
                $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

                array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION['usuario'], "op" => "eq"));
                array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            }

            // Si el usuario es un Agente 2 - CONCESIONARIO2 entonces obtenemos su red  mediante usupadre2_id

            /* Verifica la sesión y establece reglas según el perfil de usuario específico. */
            if ($_SESSION["win_perfil"] == "CONCESIONARIO2") {
                $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

                array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            }

            // Si el usuario es un Agente 1 - PUNTOVENTA entonces obtenemos su red  mediante usuhijo_id

            /* Verifica el perfil de usuario y establece reglas basadas en su ID y producto. */
            if ($_SESSION["win_perfil"] == "PUNTOVENTA") {
                $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

                array_push($rules, array("field" => "concesionario.usuhijo_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            }

            // Obtenemos toda la red en cuanto a Puntos de venta

            /* Agregar reglas de validación basadas en perfil y país del usuario a un array. */
            array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));

            // Si el usuario esta condicionado por País
            if ($_SESSION['PaisCond'] == "S") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
            }

            // Si el usuario esta condicionado por el mandante y no es de Global

            /* verifica la sesión y añade reglas relacionadas con "mandante". */
            if ($_SESSION['Global'] == "N") {
                array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
            } else {

                if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                    array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                }

            }

            // Inactivamos reportes para el país Colombia

            /* Se agrega una regla a un filtro y se codifica en formato JSON. */
            array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $jsonbetshop = json_encode($filtro);


            $PuntoVenta = new PuntoVenta();

            // Obtenemos los puntos de venta de la red de  el usuario

            /* obtiene datos de puntos de venta y los organiza en un array. */
            $mandantes = $PuntoVenta->getPuntoVentasCustom("usuario.estado_valida,usuario.fecha_crea,usuario.moneda,usuario.fecha_ult,punto_venta.*", "punto_venta.puntoventa_id", "asc", 0, 100000, $jsonbetshop, true);

            $mandantes = json_decode($mandantes);

            $finalBetShops = [];
            foreach ($mandantes->data as $key => $value) {
                $array = [];
                $array["id"] = $value->{"punto_venta.usuario_id"};
                $array["value"] = $value->{"punto_venta.descripcion"};
                array_push($finalBetShops, $array);
            }

            // Obtenemos si el usuario esta condicionado el reporte a un país en especifico

            /* Verifica el país del usuario según su perfil y condiciones de sesión. */
            $ReportCountry = $Usuario->paisId;
            if ($_SESSION["win_perfil2"] == "ADMIN" || $_SESSION["win_perfil2"] == "ADMIN2" || $_SESSION["win_perfil2"] == "OPERADOR" || $_SESSION["win_perfil2"] == "SA") {
                if ($_SESSION['PaisCond'] != "S") {
                    $ReportCountry = '0';
                }
            }

            // Obtenemos los saldos de recarga y juego de el usuario

            /* verifica perfiles y obtiene saldos de recarga y juego de PuntoVenta. */
            $SaldoRecargas = 0;
            $SaldoJuego = 0;
            if ($_SESSION["win_perfil2"] == "CONCESIONARIO" || $_SESSION["win_perfil2"] == "CONCESIONARIO2" || $_SESSION["win_perfil2"] == "PUNTOVENTA") {
                $PuntoVenta = new PuntoVenta("", $_SESSION["usuario"]);

                $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                $SaldoJuego = $PuntoVenta->getCreditosBase();
            }


            /* Verifica el perfil de usuario y obtiene saldo de recargas y créditos. */
            if ($_SESSION["win_perfil2"] == "CAJERO") {
                $PuntoVenta = new PuntoVenta("", $Usuario->puntoventaId);

                $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                $SaldoJuego = $PuntoVenta->getCreditosBase();
            }


            // Verificamos si el usuario ya realizo cierre de caja

            /* Verifica si la fecha de cierre coincide con la fecha actual. */
            $fechaCierre = date("Y-m-d", strtotime($Usuario->fechaCierrecaja));
            $hizoCierreCaja = false;

            if ($fechaCierre == date("Y-m-d")) {
                $hizoCierreCaja = true;
            }

            // Verificamos si el usuario ya realizo "Inicio de día"

            /* Inicializa una variable y crea una nueva instancia de ConfigurationEnvironment. */
            $beginDay = false;

            $ConfigurationEnvironment = new ConfigurationEnvironment();

            if ($ConfigurationEnvironment->isDevelopment() || true) {

                if ($_SESSION["win_perfil2"] == "CAJERO") {

                    /* Se define un clasificador y se añaden reglas de filtrado para ingresos. */
                    $TipoDineroInicial = new Clasificador("", "ACCAMOUNTDAY");


                    $rules = [];
                    array_push($rules, array("field" => "ingreso.usucajero_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                    array_push($rules, array("field" => "ingreso.fecha_crea", "data" => date("Y-m-d 00:00:00"), "op" => "ge"));

                    /* Agrega una regla de filtro y la convierte en formato JSON para uso posterior. */
                    array_push($rules, array("field" => "ingreso.tipo_id", "data" => $TipoDineroInicial->getClasificadorId(), "op" => "eq"));


                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json = json_encode($filtro);

                    $Ingreso = new Ingreso();


                    /* Se obtienen ingresos personalizados, se decodifica JSON y se itera sobre los datos. */
                    $data = $Ingreso->getIngresosCustom("  ingreso.* ", "ingreso.ingreso_id", "asc", $SkeepRows, $MaxRows, $json, true);

                    $data = json_decode($data);

                    foreach ($data->data as $key => $value) {
                        $beginDay = true;
                    }


                } elseif ($_SESSION["win_perfil2"] == "PUNTOVENTA") {

                    if ($Usuario->fechaCierrecaja == "") {


                        /* Código para definir reglas de filtrado en un sistema financiero basado en usuario y fecha. */
                        $TipoDineroInicial = new Clasificador("", "ACCAMOUNTDAY");


                        $rules = [];
                        array_push($rules, array("field" => "ingreso.usucajero_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                        array_push($rules, array("field" => "ingreso.fecha_crea", "data" => date("Y-m-d 00:00:00"), "op" => "ge"));

                        /* Se agregan reglas de filtro y se codifican en formato JSON para procesamiento. */
                        array_push($rules, array("field" => "ingreso.tipo_id", "data" => $TipoDineroInicial->getClasificadorId(), "op" => "eq"));


                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json = json_encode($filtro);

                        $Ingreso = new Ingreso();


                        /* obtiene y decodifica datos de ingresos personalizados en formato JSON. */
                        $data = $Ingreso->getIngresosCustom("  ingreso.* ", "ingreso.ingreso_id", "asc", $SkeepRows, $MaxRows, $json, true);

                        $data = json_decode($data);

                        foreach ($data->data as $key => $value) {
                            $beginDay = true;
                        }

                    } else {
                        /* Asigna verdadero a la variable $beginDay si no se cumple una condición anterior. */

                        $beginDay = true;
                    }


                } else {
                    /* Establece la variable $beginDay como verdadera si no se cumple la condición anterior. */

                    $beginDay = true;
                }
            }


            // Si el usuario tiene configurado Global, obtenemos los partners de el sistema

            /* Inicializa un arreglo vacío llamado $finalMandante en PHP. */
            $finalMandante = [];
            if ($_SESSION["GlobalConfig"] == "S") {

                // Agregamos como primer elemento el partner Global que es virtualsoft

                /* crea un array asociativo con información y lista de países. */
                $array = [];
                $array["id"] = "-1";
                $array["value"] = "Virtualsoft";

                $array["Countries"] = array(
                    array(
                        "id" => "",
                        "value" => "Todos"
                    )
                );


                /* Se define un filtro con reglas para validar condiciones de países mandantes. */
                $rules = [];

                array_push($rules, array("field" => "pais_mandante.mandante", "data" => $array["id"], "op" => "eq"));
                array_push($rules, array("field" => "pais_mandante.estado", "data" => "A", "op" => "eq"));


                $filtro = array("rules" => $rules, "groupOp" => "AND");

                /* codifica un filtro JSON y obtiene paises mandantes desde una base de datos. */
                $json = json_encode($filtro);

                $PaisMandante = new PaisMandante();

                $paises = $PaisMandante->getPaisMandantesCustom(" pais_mandante.*,pais.* ", "pais_mandante.paismandante_id", "asc", 0, 1000, $json, true);

                $paises = json_decode($paises);


                /* recorre países y almacena ID y nombre en un arreglo. */
                foreach ($paises->data as $key2 => $value2) {
                    $array2 = [];
                    $array2["id"] = $value2->{"pais_mandante.pais_id"};
                    $array2["value"] = $value2->{"pais.pais_nom"};
                    array_push($array["Countries"], $array2);

                }

                /* Añade datos a un array y configura reglas basadas en la sesión actual. */
                array_push($finalMandante, $array);

                $Mandante = new Mandante();

                $rules = [];

                if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                    array_push($rules, array("field" => "mandante.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                }


                /* Se crea un filtro JSON y se obtienen mandantes según ese filtro. */
                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $mandantes = $Mandante->getMandantes("mandante.mandante", "asc", 0, 100, $json, true);
                $mandantes = json_decode($mandantes);


                foreach ($mandantes->data as $key => $value) {


                    /* Crea un array con datos del mandante y un país predeterminado "Todos". */
                    $array = [];
                    $array["id"] = $value->{"mandante.mandante"};
                    $array["value"] = $value->{"mandante.descripcion"};

                    $array["Countries"] = array(
                        array(
                            "id" => "",
                            "value" => "Todos"
                        )
                    );


                    /* Se crean reglas para filtrar datos de "pais_mandante" usando operaciones lógicas. */
                    $rules = [];

                    array_push($rules, array("field" => "pais_mandante.mandante", "data" => $array["id"], "op" => "eq"));
                    array_push($rules, array("field" => "pais_mandante.estado", "data" => "A", "op" => "eq"));


                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    /* Codifica un filtro JSON y obtiene datos de países mandantes personalizados. */
                    $json = json_encode($filtro);

                    $PaisMandante = new PaisMandante();

                    $paises = $PaisMandante->getPaisMandantesCustom(" pais_mandante.*,pais.* ", "pais_mandante.paismandante_id", "asc", 0, 1000, $json, true);
                    $paises = json_decode($paises);


                    /* Itera sobre países y almacena ID y nombre en un arreglo. */
                    foreach ($paises->data as $key2 => $value2) {
                        $array2 = [];
                        $array2["id"] = $value2->{"pais_mandante.pais_id"};
                        $array2["value"] = $value2->{"pais.pais_nom"};
                        array_push($array["Countries"], $array2);

                    }

                    /* Añade un elemento al final del arreglo "$finalMandante" utilizando "$array". */
                    array_push($finalMandante, $array);

                }
            } else {


                /* Se crea un array con datos de sesión y un país predeterminado. */
                $array = [];

                $array["id"] = $_SESSION["mandante"];
                $array["value"] = $_SESSION["mandante"];

                $array["Countries"] = array(
                    array(
                        "id" => "",
                        "value" => "Todos"
                    )
                );


                /* Se crean reglas de filtrado para validar datos de "pais_mandante". */
                $rules = [];

                array_push($rules, array("field" => "pais_mandante.mandante", "data" => $array["id"], "op" => "eq"));
                array_push($rules, array("field" => "pais_mandante.estado", "data" => "A", "op" => "eq"));


                $filtro = array("rules" => $rules, "groupOp" => "AND");

                /* convierte un filtro a JSON y obtiene datos de países. */
                $json = json_encode($filtro);

                $PaisMandante = new PaisMandante();

                $paises = $PaisMandante->getPaisMandantesCustom(" pais_mandante.*,pais.* ", "pais_mandante.paismandante_id", "asc", 0, 1000, $json, true);
                $paises = json_decode($paises);


                /* Itera sobre países, extrayendo ID y nombre para construir un nuevo array. */
                foreach ($paises->data as $key2 => $value2) {
                    $array2 = [];
                    $array2["id"] = $value2->{"pais_mandante.pais_id"};
                    $array2["value"] = $value2->{"pais.pais_nom"};
                    array_push($array["Countries"], $array2);

                }


                /* Añade el contenido de `$array` al final de `$finalMandante`. */
                array_push($finalMandante, $array);

            }


            /* asigna el valor de la sesión "mandante" a la variable $PartnerSelected. */
            $PartnerSelected = $_SESSION["mandante"];

            /* if(in_array($Usuario->login,array('ADMINMIRAVALLE','OPERMIRAVALLE','FINMIRAVALLE')) ){

                 $finalMandante = [];

                 $array = [];

                 $array["id"] = $_SESSION["mandante"];
                 $array["value"] = $_SESSION["mandante"];

                 $array["Countries"] = array(
                     array(
                         "id" => "",
                         "value" => "Todos"
                     ),
                     array(
                         "id" => "173",
                         "value" => "Mexico"
                     )
                 );

                 array_push($finalMandante, $array);

                 $PartnerSelected=3;
             }*/


            /* inicializa variables para almacenar imágenes de logotipos y un estilo. */
            $logoImg = '';
            $logoSideNavImg = '';
            $skinItainment = '';

            if ($_SESSION["Global"] == 'N') {

                /* asigna configuraciones basadas en la sesión del mandante. */
                $Mandante = new Mandante(strtolower($_SESSION["mandante"]));

                $SubproveedorItn = new Subproveedor("", "ITN");
                $SubproveedorMandantePais = new SubproveedorMandantePais('', $SubproveedorItn->subproveedorId, $Usuario->mandante, $Usuario->paisId);
                $Credentials = json_decode($SubproveedorMandantePais->getCredentials());
                $walletCode = $Credentials->WALLET_CODE;
                $skinItainment = $Credentials->SKIN_ID2;

                /* Asignación de valor a $skinItainment según condiciones de mandante y país del usuario. */
                if ($Mandante->mandante == '0' && $Usuario->paisId == '2') {
                    $skinItainment = 'doradobet3';
                }

                /* Asignación de variables con datos de un objeto Mandante relacionado a una billetera y logo. */
                $walletcodeSportbookITN = $walletCode;
                $logoImg = $Mandante->logo;
                $logoSideNavImg = $Mandante->logo;

                /*switch ($_SESSION["mandante"]) {
                    case '0':
                        $logoImg = 'https://images.virtualsoft.tech/site/doradobet/doradobet-borde-azul.png';
                        $logoSideNavImg = 'https://images.virtualsoft.tech/site/doradobet/doradobet-borde-azul.png';

                        break;
                    case '1':
                        $logoImg = 'https://images.virtualsoft.tech/site/ibetsupreme/logo.png';
                        $logoSideNavImg = 'https://images.virtualsoft.tech/site/ibetsupreme/logo.png';

                        break;
                    case '2':
                        $logoImg = 'https://images.virtualsoft.tech/site/justbet/logo.png';
                        $logoSideNavImg = 'https://images.virtualsoft.tech/site/justbet/logo.png';

                        break;
                    case '3':
                        $logoImg = 'https://images.virtualsoft.tech/site/miravalle/logo3.png';
                        $logoSideNavImg = 'https://images.virtualsoft.tech/site/miravalle/logo3.png';

                        break;
                    case '4':
                        $logoImg = 'https://images.virtualsoft.tech/site/casinogranpalacio/logo.png';
                        $logoSideNavImg = 'https://images.virtualsoft.tech/site/casinogranpalacio/logo.png';

                        break;
                    case '5':
                        $logoImg = 'https://images.virtualsoft.tech/site/casinointercontinental/logo2.png';
                        $logoSideNavImg = 'https://images.virtualsoft.tech/site/casinointercontinental/logo2.png';

                        break;

                }*/


                /* Genera un enlace de afiliado con el usuario cifrado en la URL. */
                $linkAffiliate = $Mandante->baseUrl . 'registro/aff/' . $ConfigurationEnvironment->encrypt_decrypt2('encrypt', $_SESSION["usuario"]);

            } else {
                /* asigna imágenes de logo a variables si no se cumple una condición. */

                $logoImg = 'https://admin.virtualsoft.tech/sources/assets/plugins/images/logo-virtualsoft.svg';
                $logoSideNavImg = 'https://admin.virtualsoft.tech/sources/assets/plugins/images/logo-virtualsoft.svg';

            }


            /* Se crea un array y se añaden imágenes y PDFs de un cliente. */
            $MediaLibrary = array();

            array_push($MediaLibrary, $logoImg);
            array_push($MediaLibrary, $Mandante->logoPdf);


            // Respondemos correctamente con todos los datos de el usuario
            $response["Data"] = array(
                "MediaLibrary" => $MediaLibrary,
                "logo" => $logoImg,
                "logoSideNav" => $logoSideNavImg,
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
                "SubProvidersCasino" => $finalSubProveedores,
                "CloseCashBox" => $hizoCierreCaja,
                "BeginDay" => $beginDay,

                "skinIdITN" => $skinItainment,

                "Partners" => $finalMandante,
                "PartnerSelected" => $PartnerSelected,
                "GamesCasino" => $finalProductos,
                "PermissionList" => $menus_string,
                "TokenSportsbook" => $Usuario->tokenItainment,
                "linkAffiliate" => $linkAffiliate
            );
        }

    } catch (Exception $e) {
        /* Manejo de excepciones para responder con error en una solicitud. */


        // Respondemos ERROR en la solicitud por un error general desconocido
        $response["HasError"] = true;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = "General Error ('.$e->getCode().')";
        $response["ModelErrors"] = [];

        $response["Data"] = array(
            "AuthenticationStatus" => 0,

            "PermissionList" => array(),
        );

    }

}

?>