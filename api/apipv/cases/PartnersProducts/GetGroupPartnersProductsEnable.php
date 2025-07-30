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
 * PartnerProducts/GetGroupPartnersProductsEnable
 * 
 * Obtiene los productos de partners habilitados agrupados por criterios específicos
 *
 * @return array {
 *   "HasError": boolean,          // Indica si hubo error en la operación
 *   "AlertType": string,         // Tipo de alerta (success, error, etc)
 *   "AlertMessage": string,      // Mensaje descriptivo
 *   "ModelErrors": array,        // Array con errores de validación
 *   "Data": array<{             // Array con los productos agrupados
 *     "Id": int,                // ID del producto
 *     "Name": string,           // Nombre del producto
 *     "Description": string,    // Descripción del producto
 *     "IsActive": boolean,      // Estado de activación
 *     "PartnerId": int,        // ID del partner asociado
 *     "PartnerName": string,   // Nombre del partner
 *     "CountryId": int,        // ID del país
 *     "CountryName": string    // Nombre del país
 *   }>
 * }
 *
 * @access public
 */


/* obtiene parámetros de solicitud para controlar la paginación de datos. */
$MaxRows = $_REQUEST["count"];
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* Asigna valores predeterminados a variables si están vacías en PHP. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 10000;
}



/* asigna valores de parámetros a variables específicas en PHP. */
$Id = $params->Id;
$IsActivate = $params->IsActivate;
$IsVerified = $params->IsVerified;
$FilterCountry = $params->FilterCountry;
$Products = $params->Products;
$Partner = $params->Partner;

/* asigna valores de parámetros y obtiene un ID de la solicitud. */
$Minimum = $params->Minimum;
$Maximum = $params->Maximum;
$Product = $params->Product;


$Id = $_REQUEST["Id"];

/* valida y asigna valores de solicitudes según condiciones específicas. */
$IsActivate = ($_REQUEST["IsActivate"] == "A" || $_REQUEST["IsActivate"] == "I") ? $_REQUEST["IsActivate"] : '';;
$IsVerified = ($_REQUEST["IsVerified"] == "A" || $_REQUEST["IsVerified"] == "I") ? $_REQUEST["IsVerified"] : '';
$FilterCountry = ($_REQUEST["FilterCountry"] == "A" || $_REQUEST["FilterCountry"] == "I") ? $_REQUEST["FilterCountry"] : '';
$Products = $_REQUEST["Products"];
$Partner = $_REQUEST["Partner"];
$PartnerReference = $_REQUEST["PartnerReference"];

/* recoge valores de un formulario y valida el ProviderId. */
$CountrySelect = $_REQUEST["CountrySelect"];

$Minimum = $_REQUEST["Minimum"];
$Maximum = $_REQUEST["Maximum"];
$Product = $_REQUEST["Product"];
$ProviderId = ($_REQUEST["ProviderId"] > 0 && is_numeric($_REQUEST["ProviderId"]) && $_REQUEST["ProviderId"] != '') ? $_REQUEST["ProviderId"] : '';

/* valida y asigna datos desde la solicitud HTTP a variables específicas. */
$SubProviderId = ($_REQUEST["SubProviderId"] > 0 && is_numeric($_REQUEST["SubProviderId"]) && $_REQUEST["SubProviderId"] != '') ? $_REQUEST["SubProviderId"] : '';
$Name = $_REQUEST["Name"];

$CountrySelect = $_REQUEST["CountrySelect"];

$Desktop = ($_REQUEST["Desktop"] == "A" || $_REQUEST["Desktop"] == "I") ? $_REQUEST["Desktop"] : '';

/* Asigna valores a variables según condiciones específicas de entrada. */
$Mobile = ($_REQUEST["Mobile"] == "A" || $_REQUEST["Mobile"] == "I") ? $_REQUEST["Mobile"] : '';
if ($Desktop == "A") {
    $Desktop = 'S';
} elseif ($Desktop == "I") {
    $Desktop = 'N';
}


/* asigna valores a la variable $Mobile según condiciones específicas. */
if ($Mobile == "A") {
    $Mobile = 'S';
} elseif ($Mobile == "I") {
    $Mobile = 'N';
}


$ProductoMandante = new ProductoMandante();


