<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\BonoInterno;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;

/**
 * Actualiza la información de un bono en el sistema.
 *
 * @param object $params Objeto que contiene los parámetros necesarios para actualizar el bono:
 * @param int $params->Id Identificador único del bono.
 * @param string $params->RulesText Texto de las reglas del bono.
 * @param string $params->Type Tipo de bono ('1' para interno, otro valor para público).
 * @param string $params->Description Descripción del bono.
 * @param string $params->Name Nombre del bono.
 * @param string $params->BeginDate Fecha de inicio de la campaña.
 * @param string $params->EndDate Fecha de finalización de la campaña.
 * @param object $params->PartnerBonus Objeto que contiene información sobre el bono de socio:
 * @param string $params->PartnerBonus->ExpirationDate Fecha de expiración del bono.
 * @param int $params->PartnerBonus->ExpirationDays Días de expiración del bono.
 * @param string $params->ExpirationDate Fecha de expiración del bono (si no hay bono de socio).
 * @param int $params->ExpirationDays Días de expiración del bono (si no hay bono de socio).
 * @param string $params->LiveOrPreMatch Indica si es en vivo o pre-partido.
 * @param int $params->MinSelCount Mínimo número de selecciones.
 * @param float $params->MinSelPrice Mínima cuota seleccionada.
 * @param float $params->CurrentCost Costo actual.
 * @param object $params->DepositDefinition Objeto que define los depósitos:
 * @param string $params->DepositDefinition->BonusDefinition Definición del bono.
 * @param int $params->DepositDefinition->BonusDefinitionId ID de la definición del bono.
 * @param float $params->DepositDefinition->BonusPercent Porcentaje del bono.
 * @param float $params->DepositDefinition->BonusWFactor Factor de rollover del bono.
 * @param int $params->DepositDefinition->DepositNumber Número de depósitos.
 * @param float $params->DepositDefinition->DepositWFactor Factor de rollover del depósito.
 * @param bool $params->DepositDefinition->SuppressWithdrawal Indica si se suprime la retirada.
 * @param bool $params->DepositDefinition->UseFrozeWallet Indica si se utiliza una billetera congelada.
 * @param bool $params->IsVisibleForAllplayers Indica si es visible para todos los jugadores.
 * @param bool $params->OpenToAllPlayers Indica si está abierto a todos los jugadores.
 * @param int $params->MaxplayersCount Número máximo de jugadores que pueden obtenerlo.
 * @param object $params->ForeignRule Objeto que contiene reglas externas:
 * @param string $params->ForeignRule->Info Información en formato JSON sobre las reglas externas.
 * @param int $params->ProductTypeId ID del tipo de producto.
 * @param int $params->TriggerId ID del disparador.
 * @param int $params->TypeId Tipo de bono.
 * @param array $params->Games Lista de juegos asociados al bono.
 * @param array $params->MaxPayout Lista de pagos máximos por moneda:
 * @param string $params->MaxPayout->CurrencyId ID de la moneda.
 * @param float $params->MaxPayout->Amount Monto máximo.
 * @param float $params->MaximumBonusAmount Monto máximo del bono.
 * @param array $params->MaximumDeposit Lista de depósitos máximos por moneda:
 * @param string $params->MaximumDeposit->CurrencyId ID de la moneda.
 * @param float $params->MaximumDeposit->Amount Monto máximo.
 * @param array $params->MinimumDeposit Lista de depósitos mínimos por moneda:
 * @param string $params->MinimumDeposit->CurrencyId ID de la moneda.
 * @param float $params->MinimumDeposit->Amount Monto mínimo.
 * @param array $params->MoneyRequirement Lista de requisitos monetarios por moneda:
 * @param string $params->MoneyRequirement->CurrencyId ID de la moneda.
 * @param float $params->MoneyRequirement->Amount Monto requerido.
 * @param float $params->MoneyRequirementAmount Monto total requerido.
 * @param object $params->Schedule Objeto que define la programación del bono:
 * @param int $params->Schedule->Count Número de programaciones.
 * @param string $params->Schedule->Name Nombre de la programación.
 * @param string $params->Schedule->Period Periodo de la programación.
 * @param string $params->Schedule->PeriodType Tipo de periodo de la programación.
 * @param object $params->TriggerDetails Detalles del disparador:
 * @param int $params->TriggerDetails->Count Cantidad de depósitos.
 * @param string $params->TriggerDetails->AreAllowed Países permitidos.
 * @param bool $params->TriggerDetails->IsFromCashDesk Indica si es desde caja.
 * @param int $params->TriggerDetails->PaymentSystemId ID del sistema de pago.
 * @param array $params->TriggerDetails->PaymentSystemIds Lista de IDs de sistemas de pago.
 * @param array $params->TriggerDetails->Regions Lista de regiones permitidas.
 *
 *
 * @return void Modifica el parámetro $response para indicar el resultado de la operación:
 *  - HasError: bool Indica si hubo un error.
 *  - AlertType: string Tipo de alerta.
 *  - AlertMessage: string Mensaje de alerta.
 *  - ModelErrors: array Lista de errores del modelo.
 *  - Result: array Resultado de la operación.
 */


