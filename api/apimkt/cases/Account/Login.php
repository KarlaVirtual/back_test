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
 * Account/Login
 *
 * Inicio de sesión de un usuario en la plataforma, con usuario y contraseña.
 *
 * @param object $params Objeto que contiene los parámetros de entrada:
 * - string $params->Username Nombre de usuario.
 * - string $params->Password Contraseña del usuario.
 * - int $params->PartnerLogin Identificador del socio.
 *
 *
 * @return array $response Arreglo que contiene la respuesta de la autenticación:
 * - bool $response["HasError"] Indica si hubo un error.
 * - string $response["AlertType"] Tipo de alerta (success, danger, etc.).
 * - string $response["AlertMessage"] Mensaje de alerta.
 * - array $response["ModelErrors"] Errores del modelo.
 * - bool $response["ReqTokenGoogle"] Indica si se requiere token de Google.
 * - array $response["Data"] Datos adicionales de la respuesta:
 *   - int $response["Data"]["AuthenticationStatus"] Estado de la autenticación.
 *   - float $response["Data"]["SaldoRecargas"] Saldo de recargas.
 *   - float $response["Data"]["SaldoJuego"] Saldo de juego.
 *   - array $response["Data"]["PermissionList"] Lista de permisos.
 *   - array $response["Data"]["Settings"] Configuraciones del usuario.
 *   - string $response["Data"]["Settings"]["Language"] Idioma del usuario.
 *   - string $response["Data"]["Settings"]["ReportCurrency"] Moneda de reporte.
 *   - string $response["Data"]["Settings"]["ReportCountry"] País de reporte.
 *   - string $response["Data"]["Settings"]["TimeZone"] Zona horaria.
 *   - string $response["Sess"] ID de la sesión.
 *   - array $response["Data"]["Countries"] Lista de países.
 *   - array $response["Data"]["BetShops"] Lista de puntos de venta.
 *   - array $response["Data"]["ProvidersCasino"] Lista de proveedores de casino.
 *   - array $response["Data"]["Partners"] Lista de socios.
 *   - array $response["Data"]["GamesCasino"] Lista de juegos de casino.
 *   - string $response["Data"]["PartnerSelected"] Socio seleccionado.
 *
 * @throws Exception Si el usuario o la contraseña son inválidos.
 */


/* Inicializa variables para omitir filas y establecer un máximo, y obtiene credenciales. */
$SkeepRows = 0;
$MaxRows = 100000;

// Obtenemos el usuario y la clave a validar
$usuario = $params->Username;
$clave = $params->Password;

/* valida el usuario y contraseña, y maneja errores de autenticación. */
$PartnerLogin = $params->PartnerLogin;
//Obtenemos la dirección IP del usuario
$dirIP = str_replace(' ', '', explode(",", (new ConfigurationEnvironment())->get_client_ip())[0]);

$seguir = true;

//Verificamos que el usuario y la contraseña no esten vacios

if ($clave == "" || $usuario == "") {
    $usuario = $params->username;
    $clave = $params->password;

    // Verificamos que no sea por el nombre de los campos
    if ($clave == "" || $usuario == "") {

        $response["HasError"] = true;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = "Invalid Username and/or password ('.$e->getCode().')";
        $response["ModelErrors"] = [];
        $seguir = false;

    }
}

