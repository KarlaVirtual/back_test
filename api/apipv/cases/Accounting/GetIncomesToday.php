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
 * Accounting/GetIncomesToday
 *
 * Obtener los ingresos del día
 *
 * @param object $params Objeto que contiene los parámetros de la solicitud
 * @param string $params ->CloseBoxId ID de cierre de caja
 * @param int $params ->Id ID del ingreso
 * @param int $params ->MaxRows Número máximo de filas a obtener
 * @param int $params ->OrderedItem Elemento ordenado
 * @param int $params ->SkeepRows Número de filas a omitir
 *
 * @return array
 *     "HasError": boolean Estado de la soliciutd,
 *     "AlertType": string, Tipo de alerta,
 *     "AlertMessage": string Mensaje de alerta,
 *     "ModelErrors": array Errores de modelo,
 *     "Data": array,
 *     "pos": int,
 *     "total_count": int,
 *     "data": array
 *
 * @throws Exception Si ocurre un error al obtener los ingresos
 */


/* Se crea una instancia de UsuarioMandante con la sesión actual y se definen parámetros. */
$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);


$CloseBoxId = $params->CloseBoxId;
$fechaEspecifica = '';
$BetShopId = 0;

/* crea un objeto y obtiene fecha y usuario si se proporciona un ID. */
if ($CloseBoxId != "") {
    $UsuarioCierrecaja = new UsuarioCierrecaja($CloseBoxId);
    $fechaEspecifica = $UsuarioCierrecaja->getFechaCierre();

    $BetShopId = $UsuarioCierrecaja->getUsuarioId();

}


/* Se obtienen parámetros del objeto $params y se asignan a variables. */
$Id = $params->Id;

$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

$MaxRows = $_REQUEST["count"];

/* establece cuántas filas omitir dependiendo de la solicitud del usuario. */
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];


if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* asigna valores por defecto a variables vacías: $OrderedItem y $MaxRows. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000;
}


/* Define reglas basadas en la condición del ID de la casa de apuestas. */
$rules = [];

if ($BetShopId == 0) {
    if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
        array_push($rules, array("field" => "ingreso.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    } else {
        array_push($rules, array("field" => "ingreso.usucajero_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    }

} else {
    /* Añade una regla al array si no se cumple una condición previa. */

    array_push($rules, array("field" => "ingreso.usuario_id", "data" => $BetShopId, "op" => "eq"));

}


/* añade reglas de fecha basada en una variable específica o la fecha actual. */
if ($fechaEspecifica != '') {
    array_push($rules, array("field" => "ingreso.fecha_crea", "data" => date("Y-m-d 00:00:00", strtotime($fechaEspecifica)), "op" => "ge"));
    array_push($rules, array("field" => "ingreso.fecha_crea", "data" => date("Y-m-d 23:59:59", strtotime($fechaEspecifica)), "op" => "le"));

} else {
    array_push($rules, array("field" => "ingreso.fecha_crea", "data" => date("Y-m-d 00:00:00"), "op" => "ge"));

}


/* Se crean reglas y se agrupan en un filtro JSON para validaciones. */
array_push($rules, array("field" => "ingreso.tipo_id", "data" => "0", "op" => "eq"));
array_push($rules, array("field" => "ingreso.productoterc_id", "data" => "0", "op" => "eq"));


$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);


/* Se obtienen ingresos personalizados y se decodifica el resultado en JSON. */
$Ingreso = new Ingreso();

$data = $Ingreso->getIngresosCustom("  concepto.descripcion,proveedor_tercero.descripcion,ingreso.*,producto_tercero.* ", "ingreso.ingreso_id", "asc", $SkeepRows, $MaxRows, $json, true);

$data = json_decode($data);
$final = [];

foreach ($data->data as $key => $value) {


    if ($value->{"producto_tercero.interno"} == 'N' || $value->{"producto_tercero.interno"} == '') {


        /* Se crea un arreglo asociativo con datos de un objeto "ingreso". */
        $array = [];


        $array["Id"] = $value->{"ingreso.ingreso_id"};
        $array["Description"] = $value->{"ingreso.descripcion"};
        $array["Concept"] = $value->{"ingreso.concepto_id"};

        /* asigna valores a un array desde un objeto de datos. */
        $array["Reference"] = $value->{"ingreso.documento"};
        $array["Value"] = $value->{"ingreso.valor"};
        $array["Document"] = $value->{"ingreso.documento"};
        $array["ProvidersThird"] = $value->{"ingreso.proveedorterc_id"};
        $array["State"] = $value->{"ingreso.estado"};


        $array["Concept"] = $value->{"concepto.descripcion"};

        /* Asignación de descripciones de proveedores y productos a un array basado en condiciones. */
        $array["ProvidersThird"] = $value->{"proveedor_tercero.descripcion"};


        if ($value->{"producto_tercero.descripcion"} != '' && $value->{"producto_tercero.tiene_cupo"} == "S") {
            $array["Description"] = $value->{"producto_tercero.descripcion"};
        }


        /* Agrega el contenido de `$array` al final del array `$final`. */
        array_push($final, $array);
    }

}


/* Código PHP que define una respuesta sin errores, con tipo de alerta "éxito" y datos finales. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;

/* almacena datos en un arreglo de respuesta para ser enviados. */
$response["pos"] = $SkeepRows;
$response["total_count"] = $clasificadores->count[0]->{".count"};
$response["data"] = $final;
