<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

//  error_reporting(E_ALL);
// ini_set('display_errors', 'ON');
use Backend\dto\BonoDetalle;
use Backend\dto\LealtadDetalle;
use Backend\dto\LealtadInterna;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioLealtad;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\LealtadDetalleMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\UsuarioLealtadMySqlDAO;


/* asigna un mandante dependiendo de la condición de sesión del usuario. */
$mandanteUsuario = 0;
// Si el usuario esta condicionado por el mandante y no es de Global
if ($_SESSION['Global'] == "N") {
    $mandanteUsuario = $_SESSION['mandante'];
}


$Description = $params->Description; //Descripcion del lealtad

/* Asignación de valores relacionados a una campaña de lealtad según parámetros proporcionados. */
$Name = $params->Name; //Nombre del lealtad
$StartDate = $params->BeginDate; //Fecha Inicial de la campaña
$EndDate = $params->EndDate; //Fecha Final de la campaña

$PartnerLealtad = $params->PartnerLealtad;

$ExpirationDate = $PartnerLealtad->ExpirationDate; //Fecha de expiracion del lealtad

/* asigna fechas y días de expiración de lealtad si están vacíos. */
$ExpirationDays = $PartnerLealtad->ExpirationDays; // Dias de expiracion del lealtad


if ($ExpirationDate == "" && $ExpirationDays == "") {
    $ExpirationDate = $params->ExpirationDate; //Fecha de expiracion del lealtad
    $ExpirationDays = $params->ExpirationDays; // Dias de expiracion del lealtad

}


/* Asigna valores de parámetros a variables en un script, probablemente para procesar apuestas. */
$LiveOrPreMatch = $params->LiveOrPreMatch;
$MinSelCount = $params->MinSelCount;
$MinSelPrice = $params->MinSelPrice;

$MainImageURL = $params->MainImageURL;
$CurrentCost = $params->CurrentCost;


/* Asignación de variables desde la definición del depósito en un sistema. */
$DepositDefinition = $params->DepositDefinition;

$LealtadDefinition = $DepositDefinition->LealtadDefinition;
$LealtadDefinitionId = $DepositDefinition->LealtadDefinitionId;
$LealtadPercent = $DepositDefinition->LealtadPercent; // Porcentaje del lealtad
$LealtadWFactor = $DepositDefinition->LealtadWFactor; //Rollower lealtad

/* Variables extraen información de un objeto sobre depósitos y su visibilidad para jugadores. */
$DepositNumber = $DepositDefinition->DepositNumber;
$DepositWFactor = $DepositDefinition->DepositWFactor; //Rollower Deposito

$SuppressWithdrawal = $DepositDefinition->SuppressWithdrawal; //Suprimir la retirada
$UseFrozeWallet = $DepositDefinition->UseFrozeWallet;

$IsVisibleForAllplayers = $params->IsVisibleForAllplayers; //Es para todos los usuarios

/* Código para gestionar parámetros de un juego y jugadores seleccionados. */
$OpenToAllPlayers = $params->OpenToAllPlayers;//Es para todos los usuarios
$MaxplayersCount = $params->MaxplayersCount; // Maximo jugadores que lo pueden obtener
$Prefix = $params->Prefix;

$PlayersChosen = $params->PlayersChosen;

$ForeignRule = $params->ForeignRule;

/* verifica si $ForeignRuleInfo es un objeto y lo decodifica si no lo es. */
$ForeignRuleInfo = $ForeignRule->Info;


if (!is_object($ForeignRuleInfo)) {
    $ForeignRuleJSON = json_decode($ForeignRuleInfo);

} else {
    /* Asignación de variable en caso de que no se cumpla una condición previa. */

    $ForeignRuleJSON = $ForeignRuleInfo;
}


/* extrae datos de un objeto JSON para establecer parámetros de selección. */
$LiveOrPreMatch = $ForeignRuleJSON->LiveOrPreMatch;
$MinSelCount = $ForeignRuleJSON->MinSelCount; // Minimo cantidad de selecciones
$MinSelPrice = $ForeignRuleJSON->MinSelPrice; // Minimo cuota seleccionada
$MinSelPriceTotal = $ForeignRuleJSON->MinSelPriceTotal;

$TriggerId = $params->TriggerId;

/* Asignación de variables para promo, apuesta mínima y tipo de producto en un sistema. */
$CodePromo = $params->CodePromo;


$MinBetPrice = $ForeignRuleJSON->MinBetPrice; // Minimo apuesta cuota total
$SportLealtadRules = $ForeignRuleJSON->SportLealtadRules;

$ProductTypeId = $params->ProductTypeId;


/* establece `$TriggerId` dependiendo de si `$CodePromo` no está vacío. */
$TriggerId = $params->TriggerId;

if ($CodePromo != "") {
    $TriggerId = 1;
}

$TypeId = $params->TypeId;


/* inicializa una lista de juegos y un valor de pago máximo. */
$Games = $params->Games;

$condiciones = [];


$MaxPayout = $params->MaxPayout; //Pago Maximo

/* Variables definidas para gestionar límites en transacciones y requisitos de lealtad. */
$MaximumLealtadAmount = $params->MaximumLealtadAmount; //Maximo valordel lealtad
$MaximumDeposit = $params->MaximumDeposit;
$MinimumDeposit = $params->MinimumDeposit;
$MinimumAmount = $params->MinimumAmount;
$MaximumAmount = $params->MaximumAmount;
$MoneyRequirement = $params->MoneyRequirement;

/* Se extraen valores de parámetros relacionados con requerimientos de dinero y programación. */
$MoneyRequirementAmount = $params->MoneyRequirementAmount;


$Schedule = $params->Schedule; //Programar lealtad
$ScheduleCount = $Schedule->Count; //
$ScheduleName = $Schedule->Name; //Descripcion de la programacion

/* asigna valores de un objeto de programación a variables específicas. */
$SchedulePeriod = $Schedule->Period;
$SchedulePeriodType = $Schedule->PeriodType;

$TriggerDetails = $params->TriggerDetails;
$Count = $TriggerDetails->Count; //Cantidad de depositos

//$AreAllowed = $TriggerDetails->AreAllowed; //Son Permitidos los paises

$IsFromCashDesk = $TriggerDetails->IsFromCashDesk;

/* Extracción de identificadores y datos de ubicación desde detalles de un desencadenador. */
$PaymentSystemId = $TriggerDetails->PaymentSystemId;
$PaymentSystemIds = $TriggerDetails->PaymentSystemIds;

$Regions = $TriggerDetails->Regions;
$Departments = $TriggerDetails->Departments;
$Cities = $TriggerDetails->Cities;

/* extrae información sobre escritorios de cash, regiones, departamentos y ciudades. */
$CashDesks = $TriggerDetails->CashDesks;

