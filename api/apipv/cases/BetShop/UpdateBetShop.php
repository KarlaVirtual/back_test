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
use Backend\dto\UsuarioInformacion;
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
use Backend\mysql\PuntoventadimMySqlDAO;
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
use Backend\mysql\UsuarioInformacionMySqlDAO;
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
 * Actualiza un punto de venta (BetShop).
 *
 * Este script permite actualizar la información de un punto de venta, incluyendo datos de usuario,
 * configuraciones, límites de transacciones, redes sociales, y otros parámetros relacionados.
 *
 * @param object $params Objeto que contiene los siguientes valores:
 * @param int $params->CityId ID de la ciudad.
 * @param string $params->Address Dirección del punto de venta.
 * @param int $params->CountryId ID del país.
 * @param int $params->CurrencyId ID de la moneda.
 * @param string $params->PreferredLanguage Idioma preferido.
 * @param string $params->DocumentLegalID ID legal del documento.
 * @param string $params->Email Correo electrónico.
 * @param int $params->GroupId ID del grupo.
 * @param string $params->IP Dirección IP.
 * @param float $params->Latitud Latitud de la ubicación.
 * @param float $params->Longitud Longitud de la ubicación.
 * @param string $params->ManagerDocument Documento del gerente.
 * @param string $params->ManagerName Nombre del gerente.
 * @param string $params->ContactName Nombre del contacto.
 * @param float $params->PrizePaymentTax Impuesto sobre el pago de premios.
 * @param array $params->IdsAllowed IDs permitidos.
 * @param float $params->WithdrawalLimitAllies Límite de retiro para aliados.
 * @param float $params->DepositLimitAllies Límite de depósito para aliados.
 * @param bool $params->IsActiveWithdrawalLimitAllies Activación del límite de retiro para aliados.
 * @param bool $params->IsActiveDepositLimitAllies Activación del límite de depósito para aliados.
 * @param bool $params->IsActivatePayWithdrawalAllies Activación del pago de retiros para aliados.
 * @param string $params->VisitMade Visita realizada.
 * @param string $params->DocumentationReceived Documentación recibida.
 * @param string $params->ManagerPhone Teléfono del gerente.
 * @param string $params->MobilePhone Teléfono móvil.
 * @param string $params->Name Nombre del punto de venta.
 * @param string $params->Description Descripción del punto de venta.
 * @param string $params->Login Login del usuario.
 * @param string $params->Phone Teléfono del punto de venta.
 * @param int $params->RegionId ID de la región.
 * @param string $params->District Distrito del punto de venta.
 * @param bool $params->AllowsRecharges Permite recargas.
 * @param bool $params->PrintReceiptBox Permite imprimir recibos.
 * @param bool $params->ActivateRegistration Permite activar registros.
 * @param bool $params->Lockedsales Ventas bloqueadas.
 * @param string $params->Pinagent PIN del agente.
 * @param string $params->BetShopOwn Propiedad del punto de venta.
 * @param string $params->RepresentLegalDocument Documento legal del representante.
 * @param string $params->RepresentLegalName Nombre legal del representante.
 * @param string $params->RepresentLegalPhone Teléfono legal del representante.
 * @param string $params->Partner Socio.
 * @param string $params->CustomCostCenter Centro de costo personalizado.
 * @param string $params->Account Cuenta contable.
 * @param string $params->AccountClose Cuenta contable de cierre.
 * @param string $params->HeaderPrintPrizePaymentReceipt Encabezado del recibo de pago de premios.
 * @param string $params->FooterPrintPrizePaymentReceipt Pie del recibo de pago de premios.
 * @param string $params->HeaderPrintRetirementPaymentReceipt Encabezado del recibo de pago de retiros.
 * @param string $params->FooterPrintRetirementPaymentReceipt Pie del recibo de pago de retiros.
 * @param string $params->BetShopType Tipo de tienda.
 * @param float $params->PrizePaymentTa Impuesto sobre el pago de premios.
 * @param string $params->TypeEstablishment Tipo de establecimiento.
 * @param string $params->Zone Zona del punto de venta.
 * @param string $params->Facebook URL de Facebook.
 * @param string $params->FacebookVerification Verificación de Facebook.
 * @param string $params->Instagram URL de Instagram.
 * @param string $params->InstagramVerification Verificación de Instagram.
 * @param string $params->WhatsApp Número de WhatsApp.
 * @param string $params->WhatsAppVerification Verificación de WhatsApp.
 * @param string $params->OtherSocialMedia Otras redes sociales.
 * @param string $params->OtherSocialMediaVerification Verificación de otras redes sociales.
 * @param bool $params->ComissionsPayment Pago de comisiones.
 * @param bool $params->PhysicalPrize Premio físico.
 * @param float $params->MinimumBet Apuesta mínima.
 * @param float $params->DailyLimit Límite diario.
 * @param float $params->DailyQuotaReloads Cuota diaria de recargas.
 * @param string $params->Concessionaire Concesionario.
 * @param string $params->Subconcessionaire Subconcesionario.
 * @param string $params->Subconcessionaire2 Segundo subconcesionario.
 * @param float $params->MaximumWithdrawalAmount Monto máximo de retiro.
 * @param float $params->MaximumPrizePaymentAmount Monto máximo de pago de premios.
 * @param string $params->IndividualCalculation Cálculo individual.
 * @param string $params->DragNegatives Arrastra negativos.
 * @param string $params->Note Nota u observación.
 * @param string $params->CodeMincetur Código Mincetur.
 *
 *
 *
 * @return array $response Respuesta con los siguientes valores:
 *  - HasError: Indica si ocurrió un error (boolean).
 *  - AlertType: Tipo de alerta (string).
 *  - AlertMessage: Mensaje de alerta (string).
 *  - ModelErrors: Errores del modelo (array).
 *
 * @throws Exception Si el perfil del usuario no tiene permisos para realizar la acción.
 * @throws Exception Si el login del usuario ya existe.
 * @throws Exception Si ocurre un error en la base de datos al actualizar o insertar datos.
 */


/* inicializa un array y obtiene parámetros de ciudad y dirección. */
$cambiosG = array();

$CityId = $params->CityId;

$Address = $params->Address;
$CityId = $params->CityId;

/* Asigna valores a variables si $CityId está vacío usando parámetros proporcionados. */
if ($CityId == '') {
    $CityId = $params->City;

}
$CountryId = $params->CountryId;
$CurrencyId = $params->CurrencyId;

/* Se asignan valores de parámetros a variables en un script de programación. */
$PreferredLanguage = $params->PreferredLanguage;
$DocumentLegalID = $params->DocumentLegalID;
$Email = $params->Email;
$GroupId = $params->GroupId;
$IP = $params->IP;
$Latitud = $params->Latitud;

/* Asigna valores de parámetros a variables para su uso en un código. */
$Longitud = $params->Longitud;
$ManagerDocument = $params->ManagerDocument;
$ManagerName = $params->ManagerName;
$ManagerName = $params->ContactName;
$PrizePaymentTax = $params->PrizePaymentTax;
$IdsAllowed = $params->IdsAllowed;

/* Asigna parámetros relacionados con límites y activación de transacciones para aliados. */
$WithdrawalLimitAllies = $params->WithdrawalLimitAllies;
$DepositLimitAllies = $params->DepositLimitAllies;
$IsActiveWithdrawalLimitAllies = $params->IsActiveWithdrawalLimitAllies; //boton de retiro activo
$IsActiveDepositLimitAllies = $params->IsActiveDepositLimitAllies; //boton de deposito activo
$IsActivatePayWithdrawalAllies = $params->IsActivatePayWithdrawalAllies;
$IdConcesionarios = implode(",", $IdsAllowed);


/* Asigna valores de parámetros a variables para su uso posterior en código. */
$VisitMade = $params->VisitMade;
$documentationReceived = $params->DocumentationReceived;

$ManagerPhone = $params->ManagerPhone;
$MobilePhone = $params->MobilePhone;
$Name = $params->Name;

/* asigna valores de un objeto `$params` a variables individuales. */
$Description = $params->Description;
$Login = $params->Login;
$Phone = $params->Phone;
$RegionId = $params->RegionId;

$District = $params->District;


/* asigna parámetros de configuración a variables relacionadas con apuestas. */
$CanDeposit = $params->AllowsRecharges;
$CanReceipt = $params->PrintReceiptBox;
$CanActivateRegister = $params->ActivateRegistration;
$Lockedsales = $params->Lockedsales;
$Pinagent = $params->Pinagent;

$BetShopOwn = $params->BetShopOwn;


/* Asignación de parámetros legales y de centro de costo en una variable. */
$RepresentLegalDocument = $params->RepresentLegalDocument;
$RepresentLegalName = $params->RepresentLegalName;
$RepresentLegalPhone = $params->RepresentLegalPhone;
$Partner = $params->Partner;

$CustomCostCenter = $params->CustomCostCenter;

/* Asignación de parámetros para impresión de recibos de pago y cierre de cuentas. */
$Account = $params->Account;
$AccountClose = $params->AccountClose;

$HeaderPrintPrizePaymentReceipt = $params->HeaderPrintPrizePaymentReceipt;
$FooterPrintPrizePaymentReceipt = $params->FooterPrintPrizePaymentReceipt;
$HeaderPrintRetirementPaymentReceipt = $params->HeaderPrintRetirementPaymentReceipt;

/* Variables asignadas del objeto $params para gestionar un sistema de pagos y redes sociales. */
$FooterPrintRetirementPaymentReceipt = $params->FooterPrintRetirementPaymentReceipt;
$BetShopType = $params->BetShopType;
$PrizePaymentTa = $params->PrizePaymentTa;
$TypeEstablishment = $params->TypeEstablishment;
$Zone = $params->Zone;

$Facebook = $params->Facebook;

/* Asignación de variables para verificar y almacenar datos de redes sociales. */
$FacebookVerification = $params->FacebookVerification;
$Instagram = $params->Instagram;
$InstagramVerification = $params->InstagramVerification;
$WhatsApp = $params->WhatsApp;
$WhatsAppVerification = $params->WhatsAppVerification;
$OtherSocialMedia = $params->OtherSocialMedia;

