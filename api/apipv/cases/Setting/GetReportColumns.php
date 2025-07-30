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
 * Setting/GetReportColumns
 *
 * Este script obtiene las columnas de reportes basados en el parámetro "reportName".
 *
 * @param $_GET["reportName"] string Nombre del reporte solicitado. Puede tomar valores como:
 * @param string $_GET["PlayerInfo"] Información del jugador.
 * @param string $_GET["PlayerTables"] Tablas de jugadores.
 * @param string $_GET["PlayersTable"] Tabla específica de jugadores.
 * @param string $_GET["DashboardSettings"] Configuración del tablero.
 * @param string $_GET["DepositReportSettings"] Configuración del reporte de depósitos.
 * @param string $_GET["WithdrawalRequests"] Solicitudes de retiro.
 * @param string $_GET["LiquidationRequests"] Solicitudes de liquidación.
 * @param string $_GET["DepositRequests"] Solicitudes de depósito.
 * 
 *
 * @return array $response Estructura de respuesta que incluye:
 *                         - "HasError" (boolean): Indica si ocurrió un error.
 *                         - "AlertType" (string): Tipo de alerta ("success" o "error").
 *                         - "AlertMessage" (string): Mensaje asociado a la alerta.
 *                         - "ModelErrors" (array): Lista de errores del modelo (vacío si no hay errores).
 *                         - "Data" (array): Datos específicos del reporte solicitado.
 *
 * @throws none
 */


/* obtiene un nombre de reporte y establece un response exitoso. */
$ReportName = $_GET["reportName"];

$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "success";
$response["ModelErrors"] = [];


/* Define un array de datos relacionados con la información del jugador en un reporte. */
if ($ReportName == "PlayerInfo") {
    $response["Data"] = array(
        "RegionId", "Id", "Ip", "Clave", "Estado", "EstadoEspecial", "PermiteRecargas", "ImprimeRecibo", "Pais", "Idioma", "Nombre", "TipoUsuario", "Intentos", "Observaciones", "PinAgent", "BloqueoVentas", "Moneda", "ActivarRecarga", "Login", "FirstName", "LastName", "PersonalId", "Email", "AffilateId", "BTag", "IsSubscribeToEmail", "IsSubscribeToSMS", "ExternalId", "AccountHolder", "Address", "BirthCity", "BirthDate", "BirthDepartment", "BirthRegionCode2", "BirthRegionId", "CashDesk", "CreatedLocalDate", "CurrencyId", "DocIssueCode", "DocIssueDate", "DocIssuedBy", "DocNumber", "Gender", "PartnerName", "City", "IBAN", "RFId", "BTag", "IsUsingLoyaltyProgram", "LoyaltyLevelId", "TimeZone", "IsLoggedIn", "IsResident", "IsSubscribedToNewsletter", "IsTest", "IsVerified", "Language", "LastLoginLocalDate", "MiddleName", "MobilePhone", "Phone", "ProfileId", "PromoCode", "Province", "CountryName", "RegistrationSource", "SportsbookProfileId", "SwiftCode", "Title", "ZipCode", "IsLocked",

    );
}

/* asigna un array de campos a la respuesta si el nombre del reporte es "PlayerTables". */
if ($ReportName == "PlayerTables") {
    $response["Data"] = array(
        "Id", "Ip", "Clave", "Estado", "EstadoEspecial", "PermiteRecargas", "ImprimeRecibo", "Pais", "Idioma", "Nombre", "TipoUsuario", "Intentos", "Observaciones", "PinAgent", "BloqueoVentas", "Moneda", "ActivarRecarga", "Login", "FirstName", "LastName", "PersonalId", "Email", "AffilateId", "BTag", "IsSubscribeToEmail", "IsSubscribeToSMS", "ExternalId", "AccountHolder", "Address", "BirthCity", "BirthDate", "BirthDepartment", "BirthRegionCode2", "BirthRegionId", "CashDesk", "CreatedLocalDate", "CurrencyId", "DocIssueCode", "DocIssueDate", "DocIssuedBy", "DocNumber", "Gender", "PartnerName", "City", "IBAN", "RFId", "BTag", "IsUsingLoyaltyProgram", "LoyaltyLevelId", "TimeZone", "IsLoggedIn", "IsResident", "IsSubscribedToNewsletter", "IsTest", "IsVerified", "Language", "LastLoginLocalDate", "MiddleName", "MobilePhone", "Phone", "ProfileId", "PromoCode", "Province", "CountryName", "RegistrationSource", "SportsbookProfileId", "SwiftCode", "Title", "ZipCode", "IsLocked",

    );
}

