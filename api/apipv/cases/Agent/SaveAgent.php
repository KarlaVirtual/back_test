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
 * Agent/SaveAgent
 *
 * Guardar un agente
 *
 * @param object $params Objeto que contiene los parámetros necesarios para guardar un agente.
 * @param string $params ->Address Dirección del agente.
 * @param int $params ->CityId ID de la ciudad del agente.
 * @param int $params ->CountryId ID del país del agente.
 * @param int $params ->CurrencyId ID de la moneda del agente.
 * @param string $params ->PreferredLanguage Idioma preferido del agente.
 * @param string $params ->DocumentLegalID ID legal del documento del agente.
 * @param string $params ->Email Correo electrónico del agente.
 * @param int $params ->GroupId ID del grupo del agente.
 * @param string $params ->IP Dirección IP del agente.
 * @param float $params ->Latitud Latitud de la ubicación del agente.
 * @param float $params ->Longitud Longitud de la ubicación del agente.
 * @param string $params ->ManagerDocument Documento del gerente del agente.
 * @param string $params ->ContactName Nombre del contacto del agente.
 * @param string $params ->ManagerPhone Teléfono del gerente del agente.
 * @param string $params ->MobilePhone Teléfono móvil del agente.
 * @param string $params ->Name Nombre del agente.
 * @param string $params ->LoginAccess Login de acceso del agente.
 * @param string $params ->Phone Teléfono del agente.
 * @param int $params ->RegionId ID de la región del agente.
 * @param string $params ->Partner Socio del agente.
 * @param string $params ->District Distrito del agente.
 * @param int $params ->Type Tipo de agente.
 * @param string $params ->RepresentLegalDocument Documento legal del representante del agente.
 * @param string $params ->RepresentLegalName Nombre legal del representante del agente.
 * @param string $params ->RepresentLegalPhone Teléfono legal del representante del agente.
 * @param string $params ->UserName Nombre de usuario del agente.
 * @param string $params ->Concessionaire Concesionario del agente.
 * @param string $params ->Subconcessionaire Subconcesionario del agente.
 * @param string $params ->Subconcessionaire2 Segundo subconcesionario del agente.
 * @param string $params ->Password Contraseña del agente.
 * @param string $params ->CanReceipt Permiso para recibir.
 * @param string $params ->CanDeposit Permiso para depositar.
 * @param string $params ->CanActivateRegister Permiso para activar registro.
 * @param string $params ->Lockedsales Ventas bloqueadas.
 * @param string $params ->Pinagent Pin del agente.
 *
 * @return array $response Respuesta de la operación.
 * @return bool $response["HasError"] Indica si hubo un error.
 * @return string $response["AlertType"] Tipo de alerta.
 * @return string $response["AlertMessage"] Mensaje de alerta.
 * @return array $response["ModelErrors"] Errores del modelo.
 * @return int $response["id"] ID del agente creado.
 *
 * @throws Exception Si el usuario SUB no pertenece a la red.
 */


/* asigna valores de parámetros a variables correspondientes en un script. */
$Address = $params->Address;
$CityId = $params->CityId;
$CountryId = $params->CountryId;
$CurrencyId = $params->CurrencyId;
$PreferredLanguage = $params->PreferredLanguage;

$DocumentLegalID = $params->DocumentLegalID;

/* asigna valores de parámetros a variables para su posterior uso. */
$Email = $params->Email;
$GroupId = $params->GroupId;
$IP = $params->IP;
$Latitud = $params->Latitud;
$Longitud = $params->Longitud;
$ManagerDocument = $params->ManagerDocument;

/* Asigna valores de $params a variables para gestionar información de contacto. */
$ManagerName = $params->ContactName;
$ManagerPhone = $params->ManagerPhone;
$MobilePhone = $params->MobilePhone;
$Name = $params->Name;
$Login = $params->LoginAccess;
$Phone = $params->Phone;

/* asigna valores de parámetros a variables específicas. */
$RegionId = $params->RegionId;
$Partner = $params->Partner;

$District = $params->District;

$Type = $params->Type;