/* Asignación y verificación de parámetros sociales y premios en forma condicional. */
$OtherSocialMediaVerification = $params->OtherSocialMediaVerification;
$ComissionsPayment = $params->ComissionsPayment;
$PhysicalPrize = $params->PhysicalPrize;
$FacebookVerification = ($FacebookVerification == "S") ? "1" : "0";
$InstagramVerification = ($InstagramVerification == "S") ? "1" : "0";
$WhatsAppVerification = ($WhatsAppVerification == "S") ? "1" : "0";

/* Condicionales que transforman valores de verificación social y visitas en variables binarias. */
$OtherSocialMediaVerification = ($OtherSocialMediaVerification == "S") ? "1" : "0";

if ($VisitMade != '') {
    $VisitMade = ($VisitMade == "S") ? "S" : "N";

}


/* verifica si se recibió documentación y asigna "S" o "N". */
if ($documentationReceived != '') {
    $documentationReceived = ($documentationReceived == "S") ? "S" : "N";
}

$Address = $Address;
$CurrencyId = $CurrencyId;

/* asigna valores a variables relacionadas con un usuario. */
$Email = $Email;
$FirstName = $Name;
$Id = $params->Id;
$IsSuspended = false;
$LastLoginIp = "";
$LastLoginLocalDate = "";

/* inicializa variables relacionadas con un usuario en un sistema. */
$LastName = "";
$clave = '';
$SystemName = '';
$UserId = '';
$UserName = $params->UserName;
$Phone = $params->Phone;


/* Se configuran parámetros para apuestas: límite mínimo, diario y concesionarios involucrados. */
$MinimumBet = $params->MinimumBet;
$DailyLimit = $params->DailyLimit;
$DailyQuotaReloads = $params->DailyQuotaReloads;
$Concessionaire = $params->Concessionaire;
$Subconcessionaire = $params->Subconcessionaire;
$Subconcessionaire2 = $params->Subconcessionaire2;


/* asigna valores y ajusta parámetros según condiciones específicas. */
$MaximumWithdrawalAmount = $params->MaximumWithdrawalAmount;
$MaximumPrizePaymentAmount = $params->MaximumPrizePaymentAmount;


$IndividualCalculation = ($params->IndividualCalculation === 'S') ? 'A' : 'I';

if ($Subconcessionaire == "") {
    $Subconcessionaire = 0;
}

/* Asigna 0 a variables si están vacías. */
if ($Subconcessionaire2 == "") {
    $Subconcessionaire2 = 0;
}

if ($Concessionaire == "") {
    $Concessionaire = 0;
}

/* asigna valores binarios a variables según condiciones específicas. */
$Document = $params->Document;
$IPIdentification = $params->IPIdentification;
$IPIdentification = ($IPIdentification == "S") ? "1" : "0";
$DragNegatives = $params->DragNegatives;
$DragNegatives = ($DragNegatives == 'S') ? '1' : '0';
// Valores por defecto no requeridos

$Pinagent = "N";


/* asigna valores "S" o "N" según ciertas condiciones. */
$Note = $params->Note;

/* Cambiamos a valores de base de datos */
$CanReceipt = ($CanReceipt == "S") ? "S" : "N";
$CanDeposit = ($CanDeposit == "S") ? "S" : "N";
$CanActivateRegister = ($CanActivateRegister == "S") ? "S" : "N";

/* Código condicional que asigna valores a variables según sus condiciones actuales. */
$Lockedsales = ($Lockedsales == "S") ? "S" : "N";


$BetShopOwn = ($BetShopOwn == "N") ? "N" : "S";


$Id = $params->Id;


/* Código que asigna límites de retiro y depósito a partir de parámetros dados. */
$IsActivateWithdrawalLimit = $params->IsActivateWithdrawalLimit;
$WithdrawalLimit = $params->WithdrawalLimit;
$IsActivateDepositLimit = $params->IsActivateDepositLimit;
$DepositLimit = $params->DepositLimit;


$Usuario = new Usuario($Id);

$UsuarioPerfil = new UsuarioPerfil($Id);

$ConfigurationEnvironment = new ConfigurationEnvironment();



/* Condicional que verifica si el perfil del usuario es 'USUONLINE'. */
if ($UsuarioPerfil->perfilId == 'USUONLINE') {

    throw new Exception('Permiso denegado', 100035);

} elseif (in_array($UsuarioPerfil->perfilId, array('PUNTOVENTA', 'CAJERO'))) {

    $permission = $ConfigurationEnvironment->checkUserPermission('BetShop/UpdateBetShop', $_SESSION['win_perfil'], $_SESSION['usuario'], 'betShopManagement');

    if (!$permission) throw new Exception('Permiso denegado', 100035);

} elseif (in_array($UsuarioPerfil->perfilId, array('CONCESIONARIO', 'CONCESIONARIO2', 'AFILIADOR'))) {

    throw new Exception('Permiso denegado', 100035);

} else {

    throw new Exception('Permiso denegado', 100035);

}


/* Se instancian objetos de usuario y concesionario según el perfil del usuario. */
$UsuarioPremiomax = new UsuarioPremiomax($Id);
$UsuarioPerfil = new UsuarioPerfil($Id);

if ($UsuarioPerfil->perfilId != 'CAJERO') {
    $Concesionario = new Concesionario($Id, '0');

    $Concesionario2 = new Concesionario($Id, '0');

}


/* Intenta crear un objeto 'PuntoVenta' y maneja posibles excepciones al fallar. */
try {
    $PuntoVenta = new PuntoVenta("", $Id);

} catch (Exception $e) {

}


/* Se inicializan configuraciones y variables para gestionar cambios de usuario y punto de venta. */
$UsuarioConfig = new UsuarioConfig($Id);


$UsuarioPremiomaxCambios = false;
$PuntoVentaCambios = false;
$UsuarioCambios = false;

/* verifica y actualiza la apuesta mínima del usuario si cambia. */
$UsuarioConfigCambios = false;
$ConcesionarioCambios = false;

if ($UsuarioPremiomax->apuestaMin != $MinimumBet && $MinimumBet != "") {

    array_push(
        $cambiosG,
        array(
            'field' => 'apuestaMin',
            'old' => $UsuarioPremiomax->apuestaMin,
            'new' => $MinimumBet
        )
    );
    $UsuarioPremiomax->apuestaMin = $MinimumBet;
    $UsuarioPremiomaxCambios = true;
}


/* actualiza el valor de usuario si difiere del límite diario establecido. */
if ($UsuarioPremiomax->valorDiario != $DailyLimit && $DailyLimit != "") {

    array_push(
        $cambiosG,
        array(
            'field' => 'apuestaMin',
            'old' => $UsuarioPremiomax->valorDiario,
            'new' => $DailyLimit
        )
    );
    $UsuarioPremiomax->valorDiario = $DailyLimit;
    $UsuarioPremiomaxCambios = true;
}


/* Actualiza el valor de 'valorCupo2' y registra cambios si se cumplen condiciones. */
if ($PuntoVenta != null && $PuntoVenta->valorCupo2 != $DailyQuotaReloads && $DailyQuotaReloads != "") {

    array_push(
        $cambiosG,
        array(
            'field' => 'valorCupo2',
            'old' => $PuntoVenta->valorCupo2,
            'new' => $DailyQuotaReloads
        )
    );
    $PuntoVenta->valorCupo2 = $DailyQuotaReloads;
    $PuntoVentaCambios = true;
}


/* Actualiza la dirección de PuntoVenta y registra el cambio si es necesario. */
if ($PuntoVenta != null && $PuntoVenta->direccion != $Address && $Address != "") {

    array_push(
        $cambiosG,
        array(
            'field' => 'direccion',
            'old' => $PuntoVenta->direccion,
            'new' => $Address
        )
    );
    $PuntoVenta->direccion = $Address;
    $PuntoVentaCambios = true;
}


/* actualiza el barrio de un Punto de Venta si es diferente al nuevo. */
if ($PuntoVenta != null && $PuntoVenta->barrio != $District && $District != "") {

    array_push(
        $cambiosG,
        array(
            'field' => 'barrio',
            'old' => $PuntoVenta->barrio,
            'new' => $District
        )
    );
    $PuntoVenta->barrio = $District;
    $PuntoVentaCambios = true;
}


/* Actualiza el teléfono de un punto de venta si es diferente y no vacío. */
if ($PuntoVenta != null && $PuntoVenta->telefono != $Phone && $Phone != "") {

    array_push(
        $cambiosG,
        array(
            'field' => 'telefono',
            'old' => $PuntoVenta->telefono,
            'new' => $Phone
        )
    );
    $PuntoVenta->telefono = $Phone;
    $PuntoVentaCambios = true;
}



/* Actualiza el email de PuntoVenta y registra el cambio si es necesario. */
if ($PuntoVenta != null && $PuntoVenta->email != $Email && $Email != "") {

    array_push(
        $cambiosG,
        array(
            'field' => 'email',
            'old' => $PuntoVenta->email,
            'new' => $Email
        )
    );
    $PuntoVenta->email = $Email;
    $PuntoVentaCambios = true;
}



/* Actualiza la descripción si es diferente y no está vacía, registrando cambios. */
if ($PuntoVenta != null && $PuntoVenta->descripcion != $Description && $Description != "") {

    array_push(
        $cambiosG,
        array(
            'field' => 'descripcion',
            'old' => $PuntoVenta->descripcion,
            'new' => $Description
        )
    );
    $PuntoVenta->descripcion = $Description;
    $PuntoVentaCambios = true;
}



/* Actualiza el nombre del usuario si es diferente y no está vacío. */
if ($Usuario->nombre != $Name && $Name != "") {

    array_push(
        $cambiosG,
        array(
            'field' => 'nombre',
            'old' => $Usuario->nombre,
            'new' => $Name
        )
    );
    $Usuario->nombre = $Name;
    $UsuarioCambios = true;
}



/* Actualiza el nombre de contacto si es diferente al nombre del gerente. */
if ($PuntoVenta != null && $PuntoVenta->nombreContacto != $ManagerName && $ManagerName != "") {

    array_push(
        $cambiosG,
        array(
            'field' => 'nombreContacto',
            'old' => $PuntoVenta->nombreContacto,
            'new' => $ManagerName
        )
    );
    $PuntoVenta->nombreContacto = $ManagerName;
    $PuntoVentaCambios = true;
}


