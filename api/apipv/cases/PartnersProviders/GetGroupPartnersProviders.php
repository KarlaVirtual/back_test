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
 * Servicio para obtener grupos de proveedores asociados a socios comerciales
 * 
 * @param object $params Parámetros de entrada
 * @param string $params->Id ID del proveedor mandante
 * @param string $params->Name Nombre del proveedor
 * @param string $params->Partner ID del socio comercial
 * @param string $params->PartnerReference Referencia del socio comercial
 * @param int $_REQUEST["count"] Número de registros a retornar
 * @param int $_REQUEST["start"] Número de registro inicial
 * @param string $params->OrderedItem Campo para ordenar resultados
 * 
 * @return object Respuesta del servicio
 * @return boolean $response->HasError Indica si hubo error
 * @return string $response->AlertType Tipo de alerta (success/error) 
 * @return string $response->AlertMessage Mensaje descriptivo
 * @return array $response->ModelErrors Errores del modelo
 * @return array $response->Data Lista de proveedores encontrados
 * @return int $response->TotalCount Total de registros
 * @throws Exception Si ocurre un error durante el proceso
 */


/* procesa parámetros de una solicitud para paginar resultados. */
$MaxRows = $_REQUEST["count"];
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* inicializa variables si están vacías, asignando valores por defecto. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 10000;
}



/* Asigna valores de parámetros a variables en un script. */
$Id = $params->Id;
$IsActivate = $params->IsActivate;
$IsVerified = $params->IsVerified;
$FilterCountry = $params->FilterCountry;
$Products = $params->Products;
$Partner = $params->Partner;

/* asigna valores de parámetros y solicitudes a variables específicas. */
$Minimum = $params->Minimum;
$Maximum = $params->Maximum;
$Product = $params->Product;

$Partner = $_REQUEST["Partner"];
$PartnerReference = $_REQUEST["PartnerReference"];


/* Se obtienen parámetros de solicitud y se inicializa un objeto de proveedor. */
$Id = $_REQUEST["Id"];
$Name = $_REQUEST["Name"];


$ProveedorMandante = new ProveedorMandante();

$rules = [];


/* agrega condiciones a un array si las variables no están vacías. */
if ($Id != "") {
    array_push($rules, array("field" => "proveedor_mandante.provmandante_id", "data" => "$Id", "op" => "eq"));
}

if ($Partner != "") {

    array_push($rules, array("field" => "proveedor_mandante.mandante", "data" => "$Partner", "op" => "eq"));
}



/* agrega reglas basadas en condiciones de proveedor y nombre. */
if ($ProviderId != "") {

    array_push($rules, array("field" => "proveedor_mandante.proveedor_id", "data" => "$ProviderId", "op" => "eq"));
}

if ($Name != "") {

    array_push($rules, array("field" => "proveedor.descripcion", "data" => "$Name", "op" => "cn"));
}

// Si el usuario esta condicionado por el mandante y no es de Global

/* gestiona reglas de búsqueda en una sesión de usuario. */
if ($_SESSION['Global'] == "N") {
    //array_push($rules, array("field" => "proveedor_mandante.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
}

array_push($rules, array("field" => "proveedor_mandante.estado", "data" => "A", "op" => "eq"));

$orden = "proveedor_mandante.provmandante_id";

/* Se establece un filtro para un ProveedorMandante y se convierte a JSON. */
$ordenTipo = "asc";

$ProveedorMandante = new ProveedorMandante();

$filtro = array("rules" => $rules, "groupOp" => "AND");
$jsonfiltro = json_encode($filtro);


/* Se obtienen proveedores y se convierten a formato JSON para procesamiento posterior. */
$proveedores = $ProveedorMandante->getProveedoresMandanteCustom(" proveedor_mandante.*,mandante.*,proveedor.* ", $orden, $ordenTipo, $SkeepRows, $MaxRows, $jsonfiltro, true);

$proveedores = json_decode($proveedores);

$proveedoresString = '##';

$final = [];


/* construye una cadena de IDs de proveedores a partir de un array. */
$children_final = [];
$children_final2 = [];


