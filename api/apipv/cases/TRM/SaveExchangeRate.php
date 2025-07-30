<?php

/**
 * Recurso para gestionar las tasas de cambio
 * 
 * @author David Alvarez <juan.alvarez@virtualsoft.tech>
 * @since 2025-02-03
 */

use Backend\dto\TasaCambio;
use Backend\mysql\AuditoriaGeneralMySqlDAO;
use Backend\mysql\TasaCambioMySqlDAO;

$idTasaCambio = $params->Id ?? null;
$partner = $params->Partner;
$sourceCurrency = $params->SourceCurrency;
$destinationCurrency = $params->DestinationCurrency;
$exchangeRate = $params->ExchangeRate;
$status = $params->Status;
$userId = $_SESSION['usuario2'];

try {
  $tasaCambio = $idTasaCambio ? new TasaCambio($idTasaCambio) : new TasaCambio();
  $isNew = empty($idTasaCambio);

  if ($isNew) validateDuplication($sourceCurrency, $destinationCurrency);

  $cambios = compararValores($tasaCambio, [
    'monedaOrigen' => $sourceCurrency,
    'monedaDestino' => $destinationCurrency,
    'tasaCambio' => $exchangeRate,
    'estado' => $status,
  ]);

  $tasaCambio->mandante = $partner;
  $tasaCambio->monedaOrigen = $sourceCurrency;
  $tasaCambio->monedaDestino = $destinationCurrency;
  $tasaCambio->tasaCambio = $exchangeRate;
  $tasaCambio->estado = $status;
  $tasaCambio->{$isNew ? 'fechaCrea' : 'fechaModif'} = date('Y-m-d H:i:s');
  $tasaCambio->{$isNew ? 'insert' : 'update'}();

  registrarAuditoria($userId, $isNew, $tasaCambio->id, $cambios, $tasaCambio->getTransaction());
  $tasaCambio->getTransaction()->commit();

  $response = [
    "HasError" => false,
    "AlertType" => "success",
    "AlertMessage" => "",
    "ModelErrors" => [],
    "data" => []
  ];

} catch (Exception $e) {
  $response = [
    "HasError" => true,
    "AlertType" => "danger",
    "AlertMessage" => $e->getMessage(),
    "ModelErrors" => [],
    "data" => []
  ];

}

/**
 * Compara valores antiguos con los nuevos y devuelve los cambios.
 */
function compararValores($objeto, $nuevosValores)
{
  $cambios = [];
  foreach ($nuevosValores as $campo => $nuevoValor) {
    if ($objeto->$campo != $nuevoValor) {
      $cambios[$campo] = ['old' => $objeto->$campo, 'new' => $nuevoValor];
    }
  }
  return $cambios;
}

/**
 * Registra la auditoría general según la acción realizada.
 */
function registrarAuditoria($userId, $isNew, $tasaCambioId, $cambios, $transaccion)
{
  if ($isNew) {
    registrarAuditoriaGeneral($userId, 'TASA_CAMBIO_CREADA', '', '', $tasaCambioId, '', $transaccion);
  } elseif (!empty($cambios)) {
    foreach ($cambios as $campo => $cambio) {
      registrarAuditoriaGeneral($userId, 'TASA_CAMBIO_EDITADA', $cambio['old'], $cambio['new'], $tasaCambioId, $campo, $transaccion);
    }
  }
}

/**
 * Función para registrar la auditoría general.
 */
function registrarAuditoriaGeneral($userId, $tipo, $valorAntes, $valorDespues, $observacion, $campo, $transaccion)
{

  $auditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($transaccion);
  $auditoriaGeneral = (object) [
    'usuarioId' => $userId,
    'usuariosolicitaId' => $userId,
    'tipo' => $tipo,
    'estado' => 'A',
    'valorAntes' => $valorAntes,
    'valorDespues' => $valorDespues,
    'usucreaId' => $userId,
    'observacion' => $observacion,
    'campo' => $campo,
    'usuarioaprobarId' => null,
    'usuariosolicitaIp' => null,
    'usuarioIp' => null,
    'usuarioaprobarIp' => null,
    'usumodifId' => null,
    'dispositivo' => '',
    'soperativo' => '',
    'sversion' => '',
    'imagen' => '',
    'data' => ''
  ];

  $auditoriaGeneralMySqlDAO->insert($auditoriaGeneral);
}

/**
 * Función para validar que no se registren tasas de combinación que ya existen
 * @param string $sourceCurrency
 * @param string $destinationCurrency
 * 
 */
function validateDuplication($sourceCurrency, $destinationCurrency){
  $tasaCambioMySqlDAO = new TasaCambioMySqlDAO();
  $validation = $tasaCambioMySqlDAO->validateIfExist($sourceCurrency, $destinationCurrency);
  if(!empty($validation)) throw new Exception("Esta conversión ya existe. No es posible duplicar tasas de cambio.", 400);
}
