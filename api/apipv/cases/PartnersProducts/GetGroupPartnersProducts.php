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
 * PartnerProducts/GetPartnersProducts
 *
 * Este script obtiene productos asociados a un socio (partner) con base en diversos filtros y parámetros.
 *
 * @param object $params Objeto que contiene los siguientes parámetros:
 * @param int $params->Id Identificador del producto.
 * @param string $params->IsActivate Estado de activación del producto ('A' para activo, 'I' para inactivo).
 * @param string $params->IsVerified Estado de verificación del producto ('A' para verificado, 'I' para no verificado).
 * @param string $params->FilterCountry Filtro por país ('A' para activo, 'I' para inactivo).
 * @param array $params->Products Lista de productos.
 * @param string $params->Partner Identificador del socio.
 * @param float $params->Minimum Valor mínimo del rango de precios.
 * @param float $params->Maximum Valor máximo del rango de precios.
 * @param int $params->Product Identificador del producto específico.
 * @param string $params->Desktop Estado de disponibilidad en escritorio ('A' para activo, 'I' para inactivo).
 * @param string $params->Mobile Estado de disponibilidad en móvil ('A' para activo, 'I' para inactivo).
 * @param int $params->ProviderId Identificador del proveedor.
 * @param int $params->SubProviderId Identificador del subproveedor.
 * @param string $params->Name Nombre o descripción del producto.
 * @param string $params->CountrySelect País seleccionado.
 * @param string $params->PartnerReference Referencia del socio.
 * 
 * 
 *
 * @return array $response Respuesta en formato JSON con los siguientes campos:
 * - HasError: (boolean) Indica si ocurrió un error.
 * - AlertType: (string) Tipo de alerta ('success' o 'error').
 * - AlertMessage: (string) Mensaje de alerta.
 * - ModelErrors: (array) Lista de errores de validación.
 * - Data: (array) Contiene:
 *   - ExcludedProductsList: (array) Lista de productos excluidos.
 *   - IncludedProductsList: (string) Cadena de productos incluidos.
 * - pos: (int) Posición inicial de los datos.
 * - total_count: (int) Total de productos encontrados.
 * - data: (array) Datos finales procesados.
 */


/* obtiene valores de entrada y establece variables para paginación de datos. */
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
    $MaxRows = 1000;
}

/* Se definen variables para gestionar datos de una operación específica. */
$MaxRows = 1000000000;


$Id = $params->Id;
$IsActivate = $params->IsActivate;
$IsVerified = $params->IsVerified;

/* Variables extraen parámetros para filtrar productos según país, socio y rangos de precios. */
$FilterCountry = $params->FilterCountry;
$Products = $params->Products;
$Partner = $params->Partner;
$Minimum = $params->Minimum;
$Maximum = $params->Maximum;
$Product = $params->Product;


/* Valida y asigna valores de parámetros de solicitud a variables en PHP. */
$Id = $_REQUEST["Id"];
$IsActivate = ($_REQUEST["IsActivate"] == "A" || $_REQUEST["IsActivate"] == "I") ? $_REQUEST["IsActivate"] : '';;
$IsVerified = ($_REQUEST["IsVerified"] == "A" || $_REQUEST["IsVerified"] == "I") ? $_REQUEST["IsVerified"] : '';
$FilterCountry = ($_REQUEST["FilterCountry"] == "A" || $_REQUEST["FilterCountry"] == "I") ? $_REQUEST["FilterCountry"] : '';
$Products = $_REQUEST["Products"];
$Partner = $_REQUEST["Partner"];

/* PHP recoge parámetros de una solicitud HTTP para su procesamiento. */
$PartnerReference = $_REQUEST["PartnerReference"];
$CountrySelect = $_REQUEST["CountrySelect"];

$Minimum = $_REQUEST["Minimum"];
$Maximum = $_REQUEST["Maximum"];
$Product = $_REQUEST["Product"];

/* valida y asigna valores de entrada de una solicitud HTTP. */
$ProviderId = ($_REQUEST["ProviderId"] > 0 && is_numeric($_REQUEST["ProviderId"]) && $_REQUEST["ProviderId"] != '') ? $_REQUEST["ProviderId"] : '';
$SubProviderId = ($_REQUEST["SubProviderId"] > 0 && is_numeric($_REQUEST["SubProviderId"]) && $_REQUEST["SubProviderId"] != '') ? $_REQUEST["SubProviderId"] : '';
$Name = $_REQUEST["Name"];

$CountrySelect = $_REQUEST["CountrySelect"];