$RegionsUser = $TriggerDetails->RegionsUser;
$DepartmentsUser = $TriggerDetails->DepartmentsUser;
$CitiesUser = $TriggerDetails->CitiesUser;
$UserRepeatLealtad = $params->UserRepeatLealtad;


/* asigna valores de un objeto a variables para procesar detalles de saldo. */
$BalanceZero = $TriggerDetails->BalanceZero;

$WinLealtadId = $params->WinLealtadId;
$TypeSaldo = $params->TypeSaldo;

$Points = $params->Points;
//$Points = $Points->Amount;


/* Inicializa puntos y valida el tipo de condición en TriggerDetails. */
$points = 0;


$ConditionProduct = $TriggerDetails->ConditionProduct;
if ($ConditionProduct != "OR" && $ConditionProduct != "AND") {
    $ConditionProduct = 'NA';
}


/* Asignación de parámetros de definición de giros gratis en una variable. */
$FreeSpinDefinition = $params->FreeSpinDefinition;
$AutomaticForfeitureLevel = $FreeSpinDefinition->AutomaticForfeitureLevel;
$LealtadMoneyExpirationDate = $FreeSpinDefinition->LealtadMoneyExpirationDate;
$LealtadMoneyExpirationDays = $FreeSpinDefinition->LealtadMoneyExpirationDays;
$FreeSpinsTotalCount = $FreeSpinDefinition->FreeSpinsTotalCount;
$WageringFactor = $FreeSpinDefinition->WageringFactor;

/* asigna datos de un objeto a variables específicas para su uso posterior. */
$PlayersChosen = $params->PlayersChosen;
$Casino = $params->Casino->Info;
$CasinoCategory = $Casino->Category;
$CasinoProvider = $Casino->Provider;
$CasinoProduct = $Casino->Product;

$LealtadDetails = $params->LealtadDetails;


/* reemplaza caracteres, asigna tipos y maneja parámetros de entrada. */
$RulesText = str_replace("'", "\'", $params->RulesText);

$TypeLealtadDeposit = $params->TypeLealtadDeposit;

$Type = ($params->Type == '1') ? '1' : '0';

$tipolealtad = $TypeId;

/* inicializa variables y asigna un valor basado en condiciones específicas. */
$cupo = 0;
$cupoMaximo = 0;
$jugadores = 0;
$jugadoresMaximo = 0;

if ($MaximumAmount != "" && $tipolealtad == 2) {
    $cupoMaximo = $MaximumAmount[0]->Amount;
}


/* Asignar valores a variables según condiciones y asegurar valor por defecto. */
if ($MaxplayersCount != "" && $tipolealtad == 2) {
    $jugadoresMaximo = $MaxplayersCount;
}

if ($cupoMaximo == "") {
    $cupoMaximo = 0;
}

/* crea un objeto "BonoInterno" y establece sus propiedades. */
$BonoInterno = new BonoInterno();
$BonoInterno->nombre = $Name;
$BonoInterno->descripcion = $Description;
$BonoInterno->fechaInicio = $StartDate;
$BonoInterno->fechaFin = $EndDate;
$BonoInterno->tipo = $tipolealtad;

/* Asignación de valores a propiedades de un objeto `$BonoInterno` en PHP. */
$BonoInterno->estado = 'A';
$BonoInterno->usucreaId = 0;
$BonoInterno->usumodifId = 0;
$BonoInterno->mandante = $mandanteUsuario;
$BonoInterno->condicional = $ConditionProduct;
$BonoInterno->orden = $points;

/* Asigna valores a propiedades de un objeto 'BonoInterno' relacionado con bonos y reglas. */
$BonoInterno->cupoActual = $cupo;
$BonoInterno->cupoMaximo = $cupoMaximo;
$BonoInterno->cantidadBonos = $jugadores;
$BonoInterno->maximoBonos = $jugadoresMaximo;


$BonoInterno->reglas = $RulesText;


/* Establece el valor de 'publico' en 'I' o 'A' según el tipo. */
if ($Type == '1') {
    $BonoInterno->publico = 'I';
} else {
    $BonoInterno->publico = 'A';
}

$BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();

/* Se obtiene una transacción y se inserta en la base de datos. */
$transaccion = $BonoDetalleMySqlDAO->getTransaction();

$bonoId = $BonoInterno->insert($transaccion);

/*
if($MaxplayersCount != "" && $Prefix != ""){

    $codigosarray=array();

    for ($i = 1; $i <= $MaxplayersCount; $i++) {
        $codigo = GenerarClaveTicket(4);

        while(in_array($codigo,$codigosarray)){
            $codigo = GenerarClaveTicket(4);
        }



        $PromocionalLog = new PromocionalLog();

        $PromocionalLog->usuarioId= '0' ;

        $PromocionalLog->promocionalId=$bonoId ;

        $PromocionalLog->valor= '';

        $PromocionalLog->valorPromocional= '';

        $PromocionalLog->valorBase= '';

        $PromocionalLog->estado= 'L';

        $PromocionalLog->errorId= '';

        $PromocionalLog->idExterno= '';

        $PromocionalLog->mandante= '0';

        $PromocionalLog->version= '2';

        $PromocionalLog->usucreaId= '0';

        $PromocionalLog->usumodifId= '0';


        $PromocionalLog->apostado= '0';
        $PromocionalLog->rollowerRequerido= '0';
        $PromocionalLog->codigo= $Prefix . $codigo;

        $PromocionalLogMySqlDAO= new PromocionalLogMySqlDAO();
        $PromocionalLogMySqlDAO->insert($PromocionalLog);
        $PromocionalLogMySqlDAO->getTransaction()->commit();

        array_push($codigosarray,$codigo);

    }
}

*/

//Expiracion


/* Inserta un registro de bono si se especifican días de expiración. */
if ($ExpirationDays != "") {
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


/* Crea un nuevo registro de bono si se repite la lealtad del usuario. */
if ($UserRepeatLealtad != "") {

    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "REPETIRBONO";
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = $UserRepeatLealtad;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);


}


/* Inserta un nuevo detalle de bono si se utiliza una billetera congelada. */
if ($UseFrozeWallet != "") {

    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "FROZEWALLET";
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = $UseFrozeWallet;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);
}


/* Inserta un registro de BonoDetalle si $SuppressWithdrawal no está vacío. */
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


/* Se crea un registro de bono si $ScheduleCount no está vacío. */
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


/* Inserta un registro de BonoDetalle si $ScheduleName no está vacío. */
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


/* Inserta un registro de BonoDetalle si SchedulePeriod no está vacío. */
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


/* Inserta un nuevo detalle de bono si el tipo de periodo de programación no está vacío. */
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


/* Se inserta un detalle del bono en la base de datos si el tipo de producto es válido. */
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


/* Inserta un registro de BonoDetalle si $Count no está vacío. */
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


