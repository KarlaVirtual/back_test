<?php

use Backend\dto\ApiTransaction;
use Backend\dto\Area;
use Backend\dto\BodegaFlujoCaja;
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
 * Financial/GetFlujoCajaResumido
 *
 * Este script obtiene el flujo de caja resumido basado en los parámetros de entrada proporcionados.
 *
 * @param object $params Objeto JSON decodificado que contiene los siguientes valores:
 * @param string $params->dateTo Fecha final en formato "Y-m-d".
 * @param string $params->dateFrom Fecha inicial en formato "Y-m-d".
 * @param int|null $params->PaymentSystemId ID del sistema de pago.
 * @param int|null $params->CashDeskId ID de la caja.
 * @param int|null $params->ClientId ID del cliente.
 * @param float|null $params->AmountFrom Monto mínimo.
 * @param float|null $params->AmountTo Monto máximo.
 * @param int|null $params->CurrencyId ID de la moneda.
 * @param string|null $params->ExternalId ID externo.
 * @param int|null $params->Id ID de la transacción.
 * @param bool $params->IsDetails Indica si se deben obtener detalles.
 * @param int|null $params->OrderedItem Elemento de ordenación.
 * @param string|null $params->ToCreatedDateLocal Fecha final de creación en formato "Y-m-d H:i:s".
 * @param string|null $params->FromCreatedDateLocal Fecha inicial de creación en formato "Y-m-d H:i:s".
 * @param int|null $params->MaxRows Número máximo de filas a devolver.
 * @param int|null $params->SkeepRows Número de filas a omitir.
 * @param int|null $params->Region ID de la región.
 * @param string|null $params->Currency Moneda.
 * @param int|null $params->PlayerId ID del jugador.
 * @param string|null $params->Ip Dirección IP.
 * 
 *
 * @return array $response Respuesta que contiene:
 *  - pos: int Posición inicial de las filas.
 *  - total_count: int|null Número total de registros.
 *  - data: array Datos procesados del flujo de caja.
 *  - CashDeskId: int|null ID de la caja.
 *  - ClientId: int|null ID del cliente.
 *  - AmountFrom: float|null Monto mínimo.
 *  - AmountTo: float|null Monto máximo.
 *  - CurrencyId: int|null ID de la moneda.
 *  - ExternalId: string|null ID externo.
 *  - Id: int|null ID de la transacción.
 *  - IsDetails: bool Indica si se deben obtener detalles.
 *  - OrderedItem: int|null Elemento de ordenación.
 *  - ToCreatedDateLocal: string|null Fecha final de creación en formato "Y-m-d H:i:s".
 *  - FromCreatedDateLocal: string|null Fecha inicial de creación en formato "Y-m-d H:i:s".
 *  - MaxRows: int|null Número máximo de filas a devolver.
 *  - SkeepRows: int|null Número de filas a omitir.
 *  - Region: int|null ID de la región.
 *  - Currency: string|null Moneda.
 *  - PlayerId: int|null ID del jugador.
 *  - Ip: string|null Dirección IP.
 *
 * @return array $response Respuesta que contiene:
 *  - pos: int Posición inicial de las filas.
 *  - total_count: int|null Número total de registros.
 *  - data: array Datos procesados del flujo de caja.
 */


/* obtiene la fecha actual y decodifica parámetros JSON desde la entrada. */
$timeNow = time();
$PuntoVenta = new PuntoVenta();

$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->dateTo;


/* convierte fechas de entrada a un formato específico considerando la zona horaria. */
$ToDateLocal = date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . '' . $timezone . ' hour '));

$FromDateLocal = $params->dateFrom;

$FromDateLocal = date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));


$PaymentSystemId = $params->PaymentSystemId;

/* Variables extraen parámetros de entrada relacionados con un proceso de transacción. */
$CashDeskId = $params->CashDeskId;
$ClientId = $params->ClientId;
$AmountFrom = $params->AmountFrom;
$AmountTo = $params->AmountTo;
$CurrencyId = $params->CurrencyId;
$ExternalId = $params->ExternalId;

/* valida y asigna parámetros de entrada provenientes de solicitudes. */
$Id = $params->Id;
$IsDetails = ($params->IsDetails == true) ? true : false;

$CountrySelect = (is_numeric($_REQUEST["CountrySelect"])) ? $_REQUEST["CountrySelect"] : '';
$BetShopId = (is_numeric($_REQUEST["BetShopId"])) ? $_REQUEST["BetShopId"] : '';
$UserId = (is_numeric($_REQUEST["UserId"])) ? $_REQUEST["UserId"] : '';

/* asigna valores basados en entradas de solicitud, validando y filtrando datos. */
$TypeDetail = ($_REQUEST["TypeDetail"] == "0") ? 0 : 1;
$TypeTotal = ($_REQUEST["Type"] == "0") ? 0 : 1;

$dateCreatedBetShopTo = $_REQUEST["dateCreatedBetShopTo"];
$dateCreatedBetShopFrom = $_REQUEST["dateCreatedBetShopFrom"];

$Region = (is_numeric($_REQUEST["CountrySelect"])) ? $_REQUEST["CountrySelect"] : '';


/* valida y asigna valores de solicitudes HTTP a variables. */
$UserIdAgent = (is_numeric($_REQUEST["UserIdAgent"])) ? $_REQUEST["UserIdAgent"] : '';
$UserIdAgent2 = (is_numeric($_REQUEST["UserIdAgent2"])) ? $_REQUEST["UserIdAgent2"] : '';

$MaxRows = $_REQUEST["count"];
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];


/* Verifica si las variables están vacías y establece una bandera para continuar. */
$WithPaymentGateways = $_REQUEST["WithPaymentGateways"];

$seguir = true;

if ($SkeepRows == "" || $MaxRows == "") {
    $seguir = false;

}


/* Se inicializan variables para calcular y almacenar resultados finales en PHP. */
$final = [];
$totalm = 0;

$seguirHoy = false;

if (in_array($_SESSION["mandante"], array(3, 4, 5, 6, 7, 10, 22, 25)) && $_SESSION["usuario"] == 67561) {

} else {

}


/* Compara fechas y establece la variable $seguirHoy según condiciones específicas. */
if ((date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour ')) == date("Y-m-d", time()))) {
    $seguirHoy = true;

}
if (in_array($_SESSION["mandante"], array(3, 4, 5, 6, 7, 10, 22, 25))) {
    if ((date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]))) == date("Y-m-d", strtotime('-6 hour ')))) {
        $seguirHoy = true;

    }
}


/* Verifica si una fecha ingresada coincide con el 14 de octubre de 2022. */
if ((date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour ')) == date("Y-m-d", strtotime('2022-10-14')))) {
// $seguirHoy=true;

}

if ($seguir && $seguirHoy) {

    /* establece fechas locales basadas en una condición específica de entrada. */
    $conHoras = false;

    $FromDateLocal = date("Y-m-d");
    $ToDateLocal = date("Y-m-d");

    if ((date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour ')) == date("Y-m-d", strtotime('2022-10-14')))) {
        $FromDateLocal = '2022-10-14';
        $ToDateLocal = '2022-10-14';

    }

    /* ajusta fechas según la hora actual para ciertos mandantes específicos. */
    if (in_array($_SESSION["mandante"], array(3, 4, 5, 6, 7, 10, 22, 25))) {


        if (date("H") < 6) {
            $FromDateLocal = date("Y-m-d 06:00:00", strtotime('-1 days'));
            $ToDateLocal = date("Y-m-d 05:59:59");
        } else {
            $FromDateLocal = date("Y-m-d 06:00:00");
            $ToDateLocal = date("Y-m-d 05:59:59", strtotime('+1 days'));

        }
        $conHoras = true;

    }


    /* Define reglas para filtrar fechas en un arreglo vacío, sin agrupación. */
    $rules = [];

//array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$FromDateLocal", "op" => "ge"));
//array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));


    $grouping = "";

    /* Código que genera una consulta SQL según condiciones específicas de detalle y tipo. */
    $select = "";
    if ($IsDetails) {

    } else {
        if ($TypeTotal == 0) {
            $grouping = 0;

        } else {
            $grouping = 1;

        }

        $select = "SUM(usuario_recarga.valor) valoru,SUM(transaccion_producto.valor) valor, ";
//array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "'A',''", "op" => "in"));

    }


    /* inicializa variables si están vacías, asignando valores por defecto. */
    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }


    /* establece un límite de filas y asigna un ID de usuario si se proporciona. */
    if ($MaxRows == "") {
        $MaxRows = 1000000;
    }
    $MaxRows = 1000000;


    if ($_REQUEST["UserId"] != "") {
        $BetShopId = $_REQUEST["UserId"];

    }

    if (($dateCreatedBetShopTo != '' || $dateCreatedBetShopFrom != '') && $_SESSION["win_perfil2"] != "PUNTOVENTA") {


        /* asigna fechas por defecto si están vacías. */
        if ($dateCreatedBetShopTo == '') {
            $dateCreatedBetShopTo = date("Y-m-d 23:59:59", strtotime($dateCreatedBetShopFrom));
        }
        if ($dateCreatedBetShopFrom == '') {
            $dateCreatedBetShopFrom = date("Y-m-d 00:00:00", strtotime($dateCreatedBetShopTo));
        }

        /* Se crea un nuevo usuario y se establece una regla con la fecha de creación. */
        $Usuario = new Usuario();


        $rules = [];

        array_push($rules, array("field" => "usuario.fecha_crea", "data" => date("Y-m-d 00:00:00", strtotime($dateCreatedBetShopFrom)), "op" => "ge"));

        /* Se agregan reglas para filtrar datos de usuarios basadas en condiciones específicas. */
        array_push($rules, array("field" => "usuario.fecha_crea", "data" => date("Y-m-d 23:59:59", strtotime($dateCreatedBetShopTo)), "op" => "le"));
        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => 'PUNTOVENTA', "op" => "eq"));


// Si el usuario esta condicionado por el mandante y no es de Global
        if ($_SESSION['Global'] == "N") {
            array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
        } else {
            /* Condición que agrega reglas para filtrar basadas en la sesión de mandante. */


            if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
            }

        }


        /* Se crea un filtro y se obtienen usuarios personalizados en formato JSON. */
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json2 = json_encode($filtro);


        $usuarios = $Usuario->getUsuariosCustom(" usuario.usuario_id ", "usuario.usuario_id", "asc", 0, 10, $json2, true);

        $usuarios = json_decode($usuarios);

        /* Se crea un arreglo con los IDs de usuarios de una colección específica. */
        $usuariosBetshops = array();

        foreach ($usuarios->data as $item) {

            array_push($usuariosBetshops, $item->{'usuario.usuario_id'});
        }

        /* Convierte un array de usuariosBetshops en una cadena separada por comas. */
        $BetShopId = implode($usuariosBetshops, ',');

    }


    /* obtiene transacciones resumidas según el perfil de usuario en sesión. */
    if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {

        $transacciones = $PuntoVenta->getFlujoCajaResumidoConCajero($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, $_SESSION['usuario']);

    } elseif ($_SESSION["win_perfil2"] == "CAJERO") {

        $transacciones = $PuntoVenta->getFlujoCajaResumidoConCajero($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, "", $_SESSION['usuario']);

    } elseif ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
        /* Verifica el perfil de concesionario y obtiene transacciones resumidas del flujo de caja. */


        $transacciones = $PuntoVenta->getFlujoCajaResumido($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, $_SESSION['usuario'], "", "", "", "", $BetShopId);

    } elseif ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
        /* Se verifica el perfil del usuario y se obtienen transacciones resumidas. */


        $transacciones = $PuntoVenta->getFlujoCajaResumido($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, "", $_SESSION['usuario'], "", "", "", $BetShopId);

    } elseif ($_SESSION["win_perfil2"] == "CONCESIONARIO3") {
        /* Condición que ejecuta una consulta de transacciones para concesionarios específicos. */


        $transacciones = $PuntoVenta->getFlujoCajaResumido($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, "", "", $_SESSION['usuario'], "", "", $BetShopId);

    } else {


        /* asigna un país basado en condiciones específicas y sesión del usuario. */
        $Pais = "";

        if ($CountrySelect != "" && $CountrySelect != "0") {
            $Pais = $CountrySelect;
        }

// Si el usuario esta condicionado por País
        if ($_SESSION['PaisCond'] == "S") {
            $Pais = $_SESSION['pais_id'];
        }


        /* asigna un valor a $Mandante según condiciones de sesión específicas. */
        $Mandante = "";
// Si el usuario esta condicionado por el mandante y no es de Global
        if ($_SESSION['Global'] == "N") {
            $Mandante = strtolower($_SESSION["mandante"]);
        } else {

            if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                $Mandante = $_SESSION["mandanteLista"];
            }

        }


