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
use Backend\dto\Consecutivo;use Backend\dto\ConfigurationEnvironment;
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
 * Servicio para obtener productos asociados a un socio comercial por país
 * 
 * @param object $params Parámetros de entrada
 * @param string $params->Id ID del producto mandante
 * @param string $params->IsActivate Estado de activación (A/I)
 * @param string $params->IsVerified Estado de verificación
 * @param string $params->FilterCountry País para filtrar
 * @param string $params->Products Lista de productos
 * @param string $params->Partner ID del socio comercial
 * @param float $params->Minimum Valor mínimo
 * @param float $params->Maximum Valor máximo
 * @param string $params->Product ID del producto
 * @param int $_REQUEST["count"] Número de registros a retornar
 * @param int $_REQUEST["start"] Número de registro inicial
 * @param string $params->OrderedItem Campo para ordenar resultados
 * 
 * @return object Respuesta del servicio
 * @return boolean $response->HasError Indica si hubo error
 * @return string $response->AlertType Tipo de alerta (success/error)
 * @return string $response->AlertMessage Mensaje descriptivo
 * @return array $response->ModelErrors Errores del modelo
 * @return array $response->Data Lista de productos encontrados
 * @return int $response->TotalCount Total de registros
 * @throws Exception Si ocurre un error durante el proceso
 */



/* maneja la paginación de resultados solicitando el conteo y el inicio de filas. */
$MaxRows = $_REQUEST["count"];
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* Asigna valores predeterminados a $OrderedItem y $MaxRows si están vacíos. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000;
}



/* Asignación de variables a partir de parámetros recibidos en una solicitud. */
$Id = $params->Id;
$IsActivate = $params->IsActivate;
$IsVerified = $params->IsVerified;
$FilterCountry = $params->FilterCountry;
$Products = $params->Products;
$Partner = $params->Partner;

/* asigna valores de parámetros a variables y crea un objeto ProductoMandantePais. */
$Minimum = $params->Minimum;
$Maximum = $params->Maximum;
$Product = $params->Product;


$ProductoMandante = new ProductoMandantePais();


/* crea reglas de filtrado basadas en condiciones específicas de entrada. */
$rules = [];

if ($Id != "") {
    array_push($rules, array("field" => "producto_mandante.prodmandante_id", "data" => "$Id", "op" => "eq"));
}

if ($IsActivate != "" && $IsActivate != null) {
    $IsActivate = ($IsActivate == 'A') ? 'A' : 'I';

    array_push($rules, array("field" => "producto_mandante.estado", "data" => "$IsActivate", "op" => "eq"));
}


/* valida y agrega condiciones a un array según variables específicas. */
if ($IsVerified != "" && $IsVerified != null) {
    $IsVerified = ($IsVerified == 'A') ? 'A' : 'I';

    array_push($rules, array("field" => "producto_mandante.verifica", "data" => "$IsVerified", "op" => "eq"));
}


if ($FilterCountry != "" && $FilterCountry != null) {
    $FilterCountry = ($FilterCountry == 'A') ? 'A' : 'I';

    array_push($rules, array("field" => "producto_mandante.filtro_pais", "data" => "$FilterCountry", "op" => "eq"));
}


/* Agrega reglas de filtrado basadas en el valor de $Partner y $Minimum. */
if ($Partner != "") {

    array_push($rules, array("field" => "producto_mandante.mandante", "data" => "$Partner", "op" => "eq"));
}

if ($Minimum != "") {

    array_push($rules, array("field" => "producto_mandante.min", "data" => "$Minimum", "op" => "eq"));
}


/* Agrega reglas de validación si los valores no son vacíos. */
if ($Maximum != "") {

    array_push($rules, array("field" => "producto_mandante.max", "data" => "$Maximum", "op" => "eq"));
}


if ($Product != "") {

    array_push($rules, array("field" => "producto_mandante.producto_id", "data" => "$Product", "op" => "eq"));
}


/* filtra y obtiene productos de una base de datos en formato JSON. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$jsonfiltro = json_encode($filtro);

$productos = $ProductoMandante->getProductosMandanteCustom(" producto_mandante.*,producto.*,mandante.* ", "producto_mandante.prodmandante_id", "asc", $SkeepRows, $MaxRows, $jsonfiltro, true);

$productos = json_decode($productos);


/* Transforma datos de productos en un formato estructurado y los almacena en un arreglo. */
$final = [];

foreach ($productos->data as $key => $value) {

    $array = [];

    $array["Id"] = $value->{"producto_mandante.prodmandante_id"};

    $array["Product"] = $value->{"producto.descripcion"};
    $array["Partner"] = $value->{"mandante.descripcion"};
    $array["IsActivate"] = $value->{"producto_mandante.estado"};
    $array["IsVerified"] = $value->{"producto_mandante.verifica"};
    $array["FilterCountry"] = $value->{"producto_mandante.filtro_pais"};
    $array["Maximum"] = $value->{"producto_mandante.max"};
    $array["Minimum"] = $value->{"producto_mandante.min"};
    $array["ProcessingTime"] = $value->{"producto_mandante.tiempo_procesamiento"};

    array_push($final, $array);

}


/* Se inicializan respuestas exitosas para una solicitud, incluyendo errores y datos. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

//$response["Data"] = array("Objects" => $final, "Count" => $productos->count[0]->{".count"});
$response["pos"] = $SkeepRows;

/* asigna el conteo total de productos y los datos finales a una respuesta. */
$response["total_count"] = $productos->count[0]->{".count"};
$response["data"] = $final;

