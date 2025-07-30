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
 * BetShop/GetBetShopsCompetenceMap
 *
 * Obtener los puntos de venta de la competencia para el mapa
 *
 * @param no
 *
 * @return no
 * {"HasError":boolean,"AlertType": string,"AlertMessage": string,"url": string,"success": string}
 *
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */

/**
 * Obtiene los puntos de venta de la competencia para el mapa basándose en los filtros y parámetros proporcionados.
 *
 * @param string $params JSON codificado que contiene los datos de la solicitud, incluyendo:
 * @param int $params ->count Número máximo de registros a devolver. Por defecto, 100000.
 * @param int $params ->start Número de registros a omitir para la paginación. Por defecto, 0.
 * @param string $params ->Competition Identificador de la competencia.
 * @param string $params ->Name Nombre del punto de venta para filtrar.
 * @param string $params ->Description Descripción del punto de venta.
 * @param int $params ->CountryId Identificador del país asociado.
 * @param int $params ->RegionId Identificador de la región asociada.
 * @param int $params ->CityId Identificador de la ciudad asociada.
 * @param string $params ->Latitud Latitud del punto de venta.
 * @param string $params ->Longitud Longitud del punto de venta.
 * @param string $params ->Address Dirección del punto de venta.
 *
 *
 * @return array $response Contiene los datos de la respuesta, incluyendo:
 *                         - HasError (bool): Indica si ocurrió un error.
 *                         - AlertType (string): Tipo de alerta ('success' en caso de éxito).
 *                         - AlertMessage (string): Mensaje de alerta.
 *                         - ModelErrors (array): Lista de errores del modelo.
 *                         - pos (int): Posición inicial de los datos devueltos.
 *                         - total_count (int): Número total de registros encontrados.
 *                         - data (array): Datos de los puntos de venta de la competencia, incluyendo:
 *                             - Id (int): Identificador del punto de venta.
 *                             - Name (string): Nombre del punto de venta.
 *                             - Description (string): Descripción del punto de venta.
 *                             - Longitud (string): Longitud del punto de venta.
 *                             - Latitud (string): Latitud del punto de venta.
 *                             - Address (string): Dirección del punto de venta.
 *                             - CompetitionId (int): Identificador de la competencia asociada.
 *                             - CompetitionName (string): Nombre de la competencia.
 *                             - CompetitionColor (string): Color asociado a la competencia.
 */


/* obtiene datos de una solicitud HTTP para manipular filas de una competencia. */
$MaxRows = $_REQUEST["count"];
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

$Competition = $_REQUEST["Competition"];
$Name = $_REQUEST["Name"];
$Description = $_REQUEST["Description"];

/* obtiene datos de ubicación y dirección desde una solicitud HTTP. */
$CountryId = $_REQUEST["CountryId"];
$RegionId = $_REQUEST["RegionId"];
$CityId = $_REQUEST["CityId"];
$Latitud = $_REQUEST["Latitud"];
$Longitud = $_REQUEST["Longitud"];
$Address = $_REQUEST["Address"];


/* Inicializa un objeto y establece valores predeterminados para variables vacías. */
$Mandante = new Mandante();

if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* Se establece un valor predeterminado de 100000 para $MaxRows si está vacío. */
if ($MaxRows == "") {
    $MaxRows = 100000;
}


$rules = [];


/* Verifica si el usuario es concesionario y agrega reglas a un array. */
if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
    $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

    array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
}


/* verifica el perfil del usuario y agrega reglas a un arreglo. */
if ($_SESSION["win_perfil"] == "CONCESIONARIO2") {
    $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

    array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
}


/* Condicional que agrega reglas para un concesionario específico en una sesión. */
if ($_SESSION["win_perfil"] == "CONCESIONARIO3") {
    $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

    array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
}


/* agrega condiciones a un array basado en variables de sesión y competencia. */
if ($_SESSION['PaisCond'] == "S") {

    array_push($rules, array("field" => "departamento.pais_id", "data" => $_SESSION["pais_id"], "op" => "eq"));

}

if ($Competition != "") {
    array_push($rules, array("field" => "competencia_puntos.competencia_id", "data" => $Competition, "op" => "eq"));
}

/* Agrega reglas a un array si $Name o $Description no están vacíos. */
if ($Name != "") {
    array_push($rules, array("field" => "competencia_puntos.nombre", "data" => $Name, "op" => "cn"));
}
if ($Description != "") {
    array_push($rules, array("field" => "competencia_puntos.descripcion", "data" => $Description, "op" => "cn"));
}

/* Agrega reglas de filtrado basadas en identificación de país y región. */
if ($CountryId != "") {
    array_push($rules, array("field" => "departamento.pais_id", "data" => $CountryId, "op" => "eq"));
}
if ($RegionId != "") {
    array_push($rules, array("field" => "departamento.depto_id", "data" => $RegionId, "op" => "eq"));
}

/* Agrega reglas de filtrado basadas en ciudad y latitud si están definidas. */
if ($CityId != "") {
    array_push($rules, array("field" => "ciudad.ciudad_id", "data" => $CityId, "op" => "eq"));
}
if ($Latitud != "") {
    array_push($rules, array("field" => "competencia_puntos.latitud", "data" => $Latitud, "op" => "cn"));
}

/* Agrega reglas de filtrado si Longitud o Dirección no están vacíos. */
if ($Longitud != "") {
    array_push($rules, array("field" => "competencia_puntos.longitud", "data" => $Longitud, "op" => "cn"));
}

