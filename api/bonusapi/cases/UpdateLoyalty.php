<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\LealtadInterna;
use Backend\dto\LealtadDetalle;
use Backend\dto\AuditoriaGeneral;
use Backend\mysql\LealtadDetalleMySqlDAO;
use Backend\mysql\AuditoriaGeneralMySqlDAO;
use Backend\mysql\LealtadInternaMySqlDAO;


/**
 * Actualiza la configuración de un programa de lealtad basado en los parámetros proporcionados.
 *
 * @param object $params Objeto que contiene los siguientes valores:
 * @param int $params->Id Identificador del programa de lealtad.
 * @param string $params->RulesText Texto de las reglas del programa.
 * @param string $params->Type Tipo de lealtad ('1' para interno, '0' para público).
 * @param string $params->Description Descripción del programa de lealtad.
 * @param string $params->Name Nombre del programa de lealtad.
 * @param string $params->BeginDate Fecha de inicio de la campaña.
 * @param string $params->EndDate Fecha de finalización de la campaña.
 * @param object $params->PartnerLealtad Objeto con datos de expiración del programa.
 * @param string $params->ExpirationDate Fecha de expiración.
 * @param int $params->ExpirationDays Días de expiración.
 * @param string $params->ExpirationDate Fecha de expiración alternativa.
 * @param int $params->ExpirationDays Días de expiración alternativos.
 * @param string $params->LiveOrPreMatch Configuración de tipo de partido.
 * @param int $params->MinSelCount Mínimo número de selecciones.
 * @param float $params->MinSelPrice Precio mínimo de selección.
 * @param float $params->CurrentCost Costo actual.
 * @param object $params->DepositDefinition Objeto con datos de definición de depósito.
 * @param string $params->DepositDefinition->LealtadDefinition Definición de lealtad.
 * @param int $params->DepositDefinition->LealtadDefinitionId ID de la definición de lealtad.
 * @param float $params->DepositDefinition->LealtadPercent Porcentaje de lealtad.
 * @param float $params->DepositDefinition->LealtadWFactor Factor de rollover de lealtad.
 * @param int $params->DepositDefinition->DepositNumber Número de depósitos.
 * @param float $params->DepositDefinition->DepositWFactor Factor de rollover de depósito.
 * @param bool $params->DepositDefinition->SuppressWithdrawal Indica si se suprime el retiro.
 * @param bool $params->DepositDefinition->UseFrozeWallet Indica si se usa una billetera congelada.
 * @param bool $params->IsVisibleForAllplayers Indica si es visible para todos los jugadores.
 * @param bool $params->OpenToAllPlayers Indica si está abierto a todos los jugadores.
 * @param int $params->MaxplayersCount Número máximo de jugadores.
 * @param object $params->ForeignRule Objeto con reglas extranjeras.
 * @param string $params->ForeignRule->Info Información en formato JSON.
 * @param int $params->ProductTypeId ID del tipo de producto.
 * @param int $params->TriggerId ID del disparador.
 * @param int $params->TypeId Tipo de lealtad.
 * @param array $params->Games Lista de juegos con sus configuraciones.
 * @param array $params->MaxPayout Lista de pagos máximos por moneda.
 * @param float $params->MaximumLealtadAmount Monto máximo de lealtad.
 * @param array $params->MaximumDeposit Lista de depósitos máximos por moneda.
 * @param array $params->MinimumDeposit Lista de depósitos mínimos por moneda.
 * @param array $params->MoneyRequirement Requisitos monetarios por moneda.
 * @param float $params->MoneyRequirementAmount Monto requerido.
 * @param object $params->Schedule Objeto con datos de programación.
 * @param int $params->Schedule->Count Número de programaciones.
 * @param string $params->Schedule->Name Nombre de la programación.
 * @param string $params->Schedule->Period Periodo de la programación.
 * @param string $params->Schedule->PeriodType Tipo de periodo.
 * @param object $params->TriggerDetails Detalles del disparador.
 * @param int $params->TriggerDetails->Count Número de depósitos.
 * @param bool $params->TriggerDetails->AreAllowed Indica si los países están permitidos.
 * @param bool $params->TriggerDetails->IsFromCashDesk Indica si es desde caja.
 * @param int $params->TriggerDetails->PaymentSystemId ID del sistema de pago.
 * @param array $params->TriggerDetails->PaymentSystemIds Lista de IDs de sistemas de pago.
 * @param array $params->TriggerDetails->Regions Lista de regiones permitidas.
 * @param string $params->MainImageURL URL de la imagen principal.
 *
 *
 * @return void Modifica el parámetro global $response con los siguientes valores:
 *  - HasError (bool): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta.
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *  - Result (array): Resultado de la operación
 */


