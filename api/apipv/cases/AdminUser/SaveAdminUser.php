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
 * AdminUser/SaveAdminUser
 *
 * Guardar un usuario administrativo
 *
 * @param object $params Objeto que contiene los parámetros necesarios para guardar un usuario administrativo:
 * @param string $params ->Partner Partner del usuario
 * @param string $params ->Address Dirección del usuario
 * @param int $params ->CityId ID de la ciudad del usuario
 * @param int $params ->CountryId ID del país del usuario
 * @param int $params ->CurrencyId ID de la moneda del usuario
 * @param string $params ->PreferredLanguage Lenguaje preferido del usuario
 * @param string $params ->DocumentLegalID Documento legal del usuario
 * @param string $params ->Email Correo electrónico del usuario
 * @param int $params ->GroupId ID del grupo del usuario
 * @param string $params ->IP Dirección IP del usuario
 * @param float $params ->Latitud Latitud del usuario
 * @param float $params ->Longitud Longitud del usuario
 * @param string $params ->ManagerDocument Documento del manager
 * @param string $params ->ManagerName Nombre del manager
 * @param string $params ->ContactName Nombre de contacto
 * @param string $params ->ManagerPhone Teléfono del manager
 * @param string $params ->MobilePhone Teléfono móvil del usuario
 * @param string $params ->FirstName Nombre del usuario
 * @param string $params ->LoginAccess Acceso al login
 * @param string $params ->Phone Teléfono del usuario
 * @param int $params ->RegionId ID de la región del usuario
 * @param string $params ->District Distrito del usuario
 * @param string $params ->AllowsRecharges Permite recargas
 * @param string $params ->PrintReceiptBox Imprimir recibo
 * @param string $params ->ActivateRegistration Activar registro
 * @param string $params ->Lockedsales Ventas bloqueadas
 * @param string $params ->Pinagent Pin del agente
 * @param string $params ->RepresentLegalDocument Documento legal del representante
 * @param string $params ->RepresentLegalName Nombre del representante
 * @param string $params ->RepresentLegalPhone Teléfono del representante
 * @param string $params ->Partner Socio
 * @param array $params ->Partners Socios
 * @param int $params ->TypeUser Tipo de usuario
 * @param int $params ->AgentId ID del agente
 * @param string $params ->UserCountry País del usuario
 * @param int $params ->Id ID del usuario
 * @param string $params ->UserName Nombre de usuario
 * @param string $params ->Password Contraseña del usuario
 *
 *
 * @return array $response Respuesta con el resultado de la operación:
 *  - bool $HasError Indica si hubo un error
 *  - string $AlertType Tipo de alerta
 *  - string $AlertMessage Mensaje de alerta
 *  - array $ModelErrors Errores del modelo
 *  - array $Data Datos adicionales
 *  - int $id ID del usuario creado
 */


/* asigna valores de parámetros a variables correspondientes. */
$PartnerOption = $params->Partner;
$Address = $params->Address;
$CityId = $params->CityId;
$CountryId = $params->CountryId;
$CurrencyId = $params->CurrencyId;
$PreferredLanguage = $params->PreferredLanguage;

/* Variables extraen datos de parámetros para procesar información legal y ubicación. */
$DocumentLegalID = $params->DocumentLegalID;
$Email = $params->Email;
$GroupId = $params->GroupId;
$IP = $params->IP;
$Latitud = $params->Latitud;
$Longitud = $params->Longitud;

/* Asigna valores de parámetros a variables relacionadas con la información del manager. */
$ManagerDocument = $params->ManagerDocument;
$ManagerName = $params->ManagerName;
$ManagerName = $params->ContactName;

$ManagerPhone = $params->ManagerPhone;
$MobilePhone = $params->MobilePhone;

/* asigna valores de parámetros a variables para su uso posterior. */
$Name = $params->FirstName;
$Login = $params->LoginAccess;
$Phone = $params->Phone;
$RegionId = $params->RegionId;

