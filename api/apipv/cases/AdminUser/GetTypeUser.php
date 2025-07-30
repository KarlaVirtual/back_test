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
 * Envía en listado de usuarios manejados por la plataforma
 * @retun array
 *  -boolean HasError - Indica si ocurrió un error en el proceso
 *  -string AlertType - Tipo de alerta a mostrar (success, warning, danger, info)
 *  - id: string - Identificador del tipo de usuario
 *  - value: string - Nombre del tipo de usuario
 */

$final = array(
    array(
        "id" => "ADMIN",
        "value" => "Administrador General"
    ),
    array(
        "id" => "ADMIN2",
        "value" => "Administrador Secundario"
    ),
    array(
        "id" => "ADMINPARTNER",
        "value" => "ADMINPARTNER"
    ),
    array(
        "id" => "ADMINPARTNER2",
        "value" => "ADMINPARTNER2"
    ),
    array(
        "id" => "ADMINPARTNERTER",
        "value" => "ADMINPARTNERTER"
    ),
    array(
        "id" => "ADMPARTNERTER",
        "value" => "ADMPARTNERTER"
    ),
    array(
        "id" => "FINANCIERO",
        "value" => "FINANCIERO"
    ),
    array(
        "id" => "FINANCIEROTERC",
        "value" => "FINANCIEROTERC"
    ),
    array(
        "id" => "ADMINPARTNERCON",
        "value" => "ADMINPARTNERCON"
    ),
    array(
        "id" => "AFILIADOR",
        "value" => "AFILIADOR"
    ),
    array(
        "id" => "CAJERO",
        "value" => "CAJERO"
    ),
    array(
        "id" => "OPERADOR",
        "value" => "Operador"
    ),
    array(
        "id" => "SA",
        "value" => "Super Administrador"
    ),
    array(
        "id" => "CONSULTAS",
        "value" => "Consultas"
    ),
    array(
        "id" => "CONTABILIDAD",
        "value" => "Contabilidad"
    ),
    array(
        "id" => "ADMINAFILIADOS",
        "value" => "ADMIN AFILIADOS"
    ),
    array(
        "id" => "PUNTOVENTA",
        "value" => "PUTNO DE VENTA"
    ),
    array(
        "id" => "CONCESIONARIO",
        "value" => "CONCESIONARIO"
    ),
    array(
        "id" => "CONCESIONARIO2",
        "value" => "SUBCONCESIONARIO"
    ),
    array(
        "id" => "CONCESIONARIO3",
        "value" => "SUBCONCESIONARIO2"
    ),
    array(
        "id" => "CUSTOM",
        "value" => "CUSTOM"
    ),
    array(
        "id" => "COORDOPER",
        "value" => "COORDOPER"
    ),
    array(
        "id" => "ANALISTAOPER",
        "value" => "ANALISTAOPER"
    ),
    array(
        "id" => "COORDSOPORTE",
        "value" => "COORDSOPORTE"
    ),
    array(
        "id" => "OPERSOPORTE",
        "value" => "OPERSOPORTE"
    ),
    array(
        "id" => "COORDCONTRIESGO",
        "value" => "COORDCONTRIESGO"
    ),
    array(
        "id" => "ANALISTACONTRIE",
        "value" => "ANALISTACONTRIE"
    ),
    array(
        "id" => "COMERCIAL",
        "value" => "COMERCIAL"
    ),
    array(
        "id" => "ACCOUNT",
        "value" => "ACCOUNT"
    ),
    array(
        "id" => "TIADMIN",
        "value" => "TIADMIN"
    ),
    array(
        "id" => "TIINCIDENTES",
        "value" => "TIINCIDENTES"
    ),
    array(
        "id" => "TICONFIGURACION",
        "value" => "TICONFIGURACION"
    ),
    array(
        "id" => "TIGESTION",
        "value" => "TIGESTION"
    ),
    array(
        "id" => "TIBI",
        "value" => "TIBI"
    ),
    array(
        "id" => "ADMINVIRTUAL",
        "value" => "ADMINVIRTUAL"
    ),
    array(
        "id" => "IMPLEMENT",
        "value" => "IMPLEMENT"
    ),
    array(
        "id" => "QUOTA",
        "value" => "QUOTA"
    ),
    array(
        "id" => "ADMINPARTNERSIN",
        "value" => "ADMINPARTNERSIN"
    ),
    array(
        "id" => "TESORERIA",
        "value" => "TESORERIA"
    ),
    array(
        "id" => "AUXOPERAC",
        "value" => "Auxiliar Operaciones"
    ),
    array(
        "id" => "AUXTESORBAN",
        "value" => "Auxiliar Tesoreria Bancos"
    ),
    array(
        "id" => "AUXTESORPAG",
        "value" => "Auxiliar Tesoreria Pagos"
    ),
    array(
        "id" => "AUXTESORPAS",
        "value" => "Auxiliar Tesoreria Pasarelas"
    ),
    array(
        "id" => "AUXTESORPAS",
        "value" => "Auxiliar Tesoreria Pasarelas"
    ),
    array(
        "id" => "SERVCLIENTE",
        "value" => "Servicio al Cliente"
    ),
    array(
        "id" => "SUPOPERAC",
        "value" => "Supervisor Operaciones"
    ),
    array(
        "id" => "SUPTESOR",
        "value" => "Supervisor Tesorería FPS"
    ),
    array(
        "id" => "SUPTESOR",
        "value" => "Supervisor Tesorería FPS"
    ),
    array(
        "id" => "DIROPERAC",
        "value" => "Director Operaciones"
    )
);



/* inicializa una respuesta de éxito sin errores en un sistema. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;