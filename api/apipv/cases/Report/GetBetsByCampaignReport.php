<?php

use Backend\dto\TransaccionApi;
use Backend\dto\TransjuegoInfo;
use Backend\dto\UsuarioMandante;
use Backend\dto\ItTicketEncInfo1;

/**
 * Report/GetBetsByCampaignReport
 * 
 * Obtiene el historial de acreditaciones realizadas a una apuesta en las diferentes 
 * campañas ofrecidas por la plataforma (Bonos, Torneos o Sorteos)
 *
 * @param string $UserId ID del usuario a consultar
 * @param string $BetId ID de la apuesta a consultar
 * @param int $CampaignType Tipo de campaña (0: Bonos, 1: Torneos, 2: Sorteos)
 * @param string $CampaignId ID de la campaña específica
 * @param string $dateFrom Fecha inicial del rango a consultar (YYYY-MM-DD)
 * @param string $dateTo Fecha final del rango a consultar (YYYY-MM-DD)
 * @param int $TypeBet Tipo de apuesta (0: Deportivas, 1: Casino/Virtuales)
 * @param int $start Número de registros a omitir (paginación)
 * @param int $count Cantidad de registros a retornar
 *
 * @return array {
 *   "HasError": boolean,
 *   "AlertType": string,
 *   "AlertMessage": string,
 *   "ModelErrors": array,
 *   "pos": int,
 *   "total_count": int,
 *   "data": array {
 *     "BetId": string,
 *     "CampaignId": string,
 *     "CampaignType": int,
 *     "CampaignName": string,
 *     "Amount": float,
 *     "CreatedDate": string,
 *     "Status": string
 *   }
 * }
 *
 * @throws Exception Si ocurre un error al procesar la consulta
 *
 * @access public
 */

/** Recepción de parámetros */
$start = $_GET["start"];
$count = $_GET["count"];
$userId = $_GET["UserId"];
$betId = $_GET["BetId"];
$campaignType = $_GET["CampaignType"];
$campaignId = $_GET["CampaignId"];
$dateFrom = $_GET["dateFrom"];
$dateTo = $_GET["dateTo"];
$typeBet = $_GET["TypeBet"];

/** 
 * Sanitización de parámetros - Define patrones regex para validar cada parámetro
 * y evitar valores no permitidos
 */
$validableParameters = [
    'start' => '\D+', //Todo lo que no sea un número
    'count' => '\D+',
    'userId' => '\W+', //Todo lo que no sea un número o una letra
    'betId' => '([^\w|\-|\_])+', //Todo lo que no sea un caracter alfanumérico o los caracteres: (-, _)
    'campaignType' => '([^0|1|2])+|(\d){2,}', //Todo lo que no sea uno de los números listados o alguno de los números listados en más de una ocasión
    'campaignId' => '\W+',
    'dateFrom' => '([^\d|\s|\-])+', //Todo lo que no sean los caracteres que conforman una fecha
    'dateTo' => '([^\d|\s|\-])+',
    'typeBet' => '([^0|1])+|(\d){2,}'
];

/**
 * Validación de parámetros - Verifica cada parámetro contra su patrón regex
 * y guarda los inválidos en un array
 */
$invalidParamenters = [];
foreach ($validableParameters as $parameter => $pattern) {
    $validationResponse = preg_match("/$pattern/", $$parameter);
    if ($validationResponse === false || $validationResponse === 1) $invalidParamenters[] = $parameter;
}

$totalMissedValidations = count($invalidParamenters);
if ($totalMissedValidations > 0) {
    throw new Exception("Parámetros inválidos {$invalidParamenters[0]} y otro(s) {$totalMissedValidations} error(es)", 300023);
}

/**
 * Consulta información del operador y verifica parámetros requeridos
 * Retorna error si faltan typeBet o campaignType
 */
$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

if (($typeBet == null || $typeBet == "") || ($campaignType == null || $campaignType == "")) {
    $response["HasError"] = true;
    $response["AlertType"] = "warning";
    if (empty($typeBet)) $response["AlertMessage"] = "Defina el tipo de apuesta a consultar";
    if (empty($campaignType)) $response["AlertMessage"] = "Defina el tipo de campaña a consultar";
    $response["ModelErrors"] = [];
    return;
}

/**
 * Inicialización de variables y filtros para la consulta
 * Configura filtros base según mandante y país del usuario
 */
$rules = [];
$joins = [];
$partner = $_SESSION['mandante'] != -1 ? $_SESSION['mandante'] : '';
$countrySelect = $_SESSION['PaisCondS'] ?? $_SESSION['pais_id'];

