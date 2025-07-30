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
use Backend\sql\SqlQuery;
use Backend\websocket\WebsocketUsuario;

/**
 * Products/GetProducts
 *
 * Obtiene productos filtrados según los parámetros proporcionados
 *
 * Este método consulta los productos basándose en varios filtros que se reciben en la solicitud. Permite filtrar por ID de producto, proveedor, visibilidad, entre otros. También maneja la paginación y la respuesta estructurada con los productos encontrados.
 *
 * @param object $params : Objeto que contiene los parámetros de entrada para la consulta de productos.
 *
 * El objeto $params contiene los siguientes atributos:
 *  - *OrderedItem* (string): Indica el tipo de ordenamiento.
 *  - *SkeepRows* (int): Número de filas a omitir (para la paginación).
 *  - *MaxRows* (int): Número máximo de filas a retornar.
 *  - *Desktop* (string): Visibilidad en dispositivos de escritorio ('S' para visible, 'N' para no visible).
 *  - *ExternalId* (string): ID externo del producto.
 *  - *Id* (string): ID del producto.
 *  - *Image* (string): URL de la imagen del producto.
 *  - *IsActivate* (string): Estado del producto ('A' para activo, 'I' para inactivo).
 *  - *IsVerified* (string): Estado de verificación del producto ('A' para verificado, 'I' para no verificado).
 *  - *Mobile* (string): Visibilidad en dispositivos móviles ('A' para visible, 'N' para no visible).
 *  - *Name* (string): Descripción o nombre del producto.
 *  - *Order* (int): Orden de presentación del producto.
 *  - *ProviderId* (string): ID del proveedor del producto.
 *  - *Visible* (string): Visibilidad del producto ('A' para visible, 'I' para no visible).
 *  - *Product* (string): ID del producto a buscar.
 *  - *ProductId* (string): ID del producto a buscar.
 *  - *Category* (string): Categoría del producto.
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *code* (int): Código de error desde el proveedor.
 *  - *result* (string): Contiene el mensaje de error.
 *  - *data* (array): Contiene el resultado de la consulta, que incluye los productos encontrados.
 *  - *pos* (int): Número de filas omitidas (paginación).
 *  - *total_count* (int): Número total de productos que coinciden con los filtros.
 *
 * Objeto en caso de error:
 *
 * "code" => [Código de error],
 * "result" => "[Mensaje de error]",
 * "data" => array(),
 *
 * @throws Exception Si ocurre un error al procesar los filtros o al obtener los productos.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* obtiene parámetros de solicitud, estableciendo límites y filas a omitir. */
$MaxRows = $_REQUEST["count"];
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* asigna valores predeterminados a variables si están vacías. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 10000;
}


/* asigna valores de parámetros a variables en un script PHP. */
$Desktop = $params->Desktop;
$ExternalId = $params->ExternalId;
$Id = $params->Id;
$Image = $params->Image;
$IsActivate = $params->IsActivate;
$IsVerified = $params->IsVerified;

/* asigna valores de parámetros y valida el ProviderId del request. */
$Mobile = $params->Mobile;
$Name = $params->Name;


$Order = $params->Order;
$ProviderId = $params->ProviderId;
$Visible = $params->Visible;

$ProviderId = ($_REQUEST["ProviderId"] > 0 && is_numeric($_REQUEST["ProviderId"]) && $_REQUEST["ProviderId"] != '') ? $_REQUEST["ProviderId"] : '';

/* Valida y asigna valores de entrada, asegurando que SubProviderId sea un número positivo. */
$SubProviderId = ($_REQUEST["SubProviderId"] > 0 && is_numeric($_REQUEST["SubProviderId"]) && $_REQUEST["SubProviderId"] != '') ? $_REQUEST["SubProviderId"] : '';
$Product = $_REQUEST["Product"];
$ProductId = $_REQUEST["ProductId"];

$ExternalId = $_REQUEST["ExternalId"];
$Id = $_REQUEST["Id"];