// Inactivamos reportes para el país Colombia
//array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


        /* obtiene transacciones resumidas con o sin horas, dependiendo de la variable. */
        if ($conHoras) {
            $transacciones = $PuntoVenta->getFlujoCajaResumidoConHoras($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, $UserIdAgent, $UserIdAgent2, "", $Pais, $Mandante, $BetShopId);

        } else {
            $transacciones = $PuntoVenta->getFlujoCajaResumido($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, $UserIdAgent, $UserIdAgent2, "", $Pais, $Mandante, $BetShopId);
        }

    }


    /* convierte una cadena JSON en un objeto o array en PHP. */
    $transacciones = json_decode($transacciones);

    foreach ($transacciones->data as $key => $value) {

        /* Define un array y modifica su valor según una condición de sesión. */
        $array = [];
        $array["Punto"] = "PUNTO";

        if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
            $array["Punto"] = $value->{"y.login"};

        } elseif ($_SESSION["win_perfil2"] == "CAJERO") {
            /* Asigna el valor de "y.login" a "Punto" si el perfil es "CAJERO". */

            $array["Punto"] = $value->{"y.login"};

        } else {
            /* Asignación de un valor a un elemento del arreglo si no se cumple una condición. */

            $array["Punto"] = $value->{"y.punto_venta"};

        }


        /* Se inicializa la variable "ValorSalidasNotasRetiroRetail" con un valor de 0. */
        $ValorSalidasNotasRetiroRetail = 0;

        try {


            /* Se crea un filtro con reglas para consultas, usando operadores lógicos y condiciones específicas. */
            $rules = [];
            array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "I", "op" => "eq"));
            array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => "$FromDateLocal ", "op" => "ge"));
            array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => "$ToDateLocal", "op" => "le"));
            array_push($rules, array("field" => "cuenta_cobro.puntoventa_id", "data" => $value->{"y.usuario_id"}, "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");

            /* Convierte datos a JSON y consulta cuentas de cobro por usuario y moneda. */
            $json = json_encode($filtro);


            $CuentaCobro = new CuentaCobro();

            $cuentas = $CuentaCobro->getCuentasCobroPuntoVentaCustom("COUNT(*) count,SUM(cuenta_cobro.valor) valor,usuario.moneda", "cuenta_cobro.cuenta_id", "asc", $SkeepRows, $MaxRows, $json, true, "usuario.moneda");


            /* Se decodifica JSON y se asigna un valor a la variable ValorSalidasNotasRetiroRetail. */
            $cuentas = json_decode($cuentas);

            $valor_convertido = 0;
            $total = 0;

            $ValorSalidasNotasRetiroRetail = floatval($cuentas->data[0])->{'.valor'};


        } catch (Exception $e) {
            /* Captura excepciones en PHP, permitiendo manejar errores sin interrumpir la ejecución del programa. */


        }


        /* Se asignan valores a un arreglo a partir de propiedades de un objeto. */
        $array["ValorSalidasNotasRetiroRetail"] = $ValorSalidasNotasRetiroRetail;

        $array["UserId"] = $value->{"y.usuario_id"};
        $array["UserNameCreator"] = $value->{"y.login"};


        $array["Fecha"] = $value->{"y.fecha_crea"};

        /* construye un array con datos sobre moneda, país, agente y tickets. */
        $array["Moneda"] = $value->{"y.moneda"};
        $array["CountryId"] = $value->{"y.pais_nom"} . ' - ' . $value->{"y.mandante"};
        $array["Partner"] = $value->{"y.mandante"};
        $array["CountryIcon"] = strtolower($value->{"y.pais_iso"});
        $array["Agent"] = $value->{"uu.agente"} . ' - ' . $array["Moneda"];
        $array["CantidadTickets"] = $value->{".cant_tickets"};

        /* Asigna valores de un objeto a un array en PHP. */
        $array["ValorEntradasEfectivo"] = $value->{".valor_entrada_efectivo"};
        $array["ValorEntradasBonoTC"] = $value->{".valor_entrada_bono"};
        $array["ValorEntradasRecargas"] = $value->{".valor_entrada_recarga"};
        $array["ValorEntradasRecargasAgentes"] = $value->{".valor_entrada_recarga_agentes"};
        $array["ValorEntradasTraslados"] = $value->{".valor_entrada_traslado"};
        $array["ValorSalidasEfectivo"] = $value->{".valor_salida_efectivo"};

        /* asigna valores a un arreglo basado en propiedades de un objeto. */
        $array["ValorSalidasTraslados"] = $value->{".valor_salida_traslado"};
        $array["ValorSalidasNotasRetiro"] = $value->{".valor_salida_notaret"};
        $array["Saldo"] = $array["ValorEntradasEfectivo"] + $array["ValorEntradasBonoTC"] + $array["ValorEntradasRecargas"] + $array["ValorEntradasTraslados"] - $array["ValorSalidasEfectivo"] - $array["ValorSalidasTraslados"] - $array["ValorSalidasNotasRetiro"] + $array["ValorEntradasRecargasAgentes"];
        $array["MMoneda"] = $value->{"y.punto_venta"};
        $array["Tax"] = $value->{".impuestos"};


        $array["VoidedPlacedBets"] = $value->{".apuestas_void"};

        /* Se asignan valores a un array, calculando las entradas netas tras anulaciones. */
        $array["RecargasAnuladas"] = $value->{".valor_entrada_recarga_anuladas"};


        $array["VoidedPaidBets"] = $value->{".premios_void"};

        $array["ValorEntradasEfectivoNeto"] = $array["ValorEntradasEfectivo"] - $array["VoidedPlacedBets"];

        /* Calcula valores netos de entradas y salidas de efectivo según condiciones de socios. */
        $array["ValorSalidasEfectivoNeto"] = $array["ValorSalidasEfectivo"] - $array["VoidedPaidBets"];

        if ($array["Partner"] == 1 || $array["Partner"] == 2) {
            $array["ValorEntradasEfectivoNeto"] = $array["ValorEntradasEfectivo"] - $array["VoidedPaidBets"];
            $array["ValorSalidasEfectivoNeto"] = $array["ValorSalidasEfectivo"] - 0;
            $array["ValorSalidasEfectivoNeto"] = $array["ValorSalidasEfectivo"] - $array["VoidedPaidBets"];

        }


        /* Agrega un elemento al arreglo final si se cumplen ciertas condiciones. */
        if ($TypeTotal == 1) {

            $array["Punto"] = $value->{"y.punto_venta"} . $array["Agent"];

        }
        if ($array["UserId"] != '') {
            array_push($final, $array);

        }

    }
}


