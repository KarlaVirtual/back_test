<?php
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Exception;
use Backend\dto\Criptomoneda;
use Backend\mysql\CriptomonedaMySqlDAO;
use Backend\sql\Transaction;
use Backend\dto\GeneralLog;
use Backend\dto\UsuarioMandante;
use Backend\mysql\GeneralLogMySqlDAO;

/**
 * Actualiza los datos de una criptomoneda existente en el sistema.
 *
 * @param int $params->currencyId   ID de la criptomoneda a actualizar.
 * @param string $params->iso       Código ISO de la criptomoneda (solo letras y números).
 * @param string $params->name      Nombre de la criptomoneda (letras, números y espacios).
 * @param string $params->iconImage Imagen en base64 del ícono de la criptomoneda.
 * @param string $params->behavior  Nivel de estabilidad ("1" para estable, "2" para inestable).
 * @param string $params->status    Estado de la criptomoneda ("1" para activo, "0" para inactivo).
 *
 * @return array $response Arreglo asociativo con las siguientes claves:
 *   - HasError (bool): Indica si hubo error en la operación.
 *   - AlertType (string): Tipo de alerta generada.
 *   - AlertMessage (string): Mensaje descriptivo del resultado.
 *   - ModelErrors (array): Errores de validación del modelo.
 *
 * @throws Exception Si los parámetros son inválidos (código 300023).
 * @throws Exception Si el usuario no tiene permiso para actualizar criptomonedas (código 100035).
 */

/*Recepción de parámetros*/
$currencyId = $params->currencyId;
$iso = $params->iso;
$name = $params->name;
$iconImage = $params->iconImage;
$behavior = $params->behavior;
$status = $params->status;

$paramsUnderValidation = [
    'currencyId' => $currencyId,
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
    "currencyId" => '[^\d]',
    'iso' => '[^\w]',
    'name' => '[^\w\s]',
    'behavior' => '[^12]',
    'status' => '[^01]',
];

foreach ($invalidPatterns as $key => $invalidPattern) {
    if (($paramsUnderValidation[$key] !== null && preg_match("#".$invalidPattern . "#i", $paramsUnderValidation[$key])) || ($paramsUnderValidation[$key] === null || $paramsUnderValidation[$key] === '')) {
        throw new Exception("Parámetros inválidos " . $key, 300023);
    }
}


/*Obtención y validación binario de la imagen*/
if (str_contains($iconImage, ";base64,")) {
    $imageB64 = explode(';base64,', $iconImage)[1];
    $imageBinary = base64_decode($imageB64);
    if (!empty($imageBinary)) {
        Criptomoneda::isValidIconResource($imageBinary);
    }
}


/*Validación permiso para creación de criptomonedas*/
$permission = $ConfigurationEnvironment->checkUserPermission('CryptoCurrency/UpdateCurrency', $_SESSION['win_perfil'], $_SESSION['usuario'], "CryptosEdit");
if (!$permission) Throw new Exception("Permiso denegado", 100035);


/*Obtención información del usuario solicitante*/
$userIp = $ConfigurationEnvironment->get_client_ip();
$userIp = (explode(",", $userIp))[0];
$plaform = strval($_SERVER['HTTP_SEC_CH_UA_PLATFORM']);
$plaform = str_replace('"',"",$plaform);
$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);


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

$currencyIdFinalValue = intval($paramsUnderValidation['currencyId']);

/*Actualización de la criptomoneda*/
$tableToUpdate = "criptomoneda";
$changeLogs = [];
$Transaction = new Transaction();
$CriptomonedaMySqlDAO = new CriptomonedaMySqlDAO($Transaction);
$GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
$Criptomoneda = new Criptomoneda($currencyIdFinalValue);

if (!empty($imageBinary) && md5($Criptomoneda->getIcono()) !== md5($imageBinary)) {
    $newIconUrl = Criptomoneda::saveIconResource($imageBinary, $_SESSION['mandante']);
    $changeLogs[] = (object)[
        'field' => 'icono',
        'before' => $Criptomoneda->getIcono(),
        'after' => $newIconUrl,
    ];
    $Criptomoneda->setIcono($newIconUrl);
}

if ($Criptomoneda->getNivelEstabilidad() != $behaviorFinalValue) {
    $changeLogs[] = (object)[
        'field' => 'nivel_estabilidad',
        'before' => $Criptomoneda->getNivelEstabilidad(),
        'after' => $behaviorFinalValue,
    ];
    $Criptomoneda->setNivelEstabilidad($behaviorFinalValue);
}

if ($Criptomoneda->getEstado() != $statusFinalValue) {
    $changeLogs[] = (object)[
        'field' => 'estado',
        'before' => $Criptomoneda->getEstado(),
        'after' => $statusFinalValue,
    ];
    $Criptomoneda->setEstado($statusFinalValue);
}

$Criptomoneda->setUsumodifId($_SESSION['usuario']);

if (empty($changeLogs)) {
    /*Cierre de transacción*/
    $Transaction->getConnection()->close();

    /*Formato de respuesta*/
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "No había cambios a realizar";
    $response["ModelErrors"] = [];

    return;
}


/*Generación logs de actualización*/
foreach ($changeLogs as $log) {
    $GeneralLog = new GeneralLog();
    $GeneralLog->usuarioId = $UsuarioMandante->usumandanteId;
    $GeneralLog->usuarioIp = $userIp;
    $GeneralLog->usuariosolicitaId = $UsuarioMandante->usumandanteId;
    $GeneralLog->usuariosolicitaIp = $userIp;
    $GeneralLog->usuarioaprobarId = $UsuarioMandante->usumandanteId;
    $GeneralLog->usuarioaprobarIp = $userIp;
    $GeneralLog->tipo = "CHANGEFIELD";
    $GeneralLog->valorAntes = strlen($log->before) <= 250 ? $log->before : "SYSNOTE: Valor demasiado largo para almacenar";
    $GeneralLog->valorDespues = strlen($log->after) <= 250 ? $log->after : "SYSNOTE: Valor demasiado largo para almacenar";
    $GeneralLog->usucreaId = $UsuarioMandante->usumandanteId;
    $GeneralLog->estado = "A";
    $GeneralLog->dispositivo = $_SESSION['sistema'] === 'D' ? 'Desktop' : 'Mobile';
    $GeneralLog->soperativo = $plaform;
    $GeneralLog->tabla = $tableToUpdate;
    $GeneralLog->campo = $log->field;
    $GeneralLog->externoId = $Criptomoneda->getCriptomonedaId();

    $GeneralLogMySqlDAO->insert($GeneralLog);
}

$CriptomonedaMySqlDAO->update($Criptomoneda);

$Transaction->commit();

$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "Cambios realizados";
$response["ModelErrors"] = [];