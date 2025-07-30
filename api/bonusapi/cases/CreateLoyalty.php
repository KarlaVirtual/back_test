<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

//  error_reporting(E_ALL);
// ini_set('display_errors', 'ON');
use Backend\dto\LealtadDetalle;
use Backend\dto\LealtadInterna;
use Backend\dto\UsuarioLealtad;
use Backend\mysql\LealtadDetalleMySqlDAO;
use Backend\mysql\UsuarioLealtadMySqlDAO;

/**
 * Este script gestiona la creación de campañas de lealtad, configurando parámetros y detalles
 * relacionados con premios, reglas, usuarios y condiciones específicas.
 *
 * @param object $params Objeto que contiene los siguientes valores:
 * @param string $params->Description Descripción de la lealtad.
 * @param string $params->Name Nombre de la lealtad.
 * @param string $params->BeginDate Fecha de inicio de la lealtad.
 * @param string $params->EndDate Fecha de finalización de la lealtad.
 * @param object $params->PartnerLealtad Objeto que incluye:
 *  - string ExpirationDate Fecha de expiración de la lealtad.
 * - int ExpirationDays Días de expiración de la lealtad.
 * @param string $params->ExpirationDate Fecha de expiración alternativa.
 * @param int $params->ExpirationDays Días de expiración alternativos.
 * @param string $params->LiveOrPreMatch Indica si es en vivo o pre-match.
 * @param int $params->MinSelCount Cantidad mínima de selecciones.
 * @param float $params->MinSelPrice Cuota mínima seleccionada.
 * @param string $params->MainImageURL URL de la imagen principal.
 * @param float $params->CurrentCost Costo actual.
 * @param object $params->DepositDefinition Objeto que incluye:
 *  - string LealtadDefinition Definición de lealtad.
 * - int LealtadDefinitionId ID de la definición de lealtad.
 * - float LealtadPercent Porcentaje de lealtad.
 * - float LealtadWFactor Factor de rollower de lealtad.
 * - int DepositNumber Número de depósitos.
 * - float DepositWFactor Factor de rollower de depósito.
 * - bool SuppressWithdrawal Indica si se suprime el retiro.
 * - bool UseFrozeWallet Indica si se utiliza billetera congelada.
 * @param string $params->MBody Cuerpo del mensaje.
 * @param string $params->MSubject Asunto del mensaje.
 * @param int $params->Priority Prioridad de la lealtad.
 * @param bool $params->IsVisibleForAllplayers Indica si es visible para todos los jugadores.
 * @param bool $params->OpenToAllPlayers Indica si está abierto a todos los jugadores.
 * @param int $params->MaxplayersCount Número máximo de jugadores.
 * @param string $params->Prefix Prefijo para códigos generados.
 * @param array $params->MarketingCampaingIdsSelectList Lista de IDs de campañas de marketing.
 * @param string $params->TypeProduct Tipo de producto asociado.
 * @param object $params->ForeignRule Objeto que incluye:
 * - mixed Info Información de reglas extranjeras.
 * @param int $params->TriggerId ID del disparador.
 * @param int $params->TypeId Tipo de lealtad.
 * @param array $params->Games Lista de juegos asociados.
 * @param array $params->MaxPayout Lista de pagos máximos por moneda.
 * @param float $params->MaximumLealtadAmount Monto máximo de lealtad.
 * @param array $params->MaximumDeposit Lista de depósitos máximos por moneda.
 * @param array $params->MinimumDeposit Lista de depósitos mínimos por moneda.
 * @param array $params->MinimumAmount Lista de montos mínimos por moneda.
 * @param array $params->MaximumAmount Lista de montos máximos por moneda.
 * @param array $params->MoneyRequirement Requisitos monetarios por moneda.
 * @param float $params->MoneyRequirementAmount Monto requerido.
 * @param object $params->Schedule Objeto que incluye:
 * - int Count Cantidad de programaciones.
 * - string Name Nombre de la programación.
 * - string Period Período de la programación.
 * - string PeriodType Tipo de período de la programación.
 * @param object $params->TriggerDetails Objeto que incluye:
 * - int Count Cantidad de disparadores.
 * - bool IsFromCashDesk Indica si proviene de caja.
 * - int PaymentSystemId ID del sistema de pago.
 * - array PaymentSystemIds Lista de IDs de sistemas de pago.
 * - array Regions Lista de regiones.
 * - array Departments Lista de departamentos.
 * - array Cities Lista de ciudades.
 * - array CashDesks Lista de puntos de venta.
 * - array RegionsUser Lista de regiones de usuario.
 * - array DepartmentsUser Lista de departamentos de usuario.
 * - array CitiesUser Lista de ciudades de usuario.
 * - bool BalanceZero Indica si el balance es cero.
 * @param string $params->ConditionProduct Condición del producto.
 * @param string $params->TypeId Tipo de lealtad.
 * 
 * 
 *
 * @return array $response Respuesta del sistema con los siguientes valores:
 *  - HasError: bool Indica si hubo un error.
 *  - AlertType: string Tipo de alerta.
 *  - AlertMessage: string Mensaje de alerta.
 *  - LealtadId: int ID de la lealtad creada.
 *  - ModelErrors: array Lista de errores del modelo.
 *  - Result: array Resultado de la operación.
 */