if ($_SESSION["usuario"] != 449) {
    //$seguir = false;
}
try {
    if ($BetShopId == "" && $UserId == "" && $WithPaymentGateways == '0') {


        /* Se crea un objeto UsuarioRecarga y se decodifica un JSON recibido. */
        $UsuarioRecarga = new UsuarioRecarga();

        $params = file_get_contents('php://input');
        $params = json_decode($params);

        $ToDateLocal = $params->ToCreatedDateLocal;


        /* establece fechas basadas en la entrada del usuario y la zona horaria. */
        if ($_REQUEST["dateTo"] != "") {
            $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour '));
        }


        $FromDateLocal = $params->FromCreatedDateLocal;


        /* Convierte una fecha de entrada a formato local y extrae parámetros del sistema de pago. */
        if ($_REQUEST["dateFrom"] != "") {
            $FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
        }

        $PaymentSystemId = $params->PaymentSystemId;
        $CashDeskId = $params->CashDeskId;

        /* Asignación de parámetros a variables en un script, probablemente para procesamiento de transacciones. */
        $ClientId = $params->ClientId;
        $AmountFrom = $params->AmountFrom;
        $AmountTo = $params->AmountTo;
        $CurrencyId = $params->CurrencyId;
        $ExternalId = $params->ExternalId;
        $Id = $params->Id;

        /* establece una variable y fija otra para obtener siempre detalles. */
        $IsDetails = ($params->IsDetails == true) ? true : false;

//Fijamos para obtener siempre detalles
        $IsDetails = true;

        $FromId = $_REQUEST["FromId"];

        /* recoge datos de una solicitud HTTP, incluyendo ID de jugador y país. */
        $PlayerId = $_REQUEST["PlayerId"];
        $Ip = $_REQUEST["Ip"];
        $IsDetails = 1;
        $CountrySelect = (is_numeric($_REQUEST["CountrySelect"])) ? $_REQUEST["CountrySelect"] : '';


        $MaxRows = $_REQUEST["count"];

        /* inicializa variables y verifica condiciones para continuar el procesamiento. */
        $OrderedItem = $params->OrderedItem;
        $SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];
        $seguir = true;

        if ($MaxRows == "") {
            $seguir = false;
        }


        /* verifica condiciones para establecer la variable $seguir como falsa. */
        if ($SkeepRows == "") {
            $seguir = false;
        }

        if ($_SESSION["win_perfil2"] == "CONCESIONARIO" || $_SESSION["win_perfil2"] == "CONCESIONARIO2" || $_SESSION["win_perfil2"] == "CONCESIONARIO3" || $_SESSION["win_perfil2"] == "PUNTOVENTA" || $_SESSION["win_perfil2"] == "CAJERO") {
            $seguir = false;
        }


        /* verifica si el usuario tiene un ID específico y establece una variable. */
        if ($_SESSION["usuario"] == "16758") {
            $seguir = false;
        }

        if ($seguir) {


            /* Aplica una zona horaria a las fechas si el 'mandante' está en un conjunto específico. */
            $rules = [];

            if (in_array($_SESSION['mandante'], array(3, 4, 5, 6, 7, 10, 22, 25))) {
                $timezone = '+ 6 ';
                $FromDateLocal = date("Y-m-d H:i:s", strtotime(str_replace(" - ", " ", $FromDateLocal) . $timezone . ' hour '));
                $ToDateLocal = date("Y-m-d H:i:s", strtotime(str_replace(" - ", " ", $ToDateLocal) . $timezone . ' hour '));

            }


            /* modifica reglas según el usuario y la fecha proporcionada en la sesión. */
            if ($_SESSION["usuario"] == 4089418) {
                array_push($rules, array("field" => "producto.proveedor_id", "data" => "74,10,214", "op" => "ni"));

            }
            if ($FromDateLocal != "") {
//$FromDateLocal = date("Y-m-d H:00:00", strtotime(time() . $timezone . ' hour '));
                array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$FromDateLocal", "op" => "ge"));
//array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime($FromDateLocal. ' '. 'America/Bogota'), "op" => "ge"));
            }


            /* agrega reglas de filtrado basadas en fechas y sistema de pago. */
            if ($ToDateLocal != "") {
//$ToDateLocal = date("Y-m-d H:00:00", strtotime(time() . ' +1 day' . $timezone . ' hour '));
                array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
//array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime($ToDateLocal. ' '. 'America/Bogota'), "op" => "le"));
            }


            if ($PaymentSystemId != "") {
                array_push($rules, array("field" => "producto.producto_id", "data" => "$PaymentSystemId", "op" => "eq"));
            }


            /* Agrega reglas de filtrado según si hay un ID de caja o cliente. */
            if ($CashDeskId != "") {
                array_push($rules, array("field" => "punto_venta.puntoventa_id", "data" => "$CashDeskId", "op" => "eq"));
            }
            if ($ClientId != "") {
                array_push($rules, array("field" => "usuario_recarga.usuario_id", "data" => "$ClientId", "op" => "eq"));
            }


            /* Agrega reglas de filtrado basadas en los valores de cantidad. */
            if ($AmountFrom != "") {
                array_push($rules, array("field" => "usuario_recarga.valor", "data" => "$AmountFrom", "op" => "ge"));
            }
            if ($AmountTo != "") {
                array_push($rules, array("field" => "usuario_recarga.valor", "data" => "$AmountTo", "op" => "le"));
            }


            /* Agrega reglas al arreglo si CurrencyId y ExternalId no están vacíos. */
            if ($CurrencyId != "") {
                array_push($rules, array("field" => "usuario.moneda", "data" => "$CurrencyId", "op" => "eq"));
            }
            if ($ExternalId != "") {
                array_push($rules, array("field" => "transaccion_producto.externo_id", "data" => "$ExternalId", "op" => "eq"));
            }

            /* Agrega reglas de filtro basadas en ID y país si están definidos. */
            if ($Id != "") {
                array_push($rules, array("field" => "transaccion_producto.externo_id", "data" => "$Id", "op" => "eq"));
            }
            if ($CountrySelect != '') {
                array_push($rules, array("field" => "usuario.pais_id", "data" => $CountrySelect, "op" => "eq"));
            }


            /* agrega reglas según el perfil de usuario en sesión. */
            if ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
                array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION['usuario'], "op" => "eq"));

            }

            if ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
                array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION['usuario'], "op" => "eq"));

            }


            /* Condiciona reglas según el perfil y país del usuario almacenado en sesión. */
            if ($_SESSION["win_perfil2"] == "CONCESIONARIO3") {
                array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $_SESSION['usuario'], "op" => "eq"));

            }

// Si el usuario esta condicionado por País
            if ($_SESSION['PaisCond'] == "S") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
            }

// Si el usuario esta condicionado por el mandante y no es de Global

            /* agrega reglas basadas en la sesión del usuario y su mandante. */
            if ($_SESSION['Global'] == "N") {
                array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
            } else {

                if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                    array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                }

            }


// Inactivamos reportes para el país Colombia
//array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


            /* Evalúa el perfil de un usuario y agrega reglas según su ID. */
            if ($FromId != "") {

                $UsuarioPerfil = new UsuarioPerfil($FromId, "");

                if ($UsuarioPerfil->perfilId == "PUNTOVENTA") {
                    array_push($rules, array("field" => "usuario_punto.puntoventa_id", "data" => "$FromId", "op" => "eq"));

                } else {
                    array_push($rules, array("field" => "usuario_punto.usuario_id", "data" => "$FromId", "op" => "eq"));
                }
//array_push($rules, array("field" => "punto_venta.puntoventa_id", "data" => "$FromIdGetBetHistory", "op" => "eq"));
            }


            /* Agrega condiciones a un arreglo de reglas basado en PlayerId e Ip. */
            if ($PlayerId != "") {
                array_push($rules, array("field" => "usuario.usuario_id", "data" => "$PlayerId", "op" => "eq"));
            }

            if ($Ip != "") {
                array_push($rules, array("field" => "usuario_recarga.dir_ip", "data" => "$Ip", "op" => "cn"));

            }


            /* Configura agrupaciones y selecciones SQL basadas en condiciones específicas de detalle. */
            $grouping = "";
            $select = "";
            if ($IsDetails == 1) {
                $MaxRows = 10000;
                $grouping = "usuario.mandante,usuario.pais_id,DATE_FORMAT(usuario_recarga.fecha_crea,'%Y-%m-%d'),proveedor.proveedor_id ";
                $select = "usuario.mandante,pais.*,DATE_FORMAT(usuario_recarga.fecha_crea,'%Y-%m-%d') fecha_crea,SUM(usuario_recarga.valor) valoru,usuario.moneda,SUM(transaccion_producto.valor) valor,producto.descripcion,proveedor.descripcion ";
//array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "'A',''", "op" => "in"));
                array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "A", "op" => "eq"));

            } else {
                /* Selecciona múltiples campos de diversas tablas en una consulta SQL. */

                $select = " pais.*,usuario_punto.login,usuario_punto.nombre,transaccion_producto.*,producto.*,proveedor.*,usuario.moneda,usuario_recarga.* ";

            }

            /* Se añaden reglas de filtro a un array y se inicializa `$SkeepRows`. */
            array_push($rules, array("field" => "usuario_recarga.puntoventa_id", "data" => "0", "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }


            /* Inicializa variables si están vacías, asignando valores predeterminados a $OrderedItem y $MaxRows. */
            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 5;
            }


            /* convierte un filtro a formato JSON y muestra el tiempo transcurrido si hay depuración. */
            $json = json_encode($filtro);


            if ($_ENV['debug']) {
                print_r((time() - $timeNow));
            } else {
                /* obtiene transacciones personalizadas de un usuario, con paginación y agrupamiento. */

                $transacciones2 = $UsuarioRecarga->getUsuarioRecargasCustom($select, "usuario_recarga.recarga_id", "desc", $SkeepRows, $MaxRows, $json, true, $grouping, '', false);

            }

            /* decodifica un JSON y inicializa una variable totalm en cero. */
            $transacciones2 = json_decode($transacciones2);

            $totalm = 0;
            foreach ($transacciones2->data as $key => $value) {

                /* acumula un valor en totalm si IsDetails es igual a 1. */
                $array = [];
                if ($IsDetails == 1) {
                    $totalm = $totalm + $value->{".valoru"};


                } else {
                    /* Suma el valor de la transacción al total acumulado si no se cumple una condición. */

                    $totalm = $totalm + $value->{"transaccion_producto.valor"};

                }


                /* Se inicializa un arreglo con valores de retiro y descripción de proveedor. */
                $array = [];

                $ValorSalidasNotasRetiroRetail = 0;
                $array["ValorSalidasNotasRetiroRetail"] = $ValorSalidasNotasRetiroRetail;

                $array["Punto"] = $value->{"proveedor.descripcion"} . ' - ' . $value->{"usuario.moneda"};


                /* asigna valores a un arreglo a partir de un objeto $value. */
                $array["Fecha"] = $value->{".fecha_crea"};
                $array["MMoneda"] = $value->{"usuario.moneda"};
                $array["Moneda"] = $value->{"usuario.moneda"};
                $array["CountryId"] = $value->{"pais.pais_nom"} . ' - ' . $value->{"usuario.mandante"};
                $array["CountryIcon"] = strtolower($value->{"pais.iso"});
                $array["Agent"] = "Pasarelas de Pago - " . $value->{"usuario.moneda"};


                /* Configura un array según el idioma y establece valores iniciales para tickets y entradas. */
                if (strtolower($_SESSION["idioma"]) == "en") {
                    $array["Agent"] = "Payment gateways - " . $value->{"usuario.moneda"};
                }

                $array["CantidadTickets"] = 0;
                $array["ValorEntradasEfectivo"] = 0;

                /* Código PHP que inicializa y asigna valores a un array. */
                $array["ValorEntradasBonoTC"] = 0;
                $array["ValorEntradasRecargas"] = $value->{".valoru"};

                $array["ValorEntradasTraslados"] = 0;
                $array["ValorSalidasEfectivo"] = 0;
                $array["ValorSalidasTraslados"] = 0;

                /* inicializa un array y lo agrega a un array final. */
                $array["ValorSalidasNotasRetiro"] = 0;
                $array["Saldo"] = $array["ValorEntradasRecargas"];
                $array["Tax"] = 0;
                $array["Partner"] = $value->{"usuario.mandante"};


                array_push($final, $array);
            }


            /* Muestra el tiempo transcurrido si 'debug' está activado y obtiene datos de entrada. */
            if ($_ENV['debug']) {
                print_r((time() - $timeNow));
            }

            $CuentaCobro = new CuentaCobro();

            $params = file_get_contents('php://input');

            /* procesa parámetros JSON y establece fechas locales para un rango específico. */
            $params = json_decode($params);

            $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $ToDateLocal)));
            $FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $FromDateLocal)));
