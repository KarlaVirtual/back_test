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
 * Dashboard/GetTopCasinoGames
 *
 * Procesamiento de tickets personalizados y recargas de usuario
 *
 * Este script procesa tickets personalizados y maneja recargas de usuario a partir de parámetros JSON recibidos,
 * aplica reglas de filtrado y conversión de moneda, y devuelve un resumen de apuestas.
 *
 * @param json $params Parámetros de entrada en formato JSON.
 * - ToDateLocal (string): Fecha límite superior para filtrar tickets y recargas.
 * - FromDateLocal (string): Fecha límite inferior para filtrar tickets y recargas.
 * - Region (string): Identificador de la región.
 * - CurrencyId (string): Identificador de la moneda para conversión.
 * - MaxRows (int): Número máximo de filas a obtener.
 * - OrderedItem (int): Orden de los elementos.
 * - SkeepRows (int): Número de filas a omitir en la consulta.
 *
 * @return json response Respuesta en formato JSON con los siguientes datos:
 * - HasError (bool): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (success, error, warning, etc.).
 * - AlertMessage (string): Mensaje de alerta en caso de error.
 * - ModelErrors (array): Lista de errores de validación.
 * - Data (array): Datos procesados con información de apuestas, premios y recargas:
 * - BetAmount (int): Monto total de apuestas convertido a la moneda especificada.
 * - WinningAmount (int): Monto total de premios convertido a la moneda especificada.
 * - BetCount (int): Número total de apuestas.
 * - Recargas (array): Información de recargas por usuario y producto:
 * - Game (string): Nombre del producto.
 * - Stakes (float): Monto total apostado en el producto.
 * - Winnings (float): Monto total de premios en el producto.
 * - Profitness (float): Ganancia neta calculada.
 *
 * @throws Exception Si hay errores en la conversión de moneda o en el procesamiento de datos.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Se preparan fechas y se inicializa un objeto para manejo de recargas de usuario. */
$CurrencyId = $params->CurrencyId;

$ToDateLocal = date("Y-m-d", strtotime(str_replace(" - ", " ", $params->ToDateLocal)));

$FromDateLocal = date("Y-m-d", strtotime(str_replace(" - ", " ", $params->FromDateLocal)));


$UsuarioRecargaMySqlDAO = new \Backend\mysql\UsuarioRecargaMySqlDAO();


/* Consulta SQL que obtiene totales de apuestas agrupadas por usuario y producto. */
$sql = "SELECT usuario.moneda,usucasino_detalle_resumen.producto_id,producto.descripcion producto,SUM(usucasino_detalle_resumen.valor) total_apuestas FROM usucasino_detalle_resumen INNER JOIN usuario_mandante ON ( usucasino_detalle_resumen.usuario_id = usuario_mandante.usumandante_id) INNER JOIN usuario ON ( usuario_mandante.usuario_mandante = usuario.usuario_id) 
  INNER JOIN producto_mandante ON (usucasino_detalle_resumen.producto_id = producto_mandante.prodmandante_id)
  INNER JOIN producto ON (producto_mandante.producto_id = producto.producto_id)

WHERE  1=1 ";

$sql = $sql . " AND usucasino_detalle_resumen.fecha_crea >='" . $FromDateLocal . "' ";

/* SQL filtra registros basados en fechas, tipo y condiciones de país. */
$sql = $sql . " AND usucasino_detalle_resumen.fecha_crea <'" . $ToDateLocal . "' ";
$sql = $sql . " AND tipo=1 ";
$sql = $sql . " AND usuario.pais_id !='1' ";

if ($Region != "") {
    $sql = $sql . " AND usuario.pais_id='" . $Region . "' ";
}


/* Condiciona una consulta SQL para agrupar y ordenar por moneda y total de apuestas. */
if ($Currency != "") {
    //$sql = $sql. "AND usuario.moneda='".$Currency."'";
}

$sql = $sql . " GROUP BY usuario.moneda,usucasino_detalle_resumen.producto_id";
$sql = $sql . " ORDER BY total_apuestas DESC";

/* limita una consulta SQL a 5 resultados y los almacena en un array. */
$sql = $sql . " LIMIT 5";


$depositos = $UsuarioRecargaMySqlDAO->querySQL($sql);

$return = array();

/* Convierte apuestas en diferentes monedas y calcula ganancias y total acumulado. */
$valor_convertido = 0;
$total = 0;
foreach ($depositos as $key => $value) {
    $data = array();

    $data['Game'] = $value["producto.producto"];
    $data['Stakes'] = (new ConfigurationEnvironment())->currencyConverter($value["usuario.moneda"], $CurrencyId, round($value[".total_apuestas"], 2));

    $data['Stakes'] = round($value[".total_apuestas"], 2);
    $data['Winnings'] = 0;
    $data['Profitness'] = $data['Stakes'] - $data['Winnings'];

    $valor_convertido = $valor_convertido + $converted_currency;
    $total = $total + $value[".cantidad"];

    array_push($return, $data);

}


/* construye una respuesta estructurada, indicando éxito y sin errores. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $return;