/* Establece el mandante del usuario si no es de Global. */
$mandanteUsuario = 0;
// Si el usuario esta condicionado por el mandante y no es de Global
if ($_SESSION['Global'] == "N") {
    $mandanteUsuario = $_SESSION['mandante'];
}


$Description = $params->Description; //Descripcion del lealtad

/* Código que asigna variables relacionadas con una campaña de lealtad y su socio. */
$Name = $params->Name; //Nombre del lealtad
$StartDate = $params->BeginDate; //Fecha Inicial de la campaña
$EndDate = $params->EndDate; //Fecha Final de la campaña

$PartnerLealtad = $params->PartnerLealtad;

$ExpirationDate = $PartnerLealtad->ExpirationDate; //Fecha de expiracion del lealtad

/* Asignación de fechas de expiración si no están definidas en el objeto de lealtad. */
$ExpirationDays = $PartnerLealtad->ExpirationDays; // Dias de expiracion del lealtad


if ($ExpirationDate == "" && $ExpirationDays == "") {
    $ExpirationDate = $params->ExpirationDate; //Fecha de expiracion del lealtad
    $ExpirationDays = $params->ExpirationDays; // Dias de expiracion del lealtad

}


/* asigna parámetros a variables para su posterior uso en la aplicación. */
$LiveOrPreMatch = $params->LiveOrPreMatch;
$MinSelCount = $params->MinSelCount;
$MinSelPrice = $params->MinSelPrice;

$MainImageURL = $params->MainImageURL;
$CurrentCost = $params->CurrentCost;


/* Extrae valores de parámetros y define variables relacionadas con el depósito y lealtad. */
$DepositDefinition = $params->DepositDefinition;
$MBody = $params->MBody;
$MSubject = $params->MSubject;
$Order = $params->Priority;

$LealtadDefinition = $DepositDefinition->LealtadDefinition;

/* Asignación de variables relacionadas con definiciones de depósitos y lealtad. */
$LealtadDefinitionId = $DepositDefinition->LealtadDefinitionId;
$LealtadPercent = $DepositDefinition->LealtadPercent; // Porcentaje del lealtad
$LealtadWFactor = $DepositDefinition->LealtadWFactor; //Rollower lealtad
$DepositNumber = $DepositDefinition->DepositNumber;
$DepositWFactor = $DepositDefinition->DepositWFactor; //Rollower Deposito

$SuppressWithdrawal = $DepositDefinition->SuppressWithdrawal; //Suprimir la retirada

/* define variables relacionadas con un sistema de depósitos y visibilidad de usuarios. */
$UseFrozeWallet = $DepositDefinition->UseFrozeWallet;

$IsVisibleForAllplayers = $params->IsVisibleForAllplayers; //Es para todos los usuarios
$OpenToAllPlayers = $params->OpenToAllPlayers;//Es para todos los usuarios
$MaxplayersCount = $params->MaxplayersCount; // Maximo jugadores que lo pueden obtener
$Prefix = $params->Prefix;

/* Asigna parámetros de marketing y producto en una campaña de promoción. */
$IdMarketingCampaing = $params->MarketingCampaingIdsSelectList;
$TypeProduct = $params->TypeProduct; // Vertical a la que corresponde el bono regalado


//$PlayersChosen = $params->PlayersChosen;

$ForeignRule = $params->ForeignRule;

/* Verifica si $ForeignRuleInfo es un objeto; de no serlo, lo convierte a JSON. */
$ForeignRuleInfo = $ForeignRule->Info;


if (!is_object($ForeignRuleInfo)) {
    $ForeignRuleJSON = json_decode($ForeignRuleInfo);

} else {
    /* asigna información de una regla extranjera a una variable si no se cumple una condición. */

    $ForeignRuleJSON = $ForeignRuleInfo;
}


/* extrae parámetros de reglas deportivas desde un objeto JSON. */
$LiveOrPreMatch = $ForeignRuleJSON->LiveOrPreMatch;
$MinSelCount = $ForeignRuleJSON->MinSelCount; // Minimo cantidad de selecciones
$MinSelPrice = $ForeignRuleJSON->MinSelPrice; // Minimo cuota seleccionada
$MinSelPriceTotal = $ForeignRuleJSON->MinSelPriceTotal;

