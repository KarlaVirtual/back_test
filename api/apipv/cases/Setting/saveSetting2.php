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
 * Setting/saveSetting2
 *
 * Guardar configuraciones específicas del usuario.
 *
 * @param $params object 
 * @param $params->Language string Idioma preferido del usuario.
 * @param $params->ReportCurrency string Moneda para reportes.
 * @param $params->ReportCountry int ID del país para reportes.
 * @param $params->TimeZone string Zona horaria del usuario.
 * 
 *
 * @return array {
 *     "HasError": boolean Indica si ocurrió un error.
 *     "AlertType": string Tipo de alerta (e.g., "success").
 *     "AlertMessage": string Mensaje de alerta.
 *     "ModelErrors": array Lista de errores del modelo.
 *     "Data": array Datos adicionales (vacío por defecto).
 * }
 */


/* asigna valores de parámetros y crea un objeto UsuarioMandante. */
$Language = $params->Language;
$Language = $params->lang;
$ReportCurrency = $params->ReportCurrency;
$ReportCountry = $params->ReportCountry;
$TimeZone = $params->TimeZone;

$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());


$UsuarioPerfil = new UsuarioPerfil($Usuario->usuarioId);


    /* Condición que valida múltiples perfiles de usuario en la sesión actual. */
if ($UsuarioPerfil->getPerfilId() == "ADMIN" || $UsuarioPerfil->getPerfilId() == "ADMIN2" || $UsuarioPerfil->getPerfilId() == "SA" || $UsuarioPerfil->getPerfilId() == "CUSTOM"
    || $_SESSION["win_perfil2"] == "FINANCIEROTERC"
    || $_SESSION["win_perfil2"] == "FINANCIERO"
    || $_SESSION["win_perfil2"] == "DIROPERAC" || $_SESSION["win_perfil2"] == "SUPOPERAC" || $_SESSION["win_perfil2"] == "OPERSOPORTE" || $_SESSION["win_perfil2"] == "ADMINPARTNERTER" || $_SESSION["win_perfil2"] == "ADMPARTNERTER"


    || $_SESSION["win_perfil2"] == "COORDOPER" || $_SESSION["win_perfil2"] == "ANALISTAOPER" || $_SESSION["win_perfil2"] == "COORDSOPORTE"
    || $_SESSION["win_perfil2"] == "OPERSOPORTE" || $_SESSION["win_perfil2"] == "COORDCONTRIESGO" || $_SESSION["win_perfil2"] == "ANALISTACONTRIE"
    || $_SESSION["win_perfil2"] == "COMERCIAL" || $_SESSION["win_perfil2"] == "ACCOUNT" || $_SESSION["win_perfil2"] == "TIADMIN"
    || $_SESSION["win_perfil2"] == "TIINCIDENTES" || $_SESSION["win_perfil2"] == "TICONFIGURACION" || $_SESSION["win_perfil2"] == "TIGESTION"
    || $_SESSION["win_perfil2"] == "TIBI" || $_SESSION["win_perfil2"] == "ADMINVIRTUAL" || $_SESSION["win_perfil2"] == "IMPLEMENT" || $_SESSION["win_perfil2"] == "QUOTA"
) {
    if ($ReportCurrency != "") {
        $Usuario->monedaReporte = $ReportCurrency;
    }

    /* Verifica condiciones para asignar país y zona horaria a un usuario. */
    if ($ReportCountry != "" && is_numeric($ReportCountry)) {
        //$Usuario->paisId = $ReportCountry;
    }
    if ($TimeZone != "") {
        $Usuario->timezone = $TimeZone;
    }

    /* asigna un idioma y establece fecha si está vacía o nula. */
    if ($Language != "") {
        $Usuario->idioma = strtoupper($Language);

    }

    if ($Usuario->fechaDocvalido == "" || $Usuario->fechaDocvalido == "null" || $Usuario->fechaDocvalido == null) {
        $Usuario->fechaDocvalido = date('Y-m-d H:i:s');
    }


    /* Actualiza un usuario en la base de datos y guarda la zona horaria en sesión. */
    $UsuarioMySqlDAO = new UsuarioMySqlDAO();
    $UsuarioMySqlDAO->update($Usuario);

    $UsuarioMySqlDAO->getTransaction()->commit();

    $_SESSION["timezone"] = $Usuario->timezone;

    /* Guarda información del usuario y estado de respuesta en sesión. */
    $_SESSION["monedaReporte"] = $Usuario->monedaReporte;
    $_SESSION["idioma"] = $Usuario->idioma;

    $_SESSION["PaisCondS"] = $ReportCountry;

    $response["HasError"] = false;

    /* Código que inicializa un array de respuesta con tipo de alerta y errores vacíos. */
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    $response["Data"] = array();

} elseif ($UsuarioPerfil->getPerfilId() == "CONCESIONARIO" || $UsuarioPerfil->getPerfilId() == "CONCESIONARIO2" || $UsuarioPerfil->getPerfilId() == "CONCESIONARIO3" || $UsuarioPerfil->getPerfilId() == "PUNTOVENTA" || $UsuarioPerfil->getPerfilId() == "CAJERO" || $UsuarioPerfil->getPerfilId() == "AFILIADOR") {


    /* Asigna el idioma en mayúsculas si no está vacío y crea un objeto DAO. */
    if ($Language != "") {
        $Usuario->idioma = strtoupper($Language);

    }

    $UsuarioMySqlDAO = new UsuarioMySqlDAO();

    /* Actualiza información del usuario, confirma transacción y establece idioma en sesión. */
    $UsuarioMySqlDAO->update($Usuario);

    $UsuarioMySqlDAO->getTransaction()->commit();

    $_SESSION["idioma"] = $Usuario->idioma;

    $response["HasError"] = false;

    /* Código inicializa una respuesta con tipo de alerta, mensaje y errores vacíos. */
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    $response["Data"] = array();

} else {

    /* inicializa un array de respuesta con error y mensajes vacíos. */
    $response["HasError"] = true;
    $response["AlertType"] = "No puede cambiarlo";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    $response["Data"] = array();
}

