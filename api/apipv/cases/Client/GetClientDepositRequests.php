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
 * Obtener las solicitudes de depósito
 *
 * Este script procesa y devuelve las solicitudes de depósito de un cliente, 
 * incluyendo información sobre transacciones y recargas.
 *
 * @param object $params Objeto que contiene los siguientes parámetros:
 * @param string $ToDateLocal Fecha de inicio del rango de consulta.
 * @param string $FromDateLocal Fecha de fin del rango de consulta.
 * @param string $TypeId ID del tipo de producto.
 * @param string $ClientId ID del cliente.
 * @param boolean $IsCashDeskPaid Indica si el pago fue realizado en caja.
 * @param int $MaxRows Número máximo de filas a devolver.
 * @param int $OrderedItem Elemento por el cual ordenar los resultados.
 * @param int $SkeepRows Número de filas a omitir en la consulta.
 * 
 * 
 * @return array $response Arreglo con la estructura:
 *  - HasError: booleano que indica si hubo errores.
 *  - AlertType: tipo de alerta (success, error, etc.).
 *  - AlertMessage: mensaje de alerta.
 *  - Data: arreglo con las solicitudes de depósito, incluyendo:
 *    - PaymentSystemId: ID del sistema de pago.
 *    - RequestTimeLocal: Fecha y hora de la solicitud.
 *    - ClientId: ID del cliente.
 *    - ClientName: Nombre del cliente.
 *    - Amount: Monto de la solicitud.
 *    - Id: ID de la transacción.
 *    - CurrencyId: ID de la moneda.
 *    - State: Estado de la solicitud.
 *    - FromCashDesk: Indica si proviene de caja.
 *    - IsBonus: Indica si es un bono.
 */

/* recibe y decodifica datos JSON desde una entrada HTTP en PHP. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->ToDateLocal;
$FromDateLocal = $params->FromDateLocal;
$TypeId = $params->TypeId;


/* formatea fechas a un formato específico y obtiene parámetros del cliente. */
$ClientId = $params->ClientId;
$IsCashDeskPaid = $params->IsCashDeskPaid;


$ToDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $ToDateLocal) . " 00:00:00"));
$FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $FromDateLocal) . " 23:59:59"));


/* Asignación de parámetros para controlar la cantidad y orden de filas mostradas. */
$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

