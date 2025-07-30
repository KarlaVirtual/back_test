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
 * Agents/GetInformeGerencial
 *
 * Obtener el informe gerencial
 *
 * @param object $params Objeto que contiene los parámetros necesarios para la operación
 * @param string $params ->dateTo Fecha de finalización en formato específico
 * @param string $params ->dateFrom Fecha de inicio en formato específico
 * @param int $params ->PaymentSystemId ID del sistema de pago
 * @param int $params ->CashDeskId ID de la caja
 * @param int $params ->ClientId ID del cliente
 * @param float $params ->AmountFrom Monto mínimo
 * @param float $params ->AmountTo Monto máximo
 * @param int $params ->CurrencyId ID de la moneda
 * @param string $params ->ExternalId ID externo
 * @param int $params ->Id ID de la transacción
 * @param bool $params ->IsDetails Indica si se deben obtener detalles
 * @param int $params ->OrderedItem Elemento ordenado
 *
 * @return array $response Array con el resultado de la operación
 *  - pos:int Posición de inicio
 *  - total_count:int Conteo total de registros
 *  - data:array Datos obtenidos
 *
 * @throws Exception Si el perfil de usuario no tiene permisos
 */


/* Código que inicializa un objeto y verifica permisos de sesión. */
$PuntoVenta = new PuntoVenta();

$seguir = true;

if ($_SESSION["win_perfil2"] == "PUNTOVENTA" || $_SESSION["win_perfil2"] == "CAJERO") {
    $seguir = false;
}

