<?php

use Backend\dto\Pais;
use Backend\dto\Ingreso;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Proveedor;
use Backend\dto\PuntoVenta;
use Backend\dto\UsuarioLog;
use Backend\dto\Clasificador;
use Backend\dto\PaisMandante;
use Backend\dto\Subproveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\PerfilSubmenu;
use Backend\dto\MandanteDetalle;
use Backend\dto\UsuarioMandante;
use Backend\dto\DocumentoUsuario;
use Backend\dto\ProductoMandante;
use Backend\dto\UsuarioConfiguracion;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;

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
 *  Por error general desconocido
 * $response["HasError"] = true;
 * $response["AlertType"] = "danger";
 * $response["AlertMessage"] = "General Error ('.$e->getCode().')";
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
 * @throws Exception No existe Token. Si el pais no se encuentra activo en el partner
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Verifica condiciones de sesión y ajusta valores según lógica de autorización. */
if ($_SESSION["mandante"] != '2' && $_SESSION["mandanteLista"] != '' && !(in_array($_SESSION["mandante"], explode(',', $_SESSION["mandanteLista"])))) {
    if ($_SESSION["Global"] == 'N') {
        $_SESSION["Global"] = 'S';
        $_SESSION["mandante"] = '-1';
    }
}
//Verifica si ya hubo un logueo

