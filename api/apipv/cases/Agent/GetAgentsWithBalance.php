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
 * Agent/GetAgentsWithBalance
 *
 * Obtener la red completa del partner con los saldos de los usuarios.
 *
 * @param object $params Objeto que contiene los parámetros de la solicitud, incluyendo MaxRows, OrderedItem y SkeepRows.
 * @param int $params ->MaxRows Número máximo de filas a devolver.
 * @param int $params ->OrderedItem Índice de la columna por la que se ordenarán los resultados.
 * @param int $params ->SkeepRows Número de filas que se omitirán en la consulta.
 *
 * @return array Respuesta con los detalles de la operación, incluyendo:
 *               - HasError: booleano indicando si hubo un error.
 *               - AlertType: tipo de alerta.
 *               - AlertMessage: mensaje de alerta.
 *               - ModelErrors: lista de errores del modelo.
 *               - Data: datos de la respuesta, incluyendo los objetos y el conteo de usuarios.
 */


/* Se crean instancias de usuario y se obtienen parámetros para procesamiento posterior. */
$UsuarioPerfil = new UsuarioPerfil();
$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

$Perfil_id = $_GET["roleId"];
$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;

/* asigna valores predeterminados a variables si están vacías. */
$SkeepRows = $params->SkeepRows;

if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* Establece un valor predeterminado para $MaxRows y inicializa $rules como un array vacío. */
if ($MaxRows == "") {
    $MaxRows = 100000000;
}

$mismenus = "0";

$rules = [];


/* Verifica si el perfil es "CONCESIONARIO" y aplica filtros para obtener usuarios. */
if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
    array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json2 = json_encode($filtro);

    $usuarios = $UsuarioPerfil->getChilds(" punto_venta.creditos_base,usuario.usuario_id,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json2, true);
    $usuarios = json_decode($usuarios);

} elseif ($_SESSION["win_perfil"] == "CONCESIONARIO2") {
    /* Se generan y aplican reglas de filtro para usuarios en una sesión específica. */

    array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
    array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json2 = json_encode($filtro);

    $usuarios = $UsuarioPerfil->getChilds(" punto_venta.creditos_base,usuario.usuario_id,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json2, true);
    $usuarios = json_decode($usuarios);

} elseif ($_SESSION["win_perfil"] == "CONCESIONARIO3") {
    /* aplica reglas de filtrado para obtener información de usuarios. */

    array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));
    array_push($rules, array("field" => "concesionario.prodinterno_id", "data" => "0", "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json2 = json_encode($filtro);

    $usuarios = $UsuarioPerfil->getChilds(" punto_venta.creditos_base,usuario.usuario_id,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json2, true);
    $usuarios = json_decode($usuarios);

} else {

    /* Se generan reglas de filtrado y se obtienen perfiles de usuarios personalizados en JSON. */
    array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "CONCESIONARIO", "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json2 = json_encode($filtro);

    $usuarios = $UsuarioPerfil->getUsuarioPerfilesCustom(" punto_venta.creditos_base,usuario.usuario_id,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json2, true);


    /* decodifica un JSON de usuarios y define reglas de filtros. */
    $usuarios = json_decode($usuarios);

    $rules = [];

    array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));
    //array_push($rules, array("field" => "usuario.puntoventa_id", "data" => "0", "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");

    /* Se obtienen usuarios filtrados, combinando resultados en un array JSON. */
    $json2 = json_encode($filtro);

    $usuarios2 = $UsuarioPerfil->getUsuarioPerfilesCustom(" punto_venta.creditos_base,usuario.usuario_id,usuario.estado,usuario.login,usuario.fecha_ult,usuario.dir_ip,usuario.nombre,usuario_perfil.perfil_id ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json2, true);
    $usuarios2 = json_decode($usuarios2);


    $usuarios->data = array_merge($usuarios->data, $usuarios2->data);

}


/* inicializa un arreglo vacío y establece un saldo de agente en cero. */
$arrayf = [];

$balanceAgent = 0;

foreach ($usuarios->data as $key => $value) {

    /* crea un array con datos de usuario y estado desde un objeto. */
    $array = [];

    $array["UserName"] = $value->{"usuario.login"};
    $array["SystemName"] = 1;
    $array["IsSuspended"] = ($value->{"usuario.estado"} == 'A' ? false : true);
    $array["AgentBalance"] = $value->{"punto_venta.creditos_base"};

    /* Se asignan valores a un array desde un objeto, incluyendo balances y datos de usuario. */
    $array["CashBalance"] = $value->{"punto_venta.creditos_base"};

    $array["FirstName"] = $value->{"usuario.nombre"};
    $array["LastName"] = "T";
    $array["Phone"] = '';
    $array["LastLoginLocalDate"] = $value->{"usuario.fecha_ult"};

    /* asigna valores de un objeto a un array asociativo en PHP. */
    $array["LastLoginIp"] = $value->{"usuario.dir_ip"};


    $array["Id"] = $value->{"usuario.usuario_id"};
    $array["Name"] = $value->{"usuario.nombre"};
    $array["Role"] = $value->{"usuario_perfil.perfil_id"};


    /* Agrega un array a otro y suma un valor específico a balanceAgent. */
    array_push($arrayf, $array);

    $balanceAgent = $balanceAgent + $value->{"punto_venta.creditos_base"};
}


/* Código que configura una respuesta sin errores y con tipo de alerta "éxito". */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] ["Children"] = $arrayf;

/* asigna un array con objetos y un conteo de usuarios a '$response'. */
$response["Data"] = array("Objects" => $arrayf,
    "Count" => $usuarios->count[0]->{".count"});
