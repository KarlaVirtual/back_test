<?php
use Backend\dto\UsuarioMandante;
use Backend\dto\LealtadHistorial;

/**
 * Report/GetLoyaltyBalanceAdjustments
 * 
 * Obtiene los ajustes de saldo de lealtad según los filtros especificados
 *
 * @param array $params {
 *   "start": int,               // Registro inicial (paginación)
 *   "count": int,               // Cantidad de registros a retornar
 *   "dateFrom": string,         // Fecha inicial en formato Y-m-d
 *   "dateTo": string,           // Fecha final en formato Y-m-d
 *   "AdjustmentId": int,        // ID del ajuste
 *   "UserId": int,              // ID del usuario
 *   "TypeMovement": string,     // Tipo de movimiento (E: Entrada, S: Salida, T: Transferencia)
 *   "CountrySelect": int        // ID del país seleccionado
 * }
 *
 * @return array {
 *   "HasError": boolean,         // Indica si hubo error
 *   "AlertType": string,         // Tipo de alerta (success, error)
 *   "AlertMessage": string,      // Mensaje de alerta
 *   "ModelErrors": array,        // Errores del modelo
 *   "data": array {
 *     "Id": int,                 // ID del registro
 *     "Date": string,            // Fecha del registro
 *     "Type": string,            // Tipo de movimiento
 *     "Amount": float,           // Monto del ajuste
 *     "Description": string,     // Descripción del ajuste
 *     "UserId": int,             // ID del usuario
 *     "UserName": string,        // Nombre del usuario
 *     "Country": string,         // País del usuario
 *     "Balance": float           // Saldo después del ajuste
 *   }[],
 *   "pos": int,                  // Posición actual
 *   "total_count": int           // Total de registros
 * }
 */


// Obtiene los parámetros de la solicitud GET
$start = $_GET['start'];
$count = $_GET['count'];
$dateFrom = $_GET['dateFrom'];
$dateTo = $_GET['dateTo'];
$adjustmentId = $_GET['AdjustmentId'];
$userId = $_GET['UserId'];
$typeMovement = $_GET['TypeMovement'];
$countrySelect = $_GET['CountrySelect'];

// Define patrones regex para validar los parámetros de entrada
$valideableParameters = [
    'start' => '\W+', // Todo lo que no sea un número o una letra
    'count' => '\W+',
    'dateFrom' => '(?!\d|\s|\-|)+', //Todo lo que no sea una fecha o un espacio vacío
    'dateTo' => '(?!\d|\s|\-|)+',
    'adjustmentId' => '\W+',
    'userId' => '\W+',
    'typeMovement' => '(?!E|S|T|)+', //Todo lo que no sea las letras E S T o un espacio vacío
    'countrySelect' => '\W+'
];

// Valida cada parámetro contra su patrón regex y guarda los inválidos
$invalidParamenters = [];
foreach ($valideableParameters as $parameter => $pattern) {
    $validationResponse = preg_match("/$pattern/", $$parameter);
    if ($validationResponse === false || $validationResponse === 1) $invalidParamenters[] = $parameter;
}

// Si hay parámetros inválidos, lanza una excepción
$totalMissedValidations = count($invalidParamenters);
if ($totalMissedValidations > 0) {
    throw new Exception("Parámetros inválidos {$invalidParamenters[0]} y otro(s) {$totalMissedValidations} error(es)", 300023);
}

$position = $start;

// Obtiene información del operador actual
$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

// Prepara el array de reglas para filtrar la consulta
$rules = [];

// Agrega filtros según el mandante y país del usuario
$partner = $_SESSION['mandante'] != -1 ? $_SESSION['mandante'] : '';
if ($partner !== '') $rules[] = ['field' => 'usuario.mandante', 'data' => $partner, 'op' => 'eq'];

$countrySelect = $countrySelect ?? ($_SESSION['PaisCondS'] ?? $_SESSION['pais_id']);
if (!empty($countrySelect)) $rules[] = ['field' => 'usuario.pais_id', 'data' => $countrySelect, 'op' => 'eq'];

// Agrega filtros por usuario y fechas
if (!empty($userId)) $rules[] = ['field' => 'usuario.usuario_id', 'data' => $userId, 'op' => 'eq'];

$dateFrom = date('Y-m-d 00:00:00', strtotime($dateFrom));
$dateTo = date('Y-m-d 23:59:59', strtotime($dateTo));
if (!empty($dateFrom) && !empty($dateTo)) {
    $rules[] = ['field' => 'lealtad_historial.fecha_crea', 'data' => $dateFrom, 'op' => 'ge'];
    $rules[] = ['field' => 'lealtad_historial.fecha_crea', 'data' => $dateTo, 'op' => 'le'];
}

// Agrega filtros por ID de ajuste y tipo de movimiento
if (!empty($adjustmentId)) $rules[] = ['field' => 'lealtad_historial.lealtadhistorial_id', 'data' => $adjustmentId, 'op' => 'eq'];

if ($typeMovement != 'T' && !empty($typeMovement)) $rules[] = ['field' => 'lealtad_historial.movimiento', 'data' => $typeMovement, 'op' => 'eq'];

// Filtra solo movimientos de ajuste de puntos
$rules[] = ['field' => 'lealtad_historial.tipo', 'data' => 15, 'op' => 'eq'];

// Prepara los filtros y columnas para la consulta
$filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

$select = 'lealtad_historial.lealtadhistorial_id, usuario.usuario_id, usuario.nombre, lealtad_historial.movimiento, 
            lealtad_historial.valor, lealtad_historial.fecha_crea, lealtad_historial.fecha_exp, lealtad_historial.usucrea_id';

$sidx = 'lealtad_historial.lealtadhistorial_id';

// Ejecuta la consulta para obtener los ajustes
$LealtadHistorial = new LealtadHistorial();
$adjustments = $LealtadHistorial->getLealtadHistorialCustom($select, $sidx, 'DESC', $start, $count, $filters, true, null);

// Procesa los resultados de la consulta
$totalCount = json_decode($adjustments)->count['0']->{0} ?? 0;
$adjustments = json_decode($adjustments)->data;

// Formatea los datos de los ajustes para la respuesta
$adjustmentsData = [];
foreach ($adjustments as $adjustment) {
    $adjustmentData = [];
    $adjustmentData["AdjustmentId"] = $adjustment->{'lealtad_historial.lealtadhistorial_id'};
    $adjustmentData["UserId"] = $adjustment->{'usuario.usuario_id'};
    $adjustmentData["UserName"] = $adjustment->{'usuario.nombre'};
    $adjustmentData["TypeMovement"] = $adjustment->{'lealtad_historial.movimiento'};
    $adjustmentData["Value"] = $adjustment->{'lealtad_historial.valor'};
    $adjustmentData["CreationDate"] = $adjustment->{'lealtad_historial.fecha_crea'};
    $adjustmentData["ExpirationDate"] = $adjustment->{'lealtad_historial.fecha_exp'};
    $adjustmentData["OperatorId"] = $adjustment->{'lealtad_historial.usucrea_id'};

    $adjustmentsData[] = $adjustmentData;
}

// Prepara la respuesta final con los datos procesados
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response['pos'] = (string) $position;
$response['total_count'] = (string) $totalCount;
$response['data'] = $adjustmentsData;
?>
