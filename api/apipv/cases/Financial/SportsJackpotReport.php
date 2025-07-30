<?php

use Backend\dto\BonoInterno;
use Backend\dto\Helpers;
use Backend\mysql\BonoInternoMySqlDAO;

/**
 * @param string $dateFrom Fecha de inicio en formato 'YYYY-MM-DD'.
 * @param string $dateTo Fecha de fin en formato 'YYYY-MM-DD'.
 * @param string $playerName Nombre del jugador.
 * @param string $documentType Tipo de documento (por ejemplo, 'C' para DNI, 'P' para Pasaporte).
 * @param string $documentNumber Número de documento.
 * @param int $id ID del usuario.
 * @param int $jackpotId ID del jackpot.
 * @param string $gameName Nombre del evento deportivo
 */

$date_from = $_REQUEST['dateFrom'] . ' 00:00:00';
$date_to = $_REQUEST['dateTo']. ' 23:59:59';
$playerName = $_REQUEST['playerName'];
$documentType = $_REQUEST['documentType'];
$documentNumber = $_REQUEST['documentNumber'];
$id = $_REQUEST['id'];

$jackpotId = $_REQUEST['jackpotId'];
$gameName = $_REQUEST['gameName'];

$date_from = $_REQUEST['dateFrom'] . ' 00:00:00';
$date_to = $_REQUEST['dateTo']. ' 23:59:59';
$playerName = $_REQUEST['playerName'];
$documentType = $_REQUEST['documentType'];
$documentNumber = $_REQUEST['documentNumber'];
$id = $_REQUEST['id'];

$jackpotId = $_REQUEST['jackpotId'];
$gameName = $_REQUEST['gameName'];

$filters = "";

if (!empty($playerName)) {
    $Helpers = new Helpers();
    $field2 = $Helpers-> set_custom_field('u.nombre');
    $filters .= "AND $field2 like '%$playerName%'";
}
if (!empty($documentType)) {
    $filters .= " AND r.tipo_doc IN ('{$documentType}')";
}
if (!empty($documentNumber)) {
    $Helpers = new Helpers();
    $documentNumber = $Helpers->encode_data_with_key($documentNumber, $_ENV['SECRET_PASSPHRASE_DOCUMENT']);
    $filters .= "AND  r.cedula = ('{$documentNumber}')";
}
if (!empty($id)) {
    $filters .= " AND u.usuario_id IN ('{$id}')";
}

if (!empty($_REQUEST['dateFrom']) && !empty($_REQUEST['dateTo'])) {
    $filters .= " AND bl.fecha_crea BETWEEN '{$date_from}' AND '{$date_to}'";
}

if (!empty($jackpotId)) {
    $filters .= " AND ujg.jackpot_id IN ('{$jackpotId}')";
}

$gameFilters = "";

if (!empty($gameName)) {
    $gameFilters .= "AND itd.apuesta IN ('{$gameName}')";
}