if (!empty($partner) || strval($partner) == '0') $rules[] = ['field' => 'usuario_mandante.mandante', 'data' => $partner, 'op' => 'eq'];
if (!empty($_SESSION['PaisCondS']) && !in_array($_SESSION['PaisCondS'], [-1, 0])) $rules[] = ['field' => 'usuario_mandante.pais_id', 'data' => $countrySelect, 'op' => 'eq'];

if (!empty($userId)) {
    $rules[] = ['field' => 'usuario_mandante.usuario_mandante', 'data' => $userId, 'op' => 'eq'];
}

/**
 * Inicialización de variables para el resultado
 * Prepara variables para almacenar resultados de la consulta
 */
$position = $start;
$mainTable = null;
$totalCount = 0;
$data = [];
// Bloque para procesar apuestas deportivas
if ($typeBet == 0) {
    /** Lógica y filtrado de apuestas deportivas*/
    $select = 'it_ticket_enc_info1.it_ticket2_id, usuario_mandante.usuario_mandante, it_ticket_enc.ticket_id, it_ticket_enc.vlr_apuesta, it_ticket_enc_info1.valor2';
    $orderBy = 'it_ticket_enc_info1.it_ticket2_id';

    //Join inicial de it_ticket_enc
    $joins[] = ['type' => 'INNER', 'table' => 'it_ticket_enc', 'on' => 'it_ticket_enc.ticket_id = it_ticket_enc_info1.ticket_id'];

    // Configura los filtros de fecha para las apuestas
    if (!empty($dateFrom) && !empty($dateTo)) {
        $dateFrom = date("Y-m-d 00:00:00", strtotime($dateFrom));
        $dateTo = date("Y-m-d 23:59:59", strtotime($dateTo));
        $rules[] = ['field' => 'it_ticket_enc.fecha_crea_time', 'data' => $dateFrom, 'op' => 'ge'];
        $rules[] = ['field' => 'it_ticket_enc.fecha_crea_time', 'data' => $dateTo, 'op' => 'le'];
    }

    // Agrega filtro por ID de apuesta si se especifica
    if (!empty($betId)) {
        $rules[] = ['field' => 'it_ticket_enc.ticket_id', 'data' => $betId, 'op' => 'eq'];
    }

    // Configura los joins y filtros según el tipo de campaña
    switch($campaignType) {
        case 0:
            //Campaña = Bono
            $mainTable = 'usuario_bono';
            $select .= ', usuario_bono.bono_id'; //Concatenando solicitud campaignId correspondiente al tipo solicitado
            array_unshift($joins, ['type' => 'INNER', 'table' => 'it_ticket_enc_info1', 'on' => 'it_ticket_enc_info1.valor = usuario_bono.usubono_id']);
            $joins[] = ['type' => 'INNER', 'table' => 'usuario_mandante', 'on' => 'usuario_bono.usuario_id = usuario_mandante.usuario_mandante'];

            // Aplica filtros específicos para bonos
            $rules[] = ['field' => 'it_ticket_enc_info1.tipo', 'data' => 'ROLLOWER', 'op' => 'eq'];

            if (!empty($campaignId)) {
                $rules[] = ['field' => 'usuario_bono.bono_id', 'data' => $campaignId, 'op' => 'eq'];
            }
            break;
        case 1:
            //Campaña = Torneo
            $mainTable = 'usuario_torneo';
            $select .= ', usuario_torneo.torneo_id'; //Concatenando solicitud campaignId correspondiente al tipo solicitado
            array_unshift($joins, ['type' => 'INNER', 'table' => 'it_ticket_enc_info1', 'on' => 'it_ticket_enc_info1.valor = usuario_torneo.usutorneo_id']);
            $joins[] = ['type' => 'INNER', 'table' => 'usuario_mandante', 'on' => 'usuario_torneo.usuario_id = usuario_mandante.usumandante_id'];

            // Aplica filtros específicos para torneos
            $rules[] = ['field' => 'it_ticket_enc_info1.tipo', 'data' => 'TORNEO', 'op' => 'eq'];

            if (!empty($campaignId)) {
                $rules[] = ['field' => 'usuario_torneo.torneo_id', 'data' => $campaignId, 'op' => 'eq'];
            }
            break;
        case 2:
            //Campaña = Sorteo
            $mainTable = 'preusuario_sorteo';
            $select .= ', preusuario_sorteo.sorteo_id'; //Concatenando solicitud campaignId correspondiente al tipo solicitado
            array_unshift($joins, ['type' => 'INNER', 'table' => 'it_ticket_enc_info1', 'on' => 'it_ticket_enc_info1.valor = preusuario_sorteo.preususorteo_id']);
            $joins[] = ['type' => 'INNER', 'table' => 'usuario_mandante', 'on' => 'preusuario_sorteo.usuario_id = usuario_mandante.usumandante_id'];

            // Aplica filtros específicos para sorteos
            $rules[] = ['field' => 'it_ticket_enc_info1.tipo', 'data' => 'SORTEOSTICKER', 'op' => 'eq'];

            if (!empty($campaignId)) {
                $rules[] = ['field' => 'preusuario_sorteo.sorteo_id', 'data' => $campaignId, 'op' => 'eq'];
            }
            break;
    }

    // Ejecuta la consulta para obtener las apuestas
    $filters = ['rules' => $rules, 'groupOp' => 'AND'];
    $joins = json_decode(json_encode($joins));

    $TransjuegoInfo = new TransjuegoInfo();
    $betsPerCampaignResponse = $TransjuegoInfo->getSuperCustom($select, $orderBy, 'DESC', $start, $count, json_encode($filters), true, null, $mainTable, $joins);
    $betsPerCampaignResponse = json_decode($betsPerCampaignResponse);
    $totalCount = $betsPerCampaignResponse->count[0]->{'.count'};
    $betsPerCampaign = $betsPerCampaignResponse->data;

    // Procesa los resultados y construye la respuesta
    foreach ($betsPerCampaign as $bet) {
        $betLog = (object)[];
        $betLog->InternalId = (string) $bet->{'it_ticket_enc_info1.it_ticket2_id'};
        $betLog->UserId = (string) $bet->{'usuario_mandante.usuario_mandante'};
        $betLog->BetId = (string) $bet->{'it_ticket_enc.ticket_id'};
        $betLog->CampaignType = (string) $campaignType;
        $betLog->BetAmount = (string) $bet->{'it_ticket_enc.vlr_apuesta'};

        // Asigna valores específicos según el tipo de campaña
        switch ($campaignType) {
            case 0:
                //Caso = BONO
                $betLog->CampaignId = (string) $bet->{'usuario_bono.bono_id'};
                $betLog->ConvertedAmount = (string) $bet->{'it_ticket_enc_info1.valor2'};
                break;
            case 1:
                //Caso = TORNEO
                $betLog->CampaignId = (string) $bet->{'usuario_torneo.torneo_id'};
                $betLog->ConvertedAmount = (string) $bet->{'it_ticket_enc_info1.valor2'};
                break;
            case 2:
                //Caso = SORTEO
                $betLog->CampaignId = (string) $bet->{'preusuario_sorteo.sorteo_id'};
                $betLog->ConvertedAmount = (string) $bet->{'it_ticket_enc.vlr_apuesta'};
                break;
        }

        $data[] = $betLog;
    }
}

