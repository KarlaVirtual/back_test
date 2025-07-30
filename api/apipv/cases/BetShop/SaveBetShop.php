<?php

use Backend\dto\ApiTransaction;
use Backend\dto\Area;
use Backend\dto\AuditoriaGeneral;
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
use Backend\dto\Template;
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
use Backend\mysql\AuditoriaGeneralMySqlDAO;
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
 * Guarda la información de un punto de venta basándose en los datos proporcionados.
 *
 * @param string $params JSON codificado que contiene los datos de la solicitud, incluyendo:
 * @param string $params ->Address Dirección del punto de venta.
 * @param int $params ->CityId Identificador de la ciudad asociada.
 * @param int $params ->CountryId Identificador del país asociado.
 * @param string $params ->CurrencyId Moneda asociada al punto de venta.
 * @param string $params ->PreferredLanguage Idioma preferido del punto de venta.
 * @param string $params ->DocumentLegalID Documento legal asociado al punto de venta.
 * @param string $params ->Email Correo electrónico del punto de venta.
 * @param int $params ->GroupId Identificador del grupo asociado.
 * @param string $params ->IP Dirección IP asociada al punto de venta.
 * @param string $params ->Latitud Latitud del punto de venta.
 * @param string $params ->Longitud Longitud del punto de venta.
 * @param string $params ->ManagerDocument Documento del gerente del punto de venta.
 * @param string $params ->ManagerName Nombre del gerente del punto de venta.
 * @param string $params ->ContactName Nombre del contacto del punto de venta.
 * @param string $params ->ManagerPhone Teléfono del gerente del punto de venta.
 * @param string $params ->MobilePhone Teléfono móvil del punto de venta.
 * @param string $params ->Name Nombre del punto de venta.
 * @param string $params ->LoginAccess Login del punto de venta.
 * @param string $params ->Phone Teléfono del punto de venta.
 * @param int $params ->RegionId Identificador de la región asociada.
 * @param string $params ->District Distrito asociado al punto de venta.
 * @param string $params ->AllowsRecharges Indica si se permiten recargas ('S' o 'N').
 * @param string $params ->PrintReceiptBox Indica si se permite imprimir recibos ('S' o 'N').
 * @param string $params ->ActivateRegistration Indica si se permite activar registros ('S' o 'N').
 * @param string $params ->Lockedsales Indica si las ventas están bloqueadas ('S' o 'N').
 * @param string $params ->Pinagent Indica si el agente tiene PIN ('S' o 'N').
 * @param string $params ->RepresentLegalDocument Documento del representante legal.
 * @param string $params ->RepresentLegalName Nombre del representante legal.
 * @param string $params ->RepresentLegalPhone Teléfono del representante legal.
 * @param int $params ->Partner Identificador del socio.
 * @param string $params ->Document Documento asociado al punto de venta.
 * @param string $params ->IPIdentification Identificación IP ('S' o 'N').
 * @param string $params ->HeaderPrintPrizePaymentReceipt Encabezado del recibo de pago de premios.
 * @param string $params ->FooterPrintPrizePaymentReceipt Pie del recibo de pago de premios.
 * @param string $params ->HeaderPrintRetirementPaymentReceipt Encabezado del recibo de retiro.
 * @param string $params ->FooterPrintRetirementPaymentReceipt Pie del recibo de retiro.
 * @param string $params ->BetShopType Tipo de punto de venta.
 * @param string $params ->PhysicalPrize Indica si se permite premio físico ('S' o 'N').
 * @param float $params ->PrizePaymentTa Impuesto aplicado al pago de premios.
 * @param int $params ->TypeEstablishment Tipo de establecimiento.
 * @param int $params ->Zone Zona asociada al punto de venta.
 * @param string $params ->Facebook Facebook asociado al punto de venta.
 * @param string $params ->FacebookVerification Verificación de Facebook ('S' o 'N').
 * @param string $params ->Instagram Instagram asociado al punto de venta.
 * @param string $params ->InstagramVerification Verificación de Instagram ('S' o 'N').
 * @param string $params ->WhatsApp WhatsApp asociado al punto de venta.
 * @param string $params ->WhatsAppVerification Verificación de WhatsApp ('S' o 'N').
 * @param string $params ->OtherSocialMedia Otras redes sociales asociadas.
 * @param string $params ->OtherSocialMediaVerification Verificación de otras redes sociales ('S' o 'N').
 * @param string $params ->ComissionsPayment Indica si se permite el pago de comisiones ('S' o 'N').
 * @param float $params ->MinimumBet Apuesta mínima permitida.
 * @param float $params ->DailyLimit Límite diario de apuestas.
 * @param float $params ->DailyQuotaReloads Cupo diario de recargas.
 * @param float $params ->MaximumWithdrawalAmount Monto máximo de retiro.
 * @param float $params ->MaximumPrizePaymentAmount Monto máximo de pago de premios.
 * @param int $params ->Concessionaire Identificador del concesionario.
 * @param int $params ->Subconcessionaire Identificador del subconcesionario.
 * @param int $params ->Subconcessionaire2 Identificador del segundo subconcesionario.
 *
 *
 *
 *
 * @return array $response Contiene los datos de la respuesta, incluyendo:
 *                         - HasError (bool): Indica si ocurrió un error.
 *                         - AlertType (string): Tipo de alerta ('success' en caso de éxito).
 *                         - AlertMessage (string): Mensaje de alerta.
 *                         - ModelErrors (array): Lista de errores del modelo.
 *                         - id (int): Identificador del punto de venta creado o actualizado.
 *
 * @throws Exception Si ocurre un error al guardar o actualizar el punto de venta.
 */

/* asigna valores de parámetros a variables específicas. */
$Address = $params->Address;
$CityId = $params->CityId;
$CountryId = $params->CountryId;
$CurrencyId = $params->CurrencyId;
$PreferredLanguage = $params->PreferredLanguage;
$DocumentLegalID = $params->DocumentLegalID;

/* Asigna valores de un objeto 'params' a variables específicas para su uso posterior. */
$Email = $params->Email;
$GroupId = $params->GroupId;
$IP = $params->IP;
$Latitud = $params->Latitud;
$Longitud = $params->Longitud;
$ManagerDocument = $params->ManagerDocument;

/* Asignación de variables a partir de un objeto `$params` en PHP. */
$ManagerName = $params->ManagerName;
$ManagerName = $params->ContactName;

$ManagerPhone = $params->ManagerPhone;
$MobilePhone = $params->MobilePhone;
$Name = $params->Name;

/* asigna valores de un objeto `$params` a variables específicas. */
$Login = $params->LoginAccess;
$Phone = $params->Phone;
$RegionId = $params->RegionId;

$District = $params->District;

$CanDeposit = $params->AllowsRecharges;

/* asigna valores de parámetros a variables específicas relacionadas con la funcionalidad. */
$CanReceipt = $params->PrintReceiptBox;
$CanActivateRegister = $params->ActivateRegistration;
$Lockedsales = $params->Lockedsales;
$Pinagent = $params->Pinagent;