$TriggerId = $params->TriggerId;
//$CodePromo = $params->CodePromo;


/* Se extraen valores de un objeto JSON para configuración de apuestas. */
$MinBetPrice = $ForeignRuleJSON->MinBetPrice; // Minimo apuesta cuota total
$SportLealtadRules = $ForeignRuleJSON->SportLealtadRules;

$ProductTypeId = $params->ProductTypeId;

//$TriggerId = $params->TriggerId;
/*
        if ($CodePromo != "") {
            $TriggerId = 1;
        }
*/
$TypeId = $params->TypeId;


/* asigna valores de parámetros a variables para un juego y límite de pago. */
$Games = $params->Games;

$condiciones = [];


$MaxPayout = $params->MaxPayout; //Pago Maximo

/* Variables que configuran límites y requisitos financieros en un sistema. */
$MaximumLealtadAmount = $params->MaximumLealtadAmount; //Maximo valordel lealtad
$MaximumDeposit = $params->MaximumDeposit;
$MinimumDeposit = $params->MinimumDeposit;
$MinimumAmount = $params->MinimumAmount;
$MaximumAmount = $params->MaximumAmount;
$MoneyRequirement = $params->MoneyRequirement;

/* asigna valores de parámetros a variables para gestionar requisitos financieros y programación. */
$MoneyRequirementAmount = $params->MoneyRequirementAmount;


$Schedule = $params->Schedule; //Programar lealtad
$ScheduleCount = $Schedule->Count; //
$ScheduleName = $Schedule->Name; //Descripcion de la programacion

/* asigna valores de un objeto $Schedule y $TriggerDetails a variables. */
$SchedulePeriod = $Schedule->Period;
$SchedulePeriodType = $Schedule->PeriodType;

$TriggerDetails = $params->TriggerDetails;
$Count = $TriggerDetails->Count; //Cantidad de depositos


$IsFromCashDesk = $TriggerDetails->IsFromCashDesk;

/* asigna valores de $TriggerDetails a varias variables sobre pagos y ubicación. */
$PaymentSystemId = $TriggerDetails->PaymentSystemId;
$PaymentSystemIds = $TriggerDetails->PaymentSystemIds;

$Regions = $TriggerDetails->Regions;
$Departments = $TriggerDetails->Departments;
$Cities = $TriggerDetails->Cities;

/* asigna valores de un objeto a variables específicas para su uso posterior. */
$CashDesks = $TriggerDetails->CashDesks;

$RegionsUser = $TriggerDetails->RegionsUser;
$DepartmentsUser = $TriggerDetails->DepartmentsUser;
$CitiesUser = $TriggerDetails->CitiesUser;
$UserRepeatLealtad = $params->UserRepeatLealtad;


/* asigna valores de un objeto a variables para uso posterior. */
$BalanceZero = $TriggerDetails->BalanceZero;

$WinLealtadId = $params->WinLealtadId;
$TypeSaldo = $params->TypeSaldo;
$bonoId = $params->BonoId;
$Points = $params->Points;
//$Points = $Points->Amount;


/* Inicializa puntos y define si el premio es un regalo virtual según condición. */
$points = 0;
$TypeAward = $params->TypeAward;

$BetShopOwn = '0'; //Punto de Venta Propio

if ($TypeAward == true || $TypeAward == 'true') {
    $TypeAward = 1; //Regalo Virtual
} else {
    /* Asignación de premios y definición de propiedad de la tienda de apuestas. */

    $TypeAward = 0; //Regalo Fisico

    $BetShopOwn = $params->BetShopOwn;

    if ($BetShopOwn == true) {
        $BetShopOwn = 1; //Punto de Venta Propio
    } else {
        $BetShopOwn = 0; //Punto de venta Tercero
    }

}


/* verifica el tipo de producto y asigna 'NA' si no es válido. */
$ConditionProduct = $TriggerDetails->ConditionProduct;
if ($ConditionProduct != "OR" && $ConditionProduct != "AND") {
    $ConditionProduct = 'NA';
}

$FreeSpinDefinition = $params->FreeSpinDefinition;

/* asigna valores de un objeto a variables específicas relacionadas con giros gratuitos. */
$AutomaticForfeitureLevel = $FreeSpinDefinition->AutomaticForfeitureLevel;
$LealtadMoneyExpirationDate = $FreeSpinDefinition->LealtadMoneyExpirationDate;
$LealtadMoneyExpirationDays = $FreeSpinDefinition->LealtadMoneyExpirationDays;
$FreeSpinsTotalCount = $FreeSpinDefinition->FreeSpinsTotalCount;
$WageringFactor = $FreeSpinDefinition->WageringFactor;
$PlayersChosen = $params->PlayersChosen;