/* captura datos enviados a través de una solicitud HTTP. */
$Image = $_REQUEST["Image"];
$IsActivate = $_REQUEST["IsActivate"];
$IsVerified = $_REQUEST["IsVerified"];

$Name = $_REQUEST["Name"];
/* depura caracteres especiales */
$sql= 'select 1';
$SqlQuery = new SqlQuery($sql);
$Name = $SqlQuery->DepurarCaracteres($Name);
$Order = $_REQUEST["Order"];

/* procesa parámetros de solicitud y ajusta variables según condiciones específicas. */
$Visible = $_REQUEST["Visible"];
$Category = $_REQUEST['Category'];

$Desktop = ($_REQUEST["Desktop"] == "A" || $_REQUEST["Desktop"] == "I") ? $_REQUEST["Desktop"] : '';
$Mobile = ($_REQUEST["Mobile"] == "A" || $_REQUEST["Mobile"] == "I") ? $_REQUEST["Mobile"] : '';
if ($Desktop == "A") {
    $Desktop = 'S';
} elseif ($Desktop == "I") {
    /* cambia el valor de $Desktop a 'N' si es igual a "I". */

    $Desktop = 'N';
}


/* cambia el valor de $Mobile según su valor inicial y crea un objeto Producto. */
if ($Mobile == "A") {
    $Mobile = 'S';
} elseif ($Mobile == "I") {
    $Mobile = 'N';
}


$Producto = new Producto();


/* Genera un arreglo de reglas para validar campos de producto en función de dispositivos. */
$rules = [];

if ($Desktop != "") {
    array_push($rules, array("field" => "producto.desktop", "data" => "$Desktop", "op" => "eq"));
}

if ($Mobile != "") {
    array_push($rules, array("field" => "producto.mobile", "data" => "$Mobile", "op" => "eq"));
}

/* Agrega condiciones a un array de reglas basadas en IDs externos y de productos. */
if ($ExternalId != "") {
    array_push($rules, array("field" => "producto.externo_id", "data" => "$ExternalId", "op" => "eq"));
}
if ($Id != "") {
    array_push($rules, array("field" => "producto.producto_id", "data" => "$Id", "op" => "eq"));
}

/* agrega reglas a un array basadas en condiciones de imagen y activación. */
if ($Image != "") {
    array_push($rules, array("field" => "producto.image_url", "data" => "$image", "op" => "eq"));
}
if ($IsActivate != "" && $IsActivate != null) {
    $IsActivate = ($IsActivate == 'A') ? 'A' : 'I';

    array_push($rules, array("field" => "producto.estado", "data" => "$IsActivate", "op" => "eq"));
}

/* Valida y configura reglas de verificación y descripción de un producto en un arreglo. */
if ($IsVerified != "" && $IsVerified != null) {
    $IsVerified = ($IsVerified == 'A') ? 'A' : 'I';

    array_push($rules, array("field" => "producto.verifica", "data" => "$IsVerified", "op" => "eq"));
}
if ($Name != "") {
    array_push($rules, array("field" => "producto.descripcion", "data" => "$Name", "op" => "cn"));
}

/* Agrega reglas de filtrado según el valor de $Order y $ProviderId. */
if ($Order != "") {
    array_push($rules, array("field" => "producto.orden", "data" => "$Order", "op" => "eq"));
}
if ($ProviderId != "") {
    array_push($rules, array("field" => "producto.proveedor_id", "data" => "$ProviderId", "op" => "eq"));
}

/* Genera reglas basadas en condiciones de subproveedor y producto. */
if ($SubProviderId != "") {
    array_push($rules, array("field" => "producto.subproveedor_id", "data" => "$SubProviderId", "op" => "eq"));
}
if ($Product != "") {
    if ($_SESSION["Global"] == "S") {
        array_push($rules, array("field" => "producto.producto_id", "data" => "$Product", "op" => "eq"));
    } else {

    }
}