/* Verifica y actualiza el impuesto de pago si es diferente y no nulo. */
if ($PuntoVenta != null && $PuntoVenta->impuestoPagopremio != $PrizePaymentTax && $PrizePaymentTax != "") {

    array_push(
        $cambiosG,
        array(
            'field' => 'impuestoPagopremio',
            'old' => $PuntoVenta->impuestoPagopremio,
            'new' => $PrizePaymentTax
        )
    );
    $PuntoVenta->impuestoPagopremio = $PrizePaymentTax;
    $PuntoVentaCambios = true;
}

/* Verifica cambios en 'cedula' y actualiza si es diferente del documento ingresado. */
if ($Document != '' && $PuntoVenta->cedula != $Document && $Document != null) {

    array_push(
        $cambiosG,
        array(
            'field' => 'cedula',
            'old' => $PuntoVenta->cedula,
            'new' => $Document
        )
    );
    $PuntoVenta->cedula = $Document;
    $PuntoVentaCambios = true;
}


/* verifica cambios en la identificación IP y los registra si hay diferencias. */
if ($IPIdentification != '' && $IPIdentification != $PuntoVenta->identificacionIp && $IPIdentification != null) {

    array_push(
        $cambiosG,
        array(
            'field' => 'identificacionIp',
            'old' => $PuntoVenta->identificacionIp,
            'new' => $IPIdentification
        )
    );
    $PuntoVenta->identificacionIp = $IPIdentification;
    $PuntoVentaCambios = true;
}


/* Actualiza el campo Facebook y registra el cambio si es necesario. */
if ($Facebook != $PuntoVenta->facebook && $Facebook != '' && $Facebook != null) {

    array_push(
        $cambiosG,
        array(
            'field' => 'facebook',
            'old' => $PuntoVenta->facebook,
            'new' => $Facebook
        )
    );
    $PuntoVenta->facebook = $Facebook;
    $PuntoVentaCambios = true;
}


/* Actualiza la verificación de Facebook si es diferente y no está vacía. */
if ($FacebookVerification != $PuntoVenta->facebookVerificacion && $FacebookVerification != '' && $FacebookVerification != null) {

    array_push(
        $cambiosG,
        array(
            'field' => 'facebookVerificacion',
            'old' => $PuntoVenta->facebookVerificacion,
            'new' => $FacebookVerification
        )
    );
    $PuntoVenta->facebookVerificacion = $FacebookVerification;
    $PuntoVentaCambios = true;
}


/* Verifica y actualiza el campo "instagram" si hay cambios en la variable. */
if ($Instagram != $PuntoVenta->instagram && $Instagram != '' && $Instagram != null) {

    array_push(
        $cambiosG,
        array(
            'field' => 'instagram',
            'old' => $PuntoVenta->instagram,
            'new' => $Instagram
        )
    );
    $PuntoVenta->instagram = $Instagram;
    $PuntoVentaCambios = true;
}

/* Compara y actualiza la verificación de Instagram en un punto de venta. */
if ($InstagramVerification != $PuntoVenta->instagramVerificacion && $InstagramVerification != '' && $InstagramVerification != null) {

    array_push(
        $cambiosG,
        array(
            'field' => 'instagramVerificacion',
            'old' => $PuntoVenta->instagramVerificacion,
            'new' => $InstagramVerification
        )
    );
    $PuntoVenta->instagramVerificacion = $InstagramVerification;
    $PuntoVentaCambios = true;
}


/* actualiza el WhatsApp y registra el cambio si es diferente. */
if ($WhatsApp != $PuntoVenta->whatsApp && $WhatsApp != '' && $WhatsApp != null) {

    array_push(
        $cambiosG,
        array(
            'field' => 'whatsApp',
            'old' => $PuntoVenta->whatsApp,
            'new' => $WhatsApp
        )
    );
    $PuntoVenta->whatsApp = $WhatsApp;
    $PuntoVentaCambios = true;
}

/* Verifica cambios en WhatsApp y actualiza si es necesario, registrando las modificaciones. */
if ($WhatsAppVerification != $PuntoVenta->whatsAppVerificacion && $WhatsAppVerification != '' && $WhatsAppVerification != null) {

    array_push(
        $cambiosG,
        array(
            'field' => 'whatsAppVerificacion',
            'old' => $PuntoVenta->whatsAppVerificacion,
            'new' => $WhatsAppVerification
        )
    );
    $PuntoVenta->whatsAppVerificacion = $WhatsAppVerification;
    $PuntoVentaCambios = true;
}


/* Compara y actualiza redes sociales en un objeto si hay cambios. */
if ($OtherSocialMedia != $PuntoVenta->otraRedesSocial && $OtherSocialMedia != '' && $OtherSocialMedia != null) {

    array_push(
        $cambiosG,
        array(
            'field' => 'otraRedesSocial',
            'old' => $PuntoVenta->otraRedesSocial,
            'new' => $OtherSocialMedia
        )
    );
    $PuntoVenta->otraRedesSocial = $OtherSocialMedia;
    $PuntoVentaCambios = true;
}

/* Compara y actualiza la verificación de redes sociales en un objeto. */
if ($OtherSocialMediaVerification != $PuntoVenta->otraRedesSocialVerificacion && $OtherSocialMediaVerification != '' && $OtherSocialMediaVerification != null) {

    array_push(
        $cambiosG,
        array(
            'field' => 'otraRedesSocialVerificacion',
            'old' => $PuntoVenta->otraRedesSocialVerificacion,
            'new' => $OtherSocialMediaVerification
        )
    );
    $PuntoVenta->otraRedesSocialVerificacion = $OtherSocialMediaVerification;
    $PuntoVentaCambios = true;
}


/* Actualiza el campo ciudadId si es diferente y no nulo; registra el cambio. */
if ($PuntoVenta != null && $PuntoVenta->ciudadId != $CityId && $CityId != "") {

    array_push(
        $cambiosG,
        array(
            'field' => 'ciudadId',
            'old' => $PuntoVenta->ciudadId,
            'new' => $CityId
        )
    );
    $PuntoVenta->ciudadId = $CityId;
    $PuntoVentaCambios = true;
}



/* Verifica condiciones y actualiza el campo 'propio' si se cumplen. */
if ($PuntoVenta != null && $PuntoVenta->propio != $BetShopOwn && $BetShopOwn != "") {
    if ($_SESSION["win_perfil"] != "CONCESIONARIO" && $_SESSION["win_perfil"] != "CONCESIONARIO2" && $_SESSION["win_perfil"] != "CONCESIONARIO3" && $_SESSION["win_perfil"] != "PUNTOVENTA" && $_SESSION["win_perfil"] != "USUONLINE") {


        array_push(
            $cambiosG,
            array(
                'field' => 'propio',
                'old' => $PuntoVenta->propio,
                'new' => $BetShopOwn
            )
        );
        $PuntoVenta->propio = $BetShopOwn;
        $PuntoVentaCambios = true;
    }
}

if ($PuntoVenta->ciudadId != "") {

    /* asigna la región "AUSTRO" según el departamento seleccionado. */
    $Ciudad = new \Backend\dto\Ciudad($PuntoVenta->ciudadId);

    $Departamento = new \Backend\dto\Departamento($Ciudad->deptoId);


    if (strtoupper($Departamento->getDeptoNom()) == "AZUAY" || strtoupper($Departamento->getDeptoNom()) == "EL ORO" || strtoupper($Departamento->getDeptoNom()) == "CAÑAR" || strtoupper($Departamento->getDeptoNom()) == "LOJA" || strtoupper($Departamento->getDeptoNom()) == "ZAMORA" || strtoupper($Departamento->getDeptoNom()) == "MORONA SANTIAGO") {
        $Region = "AUSTRO";

    }

    /* determina la región según el nombre del departamento en mayúsculas. */
    if (strtoupper($Departamento->getDeptoNom()) == "ORELLANA" || strtoupper($Departamento->getDeptoNom()) == "SUCUMBIOS" || strtoupper($Departamento->getDeptoNom()) == "PICHINCHA" || strtoupper($Departamento->getDeptoNom()) == "ESMERALDAS" || strtoupper($Departamento->getDeptoNom()) == "NAPO" || strtoupper($Departamento->getDeptoNom()) == "SANTO DOMINGO" || strtoupper($Departamento->getDeptoNom()) == "TUNGURAHUA" || strtoupper($Departamento->getDeptoNom()) == "COTOPAXI" || strtoupper($Departamento->getDeptoNom()) == "IMBABURA" || strtoupper($Departamento->getDeptoNom()) == "CARCHI" || strtoupper($Departamento->getDeptoNom()) == "CHIMBORAZO" || strtoupper($Departamento->getDeptoNom()) == "BOLIVAR") {

        $Region = "CENTRO";

    }

    if (strtoupper($Departamento->getDeptoNom()) === "GUAYAS" || strtoupper($Departamento->getDeptoNom()) == "SANTA ELENA" || strtoupper($Departamento->getDeptoNom()) == "MANABI" || strtoupper($Departamento->getDeptoNom()) == "LOS RIOS" || strtoupper($Departamento->getDeptoNom()) == "GALAPAGOS") {
        $Region = "COSTA";

    }



    /* Actualiza la región del perfil de usuario en la base de datos. */
    $UsuarioPerfil->region = $Region;


    $UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO();
    $UsuarioPerfilMySqlDAO->update($UsuarioPerfil);
    $UsuarioPerfilMySqlDAO->getTransaction()->commit();
}



/* Verifica y actualiza el código personalizado de un punto de venta, registrando cambios. */
if ($CustomCostCenter != $PuntoVenta->codigoPersonalizado && $CustomCostCenter != '' && $CustomCostCenter != null) {
    array_push(
        $cambiosG,
        array(
            'field' => 'codigoPersonalizado',
            'old' => $PuntoVenta->codigoPersonalizado,
            'new' => $CustomCostCenter
        )
    );
    $PuntoVenta->codigoPersonalizado = $CustomCostCenter;
    $PuntoVentaCambios = true;
}


/* Asigna IDs de cuentas contables si son válidos y marca cambios en PuntoVenta. */
if ($Account != '' && $Account != null) {
    $PuntoVenta->cuentacontableId = $Account;
    $PuntoVentaCambios = true;
}