/* Se asigna un valor a $mandanteUsuario según sesión y condición del usuario. */
$mandanteUsuario = 0;
// Si el usuario esta condicionado por el mandante y no es de Global
if ($_SESSION['Global'] == "N") {
    $mandanteUsuario = $_SESSION['mandante'];
}

$lealtadId = $params->Id;


/* manipula datos de parámetros para establecer variables relacionadas con lealtad. */
$RulesText = str_replace("'", "\'", $params->RulesText);
$Type = ($params->Type == '1') ? '1' : '0';

$Description = $params->Description; //Descripcion del lealtad
$Name = $params->Name; //Nombre del lealtad
$StartDate = $params->BeginDate; //Fecha Inicial de la campaña

/* Asignación de fechas relacionadas con la campaña y la lealtad del socio. */
$EndDate = $params->EndDate; //Fecha Final de la campaña

$PartnerLealtad = $params->PartnerLealtad;

$ExpirationDate = $PartnerLealtad->ExpirationDate; //Fecha de expiracion del lealtad
$ExpirationDays = $PartnerLealtad->ExpirationDays; // Dias de expiracion del lealtad


/* Condicional que verifica lealtad y asigna fechas de expiración según parámetros. */
if ($PartnerLealtad == "") {
    $ExpirationDate = $params->ExpirationDate; //Fecha de expiracion del lealtad
    $ExpirationDays = $params->ExpirationDays; // Dias de expiracion del lealtad

}

$LiveOrPreMatch = $params->LiveOrPreMatch;

/* asigna valores de parámetros a variables para su uso posterior. */
$MinSelCount = $params->MinSelCount;
$MinSelPrice = $params->MinSelPrice;


$CurrentCost = $params->CurrentCost;

$DepositDefinition = $params->DepositDefinition;


/* Asignación de propiedades del objeto DepositDefinition a variables relacionadas con lealtad y depósito. */
$LealtadDefinition = $DepositDefinition->LealtadDefinition;
$LealtadDefinitionId = $DepositDefinition->LealtadDefinitionId;
$LealtadPercent = $DepositDefinition->LealtadPercent; // Porcentaje del lealtad
$LealtadWFactor = $DepositDefinition->LealtadWFactor; //Rollower lealtad
$DepositNumber = $DepositDefinition->DepositNumber;
$DepositWFactor = $DepositDefinition->DepositWFactor; //Rollower Deposito


/* Código que configura opciones de depósito y visualización para jugadores en un sistema. */
$SuppressWithdrawal = $DepositDefinition->SuppressWithdrawal; //Suprimir la retirada
$UseFrozeWallet = $DepositDefinition->UseFrozeWallet;

$IsVisibleForAllplayers = $params->IsVisibleForAllplayers; //Es para todos los usuarios
$OpenToAllPlayers = $params->OpenToAllPlayers;//Es para todos los usuarios
$MaxplayersCount = $params->MaxplayersCount; // Maximo jugadores que lo pueden obtener


/* Se obtiene y decodifica información sobre reglas extranjeras desde parámetros JSON. */
$ForeignRule = $params->ForeignRule;
$ForeignRuleInfo = $ForeignRule->Info;

$ForeignRuleJSON = json_decode($ForeignRuleInfo);
$LiveOrPreMatch = $ForeignRuleJSON->LiveOrPreMatch;
$MinSelCount = $ForeignRuleJSON->MinSelCount; // Minimo cantidad de selecciones

