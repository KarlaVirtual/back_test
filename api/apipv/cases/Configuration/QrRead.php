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
 * Gestiona el inicio de sesión de un usuario en la plataforma, incluyendo validación de Google Authenticator.
 *
 * @param string $params->Code Código de autenticación de Google.
 * @param string $params->PartnerLogin Identificador del socio para el inicio de sesión.
 * 
 *
 * @return array $response Arreglo que contiene:
 *  - HasError (bool): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta ('success', 'danger', etc.).
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo, si los hay.
 *  - Data (array): Información adicional como permisos, países, proveedores, etc.
 *
 * @throws Exception Si ocurre un error durante la validación o actualización de datos.
 */


/* Se configuran variables y se inicializa el entorno de configuración en PHP. */
$SkeepRows = 0;
$MaxRows = 100000;

$Code = $params->Code;
$PartnerLogin = $params->PartnerLogin;

$ConfigurationEnvironment = new ConfigurationEnvironment();


/* Depura caracteres de variables de usuario, clave, código y socio para seguridad. */
$usuario = $ConfigurationEnvironment->DepurarCaracteres($usuario);
$clave = $ConfigurationEnvironment->DepurarCaracteres($clave);
$Code = $ConfigurationEnvironment->DepurarCaracteres($Code);
$PartnerLogin = $ConfigurationEnvironment->DepurarCaracteres($PartnerLogin);

$seguir = true;


