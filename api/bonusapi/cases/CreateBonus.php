<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\Clasificador;
use Backend\dto\GeneralLog;
use Backend\dto\MandanteDetalle;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioBono;
use Backend\integrations\casino\CTGaming;
use Backend\integrations\casino\PRAGMATICSERVICES;
use Backend\integrations\casino\REDRAKESERVICESBONUS;
use Backend\integrations\crm\Optimove;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\GeneralLogMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\utils\RedisConnectionTrait;

/**
 * Este script gestiona la creación de bonos en el sistema, asignando valores a variables
 * y realizando operaciones en la base de datos según los parámetros proporcionados.
 *
 * @param object $params Objeto que contiene los siguientes valores:
 * @param string $params->Description Descripción del bono.
 * @param string $params->Name Nombre del bono.
 * @param int $params->TypeAction Tipo de acción del bono.
 * @param string $params->CampaingCategory Categoría de la campaña.
 * @param string $params->CampaingDetails Detalles de la campaña.
 * @param string $params->BeginDate Fecha de inicio de la campaña.
 * @param string $params->EndDate Fecha de fin de la campaña.
 * @param string $params->MainImageURL URL de la imagen principal.
 * @param object $params->PartnerBonus Objeto que contiene información del bono del socio.
 * @param string $params->PartnerBonus->ExpirationDate Fecha de expiración del bono.
 * @param int $params->PartnerBonus->ExpirationDays Días de expiración del bono.
 * @param int $params->ExpirationBonusDays Días de expiración de bonos pendientes para CRM.
 * @param string $params->ExpirationBonusDate Fecha de expiración para bonos de CRM.
 * @param int $params->TypeDateBonusExpiration Tipo de expiración del bono (1 o 0).
 * @param string $params->LiveOrPreMatch Indica si es en vivo o pre-partido.
 * @param int $params->MinSelCount Mínima cantidad de selecciones.
 * @param float $params->MinSelPrice Mínima cuota seleccionada.
 * @param float $params->CurrentCost Costo actual.
 * @param object $params->DepositDefinition Objeto que contiene información sobre el depósito.
 * @param string $params->DepositDefinition->BonusDefinition Definición del bono.
 * @param int $params->DepositDefinition->BonusDefinitionId ID de la definición del bono.
 * @param float $params->DepositDefinition->BonusPercent Porcentaje del bono.
 * @param float $params->DepositDefinition->BonusWFactor Factor de rollover del bono.
 * @param int $params->DepositDefinition->DepositNumber Número de depósitos.
 * @param float $params->DepositDefinition->DepositWFactor Factor de rollover del depósito.
 * @param bool $params->DepositDefinition->SuppressWithdrawal Indica si se suprime el retiro.
 * @param bool $params->DepositDefinition->UseFrozeWallet Indica si se utiliza una billetera congelada.
 * @param bool $params->IsVisibleForAllplayers Indica si es visible para todos los jugadores.
 * @param bool $params->OpenToAllPlayers Indica si está abierto a todos los jugadores.
 * @param int $params->MaxplayersCount Máximo número de jugadores que pueden obtener el bono.
 * @param string $params->Prefix Prefijo del bono.
 * @param int $params->BonusplanId ID del plan de bonos.
 * @param string $params->CodeBonus Código del bono.
 * @param array $params->PlayersChosen Lista de jugadores seleccionados.
 * @param object $params->ForeignRule Objeto que contiene información de reglas extranjeras.
 * @param string|object $params->ForeignRule->Info Información de las reglas extranjeras.
 * @param array $params->MarketingCampaingIdsSelectList Lista de IDs de campañas de marketing.
 * @param int $params->TriggerId ID del disparador.
 * @param string $params->CodePromo Código promocional.
 * @param int $params->ValueBonusMaxRollover Valor máximo de rollover del bono.
 * @param array $params->MaxBetAmountRollover Lista de montos máximos de apuesta para rollover.
 * @param array $params->MinBetAmountRollover Lista de montos mínimos de apuesta para rollover.
 * @param int $params->ValueBonusMaxSpin Valor máximo de giros gratis.
 * @param float $params->MinBetPrice Mínima cuota total de apuesta.
 * @param array $params->SportBonusRules Reglas de bonificación deportiva.
 * @param int $params->ProductTypeId ID del tipo de producto.
 * @param int $params->TypeId ID del tipo de bono.
 * @param array $params->Games Lista de juegos asociados al bono.
 * @param array $params->AmountRolloverFixed Lista de montos fijos de rollover.
 * @param array $params->MaxPayout Lista de pagos máximos.
 * @param array $params->MaxPayoutFixed Lista de pagos máximos fijos.
 * @param array $params->MinPayout Lista de pagos mínimos.
 * @param float $params->MaximumBonusAmount Monto máximo del bono.
 * @param array $params->MaximumDeposit Lista de depósitos máximos.
 * @param array $params->MinimumDeposit Lista de depósitos mínimos.
 * @param float $params->MinimumAmount Monto mínimo.
 * @param float $params->MaximumAmount Monto máximo.
 * @param array $params->MoneyRequirement Requisitos monetarios.
 * @param float $params->MoneyRequirementAmount Monto requerido.
 * @param object $params->Schedule Objeto que contiene información de programación.
 * @param int $params->Schedule->Count Cantidad de programaciones.
 * @param string $params->Schedule->Name Nombre de la programación.
 * @param string $params->Schedule->Period Periodo de la programación.
 * @param string $params->Schedule->PeriodType Tipo de periodo de la programación.
 * @param object $params->TriggerDetails Detalles del disparador.
 * @param int $params->TriggerDetails->Count Cantidad de depósitos requeridos.
 * @param int $params->TriggerDetails->OrdenDeposit Número de depósitos requeridos.
 * @param bool $params->TriggerDetails->IsFromCashDesk Indica si es desde caja.
 * @param int $params->TriggerDetails->PaymentSystemId ID del sistema de pago.
 * @param array $params->TriggerDetails->PaymentSystemIds Lista de IDs de sistemas de pago.
 * @param array $params->TriggerDetails->Regions Lista de regiones.
 * @param array $params->TriggerDetails->Departments Lista de departamentos.
 * @param array $params->TriggerDetails->Cities Lista de ciudades.
 * @param array $params->TriggerDetails->CashDesks Lista de puntos de venta.
 * @param array $params->TriggerDetails->CashDesk Lista de puntos de venta específicos.
 * @param array $params->TriggerDetails->CashDesksNot Lista de puntos de venta excluidos.
 * @param array $params->TriggerDetails->RegionsUser Lista de regiones del usuario.
 * @param array $params->TriggerDetails->DepartmentsUser Lista de departamentos del usuario.
 * @param array $params->TriggerDetails->CitiesUser Lista de ciudades del usuario.
 * @param bool $params->TriggerDetails->BalanceZero Indica si el saldo es cero.
 * @param bool $params->UserRepeatBonus Indica si el usuario puede repetir el bono.
 * @param string $params->Country País del usuario.
 * @param bool $params->AllowAdditionalBonuses Indica si se permiten bonos adicionales.
 * @param int $params->Rounds Número de rondas.
 * @param int $params->FreeRoundsMaxDays Máximo de días para rondas gratis.
 * @param int $params->WinBonusId ID del bono de ganancia.
 * @param int $params->Priority Prioridad del bono.
 * @param object $params->FreeSpinDefinition Definición de giros gratis.
 * @param int $params->FreeSpinDefinition->AutomaticForfeitureLevel Nivel de pérdida automática.
 * @param string $params->FreeSpinDefinition->BonusMoneyExpirationDate Fecha de expiración del dinero del bono.
 * @param int $params->FreeSpinDefinition->BonusMoneyExpirationDays Días de expiración del dinero del bono.
 * @param int $params->FreeSpinDefinition->FreeSpinsTotalCount Total de giros gratis.
 * @param float $params->FreeSpinDefinition->WageringFactor Factor de apuesta.
 * @param object $params->Casino Información del casino.
 * @param object $params->Casino->Info Información del casino.
 * @param string $params->Casino->Info->Category Categoría del casino.
 * @param string $params->Casino->Info->Provider Proveedor del casino.
 * @param string $params->Casino->Info->Product Producto del casino.
 * @param string $params->IsFor Indica el propósito del bono (e.g., "IsLoyalty", "IsRoulette").
 * @param string $params->PlayersIdCsv Lista de IDs de jugadores en formato CSV.
 * @param array $params->DateProgram Lista de fechas de programación.
 * @param object $params->JsonOriginFront JSON con información del bono para el frontend.
 *
 *
 *
 * @return array $response Respuesta del sistema con los siguientes valores:
 *  - idBonus (int): ID del bono creado.
 *  - HasError (bool): Indica si hubo un error.
 *  - AlertType (string): Tipo de alerta ("success", "danger").
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *  - Result (array): Resultado adicional.
 *
 * @throws Exception Si ocurre un error durante la creación del bono o la inserción en la base de datos.
 */