/* Se extraen parámetros de un objeto JSON relacionado con apuestas deportivas. */
$MinSelPrice = $ForeignRuleJSON->MinSelPrice; // Minimo cuota seleccionada
$MinBetPrice = $ForeignRuleJSON->MinBetPrice; // Minimo apuesta cuota total
$SportLealtadRules = $ForeignRuleJSON->SportLealtadRules;

$ProductTypeId = $params->ProductTypeId;

$TriggerId = $params->TriggerId;


/* Se obtienen parámetros y se inicializa un arreglo para condiciones. */
$TypeId = $params->TypeId;

$Games = $params->Games;

$condiciones = [];


$MaxPayout = $params->MaxPayout; //Pago Maximo

/* Se inicializan variables con parámetros relacionados a límites de depósitos y lealtad. */
$MaximumLealtadAmount = $params->MaximumLealtadAmount; //Maximo valordel lealtad
$MaximumDeposit = $params->MaximumDeposit;
$MinimumDeposit = $params->MinimumDeposit;
$MoneyRequirement = $params->MoneyRequirement;
$MoneyRequirementAmount = $params->MoneyRequirementAmount;

$Schedule = $params->Schedule; //Programar lealtad

/* Se extraen propiedades de un objeto de programación y detalles de un desencadenador. */
$ScheduleCount = $Schedule->Count; //
$ScheduleName = $Schedule->Name; //Descripcion de la programacion
$SchedulePeriod = $Schedule->Period;
$SchedulePeriodType = $Schedule->PeriodType;

$TriggerDetails = $params->TriggerDetails;

/* asigna valores de propiedades a variables relacionadas con depósitos y permisos. */
$Count = $TriggerDetails->Count; //Cantidad de depositos

$AreAllowed = $TriggerDetails->AreAllowed; //Son Permitidos los paises

$IsFromCashDesk = $TriggerDetails->IsFromCashDesk;
$PaymentSystemId = $TriggerDetails->PaymentSystemId;

/* obtiene identificadores de pago, regiones y URL de imagen, además de la IP. */
$PaymentSystemIds = $TriggerDetails->PaymentSystemIds;
$Regions = $TriggerDetails->Regions;
$MainImageURL = $params->MainImageURL;


$ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];

/* extrae la primera dirección IP y define una función para identificar dispositivos móviles. */
$ip = explode(",", $ip)[0];


/**
 * Verifica si el usuario está utilizando un dispositivo móvil.
 *
 * @return bool True si el usuario está utilizando un dispositivo móvil, false en caso contrario.
 */
function esMovil()
{
    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    /* verifica si un usuario usa un dispositivo móvil específico. */
    $dispositivosMoviles = array(
        'iPhone', 'iPad', 'Android', 'BlackBerry', 'Windows Phone',
        'Opera Mini', 'Mobile Safari', 'webOS'
    );

    foreach ($dispositivosMoviles as $dispositivo) {
        if (stripos($userAgent, $dispositivo) !== false) {
            return true;
        }
    }

    return false;
}

/* Determina si el dispositivo es móvil o de escritorio según el user agent. */
if (esMovil()) {
    $dispositivo = 'Mobile';
} else {
    $dispositivo = "Desktop";
}


$userAgent = $_SERVER['HTTP_USER_AGENT'];

function getOS($userAgent)
{
    $os = "Desconocido";

    if (stripos($userAgent, 'Windows') !== false) {
        $os = 'Windows';
    } elseif (stripos($userAgent, 'Linux') !== false) {
        /* verifica si el agente de usuario contiene 'Linux' y asigna el sistema operativo. */

        $os = 'Linux';
    } elseif (stripos($userAgent, 'Macintosh') !== false || stripos($userAgent, 'Mac OS X') !== false) {
        /* Detecta si el usuario utiliza un sistema operativo Macintosh a través del user agent. */

        $os = 'Mac';
    }

    return $os;
}


/* Obtiene el sistema operativo y crea un objeto LealtadDetalle para acceder a su valor. */
$so = getOS($userAgent);


$LealtadDetalle = new LealtadDetalle('', $lealtadId, 'IMGPPALURL');

$valor = $LealtadDetalle->valor;