/* inserta un detalle de bono si hay países permitidos definidos. */
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


/* Se inserta un registro de bono si la fecha de expiración es válida. */
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


/* Condicional para insertar un bono detallado si se cumple el tipo y porcentaje. */
if ($TypeLealtadDeposit == '1') {


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


/* Inserta un nuevo detalle de bono si LealtadWFactor no está vacío. */
if ($LealtadWFactor != "") {
    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "WFACTORBONO";
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = $LealtadWFactor;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);
}


/* Inserta un nuevo registro de bono si $DepositWFactor no está vacío. */
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


/* Inserta un registro de bono si el número de depósito no está vacío. */
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


/* Condicional que inserta un bono detalle si proviene de un punto de venta. */
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


/* inserta un detalle de bono si la cantidad de jugadores es válida. */
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


/* Se inserta un nuevo detalle de bono si el identificador es mayor a cero. */
if ($WinLealtadId != 0) {

    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "WINBONOID";
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = $WinLealtadId;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);


} else {
    /* Inserta un objeto BonoDetalle en la base de datos con parámetros específicos. */


    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "TIPOSALDO";
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = $TypeSaldo;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);

}


/* Valida el ID y registra detalles de bonos en la base de datos. */
if ($WinLealtadId == "" || $WinLealtadId == "0") {

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


} else {
    /* inserta detalles de bonificación en una base de datos usando un bucle. */


    foreach ($MaxPayout as $key => $value) {
        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "VALORROLLOWER";
        $BonoDetalle->moneda = $value->CurrencyId;
        $BonoDetalle->valor = $value->Amount;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);
    }

}


if ($tipolealtad == "2") {


    /* Crea y almacena detalles de bonos por cada depósito máximo. */
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


    /* Inserta detalles de bonificaciones basadas en depósitos mínimos en una base de datos. */
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
}


/* inserta detalles de bono si el tipo de lealtad es '0'. */
if ($TypeLealtadDeposit == '0') {

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


/* Inserta detalles de bonificación para cada ID de sistema de pago. */
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


/* Crea e inserta objetos BonoDetalle en una base de datos para cada región. */
foreach ($Regions as $key => $value) {

    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "CONDPAISPV";
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = $value;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);

}


/* Inserta detalles de bonificación para cada departamento en la base de datos. */
foreach ($Departments as $key => $value) {

    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "CONDDEPARTAMENTOPV";
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = $value;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);

}


/* Inserta datos en la base de datos para cada ciudad en el array $Cities. */
foreach ($Cities as $key => $value) {

    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "CONDCIUDADPV";
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = $value;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);

}


/* Inserta un nuevo bono en la base de datos si el saldo es cero. */
if ($BalanceZero == "true" || $BalanceZero == 1 || $BalanceZero == true) {
    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "CONDBALANCE";
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = '0';
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);
}


/* Inserta detalles de bonos para cada región del usuario en la base de datos. */
foreach ($RegionsUser as $key => $value) {

    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "CONDPAISUSER";
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = $value;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);

}


/* Itera sobre departamentos, creando e insertando bonos en la base de datos. */
foreach ($DepartmentsUser as $key => $value) {

    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "CONDDEPARTAMENTOUSER";
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = $value;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);


}


/* Inserta detalles de bonificaciones para cada ciudad del usuario en la base de datos. */
foreach ($CitiesUser as $key => $value) {

    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "CONDCIUDADUSER";
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = $value;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);

}


/* Inserta detalles de bono para cada caja en una base de datos. */
foreach ($CashDesks as $key => $value) {

    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "CONDPUNTOVENTA";
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = $value;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);

}


/* Se inserta un detalle de bono para cada juego en la base de datos. */
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


/* inserta detalles de bonos para cada categoría de casino en la base de datos. */
foreach ($CasinoCategory as $key => $value) {
    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "CONDCATEGORY" . $value->Id;
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = $value->Percentage;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);

}


/* Se inserta un registro de bono para cada proveedor de casino en la base de datos. */
foreach ($CasinoProvider as $key => $value) {
    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "CONDSUBPROVIDER" . $value->Id;
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = $value->Percentage;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);

}


/* Inserta detalles de bonos en la base de datos para cada producto de casino. */
foreach ($CasinoProduct as $key => $value) {
    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "CONDGAME" . $value->Id;
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = $value->Percentage;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);

}


/* Inserta registros de bono en la base de datos utilizando un bucle foreach. */
foreach ($SportLealtadRules as $key => $value) {

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


/* Insertar un nuevo detalle de bono si LiveOrPreMatch no está vacío. */
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


/* Crea un registro de bono si existe un valor mínimo de selección. */
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


/* Inserta un nuevo detalle de bono si MinSelPrice no está vacío. */
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


/* Insertar un nuevo registro de BonoDetalle si MinSelPriceTotal no está vacío. */
if ($MinSelPriceTotal != "") {
    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "MINSELPRICETOTAL";
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = $MinSelPriceTotal;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);

}


foreach ($LealtadDetails as $key => $value) {

    /* Inserta un nuevo registro de BonoDetalle en la base de datos si MinAmount no está vacío. */
    if ($value->MinAmount != "") {
        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "MINAMOUNT";
        $BonoDetalle->moneda = $value->CurrencyId;
        $BonoDetalle->valor = $value->MinAmount;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);


    }

    /* Inserta un nuevo registro de bono si MaxAmount no está vacío. */
    if ($value->MaxAmount != "") {
        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "MAXAMOUNT";
        $BonoDetalle->moneda = $value->CurrencyId;
        $BonoDetalle->valor = $value->MaxAmount;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);

    }
    if ($value->Amount != "") {

        /* Crea un registro de bono si se especifica un monto mínimo. */
        if ($value->Amount->MinAmount != "") {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "MINAMOUNT";
            $BonoDetalle->moneda = $value->CurrencyId;
            $BonoDetalle->valor = $value->Amount->MinAmount;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);


        }

        /* Inserta un nuevo registro de bono si MaxAmount no está vacío. */
        if ($value->Amount->MaxAmount != "") {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "MAXAMOUNT";
            $BonoDetalle->moneda = $value->CurrencyId;
            $BonoDetalle->valor = $value->Amount->MaxAmount;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);

        }
    }
}


/* Condicionalmente, inserta un bono detalle si se recibe un código promocional. */
if ($TriggerId != "") {
    if ($CodePromo != "") {

        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "CODEPROMO";
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = $CodePromo;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);

    }
}


/* Condicional que inserta detalles del bono si $MinBetPrice no está vacío. */
if ($MinBetPrice != "" && false) {
    $BonoDetalle = new BonoDetalle();
    $BonoDetalle->bonoId = $bonoId;
    $BonoDetalle->tipo = "MINBETPRICE";
    $BonoDetalle->moneda = '';
    $BonoDetalle->valor = $MinBetPrice;
    $BonoDetalle->usucreaId = 0;
    $BonoDetalle->usumodifId = 0;
    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
    $BonoDetalleMysqlDAO->insert($BonoDetalle);
}

