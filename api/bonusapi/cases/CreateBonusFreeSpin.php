<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\UsuarioBono;
use Backend\dto\Clasificador;
use Backend\dto\Subproveedor;
use Backend\dto\MandanteDetalle;
use Backend\dto\ProductoDetalle;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\integrations\crm\Optimove;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\integrations\casino\MASCOTSERVICES;
use Backend\integrations\casino\PASCALSERVICES;
use Backend\integrations\casino\PGSOFTSERVICES;
use Backend\integrations\casino\PLAYNGOSERVICES;
use Backend\integrations\casino\PLAYSONSERVICES;
use Backend\integrations\casino\TOMHORNSERVICES;
use Backend\integrations\casino\IESGAMESSERVICES;
use Backend\integrations\casino\KAGAMINGSERVICES;
use Backend\integrations\casino\PLATIPUSSERVICES;
use Backend\integrations\casino\PLAYTECHSERVICES;
use Backend\integrations\casino\RUBYPLAYSERVICES;
use Backend\integrations\casino\PRAGMATICSERVICES;
use Backend\integrations\casino\SMARTSOFTSERVICES;
use Backend\integrations\casino\SOFTSWISSSERVICES;
use Backend\integrations\casino\ENDORPHINASERVICES;
use Backend\integrations\casino\WORLDMATCHSERVICES;
use Backend\integrations\casino\AMIGOGAMINGSERVICES;
use Backend\integrations\casino\G7777GAMINGSERVICES;
use Backend\integrations\casino\GAMESGLOBALSERVICES;
use Backend\integrations\casino\REDRAKESERVICESBONUS;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\utils\RedisConnectionTrait;

/**
 * Este script maneja la creación de bonos de giros gratis en el sistema.
 *
 * @param object $params Objeto que contiene los siguientes valores:
 * @param string $params->Description Descripción del bono.
 * @param string $params->Name Nombre del bono.
 * @param string $params->BeginDate Fecha de inicio del bono.
 * @param string $params->EndDate Fecha de finalización del bono.
 * @param object $params->PartnerLealtad Objeto que contiene:
 *  - $PartnerLealtad->ExpirationDate Fecha de expiración del bono.
 * - $PartnerLealtad->ExpirationDays Días de expiración del bono.
 * @param string $params->LiveOrPreMatch Indica si el bono aplica a eventos en vivo o pre-partido.
 * @param int $params->MinSelCount Mínimo número de selecciones requeridas.
 * @param float $params->MinSelPrice Mínima cuota seleccionada.
 * @param string $params->MainImageURL URL de la imagen principal del bono.
 * @param float $params->CurrentCost Costo actual del bono.
 * @param object $params->DepositDefinition Objeto que contiene:
 *  - $DepositDefinition->LealtadDefinition Definición de lealtad.
 * - $DepositDefinition->LealtadDefinitionId ID de la definición de lealtad.
 * - $DepositDefinition->LealtadPercent Porcentaje de lealtad.
 * - $DepositDefinition->LealtadWFactor Factor de rollover de lealtad.
 * - $DepositDefinition->DepositNumber Número de depósitos.
 * - $DepositDefinition->DepositWFactor Factor de rollover de depósito.
 * - $DepositDefinition->SuppressWithdrawal Indica si se suprime el retiro.
 * - $DepositDefinition->UseFrozeWallet Indica si se utiliza billetera congelada.
 * @param bool $params->IsVisibleForAllplayers Indica si el bono es visible para todos los jugadores.
 * @param bool $params->OpenToAllPlayers Indica si el bono está abierto a todos los jugadores.
 * @param int $params->MaxplayersCount Máximo número de jugadores que pueden obtener el bono.
 * @param string $params->Prefix Prefijo para los códigos de bono.
 * @param array $params->MarketingCampaingIdsSelectList IDs de campañas de marketing asociadas.
 * @param string $params->TypeProduct Tipo de producto asociado al bono.
 * @param object $params->ForeignRule Objeto que contiene:
 * - $ForeignRule->Info Información de reglas extranjeras.
 * @param int $params->TriggerId ID del disparador del bono.
 * @param int $params->TypeId Tipo de bono.
 * @param array $params->Games Juegos asociados al bono.
 * @param array $params->MaxPayout Pagos máximos permitidos.
 * @param float $params->MaximumLealtadAmount Monto máximo de lealtad.
 * @param array $params->MaximumDeposit Depósitos máximos permitidos.
 * @param array $params->MinimumDeposit Depósitos mínimos permitidos.
 * @param array $params->MinimumAmount Monto mínimo requerido.
 * @param array $params->MaximumAmount Monto máximo permitido.
 * @param array $params->MoneyRequirement Requisitos monetarios.
 * @param float $params->MoneyRequirementAmount Monto requerido.
 * @param object $params->Schedule Objeto que contiene:
 *  - $Schedule->Count Cantidad de programaciones.
 * - $Schedule->Name Nombre de la programación.
 * - $Schedule->Period Período de la programación.
 * - $Schedule->PeriodType Tipo de período de la programación.
 * @param object $params->TriggerDetails Objeto que contiene:
 * - $TriggerDetails->Count Cantidad de depósitos.
 * - $TriggerDetails->IsFromCashDesk Indica si proviene de caja.
 * - $TriggerDetails->PaymentSystemId ID del sistema de pago.
 * - $TriggerDetails->PaymentSystemIds IDs de sistemas de pago.
 * - $TriggerDetails->Regions Regiones asociadas.
 * - $TriggerDetails->Departments Departamentos asociados.
 * - $TriggerDetails->Cities Ciudades asociadas.
 * - $TriggerDetails->CashDesks Puntos de venta asociados.
 * - $TriggerDetails->RegionsUser Regiones de usuarios asociadas.
 * - $TriggerDetails->DepartmentsUser Departamentos de usuarios asociados.
 * - $TriggerDetails->CitiesUser Ciudades de usuarios asociadas.
 * - $TriggerDetails->BalanceZero Indica si el balance debe ser cero.
 * @param int $params->WinLealtadId ID de lealtad ganadora.
 * @param string $params->TypeSaldo Tipo de saldo asociado.
 * @param int $params->BonoId ID del bono.
 * @param array $params->Points Puntos asociados al bono.
 * @param bool $params->TypeAward Indica si el premio es virtual o físico.
 * @param bool $params->BetShopOwn Indica si el punto de venta es propio.
 * @param string $params->ConditionProduct Condición del producto.
 * @param object $params->FreeSpinDefinition Objeto que contiene:
 * - $FreeSpinDefinition->AutomaticForfeitureLevel Nivel de pérdida automática.
 * - $FreeSpinDefinition->LealtadMoneyExpirationDate Fecha de expiración del dinero de lealtad.
 * - $FreeSpinDefinition->LealtadMoneyExpirationDays Días de expiración del dinero de lealtad.
 * - $FreeSpinDefinition->FreeSpinsTotalCount Total de giros gratis.
 * - $FreeSpinDefinition->WageringFactor Factor de apuesta.
 * @param array $params->PlayersChosen Jugadores seleccionados.
 * @param object $params->Casino Objeto que contiene:
 * - $Casino->Info Información del casino.
 * - $Casino->Category Categorías del casino.
 * - $Casino->Provider Proveedores del casino.
 * - $Casino->Product Productos del casino.
 * @param string $params->DescriptionPrize Descripción del premio.
 * @param string $params->RulesText Texto de las reglas del bono.
 * @param int $params->TypeLealtadDeposit Tipo de depósito de lealtad.
 * @param string $params->Type Tipo de bono.
 *
 *
 * @return array $response Respuesta del sistema:
 * - $response["HasError"] (bool): Indica si ocurrió un error.
 * - $response["AlertType"] (string): Tipo de alerta generada.
 * - $response["AlertMessage"] (string): Mensaje de alerta.
 * - $response["LealtadId"] (int): ID de lealtad generado.
 * - $response["ModelErrors"] (array): Errores del modelo.
 * - $response["Result"] (array): Resultado del proceso.
 *
 * @throws Exception Si ocurre un error durante la ejecución del script.
 */

