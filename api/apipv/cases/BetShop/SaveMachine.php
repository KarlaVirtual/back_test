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
use Backend\dto\UsuarioOtrainfo;
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
use Backend\mysql\UsuarioOtrainfoMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioPublicidadMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenInternoMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\mysql\UsucomisionResumenMySqlDAO;
use Backend\websocket\WebsocketUsuario;


/**
 * BetShop/SaveMachine
 *
 * Guarda la información de una máquina en el sistema, creando usuarios y configuraciones asociadas.
 *
 * @param object $params Objeto que contiene los parámetros necesarios:
 * @param string $params ->Address Dirección de la máquina.
 * @param int $params ->CityId ID de la ciudad.
 * @param int $params ->CountryId ID del país.
 * @param int $params ->CurrencyId ID de la moneda.
 * @param string $params ->PreferredLanguage Idioma preferido.
 * @param string $params ->DocumentLegalID Documento legal.
 * @param string $params ->Email Correo electrónico.
 * @param int $params ->GroupId ID del grupo.
 * @param string $params ->IP Dirección IP.
 * @param float $params ->Latitud Latitud de la ubicación.
 * @param float $params ->Longitud Longitud de la ubicación.
 * @param string $params ->ManagerDocument Documento del gerente.
 * @param string $params ->ManagerName Nombre del gerente.
 * @param string $params ->ManagerPhone Teléfono del gerente.
 * @param string $params ->MobilePhone Teléfono móvil.
 * @param string $params ->Name Nombre de la máquina.
 * @param string $params ->LoginAccess Login de acceso.
 * @param string $params ->Phone Teléfono.
 * @param int $params ->RegionId ID de la región.
 * @param string $params ->District Distrito.
 * @param string $params ->AllowsRecharges Permite recargas (S/N).
 * @param string $params ->PrintReceiptBox Imprime recibos (S/N).
 * @param string $params ->ActivateRegistration Activa registro (S/N).
 * @param string $params ->Lockedsales Ventas bloqueadas (S/N).
 * @param string $params ->Pinagent Agente PIN (S/N).
 * @param string $params ->RepresentLegalDocument Documento del representante legal.
 * @param string $params ->RepresentLegalName Nombre del representante legal.
 * @param string $params ->RepresentLegalPhone Teléfono del representante legal.
 *
 *
 *
 * @return array $response Respuesta en formato JSON:
 *                         - HasError (boolean): Indica si ocurrió un error.
 *                         - AlertType (string): Tipo de alerta.
 *                         - AlertMessage (string): Mensaje de alerta.
 *                         - ModelErrors (array): Lista de errores del modelo.
 *                         - Data (mixed): Datos adicionales.
 *
 * @throws Exception Si ocurre un error en la transacción de la base de datos.
 */

/* Se crean variables a partir de parámetros relacionados con la configuración del entorno. */
$ConfigurationEnvironment = new ConfigurationEnvironment();

$Address = $params->Address;
$CityId = $params->CityId;
$CountryId = $params->CountryId;
$CurrencyId = $params->CurrencyId;

/* Asignación de variables a partir de parámetros recibidos en un script. */
$PreferredLanguage = $params->PreferredLanguage;
$DocumentLegalID = $params->DocumentLegalID;
$Email = $params->Email;
$GroupId = $params->GroupId;
$IP = $params->IP;
$Latitud = $params->Latitud;

/* asigna valores de parámetros a variables relacionadas con un gerente. */
$Longitud = $params->Longitud;
$ManagerDocument = $params->ManagerDocument;
$ManagerName = $params->ManagerName;
$ManagerName = $params->ContactName;

$ManagerPhone = $params->ManagerPhone;

/* Se asignan valores de parámetros a variables para su uso posterior en el código. */
$MobilePhone = $params->MobilePhone;
$Name = $params->Name;
$Login = $params->LoginAccess;
$Phone = $params->Phone;
$RegionId = $params->RegionId;

$District = $params->District;


