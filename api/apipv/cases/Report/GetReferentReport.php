<?php

use Backend\dto\Usuario;
use Backend\dto\Clasificador;
use Backend\dto\LogroReferido;
use Backend\dto\UsuarioReferenteResumen;
use Backend\sql\Transaction;
use Backend\dto\BonoInterno;
use Backend\dto\UsuarioMandante;
use Backend\dto\Registro;

/**
 * Report/GetReferentReport
 * 
 * Obtiene el reporte de referidos de un usuario referente
 *
 * @param int $PlayerId            ID del usuario referente (opcional)
 * @param int $UserId              ID del usuario referido
 * @param int $start               Posición inicial para paginación
 * @param int $count               Cantidad de registros a retornar
 * @param string $dateFrom         Fecha inicial de registro (Y-m-d)
 * @param string $dateTo           Fecha final de registro (Y-m-d)
 * @param string $dateFrom2        Fecha inicial primer depósito (Y-m-d)
 * @param string $dateTo2          Fecha final primer depósito (Y-m-d)
 * @param int $Type                Tipo de consulta
 * @param string $State            Estado del referido
 * @param int $CountrySelect       ID del país a filtrar
 * @param string $partner          Código de socio
 * 
 * @return array {
 *   "HasError": boolean,          // Indica si hubo error
 *   "AlertType": string,          // Tipo de alerta (success, error)
 *   "AlertMessage": string,       // Mensaje descriptivo
 *   "ModelErrors": array,         // Errores del modelo
 *   "total_count": int,           // Total de registros encontrados
 *   "pos": int,                   // Posición actual en la paginación
 *   "data": array                 // Lista de referidos encontrados
 * }
 */


//Parámetros recibidos
$referent = $_GET['PlayerId'] ?? '0';
$referred = $_GET['UserId'];
$start = $_GET['start'] ?? 0;
$count = $_GET['count'] ?? 10;
$registerDateFrom = $_GET['dateFrom'];
$registerDateTo = $_GET['dateTo'];
$firstDepositDateFrom = $_GET['dateFrom2'];
$firstDepositDateTo = $_GET['dateTo2'];
$queryMode = $_GET['Type'];
$state = $_GET['State'];
$country = $_GET['CountrySelect'];
$partner = $_GET['partner'];
$Transaction = new Transaction();

// Obtiene la última fecha de ejecución del CRON de resúmenes de referidos
$BonoInterno = new BonoInterno();
$sql = "select fecha_ultima from proceso_interno2 where tipo = 'REFERIDO_RESUMEN'";
$lastCronExecution = $BonoInterno->execQuery($Transaction, $sql);
$lastCronExecution = $lastCronExecution[0]->{'proceso_interno2.fecha_ultima'};

// Obtiene información del operador actual
$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

// Configura parámetros iniciales y fechas
$position = $start;
$currentDateStart = date('Y-m-d 00:00:01', strtotime($lastCronExecution ?? date('Y-m-d')));
$currentDateEnd = date('Y-m-d 23:59:59');
$country = $country ?? ($_SESSION['PaisCondS'] ?? $_SESSION['pais_id']);

// Formatea las fechas de los filtros al formato correcto
if (!empty($registerDateFrom)) $registerDateFrom = date('Y-m-d 00:00:00', strtotime($registerDateFrom));
if (!empty($registerDateTo)) $registerDateTo = date('Y-m-d 23:59:59', strtotime($registerDateTo));
if (!empty($firstDepositDateFrom)) $firstDepositDateFrom = date('Y-m-d 00:00:00', strtotime($firstDepositDateFrom));
if (!empty($firstDepositDateTo)) $firstDepositDateTo = date('Y-m-d 23:59:59', strtotime($firstDepositDateTo));