/* verifica un mandante de usuario según la sesión activa. */
$mandanteUsuario = 0;
// Si el usuario esta condicionado por el mandante y no es de Global
if ($_SESSION['Global'] == "N") {
    $mandanteUsuario = $_SESSION['mandante'];
}
/*error_reporting(E_ALL);
ini_set("display_errors","ON");*/


$Description = $params->Description; //Descripcion del bono

/* Asigna valores de parámetros a variables para campaña y bono. */
$Name = $params->Name; //Nombre del bono$CampaingDetails

$TypeAction = $params->TypeAction; //Nombre del bono$CampaingDetails
$CampaingCategory = $params->CampaingCategory;
$CampaingDetails = $params->CampaingDetails;
$StartDate = $params->BeginDate; //Fecha Inicial de la campaña

/* Se asignan valores de parámetros a variables relacionadas con una campaña y bonificaciones. */
$EndDate = $params->EndDate; //Fecha Final de la campaña
$MainImageURL = $params->MainImageURL;
$EnableBonusCodes = !empty($params->EnableBonusCodes) ? 1 : 0; // Codigos Unicos: 1 = si | 0 = no


$PartnerBonus = $params->PartnerBonus;

$ExpirationDate = $PartnerBonus->ExpirationDate; //Fecha de expiracion del bono

/* gestiona fechas y días de expiración de bonos en un sistema CRM. */
$ExpirationDays = $PartnerBonus->ExpirationDays; // Dias de expiracion del bono
$ExpirationDaysForOutstandingBonds = $params->ExpirationBonusDays; // dias de expiracion de bonos pendientes para crm
$ExpirationDateForOutstandingBonds = $params->ExpirationBonusDate; // fecha de expiracion para bonos de crm
$TypeDateBonusExpiration = $params->TypeDateBonusExpiration;
$ExpirationDateForOutstandingBonds = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $ExpirationDateForOutstandingBonds)));


if ($ExpirationDate == "" && $ExpirationDays == "") {
    $ExpirationDate = $params->ExpirationDate; //Fecha de expiracion del bono
    $ExpirationDays = $params->ExpirationDays; // Dias de expiracion del bono

}


/* Establece fechas de expiración según el tipo de bonificación y estado de la partida. */
if ($TypeDateBonusExpiration == 1) {
    $ExpirationDateForOutstandingBonds = "";
} else if ($TypeDateBonusExpiration == 0) {
    $ExpirationDaysForOutstandingBonds = "";
}


$LiveOrPreMatch = $params->LiveOrPreMatch;

/* asigna valores de parámetros a variables para un procesamiento posterior. */
$MinSelCount = $params->MinSelCount;
$MinSelPrice = $params->MinSelPrice;


$CurrentCost = $params->CurrentCost;

$DepositDefinition = $params->DepositDefinition;


/* Asigna valores de un objeto DepositDefinition a variables relacionadas con bonos. */
$BonusDefinition = $DepositDefinition->BonusDefinition;
$BonusDefinitionId = $DepositDefinition->BonusDefinitionId;
$BonusPercent = $DepositDefinition->BonusPercent; // Porcentaje del bono
$BonusWFactor = $DepositDefinition->BonusWFactor; //Rollower bono
$DepositNumber = $DepositDefinition->DepositNumber;
$DepositWFactor = $DepositDefinition->DepositWFactor; //Rollower Deposito


/* Configuración de parámetros para definir restricciones en retiros y visibilidad para jugadores. */
$SuppressWithdrawal = $DepositDefinition->SuppressWithdrawal; //Suprimir la retirada
$UseFrozeWallet = $DepositDefinition->UseFrozeWallet;

$IsVisibleForAllplayers = $params->IsVisibleForAllplayers; //Es para todos los usuarios
$OpenToAllPlayers = $params->OpenToAllPlayers;//Es para todos los usuarios
$MaxplayersCount = $params->MaxplayersCount; // Maximo jugadores que lo pueden obtener

/* Código que asigna valores de parámetros a variables en un sistema de bonificación. */
$Prefix = $params->Prefix;


$BonusplanId = $params->BonusplanId;
$CodeBonus = $params->CodeBonus;

$PlayersChosen = $params->PlayersChosen;


/* obtiene información de una regla extranjera y la decodifica si no es un objeto. */
$ForeignRule = $params->ForeignRule;
$ForeignRuleInfo = $ForeignRule->Info;
$MarketingCampaing = $params->MarketingCampaingIdsSelectList;

if (!is_object($ForeignRuleInfo)) {
    $ForeignRuleJSON = json_decode($ForeignRuleInfo);

} else {
    /* Asigna la información de reglas extranjeras a una variable si no se cumple la condición. */

    $ForeignRuleJSON = $ForeignRuleInfo;
}


/* Extrae y asigna datos de un objeto JSON para configuración de apuestas. */
$LiveOrPreMatch = $ForeignRuleJSON->LiveOrPreMatch;
$MinSelCount = $ForeignRuleJSON->MinSelCount; // Minimo cantidad de selecciones
$MinSelPrice = $ForeignRuleJSON->MinSelPrice; // Minimo cuota seleccionada
$MinSelPriceTotal = $ForeignRuleJSON->MinSelPriceTotal;

$TriggerId = $params->TriggerId;

/* asigna valores de parámetros a variables relacionadas con promociones y bonos. */
$CodePromo = $params->CodePromo;

$ValueBonusMaxRollover = $params->ValueBonusMaxRollover;
$MaxBetAmountRollover = $params->MaxBetAmountRollover;
$MinBetAmountRollover = $params->MinBetAmountRollover;
$ValueBonusMaxSpin = $params->ValueBonusMaxSpin;


/* asigna valores de un objeto JSON a variables específicas para apuestas. */
$MinBetPrice = $ForeignRuleJSON->MinBetPrice; // Minimo apuesta cuota total
$SportBonusRules = $ForeignRuleJSON->SportBonusRules;

$ProductTypeId = $params->ProductTypeId;

$TriggerId = $params->TriggerId;


/* Establece un trigger si hay un código promocional y asigna variables de parámetros. */
if ($CodePromo != "") {
    $TriggerId = 1;
}

$TypeId = $params->TypeId;

$Games = $params->Games;


/* Inicializa un arreglo y asigna valores de parámetros a variables. */
$condiciones = [];


$AmountRolloverFixed = $params->AmountRolloverFixed; //Pago Maximo
$MaxPayout = $params->MaxPayout; //Pago Maximo
$MaxPayoutFixed = $params->MaxPayoutFixed; //Pago Maximo