if ($LealtadDetalle->valor != $MainImageURL) {

    /* Asigna valores a la lealtad y registra información de auditoría del usuario. */
    $LealtadDetalle->valor = $MainImageURL;

    $AuditoriaGeneral = new AuditoriaGeneral();
    $AuditoriaGeneral->usuarioId = $_SESSION['usuario'];
    $AuditoriaGeneral->usuarioIp = $ip;
    $AuditoriaGeneral->usuariosolicitaId = $_SESSION['usuario'];

    /* Registro de auditoría que documenta actualización de valor en regalo. */
    $AuditoriaGeneral->usuarioaprobarIp = '';
    $AuditoriaGeneral->tipo = 'ACTUALIZACION EN REGALO';
    $AuditoriaGeneral->valorAntes = $LealtadDetalle->valor;// Valor antes de la actualización
    $AuditoriaGeneral->valorDespues = $MainImageURL; // Valor después de la actualización
    $AuditoriaGeneral->usucreaId = $_SESSION['usuario'];
    $AuditoriaGeneral->usumodifId = 0;

    /* asigna valores a propiedades de un objeto AuditoriaGeneral. */
    $AuditoriaGeneral->estado = 'A';
    $AuditoriaGeneral->dispositivo = $dispositivo;
    $AuditoriaGeneral->soperativo = $so;
    $AuditoriaGeneral->imagen = '';
    $AuditoriaGeneral->observacion = "Actualización de imagen regalo";
    $AuditoriaGeneral->data = '';

    /* Inserta un registro en la base de datos y gestiona una transacción. */
    $AuditoriaGeneral->campo = 'valor';


    $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();
    $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
    $AuditoriaGeneralMySqlDAO->getTransaction()->commit();
}


/* asigna un tipo de lealtad y crea una instancia de LealtadInterna. */
$tipolealtad = $TypeId;

$LealtadInterna = new LealtadInterna($lealtadId);

if ($LealtadInterna->nombre != $Name) {

    /* Se actualiza un nombre y se registra una auditoría con datos del usuario. */
    $valorAntesNombre = $LealtadInterna->nombre;
    $LealtadInterna->nombre = $Name;

    $AuditoriaGeneral = new AuditoriaGeneral();
    $AuditoriaGeneral->usuarioId = $_SESSION['usuario'];
    $AuditoriaGeneral->usuarioIp = $ip;

    /* Registra auditar cambios en un sistema de actualización de datos de usuario. */
    $AuditoriaGeneral->usuariosolicitaId = $_SESSION['usuario'];
    $AuditoriaGeneral->usuarioaprobarIp = '';
    $AuditoriaGeneral->tipo = 'ACTUALIZACION EN REGALO';
    $AuditoriaGeneral->valorAntes = $valorAntesNombre;     // Valor antes de la actualización
    $AuditoriaGeneral->valorDespues = $Name;      // Valor después de la actualización
    $AuditoriaGeneral->usucreaId = $_SESSION['usuario'];

    /* Se establece una auditoría con datos específicos sobre una actualización del sistema. */
    $AuditoriaGeneral->usumodifId = 0;
    $AuditoriaGeneral->estado = 'A';
    $AuditoriaGeneral->dispositivo = $dispositivo;
    $AuditoriaGeneral->soperativo = $so;
    $AuditoriaGeneral->imagen = '';
    $AuditoriaGeneral->observacion = "Actualización de nombre de regalo";

    /* Se inicializa un objeto y se inserta en base de datos mediante un DAO. */
    $AuditoriaGeneral->data = '';
    $AuditoriaGeneral->campo = 'nombre';


    $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();
    $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);

    /* confirma una transacción en una base de datos MySQL usando DAO. */
    $AuditoriaGeneralMySqlDAO->getTransaction()->commit();
}