/* crea reglas de validación basadas en dispositivos Desktop y Mobile. */
$rules = [];

if ($Desktop != "") {
    array_push($rules, array("field" => "producto.desktop", "data" => "$Desktop", "op" => "eq"));
}

if ($Mobile != "") {
    array_push($rules, array("field" => "producto.mobile", "data" => "$Mobile", "op" => "eq"));
}


/* Condiciona la adición de reglas basadas en variables no vacías. */
if ($Id != "") {
    array_push($rules, array("field" => "producto_mandante.prodmandante_id", "data" => "$Id", "op" => "eq"));
}

if ($CountrySelect != "") {
    array_push($rules, array("field" => "producto_mandante.pais_id", "data" => "$CountrySelect", "op" => "eq"));
}


/* valida y asigna estados 'A' o 'I' a reglas específicas. */
if ($IsActivate != "" && $IsActivate != null) {
    $IsActivate = ($IsActivate == 'A') ? 'A' : 'I';

    array_push($rules, array("field" => "producto_mandante.estado", "data" => "$IsActivate", "op" => "eq"));
}

if ($IsVerified != "" && $IsVerified != null) {
    $IsVerified = ($IsVerified == 'A') ? 'A' : 'I';

    array_push($rules, array("field" => "producto_mandante.verifica", "data" => "$IsVerified", "op" => "eq"));
}



/* filtra productos según país y socio, agregando reglas a un array. */
if ($FilterCountry != "" && $FilterCountry != null) {
    $FilterCountry = ($FilterCountry == 'A') ? 'A' : 'I';

    array_push($rules, array("field" => "producto_mandante.filtro_pais", "data" => "$FilterCountry", "op" => "eq"));
}

if ($Partner != "") {

    array_push($rules, array("field" => "producto_mandante.mandante", "data" => "$Partner", "op" => "eq"));
}


/* Agrega reglas de validación para mínimos y máximos si están definidos. */
if ($Minimum != "") {

    array_push($rules, array("field" => "producto_mandante.min", "data" => "$Minimum", "op" => "eq"));
}

if ($Maximum != "") {

    array_push($rules, array("field" => "producto_mandante.max", "data" => "$Maximum", "op" => "eq"));
}



/* Agrega reglas basadas en la sesión y el producto proporcionado. */
if ($Product != "") {
    if ($_SESSION["Global"] == "S") {
        array_push($rules, array("field" => "producto.producto_id", "data" => "$Product", "op" => "eq"));

    } else {
        array_push($rules, array("field" => "producto_mandante.prodmandante_id", "data" => "$Product", "op" => "eq"));

    }

}



/* Agrega reglas de filtro según los valores de proveedor y subproveedor. */
if ($ProviderId != "") {

    array_push($rules, array("field" => "producto.proveedor_id", "data" => "$ProviderId", "op" => "eq"));
}

if ($SubProviderId != "") {

    array_push($rules, array("field" => "producto.subproveedor_id", "data" => "$SubProviderId", "op" => "eq"));
}


/* verifica condiciones para agregar reglas a un array basado en variables. */
if ($Name != "") {

    array_push($rules, array("field" => "producto.descripcion", "data" => "$Name", "op" => "cn"));
}

// Si el usuario esta condicionado por el mandante y no es de Global
if ($_SESSION['Global'] == "N") {
    //array_push($rules, array("field" => "producto_mandante.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
}


