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
 * Obtener ajustes actuales.
 *
 * Este script devuelve los ajustes actuales, incluyendo monedas y zonas horarias disponibles.
 *
 * @param object $params No se utilizan parámetros directos, pero se accede a las variables de sesión:
 *  - PaisCond: string Indicador de condición del país.
 *  - win_perfil: string Perfil del usuario.
 *  - moneda: string Moneda seleccionada.
 *
 * @return array Respuesta estructurada:
 *  - HasError: boolean Indica si ocurrió un error.
 *  - AlertType: string Tipo de alerta.
 *  - AlertMessage: string Mensaje de alerta.
 *  - ModelErrors: array Errores del modelo.
 *  - Data: array Datos adicionales, incluyendo:
 *    - ReportCurrencies: array Monedas disponibles.
 *    - TimeZones: array Zonas horarias disponibles.
 *
 * @throws No se lanzan excepciones explícitas.
 */


/* inicializa una respuesta sin errores y un array para monedas. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$ReportCurrencies = array();


/* verifica condiciones de sesión para asignar valores a un arreglo. */
if ($_SESSION['PaisCond'] == "S" || ($_SESSION['win_perfil'] != "ADMIN" && $_SESSION['win_perfil'] != "ADMIN2" && $_SESSION['win_perfil'] != "SA" && $_SESSION['win_perfil'] != "OPERADOR")) {
    $ReportCurrencies = array(
        "Id" => $_SESSION["moneda"],
        "Name" => $_SESSION["moneda"]
    );
} else {
    $ReportCurrencies = array(
        array(
            "Id" => "EUR",
            "Name" => "EUR",
            "IsSelected" => 0,
        ),
        array(
            "Id" => "PEN",
            "Name" => "PEN",
            "IsSelected" => 0,
        ),

        array(
            "Id" => "USD",
            "Name" => "USD",
            "IsSelected" => 0,
        ),

        array(
            "Id" => "MXN",
            "Name" => "MXN",
            "IsSelected" => 0,
        )

    );

}