if ($seguir) {


    /* convierte fechas de un formato específico a un formato localizado. */
    $ToDateLocal = $params->dateTo;

    $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . ' +1 day' . $timezone . ' hour '));

    $FromDateLocal = $params->dateFrom;

    $FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));


    /* Asignación de parámetros relacionados con un sistema de pago y transacciones. */
    $PaymentSystemId = $params->PaymentSystemId;
    $CashDeskId = $params->CashDeskId;
    $ClientId = $params->ClientId;
    $AmountFrom = $params->AmountFrom;
    $AmountTo = $params->AmountTo;
    $CurrencyId = $params->CurrencyId;

    /* asigna valores de parámetros y solicitudes a variables específicas. */
    $ExternalId = $params->ExternalId;
    $Id = $params->Id;
    $IsDetails = ($params->IsDetails == true) ? true : false;

    $FromId = $_REQUEST["FromId"];
    $TypeBet = ($_REQUEST["TypeBet"] == 2) ? 2 : 1;


    /* obtiene parámetros de petición y establece fechas y filas para procesamiento. */
    $MaxRows = $_REQUEST["count"];
    $OrderedItem = $params->OrderedItem;
    $SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

    if ($FromDateLocal == "") {


        $FromDateLocal = date("Y-m-d H:00:00", strtotime(time() . $timezone . ' hour '));

    }

    /* Asigna la fecha local un día adelante si está vacía. */
    if ($ToDateLocal == "") {

        $ToDateLocal = date("Y-m-d H:00:00", strtotime(time() . ' +1 day' . $timezone . ' hour '));


    }


    /* Se construye un arreglo de reglas para validaciones de datos. */
    $rules = [];

    //array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
    //array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));

    if ($PaymentSystemId != "") {
        array_push($rules, array("field" => "producto.producto_id", "data" => "$PaymentSystemId", "op" => "eq"));
    }


    /* Añade reglas de filtrado basadas en identificación de caja o cliente. */
    if ($CashDeskId != "") {
        array_push($rules, array("field" => "punto_venta.puntoventa_id", "data" => "$CashDeskId", "op" => "eq"));
    }
    if ($ClientId != "") {
        array_push($rules, array("field" => "usuario_recarga.usuario_id", "data" => "$ClientId", "op" => "eq"));
    }


    /* Agrega reglas de filtrado según valores específicos de $AmountFrom y $AmountTo. */
    if ($AmountFrom != "") {
        array_push($rules, array("field" => "usuario_recarga.valor", "data" => "$AmountFrom", "op" => "ge"));
    }
    if ($AmountTo != "") {
        array_push($rules, array("field" => "usuario_recarga.valor", "data" => "$AmountTo", "op" => "le"));
    }


    /* Añade reglas a un arreglo si las variables son distintas de vacío. */
    if ($CurrencyId != "") {
        array_push($rules, array("field" => "usuario.moneda", "data" => "$CurrencyId", "op" => "eq"));
    }
    if ($ExternalId != "") {
        array_push($rules, array("field" => "transaccion_producto.externo_id", "data" => "$ExternalId", "op" => "eq"));
    }

    /* añade reglas según un ID y verifica condiciones de país del usuario. */
    if ($Id != "") {
        array_push($rules, array("field" => "transaccion_producto.externo_id", "data" => "$Id", "op" => "eq"));
    }

    // Si el usuario esta condicionado por País
    if ($_SESSION['PaisCond'] == "S") {
        $Pais = $_SESSION['pais_id'];
    }
    // Si el usuario esta condicionado por el mandante y no es de Global

    /* asigna un valor a $Mandante según condiciones de sesión. */
    if ($_SESSION['Global'] == "N") {
        $Mandante = $_SESSION['mandante'];
    } else {
        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            $Mandante = $_SESSION["mandanteLista"];
        }

    }


    /* Asignación de ID de concesionario si el perfil de usuario es "CONCESIONARIO". */
    $ConcesionarioId = "";

    if ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
        $ConcesionarioId = $_SESSION['usuario'];

    }


    /* Código que define agrupamiento y selección de datos dependiendo de una condición. */
    $grouping = "";
    $select = "";
    if ($IsDetails) {

    } else {
        $grouping = " usuario_recarga.puntoventa_id,producto.producto_id ";
        $select = "SUM(usuario_recarga.valor) valoru,SUM(transaccion_producto.valor) valor, ";
        //array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "'A',''", "op" => "in"));

    }


    /* Define un filtro y establece valores por defecto para filas y elementos ordenados. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");

    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }


    /* Si no se especifica, establece el número máximo de filas a 5 y ejecuta consulta. */
    if ($MaxRows == "") {
        $MaxRows = 5;
    }

    $json = json_encode($filtro);


    $transacciones = $PuntoVenta->getInformeGerencialByUser($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, $FromId, '', $TypeBet, $ConcesionarioId, $Pais, $Mandante);


    /* decodifica un JSON y prepara variables para procesar transacciones. */
    $transacciones = json_decode($transacciones);

    $final = [];
    $totalm = 0;
    foreach ($transacciones->data as $key => $value) {

        /* crea un array con datos sobre país, afiliador y usuario. */
        $array = [];

        $array["Pais"] = (new ConfigurationEnvironment())->quitar_tildes($value->{"x.pais_nom"});

        $array["Afiliator"] = "Afiliador" . $value->{"afiliador.usuario_id"};
        $array["User"] = "Usuario" . $value->{"x.usuario_id"};

        /* Asigna valores de un objeto a un array asociativo en PHP. */
        $array["Date"] = $value->{"x.fecha_crea"};
        $array["Moneda"] = $value->{"x.moneda"};
        $array["CantidadTickets"] = $value->{"x.cant_tickets"};
        $array["Stake"] = $value->{"x.valor_apostado"};
        $array["StakePromedio"] = $value->{"x.valor_ticket_prom"};
        $array["Payout"] = $value->{"x.valor_premios"};


        /* Calcula bonos, GGR y porcentaje de GGR y almacena en un array final. */
        $array["Bonos"] = ($value->{"pl2.bonos"} == "") ? 0 : $value->{"pl2.bonos"};
        $array["Ggr"] = $array["Stake"] - $array["Payout"] - $array["Bonos"];
        $array["GgrPorc"] = ($array["Ggr"] / $array["Stake"]) * 100;


        array_push($final, $array);
    }

    // $response["HasError"] = false;
    // $response["AlertType"] = "success";
    // $response["AlertMessage"] = "";
    // $response["ModelErrors"] = [];

    // $response["Data"] = array("Documents" => array("Objects" => $final,"Count" => $transacciones->count[0]->{".count"}),"TotalAmount" => $totalm,);


    /* Se asignan valores a un array de respuesta en formato JSON. */
    $response["pos"] = $SkeepRows;
    $response["total_count"] = $transacciones->count[0]->{".count"};
    $response["data"] = $final;
} else {
    /* inicializa una respuesta vacía ante una condición específica. */

    $response["pos"] = 0;
    $response["total_count"] = 0;
    $response["data"] = array();

}