/* Establece los datos a incluir en un reporte de jugadores específico. */
if ($ReportName == "PlayersTable") {
    /*$response["Data"] = array(
        "Id", "Ip", "Clave", "Estado", "EstadoEspecial", "PermiteRecargas", "ImprimeRecibo", "Pais", "Idioma", "Nombre", "TipoUsuario", "Intentos", "Observaciones", "PinAgent", "BloqueoVentas", "Moneda", "ActivarRecarga", "Login", "FirstName", "LastName", "PersonalId", "Email", "AffilateId", "BTag", "IsSubscribeToEmail", "IsSubscribeToSMS", "ExternalId", "AccountHolder", "Address", "BirthCity", "BirthDate", "BirthDepartment", "BirthRegionCode2", "BirthRegionId", "CashDesk", "CreatedLocalDate", "CurrencyId", "DocIssueCode", "DocIssueDate", "DocIssuedBy", "DocNumber", "Gender", "PartnerName", "City", "IBAN", "RFId", "BTag", "IsUsingLoyaltyProgram", "LoyaltyLevelId", "TimeZone", "IsLoggedIn", "IsResident", "IsSubscribedToNewsletter", "IsTest", "IsVerified", "Language", "LastLoginLocalDate", "MiddleName", "MobilePhone", "Phone", "ProfileId", "PromoCode", "Province", "CountryName", "RegistrationSource", "SportsbookProfileId", "SwiftCode", "Title", "ZipCode", "IsLocked",

    );*/
    $response["Data"] = array(
        "Id", "Ip", "Clave", "Estado", "EstadoEspecial", "PermiteRecargas", "ImprimeRecibo", "Pais", "Idioma", "Nombre", "TipoUsuario", "Intentos", "Observaciones", "PinAgent", "BloqueoVentas", "Moneda", "ActivarRecarga", "Login", "FirstName", "LastName", "PersonalId", "Email", "AffilateId", "BTag", "ExternalId", "AccountHolder", "CashDesk", "CreatedLocalDate", "CurrencyId", "DocIssueCode", "DocIssueDate", "DocIssuedBy", "DocNumber", "Gender", "PartnerName", "City", "IBAN", "RFId", "BTag", "IsUsingLoyaltyProgram", "LoyaltyLevelId", "TimeZone", "IsTest", "IsVerified", "Language", "LastLoginLocalDate", "MiddleName", "MobilePhone", "Phone", "ProfileId", "PromoCode", "Province", "CountryName", "RegistrationSource", "SportsbookProfileId", "SwiftCode", "Title", "ZipCode", "IsLocked",

    );
}


/* asigna datos específicos a una variable basada en una condición. */
if ($ReportName == "DashboardSettings") {
    $response["Data"] = array(
        "ActivePlayersToday", "NewRegistrationToday", "SportsByStakes", "TopFiveGames", "SportBets", "CasinoBets", "TopFiveMatches", "TopFiveCasinoPlayers",

    );
}

/* asigna datos a una respuesta según el nombre del reporte específico. */
if ($ReportName == "DepositReportSettings") {
    $response["Data"] = array(
        "Id", "ClientId", "CreatedLocal", "TypeName", "CurrencyId", "ModifiedLocal", "PaymentSystemName", "CashDeskId", "State", "ExternalId", "Amount",

    );
}
//                   "ClientId","ClientLogin", "Id", "Type", "Currency", "State", "Amount", "TransactionDate", "CreatedTime", "Modified", "ModifiedSessionId", "Notes"


/* Asigna campos de datos a una respuesta para el reporte de solicitudes de retiro. */
if ($ReportName == "WithdrawalRequests") {
    $response["Data"] = array(
        "Id", "ClientId", "ClientLogin", "ClientName", "State", "Amount", "RequestTime", "BetshopId", "BetShopName", "RejectUserName", "AllowUserName", "PaidUserName", "Notes", "Info", "PaymentSystemId", "PaymentSystemName", "AllowTimeLocal", "RejectReason"

    );
}


/* asigna datos a la respuesta si el nombre del reporte es "LiquidationRequests". */
if ($ReportName == "LiquidationRequests") {
    $response["Data"] = array(
        "Id", "ClientId", "ClientLogin", "ClientName", "State", "Amount", "RequestTime", "RejectUserName", "AllowUserName", "PaidUserName", "Notes", "Info", "RejectReason"

    );
}


/* asigna un array de datos a la variable $response si el nombre es "DepositRequests". */
if ($ReportName == "DepositRequests") {
    $response["Data"] = array(
        "Id", "ClientId", "ClientLogin", "ClientName", "State", "Amount", "RequestTime", "RejectUserName", "AllowUserName", "PaidUserName", "Notes", "Info", "RejectReason", "PaymentSystemId"

    );
}