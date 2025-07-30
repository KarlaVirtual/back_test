<?php
use Backend\dto\TorneoInterno;
use Backend\dto\TorneoDetalle;
use Backend\dto\UsuarioMandante;
use Backend\dto\Usuario;
use Backend\sql\Transaction;
use Backend\mysql\UsuarioTorneoMySqlDAO;
use Backend\dto\UsuarioTorneo;
use Backend\dto\AuditoriaGeneral;
use Backend\mysql\AuditoriaGeneralMySqlDAO;

/**
 * bonusapi/cases/AddUserTournaments.php
 *
 * Este recurso se encarga de procesar y gestionar la adición masiva de usuarios a un torneo interno.
 * Se valida el torneo, la visibilidad del torneo, la existencia de los usuarios, y su compatibilidad con el torneo.
 * Además, realiza las operaciones necesarias para agregar a los usuarios al torneo, y mantiene un registro de auditoría general.
 *
 * @param object $params : Objeto que contiene los parámetros necesarios para la operación.
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista (success, danger, etc.).
 *  - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista, indicando el estado de la operación.
 *  - *ModelErrors* (array): Contiene los errores específicos si los hubo, incluyendo detalles sobre las solicitudes fallidas.
 *
 * Objeto en caso de error:
 *
 * "HasError" => true,
 * "AlertType" => "danger",
 * "AlertMessage" => "Mensaje de error",
 * "ModelErrors" => array("FailedRequisitions" => [Detalles de las peticiones fallidas]),
 *
 * @throws Exception Error general en la ejecución de la operación.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */

/** Recibiendo parámetros */
$tournamentId = $params->Id;
$playersIdCsv = $params->PlayersIdCsv;

/** Validando inputs */
$coincidences = [];
$coincidences[] = preg_match('#[^\d]{1}#', $tournamentId);
if ($coincidences[0] == 1 || $coincidences[0] === false) {
    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = "Torneo inválido";
    $response["ModelErrors"] = [];
    return;
}
else $coincidences = [];

/** Recuperando torneo */
$TorneoInterno = new TorneoInterno($tournamentId);
if ($TorneoInterno->estado == 'I') {
    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = "Torneo Inactivo";
    $response["ModelErrors"] = [];
    return;
}

/** Validando que torneo sea privado */
try {
    $TorneoDetalle = new TorneoDetalle('', $TorneoInterno->torneoId, 'VISIBILIDAD');
    if ($TorneoDetalle->valor != 1) throw new Exception('', 21);
} catch (Exception $e) {
    if ($e->getCode() != 21) throw $e;
    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = "Torneo público no aplica";
    $response["ModelErrors"] = [];
    return;
}

/** Inicializando parámetros para gestión de peticiones */
$playersData = [];
$failedRequisitions = [];
$totalRequisitions = 0;

/** Formateando data CSV --Los ID's del CSV corresponden a usuarioId, NO al usuario de casino (usumandante_id)*/
$fileWithComma = 0;
$playersIdCsv = explode("base64,", $playersIdCsv);
$playersIdCsv = base64_decode($playersIdCsv[1]);
$playersIdCsv = str_replace(';', ',', $playersIdCsv, $fileWithComma);
if ($fileWithComma == 0) $fileWithComma = (int) str_contains($playersIdCsv, ',');
$playersIdCsv = preg_split('/\r\n|\n|\r/', $playersIdCsv);

if ($fileWithComma > 0) {
    $playersIdCsv = implode("", $playersIdCsv);
    $playersIdCsv = explode(',', $playersIdCsv);
}

$playersIdCsv = array_filter($playersIdCsv);
$playersIdCsv = array_values($playersIdCsv);

