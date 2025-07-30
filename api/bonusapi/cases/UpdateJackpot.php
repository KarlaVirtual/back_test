<?php
use Backend\dto\JackpotInterno;
use Backend\mysql\JackpotInternoMySqlDAO;
use Backend\sql\Transaction;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\UsuarioMandante;
use Backend\dto\GeneralLog;
use Backend\mysql\GeneralLogMySqlDAO;
use Backend\dto\UsuarioPerfil;
use Backend\dto\JackpotDetalle;


/**
 * Script para la actualización de algunos parámetros del jackpot.
 * (Sólo parámetros no transaccionales)
 * @param string $param->JackpotId    ID del jackpot a actualizar.
 * @param string $param->imagen       URL de la imagen del jackpot.
 * @param string $param->imagen2      URL de la imagen2 del jackpot.
 * @param string $param->gif          URL del gif del jackpot.
 * @param string $param->videoDesktop URL del video desktop del jackpot.
 * @param string $param->videoMobile  URL del video mobile del jackpot.
 * @param string $param->RulesText    Texto de las reglas del jackpot.
 *
 * @return array $response Array con la respuesta de la operación.
 *  -HasError:     Booleano que indica si hubo un error en la operación.
 *  -AlertType:    Tipo de alerta (success, warning, error).
 *  -AlertMessage: Mensaje de alerta.
 *  -JackpotID:    ID del jackpot actualizado.
 *  -ModelErrors:  Errores del modelo.
 *  -Result:       Resultado de la operación.
 */


/*Obtención información del usuario solicitante*/
$ConfigurationEnvironment = new ConfigurationEnvironment();
$userIp = $ConfigurationEnvironment->get_client_ip();
$userIp = (explode(",", $userIp))[0];
$plaform = strval($_SERVER['HTTP_SEC_CH_UA_PLATFORM']);
$plaform = str_replace('"',"",$plaform);
$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

/*Recepción de parámetros*/
$jackpotId = $params->JackpotId;
$imagen = $params->imagen;
$imagen2 = $params->imagen2;
$gif = $params->gif;
$videoDesktop = $params->videoDesktop;
$videoMobile = $params->videoMobile;
$rulesText = $params->RulesText;

/*Sanitización de parámetros*/
/*Nombre columna en objeto JackpotInterno | Valor para actualizar*/
$paramsToUpdate = [
    "jackpotId" => $jackpotId,
    "imagen" => $imagen,
    "imagen2" => $imagen2,
    "gif" => $gif,
    "videoDesktop" => $videoDesktop,
    "videoMobile" => $videoMobile,
    "reglas" => $rulesText,
];

foreach ($paramsToUpdate as $param => &$value) {
    if ($param == "jackpotId" && !is_numeric($value)) {
        /*Sanitización ID jackpot*/
        throw new Exception("Error en los parámetros enviados", 100001);
    }

    /*Sanitización resto de parámetros*/
    $value = str_replace("'", "\'", $value);
    $value = str_replace('"', '\"', $value);
}
unset($value);


/*Verificando validez de la solicitud*/
$validRequest = true;

/*Obtención país y mandante del operador*/
$operatorCountry = null;
$operatorMandanteList = null;
if ($_SESSION["PaisCond"] == "S") {
    $operatorCountry = $_SESSION["pais_id"];
}
else {
    $operatorCountry = $_SESSION["PaisCondS"];
}
if (empty($operatorCountry)) $operatorCountry = 0;
$operatorMandanteList = $_SESSION["mandanteLista"];


/*Obtención paises del jackpot*/
$jackpotCountries = [];
$JackpotDetalle = new JackpotDetalle();
$JackpotInterno = new JackpotInterno($paramsToUpdate["jackpotId"]);
$jackpotCountriesCollection = $JackpotDetalle->cargarDetallesJackpot($JackpotInterno->jackpotId, "CONDPAISUSER");
$CountryDetail = $JackpotDetalle->encontrarDetalle($jackpotCountriesCollection, "CONDPAISUSER", "/^".$operatorCountry."$/i");

/*Rechazo por país inválido*/
if ((empty($CountryDetail) || ($CountryDetail[0])->valor != $operatorCountry) && !in_array(-1, explode(",", $operatorMandanteList))) {
    $validRequest = false;
}

/* Rechazado por mandante inválido */
if (!in_array($JackpotInterno->mandante, explode(",", $operatorMandanteList))) {
    $validRequest = false;
}

/*Obtención perfil de operador*/
$UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);

/* Rechazado por perfil inválido */
if ($UsuarioPerfil->perfilId == "USUONLINE") {
    $validRequest = false;
}


if (!$validRequest) {
    /*Formato de respuesta*/
    $response["HasError"] = true;
    $response["AlertType"] = "warning";
    $response["AlertMessage"] = "Solicitud rechazada";
    $response["JackpotID"] = 0;
    $response["ModelErrors"] = [];
    $response["Result"] = [];

    return;
}


/*Area de actualización*/
$Transaction = new Transaction();
$tableToUpdate = "jackpot_interno";
$JackpotInternoMySqlDAO = new JackpotInternoMySqlDAO($Transaction);
$updateLogs = [];
foreach ($paramsToUpdate as $attribute => $value) {
    if ($attribute == "jackpotId") continue;

    /*Almacenando log de cambios*/
    if ($value != $JackpotInterno->$attribute) {
        $fieldName = null;
        $fieldName = match (strval($attribute)) {
            "videoDesktop" => 'video_desktop',
            "videoMobile" => 'video_mobile',
            default => $attribute
        };

        $updateLogs[] = (object)[
            "field" => $fieldName,
            "before" => $JackpotInterno->$attribute,
            "after" => $value
        ];
    }

    /*Asignación nuevo valor*/
    $JackpotInterno->$attribute = $value;
}

/*Validación en caso de no haber contado con cambios*/
if (empty($updateLogs)) {
    /*Cierre de transacción*/
    $Transaction->getConnection()->close();

    /*Formato de respuesta*/
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "Ningún cambio realizado";
    $response["JackpotID"] = $JackpotInterno->jackpotId;
    $response["ModelErrors"] = [];
    $response["Result"] = [];

    return;
}

/*Generación logs de cambios realizados*/
$GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
foreach ($updateLogs as $log) {
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
    $GeneralLog->externoId = $JackpotInterno->jackpotId;
    $GeneralLog->mandante = $JackpotInterno->mandante;

    $GeneralLogMySqlDAO->insert($GeneralLog);
}

$JackpotInternoMySqlDAO->notTransactionalUpdate($JackpotInterno);

$Transaction->commit();


$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "Cambios realizados";
$response["JackpotID"] = $JackpotInterno->jackpotId;
$response["ModelErrors"] = [];
$response["Result"] = [];

