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
 * PartnerProducts/GetPartnersProducts
 *
 * Obtiene los productos asociados a un partner específico con sus configuraciones y estados
 *
 * @param string $Id Identificador del producto
 * @param string $IsActivate Estado de activación (A: Activo, I: Inactivo)
 * @param string $IsVerified Estado de verificación (A: Verificado, I: No verificado)
 * @param string $FilterCountry Filtro por país (A: Activo, I: Inactivo)
 * @param string $Products Lista de productos
 * @param string $Partner Identificador del partner
 * @param string $Minimum Valor mínimo
 * @param string $Maximum Valor máximo
 *
 * @return array {
 *   "HasError": boolean,
 *   "AlertType": string,
 *   "AlertMessage": string,
 *   "url": string,
 *   "success": string,
 *   "data": array {
 *     "id": string,
 *     "descripcion": string,
 *     "estado": string,
 *     "verifica": string,
 *     "partner_id": string,
 *     "producto_id": string
 *   }
 * }
 *
 * @access public
 */


/* obtiene parámetros de solicitud para paginación y orden de elementos. */
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



/* Asignación de valores de un objeto $params a variables específicas en PHP. */
$Id = $params->Id;
$IsActivate = $params->IsActivate;
$IsVerified = $params->IsVerified;
$FilterCountry = $params->FilterCountry;
$Products = $params->Products;
$Partner = $params->Partner;

/* asigna valores de parámetros a variables y obtiene un identificador de la solicitud. */
$Minimum = $params->Minimum;
$Maximum = $params->Maximum;
$Product = $params->Product;



$Id = $_REQUEST["Id"];

/* Se asignan valores a variables según condiciones específicas de entrada. */
$IsActivate = ($_REQUEST["IsActivate"] == "A" || $_REQUEST["IsActivate"] == "I") ? $_REQUEST["IsActivate"] : '';;
$IsVerified = ($_REQUEST["IsVerified"] == "A" || $_REQUEST["IsVerified"] == "I") ? $_REQUEST["IsVerified"] : '';
$FilterCountry = ($_REQUEST["FilterCountry"] == "A" || $_REQUEST["FilterCountry"] == "I") ? $_REQUEST["FilterCountry"] : '';
$Products = $_REQUEST["Products"];
$Partner = $_REQUEST["Partner"];
$Minimum = $_REQUEST["Minimum"];

/* recibe y valida datos provenientes de una solicitud HTTP. */
$Maximum = $_REQUEST["Maximum"];
$Product = $_REQUEST["Product"];
$ProviderId = ($_REQUEST["ProviderId"] > 0 && is_numeric($_REQUEST["ProviderId"]) && $_REQUEST["ProviderId"] != '') ? $_REQUEST["ProviderId"] : '';
$Name = $_REQUEST["Name"];
$CountrySelect = $_REQUEST["CountrySelect"];
$providerType = $_REQUEST["providerType"];


/* obtiene y valida parámetros de una solicitud HTTP. */
$SubProviderId= $_REQUEST["SubProviderId"];
$FeaturedOrder = $_REQUEST["FeaturedOrder"];
$ProcessingTime = $_REQUEST["ProcessingTime"];
$TypeDevice = $_REQUEST["TypeDevice"];

$Desktop = ($_REQUEST["Desktop"] == "A" || $_REQUEST["Desktop"] == "I") ? $_REQUEST["Desktop"] : '';

/* asigna valores a variables según condiciones de entrada específicas. */
$Mobile = ($_REQUEST["Mobile"] == "A" || $_REQUEST["Mobile"] == "I") ? $_REQUEST["Mobile"] : '';
if ($Desktop == "A") {
    $Desktop = 'S';
} elseif ($Desktop == "I") {
    $Desktop = 'N';
}


/* asigna valores a la variable $Mobile y inicializa un array vacío. */
if ($Mobile == "A") {
    $Mobile = 'S';
} elseif ($Mobile == "I") {
    $Mobile = 'N';
}
$final=array();