if ($FreeSpinsTotalCount != "" && $Prefix != "") {

    /* Asigna jugadores elegidos a un array con un valor específico para cada uno. */
    $jugadoresAsignar = array();
    $jugadoresAsignarFinal = array();

    if ($PlayersChosen != "") {
        $jugadoresAsignar = explode(",", $PlayersChosen);


        foreach ($jugadoresAsignar as $item) {

            array_push($jugadoresAsignarFinal, array("Id" => $item, "Valor" => $AutomaticForfeitureLevel));

        }

    }


    /* Se inicializa un arreglo vacío llamado codigosarray en PHP. */
    $codigosarray = array();

    for ($i = 1; $i <= $FreeSpinsTotalCount; $i++) {

        /* Genera un código único de 4 caracteres no presente en el array de códigos. */
        $codigo = GenerarClaveTicket(4);

        while (in_array($codigo, $codigosarray)) {
            $codigo = GenerarClaveTicket(4);
        }


        $usuarioId = '0';

        /* Se asignan varios valores a partir de $AutomaticForfeitureLevel y se define un estado. */
        $valor = $AutomaticForfeitureLevel;

        $valor_lealtad = $AutomaticForfeitureLevel;

        $valor_base = $AutomaticForfeitureLevel;

        $estado = 'L';


        /* Se inicializan cuatro variables con valor '0' para gestionar errores y usuarios. */
        $errorId = '0';

        $idExterno = '0';

        $mandante = '0';


        $usucreaId = '0';


        /* inicializa variables relacionadas con apuestas y un código prefijado. */
        $usumodifId = '0';


        $apostado = '0';
        $rollowerRequerido = '0';
        $codigo = $Prefix . $codigo;


        /* Código que inicializa un objeto UsuarioBono y establece sus propiedades. */
        $UsuarioBono = new UsuarioBono();

        $UsuarioBono->setUsuarioId($usuarioId);
        $UsuarioBono->setBonoId($bonoId);
        $UsuarioBono->setValor($valor);
        $UsuarioBono->setValorBono($valor_lealtad);

        /* establece varios atributos de un objeto UsuarioBono. */
        $UsuarioBono->setValorBase($valor_base);
        $UsuarioBono->setEstado($estado);
        $UsuarioBono->setErrorId($errorId);
        $UsuarioBono->setIdExterno($idExterno);
        $UsuarioBono->setMandante($mandante);
        $UsuarioBono->setUsucreaId($usucreaId);

        /* Se configuran propiedades de UsuarioBono y se crea un objeto UsuarioBonoMySqlDAO. */
        $UsuarioBono->setUsumodifId($usumodifId);
        $UsuarioBono->setApostado($apostado);
        $UsuarioBono->setRollowerRequerido($rollowerRequerido);
        $UsuarioBono->setCodigo($codigo);

        $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($transaccion);


        /* Inserta un bono y asigna un código a jugadores en un array. */
        $UsuarioBonoMysqlDAO->insert($UsuarioBono);

        if ($jugadoresAsignarFinal[$i]["Id"] != "") {
            $jugadoresAsignarFinal[$i]["Code"] = $codigo;
        }

        array_push($codigosarray, $codigo);

    }
}


/* determina si se otorgan privilegios a usuarios específicos según ciertas condiciones. */
$darAUsuariosEspecificos = false;
if (($MaxplayersCount != "" && $Prefix != "") || ($MaxplayersCount != "" && ($MinimumAmount) != '')) {
    $darAUsuariosEspecificos = true;
}

if ($darAUsuariosEspecificos) {


    /* Se crean dos arreglos vacíos para asignar jugadores en un sistema. */
    $jugadoresAsignar = array();
    $jugadoresAsignarFinal = array();

    foreach ($MinimumAmount as $key => $value) {


        /* asigna montos a jugadores según su moneda correspondiente. */
        $jugadoresAsignar = explode(",", $value->Amount);

        foreach ($MaxPayout as $key2 => $value2) {

            if ($value->CurrencyId == $value2->CurrencyId) {

                foreach ($jugadoresAsignar as $item) {

                    if ($item != 0) {

                        array_push($jugadoresAsignarFinal, array("Id" => $item, "Valor" => $value2->Amount));

                    }

                }

            }
        }

    }


    /* Se define un array vacío en PHP llamado $codigosarray. */
    $codigosarray = array();


    for ($i = 0; $i < $MaxplayersCount; $i++) {

        /* Genera un código único mediante la función "GenerarClaveTicket", evitando duplicados. */
        $codigo = GenerarClaveTicket(4);

        while (in_array($codigo, $codigosarray)) {
            $codigo = GenerarClaveTicket(4);
        }


        $usuarioId = '0';

        /* Define el estado de un usuario según su tipo de bono y lealtad. */
        $estado = 'L';

        /*if ($tipobono != 2) {
            if ($jugadoresAsignarFinal[$i] != "" && $jugadoresAsignarFinal[$i]["Id"] != null) {
                $usuarioId = $jugadoresAsignarFinal[$i]["Id"];
                $estado = 'A';

            }

        }*/

        if ($tipolealtad == "2") {
            if ($jugadoresAsignarFinal[$i] != "" && $jugadoresAsignarFinal[$i]["Id"] != null) {
                $usuarioId = $jugadoresAsignarFinal[$i]["Id"];
                $estado = 'P';

            }
        }


        /* inicializa variables en PHP con valores predeterminados de cero. */
        $valor = '0';

        $valor_lealtad = '0';

        $valor_base = '0';

        $errorId = '0';


        /* Asignación de variables iniciales con valores predeterminados en un código PHP. */
        $idExterno = '0';

        $mandante = '0';


        $usucreaId = '0';


        /* Asignación de variables en PHP, concatenando un prefijo al código. */
        $usumodifId = '0';


        $apostado = '0';
        $rollowerRequerido = '0';
        $codigo = $Prefix . $codigo;


        /* Se crea un objeto UsuarioBono y se le asignan propiedades relacionadas con un bono. */
        $UsuarioBono = new UsuarioBono();

        $UsuarioBono->setUsuarioId($usuarioId);
        $UsuarioBono->setBonoId($bonoId);
        $UsuarioBono->setValor($valor);
        $UsuarioBono->setValorBono($valor_lealtad);

        /* Se configuran propiedades de un objeto UsuarioBono con valores dados. */
        $UsuarioBono->setValorBase($valor_base);
        $UsuarioBono->setEstado($estado);
        $UsuarioBono->setErrorId($errorId);
        $UsuarioBono->setIdExterno($idExterno);
        $UsuarioBono->setMandante($mandante);
        $UsuarioBono->setUsucreaId($usucreaId);

        /* configura propiedades del objeto UsuarioBono con valores específicos. */
        $UsuarioBono->setUsumodifId($usumodifId);
        $UsuarioBono->setApostado($apostado);
        $UsuarioBono->setRollowerRequerido($rollowerRequerido);
        $UsuarioBono->setCodigo($codigo);
        $UsuarioBono->setVersion(0);
        $UsuarioBono->setExternoId(0);


        /* Crea un objeto DAO, inserta datos y asigna un código a jugadores. */
        $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($transaccion);

        $inse = $UsuarioBonoMysqlDAO->insert($UsuarioBono);


        if ($jugadoresAsignarFinal[$i]["Id"] != "") {

            $jugadoresAsignarFinal[$i]["Code"] = $codigo;
        }


        /* Añade el valor de $codigo al final del array $codigosarray. */
        array_push($codigosarray, $codigo);

    }
}