/* Extrae información de un casino y una descripción del premio de parámetros dados. */
$Casino = $params->Casino->Info;
$CasinoCategory = $Casino->Category;
$CasinoProvider = $Casino->Provider;
$CasinoProduct = $Casino->Product;

$DescriptionPrize = $params->DescriptionPrize;

//$LealtadDetails = $params->LealtadDetails;


/* Reemplaza comillas simples en RulesText y define variables según condiciones específicas. */
$RulesText = str_replace("'", "\'", $params->RulesText);

$TypeLealtadDeposit = $params->TypeLealtadDeposit;

$Type = ($params->Type == '1') ? '1' : '0';

$tipolealtad = $TypeId;

/* asigna valores a variables según condiciones específicas. */
$cupo = 0;
$cupoMaximo = 0;
$jugadores = 0;
$jugadoresMaximo = 0;

if ($MaximumAmount != "" && $tipolealtad == 2) {
    $cupoMaximo = $MaximumAmount[0]->Amount;
}


/* Establece jugadores máximos y reserva cupo máximo si no está definido. */
if ($MaxplayersCount != "" && $tipolealtad == 2) {
    $jugadoresMaximo = $MaxplayersCount;
}

if ($cupoMaximo == "") {
    $cupoMaximo = 0;
}


if ($bonoId != "" && $bonoId != '0') {


    /* Se inicializa un objeto "LealtadInterna" configurando sus propiedades con valores dados. */
    $LealtadInterna = new LealtadInterna();
    $LealtadInterna->nombre = $Name;
    $LealtadInterna->descripcion = $Description;
    $LealtadInterna->fechaInicio = $StartDate;
    $LealtadInterna->fechaFin = $EndDate;
    $LealtadInterna->tipo = $tipolealtad;

    /* Se asignan valores a propiedades de un objeto llamado LealtadInterna. */
    $LealtadInterna->estado = 'A';
    $LealtadInterna->usucreaId = 0;
    $LealtadInterna->usumodifId = 0;
    $LealtadInterna->mandante = $mandanteUsuario;
    $LealtadInterna->condicional = $ConditionProduct;
    $LealtadInterna->puntos = $points;

    /* Se asignan valores a las propiedades del objeto LealtadInterna. */
    $LealtadInterna->orden = $Order;
    $LealtadInterna->cupoActual = $cupo;
    $LealtadInterna->cupoMaximo = $cupoMaximo;
    $LealtadInterna->cantidadLealtad = $jugadores;
    $LealtadInterna->maximoLealtad = $jugadoresMaximo;
    $LealtadInterna->reglas = $RulesText;

    /* Se asignan propiedades a un objeto llamado LealtadInterna relacionado con premios y bonos. */
    $LealtadInterna->codigo = "";
    $LealtadInterna->bonoId = $bonoId;
    $LealtadInterna->tipoPremio = $TypeAward;
    $LealtadInterna->puntoventaPropio = $BetShopOwn;
    $LealtadInterna->mBody = $MBody;
    $LealtadInterna->mSubject = $MSubject;


    /* Se crea una transacción y se inserta un registro de lealtad en la base de datos. */
    $LealtadDetalleMySqlDAO = new LealtadDetalleMySqlDAO();
    $transaccion = $LealtadDetalleMySqlDAO->getTransaction();

    $lealtadId = $LealtadInterna->insert($transaccion);

} else {


    /* Crea un objeto 'LealtadInterna' y asigna sus propiedades. */
    $LealtadInterna = new LealtadInterna();
    $LealtadInterna->nombre = $Name;
    $LealtadInterna->descripcion = $Description;
    $LealtadInterna->fechaInicio = $StartDate;
    $LealtadInterna->fechaFin = $EndDate;
    $LealtadInterna->tipo = $tipolealtad;

    /* asigna valores a propiedades de un objeto "LealtadInterna". */
    $LealtadInterna->estado = 'A';
    $LealtadInterna->usucreaId = 0;
    $LealtadInterna->usumodifId = 0;
    $LealtadInterna->mandante = $mandanteUsuario;
    $LealtadInterna->condicional = $ConditionProduct;
    $LealtadInterna->puntos = $points;

    /* Asigna valores a propiedades del objeto LealtadInterna, relacionadas con la lealtad. */
    $LealtadInterna->orden = $Order;
    $LealtadInterna->cupoActual = $cupo;
    $LealtadInterna->cupoMaximo = $cupoMaximo;
    $LealtadInterna->cantidadLealtad = $jugadores;
    $LealtadInterna->maximoLealtad = $jugadoresMaximo;
    $LealtadInterna->reglas = $RulesText;

    /* Se asignan valores a propiedades de un objeto y se instancia un DAO. */
    $LealtadInterna->codigo = $DescriptionPrize;
    $LealtadInterna->bonoId = 0;
    $LealtadInterna->tipoPremio = $TypeAward;
    $LealtadInterna->puntoventaPropio = $BetShopOwn;


    $LealtadDetalleMySqlDAO = new LealtadDetalleMySqlDAO();

    /* obtiene una transacción y la inserta en una base de datos. */
    $transaccion = $LealtadDetalleMySqlDAO->getTransaction();

    $lealtadId = $LealtadInterna->insert($transaccion);
}