/* verifica si el usuario está logueado y responde con un error. */
if (!$_SESSION['logueado']) {
    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = "f";
    $response["ModelErrors"] = [];

    $response["Data"] = array(
        "AuthenticationStatus" => 0,

        "PermissionList" => array(),
        "Version" => "Virtualsoft - v8.1.0"
    );

} else {


    try {

        // Obtenemos UsuarioMandante de el usuario actual
        $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);
        $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

        // Obtenemos el pais de el usuario actual
        $Pais = new Pais($Usuario->paisId);

        /* verifica si el usuario es un punto de venta*/
        if ($_SESSION["win_perfil"] == "PUNTOVENTA") {


            try {
                /* verifica si se ha expirado la contraseña */
                $clasificador = new Clasificador("", "BODAYSEXPIREPASSWORD");
                $Mandantedetalle = new MandanteDetalle('', $Usuario->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, 'A');
                $ExpirationDays = $Mandantedetalle->valor;
                if ($ExpirationDays != '' && $ExpirationDays != '0') {

                    $rules = [];

                    array_push($rules, ['field' => 'usuario_log.usuario_id', 'data' => $Usuario->usuarioId, 'op' => 'eq']);
                    array_push($rules, ['field' => 'usuario_log.tipo', 'data' => 'CAMBIOCLAVEEMAIL', 'op' => 'eq']);
                    // array_push($rules, ['field' => 'usuario_log.estado', 'data' => 'A', 'op' => 'eq']);

                    $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

                    $UsuarioLog = new UsuarioLog();
                    $query = $UsuarioLog->getUsuarioLogsCustom('usuario_log.usuario_id,usuario_log.fecha_crea', 'usuario_log.usuariolog_id', 'DESC', 0, 1, $filters, true);
                    $query = json_decode($query, true);

                    if (oldCount($query['data'][0]) > 0) {

                        $fechaLogPassword = $query['data'][0]['usuario_log.fecha_crea'];
                        $fecha_Vencimiento = date("Y-m-d 00:00:00", strtotime($fechaLogPassword . "+ $ExpirationDays days"));
                        $fecha_actual = date("Y-m-d H:i:s");

                        if ($fecha_Vencimiento < $fecha_actual) {
                            $ForceChangeKey = true;
                        } else {
                            $ForceChangeKey = false;
                        }

                    } else {
                        $ForceChangeKey = true;
                    }
                }

            } catch (Exception $e) {
                $ForceChangeKey = false;
            }

        } else {


            try {
                /* verifica si se ha expirado la contraseña */
                $clasificador = new Clasificador("", "BODAYSEXPIREPASSWORD");
                $Mandantedetalle = new MandanteDetalle('', $Usuario->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, 'A');
                $ExpirationDays = $Mandantedetalle->valor;
                if ($ExpirationDays != '' && $ExpirationDays != '0') {


                    $rules = [];

                    array_push($rules, ['field' => 'usuario_log.usuario_id', 'data' => $Usuario->usuarioId, 'op' => 'eq']);
                    array_push($rules, ['field' => 'usuario_log.tipo', 'data' => 'CAMBIOCLAVEEMAIL', 'op' => 'eq']);
                    // array_push($rules, ['field' => 'usuario_log.estado', 'data' => 'A', 'op' => 'eq']);

                    $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

                    $UsuarioLog = new UsuarioLog();
                    $query = $UsuarioLog->getUsuarioLogsCustom('usuario_log.usuario_id,usuario_log.fecha_crea', 'usuario_log.usuariolog_id', 'DESC', 0, 1, $filters, true);
                    $query = json_decode($query, true);

                    if (count($query['data'][0]) > 0) {

                        $fechaLogPassword = $query['data'][0]['usuario_log.fecha_crea'];
                        $fecha_Vencimiento = date("Y-m-d 00:00:00", strtotime($fechaLogPassword . "+ $ExpirationDays days"));
                        $fecha_actual = date("Y-m-d H:i:s");

                        if ($fecha_Vencimiento < $fecha_actual) {
                            $ForceChangeKey = true;
                        } else {
                            $ForceChangeKey = false;
                        }

                    } else {
                        $ForceChangeKey = true;
                    }
                }

            } catch (Exception $e) {
                $ForceChangeKey = false;
            }
            if ($_SESSION["win_perfil"] != "PUNTOVENTA" &&
                $_SESSION["win_perfil"] != "CONCESIONARIO" &&
                $_SESSION["win_perfil"] != "CONCESIONARIO2" &&
                $_SESSION["win_perfil"] != "CONCESIONARIO3" &&
                $_SESSION["win_perfil"] != "AFILIADOR" &&
                $_SESSION["win_perfil"] != "CAJERO"
            ) {
                $ForceQrRead = ($Usuario->tokenGoogle == 'I') ? true : false;

            }
        }


        if ($_SESSION['mandante'] == 8 && $_SESSION['GlobalConfig'] == "N" && date('Y-m-d H:i:s') > '2022-11-28 20:00:00' && $_SESSION["win_perfil"] != "PUNTOVENTA") {
            $ForceQrRead = $Usuario->tokenGoogle == "I";

        }
        if ((
            $_SESSION['mandante'] == 6 ||
            $_SESSION['mandante'] == 20 ||
            $_SESSION['mandante'] == 12 ||
            $_SESSION['mandante'] == 15 ||
            $_SESSION['mandante'] == 18 ||
            $_SESSION['mandante'] == 22 ||
            $_SESSION['mandante'] == 16 ||
            $_SESSION['mandante'] == 17 ||
            ($_SESSION['mandante'] == 17 && $_SESSION['pais_id'] == 60) ||
            ($_SESSION['mandante'] == 17 && $_SESSION['pais_id'] == 94) ||
            ($_SESSION['mandante'] == 21)

        )) {
            $ForceQrRead = $Usuario->tokenGoogle == "I";

        }
        if ($_SESSION['mandante'] == 8 && $_SESSION['usuario'] == 77305 && false) {

            $ForceChangeId = true;

            if (!$ForceChangeId) {
                $ForceQrRead = $Usuario->tokenGoogle == "I";

            }
            $ForceQrRead = true;
        }

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
        /* Guardamos detalles de la respuesta */
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

            $menus_string = obtenerMenu();
            $paisesparamenu = obtenerPaisesReport();


            $Proveedor = new Proveedor();
            $Proveedor->setTipo("CASINO");

            $paramMandante = '';
            if ($_SESSION['Global'] == "N") {
                $paramMandante = $_SESSION['mandante'];
            } else {
                $paramMandante = $_SESSION["mandanteLista"];

            }

            $proveedores = $Proveedor->getProveedores($paramMandante, 'A');

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


            $Proveedor = new Proveedor();
            $Proveedor->setTipo("LIVECASINO");

            $paramMandante = '';
            if ($_SESSION['Global'] == "N") {
                $paramMandante = $_SESSION['mandante'];
            } else {
                $paramMandante = $_SESSION["mandanteLista"];


            }

            $proveedores = $Proveedor->getProveedores($paramMandante, 'A');

            foreach ($proveedores as $key => $value) {

                $array = [];

                $array["id"] = $value->getProveedorId();
                $array["value"] = $value->getDescripcion();

                array_push($finalProveedores, $array);

            }

            $Proveedor = new Proveedor();
            $Proveedor->setTipo("VIRTUAL");

            $paramMandante = '';
            if ($_SESSION['Global'] == "N") {
                $paramMandante = $_SESSION['mandante'];
            } else {
                $paramMandante = $_SESSION["mandanteLista"];

            }

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

            $Subproveedor = new Subproveedor();
            $Subproveedor->setTipo("CASINO");

            $paramMandante = '';
            if ($_SESSION['Global'] == "N") {
                $paramMandante = $_SESSION['mandante'];
            } else {
                $paramMandante = $_SESSION["mandanteLista"];

            }


            $proveedores = $Subproveedor->getSubproveedores($paramMandante, 'A');

            $finalSubProveedores = [];
            $array = [];
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
            $Subproveedor = new Subproveedor();
            $Subproveedor->setTipo("LIVECASINO");

            $paramMandante = '';
            if ($_SESSION['Global'] == "N") {
                $paramMandante = $_SESSION['mandante'];
            } else {
                $paramMandante = $_SESSION["mandanteLista"];

            }

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

            $Subproveedor = new Subproveedor();
            $Subproveedor->setTipo("VIRTUAL");

            $paramMandante = '';
            if ($_SESSION['Global'] == "N") {
                $paramMandante = $_SESSION['mandante'];
            } else {
                $paramMandante = $_SESSION["mandanteLista"];

            }

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


            $ProductoMandante = new ProductoMandante();

            $rules = [];
            array_push($rules, array("field" => "proveedor.tipo", "data" => "CASINO ", "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);

            /*if ($_SESSION["Global"] == "S") {
                $Producto = new Producto();

                $productos = $Producto->getProductosCustom(" producto.* ", "producto.producto_id", "asc", $SkeepRows, $MaxRows, $json, true);
                $productos = json_decode($productos);
            } else {
                array_push($rules, array("field" => "producto_mandante.mandante", "data" => $_SESSION["mandante"], "op" => "eq"));
                array_push($rules, array("field" => "proveedor_mandante.estado", "data" => 'A', "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $productos = $ProductoMandante->getProductosMandanteCustom(" producto_mandante.*,mandante.*,producto.* ", "producto_mandante.prodmandante_id", "asc", $SkeepRows, $MaxRows, $json, true);
                $productos = json_decode($productos);

            }*/


            $finalProductos = [];

            /*foreach ($productos->data as $key => $value) {

                $array = [];
                if ($_SESSION["Global"] == "S") {
                    $array["id"] = $value->{"producto.producto_id"};
                    $array["value"] = $value->{"producto.descripcion"};

                } else {
                    $array["id"] = $value->{"producto_mandante.prodmandante_id"};
                    $array["value"] = $value->{"producto.descripcion"};

                }
                array_push($finalProductos, $array);

            }*/


            $rules = [];

            if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
                $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

                array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION['usuario'], "op" => "eq"));
                array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            }

            if ($_SESSION["win_perfil"] == "CONCESIONARIO2") {
                $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

                array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            }

            if ($_SESSION["win_perfil"] == "CONCESIONARIO3") {
                $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

                array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            }


            if ($_SESSION["win_perfil"] == "PUNTOVENTA") {
                $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

                array_push($rules, array("field" => "concesionario.usuhijo_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            }
            array_push($rules, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));
            array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));


            // Si el usuario esta condicionado por País
            if ($_SESSION['PaisCond'] == "S") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
            } else {
                if ($_SESSION["PaisCondS"] != '') {
                    array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['PaisCondS'], "op" => "eq"));
                }
            }
            // Si el usuario esta condicionado por el mandante y no es de Global
            if ($_SESSION['Global'] == "N") {
                array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
            } else {

                if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                    array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                }

            }

            // Inactivamos reportes para el país Colombia
            //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $jsonbetshop = json_encode($filtro);


            $PuntoVenta = new PuntoVenta();


            //$mandantes = $PuntoVenta->getPuntoVentasCustom("usuario.estado_valida,usuario.fecha_crea,usuario.moneda,usuario.fecha_ult,punto_venta.*", "punto_venta.puntoventa_id", "asc", 0, 100000, $jsonbetshop, true);

            //$mandantes = json_decode($mandantes);

            $finalBetShops = [];

            /* foreach ($mandantes->data as $key => $value) {

                 $array = [];

                 $array["id"] = $value->{"punto_venta.usuario_id"};
                 $array["value"] = $value->{"punto_venta.descripcion"};

                 array_push($finalBetShops, $array);

             }*/


            $finalBetShops = [];

            /*foreach ($mandantes->data as $key => $value) {

                $array = [];

                $array["id"] = $value->{"punto_venta.usuario_id"};
                $array["value"] = $value->{"punto_venta.descripcion"};

                array_push($finalBetShops, $array);

            }*/


            $ReportCountry = $Usuario->paisId;

            if ($_SESSION["win_perfil2"] == "ADMIN" || $_SESSION["win_perfil2"] == "ADMIN2" || $_SESSION["win_perfil2"] == "OPERADOR" || $_SESSION["win_perfil2"] == "SA" || $_SESSION["win_perfil2"] == "CUSTOM"
                || $_SESSION["win_perfil2"] == "COORDOPER" || $_SESSION["win_perfil2"] == "ANALISTAOPER" || $_SESSION["win_perfil2"] == "COORDSOPORTE"
                || $_SESSION["win_perfil2"] == "OPERSOPORTE" || $_SESSION["win_perfil2"] == "COORDCONTRIESGO" || $_SESSION["win_perfil2"] == "ANALISTACONTRIE"
                || $_SESSION["win_perfil2"] == "COMERCIAL" || $_SESSION["win_perfil2"] == "ACCOUNT" || $_SESSION["win_perfil2"] == "TIADMIN"
                || $_SESSION["win_perfil2"] == "TIINCIDENTES" || $_SESSION["win_perfil2"] == "TICONFIGURACION" || $_SESSION["win_perfil2"] == "TIGESTION"
                || $_SESSION["win_perfil2"] == "TIBI" || $_SESSION["win_perfil2"] == "ADMINVIRTUAL" || $_SESSION["win_perfil2"] == "IMPLEMENT" || $_SESSION["win_perfil2"] == "QUOTA"
                || $_SESSION["win_perfil2"] == "FINANCIEROTERC"

            ) {
                if ($_SESSION['PaisCond'] != "S") {
                    $ReportCountry = '0';
                }

            }
            if ($_SESSION['PaisCondS'] != "") {
                $ReportCountry = $_SESSION['PaisCondS'];
            }

            $SaldoRecargas = 0;
            $SaldoJuego = 0;
            if ($_SESSION["win_perfil2"] == "CONCESIONARIO" || $_SESSION["win_perfil2"] == "CONCESIONARIO2" || $_SESSION["win_perfil2"] == "CONCESIONARIO3" || $_SESSION["win_perfil2"] == "PUNTOVENTA") {
                $PuntoVenta = new PuntoVenta("", $_SESSION["usuario"]);

                $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                $SaldoJuego = $PuntoVenta->getCreditosBase();

                if ($UsuarioMandante->getUsuarioMandante() == '160' || $UsuarioMandante->getUsuarioMandante() == '77305') {
                    $DocumentoUsuario = new DocumentoUsuario();
                    $DocumentoUsuario->usuarioId = $UsuarioMandante->getUsuarioMandante();
                    $plataforma = 0;
                    $Documentos = $DocumentoUsuario->getDocumentosNoProcesadosPRO($plataforma, $_SESSION["win_perfil2"]);

                    if (oldCount($Documentos) > 0) {
                        $SignatureDocument = false;

                    } else {
                        $SignatureDocument = true;


                    }
                }

            }


            if ($_SESSION["win_perfil2"] == "CAJERO") {
                $PuntoVenta = new PuntoVenta("", $Usuario->puntoventaId);

                $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                $SaldoJuego = $PuntoVenta->getCreditosBase();
            }

            $finalMandante = [];

            if ($_SESSION["GlobalConfig"] == "S") {

                $array = [];
                $array["id"] = "-1";
                $array["value"] = "Virtualsoft";


                $array["Countries"] = array(
                    array(
                        "id" => "",
                        "value" => "Todos"
                    )
                );

                $rules = [];

                array_push($rules, array("field" => "pais_mandante.mandante", "data" => $array["id"], "op" => "eq"));
                array_push($rules, array("field" => "pais_mandante.estado", "data" => "A", "op" => "eq"));


                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $PaisMandante = new PaisMandante();

                $paises = $PaisMandante->getPaisMandantesCustom(" pais_mandante.*,pais.* ", "pais_mandante.paismandante_id", "asc", 0, 1000, $json, true);
                $paises = json_decode($paises);


                foreach ($paises->data as $key2 => $value2) {
                    $array2 = [];
                    $array2["id"] = $value2->{"pais_mandante.pais_id"};
                    $array2["value"] = $value2->{"pais.pais_nom"};
                    $array2['iso'] = strtolower($value2->{'pais.iso'});
                    array_push($array["Countries"], $array2);

                }
                array_push($finalMandante, $array);


                $Mandante = new Mandante();

                $rules = [];


                if ($_SESSION["win_perfil"] != 'SA') {

                    if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                        array_push($rules, array("field" => "mandante.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                    }
                }
                // array_push($rules, array("field" => "proveedor.tipo", "data" => "CASINO ", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $mandantes = $Mandante->getMandantes("mandante.mandante", "asc", 0, 100, $json, true);
                $mandantes = json_decode($mandantes);


                foreach ($mandantes->data as $key => $value) {
                    $array = [];
                    $array["id"] = $value->{"mandante.mandante"};
                    $array["value"] = $value->{"mandante.descripcion"};
                    $array["Countries"] = array(
                        array(
                            "id" => "",
                            "value" => "Todos"
                        )
                    );
                    $array["name"] = $value->{"mandante.descripcion"};
                    $array["url"] = $value->{"mandante.base_url"};
                    $array["image"] = $value->{"mandante.logo"};
                    $array["favicon"] = $value->{"mandante.favicon"};


                    $rules = [];

                    array_push($rules, array("field" => "pais_mandante.mandante", "data" => $array["id"], "op" => "eq"));
                    array_push($rules, array("field" => "pais_mandante.estado", "data" => "A", "op" => "eq"));


                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json = json_encode($filtro);

                    $PaisMandante = new PaisMandante();

                    $paises = $PaisMandante->getPaisMandantesCustom(" pais_mandante.*,pais.* ", "pais_mandante.paismandante_id", "asc", 0, 1000, $json, true);
                    $paises = json_decode($paises);


                    foreach ($paises->data as $key2 => $value2) {
                        $array2 = [];
                        $array2["id"] = $value2->{"pais_mandante.pais_id"};
                        $array2["value"] = $value2->{"pais.pais_nom"};
                        $array2['iso'] = strtolower($value2->{'pais.iso'});
                        array_push($array["Countries"], $array2);

                    }

                    array_push($finalMandante, $array);

                }
            } else {
                $array = [];

                $array["id"] = $_SESSION["mandante"];
                $array["value"] = $_SESSION["mandante"];

                $array["Countries"] = array(
                    array(
                        "id" => "",
                        "value" => "Todos"
                    )
                );

                $rules = [];

                array_push($rules, array("field" => "pais_mandante.mandante", "data" => $array["id"], "op" => "eq"));
                array_push($rules, array("field" => "pais_mandante.estado", "data" => "A", "op" => "eq"));


                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $PaisMandante = new PaisMandante();

                $paises = $PaisMandante->getPaisMandantesCustom(" pais_mandante.*,pais.* ", "pais_mandante.paismandante_id", "asc", 0, 1000, $json, true);
                $paises = json_decode($paises);


                foreach ($paises->data as $key2 => $value2) {
                    $array2 = [];
                    $array2["id"] = $value2->{"pais_mandante.pais_id"};
                    $array2["value"] = $value2->{"pais.pais_nom"};
                    $array2['iso'] = strtolower($value2->{'pais.iso'});
                    array_push($array["Countries"], $array2);

                }
            }

            $SaldoJuego2 = "$ " . number_format($SaldoJuego, 2, ',', '.');
            $SaldoRecargas2 = "$ " . number_format($SaldoRecargas, 2, ',', '.');
            if ($Usuario->moneda == "PEN") {
                $SaldoJuego2 = "S/. " . number_format($SaldoJuego, 2, ',', '.');
                $SaldoRecargas2 = "S/. " . number_format($SaldoRecargas, 2, ',', '.');
            }
            if ($Usuario->moneda == "CRC") {
                $SaldoJuego2 = "CRC " . number_format($SaldoJuego, 2, ',', '.');
                $SaldoRecargas2 = "CRC " . number_format($SaldoRecargas, 2, ',', '.');
            }


            $SaldoComision = '$ 0';
            $SaldoComision2 = '$ 0';
            $SaldoTotal2 = '$ 0';

            if ($_SESSION["win_perfil"] == "PUNTOVENTA") {

                $SaldoComision = $Usuario->creditosAfiliacion;
                $SaldoComision2 = "$ " . number_format($SaldoComision, 2, ',', '.');

                $SaldoTotal = $SaldoComision + $SaldoJuego;
                $SaldoTotal2 = "$ " . number_format($SaldoTotal, 2, ',', '.');

            }


            $MediaLibrary = array();
            $WalletsList = array(
                array(
                    'id' => '0',
                    'value' => 'Online'
                ), array(
                    'id' => '1',
                    'value' => 'Quisk'
                )
            );

            $ReportCountryIso = '';
            if ($ReportCountry != '' && $ReportCountry != '0') {
                $Pais2 = new Pais($ReportCountry);
                $ReportCountryIso = $Pais2->iso;
            }

            $response["Data"] = array(
                "typeDate" => '1',
                "MediaLibrary" => $MediaLibrary,
                "WalletsList" => $WalletsList,
                "AuthenticationStatus" => 0,
                "SaldoRecargas" => $SaldoRecargas,
                "SaldoJuego" => $SaldoJuego,
                "SaldoRecargas2" => $SaldoRecargas2,
                "SaldoJuego2" => $SaldoJuego2,
                "SaldoTotal2" => $SaldoTotal2,
                "SaldoComision2" => $SaldoComision2,

                "PartnerLimitType" => 1,
                "FirstName" => $Usuario->nombre,
                "Settings" => array(
                    "Language" => strtolower($Usuario->idioma),
                    "ReportCurrency" => $Usuario->monedaReporte,
                    "ReportCountry" => $ReportCountry,
                    "ReportCountryIso" => $ReportCountryIso,
                    "TimeZone" => $Usuario->timezone,

                ),
                "LangId" => strtolower($Usuario->idioma),
                "UserName" => $Usuario->nombre,
                "CurrencyId" => $Usuario->moneda,
                "UserId" => $Usuario->usuarioId,
                "AgentId" => $Usuario->usuarioId,
                "PermissionList" => $menus_string,
                "Version" => "Virtualsoft - v8.1.0",
                "Countries" => $paisesparamenu,
                "BetShops" => $finalBetShops,
                "SignatureDocument" => $SignatureDocument,

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
            //Obtener Usuario Online relacionado para apuestas desde puntos de venta o cajero.
            if ($_SESSION["win_perfil"] == "PUNTOVENTA" || $_SESSION["win_perfil"] == "CAJERO") {
                try {
                    $Clasificador = new Clasificador('', 'RELATIONSHIPUSERONLINE');
                    $UsuarioConfiguracion = new UsuarioConfiguracion($_SESSION['usuario'], 'A', $Clasificador->getClasificadorId());
                    $RelationshipUserOnline = (intval($UsuarioConfiguracion->getValor()) !== 0) ? $UsuarioConfiguracion->getValor() : null;
                } catch (Exception $e) {
                }
            }
            //Obtenemos el menu de el usuario actual

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

            $mandanteMenu = 0;

            if ($_SESSION["mandante"] != '') {
                $mandanteMenu = $_SESSION["mandante"];
            }
            $mandanteMenu = '-1';

            $json = '{"rules" : [{"field" : "menu.version", "data" : "2","op":"eq"},{"field" : "perfil_submenu.perfil_id", "data" : "' . $Perfil_id . '","op":"eq"},{"field" : "perfil_submenu.mandante", "data" : "' . $mandanteMenu . '","op":"eq"}] ,"groupOp" : "AND"}';

            //Llamamos al metodo para obtener los menus
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


            //Llamamos al metodo para obtener los menus
            $menus_string = obtenerMenu();


            //Obtenemos los paises disponibles para el usuario
            $paisesparamenu = obtenerPaisesReport();


            /*
             * Obtenemos los proveedores de casino disponibles para el usuario
             */

            $Proveedor = new Proveedor();
            $Proveedor->setTipo("CASINO");

            $paramMandante = '';
            if ($_SESSION['Global'] == "N") {
                $paramMandante = $_SESSION['mandante'];
            } else {
                $paramMandante = $_SESSION["mandanteLista"];

            }

            /*            $proveedores = $Proveedor->getProveedores($paramMandante, 'A');*/

            $finalProveedores = [];
            $array = [];
            $array["id"] = '0';
            $array["value"] = 'Todos';
            array_push($finalProveedores, $array);

            /*foreach ($proveedores as $key => $value) {

                $array = [];

                $array["id"] = $value->getProveedorId();
                $array["value"] = $value->getDescripcion();

                array_push($finalProveedores, $array);

            }*/

            /*
             * Obtenemos los proveedores de casino en vivo disponibles para el usuario
             */
            $Proveedor = new Proveedor();
            $Proveedor->setTipo("LIVECASINO");

            $paramMandante = '';
            if ($_SESSION['Global'] == "N") {
                $paramMandante = $_SESSION['mandante'];
            } else {
                $paramMandante = $_SESSION["mandanteLista"];

            }


            /*$proveedores = $Proveedor->getProveedores($paramMandante, 'A');

            foreach ($proveedores as $key => $value) {

                $array = [];

                $array["id"] = $value->getProveedorId();
                $array["value"] = $value->getDescripcion();

                array_push($finalProveedores, $array);

            }*/

            $Proveedor = new Proveedor();
            $Proveedor->setTipo("VIRTUAL");

            $paramMandante = '';
            if ($_SESSION['Global'] == "N") {
                $paramMandante = $_SESSION['mandante'];
            } else {
                $paramMandante = $_SESSION["mandanteLista"];

            }


            /*            $proveedores = $Proveedor->getProveedores($paramMandante, 'A');

                        foreach ($proveedores as $key => $value) {

                            $array = [];

                            $array["id"] = $value->getProveedorId();
                            $array["value"] = $value->getDescripcion();

                            array_push($finalProveedores, $array);

                        }*/

            /*
            * Obtenemos los Subproveedores de casino disponibles para el usuario
            */
            $finalSubProveedores = [];

            $Subproveedor = new Subproveedor();
            $Subproveedor->setTipo("CASINO");

            $paramMandante = '';
            if ($_SESSION['Global'] == "N") {
                $paramMandante = $_SESSION['mandante'];
            } else {
                $paramMandante = $_SESSION["mandanteLista"];

            }


            /*            $proveedores = $Subproveedor->getSubproveedores($paramMandante, 'A');*/

            $array = [];
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
            $Subproveedor = new Subproveedor();
            $Subproveedor->setTipo("LIVECASINO");

            $paramMandante = '';
            if ($_SESSION['Global'] == "N") {
                $paramMandante = $_SESSION['mandante'];
            } else {
                $paramMandante = $_SESSION["mandanteLista"];

            }

            /*            $proveedores = $Subproveedor->getSubproveedores($paramMandante, 'A');*/

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


            $Subproveedor = new Subproveedor();
            $Subproveedor->setTipo("VIRTUAL");

            $paramMandante = '';
            if ($_SESSION['Global'] == "N") {
                $paramMandante = $_SESSION['mandante'];
            } else {
                $paramMandante = $_SESSION["mandanteLista"];

            }

            /*            $proveedores = $Subproveedor->getSubproveedores($paramMandante, 'A');*/

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

            $ProductoMandante = new ProductoMandante();

            $rules = [];
            array_push($rules, array("field" => "proveedor.tipo", "data" => "CASINO ", "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);


            // Si el usuario es de Global en configuración entonces los ID son diferentes

            /*if(!in_array($_SESSION["win_perfil2"],array("CAJERO","PUNTOVENTA","CONCESIONARIO","CONCESIONARIO2"))) {
                if ($_SESSION["Global"] == "S") {
                    $Producto = new Producto();

                    $productos = $Producto->getProductosCustom(" producto.* ", "producto.producto_id", "asc", $SkeepRows, $MaxRows, $json, true);
                    $productos = json_decode($productos);
                } else {

                    array_push($rules, array("field" => "producto_mandante.mandante", "data" => $_SESSION["mandante"], "op" => "eq"));
                    array_push($rules, array("field" => "proveedor_mandante.estado", "data" => 'A', "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json = json_encode($filtro);


                    $productos = $ProductoMandante->getProductosMandanteCustom(" producto_mandante.*,mandante.*,producto.* ", "producto_mandante.prodmandante_id", "asc", $SkeepRows, $MaxRows, $json, true);
                    $productos = json_decode($productos);

                }
            }*/
            $finalProductos = [];

            /*foreach ($productos->data as $key => $value) {

                $array = [];
                if ($_SESSION["Global"] == "S") {
                    $array["id"] = $value->{"producto.producto_id"};
                    $array["value"] = $value->{"producto.descripcion"};

                } else {
                    $array["id"] = $value->{"producto_mandante.prodmandante_id"};
                    $array["value"] = $value->{"producto.descripcion"};

                }
                array_push($finalProductos, $array);

            }*/


            $rules = [];

            // Si el usuario es un Agente 1 - CONCESIONARIO entonces obtenemos su red mediante usupadre_id
            if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
                $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

                array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION['usuario'], "op" => "eq"));
                array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            }

            // Si el usuario es un Agente 2 - CONCESIONARIO2 entonces obtenemos su red  mediante usupadre2_id
            if ($_SESSION["win_perfil"] == "CONCESIONARIO2") {
                $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

                array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            }

            // Si el usuario es un Agente 2 - CONCESIONARIO3 entonces obtenemos su red  mediante usupadre3_id
            if ($_SESSION["win_perfil"] == "CONCESIONARIO3") {
                $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

                array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            }

            // Si el usuario es un Agente 1 - PUNTOVENTA entonces obtenemos su red  mediante usuhijo_id
            if ($_SESSION["win_perfil"] == "PUNTOVENTA") {
                $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

                array_push($rules, array("field" => "concesionario.usuhijo_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            }

            array_push($rules, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));
            // Obtenemos toda la red en cuanto a Puntos de venta
            array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));

            // Si el usuario esta condicionado por País
            if ($_SESSION['PaisCond'] == "S") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
            } else {
                if ($_SESSION["PaisCondS"] != '') {
                    array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['PaisCondS'], "op" => "eq"));
                }
            }

            // Si el usuario esta condicionado por el mandante y no es de Global
            if ($_SESSION['Global'] == "N") {
                array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
            } else {

                if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                    array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                }

            }


            // Inactivamos reportes para el país Colombia
            //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $jsonbetshop = json_encode($filtro);


            $PuntoVenta = new PuntoVenta();

            // Obtenemos los puntos de venta de la red de  el usuario
            //$mandantes = $PuntoVenta->getPuntoVentasCustom("usuario.estado_valida,usuario.fecha_crea,usuario.moneda,usuario.fecha_ult,punto_venta.*", "punto_venta.puntoventa_id", "asc", 0, 100000, $jsonbetshop, true);

            //$mandantes = json_decode($mandantes);

            $finalBetShops = [];
            /*foreach ($mandantes->data as $key => $value) {
                $array = [];
                $array["id"] = $value->{"punto_venta.usuario_id"};
                $array["value"] = $value->{"punto_venta.descripcion"};
                array_push($finalBetShops, $array);
            }*/

            // Obtenemos si el usuario esta condicionado el reporte a un país en especifico
            $ReportCountry = $Usuario->paisId;
            if ($_SESSION["win_perfil2"] == "ADMIN" || $_SESSION["win_perfil2"] == "ADMIN2" || $_SESSION["win_perfil2"] == "OPERADOR" || $_SESSION["win_perfil2"] == "SA" || $_SESSION["win_perfil2"] == "CUSTOM"
                || $_SESSION["win_perfil2"] == "COORDOPER" || $_SESSION["win_perfil2"] == "ANALISTAOPER" || $_SESSION["win_perfil2"] == "COORDSOPORTE"
                || $_SESSION["win_perfil2"] == "OPERSOPORTE" || $_SESSION["win_perfil2"] == "COORDCONTRIESGO" || $_SESSION["win_perfil2"] == "ANALISTACONTRIE"
                || $_SESSION["win_perfil2"] == "COMERCIAL" || $_SESSION["win_perfil2"] == "ACCOUNT" || $_SESSION["win_perfil2"] == "TIADMIN"
                || $_SESSION["win_perfil2"] == "TIINCIDENTES" || $_SESSION["win_perfil2"] == "TICONFIGURACION" || $_SESSION["win_perfil2"] == "TIGESTION"
                || $_SESSION["win_perfil2"] == "TIBI" || $_SESSION["win_perfil2"] == "ADMINVIRTUAL" || $_SESSION["win_perfil2"] == "IMPLEMENT" || $_SESSION["win_perfil2"] == "QUOTA" || $_SESSION["win_perfil2"] == "FINANCIEROTERC"
            ) {
                if ($_SESSION['PaisCond'] != "S") {
                    $ReportCountry = '0';
                }
            }
            if ($_SESSION['PaisCondS'] != "") {
                $ReportCountry = $_SESSION['PaisCondS'];
            }

            // Obtenemos los saldos de recarga y juego de el usuario
            $SaldoRecargas = 0;
            $SaldoJuego = 0;
            if ($_SESSION["win_perfil2"] == "CONCESIONARIO" || $_SESSION["win_perfil2"] == "CONCESIONARIO2" || $_SESSION["win_perfil2"] == "CONCESIONARIO3" || $_SESSION["win_perfil2"] == "PUNTOVENTA") {
                $PuntoVenta = new PuntoVenta("", $_SESSION["usuario"]);

                $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                $SaldoJuego = $PuntoVenta->getCreditosBase();
            }


            if ($_SESSION["win_perfil2"] == "CAJERO") {
                $PuntoVenta = new PuntoVenta("", $Usuario->puntoventaId);

                $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                $SaldoJuego = $PuntoVenta->getCreditosBase();
            }


            // Verificamos si el usuario ya realizo cierre de caja
            $fechaCierre = date("Y-m-d", strtotime($Usuario->fechaCierrecaja));
            $hizoCierreCaja = false;

            if ($fechaCierre == date("Y-m-d")) {
                $hizoCierreCaja = true;
            }

            // Verificamos si el usuario ya realizo "Inicio de día"
            $beginDay = false;

            $ConfigurationEnvironment = new ConfigurationEnvironment();

            if ($ConfigurationEnvironment->isDevelopment() || true) {

                if (false && $_SESSION["win_perfil2"] == "CAJERO") {
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


                } elseif (false && $_SESSION["win_perfil2"] == "PUNTOVENTA") {

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
                }
            }


            // Si el usuario tiene configurado Global, obtenemos los partners de el sistema
            $finalMandante = [];
            if ($_SESSION["GlobalConfig"] == "S") {

                // Agregamos como primer elemento el partner Global que es virtualsoft
                $array = [];
                $array["id"] = "-1";
                $array["value"] = "Virtualsoft";

                $array["Countries"] = array(
                    array(
                        "id" => "",
                        "value" => "Todos"
                    )
                );

                $rules = [];

                array_push($rules, array("field" => "pais_mandante.mandante", "data" => $array["id"], "op" => "eq"));
                array_push($rules, array("field" => "pais_mandante.estado", "data" => "A", "op" => "eq"));


                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $PaisMandante = new PaisMandante();

                $paises = $PaisMandante->getPaisMandantesCustom(" pais_mandante.*,pais.* ", "pais_mandante.paismandante_id", "asc", 0, 1000, $json, true);

                $paises = json_decode($paises);

                foreach ($paises->data as $key2 => $value2) {
                    $array2 = [];
                    $array2["id"] = $value2->{"pais_mandante.pais_id"};
                    $array2["value"] = $value2->{"pais.pais_nom"};
                    $array2['iso'] = strtolower($value2->{'pais.iso'});
                    array_push($array["Countries"], $array2);

                }
                array_push($finalMandante, $array);

                $Mandante = new Mandante();

                $rules = [];

                if ($_SESSION["win_perfil"] != 'SA') {


                    if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                        array_push($rules, array("field" => "mandante.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                    }
                }
                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $mandantes = $Mandante->getMandantes("mandante.mandante", "asc", 0, 100, $json, true);
                $mandantes = json_decode($mandantes);


                foreach ($mandantes->data as $key => $value) {


                    $array = [];
                    $array["id"] = $value->{"mandante.mandante"};
                    $array["value"] = $value->{"mandante.descripcion"};
                    $array['logo'] = $value->{'mandante.logo'};
                    $array['name'] = $value->{'mandante.nombre'};

                    $array["name"] = $value->{"mandante.descripcion"};
                    $array["url"] = $value->{"mandante.base_url"};
                    $array["image"] = $value->{"mandante.logo"};
                    $array["favicon"] = $value->{"mandante.favicon"};
                    $array["Countries"] = array(
                        array(
                            "id" => "",
                            "value" => "Todos"
                        )
                    );

                    $rules = [];

                    array_push($rules, array("field" => "pais_mandante.mandante", "data" => $array["id"], "op" => "eq"));
                    array_push($rules, array("field" => "pais_mandante.estado", "data" => "A", "op" => "eq"));


                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json = json_encode($filtro);

                    $PaisMandante = new PaisMandante();

                    $paises = $PaisMandante->getPaisMandantesCustom(" pais_mandante.*,pais.* ", "pais_mandante.paismandante_id", "asc", 0, 1000, $json, true);
                    $paises = json_decode($paises);

                    foreach ($paises->data as $key2 => $value2) {
                        $array2 = [];
                        $array2["id"] = $value2->{"pais_mandante.pais_id"};
                        $array2["value"] = $value2->{"pais.pais_nom"};
                        $array2['iso'] = strtolower($value2->{'pais.iso'});
                        $array2['currency'] = $value2->{'pais_mandante.moneda'};
                        array_push($array["Countries"], $array2);

                    }
                    array_push($finalMandante, $array);

                }
            } else {

                $array = [];

                $Mandante = new Mandante($_SESSION["mandante"]);

                $array["id"] = $Mandante->mandante;
                $array["value"] = $Mandante->descripcion;
                $array["name"] = $Mandante->descripcion;


                $array["url"] = $Mandante->baseUrl;
                $array["image"] = $Mandante->logo;
                $array["logo"] = $Mandante->logo;
                $array["favicon"] = $Mandante->favicon;


                $array["Countries"] = array(
                    array(
                        "id" => "",
                        "value" => "Todos"
                    )
                );

                $rules = [];

                array_push($rules, array("field" => "pais_mandante.mandante", "data" => $array["id"], "op" => "eq"));
                array_push($rules, array("field" => "pais_mandante.estado", "data" => "A", "op" => "eq"));

                // Si el usuario esta condicionado por País
                if ($_SESSION['PaisCond'] == "S") {
                    array_push($rules, array("field" => "pais_mandante.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
                } else {
                    if ($_SESSION["PaisCondS"] != '') {
                        array_push($rules, array("field" => "pais_mandante.pais_id", "data" => $_SESSION['PaisCondS'], "op" => "eq"));
                    }
                }

                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $PaisMandante = new PaisMandante();

                $paises = $PaisMandante->getPaisMandantesCustom(" pais_mandante.*,pais.* ", "pais_mandante.paismandante_id", "asc", 0, 1000, $json, true);
                $paises = json_decode($paises);


                foreach ($paises->data as $key2 => $value2) {
                    $array2 = [];
                    $array2["id"] = $value2->{"pais_mandante.pais_id"};
                    $array2["value"] = $value2->{"pais.pais_nom"};
                    $array2['iso'] = strtolower($value2->{'pais.iso'});
                    array_push($array["Countries"], $array2);

                }

                array_push($finalMandante, $array);

            }

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

            $logoImg = '';
            $logoSideNavImg = '';
            $skinItainment = '';
            $walletcodeSportbookITN = '';
            $typeSkinITN = '0';
            $skinJsITN = 'https://sb1client-altenar.biahosted.com/static/AltenarSportsbook.js';

            if ($_SESSION["Global"] == 'N') {

                $Mandante = new Mandante(strtolower($_SESSION["mandante"]));

                $PlayerId = $_SESSION['usuario'];
                $FromDateLocal = '2022-05-27';
                $FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $FromDateLocal)));

                $seguir = true;

                if ($OrderedItem == "") {
                    $OrderedItem = 1;
                }

                if ($_SESSION['win_perfil'] != 'PUNTOVENTA') {
                    $seguir = false;
                } else {
                    $seguir = false;
                    if ($_SESSION['mandante'] != '8') {
                        $seguir = false;
                    }

                    if ($_SESSION['mandante'] == '8' && date("Y-m-d") > '2022-05-26') {
                        $seguir = true;
                    }
                }

                if ($seguir && !isset($ForceChangeKey)) {
                    $rules = [];

                    if ($FromDateLocal != "") {
                        array_push($rules, array("field" => "usuario_log.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
                    }

                    if ($PlayerId != "") {
                        array_push($rules, array("field" => "usuario_log.usuario_id", "data" => "$PlayerId", "op" => "eq"));
                    }

                    array_push($rules, array("field" => "usuario_log.tipo", "data" => "CAMBIOCLAVE", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json = json_encode($filtro);

                    $select = "usuario_log.* ";

                    $UsuarioLog = new UsuarioLog();
                    $data = $UsuarioLog->getUsuarioLogsCustom($select, "usuario_log.usuariolog_id", "desc", $SkeepRows, $MaxRows, $json, true);

                    $data = json_decode($data);

                    $ForceChangeKey = True;

                    if (oldCount($data->data) > 0) {
                        $ForceChangeKey = False;
                    }
                }

                $SubproveedorItn = new Subproveedor("", "ITN");
                $SubproveedorMandantePais = new SubproveedorMandantePais('', $SubproveedorItn->subproveedorId, $Usuario->mandante, $Usuario->paisId);
                $Credentials = json_decode($SubproveedorMandantePais->getCredentials());
                $walletCode = $Credentials->WALLET_CODE;
                $skinItainment = $Credentials->SKIN_ID2;
                $skinJsITN = $Credentials->SKIN_JS;

                $walletcodeSportbookITN = $walletCode;
                $logoImg = $Mandante->logo;
                $logoSideNavImg = $Mandante->logo;

                $typeSkinITN = '4';

                $PaisMandante = new \Backend\dto\PaisMandante('', $Usuario->mandante, $Usuario->paisId);

                if ($_SESSION["win_perfil"] == "PUNTOVENTA" ||
                    $_SESSION["win_perfil"] == "CAJERO"
                ) {
                    if ($PaisMandante->estado != 'A') {
                        throw new Exception("No existe Token", "21");
                    }
                    $typeSkinITN = '1';
                    $walletcodeSportbookITN = $walletCode;
                }

                if ($Mandante->mandante == 18) {
                    $typeSkinITN = '4';
                    $skinItainment = 'gangabet';
                }

                $linkAffiliate = $Mandante->baseUrl . 'registro/aff/' . $ConfigurationEnvironment->encrypt_decrypt2('encrypt', $_SESSION["usuario"]);

            } else {
                $logoImg = 'https://admin.virtualsoft.tech/sources/assets/plugins/images/logo-virtualsoft.svg';
                $logoSideNavImg = 'https://admin.virtualsoft.tech/sources/assets/plugins/images/logo-virtualsoft.svg';
            }

            if ($skinJsITN == 'https://sb2widgetsstatic-altenar2.biahosted.com/altenarWSDK.js') {
                $typeSkinITN = '4';
            }

            $SaldoJuego2 = "$ " . number_format($SaldoJuego, 2, ',', '.');
            $SaldoRecargas2 = "$ " . number_format($SaldoRecargas, 2, ',', '.');
            if ($Usuario->moneda == "PEN") {
                $SaldoJuego2 = "S/. " . number_format($SaldoJuego, 2, ',', '.');
                $SaldoRecargas2 = "S/. " . number_format($SaldoRecargas, 2, ',', '.');
            }

            if ($Usuario->moneda == "CRC") {
                $SaldoJuego2 = "CRC " . number_format($SaldoJuego, 2, ',', '.');
                $SaldoRecargas2 = "CRC " . number_format($SaldoRecargas, 2, ',', '.');
            }

            $SaldoComision = '$ 0';
            $SaldoComision2 = '$ 0';
            $SaldoTotal2 = '$ 0';

            if ($_SESSION["win_perfil"] == "PUNTOVENTA") {

                $SaldoComision = $Usuario->creditosAfiliacion;
                $SaldoComision2 = "$ " . number_format($SaldoComision, 2, ',', '.');

                $SaldoTotal = $SaldoComision + $SaldoJuego;
                $SaldoTotal2 = "$ " . number_format($SaldoTotal, 2, ',', '.');

                if ($UsuarioMandante->getUsuarioMandante() == '160' || $UsuarioMandante->getUsuarioMandante() == '77305' || $UsuarioMandante->getUsuarioMandante() == '520179') {

                    $DocumentoUsuario = new DocumentoUsuario();
                    $DocumentoUsuario->usuarioId = $UsuarioMandante->getUsuarioMandante();
                    $plataforma = 0;
                    $Documentos = $DocumentoUsuario->getDocumentosNoProcesadosPRO($plataforma, $_SESSION["win_perfil2"]);

                    if (oldCount($Documentos) > 0) {

                        $SignatureDocument = false;
                        $Document = array();
                        foreach ($Documentos as $key) {
                            $array = array();
                            $array["id"] = $key["descarga.descarga_id"];
                            $array["titulo"] = $key["descarga.descripcion"];
                            $array["ruta"] = $key["descarga.ruta"];
                            $array["version"] = $key["descarga.version"];
                            $array["checksum"] = $key["descarga.encriptacion_valor"];
                            array_push($Document, $array);
                        }

                    } else {
                        $SignatureDocument = true;

//ID Titulo o descripcion Checksum Version actual

                        //boton de traer documentos, recurso de obtener documentos auco y validar
                    }

                    $SaldoComision = $Usuario->creditosAfiliacion;
                    $SaldoComision2 = "$ " . number_format($SaldoComision, 2, ',', '.');

                    $SaldoTotal = $SaldoComision + $SaldoJuego;
                    $SaldoTotal2 = "$ " . number_format($SaldoTotal, 2, ',', '.');

                    $DocumentoUsuario = new DocumentoUsuario();
                    $DocumentoUsuario->usuarioId = $UsuarioMandante->getUsuarioMandante();
                    $plataforma = 0;
                    $Documentos = $DocumentoUsuario->getDocumentosPorProcesar($plataforma, $_SESSION["win_perfil2"]);

                    if (count($Documentos) > 0) {

                        $SignatureDocument1 = false;
                        $Document2 = array();
                        foreach ($Documentos as $key) {
                            $array1 = array();
                            $array1["id"] = $key["descarga.descarga_id"];
                            $array1["titulo"] = $key["descarga.descripcion"];
                            $array1["ruta"] = $key["descarga_version.url"];
                            $array1["version"] = $key["descarga_version.version"];
                            $array1["checksum"] = $key["descarga.encriptacion_valor"];
                            array_push($Document2, $array1);

                        }

                    } else {
                        $SignatureDocument1 = true;
                    }
                }

            }

            $MediaLibrary = array();
            $WalletsList = array(
                array(
                    'id' => '0',
                    'value' => 'Online'
                ), array(
                    'id' => '1',
                    'value' => 'Quisk'
                )
            );

            $LangData = array(
                'es' => array(
                    "Es Propio?" => "Es Propio ?"
                )
            );

            $LangDataES = json_decode('{"ERROR12":"No se encuentra Nota de retiro","ERROR21":"No se encuentra Usuario autenticado","ERROR22":"No se encuentra Usuario","ERROR23":"No se encuentra Partner","ERROR24":"No se encuentra Usuario","ERROR25":"No se encuentra Proveedor","ERROR26":"No se encuentra Producto","ERROR27":"No se encuentra Producto en Partner","ERROR28":"No se encuentra Transacción para el Juego","ERROR29":"No se encuentra Transacción en nuestro sistema","ERROR30":"No se encuentra País","ERROR31":"No se encuentra Configuración de Comisión para el Producto","ERROR32":"No se encuentra Banner","ERROR33":"No se encuentra Banner para el Usuario","ERROR34":"No se encuentra Detalle para el Partner","ERROR35":"No se encuentra Banco","ERROR36":"No se encuentra Int Deporte","ERROR37":"No se encuentra Int Competencia","ERROR38":"No se encuentra Int Region","ERROR39":"No se encuentra Int Evento","ERROR40":"No se encuentra Int Equipo","ERROR41":"No se encuentra Configuración (Clasificador)","ERROR42":"No se encuentra Flujo Caja","ERROR43":"No se encuentra Identificador para Ajuste de Saldo","ERROR44":"No se encuentra Recarga","ERROR45":"No se encuentra Alerta de Usuario","ERROR46":"No se encuentra Configuracion del Usuario","ERROR47":"No se encuentra Lenguaje para el Partner","ERROR48":"No se encuentra Concesionario","ERROR49":"No se encuentra Categoria para el Producto","ERROR50":"No se encuentra Publicidad para el Usuario","ERROR51":"No se encuentra Flujocajafact","ERROR53":"No se encuentra Otra información del Usuario","ERROR62":"No se encuentra Transacción para el api del partner","ERROR63":"No se encuentra Transacción para el sportbook","ERROR64":"No se encuentra TranssportsbookDetalle","ERROR65":"No se encuentra TranssportsbookLog","ERROR66":"No se encuentra TranssportsbookApi","ERROR67":"No se encuentra Configuración del banco para el Usuario","ERROR70":"No se encuentra Centro Costo","ERROR71":"No se encuentra Cargo","ERROR72":"No se encuentra Area","ERROR73":"No se encuentra Empleado","ERROR74":"No se encuentra Concepto","ERROR75":"No se encuentra Proveedor Tercero","ERROR76":"No se encuentra Producto Tercero","ERROR77":"No se encuentra Producto tercero para el usuario","ERROR78":"No se encuentra Egreso","ERROR79":"No se encuentra Ingreso","ERROR80":"No se encuentra Identificador para el historial del usuario","ERROR81":"No se encuentra Plan de cuentas","ERROR82":"No se encuentra Contacto Comercial","ERROR83":"No se encuentra Log para el contacto comercial","ERROR84":"No se encuentra Usuario Cierre de caja","ERROR85":"No se encuentra Codigo Promocional","ERROR86":"No se encuentra Token interno para el Usuario","ERROR87":"No se encuentra Identificador del api del Usuario","ERROR88":"No se encuentra Log para Transaccion del Usuario","ERROR89":"No se encuentra Resumen de depósitos del Usuario ","ERROR90":"No se encuentra Resumen de Retiros del Usuario","ERROR91":"No se encuentra Resumen de Bonos del Usuario","ERROR92":"No se encuentra Resumen de Deporte del Usuario","ERROR93":"No se encuentra Resumen de Detalle de Deporte del Usuario","ERROR94":"No se encuentra Detalle de Automatización para el Usuario","ERROR95":"No se encuentra Automatización para el Usuario","ERROR96":"No se encuentra Configuración de Premio máximo para el Usuario","ERROR97":"No se encuentra Moneda para el país ","ERROR98":"No se encuentra Punto de Venta","ERROR99":"No se encuentra Sesión para el Usuario","ERROR100":"No se encuentra Log para el Usuario","ERROR101":"No se encuentra Log General","ERROR102":"No se encuentra Contacto","ERROR103":"No se encuentra TransaccionProducto","ERROR104":"No se encuentra Bodega de Informe Gerencial","ERROR105":"No se encuentra Sitio Tracking","ERROR106":"No se encuentra Subproveedor","ERROR107":"No se encuentra SubProveedor para el Partner","ERROR108":"No se encuentra Billetera","ERROR109":"No se encuentra Submenu","ERROR110":"No se encuentra Plantilla","ERROR111":"No se encuentra Cheque","ERROR112":"No se encuentra Registro Rapido","ERROR113":"No se encuentra Configuración de Billetera para el Usuario","ERROR114":"No se encuentra Configuración para el Partner",
            
				"ERROR10001": "Existe transacción para el proveedor",
				"ERROR10002": "Monto negativo",
				"ERROR10003": "Valor ticket diferente a rollback",
				"ERROR10004": "Debit con rollback antes",
				"ERROR10005": "No existe la transacción",
				"ERROR10006": "La transacción no es debit",
				"ERROR10007": "10007",
				"ERROR10008": "10008",
				"ERROR10009": "10009",
				"ERROR10010": "Transaccion no Existe",
				"ERROR10011": "Token vacío",
				"ERROR10012": "10012",
				"ERROR10013": "UID vacío",
				"ERROR10014": "# Debit diferente a # Credit",
				"ERROR10015": "10015",
				"ERROR10016": "10016",
				"ERROR10017": "10017",
				"ERROR10018": "10018",
				"ERROR10019": "10019",
				"ERROR10020": "10020",
				"ERROR10021": "UsuarioId vacío",
				"ERROR10022": "10022",
				"ERROR10023": "10023",
				"ERROR10024": "10024",
				"ERROR19000": "La cédula ya existe",
				"ERROR19001": "El email ya esta registrado",
				"ERROR20000": "Sesion del usuario expirada o invalida",
				"ERROR20001": "Fondos insuficientes",
				"ERROR20002": "SIGN incorrecto",
				"ERROR20003": "Usuario Inactivo",
				"ERROR20004": "Autoexclusion Casino Producto Interno",
				"ERROR20005": "Autoexclusion Casino Categoria",
				"ERROR20006": "Autoexclusion Casino SubCategoria",
				"ERROR20007": "Autoexclusion Casino Juego",
				"ERROR20008": "Limite de deposito simple",
				"ERROR20009": "Limite de deposito diario",
				"ERROR20010": "Limite de deposito semanal",
				"ERROR20011": "Limite de deposito mensual",
				"ERROR20012": "Limite de deposito anual",
				"ERROR20013": "Limite de casino simple",
				"ERROR20014": "Limite de casino diario",
				"ERROR20015": "Limite de casino semanal",
				"ERROR20016": "Limite de casino mensual",
				"ERROR20017": "Limite de casino anual",
				"ERROR20018": "Limite de casino en vivo simple",
				"ERROR20019": "Limite de casino en vivo diario",
				"ERROR20020": "Limite de casino en vivo semanal",
				"ERROR20021": "Limite de casino en vivo mensual",
				"ERROR20022": "Limite de casino en vivo anual",
				"ERROR20023": "Casino Inactivo",
				"ERROR20024": "Casino en Contingencia",
				"ERROR20025": "LiveCasino Inactivo",
				"ERROR20026": "LiveCasino en Contingencia",
				"ERROR20027": "Usuario autoexcluido total por tiempo",
				"ERROR20028": "Usuario autoexcluido total",
				"ERROR21000": "No se encuentra el numero de retiro",
				"ERROR21001": "El retiro ya fue procesado",
				"ERROR21002": "Valor menor al minimo permitido para retirar",
				"ERROR21003": "Valor mayor al máximo permitido para retirar",
				"ERROR21004": "La cuenta necesita estar verificada para poder retirar",
				"ERROR21005": "El registro debe de estar aprobado para poder retirar",
				"ERROR21006": "La cuenta necesita estar verificada para poder depositar",
				"ERROR21007": "El registro debe de estar aprobado para poder depositar",
				"ERROR21008": "Valor menor al minimo permitido para depositar",
				"ERROR21009": "Valor mayor al máximo permitido para depositar",
				"ERROR30001": "Usuario Bloqueado por minima cantidad de logueos incorrectos",
				"ERROR30002": "Usuario (Login) no existe",
				"ERROR30003": "Login con contraseña incorrectos",
				"ERROR30004": "En el momento nos encontramos en proceso de mantenimiento del sitio",
				"ERROR30005": "Usuario con registro inactivo",
				"ERROR30006": "El usuario excede el número de cuentas bancarias registradas permitidas",
				"ERROR50001": "Error en los datos enviados",
				"ERROR50002": "La solicitud al mandante fue vacia",
				"ERROR60001": "Rechazada por Automation",
				"ERROR60002": "Necesita aprobación por automation",
				"ERROR100000": "Error General",
				"ERROR100001": "Error en los parametros enviados",
				"ERROR100002": "Punto de venta no tiene cupo disponible para realizar la recarga",
				"ERROR100003": "Punto de venta no tiene cupo disponible para realizar la apuesta",
				"ERROR200000": "Su sesión a expirado",
				"ERROR21010": "Producto no disponible",
				"ERROR30008": "El codigo de bono ingresado es incorrecto",
				
				"ERROR30009": "Billetera no esta configurada","ERROR30020": "Bono para lealtad no disponible","ERROR50001": "Error en los datos enviados","ERROR50002": "La solicitud al mandante fue vacia","ERROR60001": "Rechazada por Automation","ERROR60002": "Necesita aprobación por automation","ERROR60003": "Usuario no pertenece al parnet","ERROR100000": "Error General","ERROR100001": "Error en los parametros enviados","ERROR100002": "Punto de venta no tiene cupo disponible para realizar la recarga","ERROR100003": "Punto de venta no tiene cupo disponible para realizar la apuesta","ERROR100004": "Punto de venta excedió el cupo permitido por dia","ERROR100005": "Punto de venta no tiene habilitado para realizar depósitos","ERROR100010": "No existe el usuario por documento y tipo de documento","ERROR100011": "El recurso ha expirado","ERROR100012": "hash incorrecto","ERROR100013": "IP no encontrada","ERROR100030": "El usuario ha excedido el limite de saldo que puede tener"}');

            $PriceSymbol = '';
            if ($Usuario->moneda == "CRC") {
                $PriceSymbol = 'CRC';
            }
            if ($Usuario->moneda == "PEN") {
                $PriceSymbol = 'PEN';
            }
            if ($Usuario->moneda == "USD") {
                $PriceSymbol = 'USD';
            }
            $PriceSymbol = '';


            array_push($MediaLibrary, $logoImg);
            array_push($MediaLibrary, $logoSideNavImg);

            $groupDelimiter = '.';

            $delimiterByCommaPartners = [1, 2];
            if (in_array($Mandante->mandante, $delimiterByCommaPartners)) {
                $groupDelimiter = ',';
            }

            $ReportCountryIso = '';
            if ($ReportCountry != '' && $ReportCountry != '0') {
                $Pais2 = new Pais($ReportCountry);
                $ReportCountryIso = $Pais2->iso;
            }

            if ($_SESSION['mandante'] != '' && $_SESSION['mandante'] != '-1') {
                $mandante = new Mandante($_SESSION['mandante']);
                $PartnerName = $mandante->nombre;
            } else {
                $PartnerName = 'Virtualsoft';
            }


            $BetPhone = false;
            $BetEmail = false;
            if ($_SESSION["usuario"] == '160' || $_SESSION["usuario"] == '2757885' || $_SESSION["usuario"] == '3194449' || $_SESSION["usuario"] == '3677279' || $_SESSION["usuario"] == '4120609' || $_SESSION["usuario"] == '4612739' || $_SESSION["usuario"] == '877376' || $_SESSION["usuario"] == '3677279') {

                $BetPhone = true;
            }

            $TokenSportbook = $Usuario->tokenItainment;
            if (true) {


                $Proveedor = new Proveedor('', 'ITN');
                try {
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->usumandanteId);

                    /*$token = $UsuarioToken->createToken();
                    $UsuarioToken->setToken($token);

                    $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                    $UsuarioTokenMySqlDAO->update($UsuarioToken);

                    $UsuarioTokenMySqlDAO->getTransaction()->commit();*/

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
                $TokenSportbook = $UsuarioToken->getToken();

            }
            $oddsFormat = 0;
            if ($Usuario->mandante == 22) {
                $oddsFormat = 2;
            }

            // Respondemos correctamente con todos los datos de el usuario
            $response["Data"] = array(
                "typeDate" => '1',
                "BetPhone" => $BetPhone,
                'BetEmail' => $BetEmail,
                "PriceSymbol" => $PriceSymbol,
                "MediaLibrary" => $MediaLibrary,
                "WalletsList" => $WalletsList,
                "ForceChangeKey" => $ForceChangeKey,
                "ForceQrRead" => $ForceQrRead,
                "PartnerName" => $PartnerName,
                "CountryName" => $_SESSION['pais_nom'],
                "logo" => $logoImg,
                "logoSideNav" => $logoSideNavImg,
                "AuthenticationStatus" => 0,
                "SaldoRecargas" => $SaldoRecargas,
                "SaldoJuego" => $SaldoJuego,
                "SaldoRecargas2" => $SaldoRecargas2,
                "SaldoJuego2" => $SaldoJuego2,
                "SaldoTotal2" => $SaldoTotal2,
                "SaldoComision2" => $SaldoComision2,
                "PartnerLimitType" => 1,
                "FirstName" => $Usuario->nombre,
                "ConfigBack" => array(
                    "MinimumWithdraw" => $minimoRetiro,
                    "MaximumWithdraw" => $MaxRetiro,
                    "MinimumWithdrawBetShop" => $MinRetiroPuntoVenta,
                    "RequestMinAmountWithdrawBankAccount" => $RequestMinAmountWithdrawBankAccount,
                    "MAXWITHDRAWBETSHOP" => $MAXWITHDRAWBETSHOP,
                    "MaxWithdrawBetKashnet" => $MaxWithdrawBetKashnet,
                    "MinWithdrawBekashnet" => $MinWithdrawBetkashnet,
                    "DAYSEXPIREPASSWORD" => $DAYSEXPIREPASSWORD,
                    "DAYSNOTIFYPASSEXPIRE" => $DAYSNOTIFYPASSEXPIRE,
                    "MINLENPASSWORD" => $MinPassword,
                ),

                "Settings" => array(
                    "Language" => strtolower($Usuario->idioma),
                    "ReportCurrency" => $Usuario->monedaReporte,
                    "ReportCountry" => $ReportCountry,
                    "ReportCountryIso" => $ReportCountryIso,
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
                "walletcodeSportbookITN" => $walletcodeSportbookITN,

                "typeSkinITN" => $typeSkinITN,
                "skinJsITN" => $skinJsITN,
                "groupDelimiter" => $groupDelimiter,

                "Partners" => $finalMandante,
                "PartnerSelected" => $PartnerSelected,
                "GamesCasino" => $finalProductos,
                "PermissionList" => $menus_string,
                "Version" => "Virtualsoft - v8.1.0",
                "TokenSportsbook" => $TokenSportbook,
                "oddsFormat" => $oddsFormat,
                "SignatureDocument" => $SignatureDocument,
                "SignatureDocument2" => $SignatureDocument1,
                "Document2" => $Document2,
                "Document" => $Document,
                "linkAffiliate" => $linkAffiliate
            );

            if (isset($ForceChangeId)) $response['Data']['ForceChangeId'] = $ForceChangeId;
            if (isset($ForceQrRead)) $response['Data']['ForceQrRead'] = $ForceQrRead;

            if (true) {
                if ($PartnerSelected != '-1') {
                    $paisiso = "";
                    if ($_SESSION['PaisCond'] == "S") {
                        $paisiso = strtolower($Pais->iso);
                    } else {
                        if ($_SESSION["PaisCondS"] != '') {
                            $ReportCountry = $_SESSION["PaisCondS"];
                        }
                    }

                    $ReportCountryIso = '';
                    if ($ReportCountry != '' && $ReportCountry != '0') {
                        $Pais2 = new Pais($ReportCountry);
                        $ReportCountryIso = $Pais2->iso;
                        $paisiso = strtolower($ReportCountryIso);
                    }
                    try {
                        $ConfigMandante = new \Backend\dto\ConfigMandante("", $PartnerSelected);
                        $configg = json_decode(($ConfigMandante->getConfig()));

                        if ($paisiso == '') {
                            $LangData = $configg;
                            foreach ($configg as $LangData) break;

                            $LangData = json_decode(Utf8_ansi(json_encode($LangData)));


                        } else {

                            $LangData = $configg->{$paisiso};

                        }

                        if ($_SESSION['usuario'] == '25415') {
                            if (isset($configg) && isset($configg->bannersDesktop) && isset($configg->bannersDesktop->{$paisiso}) && isset($configg->bannersDesktop->{$paisiso}->en)
                                && isset($configg->bannersDesktop->{$paisiso}->en->login) && isset($configg->bannersDesktop->{$paisiso}->en->login->backoffice)) {
                                $response["Data"] ["banners"] = $configg->bannersDesktop->{$paisiso}->en->login->backoffice;

                            }

                        }

                    } catch (Exception $e) {

                    }
                }
                $response["Data"] ["LangData"] = $LangData;
            }

            $response["Data"] ["LangData"] = json_decode(json_encode($response["Data"] ["LangData"]), true);
            $response["Data"] ["LangData"]["es"] = (object)array_merge((array)$response["Data"] ["LangData"]["es"], (array)$LangDataES);

            if (isset($RelationshipUserOnline)) $response['Data']['RelationshipUserOnline'] = $RelationshipUserOnline;

            try {
                $Clasificador = new Clasificador('', 'ACTIVEANONYMOUSBETS');
                $Mandantedetalle = new MandanteDetalle('', $Usuario->mandante, $Clasificador->getClasificadorId(), $_SESSION['pais_id']);
                $IsActiveRelationshipUserOnline = ($Mandantedetalle->getEstado() == 'A') ? true : false;
                $response['Data']['IsActiveRelationshipUserOnline'] = $IsActiveRelationshipUserOnline;
            } catch (Exception $e) {

            }

            try {
                $Clasificador = new Clasificador('', 'DOCUAPUANONIMA');
                $Mandantedetalle = new MandanteDetalle('', $Usuario->mandante, $Clasificador->getClasificadorId(), $_SESSION['pais_id'], 'A');
                $IsActiveAnonymousDocument = ($Mandantedetalle->getValor() == 'A') ? true : false;
                $response['Data']['IsActiveAnonymousDocument'] = $IsActiveAnonymousDocument;
            } catch (Exception $e) {

            }

            try {
                $Clasificador = new Clasificador('', 'CELUAPUANONIMA');
                $Mandantedetalle = new MandanteDetalle('', $Usuario->mandante, $Clasificador->getClasificadorId(), $_SESSION['pais_id'], 'A');
                $IsActiveAnonymousPhone = ($Mandantedetalle->getValor() == 'A') ? true : false;
                $response['Data']['IsActiveAnonymousPhone'] = $IsActiveAnonymousPhone;
            } catch (Exception $e) {

            }

            try {
                $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->usumandanteId);
                $response['Data']['casino_token'] = $UsuarioToken->getToken();
            } catch (Exception $e) {

            }

            try {
                $Clasificador = new Clasificador('', 'CLOUSERIDSOCKET');
                $Mandantedetalle = new MandanteDetalle('', $Usuario->mandante, $Clasificador->getClasificadorId(), $_SESSION['pais_id']);
                $ClouserId = $Mandantedetalle->getValor();
                $response['Data']['ClouserId'] = $ClouserId;
            } catch (Exception $e) {

            }

            try {
                $Clasificador = new Clasificador('', 'CLOUSERKEYSOCKET');
                $Mandantedetalle = new MandanteDetalle('', $Usuario->mandante, $Clasificador->getClasificadorId(), $_SESSION['pais_id']);
                $KeySocket = $Mandantedetalle->getValor();
                $response['Data']['KeySocket'] = $KeySocket;
            } catch (Exception $e) {

            }

        }
        if ($_SESSION['usuario'] == '7974121' || $_SESSION['usuario'] == '5703' || $_SESSION['usuario'] == '3003641') {
            $response['Data']['IsActiveRelationshipUserOnline'] = true;
        }


    } catch (Exception $e) {
        if ($_ENV['debug']) {
            print_r($e);
        }
        // Respondemos ERROR en la solicitud por un error general desconocido
        $response["HasError"] = true;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = "General Error ('.$e->getCode().')";
        $response["ModelErrors"] = [];

        $response["Data"] = array(
            "AuthenticationStatus" => 0,

            "PermissionList" => array(),
            "Version" => "Virtualsoft - v8.1.0",
        );

    }

}


?>