$District = $params->District;


/* Variables que configuran permisos y opciones en un sistema de gestión. */
$CanDeposit = $params->AllowsRecharges;
$CanReceipt = $params->PrintReceiptBox;
$CanActivateRegister = $params->ActivateRegistration;
$Lockedsales = $params->Lockedsales;
$Pinagent = $params->Pinagent;

$RepresentLegalDocument = $params->RepresentLegalDocument;

/* Asignación de valores de parámetros a variables relacionadas con representantes y socios. */
$RepresentLegalName = $params->RepresentLegalName;
$RepresentLegalPhone = $params->RepresentLegalPhone;
$Partner = $params->Partner;
$Partners = $params->Partners;

$TypeUser = $params->TypeUser;

/* Asignación de valores y filtrado de un array en base a condiciones específicas. */
$AgentId = $params->AgentId;
$UserCountry = ($params->UserCountry == "S") ? "S" : "N";


if (is_array($Partners)) {

    if (($key = array_search('-1', $Partners)) !== false) {
        unset($Partners[$key]);
    }
}


/* asigna un primer elemento de un array a la variable $Partner si está vacía. */
if (is_array($Partner)) {
    $Partner = $Partner[0];
}

if ($Partner == "") {
    if (is_array($Partners)) {
        $Partner = $Partners[0];

    }
}


/* Se asigna el valor de `$Partner` o 0, y se definen otras variables sin cambios. */
$PartnerOption = ($Partner != "" && is_numeric($Partner)) ? $Partner : 0;


$Address = $Address;
$CurrencyId = $CurrencyId;
$Email = $Email;

/* Se inicializan variables para almacenar datos de un usuario. */
$FirstName = $Name;
$Id = $params->Id;
$IsSuspended = false;
$LastLoginIp = "";
$LastLoginLocalDate = "";
$LastName = "";

/* Variables inicializadas para almacenar información de usuario y sistema. */
$clave = '';
$SystemName = '';
$UserId = '';
$UserName = $params->UserName;
$Phone = $params->ManagerPhone;
$UserCountry = $params->UserCountry;