/* Crea un arreglo con monedas y zonas horarias, marcando una seleccionada. */
$response["Data"] = array(
    "ReportCurrencies" => $ReportCurrencies,
    "TimeZones" => array(array("DisplayName" => "UTC−12:00", "Id" => -12.00, "IsSelected" => true), array("DisplayName" => "UTC−11:00", "Id" => -11.00, "IsSelected" => false), array("DisplayName" => "UTC−10:00", "Id" => -10.00, "IsSelected" => false), array("DisplayName" => "UTC−09:30", "Id" => -9.50, "IsSelected" => false), array("DisplayName" => "UTC−09:00", "Id" => -9.00, "IsSelected" => false), array("DisplayName" => "UTC−08:00", "Id" => -8.00, "IsSelected" => false), array("DisplayName" => "UTC−07:00", "Id" => -7.00, "IsSelected" => false), array("DisplayName" => "UTC−06:00", "Id" => -6.00, "IsSelected" => false), array("DisplayName" => "UTC−05:00", "Id" => -5.00, "IsSelected" => false), array("DisplayName" => "UTC−04:30", "Id" => -4.50, "IsSelected" => false), array("DisplayName" => "UTC−04:00", "Id" => -4.00, "IsSelected" => false), array("DisplayName" => "UTC−03:30", "Id" => -3.50, "IsSelected" => false), array("DisplayName" => "UTC−03:00", "Id" => -3.00, "IsSelected" => false), array("DisplayName" => "UTC−02:00", "Id" => -2.00, "IsSelected" => false), array("DisplayName" => "UTC−01:00", "Id" => -1.00, "IsSelected" => false), array("DisplayName" => "UTC−00:00", "Id" => 0.00, "IsSelected" => false), array("DisplayName" => "UTC+01:00", "Id" => 1.00, "IsSelected" => false), array("DisplayName" => "UTC+02:00", "Id" => 2.00, "IsSelected" => false), array("DisplayName" => "UTC+03:00", "Id" => 3.00, "IsSelected" => false), array("DisplayName" => "UTC+03:30", "Id" => 3.50, "IsSelected" => false), array("DisplayName" => "UTC+04:00", "Id" => 4.00, "IsSelected" => false), array("DisplayName" => "UTC+04:30", "Id" => 4.50, "IsSelected" => false), array("DisplayName" => "UTC+05:00", "Id" => 5.00, "IsSelected" => false), array("DisplayName" => "UTC+05:30", "Id" => 5.50, "IsSelected" => false), array("DisplayName" => "UTC+06:00", "Id" => 6.00, "IsSelected" => false), array("DisplayName" => "UTC+06:30", "Id" => 6.50, "IsSelected" => false), array("DisplayName" => "UTC+07:00", "Id" => 7.00, "IsSelected" => false), array("DisplayName" => "UTC+08:00", "Id" => 8.00, "IsSelected" => false), array("DisplayName" => "UTC+08:45", "Id" => 8.75, "IsSelected" => false), array("DisplayName" => "UTC+09:00", "Id" => 9.00, "IsSelected" => false), array("DisplayName" => "UTC+09:30", "Id" => 9.50, "IsSelected" => false), array("DisplayName" => "UTC+10:00", "Id" => 10.00, "IsSelected" => false), array("DisplayName" => "UTC+10:30", "Id" => 10.50, "IsSelected" => false), array("DisplayName" => "UTC+11:00", "Id" => 11.00, "IsSelected" => false), array("DisplayName" => "UTC+11:30", "Id" => 11.50, "IsSelected" => false), array("DisplayName" => "UTC+12:00", "Id" => 12.00, "IsSelected" => false), array("DisplayName" => "UTC+12:45", "Id" => 12.75, "IsSelected" => false), array("DisplayName" => "UTC+13:00", "Id" => 13.00, "IsSelected" => false), array("DisplayName" => "UTC+14:00", "Id" => 14.00, "IsSelected" => false))

);
//{"HasError":false,"AlertType":"success","AlertMessage":"Operation has completed successfully","ModelErrors":[],"Data":{"Languages":[{"Name":"English","Id":"en","IsSelected":true,"DisplayName":"English"),{"Name":"Russian","Id":"ru","IsSelected":false,"DisplayName":"Russian"},{"Name":"Spanish","Id":"es","IsSelected":false,"DisplayName":"Spanish"},{"Name":"Turkish","Id":"tr","IsSelected":false,"DisplayName":"Turkish"},{"Name":"Chinese","Id":"zh","IsSelected":false,"DisplayName":"Chinese"},{"Name":"Korean","Id":"ko","IsSelected":false,"DisplayName":"Korean"}],"TimeZones":[{"DisplayName":"UTC−12:00","Id":-12.00,"IsSelected":true},{"DisplayName":"UTC−11:00","Id":-11.00,"IsSelected":false},{"DisplayName":"UTC−10:00","Id":-10.00,"IsSelected":false},{"DisplayName":"UTC−09:30","Id":-9.50,"IsSelected":false},{"DisplayName":"UTC−09:00","Id":-9.00,"IsSelected":false},{"DisplayName":"UTC−08:00","Id":-8.00,"IsSelected":false},{"DisplayName":"UTC−07:00","Id":-7.00,"IsSelected":false},{"DisplayName":"UTC−06:00","Id":-6.00,"IsSelected":false},{"DisplayName":"UTC−05:00","Id":-5.00,"IsSelected":false},{"DisplayName":"UTC−04:30","Id":-4.50,"IsSelected":false},{"DisplayName":"UTC−04:00","Id":-4.00,"IsSelected":false},{"DisplayName":"UTC−03:30","Id":-3.50,"IsSelected":false},{"DisplayName":"UTC−03:00","Id":-3.00,"IsSelected":false},{"DisplayName":"UTC−02:00","Id":-2.00,"IsSelected":false},{"DisplayName":"UTC−01:00","Id":-1.00,"IsSelected":false},{"DisplayName":"UTC−00:00","Id":0.00,"IsSelected":false},{"DisplayName":"UTC+01:00","Id":1.00,"IsSelected":false},{"DisplayName":"UTC+02:00","Id":2.00,"IsSelected":false},{"DisplayName":"UTC+03:00","Id":3.00,"IsSelected":false},{"DisplayName":"UTC+03:30","Id":3.50,"IsSelected":false},{"DisplayName":"UTC+04:00","Id":4.00,"IsSelected":false},{"DisplayName":"UTC+04:30","Id":4.50,"IsSelected":false},{"DisplayName":"UTC+05:00","Id":5.00,"IsSelected":false},{"DisplayName":"UTC+05:30","Id":5.50,"IsSelected":false},{"DisplayName":"UTC+06:00","Id":6.00,"IsSelected":false},{"DisplayName":"UTC+06:30","Id":6.50,"IsSelected":false},{"DisplayName":"UTC+07:00","Id":7.00,"IsSelected":false},{"DisplayName":"UTC+08:00","Id":8.00,"IsSelected":false},{"DisplayName":"UTC+08:45","Id":8.75,"IsSelected":false},{"DisplayName":"UTC+09:00","Id":9.00,"IsSelected":false},{"DisplayName":"UTC+09:30","Id":9.50,"IsSelected":false},{"DisplayName":"UTC+10:00","Id":10.00,"IsSelected":false},{"DisplayName":"UTC+10:30","Id":10.50,"IsSelected":false},{"DisplayName":"UTC+11:00","Id":11.00,"IsSelected":false},{"DisplayName":"UTC+11:30","Id":11.50,"IsSelected":false},{"DisplayName":"UTC+12:00","Id":12.00,"IsSelected":false},{"DisplayName":"UTC+12:45","Id":12.75,"IsSelected":false},{"DisplayName":"UTC+13:00","Id":13.00,"IsSelected":false},{"DisplayName":"UTC+14:00","Id":14.00,"IsSelected":false}],"ReportCurrencies":[{"Id":"EUR","Name":"Euro","Precision":0,"IsVirtual":false,"IsSelected":true}],"ReportPartners":[{"Id":161640,"Name":"betbetbet","CompanyName":null,"Notes":null,"TimeZone":0.0,"CurrencyId":null,"LanguageId":null,"SiteUrl":null,"BalanceChangeTime":"00:00:00","LimitType":0,"FirstName":null,"LastName":null,"EMail":null,"Address":null,"Phone":null,"UserName":null,"Password":null,"IntegrationType":0,"IsSelected":true}],"IsSubscribedToNotification":null}}