/* determina el tipo de usuario basado en su perfil en sesión. */
$tipoUsuario = "";
$seguir = true;

if ($Type == 0) {
    if ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
        $tipoUsuario = 'CONCESIONARIO2';
    } elseif ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
        $tipoUsuario = 'CONCESIONARIO3';
    } elseif ($_SESSION["win_perfil2"] != "CONCESIONARIO3" && $_SESSION["win_perfil2"] != "PUNTOVENTA") {
        $tipoUsuario = 'CONCESIONARIO';

    } else {
        $seguir = false;
    }
}


/* asigna 'AFILIADOR' a $tipoUsuario si $Type es 1 y extrae datos legales. */
if ($Type == 1) {
    $tipoUsuario = 'AFILIADOR';
}

$RepresentLegalDocument = $params->RepresentLegalDocument;
$RepresentLegalName = $params->RepresentLegalName;

/* Asignación de variables a partir de parámetros y campos previamente definidos. */
$RepresentLegalPhone = $params->RepresentLegalPhone;


$Address = $Address;
$CurrencyId = $CurrencyId;
$Email = $Email;

/* Código inicializa variables para un usuario, incluyendo nombre, ID y estado de suspensión. */
$FirstName = $Name;
$Id = $params->Id;
$IsSuspended = false;
$LastLoginIp = "";
$LastLoginLocalDate = "";
$LastName = "";

/* Inicializa variables vacías y asigna valores de un objeto $params. */
$clave = '';
$SystemName = '';
$UserId = '';
$UserName = $params->UserName;
$Phone = $ManagerPhone;


$Concessionaire = $params->Concessionaire;

/* asigna parámetros y verifica condiciones antes de ejecutar acciones. */
$Subconcessionaire = $params->Subconcessionaire;
$Subconcessionaire2 = $params->Subconcessionaire2;


