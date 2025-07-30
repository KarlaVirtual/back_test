<?php

/**
 * Reporte de casino en la sección de Reportes de Regulación del BO, 
 * llamado "Estructura del Reporte para Juegos", exclusivo para el ente regulador de Perú.
 * 
 * @author David Alvarez <juan.alvarez@virtualsoft.tech>
 * @since 17/02/2025
 */

use Backend\mysql\TransaccionJuegoMySqlDAO;

try {
  
  $limit = isset($_REQUEST["count"]) ? $_REQUEST["count"] : 1000;
  $start = isset($_REQUEST["start"]) ? $_REQUEST["start"] : 0;
  $period_time = isset($_REQUEST['period_time']) ? $_REQUEST['period_time'] : null;
  $user_name = isset($_REQUEST['user_name']) ? $_REQUEST['user_name'] : null;
  $document_type = isset($_REQUEST['document_type']) ? $_REQUEST['document_type'] : null;
  $document_number = isset($_REQUEST['document_number']) ? $_REQUEST['document_number'] : null;
  $user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : null;
  $partner = $_SESSION['mandante'] ?? null;
  $country = isset($_REQUEST['Country']) ? $_REQUEST['Country'] : null;

  $period_time = date('Y-m-d', strtotime($period_time));
  
  $transaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
  
  $data = $transaccionJuegoMySqlDAO->getReportStructureGames(
    $start,
    $limit,
    $period_time,
    $user_name,
    $document_type,
    $document_number,
    $user_id,
    $partner,
    $country
  );

  $dataResponse = [];
  foreach ($data['data'] as $row) {
    $value = [];
    $value['Date'] = $row['fecha'];
    $value['User_id'] = $row['usuario_id'];
    $value['User_name'] = $row['usuario_nombre'];
    $value['Game_name'] = $row['juego'];
    $value['TypeGame'] = $row['tipo_juego'];
    $value['Bet_id'] = $row['apuesta_id'];
    $value['Transaction_date'] = $row['fecha_transaccion'];
    $value['Amount'] = $row['monto_transaccion'];
    $value['Type_transaction'] = $row['tipo_transaccion'];
    $value['state'] = $row['estado'];

    $dataResponse[] = $value;
  }

  $response = [];
  $response['HasError'] = false;
  $response['AlertType'] = 'success';
  $response['AlertMessage'] = '';
  $response['ModelErrors'] = [];
  $response['total_count'] = $data['total_count'];
  $response['pos'] = $start;
  
  $response['data'] = $dataResponse;

} catch (\Throwable $th) {

  $response = [];
  $response["HasError"] = true;
  $response["AlertType"] = "danger";
  $response["AlertMessage"] = $th->getMessage();
  $response["CodeError"] = $th->getCode();
}