if ($AccountClose != '' && $AccountClose != null) {
    $PuntoVenta->cuentacontablecierreId = $AccountClose;
    $PuntoVentaCambios = true;
}


/* Actualiza el encabezado de recibo si es diferente y no está vacío. */
if ($HeaderPrintPrizePaymentReceipt != $PuntoVenta->headerRecibopagopremio && $HeaderPrintPrizePaymentReceipt != '' && $HeaderPrintPrizePaymentReceipt != null) {
    array_push(
        $cambiosG,
        array(
            'field' => 'headerRecibopagopremio',
            'old' => $PuntoVenta->headerRecibopagopremio,
            'new' => $HeaderPrintPrizePaymentReceipt
        )
    );
    $PuntoVenta->headerRecibopagopremio = $HeaderPrintPrizePaymentReceipt;
    $PuntoVentaCambios = true;
}


/* Condiciona y actualiza el footer del recibo de pago de premios. */
if ($FooterPrintPrizePaymentReceipt != $PuntoVenta->footerRecibopagopremio && $FooterPrintPrizePaymentReceipt != '' && $FooterPrintPrizePaymentReceipt != null) {
    array_push(
        $cambiosG,
        array(
            'field' => 'footerRecibopagopremio',
            'old' => $PuntoVenta->footerRecibopagopremio,
            'new' => $FooterPrintPrizePaymentReceipt
        )
    );
    $PuntoVenta->footerRecibopagopremio = $FooterPrintPrizePaymentReceipt;
    $PuntoVentaCambios = true;
}


/* Se compara un encabezado y se actualiza si es diferente y válido. */
if ($HeaderPrintRetirementPaymentReceipt != $PuntoVenta->headerRecibopagoretiro && $HeaderPrintRetirementPaymentReceipt != '' && $HeaderPrintRetirementPaymentReceipt != null) {
    array_push(
        $cambiosG,
        array(
            'field' => 'headerRecibopagoretiro',
            'old' => $PuntoVenta->headerRecibopagoretiro,
            'new' => $HeaderPrintRetirementPaymentReceipt
        )
    );
    $PuntoVenta->headerRecibopagoretiro = $HeaderPrintRetirementPaymentReceipt;
    $PuntoVentaCambios = true;
}


/* Actualiza el pie de recibo de pago si es diferente y no está vacío. */
if ($FooterPrintRetirementPaymentReceipt != $PuntoVenta->footerRecibopagoretiro && $FooterPrintRetirementPaymentReceipt != '' && $FooterPrintRetirementPaymentReceipt != null) {
    array_push(
        $cambiosG,
        array(
            'field' => 'footerRecibopagoretiro',
            'old' => $PuntoVenta->footerRecibopagoretiro,
            'new' => $FooterPrintRetirementPaymentReceipt
        )
    );
    $PuntoVenta->footerRecibopagoretiro = $FooterPrintRetirementPaymentReceipt;
    $PuntoVentaCambios = true;
}


/* actualiza el tipo de tienda si es diferente y válido. */
if ($BetShopType != $PuntoVenta->tipoTienda && $BetShopType != '' && $BetShopType != null) {
    array_push(
        $cambiosG,
        array(
            'field' => 'tipoTienda',
            'old' => $PuntoVenta->tipoTienda,
            'new' => $BetShopType
        )
    );
    $PuntoVenta->tipoTienda = $BetShopType;
    $PuntoVentaCambios = true;
}


/* Compara y actualiza 'PhysicalPrize' si es diferente y no está vacío. */
if ($PhysicalPrize != $PuntoVenta->PhysicalPrize && $PhysicalPrize != '' && $PhysicalPrize != null) {
    array_push(
        $cambiosG,
        array(
            'field' => 'PhysicalPrize',
            'old' => $PuntoVenta->PhysicalPrize,
            'new' => $PhysicalPrize
        )
    );
    $PuntoVenta->PhysicalPrize = $PhysicalPrize;
    $PuntoVentaCambios = true;
}



/* Actualiza el impuestoPagopremio si es diferente, no vacío y no nulo. */
if ($PrizePaymentTa != $PuntoVenta->impuestoPagopremio && $PrizePaymentTa != '' && $PrizePaymentTa != null) {
    array_push(
        $cambiosG,
        array(
            'field' => 'impuestoPagopremio',
            'old' => $PuntoVenta->impuestoPagopremio,
            'new' => $PrizePaymentTa
        )
    );
    $PuntoVenta->impuestoPagopremio = $PrizePaymentTa;
    $PuntoVentaCambios = true;
}

/* Actualiza el campo 'clasificador3Id' si $TypeEstablishment es diferente y válido. */
if ($TypeEstablishment != $PuntoVenta->clasificador3Id && $TypeEstablishment != '' && $TypeEstablishment != null) {
    array_push(
        $cambiosG,
        array(
            'field' => 'clasificador3Id',
            'old' => $PuntoVenta->clasificador3Id,
            'new' => $TypeEstablishment
        )
    );
    $PuntoVenta->clasificador3Id = $TypeEstablishment;
    $PuntoVentaCambios = true;
}

/* Verifica y actualiza el campo 'clasificador4Id' si el nuevo valor es distinto. */
if ($Zone != $PuntoVenta->clasificador4Id && $Zone != '' && $Zone != null) {
    array_push(
        $cambiosG,
        array(
            'field' => 'clasificador4Id',
            'old' => $PuntoVenta->clasificador4Id,
            'new' => $Zone
        )
    );
    $PuntoVenta->clasificador4Id = $Zone;
    $PuntoVentaCambios = true;
}


/* Actualiza el campo 'arrastraNegativo' y registra el cambio en un array. */
if ($Usuario->arrastraNegativo != $DragNegatives && $DragNegatives != "") {
    array_push(
        $cambiosG,
        array(
            'field' => 'arrastraNegativo',
            'old' => $Usuario->arrastraNegativo,
            'new' => $DragNegatives
        )
    );
    $Usuario->arrastraNegativo = $DragNegatives;
    $UsuarioCambios = true;
}



/* Actualiza el bloqueo de ventas de un usuario y registra los cambios realizados. */
if ($Usuario->bloqueoVentas != $Lockedsales && $Lockedsales != "") {
    array_push(
        $cambiosG,
        array(
            'field' => 'bloqueoVentas',
            'old' => $Usuario->bloqueoVentas,
            'new' => $Lockedsales
        )
    );
    $Usuario->bloqueoVentas = $Lockedsales;
    $UsuarioCambios = true;
}

/* Actualiza el pago de comisiones del usuario si hay un cambio. */
if ($ComissionsPayment != $Usuario->pagoComisiones && $ComissionsPayment != '' && $ComissionsPayment != null) {
    array_push(
        $cambiosG,
        array(
            'field' => 'pagoComisiones',
            'old' => $Usuario->pagoComisiones,
            'new' => $ComissionsPayment
        )
    );
    $Usuario->pagoComisiones = $ComissionsPayment;
    $UsuarioCambios = true;
}


/* Actualiza la longitud de ubicación del usuario si es diferente y válida. */
if ($Usuario->ubicacionLongitud != $Longitud && $Longitud != "") {
    array_push(
        $cambiosG,
        array(
            'field' => 'ubicacionLongitud',
            'old' => $Usuario->ubicacionLongitud,
            'new' => $Longitud
        )
    );
    $Usuario->ubicacionLongitud = $Longitud;
    $UsuarioCambios = true;
}



/* Actualiza la ubicación del usuario si es diferente y no está vacía. */
if ($Usuario->ubicacionLatitud != $Latitud && $Latitud != "") {
    array_push(
        $cambiosG,
        array(
            'field' => 'ubicacionLatitud',
            'old' => $Usuario->ubicacionLatitud,
            'new' => $Latitud
        )
    );
    $Usuario->ubicacionLatitud = $Latitud;
    $UsuarioCambios = true;
}

if ($_SESSION['usuario'] == '2288228' || $_SESSION['usuario'] == '2268251' || $_SESSION['usuario'] == '2725463') {
    /* verifica y actualiza el login de un usuario, controlando duplicados. */
    if ($Login != $Usuario->login) {
        array_push(
            $cambiosG,
            array(
                'field' => 'login',
                'old' => $Usuario->login,
                'new' => $Login
            )
        );
        $Usuario->login = $Login;
        /* Verificamos si existe el email para el partner */
        $checkLogin = $Usuario->exitsLogin();
        if ($checkLogin) {
            throw new Exception("Inusual Detected", "11");

        }

        $UsuarioCambios = true;

    }
}


/* Actualiza observaciones de usuario y registra cambios si son diferentes y no vacíos. */
if ($Usuario->observ != $Note && $Note != '') {
    array_push(
        $cambiosG,
        array(
            'field' => 'observ',
            'old' => $Usuario->observ,
            'new' => $Note
        )
    );
    $Usuario->observ = $Note;
    $UsuarioCambios = true;
}


/* Actualiza la propiedad "permiteRecarga" si hay diferencias y registra el cambio. */
if ($UsuarioConfig->permiteRecarga != $CanDeposit && $DragNegatives != "") {
    array_push(
        $cambiosG,
        array(
            'field' => 'permiteRecarga',
            'old' => $UsuarioConfig->permiteRecarga,
            'new' => $CanDeposit
        )
    );
    $UsuarioConfig->permiteRecarga = $CanDeposit;
    $UsuarioConfigCambios = true;
}


/* Actualiza el pinagent y registra el cambio si es necesario. */
if ($UsuarioConfig->pinagent != $Pinagent && $Pinagent != "") {
    array_push(
        $cambiosG,
        array(
            'field' => 'pinagent',
            'old' => $UsuarioConfig->pinagent,
            'new' => $Pinagent
        )
    );
    $UsuarioConfig->pinagent = $Pinagent;
    $UsuarioConfigCambios = true;
}


/* actualiza y registra cambios en la configuración del usuario sobre 'reciboCaja'. */
if ($UsuarioConfig->reciboCaja != $CanReceipt && $CanReceipt != "") {
    array_push(
        $cambiosG,
        array(
            'field' => 'reciboCaja',
            'old' => $UsuarioConfig->reciboCaja,
            'new' => $CanReceipt
        )
    );
    $UsuarioConfig->reciboCaja = $CanReceipt;
    $UsuarioConfigCambios = true;
}


