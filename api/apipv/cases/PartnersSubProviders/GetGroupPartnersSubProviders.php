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
use Backend\dto\Subproveedor;
use Backend\dto\SubproveedorMandante;
use Backend\dto\SubproveedorTercero;
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
use Backend\mysql\SubproveedorMandanteMySqlDAO;
use Backend\mysql\SubproveedorMySqlDAO;
use Backend\mysql\SubproveedorTerceroMySqlDAO;
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
 * Obtención de subproveedores y proveedores asociados a productos.
 *
 * Este recurso permite recuperar una lista de subproveedores y proveedores asociados en función de múltiples filtros,
 * como identificador, nombre, estado y relación con mandantes. Se utiliza paginación y ordenamiento para estructurar los resultados.
 *
 * @param int $params ->Id : Identificador del subproveedor mandante.
 * @param int $params ->OrderedItem : Orden de los elementos en la consulta.
 * @param int $params ->IsActivate : Estado de activación del subproveedor.
 * @param int $params ->IsVerified : Indica si el subproveedor está verificado.
 * @param string $params ->FilterCountry : País de filtrado.
 * @param string $params ->Products : Lista de productos relacionados.
 * @param string $params ->Partner : Identificador del socio comercial.
 * @param float $params ->Minimum : Monto mínimo en filtros de búsqueda.
 * @param float $params ->Maximum : Monto máximo en filtros de búsqueda.
 * @param string $params ->Product : Producto específico a filtrar.
 * @param string $_REQUEST ["PartnerReference"] : Referencia del socio comercial para filtrado.
 * @param string $_REQUEST ["Id"] : Identificador del subproveedor.
 * @param string $_REQUEST ["Name"] : Nombre del subproveedor.
 * @param int $_REQUEST ["count"] : Cantidad máxima de registros a obtener.
 * @param int $_REQUEST ["start"] : Punto de inicio para la paginación.
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si ocurrió un error en la operación.
 *  - *AlertType* (string): Tipo de alerta generada.
 *  - *AlertMessage* (string): Mensaje de alerta en caso de error.
 *  - *ModelErrors* (array): Lista de errores de validación.
 *  - *Data* (array): Contiene los resultados de la consulta, incluyendo:
 *      - *ExcludedProvidersList* (array): Lista de subproveedores excluidos.
 *      - *IncludedProvidersList* (string): Lista de subproveedores incluidos.
 *  - *pos* (int): Posición inicial de los datos recuperados.
 *  - *total_count* (int): Total de registros encontrados en la consulta.
 *
 *
 * @throws Exception no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* asigna valores de parámetros para paginación de datos en una solicitud. */
$MaxRows = $_REQUEST["count"];
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* inicializa $OrderedItem y $MaxRows si están vacíos. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 10000;
}


/* Asignación de valores de parámetros a variables en PHP. */
$Id = $params->Id;
$IsActivate = $params->IsActivate;
$IsVerified = $params->IsVerified;
$FilterCountry = $params->FilterCountry;
$Products = $params->Products;
$Partner = $params->Partner;

/* Asignación de variables desde parámetros y solicitud HTTP. */
$Minimum = $params->Minimum;
$Maximum = $params->Maximum;
$Product = $params->Product;

$Partner = $_REQUEST["Partner"];
$PartnerReference = $_REQUEST["PartnerReference"];


/* recibe parámetros y crea una instancia de SubproveedorMandante con reglas vacías. */
$Id = $_REQUEST["Id"];
$Name = $_REQUEST["Name"];


$SubproveedorMandante = new SubproveedorMandante();

$rules = [];


/* añade reglas de filtro basadas en condiciones de $Id y $Partner. */
if ($Id != "") {
    array_push($rules, array("field" => "subproveedor_mandante.provmandante_id", "data" => "$Id", "op" => "eq"));
}

if ($Partner != "") {

    array_push($rules, array("field" => "subproveedor_mandante.mandante", "data" => "$Partner", "op" => "eq"));
}

/* Agrega reglas de filtrado según el proveedor y el nombre si no están vacíos. */
if ($ProviderId != "") {

    array_push($rules, array("field" => "subproveedor_mandante.proveedor_id", "data" => "$ProviderId", "op" => "eq"));
}

if ($Name != "") {

    array_push($rules, array("field" => "subproveedor.descripcion", "data" => "$Name", "op" => "cn"));
}

// Si el usuario esta condicionado por el mandante y no es de Global

/* añade reglas para filtrar proveedores activos en una sesión. */
if ($_SESSION['Global'] == "N") {
    //array_push($rules, array("field" => "subproveedor_mandante.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
}
array_push($rules, array("field" => "proveedor_mandante.estado", "data" => "A", "op" => "eq"));

array_push($rules, array("field" => "subproveedor_mandante.estado", "data" => "A", "op" => "eq"));


/* Código para definir un orden y un filtro en una consulta de base de datos. */
$orden = "subproveedor_mandante.provmandante_id";
$ordenTipo = "asc";

$SubproveedorMandante = new SubproveedorMandante();

$filtro = array("rules" => $rules, "groupOp" => "AND");

/* codifica filtros y obtiene proveedores desde una base de datos en formato JSON. */
$jsonfiltro = json_encode($filtro);