foreach ($proveedores->data as $key => $value) {

    $proveedoresString = $proveedoresString . "," . $value->{"proveedor.proveedor_id"};

}



/* obtiene parámetros de la solicitud y define filas a omitir. */
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
    $MaxRows = 10000000;
}






/* Se crea un nuevo proveedor y se agregan reglas de validación basadas en el nombre. */
$Proveedor = new Proveedor();

$rules = [];

if ($Name != "") {

    array_push($rules, array("field" => "proveedor.descripcion", "data" => "$Name", "op" => "cn"));
}


/* Código para filtrar y obtener proveedores personalizados en formato JSON. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$jsonfiltro = json_encode($filtro);

$Proveedor = new Proveedor();

$proveedores = $Proveedor->getProveedoresCustom(" proveedor.* ", "proveedor.descripcion", "asc", $SkeepRows, $MaxRows, $jsonfiltro, true);


/* decodifica JSON y estructura datos de proveedores en un array. */
$proveedores = json_decode($proveedores);

$final = [];

foreach ($proveedores->data as $key => $value) {

    $array = [];

    $children = [];
    $children["id"] = $value->{"proveedor.proveedor_id"};
    $children["value"] = $value->{"proveedor.descripcion"} ;

    array_push($children_final, $children);

}

if($PartnerReference != "" && $Partner !="-1") {

/* crea reglas de filtrado según condiciones específicas de identificación y referencia. */
    $rules = [];

    if ($Id != "") {
        array_push($rules, array("field" => "proveedor_mandante.provmandante_id", "data" => "$Id", "op" => "eq"));
    }

    if ($PartnerReference != "" && $PartnerReference != "-1") {

        array_push($rules, array("field" => "proveedor_mandante.mandante", "data" => "$PartnerReference", "op" => "eq"));
    }


    
    /* Agrega reglas para filtrar datos según el proveedor y nombre si no son vacíos. */
    if ($ProviderId != "") {

        array_push($rules, array("field" => "proveedor_mandante.proveedor_id", "data" => "$ProviderId", "op" => "eq"));
    }

    if ($Name != "") {

        array_push($rules, array("field" => "proveedor.descripcion", "data" => "$Name", "op" => "cn"));
    }

// Si el usuario esta condicionado por el mandante y no es de Global
    
    /* Verifica la sesión y almacena reglas de estado para un proveedor. */
    if ($_SESSION['Global'] == "N") {
        //array_push($rules, array("field" => "proveedor_mandante.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    }

    array_push($rules, array("field" => "proveedor_mandante.estado", "data" => "A", "op" => "eq"));

    $orden = "proveedor_mandante.provmandante_id";

    /* Código que configura un filtro en formato JSON para consultas en un proveedor. */
    $ordenTipo = "asc";

    $ProveedorMandante = new ProveedorMandante();

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $jsonfiltro = json_encode($filtro);


    /* Se obtienen proveedores mediante una consulta, convirtiendo el resultado a formato JSON. */
    $proveedores = $ProveedorMandante->getProveedoresMandanteCustom(" proveedor_mandante.*,mandante.*,proveedor.* ", $orden, $ordenTipo, $SkeepRows, $MaxRows, $jsonfiltro, true);

    $proveedores = json_decode($proveedores);


    $final = [];


    /* crea un array con información de proveedores extraída de un objeto. */
    $children_final = [];
    $children_final2 = [];


    foreach ($proveedores->data as $key => $value) {

        $array = [];

        $children = [];
        $children["id"] = $value->{"proveedor.proveedor_id"};
        $children["value"] = $value->{"proveedor.descripcion"} ;

        array_push($children_final, $children);


    }


}

/* configura una respuesta JSON sin errores y con datos específicos. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

//$response["Data"] = array("Objects" => $final, "Count" => $proveedores->count[0]->{".count"});
$response["Data"]["ExcludedProvidersList"] = $children_final;

/* limpia y organiza datos de proveedores en una respuesta estructurada. */
$response["Data"]["IncludedProvidersList"] =str_replace("##","", str_replace("##,","", $proveedoresString));

$response["pos"] = $SkeepRows;
$response["total_count"] = $proveedores->count[0]->{".count"};
$response["data"] = $final;