$sql = "WITH jackpots_ganados AS (
 SELECT
  ujg.usuario_id,
  ujg.jackpot_id,
  usujackpot_id,
  bl.fecha_crea AS fecha_pago_jackpot,
  jd.valor AS valor_inicial_jackpot,
  ji.fecha_inicio AS fecha_inicio_jackpot,
  u.nombre,
  case
   when r.tipo_doc = 'C' then 'DNI'
   when r.tipo_doc = 'P' then 'Pasaporte'
   when r.tipo_doc = 'E' then 'DNI Extranjeria'
   else 'No especificado'
  end AS Tipo_de_Identificacion,
  r.cedula
 FROM
  usuariojackpot_ganador ujg
 INNER JOIN bono_log bl FORCE INDEX (`idx_bono_log_id_externo`)
                                               ON
  bl.id_externo = ujg.usujackpot_id
 INNER JOIN jackpot_detalle jd ON
  ujg.jackpot_id = jd.jackpot_id
 INNER JOIN jackpot_interno ji ON
  ujg.jackpot_id = ji.jackpot_id
 INNER JOIN usuario u ON
  ujg.usuario_id = u.usuario_id
 INNER JOIN registro r ON
  u.usuario_id = r.usuario_id
 WHERE
  1 = 1
  $filters
      AND jd.tipo = 'JACKPOTINITVALUE_SPORTBOOK'
     AND ujg.tipo = 'INCOME_SPORTBOOK'
     AND bl.tipo = 'JD'
  #AND u.pais_id = 173 AND u.mandante = 0
  #AND ujg.jackpot_id = 2253
  #AND u.nombre = 'Nombre usuario'
  #AND u.usuario_id = 1232
  #AND r.tipo_doc = 'C'
                           ),
      tickets_aportaron AS (
 SELECT
  DATE(fecha_pago_jackpot) AS fecha_pago,
  jg.jackpot_id,
  jg.nombre AS nombre,
  jg.usuario_id,
  Tipo_de_Identificacion,
  cedula,
 1 AS eventos,
  valor2 AS monto_total_ganado,
  'Sportbook' AS modalidad,
  fecha_pago_jackpot AS fecha_hora_pago,
  valor_inicial_jackpot,
  ite.ticket_id,
  fecha_inicio_jackpot
  #it_ticketdet_id
 FROM
  jackpots_ganados jg
 INNER JOIN usuario_jackpot uj ON
  jg.jackpot_id = uj.jackpot_id
 INNER JOIN it_ticket_enc_info1 iti ON
  uj.usujackpot_id = iti.valor
 INNER JOIN it_ticket_enc ite FORCE INDEX (`ticket_id_usuario_id_idx`)
                                                ON
  CONCAT('', iti.ticket_id) = ite.ticket_id
   AND
                                                   uj.usuario_id = ite.usuario_id
  WHERE
   1 = 1
   AND iti.tipo = 'JACKPOT')
 SELECT
  ta.fecha_pago,
  jackpot_id,
  usuario_id,
  nombre,
  Tipo_de_Identificacion,
  cedula,
  apuesta,
  monto_total_ganado,
  modalidad,
  fecha_hora_pago,
  valor_inicial_jackpot,
  fecha_inicio_jackpot
 FROM
  tickets_aportaron ta
 INNER JOIN it_ticket_det itd ON
  ta.ticket_id = itd.ticket_id
  WHERE 
  1 = 1
  $gameFilters";





$BonoInterno = new BonoInterno();
$BonointernoMySqlDAO = new BonoInternoMySqlDAO();
$transaccion = $BonointernoMySqlDAO->getTransaction();

$data = $BonoInterno->execQuery($transaccion, $sql);


$final = [];

foreach ($data as $value) {
    $array = [];

    $array["date"] = $value->{'ta.fecha_pago'};
    $array["uniqueJackpotId"] = $value->{'ta.jackpot_id'};
    $array["Id"] = $value->{'ta.nombre'};
    $array["documentType"] = $value->{'ta.Tipo_de_Identificacion'};
    $array["documentNumber"] = $value->{'ta.cedula'};
    $array["leagueOrSportingEvent"] = $value->{'itd.apuesta'};
    $array["totalAmountWon"] = round($value->{'ta.monto_total_ganado'}, 2);
    $array["progressiveSystemModality"] = $value->{'ta.modalidad'};
    $array["paymentDateAndTime"] = $value->{'ta.fecha_hora_pago'};
    $array["initialProgressiveAmount"] = round($value->{'ta.valor_inicial_jackpot'}, 2);
    $array["startDateOfTheProgressiveSystem"] = $value->{'ta.fecha_inicio_jackpot'};

    array_push($final, $array);

}


// Asignar los datos finales al array de respuesta
$response["data"] = $final;
$response["HasError"] = false; // Indica que no hubo errores
$response["AlertType"] = "success"; // Tipo de alerta
$response["AlertMessage"] = ""; // Mensaje de alerta vacío
$response["ModelErrors"] = []; // Array de errores del modelo vacío