//$Region = $params->Region;
//$CurrencyId = $params->CurrencyId;
//$IsNewRegistered = $params->IsNewRegistered;


            $ToDateLocal = $params->ToCreatedDateLocal;


            /* obtiene una fecha final local a partir de un rango en la solicitud. */
            if ($_REQUEST["dateTo"] != "") {
                $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour '));
            }


            $FromDateLocal = $params->FromCreatedDateLocal;


            /* ajusta fechas según la zona horaria y la sesión del usuario. */
            if ($_REQUEST["dateFrom"] != "") {
                $FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
            }

            if (in_array($_SESSION['mandante'], array(3, 4, 5, 6, 7, 10, 22, 25))) {
                $timezone = '+ 6 ';
                $FromDateLocal = date("Y-m-d H:i:s", strtotime(str_replace(" - ", " ", $FromDateLocal) . $timezone . ' hour '));
                $ToDateLocal = date("Y-m-d H:i:s", strtotime(str_replace(" - ", " ", $ToDateLocal) . $timezone . ' hour '));

            }


            /* asigna parámetros y asegura que SkeepRows tenga valor numérico. */
            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }


            /* Inicializa $OrderedItem y $MaxRows si están vacíos, asignando valores predeterminados. */
            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 100000000;
            }


            /* Se crean reglas para filtrar datos en una consulta, especificando condiciones y operaciones. */
            $rules = [];
//array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "'I'", "op" => "in"));
            array_push($rules, array("field" => "cuenta_cobro.mediopago_id", "data" => "0", "op" => "ne"));
            array_push($rules, array("field" => "cuenta_cobro.puntoventa_id", "data" => "0", "op" => "eq"));
            array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => "$FromDateLocal", "op" => "ge"));
            array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => "$ToDateLocal", "op" => "le"));

            /* añade reglas condicionalmente a un array según variables especificadas. */
            array_push($rules, array("field" => "cuenta_cobro.version", "data" => "1", "op" => "eq"));

            if ($Region != "") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
            }

            if ($Currency != "") {
                array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
            }


            /* agrega reglas según el perfil de usuario en la sesión. */
            if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
                array_push($rules, array("field" => "usuario_punto.puntoventa_id", "data" => $_SESSION["usuario"], "op" => "eq"));
            }

            if ($_SESSION["win_perfil2"] == "CAJERO") {
                array_push($rules, array("field" => "usuario_punto.usuario_id", "data" => $_SESSION["usuario"], "op" => "eq"));
            }


            /* asigna reglas de acceso según el perfil de usuario en sesión. */
            if ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
                array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION["usuario"], "op" => "eq"));
            }

            if ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
                array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION["usuario"], "op" => "eq"));
            }


            /* Condiciona reglas basadas en el perfil y país del usuario en una sesión. */
            if ($_SESSION["win_perfil2"] == "CONCESIONARIO3") {
                array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $_SESSION["usuario"], "op" => "eq"));
            }

// Si el usuario esta condicionado por País
            if ($_SESSION['PaisCond'] == "S") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
            }

// Si el usuario esta condicionado por el mandante y no es de Global

            /* Agrega reglas de filtrado basadas en la sesión del usuario y su mandante. */
            if ($_SESSION['Global'] == "N") {
                array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
            } else {

                if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                    array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                }

            }

// Inactivamos reportes para el país Colombia
//array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


            /* Se filtran y obtienen cuentas de cobro en formato JSON, ordenadas y procesadas. */
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);


            $cuentas = $CuentaCobro->getCuentasCobroCustom("usuario.mandante,usuario.pais_id,pais.pais_nom,pais.iso,COUNT(*) count,SUM(cuenta_cobro.valor) valor,DATE_FORMAT(cuenta_cobro.fecha_pago,'%Y-%m-%d') fecha_crea,producto.descripcion,usuario.moneda", "cuenta_cobro.fecha_pago", "desc", $SkeepRows, $MaxRows, $json, true, "usuario.mandante,usuario.pais_id,transaccion_producto.producto_id,DATE_FORMAT(cuenta_cobro.fecha_pago,'%Y-%m-%d'),usuario.moneda", '', false);

            $cuentas = json_decode($cuentas);


            /* Variables inicializadas para almacenar valores de conversiones y total de retiros. */
            $valor_convertidoretiros = 0;
            $totalretiros = 0;
            foreach ($cuentas->data as $key => $value) {


                /* asigna una descripción por defecto y construye una cadena con datos. */
                $array = [];

                if ($value->{"producto.descripcion"} == "") {
                    $value->{"producto.descripcion"} = "Fisicamente";
                }

                $array["Punto"] = "Cuentas - Giros - " . $value->{"producto.descripcion"} . '-' . $value->{"usuario.moneda"};


                /* Asigna valores a un array según el idioma y propiedades del objeto `$value`. */
                if (strtolower($_SESSION["idioma"]) == "en") {
                    $array["Punto"] = "Bank Accounts - " . $value->{"producto.descripcion"} . '-' . $value->{"usuario.moneda"};
                }
                $array["Fecha"] = $value->{".fecha_crea"};
                $array["MMoneda"] = $value->{"usuario.moneda"};
                $array["Moneda"] = $value->{"usuario.moneda"};

                /* Código para estructurar datos sobre países y usuarios en un arreglo asociativo. */
                $array["CountryId"] = $value->{"pais.pais_nom"} . ' - ' . $value->{"usuario.mandante"};
                $array["CountryIcon"] = strtolower($value->{"pais.iso"});
                $array["Agent"] = "Cuentas Bancarias y Giros - " . $value->{"usuario.moneda"};
                $array["CantidadTickets"] = 0;
                $array["ValorEntradasEfectivo"] = 0;
                $array["ValorEntradasBonoTC"] = 0;

                /* Se inicializan variables de un arreglo y un valor para gestionar entradas y salidas. */
                $array["ValorEntradasRecargas"] = 0;

                $array["ValorEntradasTraslados"] = 0;
                $array["ValorSalidasEfectivo"] = 0;
                $array["ValorSalidasTraslados"] = 0;

                $ValorSalidasNotasRetiroRetail = 0;

                /* Asignación de valores a un arreglo y cálculo de saldo y datos relacionados. */
                $array["ValorSalidasNotasRetiroRetail"] = $ValorSalidasNotasRetiroRetail;

                $array["ValorSalidasNotasRetiro"] = $value->{".valor"};
                $array["Saldo"] = $array["ValorEntradasEfectivo"] + $array["ValorEntradasBonoTC"] + $array["ValorEntradasRecargas"] + $array["ValorEntradasTraslados"] - $array["ValorSalidasEfectivo"] - $array["ValorSalidasTraslados"] - $array["ValorSalidasNotasRetiro"];
                $array["Tax"] = 0;
                $array["Partner"] = $value->{"usuario.mandante"};


                /* Añade el contenido de `$array` al final de `$final`. */
                array_push($final, $array);


            }


            /* crea una instancia de CuentaCobro y procesa datos JSON desde la entrada. */
            $CuentaCobro = new CuentaCobro();

            $params = file_get_contents('php://input');
            $params = json_decode($params);

            $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $ToDateLocal)));

            /* transforma una fecha en formato específico y asigna un valor de parámetros. */
            $FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $FromDateLocal)));
//$Region = $params->Region;
//$CurrencyId = $params->CurrencyId;
//$IsNewRegistered = $params->IsNewRegistered;


            $ToDateLocal = $params->ToCreatedDateLocal;


            /* convierte una fecha de entrada en un formato específico y la ajusta. */
            if ($_REQUEST["dateTo"] != "") {
                $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour '));
            }


            $FromDateLocal = $params->FromCreatedDateLocal;


            /* ajusta fechas según una zona horaria específica para ciertos usuarios. */
            if ($_REQUEST["dateFrom"] != "") {
                $FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
            }

            if (in_array($_SESSION['mandante'], array(3, 4, 5, 6, 7, 10, 22, 25))) {
                $timezone = '+ 6 ';
                $FromDateLocal = date("Y-m-d H:i:s", strtotime(str_replace(" - ", " ", $FromDateLocal) . $timezone . ' hour '));
                $ToDateLocal = date("Y-m-d H:i:s", strtotime(str_replace(" - ", " ", $ToDateLocal) . $timezone . ' hour '));

            }


            /* asigna valores y maneja la omisión de filas en un arreglo. */
            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }


            /* Asigna valores por defecto a $OrderedItem y $MaxRows si están vacíos. */
            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 1000;
            }


            /* Se definen reglas de validación para filtrar datos de cuenta de cobro. */
            $rules = [];
            array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "'D'", "op" => "in"));
            array_push($rules, array("field" => "cuenta_cobro.mediopago_id", "data" => "0", "op" => "ne"));
            array_push($rules, array("field" => "cuenta_cobro.puntoventa_id", "data" => "0", "op" => "eq"));
            array_push($rules, array("field" => "cuenta_cobro.fecha_accion", "data" => "$FromDateLocal", "op" => "ge"));
            array_push($rules, array("field" => "cuenta_cobro.fecha_accion", "data" => "$ToDateLocal", "op" => "le"));
//array_push($rules, array("field" => "DATE_FORMAT(cuenta_cobro.fecha_accion,'%Y-%m-%d')", "data" => "DATE_FORMAT(cuenta_cobro.fecha_pago,'%Y-%m-%d')", "op" => "neF"));

            /* Agrega reglas de filtrado basadas en la región y la moneda a un array. */
            array_push($rules, array("field" => "cuenta_cobro.version", "data" => "1", "op" => "eq"));

            if ($Region != "") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
            }

            if ($Currency != "") {
                array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
            }


            /* configura reglas basadas en el perfil del usuario en sesión. */
            if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
                array_push($rules, array("field" => "usuario_punto.puntoventa_id", "data" => $_SESSION["usuario"], "op" => "eq"));
            }

            if ($_SESSION["win_perfil2"] == "CAJERO") {
                array_push($rules, array("field" => "usuario_punto.usuario_id", "data" => $_SESSION["usuario"], "op" => "eq"));
            }


            /* agrega reglas para concesionarios basadas en el perfil del usuario. */
            if ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
                array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION["usuario"], "op" => "eq"));
            }

            if ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
                array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION["usuario"], "op" => "eq"));
            }


            /* Agrega reglas basadas en el perfil de usuario y condición de país. */
            if ($_SESSION["win_perfil2"] == "CONCESIONARIO3") {
                array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $_SESSION["usuario"], "op" => "eq"));
            }

// Si el usuario esta condicionado por País
            if ($_SESSION['PaisCond'] == "S") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
            }

// Si el usuario esta condicionado por el mandante y no es de Global

            /* gestiona reglas de acceso según la sesión del usuario. */
            if ($_SESSION['Global'] == "N") {
                array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
            } else {

                if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                    array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                }

            }