$Desktop = ($_REQUEST["Desktop"] == "A" || $_REQUEST["Desktop"] == "I") ? $_REQUEST["Desktop"] : '';

/* asigna valores a variables según condiciones de entrada específicas. */
$Mobile = ($_REQUEST["Mobile"] == "A" || $_REQUEST["Mobile"] == "I") ? $_REQUEST["Mobile"] : '';
if ($Desktop == "A") {
    $Desktop = 'S';
} elseif ($Desktop == "I") {
    $Desktop = 'N';
}


/* cambia el valor de $Mobile según su valor inicial y crea un objeto. */
if ($Mobile == "A") {
    $Mobile = 'S';
} elseif ($Mobile == "I") {
    $Mobile = 'N';
}


$ProductoMandante = new ProductoMandante();


/* crea reglas para validar campos según variables Desktop y Mobile. */
$rules = [];

if ($Desktop != "") {
    array_push($rules, array("field" => "producto.desktop", "data" => "$Desktop", "op" => "eq"));
}

if ($Mobile != "") {
    array_push($rules, array("field" => "producto.mobile", "data" => "$Mobile", "op" => "eq"));
}


/* Agrega condiciones a un array si los identificadores no están vacíos. */
if ($Id != "") {
    array_push($rules, array("field" => "producto_mandante.prodmandante_id", "data" => "$Id", "op" => "eq"));
}

if ($CountrySelect != "") {
    array_push($rules, array("field" => "producto_mandante.pais_id", "data" => "$CountrySelect", "op" => "eq"));
}


/* verifica y ajusta estados antes de agregar reglas a un array. */
if ($IsActivate != "" && $IsActivate != null) {
    $IsActivate = ($IsActivate == 'A') ? 'A' : 'I';

    array_push($rules, array("field" => "producto_mandante.estado", "data" => "$IsActivate", "op" => "eq"));
}

if ($IsVerified != "" && $IsVerified != null) {
    $IsVerified = ($IsVerified == 'A') ? 'A' : 'I';

    array_push($rules, array("field" => "producto_mandante.verifica", "data" => "$IsVerified", "op" => "eq"));
}


/* agrega reglas de filtrado basadas en condiciones de país y socio. */
if ($FilterCountry != "" && $FilterCountry != null) {
    $FilterCountry = ($FilterCountry == 'A') ? 'A' : 'I';

    array_push($rules, array("field" => "producto_mandante.filtro_pais", "data" => "$FilterCountry", "op" => "eq"));
}

if ($Partner != "") {

    array_push($rules, array("field" => "producto_mandante.mandante", "data" => "$Partner", "op" => "eq"));
}


/* Agrega reglas de validación para valores mínimo y máximo si están definidos. */
if ($Minimum != "") {

    array_push($rules, array("field" => "producto_mandante.min", "data" => "$Minimum", "op" => "eq"));
}

if ($Maximum != "") {

    array_push($rules, array("field" => "producto_mandante.max", "data" => "$Maximum", "op" => "eq"));
}


/* Condicional que agrega reglas basadas en el valor del producto y sesión. */
if ($Product != "") {
    if ($_SESSION["Global"] == "S") {
        array_push($rules, array("field" => "producto.producto_id", "data" => "$Product", "op" => "eq"));

    } else {
        array_push($rules, array("field" => "producto_mandante.prodmandante_id", "data" => "$Product", "op" => "eq"));

    }

}


/* Agrega reglas para filtros basados en proveedor y subproveedor si están definidos. */
if ($ProviderId != "") {

    array_push($rules, array("field" => "producto.proveedor_id", "data" => "$ProviderId", "op" => "eq"));
}

if ($SubProviderId != "") {

    array_push($rules, array("field" => "producto.subproveedor_id", "data" => "$SubProviderId", "op" => "eq"));
}


/* Condiciona reglas según el valor de $Name y la sesión del usuario. */
if ($Name != "") {

    array_push($rules, array("field" => "producto.descripcion", "data" => "$Name", "op" => "cn"));
}

// Si el usuario esta condicionado por el mandante y no es de Global
if ($_SESSION['Global'] == "N") {
    //array_push($rules, array("field" => "producto_mandante.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
}

/* Añade reglas de filtrado y ordena productos por ID en orden ascendente. */
array_push($rules, array("field" => "producto_mandante.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "subproveedor_mandante_pais.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "producto.estado", "data" => "A", "op" => "eq"));

$orden = "producto_mandante.prodmandante_id";
$ordenTipo = "asc";


