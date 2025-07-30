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
 * @OA\Post(path="apipv/Categories-products/GetCategoriesProducts", tags={"Categories-products"}, description = "GetCategoriesProducts",
 *
 *
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="json",
 *             @OA\Schema(required={},
 *               @OA\Property(
 *                   property="count",
 *                   description="Número total de registros",
 *                   type="integer",
 *                   example= 469
 *               ),
 *               @OA\Property(
 *                   property="start",
 *                   description="Indice de posición de registros",
 *                   type="integer",
 *                   example= 2
 *               ),
 *               @OA\Property(
 *                   property="Id",
 *                   description="",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="IsActive",
 *                   description="",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="Products",
 *                   description="",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="Description",
 *                   description="",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="Categorie",
 *                   description="Categorie",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="OrderedItem",
 *                   description="OrderedItem",
 *                   type="string",
 *                   example= ""
 *               )
 *             )
 *         )
 *     ),
 *
 * @OA\Response (
 *      response="200",
 *      description="Success",
 *         @OA\MediaType(
 *             mediaType="json",
 *             @OA\Schema(
 *               @OA\Property(
 *                   property="HasError",
 *                   description="Hay error",
 *                   type="boolean",
 *                   example= false
 *               ),
 *               @OA\Property(
 *                   property="AlertType",
 *                   description="Mensaje de la API",
 *                   type="string",
 *                   example= "success"
 *               ),
 *               @OA\Property(
 *                   property="AlertMessage",
 *                   description="Mensaje con el error especifico",
 *                   type="string",
 *                   example= "0"
 *               ),
 *               @OA\Property(
 *                   property="ModelErrors",
 *                   description="",
 *                   type="Array",
 *                   example= {}
 *               ),
 *               @OA\Property(
 *                   property="Data",
 *                   description="",
 *                   type="Array",
 *                   example= {}
 *               ),
 *               @OA\Property(
 *                   property="data",
 *                   description="",
 *                   type="Array",
 *                   example= {}
 *               ),
 *               @OA\Property(
 *                   property="pos",
 *                   description="",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="total_account",
 *                   description="Total de registros",
 *                   type="integer",
 *                   example= 20
 *               ),
 *             )
 *         )
 *     )
 * )
 */

/**
 * Categories-products/GetCategoriesProducts
 *
 * Obtiene las categorías de productos según los filtros proporcionados.
 *
 * @param object $params Objeto JSON decodificado que contiene los parámetros de entrada.
 * @param int $params ->count Número total de registros.
 * @param int $params ->start Índice de inicio para la consulta.
 * @param string $params ->Id ID de la categoría o producto.
 * @param string $params ->IsActive Estado de activación ("A" o "I").
 * @param string $params ->Products Descripción del producto.
 * @param string $params ->Description Descripción de la categoría.
 * @param string $params ->Categorie ID de la categoría.
 * @param string $params ->OrderedItem Orden de los elementos.
 *
 *
 * @return array $response Respuesta con los datos de las categorías y productos.
 *                         - HasError: booleano que indica si hubo un error.
 *                         - AlertType: tipo de alerta (ej. "success", "error").
 *                         - AlertMessage: mensaje de alerta o error.
 *                         - ModelErrors: lista de errores del modelo.
 *                         - total_count: número total de registros encontrados.
 *                         - data: datos de las categorías y productos.
 */

/* recoge y procesa parámetros de entrada para manejar filas en una consulta. */
$MaxRows = $_REQUEST["count"];
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* Establece valores predeterminados para $OrderedItem y $MaxRows si están vacíos. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 10000;
}


/* asigna valores de parámetros a variables para su posterior uso. */
$Categorie = $params->Categorie;
$Id = $params->Id;
$IsActivate = $params->IsActivate;
$Products = $params->Products;
$Description = $params->Description;

$Categorie = $_REQUEST["Categorie"];

/* Recoge datos de entrada mediante el método $_REQUEST en PHP para su procesamiento. */
$Id = $_REQUEST["Id"];
$IsActivate = $_REQUEST["IsActivate"];
$Products = $_REQUEST["Products"];
$Description = $_REQUEST["Description"];


$Product = $_REQUEST["Product"];


/* Código para crear reglas de filtrado en función de una categoría de producto seleccionada. */
$CategoriaProducto = new CategoriaProducto();

$rules = [];

if ($Categorie != "") {
    array_push($rules, array("field" => "categoria.categoria_id", "data" => "$Categorie", "op" => "eq"));
}


/* Agrega reglas a un array según condiciones de variables $Id y $IsActivate. */
if ($Id != "") {
    array_push($rules, array("field" => "categoria_producto.catprod_id", "data" => "$Id", "op" => "eq"));
}

if ($IsActivate == "A") {
    array_push($rules, array("field" => "categoria_producto.estado", "data" => "A", "op" => "eq"));
} else if ($IsActivate == "I") {
    /* Condición que agrega una regla si $IsActivate es igual a "I". */

    array_push($rules, array("field" => "categoria_producto.estado", "data" => "I", "op" => "eq"));
}


/* Agrega una regla al arreglo si el producto no está vacío y Global es "S". */
if ($Product != "") {
    if ($_SESSION["Global"] == "S") {
        array_push($rules, array("field" => "producto.producto_id", "data" => "$Product", "op" => "eq"));
    } else {

    }
}


/* Condición que añade reglas a un filtro si hay productos definidos. */
if ($Products != "") {

    array_push($rules, array("field" => "producto.descripcion", "data" => "$Products", "op" => "eq"));
}

$filtro = array("rules" => $rules, "groupOp" => "AND");

/* Codifica un filtro en JSON, obtiene categorías y las decodifica para su uso. */
$jsonfiltro = json_encode($filtro);

$categorias = $CategoriaProducto->getCategoriaProductosCustom(" categoria_producto.*,categoria.*,producto.* ", "categoria_producto.catprod_id", "asc", $SkeepRows, $MaxRows, $jsonfiltro, true);

$categorias = json_decode($categorias);

$final = [];

foreach ($categorias->data as $key => $value) {


    /* Creación de un array asociativo con información de categorías y productos. */
    $array = [];

    $array["Id"] = $value->{"categoria_producto.catprod_id"};

    $array["Categorie"] = array(
        "Id" => $value->{"categoria.categoria_id"},
        "Name" => $value->{"categoria.descripcion"}
    );

    /* Asigna descripciones de categoría y producto a un arreglo en PHP. */
    $array["Categorie"] = $value->{"categoria.descripcion"};
    $array["Product"] = array(
        "Id" => $value->{"producto.producto_id"},
        "Name" => $value->{"producto.descripcion"}
    );
    $array["Product"] = $value->{"producto.descripcion"};

    /* asigna valores a un array y lo agrega a un array final. */
    $array["IsActivate"] = $value->{"categoria_producto.estado"};
    $array["Order"] = $value->{"categoria_producto.orden"};
    $array["Partner"] = $value->{"categoria_producto.mandante"};

    array_push($final, $array);

}


/* configura una respuesta inicial sin errores, incluyendo tipo y mensaje de alerta. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

//$response["Data"] = array("Objects" => $final, "Count" => $categorias->count[0]->{".count"});
$response["pos"] = $SkeepRows;

/* Se asigna el conteo de categorías y los datos finales a la respuesta. */
$response["total_count"] = $categorias->count[0]->{".count"};
$response["data"] = $final;