/* guarda datos de lealtad tras confirmar una transacción en base de datos. */
$transaccion->commit();

$LealtadInterna = new LealtadInterna();
$LealtadInterna->nombre = $Name;
$LealtadInterna->descripcion = $Description;
$LealtadInterna->fechaInicio = $StartDate;

/* Asignación de valores a propiedades de un objeto $LealtadInterna. */
$LealtadInterna->fechaFin = $EndDate;
$LealtadInterna->tipo = $tipolealtad;
$LealtadInterna->estado = 'A';
$LealtadInterna->usucreaId = 0;
$LealtadInterna->usumodifId = 0;
$LealtadInterna->mandante = $mandanteUsuario;

/* Se asignan valores a las propiedades del objeto $LealtadInterna. */
$LealtadInterna->condicional = $ConditionProduct;
$LealtadInterna->puntos = $points;
$LealtadInterna->cupoActual = $cupo;
$LealtadInterna->cupoMaximo = $cupoMaximo;
$LealtadInterna->cantidadLealtad = $jugadores;
$LealtadInterna->maximoLealtad = $jugadoresMaximo;

/* asigna reglas y un bono, luego obtiene una transacción de la base de datos. */
$LealtadInterna->reglas = $RulesText;
$LealtadInterna->bonoId = $bonoId;


$LealtadDetalleMySqlDAO = new LealtadDetalleMySqlDAO();
$transaccion = $LealtadDetalleMySqlDAO->getTransaction();


/* inserta una transacción en la base de datos y obtiene su ID. */
$lealtadId = $LealtadInterna->insert($transaccion);

/*
if($MaxplayersCount != "" && $Prefix != ""){

    $codigosarray=array();

    for ($i = 1; $i <= $MaxplayersCount; $i++) {
        $codigo = GenerarClaveTicket(4);

        while(in_array($codigo,$codigosarray)){
            $codigo = GenerarClaveTicket(4);
        }



        $PromocionalLog = new PromocionalLog();

        $PromocionalLog->usuarioId= '0' ;

        $PromocionalLog->promocionalId=$lealtadId ;

        $PromocionalLog->valor= '';

        $PromocionalLog->valorPromocional= '';

        $PromocionalLog->valorBase= '';

        $PromocionalLog->estado= 'L';

        $PromocionalLog->errorId= '';

        $PromocionalLog->idExterno= '';

        $PromocionalLog->mandante= '0';

        $PromocionalLog->version= '2';

        $PromocionalLog->usucreaId= '0';

        $PromocionalLog->usumodifId= '0';


        $PromocionalLog->apostado= '0';
        $PromocionalLog->rollowerRequerido= '0';
        $PromocionalLog->codigo= $Prefix . $codigo;

        $PromocionalLogMySqlDAO= new PromocionalLogMySqlDAO();
        $PromocionalLogMySqlDAO->insert($PromocionalLog);
        $PromocionalLogMySqlDAO->getTransaction()->commit();

        array_push($codigosarray,$codigo);

    }
}

*/

//Expiracion


/* Crea y guarda un objeto de LealtadDetalle si ExpirationDays no está vacío. */
if ($ExpirationDays != "") {
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


/* Inserta un nuevo detalle de lealtad si $UserRepeatLealtad no está vacío. */
if ($UserRepeatLealtad != "") {

    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "REPETIRBONO";
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $UserRepeatLealtad;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);


}


/* Crea un detalle de lealtad si se utiliza una billetera congelada. */
if ($UseFrozeWallet != "") {

    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "FROZEWALLET";
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $UseFrozeWallet;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);
}


/* Crea un registro de lealtad si hay un valor para Supresión de Retirada. */
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


/* Inserta un registro de lealtad si $ScheduleCount no está vacío. */
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


/* Inserta un nuevo detalle de lealtad si el nombre del horario no está vacío. */
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


/* Inserta un nuevo registro de lealtad si $SchedulePeriod no está vacío. */
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


/* Inserta un registro de lealtad si $SchedulePeriodType no está vacío. */
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


/* Inserta un registro de detalle de lealtad si el tipo de producto está definido. */
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


/* Inserta un nuevo registro en la base de datos si $Count no está vacío. */
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


/* Inserta un nuevo registro de "LealtadDetalle" si $AreAllowed no está vacío. */
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


/* Inserta un detalle de lealtad si la fecha de expiración no está vacía. */
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


/* inserta un registro de lealtad si se cumplen ciertas condiciones. */
if ($TypeLealtadDeposit == '1') {


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


/* Inserta un nuevo objeto LealtadDetalle en la base de datos si LealtadWFactor no está vacío. */
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


/* Inserta un registro de lealtad si $DepositWFactor no está vacío. */
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


/* Inserta un registro de detalle de lealtad si el número de depósito no está vacío. */
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


/* Inserta un detalle de lealtad si proviene de un cajero. */
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


/* Crea un registro de lealtad si el conteo máximo de jugadores no está vacío. */
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


/* Inserta un nuevo registro de lealtad si $WinLealtadId es diferente de cero. */
if ($WinLealtadId != 0) {

    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "WINBONOID";
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $WinLealtadId;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);


} else {
    /* Inserta un nuevo registro de LealtadDetalle en la base de datos. */


    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "TIPOSALDO";
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $TypeSaldo;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);

}