// Si todo esta correcto, procedemos a validar el usuario y la clave
if ($seguir) {

    /* Crea un objeto Usuario y prepara una respuesta sin errores para la sesión. */
    $Usuario = new Usuario($_SESSION['usuario']);


    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";

    /* inicializa variables y verifica el estado del token de Google. */
    $response["ModelErrors"] = [];
    $response["ReqTokenGoogle"] = false;

    /*
"ViewClients","ViewMenuDashBoard","ManageClients","ViewPaymentReport","ViewDepositWithdrawalReport","ViewBalance","ViewSalesReport","ViewClientTurnoverReport","ViewMenuReport",
 */

    // Verificamos si el usuario tiene activado el token de Google
    $seguridadGoogle = true;
    if ($Usuario->tokenGoogle == "I") {
        if ($Usuario->saltGoogle == "") {


            /* Genera una llave secreta para la autenticación de Google en el usuario. */
            $Google = new GoogleAuthenticator();


            $Usuario->saltGoogle = $Google->createSecret();

            $UsuarioMySqlDAO = new UsuarioMySqlDAO();

            /* Actualiza un usuario en la base de datos y registra el cambio en logs. */
            $UsuarioMySqlDAO->update($Usuario);


            $UsuarioMySqlDAO->getTransaction()->commit();

            $UsuarioLog = new UsuarioLog();

            /* Se registra un usuario y sus detalles para auditoría en el sistema. */
            $UsuarioLog->setUsuarioId($Usuario->usuarioId);
            $UsuarioLog->setUsuarioIp('');

            $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
            $UsuarioLog->setUsuariosolicitaIp('');

            $UsuarioLog->setTipo("TOKENGOOGLEUSUARIO");

            /* registra un cambio de estado para un usuario en una base de datos. */
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes($Usuario->tokenGoogle);
            $UsuarioLog->setValorDespues('A');
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);

            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();

            /* Inserta un objeto $UsuarioLog en la base de datos usando el método insert. */
            $UsuarioLogMySqlDAO->insert($UsuarioLog);
        }


        /* Verifica el código de Google y actualiza el estado del usuario en la base de datos. */
        $Google = new GoogleAuthenticator();
        $returnCodeGoogle = $Google->verifyCode($Usuario->saltGoogle, $Code);

        // Verificamos si el usuario ingreso correctamente los datos de google
        if ($returnCodeGoogle) {
            $Usuario->tokenGoogle = "A";

            $UsuarioMySqlDAO = new UsuarioMySqlDAO();
            $UsuarioMySqlDAO->update($Usuario);


            $UsuarioMySqlDAO->getTransaction()->commit();
        } else {
            /* maneja un error de validación, restableciendo la sesión y generando una alerta. */

            $_SESSION = array();
            $seguridadGoogle = false;
            $response["HasError"] = true;
            $response["AlertType"] = "danger";
            $response["AlertMessage"] = "El Codigo es incorrecto.";
            $response["ModelErrors"] = [];

        }

    } else {
        /* El bloque "else" está vacío y no ejecuta ninguna acción. */


    }

    // Verificamos si el paso el filtro de google y asignamos la sesion
    if ($seguridadGoogle) {


        /* Se guarda información del perfil de usuario en la sesión actual. */
        $UsuarioPerfil = new UsuarioPerfil($_SESSION["usuario"]);

        $_SESSION["PaisCond"] = $UsuarioPerfil->pais;
        $_SESSION["Global"] = $UsuarioPerfil->global;
        $_SESSION["GlobalConfig"] = $UsuarioPerfil->global;
        $_SESSION["monedaReporte"] = $Usuario->monedaReporte;

        /* gestiona sesiones de usuario y permisos de mandante según roles. */
        $_SESSION['mandante'] = $UsuarioPerfil->mandante;
        $_SESSION['mandanteLista'] = $UsuarioPerfil->mandanteLista;

        if ($_SESSION["Global"] == "S") {
            if ($UsuarioPerfil->globalMandante != "-1") {
                $_SESSION["Global"] = "N";
                $_SESSION['mandante'] = $UsuarioPerfil->globalMandante;
            } else {
                $_SESSION['mandante'] = '-1';

            }
        }


        /* Verifica restricciones de IP y responde si el acceso no está permitido. */
        if ($Usuario->restriccionIp == "A" && $dirIP != $Usuario->usuarioIp) {
            $_SESSION = array();
            $response["HasError"] = true;
            $response["AlertType"] = "danger";
            $response["AlertMessage"] = "No puedes acceder desde esta IP.";
        } elseif ($UsuarioPerfil->perfilId == "USUARIO") {
            /* gestiona un error para usuarios sin perfil asignado. */

            $_SESSION = array();
            $response["HasError"] = true;
            $response["AlertType"] = "danger";
            $response["AlertMessage"] = "No existe el usuario.";

        } else {


            /* establece un token de autenticación y define permisos de acceso. */
            header('Authentication: ' . $responseU->auth_token);

            $response["Data"] = array(
                "AuthenticationStatus" => 0,

                "PermissionList" => array(
                    "ViewMenuSecurity", "ViewMenuTeacher", "AdminUser", "Contingencia", "Menu", "Perfil", "PerfilOpciones", "Submenu", "UsuarioPerfil", "Clasificador", "Concesionario", "ViewAddHocReport", "ViewMenuManagement", "ActivarRegistros", "AjustarSaldo", "AsignacionCupo", "Bonos", "CuentaCobroEliminar", "GestionRed", "RegistroRapido", "ChequeReimpresion", "RecargaReversion", "GestionContacto", "ViewMenuCash", "FlujoCaja", "PagoPremio", "PagoNotaRetiro", "RecargarCredito", "ViewMenuQueries", "FlujoCajaHistorico", "FlujoCajaResumido", "InformeCasino", "InformeGerencial", "ListadoRecargasRetiros", "PremiosPendientesPagar", "ConsultaOnlineDetalle", "ConsultaOnlineResumen",

                ),
            );


            /* obtiene la sesión, menús y países, y crea un objeto Proveedor. */
            $response["Sess"] = session_id();
            $menus_string = obtenerMenu();

            $paisesparamenu = obtenerPaisesReport();


            $Proveedor = new Proveedor();

            /* Se establece el tipo de proveedor y se obtienen proveedores en un array vacío. */
            $Proveedor->setTipo("CASINO");

            $proveedores = $Proveedor->getProveedores();

            $finalProveedores = [];
            $array = [];

            /* Crea un array con proveedores y lo almacena en $finalProveedores. */
            $array["id"] = '0';
            $array["value"] = 'Todos';
            array_push($finalProveedores, $array);

            foreach ($proveedores as $key => $value) {

                $array = [];

                $array["id"] = $value->getProveedorId();
                $array["value"] = $value->getDescripcion();

                array_push($finalProveedores, $array);

            }


            /* Se crea una lista de proveedores de tipo "LIVECASINO" y se organiza en un array. */
            $Proveedor = new Proveedor();
            $Proveedor->setTipo("LIVECASINO");

            $proveedores = $Proveedor->getProveedores();

            foreach ($proveedores as $key => $value) {

                $array = [];

                $array["id"] = $value->getProveedorId();
                $array["value"] = $value->getDescripcion();

                array_push($finalProveedores, $array);

            }


            /* Crea un objeto y define reglas de filtrado para un proveedor específico. */
            $ProductoMandante = new ProductoMandante();

            $rules = [];
            array_push($rules, array("field" => "proveedor.tipo", "data" => "CASINO ", "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");

            /* Codifica un filtro en JSON y obtiene productos personalizados si la configuración global es "S". */
            $json = json_encode($filtro);

            if ($_SESSION["GlobalConfig"] == "S") {
                $Producto = new Producto();

                $productos = $Producto->getProductosCustom(" producto.* ", "producto.producto_id", "asc", $SkeepRows, $MaxRows, $json, true);
                $productos = json_decode($productos);
            } else {
                /* obtiene productos desde una base de datos y los decodifica en JSON. */

                $productos = $ProductoMandante->getProductosMandanteCustom(" producto_mandante.*,mandante.*,producto.* ", "producto_mandante.prodmandante_id", "asc", $SkeepRows, $MaxRows, $json, true);
                $productos = json_decode($productos);

            }


            /* crea un array final con productos basados en la configuración global. */
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


            /* Se crea un arreglo de reglas basado en el perfil de usuario "CONCESIONARIO". */
            $rules = [];

            if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
                $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

                array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION['usuario'], "op" => "eq"));
                array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            }


            /* Verifica el perfil del usuario y agrega reglas a un arreglo en base a condiciones. */
            if ($_SESSION["win_perfil"] == "CONCESIONARIO2") {
                $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

                array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            }


            /* Condicional que verifica un perfil específico y define reglas para concesionarios. */
            if ($_SESSION["win_perfil"] == "CONCESIONARIO3") {
                $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

                array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            }


            /* Condicional que añade reglas según el perfil de usuario en sesión. */
            if ($_SESSION["win_perfil"] == "PUNTOVENTA") {
                $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

                array_push($rules, array("field" => "concesionario.usuhijo_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            }


            /* agrega reglas de validación de permisos según el perfil y país del usuario. */
            array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));


            // Si el usuario esta condicionado por País
            if ($_SESSION['PaisCond'] == "S") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
            }
            // Si el usuario esta condicionado por el mandante y no es de Global

            /* ajusta reglas según la sesión del usuario y sus permisos. */
            if ($_SESSION['Global'] == "N") {
                array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
            } else {

                if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                    array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                }

            }

            // Inactivamos reportes para el país Colombia
            //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


            /* Crea un filtro JSON y obtiene datos de puntos de venta usando esos criterios. */
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $jsonbetshop = json_encode($filtro);


            $PuntoVenta = new PuntoVenta();


            $mandantes = $PuntoVenta->getPuntoVentasCustom("usuario.estado_valida,usuario.fecha_crea,usuario.moneda,usuario.fecha_ult,punto_venta.*", "punto_venta.puntoventa_id", "asc", 0, 100000, $jsonbetshop, true);


            /* Convierte datos JSON en un arreglo, extrayendo ID y descripción de puntos de venta. */
            $mandantes = json_decode($mandantes);

            $finalBetShops = [];

            foreach ($mandantes->data as $key => $value) {

                $array = [];

                $array["id"] = $value->{"punto_venta.usuario_id"};
                $array["value"] = $value->{"punto_venta.descripcion"};

                array_push($finalBetShops, $array);

            }


            /* asigna un país a un usuario basado en su perfil y condiciones de sesión. */
            $ReportCountry = $Usuario->paisId;
            if ($_SESSION["win_perfil2"] == "ADMIN" || $_SESSION["win_perfil2"] == "ADMIN2" || $_SESSION["win_perfil2"] == "OPERADOR" || $_SESSION["win_perfil2"] == "SA") {
                if ($_SESSION['PaisCond'] != "S") {
                    $ReportCountry = '0';
                }
            }


            /* Calcula el saldo de recargas y juego para ciertos perfiles de usuario. */
            $SaldoRecargas = 0;
            $SaldoJuego = 0;
            if ($_SESSION["win_perfil2"] == "CONCESIONARIO" || $_SESSION["win_perfil2"] == "CONCESIONARIO2" || $_SESSION["win_perfil2"] == "CONCESIONARIO3" || $_SESSION["win_perfil2"] == "PUNTOVENTA") {
                $PuntoVenta = new PuntoVenta("", $_SESSION["usuario"]);

                $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                $SaldoJuego = $PuntoVenta->getCreditosBase();
            }

            $response["Data"] = array(

                "AuthenticationStatus" => 0,
                "SaldoRecargas" => $SaldoRecargas,
                "SaldoJuego" => $SaldoJuego,

                "Countries" => array(
                    array(
                        "id" => "0",
                        "value" => "Todos"
                    ),
                    array(
                        "id" => "173",
                        "value" => "Peru"
                    ),
                    array(
                        "id" => "2",
                        "value" => "Nicaragua"
                    )
                ),
                "PermissionList" => $menus_string,
                "Countries" => $paisesparamenu,
                "ProvidersCasino" => $finalProveedores,
                "BetShops" => $finalBetShops,

                "Partners" => array(
                    array(
                        "id" => "0",
                        "value" => "Doradobet",
                        "Countries" => array(
                            array(
                                "id" => "2",
                                "value" => "Nicaragua"
                            ),
                            array(
                                "id" => "173",
                                "value" => "Perú"
                            )
                        )
                    )
                ),
                "GamesCasino" => $finalProductos,

                "Settings" => array(
                    "Language" => strtolower($Usuario->idioma),
                    "ReportCurrency" => $Usuario->monedaReporte,
                    "ReportCountry" => $ReportCountry,
                    "TimeZone" => $Usuario->timezone,
                    //"ReportCurrency" => $Usuario->monedaReporte,

                )
            );
        }

    }

}