/* asigna un mandante si el usuario no pertenece a Global. */
$mandanteUsuario = 0;
// Si el usuario esta condicionado por el mandante y no es de Global
if ($_SESSION['Global'] == "N") {
    $mandanteUsuario = $_SESSION['mandante'];
}

$bonoId = $params->Id;


/* reemplaza comillas y asigna valores de parámetros relacionados con un bono. */
$RulesText = str_replace("'", "\'", $params->RulesText);
$Type = ($params->Type == '1') ? '1' : '0';

$Description = $params->Description; //Descripcion del bono
$Name = $params->Name; //Nombre del bono
$StartDate = $params->BeginDate; //Fecha Inicial de la campaña

/* Asignación de fechas y datos relacionados con una campaña y bonificaciones para socios. */
$EndDate = $params->EndDate; //Fecha Final de la campaña

$PartnerBonus = $params->PartnerBonus;

$ExpirationDate = $PartnerBonus->ExpirationDate; //Fecha de expiracion del bono
$ExpirationDays = $PartnerBonus->ExpirationDays; // Dias de expiracion del bono


/* Asigna fechas y días de expiración del bono si no hay bonos de partner. */
if ($PartnerBonus == "") {
    $ExpirationDate = $params->ExpirationDate; //Fecha de expiracion del bono
    $ExpirationDays = $params->ExpirationDays; // Dias de expiracion del bono

}

$LiveOrPreMatch = $params->LiveOrPreMatch;

/* Asigna valores de parámetros a variables de configuración para procesamiento posterior. */
$MinSelCount = $params->MinSelCount;
$MinSelPrice = $params->MinSelPrice;


$CurrentCost = $params->CurrentCost;

$DepositDefinition = $params->DepositDefinition;


/* Asignación de variables para definir bonos y depósitos en un sistema financiero. */
$BonusDefinition = $DepositDefinition->BonusDefinition;
$BonusDefinitionId = $DepositDefinition->BonusDefinitionId;
$BonusPercent = $DepositDefinition->BonusPercent; // Porcentaje del bono
$BonusWFactor = $DepositDefinition->BonusWFactor; //Rollower bono
$DepositNumber = $DepositDefinition->DepositNumber;
$DepositWFactor = $DepositDefinition->DepositWFactor; //Rollower Deposito


/* Código en PHP que gestiona configuraciones de depósitos y visibilidad para jugadores. */
$SuppressWithdrawal = $DepositDefinition->SuppressWithdrawal; //Suprimir la retirada
$UseFrozeWallet = $DepositDefinition->UseFrozeWallet;

$IsVisibleForAllplayers = $params->IsVisibleForAllplayers; //Es para todos los usuarios
$OpenToAllPlayers = $params->OpenToAllPlayers;//Es para todos los usuarios
$MaxplayersCount = $params->MaxplayersCount; // Maximo jugadores que lo pueden obtener