if ($LealtadInterna->descripcion != $Description) {

    /* actualiza una descripción y registra auditoría del usuario y su IP. */
    $valorAntesDescripcion = $LealtadInterna->descripcion;
    $LealtadInterna->descripcion = $Description;

    $AuditoriaGeneral = new AuditoriaGeneral();
    $AuditoriaGeneral->usuarioId = $_SESSION['usuario'];
    $AuditoriaGeneral->usuarioIp = $ip;

    /* Registro de auditoría que captura cambios en la actualización de un regalo. */
    $AuditoriaGeneral->usuariosolicitaId = $_SESSION['usuario'];
    $AuditoriaGeneral->usuarioaprobarIp = '';
    $AuditoriaGeneral->tipo = 'ACTUALIZACION EN REGALO';
    $AuditoriaGeneral->valorAntes = $valorAntesDescripcion;    // Valor antes de la actualización
    $AuditoriaGeneral->valorDespues = $Description;         // Valor después de la actualización
    $AuditoriaGeneral->usucreaId = $_SESSION['usuario'];

    /* Se configura una auditoría general con datos específicos de actualización y estado. */
    $AuditoriaGeneral->usumodifId = 0;
    $AuditoriaGeneral->estado = 'A';
    $AuditoriaGeneral->dispositivo = $dispositivo;
    $AuditoriaGeneral->soperativo = $so;
    $AuditoriaGeneral->imagen = '';
    $AuditoriaGeneral->observacion = "Actualización de descripcion de regalo";

    /* Se crea una instancia de AuditoriaGeneral y se inserta en la base de datos. */
    $AuditoriaGeneral->data = '';
    $AuditoriaGeneral->campo = 'descripcion';


    $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();
    $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);

    /* confirma y guarda una transacción en la base de datos MySQL. */
    $AuditoriaGeneralMySqlDAO->getTransaction()->commit();
}


/*$LealtadInterna->fechaInicio = $StartDate;
$LealtadInterna->fechaFin = $EndDate;
$LealtadInterna->tipo = $tipolealtad;
$LealtadInterna->estado = 'A';
$LealtadInterna->usucreaId = 0;*/

/* Asigna valores a propiedades del objeto LealtadInterna según condiciones y datos de sesión. */
$LealtadInterna->usumodifId = $_SESSION["usuario"];


$LealtadInterna->reglas = $RulesText;

if ($Type == '1') {
    $LealtadInterna->publico = 'I';
} else {
    /* Asignación de valor 'A' a la propiedad 'publico' del objeto 'LealtadInterna'. */

    $LealtadInterna->publico = 'A';
}


/* gestiona transacciones y actualiza registros en una base de datos MySQL. */
$LealtadInternaMySqlDAO = new LealtadInternaMySqlDAO();
$transaccion = $LealtadInternaMySqlDAO->getTransaction();
$LealtadInternaMySqlDAO->update($LealtadInterna);
$LealtadDetalleMySqlDAO = new LealtadDetalleMySqlDAO($transaccion);
$LealtadDetalleMySqlDAO->update($LealtadDetalle);


/*$AuditoriaGeneral = new AuditoriaGeneral();
$AuditoriaGeneral->usuarioId = $_SESSION['usuario'];
$AuditoriaGeneral->usuarioIp = $ip;
$AuditoriaGeneral->usuariosolicitaId = $_SESSION['usuario'];
$AuditoriaGeneral->usuarioaprobarIp = '';
$AuditoriaGeneral->tipo = 'ACTUALIZACION_CONFIGURACION';
$AuditoriaGeneral->valorAntes = ''; // Valor antes de la actualización
$AuditoriaGeneral->valorDespues = json_encode($final); // Valor después de la actualización
$AuditoriaGeneral->usucreaId = $_SESSION['usuario'];
$AuditoriaGeneral->usumodifId = 0;
$AuditoriaGeneral->estado = 'A';
$AuditoriaGeneral->dispositivo = $dispositivo;
$AuditoriaGeneral->soperativo = $so;
$AuditoriaGeneral->imagen = '';
$AuditoriaGeneral->observacion = "Actualización de configuración de socio";
$AuditoriaGeneral->data = '';
$AuditoriaGeneral->campo = 'Configuración';
*/

//$LealtadDetalleMySqlDAO->deleteByLealtadId($lealtadId);

//Expiracion

