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
use Backend\dto\Template;
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
use Backend\dto\UsuarioMensajecampana;
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
 * Templates/GetTypesTemplates
 *
 * Obtener clasificadores personalizados según filtros definidos.
 *
 * Este recurso obtiene clasificadores personalizados a partir de una serie de filtros aplicados, tales como
 * el tipo de clasificación (por ejemplo, "emails", "receipts", "sms", "inbox", "popup"), rango de fechas,
 * y otros parámetros de entrada. Los resultados son devueltos en un formato adecuado para su visualización,
 * incluyendo los datos de los clasificadores y su respectiva descripción.
 *
 * @param object $params : Objeto que contiene los parámetros de entrada para la consulta, incluyendo filtros,
 *                         longitud de filas, sección, tipo de clasificador, fechas, etc.
 * @param string $params->length : Número máximo de filas a retornar.
 * @param string $params->OrderedItem : Elemento por el cual se debe ordenar la consulta.
 * @param string $params->start : Fila inicial para la consulta.
 * @param string $params->Section : Sección a la que pertenece el clasificador.
 * @param string $params->Type : Tipo de clasificador.
 * @param string $params->DateFrom : Fecha de inicio para filtrar los resultados.
 * @param string $params->DateTo : Fecha de fin para filtrar los resultados.
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 *  - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 *  - *ModelErrors*  (array): Retorna array vacío.
 *  - *Data* (array): Datos de los clasificadores, incluyendo el ID y la descripción de cada uno.
 *
 *
 * Ejemplo de respuesta en caso de error:
 *
 * $response["HasError"] = true;
 * $response["AlertType"] = "danger";
 * $response["AlertMessage"] = "Error al obtener los clasificadores";
 * $response["ModelErrors"] = [];
 *
 * @throws Exception no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */



/* Código PHP que asigna variables a partir de parámetros de entrada. */
$MaxRows = $params->length;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->start;

$Section = $params->Section;
$lenguaje = $_SESSION["idioma"];


/* asigna valores de parámetros a variables basadas en condiciones de fechas. */
$Type = $params->Type;

if ($params->DateFrom != "") {
    $DateFrom = $params->DateFrom;
}

if ($params->DateTo != "") {
    $DateTo = $params->DateTo;
}

/* asigna valores predeterminados a MaxRows y SkeepRows si están vacíos. */
if ($MaxRows == "") {

    $MaxRows = $params->length;
}

if ($SkeepRows == "") {

    $SkeepRows = $params->start;
}


/* asigna valores predeterminados si las variables están vacías. */
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* establece un valor predeterminado y define arreglos para plantillas y reglas. */
if ($MaxRows == "") {
    $MaxRows = 1000;
}

$plantillasRecibidas = [];

$rules = [];

if ($Section != "") {


    /* añade reglas según la sección: "emails" o "receipts". */
    if ($Section == "emails") {

        array_push($rules, array("field" => "clasificador.tipo", "data" => "TPE", "op" => "eq"));
    }

    if ($Section == "receipts") {

        array_push($rules, array("field" => "clasificador.tipo", "data" => "TPR", "op" => "eq"));
    }


    /* Agrega reglas a un arreglo según la sección seleccionada en una condición. */
    if ($Section == "sms") {

        array_push($rules, array("field" => "clasificador.tipo", "data" => "TPS", "op" => "eq"));
    }
    if ($Section == "inbox") {

        array_push($rules, array("field" => "clasificador.tipo", "data" => "TPI", "op" => "eq"));
    }

    /* Agrega una regla para el clasificador en la sección de popup si está activa. */
    if ($Section == "popup") {

        array_push($rules, array("field" => "clasificador.tipo", "data" => "TPP", "op" => "eq"));
    }
}


/* Se define un filtro en formato JSON y se instancian clases de plantilla y clasificador. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json2 = json_encode($filtro);


$Template = new Template();
$Clasificador = new Clasificador();

/* obtiene clasificadores personalizados y los transforma en un array. */
$plantillas = $Clasificador->getClasificadoresCustom("clasificador.*", "clasificador.clasificador_id", "desc", $SkeepRows, $MaxRows, $json2, true);
$plantillas = json_decode($plantillas);


foreach ($plantillas->data as $key => $value) {

    $array = [];

    $array["id"] = $value->{"clasificador.clasificador_id"};
    $array["value"] = $value->{"clasificador.descripcion"};


    array_push($plantillasRecibidas, $array);

}


/* Se crea un arreglo `$response` que contiene los datos de `$plantillasRecibidas`. */
$response = array();
$response["data"] = $plantillasRecibidas;