/* extrae y decodifica información sobre reglas de apuestas en formato JSON. */
$ForeignRule = $params->ForeignRule;
$ForeignRuleInfo = $ForeignRule->Info;

$ForeignRuleJSON = json_decode($ForeignRuleInfo);
$LiveOrPreMatch = $ForeignRuleJSON->LiveOrPreMatch;
$MinSelCount = $ForeignRuleJSON->MinSelCount; // Minimo cantidad de selecciones

/* Captura y asigna valores relevantes desde un objeto JSON a variables específicas. */
$MinSelPrice = $ForeignRuleJSON->MinSelPrice; // Minimo cuota seleccionada
$MinBetPrice = $ForeignRuleJSON->MinBetPrice; // Minimo apuesta cuota total
$SportBonusRules = $ForeignRuleJSON->SportBonusRules;

$ProductTypeId = $params->ProductTypeId;

$TriggerId = $params->TriggerId;


/* asigna valores de parámetros a variables y define un array de condiciones. */
$TypeId = $params->TypeId;

$Games = $params->Games;

$condiciones = [];


$MaxPayout = $params->MaxPayout; //Pago Maximo

/* asigna valores de parámetros a variables para configurar un bono. */
$MaximumBonusAmount = $params->MaximumBonusAmount; //Maximo valordel bono
$MaximumDeposit = $params->MaximumDeposit;
$MinimumDeposit = $params->MinimumDeposit;
$MoneyRequirement = $params->MoneyRequirement;
$MoneyRequirementAmount = $params->MoneyRequirementAmount;

$Schedule = $params->Schedule; //Programar bono

/* asigna valores de un objeto "Schedule" a variables específicas. */
$ScheduleCount = $Schedule->Count; //
$ScheduleName = $Schedule->Name; //Descripcion de la programacion
$SchedulePeriod = $Schedule->Period;
$SchedulePeriodType = $Schedule->PeriodType;

$TriggerDetails = $params->TriggerDetails;

/* asigna valores de un objeto $TriggerDetails a variables para su uso posterior. */
$Count = $TriggerDetails->Count; //Cantidad de depositos

$AreAllowed = $TriggerDetails->AreAllowed; //Son Permitidos los paises

$IsFromCashDesk = $TriggerDetails->IsFromCashDesk;
$PaymentSystemId = $TriggerDetails->PaymentSystemId;

/* asigna valores de detalles de disparador a variables y crea un objeto de bono interno. */
$PaymentSystemIds = $TriggerDetails->PaymentSystemIds;
$Regions = $TriggerDetails->Regions;

$tipobono = $TypeId;

$BonoInterno = new BonoInterno($bonoId);

/* Se asignan valores a propiedades del objeto $BonoInterno, usando sesión de usuario. */
$BonoInterno->nombre = $Name;
$BonoInterno->descripcion = $Description;
/*$BonoInterno->fechaInicio = $StartDate;
$BonoInterno->fechaFin = $EndDate;
$BonoInterno->tipo = $tipobono;
$BonoInterno->estado = 'A';
$BonoInterno->usucreaId = 0;*/
$BonoInterno->usumodifId = $_SESSION["usuario"];


/* Se asigna texto a reglas y se define la categoría pública según el tipo. */
$BonoInterno->reglas = $RulesText;

if ($Type == '1') {
    $BonoInterno->publico = 'I';
} else {
    $BonoInterno->publico = 'A';
}


/* Se crea un objeto DAO para actualizar un bono interno en MySQL. */
$BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
$transaccion = $BonoDetalleMySqlDAO->getTransaction();
$BonoInternoMySqlDAO = new BonoInternoMySqlDAO($transaccion);
$BonoInternoMySqlDAO->update($BonoInterno);

//$BonoDetalleMySqlDAO->deleteByBonoId($bonoId);

//Expiracion