$RepresentLegalDocument = $params->RepresentLegalDocument;

/* Asignación de variables a partir de parámetros para imprimir recibo de premio. */
$RepresentLegalName = $params->RepresentLegalName;
$RepresentLegalPhone = $params->RepresentLegalPhone;
$Partner = $params->Partner;
$Document = $params->Document;
$IPIdentification = $params->IPIdentification;


$HeaderPrintPrizePaymentReceipt = $params->HeaderPrintPrizePaymentReceipt;

/* asigna valores de parámetros a variables específicas relacionadas con pagos y recibos. */
$FooterPrintPrizePaymentReceipt = $params->FooterPrintPrizePaymentReceipt;
$HeaderPrintRetirementPaymentReceipt = $params->HeaderPrintRetirementPaymentReceipt;
$FooterPrintRetirementPaymentReceipt = $params->FooterPrintRetirementPaymentReceipt;
$BetShopType = $params->BetShopType;
$PhysicalPrize = $params->PhysicalPrize;

$PrizePaymentTa = $params->PrizePaymentTa;

/* Código que asigna parámetros relacionados con un establecimiento y sus redes sociales. */
$TypeEstablishment = $params->TypeEstablishment;
$Zone = $params->Zone;

$Facebook = $params->Facebook;
$FacebookVerification = $params->FacebookVerification;
$Instagram = $params->Instagram;

/* asigna valores de los parámetros a variables relacionadas con redes sociales y pagos. */
$InstagramVerification = $params->InstagramVerification;
$WhatsApp = $params->WhatsApp;
$WhatsAppVerification = $params->WhatsAppVerification;
$OtherSocialMedia = $params->OtherSocialMedia;
$OtherSocialMediaVerification = $params->OtherSocialMediaVerification;
$ComissionsPayment = $params->ComissionsPayment;

/* Convierte verificaciones sociales en valores binarios; asigna '0' si la zona está vacía. */
$FacebookVerification = ($FacebookVerification == "S") ? "1" : "0";
$InstagramVerification = ($InstagramVerification == "S") ? "1" : "0";
$WhatsAppVerification = ($WhatsAppVerification == "S") ? "1" : "0";
$OtherSocialMediaVerification = ($OtherSocialMediaVerification == "S") ? "1" : "0";
//$ComissionsPayment = ($ComissionsPayment == "S") ? "S" : "F";


if ($Zone == '') {
    $Zone = '0';
}


/* Asignación de variables para gestionar información de un usuario o cliente. */
$Address = $Address;
$CurrencyId = $CurrencyId;
$Email = $Email;
$FirstName = $Name;
$Id = $params->Id;
$IsSuspended = false;

/* Variables inicializadas para almacenar información sobre el último inicio de sesión de un usuario. */
$LastLoginIp = "";
$LastLoginLocalDate = "";
$LastName = "";
$clave = '';
$SystemName = '';
$UserId = '';

/* Código que asigna valores de parámetros a variables específicas de usuario y apuestas. */
$UserName = $params->UserName;
$Phone = $params->ManagerPhone;

$MinimumBet = $params->MinimumBet;
$DailyLimit = $params->DailyLimit;
$DailyQuotaReloads = $params->DailyQuotaReloads;

/* Se asignan valores de parámetros y se establece un límite diario predeterminado. */
$Concessionaire = $params->Concessionaire;
$Subconcessionaire = $params->Subconcessionaire;
$Subconcessionaire2 = $params->Subconcessionaire2;

if ($DailyLimit == '') {
    $DailyLimit = '0';
}


/* asigna '0' a variables si están vacías. */
if ($MinimumBet == '') {
    $MinimumBet = '0';
}
if ($DailyQuotaReloads == '') {
    $DailyQuotaReloads = '0';
}


/* asigna valores de parámetros y establece un valor por defecto para variables vacías. */
$MaximumWithdrawalAmount = $params->MaximumWithdrawalAmount;
$MaximumPrizePaymentAmount = $params->MaximumPrizePaymentAmount;

// Valores por defecto no requeridos

if ($Subconcessionaire == "") {
    $Subconcessionaire = 0;
}


/* Asigna 0 a variables vacías de concesionarios. */
if ($Subconcessionaire2 == "") {
    $Subconcessionaire2 = 0;
}

if ($Concessionaire == "") {
    $Concessionaire = 0;
}

/* Verifica si $Id y $UserId no son cadenas vacías antes de ejecutar el código. */
$Pinagent = 'N';


