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
use Backend\dto\Template;
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
use Backend\dto\UsuarioSitebuilder;
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
 * Inicio de sesión de un usuario en la plataforma, con usuario y contraseña
 *
 * @param string $grecaptcharesponse La respuesta del reCAPTCHA obtenido del cliente.
 * @param string $usuario nombre del usuario
 * @param string $clave clave de logueo para el sitio
 * @param int $PartnerLogin id del patner a loguearse
 * @param boolean $Sitebuilder Si el logueo es por la plataforma de sitebuilder
 *
 * El objeto $response es un array con los siguientes atributos:
 * - *HasError* (bool): Indica si hubo un error durante el proceso.
 * - *AlertType* (string): Tipo de alerta que se mostrará.
 * - *AlertMessage* (string): Mensaje de alerta generado.
 * - *ModelErrors* (array): Errores del modelo, si los hubiera.
 * - *Data* (array): Contiene la información del estado de autenticación y los menus del usuario.
 *
 *   Ejemplo de respuesta en caso de error:
 *
 *   $response["HasError"] = true;
 *   $response["AlertType"] = "danger";
 *   $response["AlertMessage"] = "Invalid Username and/or password ('.$e->getCode().')";
 *   $response["ModelErrors"] = [];
 *
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */

/* Registro de eventos de inicio de sesión y validación de reCAPTCHA de Google. */
syslog(LOG_WARNING, "LOGINLOGIN:" . $_SERVER["REQUEST_URI"]);
syslog(LOG_WARNING, "LOGINGENERALLOGIN:" . $_SERVER["REQUEST_URI"]);

/**
 * Verifica la validez del reCAPTCHA de Google
 *
 * Esta función envía una solicitud al servicio de verificación de reCAPTCHA de Google
 * y retorna verdadero si la respuesta es válida, o falso en caso contrario.
 *
 * @param string $grecaptcharesponse La respuesta del reCAPTCHA obtenido del cliente.
 *
 * @return bool|null Retorna verdadero si es válido, falso si no lo es, o null si ocurrió un error.
 */
function isValid($grecaptcharesponse)
{

    /* Verifica el resultado de reCAPTCHA enviando una solicitud POST a Google. */
    try {

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
        /* maneja excepciones y devuelve null si ocurre un error. */

        return null;
    }
}

//throw new Exception("No puede iniciar sesion en el sitio. ", "30010");


/* valida una respuesta de reCAPTCHA antes de procesar datos. */
$SkeepRows = 0;
$MaxRows = 100000;

$GrecaptchaResponse = $params->GrecaptchaResponse;

if ($GrecaptchaResponse != 'undefined') {
    if (isValid($GrecaptchaResponse)) {
        throw new Exception("Inusual Detected", "11");
    }
}

// Obtenemos el usuario y la clave a validar

/* asigna valores de parámetros a variables para su posterior uso. */
$usuario = $params->Username;
$clave = $params->Password;
$PartnerLogin = $params->PartnerLogin;


$Sitebuilder = $params->Sitebuilder;
//Obtenemos la dirección IP del usuario

/* obtiene la dirección IP del cliente y valida usuarios permitidos. */
$dirIP = str_replace(' ', '', explode(",", (new ConfigurationEnvironment())->get_client_ip())[0]);

$seguir = true;


if ($usuario != 'juliandev' && $usuario != 'pventaperu' && $usuario != 'juan.algarin@virtualsoft.tech') {
    //throw new Exception('We are currently in the process of maintaining the site.', 30004);

    //  throw new Exception("No puede iniciar sesion en el sitio. ", "30010");

}


/* depura caracteres de variables utilizando un método de configuración. */
$ConfigurationEnvironment = new ConfigurationEnvironment();

$usuario = $ConfigurationEnvironment->DepurarCaracteres($usuario);
$clave = $ConfigurationEnvironment->DepurarCaracteres($clave);
$PartnerLogin = $ConfigurationEnvironment->DepurarCaracteres($PartnerLogin);