// Inactivamos reportes para el país Colombia
//array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


            /* Codifica un arreglo en JSON y muestra el tiempo transcurrido si está en modo debug. */
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);


            if ($_ENV['debug']) {
                print_r((time() - $timeNow));
//exit();
            }


            /* Consulta cuentas de cobro y convierte resultados a formato JSON para su procesamiento. */
            $cuentas = $CuentaCobro->getCuentasCobroCustom("usuario.mandante,usuario.pais_id,pais.pais_nom,pais.iso,COUNT(*) count,SUM(cuenta_cobro.valor) valor,DATE_FORMAT(cuenta_cobro.fecha_pago,'%Y-%m-%d') fecha_crea,producto.descripcion,usuario.moneda", "cuenta_cobro.fecha_pago", "desc", $SkeepRows, $MaxRows, $json, true, "usuario.mandante,usuario.pais_id,transaccion_producto.producto_id,DATE_FORMAT(cuenta_cobro.fecha_pago,'%Y-%m-%d'),usuario.moneda", '', false);


            $cuentas = json_decode($cuentas);

            $valor_convertidoretiros = 0;

            /* Se inicializa una variable llamada totalretiros con valor cero. */
            $totalretiros = 0;
            foreach ($cuentas->data as $key => $value) {


                /* asigna una descripción por defecto y construye un arreglo con información. */
                $array = [];

                if ($value->{"producto.descripcion"} == "") {
                    $value->{"producto.descripcion"} = "Fisicamente";
                }

                $array["Punto"] = "Devueltas - Cuentas - Giros - " . $value->{"producto.descripcion"} . '-' . $value->{"usuario.moneda"};


                /* asigna un valor basado en el idioma de la sesión y una descripción. */
                if (strtolower($_SESSION["idioma"]) == "en") {
                    $array["Punto"] = "Return - Bank Accounts - " . $value->{"producto.descripcion"} . '-' . $value->{"usuario.moneda"};
                }


                $ValorSalidasNotasRetiroRetail = 0;

                /* Asignación de valores a un array desde un objeto en PHP. */
                $array["ValorSalidasNotasRetiroRetail"] = $ValorSalidasNotasRetiroRetail;

                $array["Fecha"] = $value->{".fecha_crea"};
                $array["MMoneda"] = $value->{"usuario.moneda"};
                $array["Moneda"] = $value->{"usuario.moneda"};
                $array["CountryId"] = $value->{"pais.pais_nom"} . ' - ' . $value->{"usuario.mandante"};

                /* crea un array con información sobre país, agente y valores financieros. */
                $array["CountryIcon"] = strtolower($value->{"pais.iso"});
                $array["Agent"] = "Cuentas Bancarias y Giros - " . $value->{"usuario.moneda"};
                $array["CantidadTickets"] = 0;
                $array["ValorEntradasEfectivo"] = 0;
                $array["ValorEntradasBonoTC"] = 0;
                $array["ValorEntradasRecargas"] = 0;


                /* Inicializa valores financieros y calcula el saldo y impuestos en un arreglo. */
                $array["ValorEntradasTraslados"] = 0;
                $array["ValorSalidasEfectivo"] = 0;
                $array["ValorSalidasTraslados"] = 0;
                $array["ValorSalidasNotasRetiro"] = -($value->{".valor"});
                $array["Saldo"] = $array["ValorEntradasEfectivo"] + $array["ValorEntradasBonoTC"] + $array["ValorEntradasRecargas"] + $array["ValorEntradasTraslados"] - $array["ValorSalidasEfectivo"] - $array["ValorSalidasTraslados"] - $array["ValorSalidasNotasRetiro"];
                $array["Tax"] = 0;

                /* asigna un valor a un array y lo agrega a otro array. */
                $array["Partner"] = $value->{"usuario.mandante"};


                array_push($final, $array);


            }


        } else {

        }


        if ($seguir) {


            /* crea un objeto CuentaCobro y decodifica parámetros JSON de entrada. */
            $CuentaCobro = new CuentaCobro();

            $params = file_get_contents('php://input');
            $params = json_decode($params);

            $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $ToDateLocal)));

            /* formatea una fecha y asigna otra fecha desde parámetros. */
            $FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $FromDateLocal)));
//$Region = $params->Region;
//$CurrencyId = $params->CurrencyId;
//$IsNewRegistered = $params->IsNewRegistered;


            $ToDateLocal = $params->ToCreatedDateLocal;


            /* procesa una fecha, ajustando su formato y zona horaria para almacenamiento. */
            if ($_REQUEST["dateTo"] != "") {
                $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour '));
            }


            $FromDateLocal = $params->FromCreatedDateLocal;


            /* ajusta fechas en función de la zona horaria y condiciones de sesión. */
            if ($_REQUEST["dateFrom"] != "") {
                $FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
            }

            if (in_array($_SESSION['mandante'], array(3, 4, 5, 6, 7, 10, 22, 25))) {
                $timezone = '+ 6 ';
                $FromDateLocal = date("Y-m-d H:i:s", strtotime(str_replace(" - ", " ", $FromDateLocal) . $timezone . ' hour '));
                $ToDateLocal = date("Y-m-d H:i:s", strtotime(str_replace(" - ", " ", $ToDateLocal) . $timezone . ' hour '));

            }


            /* asigna valores de parámetros y asegura un valor por defecto para $SkeepRows. */
            $MaxRows = $params->MaxRows;
            $OrderedItem = $params->OrderedItem;
            $SkeepRows = $params->SkeepRows;

            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }


            /* asigna valores predeterminados a variables vacías: $OrderedItem y $MaxRows. */
            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 100000000;
            }


            /* Se crean reglas para filtrar datos de "cuenta_cobro" según ciertas condiciones. */
            $rules = [];
//array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "'I'", "op" => "in"));
            array_push($rules, array("field" => "cuenta_cobro.mediopago_id", "data" => "853460", "op" => "eq"));
            array_push($rules, array("field" => "cuenta_cobro.puntoventa_id", "data" => "0", "op" => "eq"));
            array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => "$FromDateLocal", "op" => "ge"));
            array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => "$ToDateLocal", "op" => "le"));


            /* Agrega reglas a un arreglo basadas en valores de región y moneda. */
            if ($Region != "") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
            }

            if ($Currency != "") {
                array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
            }


            /* Condiciona reglas según el perfil del usuario almacenado en la sesión. */
            if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
                array_push($rules, array("field" => "usuario_punto.puntoventa_id", "data" => $_SESSION["usuario"], "op" => "eq"));
            }

            if ($_SESSION["win_perfil2"] == "CAJERO") {
                array_push($rules, array("field" => "usuario_punto.usuario_id", "data" => $_SESSION["usuario"], "op" => "eq"));
            }


            /* Añade reglas a un array según el perfil del usuario en sesión. */
            if ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
                array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION["usuario"], "op" => "eq"));
            }

            if ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
                array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION["usuario"], "op" => "eq"));
            }


            /* Condiciona reglas de acceso según perfil de usuario y país en sesión. */
            if ($_SESSION["win_perfil2"] == "CONCESIONARIO3") {
                array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $_SESSION["usuario"], "op" => "eq"));
            }

// Si el usuario esta condicionado por País
            if ($_SESSION['PaisCond'] == "S") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
            }

// Si el usuario esta condicionado por el mandante y no es de Global

            /* agrega reglas dependiendo del estado de la sesión y mandante. */
            if ($_SESSION['Global'] == "N") {
                array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
            } else {

                if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                    array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                }

            }

// Inactivamos reportes para el país Colombia
//array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


            /* Se crea un filtro JSON y se obtienen cuentas de cobro personalizadas. */
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);


            $cuentas = $CuentaCobro->getCuentasCobroCustom("usuario.mandante,usuario.pais_id,pais.pais_nom,pais.iso,COUNT(*) count,SUM(cuenta_cobro.valor) valor,DATE_FORMAT(cuenta_cobro.fecha_pago,'%Y-%m-%d') fecha_crea,producto.descripcion,usuario.moneda", "cuenta_cobro.fecha_pago", "desc", $SkeepRows, $MaxRows, $json, true, "usuario.mandante,usuario.pais_id,transaccion_producto.producto_id,DATE_FORMAT(cuenta_cobro.fecha_pago,'%Y-%m-%d'),usuario.moneda", '', false);


            $cuentas = json_decode($cuentas);


            /* Inicializa variables para contabilizar retiros y su valor total. */
            $valor_convertidoretiros = 0;
            $totalretiros = 0;
            foreach ($cuentas->data as $key => $value) {


                /* asigna una descripción predeterminada si está vacía y construye un array. */
                $array = [];

                if ($value->{"producto.descripcion"} == "") {
                    $value->{"producto.descripcion"} = "Western Union - Fisicamente";
                }

                $array["Punto"] = "Cuentas - Giros - " . $value->{"producto.descripcion"} . '-' . $value->{"usuario.moneda"};


                /* crea un array con información de cuentas bancarias según el idioma. */
                if (strtolower($_SESSION["idioma"]) == "en") {
                    $array["Punto"] = "Bank Accounts - " . $value->{"producto.descripcion"} . '-' . $value->{"usuario.moneda"};
                }
                $array["Fecha"] = $value->{".fecha_crea"};
                $array["MMoneda"] = $value->{"usuario.moneda"};
                $array["Moneda"] = $value->{"usuario.moneda"};

                /* Código que asigna valores a un array a partir de un objeto de datos. */
                $array["CountryId"] = $value->{"pais.pais_nom"} . ' - ' . $value->{"usuario.mandante"};
                $array["CountryIcon"] = strtolower($value->{"pais.iso"});
                $array["Agent"] = "Cuentas Bancarias y Giros - " . $value->{"usuario.moneda"};
                $array["CantidadTickets"] = 0;
                $array["ValorEntradasEfectivo"] = 0;
                $array["ValorEntradasBonoTC"] = 0;

                /* Inicializa valores de entradas y salidas en un array y una variable. */
                $array["ValorEntradasRecargas"] = 0;

                $array["ValorEntradasTraslados"] = 0;
                $array["ValorSalidasEfectivo"] = 0;
                $array["ValorSalidasTraslados"] = 0;


                $ValorSalidasNotasRetiroRetail = 0;

                /* Se asignan valores a un arreglo y se calcula el saldo y el impuesto. */
                $array["ValorSalidasNotasRetiroRetail"] = $ValorSalidasNotasRetiroRetail;

                $array["ValorSalidasNotasRetiro"] = $value->{".valor"};
                $array["Saldo"] = $array["ValorEntradasEfectivo"] + $array["ValorEntradasBonoTC"] + $array["ValorEntradasRecargas"] + $array["ValorEntradasTraslados"] - $array["ValorSalidasEfectivo"] - $array["ValorSalidasTraslados"] - $array["ValorSalidasNotasRetiro"];
                $array["Tax"] = 0;
                $array["Partner"] = $value->{"usuario.mandante"};


                /* agrega un elemento al final del array $final. */
                array_push($final, $array);


            }


            /* Se crea un objeto CuentaCobro y se procesa una fecha a formato local. */
            $CuentaCobro = new CuentaCobro();

            $params = file_get_contents('php://input');
            $params = json_decode($params);

            $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $ToDateLocal)));

            /* establece fechas locales a partir de un string y parámetros proporcionados. */
            $FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $FromDateLocal)));