/* asigna valores a variables a partir de parámetros proporcionados. */
$CanDeposit = $params->AllowsRecharges;
$CanReceipt = $params->PrintReceiptBox;
$CanActivateRegister = $params->ActivateRegistration;
$Lockedsales = $params->Lockedsales;
$Pinagent = $params->Pinagent;

$RepresentLegalDocument = $params->RepresentLegalDocument;

/* Asignación de variables desde parámetros, incluyendo nombre y teléfono del representante legal. */
$RepresentLegalName = $params->RepresentLegalName;
$RepresentLegalPhone = $params->RepresentLegalPhone;


$Address = $Address;
$CurrencyId = $CurrencyId;

/* Variables asignadas para almacenar información de usuario, como email y estado de suspensión. */
$Email = $Email;
$FirstName = $Name;
$Id = $params->Id;
$IsSuspended = false;
$LastLoginIp = "";
$LastLoginLocalDate = "";

/* Inicializa variables para almacenar información de usuario y sus datos relacionados. */
$LastName = "";
$clave = '';
$SystemName = '';
$UserId = '';
$UserName = $params->UserName;
$Phone = $params->ManagerPhone;


/* Condición que verifica que $Id y $UserId no estén vacíos antes de ejecutar el bloque. */
if ($Id != "" && $UserId != "") {

} else {

    /* Asignación de variables y validación de permisos en formato condicional. */
    $login = $Login;
    $Password = $params->Password;

    $CanReceipt = ($CanReceipt == "S") ? "S" : "N";
    $CanDeposit = ($CanDeposit == "S") ? "S" : "N";
    $CanActivateRegister = ($CanActivateRegister == "S") ? "S" : "N";

    /* verifica condiciones y maneja un objeto de tipo "PuntoVenta". */
    $Lockedsales = ($Lockedsales == "S") ? "S" : "N";
    $Pinagent = ($Pinagent == "S") ? "S" : "N";

    $consecutivo_usuario = 0;

    /*$Consecutivo = new Consecutivo("", "USU", "");

    $consecutivo_usuario = $Consecutivo->numero;

    $consecutivo_usuario++;

    $ConsecutivoMySqlDAO = new ConsecutivoMySqlDAO();

    $Consecutivo->setNumero($consecutivo_usuario);


    $ConsecutivoMySqlDAO->update($Consecutivo);

    $ConsecutivoMySqlDAO->getTransaction()->commit();*/

    $PuntoVenta = new PuntoVenta();


    /* Se crea un objeto Usuario y se asignan valores a sus propiedades. */
    $Usuario = new Usuario();


    $Usuario->usuarioId = $consecutivo_usuario;

    $Usuario->login = $login;


    /* Se asignan valores a las propiedades del objeto $Usuario en PHP. */
    $Usuario->nombre = $FirstName;

    $Usuario->estado = 'A';

    $Usuario->fechaUlt = date('Y-m-d H:i:s');

    $Usuario->claveTv = '';


    /* Se inicializan propiedades de un objeto Usuario con valores específicos. */
    $Usuario->estadoAnt = 'I';

    $Usuario->intentos = 0;

    $Usuario->estadoEsp = 'A';

    $Usuario->observ = '';


    /* asigna valores a propiedades del objeto Usuario según la sesión. */
    $Usuario->dirIp = '';

    $Usuario->eliminado = 'N';

    $Usuario->mandante = '0';

    if ($_SESSION['Global'] == "N") {

        $Usuario->mandante = $_SESSION["mandante"];
    }


    /* Código establece identificadores de usuario y genera un ticket con clave aleatoria. */
    $Usuario->usucreaId = '0';

    $Usuario->usumodifId = '0';

    $Usuario->claveCasino = '';
    $token_itainment = (new ConfigurationEnvironment())->GenerarClaveTicket2(12);


    /* Se asignan valores a propiedades del objeto $Usuario en PHP. */
    $Usuario->tokenItainment = $token_itainment;

    $Usuario->fechaClave = '';

    $Usuario->retirado = '';

    $Usuario->fechaRetiro = '';


    /* Se asignan valores a propiedades del objeto Usuario para gestionar su estado. */
    $Usuario->horaRetiro = '';

    $Usuario->usuretiroId = '0';

    $Usuario->bloqueoVentas = $Lockedsales;

    $Usuario->infoEquipo = '';


    /* asigna valores a las propiedades de un objeto Usuario en un sistema. */
    $Usuario->estadoJugador = 'AC';

    $Usuario->tokenCasino = '';

    $Usuario->sponsorId = 0;

    $Usuario->verifCorreo = 'N';


    /* Asignación de propiedades a un objeto Usuario con información de país, moneda, idioma y permisos. */
    $Usuario->paisId = $CountryId;

    $Usuario->moneda = $CurrencyId;

    $Usuario->idioma = $PreferredLanguage;

    $Usuario->permiteActivareg = $CanActivateRegister;


    /* asigna valores a propiedades del objeto $Usuario en PHP. */
    $Usuario->test = 'N';

    $Usuario->tiempoLimitedeposito = '0';

    $Usuario->tiempoAutoexclusion = '0';

    $Usuario->cambiosAprobacion = 'S';


    /* Se asignan propiedades a un objeto Usuario, incluyendo zona horaria y fecha de creación. */
    $Usuario->timezone = '-5';

    $Usuario->puntoventaId = $consecutivo_usuario;

    $Usuario->fechaCrea = date('Y-m-d H:i:s');

    $Usuario->origen = 0;


    /* Asignación de valores a propiedades de un objeto Usuario en un sistema. */
    $Usuario->fechaActualizacion = $Usuario->fechaCrea;
    $Usuario->documentoValidado = "A";
    $Usuario->fechaDocvalido = $Usuario->fechaCrea;
    $Usuario->usuDocvalido = 0;

    $Usuario->estadoValida = 'N';

    /* Se inicializan atributos del objeto Usuario con valores predeterminados. */
    $Usuario->usuvalidaId = 0;
    $Usuario->fechaValida = date('Y-m-d H:i:s');
    $Usuario->contingencia = 'I';
    $Usuario->contingenciaDeportes = 'I';
    $Usuario->contingenciaCasino = 'I';
    $Usuario->contingenciaCasvivo = 'I';

    /* Se asignan valores a propiedades del objeto Usuario relacionadas con contingencias y ubicación. */
    $Usuario->contingenciaVirtuales = 'I';
    $Usuario->contingenciaPoker = 'I';
    $Usuario->restriccionIp = 'I';
    $Usuario->ubicacionLongitud = $Longitud;
    $Usuario->ubicacionLatitud = $Latitud;
    $Usuario->usuarioIp = $IP;

    /* Se inserta un nuevo usuario con tokens y salt vacío en la base de datos. */
    $Usuario->tokenGoogle = "I";
    $Usuario->tokenLocal = "I";
    $Usuario->saltGoogle = '';

    $UsuarioMySqlDAO = new UsuarioMySqlDAO();

    $UsuarioMySqlDAO->insert($Usuario);

    /* Asignación de configuración de usuario basada en permisos y datos específicos. */
    $consecutivo_usuario = $Usuario->usuarioId;

    $UsuarioConfig = new UsuarioConfig();
    $UsuarioConfig->permiteRecarga = $CanDeposit;
    $UsuarioConfig->pinagent = $Pinagent;
    $UsuarioConfig->reciboCaja = $CanReceipt;

    /* Asignación de valores a un objeto y creación de una nueva instancia de "Concesionario". */
    $UsuarioConfig->mandante = $Usuario->mandante;
    $UsuarioConfig->usuarioId = $consecutivo_usuario;


    $Concesionario = new Concesionario();

    if ($_SESSION["win_perfil"] == "CONCESIONARIO") {

        /* Se crea un nuevo perfil de usuario con propiedades específicas asignadas. */
        $UsuarioPerfil = new UsuarioPerfil();
        $UsuarioPerfil->usuarioId = $consecutivo_usuario;
        $UsuarioPerfil->perfilId = 'MAQUINAANONIMA';
        $UsuarioPerfil->mandante = $Usuario->mandante;
        $UsuarioPerfil->pais = 'S';
        $UsuarioPerfil->global = 'N';


        // $ConcesionarioU = new Concesionario($_SESSION["usuario"], 0);


        /* Asigna identificadores de usuario y porcentajes en un objeto "Concesionario". */
        $Concesionario->setUsupadreId($_SESSION["usuario"]);
        $Concesionario->setUsuhijoId($consecutivo_usuario);
        $Concesionario->setusupadre2Id(0);
        $Concesionario->setusupadre3Id(0);
        $Concesionario->setusupadre4Id(0);
        $Concesionario->setPorcenhijo(0);

        /* Se establecen valores iniciales y se asigna un mandante al concesionario. */
        $Concesionario->setPorcenpadre1(0);
        $Concesionario->setPorcenpadre2(0);
        $Concesionario->setPorcenpadre3(0);
        $Concesionario->setPorcenpadre4(0);
        $Concesionario->setProdinternoId(0);
        $Concesionario->setMandante($Usuario->mandante);

        /* Se configuran los ID de usuario y estado del objeto Concesionario. */
        $Concesionario->setUsucreaId(0);
        $Concesionario->setUsumodifId(0);
        $Concesionario->setEstado("A");
    } elseif ($_SESSION["win_perfil"] == "CONCESIONARIO2") {

        /* Se crea un nuevo perfil de usuario con información específica asignada. */
        $UsuarioPerfil = new UsuarioPerfil();
        $UsuarioPerfil->usuarioId = $consecutivo_usuario;
        $UsuarioPerfil->perfilId = 'MAQUINAANONIMA';
        $UsuarioPerfil->mandante = $Usuario->mandante;
        $UsuarioPerfil->pais = 'S';
        $UsuarioPerfil->global = 'N';


        /* Crea una instancia de Concesionario y establece identificadores de usuario relacionados. */
        $ConcesionarioU = new Concesionario($_SESSION["usuario"], 0);

        $Concesionario->setUsupadreId($ConcesionarioU->getUsupadreId());
        $Concesionario->setUsuhijoId($consecutivo_usuario);
        $Concesionario->setusupadre2Id($_SESSION["usuario"]);
        $Concesionario->setusupadre3Id(0);

        /* Se inicializan valores a cero en un objeto de tipo Concesionario. */
        $Concesionario->setusupadre4Id(0);
        $Concesionario->setPorcenhijo(0);
        $Concesionario->setPorcenpadre1(0);
        $Concesionario->setPorcenpadre2(0);
        $Concesionario->setPorcenpadre3(0);
        $Concesionario->setPorcenpadre4(0);

        /* Configura propiedades de un objeto "Concesionario" con valores iniciales y estado activo. */
        $Concesionario->setProdinternoId(0);
        $Concesionario->setMandante($Usuario->mandante);
        $Concesionario->setUsucreaId(0);
        $Concesionario->setUsumodifId(0);
        $Concesionario->setEstado("A");
    } elseif ($_SESSION["win_perfil"] == "CONCESIONARIO3") {

        /* Se crea un nuevo perfil de usuario con atributos específicos como usuarioId y perfilId. */
        $UsuarioPerfil = new UsuarioPerfil();
        $UsuarioPerfil->usuarioId = $consecutivo_usuario;
        $UsuarioPerfil->perfilId = 'MAQUINAANONIMA';
        $UsuarioPerfil->mandante = $Usuario->mandante;
        $UsuarioPerfil->pais = 'S';
        $UsuarioPerfil->global = 'N';


        /* Se crea un objeto `Concesionario` y se establecen sus IDs de usuario. */
        $ConcesionarioU = new Concesionario($_SESSION["usuario"], 0);

        $Concesionario->setUsupadreId($ConcesionarioU->getUsupadreId());
        $Concesionario->setUsuhijoId($consecutivo_usuario);
        $Concesionario->setusupadre2Id($ConcesionarioU->getUsupadre2Id());
        $Concesionario->setusupadre3Id($ConcesionarioU->getUsupadre3Id());

        /* inicializa variables de porcentaje y relaciones en un objeto 'Concesionario'. */
        $Concesionario->setusupadre4Id(0);
        $Concesionario->setPorcenhijo(0);
        $Concesionario->setPorcenpadre1(0);
        $Concesionario->setPorcenpadre2(0);
        $Concesionario->setPorcenpadre3(0);
        $Concesionario->setPorcenpadre4(0);

        /* establece propiedades del objeto Concesionario utilizando métodos de configuración. */
        $Concesionario->setProdinternoId(0);
        $Concesionario->setMandante($Usuario->mandante);
        $Concesionario->setUsucreaId(0);
        $Concesionario->setUsumodifId(0);
        $Concesionario->setEstado("A");
    } else {


        /* Se crea un objeto `UsuarioPerfil` con propiedades específicas del usuario y perfil. */
        $UsuarioPerfil = new UsuarioPerfil();
        $UsuarioPerfil->usuarioId = $consecutivo_usuario;
        $UsuarioPerfil->perfilId = 'MAQUINAANONIMA';
        $UsuarioPerfil->mandante = $Usuario->mandante;
        $UsuarioPerfil->pais = 'S';
        $UsuarioPerfil->global = 'N';


        /* establece relaciones y porcentajes entre usuarios en un concesionario. */
        $Concesionario->setUsupadreId(0);
        $Concesionario->setUsuhijoId($consecutivo_usuario);
        $Concesionario->setusupadre2Id(0);
        $Concesionario->setusupadre3Id(0);
        $Concesionario->setusupadre4Id(0);
        $Concesionario->setPorcenhijo(0);

        /* Se establecen valores iniciales y propiedades para un objeto "Concesionario". */
        $Concesionario->setPorcenpadre1(0);
        $Concesionario->setPorcenpadre2(0);
        $Concesionario->setPorcenpadre3(0);
        $Concesionario->setPorcenpadre4(0);
        $Concesionario->setProdinternoId(0);
        $Concesionario->setMandante($Usuario->mandante);

        /* Establece atributos iniciales para un objeto de concesionario, indicando creador y estado. */
        $Concesionario->setUsucreaId(0);
        $Concesionario->setUsumodifId(0);
        $Concesionario->setEstado("A");
    }


    /* Se crea un objeto UsuarioPremiomax y se inicializan sus propiedades. */
    $UsuarioPremiomax = new UsuarioPremiomax();


    $UsuarioPremiomax->usuarioId = $consecutivo_usuario;

    $UsuarioPremiomax->premioMax = 0;


    /* Asignación de valores iniciales a propiedades del objeto $UsuarioPremiomax. */
    $UsuarioPremiomax->usumodifId = 0;

    $UsuarioPremiomax->fechaModif = "";

    $UsuarioPremiomax->cantLineas = 0;

    $UsuarioPremiomax->premioMax1 = 0;


    /* Inicializa variables de un objeto UsuarioPremiomax en cero. */
    $UsuarioPremiomax->premioMax2 = 0;

    $UsuarioPremiomax->premioMax3 = 0;

    $UsuarioPremiomax->apuestaMin = 0;

    $UsuarioPremiomax->valorDirecto = 0;


    /* Se inicializan propiedades de un objeto relacionado con premios y usuario. */
    $UsuarioPremiomax->premioDirecto = 0;

    $UsuarioPremiomax->mandante = $Usuario->mandante;

    $UsuarioPremiomax->optimizarParrilla = "N";

    $UsuarioPremiomax->textoOp1 = "";


    /* Inicializa propiedades de un objeto con valores vacíos y cero. */
    $UsuarioPremiomax->textoOp2 = "";

    $UsuarioPremiomax->urlOp2 = "";

    $UsuarioPremiomax->textoOp3 = 0;

    $UsuarioPremiomax->urlOp3 = 0;


    /* Se inicializan variables en un objeto y se crea una instancia de PuntoVenta. */
    $UsuarioPremiomax->valorEvento = 0;

    $UsuarioPremiomax->valorDiario = 0;


    $PuntoVenta = new PuntoVenta();

    /* Asignación de valores a propiedades del objeto PuntoVenta. */
    $PuntoVenta->descripcion = $Name;
    $PuntoVenta->nombreContacto = $ManagerName;
    $PuntoVenta->ciudadId = $CityId->Id;
    $PuntoVenta->ciudadId = $CityId;
    $PuntoVenta->direccion = $Address;
    $PuntoVenta->barrio = $District;

    /* Asigna valores a propiedades de un objeto "PuntoVenta". */
    $PuntoVenta->telefono = $Phone;
    $PuntoVenta->email = $Email;
    $PuntoVenta->periodicidadId = 0;
    $PuntoVenta->clasificador1Id = 0;
    $PuntoVenta->clasificador2Id = 0;
    $PuntoVenta->clasificador3Id = 0;

    /* Inicializa atributos de un objeto "PuntoVenta" con valores predeterminados. */
    $PuntoVenta->valorRecarga = 0;
    $PuntoVenta->valorCupo = '0';
    $PuntoVenta->valorCupo2 = '0';
    $PuntoVenta->porcenComision = '0';
    $PuntoVenta->porcenComision2 = '0';
    $PuntoVenta->estado = 'A';

    /* Se asignan valores a propiedades de un objeto PuntoVenta para configurarlo. */
    $PuntoVenta->usuarioId = '0';
    $PuntoVenta->mandante = $Usuario->mandante;
    //$PuntoVenta->moneda = $CurrencyId;
    $PuntoVenta->moneda = $CurrencyId;
    $PuntoVenta->idioma = 'ES';
    $PuntoVenta->cupoRecarga = 0;

    /* Se inicializan variables y se crea un nuevo objeto Registro. */
    $PuntoVenta->creditosBase = 0;
    $PuntoVenta->creditos = 0;
    $PuntoVenta->creditosAnt = 0;
    $PuntoVenta->creditosBaseAnt = 0;
    $PuntoVenta->usuarioId = $consecutivo_usuario;


    $Registro = new Registro();

    /* establece atributos de un registro de usuario utilizando datos del objeto Usuario. */
    $Registro->setCedula('CMAQ-' . $consecutivo_usuario);
    $Registro->setMandante($Usuario->mandante);

    $Registro->setNombre($Usuario->nombre);
    $Registro->setEmail($Usuario->login);
    $Registro->setClaveActiva('');

    /* configura propiedades de un registro de usuario en sistema de ventas. */
    $Registro->setEstado($Usuario->estado);
    $Registro->usuarioId = $consecutivo_usuario;
    $Registro->setCelular($PuntoVenta->telefono);
    $Registro->setCreditosBase(0);
    $Registro->setCreditos(0);
    $Registro->setCreditosAnt(0);

    /* establece propiedades de un objeto relacionado con un registro y usuario. */
    $Registro->setCreditosBaseAnt(0);
    //$Registro->setCiudadId($department_id->cities[0]->id);
    $Registro->setCiudadId($PuntoVenta->ciudadId);
    $Registro->setCasino(0);
    $Registro->setCasinoBase(0);
    $Registro->setMandante($Usuario->mandante);

    /* Se asignan valores de usuario a un registro, incluyendo nombres y tipo de documento. */
    $Registro->setNombre1($Usuario->nombre);
    $Registro->setNombre2($Usuario->nombre);
    $Registro->setApellido1($Usuario->nombre);
    $Registro->setApellido2($Usuario->nombre);
    $Registro->setSexo('N');
    $Registro->setTipoDoc('C');

    /* asigna valores a propiedades de un objeto desde otro objeto. */
    $Registro->setDireccion($PuntoVenta->getDireccion());
    $Registro->setTelefono($PuntoVenta->getTelefono());
    $Registro->setCiudnacimId($PuntoVenta->ciudadId);
    $Registro->setNacionalidadId($Usuario->paisId);
    $Registro->setDirIp($dir_ip);
    $Registro->setOcupacionId(0);

    /* Código que inicializa propiedades de un objeto Registro con valores específicos. */
    $Registro->setRangoingresoId(0);
    $Registro->setOrigenfondosId(0);
    $Registro->setPaisnacimId($Usuario->paisId);
    $Registro->setPuntoVentaId(0);
    $Registro->setPreregistroId(0);
    $Registro->setCreditosBono(0);

    /* inicializa propiedades de un objeto Registro con valores predeterminados. */
    $Registro->setCreditosBonoAnt(0);
    $Registro->setPreregistroId(0);
    $Registro->setUsuvalidaId(0);
    $Registro->setFechaValida('');
    $Registro->setCodigoPostal('');

    $Registro->setCiudexpedId($Usuario->paisId);

    /* Se establece una nueva fecha y valores en un registro y se crea un nuevo usuario. */
    $Registro->setFechaExped(date('Y-m-d'));
    $Registro->setPuntoventaId(0);
    $Registro->setEstadoValida("I");

    $Registro->setAfiliadorId(0);


    $UsuarioOtrainfo = new UsuarioOtrainfo();


    /* Se asignan valores a las propiedades del objeto $UsuarioOtrainfo. */
    $UsuarioOtrainfo->usuarioId = $consecutivo_usuario;
    $UsuarioOtrainfo->fechaNacim = date('Y-m-d');
    $UsuarioOtrainfo->mandante = $Usuario->mandante;
    $UsuarioOtrainfo->info2 = '';
    $UsuarioOtrainfo->bancoId = '0';
    $UsuarioOtrainfo->numCuenta = '0';

    /* Se inicializan propiedades de objetos relacionados a usuarios y sus mandantes. */
    $UsuarioOtrainfo->anexoDoc = 'N';
    $UsuarioOtrainfo->direccion = '';
    $UsuarioOtrainfo->tipoCuenta = '0';

    $UsuarioMandante = new UsuarioMandante();

    $UsuarioMandante->mandante = $Usuario->mandante;
    //$UsuarioMandante->dirIp = $dir_ip;

    /* Asigna propiedades del objeto `$Usuario` a `$UsuarioMandante` para transferir información. */
    $UsuarioMandante->nombres = $Usuario->nombre;
    $UsuarioMandante->apellidos = $Usuario->nombre;
    $UsuarioMandante->estado = $Usuario->estado;
    $UsuarioMandante->email = $Usuario->login;
    $UsuarioMandante->moneda = $Usuario->moneda;
    $UsuarioMandante->paisId = $Usuario->paisId;

    /* Se inicializa un objeto con valores específicos y se crea un DAO para usuario. */
    $UsuarioMandante->saldo = 0;
    $UsuarioMandante->usuarioMandante = $Usuario->usuarioId;
    $UsuarioMandante->usucreaId = 0;
    $UsuarioMandante->usumodifId = 0;
    $UsuarioMandante->propio = 'S';


    $UsuarioConfigMySqlDAO = new UsuarioConfigMySqlDAO($UsuarioMySqlDAO->getTransaction());


    /* inserta objetos en bases de datos relacionadas con concesionarios y usuarios. */
    $ConcesionarioMySqlDAO = new ConcesionarioMySqlDAO($UsuarioMySqlDAO->getTransaction());

    $ConcesionarioMySqlDAO->insert($Concesionario);

    $UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO($UsuarioMySqlDAO->getTransaction());

    $UsuarioPerfilMySqlDAO->insert($UsuarioPerfil);


    /* Código crea instancias de DAOs y realiza inserciones en base de datos. */
    $UsuarioPremiomaxMySqlDAO = new UsuarioPremiomaxMySqlDAO($UsuarioMySqlDAO->getTransaction());

    $UsuarioPremiomaxMySqlDAO->insert($UsuarioPremiomax);

    $UsuarioConfigMySqlDAO->insert($UsuarioConfig);

    $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($UsuarioMySqlDAO->getTransaction());

    /* inserta datos en bases de datos utilizando distintos objetos DAO y transacciones. */
    $PuntoVentaMySqlDAO->insert($PuntoVenta);


    $RegistroMySqlDAO = new RegistroMySqlDAO($UsuarioMySqlDAO->getTransaction());
    $RegistroMySqlDAO->insert($Registro);

    $UsuarioOtrainfoMySqlDAO = new UsuarioOtrainfoMySqlDAO($UsuarioMySqlDAO->getTransaction());

    /* Inserta datos de usuario en la base de datos utilizando transacciones MySQL. */
    $UsuarioOtrainfoMySqlDAO->insert($UsuarioOtrainfo);

    $UsuarioMandanteMySqlDAO = new UsuarioMandanteMySqlDAO($UsuarioMySqlDAO->getTransaction());
    $usuario_id = $UsuarioMandanteMySqlDAO->insert($UsuarioMandante);


    $UsuarioMySqlDAO->getTransaction()->commit();


    /* Actualiza la clave y otros datos del usuario en la base de datos MySQL. */
    $UsuarioMySqlDAO->updateClave($Usuario, $Password);


    $Usuario->puntoventaId = $consecutivo_usuario;
    $UsuarioMySqlDAO2 = new UsuarioMySqlDAO();
    $UsuarioMySqlDAO2->update($Usuario);

    /* confirma una transacción MySQL y crea un nuevo objeto UsuarioToken. */
    $UsuarioMySqlDAO2->getTransaction()->commit();


    $UsuarioToken = new UsuarioToken();

    $UsuarioToken->setRequestId('');

    /* establece atributos en un objeto UsuarioToken, incluyendo ID de usuario y token. */
    $UsuarioToken->setProveedorId(0);
    $UsuarioToken->setUsuarioId($UsuarioMandante->getUsumandanteId());
    $UsuarioToken->setToken($UsuarioToken->createToken());
    $UsuarioToken->setCookie($ConfigurationEnvironment->encrypt($UsuarioMandante->getUsumandanteId() . "#" . time()));
    $UsuarioToken->setUsumodifId(0);
    $UsuarioToken->setUsucreaId(0);

    /* establece el saldo de un usuario y guarda su información en la base de datos. */
    $UsuarioToken->setSaldo(0);

    $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
    $UsuarioTokenMySqlDAO->insert($UsuarioToken);
    $UsuarioTokenMySqlDAO->getTransaction()->commit();


    $UsuarioToken = new UsuarioToken();


    /* Configuración y creación de un token para autenticación de usuario. */
    $UsuarioToken->setRequestId('');
    $UsuarioToken->setProveedorId(1);
    $UsuarioToken->setUsuarioId($UsuarioMandante->getUsumandanteId());
    $UsuarioToken->setToken($UsuarioToken->createToken());
    $UsuarioToken->setCookie($ConfigurationEnvironment->encrypt($UsuarioMandante->getUsumandanteId() . "#" . time()));
    $UsuarioToken->setUsumodifId(0);

    /* Se crea un objeto UsuarioToken y se inserta en la base de datos. */
    $UsuarioToken->setUsucreaId(0);
    $UsuarioToken->setSaldo(0);

    $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
    $UsuarioTokenMySqlDAO->insert($UsuarioToken);
    $UsuarioTokenMySqlDAO->getTransaction()->commit();


    /* Se crean instancias de Proveedor y UsuarioToken, y se establece un ID de solicitud vacío. */
    $Proveedor = new Proveedor("", "IES");


    $UsuarioToken = new UsuarioToken();

    $UsuarioToken->setRequestId('');

    /* Se asignan propiedades a un objeto UsuarioToken utilizando datos de usuario y proveedor. */
    $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
    $UsuarioToken->setUsuarioId($UsuarioMandante->getUsumandanteId());
    $UsuarioToken->setToken($UsuarioToken->createToken());
    $UsuarioToken->setCookie($ConfigurationEnvironment->encrypt($UsuarioMandante->getUsumandanteId() . "#" . time()));
    $UsuarioToken->setUsumodifId(0);
    $UsuarioToken->setUsucreaId(0);

    /* establece un saldo, inserta un usuario y confirma la transacción. */
    $UsuarioToken->setSaldo(0);

    $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
    $UsuarioTokenMySqlDAO->insert($UsuarioToken);
    $UsuarioTokenMySqlDAO->getTransaction()->commit();

    $response["id"] = $consecutivo_usuario;

}


/* configura una respuesta JSON sin errores y con datos finales. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;