/* Inserta un detalle de lealtad en función de tipo de premio y producto. */
$LealtadDetalleMySqlDAO = new LealtadDetalleMySqlDAO();
$transaccion = $LealtadDetalleMySqlDAO->getTransaction();

$lealtadId = $LealtadInterna->insert($transaccion);


//Vertical a la cual pertenece el premio
if ($TypeAward == 1 && $TypeProduct !== null) {
    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "VERTICALREGALO";
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $TypeProduct;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);
}

//Expiracion


/* Crea un registro de detalle de lealtad si hay una descripción de premio. */
if ($DescriptionPrize != "") {
    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "FISICO";
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $ExpirationDays;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalle->descripcion = $DescriptionPrize;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);
}


/* Inserta un nuevo detalle de lealtad si la cantidad de días de expiración no está vacía. */
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


/* Inserta un registro de lealtad si hay un valor de repetición de bono. */
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


/* Inserta un registro de lealtad en la base de datos si se utiliza una billetera congelada. */
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


/* inserta un detalle de lealtad si $SuppressWithdrawal no está vacío. */
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


/* Inserta un nuevo objeto LealtadDetalle si ScheduleCount no está vacío. */
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


/* Inserta un nuevo detalle de lealtad si $ScheduleName no está vacío. */
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


/* Insertar un registro de lealtad basado en el período de programación proporcionado. */
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


/* inserta un registro de lealtad si el período de programación no está vacío. */
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


/* Crea un nuevo detalle de lealtad si hay un tipo de producto definido. */
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


/* Verifica si $Count no está vacío y guarda datos en LealtadDetalle. */
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


/* inserta un registro de detalle de lealtad si el tipo y porcentaje son válidos. */
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


/* Inserta un nuevo detalle de lealtad si $LealtadWFactor no está vacío. */
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


/* Inserta un detalle de lealtad en la base de datos si hay un depósito. */
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


/* Inserta un registro de 'LealtadDetalle' si 'DepositNumber' no está vacío. */
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


/* Inserta un registro de lealtad en la base de datos si proviene de caja. */
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


/* Inserta un nuevo registro de lealtad si se especifica un máximo de jugadores. */
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


/* Insertando un nuevo detalle de lealtad si $WinLealtadId no es cero. */
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
    /* Inserta un nuevo detalle de lealtad en la base de datos con datos específicos. */


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


/* inserta detalles de lealtad si el ID está vacío o es cero. */
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
    /* Inserta datos de lealtad en la base de datos para cada pago máximo. */


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


    /* Inserta detalles de lealtad en la base de datos para cada punto del array. */
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

    /* Inserta un registro de detalle de lealtad si la URL de imagen principal no está vacía. */
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


    /* inserta detalles de lealtad según depósitos máximos, utilizando una base de datos. */
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


    /* Inserta detalles de lealtad para cada depósito mínimo en la base de datos. */
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


/* Inserts loyalty details into the database based on specified money requirements. */
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


/* inserta detalles de lealtad basados en un sistema de pagos. */
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


/* Inserta detalles de lealtad para cada región en la base de datos. */
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


/* inserta detalles de lealtad por cada departamento en una base de datos. */
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


/* itera sobre $Cities, creando e insertando registros en LealtadDetalle. */
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


/* Se crea un registro LealtadDetalle si $BalanceZero es verdadero. */
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


/* Inserta detalles de lealtad en la base de datos para cada región de usuario. */
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


/* Inserta detalles de lealtad para cada departamento del usuario en la base de datos. */
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


/* Inserta detalles de lealtad para cada ciudad del usuario en la base de datos. */
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


/* Inserta detalles de lealtad en una base de datos para cada punto de venta. */
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


/* Inserta detalles de lealtad en base de datos para cada juego en un bucle. */
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


/* Inserta datos de lealtad en la base a partir de categorías de casino. */
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


/* Inserta detalles de lealtad en base de datos para cada proveedor de casino. */
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


/* Inserta detalles de lealtad para cada producto de casino en la base de datos. */
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