// Si todo esta correcto, procedemos a validar el usuario y la clave
if ($seguir) {

    /* Código para iniciar sesión de un usuario con validaciones específicas comentadas. */
    $Usuario = new Usuario();

    /*if(in_array($usuario,array('ADMINMIRAVALLE','OPERMIRAVALLE','FINMIRAVALLE')) ){
        if($PartnerLogin != 3){
            throw new Exception("No existe ", "30002");
        }
        $PartnerLogin=0;
    }*/

    $responseU = $Usuario->login($usuario, $clave, 0, $PartnerLogin);


    /*
$UsuarioToken = new UsuarioToken("", $responseU->user_id);

$UsuarioToken->setRequestId($json->session->sid);
$UsuarioToken->setCookie(encrypt($responseU->user_id . "#" . time()));

$UsuarioTokenMySqlDAO = new \Backend\mysql\UsuarioTokenMySqlDAO();
$UsuarioTokenMySqlDAO->update($UsuarioToken);
$UsuarioTokenMySqlDAO->getTransaction()->commit();
 */

    // Si todo esta correcto, respondemos positivamente


    /* Establece una respuesta inicial sin errores y crea un objeto de perfil de usuario. */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];
    $response["ReqTokenGoogle"] = false;

    /*
"ViewClients","ViewMenuDashBoard","ManageClients","ViewPaymentReport","ViewDepositWithdrawalReport","ViewBalance","ViewSalesReport","ViewClientTurnoverReport","ViewMenuReport",
 */
    $UsuarioPerfil = new UsuarioPerfil($_SESSION["usuario"]);


    /* establece variables de sesión basadas en el perfil de usuario. */
    $_SESSION["PaisCond"] = $UsuarioPerfil->pais;
    $_SESSION["Global"] = $UsuarioPerfil->global;
    $_SESSION["GlobalConfig"] = $UsuarioPerfil->global;
    $_SESSION["monedaReporte"] = $Usuario->monedaReporte;
    $_SESSION['mandante'] = $UsuarioPerfil->mandante;
    $_SESSION['mandanteLista'] = $UsuarioPerfil->mandanteLista;


    /* gestiona sesiones y perfiles de usuario para establecer mandantes. */
    if ($_SESSION["Global"] == "S") {
        if ($UsuarioPerfil->globalMandante != "-1") {
            $_SESSION["Global"] = "N";
            $_SESSION['mandante'] = $UsuarioPerfil->globalMandante;
        } else {
            $_SESSION['mandante'] = '-1';

        }

    }


    /* Bloquea acceso si IP no corresponde y restricción está activa, generando alerta. */
    if ($Usuario->restriccionIp == "A" && $dirIP != $Usuario->usuarioIp) {
        $_SESSION = array();
        $response["HasError"] = true;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = "No puedes acceder desde esta IP.";
    } elseif ($Usuario->tokenGoogle == "A") {
        /* Verifica si el usuario tiene un token de Google y reinicia la sesión. */

        $_SESSION = array();
        $response["ReqTokenGoogle"] = true;
    } elseif ($UsuarioPerfil->perfilId == "USUARIO") {
        /* maneja el caso de un usuario no encontrado y establece un mensaje de error. */

        $_SESSION = array();
        $response["HasError"] = true;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = "No existe el usuario.";

    } else {

        /* establece un token de autenticación y verifica permisos de usuario. */
        header('Authentication: ' . $responseU->auth_token);

        $ReportCountry = $Usuario->paisId;
        if ($_SESSION["win_perfil2"] == "ADMIN" || $_SESSION["win_perfil2"] == "ADMIN2" || $_SESSION["win_perfil2"] == "OPERADOR" || $_SESSION["win_perfil2"] == "SA") {
            if ($_SESSION['PaisCond'] != "S") {
                $ReportCountry = '0';
            }
        }


        /* Establece saldo de recargas y juego para perfiles específicos de usuario. */
        $SaldoRecargas = 0;
        $SaldoJuego = 0;
        if ($_SESSION["win_perfil2"] == "CONCESIONARIO" || $_SESSION["win_perfil2"] == "CONCESIONARIO2" || $_SESSION["win_perfil2"] == "PUNTOVENTA") {
            $PuntoVenta = new PuntoVenta("", $_SESSION["usuario"]);

            $SaldoRecargas = $PuntoVenta->getCupoRecarga();
            $SaldoJuego = $PuntoVenta->getCreditosBase();
        }


        /* Estructura un array de respuesta con autenticación, saldos, permisos y configuraciones. */
        $response["Data"] = array(
            "AuthenticationStatus" => 0,
            "SaldoRecargas" => $SaldoRecargas,
            "SaldoJuego" => $SaldoJuego,
            "PermissionList" => array(
                "ViewMenuSecurity", "ViewMenuTeacher", "AdminUser", "Contingencia", "Menu", "Perfil", "PerfilOpciones", "Submenu", "UsuarioPerfil", "Clasificador", "Concesionario", "ViewAddHocReport", "ViewMenuManagement", "ActivarRegistros", "AjustarSaldo", "AsignacionCupo", "Bonos", "CuentaCobroEliminar", "GestionRed", "RegistroRapido", "ChequeReimpresion", "RecargaReversion", "GestionContacto", "ViewMenuCash", "FlujoCaja", "PagoPremio", "PagoNotaRetiro", "RecargarCredito", "ViewMenuQueries", "FlujoCajaHistorico", "FlujoCajaResumido", "InformeCasino", "InformeGerencial", "ListadoRecargasRetiros", "PremiosPendientesPagar", "ConsultaOnlineDetalle", "ConsultaOnlineResumen",

            ),
            "Settings" => array(
                "Language" => strtolower($Usuario->idioma),
                "ReportCurrency" => $Usuario->monedaReporte,
                "ReportCountry" => $ReportCountry,
                "TimeZone" => $Usuario->timezone,

            )
        );


        /* obtiene el ID de sesión y datos de menú y países. */
        $response["Sess"] = session_id();
        $menus_string = obtenerMenu();
        $paisesparamenu = obtenerPaisesReport();


        $Proveedor = new Proveedor();

        /* establece un tipo de proveedor y obtiene una lista de proveedores. */
        $Proveedor->setTipo("CASINO");

        $proveedores = $Proveedor->getProveedores();

        $finalProveedores = [];
        $array = [];

        /* Crea un array de proveedores, incluyendo un valor predeterminado "Todos". */
        $array["id"] = '0';
        $array["value"] = 'Todos';
        array_push($finalProveedores, $array);

        foreach ($proveedores as $key => $value) {

            $array = [];

            $array["id"] = $value->getProveedorId();
            $array["value"] = $value->getDescripcion();

            array_push($finalProveedores, $array);

        }


        /* Crea un objeto Proveedor, obtiene proveedores y los organiza en un array final. */
        $Proveedor = new Proveedor();
        $Proveedor->setTipo("LIVECASINO");

        $proveedores = $Proveedor->getProveedores();

        foreach ($proveedores as $key => $value) {

            $array = [];

            $array["id"] = $value->getProveedorId();
            $array["value"] = $value->getDescripcion();

            array_push($finalProveedores, $array);

        }


        /* Se define un filtro para productos, usando condiciones para el proveedor tipo "CASINO". */
        $ProductoMandante = new ProductoMandante();

        $rules = [];
        array_push($rules, array("field" => "proveedor.tipo", "data" => "CASINO ", "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");

        /* verifica el perfil de usuario y obtiene productos según condiciones específicas. */
        $json = json_encode($filtro);
        if (!in_array($_SESSION["win_perfil2"], array("CAJERO", "PUNTOVENTA", "CONCESIONARIO", "CONCESIONARIO2", "CONCESIONARIO3"))) {


            /*if ($_SESSION["GlobalConfig"] == "S") {
                $Producto = new Producto();

                $productos = $Producto->getProductosCustom(" producto.* ", "producto.producto_id", "asc", $SkeepRows, $MaxRows, $json, true);
                $productos = json_decode($productos);
            } else {
                $productos = $ProductoMandante->getProductosMandanteCustom(" producto_mandante.*,mandante.*,producto.* ", "producto_mandante.prodmandante_id", "asc", $SkeepRows, $MaxRows, $json, true);
                $productos = json_decode($productos);

            }*/

        }

        /* Código para transformar productos en arrays según configuración global. */
        $finalProductos = [];

        /*foreach ($productos->data as $key => $value) {

            $array = [];
            if ($_SESSION["GlobalConfig"] == "S") {
                $array["id"] = $value->{"producto.producto_id"};
                $array["value"] = $value->{"producto.descripcion"};

            } else {
                $array["id"] = $value->{"producto_mandante.prodmandante_id"};
                $array["value"] = $value->{"producto.descripcion"};

            }
            array_push($finalProductos, $array);

        }*/


        $rules = [];


        /* Verifica si el perfil es "CONCESIONARIO" y establece reglas para usuario. */
        if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
            $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

            array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION['usuario'], "op" => "eq"));
            array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
        }


        /* define reglas de acceso basadas en la sesión del usuario concesionario. */
        if ($_SESSION["win_perfil"] == "CONCESIONARIO2") {
            $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

            array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
            array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
        }


        /* Verifica rol de usuario y establece reglas para concesionarios en sesión. */
        if ($_SESSION["win_perfil"] == "PUNTOVENTA") {
            $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

            array_push($rules, array("field" => "concesionario.usuhijo_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
            array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
        }


        /* Se añaden reglas de validación basadas en perfil y condición de país. */
        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));


        // Si el usuario esta condicionado por País
        if ($_SESSION['PaisCond'] == "S") {
            array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
        }
        // Si el usuario esta condicionado por el mandante y no es de Global

        /* crea reglas de filtrado basadas en condiciones específicas de sesión y país. */
        if ($_SESSION['Global'] == "N") {
            array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
        }
        // Inactivamos reportes para el país Colombia
        array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");

        /* Convierte un filtro a JSON y obtiene puntos de venta personalizados según parámetros. */
        $jsonbetshop = json_encode($filtro);


        $PuntoVenta = new PuntoVenta();


        $mandantes = $PuntoVenta->getPuntoVentasCustom("usuario.estado_valida,usuario.fecha_crea,usuario.moneda,usuario.fecha_ult,punto_venta.*", "punto_venta.puntoventa_id", "asc", 0, 100000, $jsonbetshop, true);


        /* Decodifica JSON y organiza datos en un array con IDs y descripciones. */
        $mandantes = json_decode($mandantes);

        $finalBetShops = [];

        foreach ($mandantes->data as $key => $value) {

            $array = [];

            $array["id"] = $value->{"punto_venta.usuario_id"};
            $array["value"] = $value->{"punto_venta.descripcion"};

            array_push($finalBetShops, $array);

        }


        /* Establece país de reporte según roles de usuario y condiciones de sesión. */
        $ReportCountry = $Usuario->paisId;
        if ($_SESSION["win_perfil2"] == "ADMIN" || $_SESSION["win_perfil2"] == "ADMIN2" || $_SESSION["win_perfil2"] == "OPERADOR" || $_SESSION["win_perfil2"] == "SA") {
            if ($_SESSION['PaisCond'] != "S") {
                $ReportCountry = '0';
            }
        }


        /* calcula saldos de recargas y juego para ciertos perfiles de usuario. */
        $SaldoRecargas = 0;
        $SaldoJuego = 0;
        if ($_SESSION["win_perfil2"] == "CONCESIONARIO" || $_SESSION["win_perfil2"] == "CONCESIONARIO2" || $_SESSION["win_perfil2"] == "PUNTOVENTA") {
            $PuntoVenta = new PuntoVenta("", $_SESSION["usuario"]);

            $SaldoRecargas = $PuntoVenta->getCupoRecarga();
            $SaldoJuego = $PuntoVenta->getCreditosBase();
        }


        /* Se inicializa un arreglo vacío llamado $finalMandante en PHP. */
        $finalMandante = [];

        if ($_SESSION["GlobalConfig"] == "S") {


            /* Código PHP inicializa un array con información de una empresa y países. */
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


            /* Se añade un array a `$finalMandante` y define reglas de filtro para consulta. */
            array_push($finalMandante, $array);

            $Mandante = new Mandante();

            $rules = [];
            // array_push($rules, array("field" => "proveedor.tipo", "data" => "CASINO ", "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");

            /* Codifica datos de mandantes y países en JSON, creando un arreglo final. */
            $json = json_encode($filtro);

            $mandantes = $Mandante->getMandantes("mandante.mandante", "asc", 0, 100, $json, true);
            $mandantes = json_decode($mandantes);


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
            /* crea un arreglo con información del mandante y países. */

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


        /* crea una respuesta estructurada con información sobre países, bet shops y configuraciones. */
        $response["Data"] = array(
            "Countries" => $paisesparamenu,
            "BetShops" => $finalBetShops,

            "PermissionList" => $menus_string,
            "ProvidersCasino" => $finalProveedores,
            "SaldoRecargas" => $SaldoRecargas,
            "SaldoJuego" => $SaldoJuego,

            "Partners" => $finalMandante,
            "GamesCasino" => $finalProductos,
            "PartnerSelected" => $_SESSION["mandante"],
            "Settings" => array(
                "Language" => strtolower($Usuario->idioma),
                "ReportCurrency" => $Usuario->monedaReporte,
                "ReportCountry" => $ReportCountry,
                "TimeZone" => $Usuario->timezone,

            )
        );
    }


}