//$Region = $params->Region;
//$CurrencyId = $params->CurrencyId;
//$IsNewRegistered = $params->IsNewRegistered;


            $ToDateLocal = $params->ToCreatedDateLocal;


            /* Convierte una fecha de solicitud en formato local y establece una fecha límite. */
            if ($_REQUEST["dateTo"] != "") {
                $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour '));
            }


            $FromDateLocal = $params->FromCreatedDateLocal;


            /* ajusta fechas según el huso horario y la sesión del usuario. */
            if ($_REQUEST["dateFrom"] != "") {
                $FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
            }

            if (in_array($_SESSION['mandante'], array(3, 4, 5, 6, 7, 10, 22, 25))) {
                $timezone = '+ 6 ';
                $FromDateLocal = date("Y-m-d H:i:s", strtotime(str_replace(" - ", " ", $FromDateLocal) . $timezone . ' hour '));
                $ToDateLocal = date("Y-m-d H:i:s", strtotime(str_replace(" - ", " ", $ToDateLocal) . $timezone . ' hour '));

            }


            /* asigna valores de parámetros y maneja filas omitidas. */
            $MaxRows = $params->MaxRows;
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
                $MaxRows = 1000;
            }


            /* Define reglas de filtrado para consultas sobre cuentas por cobrar. */
            $rules = [];
            array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "'D'", "op" => "in"));
            array_push($rules, array("field" => "cuenta_cobro.mediopago_id", "data" => "853460", "op" => "eq"));
            array_push($rules, array("field" => "cuenta_cobro.puntoventa_id", "data" => "0", "op" => "eq"));
            array_push($rules, array("field" => "cuenta_cobro.fecha_accion", "data" => "$FromDateLocal", "op" => "ge"));
            array_push($rules, array("field" => "cuenta_cobro.fecha_accion", "data" => "$ToDateLocal", "op" => "le"));
//array_push($rules, array("field" => "DATE_FORMAT(cuenta_cobro.fecha_accion,'%Y-%m-%d')", "data" => "DATE_FORMAT(cuenta_cobro.fecha_pago,'%Y-%m-%d')", "op" => "neF"));


            /* Agrega reglas de filtro basadas en región y moneda si no están vacías. */
            if ($Region != "") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
            }

            if ($Currency != "") {
                array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
            }


            /* añade reglas de acceso según el perfil del usuario en sesión. */
            if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
                array_push($rules, array("field" => "usuario_punto.puntoventa_id", "data" => $_SESSION["usuario"], "op" => "eq"));
            }

            if ($_SESSION["win_perfil2"] == "CAJERO") {
                array_push($rules, array("field" => "usuario_punto.usuario_id", "data" => $_SESSION["usuario"], "op" => "eq"));
            }


            /* añade reglas según el perfil del usuario en sesión. */
            if ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
                array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION["usuario"], "op" => "eq"));
            }

            if ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
                array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION["usuario"], "op" => "eq"));
            }


            /* verifica la sesión de usuario y añade reglas de acceso según condiciones. */
            if ($_SESSION["win_perfil2"] == "CONCESIONARIO3") {
                array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $_SESSION["usuario"], "op" => "eq"));
            }

// Si el usuario esta condicionado por País
            if ($_SESSION['PaisCond'] == "S") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
            }

// Si el usuario esta condicionado por el mandante y no es de Global

            /* ajusta reglas según el estado de sesión del usuario y mandante. */
            if ($_SESSION['Global'] == "N") {
                array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
            } else {

                if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                    array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                }

            }

// Inactivamos reportes para el país Colombia
//array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


            /* Se construye un filtro JSON y se obtienen cuentas de cobro filtradas y agrupadas. */
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);


            $cuentas = $CuentaCobro->getCuentasCobroCustom("usuario.mandante,usuario.pais_id,pais.pais_nom,pais.iso,COUNT(*) count,SUM(cuenta_cobro.valor) valor,DATE_FORMAT(cuenta_cobro.fecha_pago,'%Y-%m-%d') fecha_crea,producto.descripcion,usuario.moneda", "cuenta_cobro.fecha_pago", "desc", $SkeepRows, $MaxRows, $json, true, "usuario.mandante,usuario.pais_id,transaccion_producto.producto_id,DATE_FORMAT(cuenta_cobro.fecha_pago,'%Y-%m-%d'),usuario.moneda", '', false);


            /* Decodifica un JSON y define variables para retiros en un sistema financiero. */
            $cuentas = json_decode($cuentas);

            $valor_convertidoretiros = 0;
            $totalretiros = 0;
            foreach ($cuentas->data as $key => $value) {


                /* asigna una descripción predeterminada si está vacía y crea un array. */
                $array = [];

                if ($value->{"producto.descripcion"} == "") {
                    $value->{"producto.descripcion"} = "Western Union - Fisicamente";
                }

                $array["Punto"] = "Devueltas - Cuentas - Giros - " . $value->{"producto.descripcion"} . '-' . $value->{"usuario.moneda"};


                /* Condicional que asigna información de banca a un arreglo según idioma y datos. */
                if (strtolower($_SESSION["idioma"]) == "en") {
                    $array["Punto"] = "Return - Bank Accounts - " . $value->{"producto.descripcion"} . '-' . $value->{"usuario.moneda"};
                }

                $array["Fecha"] = $value->{".fecha_crea"};
                $array["MMoneda"] = $value->{"usuario.moneda"};

                /* asigna valores a un arreglo basado en datos de un objeto. */
                $array["Moneda"] = $value->{"usuario.moneda"};
                $array["CountryId"] = $value->{"pais.pais_nom"} . ' - ' . $value->{"usuario.mandante"};
                $array["CountryIcon"] = strtolower($value->{"pais.iso"});
                $array["Agent"] = "Cuentas Bancarias y Giros - " . $value->{"usuario.moneda"};
                $array["CantidadTickets"] = 0;
                $array["ValorEntradasEfectivo"] = 0;

                /* Inicializa valores de entradas y salidas en un array para gestión financiera. */
                $array["ValorEntradasBonoTC"] = 0;
                $array["ValorEntradasRecargas"] = 0;

                $array["ValorEntradasTraslados"] = 0;
                $array["ValorSalidasEfectivo"] = 0;
                $array["ValorSalidasTraslados"] = 0;


                /* Se calcula el saldo restando salidas de las entradas en un sistema financiero. */
                $ValorSalidasNotasRetiroRetail = 0;
                $array["ValorSalidasNotasRetiroRetail"] = $ValorSalidasNotasRetiroRetail;


                $array["ValorSalidasNotasRetiro"] = -($value->{".valor"});
                $array["Saldo"] = $array["ValorEntradasEfectivo"] + $array["ValorEntradasBonoTC"] + $array["ValorEntradasRecargas"] + $array["ValorEntradasTraslados"] - $array["ValorSalidasEfectivo"] - $array["ValorSalidasTraslados"] - $array["ValorSalidasNotasRetiro"];

                /* Se inicializa un array y se agrega a un array final. */
                $array["Tax"] = 0;
                $array["Partner"] = $value->{"usuario.mandante"};


                array_push($final, $array);


            }


            /* verifica si está habilitado el modo debug y permite salir del script. */
            if ($_ENV['debug']) {
// exit();
            }

        } else {

        }
    }


    if ($seguir && !$seguirHoy) {


        /* convierte fechas de entrada en un formato específico aplicando una zona horaria. */
        $FromDateLocal2 = date("Y-m-d", strtotime(time() . $timezone . ' hour '));
        $ToDateLocal2 = date("Y-m-d", strtotime(time() . '' . $timezone . ' hour '));


        if ($_REQUEST["dateFrom"] != "") {


            $FromDateLocal2 = date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));

        }

        /* Convierte una fecha recibida en un formato específico utilizando la zona horaria. */
        if ($_REQUEST["dateTo"] != "") {

            $ToDateLocal2 = date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . '' . $timezone . ' hour '));


        }

        /* Crea reglas para filtrar datos según fechas y un identificador de usuario. */
        $rules = [];

        array_push($rules, array("field" => "bodega_flujo_caja.fecha", "data" => "$FromDateLocal2", "op" => "ge"));
        array_push($rules, array("field" => "bodega_flujo_caja.fecha", "data" => "$ToDateLocal2", "op" => "le"));


        if ($_REQUEST["UserId"] != "") {

            array_push($rules, array("field" => "usuario.puntoventa_id", "data" => $_REQUEST["UserId"], "op" => "eq"));


        }


        /* Condiciona la inclusión de reglas basadas en parámetros de entrada. */
        if ($_REQUEST["BetShopId"] != "") {
            array_push($rules, array("field" => "usuario.puntoventa_id", "data" => $_REQUEST["BetShopId"], "op" => "eq"));
        }


        if ($UserIdAgent != "") {
            array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $UserIdAgent, "op" => "eq"));
        }


        /* Condición que agrega una regla si $UserIdAgent2 no está vacío. */
        if ($UserIdAgent2 != "") {
            array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $UserIdAgent2, "op" => "eq"));
        }


        $grouping = "";

        /* Inicializa una variable `$select` como una cadena vacía en un lenguaje de programación. */
        $select = "";
        if ($TypeTotal == '0') {
            $select = 'bodega_flujo_caja.usuario_id,
       bodega_flujo_caja.fecha,
       bodega_flujo_caja.pais_id,
       bodega_flujo_caja.mandante,
       bodega_flujo_caja.concesionario_id,
       SUM(bodega_flujo_caja.cant_tickets) cant_tickets,
       SUM(bodega_flujo_caja.valor_entrada_efectivo) valor_entrada_efectivo,
       SUM(bodega_flujo_caja.valor_entrada_bono) valor_entrada_bono,
       SUM(bodega_flujo_caja.valor_entrada_recarga) valor_entrada_recarga,
              SUM(bodega_flujo_caja.recargas_anuladas) valor_entrada_recarga_anuladas,
       SUM(bodega_flujo_caja.valor_entrada_recarga_agentes) valor_entrada_recarga_agentes,
       SUM(bodega_flujo_caja.valor_entrada_traslado) valor_entrada_traslado,
       SUM(bodega_flujo_caja.valor_salida_traslado) valor_salida_traslado,
       SUM(bodega_flujo_caja.valor_salida_notaret) valor_salida_notaret,
       SUM(bodega_flujo_caja.valor_entrada) valor_entrada,
       SUM(bodega_flujo_caja.valor_salida) valor_salida,
       SUM(bodega_flujo_caja.valor_salida_efectivo) valor_salida_efectivo,
       SUM(bodega_flujo_caja.premios_pend) premios_pend,
       SUM(bodega_flujo_caja.impuestos) impuestos,
       SUM(bodega_flujo_caja.apuestas_void) apuestas_void,
       SUM(bodega_flujo_caja.premios_void) premios_void,
       
       usuario.usuario_id,
       usuario.login,
       usuario.moneda,
       punto_venta.descripcion,
       
       pais.*,
       
       agente.nombre,
       agente2.nombre,agente.usuario_id,agente2.usuario_id';


            /* Define un agrupamiento para consultas de datos por usuario y fecha en flujo de caja. */
            $grouping = 'bodega_flujo_caja.usuario_id,bodega_flujo_caja.fecha';
        } else {

            $select = 'bodega_flujo_caja.usuario_id,
       bodega_flujo_caja.fecha,
       bodega_flujo_caja.pais_id,
       bodega_flujo_caja.mandante,
       bodega_flujo_caja.concesionario_id,
       SUM(bodega_flujo_caja.cant_tickets) cant_tickets,
       SUM(bodega_flujo_caja.valor_entrada_efectivo) valor_entrada_efectivo,
       SUM(bodega_flujo_caja.valor_entrada_bono) valor_entrada_bono,
       SUM(bodega_flujo_caja.valor_entrada_recarga) valor_entrada_recarga,
       SUM(bodega_flujo_caja.recargas_anuladas) valor_entrada_recarga_anuladas,
       SUM(bodega_flujo_caja.valor_entrada_recarga_agentes) valor_entrada_recarga_agentes,
       SUM(bodega_flujo_caja.valor_entrada_traslado) valor_entrada_traslado,
       SUM(bodega_flujo_caja.valor_salida_traslado) valor_salida_traslado,
       SUM(bodega_flujo_caja.valor_salida_notaret) valor_salida_notaret,
       SUM(bodega_flujo_caja.valor_entrada) valor_entrada,
       SUM(bodega_flujo_caja.valor_salida) valor_salida,
       SUM(bodega_flujo_caja.valor_salida_efectivo) valor_salida_efectivo,
       SUM(bodega_flujo_caja.premios_pend) premios_pend,
       SUM(bodega_flujo_caja.impuestos) impuestos,
       SUM(bodega_flujo_caja.apuestas_void) apuestas_void,
       SUM(bodega_flujo_caja.premios_void) premios_void,
       
       usuario.usuario_id,
       usuario.login,
       usuario.moneda,
       punto_venta.descripcion,
       
       pais.*,
       
       agente.nombre,
       agente2.nombre,agente.usuario_id,agente2.usuario_id
       
       ';


            /* Define criterios de agrupación para una consulta en una base de datos. */
            $grouping = 'bodega_flujo_caja.mandante,concesionario.usupadre_id,bodega_flujo_caja.fecha';

        }


        /* asigna valores predeterminados a variables si están vacías. */
        if ($SkeepRows == "") {
            $SkeepRows = 0;
        }

        if ($OrderedItem == "") {
            $OrderedItem = 1;
        }


        /* Establece un límite de filas y filtra según el perfil del usuario. */
        if ($MaxRows == "") {
            $MaxRows = 1000000;
        }
        $MaxRows = 1000000;

        if ($_SESSION["win_perfil2"] == "CAJERO") {

            array_push($rules, array("field" => "bodega_flujo_caja.usuario_id", "data" => $_SESSION['usuario'], "op" => "eq"));

        } elseif ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
            /* Se agregan reglas de filtro basadas en el perfil de usuario en sesión. */


            array_push($rules, array("field" => "usuario.puntoventa_id", "data" => $_SESSION['usuario'], "op" => "eq"));

        } elseif ($_SESSION["win_perfil2"] == "CAJERO") {
            /* Agrega una regla para el usuario cajero en la sesión actual en PHP. */


            array_push($rules, array("field" => "bodega_flujo_caja.usuario_id", "data" => $_SESSION['usuario'], "op" => "eq"));

        } elseif ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
            /* agrega una regla si el perfil de usuario es "CONCESIONARIO". */


            array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION['usuario'], "op" => "eq"));

        } elseif ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
            /* Agrega una regla basada en la sesión del usuario si cumple una condición. */


            array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION['usuario'], "op" => "eq"));

        } elseif ($_SESSION["win_perfil2"] == "CONCESIONARIO3") {
            /* Condición que agrega una regla para concesionario3 en una sesión de usuario. */


            array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $_SESSION['usuario'], "op" => "eq"));

        }


        /* asigna valores a la variable $Pais según condiciones del usuario. */
        $Pais = "";

        if ($CountrySelect != "" && $CountrySelect != "0") {
            $Pais = $CountrySelect;
        }