/* Ordena productos basado en solicitud de ordenamiento y define filtros para la búsqueda. */
if ($_REQUEST["sort[Order]"] != "") {
    $orden = "producto_mandante.orden";
    $ordenTipo = ($_REQUEST["sort[Order]"] == "asc") ? "asc" : "desc";

}

$filtro = array("rules" => $rules, "groupOp" => "AND");

/* convierte un filtro a JSON y obtiene productos de la base de datos. */
$jsonfiltro = json_encode($filtro);

$productos = $ProductoMandante->getProductosMandanteCustom(" producto_mandante.*,producto.*,mandante.*,proveedor.* ", $orden, $ordenTipo, $SkeepRows, $MaxRows, $jsonfiltro, true);

$productos = json_decode($productos);

$productosString = '##';


/* inicializa arreglos y crea una cadena de IDs de productos. */
$final = [];

$children_final = [];
$children_final2 = [];


foreach ($productos->data as $key => $value) {

    $productosString = $productosString . "," . $value->{"producto.producto_id"};

}


/* Código para manejar parámetros de paginación en una solicitud HTTP. */
$MaxRows = $_REQUEST["count"];
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* Asigna valores predeterminados a `$OrderedItem` y `$MaxRows` si están vacíos. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 10000000;
}


/* asigna valores de parámetros a variables en un entorno de programación. */
$Desktop = $params->Desktop;
$ExternalId = $params->ExternalId;
$Id = $params->Id;
$Image = $params->Image;
$IsActivate = $params->IsActivate;
$IsVerified = $params->IsVerified;

/* asigna valores de parámetros y valida el ID del proveedor. */
$Mobile = $params->Mobile;
$Name = $params->Name;
$Order = $params->Order;
$ProviderId = $params->ProviderId;
$Visible = $params->Visible;

$ProviderId = ($_REQUEST["ProviderId"] > 0 && is_numeric($_REQUEST["ProviderId"]) && $_REQUEST["ProviderId"] != '') ? $_REQUEST["ProviderId"] : '';

/* asigna valores de entrada validados a variables específicas en PHP. */
$SubProviderId = ($_REQUEST["SubProviderId"] > 0 && is_numeric($_REQUEST["SubProviderId"]) && $_REQUEST["SubProviderId"] != '') ? $_REQUEST["SubProviderId"] : '';
$Product = $_REQUEST["Product"];
$ProductId = $_REQUEST["ProductId"];

$ExternalId = $_REQUEST["ExternalId"];
$Id = $_REQUEST["Id"];

/* recibe parámetros de una solicitud HTTP y los asigna a variables. */
$Image = $_REQUEST["Image"];
$IsActivate = $_REQUEST["IsActivate"];
$IsVerified = $_REQUEST["IsVerified"];

$Name = $_REQUEST["Name"];
$Order = $_REQUEST["Order"];

/* asigna valores a variables según parámetros de entrada y condiciones específicas. */
$Visible = $_REQUEST["Visible"];

$Desktop = ($_REQUEST["Desktop"] == "A" || $_REQUEST["Desktop"] == "I") ? $_REQUEST["Desktop"] : '';
$Mobile = ($_REQUEST["Mobile"] == "A" || $_REQUEST["Mobile"] == "I") ? $_REQUEST["Mobile"] : '';
if ($Desktop == "A") {
    $Desktop = 'S';
} elseif ($Desktop == "I") {
    /* Cambia el valor de `$Desktop` a 'N' si es igual a "I". */

    $Desktop = 'N';
}


/* asigna valores a $Mobile y crea una nueva instancia de Producto. */
if ($Mobile == "A") {
    $Mobile = 'S';
} elseif ($Mobile == "I") {
    $Mobile = 'N';
}


$Producto = new Producto();


/* agrega reglas de filtrado según valores de escritorio y móvil. */
$rules = [];

if ($Desktop != "") {
    array_push($rules, array("field" => "producto.desktop", "data" => "$Desktop", "op" => "eq"));
}

if ($Mobile != "") {
    array_push($rules, array("field" => "producto.mobile", "data" => "$Mobile", "op" => "eq"));
}

/* Agrega reglas a un arreglo basado en la presencia de identificadores externos o internos. */
if ($ExternalId != "") {
    array_push($rules, array("field" => "producto.externo_id", "data" => "$ExternalId", "op" => "eq"));
}
if ($Id != "") {
    array_push($rules, array("field" => "producto.producto_id", "data" => "$Id", "op" => "eq"));
}

