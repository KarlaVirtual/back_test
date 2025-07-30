<?php

use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Exception;
use Backend\dto\Criptomoneda;
use Backend\mysql\CriptomonedaMySqlDAO;
use Backend\sql\Transaction;

/**
 * Crea una nueva criptomoneda en el sistema.
 *
 * @param string $param ->iso       Código ISO de la criptomoneda (solo letras y números).
 * @param string $param ->name      Nombre de la criptomoneda (letras, números y espacios).
 * @param string $param ->iconImage Imagen en base64 del ícono de la criptomoneda.
 * @param string $param ->behavior  Nivel de estabilidad ("1" para estable, "2" para inestable).
 * @param string $param ->status    Estado de la criptomoneda ("1" para activo, "0" para inactivo).
 *
 * @return array $response Arreglo asociativo con las siguientes claves:
 *   - HasError (bool): Indica si hubo error en la operación.
 *   - AlertType (string): Tipo de alerta generada.
 *   - AlertMessage (string): Mensaje descriptivo del resultado.
 *   - ModelErrors (array): Errores de validación del modelo.
 *   - CurrencyId (int): ID de la criptomoneda creada.
 *
 * @throws Exception Si los parámetros son inválidos (código 300023).
 * @throws Exception Si la criptomoneda ya existe (código 300177).
 * @throws Exception Si el usuario no tiene permiso para crear criptomonedas (código 100035).
 */

/*Recepción de parámetros*/
$iso = $params->iso;
$name = $params->name;
$iconImage = $params->iconImage;
$behavior = $params->behavior;
$status = $params->status;
$newIconUrl = null;

$paramsUnderValidation = [
    'iso' => $iso,
    'name' => $name,
    'behavior' => $behavior,
    'status' => $status,
];

/*Sanitización de parámetros*/
$ConfigurationEnvironment = new ConfigurationEnvironment();
foreach ($paramsUnderValidation as &$paramUnderValidation) {
    $paramUnderValidation = $ConfigurationEnvironment->DepurarCaracteres($paramUnderValidation);
}
unset($paramUnderValidation);

/*Verificación de patrones válidos*/
$invalidPatterns = [
    'iso' => '[^\w]',
    'name' => '[^\w\s]',
    'behavior' => '[^12]',
    'status' => '[^01]',
];

foreach ($invalidPatterns as $key => $invalidPattern) {
    if (($paramsUnderValidation[$key] !== null && preg_match("#".$invalidPattern . "#i", $paramsUnderValidation[$key])) || ($paramsUnderValidation[$key] === null || $paramsUnderValidation[$key] === '')) {
        Throw new Exception("Parámetros inválidos " . $key, 300023);
    }
}


/*Validación de existencia previa de la criptomoneda*/
$rules = [];
$rules[] = ["field" => "criptomoneda.codigo_iso", "data" => $paramsUnderValidation['iso'], "op" => "eq"];
$rules[] = ["field" => "criptomoneda.nombre", "data" => $paramsUnderValidation['name'], "op" => "eq"];
$filters = [
    "rules" => $rules,
    "groupOp" => "OR",
];
$select = "criptomoneda.criptomoneda_id";

$Criptomoneda = new Criptomoneda();
$previusExistence = $Criptomoneda->getCriptomonedaCustom($select, "criptomoneda.criptomoneda_id", "DESC", 0, 1, json_encode($filters));
$previusExistence = json_decode($previusExistence);
if (($previusExistence->count[0])->{".count"} > 0) {
    throw new Exception("Criptomoneda ya existe", 300177);
}


/*Obtención y validación binario de la imagen*/
$imageB64 = explode(';base64,', $iconImage)[1];
$imageBinary = base64_decode($imageB64);
if (!empty($imageBinary)) {
    Criptomoneda::isValidIconResource($imageBinary);
}


/*Validación permiso para creación de criptomonedas*/
$permission = $ConfigurationEnvironment->checkUserPermission('CryptoCurrency/CreateCurrency', $_SESSION['win_perfil'], $_SESSION['usuario'], "CryptosAdd");
if (!$permission) Throw new Exception("Permiso denegado", 100035);


if (!empty($imageBinary)) {
    $newIconUrl = Criptomoneda::saveIconResource($imageBinary, $_SESSION['mandante']);
}


/*Traducción de convensiones*/
$behaviorFinalValue = match(intval($paramsUnderValidation['behavior'])){
    1 => 'estable',
    2 => 'inestable',
    default => null
};

$statusFinalValue = match(intval($paramsUnderValidation['status'])){
    0 => 'I',
    1 => 'A',
    default => null
};


/*Creación de la criptomoneda*/
$Transaction = new Transaction();
$CriptomonedaMySqlDAO = new CriptomonedaMySqlDAO($Transaction);

$Criptomoneda = new Criptomoneda();
$Criptomoneda->setCodigoIso($paramsUnderValidation['iso']);
$Criptomoneda->setNombre($paramsUnderValidation['name']);
if (!empty($newIconUrl)) $Criptomoneda->setIcono($newIconUrl);
$Criptomoneda->setNivelEstabilidad($behaviorFinalValue);
$Criptomoneda->setEstado($statusFinalValue);
$Criptomoneda->setUsucreaId($_SESSION['usuario']);
$Criptomoneda->setUsumodifId($_SESSION['usuario']);

$CriptomonedaMySqlDAO->insert($Criptomoneda);
$Transaction->commit();

$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "Registro exitoso";
$response["ModelErrors"] = [];
$response["CurrencyId"] = $Criptomoneda->criptomonedaId;
