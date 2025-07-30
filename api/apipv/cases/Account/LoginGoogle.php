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
 * Inicio de sesión de un usuario en la plataforma, con los servicios de Google
 *
 * @param no
 *
 * @return no
 * {"HasError":boolean,"AlertType": string,"AlertMessage": string,"url": string,"success": string}
 *
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */

/* Registra advertencias de acceso y valida respuestas del reCAPTCHA en el sistema. */
syslog(LOG_WARNING, "LOGINGOOGLELOGINGOOGLE:" . $_SERVER["REQUEST_URI"]);
syslog(LOG_WARNING, "LOGINGENERALGOOGLELOGIN:" . $_SERVER["REQUEST_URI"]);

/**
 * Verifica si la respuesta del reCAPTCHA de Google es válida.
 *
 * @param string $grecaptcharesponse La respuesta del reCAPTCHA
 *
 * @return bool|null Devuelve verdadero si la respuesta es válida, falso si no lo es, o null en caso de excepción.
 */
function isValid($grecaptcharesponse)
{
    try {
        /* verifica la respuesta de reCAPTCHA enviando una solicitud HTTP POST. */
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = ['secret' => '6LeWlFopAAAAADoeDeaS1DN6vhLWTmSyBJTJOiXJ',
            'response' => $grecaptcharesponse,
            'remoteip' => $_SERVER['REMOTE_ADDR']];

        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return json_decode($result)->success;
    } catch (Exception $e) {
        /* Maneja excepciones retornando nulo si ocurre un error en el código. */

        return null;
    }
}


/* valida un captcha y lanza una excepción si es inusual. */
$SkeepRows = 0;
$MaxRows = 100000;

$GrecaptchaResponse = $params->GrecaptchaResponse;

if (isValid($GrecaptchaResponse)) {
    throw new Exception("Inusual Detected", "11");
}


/* Asignación de parámetros y creación de un objeto de configuración de entorno. */
$usuario = $params->Username;
$clave = $params->Password;
$Code = $params->Code;
$PartnerLogin = $params->PartnerLogin;

$ConfigurationEnvironment = new ConfigurationEnvironment();


/* depura variables usando un método del entorno de configuración para sanitizarlas. */
$usuario = $ConfigurationEnvironment->DepurarCaracteres($usuario);
$clave = $ConfigurationEnvironment->DepurarCaracteres($clave);
$Code = $ConfigurationEnvironment->DepurarCaracteres($Code);
$PartnerLogin = $ConfigurationEnvironment->DepurarCaracteres($PartnerLogin);

$seguir = true;

//Verificamos que el usuario y la contraseña no esten vacios

