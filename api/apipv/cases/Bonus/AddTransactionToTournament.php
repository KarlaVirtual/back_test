<?php

use Backend\dto\BonoInterno;
use Backend\dto\ItTicketEnc;
use Backend\dto\ItTicketEncInfo1;
use Backend\dto\TorneoDetalle;
use Backend\dto\TorneoInterno;
use Backend\dto\TransjuegoInfo;
use Backend\dto\TransjuegoLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioTorneo;
use Backend\mysql\ItTicketEncInfo1MySqlDAO;
use Backend\mysql\TransjuegoInfoMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioTorneoMySqlDAO;
use Backend\websocket\WebsocketUsuario;

/**
 * Bonus/AddTransactionToTournament
 *
 * Este script asocia una transacción a un torneo específico, validando las condiciones del torneo.
 *
 * @param object $params Objeto que contiene los siguientes parámetros:
 * @param int $params ->Type Tipo de torneo (1 para sportsbook, 2 para casino, etc.).
 * @param int $params ->QueryType Tipo de consulta (0 para no enlazar, 1 para enlazar transacción).
 * @param int $params ->IdTournament Identificador del torneo.
 * @param int $params ->IdTransaction Ident
 *
 * @return array Respuesta en formato JSON con los siguientes campos:
 *  - HasError (boolean): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta (e.g., 'success', 'error').
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *
 * @throws Exception Si ocurre un error durante la validación o asociación de la transacción.
 */


/* Obtención del parámetro */
$Type = $params->Type;
$QueryType = $params->QueryType;
$IdTournament = $params->IdTournament;
$IdTransaction = $params->IdTransaction;

try {

    /* Crea filtros en formato JSON para consultar torneos internos con condiciones específicas. */
    $rules = [];
    array_push($rules, ['field' => 'torneo_interno.torneo_id', 'data' => $IdTournament, 'op' => 'eq']);
    array_push($rules, ['field' => 'torneo_interno.tipo', 'data' => $Type, 'op' => 'eq']);
#array_push($rules, ['field' => 'torneo_interno.estado', 'data' => 'A', 'op' => 'eq']);
    array_push($rules, ['field' => 'torneo_interno.fecha_fin', 'data' => date('Y-m-d h:i:s'), 'op' => 'gt']);

    $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

    $TorneoInterno = new TorneoInterno();
    $queryTournament = (string)$TorneoInterno->getTorneosCustom('torneo_interno.*', 'torneo_interno.torneo_id', 'ASC', 0, 1, $filters, true);
    $queryTournament = json_decode($queryTournament, true);
    $tournamenData = $queryTournament['data'][0];

    if ($tournamenData['torneo_interno.estado'] === 'I') throw new Exception("El torneo con ID {$IdTournament} está inactivo. Por favor, verifique el estado antes de continuar.", 300072);

    if ($queryTournament['count'][0]['.count'] == 0) throw new Exception("El torneo con ID {$IdTournament} no existe.", 300035);


    /* Verifica si la variable $Type no es igual a 1. */
    if ($Type != 1) {
        $TransjuegoLog = new TransjuegoLog($IdTransaction);

        $rules = [];

        array_push($rules, ['field' => 'transjuego_info.transapi_id', 'data' => $TransjuegoLog->getTransjuegologId(), 'op' => 'eq']);
        array_push($rules, ['field' => 'transjuego_info.tipo', 'data' => 'TORNEO', 'op' => 'eq']);

        $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

        $TransjuegoInfo = new TransjuegoInfo();
        $queryTransactionInfo = (string)$TransjuegoInfo->getTransjuegoInfosCustom('transjuego_info.*', 'transjuego_info.transapi_id', 'ASC', 0, 1, $filters, true, false);
        $queryTransactionInfo = json_decode($queryTransactionInfo, true);

        if ($queryTransactionInfo['count'][0]['.count'] > 0) throw new Exception("La transaccion con ID {$IdTransaction} ya se encuentra dentro de un torneo", 300038);

        validateTournamentDetailCasino($IdTournament, $IdTransaction, $Type, $QueryType == 0 ? false : true);
    } else {

        /* crea reglas de filtrado para una consulta basada en tickets. */
        $ItTicketEnc = new ItTicketEnc($IdTransaction);

        $rules = [];
        array_push($rules, ['field' => 'it_ticket_enc_info1.ticket_id', 'data' => $ItTicketEnc->getTicketId(), 'op' => 'eq']);
        array_push($rules, ['field' => 'it_ticket_enc_info1.tipo', 'data' => 'TORNEO', 'op' => 'eq']);

        $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

        /* Se obtiene información de un ticket y se valida su participación en un torneo. */
        $ItTicketEncInfo1 = new ItTicketEncInfo1();
        $queryItTicketEncInfo = (string)$ItTicketEncInfo1->getTicketsCustom('it_ticket_enc_info1.*', 'it_ticket_enc_info1.ticket_id', 'ASC', 0, 1, $filters, true);
        $queryItTicketEncInfo = json_decode($queryItTicketEncInfo, true);
        $ItTicketEncInfoData = $queryItTicketEncInfo['data'][0];

        if ($queryItTicketEncInfo['count'][0]['.count'] > 0) throw new Exception("La transaccion con ID {$IdTransaction} ya se encuentra dentro de un torneo", 300038);

        validateTournamenstDetailSporbook($IdTournament, $IdTransaction, $Type, $QueryType == 0 ? false : true);
    }

    /*Generación de respuesta exitosa*/
    $response['HasError'] = false;
    $response['AlertType'] = 'success';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];
} catch (Exception $ex) {
    /*Manejo de errores*/

    $errorMessage = 'Error general';

    if (in_array($ex->getCode(), [24, 28])) $errorMessage = "La transaccion con ID {$IdTransaction} no existe";
    else if ($ex->getCode() > 300000) $errorMessage = $ex->getMessage();

    $response['HasError'] = false;
    $response['AlertType'] = 'error';
    $response['AlertMessage'] = $errorMessage;
    $response['ModelErrors'] = [];
}