foreach ($playersIdCsv as $playerIdCsv) {
    $totalRequisitions++;
    $coincidences = [];

    //Validando inputs
    $coincidences[] = preg_match('#[^\d]{1}#', $playerIdCsv);
    if ($coincidences[0] == 1 || $coincidences[0] === false) {
        $failedRequisitions[] = ['UserId' => $playerIdCsv, 'Reason' => 'RejectedData'];
        continue;
    }

    try {
        //Verificando existencia de usuario
        $Usuario = new Usuario($playerIdCsv);
        $UsuarioMandante = new UsuarioMandante('', $playerIdCsv, $Usuario->mandante);
    } catch (Exception $e) {
        if (!($e->getCode() == 24 || $e->getCode() == 22)) throw $e;
        $failedRequisitions[] = ['UserId' => $playerIdCsv, 'Reason' => 'UnknownUser'];
        continue;
    }

    try {
        //Verificando que usuario no se encuentre participando en el torneo solicitado
        $UsuarioTorneo = new UsuarioTorneo('', $UsuarioMandante->getUsumandanteId(), $TorneoInterno->torneoId);
        $failedRequisitions[] = ['UserId' => $playerIdCsv, 'Reason' => 'AlreadyOnTournament'];
        continue;
    } catch (Exception $e) {
        if ($e->getCode() != 33) throw $e;
    }

    //Verificando que coincida el partner para el torneo y el usuario
    if ($UsuarioMandante->getMandante() != $TorneoInterno->mandante) {
        $failedRequisitions[] = ['UserId' => $playerIdCsv, 'Reason' => 'IncompatibleUser'];
        continue;
    }

    $playersData[] = $UsuarioMandante->usumandanteId;
}

/** Agregando usuarios al torneo */
$Transaction = new Transaction();
$UsuarioTorneoMySqlDAO = new UsuarioTorneoMySqlDAO($Transaction);
foreach ($playersData as $playerToAdd) {
    $UsuarioTorneo = new UsuarioTorneo();
    $UsuarioTorneo->torneoId = $TorneoInterno->torneoId;
    $UsuarioTorneo->usuarioId = $playerToAdd;
    $UsuarioTorneo->valor = 0;
    $UsuarioTorneo->posicion = 0;
    $UsuarioTorneo->valorBase = 0;
    $UsuarioTorneo->usucreaId = 0;
    $UsuarioTorneo->usumodifId = 0;
    $UsuarioTorneo->estado = "A";
    $UsuarioTorneo->errorId = 0;
    $UsuarioTorneo->idExterno = 0;
    $UsuarioTorneo->mandante = 0;
    $UsuarioTorneo->version = 0;
    $UsuarioTorneo->apostado = 0;
    $UsuarioTorneo->codigo = 0;
    $UsuarioTorneo->externoId = 0;


    $UsuarioTorneoMySqlDAO->insert($UsuarioTorneo);
}

$Transaction->commit();

try {
    /** Dejando LOG en auditoría general */
    $ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
    $ip = explode(",", $ip)[0];

    $AuditoriaGeneral = new AuditoriaGeneral();

    $AuditoriaGeneral->setUsuarioId($_SESSION["usuario"]);
    $AuditoriaGeneral->setUsuarioIp($ip);
    $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario"]);
    $AuditoriaGeneral->setUsuariosolicitaIp($ip);
    $AuditoriaGeneral->setTipo("ADICION_MASIVA_TORNEO");
    $AuditoriaGeneral->setValorAntes("");
    $AuditoriaGeneral->setValorDespues("A");
    $AuditoriaGeneral->setUsucreaId($_SESSION["usuario"]);
    $AuditoriaGeneral->setUsumodifId(0);
    $AuditoriaGeneral->setEstado("A");
    $AuditoriaGeneral->setDispositivo(0);
    $AuditoriaGeneral->setObservacion((string) $TorneoInterno->torneoId);

    $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();
    $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
    $AuditoriaGeneralMySqlDAO->getTransaction()->commit();
}catch (Exception $e) {

}

/** Calculando peticiones exitosas */
$successfulRequisitions = $totalRequisitions - count($failedRequisitions);

if(count($failedRequisitions) < 1){
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "Ejecución exitosa {$successfulRequisitions}/{$totalRequisitions} solicitudes exitosas";
    $response["ModelErrors"] = [
        "FailedRequisitions" => $failedRequisitions,
    ];
}
else{
    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = "Ejecución parcial {$successfulRequisitions}/{$totalRequisitions} solicitudes exitosas";
    $response["ModelErrors"] = [
        "FailedRequisitions" => $failedRequisitions,
    ];
}

?>