/*if ($ExpirationDays != "") {
    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "EXPDIA";
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = $ExpirationDays;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);
}

if ($UseFrozeWallet != "") {

    $BonoDetalle = new BonoDetalle("", $bonoId, "FROZEWALLET");
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "FROZEWALLET";
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = $UseFrozeWallet;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);
}

if ($SuppressWithdrawal != "") {


    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "SUPPRESSWITHDRAWAL";
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = $SuppressWithdrawal;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);
}

if ($ScheduleCount != "") {


    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "SCHEDULECOUNT";
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = $ScheduleCount;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);
}

if ($ScheduleName != "") {


    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "SCHEDULENAME";
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = $ScheduleName;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);
}

if ($SchedulePeriod != "") {


    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "SCHEDULEPERIOD";
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = $SchedulePeriod;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);
}

if ($SchedulePeriodType != "") {


    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "SCHEDULEPERIODTYPE";
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = $SchedulePeriodType;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);
}

if ($ProductTypeId !== "") {

    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "TIPOPRODUCTO";
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = $ProductTypeId;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);
}

if ($Count != "") {
    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "CANTDEPOSITOS";
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = $Count;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);

}

if ($AreAllowed != "") {
    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "PAISESPERMITIDOS";
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = $AreAllowed;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);
}

if ($ExpirationDate != "") {
    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "EXPFECHA";
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = $ExpirationDate;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);
}

if($TypeBonusDeposit == '1') {

    if ($BonusPercent != "") {
        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "PORCENTAJE";
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = $BonusPercent;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);
    }
}

if ($BonusWFactor != "") {
    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "WFACTORBONO";
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = $BonusWFactor;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);
}

if ($DepositWFactor != "") {
    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "WFACTORDEPOSITO";
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = $DepositWFactor;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);
}


if ($DepositNumber != "") {
    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "NUMERODEPOSITO";
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = $DepositNumber;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);
}

if ($IsFromCashDesk) {
    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "CONDEFECTIVO";
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = 'true';
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);
}

if ($MaxplayersCount != "") {
    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "MAXJUGADORES";
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = $MaxplayersCount;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);
}


foreach ($MaxPayout as $key => $value) {
    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "MAXPAGO";
    $BonoDetalle->moneda = $value->CurrencyId;
    $BonoDetalle->valor = $value->Amount;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);
}

foreach ($MaximumDeposit as $key => $value) {
    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "MAXDEPOSITO";
    $BonoDetalle->moneda = $value->CurrencyId;
    $BonoDetalle->valor = $value->Amount;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);
}

foreach ($MinimumDeposit as $key => $value) {
    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "MINDEPOSITO";
    $BonoDetalle->moneda = $value->CurrencyId;
    $BonoDetalle->valor = $value->Amount;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);
}
if($TypeBonusDeposit == '0') {

    foreach ($MoneyRequirement as $key => $value) {
        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "VALORBONO";
        $BonoDetalle->moneda = $value->CurrencyId;
        $BonoDetalle->valor = $value->Amount;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);
    }
}

foreach ($PaymentSystemIds as $key => $value) {
    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "CONDPAYMENT";
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = $value;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);
}

foreach ($Regions as $key => $value) {
    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "CONDPAIS";
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = $value;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);
}

foreach ($Games as $key => $value) {
    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "CONDGAME" . $value->Id;
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = $value->WageringPercent;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);
}

foreach ($SportBonusRules as $key => $value) {

    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "ITAINMENT" . $value->ObjectTypeId;
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = $value->ObjectId;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);
}

if ($LiveOrPreMatch != "") {
    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "LIVEORPREMATCH";
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = $LiveOrPreMatch;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);
}

if ($MinSelCount != "") {
    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "MINSELCOUNT";
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = $MinSelCount;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);
}

if ($MinSelPrice != "") {
    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "MINSELPRICE";
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = $MinSelPrice;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);
}

if ($MinBetPrice != "" && false) {
    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "MINBETPRICE";
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = $LiveOrPreMatch;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);
}*/


/* Código que finaliza una transacción y establece una respuesta sin errores. */
$transaccion->commit();

$response["HasError"] = false;
$response["AlertType"] = "danger";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

/* Crea un array vacío "Result" en la variable de respuesta. */
$response["Result"] = array();