/**
 * Obtiene el tipo actual de torneo basado en el valor proporcionado.
 *
 * @param int $type El tipo de torneo (1 para SPORTBOOK, 2 para CASINO, 3 para LIVECASINO, 4 para VIRTUAL).
 * @return string El nombre del tipo de torneo.
 */
function getCurrentType($type)
{
    switch ($type) {
        case 1:
            return 'SPORTBOOK';
        case 2:
            return 'CASINO';
        case 3:
            return 'LIVECASINO';
        case 4:
            return 'VIRTUAL';
        default:
            return '';
    }
}


/**
 * Verifica si al menos un elemento del array cumple con la condición especificada.
 *
 * @param array $array El array a evaluar.
 * @param callable $fn La función que define la condición a evaluar.
 * @return bool True si al menos un elemento cumple la condición, de lo contrario False.
 */
function array_some(array $array, callable $fn)
{
    foreach ($array as $value) {
        if ($fn($value)) return true;
    }
    return false;
}

/**
 * Verifica si todos los elementos del array cumplen con la condición especificada.
 *
 * @param array $array El array a evaluar.
 * @param callable $fn La función que define la condición a evaluar.
 * @return bool True si todos los elementos cumplen la condición, de lo contrario False.
 */
function array_every(array $array, callable $fn)
{
    foreach ($array as $value) {
        if (!$fn($value)) return false;
    }
    return true;
}

/**
 * Valida los detalles del torneo para un casino.
 *
 * @param int $tournamentID El ID del torneo.
 * @param int $transactionID El ID de la transacción.
 * @param int $type El tipo de torneo.
 * @param bool $linkTransaction Indica si se debe enlazar la transacción.
 *
 * @throws Exception Si la transacción no cumple con las condiciones del torneo.
 */