if ($Id != "" && $UserId != "") {

} else {


    /* inicializa un entorno de configuración y crea un usuario con datos específicos. */
    $ConfigurationEnvironment = new ConfigurationEnvironment();

    $Usuario = new Usuario();
    $Usuario->mandante = $Partner;

    $Usuario->login = $Login;


    /* verifica si un login existe y lanza excepciones si es así. */
    $checkLogin = $Usuario->exitsLogin();
    if ($checkLogin) {
        throw new Exception("El login ya existe", "19001");

        throw new Exception("Inusual Detected", "100001");

    }


    /* Genera una contraseña aleatoria codificada en base64 para el socio 8. */
    $login = $Login;
    $Password = $params->Password;

    if ($Partner == 8) {
        $newPassword = substr(base64_encode(bin2hex(openssl_random_pseudo_bytes(16))), 0, 12);
        $Password = $newPassword;
    }


    /* verifica y asigna valores "S" o "N" a variables. */
    $CanReceipt = ($CanReceipt == "S") ? "S" : "N";
    $CanDeposit = ($CanDeposit == "S") ? "S" : "N";
    $CanActivateRegister = ($CanActivateRegister == "S") ? "S" : "N";
    $Lockedsales = ($Lockedsales == "S") ? "S" : "N";
    $Pinagent = ($Pinagent == "S") ? "S" : "N";
    $IPIdentification = ($IPIdentification == "S") ? "S" : "N";

    /* Código que maneja la lógica para incrementar un consecutivo de usuario en un sistema. */
    $consecutivo_usuario = 0;

    /*$Consecutivo = new Consecutivo("", "USU", "");

    $consecutivo_usuario = $Consecutivo->numero;

    $consecutivo_usuario++;

    $ConsecutivoMySqlDAO = new ConsecutivoMySqlDAO();

    $Consecutivo->setNumero($consecutivo_usuario);


    $ConsecutivoMySqlDAO->update($Consecutivo);

    $ConsecutivoMySqlDAO->getTransaction()->commit();*/

    $PuntoVenta = new PuntoVenta();


    /* Creación de un objeto Usuario y asignación de propiedades usuarioId y login. */
    $Usuario = new Usuario();


    $Usuario->usuarioId = $consecutivo_usuario;

    $Usuario->login = $login;


    /* Asigna valores a propiedades del objeto $Usuario en PHP. */
    $Usuario->nombre = $FirstName;

    $Usuario->estado = 'A';

    $Usuario->fechaUlt = date('Y-m-d H:i:s');

    $Usuario->claveTv = '';


    /* Código que inicializa propiedades de un objeto Usuario, estableciendo estado e intentos. */
    $Usuario->estadoAnt = 'I';

    $Usuario->intentos = 0;

    $Usuario->estadoEsp = 'A';

    $Usuario->observ = '';


    /* Código define propiedades de un objeto "Usuario" con valores iniciales específicos. */
    $Usuario->dirIp = '';

    $Usuario->eliminado = 'N';

    $Usuario->mandante = $Partner;

    $Usuario->usucreaId = '0';


    /* asigna valores a atributos de un objeto Usuario y genera un token. */
    $Usuario->usumodifId = '0';

    $Usuario->claveCasino = '';
    $token_itainment = (new ConfigurationEnvironment())->GenerarClaveTicket2(12);

    $Usuario->tokenItainment = $token_itainment;


    /* inicializa propiedades del objeto Usuario con cadenas vacías. */
    $Usuario->fechaClave = '';

    $Usuario->retirado = '';

    $Usuario->fechaRetiro = '';

    $Usuario->horaRetiro = '';


    /* Se asignan valores a propiedades del objeto Usuario en un sistema de gestión. */
    $Usuario->usuretiroId = '0';

    $Usuario->bloqueoVentas = $Lockedsales;

    $Usuario->infoEquipo = '';

    $Usuario->estadoJugador = 'AC';


    /* Código que inicializa propiedades del objeto $Usuario relacionadas a un usuario. */
    $Usuario->tokenCasino = '';

    $Usuario->sponsorId = 0;

    $Usuario->verifCorreo = 'N';

    $Usuario->paisId = $CountryId;


    /* Se asignan valores a propiedades del objeto $Usuario, configurando preferencias y permisos. */
    $Usuario->moneda = $CurrencyId;

    $Usuario->idioma = $PreferredLanguage;

    $Usuario->permiteActivareg = $CanActivateRegister;

    $Usuario->test = 'N';


    /* Configura propiedades del usuario, incluyendo restricciones de tiempo y cambios permitidos. */
    $Usuario->tiempoLimitedeposito = '0';

    $Usuario->tiempoAutoexclusion = '0';

    $Usuario->cambiosAprobacion = 'S';

    $Usuario->timezone = '-5';


    /* Asignación de valores y fechas al objeto Usuario en PHP. */
    $Usuario->puntoventaId = $consecutivo_usuario;

    $Usuario->fechaCrea = date('Y-m-d H:i:s');

    $Usuario->origen = 0;

    $Usuario->fechaActualizacion = $Usuario->fechaCrea;

    /* asigna valores a propiedades de un objeto "Usuario". */
    $Usuario->documentoValidado = "A";
    $Usuario->fechaDocvalido = $Usuario->fechaCrea;
    $Usuario->usuDocvalido = 0;

    $Usuario->estadoValida = 'N';
    $Usuario->usuvalidaId = 0;

    /* Se establece una fecha y contingencias inicializadas para un usuario en un sistema. */
    $Usuario->fechaValida = date('Y-m-d H:i:s');
    $Usuario->contingencia = 'I';
    $Usuario->contingenciaDeportes = 'I';
    $Usuario->contingenciaCasino = 'I';
    $Usuario->contingenciaCasvivo = 'I';
    $Usuario->contingenciaVirtuales = 'I';

    /* Se asignan valores a propiedades del objeto $Usuario relacionadas con geolocalización y restricciones. */
    $Usuario->contingenciaPoker = 'I';
    $Usuario->restriccionIp = 'I';
    $Usuario->ubicacionLongitud = $Longitud;
    $Usuario->ubicacionLatitud = $Latitud;
    $Usuario->usuarioIp = $IP;
    $Usuario->tokenGoogle = "I";

    /* Código asigna valores a propiedades del objeto Usuario y crea instancia de UsuarioMySqlDAO. */
    $Usuario->tokenLocal = "I";
    $Usuario->saltGoogle = '';
    $Usuario->pagoComisiones = $ComissionsPayment;


    $UsuarioMySqlDAO = new UsuarioMySqlDAO();


    /* Inserta un usuario en la base de datos y configura sus permisos de recarga. */
    $UsuarioMySqlDAO->insert($Usuario);
    $consecutivo_usuario = $Usuario->usuarioId;


    $UsuarioConfig = new UsuarioConfig();
    $UsuarioConfig->permiteRecarga = $CanDeposit;

    /* asigna valores a propiedades de un objeto de configuración de usuario. */
    $UsuarioConfig->pinagent = $Pinagent;
    $UsuarioConfig->reciboCaja = $CanReceipt;
    $UsuarioConfig->mandante = $Partner;
    $UsuarioConfig->usuarioId = $consecutivo_usuario;

    $UsuarioConfig->maxpagoRetiro = $MaximumWithdrawalAmount;

    /* Se asigna un monto máximo de premio y se instancia un objeto Concesionario. */
    $UsuarioConfig->maxpagoPremio = $MaximumPrizePaymentAmount;


    $Concesionario = new Concesionario();

    if ($_SESSION["win_perfil"] == "CONCESIONARIO") {

        /* actualiza el estado de un usuario si cumple una condición específica. */
        if ($_SESSION["mandante"] == 8) {
            $Usuario->estado = 'I';
            $Usuario->estadoValida = 'N';
        }
        $UsuarioPerfil = new UsuarioPerfil();
        $UsuarioPerfil->usuarioId = $consecutivo_usuario;

        /* Asigna valores a un objeto y verifica si un subconcesionario es numérico. */
        $UsuarioPerfil->perfilId = 'PUNTOVENTA';
        $UsuarioPerfil->mandante = $Partner;
        $UsuarioPerfil->pais = 'S';
        $UsuarioPerfil->global = 'N';

        if (!is_numeric($Subconcessionaire)) {
            $Subconcessionaire = 0;
        }


        /* Verifica si $Subconcessionaire2 es numérico; si no, lo asigna a 0. */
        if (!is_numeric($Subconcessionaire2)) {
            $Subconcessionaire2 = 0;
        }


        // $ConcesionarioU = new Concesionario($_SESSION["usuario"], 0);

        $Concesionario->setUsupadreId($_SESSION["usuario"]);

        /* Código para establecer IDs y porcentajes en un objeto Concesionario. */
        $Concesionario->setUsuhijoId($consecutivo_usuario);
        $Concesionario->setusupadre2Id($Subconcessionaire);
        $Concesionario->setusupadre3Id($Subconcessionaire2);
        $Concesionario->setusupadre4Id(0);
        $Concesionario->setPorcenhijo(0);
        $Concesionario->setPorcenpadre1(0);

        /* Se configuran parámetros iniciales en el objeto "Concesionario" para su uso posterior. */
        $Concesionario->setPorcenpadre2(0);
        $Concesionario->setPorcenpadre3(0);
        $Concesionario->setPorcenpadre4(0);
        $Concesionario->setProdinternoId(0);
        $Concesionario->setMandante($Partner);
        $Concesionario->setUsucreaId($_SESSION['usuario']);

        /* asigna el ID de usuario y establece el estado del concesionario como activo. */
        $Concesionario->setUsumodifId($_SESSION['usuario']);
        $Concesionario->setEstado("A");
    } elseif ($_SESSION["win_perfil"] == "CONCESIONARIO2") {

        /* verifica un estado de sesión y asigna valores a un objeto de usuario. */
        if ($_SESSION["mandante"] == 8) {
            $Usuario->estado = 'I';
            $Usuario->estadoValida = 'N';
        }
        $UsuarioPerfil = new UsuarioPerfil();
        $UsuarioPerfil->usuarioId = $consecutivo_usuario;

        /* Se configura un perfil de usuario y se valida un subconcesionario. */
        $UsuarioPerfil->perfilId = 'PUNTOVENTA';
        $UsuarioPerfil->mandante = $Partner;
        $UsuarioPerfil->pais = 'S';
        $UsuarioPerfil->global = 'N';

        if (!is_numeric($Subconcessionaire2)) {
            $Subconcessionaire2 = 0;
        }


        /* asigna IDs a un objeto Concesionario usando valores de sesión y variables. */
        $ConcesionarioU = new Concesionario($_SESSION["usuario"], 0);

        $Concesionario->setUsupadreId($ConcesionarioU->getUsupadreId());
        $Concesionario->setUsuhijoId($consecutivo_usuario);
        $Concesionario->setusupadre2Id($_SESSION["usuario"]);
        $Concesionario->setusupadre3Id($Subconcessionaire2);

        /* establece valores cero para múltiples propiedades de un objeto "Concesionario". */
        $Concesionario->setusupadre4Id(0);
        $Concesionario->setPorcenhijo(0);
        $Concesionario->setPorcenpadre1(0);
        $Concesionario->setPorcenpadre2(0);
        $Concesionario->setPorcenpadre3(0);
        $Concesionario->setPorcenpadre4(0);

        /* establece propiedades de un objeto "Concesionario" con valores específicos. */
        $Concesionario->setProdinternoId(0);
        $Concesionario->setMandante($Partner);
        $Concesionario->setUsucreaId($_SESSION['usuario']);
        $Concesionario->setUsumodifId($_SESSION['usuario']);
        $Concesionario->setEstado("A");
    } elseif ($_SESSION["win_perfil"] == "CONCESIONARIO3") {

        /* asigna estados a un usuario basado en su "mandante" y crea un perfil. */
        if ($_SESSION["mandante"] == 8) {
            $Usuario->estado = 'I';
            $Usuario->estadoValida = 'N';
        }
        $UsuarioPerfil = new UsuarioPerfil();
        $UsuarioPerfil->usuarioId = $consecutivo_usuario;

        /* Se asignan valores a un perfil y se instancia un concesionario. */
        $UsuarioPerfil->perfilId = 'PUNTOVENTA';
        $UsuarioPerfil->mandante = $Partner;
        $UsuarioPerfil->pais = 'S';
        $UsuarioPerfil->global = 'N';

        $ConcesionarioU = new Concesionario($_SESSION["usuario"], 0);


        /* Asigna identificadores y porcentajes a un objeto "Concesionario". */
        $Concesionario->setUsupadreId($ConcesionarioU->getUsupadreId());
        $Concesionario->setUsuhijoId($consecutivo_usuario);
        $Concesionario->setusupadre2Id($ConcesionarioU->getUsupadre2Id());
        $Concesionario->setusupadre3Id($_SESSION["usuario"]);
        $Concesionario->setusupadre4Id(0);
        $Concesionario->setPorcenhijo(0);

        /* Se establecen valores iniciales para porcentajes y asigna un socio al concesionario. */
        $Concesionario->setPorcenpadre1(0);
        $Concesionario->setPorcenpadre2(0);
        $Concesionario->setPorcenpadre3(0);
        $Concesionario->setPorcenpadre4(0);
        $Concesionario->setProdinternoId(0);
        $Concesionario->setMandante($Partner);

        /* Se asignan valores al concesionario según el usuario de sesión y estado activo. */
        $Concesionario->setUsucreaId($_SESSION['usuario']);
        $Concesionario->setUsumodifId($_SESSION['usuario']);
        $Concesionario->setEstado("A");
    } else {


        /* asigna 0 si los valores no son numéricos. */
        if (!is_numeric($Concessionaire)) {
            $Concessionaire = 0;
        }

        if (!is_numeric($Subconcessionaire)) {
            $Subconcessionaire = 0;
        }


        /* Asigna 0 a Subconcessionaire2 si no es numérico y crea un perfil de usuario. */
        if (!is_numeric($Subconcessionaire2)) {
            $Subconcessionaire2 = 0;
        }
        $UsuarioPerfil = new UsuarioPerfil();
        $UsuarioPerfil->usuarioId = $consecutivo_usuario;
        $UsuarioPerfil->perfilId = 'PUNTOVENTA';

        /* Se asignan valores a un perfil de usuario y se configuran IDs de concesionario. */
        $UsuarioPerfil->mandante = $Partner;
        $UsuarioPerfil->pais = 'S';
        $UsuarioPerfil->global = 'N';

        $Concesionario->setUsupadreId($Concessionaire);
        $Concesionario->setUsuhijoId($consecutivo_usuario);

        /* Configura IDs y porcentajes para un concesionario utilizando datos de sub-concesionarios. */
        $Concesionario->setusupadre2Id($Subconcessionaire);
        $Concesionario->setusupadre3Id($Subconcessionaire2);
        $Concesionario->setusupadre4Id(0);
        $Concesionario->setPorcenhijo(0);
        $Concesionario->setPorcenpadre1(0);
        $Concesionario->setPorcenpadre2(0);

        /* configura propiedades de un objeto `Concesionario` con valores específicos. */
        $Concesionario->setPorcenpadre3(0);
        $Concesionario->setPorcenpadre4(0);
        $Concesionario->setProdinternoId(0);
        $Concesionario->setMandante($Partner);
        $Concesionario->setUsucreaId($_SESSION['usuario']);
        $Concesionario->setUsumodifId($_SESSION['usuario']);

        /* establece el estado del concesionario como "A". */
        $Concesionario->setEstado("A");
    }


    /* Se crea un objeto UsuarioPremiomax y se inicializan sus propiedades usuarioId y premioMax. */
    $UsuarioPremiomax = new UsuarioPremiomax();


    $UsuarioPremiomax->usuarioId = $consecutivo_usuario;

    $UsuarioPremiomax->premioMax = 0;


    /* Inicializa propiedades del objeto UsuarioPremiomax con valores por defecto. */
    $UsuarioPremiomax->usumodifId = 0;

    $UsuarioPremiomax->fechaModif = "";

    $UsuarioPremiomax->cantLineas = 0;

    $UsuarioPremiomax->premioMax1 = 0;


    /* Inicializa variables de premio y establece una apuesta mínima por defecto. */
    $UsuarioPremiomax->premioMax2 = 0;

    $UsuarioPremiomax->premioMax3 = 0;

    if ($MinimumBet == "") {
        $MinimumBet = "0";
    }


    /* asigna valores a las propiedades de un objeto UsuarioPremiomax. */
    $UsuarioPremiomax->apuestaMin = $MinimumBet;

    $UsuarioPremiomax->valorDirecto = 0;

    $UsuarioPremiomax->premioDirecto = 0;

    $UsuarioPremiomax->mandante = $Partner;


    /* Se configura un objeto con propiedades relacionadas a un usuario y sus opciones. */
    $UsuarioPremiomax->optimizarParrilla = "N";

    $UsuarioPremiomax->textoOp1 = "";

    $UsuarioPremiomax->textoOp2 = "";

    $UsuarioPremiomax->urlOp2 = "";


    /* Inicializa propiedades del objeto UsuarioPremiomax con valores predeterminados. */
    $UsuarioPremiomax->textoOp3 = 0;

    $UsuarioPremiomax->urlOp3 = 0;

    $UsuarioPremiomax->valorEvento = 0;

    $UsuarioPremiomax->valorDiario = $DailyLimit;


    /* Se crea un objeto PuntoVenta con atributos de descripción, contacto, ciudad y dirección. */
    $PuntoVenta = new PuntoVenta();
    $PuntoVenta->descripcion = $Name;
    $PuntoVenta->nombreContacto = $ManagerName;
    $PuntoVenta->ciudadId = $CityId->Id;
    $PuntoVenta->ciudadId = $CityId;
    $PuntoVenta->direccion = $Address;

    /* Asignación de valores a propiedades del objeto PuntoVenta en PHP. */
    $PuntoVenta->barrio = $District;
    $PuntoVenta->telefono = $Phone;
    $PuntoVenta->email = $Email;
    $PuntoVenta->periodicidadId = 0;
    $PuntoVenta->clasificador1Id = 0;
    $PuntoVenta->clasificador2Id = 0;

    /* Inicializa propiedades de un objeto PuntoVenta con valores predeterminados y variables. */
    $PuntoVenta->clasificador3Id = 0;
    $PuntoVenta->valorRecarga = 0;
    $PuntoVenta->valorCupo = '0';
    $PuntoVenta->valorCupo2 = $DailyQuotaReloads;
    $PuntoVenta->porcenComision = '0';
    $PuntoVenta->porcenComision2 = '0';

    /* Se asignan valores a propiedades del objeto PuntoVenta en un sistema de ventas. */
    $PuntoVenta->estado = 'A';
    $PuntoVenta->usuarioId = '0';
    $PuntoVenta->mandante = $Partner;
    //$PuntoVenta->moneda = $CurrencyId;
    $PuntoVenta->moneda = $CurrencyId;
    $PuntoVenta->idioma = $PreferredLanguage;
    //$PuntoVenta->cupoRecarga = $DailyQuotaReloads;

    /* Inicializa variables relacionadas con el sistema de puntos y recibos de premio. */
    $PuntoVenta->cupoRecarga = 0;
    $PuntoVenta->creditosBase = 0;
    $PuntoVenta->creditos = 0;
    $PuntoVenta->creditosAnt = 0;
    $PuntoVenta->creditosBaseAnt = 0;

    $PuntoVenta->headerRecibopagopremio = $HeaderPrintPrizePaymentReceipt;

    /* Se configuran parámetros de un sistema de punto de venta relacionados con pagos y retiros. */
    $PuntoVenta->footerRecibopagopremio = $FooterPrintPrizePaymentReceipt;
    $PuntoVenta->headerRecibopagoretiro = $HeaderPrintRetirementPaymentReceipt;
    $PuntoVenta->footerRecibopagoretiro = $FooterPrintRetirementPaymentReceipt;
    $PuntoVenta->tipoTienda = $BetShopType;
    $PuntoVenta->impuestoPagopremio = $PrizePaymentTa;
    $PuntoVenta->clasificador3Id = $TypeEstablishment;

    /* Asigna valores a propiedades de un objeto PuntoVenta en PHP. */
    $PuntoVenta->clasificador4Id = $Zone;

    $PuntoVenta->usuarioId = $consecutivo_usuario;
    $PuntoVenta->cedula = $Document;
    $PuntoVenta->identificacionIp = $IPIdentification;

    $PuntoVenta->facebook = $Facebook;

    /* Asigna verificaciones y datos de redes sociales a un objeto PuntoVenta. */
    $PuntoVenta->facebookVerificacion = $FacebookVerification;
    $PuntoVenta->instagram = $Instagram;
    $PuntoVenta->instagramVerificacion = $InstagramVerification;
    $PuntoVenta->whatsApp = $WhatsApp;
    $PuntoVenta->whatsAppVerificacion = $WhatsAppVerification;
    $PuntoVenta->otraRedesSocial = $OtherSocialMedia;

    /* Asigna valores a propiedades del objeto "PuntoVenta". */
    $PuntoVenta->otraRedesSocialVerificacion = $OtherSocialMediaVerification;
    $PuntoVenta->PhysicalPrize = $PhysicalPrize;

    if ($Partner == "8") {

        /* Se define la región "AUSTRO" según el departamento del punto de venta. */
        $Ciudad = new \Backend\dto\Ciudad($PuntoVenta->ciudadId);

        $Departamento = new \Backend\dto\Departamento($Ciudad->deptoId);


        if (strtoupper($Departamento->getDeptoNom()) == "AZUAY" || strtoupper($Departamento->getDeptoNom()) == "EL ORO" || strtoupper($Departamento->getDeptoNom()) == "CAÑAR" || strtoupper($Departamento->getDeptoNom()) == "LOJA" || strtoupper($Departamento->getDeptoNom()) == "ZAMORA" || strtoupper($Departamento->getDeptoNom()) == "MORONA SANTIAGO") {
            $Region = "AUSTRO";

        }

        /* verifica el nombre del departamento y asigna una región correspondiente. */
        if (strtoupper($Departamento->getDeptoNom()) == "ORELLANA" || strtoupper($Departamento->getDeptoNom()) == "SUCUMBIOS" || strtoupper($Departamento->getDeptoNom()) == "PICHINCHA" || strtoupper($Departamento->getDeptoNom()) == "ESMERALDAS" || strtoupper($Departamento->getDeptoNom()) == "NAPO" || strtoupper($Departamento->getDeptoNom()) == "SANTO DOMINGO" || strtoupper($Departamento->getDeptoNom()) == "TUNGURAHUA" || strtoupper($Departamento->getDeptoNom()) == "COTOPAXI" || strtoupper($Departamento->getDeptoNom()) == "IMBABURA" || strtoupper($Departamento->getDeptoNom()) == "CARCHI" || strtoupper($Departamento->getDeptoNom()) == "CHIMBORAZO" || strtoupper($Departamento->getDeptoNom()) == "BOLIVAR") {

            $Region = "CENTRO";

        }

        if (strtoupper($Departamento->getDeptoNom()) === "GUAYAS" || strtoupper($Departamento->getDeptoNom()) == "SANTA ELENA" || strtoupper($Departamento->getDeptoNom()) == "MANABI" || strtoupper($Departamento->getDeptoNom()) == "LOS RIOS" || strtoupper($Departamento->getDeptoNom()) == "GALAPAGOS") {
            $Region = "COSTA";

        }


        /* Asigna una región específica al perfil del usuario en la aplicación. */
        $UsuarioPerfil->region = $Region;


    }


    /* Se crean instancias de DAOs para gestionar usuarios y concesionarios en MySQL. */
    $UsuarioConfigMySqlDAO = new UsuarioConfigMySqlDAO($UsuarioMySqlDAO->getTransaction());

    $ConcesionarioMySqlDAO = new ConcesionarioMySqlDAO($UsuarioMySqlDAO->getTransaction());
    $Concesionario->setUsumodifId($_SESSION['usuario']);
    $ConcesionarioMySqlDAO->insert($Concesionario);

    $UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO($UsuarioMySqlDAO->getTransaction());


    /* Inserta datos de usuario en múltiples tablas utilizando DAO en una base de datos MySQL. */
    $UsuarioPerfilMySqlDAO->insert($UsuarioPerfil);

    $UsuarioPremiomaxMySqlDAO = new UsuarioPremiomaxMySqlDAO($UsuarioMySqlDAO->getTransaction());

    $UsuarioPremiomaxMySqlDAO->insert($UsuarioPremiomax);

    $UsuarioConfigMySqlDAO->insert($UsuarioConfig);


    /* Inserta un punto de venta y actualiza la clave del usuario en la base de datos. */
    $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($UsuarioMySqlDAO->getTransaction());
    $PuntoVentaMySqlDAO->insert($PuntoVenta);


    $UsuarioMySqlDAO->getTransaction()->commit();

    $UsuarioMySqlDAO->updateClave($Usuario, $Password);


    /* Actualiza el ID de punto de venta del usuario en la base de datos. */
    $Usuario->puntoventaId = $consecutivo_usuario;
    $UsuarioMySqlDAO2 = new UsuarioMySqlDAO();
    $UsuarioMySqlDAO2->update($Usuario);
    $UsuarioMySqlDAO2->getTransaction()->commit();

    if ($_SESSION['usuario'] == '546960') {

        /* Se define un concesionario y se establecen niveles de comisión para apuestas. */
        $ConcesionarioU = $Concesionario;

        $Clasificador = new Clasificador("", "SPORTPV");
        $ComissionLevelBetShop = 25;
        $ComissionLevel1 = 10;
        $ComissionLevel2 = 0;

        /* Inicializa comisiones y crea un objeto 'Concesionario' con un ID de padre. */
        $ComissionLevel3 = 0;
        $ComissionLevel4 = 0;

        $Concesionario = new Concesionario();

        $Concesionario->setUsupadreId($ConcesionarioU->getUsupadreId());

        /* Asignación de IDs y porcentajes de comisión a un objeto "Concesionario". */
        $Concesionario->setUsuhijoId($ConcesionarioU->getUsuhijoId());
        $Concesionario->setusupadre2Id($ConcesionarioU->getUsupadre2Id());
        $Concesionario->setusupadre3Id($ConcesionarioU->getUsupadre3Id());
        $Concesionario->setusupadre4Id($ConcesionarioU->getUsupadre4Id());
        $Concesionario->setPorcenhijo($ComissionLevelBetShop);
        $Concesionario->setPorcenpadre1($ComissionLevel1);

        /* establece propiedades en un objeto 'Concesionario' con valores específicos. */
        $Concesionario->setPorcenpadre2($ComissionLevel2);
        $Concesionario->setPorcenpadre3($ComissionLevel3);
        $Concesionario->setPorcenpadre4($ComissionLevel4);
        $Concesionario->setProdinternoId($Clasificador->clasificadorId);
        $Concesionario->setEstado('A');
        $Concesionario->setMandante(0);

        /* Se establece un concesionario con el usuario actual en la sesión y se inserta. */
        $Concesionario->setUsucreaId(0);
        $Concesionario->setUsumodifId(0);

        $ConcesionarioMySqlDAO = new ConcesionarioMySqlDAO();
        $Concesionario->setUsumodifId($_SESSION['usuario']);
        $ConcesionarioMySqlDAO->insert($Concesionario);

        /* realiza una transacción y define niveles de comisión en un sistema. */
        $ConcesionarioMySqlDAO->getTransaction()->commit();

        $Clasificador = new Clasificador("", "SPORTNGRAFF");
        $ComissionLevelBetShop = 10;
        $ComissionLevel1 = 5;
        $ComissionLevel2 = 0;

        /* Inicializa variables de comisión y asigna un ID de usuario a un concesionario. */
        $ComissionLevel3 = 0;
        $ComissionLevel4 = 0;

        $Concesionario = new Concesionario();

        $Concesionario->setUsupadreId($ConcesionarioU->getUsupadreId());

        /* Se establecen variables de un objeto "Concesionario" basado en otro objeto "ConcesionarioU". */
        $Concesionario->setUsuhijoId($ConcesionarioU->getUsuhijoId());
        $Concesionario->setusupadre2Id($ConcesionarioU->getUsupadre2Id());
        $Concesionario->setusupadre3Id($ConcesionarioU->getUsupadre3Id());
        $Concesionario->setusupadre4Id($ConcesionarioU->getUsupadre4Id());
        $Concesionario->setPorcenhijo($ComissionLevelBetShop);
        $Concesionario->setPorcenpadre1($ComissionLevel1);

        /* Se configuran permisos y estados del concesionario en el sistema. */
        $Concesionario->setPorcenpadre2($ComissionLevel2);
        $Concesionario->setPorcenpadre3($ComissionLevel3);
        $Concesionario->setPorcenpadre4($ComissionLevel4);
        $Concesionario->setProdinternoId($Clasificador->clasificadorId);
        $Concesionario->setEstado('A');
        $Concesionario->setMandante(0);

        /* Establece IDs de usuario y guarda un objeto Concesionario en la base de datos. */
        $Concesionario->setUsucreaId(0);
        $Concesionario->setUsumodifId(0);

        $ConcesionarioMySqlDAO = new ConcesionarioMySqlDAO();
        $Concesionario->setUsumodifId($_SESSION['usuario']);
        $ConcesionarioMySqlDAO->insert($Concesionario);

        /* Código que gestiona una transacción y establece niveles de comisión en un clasificador. */
        $ConcesionarioMySqlDAO->getTransaction()->commit();

        $Clasificador = new Clasificador("", "DEPOSITOPV");
        $ComissionLevelBetShop = 3;
        $ComissionLevel1 = 2;
        $ComissionLevel2 = 0;

        /* Inicializa comisiones y configura el ID del usuario padre en un concesionario. */
        $ComissionLevel3 = 0;
        $ComissionLevel4 = 0;

        $Concesionario = new Concesionario();

        $Concesionario->setUsupadreId($ConcesionarioU->getUsupadreId());

        /* establece atributos de un objeto Concesionario utilizando datos de otro objeto. */
        $Concesionario->setUsuhijoId($ConcesionarioU->getUsuhijoId());
        $Concesionario->setusupadre2Id($ConcesionarioU->getUsupadre2Id());
        $Concesionario->setusupadre3Id($ConcesionarioU->getUsupadre3Id());
        $Concesionario->setusupadre4Id($ConcesionarioU->getUsupadre4Id());
        $Concesionario->setPorcenhijo($ComissionLevelBetShop);
        $Concesionario->setPorcenpadre1($ComissionLevel1);

        /* establece diferentes porcentajes y propiedades para un concesionario en PHP. */
        $Concesionario->setPorcenpadre2($ComissionLevel2);
        $Concesionario->setPorcenpadre3($ComissionLevel3);
        $Concesionario->setPorcenpadre4($ComissionLevel4);
        $Concesionario->setProdinternoId($Clasificador->clasificadorId);
        $Concesionario->setEstado('A');
        $Concesionario->setMandante(0);

        /* establece usuario creador/modificador y luego inserta un concesionario en base de datos. */
        $Concesionario->setUsucreaId(0);
        $Concesionario->setUsumodifId(0);

        $ConcesionarioMySqlDAO = new ConcesionarioMySqlDAO();
        $Concesionario->setUsumodifId($_SESSION['usuario']);
        $ConcesionarioMySqlDAO->insert($Concesionario);

        /* gestiona transacciones y define comisiones en un concesionario. */
        $ConcesionarioMySqlDAO->getTransaction()->commit();

        $Clasificador = new Clasificador("", "RETIROSPV");
        $ComissionLevelBetShop = 1;
        $ComissionLevel1 = 0;
        $ComissionLevel2 = 0;

        /* Se inicializan comisiones y se establece el ID del usuario padre en Concesionario. */
        $ComissionLevel3 = 0;
        $ComissionLevel4 = 0;

        $Concesionario = new Concesionario();

        $Concesionario->setUsupadreId($ConcesionarioU->getUsupadreId());

        /* asigna valores a propiedades del objeto $Concesionario desde $ConcesionarioU. */
        $Concesionario->setUsuhijoId($ConcesionarioU->getUsuhijoId());
        $Concesionario->setusupadre2Id($ConcesionarioU->getUsupadre2Id());
        $Concesionario->setusupadre3Id($ConcesionarioU->getUsupadre3Id());
        $Concesionario->setusupadre4Id($ConcesionarioU->getUsupadre4Id());
        $Concesionario->setPorcenhijo($ComissionLevelBetShop);
        $Concesionario->setPorcenpadre1($ComissionLevel1);

        /* configura propiedades de un objeto "Concesionario" usando diferentes métodos. */
        $Concesionario->setPorcenpadre2($ComissionLevel2);
        $Concesionario->setPorcenpadre3($ComissionLevel3);
        $Concesionario->setPorcenpadre4($ComissionLevel4);
        $Concesionario->setProdinternoId($Clasificador->clasificadorId);
        $Concesionario->setEstado('A');
        $Concesionario->setMandante(0);

        /* Establece IDs de usuario y guarda un objeto 'Concesionario' en la base de datos. */
        $Concesionario->setUsucreaId(0);
        $Concesionario->setUsumodifId(0);

        $ConcesionarioMySqlDAO = new ConcesionarioMySqlDAO();
        $Concesionario->setUsumodifId($_SESSION['usuario']);
        $ConcesionarioMySqlDAO->insert($Concesionario);

        /* finaliza una transacción en la base de datos usando MySQL. */
        $ConcesionarioMySqlDAO->getTransaction()->commit();


    }

    if ($_SESSION['usuario'] == '767291') {

        /* inicializa variables relacionadas con concesionarios y niveles de comisión. */
        $ConcesionarioU = $Concesionario;

        $Clasificador = new Clasificador("", "SPORTPV");
        $ComissionLevelBetShop = 25;
        $ComissionLevel1 = 10;
        $ComissionLevel2 = 0;

        /* Inicializa comisiones y crea un objeto Concesionario configurando su ID de usuario padre. */
        $ComissionLevel3 = 0;
        $ComissionLevel4 = 0;

        $Concesionario = new Concesionario();

        $Concesionario->setUsupadreId($ConcesionarioU->getUsupadreId());

        /* Se asignan propiedades de un objeto a otro en el contexto de concesionarios. */
        $Concesionario->setUsuhijoId($ConcesionarioU->getUsuhijoId());
        $Concesionario->setusupadre2Id($ConcesionarioU->getUsupadre2Id());
        $Concesionario->setusupadre3Id($ConcesionarioU->getUsupadre3Id());
        $Concesionario->setusupadre4Id($ConcesionarioU->getUsupadre4Id());
        $Concesionario->setPorcenhijo($ComissionLevelBetShop);
        $Concesionario->setPorcenpadre1($ComissionLevel1);

        /* Se asignan valores a propiedades de un objeto "Concesionario". */
        $Concesionario->setPorcenpadre2($ComissionLevel2);
        $Concesionario->setPorcenpadre3($ComissionLevel3);
        $Concesionario->setPorcenpadre4($ComissionLevel4);
        $Concesionario->setProdinternoId($Clasificador->clasificadorId);
        $Concesionario->setEstado('A');
        $Concesionario->setMandante(0);

        /* Establece IDs de usuario y guarda un registro en la base de datos. */
        $Concesionario->setUsucreaId(0);
        $Concesionario->setUsumodifId(0);

        $ConcesionarioMySqlDAO = new ConcesionarioMySqlDAO();
        $Concesionario->setUsumodifId($_SESSION['usuario']);
        $ConcesionarioMySqlDAO->insert($Concesionario);

        /* realiza una transacción y define variables para comisiones. */
        $ConcesionarioMySqlDAO->getTransaction()->commit();

        $Clasificador = new Clasificador("", "SPORTNGRAFF");
        $ComissionLevelBetShop = 10;
        $ComissionLevel1 = 5;
        $ComissionLevel2 = 0;

        /* Inicializa comisiones y establece el ID del usuario padre en un concesionario. */
        $ComissionLevel3 = 0;
        $ComissionLevel4 = 0;

        $Concesionario = new Concesionario();

        $Concesionario->setUsupadreId($ConcesionarioU->getUsupadreId());

        /* asigna identificadores y porcentajes desde un objeto a otro. */
        $Concesionario->setUsuhijoId($ConcesionarioU->getUsuhijoId());
        $Concesionario->setusupadre2Id($ConcesionarioU->getUsupadre2Id());
        $Concesionario->setusupadre3Id($ConcesionarioU->getUsupadre3Id());
        $Concesionario->setusupadre4Id($ConcesionarioU->getUsupadre4Id());
        $Concesionario->setPorcenhijo($ComissionLevelBetShop);
        $Concesionario->setPorcenpadre1($ComissionLevel1);

        /* Se configuran propiedades del objeto 'Concesionario' con valores específicos y estado activo. */
        $Concesionario->setPorcenpadre2($ComissionLevel2);
        $Concesionario->setPorcenpadre3($ComissionLevel3);
        $Concesionario->setPorcenpadre4($ComissionLevel4);
        $Concesionario->setProdinternoId($Clasificador->clasificadorId);
        $Concesionario->setEstado('A');
        $Concesionario->setMandante(0);

        /* Establece IDs de usuario y luego inserta un concesionario en la base de datos. */
        $Concesionario->setUsucreaId(0);
        $Concesionario->setUsumodifId(0);

        $ConcesionarioMySqlDAO = new ConcesionarioMySqlDAO();
        $Concesionario->setUsumodifId($_SESSION['usuario']);
        $ConcesionarioMySqlDAO->insert($Concesionario);

        /* Se confirma una transacción y se inicializan clasificador y niveles de comisión. */
        $ConcesionarioMySqlDAO->getTransaction()->commit();

        $Clasificador = new Clasificador("", "DEPOSITOPV");
        $ComissionLevelBetShop = 3;
        $ComissionLevel1 = 2;
        $ComissionLevel2 = 0;

        /* Inicializa variables y configura un concesionario con ID del padre correspondiente. */
        $ComissionLevel3 = 0;
        $ComissionLevel4 = 0;

        $Concesionario = new Concesionario();

        $Concesionario->setUsupadreId($ConcesionarioU->getUsupadreId());

        /* Asignación de IDs y comisiones en un objeto "Concesionario" desde "ConcesionarioU". */
        $Concesionario->setUsuhijoId($ConcesionarioU->getUsuhijoId());
        $Concesionario->setusupadre2Id($ConcesionarioU->getUsupadre2Id());
        $Concesionario->setusupadre3Id($ConcesionarioU->getUsupadre3Id());
        $Concesionario->setusupadre4Id($ConcesionarioU->getUsupadre4Id());
        $Concesionario->setPorcenhijo($ComissionLevelBetShop);
        $Concesionario->setPorcenpadre1($ComissionLevel1);

        /* Configuración de comisiones y estado para un concesionario en un sistema. */
        $Concesionario->setPorcenpadre2($ComissionLevel2);
        $Concesionario->setPorcenpadre3($ComissionLevel3);
        $Concesionario->setPorcenpadre4($ComissionLevel4);
        $Concesionario->setProdinternoId($Clasificador->clasificadorId);
        $Concesionario->setEstado('A');
        $Concesionario->setMandante(0);

        /* Se establece usuario creador y modificador antes de insertar en la base de datos. */
        $Concesionario->setUsucreaId(0);
        $Concesionario->setUsumodifId(0);

        $ConcesionarioMySqlDAO = new ConcesionarioMySqlDAO();
        $Concesionario->setUsumodifId($_SESSION['usuario']);
        $ConcesionarioMySqlDAO->insert($Concesionario);

        /* realiza una transacción y establece niveles de comisión para "RETIROSPV". */
        $ConcesionarioMySqlDAO->getTransaction()->commit();

        $Clasificador = new Clasificador("", "RETIROSPV");
        $ComissionLevelBetShop = 1;
        $ComissionLevel1 = 0;
        $ComissionLevel2 = 0;

        /* Inicializa comisiones y establece ID de usuario padre en un objeto Concesionario. */
        $ComissionLevel3 = 0;
        $ComissionLevel4 = 0;

        $Concesionario = new Concesionario();

        $Concesionario->setUsupadreId($ConcesionarioU->getUsupadreId());

        /* Asignación de IDs y porcentajes entre objetos relacionados en un concesionario. */
        $Concesionario->setUsuhijoId($ConcesionarioU->getUsuhijoId());
        $Concesionario->setusupadre2Id($ConcesionarioU->getUsupadre2Id());
        $Concesionario->setusupadre3Id($ConcesionarioU->getUsupadre3Id());
        $Concesionario->setusupadre4Id($ConcesionarioU->getUsupadre4Id());
        $Concesionario->setPorcenhijo($ComissionLevelBetShop);
        $Concesionario->setPorcenpadre1($ComissionLevel1);

        /* Configura valores de un concesionario, incluyendo comisiones, estado y mandante. */
        $Concesionario->setPorcenpadre2($ComissionLevel2);
        $Concesionario->setPorcenpadre3($ComissionLevel3);
        $Concesionario->setPorcenpadre4($ComissionLevel4);
        $Concesionario->setProdinternoId($Clasificador->clasificadorId);
        $Concesionario->setEstado('A');
        $Concesionario->setMandante(0);

        /* Se establece usuario creador y modificador, luego se inserta el concesionario en base de datos. */
        $Concesionario->setUsucreaId(0);
        $Concesionario->setUsumodifId(0);

        $ConcesionarioMySqlDAO = new ConcesionarioMySqlDAO();
        $Concesionario->setUsumodifId($_SESSION['usuario']);
        $ConcesionarioMySqlDAO->insert($Concesionario);

        /* confirma una transacción en la base de datos utilizando MySQL. */
        $ConcesionarioMySqlDAO->getTransaction()->commit();


    }

    if ($Partner == 8) {
        try {

            /* Asigna un asunto de registro dependiendo del idioma del usuario. */
            $subject = '';
            switch ($Usuario->idioma) {
                case 'EN':
                    $subject = 'Point of Sale Registration';
                    break;
                case 'PT':
                    $subject = 'Registro no Ponto de Venda';
                    break;
                default:
                    $subject = 'Registro Punto De Venta';
                    break;
            }


            /* genera un correo utilizando un template y reemplaza variables en HTML. */
            $Clasificador = new Clasificador('', 'TEMPPVREG');
            $Template = new Template('', $Partner, $Clasificador->getClasificadorId(), $CountryId, $PreferredLanguage);

            $html = $Template->templateHtml;
            $html = str_replace(['#name#', '#password#'], [$Name, $Password], $html);

            $ConfigurationEnvironment->EnviarCorreoVersion2($Email, '', '', $subject, '', $subject, $html, '', '', '', $Partner);
        } catch (Exception $ex) {
            /* Manejo de excepciones en PHP, finaliza el script al ocurrir un error. */

            die($ex);
        }
    }


    /* Código para registrar auditoría general de un evento en una base de datos. */
    try {
        $AuditoriaGeneral = new AuditoriaGeneral();
        $AuditoriaGeneral->setUsuarioId($_SESSION["usuario2"]);
        $AuditoriaGeneral->setUsuarioIp("");

        $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario2"]);
        $AuditoriaGeneral->setUsuarioSolicitaIp($Global_IP);

        $AuditoriaGeneral->setTipo("betshop_add");
        $AuditoriaGeneral->setEstado("A");
        $AuditoriaGeneral->setValorDespues($Usuario->usuarioId);
        $AuditoriaGeneral->setData(json_encode($params));

        $AuditoriaGeneral->setUsucreaId(0);
        $AuditoriaGeneralMysqlDao = new AuditoriaGeneralMySqlDAO();
        $AuditoriaGeneralMysqlDao->insert($AuditoriaGeneral);
        $AuditoriaGeneralMysqlDao->getTransaction()->commit();
    } catch (Exception $e) {
        /* Manejo de excepciones en PHP para capturar errores sin realizar acciones específicas. */


    }


    /* Asigna el valor de $consecutivo_usuario a la clave "id" en el array $response. */
    $response["id"] = $consecutivo_usuario;

}


/* genera una respuesta estructurada con éxito y sin errores. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;