if ($Address != "") {
    array_push($rules, array("field" => "competencia_puntos.direccion", "data" => $Address, "op" => "cn"));
}


/* Se crea un filtro JSON y se consultan datos de competencia. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);


$CompetenciaPuntos = new CompetenciaPuntos();


$mandantes = $CompetenciaPuntos->getCompetenciaPuntosCustom("competencia_puntos.*,departamento.*", "competencia_puntos.comppunto_id", "asc", $SkeepRows, $MaxRows, $json, true);


/* Decodifica un JSON de mandantes y crea un array vacío llamado final. */
$mandantes = json_decode($mandantes);

$final = [];

foreach ($mandantes->data as $key => $value) {


    /* crea un array asociativo con datos de competencia. */
    $array = [];

    $array["id"] = $value->{"competencia_puntos.comppunto_id"};
    $array["Id"] = $value->{"competencia_puntos.comppunto_id"};
    $array["Name"] = $value->{"competencia_puntos.nombre"};
    $array["Description"] = $value->{"competencia_puntos.descripcion"};

    /* Asigna valores de competencia a un array y establece nombres y colores según ID. */
    $array["Longitud"] = $value->{"competencia_puntos.longitud"};
    $array["Latitud"] = $value->{"competencia_puntos.latitud"};
    $array["Address"] = $value->{"competencia_puntos.direccion"};
    $array["CompetitionId"] = $value->{"competencia_puntos.competencia_id"};

    switch ($array["CompetitionId"]) {
        case 1:
            $array["CompetitionName"] = "Inkabet";
            $array["CompetitionColor"] = "black";
            break;
        case 2:
            $array["CompetitionName"] = "BetCris";
            $array["CompetitionColor"] = "#1B3459";
            break;
        case 3:
            $array["CompetitionName"] = "ApuestaTotal";
            $array["CompetitionColor"] = "red";
            break;
    }


    /* Agrega el contenido de `$array` al final del array `$final`. */
    array_push($final, $array);

}


/* agrega condiciones a la regla según los identificadores de país y región. */
$rules = [];

if ($CountryId != "") {
    array_push($rules, array("field" => "departamento.pais_id", "data" => $CountryId, "op" => "eq"));
}
if ($RegionId != "") {
    array_push($rules, array("field" => "departamento.depto_id", "data" => $RegionId, "op" => "eq"));
}

/* Agrega reglas a un array si las variables no están vacías. */
if ($CityId != "") {
    array_push($rules, array("field" => "ciudad.ciudad_id", "data" => $CityId, "op" => "eq"));
}
if ($Latitud != "") {
    array_push($rules, array("field" => "usuario.ubicacion_latitud", "data" => $Latitud, "op" => "cn"));
}

/* Añade reglas a un arreglo si las variables Longitud o Address no están vacías. */
if ($Longitud != "") {
    array_push($rules, array("field" => "usuario.ubicacion_longitud", "data" => $Longitud, "op" => "cn"));
}

if ($Address != "") {
    array_push($rules, array("field" => "punto_venta.direccion", "data" => $Address, "op" => "cn"));
}


/* Código que define reglas de validación para usuarios basadas en diferentes criterios. */
array_push($rules, array("field" => "usuario.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "usuario.ubicacion_longitud", "data" => "''", "op" => "nn"));
array_push($rules, array("field" => "usuario.ubicacion_latitud", "data" => "''", "op" => "nn"));
array_push($rules, array("field" => "usuario.ubicacion_longitud", "data" => "0", "op" => "ne"));
array_push($rules, array("field" => "usuario.ubicacion_latitud", "data" => "0", "op" => "ne"));


if ($_SESSION['PaisCond'] == "S") {

    array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION["pais_id"], "op" => "eq"));

}


/* Se crea un filtro JSON y se utiliza para obtener puntos de venta personalizados. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);


$PuntoVenta = new PuntoVenta();


$mandantes = $PuntoVenta->getPuntoVentasCustom("usuario.fecha_crea,usuario.nombre,usuario.ubicacion_longitud,usuario.ubicacion_latitud,usuario.moneda,usuario.fecha_ult,punto_venta.*", "punto_venta.puntoventa_id", "asc", $SkeepRows, $MaxRows, $json, true);


/* transforma datos JSON en un array estructurado para competencia. */
$mandantes = json_decode($mandantes);


foreach ($mandantes->data as $key => $value) {

    $array = [];

    $array["Name"] = $value->{"usuario.nombre"};
    $array["Description"] = $value->{"usuario.nombre"};
    $array["Longitud"] = $value->{"usuario.ubicacion_longitud"};
    $array["Latitud"] = $value->{"usuario.ubicacion_latitud"};
    $array["Address"] = $value->{"punto_venta.direccion"};
    $array["CompetitionId"] = 0;
    $array["CompetitionName"] = "Doradobet";
    $array["CompetitionColor"] = "#f9da59";

    array_push($final, $array);

}


/* Código que configura un mensaje de respuesta sin errores y sin datos adicionales. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

//$response["Data"] = array();
//$response["Data"]["Objects"] = $final;

$response["pos"] = $SkeepRows;

/* asigna el conteo de mandantes y datos finales a un array de respuesta. */
$response["total_count"] = $mandantes->count[0]->{".count"};
$response["data"] = $final;

//Objects