/* inicializa una respuesta sin errores y define propiedades de alerta. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

//$response["Data"] = array("Objects" => $final, "Count" => $productos->count[0]->{".count"});

$response["pos"] = $SkeepRows;

/* Inicializa el conteo total y asigna los datos finales a la respuesta. */
$response["total_count"] = 0;
$response["data"] = $final;

if($CountrySelect != '' && $CountrySelect != '0') {



/* Se crea un objeto y se asegura que el tipo de proveedor no esté vacío. */
    $ProductoMandante = new ProductoMandante();

    $rules = [];


    if ($providerType != "") {
        array_push($rules, array("field" => "subproveedor.tipo", "data" => "$providerType", "op" => "eq"));
    }

    
    /* Añade reglas de comparación basadas en los valores de Desktop y Mobile. */
    if ($Desktop != "") {
        array_push($rules, array("field" => "producto.desktop", "data" => "$Desktop", "op" => "eq"));
    }

    if ($Mobile != "") {
        array_push($rules, array("field" => "producto.mobile", "data" => "$Mobile", "op" => "eq"));
    }

    
    /* Agrega reglas de filtrado según ID y país si están presentes. */
    if ($Id != "") {
        array_push($rules, array("field" => "producto_mandante.prodmandante_id", "data" => "$Id", "op" => "eq"));
    }
    if ($CountrySelect != "") {
        array_push($rules, array("field" => "producto_mandante.pais_id", "data" => "$CountrySelect", "op" => "eq"));
    }


    
    /* Agrega reglas según el estado de activación y verificación de un producto. */
    if ($IsActivate == "I") {
        array_push($rules, array("field" => "producto_mandante.estado", "data" => "I", "op" => "eq"));
    } else if ($IsActivate == "A") {
        array_push($rules, array("field" => "producto_mandante.estado", "data" => "A", "op" => "eq"));
    }

    if ($IsVerified == "I") {
        array_push($rules, array("field" => "producto_mandante.verifica", "data" => "I", "op" => "eq"));
    } else if ($IsVerified == "A") {
/* Agrega una regla si $IsVerified es igual a "A". */

        array_push($rules, array("field" => "producto_mandante.verifica", "data" => "A", "op" => "eq"));
    }


    
    /* Filtra datos según país y socio, creando reglas para consultas. */
    if ($FilterCountry != "" && $FilterCountry != null) {
        $FilterCountry = ($FilterCountry == 'A') ? 'A' : 'I';

        array_push($rules, array("field" => "producto_mandante.filtro_pais", "data" => "$FilterCountry", "op" => "eq"));
    }

    if ($Partner != "") {

        array_push($rules, array("field" => "producto_mandante.mandante", "data" => "$Partner", "op" => "eq"));
    }


    
    /* Agrega reglas a un arreglo si las variables no están vacías. */
    if ($SubProviderId != "") {
        array_push($rules, array("field" => "subproveedor.subproveedor_id", "data" => $SubProviderId, "op" => "eq"));
    }


    if ($Minimum != "") {

        array_push($rules, array("field" => "producto_mandante.min", "data" => "$Minimum", "op" => "eq"));
    }

    
    /* añade reglas a un arreglo basado en condiciones de entrada. */
    if ($Maximum != "") {

        array_push($rules, array("field" => "producto_mandante.max", "data" => "$Maximum", "op" => "eq"));
    }


    if ($Product != "") {
        if ($_SESSION["Global"] == "S") {
            array_push($rules, array("field" => "producto.producto_id", "data" => "$Product", "op" => "eq"));

        } else {
            array_push($rules, array("field" => "producto_mandante.prodmandante_id", "data" => "$Product", "op" => "eq"));

        }

    }


    
    /* Añade reglas de filtro a un array si ProviderId o Name no están vacíos. */
    if ($ProviderId != "") {

        array_push($rules, array("field" => "producto.proveedor_id", "data" => "$ProviderId", "op" => "eq"));
    }

    if ($Name != "") {

        array_push($rules, array("field" => "producto.descripcion", "data" => "$Name", "op" => "cn"));
    }


    
    /* Agrega reglas a un array basado en condiciones de variables. */
    if ($FeaturedOrder != "") {

        array_push($rules, array("field" => "producto_mandante.orden_destacado", "data" => "$FeaturedOrder", "op" => "cn"));
    }

    if ($ProcessingTime != "") {

        array_push($rules, array("field" => "producto_mandante.tiempo_procesamiento", "data" => "$ProcessingTime", "op" => "cn"));
    }

    
    /* agrega condiciones a un array de reglas basadas en tipo de dispositivo y usuario. */
    if ($TypeDevice != "") {

        // array_push($rules, array("field" => "producto.descripcion", "data" => "$TypeDevice", "op" => "cn"));
    }


// Si el usuario esta condicionado por el mandante y no es de Global
    if ($_SESSION['Global'] == "N") {
        array_push($rules, array("field" => "producto_mandante.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    }





/* Define el orden de los productos basado en la solicitud de ordenamiento del usuario. */
    $orden = "producto_mandante.prodmandante_id";
    $ordenTipo = "asc";

    if ($_REQUEST["sort[Order]"] != "") {
        $orden = "producto_mandante.orden";
        $ordenTipo = ($_REQUEST["sort[Order]"] == "asc") ? "asc" : "desc";

    }

/* crea un filtro JSON y obtiene productos usando ese filtro. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $jsonfiltro = json_encode($filtro);

    $productos = $ProductoMandante->getProductosMandanteCustom(" producto_mandante.*,producto.*,mandante.*,proveedor.*,subproveedor.subproveedor_id,subproveedor.tipo", $orden, $ordenTipo, $SkeepRows, $MaxRows, $jsonfiltro, true);

    $productos = json_decode($productos);


/* Se inicializa un arreglo vacío llamado "final" en PHP. */
    $final = [];


    foreach ($productos->data as $key => $value) {


/* crea un array con información de productos y proveedores. */
        $array = [];

        $array["Id"] = $value->{"producto_mandante.prodmandante_id"};

        $array["Product"] = $value->{"producto.descripcion"} . " (" . $value->{"producto.producto_id"} . ")";
        $array["ProviderId"] = $value->{"proveedor.descripcion"};

/* Asigna propiedades de un objeto a un array asociativo en PHP. */
        $array["Partner"] = $value->{"mandante.descripcion"};
        $array["TypeProduct"] = $value->{"subproveedor.tipo"};
        $array["IsActivate"] = $value->{"producto_mandante.estado"};
        $array["IsVerified"] = $value->{"producto_mandante.verifica"};
        $array["FilterCountry"] = $value->{"producto_mandante.filtro_pais"};
        $array["Maximum"] = $value->{"producto_mandante.max"};

/* Asigna valores de un objeto a un array usando claves específicas en PHP. */
        $array["Minimum"] = $value->{"producto_mandante.min"};
        $array["ProcessingTime"] = $value->{"producto_mandante.tiempo_procesamiento"};
        $array["Order"] = $value->{"producto_mandante.orden"};
        $array["Mobile"] = $value->{"producto.mobile"};
        $array["Desktop"] = $value->{"producto.desktop"};
        $array["Order"] = $value->{"producto_mandante.orden"};

/* Código asigna valores de un objeto a un array asociativo en PHP. */
        $array["FeaturedOrder"] = $value->{"producto_mandante.orden_destacado"};
        $array["Rows"] = $value->{"producto_mandante.num_fila"};
        $array["Columns"] = $value->{"producto_mandante.num_columna"};
        $array["Commission"] = $value->{"producto_mandante.valor"}; // nuevo campo

        $array["Info"] = $value->{"producto_mandante.extra_info"};

/* asigna valores a un arreglo desde un objeto. */
        $array["providerType"] = $value->{"subproveedor.tipo"};


        $array["Image"] = $value->{"producto_mandante.image_url"};
        $array["Image2"] = $value->{"producto_mandante.image_url2"};

        $array["CodeMincetur"] = $value->{"producto_mandante.codigo_minsetur"};

        
        /* Agrega elementos del array proporcionado a la variable $final en PHP. */
        array_push($final, $array);

    }

/* Asigna el conteo de productos y los datos finales a la respuesta. */
    $response["total_count"] = $productos->count[0]->{".count"};
    $response["data"] = $final;

}