// Si el usuario esta condicionado por País
        if ($_SESSION['PaisCond'] == "S") {
            $Pais = $_SESSION['pais_id'];
        }


        /* Asignación de variable según condiciones de sesión del usuario y mandantes. */
        $Mandante = "";
// Si el usuario esta condicionado por el mandante y no es de Global
        if ($_SESSION['Global'] == "N") {
            $Mandante = $_SESSION["mandante"];
        } else {

            if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                $Mandante = $_SESSION["mandanteLista"];
            }

        }


        /* Agrega reglas de filtrado basadas en país y mandante si están definidos. */
        if ($Pais != '') {
            array_push($rules, array("field" => "bodega_flujo_caja.pais_id", "data" => $Pais, "op" => "in"));
        }

        if ($Mandante != '') {
            array_push($rules, array("field" => "bodega_flujo_caja.mandante", "data" => $Mandante, "op" => "in"));
        }


        /* Verifica condiciones de sesión para agregar reglas de perfil de usuario. */
        if ($_SESSION['regionperfil'] != "0" && $_SESSION['regionperfil'] != null) {
            if ($_SESSION["win_perfil"] != "CONCESIONARIO" && $_SESSION["win_perfil"] != "CONCESIONARIO2" && $_SESSION["win_perfil"] != "CONCESIONARIO3" && $_SESSION["win_perfil"] != "PUNTOVENTA") {

                array_push($rules, array("field" => "usuario_perfil.region", "data" => $_SESSION['regionperfil'], "op" => "eq"));
            }
        }


// Inactivamos reportes para el país Colombia
//array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


        /* Configura un filtro con reglas y establece valores por defecto para variables vacías. */
        $filtro = array("rules" => $rules, "groupOp" => "AND");

        if ($SkeepRows == "") {
            $SkeepRows = 0;
        }

        if ($OrderedItem == "") {
            $OrderedItem = 1;
        }


        /* inicializa $MaxRows y convierte $filtro a formato JSON. */
        if ($MaxRows == "") {
            $MaxRows = 5;
        }

        $json = json_encode($filtro);


        $BodegaFlujoCaja = new BodegaFlujoCaja();

        /* obtiene y decodifica transacciones de una base de datos en formato JSON. */
        $transacciones = $BodegaFlujoCaja->getBodegaFlujoCajaCustom($select, "punto_venta.descripcion asc,bodega_flujo_caja.fecha", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);


        $transacciones = json_decode($transacciones);

//$final = [];
        $totalm = 0;
        foreach ($transacciones->data as $key => $value) {

            /* Crea un array y asigna un valor basado en una condición de sesión. */
            $array = [];
            $array["Punto"] = "PUNTO";
            $ValorSalidasNotasRetiroRetail = 0;

            if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
                $array["Punto"] = $value->{"usuario.login"};


            } elseif ($_SESSION["win_perfil2"] == "CAJERO") {
                /* Asigna valores de sesión a un array basándose en el perfil de usuario "CAJERO". */

                $array["Punto"] = $value->{"usuario.login"};
                $array["Punto"] = $value->{"punto_venta.descripcion"};

            } else {

                /* asigna un valor a "Punto" basado en condiciones específicas. */
                if ($TypeTotal == '0') {

                    if ($value->{"bodega_flujo_caja.mandante"} == '2') {
                        $array["Punto"] = $value->{"punto_venta.descripcion"};


                    } else {
                        $array["Punto"] = $value->{"punto_venta.descripcion"} . ' - ' . $value->{"usuario.usuario_id"};

                    }


                } else {
                    /* asigna un valor a "Punto" según el idioma y datos del usuario. */


                    $array["Punto"] = 'Punto Venta ' . $value->{"usuario.moneda"} . $value->{"agente.usuario_id"};


                    if (strtolower($_SESSION["idioma"]) == "en") {
                        $array["Punto"] = 'Betshop ' . $value->{"usuario.moneda"} . $value->{"agente.usuario_id"};
                    }
                }
            }


            /* Se asignan valores a un arreglo en PHP usando claves específicas y datos de un objeto. */
            $array["ValorSalidasNotasRetiroRetail"] = $ValorSalidasNotasRetiroRetail;


            $array["UserId"] = $value->{"usuario.usuario_id"};


            /* Asigna valores a un arreglo según propiedades de un objeto en PHP. */
            $array["UserNameCreator"] = $value->{"usuario.login"};

            $array["Fecha"] = $value->{"bodega_flujo_caja.fecha"};
            $array["Moneda"] = $value->{"usuario.moneda"};
            $array["CountryId"] = $value->{"pais.pais_nom"} . ' - ' . $value->{"bodega_flujo_caja.mandante"};
            $array["Partner"] = $value->{"bodega_flujo_caja.mandante"};

            /* asigna valores a un arreglo, considerando condiciones específicas para el agente. */
            $array["CountryIcon"] = strtolower($value->{"pais.iso"});
            $array["Agent"] = $value->{"agente.nombre"} . ' - ' . $array["Moneda"];
            if ($_SESSION['mandante'] == '2') {
                $array["Agent"] = $value->{"agente.nombre"} . ' - ' . $array["Moneda"];
            }
            $array["CantidadTickets"] = $value->{".cant_tickets"};

            /* Asigna valores de entradas a un arreglo desde un objeto PHP. */
            $array["ValorEntradasEfectivo"] = $value->{".valor_entrada_efectivo"};
            $array["ValorEntradasBonoTC"] = $value->{".valor_entrada_bono"};
            $array["ValorEntradasRecargas"] = $value->{".valor_entrada_recarga"};
            $array["ValorEntradasRecargasAgentes"] = $value->{".valor_entrada_recarga_agentes"};

            $array["ValorEntradasTraslados"] = $value->{".valor_entrada_traslado"};

            /* asigna valores a un arreglo y calcula un saldo total. */
            $array["ValorSalidasEfectivo"] = $value->{".valor_salida_efectivo"};
            $array["ValorSalidasTraslados"] = $value->{".valor_salida_traslado"};
            $array["ValorSalidasNotasRetiro"] = $value->{".valor_salida_notaret"};
            $array["Saldo"] = $array["ValorEntradasEfectivo"] + $array["ValorEntradasBonoTC"] + $array["ValorEntradasRecargas"] + $array["ValorEntradasTraslados"] - $array["ValorSalidasEfectivo"] - $array["ValorSalidasTraslados"] - $array["ValorSalidasNotasRetiro"] + $array["ValorEntradasRecargasAgentes"];
            $array["MMoneda"] = $value->{"usuario.moneda"};
            $array["Tax"] = $value->{".impuestos"};


            /* asigna valores a un array basado en datos específicos. */
            $array["VoidedPlacedBets"] = $value->{".apuestas_void"};
            $array["RecargasAnuladas"] = $value->{".valor_entrada_recarga_anuladas"};

            $array["VoidedPaidBets"] = $value->{".premios_void"};

            $array["ValorEntradasEfectivoNeto"] = $array["ValorEntradasEfectivo"] - $array["VoidedPlacedBets"];

            /* Calcula valores netos de entradas y salidas de efectivo considerando apuestas anuladas. */
            $array["ValorSalidasEfectivoNeto"] = $array["ValorSalidasEfectivo"] - $array["VoidedPaidBets"];

            if ($array["Partner"] == 1 || $array["Partner"] == 2) {
                $array["ValorEntradasEfectivoNeto"] = $array["ValorEntradasEfectivo"] - $array["VoidedPaidBets"];
                $array["ValorSalidasEfectivoNeto"] = $array["ValorSalidasEfectivo"] - 0;
                $array["ValorSalidasEfectivoNeto"] = $array["ValorSalidasEfectivo"] - $array["VoidedPaidBets"];

            }


            /* Agrega el elemento al arreglo final si UserId no está vacío. */
            if ($array["UserId"] != '') {
                array_push($final, $array);

            }
        }