if ($queryMode == 0) {
    // Modo 0: Reporte detallado de referidos para un referente específico
    try {
        $Usuario = new Usuario($referent);
    } catch (Exception $e) {
        if ($e->getCode() != 24) throw $e;
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];
        $response['total_count'] = 0;
        $response['pos'] = $position;
        $response['data'] = [];
        return;
    }

    // Construye la consulta SQL con los filtros aplicados
    $where = " where 1 = 1 ";
    $where .= " AND c.usuid_referente = $referent";

    if ($referred != '') {
        $where .= " AND usuario.usuario_id = $referred";
    }

    if ($registerDateFrom != '') {
        $where .= " AND usuario.fecha_crea >= '$registerDateFrom' ";
    }

    if ($registerDateTo != '') {
        $where .= " AND usuario.fecha_crea <= '$registerDateTo' ";
    }

    // Continúa construyendo los filtros SQL
    if ($firstDepositDateFrom != '') {
        $where .= " AND data_completa2.fecha_primer_deposito >= '$firstDepositDateFrom' ";
    }

    if ($firstDepositDateTo != '') {
        $where .= " AND data_completa2.fecha_primer_deposito <= '$firstDepositDateTo' ";
    }

    if ($state !== '' && $state !== null) {
        $state = $state == 1 ? 'A' : 'I';
        $where .= " AND usuario.estado = '$state' ";
    }

    // Define las consultas SQL para obtener datos y conteo
    $selectCount = "SELECT COUNT(*)  AS count ";
    $select = "SELECT usuario.usuario_id, usuario.fecha_crea, usuario.estado, data_completa2.ultimo_inicio_sesion, data_completa2.fecha_primer_deposito, data_completa2.monto_primer_deposito, data_completa2.fecha_ultima_apuestadeportivas, data_completa2.fecha_ultima_apuestacasino, c.usuid_referente, c.referente_avalado";
    $from = " FROM usuario inner join registro on (usuario.usuario_id = registro.usuario_id) inner join usuario_otrainfo c on (usuario.usuario_id = c.usuario_id) inner join usuario_mandante on (usuario.usuario_id = usuario_mandante.usuario_mandante) left join data_completa2 on (usuario_mandante.usumandante_id = data_completa2.usuario_id)";
    $sidx = ' ORDER BY usuario.usuario_id DESC';
    $limit = " LIMIT $start, $count";

    // Ejecuta las consultas SQL
    $sql = $select . $from . $where . $sidx . $limit;
    $sqlCount = $selectCount . $from . $where;
    $returnedUsersCount = $BonoInterno->execQuery($Transaction, $sqlCount);
    $returnedUsers = $BonoInterno->execQuery($Transaction, $sql);

    // Procesa los resultados para cada usuario referido
    $infoReferreds = [];
    foreach ($returnedUsers as $returnedUser) {
        $Registro = new Registro('', $returnedUser->{'usuario.usuario_id'});

        // Construye objeto con información básica del referido
        $infoReferred = (object)[];
        $infoReferred->ReferralId = $returnedUser->{'usuario.usuario_id'};
        $infoReferred->DateRegister = $returnedUser->{'usuario.fecha_crea'};
        $infoReferred->Name = $Registro->nombre1 . ' ' . $Registro->nombre2;
        $infoReferred->Lastname = $Registro->apellido1 . ' ' . $Registro->apellido2;
        $infoReferred->Status = $returnedUser->{'usuario.estado'};
        $infoReferred->DateLastLogin = $returnedUser->{'data_completa2.ultimo_inicio_sesion'};
        $infoReferred->Referrer = $returnedUser->{'c.usuid_referente'};
        $infoReferred->DateFirstDeposit = $returnedUser->{'data_completa2.fecha_primer_deposito'};
        $infoReferred->AmountFirstDeposit = $returnedUser->{'data_completa2.monto_primer_deposito'};
        $infoReferred->DateLastSportBet = $returnedUser->{'data_completa2.fecha_ultima_apuestadeportivas'};
        $infoReferred->DateLastCasinoBet = $returnedUser->{'data_completa2.fecha_ultima_apuestacasino'};

        // Consulta los premios ganados por el referido
        $LogroReferido = new LogroReferido();
        $conditions = ['CONDMINFIRSTDEPOSITREFERRED', 'CONDMINBETREFERRED'];

        foreach ($conditions as $condition) {
            $count = 0;
            $Clasificador = new Clasificador('', $condition);
            $rules = [];
            array_push($rules, ['field' => 'logro_referido.usuid_referido', 'data' => $returnedUser->{'usuario.usuario_id'}, 'op' => 'eq']);
            array_push($rules, ['field' => 'mandante_detalle_condicion.tipo', 'data' => $Clasificador->getClasificadorId(), 'op' => 'eq']);
            array_push($rules, ['field' => 'logro_referido.estado_grupal', 'data' => '"C","R","PE"', 'op' => 'in']);
            $select = 'logro_referido.logroreferido_id';
            $filters = ['rules' => $rules, 'groupOp' => 'AND'];

            $achievedAward = $LogroReferido->getLogroReferidoCustom($select, 'logro_referido.logroreferido_id', 'DESC', 0, 1, json_encode($filters), true, true);
            $achievedAward = json_decode($achievedAward);
            $count = $achievedAward->count[0]->{'0'} ?? '0';

            if ($condition == 'CONDMINFIRSTDEPOSITREFERRED') $infoReferred->FirstDepositAward = (int) $count > 0 ? 1 : 0;
            if ($condition == 'CONDMINBETREFERRED') $infoReferred->BettingAward = (int) $count > 0 ? 1 : 0;
        }

        // Consulta el total de referidos si el usuario es referente avalado
        if ($returnedUser->{'c.referente_avalado'} == 1) {
            $UsuarioReferenteResumen = new UsuarioReferenteResumen();
            $rules = [];
            array_push($rules, ['field' => 'usuario_referente_resumen.tipo_usuario', 'data' => 'REFERENTE', 'op' => 'eq']);
            array_push($rules, ['field' => 'usuotrainfo_referente.usuario_id', 'data' => $returnedUser->{'usuario.usuario_id'}, 'op' => 'eq']);
            array_push($rules, ['field' => 'usuario_referente_resumen.tipo', 'data' => 'NEWREFERRED', 'op' => 'eq']);

            $groupBy = 'usuario_referente_resumen.usuario_id';
            $select = 'usuario_referente_resumen.usuario_id, sum(usuario_referente_resumen.valor) as total_referidos';
            $filters = ['rules' => $rules, 'groupOp' => 'AND'];

            $totalReferrals = $UsuarioReferenteResumen->getUsuarioReferenteResumenCustom($select, 'usuario_referente_resumen.usuario_id', 'DESC', 0, 1, json_encode($filters), true, $groupBy);
            $totalReferrals = json_decode($totalReferrals)->data[0] ?? 0;

            // Consulta referidos nuevos del día actual
            $sql = "select referente.usuario_id, count(referido.usuario_id) totalNewReferreds from usuario_otrainfo as referido_otrainfo inner join usuario as referido on (referido_otrainfo.usuario_id = referido.usuario_id) inner join usuario as referente on (referido_otrainfo.usuid_referente = referente.usuario_id) where referido_otrainfo.usuid_referente = " . $returnedUser->{'usuario.usuario_id'} . " and referido_otrainfo.usuid_referente is not null and referido_otrainfo.usuid_referente != '' and referido.fecha_crea between '" . $currentDateStart . "' and '" . $currentDateEnd . "' group by referente.usuario_id";

            $BonoInterno = new BonoInterno();
            $totalNewReferreds = $BonoInterno->execQuery($Transaction, $sql);
            $totalNewReferreds = $totalNewReferreds[0]->{'.totalNewReferreds'} ?? 0;

            $infoReferred->TotalReferrals = $totalReferrals->{'.total_referidos'} + $totalNewReferreds;
        } else $infoReferred->TotalReferrals = '0';

        // Consulta saldos apostados en deportivas y casino
        $needleBetTypes = ['CASINOBETSVALUE', 'SPORTBOOKBETSVALUE'];
        $needleBetTypesString = array_reduce($needleBetTypes, function ($carry, $betType) {
            return $carry .= !$carry == '' ? ",'$betType'" : "'$betType'";
        }, '');

        $UsuarioReferenteResumen = new UsuarioReferenteResumen();
        $rules = [];
        array_push($rules, ['field' => 'usuario_referente_resumen.tipo_usuario', 'data' => 'REFERIDO', 'op' => 'eq']);
        array_push($rules, ['field' => 'usuario_referente_resumen.tipo', 'data' => $needleBetTypesString, 'op' => 'in']);
        array_push($rules, ['field' => 'usuario_referente_resumen.usuario_id', 'data' => $returnedUser->{'usuario.usuario_id'}, 'op' => 'eq']);

        $filters = ['rules' => $rules, 'groupOp' => 'AND'];
        $groupBy = 'usuario_referente_resumen.usuario_id,usuario_referente_resumen.tipo';
        $select = 'usuario_referente_resumen.usuario_id, usuario_referente_resumen.tipo, sum(usuario_referente_resumen.valor) as total';
        $sidx = 'usuario_referente_resumen.usuario_id';

        $referredStats = $UsuarioReferenteResumen->getUsuarioReferenteResumenCustom($select, $sidx, 'DESC', 0, 2, json_encode($filters), true, $groupBy);
        $referredStats = json_decode($referredStats)->data;

        foreach ($needleBetTypes as $betType) {
            $summary = array_filter($referredStats, function ($stat) use ($betType) {
                if ($stat->{'usuario_referente_resumen.tipo'} == $betType) return $stat;
            });
            $summary = array_values($summary);

            if ($betType == 'CASINOBETSVALUE') $infoReferred->AmountCasinoBet = !empty($summary) ? $summary[0]->{'.total'} : '0';
            if ($betType == 'SPORTBOOKBETSVALUE') $infoReferred->AmountSportBet = !empty($summary) ? $summary[0]->{'.total'} : '0';
        }

        array_push($infoReferreds, $infoReferred);
    }

    // Prepara la respuesta con los datos obtenidos
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];
    $response['total_count'] = $returnedUsersCount[0]->{'.count'};
    $response['pos'] = $position;
    $response['data'] = $infoReferreds;
}
elseif ($queryMode == 1) {
    // Modo 1: Reporte de totales para referentes
    $Usuario = new Usuario();
    $partner = $_SESSION['mandante'] ?? $UsuarioMandante->mandante;

    // Construye consulta SQL con filtros para referentes
    $where = " where 1 = 1 ";
    $where .= " AND c.referente_avalado = 1 ";

    if ($partner != -1 && $partner != '') {
        $where .= " AND usuario_mandante.mandante = $partner";
    }

    if ($country != '') {
        $where .= " AND usuario_mandante.pais_id = $country";
    }

    if ($referent != '' && $referent != 0) {
        $where .= " AND usuario.usuario_id = $referent";
    }

    // Aplica filtros de fechas
    if ($registerDateFrom != '') {
        $where .= " AND usuario.fecha_crea >= '$registerDateFrom' ";
    }

    if ($registerDateTo != '') {
        $where .= " AND usuario.fecha_crea <= '$registerDateTo' ";
    }

    if ($firstDepositDateFrom != '') {
        $where .= " AND data_completa2.fecha_primer_deposito >= '$firstDepositDateFrom' ";
    }

    if ($firstDepositDateTo != '') {
        $where .= " AND data_completa2.fecha_primer_deposito <= '$firstDepositDateTo' ";
    }

    if ($state !== '' && $state !== null) {
        $state = $state == 1 ? 'A' : 'I';
        $where .= " AND usuario.estado = '$state' ";
    }

    // Define y ejecuta consultas SQL para obtener referentes
    $selectCount = "SELECT COUNT(*)  AS count ";
    $select = 'SELECT usuario.usuario_id';
    $from = " FROM usuario inner join registro on (usuario.usuario_id = registro.usuario_id) inner join usuario_otrainfo c on (usuario.usuario_id = c.usuario_id) inner join usuario_mandante on (usuario.usuario_id = usuario_mandante.usuario_mandante) left join data_completa2 on (usuario_mandante.usumandante_id = data_completa2.usuario_id)";
    $sidx = ' ORDER BY usuario.usuario_id DESC ';
    $limit = " LIMIT $start, $count ";

    $sql = $select . $from . $where . $sidx . $limit;
    $sqlCount = $selectCount . $from . $where;

    $returnedUsersCount = $BonoInterno->execQuery($Transaction, $sqlCount);
    $returnedUsers = $BonoInterno->execQuery($Transaction, $sql);

    // Procesa cada referente encontrado
    $infoReferents = [];
    $UsuarioReferenteResumen = new UsuarioReferenteResumen();
    foreach ($returnedUsers as $returnedUser) {
        $infoReferent = (object)[];
        $Registro = new Registro('', $returnedUser->{'usuario.usuario_id'});

        $infoReferent->id = $returnedUser->{'usuario.usuario_id'};
        $infoReferent->name = $Registro->nombre;

        //Consultando referidos totales del usuario en resúmenes -- NEWREFERRED
        $rules = [];
        array_push($rules, ['field' => 'usuario_referente_resumen.tipo_usuario', 'data' => 'REFERENTE', 'op' => 'eq']);
        array_push($rules, ['field' => 'usuario_referente_resumen.tipo', 'data' => 'NEWREFERRED', 'op' => 'eq']);
        array_push($rules, ['field' => 'usuario_referente_resumen.usuario_id', 'data' => $returnedUser->{'usuario.usuario_id'}, 'op' => 'eq']);

        $filters = ['rules' => $rules, 'groupOp' => 'AND'];
        $groupBy = 'usuario_referente_resumen.usuario_id';
        $select = 'usuario_referente_resumen.usuario_id, sum(usuario_referente_resumen.valor) as total';
        $sidx = 'usuario_referente_resumen.usuario_id';

        $newReferredTotals = $UsuarioReferenteResumen->getUsuarioReferenteResumenCustom($select, $sidx, 'DESC', 0, 1, json_encode($filters), true, $groupBy);
        $newReferredTotals = json_decode($newReferredTotals)->data[0];

        // Construye condiciones WHERE para filtros de fecha
        $where=' 1=1 AND ';

        if ($registerDateFrom != '') {
            $where .= "  referido.fecha_crea >= '$registerDateFrom' AND ";
        }

        if ($registerDateTo != '') {
            $where .= "  referido.fecha_crea <= '$registerDateTo' AND ";
        }

        if ($firstDepositDateFrom != '') {
            $where .= "  data_completa2.fecha_primer_deposito >= '$firstDepositDateFrom' AND ";
        }

        if ($firstDepositDateTo != '') {
            $where .= "  data_completa2.fecha_primer_deposito <= '$firstDepositDateTo' AND ";
        }

        // Consulta referidos nuevos del día
        $sql = "select referente.usuario_id, count(distinct (referido.usuario_id)) totalNewReferreds from usuario_otrainfo as referido_otrainfo inner join usuario as referido on (referido_otrainfo.usuario_id = referido.usuario_id) inner join usuario as referente on (referido_otrainfo.usuid_referente = referente.usuario_id)  inner join usuario_mandante on (referido.usuario_id = usuario_mandante.usuario_mandante)  left join data_completa2 on (usuario_mandante.usumandante_id = data_completa2.usuario_id) where ".$where." referido_otrainfo.usuid_referente = " . $returnedUser->{'usuario.usuario_id'} . " and referido_otrainfo.usuid_referente is not null and referido_otrainfo.usuid_referente != ''  group by referente.usuario_id";
        $where='';
        $BonoInterno = new BonoInterno();
        $totalNewReferreds = $BonoInterno->execQuery($Transaction, $sql);

        $totalNewReferreds = $totalNewReferreds[0]->{'.totalNewReferreds'};

        $infoReferent->TotalReferrals = $totalNewReferreds;

        // Consulta total de referidos exitosos
        $rules = [];
        array_push($rules, ['field' => 'usuario_referente_resumen.tipo_usuario', 'data' => 'REFERENTE', 'op' => 'eq']);
        array_push($rules, ['field' => 'usuario_referente_resumen.tipo', 'data' => 'NEWSUCCESSFULREFERRED', 'op' => 'eq']);
        array_push($rules, ['field' => 'usuario_referente_resumen.usuario_id', 'data' => $returnedUser->{'usuario.usuario_id'}, 'op' => 'eq']);

        $filters = ['rules' => $rules, 'groupOp' => 'AND'];
        $groupBy = 'usuario_referente_resumen.usuario_id';
        $select = 'usuario_referente_resumen.usuario_id, sum(usuario_referente_resumen.valor) as total';
        $sidx = 'usuario_referente_resumen.usuario_id';

        $newReferredTotals = $UsuarioReferenteResumen->getUsuarioReferenteResumenCustom($select, $sidx, 'DESC', 0, 1, json_encode($filters), true, $groupBy);
        $newReferredTotals = json_decode($newReferredTotals)->data[0];

        // Consulta referidos exitosos del día
        $sql = "select count(*) totalNuevosPremios, usuario_otrainfo.usuid_referente from (select min(fecha_uso) min_fecha_uso, usuid_referido,referido.usuid_referente, tipo_premio, valor_premio, abreviado from usuario_otrainfo as referido inner join logro_referido on (referido.usuario_id = logro_referido.usuid_referido) inner join mandante_detalle on (mandante_detalle.manddetalle_id = logro_referido.tipo_condicion) inner join clasificador on (mandante_detalle.tipo = clasificador.clasificador_id) where estado_grupal in ('R', 'C', 'PE') and logro_referido.usuid_referente = " . $returnedUser->{'usuario.usuario_id'} . " group by usuid_referido having min_fecha_uso between '" . $currentDateStart . "' and '" . $currentDateEnd . "') as nuevoPremio inner join usuario_otrainfo on (nuevoPremio.usuid_referido = usuario_otrainfo.usuario_id) group by usuario_otrainfo.usuid_referente";
        $BonoInterno = new BonoInterno();
        $newSuccessFulReferred = $BonoInterno->execQuery($Transaction, $sql);

        $newSuccessFulReferred = $newSuccessFulReferred[0]->{'.totalNuevosPremios'};

        $infoReferent->SuccessfulReferrals = $newReferredTotals->{'.total'} ?? '0';
        $infoReferent->SuccessfulReferrals += $newSuccessFulReferred;

        // Consulta bonos por depósito redimidos
        $rules = [];
        array_push($rules, ['field' => 'usuario_referente_resumen.tipo_usuario', 'data' => 'REFERENTE', 'op' => 'eq']);
        array_push($rules, ['field' => 'usuario_referente_resumen.tipo', 'data' => 'SUCCESSFULCONDITION', 'op' => 'eq']);
        array_push($rules, ['field' => 'usuario_referente_resumen.tipo_condicion', 'data' => 'CONDMINFIRSTDEPOSITREFERRED', 'op' => 'eq']);
        array_push($rules, ['field' => 'usuario_referente_resumen.usuario_id', 'data' => $returnedUser->{'usuario.usuario_id'}, 'op' => 'eq']);

        $filters = ['rules' => $rules, 'groupOp' => 'AND'];
        $groupBy = 'usuario_referente_resumen.usuario_id';
        $select = 'usuario_referente_resumen.usuario_id, sum(usuario_referente_resumen.valor) as total';
        $sidx = 'usuario_referente_resumen.usuario_id';

        $newReferredTotals = $UsuarioReferenteResumen->getUsuarioReferenteResumenCustom($select, $sidx, 'DESC', 0, 1, json_encode($filters), true, $groupBy);
        $newReferredTotals = json_decode($newReferredTotals)->data[0];

        // Consulta bonos por depósito del día
        $sql = "select logro_referido.usuid_referente, abreviado, count(*) as totalCondicion from usuario_otrainfo as referido inner join logro_referido on (referido.usuario_id = logro_referido.usuid_referido) inner join mandante_detalle on (mandante_detalle.manddetalle_id = logro_referido.tipo_condicion) inner join clasificador on (mandante_detalle.tipo = clasificador.clasificador_id) where logro_referido.fecha_uso between '" . $currentDateStart . "' and '" . $currentDateEnd . "' and logro_referido.usuid_referente = " . $returnedUser->{'usuario.usuario_id'} . " and abreviado = 'CONDMINFIRSTDEPOSITREFERRED' and estado_grupal in ('R', 'C', 'PE') group by logro_referido.usuid_referente, abreviado";
        $BonoInterno = new BonoInterno();
        $newSuccessfulConditionsFirstDeposit = $BonoInterno->execQuery($Transaction, $sql);

        $newSuccessfulConditionsFirstDeposit = $newSuccessfulConditionsFirstDeposit[0]->{'.totalCondicion'};

        $infoReferent->DepositBonuses = $newReferredTotals->{'.total'} ?? '0';
        $infoReferent->DepositBonuses += $newSuccessfulConditionsFirstDeposit;

        // Consulta bonos por apuestas redimidos
        $rules = [];
        array_push($rules, ['field' => 'usuario_referente_resumen.tipo_usuario', 'data' => 'REFERENTE', 'op' => 'eq']);
        array_push($rules, ['field' => 'usuario_referente_resumen.tipo', 'data' => 'SUCCESSFULCONDITION', 'op' => 'eq']);
        array_push($rules, ['field' => 'usuario_referente_resumen.tipo_condicion', 'data' => 'CONDMINBETREFERRED', 'op' => 'eq']);
        array_push($rules, ['field' => 'usuario_referente_resumen.usuario_id', 'data' => $returnedUser->{'usuario.usuario_id'}, 'op' => 'eq']);

        $filters = ['rules' => $rules, 'groupOp' => 'AND'];
        $groupBy = 'usuario_referente_resumen.usuario_id';
        $select = 'usuario_referente_resumen.usuario_id, sum(usuario_referente_resumen.valor) as total';
        $sidx = 'usuario_referente_resumen.usuario_id';

        $newReferredTotals = $UsuarioReferenteResumen->getUsuarioReferenteResumenCustom($select, $sidx, 'DESC', 0, 1, json_encode($filters), true, $groupBy);
        $newReferredTotals = json_decode($newReferredTotals)->data[0];

        // Consulta bonos por apuestas del día
        $sql = "select logro_referido.usuid_referente, abreviado, count(*) as totalCondicion from usuario_otrainfo as referido inner join logro_referido on (referido.usuario_id = logro_referido.usuid_referido) inner join mandante_detalle on (mandante_detalle.manddetalle_id = logro_referido.tipo_condicion) inner join clasificador on (mandante_detalle.tipo = clasificador.clasificador_id) where logro_referido.fecha_uso between '" . $currentDateStart . "' and '" . $currentDateEnd . "' and logro_referido.usuid_referente = " . $returnedUser->{'usuario.usuario_id'} . " and abreviado = 'CONDMINBETREFERRED' and estado_grupal in ('R', 'C','PE') group by logro_referido.usuid_referente, abreviado";
        $BonoInterno = new BonoInterno();
        $newSuccessfulConditionsMinBet = $BonoInterno->execQuery($Transaction, $sql);

        $newSuccessfulConditionsMinBet = $newSuccessfulConditionsMinBet[0]->{'.totalCondicion'};

        $infoReferent->BetBonuses = $newReferredTotals->{'.total'} ?? '0';
        $infoReferent->BetBonuses += $newSuccessfulConditionsMinBet;

        array_push($infoReferents, $infoReferent);
    }

    // Prepara la respuesta con los totales
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];
    $response['pos'] = (string) $position;
    $response['total_count'] = $returnedUsersCount[0]->{'.count'};
    $response['data'] = $infoReferents;
}
else {
    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = "Especifique tipo de consulta deseada";
    $response["ModelErrors"] = [];
}