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
use Backend\dto\Banco;
use Backend\dto\ProductoComision;
use Backend\dto\ProductoInterno;
use Backend\dto\ProductoTercero;
use Backend\dto\ProductoterceroUsuario;
use Backend\dto\PromocionalLog;
use Backend\dto\Proveedor;
use Backend\dto\ProveedorMandante;
use Backend\dto\ProveedorTercero;
use Backend\dto\PuntoVenta;
use Backend\dto\BancoMandante;
use Backend\dto\BancoDetalle;
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
use Backend\mysql\BancoMySqlDAO;
use Backend\mysql\BancoMandanteMySqlDAO;
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
 * Obtener bancos asociados a un partner y país.
 *
 * Este script permite obtener los bancos asociados a un partner y país, 
 * aplicando filtros y reglas específicas según los parámetros enviados.
 *
 * @param object $params Objeto con los siguientes valores:
 *     - string $params->OrderedItem Elemento ordenado (opcional).
 *     - string $params->Banks Bancos para filtro (opcional).
 *     - string $params->Partner Partner para filtro (obligatorio).
 *     - string $params->Bank Banco para filtro (opcional).
 *     - string $params->Desktop Indica si es escritorio (opcional, valores: "A", "I").
 *     - string $params->Mobile Indica si es móvil (opcional, valores: "A", "I").
 * @param array $_REQUEST Arreglo con los siguientes valores:
 * @param string $_REQUEST["count"] Número máximo de filas (opcional, predeterminado: 1000).
 * @param string $_REQUEST["start"] Número de filas a omitir (opcional, predeterminado: 0).
 * @param string $_REQUEST["CountrySelect"] País para filtro (opcional).
 * @param string $_REQUEST["BankId"] ID del banco para filtro (opcional).
 * @param string $_REQUEST["sort[Order]"] Orden de los datos (opcional, valores: "asc", "desc").
 * 
 * 
 * @return array $response Respuesta con los siguientes valores:
 *     - HasError: Indica si hubo un error (boolean).
 *     - AlertType: Tipo de alerta (string).
 *     - AlertMessage: Mensaje de alerta (string).
 *     - ModelErrors: Errores del modelo (array).
 *     - Data: Datos de respuesta con:
 *         - ExcludedBanksList: Lista de bancos posibles a enlazar.
 *         - IncludedBanksList: Lista de bancos ya asignados.
 *     - pos: Posición inicial de los datos.
 *     - total_count: Total de registros encontrados.
 *     - data: Datos procesados.
 * @throws Exception Si se detecta una condición inusual o faltan parámetros obligatorios.
 */

//Verificaciones de variables enviadas por Frontend

/* obtiene valores de solicitud y establece filas a omitir y contar. */
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


/* Asignación de parámetros de entrada a variables para su uso posterior en el código. */
$Banks = $params->Banks;
$Partner = $params->Partner;
$Bank = $params->Bank;
$Banks = $_REQUEST["Banks"];
$Partner = $_REQUEST["Partner"]; // Partner para filtro
$CountrySelect = $_REQUEST["CountrySelect"]; //Pais para filtro

/* gestiona parámetros de solicitud y configura valores para escritorio y móvil. */
$Bank = $_REQUEST["Bank"];
$BankId = $_REQUEST["BankId"]; // id del banco para filtro

$Desktop = ($_REQUEST["Desktop"] == "A" || $_REQUEST["Desktop"] == "I") ? $_REQUEST["Desktop"] : '';
$Mobile = ($_REQUEST["Mobile"] == "A" || $_REQUEST["Mobile"] == "I") ? $_REQUEST["Mobile"] : '';
if ($Desktop == "A") {
    $Desktop = 'S';
} elseif ($Desktop == "I") {
    /* asigna 'N' a $Desktop si su valor es "I". */

    $Desktop = 'N';
}


/* ajusta el valor de $Mobile y crea una instancia de BancoMandante. */
if ($Mobile == "A") {
    $Mobile = 'S';
} elseif ($Mobile == "I") {
    $Mobile = 'N';
}

//Instanciamos BancoMandante para obtener los bancos ya asignados a un partner y pais
$BancoMandante = new BancoMandante();

//Armamos filtro

/* Agrega reglas de validación según si Desktop y Mobile tienen valores definidos. */
$rules = [];

if ($Desktop != "") {
    array_push($rules, array("field" => "banco.desktop", "data" => "$Desktop", "op" => "eq"));
}

if ($Mobile != "") {
    array_push($rules, array("field" => "banco.mobile", "data" => "$Mobile", "op" => "eq"));
}


/* valida país y socio, agregando reglas o lanzando excepciones. */
if ($CountrySelect != "") {
    array_push($rules, array("field" => "banco_mandante.pais_id", "data" => "$CountrySelect", "op" => "eq"));
}

if ($Partner != "") {


    if (!in_array($Partner, explode(',', $_SESSION["mandanteLista"]))) {
        throw new Exception("Inusual Detected", "11");
    }

    array_push($rules, array("field" => "banco_mandante.mandante", "data" => "$Partner", "op" => "eq"));
} else {
    /* Lanza una excepción con un mensaje de error específico en caso de una condición inusual. */

    throw new Exception("Inusual Detected", "11");
}


/* Agrega condiciones a un array según el valor de $BankId y $_SESSION["Global"]. */
if ($BankId != "") {
    if ($_SESSION["Global"] == "S") {
        array_push($rules, array("field" => "banco_mandante.banco_id", "data" => "$Bank", "op" => "eq"));

    } else {
        array_push($rules, array("field" => "banco_mandante.banco_id", "data" => "$BankId", "op" => "eq"));

    }

}

