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
 * BalanceAdjustments/GetBalanceAdjustments
 *
 * Obtener los ajustes manuales de saldo
 *
 * @param object $params Objeto que contiene los parámetros necesarios para obtener los ajustes:
 * @param string $params ->FromDateLocal Fecha de inicio en formato local.
 * @param string $params ->ToDateLocal Fecha de fin en formato local.
 * @param int $params ->ClientId ID del cliente.
 * @param bool $params ->IsDetails Indica si se deben obtener los detalles.
 * @param int $params ->PartnerBonusId ID del bono del socio.
 * @param string $params ->OrderedItem Item ordenado.
 * @param int $params ->SkeepRows Número de filas a omitir.
 * @param int $params ->UserId ID del usuario.
 * @param int $params ->PlayerId ID del jugador.
 * @param int $params ->CountrySelect ID del país seleccionado.
 *
 * @return array Respuesta de la operación:
 *  - bool $HasError Indica si hubo un error.
 *  - string $AlertType Tipo de alerta.
 *  - string $AlertMessage Mensaje de alerta.
 *  - array $ModelErrors Errores del modelo.
 *  - int $pos Posición de los datos.
 *  - int $total_count Conteo total de datos.
 *  - array $data Datos de la operación.
 */


/* asigna parámetros de entrada a variables para su uso posterior. */
$FromDateLocal = $params->FromDateLocal;
$ToDateLocal = $params->ToDateLocal;
$ClientId = $params->ClientId;
$IsDetails = $params->IsDetails;
$PartnerBonusId = $params->PartnerBonusId;


$MaxRows = $_REQUEST["count"];

/* obtiene datos de parámetros HTTP para procesar items ordenados. */
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];
$UserId = $_REQUEST["UserId"];
$PlayerId = $_REQUEST["PlayerId"];
$CountrySelect = $_REQUEST["CountrySelect"];

$seguir = true;


/* verifica condiciones y asigna valores a variables en función de ellas. */
if ($SkeepRows == "") {
    $seguir = false;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* Verifica si $MaxRows está vacío y establece $seguir como falso. */
if ($MaxRows == "") {
    $seguir = false;
}

if ($seguir) {


    /* Convierte una fecha ingresada a formato local, ajustando la hora. */
    $ToDateLocal = $params->dateTo;

    if ($_REQUEST["dateTo"] != "") {
        $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . ' +0 day' . $timezone . ' hour '));

    }


    /* ajusta la fecha de inicio según la zona horaria especificada. */
    $FromDateLocal = $params->dateFrom;

    if ($_REQUEST["dateFrom"] != "") {
        $FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));

    }


    /* maneja fechas locales con condiciones para asignar valores por defecto. */
    if ($FromDateLocal == "") {
        //$FromDateLocal = date("Y-m-d H:00:00", strtotime(time() . $timezone . ' hour '));
    }
    if ($ToDateLocal == "") {
        //$ToDateLocal = date("Y-m-d H:00:00", strtotime(time() . ' +1 day' . $timezone . ' hour '));
    }


    /* Se crean reglas para filtrar datos basados en una fecha específica. */
    $rules = [];

    if ($FromDateLocal != "") {
        array_push($rules, array("field" => "saldo_usuonline_ajuste.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));

        //array_push($rules, array("field" => "usuario_bono.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));

    }


    /* añade una regla al array si $ToDateLocal no está vacío. */
    if ($ToDateLocal != "") {
        array_push($rules, array("field" => "saldo_usuonline_ajuste.fecha_crea", "data" => "$ToDateLocal ", "op" => "le"));

        //array_push($rules, array("field" => "usuario_bono.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));

    }


    /* Agrega reglas de filtro para usuario y jugador si sus IDs no están vacíos. */
    if ($UserId != "") {

        array_push($rules, array("field" => "saldo_usuonline_ajuste.usuario_id", "data" => "$UserId ", "op" => "eq"));

    }


    if ($PlayerId != "") {

        array_push($rules, array("field" => "saldo_usuonline_ajuste.usuario_id", "data" => "$PlayerId ", "op" => "eq"));

    }

    /* Se añaden reglas basadas en el país seleccionado y la sesión del usuario. */
    if ($CountrySelect != "" && is_numeric($CountrySelect)) {
        array_push($rules, array("field" => "usuario.pais_id", "data" => $CountrySelect, "op" => "eq"));
    }


    // Si el usuario esta condicionado por País
    if ($_SESSION['PaisCond'] == "S") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
    }

    // Si el usuario esta condicionado por el mandante y no es de Global

    /* añade reglas basadas en condiciones de sesión relacionadas con "mandante". */
    if ($_SESSION['Global'] == "N") {
        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    } else {

        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
        }

    }

    // Inactivamos reportes para el país Colombia
    //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


    /* Se crea un filtro JSON y se establece la configuración regional en checo. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");

    $json = json_encode($filtro);

    setlocale(LC_ALL, 'czech');


    $select = " saldo_usuonline_ajuste.*,usuario.usuario_id,usuario.nombre,usuario_crea.nombre,clasificador.* ";


    /* obtiene ajustes de saldo y decodifica los datos JSON en un array. */
    $SaldoUsuonlineAjuste = new SaldoUsuonlineAjuste();
    $data = $SaldoUsuonlineAjuste->getSaldoUsuonlineAjustesCustom($select, "saldo_usuonline_ajuste.ajuste_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);

    $data = json_decode($data);

    $final = array();

    /* recorre datos y crea un arreglo con información específica de usuarios. */
    $totalAmount = 0;
    foreach ($data->data as $value) {
        $array = array();

        $array["Id"] = $value->{"saldo_usuonline_ajuste.ajuste_id"};
        $array["UserId"] = $value->{"saldo_usuonline_ajuste.usuario_id"};
        $array["UserName"] = $value->{"usuario.nombre"};
        $array["CreatedLocalDate"] = $value->{"saldo_usuonline_ajuste.fecha_crea"};
        $array["Amount"] = $value->{"saldo_usuonline_ajuste.valor"};
        $array["Description"] = $value->{"saldo_usuonline_ajuste.observ"};
        $array["TypeBalance"] = $value->{"saldo_usuonline_ajuste.tipo_saldo"};
        $array["Type"] = $value->{"clasificador.descripcion"};

        $array["UserCreated"] = $value->{"usuario_crea.nombre"};

        array_push($final, $array);
    }


    /* Código establece una respuesta exitosa y maneja errores en una operación. */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "Operation has completed successfully";
    $response["ModelErrors"] = [];


    $response["pos"] = $SkeepRows;

    /* Se asignan valores de conteo y datos a la respuesta estructurada. */
    $response["total_count"] = $data->count[0]->{".count"};
    $response["data"] = $final;
} else {
    /* configura una respuesta exitosa sin errores ni datos. */

    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "Operation has completed successfully";
    $response["ModelErrors"] = [];


    $response["pos"] = 0;
    $response["total_count"] = 0;
    $response["data"] = array();

}