function validateTournamentDetailCasino($tournamentID, $transactionID, $type, $linkTransaction = false)
{

    $TorneoInterno = new TorneoInterno($tournamentID);
    $partner = $TorneoInterno->mandante;
    $tournamentDescription = $TorneoInterno->descripcion;
    $currentType = getCurrentType($type);

    $rules = [];

    array_push($rules, ['field' => 'torneo_detalle.torneo_id', 'data' => $TorneoInterno->torneoId, 'op' => 'eq']);
    array_push($rules, ['field' => 'torneo_interno.estado', 'data' => 'A', 'op' => 'eq']);

    $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

    $TorneoDetalle = new TorneoDetalle();
    $queryTournamentDetail = (string)$TorneoDetalle->getTorneoDetallesCustom('torneo_detalle.*', 'torneo_detalle.torneodetalle_id', 'ASC', 0, 100000, $filters, true);
    $queryTournamentDetail = json_decode($queryTournamentDetail, true);

    $tournamentConditions = [
        'RANK' => [],
        'CONDPAISUSER' => [],
        'USERSUBSCRIBE' => false,
        'CONDGAME' => [],
        'GENERALGAMES' => [],
        'CONDCATEGORY' => [],
        'CONDPROVIDER' => [],
        'CONDSUBPROVIDER' => []
    ];

    foreach ($queryTournamentDetail['data'] as $key => $value) {
        switch ($value['torneo_detalle.tipo']) {
            case 'RANK':
                $tournamentConditions['RANK'][$value['torneo_detalle.moneda']] = [
                    'valor' => $value['torneo_detalle.valor'],
                    'valor2' => $value['torneo_detalle.valor2'],
                    'valor3' => $value['torneo_detalle.valor3']
                ];
                break;
            case 'USERSUBSCRIBE':
                $tournamentConditions['USERSUBSCRIBE'] = $value['torneo_detalle.valor'] == 1;
                break;
            case 'VISIBILIDAD':
                $tournamentConditions['USERSUBSCRIBE'] = $value['torneo_detalle.valor'] == 1;
                break;
            case 'CONDPAISUSER':
                array_push($tournamentConditions['CONDPAISUSER'], $value['torneo_detalle.valor']);
                break;
            default:
                $replaceType = '';
                if (strpos($value['torneo_detalle.tipo'], 'CONDGAME') !== false) $replaceType = 'CONDGAME';
                else if (strpos($value['torneo_detalle.tipo'], 'CONDCATEGORY') !== false) $replaceType = 'CONDCATEGORY';
                else if (strpos($value['torneo_detalle.tipo'], 'CONDPROVIDER') !== false) $replaceType = 'CONDPROVIDER';
                else if (strpos($value['torneo_detalle.tipo'], 'CONDSUBPROVIDER') !== false) $replaceType = 'CONDSUBPROVIDER';

                if (!empty($replaceType)) {
                    $detailValue = str_replace($replaceType, '', $value['torneo_detalle.tipo']);
                    array_push($tournamentConditions[$replaceType], $detailValue);
                }
                break;
        }
    }

    $BonoInterno = new BonoInterno();

    if (oldCount($tournamentConditions['CONDCATEGORY']) > 0) {
        $sqlValues = implode(', ', $tournamentConditions['CONDCATEGORY']);
        $queryProducts = "SELECT producto.producto_id
                    FROM producto
                        INNER JOIN categoria_producto
                        ON (categoria_producto.producto_id = producto.producto_id)
                    where categoria_producto.categoria_id IN({$sqlValues})";

        $dataProducts = json_encode($BonoInterno->execQuery('', $queryProducts));
        $dataProducts = json_decode($dataProducts, true);

        $tournamentConditions['GENERALGAMES'] = array_map(function ($item) {
            return $item['producto.producto_id'];
        }, $dataProducts);
    }
    // $subProviders = implode(',', $tournamentConditions['CONDSUBPROVIDER']);
    // $filterSubprovider = $subProviders !== '' ? "AND producto.subproveedor_id IN({$subProviders})" : '';

    // $providers = implode(',', $tournamentConditions['CONDPROVIDER']);
    // $filterProviders = $providers !== '' ? "AND producto.producto_id IN({$providers})" : '';

    // $games = implode(',', $tournamentConditions['CONDGAME']);
    // $filterGames = $games !== '' ? "AND producto_mandante.prodmandante_id IN({$games})" : '';

    // $generalGames = implode(',', $tournamentConditions['GENERALGAMES']);
    // $filterGeneralGames = $generalGames !== '' ? "AND producto.producto_id IN({$generalGames})" : '';

    // $countries = implode(',', $tournamentConditions['CONDPAISUSER']);
    // $filterCountries = $countries !== '' ? "AND usuario_mandante.pais_id IN({$countries})" : '';

    $queryTransGameLog = "SELECT
                                transjuego_log.transjuegolog_id,
                                transjuego_log.transaccion_id,
                                producto.subproveedor_id,
                                transaccion_juego.usuario_id,
                                transjuego_log.valor,
                                producto.producto_id,
                                producto.proveedor_id,
                                producto.subproveedor_id,
                                producto_mandante.prodmandante_id,
                                subproveedor.tipo,
                                usuario_mandante.usumandante_id,
                                usuario_mandante.usuario_mandante,
                                usuario_mandante.mandante,
                                usuario_mandante.moneda,
                                usuario_mandante.pais_id
                            FROM
                                transjuego_log
                            INNER JOIN transaccion_juego ON
                                transjuego_log.transjuego_id = transaccion_juego.transjuego_id
                            INNER JOIN producto_mandante ON
                                transaccion_juego.producto_id = producto_mandante.prodmandante_id
                            INNER JOIN producto ON
                                producto_mandante.producto_id = producto.producto_id
                            INNER JOIN subproveedor ON
                                subproveedor.subproveedor_id = producto.subproveedor_id
                            INNER JOIN usuario_mandante ON
                                usuario_mandante.usumandante_id = transaccion_juego.usuario_id
                            WHERE
                                subproveedor.tipo = '{$currentType}'
                                AND transjuego_log.transjuegolog_id = {$transactionID}
                                AND usuario_mandante.mandante = {$partner}
                                AND transjuego_log.tipo LIKE '%DEBIT%'";

    // $queryTransGameLog = str_replace(['$1', '$2', '$3', '$4', '$5'], [$filterSubprovider, $filterProviders, $filterGames, $filterGeneralGames, $filterCountries], $queryTransGameLog);

    $transactionData = json_encode($BonoInterno->execQuery('', $queryTransGameLog));
    $transactionData = json_decode($transactionData, true)[0];

    if (
        oldCount($tournamentConditions['CONDPAISUSER']) > 0 &&
        !in_array($transactionData['usuario_mandante.pais_id'], $tournamentConditions['CONDPAISUSER'])
    ) {
        throw new Exception('La transaccion no cumple con la condicion de pais', 300039);
    }

    if (
        oldCount($tournamentConditions['CONDGAME']) > 0 &&
        !in_array($transactionData['producto_mandante.prodmandante_id'], $tournamentConditions['CONDGAME'])
    ) {
        throw new Exception("La transaccion no cumple con la condicion de juegos", 300040);
    }

    if (
        oldCount($tournamentConditions['CONDPROVIDER']) > 0 &&
        !in_array($transactionData['producto.proveedor_id'], $tournamentConditions['CONDPROVIDER'])
    ) {
        throw new Exception('La transaccion no cumple con la condicion de proveedor', 300041);
    }

    if (
        oldCount($tournamentConditions['CONDSUBPROVIDER']) > 0 &&
        !in_array($transactionData['producto.subproveedor_id'], $tournamentConditions['CONDSUBPROVIDER'])
    ) {
        throw new Exception('La transaccion no cumple con la condicion de subproveedor', 300042);
    }

    $currentCredits = 0;
    $ticketValue = floatval($transactionData['transjuego_log.valor']);
    $currency = $transactionData['usuario_mandante.moneda'];

    if (oldCount($tournamentConditions['RANK'][$transactionData['usuario_mandante.moneda']]) > 0) {
        $currentCredits = $ticketValue >= $tournamentConditions['RANK'][$currency]['valor'] && $ticketValue <= $tournamentConditions['RANK'][$currency]['valor2'] ? $tournamentConditions['RANK'][$currency]['valor3'] : 0;
    }

    if ($currentCredits === 0) throw new Exception('La transaccion no cumple con la condicion de valor apuesta', 300045);

    if ($tournamentConditions['USERSUBSCRIBE'] && $currentCredits === 0) {
        throw new Exception('La transaccion no cumple con la condicion suscripcion', 300043);
    }

    if ($linkTransaction) {
        $UsuarioTorneoMySqlDAO = new UsuarioTorneoMySqlDAO();
        $Transaction = $UsuarioTorneoMySqlDAO->getTransaction();

        try {
            $UsuarioTorneo = new UsuarioTorneo('', $transactionData['usuario_mandante.usumandate_id'], $transactionData['torneo_interno.torneo_id']);
            $sqlUserTournament = "UPDATE usuario_torneo SET valor = (valor + $1), valor_base = (valor_base + $2) WHERE usuario_torneo = $3";
            $sqlUserTournament = str_replace(['$1', '$2', '$3'], [$currentCredits, $ticketValue, $UsuarioTorneo->getTorneoId()], $sqlUserTournament);

            $BonoInterno->execUpdate($Transaction, $sqlUserTournament);
        } catch (Exception $ex) {
            if ($ex->getCode() == 33) {
                $UsuarioTorneo = new UsuarioTorneo();
                $UsuarioTorneo->usuarioId = $transactionData['usuario_mandante.usuario_mandante'];
                $UsuarioTorneo->torneoId = $tournamentID;
                $UsuarioTorneo->posicion = 0;
                $UsuarioTorneo->usucreaId = 0;
                $UsuarioTorneo->usumodifId = 0;
                $UsuarioTorneo->estado = 'A';
                $UsuarioTorneo->errorId = 0;
                $UsuarioTorneo->idExterno = 0;
                $UsuarioTorneo->mandante = $transactionData['usuario_mandante.mandante'];
                $UsuarioTorneo->version = 0;
                $UsuarioTorneo->apostado = 0;
                $UsuarioTorneo->codigo = 0;
                $UsuarioTorneo->externoId = 0;
                $UsuarioTorneo->valor = $currentCredits;
                $UsuarioTorneo->valorBase = $ticketValue;
                $UsuarioTorneo->usucreaId = $_SESSION['usuario'];
                $UsuarioTorneo->usumodifId = 0;

                $UsuarioTorneoMySqlDAO->insert($UsuarioTorneo);
            }
        }

        $TransJuegoInfo = new TransjuegoInfo();
        $TransJuegoInfo->productoId = $transactionData['producto_mandante.prodmandante_id'];
        $TransJuegoInfo->transaccionId = $transactionData['transjuego_log.transaccion_id'];
        $TransJuegoInfo->transapiId = $transactionData['transjuego_log.transjuegolog_id'];
        $TransJuegoInfo->tipo = 'TORNEO';
        $TransJuegoInfo->descripcion = $tournamentID;
        $TransJuegoInfo->valor = $currentCredits;
        $TransJuegoInfo->usucreaId = $_SESSION['usuario'];
        $TransJuegoInfo->usumodifId = 0;

        $TransJuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO($Transaction);
        $TransJuegoInfoMySqlDAO->insert($TransJuegoInfo);

        $Usuario = new Usuario($transactionData['usuario_mandante.usuario_mandante']);
        $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);

        $title = '';
        $messageBody = '';

        switch (strtolower($Usuario->idioma)) {
            case 'es':
                $title = 'Notificacion';
                $messageBody = "¡ Bien :thumbsup: ! Sumaste {$currentCredits} puntos en {$tournamentDescription} :clap:";
                break;
            case 'en':
                $title = 'Notification';
                $messageBody = "¡ Great :thumbsup: ! You added {$currentCredits} points in {$tournamentDescription} :clap:";
                break;
            case 'pt':
                $title = 'Notificação';
                $messageBody = "¡ Bien :thumbsup: ! Sumaste {$currentCredits} puntos en {$tournamentDescription} :clap:";
                break;
        }

        $UsuarioMensaje = new UsuarioMensaje();
        $UsuarioMensaje->usufromId = 0;
        $UsuarioMensaje->usutoId = $Usuario->usuarioId;
        $UsuarioMensaje->isRead = 0;
        $UsuarioMensaje->body = $messageBody;
        $UsuarioMensaje->msubject = $title;
        $UsuarioMensaje->parentId = 0;
        $UsuarioMensaje->proveedorId = 0;
        $UsuarioMensaje->tipo = "PUSHNOTIFICACION";
        $UsuarioMensaje->paisId = 0;
        $UsuarioMensaje->fechaExpiracion = '';

        $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($Transaction);
        $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);

        try {
            $WebsocketUsuario = new WebsocketUsuario('', '');
            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $messageBody);
        } catch (Exception $ex) {
        }

        $Transaction->commit();
    }
}

