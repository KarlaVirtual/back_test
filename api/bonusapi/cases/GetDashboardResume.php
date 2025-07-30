<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\CuentaCobro;
use Backend\dto\ItTicketEnc;
use Backend\dto\UsuarioRecarga;

/**
 * Este script genera un resumen de estadísticas para el tablero de control.
 *
 * @param object $params Objeto JSON decodificado que contiene los siguientes parámetros:
 * @param string $params->ToDateLocal Fecha de finalización en formato local.
 * @param string $params->FromDateLocal Fecha de inicio en formato local.
 * @param string $params->Region Región del usuario.
 * @param string $params->Currency Moneda utilizada.
 * @param int $params->MaxRows Número máximo de filas a devolver.
 * @param int $params->OrderedItem Elemento de ordenación.
 * @param int $params->SkeepRows Número de filas a omitir.
 *
 * @return array $response Respuesta estructurada con los siguientes datos:
 *  - HasError (bool): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta (e.g., "success").
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *  - Data (array): Contiene estadísticas de jugadores, apuestas y transacciones.
 */




/* Se crea un objeto y se procesa una fecha en formato local. */
$UsuarioRecarga = new UsuarioRecarga();

$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->ToDateLocal) . ' +1 day'));

/* formatea una fecha y obtiene parámetros como región, moneda y filas máximas. */
$FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->FromDateLocal)));
$Region = $params->Region;
$Currency = $params->Currency;

$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;

/* establece reglas para filtrar fechas en una estructura de datos. */
$SkeepRows = $params->SkeepRows;

$rules = [];

array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));


/* Agrega condiciones a un arreglo de reglas basadas en la región y moneda. */
if ($Region != "") {
    array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
}

if ($Currency != "") {
    array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
}