$proveedores = $SubproveedorMandante->getSubproveedoresMandanteCustom(" subproveedor_mandante.*,mandante.*,subproveedor.*,proveedor.descripcion ", $orden, $ordenTipo, $SkeepRows, $MaxRows, $jsonfiltro, true);

$proveedores = json_decode($proveedores);

$proveedoresString = '##';


/* inicializa arrays y concatena IDs de subproveedores en una cadena. */
$final = [];

$children_final = [];
$children_final2 = [];


foreach ($proveedores->data as $key => $value) {

    $proveedoresString = $proveedoresString . "," . $value->{"subproveedor.subproveedor_id"};

}


/* gestiona parámetros de solicitud para controlar el número y inicio de filas. */
$MaxRows = $_REQUEST["count"];
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* asigna valores predeterminados si las variables están vacías. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 10000000;
}


/* Se crea un subproveedor y se agrega una regla si el nombre no está vacío. */
$Subproveedor = new Subproveedor();

$rules = [];

if ($Name != "") {

    array_push($rules, array("field" => "subproveedor.descripcion", "data" => "$Name", "op" => "cn"));
}

/* Se crea un filtro JSON para reglas de validación de estados en 'Subproveedor'. */
array_push($rules, array("field" => "proveedor_mandante.estado", "data" => "A", "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");
$jsonfiltro = json_encode($filtro);

$Subproveedor = new Subproveedor();


/* Se obtienen y procesan subproveedores, organizando datos en un nuevo arreglo. */
$proveedores = $Subproveedor->getSubproveedoresCustom(" subproveedor.*,proveedor.descripcion ", "subproveedor.descripcion", "asc", $SkeepRows, $MaxRows, $jsonfiltro, true, $Partner);

$proveedores = json_decode($proveedores);

$final = [];

foreach ($proveedores->data as $key => $value) {

    $array = [];

    $children = [];
    $children["id"] = $value->{"subproveedor.subproveedor_id"};
    $children["value"] = $value->{"subproveedor.descripcion"} . ' (' . $value->{"proveedor.descripcion"} . ')';

    array_push($children_final, $children);

}

if ($PartnerReference != "" && $Partner != "-1") {


    /* Se generan reglas basadas en condiciones para filtrar datos específicos. */
    $rules = [];

    if ($Id != "") {
        array_push($rules, array("field" => "subproveedor_mandante.provmandante_id", "data" => "$Id", "op" => "eq"));
    }


    if ($PartnerReference != "" && $PartnerReference != "-1") {

        array_push($rules, array("field" => "subproveedor_mandante.mandante", "data" => "$PartnerReference", "op" => "eq"));
    }


    /* Agrega reglas de filtrado basadas en ProviderId y Name si no están vacíos. */
    if ($ProviderId != "") {

        array_push($rules, array("field" => "subproveedor_mandante.proveedor_id", "data" => "$ProviderId", "op" => "eq"));
    }

    if ($Name != "") {

        array_push($rules, array("field" => "subproveedor.descripcion", "data" => "$Name", "op" => "cn"));
    }

// Si el usuario esta condicionado por el mandante y no es de Global

    /* Agrega reglas basadas en la sesión actual para filtrar datos de subproveedores. */
    if ($_SESSION['Global'] == "N") {
        //array_push($rules, array("field" => "subproveedor_mandante.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    }

    array_push($rules, array("field" => "subproveedor_mandante.estado", "data" => "A", "op" => "eq"));

    $orden = "subproveedor_mandante.provmandante_id";

    /* define un filtro y crea un objeto de tipo SubproveedorMandante. */
    $ordenTipo = "asc";

    $SubproveedorMandante = new SubproveedorMandante();

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $jsonfiltro = json_encode($filtro);


    /* Se obtienen subproveedores con datos específicos y se decodifican en formato JSON. */
    $proveedores = $SubproveedorMandante->getSubproveedoresMandanteCustom(" subproveedor_mandante.*,mandante.*,subproveedor.*,proveedor.descripcion ", $orden, $ordenTipo, $SkeepRows, $MaxRows, $jsonfiltro, true);

    $proveedores = json_decode($proveedores);


    $final = [];


    /* recopila información de proveedores en un array estructurado. */
    $children_final = [];
    $children_final2 = [];


    foreach ($proveedores->data as $key => $value) {

        $array = [];

        $children = [];
        $children["id"] = $value->{"subproveedor.subproveedor_id"};
        $children["value"] = $value->{"subproveedor.descripcion"} . ' (' . $value->{"proveedor.descripcion"} . ')';

        array_push($children_final, $children);

    }
}


/* Código que configura una respuesta JSON con información sobre errores y datos. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

//$response["Data"] = array("Objects" => $final, "Count" => $proveedores->count[0]->{".count"});
$response["Data"]["ExcludedProvidersList"] = $children_final;

/* procesa y formatea datos relacionados con proveedores para la respuesta JSON. */
$response["Data"]["IncludedProvidersList"] = str_replace("##", "", str_replace("##,", "", $proveedoresString));

$response["pos"] = $SkeepRows;
$response["total_count"] = $proveedores->count[0]->{".count"};
$response["data"] = $final;