/* Controla la visualización de errores según el entorno de depuración configurado. */
ini_set("display_errors", "OFF");

if ($_ENV['debug'] == true) {
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

/* registra información de bonificación y la guarda en un archivo de log. */
$log = "";
$log = $log . "CreateBonusFreeSpin " . time();

$log = $log . "\r\n" . "-------------------------" . "\r\n";
$log = $log . (file_get_contents('php://input'));
//Save string to log, use FILE_APPEND to append.

fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


/* Asigna el mandante del usuario si no es Global en la sesión. */
$mandanteUsuario = 0;
// Si el usuario esta condicionado por el mandante y no es de Global
if ($_SESSION['Global'] == "N") {
    $mandanteUsuario = $_SESSION['mandante'];
}
try {


    /* asigna valores de parámetros a variables relacionadas con una campaña de bonos. */
    $Description = $params->Description; //Descripcion del bono
    $Name = $params->Name; //Nombre del bono
    $StartDate = $params->BeginDate; //Fecha Inicial de la campaña
    $EndDate = $params->EndDate; //Fecha Final de la campaña
    $DateProgram = $params->DateProgram;
    $CampaingCategory = $params->CampaingCategory;

    /* asigna parámetros y verifica el país del usuario en la sesión. */
    $CampaingDetails = $params->CampaingDetails;
    $MainImageURL = $params->MainImageURL;

    $PartnerBonus = $params->PartnerBonus;
    $Country = $params->Country;


    if ($Country == "") {
        $Country = $_SESSION["pais_id"];
    }


    /* Asigna fechas de expiración a un bono si no están definidas. */
    $ExpirationDate = $PartnerBonus->ExpirationDate; //Fecha de expiracion del bono
    $ExpirationDays = $PartnerBonus->ExpirationDays; // Dias de expiracion del bono


    if ($ExpirationDate == "" && $ExpirationDays == "") {
        $ExpirationDate = $params->ExpirationDate; //Fecha de expiracion del bono
        $ExpirationDays = $params->ExpirationDays; // Dias de expiracion del bono

    }


    /* Asigna valores de parámetros a variables para manejo de costos y selecciones. */
    $LiveOrPreMatch = $params->LiveOrPreMatch;
    $MinSelCount = $params->MinSelCount;
    $MinSelPrice = $params->MinSelPrice;


    $CurrentCost = $params->CurrentCost;


    /* extrae definiciones de depósito y bonificaciones de un parámetro. */
    $DepositDefinition = $params->DepositDefinition;

    $BonusDefinition = $DepositDefinition->BonusDefinition;
    $BonusDefinitionId = $DepositDefinition->BonusDefinitionId;
    $BonusPercent = $DepositDefinition->BonusPercent; // Porcentaje del bono
    $BonusWFactor = $DepositDefinition->BonusWFactor; //Rollower bono

    /* asigna valores de un objeto a variables para configuraciones de depósitos. */
    $DepositNumber = $DepositDefinition->DepositNumber;
    $DepositWFactor = $DepositDefinition->DepositWFactor; //Rollower Deposito

    $SuppressWithdrawal = $DepositDefinition->SuppressWithdrawal; //Suprimir la retirada
    $UseFrozeWallet = $DepositDefinition->UseFrozeWallet;

    $IsVisibleForAllplayers = $params->IsVisibleForAllplayers; //Es para todos los usuarios

    /* Código que define configuraciones de jugadores y cartones en un juego. */
    $OpenToAllPlayers = $params->OpenToAllPlayers;//Es para todos los usuarios
    $MaxplayersCount = $params->MaxplayersCount; // Maximo jugadores que lo pueden obtener
    $Prefix = $params->Prefix;
    $numberOfCartons = $params->NumberOfCartons;

    $PlayersChosen = $params->PlayersChosen;


    /* verifica si ForeignRuleInfo es un objeto y lo convierte a JSON si no. */
    $ForeignRule = $params->ForeignRule;
    $ForeignRuleInfo = $ForeignRule->Info;
    $MarketingCampaing = $params->MarketingCampaingIdsSelectList;

    if (!is_object($ForeignRuleInfo)) {
        $ForeignRuleJSON = json_decode($ForeignRuleInfo);

    } else {
        /* asigna $ForeignRuleInfo a $ForeignRuleJSON si no se cumple una condición previa. */

        $ForeignRuleJSON = $ForeignRuleInfo;
    }


    /* extrae valores de un objeto JSON y los asigna a variables. */
    $LiveOrPreMatch = $ForeignRuleJSON->LiveOrPreMatch;
    $MinSelCount = $ForeignRuleJSON->MinSelCount; // Minimo cantidad de selecciones
    $MinSelPrice = $ForeignRuleJSON->MinSelPrice; // Minimo cuota seleccionada
    $MinSelPriceTotal = $ForeignRuleJSON->MinSelPriceTotal;

    $TriggerId = $params->TriggerId;

    /* extrae parámetros y reglas de una promoción deportiva para apuestas. */
    $CodePromo = $params->CodePromo;


    $MinBetPrice = $ForeignRuleJSON->MinBetPrice; // Minimo apuesta cuota total
    $SportBonusRules = $ForeignRuleJSON->SportBonusRules;

    $ProductTypeId = $params->ProductTypeId;


    /* asigna un id basado en condiciones de promoción y tipo de evento. */
    $TriggerId = $params->TriggerId;

    if ($CodePromo != "") {
        $TriggerId = 1;
    }

    $TypeId = $params->TypeId;


    /* asigna juegos y la máxima ganancia a variables para su uso posterior. */
    $Games = $params->Games;

    $condiciones = [];


    $MaxPayout = $params->MaxPayout; //Pago Maximo

    /* asigna valores de parámetros a variables sobre límites de bonos y depósitos. */
    $MaximumBonusAmount = $params->MaximumBonusAmount; //Maximo valordel bono
    $MaximumDeposit = $params->MaximumDeposit;
    $MinimumDeposit = $params->MinimumDeposit;
//$MinimumAmount = $params->MinimumAmount;
    $MaximumAmount = $params->MaximumAmount;
    $MoneyRequirement = $params->MoneyRequirement;

    /* Variables que almacenan requisitos financieros y detalles de programación de bonos. */
    $MoneyRequirementAmount = $params->MoneyRequirementAmount;

    $Schedule = $params->Schedule; //Programar bono
    $ScheduleCount = $Schedule->Count; //
    $ScheduleName = $Schedule->Name; //Descripcion de la programacion
    $SchedulePeriod = $Schedule->Period;

    /* obtiene detalles de un horario y características del disparador de depósitos. */
    $SchedulePeriodType = $Schedule->PeriodType;

    $TriggerDetails = $params->TriggerDetails;
    $Count = $TriggerDetails->Count; //Cantidad de depositos

//$AreAllowed = $TriggerDetails->AreAllowed; //Son Permitidos los paises

    $IsFromCashDesk = $TriggerDetails->IsFromCashDesk;

    /* asigna datos de un objeto TriggerDetails a variables específicas. */
    $PaymentSystemId = $TriggerDetails->PaymentSystemId;
    $PaymentSystemIds = $TriggerDetails->PaymentSystemIds;

    $Regions = $TriggerDetails->Regions;
    $Departments = $TriggerDetails->Departments;
    $Cities = $TriggerDetails->Cities;

    /* asigna variables a detalles del disparador y parámetros de usuario. */
    $CashDesks = $TriggerDetails->CashDesk;
    $CashDesksNot = $TriggerDetails->CashDesksNot;
    $RegionsUser = $TriggerDetails->RegionsUser;
    $DepartmentsUser = $TriggerDetails->DepartmentsUser;
    $CitiesUser = $TriggerDetails->CitiesUser;
    $UserRepeatBonus = $params->UserRepeatBonus;

    /* Código extrae y asigna valores de parámetros relacionados con rondas y bonificaciones. */
    $rounds = $params->Rounds;


    $FreeRoundsMaxDays = $params->FreeRoundsMaxDays;
    $BalanceZero = $TriggerDetails->BalanceZero;

    $WinBonusId = $params->WinBonusId;

    /* asigna valores a variables y valida la prioridad numérica. */
    $TypeSaldo = $params->TypeSaldo;
    $Priority = $params->Priority;

    if ($Priority == "" || !is_numeric($Priority)) {
        $Priority = 0;
    }


    /* valida una condición y asigna un valor si no es "OR" o "AND". */
    $ConditionProduct = $TriggerDetails->ConditionProduct;
    if ($ConditionProduct != "OR" && $ConditionProduct != "AND") {
        $ConditionProduct = 'NA';
    }

    $FreeSpinDefinition = $params->FreeSpinDefinition;

    /* Asigna variables relacionadas a definiciones de giros gratis en un sistema. */
    $AutomaticForfeitureLevel = $FreeSpinDefinition->AutomaticForfeitureLevel;
    $BonusMoneyExpirationDate = $FreeSpinDefinition->BonusMoneyExpirationDate;
    $BonusMoneyExpirationDays = $FreeSpinDefinition->BonusMoneyExpirationDays;
    $FreeSpinsTotalCount = $FreeSpinDefinition->FreeSpinsTotalCount;
    $WageringFactor = $FreeSpinDefinition->WageringFactor;
    $PlayersChosen = $params->PlayersChosen;

    /* Asigna valores de parámetros a variables correspondientes en un contexto de casino. */
    $Casino = $params->Casino->Info;
    $CasinoCategory = $Casino->Category;
    $CasinoProvider = $Casino->Provider;
    $CasinoProduct = $Casino->Product;
    $IsLoyalty = $params->IsLoyalty;
//    $IsCRM = $params->IsCRM;


    $IsFor = $params->IsFor;

    /* Variables vacías de configuración para diferentes tipos de promociones o campañas. */
    $IsLoyalty = '';
    $IsLottery = '';
    $IsRoulette = '';
    $IsForReferent = '';
    $IsForLanding = '';
    $IsCRM = '';


    /* Asigna variables según el valor de $IsFor en un switch. */
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


    /* procesa datos de bonificaciones y jugadores en formato CSV. */
    $BonusDetails = $params->BonusDetails;
    $CodeGlobal = $params->CodeGlobal;
    $RulesText = str_replace("'", "\'", $params->RulesText);

    $PlayersIdCsv = $params->PlayersIdCsv;
    $PlayersIdCsv = explode("base64,", $PlayersIdCsv);

    /* decodifica, reemplaza caracteres y divide una cadena en líneas. */
    $PlayersIdCsv = $PlayersIdCsv[1];

    $PlayersIdCsv = base64_decode($PlayersIdCsv);

    $PlayersIdCsv = str_replace(";", ",", $PlayersIdCsv);

    $lines = explode(PHP_EOL, $PlayersIdCsv);

    /* divide un archivo CSV en líneas y convierte cada línea en un array. */
    $lines = preg_split('/\r\n|\r|\n/', $PlayersIdCsv);

    $array = array();
    foreach ($lines as $line) {

        $array[] = str_getcsv($line);

    }


    /* Extrae valores de la primera columna de un array y obtiene sus posiciones. */
    $arrayfinal = array_column($array, '0');
//$arrayfinal3 =  array_column($array,'1');


    /*$primera = substr($arrayfinal[0], 3);*/

    /*$arrayfinal[0] = $primera;*/

    $posiciones = array_keys($arrayfinal);


    /* procesa un arreglo eliminando un elemento y filtrando valores vacíos. */
    $ultima = strval(end($posiciones));
    $arrayfinal = json_decode(json_encode($arrayfinal));
    $arrayfinal2 = array();
    unset($arrayfinal[$ultima]);
    foreach ($arrayfinal as $item) {

        if ($item != "") {

            array_push($arrayfinal2, $item);
        }
    }


    /* asigna un array y manipula parámetros para obtener tipos de bonos. */
    $arrayfinal = $arrayfinal2; //Array con todos los jugadores

    $ids = implode(",", $arrayfinal); //Ids concatenados por comas

    $TypeBonusDeposit = $params->TypeBonusDeposit;

    $Type = ($params->Type == '1') ? '1' : '0';


    /* asigna valores a variables relacionadas con tipos de bonificaciones y jugadores. */
    $tipobono = $TypeId;
    $cupo = 0;
    $cupoMaximo = 0;
    $jugadores = 0;
    $jugadoresMaximo = 0;

    if ($MaximumAmount != "" && $tipobono == 2) {
        $cupoMaximo = $MaximumAmount[0]->Amount;
    }


    /* Asigna valores a variables según condiciones específicas de entrada. */
    if ($MaxplayersCount != "" && $tipobono == 2) {
        $jugadoresMaximo = $MaxplayersCount;
    }

    if ($cupoMaximo == "") {
        $cupoMaximo = 0;
    }


    if (oldCount($DateProgram) > 0) {

        foreach ($DateProgram as $value) {

            /* Convierte fechas en formato correcto y crea un objeto "BonoInterno" con un nombre. */
            $StartDate = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $value->BeginDate)));
            $EndDate = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $value->EndDate)));


            $BonoInterno = new BonoInterno();

            $BonoInterno->nombre = $Name;

            /* Se asignan valores a propiedades del objeto BonoInterno en PHP. */
            $BonoInterno->descripcion = $Description;
            $BonoInterno->fechaInicio = $StartDate;
            $BonoInterno->fechaFin = $EndDate;
            $BonoInterno->tipo = $tipobono;
            $BonoInterno->estado = 'A';
            $BonoInterno->imagen = $MainImageURL;

            /* Código establece propiedades de un objeto "BonoInterno" con valores específicos. */
            $BonoInterno->usucreaId = 0;
            $BonoInterno->usumodifId = 0;
            $BonoInterno->mandante = $mandanteUsuario;
            $BonoInterno->condicional = $ConditionProduct;
            $BonoInterno->orden = $Priority;
            $BonoInterno->cupoActual = $cupo;

            /* Asigna valores a un objeto BonoInterno relacionado con una campaña. */
            $BonoInterno->cupoMaximo = $cupoMaximo;
            $BonoInterno->codigo = $CodeGlobal;
            $BonoInterno->cantidadBonos = $jugadores;
            $BonoInterno->maximoBonos = $jugadoresMaximo;
            $BonoInterno->categoriaCampaña = $CampaingCategory;
            $BonoInterno->detallesCampaña = $CampaingDetails;


            $BonoInterno->reglas = $RulesText;


            /* Establece valores a propiedades del objeto $BonoInterno según condiciones específicas. */
            if ($Type == '1') {
                $BonoInterno->publico = 'I';
            } else {
                $BonoInterno->publico = 'A';
            }
            if ($IsCRM != "") {

                $BonoInterno->perteneceCrm = 'S';
            } else {
                /* asigna 'N' a perteneceCrm si la condición previa no se cumple. */

                $BonoInterno->perteneceCrm = 'N';
            }

            /* Código para insertar un bono y su detalle en la base de datos, manejando expiración. */
            $BonoInterno->jsonTemp = json_encode($params->JsonOriginFront);// proposito: guardar un json con la informacion de un bono para luego poder retornarla al frontend, antes de guardarla en la BD la informacion se utiliza la funcion json_encode
            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();

            $bonoId = $BonoInterno->insert($transaccion);


