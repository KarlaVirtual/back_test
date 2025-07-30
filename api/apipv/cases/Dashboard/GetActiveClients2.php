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
 * Dashboard/GetActiveClients2
 *
 * Obtener los usuarios activos Versión 2.
 *
 * @param object $params Objeto JSON con los siguientes atributos:
 * @param string $params->ToDateLocal Fecha final del rango en formato "Y-m-d H:i:s".
 * @param string $params->FromDateLocal Fecha inicial del rango en formato "Y-m-d H:i:s".
 * @param string $params->Region Región seleccionada para filtrar los datos.
 * @param string $params->Currency Moneda seleccionada para filtrar los datos.
 * 
 *
 * @return array $response Respuesta con los siguientes datos:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (e.g., "success", "error").
 * - AlertMessage (string): Mensaje de alerta.
 * - Data (int): Número total de usuarios activos.
 *
 * @throws Exception Si ocurre un error en las consultas a la base de datos.
 */


/* formatea fechas y asigna parámetros para un objeto de acceso a datos. */
$ToDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->ToDateLocal) . ' +1 day'));
$FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->FromDateLocal)));
$Region = $params->Region;
$Currency = $params->Currency;

$UsuarioRecargaMySqlDAO = new \Backend\mysql\UsuarioRecargaMySqlDAO();


/* Consulta SQL que filtra usuarios basados en fechas y perfil, con condiciones regionales. */
$sql = "SELECT usuario.usuario_id FROM usuario_deporte_resumen INNER JOIN usuario ON ( usuario_deporte_resumen.usuario_id = usuario.usuario_id ) INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = usuario.usuario_id) WHERE  1=1 ";

$sql = $sql . "AND usuario_deporte_resumen.fecha_crea >='" . $FromDateLocal . "'";
$sql = $sql . "AND usuario_deporte_resumen.fecha_crea <'" . $ToDateLocal . "'";
$sql = $sql . "AND usuario_perfil.perfil_id='USUONLINE'";

if ($Region != "") {
    $sql = $sql . " AND usuario.pais_id='" . $Region . "' ";
}


/* Consulta SQL que cuenta usuarios agrupados, sin filtrar por moneda. */
if ($Currency != "") {
    //$sql = $sql. "AND usuario.moneda='".$Currency."'";
}

$sql = $sql . " GROUP BY usuario.usuario_id";

$depositos = $UsuarioRecargaMySqlDAO->querySQL("SELECT COUNT(*) cantidad_usuarios
FROM (" . $sql . ") a ");


/* Asigna la cantidad de usuarios del primer depósito a la variable $NumeroJugadoresTickets. */
$NumeroJugadoresTickets = $depositos[0][".cantidad_usuarios"];

if (false) {


    /* recibe parámetros JSON y asigna "MaxRows" a una variable. */
    $ItTicketEnc = new ItTicketEnc();

    $params = file_get_contents('php://input');
    $params = json_decode($params);


    $MaxRows = $params->MaxRows;

    /* asigna valores y maneja un parámetro opcional de filas a omitir. */
    $OrderedItem = $params->OrderedItem;
    $SkeepRows = $params->SkeepRows;

    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }


    /* Asigna valores predeterminados a $OrderedItem y $MaxRows si están vacíos. */
    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }

    if ($MaxRows == "") {
        $MaxRows = 1000000000;
    }


    /* Se define un conjunto de reglas para filtrar entradas de tickets en una base de datos. */
    $rules = [];
    array_push($rules, array("field" => "it_ticket_enc.estado", "data" => "I", "op" => "eq"));
    array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_cierre,' ',it_ticket_enc.hora_cierre)", "data" => "$FromDateLocal ", "op" => "ge"));
    array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_cierre,' ',it_ticket_enc.hora_cierre)", "data" => "$ToDateLocal", "op" => "le"));

    if ($Region != "") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
    }


    /* Se crea un filtro en formato JSON para validar la moneda del usuario. */
    if ($Currency != "") {
        array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
    }

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);


    /* obtiene y decodifica el conteo de tickets de usuarios. */
    $ItTicketEnc = new ItTicketEnc();
    $tickets = $ItTicketEnc->getTicketsCustom(" COUNT( DISTINCT (it_ticket_enc.usuario_id) ) count  ", "it_ticket_enc.it_ticket_id", "asc", $SkeepRows, $MaxRows, $json, true);
    $tickets = json_decode($tickets);

    $NumeroJugadoresTickets = $tickets->data[0]->{".count"};
}


/* establece una respuesta exitosa sin errores y contiene datos de jugadores. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $NumeroJugadoresTickets;
