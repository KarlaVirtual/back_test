<?php
use Backend\dto\Criptomoneda;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Exception;

/**
 * Obtiene una lista de criptomonedas según los filtros proporcionados.
 *
 * @param int|null $param->currencyId      ID de la criptomoneda a filtrar (opcional).
 * @param string|null $param->iso          Código ISO de la criptomoneda (opcional).
 * @param string|int|null $param->behavior Nivel de estabilidad de la criptomoneda (1: estable, 2: inestable, opcional).
 * @param string|int|null $param->status   Estado de la criptomoneda (1: activo, 0: inactivo, opcional).
 * @param int $param->start                Posición inicial para paginación (por defecto 0).
 * @param int $param->count                Cantidad de registros a devolver (por defecto 10).
 *
 * @return array $response Arreglo asociativo con las siguientes claves:
 *   - HasError (bool): Indica si hubo error en la operación.
 *   - AlertType (string): Tipo de alerta generada.
 *   - AlertMessage (string): Mensaje descriptivo del resultado.
 *   - pos (int): Posición inicial de la consulta.
 *   - total_count (int): Total de registros encontrados.
 *   - data (array): Listado de criptomonedas encontradas.
 *   - Data (array): Listado de criptomonedas encontradas (alias).
 *   - ModelErrors (array): Errores de validación del modelo.
 *
 * @throws Exception Si los parámetros son inválidos (código 300023).
 * @throws Exception Si el usuario no tiene permiso para consultar criptomonedas (código 100035).
 */

$currencyId = $_GET["currencyId"];
$iso = $_GET["iso"];
$behavior = $_GET["behavior"];
$status = $_GET["status"];
$start = $_GET["start"] ?? 0;
$count = $_GET["count"] ?? 10;

$paramsUnderValidation = [
    'currencyId' => $currencyId,
    'iso' => $iso,
    'behavior' => $behavior,
    'status' => $status,
    'start' => $start,
    'count' => $count,
];

/*Sanitización de parámetros*/
$ConfigurationEnvironment = new ConfigurationEnvironment();
foreach ($paramsUnderValidation as &$paramUnderValidation) {
    $paramUnderValidation = $ConfigurationEnvironment->DepurarCaracteres($paramUnderValidation);
}
unset($paramUnderValidation);

/*Verificación de patrones válidos*/
$invalidPatterns = [
    'currencyId' => '[^\d]',
    'iso' => '[^\w]',
    'behavior' => '[^\w]',
    'status' => '[^\w]',
    'pos' => '[^\d]',
    'count' => '[^\d]'
];

foreach ($invalidPatterns as $key => $invalidPattern) {
    if (($paramsUnderValidation[$key] !== null && preg_match("#".$invalidPattern . "#i", $paramsUnderValidation[$key]))) {
        Throw new Exception("Parámetros inválidos " . $key, 300023);
    }
}

/*Validación permiso para creación de criptomonedas*/
$permission = $ConfigurationEnvironment->checkUserPermission('CryptoCurrency/GetCurrencies', $_SESSION['win_perfil'], $_SESSION['usuario'], "Cryptos");
if (!$permission) Throw new Exception("Permiso denegado", 100035);

/*Traducción de convensiones*/
if ($paramsUnderValidation['behavior'] !== null) {
    $behaviorFinalValue = match (intval($paramsUnderValidation['behavior'])) {
        1 => 'estable',
        2 => 'inestable',
        default => null
    };
}
else $behaviorFinalValue = null;

if ($paramsUnderValidation['status'] !== null && $paramsUnderValidation['status'] !== '' && $paramsUnderValidation['status'] != 'null') {
    $statusFinalValue = match (intval($paramsUnderValidation['status'])) {
        0 => 'I',
        1 => 'A',
        default => null
    };
}
else $statusFinalValue = null;

$rules = [];

if ($paramsUnderValidation['iso'] !== null && $paramsUnderValidation['iso'] !== '') {
    $rules[] = ['field' => 'criptomoneda.codigo_iso', 'data' => $paramsUnderValidation['iso'], 'op' => 'eq'];
}

if ($behaviorFinalValue !== null && $behaviorFinalValue !== '') {
    $rules[] = ['field' => 'criptomoneda.nivel_estabilidad', 'data' => $behaviorFinalValue, 'op' => 'eq'];
}

if ($statusFinalValue !== null && $statusFinalValue !== '') {
    $rules[] = ['field' => 'criptomoneda.estado', 'data' => $statusFinalValue, 'op' => 'eq'];
}

if ($paramsUnderValidation['currencyId'] !== null && $paramsUnderValidation['currencyId'] !== '') {
    $rules[] = ['field' => 'criptomoneda.criptomoneda_id', 'data' => $paramsUnderValidation['currencyId'], 'op' => 'eq'];
}

$filters = [
    'rules' => $rules,
    'groupOp' => 'AND'
];

$select = 'criptomoneda.criptomoneda_id, criptomoneda.codigo_iso, criptomoneda.nombre, criptomoneda.nivel_estabilidad, criptomoneda.estado, criptomoneda.icono';
$Criptomoneda = new Criptomoneda();
$data = $Criptomoneda->getCriptomonedaCustom($select, 'criptomoneda.fecha_modif', 'DESC', $paramsUnderValidation['start'], $paramsUnderValidation['count'], json_encode($filters));
$data = json_decode($data);
$collection = $data->data;

$currencies = [];
foreach ($collection as $collectionItem) {
    $currencie = [];
    $currencie['currencyId'] = $collectionItem->{"criptomoneda.criptomoneda_id"};
    $currencie['iconImage'] = $collectionItem->{"criptomoneda.icono"};
    $currencie['iso'] = $collectionItem->{"criptomoneda.codigo_iso"};
    $currencie['name'] = $collectionItem->{"criptomoneda.nombre"};
    $currencie['behavior'] = $collectionItem->{"criptomoneda.nivel_estabilidad"} === 'estable' ? 1 : 2;
    $currencie['status'] = (string)($collectionItem->{"criptomoneda.estado"} === 'A' ? 1 : 0);

    $currencies[] = $currencie;
}

$pos = $start;


$response["HasError"] = false;
$response["AlertType"] = "success";
$response["pos"] = $pos;
$response["AlertMessage"] = "Consulta exitosa";
$response["total_count"] = ($data->count)[0]->{'.count'};
$response["data"] = $currencies;
$response["Data"] = $currencies;
$response["ModelErrors"] = [];