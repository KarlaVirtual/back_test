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
 * Accounting/GetProductsByBetshop
 *
 * Obtener el producto de un punto de venta
 *
 * @param object $params Objeto que contiene los parámetros de la solicitud:
 * @param int $params->BetShopId ID del punto de venta.
 * @param string $params->State Estado del producto.
 * @param int $params->MaxRows Número máximo de filas a obtener.
 * @param int $params->OrderedItem Ítem ordenado.
 * @param int $params->SkeepRows Número de filas a omitir.
 * @param string $params->ProductsThird Productos de terceros.
 *
 * @return array Respuesta con los siguientes datos:
 *  - bool $response["HasError"] Indica si hubo un error.
 *  - string $response["AlertType"] Tipo de alerta.
 *  - string $response["AlertMessage"] Mensaje de alerta.
 *  - array $response["ModelErrors"] Errores del modelo.
 *  - array $response["Data"] Datos finales.
 *  - int $response["pos"] Posición de inicio.
 *  - int $response["total_count"] Conteo total de datos.
 *  - array $response["data"] Datos finales.
 *
 * @throws Exception Si ocurre un error durante la ejecución.
 */


/* Extrae parámetros de una solicitud, incluyendo BetShopId, State, MaxRows y OrderedItem. */
$BetShopId = $params->BetShopId;
$State = $params->State;


$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;

/* obtiene y asigna valores de parámetros y solicitudes HTTP a variables. */
$SkeepRows = $params->SkeepRows;

$MaxRows = $_REQUEST["count"];
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

$ProductsThird = $_REQUEST["ProductsThird"];

/* asigna valores predeterminados a variables basadas en condiciones. */
$BetShopId = $_REQUEST["BetShopId"];

if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* Establece un valor predeterminado y define reglas de filtrado para productos. */
if ($MaxRows == "") {
    $MaxRows = 100000;
}

$rules = [];

array_push($rules, array("field" => "producto_tercero.tiene_cupo", "data" => "S", "op" => "eq"));

/* Agrega reglas para validar el estado y el producto en un array. */
array_push($rules, array("field" => "productotercero_usuario.estado", "data" => "A", "op" => "eq"));


if ($ProductsThird != "") {
    array_push($rules, array("field" => "productotercero_usuario.producto_id", "data" => $ProductsThird, "op" => "eq"));
}


/* Crea un filtro de reglas basado en el usuario de BetShopId. */
if ($BetShopId != "") {
    $Usuario = new Usuario($BetShopId);
    array_push($rules, array("field" => "usuario.usuario_id", "data" => $Usuario->puntoventaId, "op" => "eq"));
}

$filtro = array("rules" => $rules, "groupOp" => "AND");

/* convierte un filtro a JSON y obtiene productos de usuarios específicos. */
$json = json_encode($filtro);

$ProductoTerceroUsuario = new ProductoTerceroUsuario();

$data = $ProductoTerceroUsuario->getProductoterceroUsuariosCustom(" usuario.login,usuario.usuario_id, producto_tercero.*,productotercero_usuario.* ", "productotercero_usuario.prodtercusuario_id", "asc", $SkeepRows, $MaxRows, $json, true);

$data = json_decode($data);

/* agrupa información de productos en un array final. */
$final = [];
$products = "#";
foreach ($data->data as $key => $value) {

    $array = [];


    $array["Id"] = $value->{"productotercero_usuario.prodtercusuario_id"};
    $array["BetShop"] = $value->{"usuario.login"};
    $array["ProductsThird"] = $value->{"producto_tercero.descripcion"};

    $array["BetShopId"] = $value->{"usuario.usuario_id"};

    $array["Quota"] = $value->{"productotercero_usuario.cupo"};

    array_push($final, $array);

}


/* configura una respuesta sin errores, con datos finales y mensaje de éxito. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;

/* Crea una respuesta con posición, conteo total y datos finales. */
$response["pos"] = $SkeepRows;
$response["total_count"] = oldCount($final);
$response["data"] = $final;