/* Valida condiciones y guarda detalles de lealtad en la base de datos si se cumplen. */
if ($WinLealtadId == "" || $WinLealtadId == "0") {

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


} else {
    /* Inserta detalles de lealtad en la base de datos a partir de un arreglo. */


    foreach ($MaxPayout as $key => $value) {
        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "VALORROLLOWER";
        $LealtadDetalle->moneda = $value->CurrencyId;
        $LealtadDetalle->valor = $value->Amount;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);
    }

}
if ($Points != 0) {


    /* Inserta detalles de lealtad en la base de datos para cada punto procesado. */
    foreach ($Points as $key => $value) {
        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "PUNTOS";
        $LealtadDetalle->moneda = $value->CurrencyId;
        $LealtadDetalle->valor = $value->Amount;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);
    }

    /* Inserta detalles de lealtad en base de datos si existe una URL de imagen principal. */
    if ($MainImageURL != "") {
        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "IMGPPALURL";
        $LealtadDetalle->moneda = '';
        $LealtadDetalle->valor = $MainImageURL;
        $LealtadDetalle->valor2 = '';
        $LealtadDetalle->valor3 = '';
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);
    }

}

if ($tipolealtad == "2") {


    /* inserta detalles de lealtad en una base de datos mediante un bucle. */
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


    /* inserta detalles de lealtad para depósitos mínimos en una base de datos. */
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
}


/* Inserta detalles de lealtad en la base de datos si el tipo es '0'. */
if ($TypeLealtadDeposit == '0') {

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


/* inserta detalles de lealtad en una base de datos para cada pago. */
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


/* Inserta detalles de lealtad en la base de datos para cada región proporcionada. */
foreach ($Regions as $key => $value) {

    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "CONDPAISPV";
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $value;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);

}


/* Crea y guarda detalles de lealtad para departamentos en una base de datos. */
foreach ($Departments as $key => $value) {

    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "CONDDEPARTAMENTOPV";
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $value;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);

}


/* Crea y guarda detalles de lealtad para cada ciudad en una base de datos. */
foreach ($Cities as $key => $value) {

    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "CONDCIUDADPV";
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $value;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);

}


/* Inserta un nuevo registro en LealtadDetalle si el balance es cero. */
if ($BalanceZero == "true" || $BalanceZero == 1 || $BalanceZero == true) {
    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "CONDBALANCE";
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = '0';
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);
}


/* Itera sobre regiones, creando e insertando objetos LealtadDetalle en la base de datos. */
foreach ($RegionsUser as $key => $value) {

    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "CONDPAISUSER";
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $value;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);

}


/* Itera sobre departamentos, creando e insertando detalles de lealtad en la base de datos. */
foreach ($DepartmentsUser as $key => $value) {

    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "CONDDEPARTAMENTOUSER";
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $value;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);


}


/* inserta detalles de lealtad por cada ciudad de un usuario. */
foreach ($CitiesUser as $key => $value) {

    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "CONDCIUDADUSER";
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $value;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);

}


/* Itera sobre $CashDesks y guarda detalles en la base de datos. */
foreach ($CashDesks as $key => $value) {

    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "CONDPUNTOVENTA";
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $value;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);

}


/* Se insertan detalles de lealtad para cada juego en la base de datos. */
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


/* Crea y guarda detalles de lealtad para cada categoría de casino en un bucle. */
foreach ($CasinoCategory as $key => $value) {
    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "CONDCATEGORY" . $value->Id;
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $value->Percentage;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);

}


/* inserta detalles de lealtad en una base de datos para cada proveedor de casino. */
foreach ($CasinoProvider as $key => $value) {
    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "CONDSUBPROVIDER" . $value->Id;
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $value->Percentage;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);

}


/* Inserta detalles de lealtad en la base de datos para cada producto de casino. */
foreach ($CasinoProduct as $key => $value) {
    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "CONDGAME" . $value->Id;
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $value->Percentage;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);

}


/* inserta detalles de lealtad en una base de datos usando un bucle. */
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


/* Inserta un registro de detalles de lealtad si está definido LiveOrPreMatch. */
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


/* Inserta un nuevo objeto LealtadDetalle en la base de datos si $MinSelCount no está vacío. */
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


/* Inserta un detalle de lealtad si el precio mínimo es válido. */
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


/* Inserta un nuevo registro en LealtadDetalle si $MinSelPriceTotal no está vacío. */
if ($MinSelPriceTotal != "") {
    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "MINSELPRICETOTAL";
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $MinSelPriceTotal;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);

}


foreach ($LealtadDetails as $key => $value) {

    /* Se inserta un detalle de lealtad si el monto mínimo es válido. */
    if ($value->MinAmount != "") {
        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "MINAMOUNT";
        $LealtadDetalle->moneda = $value->CurrencyId;
        $LealtadDetalle->valor = $value->MinAmount;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);


    }

    /* Inserta un nuevo detalle de lealtad si MaxAmount no está vacío. */
    if ($value->MaxAmount != "") {
        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "MAXAMOUNT";
        $LealtadDetalle->moneda = $value->CurrencyId;
        $LealtadDetalle->valor = $value->MaxAmount;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);

    }
    if ($value->Amount != "") {

        /* Crea un registro de lealtad si el monto mínimo no está vacío. */
        if ($value->Amount->MinAmount != "") {
            $LealtadDetalle = new LealtadDetalle();
            $LealtadDetalle->lealtadId = $lealtadId;
            $LealtadDetalle->tipo = "MINAMOUNT";
            $LealtadDetalle->moneda = $value->CurrencyId;
            $LealtadDetalle->valor = $value->Amount->MinAmount;
            $LealtadDetalle->usucreaId = 0;
            $LealtadDetalle->usumodifId = 0;
            $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
            $LealtadDetalleMysqlDAO->insert($LealtadDetalle);


        }

        /* Inserta un registro de lealtad si MaxAmount no está vacío. */
        if ($value->Amount->MaxAmount != "") {
            $LealtadDetalle = new LealtadDetalle();
            $LealtadDetalle->lealtadId = $lealtadId;
            $LealtadDetalle->tipo = "MAXAMOUNT";
            $LealtadDetalle->moneda = $value->CurrencyId;
            $LealtadDetalle->valor = $value->Amount->MaxAmount;
            $LealtadDetalle->usucreaId = 0;
            $LealtadDetalle->usumodifId = 0;
            $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
            $LealtadDetalleMysqlDAO->insert($LealtadDetalle);

        }
    }
}


/* Inserta un detalle de lealtad si se proporciona un ID de trigger y un código promocional. */
if ($TriggerId != "") {
    if ($CodePromo != "") {

        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "CODEPROMO";
        $LealtadDetalle->moneda = '';
        $LealtadDetalle->valor = $CodePromo;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);

    }
}


/* Crea un registro de detalles de lealtad si se cumple una condición específica. */
if ($MinBetPrice != "" && false) {
    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "MINBETPRICE";
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $MinBetPrice;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);
}