//Verificamos que el usuario y la contraseña no esten vacios

if ($clave == "" || $usuario == "") {

    /* verifica el usuario y registra un aviso en el sistema. */
    $usuario = $params->username;
    $clave = $params->password;

    if ($usuario == 'SUBAGENTTEST') {
        syslog(LOG_WARNING, "LOGINLOGINSOSP :" . json_encode($_SERVER) . json_encode($_REQUEST));

    }

    /* valida un login y registra información en un log. */
    if ($clave == 'Ecuabet2022') {
        syslog(LOG_WARNING, "LOGINLOGINSOSP :" . json_encode($_SERVER) . json_encode($_REQUEST));

    }

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

    /* Autentica un usuario en Sitebuilder y obtiene información del mandante. */
    if ($Sitebuilder === true) {
        $UsuarioSitebuilder = new UsuarioSitebuilder();
        $auth = $UsuarioSitebuilder->login($usuario, $clave);
        $UsuarioS = new Usuario($UsuarioSitebuilder->getUsuarioId());
        $PartnerLogin = $UsuarioS->mandante;
    }

    /* intenta iniciar sesión de un usuario con validaciones comentadas. */
    $Usuario = new Usuario();

    /*if(in_array($usuario,array('ADMINMIRAVALLE','OPERMIRAVALLE','FINMIRAVALLE')) ){
        if($PartnerLogin != 3){
            throw new Exception("No existe ", "30002");
        }
        $PartnerLogin=0;
    }*/

    $responseU = $Usuario->login($usuario, $clave, 0, $PartnerLogin, '');


    /*
$UsuarioToken = new UsuarioToken("", $responseU->user_id);

$UsuarioToken->setRequestId($json->session->sid);
$UsuarioToken->setCookie(encrypt($responseU->user_id . "#" . time()));

$UsuarioTokenMySqlDAO = new \Backend\mysql\UsuarioTokenMySqlDAO();
$UsuarioTokenMySqlDAO->update($UsuarioToken);
$UsuarioTokenMySqlDAO->getTransaction()->commit();
 */

    // Si todo esta correcto, respondemos positivamente


    /* Se inicializa una respuesta de usuario sin errores y se asigna un perfil. */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];
    $response["ReqTokenGoogle"] = false;

    /*
"ViewClients","ViewMenuDashBoard","ManageClients","ViewPaymentReport","ViewDepositWithdrawalReport","ViewBalance","ViewSalesReport","ViewClientTurnoverReport","ViewMenuReport",
 */
    $UsuarioPerfil = new UsuarioPerfil($_SESSION["usuario"]);


    /* almacena información del perfil del usuario en variables de sesión. */
    $_SESSION["PaisCond"] = $UsuarioPerfil->pais;
    $_SESSION["Global"] = $UsuarioPerfil->global;
    $_SESSION["GlobalConfig"] = $UsuarioPerfil->global;
    $_SESSION["monedaReporte"] = $Usuario->monedaReporte;
    $_SESSION['mandante'] = $UsuarioPerfil->mandante;
    $_SESSION['mandanteLista'] = $UsuarioPerfil->mandanteLista;

    /* Se almacena el ID del usuario en la sesión para su uso posterior. */
    $_SESSION["iduser"] = $Usuario->usuarioId;


    if ($UsuarioPerfil->perfilId == "PUNTOVENTA") {

        try {

            /* Crea un clasificador y recupera su ID, luego inicializa un mandante. */
            $Clasificador = new Clasificador("", "INICIOSES");
            $ClasificadorId = $Clasificador->getClasificadorId();

            $Mandante = $_SESSION['mandante'];

            $Mandante = new Mandante($Mandante);

            /* Se asignan valores sobre el mandante y el país, creando una variable de mensaje. */
            $nombreMandante = $Mandante->nombre;

            $Pais = new Pais($Usuario->paisId);
            $PaisNombre = $Pais->paisNom;

            $mensaje_txt = "";


            /* genera un mensaje personalizado utilizando un template y datos del usuario. */
            $Template = new Template("", $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, $Usuario->idioma);

            $mensaje_txt .= $Template->templateHtml;

            $mensaje_txt = str_replace("#NamePv#", $Usuario->nombre, $mensaje_txt);
            $mensaje_txt = str_replace("#Email#", $Usuario->login, $mensaje_txt);

            /* Se reemplazan variables en un mensaje y se envía un correo. */
            $mensaje_txt = str_replace("#Country#", $PaisNombre, $mensaje_txt);
            $mensaje_txt = str_replace("#Partner#", $nombreMandante, $mensaje_txt);


            $ConfigurationEnviroment = new ConfigurationEnvironment();
            $envio = $ConfigurationEnviroment->EnviarCorreoVersion3($Usuario->login, 'noreply@doradobet.com', 'Doradobet', "Inicio de sesion por punto de venta", 'mail_registro.php', "", $mensaje_txt, 0, '', '', $Usuario->mandante);


        } catch (Exception $e) {
            /* Bloque que captura excepciones en PHP para manejar errores sin interrumpir la ejecución. */


        }
    }


    /* gestiona sesiones y permisos de acceso basados en condiciones específicas. */
    if ($_SESSION["Global"] == "S") {
        $arrayMandante = explode(',', $_SESSION['mandanteLista']);
        if ($UsuarioPerfil->globalMandante != "-1") {
            $_SESSION["Global"] = "N";
            $_SESSION['mandante'] = $UsuarioPerfil->globalMandante;
        } else {
            $_SESSION['mandante'] = '-1';

        }
        if (!in_array($UsuarioPerfil->globalMandante, $arrayMandante)) {
            $_SESSION["Global"] = "S";
            $_SESSION['mandante'] = '-1';

        }
    }


    /* Verifica restricciones de acceso por IP para un usuario en un punto de venta. */
    if ($Usuario->puntoventaId != '' && $Usuario->puntoventaId != 0) {

        $UsuarioPuntoVenta = new Usuario($Usuario->puntoventaId);
        if ($UsuarioPuntoVenta->restriccionIp == "A" && $dirIP != $UsuarioPuntoVenta->usuarioIp) {
            $_SESSION = array();
            $response["HasError"] = true;
            $response["AlertType"] = "danger";
            $response["AlertMessage"] = "No puedes acceder desde esta IP.";
        }
    }


    /* Validación de acceso IP: restringe según configuración del usuario y muestra alerta. */
    $sitebuilderTempValidation = $Sitebuilder;

    if ($Usuario->restriccionIp == "A" && $dirIP != $Usuario->usuarioIp) {
        $_SESSION = array();
        $response["HasError"] = true;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = "No puedes acceder desde esta IP.";
    } elseif ($Usuario->tokenGoogle == "A" && !$sitebuilderTempValidation) {
        /* Verifica condiciones del usuario y limpia la sesión si se cumplen. */

        $_SESSION = array();
        $response["ReqTokenGoogle"] = true;
    } elseif ($UsuarioPerfil->perfilId == "USUARIO") {
        /* Verifica el perfil de usuario y maneja errores en la sesión. */

        $_SESSION = array();
        $response["HasError"] = true;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = "No existe el usuario.";

    } else {

        /* gestiona la autenticación y establece el país de un usuario. */
        header('Authentication: ' . $responseU->auth_token);

        $ReportCountry = $Usuario->paisId;
        if ($_SESSION["win_perfil2"] == "ADMIN" || $_SESSION["win_perfil2"] == "ADMIN2" || $_SESSION["win_perfil2"] == "OPERADOR" || $_SESSION["win_perfil2"] == "SA") {
            if ($_SESSION['PaisCond'] != "S") {
                $ReportCountry = '0';
            }
        }


        /* verifica el perfil del usuario y obtiene saldos de recarga y juego. */
        $SaldoRecargas = 0;
        $SaldoJuego = 0;
        if ($_SESSION["win_perfil2"] == "CONCESIONARIO" || $_SESSION["win_perfil2"] == "CONCESIONARIO2" || $_SESSION["win_perfil2"] == "CONCESIONARIO3" || $_SESSION["win_perfil2"] == "PUNTOVENTA") {
            $PuntoVenta = new PuntoVenta("", $_SESSION["usuario"]);

            $SaldoRecargas = $PuntoVenta->getCupoRecarga();
            $SaldoJuego = $PuntoVenta->getCreditosBase();
        }


        /* establece cookies para diferentes direcciones IP de servidor. */
        if ($_SERVER["SERVER_ADDR"] == "192.168.207.80") {

            //setcookie("AdminID", "admin1");

        }

        if ($_SERVER["SERVER_ADDR"] == "192.168.173.105") {

            //setcookie("AdminID", "admin2");

        }


        /* Se crea una respuesta estructurada con datos de autenticación, saldo y permisos. */
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


        /* obtiene sesión, menús y países, luego crea un objeto "Proveedor". */
        $response["Sess"] = session_id();
        $menus_string = obtenerMenu();
        $paisesparamenu = obtenerPaisesReport();


        $Proveedor = new Proveedor();

        /* establece un tipo para el proveedor y obtiene una lista de proveedores. */
        $Proveedor->setTipo("CASINO");

        $proveedores = $Proveedor->getProveedores();

        $finalProveedores = [];
        $array = [];

        /* crea un array con proveedores y sus descripciones. */
        $array["id"] = '0';
        $array["value"] = 'Todos';
        array_push($finalProveedores, $array);

        foreach ($proveedores as $key => $value) {

            $array = [];

            $array["id"] = $value->getProveedorId();
            $array["value"] = $value->getDescripcion();

            array_push($finalProveedores, $array);

        }


        /* Crea un objeto Proveedor, obtiene datos y los almacena en un arreglo. */
        $Proveedor = new Proveedor();
        $Proveedor->setTipo("LIVECASINO");

        $proveedores = $Proveedor->getProveedores();

        foreach ($proveedores as $key => $value) {

            $array = [];

            $array["id"] = $value->getProveedorId();
            $array["value"] = $value->getDescripcion();

            array_push($finalProveedores, $array);

        }


        /* Se define un filtro para validar si el tipo de proveedor es "CASINO". */
        $ProductoMandante = new ProductoMandante();

        $rules = [];
        array_push($rules, array("field" => "proveedor.tipo", "data" => "CASINO ", "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");

        /* Condicional que verifica permisos y obtiene productos según la configuración de sesión. */
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

        /* Inicializa un arreglo vacío llamado `$finalProductos` para almacenar productos. */
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


        /* verifica parámetros de solicitud y establece reglas para un concesionario. */
        if ($_REQUEST["debugFixed2"] == '1') {
            print_r(' TIME3 ');
            print_r(time());
        }
        $rules = [];

        if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
            $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

            array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION['usuario'], "op" => "eq"));
            array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
        }


        /* Validación de sesión y configuración de reglas para concesionario en PHP. */
        if ($_SESSION["win_perfil"] == "CONCESIONARIO2") {
            $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

            array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
            array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
        }


        /* Valida si el perfil de sesión es "CONCESIONARIO3" y añade reglas a un array. */
        if ($_SESSION["win_perfil"] == "CONCESIONARIO3") {
            $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

            array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
            array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
        }


        /* verifica el perfil de usuario y añade reglas de filtro a un arreglo. */
        if ($_SESSION["win_perfil"] == "PUNTOVENTA") {
            $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

            array_push($rules, array("field" => "concesionario.usuhijo_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
            array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
        }


        /* Agrega reglas de validación basadas en perfil y condición de país. */
        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));


        // Si el usuario esta condicionado por País
        if ($_SESSION['PaisCond'] == "S") {
            array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
        }
        // Si el usuario esta condicionado por el mandante y no es de Global

        /* Se añaden reglas de filtrado a un array según condiciones específicas de sesión. */
        if ($_SESSION['Global'] == "N") {
            array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
        }
        // Inactivamos reportes para el país Colombia
        //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");

        /* convierte un filtro a JSON y obtiene datos de 'PuntoVenta'. */
        $jsonbetshop = json_encode($filtro);


        $PuntoVenta = new PuntoVenta();


        $mandantes = $PuntoVenta->getPuntoVentasCustom("usuario.estado_valida,usuario.fecha_crea,usuario.moneda,usuario.fecha_ult,punto_venta.*", "punto_venta.puntoventa_id", "asc", 0, 100000, $jsonbetshop, true);


        /* Convierte datos JSON a un arreglo con IDs y descripciones de puntos de venta. */
        $mandantes = json_decode($mandantes);

        $finalBetShops = [];

        foreach ($mandantes->data as $key => $value) {

            $array = [];

            $array["id"] = $value->{"punto_venta.usuario_id"};
            $array["value"] = $value->{"punto_venta.descripcion"};

            array_push($finalBetShops, $array);

        }


        /* Verifica el perfil del usuario y ajusta el país del reporte según condiciones. */
        $ReportCountry = $Usuario->paisId;
        if ($_SESSION["win_perfil2"] == "ADMIN" || $_SESSION["win_perfil2"] == "ADMIN2" || $_SESSION["win_perfil2"] == "OPERADOR" || $_SESSION["win_perfil2"] == "SA") {
            if ($_SESSION['PaisCond'] != "S") {
                $ReportCountry = '0';
            }
        }


        /* Calcula saldo de recargas y juego según el perfil de usuario en sesión. */
        $SaldoRecargas = 0;
        $SaldoJuego = 0;
        if ($_SESSION["win_perfil2"] == "CONCESIONARIO" || $_SESSION["win_perfil2"] == "CONCESIONARIO2" || $_SESSION["win_perfil2"] == "CONCESIONARIO3" || $_SESSION["win_perfil2"] == "PUNTOVENTA") {
            $PuntoVenta = new PuntoVenta("", $_SESSION["usuario"]);

            $SaldoRecargas = $PuntoVenta->getCupoRecarga();
            $SaldoJuego = $PuntoVenta->getCreditosBase();
        }


        /* Inicializa un array vacío llamado $finalMandante para almacenar valores posteriormente. */
        $finalMandante = [];

        if ($_SESSION["GlobalConfig"] == "S") {


            /* Se crea un array con información de una empresa y países asociados. */
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


            /* Se añade un array a $finalMandante y se define un filtro de reglas. */
            array_push($finalMandante, $array);

            $Mandante = new Mandante();

            $rules = [];
            // array_push($rules, array("field" => "proveedor.tipo", "data" => "CASINO ", "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");

            /* convierte un filtro a JSON y obtiene mandantes ordenados y decodificados. */
            $json = json_encode($filtro);

            $mandantes = $Mandante->getMandantes("mandante.mandante", "asc", 0, 100, $json, true);
            $mandantes = json_decode($mandantes);


            foreach ($mandantes->data as $key => $value) {


                /* Código PHP que crea un array con información de "mandante". */
                $array = [];
                $array["id"] = $value->{"mandante.mandante"};
                $array["value"] = $value->{"mandante.descripcion"};


                $array["name"] = $value->{"mandante.descripcion"};

                /* asigna valores de URL, imagen y favicon a un array. */
                $array["url"] = $value->{"mandante.base_url"};
                $array["image"] = $value->{"mandante.logo"};
                $array["favicon"] = $value->{"mandante.favicon"};

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

                /* Agrega el contenido de `$array` al final del array `$finalMandante`. */
                array_push($finalMandante, $array);

            }
        } else {
            /* crea un array con información de sesión y países, luego lo agrega a otro array. */

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


        /* Genera una respuesta estructurada con datos sobre países, apuestas y configuraciones de usuario. */
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
    include 'LogoutRelationshipUser.php';
}