if (!$IsCashDeskPaid) {


    /* Se establece un conjunto de reglas para validar transacciones de productos. */
    $TransaccionProducto = new TransaccionProducto();


    $rules = [];

    //  array_push($rules, array("field" => "transaccion_producto.estado", "data" => "I", "op" => "eq"));
    //  array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "transaccion_producto.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));

    /* Agrega reglas de filtrado a un array según condiciones específicas. */
    array_push($rules, array("field" => "transaccion_producto.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));

    if ($TypeId != "") {
        array_push($rules, array("field" => "producto.producto_id", "data" => "$TypeId", "op" => "eq"));
    }

    if ($ClientId != "") {
        array_push($rules, array("field" => "transaccion_producto.usuario_id", "data" => "$ClientId", "op" => "eq"));
    }

    // Si el usuario esta condicionado por País

    /* Condiciona reglas según el país y mandante del usuario en sesión. */
    if ($_SESSION['PaisCond'] == "S") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
    }
    // Si el usuario esta condicionado por el mandante y no es de Global
    if ($_SESSION['Global'] == "N") {
        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    } else {
        /* Agrega reglas a un array si "mandanteLista" está definida y no es "-1". */


        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
        }

    }

    // Inactivamos reportes para el país Colombia
    //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


    /* Configura un filtro y define valores predeterminados para saltar filas y ordenar elementos. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");

    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }


    /* establece un límite de filas y obtiene transacciones filtradas en JSON. */
    if ($MaxRows == "") {
        $MaxRows = 1000000000;
    }

    $json = json_encode($filtro);

    $transacciones = $TransaccionProducto->getTransaccionesCustom(" transaccion_producto.*,producto.*,usuario.moneda,usuario.nombre ", "transaccion_producto.transproducto_id", "asc", $SkeepRows, $MaxRows, $json, true);


    /* Se decodifica un JSON en PHP y se inicializa un array vacío. */
    $transacciones = json_decode($transacciones);

    $final = [];

    foreach ($transacciones->data as $key => $value) {


        /* Se crea un array asociativo con datos de transacción y cliente. */
        $array = [];

        $array["PaymentSystemId"] = $value->{"producto.proveedor_id"};
        $array["RequestTimeLocal"] = $value->{"transaccion_producto.fecha_crea"};
        $array["ClientId"] = $value->{"transaccion_producto.usuario_id"};
        $array["ClientName"] = $value->{"usuario.nombre"};

        /* asigna valores a un array a partir de un objeto, incluyendo estados condicionales. */
        $array["Amount"] = $value->{"transaccion_producto.valor"};
        $array["Id"] = $value->{"transaccion_producto.transproducto_id"};;
        $array["CurrencyId"] = $value->{"usuario.moneda"};
        $array["State"] = $value->{"transaccion_producto.estado_producto"};
        $array["FromCashDesk"] = false;
        if ($array["State"] == "A") {
            $array["State"] = 3;
        } elseif ($array["State"] == "E") {
            /* cambia el valor de "State" a 2 si es igual a "E". */

            $array["State"] = 2;

        } elseif ($array["State"] == "R") {
            /* cambia el estado de "R" a -2 en un arreglo. */

            $array["State"] = -2;
        }

        /* Agrega un elemento a $final indicando que "IsBonus" es falso. */
        $array["IsBonus"] = false;


        array_push($final, $array);
    }


} else {


    /* Se definen reglas de filtrado para consultas de recarga de usuarios. */
    $rules = [];

    array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
    array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
    array_push($rules, array("field" => "usuario_recarga.puntoventa_id", "data" => "0", "op" => "ne"));


    if ($ClientId != "") {
        array_push($rules, array("field" => "usuario_recarga.usuario_id", "data" => "$ClientId", "op" => "eq"));
    }


    /* inicializa un filtro y establece valores predeterminados para variables vacías. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");

    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }


    /* Asigna un valor predeterminado a $MaxRows y codifica $filtro en JSON. */
    if ($MaxRows == "") {
        $MaxRows = 1000000000;
    }

    $json = json_encode($filtro);

    $UsuarioRecarga = new UsuarioRecarga();


    /* obtiene y decodifica transacciones de usuario recarga en formato JSON. */
    $transacciones = $UsuarioRecarga->getUsuarioRecargasCustom(" usuario_recarga.*,usuario.moneda,usuario.nombre ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true, "usuario_recarga.recarga_id");

    $transacciones = json_decode($transacciones);

    $final = [];

    foreach ($transacciones->data as $key => $value) {


        /* crea un arreglo asociativo con datos de un usuario y transacción. */
        $array = [];

        $array["PaymentSystemId"] = $value->{"usuario_recarga.puntoventa_id"};
        $array["RequestTimeLocal"] = $value->{"usuario_recarga.fecha_crea"};
        $array["ClientId"] = $value->{"usuario_recarga.usuario_id"};
        $array["ClientName"] = $value->{"usuario.nombre"};

        /* asigna valores a un array según propiedades de un objeto. */
        $array["Amount"] = $value->{"usuario_recarga.valor"};
        $array["Id"] = $value->{"usuario_recarga.recarga_id"};;
        $array["CurrencyId"] = $value->{"usuario.moneda"};
        $array["State"] = $value->{"usuario_recarga.estado"};
        $array["FromCashDesk"] = true;
        if ($array["State"] == "A") {
            $array["State"] = 3;
        } elseif ($array["State"] == "E") {
            /* asigna el valor 2 al estado si es "E". */

            $array["State"] = 2;

        } elseif ($array["State"] == "R") {
            /* cambia el estado de "R" a -2 en un array. */

            $array["State"] = -2;
        }

        /* Se establece una variable y se añade un arreglo a una lista final. */
        $array["IsBonus"] = false;


        array_push($final, $array);
    }


}


/* crea una respuesta con éxito y datos finales sin errores. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;