/* Agrega reglas de validación y define orden para productos en un array. */
array_push($rules, array("field" => "producto_mandante.habilitacion", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "subproveedor_mandante_pais.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "producto.estado", "data" => "A", "op" => "eq"));

$orden = "producto_mandante.prodmandante_id";
$ordenTipo = "asc";


/* establece un orden de productos basado en una solicitud de usuario. */
if ($_REQUEST["sort[Order]"] != "") {
    $orden = "producto_mandante.orden";
    $ordenTipo = ($_REQUEST["sort[Order]"] == "asc") ? "asc" : "desc";

}

$filtro = array("rules" => $rules, "groupOp" => "AND");

/* convierte un filtro a JSON y obtiene productos personalizados desde una base de datos. */
$jsonfiltro = json_encode($filtro);

$productos = $ProductoMandante->getProductosMandanteCustom(" producto_mandante.*,producto.*,mandante.*,proveedor.* ", $orden, $ordenTipo, $SkeepRows, $MaxRows, $jsonfiltro, true);

$productos = json_decode($productos);

$productosString = '##';


/* Código que procesa productos, acumulando IDs en una cadena separada por comas. */
$final = [];

$children_final = [];
$children_final2 = [];


foreach ($productos->data as $key => $value) {

    $productosString = $productosString . "," . $value->{"producto.producto_id"};

}



/* obtiene parámetros de solicitud para paginar resultados en una aplicación. */
$MaxRows = $_REQUEST["count"];
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* establece valores predeterminados para variables vacías de "OrderedItem" y "MaxRows". */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 10000000;
}



/* Asignación de parámetros recibidos a variables para su uso posterior. */
$Desktop = $params->Desktop;
$ExternalId = $params->ExternalId;
$Id = $params->Id;
$Image = $params->Image;
$IsActivate = $params->IsActivate;
$IsVerified = $params->IsVerified;

/* Código que asigna valores de parámetros y valida el ProviderId recibido. */
$Mobile = $params->Mobile;
$Name = $params->Name;
$Order = $params->Order;
$ProviderId = $params->ProviderId;
$Visible = $params->Visible;

$ProviderId = ($_REQUEST["ProviderId"] > 0 && is_numeric($_REQUEST["ProviderId"]) && $_REQUEST["ProviderId"] != '') ? $_REQUEST["ProviderId"] : '';

/* verifica y asigna valores de entrada a variables específicas. */
$SubProviderId = ($_REQUEST["SubProviderId"] > 0 && is_numeric($_REQUEST["SubProviderId"]) && $_REQUEST["SubProviderId"] != '') ? $_REQUEST["SubProviderId"] : '';
$Product = $_REQUEST["Product"];
$ProductId = $_REQUEST["ProductId"];

$ExternalId = $_REQUEST["ExternalId"];
$Id = $_REQUEST["Id"];

/* recoge datos de una solicitud HTTP para variables específicas. */
$Image = $_REQUEST["Image"];
$IsActivate = $_REQUEST["IsActivate"];
$IsVerified = $_REQUEST["IsVerified"];

$Name = $_REQUEST["Name"];
$Order = $_REQUEST["Order"];

/* Valida entradas de usuario y asigna valores según condiciones específicas. */
$Visible = $_REQUEST["Visible"];

$Desktop = ($_REQUEST["Desktop"] == "A" || $_REQUEST["Desktop"] == "I") ? $_REQUEST["Desktop"] : '';
$Mobile = ($_REQUEST["Mobile"] == "A" || $_REQUEST["Mobile"] == "I") ? $_REQUEST["Mobile"] : '';
if ($Desktop == "A") {
    $Desktop = 'S';
} elseif ($Desktop == "I") {
/* cambia el valor de $Desktop a 'N' si es igual a "I". */

    $Desktop = 'N';
}


/* asigna nuevos valores a la variable $Mobile según su valor inicial. */
if ($Mobile == "A") {
    $Mobile = 'S';
} elseif ($Mobile == "I") {
    $Mobile = 'N';
}


$Producto = new Producto();


/* crea reglas para filtrar productos según desktop y mobile. */
$rules = [];

if ($Desktop != "") {
    array_push($rules, array("field" => "producto.desktop", "data" => "$Desktop", "op" => "eq"));
}

if ($Mobile != "") {
    array_push($rules, array("field" => "producto.mobile", "data" => "$Mobile", "op" => "eq"));
}

/* Agrega condiciones a un array si las variables no están vacías. */
if ($ExternalId != "") {
    array_push($rules, array("field" => "producto.externo_id", "data" => "$ExternalId", "op" => "eq"));
}
if ($Id != "") {
    array_push($rules, array("field" => "producto.producto_id", "data" => "$Id", "op" => "eq"));
}

/* Agrega reglas de validación para imagen y estado de activación de un producto. */
if ($Image != "") {
    array_push($rules, array("field" => "producto.image_url", "data" => "$image", "op" => "eq"));
}
if ($IsActivate != "" && $IsActivate != null) {
    $IsActivate = ($IsActivate == 'A') ? 'A' : 'I';

    array_push($rules, array("field" => "producto.habilitacion", "data" => "$IsActivate", "op" => "eq"));
}

/* valida y agrega reglas basadas en variables de verificación y nombre. */
if ($IsVerified != "" && $IsVerified != null) {
    $IsVerified = ($IsVerified == 'A') ? 'A' : 'I';

    array_push($rules, array("field" => "producto.verifica", "data" => "$IsVerified", "op" => "eq"));
}
if ($Name != "") {
    array_push($rules, array("field" => "producto.descripcion", "data" => "$Name", "op" => "cn"));
}

/* Agrega reglas a un array según condiciones de $Order y $ProviderId. */
if ($Order != "") {
    array_push($rules, array("field" => "producto.orden", "data" => "$Order", "op" => "eq"));
}
if ($ProviderId != "") {
    array_push($rules, array("field" => "producto.proveedor_id", "data" => "$ProviderId", "op" => "eq"));
}

/* Se añaden reglas a un array basado en condiciones de id de subproveedor y producto. */
if ($SubProviderId != "") {

    array_push($rules, array("field" => "producto.subproveedor_id", "data" => "$SubProviderId", "op" => "eq"));
}
if ($Product != "") {
    if ($_SESSION["Global"] == "S") {
        array_push($rules, array("field" => "producto.producto_id", "data" => "$Product", "op" => "eq"));
    } else {

    }
}


/* agrega una regla si $ProductId no está vacío y sesión es 'S'. */
if ($ProductId != "") {
    if ($_SESSION["Global"] == "S") {
        array_push($rules, array("field" => "producto.producto_id", "data" => "$ProductId", "op" => "eq"));
    } else {

    }
}

/* ajusta visibilidad y agrega reglas en un arreglo. */
if ($Visible != "") {
    $Visible = ($Visible == 'A') ? 'S' : 'N';

    array_push($rules, array("field" => "producto.mostrar", "data" => "$Visible", "op" => "eq"));
}

    array_push($rules, array("field" => "subproveedor_mandante_pais.estado", "data" => "A", "op" => "eq"));
    
    /* construye un filtro JSON y obtiene productos según reglas especificadas. */
    array_push($rules, array("field" => "producto.estado", "data" => "A", "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");
$jsonfiltro = json_encode($filtro);


$productos = $Producto->getProductosCustomMandante(" producto.*,proveedor.*,subproveedor.descripcion ", "producto.descripcion", "asc", $SkeepRows, $MaxRows, $jsonfiltro, true, $Partner,$CountrySelect);


/* Decodifica JSON de productos y crea un array con sus identificadores y descripciones. */
$productos = json_decode($productos);

$final = [];

foreach ($productos->data as $key => $value) {

    $array = [];

    $children = [];
    $children["id"] = $value->{"producto.producto_id"};
    $children["value"] = $value->{"producto.descripcion"} . " (" . $value->{"producto.producto_id"} . ")";

    array_push($children_final, $children);

}


if ($PartnerReference != "" && $PartnerReference != "-1") {


/* Crea reglas de filtrado para productos según la disponibilidad en desktop y mobile. */
    $rules = [];

    if ($Desktop != "") {
        array_push($rules, array("field" => "producto.desktop", "data" => "$Desktop", "op" => "eq"));
    }

    if ($Mobile != "") {
        array_push($rules, array("field" => "producto.mobile", "data" => "$Mobile", "op" => "eq"));
    }

    
    /* agrega reglas a un arreglo según condiciones específicas de variables. */
    if ($Id != "") {
        array_push($rules, array("field" => "producto_mandante.prodmandante_id", "data" => "$Id", "op" => "eq"));
    }

    if ($IsActivate != "" && $IsActivate != null) {
        $IsActivate = ($IsActivate == 'A') ? 'A' : 'I';

        array_push($rules, array("field" => "producto_mandante.estado", "data" => "$IsActivate", "op" => "eq"));
    }

    
    /* Se verifican condiciones y se agregan reglas según los valores de entrada. */
    if ($IsVerified != "" && $IsVerified != null) {
        $IsVerified = ($IsVerified == 'A') ? 'A' : 'I';

        array_push($rules, array("field" => "producto_mandante.verifica", "data" => "$IsVerified", "op" => "eq"));
    }


    if ($FilterCountry != "" && $FilterCountry != null) {
        $FilterCountry = ($FilterCountry == 'A') ? 'A' : 'I';

        array_push($rules, array("field" => "producto_mandante.filtro_pais", "data" => "$FilterCountry", "op" => "eq"));
    }


    
    /* Condiciona la adición de reglas basadas en el valor de $PartnerReference. */
    if ($PartnerReference != "" && $PartnerReference != "-1") {

        array_push($rules, array("field" => "producto_mandante.mandante", "data" => "$PartnerReference", "op" => "eq"));
        if ($PartnerReference == '0') {
            array_push($rules, array("field" => "producto_mandante.pais_id", "data" => "173", "op" => "eq"));

        }
    }

        
    /* Agrega reglas para validar mínimo y máximo si los valores no están vacíos. */
    if ($Minimum != "") {

        array_push($rules, array("field" => "producto_mandante.min", "data" => "$Minimum", "op" => "eq"));
    }

    if ($Maximum != "") {

        array_push($rules, array("field" => "producto_mandante.max", "data" => "$Maximum", "op" => "eq"));
    }


    
    /* verifica un producto y agrega reglas según la sesión global. */
    if ($Product != "") {
        if ($_SESSION["Global"] == "S") {
            array_push($rules, array("field" => "producto.producto_id", "data" => "$Product", "op" => "eq"));

        } else {
            array_push($rules, array("field" => "producto_mandante.prodmandante_id", "data" => "$Product", "op" => "eq"));

        }

    }


    
    /* Agrega reglas a un array si los IDs de proveedor y subproveedor no están vacíos. */
    if ($ProviderId != "") {

        array_push($rules, array("field" => "producto.proveedor_id", "data" => "$ProviderId", "op" => "eq"));
    }

    if ($SubProviderId != "") {

        array_push($rules, array("field" => "producto.subproveedor_id", "data" => "$SubProviderId", "op" => "eq"));
    }
    
    /* Agrega reglas de filtros basadas en condiciones específicas del usuario y nombre. */
    if ($Name != "") {

        array_push($rules, array("field" => "producto.descripcion", "data" => "$Name", "op" => "cn"));
    }

// Si el usuario esta condicionado por el mandante y no es de Global
    if ($_SESSION['Global'] == "N") {
        //array_push($rules, array("field" => "producto_mandante.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    }

    
    /* Se agregan reglas para validar campos de productos y establecer orden ascendente. */
    array_push($rules, array("field" => "producto_mandante.habilitacion", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "subproveedor_mandante_pais.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "producto.estado", "data" => "A", "op" => "eq"));

    $orden = "producto_mandante.prodmandante_id";
    $ordenTipo = "asc";

    
    /* define orden de productos y configura filtros para consultas. */
    if ($_REQUEST["sort[Order]"] != "") {
        $orden = "producto_mandante.orden";
        $ordenTipo = ($_REQUEST["sort[Order]"] == "asc") ? "asc" : "desc";

    }


    $filtro = array("rules" => $rules, "groupOp" => "AND");

/* convierte un filtro a JSON y obtiene productos de una base de datos. */
    $jsonfiltro = json_encode($filtro);

    $productos = $ProductoMandante->getProductosMandanteCustom(" producto_mandante.*,producto.*,mandante.*,proveedor.* ", $orden, $ordenTipo, $SkeepRows, $MaxRows, $jsonfiltro, true);

    $productos = json_decode($productos);


    $final = [];


/* crea un array con datos de productos a partir de un objeto. */
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


/* prepara una respuesta estructurada con información sobre errores y datos. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

//$response["Data"] = array("Objects" => $final, "Count" => $productos->count[0]->{".count"});
$response["Data"]["ExcludedProductsList"] = $children_final;

/* procesa una cadena de productos y organiza datos en un arreglo de respuesta. */
$response["Data"]["IncludedProductsList"] = str_replace("##", "", str_replace("##,", "", $productosString));

$response["pos"] = $SkeepRows;
$response["total_count"] = $productos->count[0]->{".count"};
$response["data"] = $final;