/* Valida si usuario y clave están vacíos, generando un error si es así. */
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

    /* Código que gestiona el inicio de sesión de un usuario y maneja token de autenticación. */
    $Usuario = new Usuario();


    $responseU = $Usuario->login($usuario, $clave, 0, $PartnerLogin);

    /*
$UsuarioToken = new UsuarioToken("", $responseU->user_id);

$UsuarioToken->setRequestId($json->session->sid);
$UsuarioToken->setCookie(encrypt($responseU->user_id . "#" . time()));

$UsuarioTokenMySqlDAO = new \Backend\mysql\UsuarioTokenMySqlDAO();
$UsuarioTokenMySqlDAO->update($UsuarioToken);
$UsuarioTokenMySqlDAO->getTransaction()->commit();
 */
    $response["HasError"] = false;

    /* asigna valores a un array de respuesta y verifica la seguridad de Google. */
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];
    $response["ReqTokenGoogle"] = false;

    /*
"ViewClients","ViewMenuDashBoard","ManageClients","ViewPaymentReport","ViewDepositWithdrawalReport","ViewBalance","ViewSalesReport","ViewClientTurnoverReport","ViewMenuReport",
 */

    // Verificamos si el usuario tiene activado el token de Google
    $seguridadGoogle = true;
    if ($Usuario->tokenGoogle == "A") {


        /* Verifica el código de autenticación de Google para el usuario. */
        $Google = new GoogleAuthenticator();
        $returnCodeGoogle = $Google->verifyCode($Usuario->saltGoogle, $Code);

        if ($Usuario->tokenGoogle == "A") {

        }

        // Verificamos si el usuario ingreso correctamente los datos de google

        /* verifica un código de Google y maneja errores de sesión. */
        if ($returnCodeGoogle) {

        } else {
            $_SESSION = array();
            $seguridadGoogle = false;
            $response["HasError"] = true;
            $response["AlertType"] = "danger";
            $response["AlertMessage"] = "El Codigo es incorrecto.";
            $response["ModelErrors"] = [];

        }

    } else {
        /* representa una estructura condicional "else" vacía en un lenguaje de programación. */


    }

    // Verificamos si el paso el filtro de google y asignamos la sesion
    if ($seguridadGoogle) {


        /* captura la IP del usuario para registrar su actividad. */
        $ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
        $ip = explode(",", $ip)[0];

        $UsuarioLog = new UsuarioLog();
        $UsuarioLog->setUsuarioId($Usuario->usuarioId);
        $UsuarioLog->setUsuarioIp($ip);

        /* Registro de actividad del usuario con datos de login y estado actualizado. */
        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
        $UsuarioLog->setUsuariosolicitaIp($ip);
        $UsuarioLog->setUsuarioaprobarId(0);
        $UsuarioLog->setTipo('LOGINTOKENGOOGLE');
        $UsuarioLog->setEstado("A");
        $UsuarioLog->setValorAntes($ip);

        /* Código para registrar un usuario en la base de datos utilizando un DAO. */
        $UsuarioLog->setValorDespues($Code);
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setUsumodifId(0);

        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();

        $UsuarioLogMySqlDAO->insert($UsuarioLog);


        /* Confirma una transacción y almacena datos del perfil de usuario en sesión. */
        $UsuarioLogMySqlDAO->getTransaction()->commit();

        $UsuarioPerfil = new UsuarioPerfil($_SESSION["usuario"]);

        $_SESSION["PaisCond"] = $UsuarioPerfil->pais;
        $_SESSION["Global"] = $UsuarioPerfil->global;

        /* Se configuran sesiones de usuario y mandantes según su perfil y globalidad. */
        $_SESSION["GlobalConfig"] = $UsuarioPerfil->global;
        $_SESSION["monedaReporte"] = $Usuario->monedaReporte;
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


        /* Verifica restricciones de IP y maneja errores de acceso en sesión. */
        if ($Usuario->restriccionIp == "A" && $dirIP != $Usuario->usuarioIp) {
            $_SESSION = array();
            $response["HasError"] = true;
            $response["AlertType"] = "danger";
            $response["AlertMessage"] = "No puedes acceder desde esta IP.";
        } elseif ($UsuarioPerfil->perfilId == "USUARIO") {
            /* Verifica el perfil de usuario y devuelve un mensaje de error si es "USUARIO". */

            $_SESSION = array();
            $response["HasError"] = true;
            $response["AlertType"] = "danger";
            $response["AlertMessage"] = "No existe el usuario.";

        } else {


            /* Se establece el token de autenticación y se define la lista de permisos. */
            header('Authentication: ' . $responseU->auth_token);

            $response["Data"] = array(
                "AuthenticationStatus" => 0,

                "PermissionList" => array(
                    "ViewMenuSecurity", "ViewMenuTeacher", "AdminUser", "Contingencia", "Menu", "Perfil", "PerfilOpciones", "Submenu", "UsuarioPerfil", "Clasificador", "Concesionario", "ViewAddHocReport", "ViewMenuManagement", "ActivarRegistros", "AjustarSaldo", "AsignacionCupo", "Bonos", "CuentaCobroEliminar", "GestionRed", "RegistroRapido", "ChequeReimpresion", "RecargaReversion", "GestionContacto", "ViewMenuCash", "FlujoCaja", "PagoPremio", "PagoNotaRetiro", "RecargarCredito", "ViewMenuQueries", "FlujoCajaHistorico", "FlujoCajaResumido", "InformeCasino", "InformeGerencial", "ListadoRecargasRetiros", "PremiosPendientesPagar", "ConsultaOnlineDetalle", "ConsultaOnlineResumen",

                ),
            );


            /* inicializa sesión y obtiene menús y países para un reporte. */
            $response["Sess"] = session_id();
            $menus_string = obtenerMenu();

            $paisesparamenu = obtenerPaisesReport();

            $finalProveedores = [];

            /* $Proveedor = new Proveedor();
             $Proveedor->setTipo("CASINO");

             $proveedores = $Proveedor->getProveedores();

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

             $proveedores = $Proveedor->getProveedores();

             foreach ($proveedores as $key => $value) {

                 $array = [];

                 $array["id"] = $value->getProveedorId();
                 $array["value"] = $value->getDescripcion();

                 array_push($finalProveedores, $array);

             }*/


            /* Se define un filtro para productos de tipo "CASINO" en PHP. */
            $ProductoMandante = new ProductoMandante();

            $rules = [];
            array_push($rules, array("field" => "proveedor.tipo", "data" => "CASINO ", "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");

            /* verifica el perfil de usuario y obtiene productos en formato JSON. */
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

            /* inicializa un array y contiene un bloque comentado para procesar productos. */
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


            /* Verifica el perfil del usuario y agrega reglas de concesionario a un arreglo. */
            if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
                $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

                array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION['usuario'], "op" => "eq"));
                array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            }


            /* Condiciona reglas de acceso según el perfil de usuario en una sesión. */
            if ($_SESSION["win_perfil"] == "CONCESIONARIO2") {
                $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

                array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            }


            /* verifica un perfil y agrega reglas a un array. */
            if ($_SESSION["win_perfil"] == "CONCESIONARIO3") {
                $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

                array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            }


            /* Verifica el perfil de usuario y agrega reglas a un array en PHP. */
            if ($_SESSION["win_perfil"] == "PUNTOVENTA") {
                $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

                array_push($rules, array("field" => "concesionario.usuhijo_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
                array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
            }


            /* Agrega reglas de validación basadas en perfil y país del usuario. */
            array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));


            // Si el usuario esta condicionado por País
            if ($_SESSION['PaisCond'] == "S") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
            }
            // Si el usuario esta condicionado por el mandante y no es de Global

            /* Condiciona reglas basadas en la sesión de usuario y el mandante. */
            if ($_SESSION['Global'] == "N") {
                array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
            } else {

                if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                    array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                }

            }

            // Inactivamos reportes para el país Colombia
            //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


            /* Se crea un filtro JSON para obtener puntos de venta personalizados en un sistema. */
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $jsonbetshop = json_encode($filtro);


            $PuntoVenta = new PuntoVenta();


            //$mandantes = $PuntoVenta->getPuntoVentasCustom("usuario.estado_valida,usuario.fecha_crea,usuario.moneda,usuario.fecha_ult,punto_venta.*", "punto_venta.puntoventa_id", "asc", 0, 100000, $jsonbetshop, true);

            // $mandantes = json_decode($mandantes);

            $finalBetShops = [];


            /* Verifica el país de un usuario según su perfil y condiciones de sesión. */
            $ReportCountry = $Usuario->paisId;
            if ($_SESSION["win_perfil2"] == "ADMIN" || $_SESSION["win_perfil2"] == "ADMIN2" || $_SESSION["win_perfil2"] == "OPERADOR" || $_SESSION["win_perfil2"] == "SA") {
                if ($_SESSION['PaisCond'] != "S") {
                    $ReportCountry = '0';
                }
            }


            /* Calcula saldos para recargas y juego según el perfil de usuario. */
            $SaldoRecargas = 0;
            $SaldoJuego = 0;
            if ($_SESSION["win_perfil2"] == "CONCESIONARIO" || $_SESSION["win_perfil2"] == "CONCESIONARIO2" || $_SESSION["win_perfil2"] == "CONCESIONARIO3" || $_SESSION["win_perfil2"] == "PUNTOVENTA") {
                $PuntoVenta = new PuntoVenta("", $_SESSION["usuario"]);

                $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                $SaldoJuego = $PuntoVenta->getCreditosBase();
            }


            /* Se inicializa un array vacío llamado $finalMandante. */
            $finalMandante = [];

            if ($_SESSION["GlobalConfig"] == "S") {


                /* Se crea un arreglo con información sobre un país y su valor. */
                $array = [];
                $array["id"] = "-1";
                $array["value"] = "Virtualsoft";


                $array["Countries"] = array(
                    array(
                        "id" => "",
                        "value" => "Todos"
                    )
                );


                /* Se define un filtro con condiciones para consultar datos de 'pais_mandante'. */
                $rules = [];

                array_push($rules, array("field" => "pais_mandante.mandante", "data" => $array["id"], "op" => "eq"));
                array_push($rules, array("field" => "pais_mandante.estado", "data" => "A", "op" => "eq"));


                $filtro = array("rules" => $rules, "groupOp" => "AND");

                /* convierte un filtro a JSON, consulta y decodifica datos de países. */
                $json = json_encode($filtro);

                $PaisMandante = new PaisMandante();

                $paises = $PaisMandante->getPaisMandantesCustom(" pais_mandante.*,pais.* ", "pais_mandante.paismandante_id", "asc", 0, 1000, $json, true);
                $paises = json_decode($paises);


                /* recorre países y guarda su ID y nombre en un arreglo. */
                foreach ($paises->data as $key2 => $value2) {
                    $array2 = [];
                    $array2["id"] = $value2->{"pais_mandante.pais_id"};
                    $array2["value"] = $value2->{"pais.pais_nom"};
                    array_push($array["Countries"], $array2);

                }

                /* agrega un array a `$finalMandante` y crea una instancia de `Mandante`. */
                array_push($finalMandante, $array);


                $Mandante = new Mandante();

                $rules = [];


                /* Verifica el usuario y agrega reglas si la lista de mandantes no está vacía. */
                if ($_SESSION["usuario"] != '449') {

                    if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                        array_push($rules, array("field" => "mandante.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                    }
                }
                // array_push($rules, array("field" => "proveedor.tipo", "data" => "CASINO ", "op" => "eq"));


                /* Se filtran y obtienen mandantes en formato JSON ordenados ascendentemente. */
                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $mandantes = $Mandante->getMandantes("mandante.mandante", "asc", 0, 100, $json, true);
                $mandantes = json_decode($mandantes);


                foreach ($mandantes->data as $key => $value) {

                    /* Se crea un array con información de mandante y una opción para países. */
                    $array = [];
                    $array["id"] = $value->{"mandante.mandante"};
                    $array["value"] = $value->{"mandante.descripcion"};
                    $array["Countries"] = array(
                        array(
                            "id" => "",
                            "value" => "Todos"
                        )
                    );

                    /* asigna propiedades de un objeto a un array asociativo en PHP. */
                    $array["name"] = $value->{"mandante.descripcion"};
                    $array["url"] = $value->{"mandante.base_url"};
                    $array["image"] = $value->{"mandante.logo"};
                    $array["favicon"] = $value->{"mandante.favicon"};


                    $rules = [];


                    /* Crea un filtro JSON para reglas de búsqueda con condiciones específicas. */
                    array_push($rules, array("field" => "pais_mandante.mandante", "data" => $array["id"], "op" => "eq"));
                    array_push($rules, array("field" => "pais_mandante.estado", "data" => "A", "op" => "eq"));


                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json = json_encode($filtro);


                    /* Se obtienen datos de países y se estructuran en un arreglo. */
                    $PaisMandante = new PaisMandante();

                    $paises = $PaisMandante->getPaisMandantesCustom(" pais_mandante.*,pais.* ", "pais_mandante.paismandante_id", "asc", 0, 1000, $json, true);
                    $paises = json_decode($paises);


                    foreach ($paises->data as $key2 => $value2) {
                        $array2 = [];
                        $array2["id"] = $value2->{"pais_mandante.pais_id"};
                        $array2["value"] = $value2->{"pais.pais_nom"};
                        array_push($array["Countries"], $array2);

                    }


                    /* Agrega elementos de $array al final de $finalMandante usando la función array_push. */
                    array_push($finalMandante, $array);

                }
            } else {

                /* Código crea un array con información de sesión y un país por defecto. */
                $array = [];

                $array["id"] = $_SESSION["mandante"];
                $array["value"] = $_SESSION["mandante"];

                $array["Countries"] = array(
                    array(
                        "id" => "",
                        "value" => "Todos"
                    )
                );


                /* Se crean reglas de filtrado para consultar datos de "pais_mandante". */
                $rules = [];

                array_push($rules, array("field" => "pais_mandante.mandante", "data" => $array["id"], "op" => "eq"));
                array_push($rules, array("field" => "pais_mandante.estado", "data" => "A", "op" => "eq"));


                $filtro = array("rules" => $rules, "groupOp" => "AND");

                /* Envía datos JSON y obtiene países filtrados desde la base de datos. */
                $json = json_encode($filtro);

                $PaisMandante = new PaisMandante();

                $paises = $PaisMandante->getPaisMandantesCustom(" pais_mandante.*,pais.* ", "pais_mandante.paismandante_id", "asc", 0, 1000, $json, true);
                $paises = json_decode($paises);


                /* recorre países y crea un arreglo con sus IDs y nombres. */
                foreach ($paises->data as $key2 => $value2) {
                    $array2 = [];
                    $array2["id"] = $value2->{"pais_mandante.pais_id"};
                    $array2["value"] = $value2->{"pais.pais_nom"};
                    array_push($array["Countries"], $array2);

                }
            }


            $response["Data"] = array(

                "AuthenticationStatus" => 0,
                "SaldoRecargas" => $SaldoRecargas,
                "SaldoJuego" => $SaldoJuego,


                "PermissionList" => $menus_string,
                "Countries" => $paisesparamenu,
                "ProvidersCasino" => $finalProveedores,
                "BetShops" => $finalBetShops,

                "Partners" => $finalMandante,
                "GamesCasino" => $finalProductos,
                "PartnerSelected" => $_SESSION["mandante"],

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