/* Agrega reglas a un array basadas en condiciones de imagen y estado de activación. */
if ($Image != "") {
    array_push($rules, array("field" => "producto.image_url", "data" => "$image", "op" => "eq"));
}
if ($IsActivate != "" && $IsActivate != null) {
    $IsActivate = ($IsActivate == 'A') ? 'A' : 'I';

    array_push($rules, array("field" => "producto.estado", "data" => "$IsActivate", "op" => "eq"));
}

/* valida condiciones y agrega reglas a un array basado en variables. */
if ($IsVerified != "" && $IsVerified != null) {
    $IsVerified = ($IsVerified == 'A') ? 'A' : 'I';

    array_push($rules, array("field" => "producto.verifica", "data" => "$IsVerified", "op" => "eq"));
}
if ($Name != "") {
    array_push($rules, array("field" => "producto.descripcion", "data" => "$Name", "op" => "cn"));
}

/* Agrega reglas para filtrar productos según orden y proveedor si no están vacíos. */
if ($Order != "") {
    array_push($rules, array("field" => "producto.orden", "data" => "$Order", "op" => "eq"));
}
if ($ProviderId != "") {
    array_push($rules, array("field" => "producto.proveedor_id", "data" => "$ProviderId", "op" => "eq"));
}

/* Agrega reglas de filtrado si el subproveedor y producto están definidos. */
if ($SubProviderId != "") {

    array_push($rules, array("field" => "producto.subproveedor_id", "data" => "$SubProviderId", "op" => "eq"));
}
if ($Product != "") {
    if ($_SESSION["Global"] == "S") {
        array_push($rules, array("field" => "producto.producto_id", "data" => "$Product", "op" => "eq"));
    } else {

    }
}


/* verifica un ID de producto y añade reglas según una condición global. */
if ($ProductId != "") {
    if ($_SESSION["Global"] == "S") {
        array_push($rules, array("field" => "producto.producto_id", "data" => "$ProductId", "op" => "eq"));
    } else {

    }
}

/* modifica reglas de visibilidad y país basándose en condiciones específicas. */
if ($Visible != "") {
    $Visible = ($Visible == 'A') ? 'S' : 'N';

    array_push($rules, array("field" => "producto.mostrar", "data" => "$Visible", "op" => "eq"));
}

if ($CountrySelect != "") {
    array_push($rules, array("field" => "producto_mandante.pais_id", "data" => "$CountrySelect", "op" => "eq"));
}