/* Crea un filtro JSON y establece el valor de SkeepRows si está vacío. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);


if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* Establece valores predeterminados para $OrderedItem y $MaxRows si están vacíos. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 10000000;
}

if ($Region != "") {


    /* Código que obtiene y procesa datos de depósitos de usuarios en forma de objeto JSON. */
    $depositos = $UsuarioRecarga->getUsuarioRecargasCustom(" COUNT( DISTINCT (usuario_recarga.usuario_id) ) count, SUM(usuario_recarga.valor) depositos ", "  usuario_recarga.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true);
    $depositos = json_decode($depositos);

    $NumeroJugadoresDepositos = $depositos->data[0]->{".count"};
    $TotalDepositos = $depositos->data[0]->{".depositos"};

    $rules = [];

    /* Se añaden reglas a un array para filtrar datos de cuentas por cobro. */
    array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "I", "op" => "eq"));
    array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => "$FromDateLocal ", "op" => "ge"));
    array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => "$ToDateLocal", "op" => "le"));

    if ($Region != "") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
    }


    /* Condicional que agrega una regla de moneda a un filtro y lo convierte en JSON. */
    if ($Currency != "") {
        array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
    }

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);


    /* Calcula el total de retiros de cuentas de cobro y lo decodifica en JSON. */
    $CuentaCobro = new CuentaCobro();
    $cuentas = $CuentaCobro->getCuentasCobroCustom("SUM(cuenta_cobro.valor) retiros", "cuenta_cobro.cuenta_id", "asc", $SkeepRows, $MaxRows, $json, true);

    $cuentas = json_decode($cuentas);

    $TotalRetiros = $cuentas->data[0]->{".retiros"};


    /* Se crean reglas para filtrar tickets por estado, fecha y región. */
    $rules = [];
    array_push($rules, array("field" => "it_ticket_enc.estado", "data" => "I", "op" => "eq"));
    array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_cierre,' ',it_ticket_enc.hora_cierre)", "data" => "$FromDateLocal ", "op" => "ge"));
    array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_cierre,' ',it_ticket_enc.hora_cierre)", "data" => "$ToDateLocal", "op" => "le"));

    if ($Region != "") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
    }


    /* Crea un filtro en formato JSON basado en una condición de moneda. */
    if ($Currency != "") {
        array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
    }

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);


    /* obtiene y procesa datos de tickets en formato JSON. */
    $ItTicketEnc = new ItTicketEnc();
    $tickets = $ItTicketEnc->getTicketsCustom(" COUNT( DISTINCT (it_ticket_enc.usuario_id) ) count ,SUM(it_ticket_enc.vlr_apuesta) apuestas, SUM(CASE WHEN it_ticket_enc.premiado = 'S' THEN it_ticket_enc.vlr_premio ELSE 0 END) premios  ", "it_ticket_enc.it_ticket_id", "asc", $SkeepRows, $MaxRows, $json, true);
    $tickets = json_decode($tickets);

    $NumeroJugadoresTickets = $tickets->data[0]->{".count"};
    $ValorTickets = $tickets->data[0]->{".apuestas"};

    /* Asignación de premios y configuración de respuesta sin errores en código PHP. */
    $ValorPremios = $tickets->data[0]->{".premios"};


    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";

    /* organiza y calcula datos estadísticos de jugadores y transacciones. */
    $response["ModelErrors"] = [];

    $response["Data"] = array(
        "TotalPlayersByDeposit" => $NumeroJugadoresDepositos,
        "TotalPlayersByBet" => $NumeroJugadoresTickets,
        "BetPromByPlayer" => ($ValorTickets / $NumeroJugadoresTickets),
        "TotalAmountBets" => $ValorTickets,
        "TotalAmountWin" => $ValorPremios,
        "GGR" => floatval($ValorTickets - $ValorPremios),
        "TotalAmountDeposit" => $TotalDepositos,
        "TotalAmountWithDrawal" => $TotalRetiros,
        "DepositPromByPlayer" => ($TotalDepositos / $NumeroJugadoresDepositos)

    );

} else {


    /* cuenta usuarios únicos y suma valores en una moneda específica, luego decodifica JSON. */
    $depositos = $UsuarioRecarga->getUsuarioRecargasCustom("COUNT(DISTINCT (usuario_recarga.usuario_id)) count,SUM(usuario_recarga.valor) valor,usuario.moneda", "usuario_recarga.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true, "usuario.moneda");

    $depositos = json_decode($depositos);
    setlocale(LC_ALL, 'czech');

    $valor_convertido = 0;

    /* Suma valores convertidos a euros y cuenta total de depósitos en un bucle. */
    $total = 0;
    foreach ($depositos->data as $key => $value) {

        $converted_currency = currencyConverter($value->{"usuario.moneda"}, 'EUR', round($value->{".valor"}, 0));
        $valor_convertido = $valor_convertido + $converted_currency;
        $total = $total + $value->{".count"};

    }


    /* Se configuran reglas para filtrar datos de cuentas de cobro y depósitos. */
    $NumeroJugadoresDepositos = $total;
    $TotalDepositos = $valor_convertido;

    $rules = [];
    array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "I", "op" => "eq"));
    array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => "$FromDateLocal ", "op" => "ge"));

    /* Agrega reglas a un arreglo para filtrar datos según condiciones específicas. */
    array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => "$ToDateLocal", "op" => "le"));

    if ($Region != "") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
    }

    if ($Currency != "") {
        array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
    }


    /* crea un filtro JSON y obtiene cuentas de cobro personalizadas. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);


    $CuentaCobro = new CuentaCobro();

    $cuentas = $CuentaCobro->getCuentasCobroCustom("COUNT(*) count,SUM(cuenta_cobro.valor) valor,usuario.moneda", "cuenta_cobro.cuenta_id", "asc", $SkeepRows, $MaxRows, $json, true, "usuario.moneda");


    /* Convierte monedas a euros y suma valores y conteos de cuentas. */
    $cuentas = json_decode($cuentas);

    $valor_convertido = 0;
    $total = 0;
    foreach ($cuentas->data as $key => $value) {

        $converted_currency = currencyConverter($value->{"usuario.moneda"}, 'EUR', round($value->{".valor"}, 0));
        $valor_convertido = $valor_convertido + $converted_currency;
        $total = $total + $value->{".count"};

    }


    /* Se definen reglas para filtrar datos de tickets según su estado y fecha. */
    $TotalRetiros = $valor_convertido;

    $rules = [];
    array_push($rules, array("field" => "it_ticket_enc.estado", "data" => "I", "op" => "eq"));
    array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_cierre,' ',it_ticket_enc.hora_cierre)", "data" => "$FromDateLocal ", "op" => "ge"));
    array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_cierre,' ',it_ticket_enc.hora_cierre)", "data" => "$ToDateLocal", "op" => "le"));


    /* Agrega reglas de filtro basadas en región y moneda si no están vacías. */
    if ($Region != "") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
    }

    if ($Currency != "") {
        array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
    }


    /* Se crea un filtro JSON y se obtienen tickets con conteos y sumas personalizadas. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);

    $ItTicketEnc = new ItTicketEnc();

    $tickets = $ItTicketEnc->getTicketsCustom("  usuario.moneda,COUNT(DISTINCT (it_ticket_enc.usuario_id) ) count,SUM(it_ticket_enc.vlr_apuesta) apuestas, SUM(CASE WHEN it_ticket_enc.premiado = 'S' THEN it_ticket_enc.vlr_premio ELSE 0 END) premios  ", "it_ticket_enc.it_ticket_id", "asc", $SkeepRows, $MaxRows, $json, true, "usuario.moneda");


    /* Convierte apuestas y premios a euros y calcula totales de un conjunto de tickets. */
    $tickets = json_decode($tickets);

    $valor_convertido_apuestas = 0;
    $valor_convertido_premios = 0;
    $total = 0;
    foreach ($tickets->data as $key => $value) {

        $converted_currency = currencyConverter($value->{"usuario.moneda"}, 'EUR', round($value->{".apuestas"}, 0));
        $valor_convertido_apuestas = $valor_convertido_apuestas + $converted_currency;
        $converted_currency = currencyConverter($value->{"usuario.moneda"}, 'EUR', round($value->{".premios"}, 0));
        $valor_convertido_premios = $valor_convertido_premios + $converted_currency;

        $total = $total + $value->{".count"};

    }


    /* asigna valores a variables y establece un indicador de error. */
    $NumeroJugadoresTickets = $total;
    $ValorTickets = $valor_convertido_apuestas;
    $ValorPremios = $valor_convertido_premios;


    $response["HasError"] = false;

    /* Se construye un arreglo de respuesta con estadísticas de jugadores y apuestas. */
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    $response["Data"] = array(
        "TotalPlayersByDeposit" => $NumeroJugadoresDepositos,
        "TotalPlayersByBet" => $NumeroJugadoresTickets,
        "BetPromByPlayer" => ($ValorTickets / $NumeroJugadoresTickets),
        "TotalAmountBets" => $ValorTickets,
        "TotalAmountWin" => $ValorPremios,
        "GGR" => floatval($ValorTickets - $ValorPremios),
        "TotalAmountDeposit" => $TotalDepositos,
        "TotalAmountWithDrawal" => $TotalRetiros,
        "DepositPromByPlayer" => ($TotalDepositos / $NumeroJugadoresDepositos)

    );


}