// $response["HasError"] = false;
// $response["AlertType"] = "success";
// $response["AlertMessage"] = "";
// $response["ModelErrors"] = [];

// $response["Data"] = array("Documents" => array("Objects" => $final,"Count" => $transacciones->count[0]->{".count"}),"TotalAmount" => $totalm,);


        /* construye una respuesta JSON y la imprime si está en modo depuración. */
        $response["pos"] = $SkeepRows;
        $response["total_count"] = null;
        $response["data"] = $final;

        if ($_ENV['debug']) {
            print_r($response);
        }
    }


    $ValorSalidasNotasRetiroRetail = 0;

    try {


        /* Código que define reglas de filtrado basadas en estado, fechas y país del usuario. */
        $rules = [];
        array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "I", "op" => "eq"));
        array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => "$FromDateLocal 00:00:00", "op" => "ge"));
        array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => "$ToDateLocal 23:59:59", "op" => "le"));
//array_push($rules, array("field" => "cuenta_cobro.puntoventa_id", "data" => $value->{"usuario.usuario_id"}, "op" => "eq"));


// Si el usuario esta condicionado por País
        if ($_SESSION['PaisCond'] == "S") {
            array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
        }

// Si el usuario esta condicionado por el mandante y no es de Global

        /* Se agregan reglas de filtro basadas en la sesión del usuario actual. */
        if ($_SESSION['Global'] == "N") {
            array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
        } else {

            if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
            }

        }


        /* Agrega condiciones a un arreglo según las variables $Region y $Currency. */
        if ($Region != "") {
            array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
        }

        if ($Currency != "") {
            array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
        }


        /* asigna reglas basadas en el perfil de usuario almacenado en sesión. */
        if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
            array_push($rules, array("field" => "usuario_punto.puntoventa_id", "data" => $_SESSION["usuario"], "op" => "eq"));
        }

        if ($_SESSION["win_perfil2"] == "CAJERO") {
            array_push($rules, array("field" => "usuario_punto.usuario_id", "data" => $_SESSION["usuario"], "op" => "eq"));
        }


        /* Se agregan reglas según el perfil del usuario en sesión. */
        if ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
            array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION["usuario"], "op" => "eq"));
        }

        if ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
            array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION["usuario"], "op" => "eq"));
        }


        /* Condicional para agregar una regla basada en sesión de usuario en PHP. */
        if ($_SESSION["win_perfil2"] == "CONCESIONARIO3") {
            array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $_SESSION["usuario"], "op" => "eq"));
        }

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $filtro = array("rules" => $rules, "groupOp" => "AND");

        /* convierte un filtro a JSON y consulta cuentas de cobro personalizadas. */
        $json = json_encode($filtro);


        $CuentaCobro = new CuentaCobro();

        $cuentas = $CuentaCobro->getCuentasCobroPuntoVentaCustom("concesionario.*,pais.*,usuario_punto.mandante,usuario_punto.moneda,usuario_punto.usuario_id,usuario_punto.login,punto_venta.descripcion,DATE_FORMAT(cuenta_cobro.fecha_crea,'%Y-%m-%d') fecha,COUNT(*) count,SUM(cuenta_cobro.valor) valor,usuario.moneda", "cuenta_cobro.cuenta_id", "asc", $SkeepRows, $MaxRows, $json, true, "cuenta_cobro.puntoventa_id,DATE_FORMAT(cuenta_cobro.fecha_crea,'%Y-%m-%d')");


        /* convierte datos JSON a un objeto y inicializa variables para cálculos. */
        $cuentas = json_decode($cuentas);

        $valor_convertido = 0;
        $total = 0;


        foreach ($cuentas->data as $key => $value) {

            /* Se inicializa un arreglo y se crean objetos de usuario según condiciones. */
            $array = [];
            $array["Punto"] = "PUNTO";
            $ValorSalidasNotasRetiroRetail = 0;

            $UsuarioAgente1 = new Usuario($value->{"concesionario.usupadre_id"});
            if ($value->{"concesionario.usupadre2_id"} != '0' && $value->{"concesionario.usupadre2_id"} != '') {
                $UsuarioAgente2 = new Usuario($value->{"concesionario.usupadre2_id"});
            }


            /* asigna diferentes valores a un array según el perfil de sesión. */
            if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
                $array["Punto"] = $value->{"usuario_punto.login"};


            } elseif ($_SESSION["win_perfil2"] == "CAJERO") {
                $array["Punto"] = $value->{"usuario_punto.login"};
                $array["Punto"] = $value->{"punto_venta.descripcion"};

            } else {

                /* Condición para asignar un valor a "Punto" según el tipo y mandante. */
                if ($TypeTotal == '0') {

                    if ($value->{"bodega_flujo_caja.mandante"} == '2') {
                        $array["Punto"] = $value->{"punto_venta.descripcion"};


                    } else {
                        $array["Punto"] = $value->{"punto_venta.descripcion"} . ' - ' . $value->{"usuario_punto.usuario_id"};

                    }


                } else {
                    /* asigna un valor a "Punto" basado en el idioma y ciertos datos. */


                    $array["Punto"] = 'Punto Venta ' . $value->{"usuario_punto.moneda"} . $UsuarioAgente1->usuarioId;


                    if (strtolower($_SESSION["idioma"]) == "en") {
                        $array["Punto"] = 'Betshop ' . $value->{"usuario_punto.moneda"} . $UsuarioAgente1->usuarioId;
                    }
                }
            }

            /* extrae y almacena valores de un objeto en un array asociativo. */
            $ValorSalidasNotasRetiroRetail = floatval($value->{'.valor'});

            $array["ValorSalidasNotasRetiroRetail"] = $ValorSalidasNotasRetiroRetail;


            $array["UserId"] = $value->{"usuario_punto.usuario_id"};


            /* asigna valores de un objeto a un array asociativo en PHP. */
            $array["UserNameCreator"] = $value->{"usuario_punto.login"};

            $array["Fecha"] = $value->{".fecha"};
            $array["Moneda"] = $value->{"usuario.moneda"};
            $array["CountryId"] = $value->{"pais.pais_nom"} . ' - ' . $value->{"usuario_punto.mandante"};
            $array["Partner"] = $value->{"usuario_punto.mandante"};

            /* asigna valores a un array basado en condiciones y datos de usuario. */
            $array["CountryIcon"] = strtolower($value->{"pais.iso"});
            $array["Agent"] = $UsuarioAgente1->nombre . ' - ' . $array["Moneda"];
            if ($_SESSION['mandante'] == '2') {
                $array["Agent"] = $UsuarioAgente1->nombre . ' - ' . $array["Moneda"];
            }
            $array["CantidadTickets"] = 0;

            /* Inicializa un array con valores de entradas como ceros. */
            $array["ValorEntradasEfectivo"] = 0;
            $array["ValorEntradasBonoTC"] = 0;
            $array["ValorEntradasRecargas"] = 0;
            $array["ValorEntradasRecargasAgentes"] = 0;

            $array["ValorEntradasTraslados"] = 0;

            /* inicializa valores y calcula el saldo y moneda de una transacción. */
            $array["ValorSalidasEfectivo"] = 0;
            $array["ValorSalidasTraslados"] = 0;
            $array["ValorSalidasNotasRetiro"] = 0;
            $array["Saldo"] = $array["ValorEntradasEfectivo"] + $array["ValorEntradasBonoTC"] + $array["ValorEntradasRecargas"] + $array["ValorEntradasTraslados"] - $array["ValorSalidasEfectivo"] - $array["ValorSalidasTraslados"] - $array["ValorSalidasNotasRetiro"] + $array["ValorEntradasRecargasAgentes"];
            $array["MMoneda"] = $value->{"usuario_punto.moneda"};
            $array["Tax"] = 0;


            /* Inicializa y calcula valores relacionados con apuestas y recargas anuladas. */
            $array["VoidedPlacedBets"] = 0;
            $array["RecargasAnuladas"] = 0;

            $array["VoidedPaidBets"] = 0;

            $array["ValorEntradasEfectivoNeto"] = $array["ValorEntradasEfectivo"] - $array["VoidedPlacedBets"];

            /* Calcula valores netos de entradas y salidas de efectivo según condiciones específicas. */
            $array["ValorSalidasEfectivoNeto"] = $array["ValorSalidasEfectivo"] - $array["VoidedPaidBets"];

            if ($array["Partner"] == 1 || $array["Partner"] == 2) {
                $array["ValorEntradasEfectivoNeto"] = $array["ValorEntradasEfectivo"] - $array["VoidedPaidBets"];
                $array["ValorSalidasEfectivoNeto"] = $array["ValorSalidasEfectivo"] - 0;
                $array["ValorSalidasEfectivoNeto"] = $array["ValorSalidasEfectivo"] - $array["VoidedPaidBets"];

            }


            /* Añade el elemento al array final si UserId no está vacío. */
            if ($array["UserId"] != '') {
                array_push($final, $array);

            }
        }

    } catch (Exception $e) {
        /* Captura excepciones en PHP para manejar errores sin interrumpir la ejecución del programa. */


    }


} catch (Exception $e) {

}


$response["pos"] = $SkeepRows;
$response["total_count"] = null;
$response["data"] = $final;
