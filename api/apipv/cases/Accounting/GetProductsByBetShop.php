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
 * Obtener productos por tienda de apuestas
 *
 * @param object $params Objeto que contiene los parámetros de entrada, incluyendo:
 * @param int $params ->BetShopId: Identificador de la tienda de apuestas.
 * @param string $params ->State: Estado del producto.
 * @param int $params ->MaxRows: Número máximo de filas a obtener.
 * @param int $params ->OrderedItem: Elemento ordenado.
 * @param int $params ->SkeepRows: Número de filas a omitir.
 * @param int $params ->CloseBoxId: Identificador de cierre de caja.
 *
 * @return array Respuesta con los siguientes datos:
 *               - HasError: boolean indicando si hubo un error.
 *               - AlertType: string tipo de alerta (success, error, etc.).
 *               - AlertMessage: string mensaje de alerta.
 *               - ModelErrors: array errores del modelo.
 *               - Data: array colección de productos.
 *               - pos: int posición de inicio de los datos.
 *               - total_count: int número total de registros.
 *               - data: array colección de productos.
 *
 * @throws Exception Si ocurre un error durante la ejecución.
 */


/* Asignación de variables y condición para modificar el identificador de la tienda de apuestas. */
$BetShopId = $params->BetShopId;
$State = $params->State;


if ($_SESSION['usuario2'] == '10119' && false) {
    $BetShopId = '5703';
}


/* Asigna valores de parámetros y solicitudes a variables para control de filas en datos. */
$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

$MaxRows = $_REQUEST["count"];
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];


/* Obtiene la fecha de cierre y usuario asociado al identificador de cierre de caja. */
$CloseBoxId = $params->CloseBoxId;
$fechaEspecifica = '';
if ($CloseBoxId != "") {
    $UsuarioCierrecaja = new UsuarioCierrecaja($CloseBoxId);
    $fechaEspecifica = $UsuarioCierrecaja->getFechaCierre();

    $BetShopId = $UsuarioCierrecaja->getUsuarioId();
}


/* asigna valores predeterminados si las variables están vacías. */
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* Establece un límite de filas y define una regla para el perfil de usuario. */
if ($MaxRows == "") {
    $MaxRows = 100000;
}

$rules = [];
array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));

/* Agrega reglas de validación a un array basado en condiciones específicas. */
array_push($rules, array("field" => "producto_tercero.interno", "data" => "N", "op" => "eq"));

array_push($rules, array("field" => "producto_tercero.tiene_cupo", "data" => "N", "op" => "eq"));

if ($BetShopId != "") {
    $Usuario = new Usuario($BetShopId);
    array_push($rules, array("field" => "usuario.usuario_id", "data" => $Usuario->puntoventaId, "op" => "eq"));
}


/* Genera un filtro en formato JSON para reglas de estado de productos. */
if ($State != "") {
    array_push($rules, array("field" => "productotercero_usuario.estado", "data" => "A", "op" => "eq"));
}

$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);


/* obtiene productos de un usuario y los procesa en formato JSON. */
$ProductoTercero = new ProductoTercero();

$data = $ProductoTercero->getProductoTercerosXUsuarioCustom(" usuario.login,usuario.usuario_id, producto_tercero.*,productotercero_usuario.* ", "producto_tercero.productoterc_id", "asc", $SkeepRows, $MaxRows, $json, true);

$data = json_decode($data);
$final = [];

/* Construye un array de productos a partir de datos recibidos y genera una cadena. */
$products = "#";
foreach ($data->data as $key => $value) {

    $array = [];


    $array["Id"] = $value->{"productotercero_usuario.prodtercusuario_id"};
    $array["BetShop"] = $value->{"usuario.login"};
    $array["Product"] = $value->{"producto_tercero.descripcion"};
    $array["StateSwitch"] = ($value->{"productotercero_usuario.estado"} == "A") ? 1 : 0;
    $array["BetShopId"] = $value->{"usuario.usuario_id"};
    $array["ProductId"] = $value->{"producto_tercero.productoterc_id"};

    array_push($final, $array);

    $products .= "," . $value->{"producto_tercero.productoterc_id"};

}

