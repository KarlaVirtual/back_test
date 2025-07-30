<?php

use Backend\dto\ItTicketEnc;
use Backend\dto\ItTicketEncInfo1;
use Backend\dto\ConfigurationEnvironment;
/**
 * Report/GetBetHistoryDetail
 * 
 * Obtiene el historial detallado de apuestas para un ticket específico
 *
 * @param string $id ID del ticket a consultar
 * @param int $start Número de registros a omitir (paginación)
 * @param int $count Cantidad de registros a retornar
 * @param string $OrderedItem Campo por el cual ordenar los resultados
 *
 * @return array {
 *   "HasError": boolean,
 *   "AlertType": string,
 *   "AlertMessage": string,
 *   "ModelErrors": array,
 *   "pos": int,
 *   "total_count": int,
 *   "data": array {
 *     "Id": string,
 *     "Amount": float,
 *     "Price": float,
 *     "WinningAmount": float,
 *     "StateName": string,
 *     "CreatedLocal": string,
 *     "ClientLoginIP": string,
 *     "Currency": string,
 *     "UserId": string,
 *     "UserName": string,
 *     "BetShop": string,
 *     "State": string,
 *     "Date": string,
 *     "Tax": float,
 *     "WinningAmountTotal": float,
 *     "Odds": string,
 *     "UserIP": string,
 *     "BetShopPayment": string
 *   }
 * }
 *
 * @throws Exception Si ocurre un error al procesar la consulta
 *
 * @access public
 */


// Inicializa el objeto ItTicketEnc para manejar tickets
$ItTicketEnc = new ItTicketEnc();

// Obtiene los parámetros de la solicitud
$Id = $_REQUEST["id"];
$MaxRows = $_REQUEST["count"];
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];
$seguir = true;

// Establece valores por defecto para los parámetros de paginación y ordenamiento
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000000000;
}

// Valida que exista un ID de ticket
if ($Id == "") {
    $seguir = false;
}

if ($seguir) {
    // Inicializa el array de reglas y el objeto de configuración
    $rules = [];
    $ConfigurationEnvironment = new ConfigurationEnvironment();

    // Agrega regla para filtrar por ID de ticket
    array_push($rules, array("field" => "it_ticket_det.ticket_id", "data" => "$Id", "op" => "eq"));

    // Agrega reglas según el perfil del usuario
    if ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
        array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION["usuario"], "op" => "eq"));
    }

    if ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
        array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION["usuario"], "op" => "eq"));
    }

    if ($_SESSION["win_perfil2"] == "CONCESIONARIO3") {
        array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $_SESSION["usuario"], "op" => "eq"));
    }

    // Agrega reglas adicionales para perfiles específicos
    if ($_SESSION["win_perfil2"] == "CAJERO") {
        array_push($rules, array("field" => "usuario.usuario_id", "data" => $_SESSION["usuario"], "op" => "eq"));
    }

    if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
        array_push($rules, array("field" => "usuario.puntoventa_id", "data" => $_SESSION["usuario"], "op" => "eq"));
    }

    // Prepara y ejecuta la consulta de tickets
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);

    $ItTicketEnc = new ItTicketEnc();
    $tickets = $ItTicketEnc->getTicketDetallesCustom(" it_ticket_det.* ", "it_ticket_det.it_ticketdet_id", "asc", $SkeepRows, $MaxRows, $json, true);
    $tickets = json_decode($tickets);

    // Inicializa array para almacenar resultados procesados
    $final = [];

    // Procesa cada ticket obtenido
    foreach ($tickets->data as $key => $value) {

        $UserPv = 0;
        try {
            $ItTicketEncInfo1 = new ItTicketEncInfo1("", $value->{"it_ticket_det.ticket_id"}, "USUARIORELACIONADO");
            $UserPv = $ItTicketEncInfo1->valor;
        } catch (Exception $e) {
            $UserPv = 0;
        }

        // Construye array con información detallada del ticket
        $array = [];
        $array["Id"] = $value->{"it_ticket_det.it_ticketdet_id"};
        $array["TicketId"] = $value->{"it_ticket_det.ticket_id"};
        $array["Description"] = $value->{"it_ticket_det.apuesta"};
        $array["Market"] = $value->{"it_ticket_det.agrupador"};
        $array["Odds"] = $value->{"it_ticket_det.logro"};
        $array["Option"] = $value->{"it_ticket_det.opcion"};
        $array["Usuario"] = $UserPv;

        array_push($final, $array);
    }

    // Prepara la respuesta exitosa con los datos procesados
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    $response["pos"] = $SkeepRows;
    $response["total_count"] = $tickets->count[0]->{".count"};
    $response["data"] = $final;
} else {
    // Prepara respuesta cuando no hay ID de ticket
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    $response["pos"] = 0;
    $response["total_count"] = 0;
    $response["data"] = array();
}