//Expiracion

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


            /* inicializa un bono si no está definido, y crea un objeto BonoDetalle. */
            if ($UserRepeatBonus == "") {
                $UserRepeatBonus = 0;
            }
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "REPETIRBONO";

            /* Se inserta un registro de bono con valores y IDs de usuario en MySQL. */
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $UserRepeatBonus;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);


            /* Condición para crear un objeto de bono si hay lealtad establecida. */
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


            /* inserta un registro de detalles de bono si $IsForLanding no está vacío. */
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


            /* Inserta un nuevo registro de bono si la variable $IsRoulette tiene valor. */
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


            /* Insertar un registro de BonoDetalle si $UseFrozeWallet no está vacío. */
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


            /* Inserta detalles de bonificación para cada carton si hay una cantidad positiva. */
            if ($numberOfCartons->Amount > 0) {
                foreach ($numberOfCartons as $key => $value) {
                    $BonoDetalle = new BonoDetalle();
                    $BonoDetalle->bonoId = $bonoId;
                    $BonoDetalle->tipo = "NUMBERCARTONS";
                    $BonoDetalle->moneda = $value->CurrencyId;
                    $BonoDetalle->valor = $value->Amount;
                    $BonoDetalle->usucreaId = 0;
                    $BonoDetalle->usumodifId = 0;
                    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                    $BonoDetalleMysqlDAO->insert($BonoDetalle);
                }
            }


            /* Inserta un detalle de bono si se proporciona una supresión de retiro. */
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


            /* Inserta un detalle de bono en la base de datos si $ScheduleCount no está vacío. */
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


            /* Crea un nuevo detalle de bono si el nombre del horario no está vacío. */
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


            /* Inserta un nuevo registro de BonoDetalle si SchedulePeriod no está vacío. */
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


            /* Inserta un registro de bono si el tipo de período de programación no está vacío. */
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


            /* inserta un nuevo registro en BonoDetalle si $Count no está vacío. */
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


            /* Inserta un registro de BonoDetalle si $AreAllowed no está vacío. */
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


            /* Crea un registro de bono si la fecha de expiración no está vacía. */
            if ($ExpirationDate != "") {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "EXPFECHA";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $tipobono != 8 ? $ExpirationDate : $EndDate;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);
            }


            /* Inserta un nuevo detalle de bono si se cumplen ciertas condiciones. */
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


            /* Inserta un detalle de bono si el factor de bonificación no está vacío. */
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


            /* Inserta un registro de bono si $DepositWFactor no está vacío. */
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


            /* Inserta un registro de BonoDetalle si DepositNumber no está vacío. */
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


            /* Inserta un registro de bono con efectivo si proviene del escritorio de caja. */
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


            /* Inserta un registro de bono con el número máximo de jugadores si está definido. */
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


            /* Inserta un registro de BonoDetalle si $Prefix no está vacío. */
            if ($Prefix != "") {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "PREFIX";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $Prefix;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);
            }


            /* Inserta un detalle de bono si $WinBonusId es diferente de cero. */
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
                /* inserta un nuevo objeto BonoDetalle en la base de datos. */


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

            /* Crea y guarda detalles de bonos según condiciones de la variable $rounds. */
            if ($rounds == "" || $rounds == "0") {

                foreach ($rounds as $key => $value) {
                    $BonoDetalle = new BonoDetalle();
                    $BonoDetalle->bonoId = $bonoId;
                    $BonoDetalle->tipo = "CANROUNDS";
                    $BonoDetalle->moneda = $value->CurrencyId;
                    $BonoDetalle->valor = $value->Amount;
                    $BonoDetalle->usucreaId = 0;
                    $BonoDetalle->usumodifId = 0;
                    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                    $BonoDetalleMysqlDAO->insert($BonoDetalle);
                }


            }


            /* Inserta detalles del bono si no hay un identificador de bono ganador definido. */
            if ($WinBonusId == "" || $WinBonusId == "0") {

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
                /* Inserta detalles de bonos en la base de datos utilizando un bucle foreach. */


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


            if ($tipobono == "2") {


                /* Inserta detalles de bonos máximos de depósito en la base de datos. */
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


                /* Inserta detalles de bonos mínimos de depósito en la base de datos. */
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


            /* Inserts bonus details into the database if the deposit type is zero. */
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


            /* Inserta detalles de bonificación para cada sistema de pago en una base de datos. */
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


            /* Inserta detalles de bonos en la base de datos para diferentes regiones. */
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


            /* Inserta detalles de bonificaciones para cada departamento en la base de datos. */
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


            /* Inserta detalles de bonos en la base de datos para cada ciudad en el array. */
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


            /* Inserta un nuevo detalle de bono si el balance es cero. */
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


            /* Se crea un objeto "BonoDetalle" y se asignan sus propiedades. */
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "CONDPAISUSER";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $Country;
            $BonoDetalle->usucreaId = 0;

            /* Inserta un detalle de bono y asigna un país si está definido. */
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);
            $PaisId = $value;


            if ($Country != "") {
                $PaisId = $Country;
            } elseif ($PaisId == '') {
                /* Asigna el valor de la sesión a $PaisId si está vacío. */

                $PaisId = $_SESSION['pais_id'];
            } elseif ($_SESSION['pais_id'] == "") {
                /* Asigna a $PaisId el valor de 'PaisCond' si 'pais_id' está vacío. */

                $PaisId = $_SESSION['PaisCond'];
            }


            /* Crea e inserta detalles de bonos para usuarios de departamentos en una base de datos. */
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


            /* Crea e inserta detalles de bonos para cada ciudad del usuario. */
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


            /* Se inserta un BonoDetalle en cada iteración sobre CashDesks. */
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

            /* Inserta detalles de bono en base de datos para cada escritorio de caja. */
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

            /* Itera sobre juegos, crea registros de bonificaciones y los inserta en una base de datos. */
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


            /* Inserta detalles de bonos en la base de datos para cada categoría de casino. */
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

            foreach ($CasinoProvider as $key => $value) {

                /* Se crea un objeto BonoDetalle con datos específicos del bono y su valor. */
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "CONDSUBPROVIDER" . $value->Id;
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $value->Percentage;
                $BonoDetalle->usucreaId = 0;

                /* Se inserta un detalle de bono en la base de datos. */
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);

                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;

                /* Se inicializan propiedades de un objeto "BonoDetalle" para una transacción específica. */
                $BonoDetalle->tipo = "CODESUBPROVIDER";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $value->Id;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);

                /* Inserta un objeto de tipo BonoDetalle en la base de datos usando MySQL. */
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


            /* Inserta detalles de bono en base de datos para cada regla de Sports Bonus. */
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


            /* Inserta un registro de bono detallado si LiveOrPreMatch no está vacío. */
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


            /* Inserta un nuevo registro de BonoDetalle si el país no está vacío. */
            if ($Country != "") {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "CONDPAISUSER";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $Country;
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);
            }


            /* Inserta un nuevo detalle de bono si el precio mínimo es válido. */
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


            /* Se inserta un nuevo detalle de bono si el precio mínimo total no está vacío. */
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

                /* Crea y guarda un nuevo detalle de bono si MinAmount no está vacío. */
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

                /* Se inserta un registro de bono si MaxAmount no está vacío. */
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

                    /* Inserta un registro de bono si MinAmount no está vacío en la base de datos. */
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

                    /* Inserta un registro de bono si MaxAmount no está vacío. */
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


            /* Inserta un detalle de bono si se proporciona un ID de disparador y un código promocional. */
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

            /* Crea e inserta detalles de bonificación para campañas de marketing en la base de datos. */
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

            /* Crea un objeto BonoDetalle y lo inserta en la base de datos si se cumple la condición. */
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


            /* inserta un registro de bono si se cumple la condición de apuesta mínima. */
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


            /* Se asigna un ID de proveedor y se crea un objeto Subproveedor y Proveedor. */
            $idProveedor = $CasinoProvider[0]->Id;
            $SubProveedor = new \Backend\dto\Subproveedor($idProveedor);
            $Proveedor = new Proveedor($SubProveedor->proveedorId);

            $respuestaGeneral = "";

            $proveedoresExcluidos = array(
                "REDRAKE",
                "TOMHORN",
                "IESGAMES",
                "MASCOT",
                "SMARTSOFT",
                "7777GAMING",
                "KAGAMING",
                "BELATRA",
                "PLAYTECH",
                "PRAGMATIC",
                "AMIGOGAMING",
                "ENPH",
                "RUBYPLAY",
                "SOFTSWISS",
                "PGSOFT",
                "GAMESGLOBAL",
                "PLATIPUS",
                "PASCAL",
                "PLAYSON",
                "MASCOT",
                "TOMHORN",
                "CTGAMING",
                "PLAYNGO",
                "BOOMING",
                "EVOPLAY",
                "AMUSNET",
                "SPINOMENAL",
                "AIRDICE",
                "BRAGG",
                "LAMBDA",
                "TADAGAMING",
                "EXPANSE",
                "EGT",
                "ONLYPLAY",
                "MANCALA",
                "MERKUR",
                "GALAXSYS",
                "RAW",
                "RFRANCO"
            );

            if (!in_array($Proveedor->abreviado, $proveedoresExcluidos)) {

                /* Asignación de valor a una variable basada en condiciones de conteo y prefijo. */
                $darAUsuariosEspecificos = false;
                if (($MaxplayersCount != "" && $Prefix != "") || ($MaxplayersCount != "")) {
                    $darAUsuariosEspecificos = true;
                }

                if ($darAUsuariosEspecificos) {


                    /* Se declaran tres arrays vacíos para asignar y almacenar jugadores y códigos. */
                    $jugadoresAsignar = array();
                    $jugadoresAsignarFinal = array();
                    $codigosarray = array();
                    for ($i = 0; $i < $MaxplayersCount; $i++) {

                        /* Genera una clave de ticket única asegurándose que no esté en el array existente. */
                        $codigo = GenerarClaveTicket(4);

                        while (in_array($codigo, $codigosarray)) {
                            $codigo = GenerarClaveTicket(4);
                        }

                        $usuarioId = '0';


                        /* Inicializa variables en PHP para estado y valores numéricos. */
                        $estado = 'L';

                        $valor = '0';

                        $valor_bono = '0';

                        $valor_base = '0';


                        /* Variables inicializadas en PHP para manejar errores y datos externos. */
                        $errorId = '0';

                        $idExterno = '0';

                        $mandante = '0';


                        $usucreaId = '0';


                        /* Se inicializan variables para el usuario, la apuesta y el requisito de lanzamiento. */
                        $usumodifId = '0';


                        $apostado = '0';

                        $rollowerRequerido = '0';


                        /* asigna un bono a un usuario en un sistema. */
                        $codigo = $Prefix . $codigo;

                        $UsuarioBono = new UsuarioBono();

                        $UsuarioBono->setUsuarioId($usuarioId);
                        $UsuarioBono->setBonoId($bonoId);

                        /* Se configuran valores y estados para el objeto UsuarioBono. */
                        $UsuarioBono->setValor($valor);
                        $UsuarioBono->setValorBono($valor_bono);
                        $UsuarioBono->setValorBase($valor_base);
                        $UsuarioBono->setEstado($estado);
                        $UsuarioBono->setErrorId($errorId);
                        $UsuarioBono->setIdExterno($idExterno);

                        /* configura atributos de un objeto UsuarioBono con valores proporcionados. */
                        $UsuarioBono->setMandante($mandante);
                        $UsuarioBono->setUsucreaId($usucreaId);
                        $UsuarioBono->setUsumodifId($usumodifId);
                        $UsuarioBono->setApostado($apostado);
                        $UsuarioBono->setRollowerRequerido($rollowerRequerido);
                        $UsuarioBono->setCodigo($codigo);

                        /* Se configura un objeto y se inserta en la base de datos mediante DAO. */
                        $UsuarioBono->setVersion(0);
                        $UsuarioBono->setExternoId(0);

                        $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($transaccion);

                        $inse = $UsuarioBonoMysqlDAO->insert($UsuarioBono);


                        /* Asigna un código a jugadores y lo agrega a un array. */
                        if ($jugadoresAsignarFinal[$i]["Id"] != "") {

                            $jugadoresAsignarFinal[$i]["Code"] = $codigo;
                        }

                        array_push($codigosarray, $codigo);
                    }
                }
            }

            if ($tipobono == 8) {


                /* Asignación de valores a un nuevo objeto BonoDetalle relacionado con ronda gratis. */
                $roundsFree = $rounds[0]->Amount;
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $bonoId;
                $BonoDetalle->tipo = "ROUNDSFREE";
                $BonoDetalle->moneda = '';
                $BonoDetalle->valor = $roundsFree;

                /* Inserta detalles de bonos y valores de rondas en la base de datos. */
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
                $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                $BonoDetalleMysqlDAO->insert($BonoDetalle);

                if ($MaxPayout[0]->Amount > 0) {
                    $roundsValue = $MaxPayout[0]->Amount;
                    $BonoDetalle = new BonoDetalle();
                    $BonoDetalle->bonoId = $bonoId;
                    $BonoDetalle->tipo = "ROUNDSVALUE";
                    $BonoDetalle->moneda = '';
                    $BonoDetalle->valor = $roundsValue;
                    $BonoDetalle->usucreaId = 0;
                    $BonoDetalle->usumodifId = 0;
                    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                    $BonoDetalleMysqlDAO->insert($BonoDetalle);
                }


                /* Inserta detalles de bonos en la base de datos si hay cartones disponibles. */
                if ($numberOfCartons[0]->Amount > 0) {
                    foreach ($numberOfCartons as $key => $value) {
                        $BonoDetalle = new BonoDetalle();
                        $BonoDetalle->bonoId = $bonoId;
                        $BonoDetalle->tipo = "NUMBERCARTONS";
                        $BonoDetalle->moneda = $value->CurrencyId;
                        $BonoDetalle->valor = $value->Amount;
                        $BonoDetalle->usucreaId = 0;
                        $BonoDetalle->usumodifId = 0;
                        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                        $BonoDetalleMysqlDAO->insert($BonoDetalle);
                    }
                }


                /* Verifica y guarda la cantidad de días de un bonificador de giros gratis. */
                if ($FreeRoundsMaxDays[0]->Amount > 0) {
                    //cantidad de días que dura el freespin
                    foreach ($FreeRoundsMaxDays as $key => $value) {
                        $freeRoundsMaxDays = $value->Amount;
                    }
                    $roundsValue = $MaxPayout[0]->Amount;
                    $BonoDetalle = new BonoDetalle();
                    $BonoDetalle->bonoId = $bonoId;
                    $BonoDetalle->tipo = "freeRoundMaxDays";
                    $BonoDetalle->moneda = '';
                    $BonoDetalle->valor = $freeRoundsMaxDays;
                    $BonoDetalle->usucreaId = 0;
                    $BonoDetalle->usumodifId = 0;
                    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                    $BonoDetalleMysqlDAO->insert($BonoDetalle);
                }


                /* inserta un registro de BonoDetalle si GoldenChip no está vacío. */
                if ($params->GoldenChip != '') {
                    $GoldenChip = $params->GoldenChip;

                    $BonoDetalle = new BonoDetalle();
                    $BonoDetalle->bonoId = $bonoId;
                    $BonoDetalle->tipo = "GOLDENCHIP";
                    $BonoDetalle->moneda = '';
                    $BonoDetalle->valor = $GoldenChip;
                    $BonoDetalle->usucreaId = 0;
                    $BonoDetalle->usumodifId = 0;
                    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                    $BonoDetalleMysqlDAO->insert($BonoDetalle);
                }


                /* inserta un bono si el monto del TemplateCode no está vacío. */
                if ($params->TemplateCode[0]->Amount != '') {
                    $TemplateCode = $params->TemplateCode[0]->Amount;

                    $BonoDetalle = new BonoDetalle();
                    $BonoDetalle->bonoId = $bonoId;
                    $BonoDetalle->tipo = "TEMPLATECODE";
                    $BonoDetalle->moneda = '';
                    $BonoDetalle->valor = $TemplateCode;
                    $BonoDetalle->usucreaId = 0;
                    $BonoDetalle->usumodifId = 0;
                    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                    $BonoDetalleMysqlDAO->insert($BonoDetalle);
                }

                if ($params->Multiplier[0]->Amount != '') {
                    $Multiplier = $params->Multiplier[0]->Amount;

                    $BonoDetalle = new BonoDetalle();
                    $BonoDetalle->bonoId = $bonoId;
                    $BonoDetalle->tipo = "MULTIPLIER";
                    $BonoDetalle->moneda = '';
                    $BonoDetalle->valor = $Multiplier;
                    $BonoDetalle->usucreaId = 0;
                    $BonoDetalle->usumodifId = 0;
                    $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                    $BonoDetalleMysqlDAO->insert($BonoDetalle);
                }


                /* Confirma todos los cambios realizados en la transacción actual de la base de datos. */
                $transaccion->commit();

            }

            //Los tipos 2,6 hacen asignación masiva de bonos sin pasar por BonoInterno, desde la creación del cupo en la zona superior
            //El tipo 8 no se crea por este recurso

            /* cuenta elementos en un arreglo y establece variables para una asignación de bonificaciones. */
            $totalQueries = empty($arrayfinal) ? 0 : count($arrayfinal);
            $enableBonusesMassiveAssignationTypes = [8];
            $maxPlayersCoupons = $totalQueries;
            $notifyCrm = 1;

            if (in_array($tipobono, $enableBonusesMassiveAssignationTypes) && $totalQueries > 0 && $totalQueries < 10000) {
                /** Asignación masiva de bonos --Área de parametrización*/

                /* Configuración de parámetros para procesar usuarios en bloques, controlando ejecución y tiempo. */
                $usersPerQuery = 40; //Cuántos ID`s de usuarios se enviarán en cada ejecución (Exec)
                $execsPerBlockTime = 6; //Cuántos execs pueden estar procesándose a la vez (Un Bloque de execs)
                $secondsDifferencePerBlockTime = 5; //Cuánto tiempo (segundos) apartará a un bloque de execs de otro bloque de execs
                $currentExecPosition = 0;

                //Iterando usuarios por asignar
                $secondsForExecution = 0; //Representa cuántos segundos debe esperar el exec antes de ejecutarse

                /* Asignaciones consultadas y contador de ejecuciones en bloque inicializado a cero. */
                $queriedAssignations = $usersPerQuery; //Solicitudes ejecutadas
                $execsInBlockTime = 0; //Ejecuciones realizadas
                for ($sentQueries = 0; $sentQueries < $maxPlayersCoupons; $queriedAssignations += $usersPerQuery) {

                    /* Ajusta los intervalos de ejecución y controla el rango de consultas permitidas. */
                    if ($execsInBlockTime == $execsPerBlockTime) {
                        /** Definiendo un nuevo intervalo de espera para las próximas ejecuciones */
                        $secondsForExecution += $secondsDifferencePerBlockTime;
                        $execsInBlockTime = 0;
                    }

                    /** Verificando cuál es el rango de usuarios que pueden ser consultados */
                    if ($maxPlayersCoupons > $totalQueries) $maxPlayersCoupons = $totalQueries;

                    /* Es un código que limita asignaciones y define un rango para consultar usuarios. */
                    if ($queriedAssignations > $maxPlayersCoupons) $queriedAssignations = $maxPlayersCoupons;

                    //El rango de usuarios por consultar y verificar corresponde a $sentQueries (Inicio intervalo es inclusivo) hasta $queriedAssignations (Fin intervalo es exclusivo)
                    //[$sentQueries, $queriedAssignations)
                    $usersToSend = "";
                    $couponsCodesToSend = "";

                    /* Crea una lista de usuarios con sus códigos de cupón a partir de arrays. */
                    for ($playerIndex = $sentQueries; $playerIndex < $queriedAssignations; $playerIndex++) {
                        $playerId = null;
                        $couponCode = null;
                        $playerId = $arrayfinal[$playerIndex];
                        $couponCode = $codigosarray[$playerIndex] ?: "";
                        $userPlusCoupon = $playerId . "_" . $couponCode;
                        if (!empty($playerId) && is_numeric($playerId)) {
                            $usersToSend .= ($usersToSend != "" ? "," : "") . "{$userPlusCoupon}";
                        }
                    }


                    /** Validando si requiere notificar CRM */

                    /* Condicional que desactiva notificación CRM y establece la ruta de PHP. */
                    if ($notifyCrm == 1 && $IsCRM == "") $notifyCrm = 0;


                    /** Ejecutando acreditacion en segundo plano */
                    /** Ejecutando acreditacion en segundo plano */
                    $phpPath = PHP_BINDIR . "/php"; //Ubicación PHP en linux

                    /* Ejecuta un script PHP en segundo plano con parámetros específicos para crear bonos. */
                    $execMode = "> /dev/null & "; //Este modo solicita la ejecución en 2do plano y sigue con el script
                    // $execMode = "2>&1 "; //Este modo espera la ejecución del recurso y el retorno de la respuesta

                    /** argv`s 1=Segundos espera 2=bonoId 3=tipobono 4=Jugadores separados por comas */
                    $currentExecPosition ++; //Indicando la posición del exec entre 1 hasta el total de ejecuciones realizadas

                    $redisParam = ['ex' => 18000];

                    $redisPrefix = "F100BACK+COMMANDEXEC+UID+".$secondsForExecution."+".$bonoId."+".$tipobono.'+'.$execsInBlockTime;

                    $redis = RedisConnectionTrait::getRedisInstance(true);

                    if($redis != null) {

                        $redis->set($redisPrefix, base64_encode($phpPath . " -f " . __DIR__ . "/CreateBonusExec.php '$secondsForExecution' '$bonoId' '$tipobono' '$usersToSend' '$notifyCrm' '$currentExecPosition' " . $execMode), $redisParam);
                    }else{
                        exec($phpPath . " -f " . __DIR__ . "/CreateBonusExec.php '$secondsForExecution' '$bonoId' '$tipobono' '$usersToSend' '$notifyCrm' '$currentExecPosition' " . $execMode);
                    }


                    $notifyCrm = 0;


                    //--End Function
                    $sentQueries = $queriedAssignations; //Actualizando último usuario asignado
                    $execsInBlockTime++; //Suma una nueva ejecución al bloque
                }
            }


            //Emvío campaña a optimove
            if ($BonoInterno->perteneceCrm == 'S') {
                try {

                    /* Se crean instancias de clases para gestión de clasificador y proveedores. */
                    $Clasificador = new Clasificador("", "PROVCRM");
                    $MandanteDetalle = new MandanteDetalle('', $mandanteUsuario, $Clasificador->clasificadorId, $PaisId, 'A');
                    $Proveedor = new Proveedor($MandanteDetalle->getValor());

                    switch ($Proveedor->abreviado) {
                        case "OPTIMOVE":
                            /* Código que procesa promociones usando la clase Optimove y datos proporcionados. */


                            $BonoId = "B" . $bonoId;
                            $Optimove = new Optimove();
                            $respon = $Optimove->AddPromotions($BonoId, $Description, $mandanteUsuario, $PaisId);
                            break;
                        case "FASTTRACK":
                            /* Crea instancias de clasificador, mandante y proveedor según el caso "FASTTRACK". */

                            $Clasificador = new Clasificador("", "PROVCRM");

                            $MandanteDetalle = new MandanteDetalle('', $mandanteUsuario, $Clasificador->clasificadorId, $PaisId, 'A');

                            $Proveedor = new Proveedor($MandanteDetalle->valor);

                            break;
                        case "CRMPROPIO":
                            /* Se crea un clasificador y un proveedor basado en detalles de un mandante. */

                            $Clasificador = new Clasificador("", "PROVCRM");

                            $MandanteDetalle = new MandanteDetalle('', $mandanteUsuario, $Clasificador->clasificadorId, $PaisId, 'A');

                            $Proveedor = new Proveedor($MandanteDetalle->valor);
                            break;
                    }
                } catch (Exception $e) {
                    /* Bloque que captura excepciones en PHP, pero no realiza ninguna acción al ocurrir. */

                }
            }


            /* define una respuesta en formato JSON con información de un bono. */
            $response["idBonus"] = $bonoId;
            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = $respuestaGeneral; //variable de moneda concatenado
            $response["detail_response"] = "";
            $response["ModelErrors"] = [];

            /* Inicializa un array vacío llamado "Result" dentro de la variable $response. */
            $response["Result"] = array();
            return $response;
        }
    } else {
        /* gestiona una respuesta de error con información específica. */


        $response["idBonus"] = 0;
        $response["HasError"] = true;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = "";
        $response["detail_response"] = "";
        $response["ModelErrors"] = [];
        $response["Result"] = array();
    }

} catch (Exception $e) {
    /* Maneja excepciones estableciendo un mensaje de error en la respuesta. */


    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = "";
    $response["detail_response"] = "";
    $response["ModelErrors"] = [];
    $response["Result"] = array();
}
