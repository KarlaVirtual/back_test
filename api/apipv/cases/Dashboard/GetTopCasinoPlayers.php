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
 * Dashboard/GetTopCasinoPlayers
 *
 * Reporte de Apuestas y Premios por Usuario
 *
 * Este script procesa un rango de fechas y obtiene un identificador de moneda para
 * generar un reporte de apuestas y premios de usuarios, excluyendo un país específico.
 * Se agrupan y ordenan los resultados por total de apuestas, con un límite en la cantidad de registros retornados.
 *
 * @param object $params Objeto con los parámetros de entrada.
 * @param int $params ->CurrencyId Identificador de la moneda de referencia para la conversión.
 * @param string $params ->ToDateLocal Fecha final del rango en formato "Y-m-d".
 * @param string $params ->FromDateLocal Fecha inicial del rango en formato "Y-m-d".
 * @param int|null $Region (Opcional) Identificador de la región para filtrar los resultados.
 * @param string|null $Currency (Opcional) Código de la moneda para filtrar los resultados.
 *
 * @return array response Respuesta estructurada del reporte.
 * @return bool response["HasError"] Indica si hubo un error en la ejecución.
 * @return string response["AlertType"] Tipo de alerta ("success" si no hay errores).
 * @return string response["AlertMessage"] Mensaje de alerta (vacío si no hay errores).
 * @return array response["ModelErrors"] Lista de errores en el modelo (vacío si no hay errores).
 * @return array response["Data"] Lista con los datos de los usuarios y sus apuestas.
 * @return string response["Data"][].UserName Nombre de usuario.
 * @return int response["Data"][].ClientId Identificador del usuario.
 * @return int response["Data"][].CountryId Identificador del país del usuario.
 * @return float response["Data"][].Stakes Total apostado en la moneda de referencia.
 * @return float response["Data"][].Winnings Total de premios en la moneda de referencia.
 * @return float response["Data"][].Profitness Diferencia entre apuestas y premios.
 *
 * @throws Exception En caso de error en la consulta SQL o conversión de divisas.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* procesa fechas y obtiene un identificador de moneda para una operación. */
$CurrencyId = $params->CurrencyId;

$ToDateLocal = date("Y-m-d", strtotime(str_replace(" - ", " ", $params->ToDateLocal)));

$FromDateLocal = date("Y-m-d", strtotime(str_replace(" - ", " ", $params->FromDateLocal)));


$UsuarioRecargaMySqlDAO = new \Backend\mysql\UsuarioRecargaMySqlDAO();


/* Consulta SQL que obtiene totales de apuestas y premios de usuarios excluyendo un país específico. */
$sql = "SELECT usuario.moneda,usuario.usuario_id,usuario.login,usuario.pais_id,SUM(usuario_casino_resumen.valor) total_apuestas,SUM(usuario_casino_resumen.valor_premios) total_premios FROM usuario_casino_resumen INNER JOIN usuario_mandante ON ( usuario_casino_resumen.usuario_id = usuario_mandante.usumandante_id) INNER JOIN usuario ON ( usuario_mandante.usuario_mandante = usuario.usuario_id ) WHERE  1=1 ";

$sql = $sql . " AND usuario_casino_resumen.fecha_crea >='" . $FromDateLocal . "' ";
$sql = $sql . " AND usuario_casino_resumen.fecha_crea <'" . $ToDateLocal . "' ";

$sql = $sql . " AND usuario.pais_id !='1' ";

/* agrega condiciones SQL basadas en las variables $Region y $Currency. */
if ($Region != "") {
    $sql = $sql . " AND usuario.pais_id='" . $Region . "' ";
}


if ($Currency != "") {
    //$sql = $sql. "AND usuario.moneda='".$Currency."'";
}


/* Consulta SQL que agrupa y ordena usuarios por total de apuestas, limitando resultados. */
$sql = $sql . " GROUP BY usuario_casino_resumen.usuario_id";
$sql = $sql . " ORDER BY total_apuestas DESC";
$sql = $sql . " LIMIT 5";


$depositos = $UsuarioRecargaMySqlDAO->querySQL($sql);


/* Código inicializa un array y dos variables para almacenar valores numéricos. */
$return = array();
$valor_convertido = 0;
$total = 0;
foreach ($depositos as $key => $value) {

    /* crea un array con información del usuario y convierte su moneda. */
    $data = array();
    $data['UserName'] = $value["usuario.login"];
    $data['ClientId'] = $value["usuario.usuario_id"];
    $data['CountryId'] = $value["usuario.pais_id"];

    $data['Stakes'] = (new ConfigurationEnvironment())->currencyConverter($value["usuario.moneda"], $CurrencyId, round($value[".total_apuestas"], 2));

    /* Calcula ganancias, apuestas y rentabilidad usando conversión de divisas y redondeo. */
    $data['Winnings'] = (new ConfigurationEnvironment())->currencyConverter($value["usuario.moneda"], $CurrencyId, round($value[".total_premios"], 2));

    $data['Stakes'] = round($value[".total_apuestas"], 2);
    $data['Winnings'] = round($value[".total_premios"], 2);


    $data['Profitness'] = $data['Stakes'] - $data['Winnings'];


    /* Acumula valores convertidos y totales en un arreglo para retorno. */
    $valor_convertido = $valor_convertido + $converted_currency;
    $total = $total + $value[".cantidad"];

    array_push($return, $data);

}


/* Código establece una respuesta exitosa sin errores ni mensajes adicionales. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $return;