/**
 * Valida los detalles del torneo para un sportsbook.
 *
 * @param int $tournamentID El ID del torneo.
 * @param int $transactionID El ID de la transacción.
 * @param int $type El tipo de torneo.
 * @param bool $linkTransaction Indica si se debe enlazar la transacción.
 *
 * @throws Exception Si la transacción no cumple con las condiciones del torneo.
 */
function validateTournamenstDetailSporbook($tournamentID, $transactionID, $type, $linkTransaction = false)
{
    $TorneoInterno = new TorneoInterno($tournamentID);
    $partner = $TorneoInterno->mandante;
    $tournamentDescription = $TorneoInterno->descripcion;

    $rules = [];

    array_push($rules, ['field' => 'torneo_detalle.torneo_id', 'data' => $TorneoInterno->torneoId, 'op' => 'eq']);
    array_push($rules, ['field' => 'torneo_interno.estado', 'data' => 'A', 'op' => 'eq']);

    $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

    $TorneoDetalle = new TorneoDetalle();
    $queryTournamentDetail = (string)$TorneoDetalle->getTorneoDetallesCustom('torneo_detalle.*', 'torneo_detalle.torneodetalle_id', 'ASC', 0, 100000, $filters, true);
    $queryTournamentDetail = json_decode($queryTournamentDetail, true);

    $tournamentConditions = [
        'RANK' => [],
        'MINBETPRICESPORTSBOOK' => null,
        'USERSUBSCRIBE' => false,
        'CONDPAISUSER' => [],
        'LIVEORPREMATCH' => '',
        'MINSELCOUNT' => null,
        'MINSELPRICE' => null,
        'MINSELPRICETOTAL' => null,
        'ITAINMENT1' => [],
        'ITAINMENT3' => [],
        'ITAINMENT4' => [],
        'ITAINMENT5' => [],
        'ITAINMENT82COMBINE' => null,
        'ITAINMENT82SIMPLE' => null
    ];

    foreach ($queryTournamentDetail['data'] as $key => $value) {
        switch ($value['torneo_detalle.tipo']) {
            case 'RANK':
                $tournamentConditions['RANK'][$value['torneo_detalle.moneda']] = [
                    'valor' => $value['torneo_detalle.valor'],
                    'valor2' => $value['torneo_detalle.valor2'],
                    'valor3' => $value['torneo_detalle.valor3']
                ];
                break;
            case 'MINBETPRICESPORTSBOOK':
                $tournamentConditions['MINBETPRICESPORTSBOOK'] = floatval($value['torneo_detalle.valor']);
                break;
            case 'VISIBILIDAD':
                $tournamentConditions['USERSUBSCRIBE'] = $value['torneo_detalle.valor'] !== 1;
                break;
            case 'USERSUBSCRIBE':
                $tournamentConditions['USERSUBSCRIBE'] = $value['torneo_detalle.valor'] !== 0;
                break;
            case 'CONDPAISUSER':
                array_push($tournamentConditions['CONDPAISUSER'], $value['torneo_detalle.valor']);
                break;
            case 'LIVEORPREMATCH':
                $tournamentConditions['LIVEORPREMATCH'] = $value['torneo_detalle.valor'];
                break;
            case 'MINSELCOUNT':
                $tournamentConditions['MINSELCOUNT'] = $value['torneo_detalle.valor'];
                break;
            case 'MINSELPRICE':
                $tournamentConditions['MINSELPRICE'] = $value['torneo_detalle.valor'];
                break;
            case 'MINSELPRICETOTAL':
                $tournamentConditions['MINSELPRICETOTAL'] = $value['torneo_detalle.valor'];
                break;
            case 'ITAINMENT1':
                array_push($tournamentConditions['ITAINMENT1'], $value['torneo_detalle.valor']);
                break;
            case 'ITAINMENT3':
                array_push($tournamentConditions['ITAINMENT3'], $value['torneo_detalle.valor']);
                break;
            case 'ITAINMENT4':
                array_push($tournamentConditions['ITAINMENT4'], $value['torneo_detalle.valor']);
                break;
            case 'ITAINMENT5':
                array_push($tournamentConditions['ITAINMENT5'], $value['torneo_detalle.valor']);
                break;
            case 'ITAINMENT82':
                if (in_array($value['torneo_detalle.valor'], [1, 2])) {
                    $key = $value['torneo_detalle.valor'] === 1 ? 'ITAINMENT82COMBINE' : 'ITAINMENT82SIMPLE';
                    $tournamentConditions[$key] = true;
                }
            default:
                break;
        }


        $BonoInterno = new BonoInterno();
        $counteDetail = $tournamentConditions['MINSELPRICE'] ?: 0;

        $queryTicketDEC = "SELECT 
                                usuario.mandante,
                                usuario_mandante.pais_id,
                                usuario_mandante.usumandante_id,
                                usuario.usuario_id,
                                usuario.nombre,
                                usuario.login,
                                usuario.moneda,
                                it_ticket_enc.ticket_id,
                                it_ticket_enc.bet_mode,
                                it_ticket_enc.vlr_apuesta,
                                it_ticket_enc.vlr_premio,
                                it_ticket_enc.fecha_crea_time,
                                it_ticket_det.*
                        FROM it_ticket_det
                        INNER JOIN(SELECT it_ticket_enc.ticket_id
                                FROM it_ticket_det INNER JOIN (SELECT it_ticket_enc.ticket_id
                                FROM it_ticket_det
                                INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_ticket_det.ticket_id)
                                LEFT OUTER JOIN it_ticket_enc_info1  ON (it_ticket_enc_info1.ticket_id = it_ticket_det.ticket_id AND it_ticket_enc_info1.tipo='TORNEO')
                                INNER JOIN usuario ON (it_ticket_enc.usuario_id = usuario.usuario_id)
                                INNER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante = usuario.usuario_id AND usuario_mandante.mandante = usuario.mandante)
                                INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = usuario.usuario_id)
                        WHERE
                                it_ticket_enc_info1.it_ticket2_id IS NULL
                                AND it_ticket_det.ticket_id = {$transactionID}
                                AND ((it_ticket_enc.bet_status)) != 'T'
                                AND ((it_ticket_enc.freebet)) = '0'
                                AND ((it_ticket_enc.eliminado)) = 'N'
                                AND ((it_ticket_enc.mandante)) = {$partner}
                                AND ((usuario_perfil.perfil_id)) = 'USUONLINE') a ON (a.ticket_id = it_ticket_det.ticket_id)
                                INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_ticket_det.ticket_id)
                                -- LEFT OUTER JOIN it_ticket_enc_info1
                                --                 ON (it_ticket_enc_info1.ticket_id = it_ticket_det.ticket_id AND
                                --                     it_ticket_enc_info1.tipo = 'TORNEO')
                                INNER JOIN usuario ON (it_ticket_enc.usuario_id = usuario.usuario_id)
                                INNER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante = usuario.usuario_id AND
                                                                usuario_mandante.mandante = usuario.mandante)
                                INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = usuario.usuario_id)
                        GROUP BY it_ticket_det.ticket_id
                        HAVING min(logro) >= {$counteDetail}) b ON (b.ticket_id = it_ticket_det.ticket_id)
                                    INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_ticket_det.ticket_id)
                                    INNER JOIN usuario ON (it_ticket_enc.usuario_id = usuario.usuario_id)
                                    INNER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante = usuario.usuario_id AND
                                                                    usuario_mandante.mandante = usuario.mandante)
                                    INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = usuario.usuario_id)";

        $transactionData = json_encode($BonoInterno->execQuery('', $queryTicketDEC));
        $transactionData = json_decode($transactionData, true);

        $totalCuote = 1;

        $comparation = in_array($TorneoInterno->condicional, ['', 'NA']) ? 'OR' : $TorneoInterno->condicional;
        $BonoInterno = new BonoInterno();

        foreach ($transactionData as $key => $value) {
            $totalCuote *= $value['it_ticket_det.logro'];
        }

        if ($tournamentConditions['MINSELPRICETOTAL'] !== null && $tournamentConditions['MINSELPRICETOTAL'] > $totalCuote) {
            throw new Exception('La transaccion no cumple con la condicion precio total', 300040);
        }

        foreach ($transactionData as $key => $value) {

            if (
                oldCount($tournamentConditions['CONDPAISUSER']) > 0 &&
                !in_array($value['usuario_mandante.pais_id'], $tournamentConditions['CONDPAISUSER'])
            ) {
                throw new Exception('La transaccion no cumple con la condicion de pais', 300039);
            }

            if (oldCount($tournamentConditions['ITAINMENT1']) > 0) {
                if (
                    $comparation === 'OR' &&
                    !array_some($tournamentConditions['ITAINMENT1'], function ($data) use ($value) {
                        return $data === $value['it_ticket_det.sportid'];
                    })
                ) {
                    throw new Exception('La transaccion no cumple con la condicion de deportes', 300044);
                } else if (
                    !array_every($tournamentConditions['ITAINMENT1'], function ($data) use ($value) {
                        return $data === $value['it_ticket_det.sportid'];
                    })
                ) {
                    throw new Exception('La transaccion no cumple con la condicion de deportes', 300044);
                }
            }

            if (oldCount($tournamentConditions['ITAINMENT3']) > 0) {
                if (
                    $comparation === 'OR' &&
                    !array_some($tournamentConditions['ITAINMENT3'], function ($data) use ($value) {
                        return $data === $value['it_ticket_det.ligaid'];
                    })
                ) {
                    throw new Exception('La transaccion no cumple con la condicion de liga', 300045);
                } else if (
                    !array_every($tournamentConditions['ITAINMENT3'], function ($data) use ($value) {
                        return $data === $value['it_ticket_det.ligaid'];
                    })
                ) {
                    throw new Exception('La transaccion no cumple con la condicion de liga', 300045);
                }
            }

            if (oldCount($tournamentConditions['ITAINMENT4']) > 0) {
                if (
                    $comparation === 'OR' &&
                    !array_some($tournamentConditions['ITAINMENT4'], function ($data) use ($value) {
                        return $data === $value['it_ticket_det.matchid'];
                    })
                ) {
                    throw new Exception('La transaccion no cumple con la condicion de evento', 300046);
                } else if (
                    !array_every($tournamentConditions['ITAINMENT4'], function ($data) use ($value) {
                        return $data === $value['it_ticket_det.matchid'];
                    })
                ) {
                    throw new Exception('La transaccion no cumple con la condicion de evento', 300046);
                }
            }

            if (oldCount($tournamentConditions['ITAINMENT5']) > 0) {
                if (
                    $comparation === 'OR' &&
                    !array_some($tournamentConditions['ITAINMENT5'], function ($data) use ($value) {
                        $sportID = $value['it_ticket_det.sportid'];
                        $groupID = $value['it_ticket_det.agrupador_id'];
                        return $data === "{$sportID}M{$groupID}";
                    })
                ) {
                    throw new Exception('La transaccion no cumple con la condicion de deporte mercado', 300047);
                } else if (
                    !array_every($tournamentConditions['ITAINMENT5'], function ($data) use ($value) {
                        $sportID = $value['it_ticket_det.sportid'];
                        $groupID = $value['it_ticket_det.agrupador_id'];
                        return $data === "{$sportID}M{$groupID}";
                    })
                ) {
                    throw new Exception('La transaccion no cumple con la condicion de deporter mercado', 300047);
                }
            }

            if (!empty($tournamentConditions['LIVEORPREMATCH'])) {
                if ($tournamentConditions['LIVEORPREMATCH'] === 1 && $value['it_ticket_enc.bet_mode'] !== 'Live') {
                    throw new Exception('La transaccion no cumple con la condicion modo apuesta', 300048);
                }

                if ($tournamentConditions['LIVEORPREMATCH'] === 2 && $value['it_ticket_enc.bet_mode'] !== 'PreLive') {
                    throw new Exception('La transaccion no cumple con la condicion modo apuesta', 300048);
                }
            }

            if (!empty($tournamentConditions['ITAINMENT82COMBINE']) || !empty($tournamentConditions['ITAINMENT82SIMPLE'])) {
                if ($tournamentConditions['ITAINMENT82SIMPLE'] && oldCount($transactionData) !== 1) {
                    throw new Exception('La transaccion no cumple con la condicion apuesta simple', 300049);
                }

                if ($tournamentConditions['ITAINMENT82COMBINE'] && oldCount($transactionData) > $tournamentConditions['MINSELCOUNT']) {
                    throw new Exception('La transaccion no cumple con la condicion apuesta combinada', 300050);
                }
            }

            if (!empty($tournamentConditions['MINSELPRICE']) && $tournamentConditions['MINSELPRICE'] > 0) {
                if (floatval($tournamentConditions['MINSELPRICE']) > floatval($value['it_ticket_det.logro'])) {
                    throw new Exception('La transaccion no cumple con la condicion de precio minimo', 300051);
                }
            }

            $currentCredits = 0;
            $ticketValue = $value['it_ticket_enc.vlr_apuesta'];
            $currency = $value['usuario.moneda'];

            if (oldCount($tournamentConditions['RANK'][$value['usuario.moneda']]) > 0) {
                $currentCredits = $ticketValue >= $tournamentConditions['RANK'][$currency]['valor'] && $ticketValue <= $tournamentConditions['RANK'][$currency]['valor2'] ? $tournamentConditions['RANK'][$currency]['valor3'] : 0;
            }

            if ($currentCredits === 0) throw new Exception('La transaccion no cumple con la condicion valor apuesta', 300060);

            if ($tournamentConditions['USERSUBSCRIBE']) {
                $rules = [];

                array_push($rules, ['field' => 'usuario_torneo.torneo_id', 'data' => $TorneoInterno->torneoId, 'op' => 'eq']);
                array_push($rules, ['field' => 'usuario_torneo.usuario_id', 'data' => $value['usuairio_mandante.usumandante_id'], 'op' => 'eq']);

                $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

                $UsuarioTorneo = new UsuarioTorneo();
                $queryUserTournament = (string)$UsuarioTorneo->getUsuarioTorneosCustom('COUNT(distinct(usuario_torneo.usuario_id)) countUsers,COUNT((usuario_torneo.usutorneo_id)) countStickers', 'usuario_torneo.usutorneo_id', 'ASC', 0, 1000000, $filters, true);
                $queryUserTournament = json_decode($queryUserTournament, true);

                if ($queryUserTournament['count'][0]['.count'] == 0) throw new Exception('La transaccion no cumple con la condicion de suscripcion', 300052);
            }

            if ($linkTransaction) {

                $sqlRepeatTorunament = "SELECT * FROM usuario_torneo a WHERE a.usutorneo_id != 0 AND a.torneo_id = {$tournamentID} AND a.usuario_id = {$partner}";
                $queryData = json_encode(execQuery('', $sqlRepeatTorunament));
                $queryData = json_decode($queryData, true);

                $usuTorunementID = $sqlRepeatTorunament[0]['a.usuario_id'];

                if (oldCount($queryData) > 0) {
                    $UsuarioTorneo = new UsuarioTorneo();
                    $UsuarioTorneo->usuarioId = $value['usuario_mandante.usumandante_id'];
                    $UsuarioTorneo->torneoId = $value['torneo_interno.torneo_id'];
                    $UsuarioTorneo->posicion = 0;
                    $UsuarioTorneo->usucreaId = 0;
                    $UsuarioTorneo->usumodifId = 0;
                    $UsuarioTorneo->estado = "A";
                    $UsuarioTorneo->valor = $currentCredits;
                    $UsuarioTorneo->valorBase = $ticketValue;
                    $UsuarioTorneo->errorId = 0;
                    $UsuarioTorneo->idExterno = 0;
                    $UsuarioTorneo->mandante = 0;
                    $UsuarioTorneo->version = 0;
                    $UsuarioTorneo->apostado = $value['it_ticket_enc.vlr_apuesta'];
                    $UsuarioTorneo->codigo = 0;
                    $UsuarioTorneo->externoId = 0;

                    $UsuarioTorneoMySqlDAO = new UsuarioTorneoMySqlDAO();

                    $UsuarioTorneoMySqlDAO->insert($UsuarioTorneo);
                    $usuTorunementID = $UsuarioTorneoMySqlDAO->getTransaction()->commit();
                } else {
                    $sqlUserTournament = "UPDATE usuario_torneo SET valor = valor + {$currentCredits}, valor_base = valor_base + {$ticketValue}
                        WHERE usutorneo_id = {$usuTorunementID}";

                    $BonoInterno->execQuery('', $sqlUserTournament);
                }

                $ItTicketEncInfo1 = new ItTicketEncInfo1();
                $ItTicketEncInfo1->ticketId = $value['it_ticket_enc.ticket_id'];
                $ItTicketEncInfo1->tipo = 'TORNEO';
                $ItTicketEncInfo1->valor = $usuTorunementID;
                $ItTicketEncInfo1->fechaCrea = date('Y-m-d H:i:s');
                $ItTicketEncInfo1->fechaModif = date('Y-m-d H:i:s');
                $ItTicketEncInfo1->valor2 = $currentCredits;

                $ItTicketEncInfo1MySqlDAO = new ItTicketEncInfo1MySqlDAO();
                $ItTicketEncInfo1MySqlDAO->insert($ItTicketEncInfo1);
                $ItTicketEncInfo1MySqlDAO->getTransaction()->commit();
            }
        }
    }
}

?>