if ($CloseBoxId != "") {

    /* Se está inicializando un arreglo vacío en PHP llamado $arrayFinal. */
    $arrayFinal = array();
    if ($products != "#") {

        /* establece reglas para filtrar productos por usuario y fechas específicas. */
        $products = str_replace("#,", "", $products);
        $rules = [];

        array_push($rules, array("field" => "ingreso.usuario_id", "data" => $BetShopId, "op" => "eq"));


        if ($fechaEspecifica != '') {
            array_push($rules, array("field" => "ingreso.fecha_crea", "data" => date("Y-m-d 00:00:00", strtotime($fechaEspecifica)), "op" => "ge"));
            array_push($rules, array("field" => "ingreso.fecha_crea", "data" => date("Y-m-d 23:59:59", strtotime($fechaEspecifica)), "op" => "le"));
        }


        /* Se añaden reglas de filtrado y se codifican en JSON para la clase Ingreso. */
        array_push($rules, array("field" => "ingreso.productoterc_id", "data" => $products, "op" => "in"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $Ingreso = new Ingreso();


        /* suma valores de ingresos filtrados por productos y los agrega a un array. */
        $data = $Ingreso->getIngresosCustom("  ingreso.* ", "ingreso.ingreso_id", "asc", 0, 10000, $json, true);

        $data = json_decode($data);

        foreach ($final as $item) {
            $item["Bets"] = 0;

            foreach ($data->data as $key => $value) {

                if ($item["ProductId"] == $value->{"ingreso.productoterc_id"}) {
                    $item["Bets"] = $item["Bets"] + floatval($value->{"ingreso.valor"});
                }

            }

            array_push($arrayFinal, $item);

        }

        /* Código inicializa un arreglo y define reglas para filtrar datos de usuario. */
        $final = $arrayFinal;

        $arrayFinal = array();

        $rules = [];

        array_push($rules, array("field" => "egreso.usuario_id", "data" => $BetShopId, "op" => "eq"));


        /* Se agregan reglas para filtrar por fecha y productos en un array. */
        if ($fechaEspecifica != '') {
            array_push($rules, array("field" => "egreso.fecha_crea", "data" => date("Y-m-d 00:00:00", strtotime($fechaEspecifica)), "op" => "ge"));
            array_push($rules, array("field" => "egreso.fecha_crea", "data" => date("Y-m-d 23:59:59", strtotime($fechaEspecifica)), "op" => "le"));

        }

        array_push($rules, array("field" => "egreso.productoterc_id", "data" => $products, "op" => "in"));


        /* Se genera un filtro JSON y se obtienen egresos personalizados de la base de datos. */
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $Egreso = new Egreso();

        $data = $Egreso->getEgresosCustom("  egreso.* ", "egreso.egreso_id", "asc", 0, 1000, $json, true);


        /* suma valores de productos a partir de datos JSON y los almacena. */
        $data = json_decode($data);

        foreach ($final as $item) {
            $item["Prize"] = 0;

            foreach ($data->data as $key => $value) {

                if ($item["ProductId"] == $value->{"egreso.productoterc_id"}) {
                    $item["Prize"] = $item["Prize"] + floatval($value->{"egreso.valor"});
                }

            }

            array_push($arrayFinal, $item);

        }

        /* Asigna el valor de `$arrayFinal` a la variable `$final`. */
        $final = $arrayFinal;


    }

}


/* crea una respuesta JSON indicando éxito y sin errores en los datos. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;

/* Se crea un array de respuesta con posición, conteo total y datos finales. */
$response["pos"] = $SkeepRows;
$response["total_count"] = oldCount($final);
$response["data"] = $final;