if ($Id != "" && $UserId != "" && $seguir) {

} elseif ($seguir) {

    /* Asigna valores a variables y normaliza permisos de acción como "S" o "N". */
    $login = $Login;
    $Password = $params->Password;

    $CanReceipt = ($CanReceipt == "S") ? "S" : "N";
    $CanDeposit = ($CanDeposit == "S") ? "S" : "N";
    $CanActivateRegister = ($CanActivateRegister == "S") ? "S" : "N";

    /* Se asignan valores a variables según condiciones y se inicializa un contador. */
    $Lockedsales = ($Lockedsales == "S") ? "S" : "N";
    $Pinagent = ($Pinagent == "S") ? "S" : "N";

    $Pinagent = 'N';

    $consecutivo_usuario = 0;
    /*$Consecutivo = new Consecutivo("", "USU", "");

        $consecutivo_usuario = $Consecutivo->numero;

        $consecutivo_usuario++;

        $ConsecutivoMySqlDAO = new ConsecutivoMySqlDAO();

        $Consecutivo->setNumero($consecutivo_usuario);


        $ConsecutivoMySqlDAO->update($Consecutivo);

        $ConsecutivoMySqlDAO->getTransaction()->commit();*/


    /* Se crea un objeto de PuntoVenta y un objeto de Usuario con un login específico. */
    $PuntoVenta = new PuntoVenta($CashDeskId);


    $Usuario = new Usuario();


    //$Usuario->usuarioId = $consecutivo_usuario;

    $Usuario->login = $login;


    /* Asigna valores a propiedades del objeto Usuario en PHP. */
    $Usuario->nombre = $FirstName;

    $Usuario->estado = 'A';

    $Usuario->fechaUlt = date('Y-m-d H:i:s');

    $Usuario->claveTv = '';


    /* Código que inicializa propiedades de un objeto Usuario, indicando estado y número de intentos. */
    $Usuario->estadoAnt = 'I';

    $Usuario->intentos = 0;

    $Usuario->estadoEsp = 'A';

    $Usuario->observ = '';


    /* Código para inicializar propiedades de un objeto Usuario en PHP. */
    $Usuario->dirIp = '';

    $Usuario->eliminado = 'N';

    $Usuario->mandante = $Partner;

    $Usuario->usucreaId = '0';


    /* Se inicializan propiedades de un usuario y se genera un token de identificación. */
    $Usuario->usumodifId = '0';

    $Usuario->claveCasino = '';
    $token_itainment = (new ConfigurationEnvironment())->GenerarClaveTicket2(12);

    $Usuario->tokenItainment = $token_itainment;


    /* Variables de un objeto Usuario inicializadas como cadenas vacías para datos relacionados. */
    $Usuario->fechaClave = '';

    $Usuario->retirado = '';

    $Usuario->fechaRetiro = '';

    $Usuario->horaRetiro = '';


    /* Asignación de propiedades a un objeto Usuario, incluyendo estado y bloqueo de ventas. */
    $Usuario->usuretiroId = '0';

    $Usuario->bloqueoVentas = $Lockedsales;

    $Usuario->infoEquipo = '';

    $Usuario->estadoJugador = 'AC';


    /* Código que inicializa propiedades del usuario: token, patrocinador, verificación de correo y país. */
    $Usuario->tokenCasino = '';

    $Usuario->sponsorId = 0;

    $Usuario->verifCorreo = 'N';


    $Usuario->paisId = $CountryId;


    /* Asignación de propiedades en un objeto Usuario: moneda, idioma y permisos. */
    $Usuario->moneda = $CurrencyId;

    $Usuario->idioma = $PreferredLanguage;

    $Usuario->permiteActivareg = $CanActivateRegister;

    $Usuario->test = 'N';


    /* Se configuran parámetros de usuario como límite de depósito y zona horaria. */
    $Usuario->tiempoLimitedeposito = '0';

    $Usuario->tiempoAutoexclusion = '0';

    $Usuario->cambiosAprobacion = 'S';

    $Usuario->timezone = '-5';


    /* Asignación de valores a propiedades del objeto Usuario, incluyendo fechas y origen. */
    $Usuario->puntoventaId = $consecutivo_usuario;

    $Usuario->fechaCrea = date('Y-m-d H:i:s');

    $Usuario->origen = 0;

    $Usuario->fechaActualizacion = $Usuario->fechaCrea;

    /* Asignación de valores a propiedades del objeto $Usuario relacionadas con la validación de documentos. */
    $Usuario->documentoValidado = "A";
    $Usuario->fechaDocvalido = $Usuario->fechaCrea;
    $Usuario->usuDocvalido = 0;


    $Usuario->estadoValida = 'N';

    /* inicializa propiedades de un objeto Usuario con valores específicos. */
    $Usuario->usuvalidaId = 0;
    $Usuario->fechaValida = date('Y-m-d H:i:s');
    $Usuario->contingencia = 'I';
    $Usuario->contingenciaDeportes = 'I';
    $Usuario->contingenciaCasino = 'I';
    $Usuario->contingenciaCasvivo = 'I';

    /* Asigna valores de contingencia y ubicación a un objeto Usuario. */
    $Usuario->contingenciaVirtuales = 'I';
    $Usuario->contingenciaPoker = 'I';
    $Usuario->restriccionIp = 'I';
    $Usuario->ubicacionLongitud = $Longitud;
    $Usuario->ubicacionLatitud = $Latitud;
    $Usuario->usuarioIp = $IP;

    /* inserta un objeto de usuario con tokens en la base de datos. */
    $Usuario->tokenGoogle = "I";
    $Usuario->tokenLocal = "I";
    $Usuario->saltGoogle = '';
    $UsuarioMySqlDAO = new UsuarioMySqlDAO();

    $UsuarioMySqlDAO->insert($Usuario);

    /* Se inicializa la configuración de usuario con permisos y propiedades específicas. */
    $consecutivo_usuario = $Usuario->usuarioId;

    $UsuarioConfig = new UsuarioConfig();
    $UsuarioConfig->permiteRecarga = $CanDeposit;
    $UsuarioConfig->pinagent = $Pinagent;
    $UsuarioConfig->reciboCaja = $CanReceipt;

    /* Se asignan valores a la configuración de usuario y se crea un objeto concesionario. */
    $UsuarioConfig->mandante = $Partner;
    $UsuarioConfig->usuarioId = $consecutivo_usuario;


    $Concesionario = new Concesionario();

    if ($_SESSION["win_perfil"] == "CONCESIONARIO") {

        /* Verifica si $Subconcessionaire está vacío, crea objeto y valida usuario. */
        if ($Subconcessionaire == '') {
            $Subconcessionaire = '0';
        } else {
            $Concesionario = new Concesionario($Subconcessionaire, '0');

            if ($Concesionario->usupadreId != $_SESSION["usuario"]) {
                throw new Exception("Error General. " . "Usuario SUB no pertenece a la red", "100000");

            }

            $tipoUsuario = 'CONCESIONARIO3';
        }


        /* Crea un nuevo objeto UsuarioPerfil y asigna propiedades relacionadas al usuario. */
        $UsuarioPerfil = new UsuarioPerfil();
        $UsuarioPerfil->usuarioId = $consecutivo_usuario;

        $UsuarioPerfil->perfilId = $tipoUsuario;
        $UsuarioPerfil->mandante = $Partner;
        $UsuarioPerfil->pais = 'S';

        /* Establece el perfil global del usuario y asigna un concesionario a su ID. */
        $UsuarioPerfil->global = 'N';


        // $ConcesionarioU = new Concesionario($_SESSION["usuario"], 0);

        $Concesionario->setUsupadreId($_SESSION["usuario"]);

        /* Configuración de identificadores y porcentajes para un concesionario específico. */
        $Concesionario->setUsuhijoId($consecutivo_usuario);
        $Concesionario->setusupadre2Id($Subconcessionaire);
        $Concesionario->setusupadre3Id(0);
        $Concesionario->setusupadre4Id(0);
        $Concesionario->setPorcenhijo(0);
        $Concesionario->setPorcenpadre1(0);

        /* Configuración de propiedades del objeto "Concesionario" con valores iniciales específicos. */
        $Concesionario->setPorcenpadre2(0);
        $Concesionario->setPorcenpadre3(0);
        $Concesionario->setPorcenpadre4(0);
        $Concesionario->setProdinternoId(0);
        $Concesionario->setMandante($Partner);
        $Concesionario->setUsucreaId(0);

        /* Se establece el ID de usuario y el estado del concesionario como activo. */
        $Concesionario->setUsumodifId(0);
        $Concesionario->setEstado("A");
    } elseif ($_SESSION["win_perfil"] == "CONCESIONARIO2") {

        /* Creación de objeto UsuarioPerfil con atributos asignados para un usuario específico. */
        $UsuarioPerfil = new UsuarioPerfil();
        $UsuarioPerfil->usuarioId = $consecutivo_usuario;
        $UsuarioPerfil->perfilId = $tipoUsuario;
        $UsuarioPerfil->mandante = $Partner;
        $UsuarioPerfil->pais = 'S';
        $UsuarioPerfil->global = 'N';


        /* inicializa un objeto Concesionario y establece relaciones de usuario. */
        $ConcesionarioU = new Concesionario($_SESSION["usuario"], 0);

        $Concesionario->setUsupadreId($ConcesionarioU->getUsupadreId());
        $Concesionario->setUsuhijoId($consecutivo_usuario);
        $Concesionario->setusupadre2Id($_SESSION["usuario"]);
        $Concesionario->setusupadre3Id(0);

        /* inicializa valores de porcentaje y relación para un concesionario. */
        $Concesionario->setusupadre4Id(0);
        $Concesionario->setPorcenhijo(0);
        $Concesionario->setPorcenpadre1(0);
        $Concesionario->setPorcenpadre2(0);
        $Concesionario->setPorcenpadre3(0);
        $Concesionario->setPorcenpadre4(0);

        /* establece propiedades de un objeto "Concesionario" con valores específicos. */
        $Concesionario->setProdinternoId(0);
        $Concesionario->setMandante($Partner);
        $Concesionario->setUsucreaId(0);
        $Concesionario->setUsumodifId(0);
        $Concesionario->setEstado("A");
    } else {


        /* Asigna '0' a $Concessionaire si está vacío; de lo contrario, establece $tipoUsuario. */
        if ($Concessionaire == '') {
            $Concessionaire = '0';
        } else {
            $tipoUsuario = 'CONCESIONARIO2';

        }


        /* asigna '0' a $Subconcessionaire si está vacío; define tipoUsuario como 'CONCESIONARIO3' si no. */
        if ($Subconcessionaire == '') {
            $Subconcessionaire = '0';
        } else {
            $tipoUsuario = 'CONCESIONARIO3';
        }
        $UsuarioPerfil = new UsuarioPerfil();

        /* Asigna propiedades a un objeto de perfil de usuario y establece el ID del concesionario. */
        $UsuarioPerfil->usuarioId = $consecutivo_usuario;
        $UsuarioPerfil->perfilId = $tipoUsuario;
        $UsuarioPerfil->mandante = $Partner;
        $UsuarioPerfil->pais = 'S';
        $UsuarioPerfil->global = 'N';

        $Concesionario->setUsupadreId($Concessionaire);

        /* Se están configurando varios IDs y porcentajes en un objeto "Concesionario". */
        $Concesionario->setUsuhijoId($consecutivo_usuario);
        $Concesionario->setusupadre2Id($Subconcessionaire);
        $Concesionario->setusupadre3Id(0);
        $Concesionario->setusupadre4Id(0);
        $Concesionario->setPorcenhijo(0);
        $Concesionario->setPorcenpadre1(0);

        /* Configura valores iniciales y asigna variables en un objeto de concesionario. */
        $Concesionario->setPorcenpadre2(0);
        $Concesionario->setPorcenpadre3(0);
        $Concesionario->setPorcenpadre4(0);
        $Concesionario->setProdinternoId(0);
        $Concesionario->setMandante($Partner);
        $Concesionario->setUsucreaId(0);

        /* establece el ID de modificación y el estado del concesionario a activo. */
        $Concesionario->setUsumodifId(0);
        $Concesionario->setEstado("A");
    }


    /* Se crea un objeto UsuarioPremiomax y se inicializan sus propiedades. */
    $UsuarioPremiomax = new UsuarioPremiomax();


    $UsuarioPremiomax->usuarioId = $consecutivo_usuario;

    $UsuarioPremiomax->premioMax = 0;


    /* Inicializa propiedades de un objeto UsuarioPremiomax a valores por defecto. */
    $UsuarioPremiomax->usumodifId = 0;

    $UsuarioPremiomax->fechaModif = "";

    $UsuarioPremiomax->cantLineas = 0;

    $UsuarioPremiomax->premioMax1 = 0;


    /* Inicializa atributos de un objeto UsuarioPremiomax en cero. */
    $UsuarioPremiomax->premioMax2 = 0;

    $UsuarioPremiomax->premioMax3 = 0;

    $UsuarioPremiomax->apuestaMin = 0;

    $UsuarioPremiomax->valorDirecto = 0;


    /* Inicializa atributos de un objeto llamado UsuarioPremiomax. */
    $UsuarioPremiomax->premioDirecto = 0;

    $UsuarioPremiomax->mandante = $Partner;

    $UsuarioPremiomax->optimizarParrilla = "N";

    $UsuarioPremiomax->textoOp1 = "";


    /* Se asignan valores iniciales a propiedades de un objeto llamado UsuarioPremiomax. */
    $UsuarioPremiomax->textoOp2 = "";

    $UsuarioPremiomax->urlOp2 = "";

    $UsuarioPremiomax->textoOp3 = 0;

    $UsuarioPremiomax->urlOp3 = 0;


    /* Se inicializan valores en cero y se crea una instancia de PuntoVenta. */
    $UsuarioPremiomax->valorEvento = 0;

    $UsuarioPremiomax->valorDiario = 0;


    $PuntoVenta = new PuntoVenta();

    /* Asignación de propiedades a un objeto PuntoVenta con datos de contacto y ubicación. */
    $PuntoVenta->descripcion = $Name;
    $PuntoVenta->nombreContacto = $ManagerName;
    $PuntoVenta->ciudadId = $CityId->Id;
    $PuntoVenta->ciudadId = $CityId;
    $PuntoVenta->direccion = $Address;
    $PuntoVenta->barrio = $District;

    /* Asignación de valores a propiedades del objeto PuntoVenta. */
    $PuntoVenta->telefono = $Phone;
    $PuntoVenta->email = $Email;
    $PuntoVenta->periodicidadId = 0;
    $PuntoVenta->clasificador1Id = 0;
    $PuntoVenta->clasificador2Id = 0;
    $PuntoVenta->clasificador3Id = 0;

    /* Inicializa valores para un objeto PuntoVenta con datos predeterminados. */
    $PuntoVenta->valorRecarga = 0;
    $PuntoVenta->valorCupo = '0';
    $PuntoVenta->valorCupo2 = '0';
    $PuntoVenta->porcenComision = '0';
    $PuntoVenta->porcenComision2 = '0';
    $PuntoVenta->estado = 'A';

    /* Código que inicializa propiedades de un objeto PuntoVenta con valores específicos. */
    $PuntoVenta->usuarioId = '0';
    $PuntoVenta->mandante = $Partner;
    $PuntoVenta->moneda = $CurrencyId;
    //$PuntoVenta->moneda = $CurrencyId->Id;
    $PuntoVenta->idioma = 'ES';
    $PuntoVenta->cupoRecarga = 0;

    /* Inicializa variables de créditos y configura usuario en una transacción de base de datos. */
    $PuntoVenta->creditosBase = 0;
    $PuntoVenta->creditos = 0;
    $PuntoVenta->creditosAnt = 0;
    $PuntoVenta->creditosBaseAnt = 0;
    $PuntoVenta->usuarioId = $consecutivo_usuario;


    $UsuarioConfigMySqlDAO = new UsuarioConfigMySqlDAO($UsuarioMySqlDAO->getTransaction());


    /* Código para insertar un concesionario en la base de datos con usuario modificado. */
    $ConcesionarioMySqlDAO = new ConcesionarioMySqlDAO($UsuarioMySqlDAO->getTransaction());
    $Concesionario->setUsumodifId($_SESSION['usuario']);

    $ConcesionarioMySqlDAO->insert($Concesionario);

    $UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO($UsuarioMySqlDAO->getTransaction());


    /* inserta usuarios y configuraciones en bases de datos utilizando DAO. */
    $UsuarioPerfilMySqlDAO->insert($UsuarioPerfil);

    $UsuarioPremiomaxMySqlDAO = new UsuarioPremiomaxMySqlDAO($UsuarioMySqlDAO->getTransaction());

    $UsuarioPremiomaxMySqlDAO->insert($UsuarioPremiomax);

    $UsuarioConfigMySqlDAO->insert($UsuarioConfig);


    /* Inserta un punto de venta y actualiza la contraseña del usuario en MySQL. */
    $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($UsuarioMySqlDAO->getTransaction());
    $PuntoVentaMySqlDAO->insert($PuntoVenta);

    $UsuarioMySqlDAO->getTransaction()->commit();


    $UsuarioMySqlDAO->updateClave($Usuario, $Password);


    /* Actualiza el usuario en la base de datos y confirma la transacción. */
    $Usuario->puntoventaId = $consecutivo_usuario;
    $UsuarioMySqlDAO2 = new UsuarioMySqlDAO();
    $UsuarioMySqlDAO2->update($Usuario);
    $UsuarioMySqlDAO2->getTransaction()->commit();

    $response["id"] = $consecutivo_usuario;


    /* Código para gestionar la respuesta de una operación sin errores en formato JSON. */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];


} else {
    /* maneja un error, estableciendo un mensaje de éxito y no errores en el modelo. */

    $response["HasError"] = true;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "Error no puede crearlo";
    $response["ModelErrors"] = [];

}

