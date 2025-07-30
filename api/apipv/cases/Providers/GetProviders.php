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
 * Providers/GetProviders
 *
 * Obtiene una lista paginada de proveedores con filtros personalizados.
 *
 * Este método recibe parámetros para filtrar y paginar los proveedores, y devuelve una lista de ellos con su información básica (ID, nombre, tipo, estado, verificación, abreviado, imagen). Se pueden aplicar filtros como ID, nombre, tipo, estado, abreviado y verificación. También se permiten filtros de activación y verificación, además de paginación.
 *
 * @param string $OrderedItem : Columna a order para la consulta.
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Tipo de alerta que se mostrará (por ejemplo, "success" si la operación fue exitosa).
 *  - *AlertMessage* (string): Mensaje que se mostrará junto a la alerta.
 *  - *ModelErrors* (array): Retorna un array vacío en este caso.
 *  - *pos* (int): Número de la fila de inicio (paginación).
 *  - *total_count* (int): Número total de proveedores disponibles que cumplen los filtros.
 *  - *data* (array): Contiene los proveedores obtenidos, cada uno con los atributos "Id", "Name", "Type", "IsActivate", "IsVerified", "Abbreviated", "Image".
 *
 * @throws no No contiene manejo de excepciones.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* captura parámetros de solicitud para procesar datos relacionados con pedidos. */
$MaxRows = $_REQUEST["count"];
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

$Type = $_REQUEST["Type"];
$Name = $_REQUEST["Name"];

/* obtiene datos de la solicitud y asigna un tipo basado en un valor. */
$Id = $_REQUEST["Id"];
$IsActivate = $_REQUEST["IsActivate"];
$IsVerified = $_REQUEST["IsVerified"];
$Abbreviated = $_REQUEST["Abbreviated"];

switch ($Type) {
    case 1:
        $tipo = "CASINO";
        break;
    case 2:
        $tipo = "LIVECASINO";
        break;
    case 3:
        $tipo = "PAYMENT";
        break;
    case 4:
        $tipo = "PAYOUT";
        break;
    default:
        $tipo = "";
        break;
}


/* inicializa variables si están vacías, asignando valores predeterminados. */
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* establece un límite de filas y agrega reglas de filtro. */
if ($MaxRows == "") {
    $MaxRows = 10000;
}

$rules = [];

if ($Id != "") {
    array_push($rules, array("field" => "proveedor.proveedor_id", "data" => $Id, "op" => "eq"));
}


/* Agrega reglas de filtrado basadas en nombre y tipo si están definidos. */
if ($Name != "") {
    array_push($rules, array("field" => "proveedor.descripcion", "data" => $Name, "op" => "eq"));
}

if ($tipo != "") {
    array_push($rules, array("field" => "proveedor.tipo", "data" => $tipo, "op" => "eq"));
}


/* agrega reglas basadas en condiciones de estado y abreviación del proveedor. */
if ($IsActivate != "" and $IsActivate == "A") {
    array_push($rules, array("field" => "proveedor.estado", "data" => "A", "op" => "eq"));
} else if ($IsActivate == "I") {
    array_push($rules, array("field" => "proveedor.estado", "data" => "I", "op" => "eq"));
}

if ($Abbreviated != "") {
    array_push($rules, array("field" => "proveedor.abreviado", "data" => $Abbreviated, "op" => "eq"));
}


/* crea reglas de filtrado según el estado de verificación. */
if ($IsVerified != "" and $IsVerified == "A") {
    array_push($rules, array("field" => "proveedor.verifica", "data" => "A", "op" => "eq"));
} else if ($IsVerified == "I") {
    array_push($rules, array("field" => "proveedor.verifica", "data" => "I", "op" => "eq"));
}

$filtro = array("rules" => $rules, "groupOp" => "AND");

/* convierte datos a JSON y obtiene proveedores personalizados de una base de datos. */
$json = json_encode($filtro);

$Proveedor = new Proveedor();

$proveedores = $Proveedor->getProveedoresCustom(" proveedor.* ", "proveedor.proveedor_id", "asc", $SkeepRows, $MaxRows, $json, true);
$proveedores = json_decode($proveedores);


/* transforma datos de proveedores en un formato estructurado y los almacena. */
$final = [];

foreach ($proveedores->data as $key => $value) {

    $array = [];

    $array["Id"] = $value->{"proveedor.proveedor_id"};
    $array["Name"] = $value->{"proveedor.descripcion"};

    $array["Type"] = $value->{"proveedor.tipo"};
    $array["IsActivate"] = $value->{"proveedor.estado"};
    $array["IsVerified"] = $value->{"proveedor.verifica"};
    $array["Abbreviated"] = $value->{"proveedor.abreviado"};
    $array["Image"] = $value->{"proveedor.imagen"};


    array_push($final, $array);

}


/* configura una respuesta con información sobre errores y datos adicionales. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

//$response["Data"] = array("Objects" => $final, "Count" => $proveedores->count[0]->{".count"});

$response["pos"] = $SkeepRows;

/* Se cuenta el total de proveedores y se almacena en la respuesta JSON. */
$response["total_count"] = $proveedores->count[0]->{".count"};
$response["data"] = $final;