/* Itera sobre reglas de lealtad, creando y guardando detalles en la base de datos. */
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


/* Inserts loyalty details based on the condition of LiveOrPreMatch variable. */
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


/* Inserta un nuevo detalle de lealtad si $MinSelCount no está vacío. */
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


/* Inserta un nuevo detalle de lealtad si el precio mínimo es válido. */
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


/* Inserta un registro en LealtadDetalle si MinSelPriceTotal no está vacío. */
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

/*
    foreach ($LealtadDetails as $key => $value) {
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

    /*
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
*/


/* Inserta un registro de lealtad si el precio mínimo de apuesta no está vacío. */
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

    /* asigna jugadores seleccionados a un array final, pero está comentado. */
    $jugadoresAsignar = array();
    $jugadoresAsignarFinal = array();
    /*
        if ($PlayersChosen != "") {
            $jugadoresAsignar = explode(",", $PlayersChosen);


            foreach ($jugadoresAsignar as $item) {

                array_push($jugadoresAsignarFinal, array("Id" => $item, "Valor" => $AutomaticForfeitureLevel));

            }

        }
    */

    $codigosarray = array();

    for ($i = 1; $i <= $FreeSpinsTotalCount; $i++) {

        /* Genera un código único de 4 caracteres que no esté en el array dado. */
        $codigo = GenerarClaveTicket(4);

        while (in_array($codigo, $codigosarray)) {
            $codigo = GenerarClaveTicket(4);
        }


        $usuarioId = '0';

        /* Se asignan valores de referencia y un estado activo a variables en un código. */
        $valor = $AutomaticForfeitureLevel;

        $valor_lealtad = $AutomaticForfeitureLevel;

        $valor_base = $AutomaticForfeitureLevel;

        $estado = 'A';


        /* Variables inicializadas a '0' para manejar errores y usuarios en un sistema. */
        $errorId = '0';

        $idExterno = '0';

        $mandante = '0';


        $usucreaId = '0';


        /* Se definen variables y se concatena un prefijo a un código. */
        $usumodifId = '0';


        $apostado = '0';
        $rollowerRequerido = '0';
        $codigo = $Prefix . $codigo;


        /* crea y configura un objeto UsuarioLealtad con datos específicos. */
        $UsuarioLealtad = new UsuarioLealtad();

        $UsuarioLealtad->setUsuarioId($usuarioId);
        $UsuarioLealtad->setLealtadId($lealtadId);
        $UsuarioLealtad->setValor($valor);
        $UsuarioLealtad->setValorLealtad($valor_lealtad);

        /* Código que configura propiedades de un objeto UsuarioLealtad. */
        $UsuarioLealtad->setValorBase($valor_base);
        $UsuarioLealtad->setEstado($estado);
        $UsuarioLealtad->setErrorId($errorId);
        $UsuarioLealtad->setIdExterno($idExterno);
        $UsuarioLealtad->setMandante($mandante);
        $UsuarioLealtad->setUsucreaId($usucreaId);

        /* Código establece propiedades de un objeto y crea una instancia de acceso a datos. */
        $UsuarioLealtad->setUsumodifId($usumodifId);
        $UsuarioLealtad->setApostado($apostado);
        $UsuarioLealtad->setRollowerRequerido($rollowerRequerido);
        $UsuarioLealtad->setCodigo($codigo);

        $UsuarioLealtadMysqlDAO = new UsuarioLealtadMySqlDAO($transaccion);


        /* Inserta un usuario y asigna códigos a jugadores en un array. */
        $UsuarioLealtadMysqlDAO->insert($UsuarioLealtad);

        if ($jugadoresAsignarFinal[$i]["Id"] != "") {
            $jugadoresAsignarFinal[$i]["Code"] = $codigo;
        }

        array_push($codigosarray, $codigo);

    }
}


/* establece una variable booleana basada en condiciones de conteo y prefijos. */
$darAUsuariosEspecificos = false;
if (($MaxplayersCount != "" && $Prefix != "") || ($MaxplayersCount != "" && ($MinimumAmount) != '')) {
    $darAUsuariosEspecificos = true;
}