/* Actualiza el límite de retiro del usuario si es diferente al máximo permitido. */
if ($UsuarioConfig->maxpagoRetiro != $MaximumWithdrawalAmount && $MaximumWithdrawalAmount != "") {
    array_push(
        $cambiosG,
        array(
            'field' => 'maxpagoRetiro',
            'old' => $UsuarioConfig->maxpagoRetiro,
            'new' => $MaximumWithdrawalAmount
        )
    );
    $UsuarioConfig->maxpagoRetiro = $MaximumWithdrawalAmount;
    $UsuarioConfigCambios = true;
}


/* actualiza el premio máximo si es diferente y no está vacío. */
if ($UsuarioConfig->maxpagoPremio != $MaximumPrizePaymentAmount && $MaximumPrizePaymentAmount != "") {
    array_push(
        $cambiosG,
        array(
            'field' => 'maxpagoPremio',
            'old' => $UsuarioConfig->maxpagoPremio,
            'new' => $MaximumPrizePaymentAmount
        )
    );
    $UsuarioConfig->maxpagoPremio = $MaximumPrizePaymentAmount;
    $UsuarioConfigCambios = true;
}

if ($UsuarioPerfil->perfilId != 'CAJERO') {



    /* actualiza el ID de usuario si es diferente al nuevo subconcesionario. */
    if ($Concesionario->getUsupadre2Id() != $Subconcessionaire) {
        array_push(
            $cambiosG,
            array(
                'field' => 'usupadre2Id',
                'old' => $Concesionario->getUsupadre2Id(),
                'new' => $Subconcessionaire
            )
        );
        $Concesionario2->setusupadre2Id($Subconcessionaire);
        $ConcesionarioCambios = true;
    }

    /* Actualiza el ID de usuario si es diferente y no está vacío, registrando cambios. */
    if ($Concesionario->getUsupadre3Id() != $Subconcessionaire2 && $Subconcessionaire2 != '') {
        array_push(
            $cambiosG,
            array(
                'field' => 'usupadre3Id',
                'old' => $Concesionario->getUsupadre3Id(),
                'new' => $Subconcessionaire2
            )
        );
        $Concesionario2->setUsupadre3Id($Subconcessionaire2);
        $ConcesionarioCambios = true;
    }



    /* verifica y registra cambios en el ID del usuario padre de un concesionario. */
    if ($Concesionario->getUsupadreId() != $Concessionaire) {
        array_push(
            $cambiosG,
            array(
                'field' => 'usupadreId',
                'old' => $Concesionario->getUsupadreId(),
                'new' => $Concessionaire
            )
        );
        $Concesionario2->setUsupadreId($Concessionaire);
        $ConcesionarioCambios = true;
    }

}


try {


    /* Se maneja información de usuario y clasificador en el código presentado. */
    $mandanteInfo = $Usuario->mandante;

    $Clasificador = new Clasificador('', 'VISITREAL');
    $idVisit = $Clasificador->clasificadorId;
    $MandanteUser = $Usuario->mandante;

    $InformacionUsuario = new UsuarioInformacion('', $idVisit, $Id, '', $MandanteUser);

    /* asigna información del usuario a una clase y crea un DAO. */
    $idusuario = $_SESSION['usuario'];


    $InformacionUsuario->setValor($VisitMade);
    $InformacionUsuario->setUsuModif($idusuario);


    $UsuarioInformacionMySqlDAO = new UsuarioInformacionMySqlDAO();

    /* Se obtiene y actualiza una transacción de información de usuario en la base de datos. */
    $transaction = $UsuarioInformacionMySqlDAO->getTransaction();
    $UsuarioInformacionMySqlDAO->update($InformacionUsuario);
    $UsuarioInformacionMySqlDAO->getTransaction()->commit();


} catch (\Exception $e) {

    if ($e->getCode() == 115) {


        /* inicializa objetos y obtiene el ID de un clasificador para un usuario. */
        $mandanteUsuario = $Usuario->mandante;

        $Clasificador = new Clasificador('', 'VISITREAL');
        $clasificadorId = $Clasificador->getClasificadorId();

        $informacionUsuario = new UsuarioInformacion();


        /* Se configuran atributos de un objeto de información del usuario y se inicializa un DAO. */
        $informacionUsuario->setClasificadorId($clasificadorId);
        $informacionUsuario->setUsuarioId($Id);
        $informacionUsuario->setValor($VisitMade);
        $informacionUsuario->setMandante($mandanteUsuario);
        $informacionUsuario->setUsuCreaId($_SESSION['usuario']);


        $informacionUsuarioMySqlDAO = new UsuarioInformacionMySqlDAO();

        /* Inserta un usuario en MySQL y maneja transacciones con el DAO. */
        $informacionUsuarioMySqlDAO->insert($informacionUsuario);
        $informacionUsuarioMySqlDAO->getTransaction()->commit();

    }
}


/* actualiza información de usuario en una base de datos. */
try {

    $clasificador = new clasificador('', 'DOCUMRECIB');
    $id_clasificador = $clasificador->getClasificadorId();

    $MandanteUser = $Usuario->mandante;
    $InformacionUsuario = new UsuarioInformacion('', $id_clasificador, $Id, '', $MandanteUser);
    $usumodificaId = $_SESSION['usuario'];


    $InformacionUsuario->setValor($documentationReceived);
    $InformacionUsuario->setUsuModif($idusuario);


    $UsuarioInformacionMySqlDAO = new UsuarioInformacionMySqlDAO();
    $transaction = $UsuarioInformacionMySqlDAO->getTransaction();
    $UsuarioInformacionMySqlDAO->update($InformacionUsuario);
    $UsuarioInformacionMySqlDAO->getTransaction()->commit();

} catch (\Exception $e) {


    /* Condicional para insertar información del usuario en base de datos si se cumple un código. */
    if ($e->getCode() == 115) {


        $clasificador = new clasificador('', 'DOCUMRECIB');
        $id_clasificador = $clasificador->getClasificadorId();
        $mandantenumero = $Usuario->mandante;

        $InformacionUsuario = new UsuarioInformacion();

        $InformacionUsuario->setClasificadorId($id_clasificador);
        $InformacionUsuario->setUsuarioId($Id);
        $InformacionUsuario->setValor($documentationReceived);
        $InformacionUsuario->setMandante(0);
        $InformacionUsuario->setUsuCreaId($_SESSION['usuario']);

        $InformacionUsuarioMySqlDAO = new UsuarioInformacionMySqlDAO();
        $InformacionUsuarioMySqlDAO->insert($InformacionUsuario);
        $InformacionUsuarioMySqlDAO->getTransaction()->commit();
    }
}



/* Actualiza datos en la base de datos y confirma transacciones si hay cambios. */
if ($PuntoVentaCambios) {
    $PuntoventaMySqlDAO = new PuntoventaMySqlDAO();
    $PuntoventaMySqlDAO->update($PuntoVenta);
    $PuntoventaMySqlDAO->getTransaction()->commit();
}

if ($UsuarioPremiomaxCambios) {
    $UsuarioPremiomaxMySqlDAO = new UsuarioPremiomaxMySqlDAO();
    $UsuarioPremiomaxMySqlDAO->update($UsuarioPremiomax);
    $UsuarioPremiomaxMySqlDAO->getTransaction()->commit();
}


/* Actualiza la fecha del usuario y guarda los cambios en la base de datos. */
if ($UsuarioCambios) {
    $Usuario->fechaActualizacion = date('Y-m-d H:i:s');
    $UsuarioMySqlDAO = new UsuarioMySqlDAO();
    $UsuarioMySqlDAO->update($Usuario);
    $UsuarioMySqlDAO->getTransaction()->commit();
}


/* Actualiza la configuración del usuario en la base de datos si se permiten cambios. */
if ($UsuarioConfigCambios) {
    $UsuarioConfigMySqlDAO = new UsuarioConfigMySqlDAO();
    $UsuarioConfigMySqlDAO->update($UsuarioConfig);
    $UsuarioConfigMySqlDAO->getTransaction()->commit();
}