if ($FreeSpinsTotalCount != "" && $Prefix != "") {

    /* Asigna jugadores elegidos a una lista con su respectivo valor de penalización. */
    $jugadoresAsignar = array();
    $jugadoresAsignarFinal = array();

    if ($PlayersChosen != "") {
        $jugadoresAsignar = explode(",", $PlayersChosen);


        foreach ($jugadoresAsignar as $item) {

            array_push($jugadoresAsignarFinal, array("Id" => $item, "Valor" => $AutomaticForfeitureLevel));

        }

    }


    /* Se inicializa un array vacío llamado 'codigosarray' en PHP. */
    $codigosarray = array();

    for ($i = 1; $i <= $FreeSpinsTotalCount; $i++) {

        /* Genera un código único de 4 caracteres y verifica su existencia en un arreglo. */
        $codigo = GenerarClaveTicket(4);

        while (in_array($codigo, $codigosarray)) {
            $codigo = GenerarClaveTicket(4);
        }


        $usuarioId = '0';

        /* Asignación de variables relacionadas con el nivel de pérdida automática y estado de lealtad. */
        $valor = $AutomaticForfeitureLevel;

        $valor_lealtad = $AutomaticForfeitureLevel;

        $valor_base = $AutomaticForfeitureLevel;

        $estado = 'L';


        /* Asignación de valores iniciales a variables relacionadas con errores y usuarios. */
        $errorId = '0';

        $idExterno = '0';

        $mandante = '0';


        $usucreaId = '0';


        /* Inicializa variables y concatena un prefijo al código en PHP. */
        $usumodifId = '0';


        $apostado = '0';
        $rollowerRequerido = '0';
        $codigo = $Prefix . $codigo;


        /* Se crea un objeto UsuarioLealtad y se configuran sus propiedades. */
        $UsuarioLealtad = new UsuarioLealtad();

        $UsuarioLealtad->setUsuarioId($usuarioId);
        $UsuarioLealtad->setLealtadId($lealtadId);
        $UsuarioLealtad->setValor($valor);
        $UsuarioLealtad->setValorLealtad($valor_lealtad);

        /* configura propiedades de un objeto UsuarioLealtad con diversos valores. */
        $UsuarioLealtad->setValorBase($valor_base);
        $UsuarioLealtad->setEstado($estado);
        $UsuarioLealtad->setErrorId($errorId);
        $UsuarioLealtad->setIdExterno($idExterno);
        $UsuarioLealtad->setMandante($mandante);
        $UsuarioLealtad->setUsucreaId($usucreaId);

        /* Configura propiedades de un objeto UsuarioLealtad y crea su DAO correspondiente. */
        $UsuarioLealtad->setUsumodifId($usumodifId);
        $UsuarioLealtad->setApostado($apostado);
        $UsuarioLealtad->setRollowerRequerido($rollowerRequerido);
        $UsuarioLealtad->setCodigo($codigo);

        $UsuarioLealtadMysqlDAO = new UsuarioLealtadMySqlDAO($transaccion);


        /* inserta datos y asigna códigos a jugadores en un array. */
        $UsuarioLealtadMysqlDAO->insert($UsuarioLealtad);

        if ($jugadoresAsignarFinal[$i]["Id"] != "") {
            $jugadoresAsignarFinal[$i]["Code"] = $codigo;
        }

        array_push($codigosarray, $codigo);

    }
}


/* verifica condiciones para habilitar usuarios específicos. */
$darAUsuariosEspecificos = false;
if (($MaxplayersCount != "" && $Prefix != "") || ($MaxplayersCount != "" && ($MinimumAmount) != '')) {
    $darAUsuariosEspecificos = true;
}

if ($darAUsuariosEspecificos) {


    /* Se crean dos arreglos vacíos para almacenar jugadores asignados y finales. */
    $jugadoresAsignar = array();
    $jugadoresAsignarFinal = array();

    foreach ($MinimumAmount as $key => $value) {


        /* asigna valores a jugadores según su moneda, filtrando y estructurando datos. */
        $jugadoresAsignar = explode(",", $value->Amount);

        foreach ($MaxPayout as $key2 => $value2) {

            if ($value->CurrencyId == $value2->CurrencyId) {

                foreach ($jugadoresAsignar as $item) {

                    if ($item != 0) {

                        array_push($jugadoresAsignarFinal, array("Id" => $item, "Valor" => $value2->Amount));

                    }

                }

            }
        }

    }


    /* Se inicializa un arreglo vacío llamado $codigosarray en PHP. */
    $codigosarray = array();


    for ($i = 0; $i < $MaxplayersCount; $i++) {

        /* Genera un código único de 4 caracteres que no está en el array existente. */
        $codigo = GenerarClaveTicket(4);

        while (in_array($codigo, $codigosarray)) {
            $codigo = GenerarClaveTicket(4);
        }


        $usuarioId = '0';

        /* asigna estados basados en condiciones de lealtad y jugadores. */
        $estado = 'L';

        /*if ($tipolealtad != 2) {
            if ($jugadoresAsignarFinal[$i] != "" && $jugadoresAsignarFinal[$i]["Id"] != null) {
                $usuarioId = $jugadoresAsignarFinal[$i]["Id"];
                $estado = 'A';

            }

        }*/

        if ($tipolealtad == "2") {
            if ($jugadoresAsignarFinal[$i] != "" && $jugadoresAsignarFinal[$i]["Id"] != null) {
                $usuarioId = $jugadoresAsignarFinal[$i]["Id"];
                $estado = 'P';

            }
        }


        /* inicializa variables con valores predeterminados de tipo string. */
        $valor = '0';

        $valor_lealtad = '0';

        $valor_base = '0';

        $errorId = '0';


        /* Inicializa variables con valor '0' para uso posterior en el código. */
        $idExterno = '0';

        $mandante = '0';


        $usucreaId = '0';


        /* Asignaciones de variables iniciales y concatenación de un prefijo a un código. */
        $usumodifId = '0';


        $apostado = '0';
        $rollowerRequerido = '0';
        $codigo = $Prefix . $codigo;


        /* Crea un objeto de lealtad de usuario y establece sus propiedades correspondientes. */
        $UsuarioLealtad = new UsuarioLealtad();

        $UsuarioLealtad->setUsuarioId($usuarioId);
        $UsuarioLealtad->setLealtadId($lealtadId);
        $UsuarioLealtad->setValor($valor);
        $UsuarioLealtad->setValorLealtad($valor_lealtad);

        /* establece parámetros para un objeto de usuario leal. */
        $UsuarioLealtad->setValorBase($valor_base);
        $UsuarioLealtad->setEstado($estado);
        $UsuarioLealtad->setErrorId($errorId);
        $UsuarioLealtad->setIdExterno($idExterno);
        $UsuarioLealtad->setMandante($mandante);
        $UsuarioLealtad->setUsucreaId($usucreaId);

        /* establece propiedades de un objeto UsuarioLealtad en PHP. */
        $UsuarioLealtad->setUsumodifId($usumodifId);
        $UsuarioLealtad->setApostado($apostado);
        $UsuarioLealtad->setRollowerRequerido($rollowerRequerido);
        $UsuarioLealtad->setCodigo($codigo);
        $UsuarioLealtad->setVersion(0);
        $UsuarioLealtad->setExternoId(0);


        /* inserta datos de usuario y asigna un código si el ID no está vacío. */
        $UsuarioLealtadMysqlDAO = new UsuarioLealtadMySqlDAO($transaccion);

        $inse = $UsuarioLealtadMysqlDAO->insert($UsuarioLealtad);


        if ($jugadoresAsignarFinal[$i]["Id"] != "") {

            $jugadoresAsignarFinal[$i]["Code"] = $codigo;
        }


        /* Agrega un elemento `$codigo` al final del array `$codigosarray`. */
        array_push($codigosarray, $codigo);

    }
}