// Bloque para procesar apuestas de casino, casino en vivo o virtuales
elseif ($typeBet == 1) {
    /** Lógica y filtrado de apuestas casino, casino en vivo o virtuales*/
    $select = 'transjuego_info.transjuegoinfo_id, transaccion_juego.ticket_id, usuario_mandante.usuario_mandante, transjuego_log.valor, transjuego_info.valor';
    $orderBy = 'transjuego_info.transjuegoinfo_id';

    // Configura los joins iniciales para la consulta
    $joins[] = ['type' => 'INNER', 'table' => 'transjuego_log', 'on' => 'transjuego_info.transapi_id = transjuego_log.transjuegolog_id AND transjuego_log.tipo = "DEBIT"'];
    $joins[] = ['type' => 'INNER', 'table' => 'transaccion_juego', 'on' => 'transjuego_log.transjuego_id = transaccion_juego.transjuego_id'];

    // Aplica filtros de fecha si se especifican
    if (!empty($dateFrom) && !empty($dateTo)) {
        $dateFrom = date("Y-m-d 00:00:00", strtotime($dateFrom));
        $dateTo = date("Y-m-d 23:59:59", strtotime($dateTo));
        $rules[] = ['field' => 'transjuego_log.fecha_crea', 'data' => $dateFrom, 'op' => 'ge'];
        $rules[] = ['field' => 'transjuego_log.fecha_crea', 'data' => $dateTo, 'op' => 'le'];
    }

    // Agrega filtro por ID de apuesta si se especifica
    if (!empty($betId)) {
        $rules[] = ['field' => 'transaccion_juego.ticket_id', 'data' => $betId, 'op' => 'eq'];
    }

    // Configura joins y filtros según el tipo de campaña
    switch($campaignType) {
        case 0:
            //Campaña = Bono
            $mainTable = 'usuario_bono';
            $select .= ', usuario_bono.bono_id'; //Concatenando solicitud campaignId correspondiente al tipo solicitado
            array_unshift($joins, ['type' => 'INNER', 'table' => 'transjuego_info', 'on' => 'transjuego_info.descripcion = usuario_bono.usubono_id']);
            $joins[] = ['type' => 'INNER', 'table' => 'usuario_mandante', 'on' => 'usuario_bono.usuario_id = usuario_mandante.usuario_mandante'];

            $rules[] = ['field' => 'transjuego_info.tipo', 'data' => 'ROLLOWER', 'op' => 'eq'];

            if (!empty($campaignId)) {
                $rules[] = ['field' => 'usuario_bono.bono_id', 'data' => $campaignId, 'op' => 'eq'];
            }
            break;
        case 1:
            //Campaña = Torneo
            $mainTable = 'usuario_torneo';
            $select .= ', usuario_torneo.torneo_id'; //Concatenando solicitud campaignId correspondiente al tipo solicitado
            array_unshift($joins, ['type' => 'INNER', 'table' => 'transjuego_info', 'on' => 'transjuego_info.descripcion = usuario_torneo.usutorneo_id']);
            $joins[] = ['type' => 'INNER', 'table' => 'usuario_mandante', 'on' => 'usuario_torneo.usuario_id = usuario_mandante.usumandante_id'];

            $rules[] = ['field' => 'transjuego_info.tipo', 'data' => 'TORNEO', 'op' => 'eq'];

            if (!empty($campaignId)) {
                $rules[] = ['field' => 'usuario_torneo.torneo_id', 'data' => $campaignId, 'op' => 'eq'];
            }
            break;
        case 2:
            //Campaña = Sorteo
            $mainTable = 'preusuario_sorteo';
            $select .= ', preusuario_sorteo.sorteo_id'; //Concatenando solicitud campaignId correspondiente al tipo solicitado
            array_unshift($joins, ['type' => 'INNER', 'table' => 'transjuego_info', 'on' => 'transjuego_info.descripcion = preusuario_sorteo.preususorteo_id']);
            $joins[] = ['type' => 'INNER', 'table' => 'usuario_mandante', 'on' => 'preusuario_sorteo.usuario_id = usuario_mandante.usumandante_id'];

            $rules[] = ['field' => 'transjuego_info.tipo', 'data' => 'SORTEO', 'op' => 'eq'];

            if (!empty($campaignId)) {
                $rules[] = ['field' => 'preusuario_sorteo.sorteo_id', 'data' => $campaignId, 'op' => 'eq'];
            }
            break;
    }

    // Ejecuta la consulta para obtener las apuestas
    $filters = ['rules' => $rules, 'groupOp' => 'AND'];
    $joins = json_decode(json_encode($joins));

    $TransjuegoInfo = new TransjuegoInfo();
    $betsPerCampaignResponse = $TransjuegoInfo->getSuperCustom($select, $orderBy, 'DESC', $start, $count, json_encode($filters), true, null, $mainTable, $joins);
    $betsPerCampaignResponse = json_decode($betsPerCampaignResponse);
    $totalCount = $betsPerCampaignResponse->count[0]->{'.count'};
    $betsPerCampaign = $betsPerCampaignResponse->data;

    // Procesa los resultados y construye la respuesta
    foreach ($betsPerCampaign as $bet) {
        $betLog = (object)[];
        $betLog->InternalId = (string) $bet->{'transjuego_info.transjuegoinfo_id'};
        $betLog->UserId = (string) $bet->{'usuario_mandante.usuario_mandante'};
        $betLog->BetId = (string) $bet->{'transaccion_juego.ticket_id'};
        $betLog->CampaignType = (string) $campaignType;
        $betLog->BetAmount = (string) $bet->{'transjuego_log.valor'};
        $betLog->ConvertedAmount = (string) $bet->{'transjuego_info.valor'};

        // Asigna valores específicos según el tipo de campaña
        switch ($campaignType) {
            case 0:
                //Caso = BONO
                $betLog->CampaignId = (string) $bet->{'usuario_bono.bono_id'};
                break;
            case 1:
                //Caso = TORNEO
                $betLog->CampaignId = (string) $bet->{'usuario_torneo.torneo_id'};
                break;
            case 2:
                //Caso = SORTEO
                $betLog->CampaignId = (string) $bet->{'preusuario_sorteo.sorteo_id'};
                $betLog->ConvertedAmount = (string) $bet->{'transjuego_log.valor'};
                break;
        }

        $data[] = $betLog;
    }
}

$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response['pos'] = (string) $position;
$response['total_count'] = (string) $totalCount;
$response['data'] = $data;
?>