/* Se definen variables para límites de pagos y bonos en un sistema financiero. */
$MinPayout = $params->MinPayout; //Pago Minimo
$MaximumBonusAmount = $params->MaximumBonusAmount; //Maximo valordel bono
$MaximumDeposit = $params->MaximumDeposit;
$MinimumDeposit = $params->MinimumDeposit;
$MinimumAmount = $params->MinimumAmount;
$MaximumAmount = $params->MaximumAmount;

/* Asignación de parámetros relacionados con requisitos monetarios y programación de bonos. */
$MoneyRequirement = $params->MoneyRequirement;
$MoneyRequirementAmount = $params->MoneyRequirementAmount;

$Schedule = $params->Schedule; //Programar bono
$ScheduleCount = $Schedule->Count; //
$ScheduleName = $Schedule->Name; //Descripcion de la programacion

/* extrae información del objeto $Schedule y $TriggerDetails en PHP. */
$SchedulePeriod = $Schedule->Period;
$SchedulePeriodType = $Schedule->PeriodType;

$TriggerDetails = $params->TriggerDetails;
$Count = $TriggerDetails->Count; //Cantidad de depositos (0 = Primer Depósito, 1= Próximo depósito o cualquier depósito, 2= Depoósito específico)
$OrdenDeposit = $TriggerDetails->OrdenDeposit; //Número con la cantidad de depósitos requeridos para la entrega del bono (Sólo se utiliza con la opción depoósito específico)

//$AreAllowed = $TriggerDetails->AreAllowed; //Son Permitidos los paises


/* asigna valores de un objeto a variables específicas relacionadas con un sistema de pagos. */
$IsFromCashDesk = $TriggerDetails->IsFromCashDesk;
$PaymentSystemId = $TriggerDetails->PaymentSystemId;
$PaymentSystemIds = $TriggerDetails->PaymentSystemIds;

$Regions = $TriggerDetails->Regions;
$Departments = $TriggerDetails->Departments;

/* Se asignan variables de detalles del desencadenador a diferentes elementos relacionados. */
$Cities = $TriggerDetails->Cities;
$CashDesks = $TriggerDetails->CashDesks;
$CashDesk = $TriggerDetails->CashDesk;
$CashDesksNot = $TriggerDetails->CashDesksNot;
$RegionsUser = $TriggerDetails->RegionsUser;
$DepartmentsUser = $TriggerDetails->DepartmentsUser;

/* Asigna valores a variables y verifica el país del usuario en sesión. */
$CitiesUser = $TriggerDetails->CitiesUser;
$UserRepeatBonus = $params->UserRepeatBonus;
$Country = $params->Country;

$RemoveByWithdrawal = $params->RemoveByWithdrawal;

if ($Country == "") {
    $Country = $_SESSION["pais_id"];
}


/* Variables asignan parámetros relacionados a bonificaciones y detalles del juego. */
$AllowAdditionalBonuses = $params->AllowAdditionalBonuses;

$rounds = $params->Rounds;
$FreeRoundsMaxDays = $params->FreeRoundsMaxDays;
$BalanceZero = $TriggerDetails->BalanceZero;

$WinBonusId = $params->WinBonusId;

/* valida el campo "Priority" y lo establece en 0 si es inválido. */
$TypeSaldo = $params->TypeSaldo;
$Priority = $params->Priority;

if ($Priority == "" || !is_numeric($Priority)) {
    $Priority = 0;
}


/* verifica el valor de `$ConditionProduct` y lo establece como 'NA' si es inválido. */
$ConditionProduct = $TriggerDetails->ConditionProduct;
if ($ConditionProduct != "OR" && $ConditionProduct != "AND") {
    $ConditionProduct = 'NA';
}

$FreeSpinDefinition = $params->FreeSpinDefinition;

/* Asigna valores de $FreeSpinDefinition y $params a variables específicas. */
$AutomaticForfeitureLevel = $FreeSpinDefinition->AutomaticForfeitureLevel;
$BonusMoneyExpirationDate = $FreeSpinDefinition->BonusMoneyExpirationDate;
$BonusMoneyExpirationDays = $FreeSpinDefinition->BonusMoneyExpirationDays;
$FreeSpinsTotalCount = $FreeSpinDefinition->FreeSpinsTotalCount;
$WageringFactor = $FreeSpinDefinition->WageringFactor;
$PlayersChosen = $params->PlayersChosen;

/* asigna información de un casino a variables específicas. */
$Casino = $params->Casino->Info;
$CasinoCategory = $Casino->Category;
$CasinoProvider = $Casino->Provider;
$CasinoProduct = $Casino->Product;
$IsFor = $params->IsFor;
$IsLoyalty = '';

/* Se declaran variables vacías para diferentes tipos de juegos o funciones. */
$IsLottery = '';
$IsRoulette = '';
$IsForReferent = '';
$IsForLanding = '';
$IsCRM = '';


/* usa un switch para asignar valores según la variable $IsFor. */
switch ($IsFor) {
    case "IsLoyalty":
        $IsLoyalty = 1;
        break;
    case "IsRoulette":
        $IsRoulette = 1;
        break;
    case "IsLottery":
        $IsLottery = 1;
        break;
    case "IsCRM":
        $IsCRM = 1;
        break;
    case "IsForReferent":
        $IsForReferent = 1;
        break;
    case "IsLanding":
        $IsForLanding = 1;
        break;
}


/* Se extraen datos de apuestas y detalles de bonificaciones de un JSON. */
$SportsbookDeports2 = $ForeignRuleJSON->SportsbookDeports2;
$SportsbookMarkets2 = $ForeignRuleJSON->SportsbookMarkets2;
$SportsbookLeagues2 = $ForeignRuleJSON->SportsbookLeagues2;
$SportsbookMatches2 = $ForeignRuleJSON->SportsbookMatches2;


$BonusDetails = $params->BonusDetails;

/* Asignación y transformación de variables según parámetros recibidos. */
$CodeGlobal = $params->CodeGlobal;
$RulesText = str_replace("'", "\'", $params->RulesText);

$TypeBonusDeposit = $params->TypeBonusDeposit;

$Type = ($params->Type == '1') ? '1' : '0';

/* asigna valores de parámetros a variables relacionadas con un programa y jugadores. */
$DateProgram = $params->DateProgram;

$tipobono = $TypeId;
$cupo = 0;
$cupoMaximo = 0;
$jugadores = 0;

/* Asigna valores de máximos según condiciones de tipo de bono y existencias previas. */
$jugadoresMaximo = 0;

if ($MaximumAmount != "" && $tipobono == 2) {
    $cupoMaximo = $MaximumAmount[0]->Amount;
}

if ($MaxplayersCount != "" && $tipobono == 2) {
    $jugadoresMaximo = $MaxplayersCount;
}


/* verifica si 'cupoMaximo' está vacío y lo inicializa a 0. */
if ($cupoMaximo == "") {
    $cupoMaximo = 0;
}