if ($ConcesionarioCambios) {
    $seguir = true;
    if ($Usuario->mandante == 8 && $_SESSION['usuario'] != 449 && $_SESSION['usuario'] != 2267885) {
        $seguir = false;

    }
    if ($seguir) {

        /* Se actualiza el estado de un concesionario y se registra el usuario modificador. */
        $Concesionario->estado = 'I';

        $ConcesionarioMySqlDAO = new ConcesionarioMySqlDAO();
        $ConcesionarioMySqlDAO->update($Concesionario);
        $ConcesionarioMySqlDAO->getTransaction()->commit();

        $Concesionario2->setUsumodifId($_SESSION['usuario']);

        /* Inserta datos en la base de datos y confirma la transacción. */
        $ConcesionarioMySqlDAO = new ConcesionarioMySqlDAO();
        $ConcesionarioMySqlDAO->insert($Concesionario2);
        $ConcesionarioMySqlDAO->getTransaction()->commit();

        $ConcesionarioMySqlDAO = new ConcesionarioMySqlDAO();


        $rules = [];


        /* Crea un filtro en formato JSON con reglas de comparación para datos. */
        array_push($rules, array("field" => "clasificador.tipo", "data" => "PCOM", "op" => "eq"));
        array_push($rules, array("field" => "clasificador.estado", "data" => "A", "op" => "eq"));
        array_push($rules, array("field" => "DISP", "data" => "DISP", "op" => "neq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $jsonfiltro = json_encode($filtro);



        /* Se crea un objeto de concesionario y se obtienen productos usando filtros JSON. */
        $Concesionario = new Concesionario();
        $productos = $Concesionario->getConcesionariosProductoInternoCustom("clasificador.clasificador_id,clasificador.descripcion, concesionario.porcenpadre1,concesionario.porcenpadre2,concesionario.porcenpadre3,concesionario.porcenpadre4,concesionario.porcenhijo  ", "clasificador.clasificador_id", "asc", 0, 10000, $jsonfiltro, true, $Id);
        $productos = json_decode($productos);


        $final = array();


        /* Actualiza el estado de productos a 'I' en la base de datos mediante excepciones. */
        foreach ($productos->data as $producto) {

            try {
                $ConcesionarioAntes = new Concesionario($Id, strtolower($producto->{"clasificador.clasificador_id"}));
                $ConcesionarioAntes->setEstado('I');

                $ConcesionarioMySqlDAO->update($ConcesionarioAntes);

            } catch (Exception $e) {

            }

        }

        /* confirma una transacción en una base de datos MySQL utilizando un DAO. */
        $ConcesionarioMySqlDAO->getTransaction()->commit();
    }

}

try {
    foreach ($cambiosG as $item) {


        /* Código para configurar auditoría de acciones de usuario en un sistema. */
        $AuditoriaGeneral = new AuditoriaGeneral();
        $AuditoriaGeneral->setUsuarioIp("");

        $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario2"]);
        $AuditoriaGeneral->setUsuarioSolicitaIp($Global_IP);

        $AuditoriaGeneral->setTipo("betshop_edit");

        /* Establece datos de auditoría, registrando cambios en los campos de un usuario. */
        $AuditoriaGeneral->setEstado("A");
        $AuditoriaGeneral->setUsuarioId($Usuario->usuarioId);
        $AuditoriaGeneral->setValorAntes($item['old']);
        $AuditoriaGeneral->setValorDespues($item['new']);
        $AuditoriaGeneral->setData(json_encode($params));
        $AuditoriaGeneral->setCampo($item['field']);

        /* Inserts audit data into the database using the specified parameters and user ID. */
        $AuditoriaGeneral->setData(json_encode($params));

        $AuditoriaGeneral->setUsucreaId(0);
        $AuditoriaGeneralMysqlDao = new AuditoriaGeneralMySqlDAO();
        $AuditoriaGeneralMysqlDao->insert($AuditoriaGeneral);
        $AuditoriaGeneralMysqlDao->getTransaction()->commit();
    }
} catch (Exception $e) {
    /* Captura y maneja excepciones en PHP sin realizar ninguna acción específica. */


}

if ($IsActivatePayWithdrawalAllies != "") {

    /* Crea un objeto "Clasificador" y obtiene su identificador. */
    $Clasificador = new Clasificador("", "ISACTIVATEPAYWITHDRAWALALLIES");
    $ClasificadorId = $Clasificador->getClasificadorId();

    try {

        /* Se crea y configura un objeto de usuario con varios valores específicos. */
        $UsuarioConfiguracion = new UsuarioConfiguracion($Id, "A", $ClasificadorId);
        $UsuarioConfiguracion->setUsuarioId($Id);
        $UsuarioConfiguracion->setTipo($ClasificadorId);
        $UsuarioConfiguracion->setValor($IsActivatePayWithdrawalAllies);
        $UsuarioConfiguracion->setNota("");
        $UsuarioConfiguracion->setUsucreaId($Id);

        /* Configura usuario y estado basado en condiciones de activación de pagos. */
        $UsuarioConfiguracion->setUsumodifId($Id);
        $UsuarioConfiguracion->setProductoId(0);
        $UsuarioConfiguracion->setEstado("A");

        if ($IsActivatePayWithdrawalAllies == "I" and $IsActivatePayWithdrawalAllies != "") {
            $UsuarioConfiguracion->setEstado("I");
            $UsuarioConfiguracion->setValor(0);
            $UsuarioConfiguracion->setUsumodifId($_SESSION["usuario"]);
        }


        /* gestiona transacciones y actualiza la configuración del usuario en MySQL. */
        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
        $Transaction = $UsuarioConfiguracionMySqlDAO->getTransaction();
        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
        $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

    } catch (Exception $e) {
        /* Manejo de excepciones que inserta configuraciones de usuario en una base de datos. */

        if ($e->getCode() == "46") {
            $UsuarioConfiguracion = new UsuarioConfiguracion();
            $UsuarioConfiguracion->setUsuarioId($Id);
            $UsuarioConfiguracion->setTipo($ClasificadorId);
            $UsuarioConfiguracion->setValor($IsActivatePayWithdrawalAllies);
            $UsuarioConfiguracion->setNota("");
            $UsuarioConfiguracion->setUsucreaId($Id);
            $UsuarioConfiguracion->setUsumodifId($Id);
            $UsuarioConfiguracion->setProductoId(0);
            $UsuarioConfiguracion->setEstado("A");

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

        }
    }
}
//limite de depositos diarios

if ($DepositLimitAllies != "") {

    /* Se crea un objeto "Clasificador" y se obtiene su identificador. */
    $Clasificador = new Clasificador("", "LIMITDAILYDEPOSITSPERPOINTSOFSALE");
    $ClasificadorId = $Clasificador->getClasificadorId();

    try {

        /* Código para crear y configurar un objeto UsuarioConfiguracion con parámetros específicos. */
        $UsuarioConfiguracion = new UsuarioConfiguracion($Id, "A", $ClasificadorId);
        $UsuarioConfiguracion->setUsuarioId($Id);
        $UsuarioConfiguracion->setTipo($ClasificadorId);
        $UsuarioConfiguracion->setValor($DepositLimitAllies);
        $UsuarioConfiguracion->setNota("");
        $UsuarioConfiguracion->setUsucreaId($Id);

        /* Configura un usuario y su estado basado en condiciones de límites de depósito. */
        $UsuarioConfiguracion->setUsumodifId($Id);
        $UsuarioConfiguracion->setProductoId(0);
        $UsuarioConfiguracion->setEstado("A");

        if ($DepositLimitAllies == "0" and $DepositLimitAllies != "") {
            $UsuarioConfiguracion->setEstado("I");
            $UsuarioConfiguracion->setValor(0);
            $UsuarioConfiguracion->setUsumodifId($_SESSION["usuario"]);
        }


        /* actualiza la configuración del usuario en una base de datos MySQL. */
        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
        $Transaction = $UsuarioConfiguracionMySqlDAO->getTransaction();
        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
        $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();


    } catch (Exception $e) {
        /* Manejo de excepciones para insertar configuración de usuario si ocurre error específico. */

        if ($e->getCode() == "46") {
            $UsuarioConfiguracion = new UsuarioConfiguracion();
            $UsuarioConfiguracion->setUsuarioId($Id);
            $UsuarioConfiguracion->setTipo($ClasificadorId);
            $UsuarioConfiguracion->setValor($DepositLimitAllies);
            $UsuarioConfiguracion->setNota("");
            $UsuarioConfiguracion->setUsucreaId($Id);
            $UsuarioConfiguracion->setUsumodifId($Id);
            $UsuarioConfiguracion->setProductoId(0);
            $UsuarioConfiguracion->setEstado("A");

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
        }
    }
}

//permite pagar retiros a redes aliadas


if ($IsActiveWithdrawalLimitAllies != "") {

    /* Se instancia un clasificador con un tipo específico y se obtiene su ID. */
    $Clasificador = new Clasificador("", "ACTIVATESECONDLEVELPOINTOFSALEWITHDRAWALS");
    $ClasificadorId = $Clasificador->getClasificadorId();

    try {

        /* Se crea y configura un objeto UsuarioConfiguracion con diversos parámetros y valores. */
        $UsuarioConfiguracion = new UsuarioConfiguracion($Id, "A", $ClasificadorId);
        $UsuarioConfiguracion->setUsuarioId($Id);
        $UsuarioConfiguracion->setTipo($ClasificadorId);
        $UsuarioConfiguracion->setValor($IsActiveWithdrawalLimitAllies);
        $UsuarioConfiguracion->setNota("");
        $UsuarioConfiguracion->setUsucreaId($Id);

        /* Configura el estado y usuario de una entidad según condiciones específicas. */
        $UsuarioConfiguracion->setUsumodifId($Id);
        $UsuarioConfiguracion->setProductoId(0);
        $UsuarioConfiguracion->setEstado("A");

        if ($IsActiveWithdrawalLimitAllies == "I" and $IsActiveWithdrawalLimitAllies != "") {
            $UsuarioConfiguracion->setEstado("I");
            $UsuarioConfiguracion->setValor("I");
            $UsuarioConfiguracion->setUsumodifId($_SESSION["usuario"]);
        }



        /* Se crea un DAO para actualizar la configuración de usuario en MySQL. */
        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
        $Transaction = $UsuarioConfiguracionMySqlDAO->getTransaction();
        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
        $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();


    } catch (Exception $e) {
        /* Maneja excepciones insertando configuración de usuario si el código de error es 46. */

        if ($e->getCode() == "46") {
            $UsuarioConfiguracion = new UsuarioConfiguracion();
            $UsuarioConfiguracion->setUsuarioId($Id);
            $UsuarioConfiguracion->setTipo($ClasificadorId);
            $UsuarioConfiguracion->setValor($IsActiveWithdrawalLimitAllies);
            $UsuarioConfiguracion->setNota("");
            $UsuarioConfiguracion->setUsucreaId($Id);
            $UsuarioConfiguracion->setUsumodifId($Id);
            $UsuarioConfiguracion->setProductoId(0);
            $UsuarioConfiguracion->setEstado("A");

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
        }
    }

}

// limite de deposito


if ($IsActiveDepositLimitAllies != "") {

    /* Se crea un clasificador y se obtiene su ID mediante un método. */
    $Clasificador = new Clasificador("", "ALLOWSDEPOSITTOALLIEDNETWORKS");
    $ClasificadorId = $Clasificador->getClasificadorId();

    try {

        /* Se crea un objeto UsuarioConfiguracion y se configuran sus propiedades. */
        $UsuarioConfiguracion = new UsuarioConfiguracion($Id, "A", $ClasificadorId);
        $UsuarioConfiguracion->setUsuarioId($Id);
        $UsuarioConfiguracion->setTipo($ClasificadorId);
        $UsuarioConfiguracion->setValor($IsActiveDepositLimitAllies);
        $UsuarioConfiguracion->setNota("");
        $UsuarioConfiguracion->setUsucreaId($Id);

        /* configura atributos de usuario según ciertas condiciones activando o desactivando estados. */
        $UsuarioConfiguracion->setUsumodifId($Id);
        $UsuarioConfiguracion->setProductoId(0);
        $UsuarioConfiguracion->setEstado("A");

        if ($IsActiveDepositLimitAllies == "I" and $IsActiveDepositLimitAllies != "") {
            $UsuarioConfiguracion->setEstado("I");
            $UsuarioConfiguracion->setValor("I");
            $UsuarioConfiguracion->setUsumodifId($_SESSION["usuario"]);
        }


        /* Crea una instancia y actualiza la configuración del usuario en MySQL. */
        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
        $Transaction = $UsuarioConfiguracionMySqlDAO->getTransaction();
        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
        $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

    } catch (Exception $e) {
        /* Manejo de excepciones para insertar configuración de usuario en base de datos. */

        if ($e->getCode() == "46") {
            $UsuarioConfiguracion = new UsuarioConfiguracion();
            $UsuarioConfiguracion->setUsuarioId($Id);
            $UsuarioConfiguracion->setTipo($ClasificadorId);
            $UsuarioConfiguracion->setValor($IsActiveDepositLimitAllies);
            $UsuarioConfiguracion->setNota("");
            $UsuarioConfiguracion->setUsucreaId($Id);
            $UsuarioConfiguracion->setUsumodifId($Id);
            $UsuarioConfiguracion->setProductoId(0);
            $UsuarioConfiguracion->setEstado("A");

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
        }
    }

}

//limite de retiros diarios
if ($WithdrawalLimitAllies != "") {

    /* Crea un objeto clasificador y obtiene su ID basado en un tipo específico. */
    $Clasificador = new Clasificador("", "DAILYWITHDRAWALPOINTLIMIT");
    $ClasificadorId = $Clasificador->getClasificadorId();


    try {

        /* Se crea y configura un objeto UsuarioConfiguracion con diversos parámetros e información. */
        $UsuarioConfiguracion = new UsuarioConfiguracion($Id, "A", $ClasificadorId);
        $UsuarioConfiguracion->setUsuarioId($Id);
        $UsuarioConfiguracion->setTipo($ClasificadorId);
        $UsuarioConfiguracion->setValor($WithdrawalLimitAllies);
        $UsuarioConfiguracion->setNota("");
        $UsuarioConfiguracion->setUsucreaId($Id);

        /* Configura usuario con estado y límites, modificando según condiciones específicas. */
        $UsuarioConfiguracion->setUsumodifId($Id);
        $UsuarioConfiguracion->setProductoId(0);
        $UsuarioConfiguracion->setEstado("A");

        if ($WithdrawalLimitAllies == "0" and $WithdrawalLimitAllies != "") {
            $UsuarioConfiguracion->setEstado("I");
            $UsuarioConfiguracion->setValor(0);
            $UsuarioConfiguracion->setUsumodifId($_SESSION["usuario"]);
        }


        /* Código que actualiza la configuración de usuario en una base de datos MySQL. */
        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
        $Transaction = $UsuarioConfiguracionMySqlDAO->getTransaction();
        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
        $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

    } catch (Exception $e) {
        /* Maneja excepciones específicas y guarda configuración de usuario en base de datos. */

        if ($e->getCode() == "46") {
            $UsuarioConfiguracion = new UsuarioConfiguracion();
            $UsuarioConfiguracion->setUsuarioId($Id);
            $UsuarioConfiguracion->setTipo($ClasificadorId);
            $UsuarioConfiguracion->setValor($WithdrawalLimitAllies);
            $UsuarioConfiguracion->setNota("");
            $UsuarioConfiguracion->setUsucreaId($Id);
            $UsuarioConfiguracion->setUsumodifId($Id);
            $UsuarioConfiguracion->setProductoId(0);
            $UsuarioConfiguracion->setEstado("A");

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

        }
    }

}


//limite de depositos diarios

if ($DepositLimitAllies != "") {

    /* Se crea un clasificador con un tipo específico y se obtiene su ID. */
    $Clasificador = new Clasificador("", "LIMITDAILYDEPOSITSPERPOINTSOFSALE");
    $ClasificadorId = $Clasificador->getClasificadorId();

    try {

        /* Se crea un objeto UsuarioConfiguracion y se configuran sus propiedades respectivas. */
        $UsuarioConfiguracion = new UsuarioConfiguracion($Id, "A", $ClasificadorId);
        $UsuarioConfiguracion->setUsuarioId($Id);
        $UsuarioConfiguracion->setTipo($ClasificadorId);
        $UsuarioConfiguracion->setValor($DepositLimitAllies);
        $UsuarioConfiguracion->setNota("");
        $UsuarioConfiguracion->setUsucreaId($Id);

        /* configura propiedades de usuario según condiciones específicas de límite de depósito. */
        $UsuarioConfiguracion->setUsumodifId($Id);
        $UsuarioConfiguracion->setProductoId(0);
        $UsuarioConfiguracion->setEstado("A");

        if ($DepositLimitAllies == "I" and $DepositLimitAllies != "") {
            $UsuarioConfiguracion->setEstado("I");
            $UsuarioConfiguracion->setValor(0);
            $UsuarioConfiguracion->setUsumodifId($_SESSION["usuario"]);
        }


        /* Se crea un objeto DAO para actualizar la configuración de usuario en MySQL. */
        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
        $Transaction = $UsuarioConfiguracionMySqlDAO->getTransaction();
        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
        $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();


    } catch (Exception $e) {
        /* Maneja excepciones, insertando configuración de usuario si el código de error es "46". */

        if ($e->getCode() == "46") {
            $UsuarioConfiguracion = new UsuarioConfiguracion();
            $UsuarioConfiguracion->setUsuarioId($Id);
            $UsuarioConfiguracion->setTipo($ClasificadorId);
            $UsuarioConfiguracion->setValor($DepositLimitAllies);
            $UsuarioConfiguracion->setNota("");
            $UsuarioConfiguracion->setUsucreaId($Id);
            $UsuarioConfiguracion->setUsumodifId($Id);
            $UsuarioConfiguracion->setProductoId(0);
            $UsuarioConfiguracion->setEstado("A");

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
        }
    }
}

//limite de retiros diarios
if ($WithdrawalLimitAllies != "") {

    /* Crea un clasificador y obtiene su identificador asociado en un sistema determinado. */
    $Clasificador = new Clasificador("", "DAILYWITHDRAWALPOINTLIMIT");
    $ClasificadorId = $Clasificador->getClasificadorId();


    try {

        /* Se crea y configura un objeto de usuario con datos específicos. */
        $UsuarioConfiguracion = new UsuarioConfiguracion($Id, "A", $ClasificadorId);
        $UsuarioConfiguracion->setUsuarioId($Id);
        $UsuarioConfiguracion->setTipo($ClasificadorId);
        $UsuarioConfiguracion->setValor($WithdrawalLimitAllies);
        $UsuarioConfiguracion->setNota("");
        $UsuarioConfiguracion->setUsucreaId($Id);

        /* Configura el estado del usuario según condiciones de límite de retiro. */
        $UsuarioConfiguracion->setUsumodifId($Id);
        $UsuarioConfiguracion->setProductoId(0);
        $UsuarioConfiguracion->setEstado("A");

        if ($WithdrawalLimitAllies == "I" and $WithdrawalLimitAllies != "") {
            $UsuarioConfiguracion->setEstado("I");
            $UsuarioConfiguracion->setValor(0);
            $UsuarioConfiguracion->setUsumodifId($_SESSION["usuario"]);
        }


        /* Código para actualizar configuración de usuario en una base de datos MySQL. */
        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
        $Transaction = $UsuarioConfiguracionMySqlDAO->getTransaction();
        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
        $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

    } catch (Exception $e) {
        /* Maneja excepciones para insertar configuraciones de usuario en base de datos según condiciones. */

        if ($e->getCode() == "46") {
            $UsuarioConfiguracion = new UsuarioConfiguracion();
            $UsuarioConfiguracion->setUsuarioId($Id);
            $UsuarioConfiguracion->setTipo($ClasificadorId);
            $UsuarioConfiguracion->setValor($WithdrawalLimitAllies);
            $UsuarioConfiguracion->setNota("");
            $UsuarioConfiguracion->setUsucreaId($Id);
            $UsuarioConfiguracion->setUsumodifId($Id);
            $UsuarioConfiguracion->setProductoId(0);
            $UsuarioConfiguracion->setEstado("A");

            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

        }
    }

}


try {
    if ($IsActivatePayWithdrawalAllies != "") {

        /* Se crea un objeto "Clasificador" y se obtiene su identificador. */
        $Clasificador = new Clasificador("", "ISACTIVATEPAYWITHDRAWALALLIES");
        $ClasificadorId = $Clasificador->getClasificadorId();

        try {

            /* Creación y configuración de un objeto usuario con atributos específicos. */
            $UsuarioConfiguracion = new UsuarioConfiguracion($Id, "A", $ClasificadorId);
            $UsuarioConfiguracion->setUsuarioId($Id);
            $UsuarioConfiguracion->setTipo($ClasificadorId);
            $UsuarioConfiguracion->setValor($IsActivatePayWithdrawalAllies);
            $UsuarioConfiguracion->setNota("");
            $UsuarioConfiguracion->setUsucreaId($Id);

            /* Configura usuario y estado según condiciones de pago y modificación. */
            $UsuarioConfiguracion->setUsumodifId($Id);
            $UsuarioConfiguracion->setProductoId(0);
            $UsuarioConfiguracion->setEstado("A");

            if ($IsActivatePayWithdrawalAllies == "I" and $IsActivatePayWithdrawalAllies != "") {
                $UsuarioConfiguracion->setEstado("I");
                $UsuarioConfiguracion->setValor(0);
                $UsuarioConfiguracion->setUsumodifId($_SESSION["usuario"]);
            }


            /* gestiona transacciones y actualiza configuraciones de usuario en MySQL. */
            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
            $Transaction = $UsuarioConfiguracionMySqlDAO->getTransaction();
            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

        } catch (Exception $e) {
            /* Manejo de excepciones que inserta configuración de usuario para código de error específico. */

            if ($e->getCode() == "46") {
                $UsuarioConfiguracion = new UsuarioConfiguracion();
                $UsuarioConfiguracion->setUsuarioId($Id);
                $UsuarioConfiguracion->setTipo($ClasificadorId);
                $UsuarioConfiguracion->setValor($IsActivatePayWithdrawalAllies);
                $UsuarioConfiguracion->setNota("");
                $UsuarioConfiguracion->setUsucreaId($Id);
                $UsuarioConfiguracion->setUsumodifId($Id);
                $UsuarioConfiguracion->setProductoId(0);
                $UsuarioConfiguracion->setEstado("A");

                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
                $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

            }
        }
    }
} catch (Exception $e) {
    /* maneja excepciones en PHP sin realizar ninguna acción específica. */

}
try {
    if ($IdsAllowed != "") {

        /* Se crea un objeto 'Clasificador' y se obtiene su ID. */
        $Clasificador = new Clasificador("", "CONCESIONARIOSALLOWED");
        $ClasificadorId = $Clasificador->getClasificadorId();

        try {

            /* Código crea y configura un objeto UsuarioConfiguracion con varios parámetros e identificadores. */
            $UsuarioConfiguracion = new UsuarioConfiguracion($Id, "A", $ClasificadorId);
            $UsuarioConfiguracion->setUsuarioId($Id);
            $UsuarioConfiguracion->setTipo($ClasificadorId);
            $UsuarioConfiguracion->setValor($IdConcesionarios);
            $UsuarioConfiguracion->setNota("");
            $UsuarioConfiguracion->setUsucreaId($Id);

            /* configura un usuario, aplicando estados y valores condicionados. */
            $UsuarioConfiguracion->setUsumodifId($Id);
            $UsuarioConfiguracion->setProductoId(0);
            $UsuarioConfiguracion->setEstado("A");

            if ($IdsAllowed == "I" and $IdsAllowed != "") {
                $UsuarioConfiguracion->setEstado("I");
                $UsuarioConfiguracion->setValor(0);
                $UsuarioConfiguracion->setUsumodifId($_SESSION["usuario"]);
            }

            /* Se crea una transacción para actualizar la configuración del usuario en MySQL. */
            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
            $Transaction = $UsuarioConfiguracionMySqlDAO->getTransaction();
            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();


        } catch (Exception $e) {
            /* Manejo de excepción para registrar configuración de usuario al recibir un código específico. */

            if ($e->getCode() == "46") {
                $UsuarioConfiguracion = new UsuarioConfiguracion();
                $UsuarioConfiguracion->setUsuarioId($Id);
                $UsuarioConfiguracion->setTipo($ClasificadorId);
                $UsuarioConfiguracion->setValor($IdConcesionarios);
                $UsuarioConfiguracion->setNota("");
                $UsuarioConfiguracion->setUsucreaId($Id);
                $UsuarioConfiguracion->setUsumodifId($Id);
                $UsuarioConfiguracion->setProductoId(0);
                $UsuarioConfiguracion->setEstado("A");

                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
                $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

            }
        }

    }
} catch (Exception $e) {
    /* Bloque para capturar y manejar excepciones en programación PHP. */

}

###
try {

    if ($IndividualCalculation != '' && $IndividualCalculation != null) {
        try {

            /* Código para actualizar configuración de usuario en base de datos, utilizando un clasificador. */
            $Clasificador = new Clasificador('', 'INDIVIDUALGGR');
            try {
                $UsuarioConfiguracion = new UsuarioConfiguracion($Id, '', $Clasificador->getClasificadorId(), 0);

                if ($UsuarioConfiguracion->getEstado() != $IndividualCalculation) {
                    $UsuarioConfiguracion->setValor(0);
                    $UsuarioConfiguracion->setEstado($IndividualCalculation);
                    $UsuarioConfiguracion->setUsumodifId($_SESSION["usuario"]);

                    $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                    $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
                    $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
                }


            } catch (Exception $e) {
                /* Manejo de excepciones para crear y almacenar configuración de usuario en la base de datos. */

                if ($e->getCode() == 46) {
                    $UsuarioConfiguracion = new UsuarioConfiguracion();
                    $UsuarioConfiguracion->setUsuarioId($Id);
                    $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                    $UsuarioConfiguracion->setValor(0);
                    $UsuarioConfiguracion->setProductoId(0);
                    $UsuarioConfiguracion->setEstado($IndividualCalculation);
                    $UsuarioConfiguracion->setUsucreaId($_SESSION["usuario"]);

                    $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                    $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
                    $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
                }
            }
        } catch (Exception $e) {
            /* Captura excepciones en PHP sin realizar ninguna acción específica. */

        }
    }
} catch (Exception $e) {
    /* Bloque que captura excepciones en PHP para manejar errores sin interrumpir la ejecución. */

}


###
try {

    if ($IsActivateWithdrawalLimit != '' && $IsActivateWithdrawalLimit != null) {
        try {

            /* Se crea una instancia de la clase Clasificador con parámetros específicos. */
            $Clasificador = new Clasificador('', 'DAYLILIMITPV');
            try {

                /* Actualiza la configuración del usuario si se cumplen ciertas condiciones de retiro. */
                $UsuarioConfiguracion = new UsuarioConfiguracion($Id, '', $Clasificador->getClasificadorId(), 0);

                if ($IsActivateWithdrawalLimit == 'A' && $UsuarioConfiguracion->getValor() != $WithdrawalLimit) {
                    $UsuarioConfiguracion->setValor($WithdrawalLimit);
                    $UsuarioConfiguracion->setEstado($IsActivateWithdrawalLimit);
                    $UsuarioConfiguracion->setUsumodifId($_SESSION["usuario"]);

                    $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                    $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
                    $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
                }

                /* verifica condiciones y actualiza configuraciones de usuario en la base de datos. */
                if ($IsActivateWithdrawalLimit == 'I' && $UsuarioConfiguracion->getEstado() != $IsActivateWithdrawalLimit) {
                    $UsuarioConfiguracion->setValor(0);
                    $UsuarioConfiguracion->setEstado('I');
                    $UsuarioConfiguracion->setUsumodifId($_SESSION["usuario"]);

                    $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                    $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
                    $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
                }
            } catch (Exception $e) {
                /* Maneja excepciones, guarda configuración del usuario si el código de error es 46. */

                if ($e->getCode() == 46) {
                    $UsuarioConfiguracion = new UsuarioConfiguracion();
                    $UsuarioConfiguracion->setUsuarioId($Id);
                    $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                    $UsuarioConfiguracion->setValor($WithdrawalLimit);
                    $UsuarioConfiguracion->setProductoId(0);
                    $UsuarioConfiguracion->setEstado($IsActivateWithdrawalLimit);
                    $UsuarioConfiguracion->setUsucreaId($_SESSION["usuario"]);

                    $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                    $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
                    $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
                }
            }
        } catch (Exception $e) {
            /* captura excepciones en PHP, pero no realiza ninguna acción con ellas. */

        }
    }
} catch (Exception $e) {
    /* Captura excepciones en PHP, permitiendo manejar errores sin detener la ejecución del programa. */

}
try {

    if ($IsActivateDepositLimit != '' && $IsActivateDepositLimit != null) {
        try {

            /* Se crea una instancia del clasificador con un tipo específico 'DAYLILIMITPV'. */
            $Clasificador = new Clasificador('', 'DAYLILIMITPV');
            try {

                /* Actualiza la configuración del usuario si se activan límites de depósito. */
                $UsuarioConfiguracion = new UsuarioConfiguracion($Id, '', $Clasificador->getClasificadorId(), 1);
                if ($IsActivateDepositLimit == 'A' && $UsuarioConfiguracion->getValor() != $DepositLimit) {
                    $UsuarioConfiguracion->setValor($DepositLimit);
                    $UsuarioConfiguracion->setEstado($IsActivateDepositLimit);
                    $UsuarioConfiguracion->setUsumodifId($_SESSION["usuario"]);

                    $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                    $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
                    $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
                }

                /* actualiza el estado de usuario si ciertas condiciones se cumplen. */
                if ($IsActivateDepositLimit == 'I' && $UsuarioConfiguracion->getEstado() != $IsActivateDepositLimit) {
                    $UsuarioConfiguracion->setValor(0);
                    $UsuarioConfiguracion->setEstado('I');
                    $UsuarioConfiguracion->setUsumodifId($_SESSION["usuario"]);

                    $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                    $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
                    $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
                }
            } catch (Exception $e) {
                /* gestiona excepciones, creando y guardando configuración de usuario en base de datos. */

                if ($e->getCode() == 46) {
                    $UsuarioConfiguracion = new UsuarioConfiguracion();
                    $UsuarioConfiguracion->setUsuarioId($Id);
                    $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                    $UsuarioConfiguracion->setValor($DepositLimit);
                    $UsuarioConfiguracion->setProductoId(1);
                    $UsuarioConfiguracion->setEstado($IsActivateDepositLimit);
                    $UsuarioConfiguracion->setUsucreaId($_SESSION["usuario"]);

                    $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                    $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
                    $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
                }
            }
        } catch (Exception $e) {
            /* Bloque para manejar excepciones en PHP, evitando errores inesperados en el código. */

        }
    }
} catch (Exception $e) {
    /* Manejo de excepciones en PHP, captura errores sin realizar ninguna acción adicional. */

}

try {


    /* Se asigna el valor de CodeMincetur desde los parámetros recibidos. */
    $CodeMincetur = $params->CodeMincetur;
    if ($CodeMincetur != '' && $CodeMincetur != null) {
        try {

            /* Actualiza la configuración del usuario si el valor no coincide con CodeMincetur. */
            $Clasificador = new Clasificador('', 'CODEMINCETUR');
            try {
                $UsuarioConfiguracion = new UsuarioConfiguracion($Id, 'A', $Clasificador->getClasificadorId());
                if ($UsuarioConfiguracion->getValor() != $CodeMincetur) {
                    $UsuarioConfiguracion->setValor($CodeMincetur);
                    $UsuarioConfiguracion->setUsumodifId($_SESSION["usuario"]);

                    $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                    $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
                    $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
                }
            } catch (Exception $e) {
                /* Manejo de excepciones para insertar configuración de usuario si el código es 46. */

                if ($e->getCode() == 46) {
                    $UsuarioConfiguracion = new UsuarioConfiguracion();
                    $UsuarioConfiguracion->setUsuarioId($Id);
                    $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
                    $UsuarioConfiguracion->setValor($CodeMincetur);
                    $UsuarioConfiguracion->setEstado('A');
                    $UsuarioConfiguracion->setUsucreaId($_SESSION["usuario"]);

                    $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                    $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
                    $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
                }
            }
        } catch (Exception $e) {
            /* Bloque para manejar excepciones en PHP, sin acciones específicas dentro. */

        }
    }
} catch (Exception $e) {
    /* Captura excepciones en PHP sin realizar ninguna acción específica en el bloque. */

}


$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];