if ($darAUsuariosEspecificos) {


    /* Se definen dos arrays vacíos para asignar jugadores en un sistema. */
    $jugadoresAsignar = array();
    $jugadoresAsignarFinal = array();

    foreach ($MinimumAmount as $key => $value) {


        /* asigna jugadores y sus valores basado en una moneda coincidente. */
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


    /* Se inicializa un arreglo vacío llamado "codigosarray". */
    $codigosarray = array();


    for ($i = 0; $i < $MaxplayersCount; $i++) {

        /* Genera un código único de 4 caracteres no existente en un array. */
        $codigo = GenerarClaveTicket(4);

        while (in_array($codigo, $codigosarray)) {
            $codigo = GenerarClaveTicket(4);
        }


        $usuarioId = '0';

        /* asigna 'P' a $estado si se cumplen ciertas condiciones del jugador. */
        $estado = 'A';


        if ($tipolealtad == "2") {
            if ($jugadoresAsignarFinal[$i] != "" && $jugadoresAsignarFinal[$i]["Id"] != null) {
                $usuarioId = $jugadoresAsignarFinal[$i]["Id"];
                $estado = 'P';

            }
        }


        /* Variables inicializadas con valores '0' para uso posterior en el código. */
        $valor = '0';

        $valor_lealtad = '0';

        $valor_base = '0';

        $errorId = '0';


        /* Se inicializan variables con valores predeterminados en un script PHP. */
        $idExterno = '0';

        $mandante = '0';


        $usucreaId = '0';


        /* Asignación de variables en PHP para gestionar un sistema de apuestas. */
        $usumodifId = '0';


        $apostado = '0';
        $rollowerRequerido = '0';
        $codigo = $Prefix . $codigo;


        /* Se crea un objeto UsuarioLealtad y se establecen sus propiedades. */
        $UsuarioLealtad = new UsuarioLealtad();

        $UsuarioLealtad->setUsuarioId($usuarioId);
        $UsuarioLealtad->setLealtadId($lealtadId);
        $UsuarioLealtad->setValor($valor);
        $UsuarioLealtad->setValorLealtad($valor_lealtad);

        /* establece propiedades de un objeto "UsuarioLealtad" con diversos valores. */
        $UsuarioLealtad->setValorBase($valor_base);
        $UsuarioLealtad->setEstado($estado);
        $UsuarioLealtad->setErrorId($errorId);
        $UsuarioLealtad->setIdExterno($idExterno);
        $UsuarioLealtad->setMandante($mandante);
        $UsuarioLealtad->setUsucreaId($usucreaId);

        /* configura propiedades de un objeto UsuarioLealtad en PHP. */
        $UsuarioLealtad->setUsumodifId($usumodifId);
        $UsuarioLealtad->setApostado($apostado);
        $UsuarioLealtad->setRollowerRequerido($rollowerRequerido);
        $UsuarioLealtad->setCodigo($codigo);
        $UsuarioLealtad->setVersion(0);
        $UsuarioLealtad->setExternoId(0);


        /* Código que inserta un usuario y asigna un código a jugadores válidos. */
        $UsuarioLealtadMysqlDAO = new UsuarioLealtadMySqlDAO($transaccion);

        $inse = $UsuarioLealtadMysqlDAO->insert($UsuarioLealtad);


        if ($jugadoresAsignarFinal[$i]["Id"] != "") {

            $jugadoresAsignarFinal[$i]["Code"] = $codigo;
        }


        /* Añade el valor de $codigo al final del array $codigosarray. */
        array_push($codigosarray, $codigo);

    }
}

/* Inserta un registro en la base de datos si IdMarketingCampaing no está vacío. */
if ($IdMarketingCampaing != "") {
    $IdMarketingCampaing = json_encode($IdMarketingCampaing);
    $LealtadDetalle = new LealtadDetalle();
    $LealtadDetalle->lealtadId = $lealtadId;
    $LealtadDetalle->tipo = "MARKETINGCAMPAING";
    $LealtadDetalle->moneda = '';
    $LealtadDetalle->valor = $IdMarketingCampaing;
    $LealtadDetalle->usucreaId = 0;
    $LealtadDetalle->usumodifId = 0;
    $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
    $LealtadDetalleMysqlDAO->insert($LealtadDetalle);
}

/* finaliza una transacción en una base de datos, guardando cambios realizados. */
$transaccion->commit();


if ($darAUsuariosEspecificos) {

    /* Se crea un objeto DAO y se obtiene una transacción de base de datos. */
    $LealtadDetalleMySqlDAO = new LealtadDetalleMySqlDAO();
    $transaccion = $LealtadDetalleMySqlDAO->getTransaction();


    if ($tipolealtad != 2) {

        for ($i = 0; $i < oldCount($jugadoresAsignarFinal); $i++) {

            if ($jugadoresAsignarFinal[$i]["Id"] != "") {

                /* Código SQL que obtiene información del usuario y su ubicación geográfica asociada. */
                $LealtadInterna = new LealtadInterna();

                $usuarioSql = "select ciudad.ciudad_id,ciudad.depto_id,pais.pais_id,usuario.moneda,usuario.mandante FROM registro
  INNER JOIN usuario ON (usuario.usuario_id = registro.usuario_id)
  INNER JOIN ciudad ON (ciudad.ciudad_id = registro.ciudad_id)
  INNER JOIN departamento ON (ciudad.depto_id = departamento.depto_id)
  INNER JOIN pais ON (pais.pais_id = departamento.pais_id) WHERE registro.usuario_id='" . $jugadoresAsignarFinal[$i]["Id"] . "'";

                $Usuario = $LealtadInterna->execQuery($transaccion, $usuarioSql);


                $dataUsuario = $Usuario;

                /* Verifica un usuario y agrega detalles de lealtad en un sistema. */
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

    /* Confirma y guarda los cambios realizados en la transacción de la base de datos. */
    $transaccion->commit();

}


/* Se crea un objeto DAO y se obtiene una transacción de la base de datos. */
$LealtadDetalleMySqlDAO = new LealtadDetalleMySqlDAO();
$transaccion = $LealtadDetalleMySqlDAO->getTransaction();


if ($FreeSpinsTotalCount != "" && $Prefix != "") {

    for ($i = 1; $i <= oldCount($jugadoresAsignarFinal); $i++) {


        /* Consulta SQL que une tablas para obtener información de usuarios y su ubicación. */
        $LealtadInterna = new LealtadInterna();

        $usuarioSql = "select ciudad.ciudad_id,ciudad.depto_id,pais.pais_id,usuario.mandante FROM registro
  INNER JOIN ciudad ON (ciudad.ciudad_id = registro.ciudad_id)
  INNER JOIN departamento ON (ciudad.depto_id = departamento.depto_id)
  INNER JOIN pais ON (pais.pais_id = departamento.pais_id) WHERE registro.usuario_id='" . $jugadoresAsignarFinal[$i]["Id"] . "'";

        $Usuario = $LealtadInterna->execQuery($transaccion, $usuarioSql);


        $dataUsuario = $Usuario;

        /* crea un array con información de ubicación del usuario. */
        $detalles = array(
            "PaisUSER" => $dataUsuario[0]->pais_id,
            "DepartamentoUSER" => $dataUsuario[0]->depto_id,
            "CiudadUSER" => $dataUsuario[0]->ciudad_id,

        );

        /* Se agrega lealtad a un jugador usando datos convertidos y función específica. */
        $detalles = json_decode(json_encode($detalles));


        $respuesta = $LealtadInterna->agregarLealtadFree($lealtadId, $jugadoresAsignarFinal[$i]["Id"], $dataUsuario[0]->{'usuario.mandante'}, $detalles, true, $jugadoresAsignarFinal[$i]["Code"], $transaccion);


    }
}
if ($MaxplayersCount != "" && $Prefix != "") {
    if ($tipolealtad == 2) {

        for ($i = 0; $i < oldCount($jugadoresAsignarFinal); $i++) {

            if ($jugadoresAsignarFinal[$i]["Id"] != "") {

                /* Código que consulta datos de usuarios y su ubicación en la base de datos. */
                $LealtadInterna = new LealtadInterna();

                $usuarioSql = "select ciudad.ciudad_id,ciudad.depto_id,pais.pais_id,usuario.mandante FROM registro
  INNER JOIN ciudad ON (ciudad.ciudad_id = registro.ciudad_id)
  INNER JOIN departamento ON (ciudad.depto_id = departamento.depto_id)
  INNER JOIN pais ON (pais.pais_id = departamento.pais_id) WHERE registro.usuario_id='" . $jugadoresAsignarFinal[$i]["Id"] . "'";

                $Usuario = $LealtadInterna->execQuery($transaccion, $usuarioSql);


                $dataUsuario = $Usuario;

                /* crea un array con información de ubicación del usuario. */
                $detalles = array(
                    "PaisUSER" => $dataUsuario[0]->pais_id,
                    "DepartamentoUSER" => $dataUsuario[0]->depto_id,
                    "CiudadUSER" => $dataUsuario[0]->ciudad_id,

                );

                /* Se decodifica un JSON y se llama a un método para agregar lealtad. */
                $detalles = json_decode(json_encode($detalles));


                $respuesta = $LealtadInterna->agregarLealtadFree($lealtadId, $jugadoresAsignarFinal[$i]["Id"], $dataUsuario[0]->{'usuario.mandante'}, $detalles, true, $jugadoresAsignarFinal[$i]["Code"], $transaccion);

            }

        }
    }
}
//$transaccion->commit();


/* Configuración de respuesta para manejar errores y mensajes en una aplicación. */
$response["HasError"] = false;
$response["AlertType"] = "danger";
$response["AlertMessage"] = "";
$response["LealtadId"] = intval($lealtadId);
$response["ModelErrors"] = [];
$response["Result"] = array();