/* Confirma los cambios realizados en una transacción en la base de datos. */
$transaccion->commit();


if ($darAUsuariosEspecificos) {


    if ($tipolealtad != 2) {

        for ($i = 0; $i < oldCount($jugadoresAsignarFinal); $i++) {

            if ($jugadoresAsignarFinal[$i]["Id"] != "") {

                /* Consulta SQL para obtener información de un usuario y su ubicación geográfica. */
                $LealtadInterna = new LealtadInterna();

                $usuarioSql = "select ciudad.ciudad_id,ciudad.depto_id,pais.pais_id,usuario.moneda,usuario.mandante FROM registro
  INNER JOIN usuario ON (usuario.usuario_id = registro.usuario_id)
  INNER JOIN ciudad ON (ciudad.ciudad_id = registro.ciudad_id)
  INNER JOIN departamento ON (ciudad.depto_id = departamento.depto_id)
  INNER JOIN pais ON (pais.pais_id = departamento.pais_id) WHERE registro.usuario_id='" . $jugadoresAsignarFinal[$i]["Id"] . "'";

                $Usuario = $LealtadInterna->execQuery($transaccion, $usuarioSql);


                $dataUsuario = $Usuario;

                /* Verifica si el usuario tiene mandante y agrega detalles de lealtad. */
                if ($dataUsuario[0]->{'usuario.mandante'} != "") {
                    $detalles = array(
                        "PaisUSER" => $dataUsuario[0]->{'pais.pais_id'},
                        "DepartamentoUSER" => $dataUsuario[0]->{'ciudad.depto_id'},
                        "CiudadUSER" => $dataUsuario[0]->{'ciudad.ciudad_id'},
                        "MonedaUSER" => $dataUsuario[0]->{'usuario.moneda'},
                        "ValorDeposito" => 0

                    );
                    $detalles = json_decode(json_encode($detalles));

                    $respuesta = $LealtadInterna->agregarLealtadFree($lealtadId, $jugadoresAsignarFinal[$i]["Id"], $dataUsuario[0]->{'usuario.mandante'}, $detalles, true, $jugadoresAsignarFinal[$i]["Code"], $transaccion);

                }

            }

        }
    }
}


/* Se crea un objeto DAO y se obtiene una transacción asociada. */
$LealtadDetalleMySqlDAO = new LealtadDetalleMySqlDAO();
$transaccion = $LealtadDetalleMySqlDAO->getTransaction();


if ($FreeSpinsTotalCount != "" && $Prefix != "") {

    for ($i = 1; $i <= oldCount($jugadoresAsignarFinal); $i++) {


        /* Consulta SQL que une tablas para obtener información del usuario según su ID. */
        $LealtadInterna = new LealtadInterna();

        $usuarioSql = "select ciudad.ciudad_id,ciudad.depto_id,pais.pais_id,usuario.mandante FROM registro
  INNER JOIN ciudad ON (ciudad.ciudad_id = registro.ciudad_id)
  INNER JOIN departamento ON (ciudad.depto_id = departamento.depto_id)
  INNER JOIN pais ON (pais.pais_id = departamento.pais_id) WHERE registro.usuario_id='" . $jugadoresAsignarFinal[$i]["Id"] . "'";

        $Usuario = $LealtadInterna->execQuery($transaccion, $usuarioSql);


        $dataUsuario = $Usuario;

        /* Código PHP que crea un arreglo con información de ubicación del usuario. */
        $detalles = array(
            "PaisUSER" => $dataUsuario[0]->pais_id,
            "DepartamentoUSER" => $dataUsuario[0]->depto_id,
            "CiudadUSER" => $dataUsuario[0]->ciudad_id,

        );

        /* Convierte detalles a objeto y llama función para agregar lealtad con parámetros específicos. */
        $detalles = json_decode(json_encode($detalles));


        $respuesta = $LealtadInterna->agregarLealtadFree($lealtadId, $jugadoresAsignarFinal[$i]["Id"], $dataUsuario[0]->{'usuario.mandante'}, $detalles, true, $jugadoresAsignarFinal[$i]["Code"], $transaccion);


    }
}
if ($MaxplayersCount != "" && $Prefix != "") {
    if ($tipolealtad == 2) {

        for ($i = 0; $i < oldCount($jugadoresAsignarFinal); $i++) {

            if ($jugadoresAsignarFinal[$i]["Id"] != "") {

                /* Consulta SQL que obtiene información de usuario y localidad de varias tablas. */
                $LealtadInterna = new LealtadInterna();

                $usuarioSql = "select ciudad.ciudad_id,ciudad.depto_id,pais.pais_id,usuario.mandante FROM registro
  INNER JOIN ciudad ON (ciudad.ciudad_id = registro.ciudad_id)
  INNER JOIN departamento ON (ciudad.depto_id = departamento.depto_id)
  INNER JOIN pais ON (pais.pais_id = departamento.pais_id) WHERE registro.usuario_id='" . $jugadoresAsignarFinal[$i]["Id"] . "'";

                $Usuario = $LealtadInterna->execQuery($transaccion, $usuarioSql);


                $dataUsuario = $Usuario;

                /* Se crea un array con información de ubicación del usuario. */
                $detalles = array(
                    "PaisUSER" => $dataUsuario[0]->pais_id,
                    "DepartamentoUSER" => $dataUsuario[0]->depto_id,
                    "CiudadUSER" => $dataUsuario[0]->ciudad_id,

                );

                /* Convierte detalles a objeto y llama a función para agregar lealtad en sistema. */
                $detalles = json_decode(json_encode($detalles));


                $respuesta = $LealtadInterna->agregarLealtadFree($lealtadId, $jugadoresAsignarFinal[$i]["Id"], $dataUsuario[0]->{'usuario.mandante'}, $detalles, true, $jugadoresAsignarFinal[$i]["Code"], $transaccion);

            }

        }
    }
}
//$transaccion->commit();


/* Código para manejar respuestas, indicando errores y mensajes en aplicaciones web. */
$response["HasError"] = false;
$response["AlertType"] = "danger";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response["Result"] = array();