//Obtención de usuarios mediante CSV
$PlayersIdCsv = $params->PlayersIdCsv;
if ($PlayersIdCsv != '') {


    /* decodifica un CSV en base64 y reemplaza puntos y coma por comas. */
    $PlayersIdCsv = explode("base64,", $PlayersIdCsv);
    $PlayersIdCsv = $PlayersIdCsv[1];

    $PlayersIdCsv = base64_decode($PlayersIdCsv);

    $PlayersIdCsv = str_replace(";", ",", $PlayersIdCsv);


    /* separa un CSV en líneas y convierte cada línea en un array. */
    $lines = explode(PHP_EOL, $PlayersIdCsv);
    $lines = preg_split('/\r\n|\r|\n/', $PlayersIdCsv);

    $array = array();
    foreach ($lines as $line) {

        $array[] = str_getcsv($line);

    }


    /* Extrae la primera columna de un array y obtiene sus posiciones. */
    $arrayfinal = array_column($array, '0');
//$arrayfinal3 =  array_column($array,'1');


    /*$primera = substr($arrayfinal[0], 3);*/

    /*$arrayfinal[0] = $primera;*/

    $posiciones = array_keys($arrayfinal);


    /* convierte un array a JSON y filtra elementos vacíos. */
    $ultima = strval(end($posiciones));

    $arrayfinal = json_decode(json_encode($arrayfinal));

    $arrayfinal2 = array();
    //unset($arrayfinal[$ultima]);
    foreach ($arrayfinal as $item) {

        if ($item != "") {

            array_push($arrayfinal2, $item);
        }
    }

    /** Consolidado Usuarios enviados por CSV */

    /* Asignación de un array y conversión a cadena separada por comas. */
    $arrayfinal = $arrayfinal2; //Array
    $PlayersChosen = implode(",", $arrayfinal); //String separado por comas

}

//Fin lógica obtención de usuarios por CSV


/* inicializa un array con fechas si $DateProgram está vacío. */
if ($DateProgram == '' || oldCount($DateProgram) == 0) {
    $DateProgram = array();

    $DateProgramTemp = array();
    $DateProgramTemp = array(
        'BeginDate' => $params->BeginDate,
        'EndDate' => $params->EndDate
    );


    array_push($DateProgram, $DateProgramTemp);

    $DateProgram = json_decode(json_encode($DateProgram));
}