/* Se crean reglas de filtrado basadas en condiciones de igualdad para varios campos. */
array_push($rules, array("field" => "subproveedor_mandante.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "producto_mandante.habilitacion", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "subproveedor_mandante_pais.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "producto.estado", "data" => "A", "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");

/* Codifica filtros en JSON y obtiene productos filtrados desde una base de datos. */
$jsonfiltro = json_encode($filtro);


$productos = $Producto->getProductosCustomMandante(" producto.*,proveedor.*,subproveedor.descripcion ", "producto.descripcion", "asc", $SkeepRows, $MaxRows, $jsonfiltro, true, $Partner, $CountrySelect);

$productos = json_decode($productos);


/* Se crea un arreglo con productos, almacenando id y descripción de cada uno. */
$final = [];

foreach ($productos->data as $key => $value) {

    $array = [];

    $children = [];
    $children["id"] = $value->{"producto.producto_id"};
    $children["value"] = $value->{"producto.descripcion"} . " (" . $value->{"producto.producto_id"} . ")";

    array_push($children_final, $children);

}

if ($PartnerReference != "" && $PartnerReference != "-1") {


    /* Se construyen reglas basadas en valores de Desktop y Mobile, agregándolas a un array. */
    $rules = [];

    if ($Desktop != "") {
        array_push($rules, array("field" => "producto.desktop", "data" => "$Desktop", "op" => "eq"));
    }

    if ($Mobile != "") {
        array_push($rules, array("field" => "producto.mobile", "data" => "$Mobile", "op" => "eq"));
    }


    /* Agrega reglas basadas en condiciones de un ID y estado de activación. */
    if ($Id != "") {
        array_push($rules, array("field" => "producto_mandante.prodmandante_id", "data" => "$Id", "op" => "eq"));
    }

    if ($IsActivate != "" && $IsActivate != null) {
        $IsActivate = ($IsActivate == 'A') ? 'A' : 'I';

        array_push($rules, array("field" => "producto_mandante.estado", "data" => "$IsActivate", "op" => "eq"));
    }


    /* valida y ajusta reglas para verificación y filtrado de país. */
    if ($IsVerified != "" && $IsVerified != null) {
        $IsVerified = ($IsVerified == 'A') ? 'A' : 'I';

        array_push($rules, array("field" => "producto_mandante.verifica", "data" => "$IsVerified", "op" => "eq"));
    }


    if ($FilterCountry != "" && $FilterCountry != null) {
        $FilterCountry = ($FilterCountry == 'A') ? 'A' : 'I';

        array_push($rules, array("field" => "producto_mandante.filtro_pais", "data" => "$FilterCountry", "op" => "eq"));
    }


    /* Se agregan reglas a un array según el valor de $PartnerReference. */
    if ($PartnerReference != "" && $PartnerReference != "-1") {

        array_push($rules, array("field" => "producto_mandante.mandante", "data" => "$PartnerReference", "op" => "eq"));
        if ($PartnerReference == '0') {
            array_push($rules, array("field" => "producto_mandante.pais_id", "data" => "173", "op" => "eq"));

        }
    }


    /* Agrega reglas a un arreglo si los valores mínimo y máximo son diferentes a vacío. */
    if ($Minimum != "") {

        array_push($rules, array("field" => "producto_mandante.min", "data" => "$Minimum", "op" => "eq"));
    }

    if ($Maximum != "") {

        array_push($rules, array("field" => "producto_mandante.max", "data" => "$Maximum", "op" => "eq"));
    }


    /* Condicional que agrega reglas según el valor y la sesión del producto. */
    if ($Product != "") {
        if ($_SESSION["Global"] == "S") {
            array_push($rules, array("field" => "producto.producto_id", "data" => "$Product", "op" => "eq"));

        } else {
            array_push($rules, array("field" => "producto_mandante.prodmandante_id", "data" => "$Product", "op" => "eq"));

        }

    }


    /* Agrega reglas para filtrar productos por proveedor y subproveedor si están definidos. */
    if ($ProviderId != "") {

        array_push($rules, array("field" => "producto.proveedor_id", "data" => "$ProviderId", "op" => "eq"));
    }

    if ($SubProviderId != "") {

        array_push($rules, array("field" => "producto.subproveedor_id", "data" => "$SubProviderId", "op" => "eq"));
    }

    /* crea reglas basadas en la entrada del usuario y condiciones de sesión. */
    if ($Name != "") {

        array_push($rules, array("field" => "producto.descripcion", "data" => "$Name", "op" => "cn"));
    }

// Si el usuario esta condicionado por el mandante y no es de Global
    if ($_SESSION['Global'] == "N") {
        //array_push($rules, array("field" => "producto_mandante.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    }


    /* Agrega reglas para filtrar datos y define el orden de los resultados. */
    array_push($rules, array("field" => "producto_mandante.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "subproveedor_mandante_pais.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "producto.estado", "data" => "A", "op" => "eq"));

    $orden = "producto_mandante.prodmandante_id";
    $ordenTipo = "asc";


    /* gestiona la ordenación de productos según parámetros de solicitud. */
    if ($_REQUEST["sort[Order]"] != "") {
        $orden = "producto_mandante.orden";
        $ordenTipo = ($_REQUEST["sort[Order]"] == "asc") ? "asc" : "desc";

    }

    $filtro = array("rules" => $rules, "groupOp" => "AND");

    /* Convierte filtro a JSON, obtiene productos y los decodifica para procesarlos. */
    $jsonfiltro = json_encode($filtro);

    $productos = $ProductoMandante->getProductosMandanteCustom(" producto_mandante.*,producto.*,mandante.*,proveedor.* ", $orden, $ordenTipo, $SkeepRows, $MaxRows, $jsonfiltro, true);

    $productos = json_decode($productos);


    $final = [];


    /* convierte datos de productos en un formato estructurado para uso posterior. */
    $children_final = [];
    $children_final2 = [];


    foreach ($productos->data as $key => $value) {

        $array = [];

        $children = [];
        $children["id"] = $value->{"producto.producto_id"};
        $children["value"] = $value->{"producto.descripcion"} . " (" . $value->{"producto.producto_id"} . ")";

        array_push($children_final, $children);

    }


}

/* configura una respuesta exitosa y almacena una lista de productos excluidos. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

//$response["Data"] = array("Objects" => $final, "Count" => $productos->count[0]->{".count"});
/* procesa una lista de productos y asigna datos a una respuesta JSON. */
$response["Data"]["ExcludedProductsList"] = $children_final;
$response["Data"]["IncludedProductsList"] = str_replace("##", "", str_replace("##,", "", $productosString));
$response["pos"] = $SkeepRows;
$response["total_count"] = $productos->count[0]->{".count"};
$response["data"] = $final;