/* Condición que valida si las variables $Id y $UserId no están vacías. */
if ($Id != "" && $UserId != "") {

} else {

    /* Asignación de valores y validación de permisos en variables específicas. */
    $login = $Login;
    $Password = $params->Password;

    $CanReceipt = ($CanReceipt == "S") ? "S" : "N";
    $CanDeposit = ($CanDeposit == "S") ? "S" : "N";
    $CanActivateRegister = ($CanActivateRegister == "S") ? "S" : "N";

    /* gestiona variables de estado y prepara un objeto PuntoVenta. */
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

    $PuntoVenta = new PuntoVenta($CashDeskId);


    /* Se crea un objeto Usuario y se asigna un valor a su atributo login. */
    $Usuario = new Usuario();


    //$Usuario->usuarioId = $consecutivo_usuario;

    $Usuario->login = $login;


    /* Se asignan valores a propiedades del objeto Usuario en PHP. */
    $Usuario->nombre = $FirstName;

    $Usuario->estado = 'A';

    $Usuario->fechaUlt = date('Y-m-d H:i:s');

    $Usuario->claveTv = '';


    /* Se inicializan propiedades de un objeto usuario: estado, intentos y observaciones. */
    $Usuario->estadoAnt = 'I';

    $Usuario->intentos = 0;

    $Usuario->estadoEsp = 'A';

    $Usuario->observ = '';


    /* Se inicializan propiedades del objeto Usuario con valores predeterminados. */
    $Usuario->dirIp = '';

    $Usuario->eliminado = 'N';

    $Usuario->mandante = $PartnerOption;

    $Usuario->usucreaId = '0';


    /* Se asignan valores a propiedades del objeto Usuario, incluyendo un token generado. */
    $Usuario->usumodifId = '0';

    $Usuario->claveCasino = '';
    $token_itainment = (new ConfigurationEnvironment())->GenerarClaveTicket2(12);

    $Usuario->tokenItainment = $token_itainment;


    /* Se inicializan propiedades de un objeto Usuario con cadenas vacías. */
    $Usuario->fechaClave = '';

    $Usuario->retirado = '';

    $Usuario->fechaRetiro = '';

    $Usuario->horaRetiro = '';


    /* Se asignan valores a propiedades del objeto Usuario, configurando su estado y bloqueos. */
    $Usuario->usuretiroId = '0';

    $Usuario->bloqueoVentas = $Lockedsales;

    $Usuario->infoEquipo = '';

    $Usuario->estadoJugador = 'AC';


    /* asigna valores iniciales a propiedades del objeto Usuario. */
    $Usuario->tokenCasino = '';

    $Usuario->sponsorId = 0;

    $Usuario->verifCorreo = 'N';

    $Usuario->paisId = $CountryId;


    /* Se asignan propiedades a un objeto Usuario, incluyendo moneda, idioma y permisos. */
    $Usuario->moneda = $CurrencyId;

    $Usuario->idioma = $PreferredLanguage;

    $Usuario->permiteActivareg = $CanActivateRegister;

    $Usuario->test = 'N';


    /* Configuración de variables para un usuario, incluyendo límite de depósito y zona horaria. */
    $Usuario->tiempoLimitedeposito = '0';

    $Usuario->tiempoAutoexclusion = '0';

    $Usuario->cambiosAprobacion = 'S';

    $Usuario->timezone = '-5';


    /* Se inicializan propiedades del objeto Usuario con valores predeterminados y la fecha actual. */
    $Usuario->puntoventaId = 0;

    $Usuario->fechaCrea = date('Y-m-d H:i:s');

    $Usuario->origen = 0;

    $Usuario->fechaActualizacion = $Usuario->fechaCrea;

    /* Asignación de valores a propiedades de un objeto Usuario para manejo de documentación. */
    $Usuario->documentoValidado = "A";
    $Usuario->fechaDocvalido = $Usuario->fechaCrea;
    $Usuario->usuDocvalido = 0;

    $Usuario->estadoValida = 'N';
    $Usuario->usuvalidaId = 0;

    /* Se asignan valores a propiedades del objeto Usuario, incluyendo fecha y estado de contingencias. */
    $Usuario->fechaValida = date('Y-m-d H:i:s');
    $Usuario->contingencia = 'I';
    $Usuario->contingenciaDeportes = 'I';
    $Usuario->contingenciaCasino = 'I';
    $Usuario->contingenciaCasvivo = 'I';
    $Usuario->contingenciaVirtuales = 'I';

    /* Asigna valores de configuración de usuario, incluyendo ubicación y restricciones. */
    $Usuario->contingenciaPoker = 'I';
    $Usuario->restriccionIp = 'I';
    $Usuario->ubicacionLongitud = $Longitud;
    $Usuario->ubicacionLatitud = $Latitud;
    $Usuario->usuarioIp = $IP;
    $Usuario->tokenGoogle = "I";

    /* Se establece información inicial para un objeto Usuario y se crea un DAO. */
    $Usuario->tokenLocal = "I";
    $Usuario->saltGoogle = '';
    $Usuario->monedaReporte = $Usuario->moneda;
    $Usuario->verifcedulaAnt = 'N';
    $Usuario->verifcedulaPost = 'N';

    $UsuarioMySqlDAO = new UsuarioMySqlDAO();


    /* inserta un usuario en la base de datos y configura sus preferencias. */
    $UsuarioMySqlDAO->insert($Usuario);
    $consecutivo_usuario = $Usuario->usuarioId;

    $UsuarioConfig = new UsuarioConfig();
    $UsuarioConfig->permiteRecarga = $CanDeposit;
    $UsuarioConfig->pinagent = $Pinagent;

    /* Asignación de propiedades a objeto de configuración de usuario y creación de instancia adicional. */
    $UsuarioConfig->reciboCaja = $CanReceipt;
    $UsuarioConfig->mandante = $PartnerOption;
    $UsuarioConfig->usuarioId = $consecutivo_usuario;


    $UsuarioPremiomax = new UsuarioPremiomax();


    /* Se asignan valores a propiedades de un objeto UsuarioPremiomax. */
    $UsuarioPremiomax->usuarioId = $consecutivo_usuario;

    $UsuarioPremiomax->premioMax = 0;

    $UsuarioPremiomax->usumodifId = 0;

    $UsuarioPremiomax->fechaModif = "";


    /* Inicializa variables de un objeto UsuarioPremiomax en cero. */
    $UsuarioPremiomax->cantLineas = 0;

    $UsuarioPremiomax->premioMax1 = 0;

    $UsuarioPremiomax->premioMax2 = 0;

    $UsuarioPremiomax->premioMax3 = 0;


    /* Inicializa propiedades de un objeto UsuarioPremiomax con valores predeterminados. */
    $UsuarioPremiomax->apuestaMin = 0;

    $UsuarioPremiomax->valorDirecto = 0;

    $UsuarioPremiomax->premioDirecto = 0;

    $UsuarioPremiomax->mandante = $PartnerOption;


    /* Código que inicializa propiedades de un objeto para un usuario específico. */
    $UsuarioPremiomax->optimizarParrilla = "N";

    $UsuarioPremiomax->textoOp1 = "";

    $UsuarioPremiomax->textoOp2 = "";

    $UsuarioPremiomax->urlOp2 = "";


    /* Asignación de valores iniciales a propiedades de un objeto en programación. */
    $UsuarioPremiomax->textoOp3 = 0;

    $UsuarioPremiomax->urlOp3 = 0;

    $UsuarioPremiomax->valorEvento = 0;

    $UsuarioPremiomax->valorDiario = 0;


    /* Crea un nuevo objeto 'UsuarioPerfil' con propiedades específicas del usuario. */
    $UsuarioPerfil = new UsuarioPerfil();
    $UsuarioPerfil->usuarioId = $consecutivo_usuario;
    $UsuarioPerfil->perfilId = $TypeUser;
    $UsuarioPerfil->mandante = $PartnerOption;
    $UsuarioPerfil->pais = $UserCountry;
    $UsuarioPerfil->global = 'N';


    /* Asigna una lista de socios y un ID de agente al perfil del usuario. */
    $UsuarioPerfil->mandanteLista = implode(", ", $Partners);

    if ($AgentId != "") {
        $UsuarioPerfil->consultaAgente = $AgentId;
    } else {
        $UsuarioPerfil->consultaAgente = "0";
    }


    /* Se crean instancias de DAO para gestionar usuarios y sus perfiles en MySQL. */
    $UsuarioConfigMySqlDAO = new UsuarioConfigMySqlDAO($UsuarioMySqlDAO->getTransaction());

    $UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO($UsuarioMySqlDAO->getTransaction());

    $UsuarioPerfilMySqlDAO->insert($UsuarioPerfil);

    $UsuarioPremiomaxMySqlDAO = new UsuarioPremiomaxMySqlDAO($UsuarioMySqlDAO->getTransaction());


    /* Inserta datos de usuario y configura clave, seguido de un commit en transacción. */
    $UsuarioPremiomaxMySqlDAO->insert($UsuarioPremiomax);

    $UsuarioConfigMySqlDAO->insert($UsuarioConfig);

    $UsuarioMySqlDAO->getTransaction()->commit();


    $UsuarioMySqlDAO->updateClave($Usuario, $Password);


    /* Asigna el valor de $consecutivo_usuario a la clave "id" del array $response. */
    $response["id"] = $consecutivo_usuario;

}


/* Código establece una respuesta con éxito y datos finales sin errores. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;