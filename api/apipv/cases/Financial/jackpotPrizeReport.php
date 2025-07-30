<?php
use Backend\dto\BonoInterno;
use Backend\dto\Helpers;
use Backend\mysql\BonoInternoMySqlDAO;


/**
 * Obtener los parámetros de la solicitud
 *
 * @param String $date_from Fecha de inicio con hora 00:00:00
 * @param String $date_to Fecha de fin con hora 23:59:59
 * @param String $playerName Nombre del jugador
 * @param String $documentType Tipo de documento
 * @param String $documentNumber Número de documento
 * @param String $id Identificación del usuario
 * @param String $jackpotId Identificación del jackpot
 * @param String $gameName Nombre del juego
 * @param String $jackpotType Tipo de jackpot
 */

$date_from = $_REQUEST['dateFrom'] . ' 00:00:00';
$date_to = $_REQUEST['dateTo']. ' 23:59:59';
$playerName = $_REQUEST['playerName'];
$documentType = $_REQUEST['documentType'];
$documentNumber = $_REQUEST['documentNumber'];
$id = $_REQUEST['id'];
$jackpotId = $_REQUEST['jackpotId'];
$gameName = $_REQUEST['gameName'];
$jackpotType = $_REQUEST['jackpotType'];
$date_from = $_REQUEST['dateFrom'] . ' 00:00:00';
$date_to = $_REQUEST['dateTo']. ' 23:59:59';
$playerName = $_REQUEST['playerName'];
$documentType = $_REQUEST['documentType'];
$documentNumber = $_REQUEST['documentNumber'];
$id = $_REQUEST['id'];
$jackpotId = $_REQUEST['jackpotId'];
$gameName = $_REQUEST['gameName'];
$jackpotType = $_REQUEST['jackpotType'];

switch ($jackpotType){
    case "1":
        $jackpotType = "CASINO";
        break;
    case "2":
        $jackpotType = "LIVECASINO";
        break;
    case "3":
        $jackpotType = "VIRTUAL";
        break;
    default: "";
    break;
}


$filters = "";


if (!empty($playerName)) {
    $Helpers = new Helpers();
    $field2 = $Helpers->set_custom_field('r.nombre');
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
    $filters .= " AND r.usuario_id IN ('{$id}')";
}

if (!empty($date_from) && !empty($date_to)) {
    $filters .= " AND bl.fecha_crea BETWEEN '{$date_from}' AND '{$date_to}'";
}

$jackpotFilters = "";

if (!empty($jackpotId)) {
    $jackpotFilters .= " AND ji.jackpot_id IN ('{$jackpotId}')";
}
if (!empty($gameName)) {
    $jackpotFilters .= " AND p.descripcion IN ('{$gameName}')";
}
if (!empty($jackpotType)) {
    $jackpotFilters .= " AND sub.tipo IN ('{$jackpotType}')";
}


$sql = "WITH jackpots_ganados AS (
SELECT
 ujg.usuario_id,
 ujg.jackpot_id,
 ujg.usujackpot_id,
 ujg.fecha_modif,
 bl.fecha_crea AS fecha_pago_jackpot,
 ji.fecha_inicio AS fecha_inicio_jackpot,
 r.nombre,
 case
  when r.tipo_doc = 'C' then 'DNI'
  when r.tipo_doc = 'P' then 'Pasaporte'
  when r.tipo_doc = 'E' then 'DNI Extranjeria'
  else 'No especificado'
 end AS Tipo_de_Identificacion,
 r.cedula
FROM
 usuariojackpot_ganador ujg
INNER JOIN bono_log bl ON
 bl.id_externo = ujg.usujackpot_id
INNER JOIN jackpot_interno ji ON
 ujg.jackpot_id = ji.jackpot_id
INNER JOIN registro r ON
 ujg.usuario_id = r.usuario_id
WHERE
 1 = 1
 $filters
 AND ujg.tipo IN ('INCOME_CASINO', 'INCOME_LIVECASINO', 'INCOME_VIRTUAL')
GROUP BY
 ujg.usuario_id,
 ujg.jackpot_id)
SELECT
 /*+ MAX_EXECUTION_TIME(300000) */
 DATE(jg.fecha_modif) AS Fecha,
 uj.jackpot_id AS 'Identificación única del Jackpot',
 jg.nombre AS 'Nombre del jugador ganador',
 jg.Tipo_de_Identificacion AS 'Tipo de documento',
 jg.cedula AS 'Cedula Número de documento',
 p.descripcion AS 'Nombre del juego',
 ROUND(SUM(tji.valor), 4) AS 'Monto total ganado',
 sub.tipo AS 'Modalidad del sistema progresivo',
 jg.fecha_pago_jackpot AS 'Fecha y hora del pago',
 (
 SELECT
  jd.valor
 FROM
  jackpot_detalle jd
 WHERE
  1 = 1
  AND jd.jackpot_id = jg.jackpot_id
  AND jd.tipo LIKE CONCAT('JACKPOTINITVALUE_', sub.tipo)) AS 'Monto inicial del sistema progresivo',
 ji.fecha_inicio AS 'Fecha de inicio del sistema progresivo'
FROM
 jackpots_ganados jg
JOIN usuario_jackpot uj ON
 jg.jackpot_id = uj.jackpot_id
JOIN jackpot_interno ji ON
 uj.jackpot_id = ji.jackpot_id
JOIN transjuego_info tji ON
 uj.usujackpot_id = tji.descripcion
JOIN transjuego_log tjl FORCE INDEX (`PRIMARY`) on
 tji.transapi_id = tjl.transjuegolog_id
JOIN transaccion_juego tj on
 tjl.transjuego_id = tj.transjuego_id
JOIN producto_mandante pm ON
 tj.producto_id = pm.prodmandante_id
JOIN producto p ON
 pm.producto_id = p.producto_id
JOIN subproveedor sub ON
 p.subproveedor_id = sub.subproveedor_id
JOIN registro r ON
 uj.usuario_id = r.usuario_id
WHERE
 1 = 1
 AND tji.tipo = 'JACKPOT'
 $jackpotFilters
GROUP BY
 uj.jackpot_id,
 p.descripcion,
 p.producto_id,
 sub.tipo;
";



$BonoInterno = new BonoInterno();
$BonointernoMySqlDAO = new BonoInternoMySqlDAO();
$transaccion = $BonointernoMySqlDAO->getTransaction();

$data = $BonoInterno->execQuery($transaccion, $sql);

$final = [];
foreach ($data as $value) {
    $array = [];

    $array["date"] = $value->{".Fecha"};
    $array["uniqueJackpotId"] = $value->{"uj.Identificación única del Jackpot"};
    $array["Id"] = $value->{"jg.Nombre del jugador ganador"};
    $array["documentType"] = $value->{"jg.Tipo de documento"};
    $array["documentNumber"] = $value->{"jg.Cedula Número de documento"};
    $array["gameName"] = $value->{"p.Nombre del juego"};
    $array["totalAmountWon"] = round($value->{".Monto total ganado"}, 2);
    $array["progressiveSystemModality"] = $value->{"sub.Modalidad del sistema progresivo"};
    $array["paymentDate"] = $value->{"jg.Fecha y hora del pago"};
    $array["initialProgressiveAmount"] = round($value->{".Monto inicial del sistema progresivo"}, 2);
    $array["progressiveStartDate"] = $value->{"ji.Fecha de inicio del sistema progresivo"};

    array_push($final,$array);
}



$response["data"] = $final;
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];