// Si el usuario esta condicionado por el mandante y no es de Global

/* Condicionalmente añade reglas a un array y define un orden específico. */
if ($_SESSION['Global'] == "N") {
    //array_push($rules, array("field" => "banco_mandante.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
}

array_push($rules, array("field" => "banco_mandante.estado", "data" => "A", "op" => "eq"));

$orden = "banco_mandante.bancomandante_id";

/* establece la ordenación de datos basado en una solicitud HTTP. */
$ordenTipo = "asc";

if ($_REQUEST["sort[Order]"] != "") {
    $orden = "banco_mandante.orden";
    $ordenTipo = ($_REQUEST["sort[Order]"] == "asc") ? "asc" : "desc";

}


/* Se filtran y obtienen datos de bancos asignados a un socio específico. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$jsonfiltro = json_encode($filtro);

// Obtenemos los bancos asignados a un partner y pais
$bancos = $BancoMandante->getBancosMandanteCustom(" banco_mandante.*,banco.*,mandante.* ", $orden, $ordenTipo, $SkeepRows, $MaxRows, $jsonfiltro, true);

$bancos = json_decode($bancos);


/* Se inicializan variables vacías para almacenar datos en arrays. */
$bancosString = '##';

$final = [];

$children_final = [];
$children_final2 = [];


/* Concatena IDs de bancos en una cadena y obtiene un conteo desde la solicitud. */
foreach ($bancos->data as $key => $value) {

    $bancosString = $bancosString . "," . $value->{"banco.banco_id"};

}


$MaxRows = $_REQUEST["count"];

/* obtiene elementos ordenados y establece filas a omitir desde parámetros de solicitud. */
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
    $MaxRows = 1000;
}


/* obtiene parámetros de entrada para escritorio, móvil, orden y banco. */
$Desktop = $params->Desktop;

$Mobile = $params->Mobile;
$Order = $params->Order;


$Bank = $_REQUEST["Bank"];

/* obtiene valores de solicitud para BankId, Order y Desktop. */
$BankId = $_REQUEST["BankId"];

$Order = $_REQUEST["Order"];


$Desktop = ($_REQUEST["Desktop"] == "A" || $_REQUEST["Desktop"] == "I") ? $_REQUEST["Desktop"] : '';

/* Asignación condicional de valores para variables Mobile y Desktop según condiciones específicas. */
$Mobile = ($_REQUEST["Mobile"] == "A" || $_REQUEST["Mobile"] == "I") ? $_REQUEST["Mobile"] : '';
if ($Desktop == "A") {
    $Desktop = 'S';
} elseif ($Desktop == "I") {
    $Desktop = 'N';
}


/* Cambia el valor de $Mobile según condiciones y verifica bancos con la clase Banco. */
if ($Mobile == "A") {
    $Mobile = 'S';
} elseif ($Mobile == "I") {
    $Mobile = 'N';
}

//Verificamos bancos a enlazar en la tabla banco

//Instanciamos la clase Banco para verificar los posibles bancos a enlazar en un partner y pais
$Banco = new Banco();

//Armamos el filtro

/* crea reglas condicionales basadas en variables de escritorio y móvil. */
$rules = [];

if ($Desktop != "") {
    array_push($rules, array("field" => "banco.desktop", "data" => "$Desktop", "op" => "eq"));
}

if ($Mobile != "") {
    array_push($rules, array("field" => "banco.mobile", "data" => "$Mobile", "op" => "eq"));
}


/* Agrega reglas a un array según condiciones de orden y banco. */
if ($Order != "") {
    array_push($rules, array("field" => "banco.orden", "data" => "$Order", "op" => "eq"));
}
if ($Bank != "") {
    if ($_SESSION["Global"] == "S") {
        array_push($rules, array("field" => "banco.banco_id", "data" => "$Bank", "op" => "eq"));
    } else {

    }
}


/* añade reglas basadas en la variable $BankId a un arreglo. */
if ($BankId != "") {
    if ($_SESSION["Global"] == "S") {
        array_push($rules, array("field" => "banco.banco_id", "data" => "$BankId", "op" => "eq"));
    } else {
        array_push($rules, array("field" => "banco.banco_id", "data" => "$BankId", "op" => "eq"));
    }
}


/* Se crea un filtro JSON y se obtienen bancos según criterios específicos. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$jsonfiltro = json_encode($filtro);


//Obtenemos los bancos posibles a enlazar en un partner y pais
$bancos = $Banco->getBancosCustom3(" banco.* ", "banco.descripcion", "asc", $SkeepRows, $MaxRows, $jsonfiltro, true);


/* procesa datos JSON de bancos, creando un arreglo con información específica. */
$bancos = json_decode($bancos);

$final = [];

foreach ($bancos->data as $key => $value) {

    $array = [];
    $children = [];
    $children["id"] = $value->{"banco.banco_id"};
    $children["value"] = $value->{"banco.descripcion"} . " (" . $value->{"banco.banco_id"} . ")";

    array_push($children_final, $children);

}


/* crea una respuesta sin errores, incluyendo una lista de bancos excluidos. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"]["ExcludedBanksList"] = $children_final; //Bancos posibles a enlazar

/* Código que procesa y limpia datos de bancos para una respuesta estructurada. */
$response["Data"]["IncludedBanksList"] = str_replace("##", "", str_replace("##,", "", $bancosString)); //Bancos ya asignados a un partner y pais

$response["pos"] = $SkeepRows;
$response["total_count"] = $bancos->count[0]->{".count"};
$response["data"] = $final;