/*if ($ExpirationDays != "") {
    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "EXPDIA";
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $ExpirationDays;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);
}

if ($UseFrozeWallet != "") {

    $LealtadDetalle = new LealtadDetalle("", $lealtadId, "FROZEWALLET");
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "FROZEWALLET";
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $UseFrozeWallet;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);
}

if ($SuppressWithdrawal != "") {


    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "SUPPRESSWITHDRAWAL";
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $SuppressWithdrawal;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);
}

if ($ScheduleCount != "") {


    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "SCHEDULECOUNT";
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $ScheduleCount;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);
}

if ($ScheduleName != "") {


    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "SCHEDULENAME";
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $ScheduleName;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);
}

if ($SchedulePeriod != "") {


    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "SCHEDULEPERIOD";
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $SchedulePeriod;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);
}

if ($SchedulePeriodType != "") {


    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "SCHEDULEPERIODTYPE";
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $SchedulePeriodType;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);
}

if ($ProductTypeId !== "") {

    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "TIPOPRODUCTO";
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $ProductTypeId;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);
}

if ($Count != "") {
    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "CANTDEPOSITOS";
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $Count;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);

}

if ($AreAllowed != "") {
    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "PAISESPERMITIDOS";
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $AreAllowed;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);
}

if ($ExpirationDate != "") {
    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "EXPFECHA";
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $ExpirationDate;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);
}

if($TypeLealtadDeposit == '1') {

    if ($LealtadPercent != "") {
        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "PORCENTAJE";
        $LealtadDetalle->moneda = '';
        $LealtadDetalle->valor = $LealtadPercent;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);
    }
}

if ($LealtadWFactor != "") {
    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "WFACTORBONO";
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $LealtadWFactor;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);
}

if ($DepositWFactor != "") {
    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "WFACTORDEPOSITO";
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $DepositWFactor;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);
}


if ($DepositNumber != "") {
    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "NUMERODEPOSITO";
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $DepositNumber;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);
}

if ($IsFromCashDesk) {
    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "CONDEFECTIVO";
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = 'true';
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);
}

if ($MaxplayersCount != "") {
    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "MAXJUGADORES";
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $MaxplayersCount;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);
}


foreach ($MaxPayout as $key => $value) {
    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "MAXPAGO";
    $LealtadDetalle->moneda = $value->CurrencyId;
    $LealtadDetalle->valor = $value->Amount;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);
}

foreach ($MaximumDeposit as $key => $value) {
    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "MAXDEPOSITO";
    $LealtadDetalle->moneda = $value->CurrencyId;
    $LealtadDetalle->valor = $value->Amount;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);
}

foreach ($MinimumDeposit as $key => $value) {
    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "MINDEPOSITO";
    $LealtadDetalle->moneda = $value->CurrencyId;
    $LealtadDetalle->valor = $value->Amount;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);
}
if($TypeLealtadDeposit == '0') {

    foreach ($MoneyRequirement as $key => $value) {
        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "VALORBONO";
        $LealtadDetalle->moneda = $value->CurrencyId;
        $LealtadDetalle->valor = $value->Amount;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);
    }
}

foreach ($PaymentSystemIds as $key => $value) {
    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "CONDPAYMENT";
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $value;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);
}

foreach ($Regions as $key => $value) {
    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "CONDPAIS";
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $value;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);
}

foreach ($Games as $key => $value) {
    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "CONDGAME" . $value->Id;
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $value->WageringPercent;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);
}

foreach ($SportLealtadRules as $key => $value) {

    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "ITAINMENT" . $value->ObjectTypeId;
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $value->ObjectId;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);
}

if ($LiveOrPreMatch != "") {
    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "LIVEORPREMATCH";
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $LiveOrPreMatch;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);
}

if ($MinSelCount != "") {
    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "MINSELCOUNT";
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $MinSelCount;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);
}

if ($MinSelPrice != "") {
    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "MINSELPRICE";
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $MinSelPrice;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);
}

if ($MinBetPrice != "" && false) {
    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "MINBETPRICE";
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $LiveOrPreMatch;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);
}*/


/* Código que finaliza una transacción y prepara una respuesta sin errores. */
$transaccion->commit();

$response["HasError"] = false;
$response["AlertType"] = "danger";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

/* Inicializa un arreglo vacío llamado "Result" en la variable $response. */
$response["Result"] = array();