/* verifica condiciones y agrega una regla si se cumplen ciertas condiciones. */
if ($ProductId != "") {
    if ($_SESSION["Global"] == "S") {
        array_push($rules, array("field" => "producto.producto_id", "data" => "$ProductId", "op" => "eq"));
    } else {

    }
}

/* Verificación de visibilidad */
if ($Visible != "") {
    $Visible = ($Visible == 'A') ? 'S' : 'N';

    array_push($rules, array("field" => "producto.mostrar", "data" => "$Visible", "op" => "eq"));
}

if (!empty($Category)) array_push($rules, ['field' => 'categoria_mandante.catmandante_id', 'data' => $Category, 'op' => 'eq']);


/* Se prepara un filtro y se obtienen productos personalizados desde la base de datos. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$jsonfiltro = json_encode($filtro);


$productos = $Producto->getProductosCustom(" producto.*,proveedor.*,subproveedor.descripcion,producto_detalle.p_value,categoria_mandante.catmandante_id,categoria_mandante.descripcion ", "producto.producto_id", "asc", $SkeepRows, $MaxRows, $jsonfiltro, true);

$productos = json_decode($productos);


/* Se inicializa un array vacío llamado "final". */
$final = [];

foreach ($productos->data as $key => $value) {


    /* crea un arreglo con datos de producto y proveedor. */
    $array = [];

    $array["Id"] = $value->{"producto.producto_id"};
    $array["Provider"] = array(
        "Id" => $value->{"producto.proveedor_id"},
        "Name" => $value->{"proveedor.descripcion"}
    );


    /* Asigna valores descriptivos de un producto a un array en PHP. */
    $array["ProviderId"] = $value->{"proveedor.descripcion"};
    $array["SubProviderId"] = $value->{"subproveedor.descripcion"};

    $array["Name"] = $value->{"producto.descripcion"};
    $array["ImageURL"] = '';
    $array["Image"] = $value->{"producto.image_url"};

    /* asigna valores de un objeto a un array asociativo. */
    $array["Image2"] = $value->{"producto.image_url2"};

    $array["Order"] = $value->{"producto.orden"};

    $array["IsActivate"] = $value->{"producto.estado"};
    $array["IsVerified"] = $value->{"producto.verifica"};

    /* asigna valores de un objeto a un array, utilizando condiciones para mostrar resultados. */
    $array["ExternalId"] = $value->{"producto.externo_id"};
    $array["ExternalId2"] = $value->{"producto_detalle.p_value"}; // esta linea devuelve el id externo 2
    $array["Visible"] = ($value->{"producto.mostrar"} == "S") ? "A" : "I";
    $array["Mobile"] = ($value->{"producto.mobile"} == "S") ? "A" : "I";
    $array["Desktop"] = ($value->{"producto.desktop"} == "S") ? "A" : "I";
    $array['TheoreticalRTP'] = $value->{'producto.rtp_teorico'};

    /* Asigna valores a un array basado en condiciones de categorías y dispositivos. */
    $array['Category'] = $value->{'categoria_mandante.catmandante_id'} ?: 0;
    $array['CategoryName'] = $value->{'categoria_mandante.descripcion'} ?: '';

    if ($array["Mobile"] == "A") {
        if ($array["Desktop"] == "A") {
            $array["TypeDevice"] = 3;
        } else {
            $array["TypeDevice"] = 1;

        }
    } elseif ($array["Desktop"] == "A") {
        /* Asignación del tipo de dispositivo basado en la condición del valor "Desktop". */

        $array["TypeDevice"] = 2;

    }


    /* Agrega el contenido de `$array` al final de `$final`. */
    array_push($final, $array);

}


/* crea una respuesta estructurada sin errores y contiene información adicional. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

//$response["Data"] = array("Objects" => $final, "Count" => $productos->count[0]->{".count"});

$response["pos"] = $SkeepRows;

/* asigna la cantidad total y datos de productos a una respuesta. */
$response["total_count"] = $productos->count[0]->{".count"};
$response["data"] = $final;

