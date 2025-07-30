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
 * Dashboard/GetDepositSummary
 *
 * Este script obtiene un resumen de los depósitos realizados por los usuarios, incluyendo el total de depósitos y su conversión a una moneda específica.
 *
 * @param object $params Objeto JSON con los siguientes atributos:
 * @param string $params->FromDateLocal Fecha inicial del rango en formato "Y-m-d H:i:s".
 * @param string $params->ToDateLocal Fecha final del rango en formato "Y-m-d H:i:s".
 * @param string $params->Region Identificador de la región para filtrar los usuarios.
 * @param string $params->CurrencyId Moneda destino para la conversión.
 * @param int $params->MaxRows Número máximo de filas a devolver.
 * @param int $params->OrderedItem Orden de los elementos (1 por defecto).
 * @param int $params->SkeepRows Número de filas a omitir (0 por defecto).
 * 
 *
 * @return array $response Respuesta en formato JSON con los siguientes atributos:
 *  - HasError (boolean): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta (e.g., "success", "error").
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *  - Data (array): Información estructurada con los siguientes datos:
 *      - DepositTotalCount (int): Total de depósitos realizados.
 *      - DepositTotalAmount (float): Monto total de depósitos convertidos a la moneda especificada.
 *
 * @throws Exception No se lanza ninguna excepción explícita en este script.
 */

/* Crea un objeto y procesa una fecha de entrada en formato específico. */
$UsuarioRecarga = new UsuarioRecarga();

$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->ToDateLocal) . ' +1 day'));

/* manipula fechas y obtiene parámetros como región y moneda. */
$FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->FromDateLocal)));

$Region = $params->Region;
$CurrencyId = $params->CurrencyId;

$MaxRows = $params->MaxRows;

/* Se definen reglas para filtrar datos basadas en parámetros de entrada. */
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

$rules = [];

array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));

/* agrega reglas a un array basadas en condiciones específicas. */
array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));

if ($Region != "") {
    array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
}

if ($Currency != "") {
    array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
}


/* crea un filtro JSON y establece filas a omitir. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* asigna valores predeterminados si las variables están vacías. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 10000000;
}


/* Se obtienen recargas de usuarios y se configuran para conversión en coronas checas. */
$usuarios = $UsuarioRecarga->getUsuarioRecargasCustom("COUNT(*) count,SUM(usuario_recarga.valor) valor,usuario.moneda", "usuario_recarga.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true, "usuario.moneda");

$usuarios = json_decode($usuarios);
setlocale(LC_ALL, 'czech');

$valor_convertido = 0;

/* Calcula el total y convierte monedas para usuarios en un bucle. */
$total = 0;
foreach ($usuarios->data as $key => $value) {

    $converted_currency = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario.moneda"}, $CurrencyId, round($value->{".valor"}, 2));
    $valor_convertido = $valor_convertido + $converted_currency;
    $total = $total + $value->{".count"};

}


/* define una respuesta con estado exitoso y datos de depósito. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = array(
    "DepositTotalCount" => $total,
    "DepositTotalAmount" => $valor_convertido,

);