if (oldCount($DateProgram) > 0) {
    foreach ($DateProgram as $value) {

        /* Convierte fechas de formato 'T' a 'Y-m-d H:i:s' y asigna valores a BonoInterno. */
        $StartDate = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $value->BeginDate)));
        $EndDate = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $value->EndDate)));


        $BonoInterno = new BonoInterno();
        $BonoInterno->nombre = $Name;

        /* Código asigna valores a un objeto llamado BonoInterno, configurando su estado y propiedades. */
        $BonoInterno->descripcion = $Description;
        $BonoInterno->fechaInicio = $StartDate;
        $BonoInterno->fechaFin = $EndDate;
        $BonoInterno->tipo = $tipobono;
        $BonoInterno->estado = 'A';
        $BonoInterno->imagen = $MainImageURL;

        /* Asignación de valores a propiedades del objeto BonoInterno según la sesión y variables definidas. */
        $BonoInterno->usucreaId = $_SESSION['usuario'];
        $BonoInterno->usumodifId = 0;
        $BonoInterno->mandante = $mandanteUsuario;
        $BonoInterno->condicional = $ConditionProduct;
        $BonoInterno->orden = $Priority;
        $BonoInterno->cupoActual = $cupo;

        /* Se asignan valores a propiedades del objeto BonoInterno, configurando sus parámetros. */
        $BonoInterno->cupoMaximo = $cupoMaximo;
        $BonoInterno->codigo = $CodeGlobal;
        $BonoInterno->cantidadBonos = $jugadores;
        $BonoInterno->maximoBonos = $jugadoresMaximo;

        $BonoInterno->reglas = $RulesText;


        /* asigna valores a propiedades basadas en condiciones específicas. */
        if ($Type == '1') {
            $BonoInterno->publico = 'I';
        } else {
            $BonoInterno->publico = 'A';
        }


        if ($AllowAdditionalBonuses === true) {
            $BonoInterno->permiteBonos = 1;
        } else {
            /* Establece que no se permiten bonos internos si se cumple cierta condición. */

            $BonoInterno->permiteBonos = 0;
        }

        /* Asigna 'S' o 'N' a perteneceCrm según la variable $IsCRM. */
        if ($IsCRM != "") {

            $BonoInterno->perteneceCrm = 'S';
        } else {
            $BonoInterno->perteneceCrm = 'N';
        }


        /* verifica y asigna un valor a $TypeAction si es igual a '1'. */
        if ($TypeAction != "") {

            if ($TypeAction == '1') {

                $TypeAction = 'REGISTRO';
            }
        }


        /* Asigna un valor a tipoAccion según la variable TypeAction. */
        if ($TypeAction != "") {

            $BonoInterno->tipoAccion = $TypeAction;
        } else {
            $BonoInterno->tipoAccion = null;

        }

        $BonoInterno->categoriaCampaña = $CampaingCategory;
        $BonoInterno->detallesCampaña = $CampaingDetails;


        /* guarda información JSON de un bono y lo inserta en una base de datos. */
        $BonoInterno->jsonTemp = json_encode($params->JsonOriginFront); // proposito: guardar un json con la informacion de un bono para luego poder retornarla al frontend, antes de guardarla en la BD la informacion se utiliza la funcion json_encode

        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $transaccion = $BonoDetalleMySqlDAO->getTransaction();

        $bonoId = $BonoInterno->insert($transaccion);


        /** Inserción detalles del bono */

        /* verifica y procesa un monto mínimo de apuesta para un bono. */
        if (!empty($MinBetAmountRollover)) {
            $MinBetAmountRolloverValue = $MinBetAmountRollover[0]->Amount;
            $MinBetAmountRolloverCurrency = $MinBetAmountRollover[0]->CurrencyId;

            if ($MinBetAmountRolloverValue != '' || $MinBetAmountRolloverCurrency != '') {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "MINBETAMOUNTROLLOVER";
                $BonoDetalle->moneda = $MinBetAmountRolloverCurrency;
                $BonoDetalle->valor = $MinBetAmountRolloverValue;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);
            }
        }

        if(!empty($RemoveByWithdrawal)) {
            $RemoveByWithdrawal = $RemoveByWithdrawal === true ? 1 : 0;
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = 'REMOVEBONUSALTENAR';
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $RemoveByWithdrawal;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMySqlDAO->insert($BonoDetalle);
        }

        /* Crea e inserta un objeto BonoDetalle si hay días de expiración. */
        if ($ExpirationDaysForOutstandingBonds != "") {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "EXPIRATIONDAYSOFOUTSTANDINGBONDS";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $ExpirationDaysForOutstandingBonds;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }


        /* Insertar detalles de bonos si la fecha de expiración no está vacía. */
        if ($ExpirationDateForOutstandingBonds != "") {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "EXPIRATIONDATESOFOUTSTANDINGBONDS";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $ExpirationDateForOutstandingBonds;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }


        /* Crea un objeto BonoDetalle y lo inserta en la base de datos si existe. */
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


        /* Se inserta un registro de bonificación si $UserRepeatBonus no está vacío. */
        if ($UserRepeatBonus != "") {

            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "REPETIRBONO";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $UserRepeatBonus;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);


        }

        /* Inserta un bono de lealtad en la base de datos si está activo. */
        if ($IsLoyalty != "") {

            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "BONOLEALTAD";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $IsLoyalty;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);


        }


        /* Inserta un nuevo registro de BonoDetalle si IsForLanding no está vacío. */
        if ($IsForLanding != "") {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "ISFORLANDING";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $IsForLanding;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);

        }


        /* Inserta un detalle de bono si IsRoulette no está vacío. */
        if ($IsRoulette != "") {

            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "BONORULETA";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $IsRoulette;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);


        }

        /*
         * Se retornar el valor de $EnableBonusCodes en caso de ser 1 = si | 0 = no
         * Recibida la variable se realiza una insercion en la tabla Bono_detalle con los parametros necesarios
         * para que el registro sea efectivo el valor que llegue de la variable debe ser diferente de 0.
         */

        if ($EnableBonusCodes != "0") { // Insercion Codigos Unicos

            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "ALLOWCODEINDIVIDUAL";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $EnableBonusCodes;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }


        /* Inserta un nuevo detalle de bono si existe un sorteo. */
        if ($IsLottery != "") {

            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "BONOSORTEO";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $IsLottery;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);


        }

        /* Crea un registro de bono si se utiliza una billetera congelada. */
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


        /* Inserta un detalle de bono si $SuppressWithdrawal no está vacío. */
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


        /* Crea un nuevo registro de bono si ScheduleCount no está vacío. */
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


        /* Inserta un detalle de bono en la base de datos si ScheduleName no está vacío. */
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


        /* Agrega un registro de bono si SchedulePeriod no está vacío. */
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


        /* inserta un objeto BonoDetalle en la base de datos si $SchedulePeriodType no está vacío. */
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


        /* Inserta un nuevo detalle de bono si el tipo de producto no está vacío. */
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


        /* asigna un valor de depósito según el valor de $Count. */
        $countPossibleValues = [0, 1, 2];
        if ($Count !== "" && $Count !== null && in_array($Count, $countPossibleValues)) {
            if ($Count == 0) {
                $OrdenDeposit = 1;//Primer Depoósito == Count como 0
            } else if ($Count == 1) {
                $OrdenDeposit = 0;//Próximo depoósito == Count como 1
            } else if ($Count == 2) {
                $OrdenDeposit = $OrdenDeposit;  //Depósito específico
            }

            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "CANTDEPOSITOS";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $OrdenDeposit;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);

        }


        /* Crea un objeto BonoDetalle y lo inserta en la base de datos si está permitido. */
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


        /* Inserta un nuevo detalle de bono si la fecha de expiración no está vacía. */
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


        /* Inserta un detalle de bono usando porcentaje si el tipo de bono es válido. */
        if ($TypeBonusDeposit == '1') {


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


        /* Crea un objeto BonoDetalle y lo inserta en la base de datos si $BonusWFactor no está vacío. */
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


        /* Inserta un nuevo registro de bono si el número de depósito no está vacío. */
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


        /* Registra un bono detallado en la base de datos si proviene de un caja. */
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


        /* Inserta un registro de bono con el conteo máximo de jugadores en la base de datos. */
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


        /* Crea y guarda un registro de bono si WinBonusId es distinto de cero. */
        if ($WinBonusId != 0) {

            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "WINBONOID";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $WinBonusId;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);


        } else {
            /* Inserta un nuevo registro de BonoDetalle en la base de datos. */


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


        /* Se insertan detalles de bonos en la base de datos para cada pago máximo. */
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


        /* Inserta detalles de bonificación en base de datos para cada pago mínimo. */
        foreach ($MinPayout as $key => $value) {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "MINPAGO";
            $BonoDetalle->moneda = $value->CurrencyId;
            $BonoDetalle->valor = $value->Amount;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }


        /* Crea y guarda registros de bonos utilizando datos de un arreglo. */
        foreach ($MaxPayoutFixed as $key => $value) {
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


        /* Inserta detalles de bonos para cada monto en un bucle foreach. */
        foreach ($AmountRolloverFixed as $key => $value) {
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


        if ($tipobono == "2") {


            /* Inserta detalles de bonificaciones basadas en depósitos máximos en la base de datos. */
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


            /* Crea instancias de BonoDetalle y las inserta en la base de datos. */
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


        /* Inserta detalles del bono en la base de datos si el tipo de bono es '0'. */
        if ($TypeBonusDeposit == '0') {

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


        /* Inserta detalles de bono en la base de datos para cada sistema de pago. */
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


        /* Inserta detalles de bonificaciones para cada región en la base de datos. */
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


        /* Itera sobre departamentos y crea registros de bono en base de datos. */
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


        /* Itera ciudades y crea registros de bonos en la base de datos. */
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


        /* Inserta un registro de bono si el saldo es cero o verdadero. */
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


        /* Se crea un objeto BonoDetalle con propiedades específicas inicializadas. */
        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "CONDPAISUSER";
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = $Country;
        $BonoDetalle->usucreaId = 0;

        /* Se inserta un bono detalle para cada departamento del usuario en la base de datos. */
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);
        $PaisId = $Country;


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


        /* Crea y guarda detalles de bonos para cada elemento en $CashDesk. */
        foreach ($CashDesk as $key => $value) {

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


        /* Inserta detalles de bonos para cada caja en la base de datos. */
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

        /* Inserta detalles de bonos para cada caja no registrada en la base de datos. */
        foreach ($CashDesksNot as $key => $value) {

            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "CONDNOPUNTOVENTA";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $value;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);

        }

        if ($tipobono == "4") {

            /* Inserta detalles de bonos en la base de datos para cada juego en la lista. */
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


            /* Crea e inserta bonos por cada categoría de casino en la base de datos. */
            foreach ($CasinoCategory as $key => $value) {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "CONDCATEGORY";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $value->Id;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);

            }


            /* inserta detalles de bonos en una base de datos para cada proveedor. */
            foreach ($CasinoProvider as $key => $value) {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "CONDSUBPROVIDER";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $value->Id;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);

            }
        } else {

            /* Inserta detalles de bono para cada juego en una base de datos. */
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


            /* Crea y guarda detalles de bonificaciones para cada categoría de casino. */
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


            /* Inserta detalles de bonos para cada proveedor de casino en la base de datos. */
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
        }


        /* Condición que inserta detalles de bono en base de datos si tipo es "4". */
        if ($tipobono == "4") {
            foreach ($CasinoProduct as $key => $value) {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "CONDGAME";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $value->Id;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);

            }
        } else {
            /* Inserta detalles de bono en base de datos para cada producto de casino. */

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
        }


        /* divide una cadena en valores y los guarda en una base de datos. */
        if ($SportsbookDeports2 != '') {
            $SportsbookDeports2 = explode(',', $SportsbookDeports2);


            foreach ($SportsbookDeports2 as $key => $value) {

                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "ITAINMENT" . '1';
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $value;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);
            }
        }


        /* Separa y almacena valores de una cadena en una base de datos. */
        if ($SportsbookLeagues2 != '') {
            $SportsbookLeagues2 = explode(',', $SportsbookLeagues2);


            foreach ($SportsbookLeagues2 as $key => $value) {

                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "ITAINMENT" . '3';
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $value;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);
            }
        }


        /* inserta detalles de bono a partir de un listado de partidos. */
        if ($SportsbookMatches2 != '') {
            $SportsbookMatches2 = explode(',', $SportsbookMatches2);


            foreach ($SportsbookMatches2 as $key => $value) {

                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "ITAINMENT" . '4';
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $value;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);
            }
        }


        /* procesa mercados deportivos y guarda detalles de bonos en la base de datos. */
        if ($SportsbookMarkets2 != '') {
            $SportsbookMarkets2 = explode(',', $SportsbookMarkets2);


            foreach ($SportsbookMarkets2 as $key => $value) {

                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "ITAINMENT" . '5';
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $value;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);
            }
        }


        /* Itera sobre reglas de bonus para insertar detalles en la base de datos. */
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


        /* Inserta un nuevo BonoDetalle en la base de datos si LiveOrPreMatch no está vacío. */
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


        /* Inserta un nuevo detalle de bono si $MinSelCount no está vacío. */
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


        /* Inserta un registro de bono si $BonusplanId no está vacío. */
        if ($BonusplanId != "") {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "BONUSPLANIDALTENAR";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $BonusplanId;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        /* inserta un nuevo detalle de bono si $CodeBonus no está vacío. */
        if ($CodeBonus != "") {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "BONUSCODEALTENAR";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $CodeBonus;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
        }

        /* Insertar un registro en BonoDetalle si MinSelPrice no está vacío. */
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


        /* Inserta un nuevo registro de bono si el precio total mínimo no está vacío. */
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


        foreach ($BonusDetails as $key => $value) {

            /* inserta un nuevo registro de bono si hay un monto mínimo definido. */
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

            /* inserta un nuevo registro de bono si MaxAmount no está vacío. */
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

                /* Inserta un registro de BonoDetalle si MinAmount no está vacío. */
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

                /* Se inserta un nuevo registro de bono si MaxAmount no está vacío. */
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

                /* Se inserta un nuevo registro de bono basado en un porcentaje especificado. */
                if ($value->Amount->Percent != "") {
                    $BonoDetalle = new BonoDetalle();
                    $BonoDetalle->bonoId = $bonoId;
                    $BonoDetalle->tipo = "PORCENTAJEGGR";
                    $BonoDetalle->moneda = $value->CurrencyId;
                    $BonoDetalle->valor = $value->Amount->Percent;
                    $BonoDetalle->usucreaId = 0;
                    $BonoDetalle->usumodifId = 0;
                    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                    $BonoDetalleMysqlDAO->insert($BonoDetalle);

                }

            }

        }


        /* inserta un bono detalla si se proporciona un código promocional. */
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

        /////Asignar Id Campaña de marketing

        /* inserta detalles de bonificaciones para cada campaña de marketing. */
        if ($MarketingCampaing) {
            foreach ($MarketingCampaing as $idM) {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "MARKETINGCAMPAING";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $idM;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);
            }
        }

        /////////Asignar detalle referido

        /* Crea un registro de detalle de bono si es para referente. */
        if ($IsForReferent == 1) {

            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "BONOREFERENTE";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = 1;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);

        }


        /* inserta un detalle de bono si $ValueBonusMaxRollover es "1". */
        if ($ValueBonusMaxRollover == "1") {

            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "AMOUNTBONUSMAXROLLOVER";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $ValueBonusMaxRollover;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);

        }


        /* inserta detalles de bonos dependiendo de montos y monedas válidas. */
        foreach ($MaxBetAmountRollover as $maxBetRolloverEspecification) {
            $maxBetRollover = $maxBetRolloverEspecification->Amount;
            $currencyIdRollover = $maxBetRolloverEspecification->CurrencyId;

            if (!empty($maxBetRollover) && is_numeric($maxBetRollover) && is_string($currencyIdRollover)) {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "MAXBETAMOUNTROLLOVER";
                $BonoDetalle->moneda = $currencyIdRollover;
                $BonoDetalle->valor = $maxBetRollover;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);
            }
        }


        /* Crea un registro de bono si el valor máximo de bonificación es uno. */
        if ($ValueBonusMaxSpin == "1") {

            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "AMOUNTBONUSMAXSPIN";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $ValueBonusMaxSpin;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);

        }


        /* Condición para insertar un nuevo bono si $MinBetPrice no está vacío. */
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
        /** Finalización creación de detalles */


        /** Creación de cupos para FreeSpins (Aquí NO se crean FreeSpins) */
        if ($FreeSpinsTotalCount != "" && $Prefix != "") {


            /* asigna valores a los jugadores seleccionados y los almacena en un array. */
            $jugadoresAsignar = array();
            $jugadoresAsignarFinal = array();

            if ($PlayersChosen != "") {
                $jugadoresAsignar = explode(",", $PlayersChosen);


                foreach ($jugadoresAsignar as $item) {

                    array_push($jugadoresAsignarFinal, array("Id" => $item, "Valor" => $AutomaticForfeitureLevel));

                }

            }


            /* Se crea un arreglo vacío llamado "codigosarray" en PHP. */
            $codigosarray = array();

            for ($i = 1; $i <= $FreeSpinsTotalCount; $i++) {

                /* Genera un código único de 4 caracteres y verifica su existencia en un array. */
                $codigo = GenerarClaveTicket(4);

                while (in_array($codigo, $codigosarray)) {
                    $codigo = GenerarClaveTicket(4);
                }


                $usuarioId = '0';

                /* Se asignan valores a variables y se establece un estado 'L'. */
                $valor = $AutomaticForfeitureLevel;

                $valor_bono = $AutomaticForfeitureLevel;

                $valor_base = $AutomaticForfeitureLevel;

                $estado = 'L';


                /* Variables inicializadas en cero para su uso posterior en un sistema. */
                $errorId = '0';

                $idExterno = '0';

                $mandante = '0';


                $usucreaId = '0';


                /* Código inicializa variables y concatena un prefijo a una cadena. */
                $usumodifId = '0';


                $apostado = '0';
                $rollowerRequerido = '0';
                $codigo = $Prefix . $codigo;



                /* Se crea un objeto UsuarioBono y se establecen sus propiedades. */
                $UsuarioBono = new UsuarioBono();

                $UsuarioBono->setUsuarioId($usuarioId);
                $UsuarioBono->setBonoId($bonoId);
                $UsuarioBono->setValor($valor);
                $UsuarioBono->setValorBono($valor_bono);

                /* establece propiedades en un objeto UsuarioBono usando diversos valores. */
                $UsuarioBono->setValorBase($valor_base);
                $UsuarioBono->setEstado($estado);
                $UsuarioBono->setErrorId($errorId);
                $UsuarioBono->setIdExterno($idExterno);
                $UsuarioBono->setMandante($mandante);
                $UsuarioBono->setUsucreaId($usucreaId);

                /* establece propiedades en un objeto UsuarioBono y crea un DAO correspondiente. */
                $UsuarioBono->setUsumodifId($usumodifId);
                $UsuarioBono->setApostado($apostado);
                $UsuarioBono->setRollowerRequerido($rollowerRequerido);
                $UsuarioBono->setCodigo($codigo);

                $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($transaccion);


                /* Inserta un bono, asigna código si el ID es válido y guarda en un array. */
                $UsuarioBonoMysqlDAO->insert($UsuarioBono);

                if ($jugadoresAsignarFinal[$i]["Id"] != "") {
                    $jugadoresAsignarFinal[$i]["Code"] = $codigo;
                }

                array_push($codigosarray, $codigo);

            }
        }


        /* Evalúa condiciones para determinar si dar acceso a usuarios específicos. */
        $darAUsuariosEspecificos = false;
        if (($MaxplayersCount != "" && $Prefix != "") || ($MaxplayersCount != "" && ($MinimumAmount) != '')) {
            $darAUsuariosEspecificos = true;
        }

        /** Creación de cupos para todos los otros tipos de bonos */
        if ($darAUsuariosEspecificos) {

            /* Inicializa arrays para jugadores y muestra los resultados si está en modo debug. */
            $jugadoresAsignar = array();
            $jugadoresAsignarFinal = array();

            if ($_ENV['debug']) {
                print_r('jugadoresAsignarFinalANTES');
                print_r($jugadoresAsignarFinal);
            }

            /* asigna jugadores a una lista final con un valor determinado. */
            if ($PlayersChosen != "") {
                $jugadoresAsignar = explode(",", $PlayersChosen);


                foreach ($jugadoresAsignar as $item) {

                    array_push($jugadoresAsignarFinal, array("Id" => $item, "Valor" => $AutomaticForfeitureLevel));

                }

            }


            /* verifica si la depuración está activada y muestra datos de jugadores. */
            if ($_ENV['debug']) {
                print_r('jugadoresAsignarFinal');
                print_r($jugadoresAsignarFinal);
            }

            $codigosarray = array();


            if ($tipobono == 4) {
                if ($arrayfinal != "" && $arrayfinal != null) {
                    foreach ($arrayfinal as $key => $value) {


                        /* Variables de usuario y estado inicializadas, así como valores para bono. */
                        $usuarioId = $value;
                        $estado = 'P';

                        $valor = '0';

                        $valor_bono = '0';


                        /* Se definen variables iniciales con valores predeterminados en PHP. */
                        $valor_base = '0';

                        $errorId = '0';

                        $idExterno = '0';

                        $mandante = '0';


                        /* Se inicializan variables y se genera un código de ticket aleatorio de 4 caracteres. */
                        $usucreaId = '0';

                        $usumodifId = '0';

                        $codigosarray = array();

                        $codigo = GenerarClaveTicket(4);

                        /* Código en PHP que establece valores iniciales y configura un objeto UsuarioBono. */
                        $apostado = '0';
                        $rollowerRequerido = '0';
                        $codigo = $Prefix . $codigo;

                        $UsuarioBono = new UsuarioBono();

                        $UsuarioBono->setUsuarioId($usuarioId);

                        /* Se configuran atributos de un objeto UsuarioBono usando varios valores. */
                        $UsuarioBono->setBonoId($bonoId);
                        $UsuarioBono->setValor($valor);
                        $UsuarioBono->setValorBono($valor_bono);
                        $UsuarioBono->setValorBase($valor_base);
                        $UsuarioBono->setEstado($estado);
                        $UsuarioBono->setErrorId($errorId);

                        /* establece propiedades para un objeto UsuarioBono. */
                        $UsuarioBono->setIdExterno($idExterno);
                        $UsuarioBono->setMandante($mandante);
                        $UsuarioBono->setUsucreaId($usucreaId);
                        $UsuarioBono->setUsumodifId($usumodifId);
                        $UsuarioBono->setApostado($apostado);
                        $UsuarioBono->setRollowerRequerido($rollowerRequerido);

                        /* Se están estableciendo propiedades de un objeto y luego se inserta en la base de datos. */
                        $UsuarioBono->setCodigo($codigo);
                        $UsuarioBono->setVersion(0);
                        $UsuarioBono->setExternoId(0);

                        $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($transaccion);

                        $inse = $UsuarioBonoMysqlDAO->insert($UsuarioBono);
                    }

                } else {
                    for ($i = 0; $i < $MaxplayersCount; $i++) {

                        /* Variables inicializadas para usuario, estado y valores de bono en PHP. */
                        $usuarioId = 0;
                        $estado = 'L';

                        $valor = '0';

                        $valor_bono = '0';


                        /* Se inicializan variables para manejar valores y posibles errores en programación. */
                        $valor_base = '0';

                        $errorId = '0';

                        $idExterno = '0';

                        $mandante = '0';


                        /* inicializa variables y genera un código usando la función GenerarClaveTicket. */
                        $usucreaId = '0';

                        $usumodifId = '0';

                        $codigosarray = array();

                        $codigo = GenerarClaveTicket(4);

                        /* inicializa variables y crea un objeto de UsuarioBono con un ID de usuario. */
                        $apostado = '0';
                        $rollowerRequerido = '0';
                        $codigo = $Prefix . $codigo;

                        $UsuarioBono = new UsuarioBono();

                        $UsuarioBono->setUsuarioId($usuarioId);

                        /* Se establecen múltiples propiedades en el objeto UsuarioBono. */
                        $UsuarioBono->setBonoId($bonoId);
                        $UsuarioBono->setValor($valor);
                        $UsuarioBono->setValorBono($valor_bono);
                        $UsuarioBono->setValorBase($valor_base);
                        $UsuarioBono->setEstado($estado);
                        $UsuarioBono->setErrorId($errorId);

                        /* Código PHP que establece propiedades de un objeto "UsuarioBono". */
                        $UsuarioBono->setIdExterno($idExterno);
                        $UsuarioBono->setMandante($mandante);
                        $UsuarioBono->setUsucreaId($usucreaId);
                        $UsuarioBono->setUsumodifId($usumodifId);
                        $UsuarioBono->setApostado($apostado);
                        $UsuarioBono->setRollowerRequerido($rollowerRequerido);

                        /* Configura un objeto UsuarioBono con código, fecha, versión y ID externo. */
                        $UsuarioBono->setCodigo($codigo);
                        $UsuarioBono->setFechaExpiracion(); // fecha expiracion nueva
                        $UsuarioBono->setVersion(0);
                        $UsuarioBono->setExternoId(0);

                        $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($transaccion);


                        /* Inserta un objeto $UsuarioBono en la base de datos usando $UsuarioBonoMysqlDAO. */
                        $inse = $UsuarioBonoMysqlDAO->insert($UsuarioBono);
                    }
                }
            } else {
                for ($i = 0; $i < $MaxplayersCount; $i++) {

                    /* Genera un código único de 4 caracteres que no esté en un array existente. */
                    $codigo = GenerarClaveTicket(4);

                    while (in_array($codigo, $codigosarray)) {
                        $codigo = GenerarClaveTicket(4);
                    }


                    $usuarioId = '0';

                    /* asigna estados a jugadores según condiciones específicas de bonificación. */
                    $estado = 'L';

                    /*if ($tipobono != 2) {
                        if ($jugadoresAsignarFinal[$i] != "" && $jugadoresAsignarFinal[$i]["Id"] != null) {
                            $usuarioId = $jugadoresAsignarFinal[$i]["Id"];
                            $estado = 'A';

                        }

                    }*/

                    if ($tipobono == "2") {
                        if ($jugadoresAsignarFinal[$i] != "" && $jugadoresAsignarFinal[$i]["Id"] != null) {
                            $usuarioId = $jugadoresAsignarFinal[$i]["Id"];
                            $estado = 'P';

                        }
                    }


                    /* Verifica condiciones para asignar un usuario según tipo de bono y estado. */
                    if ($tipobono == "6") {
                        if ($jugadoresAsignarFinal[$i] != "" && $jugadoresAsignarFinal[$i]["Id"] != null) {
                            $usuarioId = $jugadoresAsignarFinal[$i]["Id"];
                            $estado = 'A';

                        }
                    }


                    /* Se inicializan variables en PHP con valores predeterminados. */
                    $valor = '0';

                    $valor_bono = '0';

                    $valor_base = '0';

                    $errorId = '0';


                    /* Variables inicializadas con valores '0' para uso posterior en el código. */
                    $idExterno = '0';

                    $mandante = '0';


                    $usucreaId = '0';


                    /* Inicializa variables y concatena un prefijo al código en PHP. */
                    $usumodifId = '0';


                    $apostado = '0';
                    $rollowerRequerido = '0';
                    $codigo = $Prefix . $codigo;

                    if (strlen($codigo) >= 16) {
                        throw new Exception("Prefijo debe ser menor o igual a 11 caracteres", "300191");
                    }


                    /* Se crea un objeto UsuarioBono y se asignan sus propiedades. */
                    $UsuarioBono = new UsuarioBono();

                    $UsuarioBono->setUsuarioId($usuarioId);
                    $UsuarioBono->setBonoId($bonoId);
                    $UsuarioBono->setValor($valor);
                    $UsuarioBono->setValorBono($valor_bono);

                    /* Configuración de propiedades para un objeto UsuarioBono en programación. */
                    $UsuarioBono->setValorBase($valor_base);
                    $UsuarioBono->setEstado($estado);
                    $UsuarioBono->setErrorId($errorId);
                    $UsuarioBono->setIdExterno($idExterno);
                    $UsuarioBono->setMandante($mandante);
                    $UsuarioBono->setUsucreaId($usucreaId);

                    /* Configura propiedades de un objeto UsuarioBono con datos específicos. */
                    $UsuarioBono->setUsumodifId($usumodifId);
                    $UsuarioBono->setApostado($apostado);
                    $UsuarioBono->setRollowerRequerido($rollowerRequerido);
                    $UsuarioBono->setCodigo($codigo);
                    $UsuarioBono->setVersion(0);
                    $UsuarioBono->setExternoId(0);


                    /* Se inserta un objeto UsuarioBono y se asigna un código a jugadores específicos. */
                    $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($transaccion);

                    $inse = $UsuarioBonoMysqlDAO->insert($UsuarioBono);


                    if ($jugadoresAsignarFinal[$i]["Id"] != "") {

                        $jugadoresAsignarFinal[$i]["Code"] = $codigo;
                    }


                    /* imprime información si el entorno de depuración está activo y agrega un código. */
                    if ($_ENV['debug']) {
                        print_r('ENTRO2');
                        print_r($UsuarioBono);
                    }
                    array_push($codigosarray, $codigo);

                }
            }
        }


        /* Código muestra datos de depuración si la variable de entorno 'debug' está activa. */
        if ($_ENV['debug']) {
            print_r('ENTRO1');
            print_r($codigosarray);

        }

        $transaccion->commit();
        if ($IsCRM != "") {


            /* Se crean instancias de Clasificador, MandanteDetalle y Proveedor con parámetros específicos. */
            $Clasificador = new Clasificador("", "PROVCRM");

            $MandanteDetalle = new MandanteDetalle('', $mandanteUsuario, $Clasificador->clasificadorId, $PaisId, 'A');

            $Proveedor = new Proveedor($MandanteDetalle->valor);

            switch ($Proveedor->abreviado) {
                case "OPTIMOVE":
                    /* gestiona promociones en Optimove utilizando un identificador de bono. */


                    $BonoId = "B" . $bonoId;
                    $Optimove = new Optimove();
                    //   $Token = $Optimove->Login($mandanteUsuario,$PaisId);

                    // $Token = $Token->response;
                    $respon = $Optimove->AddPromotions($BonoId, $Description, $mandanteUsuario, $PaisId);

                    break;

                case "FASTTRACK":
                    /* Es un fragmento de código que define un caso en una estructura switch. */


                    break;

                case "CRMPROPIO":
                    /* Código que activa un caso específico "CRMPROPIO" sin realizar ninguna acción. */


                    break;
            }

        }


        /* Instancia de DAO y manejo de transacciones para asignación de bonos. */
        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $transaccion = $BonoDetalleMySqlDAO->getTransaction();


        //Los tipos 2,6 hacen asignación masiva de bonos sin pasar por BonoInterno, desde la creación del cupo en la zona superior
        //El tipo 8 no se crea por este recurso
        $totalQueries = empty($arrayfinal) ? 0 : count($arrayfinal);

        /* define límites para asignación de bonos según tipos y condiciones específicas. */
        $disableBonusesMassiveAssignationTypes = [6, 8];
        $maxPlayersCoupons = $MaxplayersCount;
        if (in_array($tipobono, [2, 3]) && !empty($BonusplanId)) {
            //Si el bono es por Altenar lo configurarán con un único jugador en Torneos y Bonos, por lo que se redefine $maxPlayersCoupons
            $maxPlayersCoupons = $totalQueries;
        } else {
            /* añade el valor 2 a un array si no se cumple una condición. */

            $disableBonusesMassiveAssignationTypes[] = 2;
        }

        if (!in_array($tipobono, $disableBonusesMassiveAssignationTypes) && $totalQueries > 0 && $totalQueries < 10000) {
            /** Asignación masiva de bonos --Área de parametrización*/

            /* Se configuran parámetros para procesar usuarios en bloques de ejecución controlados por tiempo. */
            $usersPerQuery = 40; //Cuántos ID`s de usuarios se enviarán en cada ejecución (Exec)
            $execsPerBlockTime = 6; //Cuántos execs pueden estar procesándose a la vez (Un Bloque de execs)
            $secondsDifferencePerBlockTime = 5; //Cuánto tiempo (segundos) apartará a un bloque de execs de otro bloque de execs

            //Iterando usuarios por asignar
            $secondsForExecution = 0; //Representa cuántos segundos debe esperar el exec antes de ejecutarse

            /* Se asigna el valor de $usersPerQuery a $queriedAssignations y se inicializa $execsInBlockTime. */
            $queriedAssignations = $usersPerQuery; //Solicitudes ejecutadas
            $execsInBlockTime = 0; //Ejecuciones realizadas
            for ($sentQueries = 0; $sentQueries < $maxPlayersCoupons; $queriedAssignations += $usersPerQuery) {

                /* Ajusta intervalos de espera y verifica el rango de usuarios permitidos. */
                if ($execsInBlockTime == $execsPerBlockTime) {
                    /** Definiendo un nuevo intervalo de espera para las próximas ejecuciones */
                    $secondsForExecution += $secondsDifferencePerBlockTime;
                    $execsInBlockTime = 0;
                }

                /** Verificando cuál es el rango de usuarios que pueden ser consultados */
                if ($maxPlayersCoupons > $totalQueries) $maxPlayersCoupons = $totalQueries;

                /* limita las asignaciones de jugadores y define un rango para verificar usuarios. */
                if ($queriedAssignations > $maxPlayersCoupons) $queriedAssignations = $maxPlayersCoupons;

                //El rango de usuarios por consultar y verificar corresponde a $sentQueries (Inicio intervalo es inclusivo) hasta $queriedAssignations (Fin intervalo es exclusivo)
                //[$sentQueries, $queriedAssignations)
                $usersToSend = "";
                $couponsCodesToSend = "";

                /* Itera y combina IDs de jugadores con códigos de cupones, creando un string separado por comas. */
                for ($playerIndex = $sentQueries; $playerIndex < $queriedAssignations; $playerIndex++) {
                    $playerId = null;
                    $couponCode = null;
                    $playerId = $arrayfinal[$playerIndex];
                    $couponCode = $codigosarray[$playerIndex];
                    $userPlusCoupon = $playerId . "_" . $couponCode;
                    if (!empty($playerId) && is_numeric($playerId)) {
                        $usersToSend .= ($usersToSend != "" ? "," : "") . "{$userPlusCoupon}";
                    }
                }


                /** Ejecutando acreditacion en segundo plano */

                /* Ejecuta un script PHP en segundo plano con parámetros específicos. */
                $phpPath = PHP_BINDIR . "/php"; //Ubicación PHP en linux
                $execMode = "> /dev/null & "; //Este modo solicita la ejecución en 2do plano y sigue con el script
                // $execMode = "2>&1 "; //Este modo espera la ejecución del recurso y el retorno de la respuesta


                /** argv`s 1=Segundos espera 2=bonoId 3=tipobono 4=Jugadores separados por comas */
                //exec($phpPath . " -f " . __DIR__ . "/CreateBonusExec.php '$secondsForExecution' '$bonoId' '$tipobono' '$usersToSend' " . $execMode);

                $redisParam = ['ex' => 18000];

                $redisPrefix = "F100BACK+COMMANDEXEC+UID+".$secondsForExecution."+".$bonoId."+".$tipobono.'+'.$execsInBlockTime;

                $redis = RedisConnectionTrait::getRedisInstance(true);

                if($redis != null) {

                    $redis->set($redisPrefix, base64_encode($phpPath . " -f " . __DIR__ . "/CreateBonusExec.php '$secondsForExecution' '$bonoId' '$tipobono' '$usersToSend' " . $execMode), $redisParam);
                }else{
                    exec($phpPath . " -f " . __DIR__ . "/CreateBonusExec.php '$secondsForExecution' '$bonoId' '$tipobono' '$usersToSend' " . $execMode);
                }

                //--End Function

                /* Se asigna el valor de `$queriedAssignations` a `$sentQueries` para actualizar datos. */
                $sentQueries = $queriedAssignations; //Actualizando último usuario asignado
                $execsInBlockTime++; //Suma una nueva ejecución al bloque
            }
        }
    }


    /* Se configura un array de respuesta con datos y sin errores. */
    $response["idBonus"] = $bonoId;

    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    /* Inicializa un arreglo vacío llamado "Result" en la variable de respuesta. */
    $response["Result"] = array();
} else {
    /* maneja una respuesta de error asignando valores a un array. */

    $response["idBonus"] = 0;
    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];
    $response["Result"] = array();
}
